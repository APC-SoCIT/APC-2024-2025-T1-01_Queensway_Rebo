<aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="{{ route('admin.dashboard') }}" class="brand-link text-center">
        <span class="brand-text font-weight-light">Admin Panel</span>
    </a>

    <!-- Sidebar user panel -->
    <div class="user-panel mt-3 pb-3 mb-3 d-flex align-items-center px-3 ml-2">
        <i class="nav-icon fas fa-user"></i>
        <div class="info ml-2">
            <div class="d-block text-white">{{ Auth::guard('admin')->user()->name ?? 'Guest' }}</div>
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
