<h2>New Support Ticket Created</h2>

<p><strong>User:</strong> {{ $ticket->user->FirstName }} {{ $ticket->user->LastName }}</p>
<p><strong>Email:</strong> {{ $ticket->user->UserEmail }}</p>
<p><strong>Subject:</strong> {{ $ticket->subject }}</p>
<p><strong>Priority:</strong> {{ ucfirst($ticket->priority) }}</p>

<p><strong>Message:</strong></p>
<p>{{ $ticket->message }}</p>

@if($ticket->attachment)
    <a href="{{ asset('ticket_attachments/'.$ticket->attachment) }}" target="_blank">
        View Attachment
    </a>
@endif

<p>
    <a href="{{ url('/admin/support/'.$ticket->id) }}">
        View Ticket
    </a>
</p>
