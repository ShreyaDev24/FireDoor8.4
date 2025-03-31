@extends("layouts.Master")

@section("main_section")
<style>
    .dropdown-div{
    left: -48px !important;
    
    }
    .dropdown-menu {
        min-width: 6rem !important;
        margin-right: -110px !important;
        margin-left: 50px !important;
        margin-top: 5px !important;
    }
    .dropdown a:hover {
    background-color: #008fe1 !important;
}
</style>

     <div class="app-main__outer">

                <div class="app-main__inner">

                    <div class="main-card mb-3 card">
                        <div class="card-body">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card-header"><h5 class="card-title">User List</h5></div>
                            </div>
                            <div class="col-sm-6 ">

                            @if (Auth::user()->UserType=="2")
                                    <!-- <a  href="{{route('user/add')}}"  class="btn-shadow btn btn-info float-right">
                                        Add New
                                    </a> -->
                                    <div class="dropdown">
                                    <button type="button" class="btn-shadow btn btn-info float-right dropdown-toggle" data-toggle="dropdown">
                                        Add New
                                    </button>
                                    <div class="dropdown-menu dropdown-div">
                                        <a class="dropdown-item" href="{{route('admins/add')}}">Admin</a>
                                        <a class="dropdown-item" href="{{route('user/add')}}">User</a>
                                    </div>
                                    </div>

                                    @endif
                            </div>
                            </div>
                        <hr>
                            <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                            <thead class="text-uppercase table-header-bg">
                                <tr class="text-white">
                                    @if(Auth::user()->UserType=='1')
                                    <th>Company Name</th>
                                    @endif
                                    <th>FirstName</th>
                                    <th>LastName</th>
                                    <th>Phone</th>
                                    <th>Email</th>
                                    <th>Type</th>
                                    @if (Auth::user()->UserType=="2" || Auth::user()->UserType=="3")
                                    <th>Action</th>
                                    @endif

                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $row)
                                
                                <tr>
                                    @if(Auth::user()->UserType=='1')
                                    <td><a href="{{url('company/details/'.$row->comId)}}">{{$row->CompanyName}}</a></td>
                                    @endif
                                    <td><a href="{{url('user/details/'.$row->id)}}">{{$row->FirstName}}</a></td>
                                    <td>{{$row->LastName}}</td>
                                    <td>{{$row->UserPhone}}</td>
                                    <td>{{$row->UserEmail}}</td>
                                    <td>@if($row->UserType=="2")
                                      Admin
                                      @elseif($row->UserType=="3")
                                      User
                                      @endif

                                    </td>



                                    @if (Auth::user()->UserType=="2" )
                                    <td>
                                    @if($row->UserType=="2" && Auth::user()->id != $row->id )
                                    <a href="{{ url('admins/edit/' . $row->id) }}"><i
                                                    class="fa fa-pencil fa-lg"></i></a>

                                            <!-- <a href="{{ url('admins/details/' . $row->id) }}"><i
                                                    class="fa fa-eye fa-lg"></i></a> -->
                                            <a class="user_delete" data-id="{{ $row->id }}"><i
                                                    class="fa fa-trash fa-lg" style="color: red"></i></a>
                                      @elseif($row->UserType=="3")
                                      <a href="{{url('user/edit/'.$row->id)}}"><i class="fa fa-pencil"></i></a>
                                        <a class="user_delete" data-id="{{ $row->id }}"><i
                                            class="fa fa-trash fa-lg" style="color: red"></i></a>
                                      @endif
                                        
                                    </td>

                                    @endif




                                </tr>
                                @endforeach
                                </tbody>

                            </table>
                        </div>
                    </div>
                </div>
    <button style="display: none;" type="button" id="success-alert" data-type="success" class="btn btn-success btn-show-swal"></button>

@endsection

@section('js')
   @if(session()->has('updated'))
    <script type="text/javascript">
            swal(
                'Success',
                'User Updated the <b style="color:green;">Success</b>!',
                'success'
            )
    </script>
@endif

 @if(session()->has('added'))
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

        $(".user_delete").click(function() {
            var delete_id = $(this).data('id');

            sweetConfirm('Are you sure?', 'You will not be able to revert this!', function(confirmed) {
                if (confirmed) {
                    // YES
                    $.ajax({
                        headers: {
                            'X-CSRF-TOKEN': "{{ csrf_token() }}"
                        },
                        url: "{{ url('user/delete') }}",
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
    })
</script>


