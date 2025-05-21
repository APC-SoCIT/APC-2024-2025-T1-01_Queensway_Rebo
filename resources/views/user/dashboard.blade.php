@extends('layouts.website')

@section('content')
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

        /* Shipping Progress Path */
        .shipping-progress {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .shipping-step {
            position: relative;
            text-align: center;
            flex-grow: 1;
        }

        .shipping-step .step-circle {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            border: 2px solid #ddd;
            background-color: #fff;
            margin: 0 auto;
            line-height: 18px;
            font-weight: bold;
            font-size: 12px;
            color: #ddd;
        }

        .shipping-step.completed .step-circle {
            background-color: #28a745;
            border-color: #28a745;
            color: white;
        }

        .shipping-step.current .step-circle {
            background-color: #007bff;
            border-color: #007bff;
            color: white;
        }

        .shipping-step .step-label {
            margin-top: 5px;
            font-size: 0.8rem;
            color: #333;
        }

        /* Adjust the order header to make room for the caret button */
        .order-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem;
            cursor: pointer;
        }

        .order-header h4 {
            margin: 0;
        }

        .order-header .collapse-btn {
            font-size: 1.5rem;
            color: white;
            border: none;
            background: transparent;
            cursor: pointer;
        }

        .order-date {
            font-size: 0.9rem;
            color: #ddd;
            margin-top: 5px;
        }
    </style>

    <div class="container my-5">
        <h2 class="text-center mb-4">Your Orders</h2>

        @forelse ($orders as $order)
            <div class="order-card mb-4 shadow-sm rounded">
                <!-- Order Header (Clickable to Expand/Collapse) -->
                <div class="order-header bg-primary text-white p-4 rounded-top" data-bs-toggle="collapse"
                    data-bs-target="#orderDetails{{ $order->id }}" aria-expanded="false"
                    aria-controls="orderDetails{{ $order->id }}">
                    <div class="d-flex w-100 justify-content-between align-items-center">
                        <div>
                            <h4 class="mb-1">
                                Order #{{ $order->order_number }}
                            </h4>
                            <div class="order-date">
                                Placed on {{ $order->created_at->format('F j, Y') }}
                            </div>
                        </div>
                        <button class="collapse-btn" id="collapse-btn{{ $order->id }}">
                            <i class="bi bi-caret-down-fill"></i>
                        </button>
                    </div>
                </div>

                <!-- Shipping Progress Path -->
                <div class="shipping-progress">
                    <div
                        class="shipping-step @if($order->order_status === 'paid' || $order->order_status === 'preparing') current @elseif($order->order_status === 'completed') completed @endif">
                        <div class="step-circle">1</div>
                        <div class="step-label">Paid</div>
                    </div>
                    <div
                        class="shipping-step @if($order->order_status === 'preparing') current @elseif($order->order_status === 'completed') completed @endif">
                        <div class="step-circle">2</div>
                        <div class="step-label">Preparing</div>
                    </div>
                    <div
                        class="shipping-step @if($order->order_status === 'shipped') current @elseif($order->order_status === 'completed') completed @endif">
                        <div class="step-circle">3</div>
                        <div class="step-label">Shipped</div>
                    </div>
                    <div class="shipping-step @if($order->order_status === 'completed') current @endif">
                        <div class="step-circle">4</div>
                        <div class="step-label">Completed</div>
                    </div>
                </div>

                <!-- Collapsible Order Details -->
                <div id="orderDetails{{ $order->id }}" class="order-details collapse p-4 bg-light rounded-bottom">
                    <h5 class="mb-3">Order Summary</h5>

                    @foreach ($order->items as $item)
                        <div class="d-flex align-items-center order-item mb-3">
                            <!-- Item Image -->
                            <img src="/storage/{{ $item->product->image }}" alt="{{ $item->product->name }}"
                                class="order-item-image" style="width: 80px; height: 80px; object-fit: cover;">
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

            <script>
                // Dynamic caret toggle for each order
                const collapseBtn = document.getElementById('collapse-btn{{ $order->id }}');
                const orderDetails = document.getElementById('orderDetails{{ $order->id }}');

                orderDetails.addEventListener('show.bs.collapse', function () {
                    collapseBtn.innerHTML = '<i class="bi bi-caret-up-fill"></i>';
                });

                orderDetails.addEventListener('hide.bs.collapse', function () {
                    collapseBtn.innerHTML = '<i class="bi bi-caret-down-fill"></i>';
                });
            </script>
        @empty
            <p class="text-center">No orders found.</p>
        @endforelse
    </div>
@endsection