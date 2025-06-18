@extends('layouts.website')

@section('content')
    <style>
        .product-detail-container {
            background-color: #f8f9fa;
            padding: 40px 20px;
            border-radius: 12px;
            margin-bottom: 40px;
        }

        .related-section {
            background-color: #ffffff;
            padding: 30px 20px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .related-title {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 20px;
        }

        .related-card img {
            height: 180px;
            object-fit: cover;
            border-top-left-radius: 12px;
            border-top-right-radius: 12px;
        }

        .related-card {
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s ease-in-out;
            border: 1px solid #e0e0e0;
        }

        .related-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }
    </style>

    <div class="container my-5">
        <div class="product-detail-container shadow-sm">
            <div class="row">
                <!-- Product Image -->
                <div class="col-md-6">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}"
                        class="img-fluid rounded w-100">
                </div>

                <!-- Product Details -->
                <div class="col-md-6">
                    <h1 class="fw-bold">{{ $product->name }}</h1>

                    <h3 class="text-primary mb-3">₱{{ number_format($product->price, 2) }}</h3>

                    {{-- SKU Display --}}
                    @if($product->sku)
                        <p class="mb-1">
                            <strong>SKU:</strong> {{ $product->sku }}
                        </p>
                    @endif

                    <p class="mb-3">
                        <strong>Description:</strong><br>
                        {{ $product->description }}
                    </p>

                    <p class="mb-4">
                        <strong>Stock:</strong>
                        @if($product->quantity > 0)
                            {{ $product->quantity }}
                        @else
                            <span class="text-danger">Out of Stock</span>
                        @endif
                    </p>

                    <!-- Add to Cart Form (Hidden when Out of Stock) -->
                    @if($product->quantity > 0)
                        <form id="add-to-cart-form" action="{{ route('cart.add') }}" method="POST">
                            @csrf

                            <div class="mb-3">
                                <label for="quantity" class="form-label">Quantity</label>
                                <input type="number" name="quantity" class="form-control" value="1" min="1"
                                    max="{{ $product->quantity }}" required>
                            </div>

                            <input type="hidden" name="product_id" value="{{ $product->id }}">

                            @auth
                                <button type="submit" class="btn btn-primary">
                                    Add to Cart
                                </button>
                            @else
                                <button type="button" class="btn btn-secondary" disabled
                                    title="Please log in to add items to your cart">
                                    Add to Cart (Login Required)
                                </button>
                            @endauth
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Related Products -->
        @if(isset($relatedProducts) && $relatedProducts->count())
            <div class="related-section mt-5 shadow-sm">
                <h2 class="related-title text-center">Related Products</h2>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                    @foreach($relatedProducts as $related)
                        <div class="col">
                            <div class="card related-card h-100">
                                <img src="{{ asset('storage/' . $related->image) }}" class="card-img-top"
                                    alt="{{ $related->name }}">
                                <div class="card-body text-center">
                                    <h6 class="card-title">{{ $related->name }}</h6>
                                    <p class="text-primary fw-semibold">₱{{ number_format($related->price, 2) }}</p>
                                    <p class="{{ $related->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $related->quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                                    </p>
                                    <a href="{{ route('product.show', $related->id) }}"
                                        class="btn btn-outline-primary btn-sm">View</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Loading Spinner Component -->
    <x-loading-overlay id="view-loading" />

    <script>
        document.getElementById('add-to-cart-form')?.addEventListener('submit', function () {
            document.getElementById('view-loading').classList.add('active');
        });
    </script>
@endsection
