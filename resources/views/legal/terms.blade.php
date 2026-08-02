@extends('layouts.app')

@section('content')
    <div class="max-w-3xl mx-auto px-4 py-16">
        <h1 class="text-3xl font-bold text-gray-900 mb-2">Termeni și condiții</h1>
        <p class="text-sm text-gray-500 mb-10">Ultima actualizare: 3 august 2026</p>

        <div class="space-y-8 text-gray-700 leading-relaxed">
            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">1. Despre serviciu</h2>
                <p>
                    {{ config('app.name') }} pune la dispoziție, prin <code class="px-1.5 py-0.5 bg-gray-100 rounded text-sm font-mono">api.siruta.ro</code>,
                    un API cu nomenclatorul SIRUTA - județele, localitățile și codurile administrative ale României.
                    Datele provin din surse publice oficiale și sunt oferite ca atare.
                </p>
                <p class="mt-3">
                    Serviciul este operat de <strong>Neluttu</strong>. Pentru orice întrebare legată de acești termeni
                    ne poți scrie la <a href="mailto:contact@siruta.ro" class="text-purple-600 hover:underline">contact@siruta.ro</a>.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">2. Contul și tokenul</h2>
                <p>
                    Accesul la API necesită un cont gratuit și un token pe care îl generezi din panoul tău.
                    Tokenul identifică apelurile ca fiind ale tale - de aceea ești responsabil pentru cum este folosit
                    și pentru păstrarea lui în siguranță. Dacă bănuiești că a fost compromis, îl poți regenera oricând
                    din panou; tokenul vechi încetează imediat să funcționeze.
                </p>
                <p class="mt-3">
                    Domeniul pe care îl completezi la înregistrarea unui site este o etichetă pentru propriile tale
                    statistici. Nu restricționează de unde poate fi folosit tokenul.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">3. Utilizare acceptabilă</h2>
                <ul class="list-disc pl-5 space-y-2">
                    <li>Poți folosi datele în proiecte personale și comerciale, inclusiv în produse pe care le vinzi.</li>
                    <li>Limita tehnică este de <strong>120 de request-uri pe minut</strong> per token. Peste ea primești
                        <code class="px-1.5 py-0.5 bg-gray-100 rounded text-sm font-mono">429</code>.</li>
                    <li>Nu ocoli limita prin conturi multiple create în acest scop și nu folosi serviciul într-un mod
                        care îi afectează disponibilitatea pentru ceilalți.</li>
                    <li>Nu revinde accesul la API ca atare. Poți însă construi și vinde produse care îl folosesc.</li>
                </ul>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">4. Disponibilitate și garanții</h2>
                <p>
                    Serviciul este oferit gratuit, „ca atare", fără garanție de disponibilitate neîntreruptă și fără
                    un acord de nivel al serviciului. Depunem eforturi rezonabile ca datele să fie corecte și
                    actualizate, dar nu garantăm că sunt lipsite de erori. Verifică datele critice înainte să te
                    bazezi pe ele în decizii importante.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">5. Limitarea răspunderii</h2>
                <p>
                    În limitele permise de lege, nu răspundem pentru pierderi indirecte, pierderi de date sau de
                    profit rezultate din folosirea ori din indisponibilitatea serviciului.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">6. Suspendarea și încetarea</h2>
                <p>
                    Putem dezactiva un token sau un cont care încalcă acești termeni, de regulă după o notificare
                    prealabilă, cu excepția cazurilor de abuz evident.
                </p>
                <p class="mt-3">
                    Îți poți șterge contul oricând, singur, din
                    <a href="{{ url('/dashboard/account/delete') }}" class="text-purple-600 hover:underline">panoul tău</a>.
                    Tokenurile se opresc imediat, iar datele se șterg definitiv după 30 de zile. Detaliile sunt în
                    <a href="{{ url('/politica-de-confidentialitate') }}" class="text-purple-600 hover:underline">politica de confidențialitate</a>.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">7. Modificări</h2>
                <p>
                    Putem actualiza acești termeni. Modificările importante vor fi anunțate pe email sau în panou,
                    cu un preaviz rezonabil. Data ultimei actualizări este afișată în capul paginii.
                </p>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">8. Legea aplicabilă</h2>
                <p>
                    Acestor termeni li se aplică legea română, iar eventualele litigii se soluționează de instanțele
                    competente din România. Dacă ești consumator, îți păstrezi drepturile prevăzute de legislația
                    de protecție a consumatorilor.
                </p>
            </section>
        </div>
    </div>
@endsection
