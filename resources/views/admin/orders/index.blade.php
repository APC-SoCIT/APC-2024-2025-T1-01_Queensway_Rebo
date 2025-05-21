@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2 class="mb-4">All Orders</h2>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered table-hover">
            <thead class="thead-light">
                <tr>
                    <th>Order #</th>
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Items</th>
                    <th>Recipient Name</th>
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
                                    <li>
                                        {{ $item->product->name ?? 'Deleted Product' }} (x{{ $item->quantity }}) -
                                        ₱{{ number_format($item->unit_price, 2) }}
                                    </li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $order->recipient_name ?? '-' }}</td>
                        <td>{{ $order->shipping_address ?? '-' }}</td>
                        <td>
                            @if($order->order_status === 'paid')
                                <!-- Mark as Preparing Form -->
                                <form action="{{ route('admin.orders.markPreparing', $order) }}" method="POST"
                                    class="d-inline-block">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-success" @if($order->order_status !== 'paid')
                                    disabled @endif>
                                        Mark as Preparing
                                    </button>
                                </form>
                            @endif

                            @if($order->order_status === 'preparing')
                                <!-- Mark as Shipped Form -->
                                <form action="{{ route('admin.orders.markShipped', $order) }}" method="POST"
                                    class="d-inline-block ml-2">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-primary" @if($order->order_status !== 'preparing')
                                    disabled @endif>
                                        Mark as Shipped
                                    </button>
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
@endsection