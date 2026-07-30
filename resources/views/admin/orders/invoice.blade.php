<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $order->order_number }}</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; color: #222; padding: 30px; font-size: 14px; }
        .head { display: flex; justify-content: space-between; border-bottom: 2px solid #b5303f; padding-bottom: 12px; }
        .brand { font-size: 22px; font-weight: 700; color: #b5303f; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f7f7f7; }
        .text-end { text-align: right; }
        .totals td { border: none; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body onload="window.print()">
    <div class="head">
        <div>
            <div class="brand">Rathna Collections</div>
            <div>Textile Fashion Store</div>
        </div>
        <div class="text-end">
            <strong>INVOICE</strong><br>
            #{{ $order->order_number }}<br>
            {{ $order->created_at->format('d M Y') }}
        </div>
    </div>

    <div style="margin-top:20px;">
        <strong>Bill To:</strong><br>
        @php($bill = $order->billing_address ?? $order->shipping_address)
        {{ $bill['name'] ?? $order->user?->name }}<br>
        {{ $bill['line1'] ?? '' }} {{ $bill['line2'] ?? '' }}<br>
        {{ $bill['city'] ?? '' }}, {{ $bill['state'] ?? '' }} - {{ $bill['pincode'] ?? '' }}<br>
        {{ $bill['phone'] ?? '' }}
    </div>

    <table>
        <thead><tr><th>#</th><th>Product</th><th>Variant</th><th class="text-end">Price</th><th class="text-end">Qty</th><th class="text-end">Total</th></tr></thead>
        <tbody>
            @foreach ($order->items as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->product_name }} <small>({{ $item->sku }})</small></td>
                    <td>{{ $item->variant_label ?? '—' }}</td>
                    <td class="text-end">₹{{ number_format($item->price, 2) }}</td>
                    <td class="text-end">{{ $item->quantity }}</td>
                    <td class="text-end">₹{{ number_format($item->subtotal, 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals" style="width:300px; float:right; margin-top:10px;">
        <tr><td>Subtotal</td><td class="text-end">₹{{ number_format($order->subtotal, 2) }}</td></tr>
        <tr><td>Discount</td><td class="text-end">- ₹{{ number_format($order->discount, 2) }}</td></tr>
        <tr><td>Tax</td><td class="text-end">₹{{ number_format($order->tax, 2) }}</td></tr>
        <tr><td>Shipping</td><td class="text-end">₹{{ number_format($order->shipping, 2) }}</td></tr>
        <tr style="font-weight:700;border-top:2px solid #333;"><td>Grand Total</td><td class="text-end">₹{{ number_format($order->total, 2) }}</td></tr>
    </table>

    <div style="clear:both; margin-top:60px; text-align:center; color:#888;">Thank you for shopping with Rathna Collections!</div>
</body>
</html>
