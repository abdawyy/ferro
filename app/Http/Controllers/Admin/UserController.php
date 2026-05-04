<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Password;
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

    public function makeAdmin(User $user): RedirectResponse
    {
        if ($user->is_admin) {
            return back()->with('success', 'This account is already an administrator.');
        }

        $user->update(['is_admin' => true]);

        return back()->with('success', "{$user->name} is now an administrator.");
    }

    public function removeAdmin(Request $request, User $user): RedirectResponse
    {
        if (! $user->is_admin) {
            return back()->with('success', 'This account is not an administrator.');
        }

        if ($user->id === $request->user()->id) {
            return back()->with('error', 'You cannot remove your own administrator access.');
        }

        if (User::where('is_admin', true)->count() <= 1) {
            return back()->with('error', 'Cannot remove the last administrator.');
        }

        $user->update(['is_admin' => false]);

        return back()->with('success', "{$user->name} is no longer an administrator.");
    }

    public function admins(): View
    {
        $admins = User::query()
            ->where('is_admin', true)
            ->orderBy('name')
            ->paginate(30);

        return view('admin.admins.index', compact('admins'));
    }

    public function createAdmin(): View
    {
        return view('admin.admins.create');
    }

    public function storeAdmin(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::min(10)],
        ]);

        User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'password' => $validated['password'],
            'is_admin' => true,
        ]);

        return redirect()
            ->route('admin.admins.index')
            ->with('success', 'Administrator account created for ' . $validated['email'] . '.');
    }
}
