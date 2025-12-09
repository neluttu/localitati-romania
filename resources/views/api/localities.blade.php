@extends('layouts.app')

@section('content')
    <div class="max-w-5xl mx-auto py-10">
        <h1 class="text-2xl font-bold mb-4">Localități pentru județul {{ strtoupper($county) }}</h1>

        <table class="w-full border text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">ID</th>
                    <th class="p-2 border">Nume</th>
                    <th class="p-2 border">Tip</th>
                    <th class="p-2 border">Cod Poștal</th>
                    <th class="p-2 border">Lat</th>
                    <th class="p-2 border">Lng</th>
                </tr>
            </thead>
            <tbody id="localities-table"></tbody>
        </table>
    </div>

    <script>
        fetch(`/api/counties/{{ $county }}/localities`)
            .then(res => res.json())
            .then(data => {
                let html = '';
                data.data.forEach(l => {
                    html += `
                <tr>
                    <td class="p-2 border">${l.id}</td>
                    <td class="p-2 border">${l.name}</td>
                    <td class="p-2 border">${l.type}</td>
                    <td class="p-2 border">${l.postal_code ?? ''}</td>
                    <td class="p-2 border">${l.lat ?? ''}</td>
                    <td class="p-2 border">${l.lng ?? ''}</td>
                </tr>
            `;
                });
                document.getElementById('localities-table').innerHTML = html;
            });
    </script>
@endsection
