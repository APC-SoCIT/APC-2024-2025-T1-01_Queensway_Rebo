@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2 class="mb-4">Products</h2>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <!-- Add New Product Button -->
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">
            <i class="bi bi-plus-circle"></i> Add New Product
        </a>

        <!-- Products Table -->
        <div class="table-responsive">
            <table class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>₱{{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning btn-sm">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST"
                                    style="display:inline;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div>
                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
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
    </div>
@endsection