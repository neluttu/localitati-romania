@extends('layouts.app')

@section('content')
    <div class="max-w-md mx-auto bg-white p-8 shadow rounded">

        <h2 class="text-2xl font-bold mb-4 text-center">Verify Your Email</h2>

        <p class="text-gray-700 mb-4">
            Before accessing your account, please verify your email address.
            We have sent you a verification link.
        </p>

        @if (session('success'))
            <div class="p-3 bg-green-100 border border-green-200 rounded mb-4 text-green-800">
                {{ session('success') }}
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="text-center">
            @csrf
            <button class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                Resend Verification Email
            </button>
        </form>

    </div>
@endsection
