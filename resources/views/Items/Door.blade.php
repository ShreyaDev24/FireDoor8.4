@extends("layouts.Master")

@section("main_section")
<div class="app-main__outer">
    <div class="app-main__inner">
        @if (\Session::has('msg'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('msg') !!}</li>
            </ul>
        </div>
        @endif
        @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="fas fa-skull-crossbones"></i> Alert!</h5>
            {{ session()->get('error') }}
        </div>
        @endif

        <script>
            function Tooltip(tooltipValue){
                let TooltipCode2 =
                `<i class="fa fa-info-circle field_info tooltip" aria-hidden="true">
                    <span class="tooltiptext info_tooltip">`+tooltipValue+`</span>
                </i>`;
                return TooltipCode2;
            }
        </script>
        <form id="itemForm" enctype="multipart/form-data" method="post" action="{{route('items/store1')}}">
            <input type="hidden" name="version_id" value="<?= (!is_null($versionId))?$versionId:0; ?>">
            <div class="tab-content">
                <!-- Main Options -->
                <div class="main-card mb-3 custom_card">
                    <a href="http://firedoor2.workdemo.online/quotation/add-configuration-cad-item/{{$schedule->QuotationId}}" class="btn-shadow btn btn-info float-right" style="margin-right:5px">Configure</a>
                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="issingleconfiguration" value="{{$issingleconfiguration}}">
                    <input type="hidden" name="mark"
                        value="@if(isset($schedule)){{$schedule->Mark}} @else {{''}}  @endif">
                    <input type="hidden" name="type"
                        value="@if(isset($schedule)) {{$schedule->Type}} @else {{''}}  @endif">
                    <input type="hidden" name="QuotationId"
                        value="@if(isset($schedule)) {{$schedule->QuotationId}} @else {{''}}  @endif">
                    <input type="hidden" name="itemID"
                        value="@if(isset($schedule)) {{$schedule->id}} @else {{''}}  @endif">

                    @if(isset($editdata->id))
                    <input type="hidden" name="update" value="{{ $editdata->id }}">
                    @endif

                    <div class="">
                        <div class="tab-content">
                            <div class="card-header">
                                <h5 class="card-title" style="margin-top: 10px">Main Options</h5>
                            </div>
                            <div class="">
                                <div class="form-row">
                                    <!-- <div class="col-md-3">
                                        <div class="position-relative form-group">
                                        <label for="Company Name" class="">Company Name</label>
                                        <input name="CompanyName" required placeholder="Enter Company Name" value="@if(isset($editdata->id)) {{$editdata->CompanyName}} @endif" type="text" class="form-control"></div>
                                    </div> -->
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leafConstruction">Leaf Construction
                                            @if(!empty($tooltip->leafConstruction))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->leafConstruction}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                            <label for="doorType" class="">Door Type
                                            @if(!empty($tooltip->doorType))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->doorType}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input name="doorType" id="doorType" required placeholder="Enter door type"
                                                type="text"
                                                value="@if(isset($schedule)){{$schedule->Type}}@else{{old('doorType')}}@endif{{ old('doorType') }}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="fireRating" class="">Fire Rating
                                            @if(!empty($tooltip->fireRating))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->fireRating}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <select required name="fireRating" id="fireRating" class="form-control">
                                                <option value="">Select fire rating</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='fire_rating')
                                                <option value="{{$row->OptionKey}}"
                                                    @if(isset($schedule->FireRating))
                                                        @if($schedule->FireRating==$row->OptionKey){{'selected'}} @endif
                                                    @endif >{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorsetType" class="">Doorset Type
                                            @if(!empty($tooltip->doorsetType))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->doorsetType}}'));
                                            </script>
                                            @endif
                                            <span
                                                    class="dsl"></span>

                                                    </label>
                                            <!-- combination_of -->
                                            <select required name="doorsetType" id="doorsetType"
                                                class="form-control combination_of change-event-calulation">

                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='door_configuration_doorset_type')
                                                <option value="{{$row->OptionKey}}" >{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="swingType" class="">Swing Type
                                            @if(!empty($tooltip->swingType))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->swingType}}'));
                                            </script>
                                            @endif
                                            <span class="dsl"></span></label>
                                            <select required name="swingType" id="swingType"
                                                class="form-control combination_of">
                                                <option value="">Select swing type</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='door_configuration_swing_type')
                                                <option value="{{$row->OptionKey}}" @if(!empty($itemlist->SwingType)) @if($itemlist->SwingType == $row->OptionKey) {{'selected'}} @endif @endif >{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="latchType" class="">Latch Type
                                            @if(!empty($tooltip->latchType))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->latchType}}'));
                                            </script>
                                            @endif
                                            <span class="dsl"></span></label>
                                            <select name="latchType" id="latchType"
                                                class="form-control combination_of">
                                                <option value="">Select latch type</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='door_configuration_latch_type')
                                                <option value="{{$row->OptionKey}}" @if(!empty($itemlist->LatchType)) @if($itemlist->LatchType == $row->OptionKey) {{'selected'}} @endif @endif >{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="Handing" class="">Handing
                                            @if(!empty($tooltip->Handing))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->Handing}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <select required name="Handing" id="Handing" class="form-control">
                                                <option value="">Select Handing</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="OpensInwards" class="">Pull Towards
                                            @if(!empty($tooltip->OpensInwards))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->OpensInwards}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <select required name="OpensInwards" id="OpensInwards" class="form-control">
                                                <option value="">Select Pull Towards</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='Opens_Inwards')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="COC" class="">COC

                                            @if(!empty($tooltip->COC))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->COC}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <select required name="COC" id="COC" class="form-control">
                                                <option value="">Select COC</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='COC')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="tollerance" class="">Tollerance
                                            @if(!empty($tooltip->tollerance))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->tollerance}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="number" id="tollerance" name="tollerance"
                                                class="form-control for_c_leaf_height  forleafHeightNoOP change-event-calulation">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="undercut" class="">Undercut
                                            @if(!empty($tooltip->undercut))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->undercut}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="number" id="undercut" readonly name="undercut"
                                                class="form-control change-event-calulation  undercut">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="floorFinish" class="">Floor Finish
                                            @if(!empty($tooltip->floorFinish))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->floorFinish}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="number" id="floorFinish" name="floorFinish"
                                                class="form-control change-event-calulation forundercut">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="gap" class="">GAP
                                            @if(!empty($tooltip->gap))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->gap}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="number" id="gap" name="gap"
                                                class="form-control for_c_leaf_height  forleafHeightNoOP change-event-calulation">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="frameThickness" class="">Frame Thickness
                                            @if(!empty($tooltip->frameThickness))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->frameThickness}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <!-- <input type="number" id="frameThickness" name="frameThickness" class="form-control for_c_leaf_height  forleafHeightNoOP change-event-calulation"> -->
                                            <select name="frameThickness" required
                                                class="form-control change-event-calulation" id="frameThickness">
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



                <!-- DOOR DIMENSIONS & DOOR LEAF -->
                <div class="main-card mb-3 custom_card">
                    <div class="">
                        <div class="tab-content">
                            <div class="card-header">
                                <h5 class="card-title" style="margin-top: 10px">Door Dimensions & Door Leaf</h5><span></span>
                            </div>
                            <div class="">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="sOWidth" class="">S.O Width
                                            @if(!empty($tooltip->sOWidth))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->sOWidth}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="number" id="sOWidth" name="sOWidth" value="@if(!empty($itemlist->SOWidth)){{$itemlist->SOWidth}}@endif" class="form-control  change-event-calulation" required>
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="sOHeight" class="">S.O Height
                                            @if(!empty($tooltip->sOHeight))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->sOHeight}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="number" id="sOHeight" name="sOHeight" value="@if(!empty($itemlist->SOHeight)){{$itemlist->SOHeight}}@endif"
                                                class="form-control change-event-calulation" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="sODepth" class="">S.O Depth

                                            @if(!empty($tooltip->sODepth))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->sODepth}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <!-- <input type="number" id="sODepth" name="sODepth" class="form-control change-event-calulation "> -->
                                            <input type="number" id="sODepth" name="sODepth" class="form-control" value="@if(!empty($itemlist->SOWallThick)){{$itemlist->SOWallThick}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leafWidth1" class="">Leaf Width 1
                                            @if(!empty($tooltip->leafWidth1))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->leafWidth1}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="number" readonly id="leafWidth1" name="leafWidth1" readonly
                                                class="form-control change-event-calulation forcoreWidth1 LippingCalculation">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leafWidth2" class="">Leaf Width 2
                                            @if(!empty($tooltip->leafWidth2))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->leafWidth2}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="number" readonly id="leafWidth2" name="leafWidth2" class="form-control forcoreWidth1 LippingCalculation">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leafHeightNoOP" class="">Leaf Height
                                            @if(!empty($tooltip->leafHeightNoOP))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->leafHeightNoOP}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="number" id="leafHeightNoOP" readonly name="leafHeightNoOP" class="form-control leafHeightNoOP foroPWidth forcoreWidth1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorThickness" class="">Door Thickness (mm)
                                            @if(!empty($tooltip->doorThickness))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->doorThickness}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="number" readonly name="doorThickness" id="doorThickness" class="form-control">
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
                                            <label for="doorLeafFacing" class="">Door Leaf Facing
                                            @if(!empty($tooltip->doorLeafFacing))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->doorLeafFacing}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <select name="doorLeafFacing" id="doorLeafFacing" class="form-control">
                                                <option value="">Select door leaf facing</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='Door_Leaf_Facing')
                                                <option value="{{$row->OptionKey}}" @if(isset($schedule))
                                                    @if($schedule->DoorFinish==$row->OptionKey){{'selected'}} @endif
                                                    @endif>{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorLeafFacingValue" class="">Brand
                                            @if(!empty($tooltip->doorLeafFacingValue))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->doorLeafFacingValue}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <select name="doorLeafFacingValue" id="doorLeafFacingValue"
                                                class="form-control">
                                                <option value="">Select Brand</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorLeafFacing" class="">Door Leaf Finish
                                            @if(!empty($tooltip->doorLeafFacing))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->doorLeafFacing}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <div class="doorLeafFinishDiv">
                                                <select name="doorLeafFinish" id="doorLeafFinish"
                                                    class="form-control doorLeafFinishSelect">
                                                    <option value="">Select door leaf finish</option>
                                                </select>
                                            </div>
                                            <!-- <div class="input-icons doorLeafFinishInputDiv" hidden>

                                                <i class="fa fa-info icon" id="doorLeafFinishIcon" onClick="$('#ralColor').modal();"></i>
                                                <input type="text"  readonly name="doorLeafFinish" id="doorLeafFinish"  class="form-control" >

                                            </div> -->
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorLeafFinishColor" class="">Door Leaf Finish Color
                                            @if(!empty($tooltip->doorLeafFinishColor))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->doorLeafFinishColor}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <!-- <select name="doorLeafFinishColor" id="doorLeafFinishColor" class="form-control">
                                                <option value="">Select door leaf finish color</option> -->
                                            <!-- @foreach($option_data as $row)
                                            @if($row->OptionSlug=='door_leaf_finish')
                                            <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                            @endif
                                            @endforeach -->
                                            <!-- </select> -->
                                            <div class="input-icons">
                                                <i class="fa fa-info icon" id="doorLeafFinishColorIcon" onClick=""></i>
                                                <input type="text" readonly name="doorLeafFinishColor"
                                                    id="doorLeafFinishColor" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="decorativeGroves" class="">Decorative Groves
                                            @if(!empty($tooltip->decorativeGroves))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->decorativeGroves}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="grooveLocation" class="">Groove Location
                                            @if(!empty($tooltip->grooveLocation))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->grooveLocation}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <select name="grooveLocation" id="grooveLocation" class="form-control">
                                                <option value="" selected>Select Groves Location</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='Groove_Location')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="grooveWidth" class="">Groove Width(Max 10 mm)
                                            @if(!empty($tooltip->grooveWidth))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->grooveWidth}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="number" max="10" name="grooveWidth" id="grooveWidth"
                                                class="form-control MaxInputWarning">
                                            <input type="hidden" name="getmaxinputvalue" value="10">
                                            <input type="hidden" name="getmsginput" value="Groove Width is not more than 10mm.">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="grooveDepth" class="">Groove Depth
                                            @if(!empty($tooltip->grooveDepth))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->grooveDepth}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="number" name="grooveDepth" id="grooveDepth"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="maxNumberOfGroove" class=""> Maximum Number of Groove
                                            @if(!empty($tooltip->maxNumberOfGroove))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->maxNumberOfGroove}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="number" value="0" pattern="\d*" name="maxNumberOfGroove"
                                                id="maxNumberOfGroove" class="form-control" readonly>
                                            <!-- <input type="text" value="0" pattern="[+-]?([0-9]*[.])?[0-9]+" readonly onchange='return event.charCode >= 48 && event.charCode <= 57' name="maxNumberOfGroove" id="maxNumberOfGroove" class="form-control"> -->
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="numberOfGroove" class="">Number of Grooves
                                            @if(!empty($tooltip->numberOfGroove))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->numberOfGroove}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="number" value="0" max="10" name="numberOfGroove"
                                                id="numberOfGroove" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="numberOfVerticalGroove" class="">No. of Vertical Grooves(Max 4)
                                            @if(!empty($tooltip->numberOfVerticalGroove))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->numberOfVerticalGroove}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="number" value="0" max="4" name="numberOfVerticalGroove"
                                                id="numberOfVerticalGroove" class="form-control MaxInputWarning">
                                                <input type="hidden" name="getmaxinputvalue" value="4">
                                                <input type="hidden" name="getmsginput" value="Vertical Grooves is not more than 4.">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="numberOfHorizontalGroove" class="">No. of Horizontal Grooves(Max
                                                4)
                                            @if(!empty($tooltip->numberOfHorizontalGroove))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->numberOfHorizontalGroove}}'));
                                            </script>
                                            @endif
                                                </label>
                                            <input type="number" value="0" name="numberOfHorizontalGroove"
                                                id="numberOfHorizontalGroove" max="4" class="form-control MaxInputWarning">
                                                <input type="hidden" name="getmaxinputvalue" value="4">
                                                <input type="hidden" name="getmsginput" value="Horizontal Grooves is not more than 4.">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- VISION PANEL -->
                <div class="main-card mb-3 custom_card">
                    <div class="">
                        <div class="tab-content">
                            <div class="card-header">
                                <h5 class="card-title" style="margin-top: 10px">Vision Panel</h5>
                            </div>
                            <div class="">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leaf1VisionPanel" class="">Leaf 1 Vision Panel
                                            @if(!empty($tooltip->leaf1VisionPanel))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->leaf1VisionPanel}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <select required name="leaf1VisionPanel" id="leaf1VisionPanel"
                                                class="form-control change-event-calulation">
                                                <option value=""> Is Vision Panel active? </option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='leaf1_vision_panel')
                                                <option value="{{$row->OptionKey}}"
                                                    @if(isset($schedule))
                                                        @if($schedule->VisionPanel==$row->OptionKey)
                                                            {{'selected'}}
                                                        @elseif($row->OptionKey == 'No')
                                                            {{'selected'}}
                                                        @endif
                                                    @endif
                                                >{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leaf1VisionPanelShape" class="">Vision Panel Shape
                                            @if(!empty($tooltip->leaf1VisionPanelShape))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->leaf1VisionPanelShape}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <select required name="leaf1VisionPanelShape" id="leaf1VisionPanelShape"
                                                class="form-control change-event-calulation">
                                                <option val="">select any shape </option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='Vision_panel_shape')
                                                <option value="{{$row->OptionKey}}" @if(isset($schedule))
                                                    @if($schedule->VisionPanel==$row->OptionKey){{'selected'}} @endif
                                                    @endif>{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="visionPanelQuantity" class="">Vision Panel Quantity
                                            @if(!empty($tooltip->visionPanelQuantity))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->visionPanelQuantity}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                            <label for="AreVPsEqualSizes" class="">Are VP's equal sizes?
                                            @if(!empty($tooltip->AreVPsEqualSizes))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->AreVPsEqualSizes}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                                door
                                            @if(!empty($tooltip->distanceFromTopOfDoor))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->distanceFromTopOfDoor}}'));
                                            </script>
                                            @endif
                                                </label>
                                            <input required Type="number" readonly min="100"
                                                name="distanceFromTopOfDoor" id="distanceFromTopOfDoor"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="distanceFromTheEdgeOfDoor" class="">Distance from the
                                                edge
                                            @if(!empty($tooltip->distanceFromTheEdgeOfDoor))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->distanceFromTheEdgeOfDoor}}'));
                                            </script>
                                            @endif
                                                </label>
                                            <input required Type="number" readonly min="100"
                                                name="distanceFromTheEdgeOfDoor" id="distanceFromTheEdgeOfDoor"
                                                class="form-control">
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="distanceBetweenVPs" class="">Distance between VP's
                                            @if(!empty($tooltip->distanceBetweenVPs))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->distanceBetweenVPs}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input Type="number" min="80" readonly name="distanceBetweenVPs"
                                                id="distanceBetweenVPs" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP1Width" class="">Leaf 1 VP Width
                                            @if(!empty($tooltip->vP1Width))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->vP1Width}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input Type="number" min="0" readonly name="vP1Width" id="vP1Width"
                                                class="form-control change-event-calulation">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP1Height1" class="">Leaf 1 VP Height (1)
                                            @if(!empty($tooltip->vP1Height1))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->vP1Height1}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input Type="number" min="0" readonly name="vP1Height1" id="vP1Height1"
                                                class="form-control change-event-calulation">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP1Height2" class="">Leaf 1 VP Height (2)

                                            @if(!empty($tooltip->vP1Height2))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->vP1Height2}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input Type="number" min="0" readonly name="vP1Height2" id="vP1Height2"
                                                class="form-control change-event-calulation">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP1Height3" class="">Leaf 1 VP Height (3)
                                            @if(!empty($tooltip->vP1Height3))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->vP1Height3}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input Type="number" min="0" readonly name="vP1Height3" id="vP1Height3"
                                                class="form-control change-event-calulation">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP1Height4" class="">Leaf 1 VP Height (4)
                                            @if(!empty($tooltip->vP1Height4))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->vP1Height4}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input Type="number" min="0" readonly name="vP1Height4" id="vP1Height4"
                                                class="form-control change-event-calulation">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP1Height5" class="">Leaf 1 VP Height (5)
                                            @if(!empty($tooltip->vP1Height5))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->vP1Height5}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input Type="number" min="0" readonly name="vP1Height5" id="vP1Height5"
                                                class="form-control change-event-calulation">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leaf1VpAreaSizeM2" class=""> Leaf 1 VP Area Size m2

                                            @if(!empty($tooltip->leaf1VpAreaSizeM2))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->leaf1VpAreaSizeM2}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input Type="number" min="0" readonly name="leaf1VpAreaSizeM2"
                                                id="leaf1VpAreaSizeM2" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leaf2VisionPanel" class="">Leaf 2 Vision Panel
                                            @if(!empty($tooltip->leaf2VisionPanel))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->leaf2VisionPanel}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                            <label for="vpSameAsLeaf1" class="">Is VP same as Leaf 1?
                                            @if(!empty($tooltip->vpSameAsLeaf1))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->vpSameAsLeaf1}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                                Quantity

                                            @if(!empty($tooltip->visionPanelQuantityforLeaf2))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->visionPanelQuantityforLeaf2}}'));
                                            </script>
                                            @endif
                                                </label>
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
                                                2?
                                            @if(!empty($tooltip->AreVPsEqualSizesForLeaf2))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->AreVPsEqualSizesForLeaf2}}'));
                                            </script>
                                            @endif
                                                </label>
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
                                                door for Leaf 2
                                            @if(!empty($tooltip->distanceFromTopOfDoorforLeaf2))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->distanceFromTopOfDoorforLeaf2}}'));
                                            </script>
                                            @endif
                                                </label>
                                            <input required Type="number" readonly min="100"
                                                name="distanceFromTopOfDoorforLeaf2" id="distanceFromTopOfDoorforLeaf2"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="distanceFromTheEdgeOfDoorforLeaf2" class="">Distance from the
                                                edge for Leaf 2
                                            @if(!empty($tooltip->distanceFromTheEdgeOfDoorforLeaf2))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->distanceFromTheEdgeOfDoorforLeaf2}}'));
                                            </script>
                                            @endif

                                                </label>
                                            <input required Type="number" readonly min="100"
                                                name="distanceFromTheEdgeOfDoorforLeaf2"
                                                id="distanceFromTheEdgeOfDoorforLeaf2" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="distanceBetweenVPsforLeaf2" class="">Distance between
                                                VP's
                                            @if(!empty($tooltip->distanceBetweenVPsforLeaf2))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->distanceBetweenVPsforLeaf2}}'));
                                            </script>
                                            @endif
                                                </label>
                                            <input Type="number" min="80" readonly name="distanceBetweenVPsforLeaf2"
                                                id="distanceBetweenVPsforLeaf2" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP2Width" class="">Leaf 2 VP Width
                                            @if(!empty($tooltip->vP2Width))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->vP2Width}}'));
                                            </script>
                                            @endif

                                            </label>
                                            <input Type="number" min="0" readonly name="vP2Width" id="vP2Width"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP2Height1" class="">Leaf 2 VP Height (1)
                                            @if(!empty($tooltip->vP2Height1))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->vP2Height1}}'));
                                            </script>
                                            @endif

                                            </label>
                                            <input Type="number" min="0" readonly name="vP2Height1" id="vP2Height1"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP2Height2" class="">Leaf 2 VP Height (2)
                                            @if(!empty($tooltip->vP2Height2))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->vP2Height2}}'));
                                            </script>
                                            @endif

                                            </label>
                                            <input Type="number" min="0" readonly name="vP2Height2" id="vP2Height2"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP2Height3" class="">Leaf 2 VP Height (3)
                                            @if(!empty($tooltip->vP2Height3))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->vP2Height3}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input Type="number" min="0" readonly name="vP2Height3" id="vP2Height3"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP2Height4" class="">Leaf 2 VP Height (4)
                                            @if(!empty($tooltip->vP2Height4))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->vP2Height4}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input Type="number" min="0" readonly name="vP2Height4" id="vP2Height4"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP2Height5" class="">Leaf 2 VP Height (5)
                                            @if(!empty($tooltip->vP2Height5))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->vP2Height5}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                                Integrity
                                            @if(!empty($tooltip->lazingIntegrityOrInsulationIntegrity))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->lazingIntegrityOrInsulationIntegrity}}'));
                                            </script>
                                            @endif
                                                </label>

                                            <select name="lazingIntegrityOrInsulationIntegrity"
                                                id="lazingIntegrityOrInsulationIntegrity" class="form-control">
                                                <option value=''> Select Glass Integrity</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glassType" class="">Glass Type
                                            @if(!empty($tooltip->glassType))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->glassType}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                            <label for="glassThickness" class="">Glass Thickness
                                            @if(!empty($tooltip->glassThickness))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->glassThickness}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="text" readonly name="glassThickness" id="glassThickness"
                                                class="form-control">
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
                                            <label for="glazingSystems" class="">Glazing Systems
                                            @if(!empty($tooltip->glazingSystems))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->glazingSystems}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                                Thickness
                                            @if(!empty($tooltip->glazingSystemsThickness))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->glazingSystemsThickness}}'));
                                            </script>
                                            @endif

                                                </label>
                                            <input type="text" readonly name="glazingSystemsThickness"
                                                id="glazingSystemsThickness" class="form-control">
                                                <!-- <select name="glazingSystemsThickness" id="glazingSystemsThickness" class="form-control">
                                                        <option value="">Select Glazing Thickness</option>
                                                </select> -->
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glazingBeads" class="">Glazing Beads

                                            @if(!empty($tooltip->glazingBeads))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->glazingBeads}}'));
                                            </script>
                                            @endif

                                            </label>
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
                                            <label for="glazingBeadsThickness" class="">Glazing Beads Thickness
                                            @if(!empty($tooltip->glazingBeadsThickness))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->glazingBeadsThickness}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input Type="number" min="0" name="glazingBeadsThickness"
                                                id="glazingBeadsThickness" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glazingBeadsWidth" class="">Glazing Beads Width
                                            @if(!empty($tooltip->glazingBeadsWidth))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->glazingBeadsWidth}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input Type="number" min="0" name="glazingBeadsWidth" id="glazingBeadsWidth"
                                                class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glazingBeadsHeight" class="">Glazing Beads Height
                                            @if(!empty($tooltip->glazingBeadsHeight))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->glazingBeadsHeight}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input Type="number" min="0" name="glazingBeadsHeight"
                                                id="glazingBeadsHeight" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glazingBeadsFixingDetail" class="">Glazing Bead Fixing
                                                Detail
                                            @if(!empty($tooltip->glazingBeadsFixingDetail))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->glazingBeadsFixingDetail}}'));
                                            </script>
                                            @endif

                                                </label>
                                            <input Type="text" name="glazingBeadsFixingDetail"
                                                id="glazingBeadsFixingDetail" class="form-control">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glazingBeadSpecies" class="">Glazing Bead Species
                                            @if(!empty($tooltip->glazingBeadSpecies))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->glazingBeadSpecies}}'));
                                            </script>
                                            @endif

                                            </label>
                                            <!-- <select name="glazingBeadSpecies" id="glazingBeadSpecies"
                                                class="form-control">
                                                <option value="">Select Species</option>
                                                 @foreach($option_data as $row)
                                                @if($row->OptionSlug=='leaf_construction')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select> -->
                                            <div class="input-icons">
                                                <i class="fa fa-info icon" id="glazingBeadSpeciesIcon" onClick=""></i>
                                                <input type="text" readonly id="glazingBeadSpecies"
                                                    class="form-control bg-white">
                                                <input type="hidden" name="glazingBeadSpecies">
                                            </div>
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

                <!-- Frame -->
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
                                            <label for="frameMaterial" class="">Frame Material
                                            @if(!empty($tooltip->frameMaterial))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->frameMaterial}}'));
                                            </script>
                                            @endif

                                            </label>
                                            {{--<select name="frameMaterial" id="frameMaterial" class="form-control">--}}
                                            {{--<option value="">Select Frame material</option>--}}
                                            {{--</select>--}}

                                            <div class="input-icons">

                                                <i class="fa fa-info icon" id="frameMaterialIcon" onClick=""></i>
                                                <input type="text" readonly name="frameMaterial" id="frameMaterial"
                                                    class="form-control bg-white">

                                            </div>

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="frameType" class="">Frame Type
                                            @if(!empty($tooltip->frameType))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->frameType}}'));
                                            </script>
                                            @endif

                                            </label>
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
                                            <label for="plantonStopWidth" class="">Plant on Stop Width(max 32)

                                            @if(!empty($tooltip->plantonStopWidth))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->plantonStopWidth}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="number" readonly max="32" name="plantonStopWidth" value="0"
                                                id="plantonStopWidth" class="form-control MaxInputWarning">
                                                <input type="hidden" name="getmaxinputvalue" value="32">
                                                <input type="hidden" name="getmsginput" value="Plant on Stop Width is not more than 32.">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="plantonStopHeight" class="">Plant on Stop Height(max 12)
                                            @if(!empty($tooltip->plantonStopHeight))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->plantonStopHeight}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="number" readonly max="12" name="plantonStopHeight" value="0"
                                                id="plantonStopHeight" class="form-control MaxInputWarning">
                                                <input type="hidden" name="getmaxinputvalue" value="12">
                                                <input type="hidden" name="getmsginput" value="Plant on Stop Height is not more than 12.">
                                        </div>
                                    </div>
                                    <div class="col-md-3" hidden>
                                        <div class="position-relative form-group">
                                            <label for="frameTypeDimensions" class="">Dimensions
                                            @if(!empty($tooltip->frameTypeDimensions))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->frameTypeDimensions}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="number" name="frameTypeDimensions" value="0" min="1" readonly
                                                id="frameTypeDimensions" class="form-control">

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="frameWidth" class="">Frame Width
                                            @if(!empty($tooltip->frameWidth))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->frameWidth}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="text" name="frameWidth" id="frameWidth"
                                                placeholder="Frame Width" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="frameHeight" class="">Frame Height
                                            @if(!empty($tooltip->frameHeight))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->frameHeight}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="text" name="frameHeight" placeholder="Frame Height"
                                                id="frameHeight" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="frameDepth" class="">Frame Depth
                                            @if(!empty($tooltip->frameDepth))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->frameDepth}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="text" name="frameDepth" id="frameDepth" class="form-control">
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="frameThickness1" class="">Frame Thickness</label>
                             <input name="frameThickness1" required placeholder="Frame Thickness" id="frameThickness1" min="0" type="text" class="form-control">

                        </div>
                    </div> -->
                                    <input name="frameThickness1" id="frameThickness1" min="0" type="hidden"
                                        class="form-control">

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="frameFinish" class="">Frame Finish

                                            @if(!empty($tooltip->frameFinish))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->frameFinish}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                            <label for="framefinishColor" class="">Frame finish Color
                                            @if(!empty($tooltip->framefinishColor))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->framefinishColor}}'));
                                            </script>
                                            @endif

                                            </label>
                                            <select name="framefinishColor" id="framefinishColor" class="form-control">
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
                                            <label for="extLiner" class="">Ext-Liner
                                            @if(!empty($tooltip->extLiner))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->extLiner}}'));
                                            </script>
                                            @endif

                                            </label>
                                            <select name="extLiner" id="extLiner" class="form-control change-event-calulation">
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
                                            <label for="frameCostuction" class="">Door Frame Construction
                                            @if(!empty($tooltip->frameCostuction))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->frameCostuction}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <!-- <select name="frameCostuction" id="frameCostuction"
                                                class="form-control change-event-calulation">
                                                <option value="">Select Door Frame Construction</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='Door_Frame_Construction')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select> -->
                                            <div class="input-icons">
                                                <i class="fa fa-info icon cursor-pointer" id="frameCostuction" onClick="$('#DoorFrameConstructionModal').modal()"></i>
                                                <input type="text" readonly id="frameCostuction" class="form-control bg-white">
                                                <input type="hidden" name="frameCostuction">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="extLinerValue" class="">Ext-Liner Value
                                            @if(!empty($tooltip->extLinerValue))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->extLinerValue}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="text" readonly value="0" name="extLinerValue"
                                                id="extLinerValue" class="form-control">


                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="extLinerSize" class="">Ext-Liner Size
                                            @if(!empty($tooltip->extLinerSize))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->extLinerSize}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input name="extLinerSize" readonly id="extLinerSize"
                                                placeholder="Ext-Liner Size" class="form-control" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="extLinerThickness" class="">Ext-Liner Thickness
                                            @if(!empty($tooltip->extLinerThickness))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->extLinerThickness}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input name="extLinerThickness" readonly id="extLinerThickness"
                                                placeholder="Ext-Liner Thickness"
                                                class="form-control change-event-calulation" type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="extLinerFinish" class="">Ext-Liner FInish
                                            @if(!empty($tooltip->extLinerFinish))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->extLinerFinish}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                            <label for="intumescentSeal" class="">Intumescent Seal
                                            @if(!empty($tooltip->intumescentSeal))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->intumescentSeal}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <select name="intumescentSeal" id="intumescentSeal" class="form-control">
                                                <option value="Frame">Frame</option>
                                                <option value="Door Leaf 1">Door Leaf 1</option>
                                                <option value="Door Leaf 2">Door Leaf 2</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="intumescentSealColor" class="">Intumescent Seal Color
                                            @if(!empty($tooltip->intumescentSealColor))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->intumescentSealColor}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                            <label for="intumescentSealSize" class="">Intumescent Seal Size
                                            @if(!empty($tooltip->intumescentSealSize))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->intumescentSealSize}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <select name="intumescentSealSize" id="intumescentSealSize"
                                                class="form-control">
                                                <option value="10x4mm">10x4mm</option>
                                            </select>
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="ironmongerySet" class="">Ironmongery Set
                                            @if(!empty($tooltip->ironmongerySet))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->ironmongerySet}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <select name="ironmongerySet" id="ironmongerySet" class="form-control">
                                                <option value="Yes">Yes</option>
                                                <option value="No" selected>No</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="IronmongeryID">Select Ironmongery Set
                                            @if(!empty($tooltip->selectironmongerySet))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->selectironmongerySet}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <select name="IronmongeryID" id="IronmongeryID" class="form-control" disabled>
                                                <option value="">Select Ironmongery Set</option>
                                                @if(!empty($setIronmongery))
                                                @foreach($setIronmongery as $setIronmongerys)
                                                    <option value="{{$setIronmongerys->id}}">{{$setIronmongerys->Setname}}</option>
                                                @endforeach
                                                @endif
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="specialFeatureRefs" class="">Special Feature Refs
                                            @if(!empty($tooltip->specialFeatureRefs))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->specialFeatureRefs}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input name="specialFeatureRefs" id="specialFeatureRefs"
                                                placeholder="Special Feature Refs" class="form-control" type="text">
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Over Panel Section -->
                <div class="main-card mb-3 custom_card">
                    <div class="">
                        <div class="tab-content">
                            <div class="card-header">
                                <h5 class="card-title" style="margin-top: 10px">Over Panel Section</h5>
                            </div>
                            <div class="">
                                <div class="form-row">
                                <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="overpanel" class="">Overpanel
                                            @if(!empty($tooltip->overpanel))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->overpanel}}'));
                                            </script>
                                            @endif

                                            </label>
                                            <select required name="overpanel" id="overpanel"
                                                class="form-control change-event-calulation">
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='door_configuration_overpanel')
                                                <option value="{{$row->OptionKey}}" @if($row->OptionKey == 'No'){{'selected'}}@endif>{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="OPLippingThickness" class="">OP Lipping Thickness
                                            @if(!empty($tooltip->OPLippingThickness))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->OPLippingThickness}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                            <label for="oPWidth" class="">OP Width
                                            @if(!empty($tooltip->oPWidth))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->oPWidth}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input name="oPWidth" id="oPWidth" readonly class="form-control oPWidth"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="oPHeigth" class="">OP Height (Max-value:600)
                                            @if(!empty($tooltip->oPHeigth))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->oPHeigth}}'));
                                            </script>
                                            @endif

                                            </label>
                                            <input name="oPHeigth" id="oPHeigth" max="600" class="form-control MaxInputWarning" type="number">
                                            <input type="hidden" name="getmaxinputvalue" value="600">
                                            <input type="hidden" name="getmsginput" value="OP Heigth is not more than 600.">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="opTransom" class="">OP Transom
                                            @if(!empty($tooltip->opTransom))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->opTransom}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                            <label for="transomThickness" class="">Transom Thickness
                                            @if(!empty($tooltip->transomThickness))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->transomThickness}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                            <label for="opGlassType" class="">OP Glass Type
                                            @if(!empty($tooltip->opGlassType))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->opGlassType}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <select name="opGlassType" id="opGlassType" class="form-control">
                                                <option value="">Select OP Glass Type</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="opGlazingBeads" class="">OP Glazing Beads
                                            @if(!empty($tooltip->opGlazingBeads))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->opGlazingBeads}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <select name="opGlazingBeads" id="opGlazingBeads" class="form-control">
                                                <option value="">Select Glazing Beads</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="opGlazingBeadSpecies" class="">OP Glazing Bead Species
                                            @if(!empty($tooltip->opGlazingBeadSpecies))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->opGlazingBeadSpecies}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <!-- <select name="opGlazingBeadSpecies" id="opGlazingBeadSpecies"
                                                class="form-control">
                                                <option value="">Select Species</option>
                                            </select> -->
                                            <div class="input-icons">
                                                <i class="fa fa-info icon" id="opGlazingBeadSpeciesIcon" onClick=""></i>
                                                <input type="text" readonly id="opGlazingBeadSpecies"
                                                    class="form-control bg-white">
                                                <input type="hidden" name="opGlazingBeadSpecies">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- SIDE LIGHT -->
                <div class="main-card mb-3 custom_card">
                    <div class="">
                        <div class="tab-content">
                            <div class="card-header">
                                <h5 class="card-title" style="margin-top: 10px">Side Light</h5>
                            </div>
                            <div class="">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="sideLight1" class="">Side Light 1 (SL1)
                                            @if(!empty($tooltip->sideLight1))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->sideLight1}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                            <label for="sideLight1GlassType" class="">Side Light 1 Glass Type
                                            @if(!empty($tooltip->sideLight1GlassType))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->sideLight1GlassType}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                            <label for="SideLight1BeadingType" class="">Beading Type
                                            @if(!empty($tooltip->SideLight1BeadingType))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->SideLight1BeadingType}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                                Species
                                            @if(!empty($tooltip->SideLight1GlazingBeadSpecies))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->SideLight1GlazingBeadSpecies}}'));
                                            </script>
                                            @endif

                                                </label>
                                            <!-- <select name="SideLight1GlazingBeadSpecies"
                                                id="SideLight1GlazingBeadSpecies" disabled class="form-control">
                                                <option value="">Select Glazing Bead Species</option>
                                                 @foreach($option_data as $row)
                                                @if($row->OptionSlug=='leaf1_glass_type')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select> -->
                                            <div class="input-icons">
                                                <i class="fa fa-info icon" id="SideLight1GlazingBeadSpeciesIcon"
                                                    onClick=""></i>
                                                <input type="text" id="SideLight1GlazingBeadSpecies"
                                                    class="form-control" disabled>
                                                <input type="hidden" name="SideLight1GlazingBeadSpecies">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL1Width" class="">SL1 Width
                                            @if(!empty($tooltip->SL1Width))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->SL1Width}}'));
                                            </script>
                                            @endif

                                            </label>
                                            <input name="SL1Width" readonly id="SL1Width" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL1Height" readonly class="">SL1 Height
                                            @if(!empty($tooltip->SL1Height))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->SL1Height}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input name="SL1Height" readonly id="SL1Height" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL1Depth" class="">SL1 Depth
                                            @if(!empty($tooltip->SL1Depth))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->SL1Depth}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input name="SL1Depth" readonly id="SL1Depth" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL1Transom" class="">SL1 Transom
                                            @if(!empty($tooltip->SL1Transom))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->SL1Transom}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                            <label for="sideLight2" class="">Side Light 2 (SL2)
                                            @if(!empty($tooltip->sideLight2))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->sideLight2}}'));
                                            </script>
                                            @endif

                                            </label>
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
                                                SL1?
                                            @if(!empty($tooltip->copyOfSideLite1))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->copyOfSideLite1}}'));
                                            </script>
                                            @endif
                                                </label>
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
                                            <label for="sideLight2GlassType" class="">Side Light 2 Glass Type
                                            @if(!empty($tooltip->sideLight2GlassType))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->sideLight2GlassType}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                                Type
                                            @if(!empty($tooltip->SideLight2BeadingType))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->SideLight2BeadingType}}'));
                                            </script>
                                            @endif
                                                </label>
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
                                                Species
                                            @if(!empty($tooltip->SideLight2GlazingBeadSpecies))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->SideLight2GlazingBeadSpecies}}'));
                                            </script>
                                            @endif

                                                </label>
                                            <!-- <select name="SideLight2GlazingBeadSpecies"
                                                id="SideLight2GlazingBeadSpecies" disabled class="form-control">
                                                <option value="">Select Glazing Bead Species</option>
                                                 @foreach($option_data as $row)
                                                @if($row->OptionSlug=='leaf1_glass_type')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select> -->
                                            <div class="input-icons">
                                                <i class="fa fa-info icon" id="SideLight2GlazingBeadSpeciesIcon"
                                                    onClick=""></i>
                                                <input type="text" id="SideLight2GlazingBeadSpecies"
                                                    class="form-control" disabled>
                                                <input type="hidden" name="SideLight2GlazingBeadSpecies">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL2Width" class="">SL2 Width
                                            @if(!empty($tooltip->SL2Width))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->SL2Width}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input name="SL2Width" readonly id="SL2Width" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL2Height" class="">SL2 Height
                                            @if(!empty($tooltip->SL2Height))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->SL2Height}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input name="SL2Height" readonly id="SL2Height" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL2Depth" class="">SL2 Depth
                                            @if(!empty($tooltip->SL2Depth))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->SL2Depth}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input name="SL2Depth" readonly id="SL2Depth" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL2Transom" class="">SL2 Transom
                                            @if(!empty($tooltip->SL2Transom))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->SL2Transom}}'));
                                            </script>
                                            @endif

                                            </label>
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

                <!-- LIPPING AND INTUMESCENT  -->
                <div class="main-card mb-3 custom_card">
                    <div class="">
                        <div class="tab-content">
                            <div class="card-header">
                                <h5 class="card-title" style="margin-top: 10px">Lipping And Intumescent </h5>
                            </div>
                            <div class="">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="lippingType" class="">Lipping Type
                                            @if(!empty($tooltip->lippingType))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->lippingType}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                            <label for="lippingThickness" class="">Lipping Thickness
                                            @if(!empty($tooltip->lippingThickness))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->lippingThickness}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <select name="lippingThickness" id="lippingThickness"
                                                class="form-control forcoreWidth1 LippingCalculation">
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
                                            <label for="lippingSpecies" class="">Lipping Species
                                            @if(!empty($tooltip->lippingSpecies))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->lippingSpecies}}'));
                                            </script>
                                            @endif

                                            </label>
                                            <!-- <select name="lippingSpecies" id="lippingSpecies" class="form-control">
                                                <option value="">Select Lipping Species</option>
                                            </select> -->
                                            <div class="input-icons">
                                                <i class="fa fa-info icon" id="lippingSpeciesIcon" onClick=""></i>
                                                <input type="text" readonly id="lippingSpecies"
                                                    class="form-control bg-white">
                                                <input type="hidden" name="lippingSpecies">
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="meetingStyle" class="">Meeting Style
                                            @if(!empty($tooltip->meetingStyle))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->meetingStyle}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                                Thickness
                                            @if(!empty($tooltip->scallopedLippingThickness))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->scallopedLippingThickness}}'));
                                            </script>
                                            @endif

                                                </label>
                                            <select name="scallopedLippingThickness" disabled
                                                id="scallopedLippingThickness" class="form-control">
                                                <option value="">Select Scalloped Lipping Thickness</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="flatLippingThickness" class="">Flat Lipping Thickness
                                            @if(!empty($tooltip->flatLippingThickness))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->flatLippingThickness}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <select name="flatLippingThickness" disabled id="flatLippingThickness"
                                                class="form-control">
                                                <option value="">Select Flat Lipping Thickness</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="rebatedLippingThickness" class="">Rebated Lipping
                                                Thickness
                                            @if(!empty($tooltip->rebatedLippingThickness))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->rebatedLippingThickness}}'));
                                            </script>
                                            @endif
                                                </label>
                                            <select name="rebatedLippingThickness" disabled id="rebatedLippingThickness"
                                                class="form-control">
                                                <option value="">Select Rebated Lipping Thickness</option>

                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="coreWidth1" class="">Core Width 1
                                            @if(!empty($tooltip->coreWidth1))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->coreWidth1}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="number" min="1" readonly id="coreWidth1" name="coreWidth1"
                                                class="form-control coreWidth1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="coreWidth2" class="">Core Width 2
                                            @if(!empty($tooltip->coreWidth2))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->coreWidth2}}'));
                                            </script>
                                            @endif

                                            </label>
                                            <input type="number" min="1" readonly id="coreWidth2" name="coreWidth2"
                                                class="form-control coreWidth1">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="coreHeight" class="">Core Height
                                            @if(!empty($tooltip->coreHeight))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->coreHeight}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input type="number" min="1" readonly id="coreHeight" name="coreHeight"
                                                class="form-control coreWidth1">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="intumescentSealType" class="">Intumescent Seal Type
                                            @if(!empty($tooltip->intumescentSealType))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->intumescentSealType}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                                Location
                                            @if(!empty($tooltip->intumescentSealLocation))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->intumescentSealLocation}}'));
                                            </script>
                                            @endif
                                                </label>
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
                                            <label for="intumescentSealColor" class="">Intumescent Seal Color
                                            @if(!empty($tooltip->intumescentSealColor))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->intumescentSealColor}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                                Arrangement
                                            @if(!empty($tooltip->intumescentSealArrangement))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->intumescentSealArrangement}}'));
                                            </script>
                                            @endif
                                                </label>
                                            <select name="intumescentSealArrangement" id="intumescentSealArrangement" class="form-control"></select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ACCOUSTICS -->
                <div class="main-card mb-3 custom_card">
                    <div class="">
                        <div class="tab-content">
                            <div class="card-header">
                                <h5 class="card-title" style="margin-top: 10px">Accoustics </h5>
                            </div>
                            <div class="">
                                <div class="form-row">

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="accoustics" class="">Accoustics
                                            @if(!empty($tooltip->accoustics))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->accoustics}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                            <label for="rWdBRating" class="">rW dB Rating
                                            @if(!empty($tooltip->rWdBRating))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->rWdBRating}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input name="rWdBRating" id="rWdBRating" class="form-control" type="text">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="accousticsJambs" class="">Accoustics Jambs
                                            @if(!empty($tooltip->accousticsJambs))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->accousticsJambs}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input name="accousticsJambs" id="accousticsJambs" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="accousticsHead" class="">Accoustics Head
                                            @if(!empty($tooltip->accousticsHead))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->accousticsHead}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input name="accousticsHead" id="accousticsHead" class="form-control"
                                                type="text">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="thresholdSeal" class="">Threshold Seal
                                            @if(!empty($tooltip->thresholdSeal))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->thresholdSeal}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input name="thresholdSeal" id="thresholdSeal" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="accousticsSeal" class="">Accoustics Seal
                                            @if(!empty($tooltip->accousticsSeal))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->accousticsSeal}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input name="accousticsSeal" id="accousticsSeal" class="form-control"
                                                type="text">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="accousticsmeetingStiles" class="">Meeting Stiles
                                            @if(!empty($tooltip->accousticsmeetingStiles))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->accousticsmeetingStiles}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input name="accousticsmeetingStiles" id="meetingStiles"
                                                class="form-control" type="text">

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="accousticsglassType" class="">Glass Type
                                            @if(!empty($tooltip->accousticsglassType))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->accousticsglassType}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input name="accousticsglassType" id="glassType" class="form-control"
                                                type="text">
                                        </div>
                                    </div>





                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- ARCHITRAVE -->
                <div class="main-card mb-3 custom_card">
                    <div class="">
                        <div class="tab-content">
                            <div class="card-header">
                                <h5 class="card-title" style="margin-top: 10px">Architrave </h5>
                            </div>
                            <div class="">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="Architrave" class="">Architrave
                                            @if(!empty($tooltip->Architrave))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->Architrave}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <select name="Architrave" id="Architrave" class="form-control">
                                                <option value="">Select Architrave</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='Architrave')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="architraveMaterial" class="">Architrave Material
                                            @if(!empty($tooltip->architraveMaterial))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->architraveMaterial}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                            <label for="architraveType" class="">Architrave Type
                                            @if(!empty($tooltip->architraveType))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->architraveType}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                            <label for="architraveWidth" class="">Architrave Width
                                            @if(!empty($tooltip->architraveWidth))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->architraveWidth}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input name="architraveWidth" id="architraveWidth"
                                                placeholder="Architrave Width" class="form-control" type="text">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="architraveDepth" class="">Architrave Depth
                                            @if(!empty($tooltip->architraveDepth))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->architraveDepth}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <input name="architraveDepth" id="architraveDepth"
                                                placeholder="Architrave Depth" class="form-control" type="text">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="architraveFinish" class="">Architrave Finish
                                            @if(!empty($tooltip->architraveFinish))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->architraveFinish}}'));
                                            </script>
                                            @endif
                                            </label>
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
                                            <label for="architraveSetQty" class="">Architrave Set Qty
                                            @if(!empty($tooltip->architraveSetQty))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->architraveSetQty}}'));
                                            </script>
                                            @endif
                                            </label>
                                            <select name="architraveSetQty" id="architraveSetQty" class="form-control">
                                                <option value="">Select Architrave Finish</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='Architrave_Set_Qty')
                                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
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



            <!-- Transport -->
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
                                        <label for="Vehicle Type" class="">Vehicle Type
                                        @if(!empty($tooltip->VehicleType))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->VehicleType}}'));
                                            </script>
                                            @endif
                                        </label>
                                        <select name="vehicleType" id="vehicleType" class="form-control">
                                            <option value="Curtainsider">Curtainsider</option>
                                            <option value="Opentop">Opentop</option>
                                            <option value="Tail Lift">Tail Lift</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="deliveryTime" class="">Delivery Time
                                        @if(!empty($tooltip->deliveryTime))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->deliveryTime}}'));
                                            </script>
                                            @endif
                                        </label>
                                        <select name="deliveryTime" id="deliveryTime" class="form-control">
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label for="packaging" class="">Packaging
                                        @if(!empty($tooltip->packaging))
                                            <script type="text/javascript">
                                            document.write(Tooltip('{{$tooltip->packaging}}'));
                                            </script>
                                            @endif
                                        </label>
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
                        @if(!empty($schedule->id)){{ 'Update Now' }} @else {{'Submit Now'}}  @endif
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
            <div hidden id="filter-iron-mongery-category">{{route('ironmongery-info/filter-iron-mongery-category')}}
            </div>
            <div hidden id="url">{{url('/')}}</div>
            <div hidden id="get-handing-options">{{route('items/get-handing-options')}}</div>


            <div hidden id="Filterintumescentseals">{{route('Filterintumescentseals')}}</div>

        </form>
    </div>

</div>













@endsection



@section('js')
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    // Temporary Code
    $(document).on('change','.LippingCalculation',function(e){
        e.preventDefault();
        const lippingThicknessValue = $('#lippingThickness').val();
        const leafWidth1Value = $('#leafWidth1').val();
        const leafWidth2Value = $('#leafWidth2').val();
        const leafHeightNoOPValue = $('#leafHeightNoOP').val();
        const fireRating = $('#fireRating').val();
        console.log(fireRating)
        return false;
        if(lippingThicknessValue != '' && leafWidth1Value != '' && leafWidth2Value != '' && leafHeightNoOPValue != '' && fireRating != ''){
            $.ajax({
                type: "POST",
                url: "{{route('TestGenerateBOM')}}",
                data: {
                    _token: $("#_token").val(),
                    lippingThicknessValue: lippingThicknessValue,
                    leafWidth1Value: leafWidth1Value,
                    leafWidth2Value: leafWidth2Value,
                    leafHeightNoOPValue: leafHeightNoOPValue,
                    fireRating: fireRating
                },
                success: function(data) {
                    console.log(data)
                }
            })
        }
    })
</script>

@endsection

<!-- Modal -->
<div class="modal fade" id="iron" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="display:block !important;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" id="modalTitle"></h5>
            </div>
            <div class="modal-body">
                <div class="row" id="content"></div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>



<div class="modal fade bd-example-modal-lg" id="frameMaterialModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="frameMaterialModalLabel">Frame Materials</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="frameMaterialModalBody">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="glazingModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="glazingModalLabel">All Glazing</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="inputId">
                <div id="glazingModalBody">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<!-- Door Frame Construction  -->
<div class="modal fade bd-example-modal-lg" id="DoorFrameConstructionModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="DoorFrameConstructionModalLabel">Door Frame Construction</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="inputId">
                <div id="DoorFrameConstructionModalBody">
                    <div class="container">
                        <div class="row">
                            @foreach($option_data as $row)
                                @if($row->OptionSlug=='Door_Frame_Construction')
                                    <div class="col-md-2 col-sm-4 col-6 cursor-pointer" onclick="DoorFrameConstruction('#frameCostuction','{{$row->OptionKey}}','{{$row->OptionValue}}')">
                                        <div class="color_box">
                                            <div class="frameMaterialImage">
                                                <img width="100%" height="100" src="{{url('/')}}/uploads/Options/{{$row->file}}">
                                            </div>
                                            <h4>{{$row->OptionValue}}</h4>
                                        </div>
                                    </div>
                                @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- @foreach($option_data as $row)
                        @if($row->OptionSlug=='Door_Frame_Construction')
                        <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                        @endif
                    @endforeach -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
