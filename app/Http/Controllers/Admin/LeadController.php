<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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

        return view('admin.leads.index', compact('leads', 'sourceCounts'));
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

            Lead::where('on_waitlist', true)
                ->with('waitlistProduct')
                ->orderBy('created_at')
                ->chunk(500, function ($leads) use ($handle) {
                    foreach ($leads as $i => $lead) {
                        fputcsv($handle, [
                            $lead->id,
                            $lead->email,
                            $lead->first_name ?? '',
                            $lead->last_name  ?? '',
                            $lead->waitlist_product_id ?? '',
                            $lead->preferred_language ?? '',
                            $lead->waitlist_notified_at ? $lead->waitlist_notified_at->format('Y-m-d') : 'No',
                            $i + 1,
                            $lead->created_at->format('Y-m-d H:i:s'),
                        ]);
                    }
                });

            fclose($handle);
        }, 'ferro_waitlist_' . now()->format('Y-m-d') . '.csv', $headers);
    }
}
