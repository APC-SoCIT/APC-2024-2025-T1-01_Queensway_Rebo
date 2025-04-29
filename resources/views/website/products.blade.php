@extends('layouts.website')

@section('content')
    <style>
        .product-card img {
            width: 100%;
            height: 200px;
            /* Adjust height to maintain uniformity */
            object-fit: cover;
        }

        .product-card {
            transition: transform 0.2s;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            /* Slight rounding for the cards */
        }

        .product-card:hover {
            transform: scale(1.05);
        }

        .sort-select {
            max-width: 200px;
        }

        /* Adjust the grid layout */
        .row-cols-md-3 .col {
            flex: 1 0 33%;
            /* Make each card occupy a third of the row */
            max-width: 33%;
            margin-bottom: 20px;
        }

        /* Adjust text and button styling inside card */
        .card-body {
            padding: 15px;
        }

        .card-title {
            font-size: 1.1rem;
            /* Slightly larger title */
            margin-bottom: 10px;
            font-weight: bold;
        }

        .card-text {
            font-size: 0.9rem;
            /* Slightly bigger text for better readability */
            color: #555;
        }

        .btn-primary {
            font-size: 0.9rem;
            padding: 8px 16px;
        }

        @media (max-width: 768px) {
            .row-cols-md-3 .col {
                flex: 1 0 50%;
                /* Cards take up 50% of the width on medium screens */
                max-width: 50%;
            }
        }

        @media (max-width: 576px) {
            .row-cols-md-3 .col {
                flex: 1 0 100%;
                /* Cards take up full width on small screens */
                max-width: 100%;
            }
        }
    </style>

    <div class="container my-5">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>All Products</h2>
            <select class="form-select sort-select" id="sort">
                <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Latest</option>
                <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
            </select>
        </div>

        <div id="products" class="row row-cols-1 row-cols-md-3 g-4">
            <!-- JS will render products here -->
            @foreach ($products as $product)
                <div class="col">
                    <div class="card product-card h-100">
                        <img src="/storage/{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}">
                        <div class="card-body text-center">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="card-text">₱{{ number_format($product->price, 2) }}</p>
                            <p class="card-text">Stock: {{ $product->quantity }}</p>
                            <a href="{{ route('product.show', $product->id) }}" class="btn btn-primary">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Custom Pagination -->
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

    <script>
        document.getElementById('sort').addEventListener('change', function () {
            window.location.href = `/shop?sort=${this.value}`;
        });
    </script>
@endsection