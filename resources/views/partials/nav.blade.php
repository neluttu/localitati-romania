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
    <a href="/" class="transition-all duration-300 ease-in-out hover:text-purple-300">Docs</a>
    <a href="{{ route('examples.index') }}"
        class="transition-all duration-300 ease-in-out hover:text-purple-300">Exemple</a>
    @guest
        <a href="/login"
            class="p-1 bg-purple-500 text-white rounded-full px-3 transition-all duration-300 ease-in-out hover:bg-purple-600 mr-0.5">
            Cont
        </a>
    @endguest
    @auth
        <a href="/" class="transition-all duration-300 ease-in-out hover:text-purple-300">API Keys</a>
        <a href="{{ route('account.index') }}" class="flex items-center gap-2">
            <span>Salut, {{ auth()->user()->profile->first_name }}!</span>
            <img src="{{ auth()->user()->profile->avatar_url }}" class="w-7 h-7 rounded-full" />
        </a>
    @endauth

    @php
        $link = route('login');

        if (auth()->check()) {
            $user = auth()->user();
            if ($user->isAdmin()) {
                $link = route('admin.dashboard');
            } else {
                $link = route('account.index');
            }
        }
    @endphp

    @auth
        <form method="POST" action="{{ route('logout') }}" class="mr-1">
            @csrf
            <button class="text-purple-300">
                @svg('heroicon-o-power', 'w-7 h-7 pt-1 stroke-2')
            </button>
        </form>
    @endauth

</nav>
