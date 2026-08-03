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
                    <strong>siruta.ro este un proiect personal, non-profit.</strong> Este dezvoltat și întreținut de o
                    singură persoană, în timpul liber. Nu este operat de o societate comercială, nu urmărește obținerea
                    de profit și nu percepe niciun tarif - accesul este și rămâne gratuit. Menționăm asta nu ca detaliu
                    de context, ci pentru că este premisa pe care se sprijină restul acestor termeni, în special
                    limitele de disponibilitate de la punctul 4.
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
                <h2 class="text-xl font-semibold text-gray-900 mb-3">4. Disponibilitate: ce nu îți putem garanta</h2>
                <p>
                    Serviciul este oferit <strong>„ca atare" și „în funcție de disponibilitate"</strong>, fără nicio
                    garanție de funcționare neîntreruptă și fără un acord privind nivelul serviciului (SLA). Nu
                    garantăm un anumit grad de disponibilitate, un timp de răspuns, o capacitate minimă de apeluri
                    și nici păstrarea nemodificată a unui anumit set de endpointuri.
                </p>
                <p class="mt-3">Disponibilitatea depinde de factori pe care nu îi controlăm integral:</p>
                <ul class="list-disc pl-5 space-y-2 mt-2">
                    <li>resursele incluse în planul de găzduire - procesor, memorie, număr de procese simultane,
                        trafic lunar - și limitele impuse de furnizor;</li>
                    <li>numărul total de utilizatori și volumul de apeluri pe care îl generează, inclusiv creșteri
                        bruște pe care nu le putem anticipa;</li>
                    <li>disponibilitatea furnizorului de găzduire, a rețelei și a serviciilor de care depinde;</li>
                    <li>timpul pe care îl putem aloca proiectului, fiind o activitate desfășurată în timpul liber.</li>
                </ul>
                <p class="mt-3">
                    În consecință, ne rezervăm dreptul de a reduce limita de apeluri, de a dezactiva temporar
                    funcționalități, de a suspenda accesul unui utilizator al cărui consum afectează ceilalți
                    utilizatori, precum și de a întrerupe serviciul - parțial sau integral, temporar sau definitiv.
                    Vom anunța din timp o încetare definitivă în măsura în care ne stă în putință, dar nu ne putem
                    obliga la un preaviz în caz de forță majoră, de depășire a resurselor sau de decizie a
                    furnizorului de găzduire.
                </p>
                <p class="mt-3">
                    Depunem eforturi rezonabile ca datele să fie corecte și actualizate, dar nu garantăm că sunt
                    lipsite de erori. Verifică datele critice înainte să te bazezi pe ele în decizii importante.
                </p>
                <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50/60 p-4">
                    <p class="text-sm text-amber-900">
                        <strong>Recomandare practică:</strong> dacă integrezi API-ul într-un produs de care depinzi,
                        păstrează o copie locală a datelor de care ai nevoie. Nomenclatorul SIRUTA se modifică rar,
                        iar o copie proprie te protejează complet de o eventuală indisponibilitate a serviciului.
                    </p>
                </div>
            </section>

            <section>
                <h2 class="text-xl font-semibold text-gray-900 mb-3">5. Limitarea răspunderii</h2>
                <p>
                    Serviciul fiind gratuit și fără scop lucrativ, răspunderea este limitată la maximul permis de
                    lege. Nu răspundem pentru pierderi directe sau indirecte, pierderi de date, de profit ori de
                    oportunitate, nici pentru costuri de înlocuire, rezultate din folosirea serviciului, din
                    imposibilitatea de a-l folosi, din întreruperea sau încetarea lui, ori din erori în date.
                </p>
                <p class="mt-3">
                    Nimic din acești termeni nu limitează răspunderea în situațiile în care legea nu permite acest
                    lucru, iar dacă ești consumator, îți păstrezi drepturile prevăzute de legislația de protecție a
                    consumatorilor.
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
