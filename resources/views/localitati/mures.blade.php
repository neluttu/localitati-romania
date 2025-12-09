@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-6">

        <h1 class="text-2xl font-bold mb-4">
            Localitățile din județul Mureș ({{ $localities->count() }} total)
        </h1>

        <table class="w-full border-collapse border border-gray-300 text-sm">
            <thead>
            <tr class="bg-gray-100">
                <th class="border p-2">Nume</th>
                <th class="border p-2">Rank</th>
                <th class="border p-2">Tip</th>
                <th class="border p-2">SIRUTA</th>
                <th class="border p-2">Lat</th>
                <th class="border p-2">Lng</th>
            </tr>
            </thead>

            <tbody>
            @foreach ($localities as $loc)
                <tr class="hover:bg-gray-50">
                    <td class="border p-2 font-medium">{{ $loc->name }}</td>
                    <td class="border p-2">{{ $loc->rank ?? '-' }}</td>
                    <td class="border p-2">{{ $loc->type_label ?? '-' }}</td>
                    <td class="border p-2">{{ $loc->siruta ?? '-' }}</td>
                    <td class="border p-2">{{ $loc->lat }}</td>
                    <td class="border p-2">{{ $loc->lng }}</td>
                </tr>
            @endforeach
            </tbody>
        </table>

    </div>
@endsection
