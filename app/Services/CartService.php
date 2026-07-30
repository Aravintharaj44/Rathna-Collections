<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\ProductVariant;
use App\Models\Setting;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;

/**
 * Cart handling for both guests (session token) and logged-in users.
 */
class CartService
{
    /**
     * Add a product (optionally a variant) to the cart, merging quantity
     * if the same line already exists.
     */
    public function add(Product $product, ?int $variantId, int $quantity = 1): Cart
    {
        $variant = $variantId ? ProductVariant::find($variantId) : null;
        $price = $product->final_price + ($variant?->additional_price ?? 0);

        $line = $this->query()
            ->where('product_id', $product->id)
            ->where('product_variant_id', $variantId)
            ->first();

        if ($line) {
            $line->increment('quantity', $quantity);
            $line->update(['price' => $price]);

            return $line;
        }

        return Cart::create(array_merge($this->owner(), [
            'product_id' => $product->id,
            'product_variant_id' => $variantId,
            'quantity' => max(1, $quantity),
            'price' => $price,
        ]));
    }

    public function update(Cart $line, int $quantity): void
    {
        if ($quantity <= 0) {
            $line->delete();

            return;
        }

        $line->update(['quantity' => $quantity]);
    }

    public function remove(Cart $line): void
    {
        $line->delete();
    }

    public function clear(): void
    {
        $this->query()->delete();
    }

    /**
     * @return Collection<int, Cart>
     */
    public function items(): Collection
    {
        return $this->query()->with(['product', 'variant'])->get();
    }

    public function count(): int
    {
        return (int) $this->query()->sum('quantity');
    }

    public function subtotal(): float
    {
        return (float) $this->items()->sum(fn (Cart $line) => $line->price * $line->quantity);
    }

    /**
     * Full money breakdown, factoring an optionally applied coupon.
     */
    public function summary(): array
    {
        $subtotal = $this->subtotal();
        $discount = 0;
        $coupon = $this->appliedCoupon();

        if ($coupon && $coupon->isValid()) {
            $discount = $coupon->discountFor($subtotal);
        }

        $taxable = max(0, $subtotal - $discount);
        $taxPercent = (float) Setting::get('tax_percent', 0);
        $tax = round($taxable * $taxPercent / 100, 2);

        $shipping = $this->shippingCharge($subtotal);
        $total = round($taxable + $tax + $shipping, 2);

        return compact('subtotal', 'discount', 'tax', 'shipping', 'total', 'coupon');
    }

    public function shippingCharge(float $subtotal): float
    {
        $flat = (float) Setting::get('shipping_charge', 0);
        $freeAbove = (float) Setting::get('free_shipping_above', 0);

        if ($subtotal <= 0) {
            return 0;
        }

        if ($freeAbove > 0 && $subtotal >= $freeAbove) {
            return 0;
        }

        return $flat;
    }

    // ----- Coupon session handling --------------------------------------

    public function applyCoupon(Coupon $coupon): void
    {
        Session::put('coupon_id', $coupon->id);
    }

    public function removeCoupon(): void
    {
        Session::forget('coupon_id');
    }

    public function appliedCoupon(): ?Coupon
    {
        $id = Session::get('coupon_id');

        return $id ? Coupon::find($id) : null;
    }

    /**
     * Merge a guest cart into the user's cart after login.
     */
    public function mergeGuestCartToUser(int $userId): void
    {
        $token = Session::get('cart_token');
        if (! $token) {
            return;
        }

        Cart::where('session_id', $token)->update([
            'user_id' => $userId,
            'session_id' => null,
        ]);

        Session::forget('cart_token');
    }

    // ----- Internals -----------------------------------------------------

    private function query()
    {
        return Cart::query()->where($this->owner());
    }

    /**
     * The owning key: user_id for authenticated users, else a session token.
     */
    private function owner(): array
    {
        if (Auth::check()) {
            return ['user_id' => Auth::id()];
        }

        if (! Session::has('cart_token')) {
            Session::put('cart_token', (string) Str::uuid());
        }

        return ['session_id' => Session::get('cart_token')];
    }
}
