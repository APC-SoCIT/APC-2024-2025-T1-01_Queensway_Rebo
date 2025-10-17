@extends('layouts.website')

@section('content')
    <!-- AOS for animations -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #004b8d;
            /* hardware blue */
            --accent: #f4b400;
            /* warm gold accent */
            --background: #f8f9fb;
            --white: #ffffff;
            --gray: #6c757d;
            --text-dark: #1c1c1c;
            --card-shadow: rgba(0, 0, 0, 0.08);
            --divider-gradient: linear-gradient(90deg, rgba(0, 75, 141, 0.2), rgba(244, 180, 0, 0.3));
        }

        body {
            background-color: var(--background);
            color: var(--text-dark);
            font-family: 'Poppins', 'Inter', sans-serif;
            overflow-x: hidden;
        }

        /* --- HERO --- */
        #heroCarousel {
            margin-bottom: 80px;
        }

        .hero-slide {
            height: 520px;
            background-size: cover !important;
            background-position: center !important;
            position: relative;
        }

        .hero-slide::before {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(to right, rgba(0, 0, 0, 0.6), rgba(0, 0, 0, 0.3));
            z-index: 1;
        }

        .carousel-caption {
            z-index: 2;
            text-align: left;
            left: 10%;
            bottom: 20%;
        }

        .carousel-caption h1 {
            font-size: 3rem;
            font-weight: 700;
            color: var(--white);
            text-shadow: 0 2px 6px rgba(0, 0, 0, 0.4);
        }

        .carousel-caption p {
            font-size: 1.2rem;
            color: #f0f0f0;
            max-width: 500px;
        }

        .carousel-caption .btn-primary {
            background-color: var(--accent);
            color: #222;
            font-weight: 600;
            border: none;
            border-radius: 30px;
            padding: 12px 32px;
            transition: all 0.3s ease;
        }

        .carousel-caption .btn-primary:hover {
            background-color: #e2a800;
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(0, 0, 0, 0.2);
        }

        /* --- SECTION DIVIDER --- */
        .section-divider {
            width: 120px;
            height: 5px;
            background: var(--divider-gradient);
            border-radius: 10px;
            margin: 40px auto;
        }

        /* --- AI FINDER --- */
        .ai-finder {
            background: var(--white) url('https://www.transparenttextures.com/patterns/cubes.png');
            border-radius: 20px;
            box-shadow: 0 10px 30px var(--card-shadow);
            padding: 60px;
            max-width: 900px;
            margin: 100px auto;
            text-align: center;
            transition: transform 0.3s ease, box-shadow 0.3s;
            position: relative;
            overflow: hidden;
        }

        .ai-finder::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--divider-gradient);
        }

        .ai-finder:hover {
            transform: translateY(-6px);
            box-shadow: 0 16px 36px rgba(0, 75, 141, 0.15);
        }

        .ai-finder h2 {
            font-weight: 700;
            color: var(--primary);
        }

        .ai-finder p {
            color: var(--gray);
        }

        .ai-finder input[type="text"],
        .ai-finder input[type="file"] {
            border: 1.5px solid var(--primary);
            border-radius: 10px;
            padding: 12px 16px;
            margin-bottom: 15px;
            width: 100%;
            transition: all 0.3s;
        }

        .ai-finder input[type="text"]:focus,
        .ai-finder input[type="file"]:focus {
            outline: none;
            box-shadow: 0 0 8px rgba(0, 75, 141, 0.3);
            border-color: var(--accent);
        }

        .ai-finder button {
            background-color: var(--primary);
            color: white;
            border: none;
            border-radius: 30px;
            padding: 12px 32px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .ai-finder button:hover {
            background-color: #003c73;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 75, 141, 0.3);
        }

        /* --- PRODUCTS --- */
        .products {
            background: linear-gradient(180deg, #f8f9fb 0%, #eef2f7 100%);
            padding: 100px 0;
            position: relative;
        }

        .products::before {
            content: "";
            position: absolute;
            top: -30px;
            left: 0;
            right: 0;
            height: 50px;
            background: var(--divider-gradient);
            clip-path: polygon(0 100%, 100% 0, 100% 100%);
        }

        .product-card {
            background: var(--white);
            border-radius: 15px;
            box-shadow: 0 4px 20px var(--card-shadow);
            transition: all 0.3s ease;
            overflow: hidden;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.1);
        }

        .product-card img {
            height: 230px;
            width: 100%;
            object-fit: cover;
        }

        .card-body {
            text-align: center;
            padding: 20px;
        }

        .product-card h5 {
            color: var(--primary);
            font-weight: 600;
        }

        .product-card p {
            color: var(--gray);
        }

        .product-card .btn {
            background-color: var(--accent);
            color: #222;
            font-weight: 600;
            border-radius: 30px;
            padding: 8px 20px;
            transition: 0.3s;
        }

        .product-card .btn:hover {
            background-color: #e2a800;
        }

        .product-card img {
            width: 100%;
            height: auto;
            max-height: 230px;
            object-fit: contain;
        }


        /* --- FOOTER --- */
        footer {
            background-color: var(--primary);
            color: var(--white);
            padding: 40px 0;
            text-align: center;
            font-size: 0.9rem;
        }

        footer p {
            margin: 0;
            color: #e0e0e0;
        }
    </style>


    <!-- HERO CAROUSEL -->
    <div id="heroCarousel" class="carousel slide" data-bs-ride="carousel">
        <div class="carousel-inner">
            <div class="carousel-item active hero-slide" style="background-image: url('/storage/images/slide1.jpg')">
                <div class="carousel-caption" data-aos="fade-up">
                    <h1>Build with Quality</h1>
                    <p>Tiles, fixtures, and tools designed for long-lasting performance.</p>
                    <a href="/shop" class="btn btn-primary mt-3">Shop Now</a>
                </div>
            </div>
            <div class="carousel-item hero-slide" style="background-image: url('/storage/images/slide2.jpg')">
                <div class="carousel-caption" data-aos="fade-up">
                    <h1>Modern Hardware, Modern Living</h1>
                    <p>Find premium materials that fit your home and lifestyle.</p>
                    <a href="/shop" class="btn btn-primary mt-3">Explore Products</a>
                </div>
            </div>
            <div class="carousel-item hero-slide" style="background-image: url('/storage/images/slide3.jpg')">
                <div class="carousel-caption" data-aos="fade-up">
                    <h1>Precision in Every Piece</h1>
                    <p>Trusted by builders, loved by homeowners.</p>
                    <a href="/shop" class="btn btn-primary mt-3">View Collection</a>
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

    <!-- AI FINDER -->
    <section class="ai-finder" data-aos="zoom-in">
        <h2>Find What You Need Instantly</h2>
        <p>Type a description or upload a product image — our AI will help you find the perfect match.</p>
        <form id="searchForm" method="POST" enctype="multipart/form-data" class="mt-4">
            @csrf
            <input type="text" name="query" placeholder="e.g. white ceramic tile 12x12">
            <input type="file" name="image" accept="image/*">
            <button type="submit">🔍 Search</button>
        </form>
    </section>

    <!-- MODAL RESULTS -->
    <div class="modal fade" id="resultsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5>Search Results</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body" id="resultsContent">
                    <p class="text-center text-muted py-5">Loading...</p>
                </div>
            </div>
        </div>
    </div>

    <!-- FEATURED PRODUCTS -->
    <section class="products container" data-aos="fade-up">
        <h2 class="text-center fw-bold mb-5">Featured Products</h2>
        <div class="row g-4">
            @foreach($latestProducts as $product)
                <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                    <div class="product-card">
                        <img src="/storage/{{ $product->image }}" alt="{{ $product->name }}">
                        <div class="card-body">
                            <h5>{{ $product->name }}</h5>
                            <p>₱{{ number_format($product->price, 2) }}</p>
                            @if ($product->quantity > 0)
                                <p>Stock: {{ $product->quantity }}</p>
                            @else
                                <p class="text-danger fw-bold">Out of Stock</p>
                            @endif
                            <button class="btn mt-2" onclick="showLoadingAndRedirect({{ $product->id }})">View Details</button>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </section>

    <x-loading-overlay id="view-loading" />

    <footer>
        <div class="container">
            <p>&copy; 2025 Queens Rebo Hardware. Built for quality and style.</p>
        </div>
    </footer>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 1000, once: true });

        const overlay = document.getElementById('view-loading');

        function showLoadingAndRedirect(id) {
            overlay.classList.add('active');
            setTimeout(() => { window.location.href = `/product/${id}`; }, 800);
        }

        document.getElementById('searchForm').addEventListener('submit', e => {
            e.preventDefault();
            overlay.classList.add('active');

            const formData = new FormData(e.target);
            const imageFile = formData.get('image');
            const action = imageFile && imageFile.name ? "{{ route('search.image') }}" : "{{ route('search.text') }}";

            fetch(action, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                body: formData
            })
                .then(res => res.text())
                .then(html => {
                    overlay.classList.remove('active');
                    document.getElementById('resultsContent').innerHTML = html;
                    new bootstrap.Modal(document.getElementById('resultsModal')).show();
                })
                .catch(() => {
                    overlay.classList.remove('active');
                    document.getElementById('resultsContent').innerHTML =
                        '<p class="text-danger text-center">Error loading results.</p>';
                });
        });
    </script>
@endsection