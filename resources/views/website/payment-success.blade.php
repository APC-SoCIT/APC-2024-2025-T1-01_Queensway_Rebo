@extends('layouts.website')

@section('content')
    <div class="container my-5">
        <h1 class="text-success">Payment Successful!</h1>
        <p>Thank you for your purchase. Your order ID is <strong>{{ $order->id }}</strong>.</p>

        <h4 class="mt-4">Order Summary</h4>
        <table class="table">
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
                    <td colspan="3" class="text-end"><strong>Total:</strong></td>
                    <td><strong>₱{{ number_format($order->total_amount, 2) }}</strong></td>
                </tr>
            </tbody>
        </table>

        <a href="/" class="btn btn-primary mt-3">Back to Home</a>
    </div>
@endsection
