<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice #{{ $order->order_code }}</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 13px;
            color: #1f2937; /* slate-800 */
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: auto;
            padding: 32px;
            background: #fff;
            border: 1px solid #e5e7eb; /* gray-200 */
            border-radius: 12px;
        }
        .brand {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #e5e7eb;
            padding-bottom: 16px;
            margin-bottom: 24px;
        }
        .brand h1 {
            font-size: 24px;
            font-weight: 700;
            color: #111827; /* gray-900 */
        }
        .invoice-meta {
            text-align: right;
            font-size: 13px;
            color: #374151; /* gray-700 */
        }
        .section-title {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 8px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 4px;
        }
        .info-box {
            margin-bottom: 24px;
        }
        .info-box p {
            margin: 2px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 24px;
            border-radius: 8px;
            overflow: hidden;
        }
        thead {
            background: #f9fafb; /* gray-50 */
        }
        th, td {
            border: 1px solid #e5e7eb;
            padding: 10px 12px;
            text-align: left;
            font-size: 13px;
        }
        th {
            font-weight: 600;
            color: #374151;
        }
        .total {
            text-align: right;
            font-size: 16px;
            font-weight: 700;
            color: #111827;
            margin-top: 12px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #6b7280;
            margin-top: 40px;
            border-top: 1px solid #e5e7eb;
            padding-top: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Brand Header -->
        <div class="brand">
            <h1>Luxorix</h1>
            <div class="invoice-meta">
                <p><strong>Invoice #:</strong> {{ $order->order_code }}</p>
                <p><strong>Date:</strong> {{ $order->created_at->format('d M Y') }}</p>
            </div>
        </div>

        <!-- Customer Info -->
        <div class="info-box">
            <h2 class="section-title">Customer Info</h2>
            <p><strong>Name:</strong> {{ $order->first_name . $order->last_name ?? 'Guest' }}</p>
            <p><strong>Email:</strong> {{ $order->email ?? '' }}</p>
            <p><strong>Phone:</strong> {{ $order->phone ?? 'N/A' }}</p>
        </div>

        <!-- Order Items -->
        <div class="info-box">
            <h2 class="section-title">Order Items</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th style="text-align:center;">Qty</th>
                        <th style="text-align:right;">Price</th>
                        <th style="text-align:right;">Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($order->items as $item)
                        <tr>
                            <td>{{ $item->product->name }}</td>
                            <td style="text-align:center;">{{ $item->quantity }}</td>
                            <td style="text-align:right;">${{ number_format($item->price, 2) }}</td>
                            <td style="text-align:right;">${{ number_format($item->price * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <p class="total">Total: ${{ number_format($order->total_amount, 2) }}</p>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>Thank you for shopping with <strong>Luxorix</strong>!</p>
            <p>If you have any questions about this invoice, contact us at support@luxorix.com</p>
        </div>
    </div>
</body>
</html>
