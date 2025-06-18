@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2 class="mb-4">Add New FAQ</h2>

        <form method="POST" action="{{ route('admin.faqs.store') }}">
            @csrf

            <div class="mb-3">
                <label for="question" class="form-label">Question</label>
                <input type="text" class="form-control" id="question" name="question" required>
            </div>

            <div class="mb-3">
                <label for="answer" class="form-label">Answer</label>
                <textarea class="form-control" id="answer" name="answer" rows="4" required></textarea>
            </div>

            <div class="mb-3">
                <label for="keywords" class="form-label">Keywords (comma-separated)</label>
                <input type="text" class="form-control" id="keywords" name="keywords" required>
            </div>

            <button type="submit" class="btn btn-success">Save FAQ</button>
        </form>
    </div>
@endsection