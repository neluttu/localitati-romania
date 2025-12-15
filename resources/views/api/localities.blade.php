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
        let currentCounty = "{{ strtoupper($county) }}";

        const countySelect = document.querySelector('select[name="county"]');
        const localitySelect = document.getElementById('localitySelect');
        const countSpan = document.getElementById('count');
        const contentDiv = document.getElementById('content');

        // ------------------------------
        // 1. FETCH JUDEȚE + POPULARE SELECT
        // ------------------------------
        fetch('/v1/counties')
            .then(res => res.json())
            .then(response => {
                const counties = response.data;
                let html = '<option value="">Alege județul...</option>';

                counties.forEach(c => {
                    html += `<option value="${c.code}" ${c.code === currentCounty ? 'selected' : ''}>
                ${c.name}
            </option>`;
                });

                countySelect.innerHTML = html;
            });


        // ------------------------------
        // 2. LOAD LOCALITIES FUNCTION
        // ------------------------------
        function loadLocalities(county) {
            if (!county) return;

            localitySelect.innerHTML = `<option>Loading...</option>`;
            contentDiv.innerHTML = `<p class="text-gray-600">Loading data...</p>`;
            fetch(`/v1/counties/${county}/localities`)
                .then(res => res.json())
                .then(response => {

                    const items = response.data;

                    // ------------------------------
                    // COUNT SIMPLU
                    // ------------------------------
                    countSpan.innerText = items.length;
                    // ------------------------------
                    // SELECT LOCALITĂȚI (CU OPTGROUP)
                    // ------------------------------
                    let options = '<option value="">Alege localitatea...</option>';

                    const groups = {
                        'Sector': [],
                        'Oraș': [],
                        'Localitate': [],
                        'Municipiu': [],
                        'Sat': [],
                    };

                    const order = [
                        'Municipiu',
                        'Oraș',
                        'Sector',
                        'Sat',
                        'Localitate',
                    ];


                    items.forEach(i => {
                        const label = i.parent && i.name !== i.parent.name ?
                            `${i.name} (${i.parent.name})` :
                            i.name;

                        const group = i.type_label || 'Localitate';

                        if (!groups[group]) {
                            groups[group] = [];
                        }

                        groups[group].push(`<option value="${label}">${label}</option>`);
                    });

                    order.forEach(group => {
                        const opts = groups[group];
                        if (opts && opts.length > 0) {
                            options += `<optgroup label="${group}">${opts.join('')}</optgroup>`;
                        }
                    });


                    localitySelect.innerHTML = options;

                    // ------------------------------
                    // TABLE RENDER (SINGURĂ TABELĂ)
                    // ------------------------------
                    const renderTable = (items) => `
            <table class="w-full border text-left mb-4">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border w-20">SIRUTA</th>
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
                                                                            ${i.name} (${i.type_label} - ${i.type})
                                                                            ${i.parent && i.name !== i.parent.name
                                                                                ? ` <span class="text-gray-500 text-sm">(${i.parent.name})</span>`
                                                                                : ''
                                                                            }
                                                                        </td>
                                                                        <td class="p-2 border text-center">
                                                                            ${i.postal_code && i.postal_code !== "000000" ? i.postal_code : "-"}
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

                    contentDiv.innerHTML = renderTable(items);
                });

        }


        // ------------------------------
        // 3. SCHIMBARE JUDEȚ
        // ------------------------------
        countySelect.addEventListener('change', function() {
            currentCounty = this.value;
            loadLocalities(currentCounty);
        });

        // ------------------------------
        // 4. LOAD INITIAL
        // ------------------------------
        loadLocalities(currentCounty);
    </script>
@endsection
