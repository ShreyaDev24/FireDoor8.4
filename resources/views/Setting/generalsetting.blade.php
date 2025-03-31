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
                        <h5 class="card-title" style="margin-top: 10px">General Setting</h5>
                    </div>
                    <form action="{{route('subgeneralSetting')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="currencyUpdate" value="@if(!empty($currency->id)){{$currency->id}}@endif">
                        <input type="hidden" name="SetCurrencyRateValue" value="@if(!empty($currency->SetCurrencyRate)){{$currency->SetCurrencyRate}}@endif">
                        {{-- <div class="card-body"> --}}
                            <div class="form-row">
                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <label for="HideCosts">Hide Costs <span class="text-danger">*</span></label>
                                        <input type="checkbox" name="HideCosts" class="form-group"
                                        value="1"  @if(!empty($currency->HideCosts) && ($currency->HideCosts == 1)){{ 'checked' }}@endif>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="ProductName">Currency <span class="text-danger">*</span></label>
                                        <select name="currency" id="currency" class="form-control" required>
                                            <option value="" selected>Select Currency</option>
                                            {{--  <option value="$_US_DOLLAR" @if(!empty($currency)) @if($currency->currency == '$_US_DOLLAR'){{'selected'}}@endif @endif >$ US DOLLAR</option>  --}}
                                            <option value="£_GBP"  @if(!empty($currency)) @if($currency->currency == '£_GBP'){{'selected'}}@endif @endif>£ GBP</option>
                                            <option value="€_EURO"  @if(!empty($currency)) @if($currency->currency == '€_EURO'){{'selected'}}@endif @endif>€ EURO</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="quotation_prefix">Set Quotation Prefix <span class="text-danger">*</span></label>
                                        <input name="quotation_prefix" id="quotation_prefix" placeholder="Enter Quotation Prefix"
                                            type="text" class="form-control"
                                            value="@if(!empty($ComQuotCounter->quotation_prefix)){{$ComQuotCounter->quotation_prefix}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="order_prefix">Set Order Prefix <span class="text-danger">*</span></label>
                                        <input name="order_prefix" id="order_prefix" placeholder="Enter Prefix"
                                            type="text" class="form-control"
                                            value="@if(!empty($ComOrdCounter->order_prefix)){{$ComOrdCounter->order_prefix}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="SetCurrencyRate">Set Currency Rate <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <span class="input-group-text" id="SetCurrencyRateText"></span>
                                            <input name="SetCurrencyRate" id="SetCurrencyRate" placeholder="Set Currency Rate"
                                                type="number" step="0.0001" class="form-control"
                                                value="@if(!empty($currency->SetCurrencyRate)){{$currency->SetCurrencyRate}}@endif">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group" style="margin-left: auto; float: right;">
                                        <button type="submit" id="submit" class="btn-wide btn btn-success"
                                            style="margin-top: 28px;"> SET PREFIX </button>
                                    </div>
                                </div>
                                <!-- <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <button type="submit" id="submit" class="btn-wide btn btn-success"
                                            style="margin-top: 28px;"> SET CURRENCY </button>
                                    </div>
                                </div> -->
                            </div>
                        {{-- </div> --}}
                    <!-- </form> -->
                    <!-- <form action="{{route('setprefix')}}" method="post"> -->
                        <!-- {{ csrf_field() }} -->
                        <input type="hidden" name="quotation_prefixUpdval"
                            value="@if(!empty($ComQuotCounter->id)){{$ComQuotCounter->id}}@endif">
                        {{-- <div class="card-body">
                            <div class="form-row"> --}}

                                {{--  <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="order_prefix">Set Order Prefix</label>
                                        <input name="order_prefix" id="order_prefix" placeholder="Ente Order Prefix"
                                            type="text" class="form-control"
                                            value="@if(!empty($ComQuotCounter->order_prefix)){{$ComQuotCounter->order_prefix}}@endif">
                                    </div>
                                </div>  --}}
                                <!-- <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <button type="submit" id="submit" class="btn-wide btn btn-success"
                                            style="margin-top: 28px;"> SET PREFIX </button>
                                    </div>
                                </div> -->
                            {{-- </div>
                        </div> --}}
                    <!-- </form> -->
                    <!-- <form action="{{route('set_order_prefix')}}" method="post">
                        {{ csrf_field() }} -->
                        <input type="hidden" name="order_prefixUpdval"
                            value="@if(!empty($ComOrdCounter->id)){{$ComOrdCounter->id}}@endif">
                        <div class="card-body">
                            <div class="form-row">
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
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        SetCurrencyRateText();
        $(document).on('change','#currency',function(){
            SetCurrencyRateText();
        })
        function SetCurrencyRateText(){
            var currency = $('#currency').val();
            if(currency == '£_GBP'){
                $('#SetCurrencyRateText').text('£1 = €');
            }
            if(currency == '€_EURO'){
                $('#SetCurrencyRateText').text('€1 = £');
            }
        }
    </script>
@endsection
