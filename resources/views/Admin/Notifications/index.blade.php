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
                    <div class="col-sm-6 ">
                        <a href="{{ route('admin.notifications.create') }}"  class="btn-shadow btn btn-info float-right">Send Notification</a>
                    </div>
                </div>
                <hr>

                <div class="table-responsive">
                    <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                        <thead class="text-uppercase table-header-bg">
                            <tr class="text-white">
                                <th>Title</th>
                                <th>Message</th>
                                <th>Created At</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                           @forelse($notifications as $note)
                            <tr>
                                <td>{{ $note->title }}</td>
                                <td>{{ $note->message }} </td>
                                {{-- <td>{{ $note->created_at->format('d M Y') }}</td> --}}
                                <td>{{ $note->created_at->format('d M Y') }}</td>
                                <td class="text-center">


                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="text-center">No Notification found.</td>
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
