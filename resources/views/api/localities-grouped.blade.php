@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto py-10">
        <h1 class="text-2xl font-bold mb-6">Localități grupate pentru județul {{ strtoupper($county) }}</h1>
        <div id="content"></div>
    </div>

    <script>
        fetch(`/api/counties/{{ $county }}/localities-grouped`)
            .then(res => res.json())
            .then(groups => {
                let html = '';

                const renderTable = (title, items) => {
                    return `
        <h2 class="text-xl font-bold mt-6 mb-2">${title}</h2>
        <table class="w-full border text-left mb-4">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border w-20">ID</th>
                    <th class="p-2 border">Nume</th>
                    <th class="p-2 border w-20 text-center">Maps</th>
                </tr>
            </thead>
            <tbody>
                ${items.map(i => `
                        <tr>
                            <td class="p-2 border">${i.id}</td>
                            <td class="p-2 border">${i.name}</td>
                            <td class="p-2 border text-center">
                                ${i.lat && i.lng 
                                    ? `<a href="https://www.google.com/maps?q=${i.lat},${i.lng}" target="_blank" class="text-blue-600" title="Deschide în Google Maps">
                                       📍
                                   </a>`
                                    : `-`
                                }
                            </td>
                        </tr>
                    `).join('')}
            </tbody>
        </table>
    `;
                };


                html += renderTable("✔ Municipii", groups.municipii);
                html += renderTable("✔ Orașe", groups.orase);
                html += renderTable("✔ Comune", groups.comune);
                html += renderTable("✔ Sate", groups.sate);

                document.getElementById('content').innerHTML = html;
            });
    </script>
@endsection
