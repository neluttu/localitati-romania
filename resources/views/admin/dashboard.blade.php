@extends('layouts.admin')

@section('content')
    {{-- Header --}}
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900">Dashboard</h1>
        <p class="text-gray-500 mt-1">Prezentare generală a platformei</p>
    </div>

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ number_format($totalUsers) }}</div>
            <div class="text-sm text-gray-500 mt-1">Utilizatori</div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ number_format($totalSites) }}</div>
            <div class="text-sm text-gray-500 mt-1">Site-uri</div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ number_format($totalApiCalls) }}</div>
            <div class="text-sm text-gray-500 mt-1">Total API Calls</div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-100 p-6 hover:shadow-lg transition-shadow">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6" />
                    </svg>
                </div>
            </div>
            <div class="text-3xl font-bold text-gray-900">{{ number_format($callsToday) }}</div>
            <div class="text-sm text-gray-500 mt-1">Calls astăzi</div>
        </div>
    </div>

    {{-- Chart --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-8">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">API Calls - Ultimele 7 zile</h2>
        @if ($callsLast7Days->isNotEmpty())
            @php
                $maxCalls = $callsLast7Days->max('count') ?: 1;
            @endphp
            <div class="h-64 flex items-end gap-2">
                @foreach ($callsLast7Days as $day)
                    <div class="flex-1 flex flex-col items-center">
                        <div class="text-sm font-semibold text-gray-700 mb-2">
                            {{ number_format($day->count) }}
                        </div>
                        <div class="w-full bg-gradient-to-t from-purple-600 to-purple-400 rounded-lg transition-all hover:from-purple-700 hover:to-purple-500 cursor-pointer"
                            style="height: {{ max(($day->count / $maxCalls) * 160, 8) }}px">
                        </div>
                        <div class="text-xs font-medium text-gray-500 mt-3">
                            {{ \Carbon\Carbon::parse($day->date)->locale('ro')->isoFormat('ddd') }}
                        </div>
                        <div class="text-xs text-gray-400">
                            {{ \Carbon\Carbon::parse($day->date)->format('d.m') }}
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="h-48 flex items-center justify-center text-gray-400">
                <div class="text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <p>Nu există date pentru ultimele 7 zile</p>
                </div>
            </div>
        @endif
    </div>

    {{-- Two Columns --}}
    <div class="grid lg:grid-cols-2 gap-6">
        {{-- Recent Users --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Utilizatori Recenți</h2>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-purple-600 hover:text-purple-700">
                    Vezi toți &rarr;
                </a>
            </div>
            <div class="space-y-4">
                @foreach ($recentUsers as $user)
                    <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-gray-100 rounded-full flex items-center justify-center text-gray-500 font-medium">
                                {{ strtoupper(substr($user->email, 0, 1)) }}
                            </div>
                            <div>
                                <a href="{{ route('admin.users.show', $user) }}"
                                    class="font-medium text-gray-900 hover:text-purple-600">
                                    {{ $user->email }}
                                </a>
                                <div class="text-sm text-gray-500">{{ $user->sites_count }} site-uri</div>
                            </div>
                        </div>
                        <span class="text-sm text-gray-400">{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Top Sites --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <div class="flex items-center justify-between mb-6">
                <h2 class="text-lg font-semibold text-gray-900">Top Site-uri</h2>
                <a href="{{ route('admin.sites.index') }}" class="text-sm text-purple-600 hover:text-purple-700">
                    Vezi toate &rarr;
                </a>
            </div>
            <div class="space-y-4">
                @foreach ($topSites as $index => $site)
                    <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600 font-bold text-sm">
                                {{ $index + 1 }}
                            </div>
                            <div>
                                <a href="{{ route('admin.sites.show', $site) }}"
                                    class="font-medium text-gray-900 hover:text-purple-600">
                                    {{ $site->name }}
                                </a>
                                <div class="text-sm text-gray-500">{{ $site->domain ?? '-' }}</div>
                            </div>
                        </div>
                        <span class="font-semibold text-gray-900">{{ number_format($site->api_logs_count) }}</span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endsection
