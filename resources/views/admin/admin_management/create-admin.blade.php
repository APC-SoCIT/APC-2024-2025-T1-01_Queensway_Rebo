@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold text-gray-800 mb-0">👤 Add New Admin</h2>
        <a href="{{ route('admin.admin_management.index') }}" class="btn btn-outline-indigo d-flex align-items-center gap-2">
            <i class="fas fa-arrow-left"></i> <span>Back to Admins</span>
        </a>
    </div>

    <!-- Success & Error Alerts -->
    @if(session('success'))
        <div class="alert alert-success shadow-sm border-0">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger shadow-sm border-0">
            <ul class="mb-0 ps-3 small">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.admin_management.store') }}">
                @csrf

                <div class="row g-4">
                    <!-- First Name -->
                    <div class="col-md-6">
                        <label for="first_name" class="form-label fw-semibold text-gray-700">First Name</label>
                        <input type="text" class="form-control shadow-sm" id="first_name" name="first_name"
                            placeholder="Enter first name" required>
                    </div>

                    <!-- Last Name -->
                    <div class="col-md-6">
                        <label for="last_name" class="form-label fw-semibold text-gray-700">Last Name</label>
                        <input type="text" class="form-control shadow-sm" id="last_name" name="last_name"
                            placeholder="Enter last name" required>
                    </div>

                    <!-- Email -->
                    <div class="col-12">
                        <label for="email" class="form-label fw-semibold text-gray-700">Email Address</label>
                        <input type="email" class="form-control shadow-sm" id="email" name="email"
                            placeholder="Enter email address" required>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-indigo px-4 d-flex align-items-center gap-2">
                        <i class="fas fa-save"></i> <span>Create Admin</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- FontAwesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>

<style>
/* ===== Modern Theme (matches FAQ/Add Product) ===== */
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

.form-control, .form-select {
    border-radius: 8px;
    border: 1px solid #e5e7eb;
    font-size: 0.95rem;
    transition: border-color 0.2s, box-shadow 0.2s;
}
.form-control:focus, .form-select:focus {
    border-color: #4f46e5;
    box-shadow: 0 0 0 0.2rem rgba(79, 70, 229, 0.15);
}

.card {
    background: #fff;
    border-radius: 12px;
}

.alert {
    font-size: 0.9rem;
}
</style>
@endsection
