<h2>Thank you for your purchase!</h2>
<p>Your order #: <strong>{{ $order->order_number }}</strong></p>
<p>Total Amount: <strong>₱{{ number_format($order->total_amount, 2) }}</strong></p>
<p>The invoice is attached as a PDF for your reference.</p>
