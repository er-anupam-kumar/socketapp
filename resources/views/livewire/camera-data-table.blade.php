<div wire:poll>
    <h1>Camera Data</h1>
    <table border="1">
        <thead>
            <tr>
                <th>ID</th>
                <th>Data</th>
                <th>Timestamp</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $entry)
            <tr>
                <td>{{ $entry->id }}</td>
                <td>{{ $entry->data }}</td>
                <td>{{ $entry->created_at }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
