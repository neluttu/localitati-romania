@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-16">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Politica de cookies</h1>
        <p class="text-sm text-gray-500 mb-10">Ultima actualizare: 3 august 2026</p>

        <div class="space-y-8 text-gray-700 leading-relaxed">
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">1. Ce folosim</h2>
                <p>
                    Folosim exclusiv cookie-uri <strong>strict necesare</strong> - cele fără de care autentificarea
                    și formularele nu ar funcționa. Nu folosim cookie-uri de analiză, de publicitate sau de
                    urmărire, și nu încărcăm scripturi ale altor companii în paginile noastre.
                </p>
                <p class="mt-3">
                    Pentru că sunt strict necesare, aceste cookie-uri nu necesită consimțământ prealabil - de aceea
                    nu îți afișăm un banner care să te întrerupă.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">2. Lista completă</h2>
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Cookie</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Rol</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Durată</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="px-4 py-3 font-mono text-xs">..._session</td>
                                <td class="px-4 py-3">Ține minte că ești autentificat pe durata vizitei</td>
                                <td class="px-4 py-3">2 ore</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-mono text-xs">XSRF-TOKEN</td>
                                <td class="px-4 py-3">Protejează formularele împotriva atacurilor CSRF</td>
                                <td class="px-4 py-3">2 ore</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-mono text-xs">remember_web_*</td>
                                <td class="px-4 py-3">Te menține autentificat dacă bifezi „ține-mă minte"</td>
                                <td class="px-4 py-3">până la 5 ani sau până la delogare</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="mt-3 text-sm text-gray-600">
                    Toate sunt cookie-uri proprii (first-party), transmise doar prin conexiune securizată.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">3. Apelurile către API</h2>
                <p>
                    API-ul de la <code class="px-1.5 py-0.5 bg-gray-100 rounded text-sm font-mono">api.siruta.ro</code>
                    se autentifică prin headerul <code class="px-1.5 py-0.5 bg-gray-100 rounded text-sm font-mono">X-Site-Token</code>,
                    nu prin cookie-uri. Integrarea ta nu are nevoie de niciun cookie ca să funcționeze.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">4. Cum le controlezi</h2>
                <p>
                    Orice browser îți permite să vezi, să blochezi sau să ștergi cookie-urile din setări. Reține însă
                    că blocarea celor de mai sus face autentificarea imposibilă - partea publică a site-ului și
                    documentația rămân accesibile.
                </p>
            </section>
        </div>
    </div>
@endsection
