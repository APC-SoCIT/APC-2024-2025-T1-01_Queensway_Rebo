<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            color: #333;
        }

        h1 {
            color: #444;
            margin-bottom: 20px;
        }

        .info-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .info-box {
            width: 48%;
        }

        .info-box h4 {
            margin: 0 0 6px 0;
            font-size: 13px;
            font-weight: bold;
        }

        .info-box p {
            margin: 4px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background-color: #f8f8f8;
        }

        .text-right {
            text-align: right;
        }

        .separator-row td {
            background-color: #eee;
            border: none;
            height: 10px;
        }
    </style>
</head>
<body>

    <h1>Invoice</h1>

    <div class="info-container">
        <div class="info-box">
            <p><strong>Order Number:</strong> {{ $order->order_number }}</p>
            <p><strong>Order Date:</strong> {{ $order->created_at->format('F j, Y') }}</p>
            <p><strong>Order Status:</strong> {{ ucfirst($order->order_status) }}</p>
        </div>
        <div class="info-box">
            <p><strong>Recipient:</strong> {{ $order->recipient_name }}</p>
            <p><strong>Shipping Address:</strong> {{ $order->shipping_address }}</p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>SKU</th> <!-- ✅ Added SKU Column -->
                <th>Quantity</th>
                <th>Price Each (VAT incl.)</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product_name ?? 'N/A' }}</td>
                    <td>{{ $item->product->sku ?? $item->sku }}</td> <!-- ✅ Display SKU -->
                    <td>{{ $item->quantity }}</td>
                    <td class="text-right">₱{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">₱{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                </tr>
            @endforeach

            <!-- Gray separator row -->
            <tr class="separator-row">
                <td colspan="5"></td>
            </tr>

            @php
                $total = $order->total_amount;
                $net = $total / 1.12;
                $vat = $total - $net;
            @endphp

            <tr>
                <td colspan="4" class="text-right"><strong>Subtotal (VAT excl.):</strong></td>
                <td class="text-right">₱{{ number_format($net, 2) }}</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right"><strong>VAT (12%):</strong></td>
                <td class="text-right">₱{{ number_format($vat, 2) }}</td>
            </tr>
            <tr>
                <td colspan="4" class="text-right"><strong>Total (VAT incl.):</strong></td>
                <td class="text-right"><strong>₱{{ number_format($total, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

</body>
</html>
