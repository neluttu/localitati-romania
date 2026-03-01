@extends('layouts.app')

@section('content')
    <div class="flex items-center justify-center flex-col mt-16 w-full mx-auto max-w-7xl">
        <h1>Autentificare în cont</h1>
        <div class="w-full max-w-md p-8">

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-sm font-semibold mb-1" for="email">Adresă email</label>
                    <x-form-input name="email" type="text" id="email" :value="old('email')" />
                </div>

                <div>
                    <label class="block text-sm font-semibold mb-1" for="password">Parolă cont</label>
                    <x-form-input name="password" id="password" type="password" required />
                </div>

                <div class="flex items-center">
                    <input type="checkbox" id="remember" name="remember" class="mr-2">
                    <label for="remember" class="text-sm">Ține-mă minte</label>
                </div>

                <button class="w-full bg-purple-500 text-white py-2 rounded-lg hover:bg-purple-600">
                    Accesează contul
                </button>

                <div class="flex items-center justify-between gap-4 font-light">
                    <a href="{{ route('register') }}">Înregistrare cont nou</a>
                    <a href="{{ route('password.request') }}">Am uitat parola</a>
                </div>
            </form>
            <div class="mt-3 space-y-3">
                <x-auth.social-button provider="google" context="login" />
                <x-auth.social-button provider="facebook" context="login" />
            </div>


        </div>
    </div>
@endsection
