@extends('layouts.website')

@section('content')
    <style>
        .product-card img {
            width: 100%;
            height: 200px;
            object-fit: cover;
        }

        .product-card {
            transition: transform 0.2s;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .product-card:hover {
            transform: scale(1.05);
        }

        .card-title {
            font-size: 1.1rem;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .card-text {
            font-size: 0.9rem;
            color: #555;
        }

        .btn-primary {
            font-size: 0.9rem;
            padding: 8px 16px;
        }
    </style>

    <!-- Hero Section -->
    <div class="bg-dark text-white py-5">
        <div class="container text-center">
            <h1>Welcome to Queens Rebo</h1>
            <p class="lead">Discover our exclusive collection of products</p>
            <a href="/shop" class="btn btn-primary btn-lg">Shop Now</a>
        </div>
    </div>

    <!-- Product Cards Section -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Featured Products</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4">
            @foreach($latestProducts as $product)
                <div class="col d-flex">
                    <div class="card product-card w-100 h-100 d-flex flex-column">
                        <img src="/storage/{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body text-center d-flex flex-column">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">₱{{ number_format($product->price, 2) }}</p>
                            <p class="card-text">Stock: {{ $product->quantity }}</p>
                            <a href="/product/{{ $product->id }}" class="btn btn-primary mt-auto">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-light py-4">
        <div class="container text-center">
            <p>&copy; 2025 Queens Rebo. All rights reserved.</p>
        </div>
    </footer>
@endsection
