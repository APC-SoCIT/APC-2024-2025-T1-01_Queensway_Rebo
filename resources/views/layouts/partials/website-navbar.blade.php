<!-- Navbar with color palette applied -->
<style>
    :root {
        --primary: #004b8d;
        /* Deep AI Blue */
        --accent: #f4b400;
        /* Gold Accent */
        --light-beige: #e1ddd1;
        --soft-blue: #add8e6;
        --deep-blue: #2608bd;
        --white: #ffffff;
        --nav-shadow: rgba(0, 0, 0, 0.08);
        --hover-bg: rgba(0, 75, 141, 0.1);
    }

    /* NAVBAR BASE */
    .navbar-custom {
        background: linear-gradient(90deg, var(--light-beige) 0%, var(--soft-blue) 100%);
        box-shadow: 0 4px 10px var(--nav-shadow);
        transition: all 0.3s ease;
        padding: 0.8rem 1rem;
    }

    .navbar-custom.scrolled {
        background: linear-gradient(90deg, var(--primary) 0%, var(--deep-blue) 100%);
        box-shadow: 0 4px 16px rgba(0, 0, 0, 0.15);
    }

    /* BRAND LOGO */
    .navbar-brand {
        font-weight: 700;
        color: var(--primary);
        font-size: 1.4rem;
        letter-spacing: 0.5px;
        display: flex;
        align-items: center;
    }

    .navbar-brand:hover {
        color: var(--accent);
        transform: scale(1.02);
        transition: all 0.2s ease;
    }

    /* NAV LINKS */
    .nav-link {
        color: #212529;
        font-weight: 600;
        margin: 0 8px;
        position: relative;
        transition: color 0.3s ease;
    }

    .nav-link:hover,
    .nav-link.active {
        color: var(--primary);
    }

    /* UNDERLINE EFFECT ON HOVER */
    .nav-link::after {
        content: "";
        position: absolute;
        bottom: -4px;
        left: 50%;
        width: 0;
        height: 2px;
        background-color: var(--accent);
        transition: all 0.3s ease;
        transform: translateX(-50%);
    }

    .nav-link:hover::after,
    .nav-link.active::after {
        width: 40%;
    }

    /* Remove Bootstrap dropdown caret */
    .nav-link.dropdown-toggle::after {
        display: none !important;
    }


    /* DROPDOWN MENU */
    .dropdown-menu {
        border: none;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
        overflow: hidden;
        animation: dropdownFade 0.2s ease;
    }

    @keyframes dropdownFade {
        from {
            opacity: 0;
            transform: translateY(-8px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .dropdown-item {
        font-weight: 500;
        color: #212529;
        transition: all 0.3s ease;
        padding: 10px 20px;
    }

    .dropdown-item:hover {
        background-color: var(--hover-bg);
        color: var(--primary);
    }

    /* CART BADGE */
    .nav-link .badge {
        font-size: 0.75rem;
        position: relative;
        top: -2px;
        background-color: var(--accent) !important;
        color: #000;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15);
    }

    /* USER MENU ICON */
    #userMenu {
        font-size: 1.5rem;
        color: var(--primary);
        transition: 0.3s;
    }

    #userMenu:hover {
        color: var(--accent);
        transform: translateY(-1px);
    }

    /* RESPONSIVE */
    @media (max-width: 992px) {
        .navbar-custom {
            background: var(--white);
        }

        .navbar-nav {
            text-align: center;
        }

        .nav-link {
            padding: 10px 0;
        }
    }
</style>


<nav class="navbar navbar-expand-lg navbar-light navbar-custom">
    <div class="container">
        <!-- Centered logo -->
        <div class="container justify-content-center">
            <a class="navbar-brand mx-auto" href="{{ url('/') }}">
                <img src="{{ asset('storage/images/logo.png') }}" alt="Logo"
                    style="width: 95px; height: 70px; object-fit: contain;">
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
                    <a class="nav-link dropdown-toggle" href="#" id="toolsDropdown" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Tools
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="toolsDropdown">
                        <li><a class="dropdown-item" href="{{ url('/tile-calculator') }}">Tile Calculator</a></li>
                        <li><a class="dropdown-item" href="{{ url('/installation-videos') }}">Installation Videos</a>
                        </li>
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
                            <span id="cart-badge" class="badge bg-danger rounded-pill ms-2"
                                style="{{ $count ? '' : 'display:none;' }}">
                                {{ $count }}
                            </span>
                        </a>

                    </li>

                    <!-- User Dropdown -->
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="userMenu" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <span style="font-size: 1.25rem; line-height: 1;">&#128100;</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                            <li>
                                <a class="dropdown-item" href="{{ url('user/dashboard') }}">🏠 Home</a>
                            </li>
                            <li>
                                <a class="dropdown-item" href="/user/account-info">
                                    👤 {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}
                                </a>

                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
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