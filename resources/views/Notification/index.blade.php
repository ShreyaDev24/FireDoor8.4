@extends('layouts.Master')

@section('main_section')



<div class="app-main__outer">

    <div class="app-main__inner">

        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card-header">
                            <h5 class="card-title">Notification List</h5>
                        </div>
                    </div>
                    @if(Auth::user()->UserType==1)
                    <div class="col-sm-6 ">
                        <a href="{{ route('admin.notifications.create') }}"  class="btn-shadow btn btn-info float-right">Send Notification</a>
                    </div>
                    @endif
                </div>
                <hr>

                <div class="table-responsive">
                    <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                        <thead class="text-uppercase table-header-bg">
                            <tr class="text-white">
                                <th>#</th>
                                <th>Title</th>
                                <th>Message</th>
                                <th>Date</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           @forelse($notifications as $index => $note)
                            <tr class="{{ $note->is_read ? '' : 'table-warning' }}">
                                <td>{{ $index + 1 }}</td>

                                <td>
                                    <strong>{{ $note->title }}</strong>
                                </td>

                                <td>
                                    {{ $note->message }}
                                </td>

                                <td>
                                    {{ $note->created_at->format('d M Y, h:i A') }}
                                </td>

                                <td class="text-center">
                                    @if($note->is_read)
                                        <span class="badge bg-success">Read</span>
                                    @else
                                        <span class="badge bg-warning text-dark">Unread</span>
                                    @endif
                                </td>

                                <td class="text-center">
                                    @if(!$note->is_read)
                                        <form method="POST"
                                            action="{{ route('notifications.read', $note->id) }}"
                                            style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-primary">
                                                Mark as Read
                                            </button>
                                        </form>
                                    @endif

                                    @if(!empty($note->action_url))
                                        <a href="{{ $note->action_url }}"
                                        class="btn btn-sm btn-outline-secondary">
                                            View
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    No notifications found.
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>






{{-- ////////////////////////////////////////// --}}
    @if (session()->has('error'))
        <style type="text/css">
            #useremail {
                border-color: red
            }

        </style>
    @endif
   <h3>Notifications</h3>


<script>
document.querySelectorAll('.mark-read').forEach(btn => {
    btn.addEventListener('click', function () {
        fetch('/notifications/read/' + this.dataset.id, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        }).then(() => location.reload());
    });
});
</script>
@endsection
