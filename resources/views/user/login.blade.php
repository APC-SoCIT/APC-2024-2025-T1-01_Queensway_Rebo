@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6 col-lg-4">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body p-4">
                        <h2 class="text-center mb-4">User Login</h2>


                        <!-- Show session success message -->
                        @if (session('message'))
                            <div class="alert alert-success">
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
                                            <form method="POST" action="{{ route('verification.send') }}"
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


                        <form method="POST" action="{{ route('login.submit') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" id="email" class="form-control" name="email" value="{{ old('email') }}"
                                    required autofocus>
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" class="form-control" name="password" required>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-4">
                                <div class="form-check">
                                    <input type="checkbox" class="form-check-input" id="remember">
                                    <label class="form-check-label" for="remember">Remember Me</label>
                                </div>
                                <a href="#" class="text-decoration-none small">Forgot Password?</a>
                            </div>

                            <button type="submit" class="btn btn-primary w-100">Login</button>
                        </form>

                        <p class="mt-3 text-center">Don't have an account? <a href="{{ route('register') }}"
                                class="text-decoration-none">Register here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection