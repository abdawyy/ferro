<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\View\View;

class OrderTrackingController extends Controller
{
    /**
     * Signed, login-free order status page (linked from transactional emails).
     */
    public function show(Order $order): View
    {
        $order->load(['items.product']);

        return view('orders.track', compact('order'));
    }
}
