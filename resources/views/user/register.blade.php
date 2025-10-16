@extends('layouts.app')

@section('content')
<style>
    :root {
        --primary: #004b8d;
        --accent: #f4b400;
        --background: #f8f9fb;
        --text-dark: #1c1c1c;
        --white: #ffffff;
        --shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
    }

    body {
        background: linear-gradient(135deg, #eef2f7 0%, #f8f9fb 100%);
        font-family: 'Poppins', 'Inter', sans-serif;
        color: var(--text-dark);
    }

    .auth-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding: 2rem;
    }

    .auth-card {
        display: flex;
        flex-direction: row;
        width: 100%;
        max-width: 950px;
        background: var(--white);
        border-radius: 20px;
        box-shadow: var(--shadow);
        overflow: hidden;
        animation: fadeInUp 0.6s ease;
    }

    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(30px); }
        to { opacity: 1; transform: translateY(0); }
    }

    .auth-left {
        flex: 1;
        background: linear-gradient(180deg, #004b8d 0%, #003a73 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 3rem;
    }

    .auth-left img {
        width: 85%;
        max-width: 280px;
        height: auto;
        filter: drop-shadow(0 6px 10px rgba(0,0,0,0.3));
    }

    .auth-right {
        flex: 1;
        padding: 3rem;
        background-color: var(--white);
    }

    .auth-right h2 {
        font-weight: 700;
        color: var(--primary);
        text-align: center;
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 600;
        color: #333;
    }

    .form-control {
        border-radius: 10px;
        border: 1.5px solid #ccc;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: var(--primary);
        box-shadow: 0 0 0 0.25rem rgba(0, 75, 141, 0.2);
    }

    .btn-primary {
        background-color: var(--accent);
        border: none;
        border-radius: 50px;
        color: #222;
        font-weight: 600;
        padding: 0.75rem;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background-color: #e2a800;
        transform: translateY(-2px);
        box-shadow: 0 6px 14px rgba(0,0,0,0.1);
    }

    .link-container {
        text-align: center;
        margin-top: 1.5rem;
    }

    .link-container a {
        color: var(--primary);
        text-decoration: none;
        transition: color 0.3s;
    }

    .link-container a:hover {
        color: var(--accent);
    }

    .alert {
        border-radius: 10px;
        font-size: 0.9rem;
    }

    @media (max-width: 768px) {
        .auth-card {
            flex-direction: column;
        }

        .auth-left {
            padding: 2rem;
        }
    }
</style>

<div class="auth-wrapper">
    <div class="auth-card">
        <!-- Left Logo Section -->
        <div class="auth-left">
            <img src="{{ asset('storage/images/logo.png') }}" alt="Queens Rebo Logo">
        </div>

        <!-- Right Register Section -->
        <div class="auth-right">
            <h2>Create an Account</h2>

            {{-- Validation Errors --}}
            @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST" action="{{ route('register.submit') }}" onsubmit="showRegisterSpinner(event)">
                @csrf

                <div class="mb-3">
                    <label for="first_name" class="form-label">First Name</label>
                    <input type="text" id="first_name" name="first_name" class="form-control" value="{{ old('first_name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="last_name" class="form-label">Last Name</label>
                    <input type="text" id="last_name" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" id="password" name="password" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                </div>

                <button type="submit" id="register-btn" class="btn btn-primary w-100">
                    <span class="spinner-border spinner-border-sm d-none" id="register-spinner" role="status" aria-hidden="true"></span>
                    <span id="register-text">Register</span>
                </button>
            </form>

            <div class="link-container">
                <p class="mt-3">Already have an account? <a href="{{ route('login') }}">Login here</a></p>
                <a href="{{ url('/') }}">← Return to Website</a>
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
