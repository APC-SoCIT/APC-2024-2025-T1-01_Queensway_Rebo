@extends('layouts.website')

@section('content')
    <div class="container my-5">
        <h1 class="mb-4">Checkout</h1>
        <div class="row">
            <div class="col-md-6">
                <h3>Order Summary</h3>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Quantity</th>
                            <th>Price</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cartItems as $item)
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['quantity'] }}</td>
                                <td>₱{{ number_format($item['price'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" class="text-end"><strong>Total:</strong></td>
                            <td>₱{{ number_format($total, 2) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="col-md-6">
                <div class="mt-5" id="paypal-button-container">
                    <div id="paypal-button-container"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    {{-- 1) PayPal SDK --}}
    <script src="https://www.paypal.com/sdk/js?client-id={{ $paypalClientId }}&currency=PHP"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function () {
        // Grab the raw cart object (keys are product IDs)
        const rawCart = @json($cartItems);
        // Format total as a string with two decimals
        const totalAmount = "{{ number_format($total, 2, '.', '') }}";

        // Rebuild items[] so each object *always* has an `id` field
        const items = Object.entries(rawCart).map(([id, item]) => ({
            id:       id,
            name:     item.name,
            price:    item.price.toString(),
            quantity: item.quantity,
            image:    item.image || ''   // optional
        }));

        // Render the PayPal button
        paypal.Buttons({
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: { value: totalAmount }
                    }]
                });
            },
            onApprove: function (data, actions) {
                return actions.order.capture().then(function () {
                    // Now post exactly what your Laravel controller expects:
                    const payload = {
                        orderID:     data.orderID,   // <-- use `data.orderID`, not details.id
                        totalAmount: totalAmount,
                        items:       items
                    };

                    return fetch("{{ route('checkout.complete') }}", {
                        method:  "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN":  "{{ csrf_token() }}"
                        },
                        body: JSON.stringify(payload)
                    })
                    .then(res => res.json())
                    .then(json => {
                        if (json.success) {
                            window.location.href = "{{ url('/payment-success') }}?order_id=" + data.orderID;
                        } else {
                            alert("Checkout failed: " + (json.message || "Unknown error"));
                        }
                    })
                    .catch(err => {
                        console.error(err);
                        alert("An error occurred during checkout.");
                    });
                });
            },
            onError: function (err) {
                console.error("PayPal error:", err);
                alert("Payment failed. Please try again.");
            }
        }).render('#paypal-button-container');
    });
    </script>
@endsection
