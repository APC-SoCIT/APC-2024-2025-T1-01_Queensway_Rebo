@extends('layouts.website')

@section('content')
<style>
    :root {
        --light-green: #c1e1c1;
        --beige: #e1ddd1;
        --light-gray: #e6d6d6;
        --white: #fefefe;
        --blue-accent: #8890d4;
        --deep-blue: #2608bd;
        --pastel-blue: #add8e6;
        --bold-blue: #342de5;
    }

    /* Carousel Hero Section */
    #heroCarousel {
        margin-bottom: 60px;
    }

    .hero-slide {
        background: linear-gradient(to right, #343a40, #495057);
        color: #fff;
        height: 400px;
        position: relative;
    }

    .carousel-caption {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        text-align: center;
    }

    .carousel-caption h1 {
        font-size: 2.5rem;
        font-weight: 700;
    }

    .carousel-caption p {
        font-size: 1.2rem;
    }

    .carousel-caption .btn-primary {
        padding: 10px 24px;
        font-size: 1rem;
        border-radius: 30px;
    }

    .carousel-control-prev-icon,
    .carousel-control-next-icon {
        background-color: rgba(0, 0, 0, 0.3);
        border-radius: 50%;
        padding: 10px;
    }

    .carousel-item.hero-slide {
        position: relative;
        height: 500px;
        background-size: cover !important;
        background-position: center !important;
    }

    .carousel-item.hero-slide::before {
        content: "";
        position: absolute;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.4);
        pointer-events: none;
        z-index: 1;
    }

    .carousel-caption {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        z-index: 2;
        color: white;
        text-align: center;
        width: 100%;
        padding: 0 15px;
    }

    .carousel-control-prev,
    .carousel-control-next {
        z-index: 3;
    }

    /* Product Card Section */
    .product-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .product-card {
        transition: all 0.3s ease-in-out;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        background-color: #fff;
        display: flex;
        flex-direction: column;
    }

    .product-card:hover {
        transform: scale(1.03);
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .card-text {
        font-size: 0.9rem;
        color: #666;
    }

    .btn-primary {
        font-size: 0.85rem;
        padding: 6px 14px;
        border-radius: 20px;
        cursor: pointer;
    }

    /* Footer */
    footer {
        background-color: #f8f9fa;
        padding: 20px 0;
        text-align: center;
        font-size: 0.9rem;
        color: #555;
    }
</style>

<!-- Hero Carousel -->
<div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">

        <!-- Slide 1 -->
        <div class="carousel-item active hero-slide" style="background: url('/storage/images/slide1.jpg') center center / cover no-repeat;">
            <div class="carousel-caption d-flex flex-column justify-content-center align-items-center h-100">
                <h1>Welcome to Queens Rebo</h1>
                <p>Discover our exclusive collection of high-quality products</p>
                <a href="/shop" class="btn btn-primary btn-lg mt-3">Shop Now</a>
            </div>
        </div>

        <!-- Slide 2 -->
        <div class="carousel-item hero-slide" style="background: url('/storage/images/slide2.jpg') center center / cover no-repeat;">
            <div class="carousel-caption d-flex flex-column justify-content-center align-items-center h-100">
                <h1>Style & Quality Combined</h1>
                <p>Shop the latest trends at unbeatable prices</p>
                <a href="/shop" class="btn btn-primary btn-lg mt-3">Browse Collection</a>
            </div>
        </div>

        <!-- Slide 3 -->
        <div class="carousel-item hero-slide" style="background: url('/storage/images/slide3.jpg') center center / cover no-repeat;">
            <div class="carousel-caption d-flex flex-column justify-content-center align-items-center h-100">
                <h1>Your Perfect Match Awaits</h1>
                <p>Curated pieces that fit your lifestyle</p>
                <a href="/shop" class="btn btn-primary btn-lg mt-3">Explore Now</a>
            </div>
        </div>
    </div>

    <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
    </button>
</div>

<!-- Product Cards Section -->
<div class="container my-5">
    <h2 class="text-center mb-4">Featured Products</h2>
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
        @foreach($latestProducts as $product)
            <div class="col d-flex">
                <div class="product-card w-100 h-100">
                    <img src="/storage/{{ $product->image }}" alt="{{ $product->name }}">
                    <div class="card-body text-center d-flex flex-column">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">₱{{ number_format($product->price, 2) }}</p>

                        @if ($product->quantity > 0)
                            <p class="card-text">Stock: {{ $product->quantity }}</p>
                        @else
                            <p class="card-text text-danger fw-bold">Out of Stock</p>
                        @endif

                        <button 
                            class="btn btn-primary mt-auto align-self-center mb-3" 
                            onclick="showLoadingAndRedirect({{ $product->id }})">
                            View Details
                        </button>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

<!-- Loading Overlay Component -->
<x-loading-overlay id="view-loading" />

<!-- Footer -->
<footer>
    <div class="container">
        <p>&copy; 2025 Queens Rebo. All rights reserved.</p>
    </div>
</footer>

<script>
    function showLoadingAndRedirect(productId) {
        const overlay = document.getElementById('view-loading');
        overlay.classList.add('active');
        setTimeout(() => {
            window.location.href = `/product/${productId}`;
        }, 800);
    }
</script>
@endsection
