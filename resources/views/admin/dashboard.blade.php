@extends('layouts.admin')

@section('content')
    <div class="row">
        <!-- Total Users Card -->
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Total Users</h5>
                    <p class="card-text">{{ $totalUsers }}</p>
                </div>
            </div>
        </div>

        <!-- Total Revenue Card -->
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-success">
                <div class="card-body">
                    <h5 class="card-title">Total Revenue</h5>
                    <p class="card-text">₱{{ $totalRevenue }}</p>
                </div>
            </div>
        </div>

        <!-- Total Pending Orders Card -->
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Pending Orders</h5>
                    <p class="card-text">{{ $pendingOrders }}</p>
                </div>
            </div>
        </div>

        <!-- Total Products Card -->
        <div class="col-md-3 mb-4">
            <div class="card text-white bg-info">
                <div class="card-body">
                    <h5 class="card-title">Total Products</h5>
                    <p class="card-text">{{ $totalProducts }}</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart -->
    <div class="row">
        <div class="col-md-12">
            <canvas id="dashboardChart"></canvas>
        </div>
    </div>

    <script>
        var ctx = document.getElementById('dashboardChart').getContext('2d');
        var dashboardChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Users', 'Revenue', 'Orders', 'Products'],
                datasets: [{
                    label: 'Total Count',
                    data: [{{ $totalUsers }}, {{ $totalRevenue }}, {{ $pendingOrders }}, {{ $totalProducts }}],
                    backgroundColor: ['#007bff', '#28a745', '#ffc107', '#17a2b8'],
                    borderColor: ['#007bff', '#28a745', '#ffc107', '#17a2b8'],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    </script>
@endsection
