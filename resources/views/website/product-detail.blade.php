@extends('layouts.website')

@section('content')
    <!-- AOS Animations -->
    <link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

    <style>
        :root {
            --primary: #004b8d;
            --accent: #f4b400;
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

        /* Section Divider */
        .section-divider {
            width: 120px;
            height: 5px;
            background: var(--divider-gradient);
            border-radius: 10px;
            margin: 40px auto;
        }

        /* Product Detail Container */
        .product-detail-container {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 30px var(--card-shadow);
            padding: 50px 40px;
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .product-detail-container::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--divider-gradient);
        }

        .product-detail-container:hover {
            transform: translateY(-4px);
            box-shadow: 0 16px 36px rgba(0, 75, 141, 0.15);
        }

        .product-detail-container img {
            border-radius: 15px;
            box-shadow: 0 4px 20px var(--card-shadow);
        }

        .product-detail-container h1 {
            font-weight: 700;
            color: var(--primary);
        }

        .product-detail-container h3 {
            color: var(--accent);
            font-weight: 700;
        }

        /* Buttons */
        .btn-primary {
            background-color: var(--primary);
            border: none;
            border-radius: 30px;
            padding: 10px 28px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #003c73;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 75, 141, 0.3);
        }

        .btn-success {
            border-radius: 30px;
            font-weight: 600;
        }

        /* Related Section / Tile Calculator / Tutorials */
        .related-section {
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 10px 30px var(--card-shadow);
            padding: 50px 40px;
            margin-top: 60px;
            position: relative;
            overflow: hidden;
        }

        .related-section::after {
            content: "";
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 6px;
            background: var(--divider-gradient);
        }

        .related-title {
            font-size: 1.8rem;
            font-weight: 700;
            color: var(--primary);
            text-align: center;
            margin-bottom: 30px;
        }

        .related-card {
            border-radius: 15px;
            overflow: hidden;
            border: none;
            box-shadow: 0 4px 20px var(--card-shadow);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }

        .related-card:hover {
            transform: translateY(-6px);
            box-shadow: 0 10px 26px rgba(0, 0, 0, 0.1);
        }

        .related-card img {
            height: 180px;
            object-fit: cover;
            width: 100%;
        }

        /* Inputs */
        input[type="number"] {
            border: 1.5px solid var(--primary);
            border-radius: 10px;
            padding: 10px 14px;
            transition: all 0.3s;
        }

        input[type="number"]:focus {
            outline: none;
            border-color: var(--accent);
            box-shadow: 0 0 8px rgba(0, 75, 141, 0.3);
        }
    </style>

    <div class="container my-5">
        <div class="product-detail-container shadow-sm" data-aos="fade-up">
            <div class="row align-items-center g-4">
                <div class="col-md-6">
                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid w-100">
                </div>
                <div class="col-md-6">
                    <h1>{{ $product->name }}</h1>
                    <h3 class="mb-3">₱{{ number_format($product->price, 2) }}</h3>

                    @if($product->sku)
                        <p><strong>SKU:</strong> {{ $product->sku }}</p>
                    @endif

                    <p><strong>Description:</strong><br>{{ $product->description }}</p>

                    <p>
                        <strong>Stock:</strong>
                        @if($product->quantity > 0)
                            {{ $product->quantity }}
                        @else
                            <span class="text-danger fw-bold">Out of Stock</span>
                        @endif
                    </p>

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
                                <button type="submit" class="btn btn-primary">Add to Cart</button>
                            @else
                                <button type="button" class="btn btn-secondary" disabled>
                                    Add to Cart (Login Required)
                                </button>
                            @endauth
                        </form>
                    @endif
                </div>
            </div>
        </div>

        <!-- Divider -->
        <div class="section-divider" data-aos="zoom-in"></div>

        <!-- 🧮 Tile Calculator -->
        <div class="related-section" data-aos="fade-up">
            <h2 class="related-title">🧮 Tile Calculator</h2>
            <p class="text-center text-muted mb-4">Estimate how many tiles you’ll need for your project.</p>

            <form id="tile-calculator" class="row g-3 justify-content-center">
                <div class="col-md-5">
                    <label class="form-label">Room Length (m)</label>
                    <input type="number" step="0.01" id="room_length" class="form-control" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Room Width (m)</label>
                    <input type="number" step="0.01" id="room_width" class="form-control" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Tile Length (cm)</label>
                    <input type="number" step="0.1" id="tile_length" class="form-control"
                        value="{{ $product->length ?? '' }}" required>
                </div>
                <div class="col-md-5">
                    <label class="form-label">Tile Width (cm)</label>
                    <input type="number" step="0.1" id="tile_width" class="form-control" value="{{ $product->width ?? '' }}"
                        required>
                </div>
                <div class="col-md-10 text-center">
                    <button type="button" class="btn btn-primary mt-3" onclick="calculateTiles()">Calculate</button>
                </div>
            </form>

            <div class="mt-4 text-center" id="tile-result" style="display:none;">
                <h4>Estimated Tiles Needed: <span id="tiles-needed"></span></h4>
                <p><small>(Including 10% wastage)</small></p>
                @if($product->quantity > 0)
                    <form id="tile-cart-form" action="{{ route('cart.add') }}" method="POST">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">
                        <input type="hidden" name="quantity" id="calculated-quantity">
                        @auth
                            <button type="submit" class="btn btn-success mt-2">Add to Cart with Calculated Quantity</button>
                        @else
                            <button type="button" class="btn btn-secondary mt-2" disabled>
                                Login Required
                            </button>
                        @endauth
                    </form>
                @endif
            </div>
        </div>

        <!-- Tutorial Videos -->
        @if(!empty($tutorialVideos))
            <div class="related-section" data-aos="fade-up">
                <h2 class="related-title">🎥 Tutorial Videos</h2>
                <div class="row row-cols-1 row-cols-md-2 g-4">
                    @foreach($tutorialVideos as $video)
                        <div class="col">
                            <div class="related-card p-3">
                                <video class="w-100 rounded" controls>
                                    <source src="{{ asset('storage/videos/' . rawurlencode($video)) }}" type="video/mp4">
                                </video>
                                <p class="mt-2 text-center fw-semibold">{{ pathinfo($video, PATHINFO_FILENAME) }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        <!-- Related Products -->
        @if(isset($relatedProducts) && $relatedProducts->count())
            <div class="related-section" data-aos="fade-up">
                <h2 class="related-title">🧰 Related Products</h2>
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4">
                    @foreach($relatedProducts as $related)
                        <div class="col">
                            <div class="related-card h-100">
                                <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}">
                                <div class="card-body text-center">
                                    <h6>{{ $related->name }}</h6>
                                    <p class="text-primary fw-semibold">₱{{ number_format($related->price, 2) }}</p>
                                    <p class="{{ $related->quantity > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $related->quantity > 0 ? 'In Stock' : 'Out of Stock' }}
                                    </p>
                                    <a href="{{ route('product.show', $related->id) }}"
                                        class="btn btn-outline-primary btn-sm rounded-pill">View</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <x-loading-overlay id="view-loading" />

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 1000, once: true });

        document.getElementById('add-to-cart-form')?.addEventListener('submit', () => {
            document.getElementById('view-loading').classList.add('active');
        });

        function calculateTiles() {
            const l = parseFloat(room_length.value);
            const w = parseFloat(room_width.value);
            const tl = parseFloat(tile_length.value);
            const tw = parseFloat(tile_width.value);
            if (l <= 0 || w <= 0 || tl <= 0 || tw <= 0) return alert('Please enter valid positive numbers.');
            const roomArea = l * w;
            const tileArea = (tl / 100) * (tw / 100);
            const tiles = Math.ceil((roomArea / tileArea) * 1.1);
            document.getElementById('tiles-needed').textContent = tiles;
            document.getElementById('tile-result').style.display = 'block';
            document.getElementById('calculated-quantity').value = tiles;
        }
    </script>
@endsection