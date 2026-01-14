<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice - Order #{{ $order->order_code }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 14px; }
        .header { text-align: center; margin-bottom: 20px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ddd; padding: 8px; }
        .table th { background: #f2f2f2; }
    </style>
</head>
<body>
    <div class="header">
        <h2>Invoice</h2>
        <p>Order ID: #{{ $order->id }}</p>
        <p>Date: {{ $order->created_at->format('d M Y') }}</p>
    </div>

    <h4>Billing Details</h4>
    <p><strong>Name:</strong> {{ $order->user->name }}</p>
    <p><strong>Email:</strong> {{ $order->user->email }}</p>

    <h4>Order Items</h4>
    <table class="table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Price</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($order->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td>{{ $item->quantity }}</td>
                <td>{{ number_format($item->price, 2) }}</td>
                <td>{{ number_format($item->price * $item->quantity, 2) }}</td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <h4 style="text-align: right; margin-top: 20px;">
        Grand Total: {{ number_format($order->total_amount, 2) }}
    </h4>
</body>
</html>
