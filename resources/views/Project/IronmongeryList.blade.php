@extends("layouts.Master")

@section("main_section")
<style>
.input-icons i {
    position: absolute;
    left: 0;
}
.input-icons{
    display: flex;
}
.input-group-text{
    text-align: left;
    padding-left: 18px;
}
.qty{
    width: 80px;
}
.text_style{
    font-size: 12px;
    color: #f00;
    margin-top: -14px;
}
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
.customcross{
    font-size: 15px;
    background: #ffffff;
    border: 1px solid #ececec;
    color: red;
    font-weight: bold;
}
</style>
<script>
    function Tooltip(tooltipValue) {
        let TooltipCode2 =
            `<i class="fa fa-info-circle field_info tooltip" aria-hidden="true">
                <span class="tooltiptext info_tooltip">` + tooltipValue + `</span>
            </i>`;
        return TooltipCode2;
    }
</script>
<div class="app-main__outer">
    <div class="app-main__inner">

        @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Alert!</h5>
            {{ session()->get('success') }}
        </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="fas fa-skull-crossbones"></i> Alert!</h5>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="tab-content mb-5" id="ironmongery" data-tab-content>
            <div class="row">
                <div class="col-sm-12">
                    <div class="custom_card">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card-header"><h5 class="card-title">Ironmongery <span>Set</span></h5></div>
                            </div>
                            <div class="col-sm-6 ">
                                <a href="{{route('ironmongeryadd')}}" class="btn-shadow btn btn-info float-right">
                                    <i class="fa fa-edit" aria-hidden="true"></i> Add Ironmongery Set
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table class="table">
                                    <tbody>
                                        <tr>
                                            <th>Set Name</th>
                                            <th>Manage</th>
                                        </tr>
                                         {!! $tbl !!}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
<form action="{{route('updAddIronmongery')}}" id="updSubmit" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="updAddIronmongery" id="updId">
</form>
<form action="{{route('delAddIronmongery')}}" id="delSubmit" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="delId" id="delId">
</form>
@endsection

@section("script_section")
<script>
        // Edit Ironmongery
        $(document).on('click', '.updAddIronmongery', function() {
            let id = $(this).val();
            $('#updId').val(id);
            $('#updSubmit').submit();
        })
        // Delete Ironmongery
        $(document).on('click', '.delAddIronmongery', function() {
            let id = $(this).val();
            $('#delId').val(id);
            $('#delSubmit').submit();
        })

</script>
@endsection

