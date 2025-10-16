@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold text-gray-800 mb-0">✏️ Edit Product</h2>
        <a href="{{ route('admin.products.index') }}" class="btn btn-outline-indigo d-flex align-items-center gap-2">
            <i class="fas fa-arrow-left"></i> <span>Back to Products</span>
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.products.update', $product) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Product Name -->
                    <div class="col-md-6">
                        <label for="name" class="form-label fw-semibold text-gray-700">Product Name</label>
                        <input type="text" class="form-control shadow-sm" id="name" name="name"
                            value="{{ $product->name }}" placeholder="Enter product name" required>
                    </div>

                    <!-- Price -->
                    <div class="col-md-3">
                        <label for="price" class="form-label fw-semibold text-gray-700">Price (₱)</label>
                        <input type="number" step="0.01" class="form-control shadow-sm" id="price" name="price"
                            value="{{ $product->price }}" placeholder="0.00" required>
                    </div>

                    <!-- Quantity -->
                    <div class="col-md-3">
                        <label for="quantity" class="form-label fw-semibold text-gray-700">Quantity</label>
                        <input type="number" class="form-control shadow-sm" id="quantity" name="quantity"
                            value="{{ $product->quantity }}" placeholder="0" required>
                    </div>

                    <!-- Category -->
                    <div class="col-md-6">
                        <label for="category" class="form-label fw-semibold text-gray-700">Category</label>
                        <select class="form-select shadow-sm" id="category" name="category" required>
                            <option value="" disabled>Select Category</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category }}" {{ $product->category === $category ? 'selected' : '' }}>
                                    {{ $category }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Image Upload -->
                    <div class="col-md-6">
                        <label for="image" class="form-label fw-semibold text-gray-700">Product Image</label>
                        <input type="file" class="form-control shadow-sm" id="image" name="image">

                        @if($product->image)
                            <div class="mt-3">
                                <img src="{{ asset('storage/' . $product->image) }}" 
                                     alt="Product Image"
                                     class="rounded shadow-sm border" width="140">
                            </div>
                        @endif
                    </div>

                    <!-- Description -->
                    <div class="col-12">
                        <label for="description" class="form-label fw-semibold text-gray-700">Description</label>
                        <textarea class="form-control shadow-sm" id="description" name="description" rows="4"
                            placeholder="Enter product details...">{{ $product->description }}</textarea>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-indigo px-4 d-flex align-items-center gap-2">
                        <i class="fas fa-save"></i> <span>Update Product</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- FontAwesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>

<style>
/* ===== Modern Theme ===== */
.text-gray-700 { color: #374151 !important; }
.text-gray-800 { color: #1f2937 !important; }

.btn-indigo {
    background: #4f46e5;
    color: #fff;
    border: none;
    transition: all 0.2s ease-in-out;
}
.btn-indigo:hover {
    background: #4338ca;
    color: #fff;
}

.btn-outline-indigo {
    border: 1.5px solid #4f46e5;
    color: #4f46e5;
    background: transparent;
    transition: all 0.2s ease-in-out;
}
.btn-outline-indigo:hover {
    background: #4f46e5;
    color: #fff;
}

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    font-size: 0.95rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.form-control:focus, .form-select:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.15);
}
.card {
    background: #fff;
    border-radius: 12px;
}
</style>
@endsection
