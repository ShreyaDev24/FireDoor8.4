@extends("layouts.Master")
@section("main_section")
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div>Door List</div>
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
                            <input type="hidden" id="qId" value="{{Request::segment(3)}}">
                        @endif
                    </div>
                </div>
                <hr>
                <table style="width: 100% !important;" id="example" class="table table-hover  table-bordered">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Door Number</th>
                            <th>Door Type</th>
                            <th>Floor</th>
                            <th>FireRating</th>
                            <th>Vision Panel</th>
                            <th>SOWidth</th>
                            <th>SOHeight</th>
                            <th>Door Finish</th>
                            <th>Action</th>
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

    <input type="hidden" id="url" value="{{ url('quotation/door-list-delete') }}">
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
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

    <script>
        function doorListAjax(id, itemmasterId='' ,vid){
            if( !confirm('Are you sure you want to delete?')) {
                return false;
            }else{
                var url = $('#url').val();
                var qId = $('#qId').val();
                $.ajax({
                    type: "POST",
                    url: url,
                    data: {
                        _token: $("#_token").val(),
                        id: id,
                        qId: qId,
                        vid: vid,
                        itemmasterId: itemmasterId
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == "success") {
                            swal({
                                title: "success!",
                                text:  data.message,
                                type: "success"
                            }).then(function() {
                                location.reload();
                            });
                        } else {
                            swal("Oops!", "Something went wrong. Please try again.", "error");
                        }
                    },
                    error: function(data) {
                        swal("Oops!", "Something went wrong. Please try again.", "error");
                    }
                });
            }
        }
    </script>
    @endsection
