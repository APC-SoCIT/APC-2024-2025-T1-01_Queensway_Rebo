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

    .btn-link {
        padding: 0;
    }

    .spinner-border-sm {
        margin-right: 8px;
    }
</style>

<div class="auth-wrapper">
    <div class="auth-container">
        <!-- Logo Section -->
        <div class="auth-logo-section">
            <img src="{{ asset('storage/images/logo.png') }}" alt="Queens Rebo Logo">
        </div>

        <!-- Login Form Section -->
        <div class="auth-form-section">
            <h2 class="text-center mb-4">User Login</h2>

            @if (session('message'))
                <div class="alert alert-success">{{ session('message') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach

                    @if (session('showResend'))
                        <div class="mt-3">
                            <form method="POST" action="{{ route('verification.send') }}" onsubmit="showResendSpinner(this)">
                                @csrf
                                <input type="hidden" name="email" value="{{ old('email') }}">
                                <button type="submit" class="btn btn-link p-0" id="resend-btn">
                                    <span class="spinner-border spinner-border-sm d-none" role="status" id="resend-spinner"></span>
                                    <span id="resend-text">Resend Verification Email</span>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endif

            <form method="POST" action="{{ route('login.submit') }}" onsubmit="showLoginSpinner(event)">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" class="form-control" name="password" required>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="remember" id="remember">
                        <label class="form-check-label" for="remember">Remember Me</label>
                    </div>
                    <a href="{{ route('password.request') }}" class="small text-decoration-none">Forgot Password?</a>

                </div>

                <button type="submit" id="login-btn" class="btn btn-primary w-100">
                    <span class="spinner-border spinner-border-sm d-none" id="login-spinner" role="status" aria-hidden="true"></span>
                    <span id="login-text">Login</span>
                </button>
            </form>

            <p class="mt-3 text-center">
                Don't have an account? <a href="{{ route('register') }}" class="text-decoration-none">Register here</a>
            </p>

            <div class="link-container">
                <a href="{{ url('/') }}" class="btn btn-link text-primary">Return to Website</a> |
                <a href="{{ route('admin.login') }}" class="btn btn-link text-primary">Login as Admin</a>
            </div>
        </div>
    </div>
</div>

<script>
    function showResendSpinner(form) {
        const spinner = document.getElementById('resend-spinner');
        const text = document.getElementById('resend-text');
        const btn = document.getElementById('resend-btn');
        btn.disabled = true;
        spinner.classList.remove('d-none');
        text.textContent = 'Sending...';
    }

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
