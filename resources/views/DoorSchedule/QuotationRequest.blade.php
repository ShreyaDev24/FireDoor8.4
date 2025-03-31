@extends("layouts.Master")
@section("main_section")
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div>Quotation Request</div>
                    </div>
                    <div class="col-sm-6 ">
                        @if(Auth::user()->UserType=='2' || Auth::user()->UserType=='3')
                            <a href="{{url('quotation/add-new-doors')}}/{{Request::segment(3)}}/{{$vid}}" class="btn-shadow btn btn-info float-right">
                                Add New
                            </a>
                            <a href="{{url('quotation/generate')}}/{{Request::segment(3)}}/{{$vid}}"
                                class="btn-shadow btn btn-info float-right" style="margin-right:5px">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
                        @endif
                    </div>
                </div>
                <hr>
                <table style="width: 100% !important;" id="example" class="table table-hover  table-bordered">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>D.Number</th>
                            <th>D.Type</th>
                            <th>MarkLevel</th>
                            <th>FireRating</th>
                            <th>Vision Panel</th>
                            <th>Internal/External</th>
                            <th>SO width</th>
                            <th>SO height</th>
                            <th>Door Finish</th>
                            <th>NBS</th>
                        </tr>
                    </thead>
                    <tbody>
                        {!! $tbl !!}
                    </tbody>
                </table>

            </div>
        </div>
    </div>
    <button style="display: none;" type="button" id="success-alert" data-type="success"
        class="btn btn-success btn-show-swal"></button>


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
        'Quotation Added <b style="color:green;">Success</b>!',
        'success'
    )
    </script>


    @endif

    @endsection
