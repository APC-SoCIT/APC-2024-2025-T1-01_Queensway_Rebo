<div class="container">
    <h3>Results for "{{ $query }}"</h3>
    <div class="row mt-3">
        @forelse($products as $product)
            <div class="col-md-3 mb-4">
                <div class="card h-100">
                <!-- Image wrapper -->
                    <div style="height: 180px; overflow: hidden; border-top-left-radius: 10px; border-top-right-radius: 10px;">
                        <img src="/storage/{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}" style="width:100%; height:100%; object-fit: cover;">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        <p class="card-text">{{ $product->description }}</p>
                        <p class="fw-bold">₱@if($products->isEmpty())
                            <p class="text-center">No matching products found for "{{ $query }}"</p>
                        @else
                            <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">

                            </div>
                        @endif
                        {{ number_format($product->price, 2) }}</p>
                        <a href="#" class="btn btn-sm btn-primary align-items-center text-center">View</a>
                    </div>
                </div>
            </div>
        @empty
            <p>No matching products found.</p>
        @endforelse
    </div>
</div>