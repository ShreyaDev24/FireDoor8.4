<!-- VISION PANEL -->
<div class="main-card mb-3 custom_card">
    <div class="">
        <div class="tab-content">
            <div class="card-header">
                <h5 class="card-title" style="margin-top: 10px">Vision Panel</h5>
            </div>
            <div class="">
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="leaf1VisionPanel">Leaf 1 Vision Panel
                            @if(!empty($tooltip->leaf1VisionPanel))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->leaf1VisionPanel}}'));
                            </script>
                            @endif
                            </label>
                            <select required name="leaf1VisionPanel" id="leaf1VisionPanel"
                                class="form-control change-event-calulation door-configuration">
                                <option value=""> Is Vision Panel active? </option>
                                @foreach($option_data as $row)
                                @if($row->OptionSlug=='leaf1_vision_panel')
                                <option value="{{$row->OptionKey}}"
                                    @if(isset($Item["Leaf1VisionPanel"]))
                                        @if($Item["Leaf1VisionPanel"] == $row->OptionKey)
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
                            <label for="leaf1VisionPanelShape">Vision Panel Shape
                            @if(!empty($tooltip->leaf1VisionPanelShape))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->leaf1VisionPanelShape}}'));
                            </script>
                            @endif
                            </label>
                            <select required name="leaf1VisionPanelShape" id="leaf1VisionPanelShape"
                                class="form-control change-event-calulation door-configuration">
                                <option value="">select any shape </option>
                                @foreach($option_data as $row)
                                @if($row->OptionSlug=='Vision_panel_shape')
                                <option value="{{$row->OptionKey}}"
                                    @if(isset($Item["Leaf1VisionPanelShape"]))
                                        @if($Item["Leaf1VisionPanelShape"] == $row->OptionKey)
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
                            <label for="visionPanelQuantity">Vision Panel Quantity
                            @if(!empty($tooltip->visionPanelQuantity))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->visionPanelQuantity}}'));
                            </script>
                            @endif
                            </label>
                            <select name="visionPanelQuantity"
                                id="visionPanelQuantity"
                                @if(empty($Item['VisionPanelQuantity'])) {{'disabled'}} @endif
                                class="form-control change-event-calulation door-configuration">

                                <option value="">Select quantity</option>
                                @foreach($option_data as $row)
                                @if($row->OptionSlug=='leaf1_vision_panel_Qty')
                                <option value="{{$row->OptionKey}}"
                                    @if(isset($Item['VisionPanelQuantity']))
                                        @if($Item['VisionPanelQuantity'] == $row->OptionKey)
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
                            <label for="AreVPsEqualSizes">Are VP's equal sizes?
                            @if(!empty($tooltip->AreVPsEqualSizes))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->AreVPsEqualSizes}}'));
                            </script>
                            @endif
                            </label>
                            <select name="AreVPsEqualSizes"
                                id="AreVPsEqualSizes"
                                @if(empty($Item['AreVPsEqualSizesForLeaf1'])) {{'disabled'}} @endif
                                class="form-control change-event-calulation door-configuration">
                                <option value="">Are Visible panel equll size?</option>
                                @foreach($option_data as $row)
                                @if($row->OptionSlug=='leaf1are_vps_equal_sizes')
                                <option value="{{$row->OptionKey}}"
                                    @if(isset($Item['AreVPsEqualSizesForLeaf1']))
                                        @if($Item['AreVPsEqualSizesForLeaf1'] == $row->OptionKey)
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
                            <label for="distanceFromTopOfDoor">Distance from top of door
                            @if(!empty($tooltip->distanceFromTopOfDoor))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->distanceFromTopOfDoor}}'));
                            </script>
                            @endif
                                </label>
                            <input type="number"
                                @if(!isset($Item['DistanceFromtopOfDoor'])) {{'readonly'}} @endif
                                min="100"
                                name="distanceFromTopOfDoor" id="distanceFromTopOfDoor"
                                class="form-control door-configuration"
                        value="@if(isset($Item['DistanceFromtopOfDoor'])){{$Item['DistanceFromtopOfDoor']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="distanceFromTheEdgeOfDoor">Distance from the
                                edge
                            @if(!empty($tooltip->distanceFromTheEdgeOfDoor))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->distanceFromTheEdgeOfDoor}}'));
                            </script>
                            @endif
                                </label>
                            <input type="number"
                                @if(!isset($Item['DistanceFromTheEdgeOfDoor'])) {{'readonly'}} @endif
                                min="150" name="distanceFromTheEdgeOfDoor" id="distanceFromTheEdgeOfDoor"
                                class="form-control door-configuration" value="@if(isset($Item['DistanceFromTheEdgeOfDoor'])){{$Item['DistanceFromTheEdgeOfDoor']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="distanceBetweenVPs">Distance between VP's
                            @if(!empty($tooltip->distanceBetweenVPs))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->distanceBetweenVPs}}'));
                            </script>
                            @endif
                            </label>
                            <input type="number"
                                min="80"
                                @if(!isset($Item['DistanceBetweenVPs'])) {{'readonly'}} @endif
                                name="distanceBetweenVPs"
                                id="distanceBetweenVPs" class="form-control door-configuration"
                                value="@if(isset($Item['DistanceBetweenVPs'])){{$Item['DistanceBetweenVPs']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="vP1Width">Leaf 1 VP Width
                                @if(!empty($tooltip->vP1Width))
                                    <script type="text/javascript">
                                        document.write(Tooltip('{{$tooltip->vP1Width}}'));
                                    </script>
                                @endif
                            </label>
                            <input type="number"
                                min="0"
                                @if(!isset($Item['Leaf1VPWidth'])) {{'readonly'}} @endif
                                name="vP1Width"
                                id="vP1Width"
                                class="form-control change-event-calulation door-configuration"
                                value="@if(isset($Item['Leaf1VPWidth'])){{$Item['Leaf1VPWidth']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="vP1Height1">Leaf 1 VP Height (1)
                            @if(!empty($tooltip->vP1Height1))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->vP1Height1}}'));
                            </script>
                            @endif
                            </label>
                            <input type="number"
                                min="0"
                                @if(!isset($Item['Leaf1VPHeight1'])) {{'readonly'}} @endif
                                name="vP1Height1"
                                id="vP1Height1"
                                class="form-control change-event-calulation door-configuration"
                                value="@if(isset($Item['Leaf1VPHeight1'])){{$Item['Leaf1VPHeight1']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="vP1Height2">Leaf 1 VP Height (2)

                            @if(!empty($tooltip->vP1Height2))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->vP1Height2}}'));
                            </script>
                            @endif
                            </label>
                            <input type="number"
                                min="0"
                                @if(!isset($Item['Leaf1VPHeight2']) || (isset($Item['AreVPsEqualSizesForLeaf1']) && $Item['AreVPsEqualSizesForLeaf1'] == "Yes")) {{'readonly'}} @endif
                                name="vP1Height2"
                                id="vP1Height2"
                                class="form-control change-event-calulation door-configuration"
                                value="@if(isset($Item['Leaf1VPHeight2'])){{$Item['Leaf1VPHeight2']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="vP1Height3">Leaf 1 VP Height (3)
                            @if(!empty($tooltip->vP1Height3))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->vP1Height3}}'));
                            </script>
                            @endif
                            </label>
                            <input type="number"
                                min="0"
                                @if(!isset($Item['Leaf1VPHeight3']) || (isset($Item['AreVPsEqualSizesForLeaf1']) && $Item['AreVPsEqualSizesForLeaf1'] == "Yes")) {{'readonly'}} @endif
                                name="vP1Height3"
                                id="vP1Height3"
                                class="form-control change-event-calulation door-configuration"
                                value="@if(isset($Item['Leaf1VPHeight3'])){{$Item['Leaf1VPHeight3']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="vP1Height4">Leaf 1 VP Height (4)
                            @if(!empty($tooltip->vP1Height4))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->vP1Height4}}'));
                            </script>
                            @endif
                            </label>
                            <input type="number"
                                min="0"
                                @if(!isset($Item['Leaf1VPHeight4']) || (isset($Item['AreVPsEqualSizesForLeaf1']) && $Item['AreVPsEqualSizesForLeaf1'] == "Yes")) {{'readonly'}} @endif
                                name="vP1Height4"
                                id="vP1Height4"
                                class="form-control change-event-calulation door-configuration"
                                value="@if(isset($Item['Leaf1VPHeight4'])){{$Item['Leaf1VPHeight4']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="vP1Height5">Leaf 1 VP Height (5)
                            @if(!empty($tooltip->vP1Height5))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->vP1Height5}}'));
                            </script>
                            @endif
                            </label>
                            <input type="number"
                                min="0"
                                @if(!isset($Item['Leaf1VPHeight5']) || (isset($Item['AreVPsEqualSizesForLeaf1']) && $Item['AreVPsEqualSizesForLeaf1'] == "Yes")) {{'readonly'}} @endif
                                name="vP1Height5"
                                id="vP1Height5"
                                class="form-control change-event-calulation door-configuration"
                                value="@if(isset($Item['Leaf1VPHeight5'])){{$Item['Leaf1VPHeight5']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="leaf1VpAreaSizeM2"> Leaf 1 VP Area Size m2

                            @if(!empty($tooltip->leaf1VpAreaSizeM2))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->leaf1VpAreaSizeM2}}'));
                            </script>
                            @endif
                            </label>
                            <input type="number"
                                min="0"
                                readonly
                                name="leaf1VpAreaSizeM2"
                                id="leaf1VpAreaSizeM2" class="form-control"
                                value="@if(isset($Item['Leaf1VPAreaSizem2'])){{$Item['Leaf1VPAreaSizem2']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="leaf2VisionPanel">Leaf 2 Vision Panel
                                @if(!empty($tooltip->leaf2VisionPanel))
                                    <script type="text/javascript">
                                        document.write(Tooltip('{{$tooltip->leaf2VisionPanel}}'));
                                    </script>
                                @endif
                            </label>
                            <select name="leaf2VisionPanel"
                                id="leaf2VisionPanel"
                                @if(!isset($Item['Leaf2VisionPanel'])) {{'disabled'}} @endif
                                class="form-control door-configuration">
                                <option value=""> Is Vision Panel fr leaf 2 active? </option>
                                @foreach($option_data as $row)
                                @if($row->OptionSlug=='leaf1_vision_panel')
                                <option value="{{$row->OptionKey}}"


                                    @if(isset($Item["Leaf2VisionPanel"]))
                                        @if($Item["Leaf2VisionPanel"] == $row->OptionKey)
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
                            <label for="vpSameAsLeaf1">Is VP same as Leaf 1?
                                @if(!empty($tooltip->vpSameAsLeaf1))
                                    <script type="text/javascript">
                                        document.write(Tooltip('{{$tooltip->vpSameAsLeaf1}}'));
                                    </script>
                                @endif
                            </label>
                            <select name="vpSameAsLeaf1"
                                id="vpSameAsLeaf1"
                                @if(!isset($Item['sVPSameAsLeaf1'])) {{'disabled'}} @endif
                                class="form-control door-configuration">
                                <option value="">Is VP same as Leaf 1?</option>
                                @foreach($option_data as $row)
                                    @if($row->OptionSlug=='leaf1are_vps_equal_sizes')
                                    <option value="{{$row->OptionKey}}"
                                        @if(isset($Item['sVPSameAsLeaf1']))
                                            @if($Item['sVPSameAsLeaf1'] == $row->OptionKey)
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
                            <label for="visionPanelQuantityforLeaf2" >Leaf 2 Vision Panel
                                Quantity

                            @if(!empty($tooltip->visionPanelQuantityforLeaf2))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->visionPanelQuantityforLeaf2}}'));
                            </script>
                            @endif
                                </label>
                            <select name="visionPanelQuantityforLeaf2"
                                id="visionPanelQuantityforLeaf2"
                                @if(!isset($Item['Leaf2VisionPanelQuantity']) || (isset($Item['sVPSameAsLeaf1']) && $Item['sVPSameAsLeaf1'] == "Yes"))
                                {{'disabled'}}
                                @endif
                                class="form-control door-configuration">
                                <option value="">Select quantity</option>
                                @foreach($option_data as $row)
                                @if($row->OptionSlug=='leaf1_vision_panel_Qty')
                                <option value="{{$row->OptionKey}}"
                                    @if(isset($Item['Leaf2VisionPanelQuantity']))
                                        @if($Item['Leaf2VisionPanelQuantity'] == $row->OptionKey)
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
                                2?
                            @if(!empty($tooltip->AreVPsEqualSizesForLeaf2))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->AreVPsEqualSizesForLeaf2}}'));
                            </script>
                            @endif
                                </label>
                            <select name="AreVPsEqualSizesForLeaf2"
                                id="AreVPsEqualSizesForLeaf2"
                                @if(!isset($Item['AreVPsEqualSizesForLeaf2']) || (isset($Item['sVPSameAsLeaf1']) && $Item['sVPSameAsLeaf1'] == "Yes"))
                                {{'disabled'}}
                                @endif
                                class="form-control door-configuration">
                                <option value="">Are Visible panel equll size?</option>
                                @foreach($option_data as $row)
                                @if($row->OptionSlug=='leaf1are_vps_equal_sizes')
                                <option value="{{$row->OptionKey}}"
                                    @if(isset($Item['AreVPsEqualSizesForLeaf2']))
                                        @if($Item['AreVPsEqualSizesForLeaf2'] == $row->OptionKey)
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
                            <label for="distanceFromTopOfDoorforLeaf2">Distance from top of door for Leaf 2
                            @if(!empty($tooltip->distanceFromTopOfDoorforLeaf2))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->distanceFromTopOfDoorforLeaf2}}'));
                            </script>
                            @endif
                                </label>
                            <input type="number"
                                @if(!isset($Item['DistanceFromTopOfDoorForLeaf2']) || (isset($Item['sVPSameAsLeaf1']) && $Item['sVPSameAsLeaf1'] == "Yes")) {{'readonly'}} @endif
                                min="100"
                                name="distanceFromTopOfDoorforLeaf2" id="distanceFromTopOfDoorforLeaf2"
                                class="form-control door-configuration"
                                value="@if(isset($Item['DistanceFromTopOfDoorForLeaf2'])){{$Item['DistanceFromTopOfDoorForLeaf2']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="distanceFromTheEdgeOfDoorforLeaf2">Distance from the
                                edge for Leaf 2
                            @if(!empty($tooltip->distanceFromTheEdgeOfDoorforLeaf2))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->distanceFromTheEdgeOfDoorforLeaf2}}'));
                            </script>
                            @endif

                                </label>
                            <input type="number"
                                @if(!isset($Item['DistanceFromTheEdgeOfDoorforLeaf2']) || (isset($Item['sVPSameAsLeaf1']) && $Item['sVPSameAsLeaf1'] == "Yes")) {{'readonly'}} @endif
                                min="100"
                                name="distanceFromTheEdgeOfDoorforLeaf2"
                                id="distanceFromTheEdgeOfDoorforLeaf2" class="form-control door-configuration"
                                value="@if(isset($Item['DistanceFromTheEdgeOfDoorforLeaf2'])){{$Item['DistanceFromTheEdgeOfDoorforLeaf2']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="distanceBetweenVPsforLeaf2">Distance between
                                VP's
                            @if(!empty($tooltip->distanceBetweenVPsforLeaf2))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->distanceBetweenVPsforLeaf2}}'));
                            </script>
                            @endif
                                </label>
                            <input type="number"
                                min="80"
                                @if(!isset($Item['DistanceBetweenVp']) || (isset($Item['sVPSameAsLeaf1']) && $Item['sVPSameAsLeaf1'] == "Yes")) {{'readonly'}} @endif
                                name="distanceBetweenVPsforLeaf2"
                                id="distanceBetweenVPsforLeaf2" class="form-control door-configuration"
                                value="@if(isset($Item['DistanceBetweenVp'])){{$Item['DistanceBetweenVp']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="vP2Width">Leaf 2 VP Width
                            @if(!empty($tooltip->vP2Width))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->vP2Width}}'));
                            </script>
                            @endif

                            </label>
                            <input type="number"
                                min="0"
                                @if(!isset($Item['Leaf2VPWidth']) || (isset($Item['sVPSameAsLeaf1']) && $Item['sVPSameAsLeaf1'] == "Yes")) {{'readonly'}} @endif
                                name="vP2Width"
                                id="vP2Width"
                                class="form-control"
                                value="@if(isset($Item['Leaf2VPWidth'])){{$Item['Leaf2VPWidth']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="vP2Height1">Leaf 2 VP Height (1)
                            @if(!empty($tooltip->vP2Height1))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->vP2Height1}}'));
                            </script>
                            @endif

                            </label>
                            <input type="number"
                                min="0"
                                @if(!isset($Item['Leaf2VPHeight1']) || (isset($Item['sVPSameAsLeaf1']) && $Item['sVPSameAsLeaf1'] == "Yes")) {{'readonly'}} @endif
                                name="vP2Height1"
                                id="vP2Height1"
                                class="form-control door-configuration"
                                value="@if(isset($Item['Leaf2VPHeight1'])){{$Item['Leaf2VPHeight1']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="vP2Height2">Leaf 2 VP Height (2)
                            @if(!empty($tooltip->vP2Height2))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->vP2Height2}}'));
                            </script>
                            @endif

                            </label>
                            <input type="number"
                                min="0"
                                @if(!isset($Item['Leaf2VPHeight2']) || (isset($Item['sVPSameAsLeaf1']) && $Item['sVPSameAsLeaf1'] == "Yes") || (isset($Item['AreVPsEqualSizesForLeaf2']) && $Item['AreVPsEqualSizesForLeaf2'] == "Yes")) {{'readonly'}} @endif
                                name="vP2Height2"
                                id="vP2Height2"
                                class="form-control door-configuration"
                                value="@if(isset($Item['Leaf2VPHeight2'])){{$Item['Leaf2VPHeight2']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="vP2Height3">Leaf 2 VP Height (3)
                            @if(!empty($tooltip->vP2Height3))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->vP2Height3}}'));
                            </script>
                            @endif
                            </label>
                            <input type="number"
                                min="0"
                                @if(!isset($Item['Leaf2VPHeight3']) || (isset($Item['sVPSameAsLeaf1']) && $Item['sVPSameAsLeaf1'] == "Yes") || (isset($Item['AreVPsEqualSizesForLeaf2']) && $Item['AreVPsEqualSizesForLeaf2'] == "Yes")) {{'readonly'}} @endif
                                name="vP2Height3"
                                id="vP2Height3"
                                class="form-control door-configuration"
                                value="@if(isset($Item['Leaf2VPHeight3'])){{$Item['Leaf2VPHeight3']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="vP2Height4">Leaf 2 VP Height (4)
                            @if(!empty($tooltip->vP2Height4))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->vP2Height4}}'));
                            </script>
                            @endif
                            </label>
                            <input type="number"
                                min="0"
                                @if(!isset($Item['Leaf2VPHeight4']) || (isset($Item['sVPSameAsLeaf1']) && $Item['sVPSameAsLeaf1'] == "Yes") || (isset($Item['AreVPsEqualSizesForLeaf2']) && $Item['AreVPsEqualSizesForLeaf2'] == "Yes")) {{'readonly'}} @endif
                                name="vP2Height4"
                                id="vP2Height4"
                                class="form-control door-configuration"
                                value="@if(isset($Item['Leaf2VPHeight4'])){{$Item['Leaf2VPHeight4']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="vP2Height5">Leaf 2 VP Height (5)
                            @if(!empty($tooltip->vP2Height5))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->vP2Height5}}'));
                            </script>
                            @endif
                            </label>
                            <input type="number"
                                min="0"
                                @if(!isset($Item['Leaf2VPHeight5']) || (isset($Item['sVPSameAsLeaf1']) && $Item['sVPSameAsLeaf1'] == "Yes") || (isset($Item['AreVPsEqualSizesForLeaf2']) && $Item['AreVPsEqualSizesForLeaf2'] == "Yes")) {{'readonly'}} @endif
                                name="vP2Height5"
                                id="vP2Height5"
                                class="form-control door-configuration"
                                value="@if(isset($Item['Leaf2VPHeight5'])){{$Item['Leaf2VPHeight5']}}@endif">
                        </div>
                    </div>
                    <!-- <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="leaf2VpAreaSizeM2" class="">Leaf 2 VP Area Size m2</label>
                            <input Type="number" min="0" readonly name="leaf2VpAreaSizeM2"
                                id="leaf2VpAreaSizeM2" class="form-control"
                                value="@if(isset($Item['Leaf2VpAreaSizeM2'])){{$Item['Leaf2VpAreaSizeM2']}}@endif">
                            </div>
                    </div> -->
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="lazingIntegrityOrInsulationIntegrity">Glass
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
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="glassType">Glass Type
                            @if(!empty($tooltip->glassType))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->glassType}}'));
                            </script>
                            @endif
                            </label>
                            <select name="glassType" id="glassType" option_slug="leaf1_glass_type" class="form-control">
                                <option value="">Select Glass Type</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="glassThickness">Glass Thickness
                            @if(!empty($tooltip->glassThickness))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->glassThickness}}'));
                            </script>
                            @endif
                            </label>
                            {{--  <select name="glassThickness" id="glassThickness" class="form-control"></select>  --}}
                            <input type="text" readonly name="glassThickness" id="glassThickness" class="form-control"
                                value="@if(isset($Item['GlassThickness'])){{$Item['GlassThickness']}}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="glazingSystems">Glazing Systems
                            @if(!empty($tooltip->glazingSystems))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->glazingSystems}}'));
                            </script>
                            @endif
                            </label>
                            <select name="glazingSystems" id="glazingSystems" option_slug="leaf1_glazing_systems" class="form-control">
                                <option value=""> Select Glazing Systems</option>
                                <!-- @foreach($option_data as $row)
                                @if($row->OptionSlug=='leaf1_glazing_systems')
                                <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                @endif
                                @endforeach -->
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="glazingSystemsThickness">Glazing System
                                Thickness
                            @if(!empty($tooltip->glazingSystemsThickness))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->glazingSystemsThickness}}'));
                            </script>
                            @endif

                                </label>
                            <input type="text" readonly name="glazingSystemsThickness"
                                id="glazingSystemsThickness" class="form-control"
                                value="@if(isset($Item['GlazingSystemThickness'])){{$Item['GlazingSystemThickness']}}@endif">
                                <!-- <select name="glazingSystemsThickness" id="glazingSystemsThickness" class="form-control">
                                        <option value="">Select Glazing Thickness</option>
                                </select> -->
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="glazingBeads">Glazing Beads

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
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="glazingBeadsThickness">Glazing Beads Height
                            @if(!empty($tooltip->glazingBeadsThickness))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->glazingBeadsThickness}}'));
                            </script>
                            @endif
                            </label>
                            <input Type="number" min="0" name="glazingBeadsThickness"
                                id="glazingBeadsThickness" class="form-control"
                                value="@if(isset($Item['GlazingBeadsThickness'])){{$Item['GlazingBeadsThickness']}}@endif">
                        </div>
                    </div>
                    {{-- <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="glazingBeadsWidth">Glazing Beads Width
                            @if(!empty($tooltip->glazingBeadsWidth))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->glazingBeadsWidth}}'));
                            </script>
                            @endif
                            </label> --}}
                            <input Type="hidden" min="0" name="glazingBeadsWidth" id="glazingBeadsWidth"
                                class="form-control"
                                value="0">
                        {{-- </div>
                    </div> --}}

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="glazingBeadsHeight" class="">Glazing Beads Width
                            @if(!empty($tooltip->glazingBeadsHeight))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->glazingBeadsHeight}}'));
                            </script>
                            @endif
                            </label>
                            <input Type="number" min="0" name="glazingBeadsHeight"
                                id="glazingBeadsHeight" class="form-control"
                                value="@if(isset($Item['glazingBeadsHeight'])){{$Item['glazingBeadsHeight']}}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="glazingBeadsFixingDetail">Glazing Bead Fixing
                                Detail
                            @if(!empty($tooltip->glazingBeadsFixingDetail))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->glazingBeadsFixingDetail}}'));
                            </script>
                            @endif

                                </label>
                            <input type="text" name="glazingBeadsFixingDetail"
                                id="glazingBeadsFixingDetail" class="form-control"
                                value="@if(isset($Item['glazingBeadsFixingDetail'])){{$Item['glazingBeadsFixingDetail']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="glazingBeadSpecies">Glazing Bead Species
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
                                <i class="fa fa-info icon" id="glazingBeadSpeciesIcon"></i>
                                <input type="text" readonly id="glazingBeadSpecies"
                                    class="form-control bg-white">
                                <input type="hidden" name="glazingBeadSpecies" id="glazingBeadSpeciesid"
                                value="@if(isset($Item['GlazingBeadSpecies'])){{$Item['GlazingBeadSpecies']}}@endif">
                            </div>
                        </div>
                    </div>





                    <!-- <div class="col-md-6">
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
