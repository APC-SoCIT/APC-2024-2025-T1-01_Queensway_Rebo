@extends('layouts.website')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">Checkout</h1>

    <div class="row">
        <!-- Left: Order Items -->
        <div class="col-md-8">
            <h3 class="mb-3">Your Items</h3>
            <div class="table-responsive">
                <table class="table align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>SKU</th> <!-- ✅ Added SKU column -->
                            <th>Quantity</th>
                            <th>Price each (VAT incl.)</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $totalQuantity = 0; @endphp
                        @foreach ($cartItems as $item)
                            @php $totalQuantity += $item['quantity']; @endphp
                            <tr>
                                <td>{{ $item['name'] }}</td>
                                <td>{{ $item['sku'] ?? 'N/A' }}</td> <!-- ✅ Display SKU -->
                                <td>{{ $item['quantity'] }}</td>
                                <td>₱{{ number_format($item['price'], 2) }}</td>
                                <td>₱{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Right: Order Summary -->
        <div class="col-md-4">
            <h3 class="mb-3">Order Summary</h3>
            <div class="card shadow-sm border-0 rounded-3 mb-4">
                <div class="card-body">
                    <p><strong>Total Items:</strong> {{ $totalQuantity }}</p>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Subtotal (VAT Excl.):</span>
                        <strong>₱{{ number_format($subtotal, 2) }}</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>VAT (12%):</span>
                        <strong>₱{{ number_format($vat, 2) }}</strong>
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span class="fw-bold">Total (VAT Inclusive):</span>
                        <span class="fw-bold fs-5 text-primary">₱{{ number_format($grandTotal, 2) }}</span>
                    </div>

                    <!-- PayPal Button -->
                    <div id="paypal-button-container" class="mt-4"></div>
                </div>
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
        const totalAmount = "{{ number_format($grandTotal, 2, '.', '') }}";

        const items = Object.entries(rawCart).map(([id, item]) => ({
            id: id,
            name: item.name,
            price: item.price.toString(),
            quantity: item.quantity,
            image: item.image || '',
            sku: item.sku || ''
        }));

        paypal.Buttons({
            createOrder: function (data, actions) {
                return actions.order.create({
                    purchase_units: [{
                        amount: { value: totalAmount }
                    }],
                    application_context: {
                        shipping_preference: "GET_FROM_FILE"
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
