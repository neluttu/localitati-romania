@extends('layouts.admin')

@section('content')
    {{-- Back Link --}}
    <div class="mb-6">
        <a href="{{ route('admin.sites.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Înapoi la site-uri
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

    {{-- Site Info Card --}}
    <div class="bg-white rounded-2xl border border-gray-100 p-6 mb-6">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">{{ $site->name }}</h1>
                @if ($site->domain)
                    <p class="text-gray-500 mt-1">{{ $site->domain }}</p>
                @endif
                <p class="text-sm text-gray-400 mt-1">
                    Proprietar:
                    <a href="{{ route('admin.users.show', $site->user) }}" class="text-purple-600 hover:text-purple-700">
                        {{ $site->user->email }}
                    </a>
                </p>
            </div>
            <div class="flex items-center gap-3">
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
                <form action="{{ route('admin.sites.toggle', $site) }}" method="POST">
                    @csrf
                    <button type="submit"
                        class="px-4 py-2 {{ $site->is_active ? 'bg-red-500 hover:bg-red-600' : 'bg-green-500 hover:bg-green-600' }} text-white font-medium rounded-xl transition-colors">
                        {{ $site->is_active ? 'Dezactivează' : 'Activează' }}
                    </button>
                </form>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-purple-50 rounded-xl p-4">
                <div class="text-2xl font-bold text-purple-600">{{ number_format($site->api_logs_count) }}</div>
                <div class="text-gray-500 text-sm">Total API Calls</div>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="text-lg font-bold text-gray-900">{{ $site->created_at->format('d.m.Y') }}</div>
                <div class="text-gray-500 text-sm">Creat la</div>
            </div>
            <div class="bg-gray-50 rounded-xl p-4">
                <div class="text-lg font-bold text-gray-900">{{ $site->updated_at->format('d.m.Y') }}</div>
                <div class="text-gray-500 text-sm">Ultima actualizare</div>
            </div>
        </div>

        {{-- Token Section --}}
        <div class="bg-gray-50 rounded-xl p-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Token API</label>
            <div class="flex items-center gap-3">
                <input type="text" readonly value="{{ $site->token }}" id="admin-token-field"
                    class="flex-1 px-4 py-2 bg-white border border-gray-300 rounded-lg font-mono text-sm">
                <button type="button" onclick="copyAdminToken()"
                    class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg transition-colors">
                    Copiază
                </button>
            </div>
            <div class="mt-3 p-3 bg-blue-50 rounded-lg">
                <p class="text-sm text-blue-800 font-medium mb-1">Header pentru request:</p>
                <code class="text-xs text-blue-700 block break-all">X-Site-Token: {{ $site->token }}</code>
                @if ($site->domain)
                    <p class="text-xs text-blue-600 mt-2">Domeniu autorizat: <strong>{{ $site->domain }}</strong></p>
                @endif
            </div>
        </div>
    </div>

    <script>
        function copyAdminToken() {
            const f = document.getElementById('admin-token-field');
            f.select();
            document.execCommand('copy');
            alert('Token copiat!');
        }
    </script>

    {{-- Recent Logs --}}
    @if ($recentLogs->isNotEmpty())
        <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h2 class="text-lg font-semibold text-gray-900">Ultimele 50 de request-uri</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-100">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Endpoint</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">IP</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Timp</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Data</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @foreach ($recentLogs as $log)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <code class="text-sm bg-gray-100 px-2 py-1 rounded">{{ $log->endpoint }}</code>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded
                                        @if ($log->method === 'GET') bg-blue-100 text-blue-700
                                        @elseif($log->method === 'POST') bg-green-100 text-green-700
                                        @else bg-gray-100 text-gray-700 @endif">
                                        {{ $log->method }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 py-1 text-xs font-medium rounded
                                        @if ($log->status_code < 300) bg-green-100 text-green-700
                                        @elseif($log->status_code < 400) bg-yellow-100 text-yellow-700
                                        @else bg-red-100 text-red-700 @endif">
                                        {{ $log->status_code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-500 text-sm">
                                    {{ $log->ip }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-gray-600">
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
    @else
        <div class="bg-white rounded-2xl border border-gray-100 p-8 text-center text-gray-500">
            <svg class="w-12 h-12 mx-auto mb-3 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
            </svg>
            <p>Niciun request API înregistrat pentru acest site.</p>
        </div>
    @endif
@endsection
