@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto py-10">

        <h1 class="text-2xl font-bold mb-6">
            Localități grupate pentru județul {{ strtoupper($county) }}
        </h1>

        <div class="flex items-center gap-4 mb-6">
            <select name="county" class="border rounded p-2 w-48"></select>

            <select id="localitySelect" class="border rounded p-2 w-full max-w-md">
                <option>Loading...</option>
            </select>
        </div>

        <div id="content"></div>
    </div>

    <script>
        const currentCounty = "{{ strtoupper($county) }}";

        // 🔥 FETCH JUDEȚE + POPULARE SELECT
        fetch('/api/counties')
            .then(res => res.json())
            .then(response => {

                const counties = response.data; // 🔥 AICI ERA PROBLEMA

                const countySelect = document.querySelector('select[name="county"]');
                let html = '<option value="">Alege județul...</option>';

                counties.forEach(c => {
                    html += `<option value="${c.abbr}" ${c.abbr === currentCounty ? 'selected' : ''}>
                        ${c.name}
                     </option>`;
                });

                countySelect.innerHTML = html;

                countySelect.addEventListener('change', function() {
                    if (this.value) {
                        window.location.href = `/view/counties/${this.value}/localities-grouped`;
                    }
                });
            });


        // 🔥 FETCH LOCALITĂȚI + SELECT + TABEL
        fetch(`/api/counties/{{ $county }}/localities-grouped`)
            .then(res => res.json())
            .then(groups => {

                // --- SELECT LOCALITĂȚI ---
                const buildSelectOptions = (groups) => {
                    let options = '<option value="">Alege localitatea...</option>';

                    const add = (items) => {
                        items.forEach(i => {
                            const label = i.parent && i.name !== i.parent.name ?
                                `${i.name} (${i.parent.name})` :
                                i.name;

                            options += `<option value="${label}">${label}</option>`;
                        });
                    };

                    add(groups.municipii);
                    add(groups.orase);
                    add(groups.comune);
                    add(groups.sate);

                    return options;
                };

                document.getElementById('localitySelect').innerHTML = buildSelectOptions(groups);

                // --- TABELE ---
                const renderTable = (title, items) => `
            <h2 class="text-xl font-bold mt-6 mb-2">${title}</h2>
            <table class="w-full border text-left mb-4">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="p-2 border w-20">SIRUTA</th>
                        <th class="p-2 border">Nume</th>
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
                                                ${i.parent && i.name !== i.parent.name
                                                    ? ` <span class="text-gray-500 text-sm">(${i.parent.name})</span>`
                                                    : ''
                                                }
                                            </td>
                                            <td class="p-2 border text-center">${i.postal_code && i.postal_code !== "000000" ? i.postal_code : "-"}</td>
                                            <td class="p-2 border text-center">
                                                ${i.lat && i.lng
                                                    ? `<a href="https://www.google.com/maps?q=${i.lat},${i.lng}" 
                                        target="_blank" class="text-blue-600">📍</a>`
                                                    : `-`
                                                }
                                            </td>
                                        </tr>
                                    `).join('')}
                </tbody>
            </table>
        `;

                let html = '';
                html += renderTable("Municipii", groups.municipii);
                html += renderTable("Orașe", groups.orase);
                html += renderTable("Comune", groups.comune);
                html += renderTable("Sate", groups.sate);

                document.getElementById('content').innerHTML = html;
            });
    </script>
@endsection
