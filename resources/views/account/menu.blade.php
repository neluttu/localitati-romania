<div class="w-full flex flex-row  mb-10">
    <a href="{{ route('account.profile') }}"
        class="px-4 py-2 rounded-lg mb-2 hover:bg-gray-100 {{ request()->routeIs('account.profile') ? 'bg-gray-100 font-semibold' : '' }}">
        Profil utilizator
    </a>
    <a href="{{ route('account.index') }}"
        class="px-4 py-2 rounded-lg mb-2 hover:bg-gray-100 {{ request()->routeIs('') ? 'bg-gray-100 font-semibold' : '' }}">
        API Keys
    </a>
</div>
