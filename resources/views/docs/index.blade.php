@extends('layouts.app')

@section('content')
<div class="bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4 py-16">
        {{-- Hero Section --}}
        <div class="mb-12">
            <h1 class="text-4xl font-bold text-gray-900 tracking-tight mb-4">
                Documentație API
            </h1>
            <p class="text-lg text-gray-600 mb-6 max-w-2xl">
                Integrează date administrative oficiale pentru România în aplicația ta.
                Județe, localități, coduri poștale și coordonate GPS.
            </p>
            <div class="flex flex-wrap gap-3">
                <a href="{{ route('register') }}"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-purple-500 hover:bg-purple-600 text-white font-medium rounded-full transition-all">
                    Obține API Key
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                    </svg>
                </a>
                <a href="#endpoints"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-full transition-all">
                    Vezi Endpoints
                </a>
            </div>
        </div>

        {{-- Quick Start Cards --}}
        <div class="mb-12">
            <div class="grid md:grid-cols-3 gap-6">
                <div class="group p-6 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:border-purple-200 transition-all duration-300">
                    <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">1. Creează cont</h3>
                    <p class="text-gray-600 text-sm">Înregistrează-te gratuit și adaugă site-ul tău în dashboard.</p>
                </div>
                <div class="group p-6 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:border-blue-200 transition-all duration-300">
                    <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">2. Copiază token</h3>
                    <p class="text-gray-600 text-sm">Primești automat un API token unic pentru domeniul tău.</p>
                </div>
                <div class="group p-6 bg-white rounded-2xl border border-gray-100 shadow-sm hover:shadow-lg hover:border-green-200 transition-all duration-300">
                    <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center mb-4 group-hover:scale-110 transition-transform">
                        <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                        </svg>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">3. Integrează</h3>
                    <p class="text-gray-600 text-sm">Trimite token-ul în header și accesează toate datele.</p>
                </div>
            </div>
        </div>

        {{-- Main Content with Sidebar --}}
        <div class="grid lg:grid-cols-[220px_1fr] gap-12">
            {{-- Sidebar --}}
            <aside class="hidden lg:block">
                <nav class="sticky top-24 space-y-1 text-sm">
                    <a href="#authentication" class="block py-2 text-gray-600 hover:text-purple-600 transition-colors">Autentificare</a>
                    <a href="#endpoints" class="block py-2 text-gray-600 hover:text-purple-600 transition-colors">Endpoints</a>
                    <a href="#errors" class="block py-2 text-gray-600 hover:text-purple-600 transition-colors">Coduri eroare</a>
                    <a href="#examples" class="block py-2 text-gray-600 hover:text-purple-600 transition-colors">Exemple cod</a>
                    <a href="#limits" class="block py-2 text-gray-600 hover:text-purple-600 transition-colors">Rate Limits</a>
                </nav>
            </aside>

            {{-- Content --}}
            <div>
                    {{-- Authentication Section --}}
                    <section id="authentication" class="scroll-mt-8 mb-16">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-purple-600 rounded-xl flex items-center justify-center shadow-lg shadow-purple-500/30">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">Autentificare</h2>
                        </div>

                        <p class="text-gray-600 mb-6">
                            Toate request-urile către API necesită un token valid. Token-ul identifică site-ul care apelează,
                            pentru statistici de utilizare - funcționează de pe orice domeniu, din aplicații server-side
                            și din mediul local de dezvoltare.
                        </p>

                        {{-- Code Block --}}
                        <div class="relative group mb-8">
                            <div class="absolute -inset-1 bg-gradient-to-r from-purple-600 to-blue-600 rounded-2xl blur opacity-20 group-hover:opacity-30 transition-opacity"></div>
                            <div class="relative bg-gray-950 rounded-xl overflow-hidden">
                                <div class="flex items-center justify-between px-4 py-3 bg-gray-900/50 border-b border-gray-800">
                                    <span class="text-sm text-gray-400">Header necesar</span>
                                    <button onclick="navigator.clipboard.writeText('X-Site-Token: YOUR_API_TOKEN')" class="text-xs text-gray-500 hover:text-gray-300 transition-colors">
                                        Copiază
                                    </button>
                                </div>
                                <pre class="p-4 overflow-x-auto"><code class="text-sm"><span class="text-purple-400">X-Site-Token</span><span class="text-gray-500">:</span> <span class="text-green-400">YOUR_API_TOKEN</span></code></pre>
                            </div>
                        </div>

                        {{-- Where the token works --}}
                        <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200/50 rounded-2xl p-6 mb-6">
                            <div class="flex gap-4">
                                <div class="shrink-0 w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-emerald-900 mb-1">Fără restricții de domeniu</h4>
                                    <p class="text-emerald-800 text-sm">
                                        Token-ul funcționează de oriunde: din browser, din backend, de pe
                                        <code class="px-1.5 py-0.5 bg-emerald-200/50 rounded font-mono text-xs">localhost</code>
                                        sau din Postman. Domeniul completat la înregistrare este doar o etichetă care te
                                        ajută să-ți recunoști site-urile în panou.
                                    </p>
                                </div>
                            </div>
                        </div>

                        {{-- Rate limit --}}
                        <div class="bg-gradient-to-br from-amber-50 to-orange-50 border border-amber-200/50 rounded-2xl p-6">
                            <div class="flex gap-4">
                                <div class="shrink-0 w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                                    <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div>
                                    <h4 class="font-semibold text-amber-900 mb-1">Limită de apeluri</h4>
                                    <p class="text-amber-800 text-sm">
                                        Maximum <strong>120 de request-uri pe minut</strong>. Peste această limită primești
                                        <code class="px-1.5 py-0.5 bg-amber-200/50 rounded font-mono text-xs">429 Too Many Requests</code>.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Endpoints Section --}}
                    <section id="endpoints" class="scroll-mt-8 mb-16">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-xl flex items-center justify-center shadow-lg shadow-blue-500/30">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">Endpoints</h2>
                        </div>

                        <div class="flex items-center gap-3 p-4 bg-gray-100 rounded-xl mb-8">
                            <span class="text-sm text-gray-500">Base URL</span>
                            <code class="text-sm font-mono font-medium text-gray-900">https://api.siruta.ro/v1</code>
                        </div>

                        {{-- Counties --}}
                        <div id="judete" class="scroll-mt-8 mb-10">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="w-2 h-2 bg-blue-500 rounded-full"></span>
                                Județe
                            </h3>

                            {{-- GET /counties --}}
                            <div class="border border-gray-200 rounded-2xl overflow-hidden mb-4 hover:border-gray-300 transition-colors">
                                <div class="flex items-center gap-3 px-5 py-4 bg-gray-50 border-b border-gray-200">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-100 text-emerald-700">GET</span>
                                    <code class="text-sm font-mono text-gray-800">/counties</code>
                                    <span class="ml-auto text-sm text-gray-500">Lista județelor</span>
                                </div>
                                <div class="p-5">
                                    <p class="text-gray-600 text-sm mb-4">Returnează toate cele 42 de județe din România, inclusiv coduri SIRUTA și regiuni.</p>
                                    <details class="group">
                                        <summary class="flex items-center gap-2 cursor-pointer text-sm font-medium text-purple-600 hover:text-purple-700">
                                            <svg class="w-4 h-4 transition-transform group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                            Vezi exemplu răspuns
                                        </summary>
                                        <div class="mt-4 bg-gray-950 rounded-xl overflow-hidden">
                                            <pre class="p-4 overflow-x-auto text-sm"><code class="text-gray-300">{
  <span class="text-purple-400">"data"</span>: [
    {
      <span class="text-blue-400">"id"</span>: <span class="text-amber-400">1</span>,
      <span class="text-blue-400">"siruta_code"</span>: <span class="text-amber-400">10</span>,
      <span class="text-blue-400">"name"</span>: <span class="text-green-400">"Alba"</span>,
      <span class="text-blue-400">"abbr"</span>: <span class="text-green-400">"AB"</span>,
      <span class="text-blue-400">"region"</span>: { <span class="text-blue-400">"id"</span>: <span class="text-amber-400">7</span>, <span class="text-blue-400">"label"</span>: <span class="text-green-400">"Centru"</span> }
    }
  ],
  <span class="text-purple-400">"meta"</span>: { <span class="text-blue-400">"total"</span>: <span class="text-amber-400">42</span> }
}</code></pre>
                                        </div>
                                    </details>
                                </div>
                            </div>

                            {{-- GET /counties/{abbr} --}}
                            <div class="border border-gray-200 rounded-2xl overflow-hidden mb-4 hover:border-gray-300 transition-colors">
                                <div class="flex items-center gap-3 px-5 py-4 bg-gray-50 border-b border-gray-200">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-100 text-emerald-700">GET</span>
                                    <code class="text-sm font-mono text-gray-800">/counties/<span class="text-purple-600">{abbr}</span></code>
                                    <span class="ml-auto text-sm text-gray-500">Detalii județ</span>
                                </div>
                                <div class="p-5">
                                    <p class="text-gray-600 text-sm">Returnează informații detaliate despre un județ specific.</p>
                                    <p class="mt-2 text-xs text-gray-500"><strong>Param:</strong> <code class="bg-gray-100 px-1.5 py-0.5 rounded">abbr</code> - Abrevierea județului (MS, CJ, B)</p>
                                </div>
                            </div>
                        </div>

                        {{-- Localities --}}
                        <div id="localitati" class="scroll-mt-8 mb-10">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="w-2 h-2 bg-purple-500 rounded-full"></span>
                                Localități
                            </h3>

                            {{-- GET /counties/{abbr}/localities --}}
                            <div class="border border-gray-200 rounded-2xl overflow-hidden mb-4 hover:border-gray-300 transition-colors">
                                <div class="flex items-center gap-3 px-5 py-4 bg-gray-50 border-b border-gray-200">
                                    <span class="px-2.5 py-1 text-xs font-bold rounded-lg bg-emerald-100 text-emerald-700">GET</span>
                                    <code class="text-sm font-mono text-gray-800">/counties/<span class="text-purple-600">{abbr}</span>/localities</code>
                                </div>
                                <div class="p-5">
                                    <p class="text-gray-600 text-sm mb-4">Toate localitățile dintr-un județ cu coordonate GPS și relații părinte.</p>
                                    <details class="group">
                                        <summary class="flex items-center gap-2 cursor-pointer text-sm font-medium text-purple-600 hover:text-purple-700">
                                            <svg class="w-4 h-4 transition-transform group-open:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                            </svg>
                                            Vezi exemplu răspuns
                                        </summary>
                                        <div class="mt-4 bg-gray-950 rounded-xl overflow-hidden">
                                            <pre class="p-4 overflow-x-auto text-sm"><code class="text-gray-300">{
  <span class="text-purple-400">"data"</span>: [{
    <span class="text-blue-400">"siruta_code"</span>: <span class="text-amber-400">114337</span>,
    <span class="text-blue-400">"name"</span>: <span class="text-green-400">"Mureșeni"</span>,
    <span class="text-blue-400">"type_label"</span>: <span class="text-green-400">"Componentă municipiu"</span>,
    <span class="text-blue-400">"postal_code"</span>: <span class="text-green-400">"540001"</span>,
    <span class="text-blue-400">"lat"</span>: <span class="text-amber-400">46.522996</span>,
    <span class="text-blue-400">"lng"</span>: <span class="text-amber-400">24.520033</span>,
    <span class="text-blue-400">"parent"</span>: {
      <span class="text-blue-400">"name"</span>: <span class="text-green-400">"Târgu Mureș"</span>,
      <span class="text-blue-400">"type_label"</span>: <span class="text-green-400">"Municipiu reședință"</span>
    }
  }]
}</code></pre>
                                        </div>
                                    </details>
                                </div>
                            </div>

                            {{-- Other locality endpoints --}}
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="border border-gray-200 rounded-2xl p-5 hover:border-gray-300 transition-colors">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="px-2 py-0.5 text-xs font-bold rounded bg-emerald-100 text-emerald-700">GET</span>
                                        <code class="text-xs font-mono text-gray-700">/localities/lite?county=MS</code>
                                    </div>
                                    <p class="text-sm text-gray-600">Versiune simplificată - ideal pentru dropdown-uri. Parametrul <code>county</code> este obligatoriu.</p>
                                </div>
                                <div class="border border-gray-200 rounded-2xl p-5 hover:border-gray-300 transition-colors">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="px-2 py-0.5 text-xs font-bold rounded bg-emerald-100 text-emerald-700">GET</span>
                                        <code class="text-xs font-mono text-gray-700">/localities/grouped?county=MS</code>
                                    </div>
                                    <p class="text-sm text-gray-600">Grupate după tip (municipii, orașe, comune). Parametrul <code>county</code> este obligatoriu.</p>
                                </div>
                                <div class="border border-gray-200 rounded-2xl p-5 hover:border-gray-300 transition-colors">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="px-2 py-0.5 text-xs font-bold rounded bg-emerald-100 text-emerald-700">GET</span>
                                        <code class="text-xs font-mono text-gray-700">/localities?county=MS</code>
                                    </div>
                                    <p class="text-sm text-gray-600">Căutare într-un județ. <code>county</code> este obligatoriu; <code>search</code> și <code>type</code> sunt opționale.</p>
                                </div>
                                <div class="border border-gray-200 rounded-2xl p-5 hover:border-gray-300 transition-colors">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="px-2 py-0.5 text-xs font-bold rounded bg-emerald-100 text-emerald-700">GET</span>
                                        <code class="text-xs font-mono text-gray-700">/localities/{siruta}</code>
                                    </div>
                                    <p class="text-sm text-gray-600">Detalii localitate după cod SIRUTA</p>
                                </div>
                            </div>
                        </div>

                        {{-- Metadata --}}
                        <div id="metadata" class="scroll-mt-8">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center gap-2">
                                <span class="w-2 h-2 bg-green-500 rounded-full"></span>
                                Metadata
                            </h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div class="border border-gray-200 rounded-2xl p-5 hover:border-gray-300 transition-colors">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="px-2 py-0.5 text-xs font-bold rounded bg-emerald-100 text-emerald-700">GET</span>
                                        <code class="text-xs font-mono text-gray-700">/lookups/locality-types</code>
                                    </div>
                                    <p class="text-sm text-gray-600">Tipuri de localități (municipiu, oraș, sat...)</p>
                                </div>
                                <div class="border border-gray-200 rounded-2xl p-5 hover:border-gray-300 transition-colors">
                                    <div class="flex items-center gap-2 mb-2">
                                        <span class="px-2 py-0.5 text-xs font-bold rounded bg-emerald-100 text-emerald-700">GET</span>
                                        <code class="text-xs font-mono text-gray-700">/lookups/regions</code>
                                    </div>
                                    <p class="text-sm text-gray-600">Regiuni de dezvoltare din România</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Errors Section --}}
                    <section id="errors" class="scroll-mt-8 mb-16">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-red-500 to-red-600 rounded-xl flex items-center justify-center shadow-lg shadow-red-500/30">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">Coduri de eroare</h2>
                        </div>

                        <div class="space-y-3">
                            <div class="flex items-start gap-4 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                                <span class="shrink-0 px-3 py-1 text-sm font-bold rounded-lg bg-amber-200 text-amber-800">401</span>
                                <div>
                                    <p class="font-medium text-gray-900">Token lipsă sau invalid</p>
                                    <p class="text-sm text-gray-600 mt-1">Adaugă header-ul X-Site-Token cu un token valid.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 p-4 bg-orange-50 border border-orange-200 rounded-xl">
                                <span class="shrink-0 px-3 py-1 text-sm font-bold rounded-lg bg-orange-200 text-orange-800">403</span>
                                <div>
                                    <p class="font-medium text-gray-900">Domain mismatch</p>
                                    <p class="text-sm text-gray-600 mt-1">Request-ul vine de pe un domeniu diferit de cel înregistrat.</p>
                                </div>
                            </div>
                            <div class="flex items-start gap-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                                <span class="shrink-0 px-3 py-1 text-sm font-bold rounded-lg bg-red-200 text-red-800">429</span>
                                <div>
                                    <p class="font-medium text-gray-900">Too Many Requests</p>
                                    <p class="text-sm text-gray-600 mt-1">Ai depășit limita de 120 request-uri/minut. Așteaptă 1 minut.</p>
                                </div>
                            </div>
                        </div>
                    </section>

                    {{-- Examples Section --}}
                    <section id="examples" class="scroll-mt-8 mb-16">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-emerald-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg shadow-emerald-500/30">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">Exemple de cod</h2>
                        </div>

                        <div x-data="{ tab: 'js' }" class="border border-gray-200 rounded-2xl overflow-hidden">
                            {{-- Tabs --}}
                            <div class="flex border-b border-gray-200 bg-gray-50">
                                <button @click="tab = 'js'" :class="tab === 'js' ? 'border-b-2 border-purple-500 text-purple-600 bg-white' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition-colors">JavaScript</button>
                                <button @click="tab = 'php'" :class="tab === 'php' ? 'border-b-2 border-purple-500 text-purple-600 bg-white' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition-colors">PHP</button>
                                <button @click="tab = 'python'" :class="tab === 'python' ? 'border-b-2 border-purple-500 text-purple-600 bg-white' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition-colors">Python</button>
                                <button @click="tab = 'curl'" :class="tab === 'curl' ? 'border-b-2 border-purple-500 text-purple-600 bg-white' : 'text-gray-500 hover:text-gray-700'" class="px-5 py-3 text-sm font-medium transition-colors">cURL</button>
                            </div>

                            {{-- Code blocks --}}
                            <div class="bg-gray-950">
                                <pre x-show="tab === 'js'" class="p-5 overflow-x-auto text-sm"><code class="text-gray-300"><span class="text-purple-400">const</span> response = <span class="text-purple-400">await</span> <span class="text-blue-400">fetch</span>(<span class="text-green-400">'https://api.siruta.ro/v1/counties'</span>, {
  <span class="text-blue-400">headers</span>: {
    <span class="text-green-400">'X-Site-Token'</span>: <span class="text-green-400">'YOUR_API_TOKEN'</span>,
    <span class="text-green-400">'Accept'</span>: <span class="text-green-400">'application/json'</span>
  }
});

<span class="text-purple-400">const</span> data = <span class="text-purple-400">await</span> response.<span class="text-blue-400">json</span>();</code></pre>

                                <pre x-show="tab === 'php'" class="p-5 overflow-x-auto text-sm"><code class="text-gray-300"><span class="text-purple-400">use</span> Illuminate\Support\Facades\<span class="text-blue-400">Http</span>;

<span class="text-purple-400">$response</span> = <span class="text-blue-400">Http</span>::<span class="text-blue-400">withHeaders</span>([
    <span class="text-green-400">'X-Site-Token'</span> => <span class="text-green-400">'YOUR_API_TOKEN'</span>,
])-><span class="text-blue-400">get</span>(<span class="text-green-400">'https://api.siruta.ro/v1/counties'</span>);

<span class="text-purple-400">$counties</span> = <span class="text-purple-400">$response</span>-><span class="text-blue-400">json</span>(<span class="text-green-400">'data'</span>);</code></pre>

                                <pre x-show="tab === 'python'" class="p-5 overflow-x-auto text-sm"><code class="text-gray-300"><span class="text-purple-400">import</span> requests

headers = {
    <span class="text-green-400">'X-Site-Token'</span>: <span class="text-green-400">'YOUR_API_TOKEN'</span>,
    <span class="text-green-400">'Accept'</span>: <span class="text-green-400">'application/json'</span>
}

response = requests.<span class="text-blue-400">get</span>(<span class="text-green-400">'https://api.siruta.ro/v1/counties'</span>, headers=headers)
data = response.<span class="text-blue-400">json</span>()</code></pre>

                                <pre x-show="tab === 'curl'" class="p-5 overflow-x-auto text-sm"><code class="text-gray-300">curl -X GET <span class="text-green-400">"https://api.siruta.ro/v1/counties"</span> \
     -H <span class="text-green-400">"X-Site-Token: YOUR_API_TOKEN"</span> \
     -H <span class="text-green-400">"Accept: application/json"</span></code></pre>
                            </div>
                        </div>
                    </section>

                    {{-- Rate Limits --}}
                    <section id="limits" class="scroll-mt-8 mb-16">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-10 h-10 bg-gradient-to-br from-cyan-500 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg shadow-cyan-500/30">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </div>
                            <h2 class="text-2xl font-bold text-gray-900">Rate Limiting</h2>
                        </div>

                        <div class="bg-gradient-to-br from-cyan-50 to-blue-50 border border-cyan-200/50 rounded-2xl p-6">
                            <div class="flex items-center gap-6 mb-4">
                                <div class="text-center">
                                    <div class="text-4xl font-bold text-cyan-600">120</div>
                                    <div class="text-sm text-gray-600">requests</div>
                                </div>
                                <div class="text-3xl text-gray-300">/</div>
                                <div class="text-center">
                                    <div class="text-4xl font-bold text-cyan-600">1</div>
                                    <div class="text-sm text-gray-600">minut</div>
                                </div>
                            </div>
                            <p class="text-gray-600 text-sm">
                                Dacă depășești limita, primești <code class="bg-cyan-100 px-1.5 py-0.5 rounded text-xs">429 Too Many Requests</code>.
                                Implementează caching local pentru date statice (județe, tipuri).
                            </p>
                        </div>
                    </section>

                    {{-- CTA --}}
                    <div class="relative overflow-hidden rounded-3xl bg-gradient-to-r from-gray-900 to-gray-800 p-8 lg:p-12">
                        <div class="absolute top-0 right-0 w-64 h-64 bg-purple-500/20 rounded-full blur-3xl"></div>
                        <div class="absolute bottom-0 left-0 w-48 h-48 bg-blue-500/20 rounded-full blur-3xl"></div>
                        <div class="relative">
                            <h2 class="text-3xl font-bold text-white mb-4">Gata să integrezi?</h2>
                            <p class="text-gray-400 mb-8 max-w-lg">Creează un cont gratuit și obține token-ul API în mai puțin de 1 minut. Fără card, fără limite ascunse.</p>
                            <div class="flex flex-wrap gap-4">
                                <a href="{{ route('register') }}" class="inline-flex items-center gap-2 px-6 py-3 bg-white hover:bg-gray-100 text-gray-900 font-medium rounded-xl transition-all">
                                    Creează cont gratuit
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                                    </svg>
                                </a>
                                <a href="{{ route('examples.index') }}" class="inline-flex items-center gap-2 px-6 py-3 text-white border border-white/20 hover:bg-white/10 font-medium rounded-xl transition-all">
                                    Vezi exemple live
                                </a>
                            </div>
                        </div>
                    </div>
            </div>
        </div>
    </div>
</div>
@endsection
