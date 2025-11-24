<h2>Monitoring Guru</h2>

<table border="1" cellpadding="6">
    <tr>
        <th>Nama</th>
        <th>Email</th>
        <th>Role</th>
    </tr>

    @foreach ($guru as $g)
        <tr>
            <td>{{ $g->name }}</td>
            <td>{{ $g->email }}</td>
            <td>{{ $g->role }}</td>
        </tr>
    @endforeach
</table>
