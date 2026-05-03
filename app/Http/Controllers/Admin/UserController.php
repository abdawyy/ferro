<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::where('is_admin', false)->withCount('orders');

        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_blocked', $request->input('status') === 'blocked');
        }

        $users = $query->latest()->paginate(25)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        $orders = $user->orders()->latest()->paginate(10);
        return view('admin.users.show', compact('user', 'orders'));
    }

    public function block(Request $request, User $user): RedirectResponse
    {
        $request->validate(['reason' => 'nullable|string|max:500']);

        $user->update([
            'is_blocked'     => true,
            'blocked_reason' => $request->input('reason', 'Blocked by administrator.'),
            'blocked_at'     => now(),
        ]);

        return back()->with('success', "Account for {$user->name} has been blocked.");
    }

    public function unblock(User $user): RedirectResponse
    {
        $user->update([
            'is_blocked'     => false,
            'blocked_reason' => null,
            'blocked_at'     => null,
        ]);

        return back()->with('success', "Account for {$user->name} has been unblocked.");
    }
}
