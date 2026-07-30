<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Product;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    public function __construct(private CartService $cart)
    {
    }

    public function index(): View
    {
        return view('frontend.cart.index', [
            'items' => $this->cart->items(),
            'summary' => $this->cart->summary(),
        ]);
    }

    public function store(Request $request, Product $product): RedirectResponse
    {
        $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1', 'max:99'],
            'variant_id' => ['nullable', 'exists:product_variants,id'],
        ]);

        $this->cart->add($product, $request->variant_id, (int) $request->input('quantity', 1));

        return back()->with('success', "{$product->name} added to cart.");
    }

    public function update(Request $request, Cart $item): RedirectResponse
    {
        $this->authorizeLine($item);
        $request->validate(['quantity' => ['required', 'integer', 'min:0', 'max:99']]);

        $this->cart->update($item, (int) $request->quantity);

        return back()->with('success', 'Cart updated.');
    }

    public function destroy(Cart $item): RedirectResponse
    {
        $this->authorizeLine($item);
        $this->cart->remove($item);

        return back()->with('success', 'Item removed.');
    }

    public function applyCoupon(Request $request): RedirectResponse
    {
        $request->validate(['code' => ['required', 'string']]);

        $coupon = Coupon::where('code', strtoupper(trim($request->code)))->first();

        if (! $coupon || ! $coupon->isValid()) {
            return back()->with('error', 'Invalid or expired coupon.');
        }

        if ($this->cart->subtotal() < (float) $coupon->min_purchase) {
            return back()->with('error', 'Cart total does not meet the coupon minimum.');
        }

        $this->cart->applyCoupon($coupon);

        return back()->with('success', "Coupon {$coupon->code} applied.");
    }

    public function removeCoupon(): RedirectResponse
    {
        $this->cart->removeCoupon();

        return back()->with('success', 'Coupon removed.');
    }

    /**
     * Ensure the cart line belongs to the current owner (user or session).
     */
    private function authorizeLine(Cart $item): void
    {
        $ownItems = $this->cart->items()->pluck('id');
        abort_unless($ownItems->contains($item->id), 403);
    }
}
