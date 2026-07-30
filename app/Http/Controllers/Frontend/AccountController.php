<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class AccountController extends Controller
{
    /**
     * Account overview dashboard.
     */
    public function dashboard(Request $request): View
    {
        $user = $request->user();

        return view('frontend.account.dashboard', [
            'ordersCount' => $user->orders()->count(),
            'wishlistCount' => $user->wishlists()->count(),
            'addressCount' => $user->addresses()->count(),
            'recentOrders' => $user->orders()->latest()->take(5)->get(),
        ]);
    }

    public function orders(Request $request): View
    {
        $orders = $request->user()->orders()->latest()->paginate(10);

        return view('frontend.account.orders', compact('orders'));
    }

    public function showOrder(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        $order->load('items', 'payment');

        return view('frontend.account.order-show', compact('order'));
    }

    public function profile(Request $request): View
    {
        return view('frontend.account.profile', ['user' => $request->user()]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'email' => ['required', 'email', Rule::unique('users', 'email')->ignore($user->id)],
        ]);

        $user->update($data);

        return back()->with('success', 'Profile updated.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', 'confirmed', 'min:8'],
        ]);

        $request->user()->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password changed.');
    }
}
