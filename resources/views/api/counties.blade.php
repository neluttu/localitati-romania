@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto py-10">
        <h1 class="text-2xl font-bold mb-4">Lista Județelor</h1>
        <p class="text-gray-600 mb-4">Total județe: <span id="count"></span></p>
        <table class="w-full border text-left">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-2 border">ID</th>
                    <th class="p-2 border">Nume</th>
                    <th class="p-2 border">Abbr</th>
                    <th class="p-2 border">Cod</th>
                    <th class="p-2 border">Siruta</th>
                    <th class="p-2 border">Vezi Localități</th>
                </tr>
            </thead>
            <tbody id="counties-table"></tbody>
        </table>
    </div>

    <script>
        fetch('/api/v1/counties')
            .then(res => res.json())
            .then(data => {
                let html = '';
                document.getElementById('count').innerText = data.data.length;
                data.data.forEach(c => {
                    html += `
                <tr>
                    <td class="p-2 border">${c.id}</td>
                    <td class="p-2 border">${c.name}</td>
                    <td class="p-2 border">${c.abbr}</td>
                    <td class="p-2 border">${c.code}</td>
                    <td class="p-2 border">${c.siruta_code}</td>
                    <td class="p-2 border">
                        <a class="text-blue-600 underline" href="/view/counties/${c.abbr}/localities-grouped">Localități</a>
                    </td>
                </tr>
            `;
                });
                document.getElementById('counties-table').innerHTML = html;
            });
    </script>
@endsection
