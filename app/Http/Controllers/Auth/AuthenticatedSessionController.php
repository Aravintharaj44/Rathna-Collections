<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Show the login form.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Authenticate and route the user to the right dashboard by role.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Move any guest cart items onto the now-authenticated user.
        app(CartService::class)->mergeGuestCartToUser(Auth::id());

        // Admins land in the admin panel, customers on the storefront.
        $redirect = Auth::user()->isAdmin()
            ? route('admin.dashboard')
            : route('home');

        return redirect()->intended($redirect);
    }

    /**
     * Log the user out and invalidate the session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home');
    }
}
