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
            <p><strong>Stock:</strong> {{ $product->quantity }}</p>

            <!-- Add to Cart Form -->
            <form action="{{ route('cart.add') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" name="quantity" class="form-control" value="1" min="1" max="{{ $product->quantity }}" required>
                </div>
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                <button type="submit" class="btn btn-primary">Add to Cart</button>
            </form>
        </div>
    </div>
</div>
@endsection
