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
                            </div>

                            <div class="form-row">
                                <div class="col-md-6">
                                    <div class="position-relative form-group">

                                        <label for="SetCurrencyRate">
                                            Set Company Code (Door Plug)
                                            <span class="text-danger">*</span>
                                        </label>

                                        <!-- Checkbox -->
                                        <div class="mb-2">
                                            <input type="checkbox"
                                                id="toggleCompanyCode"
                                                name="doorPlugActivated"
                                                value="1"
                                                {{ ($currency->doorPlugActivated == 1) ? 'checked' : '' }}>

                                            <label for="toggleCompanyCode">Enable Door Plug</label>
                                        </div>

                                        <!-- Input Field -->
                                        <div class="input-group">
                                            <input name="companyCode" id="companyCode"
                                                placeholder="Set Company Code"
                                                type="text"
                                                class="form-control"
                                                value="{{ $currency->companyCode ?? '' }}">
                                        </div>

                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="position-relative form-group">
                                        <button type="submit" id="submit" class="btn-wide btn btn-success"
                                            style="margin-top: 28px;"> Submit </button>
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
                    <form action="{{ route('Fittinginstructions') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-2">
                            <label class="form-label">Upload Fitting Instructions</label>
                            <input type="file" name="document" class="form-control" required>
                        </div>
                        <button type="submit" class="btn btn-success">Save</button>
                    </form>

                    <hr>

                    <h5 class="mt-3">Uploaded Fitting Instructions</h5>
                    <table class="table table-bordered mt-2">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>File Name</th>
                                {{--  <th>Uploaded By</th>  --}}
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($fittingInstructions as $index => $item)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ basename($item->document_path) }}</td>
                                    {{--  <td>{{ $item->user->name ?? 'N/A' }}</td>  --}}
                                    <td>
                                        @if($item->status == 1)
                                            <span class="badge bg-success">Active</span>
                                        @else
                                            <span class="badge bg-secondary">Inactive</span>
                                        @endif
                                    </td>
                                    <td class="d-flex" style="gap:10px;">
                                        <a href="{{ asset($item->document_path) }}" target="_blank" class="btn btn-primary btn-sm">
                                            View
                                        </a>

                                        <form action="{{ route('Fittinginstructions.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this file?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                        </form>
                                    </td>


                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No fitting instructions uploaded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

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

        $(document).ready(function () {

            function toggleCompanyCode() {
                if ($('#toggleCompanyCode').is(':checked')) {
                    $('#companyCode').prop('disabled', false);
                } else {
                    $('#companyCode').prop('disabled', true).val('');
                }
            }

            // Initial check on page load
            toggleCompanyCode();

            // On checkbox change
            $('#toggleCompanyCode').change(function () {
                toggleCompanyCode();
            });

        });
    </script>
@endsection
