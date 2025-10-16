@extends('layouts.admin')

@section('content')
<div class="container-fluid px-4 py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-semibold text-gray-800 mb-0">❓ Edit FAQ</h2>
        <a href="{{ route('admin.faqs.index') }}" class="btn btn-outline-indigo d-flex align-items-center gap-2">
            <i class="fas fa-arrow-left"></i> <span>Back to FAQs</span>
        </a>
    </div>

    <div class="card shadow-sm border-0 rounded-3">
        <div class="card-body p-4">
            <form method="POST" action="{{ route('admin.faqs.update', $faq->id) }}">
                @csrf
                @method('PUT')

                <div class="row g-4">
                    <!-- Question -->
                    <div class="col-12">
                        <label for="question" class="form-label fw-semibold text-gray-700">Question</label>
                        <input 
                            type="text" 
                            class="form-control shadow-sm" 
                            id="question" 
                            name="question" 
                            value="{{ old('question', $faq->question) }}" 
                            placeholder="Enter FAQ question..." 
                            required>
                    </div>

                    <!-- Answer -->
                    <div class="col-12">
                        <label for="answer" class="form-label fw-semibold text-gray-700">Answer</label>
                        <textarea 
                            class="form-control shadow-sm" 
                            id="answer" 
                            name="answer" 
                            rows="4" 
                            placeholder="Enter detailed answer..." 
                            required>{{ old('answer', $faq->answer) }}</textarea>
                    </div>

                    <!-- Keywords -->
                    <div class="col-12">
                        <label for="keywords" class="form-label fw-semibold text-gray-700">Keywords (comma-separated)</label>
                        <input 
                            type="text" 
                            class="form-control shadow-sm" 
                            id="keywords" 
                            name="keywords" 
                            value="{{ old('keywords', $faq->keywords) }}" 
                            placeholder="e.g. shipping, returns, refund"
                            required>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <button type="submit" class="btn btn-indigo px-4 d-flex align-items-center gap-2">
                        <i class="fas fa-save"></i> <span>Update FAQ</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- FontAwesome --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css"/>

<style>
/* ===== Modern Theme (matches Products Page) ===== */
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
</style>
@endsection
