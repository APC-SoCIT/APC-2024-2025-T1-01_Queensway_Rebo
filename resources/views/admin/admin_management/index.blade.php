@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Admin Management</h2>

    <!-- Success Message -->
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <!-- Error Handling -->
    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0 ps-3">
                @foreach($errors->all() as $error)
                    <li class="small">{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <!-- Add Admin Button -->
    <a href="{{ route('admin.admin_management.create') }}" class="btn btn-primary mb-4">+ Add Admin</a>

    <!-- Admins Table -->
    <h4 class="mb-3">Existing Admins</h4>
    <div class="table-responsive">
        <table class="table table-bordered table-striped small">
            <thead class="table-dark">
                <tr>
                    <th>Full Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Created At</th>
                    <th class="text-center">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach($admins as $admin)
                    <tr>
                        <td>{{ $admin->first_name }} {{ $admin->last_name }}</td>
                        <td>{{ $admin->email }}</td>
                        <td>{{ ucfirst($admin->role) }}</td>
                        <td>{{ $admin->created_at->format('M d, Y') }}</td>
                        <td class="text-center">
                            @if(auth('admin')->user()->id !== $admin->id)
                                <form action="{{ route('admin.admin_management.destroy', $admin->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this admin?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="btn btn-sm btn-danger">Delete</button>
                                </form>
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

<!-- Toggle Script -->
<script>
    function toggleForm() {
        const form = document.getElementById('admin-form');
        form.classList.toggle('d-none');
    }
</script>
@endsection
