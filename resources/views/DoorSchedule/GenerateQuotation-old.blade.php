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

</style>



<div class="app-main__outer">
    <div class="app-main__inner p-0">
        <div class="main-card mb-3">
            <div class="card-body">
                <!-- table-striped -->
                <div class="alert response-section" role="alert" style="display:none;"></div>
                <div>
                    <div class="action_options">
                    @if(!empty($generatedId))
                        <h2 class="origin_heading">Quote Search / {{$generatedId}}</h2>
                        @else
                        <h2 class="origin_heading">Door Schedule / {{$ArcgeneratedId}}</h2>
                        @endif
                        <ul class="float-right">                            
                            <li>
                                <button type="button" class="btn btn-primary" onClick="openVersionModal();">Select
                                    Version</button>
                            </li>
                            <li>
                                <div class="dropdown">
                                    <a class="btn btn-light dropdown-toggle" data-toggle="dropdown">MORE</a>
                                    <ul class="dropdown-menu drop_style">
                                        @if(Auth::user()->UserType=='2')
                                        <li><a href="javascript:void(0);"
                                                onClick="CreateNewVersionModal('{{ ($maxVersion !== null)?$maxVersion:0 }}');">Create
                                                Version</a></li>
                                        @endif
                                        <li><a href="javascript:void(0);" onClick="PrintInvoice();">Generate PDF</a></li>
                                        <li><a href="javascript:void(0);" onClick="BuildOfMaterial();">Generate Bill Of Material</a></li>
                                        <li><a href="javascript:void(0);" onClick="MainFormImport();">Import</a></li>
                                        <li><a href="javascript:void(0);" onClick="ExcelExport();">Export</a></li>
                                        <li><a href="javascript:void(0);" onClick="CopyQuotation({{$quotationId}});">Copy</a></li>
                                        <li><a href="javascript:void(0);" onClick="DeleteQuotation();">Delete</a></li>
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
                        <div class="col-sm-9 p-0">
                            <div class="table_list_row">
                                <h5 class="float-left customerData">
                                    @if(isset($customerDetails->FirstName) && isset($customerDetails->LastName))
                                    <span>{{ $customerDetails->FirstName .' '. $customerDetails->LastName }}</span>
                                    <span>{{ isset($customerDetails->CstSiteAddressLine1)?$customerDetails->CstSiteAddressLine1:"" }}</span>
                                    @else
                                    <span>Customer is not selected</span>
                                    @endif
                                </h5>
                                <a href="javascript:void(0);" class="btn btn-default float-right"
                                    onclick="ChangeCustomerBtn()" id="ChangeCustomerBtn">Select Customer</a>
                                <!-- <a href="javascript:void(0);" class="btn btn-default float-right"
                                    onclick="ChangeQuotationFormBtn()" id="ChangeQuotationFormBtn">Edit Header</a> -->
                            </div>
                            <div class="table_list_row pb-2">
                                <span class="ShowVersion pull-left pt-2">
                                    @if($selectQV['selectVersion'] > 0)
                                        <b>Selected Version : {{$selectQV['selectVersion']}}</b>
                                    @endif
                                </span>
                                <!-- <a href="{{url('quotation/request/'.$quotationId)}}" class="btn btn-primary float-right" style="margin-left:5px;">Add Bulk Item</a> -->

                                
                               

                                <a href="javascript:void(0);" onclick="ShowAddItemOption();"
                                    class="btn btn-primary float-right @if($additem == 1){{ 'customDisabled' }}@endif"
                                    id="add-item">Add Item</a>


                            </div>
                            <div class="main-card mb-3" id="add-item-section" style="display: none;">
                                <div class="col-sm-12 mt-3">
                                    <h3 class="card-title">
                                       
                                        <a href="javascript:void(0);" onclick="$('#add-item-section').hide();"
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
                                                            <div class="col-sm-6 p-0 pr-1">
                                                                <div class="Quote_tems">
                                                                    <img src="{{url('/')}}/images/strebord.png">
                                                                    <a href="#">Strebord</a>
                                                                    <p>Configurable On Configuration</p>
                                                                    <a href="javascript:void(0);" data-type="strebord"
                                                                        class="configure_btn">Configure Door Type</a>
                                                                    <a href="javascript:void(0);"
                                                                        data-type="strebord" class="configure_btn configure_door_btn">Add
                                                                        To <br> Door Type</a>
                                                                </div>
                                                            </div>
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
                                <div class="card-header">
                                    <h3 class="w-100">
                                        Edit Headers
                                        <a href="javascript:void(0);" onclick="$('#edit-header-section').hide();"
                                            class="add_button"><i class="fa fa-times"></i></a>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <form id="signupForm" enctype="multipart/form-data" method="post"
                                        action="{{route('quotation/store')}}">
                                        {{csrf_field()}}
                                        <input type="hidden" name="quotationId" value="{{$quotationId}}">
                                        <input type="hidden" name="ShippingAddressId" id="ShippingAddressId">
                                        <input type="hidden" name="ProjectId" id="ProjectId"
                                            value="@if(isset($projectId)){{$projectId}}@else{{'0'}}@endif">
                                        <div class="row" data-select2-id="5">
                                            <div class="col-sm-4">
                                                <div class="card-header">
                                                    <h5 class="card-title" style="margin-top: 10px">Quote Information
                                                    </h5>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="ProductName" class="">Expiration Date<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control datepicker"
                                                            name="ExpiryDate"
                                                            value="@if(!empty($quotation->ExpiryDate)){{ date('d-m-Y',strtotime($quotation->ExpiryDate)) }}@endif"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="ProductName" class="">Quote Name<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="QuotationName"
                                                            value="@if(!empty($quotation->QuotationName)){{$quotation->QuotationName}}@endif"
                                                            required>
                                                    </div>
                                                </div>
                                                <!-- <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="ProductName" class="">P.O. Number<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="ponumber"
                                                            value="@if(!empty($quotation->QuotationName)){{$quotation->PONumber}}@endif"
                                                            required>
                                                    </div>
                                                </div> -->
                                                <div class="row">
                                                    <div class="col-md-6">
                                                        <div class="position-relative form-group">
                                                            <label for="ProductName" class="">Quote Status</label>
                                                            <input type="text" readonly class="form-control"
                                                                name="" placeholder="Ex:. Open" value="@if(!empty($quotation->QuotationStatus)){{$quotation->QuotationStatus}}@endif">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" data-select2-id="4">
                                                        <div class="position-relative form-group">
                                                            <label for="Customer" class="">Change Status</label>
                                                            <select onchange="get_user_commission(this.value);"
                                                                name="QuotationStatus" class="form-control">
                                                                @if(!empty($quotation))
                                                                <option value="{{$quotation->QuotationStatus}}">
                                                                    {{$quotation->QuotationStatus}}</option>
                                                                @endif
                                                                <option value="">Select Status</option>
                                                                <option value="All" data-select2-id="2">All</option>
                                                                <option value="Open" data-select2-id="2">Open</option>
                                                                <option value="Closed" data-select2-id="2">Closed
                                                                </option>
                                                                <option value="Lost" data-select2-id="2">Lost</option>
                                                                <option value="Abandoned" data-select2-id="2">Abandoned
                                                                </option>
                                                                <option value="Expired" data-select2-id="2">Expired
                                                                </option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 p-0" data-select2-id="4">
                                                    <div class="position-relative form-group">
                                                        <!-- <label for="Customer" class="">Project<span class="text-danger">*</span></label>
                                            <select id="gggg" onchange="get_user_commission(this.value);" name="customerId" class="form-control multiselect-dropdown select2-hidden-accessible" data-select2-id="ggg" tabindex="-1" aria-hidden="true">

                                            </select> -->
                                                        <label for="Customer">Select Project</label>
                                                        <select name="projectId" class="form-control">
                                                            <option value="">Select Project</option>
                                                            @foreach($ProjectTable as $Project)
                                                            <option value="{{$Project['id']}}" @if($quotation->ProjectId
                                                                ==
                                                                $Project['id']){{'selected'}}@endif
                                                                >{{$Project['ProjectName']}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="ProductName" class="">Report Currency <span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="Currency"
                                                            value="@if(!empty($quotation->Currency)){{$quotation->Currency}}@endif"
                                                            required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="ProductName" class="">Exchange Rate<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="ExchangeRate"
                                                            value="@if(!empty($quotation->ExchangeRate)){{$quotation->ExchangeRate}}@endif"
                                                            required>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="card-header">
                                                    <h5 class="card-title" style="margin-top: 10px">Contact Information
                                                    </h5>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="ProductName" class="">Contact</label>
                                                        <input type="text" class="form-control" name="Contact"
                                                            value="@if(!empty($QuotationContactInformation->Contact)){{$QuotationContactInformation->Contact}}@endif">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="Email" class="">Email<span
                                                                class="text-danger">*</span></label>
                                                        <input type="email" class="form-control" name="Email"
                                                            value="@if(!empty($QuotationContactInformation->Email)){{$QuotationContactInformation->Email}}@endif">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="ProductName" class="">Phone</label>
                                                        <input type="text" pattern="\d" class="form-control"
                                                            name="Phone"
                                                            value="@if(!empty($QuotationContactInformation->Phone)){{$QuotationContactInformation->Phone}}@endif">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="Mobile" class="">Mobile</label>
                                                        <input type="text" pattern="\d" class="form-control"
                                                            name="Mobile"
                                                            value="@if(!empty($QuotationContactInformation->Mobile)){{$QuotationContactInformation->Mobile}}@endif">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="ProductName" class="">Fax</label>
                                                        <input type="text" class="form-control" name="Fax"
                                                            autocomplete="off"
                                                            value="@if(!empty($QuotationContactInformation->Fax)){{$QuotationContactInformation->Fax}}@endif">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="card-header">
                                                    <h5 class="card-title" style="margin-top: 10px">Ship To Information
                                                    </h5>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <input type="checkbox" id="oneTimeShipTo" name="oneTimeShipTo"
                                                            value="1">
                                                        <label for="oneTimeShipTo"> One time ship to</label><br>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="Name" class="">Name<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="Name"
                                                            value="@if(!empty($QuotationShipToInformation->Name)){{$QuotationShipToInformation->Name}}@endif" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="Country" class="">Country</label>
                                                        <input type="text" class="form-control" name="Country"
                                                            value="@if(!empty($QuotationShipToInformation->Country)){{$QuotationShipToInformation->Country}}@endif">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="Address1" class="">Address 1<span
                                                                class="text-danger">*</span></label>
                                                        <input type="text" class="form-control" name="Address1"
                                                            value="@if(!empty($QuotationShipToInformation->Address1)){{$QuotationShipToInformation->Address1}}@endif" required>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="Address2" class="">Address 2</label>
                                                        <input type="text" class="form-control" name="Address2"
                                                            value="@if(!empty($QuotationShipToInformation->Address2)){{$QuotationShipToInformation->Address2}}@endif">
                                                    </div>
                                                </div>

                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="Address2" class="">Address 3</label>
                                                        <input type="text" class="form-control" name="Address3"
                                                            value="@if(!empty($QuotationShipToInformation->Address3)){{$QuotationShipToInformation->Address3}}@endif">
                                                    </div>
                                                </div>

                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="City" class="">City</label>
                                                        <input type="text" class="form-control" name="City"
                                                            value="@if(!empty($QuotationShipToInformation->City)){{$QuotationShipToInformation->City}}@endif">
                                                    </div>
                                                </div>

                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="PostalCode" class="">Postal Code</label>
                                                        <input type="text" class="form-control" name="PostalCode"
                                                            value="@if(!empty($QuotationShipToInformation->PostalCode)){{$QuotationShipToInformation->PostalCode}}@endif">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="card-header">
                                                    <h5 class="card-title" style="margin-top: 10px">Shipping Information
                                                    </h5>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="ProductName" class="">Shipping Policy</label>
                                                        <input type="text" class="form-control" name="ShippingPolicy"
                                                            value="@if(!empty($QuotationShipToInformation->ShippingPolicy)){{$QuotationShipToInformation->ShippingPolicy}}@endif">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="Email" class="">Shipping Carrier</label>
                                                        <input type="text" class="form-control" name="ShippingCarrier"
                                                            value="@if(!empty($QuotationShipToInformation->ShippingCarrier)){{$QuotationShipToInformation->ShippingCarrier}}@endif">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="ProductName" class="">Shipping Method</label>
                                                        <input type="text" pattern="\d" class="form-control"
                                                            name="ShippingMethod"
                                                            value="@if(!empty($QuotationShipToInformation->ShippingMethod)){{$QuotationShipToInformation->ShippingMethod}}@endif">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="Mobile" class="">Freight Terms</label>
                                                        <input type="text" pattern="\d" class="form-control"
                                                            name="FreightTerms"
                                                            value="@if(!empty($QuotationShipToInformation->FreightTerms)){{$QuotationShipToInformation->FreightTerms}}@endif">
                                                    </div>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="ProductName" class="">RequestShip Date</label>
                                                        <input type="text" class="form-control datepicker"
                                                            name="RequestShipDate" autocomplete="off"
                                                            value="@if(!empty($QuotationShipToInformation->RequestShipDate)){{ date('d-m-Y',strtotime($QuotationShipToInformation->RequestShipDate)) }}@endif">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-sm-4">
                                                <div class="card-header">
                                                    <h5 class="card-title" style="margin-top: 10px">Payment Information
                                                    </h5>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="Payment Method" class="">Payment Method</label>
                                                        <input type="text" class="form-control" name="PaymentMethod"
                                                            value="@if(!empty($QuotationShipToInformation->PaymentMethod)){{$QuotationShipToInformation->PaymentMethod}}@endif">
                                                    </div>
                                                </div>

                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="PaymentTerms" class="">Payment Terms</label>
                                                        <input type="text" class="form-control" name="PaymentTerms"
                                                            value="@if(!empty($QuotationShipToInformation->PaymentTerms)){{$QuotationShipToInformation->PaymentTerms}}@endif">
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-sm-4">
                                                <div class="card-header">
                                                    <h5 class="card-title" style="margin-top: 10px">Comments</h5>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="ProductName" class="">Header</label>
                                                        <textarea class="form-control" name="Header"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="Terms" class="">Terms</label>
                                                        <textarea class="form-control" name="Terms"></textarea>
                                                    </div>
                                                </div>
                                                <div class="col-md-12 p-0">
                                                    <div class="position-relative form-group">
                                                        <label for="Pricing" class="">Pricing</label>
                                                        <textarea class="form-control" name="Pricing"></textarea>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr>
                                        <button type="submit" class="btn btn-success">Submit Details</button>
                                    </form>
                                </div>
                            </div>

                            <div class="main-card mb-3 custom_card" id="customer-list" style="display: none;">
                                <div class="card-header">
                                    <h3 class="w-100">
                                        Customer's List
                                        <a href="javascript:void(0);" onclick="$('#customer-list').hide();"
                                            class="add_button"><i class="fa fa-times"></i></a>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="large_search">
                                        <input type="text" name="search" id="searchCustomer" class="form-control input-lg"
                                            placeholder="Search Customer By Name">
                                        <i class="fa fa-search"></i>   
                                    </div>
                                    <span id="AppendCustTbl"></span>
                                    <table style="width: 100%;" id="example"
                                        class="table table-hover table-striped custTbl">
                                        <tbody>
                                            @if(!empty($customers))
                                            @php
                                            $i = 1;
                                            @endphp
                                            @foreach($customers as $row)
                                            <tr>
                                                <td>{{$i}}</td>
                                                <td><a href="{{url('customer/details/'.$row->id)}}">{{$row->FirstName}}
                                                        {{$row->LastName}}</a></td>
                                                <td>{{$row->CstSiteAddressLine1}}</td>
                                                <td>
                                                    <a class="btn btn-dark"
                                                        onclick="selectCustomer('{{$row->id}}','{{$generatedId}}','{{$row->CstCompanyName}}','{{$row->FirstName}} {{$row->LastName}}','{{$row->CstSiteAddressLine1}}');"
                                                        href="javascript:void(0);">Select</a>
                                                </td>
                                            </tr>
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
                                                <th>DoorType</th>
                                                <th>Label</th>
                                                <th>Item</th>
                                                <th>Qty</th>
                                                <th>Price</th>
                                                <th>Total</th>
                                                <th></th>
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
                                                <td>{{$row->DoorType}}</td>
                                                <td>{{$row->doorNumber}}</td>
                                                <td>
                                                    @if($row->DoorsetType=='DD')
                                                        {{'Double Door'}}
                                                    @elseif($row->DoorsetType=='SD')
                                                        {{'Single Door'}}
                                                    @else 
                                                        {{"Leaf and a half"}} 
                                                    @endif
                                                </td>
                                                <td>
                                                    <input type="number" style="width: 100%;" readonly id="quantity"
                                                        value="1" name="quantity" min="1" max="100"
                                                        class="quantity_{{$index}}">
                                                </td>
                                                <td>{{$row->DoorsetPrice}}</td>
                                                <td>{{$row->DoorsetPrice}}</td>
                                                <td class="text-center">
                                                    <div class="dropdown">
                                                        <a class="dropdown-toggle btn btn-light" type="button"
                                                            data-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></a>
                                                        <ul class="dropdown-menu drop_style">
                                                            <li><a
                                                                    href="{{{url('quotation/edit-configuration-cad-item/'.$row->itemId.'/'.$version_id)}}}">Edit</a>
                                                            </li>
                                                            <li><a href="#">Name Configuration</a></li>
                                                            <li><a href="#">Adjust Price</a></li>
                                                            <li><a href="#">Comment</a></li>
                                                            <li><a href="#">Copy</a></li>
                                                            <li><a href="#">Export</a></li>
                                                            <li><a href="#">Remove</a></li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php $index++; $SI++; ?>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-3 p-0">
                            <div class="quote_card">
                                <div class="quote_card_title">
                                    <h3>Quote Summary</h3>
                                </div>
                                <div class="quote_card_header mt-4">
                                    <h4>Header 
                                        <a href="#"  onclick="ChangeQuotationFormBtn()" id="ChangeQuotationFormBtn">Edit Header Details</a>
                                    </h4>
                                    <div class="quote_status">Open</div>
                                </div>
                                <form action="{{route('QuoteSummary')}}" method="post">
                                    {{csrf_field()}}
                                    <input type="hidden" name="quotationId" value="{{$quotationId}}">
                                    <div class="quote_card_form">
                                        <div class="form-group">
                                            <label>Contact</label>
                                            <select name="CustomerId" id="CustomerId" required>
                                                <option value="">Select a Customer</option>
                                                @if(!empty($customers))
                                                @foreach($customers as $row)
                                                <option value="{{$row->id}}"
                                                    @if(!empty($quotation->CustomerId))
                                                        @if($quotation->CustomerId == $row->id)
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
                                            <select required>
                                                <option value="">Same as Bill To</option>
                                                @if(!empty($QuotationShipToInformation->Name))                                                
                                                <option value="{{$QuotationShipToInformation->id}}">
                                                    {{$QuotationShipToInformation->Name}}
                                                </option>
                                                @endif
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label>P.O. Number</label>
                                            <input type="text" name="PONumber" required>
                                        </div>
                                    </div>
                                    <div class="quote_card_header mt-4">
                                        <h4>Pricing </h4>
                                    </div>
                                    <div class="quote_pricing">
                                        
                                        <p>Subtotal(List) 
                                            <span>@if(!empty($TotalDoorPrice)){{$TotalDoorPrice}} @else {{'0.00'}}@endif</span>
                                        </p>
                                        <p>Discount(% Off List) <span id="QSdiscountValue">0.00</span></p>
                                        <input type="text" id="QuoteSummaryDiscount" placeholder="0.00">
                                        <input type="hidden" id="QuoteSummaryTotalDoorPrice" value="@if(!empty($TotalDoorPrice)){{$TotalDoorPrice}} @else {{'0.00'}}@endif">
                                        <h4 class="total_amount">Total(GBP) <span>@if(!empty($TotalDoorPrice)){{$TotalDoorPrice}} @else {{'0.00'}}@endif</span></h4>
                                        <button type="submit" class="submit_button w-100 mt-5">CONVERT TO ORDER</button>
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
    <!-- <input type="hidden" name="addItemUrl"  value="{{url('/quotation/singleconfigurationitem')}}" /> -->
    <input type="hidden" name="addItemUrl2" id="addItemUrl2" value="{{url('/quotation/request')}}" />
    <input type="hidden" name="printInvoiceUrl" id="printInvoiceUrl" value="{{url('/quotation/printinvoice')}}" />
    <input type="hidden" name="buildofmaterialUrl" id="buildofmaterialUrl" value="{{url('/quotation/generateBOM')}}" />
    <input type="hidden" name="quotationId" id="quotationId" value="{{$quotationId}}" />
    <input type="hidden" name="version" id="version" value="{{ ($maxVersion !== null)?$maxVersion:0 }}" />
    <input type="hidden" name="currentVersion" id="currentVersion" value="{{ ($selectQV['selectVersionID'] > 0)?$selectQV['selectVersionID']:0 }}" />
    <input type="hidden" name="customer_id" id="customer_id" value="{{ $quotation->CustomerId }}" />
    <input type="hidden" name="flag" id="flag" value="{{ $quotation->flag }}" />

    <button style="display: none;" type="button" id="success-alert" data-type="success"
        class="btn btn-success btn-show-swal"></button>

    <input type="hidden" name="excelexportUrl" id="excelexportUrl" value="{{url('/quotation/excelexport')}}" />
    <input type="hidden" name="mainformimportUrl" id="mainformimportUrl" value="{{url('quotation/excel-upload/')}}" />

    @endsection
    @section('js')
    <script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    let UniversalToken = $("#_token").val();
    
    BuildOfMaterial = function(){
        var buildofmaterialUrl = $("#buildofmaterialUrl").val();
        var quotationId = $("#quotationId").val();
        var currentVersion = $("#currentVersion").val();
        if (currentVersion != 0) {
            window.location.href = buildofmaterialUrl + '/' + quotationId + '/' + currentVersion;
        } else {
            swal("Oops!", "You haven't selected any version yet.", "error");
        }
    };

     

    
        
   





    
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
                swal("Warning!", "Select a customer first.", "warning");
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
    }

    function ShowAddItemOption() {
        $('#customer-list').hide();
        $('#add-item-section').show();
        $('#edit-header-section').hide();
    }

    function ChangeCustomerBtn() {
        $('#customer-list').show();
        $('#add-item-section').hide();
        $('#edit-header-section').hide();
    }

    $(document).on("click", ".configure_btn", function(e) {
        var type = $(this).data("type");
        var addItemUrl = $("#addItemUrl").val();
        var quotationId = $("#quotationId").val();
        var currentVersion = $("#currentVersion").val();

        if (type == "strebord") {
            if (currentVersion != 0) {
                window.location.href = addItemUrl + '/' + quotationId + '/' + currentVersion;
            } else {
                window.location.href = addItemUrl + '/' + quotationId;
            }
        }
    });
    $(document).on("click", ".configure_door_btn", function(e) {
        var type = $(this).data("type");
        var addItemUrl = $("#addItemUrl2").val();
        var quotationId = $("#quotationId").val();
        var currentVersion = $("#currentVersion").val();

        if (type == "strebord") {
            if (currentVersion != 0) {
                window.location.href = addItemUrl + '/' + quotationId + '/' + currentVersion;
            } else {
                window.location.href = addItemUrl + '/' + quotationId+'/'+currentVersion;
            }
        }
    });

    //    let popupParent = document.querySelector(".popup-parent1");
    //    let btn = document.getElementById("btn");
    //    let btnClose = document.querySelector(".close");
    //    let mainSection = document.querySelector(".mainSection");
    //
    //    $(".popup-parent1").css({
    //        'display': 'none'
    //    });
    //    btn.addEventListener("click", showPopup);
    //
    //    function showPopup() {
    //        popupParent.style.display = "block";
    //    }
    //
    //    btnClose.addEventListener("click", closePopup);
    //
    //    function closePopup() {
    //        popupParent.style.display = "none";
    //    }
    //    popupParent.addEventListener("click", closeOutPopup);
    //
    //    function closeOutPopup(o) {
    //        if (o.target.className == "popup-parent") {
    //            popupParent.style.display = "none";
    //        }
    //    }

    function selectCustomer(customerId, QuotationId, company, name, address) {

        var html = '<span>' + name + '</span><span>' + address + '</span>';
        $(".customerData").empty().append(html);
        $("#ChangeCustomerBtn").text("Change Customer");
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
            success: function(data) { console.log(data.selectCC)
                if (data.status == "success") {
                    $('#customer_id').val(customerId);
                    $("#CustomerId option[value='"+customerId+"']").remove();
                    $('#CustomerId').append(data.selectCC);

                    //                    if(data.flag == 1 && data.customerId != ''){
                    //                        $('#generateInvoice').removeAttr('disabled');
                    //                    }

                    //                    var html = '<span>' + company + '</span><span>' + name + '</span><span>' + address + '</span>';
                    //                    $(".customerData").empty().append(html);
                    //                    $("#ChangeCustomerBtn").text("Change Customer");
                    //                    $("#customer-list").hide();
                } else {
                    //                    swal("Oops!!", data.message, "error");
                }
            },
            error: function(data) {
                //                swal("Oops!!", "Something went wrong. Please try again.", "error");
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
            if (VersionId == 0) {
                swal("Oops!", "Something went wrong. Please try again.", "error");
                return false;
            }

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

    PrintInvoice = function() {
        var printInvoiceUrl = $("#printInvoiceUrl").val();
        var quotationId = $("#quotationId").val();
        var currentVersion = $("#currentVersion").val();
        if (currentVersion != 0) {
            window.location.href = printInvoiceUrl + '/' + quotationId + '/' + currentVersion;
        } else {
            swal("Oops!", "You haven't selected any version yet.", "error");
        }
    };

    ExcelExport = function() {
        var excelexportUrl = $("#excelexportUrl").val();
        var quotationId = $("#quotationId").val();
        var currentVersion = $("#currentVersion").val();
        if (currentVersion != 0) {
            window.location.href = excelexportUrl + '/' + quotationId + '/' + currentVersion;
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

    $(document).on('keyup','#QuoteSummaryDiscount',function(e){
        e.preventDefault();
        const QuoteSummaryDiscountValue = $(this).val(); // in percent
        const QuoteSummaryTotalDoorPriceValue = $('#QuoteSummaryTotalDoorPrice').val();
        const totalQSdiscountValue = QuoteSummaryTotalDoorPriceValue * QuoteSummaryDiscountValue/100;
        $('#QSdiscountValue').html(totalQSdiscountValue);
        const totalGBP = QuoteSummaryTotalDoorPriceValue - totalQSdiscountValue;
        $('.total_amount > span').html(totalGBP)
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
                            <p>Select Version</p>
                            <select name="select-version" id="select-version" class="form-control">
                                <option value="">Select Version</option>
                                {{--<option value="newitem">New Items</option>--}}
                                @if(is_array($version))
                                @foreach($version as $row)
                                <option value="{{$row['id']}}">Version-{{$row['version']}}</option>
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
                        {{--<label for="previous-version" class="">Want to copy item's from previous version.</label>--}}
                        {{--<input type="radio" id="new-version" class="form-control" name="version_option" >--}}
                        {{--<label for="new-version" class="">New Version</label>--}}
                        {{--</div>--}}
                        {{--</div>--}}
                        <div class="col-sm-12 mb-2">
                            <p>Select Version</p>
                            <select name="createNewSelectVersion" id="createNewSelectVersion" class="form-control">
                                <option value="">Select any version</option>
                                @if(is_array($version))
                                @foreach($version as $row)
                                <option value="{{$row['id']}}">Version-{{$row['version']}}</option>
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