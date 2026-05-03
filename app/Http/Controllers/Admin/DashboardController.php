<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_orders'    => Order::count(),
            'revenue'         => Order::whereIn('status', ['confirmed','processing','shipped','delivered'])->sum('total'),
            'total_customers' => User::where('is_admin', false)->count(),
            'total_leads'     => Lead::count(),
            'waitlist_total'  => Lead::where('on_waitlist', true)->count(),
            'pending_orders'  => Order::where('status', 'pending_payment')->count(),
            'low_stock'       => Product::where('status', 'active')
                                    ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
                                    ->count(),
            'blocked_users'   => User::where('is_blocked', true)->count(),
        ];

        $recentOrders = Order::with('user')
            ->latest()
            ->limit(8)
            ->get();

        $lowStockProducts = Product::where('status', 'active')
            ->whereColumn('stock_quantity', '<=', 'low_stock_threshold')
            ->orderBy('stock_quantity')
            ->limit(6)
            ->get();

        $recentLeads = Lead::latest()->limit(6)->get();

        // Revenue last 7 days (for chart)
        $dailyRevenue = Order::selectRaw('DATE(created_at) as date, SUM(total) as total')
            ->where('created_at', '>=', now()->subDays(6)->startOfDay())
            ->whereIn('status', ['confirmed','processing','shipped','delivered'])
            ->groupByRaw('DATE(created_at)')
            ->orderBy('date')
            ->pluck('total', 'date');

        return view('admin.dashboard', compact(
            'stats', 'recentOrders', 'lowStockProducts', 'recentLeads', 'dailyRevenue'
        ));
    }
}
