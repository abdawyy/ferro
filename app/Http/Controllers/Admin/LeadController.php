<?php

namespace App\Http\Controllers\Admin;

use App\Events\LeadRegistered;
use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Product;
use App\Models\WaitlistEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\StreamedResponse;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $query = Lead::query();

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('email', 'like', "%{$search}%")
                  ->orWhere('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%");
            });
        }

        if ($source = $request->input('source')) {
            $query->where('source', $source);
        }
        if ($priority = $request->input('priority')) {
            $query->where('priority', $priority);
        }
        if ($request->input('waitlist_only')) {
            $query->where('on_waitlist', true);
        }

        $leads = $query->latest()->paginate(25)->withQueryString();

        $sourceCounts = Lead::selectRaw('source, count(*) as total')
            ->groupBy('source')
            ->pluck('total', 'source');

        $products = Product::query()
            ->where('status', '!=', Product::STATUS_ARCHIVED)
            ->orderBy('sku')
            ->get(['id', 'sku', 'name']);

        return view('admin.leads.index', compact('leads', 'sourceCounts', 'products'));
    }

    /**
     * POST /admin/leads/waitlist — add or refresh a waitlist lead (manual entry).
     */
    public function storeWaitlist(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'email'               => ['required', 'email', 'max:255'],
            'first_name'          => ['nullable', 'string', 'max:100'],
            'last_name'           => ['nullable', 'string', 'max:100'],
            'product_id'          => ['nullable', 'exists:products,id'],
            'preferred_language'  => ['nullable', Rule::in(['en', 'ar'])],
            'marketing_consent'   => ['nullable', 'boolean'],
            'send_welcome_email'  => ['nullable', 'boolean'],
        ]);

        $lead = Lead::updateOrCreate(
            ['email' => $validated['email']],
            [
                'first_name'          => $validated['first_name'] ?? null,
                'last_name'           => $validated['last_name'] ?? null,
                'source'              => Lead::SOURCE_WAITLIST,
                'status'              => Lead::STATUS_NEW,
                'preferred_language'  => $validated['preferred_language'] ?? 'en',
                'on_waitlist'         => true,
                'waitlist_product_id' => $validated['product_id'] ?? null,
                'marketing_consent'   => $request->boolean('marketing_consent'),
                'gdpr_consent'        => true,
                'consented_at'        => now(),
                'ip_address'          => $request->ip(),
                'utm_data'            => array_filter([
                    'utm_source'       => 'admin_portal',
                    'admin_user_id'  => (string) auth()->id(),
                    'admin_added_at' => now()->toIso8601String(),
                ]),
            ]
        );

        if (! empty($validated['product_id'])) {
            $pid = (int) $validated['product_id'];
            $entry = WaitlistEntry::firstOrNew([
                'product_id' => $pid,
                'email'      => $validated['email'],
            ]);
            if (! $entry->exists) {
                $entry->position = WaitlistEntry::where('product_id', $pid)->count() + 1;
            }
            $entry->fill([
                'lead_id'            => $lead->id,
                'preferred_language' => $validated['preferred_language'] ?? 'en',
            ]);
            $entry->save();
        }

        if ($request->boolean('send_welcome_email')) {
            LeadRegistered::dispatch($lead);
        }

        return redirect()
            ->route('admin.leads.index', ['waitlist_only' => 1, 'source' => Lead::SOURCE_WAITLIST])
            ->with('success', 'Waitlist entry saved for ' . $validated['email'] . '.');
    }

    /**
     * Export ALL leads to CSV / Excel-compatible file.
     */
    public function export(Request $request): StreamedResponse
    {
        $query = Lead::query();
        if ($source = $request->input('source')) {
            $query->where('source', $source);
        }

        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="ferro_leads_' . now()->format('Y-m-d') . '.csv"',
            'Pragma'              => 'no-cache',
            'Cache-Control'       => 'must-revalidate, post-check=0, pre-check=0',
            'Expires'             => '0',
        ];

        return response()->streamDownload(function () use ($query) {
            $handle = fopen('php://output', 'w');

            // UTF-8 BOM for Excel
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'ID', 'Email', 'First Name', 'Last Name', 'Phone',
                'Source', 'Priority', 'Status', 'Language',
                'On Waitlist', 'Waitlist Product ID',
                'Marketing Consent', 'Engagement Score',
                'Converted', 'Created At',
            ]);

            $query->chunk(500, function ($leads) use ($handle) {
                foreach ($leads as $lead) {
                    fputcsv($handle, [
                        $lead->id,
                        $lead->email,
                        $lead->first_name ?? '',
                        $lead->last_name  ?? '',
                        $lead->phone      ?? '',
                        $lead->source     ?? '',
                        $lead->priority   ?? '',
                        $lead->status     ?? '',
                        $lead->preferred_language ?? '',
                        $lead->on_waitlist ? 'Yes' : 'No',
                        $lead->waitlist_product_id ?? '',
                        $lead->marketing_consent ? 'Yes' : 'No',
                        $lead->engagement_score ?? 0,
                        $lead->converted_at ? 'Yes' : 'No',
                        $lead->created_at->format('Y-m-d H:i:s'),
                    ]);
                }
            });

            fclose($handle);
        }, 'ferro_leads_' . now()->format('Y-m-d') . '.csv', $headers);
    }

    /**
     * Export only waitlist leads to CSV.
     */
    public function exportWaitlist(): StreamedResponse
    {
        $headers = [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="ferro_waitlist_' . now()->format('Y-m-d') . '.csv"',
        ];

        return response()->streamDownload(function () {
            $handle = fopen('php://output', 'w');
            fwrite($handle, "\xEF\xBB\xBF");

            fputcsv($handle, [
                'ID', 'Email', 'First Name', 'Last Name',
                'Waitlist Product ID', 'Language',
                'Waitlist Notified', 'Position', 'Created At',
            ]);

            $position = 0;
            Lead::where('on_waitlist', true)
                ->with('waitlistProduct')
                ->orderBy('created_at')
                ->chunk(500, function ($leads) use ($handle, &$position) {
                    foreach ($leads as $lead) {
                        $position++;
                        fputcsv($handle, [
                            $lead->id,
                            $lead->email,
                            $lead->first_name ?? '',
                            $lead->last_name  ?? '',
                            $lead->waitlist_product_id ?? '',
                            $lead->preferred_language ?? '',
                            $lead->waitlist_notified_at ? $lead->waitlist_notified_at->format('Y-m-d') : 'No',
                            $position,
                            $lead->created_at->format('Y-m-d H:i:s'),
                        ]);
                    }
                });

            fclose($handle);
        }, 'ferro_waitlist_' . now()->format('Y-m-d') . '.csv', $headers);
    }
}
