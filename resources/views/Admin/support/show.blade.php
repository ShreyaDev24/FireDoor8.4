@extends('layouts.Master')

@section('main_section')
<div class="container-fluid">
    <div class="row justify-content-center">
        <div class="col-xl-9 col-lg-10">

            <div class="card shadow-sm border-0">

                {{-- Header --}}
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">
                        <i class="fa fa-ticket ."></i>
                        Support Ticket #{{ $ticket->id }}
                    </h5>

                    <span class="badge badge-pill badge-success px-3 py-2">
                        {{ strtoupper($ticket->status ?? 'OPEN') }}
                    </span>
                </div>

                {{-- Body --}}
                <div class="card-body">

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <small class="text-muted">User</small>
                            <div class="font-weight-bold">
                                {{ $ticket->user->name ?? 'System User' }}
                            </div>
                        </div>

                        <div class="col-md-6 text-md-right">
                            <small class="text-muted">Created</small>
                            <div>
                                {{ $ticket->created_at->format('d M Y, h:i A') }}
                            </div>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <small class="text-muted">Subject</small>
                        <h6 class="mb-0">{{ $ticket->subject }}</h6>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <small class="text-muted">Category</small><br>
                            <span class="badge badge-info px-3 py-2">
                                {{ strtoupper($ticket->category) }}
                            </span>
                        </div>

                        <div class="col-md-6">
                            <small class="text-muted">Priority</small><br>
                            <span class="badge
                                @if($ticket->priority == 'high') badge-danger
                                @elseif($ticket->priority == 'medium') badge-warning
                                @else badge-success
                                @endif px-3 py-2">
                                {{ strtoupper($ticket->priority) }}
                            </span>
                        </div>
                    </div>

                    {{-- Message --}}
                    <div class="mb-4">
                        <small class="text-muted">Message</small>
                        <div class="bg-light border rounded p-4 mt-2">
                            {{ $ticket->message }}
                        </div>
                    </div>

                    {{-- Attachment --}}
                    <div class="mb-4">
                        <small class="text-muted">Attachment</small><br>

                        @if($ticket->attachment)
                            <a href="{{ asset('ticket_attachments/'.$ticket->attachment) }}"
                               target="_blank"
                               class="btn btn-outline-primary btn-sm mt-2">
                                <i class="fa fa-paperclip"></i> View Attachment
                            </a>
                        @else
                            <div class="text-muted mt-1">No attachment provided</div>
                        @endif
                    </div>

                </div>

                {{-- Footer --}}
                <div class="card-footer bg-white text-right">
                    <a href="{{ url('/admin/support') }}"
                       class="btn btn-secondary btn-sm">
                        <i class="fa fa-arrow-left"></i> Back to Tickets
                    </a>
                </div>

            </div>

        </div>
    </div>
</div>

@endsection
