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
                        <div class="card-header"><h5 class="card-title">Architect List</h5></div>
                    </div>
                    <div class="col-sm-6 ">
                        <a href="{{route('Architect/add')}}" class="btn-shadow btn btn-info float-right">
                            Add New
                        </a>
                    </div>
                </div>
                <hr>
                
                <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                    <thead class="text-uppercase table-header-bg">
                        <tr class="text-white">
                            <th> Architect Company Name</th>
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
                            <td> <a href="{{url('Architect/details/'.$row->id)}}">{{{$row->ArcCompanyName}}}</a></td>
                            <td>{{$row->FirstName}}</td>
                            <td>{{$row->ArcCompanyPhone}}</td>
                            <td>{{$row->ArcCompanyEmail}}</td>
                            <td>{{$row->ArcCompanyAddressLine1}} </td>
                            <td style="width: 100px">
                                <a href="{{url('Architect/edit/'.$row->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                                <a href="javascript:void(0);" onClick="deleteArchitect({{$row->id}});" class="btn btn-danger"><i class="fa fa-trash"></i></a>
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
    <form action="{{route('deleteArchitect')}}" method="post" id="deleteArchitec">
        {{ csrf_field() }}
        <input type="hidden" name="ArchitectId" value="5" id="ArchitectId">
        <!-- <button type="submit" style="background:none" id=""></button> -->
    </form>
    @endsection

    @section('js')
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            function deleteArchitect(ArchitectId){
                
                $("#ArchitectId").val(ArchitectId);
                var r = confirm("Are you sure! you wan't to delete Architect. If you deleted Architect it will delete all other data which is related to company and it's not revert process.");
                if (r == true) {
           
                    $('#deleteArchitec').submit();
                }
            }
        </script> 





    @if(session()->has('updated'))
    <script type="text/javascript">
    swal(
        'Success',
        'Architect updated Succesfully!',
        'success'
    )

    
    </script>
    @endif

    @if(session()->has('added'))
    <script type="text/javascript">
    swal(
        'Success',
        'Architect added Succesfully!',
        'success'
    )
    </script>
    @endif
    @endsection 