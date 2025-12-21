@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto py-10">

        <h1 class="text-2xl font-bold mb-6">
            Localități grupate pentru județul {{ strtoupper($county) }}
        </h1>
        <p class="text-gray-600 mb-4">Total localități: <span id="count"></span></p>

        <div class="flex items-center gap-4 mb-6">
            <select name="county" class="border rounded p-2 w-48"></select>

            <select id="localitySelect" class="border rounded p-2 w-full max-w-md">
                <option>Loading...</option>
            </select>
        </div>

        <div id="content"></div>
    </div>

    <script>
        const countySelect = document.querySelector('select[name="county"]');
        const localitySelect = document.getElementById('localitySelect');
        const countSpan = document.getElementById('count');
        const contentDiv = document.getElementById('content');

        let currentCounty = null;

        // ------------------------------
        // 1. FETCH JUDEȚE + POPULARE SELECT
        // ------------------------------
        fetch('/v1/counties')
            .then(res => res.json())
            .then(response => {
                const counties = response.data;

                let html = '<option value="">Alege județul...</option>';

                counties.forEach(c => {
                    html += `<option value="${c.abbr}">${c.name}</option>`;
                });

                countySelect.innerHTML = html;

                // setăm implicit primul județ
                if (counties.length > 0) {
                    currentCounty = counties[0].abbr;
                    countySelect.value = currentCounty;
                    loadLocalities(currentCounty);
                }
            })
            .catch(err => {
                console.error('Eroare la încărcarea județelor', err);
            });

        // ------------------------------
        // 2. LOAD LOCALITIES
        // ------------------------------
        function loadLocalities(county) {
            if (!county) return;

            localitySelect.innerHTML = '<option>Loading...</option>';
            contentDiv.innerHTML = '<p class="text-gray-600">Loading data...</p>';

            fetch(`/v1/counties/${county}/localities`)
                .then(res => res.json())
                .then(response => {
                    const items = response.data;

                    // COUNT
                    countSpan.innerText = items.length;

                    // ------------------------------
                    // SELECT LOCALITĂȚI (OPTGROUP)
                    // ------------------------------
                    let options = '<option value="">Alege localitatea...</option>';

                    const groups = {
                        localitati: [],
                        sate: [],
                        sectoare: [],
                    };

                    const order = ['localitati', 'sate', 'sectoare'];

                    items.forEach(i => {
                        const label = i.parent ?
                            `${i.name} (${i.parent.name})` :
                            i.name;

                        const group = i.type_group;

                        if (!groups[group]) {
                            groups[group] = [];
                        }

                        groups[group].push(
                            `<option value="${i.id}">${label}</option>`
                        );
                    });

                    order.forEach(group => {
                        const opts = groups[group];
                        if (opts && opts.length) {
                            options += `
                            <optgroup label="${group}" class="capitalize">
                                ${opts.join('')}
                            </optgroup>
                        `;
                        }
                    });

                    localitySelect.innerHTML = options;

                    // ------------------------------
                    // TABLE
                    // ------------------------------
                    contentDiv.innerHTML = renderTable(items);
                })
                .catch(err => {
                    console.error('Eroare la încărcarea localităților', err);
                    contentDiv.innerHTML = '<p class="text-red-600">Eroare la încărcare.</p>';
                });
        }

        // ------------------------------
        // 3. TABLE RENDER
        // ------------------------------
        function renderTable(items) {
            return `
            <table class="w-full border text-left mb-4">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border w-24">SIRUTA</th>
                        <th class="p-2 border">Localitate</th>
                        <th class="p-2 border w-40 text-center">Cod poștal</th>
                        <th class="p-2 border w-20 text-center">Maps</th>
                    </tr>
                </thead>
                <tbody>
                    ${items.map(i => `
                            <tr>
                                <td class="p-2 border">${i.siruta_code}</td>
                                <td class="p-2 border">
                                    ${i.name}
                                    <span class="text-gray-500 text-sm">
                                        (${i.type_label})
                                    </span>
                                    ${i.parent
                                        ? `<span class="text-gray-500 text-sm"> – ${i.parent.name}</span>`
                                        : ''
                                    }
                                </td>
                                <td class="p-2 border text-center">
                                    ${i.postal_code ? i.postal_code : '-'}
                                </td>
                                <td class="p-2 border text-center">
                                    ${i.lat && i.lng
                                        ? `<a href="https://www.google.com/maps?q=${i.lat},${i.lng}" target="_blank">📍</a>`
                                        : '-'
                                    }
                                </td>
                            </tr>
                        `).join('')}
                </tbody>
            </table>
        `;
        }

        // ------------------------------
        // 4. CHANGE JUDEȚ
        // ------------------------------
        countySelect.addEventListener('change', function() {
            currentCounty = this.value;
            loadLocalities(currentCounty);
        });
    </script>
@endsection
