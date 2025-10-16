@extends('layouts.admin')

@section('content')
    <div class="container-fluid px-4 py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-semibold text-gray-800 mb-0">👨‍💼 Admin Management</h2>
            <a href="{{ route('admin.admin_management.create') }}" class="btn btn-indigo d-flex align-items-center gap-2">
                <i class="fas fa-user-plus"></i> <span>Add New Admin</span>
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

        <!-- Admins Table -->
        <div class="card shadow-sm border-0 rounded-3">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table id="admin-table" class="table table-hover align-middle mb-0">
                        <thead class="bg-indigo text-white">
                            <tr>
                                <th>Full Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Created At</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($admins as $admin)
                                <tr>
                                    <td class="fw-semibold text-gray-800">{{ $admin->first_name }} {{ $admin->last_name }}</td>
                                    <td>{{ $admin->email }}</td>
                                    <td><span class="badge bg-light text-gray-700 border">{{ ucfirst($admin->role) }}</span></td>
                                    <td>{{ $admin->created_at->format('M d, Y') }}</td>
                                    <td class="text-center">
                                        @if(auth('admin')->user()->id !== $admin->id)
                                            <div class="d-flex justify-content-center align-items-center gap-2">
                                                <form action="{{ route('admin.admin_management.destroy', $admin->id) }}" method="POST"
                                                    onsubmit="return confirm('Are you sure you want to delete this admin?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="action-btn delete-btn" title="Delete">
                                                        <i class="fas fa-trash-alt"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        @else
                                            <em class="text-muted small">You</em>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    {{-- FontAwesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

    <style>
        /* ===== Modern Dashboard Theme (Same as FAQ Page) ===== */
        .text-gray-700 {
            color: #374151 !important;
        }

        .text-gray-800 {
            color: #1f2937 !important;
        }

        .bg-indigo {
            background-color: #4f46e5 !important;
        }

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

        .table thead th {
            font-weight: 600;
            vertical-align: middle;
            padding: 0.85rem 1rem;
            font-size: 0.9rem;
        }

        .table tbody td {
            font-size: 0.9rem;
            color: #374151;
            padding: 0.85rem 1rem;
        }

        .table-hover tbody tr:hover {
            background-color: #f9fafb;
        }

        .badge {
            font-size: 0.75rem;
            padding: 0.4rem 0.6rem;
            border-radius: 0.5rem;
        }

        /* ===== Action Buttons (Copied from FAQ Page) ===== */
        .action-btn {
            width: 38px;
            height: 38px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            border: none;
            font-size: 16px;
            transition: all 0.2s ease-in-out;
            color: #fff !important;
        }

        .edit-btn {
            background-color: #facc15;
        }

        .edit-btn:hover {
            background-color: #eab308;
        }

        .delete-btn {
            background-color: #ef4444;
        }

        .delete-btn:hover {
            background-color: #dc2626;
        }

        .alert {
            font-size: 0.9rem;
        }
    </style>
@endsection
