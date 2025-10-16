@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold text-gray-800 mb-0">
            👤 Account Settings
        </h2>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-indigo d-flex align-items-center gap-2">
            <i class="fas fa-arrow-left"></i> <span>Back to Dashboard</span>
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.update-password') }}">
                @csrf
                @method('PUT')

                <!-- Email -->
                <div class="mb-4">
                    <label class="form-label fw-semibold text-gray-700">Email Address</label>
                    <input type="email" class="form-control shadow-sm" value="{{ Auth::user()->email }}" disabled>
                </div>

                <hr class="my-4">

                <!-- Change Password -->
                <h5 class="fw-semibold text-gray-800 mb-3">🔒 Change Password</h5>

                @if (session('message'))
                    <div class="alert alert-success py-2 px-3 rounded-2 shadow-sm small">
                        {{ session('message') }}
                    </div>
                @endif

                @if ($errors->any())
                    <div class="alert alert-danger py-2 px-3 rounded-2 shadow-sm small">
                        <ul class="mb-0 ps-3">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row g-4">
                    <div class="col-md-4">
                        <label for="current_password" class="form-label fw-semibold text-gray-700">Current Password</label>
                        <input type="password" name="current_password" id="current_password"
                            class="form-control shadow-sm" required>
                    </div>

                    <div class="col-md-4">
                        <label for="new_password" class="form-label fw-semibold text-gray-700">New Password</label>
                        <input type="password" name="new_password" id="new_password"
                            class="form-control shadow-sm" required>
                    </div>

                    <div class="col-md-4">
                        <label for="new_password_confirmation" class="form-label fw-semibold text-gray-700">
                            Confirm New Password
                        </label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation"
                            class="form-control shadow-sm" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-indigo px-4 d-flex align-items-center gap-2">
                        <i class="fas fa-save"></i> <span>Update Password</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- FontAwesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>

<style>
/* ===== Modern Indigo Admin Theme ===== */
.text-gray-700 { color: #374151 !important; }
.text-gray-800 { color: #1f2937 !important; }

.btn-indigo {
    background: #4f46e5;
    color: #fff;
    border: none;
    transition: all 0.2s ease-in-out;
}
.btn-indigo:hover {
    background: #4338ca;
    color: #fff;
}

.btn-outline-indigo {
    border: 1.5px solid #4f46e5;
    color: #4f46e5;
    background: transparent;
    transition: all 0.2s ease-in-out;
}
.btn-outline-indigo:hover {
    background: #4f46e5;
    color: #fff;
}

.form-control {
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    font-size: 0.95rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.form-control:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.15);
}

.card {
    background: #fff;
    border-radius: 12px;
}
</style>
@endsection
