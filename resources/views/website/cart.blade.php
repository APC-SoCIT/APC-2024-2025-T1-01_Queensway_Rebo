@extends('layouts.website')

@section('content')

<div class="container my-5">
    <h1 class="mb-4">Your Shopping Cart</h1>

    @if ($cartItems)
        <div class="row">
            {{-- Cart Items --}}
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Product</th>
                                <th>Price each (VAT incl.)</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $cartTotal = 0; @endphp
                            @foreach ($cartItems as $id => $item)
                                @php 
                                    $lineTotal = $item['price'] * $item['quantity']; 
                                    $cartTotal += $lineTotal; 
                                @endphp
                                <tr id="cart-item-{{ $id }}">
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}"
                                                class="me-3" style="width: 100px; height: auto; border-radius: 8px;">
                                            <div>
                                                <h5 class="mb-1">{{ $item['name'] }}</h5>
                                                @if(isset($item['sku']))
                                                    <p class="text-muted mb-0"><small><strong>SKU:</strong> {{ $item['sku'] }}</small></p>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>₱{{ number_format($item['price'], 2) }}</td>
                                    <td>
                                        <input type="number" class="form-control quantity-input" value="{{ $item['quantity'] }}" min="1"
                                            data-product-id="{{ $id }}">
                                    </td>
                                    <td id="subtotal-{{ $id }}">₱{{ number_format($lineTotal, 2) }}</td>
                                    <td>
                                        <button class="btn btn-danger btn-sm remove-item" data-product-id="{{ $id }}">Remove</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end fw-bold">Total:</td>
                                <td colspan="2" class="fw-bold text-primary fs-5">₱{{ number_format($cartTotal, 2) }}</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-3">
                    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Continue Shopping</a>
                    <a href="{{ route('checkout') }}" class="btn btn-primary">Proceed to Checkout</a>
                </div>
            </div>
        </div>
    @else
        <p class="text-muted">Your cart is empty.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">Start Shopping</a>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        function updateTotalDisplay(total) {
            const formattedTotal = `₱${parseFloat(total).toFixed(2)}`;
            document.querySelector('tfoot .text-primary').textContent = formattedTotal;
        }

        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function () {
                const productId = this.dataset.productId;
                const quantity = parseInt(this.value);

                if (quantity < 1) {
                    alert('Quantity must be at least 1.');
                    this.value = 1;
                    return;
                }

                fetch("{{ route('cart.update') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: new URLSearchParams({
                        product_id: productId,
                        quantity: quantity
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`subtotal-${productId}`).textContent = `₱${data.subtotal.toFixed(2)}`;
                        updateTotalDisplay(data.total);
                    } else {
                        alert('Failed to update cart.');
                    }
                })
                .catch(() => alert('Error while updating cart.'));
            });
        });

        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', function () {
                const productId = this.dataset.productId;

                fetch("{{ route('cart.remove') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: new URLSearchParams({ product_id: productId })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`cart-item-${productId}`).remove();
                        updateTotalDisplay(data.total);

                        if (data.total === 0) {
                            document.querySelector('.row').innerHTML = `
                                <p class="text-muted">Your cart is empty.</p>
                                <a href="{{ route('products.index') }}" class="btn btn-primary">Start Shopping</a>`;
                        }
                    } else {
                        alert('Failed to remove item.');
                    }
                })
                .catch(() => alert('Error while removing item.'));
            });
        });
    });
</script>
@endsection
