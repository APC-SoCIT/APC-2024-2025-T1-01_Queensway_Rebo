@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">All Products</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Add New Product Button -->
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus-circle"></i> Add New Product
    </a>

    <!-- Search box -->
    <div class="mb-3" style="max-width: 300px;">
        <input id="search-input" type="text" class="form-control ps-5" placeholder="Search products...">
    </div>

    <!-- Products Table -->
    <div class="table-responsive">
        <table id="products-table" class="table table-bordered table-striped small">
            <thead class="table-dark">
                <tr>
                    <th data-sort="string" style="cursor:pointer">SKU <i class="fas fa-sort"></i></th>
                    <th data-sort="string" style="cursor:pointer">Name <i class="fas fa-sort"></i></th>
                    <th data-sort="number" style="cursor:pointer">Price <i class="fas fa-sort"></i></th>
                    <th data-sort="number" style="cursor:pointer">Quantity <i class="fas fa-sort"></i></th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->sku }}</td>
                        <td>{{ $product->name }}</td>
                        <td>₱{{ number_format($product->price, 2) }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center flex-wrap">
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No products found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($products->hasPages())
        <div class="mt-3 d-flex justify-content-between align-items-center">
            <div class="small text-muted">
                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
            </div>
            <ul class="pagination pagination-sm mb-0">
                @if ($products->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">« Previous</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $products->previousPageUrl() }}">« Previous</a></li>
                @endif

                @for ($i = 1; $i <= $products->lastPage(); $i++)
                    <li class="page-item {{ $i == $products->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                @if ($products->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $products->nextPageUrl() }}">Next »</a></li>
                @else
                    <li class="page-item disabled"><span class="page-link">Next »</span></li>
                @endif
            </ul>
        </div>
    @endif
</div>

{{-- FontAwesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>

{{-- Search + Sort Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.getElementById('products-table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const searchInput = document.getElementById('search-input');

        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase();
            rows.forEach(row => {
                const sku = row.cells[0].textContent.toLowerCase();
                const name = row.cells[1].textContent.toLowerCase();
                row.style.display = sku.includes(query) || name.includes(query) ? '' : 'none';
            });
        });

        let sortColumnIndex = null;
        let sortDirection = 1;

        const headers = table.querySelectorAll('thead th[data-sort]');
        headers.forEach((header, index) => {
            header.addEventListener('click', () => {
                const type = header.getAttribute('data-sort');
                if (sortColumnIndex === index) {
                    sortDirection = -sortDirection;
                } else {
                    sortColumnIndex = index;
                    sortDirection = 1;
                }

                headers.forEach(h => {
                    const icon = h.querySelector('i');
                    if (h !== header) icon.className = 'fas fa-sort';
                });

                const icon = header.querySelector('i');
                icon.className = sortDirection === 1 ? 'fas fa-sort-up' : 'fas fa-sort-down';

                const sortedRows = rows
                    .filter(row => row.style.display !== 'none')
                    .sort((a, b) => {
                        let aText = a.cells[index].textContent.trim();
                        let bText = b.cells[index].textContent.trim();

                        if (type === 'number') {
                            aText = parseFloat(aText.replace(/[^0-9.-]+/g,"")) || 0;
                            bText = parseFloat(bText.replace(/[^0-9.-]+/g,"")) || 0;
                        } else {
                            aText = aText.toLowerCase();
                            bText = bText.toLowerCase();
                        }

                        return (aText < bText ? -1 : aText > bText ? 1 : 0) * sortDirection;
                    });

                sortedRows.forEach(row => tbody.appendChild(row));
            });
        });
    });
</script>
@endsection
