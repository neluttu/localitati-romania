@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto p-8">

        <h2 class="text-2xl font-bold mb-4">Forgot Password</h2>

        @if (session('success'))
            <div class="p-3 bg-green-100 text-green-800 mb-4">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="p-3 bg-red-100 text-red-800 mb-4">
                @foreach ($errors->all() as $e)
                    <p>{{ $e }}</p>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <label class="block font-semibold mb-1">Email</label>
            <input type="email" name="email" class="w-full border p-2 rounded mb-4" required>

            <button class="w-full bg-blue-600 text-white p-2 rounded">
                Send Password Reset Link
            </button>
        </form>

    </div>
@endsection
