<div class="form-row">
    <div class="col-md-6 framehideshow">
        <div class="position-relative form-group">
            <label for="IntumescentLeafType">Leaf Type</label>
            <input required type="text" readonly id="IntumescentLeafType" value="Leaf Type 1 (44mm)" name="IntumescentLeafType" class="form-control for_c_leaf_height  forleafHeightNoOP change-event-calulation door-configuration" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="fireRating">Fire Rating
            </label>
            <select name="fireRating" id="fireRating" class="form-control" required>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='fire_rating' && ($row->OptionKey == 'FD30' || $row->OptionKey == 'FD30s'))
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsCustom->FireRating))
                    @if($defaultItemsCustom->FireRating==$row->OptionKey){{'selected'}} @endif
                    @endif>{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
            <input type="hidden" name="pageIdentity" id="pageIdentity" value="1">
            <div hidden id="glazing-system-filter">{{route('items/glazing-system-filter')}}</div>
            <div hidden id="architrave-system-filter">{{route('items/architrave-system-filter')}}</div>
            <div hidden id="fire-rating-filter">{{route('items/fire-rating-filter')}}</div>
            <div hidden id="glazing-beads-filter">{{route('items/glazing-beads-filter')}}</div>
            <div hidden id="glass-type-filter">{{route('items/glass-type-filter')}}</div>
            <div hidden id="glazing-thikness-filter">{{route('items/glazing-thikness-filter')}}</div>
            <div hidden id="frame-material-filter">{{route('items/frame-material-filter')}}</div>
            <div hidden id="scallopped-lipping-thickness">{{route('items/scallopped-lipping-thickness')}}
            </div>
            <div hidden id="flat-lipping-thickness">{{route('items/flat-lipping-thickness')}}</div>
            <div hidden id="rebated-lipping-thickness">{{route('items/rebated-lipping-thickness')}}</div>
            <div hidden id="door-thickness-filter">{{route('items/door-thickness-filter')}}</div>
            <div hidden id="door-leaf-face-value-filter">{{route('items/door-leaf-face-value-filter')}}
            </div>
            <div hidden id="ral-color-filter">{{route('items/ral-color-filter')}}</div>
            <div hidden id="filter-iron-mongery-category">
                {{route('ironmongery-info/filter-iron-mongery-category')}}
            </div>
            <div hidden id="url">{{url('/')}}</div>
            <div hidden id="get-handing-options">{{route('items/get-handing-options')}}</div>
            <div hidden id="liping-glazing-system-filter">{{route('items/liping-glazing-system-filter')}}</div>
            {{--  <div hidden id="Filterintumescentseals">{{route('Filterintumescentseals')}}</div>  --}}
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="doorsetType">Doorset Type</label>
            <!-- combination_of -->
            <select name="doorsetType" id="doorsetType"
                class="form-control combination_of change-event-calulation door-configuration" required>
                <option value="">Select door set type</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='door_configuration_doorset_type')
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsCustom->DoorsetType))
                    @if($defaultItemsCustom->DoorsetType==$row->OptionKey){{'selected'}} @endif @endif
                    >{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="swingType">Swing Type</label>
            <select name="swingType" id="swingType" class="form-control combination_of" required>
                <option value="">Select swing type</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='door_configuration_swing_type')
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsCustom->SwingType))
                    @if($defaultItemsCustom->SwingType==$row->OptionKey) {{'selected'}} @endif @endif
                    >{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="latchType">Latch Type</label>
            <select name="latchType" id="latchType" class="form-control combination_of mylatch">
                <option value="">Select latch type</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='door_configuration_latch_type')
                <option value="{{$row->OptionKey}}" @if(!empty($defaultItemsCustom->LatchType))
                    @if($defaultItemsCustom->LatchType==$row->OptionKey) {{'selected'}} @endif @endif
                    >{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="Handing">Handing
            </label>
            <select name="Handing" id="Handing" class="form-control">
                <option value="">Select Handing</option>
            </select>
            <div id="Handing-value" data-value="@if(isset($defaultItemsCustom->Handing)){{$defaultItemsCustom->Handing}}@else{{''}}@endif" hidden=""></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="OpensInwards">Pull Towards
            </label>
            <select required name="OpensInwards" id="OpensInwards" class="form-control">
                <option value="">Select Pull Towards</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='Opens_Inwards')
                <option value="{{$row->OptionKey}}" @if(!empty($defaultItemsCustom->OpensInwards))
                    @if($defaultItemsCustom->OpensInwards==$row->OptionKey) {{'selected'}} @endif
                    @endif>{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6 framehideshow">
        <div class="position-relative form-group">
            <label for="tollerance">Tollerance</label>
            <input required type="number" min="0" max="20" step="any" id="tollerance" value="@if(isset($defaultItemsCustom->Tollerance)){{$defaultItemsCustom->Tollerance}}@else{{'7'}}@endif" name="tollerance"
                class="form-control for_c_leaf_height  forleafHeightNoOP change-event-calulation door-configuration" required>
        </div>
    </div>
    <div class="col-md-6 framehideshow" id="floor_finish">
        <div class="position-relative form-group">
            <label for="floorFinish">Floor Finish</label>
            <input type="number" id="floorFinish" name="floorFinish" value="@if(isset($defaultItemsCustom->FloorFinish)){{$defaultItemsCustom->FloorFinish}}@else{{'5'}}@endif"
                class="form-control change-event-calulation forundercut door-configuration" required>
        </div>
    </div>
    <div class="col-md-6 framehideshow">
        <div class="position-relative form-group">
            <label for="gap">GAP (Min:2 Max:4)</label>
            <input type="number" min="2" max="4" id="gap" name="gap" value="@if(isset($defaultItemsCustom->GAP)){{$defaultItemsCustom->GAP}}@else{{'3'}}@endif"
                class="form-control for_c_leaf_height  forleafHeightNoOP change-event-calulation door-configuration" required>
        </div>
    </div>
    <div class="col-md-6 framehideshow">
        <div class="position-relative form-group">
            <label for="frameThickness">Frame Thickness</label>
            <input type="number" id="frameThickness" name="frameThickness" value="@if(isset($defaultItemsCustom->FrameThickness)){{$defaultItemsCustom->FrameThickness}}@else{{'32'}}@endif" class="form-control change-event-calulation door-configuration" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="doorLeafFacing">Door Leaf Facing</label>
            <select name="doorLeafFacing" id="doorLeafFacing" option_slug="Door_Leaf_Facing" class="form-control">
                <option value="">Select door leaf facing</option>

                @foreach($selected_option_data as $row)
                @if($row->OptionSlug=='Door_Leaf_Facing')
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsCustom->DoorLeafFacing))
                    @if($defaultItemsCustom->DoorLeafFacing==$row->OptionKey){{'selected'}} @endif
                    @endif>{{$row->OptionValue}}
                </option>
                @endif
                @endforeach
                @foreach($option_data as $row)
                @if($row->OptionKey != 'Kraft_Paper')
                @if($row->OptionSlug=='Door_Leaf_Facing')
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsCustom->DoorLeafFacing))
                    @if($defaultItemsCustom->DoorLeafFacing==$row->OptionKey){{'selected'}} @endif
                    @endif>{{$row->OptionValue}}
                </option>
                @endif
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="doorLeafFacingValue">Brand</label>
            <select name="doorLeafFacingValue" id="doorLeafFacingValue" class="form-control">
                <option value="">Select Brand</option>
            </select>
            <div id="DoorLeafFacingValue-value" data-value="@if(isset($defaultItemsCustom->DoorLeafFacingValue)){{$defaultItemsCustom->DoorLeafFacingValue}}@else{{''}}@endif" hidden=""></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group doorLeafFinishDiv">
            <label for="doorLeafFacing">Door Leaf Finish</label>
            <select name="doorLeafFinish" id="doorLeafFinish" option_slug="door_leaf_finish"
                class="form-control doorLeafFinishSelect">
                <option value="">Select door leaf finish</option>
            </select>
            <div id="DoorLeafFinish-value" data-value="@if(isset($defaultItemsCustom->DoorLeafFinish)){{$defaultItemsCustom->DoorLeafFinish}}@else{{''}}@endif" hidden=""></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="leaf1VisionPanel">Leaf 1 Vision Panel</label>
            <select  name="leaf1VisionPanel" id="leaf1VisionPanel"
                class="form-control change-event-calulation door-configuration">
                <option value=""> Is Vision Panel active? </option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='leaf1_vision_panel')
                <option value="{{$row->OptionKey}}"
                    @if(isset($defaultItemsCustom->Leaf1VisionPanel))
                        @if($defaultItemsCustom->Leaf1VisionPanel == $row->OptionKey)
                            {{'selected'}}
                        @endif
                    @elseif($row->OptionKey == 'No')
                        {{'selected'}}
                    @endif

                >{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="leaf1VisionPanelShape">Vision Panel Shape</label>
            <select  name="leaf1VisionPanelShape" id="leaf1VisionPanelShape"
                class="form-control change-event-calulation door-configuration">
                <option value="">select any shape </option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='Vision_panel_shape')
                <option value="{{$row->OptionKey}}"
                    @if(isset($defaultItemsCustom->Leaf1VisionPanelShape))
                        @if($defaultItemsCustom->Leaf1VisionPanelShape == $row->OptionKey)
                            {{'selected'}}
                        @endif
                    @endif>{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="visionPanelQuantity">Vision Panel Quantity</label>
            <select name="visionPanelQuantity"
                id="visionPanelQuantity"
                @if(empty($defaultItemsCustom->VisionPanelQuantity)) {{'disabled'}} @endif
                class="form-control change-event-calulation door-configuration">

                <option value="">Select quantity</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='leaf1_vision_panel_Qty')
                <option value="{{$row->OptionKey}}"
                    @if(isset($defaultItemsCustom->VisionPanelQuantity))
                        @if($defaultItemsCustom->VisionPanelQuantity == $row->OptionKey)
                            {{'selected'}}
                        @endif
                    @endif>{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="AreVPsEqualSizes">Are VP's equal sizes?</label>
            <select name="AreVPsEqualSizes"
                id="AreVPsEqualSizes"
                @if(empty($defaultItemsCustom->AreVPsEqualSizesForLeaf1)) {{'disabled'}} @endif
                class="form-control change-event-calulation door-configuration">
                <option value="">Are Visible panel equll size?</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='leaf1are_vps_equal_sizes')
                <option value="{{$row->OptionKey}}"
                    @if(isset($defaultItemsCustom->AreVPsEqualSizesForLeaf1))
                        @if($defaultItemsCustom->AreVPsEqualSizesForLeaf1 == $row->OptionKey)
                            {{'selected'}}
                        @endif
                    @endif>{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="distanceFromTopOfDoor">Distance from top of door</label>
            <input type="number"
                @if(!isset($defaultItemsCustom->DistanceFromtopOfDoor)) {{'readonly'}} @endif
                min="100"
                name="distanceFromTopOfDoor" id="distanceFromTopOfDoor"
                class="form-control door-configuration"
        value="@if(isset($defaultItemsCustom->DistanceFromtopOfDoor)){{$defaultItemsCustom->DistanceFromtopOfDoor}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="distanceFromTheEdgeOfDoor">Distance from the
                edge</label>
            <input type="number"
                @if(!isset($defaultItemsCustom->DistanceFromTheEdgeOfDoor)) {{'readonly'}} @endif
                min="100" name="distanceFromTheEdgeOfDoor" id="distanceFromTheEdgeOfDoor"
                class="form-control door-configuration" value="@if(isset($defaultItemsCustom->DistanceFromTheEdgeOfDoor)){{$defaultItemsCustom->DistanceFromTheEdgeOfDoor}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="distanceBetweenVPs">Distance between VP's</label>
            <input type="number"
                min="80"
                @if(!isset($defaultItemsCustom->DistanceBetweenVPs)) {{'readonly'}} @endif
                name="distanceBetweenVPs"
                id="distanceBetweenVPs" class="form-control door-configuration"
                value="@if(isset($defaultItemsCustom->DistanceBetweenVPs)){{$defaultItemsCustom->DistanceBetweenVPs}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP1Width">Leaf 1 VP Width</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsCustom->Leaf1VPWidth)) {{'readonly'}} @endif
                name="vP1Width"
                id="vP1Width"
                class="form-control change-event-calulation door-configuration"
                value="@if(isset($defaultItemsCustom->Leaf1VPWidth)){{$defaultItemsCustom->Leaf1VPWidth}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP1Height1">Leaf 1 VP Height (1)</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsCustom->Leaf1VPHeight1)) {{'readonly'}} @endif
                name="vP1Height1"
                id="vP1Height1"
                class="form-control change-event-calulation door-configuration"
                value="@if(isset($defaultItemsCustom->Leaf1VPHeight1)){{$defaultItemsCustom->Leaf1VPHeight1}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP1Height2">Leaf 1 VP Height (2)</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsCustom->Leaf1VPHeight2) || (isset($defaultItemsCustom->AreVPsEqualSizesForLeaf1) && $defaultItemsCustom->AreVPsEqualSizesForLeaf1 == "Yes")) {{'readonly'}} @endif
                name="vP1Height2"
                id="vP1Height2"
                class="form-control change-event-calulation door-configuration"
                value="@if(isset($defaultItemsCustom->Leaf1VPHeight2)){{$defaultItemsCustom->Leaf1VPHeight2}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP1Height3">Leaf 1 VP Height (3)</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsCustom->Leaf1VPHeight3) || (isset($defaultItemsCustom->AreVPsEqualSizesForLeaf1) && $defaultItemsCustom->AreVPsEqualSizesForLeaf1 == "Yes")) {{'readonly'}} @endif
                name="vP1Height3"
                id="vP1Height3"
                class="form-control change-event-calulation door-configuration"
                value="@if(isset($defaultItemsCustom->Leaf1VPHeight3)){{$defaultItemsCustom->Leaf1VPHeight3}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP1Height4">Leaf 1 VP Height (4)</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsCustom->Leaf1VPHeight4) || (isset($defaultItemsCustom->AreVPsEqualSizesForLeaf1) && $defaultItemsCustom->AreVPsEqualSizesForLeaf1 == "Yes")) {{'readonly'}} @endif
                name="vP1Height4"
                id="vP1Height4"
                class="form-control change-event-calulation door-configuration"
                value="@if(isset($defaultItemsCustom->Leaf1VPHeight4)){{$defaultItemsCustom->Leaf1VPHeight4}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP1Height5">Leaf 1 VP Height (5)</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsCustom->Leaf1VPHeight5) || (isset($defaultItemsCustom->AreVPsEqualSizesForLeaf1) && $defaultItemsCustom->AreVPsEqualSizesForLeaf1 == "Yes")) {{'readonly'}} @endif
                name="vP1Height5"
                id="vP1Height5"
                class="form-control change-event-calulation door-configuration"
                value="@if(isset($defaultItemsCustom->Leaf1VPHeight5)){{$defaultItemsCustom->Leaf1VPHeight5}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="leaf1VpAreaSizeM2"> Leaf 1 VP Area Size m2</label>
            <input type="number"
                min="0"
                readonly
                name="leaf1VpAreaSizeM2"
                id="leaf1VpAreaSizeM2" class="form-control"
                value="@if(isset($defaultItemsCustom->Leaf1VPAreaSizem2)){{$defaultItemsCustom->Leaf1VPAreaSizem2}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="leaf2VisionPanel">Leaf 2 Vision Panel</label>
            <select name="leaf2VisionPanel"
                id="leaf2VisionPanel"
                @if(!isset($defaultItemsCustom->Leaf2VisionPanel)) {{'disabled'}} @endif
                class="form-control door-configuration">
                <option value=""> Is Vision Panel fr leaf 2 active? </option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='leaf1_vision_panel')
                <option value="{{$row->OptionKey}}"


                    @if(isset($defaultItemsCustom->Leaf2VisionPanel))
                        @if($defaultItemsCustom->Leaf2VisionPanel == $row->OptionKey)
                            {{'selected'}}
                        @endif
                    @elseif($row->OptionKey == 'No')
                        {{'selected'}}
                    @endif

                    >{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vpSameAsLeaf1">Is VP same as Leaf 1?</label>
            <select name="vpSameAsLeaf1"
                id="vpSameAsLeaf1"
                @if(!isset($defaultItemsCustom->sVPSameAsLeaf1)) {{'disabled'}} @endif
                class="form-control door-configuration">
                <option value="">Is VP same as Leaf 1?</option>
                @foreach($option_data as $row)
                    @if($row->OptionSlug=='leaf1are_vps_equal_sizes')
                    <option value="{{$row->OptionKey}}"
                        @if(isset($defaultItemsCustom->sVPSameAsLeaf1))
                            @if($defaultItemsCustom->sVPSameAsLeaf1 == $row->OptionKey)
                                {{'selected'}}
                            @endif
                        @endif>{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="visionPanelQuantityforLeaf2" >Leaf 2 Vision Panel Quantity</label>
            <select name="visionPanelQuantityforLeaf2"
                id="visionPanelQuantityforLeaf2"
                @if(!isset($defaultItemsCustom->Leaf2VisionPanelQuantity) || (isset($defaultItemsCustom->sVPSameAsLeaf1) && $defaultItemsCustom->sVPSameAsLeaf1 == "Yes"))
                {{'disabled'}}
                @endif
                class="form-control door-configuration">
                <option value="">Select quantity</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='leaf1_vision_panel_Qty')
                <option value="{{$row->OptionKey}}"
                    @if(isset($defaultItemsCustom->Leaf2VisionPanelQuantity))
                        @if($defaultItemsCustom->Leaf2VisionPanelQuantity == $row->OptionKey)
                            {{'selected'}}
                        @endif
                    @endif>{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="AreVPsEqualSizesForLeaf2">Are VP's equal sizes for leaf
                2?</label>
            <select name="AreVPsEqualSizesForLeaf2"
                id="AreVPsEqualSizesForLeaf2"
                @if(!isset($defaultItemsCustom->AreVPsEqualSizesForLeaf2) || (isset($defaultItemsCustom->sVPSameAsLeaf1) && $defaultItemsCustom->sVPSameAsLeaf1 == "Yes"))
                {{'disabled'}}
                @endif
                class="form-control door-configuration">
                <option value="">Are Visible panel equll size?</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='leaf1are_vps_equal_sizes')
                <option value="{{$row->OptionKey}}"
                    @if(isset($defaultItemsCustom->AreVPsEqualSizesForLeaf2))
                        @if($defaultItemsCustom->AreVPsEqualSizesForLeaf2 == $row->OptionKey)
                            {{'selected'}}
                        @endif
                    @endif>{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="distanceFromTopOfDoorforLeaf2">Distance from top of door for Leaf 2</label>
            <input type="number"
                @if(!isset($defaultItemsCustom->DistanceFromTopOfDoorForLeaf2) || (isset($defaultItemsCustom->sVPSameAsLeaf1) && $defaultItemsCustom->sVPSameAsLeaf1 == "Yes")) {{'readonly'}} @endif
                min="100"
                name="distanceFromTopOfDoorforLeaf2" id="distanceFromTopOfDoorforLeaf2"
                class="form-control door-configuration"
                value="@if(isset($defaultItemsCustom->DistanceFromTopOfDoorForLeaf2)){{$defaultItemsCustom->DistanceFromTopOfDoorForLeaf2}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="distanceFromTheEdgeOfDoorforLeaf2">Distance from the
                edge for Leaf 2</label>
            <input type="number"
                @if(!isset($defaultItemsCustom->DistanceFromTheEdgeOfDoorforLeaf2) || (isset($defaultItemsCustom->sVPSameAsLeaf1) && $defaultItemsCustom->sVPSameAsLeaf1 == "Yes")) {{'readonly'}} @endif
                min="100"
                name="distanceFromTheEdgeOfDoorforLeaf2"
                id="distanceFromTheEdgeOfDoorforLeaf2" class="form-control door-configuration"
                value="@if(isset($defaultItemsCustom->DistanceFromTheEdgeOfDoorforLeaf2)){{$defaultItemsCustom->DistanceFromTheEdgeOfDoorforLeaf2}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="distanceBetweenVPsforLeaf2">Distance between VP's</label>
            <input type="number"
                min="80"
                @if(!isset($defaultItemsCustom->DistanceBetweenVp) || (isset($defaultItemsCustom->sVPSameAsLeaf1) && $defaultItemsCustom->sVPSameAsLeaf1 == "Yes")) {{'readonly'}} @endif
                name="distanceBetweenVPsforLeaf2"
                id="distanceBetweenVPsforLeaf2" class="form-control door-configuration"
                value="@if(isset($defaultItemsCustom->DistanceBetweenVp)){{$defaultItemsCustom->DistanceBetweenVp}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP2Width">Leaf 2 VP Width</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsCustom->Leaf2VPWidth) || (isset($defaultItemsCustom->sVPSameAsLeaf1) && $defaultItemsCustom->sVPSameAsLeaf1 == "Yes")) {{'readonly'}} @endif
                name="vP2Width"
                id="vP2Width"
                class="form-control"
                value="@if(isset($defaultItemsCustom->Leaf2VPWidth)){{$defaultItemsCustom->Leaf2VPWidth}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP2Height1">Leaf 2 VP Height (1)</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsCustom->Leaf2VPHeight1) || (isset($defaultItemsCustom->sVPSameAsLeaf1) && $defaultItemsCustom->sVPSameAsLeaf1 == "Yes")) {{'readonly'}} @endif
                name="vP2Height1"
                id="vP2Height1"
                class="form-control door-configuration"
                value="@if(isset($defaultItemsCustom->Leaf2VPHeight1)){{$defaultItemsCustom->Leaf2VPHeight1}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP2Height2">Leaf 2 VP Height (2)</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsCustom->Leaf2VPHeight2) || (isset($defaultItemsCustom->sVPSameAsLeaf1) && $defaultItemsCustom->sVPSameAsLeaf1 == "Yes") || (isset($defaultItemsCustom->AreVPsEqualSizesForLeaf2) && $defaultItemsCustom->AreVPsEqualSizesForLeaf2 == "Yes")) {{'readonly'}} @endif
                name="vP2Height2"
                id="vP2Height2"
                class="form-control door-configuration"
                value="@if(isset($defaultItemsCustom->Leaf2VPHeight2)){{$defaultItemsCustom->Leaf2VPHeight2}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP2Height3">Leaf 2 VP Height (3)</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsCustom->Leaf2VPHeight3) || (isset($defaultItemsCustom->sVPSameAsLeaf1) && $defaultItemsCustom->sVPSameAsLeaf1 == "Yes") || (isset($defaultItemsCustom->AreVPsEqualSizesForLeaf2) && $defaultItemsCustom->AreVPsEqualSizesForLeaf2 == "Yes")) {{'readonly'}} @endif
                name="vP2Height3"
                id="vP2Height3"
                class="form-control door-configuration"
                value="@if(isset($defaultItemsCustom->Leaf2VPHeight3)){{$defaultItemsCustom->Leaf2VPHeight3}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP2Height4">Leaf 2 VP Height (4)</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsCustom->Leaf2VPHeight4) || (isset($defaultItemsCustom->sVPSameAsLeaf1) && $defaultItemsCustom->sVPSameAsLeaf1 == "Yes") || (isset($defaultItemsCustom->AreVPsEqualSizesForLeaf2) && $defaultItemsCustom->AreVPsEqualSizesForLeaf2 == "Yes")) {{'readonly'}} @endif
                name="vP2Height4"
                id="vP2Height4"
                class="form-control door-configuration"
                value="@if(isset($defaultItemsCustom->Leaf2VPHeight4)){{$defaultItemsCustom->Leaf2VPHeight4}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP2Height5">Leaf 2 VP Height (5)</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsCustom->Leaf2VPHeight5) || (isset($defaultItemsCustom->sVPSameAsLeaf1) && $defaultItemsCustom->sVPSameAsLeaf1 == "Yes") || (isset($defaultItemsCustom->AreVPsEqualSizesForLeaf2) && $defaultItemsCustom->AreVPsEqualSizesForLeaf2 == "Yes")) {{'readonly'}} @endif
                name="vP2Height5"
                id="vP2Height5"
                class="form-control door-configuration"
                value="@if(isset($defaultItemsCustom->Leaf2VPHeight5)){{$defaultItemsCustom->Leaf2VPHeight5}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group input-icons">
            <label for="frameMaterial">Frame Material</label>
            <i class="fa fa-info icon" id="frameMaterialIcon"></i>
            <input type="text"  readonly id="frameMaterial"
                class="form-control bg-white"
                value="@if(isset($defaultItemsCustom->FrameMaterial)){{$defaultItemsCustom->FrameMaterial}}@endif">
            <input type="hidden" id="frameMaterialNew"
                name="frameMaterial"
                value="@if(isset($defaultItemsCustom->FrameMaterial)){{$defaultItemsCustom->FrameMaterial}}@endif">
        </div>

    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="frameType">Frame Type</label>
            <select name="frameType" required id="frameType" class="form-control">
                <option value="">Select Frame Type</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='Frame_Type')
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsCustom->FrameType)) @if($defaultItemsCustom->FrameType == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
            <div id="LippingSpecies-value" data-value="@if(isset($defaultItemsCustom->LippingSpecies)){{$defaultItemsCustom->LippingSpecies}}@endif" hidden=""></div>
            <div id="FrameMaterial-value" data-value="@if(isset($defaultItemsCustom->FrameMaterial)){{$defaultItemsCustom->FrameMaterial}}@endif" hidden=""></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="frameDepth">Frame Depth (min 70)</label>
            <input type="text" name="frameDepth" id="frameDepth" class="form-control"
                value="@if(isset($defaultItemsCustom->FrameDepth)){{$defaultItemsCustom->FrameDepth}}@endif" min="70">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="frameFinish">Frame Finish</label>
            <select name="frameFinish" id="frameFinish"
                class="form-control change-event-calulation">
                <option value="">Select Frame finish</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='Frame_Finish')
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsCustom->FrameFinish)) @if($defaultItemsCustom->FrameFinish == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="framefinishColor">Frame Finish Color</label>
            <select name="framefinishColor" id="framefinishColor" class="form-control">
                <option value="">Frame Finish</option>
                <!-- @foreach($option_data as $row)
                    @if($row->OptionSlug=='door_leaf_finish')
                    <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                    @endif
                @endforeach -->
            </select>
            <input type="hidden" id="FrameFinishColor-value" value="@if(isset($defaultItemsCustom->FrameFinishColor)){{$defaultItemsCustom->FrameFinishColor}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group input-icons">
            <label for="frameCostuction">Door Frame Construction</label>
            @if(isset($defaultItemsCustom->DoorFrameConstruction) && $defaultItemsCustom->DoorFrameConstruction != "")
                @foreach($option_data as $row)
                    @if($row->OptionSlug=='Door_Frame_Construction')
                        @if($row->OptionKey == $defaultItemsCustom->DoorFrameConstruction)
                            <?php $DoorFrameConstruction = $row->OptionValue; ?>
                        @endif
                    @endif
                @endforeach
            @endif
            <i class="fa fa-info icon cursor-pointer" id="frameCostuction" onClick="$('#DoorFrameConstructionModal').modal('show')"></i>
            <input type="text" readonly id="frameCostuction" value="@if(isset($DoorFrameConstruction)){{$DoorFrameConstruction}}@endif" class="form-control bg-white">
            <input type="hidden" name="frameCostuction" value="@if(isset($defaultItemsCustom->DoorFrameConstruction)){{$defaultItemsCustom->DoorFrameConstruction}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="lippingType">Lipping Type</label>
            <select name="lippingType" required  @if(isset($defaultItemsCustom->FireRating) && $defaultItemsCustom->FireRating != "NFR"){{'required'}} @endif id="lippingType" class="form-control">
                <option value="">Select Lipping Types</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='lipping_type')
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsCustom->LippingType)) @if($defaultItemsCustom->LippingType == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="lippingThickness">Lipping Thickness</label>
            <select name="lippingThickness" required @if(isset($defaultItemsCustom->FireRating) && $defaultItemsCustom->FireRating != "NFR"){{'required'}} @endif id="lippingThickness"
                class="form-control forcoreWidth1 door-configuration" onchange="$('#lippingSpecies').val('')">
                <option value="">Select leaping thickness</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='lipping_thickness')
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsCustom->LippingThickness)) @if($defaultItemsCustom->LippingThickness == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="position-relative form-group input-icons">
            <label for="lippingSpecies">Lipping Species</label>
            <i class="fa fa-info icon" id="lippingSpeciesIcon"  onClick=""></i>
            <input type="text" required  @if(isset($defaultItemsCustom->FireRating) && $defaultItemsCustom->FireRating != "NFR"){{'required'}} @endif readonly id="lippingSpecies"
                class="form-control bg-white door-configuration">
            <input type="hidden" name="lippingSpecies" id="lippingSpeciesid" value="@if(isset($defaultItemsCustom->LippingSpecies)){{$defaultItemsCustom->LippingSpecies}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="intumescentSealType">Intumescent Seal Type</label>
            <select name="intumescentSealType" id="intumescentSealType"
                class="form-control">
                <option value="">Select Intumescent seal type</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='intumescent_seal_type')
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsCustom->IntumescentLeapingSealType)) @if($defaultItemsCustom->IntumescentLeapingSealType == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="intumescentSealLocation">Intumescent Seal Location</label>
            <select name="intumescentSealLocation" id="intumescentSealLocation"
                class="form-control">
                <option value="">Select Intumescent seal Location</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='IntumescentSeal_location')
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsCustom->IntumescentLeapingSealLocation)) @if($defaultItemsCustom->IntumescentLeapingSealLocation == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="intumescentSealColor">Intumescent Seal Color</label>
            <label for="intumescent_Seal_Color" style="display: none;">Intumescent Seal Color</label>
            <select name="intumescentSealColor" id="intumescentSealColor"  option_slug = "Intumescent_Seal_Color"
                class="form-control">
                <option value="">Select Intumescent seal Color</option>
                @foreach($intumescentSealColor as $row)
                    <option value="{{$row->Key}}" @if(isset($defaultItemsCustom->IntumescentLeapingSealColor)) @if($defaultItemsCustom->IntumescentLeapingSealColor == $row->Key) {{'selected'}} @endif @endif>{{$row->IntumescentSealColor}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="Architrave">Architrave</label>
            <select name="Architrave" id="Architrave" class="form-control">
                <option value="">Select Architrave</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='Architrave')
                <option value="{{$row->OptionKey}}"
                    @if(isset($defaultItemsCustom->Architrave))
                        @if($defaultItemsCustom->Architrave == $row->OptionKey)
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
        <div class="position-relative form-group input-icons">
            <label for="architraveMaterial">Architrave Material</label>
            <i class="fa fa-info icon cursor-pointer" id="architraveMaterialIcon"></i>
            <input  type="text" id="architraveMaterial" class="form-control" readonly  value="@if(isset($defaultItemsCustom->ArchitraveMaterial)){{$defaultItemsCustom->ArchitraveMaterial}}@endif">
            <input type="hidden" name="architraveMaterial" id="architraveMaterialNew"
                value="@if(isset($defaultItemsCustom->ArchitraveMaterial)){{$defaultItemsCustom->ArchitraveMaterial}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="architraveType">Architrave Type</label>
            <select  name="architraveType" id="architraveType" option_slug = "Architrave_Type"
                @if(isset($defaultItemsCustom->Architrave))
                    @if($defaultItemsCustom->Architrave != "Yes")
                        {{'disabled'}}
                    @endif
                @else
                    {{'disabled'}}
                @endif
                class="form-control">
                <option value="">Select Architrave Type</option>
                @foreach($ArchitraveType as $row)
                    <option value="{{$row->Key}}" @if(isset($defaultItemsCustom->ArchitraveType)) @if($defaultItemsCustom->ArchitraveType == $row->Key) {{'selected'}} @endif @endif>{{$row->ArchitraveType}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="architraveWidth">Architrave Width</label>
            <input name="architraveWidth" id="architraveWidth"
                @if(isset($defaultItemsCustom->Architrave))
                    @if($defaultItemsCustom->Architrave != "Yes")
                        {{'readonly'}}
                    @endif
                @else
                    {{'readonly'}}
                @endif
                placeholder="Architrave Width" class="form-control" type="text"
                value="@if(isset($defaultItemsCustom->ArchitraveWidth)){{$defaultItemsCustom->ArchitraveWidth}}@endif">
        </div>
    </div>

    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="architraveHeight">Architrave Thickness</label>
            <label for="architrave_Height" style="display: none;">Architrave Thickness</label>
            <input name="architraveHeight" id="architraveHeight"
                @if(isset($defaultItemsCustom->Architrave))
                    @if($defaultItemsCustom->Architrave != "Yes")
                        {{'readonly'}}
                    @endif
                @else
                    {{'readonly'}}
                @endif
                placeholder="Architrave Thickness" class="form-control" type="text"
                value="@if(isset($defaultItemsCustom->ArchitraveHeight)){{$defaultItemsCustom->ArchitraveHeight}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="architraveFinish">Architrave Finish</label>
            <select name="architraveFinish" id="architraveFinish"
                @if(isset($defaultItemsCustom->Architrave))
                    @if($defaultItemsCustom->Architrave != "Yes")
                        {{'disabled'}}
                    @endif
                @else
                    {{'disabled'}}
                @endif
                class="form-control">
                <option value="">Select Architrave Finish</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='Architrave_Finish')
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsCustom->ArchitraveFinish)) @if($defaultItemsCustom->ArchitraveFinish == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group input-icons">
            <label for="architravefinishColor">Architrave Finish color</label>
            <i class="fa fa-info icon cursor-pointer" id="architraveFinishcolorIcon"></i>

            <input type="text" id="architraveFinishcolor" name="architraveFinishcolor" class="form-control"  @if(empty($defaultItemsCustom->ArchitraveFinishColor)){{'readonly'}}@endif value="@if(isset($defaultItemsCustom->ArchitraveFinishColor)){{$defaultItemsCustom->ArchitraveFinishColor}}@endif">


        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="architraveSetQty">Architrave Set Qty</label>
            <select name="architraveSetQty" id="architraveSetQty"
                @if(isset($defaultItemsCustom->Architrave))
                    @if($defaultItemsCustom->Architrave != "Yes")
                        {{'disabled'}}
                    @endif
                @else
                    {{'disabled'}}
                @endif
                class="form-control">
                <option value="">Select Architrave Finish</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='Architrave_Set_Qty')
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsCustom->ArchitraveSetQty)) @if($defaultItemsCustom->ArchitraveSetQty == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <button class="btn btn-primary float-right" type="submit">Submit</button>
    </div>
</div>
