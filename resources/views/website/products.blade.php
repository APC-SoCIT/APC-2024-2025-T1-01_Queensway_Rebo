@extends('layouts.website')

@section('content')
<style>
    .product-card img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .product-card {
        transition: all 0.3s ease-in-out;
        padding: 15px;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        background-color: #fff;
    }

    .product-card:hover {
        transform: scale(1.03);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
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
    }

    .category-filter input[type="radio"] {
        display: none;
    }

    .category-filter label {
        display: block;
        padding: 10px 15px;
        border: 1px solid #ddd;
        border-radius: 8px;
        cursor: pointer;
        margin-bottom: 8px;
        transition: all 0.2s;
    }

    .category-filter input[type="radio"]:checked + label {
        background-color: #007bff;
        color: white;
        border-color: #007bff;
    }

    .sort-select {
        max-width: 200px;
        font-size: 0.9rem;
    }
</style>

<div class="container py-5">
    <div class="row g-4">
        <!-- Sidebar Filter -->
        <div class="col-lg-3">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <strong>Filter by Category</strong>
                </div>
                <div class="card-body category-filter">
                    <form id="category-filter-form">
                        @php
                            $categories = [
                                'Tiles',
                                'Vinyl',
                                'Borders',
                                'Mosaics',
                                'Sanitary Wares',
                                'WPC Panels & Wall Cladding',
                                'Tile Adhesive, Grout & Epoxy',
                                'Tools, Tile Spacers & Levelers'
                            ];
                            $selectedCategory = request('category');
                        @endphp

                        @foreach ($categories as $category)
                            <div>
                                <input type="radio" name="category" id="cat-{{ Str::slug($category) }}" value="{{ $category }}"
                                    {{ $selectedCategory === $category ? 'checked' : '' }}>
                                <label for="cat-{{ Str::slug($category) }}">{{ $category }}</label>
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-outline-primary btn-sm mt-3 w-100">Apply Filter</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products & Sort -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="h5 mb-0">All Products</h2>
                <select class="form-select sort-select" id="sort">
                    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
            </div>

            @if ($products->count())
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                    @foreach ($products as $product)
                        <div class="col">
                            <div class="product-card h-100">
                                <img src="/storage/{{ $product->image }}" class="card-img-top rounded" alt="{{ $product->name }}">
                                <div class="card-body text-center">
                                    <h5 class="card-title">{{ $product->name }}</h5>
                                    <p class="card-text">₱{{ number_format($product->price, 2) }}</p>

                                    @if ($product->quantity > 0)
                                        <p class="card-text">Stock: {{ $product->quantity }}</p>
                                    @else
                                        <p class="card-text text-danger fw-bold">Out of Stock</p>
                                    @endif

                                    <a href="{{ route('product.show', $product->id) }}" class="btn btn-primary">View Details</a>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="alert alert-info text-center mt-4">No products found.</div>
            @endif

            <!-- Pagination -->
            <div class="pagination-container text-center mt-4">
                <ul class="pagination">
                    @if ($products->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">« Previous</span></li>
                    @else
                        <li class="page-item"><a href="{{ $products->previousPageUrl() }}" class="page-link">« Previous</a></li>
                    @endif

                    @for ($i = 1; $i <= $products->lastPage(); $i++)
                        <li class="page-item {{ $i == $products->currentPage() ? 'active' : '' }}">
                            <a href="{{ $products->url($i) }}" class="page-link">{{ $i }}</a>
                        </li>
                    @endfor

                    @if ($products->hasMorePages())
                        <li class="page-item"><a href="{{ $products->nextPageUrl() }}" class="page-link">Next »</a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link">Next »</span></li>
                    @endif
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Loading Overlay -->
<x-loading-overlay id="view-loading" />

<script>
    function showLoading() {
        document.getElementById('view-loading').classList.add('active');
    }

    // Sorting change
    document.getElementById('sort').addEventListener('change', function () {
        showLoading();
        const url = new URL(window.location.href);
        url.searchParams.set('sort', this.value);
        window.location.href = url.toString();
    });

    // Filter submit
    document.getElementById('category-filter-form').addEventListener('submit', function (e) {
        e.preventDefault();
        showLoading();
        const selectedCategory = document.querySelector('input[name="category"]:checked')?.value || '';
        const url = new URL(window.location.href);
        url.searchParams.set('category', selectedCategory);
        window.location.href = url.toString();
    });

    // View Details buttons click
    document.querySelectorAll('.btn-primary').forEach(btn => {
        if (btn.textContent.trim() === 'View Details') {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                showLoading();
                const href = this.href;
                setTimeout(() => window.location.href = href, 200);
            });
        }
    });
</script>
@endsection
