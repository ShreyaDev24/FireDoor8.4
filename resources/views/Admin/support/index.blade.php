<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>User</th>
            <th>Subject</th>
            <th>Status</th>
            <th>Created</th>
        </tr>
    </thead>
    <tbody>
        @foreach($tickets as $ticket)
            <tr>
                <td>#{{ $ticket->id }}</td>
                <td>{{ $ticket->user->name }}</td>
                <td>
                    <a href="/admin/support/{{ $ticket->id }}">
                        {{ $ticket->subject }}
                    </a>
                </td>
                <td>{{ ucfirst($ticket->status) }}</td>
                <td>{{ $ticket->created_at->format('d M Y') }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
