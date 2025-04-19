@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4">
                        <h3 class="text-center mb-4">Admin Login</h3>

                        {{-- Session Message --}}
                        @if(session('message'))
                            <div class="alert alert-info">
                                {{ session('message') }}
                            </div>
                        @endif

                        {{-- Validation Errors --}}
                        @if($errors->any())
                            <div class="alert alert-danger">
                                <div class="mb-0">
                                    @foreach($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach

                                    <!-- Resend Verification Form -->
                                    @if (session('showResend'))
                                        <div class="mt-3">
                                            <form method="POST" action="{{ route('admin.verification.send') }}"
                                                onsubmit="showResendSpinner(this)">
                                                @csrf
                                                <input type="hidden" name="email" value="{{ old('email') }}">

                                                <button type="submit" class="btn btn-link p-0" id="resend-btn">
                                                    <span class="spinner-border spinner-border-sm d-none" role="status"
                                                        aria-hidden="true" id="resend-spinner"></span>
                                                    <span id="resend-text">Resend Verification Email</span>
                                                </button>
                                            </form>

                                        </div>
                                    @endif

                                </div>
                            </div>
                        @endif



                        <form method="POST" action="{{ route('admin.login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" name="email" id="email" class="form-control" value="{{ old('email') }}"
                                    required autofocus>
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
                                <a href="#" class="small text-decoration-none">Forgot Password?</a>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>

                        <p class="mt-4 text-center small">
                            Don’t have an admin account?
                            <a href="{{ route('admin.register') }}" class="text-decoration-none">Register here</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
    function showResendSpinner(form) {
        const spinner = document.getElementById('resend-spinner');
        const text = document.getElementById('resend-text');
        const btn = document.getElementById('resend-btn');

        // Disable the button and show the spinner
        btn.disabled = true;
        spinner.classList.remove('d-none');
        text.textContent = 'Sending...';
    }
</script>