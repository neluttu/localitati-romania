@extends('layouts.dashboard')

@section('content')
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Site-urile mele</h1>
            <p class="text-gray-500 mt-1">Gestionează site-urile și token-urile API</p>
        </div>
        <a href="{{ route('dashboard.sites.create') }}"
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-500 hover:bg-purple-600 text-white font-medium rounded-xl transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
            </svg>
            Adaugă site
        </a>
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

    {{-- Sites Count --}}
    @if ($sites->count() > 0)
        <div class="mb-6 text-sm text-gray-500">
            {{ $sites->count() }} / 28 site-uri utilizate
        </div>
    @endif

    @if ($sites->isEmpty())
        {{-- Empty State --}}
        <div class="text-center py-16 bg-white rounded-2xl border border-gray-100">
            <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Niciun site înregistrat</h3>
            <p class="text-gray-500 mb-6 max-w-md mx-auto">Creează primul tău site pentru a primi un token API și a începe să accesezi datele.</p>
            <a href="{{ route('dashboard.sites.create') }}"
                class="inline-flex items-center gap-2 px-6 py-3 bg-purple-500 hover:bg-purple-600 text-white font-medium rounded-xl transition-colors">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Creează primul site
            </a>
        </div>
    @else
        {{-- Sites Grid --}}
        <div class="grid gap-4">
            @foreach ($sites as $site)
                <div class="bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-lg transition-shadow">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center overflow-hidden">
                                @if($site->domain && !str_starts_with($site->domain, '*.'))
                                    <img src="https://www.google.com/s2/favicons?domain={{ $site->domain }}&sz=64"
                                        alt="{{ $site->name }}"
                                        class="w-8 h-8"
                                        onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';">
                                    <div class="w-full h-full bg-gradient-to-br from-purple-500 to-blue-500 items-center justify-center text-white font-bold text-lg hidden">
                                        {{ strtoupper(substr($site->name, 0, 1)) }}
                                    </div>
                                @else
                                    <div class="w-full h-full bg-gradient-to-br from-purple-500 to-blue-500 flex items-center justify-center text-white font-bold text-lg">
                                        {{ strtoupper(substr($site->name, 0, 1)) }}
                                    </div>
                                @endif
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 text-lg">{{ $site->name }}</h3>
                                <p class="text-gray-500 text-sm">{{ $site->domain ?? 'Fără domeniu' }}</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-6">
                            <div class="text-center">
                                <div class="text-2xl font-bold text-gray-900">{{ number_format($site->api_logs_count) }}</div>
                                <div class="text-xs text-gray-500">API Calls</div>
                            </div>
                            <div>
                                @if ($site->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-full bg-green-100 text-green-700">
                                        <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                        Activ
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1.5 text-sm font-medium rounded-full bg-red-100 text-red-700">
                                        <span class="w-2 h-2 bg-red-500 rounded-full"></span>
                                        Inactiv
                                    </span>
                                @endif
                            </div>
                            <a href="{{ route('dashboard.sites.show', $site) }}"
                                class="inline-flex items-center gap-1 px-4 py-2 text-purple-600 hover:bg-purple-50 font-medium rounded-xl transition-colors">
                                Detalii
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
@endsection
