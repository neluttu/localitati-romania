@extends('layouts.admin')

@section('content')
    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('admin.users.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Înapoi la utilizatori
        </a>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- User Info Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $user->email }}</h1>
                <p class="text-gray-500 mt-1">Înregistrat: {{ $user->created_at->format('d.m.Y H:i') }}</p>
            </div>
            <span class="px-3 py-1.5 text-sm font-medium rounded-full
                @if ($user->role->value === 'admin') bg-purple-100 text-purple-700
                @else bg-gray-100 text-gray-700 @endif">
                {{ $user->role->value }}
            </span>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="text-2xl font-bold text-gray-900">{{ $sites->count() }}</div>
                <div class="text-gray-500 text-sm">Site-uri</div>
            </div>
            <div class="bg-purple-50 rounded-xl p-4">
                <div class="text-2xl font-bold text-purple-600">{{ number_format($totalApiCalls) }}</div>
                <div class="text-gray-500 text-sm">Total API Calls</div>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="text-2xl font-bold text-gray-900">{{ $user->login_count ?? 0 }}</div>
                <div class="text-gray-500 text-sm">Login-uri</div>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="text-sm font-medium text-gray-900">{{ $user->last_login_at?->format('d.m.Y H:i') ?? '-' }}</div>
                <div class="text-gray-500 text-sm">Ultimul login</div>
            </div>
        </div>
    </div>

    {{-- User Sites --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="text-lg font-semibold text-gray-900">Site-urile utilizatorului</h2>
        </div>

        @if ($sites->isEmpty())
            <div class="p-8 text-center text-gray-500">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                </svg>
                <p>Acest utilizator nu are site-uri înregistrate.</p>
            </div>
        @else
            <table class="min-w-full divide-y divide-gray-100">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nume</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Domeniu</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">API Calls</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Acțiuni</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach ($sites as $site)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">
                                {{ $site->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                {{ $site->domain ?? '-' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-gray-600">
                                {{ number_format($site->api_logs_count) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if ($site->is_active)
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full bg-green-100 text-green-700">
                                        <span class="w-1.5 h-1.5 bg-green-500 rounded-full"></span>
                                        Activ
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-medium rounded-full bg-red-100 text-red-700">
                                        <span class="w-1.5 h-1.5 bg-red-500 rounded-full"></span>
                                        Inactiv
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right">
                                <a href="{{ route('admin.sites.show', $site) }}" class="text-purple-600 hover:text-purple-700 mr-3">
                                    Detalii
                                </a>
                                <form action="{{ route('admin.sites.toggle', $site) }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit"
                                        class="text-sm font-medium {{ $site->is_active ? 'text-red-600 hover:text-red-700' : 'text-green-600 hover:text-green-700' }}">
                                        {{ $site->is_active ? 'Dezactivează' : 'Activează' }}
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>
@endsection
