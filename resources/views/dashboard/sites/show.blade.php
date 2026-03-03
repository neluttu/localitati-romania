@extends('layouts.dashboard')

@section('content')
    <div class="w-full">
        <div class="mb-8">
            <a href="{{ route('dashboard.sites.index') }}" class="text-purple-600 hover:text-purple-800">
                &larr; Înapoi la site-uri
            </a>
        </div>

        @if (session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-semibold text-gray-800">{{ $site->name }}</h1>
                    @if ($site->domain)
                        <p class="text-gray-500">{{ $site->domain }}</p>
                    @endif
                </div>
                @if ($site->is_active)
                    <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-green-100 text-green-800">
                        Activ
                    </span>
                @else
                    <span class="inline-flex px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800">
                        Inactiv
                    </span>
                @endif
            </div>

            {{-- Token Section --}}
            <div class="bg-gray-50 rounded-lg p-4 mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">Token API</label>
                <div class="flex items-center gap-3">
                    <input type="text" readonly value="{{ $site->token }}" id="token-field"
                        class="flex-1 px-4 py-2 bg-white border border-gray-300 rounded-lg font-mono text-sm">
                    <button type="button" onclick="copyToken()"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors">
                        Copiază
                    </button>
                </div>
                <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-800 font-medium mb-1">Cum să folosești token-ul:</p>
                    <code class="text-xs text-blue-700 block">X-Site-Token: {{ $site->token }}</code>
                    <p class="text-xs text-blue-600 mt-2">Token-ul funcționează doar pentru domeniul <strong>{{ $site->domain }}</strong></p>
                </div>
            </div>

            <div class="flex gap-3">
                <form action="{{ route('dashboard.sites.regenerate', $site) }}" method="POST" class="inline"
                    onsubmit="return confirm('Ești sigur? Token-ul vechi va deveni invalid.')">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg transition-colors">
                        Regenerează token
                    </button>
                </form>

                <form action="{{ route('dashboard.sites.destroy', $site) }}" method="POST" class="inline"
                    onsubmit="return confirm('Ești sigur că vrei să ștergi acest site? Toate datele vor fi pierdute.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-lg transition-colors">
                        Șterge site
                    </button>
                </form>
            </div>
        </div>

        {{-- Statistics --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-3xl font-bold text-gray-800">{{ number_format($totalCalls) }}</div>
                <div class="text-gray-500">Total API Calls</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-3xl font-bold text-purple-600">{{ number_format($callsToday) }}</div>
                <div class="text-gray-500">Calls astăzi</div>
            </div>
            <div class="bg-white rounded-lg shadow p-6">
                <div class="text-3xl font-bold text-gray-800">{{ $site->created_at->format('d.m.Y') }}</div>
                <div class="text-gray-500">Creat la</div>
            </div>
        </div>

        {{-- Calls by Endpoint --}}
        @if ($callsByEndpoint->isNotEmpty())
            <div class="bg-white rounded-lg shadow p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-800 mb-4">Top Endpoint-uri</h2>
                <div class="space-y-3">
                    @foreach ($callsByEndpoint as $endpoint)
                        <div class="flex items-center justify-between">
                            <code class="text-sm bg-gray-100 px-2 py-1 rounded">{{ $endpoint->endpoint }}</code>
                            <span class="text-gray-600">{{ number_format($endpoint->count) }} calls</span>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

        {{-- Recent Logs --}}
        @if ($recentLogs->isNotEmpty())
            <div class="bg-white rounded-lg shadow overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-lg font-semibold text-gray-800">Ultimele request-uri</h2>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Endpoint</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Method</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Timp</th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Data</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach ($recentLogs as $log)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <code class="text-sm">{{ $log->endpoint }}</code>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded
                                            @if ($log->method === 'GET') bg-blue-100 text-blue-800
                                            @elseif($log->method === 'POST') bg-green-100 text-green-800
                                            @else bg-gray-100 text-gray-800 @endif">
                                            {{ $log->method }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span
                                            class="px-2 py-1 text-xs font-medium rounded
                                            @if ($log->status_code < 300) bg-green-100 text-green-800
                                            @elseif($log->status_code < 400) bg-yellow-100 text-yellow-800
                                            @else bg-red-100 text-red-800 @endif">
                                            {{ $log->status_code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                        {{ $log->response_time_ms }}ms
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                                        {{ $log->created_at->format('d.m.Y H:i:s') }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif

    <script>
        function copyToken() {
            const tokenField = document.getElementById('token-field');
            tokenField.select();
            document.execCommand('copy');
            alert('Token copiat!');
        }
    </script>
@endsection
