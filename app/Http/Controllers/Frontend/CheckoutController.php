<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    public function __construct(private CartService $cart)
    {
    }

    public function index(Request $request): View|RedirectResponse
    {
        if ($this->cart->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('frontend.checkout.index', [
            'items' => $this->cart->items(),
            'summary' => $this->cart->summary(),
            'addresses' => $request->user()->addresses()->latest()->get(),
        ]);
    }
}
