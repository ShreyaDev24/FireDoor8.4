<!-- DOOR DIMENSIONS & DOOR LEAF -->
<div class="main-card mb-3 custom_card">
    <div>
        <div class="tab-content">
            <div class="card-header">
                <h5 class="card-title" style="margin-top: 10px">Door Dimensions & Door Leaf</h5><span></span>
            </div>
            <div>
                <div class="form-row">

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="doorLeafFacing">Door Leaf Facing
                                @if(!empty($tooltip->doorLeafFacing))
                                <script type="text/javascript">
                                    document.write(Tooltip('{{$tooltip->doorLeafFacing}}'));
                                </script>
                                @endif
                            </label>
                            <select name="doorLeafFacing" id="doorLeafFacing" option_slug="Door_Leaf_Facing" class="form-control doorLeafFacing1" required>
                                <option value="">Select Leaf Facing</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group input-icons">
                            <label for="doorLeafFinishColor">Door Leaf Finish
                                @if(!empty($tooltip->doorLeafFinishColor))
                                <script type="text/javascript">
                                document.write(Tooltip('{{$tooltip->doorLeafFinishColor}}'));
                                </script>
                                @endif
                            </label>
                            <i class="fa fa-info icon" id="doorLeafFinishColorIcon"></i>
                            <input type="text" readonly @if(empty($Item['DoorLeafFinishColor'])){{'readonly'}}@endif name="doorLeafFinishColor" id="doorLeafFinishColor" class="form-control" value="@if(!empty($Item['DoorLeafFinishColor'])){{$Item['DoorLeafFinishColor']}}@endif">
                        </div>
                    </div>
                    <!-- <div class="col-md-6">
                        <div class="position-relative form-group doorLeafFinishDiv">
                            <label for="doorLeafFacing">Door Leaf Finish
                                @if(!empty($tooltip->doorLeafFacing))
                                <script type="text/javascript">
                                    document.write(Tooltip('{{$tooltip->doorLeafFacing}}'));
                                </script>
                                @endif
                            </label>
                            <select name="doorLeafFinish" id="doorLeafFinish" option_slug="door_leaf_finish" class="form-control doorLeafFinish1 doorLeafFinishSelect">
                                <option value="">Select door leaf finish</option>
                            </select>
                        </div>
                    </div> -->


                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="DoorDimensions">Door Dimensions
                            @if(!empty($tooltip->DoorDimensions))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->glazingBeadSpecies}}'));
                            </script>
                            @endif

                            </label>

                            <div class="input-icons">
                                <i class="fa fa-info icon" data-toggle="modal" style="cursor:pointer" data-target="#DoorDimension" id="DoorDimensionsIcon"></i>
                                <input type="text" readonly name="DoorDimensionsCode" id="DoorDimensions"
                                    class="form-control bg-white" value="@if(isset($Item['DoorDimensionsCode'])){{$Item['DoorDimensionsCode']}}@endif">
                                <input type="hidden" name="DoorDimensions"  id="DoorDimensionId"
                                value="@if(isset($Item['DoorDimensions'])){{$Item['DoorDimensions']}}@endif">
                                @php
                                if( !empty($Item['LeafWidth1']) && !empty($Item['LeafHeight']) ){
                                    $lw = !empty($Item['AdjustmentLeafWidth1']) ? floatval($Item['LeafWidth1']) + floatval ($Item['AdjustmentLeafWidth1']) : $Item['LeafWidth1'] ;
                                    $lh = !empty($Item['AdjustmentLeafHeightNoOP']) ? floatval ($Item['LeafHeight']) + floatval ($Item['AdjustmentLeafHeightNoOP']) : $Item['LeafHeight'] ;

                                    $hv = $lw .",". $lh;
                                }else{
                                    $hv = '';
                                }
                                @endphp
                                <input type="hidden" id="doorDimensionHeightWidth" value="{{$hv}}">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6" id="DDLAH">

                        <div class="position-relative form-group">

                            <label for="DoorDimensions">Door Dimensions Leaf and a Half

                            @if(!empty($tooltip->DoorDimensions))

                            <script type="text/javascript">

                            document.write(Tooltip('{{$tooltip->glazingBeadSpecies}}'));

                            </script>

                            @endif



                            </label>



                            <div class="input-icons">

                                <i class="fa fa-info icon" data-toggle="modal" style="cursor:pointer" data-target="#DoorDimension2" id="DoorDimensionsIcon2"></i>

                                <input type="text" readonly name="DoorDimensionsCode2" id="DoorDimensions2"

                                    class="form-control bg-white" value="@if(isset($Item['DoorDimensionsCode2'])){{$Item['DoorDimensionsCode2']}}@endif">

                                <input type="hidden" name="DoorDimensions2"  id="DoorDimensionId2"

                                value="@if(isset($Item['DoorDimensions2'])){{$Item['DoorDimensions2']}}@endif">

                                @php

                                if( !empty($Item['LeafWidth2']) && !empty($Item['LeafHeight']) ){

                                    $lw = !empty($Item['AdjustmentLeafWidth2']) ? floatval($Item['LeafWidth2']) + floatval ($Item['AdjustmentLeafWidth2']) : $Item['LeafWidth2'] ;

                                    $lh = !empty($Item['AdjustmentLeafHeightNoOP']) ? floatval ($Item['LeafHeight']) + floatval ($Item['AdjustmentLeafHeightNoOP']) : $Item['LeafHeight'] ;



                                    $hv = $lw .",". $lh;

                                }else{

                                    $hv = '';

                                }

                                @endphp

                                <input type="hidden" id="doorDimensionHeightWidth2" value="{{$hv}}">

                            </div>

                        </div>

                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="leafWidth1">Leaf Width 1
                                @if(!empty($tooltip->leafWidth1))
                                <script type="text/javascript">
                                    document.write(Tooltip('{{$tooltip->leafWidth1}}'));
                                </script>
                                @endif
                            </label>
                            <input type="number" readonly id="leafWidth1" name="leafWidth1" readonly class="form-control change-event-calulation forcoreWidth1 door-configuration" value="@if(!empty($Item['LeafWidth1'])){{$Item['LeafWidth1']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="leafWidth1">Leaf Width 1 Adjustment</label>
                            <input type="number" id="adjustmentLeafWidth1" name="adjustmentLeafWidth1" class="form-control" value="@if(!empty($Item['AdjustmentLeafWidth1'])){{$Item['AdjustmentLeafWidth1']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="leafWidth2">Leaf Width 2
                                @if(!empty($tooltip->leafWidth2))
                                <script type="text/javascript">
                                    document.write(Tooltip('{{$tooltip->leafWidth2}}'));
                                </script>
                                @endif
                            </label>
                            <input type="number" readonly id="leafWidth2" name="leafWidth2" class="form-control forcoreWidth1 door-configuration LeaftypewithEmpty" value="@if(!empty($Item['LeafWidth2'])){{$Item['LeafWidth2']}}@endif">
                        </div>
                    </div>

                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="leafWidth2">Leaf Width 2 Adjustment</label>
                            <input type="number" readonly id="adjustmentLeafWidth2" name="adjustmentLeafWidth2" class="form-control" value="@if(!empty($Item['AdjustmentLeafWidth2'])){{$Item['AdjustmentLeafWidth2']}}@endif">
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
                            <input type="number" id="leafHeightNoOP" readonly name="leafHeightNoOP" class="form-control leafHeightNoOP foroPWidth forcoreWidth1 door-configuration" value="@if(!empty($Item['LeafHeight'])){{$Item['LeafHeight']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="position-relative form-group">
                            <label for="leafHeightNoOP" class="">Leaf Height Adjustment </label>
                            <input type="number" id="adjustmentLeafHeightNoOP" name="adjustmentLeafHeightNoOP" class="form-control" value="@if(!empty($Item['AdjustmentLeafHeightNoOP'])){{$Item['AdjustmentLeafHeightNoOP']}}@endif">
                        </div>
                    </div>


                    <div class="col-md-6 framehideshow">
                        <div class="position-relative form-group">
                            <label for="sOWidth" class="">S.O Width
                                @if(!empty($tooltip->sOWidth))
                                <script type="text/javascript">
                                    document.write(Tooltip('{{$tooltip->sOWidth}}'));
                                </script>
                                @endif
                            </label>
                            <input type="number" id="sOWidth" readonly name="sOWidth" value="@if(!empty($Item['SOWidth'])){{$Item['SOWidth']}}@endif" class="form-control  change-event-calulation door-configuration" required>
                        </div>
                    </div>
                    <div class="col-md-6 framehideshow">
                        <div class="position-relative form-group">
                            <label for="sOHeight" class="">S.O Height
                                @if(!empty($tooltip->sOHeight))
                                <script type="text/javascript">
                                    document.write(Tooltip('{{$tooltip->sOHeight}}'));
                                </script>
                                @endif
                            </label>
                            <input type="number" id="sOHeight" readonly name="sOHeight" value="@if(!empty($Item['SOHeight'])){{$Item['SOHeight']}}@endif" class="form-control change-event-calulation door-configuration" required>
                        </div>
                    </div>
                    <div class="col-md-6 framehideshow">
                        <div class="position-relative form-group">
                            <label for="sODepth" class="">S.O Depth
                                @if(!empty($tooltip->sODepth))
                                <script type="text/javascript">
                                    document.write(Tooltip('{{$tooltip->sODepth}}'));
                                </script>
                                @endif
                            </label>
                            <input type="number" id="sODepth" name="sODepth" class="form-control door-configuration" value="@if(!empty($Item['SOWallThick'])){{$Item['SOWallThick']}}@endif" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="doorThickness">Door Thickness (mm)
                                @if(!empty($tooltip->doorThickness))
                                <script type="text/javascript">
                                    document.write(Tooltip('{{$tooltip->doorThickness}}'));
                                </script>
                                @endif
                            </label>
                            <div id="door_thickness_div">
                                <input type="number" readonly name="doorThickness" id="doorThickness" class="form-control" value="@if(!empty($Item['LeafThickness'])){{$Item['LeafThickness']}}@endif">
                            </div>
                        </div>
                    </div>
                    {{-- ADD HINGE LOCATION (15-12-2023) --}}
                    <div class="col-md-6 framehideshow">
                        <div class="position-relative form-group">
                            <label for="hinge1Location">Hinge 1 Location (Min 100 mm, Max 180 mm)
                                @if(!empty($tooltip->hing1))
                                   <script type="text/javascript">
                                   document.write(Tooltip('{{$tooltip->gap}}'));
                                   </script>
                                @endif
                            </label>
                            <div id="hinge_location_div">
                            @if(!empty($Item['hinge1Location']))
                            <input type="number" name="hinge1Location" id="hinge1Location" class="form-control change-event-calulation" value="@if(!empty($Item['hinge1Location'])){{$Item['hinge1Location']}}@endif" min="100" max="180">
                            @else
                            <input type="number" name="hinge1Location" id="hinge1Location" class="form-control change-event-calulation" value="@if(!empty($hinge_location)){{$hinge_location->hinge1Location}}@endif" min="100" max="180">
                            @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 framehideshow">
                        <div class="position-relative form-group">
                            <label for="hinge2Location">Hinge 2 Location (Min 200 mm)
                                @if(!empty($tooltip->hing2))
                                   <script type="text/javascript">
                                   document.write(Tooltip('{{$tooltip->gap}}'));
                                   </script>
                                @endif
                            </label>
                            <div id="hinge_location_div">
                                @if(!empty($Item['hinge2Location']))
                            <input type="number" name="hinge2Location" id="hinge2Location" class="form-control change-event-calulation" value="@if(!empty($Item['hinge2Location'])){{$Item['hinge2Location']}}@endif" min="200">
                            @else
                            <input type="number" name="hinge2Location" id="hinge2Location" class="form-control change-event-calulation" value="@if(!empty($hinge_location)){{$hinge_location->hinge2Location}}@endif" min="200">
                            @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 framehideshow">
                        <div class="position-relative form-group">
                            <label for="hinge3Location" id="hinge3LocationLabel">Hinge 3 Location  (Min 150 mm, Max 250 mm)
                                @if(!empty($tooltip->hing3))
                                   <script type="text/javascript">
                                   document.write(Tooltip('{{$tooltip->gap}}'));
                                   </script>
                                @endif
                            </label>
                            <div id="hinge_location_div">
                            @if(!empty($Item['hinge3Location']))
                            <input type="number" name="hinge3Location" id="hinge3Location" class="form-control change-event-calulation" value="@if(!empty($Item['hinge3Location'])){{$Item['hinge3Location']}}@endif" min="150" max="250">
                            @else
                            <input type="number" name="hinge3Location" id="hinge3Location" class="form-control change-event-calulation" value="@if(!empty($hinge_location)){{$hinge_location->hinge3Location}}@endif" min="150" max="250">
                            @endif

                            </div>
                        </div>
                    </div>
                    <div id="hing4LocationDiv" class="col-md-6 d-none framehideshow">
                        <div class="position-relative form-group ">
                            <label for="hinge4Location" id="hinge4LocationLabel">Hinge 3 Location (Min 200 mm)</label>
                            <div id="hinge_location_div">
                            <input type="number" name="hinge4Location" id="hinge4Location" class="form-control change-event-calulation" value="@if(!empty($Item['hinge4Location'])){{$Item['hinge4Location']}}@endif" min="200">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6 framehideshow">
                        <div class="position-relative form-group d-flex">
                            <label for="hingeCenterCheck">Hinge Center</label>
                            @if(!empty($Item['hinge3Location']))
                            <input type="checkbox" name="hingeCenterCheck" id="hingeCenterCheck" class="change-event-calulation form-control" style="margin: 2px -4px 10px 12px;border: 1px solid rgb(206, 212, 218);display: inline-block;height: 15px;width: 15px;" value="1" @if(!empty($Item['hingeCenterCheck']) && $Item['hingeCenterCheck']==1){{'checked'}}@endif>
                            @else
                            <input type="checkbox" name="hingeCenterCheck" id="hingeCenterCheck" class="change-event-calulation form-control" style="margin: 2px -4px 10px 12px;border: 1px solid rgb(206, 212, 218);display: inline-block;height: 15px;width: 15px;" value="1" @if(!empty($hinge_location) && $hinge_location->hingeCenterCheck == 1){{'checked'}}@endif>
                            @endif
                        </div>
                    </div>

                    <div class="col-md-6 framehideshow">
                        <div class="position-relative form-group d-flex">
                            <label for="fourthHinges">4th Hinges</label>
                            <input type="checkbox" name="fourthHinges" id="fourthHinges" class="form-control" style="margin: 2px -4px 10px 12px;border: 1px solid rgb(206, 212, 218);display: inline-block;height: 15px;width: 15px;" value="1" @if(!empty($Item['fourthHinges']) && $Item['fourthHinges'] == 1){{'checked'}}@endif>
                        </div>
                    </div>



                    {{-- <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="doorLeafFacing">Door Leaf Facing
                                @if(!empty($tooltip->doorLeafFacing))
                                <script type="text/javascript">
                                document.write(Tooltip('{{$tooltip->doorLeafFacing}}'));
                    </script>
                    @endif
                    </label>
                    <select name="doorLeafFacing" id="doorLeafFacing" option_slug="Door_Leaf_Facing" class="form-control">
                        <option value="">Select door leaf facing</option>

                        @foreach($selected_option_data as $row)
                        @if($row->OptionSlug=='Door_Leaf_Facing')
                        <option value="{{$row->OptionKey}}" @if(isset($Item["DoorLeafFacing"])) @if($Item["DoorLeafFacing"]==$row->OptionKey){{'selected'}} @endif
                            @endif>{{$row->OptionValue}}
                        </option>
                        @endif
                        @endforeach
                        @foreach($option_data as $row)
                        @if($row->OptionKey != 'Kraft_Paper')
                        @if($row->OptionSlug=='Door_Leaf_Facing')
                        <option value="{{$row->OptionKey}}" @if(isset($Item["DoorLeafFacing"])) @if($Item["DoorLeafFacing"]==$row->OptionKey){{'selected'}} @endif
                            @endif>{{$row->OptionValue}}
                        </option>
                        @endif
                        @endif
                        @endforeach
                    </select>
                </div>
            </div> --}}


            <div class="col-md-6" style="display: none;">
                <div class="position-relative form-group">
                    <label for="doorLeafFacingValue">Brand
                        @if(!empty($tooltip->doorLeafFacingValue))
                        <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->doorLeafFacingValue}}'));
                        </script>
                        @endif
                    </label>
                    <select name="doorLeafFacingValue" id="doorLeafFacingValue" class="form-control">
                        <option value="">Select Brand</option>
                    </select>
                </div>
            </div>

            <div class="col-md-6 SheenLevel" style="display:none;">
                <div class="position-relative form-group">
                    <label for="">Sheen Level</label>
                    <input type="text" name="SheenLevel" class="form-control">
                </div>
            </div>
          {{--  <div class="col-md-6" style="display: none;">
                <div class="position-relative form-group input-icons">
                    <label for="doorLeafFinishColor">Door Leaf Finish Color
                        @if(!empty($tooltip->doorLeafFinishColor))
                        <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->doorLeafFinishColor}}'));
                        </script>
                        @endif
                    </label>
                    <i class="fa fa-info icon" id="doorLeafFinishColorIcon"></i>
                    <input type="text" @if(empty($Item['DoorLeafFinishColor'])){{'readonly'}}@endif name="doorLeafFinishColor" id="doorLeafFinishColor" class="form-control" value="@if(!empty($Item['DoorLeafFinishColor'])){{$Item['DoorLeafFinishColor']}}@endif">
                </div>
            </div> --}}
            <div class="col-md-6">
                <div class="position-relative form-group">
                    <label for="decorativeGroves">Decorative Groves
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
                        <option value="{{$row->OptionKey}}" @if(isset($Item['DecorativeGroves'])) @if($Item['DecorativeGroves']==$row->OptionKey)
                            {{'selected'}}
                            @endif
                            @elseif($row->OptionKey == "No")
                            {{'selected'}}
                            @endif>{{$row->OptionValue}}
                        </option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-md-6">
                <div class="position-relative form-group">
                    <label for="doorDimensionGroove">Grooves Icon
                    @if(!empty($tooltip->DoorDimensions))
                    <script type="text/javascript">
                    document.write(Tooltip('{{$tooltip->doorDimensionGroove}}'));
                    </script>
                    @endif

                    </label>
                    <div class="input-icons">
                        <i class="fa fa-info icon" data-toggle="modal" style="cursor:pointer"   id="GroovesIcon"></i>
                        <input type="text" disabled readonly name="doorDimensionGroove" id="doorDimensionGroove"
                            class="form-control bg-white"
                            value="@if(isset($Item['groovesNumber'])){{$Item['groovesNumber']}}@endif">
                        {{-- <input type="hidden" name="doorDimensionGroove"> --}}

                    </div>
                </div>
            </div>

            <div class="col-md-6" style="display: none;">
                <div class="position-relative form-group">
                    <label for="grooveLocation">Groove Location
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
                        <option value="{{$row->OptionKey}}" @if(isset($Item['GrooveLocation'])) @if($Item['GrooveLocation']==$row->OptionKey) {{'selected'}} @endif
                            @endif>{{$row->OptionValue}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="position-relative form-group">
                    <label for="grooveWidth">Groove Width(Max 10 mm)
                        @if(!empty($tooltip->grooveWidth))
                        <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->grooveWidth}}'));
                        </script>
                        @endif
                    </label>
                    <input type="number" max="10" name="grooveWidth" id="grooveWidth" class="form-control" value="@if(!empty($Item['GrooveWidth'])){{$Item['GrooveWidth']}}@endif">
                </div>
            </div>
            <div class="col-md-6">
                <div class="position-relative form-group">
                    <label for="grooveDepth">Groove Depth
                        @if(!empty($tooltip->grooveDepth))
                        <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->grooveDepth}}'));
                        </script>
                        @endif
                    </label>
                    <input type="number" name="grooveDepth" id="grooveDepth" class="form-control" value="@if(!empty($Item['GrooveDepth'])){{$Item['GrooveDepth']}}@endif">
                </div>
            </div>
            <div class="col-md-6">
                <div class="position-relative form-group">
                    <label for="maxNumberOfGroove"> Maximum Number of Groove
                        @if(!empty($tooltip->maxNumberOfGroove))
                        <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->maxNumberOfGroove}}'));
                        </script>
                        @endif
                    </label>
                    <input type="number" pattern="\d*" name="maxNumberOfGroove" id="maxNumberOfGroove" class="form-control" @if(isset($Item['GrooveLocation'])) @if($Item['GrooveLocation']=='Vertical_&_Horizontal' ) {{'readonly'}} @endif @endif value="@if(!empty($Item['MaxNumberOfGroove'])){{$Item['MaxNumberOfGroove']}}@else{{'0'}}@endif">
                </div>
            </div>
            <div class="col-md-6">
                <div class="position-relative form-group">
                    <label for="numberOfGroove">Number of Grooves
                        @if(!empty($tooltip->numberOfGroove))
                        <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->numberOfGroove}}'));
                        </script>
                        @endif
                    </label>
                    <input type="number" name="numberOfGroove" @if(isset($Item['GrooveLocation'])) @if($Item['GrooveLocation']=='Vertical_&_Horizontal' ) {{'readonly'}} @endif @endif id="numberOfGroove" class="form-control" value="@if(isset($Item['NumberOfGroove'])){{$Item['NumberOfGroove']}}@else{{'0'}}@endif">
                </div>
            </div>
            <div class="col-md-6" style="display: none;">
                <div class="position-relative form-group">
                    <label for="numberOfVerticalGroove">No. of Vertical Grooves(Max 4)
                        @if(!empty($tooltip->numberOfVerticalGroove))
                        <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->numberOfVerticalGroove}}'));
                        </script>
                        @endif
                    </label>
                    <input type="number" max="4" name="numberOfVerticalGroove" @if(isset($Item['GrooveLocation'])) @if($Item['GrooveLocation'] !='Vertical_&_Horizontal' ) {{'readonly'}} @endif @else {{'readonly'}} @endif id="numberOfVerticalGroove" class="form-control" value="@if(isset($Item['NumberOfVerticalGroove'])){{$Item['NumberOfVerticalGroove']}}@else{{'0'}}@endif">
                </div>
            </div>
            <div class="col-md-6" style="display: none;">
                <div class="position-relative form-group">
                    <label for="numberOfHorizontalGroove">No. of Horizontal Grooves(Max 4)
                        @if(!empty($tooltip->numberOfHorizontalGroove))
                        <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->numberOfHorizontalGroove}}'));
                        </script>
                        @endif
                    </label>
                    <input type="number" name="numberOfHorizontalGroove" @if(isset($Item['GrooveLocation'])) @if($Item['GrooveLocation'] !='Vertical_&_Horizontal' ) {{'readonly'}} @endif @else {{'readonly'}} @endif id="numberOfHorizontalGroove" max="4" class="form-control" value="@if(isset($Item['NumberOfHorizontalGroove'])){{$Item['NumberOfHorizontalGroove']}}@else{{'0'}}@endif">
                </div>
            </div>

            <div class="col-md-6">
                <div class="position-relative form-group">
                    <label for="decorativeGroves">Is decorative Groove on leaf 2 Active?
                        @if(!empty($tooltip->decorativeGroves))
                        <script type="text/javascript">
                        document.write(Tooltip('{{$tooltip->decorativeGroves}}'));
                        </script>
                        @endif
                    </label>
                    <select name="DecorativeGrovesLeaf2" id="DecorativeGrovesLeaf2" class="form-control" required
                    @if(isset($Item['DoorsetType']) && $Item['DoorsetType'] == 'SD'){{ 'disabled' }}@endif>
                        <option value="">decorative Groove on leaf 2 Active? </option>
                        @foreach($option_data as $row)
                        @if($row->OptionSlug=='Decorative_Groves_leaf2')
                        <option value="{{$row->OptionKey}}"
                            @if(isset($Item['DecorativeGrovesLeaf2']))
                                @if($Item['DecorativeGrovesLeaf2']==$row->OptionKey)
                                    {{'selected'}}
                                @endif
                            @elseif($row->OptionKey == "No")
                            {{'selected'}}
                            @endif>{{$row->OptionValue}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="position-relative form-group">
                    <label for="decorativeGroves">Is decorative Groove the same as leaf 1?
                        @if(!empty($tooltip->decorativeGroves))
                        <script type="text/javascript">
                        document.write(Tooltip('{{$tooltip->decorativeGroves}}'));
                        </script>
                        @endif
                    </label>
                    <select name="IsSameAsDecorativeGroves1" id="IsSameAsDecorativeGroves1" class="form-control">
                        <option value="">decorative Groove the same as leaf 1?</option>
                        @foreach($option_data as $row)
                        @if($row->OptionSlug=='IsSameAsDecorativeGroves1')
                        <option value="{{$row->OptionKey}}"
                            @if(isset($Item['IsSameAsDecorativeGroves1']))
                                @if($Item['IsSameAsDecorativeGroves1']==$row->OptionKey)
                                    {{'selected'}}
                                @endif
                            @elseif($row->OptionKey == "No")
                                {{'selected'}}
                            @endif>{{$row->OptionValue}}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="position-relative form-group">
                    <label for="doorDimensionGroove">Grooves Icon Leaf2
                    @if(!empty($tooltip->DoorDimensions))
                    <script type="text/javascript">
                    document.write(Tooltip('{{$tooltip->doorDimensionGroove}}'));
                    </script>
                    @endif

                    </label>
                    <div class="input-icons">
                        <i class="fa fa-info icon" data-toggle="modal" style="cursor:pointer"   id="GroovesIconLeaf2"></i>
                        <input type="text" disabled readonly name="DoorDimensionGrooveLeaf2" id="DoorDimensionGrooveLeaf2"
                            class="form-control bg-white" value="@if(isset($Item['GroovesNumberLeaf2'])){{$Item['GroovesNumberLeaf2']}}@endif">
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="position-relative form-group">
                    <label for="grooveWidth">Groove Width Leaf2(Max 10 mm)
                        @if(!empty($tooltip->grooveWidth))
                        <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->grooveWidth}}'));
                        </script>
                        @endif
                    </label>
                    <input type="number" max="10" name="GrooveWidthLeaf2" id="GrooveWidthLeaf2" class="form-control" value="@if(!empty($Item['GrooveWidthLeaf2'])){{$Item['GrooveWidthLeaf2']}}@endif">
                </div>
            </div>
            <div class="col-md-6">
                <div class="position-relative form-group">
                    <label for="grooveDepth">Groove Depth Leaf2
                        @if(!empty($tooltip->grooveDepth))
                        <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->grooveDepth}}'));
                        </script>
                        @endif
                    </label>
                    <input type="number" name="GrooveDepthLeaf2" id="GrooveDepthLeaf2" class="form-control" value="@if(!empty($Item['GrooveDepthLeaf2'])){{$Item['GrooveDepthLeaf2']}}@endif">
                </div>
            </div>
            <div class="col-md-6">
                <div class="position-relative form-group">
                    <label for="maxNumberOfGroove"> Maximum Number of Groove Leaf2
                        @if(!empty($tooltip->maxNumberOfGroove))
                        <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->maxNumberOfGroove}}'));
                        </script>
                        @endif
                    </label>
                    <input type="number" pattern="\d*" name="MaxNumberOfGrooveLeaf2" id="MaxNumberOfGrooveLeaf2" class="form-control" @if(isset($Item['GrooveLocation'])) @if($Item['GrooveLocation']=='Vertical_&_Horizontal' ) {{'readonly'}} @endif @endif value="@if(!empty($Item['MaxNumberOfGrooveLeaf2'])){{$Item['MaxNumberOfGrooveLeaf2']}}@else{{'0'}}@endif">
                </div>
            </div>
            <div class="col-md-6">
                <div class="position-relative form-group">
                    <label for="numberOfGroove">Number of Grooves Leaf2
                        @if(!empty($tooltip->numberOfGroove))
                        <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->numberOfGroove}}'));
                        </script>
                        @endif
                    </label>
                    <input type="number" name="NumberOfGrooveLeaf2" @if(isset($Item['GrooveLocation'])) @if($Item['GrooveLocation']=='Vertical_&_Horizontal' ) {{'readonly'}} @endif @endif id="NumberOfGrooveLeaf2" class="form-control" value="@if(isset($Item['NumberOfGrooveLeaf2'])){{$Item['NumberOfGrooveLeaf2']}}@else{{'0'}}@endif">
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<input type="hidden" id="door_dimension_url" value="{{route('filter-door-dimensions')}}">
<input type="hidden" id="door_dimension_url_leaf" value="{{route('filter-door-dimensions-leaf')}}">
