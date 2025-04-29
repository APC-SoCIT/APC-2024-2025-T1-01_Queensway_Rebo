<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container">
        <a class="navbar-brand" href="/">Queens Rebo</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="/">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/shop">Shop</a>
                </li>

                @guest
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('login') }}">Login</a>
                    </li>
                @endguest

                @auth
                    {{-- CART LINK WITH COUNT --}}
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="/cart">
                            Cart
                            @php $count = count(session('cart', [])); @endphp
                            @if($count > 0)
                                <span class="badge bg-danger ms-1">{{ $count }}</span>
                            @endif
                        </a>
                    </li>

                    {{-- USER ICON DROPDOWN --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link" href="#" id="userMenu" role="button" data-bs-toggle="dropdown"
                            aria-expanded="false">
                            <!-- Unicode user icon; swap for <i class="bi bi-person-circle"></i> if you load Bootstrap Icons -->
                            <span style="font-size:1.25rem; line-height:1;">&#128100;</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                            <li>
                                <a class="dropdown-item" href="/user/dashboard">
                                    👤 {{ Auth::user()->name }}
                                </a>
                            </li>
                            <li>
                                <hr class="dropdown-divider">
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="dropdown-item">
                                        🚪 Logout
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @endauth

            </ul>
        </div>
    </div>
</nav>