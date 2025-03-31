@extends("layouts.Master")

@section("main_section")
<div class="app-main__outer">
<div class="app-main__inner">
      <div class="tab-content">
      <div class="main-card mb-3 card">
      <div class="card-header">
      <h5 class="card-title" style="margin-top: 10px">Company Details</h5>
      </div>
      <div class="card-body">
      <div class="tab-content">
      <div class="card-body">
      <div class="form-row">
      <div class="col-md-6">
      <label for="leafConstruction" >Select Company</label>
      <select required name="companyName" id="companyName" class="form-control">
      @if(!empty($company_list) && count($company_list))
      <option value="">Select Company</option>
      @foreach($company_list as $row)
      <option value="{{$row->UserId}}">{{$row->CompanyName}}</option>
      @endforeach
      @else
      <option value="">No company listed</option>
      @endif
      </select>
      </div>
      <div class="col-md-6">
      <label for="filename" >Filename</label>
      <input type="text" name="CompanyFileName" id="CompanyFileName" required class="form-control">

      </diV>

      </div>
      </div>
      </div>
      </div>
      </div>
      </div>

<div id="form-gentate">
    <form id="itemForm" enctype="multipart/form-data" method="post" action="{{route('form/store-filename')}}" >
    <div class="tab-content">
    <div class="main-card mb-3 card">
       <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">


        <input type="hidden" name="mark" value="@if(isset($schedule)){{$schedule->Mark}} @else {{''}}  @endif">
        <input type="hidden" name="type" value="@if(isset($schedule)) {{$schedule->Type}} @else {{''}}  @endif">
         <input type="hidden" name="QuotationId" value="@if(isset($schedule)) {{$schedule->QuotationId}} @else {{''}}  @endif">
         <input type="hidden" name="itemID" value="@if(isset($schedule)) {{$schedule->id}} @else {{''}}  @endif">

                    @if(isset($editdata->id))
                    <input type="hidden" name="update" value="{{ $editdata->id }}">
                    @endif
                    <div class="card-header">
                        <h5 class="card-title" style="margin-top: 10px">Main Options</h5>
                    </div>
                    <div class="card-body">
                        <div class="tab-content">
                    <div class="card-body">
                    <div class="form-row">
                    <!-- <div class="col-md-3">
                        <div class="position-relative form-group">
                        <label for="Company Name" class="">Company Name</label>
                        <input name="CompanyName" required placeholder="Enter Company Name" value="@if(isset($editdata->id)) {{$editdata->CompanyName}} @endif" type="text" class="form-control"></div>
                    </div> -->
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="leafConstruction" >Leaf Construction</label>
                                <select required name="leafConstruction" id="leafConstruction" class="form-control">
                                    @foreach($option_data as $row)
                                    @if($row->OptionSlug=='leaf_construction')
                                      <option value="{{$row->OptionKey}}"  >{{$row->OptionValue}}</option>
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
                            <input name="doorType" id="doorType"  required placeholder="Enter door type" type="text" value="@if(isset($schedule)){{$schedule->Type}}@else{{''}}@endif" class="form-control"></div>
                        </div>
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="DoorNo" class="">Door No.</label>
                                <input name="DoorNo" id="DoorNo"  required placeholder="Enter door number" type="text" value="@if(isset($schedule)){{$schedule->Mark}}@else{{''}}@endif" class="form-control">
                            </div>
                        </div>

                          <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="fireRating" class="">Fire Rating</label>
                                <select required name="fireRating" id="fireRating" class="form-control">
                                <option value="">Select fire rating</option>
                                     @foreach($option_data as $row)
                                    @if($row->OptionSlug=='fire_rating')
                                      <option value="{{$row->OptionKey}}" @if(isset($schedule)) @if($schedule->FireRating==$row->OptionKey){{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                                    @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="doorsetType" class="">Doorset Type <span class="dsl"></span></label>
                                <!-- combination_of -->
                                <select required name="doorsetType" id="doorsetType" class="form-control  change-event-calulation">

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
                                <select required name="swingType" id="swingType" class="form-control combination_of">
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
                                <select required name="latchType" id="latchType" class="form-control combination_of">
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
                                <select required name="overpanel" id="overpanel" class="form-control change-event-calulation">

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
                        <select name="OPLippingThickness" id="OPLippingThickness" class="form-control">
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
                        <input name="oPWidth"  id="oPWidth" readonly class="form-control oPWidth" type="text">
                      </div>
                   </div>

                   <div class="col-md-3">
                      <div class="position-relative form-group">
                        <label for="oPHeigth" class="">OP Height (Max-value:600)</label>
                        <input name="oPHeigth" id="oPHeigth" max="600" class="form-control" type="number">
                      </div>
                   </div>
                   <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="opTransom" class="">OP Transom</label>
                            <select id="opTransom"  name="opTransom" class="form-control">
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
                            <label for="opGlassType" class="">OP Glass Type</label>
                            <select name="opGlassType" id="opGlassType" class="form-control">
                              <option value="">Select OP Glass Type</option>

                            </select>
                            </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="opGlazingBeads" class="">OP Glazing Beads</label>
                            <select name="opGlazingBeads" id="opGlazingBeads" class="form-control">
                            <option value="">Select Glazing Beads</option>

                            </select>
                            </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="opGlazingBeadSpecies" class="">OP Glazing Bead Species</label>
                            <select name="opGlazingBeadSpecies" id="opGlazingBeadSpecies" class="form-control">
                            <option value="">Select Species</option>

                            </select>
                        </div>
                    </div>


                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="doorQuantity" class="">Door Quantity</label>
                                <input type="number"  id="doorQuantity" value="1" name="doorQuantity" class="form-control">
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
                                <input type="number" id="tollerance" name="tollerance" class="form-control for_c_leaf_height  forleafHeightNoOP change-event-calulation">
                                </div>
                        </div>
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="undercut" class="">Undercut</label>
                                <input type="number" id="undercut" readonly name="undercut" class="form-control change-event-calulation  undercut">

                                </div>
                        </div>
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="floorFinish" class="">Floor Finish</label>
                                <input type="number" id="floorFinish" name="floorFinish" class="form-control change-event-calulation forundercut">

                                </div>
                        </div>


                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="gap" class="">GAP</label>
                                <input type="number" id="gap" name="gap" class="form-control for_c_leaf_height  forleafHeightNoOP change-event-calulation">

                                </div>
                        </div>
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="frameThickness" class="">Frame Thickness</label>
                                <!-- <input type="number" id="frameThickness" name="frameThickness" class="form-control for_c_leaf_height  forleafHeightNoOP change-event-calulation"> -->
                                <select name="frameThickness" required class="form-control change-event-calulation"  id="frameThickness"  >
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




                <div class="main-card mb-3 card">
                <div class="card-header">
                    <h5 class="card-title" style="margin-top: 10px">DOOR DIMENSIONS & DOOR LEAF</h5><span></span>
                </div>
                <div class="card-body">
                    <div class="tab-content">
                <div class="card-body">
                   <div class="form-row">
                   <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="sOWidth" class="">S.O Width</label>
                                <input type="number" id="sOWidth" name="sOWidth" class="form-control  change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="sOHeight" class="">S.O Height</label>
                                <input type="number" id="sOHeight" name="sOHeight" class="form-control change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="sODepth" class="">S.O Depth</label>
                                <input type="number" id="sODepth" name="sODepth" class="form-control change-event-calulation ">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="leafWidth1" class="">Leaf Width 1</label>
                                <input type="number" readonly id="leafWidth1" name="leafWidth1" readonly class="form-control change-event-calulation forcoreWidth1">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="leafWidth2" class="">Leaf Width 2</label>
                                <input type="number" readonly id="leafWidth2" name="leafWidth2" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="leafHeightNoOP" class="">Leaf Height</label>
                                <input type="number" id="leafHeightNoOP" readonly name="leafHeightNoOP" class="form-control leafHeightNoOP foroPWidth">
                            </div>
                        </div>
                        <div class="col-md-3">
                        <div class="position-relative form-group">
                        <label for="doorThickness" class="">Door Thickness (mm)</label>
                        <input type="number" readonly  name="doorThickness" id="doorThickness" class="form-control">
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
                                  <option value="{{$row->OptionKey}}" @if(isset($schedule)) @if($schedule->DoorFinish==$row->OptionKey){{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                                @endif
                                @endforeach
                        </select>
                      </div>
                   </div>
                   <div class="col-md-3">
                      <div class="position-relative form-group">
                        <label for="doorLeafFacingValue" class="">Brand</label>
                          <select name="doorLeafFacingValue" id="doorLeafFacingValue" class="form-control">
                            <option value="">Select Brand</option>

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
                          <select name="doorLeafFinishColor" id="doorLeafFinishColor" class="form-control">
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

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="tab-content">
                          <div class="card-header">
                    <h5 class="card-title" style="margin-top: 10px">VISION PANEL</h5>
                </div>
                <div class="card-body">
                 <div class="form-row">


                 <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="leaf1VisionPanel" class="">Leaf 1 Vision Panel</label>
                            <select required name="leaf1VisionPanel" id="leaf1VisionPanel" class="form-control change-event-calulation">
                            <option val=""> Is Vision Panel active? </option>
                                @foreach($option_data as $row)
                                @if($row->OptionSlug=='leaf1_vision_panel')
                                  <option value="{{$row->OptionKey}}" @if(isset($schedule)) @if($schedule->VisionPanel==$row->OptionKey){{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="visionPanelQuantity" class="">Vision Panel Quantity</label>
                            <select  name="visionPanelQuantity" id="visionPanelQuantity"  disabled class="form-control change-event-calulation">
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
                            <select  name="AreVPsEqualSizes" id="AreVPsEqualSizes" disabled class="form-control change-event-calulation">
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
                            <label for="distanceFromTopOfDoor" class="">Distance from top of door</label>
                            <input required Type="number" readonly min="100" name="distanceFromTopOfDoor" id="distanceFromTopOfDoor" class="form-control">
                            </div>
                    </div>

                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="distanceBetweenVPs" class="">Distance between VP's</label>
                            <input Type="number" min="80" readonly name="distanceBetweenVPs" id="distanceBetweenVPs" class="form-control">
                            </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="vP1Width" class="">Leaf 1 VP Width</label>
                            <input Type="number" min="0" readonly  name="vP1Width" id="vP1Width" class="form-control change-event-calulation">
                            </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="vP1Height1" class="">Leaf 1 VP Height (1)</label>
                            <input Type="number" min="0" readonly name="vP1Height1" id="vP1Height1" class="form-control change-event-calulation">
                            </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="vP1Height2" class="">Leaf 1 VP Height (2)</label>
                            <input Type="number" min="0"  readonly name="vP1Height2" id="vP1Height2" class="form-control change-event-calulation">
                            </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="vP1Height3" class="">Leaf 1 VP Height (3)</label>
                            <input Type="number" min="0" readonly name="vP1Height3" id="vP1Height3" class="form-control change-event-calulation">
                            </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="vP1Height4" class="">Leaf 1 VP Height (4)</label>
                            <input Type="number" min="0" readonly name="vP1Height4" id="vP1Height4" class="form-control change-event-calulation">
                            </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="vP1Height5" class="">Leaf 1 VP Height (5)</label>
                            <input Type="number" min="0" readonly name="vP1Height5" id="vP1Height5" class="form-control change-event-calulation">
                            </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="leaf1VpAreaSizeM2" class=""> Leaf 1 VP Area Size m2</label>
                            <input Type="number" min="0" readonly name="leaf1VpAreaSizeM2" id="leaf1VpAreaSizeM2" class="form-control">
                            </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="leaf2VisionPanel" class="">Leaf 2 Vision Panel</label>
                            <select disabled name="leaf2VisionPanel" id="leaf2VisionPanel" class="form-control">
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
                            <select  name="vpSameAsLeaf1" id="vpSameAsLeaf1" disabled class="form-control">
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
                            <label for="visionPanelQuantityforLeaf2" class="">Leaf 2 Vision Panel Quantity</label>
                            <select  name="visionPanelQuantityforLeaf2" id="visionPanelQuantityforLeaf2"  disabled class="form-control">
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
                            <label for="AreVPsEqualSizesForLeaf2" class="">Are VP's equal sizes for leaf 2?</label>
                            <select  name="AreVPsEqualSizesForLeaf2" id="AreVPsEqualSizesForLeaf2" disabled class="form-control">
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
                            <label for="distanceFromTopOfDoorforLeaf2" class="">Distance from top of door for Leaf 2</label>
                            <input required Type="number" readonly min="100" name="distanceFromTopOfDoorforLeaf2" id="distanceFromTopOfDoorforLeaf2" class="form-control">
                            </div>
                    </div>

                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="distanceBetweenVPsforLeaf2" class="">Distance between VP's</label>
                            <input Type="number" min="80" readonly name="distanceBetweenVPsforLeaf2" id="distanceBetweenVPsforLeaf2" class="form-control">
                            </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="vP2Width" class="">Leaf 2 VP Width</label>
                            <input Type="number" min="0" readonly  name="vP2Width" id="vP2Width" class="form-control">
                            </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="vP2Height1" class="">Leaf 2 VP Height (1)</label>
                            <input Type="number" min="0" readonly name="vP2Height1" id="vP2Height1" class="form-control">
                            </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="vP2Height2" class="">Leaf 2 VP Height (2)</label>
                            <input Type="number" min="0"  readonly name="vP2Height2" id="vP2Height2" class="form-control">
                            </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="vP2Height3" class="">Leaf 2 VP Height (3)</label>
                            <input Type="number" min="0" readonly name="vP2Height3" id="vP2Height3" class="form-control">
                            </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="vP2Height4" class="">Leaf 2 VP Height (4)</label>
                            <input Type="number" min="0" readonly name="vP2Height4" id="vP2Height4" class="form-control">
                            </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="vP2Height5" class="">Leaf 2 VP Height (5)</label>
                            <input Type="number" min="0" readonly name="vP2Height5" id="vP2Height5" class="form-control">
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
                            <label for="lazingIntegrityOrInsulationIntegrity" class="">Glass Integrity</label>

                            <select name="lazingIntegrityOrInsulationIntegrity" id="lazingIntegrityOrInsulationIntegrity" class="form-control" >
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
                            <input type="text" readonly name="glassThickness" id="glassThickness" class="form-control">
                            <!-- <select name="glassThickness" id="glassThickness" class="form-control"> -->
                            <!-- <option value=""> Select glass thickness</option> -->
                                  <!-- @foreach($option_data as $row)
                                @if($row->OptionSlug=='leaf1_glass_thickness')
                                  <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                @endif
                                @endforeach -->

                            <!-- </select> -->
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
                            <label for="glazingSystemsThickness" class="">Glazing System Thickness</label>
                            <input type="text" readonly name="glazingSystemsThickness" id="glazingSystemsThickness" class="form-control">
                            <!-- <select name="glazingSystemsThickness" id="glazingSystemsThickness" class="form-control">
                                  <option value="">Select Glazing Thickness</option>
                           </select> -->
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
                            <input Type="number" min="0" name="glazingBeadsThickness" id="glazingBeadsThickness" class="form-control">
                            </div>
                    </div>

                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="glazingBeadSpecies" class="">Glazing Bead Species</label>
                            <select name="glazingBeadSpecies" id="glazingBeadSpecies" class="form-control">
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
    <div class="main-card mb-3 card">
                 <div class="card-body">
                    <div class="tab-content">
                          <div class="card-header">
                    <h5 class="card-title" style="margin-top: 10px">Frame </h5>
                </div>
                <div class="card-body">
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
                            <input type="number" name="frameTypeDimensions" value="0" min="1" readonly id="frameTypeDimensions"  class="form-control" >

                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="frameWidth" class="">Frame Width</label>
                             <input type="text" readonly name="frameWidth" id="frameWidth" placeholder="Frame Width" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="frameHeight" class="">Frame Height</label>
                            <input type="text" readonly name="frameHeight" placeholder="Frame Height" id="frameHeight" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="frameDepth" class="">Frame Depth</label>
                            <input type="text" readonly name="frameDepth" id="frameDepth" placeholder="Frame Depth" class="form-control">
                      </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="frameThickness1" class="">Frame Thickness</label>
                             <input name="frameThickness1" required placeholder="Frame Thickness" id="frameThickness1" min="0" type="text" class="form-control">

                      </div>
                    </div>

                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="frameFinish" class="">Frame Finish</label>
                            <select name="frameFinish" id="frameFinish" class="form-control change-event-calulation">
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
                        <select  name="extLiner" id="extLiner"  class="form-control change-event-calulation">
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
                        <input type="text" readonly value="0" name="extLinerValue" id="extLinerValue" class="form-control">


                      </div>
                   </div>
                   <div class="col-md-3">
                      <div class="position-relative form-group">
                        <label for="extLinerSize" class="">Ext-Liner Size</label>
                        <input name="extLinerSize" id="extLinerSize" placeholder="Ext-Liner Size" class="form-control" type="text">
                      </div>
                   </div>
                   <div class="col-md-3">
                      <div class="position-relative form-group">
                        <label for="extLinerThickness" class="">Ext-Liner Thickness</label>
                        <input name="extLinerThickness" id="extLinerThickness" placeholder="Ext-Liner Thickness" class="form-control change-event-calulation" type="text">
                      </div>
                   </div>
                   <div class="col-md-3">
                      <div class="position-relative form-group">
                        <label for="extLinerFinish" class="">Ext-Liner FInish</label>
                        <input name="extLinerFinish" id="extLinerFinish" readonly placeholder="Ext-Liner Finish" class="form-control" type="text">
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
                       <select name="intumescentSealColor" id="intumescentSealColor" class="form-control">
                                  <option value="White">White</option>
                                  <option value="brown">brown</option>
                                  <option value="black">black</option>
                        </select>
                      </div>
                   </div>
                   <div class="col-md-3">
                      <div class="position-relative form-group">
                        <label for="intumescentSealSize" class="">Intumescent Seal Size</label>
                        <select name="intumescentSealSize" id="intumescentSealSize" class="form-control">
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
                          <input name="specialFeatureRefs" id="specialFeatureRefs" placeholder="Special Feature Refs" class="form-control" type="text">
                      </div>
                   </div>

                </div>
                </div>
            </div>
        </div>
</div>


              <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="tab-content">
                          <div class="card-header">
                    <h5 class="card-title" style="margin-top: 10px">SIDE LIGHT</h5>
                </div>
                <div class="card-body">
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
                            <select name="sideLight1GlassType" disabled id="sideLight1GlassType" class="form-control">
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
                        <select name="SideLight1BeadingType" id="SideLight1BeadingType" disabled class="form-control">
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
                        <label for="SideLight1GlazingBeadSpecies" class="">Glazing Bead Species</label>
                        <select name="SideLight1GlazingBeadSpecies" id="SideLight1GlazingBeadSpecies" disabled class="form-control">
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
                        <input name="SL1Width"  readonly id="SL1Width" class="form-control" type="text">
                      </div>
                   </div>
                   <div class="col-md-3">
                      <div class="position-relative form-group">
                        <label for="SL1Height" readonly class="">SL1 Height</label>
                        <input name="SL1Height" readonly id="SL1Height" class="form-control" type="text">
                      </div>
                   </div>
                   <div class="col-md-3">
                      <div class="position-relative form-group">
                        <label for="SL1Depth" class="">SL1 Depth</label>
                        <input name="SL1Depth" readonly id="SL1Depth" class="form-control" type="text">
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
                        <label for="copyOfSideLite1" class="">Do you want to copy Same as SL1?</label>
                        <select name="copyOfSideLite1" id="copyOfSideLite1" class="form-control" disabled>
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
                            <select name="sideLight2GlassType" disabled id="sideLight2GlassType" class="form-control">
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
                        <label for="SideLight2BeadingType" class="">Side Light 2 Beading Type</label>
                        <select name="SideLight2BeadingType" id="SideLight2BeadingType" disabled class="form-control">
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
                        <label for="SideLight2GlazingBeadSpecies" class="">Side Light 2 Glazing Bead Species</label>
                        <select name="SideLight2GlazingBeadSpecies" id="SideLight2GlazingBeadSpecies" disabled class="form-control">
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
                        <input name="SL2Width" readonly id="SL2Width" class="form-control" type="text">
                      </div>
                   </div>
                   <div class="col-md-3">
                      <div class="position-relative form-group">
                        <label for="SL2Height" class="">SL2 Height</label>
                        <input name="SL2Height" readonly id="SL2Height" class="form-control" type="text">
                      </div>
                   </div>
                   <div class="col-md-3">
                      <div class="position-relative form-group">
                        <label for="SL2Depth" class="">SL2 Depth</label>
                        <input name="SL2Depth" readonly id="SL2Depth" class="form-control" type="text">
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
    <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="tab-content">
                          <div class="card-header">
                    <h5 class="card-title" style="margin-top: 10px">LIPPING AND INTUMESCENT </h5>
                </div>
                <div class="card-body">
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
                    <select name="lippingThickness" id="lippingThickness" class="form-control forcoreWidth1">
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
                            <label for="scallopedLippingThickness" class="">Scalloped Lipping Thickness</label>
                            <select name="scallopedLippingThickness" disabled id="scallopedLippingThickness" class="form-control">
                              <option value="">Select Scalloped Lipping Thickness</option>

                        </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="flatLippingThickness" class="">Flat Lipping Thickness</label>
                            <select name="flatLippingThickness" disabled id="flatLippingThickness" class="form-control">
                              <option value="">Select Flat Lipping Thickness</option>

                        </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="rebatedLippingThickness" class="">Rebated Lipping Thickness</label>
                            <select name="rebatedLippingThickness" disabled id="rebatedLippingThickness" class="form-control">
                              <option value="">Select Rebated Lipping Thickness</option>

                        </select>
                        </div>
                    </div>
                    <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="coreWidth1" class="">Core Width 1</label>
                                <input type="number" min="1" readonly id="coreWidth1" name="coreWidth1" class="form-control coreWidth1">
                            </div>
                        </div>

                    <div class="col-md-3">
                        <div class="position-relative form-group">
                        <label for="intumescentSealType" class="">Intumescent Seal Type</label>
                        <select name="intumescentSealType" id="intumescentSealType" class="form-control">
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
                            <label for="intumescentSealLocation" class="">Intumescent Seal Location</label>
                            <select name="intumescentSealLocation" id="intumescentSealLocation" class="form-control">
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
                            <select name="intumescentSealColor" id="intumescentSealColor" class="form-control">
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
                            <label for="intumescentSealArrangement" class="">Intumescent Seal Arrangement</label>
                            <input type="text" name="intumescentSealArrangement" id="intumescentSealArrangement" class="form-control">
                        </div>
                    </div>








                </div>
                </div>
    </div>
</div>
</div>

<div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="tab-content">
                          <div class="card-header">
                    <h5 class="card-title" style="margin-top: 10px">ACCOUSTICS </h5>
                </div>
                <div class="card-body">
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
                        <input name="accousticsJambs" id="accousticsJambs" class="form-control" type="text">
                      </div>
                   </div>
                   <div class="col-md-3">
                      <div class="position-relative form-group">
                        <label for="accousticsHead" class="">Accoustics Head</label>
                         <input name="accousticsHead" id="accousticsHead" class="form-control" type="text">
                      </div>
                   </div>

                   <div class="col-md-3">
                      <div class="position-relative form-group">
                        <label for="thresholdSeal" class="">Threshold Seal</label>
                        <input name="thresholdSeal" id="thresholdSeal" class="form-control" type="text">
                      </div>
                   </div>
                   <div class="col-md-3">
                      <div class="position-relative form-group">
                        <label for="accousticsSeal" class="">Accoustics Seal</label>
                        <input name="accousticsSeal" id="accousticsSeal" class="form-control" type="text">
                      </div>
                   </div>
                   <div class="col-md-3">
                      <div class="position-relative form-group">
                        <label for="accousticsmeetingStiles" class="">Meeting Stiles</label>
                        <input name="accousticsmeetingStiles" id="meetingStiles" class="form-control" type="text">

                      </div>
                   </div>
                   <div class="col-md-3">
                      <div class="position-relative form-group">
                        <label for="accousticsglassType" class="">Glass Type</label>
                        <input name="accousticsglassType" id="glassType" class="form-control" type="text">
                      </div>
                   </div>





                </div>
                </div>
    </div>
</div>
</div>






<div class="main-card mb-3 card">
                 <div class="card-body">
                    <div class="tab-content">
                          <div class="card-header">
                    <h5 class="card-title" style="margin-top: 10px">ARCHITRAVE </h5>
                </div>
                <div class="card-body">
                 <div class="form-row">
                 <div class="col-md-3">
                      <div class="position-relative form-group">
                        <label for="architraveMaterial" class="">Architrave Material</label>
                       <select name="architraveMaterial" id="architraveMaterial" class="form-control">
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
                          <input name="architraveWidth" id="architraveWidth" placeholder="Architrave Width" class="form-control" type="text">
                      </div>
                   </div>

                   <div class="col-md-3">
                      <div class="position-relative form-group">
                        <label for="architraveDepth" class="">Architrave Depth</label>
                          <input name="architraveDepth" id="architraveDepth" placeholder="Architrave Depth" class="form-control" type="text">
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
                          <input name="architraveSetQty" id="architraveSetQty" placeholder="Architrave Set Qty" class="form-control" type="text">
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
<div class="main-card mb-3 card">
                 <div class="card-body">
                    <div class="tab-content">
                          <div class="card-header">
                    <h5 class="card-title" style="margin-top: 10px">Fitting Hardware/Ironmongery </h5>
                    <input type="hidden"  id="ironIronmongerydata">
                </div>
                <div class="card-body">
                 <div class="form-row">
                 <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="HingesKey" class="">Hinges</label>
                            <div class="input-icons">
                            <i class="fa fa-info icon" onClick="IronMongery('Hinges','Hinges')"></i>
                            <input type="hidden" name="hingesValue" id="HingesValue">
                            <input type="text"  readonly name="hingesKey" id="HingesKey"  class="form-control" >
                          </div>


                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="FloorSpringKey" class="">Floor Spring</label>
                          <div class="input-icons">
                              <i class="fa fa-info icon" onClick="IronMongery('FloorSpring','Floor Spring')"></i>
                              <input type="hidden" name="floorSpringValue" id="FloorSpringValue">
                              <input type="text"  readonly name="floorSpringKey" id="FloorSpringKey"  class="form-control" >
                          </div>


                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="lockesAndLatchesKey" class="">Locks And Latches</label>
                          <div class="input-icons">
                              <i class="fa fa-info icon" onClick="IronMongery('LocksandLatches','Locks And Latches')"></i>
                              <input type="hidden" name="lockesAndLatchesValue" id="LocksandLatchesValue">
                              <input type="text"  readonly name="lockesAndLatchesKey" id="LocksandLatchesKey"  class="form-control" >
                          </div>


                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="flushBoltsKey" class="">Flush Bolts</label>
                            <div class="input-icons">
                              <i class="fa fa-info icon" onClick="IronMongery('FlushBolts','Flush Bolts')"></i>
                              <input type="hidden" name="flushBoltsValue" id="FlushBoltsValue">
                              <input type="text"  readonly name="flushBoltsKey" id="FlushBoltsKey"  class="form-control" >
                          </div>


                      </div>
                </div>

                <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="concealedOverheadCloserKey" class="">Concealed Overhead Closer</label>
                            <div class="input-icons">
                              <i class="fa fa-info icon" onClick="IronMongery('OverheadClosers','Concealed Overhead Closer')"></i>
                              <input type="hidden" name="concealedOverheadCloserValue" id="OverheadClosersValue">
                              <input type="text"  readonly name="concealedOverheadCloserKey" id="OverheadClosersKey"  class="form-control" >
                          </div>

                      </div>
                </div>
                <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="pullHandlesKey" class="">Pull Handles</label>
                          <div class="input-icons">
                              <i class="fa fa-info icon" onClick="IronMongery('PullHandles','Pull Handles')"></i>
                              <input type="hidden" name="pullHandlesValue" id="PullHandlesValue">
                              <input type="text"  readonly name="pullHandlesKey" id="PullHandlesKey"  class="form-control" >
                          </div>
                      </div>
                </div>
                <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="pushHandlesValue" class="">Push Plates</label>
                            <div class="input-icons">
                              <i class="fa fa-info icon" onClick="IronMongery('PushHandles','Push Handles')"></i>
                              <input type="hidden" name="pushHandlesValue" id="PushHandlesValue">
                              <input type="text"  readonly name="pushHandlesKey" id="PushHandlesKey"  class="form-control" >
                          </div>


                      </div>
                </div>


                <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="kickPlates" class="">Kick Plates</label>
                            <div class="input-icons">
                              <i class="fa fa-info icon" onClick="IronMongery('KickPlates','Kick Plates')"></i>
                              <input type="hidden" name="kickPlatesValue" id="KickPlatesValue">
                              <input type="text"  readonly name="kickPlatesKey" id="KickPlatesKey"  class="form-control" >
                          </div>


                      </div>
                </div>

                <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="doorSelectorsKey" class="">Door Selectors</label>
                            <div class="input-icons">
                              <i class="fa fa-info icon" onClick="IronMongery('DoorSelectors','Door Selectors')"></i>
                              <input type="hidden" name="doorSelectorsValue" id="DoorSelectorsValue">
                              <input type="text"  readonly name="doorSelectorsKey" id="DoorSelectorsKey"  class="form-control" >
                          </div>


                      </div>
                </div>

                <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="panicHardwareKey" class="">Panic Hardware</label>

                            <div class="input-icons">
                              <i class="fa fa-info icon" onClick="IronMongery('PanicHardware','Panic Hardware')"></i>
                              <input type="hidden" name="panicHardwareValue" id="PanicHardwareValue">
                              <input type="text"  readonly name="panicHardwareKey" id="PanicHardwareKey"  class="form-control" >
                          </div>


                      </div>
                </div>
                <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="doorSecurityViewerKey" class="">Door security viewer</label>
                            <div class="input-icons">
                              <i class="fa fa-info icon" onClick="IronMongery('Doorsecurityviewer','Door security viewer')"></i>
                              <input type="hidden" name="doorSecurityViewerValue" id="DoorsecurityviewerValue">
                              <input type="text"  readonly name="doorSecurityViewerKey" id="DoorsecurityviewerKey"  class="form-control" >
                          </div>

                      </div>
                </div>
                <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="morticeddropdownsealsKey" class="">Morticed drop down seals</label>
                            <div class="input-icons">
                              <i class="fa fa-info icon" onClick="IronMongery('Morticeddropdownseals','Morticed drop down seals')"></i>
                              <input type="hidden" name="morticeddropdownsealsValue" id="MorticeddropdownsealsValue">
                              <input type="text"  readonly name="morticeddropdownsealsKey" id="MorticeddropdownsealsKey"  class="form-control" >
                          </div>
                      </div>
                </div>
                <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="facefixeddropsealsKey" class="">Face fixed drop seals</label>
                            <div class="input-icons">
                              <i class="fa fa-info icon" onClick="IronMongery('Facefixeddropseals','Face fixed drop seals')"></i>
                              <input type="hidden" name="facefixeddropsealsValue" id="FacefixeddropsealsValue">
                              <input type="text"  readonly name="facefixeddropsealsKey" id="FacefixeddropsealsKey"  class="form-control" >
                          </div>

                      </div>
                </div>
                <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="thresholdSealKey" class="">Threshold Seal</label>
                            <div class="input-icons">
                              <i class="fa fa-info icon" onClick="IronMongery('ThresholdSeal','Threshold Seal')"></i>
                              <input type="hidden" name="thresholdSealValue" id="ThresholdSealValue">
                              <input type="text"  readonly name="thresholdSealKey" id="ThresholdSealKey"  class="form-control" >
                          </div>

                        </div>
                </div>
                <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="airtransfergrillsKey" class="">Air Transfer Grill</label>
                            <div class="input-icons">
                              <i class="fa fa-info icon" onClick="IronMongery('Airtransfergrills','Air Transfer Grill')"></i>
                              <input type="hidden" name="airtransfergrillsValue" id="AirtransfergrillsValue">
                              <input type="text"  readonly name="airtransfergrillsKey" id="AirtransfergrillsKey"  class="form-control" >
                          </div>

                        </div>
                </div>
                    <div class="col-md-3">
                      <div class="position-relative form-group">
                        <label for="letterplatesKey" class="">Letterplates</label>
                        <div class="input-icons">
                              <i class="fa fa-info icon" onClick="IronMongery('Letterplates','Letter plates')"></i>
                              <input type="hidden" name="letterplatesValue" id="LetterplatesValue">
                              <input type="text"  readonly name="letterplatesKey" id="LetterplatesKey"  class="form-control" >
                          </div>
                        </div>
                   </div>
                <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="cableWays" class="">Cable Ways</label>
                            <div class="input-icons">
                              <i class="fa fa-info icon" onClick="IronMongery('CableWays','Cable Ways')"></i>
                              <input type="hidden" name="cableWaysValue" id="CableWaysValue">
                              <input type="text"  readonly name="cableWaysKey" id="CableWaysKey"  class="form-control" >
                          </div>

                      </div>
                </div>
                <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="safeHingeKey" class="">Safe Hinge</label>
                            <div class="input-icons">
                              <i class="fa fa-info icon" onClick="IronMongery('SafeHinge','Safe Hinge')"></i>
                              <input type="hidden" name="safeHingeValue" id="SafeHingeValue">
                              <input type="text"  readonly name="safeHingeKey" id="SafeHingeKey"  class="form-control" >
                        </div>


                      </div>
                </div>
                <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="leverHandleKey" class="">Lever Handle</label>
                            <div class="input-icons">
                              <i class="fa fa-info icon" onClick="IronMongery('LeverHandle','Lever Handle')"></i>
                              <input type="hidden" name="leverHandleValue" id="LeverHandleValue">
                              <input type="text"  readonly name="leverHandleKey" id="LeverHandleKey"  class="form-control" >
                          </div>

                      </div>
                </div>
                <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="doorSignageKey" class="">Door Sinage</label>
                            <div class="input-icons">
                              <i class="fa fa-info icon" onClick="IronMongery('DoorSignage','Door Sinage')"></i>
                              <input type="hidden" name="doorSignageValue" id="DoorSignageValue">
                              <input type="text"  readonly name="doorSignageKey" id="DoorSignageKey"  class="form-control" >
                          </div>

                      </div>
                </div>


                <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="faceFixedDoorClosersKey" class="">Face Fixed Door Closer</label>
                            <div class="input-icons">
                              <i class="fa fa-info icon" onClick="IronMongery('FaceFixedDoorClosers','Face Fixed Door Closer')"></i>
                              <input type="hidden" name="faceFixedDoorClosersValue" id="FaceFixedDoorClosersValue">
                              <input type="text"  readonly name="faceFixedDoorClosersKey" id="FaceFixedDoorClosersKey"  class="form-control" >
                          </div>

                      </div>
                </div>
                <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="thumbturnKey" class="">Thumbturn</label>
                            <div class="input-icons">
                              <i class="fa fa-info icon" onClick="IronMongery('Thumbturn','Thumbturn')"></i>
                              <input type="hidden" name="thumbturnValue" id="ThumbturnValue">
                              <input type="text"  readonly name="thumbturnKey" id="ThumbturnKey"  class="form-control" >
                          </div>

                      </div>
                </div>
                <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="keyholeEscutcheonKey" class="">Keyhole Escutchen</label>
                            <div class="input-icons">
                              <i class="fa fa-info icon" onClick="IronMongery('KeyholeEscutcheon','Keyhole Escutchen')"></i>
                              <input type="hidden" name="keyholeEscutcheonValue" id="KeyholeEscutcheonValue">
                              <input type="text"  readonly name="keyholeEscutcheonKey" id="KeyholeEscutcheonKey"  class="form-control" >
                          </div>

                      </div>
                </div>





                </div>
    </div>

                </div>


</div>
</div>
<div class="main-card mb-3 card">
                 <div class="card-body">
                    <div class="tab-content">
                          <div class="card-header">
                    <h5 class="card-title" style="margin-top: 10px">Transport </h5>
                </div>
                <div class="card-body">
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
                    <button  type="submit"  hidden id="submit" class="btn-wide btn btn-success" style="margin-right: 20px">
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
<div hidden id="filter-iron-mongery-category">{{route('ironmongery-info/filter-iron-mongery-category')}}</div>
<div hidden id="url">{{url('/')}}</div>

</form>
      </div>
        <div class="main-card mb-12 card" >

                            <div class="card-header">
                               <button style="margin: 0 auto;" id="generateForm" class="btn btn-md btn-primary" >Generate Form</button>
                            </div>
                            <!-- <div class="card-body">
                                <div class="tab-content">

                                </div>
                            </div> -->
        </div>
    </div>

</div>
@endsection

@section('js')
<script type="text/javascript">


$("#generateForm").click(function(){


   var content = $("#form-gentate").html();
   var companyName = $("#companyName").val();
   var CompanyFileName = $("#CompanyFileName").val();
   if(companyName=='' || CompanyFileName=='' ){

      if(companyName=='' && CompanyFileName!='' ){
        alert('please select company ');
        return false;
      }else if(companyName!='' && CompanyFileName==''){
        alert('please enter filename');
        return false;
      }else{
        alert('please select company and enter filename');
        return false;

      }

   }else{
        $.ajax({

            url : '{{route('form/store-filename')}}',
            type : 'POST',
            data : {
                'content' : content,
                'UserId' : companyName,
                'CompanyFileName' : CompanyFileName,
                'form_value':$('#itemForm').serializeArray(),
                _token:'{{ csrf_token() }}'
                 },
            dataType:'json',
            success : function(data) {
            if(data.status=="success"){
                swal("Success!", data.msg, "success");
            }else{
                swal("failed!", data.msg, "danger");
            }
            },
            error : function(request,error)
            {
                swal("failed!", 'there is some technical problem please try again later', "danger");
            }
        });
   }






});




</script>
@endsection
