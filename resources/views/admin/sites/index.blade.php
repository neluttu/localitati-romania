@extends('layouts.admin')

@section('content')
    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Site-uri</h1>
            <p class="text-gray-500 mt-1">Gestionare toate site-urile înregistrate</p>
        </div>
    </div>

    @if (session('success'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
            </svg>
            {{ session('success') }}
        </div>
    @endif

    {{-- Filters --}}
    <form action="{{ route('admin.sites.index') }}" method="GET" class="mb-6">
        <div class="flex gap-3">
            <input type="text" name="search" value="{{ request('search') }}"
                placeholder="Caută după nume, domeniu sau email..."
                class="flex-1 px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
            <select name="status"
                class="px-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-purple-500 focus:border-purple-500">
                <option value="">Toate</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
            </select>
            <button type="submit"
                class="px-6 py-2.5 bg-purple-500 hover:bg-purple-600 text-white font-medium rounded-xl transition-colors">
                Filtrează
            </button>
        </div>
    </form>

    {{-- Sites Table --}}
    <div class="bg-white rounded-2xl border border-gray-100 overflow-hidden">
        <table class="min-w-full divide-y divide-gray-100">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nume</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Proprietar</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">API Calls</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Creat</th>
                    <th class="px-6 py-4 text-right text-xs font-semibold text-gray-600 uppercase tracking-wider">Acțiuni</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach ($sites as $site)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="font-medium text-gray-900">{{ $site->name }}</div>
                            <div class="text-sm text-gray-500">{{ $site->domain ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <a href="{{ route('admin.users.show', $site->user) }}" class="text-purple-600 hover:text-purple-700">
                                {{ $site->user->email }}
                            </a>
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
                        <td class="px-6 py-4 whitespace-nowrap text-gray-500">
                            {{ $site->created_at->format('d.m.Y') }}
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
    </div>

    {{-- Pagination --}}
    <div class="mt-6">
        {{ $sites->links() }}
    </div>
@endsection
