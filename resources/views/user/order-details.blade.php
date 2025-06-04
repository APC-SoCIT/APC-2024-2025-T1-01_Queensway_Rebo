@extends('layouts.website')

@section('content')
    <style>
        .order-page {
            margin-top: 2rem;
        }

        .order-container {
            display: flex;
            gap: 2rem;
            flex-wrap: wrap;
        }

        .left-pane {
            flex: 2;
            min-width: 300px;
        }

        .right-pane {
            flex: 1;
            min-width: 250px;
        }

        .section-box {
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
        }

        .progress-bar-track {
            background-color: #f1f1f1;
            height: 8px;
            border-radius: 4px;
            overflow: hidden;
            margin: 0.8rem 0;
        }

        .progress-bar-fill {
            height: 100%;
            background-color: #28a745;
            width:
                {{ 
                $order->order_status === 'shipped' ? '100%' :
        ($order->order_status === 'preparing' ? '66%' :
            ($order->order_status === 'paid' ? '33%' : '0%')) 
            }}

            ;
            transition: width 0.3s ease;
        }

        .order-item {
            display: flex;
            margin-bottom: 1rem;
            border-bottom: 1px solid #eee;
            padding-bottom: 1rem;
        }

        .order-item img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border: 1px solid #ccc;
            margin-right: 1rem;
        }

        .order-total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
        }

        .total-label {
            font-weight: bold;
            border-top: 1px solid #ccc;
            padding-top: 0.8rem;
            margin-top: 0.8rem;
        }

        /* Order History Timeline */
        .section-box ul {
            padding-left: 0;
            list-style: none;
        }

        .section-box ul li {
            border-left: 3px solid #28a745;
            padding-left: 1rem;
            position: relative;
            margin-bottom: 1.5rem;
        }

        .section-box ul li::before {
            content: '';
            position: absolute;
            left: -7px;
            top: 6px;
            width: 10px;
            height: 10px;
            background: #28a745;
            border-radius: 50%;
        }
    </style>

    <div class="container order-page">
        <h2 class="mb-4">Order Details</h2>

        <div class="order-container">
            <!-- Left Side -->
            <div class="left-pane">
                <!-- Order Info -->
                <div class="section-box">
                    <h5>Order Details</h5>
                    <p><strong>Date Ordered:</strong> {{ $order->created_at->format('F j, Y') }}</p>
                    <p><strong>Order Number:</strong> {{ $order->order_number ?? $order->id }}</p>
                    <a href="{{ route('order.invoice', $order->order_number) }}" target="_blank" class="btn btn-outline-secondary">
                        View Invoice
                    </a>

                </div>

                <!-- Delivery Info -->
                <div class="section-box">
                    <h5>Delivery Details</h5>
                    <p><strong>Order Status:</strong> {{ ucfirst($order->order_status) }}</p>
                    <div class="progress-bar-track">
                        <div class="progress-bar-fill"></div>
                    </div>

                    @foreach($order->items as $item)
                        <div class="order-item">
                            <img src="/storage/{{ $item->product->image }}" alt="{{ $item->product->name }}">
                            <div>
                                <strong>{{ $item->product->name }}</strong><br>
                                Price: ₱{{ number_format($item->unit_price, 2) }}<br>
                                Qty: {{ $item->quantity }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- Right Side -->
            <div class="right-pane">
                <!-- Order Total -->
                <div class="section-box">
                    <h5>Order Total</h5>
                    <div class="order-total-row">
                        <span>Subtotal</span>
                        <span>₱{{ number_format($order->subtotal ?? $order->total_amount, 2) }}</span>
                    </div>
                    <div class="order-total-row">
                        <span>Shipping</span>
                        <span>{{ $order->shipping_cost ? '₱' . number_format($order->shipping_cost, 2) : 'Free' }}</span>
                    </div>
                    <div class="order-total-row">
                        <span>Tax</span>
                        <span>₱{{ number_format($order->tax ?? 0, 2) }}</span>
                    </div>
                    <div class="order-total-row total-label">
                        <span>Total</span>
                        <span>₱{{ number_format($order->total_amount, 2) }}</span>
                    </div>
                </div>

                <!-- Order History Timeline -->
                <div class="section-box">
                    <h5>Order History</h5>
                    <ul class="list-unstyled">
                        {{-- Default "Paid" entry using order created_at --}}
                        <li>
                            <strong>Paid</strong><br>
                            <small class="text-muted">{{ $order->created_at->format('F j, Y g:i A') }}</small><br>
                            <em>Order has been placed and paid.</em>
                        </li>

                        {{-- Render other history items if any --}}
                        @foreach($order->histories->sortByDesc('created_at') as $history)
                            <li>
                                <strong>{{ ucfirst($history->status) }}</strong><br>
                                <small class="text-muted">{{ $history->created_at->format('F j, Y g:i A') }}</small><br>
                                @if($history->message)
                                    <em>{{ $history->message }}</em>
                                @endif
                            </li>
                        @endforeach
                    </ul>

                </div>

            </div>
        </div>
    </div>
@endsection