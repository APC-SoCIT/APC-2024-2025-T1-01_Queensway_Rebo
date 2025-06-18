@extends('layouts.admin')

@section('content')
    <div class="container">
        <h2 class="mb-4">Edit FAQ</h2>

        <form method="POST" action="{{ route('admin.faqs.update', $faq->id) }}">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="question" class="form-label">Question</label>
                <input type="text" class="form-control" id="question" name="question" value="{{ $faq->question }}" required>
            </div>

            <div class="mb-3">
                <label for="answer" class="form-label">Answer</label>
                <textarea class="form-control" id="answer" name="answer" rows="4" required>{{ $faq->answer }}</textarea>
            </div>

            <div class="mb-3">
                <label for="keywords" class="form-label">Keywords (comma-separated)</label>
                <input type="text" class="form-control" id="keywords" name="keywords" value="{{ $faq->keywords }}" required>
            </div>

            <button type="submit" class="btn btn-primary">Update FAQ</button>
        </form>
    </div>
@endsection