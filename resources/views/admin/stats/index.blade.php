@extends('layouts.admin')

@section('content')
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Statistici</h1>
            <p class="text-gray-500 mt-1">Statistici globale pentru platformă</p>
        </div>
    </div>

    {{-- Overview Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <div class="text-3xl font-bold text-gray-900">{{ number_format($totalUsers) }}</div>
            <div class="text-gray-500 text-sm mt-1">Utilizatori</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <div class="text-3xl font-bold text-purple-600">{{ number_format($totalSites) }}</div>
            <div class="text-gray-500 text-sm mt-1">Site-uri</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <div class="text-3xl font-bold text-gray-900">{{ number_format($totalApiCalls) }}</div>
            <div class="text-gray-500 text-sm mt-1">Total API Calls</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <div class="text-3xl font-bold text-green-600">{{ number_format($callsToday) }}</div>
            <div class="text-gray-500 text-sm mt-1">Calls astăzi</div>
        </div>
    </div>

    {{-- Additional Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-8">
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <div class="text-2xl font-bold text-gray-900">{{ round($avgResponseTime ?? 0, 2) }}ms</div>
            <div class="text-gray-500 text-sm mt-1">Timp mediu de răspuns</div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <div class="text-2xl font-bold text-gray-900">{{ $totalSites > 0 ? round($totalApiCalls / $totalSites, 1) : 0 }}</div>
            <div class="text-gray-500 text-sm mt-1">Media calls/site</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
        {{-- Top Endpoints --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Top 10 Endpoint-uri</h2>
            @if ($topEndpoints->isNotEmpty())
                <div class="space-y-4">
                    @foreach ($topEndpoints as $endpoint)
                        @php
                            $percentage = $totalApiCalls > 0 ? ($endpoint->count / $totalApiCalls) * 100 : 0;
                        @endphp
                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <code class="text-sm bg-gray-100 px-2 py-1 rounded">{{ $endpoint->endpoint }}</code>
                                <span class="text-gray-600 font-medium">{{ number_format($endpoint->count) }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2">
                                <div class="bg-gradient-to-r from-purple-500 to-purple-400 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Niciun call înregistrat.</p>
            @endif
        </div>

        {{-- Top Sites --}}
        <div class="bg-white rounded-2xl border border-gray-100 p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Top 10 Site-uri</h2>
            @if ($topSites->isNotEmpty())
                <div class="space-y-1">
                    @foreach ($topSites as $index => $site)
                        <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0">
                            <div class="flex items-center gap-3">
                                <div class="w-7 h-7 bg-purple-100 rounded-lg flex items-center justify-center text-purple-600 font-bold text-xs">
                                    {{ $index + 1 }}
                                </div>
                                <a href="{{ route('admin.sites.show', $site) }}" class="text-purple-600 hover:text-purple-700">
                                    {{ $site->name }}
                                </a>
                            </div>
                            <span class="font-semibold text-gray-900">{{ number_format($site->api_logs_count) }}</span>
                        </div>
                    @endforeach
                </div>
            @else
                <p class="text-gray-500 text-center py-4">Niciun site înregistrat.</p>
            @endif
        </div>
    </div>

    {{-- Status Codes Distribution --}}
    @if ($callsByStatusCode->isNotEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Distribuție Status Codes</h2>
            <div class="flex flex-wrap gap-3">
                @foreach ($callsByStatusCode as $statusCode)
                    <div class="px-4 py-3 rounded-xl
                        @if ($statusCode->status_code < 300) bg-green-50
                        @elseif($statusCode->status_code < 400) bg-yellow-50
                        @else bg-red-50 @endif">
                        <div class="text-xl font-bold
                            @if ($statusCode->status_code < 300) text-green-700
                            @elseif($statusCode->status_code < 400) text-yellow-700
                            @else text-red-700 @endif">
                            {{ $statusCode->status_code }}
                        </div>
                        <div class="text-gray-500 text-sm">{{ number_format($statusCode->count) }} calls</div>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    {{-- Calls Last 30 Days Chart --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-6">API Calls - Ultimele 30 de zile</h2>
        @if ($callsLast30Days->isNotEmpty())
            @php
                $maxCalls = $callsLast30Days->max('count') ?: 1;
            @endphp
            <div class="h-64 flex items-end gap-1">
                @foreach ($callsLast30Days as $day)
                    <div class="flex-1 bg-gradient-to-t from-purple-600 to-purple-400 rounded-t-lg transition-all hover:from-purple-700 hover:to-purple-500 relative group cursor-pointer"
                        style="height: {{ max(($day->count / $maxCalls) * 100, 2) }}%">
                        <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-gray-900 text-white text-xs rounded opacity-0 group-hover:opacity-100 whitespace-nowrap z-10">
                            {{ $day->date }}: {{ number_format($day->count) }}
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="flex justify-between mt-3 text-xs text-gray-500">
                <span>{{ $callsLast30Days->first()->date }}</span>
                <span>{{ $callsLast30Days->last()->date }}</span>
            </div>
        @else
            <div class="h-48 flex items-center justify-center text-gray-400">
                <div class="text-center">
                    <svg class="w-12 h-12 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    <p>Nu există date pentru ultimele 30 de zile</p>
                </div>
            </div>
        @endif
    </div>
@endsection
