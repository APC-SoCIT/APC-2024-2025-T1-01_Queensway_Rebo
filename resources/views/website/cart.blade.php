@extends('layouts.website')

@section('content')
<div class="container my-5">
    <h1 class="mb-4">Your Shopping Cart</h1>
    @if ($cartItems)
        <div class="table-responsive">
            <table class="table align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Product</th>
                        <th scope="col">Price</th>
                        <th scope="col">Quantity</th>
                        <th scope="col">Subtotal</th>
                        <th scope="col">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($cartItems as $id => $item)
                        <tr id="cart-item-{{ $id }}">
                            <td>
                                <div class="d-flex align-items-center">
                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="product-img me-3" style="width: 100px; height: auto;">
                                    <div>
                                        <h5 class="mb-0">{{ $item['name'] }}</h5>
                                    </div>
                                </div>
                            </td>
                            <td>₱{{ number_format($item['price'], 2) }}</td>
                            <td>
                                <input type="number" class="form-control quantity-input" value="{{ $item['quantity'] }}" min="1" data-product-id="{{ $id }}" required>
                            </td>
                            <td id="subtotal-{{ $id }}">₱{{ number_format($item['price'] * $item['quantity'], 2) }}</td>
                            <td>
                                <button class="btn btn-danger btn-sm remove-item" data-product-id="{{ $id }}">Remove</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end"><strong>Total:</strong></td>
                        <td id="total-amount">₱{{ number_format($total, 2) }}</td>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
        <div class="d-flex justify-content-between">
            <a href="{{ route('products.index') }}" class="btn btn-outline-secondary">Continue Shopping</a>
            <a href="{{ route('checkout') }}" class="btn btn-primary">Proceed to Checkout</a>

        </div>
    @else
        <p class="text-muted">Your cart is empty.</p>
        <a href="{{ route('products.index') }}" class="btn btn-primary">Start Shopping</a>
    @endif
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log('Script loaded!');

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        document.querySelectorAll('.quantity-input').forEach(input => {
            input.addEventListener('change', function () {
                console.log('Quantity changed');
                const productId = this.dataset.productId;
                const newQuantity = parseInt(this.value);

                if (newQuantity < 1) {
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
                        quantity: newQuantity
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`subtotal-${productId}`).textContent = `₱${data.subtotal.toFixed(2)}`;
                        document.getElementById('total-amount').textContent = `₱${data.total.toFixed(2)}`;
                    } else {
                        alert('Failed to update cart.');
                    }
                })
                .catch(() => alert('Error while updating cart.'));
            });
        });

        document.querySelectorAll('.remove-item').forEach(button => {
            button.addEventListener('click', function () {
                console.log('Remove clicked');
                const productId = this.dataset.productId;

                fetch("{{ route('cart.remove') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                        "X-CSRF-TOKEN": csrfToken
                    },
                    body: new URLSearchParams({
                        product_id: productId
                    })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        document.getElementById(`cart-item-${productId}`).remove();
                        document.getElementById('total-amount').textContent = `₱${data.total.toFixed(2)}`;

                        if (data.total === 0) {
                            document.querySelector('.table-responsive').innerHTML = `
                                <p class="text-muted">Your cart is empty.</p>
                                <a href="" class="btn btn-primary">Start Shopping</a>`;
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
