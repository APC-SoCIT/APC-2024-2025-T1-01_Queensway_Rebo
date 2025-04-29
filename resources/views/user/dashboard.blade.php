
<style>
    .order-card {
    background-color: #fff;
    border: 1px solid #ddd;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.order-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 2px 15px rgba(0, 0, 0, 0.1);
}

.order-item-image {
    border-radius: 8px;
    border: 1px solid #ddd;
}

.order-item {
    align-items: center;
    display: flex;
}

.order-total {
    font-size: 1.1rem;
    font-weight: bold;
    color: #333;
}

.badge {
    font-size: 0.9rem;
    padding: 8px 12px;
    border-radius: 20px;
    text-transform: capitalize;
}

.badge-warning {
    background-color: #f0ad4e;
    color: white;
}

.badge-success {
    background-color: #5bc0de;
    color: white;
}

.badge-danger {
    background-color: #d9534f;
    color: white;
}

</style>
   @extends('layouts.website')

@section('content')
    <div class="container my-5">
        <h2 class="text-center mb-4">Your Orders</h2>

        @forelse ($orders as $order)
            <div class="order-card mb-4 shadow-sm rounded">
                <!-- Order Header (Clickable to Expand/Collapse) -->
                <div class="order-header bg-primary text-white p-4 rounded-top">
                    <h4 class="mb-1">
                        <button class="btn btn-link text-white" data-bs-toggle="collapse" data-bs-target="#orderDetails{{ $order->id }}" aria-expanded="false" aria-controls="orderDetails{{ $order->id }}">
                            Order #{{ $order->id }}
                        </button>
                    </h4>
                    <p class="mb-0">Placed on {{ $order->created_at->format('F j, Y') }}</p>
                </div>

                <!-- Collapsible Order Details -->
                <div id="orderDetails{{ $order->id }}" class="order-details collapse p-4 bg-light rounded-bottom">
                    <h5 class="mb-3">Order Summary</h5>

                    @foreach ($order->items as $item)
                        <div class="d-flex align-items-center order-item mb-3">
                            <!-- Item Image -->
                            <img src="/storage/{{ $item->product->image }}" alt="{{ $item->product->name }}" class="order-item-image" style="width: 80px; height: 80px; object-fit: cover;">
                            <div class="item-info ml-3 flex-grow-1">
                                <strong>{{ $item->product->name }}</strong><br>
                                Quantity: {{ $item->quantity }}<br>
                                ₱{{ number_format($item->product->price, 2) }}
                            </div>
                            <div class="ml-auto">
                                ₱{{ number_format($item->quantity * $item->product->price, 2) }}
                            </div>
                        </div>
                    @endforeach

                    <!-- Total Amount -->
                    <div class="d-flex justify-content-between mt-4">
                        <span class="order-total font-weight-bold">Total</span>
                        <span class="order-total font-weight-bold">₱{{ number_format($order->total_amount, 2) }}</span>
                    </div>

                    <!-- Order Status -->
                    <div class="mt-3">
                        <span class="badge 
                            @if($order->order_status === 'paid') badge-warning
                            @elseif($order->order_status === 'completed') badge-success
                            @elseif($order->order_status === 'cancelled') badge-danger
                            @endif">
                            {{ ucfirst($order->order_status) }}
                        </span>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">No orders found.</p>
        @endforelse
    </div>
@endsection
