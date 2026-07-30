<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

/**
 * Thin Razorpay integration using the REST API directly (no SDK dependency).
 * Handles order creation and signature verification.
 */
class RazorpayService
{
    private ?string $key;
    private ?string $secret;
    private string $currency;

    public function __construct()
    {
        $this->key = config('services.razorpay.key');
        $this->secret = config('services.razorpay.secret');
        $this->currency = config('services.razorpay.currency', 'INR');
    }

    public function isConfigured(): bool
    {
        return ! empty($this->key) && ! empty($this->secret);
    }

    public function key(): ?string
    {
        return $this->key;
    }

    /**
     * Create a Razorpay order. Amount is in rupees; Razorpay expects paise.
     *
     * @return array{id:string,amount:int,currency:string}
     */
    public function createOrder(float $amount, string $receipt): array
    {
        $response = Http::withBasicAuth($this->key, $this->secret)
            ->acceptJson()
            ->post('https://api.razorpay.com/v1/orders', [
                'amount' => (int) round($amount * 100),
                'currency' => $this->currency,
                'receipt' => $receipt,
                'payment_capture' => 1,
            ]);

        $response->throw();

        return $response->json();
    }

    /**
     * Verify the checkout signature returned by Razorpay.
     */
    public function verifySignature(string $orderId, string $paymentId, string $signature): bool
    {
        $expected = hash_hmac('sha256', $orderId.'|'.$paymentId, $this->secret);

        return hash_equals($expected, $signature);
    }
}
