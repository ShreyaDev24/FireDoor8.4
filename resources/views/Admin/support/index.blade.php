@extends('layouts.Master')

@section('main_section')
    <div class="app-main__outer">

        <div class="app-main__inner">

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="card-header">
                                <h5 class="card-title">Support Ticket List</h5>
                            </div>
                        </div>

                    </div>
                    <hr>
                    <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                        <thead class="text-uppercase ">
                            <tr>
                                <th>ID</th>
                                <th>User</th>
                                <th>Contact E-mail</th>
                                <th>Subject</th>
                                <th>Status</th>
                                <th>Created</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tickets as $ticket)
                                <tr>
                                    <td>#{{ $ticket->id }}</td>
                                    <td>{{ $ticket->user->FirstName }} {{ $ticket->user->LastName }}</td>
                                    <td>{{ $ticket->contact_email }}</td>
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
                </div>
            </div>
        </div>
    </div>
@endsection
