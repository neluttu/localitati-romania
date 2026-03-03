@extends('layouts.dashboard')

@section('content')
    <div class="w-full">
        <h1 class="text-2xl font-semibold text-gray-800 mb-8">Statisticile mele</h1>

        {{-- Overview Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-3xl font-bold text-gray-800">{{ number_format($totalCalls) }}</div>
                <div class="text-gray-500">Total API Calls</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-3xl font-bold text-purple-600">{{ number_format($callsToday) }}</div>
                <div class="text-gray-500">Calls astăzi</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-3xl font-bold text-gray-800">{{ $callsBySite->count() }}</div>
                <div class="text-gray-500">Site-uri active</div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Calls by Site --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Calls per Site</h2>
                @if ($callsBySite->isNotEmpty())
                    <div class="space-y-3">
                        @foreach ($callsBySite as $site)
                            <div class="flex items-center justify-between">
                                <a href="{{ route('dashboard.sites.show', $site) }}"
                                    class="text-purple-600 hover:text-purple-800">
                                    {{ $site->name }}
                                </a>
                                <span class="text-gray-600">{{ number_format($site->api_logs_count) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Niciun site înregistrat.</p>
                @endif
            </div>

            {{-- Calls by Endpoint --}}
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Top Endpoint-uri</h2>
                @if ($callsByEndpoint->isNotEmpty())
                    <div class="space-y-3">
                        @foreach ($callsByEndpoint as $endpoint)
                            <div class="flex items-center justify-between">
                                <code class="text-sm bg-gray-100 px-2 py-1 rounded">{{ $endpoint->endpoint }}</code>
                                <span class="text-gray-600">{{ number_format($endpoint->count) }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-gray-500">Niciun call înregistrat.</p>
                @endif
            </div>
        </div>

        {{-- Calls Last 30 Days Chart --}}
        @if ($callsLast30Days->isNotEmpty())
            <div class="bg-white rounded-lg shadow p-6 mt-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Calls în ultimele 30 de zile</h2>
                <div class="h-64 flex items-end gap-1">
                    @php
                        $maxCalls = $callsLast30Days->max('count') ?: 1;
                    @endphp
                    @foreach ($callsLast30Days as $day)
                        <div class="flex-1 bg-purple-500 rounded-t transition-all hover:bg-purple-600"
                            style="height: {{ ($day->count / $maxCalls) * 100 }}%"
                            title="{{ $day->date }}: {{ number_format($day->count) }} calls">
                        </div>
                    @endforeach
                </div>
                <div class="flex justify-between mt-2 text-xs text-gray-500">
                    <span>{{ $callsLast30Days->first()->date }}</span>
                    <span>{{ $callsLast30Days->last()->date }}</span>
                </div>
            </div>
        @endif
@endsection
