@extends("layouts.Master")

@section("main_section")
     <div class="app-main__outer">

                <div class="app-main__inner">
                    @if(session()->has('successed'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> Alert!</h5>
                        {{ session()->get('successed') }}
                    </div>
                    @endif
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-sm-6">
                                <div class="card-header"><h5 class="card-title">Main Contractor's List</h5></div>
                            </div>
                            <div class="col-sm-6 ">
                            @if(Auth::user()->UserType=='2')

                                    <a  href="{{route('customer/add')}}" class="btn-shadow btn btn-info float-right">
                                        Add New
                                    </a>

                            @endif
                            </div>
                            </div>
                            <hr>
                            <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                                <thead class="text-uppercase table-header-bg">
                                <tr class="text-white">
                                    <th>Company Name</th>
                                    <th>Contact Name</th>
                                    <th>E-Mail</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Action</th>
                                    <!-- @if(Auth::user()->UserType=='2')
                                    <th>Action</th>
                                    @endif -->
                                </tr>
                                </thead>
                                <tbody>
                                    {!! $tbl !!}
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
    <form action="{{route('deleteContractor')}}" method="post" id="deleteContractor">
        {{ csrf_field() }}
        <input type="hidden" name="ContractorId" id="ContractorId">
    </form>
@endsection

@section('js')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    function deleteContractor(ContractorId){

        $("#ContractorId").val(ContractorId);
        var r = confirm("Are you sure! you wan't to delete Contractor. If you deleted Contractor it will delete all other data which is related to company and it's not revert process.");
        if (r == true) {

            $('#deleteContractor').submit();
        }
    }
</script>
@if(session()->has('success'))
    <script type="text/javascript">
            swal(
                'Success',
                'Main Contractor updated Successfully!',
                'success'
            )
    </script>
@endif
@endsection
