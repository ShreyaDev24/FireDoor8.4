@extends("layouts.Master")
@section("main_section")


<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="tab-content">
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

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title" style="margin-top: 10px">Quotation Prefix</h5>
                    </div>
                    <form action="{{route('setprefix')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="updval"
                            value="@if(!empty($ComQuotCounter->id)){{$ComQuotCounter->id}}@endif">
                        <div class="card-body">
                            <div class="form-row">
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="quotation_prefix">Set Quotation Prefix</label>
                                        <input name="quotation_prefix" id="quotation_prefix" placeholder="Enter Quotation Prefix"
                                            type="text" class="form-control"
                                            value="@if(!empty($ComQuotCounter->quotation_prefix)){{$ComQuotCounter->quotation_prefix}}@endif">
                                    </div>
                                </div>
                                {{--  <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="order_prefix">Set Order Prefix</label>
                                        <input name="order_prefix" id="order_prefix" placeholder="Ente Order Prefix"
                                            type="text" class="form-control"
                                            value="@if(!empty($ComQuotCounter->order_prefix)){{$ComQuotCounter->order_prefix}}@endif">
                                    </div>
                                </div>  --}}
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <button type="submit" id="submit" class="btn-wide btn btn-success"
                                            style="margin-top: 28px;"> SET PREFIX </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section("script_section")

@endsection
