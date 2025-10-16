@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold text-gray-800 mb-0">🧾 All Orders</h2>
    </div>

    @if (session('success'))
        <div class="alert alert-success shadow-sm border-0">{{ session('success') }}</div>
    @endif

    <!-- Search box -->
    <div class="mb-3 position-relative" style="max-width: 300px;">
        <input id="search-input" type="text" class="form-control ps-5 shadow-sm"
            placeholder="Search order # / customer...">
    </div>

    <!-- Orders Table -->
    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table id="orders-table" class="table table-hover align-middle mb-0">
                    <thead class="bg-indigo text-white small text-uppercase">
                        <tr>
                            <th data-sort="string" class="py-3 px-3" style="cursor:pointer">Order # <i class="fas fa-sort"></i></th>
                            <th data-sort="string" class="py-3 px-3" style="cursor:pointer">Customer <i class="fas fa-sort"></i></th>
                            <th data-sort="string" class="py-3 px-3" style="cursor:pointer">Date <i class="fas fa-sort"></i></th>
                            <th data-sort="number" class="py-3 px-3" style="cursor:pointer">Total <i class="fas fa-sort"></i></th>
                            <th data-sort="string" class="py-3 px-3" style="cursor:pointer">Status <i class="fas fa-sort"></i></th>
                            <th class="py-3 px-3">Items</th>
                            <th class="py-3 px-3">Recipient</th>
                            <th class="py-3 px-3">Address</th>
                            <th class="py-3 text-center">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td class="fw-semibold text-dark">#{{ $order->order_number }}</td>
                                <td>{{ $order->user->name ?? 'Guest' }}</td>
                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                <td class="text-success fw-medium">₱{{ number_format($order->total_amount, 2) }}</td>
                                <td>
                                    <span class="badge rounded-pill 
                                        @if($order->order_status === 'paid') bg-warning text-dark
                                        @elseif($order->order_status === 'preparing') bg-info text-dark
                                        @elseif($order->order_status === 'cancelled') bg-danger
                                        @elseif($order->order_status === 'shipped') bg-success
                                        @endif">
                                        {{ ucfirst($order->order_status) }}
                                    </span>
                                </td>
                                <td>
                                    <ul class="list-unstyled mb-0 small">
                                        @foreach ($order->items as $item)
                                            <li>{{ $item->product->name ?? 'Deleted Product' }} (x{{ $item->quantity }})</li>
                                        @endforeach
                                    </ul>
                                </td>
                                <td>{{ $order->recipient_name ?? '-' }}</td>
                                <td>{{ $order->shipping_address ?? '-' }}</td>
                                <td class="text-center">
                                    @if($order->order_status === 'paid')
                                        <form action="{{ route('admin.orders.markPreparing', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-success rounded-pill px-3">Mark as Preparing</button>
                                        </form>
                                    @elseif($order->order_status === 'preparing')
                                        <form action="{{ route('admin.orders.markShipped', $order) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary rounded-pill px-3">Mark as Shipped</button>
                                        </form>
                                    @elseif($order->order_status === 'shipped')
                                        <span class="text-muted small">Order Completed</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-gray-500 py-4">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
        <div class="mt-4 d-flex justify-content-between align-items-center">
            <div class="small text-gray-600">
                Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
            </div>
            <ul class="pagination pagination-sm mb-0">
                @if ($orders->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">« Previous</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $orders->previousPageUrl() }}">« Previous</a></li>
                @endif

                @for ($i = 1; $i <= $orders->lastPage(); $i++)
                    <li class="page-item {{ $i == $orders->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $orders->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                @if ($orders->hasMorePages())
                    <li class="page-item"><a class="page-link" href="{{ $orders->nextPageUrl() }}">Next »</a></li>
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
/* ===== Matching Modern Indigo Theme ===== */
.text-gray-500 { color: #6b7280 !important; }
.text-gray-600 { color: #4b5563 !important; }
.text-gray-800 { color: #1f2937 !important; }
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
    const table = document.getElementById('orders-table');
    const tbody = table.querySelector('tbody');
    const rows = Array.from(tbody.querySelectorAll('tr'));
    const searchInput = document.getElementById('search-input');

    searchInput.addEventListener('input', () => {
        const query = searchInput.value.toLowerCase();
        rows.forEach(row => {
            const orderNumber = row.cells[0].textContent.toLowerCase();
            const customer = row.cells[1].textContent.toLowerCase();
            row.style.display = orderNumber.includes(query) || customer.includes(query) ? '' : 'none';
        });
    });

    let sortColumnIndex = null;
    let sortDirection = 1;
    const headers = table.querySelectorAll('thead th[data-sort]');
    headers.forEach((header, index) => {
        header.addEventListener('click', () => {
            const type = header.getAttribute('data-sort');
            if (sortColumnIndex === index) sortDirection = -sortDirection;
            else { sortColumnIndex = index; sortDirection = 1; }

            headers.forEach(h => h.querySelector('i').className = 'fas fa-sort');
            header.querySelector('i').className = sortDirection === 1 ? 'fas fa-sort-up' : 'fas fa-sort-down';

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
