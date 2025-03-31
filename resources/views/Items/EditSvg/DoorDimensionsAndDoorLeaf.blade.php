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
                            <label for="sOWidth" class="">S.O Width
                                @if(!empty($tooltip->sOWidth))
                                <script type="text/javascript">
                                //(Tooltip('{{$tooltip->sOWidth}}'));
                                </script>
                                @endif
                            </label>
                            <input type="number" id="sOWidth" name="sOWidth"
                                value="@if(!empty($Item['SOWidth'])){{$Item['SOWidth']}}@endif"
                                class="form-control  change-event-calulation door-configuration" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="sOHeight" class="">S.O Height
                                @if(!empty($tooltip->sOHeight))
                                <script type="text/javascript">
                                //(Tooltip('{{$tooltip->sOHeight}}'));
                                </script>
                                @endif
                            </label>
                            <input type="number" id="sOHeight" name="sOHeight"
                                value="@if(!empty($Item['SOHeight'])){{$Item['SOHeight']}}@endif"
                                class="form-control change-event-calulation door-configuration" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="sODepth" class="">S.O Depth
                                @if(!empty($tooltip->sODepth))
                                <script type="text/javascript">
                                //(Tooltip('{{$tooltip->sODepth}}'));
                                </script>
                                @endif
                            </label>
                            <input type="number" id="sODepth" name="sODepth" class="form-control door-configuration"
                                value="@if(!empty($Item['SOWallThick'])){{$Item['SOWallThick']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="leafWidth1">Leaf Width 1
                                @if(!empty($tooltip->leafWidth1))
                                <script type="text/javascript">
                                //(Tooltip('{{$tooltip->leafWidth1}}'));
                                </script>
                                @endif
                            </label>
                            <input type="number" readonly id="leafWidth1" name="leafWidth1" readonly
                                class="form-control change-event-calulation forcoreWidth1 door-configuration"
                                value="@if(!empty($Item['LeafWidth1'])){{$Item['LeafWidth1']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="leafWidth2">Leaf Width 2
                                @if(!empty($tooltip->leafWidth2))
                                <script type="text/javascript">
                                //(Tooltip('{{$tooltip->leafWidth2}}'));
                                </script>
                                @endif
                            </label>
                            <input type="number" readonly id="leafWidth2" name="leafWidth2"
                                class="form-control forcoreWidth1 door-configuration"
                                value="@if(!empty($Item['LeafWidth2'])){{$Item['LeafWidth2']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="leafHeightNoOP" class="">Leaf Height
                                @if(!empty($tooltip->leafHeightNoOP))
                                <script type="text/javascript">
                                //(Tooltip('{{$tooltip->leafHeightNoOP}}'));
                                </script>
                                @endif
                            </label>
                            <input type="number" id="leafHeightNoOP" readonly name="leafHeightNoOP"
                                class="form-control leafHeightNoOP foroPWidth forcoreWidth1 door-configuration"
                                value="@if(!empty($Item['LeafHeight'])){{$Item['LeafHeight']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="doorThickness">Door Thickness (mm)
                                @if(!empty($tooltip->doorThickness))
                                <script type="text/javascript">
                                //(Tooltip('{{$tooltip->doorThickness}}'));
                                </script>
                                @endif
                            </label>
                            <div id="door_thickness_div">
                            <input type="number" readonly name="doorThickness" id="doorThickness" class="form-control"
                                value="@if(!empty($Item['LeafThickness'])){{$Item['LeafThickness']}}@endif">
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="doorLeafFacing">Door Leaf Facing
                                @if(!empty($tooltip->doorLeafFacing))
                                <script type="text/javascript">
                                //(Tooltip('{{$tooltip->doorLeafFacing}}'));
                                </script>
                                @endif
                            </label>
                            <select name="doorLeafFacing" id="doorLeafFacing" option_slug="Door_Leaf_Facing" class="form-control">
                                <option value="">Select door leaf facing</option>

                                @foreach($selected_option_data as $row)
                                @if($row->OptionSlug=='Door_Leaf_Facing')
                                <option value="{{$row->OptionKey}}" @if(isset($Item["DoorLeafFacing"]))
                                    @if($Item["DoorLeafFacing"]==$row->OptionKey){{'selected'}} @endif
                                    @endif>{{$row->OptionValue}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="doorLeafFacingValue">Brand
                                @if(!empty($tooltip->doorLeafFacingValue))
                                <script type="text/javascript">
                                //(Tooltip('{{$tooltip->doorLeafFacingValue}}'));
                                </script>
                                @endif
                            </label>
                            <select name="doorLeafFacingValue" id="doorLeafFacingValue" class="form-control">
                                <option value="">Select Brand</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group doorLeafFinishDiv">
                            <label for="doorLeafFacing">Door Leaf Finish
                                @if(!empty($tooltip->doorLeafFacing))
                                <script type="text/javascript">
                                //(Tooltip('{{$tooltip->doorLeafFacing}}'));
                                </script>
                                @endif
                            </label>
                            <select name="doorLeafFinish" id="doorLeafFinish" option_slug="door_leaf_finish"
                                class="form-control doorLeafFinishSelect">
                                <option value="">Select door leaf finish</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 SheenLevel" style="display:none;">
                        <div class="position-relative form-group">
                            <label for="">Sheen Level</label>
                            <input type="text" name="SheenLevel" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group input-icons">
                            <label for="doorLeafFinishColor">Door Leaf Finish Color
                                @if(!empty($tooltip->doorLeafFinishColor))
                                <script type="text/javascript">
                                //(Tooltip('{{$tooltip->doorLeafFinishColor}}'));
                                </script>
                                @endif
                            </label>
                            <i class="fa fa-info icon" id="doorLeafFinishColorIcon"></i>
                            <input type="text" @if(empty($Item['DoorLeafFinishColor'])){{'readonly'}}@endif name="doorLeafFinishColor" id="doorLeafFinishColor" class="form-control" value="@if(!empty($Item['DoorLeafFinishColor'])){{$Item['DoorLeafFinishColor']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="decorativeGroves">Decorative Groves
                                @if(!empty($tooltip->decorativeGroves))
                                <script type="text/javascript">
                                //(Tooltip('{{$tooltip->decorativeGroves}}'));
                                </script>
                                @endif
                            </label>
                            <select name="decorativeGroves" id="decorativeGroves" class="form-control" required>
                                <option value="">Select Decorative Groves</option>
                                @foreach($option_data as $row)
                                @if($row->OptionSlug=='Decorative_Groves')
                                <option value="{{$row->OptionKey}}"
                                    @if(isset($Item['DecorativeGroves']))
                                        @if($Item['DecorativeGroves']==$row->OptionKey)
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
                            <label for="grooveLocation">Groove Location
                                @if(!empty($tooltip->grooveLocation))
                                <script type="text/javascript">
                                //(Tooltip('{{$tooltip->grooveLocation}}'));
                                </script>
                                @endif
                            </label>
                            <select name="grooveLocation" id="grooveLocation" class="form-control">
                                <option value="" selected>Select Groves Location</option>
                                @foreach($option_data as $row)
                                @if($row->OptionSlug=='Groove_Location')
                                <option value="{{$row->OptionKey}}" @if(isset($Item['GrooveLocation']))
                                    @if($Item['GrooveLocation']==$row->OptionKey) {{'selected'}} @endif
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
                                //(Tooltip('{{$tooltip->grooveWidth}}'));
                                </script>
                                @endif
                            </label>
                            <input type="number" max="10" name="grooveWidth" id="grooveWidth" class="form-control"
                                value="@if(!empty($Item['GrooveWidth'])){{$Item['GrooveWidth']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="grooveDepth">Groove Depth
                                @if(!empty($tooltip->grooveDepth))
                                <script type="text/javascript">
                                //(Tooltip('{{$tooltip->grooveDepth}}'));
                                </script>
                                @endif
                            </label>
                            <input type="number" name="grooveDepth" id="grooveDepth" class="form-control"
                                value="@if(!empty($Item['GrooveDepth'])){{$Item['GrooveDepth']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="maxNumberOfGroove"> Maximum Number of Groove
                                @if(!empty($tooltip->maxNumberOfGroove))
                                <script type="text/javascript">
                                //(Tooltip('{{$tooltip->maxNumberOfGroove}}'));
                                </script>
                                @endif
                            </label>
                            <input type="number" pattern="\d*" name="maxNumberOfGroove" id="maxNumberOfGroove"
                                class="form-control" @if(isset($Item['GrooveLocation']))
                                @if($Item['GrooveLocation']=='Vertical_&_Horizontal' ) {{'readonly'}} @endif @endif
                                value="@if(!empty($Item['MaxNumberOfGroove'])){{$Item['MaxNumberOfGroove']}}@else{{'0'}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="numberOfGroove">Number of Grooves
                                @if(!empty($tooltip->numberOfGroove))
                                <script type="text/javascript">
                                //(Tooltip('{{$tooltip->numberOfGroove}}'));
                                </script>
                                @endif
                            </label>
                            <input type="number" name="numberOfGroove" @if(isset($Item['GrooveLocation']))
                                @if($Item['GrooveLocation']=='Vertical_&_Horizontal' ) {{'readonly'}} @endif @endif
                                id="numberOfGroove" class="form-control"
                                value="@if(isset($Item['NumberOfGroove'])){{$Item['NumberOfGroove']}}@else{{'0'}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="numberOfVerticalGroove">No. of Vertical Grooves(Max 4)
                                @if(!empty($tooltip->numberOfVerticalGroove))
                                <script type="text/javascript">
                                //(Tooltip('{{$tooltip->numberOfVerticalGroove}}'));
                                </script>
                                @endif
                            </label>
                            <input type="number" max="4" name="numberOfVerticalGroove"
                                @if(isset($Item['GrooveLocation'])) @if($Item['GrooveLocation']
                                !='Vertical_&_Horizontal' ) {{'readonly'}} @endif @else {{'readonly'}} @endif
                                id="numberOfVerticalGroove" class="form-control"
                                value="@if(isset($Item['NumberOfVerticalGroove'])){{$Item['NumberOfVerticalGroove']}}@else{{'0'}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="numberOfHorizontalGroove">No. of Horizontal Grooves(Max 4)
                                @if(!empty($tooltip->numberOfHorizontalGroove))
                                <script type="text/javascript">
                                //(Tooltip('{{$tooltip->numberOfHorizontalGroove}}'));
                                </script>
                                @endif
                            </label>
                            <input type="number" name="numberOfHorizontalGroove" @if(isset($Item['GrooveLocation']))
                                @if($Item['GrooveLocation'] !='Vertical_&_Horizontal' ) {{'readonly'}} @endif @else
                                {{'readonly'}} @endif id="numberOfHorizontalGroove" max="4" class="form-control"
                                value="@if(isset($Item['NumberOfHorizontalGroove'])){{$Item['NumberOfHorizontalGroove']}}@else{{'0'}}@endif">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
