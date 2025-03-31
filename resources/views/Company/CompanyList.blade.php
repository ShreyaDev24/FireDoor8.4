@extends("layouts.Master")

@section("main_section")

<div class="app-main__outer">
    <div class="app-main__inner">
        @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Alert!</h5>
            {{ session()->get('success') }}
        </div>
        @endif
        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card-header"><h5 class="card-title">Company List</h5></div>
                    </div>
                    <div class="col-sm-6 ">
                        <a href="{{route('company/add')}}" class="btn-shadow btn btn-info float-right">
                            Add New
                        </a>
                    </div>
                </div>
                <hr>
                
                <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                    <thead class="text-uppercase table-header-bg">
                        <tr class="text-white">
                            <th>Company Name</th>
                            <th>Contact</th>
                            <th>Phone</th>
                            <th>E-Mail</th>
                            <th>Address</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>                       
                        @foreach($data as $row)
                        <tr>
                            <td> <a href="{{url('company/details/'.$row->id)}}">{{{$row->CompanyName}}}</a></td>
                            <td>{{$row->FirstName}} {{$row->LastName}}</td>
                            <td>{{$row->CompanyPhone}}</td>
                            <td>{{$row->UserEmail}}</td>
                            <td>{{$row->CompanyAddressLine1}} </td>
                            <td style="width: 100px">
                                <a href="{{url('company/edit/'.$row->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                                <a href="javascript:void(0);" onClick="deleteCompany({{$row->UserId}});" class="btn btn-danger"><i class="fa fa-trash"></i></a>
                            </td>
                        </tr>                       
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>
    </div>
    <button style="display: none;" type="button" id="success-alert" data-type="success"
        class="btn btn-success btn-show-swal"></button>
    <form action="{{route('deleteCompany')}}" method="post" id="deleteCompany">
        {{ csrf_field() }}
        <input type="hidden" name="companyId" id="companyId">
    </form>
    @endsection

    @section('js')
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            function deleteCompany(companyId){
                var r = confirm("Are you sure! you wan't to delete company. If you deleted company it delete all other data which is related to company and it's not revert process.");
                if (r == true) {
                    $('#companyId').val(companyId);
                    $('#deleteCompany').submit();
                }
            }
        </script>





    @if(session()->has('updated'))
    <script type="text/javascript">
    swal(
        'Success',
        'Company updated Succesfully!',
        'success'
    )

    
    </script>
    @endif

    @if(session()->has('added'))
    <script type="text/javascript">
    swal(
        'Success',
        'Company added Succesfully!',
        'success'
    )
    </script>
    @endif
    @endsection