<h1>Invoice</h1>
<p>Order ID: {{ $order->paypal_order_id }}</p>

<table width="100%" border="1" cellspacing="0" cellpadding="5">
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
                <td>₱{{ number_format($item->unit_price, 2) }}</td>
                <td>₱{{ number_format($item->quantity * $item->unit_price, 2) }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="3"><strong>Total:</strong></td>
            <td><strong>₱{{ number_format($order->total_amount, 2) }}</strong></td>
        </tr>
    </tbody>
</table>
