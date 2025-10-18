@extends('layouts.website')

@section('content')
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>
:root {
    --primary: #222;
    --accent: #f4b400;
    --bg: #fafafa;
    --gray: #6c757d;
    --text: #1c1c1c;
    --shadow: rgba(0,0,0,0.06);
    --border: #e5e5e5;
}

body {
    background-color: var(--bg);
    color: var(--text);
    font-family: 'Poppins', sans-serif;
}

/* ---------- GLOBAL LOADER ---------- */
#global-loader {
    display: none;
    position: fixed;
    top:0; left:0; right:0; bottom:0;
    background: rgba(255,255,255,0.85);
    backdrop-filter: blur(5px);
    z-index: 9999;
    align-items: center;
    justify-content: center;
    flex-direction: column;
}

#global-loader .spinner {
    width: 70px;
    height: 70px;
    border: 6px solid #ddd;
    border-top-color: var(--accent);
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

#global-loader p {
    margin-top: 15px;
    font-weight: 600;
    color: var(--primary);
}

@keyframes spin { 100% { transform: rotate(360deg); } }

/* ---------- CART SECTION ---------- */
.cart-section {
    max-width: 1200px;
    margin: 60px auto;
    background: #fff;
    border-radius: 20px;
    box-shadow: 0 6px 20px var(--shadow);
    padding: 40px;
}

.cart-table img {
    width: 100px;
    height: auto;
    border-radius: 12px;
}

.cart-table th, .cart-table td {
    vertical-align: middle;
}

.cart-table input.quantity-input {
    width: 70px;
    border-radius: 10px;
    border: 1.5px solid var(--border);
    padding: 6px 10px;
    text-align: center;
}

.cart-table input.quantity-input:focus {
    border-color: var(--accent);
    box-shadow: 0 0 6px rgba(244,180,0,0.3);
    outline: none;
}

.cart-actions {
    display: flex;
    gap: 15px;
    justify-content: flex-end;
    margin-top: 25px;
}

.cart-actions a {
    border-radius: 30px;
    padding: 12px 30px;
    font-weight: 600;
    transition: 0.3s;
    text-decoration: none;
}

.cart-actions .btn-primary {
    background: var(--accent);
    color: var(--primary);
    border: none;
}

.cart-actions .btn-primary:hover {
    background: #e2a800;
}

.cart-actions .btn-outline-secondary {
    border: 1.5px solid var(--primary);
    color: var(--primary);
    background: #fff;
}

.cart-actions .btn-outline-secondary:hover {
    background: var(--primary);
    color: #fff;
}

.empty-cart {
    text-align: center;
    margin-top: 40px;
    font-size: 1.1rem;
    color: var(--gray);
}
</style>

<!-- Global Loader -->
<div id="global-loader">
    <div class="spinner"></div>
</div>

<div class="cart-section" data-aos="fade-up">
    <h1 class="mb-4">Your Shopping Cart</h1>

    @if($cartItems)
        <div class="table-responsive">
            <table class="table cart-table align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>Price (VAT)</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @php $cartTotal = 0; @endphp
                    @foreach($cartItems as $id => $item)
                        @php 
                            $lineTotal = $item['price'] * $item['quantity'];
                            $cartTotal += $lineTotal;
                        @endphp
                        <tr id="cart-item-{{ $id }}">
                            <td>
                                <div class="d-flex align-items-center gap-3">
                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}">
                                    <div>
                                        <h5>{{ $item['name'] }}</h5>
                                        @if(isset($item['sku']))
                                            <small class="text-muted">SKU: {{ $item['sku'] }}</small>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>₱{{ number_format($item['price'],2) }}</td>
                            <td>
                                <input type="number" class="quantity-input" min="1" value="{{ $item['quantity'] }}" data-product-id="{{ $id }}">
                            </td>
                            <td id="subtotal-{{ $id }}">₱{{ number_format($lineTotal,2) }}</td>
                            <td>
                                <button class="btn btn-danger btn-sm remove-item" data-product-id="{{ $id }}">Remove</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3" class="text-end fw-bold">Total:</td>
                        <td colspan="2" class="fw-bold text-primary fs-5">₱{{ number_format($cartTotal,2) }}</td>
                    </tr>
                </tfoot>
            </table>
        </div>

        <div class="cart-actions">
            <a href="{{ route('products.index') }}" class="btn-outline-secondary action-btn">Continue Shopping</a>
            <a href="{{ route('checkout') }}" class="btn-primary action-btn">Proceed to Checkout</a>
        </div>
    @else
        <div class="empty-cart">
            <p>Your cart is empty.</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary action-btn mt-3">Start Shopping</a>
        </div>
    @endif
</div>
@endsection

@section('scripts')
<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init({ duration:1000, once:true });

document.addEventListener('DOMContentLoaded', () => {
    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
    const loader = document.getElementById('global-loader');

    function showLoader(){ loader.style.display='flex'; }
    function hideLoader(){ loader.style.display='none'; }

    function updateTotalDisplay(total){
        document.querySelector('tfoot .text-primary').textContent = `₱${parseFloat(total).toFixed(2)}`;
    }

    function updateCartBadge(count){
        const badge = document.getElementById('cart-badge');
        if(!badge) return;
        badge.textContent = count;
        badge.style.display = count>0?'inline-block':'none';
    }

    // Quantity change
    document.querySelectorAll('.quantity-input').forEach(input=>{
        input.addEventListener('change', function(){
            const productId=this.dataset.productId;
            const quantity=parseInt(this.value);
            if(quantity<1){ alert('Quantity must be at least 1'); this.value=1; return; }

            showLoader();
            fetch("{{ route('cart.update') }}", {
                method:'POST',
                headers:{ "Content-Type":"application/x-www-form-urlencoded","X-CSRF-TOKEN":csrfToken },
                body: new URLSearchParams({ product_id: productId, quantity: quantity })
            })
            .then(res=>res.json())
            .then(data=>{
                hideLoader();
                if(data.success){
                    document.getElementById(`subtotal-${productId}`).textContent=`₱${data.subtotal.toFixed(2)}`;
                    updateTotalDisplay(data.total);
                    updateCartBadge(data.cartCount);
                } else alert('Failed to update cart.');
            })
            .catch(()=>{ hideLoader(); alert('Error updating cart.'); });
        });
    });

    // Remove item
    document.querySelectorAll('.remove-item').forEach(btn=>{
        btn.addEventListener('click', function(){
            const productId=this.dataset.productId;
            showLoader();
            fetch("{{ route('cart.remove') }}", {
                method:'POST',
                headers:{ "Content-Type":"application/x-www-form-urlencoded","X-CSRF-TOKEN":csrfToken },
                body: new URLSearchParams({ product_id: productId })
            })
            .then(res=>res.json())
            .then(data=>{
                hideLoader();
                if(data.success){
                    document.getElementById(`cart-item-${productId}`).remove();
                    updateTotalDisplay(data.total);
                    updateCartBadge(data.cartCount);

                    if(data.cartCount===0){
                        document.querySelector('.cart-section').innerHTML=`
                            <div class="empty-cart">
                                <p>Your cart is empty.</p>
                                <a href="{{ route('products.index') }}" class="btn btn-primary action-btn mt-3">Start Shopping</a>
                            </div>`;
                    }
                } else alert('Failed to remove item.');
            })
            .catch(()=>{ hideLoader(); alert('Error removing item.'); });
        });
    });

    // Loader on links/buttons
    document.querySelectorAll('.action-btn').forEach(btn=>{
        btn.addEventListener('click', showLoader);
    });

    window.addEventListener('beforeunload', showLoader);
});
</script>
@endsection
