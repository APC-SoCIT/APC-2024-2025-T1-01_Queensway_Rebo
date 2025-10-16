<nav class="main-header navbar navbar-expand navbar-white shadow-sm border-0 py-3 px-4">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <!-- Left Section -->
        <div class="d-flex align-items-center gap-3">
            <h5 class="mb-0 fw-semibold text-gray-800">Admin Panel</h5>
        </div>

        <!-- Right Section -->
        <ul class="navbar-nav ms-auto d-flex align-items-center gap-3 mb-0">
            <!-- Logout Button -->
            <li class="nav-item">
                <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" class="mb-0">
                    @csrf
                    <button class="btn btn-indigo d-flex align-items-center gap-2 px-3 py-2" type="submit">
                        <i class="fas fa-sign-out-alt"></i> <span>Logout</span>
                    </button>
                </form>
            </li>
        </ul>
    </div>
</nav>

{{-- FontAwesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>

<style>
/* ===== Modern Indigo Navbar ===== */
.navbar {
    background: #ffffff !important;
    border-bottom: 1px solid #f3f4f6 !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}

.text-indigo { color: #4f46e5 !important; }
.text-gray-700 { color: #374151 !important; }
.text-gray-800 { color: #1f2937 !important; }

.btn-indigo {
    background: #4f46e5;
    color: #fff;
    border: none;
    border-radius: 8px;
    transition: all 0.2s ease-in-out;
}
.btn-indigo:hover {
    background: #4338ca;
    color: #fff;
}

.navbar .nav-item span {
    font-size: 0.95rem;
}

.navbar h5 {
    font-size: 1.1rem;
}
</style>
