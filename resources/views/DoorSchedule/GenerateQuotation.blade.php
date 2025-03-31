@extends('layouts.Master')
@section('main_section')
    <style>
        .customDisabled {
            opacity: .54;
            pointer-events: none;
            cursor: default;
        }
        .drop_style {
            transform: translate3d(-165px, 33px, 0px) !important;
        }
        .color_red {
            background-color: #f5c7c9;
        }
        .description_msg {
            font-size: 13px !important;
            line-height: 18px !important;
            font-weight: 500 !important;
            color: #3d3d3d !important;
            background: #f3f3f3;
            padding: 12px 5px;
            border-radius: 4px;
        }
        .nonConfigData {
            font-size: 16px !important;
            color: #303641 !important;
            text-align: left;
        }
        .nonConfigData span {
            font-weight: bold;
        }

        .inline_tab {
            display: flex;
            padding: 0px !important;
        }
        .inline_tab li {
            border-right: 1px solid #d3d3d3;
            padding: 15px 15px !important;
            flex: 1 1;
            text-align: center;
        }

        .inline_tab li:last-child{
            border-right: 0px !important
        }

        .inline_tab li a {
            padding: 6px 15px;
            display: block;
        }
        .activeNC {
            background: #303641;
            border: 1px solid #303641;
        }
        .SideScreen_btn {
            background: #338dff;
            display: inline-block !important;
            max-width: 120px;
            width: 100%;
            padding: 8px 0px;
            font-size: 11px !important;
            color: #fff !important;
            border-radius: 3px;
            text-transform: uppercase;
            text-align: center;
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
                            @if (!empty($generatedId))
                                <h2 class="origin_heading">Quote Search / {{ $generatedId }}</h2>
                            @else
                                <h2 class="origin_heading">Door Schedule / {{ $ArcgeneratedId }}</h2>
                            @endif
                            <ul class="float-right">
                                <li>
                                    <button type="button" class="btn btn-primary" onClick="openVersionModal();">Select
                                        Revision</button>
                                </li>
                                <li>
                                    <div class="dropdown">
                                        <a class="btn btn-light dropdown-toggle" data-toggle="dropdown">MORE</a>
                                        <ul class="dropdown-menu drop_style">
                                            @if (Auth::user()->UserType == '2' || Auth::user()->UserType == '3')
                                                <li><a href="javascript:void(0);"
                                                        onClick="CreateNewVersionModal('{{ $maxVersion !== null ? $maxVersion : 0 }}');">Create
                                                        Revision</a></li>
                                            @endif
                                            <li><a href="javascript:void(0);" onClick="PrintInvoice();"
                                                    id="">Generate Quote</a></li>
                                            <li><a href="javascript:void(0);" onClick="PrintInvoiceInExcel();">Generate
                                                    Doorset Schedule Excel</a></li>
                                            {{--  <li><a href="javascript:void(0);" onClick="BuildOfMaterial();">Generate Bill Of Material</a></li>  --}}
                                            <li><a href="javascript:void(0);" onClick="BomCalculation();">Generate Bom
                                                    Calculation</a></li>
                                            <li><a href="javascript:void(0);" onClick="ExportBomCalculation();">Export BOM Calculation Excel</a></li>
                                            <li><a href="javascript:void(0);" onClick="ScreenBomCalculation();">Screen Bom
                                                Calculation</a></li>
                                            <li><a href="javascript:void(0);" onClick="ExportDoorTypeBom();">Export Door Type BOM Excel</a></li>
                                            <li><a href="javascript:void(0);" onClick="ExportSideScreen();">Side Screen Cut List</a></li>
                                            <li><a href="javascript:void(0);" onClick="ExportIronmongery();">Export Ironmongery Excel</a></li>
                                            <li><a href="javascript:void(0);" onClick="DoorOrderSheet();">Door Order Sheet BOM</a></li>
                                            <li><a href="javascript:void(0);" onClick="FrameTransoms();">Frames & Transoms BOM</a></li>
                                            <li><a href="javascript:void(0);" onClick="GlassOrderSheet();">Glass Order Sheet BOM</a></li>
                                            <li><a href="javascript:void(0);" onClick="GlazingBeadsDoors();">Glazing Beads for Doors BOM</a></li>
                                            <li><a href="javascript:void(0);" onClick="QualityControl();">Quality Control</a></li>
                                            {{-- <li><a href="{{url('quotation/generateBOMPrint')}}/{{$quotation->id}}">Generate Bom Calculation</a></li> --}}
                                            <li><a
                                                    href="{{ url('quotation/door-list-show') }}/{{ $quotation->id }}/{{ $selectQV['selectVersionID'] > 0 ? $selectQV['selectVersionID'] : 0 }}">Door
                                                    List</a></li>
                                            <li><a href="javascript:void(0);" onClick="MainFormImport();">Import</a></li>
                                            {{--  <li><a href="javascript:void(0);" onClick="ExcelExport();">Export Old</a></li>  --}}
                                            <li><a href="javascript:void(0);" onClick="ExcelExportNew();">Export</a></li>
                                            <li><a href="javascript:void(0);"
                                                    onClick="CopyQuotation({{ $quotationId }});">Copy</a></li>
                                            <li><a href="javascript:void(0);" onClick="favoriteItemList();">favourite</a>
                                            </li>
                                            <li><a href="javascript:void(0);" onClick="DeleteQuotation();">Delete</a></li>
                                            <li><a href="javascript:void(0);" onClick="SendToClient();">Send To Client</a>
                                            </li>
                                        </ul>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        @if (session()->has('success'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                {{ session()->get('success') }}
                            </div>
                        @endif
                        @if (session()->has('successes'))
                            <input type="hidden" id="successes" value="successes">
                            <input type="hidden" id="successesvalue" value="{{ session()->get('successes') }}">
                        @endif
                        @if (session()->has('errors'))
                            <input type="hidden" id="successes" value="errors">
                            <input type="hidden" id="errorsvalue" value="{{ session()->get('errors') }}">
                        @endif
                        <span class="error"></span>
                        <span class="success"></span>
                        <div class="row w-100 m-0">
                            @if (Auth::user()->UserType == 1 || Auth::user()->UserType == 2 || Auth::user()->UserType == 3)
                                <div class="col-sm-9 p-0">
                                @else
                                    <div class="col-sm-12 p-0">
                            @endif
                            @if (Auth::user()->UserType == 1 || Auth::user()->UserType == 2 || Auth::user()->UserType == 3)
                                <div class="table_list_row" style="background: #d0cbcb;padding: 8px;margin-top: -1px;">
                                    <h5 class="float-left customerData" style="margin: 6px;">
                                        @if (isset($customerDetails->CstCompanyName))
                                            <span>{{ $customerDetails->CstCompanyName }}</span>
                                            <span>{{ isset($customerDetails->CstCompanyAddressLine1) ? $customerDetails->CstCompanyAddressLine1 : '' }}</span>
                                        @else
                                            <span>Main Contractor is not selected</span>
                                        @endif
                                    </h5>
                                    <a href="javascript:void(0);" class="btn btn-primary float-right mx-1"
                                        onclick="ChangeCustomerBtn()" id="ChangeCustomerBtn">Select Main Contractor</a>

                                    {{--  <a href="javascript:void(0);" class="btn btn-primary float-right mx-1"
                                        onclick="ManualQuotationStatus()" id="ManualQuotationStatus">Accept/Reject Quote</a>  --}}
                                    <!-- <a href="ManualQuotationStatus:void(0);" class="btn btn-default float-right"
                                        onclick="ChangeQuotationFormBtn()" id="ChangeQuotationFormBtn">Edit Header</a> -->
                                </div>
                            @endif
                            <div class="table_list_row pb-2">
                                <span class="ShowVersion pull-left pt-2">
                                    @if ($selectQV['selectVersion'] > 0)
                                        <b>Selected Revision : {{ $selectQV['selectVersion'] }}</b>
                                    @endif
                                </span>
                                <!-- <a href="{{ url('quotation/request/' . $quotationId) }}" class="btn btn-primary float-right" style="margin-left:5px;">Add Bulk Item</a> -->
                                <a href="javascript:void(0);" onclick="ShowAddItemOption();" id="showall"
                                    class="btn btn-primary float-right @if ($additem == 1) {{ 'customDisabled' }} @endif"
                                    id="add-item">Add Doorset</a>
                                <a href="javascript:void(0);" onclick="addSideScreen();" id="addSideScreen"
                                    class="btn btn-primary float-right mx-1">Add Side Screen
                                </a>
                                <a href="javascript:void(0);" onclick="SideScreen();" id="SideScreenList"
                                    class="btn btn-primary float-right mx-1">Side Screens
                                </a>
                                <a href="javascript:void(0);" onclick="configurableNon(2);" id="config"
                                    class="btn btn-primary float-right mx-1">Non Configurable
                                </a>
                                <a href="javascript:void(0);" onclick="configurableNon(1);" id="nonConfig"
                                    class="btn btn-primary float-right mx-1 activeNC">Configurable
                                </a>
                            </div>
                            <div class="main-card mb-3" id="side-screen-section" style="display: none;">
                                <div class="card-body">
                                    <table class="table table-bordered table-striped">
                                        <thead class="table-header-bg">
                                            <tr class="text-white">
                                                <th>Line</th>
                                                <th>Fire Rating</th>
                                                <th>Screen Type</th>
                                                <th>Door No.</th>
                                                <th>Floor</th>
                                                <th>Glazing Type</th>
                                                <th>S.O. Width</th>
                                                <th>S.O. Height</th>
                                                <th>S.O. Depth</th>
                                                <th>Screen Price</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="versionData">
                                            @if (!empty($SideScreenData) && count($SideScreenData) > 0)
                                                <?php $index = 0;
                                                $SI = 1; ?>
                                                @foreach ($SideScreenData as $row)
                                                    @if (!empty($row->VersionId))
                                                        @php
                                                            $version_id = $row->VersionId;
                                                        @endphp
                                                    @else
                                                        @php
                                                            $version_id = 0;
                                                        @endphp
                                                    @endif
                                                    <tr>
                                                        <td>
                                                            {{ $SI }}
                                                            <input type="hidden" class=""
                                                                value="{{ $row->id }}">
                                                            <input type="hidden" class="doors_{{ $index }}"
                                                                value="{{ $row->screenMasterid }}">
                                                        </td>
                                                        <td>{{ $row->FireRating }}</td>
                                                        <td>{{ $row->ScreenType }}</td>
                                                        <td>{{ $row->screenNumber }}</td>
                                                        <td>{{ $row->floor }}</td>
                                                        <td>{{ $row->GlazingType }}</td>
                                                        <td>{{ $row->SOWidth }}</td>
                                                        <td>{{ $row->SOHeight }}</td>
                                                        <td>{{ $row->SODepth }}</td>
                                                        <td>{{ $row->ScreenPrice }}</td>
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="dropdown">
                                                                <a class="dropdown-toggle btn btn-light" type="button"
                                                                    data-toggle="dropdown"><i
                                                                        class="fa fa-ellipsis-h"></i></a>
                                                                <ul class="dropdown-menu drop_style">
                                                                    <li><a
                                                                            href="{{ url('quotation/edit-side-screen-item/'.$row->id.'/'.$version_id) }}">Edit Screen</a>
                                                                    </li>
                                                                    <li><a href="javascript:void(0);"
                                                                        onClick="CopyDoorSetScreen({{ $quotationId }},'{{ $row->screenMasterid }}');">Copy Screen</a>
                                                                    <li><a onclick="remove_item_screen('{{ $row->screenMasterid }}')"  href="#">Remove Screen</a></li>
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php $index++;
                                                    $SI++; ?>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="main-card mb-3" id="add-side-screen-section" style="display: none;">
                                <div class="card-body">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-sm-12 mt-3">
                                                <div class="CustomTabContent tab-content">
                                                    <div id="configurable" class="tab-pane active">
                                                        <h3 class="card-title ">Side Screen</h3>
                                                        <div class="row m-0">
                                                            <div class="col-sm-12 p-0 pr-1">
                                                                <div class="Quote_tems">
                                                                    <a href="#">Side Screen</a>

                                                                    <p>Configurable On Configuration</p>
                                                                    <a href="javascript:void(0);" data-type="" class="SideScreen_btn">Create <br>Side Screen</a>
                                                                    <a href="javascript:void(0);" data-type="" class="SideScreen_btn SideScreen_Additional_btn">Add  Additional <br> Side Screen</a>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="main-card mb-3" id="add-item-section" style="display: none;">
                                <div class="col-sm-12 mt-3">
                                    <h3 class="card-title">
                                        <a href="javascript:void(0);"
                                            onclick="$('#add-item-section').hide();$('#quotation-item-list').show();$('#NonConfig-item-list').css('display','none');$('#nonConfig').addClass('activeNC');$('#showall').removeClass('activeNC');"
                                            class="add_button"><i class="fa fa-times"></i></a>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="container-fluid">
                                        <div class="row">
                                            <div class="col-sm-12 p-0">
                                                <h5 class="card-title">Categories</h5>
                                                <ul class="CustomTabs tabs inline_tab">
                                                    <li class="active"><a data-toggle="tab"
                                                            href="#configurable">Configurable</a></li>
                                                    <li><a data-toggle="tab" href="#nonconfigurable">Non
                                                            Configurable</a></li>
                                                    <li><a data-toggle="tab" href="#all">All</a></li>
                                                </ul>
                                            </div>
                                            <div class="col-sm-12 mt-3">
                                                <div class="CustomTabContent tab-content">
                                                    <div id="configurable" class="tab-pane active">
                                                        <h3 class="card-title ">Configurable Items</h3>
                                                        <div class="row m-0">
                                                            {!! $configItem !!}
                                                        </div>
                                                    </div>
                                                    <div id="nonconfigurable" class="tab-pane fade">
                                                        <h3 class="card-title">Non Configurable Items</h3>
                                                        <div class="row">
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
                                @include('DoorSchedule.quotationForms.editheader')
                            </div>
                            <div class="main-card mb-3 custom_card" id="customer-list" style="display: none;">
                                <div class="card-header">
                                    <h3 class="w-100">
                                        Main Contractors List
                                        <a href="javascript:void(0);"
                                            onclick="$('#customer-list').hide();$('#quotation-item-list').show();$('#NonConfig-item-list').css('display','none');$('#nonConfig').addClass('activeNC');"
                                            class="add_button"><i class="fa fa-times"></i></a>
                                    </h3>
                                </div>
                                <div class="card-body">
                                    <div class="large_search">
                                        <input type="text" name="search" id="searchCustomer"
                                            class="form-control input-lg" placeholder="Search Main Contractor By Name">
                                        <i class="fa fa-search"></i>
                                    </div>
                                    <span id="AppendCustTbl"></span>
                                    <table style="width: 100%;" id="example"
                                        class="table table-hover table-striped custTbl">
                                        <tbody>
                                            @if (!empty($companykacustomer))
                                                @php
                                                    $i = 1;
                                                @endphp
                                                @foreach ($companykacustomer as $row)
                                                    @if ($row->CstCompanyName != '')
                                                        <tr>
                                                            <td>
                                                                <a
                                                                    href="{{ url('contractor/details/' . $row->CId) }}">{{ $row->CstCompanyName }}</a>
                                                            </td>
                                                            <td>{{ $row->CstCompanyPhone }}</td>
                                                            <td>{{ $row->CstCompanyAddressLine1 }}</td>
                                                            <td>
                                                                <a class="btn btn-dark"
                                                                    onclick="selectCustomer('{{ $row->id }}','{{ $generatedId }}','{{ $row->CstCompanyName }}','{{ $row->CstCompanyAddressLine1 }}');"
                                                                    href="javascript:void(0);">Select</a>
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
                                                <th>Item</th>
                                                <th>S.O. Width</th>
                                                <th>S.O. Height</th>
                                                <th>S.O. Depth</th>
                                                <th>Doorset Price</th>
                                                <th>Ironmongery Price</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody id="versionData">
                                            @if (!empty($data) && count($data) > 0)
                                                <?php $index = 0;
                                                $SI = 1; ?>
                                                @foreach ($data as $row)
                                                    @if (!empty($row->version_id))
                                                        @php
                                                            $version_id = $row->version_id;
                                                        @endphp
                                                    @else
                                                        @php
                                                            $version_id = 0;
                                                        @endphp
                                                    @endif
                                                    @php
                                                        $SvgImage = '';
                                                    @endphp
                                                    @if (empty($row->SvgImage))
                                                        @php
                                                            $SvgImage = 'color_red';
                                                        @endphp
                                                    @endif
                                                    <tr id="validate{{ $row->itemId }}" class="{{ $SvgImage }}">
                                                        <td>
                                                            {{ $SI }}
                                                            <input type="hidden" class="check"
                                                                value="{{ $row->itemId }}">
                                                            <input type="hidden" class="doors_{{ $index }}"
                                                                value="{{ $row->id }}">
                                                        </td>
                                                        <td>{{ $row->DoorQuantity }}</td>
                                                        <td>{{ $row->FireRating }}</td>
                                                        <td>{{ $row->DoorType }}</td>
                                                        <td>{{ $row->doorNumber }}</td>
                                                        <td>{{ $row->floor }}</td>
                                                        <td>{{ $row->DoorsetType }}</td>
                                                        <td>{{ $row->SOWidth }}</td>
                                                        <td>{{ $row->SOHeight }}</td>
                                                        <td>{{ $row->SOWallThick }}</td>
                                                        <td>{{ (($row->AdjustPrice)?floatval($row->AdjustPrice) :floatval($row->DoorsetPrice)) }}</td>
                                                        <td>{{ $row->IronmongaryPrice }}</td>
                                                        <td>{{ (($row->AdjustPrice)?floatval($row->AdjustPrice) + floatval($row->IronmongaryPrice):floatval($row->DoorsetPrice) + floatval($row->IronmongaryPrice))  }}
                                                        </td>
                                                        <td class="text-center">
                                                            <div class="dropdown">
                                                                <a class="dropdown-toggle btn btn-light" type="button"
                                                                    data-toggle="dropdown"><i
                                                                        class="fa fa-ellipsis-h"></i></a>
                                                                <ul class="dropdown-menu drop_style">
                                                                    <li><a
                                                                            href="{{ ConfigurationURL($quotation->configurableitems, $row->itemId, $version_id) }}">Edit</a>
                                                                    </li>
                                                                    <li><a onclick="favoriteItem('{{ $row->itemId }}','{{ $row->id }}')"
                                                                            href="javascript:void(0);">Name
                                                                            Configuration</a></li>
                                                                    <li><a onclick="adjustPrice('{{ $row->itemId }}','{{ $row->id }}','{{ floatval($row->DoorsetPrice) + floatval($row->IronmongaryPrice) }}')"
                                                                            href="javascript:void(0);">Adjust Price</a>
                                                                    </li>
                                                                    <li><a href="javascript:void(0);">Comment</a></li>
                                                                    <li><a href="javascript:void(0);"
                                                                            onClick="CopyDoorSet({{ $quotationId }},'{{ $row->id }}');">Copy</a>
                                                                    </li>
                                                                    <li><a onclick="remove_item('{{ $row->id }}')"
                                                                            href="#">Remove</a></li>
                                                                    {{--  <li><a onclick="edit_image1('{{ $row->itemId }}')"
                                                                            href="javascript:void(0);">Validate</a></li>  --}}
                                                                </ul>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    <?php $index++;
                                                    $SI++; ?>
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
                                                <th>Action</th>
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
                                                        <td><input type="number" name="quantity" style="margin: 0 auto; max-width: 50px;font-size: 14px !important;" class="form-control quantity quantity_{{ $value->NonConfigId }} nonconfigQut" disabled value="{{ $value->quantity }}"></td>
                                                        <td>{{ floatval($value->storePrice) }}</td>
                                                        <td>{{ floatval($value->total_price) }}</td>
                                                        <td>
                                                            <div class="editconfig editconfig{{ $value->NonConfigId }}">
                                                                <button type="button" class="btn btn-success" style="color: #fff; font-size:15px" onclick="editNonConfig({{ $value->NonConfigId }})">
                                                                    <i class="fa fa-edit text-white text-center"></i>
                                                                </button>
                                                                <button type="button" class="btn btn-danger" style="color: #fff; font-size:15px" onclick="deleteNonConfig({{ $value->NonConfigId }})">
                                                                    <i class="fa fa-trash text-white text-center"></i>
                                                                </button>
                                                            </div>
                                                            <div style="display: none;" class="updateconfig updateconfig{{ $value->NonConfigId }}">
                                                                <button type="button" class="btn btn-success" style="color: #fff; font-size:15px" onclick="updateNonConfig({{ $value->NonConfigId }})">
                                                                    Update
                                                                </button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @php
                            if ($quotation->QuotationStatus != '') {
                                if ($quotation->QuotationStatus == 'Open') {
                                    $quotation_status = '<div class="quote_status" style="background: #69e4a6;">' . $quotation->QuotationStatus . '</div>';
                                } elseif ($quotation->QuotationStatus == 'Ordered' || $quotation->QuotationStatus == 'Accept') {
                                    if ($quotation_data->VersionId == $selectQV['selectVersionID']) {
                                        $quotation_status = '<div class="quote_status" style="background: #47a91f;">' . $quotation->QuotationStatus . '</div>';
                                    } else {
                                        $quotation_status = '<div class="quote_status" style="background: #69e4a6;">Open</div>';
                                    }
                                } elseif ($quotation->QuotationStatus == 'All') {
                                    $quotation_status = '<div class="quote_status" style="background:#808080;">' . $quotation->QuotationStatus . '</div>';
                                } else {
                                    $quotation_status = '<div class="quote_status" style="background:red;">' . $quotation->QuotationStatus . '</div>';
                                }
                            } else {
                                $quotation_status = null;
                            }
                        @endphp
                        @if (Auth::user()->UserType == 1 || Auth::user()->UserType == 2 || Auth::user()->UserType == 3)
                            <div class="col-sm-3 p-0">
                                <div class="quote_card">
                                    <div class="quote_card_title">
                                        <h3>Quote Summary</h3>
                                    </div>
                                    <div class="quote_card_header mt-4">
                                        <h4>Header
                                            <a href="#" onclick="ChangeQuotationFormBtn()"
                                                id="ChangeQuotationFormBtn">Edit Header Details</a>
                                        </h4>
                                        {{--  {!! $manual_quotation_status !!}  --}}
                                        {!! $quotation_status !!}
                                    </div>
                                    <form action="{{ route('QuoteSummary') }}" method="post">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="quotationId" value="{{ $quotationId }}">
                                        <div class="quote_card_form">
                                            <div class="form-group">
                                                <label>Contact List</label>
                                                <select name="QSCustomerContactId" id="CustomerContactIdSTC"
                                                    class="CustomerId" required>
                                                    <option value="">Select Contact List</option>
                                                    @if (!empty($customerMultiContact))
                                                        @foreach ($customerMultiContact as $row)
                                                            <option @if ($quotation_data->QSCustomerContactId == $row->id) selected @endif
                                                                value="{{ $row->id }},{{ $row->MainContractorId }}"
                                                                @if (!empty($QuotationContactInformation->Contact)) @php $array_data = explode(",",$QuotationContactInformation->Contact); @endphp
                                                        @if ($array_data[0] == $row->id && $array_data[1] == $row->MainContractorId)
                                                        {{ 'selected' }} @endif
                                                                @endif
                                                                >{{ $row->FirstName . ' ' . $row->LastName }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>Ship To</label>
                                                <select name="QSQuotationSiteDeliveryAddressId"
                                                    id="QSQuotationSiteDeliveryAddressId" required>
                                                    <option value="">Same as Bill To</option>
                                                    @if (!empty($QuotationSiteDeliveryAddress))
                                                        @foreach ($QuotationSiteDeliveryAddress as $deliveryaddr)
                                                            <option @if ($quotation->QSQuotationSiteDeliveryAddressId == $deliveryaddr->id) selected @endif
                                                                value="{{ $deliveryaddr->id }}">
                                                                {{ $deliveryaddr->Address1 }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label>P.O. Number</label>
                                                <input type="text" name="PONumber" value="{{ $quotation->PONumber }}"
                                                    required>
                                            </div>
                                            @if ($quotation->QuotationStatus == 'Accept')
                                                <div class="form-group">
                                                    <label>Atteched File By Client</label>
                                                    <a href="{{ url('/') }}/quotationFiles/fileSendByClient/{{ $quotation->fileByClient }}"
                                                        target="_blanks">
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
                                             <p>Side Screen
                                                <span>
                                                    @if (!empty($screenDataprice))
                                                        {{ number_format( (float) $screenDataprice, 2, '.', '') }}
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

                                            <input type="number" class="dis" id="QuoteSummaryDiscount" name="QuoteSummaryDiscount" placeholder="0.00" value="">
                                            <button type="button" class="btn btn-primary mt-3" style="color: #fff; font-size:15px" onclick="adjustPriceDiscountBtn()">
                                                Adjust Price
                                            </button>

                                            <input type="hidden" id="QuoteSummaryTotalDoorPrice"
                                                value="@if (!empty($total_price)) {{ $total_price }} @else {{ '0.00' }} @endif">

                                            <h4 class="total_amount">Total(GBP) <span>
                                                    @if (!empty($total_price))
                                                    {{ number_format( (float) $total_price, 2, '.', '') }}
                                                    @else
                                                        {{ '0.00' }}@endif
                                            </span></h4>
                                            <h5 class="total_amount">Discount(%) Applied <span>{{ $selectQV['selectVersionID'] > 0 ? $selectQV['discountQuotation'] : 0 }}%</span></h5>
                                            @if ($quotation->QuotationStatus != 'Accept')
                                                <a tabindex="0" class="btn btn-dark btn-lg btn-block" role="button"
                                                    data-toggle="popover" data-trigger="focus" title=""
                                                    data-original-title="Wait For Quotation Accept.">
                                                    CONVERT TO ORDER
                                                </a>
                                            @else
                                                <button type="submit" class="btn btn-dark btn-lg btn-block">CONVERT TO
                                                    ORDER</button>
                                            @endif
                                        </div>
                                    </form>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Manual Accept/Reject Quote-->
    <div class="modal fade" id="ManualAddForm" role="dialog" tabindex="-1" data-backdrop="false">
        <div class="modal-dialog " style="margin-top: 70px">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                    <h5 class="modal-title">Manual Accept/Reject Quote</h5>
                </div>
                <div class="modal-body" style="overflow-y: inherit">
                    <div class="mt-3">
                        <button type="button" class="btn btn-success" style="color: #fff; font-size:15px" onclick="ManualAccpetReject(1)">Accept
                        </button>
                        <button type="button" class="btn btn-danger" style="color: #fff; font-size:15px" onclick="ManualAccpetReject(2)">Reject
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
    <input type="hidden" name="SideScreenCount" id="SideScreenCount" value="{{ count($SideScreenData) }}" />
    <input type="hidden" name="ManualQuotationStatusvalue" id="ManualQuotationStatusvalue" value="{{ $quotation->ManualQuotationStatus }}" />
    <input type="hidden" name="addItemUrl" id="addItemUrl"
        value="{{ url('/quotation/add-configuration-cad-item') }}" />
    <input type="hidden" name="addSideScreenUrl" id="addSideScreenUrl"
        value="{{ url('/quotation/add-side-screen-item') }}" />
    <input type="hidden" name="addSideScreenUrl2" id="addSideScreenUrl2"
        value="{{ url('/quotation/add-new-screens') }}" />
    <input type="hidden" name="addhalspanUrl" id="addhalspanUrl"
        value="{{ url('/quotation/add-halspan-item') }}" />
    <input type="hidden" name="add_norma_door_core_Url" id="add_norma_door_core_Url"
        value="{{ url('/quotation/add-norma-door-core-item') }}" />
    <input type="hidden" name="add_vicaima_url" id="add_vicaima_url"
        value="{{ url('/quotation/add-vicaima-door-core-item') }}" />
    <input type="hidden" name="add_seadec_url" id="add_seadec_url"
        value="{{ url('/quotation/add-seadec-cad-item') }}" />
    <input type="hidden" name="add_deanta_url" id="add_deanta_url"
        value="{{ url('/quotation/add-deanta-cad-item') }}" />
    {{-- for new door core Flamebreak --}}
    <input type="hidden" name="addflamebreakUrl" id="addflamebreakUrl" value="{{ url('/quotation/add-flamebreak-item') }}" />
     {{-- for end new door core Flamebreak --}}
    {{-- for new door core Stredor --}}
    <input type="hidden" name="addstredorUrl" id="addstredorUrl" value="{{ url('/quotation/add-stredor-item') }}" />
     {{-- for end new door core Stredor --}}
    <!-- <input type="hidden" name="addItemUrl"  value="{{ url('/quotation/singleconfigurationitem') }}" /> -->
    <input type="hidden" name="addItemUrl2" id="addItemUrl2" value="{{ url('/quotation/request') }}" />
    <input type="hidden" name="printInvoiceUrl" id="printInvoiceUrl" value="{{ url('/quotation/printinvoice') }}" />
    <input type="hidden" name="printInvoiceExcelUrl" id="printInvoiceExcelUrl"
        value="{{ url('/quotation/printinvoiceinexcel') }}" />
    <input type="hidden" id="sendToClientUrl" value="{{ route('sendToClientUrl') }}" />
    <input type="hidden" name="buildofmaterialUrl" id="buildofmaterialUrl"
        value="{{ url('/quotation/generateBOM2') }}" />
    <input type="hidden" name="BomCalculationUrl" id="BomCalculationUrl"
        value="{{ url('quotation/generateBOMPrint') }}" />
    <input type="hidden" name="QualityControlUrl" id="QualityControlUrl"
        value="{{ url('quotation/QualityControlPrint') }}" />
    <input type="hidden" name="ScreenBomCalculationUrl" id="ScreenBomCalculationUrl"
        value="{{ url('quotation/ScreengenerateBOMPrint') }}" />
    <input type="hidden" name="DoorOrderSheetUrl" id="DoorOrderSheetUrl"
        value="{{ url('quotation/DoorOrderSheetUrl') }}" />
    <input type="hidden" name="FrameTransomsUrl" id="FrameTransomsUrl"
        value="{{ url('quotation/FrameTransomsUrl') }}" />
    <input type="hidden" name="BomCalculationUrlGet" id="BomCalculationUrlGet"
        value="{{ url('quotation/generateBOM') }}/{{ $quotation->id }}/{{ $VersionId !== null ? $VersionId : 0 }}/{{ $selectQV['selectVersionID'] > 0 ? $selectQV['selectVersionID'] : 0 }}" />
     <input type="hidden" name="ScreenBomCalculationUrlGet" id="ScreenBomCalculationUrlGet"
        value="{{ url('quotation/ScreengenerateBOM') }}/{{ $quotation->id }}/{{ $VersionId !== null ? $VersionId : 0 }}/{{ $selectQV['selectVersionID'] > 0 ? $selectQV['selectVersionID'] : 0 }}" />
    <input type="hidden" name="DoorOrderSheet" id="DoorOrderSheet"
        value="{{ url('quotation/DoorOrderSheet') }}/{{ $quotation->id }}/{{ $VersionId !== null ? $VersionId : 0 }}/{{ $selectQV['selectVersionID'] > 0 ? $selectQV['selectVersionID'] : 0 }}" />
    <input type="hidden" name="FrameTransoms" id="FrameTransoms"
        value="{{ url('quotation/FrameTransoms') }}/{{ $quotation->id }}/{{ $VersionId !== null ? $VersionId : 0 }}/{{ $selectQV['selectVersionID'] > 0 ? $selectQV['selectVersionID'] : 0 }}" />
    <input type="hidden" name="GlassOrderSheet" id="GlassOrderSheet"
        value="{{ url('quotation/GlassOrderSheet') }}/{{ $quotation->id }}/{{ $VersionId !== null ? $VersionId : 0 }}/{{ $selectQV['selectVersionID'] > 0 ? $selectQV['selectVersionID'] : 0 }}" />
    <input type="hidden" name="GlazingBeadsDoors" id="GlazingBeadsDoors"
        value="{{ url('quotation/GlazingBeadsDoors') }}/{{ $quotation->id }}/{{ $VersionId !== null ? $VersionId : 0 }}/{{ $selectQV['selectVersionID'] > 0 ? $selectQV['selectVersionID'] : 0 }}" />
    <input type="hidden" name="quotationId" id="quotationId" value="{{ $quotationId }}" />
    <input type="hidden" name="version" id="version" value="{{ $maxVersion !== null ? $maxVersion : 0 }}" />
    <input type="hidden" name="currentVersion" id="currentVersion"
        value="{{ $selectQV['selectVersionID'] > 0 ? $selectQV['selectVersionID'] : 0 }}" />
    <input type="hidden" name="bomTag" id="bomTag" value="{{ $quotation->bomTag > 0 ? $quotation->bomTag : 0 }}" />
    <input type="hidden" name="customer_id" id="customer_id" value="{{ $quotation->MainContractorId }}" />
    <input type="hidden" name="flag" id="flag" value="{{ $quotation->flag }}" />
    <input type="hidden" name="generatedId" id="generatedId" value="{{ $generatedId }}" />
    <input type="hidden" name="CompanyName" id="CompanyName" />
    <button style="display: none;" type="button" id="success-alert" data-type="success"
        class="btn btn-success btn-show-swal"></button>
    <input type="hidden" name="excelexportUrl" id="excelexportUrl" value="{{ url('/quotation/excelexport') }}" />
    <input type="hidden" name="excelexportNewUrl" id="excelexportNewUrl"
        value="{{ url('/quotation/excelexportNew') }}" />
    <input type="hidden" name="ExportBomCalculationUrl" id="ExportBomCalculationUrl"
        value="{{ url('/quotation/ExportBomCalculation') }}" />
    <input type="hidden" name="ExportDoorTypeBomUrl" id="ExportDoorTypeBomUrl"
        value="{{ url('/quotation/ExportDoorTypeBom') }}" />
    <input type="hidden" name="ExportSideScreenUrl" id="ExportSideScreenUrl"
        value="{{ url('/quotation/ExportSideScreen') }}" />
    <input type="hidden" name="ExportIronmongeryUrl" id="ExportIronmongeryUrl"
        value="{{ url('/quotation/ExportIronmongery') }}" />
    <input type="hidden" name="excelexportVicaimaUrl" id="excelexportVicaimaUrl"
        value="{{ url('/quotation/excelexportVicaimaUrl') }}" />
    <input type="hidden" name="mainformimportUrl" id="mainformimportUrl"
        value="{{ url('quotation/excel-upload/') }}" />
    <input type="hidden" name="generateBOM" id="generateBOM" value="{{ route('generateBOM') }}" />
    <div id="user_jobs" hidden></div>
    <input type="hidden" id="SvgImage" name="SvgImage" value="" />
    <input type="hidden" id='store2' value="{{ url('items/store2') }}">
    <input type="hidden" id="quotationId" name="quotationId" value="{{ $quotationId }}">
    <input type="hidden" id="quotationconfigurableitems" name="quotationconfigurableitems" value="{{ $quotation_data->configurableitems }}">
    <input type="hidden" id="versionId" name="versionId"
        value="{{ $selectQV['selectVersionID'] > 0 ? $selectQV['selectVersionID'] : 0 }}">
    <div class="col-md-6">
        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
        <input type="hidden" id="edit_image" value="{{ url('/quotation/edit-image') }}" />
        <input type="hidden" id="edit_image1" value="{{ url('/quotation/edit-image1') }}" />
        <input type="hidden" id="favoriteItem" value="{{ url('/quotation/favoriteItem') }}" />
        <input type="hidden" id="adjustPriceUrl" value="{{ url('/quotation/adjustPriceUrl') }}" />
        <input type="hidden" id="favoriteItemAdd" value="{{ url('/quotation/favoriteItemAdd') }}" />
        <input type="hidden" id="favoriteDeleteItem" value="{{ url('/quotation/favoriteDeleteItem') }}" />
        <input type="hidden" id="ProjectId1" value="{{ !empty($ProjectId) ? $ProjectId : 0 }}" />
        <input type="hidden" id="nonConfigstore" value="{{ route('non-configural-items/nonConfigstore') }}" />
        <input type="hidden" id="updateNonConfigUrl" value="{{ route('non-configural-items/nonConfigUpdate') }}" />
        <input type="hidden" id="updateManualAcceptQuote" value="{{ url('/quotation/updateManualAcceptQuote') }}" />
        <input type="hidden" id="adjustPriceDiscountUrl" value="{{ url('/quotation/adjustPriceDiscountUrl') }}" />
    @endsection
    @section('js')
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        </script>
        <script type="text/javascript" src="{{ url('/') }}/js/generateQuotation.js"></script>
        <script>
            $("#ManualQuotationStatus").click(function(e) {
                e.preventDefault();
                $("#ManualAddForm").modal('show');
            });

            $('.close_model').on('click', function() {
                $('#ManualAddForm').modal('hide');
            });

            function ManualAccpetReject(value){
                $('.loader').css({'display':'block'});
                let sendToClientUrl = $("#sendToClientUrl").val();
                let CustomerContactId = $("#customer_id").val();
                let currentVersion = $("#currentVersion").val();
                let ProjectId = $("#ProjectId1").val();
                if (CustomerContactId == ""){
                    swal("Error!", "Select a main contractor first.", "error");
                    return false;
                }
                if (currentVersion == 0){
                    swal("Error!", "Please generate quotation first.", "error");
                    return false;
                }
                if(ProjectId == '' || ProjectId == 0){
                    swal("Error!", "Please select project first!", "error");
                    $('.loader').css({'display':'none'});
                    return false;
                }
                var url = $('#updateManualAcceptQuote').val();
                var versionId = $('#versionId').val();
                var quotationId = $('#quotationId').val();
                const token = $("#_token").val();
                $.ajax({
                    url: url,
                    type: 'post',
                    data: {
                        _token: $("#_token").val(),
                        value:value,
                        versionId:versionId,
                        quotationId: quotationId
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('.loader').css({'display':'none'});
                        if (data.status == true) {
                            swal('success', data.msg, 'success').then(function() {
                                location.reload();
                            });
                        } else {
                            swal('error', data.msg, 'error').then(function() {
                                location.reload();
                            });
                        }
                    }
                });
            }

            function configurableNon(id) {
                $('#add-item-section').hide();
                $('#add-side-screen-section').hide();
                $('#side-screen-section').hide();
                $('#edit-header-section').hide();
                $('#customer-list').hide();
                if (id == 1) {
                    $('#quotation-item-list').css('display','block');
                    $('#NonConfig-item-list').css('display','none');
                    $('#config').removeClass('activeNC');
                    $('#nonConfig').addClass('activeNC');
                    $('#showall').removeClass('activeNC');
                    $('#addSideScreen').removeClass('activeNC');
                    $('#SideScreenList').removeClass('activeNC');
                } else {
                    $('#quotation-item-list').css('display','none');
                    $('#NonConfig-item-list').css('display','block');
                    $('#config').addClass('activeNC');
                    $('#nonConfig').removeClass('activeNC');
                    $('#showall').removeClass('activeNC');
                    $('#addSideScreen').removeClass('activeNC');
                    $('#SideScreenList').removeClass('activeNC');
                }
            }
            function editNonConfig(id){
                $('.editconfig').css('display','block');
                $('.updateconfig').css('display','none');
                $('.quantity').prop('disabled', true);
                $('.quantity_'+id).prop('disabled', false);
                $('.editconfig'+id).css('display','none');
                $('.updateconfig'+id).css('display','block');

            }
            function deleteNonConfig(id) {
                swal({
                        title: "Are you sure?",
                        text: "if you delete this not get back anyone",
                        type: "error",
                        confirmButtonText: "Delete",
                        showCancelButton: true
                    })
                    .then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: "{{route('non-configural-items/nonConfigDelete')}}",
                                method: "POST",
                                data: {
                                    'id': id,
                                    _token: $("#_token").val()
                                },
                                success: function(result) {
                                    swal(
                                        'Success',
                                        'Option Deleted the <b style="color:green;">Success</b>!',
                                        'success'
                                    ).then((result) => {
                                        window.location.reload();
                                    })
                                }
                            });


                        } else if (result.dismiss === 'cancel') {
                            swal(
                                'Cancelled',
                                'Option not deleted!',
                                'error'
                            )
                        }
                    })
            }
            function updateNonConfig(id) {
                var url = $('#updateNonConfigUrl').val();
                var quantity = $('.quantity_'+id).val();
                const token = $("#_token").val();
                $.ajax({
                    url: url,
                    type: 'post',
                    data: {
                        _token: $("#_token").val(),
                        id:id,
                        quantity: quantity
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == true) {
                            swal('success', data.msg, 'success').then(function() {
                                location.reload();
                            });
                        } else {
                            swal('error', data.msg, 'error').then(function() {
                                location.reload();
                            });
                        }
                    }
                });
            }
            function nonConfigStore(QuotationId, versionId, nonConfigurableId, price) {
                var url = $('#nonConfigstore').val();
                var quantity = $('#nonconfigQuantity-'+nonConfigurableId).val();
                const token = $("#_token").val();
                $.ajax({
                    url: url,
                    type: 'post',
                    data: {
                        _token: $("#_token").val(),
                        versionId: versionId,
                        QuotationId: QuotationId,
                        nonConfigurableId: nonConfigurableId,
                        price: price,
                        quantity: quantity
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == true) {
                            swal('success', data.msg, 'success').then(function() {
                                location.reload();
                            });
                        } else {
                            swal('error', data.msg, 'error').then(function() {
                                location.reload();
                            });
                        }
                    }
                });
            }
            //$(document).ready(function(){
            //var successes = $('#successes').val();
            //if(successes == 'successes')
            //{
            //var successesvalue = $('#successesvalue').val();
            //$('#'+successesvalue).css("background-color", "#90EE90");
            //}else if(successes == 'errors')
            //{
            //var errorsvalue = $('#errorsvalue').val();
            //$('#'+errorsvalue).css("background-color", "#ce2029");
            //}
            //});
            let UniversalToken = $("#_token").val();
            function adjustPriceAjax() {
                $('.loader').empty().css({
                    'display': 'block'
                });
                var versionId = $('#versionId').val();
                var quotationId = $('#quotationId').val();
                var itemId = $('#adjustPriceitemId').val();
                var itemMasterId = $('#adjustPriceitemMasterId').val();
                var AdjustPrice = $('#AdjustPrice').val();
                var totalPrice = $('#totalPrice').val();
                //if (parseFloat(AdjustPrice) < parseFloat(totalPrice)) {
                    $.ajax({
                        url: $("#adjustPriceUrl").val(),
                        method: "POST",
                        data: {
                            _token: $("#_token").val(),
                            quotationId: quotationId,
                            versionId: versionId,
                            itemId: itemId,
                            itemMasterId: itemMasterId,
                            AdjustPrice: AdjustPrice
                        },
                        dataType: "Json",
                        success: function(data) {
                            if (data.status == true) {
                                swal('success', data.msg, 'success').then(function() {
                                    location.reload();
                                });
                            } else {
                                swal('error', data.msg, 'error').then(function() {
                                    location.reload();
                                });
                            }
                        }
                    });
                //} else {
                //    $('.loader').empty().css({
                  //      'display': 'none'
                    //});
                   // alert("Adjust price can't be greater than total price!");
                   // return false;
               // }
            }
            function favorite() {
                $('.loader').empty().css({
                    'display': 'block'
                });
                var versionId = $('#versionId').val();
                if (versionId == '0') {
                    $('.loader').empty().css({
                        'display': 'none'
                    });
                    alert("You haven't selected any version yet.");
                    return false;
                }
                var quotationId = $('#quotationId').val();
                var itemId = $('#itemId').val();
                var itemMasterId = $('#itemMasterId').val();
                var doorTypeName = $('#doorTypeName').val();
                $.ajax({
                    url: $("#favoriteItem").val(),
                    method: "POST",
                    data: {
                        _token: $("#_token").val(),
                        quotationId: quotationId,
                        versionId: versionId,
                        itemId: itemId,
                        itemMasterId: itemMasterId,
                        doorTypeName: doorTypeName
                    },
                    dataType: "Json",
                    success: function(data) {
                        if (data.status == true) {
                            swal('success', data.msg, 'success').then(function() {
                                location.reload();
                            });
                        } else {
                            swal('error', data.msg, 'error').then(function() {
                                location.reload();
                            });
                        }
                    }
                });
            }
            function favoriteItemAdd(itemId, itemMasterId, quotationId, versionId) {
                $('.loader').empty().css({
                    'display': 'block'
                });
                var vId = $('#versionId').val();
                var qId = $('#quotationId').val();
                $.ajax({
                    url: $("#favoriteItemAdd").val(),
                    method: "POST",
                    data: {
                        _token: $("#_token").val(),
                        quotationId: quotationId,
                        versionId: versionId,
                        itemId: itemId,
                        itemMasterId: itemMasterId,
                        vId: vId,
                        qId: qId
                    },
                    dataType: "Json",
                    success: function(data) {
                        if (data.status == true) {
                            swal('success', data.msg, 'success').then(function() {
                                window.location.href = "{{ url('/') }}/quotation/add-new-doors/" +
                                    data.QuotationId + "/" + data.VersionId;
                            });
                        } else {
                            swal('error', data.msg, 'error').then(function() {
                                location.reload();
                            });
                        }
                    }
                });
            }
            function favoriteDeleteItem(id) {
                $('.loader').empty().css({
                    'display': 'block'
                });
                var r = confirm("Are you sure! you wan't to delete it.");
                if (r == true) {
                    $.ajax({
                        url: $("#favoriteDeleteItem").val(),
                        method: "POST",
                        data: {
                            _token: $("#_token").val(),
                            id: id
                        },
                        dataType: "Json",
                        success: function(data) {
                            if (data.status == true) {
                                location.reload();
                            } else {
                                swal('error', data.msg, 'error').then(function() {
                                    location.reload();
                                });
                            }
                        }
                    });
                }
            }
            function edit_image1(element) {
                $('.loader').empty().css({
                    'display': 'block'
                });
                var versionId = $('#versionId').val();
                var quotationId = $('#quotationId').val();
                $.ajax({
                    // async: false,
                    url: $("#edit_image1").val(),
                    method: "POST",
                    data: {
                        _token: $("#_token").val(),
                        quotationId: quotationId,
                        versionId: versionId,
                        id: element
                    },
                    dataType: "Json",
                    success: function(data) {
                        if (data.status == true) {
                            //user_jobs div defined on page
                            $('.loader').empty().css({
                                'display': 'block'
                            });
                            $('#user_jobs').empty().html(data.html);
                            setTimeout(function() {
                                //editSvg()
                                IntumescentSeals()
                            }, 4000);
                        }
                    }
                });
            }
            function IntumescentSeals() {
                let pageId = 1;
                const latchTypeValue = $('#latchType').val(); // L,UL
                const swingTypeValue = $('#swingType').val(); // SA,DA
                const doorsetTypeValue = $('#doorsetType').val(); // SD,DD
                const fireRatingValue = $('#fireRating').val(); // FD30
                const overpanelValue2 = $('#overpanel').val(); // Yes
                const leafWidth1Value = $('#leafWidth1').val();
                const leafHeightNoOPValue = $('#leafHeightNoOP').val();
                const sOWidthValue = $('#sOWidth').val();
                const sOHeightValue = $('#sOHeight').val();
                const tollerance = $('#tollerance').val();
                const framethikness = $('#frameThickness').val();
                const undercut = $('#undercut').val();
                const gap = $('#gap').val();
                let overpanel = '';
                if (overpanelValue2 == 'Yes') {
                    overpanel = 'OP';
                }
                const doorLeafFacingValueNew = $('#doorLeafFacingValue').val();
                const frameMaterialNew = $('#frameMaterialNew').val();
                // The leaf and a half should be treated as a double door so the same way it works for a double door should work for leaf and a half.
                // start
                let $aa = '';
                if (doorsetTypeValue == 'leaf_and_a_half') {
                    const dobledoor = 'DD';
                    $aa = latchTypeValue + swingTypeValue + dobledoor + overpanel; // LSASD
                } else {
                    $aa = latchTypeValue + swingTypeValue + doorsetTypeValue + overpanel; // LSASD
                }
                // end
                let SelectedValue = 0;
                var IntumescentLeapingSealArrangementValue = document.getElementById('IntumescentLeapingSealArrangement-value');
                if (IntumescentLeapingSealArrangementValue != null) {
                    SelectedValue = $("#IntumescentLeapingSealArrangement-value").data("value");
                }
                if (doorsetTypeValue == "DD") {
                    TolleranceAdditionalNumber = 2;
                    FrameThicknessAdditionalNumber = 2;
                    GapAdditionalNumber = 3;
                    var leafWidth1 = (sOWidthValue - (tollerance * TolleranceAdditionalNumber) - (framethikness *
                        FrameThicknessAdditionalNumber) - (GapAdditionalNumber * gap)) / 2;
                } else if (doorsetTypeValue == "SD") {
                    TolleranceAdditionalNumber = 2;
                    FrameThicknessAdditionalNumber = 2;
                    GapAdditionalNumber = 2;
                    var leafWidth1 = sOWidthValue - (tollerance * TolleranceAdditionalNumber) - (framethikness *
                        FrameThicknessAdditionalNumber) - (GapAdditionalNumber * gap);
                } else {
                    TolleranceAdditionalNumber = 2;
                    FrameThicknessAdditionalNumber = 2;
                    GapAdditionalNumber = 3;
                    var leafWidth1 = 0;
                }
                //if(leafWidth1 != leafWidth1Value){
                //  swal('Warning','Invalid value for Leaf Width 1!').then(function(){
                //    location.reload();
                //});
                //return false;
                //}
                var calculationOfLeafHeight = sOHeightValue - tollerance - framethikness - undercut - gap;
                //if(calculationOfLeafHeight != leafHeightNoOPValue){
                //  swal('Warning','Invalid value for Leaf Height!').then(function(){
                //    location.reload();
                //  });
                //  return false;
                //}
                // console.log($aa);
                if (fireRatingValue != '' && sOWidthValue != '' && sOHeightValue != '') {
                    $.ajax({
                        url: $("#Filterintumescentseals").text(),
                        method: "POST",
                        dataType: "Json",
                        data: {
                            pageId: pageId,
                            SelectedValue: SelectedValue,
                            fireRatingValue: fireRatingValue,
                            intumescentseals: $aa,
                            leafWidth1Value: leafWidth1,
                            leafHeightNoOPValue: calculationOfLeafHeight,
                            doorLeafFacingValueNew: doorLeafFacingValueNew,
                            frameMaterialNew: frameMaterialNew,
                            _token: $("#_token").val()
                        },
                        success: function(result) {
                            if (result.status == 'ok') {
                                editSvg(leafWidth1, calculationOfLeafHeight);
                            } else if (result.status == 'error2') {
                                swal('Warning', result.msg).then(function() {
                                    location.reload();
                                });
                            }
                        }
                    });
                } else {
                    swal('Warning', 'Please fill the required value').then(function() {
                        location.reload();
                    });
                }
            }
            function editSvg(leafWidth1, calculationOfLeafHeight) {
                // var retval = true;
                var itemID = $('#itemID').val();
                var SvgImage = $('#SvgImage').val();
                var versionId = $('#versionId').val();
                var quotationId = $('#quotationId').val();
                if (SvgImage == '') {
                    swal('Warning', 'Something went wrong!').then(function() {
                        location.reload();
                    });
                    return false;
                }
                $.ajax({
                    url: $('#store2').val(),
                    type: 'POST',
                    data: {
                        SvgImage: SvgImage,
                        _token: $("#_token").val(),
                        QuotationId: quotationId,
                        versionId: versionId,
                        itemID: itemID,
                        leafWidth1: leafWidth1,
                        calculationOfLeafHeight: calculationOfLeafHeight
                    },
                    datatype: "json",
                    success: function(res) {
                        // console.log(res);
                        // console.log(res.data)
                        if (res.status == 'error') {
                            // $('.error').empty().html(
                            //     `<div class="alert notify alert-dismissible">
                    //         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                    //         <h5><i class="fas fa-exclamation"></i> Alert!</h5>
                    //         <ul>
                    //             ` + res.errors + `
                    //         </ul>
                    //     </div>`);
                            //     $('.loader').empty().css({'display':'none'});
                            // setTimeout(() => {
                            //     $('.error').html('')
                            // }, 10000);
                            location.reload();
                        } else if (res.status == 'success') {
                            // $('.success').empty().html(
                            //     `<div class="alert alert-success alert-dismissible">
                    //         <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    //         <strong>Success!</strong> ` + res.data + `
                    //     </div>`);
                            //     $('.loader').empty().css({'display':'none'});
                            //     $('.Validate'+itemID).attr({'disabled':true});
                            // setTimeout(() => {
                            //     $('.success').html('')
                            // }, 10000);
                            location.reload();
                        }
                    }
                });
            }
            $(document).on('keyup', '#searchCustomer', function(e) {
                e.preventDefault();
                const customerName = $(this).val();
                const quotationIdValue = $('#quotationId').val();
                $.ajax({
                    url: "{{ route('searchCustomer') }}",
                    type: 'post',
                    data: {
                        _token: UniversalToken,
                        'customerName': customerName,
                        'quotationIdValue': quotationIdValue
                    },
                    // dataType: "json",
                    success: function(data) {
                        console.log(data)
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


                $('.nonconfigQut').on('input', function() {

                    var value = $(this).val();

                    if ((value !== '') && (value.indexOf('.') === -1)) {

                        $(this).val(Math.max(Math.min(value, 99999999999999999999), 1));
                    }
                });
            });
            function openVersionModal() {
                $("#quotation-version-modal").modal("show");
            }
            function favoriteItemList() {
                $("#Favorite-Item-modal").modal("show");
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
                    var SideScreenCount = $("#SideScreenCount").val();
                    var isStatusChecked = true;
                    if(SideScreenCount == 0){
                        isStatusChecked = false;
                    }
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
                    if (isChecked || isStatusChecked) {
                        $('.loader').empty().css({
                            'display': 'block'
                        });
                        $.ajax({
                            type: "POST",
                            url: "{{ url('quotation/versionstore') }}",
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
                            success: function(data) {
                                console.log(data);
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
                                $('.loader').empty().css({'display': 'none'});
                            },
                            error: function(data) {
                                swal("Oops!", "Something went wrong. Please try again.", "error");
                                $('.loader').empty().css({'display': 'none'});
                            }
                        });
                    } else {
                        swal("Warning!", "Select at least one door or one screen.", "warning");
                    }
                } else {
                    $("#create-new-version-modal").modal("show");
                }
            }
            function ChangeQuotationFormBtn() {
                $('#customer-list').hide();
                $('#add-item-section').hide();
                $('#add-side-screen-section').hide();
                $('#side-screen-section').hide();
                $('#edit-header-section').show();
                $('#quotation-item-list').hide();
                $('#NonConfig-item-list').css('display','none');
                $('#showall').removeClass('activeNC')
                $('#addSideScreen').removeClass('activeNC')
                $('#SideScreenList').removeClass('activeNC')
                $('#config').removeClass('activeNC');
                $('#nonConfig').removeClass('activeNC');
            }
            function ShowAddItemOption() {
                $('#customer-list').hide();
                $('#add-item-section').show();
                $('#add-side-screen-section').hide();
                $('#side-screen-section').hide();
                $('#edit-header-section').hide();
                $('#quotation-item-list').hide();
                $('#NonConfig-item-list').css('display','none');
                $('#showall').addClass('activeNC')
                $('#addSideScreen').removeClass('activeNC');
                $('#SideScreenList').removeClass('activeNC');
                $('#config').removeClass('activeNC');
                $('#nonConfig').removeClass('activeNC');
            }
            function addSideScreen() {
                $('#customer-list').hide();
                $('#add-item-section').hide();
                $('#side-screen-section').hide();
                $('#add-side-screen-section').show();
                $('#side-screen-section').hide();
                $('#edit-header-section').hide();
                $('#quotation-item-list').hide();
                $('#NonConfig-item-list').css('display','none');
                $('#addSideScreen').addClass('activeNC')
                $('#SideScreenList').removeClass('activeNC')
                $('#showall').removeClass('activeNC')
                $('#config').removeClass('activeNC');
                $('#nonConfig').removeClass('activeNC');
            }
            function SideScreen() {
                $('#customer-list').hide();
                $('#add-item-section').hide();
                $('#side-screen-section').show();
                $('#add-side-screen-section').hide();
                $('#edit-header-section').hide();
                $('#quotation-item-list').hide();
                $('#NonConfig-item-list').css('display','none');
                $('#addSideScreen').removeClass('activeNC')
                $('#SideScreenList').addClass('activeNC')
                $('#showall').removeClass('activeNC')
                $('#config').removeClass('activeNC');
                $('#nonConfig').removeClass('activeNC');
            }
            function ChangeCustomerBtn() {
                $('#customer-list').show();
                $('#add-item-section').hide();
                $('#add-side-screen-section').hide();
                $('#side-screen-section').hide();
                $('#edit-header-section').hide();
                $('#quotation-item-list').hide();
                $('#NonConfig-item-list').css('display','none');
                $('#addSideScreen').removeClass('activeNC')
                $('#SideScreenList').removeClass('activeNC')
                $('#showall').removeClass('activeNC')
                $('#config').removeClass('activeNC');
                $('#nonConfig').removeClass('activeNC');
            }

            $(document).on("click", ".SideScreen_btn", function(e) {
                var quotationId = $("#quotationId").val();
                var currentVersion = $("#currentVersion").val();

                var addSideScreenUrl = $("#addSideScreenUrl").val();
                if (currentVersion != 0) {
                    window.location.href = addSideScreenUrl + '/' + quotationId + '/' + currentVersion;
                } else {
                    window.location.href = addSideScreenUrl + '/' + quotationId;
                }
            });

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
                } else if (type == 2) { // for 2 Halspan
                    var addhalspanUrl = $("#addhalspanUrl").val();
                    if (currentVersion != 0) {
                        window.location.href = addhalspanUrl + '/' + quotationId + '/' + currentVersion;
                    } else {
                        window.location.href = addhalspanUrl + '/' + quotationId;
                    }
                } else if (type == 3) { // for 3 Norma Door Core
                    var add_norma_door_core_Url = $("#add_norma_door_core_Url").val();
                    if (currentVersion != 0) {
                        window.location.href = add_norma_door_core_Url + '/' + quotationId + '/' + currentVersion;
                    } else {
                        window.location.href = add_norma_door_core_Url + '/' + quotationId;
                    }
                } else if (type == 4) { // for 4 vicaima
                    var add_vicaima_url = $("#add_vicaima_url").val();
                    if (currentVersion != 0) {
                        window.location.href = add_vicaima_url + '/' + quotationId + '/' + currentVersion;
                    } else {
                        window.location.href = add_vicaima_url + '/' + quotationId;
                    }
                } else if (type == 5) { // for 4 seadec
                    var add_seadec_url = $("#add_seadec_url").val();
                    if (currentVersion != 0) {
                        window.location.href = add_seadec_url + '/' + quotationId + '/' + currentVersion;
                    } else {
                        window.location.href = add_seadec_url + '/' + quotationId;
                    }
                } else if (type == 6) { // for  6 deanta
                    var add_deanta_url = $("#add_deanta_url").val();
                    if (currentVersion != 0) {
                        window.location.href = add_deanta_url + '/' + quotationId + '/' + currentVersion;
                    } else {
                        window.location.href = add_deanta_url + '/' + quotationId;
                    }
                }
                else if (type == 7) { // for 7 Flamebreak
                    var addflamebreakUrl = $("#addflamebreakUrl").val();
                    if (currentVersion != 0) {
                        window.location.href = addflamebreakUrl + '/' + quotationId + '/' + currentVersion;
                    } else {
                        window.location.href = addflamebreakUrl + '/' + quotationId;
                    }
                }
                else if (type == 8) { // for 8 Stredor
                    var addStredorUrl = $("#addstredorUrl").val();
                    if (currentVersion != 0) {
                        window.location.href = addStredorUrl + '/' + quotationId + '/' + currentVersion;
                    } else {
                        window.location.href = addStredorUrl + '/' + quotationId;
                    }
                }
            });
            $(document).on("click", ".SideScreen_Additional_btn", function(e) {
                var type = $(this).data("type");
                var addItemUrl = $("#addSideScreenUrl2").val();
                var quotationId = $("#quotationId").val();
                var currentVersion = $("#currentVersion").val();
                //if (type == 1 || type == 2) { // for 1 Streboard // for 2 Halspan
                    if (currentVersion != 0) {
                        window.location.href = addItemUrl + '/' + quotationId + '/' + currentVersion;
                    } else {
                        window.location.href = addItemUrl + '/' + quotationId + '/' + currentVersion;
                    }
                //}
            });
             $(document).on("click", ".configure_door_btn", function(e) {
                var type = $(this).data("type");
                var addItemUrl = $("#addItemUrl2").val();
                var quotationId = $("#quotationId").val();
                var currentVersion = $("#currentVersion").val();
                //if (type == 1 || type == 2) { // for 1 Streboard // for 2 Halspan
                    if (currentVersion != 0) {
                        window.location.href = addItemUrl + '/' + quotationId + '/' + currentVersion;
                    } else {
                        window.location.href = addItemUrl + '/' + quotationId + '/' + currentVersion;
                    }
                //}
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
            function selectCustomer(customerId, QuotationId, company, address) {
                var html = '<span>' + company + '</span><span>' + address + '</span>';
                $(".customerData").empty().append(html);
                $("#ChangeCustomerBtn").text("Change Main Contractor");
                $("#customer-list").hide();
                $('#customer_id').val(customerId);
                var url = "{{ route('quotation/selectcustomer') }}";
                const token = $("#_token").val();
                const quotationId = "{{ $quotationId }}";
                $.ajax({
                    url: url,
                    type: 'post',
                    data: {
                        'url': url,
                        '_token': token,
                        'customerId': customerId,
                        'QuotationId': QuotationId,
                        'quotationId': quotationId
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.status == "success") {
                            $('#customer_id').val(customerId);
                            $(".CustomerId option[value='" + customerId + "']").remove();
                            $('.CustomerId').empty().append(data.selectCC);
                            $('.selectprojectId').empty().append(data.selproj);
                            $('#quotation-item-list').show();
                            location.reload();
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
                var SideScreenCount = $("#SideScreenCount").val();
                var isStatusChecked = true;
                if(SideScreenCount == 0){
                    isStatusChecked = false;
                }
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
                if (isChecked || isStatusChecked) {
                    $.ajax({
                        type: "POST",
                        url: "{{ url('quotation/versionstore') }}",
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
                    url: "{{ url('quotation/get-version') }}",
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
                            location.reload();
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
                window.location.href = "{{ url('/') }}/quotation/generate/" + QuotationId + "/" + Version;
                //$.ajax({
                //type: "POST",
                //url: "{{ url('quotation/get-version') }}",
                //data: {
                //_token: $("#_token").val(),
                //version: Version,
                //quotationId: QuotationId
                //},
                //dataType: 'Json',
                //success: function(data) { console.log(data)
                //var innerhtml = '';
                //if (data.status == "success") {
                //var door = data.door;
                //var length = door.length;
                //for (var i = 0; i < length; i++) {
                //if (door[i].DoorsetType == "DD") {
                //var doorType = 'Double Door';
                //} else if (door[i].DoorsetType == "SD") {
                //var doorType = 'Single Door';
                //} else {
                //var doorType = 'Leaf and a half';
                //}
                //let Editurl = "{{ url('quotation/edit-configuration-cad-item/') }}/" + door[i].itemId+"/"+data.versionId;
                //innerhtml += '<tr id="item-' + door[i].id + '">';
                //innerhtml += '<td>' + (i + 1) + '<input type="hidden" class="check" value="' + door[
                //i].itemId + '"><input type="hidden" class="doors_' + i + '" value="' + door[
                //i].id + '"></td>';
                //innerhtml += '<td>' + door[i].DoorType + '</td>';
                //innerhtml += '<td>' + door[i].doorNumber + '</td>';
                //innerhtml += '<td>' + doorType + '</td>';
                //innerhtml +=
                //'<td><input type="number"  style="width: 100%;" readonly id="quantity" value="1" name="quantity" min="1" max="100" class="quantity_' +
                //i + '"></td>';
                //innerhtml += '<td>' + door[i].DoorsetPrice + '</td>';
                //innerhtml += '<td>' + door[i].DoorsetPrice + '</td>';
                //innerhtml += '<td class="text-center">';
                //innerhtml += '<div class="dropdown">';
                //innerhtml +=
                //'<a class="dropdown-toggle btn btn-light" type="button" data-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></a>';
                //innerhtml += '<ul class="dropdown-menu drop_style">';
                //innerhtml += '<li><a href="' + Editurl + '">Edit</a></li>';
                //innerhtml += '<li><a href="#">Name Configuration</a></li>';
                //innerhtml += '<li><a href="#">Adjust Price</a></li>';
                //innerhtml += '<li><a href="#">Comment</a></li>';
                //innerhtml += '<li><a href="#">Copy</a></li>';
                //innerhtml += '<li><a href="#">Export</a></li>';
                //innerhtml += '<li><a href="javascript:void(0);" onclick="RemoveVersionsItem(' +
                //door[i].id + ')">Remove</a></li>';
                //innerhtml += '</ul>';
                //innerhtml += '</div>';
                //innerhtml += '</td>';
                //innerhtml += '</tr>';
                //}
                //location.reload();
                //} else {
                //innerhtml += '';
                //}
                ///$("#versionData").empty().append(innerhtml);
                //$("#quotation-version-modal").modal("hide");
                //$("#currentVersion").val(data.versionId);
                //$(".ShowVersion").html('<b>Selected Version : ' + data.version + '</b>');
                //$('#generateInvoice').attr('disabled', 'disabled');
                //$('#add-item').removeClass('customDisabled');
                //},
                //error: function(data) {
                //                $(".page-loader-action").fadeOut();
                //swal("Oops!!", "Something went wrong. Please try again.", "error");
                //}
                //});
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
                $('.loader').empty().css({
                    'display': 'block'
                });
                $.ajax({
                    type: "POST",
                    url: "{{ url('quotation/create-new-version') }}",
                    data: {
                        _token: $("#_token").val(),
                        version: Version,
                        quotationId: QuotationId
                    },
                    dataType: 'Json',
                    success: function(data) {
                        console.log(data);
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
                        $('.loader').empty().css({'display': 'none'});
                    },
                    error: function(data) {
                        swal("Oops!!", "Something went wrong. Please try again.", "error");
                        $('.loader').empty().css({'display': 'none'});
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
                        url: "{{ url('quotation/remove-item-from-version') }}",
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
            CopyQuotation = function(QuotationId) {
                var r = confirm("Are you sure! you wan't to copy this quotation.");
                if (r == true) {
                    var VersionId = $("#currentVersion").val();
                    if (VersionId == "") {
                        return false;
                    }
                    $.ajax({
                        type: "POST",
                        url: "{{ url('quotation/copy-existing-quotation') }}",
                        data: {
                            _token: $("#_token").val(),
                            quotationId: QuotationId,
                            versionId: VersionId
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.status == "success") {
                                swal({
                                    title: "Copied!",
                                    text: data.message,
                                    type: "success"
                                }).then(function() {
                                    window.location = "{{ url('/') }}/" + data.url;
                                });
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
            DeleteQuotation = function() {
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
                        url: "{{ url('quotation/deletequotation') }}",
                        data: {
                            _token: $("#_token").val(),
                            quotationId: quotationId,
                            versionId: VersionId
                        },
                        dataType: 'Json',
                        success: function(data) {
                            console.log(data)
                            if (data.status == "success") {
                                window.location.href = "{{ url('/') }}/" + data.url;
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
            ExcelExportNew = function() {
                var excelexportNewUrl = $("#excelexportNewUrl").val();
                var excelexportVicaimaUrl = $("#excelexportVicaimaUrl").val();
                var quotationId = $("#quotationId").val();
                var quotationconfigurableitems = $("#quotationconfigurableitems").val();
                var currentVersion = $("#currentVersion").val();
                if (currentVersion != 0) {
                    if(quotationconfigurableitems == 4 || quotationconfigurableitems == 5 || quotationconfigurableitems == 6){
                        window.location.href = excelexportVicaimaUrl + '/' + quotationId + '/' + currentVersion;
                    }else{
                        window.location.href = excelexportNewUrl + '/' + quotationId + '/' + currentVersion;
                    }
                } else {
                    swal("Oops!", "You haven't selected any version yet.", "error");
                }
            };
            ExportBomCalculation = function() {
                var ExportBomCalculationUrl = $("#ExportBomCalculationUrl").val();
                var excelexportVicaimaUrl = $("#excelexportVicaimaUrl").val();
                var quotationId = $("#quotationId").val();
                var quotationconfigurableitems = $("#quotationconfigurableitems").val();
                var currentVersion = $("#currentVersion").val();
                if (currentVersion != 0) {
                    if(quotationconfigurableitems == 4 || quotationconfigurableitems == 5 || quotationconfigurableitems == 6){
                        window.location.href = ExportBomCalculationUrl + '/' + quotationId + '/' + currentVersion;
                    }else{
                        window.location.href = ExportBomCalculationUrl + '/' + quotationId + '/' + currentVersion;
                    }
                } else {
                    swal("Oops!", "You haven't selected any version yet.", "error");
                }
            };
            ExportDoorTypeBom = function() {
                var ExportDoorTypeBomUrl = $("#ExportDoorTypeBomUrl").val();
                var excelexportVicaimaUrl = $("#excelexportVicaimaUrl").val();
                var quotationId = $("#quotationId").val();
                var quotationconfigurableitems = $("#quotationconfigurableitems").val();
                var currentVersion = $("#currentVersion").val();
                if (currentVersion != 0) {
                    if(quotationconfigurableitems == 4){
                        window.location.href = ExportDoorTypeBomUrl + '/' + quotationId + '/' + currentVersion;
                    }else{
                        window.location.href = ExportDoorTypeBomUrl + '/' + quotationId + '/' + currentVersion;
                    }
                } else {
                    swal("Oops!", "You haven't selected any version yet.", "error");
                }
            };
            ExportSideScreen = function() {
                var ExportSideScreenUrl = $("#ExportSideScreenUrl").val();
                var excelexportVicaimaUrl = $("#excelexportVicaimaUrl").val();
                var quotationId = $("#quotationId").val();
                var quotationconfigurableitems = $("#quotationconfigurableitems").val();
                var currentVersion = $("#currentVersion").val();
                if (currentVersion != 0) {
                    window.location.href = ExportSideScreenUrl + '/' + quotationId + '/' + currentVersion;
                } else {
                    swal("Oops!", "You haven't selected any version yet.", "error");
                }
            };
            ExportIronmongery = function(){
                var ExportIronmongeryUrl = $("#ExportIronmongeryUrl").val();
                var quotationId = $("#quotationId").val();
                var currentVersion = $("#currentVersion").val();
                if (currentVersion != 0) {
                        window.location.href = ExportIronmongeryUrl + '/' + quotationId + '/' + currentVersion;
                } else {
                    swal("Oops!", "You haven't selected any version yet.", "error");
                }
            }
            MainFormImport = function() {
                var mainformimportUrl = $("#mainformimportUrl").val();
                var quotationId = $("#quotationId").val();
                var currentVersion = $("#currentVersion").val();
                window.location.href = mainformimportUrl + '/' + quotationId + '/' + currentVersion;
            }
            CopyDoorSet = function(QuotationId, id) {
                var r = confirm("Are you sure! you wan't to copy this doorset.");
                if (r == true) {
                    var VersionId = $("#currentVersion").val();
                    if (VersionId == "") {
                        return false;
                    }
                    $.ajax({
                        type: "POST",
                        url: "{{ url('quotation/copy-existing-doorset') }}",
                        data: {
                            _token: $("#_token").val(),
                            quotationId: QuotationId,
                            versionId: VersionId,
                            id: id
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.status == "success") {
                                swal({
                                    title: "Copied!",
                                    text: data.message,
                                    type: "success"
                                }).then(function() {
                                    window.location = "{{ url('/') }}/" + data.url;
                                });
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
            CopyDoorSetScreen = function(QuotationId, id) {
                var r = confirm("Are you sure! you wan't to copy this doorset.");
                if (r == true) {
                    var VersionId = $("#currentVersion").val();
                    if (VersionId == "") {
                        return false;
                    }
                    $.ajax({
                        type: "POST",
                        url: "{{ url('quotation/copy-existing-doorset-screen') }}",
                        data: {
                            _token: $("#_token").val(),
                            quotationId: QuotationId,
                            versionId: VersionId,
                            id: id
                        },
                        dataType: 'json',
                        success: function(data) {
                            if (data.status == "success") {
                                swal({
                                    title: "Copied!",
                                    text: data.message,
                                    type: "success"
                                }).then(function() {
                                    window.location = "{{ url('/') }}/" + data.url;
                                });
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
            $(function() {
                $('#QuoteSummaryDiscount').keyup();;
            });
            $(document).on('keyup', '#QuoteSummaryDiscount', function(e) {
                e.preventDefault();

                // Get the discount percentage and convert to a number
                let QuoteSummaryDiscountValue = parseFloat($(this).val()) || 0;

                // Get the total price and convert to a number
                let QuoteSummaryTotalDoorPriceValue = parseFloat($('#QuoteSummaryTotalDoorPrice').val()) || 0;

                // Calculate the discount amount
                let totalQSdiscountValue = (QuoteSummaryTotalDoorPriceValue * Math.abs(QuoteSummaryDiscountValue)) / 100;

                // Apply positive discount as subtraction, negative discount as addition
                let totalGBP = QuoteSummaryDiscountValue > 0
                    ? QuoteSummaryTotalDoorPriceValue + totalQSdiscountValue
                    : QuoteSummaryTotalDoorPriceValue - totalQSdiscountValue;

                // Update the discount and total amount display
                $('#QSdiscountValue').html((QuoteSummaryDiscountValue > 0 ? '+' : '-') + totalQSdiscountValue.toFixed(2));
                if (totalQSdiscountValue === 0) {
                    $('#QSdiscountValue').closest('p').hide(); // Hide when discount is 0.00
                } else {
                    $('#QSdiscountValue').closest('p').show(); // Show when discount is non-zero
                }
                //$('.total_amount > span').html(totalGBP.toFixed(2));
            });

            function adjustPriceDiscountBtn(){
                var url = $('#adjustPriceDiscountUrl').val();
                var versionId = $('#versionId').val();
                var quotationId = $('#quotationId').val();
                var QuoteSummaryDiscount = $('#QuoteSummaryDiscount').val();
                const token = $("#_token").val();
                if(QuoteSummaryDiscount == ''){
                    swal('Warning', 'Please fill in the Discount (% Off List) field.');
                    return false;
                }
                if(versionId == '0'){
                    alert("You haven't selected any version yet.");
                    return false;
                }
                var r = confirm("Do you want to adjust the price for Configurable Items, Non-Configurable Items, and Side Screens? If yes, then the prices for all items will be overridden with the adjusted price.");
                if (r == true) {
                    $('.loader').css({'display':'block'});
                    $.ajax({
                        url: url,
                        type: 'post',
                        data: {
                            _token: $("#_token").val(),
                            QuoteSummaryDiscount:QuoteSummaryDiscount,
                            versionId:versionId,
                            quotationId: quotationId
                        },
                        dataType: 'json',
                        success: function(data) {
                            $('.loader').css({'display':'none'});
                            if (data.status == true) {
                                swal('success', data.msg, 'success').then(function() {
                                    location.reload();
                                });
                            } else {
                                swal('error', data.msg, 'error').then(function() {
                                    location.reload();
                                });
                            }
                        }
                    });
                }
            }

            @if (isset($Count))
                let CustomerCounter = {{ $Count }};
            @else
                let CustomerCounter = 1;
            @endif
            $(document).on('click', '.deleteQuotDeliverAddr', function(e) {
                e.preventDefault();
                let quotationDeliveryAddressID = $(this).siblings('.QuotDeliverAddrID').val();
                let self = $(this);
                $.ajax({
                    type: "POST",
                    url: "{{ route('delquotationDeliveryAddress') }}",
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
            function RemoveCustomerSection(id) {
                $("#customer-details-" + id).remove();
            }
            $(document).on("click", "#add-customer-detail", function() {
                CustomerCounter = CustomerCounter + 1;
                var CustomerDetails = `
        <input type="hidden" name="quotation_sitedeliveryaddressID[]">
        <div class="col-sm-12" id="customer-details-` + CustomerCounter +
                    `">
            <div class="card-header">
                <h5 class="card-title" style="margin-top: 10px">Site Delivery Address</h5>
            </div>
            <div>
                <a style="float: right; margin-right: 10px; margin-top: -45px" href="javascript:void(0);" onclick="RemoveCustomerSection(` + CustomerCounter + `);" class="btn-shadow btn btn-danger">
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
            $(document).on('click', '.closeModal', function() {
                $('#RejectquotationModal').removeClass('show')
                $('#RejectquotationModal').addClass('fade')
            })
        </script>
        <script>
            $("#CustomerContactIdSTC, #QSQuotationSiteDeliveryAddressId, #CustomerContactIdSTC_Name").on('change', function() {
                if ($("#CustomerContactIdSTC").val() == $(this).val()) {
                    var id = $("#CustomerContactIdSTC").val();
                } else {
                    var id = $("#CustomerContactIdSTC_Name").val();
                }
                $.ajax({
                    type: 'POST',
                    url: "{{ route('QuoteSummaryOnChangeCustomer') }}",
                    data: {
                        quotationId: $("#quotationId").val(),
                        QSCustomerContactId: id,
                        QSQuotationSiteDeliveryAddressId: $("#QSQuotationSiteDeliveryAddressId").val()
                    },
                    success: function(data) {
                        console.log('success')
                    }
                })
            })
        </script>
        <script>
            function remove_item(id) {
                var r = confirm("Do you want to remove this item");
                var version = $("#version").val();
                if (r == true) {
                    $.ajax({
                        url: "{{ route('item/remove') }}",
                        method: "post",
                        data: {
                            id: id,
                            version: version
                        },
                        success: function(data) {
                            window.location.reload();
                        }
                    })
                } else {
                }
            }
            function remove_item_screen(id) {
                var r = confirm("Do you want to remove this item");
                var version = $("#version").val();
                if (r == true) {
                    $.ajax({
                        url: "{{ route('item/remove-screen') }}",
                        method: "post",
                        data: {
                            id: id,
                            version: version
                        },
                        success: function(data) {
                            window.location.reload();
                        }
                    })
                } else {
                }
            }
            function favoriteItem(itemId, id) {
                $('#itemId').val(itemId);
                $('#itemMasterId').val(id);
                $("#Favorite-modal").modal("show");
            }
            function adjustPrice(itemId, id, totalPrice) {
                $('#adjustPriceitemId').val(itemId);
                $('#adjustPriceitemMasterId').val(id);
                $('#totalPrice').val(totalPrice);
                $("#adjust-price-modal").modal("show");
            }
        </script>
    @endsection
    {{--  //adjust price modal  --}}
    <div id="adjust-price-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Adjust Price</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 mb-2">
                            <label for="doorTypeName">Total Price</label>
                            <input type="number" class="form-control" id="totalPrice" readonly>
                        </div>
                        <div class="col-sm-12 mb-2">
                            <label for="doorTypeName">Adjust Price</label>
                            <input type="number" class="form-control" id="AdjustPrice"
                                pattern="[0-9]+([\.,][0-9]+)?" placeholder="Enter Adjust Price" required=""
                                step="0.01">
                            <input type="hidden" class="form-control" id="adjustPriceitemId">
                            <input type="hidden" class="form-control" id="adjustPriceitemMasterId">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="adjustPriceAjax()" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    {{--  //Favorite modal  --}}
    <div id="Favorite-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Favorite Item</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 mb-2">
                            <label for="doorTypeName">Door Type Name</label>
                            <input type="text" class="form-control" id="doorTypeName">
                            <input type="hidden" class="form-control" id="itemId">
                            <input type="hidden" class="form-control" id="itemMasterId">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button onclick="favorite()" class="btn btn-success">Submit</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <div id="Favorite-Item-modal" class="modal fade" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Favorite Item List</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12 mb-2">
                            @if (!empty($Favorite) && $Favorite != '')
                                @foreach ($Favorite as $value)
                                    <div class="row">
                                        <div class="col-sm-5">
                                            <p>{{ $value->DoorType }}</p>
                                        </div>
                                        <div class="col-sm-2">
                                            <button
                                                onclick="favoriteItemAdd('{{ $value->itemId }}','{{ $value->itemMasterId }}','{{ $value->quotationId }}','{{ $value->versionId }}')"
                                                class="btn btn-success">Assign</button>
                                        </div>
                                        <div class="col-sm-2">
                                            <a href="{{ ConfigurationURL($value->configurableitems, $value->itemId, $value->versionId) }}" class="btn btn-info">Edit</a>
                                        </div>
                                        <div class="col-sm-2">
                                            <button
                                                onclick="favoriteDeleteItem('{{ $value->id }}')"
                                                class="btn btn-danger">Delete</button>
                                        </div>
                                        <div class="col-sm-1"></div>
                                    </div>
                                @endforeach
                            @else
                                <div class="row">
                                    <div class="col-sm-8">
                                        <p>Data not found!</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
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
                                {{-- <option value="newitem">New Items</option> --}}
                                @if (is_array($version))
                                    @foreach ($version as $row)
                                        <option value="{{ $row['id'] }}">Revision-{{ $row['version'] }}</option>
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
                        {{-- <div class="col-sm-12 mb-2"> --}}
                        {{-- <div class="position-relative form-group"> --}}
                        {{-- <input type="radio" id="previous-version" onclick="" class="form-control" name="version_option" > --}}
                        {{-- <label for="previous-version" >Want to copy item's from previous version.</label> --}}
                        {{-- <input type="radio" id="new-version" class="form-control" name="version_option" > --}}
                        {{-- <label for="new-version" >New Version</label> --}}
                        {{-- </div> --}}
                        {{-- </div> --}}
                        <div class="col-sm-12 mb-2">
                            <p>Select Revision</p>
                            <select name="createNewSelectVersion" id="createNewSelectVersion" class="form-control">
                                <option value="">Select any revision</option>
                                @if (is_array($version))
                                    @foreach ($version as $row)
                                        <option value="{{ $row['id'] }}">Revision-{{ $row['version'] }}</option>
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
    <input type="hidden" id="get_contact_details" value="{{ route('get-contact-details') }}">
    @if ($quotation->QuotationStatus == 'Reject')
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
    <input type="hidden" id="bom_calculation" value="{{ route('quotation/BomCalculation') }}">
