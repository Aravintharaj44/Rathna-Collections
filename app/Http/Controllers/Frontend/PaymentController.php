<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Services\CartService;
use App\Services\OrderService;
use App\Services\RazorpayService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function __construct(
        private CartService $cart,
        private OrderService $orders,
        private RazorpayService $razorpay,
    ) {
    }

    /**
     * Handle the checkout form: capture addresses, then either place a COD
     * order immediately or hand off to Razorpay.
     */
    public function place(Request $request): View|RedirectResponse
    {
        $data = $this->validated($request);

        if ($this->cart->count() === 0) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        [$billing, $shipping] = $this->addresses($data);

        // Cash on delivery — create the order right away.
        if ($data['payment_method'] === 'cod') {
            $order = $this->orders->createFromCart($request->user()->id, $billing, $shipping, $data['notes'] ?? null);
            $order->update(['status' => 'confirmed']);
            $this->sendConfirmation($order);

            return redirect()->route('checkout.success', $order)->with('success', 'Order placed successfully!');
        }

        // Razorpay flow.
        if (! $this->razorpay->isConfigured()) {
            return back()->with('error', 'Online payment is not configured. Please choose Cash on Delivery.')->withInput();
        }

        $summary = $this->cart->summary();
        $rzpOrder = $this->razorpay->createOrder($summary['total'], 'rcpt_'.uniqid());

        // Stash the addresses to rebuild the order after payment succeeds.
        session([
            'checkout' => compact('billing', 'shipping') + ['notes' => $data['notes'] ?? null],
        ]);

        return view('frontend.checkout.pay', [
            'razorpayKey' => $this->razorpay->key(),
            'rzpOrder' => $rzpOrder,
            'amount' => $summary['total'],
            'user' => $request->user(),
        ]);
    }

    /**
     * Razorpay success callback: verify the signature, then create the order.
     */
    public function verify(Request $request): RedirectResponse
    {
        $request->validate([
            'razorpay_order_id' => ['required', 'string'],
            'razorpay_payment_id' => ['required', 'string'],
            'razorpay_signature' => ['required', 'string'],
        ]);

        $valid = $this->razorpay->verifySignature(
            $request->razorpay_order_id,
            $request->razorpay_payment_id,
            $request->razorpay_signature,
        );

        if (! $valid) {
            return redirect()->route('cart.index')->with('error', 'Payment verification failed.');
        }

        $checkout = session('checkout');
        if (! $checkout) {
            return redirect()->route('cart.index')->with('error', 'Checkout session expired.');
        }

        $order = $this->orders->createFromCart(
            $request->user()->id,
            $checkout['billing'],
            $checkout['shipping'],
            $checkout['notes'] ?? null,
        );

        $order->update(['payment_status' => 'paid', 'status' => 'confirmed']);

        $order->payment()->create([
            'gateway' => 'razorpay',
            'razorpay_order_id' => $request->razorpay_order_id,
            'razorpay_payment_id' => $request->razorpay_payment_id,
            'razorpay_signature' => $request->razorpay_signature,
            'amount' => $order->total,
            'currency' => config('services.razorpay.currency', 'INR'),
            'status' => 'captured',
        ]);

        session()->forget('checkout');
        $this->sendConfirmation($order);

        return redirect()->route('checkout.success', $order)->with('success', 'Payment successful! Your order is confirmed.');
    }

    public function success(Request $request, Order $order): View
    {
        abort_unless($order->user_id === $request->user()->id, 403);
        $order->load('items');

        return view('frontend.checkout.success', compact('order'));
    }

    // ----- Helpers -------------------------------------------------------

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'phone' => ['required', 'string', 'max:20'],
            'line1' => ['required', 'string', 'max:255'],
            'line2' => ['nullable', 'string', 'max:255'],
            'city' => ['required', 'string', 'max:100'],
            'state' => ['required', 'string', 'max:100'],
            'pincode' => ['required', 'string', 'max:12'],
            'country' => ['nullable', 'string', 'max:100'],
            'notes' => ['nullable', 'string', 'max:500'],
            'payment_method' => ['required', 'in:cod,razorpay'],
            'ship_to_different' => ['nullable', 'boolean'],
            // Optional separate shipping fields.
            'ship_name' => ['nullable', 'string', 'max:255'],
            'ship_phone' => ['nullable', 'string', 'max:20'],
            'ship_line1' => ['nullable', 'string', 'max:255'],
            'ship_line2' => ['nullable', 'string', 'max:255'],
            'ship_city' => ['nullable', 'string', 'max:100'],
            'ship_state' => ['nullable', 'string', 'max:100'],
            'ship_pincode' => ['nullable', 'string', 'max:12'],
        ]);
    }

    /**
     * Build billing + shipping address snapshots from the request.
     */
    private function addresses(array $data): array
    {
        $billing = [
            'name' => $data['name'],
            'phone' => $data['phone'],
            'line1' => $data['line1'],
            'line2' => $data['line2'] ?? null,
            'city' => $data['city'],
            'state' => $data['state'],
            'pincode' => $data['pincode'],
            'country' => $data['country'] ?? 'India',
        ];

        if (! empty($data['ship_to_different']) && ! empty($data['ship_line1'])) {
            $shipping = [
                'name' => $data['ship_name'] ?? $data['name'],
                'phone' => $data['ship_phone'] ?? $data['phone'],
                'line1' => $data['ship_line1'],
                'line2' => $data['ship_line2'] ?? null,
                'city' => $data['ship_city'] ?? $data['city'],
                'state' => $data['ship_state'] ?? $data['state'],
                'pincode' => $data['ship_pincode'] ?? $data['pincode'],
                'country' => 'India',
            ];
        } else {
            $shipping = $billing;
        }

        return [$billing, $shipping];
    }

    /**
     * Best-effort order confirmation email (mailer defaults to the log driver).
     */
    private function sendConfirmation(Order $order): void
    {
        try {
            Mail::raw(
                "Thank you for your order {$order->order_number}. Total: ₹".number_format($order->total, 2),
                fn ($m) => $m->to($order->user->email)->subject("Order Confirmation — {$order->order_number}")
            );
        } catch (\Throwable $e) {
            report($e); // Don't block checkout on mail failures.
        }
    }
}
