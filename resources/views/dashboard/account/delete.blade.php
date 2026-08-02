@extends('layouts.dashboard')

@section('content')
    {{-- Header --}}
    <div class="mb-8">
        <a href="{{ route('dashboard.profile.edit') }}" class="text-sm text-gray-500 hover:text-gray-700 inline-flex items-center gap-1 mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Înapoi la profil
        </a>
        <h1 class="text-2xl font-bold text-gray-900">Ștergerea contului</h1>
        <p class="text-gray-500 mt-1">Citește ce se întâmplă înainte să confirmi</p>
    </div>

    <div class="max-w-2xl">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="p-6 border-b border-gray-100">
                <h2 class="font-semibold text-gray-900 mb-4">Ce se întâmplă când ștergi contul</h2>
                <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex gap-3">
                        <span class="shrink-0 w-5 h-5 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xs font-bold">1</span>
                        <span><strong class="text-gray-900">Tokenurile se opresc imediat.</strong> Orice site sau aplicație care folosește API-ul cu tokenurile tale va primi <code class="px-1 py-0.5 bg-gray-100 rounded text-xs font-mono">401</code> din acel moment.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="shrink-0 w-5 h-5 rounded-full bg-red-100 text-red-600 flex items-center justify-center text-xs font-bold">2</span>
                        <span><strong class="text-gray-900">Nu te mai poți autentifica.</strong> Contul și site-urile tale dispar din aplicație.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="shrink-0 w-5 h-5 rounded-full bg-amber-100 text-amber-600 flex items-center justify-center text-xs font-bold">3</span>
                        <span><strong class="text-gray-900">Ai la dispoziție 30 de zile.</strong> În acest interval ne poți scrie ca să îți recuperăm contul. După aceea datele se șterg definitiv și nu mai pot fi recuperate.</span>
                    </li>
                    <li class="flex gap-3">
                        <span class="shrink-0 w-5 h-5 rounded-full bg-gray-100 text-gray-600 flex items-center justify-center text-xs font-bold">4</span>
                        <span><strong class="text-gray-900">Statisticile agregate rămân.</strong> Păstrăm numărul de apeluri făcute, dar fără nicio legătură cu tine: se șterg atât identificarea site-ului, cât și user agent-ul.</span>
                    </li>
                </ul>
            </div>

            <form method="POST" action="{{ route('dashboard.account.destroy') }}" class="p-6 bg-red-50/50">
                @csrf
                @method('DELETE')

                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Confirmă cu parola ta
                </label>
                <input type="password" name="password" id="password" required autocomplete="current-password"
                    class="w-full px-4 py-2.5 border rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500 @error('password') border-red-500 @else border-gray-300 @enderror"
                    placeholder="Parola contului">
                @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror

                <div class="flex items-center gap-3 mt-6">
                    <button type="submit"
                        class="px-5 py-2.5 bg-red-600 text-white font-medium rounded-xl hover:bg-red-700 transition-colors">
                        Șterge contul definitiv
                    </button>
                    <a href="{{ route('dashboard.profile.edit') }}"
                        class="px-5 py-2.5 text-gray-600 font-medium rounded-xl hover:bg-gray-100 transition-colors">
                        Renunț
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
