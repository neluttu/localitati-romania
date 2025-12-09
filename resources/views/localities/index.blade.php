@extends('layouts.app')

@section('content')
    <div class="container mx-auto py-6">


        <table class="w-full border-collapse border border-gray-300 text-sm">
            <thead>
                <tr class="bg-gray-100">
                    <th class="border p-2">ID</th>
                    <th class="border p-2">Nume</th>
                    <th class="border p-2">Județ</th>
                    <th class="border p-2">Tip</th>
                    <th class="border p-2">Rank</th>
                    <th class="border p-2">Siruta</th>
                    <th class="border p-2">Population</th>
                    <th class="border p-2">Lat</th>
                    <th class="border p-2">Lng</th>
                    <th class="border p-2">Parent</th>
                    <th class="border p-2">Region</th>
                </tr>
            </thead>

            <tbody>
                @foreach ($localities as $loc)
                    <tr class="hover:bg-gray-50">
                        <td class="border p-2">{{ $loc->id }}</td>
                        <td class="border p-2">{{ $loc->name }}</td>
                        <td class="border p-2">{{ $loc->county->name ?? '-' }}</td>
                        <td class="border p-2">{{ $loc->type_label ?? $loc->type }}</td>
                        <td class="border p-2">{{ $loc->rank ?? '-' }}</td>
                        <td class="border p-2">{{ $loc->siruta ?? '-' }}</td>
                        <td class="border p-2">{{ $loc->population ?? '-' }}</td>
                        <td class="border p-2">{{ $loc->lat }}</td>
                        <td class="border p-2">{{ $loc->lng }}</td>
                        <td class="border p-2">{{ $loc->parent_locality ?? '-' }}</td>
                        <td class="border p-2">{{ $loc->region ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>



    </div>
@endsection
