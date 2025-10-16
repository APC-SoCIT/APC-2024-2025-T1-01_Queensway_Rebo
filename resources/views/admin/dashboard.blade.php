@extends('layouts.admin')

@section('content')
    <style>
        /* Existing Styles */
        .small-box-clean {
            background: #fff;
            border-radius: .5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .06);
            padding: 1.5rem 1.25rem;
            border: 1px solid #e6e6e6;
            position: relative;
        }a

        .small-box-clean:hover {
            box-shadow: 0 4px 8px rgba(0, 0, 0, .08);
        }

        .small-box-clean .inner h3 {
            font-size: 1.6rem;
            font-weight: 700;
            margin: 0 0 .15rem;a
        }

        .small-box-clean .inner p {
            margin: 0;
            color: #6c757d;
            font-weight: 500;
        }

        .small-box-clean .icon {
            position: absolute;
            top: 1.25rem;
            right: 1.25rem;
            font-size: 3.2rem;
        }

        .icon-users i {
            color: #007bff !important;
        }

        .icon-dollar i {
            color: #28a745 !important;
        }

        .icon-cart i {
            color: #ffc107 !important;
        }

        .icon-box i {
            color: #17a2b8 !important;
        }

        .sales-card,
        .product-overview {
            background: #fff;
            border: 1px solid #e6e6e6;
            border-radius: .5rem;
            box-shadow: 0 2px 4px rgba(0, 0, 0, .06);
            margin-bottom: 2rem;
        }

        .sales-card-header {
            display: flex;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #e6e6e6;
        }

        .sales-card-body,
        .product-overview {
            padding: 1.25rem;
        }

        .product-overview h3 {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #333;
        }

        .product-overview-header {
            display: flex;
            justify-content: flex-end;
            margin-bottom: 1rem;
        }

        .search-input {
            max-width: 300px;
        }

        .table-responsive {
            max-height: 400px;
            overflow-y: auto;
        }

        table.table {
            width: 100%;
            border-collapse: collapse;
        }

        table.table th,
        table.table td {
            padding: 0.75rem 1rem;
            border-bottom: 1px solid #dee2e6;
            text-align: left;
        }

        table.table th {
            background-color: #f8f9fa;
            font-weight: 600;
            cursor: pointer;
            position: relative;
        }

        table.table th.asc::after,
        table.table th.desc::after {
            content: '';
            position: absolute;
            right: 10px;
            top: 50%;
            border: 5px solid transparent;
            transform: translateY(-50%);
        }

        table.table th.asc::after {
            border-bottom-color: #333;
        }

        table.table th.desc::after {
            border-top-color: #333;
        }

        .badge {
            display: inline-block;
            padding: 0.25em 0.6em;
            font-size: 0.75em;
            font-weight: 600;
            border-radius: 0.35rem;
            color: #fff;
        }

        .badge-success {
            background-color: #28a745;
        }

        .badge-danger {
            background-color: #dc3545;
        }

        .badge-secondary {
            background-color: #6c757d;
        }
    </style>

    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box-clean">
                <div class="inner">
                    <h3>{{ $totalUsers }}</h3>
                    <p>Total Users</p>
                </div>
                <div class="icon icon-users"><i class="fas fa-users"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box-clean">
                <div class="inner">
                    <h3>₱{{ number_format($totalRevenue, 2) }}</h3>
                    <p>Total Revenue</p>
                </div>
                <div class="icon icon-dollar"><i class="fas fa-dollar-sign"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box-clean">
                <div class="inner">
                    <h3>{{ $pendingOrders }}</h3>
                    <p>Pending Orders</p>
                </div>
                <div class="icon icon-cart"><i class="fas fa-shopping-cart"></i></div>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box-clean">
                <div class="inner">
                    <h3>{{ $totalProducts }}</h3>
                    <p>Total Products</p>
                </div>
                <div class="icon icon-box"><i class="fas fa-box"></i></div>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <div class="sales-card">
                <div class="sales-card-header">
                    <h3>Sales Overview</h3>
                    <div class="dropdown">
                        <button id="salesRangeBtn" class="btn btn-sm btn-light border btn-time dropdown-toggle"
                            data-toggle="dropdown">
                            Last 30 Days
                        </button>
                        <div class="dropdown-menu dropdown-menu-right">
                            <a href="#" class="dropdown-item" data-range="7">Last 7 Days</a>
                            <a href="#" class="dropdown-item active" data-range="30">Last 30 Days</a>
                            <a href="#" class="dropdown-item" data-range="90">Last 3 Months</a>
                            <a href="#" class="dropdown-item" data-range="365">Last Year</a>
                        </div>
                    </div>
                </div>
                <div class="sales-card-body">
                    <canvas id="dashboardChart" style="height:300px"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <div class="product-overview">
                <h3 class="mb-4">Product Overview</h3>

                <!-- Tabs -->
                <ul class="nav nav-tabs mb-3" id="productTabs" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link active" id="top-sellers-tab" data-toggle="tab" href="#top-sellers" role="tab">Top
                            Sellers</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="low-stock-tab" data-toggle="tab" href="#low-stock" role="tab">Critically
                            Low</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" id="out-of-stock-tab" data-toggle="tab" href="#out-of-stock" role="tab">Out of
                            Stock</a>
                    </li>
                    <li class="nav-item ml-auto">
                        <button class="btn btn-outline-secondary btn-sm" onclick="location.reload()">↻ Refresh</button>
                    </li>
                </ul>

                <!-- Tab Contents -->
                <div class="tab-content" id="productTabsContent">

                    <!-- Top Sellers -->
                    <div class="tab-pane fade show active" id="top-sellers" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Price</th>
                                        <th>Sales</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topSellingProducts as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>₱{{ number_format($product->price, 2) }}</td>
                                            <td class="d-flex align-items-center">
                                                {{ $product->sales_count ?? 0 }}
                                                <span class="badge badge-success ml-2">Top Selling</span>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3">No top selling products found.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Critically Low -->
                    <div class="tab-pane fade" id="low-stock" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-warning table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Stock</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($criticallyLowProducts as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td>{{ $product->quantity }}</td>
                                            <td class="d-flex align-items-center">
                                                <span class="badge badge-danger">Critically Low</span>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="3">No critically low stock items.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Out of Stock -->
                    <div class="tab-pane fade" id="out-of-stock" role="tabpanel">
                        <div class="table-responsive">
                            <table class="table table-danger table-striped align-middle">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($outOfStockProducts as $product)
                                        <tr>
                                            <td>{{ $product->name }}</td>
                                            <td class="d-flex align-items-center">
                                                <span class="badge badge-secondary">Out of Stock</span>
                                            </td>

                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="2">All products are in stock.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>

            </div>
        </div>

    </div>
@endsection

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const ctx = document.getElementById('dashboardChart').getContext('2d');
            let salesChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json(array_column($salesData, 'date')),
                    datasets: [{
                        label: 'Sales',
                        data: @json(array_column($salesData, 'revenue')),
                        fill: true,
                        backgroundColor: 'rgba(0,123,255,.12)',
                        borderColor: '#007bff',
                        borderWidth: 2,
                        tension: 0.35,
                        pointRadius: 2,
                        pointBackgroundColor: '#007bff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: value => '₱' + value.toLocaleString()
                            }
                        }
                    },
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            callbacks: {
                                label: ctx => '₱' + ctx.parsed.y.toLocaleString()
                            }
                        }
                    }
                }
            });

            document.querySelectorAll('.dropdown-menu a.dropdown-item').forEach(item => {
                item.addEventListener('click', function (e) {
                    e.preventDefault();
                    document.querySelectorAll('.dropdown-menu a.dropdown-item').forEach(i => i.classList.remove('active'));
                    this.classList.add('active');
                    document.getElementById('salesRangeBtn').textContent = this.textContent;

                    const range = this.getAttribute('data-range');
                    fetch(`{{ route('admin.dashboard.salesData') }}?range=${range}`, {
                        headers: { 'X-Requested-With': 'XMLHttpRequest' }
                    })
                        .then(res => res.json())
                        .then(response => {
                            if (response.success) {
                                const salesData = response.data;
                                salesChart.data.labels = salesData.map(d => d.date);
                                salesChart.data.datasets[0].data = salesData.map(d => d.revenue);
                                salesChart.update();
                            }
                        })
                        .catch(err => console.error(err));
                });
            });

            const searchInput = document.getElementById('productSearch');
            const table = document.getElementById('productTable');
            const tbody = table.tBodies[0];

            searchInput.addEventListener('input', () => {
                const query = searchInput.value.toLowerCase();
                Array.from(tbody.rows).forEach(row => {
                    const productName = row.cells[0].textContent.toLowerCase();
                    row.style.display = productName.includes(query) ? '' : 'none';
                });
            });

            const headers = table.querySelectorAll('th[data-sort]');
            headers.forEach(header => {
                header.style.cursor = 'pointer';
                header.addEventListener('click', () => {
                    const sortKey = header.getAttribute('data-sort');
                    const asc = !header.classList.contains('asc');
                    headers.forEach(h => h.classList.remove('asc', 'desc'));
                    header.classList.toggle('asc', asc);
                    header.classList.toggle('desc', !asc);

                    const rows = Array.from(tbody.rows);
                    rows.sort((a, b) => {
                        let aVal = a.cells[header.cellIndex].textContent.trim();
                        let bVal = b.cells[header.cellIndex].textContent.trim();

                        if (sortKey === 'price' || sortKey === 'stock') {
                            aVal = parseFloat(aVal.replace(/[^0-9.-]+/g, "")) || 0;
                            bVal = parseFloat(bVal.replace(/[^0-9.-]+/g, "")) || 0;
                        } else if (sortKey === 'sales') {
                            aVal = parseInt(aVal) || 0;
                            bVal = parseInt(bVal) || 0;
                        }

                        return asc ? aVal - bVal : bVal - aVal;
                    });

                    rows.forEach(row => tbody.appendChild(row));
                });
            });

            const salesHeader = document.querySelector('th[data-sort="sales"]');
            if (salesHeader) {
                salesHeader.classList.add('desc');
                const rows = Array.from(tbody.rows);
                rows.sort((a, b) => {
                    let aVal = parseInt(a.cells[salesHeader.cellIndex].textContent) || 0;
                    let bVal = parseInt(b.cells[salesHeader.cellIndex].textContent) || 0;
                    return bVal - aVal;
                });
                rows.forEach(row => tbody.appendChild(row));
            }
        });
    </script>
@endsection