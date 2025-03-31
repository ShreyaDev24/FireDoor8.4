<div class="form-row">
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="fireRating">Fire Rating
            </label>
            <select name="fireRating" id="StandardfireRating" class="form-control" required>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='fire_rating' && ($row->OptionKey == 'FD30' || $row->OptionKey == 'FD30s'))
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsStandard->FireRating))
                    @if($defaultItemsStandard->FireRating==$row->OptionKey){{'selected'}} @endif
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
            {{--  <div hidden id="Filterintumescentseals">{{route('Filterintumescentseals')}}</div>  --}}
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="doorsetType">Doorset Type</label>
            <!-- combination_of -->
            <select name="doorsetType" id="standarddoorsetType"
                class="form-control combination_of change-event-calulation door-configuration" required>
                <option value="">Select door set type</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='door_configuration_doorset_type')
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsStandard->DoorsetType))
                    @if($defaultItemsStandard->DoorsetType==$row->OptionKey){{'selected'}} @endif @endif
                    >{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="swingType">Swing Type</label>
            <select name="swingType" id="standardswingType" class="form-control combination_of" required>
                <option value="">Select swing type</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='door_configuration_swing_type')
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsStandard->SwingType))
                    @if($defaultItemsStandard->SwingType==$row->OptionKey) {{'selected'}} @endif @endif
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
                <option value="{{$row->OptionKey}}" @if(!empty($defaultItemsStandard->LatchType))
                    @if($defaultItemsStandard->LatchType==$row->OptionKey) {{'selected'}} @endif @endif
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
            <select name="Handing" id="standardHanding" class="form-control">
                <option value="">Select Handing</option>
            </select>
            <div id="standardHanding-value" data-value="@if(isset($defaultItemsStandard->Handing)){{$defaultItemsStandard->Handing}}@else{{''}}@endif" hidden=""></div>
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
                <option value="{{$row->OptionKey}}" @if(!empty($defaultItemsStandard->OpensInwards))
                    @if($defaultItemsStandard->OpensInwards==$row->OptionKey) {{'selected'}} @endif
                    @endif>{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6 framehideshow">
        <div class="position-relative form-group">
            <label for="tollerance">Tollerance</label>
            <input required type="number" min="0" max="20" step="any" id="tollerance" value="@if(isset($defaultItemsStandard->Tollerance)){{$defaultItemsStandard->Tollerance}}@else{{'7'}}@endif" name="tollerance"
                class="form-control for_c_leaf_height  forleafHeightNoOP change-event-calulation door-configuration" required>
        </div>
    </div>
    <div class="col-md-6 framehideshow" id="floor_finish">
        <div class="position-relative form-group">
            <label for="floorFinish">Floor Finish</label>
            <input type="number" id="floorFinish" name="floorFinish" value="@if(isset($defaultItemsStandard->FloorFinish)){{$defaultItemsStandard->FloorFinish}}@else{{'5'}}@endif"
                class="form-control change-event-calulation forundercut door-configuration" required>
        </div>
    </div>
    <div class="col-md-6 framehideshow">
        <div class="position-relative form-group">
            <label for="gap">GAP (Min:2 Max:4)</label>
            <input type="number" min="2" max="4" id="gap" name="gap" value="@if(isset($defaultItemsStandard->GAP)){{$defaultItemsStandard->GAP}}@else{{'3'}}@endif"
                class="form-control for_c_leaf_height  forleafHeightNoOP change-event-calulation door-configuration" required>
        </div>
    </div>
    <div class="col-md-6 framehideshow">
        <div class="position-relative form-group">
            <label for="frameThickness">Frame Thickness</label>
            <input type="number" id="frameThickness" name="frameThickness" value="@if(isset($defaultItemsStandard->FrameThickness)){{$defaultItemsStandard->FrameThickness}}@else{{'32'}}@endif" class="form-control change-event-calulation door-configuration" required>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="leaf1VisionPanel">Leaf 1 Vision Panel</label>
            <select  name="leaf1VisionPanel" id="standardleaf1VisionPanel"
                class="form-control change-event-calulation door-configuration">
                <option value=""> Is Vision Panel active? </option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='leaf1_vision_panel')
                <option value="{{$row->OptionKey}}"
                    @if(isset($defaultItemsStandard->Leaf1VisionPanel))
                        @if($defaultItemsStandard->Leaf1VisionPanel == $row->OptionKey)
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
            <select  name="leaf1VisionPanelShape" id="standardleaf1VisionPanelShape"
                class="form-control change-event-calulation door-configuration">
                <option value="">select any shape </option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='Vision_panel_shape')
                <option value="{{$row->OptionKey}}"
                    @if(isset($defaultItemsStandard->Leaf1VisionPanelShape))
                        @if($defaultItemsStandard->Leaf1VisionPanelShape == $row->OptionKey)
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
                id="standardvisionPanelQuantity"
                @if(empty($defaultItemsStandard->VisionPanelQuantity)) {{'disabled'}} @endif
                class="form-control change-event-calulation door-configuration">

                <option value="">Select quantity</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='leaf1_vision_panel_Qty')
                <option value="{{$row->OptionKey}}"
                    @if(isset($defaultItemsStandard->VisionPanelQuantity))
                        @if($defaultItemsStandard->VisionPanelQuantity == $row->OptionKey)
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
                id="standardAreVPsEqualSizes"
                @if(empty($defaultItemsStandard->AreVPsEqualSizesForLeaf1)) {{'disabled'}} @endif
                class="form-control change-event-calulation door-configuration">
                <option value="">Are Visible panel equll size?</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='leaf1are_vps_equal_sizes')
                <option value="{{$row->OptionKey}}"
                    @if(isset($defaultItemsStandard->AreVPsEqualSizesForLeaf1))
                        @if($defaultItemsStandard->AreVPsEqualSizesForLeaf1 == $row->OptionKey)
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
                @if(!isset($defaultItemsStandard->DistanceFromtopOfDoor)) {{'readonly'}} @endif
                min="100"
                name="distanceFromTopOfDoor" id="standarddistanceFromTopOfDoor"
                class="form-control door-configuration"
        value="@if(isset($defaultItemsStandard->DistanceFromtopOfDoor)){{$defaultItemsStandard->DistanceFromtopOfDoor}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="distanceFromTheEdgeOfDoor">Distance from the
                edge</label>
            <input type="number"
                @if(!isset($defaultItemsStandard->DistanceFromTheEdgeOfDoor)) {{'readonly'}} @endif
                min="100" name="distanceFromTheEdgeOfDoor" id="standarddistanceFromTheEdgeOfDoor"
                class="form-control door-configuration" value="@if(isset($defaultItemsStandard->DistanceFromTheEdgeOfDoor)){{$defaultItemsStandard->DistanceFromTheEdgeOfDoor}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="distanceBetweenVPs">Distance between VP's</label>
            <input type="number"
                min="80"
                @if(!isset($defaultItemsStandard->DistanceBetweenVPs)) {{'readonly'}} @endif
                name="distanceBetweenVPs"
                id="standarddistanceBetweenVPs" class="form-control door-configuration"
                value="@if(isset($defaultItemsStandard->DistanceBetweenVPs)){{$defaultItemsStandard->DistanceBetweenVPs}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP1Width">Leaf 1 VP Width</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsStandard->Leaf1VPWidth)) {{'readonly'}} @endif
                name="vP1Width"
                id="standardvP1Width"
                class="form-control change-event-calulation door-configuration"
                value="@if(isset($defaultItemsStandard->Leaf1VPWidth)){{$defaultItemsStandard->Leaf1VPWidth}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP1Height1">Leaf 1 VP Height (1)</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsStandard->Leaf1VPHeight1)) {{'readonly'}} @endif
                name="vP1Height1"
                id="standardvP1Height1"
                class="form-control change-event-calulation door-configuration"
                value="@if(isset($defaultItemsStandard->Leaf1VPHeight1)){{$defaultItemsStandard->Leaf1VPHeight1}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP1Height2">Leaf 1 VP Height (2)</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsStandard->Leaf1VPHeight2) || (isset($defaultItemsStandard->AreVPsEqualSizesForLeaf1) && $defaultItemsStandard->AreVPsEqualSizesForLeaf1 == "Yes")) {{'readonly'}} @endif
                name="vP1Height2"
                id="standardvP1Height2"
                class="form-control change-event-calulation door-configuration"
                value="@if(isset($defaultItemsStandard->Leaf1VPHeight2)){{$defaultItemsStandard->Leaf1VPHeight2}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP1Height3">Leaf 1 VP Height (3)</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsStandard->Leaf1VPHeight3) || (isset($defaultItemsStandard->AreVPsEqualSizesForLeaf1) && $defaultItemsStandard->AreVPsEqualSizesForLeaf1 == "Yes")) {{'readonly'}} @endif
                name="vP1Height3"
                id="standardvP1Height3"
                class="form-control change-event-calulation door-configuration"
                value="@if(isset($defaultItemsStandard->Leaf1VPHeight3)){{$defaultItemsStandard->Leaf1VPHeight3}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP1Height4">Leaf 1 VP Height (4)</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsStandard->Leaf1VPHeight4) || (isset($defaultItemsStandard->AreVPsEqualSizesForLeaf1) && $defaultItemsStandard->AreVPsEqualSizesForLeaf1 == "Yes")) {{'readonly'}} @endif
                name="vP1Height4"
                id="standardvP1Height4"
                class="form-control change-event-calulation door-configuration"
                value="@if(isset($defaultItemsStandard->Leaf1VPHeight4)){{$defaultItemsStandard->Leaf1VPHeight4}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP1Height5">Leaf 1 VP Height (5)</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsStandard->Leaf1VPHeight5) || (isset($defaultItemsStandard->AreVPsEqualSizesForLeaf1) && $defaultItemsStandard->AreVPsEqualSizesForLeaf1 == "Yes")) {{'readonly'}} @endif
                name="vP1Height5"
                id="standardvP1Height5"
                class="form-control change-event-calulation door-configuration"
                value="@if(isset($defaultItemsStandard->Leaf1VPHeight5)){{$defaultItemsStandard->Leaf1VPHeight5}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="leaf1VpAreaSizeM2"> Leaf 1 VP Area Size m2</label>
            <input type="number"
                min="0"
                readonly
                name="leaf1VpAreaSizeM2"
                id="standardleaf1VpAreaSizeM2" class="form-control"
                value="@if(isset($defaultItemsStandard->Leaf1VPAreaSizem2)){{$defaultItemsStandard->Leaf1VPAreaSizem2}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="leaf2VisionPanel">Leaf 2 Vision Panel</label>
            <select name="leaf2VisionPanel"
                id="standardleaf2VisionPanel"
                @if(!isset($defaultItemsStandard->Leaf2VisionPanel)) {{'disabled'}} @endif
                class="form-control door-configuration">
                <option value=""> Is Vision Panel fr leaf 2 active? </option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='leaf1_vision_panel')
                <option value="{{$row->OptionKey}}"


                    @if(isset($defaultItemsStandard->Leaf2VisionPanel))
                        @if($defaultItemsStandard->Leaf2VisionPanel == $row->OptionKey)
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
                id="standardvpSameAsLeaf1"
                @if(!isset($defaultItemsStandard->sVPSameAsLeaf1)) {{'disabled'}} @endif
                class="form-control door-configuration">
                <option value="">Is VP same as Leaf 1?</option>
                @foreach($option_data as $row)
                    @if($row->OptionSlug=='leaf1are_vps_equal_sizes')
                    <option value="{{$row->OptionKey}}"
                        @if(isset($defaultItemsStandard->sVPSameAsLeaf1))
                            @if($defaultItemsStandard->sVPSameAsLeaf1 == $row->OptionKey)
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
                id="standardvisionPanelQuantityforLeaf2"
                @if(!isset($defaultItemsStandard->Leaf2VisionPanelQuantity) || (isset($defaultItemsStandard->sVPSameAsLeaf1) && $defaultItemsStandard->sVPSameAsLeaf1 == "Yes"))
                {{'disabled'}}
                @endif
                class="form-control door-configuration">
                <option value="">Select quantity</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='leaf1_vision_panel_Qty')
                <option value="{{$row->OptionKey}}"
                    @if(isset($defaultItemsStandard->Leaf2VisionPanelQuantity))
                        @if($defaultItemsStandard->Leaf2VisionPanelQuantity == $row->OptionKey)
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
                id="standardAreVPsEqualSizesForLeaf2"
                @if(!isset($defaultItemsStandard->AreVPsEqualSizesForLeaf2) || (isset($defaultItemsStandard->sVPSameAsLeaf1) && $defaultItemsStandard->sVPSameAsLeaf1 == "Yes"))
                {{'disabled'}}
                @endif
                class="form-control door-configuration">
                <option value="">Are Visible panel equll size?</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='leaf1are_vps_equal_sizes')
                <option value="{{$row->OptionKey}}"
                    @if(isset($defaultItemsStandard->AreVPsEqualSizesForLeaf2))
                        @if($defaultItemsStandard->AreVPsEqualSizesForLeaf2 == $row->OptionKey)
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
                @if(!isset($defaultItemsStandard->DistanceFromTopOfDoorForLeaf2) || (isset($defaultItemsStandard->sVPSameAsLeaf1) && $defaultItemsStandard->sVPSameAsLeaf1 == "Yes")) {{'readonly'}} @endif
                min="100"
                name="distanceFromTopOfDoorforLeaf2" id="standarddistanceFromTopOfDoorforLeaf2"
                class="form-control door-configuration"
                value="@if(isset($defaultItemsStandard->DistanceFromTopOfDoorForLeaf2)){{$defaultItemsStandard->DistanceFromTopOfDoorForLeaf2}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="distanceFromTheEdgeOfDoorforLeaf2">Distance from the
                edge for Leaf 2</label>
            <input type="number"
                @if(!isset($defaultItemsStandard->DistanceFromTheEdgeOfDoorforLeaf2) || (isset($defaultItemsStandard->sVPSameAsLeaf1) && $defaultItemsStandard->sVPSameAsLeaf1 == "Yes")) {{'readonly'}} @endif
                min="100"
                name="distanceFromTheEdgeOfDoorforLeaf2"
                id="standarddistanceFromTheEdgeOfDoorforLeaf2" class="form-control door-configuration"
                value="@if(isset($defaultItemsStandard->DistanceFromTheEdgeOfDoorforLeaf2)){{$defaultItemsStandard->DistanceFromTheEdgeOfDoorforLeaf2}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="distanceBetweenVPsforLeaf2">Distance between VP's</label>
            <input type="number"
                min="80"
                @if(!isset($defaultItemsStandard->DistanceBetweenVp) || (isset($defaultItemsStandard->sVPSameAsLeaf1) && $defaultItemsStandard->sVPSameAsLeaf1 == "Yes")) {{'readonly'}} @endif
                name="distanceBetweenVPsforLeaf2"
                id="standarddistanceBetweenVPsforLeaf2" class="form-control door-configuration"
                value="@if(isset($defaultItemsStandard->DistanceBetweenVp)){{$defaultItemsStandard->DistanceBetweenVp}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP2Width">Leaf 2 VP Width</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsStandard->Leaf2VPWidth) || (isset($defaultItemsStandard->sVPSameAsLeaf1) && $defaultItemsStandard->sVPSameAsLeaf1 == "Yes")) {{'readonly'}} @endif
                name="vP2Width"
                id="standardvP2Width"
                class="form-control"
                value="@if(isset($defaultItemsStandard->Leaf2VPWidth)){{$defaultItemsStandard->Leaf2VPWidth}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP2Height1">Leaf 2 VP Height (1)</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsStandard->Leaf2VPHeight1) || (isset($defaultItemsStandard->sVPSameAsLeaf1) && $defaultItemsStandard->sVPSameAsLeaf1 == "Yes")) {{'readonly'}} @endif
                name="vP2Height1"
                id="standardvP2Height1"
                class="form-control door-configuration"
                value="@if(isset($defaultItemsStandard->Leaf2VPHeight1)){{$defaultItemsStandard->Leaf2VPHeight1}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP2Height2">Leaf 2 VP Height (2)</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsStandard->Leaf2VPHeight2) || (isset($defaultItemsStandard->sVPSameAsLeaf1) && $defaultItemsStandard->sVPSameAsLeaf1 == "Yes") || (isset($defaultItemsStandard->AreVPsEqualSizesForLeaf2) && $defaultItemsStandard->AreVPsEqualSizesForLeaf2 == "Yes")) {{'readonly'}} @endif
                name="vP2Height2"
                id="standardvP2Height2"
                class="form-control door-configuration"
                value="@if(isset($defaultItemsStandard->Leaf2VPHeight2)){{$defaultItemsStandard->Leaf2VPHeight2}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP2Height3">Leaf 2 VP Height (3)</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsStandard->Leaf2VPHeight3) || (isset($defaultItemsStandard->sVPSameAsLeaf1) && $defaultItemsStandard->sVPSameAsLeaf1 == "Yes") || (isset($defaultItemsStandard->AreVPsEqualSizesForLeaf2) && $defaultItemsStandard->AreVPsEqualSizesForLeaf2 == "Yes")) {{'readonly'}} @endif
                name="vP2Height3"
                id="standardvP2Height3"
                class="form-control door-configuration"
                value="@if(isset($defaultItemsStandard->Leaf2VPHeight3)){{$defaultItemsStandard->Leaf2VPHeight3}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP2Height4">Leaf 2 VP Height (4)</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsStandard->Leaf2VPHeight4) || (isset($defaultItemsStandard->sVPSameAsLeaf1) && $defaultItemsStandard->sVPSameAsLeaf1 == "Yes") || (isset($defaultItemsStandard->AreVPsEqualSizesForLeaf2) && $defaultItemsStandard->AreVPsEqualSizesForLeaf2 == "Yes")) {{'readonly'}} @endif
                name="vP2Height4"
                id="standardvP2Height4"
                class="form-control door-configuration"
                value="@if(isset($defaultItemsStandard->Leaf2VPHeight4)){{$defaultItemsStandard->Leaf2VPHeight4}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="vP2Height5">Leaf 2 VP Height (5)</label>
            <input type="number"
                min="0"
                @if(!isset($defaultItemsStandard->Leaf2VPHeight5) || (isset($defaultItemsStandard->sVPSameAsLeaf1) && $defaultItemsStandard->sVPSameAsLeaf1 == "Yes") || (isset($defaultItemsStandard->AreVPsEqualSizesForLeaf2) && $defaultItemsStandard->AreVPsEqualSizesForLeaf2 == "Yes")) {{'readonly'}} @endif
                name="vP2Height5"
                id="standardvP2Height5"
                class="form-control door-configuration"
                value="@if(isset($defaultItemsStandard->Leaf2VPHeight5)){{$defaultItemsStandard->Leaf2VPHeight5}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group input-icons">
            <label for="frameMaterial">Frame Material</label>
            <i class="fa fa-info icon" id="StandardframeMaterialIcon"></i>
            <input type="text" required readonly id="StandardframeMaterial"
                class="form-control bg-white"
                value="@if(isset($defaultItemsStandard->FrameMaterial)){{$defaultItemsStandard->FrameMaterial}}@endif">
            <input type="hidden" id="StandardframeMaterialNew"
                name="frameMaterial"
                value="@if(isset($defaultItemsStandard->FrameMaterial)){{$defaultItemsStandard->FrameMaterial}}@endif">
        </div>

    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="frameType">Frame Type</label>
            <select name="frameType" required id="frameType" class="form-control">
                <option value="">Select Frame Type</option>
                <option value="Scalloped" @if(isset($defaultItemsStandard->FrameType)) @if($defaultItemsStandard->FrameType == "Scalloped") {{'selected'}} @endif @endif>Scalloped Frame</option>
                <option value="Plant_on_Stop" @if(isset($defaultItemsStandard->FrameType)) @if($defaultItemsStandard->FrameType == "Plant_on_Stop") {{'selected'}} @endif @endif>Plant on Stop</option>
                <option value="Rebated_Frame" @if(isset($defaultItemsStandard->FrameType)) @if($defaultItemsStandard->FrameType == "Rebated_Frame") {{'selected'}} @endif @endif>Rebated Frame</option>
            </select>
            <div id="StandardLippingSpecies-value" data-value="@if(isset($defaultItemsStandard->LippingSpecies)){{$defaultItemsStandard->LippingSpecies}}@endif" hidden=""></div>
            <div id="StandardFrameMaterial-value" data-value="@if(isset($defaultItemsStandard->FrameMaterial)){{$defaultItemsStandard->FrameMaterial}}@endif" hidden=""></div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="frameDepth">Frame Depth (min 80)</label>
            <input type="text" name="frameDepth" id="frameDepth" class="form-control"
                value="@if(isset($defaultItemsStandard->FrameDepth)){{$defaultItemsStandard->FrameDepth}}@endif" min="80">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="frameFinish">Frame Finish</label>
            <select name="frameFinish" id="StandardframeFinish"
                class="form-control change-event-calulation">
                <option value="">Select Frame finish</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='Frame_Finish')
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsStandard->FrameFinish)) @if($defaultItemsStandard->FrameFinish == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="framefinishColor">Frame Finish Color</label>
            <select name="framefinishColor" id="StandardframefinishColor" class="form-control">
                <option value="">Frame Finish</option>
                <!-- @foreach($option_data as $row)
                    @if($row->OptionSlug=='door_leaf_finish')
                    <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                    @endif
                @endforeach -->
            </select>
            <input type="hidden" id="StandardFrameFinishColor-value" value="@if(isset($defaultItemsStandard->FrameFinishColor)){{$defaultItemsStandard->FrameFinishColor}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group input-icons">
            <label for="frameCostuction">Door Frame Construction</label>
            @if(isset($defaultItemsStandard->DoorFrameConstruction) && $defaultItemsStandard->DoorFrameConstruction != "")
                @foreach($option_data as $row)
                    @if($row->OptionSlug=='Door_Frame_Construction')
                        @if($row->OptionKey == $defaultItemsStandard->DoorFrameConstruction)
                            <?php $DoorFrameConstruction = $row->OptionValue; ?>
                        @endif
                    @endif
                @endforeach
            @endif
            <i class="fa fa-info icon cursor-pointer" id="frameCostuction" onClick="$('#DoorFrameConstructionModal').modal('show')"></i>
            <input type="text" readonly id="frameCostuction" value="@if(isset($DoorFrameConstruction)){{$DoorFrameConstruction}}@endif" class="form-control bg-white">
            <input type="hidden" name="frameCostuction" value="@if(isset($defaultItemsStandard->DoorFrameConstruction)){{$defaultItemsStandard->DoorFrameConstruction}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="lippingType">Lipping Type</label>
            <select name="lippingType"  id="lippingType" class="form-control">
                <option value="">Select Lipping Types</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='lipping_type')
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsStandard->LippingType)) @if($defaultItemsStandard->LippingType == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="lippingThickness">Lipping Thickness</label>
            <select name="lippingThickness"  id="lippingThickness"
                class="form-control forcoreWidth1 door-configuration" onchange="$('#lippingSpecies').val('')">
                <option value="">Select leaping thickness</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='lipping_thickness')
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsStandard->LippingThickness)) @if($defaultItemsStandard->LippingThickness == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="position-relative form-group input-icons">
            <label for="lippingSpecies">Lipping Species</label>
            <i class="fa fa-info icon" id="StandardlippingSpeciesIcon"  onClick=""></i>
            <input type="text"  @if(isset($defaultItemsStandard->FireRating) && $defaultItemsStandard->FireRating != "NFR"){{'required'}} @endif readonly id="StandardlippingSpecies"
                class="form-control bg-white door-configuration">
            <input type="hidden" name="lippingSpecies" id="StandardlippingSpeciesid" value="@if(isset($defaultItemsStandard->LippingSpecies)){{$defaultItemsStandard->LippingSpecies}}@endif">
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
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsStandard->IntumescentLeapingSealType)) @if($defaultItemsStandard->IntumescentLeapingSealType == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
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
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsStandard->IntumescentLeapingSealLocation)) @if($defaultItemsStandard->IntumescentLeapingSealLocation == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
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
                    <option value="{{$row->Key}}" @if(isset($defaultItemsStandard->IntumescentLeapingSealColor)) @if($defaultItemsStandard->IntumescentLeapingSealColor == $row->Key) {{'selected'}} @endif @endif>{{$row->IntumescentSealColor}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="Architrave">Architrave</label>
            <select name="Architrave" id="standardArchitrave" class="form-control">
                <option value="">Select Architrave</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='Architrave')
                <option value="{{$row->OptionKey}}"
                    @if(isset($defaultItemsStandard->Architrave))
                        @if($defaultItemsStandard->Architrave == $row->OptionKey)
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
            <i class="fa fa-info icon cursor-pointer" id="standardarchitraveMaterialIcon"></i>
            <input  type="text" id="standardarchitraveMaterial" class="form-control" readonly >
            <input type="hidden" name="architraveMaterial" id="standardarchitraveMaterialNew"
                value="@if(isset($defaultItemsStandard->ArchitraveMaterial)){{$defaultItemsStandard->ArchitraveMaterial}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="architraveType">Architrave Type</label>
            <select  name="architraveType" id="standardarchitraveType" option_slug = "Architrave_Type"
                @if(isset($defaultItemsStandard->Architrave))
                    @if($defaultItemsStandard->Architrave != "Yes")
                        {{'disabled'}}
                    @endif
                @else
                    {{'disabled'}}
                @endif
                class="form-control">
                <option value="">Select Architrave Type</option>
                @foreach($ArchitraveType as $row)
                    <option value="{{$row->Key}}" @if(isset($defaultItemsStandard->ArchitraveType)) @if($defaultItemsStandard->ArchitraveType == $row->Key) {{'selected'}} @endif @endif>{{$row->ArchitraveType}}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="architraveWidth">Architrave Width</label>
            <input name="architraveWidth" id="standardarchitraveWidth"
                @if(isset($defaultItemsStandard->Architrave))
                    @if($defaultItemsStandard->Architrave != "Yes")
                        {{'readonly'}}
                    @endif
                @else
                    {{'readonly'}}
                @endif
                placeholder="Architrave Width" class="form-control" type="text"
                value="@if(isset($defaultItemsStandard->ArchitraveWidth)){{$defaultItemsStandard->ArchitraveWidth}}@endif">
        </div>
    </div>

    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="architraveHeight">Architrave Thickness</label>
            <label for="architrave_Height" style="display: none;">Architrave Thickness</label>
            <input name="architraveHeight" id="standardarchitraveHeight"
                @if(isset($defaultItemsStandard->Architrave))
                    @if($defaultItemsStandard->Architrave != "Yes")
                        {{'readonly'}}
                    @endif
                @else
                    {{'readonly'}}
                @endif
                placeholder="Architrave Thickness" class="form-control" type="text"
                value="@if(isset($defaultItemsStandard->ArchitraveHeight)){{$defaultItemsStandard->ArchitraveHeight}}@endif">
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="architraveFinish">Architrave Finish</label>
            <select name="architraveFinish" id="standardarchitraveFinish"
                @if(isset($defaultItemsStandard->Architrave))
                    @if($defaultItemsStandard->Architrave != "Yes")
                        {{'disabled'}}
                    @endif
                @else
                    {{'disabled'}}
                @endif
                class="form-control">
                <option value="">Select Architrave Finish</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='Architrave_Finish')
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsStandard->ArchitraveFinish)) @if($defaultItemsStandard->ArchitraveFinish == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group input-icons">
            <label for="architravefinishColor">Architrave Finish color</label>
            <i class="fa fa-info icon cursor-pointer" id="standardarchitraveFinishcolorIcon"></i>

            <input type="text" id="standardarchitraveFinishcolor" name="architraveFinishcolor" class="form-control"  @if(empty($defaultItemsStandard->ArchitraveFinishColor)){{'readonly'}}@endif value="@if(isset($defaultItemsStandard->ArchitraveFinishColor)){{$defaultItemsStandard->ArchitraveFinishColor}}@endif">


        </div>
    </div>
    <div class="col-md-6">
        <div class="position-relative form-group">
            <label for="architraveSetQty">Architrave Set Qty</label>
            <select name="architraveSetQty" id="standardarchitraveSetQty"
                @if(isset($defaultItemsStandard->Architrave))
                    @if($defaultItemsStandard->Architrave != "Yes")
                        {{'disabled'}}
                    @endif
                @else
                    {{'disabled'}}
                @endif
                class="form-control">
                <option value="">Select Architrave Finish</option>
                @foreach($option_data as $row)
                @if($row->OptionSlug=='Architrave_Set_Qty')
                <option value="{{$row->OptionKey}}" @if(isset($defaultItemsStandard->ArchitraveSetQty)) @if($defaultItemsStandard->ArchitraveSetQty == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                @endif
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <button class="btn btn-primary float-right" type="submit">Submit</button>
    </div>
</div>
