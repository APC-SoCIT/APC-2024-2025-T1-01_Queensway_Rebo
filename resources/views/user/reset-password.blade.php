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

        <!-- Reset Password Form Section -->
        <div class="auth-form-section">
            <h2 class="text-center mb-4">Reset Password</h2>

            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('password.update') }}" onsubmit="showResetSpinner(event)">
                @csrf
                <input type="hidden" name="token" value="{{ request()->route('token') }}">

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input id="email" type="email" name="email" class="form-control" value="{{ old('email', request()->email) }}" required autofocus>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">New Password</label>
                    <input id="password" type="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input id="password_confirmation" type="password" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" id="reset-btn" class="btn btn-primary w-100">
                    <span class="spinner-border spinner-border-sm d-none" id="reset-spinner" role="status" aria-hidden="true"></span>
                    <span id="reset-text">Reset Password</span>
                </button>
            </form>

            <div class="link-container mt-4">
                <a href="{{ route('login') }}" class="btn btn-link text-primary">Back to Login</a> |
                <a href="{{ url('/') }}" class="btn btn-link text-primary">Return to Website</a>
            </div>
        </div>
    </div>
</div>

<script>
    function showResetSpinner(event) {
        const spinner = document.getElementById('reset-spinner');
        const text = document.getElementById('reset-text');
        const btn = document.getElementById('reset-btn');

        btn.disabled = true;
        spinner.classList.remove('d-none');
        text.textContent = 'Resetting...';
    }
</script>
@endsection
