@extends("layouts.Master")

@section("main_section")
     <div class="app-main__outer">

                <div class="app-main__inner">

                    <div class="main-card mb-3 card">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-sm-6">
                                <div class="card-header"><h5 class="card-title">Main Contractor List</h5></div>
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
                                    <th>Edit</th>

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

@endsection

@section('js')
@if(session()->has('success'))
    <script type="text/javascript">
            swal(
                'Success',
                // 'Customer Added the <b style="color:green;">Success</b> button!',
                'Main Contractor updated Successfully'
                'success'
            )
    </script>
@endif
@endsection
