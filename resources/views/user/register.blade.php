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

        <!-- Register Form Section -->
        <div class="auth-form-section">
            <h2 class="text-center mb-4">Create an Account</h2>

            {{-- Display validation errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}" onsubmit="showRegisterSpinner(event)">
                @csrf

                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" class="form-control" id="first_name" name="first_name" value="{{ old('first_name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" class="form-control" id="last_name" name="last_name" value="{{ old('last_name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                </div>

                <button type="submit" id="register-btn" class="btn btn-primary w-100">
                    <span class="spinner-border spinner-border-sm d-none" id="register-spinner" role="status" aria-hidden="true"></span>
                    <span id="register-text">Register</span>
                </button>
            </form>

            <p class="mt-3 text-center">
                Already have an account?
                <a href="{{ route('login') }}" class="text-decoration-none">Login here</a>
            </p>

            <div class="link-container">
                <a href="{{ url('/') }}" class="btn btn-link text-primary">Return to Website</a> |
                <a href="{{ route('admin.login') }}" class="btn btn-link text-primary">Login as Admin</a>
            </div>
        </div>
    </div>
</div>

<script>
    function showRegisterSpinner(event) {
        const spinner = document.getElementById('register-spinner');
        const text = document.getElementById('register-text');
        const btn = document.getElementById('register-btn');

        btn.disabled = true;
        spinner.classList.remove('d-none');
        text.textContent = 'Registering...';
    }
</script>
@endsection
