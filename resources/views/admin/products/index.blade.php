@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Products</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Add New Product Button -->
    <a href="{{ route('admin.products.create') }}" class="btn btn-primary mb-3">
        <i class="fas fa-plus-circle"></i> Add New Product
    </a>

    <!-- Search box -->
    <div class="mb-3" style="max-width: 300px; position: relative;">
        <input id="search-input" type="text" class="form-control ps-5" placeholder="Search products...">
        <span style="position: absolute; left: 15px; top: 50%; transform: translateY(-50%); color: #aaa;">
        </span>
    </div>

    <!-- Products Table -->
    <div class="card">
        <div class="card-body p-0">
            <table id="products-table" class="table table-hover table-striped table-bordered mb-0">
                <thead class="thead-light">
                    <tr>
                        <th data-sort="string" style="cursor:pointer">Name <i class="fas fa-sort"></i></th>
                        <th data-sort="number" style="cursor:pointer">Price <i class="fas fa-sort"></i></th>
                        <th data-sort="number" style="cursor:pointer">Quantity <i class="fas fa-sort"></i></th>
                        <th style="width: 140px;">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>₱{{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->quantity }}</td>
                            <td>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-warning" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>

                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure to delete this product?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-danger" title="Delete">
                                        <i class="fas fa-trash-alt"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($products->hasPages())
            <div class="card-footer clearfix">
                <div class="float-left mt-2">
                    Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} products
                </div>
                <ul class="pagination pagination-sm m-0 float-right">
                    {{-- Previous Page Link --}}
                    @if ($products->onFirstPage())
                        <li class="page-item disabled"><span class="page-link">« Previous</span></li>
                    @else
                        <li class="page-item"><a class="page-link" href="{{ $products->previousPageUrl() }}" rel="prev">« Previous</a></li>
                    @endif

                    {{-- Pagination Elements --}}
                    @for ($i = 1; $i <= $products->lastPage(); $i++)
                        <li class="page-item {{ $i == $products->currentPage() ? 'active' : '' }}">
                            <a class="page-link" href="{{ $products->url($i) }}">{{ $i }}</a>
                        </li>
                    @endfor

                    {{-- Next Page Link --}}
                    @if ($products->hasMorePages())
                        <li class="page-item"><a class="page-link" href="{{ $products->nextPageUrl() }}" rel="next">Next »</a></li>
                    @else
                        <li class="page-item disabled"><span class="page-link">Next »</span></li>
                    @endif
                </ul>
            </div>
        @endif
    </div>
</div>

{{-- Include FontAwesome if not already included --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.getElementById('products-table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const searchInput = document.getElementById('search-input');

        // Search filtering
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase();
            rows.forEach(row => {
                const nameCell = row.cells[0].textContent.toLowerCase();
                if (nameCell.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Sorting
        let sortColumnIndex = null;
        let sortDirection = 1; // 1 = ascending, -1 = descending

        const headers = table.querySelectorAll('thead th[data-sort]');
        headers.forEach((header, index) => {
            header.addEventListener('click', () => {
                const type = header.getAttribute('data-sort');
                if (sortColumnIndex === index) {
                    sortDirection = -sortDirection; // toggle direction
                } else {
                    sortColumnIndex = index;
                    sortDirection = 1;
                }

                // Remove sort icons from all headers except current
                headers.forEach(h => {
                    const icon = h.querySelector('i');
                    if(h !== header) icon.className = 'fas fa-sort';
                });

                // Update icon on current header
                const icon = header.querySelector('i');
                if(sortDirection === 1){
                    icon.className = 'fas fa-sort-up';
                } else {
                    icon.className = 'fas fa-sort-down';
                }

                // Sort rows
                const sortedRows = rows
                    .filter(row => row.style.display !== 'none') // sort only visible rows
                    .sort((a, b) => {
                        let aText = a.cells[index].textContent.trim();
                        let bText = b.cells[index].textContent.trim();

                        if(type === 'number'){
                            aText = parseFloat(aText.replace(/[^0-9.-]+/g,"")) || 0;
                            bText = parseFloat(bText.replace(/[^0-9.-]+/g,"")) || 0;
                        } else {
                            aText = aText.toLowerCase();
                            bText = bText.toLowerCase();
                        }

                        if (aText < bText) return -1 * sortDirection;
                        if (aText > bText) return 1 * sortDirection;
                        return 0;
                    });

                // Append sorted rows
                sortedRows.forEach(row => tbody.appendChild(row));
            });
        });
    });
</script>
@endsection
