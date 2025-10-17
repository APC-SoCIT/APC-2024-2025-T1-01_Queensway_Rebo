<div class="container">
    <h3 class="text-center mb-4">Results for "{{ $query }}"</h3>

    <div class="row g-4">
        @forelse($products as $product)
            <div class="col-md-4 d-flex">
                <div class="product-card h-100 d-flex flex-column w-100">
                    <!-- Fixed height image container -->
                    <div style="height: 230px; overflow: hidden;">
                        <img src="/storage/{{ $product->image }}" alt="{{ $product->name }}" class="w-100 h-100"
                            style="object-fit: contain;">
                    </div>

                    <!-- Card Body -->
                    <div class="card-body d-flex flex-column justify-content-between flex-grow-1">
                        <div>
                            <h5>{{ $product->name }}</h5>
                            <p class="mb-1">₱{{ number_format($product->price, 2) }}</p>
                            @if ($product->quantity > 0)
                                <p class="text-success mb-2">Stock: {{ $product->quantity }}</p>
                            @else
                                <p class="text-danger fw-bold mb-2">Out of Stock</p>
                            @endif
                        </div>
                        <button class="btn mt-auto" onclick="showLoadingAndRedirect({{ $product->id }})">
                            View Details
                        </button>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center text-muted py-5">
                <p>No matching products found for "{{ $query }}".</p>
            </div>
        @endforelse
    </div>
</div>