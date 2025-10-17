@extends('layouts.website')

@section('content')
<!-- AOS for animations -->
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>
    :root {
        --primary: #222;
        --accent: #f4b400;
        --bg: #fafafa;
        --gray: #6c757d;
        --text: #1c1c1c;
        --shadow: rgba(0, 0, 0, 0.08);
    }

    body {
        font-family: 'Poppins', sans-serif;
        background-color: var(--bg);
        color: var(--text);
        overflow-x: hidden;
    }

    /* ---------- HERO ---------- */
    .hero {
        background: url('/storage/images/slide1.jpg') center/cover no-repeat;
        height: 90vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        position: relative;
    }

    .hero::before {
        content: "";
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, 0.45);
    }

    .hero-content {
        position: relative;
        z-index: 2;
        color: #fff;
        max-width: 700px;
        padding: 0 20px;
    }

    .hero-content h1 {
        font-size: 3rem;
        font-weight: 700;
        margin-bottom: 15px;
    }

    .hero-content p {
        font-size: 1.1rem;
        margin-bottom: 25px;
        color: #f2f2f2;
    }

    .hero-content .btn {
        background: var(--accent);
        color: #222;
        font-weight: 600;
        border-radius: 30px;
        padding: 12px 32px;
        transition: 0.3s;
    }

    .hero-content .btn:hover {
        background: #e2a800;
        transform: translateY(-3px);
    }

    /* ---------- AI FINDER ---------- */
    .ai-finder {
        background: #fff;
        border-radius: 20px;
        box-shadow: 0 10px 30px var(--shadow);
        padding: 60px;
        max-width: 900px;
        margin: -80px auto 80px;
        text-align: center;
        position: relative;
        z-index: 5;
    }

    .ai-finder h2 {
        font-weight: 700;
        color: var(--primary);
        margin-bottom: 10px;
    }

    .ai-finder input[type="text"],
    .ai-finder input[type="file"] {
        border: 1.5px solid var(--primary);
        border-radius: 10px;
        padding: 12px 16px;
        margin-bottom: 15px;
        width: 100%;
        transition: 0.3s;
    }

    .ai-finder input:focus {
        outline: none;
        box-shadow: 0 0 8px rgba(0, 0, 0, 0.2);
    }

    .ai-finder button {
        background: var(--primary);
        color: #fff;
        border: none;
        border-radius: 30px;
        padding: 12px 32px;
        font-weight: 600;
        transition: 0.3s;
    }

    .ai-finder button:hover {
        background: #000;
        transform: translateY(-2px);
    }

    /* ---------- BENEFITS ---------- */
    .benefits {
        background: #fff;
        padding: 80px 0;
        text-align: center;
    }

    .benefits .icon {
        font-size: 2rem;
        color: var(--accent);
        margin-bottom: 15px;
    }

    .benefits h5 {
        font-weight: 600;
        margin-bottom: 5px;
    }

    .benefits p {
        color: var(--gray);
        font-size: 0.9rem;
    }

    /* ---------- PRODUCTS ---------- */
    .products {
        background: var(--bg);
        padding: 100px 0;
    }

    .products h2 {
        text-align: center;
        font-weight: 700;
        margin-bottom: 50px;
    }

    .product-card {
        background: #fff;
        border-radius: 15px;
        box-shadow: 0 4px 20px var(--shadow);
        transition: all 0.3s;
        overflow: hidden;
        text-align: center;
    }

    .product-card:hover {
        transform: translateY(-5px);
    }

    .product-card img {
        width: 100%;
        height: 230px;
        object-fit: cover;
    }

    .product-card h5 {
        color: var(--primary);
        font-weight: 600;
        margin-top: 10px;
    }

    .product-card p {
        color: var(--gray);
    }

    /* ---------- CATEGORIES ---------- */
    .categories {
        padding: 80px 0;
        background: #fff;
    }

    .categories h2 {
        text-align: center;
        font-weight: 700;
        margin-bottom: 50px;
    }

    .category-card {
        border-radius: 15px;
        overflow: hidden;
        box-shadow: 0 4px 20px var(--shadow);
        text-align: center;
        transition: 0.3s;
    }

    .category-card:hover {
        transform: translateY(-6px);
    }

    .category-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .category-card h5 {
        padding: 15px 0;
        font-weight: 600;
    }

    /* ---------- TESTIMONIALS ---------- */
    .testimonials {
        background: var(--bg);
        padding: 100px 0;
        text-align: center;
    }

    .testimonial-card {
        background: #fff;
        border-radius: 15px;
        padding: 30px;
        box-shadow: 0 4px 15px var(--shadow);
        transition: 0.3s;
    }

    .testimonial-card:hover {
        transform: translateY(-5px);
    }

    .testimonial-card img {
        width: 80px;
        height: 80px;
        border-radius: 50%;
        margin-bottom: 15px;
        object-fit: cover;
    }

    .testimonial-card h6 {
        font-weight: 600;
    }

    /* ---------- SALE CTA ---------- */
    .sale-section {
        background: #fff7e0;
        padding: 80px 0;
        text-align: center;
    }

    .sale-section img {
        max-width: 220px;
        margin-bottom: 20px;
    }

    .sale-section h2 {
        font-weight: 700;
        margin-bottom: 10px;
    }

    .sale-section .btn {
        background: var(--accent);
        color: #222;
        border-radius: 30px;
        padding: 12px 30px;
        font-weight: 600;
    }

    footer {
        background: var(--primary);
        color: #eee;
        text-align: center;
        padding: 30px 0;
        font-size: 0.9rem;
    }

    .ai-finder {
        position: relative;
        overflow: hidden;
    }

    #bg-animation {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
    }
</style>

<!-- HERO -->
<section class="hero" data-aos="fade-in">
    <div class="hero-content">
        <h1>Make Your Interior More Minimalistic & Modern</h1>
        <p>Turn your space into a more stylish and functional environment with our premium products.</p>
        <a href="/shop" class="btn">Shop Now</a>
    </div>
</section>

<!-- AI FINDER -->
<section class="ai-finder" data-aos="zoom-in">
    <canvas id="bg-animation"></canvas>
    <h2>Find What You Need Instantly</h2>
    <p>Type a description or upload a product image — our AI will help you find the perfect match.</p>
    <form id="searchForm" method="POST" enctype="multipart/form-data" class="mt-4">
        @csrf
        <input type="text" name="query" placeholder="e.g. leather chair, wooden table">
        <input type="file" name="image" accept="image/*">
        <button type="submit">🔍 Search</button>
    </form>
</section>

<!-- BENEFITS -->
<section class="benefits">
    <div class="container">
        <div class="row g-4 justify-content-center">
            <div class="col-md-3" data-aos="fade-up">
                <div class="icon"><i class="bi bi-truck"></i></div>
                <h5>Free Services</h5>
                <p>Save cost with our free delivery.</p>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="100">
                <div class="icon"><i class="bi bi-credit-card"></i></div>
                <h5>Easy Payments</h5>
                <p>Secure and fast online payments.</p>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="200">
                <div class="icon"><i class="bi bi-arrow-repeat"></i></div>
                <h5>Return Policy</h5>
                <p>Return easily within 7 days.</p>
            </div>
            <div class="col-md-3" data-aos="fade-up" data-aos-delay="300">
                <div class="icon"><i class="bi bi-shield-check"></i></div>
                <h5>Warranty</h5>
                <p>Products come with warranty support.</p>
            </div>
        </div>
    </div>
</section>

<!-- FEATURED PRODUCTS -->
<section class="products">
    <div class="container">
        <h2>See Our Quality Products</h2>
        <div class="row g-4">
            @foreach($latestProducts as $product)
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                <div class="product-card">
                    <img src="/storage/{{ $product->image }}" alt="{{ $product->name }}">
                    <div class="p-3">
                        <h5>{{ $product->name }}</h5>
                        <p>₱{{ number_format($product->price, 2) }}</p>
                        <a href="/product/{{ $product->id }}" class="btn btn-sm btn-warning mt-2">View Details</a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>


<!-- TESTIMONIALS -->
<section class="testimonials">
    <div class="container">
        <h2>Our Client Reviews</h2>
        <div class="row g-4 justify-content-center">
            <div class="col-md-4" data-aos="fade-up">
                <div class="testimonial-card">
                    <img src="/storage/images/user1.jpg" alt="User">
                    <h6>Bunga Ulin</h6>
                    <p>"The products are high quality and delivery was fast! My living room looks amazing now."</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="100">
                <div class="testimonial-card">
                    <img src="/storage/images/user2.jpg" alt="User">
                    <h6>Rudi Suljam</h6>
                    <p>"Superb craftsmanship and modern design. I’ll definitely order again."</p>
                </div>
            </div>
            <div class="col-md-4" data-aos="fade-up" data-aos-delay="200">
                <div class="testimonial-card">
                    <img src="/storage/images/user3.jpg" alt="User">
                    <h6>Impek Ino</h6>
                    <p>"Excellent customer service and the furniture exceeded my expectations."</p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- SALE CTA -->
<section class="sale-section" data-aos="zoom-in">
    <img src="/storage/images/yellow-chair.png" alt="Sale Chair">
    <h2>World Best Sofas</h2>
    <p>Sale Ends Very Soon</p>
    <a href="/shop" class="btn">Order Now</a>
</section>

<footer>
    <p>&copy; 2025 Queens Rebo Hardware — Designed for comfort and style.</p>
</footer>

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
AOS.init({ duration: 1000, once: true });

// --- ENHANCED BACKGROUND ANIMATION ---
const canvas = document.getElementById('bg-animation');
const ctx = canvas.getContext('2d');

// Resize canvas dynamically
function resizeCanvas() {
    canvas.width = canvas.offsetWidth;
    canvas.height = canvas.offsetHeight;
}
resizeCanvas();
window.addEventListener('resize', resizeCanvas);

// Create particles
let particles = [];
const numParticles = 55; // more particles for denser effect

function createParticles() {
    particles = [];
    for (let i = 0; i < numParticles; i++) {
        particles.push({
            x: Math.random() * canvas.width,
            y: Math.random() * canvas.height,
            r: Math.random() * 4 + 2, // larger circles
            dx: (Math.random() - 0.5) * 0.7, // slightly faster
            dy: (Math.random() - 0.5) * 0.7
        });
    }
}
createParticles();

// Animate particles
function animate() {
    ctx.clearRect(0, 0, canvas.width, canvas.height);

    // brighter, more visible gold particles
    ctx.fillStyle = "rgba(244,180,0,0.45)";

    particles.forEach(p => {
        ctx.beginPath();
        ctx.arc(p.x, p.y, p.r, 0, Math.PI * 2);
        ctx.fill();

        p.x += p.dx;
        p.y += p.dy;

        // Bounce effect on edges
        if (p.x < 0 || p.x > canvas.width) p.dx *= -1;
        if (p.y < 0 || p.y > canvas.height) p.dy *= -1;
    });

    requestAnimationFrame(animate);
}
animate();
</script>


@endsection