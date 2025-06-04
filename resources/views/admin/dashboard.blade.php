@extends('layouts.admin')

@section('content')
<style>
    /* KPI cards (existing styles) */
    .small-box-clean {
        background: #fff;
        border-radius: .5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,.06);
        padding: 1.5rem 1.25rem;
        border: 1px solid #e6e6e6;
        position: relative;
    }
    .small-box-clean:hover {
        box-shadow: 0 4px 8px rgba(0,0,0,.08);
    }
    .small-box-clean .inner h3 {
        font-size: 1.6rem;
        font-weight: 700;
        margin: 0 0 .15rem;
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
    .icon-users i { color: #007bff !important; }
    .icon-dollar i { color: #28a745 !important; }
    .icon-cart i { color: #ffc107 !important; }
    .icon-box i { color: #17a2b8 !important; }

    /* Sales Overview card */
    .sales-card {
        background: #fff;
        border: 1px solid #e6e6e6;
        border-radius: .5rem;
        box-shadow: 0 2px 4px rgba(0,0,0,.06);
        margin-bottom: 1.5rem;
    }
    .sales-card-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1rem 1.25rem;
        border-bottom: 1px solid #e6e6e6;
    }
    .sales-card-header h3 {
        margin: 0;
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
    }
    .sales-card-body {
        padding: 1.25rem;
    }
    .sales-card .btn-time {
        font-size: .875rem;
        color: #6c757d;
    }

    /* Product Overview Table */
    .product-overview {
        background: #fff;
        border: 1px solid #e6e6e6;
        border-radius: .5rem;
        padding: 1rem;
        box-shadow: 0 2px 4px rgba(0,0,0,.06);
        margin-bottom: 2rem;
    }
    /* Make Product Overview heading font size same as Sales Overview */
    .product-overview h3 {
        font-size: 1.1rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: #333;
    }
    /* Search bar container aligned right */
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
    table.table th, table.table td {
        padding: 0.75rem 1rem;
        border-bottom: 1px solid #dee2e6;
        text-align: left;
    }
    table.table th {
        background-color: #f8f9fa;
        font-weight: 600;
        cursor: pointer;
        position: relative;
        user-select: none;
    }
    /* Sorting arrow indicators */
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
</style>

{{-- KPI ROW --}}
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

{{-- SALES OVERVIEW --}}
<div class="row mt-4">
    <div class="col-12">
        <div class="sales-card">
            <div class="sales-card-header">
                <h3>Sales Overview</h3>
                <div class="dropdown">
                    <button id="salesRangeBtn" class="btn btn-sm btn-light border btn-time dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Last 30 Days
                    </button>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="salesRangeBtn">
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

{{-- PRODUCT OVERVIEW --}}
<div class="row">
    <div class="col-12">
        <div class="product-overview">
            <h3>Product Overview</h3>
            <div class="product-overview-header">
                <input type="text" id="productSearch" class="form-control search-input" placeholder="Search products by name..." />
            </div>

            <div class="table-responsive">
                <table class="table table-hover" id="productTable">
                    <thead>
                        <tr>
                            <th data-sort="name">Product</th>
                            <th data-sort="price">Price</th>
                            <th data-sort="sales">Sales</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($products as $product)
                        <tr>
                            <td>{{ $product->name }}</td>
                            <td>₱{{ number_format($product->price, 2) }}</td>
                            <td>{{ $product->sales_count ?? 0   }} Sold</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@section('js')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', () => {
    // === Sales Chart ===
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
                        callback: function(value) {
                            return '₱' + value.toLocaleString();
                        }
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

    // Dropdown click to load sales data via AJAX
    document.querySelectorAll('.dropdown-menu a.dropdown-item').forEach(item => {
        item.addEventListener('click', function(e) {
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
                if(response.success){
                    const salesData = response.data;
                    salesChart.data.labels = salesData.map(d => d.date);
                    salesChart.data.datasets[0].data = salesData.map(d => d.revenue);
                    salesChart.update();
                }
            })
            .catch(err => console.error(err));
        });
    });

    // === Product Search ===
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

    // === Product Sorting (simple client-side) ===
    const headers = table.querySelectorAll('th[data-sort]');
    headers.forEach(header => {
        header.style.cursor = 'pointer';
        header.addEventListener('click', () => {
            const sortKey = header.getAttribute('data-sort');
            // Toggle asc/desc
            const asc = !header.classList.contains('asc');
            headers.forEach(h => h.classList.remove('asc', 'desc'));
            header.classList.toggle('asc', asc);
            header.classList.toggle('desc', !asc);

            // Sort rows
            const rows = Array.from(tbody.rows);
            rows.sort((a, b) => {
                let aVal = a.cells[header.cellIndex].textContent.trim();
                let bVal = b.cells[header.cellIndex].textContent.trim();

                if(sortKey === 'price') {
                    aVal = parseFloat(aVal.replace(/[^0-9.-]+/g,"")) || 0;
                    bVal = parseFloat(bVal.replace(/[^0-9.-]+/g,"")) || 0;
                } else if(sortKey === 'sales') {
                    aVal = parseInt(aVal) || 0;
                    bVal = parseInt(bVal) || 0;
                }

                if(aVal < bVal) return asc ? -1 : 1;
                if(aVal > bVal) return asc ? 1 : -1;
                return 0;
            });

            rows.forEach(row => tbody.appendChild(row));
        });
    });

    // === Default sort by Sales descending on page load ===
    const salesHeader = document.querySelector('th[data-sort="sales"]');
    if (salesHeader) {
        salesHeader.classList.add('desc'); // mark descending

        const rows = Array.from(tbody.rows);
        rows.sort((a, b) => {
            let aVal = parseInt(a.cells[salesHeader.cellIndex].textContent) || 0;
            let bVal = parseInt(b.cells[salesHeader.cellIndex].textContent) || 0;
            return bVal - aVal;  // descending
        });
        rows.forEach(row => tbody.appendChild(row));
    }
});
</script>
@endsection
