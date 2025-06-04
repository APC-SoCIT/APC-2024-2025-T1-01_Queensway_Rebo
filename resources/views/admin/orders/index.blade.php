@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">All Orders</h2>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Search box -->
    <div class="mb-3" style="max-width: 300px; position: relative;">
        <input id="search-input" type="text" class="form-control ps-5" placeholder="Search by customer/order #...">
    </div>

    <div class="card">
        <div class="card-body p-0">
            <table id="orders-table" class="table table-bordered table-hover table-striped mb-0">
                <thead class="thead-light">
                    <tr>
                        <th data-sort="string" style="cursor:pointer">Order # <i class="fas fa-sort"></i></th>
                        <th data-sort="string" style="cursor:pointer">Customer <i class="fas fa-sort"></i></th>
                        <th data-sort="string" style="cursor:pointer">Date <i class="fas fa-sort"></i></th>
                        <th data-sort="number" style="cursor:pointer">Total <i class="fas fa-sort"></i></th>
                        <th>Status</th>
                        <th>Items</th>
                        <th>Recipient</th>
                        <th>Shipping Address</th>
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
                                @if($order->order_status === 'paid')
                                    <form action="{{ route('admin.orders.markPreparing', $order) }}" method="POST" class="d-inline-block">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Mark as Preparing</button>
                                    </form>
                                @endif
                                @if($order->order_status === 'preparing')
                                    <form action="{{ route('admin.orders.markShipped', $order) }}" method="POST" class="d-inline-block ml-2">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">Mark as Shipped</button>
                                    </form>
                                @endif
                                @if($order->order_status === 'shipped')
                                    <span class="text-muted">Order Completed</span>
                                @endif
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
            <div class="card-footer clearfix">
                <div class="float-left mt-2">
                    Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
                </div>
                <ul class="pagination pagination-sm m-0 float-right">
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
</div>

{{-- FontAwesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const table = document.getElementById('orders-table');
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const searchInput = document.getElementById('search-input');

        // Search filtering
        searchInput.addEventListener('input', () => {
            const query = searchInput.value.toLowerCase();
            rows.forEach(row => {
                const orderNumber = row.cells[0].textContent.toLowerCase();
                const customer = row.cells[1].textContent.toLowerCase();
                row.style.display = (orderNumber.includes(query) || customer.includes(query)) ? '' : 'none';
            });
        });

        // Sorting
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
