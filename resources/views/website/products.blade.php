@extends('layouts.website')

@section('content')
<link href="https://unpkg.com/aos@2.3.4/dist/aos.css" rel="stylesheet">

<style>
   :root {
  --primary: #004b8d;
  --accent: #f4b400;
  --bg-light: #f8faff;
  --white: #ffffff;
  --gray: #6c757d;
  --text-dark: #1c1c1c;
  --card-shadow: rgba(0, 0, 0, 0.08);
}

/* Background */
body {
  background: linear-gradient(180deg, #eef4f9 0%, #f8faff 100%);
  font-family: 'Poppins', 'Inter', sans-serif;
}

/* Filter Card Container */
.filter-card {
  background: #ffffff;
  border-radius: 16px;
  box-shadow: 0 4px 14px rgba(0, 0, 0, 0.05);
  overflow: hidden;
  position: sticky;
  top: 1rem;
}

/* Card Header */
.filter-card .card-header {
  background: linear-gradient(90deg, #0044ff, #007bff);
  color: #fff;
  font-weight: 700;
  font-size: 1.1rem;
  text-align: center;
  padding: 0.9rem;
  border: none;
  letter-spacing: 0.3px;
}

/* Card Body */
.category-filter {
  padding: 1.25rem 1.5rem;
  background-color: #f8fafc;
}

/* Checkbox Container */
.category-filter div {
  display: flex;
  align-items: center;
  padding: 0.45rem 0.75rem;
  border-radius: 10px;
  transition: all 0.25s ease;
  cursor: pointer;
}

/* Hover & Active Effect */
.category-filter div:hover {
  background-color: #e9f1ff;
}

.category-filter input[type="checkbox"] {
  accent-color: #0044ff;
  margin-right: 0.6rem;
  width: 18px;
  height: 18px;
}

/* Label Text */
.category-filter label {
  margin: 0;
  font-size: 0.95rem;
  color: #1e293b;
  cursor: pointer;
}

/* Buttons */
#category-filter-form .btn {
  font-weight: 600;
  border-radius: 12px;
  transition: all 0.25s ease;
}

#category-filter-form .btn-outline-primary {
  border: 2px solid #0044ff;
}

#category-filter-form .btn-outline-primary:hover {
  background: #0044ff;
  color: #fff;
}

#category-filter-form .btn-outline-danger {
  border: 2px solid #dc2626;
}

#category-filter-form .btn-outline-danger:hover {
  background: #dc2626;
  color: #fff;
}


/* --- PRODUCT SECTION --- */
.shop-products {
  padding: 70px 0;
}

.product-card {
  background: var(--white);
  border-radius: 18px;
  box-shadow: 0 6px 20px rgba(0, 0, 0, 0.07);
  transition: all 0.35s ease;
  overflow: hidden;
  height: 100%;
}

.product-card:hover {
  transform: translateY(-8px) scale(1.02);
  box-shadow: 0 10px 28px rgba(0, 0, 0, 0.1);
}

.product-card img {
  height: 250px;
  width: 100%;
  object-fit: cover;
  border-bottom: 3px solid var(--accent);
}

.product-card .card-body {
  text-align: center;
  padding: 22px 16px;
}

.product-card h5 {
  color: var(--primary);
  font-weight: 700;
  font-size: 1.05rem;
  text-transform: uppercase;
}

.product-card p {
  color: var(--gray);
  margin-bottom: 5px;
  font-size: 0.9rem;
}

.product-card .btn {
  background: var(--accent);
  color: #222;
  border-radius: 25px;
  padding: 8px 20px;
  font-weight: 600;
  border: none;
  box-shadow: 0 3px 10px rgba(244, 180, 0, 0.3);
  transition: 0.3s ease;
}

.product-card .btn:hover {
  background: #ffca28;
  transform: translateY(-2px);
}

/* --- HEADER & SORT --- */
.sort-select {
  max-width: 220px;
  font-size: 0.9rem;
  border-radius: 10px;
  border: 1.5px solid var(--primary);
}

.sort-select:focus {
  border-color: var(--accent);
  box-shadow: 0 0 6px rgba(0, 75, 141, 0.3);
}

/* --- PAGINATION --- */
.pagination-container .page-link {
  color: var(--primary);
  border-radius: 8px;
  border: 1px solid #dee2e6;
}

.pagination-container .page-item.active .page-link {
  background-color: var(--accent);
  border-color: var(--accent);
  color: #222;
  font-weight: 600;
}

.pagination-container .page-link:hover {
  background-color: var(--primary);
  color: #fff;
}

/* --- RESPONSIVE --- */
@media (max-width: 991px) {
  .filter-card {
    margin-bottom: 25px;
  }
}

</style>

<section class="shop-products container" data-aos="fade-up">
    <div class="row g-4">
        <!-- Sidebar Filter -->
        <div class="col-lg-3">
            <div class="filter-card">
                <div class="card-header">Filter by Category</div>
                <div class="card-body category-filter">
                    <form id="category-filter-form">
                        @php
                            $categories = [
                                'Tiles', 'Vinyl', 'Borders', 'Mosaics',
                                'Sanitary Wares', 'WPC Panels & Wall Cladding',
                                'Tile Adhesive, Grout & Epoxy', 'Tools, Tile Spacers & Levelers'
                            ];
                            $selectedCategories = request()->input('categories', []);
                            if (!is_array($selectedCategories)) {
                                $selectedCategories = explode(',', $selectedCategories);
                            }
                        @endphp

                        @foreach ($categories as $category)
                            <div>
                                <input type="checkbox" name="categories[]" id="cat-{{ Str::slug($category) }}" value="{{ $category }}"
                                    {{ in_array($category, $selectedCategories) ? 'checked' : '' }}>
                                <label for="cat-{{ Str::slug($category) }}">{{ $category }}</label>
                            </div>
                        @endforeach

                        <button type="submit" class="btn btn-outline-primary btn-sm w-100 mt-3">Apply Filter</button>
                        <button type="button" id="clear-filters" class="btn btn-outline-danger btn-sm w-100 mt-2">Remove Filter</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Products -->
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-primary mb-0">All Products</h2>
                <select class="form-select sort-select" id="sort">
                    <option value="latest" {{ $sort === 'latest' ? 'selected' : '' }}>Latest</option>
                    <option value="price_asc" {{ $sort === 'price_asc' ? 'selected' : '' }}>Price: Low to High</option>
                    <option value="price_desc" {{ $sort === 'price_desc' ? 'selected' : '' }}>Price: High to Low</option>
                </select>
            </div>

            @if ($products->count())
                <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">
                    @foreach ($products as $product)
                        <div class="col" data-aos="fade-up" data-aos-delay="{{ $loop->index * 100 }}">
                            <div class="product-card h-100">
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
            @else
                <div class="alert alert-info text-center mt-4">No products found.</div>
            @endif

            <!-- Pagination -->
            <div class="pagination-container text-center mt-5">
                <ul class="pagination justify-content-center">
                    @if ($products->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">« Prev</span></li>
                    @else
                        <li class="page-item"><a href="{{ $products->previousPageUrl() }}" class="page-link">« Prev</a></li>
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
</section>

<x-loading-overlay id="view-loading" />

<script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
<script>
    AOS.init({ duration: 900, once: true });

    function showLoading() {
        document.getElementById('view-loading').classList.add('active');
    }

    function showLoadingAndRedirect(id) {
        showLoading();
        setTimeout(() => window.location.href = `/product/${id}`, 800);
    }

    // Sorting change
    document.getElementById('sort').addEventListener('change', function () {
        showLoading();
        const url = new URL(window.location.href);
        url.searchParams.set('sort', this.value);
        url.searchParams.delete('page');
        window.location.href = url.toString();
    });

    // Filter form
    document.getElementById('category-filter-form').addEventListener('submit', function (e) {
        e.preventDefault();
        showLoading();

        const url = new URL(window.location.href);
        url.searchParams.delete('page');
        for (const key of [...url.searchParams.keys()]) {
            if (key.startsWith("categories")) url.searchParams.delete(key);
        }

        const selected = document.querySelectorAll('input[name="categories[]"]:checked');
        selected.forEach(el => url.searchParams.append('categories[]', el.value));

        window.location.href = url.toString();
    });

    document.getElementById('clear-filters').addEventListener('click', function () {
        showLoading();
        const url = new URL(window.location.href);
        url.searchParams.delete('categories[]');
        url.searchParams.delete('page');
        window.location.href = url.toString();
    });
</script>
@endsection
