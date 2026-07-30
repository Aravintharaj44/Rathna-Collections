@extends('layouts.app')

@section('title', 'Complete Payment')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center py-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <i class="bi bi-credit-card fs-1 text-primary"></i>
                    <h1 class="h4 mt-3">Complete Your Payment</h1>
                    <p class="text-muted">Amount payable: <strong>₹{{ number_format($amount, 2) }}</strong></p>
                    <button id="payBtn" class="btn btn-primary btn-lg">Pay Now</button>
                    <p class="small text-muted mt-3">You will be redirected to Razorpay's secure checkout.</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Hidden form used to POST the verified payment back to the server. --}}
<form id="verifyForm" action="{{ route('payment.verify') }}" method="POST" class="d-none">
    @csrf
    <input type="hidden" name="razorpay_order_id">
    <input type="hidden" name="razorpay_payment_id">
    <input type="hidden" name="razorpay_signature">
</form>

@push('scripts')
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
    const options = {
        key: @json($razorpayKey),
        amount: @json($rzpOrder['amount']),
        currency: @json($rzpOrder['currency']),
        name: 'Rathna Collections',
        description: 'Order Payment',
        order_id: @json($rzpOrder['id']),
        prefill: { name: @json($user->name), email: @json($user->email), contact: @json($user->phone ?? '') },
        theme: { color: '#b5303f' },
        handler: function (response) {
            const form = document.getElementById('verifyForm');
            form.razorpay_order_id.value = response.razorpay_order_id;
            form.razorpay_payment_id.value = response.razorpay_payment_id;
            form.razorpay_signature.value = response.razorpay_signature;
            form.submit();
        },
    };
    const rzp = new Razorpay(options);
    document.getElementById('payBtn').addEventListener('click', () => rzp.open());
    // Auto-open on load.
    rzp.open();
</script>
@endpush
@endsection
