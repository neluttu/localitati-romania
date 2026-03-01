@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto bg-white p-8 shadow rounded">

        <h2 class="text-2xl font-bold mb-4">Reset Password</h2>

        @if ($errors->any())
            <div class="p-3 bg-red-100 text-red-800 mb-4">
                @foreach ($errors->all() as $e)
                    <p>{{ $e }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.update') }}">
            @csrf

            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email }}">

            <label class="block font-semibold mb-1">New Password</label>
            <input type="password" name="password" class="w-full border p-2 rounded mb-4" required>

            <label class="block font-semibold mb-1">Confirm Password</label>
            <input type="password" name="password_confirmation" class="w-full border p-2 rounded mb-4" required>

            <button class="w-full bg-blue-600 text-white p-2 rounded">
                Reset Password
            </button>
        </form>

    </div>
@endsection
