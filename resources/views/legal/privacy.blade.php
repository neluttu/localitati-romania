@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-16">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Politica de confidențialitate</h1>
        <p class="text-sm text-gray-500 mb-10">Ultima actualizare: 3 august 2026</p>

        <div class="space-y-8 text-gray-700 leading-relaxed">
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">1. Cine prelucrează datele</h2>
                <p>
                    Operatorul datelor este <strong>Neluttu</strong>, care administrează {{ config('app.name') }} și
                    <code class="px-1.5 py-0.5 bg-gray-100 rounded text-sm font-mono">api.siruta.ro</code>.
                    Pentru orice solicitare privind datele tale scrie la
                    <a href="mailto:contact@siruta.ro" class="text-purple-600 hover:underline">contact@siruta.ro</a>.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">2. Ce date colectăm</h2>

                <h3 class="font-medium text-gray-900 mt-4 mb-2">La crearea contului</h3>
                <ul class="list-disc pl-5 space-y-1">
                    <li>adresa de email;</li>
                    <li>numele și prenumele;</li>
                    <li>parola, stocată exclusiv sub formă de hash - nu o cunoaștem și nu o putem citi;</li>
                    <li>opțional, numărul de telefon și imaginea de profil, dacă alegi să le completezi;</li>
                    <li>dacă te autentifici cu Google sau Facebook: identificatorul de cont de la furnizorul respectiv.</li>
                </ul>

                <h3 class="font-medium text-gray-900 mt-4 mb-2">La autentificare</h3>
                <ul class="list-disc pl-5 space-y-1">
                    <li>data și ora ultimei autentificări, adresa IP, tipul browserului și numărul de autentificări.</li>
                </ul>

                <h3 class="font-medium text-gray-900 mt-4 mb-2">La folosirea API-ului</h3>
                <ul class="list-disc pl-5 space-y-1">
                    <li>site-urile pe care le înregistrezi: denumire, domeniu declarat și tokenul generat;</li>
                    <li>pentru fiecare apel: endpointul, metoda, codul de răspuns, durata, tipul browserului și
                        <strong>adresa IP mascată</strong> - ultimul segment este înlocuit cu zero
                        (<code class="px-1.5 py-0.5 bg-gray-100 rounded text-sm font-mono">192.168.1.123</code> devine
                        <code class="px-1.5 py-0.5 bg-gray-100 rounded text-sm font-mono">192.168.1.0</code>), astfel
                        încât apelul nu poate fi legat de un dispozitiv anume.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">3. De ce le folosim și în ce temei</h2>
                <div class="overflow-x-auto rounded-xl border border-gray-200">
                    <table class="w-full text-sm">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Scop</th>
                                <th class="px-4 py-3 text-left font-semibold text-gray-900">Temei legal (GDPR)</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            <tr>
                                <td class="px-4 py-3">Crearea contului și furnizarea accesului la API</td>
                                <td class="px-4 py-3">executarea contractului - art. 6 alin. (1) lit. b)</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3">Statistici de utilizare și atribuirea apelurilor pe site</td>
                                <td class="px-4 py-3">interes legitim - art. 6 alin. (1) lit. f)</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3">Securitate, prevenirea abuzului și aplicarea limitei de apeluri</td>
                                <td class="px-4 py-3">interes legitim - art. 6 alin. (1) lit. f)</td>
                            </tr>
                            <tr>
                                <td class="px-4 py-3">Emailuri legate de cont (confirmare, resetare parolă)</td>
                                <td class="px-4 py-3">executarea contractului - art. 6 alin. (1) lit. b)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <p class="mt-3">Nu folosim datele tale pentru profilare, publicitate comportamentală sau decizii automate.</p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">4. Cât timp le păstrăm</h2>
                <ul class="list-disc pl-5 space-y-2">
                    <li><strong>Datele contului:</strong> cât timp contul este activ.</li>
                    <li><strong>Log-urile de apeluri API:</strong> 90 de zile, apoi se șterg automat.</li>
                    <li><strong>După ștergerea contului:</strong> contul și site-urile sunt dezactivate imediat, iar
                        datele se șterg definitiv după 30 de zile. Intervalul există ca să putem recupera un cont
                        șters din greșeală.</li>
                    <li><strong>După ștergerea definitivă:</strong> păstrăm strict numărul de apeluri, fără nicio
                        legătură cu tine - se șterg atât identificarea site-ului, cât și tipul browserului.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">5. Cui le divulgăm</h2>
                <p>
                    Nu vindem și nu închiriem datele nimănui. Ele sunt accesibile doar furnizorului de găzduire care
                    operează serverele pe care rulează aplicația, în calitate de persoană împuternicită, și
                    autorităților, dacă legea ne obligă.
                </p>
                <p class="mt-3">
                    Dacă alegi autentificarea cu Google sau Facebook, aceștia prelucrează datele conform propriilor
                    politici, pe care le poți citi pe site-urile lor.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">6. Drepturile tale</h2>
                <p>Conform GDPR ai dreptul:</p>
                <ul class="list-disc pl-5 space-y-1 mt-2">
                    <li>să afli ce date deținem despre tine și să primești o copie;</li>
                    <li>să ceri corectarea datelor inexacte;</li>
                    <li>să ceri ștergerea lor;</li>
                    <li>să ceri restricționarea prelucrării;</li>
                    <li>să primești datele într-un format portabil;</li>
                    <li>să te opui prelucrării întemeiate pe interesul legitim.</li>
                </ul>
                <p class="mt-3">
                    Îți poți șterge singur contul, oricând, din
                    <a href="{{ route('dashboard.account.delete') }}" class="text-purple-600 hover:underline">panoul tău</a>.
                    Pentru celelalte drepturi scrie-ne la
                    <a href="mailto:contact@siruta.ro" class="text-purple-600 hover:underline">contact@siruta.ro</a>;
                    răspundem în cel mult 30 de zile.
                </p>
                <p class="mt-3">
                    Dacă nu ești mulțumit de răspuns, poți depune o plângere la Autoritatea Națională de Supraveghere
                    a Prelucrării Datelor cu Caracter Personal (ANSPDCP),
                    <a href="https://www.dataprotection.ro" target="_blank" rel="noopener" class="text-purple-600 hover:underline">dataprotection.ro</a>.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">7. Securitate</h2>
                <p>
                    Traficul către site și API este criptat prin HTTPS, parolele sunt stocate ca hash-uri, iar
                    adresele IP din log-urile de apeluri sunt mascate încă din momentul înregistrării. Niciun sistem
                    nu este însă complet invulnerabil; dacă observi o problemă de securitate, scrie-ne și o tratăm
                    cu prioritate.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">8. Cookies</h2>
                <p>
                    Folosim doar cookie-uri strict necesare funcționării. Detaliile sunt în
                    <a href="{{ route('legal.cookies') }}" class="text-purple-600 hover:underline">politica de cookies</a>.
                </p>
            </section>
        </div>
    </div>
@endsection
