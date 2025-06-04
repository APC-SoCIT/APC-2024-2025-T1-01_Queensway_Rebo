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

        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 20px;
        }

        .logo {
            width: 120px;
        }

        h1 {
            color: #444;
            margin: 0 0 10px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #ccc;
            padding: 8px;
        }

        th {
            background-color: #f8f8f8;
        }

        .text-right {
            text-align: right;
        }
    </style>
</head>

<body>

    <div class="header">
        <div>
            <h1>Invoice</h1>
            <p><strong>Order #:</strong> {{ $order->order_number }}</p>

            <h4>Ship To:</h4>
            <p>
                {{ $order->recipient_name }}<br>
                {{ $order->shipping_address }}
            </p>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td class="text-right">₱{{ number_format($item->unit_price, 2) }}</td>
                    <td class="text-right">₱{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
                </tr>
            @endforeach
            <tr>
                <td colspan="3" class="text-right"><strong>Total:</strong></td>
                <td class="text-right"><strong>₱{{ number_format($order->total_amount, 2) }}</strong></td>
            </tr>
        </tbody>
    </table>

</body>

</html>
