<?php

namespace App\Http\Controllers;

use App\Events\OrderStatusChanged;
use App\Models\Order;
use App\Models\OrderReturnRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CustomerOrderActionController extends Controller
{
    public function cancel(Request $request, string $orderNumber): RedirectResponse
    {
        $order = Order::query()
            ->where('order_number', $orderNumber)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        $allowed = in_array($order->status, [
            Order::STATUS_PENDING,
            Order::STATUS_CONFIRMED,
            Order::STATUS_PROCESSING,
        ], true);

        if (! $allowed) {
            return back()->with('error', __('This order can no longer be cancelled online. Please contact support.'));
        }

        $previous = $order->status;
        $order->update(['status' => Order::STATUS_CANCELLED]);

        event(new OrderStatusChanged($order->fresh(['items', 'user', 'lead']), $previous, Order::STATUS_CANCELLED));

        return back()->with('success', __('Your order has been cancelled.'));
    }

    public function requestReturn(Request $request, string $orderNumber): RedirectResponse
    {
        $order = Order::query()
            ->where('order_number', $orderNumber)
            ->where('user_id', $request->user()->id)
            ->firstOrFail();

        if ($order->status !== Order::STATUS_DELIVERED) {
            return back()->with('error', __('Returns can only be requested after your order is delivered.'));
        }

        $validated = $request->validate([
            'customer_reason' => ['required', 'string', 'max:2000'],
        ]);

        $pending = OrderReturnRequest::query()
            ->where('order_id', $order->id)
            ->where('status', OrderReturnRequest::STATUS_PENDING)
            ->exists();

        if ($pending) {
            return back()->with('error', __('You already have a return request pending for this order.'));
        }

        OrderReturnRequest::create([
            'order_id' => $order->id,
            'user_id' => $request->user()->id,
            'status' => OrderReturnRequest::STATUS_PENDING,
            'customer_reason' => $validated['customer_reason'],
        ]);

        return back()->with('success', __('We received your return request. Our team will email you with next steps.'));
    }
}
