<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class OrderService
{
    public function __construct(private CartService $cart)
    {
    }

    /**
     * Create an order from the current cart. Runs in a transaction:
     * builds line snapshots, reduces stock, bumps coupon usage, clears cart.
     *
     * @param  array  $billing   Billing address snapshot
     * @param  array  $shipping  Shipping address snapshot
     */
    public function createFromCart(int $userId, array $billing, array $shipping, ?string $notes = null): Order
    {
        return DB::transaction(function () use ($userId, $billing, $shipping, $notes) {
            $items = $this->cart->items();

            if ($items->isEmpty()) {
                throw new \RuntimeException('Cart is empty.');
            }

            $summary = $this->cart->summary();

            $order = Order::create([
                'order_number' => $this->generateOrderNumber(),
                'user_id' => $userId,
                'coupon_id' => $summary['coupon']?->id,
                'subtotal' => $summary['subtotal'],
                'discount' => $summary['discount'],
                'tax' => $summary['tax'],
                'shipping' => $summary['shipping'],
                'total' => $summary['total'],
                'payment_status' => 'pending',
                'status' => 'pending',
                'billing_address' => $billing,
                'shipping_address' => $shipping,
                'notes' => $notes,
                'placed_at' => now(),
            ]);

            foreach ($items as $line) {
                $product = $line->product;

                $order->items()->create([
                    'product_id' => $product?->id,
                    'product_variant_id' => $line->product_variant_id,
                    'product_name' => $product?->name ?? 'Product',
                    'sku' => $product?->sku,
                    'variant_label' => $line->variant?->label,
                    'price' => $line->price,
                    'quantity' => $line->quantity,
                    'subtotal' => $line->price * $line->quantity,
                ]);

                $this->reduceStock($line->product_id, $line->product_variant_id, $line->quantity);
            }

            // Bump coupon usage.
            if ($summary['coupon']) {
                $summary['coupon']->increment('used_count');
            }

            $this->cart->clear();
            $this->cart->removeCoupon();

            return $order;
        });
    }

    /**
     * Decrement product (and variant) stock, never below zero.
     */
    private function reduceStock(?int $productId, ?int $variantId, int $qty): void
    {
        if ($productId) {
            Product::where('id', $productId)->where('stock', '>=', $qty)->decrement('stock', $qty);
        }

        if ($variantId) {
            DB::table('product_variants')->where('id', $variantId)
                ->where('stock', '>=', $qty)->decrement('stock', $qty);
        }
    }

    private function generateOrderNumber(): string
    {
        return 'RC-'.now()->format('Ymd').'-'.strtoupper(Str::random(5));
    }
}
