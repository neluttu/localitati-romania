<h2>Site nou adăugat</h2>

<p>Un utilizator a adăugat un site nou pe {{ config('app.name') }}.</p>

<table>
    <tr>
        <td><strong>Utilizator:</strong></td>
        <td>{{ $site->user->email }}</td>
    </tr>
    <tr>
        <td><strong>Nume site:</strong></td>
        <td>{{ $site->name }}</td>
    </tr>
    <tr>
        <td><strong>Domeniu:</strong></td>
        <td>{{ $site->domain }}</td>
    </tr>
    <tr>
        <td><strong>Data:</strong></td>
        <td>{{ $site->created_at->format('d.m.Y H:i') }}</td>
    </tr>
</table>
