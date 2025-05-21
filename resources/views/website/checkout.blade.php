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
    <script src="https://www.paypal.com/sdk/js?client-id={{ $paypalClientId }}&currency=PHP"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const rawCart = @json($cartItems);
            const totalAmount = "{{ number_format($total, 2, '.', '') }}";

            const items = Object.entries(rawCart).map(([id, item]) => ({
                id: id,
                name: item.name,
                price: item.price.toString(),
                quantity: item.quantity,
                image: item.image || ''
            }));

            paypal.Buttons({
                createOrder: function (data, actions) {
                    return actions.order.create({
                        purchase_units: [{
                            amount: { value: totalAmount }
                        }],
                        application_context: {
                            shipping_preference: "GET_FROM_FILE" // allow user to input address
                        }
                    });
                },

                onApprove: function (data, actions) {
                    return actions.order.capture().then(function (details) {
                        const shippingInfo = details.purchase_units[0].shipping;
                        const name = shippingInfo?.name?.full_name || '';
                        const address = shippingInfo?.address || {};

                        const payload = {
                            orderID: data.orderID,
                            totalAmount: totalAmount,
                            items: items,
                            shipping: {
                                name: name,
                                address_line_1: address.address_line_1 || '',
                                address_line_2: address.address_line_2 || '',
                                city: address.admin_area_2 || '',
                                state: address.admin_area_1 || '',
                                postal_code: address.postal_code || '',
                                country: address.country_code || ''
                            }
                        };

                        return fetch("{{ route('checkout.complete') }}", {
                            method: "POST",
                            headers: {
                                "Content-Type": "application/json",
                                "X-CSRF-TOKEN": "{{ csrf_token() }}"
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