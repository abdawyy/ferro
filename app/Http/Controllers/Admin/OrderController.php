<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\InvoiceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class OrderController extends Controller
{
    public function index(Request $request): View
    {
        $query = Order::with(['user', 'lead'])->latest();

        if ($search = $request->input('search')) {
            $like = '%'.addcslashes($search, '%_\\').'%';
            $query->where(function ($q) use ($like) {
                $q->where('order_number', 'like', $like)
                    ->orWhereHas('user', fn ($u) => $u->where('name', 'like', $like)
                        ->orWhere('email', 'like', $like))
                    ->orWhereHas('lead', fn ($l) => $l->where('email', 'like', $like)
                        ->orWhere('first_name', 'like', $like)
                        ->orWhere('last_name', 'like', $like))
                    ->orWhere('billing_address->email', 'like', $like)
                    ->orWhere('billing_address->first_name', 'like', $like)
                    ->orWhere('billing_address->last_name', 'like', $like);
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        if ($payment = $request->input('payment_status')) {
            $query->where('payment_status', $payment);
        }

        $orders = $query->paginate(20)->withQueryString();

        $statusCounts = Order::selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('admin.orders.index', compact('orders', 'statusCounts'));
    }

    public function show(Order $order): View
    {
        $order->load(['user', 'lead', 'items.product']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order): RedirectResponse
    {
        $request->validate([
            'status'          => 'required|in:pending_payment,confirmed,processing,shipped,delivered,cancelled,refunded',
            'tracking_number' => 'nullable|string|max:255',
            'carrier'         => 'nullable|string|max:100',
            'admin_notes'     => 'nullable|string|max:1000',
        ]);

        $data = ['status' => $request->input('status')];

        if ($request->filled('tracking_number')) {
            $data['tracking_number'] = $request->input('tracking_number');
        }
        if ($request->filled('carrier')) {
            $data['carrier'] = $request->input('carrier');
        }
        if ($request->filled('admin_notes')) {
            $data['admin_notes'] = $request->input('admin_notes');
        }
        if ($request->input('status') === 'shipped' && ! $order->shipped_at) {
            $data['shipped_at'] = now();
        }
        if ($request->input('status') === 'delivered' && ! $order->delivered_at) {
            $data['delivered_at'] = now();
        }

        $order->update($data);

        return back()->with('success', "Order #{$order->order_number} status updated to {$data['status']}.");
    }

    public function downloadInvoice(Order $order): SymfonyResponse
    {
        $order->refresh();

        // Always regenerate so template/language fixes apply (old PDFs were cached on disk).
        try {
            $path = app(InvoiceService::class)->generate($order->fresh(['items', 'user', 'lead']));
            $order->update(['invoice_pdf_path' => $path]);
            $order->refresh();
        } catch (\Throwable $e) {
            return back()->with('error', 'Invoice could not be generated: ' . $e->getMessage());
        }

        $absolute = Storage::disk('local')->path($order->invoice_pdf_path);

        return response()->download(
            $absolute,
            "FERRO_Invoice_{$order->invoice_number}.pdf",
            ['Content-Type' => 'application/pdf']
        );
    }
}
