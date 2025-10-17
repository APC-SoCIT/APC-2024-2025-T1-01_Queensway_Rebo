@extends('layouts.website')

@section('content')
    <style>
        .account-wrapper {
            display: flex;
            gap: 2rem;
            margin-top: 2rem;
        }

        .account-sidebar {
            width: 250px;
            border-right: 1px solid #eee;
            padding-right: 1rem;
        }

        .account-sidebar ul {
            list-style: none;
            padding: 0;
        }

        .account-sidebar li {
            padding: 0.6rem 0;
            font-size: 0.95rem;
            cursor: pointer;
            color: #333;
        }

        .account-sidebar li.active {
            font-weight: bold;
            border-left: 3px solid orange;
            padding-left: 10px;
        }

        .orders-table-container {
            flex: 1;
        }

        .orders-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 0.95rem;
        }

        .orders-table th,
        .orders-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .orders-table th {
            background-color: #f9f9f9;
        }

        .orders-table a {
            text-decoration: none;
            color: #007bff;
            margin-right: 0.5rem;
        }

        .orders-table a:hover {
            text-decoration: underline;
        }

        .pagination-controls {
            margin-top: 1rem;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            justify-content: flex-end;
        }

        .pagination-controls select {
            margin-left: 0.5rem;
        }

        .account-sidebar a {
            color: inherit;
            text-decoration: none;
        }

        .account-sidebar a:hover {
            text-decoration: underline;
            color: orange; /* Optional hover effect */
        }
    </style>

    <div class="container mt-4">
        <h2 class="mb-4">My Orders</h2>

        <div class="account-wrapper">
            <!-- Sidebar -->
            <div class="account-sidebar">
                <ul>
                    <li class="active">My Orders</li>
                    <li><a href="{{ route('user.account') }}">Account Information</a></li>
                </ul>
            </div>

            <!-- Orders Table -->
            <div class="orders-table-container">
                <table class="orders-table">
                    <thead>
                        <tr>
                            <th>Order #</th>
                            <th>Date</th>
                            <th>Ship To</th>
                            <th>Order Total</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($orders as $order)
                            <tr>
                                <td>{{ str_pad($order->order_number, 8, '0', STR_PAD_LEFT) }}</td>
                                <td>{{ $order->created_at->format('d.m.y') }}</td>
                                <td>{{ $order->recipient_name }}</td>
                                <td>₱{{ number_format($order->total_amount, 2) }}</td>
                                <td>{{ ucfirst($order->order_status) }}</td>
                                <td>
                                    <a href="{{ route('orders.show', ['order' => $order->order_number]) }}">Track your order</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted">No orders found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>

                {{-- Pagination --}}
                @if($orders->hasPages())
                    <div class="card-footer clearfix mt-3">
                        <div class="float-left mt-2">
                            Showing {{ $orders->firstItem() }} to {{ $orders->lastItem() }} of {{ $orders->total() }} orders
                        </div>
                        <ul class="pagination pagination-sm m-0 float-right">
                            {{-- Previous Page Link --}}
                            @if ($orders->onFirstPage())
                                <li class="page-item disabled"><span class="page-link">« Previous</span></li>
                            @else
                                <li class="page-item"><a class="page-link" href="{{ $orders->previousPageUrl() }}" rel="prev">« Previous</a></li>
                            @endif

                            {{-- Pagination Elements --}}
                            @for ($i = 1; $i <= $orders->lastPage(); $i++)
                                <li class="page-item {{ $i == $orders->currentPage() ? 'active' : '' }}">
                                    <a class="page-link" href="{{ $orders->url($i) }}">{{ $i }}</a>
                                </li>
                            @endfor

                            {{-- Next Page Link --}}
                            @if ($orders->hasMorePages())
                                <li class="page-item"><a class="page-link" href="{{ $orders->nextPageUrl() }}" rel="next">Next »</a></li>
                            @else
                                <li class="page-item disabled"><span class="page-link">Next »</span></li>
                            @endif
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
