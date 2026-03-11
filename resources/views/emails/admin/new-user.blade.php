<h2>Utilizator nou înregistrat</h2>

<p>Un utilizator nou s-a înregistrat pe {{ config('app.name') }}.</p>

<table>
    <tr>
        <td><strong>Email:</strong></td>
        <td>{{ $user->email }}</td>
    </tr>
    <tr>
        <td><strong>Nume:</strong></td>
        <td>{{ $user->fullName() }}</td>
    </tr>
    <tr>
        <td><strong>Data:</strong></td>
        <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
    </tr>
</table>
