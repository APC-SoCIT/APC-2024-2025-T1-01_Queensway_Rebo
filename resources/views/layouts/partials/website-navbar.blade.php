<!-- Navbar with color palette applied -->
<style>
:root {
    --light-green: #c1e1c1;
    --beige: #e1ddd1;
    --light-gray: #e6d6d6;
    --white: #fefefe;
    --blue-accent: #8890d4;
    --deep-blue: #2608bd;
    --pastel-blue: #add8e6;
    --bold-blue: #342de5;
}

/* Navbar styling */
.navbar-custom {
    background-color: var(--beige) !important;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
}

.navbar-brand {
    font-weight: 600;
    color: var(--deep-blue);
}

.navbar-brand:hover {
    color: var(--bold-blue);
}

.nav-link {
    color: black;
    font-weight: 600;
    transition: color 0.15s ease-in-out;
}

.nav-link:hover,
.nav-link.active {
    color: #26707c;
}

.dropdown-menu {
    border: none;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.05);
}

.dropdown-item:hover {
    background-color: var(--light-green);
    color: #000;
}
</style>

<nav class="navbar navbar-expand-lg navbar-light navbar-custom">
    <div class="container">
        <!-- Centered logo -->
        <div class="container justify-content-center">
            <a class="navbar-brand mx-auto" href="{{ url('/') }}">
                <img src="{{ asset('storage/images/logo.png') }}" alt="Logo" style="width: 95px; height: 70px; object-fit: contain;">
            </a>
        </div>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="/">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/shop">Shop</a>
                </li>

                <!-- Tools Dropdown -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="toolsDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Tools
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="toolsDropdown">
                        <li><a class="dropdown-item" href="{{ url('/tile-calculator') }}">Tile Calculator</a></li>
                        <li><a class="dropdown-item" href="{{ url('/installation-videos') }}">Installation Videos</a></li>
                    </ul>
                </li>

                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                @endguest

                @auth
                    <!-- Cart Link with Red Badge -->
                    <li class="nav-item">
                        <a class="nav-link position-relative d-flex align-items-center" href="/cart">
                            Cart
                            @php $count = count(session('cart', [])); @endphp
                            @if ($count > 0)
                                <span class="badge bg-danger rounded-pill ms-2">{{ $count }}</span>
                            @endif
                        </a>
                    </li>

                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span style="font-size: 1.25rem; line-height: 1;">&#128100;</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                            <li>
                                <a class="dropdown-item" href="{{ url('user/dashboard') }}">🏠 Home</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/user/account-info">👤 {{ Auth::user()->name }}</a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">🚪 Logout</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>
