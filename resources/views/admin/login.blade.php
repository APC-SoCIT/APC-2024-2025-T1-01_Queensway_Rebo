@extends('layouts.app')

@section('content')
<style>
    .auth-wrapper {
        display: flex;
        min-height: 100vh;
        align-items: center;
        justify-content: center;
    }

    .auth-container {
        display: flex;
        width: 100%;
        max-width: 900px;
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
        overflow: hidden;
    }

    .auth-logo-section {
        background-color: #e1ddd1;
        flex: 1;
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 2rem;
    }

    .auth-logo-section img {
        max-width: 100%;
        max-height: 150px;
    }

    .auth-form-section {
        flex: 1;
        padding: 2.5rem;
    }

    .form-label {
        font-weight: 500;
    }

    .btn-primary {
        border-radius: 30px;
    }

    .link-container {
        margin-top: 1rem;
        text-align: center;
    }

    .link-container a {
        margin: 0 8px;
        text-decoration: none;
    }

    .btn-link {
        padding: 0;
    }

    .spinner-border-sm {
        margin-right: 8px;
    }
</style>

<div class="auth-wrapper">
    <div class="auth-container">
        <!-- Left Logo Section -->
        <div class="auth-logo-section">
            <img src="{{ asset('storage/images/logo.png') }}" alt="Queens Rebo Logo">
        </div>

        <!-- Right Login Form Section -->
        <div class="auth-form-section">
            <h3 class="text-center mb-4">Admin Login</h3>

            @if(session('message'))
                <div class="alert alert-info">{{ session('message') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login') }}" onsubmit="showLoginSpinner(event)">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" id="password" class="form-control" required>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>
                    <a href="{{ route('admin.password.request') }}" class="small text-decoration-none">Forgot Password?</a>
                </div>

                <button type="submit" id="login-btn" class="btn btn-primary w-100">
                    <span class="spinner-border spinner-border-sm d-none" id="login-spinner" role="status" aria-hidden="true"></span>
                    <span id="login-text">Login</span>
                </button>
            </form>

            <div class="link-container mt-4">
                <a href="{{ url('/') }}" class="btn btn-link text-primary">Return to Website</a> |
                <a href="{{ route('login') }}" class="btn btn-link text-primary">Login as User</a>
            </div>
        </div>
    </div>
</div>

<script>
    function showLoginSpinner(event) {
        const spinner = document.getElementById('login-spinner');
        const text = document.getElementById('login-text');
        const btn = document.getElementById('login-btn');

        btn.disabled = true;
        spinner.classList.remove('d-none');
        text.textContent = 'Logging in...';
    }
</script>
@endsection
