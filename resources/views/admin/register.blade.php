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

    .spinner-border-sm {
        margin-right: 8px;
    }

    .link-container {
        margin-top: 1rem;
        text-align: center;
    }
</style>

<div class="auth-wrapper">
    <div class="auth-container">
        <!-- Logo Section -->
        <div class="auth-logo-section">
            <img src="{{ asset('storage/images/logo.png') }}" alt="Queens Rebo Logo">
        </div>

        <!-- Admin Register Form Section -->
        <div class="auth-form-section">
            <h2 class="text-center mb-4">Admin Registration</h2>

            {{-- Session Message --}}
            @if(session('message'))
                <div class="alert alert-success">
                    {{ session('message') }}
                </div>
            @endif

            {{-- Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.register.submit') }}" onsubmit="showRegisterSpinner(event)">
                @csrf

                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" name="name" class="form-control" id="name" value="{{ old('name') }}" placeholder="Enter your name" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" name="email" class="form-control" id="email" value="{{ old('email') }}" placeholder="Enter your email" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" id="password" placeholder="Create a password" required>
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control" id="password_confirmation" placeholder="Confirm your password" required>
                </div>

                <button type="submit" id="register-btn" class="btn btn-primary w-100">
                    <span id="register-spinner" class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                    <span id="register-text">Register</span>
                </button>
            </form>

            <p class="text-center mt-3">
                Already have an account?
                <a href="{{ route('admin.login') }}" class="text-decoration-none">Login here</a>
            </p>

            <div class="link-container">
                <a href="{{ url('/') }}" class="btn btn-link text-primary">Return to Website</a> |
                <a href="{{ route('login') }}" class="btn btn-link text-primary">Login as User</a>
            </div>
        </div>
    </div>
</div>

<script>
    function showRegisterSpinner(event) {
        const spinner = document.getElementById('register-spinner');
        const text = document.getElementById('register-text');
        const button = document.getElementById('register-btn');

        spinner.classList.remove('d-none');
        text.textContent = 'Registering...';
        button.disabled = true;
    }
</script>
@endsection
