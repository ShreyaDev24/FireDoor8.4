<div class="door_main_data">
    <div class="door_option_list">
        <h6><b>Main Options</b></h6>
        <table class="table">

            <tr id="section" class="">
                <td>Leaf Construction</td>
                <td id="selected">@if (isset($Item['LeafConstruction'])){{ $Item['LeafConstruction'] }}@endif</td>
            </tr>
            <tr id="leafConstruction-section" class="@if (isset($Item['LeafConstruction'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Leaf Construction</td>
                <td id="leafConstruction-selected">@if (isset($Item['LeafConstruction'])){{ $Item['LeafConstruction'] }}@endif</td>
                {{--  <td id="leafConstruction-price">
                    @if (isset($Item['LeafConstruction']))
                        @foreach ($option_data as $row)
                            @if ($row->OptionSlug == 'leaf_construction' && $Item['LeafConstruction'] == $row->OptionKey)
                                £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                            @break
                        @endif
                    @endforeach
                @else
                    £0.00
                    @endif
                </td>  --}}
            </tr>
            <tr id="doorType-section" class="@if (isset($Item['DoorType'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td class="td-align">Door Type</td>
                <td id="doorType-selected" class="td-align3">@if (isset($Item['DoorType'])){{ $Item['DoorType'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="doorType-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="fireRating-section" class="@if (isset($Item['FireRating'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Fire Rating</td>
                <td id="fireRating-selected">@if (isset($Item['FireRating'])){{ $Item['FireRating'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="fireRating-price">
                        @if (isset($Item['FireRating']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'fire_rating' && $Item['FireRating'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="doorsetType-section" class="@if (isset($Item['DoorsetType'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Doorset Type</td>
                <td id="doorsetType-selected">@if (isset($Item['DoorsetType'])){{ $Item['DoorsetType'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="doorsetType-price">
                        @if (isset($Item['DoorsetType']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'door_configuration_doorset_type' && $Item['DoorsetType'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="swingType-section" class="@if (isset($Item['SwingType'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Swing Type</td>
                <td id="swingType-selected">@if (isset($Item['SwingType'])){{ $Item['SwingType'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="swingType-price">
                        @if (isset($Item['SwingType']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'door_configuration_swing_type' && $Item['SwingType'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="latchType-section" class="@if (isset($Item['LatchType'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Latch Type</td>
                <td id="latchType-selected">@if (isset($Item['LatchType'])){{ $Item['LatchType'] }}@endif</td>
                {{--  <td id="latchType-price">
                    @if (isset($Item['LatchType']))
                        @foreach ($option_data as $row)
                            @if ($row->OptionSlug == 'door_configuration_latch_type' && $Item['LatchType'] == $row->OptionKey)
                                £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                            @break
                        @endif
                    @endforeach
                @else
                    £0.00
                    @endif
                </td>  --}}
            </tr>
            <tr id="Handing-section" class="@if (isset($Item['Handing'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Handing</td>
                <td id="Handing-selected">@if (isset($Item['Handing'])){{ $Item['Handing'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="Handing-price">
                        @if (isset($Item['Handing']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'Handing' && $Item['Handing'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>

            <tr id="OpensInwards-section" class="@if (isset($Item['OpensInwards'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Pull Towards</td>
                <td id="OpensInwards-selected">@if (isset($Item['OpensInwards'])){{ $Item['OpensInwards'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="OpensInwards-price">
                        @if (isset($Item['OpensInwards']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'Opens_Inwards' && $Item['OpensInwards'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>

            {{-- <tr id="COC-section" class="@if (isset($Item['COC'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>COC</td>
                <td id="COC-selected">@if (isset($Item['COC'])){{ $Item['COC'] }}@endif</td>
                 @if (price_view_vlidator() == 1)
                    <td id="COC-price">
                        @if (isset($Item['COC']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'COC' && $Item['COC'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif
            </tr> --}}

            <tr id="tollerance-section" class="@if (isset($Item['Tollerance'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Tollerance</td>
                <td id="tollerance-selected">@if (isset($Item['Tollerance'])){{ $Item['Tollerance'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="tollerance-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="undercut-section" class="@if (isset($Item['Undercut'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Undercut</td>
                <td id="undercut-selected">@if (isset($Item['Undercut'])){{ $Item['Undercut'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="undercut-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="floorFinish-section" class="@if (isset($Item['FloorFinish'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Floor Finish</td>
                <td id="floorFinish-selected">@if (isset($Item['FloorFinish'])){{ $Item['FloorFinish'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="floorFinish-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="gap-section" class="@if (isset($Item['GAP'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>GAP</td>
                <td id="gap-selected">@if (isset($Item['GAP'])){{ $Item['GAP'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="gap-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="frameThickness-section" class="@if (isset($Item['FrameThickness'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Frame Thickness</td>
                <td id="frameThickness-selected">@if (isset($Item['FrameThickness'])){{ $Item['FrameThickness'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="frameThickness-price">£0.00</td>
                @endif  --}}
            </tr>

            <tr id="ironmongerySet-section" class="@if (isset($Item['IronmongerySet'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Ironmongery Set</td>
                <td id="ironmongerySet-selected">@if (isset($Item['IronmongerySet'])){{ $Item['IronmongerySet'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="ironmongerySet-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="IronmongeryID-section" class="@if (isset($Item['IronmongeryID'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Select Ironmongery Set </td>
                <td id="IronmongeryID-selected">@if (isset($Item['IronmongeryID'])){{ $Item['IronmongeryID'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="IronmongeryID-price">£0.00</td>
                @endif  --}}
            </tr>
        </table>
    </div>
</div>

<div class="door_main_data">
    <div class="door_option_list">
        <h6><b>Door Dimensions & Door Leaf</b></h6>
        <table class="table">
            <tr id="doorLeafFacing-section" class="@if (isset($Item['DoorLeafFacing'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Door Leaf Facing</td>
                <td id="doorLeafFacing-selected">@if (isset($Item['DoorLeafFacing'])){{ $Item['DoorLeafFacing'] }}@endif</td>
            </tr>
            <tr id="doorLeafFinishColor-section" class="@if (isset($Item['DoorLeafFinish'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Door Leaf Finish</td>
                <td id="doorLeafFinishColor-selected">@if (isset($Item['DoorLeafFinish'])){{ $Item['DoorLeafFinish'] }}@endif</td>
            </tr>
            <tr id="DoorDimensionsCode-section" class="@if (isset($Item['DoorDimensionsCode'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Door Dimensions</td>
                <td id="DoorDimensionsCode-selected">@if (isset($Item['DoorDimensionsCode'])){{ $Item['DoorDimensionsCode'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="DoorDimensions-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="DoorDimensionsCode2-section" class="@if (isset($Item['DoorDimensionsCode2'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Door Dimensions Leaf and a Half</td>
                <td id="DoorDimensionsCode2-selected">@if (isset($Item['DoorDimensionsCode2'])){{ $Item['DoorDimensionsCode2'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="DoorDimensions-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="leafWidth1-section" class="@if (isset($Item['LeafWidth1'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Leaf Width 1</td>
                <td id="leafWidth1-selected">@if (isset($Item['LeafWidth1'])){{ $Item['LeafWidth1'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="leafWidth1-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="adjustmentLeafWidth1-section" class="@if (isset($Item['AdjustmentLeafWidth1'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Adjustment Leaf Width 1</td>
                <td id="adjustmentLeafWidth1-selected">@if (isset($Item['AdjustmentLeafWidth1'])){{ $Item['AdjustmentLeafWidth1'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="leafWidth1-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="leafWidth2-section" class="@if (isset($Item['LeafWidth2'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Leaf Width 2</td>
                <td id="leafWidth2-selected">@if (isset($Item['LeafWidth2'])){{ $Item['LeafWidth2'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="leafWidth2-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="adjustmentLeafWidth2-section" class="@if (isset($Item['AdjustmentLeafWidth2'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Adjustment Leaf Width 2</td>
                <td id="adjustmentLeafWidth2-selected">@if (isset($Item['AdjustmentLeafWidth2'])){{ $Item['AdjustmentLeafWidth2'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="leafWidth2-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="leafHeightNoOP-section" class="@if (isset($Item['LeafHeight'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Leaf Height</td>
                <td id="leafHeightNoOP-selected">@if (isset($Item['LeafHeight'])){{ $Item['LeafHeight'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="leafHeightNoOP-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="adjustmentLeafHeightNoOP-section" class="@if (isset($Item['AdjustmentLeafHeightNoOP'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>AdjustmentLeaf Height</td>
                <td id="adjustmentLeafHeightNoOP-selected">@if (isset($Item['AdjustmentLeafHeightNoOP'])){{ $Item['AdjustmentLeafHeightNoOP'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="leafHeightNoOP-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="sOWidth-section" class="@if (isset($Item['SOWidth'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td class="td-align">S.O Width</td>
                <td id="sOWidth-selected" class="td-align3">@if (isset($Item['SOWidth'])){{ $Item['SOWidth'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="sOWidth-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="sOHeight-section" class="@if (isset($Item['SOHeight'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>S.O Height</td>
                <td id="sOHeight-selected">@if (isset($Item['SOHeight'])){{ $Item['SOHeight'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="sOHeight-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="sODepth-section" class="@if (isset($Item['SOWallThick'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>S.O Depth</td>
                <td id="sODepth-selected">@if (isset($Item['SOWallThick'])){{ $Item['SOWallThick'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="sODepth-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="doorThickness-section" class="@if (isset($Item['LeafThickness'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Door Thickness</td>
                <td id="doorThickness-selected">@if (isset($Item['LeafThickness'])){{ $Item['LeafThickness'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="doorThickness-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="hinge1Location-section" class="@if (isset($Item['hinge1Location'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Hinge 1 Location</td>
                <td id="hinge1Location-selected">@if (isset($Item['hinge1Location'])){{ $Item['hinge1Location'] }}@endif</td>
            </tr>
            <tr id="hinge2Location-section" class="@if (isset($Item['hinge2Location'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Hinge 2 Location</td>
                <td id="hinge2Location-selected">@if (isset($Item['hinge2Location'])){{ $Item['hinge2Location'] }}@endif</td>
            </tr>
            <tr id="hinge3Location-section" class="@if (isset($Item['hinge3Location'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Hinge 3 Location</td>
                <td id="hinge3Location-selected">@if (isset($Item['hinge3Location'])){{ $Item['hinge3Location'] }}@endif</td>
            </tr>
            <tr id="hingeCenterCheck-section" class="@if (isset($Item['hingeCenterCheck'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Hinge Center</td>
                <td id="hingeCenterCheck-selected">@if (isset($Item['hingeCenterCheck'])){{ $Item['hingeCenterCheck'] }}@endif</td>
            </tr>

            <tr id="decorativeGroves-section" class="@if (isset($Item['DecorativeGroves'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Decorative Groves</td>
                <td id="decorativeGroves-selected">@if (isset($Item['DecorativeGroves'])){{ $Item['DecorativeGroves'] }}@endif</td>
            </tr>
            <tr id="doorDimensionGroove-section" class="@if (isset($Item['groovesNumber'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Grooves Icon</td>
                <td id="doorDimensionGroove-selected">@if (isset($Item['groovesNumber'])){{ $Item['groovesNumber'] }}@endif</td>
            </tr>

            <tr id="grooveWidth-section" class="@if (isset($Item['GrooveWidth'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Groove Width</td>
                <td id="grooveWidth-selected">@if (isset($Item['GrooveWidth'])){{ $Item['GrooveWidth'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="grooveWidth-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="grooveDepth-section" class="@if (isset($Item['GrooveDepth'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Groove Depth</td>
                <td id="grooveDepth-selected">@if (isset($Item['GrooveDepth'])){{ $Item['GrooveDepth'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="grooveDepth-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="maxNumberOfGroove-section" class="@if (isset($Item['MaxNumberOfGroove'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Maximum Number of Groove</td>
                <td id="maxNumberOfGroove-selected">@if (isset($Item['MaxNumberOfGroove'])){{ $Item['MaxNumberOfGroove'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="maxNumberOfGroove-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="numberOfGroove-section" class="@if (isset($Item['NumberOfGroove'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Number of Grooves</td>
                <td id="numberOfGroove-selected">@if (isset($Item['NumberOfGroove'])){{ $Item['NumberOfGroove'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="numberOfGroove-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="numberOfVerticalGroove-section" class="@if (isset($Item['NumberOfVerticalGroove'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>No. of Vertical Grooves</td>
                <td id="numberOfVerticalGroove-selected">@if (isset($Item['NumberOfVerticalGroove'])){{ $Item['NumberOfVerticalGroove'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="numberOfVerticalGroove-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="numberOfHorizontalGroove-section" class="@if (isset($Item['NumberOfHorizontalGroove'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>No. of Horizontal Grooves</td>
                <td id="numberOfHorizontalGroove-selected">@if (isset($Item['NumberOfHorizontalGroove'])){{ $Item['NumberOfHorizontalGroove'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="numberOfHorizontalGroove-price">£0.00</td>
                @endif  --}}
            </tr>

        </table>
    </div>
</div>

<div class="door_main_data">
    <div class="door_option_list">
        <h6><b>Vision Panel</b></h6>
        <table class="table">
            <tr id="leaf1VisionPanel-section" class="@if (isset($Item['Leaf1VisionPanel'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td class="td-align">Leaf 1 Vision Panel</td>
                <td id="leaf1VisionPanel-selected" class="td-align3">@if (isset($Item['Leaf1VisionPanel'])){{ $Item['Leaf1VisionPanel'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="leaf1VisionPanel-price">
                        @if (isset($Item['Leaf1VisionPanel']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'leaf1_vision_panel' && $Item['Leaf1VisionPanel'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="leaf1VisionPanelShape-section" class="@if (isset($Item['Leaf1VisionPanelShape'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Vision Panel Shape</td>
                <td id="leaf1VisionPanelShape-selected">@if (isset($Item['Leaf1VisionPanelShape'])){{ $Item['Leaf1VisionPanelShape'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="leaf1VisionPanelShape-price">
                        @if (isset($Item['Leaf1VisionPanelShape']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'Vision_panel_shape' && $Item['Leaf1VisionPanelShape'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="visionPanelQuantity-section" class="@if (isset($Item['VisionPanelQuantity'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Vision Panel Quantity</td>
                <td id="visionPanelQuantity-selected">@if (isset($Item['VisionPanelQuantity'])){{ $Item['VisionPanelQuantity'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="visionPanelQuantity-price">
                        @if (isset($Item['VisionPanelQuantity']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'leaf1_vision_panel_Qty' && $Item['VisionPanelQuantity'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="AreVPsEqualSizes-section" class="@if (isset($Item['AreVPsEqualSizesForLeaf1'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Are VP's equal sizes?</td>
                <td id="AreVPsEqualSizes-selected">@if (isset($Item['AreVPsEqualSizesForLeaf1'])){{ $Item['AreVPsEqualSizesForLeaf1'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="AreVPsEqualSizes-price">
                        @if (isset($Item['AreVPsEqualSizesForLeaf1']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'leaf1are_vps_equal_sizes' && $Item['AreVPsEqualSizesForLeaf1'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="distanceFromTopOfDoor-section" class="@if (isset($Item['DistanceFromtopOfDoor'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Distance from top of door</td>
                <td id="distanceFromTopOfDoor-selected">@if (isset($Item['DistanceFromtopOfDoor'])){{ $Item['DistanceFromtopOfDoor'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="distanceFromTopOfDoor-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="distanceFromTheEdgeOfDoor-section" class="@if (isset($Item['DistanceFromTheEdgeOfDoor'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Distance from the edge</td>
                <td id="distanceFromTheEdgeOfDoor-selected">@if (isset($Item['DistanceFromTheEdgeOfDoor'])){{ $Item['DistanceFromTheEdgeOfDoor'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="distanceFromTheEdgeOfDoor-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="distanceBetweenVPs-section" class="@if (isset($Item['DistanceBetweenVPs'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Distance between VP's</td>
                <td id="distanceBetweenVPs-selected">@if (isset($Item['DistanceBetweenVPs'])){{ $Item['DistanceBetweenVPs'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="distanceBetweenVPs-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="vP1Width-section" class="@if (isset($Item['Leaf1VPWidth'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Leaf 1 VP Width</td>
                <td id="vP1Width-selected">@if (isset($Item['Leaf1VPWidth'])){{ $Item['Leaf1VPWidth'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="vP1Width-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="vP1Height1-section" class="@if (isset($Item['Leaf1VPHeight1'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Leaf 1 VP Height (1) </td>
                <td id="vP1Height1-selected">@if (isset($Item['Leaf1VPHeight1'])){{ $Item['Leaf1VPHeight1'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="vP1Height1-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="vP1Height2-section" class="@if (isset($Item['Leaf1VPHeight2'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Leaf 1 VP Height (2)</td>
                <td id="vP1Height2-selected">@if (isset($Item['Leaf1VPHeight2'])){{ $Item['Leaf1VPHeight2'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="vP1Height2-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="vP1Height3-section" class="@if (isset($Item['Leaf1VPHeight3'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Leaf 1 VP Height (3)</td>
                <td id="vP1Height3-selected">@if (isset($Item['Leaf1VPHeight3'])){{ $Item['Leaf1VPHeight3'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="vP1Height3-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="vP1Height4-section" class="@if (isset($Item['Leaf1VPHeight4'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Leaf 1 VP Height (4)</td>
                <td id="vP1Height4-selected">@if (isset($Item['Leaf1VPHeight4'])){{ $Item['Leaf1VPHeight4'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="vP1Height4-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="vP1Height5-section" class="@if (isset($Item['Leaf1VPHeight5'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Leaf 1 VP Height (5)</td>
                <td id="vP1Height5-selected">@if (isset($Item['Leaf1VPHeight5'])){{ $Item['Leaf1VPHeight5'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="vP1Height5-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="leaf1VpAreaSizeM2-section" class="@if (isset($Item['Leaf1VPAreaSizem2'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Leaf 1 VP Area Size m2</td>
                <td id="leaf1VpAreaSizeM2-selected">@if (isset($Item['Leaf1VPAreaSizem2'])){{ $Item['Leaf1VPAreaSizem2'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="leaf1VpAreaSizeM2-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="leaf2VisionPanel-section" class="@if (isset($Item['Leaf2VisionPanel'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Leaf 2 Vision Panel</td>
                <td id="leaf2VisionPanel-selected">@if (isset($Item['Leaf2VisionPanel'])){{ $Item['Leaf2VisionPanel'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="leaf2VisionPanel-price">
                        @if (isset($Item['Leaf2VisionPanel']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'leaf1_vision_panel' && $Item['Leaf2VisionPanel'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="vpSameAsLeaf1-section" class="@if (isset($Item['sVPSameAsLeaf1'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Is VP same as Leaf 1?</td>
                <td id="vpSameAsLeaf1-selected">@if (isset($Item['sVPSameAsLeaf1'])){{ $Item['sVPSameAsLeaf1'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="vpSameAsLeaf1-price">
                        @if (isset($Item['sVPSameAsLeaf1']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'leaf1are_vps_equal_sizes' && $Item['sVPSameAsLeaf1'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="visionPanelQuantityforLeaf2-section" class="@if (isset($Item['Leaf2VisionPanelQuantity'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Leaf 2 Vision Panel Quantity</td>
                <td id="visionPanelQuantityforLeaf2-selected">@if (isset($Item['Leaf2VisionPanelQuantity'])){{ $Item['Leaf2VisionPanelQuantity'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="visionPanelQuantityforLeaf2-price">
                        @if (isset($Item['Leaf2VisionPanelQuantity']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'leaf1_vision_panel_Qty' && $Item['Leaf2VisionPanelQuantity'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="AreVPsEqualSizesForLeaf2-section" class="@if (isset($Item['AreVPsEqualSizesForLeaf2'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Are VP's equal sizes for leaf 2?</td>
                <td id="AreVPsEqualSizesForLeaf2-selected">@if (isset($Item['AreVPsEqualSizesForLeaf2'])){{ $Item['AreVPsEqualSizesForLeaf2'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="AreVPsEqualSizesForLeaf2-price">
                        @if (isset($Item['AreVPsEqualSizesForLeaf2']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'leaf1are_vps_equal_sizes' && $Item['AreVPsEqualSizesForLeaf2'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="distanceFromTopOfDoorforLeaf2-section" class="@if (isset($Item['DistanceFromTopOfDoorForLeaf2'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Distance from top of door for Leaf 2</td>
                <td id="distanceFromTopOfDoorforLeaf2-selected">@if (isset($Item['DistanceFromTopOfDoorForLeaf2'])){{ $Item['DistanceFromTopOfDoorForLeaf2'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="distanceFromTopOfDoorforLeaf2-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="distanceFromTheEdgeOfDoorforLeaf2-section" class="@if (isset($Item['DistanceFromTheEdgeOfDoorforLeaf2'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Distance from the edge for Leaf 2 </td>
                <td id="distanceFromTheEdgeOfDoorforLeaf2-selected">@if (isset($Item['DistanceFromTheEdgeOfDoorforLeaf2'])){{ $Item['DistanceFromTheEdgeOfDoorforLeaf2'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="distanceFromTheEdgeOfDoorforLeaf2-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="distanceBetweenVPsforLeaf2-section" class="@if (isset($Item['DistanceBetweenVp'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Distance between VP's</td>
                <td id="distanceBetweenVPsforLeaf2-selected">@if (isset($Item['DistanceBetweenVp'])){{ $Item['DistanceBetweenVp'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="distanceBetweenVPsforLeaf2-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="vP2Width-section" class="@if (isset($Item['Leaf2VPWidth'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Leaf 2 VP Width</td>
                <td id="vP2Width-selected">@if (isset($Item['Leaf2VPWidth'])){{ $Item['Leaf2VPWidth'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="vP2Width-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="vP2Height1-section" class="@if (isset($Item['Leaf2VPHeight1'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Leaf 2 VP Height (1)</td>
                <td id="vP2Height1-selected">@if (isset($Item['Leaf2VPHeight1'])){{ $Item['Leaf2VPHeight1'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="vP2Height1-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="vP2Height2-section" class="@if (isset($Item['Leaf2VPHeight2'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Leaf 2 VP Height (2)</td>
                <td id="vP2Height2-selected">@if (isset($Item['Leaf2VPHeight2'])){{ $Item['Leaf2VPHeight2'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="vP2Height2-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="vP2Height3-section" class="@if (isset($Item['Leaf2VPHeight3'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Leaf 2 VP Height (3)</td>
                <td id="vP2Height3-selected">@if (isset($Item['Leaf2VPHeight3'])){{ $Item['Leaf2VPHeight3'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="vP2Height3-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="vP2Height4-section" class="@if (isset($Item['Leaf2VPHeight4'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Leaf 2 VP Height (4)</td>
                <td id="vP2Height4-selected">@if (isset($Item['Leaf2VPHeight4'])){{ $Item['Leaf2VPHeight4'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="vP2Height4-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="vP2Height5-section" class="@if (isset($Item['Leaf2VPHeight5'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Leaf 2 VP Height (5)</td>
                <td id="vP2Height5-selected">@if (isset($Item['Leaf2VPHeight5'])){{ $Item['Leaf2VPHeight5'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="vP2Height5-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="lazingIntegrityOrInsulationIntegrity-section" class="@if (isset($Item['GlassIntegrity'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Glass Integrity </td>
                <td id="lazingIntegrityOrInsulationIntegrity-selected">@if (isset($Item['GlassIntegrity'])){{ $Item['GlassIntegrity'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="lazingIntegrityOrInsulationIntegrity-price">
                        @if (isset($Item['GlassIntegrity']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'Glass_Integrity' && $Item['GlassIntegrity'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="glassType-section" class="@if (isset($Item['GlassType'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Glass Type</td>
                <td id="glassType-selected">@if (isset($Item['GlassType'])){{ $Item['GlassType'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="glassType-price">
                        @if (isset($Item['GlassType']))
                            @foreach ($selected_option_data as $row)
                                @if ($row->OptionSlug == 'leaf1_glass_type' && $Item['GlassType'] == $row->OptionKey)
                                £{{ ($row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost }}

                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                    @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="glassThickness-section" class="@if (isset($Item['GlassThickness'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Glass Thickness</td>
                <td id="glassThickness-selected">@if (isset($Item['GlassThickness'])){{ $Item['GlassThickness'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="glassThickness-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="glazingSystems-section" class="@if (isset($Item['GlazingSystems'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Glazing Systems</td>
                <td id="glazingSystems-selected">@if (isset($Item['GlazingSystems'])){{ $Item['GlazingSystems'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="glazingSystems-price">
                        @if (isset($Item['GlazingSystems']))
                            @foreach ($selected_option_data as $row)
                                @if ($row->OptionSlug == 'leaf1_glazing_systems' && $Item['GlazingSystems'] == $row->OptionKey)
                                    £{{ ($row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost }}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="glazingSystemsThickness-section" class="@if (isset($Item['GlazingSystemThickness'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Glazing System Thickness</td>
                <td id="glazingSystemsThickness-selected">@if (isset($Item['GlazingSystemThickness'])){{ $Item['GlazingSystemThickness'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="glazingSystemsThickness-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="glazingBeads-section" class="@if (isset($Item['GlazingBeads'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Glazing Beads</td>
                <td id="glazingBeads-selected">@if (isset($Item['GlazingBeads'])){{ $Item['GlazingBeads'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="glazingBeads-price">
                        @if (isset($Item['GlazingBeads']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'leaf1_glazing_beads' && $Item['GlazingBeads'] == $row->OptionKey)
                                    £{{ ($row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost }}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="glazingBeadsThickness-section" class="@if (isset($Item['GlazingBeadsThickness'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Glazing Beads Height</td>
                <td id="glazingBeadsThickness-selected">@if (isset($Item['GlazingBeadsThickness'])){{ $Item['GlazingBeadsThickness'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="glazingBeadsThickness-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="glazingBeadsWidth-section" class="@if (isset($Item['glazingBeadsWidth'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Glazing Beads Width</td>
                <td id="glazingBeadsWidth-selected">@if (isset($Item['glazingBeadsWidth'])){{ $Item['glazingBeadsWidth'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="glazingBeadsWidth-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="glazingBeadsHeight-section" class="@if (isset($Item['glazingBeadsHeight'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Glazing Beads Height</td>
                <td id="glazingBeadsHeight-selected">@if (isset($Item['glazingBeadsHeight'])){{ $Item['glazingBeadsHeight'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="glazingBeadsHeight-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="glazingBeadsFixingDetail-section" class="@if (isset($Item['glazingBeadsFixingDetail'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Glazing Bead Fixing Detail</td>
                <td id="glazingBeadsFixingDetail-selected">@if (isset($Item['glazingBeadsFixingDetail'])){{ $Item['glazingBeadsFixingDetail'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="glazingBeadsFixingDetail-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="glazingBeadSpecies-section" class="@if (isset($Item['GlazingBeadSpecies'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Glazing Bead Species</td>
                <td id="glazingBeadSpecies-selected">@if (isset($Item['GlazingBeadSpecies'])){{ $Item['GlazingBeadSpecies'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="glazingBeadSpecies-price">£0.00</td>
                @endif  --}}
            </tr>
        </table>
    </div>
</div>

<div class="door_main_data">
    <div class="door_option_list">
        <h6><b>Frame</b></h6>
        <table class="table">
            <tr id="frameMaterial-section" class="@if (isset($Item['FrameMaterial'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td class="td-align">Frame Material</td>
                <td id="frameMaterial-selected" class="td-align3">@if (isset($Item['FrameMaterial'])){{ $Item['FrameMaterial'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="frameMaterial-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="frameType-section" class="@if (isset($Item['FrameType'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Frame Type</td>
                <td id="frameType-selected">@if (isset($Item['FrameType'])){{ $Item['FrameType'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="frameType-price">
                        @if (isset($Item['FrameType']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'Frame_Type' && $Item['FrameType'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="ScallopedWidth-section" class="@if (isset($Item['ScallopedWidth'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Scalloped Width</td>
                <td id="ScallopedWidth-selected">@if (isset($Item['ScallopedWidth'])){{ $Item['ScallopedWidth'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="ScallopedWidth-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="ScallopedHeight-section" class="@if (isset($Item['ScallopedHeight'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Scalloped Height</td>
                <td id="ScallopedHeight-selected">@if (isset($Item['ScallopedHeight'])){{ $Item['ScallopedHeight'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="ScallopedHeight-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="plantonStopWidth-section" class="@if (isset($Item['PlantonStopWidth'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Plant on Stop Width</td>
                <td id="plantonStopWidth-selected">@if (isset($Item['PlantonStopWidth'])){{ $Item['PlantonStopWidth'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="plantonStopWidth-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="plantonStopHeight-section" class="@if (isset($Item['PlantonStopHeight'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Plant on Stop Height</td>
                <td id="plantonStopHeight-selected">@if (isset($Item['PlantonStopHeight'])){{ $Item['PlantonStopHeight'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="plantonStopHeight-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="rebatedWidth-section" class="@if (isset($Item['RebatedWidth'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Rebated Width</td>
                <td id="rebatedWidth-selected">@if (isset($Item['RebatedWidth'])){{ $Item['RebatedWidth'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="rebatedWidth-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="rebatedHeight-section" class="@if (isset($Item['RebatedHeight'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Rebated Height</td>
                <td id="rebatedHeight-selected">@if (isset($Item['RebatedHeight'])){{ $Item['RebatedHeight'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="rebatedHeight-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="frameWidth-section" class="@if (isset($Item['FrameWidth'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Frame Width</td>
                <td id="frameWidth-selected">@if (isset($Item['FrameWidth'])){{ $Item['FrameWidth'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="frameWidth-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="frameHeight-section" class="@if (isset($Item['FrameHeight'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Frame Height</td>
                <td id="frameHeight-selected">@if (isset($Item['FrameHeight'])){{ $Item['FrameHeight'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="frameHeight-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="frameDepth-section" class="@if (isset($Item['FrameDepth'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Frame Depth</td>
                <td id="frameDepth-selected">@if (isset($Item['FrameDepth'])){{ $Item['FrameDepth'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="frameDepth-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="frameFinish-section" class="@if (isset($Item['FrameFinish'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Frame Finish </td>
                <td id="frameFinish-selected">@if (isset($Item['FrameFinish'])){{ $Item['FrameFinish'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="frameFinish-price">
                        @if (isset($Item['FrameFinish']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'Frame_Finish' && $Item['FrameFinish'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="framefinishColor-section" class="@if (isset($Item['FrameFinishColor'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Frame finish Color </td>
                <td id="framefinishColor-selected">@if (isset($Item['FrameFinishColor'])){{ $Item['FrameFinishColor'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="framefinishColor-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="extLiner-section" class="@if (isset($Item['ExtLiner'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Ext-Liner</td>
                <td id="extLiner-selected">@if (isset($Item['ExtLiner'])){{ $Item['ExtLiner'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="extLiner-price">
                        @if (isset($Item['ExtLiner']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'Ext_Liner' && $Item['ExtLiner'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="frameCostuction-section" class="@if (isset($Item['DoorFrameConstruction'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Door Frame Construction</td>
                <td id="frameCostuction-selected">@if (isset($Item['DoorFrameConstruction'])){{ $Item['DoorFrameConstruction'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="frameCostuction-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="extLinerValue-section" class="@if (isset($Item['ExtLinerValue'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Ext-Liner Value </td>
                <td id="extLinerValue-selected">@if (isset($Item['ExtLinerValue'])){{ $Item['ExtLinerValue'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="extLinerValue-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="extLinerSize-section" class="@if (isset($Item['ExtLinerSize'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Ext-Liner Size</td>
                <td id="extLinerSize-selected">@if (isset($Item['ExtLinerSize'])){{ $Item['ExtLinerSize'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="extLinerSize-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="extLinerThickness-section" class="@if (isset($Item['ExtLinerThickness'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Ext-Liner Thickness</td>
                <td id="extLinerThickness-selected">@if (isset($Item['ExtLinerThickness'])){{ $Item['ExtLinerThickness'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="extLinerThickness-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="extLinerFinish-section" class="@if (isset($Item['ExtLinerFInish'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Ext-Liner Finish</td>
                <td id="extLinerFinish-selected">@if (isset($Item['ExtLinerFInish'])){{ $Item['ExtLinerFInish'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="extLinerFinish-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="intumescentSeal-section" class="@if (isset($Item['IntumescentSeal'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Intumescent Seal</td>
                <td id="intumescentSeal-selected">@if (isset($Item['IntumescentSeal'])){{ $Item['IntumescentSeal'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="intumescentSeal-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="IronmongeryID-section" class="@if (isset($Item['IronmongeryID'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Intumescent Seal Color </td>
                <td id="IronmongeryID-selected">@if (isset($Item['IronmongeryID'])){{ $Item['IronmongeryID'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="IronmongeryID-price">£0.00</td>
                @endif  --}}
            </tr>

            <tr id="intumescentSealSize-section" class="@if (isset($Item['IntumescentSealSize'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Intumescent Seal Size</td>
                <td id="intumescentSealSize-selected">@if (isset($Item['IntumescentSealSize'])){{ $Item['IntumescentSealSize'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="intumescentSealSize-price">£0.00</td>
                @endif  --}}
            </tr>

            <tr id="specialFeatureRefs-section" class="@if (isset($Item['SpecialFeatureRefs'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Special Feature Refs </td>
                <td id="specialFeatureRefs-selected">@if (isset($Item['SpecialFeatureRefs'])){{ $Item['SpecialFeatureRefs'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="specialFeatureRefs-price">£0.00</td>
                @endif  --}}
            </tr>

        </table>
    </div>
</div>

<div class="door_main_data">
    <div class="door_option_list">
        <h6><b>Over Panel Section</b></h6>
        <table class="table">
            <tr id="overpanel-section" class="@if (isset($Item['Overpanel'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td class="td-align">Overpanel</td>
                <td id="overpanel-selected" class="td-align3">@if (isset($Item['Overpanel'])){{ $Item['Overpanel'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="overpanel-price">
                        @if (isset($Item['Overpanel']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'door_configuration_overpanel' && $Item['Overpanel'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="OPLippingThickness-section" class="@if (isset($Item['OPLippingThickness'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>OP Lipping Thickness </td>
                <td id="OPLippingThickness-selected">@if (isset($Item['OPLippingThickness'])){{ $Item['OPLippingThickness'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="OPLippingThickness-price">
                        @if (isset($Item['OPLippingThickness']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'op_lipping_thickness' && $Item['OPLippingThickness'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="oPWidth-section" class="@if (isset($Item['OPWidth'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>OP Width</td>
                <td id="oPWidth-selected">@if (isset($Item['OPWidth'])){{ $Item['OPWidth'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="oPWidth-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="oPHeigth-section" class="@if (isset($Item['OPHeigth'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>OP Height</td>
                <td id="oPHeigth-selected">@if (isset($Item['OPHeigth'])){{ $Item['OPHeigth'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="oPHeigth-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="opTransom-section" class="@if (isset($Item['OPTransom'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>OP Transom</td>
                <td id="opTransom-selected">@if (isset($Item['OPTransom'])){{ $Item['OPTransom'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="opTransom-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="transomThickness-section" class="@if (isset($Item['TransomThickness'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Transom Thickness</td>
                <td id="transomThickness-selected">@if (isset($Item['TransomThickness'])){{ $Item['TransomThickness'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="transomThickness-price">
                        @if (isset($Item['TransomThickness']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'transom_thickness' && $Item['TransomThickness'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="opGlassType-section" class="@if (isset($Item['OPGlassType'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>OP Glass Type</td>
                <td id="opGlassType-selected">@if (isset($Item['OPGlassType'])){{ $Item['OPGlassType'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="opGlassType-price">
                        @if (isset($Item['OPGlassType']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'leaf1_glass_type' && $Item['OPGlassType'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="opGlazingBeads-section" class="@if (isset($Item['OPGlazingBeads'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>OP Glazing Beads</td>
                <td id="opGlazingBeads-selected">@if (isset($Item['OPGlazingBeads'])){{ $Item['OPGlazingBeads'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="opGlazingBeads-price">
                        @if (isset($Item['OPGlazingBeads']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'leaf1_glazing_beads' && $Item['OPGlazingBeads'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="opGlazingBeadSpecies-section" class="@if (isset($Item['OPGlazingBeadSpecies'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>OP Glazing Bead Species</td>
                <td id="opGlazingBeadSpecies-selected">@if (isset($Item['OPGlazingBeadSpecies'])){{ $Item['OPGlazingBeadSpecies'] }}@endif</td>
                {{--  <td id="opGlazingBeadSpecies-price">£0.00</td>  --}}
            </tr>


        </table>
    </div>
</div>


<div class="door_main_data">
    <div class="door_option_list">
        <h6><b>Side Light</b></h6>
        <table class="table">
            <tr id="sideLight1-section" class="@if (isset($Item['SideLight1'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td class="td-align">Side Light 1</td>
                <td id="sideLight1-selected" class="td-align3">@if (isset($Item['SideLight1'])){{ $Item['SideLight1'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="sideLight1-price">
                        @if (isset($Item['SideLight1']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'SideLight1' && $Item['SideLight1'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="sideLight1GlassType-section" class="@if (isset($Item['SideLight1GlassType'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Side Light 1 Glass Type</td>
                <td id="sideLight1GlassType-selected">@if (isset($Item['SideLight1GlassType'])){{ $Item['SideLight1GlassType'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="sideLight1GlassType-price">
                        @if (isset($Item['SideLight1GlassType']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'leaf1_glass_type' && $Item['SideLight1GlassType'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="SideLight1BeadingType-section" class="@if (isset($Item['BeadingType'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Beading Type</td>
                <td id="SideLight1BeadingType-selected">@if (isset($Item['BeadingType'])){{ $Item['BeadingType'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="SideLight1BeadingType-price">
                        @if (isset($Item['BeadingType']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'leaf1_glazing_beads' && $Item['BeadingType'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="SideLight1GlazingBeadSpecies-section" class="@if (isset($Item['SL1GlazingBeadSpecies'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Glazing Bead Species</td>
                <td id="SideLight1GlazingBeadSpecies-selected">@if (isset($Item['SL1GlazingBeadSpecies'])){{ $Item['SL1GlazingBeadSpecies'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="SideLight1GlazingBeadSpecies-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="SL1Width-section" class="@if (isset($Item['SL1Width'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>SL1 Width</td>
                <td id="SL1Width-selected">@if (isset($Item['SL1Width'])){{ $Item['SL1Width'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="SL1Width-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="SL1Height-section" class="@if (isset($Item['SL1Height'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>SL1 Height</td>
                <td id="SL1Height-selected">@if (isset($Item['SL1Height'])){{ $Item['SL1Height'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="SL1Height-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="SL1Depth-section" class="@if (isset($Item['SL1Depth'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>SL1 Depth</td>
                <td id="SL1Depth-selected">@if (isset($Item['SL1Depth'])){{ $Item['SL1Depth'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="SL1Depth-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="SL1Transom-section" class="@if (isset($Item['SL1Transom'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>SL1 Transom </td>
                <td id="SL1Transom-selected">@if (isset($Item['SL1Transom'])){{ $Item['SL1Transom'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="SL1Transom-price">
                        @if (isset($Item['SL1Transom']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'SideLight1_transom' && $Item['SL1Transom'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="sideLight2-section" class="@if (isset($Item['SideLight2'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Side Light 2</td>
                <td id="sideLight2-selected">@if (isset($Item['SideLight2'])){{ $Item['SideLight2'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="sideLight2-price">
                        @if (isset($Item['SideLight2']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'SideLight2' && $Item['SideLight2'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="copyOfSideLite1-section" class="@if (isset($Item['DoYouWantToCopySameAsSL1'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Do you want to copy Same as SL1?</td>
                <td id="copyOfSideLite1-selected">@if (isset($Item['DoYouWantToCopySameAsSL1'])){{ $Item['DoYouWantToCopySameAsSL1'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="copyOfSideLite1-price">
                        @if (isset($Item['DoYouWantToCopySameAsSL1']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'copy_Same_as_SL1' && $Item['DoYouWantToCopySameAsSL1'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="sideLight2GlassType-section" class="@if (isset($Item['SideLight2GlassType'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Side Light 2 Glass Type</td>
                <td id="sideLight2GlassType-selected">@if (isset($Item['SideLight2GlassType'])){{ $Item['SideLight2GlassType'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="sideLight2GlassType-price">
                        @if (isset($Item['SideLight2GlassType']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'leaf1_glass_type' && $Item['SideLight2GlassType'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="SideLight2BeadingType-section" class="@if (isset($Item['SideLight2BeadingType'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Side Light 2 Beading Type </td>
                <td id="SideLight2BeadingType-selected">@if (isset($Item['SideLight2BeadingType'])){{ $Item['SideLight2BeadingType'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="SideLight2BeadingType-price">
                        @if (isset($Item['SideLight2BeadingType']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'leaf1_glazing_beads' && $Item['SideLight2BeadingType'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="SideLight2GlazingBeadSpecies-section" class="@if (isset($Item['SideLight2GlazingBeadSpecies'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Side Light 2 Glazing Bead Species</td>
                <td id="SideLight2GlazingBeadSpecies-selected">@if (isset($Item['SideLight2GlazingBeadSpecies'])){{ $Item['SideLight2GlazingBeadSpecies'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="SideLight2GlazingBeadSpecies-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="SL2Width-section" class="@if (isset($Item['SL2Width'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>SL2 Width</td>
                <td id="SL2Width-selected">@if (isset($Item['SL2Width'])){{ $Item['SL2Width'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="SL2Width-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="SL2Height-section" class="@if (isset($Item['SL2Height'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>SL2 Height </td>
                <td id="SL2Height-selected">@if (isset($Item['SL2Height'])){{ $Item['SL2Height'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="SL2Height-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="SL2Depth-section" class="@if (isset($Item['SL2Depth'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>SL2 Depth</td>
                <td id="SL2Depth-selected">@if (isset($Item['SL2Depth'])){{ $Item['SL2Depth'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="SL2Depth-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="SL2Transom-section" class="@if (isset($Item['SL2Transom'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>SL2 Transom </td>
                <td id="SL2Transom-selected">@if (isset($Item['SL2Transom'])){{ $Item['SL2Transom'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="SL2Transom-price">
                        @if (isset($Item['SL2Transom']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'SideLight2_transom' && $Item['SL2Transom'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>


        </table>
    </div>
</div>


<div class="door_main_data">
    <div class="door_option_list">
        <h6><b>Lipping And Intumescent</b></h6>
        <table class="table">
            <tr id="lippingType-section" class="@if (isset($Item['LippingType'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td class="td-align">Lipping Type</td>
                <td id="lippingType-selected" class="td-align3">@if (isset($Item['LippingType'])){{ $Item['LippingType'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="lippingType-price">
                        @if (isset($Item['LippingType']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'lipping_type' && $Item['LippingType'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="lippingThickness-section" class="@if (isset($Item['LippingThickness'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Lipping Thickness</td>
                <td id="lippingThickness-selected">@if (isset($Item['LippingThickness'])){{ $Item['LippingThickness'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="lippingThickness-price">
                        @if (isset($Item['LippingThickness']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'lipping_thickness' && $Item['LippingThickness'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif


                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="lippingSpecies-section" class="@if (isset($Item['LippingSpecies'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Lipping Species</td>

                @if (isset($Item['LippingSpecies']))
                    @foreach ($selected_lipping_species as $row)
                        @if ($row->id == $Item['LippingSpecies'])
                            <td id="lippingSpecies-selected">{{$row->SpeciesName}}</td>
                            {{--  @if (price_view_vlidator() == 1)
                                <td id="lippingSpecies-price">

                                    @foreach ($row->lipping_species_items as $lipping_species_items_row)
                                        @foreach ($lipping_species_items_row->selected_lipping_species_items as $selected_lipping_species_items_row)
                                            @if ($Item['LippingThickness'] == $selected_lipping_species_items_row->selected_thickness)
                                                £{{ (isset($selected_lipping_species_items_row->selected_price) && $selected_lipping_species_items_row->selected_price != null)?$selected_lipping_species_items_row->selected_price:$lipping_species_items_row->price}}
                                            @endif
                                        @endforeach
                                    @endforeach
                                </td>
                            @endif  --}}
                            @break

                        @endif
                    @endforeach
                @else
                    <td id="lippingSpecies-selected"></td>
                    {{--  <td id="lippingSpecies-price"> £0.00</td>  --}}
                @endif
            </tr>
            <tr id="meetingStyle-section" class="@if (isset($Item['MeetingStyle'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Meeting Style</td>
                <td id="meetingStyle-selected">@if (isset($Item['MeetingStyle'])){{ $Item['MeetingStyle'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="meetingStyle-price">
                        @if (isset($Item['MeetingStyle']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'meeting_style' && $Item['MeetingStyle'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="scallopedLippingThickness-section" class="@if (isset($Item['ScallopedLippingThickness'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Scalloped Lipping Thickness</td>
                <td id="scallopedLippingThickness-selected">@if (isset($Item['ScallopedLippingThickness'])){{ $Item['ScallopedLippingThickness'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="scallopedLippingThickness-price">
                        @if (isset($Item['ScallopedLippingThickness']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'scalloped_lipping_thickness' && $Item['ScallopedLippingThickness'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="flatLippingThickness-section" class="@if (isset($Item['FlatLippingThickness'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Flat Lipping Thickness</td>
                <td id="flatLippingThickness-selected">@if (isset($Item['FlatLippingThickness'])){{ $Item['FlatLippingThickness'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="flatLippingThickness-price">
                        @if (isset($Item['FlatLippingThickness']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'flat_lipping_thickness' && $Item['FlatLippingThickness'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="rebatedLippingThickness-section" class="@if (isset($Item['RebatedLippingThickness'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Rebated Lipping Thickness</td>
                <td id="rebatedLippingThickness-selected">@if (isset($Item['RebatedLippingThickness'])){{ $Item['RebatedLippingThickness'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="rebatedLippingThickness-price">
                        @if (isset($Item['RebatedLippingThickness']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'rebeated_lipping_thickness' && $Item['RebatedLippingThickness'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="coreWidth1-section" class="@if (isset($Item['CoreWidth1'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Core Width 1 </td>
                <td id="coreWidth1-selected">@if (isset($Item['CoreWidth1'])){{ $Item['CoreWidth1'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="coreWidth1-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="coreWidth2-section" class="@if (isset($Item['CoreWidth2'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Core Width 2</td>
                <td id="coreWidth2-selected">@if (isset($Item['CoreWidth2'])){{ $Item['CoreWidth2'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="coreWidth2-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="coreHeight-section" class="@if (isset($Item['CoreHeight'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Core Height</td>
                <td id="coreHeight-selected">@if (isset($Item['CoreHeight'])){{ $Item['CoreHeight'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="coreHeight-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="intumescentSealType-section" class="@if (isset($Item['IntumescentLeapingSealType'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Intumescent Seal Type</td>
                <td id="intumescentSealType-selected">@if (isset($Item['IntumescentLeapingSealType'])){{ $Item['IntumescentLeapingSealType'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="intumescentSealType-price">
                        @if (isset($Item['IntumescentLeapingSealType']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'intumescent_seal_type' && $Item['IntumescentLeapingSealType'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="intumescentSealLocation-section" class="@if (isset($Item['IntumescentLeapingSealLocation'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Intumescent Seal Location </td>
                <td id="intumescentSealLocation-selected">@if (isset($Item['IntumescentLeapingSealLocation'])){{ $Item['IntumescentLeapingSealLocation'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="intumescentSealLocation-price">
                        @if (isset($Item['IntumescentLeapingSealLocation']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'IntumescentSeal_location' && $Item['IntumescentLeapingSealLocation'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="intumescentSealColor-section" class="@if (isset($Item['IntumescentLeapingSealColor'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Intumescent Seal Color </td>
                <td id="intumescentSealColor-selected">@if (isset($Item['IntumescentLeapingSealColor'])){{ $Item['IntumescentLeapingSealColor'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="intumescentSealColor-price">
                        @if (isset($Item['IntumescentLeapingSealColor']))
                            @foreach ($selected_option_data as $row)
                                @if ($row->OptionSlug == 'Intumescent_Seal_Color' && $Item['IntumescentLeapingSealColor'] == $row->OptionKey)
                                    £{{ ($row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost }}
                                    @break
                                @endif
                            @endforeach
                        @else
                                £0.00
                        @endif
                    </td>
                @endif  --}}
            </tr>
            <tr id="intumescentSealArrangement-section" class="@if (isset($Item['IntumescentLeapingSealArrangement'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Intumescent Seal Arrangement</td>
                <td id="intumescentSealArrangement-selected">

                    @if (isset($Item['IntumescentLeapingSealArrangement']))
                        @foreach ($SelectedIntumescentSealArrangement as $row)
                            @if ($row->intumescentseals2_id == $Item['IntumescentLeapingSealArrangement'])
                                {{ $row->selected_brand."-".$row->selected_intumescentSeals }}
                                @break
                            @endif
                        @endforeach
                    @endif
                </td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="intumescentSealArrangement-price">

                        @if (isset($Item['IntumescentLeapingSealArrangement']))
                            @foreach ($SelectedIntumescentSealArrangement as $row)
                                @if ($row->intumescentseals2_id == $Item['IntumescentLeapingSealArrangement'])
                                    £{{ $row->selected_cost }}
                                    @break
                                @endif
                            @endforeach
                        @else
                                £0.00
                        @endif

                    </td>
                @endif  --}}
            </tr>


        </table>
    </div>
</div>

<div class="door_main_data">
    <div class="door_option_list">
        <h6><b>Accoustics</b></h6>
        <table class="table">
            <tr id="accoustics-section" class="@if (isset($Item['Accoustics'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td class="td-align">Accoustics </td>
                <td id="accoustics-selected" class="td-align3">@if (isset($Item['Accoustics'])){{ $Item['Accoustics'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="accoustics-price">
                        @if (isset($Item['Accoustics']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'Accoustics' && $Item['Accoustics'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                    @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="rWdBRating-section" class="@if (isset($Item['rWdBRating'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>rW dB Rating</td>
                <td id="rWdBRating-selected">@if (isset($Item['rWdBRating'])){{ $Item['rWdBRating'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="rWdBRating-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="perimeterSeal1-section" class="@if (isset($Item['perimeterSeal1'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Perimeter Seal 1</td>
                <td id="perimeterSeal1-selected">@if (isset($Item['perimeterSeal1'])){{ $Item['perimeterSeal1'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="perimeterSeal1-price">

                        @if (isset($Item['perimeterSeal1']))
                            @foreach ($selected_option_data as $row)
                                @if ($row->OptionKey == $Item['perimeterSeal1'] && $row->UnderAttribute == 'Perimeter_Seal_1' && $row->OptionSlug == 'Accoustics')
                                    £{{ ($row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                    @break
                                @endif
                            @endforeach
                        @else
                                £0.00
                        @endif

                    </td>
                @endif  --}}
            </tr>
            <tr id="perimeterSeal2-section" class="@if (isset($Item['perimeterSeal2'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Perimeter Seal 2</td>
                <td id="perimeterSeal2-selected">@if (isset($Item['perimeterSeal2'])){{ $Item['perimeterSeal2'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="perimeterSeal2-price">
                        @if (isset($Item['perimeterSeal1']))
                            @foreach ($selected_option_data as $row)
                                @if ($row->OptionKey == $Item['perimeterSeal2'] && $row->UnderAttribute == 'Perimeter_Seal_2' && $row->OptionSlug == 'Accoustics')
                                    £{{ ($row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                    @break
                                @endif
                            @endforeach
                        @else
                                £0.00
                        @endif
                    </td>
                @endif  --}}
            </tr>
            <tr id="thresholdSeal1-section" class="@if (isset($Item['thresholdSeal1'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Threshold Seal 1</td>
                <td id="thresholdSeal1-selected">@if (isset($Item['thresholdSeal1'])){{ $Item['thresholdSeal1'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="thresholdSeal1-price">
                        @if (isset($Item['thresholdSeal1']))
                            @foreach ($selected_option_data as $row)
                                @if ($row->OptionKey == $Item['thresholdSeal1'] && $row->UnderAttribute == 'Threshold_Seal_1' && $row->OptionSlug == 'Accoustics')
                                    £{{ ($row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                    @break
                                @endif
                            @endforeach
                        @else
                                £0.00
                        @endif
                    </td>
                @endif  --}}
            </tr>
            <tr id="thresholdSeal2-section" class="@if (isset($Item['thresholdSeal2'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Threshold Seal 2</td>
                <td id="thresholdSeal2-selected">@if (isset($Item['thresholdSeal2'])){{ $Item['thresholdSeal2'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="thresholdSeal2-price">
                        @if (isset($Item['thresholdSeal2']))
                            @foreach ($selected_option_data as $row)
                                @if ($row->OptionKey == $Item['thresholdSeal2'] && $row->UnderAttribute == 'Threshold_Seal_2' && $row->OptionSlug == 'Accoustics')
                                    £{{ ($row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                    @break
                                @endif
                            @endforeach
                        @else
                                £0.00
                        @endif
                    </td>
                @endif  --}}
            </tr>
            <tr id="accousticsmeetingStiles-section" class="@if (isset($Item['AccousticsMeetingStiles'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Meeting Stiles</td>
                <td id="accousticsmeetingStiles-selected">@if (isset($Item['AccousticsMeetingStiles'])){{ $Item['AccousticsMeetingStiles'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="accousticsmeetingStiles-price">
                        @if (isset($Item['AccousticsMeetingStiles']))
                            @foreach ($selected_option_data as $row)
                                @if ($row->OptionKey == $Item['AccousticsMeetingStiles'] && $row->UnderAttribute == 'Meeting_Stiles' && $row->OptionSlug == 'Accoustics')
                                    £{{ ($row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                    @break
                                @endif
                            @endforeach
                        @else
                                £0.00
                        @endif
                    </td>
                @endif  --}}
            </tr>
            <tr id="accousticsglassType-section" class="@if (isset($Item['AccousticsGlassType'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Accoustics Glass Type</td>
                <td id="accousticsglassType-selected">@if (isset($Item['AccousticsGlassType'])){{ $Item['AccousticsGlassType'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="accousticsglassType-price">£0.00</td>
                @endif  --}}
            </tr>


        </table>
    </div>
</div>

<div class="door_main_data">
    <div class="door_option_list">
        <h6><b>Architrave</b></h6>
        <table class="table">
            <tr id="Architrave-section" class="@if (isset($Item['Architrave'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td class="td-align">Architrave </td>
                <td id="Architrave-selected" class="td-align3">@if (isset($Item['Architrave'])){{ $Item['Architrave'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="Architrave-price">
                        @if (isset($Item['Architrave']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'Architrave' && $Item['Architrave'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="architraveMaterial-section" class="@if (isset($Item['ArchitraveMaterial'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Architrave Material</td>
                <td id="architraveMaterial-selected">@if (isset($Item['ArchitraveMaterial'])){{ $Item['ArchitraveMaterial'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="architraveMaterial-price">
                        @if (isset($Item['ArchitraveMaterial']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'Architrave_Material' && $Item['ArchitraveMaterial'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="architraveType-section" class="@if (isset($Item['ArchitraveType'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Architrave Type</td>
                <td id="architraveType-selected">@if (isset($Item['ArchitraveType'])){{ $Item['ArchitraveType'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="architraveType-price">
                        @if (isset($Item['ArchitraveType']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'Architrave_Type' && $Item['ArchitraveType'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="architraveWidth-section" class="@if (isset($Item['ArchitraveWidth'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Architrave Width</td>
                <td id="architraveWidth-selected">@if (isset($Item['ArchitraveWidth'])){{ $Item['ArchitraveWidth'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="architraveWidth-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="architraveDepth-section" class="@if (isset($Item['ArchitraveDepth'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Architrave Depth</td>
                <td id="architraveDepth-selected">@if (isset($Item['ArchitraveDepth'])){{ $Item['ArchitraveDepth'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="architraveDepth-price">£0.00</td>
                @endif  --}}
            </tr>
            <tr id="architraveFinish-section" class="@if (isset($Item['ArchitraveFinish'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Architrave Finish</td>
                <td id="architraveFinish-selected">@if (isset($Item['ArchitraveFinish'])){{ $Item['ArchitraveFinish'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="architraveFinish-price">
                        @if (isset($Item['ArchitraveFinish']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'Architrave_Finish' && $Item['ArchitraveFinish'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>
            <tr id="architraveSetQty-section" class="@if (isset($Item['ArchitraveSetQty'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Architrave Set Qty</td>
                <td id="architraveSetQty-selected">@if (isset($Item['ArchitraveSetQty'])){{ $Item['ArchitraveSetQty'] }}@endif</td>
                {{--  @if (price_view_vlidator() == 1)
                    <td id="architraveSetQty-price">
                        @if (isset($Item['ArchitraveSetQty']))
                            @foreach ($option_data as $row)
                                @if ($row->OptionSlug == 'Architrave_Set_Qty' && $Item['ArchitraveSetQty'] == $row->OptionKey)
                                    £{{ (isset($row->SelectedOptionCost) && $row->SelectedOptionCost != null)?$row->SelectedOptionCost:$row->OptionCost}}
                                @break
                            @endif
                        @endforeach
                    @else
                        £0.00
                @endif
                </td>
                @endif  --}}
            </tr>

        </table>
    </div>
</div>
