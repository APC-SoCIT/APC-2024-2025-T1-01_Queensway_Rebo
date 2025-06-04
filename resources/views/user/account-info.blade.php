@extends('layouts.website')

@section('content')
<style>
    .account-wrapper {
        display: flex;
        gap: 2rem;
        margin-top: 2rem;
    }

    .account-sidebar {
        width: 250px;
        border-right: 1px solid #eee;
        padding-right: 1rem;
    }

    .account-sidebar ul {
        list-style: none;
        padding: 0;
    }

    .account-sidebar li {
        padding: 0.6rem 0;
        font-size: 0.95rem;
        cursor: pointer;
        color: #333;
    }

    .account-sidebar li.active {
        font-weight: bold;
        border-left: 3px solid orange;
        padding-left: 10px;
    }

    .account-content {
        flex: 1;
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-label {
        font-weight: 500;
    }

    .form-control:disabled {
        background-color: #f9f9f9;
    }

    .account-sidebar a {
    color: inherit;
    text-decoration: none;
}

.account-sidebar a:hover {
    text-decoration: underline;
    color: orange; /* Optional hover effect */
}
</style>

<div class="container mt-4">
    <h2 class="mb-4">Account Information</h2>

    <div class="account-wrapper">
        <!-- Sidebar -->
        <div class="account-sidebar">
            <ul>
                <li><a href="{{ route('user.dashboard') }}">My Orders</a></li>
                <li class="active">Account Information</li>
            </ul>
        </div>

        <!-- Account Info Content -->
        <div class="account-content">
            <form method="POST" action="{{ route('user.update-password') }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="form-label">Email Address</label>
                    <input type="email" class="form-control" value="{{ Auth::user()->email }}" disabled>
                </div>

                <h5 class="mt-4">Change Password</h5>

                @if (session('message'))
                    <div class="alert alert-success">{{ session('message') }}</div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="form-group">
                    <label for="current_password" class="form-label">Current Password</label>
                    <input type="password" name="current_password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="new_password" class="form-label">New Password</label>
                    <input type="password" name="new_password" class="form-control" required>
                </div>

                <div class="form-group">
                    <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
                    <input type="password" name="new_password_confirmation" class="form-control" required>
                </div>

                <button type="submit" class="btn btn-primary">Update Password</button>
            </form>
        </div>
    </div>
</div>
@endsection
