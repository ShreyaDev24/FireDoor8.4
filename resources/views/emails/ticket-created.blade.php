<h2>New Support Ticket Created</h2>

<p><strong>User:</strong> {{ $ticket->user->name }}</p>
<p><strong>Email:</strong> {{ $ticket->user->email }}</p>
<p><strong>Subject:</strong> {{ $ticket->subject }}</p>
<p><strong>Priority:</strong> {{ ucfirst($ticket->priority) }}</p>

<p><strong>Message:</strong></p>
<p>{{ $ticket->message }}</p>

<p>
    <a href="{{ url('/admin/support/'.$ticket->id) }}">
        View Ticket
    </a>
</p>
