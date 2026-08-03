<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }} - Admin</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gray-50 font-geist text-gray-700">
    {{-- Top Navigation --}}
    <div class="sticky top-4 flex items-center justify-center z-20 px-4">
        @include('partials.nav')
    </div>

    <div class="max-w-7xl mx-auto px-4 py-8 mt-8">
        <div class="flex gap-8">
            {{-- Sidebar --}}
            <aside class="w-64 shrink-0">
                <nav class="sticky top-24 space-y-2">
                    {{-- Admin Badge --}}
                    <div class="bg-gradient-to-r from-purple-600 to-blue-600 rounded-2xl p-4 mb-4 text-white">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" />
                                </svg>
                            </div>
                            <div>
                                <div class="font-semibold">Admin Panel</div>
                                <div class="text-white/70 text-sm">{{ auth()->user()->email }}</div>
                            </div>
                        </div>
                    </div>

                    {{-- Navigation --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2">
                        <a href="{{ route('admin.dashboard') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-purple-50 text-purple-700' : 'hover:bg-gray-50 text-gray-600' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z" />
                            </svg>
                            <span class="font-medium">Dashboard</span>
                        </a>

                        <a href="{{ route('admin.users.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.users.*') ? 'bg-purple-50 text-purple-700' : 'hover:bg-gray-50 text-gray-600' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                            <span class="font-medium">Utilizatori</span>
                        </a>

                        <a href="{{ route('admin.sites.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.sites.*') ? 'bg-purple-50 text-purple-700' : 'hover:bg-gray-50 text-gray-600' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9" />
                            </svg>
                            <span class="font-medium">Site-uri</span>
                        </a>

                        <a href="{{ route('admin.stats.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.stats.*') ? 'bg-purple-50 text-purple-700' : 'hover:bg-gray-50 text-gray-600' }}">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                            </svg>
                            <span class="font-medium">Statistici</span>
                        </a>
                    </div>

                    {{-- Back to User Dashboard --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2">
                        <a href="{{ route('dashboard.sites.index') }}"
                            class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-50 text-gray-500 transition-all">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M11 17l-5-5m0 0l5-5m-5 5h12" />
                            </svg>
                            <span class="font-medium">Dashboard User</span>
                        </a>
                    </div>
                </nav>
            </aside>

            {{-- Main Content --}}
            <main class="flex-1 min-w-0">
                @yield('content')
            </main>
        </div>
    </div>

    <x-cookie-consent />
</body>

</html>
