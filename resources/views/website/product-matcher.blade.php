@extends('layouts.website')

@section('content')
<div class="container">
    <h2 class="mb-4">Search Products</h2>

    <!-- Text Search -->
    <form method="POST" action="{{ route('search.text') }}" class="mb-4">
        @csrf
        <div class="input-group">
            <input type="text" name="query" class="form-control" placeholder="Search for a product..." required>
            <button class="btn btn-primary">Search</button>
        </div>
    </form>

    <hr>

    <!-- Image Search -->
    <form method="POST" action="{{ route('search.image') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label class="form-label">Upload an Image</label>
            <input type="file" name="image" class="form-control" required>
        </div>
        <button class="btn btn-success">Search by Image</button>
    </form>
</div>
@endsection
