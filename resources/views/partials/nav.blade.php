<nav class="flex items-center justify-around bg-black text-white rounded-full p-2 font-medium gap-8 text-sm">
    <a href="/" class="pl-1 font-semibold flex items-center gap-1 text-purple-300">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
            class="icon icon-tabler icons-tabler-outline icon-tabler-map-pin">
            <path stroke="none" d="M0 0h24v24H0z" fill="none" />
            <path d="M9 11a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" />
            <path d="M17.657 16.657l-4.243 4.243a2 2 0 0 1 -2.827 0l-4.244 -4.243a8 8 0 1 1 11.314 0z" />
        </svg>
        SIRUTA
    </a>
    <a href="{{ route('docs') }}" class="transition-all duration-300 ease-in-out hover:text-purple-300">Docs</a>
    <a href="{{ route('examples.index') }}"
        class="transition-all duration-300 ease-in-out hover:text-purple-300">Exemple</a>
    @guest
        <a href="/login"
            class="p-1 bg-purple-500 text-white rounded-full px-3 transition-all duration-300 ease-in-out hover:bg-purple-600 mr-0.5">
            Cont
        </a>
    @endguest
    @auth
        @if(auth()->user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="transition-all duration-300 ease-in-out hover:text-purple-300 flex items-center gap-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                Admin
            </a>
        @endif
        <a href="{{ route('dashboard.sites.index') }}" class="flex items-center gap-2 transition-all duration-300 ease-in-out hover:text-purple-300">
            <span>{{ auth()->user()->profile?->first_name ?? auth()->user()->email }}</span>
            @if(auth()->user()->profile?->avatar_url)
                <img src="{{ auth()->user()->profile->avatar_url }}" class="w-7 h-7 rounded-full" />
            @endif
        </a>
        <form method="POST" action="{{ route('logout') }}" class="mr-1">
            @csrf
            <button class="text-purple-300">
                @svg('heroicon-o-power', 'w-7 h-7 pt-1 stroke-2')
            </button>
        </form>
    @endauth

</nav>
