@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Verify your email</h2>
        <p>We sent you an email. Please click the link to verify.</p>
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Resend Verification Email</button>
        </form>
    </div>
@endsection
