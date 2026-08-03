@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-16">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Politica de cookies</h1>
        <p class="text-sm text-gray-500 mb-10">Ultima actualizare: 3 august 2026</p>

        <div class="space-y-8 text-gray-700 leading-relaxed">
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">1. Cele două categorii</h2>
                <p>
                    <strong>Strict necesare.</strong> Cele fără de care autentificarea și formularele nu ar
                    funcționa. Pentru că serviciul nu poate fi oferit fără ele, nu îți cerem acordul și nu pot fi
                    dezactivate.
                </p>
                <p class="mt-3">
                    <strong>Analiză.</strong> Google Analytics, cu adresa IP anonimizată, ca să vedem ce pagini sunt
                    folosite și ce merită îmbunătățit. Acestea se încarcă <strong>doar dacă le accepți</strong>.
                    Dacă refuzi sau nu alegi nimic, nu se încarcă deloc - nu doar că nu sunt setate cookie-uri,
                    ci scriptul nici măcar nu este cerut de la Google.
                </p>
                <p class="mt-3">
                    Nu folosim cookie-uri de publicitate și nu vindem date către rețele de marketing.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">2. Cum îți alegi și îți schimbi opțiunile</h2>
                <p>
                    La prima vizită îți arătăm un banner cu trei opțiuni la fel de accesibile: accepți tot, refuzi
                    tot, sau intri în preferințe și alegi pe categorii. Refuzul este la un singur clic, exact ca
                    acceptarea.
                </p>
                <p class="mt-3">
                    Te poți răzgândi oricând: apasă
                    <button type="button" class="underline text-purple-600 hover:text-purple-700"
                        onclick="window.dispatchEvent(new CustomEvent('open-cookie-preferences'))">Setări cookies</button>
                    - același link se află și în subsolul fiecărei pagini. Alegerea ta este reținută șase luni,
                    după care te întrebăm din nou.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">3. Lista completă</h2>
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
                            <tr>
                                <td class="px-4 py-3 font-mono text-xs">cookie_consent</td>
                                <td class="px-4 py-3">Reține chiar alegerea ta de aici, ca să nu te întrebăm la fiecare pagină</td>
                                <td class="px-4 py-3">6 luni</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="mt-3 text-sm text-gray-600">
                    Toate cele de mai sus sunt cookie-uri proprii (first-party), transmise doar prin conexiune
                    securizată, și sunt strict necesare.
                </p>

                <h3 class="font-medium text-gray-900 mt-6 mb-2">Doar dacă accepți categoria Analiză</h3>
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
                                <td class="px-4 py-3 font-mono text-xs">_ga</td>
                                <td class="px-4 py-3">Google Analytics - distinge vizitatorii între ei</td>
                                <td class="px-4 py-3">2 ani</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3 font-mono text-xs">_ga_*</td>
                                <td class="px-4 py-3">Google Analytics - menține starea sesiunii de măsurare</td>
                                <td class="px-4 py-3">2 ani</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="mt-3 text-sm text-gray-600">
                    Sunt setate de Google, cu adresa IP anonimizată. Dacă refuzi, nu apar deloc - scriptul Google
                    nici măcar nu este cerut.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">4. Apelurile către API</h2>
                <p>
                    API-ul de la <code class="px-1.5 py-0.5 bg-gray-100 rounded text-sm font-mono">api.siruta.ro</code>
                    se autentifică prin headerul <code class="px-1.5 py-0.5 bg-gray-100 rounded text-sm font-mono">X-Site-Token</code>,
                    nu prin cookie-uri. Integrarea ta nu are nevoie de niciun cookie ca să funcționeze.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">5. Cum le controlezi din browser</h2>
                <p>
                    Orice browser îți permite să vezi, să blochezi sau să ștergi cookie-urile din setări. Reține însă
                    că blocarea celor de mai sus face autentificarea imposibilă - partea publică a site-ului și
                    documentația rămân accesibile.
                </p>
            </section>
        </div>
    </div>
@endsection
