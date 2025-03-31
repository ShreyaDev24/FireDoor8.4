@extends("layouts.Master")
@section("main_section")
<style>
.customDisabled {
    opacity: .54;
    pointer-events: none;
    cursor: default;
}

.drop_style{
    transform: translate3d(-165px, 33px, 0px) !important;
}

.activeNC {
    background: #303641;
    border: 1px solid #303641;
}
</style>

<div class="app-main__outer">
    <div class="app-main__inner p-0">
        <div class="main-card mb-3">
            <div class="card-body">
                <!-- table-striped -->
                <div class="alert response-section" role="alert" style="display:none;"></div>
                <span class="error"></span>
                <div>
                    <div class="action_options">
                        <h2 class="origin_heading">Order / {{$orderNumber}}</h2>
                        <ul class="float-right">

                            <li>
                                <div class="dropdown">
                                    <a class="btn btn-light dropdown-toggle" data-toggle="dropdown">MORE</a>
                                    <ul class="dropdown-menu drop_style">

                                        <li><a href="javascript:void(0);" onClick="OMmanualQuotation({{ $quotationId }},{{ $quotation->VersionId }},'')" id="">Generate O&M Manual</a></li>
                                        <li><a href="javascript:void(0);" onClick="ExcelExport({{ $quotationId }},{{ $quotation->VersionId }});">Generate Doorset Schedule Excel</a></li>
                                        {{--  <li><a href="javascript:void(0);" onClick="BuildOfMaterial();">Generate Bill Of Material</a></li>  --}}
                                        <li><a href="javascript:void(0);" onClick="DeleteQuotation();">Delete</a></li>
                                        {{--  <li><a href="javascript:void(0);" onClick="SendToClient();">Send To Client</a></li>  --}}
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                    @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="icon fas fa-check"></i> Alert!</h5>
                        {{ session()->get('success') }}
                    </div>
                    @endif
                    <div class="row w-100 m-0">
                    @if(Auth::user()->UserType==1 || Auth::user()->UserType==2 || Auth::user()->UserType==3)
                        <div class="col-sm-9 p-0">
                    @else
                        <div class="col-sm-12 p-0">
                    @endif
                            <div class="table_list_row" style="background: #d0cbcb;padding: 8px;margin-top: -1px;">
                                <h5 class="float-left customerData" style="margin: 6px;">
                                    @if(isset($customerDetails->CstCompanyName))
                                    <span>{{ $customerDetails->CstCompanyName }}</span>
                                    <span>{{ isset($customerDetails->CstCompanyAddressLine1)?$customerDetails->CstCompanyAddressLine1:"" }}</span>
                                    @else
                                    <span>Main Contractor is not selected</span>
                                    @endif
                                </h5>


                            </div>
                            <div class="table_list_row pb-2">
                                <span class="ShowVersion pull-left pt-2">
                                    @if($selectQV['selectVersion'] > 0)
                                        <b>Selected Revision : {{$selectQV['selectVersion']}}</b>
                                    @endif
                                </span>

                                <a href="javascript:void(0);" onclick="configurableNon(2);"
                                    class="btn btn-primary float-right mx-1" id="config">Non Configurable
                                </a>
                                <a href="javascript:void(0);" onclick="configurableNon(1);"
                                    class="btn btn-primary float-right mx-1 activeNC" id="nonConfig">Configurable
                                </a>
                            </div>
                            <div class="main-card mb-3" id="add-item-section" style="display: none;">
                                <div class="col-sm-12 mt-3">
                                    <h3 class="card-title">

                                        <a href="javascript:void(0);" onclick="$('#add-item-section').hide();$('#quotation-item-list').show();$('#NonConfig-item-list').css('display','none');"
                                            class="add_button"><i class="fa fa-times"></i></a>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-sm-3 p-0">
                                                <h5 class="card-title">Categories</h5>
                                                <ul class="CustomTabs tabs">
                                                    <li class="active"><a data-toggle="tab"
                                                            href="#configurable">Configurable</a></li>
                                                    <li><a data-toggle="tab" href="#nonconfigurable">Non
                                                            Configurable</a></li>
                                                    <li><a data-toggle="tab" href="#all">All</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-sm-9">
                                                <div class="CustomTabContent tab-content">
                                                    <div id="configurable" class="tab-pane active">
                                                        <h3 class="card-title">Configurable Items</h3>
                                                        <div class="row m-0">
                                                            {!! $configItem !!}
                                                        </div>
                                                    </div>
                                                    <div id="nonconfigurable" class="tab-pane fade">
                                                        <h3 class="card-title">Non Configurable Items</h3>
                                                        <div class="row m-0">
                                                            {!! $NonConfig !!}
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="main-card mb-3 custom_card" id="edit-header-section" style="display: none;">
                                @include("DoorSchedule.quotationForms.viewheader")
                            </div>
                            <div class="main-card mb-3 custom_card" id="customer-list" style="display: none;">
                                <div class="card-header">
                                    <h3 class="w-100">
                                        Main Contractors List
                                        <a href="javascript:void(0);" onclick="$('#customer-list').hide();$('#quotation-item-list').show();$('#NonConfig-item-list').css('display','none');"
                                            class="add_button"><i class="fa fa-times"></i></a>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="large_search">
                                        <input type="text" name="search" id="searchCustomer" class="form-control input-lg"
                                            placeholder="Search Main Contractor By Name">
                                        <i class="fa fa-search"></i>
                                    </div>
                                    <span id="AppendCustTbl"></span>
                                    <table style="width: 100%;" id="example"
                                        class="table table-hover table-striped custTbl">
                                        <tbody>
                                            @if(!empty($companykacustomer))
                                            @php
                                            $i = 1;
                                            @endphp
                                            @foreach($companykacustomer as $row)
                                            @if($row->CstCompanyName != '')
                                            <tr>
                                                <td>
                                                    <a href="{{url('customer/details/'.$row->id)}}">{{$row->CstCompanyName}}</a>
                                                </td>
                                                <td>{{$row->CstCompanyPhone}}</td>
                                                <td>{{$row->CstCompanyAddressLine1}}</td>
                                                <td>
                                                    <a class="btn btn-dark"
                                                        onclick="selectCustomer('{{$row->id}}','{{$orderNumber}}','{{$row->CstCompanyName}}','{{$row->CstCompanyAddressLine1}}');" href="javascript:void(0);">Select</a>
                                                </td>
                                            </tr>
                                            @endif
                                            @php
                                            $i++;
                                            @endphp
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>

                                </div>
                            </div>

                            <div class="main-card mb-3" id="quotation-item-list">
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-header-bg">
                                            <tr class="text-white">
                                                <th>Line</th>
                                                <th>Door Qty</th>
                                                <th>Fire Rating</th>
                                                <th>Door Type</th>
                                                <th>Door No.</th>
                                                <th>Floor</th>
                                                <th>Door Description</th>
                                                <th>S.O. Width</th>
                                                <th>S.O. Height</th>
                                                <th>S.O. Depth</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="versionData">
                                            @if(!empty($data) && count($data)>0)
                                            <?php  $index =0; $SI=1;?>
                                            @foreach($data as $row)
                                            @if(!empty($row->version_id))
                                                @php
                                                    $version_id = $row->version_id;
                                                @endphp
                                            @else
                                                @php
                                                    $version_id = 0;
                                                @endphp
                                            @endif
                                            <tr>
                                                <td>
                                                    {{$SI}}
                                                    <input type="hidden" class="check" value="{{$row->itemId}}">
                                                    <input type="hidden" class="doors_{{$index}}" value="{{$row->id}}">
                                                </td>
                                                <td>{{$row->DoorQuantity}}</td>
                                                <td>{{$row->FireRating}}</td>
                                                <td>{{$row->DoorType}}</td>
                                                <td>{{$row->doorNumber}}</td>
                                                <td>{{$row->floor}}</td>
                                                <td>door descrition</td>
                                                <td>{{$row->SOWidth}}</td>
                                                <td>{{$row->SOHeight}}</td>
                                                <td>{{$row->SOWallThick}}</td>

                                                <td>{{ (($row->AdjustPrice)?floatval($row->AdjustPrice) :floatval($row->DoorsetPrice)) }}</td>
                                                <td>{{ (($row->AdjustPrice)?floatval($row->AdjustPrice) + floatval($row->IronmongaryPrice):floatval($row->DoorsetPrice) + floatval($row->IronmongaryPrice))  }}</td>
                                                <!-- <td class="text-center">
                                                    <div class="dropdown">
                                                        <a class="dropdown-toggle btn btn-light" type="button"
                                                            data-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></a>
                                                        <ul class="dropdown-menu drop_style">
                                                            <li><a
                                                                    href="{{ConfigurationURL($quotation->configurableitems  , $row->itemId , $version_id)}}">Edit</a>
                                                            </li>
                                                            <li><a href="#">Name Configuration</a></li>
                                                            <li><a href="#">Adjust Price</a></li>
                                                            <li><a href="#">Comment</a></li>
                                                            <li><a href="#">Copy</a></li>
                                                            <li><a href="#">Export</a></li>
                                                            <li><a href="#">Remove</a></li>
                                                        </ul>
                                                    </div>
                                                </td> -->
                                            </tr>
                                            <?php $index++; $SI++; ?>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="main-card mb-3" id="NonConfig-item-list" style="display: none;">
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-header-bg">
                                            <tr class="text-white">
                                                <th>Line</th>
                                                <th>Name</th>
                                                <th>Product Code</th>
                                                <th>Description</th>
                                                <th>Unit</th>
                                                <th>Qty</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                            </tr>
                                        </thead>
                                        <tbody id="versionData">
                                            @if (!empty($nonConfigData) && count($nonConfigData) > 0)
                                                <?php
                                                $SI = 1; ?>
                                                @foreach ($nonConfigData as $value)
                                                    <tr>
                                                        <td>{{ $SI++; }}</td>
                                                        <td>{{ $value->name }}</td>
                                                        <td>{{ $value->product_code }}</td>
                                                        <td><script type="text/javascript">
                                                            document.write(ReadMore(5,"{{ $value->description }}"))
                                                        </script></td>
                                                        <td>{{ $value->unit }}</td>
                                                        <td>{{ $value->quantity }}</td>
                                                        <td>{{ floatval($value->price) }}</td>
                                                        <td>{{ floatval($value->quantity) * floatval($value->price) }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        <!-- @php
                            if($quotation->QuotationStatus != ''){
                                if($quotation->QuotationStatus == 'Open'){
                                    $quotation_status = '<div class="quote_status" style="background: #69e4a6;">'.$quotation->QuotationStatus.'</div>';
                                }
                                else if($quotation->QuotationStatus == 'Ordered' || $quotation->QuotationStatus == 'Accept'){
                                    $quotation_status = '<div class="quote_status" style="background: #47a91f;">'.$quotation->QuotationStatus.'</div>';
                                } else if($quotation->QuotationStatus == 'All'){
                                    $quotation_status = '<div class="quote_status" style="background:#808080;">'.$quotation->QuotationStatus.'</div>';
                                } else {
                                    $quotation_status = '<div class="quote_status" style="background:red;">'.$quotation->QuotationStatus.'</div>';
                                }
                            } else {
                                $quotation_status = null;
                            }
                        @endphp -->
                        <div class="col-sm-3 p-0">
                            <div class="quote_card">
                                <div class="quote_card_title">
                                    <h3>Quote Summary</h3>
                                </div>
                                <div class="quote_card_header mt-4">
                                    <h4>Header
                                        <a href="#" onclick="ChangeQuotationFormBtn()" id="ChangeQuotationFormBtn">View Header Details</a>
                                    </h4>
                                    <!-- {!! $quotation_status !!} -->
                                </div>
                                <form action="{{route('QuoteSummary')}}" method="post">
                                    {{csrf_field()}}
                                    <input type="hidden" name="quotationId" value="{{$quotationId}}">
                                    <div class="quote_card_form">
                                        <div class="form-group">
                                            <label>Contact List</label>
                                            <select disabled name="QSCustomerContactId" id="CustomerContactIdSTC" class="CustomerId" required>
                                                <option value="">Select Contact List</option>
                                                @if(!empty($customerMultiContact))
                                                @foreach($customerMultiContact as $row)
                                                <option value="{{$row->id}}"
                                                    @if(!empty($quotation->CustomerId))
                                                        @if($quotation->QSCustomerContactId == $row->id)
                                                        {{'selected'}}
                                                        @endif
                                                    @endif
                                                >{{$row->FirstName.' '.$row->LastName}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>Ship To</label>
                                            <select disabled name="QSQuotationSiteDeliveryAddressId" required>
                                                <option value="">Same as Bill To</option>
                                                @if(!empty($QuotationSiteDeliveryAddress))
                                                @foreach($QuotationSiteDeliveryAddress as $deliveryaddr)
                                                    <option @if($quotation->QSQuotationSiteDeliveryAddressId==$deliveryaddr->id) selected @endif  value="{{$deliveryaddr->id}}">
                                                        {{$deliveryaddr->Address1}}
                                                    </option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>P.O. Number</label>
                                            <input disabled type="text" name="PONumber" value="{{$quotation->PONumber}}" required>
                                        </div>
                                        @if($quotation->QuotationStatus == 'Accept')
                                            <div class="form-group">
                                                <label>Atteched File By Client</label>
                                                <a href="{{url('/')}}/quotationFiles/fileSendByClient/{{$quotation->fileByClient}}" target="_blanks">
                                                    <i class="fa fa-download"></i> Download File
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="quote_card_header mt-4">
                                        <h4>Pricing </h4>
                                    </div>
                                    <div class="quote_pricing">
                                        <p>DoorSet
                                            <span>
                                                @if (!empty($TotalExactDoorPrice))
                                                    {{ number_format( (float) $TotalDoorPrice, 2, '.', '') }}
                                                @else
                                                    {{ '0.00' }}@endif
                                            </span>
                                        </p>
                                        <p>Ironmongery Set
                                            <span>
                                                @if (!empty($TotalIronmongeryPrice))
                                                    {{ number_format( (float) $TotalIronmongeryPrice, 2, '.', '') }}
                                                @else
                                                    {{ '0.00' }}@endif
                                            </span>
                                        </p>
                                        <p>Non Configurable
                                            <span>
                                                @if (!empty($nonConfigDataPrice))
                                                    {{ number_format( (float) $nonConfigDataPrice, 2, '.', '') }}
                                                @else
                                                    {{ '0.00' }}@endif
                                            </span>
                                        </p>
                                        <hr>
                                        <p>Subtotal(List)
                                            <span>
                                                @if (!empty($total_price))
                                                    {{ number_format( (float) $total_price, 2, '.', '') }}
                                                @else
                                                    {{ '0.00' }}@endif
                                            </span>
                                        </p>
                                        <p>Discount(% Off List) <span id="QSdiscountValue">0.00</span></p>
                                        <input disabled type="text" id="QuoteSummaryDiscount"  name="QuoteSummaryDiscount" placeholder="0.00" value="@if(!empty($quotation->QuoteSummaryDiscount)){{ $quotation->QuoteSummaryDiscount }}@endif">
                                        <input type="hidden" id="QuoteSummaryTotalDoorPrice" value="@if(!empty($total_price)){{$total_price}} @else {{'0.00'}}@endif">
                                        <h4 class="total_amount">Total(GBP) <span>@if(!empty($total_price)){{$total_price}} @else {{'0.00'}}@endif</span></h4>

                                        <!-- @if($quotation->QuotationStatus != 'Accept')
                                            <a tabindex="0" class="btn btn-dark btn-lg btn-block" role="button" data-toggle="popover" data-trigger="focus" title="" data-original-title="Wait For Quotation Accept.">
                                                CONVERT TO ORDER
                                            </a>
                                        @else
                                            <button type="submit" class="btn btn-dark btn-lg btn-block" >CONVERT TO ORDER</button>
                                        @endif -->
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
    <input type="hidden" name="addItemUrl" id="addItemUrl" value="{{url('/quotation/add-configuration-cad-item')}}" />
    <input type="hidden" name="addhalspanUrl" id="addhalspanUrl" value="{{url('/quotation/add-halspan-item')}}" />
    <!-- <input type="hidden" name="addItemUrl"  value="{{url('/quotation/singleconfigurationitem')}}" /> -->
    <input type="hidden" name="addItemUrl2" id="addItemUrl2" value="{{url('/quotation/request')}}" />
    <input type="hidden" name="printInvoiceUrl" id="printInvoiceUrl" value="{{url('/quotation/printinvoice')}}" />
    <input type="hidden" name="printInvoiceExcelUrl" id="printInvoiceExcelUrl" value="{{url('/quotation/printinvoiceinexcel')}}" />
    <input type="hidden" id="sendToClientUrl" value="{{route('sendToClientUrl')}}" />
    <input type="hidden" name="buildofmaterialUrl" id="buildofmaterialUrl" value="{{url('/quotation/generateBOM2')}}" />
    <input type="hidden" name="quotationId" id="quotationId" value="{{$quotationId}}" />
    <input type="hidden" name="version" id="version" value="{{ ($maxVersion !== null)?$maxVersion:0 }}" />
    <input type="hidden" name="currentVersion" id="currentVersion" value="{{ ($selectQV['selectVersionID'] > 0)?$selectQV['selectVersionID']:0 }}" />
    <input type="hidden" name="bomTag" id="bomTag" value="{{ ($quotation->bomTag > 0)?$quotation->bomTag:0 }}" />
    <input type="hidden" name="customer_id" id="customer_id" value="{{ $quotation->CustomerId }}" />
    <input type="hidden" name="flag" id="flag" value="{{ $quotation->flag }}" />
    <input type="hidden" name="generatedId" id="generatedId" value="{{$orderNumber}}" />

    <button style="display: none;" type="button" id="success-alert" data-type="success"
        class="btn btn-success btn-show-swal"></button>

    <input type="hidden" name="excelexportUrl" id="excelexportUrl" value="{{url('/quotation/excelexport')}}" />
    <input type="hidden" name="mainformimportUrl" id="mainformimportUrl" value="{{url('quotation/excel-upload/')}}" />
    <input type="hidden" name="generateBOM" id="generateBOM" value="{{ route('generateBOM') }}" />
    <input type="hidden" id="ommanual" value="{{url('order/ommanual')}}" />
    @endsection
    @section('js')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });



    </script>
    <script type="text/javascript" src="{{url('/')}}/js/generateQuotation.js"></script>
<script>

    let UniversalToken = $("#_token").val();


    function configurableNon(id) {
        $('#add-item-section').hide();
        $('#edit-header-section').hide();
        $('#customer-list').hide();
        if (id == 1) {
            $('#quotation-item-list').css('display','block');
            $('#NonConfig-item-list').css('display','none');
            $('#config').removeClass('activeNC');
            $('#nonConfig').addClass('activeNC');
            $('#showall').removeClass('activeNC');
        } else {
            $('#quotation-item-list').css('display','none');
            $('#NonConfig-item-list').css('display','block');
            $('#config').addClass('activeNC');
            $('#nonConfig').removeClass('activeNC');
            $('#showall').removeClass('activeNC');
        }
    }





    $(document).on('keyup', '#searchCustomer', function(e) {
        e.preventDefault();
        const customerName = $(this).val();
        const quotationIdValue = $('#quotationId').val();
        $.ajax({
            url: "{{route('searchCustomer')}}",
            type: 'post',
            data: {
                _token: UniversalToken,
                'customerName': customerName,
                'quotationIdValue': quotationIdValue
            },
            dataType: "json",
            success: function(data) {
                $('.custTbl').css({
                    'display': 'none'
                });
                $('#AppendCustTbl').html(data)
            }
        })
    })








    $(document).ready(function(e) {
        $('.datepicker').datepicker({
            format: 'dd-mm-yyyy'
        });
    });

    function openVersionModal() {
        $("#quotation-version-modal").modal("show");
    }

    function CreateNewVersionModal(version) {
        if (version == 0) {
            var Flag = $("#flag").val();
            var CustomerId = $("#customer_id").val();
            if (CustomerId == "") {
                swal("Warning!", "Select a Main Contractor first.", "warning");
                return false;
            }

            if (Flag == 0) {
                swal("Warning!", "Please complete the edit header form.", "warning");
                return false;
            }

            var checkboxes = document.getElementsByClassName('check');
            var isChecked = false;
            var version = $("#version").val();
            var doors = [];
            var items = [];
            var quantity = [];
            for (var i = 0; i < checkboxes.length; i++) {
                isChecked = true;
                items.push(checkboxes[i].value);
                // var quant = document.getElementsByClassName('quantity_'+i);
                // items.push(quant[0].value);
                quantity.push($('.quantity_' + i).val());

                // var door = document.getElementsByClassName('doors_'+i)[0].value;
                // doors.push(door[0].value);
                doors.push($('.doors_' + i).val());
            }
            if (isChecked) {
                $.ajax({
                    type: "POST",
                    url: "{{url('quotation/versionstore')}}",
                    data: {
                        _token: $("#_token").val(),
                        quotationId: $("#quotationId").val(),
                        itemId: $("#version").val(),
                        doors: doors,
                        quantity: quantity,
                        items: items,
                        version: version
                    },
                    dataType: "json",
                    success: function(data) { console.log(data);
                        if (data.status == "success") {
                            $(".response-section").removeClass('alert-danger');
                            $(".response-section").addClass('alert-success');
                            $(".response-section").empty().append(data.message).show();
                            $("#invoice").val(data.newVersion);
                            setTimeout(function() {
                                window.location.href = data.url;
                            }, 3000);
                        } else {
                            $(".response-section").removeClass('alert-success');
                            $(".response-section").addClass('alert-danger');
                            $(".response-section").empty().append(data.message).show();
                        }

                        setTimeout(function() {
                            $(".response-section").fadeOut();
                        }, 3000);

                    },
                    error: function(data) {
                        swal("Oops!", "Something went wrong. Please try again.", "error");
                    }
                });

            } else {
                swal("Warning!", "Select at least one door.", "warning");
            }
        } else {
            $("#create-new-version-modal").modal("show");
        }
    }

    function ChangeQuotationFormBtn() {
        $('#customer-list').hide();
        $('#add-item-section').hide();
        $('#edit-header-section').show();
        $('#quotation-item-list').hide();
        $('#NonConfig-item-list').css('display','none');
        $('#config').removeClass('activeNC');
        $('#nonConfig').removeClass('activeNC');
    }

    function ShowAddItemOption() {
        $('#customer-list').hide();
        $('#add-item-section').show();
        $('#edit-header-section').hide();
        $('#quotation-item-list').hide();
        $('#NonConfig-item-list').css('display','none');
        $('#config').removeClass('activeNC');
        $('#nonConfig').removeClass('activeNC');
    }

    function ChangeCustomerBtn() {
        $('#customer-list').show();
        $('#add-item-section').hide();
        $('#edit-header-section').hide();
        $('#quotation-item-list').hide();
        $('#NonConfig-item-list').css('display','none');
        $('#config').removeClass('activeNC');
        $('#nonConfig').removeClass('activeNC');
    }

    $(document).on("click", ".configure_btn", function(e) {
        var type = $(this).data("type");
        var quotationId = $("#quotationId").val();
        var currentVersion = $("#currentVersion").val();

        if (type == 1) { // for 1 Streboard
            var addItemUrl = $("#addItemUrl").val();
            if (currentVersion != 0) {
                window.location.href = addItemUrl + '/' + quotationId + '/' + currentVersion;
            } else {
                window.location.href = addItemUrl + '/' + quotationId;
            }
        } else if(type == 2){ // for 2 Halspan
            var addhalspanUrl = $("#addhalspanUrl").val();
            if (currentVersion != 0) {
                window.location.href = addhalspanUrl + '/' + quotationId + '/' + currentVersion;
            } else {
                window.location.href = addhalspanUrl + '/' + quotationId;
            }
        }
    });
    $(document).on("click", ".configure_door_btn", function(e) {
        var type = $(this).data("type");
        var addItemUrl = $("#addItemUrl2").val();
        var quotationId = $("#quotationId").val();
        var currentVersion = $("#currentVersion").val();

        if (type == 1 || type == 2) {// for 1 Streboard // for 2 Halspan
            if (currentVersion != 0) {
                window.location.href = addItemUrl + '/' + quotationId + '/' + currentVersion;
            } else {
                window.location.href = addItemUrl + '/' + quotationId+'/'+currentVersion;
            }
        }
    });



    function selectCustomer(customerId, QuotationId, company, address) {

        var html = '<span>' + company + '</span><span>' + address + '</span>';
        $(".customerData").empty().append(html);
        $("#ChangeCustomerBtn").text("Change Main Contractor");
        $("#customer-list").hide();
        $('#customer_id').val(customerId);

        var url = "{{route('quotation/selectcustomer')}}";
        const token = $("#_token").val();
        const quotationId = "{{$quotationId}}";

        $.ajax({
            url: url,
            type: 'post',
            data: {
                'url': url,
                _token: token,
                'customerId': customerId,
                'QuotationId': QuotationId,
                'quotationId': quotationId
            },
            dataType: 'json',
            success: function(data) {
                if (data.status == "success") {
                    $('#customer_id').val(customerId);
                    $(".CustomerId option[value='"+customerId+"']").remove();
                    $('.CustomerId').empty().append(data.selectCC);
                    $('.selectprojectId').empty().append(data.selproj);
                    $('#quotation-item-list').show();
                }
            }
        });

    }


    $('#selectAll').click(function(ele) {
        var checkboxes = document.getElementsByClassName('check');
        if ($(this).prop("checked") == true) {
            $(this).prop('checked', false);
            for (var i = 0; i < checkboxes.length; i++) {
                $(checkboxes[i]).prop('checked', true);
            }

        } else {
            $(this).prop('checked', true);

            for (var i = 0; i < checkboxes.length; i++) {
                $(checkboxes[i]).prop('checked', false);


            }
        }



    });

    $("#generateInvoice").click(function() {

        var checkboxes = document.getElementsByClassName('check');
        var isChecked = false;
        var version = $("#version").val();
        var doors = [];
        var items = [];
        var quantity = [];
        for (var i = 0; i < checkboxes.length; i++) {

            isChecked = true;

            items.push(checkboxes[i].value);
            // var quant = document.getElementsByClassName('quantity_'+i);
            // items.push(quant[0].value);
            quantity.push($('.quantity_' + i).val());

            // var door = document.getElementsByClassName('doors_'+i)[0].value;
            // doors.push(door[0].value);
            doors.push($('.doors_' + i).val());

        }

        if (isChecked) {

            $.ajax({
                type: "POST",
                url: "{{url('quotation/versionstore')}}",
                data: {
                    _token: $("#_token").val(),
                    quotationId: $("#quotationId").val(),
                    itemId: $("#version").val(),
                    doors: doors,
                    quantity: quantity,
                    items: items,
                    version: version
                },
                success: function(data) {
                    data = JSON.parse(data);

                    if (data.status == "success") {
                        $(".response-section").removeClass('alert-danger');
                        $(".response-section").addClass('alert-success');
                        $(".response-section").empty().append(data.message).show();
                        $("#invoice").val(data.newVersion);
                        setTimeout(function() {
                            location.reload();
                        }, 3000);

                    } else {
                        $(".response-section").removeClass('alert-success');
                        $(".response-section").addClass('alert-danger');
                        $(".response-section").empty().append(data.message).show();
                    }
                    setTimeout(function() {
                        $(".response-section").fadeOut();
                    }, 3000);

                },
                error: function(data) {
                    swal("Oops!!", "Something went wrong. Please try again.", "error");
                }
            });


        } else {
            swal("Warning!!", "Select at least one door.", "warning");
        }
    });


    function getVersion(version, quotationId) {
        $.ajax({
            type: "POST",
            url: "{{url('quotation/get-version')}}",
            data: {
                _token: $("#_token").val(),
                version: version,
                quotationId: quotationId
            },
            dataType: 'Json',
            success: function(data) {
                innerhtml = '';
                if (data.status == "ok") {
                    var door = data.door;
                    var length = door.length;
                    for (var i = 0; i < length; i++) {
                        if (door[i].DoorsetType == "DD") {
                            var doorType = 'Double Door';
                        } else if (door[i].DoorsetType == "SD") {
                            var doorType = 'Single Door';
                        } else {
                            var doorType = 'Leaf and a half';
                        }

                        innerhtml += '<tr>';
                        innerhtml += '<td>' + (i + 1) + '<input type="hidden" class="check" value="' + door[
                            i].itemId + '"><input type="hidden" class="doors_' + i + '" value="' + door[
                            i].id + '"></td>';
                        innerhtml += '<td>' + door[i].DoorNumber + '</td>';
                        innerhtml += '<td>' + doorType + '</td>';
                        innerhtml +=
                            '<td><input type="number"  style="width: 100%;" readonly id="quantity" value="1" name="quantity" min="1" max="100" class="quantity_' +
                            i + '"></td>';
                        innerhtml += '<td>' + door[i].DoorsetPrice + '</td>';
                        innerhtml += '<td>' + door[i].DoorsetPrice + '</td>';
                        innerhtml += '<td class="text-center">';
                        innerhtml += '<div class="dropdown">';
                        innerhtml +=
                            '<a class="dropdown-toggle btn btn-light" type="button" data-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></a>';
                        innerhtml += '<ul class="dropdown-menu drop_style">';
                        innerhtml += '<li><a href="#">Change Option</a></li>';
                        innerhtml += '<li><a href="#">Name</a></li>';
                        innerhtml += '<li><a href="#">Configuration</a></li>';
                        innerhtml += '<li><a href="#">Adjust Price</a></li>';
                        innerhtml += '<li><a href="#">Comment</a></li>';
                        innerhtml += '<li><a href="#">Copy</a></li>';
                        innerhtml += '<li><a href="#">Export</a></li>';
                        innerhtml += '<li><a href="javascript:void(0);" onclick="RemoveVersionsItem(' +
                            door[i].id + ')">Remove</a></li>';
                        innerhtml += '</ul>';
                        innerhtml += '</div>';
                        innerhtml += '</td>';
                        innerhtml += '</tr>';
                    }

                } else {
                    innerhtml += '';
                }

                $("#versionData").empty().append(innerhtml);

            },
            error: function(data) {
                //                $(".page-loader-action").fadeOut();
                swal("Oops!!", "Something went wrong. Please try again.", "error");
            }
        });
    }

    function ApplyVersionFilters() {

        var Version = $("#select-version").val();
        if (Version == "") {
            return false;
        }

        var QuotationId = $("#quotationId").val();
        if (QuotationId == "") {
            return false;
        }
        $.ajax({
            type: "POST",
            url: "{{url('quotation/get-version')}}",
            data: {
                _token: $("#_token").val(),
                version: Version,
                quotationId: QuotationId
            },
            dataType: 'Json',
            success: function(data) { console.log(data)
                var innerhtml = '';
                if (data.status == "success") {
                    var door = data.door;
                    var length = door.length;
                    for (var i = 0; i < length; i++) {

                        if (door[i].DoorsetType == "DD") {
                            var doorType = 'Double Door';
                        } else if (door[i].DoorsetType == "SD") {
                            var doorType = 'Single Door';
                        } else {
                            var doorType = 'Leaf and a half';
                        }

                        let Editurl = "{{url('quotation/edit-configuration-cad-item/')}}/" + door[i].itemId+"/"+data.versionId;


                        innerhtml += '<tr id="item-' + door[i].id + '">';
                        innerhtml += '<td>' + (i + 1) + '<input type="hidden" class="check" value="' + door[
                            i].itemId + '"><input type="hidden" class="doors_' + i + '" value="' + door[
                            i].id + '"></td>';
                        innerhtml += '<td>' + door[i].DoorType + '</td>';
                        innerhtml += '<td>' + door[i].doorNumber + '</td>';
                        innerhtml += '<td>' + doorType + '</td>';
                        innerhtml +=
                            '<td><input type="number"  style="width: 100%;" readonly id="quantity" value="1" name="quantity" min="1" max="100" class="quantity_' +
                            i + '"></td>';
                        innerhtml += '<td>' + door[i].DoorsetPrice + '</td>';
                        innerhtml += '<td>' + door[i].DoorsetPrice + '</td>';
                        innerhtml += '<td class="text-center">';
                        innerhtml += '<div class="dropdown">';
                        innerhtml +=
                            '<a class="dropdown-toggle btn btn-light" type="button" data-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></a>';
                        innerhtml += '<ul class="dropdown-menu drop_style">';
                        innerhtml += '<li><a href="' + Editurl + '">Edit</a></li>';
                        innerhtml += '<li><a href="#">Name Configuration</a></li>';
                        innerhtml += '<li><a href="#">Adjust Price</a></li>';
                        innerhtml += '<li><a href="#">Comment</a></li>';
                        innerhtml += '<li><a href="#">Copy</a></li>';
                        innerhtml += '<li><a href="#">Export</a></li>';
                        innerhtml += '<li><a href="javascript:void(0);" onclick="RemoveVersionsItem(' +
                            door[i].id + ')">Remove</a></li>';
                        innerhtml += '</ul>';
                        innerhtml += '</div>';
                        innerhtml += '</td>';
                        innerhtml += '</tr>';
                    }

                } else {
                    innerhtml += '';
                }

                $("#versionData").empty().append(innerhtml);
                $("#quotation-version-modal").modal("hide");
                $("#currentVersion").val(data.versionId);
                $(".ShowVersion").html('<b>Selected Version : ' + data.version + '</b>');

                $('#generateInvoice').attr('disabled', 'disabled');
                $('#add-item').removeClass('customDisabled');
            },
            error: function(data) {
                //                $(".page-loader-action").fadeOut();
                swal("Oops!!", "Something went wrong. Please try again.", "error");
            }
        });
    }

    function CreateNewVersion() {
        var Version = $('#createNewSelectVersion').val();
        if (Version == "") {
            return false;
        }
        var QuotationId = $("#quotationId").val();
        if (QuotationId == "") {
            return false;
        }
        $.ajax({
            type: "POST",
            url: "{{url('quotation/create-new-version')}}",
            data: {
                _token: $("#_token").val(),
                version: Version,
                quotationId: QuotationId
            },
            dataType: 'Json',
            success: function(data) { console.log(data);
                if (data.status == "success") {

                    $("#create-new-version-modal").modal("hide");

                    $(".response-section").removeClass('alert-danger');
                    $(".response-section").addClass('alert-success');
                    $(".response-section").empty().append(data.message).show();
                    $("#invoice").val(data.newVersion);
                    setTimeout(function() {
                        window.location.href = data.url;
                    }, 3000);
                } else {
                    $(".response-section").removeClass('alert-success');
                    $(".response-section").addClass('alert-danger');
                    $(".response-section").empty().append(data.message).show();
                }
                setTimeout(function() {
                    $(".response-section").fadeOut();
                }, 3000);
            },
            error: function(data) {
                swal("Oops!!", "Something went wrong. Please try again.", "error");
            }
        });
    }

    RemoveVersionsItem = function(id) {
        var r = confirm("Are you sure! you wan't to delete it.");
        if (r == true) {

            var Version = $("#currentVersion").val();
            if (Version == "") {
                return false;
            }

            var QuotationId = $("#quotationId").val();
            if (QuotationId == "") {
                return false;
            }

            $.ajax({
                type: "POST",
                url: "{{url('quotation/remove-item-from-version')}}",
                data: {
                    _token: $("#_token").val(),
                    version: Version,
                    id: id,
                    quotationId: QuotationId
                },
                dataType: 'Json',
                success: function(data) {
                    if (data.status == "success") {
                        $("#item-" + id).remove();
                    } else {
                        swal("Oops!", data.message, "error");
                    }
                },
                error: function(data) {
                    swal("Oops!", "Something went wrong. Please try again.", "error");
                }
            });
        }
    };

    CopyQuotation = function(QuotationId){
        var r = confirm("Are you sure! you wan't to copy this quotation.");
        if (r == true) {

            var VersionId = $("#currentVersion").val();
            if(VersionId == ""){
                return false;
            }

            $.ajax({
                type: "POST",
                url: "{{url('quotation/copy-existing-quotation')}}",
                data: {
                    _token: $("#_token").val(),
                    quotationId: QuotationId,
                    versionId: VersionId
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == "success") {
                        swal("Copied!", data.message, "success");
                    } else {
                        swal("Oops!", data.message, "error");
                    }
                },
                error: function(data) {
                    swal("Oops!", "Something went wrong. Please try again.", "error");
                }
            });
        }
    };

    DeleteQuotation = function(){
        var r = confirm("Are you sure! you wan't to delete it.");
        if (r == true) {
            var quotationId = $("#quotationId").val();
            var VersionId = $("#currentVersion").val();
            // if (VersionId == 0) {
            //     swal("Oops!", "Something went wrong. Please try again.", "error");
            //     return false;
            // }

            $.ajax({
                type: "POST",
                url: "{{url('quotation/deletequotation')}}",
                data: {
                    _token: $("#_token").val(),
                    quotationId: quotationId,
                    versionId: VersionId
                },
                dataType: 'Json',
                success: function(data) { console.log(data)
                    if (data.status == "success") {
                        window.location.href = "{{url('/')}}/"+data.url;
                    } else {
                        swal("Oops!", data.message, "error");
                    }
                },
                error: function(data) {
                    swal("Oops!", "Something went wrong. Please try again.", "error");
                }
            });
        }
    };



    ExcelExport = function(quotationId,VersionId) {
        var excelexportUrl = $("#excelexportUrl").val();
        //var quotationId = $("#quotationId").val();
        //var currentVersion = $("#currentVersion").val();
        if (currentVersion != 0) {
            window.location.href = excelexportUrl + '/' + quotationId + '/' + VersionId;
        } else {
            swal("Oops!", "You haven't selected any version yet.", "error");
        }
    };


    MainFormImport = function(){
        var mainformimportUrl = $("#mainformimportUrl").val();
        var quotationId = $("#quotationId").val();
        var currentVersion = $("#currentVersion").val();

        window.location.href = mainformimportUrl + '/' + quotationId + '/' + currentVersion;
    }

    $(function() {
        $('#QuoteSummaryDiscount').keyup();;
    });

    $(document).on('keyup','#QuoteSummaryDiscount',function(e){
        e.preventDefault();
        const QuoteSummaryDiscountValue = $(this).val(); // in percent
        const QuoteSummaryTotalDoorPriceValue = $('#QuoteSummaryTotalDoorPrice').val();
        const totalQSdiscountValue = QuoteSummaryTotalDoorPriceValue * QuoteSummaryDiscountValue/100;
        $('#QSdiscountValue').html(totalQSdiscountValue);
        const totalGBP = QuoteSummaryTotalDoorPriceValue - totalQSdiscountValue;
        $('.total_amount > span').html(totalGBP)
    })




    @if(isset($Count))
        let CustomerCounter = {{$Count}};
    @else
        let CustomerCounter = 1;
    @endif

    $(document).on('click','.deleteQuotDeliverAddr',function(e){
        e.preventDefault();
        let quotationDeliveryAddressID = $(this).siblings('.QuotDeliverAddrID').val();
        let self = $(this);
        $.ajax({
            type: "POST",
            url: "{{route('delquotationDeliveryAddress')}}",
            data: {
                _token: $("#_token").val(),
                quotationDeliveryAddressID: quotationDeliveryAddressID
            },
            dataType: 'Json',
            success: function(data) {
                if (data == 1) {
                    $(self).parent('div').parent('div').remove();
                }
            },
            error: function(data) {
                swal("Oops!", "Something went wrong. Please try again.", "error");
            }
        });
    })
    function RemoveCustomerSection(id){
        $("#customer-details-"+ id).remove();
    }

    $(document).on("click","#add-customer-detail",function(){
        CustomerCounter = CustomerCounter + 1;
        var CustomerDetails = `
        <input type="hidden" name="quotation_sitedeliveryaddressID[]">
        <div class="col-sm-12" id="customer-details-`+ CustomerCounter +`">
            <div class="card-header">
                <h5 class="card-title" style="margin-top: 10px">Site Delivery Address</h5>
            </div>
            <div>
                <a style="float: right; margin-right: 10px; margin-top: -45px" href="javascript:void(0);" onclick="RemoveCustomerSection(`+ CustomerCounter +`);" class="btn-shadow btn btn-danger">
                    <i class="fa fa-remove"></i>
                </a>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label for="Address1">Address 1<span class="text-danger">*</span></label>
                        <input type="text" class="form-control" name="Address1[]" required>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label for="Address2">Address 2</label>
                        <input type="text" class="form-control" name="Address2[]">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="Country">Country</label>
                        <input type="text" class="form-control" name="Country[]">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="City">City</label>
                        <input type="text" class="form-control" name="City[]">
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="position-relative form-group">
                        <label for="PostalCode">Postal Code/Eircode</label>
                        <input type="text" class="form-control" name="PostalCode[]">
                    </div>
                </div>
            </div>
        </div>
        `;
        $("#customer-details-section").append(CustomerDetails);
    });

    $(document).on('click','.closeModal',function(){
        $('#RejectquotationModal').removeClass('show')
        $('#RejectquotationModal').addClass('fade')
    })
</script>
    @endsection


    <div id="quotation-version-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 mb-2">
                            <p>Select Revision</p>
                            <select name="select-version" id="select-version" class="form-control">
                                <option value="">Select Revision</option>
                                {{--<option value="newitem">New Items</option>--}}
                                @if(is_array($version))
                                @foreach($version as $row)
                                <option value="{{$row['id']}}">Revision-{{$row['version']}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" onclick="ApplyVersionFilters()">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->

    <div id="create-new-version-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Create new version</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        {{--<div class="col-sm-12 mb-2">--}}
                        {{--<div class="position-relative form-group">--}}
                        {{--<input type="radio" id="previous-version" onclick="" class="form-control" name="version_option" >--}}
                        {{--<label for="previous-version" >Want to copy item's from previous version.</label>--}}
                        {{--<input type="radio" id="new-version" class="form-control" name="version_option" >--}}
                        {{--<label for="new-version" >New Version</label>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        <div class="col-sm-12 mb-2">
                            <p>Select Revision</p>
                            <select name="createNewSelectVersion" id="createNewSelectVersion" class="form-control">
                                <option value="">Select any revision</option>
                                @if(is_array($version))
                                @foreach($version as $row)
                                <option value="{{$row['id']}}">Revision-{{$row['version']}}</option>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" onclick="CreateNewVersion()">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>




    @if($quotation->QuotationStatus == 'Reject')
    <div id="RejectquotationModal" class="modal show" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h3>Rejected Quotation</h3>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 mb-2">
                            <div class="alert alert-danger" role="alert">
                                <h4 class="alert-heading"> Reason </h4>
                                <p>{{ $quotation->rejectreason }}</p>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default closeModal" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @endif
