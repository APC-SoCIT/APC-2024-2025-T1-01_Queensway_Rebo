<aside class="sidebar">
    <div class="sidebar-header">
        <div class="brand">
            <i class="fas fa-chart-line"></i>
            <span>{{ Auth::guard('admin')->user()->name ?? 'Guest' }}</span>
        </div>
    </div>

    <!-- Sidebar Menu -->
    <nav class="mt-3">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
            <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                    <i class="nav-icon fas fa-chart-line"></i>
                    <p>Dashboard</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.products.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-box"></i>
                    <p>Products</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.orders.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-shopping-cart"></i>
                    <p>Orders</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.faqs.index') }}" class="nav-link">
                    <i class="nav-icon fas fa-question-circle"></i>
                    <p>FAQs</p>
                </a>
            </li>
            <li class="nav-item">
                <a href="{{ route('admin.account-info') }}" class="nav-link">
                    <i class="nav-icon fas fa-user-shield"></i>
                    <p>Account Info</p>
                </a>
            </li>

            @if(Auth::guard('admin')->user()->role === 'super_admin')
                <li class="nav-item">
                    <a href="{{ route('admin.admin_management.index') }}" class="nav-link">
                        <i class="nav-icon fas fa-user-cog"></i>
                        <p>Admin Management</p>
                    </a>
                </li>
            @endif

            <li class="nav-item">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-link nav-link text-left">
                        <i class="nav-icon fas fa-sign-out-alt"></i>
                        <p>Logout</p>
                    </button>
                </form>
            </li>
        </ul>
    </nav>
</aside>

<style>
/* ===== Modern Fixed Sidebar (No Collapse) ===== */
.sidebar {
    width: 250px;
    background: #ffffff;
    box-shadow: 2px 0 12px rgba(0, 0, 0, 0.05);
    height: 100vh;
    position: fixed;
    top: 0;
    left: 0;
    border-right: 1px solid #f1f1f1;
    z-index: 1000;
}

.sidebar-header {
    display: flex;
    align-items: center;
    justify-content: flex-start;
    gap: 0.6rem;
    padding: 1rem 1.25rem;
    border-bottom: 1px solid #f1f1f1;
}

.sidebar-header .brand {
    display: flex;
    align-items: center;
    gap: 0.6rem;
    font-size: 1.15rem;
    font-weight: 600;
    color: #2d3748;
}

.sidebar-header .brand i {
    color: #4f46e5;
    font-size: 1.25rem;
}

.nav-item {
    margin: 0.25rem 0;
}

.nav-item a {
    display: flex;
    align-items: center;
    gap: 0.9rem;
    padding: 0.75rem 1.25rem;
    color: #6b7280;
    font-weight: 500;
    font-size: 0.95rem;
    text-decoration: none;
    border-left: 3px solid transparent;
    transition: all 0.25s ease;
}

.nav-item a i {
    font-size: 1.1rem;
    width: 20px;
    text-align: center;
}

.nav-item.active a,
.nav-item a:hover {
    color: #111827;
    background: #f9fafb;
    border-left-color: #4f46e5;
}

.btn-link.nav-link {
    color: #6b7280;
    text-decoration: none;
}

.btn-link.nav-link:hover {
    color: #111827;
    background: #f9fafb;
    border-left-color: #4f46e5;
}
</style>
