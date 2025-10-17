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
  --white: #ffffff;
}

body {
  background-color: var(--bg);
  color: var(--text);
  font-family: 'Poppins', sans-serif;
}

/* -------- FILTER SIDEBAR -------- */
.filter-card {
  background: var(--white);
  border-radius: 18px;
  box-shadow: 0 4px 15px var(--shadow);
  position: sticky;
  top: 1rem;
  overflow: hidden;
}

.filter-card .card-header {
  background: var(--accent);
  color: var(--primary);
  font-weight: 700;
  font-size: 1.05rem;
  text-align: center;
  padding: 0.9rem;
  border: none;
  letter-spacing: 0.3px;
}

.category-filter {
  padding: 1.3rem 1.5rem;
  background-color: #fffdf5;
}

.category-filter div {
  display: flex;
  align-items: center;
  padding: 0.5rem 0.7rem;
  border-radius: 10px;
  transition: 0.25s;
  cursor: pointer;
}

.category-filter div:hover {
  background-color: #fff4cc;
}

.category-filter input[type="checkbox"] {
  accent-color: var(--accent);
  margin-right: 0.6rem;
  width: 18px;
  height: 18px;
}

.category-filter label {
  margin: 0;
  font-size: 0.95rem;
  color: var(--text);
  cursor: pointer;
}

/* Buttons inside filter */
#category-filter-form .btn {
  font-weight: 600;
  border-radius: 12px;
  transition: 0.25s;
}

#category-filter-form .btn-outline-primary {
  border: 2px solid var(--accent);
  color: var(--primary);
}

#category-filter-form .btn-outline-primary:hover {
  background: var(--accent);
  color: var(--primary);
}

#category-filter-form .btn-outline-danger {
  border: 2px solid #dc2626;
  color: #dc2626;
}

#category-filter-form .btn-outline-danger:hover {
  background: #dc2626;
  color: #fff;
}

/* -------- PRODUCT GRID -------- */
.shop-products {
  padding: 70px 0;
}

.product-card {
  background: var(--white);
  border-radius: 16px;
  box-shadow: 0 4px 15px var(--shadow);
  transition: all 0.3s ease;
  overflow: hidden;
  height: 100%;
}

.product-card:hover {
  transform: translateY(-6px);
  box-shadow: 0 8px 25px var(--shadow);
}

.product-card img {
  height: 250px;
  width: 100%;
  object-fit: cover;
  background: #f2f2f2;
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

/* Buttons inside product cards */
.product-card .btn {
  background: var(--accent);
  color: var(--primary);
  border-radius: 25px;
  padding: 8px 20px;
  font-weight: 600;
  border: none;
  transition: 0.3s ease;
  box-shadow: 0 3px 10px var(--shadow);
}

.product-card .btn:hover {
  background: #e2a800;
  color: #000;
  transform: translateY(-2px);
}

/* -------- SORT DROPDOWN -------- */
.sort-select {
  max-width: 220px;
  font-size: 0.9rem;
  border-radius: 10px;
  border: 1.5px solid var(--border);
  box-shadow: 0 2px 6px var(--shadow);
}

.sort-select:focus {
  border-color: var(--accent);
  box-shadow: 0 0 6px rgba(244, 180, 0, 0.4);
}

/* -------- PAGINATION -------- */
.pagination-container .page-link {
  color: var(--primary);
  border-radius: 8px;
  border: 1px solid #dee2e6;
}

.pagination-container .page-item.active .page-link {
  background-color: var(--accent);
  border-color: var(--accent);
  color: var(--primary);
  font-weight: 600;
}

.pagination-container .page-link:hover {
  background-color: var(--primary);
  color: #fff;
}

@media (max-width: 991px) {
  .filter-card { margin-bottom: 25px; }
}
</style>

<section class="shop-products container" data-aos="fade-up">
  <div class="row g-4">
    <!-- FILTER SIDEBAR -->
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

    <!-- PRODUCT LIST -->
    <div class="col-lg-9">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold mb-0" style="color: var(--primary);">All Products</h2>
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

document.getElementById('sort').addEventListener('change', function () {
  showLoading();
  const url = new URL(window.location.href);
  url.searchParams.set('sort', this.value);
  url.searchParams.delete('page');
  window.location.href = url.toString();
});

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
