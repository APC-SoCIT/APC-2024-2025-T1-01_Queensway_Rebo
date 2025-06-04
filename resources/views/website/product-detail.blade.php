@extends('layouts.website')

@section('content')
<div class="container my-5">
    <div class="row">
        <!-- Product Image -->
        <div class="col-md-6">
            <img src="{{ asset('storage/' . $product->image) }}" 
                 alt="{{ $product->name }}" 
                 class="img-fluid product-img">
        </div>

        <!-- Product Details -->
        <div class="col-md-6">
            <h1>{{ $product->name }}</h1>
            <h3 class="text-primary">₱{{ number_format($product->price, 2) }}</h3>
            <p>{{ $product->description }}</p>

            <p>
                <strong>Stock:</strong> 
                @if($product->quantity > 0)
                    {{ $product->quantity }}
                @else
                    <span class="text-danger">Out of Stock</span>
                @endif
            </p>

            <!-- Add to Cart Form -->
            <form id="add-to-cart-form" action="{{ route('cart.add') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" 
                           name="quantity" 
                           class="form-control" 
                           value="1" 
                           min="1" 
                           max="{{ $product->quantity }}" 
                           {{ $product->quantity == 0 ? 'disabled' : '' }} 
                           required>
                </div>

                <input type="hidden" name="product_id" value="{{ $product->id }}">

                @auth
                    <button type="submit" class="btn btn-primary" {{ $product->quantity == 0 ? 'disabled' : '' }}>
                        Add to Cart
                    </button>
                @else
                    <button type="button" class="btn btn-secondary" disabled title="Please log in to add items to your cart">
                        Add to Cart (Login Required)
                    </button>
                @endauth
            </form>
        </div>
    </div>
</div>

<!-- Loading Spinner Component -->
<x-loading-overlay id="view-loading" />

<script>
    document.getElementById('add-to-cart-form').addEventListener('submit', function () {
        document.getElementById('view-loading').classList.add('active');
    });
</script>
@endsection
