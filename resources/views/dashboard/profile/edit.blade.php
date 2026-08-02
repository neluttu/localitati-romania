@extends('layouts.dashboard')

@section('content')
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Profilul meu</h1>
            <p class="text-gray-500 mt-1">Gestionează informațiile personale și avatarul</p>
        </div>
    </div>

    {{-- Alerts --}}
    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="grid lg:grid-cols-3 gap-6">
        {{-- Avatar Card --}}
        <div class="lg:col-span-1">
            <div class="bg-white rounded-2xl border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Avatar</h2>

                <div class="flex flex-col items-center">
                    <div class="w-32 h-32 rounded-2xl overflow-hidden bg-gray-100 relative group">
                        <img src="{{ $profile->avatar_url }}" alt="Avatar" class="w-full h-full object-cover">
                        @if ($profile->avatar)
                            <form method="post" action="{{ route('dashboard.profile.avatar.delete') }}"
                                class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                @method('DELETE')
                                @csrf
                                <button type="submit" class="p-3 bg-white/90 hover:bg-white rounded-xl text-red-600 transition-colors">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </div>

                    <form action="{{ route('dashboard.profile.update') }}" method="POST" enctype="multipart/form-data" class="w-full mt-4">
                        @csrf
                        @method('PUT')
                        <input type="hidden" name="first_name" value="{{ old('first_name', $profile->first_name ?? '') }}">
                        <input type="hidden" name="last_name" value="{{ old('last_name', $profile->last_name ?? '') }}">
                        <input type="hidden" name="phone" value="{{ old('phone', $profile->phone ?? '') }}">

                        <label class="block">
                            <span class="sr-only">Alege avatar</span>
                            <input type="file" name="avatar" accept="image/*" onchange="this.form.submit()"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-medium file:bg-purple-50 file:text-purple-600 hover:file:bg-purple-100 cursor-pointer">
                        </label>
                    </form>

                    <p class="text-xs text-gray-400 mt-3 text-center">Recomandăm o imagine de cel puțin 200x200 pixeli</p>
                </div>
            </div>

            {{-- Account Info Card --}}
            <div class="bg-white rounded-2xl border border-gray-100 p-6 mt-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Cont</h2>

                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-500">Email</p>
                        <p class="font-medium text-gray-900">{{ $user->email }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Membru din</p>
                        <p class="font-medium text-gray-900">{{ $user->created_at->format('d F Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Site-uri înregistrate</p>
                        <p class="font-medium text-gray-900">{{ $user->sites()->count() }} / 28</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- Profile Form Card --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 p-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-6">Informații personale</h2>

                <form action="{{ route('dashboard.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid sm:grid-cols-2 gap-6">
                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700 mb-2">Prenume</label>
                            <input type="text" name="first_name" id="first_name"
                                value="{{ old('first_name', $profile->first_name ?? '') }}"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-colors"
                                placeholder="Ex: Ion">
                            @error('first_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700 mb-2">Nume</label>
                            <input type="text" name="last_name" id="last_name"
                                value="{{ old('last_name', $profile->last_name ?? '') }}"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-colors"
                                placeholder="Ex: Popescu">
                            @error('last_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="sm:col-span-2">
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Adresă email</label>
                            <input type="email" id="email" value="{{ $user->email }}" disabled
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-gray-500 cursor-not-allowed">
                            <p class="mt-1 text-xs text-gray-400">Adresa de email nu poate fi modificată</p>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Telefon</label>
                            <input type="text" name="phone" id="phone"
                                value="{{ old('phone', $profile->phone ?? '') }}"
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:border-purple-500 focus:ring-2 focus:ring-purple-500/20 transition-colors"
                                placeholder="Ex: 0712 345 678">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-purple-500 hover:bg-purple-600 text-white font-medium rounded-xl transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                            </svg>
                            Salvează modificările
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Danger zone --}}
        <div class="mt-8 rounded-2xl border border-red-200 bg-red-50/40 p-6">
            <h2 class="font-semibold text-gray-900">Ștergerea contului</h2>
            <p class="text-sm text-gray-600 mt-1 max-w-2xl">
                Contul și site-urile tale sunt dezactivate imediat, iar tokenurile încetează să funcționeze.
                Datele se șterg definitiv după 30 de zile.
            </p>
            <a href="{{ route('dashboard.account.delete') }}"
                class="inline-flex items-center gap-2 mt-4 px-5 py-2.5 border border-red-300 text-red-700 font-medium rounded-xl hover:bg-red-100 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
                Vreau să îmi șterg contul
            </a>
        </div>
    </div>
@endsection
