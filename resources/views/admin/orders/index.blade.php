@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">All Orders</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Search box -->
    <div class="mb-3" style="max-width: 300px;">
        <input id="search-input" type="text" class="form-control ps-5" placeholder="Search by customer/order #...">
    </div>

    <!-- Orders Table -->
    <div class="table-responsive">
        <table id="orders-table" class="table table-bordered table-striped small">
            <thead class="table-dark">
                <tr>
                    <th data-sort="string" style="cursor:pointer">Order # <i class="fas fa-sort"></i></th>
                    <th data-sort="string" style="cursor:pointer">Customer <i class="fas fa-sort"></i></th>
                    <th data-sort="string" style="cursor:pointer">Date <i class="fas fa-sort"></i></th>
                    <th data-sort="number" style="cursor:pointer">Total <i class="fas fa-sort"></i></th>
                    <th data-sort="string" style="cursor:pointer">Status <i class="fas fa-sort"></i></th>
                    <th data-sort="string" style="cursor:pointer">Items <i class="fas fa-sort"></i></th>
                    <th data-sort="string" style="cursor:pointer">Recipient <i class="fas fa-sort"></i></th>
                    <th data-sort="string" style="cursor:pointer">Shipping Address <i class="fas fa-sort"></i></th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($orders as $order)
                    <tr>
                        <td>#{{ $order->order_number }}</td>
                        <td>{{ $order->user->name ?? 'Guest' }}</td>
                        <td>{{ $order->created_at->format('F j, Y') }}</td>
                        <td>₱{{ number_format($order->total_amount, 2) }}</td>
                        <td>
                            <span class="badge 
                                @if($order->order_status === 'paid') bg-warning
                                @elseif($order->order_status === 'preparing') bg-info
                                @elseif($order->order_status === 'cancelled') badge-danger
                                @elseif($order->order_status === 'shipped') bg-success
                                @endif">
                                {{ ucfirst($order->order_status) }}
                            </span>
                        </td>
                        <td>
                            <ul class="list-unstyled">
                                @foreach ($order->items as $item)
                                    <li>{{ $item->product->name ?? 'Deleted Product' }} (x{{ $item->quantity }})</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $order->recipient_name ?? '-' }}</td>
                        <td>{{ $order->shipping_address ?? '-' }}</td>
                        <td>
                            <div class="d-flex gap-2 flex-wrap justify-content-center">
                                @if($order->order_status === 'paid')
                                    <form action="{{ route('admin.orders.markPreparing', $order) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Mark as Preparing</button>
                                    </form>
                                @endif
                                @if($order->order_status === 'preparing')
                                    <form action="{{ route('admin.orders.markShipped', $order) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">Mark as Shipped</button>
                                    </form>
                                @endif
                                @if($order->order_status === 'shipped')
                                    <span class="text-muted small">Order Completed</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($orders->hasPages())
        <div class="mt-3 d-flex justify-content-between align-items-center">
            <div class="small text-muted">
                Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
            </div>
            <ul class="pagination pagination-sm mb-0">
                {{-- Previous --}}
                @if ($orders->onFirstPage())
                    <li class="page-item disabled"><span class="page-link">« Previous</span></li>
                @else
                    <li class="page-item"><a class="page-link" href="{{ $orders->previousPageUrl() }}">« Previous</a></li>
                @endif

                {{-- Pages --}}
                @for ($i = 1; $i <= $orders->lastPage(); $i++)
                    <li class="page-item {{ $i == $orders->currentPage() ? 'active' : '' }}">
                        <a class="page-link" href="{{ $orders->url($i) }}">{{ $i }}</a>
                    </li>
                @endfor

                {{-- Next --}}
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

{{-- Search + Sort Script --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.getElementById('orders-table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const searchInput = document.getElementById('search-input');

        // Search
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase();
            rows.forEach(row => {
                const orderNumber = row.cells[0].textContent.toLowerCase();
                const customer = row.cells[1].textContent.toLowerCase();
                row.style.display = (orderNumber.includes(query) || customer.includes(query)) ? '' : 'none';
            });
        });

        // Sort (only th with data-sort)
        let sortColumnIndex = null;
        let sortDirection = 1;

        const headers = table.querySelectorAll('thead th[data-sort]');
        headers.forEach(header => {
            const index = Array.from(header.parentNode.children).indexOf(header);

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
                    if (h !== header && icon) icon.className = 'fas fa-sort';
                });

                const icon = header.querySelector('i');
                if (icon) icon.className = sortDirection === 1 ? 'fas fa-sort-up' : 'fas fa-sort-down';

                const sortedRows = rows
                    .filter(row => row.style.display !== 'none')
                    .sort((a, b) => {
                        let aText = a.cells[index].textContent.trim();
                        let bText = b.cells[index].textContent.trim();

                        if (type === 'number') {
                            aText = parseFloat(aText.replace(/[^0-9.-]+/g, "")) || 0;
                            bText = parseFloat(bText.replace(/[^0-9.-]+/g, "")) || 0;
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
