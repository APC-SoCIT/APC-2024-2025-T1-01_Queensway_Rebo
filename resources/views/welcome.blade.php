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
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            z-index: 2;
            color: white;
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

        .carousel-item.hero-slide {
            height: 500px;
            background-size: cover !important;
            background-position: center !important;
        }

        .carousel-item.hero-slide::before {
            content: "";
            position: absolute;
            inset: 0;
            background-color: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }

        /* Product Card */
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
            <div class="carousel-item active hero-slide"
                style="background: url('/storage/images/slide1.jpg') center center / cover no-repeat;">
                <div class="carousel-caption">
                    <h1>Welcome to Queens Rebo</h1>
                    <p>Discover our exclusive collection of high-quality products</p>
                    <a href="/shop" class="btn btn-primary btn-lg mt-3">Shop Now</a>
                </div>
            </div>
            <div class="carousel-item hero-slide"
                style="background: url('/storage/images/slide2.jpg') center center / cover no-repeat;">
                <div class="carousel-caption">
                    <h1>Style & Quality Combined</h1>
                    <p>Shop the latest trends at unbeatable prices</p>
                    <a href="/shop" class="btn btn-primary btn-lg mt-3">Browse Collection</a>
                </div>
            </div>
            <div class="carousel-item hero-slide"
                style="background: url('/storage/images/slide3.jpg') center center / cover no-repeat;">
                <div class="carousel-caption">
                    <h1>Your Perfect Match Awaits</h1>
                    <p>Curated pieces that fit your lifestyle</p>
                    <a href="/shop" class="btn btn-primary btn-lg mt-3">Explore Now</a>
                </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#heroCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#heroCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
        </button>
    </div>

    <!-- AI Product Finder -->
    <div class="container my-5">
        <div class="p-4 shadow-lg rounded bg-light">
            <h2 class="text-center mb-4">Find What You Need Instantly</h2>
            <p class="text-center text-muted mb-4">
                Describe the product you’re looking for or upload an image — we’ll find the best matches for you.
            </p>

            <form id="searchForm" method="POST" enctype="multipart/form-data" class="text-center">
                @csrf
                <div class="mb-3">
                    <input type="text" name="query" class="form-control mb-2 border border-primary shadow-sm"
                        placeholder="Type what you are looking for...">
                </div>
                <div class="mb-3">
                    <input type="file" name="image" class="form-control mb-2 border border-primary shadow-sm"
                        accept="image/*">
                </div>
                <button type="submit" class="btn btn-primary px-4 py-2">🔍 Search</button>
            </form>

        </div>
    </div>

    <!-- Search Results Modal -->
    <div class="modal fade" id="resultsModal" tabindex="-1" aria-labelledby="resultsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="resultsModalLabel">Search Results</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="resultsContent">
                    <p class="text-center">Loading...</p>
                </div>
            </div>
        </div>
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
                            <button class="btn btn-primary mt-auto mb-3"
                                onclick="showLoadingAndRedirect({{ $product->id }})">View Details</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <x-loading-overlay id="view-loading" />

    <footer>
        <div class="container">
            <p>&copy; 2025 Queens Rebo. All rights reserved.</p>
        </div>
    </footer>

    <script>
        const overlay = document.getElementById('view-loading');

        function showLoadingAndRedirect(productId) {
            overlay.classList.add('active');
            setTimeout(() => { window.location.href = `/product/${productId}`; }, 800);
        }

        // Handle AJAX search with loading overlay

        document.getElementById('searchForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const overlay = document.getElementById('view-loading');
            overlay.classList.add('active'); // show loading

            const formData = new FormData(this);
            let action = '';

            // Determine which endpoint to use
            if (formData.get('image') && formData.get('image').name) {
                action = "{{ route('search.image') }}";
            } else if (formData.get('query')) {
                action = "{{ route('search.text') }}";
            } else {
                overlay.classList.remove('active');
                alert('Please enter a query or upload an image.');
                return;
            }

            fetch(action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            })
                .then(res => res.text())
                .then(html => {
                    overlay.classList.remove('active'); // hide loading
                    document.getElementById('resultsContent').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('resultsModal')).show();
                })
                .catch(err => {
                    overlay.classList.remove('active');
                    document.getElementById('resultsContent').innerHTML = '<p class="text-danger">Error loading results.</p>';
                });
        });

    </script>
@endsection