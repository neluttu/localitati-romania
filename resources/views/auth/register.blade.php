@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center flex-col mt-16 w-full mx-auto max-w-7xl">
        <h1>Autentificare în cont</h1>

        <div class="w-full max-w-md p-8">


            {{-- Succes message --}}
            @if (session('success'))
                <div class="mb-4 p-3 bg-green-100 border border-green-300 rounded text-green-800">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Error list --}}
            @if ($errors->any())
                <div class="mb-4 p-3 bg-red-100 border border-red-300 rounded text-red-800">
                    <ul class="list-disc pl-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold mb-1" for="first_name">Prenume</label>
                    <input type="text" name="first_name" value="{{ old('first_name') }}" id="first_name"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-purple-500" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1" for="last_name">Nume</label>
                    <input type="text" name="last_name" value="{{ old('last_name') }}" id="last_name"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-purple-500" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1" for="email">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" id="email"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-purple-500" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1" for="password">Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-purple-500" required>
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1" for="password_confirmation">Confirm Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full border border-gray-300 rounded-lg p-2 focus:ring-2 focus:ring-purple-500" required>
                </div>

                <div>
                    <label for="terms" class="flex items-start gap-3 cursor-pointer">
                        <input type="checkbox" name="terms" id="terms" value="1" @checked(old('terms'))
                            class="mt-1 w-4 h-4 shrink-0 rounded border-gray-300 text-purple-600 focus:ring-purple-500">
                        <span class="text-sm text-gray-600 leading-snug">
                            Am citit și accept
                            <a href="{{ url('/termeni-si-conditii') }}" target="_blank" class="text-purple-600 hover:underline">termenii și condițiile</a>,
                            <a href="{{ url('/politica-de-confidentialitate') }}" target="_blank" class="text-purple-600 hover:underline">politica de confidențialitate</a>
                            și <a href="{{ url('/politica-de-cookies') }}" target="_blank" class="text-purple-600 hover:underline">politica de cookies</a>.
                        </span>
                    </label>
                    @error('terms')
                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-purple-500 text-white py-2 rounded-lg hover:bg-purple-600">
                    Înregistrează cont
                </button>

                <p class="text-center text-sm text-gray-600 mt-4">
                    Ai deja cont?
                    <a href="/login" class="text-blue-600 hover:underline">Autentifică-te</a>
                </p>
            </form>
            <div class="mt-3 space-y-3">
                <x-auth.social-button provider="google" context="register" />
                <x-auth.social-button provider="facebook" context="register" />
            </div>
        </div>
    </div>
@endsection
