@extends("...layouts.Master")

@section("main_section")
<div class="app-main__outer">
    <div class="app-main__inner">
        <!-- <div class="app-page-title">
            <div class="page-title-wrapper"> -->
                <!-- <div class="page-title-heading">
                <div class="page-title-icon">
                    <i class="pe-7s-graph text-success">
                    </i>
                </div>
                <div>Form Layouts
                    <div class="page-title-subheading">Build whatever layout you need with our Architect framework.
                    </div>
                </div>
            </div> -->
                <!-- <div class="page-title-actions">
                <div class="d-inline-block dropdown">
                   <a href="{{route('company/list')}}" class="btn-shadow  btn btn-info">
                        Company List
                    </a>
                </div>
            </div>     -->
            <!-- </div>
        </div> -->

<!--
        <div class="main-card mb-3 card">
        </div> -->

        @if (\Session::has('msg'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('msg') !!}</li>
            </ul>
        </div>
        @endif
        <form id="itemForm" enctype="multipart/form-data" method="post" action="{{route('items/store')}}">

            <div class="tab-content">
                <div class="main-card mb-3 custom_card">
                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                    @if(isset($editdata->id))
                    <input type="hidden" name="update" value="{{ $editdata->id }}">
                    @endif
                    <div class="card-header">
                        <h5 class="card-title" style="margin-top: 10px">Main Options</h5>
                    </div>
                    <div class="">
                        <div class="tab-content">
                            <div class="">
                                <div class="form-row">
                                    <!-- <div class="col-md-3">
                        <div class="position-relative form-group">
                        <label for="Company Name" class="">Company Name</label>
                        <input name="CompanyName" required placeholder="Enter Company Name" value="@if(isset($editdata->id)) {{$editdata->CompanyName}} @endif" type="text" class="form-control"></div>
                    </div> -->
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leafConstruction">Leaf Construction</label>
                                            <select required name="leafConstruction" id="leafConstruction"
                                                class="form-control">
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='leaf_construction')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="lineNumber" class="">Line No.</label>
                                <input name="lineNumber"id="lineNumber"  required  type="number" class="form-control">
                            </div>
                        </div> -->

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorType" class="">Door Type</label>
                                            <input name="doorType" id="doorType" required placeholder="Enter door type"
                                                type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="DoorNo" class="">Door No.</label>
                                            <input name="DoorNo" id="DoorNo" required placeholder="Enter door number"
                                                type="text" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="fireRating" class="">Fire Rating</label>
                                            <select required name="fireRating" id="fireRating" class="form-control">
                                                <option value="">Select fire rating</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='fire_rating')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorsetType" class="">Doorset Type <span
                                                    class="dsl"></span></label>
                                            <!-- combination_of -->
                                            <select required name="doorsetType" id="doorsetType"
                                                class="form-control  change-event-calulation">

                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='door_configuration_doorset_type')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="swingType" class="">Swing Type <span class="dsl"></span></label>
                                            <select required name="swingType" id="swingType"
                                                class="form-control combination_of">
                                                <option value="">Select swing type</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='door_configuration_swing_type')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="latchType" class="">Latch Type <span class="dsl"></span></label>
                                            <select required name="latchType" id="latchType"
                                                class="form-control combination_of">
                                                <option value="">Select latch type</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='door_configuration_latch_type')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="overpanel" class="">Overpanel</label>
                                            <select required name="overpanel" id="overpanel"
                                                class="form-control change-event-calulation">

                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='door_configuration_overpanel')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="OPLippingThickness" class="">OP Lipping Thickness</label>
                                            <select name="OPLippingThickness" id="OPLippingThickness"
                                                class="form-control">
                                                <option value="">Select Op Thickness</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='op_lipping_thickness')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="oPWidth" class="">OP Width</label>
                                            <input name="oPWidth" id="oPWidth" readonly class="form-control oPWidth"
                                                type="text">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="oPHeigth" class="">OP Height (Max-value:600)</label>
                                            <input name="oPHeigth" id="oPHeigth" max="600" class="form-control"
                                                type="number">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="opTransom" class="">OP Transom</label>
                                            <select id="opTransom" name="opTransom" class="form-control">
                                                <option value="1">1</option>
                                                <option value="2">2</option>
                                                <option value="3">3</option>
                                                <option value="4">4</option>
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="transomThickness" class="">Transom Thickness</label>
                                            <select name="transomThickness" id="transomThickness" class="form-control">
                                                <option value="">Select transform thickness</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='transom_thickness')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorQuantity" class="">Door Quantity</label>
                                            <input type="number" id="doorQuantity" value="1" name="doorQuantity"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="floor" class="">Floor</label>
                                            <input type="text" id="floor" name="floor" class="form-control">

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="location" class="">Location</label>
                                            <input type="text" id="location" name="location" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="tollerance" class="">Tollerance</label>
                                            <input type="number" id="tollerance" name="tollerance"
                                                class="form-control for_c_leaf_height  forleafHeightNoOP change-event-calulation">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="undercut" class="">Undercut</label>
                                            <input type="number" id="undercut" readonly name="undercut"
                                                class="form-control change-event-calulation  undercut">

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="floorFinish" class="">Floor Finish</label>
                                            <input type="number" id="floorFinish" name="floorFinish"
                                                class="form-control change-event-calulation forundercut">

                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="gap" class="">GAP</label>
                                            <input type="number" id="gap" name="gap"
                                                class="form-control for_c_leaf_height  forleafHeightNoOP change-event-calulation">

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="frameThickness" class="">Frame Thickness</label>
                                            <!-- <input type="number" id="frameThickness" name="frameThickness" class="form-control for_c_leaf_height  forleafHeightNoOP change-event-calulation"> -->
                                            <select name="frameThickness" required
                                                class="form-control change-event-calulation leaf_width_1 "
                                                id="frameThickness">
                                                <option value="">Select Frame thickness</option>
                                                <option value="30">30</option>
                                                <option value="32">32</option>

                                            </select>
                                        </div>
                                    </div>




                                </div>
                            </div>
                        </div>
                    </div>
                </div>




                <div class="main-card mb-3 custom_card">
                    <div class="card-header">
                        <h5 class="card-title" style="margin-top: 10px">DOOR DIMENSIONS & DOOR LEAF</h5><span></span>
                    </div>
                    <div class="">
                        <div class="tab-content">
                            <div class="">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="sOWidth" class="">S.O Width</label>
                                            <input type="number" id="sOWidth" name="sOWidth"
                                                class="form-control  change-event-calulation">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="sOHeight" class="">S.O Height</label>
                                            <input type="number" id="sOHeight" name="sOHeight"
                                                class="form-control change-event-calulation">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="sODepth" class="">S.O Depth</label>
                                            <input type="number" id="sODepth" name="sODepth"
                                                class="form-control change-event-calulation ">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leafWidth1" class="">Leaf Width 1</label>
                                            <input type="number" readonly id="leafWidth1" name="leafWidth1" readonly
                                                class="form-control change-event-calulation forcoreWidth1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leafWidth2" class="">Leaf Width 2</label>
                                            <input type="number" readonly id="leafWidth2" name="leafWidth2"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leafHeightNoOP" class="">Leaf Height</label>
                                            <input type="number" id="leafHeightNoOP" readonly name="leafHeightNoOP"
                                                class="form-control leafHeightNoOP foroPWidth">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorThickness" class="">Door Thickness (mm)</label>
                                            <input type="number" readonly name="doorThickness" id="doorThickness"
                                                class="form-control">
                                            <!-- <select name="doorThickness" id="doorThickness" class="form-control">
                          <option value="">Select door thickness</option>
                                @foreach($option_data as $row)
                                @if($row->OptionSlug=='door_thickness')
                                  <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                @endif
                                @endforeach -->
                                            <!-- </select> -->
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorLeafFacing" class="">Door Leaf Facing</label>
                                            <select name="doorLeafFacing" id="doorLeafFacing" class="form-control">
                                                <option value="">Select door leaf facing</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='Door_Leaf_Facing')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorLeafFacingValue" class="">Door Leaf Facing value</label>
                                            <select name="doorLeafFacingValue" id="doorLeafFacingValue"
                                                class="form-control">
                                                <option value="">Select door leaf facing value</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorLeafFacing" class="">Door Leaf Finish</label>
                                            <select name="doorLeafFinish" id="doorLeafFinish" class="form-control">
                                                <option value="">Select door leaf finish</option>
                                                <!-- @foreach($option_data as $row)
                                @if($row->OptionSlug=='door_leaf_finish')
                                  <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                @endif
                                @endforeach -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorLeafFinishColor" class="">Door Leaf Finish Color</label>
                                            <select name="doorLeafFinishColor" id="doorLeafFinishColor"
                                                class="form-control">
                                                <option value="">Select door leaf finish color</option>
                                                <!-- @foreach($option_data as $row)
                                @if($row->OptionSlug=='door_leaf_finish')
                                  <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                @endif
                                @endforeach -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="decorativeGroves" class="">Decorative Groves</label>
                                            <select name="decorativeGroves" id="decorativeGroves" class="form-control">
                                                <option value="">Select Decorative Groves</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='Decorative_Groves')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>



                                </div>
                            </div>
                        </div>

                    </div>

                </div>

                <div class="main-card mb-3 custom_card">
                    <div class="">
                        <div class="tab-content">
                            <div class="card-header">
                                <h5 class="card-title" style="margin-top: 10px">VISION PANEL</h5>
                            </div>
                            <div class="">
                                <div class="form-row">


                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leaf1VisionPanel" class="">Leaf 1 Vision Panel</label>
                                            <select required name="leaf1VisionPanel" id="leaf1VisionPanel"
                                                class="form-control change-event-calulation">
                                                <option val=""> Is Vision Panel active? </option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='leaf1_vision_panel')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="visionPanelQuantity" class="">Vision Panel Quantity</label>
                                            <select name="visionPanelQuantity" id="visionPanelQuantity" disabled
                                                class="form-control change-event-calulation">
                                                <option value="">Select quantity</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='leaf1_vision_panel_Qty')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="AreVPsEqualSizes" class="">Are VP's equal sizes?</label>
                                            <select name="AreVPsEqualSizes" id="AreVPsEqualSizes" disabled
                                                class="form-control change-event-calulation">
                                                <option value="">Are Visible panel equll size?</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='leaf1are_vps_equal_sizes')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>



                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="distanceFromTopOfDoor" class="">Distance from top of
                                                door</label>
                                            <input required Type="number" readonly min="100"
                                                name="distanceFromTopOfDoor" id="distanceFromTopOfDoor"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="distanceBetweenVPs" class="">Distance between VP's</label>
                                            <input Type="number" min="80" readonly name="distanceBetweenVPs"
                                                id="distanceBetweenVPs" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP1Width" class="">Leaf 1 VP Width</label>
                                            <input Type="number" min="0" readonly name="vP1Width" id="vP1Width"
                                                class="form-control change-event-calulation">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP1Height1" class="">Leaf 1 VP Height (1)</label>
                                            <input Type="number" min="0" readonly name="vP1Height1" id="vP1Height1"
                                                class="form-control change-event-calulation">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP1Height2" class="">Leaf 1 VP Height (2)</label>
                                            <input Type="number" min="0" readonly name="vP1Height2" id="vP1Height2"
                                                class="form-control change-event-calulation">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP1Height3" class="">Leaf 1 VP Height (3)</label>
                                            <input Type="number" min="0" readonly name="vP1Height3" id="vP1Height3"
                                                class="form-control change-event-calulation">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP1Height4" class="">Leaf 1 VP Height (4)</label>
                                            <input Type="number" min="0" readonly name="vP1Height4" id="vP1Height4"
                                                class="form-control change-event-calulation">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP1Height5" class="">Leaf 1 VP Height (5)</label>
                                            <input Type="number" min="0" readonly name="vP1Height5" id="vP1Height5"
                                                class="form-control change-event-calulation">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leaf1VpAreaSizeM2" class=""> Leaf 1 VP Area Size m2</label>
                                            <input Type="number" min="0" readonly name="leaf1VpAreaSizeM2"
                                                id="leaf1VpAreaSizeM2" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leaf2VisionPanel" class="">Leaf 2 Vision Panel</label>
                                            <select disabled name="leaf2VisionPanel" id="leaf2VisionPanel"
                                                class="form-control">
                                                <option value=""> Is Vision Panel fr leaf 2 active? </option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='leaf1_vision_panel')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vpSameAsLeaf1" class="">Is VP same as Leaf 1?</label>
                                            <select name="vpSameAsLeaf1" id="vpSameAsLeaf1" disabled
                                                class="form-control">
                                                <option value="">Is VP same as Leaf 1?</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='leaf1are_vps_equal_sizes')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="visionPanelQuantityforLeaf2" class="">Leaf 2 Vision Panel
                                                Quantity</label>
                                            <select name="visionPanelQuantityforLeaf2" id="visionPanelQuantityforLeaf2"
                                                disabled class="form-control">
                                                <option value="">Select quantity</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='leaf1_vision_panel_Qty')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="AreVPsEqualSizesForLeaf2" class="">Are VP's equal sizes for leaf
                                                2?</label>
                                            <select name="AreVPsEqualSizesForLeaf2" id="AreVPsEqualSizesForLeaf2"
                                                disabled class="form-control">
                                                <option value="">Are Visible panel equll size?</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='leaf1are_vps_equal_sizes')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="distanceFromTopOfDoorforLeaf2" class="">Distance from top of
                                                door for Leaf 2</label>
                                            <input required Type="number" readonly min="100"
                                                name="distanceFromTopOfDoorforLeaf2" id="distanceFromTopOfDoorforLeaf2"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="distanceBetweenVPsforLeaf2" class="">Distance between
                                                VP's</label>
                                            <input Type="number" min="80" readonly name="distanceBetweenVPsforLeaf2"
                                                id="distanceBetweenVPsforLeaf2" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP2Width" class="">Leaf 2 VP Width</label>
                                            <input Type="number" min="0" readonly name="vP2Width" id="vP2Width"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP2Height1" class="">Leaf 2 VP Height (1)</label>
                                            <input Type="number" min="0" readonly name="vP2Height1" id="vP2Height1"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP2Height2" class="">Leaf 2 VP Height (2)</label>
                                            <input Type="number" min="0" readonly name="vP2Height2" id="vP2Height2"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP2Height3" class="">Leaf 2 VP Height (3)</label>
                                            <input Type="number" min="0" readonly name="vP2Height3" id="vP2Height3"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP2Height4" class="">Leaf 2 VP Height (4)</label>
                                            <input Type="number" min="0" readonly name="vP2Height4" id="vP2Height4"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP2Height5" class="">Leaf 2 VP Height (5)</label>
                                            <input Type="number" min="0" readonly name="vP2Height5" id="vP2Height5"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="leaf2VpAreaSizeM2" class="">Leaf 2 VP Area Size m2</label>
                            <input Type="number" min="0" readonly name="leaf2VpAreaSizeM2" id="leaf2VpAreaSizeM2" class="form-control">
                            </div>
                    </div> -->
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="lazingIntegrityOrInsulationIntegrity" class="">Glass
                                                Integrity</label>

                                            <select name="lazingIntegrityOrInsulationIntegrity"
                                                id="lazingIntegrityOrInsulationIntegrity" class="form-control">
                                                <option value=''> Select Glass Integrity</option>

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glassType" class="">Glass Type</label>
                                            <select name="glassType" id="glassType" class="form-control">
                                                <option value="">Select Glass Type</option>
                                                <!-- @foreach($option_data as $row)
                                @if($row->OptionSlug=='leaf1_glass_type')
                                  <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                @endif
                                @endforeach -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glassThickness" class="">Glass Thickness</label>
                                            <select name="glassThickness" id="glassThickness" class="form-control">
                                                <option value=""> Select glass thickness</option>
                                                <!-- @foreach($option_data as $row)
                                @if($row->OptionSlug=='leaf1_glass_thickness')
                                  <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                @endif
                                @endforeach -->

                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glazingSystems" class="">Glazing Systems</label>
                                            <select name="glazingSystems" id="glazingSystems" class="form-control">
                                                <option value=""> Select Glazing Systems</option>
                                                <!-- @foreach($option_data as $row)
                                @if($row->OptionSlug=='leaf1_glazing_systems')
                                  <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                @endif
                                @endforeach -->
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glazingSystemsThickness" class="">Glazing System
                                                Thickness</label>
                                            <select name="glazingSystemsThickness" id="glazingSystemsThickness"
                                                class="form-control">
                                                <option value="">Select Glazing Thickness</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glazingBeads" class="">Glazing Beads</label>
                                            <select name="glazingBeads" id="glazingBeads" class="form-control">
                                                <option value="">Select Glazing Beads</option>
                                                <!-- @foreach($option_data as $row)
                                @if($row->OptionSlug=='leaf_construction')
                                  <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                @endif
                                @endforeach -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glazingBeadsThickness" class="">Glazing Beads Thickness</label>
                                            <input Type="number" min="0" name="glazingBeadsThickness"
                                                id="glazingBeadsThickness" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glazingBeadSpecies" class="">Glazing Bead Species</label>
                                            <select name="glazingBeadSpecies" id="glazingBeadSpecies"
                                                class="form-control">
                                                <option value="">Select Species</option>
                                                <!-- @foreach($option_data as $row)
                                @if($row->OptionSlug=='leaf_construction')
                                  <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                @endif
                                @endforeach -->
                                            </select>


                                        </div>
                                    </div>





                                    <!-- <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="overPanel" class="">Over Panel (OP)/Fanlight</label>
                            <select name="overPanel" id="overPanel" class="form-control">
                                 @foreach($option_data as $row)
                                @if($row->OptionSlug=='Over_Panel_Fanlight')
                                  <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                @endif
                                @endforeach
                        </select>
                      </div>
                    </div> -->




                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="main-card mb-3 custom_card">
                    <div class="">
                        <div class="tab-content">
                            <div class="card-header">
                                <h5 class="card-title" style="margin-top: 10px">Frame </h5>
                            </div>
                            <div class="">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="frameMaterial" class="">Frame Material</label>
                                            <select name="frameMaterial" id="frameMaterial" class="form-control">
                                                <option value="">Select Frame material</option>
                                                <!-- @foreach($option_data as $row)
                                @if($row->OptionSlug=='leaf_construction')
                                  <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                @endif
                                @endforeach -->
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="frameType" class="">Frame Type</label>
                                            <select name="frameType" id="frameType" class="form-control">
                                                <option value="">Select Frame Type</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='Frame_Type')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="frameTypeDimensions" class="">Dimensions</label>
                                            <input type="number" name="frameTypeDimensions" value="0" min="1" readonly
                                                id="frameTypeDimensions" class="form-control">

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="frameWidth" class="">Frame Width</label>
                                            <input type="text" readonly name="frameWidth" id="frameWidth"
                                                placeholder="Frame Width" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="frameHeight" class="">Frame Height</label>
                                            <input type="text" readonly name="frameHeight" placeholder="Frame Height"
                                                id="frameHeight" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="frameDepth" class="">Frame Depth</label>
                                            <input type="text" readonly name="frameDepth" id="frameDepth"
                                                placeholder="Frame Depth" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="frameThickness1" class="">Frame Thickness</label>
                                            <input name="frameThickness1" required placeholder="Frame Thickness"
                                                id="frameThickness1" min="0" type="text" class="form-control">

                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="frameFinish" class="">Frame Finish</label>
                                            <select name="frameFinish" id="frameFinish"
                                                class="form-control change-event-calulation">
                                                <option value="">Select Frame finish</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='Frame_Finish')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="extLiner" class="">Ext-Liner</label>
                                            <select name="extLiner" id="extLiner"
                                                class="form-control change-event-calulation">
                                                <option value="">Select Ex-liner</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='Ext_Liner')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="extLinerValue" class="">Ext-Liner Value</label>
                                            <input type="text" readonly value="0" name="extLinerValue"
                                                id="extLinerValue" class="form-control">


                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="extLinerSize" class="">Ext-Liner Size</label>
                                            <input name="extLinerSize" id="extLinerSize" placeholder="Ext-Liner Size"
                                                class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="extLinerThickness" class="">Ext-Liner Thickness</label>
                                            <input name="extLinerThickness" id="extLinerThickness"
                                                placeholder="Ext-Liner Thickness"
                                                class="form-control change-event-calulation" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="extLinerFinish" class="">Ext-Liner FInish</label>
                                            <input name="extLinerFinish" id="extLinerFinish" readonly
                                                placeholder="Ext-Liner Finish" class="form-control" type="text">
                                        </div>
                                    </div>


                                    <!-- <div class="col-md-3">
                      <div class="position-relative form-group">
                        <label for="intumescentSealType" class="">Intumescent Seal Type</label>
                       <select name="intumescentSealType" id="intumescentSealType" class="form-control">
                                  <option value="Fire only">Fire only</option>
                                  <option value="Fire and Smoke">Fire and Smoke</option>
                        </select>
                      </div>
                   </div> -->
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="intumescentSeal" class="">Intumescent Seal</label>
                                            <select name="intumescentSeal" id="intumescentSeal" class="form-control">
                                                <option value="Frame">Frame</option>
                                                <option value="Door Leaf 1">Door Leaf 1</option>
                                                <option value="Door Leaf 2">Door Leaf 2</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="intumescentSealColor" class="">Intumescent Seal Color</label>
                                            <select name="intumescentSealColor" id="intumescentSealColor"
                                                class="form-control">
                                                <option value="White">White</option>
                                                <option value="brown">brown</option>
                                                <option value="black">black</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="intumescentSealSize" class="">Intumescent Seal Size</label>
                                            <select name="intumescentSealSize" id="intumescentSealSize"
                                                class="form-control">
                                                <option value="10x4mm">10x4mm</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="ironmongerySet" class="">Ironmongery Set</label>
                                            <select name="ironmongerySet" id="ironmongerySet" class="form-control">
                                                <option value="Yes">Yes</option>
                                                <option value="No">No</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="specialFeatureRefs" class="">Special Feature Refs</label>
                                            <input name="specialFeatureRefs" id="specialFeatureRefs"
                                                placeholder="Special Feature Refs" class="form-control" type="text">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="main-card mb-3 custom_card">
                    <div class="">
                        <div class="tab-content">
                            <div class="card-header">
                                <h5 class="card-title" style="margin-top: 10px">SIDE LIGHT</h5>
                            </div>
                            <div class="">
                                <div class="form-row">




                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="sideLight1" class="">Side Light 1 (SL1)</label>
                                            <select name="sideLight1" id="sideLight1" class="form-control">
                                                <option value=""> Is side light 1 is active?</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='SideLight1')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="sideLight1GlassType" class="">Side Light 1 Glass Type</label>
                                            <select name="sideLight1GlassType" disabled id="sideLight1GlassType"
                                                class="form-control">
                                                <option value="">Select Glass Type</option>
                                                <!-- @foreach($option_data as $row)
                                @if($row->OptionSlug=='leaf1_glass_type')
                                  <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                @endif
                                @endforeach -->
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SideLight1BeadingType" class="">Beading Type</label>
                                            <select name="SideLight1BeadingType" id="SideLight1BeadingType" disabled
                                                class="form-control">
                                                <option value="">Select Beading Type</option>
                                                <!-- @foreach($option_data as $row)
                                @if($row->OptionSlug=='leaf1_glass_type')
                                  <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                @endif
                                @endforeach -->
                                            </select>


                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SideLight1GlazingBeadSpecies" class="">Glazing Bead
                                                Species</label>
                                            <select name="SideLight1GlazingBeadSpecies"
                                                id="SideLight1GlazingBeadSpecies" disabled class="form-control">
                                                <option value="">Select Glazing Bead Species</option>
                                                <!-- @foreach($option_data as $row)
                                @if($row->OptionSlug=='leaf1_glass_type')
                                  <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                @endif
                                @endforeach -->
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL1Width" class="">SL1 Width</label>
                                            <input name="SL1Width" readonly id="SL1Width" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL1Height" readonly class="">SL1 Height</label>
                                            <input name="SL1Height" readonly id="SL1Height" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL1Depth" class="">SL1 Depth</label>
                                            <input name="SL1Depth" readonly id="SL1Depth" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL1Transom" class="">SL1 Transom</label>
                                            <select name="SL1Transom" disabled id="SL1Transom" class="form-control">
                                                <option value="">Select side light 1 transom</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='SideLight1_transom')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="sideLight2" class="">Side Light 2 (SL2)</label>
                                            <select name="sideLight2" id="sideLight2" class="form-control">
                                                <option value=""> Is side light 2 is active?</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='SideLight2')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="copyOfSideLite1" class="">Do you want to copy Same as
                                                SL1?</label>
                                            <select name="copyOfSideLite1" id="copyOfSideLite1" class="form-control"
                                                disabled>
                                                <option value=""> Do you want to copy Same as SL1?</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='copy_Same_as_SL1')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="sideLight2GlassType" class="">Side Light 2 Glass Type</label>
                                            <select name="sideLight2GlassType" disabled id="sideLight2GlassType"
                                                class="form-control">
                                                <option value="">Select Glass Type</option>
                                                <!-- @foreach($option_data as $row)
                                @if($row->OptionSlug=='leaf1_glass_type')
                                  <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                @endif
                                @endforeach -->
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SideLight2BeadingType" class="">Side Light 2 Beading
                                                Type</label>
                                            <select name="SideLight2BeadingType" id="SideLight2BeadingType" disabled
                                                class="form-control">
                                                <option value="">Select Beading Type</option>
                                                <!-- @foreach($option_data as $row)
                                @if($row->OptionSlug=='leaf1_glass_type')
                                  <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                @endif
                                @endforeach -->
                                            </select>


                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SideLight2GlazingBeadSpecies" class="">Side Light 2 Glazing Bead
                                                Species</label>
                                            <select name="SideLight2GlazingBeadSpecies"
                                                id="SideLight2GlazingBeadSpecies" disabled class="form-control">
                                                <option value="">Select Glazing Bead Species</option>
                                                <!-- @foreach($option_data as $row)
                                @if($row->OptionSlug=='leaf1_glass_type')
                                  <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                @endif
                                @endforeach -->
                                            </select>

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL2Width" class="">SL2 Width</label>
                                            <input name="SL2Width" readonly id="SL2Width" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL2Height" class="">SL2 Height</label>
                                            <input name="SL2Height" readonly id="SL2Height" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL2Depth" class="">SL2 Depth</label>
                                            <input name="SL2Depth" readonly id="SL2Depth" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL2Transom" class="">SL2 Transom</label>
                                            <select name="SL2Transom" disabled id="SL2Transom" class="form-control">
                                                <option value="">Select side light 2 transom</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='SideLight2_transom')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="main-card mb-3 custom_card">
                    <div class="">
                        <div class="tab-content">
                            <div class="card-header">
                                <h5 class="card-title" style="margin-top: 10px">LIPPING AND INTUMESCENT </h5>
                            </div>
                            <div class="">
                                <div class="form-row">

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="lippingType" class="">Lipping Type</label>
                                            <select name="lippingType" id="lippingType" class="form-control">
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='lipping_type')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="lippingThickness" class="">Lipping Thickness</label>
                                            <select name="lippingThickness" id="lippingThickness"
                                                class="form-control forcoreWidth1">
                                                <option value="">Select leaping thickness</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='lipping_thickness')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="lippingSpecies" class="">Lipping Species</label>
                                            <select name="lippingSpecies" id="lippingSpecies" class="form-control">
                                                <option value="">Select Lipping Species</option>
                                            </select>

                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="meetingStyle" class="">Meeting Style</label>
                                            <select name="meetingStyle" disabled id="meetingStyle" class="form-control">
                                                <option value="">Select Meeting Style</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='meeting_style')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="scallopedLippingThickness" class="">Scalloped Lipping
                                                Thickness</label>
                                            <select name="scallopedLippingThickness" id="scallopedLippingThickness"
                                                class="form-control">
                                                <option value="">Select Scalloped Lipping Thickness</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="flatLippingThickness" class="">Flat Lipping Thickness</label>
                                            <select name="flatLippingThickness" id="flatLippingThickness"
                                                class="form-control">
                                                <option value="">Select Flat Lipping Thickness</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="rebatedLippingThickness" class="">Rebated Lipping
                                                Thickness</label>
                                            <select name="rebatedLippingThickness" id="rebatedLippingThickness"
                                                class="form-control">
                                                <option value="">Select Rebated Lipping Thickness</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="coreWidth1" class="">Core Width 1</label>
                                            <input type="number" min="1" readonly id="coreWidth1" name="coreWidth1"
                                                class="form-control coreWidth1">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="intumescentSealType" class="">Intumescent Seal Type</label>
                                            <select name="intumescentSealType" id="intumescentSealType"
                                                class="form-control">
                                                <option value="">Select Intumescent seal type</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='intumescent_seal_type')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="intumescentSealLocation" class="">Intumescent Seal
                                                Location</label>
                                            <select name="intumescentSealLocation" id="intumescentSealLocation"
                                                class="form-control">
                                                <option value="">Select Intumescent seal Location</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='IntumescentSeal_location')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="intumescentSealColor" class="">Intumescent Seal Color</label>
                                            <select name="intumescentSealColor" id="intumescentSealColor"
                                                class="form-control">
                                                <option value="">Select Intumescent seal Color</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='Intumescent_Seal_Color')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="intumescentSealArrangement" class="">Intumescent Seal
                                                Arrangement</label>
                                            <input type="text" name="intumescentSealArrangement"
                                                id="intumescentSealArrangement" class="form-control">
                                        </div>
                                    </div>








                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="main-card mb-3 custom_card">
                    <div class="">
                        <div class="tab-content">
                            <div class="card-header">
                                <h5 class="card-title" style="margin-top: 10px">ACCOUSTICS </h5>
                            </div>
                            <div class="">
                                <div class="form-row">

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="accoustics" class="">Accoustics</label>
                                            <select name="accoustics" id="accoustics" class="form-control">
                                                <option value="">Select Accoustics</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='Accoustics')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="rWdBRating" class="">rW dB Rating</label>
                                            <input name="rWdBRating" id="rWdBRating" class="form-control" type="text">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="accousticsJambs" class="">Accoustics Jambs</label>
                                            <input name="accousticsJambs" id="accousticsJambs" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="accousticsHead" class="">Accoustics Head</label>
                                            <input name="accousticsHead" id="accousticsHead" class="form-control"
                                                type="text">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="thresholdSeal" class="">Threshold Seal</label>
                                            <input name="thresholdSeal" id="thresholdSeal" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="accousticsSeal" class="">Accoustics Seal</label>
                                            <input name="accousticsSeal" id="accousticsSeal" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="meetingStiles" class="">Meeting Stiles</label>
                                            <input name="meetingStiles" id="meetingStiles" class="form-control"
                                                type="text">

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glassType" class="">Glass Type</label>
                                            <input name="glassType" id="glassType" class="form-control" type="text">
                                        </div>
                                    </div>





                                </div>
                            </div>
                        </div>
                    </div>
                </div>






                <div class="main-card mb-3 custom_card">
                    <div class="">
                        <div class="tab-content">
                            <div class="card-header">
                                <h5 class="card-title" style="margin-top: 10px">ARCHITRAVE </h5>
                            </div>
                            <div class="">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="architraveMaterial" class="">Architrave Material</label>
                                            <select name="architraveMaterial" id="architraveMaterial"
                                                class="form-control">
                                                <option value="">Select Architrave Material</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='Architrave_Material')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="architraveType" class="">Architrave Type</label>
                                            <select name="architraveType" id="architraveType" class="form-control">
                                                <option value="">Select Architrave Type</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='Architrave_Type')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="architraveWidth" class="">Architrave Width</label>
                                            <input name="architraveWidth" id="architraveWidth"
                                                placeholder="Architrave Width" class="form-control" type="text">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="architraveDepth" class="">Architrave Depth</label>
                                            <input name="architraveDepth" id="architraveDepth"
                                                placeholder="Architrave Depth" class="form-control" type="text">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="architraveFinish" class="">Architrave Finish</label>
                                            <select name="architraveFinish" id="architraveFinish" class="form-control">
                                                <option value="">Select Architrave Finish</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='Architrave_Finish')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="architraveSetQty" class="">Architrave Set Qty</label>
                                            <input name="architraveSetQty" id="architraveSetQty"
                                                placeholder="Architrave Set Qty" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-3">
                        <div class="position-relative form-group">
                        <label for="ironmongeryFinish" class="">Ironmongery Finish</label>
                       <input type="text" name="ironmongeryFinish" id="ironmongeryFinish" placeholder="Ironmongery Finish" class="form-control">
                        </div>
                    </div> -->




                                    <!-- <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="lockPositionHeight" class="">Lock Position Height</label>
                            <input type="text" name="lockPositionHeight" placeholder="Lock Position Height" id="lockPositionHeight" class="form-control">
                        </div>
                    </div> -->

                                </div>


                            </div>
                        </div>
                    </div>

                </div>


            </div>
            <div class="main-card mb-3 custom_card">
                <div class="">
                    <div class="tab-content">
                        <div class="card-header">
                            <h5 class="card-title" style="margin-top: 10px">Fitting Hardware/Ironmongery </h5>
                        </div>
                        <div class="">
                            <div class="form-row">
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="Hinges" class="">Hinges</label>
                                        <input type="text" name="hinges" id="hinges" class="form-control">

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="floorSpring" class="">Floor Spring</label>
                                        <input type="text" name="floorSpring" id="floorSpring" class="form-control">

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="lockesAndLatches" class="">Locks And Latches</label>

                                        <input type="text" name="lockesAndLatches" id="lockesAndLatches"
                                            class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="flushBolts" class="">Flush Bolts</label>
                                        <input type="text" name="flushBolts" id="flushBolts" class="form-control">

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="concealedOverheadCloser" class="">Concealed Overhead Closer</label>
                                        <input type="text" name="concealedOverheadCloser" id="concealedOverheadCloser"
                                            class="form-control">

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="pullHandles" class="">Pull Handles</label>
                                        <input type="text" name="pullHandles" id="pullHandles" class="form-control">

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="pushHandles" class="">Push Plates</label>
                                        <input type="text" name="pushHandles" id="pushHandles" class="form-control">

                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="KickPlates" class="">Kick Plates</label>
                                        <input type="text" name="KickPlates" id="KickPlates" class="form-control">

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="doorSelectors" class="">Door Selectors</label>
                                        <input type="text" name="doorSelectors" id="doorSelectors" class="form-control">

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="panicHardware" class="">Panic Hardware</label>
                                        <input type="text" name="panicHardware" id="panicHardware" class="form-control">

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="doorSecurityViewer" class="">Door security viewer</label>
                                        <input type="text" name="doorSecurityViewer" id="doorSecurityViewer"
                                            class="form-control">

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="morticedDropDownSeals" class="">Morticed drop down seals</label>
                                        <input type="text" name="morticedDropDownSeals" id="morticedDropDownSeals"
                                            class="form-control">

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="faceFixedDropSeals" class="">Face fixed drop seals</label>
                                        <input type="text" name="faceFixedDropSeals" id="faceFixedDropSeals"
                                            class="form-control">

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="airTransferGrill" class="">Air Transfer Grill</label>
                                        <select name="airTransferGrill" id="airTransferGrill" class="form-control">
                                            <option value="6">6.0</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="letterPlates" class="">Letterplates</label>
                                        <select name="letterPlates" id="letterPlates" class="form-control">
                                            <option value="6">6.0</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="cableWays" class="">Cable Ways</label>
                                        <input type="text" name="cableWays" id="cableWays" class="form-control">

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="safeHinge" class="">Safe Hinge</label>
                                        <input type="text" name="safeHinge" id="safeHinge" class="form-control">

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="leverHandle" class="">Lever Handle</label>
                                        <input type="text" name="leverHandle" id="leverHandle" class="form-control">

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="doorSinage" class="">Door Sinage</label>
                                        <input type="text" name="doorSinage" id="doorSinage" class="form-control">

                                    </div>
                                </div>


                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="faceFixedDoorCloser" class="">Face Fixed Door Closer</label>
                                        <input type="text" name="faceFixedDoorCloser" id="faceFixedDoorCloser"
                                            class="form-control">

                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="thumbturn" class="">Thumbturn</label>
                                        <input type="text" name="thumbturn" id="thumbturn" class="form-control">

                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="keyholeEscutchen" class="">Keyhole Escutchen</label>
                                        <input type="text" name="keyholeEscutchen" id="keyholeEscutchen"
                                            class="form-control">

                                    </div>
                                </div>



                            </div>
                        </div>

                    </div>


                </div>
            </div>
            <div class="main-card mb-3 custom_card">
                <div class="">
                    <div class="tab-content">
                        <div class="card-header">
                            <h5 class="card-title" style="margin-top: 10px">Transport </h5>
                        </div>
                        <div class="">
                            <div class="form-row">


                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="Vehicle Type" class="">Vehicle Type</label>
                                        <select name="vehicleType" id="vehicleType" class="form-control">
                                            <option value="Curtainsider">Curtainsider</option>
                                            <option value="Opentop">Opentop</option>
                                            <option value="Tail Lift">Tail Lift</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="deliveryTime" class="">Delivery Time</label>
                                        <select name="deliveryTime" id="deliveryTime" class="form-control">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="packaging" class="">Packaging</label>
                                        <select name="packaging" id="packaging" class="form-control">
                                            <option value="cardbord">Cardbord</option>
                                            <option value="corryboard">Corryboard</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="d-block text-right card-footer">
                    <button type="submit" id="submit" class="btn-wide btn btn-success" style="margin-right: 20px">
                        Submit Now
                    </button>
                </div>

            </div>

            <div hidden id="glazing-system-filter">{{route('items/glazing-system-filter')}}</div>
            <div hidden id="fire-rating-filter">{{route('items/fire-rating-filter')}}</div>
            <div hidden id="glazing-beads-filter">{{route('items/glazing-beads-filter')}}</div>
            <div hidden id="glass-type-filter">{{route('items/glass-type-filter')}}</div>
            <div hidden id="glazing-thikness-filter">{{route('items/glazing-thikness-filter')}}</div>
            <div hidden id="frame-material-filter">{{route('items/frame-material-filter')}}</div>
            <div hidden id="scallopped-lipping-thickness">{{route('items/scallopped-lipping-thickness')}}</div>
            <div hidden id="flat-lipping-thickness">{{route('items/flat-lipping-thickness')}}</div>
            <div hidden id="rebated-lipping-thickness">{{route('items/rebated-lipping-thickness')}}</div>
            <div hidden id="door-thickness-filter">{{route('items/door-thickness-filter')}}</div>
            <div hidden id="door-leaf-face-value-filter">{{route('items/door-leaf-face-value-filter')}}</div>
            <div hidden id="ral-color-filter">{{route('items/ral-color-filter')}}</div>


        </form>
    </div>

</div>
@endsection
