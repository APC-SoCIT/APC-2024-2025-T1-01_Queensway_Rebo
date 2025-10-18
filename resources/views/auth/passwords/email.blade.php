@extends('layouts.app')

@section('content')
    <style>
        :root {
            --primary: #222;
            --accent: #f4b400;
            --bg: #fafafa;
            --gray: #6c757d;
            --text: #1c1c1c;
            --shadow: rgba(0, 0, 0, 0.06);
            --border: #e5e5e5;
            --white: #fff;
        }

        body {
            background-color: var(--bg);
            color: var(--text);
            font-family: 'Poppins', sans-serif;
        }

        .auth-wrapper {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 40px 15px;
        }

        .auth-card {
            display: flex;
            flex-direction: row;
            background: var(--white);
            border-radius: 20px;
            box-shadow: 0 6px 20px var(--shadow);
            overflow: hidden;
            width: 100%;
            max-width: 950px;
            animation: fadeInUp 0.6s ease;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Left image section */
        .auth-left {
            flex: 1;
            background: #fff;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 3rem;
            border-right: 1px solid var(--border);
        }

        .auth-left img {
            width: 80%;
            max-width: 280px;
            height: auto;
            filter: drop-shadow(0 4px 8px rgba(0, 0, 0, 0.1));
        }

        /* Right section */
        .auth-right {
            flex: 1;
            padding: 3rem;
        }

        .auth-right h2 {
            font-weight: 700;
            color: var(--primary);
            text-align: center;
            margin-bottom: 1.5rem;
        }

        .form-label {
            font-weight: 600;
            color: var(--text);
            font-size: 0.95rem;
        }

        .form-control {
            border-radius: 12px;
            border: 1.5px solid var(--border);
            padding: 0.75rem 1rem;
            transition: all 0.3s ease;
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: var(--accent);
            box-shadow: 0 0 6px rgba(244, 180, 0, 0.3);
            outline: none;
        }

        .btn-primary {
            background-color: var(--accent);
            color: var(--primary);
            border: none;
            border-radius: 30px;
            font-weight: 600;
            padding: 0.75rem;
            width: 100%;
            transition: 0.3s ease;
            box-shadow: 0 4px 10px var(--shadow);
        }

        .btn-primary:hover {
            background-color: #e2a800;
            transform: translateY(-2px);
        }

        .link-container {
            text-align: center;
            margin-top: 1.5rem;
        }

        .link-container a {
            color: var(--primary);
            text-decoration: none;
            transition: color 0.3s ease;
            font-weight: 500;
        }

        .link-container a:hover {
            color: var(--accent);
        }

        .alert {
            border-radius: 12px;
            font-size: 0.9rem;
        }

        @media (max-width: 768px) {
            .auth-card {
                flex-direction: column;
                border-radius: 15px;
            }

            .auth-left {
                border-right: none;
                border-bottom: 1px solid var(--border);
                padding: 2rem;
            }
        }
    </style>

    <div class="auth-wrapper">
        <div class="auth-card" data-aos="fade-up">
            <!-- Left Logo Section -->
            <div class="auth-left">
                <img src="{{ asset('storage/images/logo.png') }}" alt="Queens Rebo Logo">
            </div>

            <!-- Right Password Reset Section -->
            <div class="auth-right">
                <h2>Password Reset</h2>

                @if (session('status'))
                    <div class="alert alert-success">{{ session('status') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                @endif

                <form method="POST" action="{{ route('password.email') }}" onsubmit="showSendSpinner(event)">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email Address</label>
                        <input type="email" id="email" name="email" class="form-control" required autofocus>
                    </div>

                    <button type="submit" id="send-btn" class="btn btn-primary">
                        <span class="spinner-border spinner-border-sm d-none" id="send-spinner"></span>
                        <span id="send-text">Send Password Reset Link</span>
                    </button>
                </form>

                <div class="link-container">
                    <a href="{{ route('login') }}">← Back to Login</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/aos@2.3.4/dist/aos.js"></script>
    <script>
        AOS.init({ duration: 800, once: true });

        function showSendSpinner(event) {
            const spinner = document.getElementById('send-spinner');
            const text = document.getElementById('send-text');
            const btn = document.getElementById('send-btn');
            btn.disabled = true;
            spinner.classList.remove('d-none');
            text.textContent = 'Sending...';
        }
    </script>
@endsection