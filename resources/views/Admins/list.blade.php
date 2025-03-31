@extends('layouts.Master')

@section('main_section')
    <div class="app-main__outer">

        <div class="app-main__inner">

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-6">
                            <div class="card-header">
                                <h5 class="card-title">Admin List</h5>
                            </div>
                        </div>
                        <div class="col-sm-6 ">

                            @if (Auth::user()->UserType == '2')
                                <a href="{{ route('admins/add') }}" class="btn-shadow btn btn-info float-right">
                                    Add New
                                </a>
                            @endif
                        </div>
                    </div>
                    <hr>
                    <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                        <thead class="text-uppercase table-header-bg">
                            <tr class="text-white">
                                <th>FirstName</th>
                                <th>LastName</th>
                                <th>Phone</th>
                                <th>Email</th>
                                @if (Auth::user()->UserType == '2')
                                    <th>Action</th>
                                @endif

                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($data as $row)
                                <tr>

                                    <td><a href="{{ url('survey/details/' . $row->id) }}">{{ $row->FirstName }}</a></td>
                                    <td>{{ $row->LastName }}</td>
                                    <td>{{ $row->UserPhone }}</td>
                                    <td>{{ $row->UserEmail }}</td>

                                    @if (Auth::user()->UserType == '2')
                                        <td>
                                            <a href="{{ url('admins/edit/' . $row->id) }}"><i
                                                    class="fa fa-pencil fa-lg"></i></a>

                                            <a href="{{ url('admins/details/' . $row->id) }}"><i
                                                    class="fa fa-eye fa-lg"></i></a>
                                            <a class="survey_delete" data-id="{{ $row->id }}"><i
                                                    class="fa fa-trash fa-lg" style="color: red"></i></a>

                                        </td>
                                    @endif

                                </tr>
                            @endforeach
                        </tbody>

                    </table>
                </div>
            </div>
        </div>
        <button style="display: none;" type="button" id="success-alert" data-type="success"
            class="btn btn-success btn-show-swal"></button>
    @endsection

    @section('js')
        @if (session()->has('updated'))
            <script type="text/javascript">
                swal(
                    'Success',
                    'User Updated the <b style="color:green;">Success</b>!',
                    'success'
                )
            </script>
        @endif

        @if (session()->has('added'))
            <script type="text/javascript">
                swal(
                    'Success',
                    'User Added <b style="color:green;">Success</b>!',
                    'success'
                )
            </script>
        @endif
    @endsection

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="https://code.jquery.com/jquery-2.1.3.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert-dev.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/1.1.3/sweetalert.css">
    <script>
        $(document).ready(function() {
            function sweetConfirm(title, message, callback) {
                swal({
                    title: title,
                    text: message,
                    icon: 'warning',
                    showCancelButton: true
                }).then((confirmed) => {
                    callback(confirmed && confirmed.value == true);
                });
            }
            $(".survey_delete").click(function() {

                var delete_id = $(this).data('id');

                sweetConfirm('Are you sure?', 'You will not be able to revert this!', function(confirmed) {
                    if (confirmed) {
                        // YES
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            url: "{{ url('admins/delete') }}",
                            method: "POST",
                            dataType: "Json",
                            data: {
                                id: delete_id,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(result) {
                                console.log(result)
                                if (result.status = "ok") {
                                    alert('Data Deleted successfully.');
                                    // sweetConfirm('Are you sure?', 'You will not be able to revert this!', function(confirmed)
                                    location.reload();
                                } else {
                                    alert(result.msg);
                                }
                            }
                        });
                    }
                });
            });


            $(".survey_status_change").on('click', function() {

                var statusChangeId = $(this).data('id');
                var status = $(this).data('value');

                sweetConfirm('Are you sure?', 'Do you want to change survey user status?', function(
                    confirmed) {
                    if (confirmed) {
                        // YES
                        $.ajax({
                            headers: {
                                'X-CSRF-TOKEN': "{{ csrf_token() }}"
                            },
                            url: "{{ url('survey/status-change') }}",
                            method: "POST",
                            dataType: "Json",
                            data: {
                                id: statusChangeId,
                                status: status,
                                _token: "{{ csrf_token() }}"
                            },
                            success: function(result) {
                                console.log(result)
                                if (result.status = "ok") {
                                    alert('User Status change successfully.');
                                    // sweetConfirm('Are you sure?', 'You will not be able to revert this!', function(confirmed)
                                    location.reload();
                                } else {
                                    alert(result.msg);
                                }
                            }
                        });
                    }
                });
            });


        });
    </script>
