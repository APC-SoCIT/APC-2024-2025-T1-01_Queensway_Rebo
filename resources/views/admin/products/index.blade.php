@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold text-gray-800 mb-0">📦 All Products</h2>
        <a href="{{ route('admin.products.create') }}" class="btn btn-indigo d-flex align-items-center gap-2">
            <i class="fas fa-plus-circle"></i>
            <span>Add New Product</span>
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success shadow-sm border-0">{{ session('success') }}</div>
    @endif

    <!-- Search box -->
    <div class="mb-3 position-relative" style="max-width: 300px;">
        <input id="search-input" type="text" class="form-control ps-5 shadow-sm"
            placeholder="Search products...">
    </div>

    <!-- Products Table Card -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="products-table" class="table table-hover align-middle mb-0">
                    <thead class="bg-indigo text-white small text-uppercase">
                        <tr>
                            <th data-sort="string" class="py-3 px-3" style="cursor:pointer">SKU <i class="fas fa-sort"></i></th>
                            <th data-sort="string" class="py-3 px-3" style="cursor:pointer">Name <i class="fas fa-sort"></i></th>
                            <th data-sort="number" class="py-3 px-3" style="cursor:pointer">Price <i class="fas fa-sort"></i></th>
                            <th data-sort="number" class="py-3 px-3" style="cursor:pointer">Quantity <i class="fas fa-sort"></i></th>
                            <th class="py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td class="px-3">{{ $product->sku }}</td>
                                <td class="px-3">{{ $product->name }}</td>
                                <td class="px-3 text-success fw-medium">₱{{ number_format($product->price, 2) }}</td>
                                <td class="px-3">{{ $product->quantity }}</td>
                                <td class="text-center">
                                    <div class="d-flex gap-2 justify-content-center flex-wrap">
                                        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning rounded-pill">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline"
                                            onsubmit="return confirm('Are you sure to delete this product?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger rounded-pill">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-gray-500 py-4">No products found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="mt-4 d-flex justify-content-between align-items-center">
            <div class="small text-gray-600">
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

<style>
/* ===== Modern Styling Matching Sidebar ===== */
.text-gray-500 { color: #6b7280 !important; }
.text-gray-600 { color: #4b5563 !important; }
.text-gray-800 { color: #1f2937 !important; }
.text-indigo-600 { color: #4f46e5 !important; }
.bg-indigo { background-color: #4f46e5 !important; }

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

.table thead th {
    border-bottom: none;
}
.table-hover tbody tr:hover {
    background-color: #f9fafb !important;
}

.page-link {
    color: #4f46e5;
    border-radius: 6px;
}
.page-item.active .page-link {
    background-color: #4f46e5;
    border-color: #4f46e5;
}
</style>

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
