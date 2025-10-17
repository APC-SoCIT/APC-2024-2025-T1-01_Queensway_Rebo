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
        --shadow: rgba(0, 0, 0, 0.06);
        --border: #e5e5e5;
    }

    body {
        background-color: var(--bg);
        color: var(--text);
        font-family: 'Poppins', sans-serif;
    }

    /* ---------- PRODUCT SECTION ---------- */
    .product-section {
        max-width: 1200px;
        margin: 80px auto;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 6px 20px var(--shadow);
        padding: 60px;
    }

    .product-image {
        border-radius: 15px;
        width: 100%;
        height: 420px;
        object-fit: cover;
        background-color: #f2f2f2;
    }

    .product-title {
        font-weight: 700;
        font-size: 2rem;
        color: var(--primary);
        margin-bottom: 10px;
    }

    .product-price {
        font-size: 1.6rem;
        font-weight: 700;
        color: var(--accent);
        margin-bottom: 15px;
    }

    .product-description {
        color: var(--gray);
        font-size: 0.95rem;
        line-height: 1.7;
    }

    .product-meta {
        color: var(--gray);
        font-size: 0.9rem;
        margin-bottom: 20px;
    }

    /* ---------- BUTTONS ---------- */
    .btn-buy,
    .btn-cart {
        border: none;
        border-radius: 30px;
        padding: 12px 30px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-buy {
        background: var(--accent);
        color: var(--primary);
    }

    .btn-buy:hover {
        background: #e2a800;
        transform: translateY(-2px);
    }

    .btn-cart {
        border: 1.5px solid var(--primary);
        color: var(--primary);
        background: #fff;
    }

    .btn-cart:hover {
        background: var(--primary);
        color: #fff;
        transform: translateY(-2px);
    }

    /* ---------- TILE CALCULATOR ---------- */
    .calculator-section {
        text-align: center;
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 15px var(--shadow);
        padding: 60px;
        margin: 80px auto;
        max-width: 900px;
    }

    .calculator-section h2 {
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 10px;
    }

    .calculator-section p {
        color: var(--gray);
        margin-bottom: 30px;
    }

    .form-control {
        border: 1.5px solid var(--border);
        border-radius: 10px;
        padding: 10px 14px;
        transition: 0.3s;
    }

    .form-control:focus {
        border-color: var(--accent);
        box-shadow: 0 0 6px rgba(244, 180, 0, 0.3);
        outline: none;
    }

    .btn-calc {
        background: var(--primary);
        color: #fff;
        border-radius: 30px;
        padding: 12px 26px;
        font-weight: 600;
        transition: 0.3s;
    }

    .btn-calc:hover {
        background: #000;
        transform: translateY(-2px);
    }

    /* ---------- TUTORIAL VIDEOS ---------- */
    .tutorial-section {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 4px 15px var(--shadow);
        padding: 60px;
        margin: 80px auto;
        max-width: 1100px;
    }

    .tutorial-section h2 {
        font-weight: 700;
        color: var(--primary);
        text-align: center;
        margin-bottom: 40px;
    }

    .video-wrapper {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
        justify-content: center;
    }

    .video-wrapper iframe {
        border-radius: 15px;
        box-shadow: 0 4px 10px var(--shadow);
        width: 100%;
        max-width: 480px;
        height: 270px;
    }

    /* ---------- RELATED PRODUCTS ---------- */
    .related-section {
        background: #fff;
        padding: 80px 60px;
        border-radius: 20px;
        box-shadow: 0 4px 20px var(--shadow);
        max-width: 1200px;
        margin: 0 auto 100px;
    }

    .related-section h2 {
        text-align: center;
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 40px;
    }

    .view-btn {
        background: var(--accent);
        color: var(--primary);
        border-radius: 25px;
        font-weight: 600;
        padding: 10px 25px;
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 3px 10px var(--shadow);
    }

    .view-btn:hover {
        background: #e2a800;
        color: #000;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px var(--shadow);
    }
</style>

<section class="product-section" data-aos="fade-up">
    <div class="row align-items-center g-5">
        <div class="col-md-6">
            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
        </div>

        <div class="col-md-6">
            <span class="text-muted mb-2 d-block">{{ $product->category ?? 'Category' }}</span>
            <h1 class="product-title">{{ $product->name }}</h1>
            <div class="product-price">₱{{ number_format($product->price, 2) }}</div>
            <p class="product-meta">
                @if($product->sku) <strong>SKU:</strong> {{ $product->sku }} <br> @endif
                <strong>Stock:</strong>
                @if($product->quantity > 0)
                {{ $product->quantity }}
                @else
                <span class="text-danger fw-bold">Out of Stock</span>
                @endif
            </p>
            <p class="product-description">{{ $product->description }}</p>

            @if($product->quantity > 0)
            <form action="{{ route('cart.add') }}" method="POST" class="mt-4">
                @csrf
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" name="quantity" value="1" min="1" max="{{ $product->quantity }}"
                    class="form-control w-25 mb-3" required>
                <input type="hidden" name="product_id" value="{{ $product->id }}">
                @auth
                <button type="submit" class="btn-cart me-2">Add to Cart</button>
                <a href="{{ route('checkout') }}" class="btn-buy">Buy Now</a>
                @else
                <button type="button" class="btn-cart" disabled>Login to Purchase</button>
                @endauth
            </form>
            @endif
        </div>
    </div>
</section>

<!-- TILE CALCULATOR -->
<section class="calculator-section" data-aos="fade-up">
    <h2>🧮 Tile Calculator</h2>
    <p>Estimate how many tiles you’ll need for your project.</p>
    <form class="row justify-content-center g-3" id="tile-calculator">
        <div class="col-md-5">
            <label>Room Length (m)</label>
            <input type="number" step="0.01" id="room_length" class="form-control" required>
        </div>
        <div class="col-md-5">
            <label>Room Width (m)</label>
            <input type="number" step="0.01" id="room_width" class="form-control" required>
        </div>
        <div class="col-md-5">
            <label>Tile Length (cm)</label>
            <input type="number" step="0.1" id="tile_length" class="form-control"
                value="{{ $product->length ?? '' }}" required>
        </div>
        <div class="col-md-5">
            <label>Tile Width (cm)</label>
            <input type="number" step="0.1" id="tile_width" class="form-control"
                value="{{ $product->width ?? '' }}" required>
        </div>
        <div class="col-md-10">
            <button type="button" class="btn-calc mt-3" onclick="calculateTiles()">Calculate</button>
        </div>
    </form>

    <div id="tile-result" class="mt-4" style="display:none;">
        <h4 class="fw-bold">Estimated Tiles Needed: <span id="tiles-needed"></span></h4>
        <p class="text-muted"><small>(Including 10% wastage)</small></p>
    </div>
</section>

<!-- 🎥 Tutorial Videos -->
@if(!empty($tutorialVideos))
<section class="tutorial-section" data-aos="fade-up">
    <h2>🎥 Installation & Tutorial Videos</h2>
    <div class="video-wrapper">
        @foreach($tutorialVideos as $video)
            <div>
                <video class="w-100 rounded" controls>
                    <source src="{{ asset('storage/videos/' . rawurlencode($video)) }}" type="video/mp4">
                </video>
                <p class="mt-2 text-center fw-semibold">
                    {{ pathinfo($video, PATHINFO_FILENAME) }}
                </p>
            </div>
        @endforeach
    </div>
</section>
@endif



<!-- RELATED PRODUCTS -->
@if(isset($relatedProducts) && $relatedProducts->count())
<section class="related-section" data-aos="fade-up">
    <h2>Related Products</h2>
    <div class="row g-4 justify-content-center">
        @foreach($relatedProducts as $related)
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden h-100 text-center">
                <img src="/storage/{{ $related->image }}" alt="{{ $related->name }}"
                    class="img-fluid" style="height: 220px; object-fit: cover;">
                <div class="p-3">
                    <h6 class="fw-semibold mt-2" style="color: var(--primary);">{{ $related->name }}</h6>
                    <p class="text-muted mb-3">₱{{ number_format($related->price, 2) }}</p>
                    <a href="/product/{{ $related->id }}" class="btn view-btn">View Details</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</section>
@endif

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({ duration: 1000, once: true });

    function calculateTiles() {
        const l = parseFloat(room_length.value);
        const w = parseFloat(room_width.value);
        const tl = parseFloat(tile_length.value);
        const tw = parseFloat(tile_width.value);
        if (l <= 0 || w <= 0 || tl <= 0 || tw <= 0)
            return alert('Please enter valid positive numbers.');
        const roomArea = l * w;
        const tileArea = (tl / 100) * (tw / 100);
        const tiles = Math.ceil((roomArea / tileArea) * 1.1);
        document.getElementById('tiles-needed').textContent = tiles;
        document.getElementById('tile-result').style.display = 'block';
    }
</script>
@endsection
