@extends('layouts.dashboard')

@section('content')
    <div class="max-w-xl">
        <div class="mb-8">
            <a href="{{ route('dashboard.sites.index') }}" class="text-purple-600 hover:text-purple-800">
                &larr; Înapoi la site-uri
            </a>
        </div>

        <h1 class="text-2xl font-semibold text-gray-800 mb-6">Adaugă un site nou</h1>

        <form action="{{ route('dashboard.sites.store') }}" method="POST" class="bg-white rounded-lg shadow p-6 space-y-6">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
                    Numele site-ului *
                </label>
                <input type="text" name="name" id="name" value="{{ old('name') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('name') border-red-500 @enderror"
                    placeholder="Ex: Magazinul meu online">
                @error('name')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="domain" class="block text-sm font-medium text-gray-700 mb-1">
                    Domeniu *
                </label>
                <input type="text" name="domain" id="domain" value="{{ old('domain') }}" required
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-purple-500 focus:border-purple-500 @error('domain') border-red-500 @enderror"
                    placeholder="Ex: example.com sau *.example.com">
                @error('domain')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
                <p class="mt-1 text-sm text-gray-500">Folosește <code class="bg-gray-100 px-1 rounded">*.domeniu.ro</code> pentru wildcard (toate subdomeniile).</p>
            </div>

            <div class="pt-4">
                <button type="submit"
                    class="w-full px-4 py-2 bg-purple-500 hover:bg-purple-600 text-white font-medium rounded-lg transition-colors">
                    Creează site și generează token
                </button>
            </div>
        </form>

        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
            <h3 class="font-medium text-blue-800 mb-2">Cum funcționează?</h3>
            <ul class="text-sm text-blue-700 space-y-1">
                <li>• Fiecare site primește un token API unic</li>
                <li>• Trimite token-ul în header-ul <code class="bg-blue-100 px-1 rounded">X-Site-Token</code></li>
                <li>• Token-ul funcționează de pe orice domeniu, din backend și de pe <code class="bg-blue-100 px-1 rounded">localhost</code></li>
                <li>• Domeniul de mai sus e o etichetă: separă statisticile pe site-urile tale</li>
                <li>• Limită: 120 de request-uri pe minut</li>
            </ul>
        </div>
    </div>
@endsection
