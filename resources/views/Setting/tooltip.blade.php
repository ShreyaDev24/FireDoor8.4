@extends("layouts.Master")
@section("main_section")
<div class="app-main__outer">
    <div class="app-main__inner">
        @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Alert!</h5>
            {{ session()->get('success') }}
        </div>
        @endif
        <script>
            function Tooltip(tooltipValue) {
                let TooltipCode2 =
                    `<i class="fa fa-info-circle field_info tooltip" aria-hidden="true">
                    <span class="tooltiptext info_tooltip">` + tooltipValue + `</span>
                </i>`;
                return TooltipCode2;
            }
        </script>
        <form enctype="multipart/form-data" method="post" action="{{route('submittooltip')}}">
            {{@csrf_field()}}
            <input type="hidden" name="updval" value="@if(!empty($tooltip->id)){{$tooltip->id}}@endif">
            <div class="tab-content">
                <!-- Main Options -->
                <div class="main-card mb-3 custom_card">
                    <div class="">
                        <div class="tab-content">
                            <div class="card-header">
                                <h5 class="card-title" style="margin-top: 10px">Main Options</h5>
                            </div>
                            <div class="">
                                <div class="form-row">
                                    <!-- <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leafConstruction">Leaf Construction
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->leafConstruction)){{$tooltip->leafConstruction}}@endif'));
                                                </script>
                                            </label>
                                            <input name="leafConstruction" id="leafConstruction"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->leafConstruction)){{$tooltip->leafConstruction}}@endif">
                                        </div>
                                    </div> -->
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorType" class="">Door Type
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->doorType)){{$tooltip->doorType}}@endif'));
                                                </script>
                                            </label>
                                            <input name="doorType" id="doorType" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->doorType)){{$tooltip->doorType}}@endif">
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="DoorNo" class="">Door No.
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->DoorNo)){{$tooltip->DoorNo}}@endif'));
                                                </script>
                                            </label>
                                            <input name="DoorNo" id="DoorNo" placeholder="Enter Tooltip" type="text"
                                                class="form-control"
                                                value="@if(!empty($tooltip->DoorNo)){{$tooltip->DoorNo}}@endif">
                                        </div>
                                    </div> -->
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="fireRating" class="">Fire Rating
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->fireRating)){{$tooltip->fireRating}}@endif'));
                                                </script>
                                            </label>
                                            <input name="fireRating" id="fireRating" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->fireRating)){{$tooltip->fireRating}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorsetType" class="">Doorset Type
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->doorsetType)){{$tooltip->doorsetType}}@endif'));
                                                </script>
                                            </label>
                                            <input name="doorsetType" id="doorsetType" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->doorsetType)){{$tooltip->doorsetType}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="swingType" class="">Swing Type
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->swingType)){{$tooltip->swingType}}@endif'));
                                                </script>
                                            </label>
                                            <input name="swingType" id="swingType" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->swingType)){{$tooltip->swingType}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="latchType" class="">Latch Type
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->latchType)){{$tooltip->latchType}}@endif'));
                                                </script>
                                            </label>
                                            <input name="latchType" id="latchType" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->latchType)){{$tooltip->latchType}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="Handing" class="">Handing
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->Handing)){{$tooltip->Handing}}@endif'));
                                                </script>
                                            </label>
                                            <input name="Handing" id="Handing" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->Handing)){{$tooltip->Handing}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="OpensInwards" class="">Pull Towards
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->OpensInwards)){{$tooltip->OpensInwards}}@endif'));
                                                </script>
                                            </label>
                                            <input name="OpensInwards" id="OpensInwards" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->OpensInwards)){{$tooltip->OpensInwards}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="COC" class="">COC
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->COC)){{$tooltip->COC}}@endif'));
                                                </script>
                                            </label>
                                            <input name="COC" id="COC" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->COC)){{$tooltip->COC}}@endif">
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorQuantity" class="">Door Quantity
                                            <script type="text/javascript">
                                                document.write(Tooltip('@if(!empty($tooltip->doorQuantity)){{$tooltip->doorQuantity}}@endif'));
                                            </script>
                                            </label>
                                            <input name="doorQuantity" id="doorQuantity" placeholder="Enter Tooltip"
                                                type="text" class="form-control"
                                                value="@if(!empty($tooltip->doorQuantity)){{$tooltip->doorQuantity}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="floor" class="">Floor
                                            <script type="text/javascript">
                                                document.write(Tooltip('@if(!empty($tooltip->floor)){{$tooltip->floor}}@endif'));
                                            </script>
                                            </label>
                                            <input name="floor" id="floor" placeholder="Enter Tooltip" type="text"
                                                class="form-control"
                                                value="@if(!empty($tooltip->floor)){{$tooltip->floor}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="location" class="">Location
                                            <script type="text/javascript">
                                                document.write(Tooltip('@if(!empty($tooltip->location)){{$tooltip->location}}@endif'));
                                            </script>
                                            </label>
                                            <input name="location" id="location" placeholder="Enter Tooltip" type="text"
                                                class="form-control"
                                                value="@if(!empty($tooltip->location)){{$tooltip->location}}@endif">
                                        </div>
                                    </div> -->
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="tollerance" class="">Tollerance
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->tollerance)){{$tooltip->tollerance}}@endif'));
                                                </script>
                                            </label>
                                            <input name="tollerance" id="tollerance" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->tollerance)){{$tooltip->tollerance}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="undercut" class="">Undercut
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->undercut)){{$tooltip->undercut}}@endif'));
                                                </script>
                                            </label>
                                            <input name="undercut" id="undercut" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->undercut)){{$tooltip->undercut}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="floorFinish" class="">Floor Finish
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->floorFinish)){{$tooltip->floorFinish}}@endif'));
                                                </script>
                                            </label>
                                            <input name="floorFinish" id="floorFinish" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->floorFinish)){{$tooltip->floorFinish}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="gap" class="">GAP
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->gap)){{$tooltip->gap}}@endif'));
                                                </script>
                                            </label>
                                            <input name="gap" id="gap" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->gap)){{$tooltip->gap}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="frameThickness" class="">Frame Thickness
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->frameThickness)){{$tooltip->frameThickness}}@endif'));
                                                </script>
                                            </label>
                                            <input name="frameThickness" id="frameThickness" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->frameThickness)){{$tooltip->frameThickness}}@endif">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="ironmongerySet">Ironmongery Set
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->ironmongerySet)){{$tooltip->ironmongerySet}}@endif'));
                                                </script>
                                            </label>
                                            <input name="ironmongerySet" id="ironmongerySet" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->ironmongerySet)){{$tooltip->ironmongerySet}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="IronmongeryID">Select Ironmongery Set
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->selectironmongerySet)){{$tooltip->selectironmongerySet}}@endif'));
                                                </script>
                                            </label>
                                            <input name="selectironmongerySet" id="selectironmongerySet" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->selectironmongerySet)){{$tooltip->selectironmongerySet}}@endif">
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
                                            <label for="sOWidth">S.O Width
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->sOWidth)){{$tooltip->sOWidth}}@endif'));
                                                </script>
                                            </label>
                                            <input name="sOWidth" id="sOWidth" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->sOWidth)){{$tooltip->sOWidth}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="sOHeight">S.O Height
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->sOHeight)){{$tooltip->sOHeight}}@endif'));
                                                </script>
                                            </label>
                                            <input name="sOHeight" id="sOHeight" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->sOHeight)){{$tooltip->sOHeight}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="sODepth">S.O Depth
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->sODepth)){{$tooltip->sODepth}}@endif'));
                                                </script>
                                            </label>
                                            <input name="sODepth" id="sODepth" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->sODepth)){{$tooltip->sODepth}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leafWidth1">Leaf Width 1
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->leafWidth1)){{$tooltip->leafWidth1}}@endif'));
                                                </script>
                                            </label>
                                            <input name="leafWidth1" id="leafWidth1" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->leafWidth1)){{$tooltip->leafWidth1}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leafWidth22">Leaf Width 2
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->leafWidth2)){{$tooltip->leafWidth2}}@endif'));
                                                </script>
                                            </label>
                                            <input name="leafWidth2" id="leafWidth22" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->leafWidth2)){{$tooltip->leafWidth2}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leafHeightNoOP">Leaf Height
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->leafHeightNoOP)){{$tooltip->leafHeightNoOP}}@endif'));
                                                </script>
                                            </label>
                                            <input name="leafHeightNoOP" id="leafHeightNoOP" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->leafHeightNoOP)){{$tooltip->leafHeightNoOP}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorThickness">Door Thickness (mm)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->doorThickness)){{$tooltip->doorThickness}}@endif'));
                                                </script>
                                            </label>
                                            <input name="doorThickness" id="doorThickness" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->doorThickness)){{$tooltip->doorThickness}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorLeafFacing">Door Leaf Facing
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->doorLeafFacing)){{$tooltip->doorLeafFacing}}@endif'));
                                                </script>
                                            </label>
                                            <input name="doorLeafFacing" id="doorLeafFacing" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->doorLeafFacing)){{$tooltip->doorLeafFacing}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorLeafFacingValue">Brand
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->doorLeafFacingValue)){{$tooltip->doorLeafFacingValue}}@endif'));
                                                </script>
                                            </label>
                                            <input name="doorLeafFacingValue" id="doorLeafFacingValue" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->doorLeafFacingValue)){{$tooltip->doorLeafFacingValue}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorLeafFacing">Door Leaf Finish
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->doorLeafFacing)){{$tooltip->doorLeafFacing}}@endif'));
                                                </script>
                                            </label>
                                            <input name="doorLeafFacing" id="doorLeafFacing" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->doorLeafFacing)){{$tooltip->doorLeafFacing}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorLeafFinishColor">Door Leaf Finish Color
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->doorLeafFinishColor)){{$tooltip->doorLeafFinishColor}}@endif'));
                                                </script>
                                            </label>
                                            <input name="doorLeafFinishColor" id="doorLeafFinishColor" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->doorLeafFinishColor)){{$tooltip->doorLeafFinishColor}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="decorativeGroves">Decorative Groves
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->decorativeGroves)){{$tooltip->decorativeGroves}}@endif'));
                                                </script>
                                            </label>
                                            <input name="decorativeGroves" id="decorativeGroves" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->decorativeGroves)){{$tooltip->decorativeGroves}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="grooveLocation1">Groove Location
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->grooveLocation)){{$tooltip->grooveLocation}}@endif'));
                                                </script>
                                            </label>
                                            <input name="grooveLocation" id="grooveLocation1" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->grooveLocation)){{$tooltip->grooveLocation}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="grooveWidth1">Groove Width(Max 10 mm)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->grooveWidth)){{$tooltip->grooveWidth}}@endif'));
                                                </script>
                                            </label>
                                            <input name="grooveWidth" id="grooveWidth1" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->grooveWidth)){{$tooltip->grooveWidth}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="grooveDepth1">Groove Depth
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->grooveDepth)){{$tooltip->grooveDepth}}@endif'));
                                                </script>
                                            </label>
                                            <input name="grooveDepth" id="grooveDepth1" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->grooveDepth)){{$tooltip->grooveDepth}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="maxNumberOfGroove1" class=""> Maximum Number of Groove
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->maxNumberOfGroove)){{$tooltip->maxNumberOfGroove}}@endif'));
                                                </script>
                                            </label>
                                            <input name="maxNumberOfGroove" id="maxNumberOfGroove1" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->maxNumberOfGroove)){{$tooltip->maxNumberOfGroove}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="numberOfGroove1" class="">Number of Grooves
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->numberOfGroove)){{$tooltip->numberOfGroove}}@endif'));
                                                </script>
                                            </label>
                                            <input name="numberOfGroove" id="numberOfGroove1" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->numberOfGroove)){{$tooltip->numberOfGroove}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="numberOfVerticalGroove1" class="">No. of Vertical Grooves(Max 4)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->numberOfVerticalGroove)){{$tooltip->numberOfVerticalGroove}}@endif'));
                                                </script>
                                            </label>
                                            <input name="numberOfVerticalGroove" id="numberOfVerticalGroove1" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->numberOfVerticalGroove)){{$tooltip->numberOfVerticalGroove}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="numberOfHorizontalGroove1" class="">No. of Horizontal Grooves(Max 4)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->numberOfHorizontalGroove)){{$tooltip->numberOfHorizontalGroove}}@endif'));
                                                </script>
                                            </label>
                                            <input name="numberOfHorizontalGroove" id="numberOfHorizontalGroove1" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->numberOfHorizontalGroove)){{$tooltip->numberOfHorizontalGroove}}@endif">
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
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->leaf1VisionPanel)){{$tooltip->leaf1VisionPanel}}@endif'));
                                                </script>
                                            </label>
                                            <input name="leaf1VisionPanel" id="leaf1VisionPanel" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->leaf1VisionPanel)){{$tooltip->leaf1VisionPanel}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leaf1VisionPanelShape" class="">Vision Panel Shape
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->leaf1VisionPanelShape)){{$tooltip->leaf1VisionPanelShape}}@endif'));
                                                </script>
                                            </label>
                                            <input name="leaf1VisionPanelShape" id="leaf1VisionPanelShape" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->leaf1VisionPanelShape)){{$tooltip->leaf1VisionPanelShape}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="visionPanelQuantity" class="">Vision Panel Quantity
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->visionPanelQuantity)){{$tooltip->visionPanelQuantity}}@endif'));
                                                </script>
                                            </label>
                                            <input name="visionPanelQuantity" id="visionPanelQuantity" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->visionPanelQuantity)){{$tooltip->visionPanelQuantity}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="AreVPsEqualSizes2" class="">Are VP's equal sizes?
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->AreVPsEqualSizes)){{$tooltip->AreVPsEqualSizes}}@endif'));
                                                </script>
                                            </label>
                                            <input name="AreVPsEqualSizes" id="AreVPsEqualSizes2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->AreVPsEqualSizes)){{$tooltip->AreVPsEqualSizes}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="distanceFromTopOfDoor2" class="">Distance from top of door
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->distanceFromTopOfDoor)){{$tooltip->distanceFromTopOfDoor}}@endif'));
                                                </script>
                                            </label>
                                            <input name="distanceFromTopOfDoor" id="distanceFromTopOfDoor2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->distanceFromTopOfDoor)){{$tooltip->distanceFromTopOfDoor}}@endif">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="distanceFromTheEdgeOfDoor" class="">Distance from the edge
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->distanceFromTheEdgeOfDoor)){{$tooltip->distanceFromTheEdgeOfDoor}}@endif'));
                                                </script>
                                            </label>
                                            <input name="distanceFromTheEdgeOfDoor" id="distanceFromTheEdgeOfDoor" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->distanceFromTheEdgeOfDoor)){{$tooltip->distanceFromTheEdgeOfDoor}}@endif">
                                        </div>
                                    </div>


                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="distanceBetweenVPs2" class="">Distance between VP's
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->distanceBetweenVPs)){{$tooltip->distanceBetweenVPs}}@endif'));
                                                </script>
                                            </label>
                                            <input name="distanceBetweenVPs" id="distanceBetweenVPs2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->distanceBetweenVPs)){{$tooltip->distanceBetweenVPs}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP1Width2" class="">Leaf 1 VP Width
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->vP1Width)){{$tooltip->vP1Width}}@endif'));
                                                </script>
                                            </label>
                                            <input name="vP1Width" id="vP1Width2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->vP1Width)){{$tooltip->vP1Width}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP1Height1" class="">Leaf 1 VP Height (1)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->vP1Height1)){{$tooltip->vP1Height1}}@endif'));
                                                </script>
                                            </label>
                                            <input name="vP1Height1" id="vP1Height1" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->vP1Height1)){{$tooltip->vP1Height1}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP1Height22">Leaf 1 VP Height (2)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->vP1Height2)){{$tooltip->vP1Height2}}@endif'));
                                                </script>
                                            </label>
                                            <input name="vP1Height2" id="vP1Height22" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->vP1Height2)){{$tooltip->vP1Height2}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP1Height32">Leaf 1 VP Height (3)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->vP1Height3)){{$tooltip->vP1Height3}}@endif'));
                                                </script>
                                            </label>
                                            <input name="vP1Height3" id="vP1Height32" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->vP1Height3)){{$tooltip->vP1Height3}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP1Height42">Leaf 1 VP Height (4)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->vP1Height4)){{$tooltip->vP1Height4}}@endif'));
                                                </script>
                                            </label>
                                            <input name="vP1Height4" id="vP1Height42" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->vP1Height4)){{$tooltip->vP1Height4}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP1Height52">Leaf 1 VP Height (5)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->vP1Height5)){{$tooltip->vP1Height5}}@endif'));
                                                </script>
                                            </label>
                                            <input name="vP1Height5" id="vP1Height52" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->vP1Height5)){{$tooltip->vP1Height5}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leaf1VpAreaSizeM22"> Leaf 1 VP Area Size m2
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->leaf1VpAreaSizeM2)){{$tooltip->leaf1VpAreaSizeM2}}@endif'));
                                                </script>
                                            </label>
                                            <input name="leaf1VpAreaSizeM2" id="leaf1VpAreaSizeM22" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->leaf1VpAreaSizeM2)){{$tooltip->leaf1VpAreaSizeM2}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leaf2VisionPanel" class="">Leaf 2 Vision Panel
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->leaf2VisionPanel)){{$tooltip->leaf2VisionPanel}}@endif'));
                                                </script>
                                            </label>
                                            <input name="leaf2VisionPanel" id="leaf2VisionPanel" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->leaf2VisionPanel)){{$tooltip->leaf2VisionPanel}}@endif">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vpSameAsLeaf12" class="">Is VP same as Leaf 1?
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->vpSameAsLeaf1)){{$tooltip->vpSameAsLeaf1}}@endif'));
                                                </script>
                                            </label>
                                            <input name="vpSameAsLeaf1" id="vpSameAsLeaf12" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->vpSameAsLeaf1)){{$tooltip->vpSameAsLeaf1}}@endif">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="visionPanelQuantityforLeaf22" class="">Leaf 2 Vision Panel Quantity
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->visionPanelQuantityforLeaf2)){{$tooltip->visionPanelQuantityforLeaf2}}@endif'));
                                                </script>
                                            </label>
                                            <input name="visionPanelQuantityforLeaf2" id="visionPanelQuantityforLeaf22" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->visionPanelQuantityforLeaf2)){{$tooltip->visionPanelQuantityforLeaf2}}@endif">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="AreVPsEqualSizesForLeaf2" class="">Are VP's equal sizes for leaf 2?
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->AreVPsEqualSizesForLeaf2)){{$tooltip->AreVPsEqualSizesForLeaf2}}@endif'));
                                                </script>
                                            </label>
                                            <input name="AreVPsEqualSizesForLeaf2" id="AreVPsEqualSizesForLeaf2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->AreVPsEqualSizesForLeaf2)){{$tooltip->AreVPsEqualSizesForLeaf2}}@endif">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="distanceFromTopOfDoorforLeaf22" class="">Distance from top of door for Leaf 2
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->distanceFromTopOfDoorforLeaf2)){{$tooltip->distanceFromTopOfDoorforLeaf2}}@endif'));
                                                </script>
                                            </label>
                                            <input name="distanceFromTopOfDoorforLeaf2" id="distanceFromTopOfDoorforLeaf22" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->distanceFromTopOfDoorforLeaf2)){{$tooltip->distanceFromTopOfDoorforLeaf2}}@endif">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="distanceFromTheEdgeOfDoorforLeaf22" class="">Distance from the edge for Leaf 2
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->distanceFromTheEdgeOfDoorforLeaf2)){{$tooltip->distanceFromTheEdgeOfDoorforLeaf2}}@endif'));
                                                </script>
                                            </label>
                                            <input name="distanceFromTheEdgeOfDoorforLeaf2" id="distanceFromTheEdgeOfDoorforLeaf22" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->distanceFromTheEdgeOfDoorforLeaf2)){{$tooltip->distanceFromTheEdgeOfDoorforLeaf2}}@endif">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="distanceBetweenVPsforLeaf2" class="">Distance between VP's
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->distanceBetweenVPsforLeaf2)){{$tooltip->distanceBetweenVPsforLeaf2}}@endif'));
                                                </script>
                                            </label>
                                            <input name="distanceBetweenVPsforLeaf2" id="distanceBetweenVPsforLeaf2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->distanceBetweenVPsforLeaf2)){{$tooltip->distanceBetweenVPsforLeaf2}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP2Width2" class="">Leaf 2 VP Width
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->vP2Width)){{$tooltip->vP2Width}}@endif'));
                                                </script>
                                            </label>
                                            <input name="vP2Width" id="vP2Width2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->vP2Width)){{$tooltip->vP2Width}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP2Height1" class="">Leaf 2 VP Height (1)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->vP2Height1)){{$tooltip->vP2Height1}}@endif'));
                                                </script>
                                            </label>
                                            <input name="vP2Height1" id="vP2Height1" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->vP2Height1)){{$tooltip->vP2Height1}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP2Height22" class="">Leaf 2 VP Height (2)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->vP2Height2)){{$tooltip->vP2Height2}}@endif'));
                                                </script>
                                            </label>
                                            <input name="vP2Height2" id="vP2Height22" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->vP2Height2)){{$tooltip->vP2Height2}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP2Height32" class="">Leaf 2 VP Height (3)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->vP2Height3)){{$tooltip->vP2Height3}}@endif'));
                                                </script>
                                            </label>
                                            <input name="vP2Height3" id="vP2Height32" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->vP2Height3)){{$tooltip->vP2Height3}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP2Height42" class="">Leaf 2 VP Height (4)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->vP2Height4)){{$tooltip->vP2Height4}}@endif'));
                                                </script>
                                            </label>
                                            <input name="vP2Height4" id="vP2Height42" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->vP2Height4)){{$tooltip->vP2Height4}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="vP2Height52" class="">Leaf 2 VP Height (5)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->vP2Height5)){{$tooltip->vP2Height5}}@endif'));
                                                </script>
                                            </label>
                                            <input name="vP2Height5" id="vP2Height52" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->vP2Height5)){{$tooltip->vP2Height5}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="lazingIntegrityOrInsulationIntegrity" class="">Glass Integrity
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->lazingIntegrityOrInsulationIntegrity)){{$tooltip->lazingIntegrityOrInsulationIntegrity}}@endif'));
                                                </script>
                                            </label>

                                            <input name="lazingIntegrityOrInsulationIntegrity" id="lazingIntegrityOrInsulationIntegrity" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->lazingIntegrityOrInsulationIntegrity)){{$tooltip->lazingIntegrityOrInsulationIntegrity}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glassType" class="">Glass Type
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->glassType)){{$tooltip->glassType}}@endif'));
                                                </script>
                                            </label>
                                            <input name="glassType" id="glassType" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->glassType)){{$tooltip->glassType}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glassThickness" class="">Glass Thickness
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->glassThickness)){{$tooltip->glassThickness}}@endif'));
                                                </script>
                                            </label>
                                            <input name="glassThickness" id="glassThickness" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->glassThickness)){{$tooltip->glassThickness}}@endif">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glazingSystems2" class="">Glazing Systems
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->glazingSystems)){{$tooltip->glazingSystems}}@endif'));
                                                </script>
                                            </label>
                                            <input name="glazingSystems" id="glazingSystems2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->glazingSystems)){{$tooltip->glazingSystems}}@endif">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glazingSystemsThickness" class="">Glazing System Thickness
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->glazingSystemsThickness)){{$tooltip->glazingSystemsThickness}}@endif'));
                                                </script>
                                            </label>
                                            <input name="glazingSystemsThickness" id="glazingSystemsThickness" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->glazingSystemsThickness)){{$tooltip->glazingSystemsThickness}}@endif">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glazingBeads" class="">Glazing Beads
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->glazingBeads)){{$tooltip->glazingBeads}}@endif'));
                                                </script>
                                            </label>
                                            <input name="glazingBeads" id="glazingBeads" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->glazingBeads)){{$tooltip->glazingBeads}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glazingBeadsThickness" class="">Glazing Beads Thickness
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->glazingBeadsThickness)){{$tooltip->glazingBeadsThickness}}@endif'));
                                                </script>
                                            </label>
                                            <input name="glazingBeadsThickness" id="glazingBeadsThickness" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->glazingBeadsThickness)){{$tooltip->glazingBeadsThickness}}@endif">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glazingBeadsWidth" class="">Glazing Beads Width
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->glazingBeadsWidth)){{$tooltip->glazingBeadsWidth}}@endif'));
                                                </script>
                                            </label>
                                            <input name="glazingBeadsWidth" id="glazingBeadsWidth" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->glazingBeadsWidth)){{$tooltip->glazingBeadsWidth}}@endif">
                                        </div>
                                    </div>

                                    <!-- <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glazingBeadsHeight" class="">Glazing Beads Height
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->glazingBeadsHeight)){{$tooltip->glazingBeadsHeight}}@endif'));
                                            </script>
                                            </label>
                                            <input name="glazingBeadsHeight" id="glazingBeadsHeight"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->glazingBeadsHeight)){{$tooltip->glazingBeadsHeight}}@endif">
                                        </div>
                                    </div> -->

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glazingBeadsFixingDetail" class="">Glazing Bead Fixing Detail
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->glazingBeadsFixingDetail)){{$tooltip->glazingBeadsFixingDetail}}@endif'));
                                                </script>
                                            </label>
                                            <input name="glazingBeadsFixingDetail" id="glazingBeadsFixingDetail" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->glazingBeadsFixingDetail)){{$tooltip->glazingBeadsFixingDetail}}@endif">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="glazingBeadSpecies" class="">Glazing Bead Species
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->glazingBeadSpecies)){{$tooltip->glazingBeadSpecies}}@endif'));
                                                </script>
                                            </label>
                                            <input name="glazingBeadSpecies" id="glazingBeadSpecies" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->glazingBeadSpecies)){{$tooltip->glazingBeadSpecies}}@endif">
                                        </div>
                                    </div>
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
                                            <label for="frameMaterial2">Frame Material
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->frameMaterial)){{$tooltip->frameMaterial}}@endif'));
                                                </script>
                                            </label>
                                            <input name="frameMaterial" id="frameMaterial2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->frameMaterial)){{$tooltip->frameMaterial}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="frameType2">Frame Type
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->frameType)){{$tooltip->frameType}}@endif'));
                                                </script>
                                            </label>
                                            <input name="frameType" id="frameType2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->frameType)){{$tooltip->frameType}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="plantonStopWidth2">Plant on Stop Width(min 32)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->plantonStopWidth)){{$tooltip->plantonStopWidth}}@endif'));
                                                </script>
                                            </label>
                                            <input name="plantonStopWidth" id="plantonStopWidth2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->plantonStopWidth)){{$tooltip->plantonStopWidth}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="plantonStopHeight2">Plant on Stop Height(min 12)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->plantonStopHeight)){{$tooltip->plantonStopHeight}}@endif'));
                                                </script>
                                            </label>
                                            <input name="plantonStopHeight" id="plantonStopHeight2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->plantonStopHeight)){{$tooltip->plantonStopHeight}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="rebatedWidth">Rebated Width (min 32)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->rebatedWidth)){{$tooltip->rebatedWidth}}@endif'));
                                                </script>
                                            </label>
                                            <input name="rebatedWidth" id="rebatedWidth" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->rebatedWidth)){{$tooltip->rebatedWidth}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="rebatedHeight">Rebated Height (min 12)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->rebatedHeight)){{$tooltip->rebatedHeight}}@endif'));
                                                </script>
                                            </label>
                                            <input name="rebatedHeight" id="rebatedHeight" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->rebatedHeight)){{$tooltip->rebatedHeight}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3" hidden>
                                        <div class="position-relative form-group">
                                            <label for="frameTypeDimensions2">Dimensions
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->frameTypeDimensions)){{$tooltip->frameTypeDimensions}}@endif'));
                                                </script>
                                            </label>
                                            <input name="frameTypeDimensions" id="frameTypeDimensions2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->frameTypeDimensions)){{$tooltip->frameTypeDimensions}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="frameWidth2">Frame Width
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->frameWidth)){{$tooltip->frameWidth}}@endif'));
                                                </script>
                                            </label>
                                            <input name="frameWidth" id="frameWidth2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->frameWidth)){{$tooltip->frameWidth}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="frameHeight2">Frame Height
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->frameHeight)){{$tooltip->frameHeight}}@endif'));
                                                </script>
                                            </label>
                                            <input name="frameHeight" id="frameHeight2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->frameHeight)){{$tooltip->frameHeight}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="frameDepth2">Frame Depth (min 80)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->frameDepth)){{$tooltip->frameDepth}}@endif'));
                                                </script>
                                            </label>
                                            <input name="frameDepth" id="frameDepth2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->frameDepth)){{$tooltip->frameDepth}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="frameFinish2">Frame Finish
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->frameFinish)){{$tooltip->frameFinish}}@endif'));
                                                </script>
                                            </label>
                                            <input name="frameFinish" id="frameFinish2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->frameFinish)){{$tooltip->frameFinish}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="framefinishColor2">Frame finish Color
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->framefinishColor)){{$tooltip->framefinishColor}}@endif'));
                                            </script>
                                            </label>
                                            <input name="framefinishColor" id="framefinishColor2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->framefinishColor)){{$tooltip->framefinishColor}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="extLiner2">Ext-Liner
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->extLiner)){{$tooltip->extLiner}}@endif'));
                                                </script>
                                            </label>
                                            <input name="extLiner" id="extLiner2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->extLiner)){{$tooltip->extLiner}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="frameCostuction2" class="">Door Frame Construction
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->frameCostuction)){{$tooltip->frameCostuction}}@endif'));
                                                </script>
                                            </label>
                                            <input name="frameCostuction" id="frameCostuction2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->frameCostuction)){{$tooltip->frameCostuction}}@endif">
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="extLinerValue2">Ext-Liner Value
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->extLinerValue)){{$tooltip->extLinerValue}}@endif'));
                                            </script>
                                            </label>
                                            <input name="extLinerValue" id="extLinerValue2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->extLinerValue)){{$tooltip->extLinerValue}}@endif">
                                        </div>
                                    </div> -->
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="extLinerSize2">Ext-Liner Size
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->extLinerSize)){{$tooltip->extLinerSize}}@endif'));
                                                </script>
                                            </label>
                                            <input name="extLinerSize" id="extLinerSize2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->extLinerSize)){{$tooltip->extLinerSize}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="extLinerThickness2">Ext-Liner Thickness
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->extLinerThickness)){{$tooltip->extLinerThickness}}@endif'));
                                                </script>
                                            </label>
                                            <input name="extLinerThickness" id="extLinerThickness2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->extLinerThickness)){{$tooltip->extLinerThickness}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="extLinerFinish2">Ext-Liner FInish
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->extLinerFinish)){{$tooltip->extLinerFinish}}@endif'));
                                                </script>
                                            </label>
                                            <input name="extLinerFinish" id="extLinerFinish2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->extLinerFinish)){{$tooltip->extLinerFinish}}@endif">
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="intumescentSeal2" class="">Intumescent Seal
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->intumescentSeal)){{$tooltip->intumescentSeal}}@endif'));
                                            </script>
                                            </label>
                                            <input name="intumescentSeal" id="intumescentSeal2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->intumescentSeal)){{$tooltip->intumescentSeal}}@endif">
                                        </div>
                                    </div> -->

                                    <!-- <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="intumescentSealColor2">Intumescent Seal Color
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->intumescentSealColor)){{$tooltip->intumescentSealColor}}@endif'));
                                            </script>
                                            </label>
                                            <input name="intumescentSealColor" id="intumescentSealColor2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->intumescentSealColor)){{$tooltip->intumescentSealColor}}@endif">
                                        </div>
                                    </div> -->
                                    <!-- <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="intumescentSealSize2">Intumescent Seal Size
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->intumescentSealSize)){{$tooltip->intumescentSealSize}}@endif'));
                                            </script>
                                            </label>
                                            <input name="intumescentSealSize" id="intumescentSealSize2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->intumescentSealSize)){{$tooltip->intumescentSealSize}}@endif">
                                        </div>
                                    </div> -->
                                    <!-- <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="ironmongerySet2">Ironmongery Set
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->ironmongerySet)){{$tooltip->ironmongerySet}}@endif'));
                                            </script>
                                            </label>
                                            <input name="ironmongerySet" id="ironmongerySet2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->ironmongerySet)){{$tooltip->ironmongerySet}}@endif">
                                        </div>
                                    </div> -->
                                    <!-- <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="selectironmongerySet">Select Ironmongery Set
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->selectironmongerySet)){{$tooltip->selectironmongerySet}}@endif'));
                                            </script>
                                            </label>
                                            <input name="selectironmongerySet" id="selectironmongerySet"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->selectironmongerySet)){{$tooltip->selectironmongerySet}}@endif">
                                        </div>
                                    </div> -->
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="specialFeatureRefs2">Special Feature Refs
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->specialFeatureRefs)){{$tooltip->specialFeatureRefs}}@endif'));
                                                </script>
                                            </label>
                                            <input name="specialFeatureRefs" id="specialFeatureRefs2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->specialFeatureRefs)){{$tooltip->specialFeatureRefs}}@endif">
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
                                            <label for="overpanel2">Overpanel
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->overpanel)){{$tooltip->overpanel}}@endif'));
                                                </script>
                                            </label>
                                            <input name="overpanel" id="overpanel2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->overpanel)){{$tooltip->overpanel}}@endif">
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="OPLippingThickness2">OP Lipping Thickness
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->OPLippingThickness)){{$tooltip->OPLippingThickness}}@endif'));
                                            </script>
                                            </label>
                                            <input name="OPLippingThickness" id="OPLippingThickness2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->OPLippingThickness)){{$tooltip->OPLippingThickness}}@endif">
                                        </div>
                                    </div> -->
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="oPWidth2" class="">OP Width
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->oPWidth)){{$tooltip->oPWidth}}@endif'));
                                                </script>
                                            </label>
                                            <input name="oPWidth" id="oPWidth2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->oPWidth)){{$tooltip->oPWidth}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="oPHeigth2" class="">OP Height (Max-value:600)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->oPHeigth)){{$tooltip->oPHeigth}}@endif'));
                                                </script>
                                            </label>
                                            <input name="oPHeigth" id="oPHeigth2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->oPHeigth)){{$tooltip->oPHeigth}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="OpBeadThickness">Fan Light/ Over Panel Frame Thickness
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->OpBeadThickness)){{$tooltip->OpBeadThickness}}@endif'));
                                                </script>
                                            </label>
                                            <input name="OpBeadThickness" id="OpBeadThickness" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->OpBeadThickness)){{$tooltip->OpBeadThickness}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="OpBeadHeight">Fan Light/ Over Panel Frame Depth
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->OpBeadHeight)){{$tooltip->OpBeadHeight}}@endif'));
                                                </script>
                                            </label>
                                            <input name="OpBeadHeight" id="OpBeadHeight" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->OpBeadHeight)){{$tooltip->OpBeadHeight}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="opTransom2" class="">OP Transom
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->opTransom)){{$tooltip->opTransom}}@endif'));
                                                </script>
                                            </label>
                                            <input name="opTransom" id="opTransom2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->opTransom)){{$tooltip->opTransom}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="transomThickness2" class="">Transom Thickness
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->transomThickness)){{$tooltip->transomThickness}}@endif'));
                                                </script>
                                            </label>
                                            <input name="transomThickness" id="transomThickness2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->transomThickness)){{$tooltip->transomThickness}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="opGlassInopGlassIntegritytegrity">Fan Light Glass
                                                Integrity
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->opGlassIntegrity)){{$tooltip->opGlassIntegrity}}@endif'));
                                                </script>
                                            </label>
                                            <input name="opGlassIntegrity" id="opGlassIntegrity" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->opGlassIntegrity)){{$tooltip->opGlassIntegrity}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="opGlassType">Fan Light Glass Type
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->opGlassType)){{$tooltip->opGlassType}}@endif'));
                                                </script>
                                            </label>
                                            <input name="opGlassType" id="opGlassType" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->opGlassType)){{$tooltip->opGlassType}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="opGlazingBeads">Fan Light Glazing Beads
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->opGlazingBeads)){{$tooltip->opGlazingBeads}}@endif'));
                                                </script>
                                            </label>
                                            <input name="opGlazingBeads" id="opGlazingBeads" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->opGlazingBeads)){{$tooltip->opGlazingBeads}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group input-icons">
                                            <label for="opGlazingBeadSpecies">Fan Light Glazing Bead Species
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->opGlazingBeadSpecies)){{$tooltip->opGlazingBeadSpecies}}@endif'));
                                                </script>
                                            </label>
                                            <input name="opGlazingBeadSpecies" id="opGlazingBeadSpecies" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->opGlazingBeadSpecies)){{$tooltip->opGlazingBeadSpecies}}@endif">
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="opGlassType2" class="">OP Glass Type
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->opGlassType)){{$tooltip->opGlassType}}@endif'));
                                            </script>
                                            </label>
                                            <input name="opGlassType" id="opGlassType2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->opGlassType)){{$tooltip->opGlassType}}@endif">
                                        </div>
                                    </div> -->
                                    <!-- <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="opGlazingBeads2">OP Glazing Beads
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->opGlazingBeads)){{$tooltip->opGlazingBeads}}@endif'));
                                            </script>
                                            </label>
                                            <input name="opGlazingBeads" id="opGlazingBeads2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->opGlazingBeads)){{$tooltip->opGlazingBeads}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="opGlazingBeadSpecies2">OP Glazing Bead Species
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->opGlazingBeadSpecies)){{$tooltip->opGlazingBeadSpecies}}@endif'));
                                            </script>
                                            </label>
                                            <input name="opGlazingBeadSpecies" id="opGlazingBeadSpecies2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->opGlazingBeadSpecies)){{$tooltip->opGlazingBeadSpecies}}@endif">
                                        </div>
                                    </div> -->
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
                                            <label for="sideLight12">Side Light 1 (SL1)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->sideLight1)){{$tooltip->sideLight1}}@endif'));
                                                </script>
                                            </label>
                                            <input name="sideLight1" id="sideLight12" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->sideLight1)){{$tooltip->sideLight1}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="sideLight1GlassType2">Side Light 1 Glass Type
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->sideLight1GlassType)){{$tooltip->sideLight1GlassType}}@endif'));
                                                </script>
                                            </label>
                                            <input name="sideLight1GlassType" id="sideLight1GlassType2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->sideLight1GlassType)){{$tooltip->sideLight1GlassType}}@endif">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SideLight1BeadingType2">Beading Type
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->SideLight1BeadingType)){{$tooltip->SideLight1BeadingType}}@endif'));
                                                </script>
                                            </label>
                                            <input name="SideLight1BeadingType" id="SideLight1BeadingType2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->SideLight1BeadingType)){{$tooltip->SideLight1BeadingType}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SideLight1GlazingBeadSpecies2">Glazing Bead Species
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->SideLight1GlazingBeadSpecies)){{$tooltip->SideLight1GlazingBeadSpecies}}@endif'));
                                                </script>
                                            </label>
                                            <input name="SideLight1GlazingBeadSpecies" id="SideLight1GlazingBeadSpecies2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->SideLight1GlazingBeadSpecies)){{$tooltip->SideLight1GlazingBeadSpecies}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL1Width2">SL1 Width (Max-value:600)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->SL1Width)){{$tooltip->SL1Width}}@endif'));
                                                </script>
                                            </label>
                                            <input name="SL1Width" id="SL1Width2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->SL1Width)){{$tooltip->SL1Width}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL1Height2">SL1 Height
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->SL1Height)){{$tooltip->SL1Height}}@endif'));
                                                </script>
                                            </label>
                                            <input name="SL1Height" id="SL1Height2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->SL1Height)){{$tooltip->SL1Height}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SlBeadThickness">SL Bead Thickness
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->SlBeadThickness)){{$tooltip->SlBeadThickness}}@endif'));
                                                </script>
                                            </label>
                                            <input name="SlBeadThickness" id="SlBeadThickness" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->SlBeadThickness)){{$tooltip->SlBeadThickness}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SlBeadHeight">SL Bead Height
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->SlBeadHeight)){{$tooltip->SlBeadHeight}}@endif'));
                                                </script>
                                            </label>
                                            <input name="SlBeadHeight" id="SlBeadHeight" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->SlBeadHeight)){{$tooltip->SlBeadHeight}}@endif">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL1Depth2">SL1 Depth
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->SL1Depth)){{$tooltip->SL1Depth}}@endif'));
                                                </script>
                                            </label>
                                            <input name="SL1Depth" id="SL1Depth2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->SL1Depth)){{$tooltip->SL1Depth}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL1Transom2">SL1 Transom
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->SL1Transom)){{$tooltip->SL1Transom}}@endif'));
                                                </script>
                                            </label>
                                            <input name="SL1Transom" id="SL1Transom2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->SL1Transom)){{$tooltip->SL1Transom}}@endif">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="sideLight22">Side Light 2 (SL2)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->sideLight2)){{$tooltip->sideLight2}}@endif'));
                                                </script>
                                            </label>
                                            <input name="sideLight2" id="sideLight22" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->sideLight2)){{$tooltip->sideLight2}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="copyOfSideLite12">Do you want to copy Same as SL1?
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->copyOfSideLite1)){{$tooltip->copyOfSideLite1}}@endif'));
                                                </script>
                                            </label>
                                            <input name="copyOfSideLite1" id="copyOfSideLite12" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->copyOfSideLite1)){{$tooltip->copyOfSideLite1}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="sideLight2GlassType2">Side Light 2 Glass Type
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->sideLight2GlassType)){{$tooltip->sideLight2GlassType}}@endif'));
                                                </script>
                                            </label>
                                            <input name="sideLight2GlassType" id="sideLight2GlassType2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->sideLight2GlassType)){{$tooltip->sideLight2GlassType}}@endif">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SideLight2BeadingType2">Side Light 2 Beading Type
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->SideLight2BeadingType)){{$tooltip->SideLight2BeadingType}}@endif'));
                                                </script>
                                            </label>
                                            <input name="SideLight2BeadingType" id="SideLight2BeadingType2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->SideLight2BeadingType)){{$tooltip->SideLight2BeadingType}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SideLight2GlazingBeadSpecies2">Side Light 2 Glazing Bead Species
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->SideLight2GlazingBeadSpecies)){{$tooltip->SideLight2GlazingBeadSpecies}}@endif'));
                                                </script>
                                            </label>
                                            <input name="SideLight2GlazingBeadSpecies" id="SideLight2GlazingBeadSpecies2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->SideLight2GlazingBeadSpecies)){{$tooltip->SideLight2GlazingBeadSpecies}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL2Width2">SL2 Width (Max-value:600)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->SL2Width)){{$tooltip->SL2Width}}@endif'));
                                                </script>
                                            </label>
                                            <input name="SL2Width" id="SL2Width2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->SL2Width)){{$tooltip->SL2Width}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL2Height2">SL2 Height
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->SL2Height)){{$tooltip->SL2Height}}@endif'));
                                                </script>
                                            </label>
                                            <input name="SL2Height" id="SL2Height2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->SL2Height)){{$tooltip->SL2Height}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL2Depth2">SL2 Depth
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->SL2Depth)){{$tooltip->SL2Depth}}@endif'));
                                                </script>
                                            </label>
                                            <input name="SL2Depth" id="SL2Depth2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->SL2Depth)){{$tooltip->SL2Depth}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="SL2Transom2">SL2 Transom
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->SL2Transom)){{$tooltip->SL2Transom}}@endif'));
                                                </script>
                                            </label>
                                            <input name="SL2Transom" id="SL2Transom2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->SL2Transom)){{$tooltip->SL2Transom}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group input-icons">
                                            <label for="SLtransomHeightFromTop">Transom height from top
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->SLtransomHeightFromTop)){{$tooltip->SLtransomHeightFromTop}}@endif'));
                                                </script>
                                            </label>
                                            <input name="SLtransomHeightFromTop" id="SLtransomHeightFromTop" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->SLtransomHeightFromTop)){{$tooltip->SLtransomHeightFromTop}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group input-icons">
                                            <label for="SLtransomThickness">Transom Thickness (Min 32mm)
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->SLtransomThickness)){{$tooltip->SLtransomThickness}}@endif'));
                                                </script>
                                            </label>
                                            <input name="SLtransomThickness" id="SLtransomThickness" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->SLtransomThickness)){{$tooltip->SLtransomThickness}}@endif">
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
                                            <label for="lippingType2">Lipping Type
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->lippingType)){{$tooltip->lippingType}}@endif'));
                                                </script>
                                            </label>
                                            <input name="lippingType" id="lippingType2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->lippingType)){{$tooltip->lippingType}}@endif">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="lippingThickness2">Lipping Thickness
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->lippingThickness)){{$tooltip->lippingThickness}}@endif'));
                                                </script>
                                            </label>
                                            <input name="lippingThickness" id="lippingThickness2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->lippingThickness)){{$tooltip->lippingThickness}}@endif">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="lippingSpecies2">Lipping Species
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->lippingSpecies)){{$tooltip->lippingSpecies}}@endif'));
                                                </script>
                                            </label>
                                            <input name="lippingSpecies" id="lippingSpecies2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->lippingSpecies)){{$tooltip->lippingSpecies}}@endif">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="meetingStyle2">Meeting Style
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->meetingStyle)){{$tooltip->meetingStyle}}@endif'));
                                                </script>
                                            </label>
                                            <input name="meetingStyle" id="meetingStyle2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->meetingStyle)){{$tooltip->meetingStyle}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="scallopedLippingThickness2">Scalloped Lipping Thickness
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->scallopedLippingThickness)){{$tooltip->scallopedLippingThickness}}@endif'));
                                                </script>
                                            </label>
                                            <input name="scallopedLippingThickness" id="scallopedLippingThickness2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->scallopedLippingThickness)){{$tooltip->scallopedLippingThickness}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="flatLippingThickness2">Flat Lipping Thickness
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->flatLippingThickness)){{$tooltip->flatLippingThickness}}@endif'));
                                                </script>
                                            </label>
                                            <input name="flatLippingThickness" id="flatLippingThickness2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->flatLippingThickness)){{$tooltip->flatLippingThickness}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="rebatedLippingThickness2">Rebated Lipping Thickness
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->rebatedLippingThickness)){{$tooltip->rebatedLippingThickness}}@endif'));
                                                </script>
                                            </label>
                                            <input name="rebatedLippingThickness" id="rebatedLippingThickness2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->rebatedLippingThickness)){{$tooltip->rebatedLippingThickness}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="coreWidth12">Core Width 1
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->coreWidth1)){{$tooltip->coreWidth1}}@endif'));
                                                </script>
                                            </label>
                                            <input name="coreWidth1" id="coreWidth12" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->coreWidth1)){{$tooltip->coreWidth1}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="coreWidth22">Core Width 2
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->coreWidth2)){{$tooltip->coreWidth2}}@endif'));
                                                </script>
                                            </label>
                                            <input name="coreWidth2" id="coreWidth22" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->coreWidth2)){{$tooltip->coreWidth2}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="coreHeight2">Core Height
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->coreHeight)){{$tooltip->coreHeight}}@endif'));
                                                </script>
                                            </label>
                                            <input name="coreHeight" id="coreHeight2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->coreHeight)){{$tooltip->coreHeight}}@endif">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="intumescentSealType2">Intumescent Seal Type
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->intumescentSealType)){{$tooltip->intumescentSealType}}@endif'));
                                                </script>
                                            </label>
                                            <input name="intumescentSealType" id="intumescentSealType2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->intumescentSealType)){{$tooltip->intumescentSealType}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="intumescentSealLocation2">Intumescent Seal Location
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->intumescentSealLocation)){{$tooltip->intumescentSealLocation}}@endif'));
                                                </script>
                                            </label>
                                            <input name="intumescentSealLocation" id="intumescentSealLocation2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->intumescentSealLocation)){{$tooltip->intumescentSealLocation}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="intumescentSealColor2">Intumescent Seal Color
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->intumescentSealColor)){{$tooltip->intumescentSealColor}}@endif'));
                                            </script>
                                            </label>
                                            <input name="intumescentSealColor" id="intumescentSealColor2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->intumescentSealColor)){{$tooltip->intumescentSealColor}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="intumescentSealArrangement2">Intumescent Seal Arrangement
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->intumescentSealArrangement)){{$tooltip->intumescentSealArrangement}}@endif'));
                                                </script>
                                            </label>
                                            <input name="intumescentSealArrangement" id="intumescentSealArrangement2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->intumescentSealArrangement)){{$tooltip->intumescentSealArrangement}}@endif">
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
                                <h5 class="card-title" style="margin-top: 10px">Acoustics </h5>
                            </div>
                            <div class="">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="accoustics2">Acoustics
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->accoustics)){{$tooltip->accoustics}}@endif'));
                                                </script>
                                            </label>
                                            <input name="accoustics" id="accoustics2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->accoustics)){{$tooltip->accoustics}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="rWdBRating2">rW dB Rating
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->rWdBRating)){{$tooltip->rWdBRating}}@endif'));
                                                </script>
                                            </label>
                                            <input name="rWdBRating" id="rWdBRating2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->rWdBRating)){{$tooltip->rWdBRating}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                                <div class="position-relative form-group input-icons">
                                                    <label for="perimeterSeal1">Perimeter Seal 1
                                                            <script type="text/javascript">
                                                                document.write(Tooltip('@if(!empty($tooltip->perimeterSeal1)){{$tooltip->perimeterSeal1}}@endif'));
                                                            </script>
                                                    </label>
                                                    <input name="perimeterSeal1" id="perimeterSeal1" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->perimeterSeal1)){{$tooltip->perimeterSeal1}}@endif">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="position-relative form-group input-icons">
                                                    <label for="perimeterSeal2">Perimeter Seal 2
                                                            <script type="text/javascript">
                                                                document.write(Tooltip('@if(!empty($tooltip->perimeterSeal2)){{$tooltip->perimeterSeal2}}@endif'));
                                                            </script>
                                                    </label>
                                                    <input name="perimeterSeal2" id="perimeterSeal2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->perimeterSeal2)){{$tooltip->perimeterSeal2}}@endif">
                                                </div>
                                            </div>

                                            <div class="col-md-3">
                                                <div class="position-relative form-group input-icons">
                                                    <label for="thresholdSeal1">Threshold Seal 1
                                                            <script type="text/javascript">
                                                                document.write(Tooltip('@if(!empty($tooltip->thresholdSeal1)){{$tooltip->thresholdSeal1}}@endif'));
                                                            </script>
                                                    </label>
                                                    <input name="thresholdSeal1" id="thresholdSeal1" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->thresholdSeal1)){{$tooltip->thresholdSeal1}}@endif">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="position-relative form-group input-icons">
                                                    <label for="thresholdSeal2">Threshold Seal 2
                                                            <script type="text/javascript">
                                                                document.write(Tooltip('@if(!empty($tooltip->thresholdSeal1)){{$tooltip->thresholdSeal1}}@endif'));
                                                            </script>
                                                    </label>
                                                    <input name="thresholdSeal2" id="thresholdSeal2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->thresholdSeal1)){{$tooltip->thresholdSeal1}}@endif">
                                                </div>
                                            </div>

                                    <!-- <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="accousticsJambs2">Accoustics Jambs
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->accousticsJambs)){{$tooltip->accousticsJambs}}@endif'));
                                            </script>
                                            </label>
                                            <input name="accousticsJambs" id="accousticsJambs2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->accousticsJambs)){{$tooltip->accousticsJambs}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="accousticsHead2">Accoustics Head
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->accousticsHead)){{$tooltip->accousticsHead}}@endif'));
                                            </script>
                                            </label>
                                            <input name="accousticsHead" id="accousticsHead2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->accousticsHead)){{$tooltip->accousticsHead}}@endif">
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="thresholdSeal2">Threshold Seal
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->thresholdSeal)){{$tooltip->thresholdSeal}}@endif'));
                                            </script>
                                            </label>
                                            <input name="thresholdSeal" id="thresholdSeal2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->thresholdSeal)){{$tooltip->thresholdSeal}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="accousticsSeal2">Accoustics Seal
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->accousticsSeal)){{$tooltip->accousticsSeal}}@endif'));
                                            </script>
                                            </label>
                                            <input name="accousticsSeal" id="accousticsSeal2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->accousticsSeal)){{$tooltip->accousticsSeal}}@endif">
                                        </div>
                                    </div> -->
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="accousticsmeetingStiles2">Meeting Stiles
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->accousticsmeetingStiles)){{$tooltip->accousticsmeetingStiles}}@endif'));
                                                </script>
                                            </label>
                                            <input name="accousticsmeetingStiles" id="accousticsmeetingStiles2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->accousticsmeetingStiles)){{$tooltip->accousticsmeetingStiles}}@endif">
                                        </div>
                                    </div>
                                    <!-- <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="accousticsglassType2">Glass Type
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->accousticsglassType)){{$tooltip->accousticsglassType}}@endif'));
                                            </script>
                                            </label>
                                            <input name="accousticsglassType" id="accousticsglassType2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->accousticsglassType)){{$tooltip->accousticsglassType}}@endif">
                                        </div>
                                    </div> -->
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
                                            <label for="Architrave2">Architrave
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->Architrave)){{$tooltip->Architrave}}@endif'));
                                                </script>
                                            </label>
                                            <input name="Architrave" id="Architrave2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->Architrave)){{$tooltip->Architrave}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="architraveMaterial2">Architrave Material
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->architraveMaterial)){{$tooltip->architraveMaterial}}@endif'));
                                                </script>
                                            </label>
                                            <input name="architraveMaterial" id="architraveMaterial2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->architraveMaterial)){{$tooltip->architraveMaterial}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="architraveType2">Architrave Type
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->architraveType)){{$tooltip->architraveType}}@endif'));
                                                </script>
                                            </label>
                                            <input name="architraveType" id="architraveType2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->architraveType)){{$tooltip->architraveType}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="architraveWidth2">Architrave Width
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->architraveWidth)){{$tooltip->architraveWidth}}@endif'));
                                                </script>
                                            </label>
                                            <input name="architraveWidth" id="architraveWidth2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->architraveWidth)){{$tooltip->architraveWidth}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                                <div class="position-relative form-group">
                                                    <label for="architraveHeight">Architrave Thickness
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->architraveHeight)){{$tooltip->architraveHeight}}@endif'));
                                                    </script>
                                                    </label>
                                                    <input name="architraveHeight" id="architraveHeight" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->architraveHeight)){{$tooltip->architraveHeight}}@endif">
                                                </div>
                                            </div>
                                    <!-- <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="architraveDepth2">Architrave Depth
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->architraveDepth)){{$tooltip->architraveDepth}}@endif'));
                                                </script>
                                            </label>
                                            <input name="architraveDepth" id="architraveDepth2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->architraveDepth)){{$tooltip->architraveDepth}}@endif">
                                        </div>
                                    </div> -->
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="architraveFinish2">Architrave Finish
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->architraveFinish)){{$tooltip->architraveFinish}}@endif'));
                                                </script>
                                            </label>
                                            <input name="architraveFinish" id="architraveFinish2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->architraveFinish)){{$tooltip->architraveFinish}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                                <div class="position-relative form-group input-icons">
                                                    <label for="architravefinishColor">Architrave Finish color
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->architraveFinishcolor)){{$tooltip->architraveFinishcolor}}@endif'));
                                                    </script>
                                                 </label>
                                                 <input name="architraveFinishcolor" id="architraveFinishcolor" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->architraveFinishcolor)){{$tooltip->architraveFinishcolor}}@endif">
                                                </div>
                                            </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="architraveSetQty2">Architrave Set Qty
                                                <script type="text/javascript">
                                                    document.write(Tooltip('@if(!empty($tooltip->architraveSetQty)){{$tooltip->architraveSetQty}}@endif'));
                                                </script>
                                            </label>
                                            <input name="architraveSetQty" id="architraveSetQty2" placeholder="Enter Tooltip" type="text" class="form-control" value="@if(!empty($tooltip->architraveSetQty)){{$tooltip->architraveSetQty}}@endif">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <!-- Fitting Hardware/Ironmongery -->
                <!-- <div class="main-card mb-3 custom_card">
                    <div class="">
                        <div class="tab-content">
                            <div class="card-header">
                                <h5 class="card-title" style="margin-top: 10px">Fitting Hardware/Ironmongery </h5>
                                <input type="hidden" id="ironIronmongerydata">
                            </div>
                            <div class="">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="HingesKey2">Hinges
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->HingesKey)){{$tooltip->HingesKey}}@endif'));
                                            </script>
                                            </label>
                                            <input name="HingesKey" id="HingesKey2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->HingesKey)){{$tooltip->HingesKey}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="FloorSpringKey2">Floor Spring
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->FloorSpringKey)){{$tooltip->FloorSpringKey}}@endif'));
                                            </script>
                                            </label>
                                            <input name="FloorSpringKey" id="FloorSpringKey2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->FloorSpringKey)){{$tooltip->FloorSpringKey}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="lockesAndLatchesKey2">Locks And Latches
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->lockesAndLatchesKey)){{$tooltip->lockesAndLatchesKey}}@endif'));
                                            </script>
                                            </label>
                                            <input name="lockesAndLatchesKey" id="lockesAndLatchesKey2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->lockesAndLatchesKey)){{$tooltip->lockesAndLatchesKey}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="flushBoltsKey2">Flush Bolts
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->flushBoltsKey)){{$tooltip->flushBoltsKey}}@endif'));
                                            </script>
                                            </label>
                                            <input name="flushBoltsKey" id="flushBoltsKey2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->flushBoltsKey)){{$tooltip->flushBoltsKey}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="concealedOverheadCloserKey2">Concealed Overhead Closer
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->concealedOverheadCloserKey)){{$tooltip->concealedOverheadCloserKey}}@endif'));
                                            </script>
                                            </label>
                                                <input name="concealedOverheadCloserKey" id="concealedOverheadCloserKey2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->concealedOverheadCloserKey)){{$tooltip->concealedOverheadCloserKey}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="pullHandlesKey2">Pull Handles
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->pullHandlesKey)){{$tooltip->pullHandlesKey}}@endif'));
                                            </script>
                                            </label>
                                            <input name="pullHandlesKey" id="pullHandlesKey2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->pullHandlesKey)){{$tooltip->pullHandlesKey}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="pushHandlesValue2">Push Plates
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->pushHandlesValue)){{$tooltip->pushHandlesValue}}@endif'));
                                            </script>
                                            </label>
                                            <input name="pushHandlesValue" id="pushHandlesValue2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->pushHandlesValue)){{$tooltip->pushHandlesValue}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="kickPlates2">Kick Plates
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->kickPlates)){{$tooltip->kickPlates}}@endif'));
                                            </script>
                                            </label>
                                            <input name="kickPlates" id="kickPlates2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->kickPlates)){{$tooltip->kickPlates}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorSelectorsKey2">Door Selectors
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->doorSelectorsKey)){{$tooltip->doorSelectorsKey}}@endif'));
                                            </script>
                                            </label>
                                            <input name="doorSelectorsKey" id="doorSelectorsKey2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->doorSelectorsKey)){{$tooltip->doorSelectorsKey}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="panicHardwareKey2">Panic Hardware
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->panicHardwareKey)){{$tooltip->panicHardwareKey}}@endif'));
                                            </script>
                                            </label>
                                            <input name="panicHardwareKey" id="panicHardwareKey2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->panicHardwareKey)){{$tooltip->panicHardwareKey}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorSecurityViewerKey2">Door security viewer
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->doorSecurityViewerKey)){{$tooltip->doorSecurityViewerKey}}@endif'));
                                            </script>
                                            </label>
                                            <input name="doorSecurityViewerKey" id="doorSecurityViewerKey2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->doorSecurityViewerKey)){{$tooltip->doorSecurityViewerKey}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="morticeddropdownsealsKey2">Morticed drop down seals
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->morticeddropdownsealsKey)){{$tooltip->morticeddropdownsealsKey}}@endif'));
                                            </script>
                                            </label>
                                            <input name="morticeddropdownsealsKey" id="morticeddropdownsealsKey2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->morticeddropdownsealsKey)){{$tooltip->morticeddropdownsealsKey}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="facefixeddropsealsKey2">Face fixed drop seals
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->facefixeddropsealsKey)){{$tooltip->facefixeddropsealsKey}}@endif'));
                                            </script>
                                            </label>
                                            <input name="facefixeddropsealsKey" id="facefixeddropsealsKey2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->facefixeddropsealsKey)){{$tooltip->facefixeddropsealsKey}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="thresholdSealKey2">Threshold Seal
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->thresholdSealKey)){{$tooltip->thresholdSealKey}}@endif'));
                                            </script>
                                            </label>
                                            <input name="thresholdSealKey" id="thresholdSealKey2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->thresholdSealKey)){{$tooltip->thresholdSealKey}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="airtransfergrillsKey2">Air Transfer Grill
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->airtransfergrillsKey)){{$tooltip->airtransfergrillsKey}}@endif'));
                                            </script>
                                            </label>
                                            <input name="airtransfergrillsKey" id="airtransfergrillsKey2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->airtransfergrillsKey)){{$tooltip->airtransfergrillsKey}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="letterplatesKey2">Letterplates
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->letterplatesKey)){{$tooltip->letterplatesKey}}@endif'));
                                            </script>

                                            </label>
                                            <input name="letterplatesKey" id="letterplatesKey2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->letterplatesKey)){{$tooltip->letterplatesKey}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="cableWays2">Cable Ways
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->cableWays)){{$tooltip->cableWays}}@endif'));
                                            </script>
                                            </label>
                                            <input name="cableWays" id="cableWays2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->cableWays)){{$tooltip->cableWays}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="safeHingeKey2">Safe Hinge
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->safeHingeKey)){{$tooltip->safeHingeKey}}@endif'));
                                            </script>
                                            </label>
                                            <input name="safeHingeKey" id="safeHingeKey2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->safeHingeKey)){{$tooltip->safeHingeKey}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leverHandleKey2">Lever Handle
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->leverHandleKey)){{$tooltip->leverHandleKey}}@endif'));
                                            </script>
                                            </label>
                                            <input name="leverHandleKey" id="leverHandleKey2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->leverHandleKey)){{$tooltip->leverHandleKey}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorSignageKey2">Door Sinage
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->doorSignageKey)){{$tooltip->doorSignageKey}}@endif'));
                                            </script>
                                            </label>
                                            <input name="doorSignageKey" id="doorSignageKey2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->doorSignageKey)){{$tooltip->doorSignageKey}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="faceFixedDoorClosersKey2">Face Fixed Door Closer
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->faceFixedDoorClosersKey)){{$tooltip->faceFixedDoorClosersKey}}@endif'));
                                            </script>
                                            </label>
                                            <input name="faceFixedDoorClosersKey" id="faceFixedDoorClosersKey2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->faceFixedDoorClosersKey)){{$tooltip->faceFixedDoorClosersKey}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="thumbturnKey2">Thumbturn
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->thumbturnKey)){{$tooltip->thumbturnKey}}@endif'));
                                            </script>
                                            </label>
                                            <input name="thumbturnKey" id="thumbturnKey2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->thumbturnKey)){{$tooltip->thumbturnKey}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="keyholeEscutcheonKey2">Keyhole Escutchen
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->keyholeEscutcheonKey)){{$tooltip->keyholeEscutcheonKey}}@endif'));
                                            </script>
                                            </label>
                                            <input name="keyholeEscutcheonKey" id="keyholeEscutcheonKey2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->keyholeEscutcheonKey)){{$tooltip->keyholeEscutcheonKey}}@endif">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div> -->


                <!-- Transport -->
                <!-- <div class="main-card mb-3 custom_card">
                    <div class="">
                        <div class="tab-content">
                            <div class="card-header">
                                <h5 class="card-title" style="margin-top: 10px">Transport </h5>
                            </div>
                            <div class="">
                                <div class="form-row">
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="VehicleType2">Vehicle Type
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->VehicleType)){{$tooltip->VehicleType}}@endif'));
                                            </script>
                                            </label>
                                            <input name="VehicleType" id="VehicleType2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->VehicleType)){{$tooltip->VehicleType}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="deliveryTime2" class="">Delivery Time
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->deliveryTime)){{$tooltip->deliveryTime}}@endif'));
                                            </script>
                                            </label>
                                            <input name="deliveryTime" id="deliveryTime2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->deliveryTime)){{$tooltip->deliveryTime}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="packaging2">Packaging
                                            <script type="text/javascript">
                                            document.write(Tooltip('@if(!empty($tooltip->packaging)){{$tooltip->packaging}}@endif'));
                                            </script>
                                            </label>
                                            <input name="packaging" id="packaging2"
                                                placeholder="Enter Tooltip" type="text" class="form-control"
                                                value="@if(!empty($tooltip->packaging)){{$tooltip->packaging}}@endif">
                                        </div>
                                    </div> -->
                <!-- </div>
                            </div>
                        </div>
                    </div>
                </div> -->


                <div class="main-card mb-3 custom_card">
                    <div class="d-block text-right">
                        <button type="submit" id="submit" class="btn-wide btn btn-success">
                            Submit Now
                        </button>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>


@endsection
@section("script_section")

@endsection
