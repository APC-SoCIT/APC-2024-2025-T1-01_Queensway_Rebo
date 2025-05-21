<div class="sidebar bg-dark text-white p-3" style="width: 250px; height: 150vh;">
    <h4 class="text-center py-3">Admin Panel</h4>
    <a href="{{ route('admin.dashboard') }}" class="text-white d-block mb-3">
        <i class="fas fa-chart-line me-2"></i> Dashboard
    </a>
    <a href="{{ route('admin.products.index') }}" class="text-white d-block mb-3">
        <i class="fas fa-box me-2"></i> Products
    </a>
    <a href="{{ route('admin.orders.index') }}" class="text-white d-block mb-3">
        <i class="fas fa-shopping-cart me-2"></i> Orders
    </a>
    <a href="" class="text-white d-block mb-3">
        <i class="fas fa-users me-2"></i> Users
    </a>
    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit" class="btn btn-link text-white w-100 text-start px-3">
            <i class="fas fa-sign-out-alt me-2"></i> Logout
        </button>
    </form>
</div>