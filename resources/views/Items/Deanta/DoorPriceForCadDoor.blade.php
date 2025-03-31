<div class="door_main_data">
    <div class="door_option_list">
        <h6 style = "margin-top: 71px;"><b>Standard Door Size</b></h6>
        <table class="table">
            <tr id="doorsize-section" class="@if (isset($Item['LeafWidth1']) && isset($Item['LeafHeight'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td class="td-align">Door Size</td>
                <td id="doorsize-LeafWidth1" class="td-alignment"></td>
                {{--  <td width="100%" id="doorsize-LeafHeight"></td>  --}}
                @if (price_view_vlidator() == 1)
                <td class="td-align3" id="doorsize-price">£0.00</td>
                @endif
            </tr>
        </table>
    </div>
</div>

<div class="door_main_data">
    <div class="door_option_list">
        <h6><b>Glass Type</b></h6>
        <table class="table">
            <tr id="glassType-section1" class="@if (isset($Item['GlassType']) && isset($Item['VisionPanelQuantity'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td class="td-align">Glass Type</td>
                <td id="glassType-selected1" class="td-alignment"></td>
                @if (price_view_vlidator() == 1)
                    <td id="glassType-price1" class="td-align3">£0.00</td>
                @endif
            </tr>
            <tr id="sidelight1-section1" class="@if (isset($Item['GlassType']) && isset($Item['VisionPanelQuantity']) && isset($Item['SideLight1']) && $Item['SideLight1'] == 'yes'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td class="td-align">Side Light 1</td>
                <td id="sidelight1-selected1" class="td-alignment">@if (isset($Item['SideLight1']) && $Item['SideLight1'] == 'Yes'){{ $Item['SideLight1GlassType'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="sidelight1-price1">£0.00</td>
                @endif
            </tr>
            <tr id="sidelight2-section1" class="@if (isset($Item['GlassType']) && isset($Item['VisionPanelQuantity']) && isset($Item['SideLight2']) && $Item['SideLight2'] == 'yes'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Side Light 2</td>
                <td id="sidelight2-selected1">@if (isset($Item['SideLight2']) && $Item['SideLight2'] == 'Yes'){{ $Item['SideLight2GlassType'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="sidelight2-price1">£0.00</td>
                @endif
            </tr>
        </table>
    </div>
</div>


<div class="door_main_data">
    <div class="door_option_list">
        <h6><b>Glazing Systems</b></h6>
        <table class="table">
            <tr id="glazingSystems-section1" class="@if (isset($Item['GlazingSystems']) && isset($Item['VisionPanelQuantity'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td class="td-align">Glazing Systems</td>
                <td id="glazingSystems-selected1" class="td-alignment"></td>
                @if (price_view_vlidator() == 1)
                    <td id="glazingSystems-price1" class="td-align3">£0.00</td>
                @endif
            </tr>
        </table>
    </div>
</div>

<div class="door_main_data">
    <div class="door_option_list">
        <h6><b>Glazing Beads</b></h6>
        <table class="table">
            <tr id="glazingBead-section1" class="@if (isset($Item['GlazingBeads']) && isset($Item['VisionPanelQuantity'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td class="td-align">Glazing Beads</td>
                <td id="glazingBead-selected1" class="td-alignment"></td>
                @if (price_view_vlidator() == 1)
                    <td id="glazingBead-price1" class="td-align3">£0.00</td>
                @endif
            </tr>
            <tr id="overpanel2-section1" class="@if (isset($Item['Overpanel']) && $Item['Overpanel'] == 'Fan_Light'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Overpanel</td>
                <td id="overpanel2-selected1"></td>
                @if (price_view_vlidator() == 1)
                    <td id="overpanel2-price1">£0.00</td>
                @endif
            </tr>
            <tr id="sideLight2-section1" class="@if (isset($Item['SideLight1']) && $Item['SideLight1'] == 'Yes'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Side Light 1</td>
                <td id="sideLight2-selected1">@if (isset($Item['SideLight1']) && $Item['SideLight1'] == 'Yes'){{ $Item['BeadingType'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="sideLight2-price1">£0.00</td>
                @endif
            </tr>
            <tr id="sideLight12-section1" class="@if (isset($Item['SideLight2']) && $Item['SideLight2'] == 'Yes'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td>Side Light 2</td>
                <td id="sideLight12-selected1">@if (isset($Item['SideLight2']) && $Item['SideLight2'] == 'Yes'){{ $Item['SideLight2BeadingType'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="sideLight12-price1">£0.00</td>
                @endif
            </tr>
        </table>
    </div>
</div>

<div class="door_main_data">
    <div class="door_option_list">
        <h6><b>Frame Type</b></h6>
        <table class="table">
            <tr id="Plant_on_Stop1-section1" class="@if (isset($Item['FrameType']) && $Item['FrameType'] == 'Plant_on_Stop'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif Plant_on_Stop_section">
                <td class="td-align">Plant On stop</td>
                <td class="td-alignment">Head</td>
                @if (price_view_vlidator() == 1)
                    <td id="Plant_on_Stop1-price1" class="td-align3">£0.00</td>
                @endif
            </tr>
            <tr id="Plant_on_Stop2-section1" class="@if (isset($Item['FrameType']) && $Item['FrameType'] == 'Plant_on_Stop'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif Plant_on_Stop_section">
                <td>Plant On stop</td>
                <td>Plant On Stop Head</td>
                @if (price_view_vlidator() == 1)
                    <td id="Plant_on_Stop2-price1">£0.00</td>
                @endif
            </tr>
            <tr id="Plant_on_Stop3-section1" class="@if (isset($Item['FrameType']) && $Item['FrameType'] == 'Plant_on_Stop'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif Plant_on_Stop_section">
                <td>Plant On stop</td>
                <td>Sides</td>
                @if (price_view_vlidator() == 1)
                    <td id="Plant_on_Stop3-price1">£0.00</td>
                @endif
            </tr>
            <tr id="Plant_on_Stop4-section1" class="@if (isset($Item['FrameType']) && $Item['FrameType'] == 'Plant_on_Stop'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif Plant_on_Stop_section">
                <td>Plant On stop</td>
                <td>Plant On Stop Sides</td>
                @if (price_view_vlidator() == 1)
                    <td id="Plant_on_Stop4-price1">£0.00</td>
                @endif
            </tr>
            <tr id="Rebated_Frame1-section1" class="@if (isset($Item['FrameType']) && $Item['FrameType'] == 'Rebated_Frame'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif Rebated_Frame_section">
                <td>Rebated Frame</td>
                <td>Rebated Frame Head</td>
                @if (price_view_vlidator() == 1)
                    <td id="Rebated_Frame1-price1">£0.00</td>
                @endif
            </tr>
            <tr id="Rebated_Frame2-section1" class="@if (isset($Item['FrameType']) && $Item['FrameType'] == 'Rebated_Frame'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif Rebated_Frame_section">
                <td>Rebated Frame</td>
                <td>Rebated Frame Sides</td>
                @if (price_view_vlidator() == 1)
                    <td id="Rebated_Frame2-price1">£0.00</td>
                @endif
            </tr>
            <tr id="extLiner1-section1" class="@if (isset($Item['ExtLiner']) && $Item['ExtLiner'] == 'Yes'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif extLiner_section">
                <td>Ext-Liner</td>
                <td>Fanlight Top/Bottom</td>
                @if (price_view_vlidator() == 1)
                    <td id="extLiner1-price1">£0.00</td>
                @endif
            </tr>
            <tr id="extLiner2-section1" class="@if (isset($Item['ExtLiner']) && $Item['ExtLiner'] == 'Yes'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif extLiner_section">
                <td>Ext-Liner</td>
                <td>Sidelights sides</td>
                @if (price_view_vlidator() == 1)
                    <td id="extLiner2-price1">£0.00</td>
                @endif
            </tr>
            <tr id="sideLight3-section1" class="@if (isset($Item['SideLight1']) && $Item['SideLight1'] == 'Yes'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif sideLight3_section">
                <td>Side Light1</td>
                <td>Sidelight Top/Bottom</td>
                @if (price_view_vlidator() == 1)
                    <td id="sideLight31-price1">£0.00</td>
                @endif
            </tr>
            <tr id="sideLight3-section1" class="@if (isset($Item['SideLight1']) && $Item['SideLight1'] == 'Yes'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif sideLight3_section">
                <td>Side Light1</td>
                <td>Sidelight Sides</td>
                @if (price_view_vlidator() == 1)
                    <td id="sideLight32-price1">£0.00</td>
                @endif
            </tr>
            <tr id="overpanel3-section1" class="@if (isset($Item['Overpanel']) && $Item['Overpanel'] == 'Fan_Light'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif overpanel3_section">
                <td>Over Panel</td>
                <td>Fanlight side1</td>
                @if (price_view_vlidator() == 1)
                    <td id="overpanel3-price1">£0.00</td>
                @endif
            </tr>
        </table>
    </div>
</div>

<div class="door_main_data">
    <div class="door_option_list">
        <h6><b>Leaf Set Bespoke</b></h6>
        <table class="table">
            <tr id="LeafSet-section1" class=" @if (isset($Item['DoorLeafFacing']) && isset($Item['DoorLeafFinish']) && isset($Item['LippingSpecies'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td class="td-align">Leaf Set Bespoke</td>
                <td id="LeafSet-DoorLeafFacing"></td>
                <td id="LeafSet-selected1"></td>
                @if (price_view_vlidator() == 1)
                    <td id="LeafSet-price1" class="td-align3">£0.00</td>
                @endif
            </tr>
        </table>
    </div>
</div>

<div class="door_main_data">
    <div class="door_option_list">
        <h6><b>Intumescent Strip / Seals</b></h6>
        <table class="table">
            <tr id="intumescentSealArrangement-section1" class="@if (isset($Item['IntumescentLeapingSealType']) && isset($Item['IntumescentLeapingSealLocation']) && isset($Item['IntumescentLeapingSealColor']) && isset($Item['IntumescentLeapingSealArrangement'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td class="td-align">Intumescent</td>
                <td id="intumescentSealArrangement-selected1" class="td-alignment"></td>
                @if (price_view_vlidator() == 1)
                    <td id="intumescentSealArrangement-price1" class="td-align3">£0.00</td>
                @endif
            </tr>
        </table>
    </div>
</div>


<div class="door_main_data">
    <div class="door_option_list">
        <h6><b>Ironmongery Items</b></h6>
        <table class="table">
            <tr id="ironmongerySet-section2" class="@if (isset($Item['IronmongerySet'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td class="td-align">Ironmongery Set</td>
                <td id="IronmongeryID-selected" class="IronmongeryID-selected td-alignment">@if (isset($Item['IronmongeryID'])){{ $Item['IronmongeryID'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="ironmongerySet1-price" class="td-align3">£0.00</td>
                @endif
            </tr>
        </table>
    </div>
</div>

<div class="door_main_data">
    <div class="door_option_list">
        <h6><b>Ironmongery & Machining Cost</b></h6>
        <table class="table">
            <tr id="ironmongerySet-section1" class="@if (isset($Item['IronmongerySet'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td class="td-align">Ironmongery Set</td>
                <td id="IronmongeryID-selected" class="IronmongeryID-selected td-alignment">@if (isset($Item['IronmongeryID'])){{ $Item['IronmongeryID'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="ironmongerySet-price" class="td-align3">£0.00</td>
                @endif
            </tr>
        </table>
    </div>
</div>

<div class="door_main_data">
    <div class="door_option_list">
        <h6><b>General Labour Costs - Total Cost:</b></h6>
        <table class="table">
            <tr id="labour-section">
                <td id="labour-description0" class="td-align"></td>
                <td>---</td>
                <td>---</td>
                @if (price_view_vlidator() == 1)
                    <td id="labour-price0" class="td-align3">£0.00</td>
                @endif
            </tr>

            <tr id="labour-section">
                <td id="labour-description1"></td>
                <td>---</td>
                <td>---</td>
                @if (price_view_vlidator() == 1)
                    <td id="labour-price1">£0.00</td>
                @endif
            </tr>

            <tr id="labour-section">
                <td id="labour-description2"></td>
                <td>---</td>
                <td>---</td>
                @if (price_view_vlidator() == 1)
                    <td id="labour-price2">£0.00</td>
                @endif
            </tr>

            <tr id="labour-section">
                <td id="labour-description3"></td>
                <td>---</td>
                <td>---</td>
                @if (price_view_vlidator() == 1)
                    <td id="labour-price3">£0.00</td>
                @endif
            </tr>

            <tr id="labour-section">
                <td id="labour-description4"></td>
                <td>---</td>
                <td>---</td>
                @if (price_view_vlidator() == 1)
                    <td id="labour-price4">£0.00</td>
                @endif
            </tr>

            <tr id="labour-section">
                <td id="labour-description5"></td>
                <td>---</td>
                <td>---</td>
                @if (price_view_vlidator() == 1)
                    <td id="labour-price5">£0.00</td>
                @endif
            </tr>

            <tr id="labour-section">
                <td id="labour-description6"></td>
                <td>---</td>
                <td>---</td>
                @if (price_view_vlidator() == 1)
                    <td id="labour-price6">£0.00</td>
                @endif
            </tr>

            <tr id="labour-section">
                <td id="labour-description7"></td>
                <td>---</td>
                <td>---</td>
                @if (price_view_vlidator() == 1)
                    <td id="labour-price7">£0.00</td>
                @endif
            </tr>

            <tr id="doorsetType-section1" class="@if (isset($Item['DoorsetType'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td id="doorsetType-description"></td>
                <td>Doorset Type</td>
                <td class="doorsetType-selected">@if (isset($Item['DoorsetType'])){{ $Item['DoorsetType'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="doorsetType-price">£0.00</td>
                @endif
            </tr>

            <tr id="doorsetType-section2" class="@if (isset($Item['DoorsetType'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td id="doorsetType-description1"></td>
                <td>Doorset Type</td>
                <td class="doorsetType-selected">@if (isset($Item['DoorsetType'])){{ $Item['DoorsetType'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="doorsetType-price1">£0.00</td>
                @endif
            </tr>

            <tr id="doorLeafFacing-section1" class="@if (isset($Item['DoorLeafFacing'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td id="doorLeafFacing-description1"></td>
                <td>Door Leaf Facing</td>
                <td id="doorLeafFacing-selected1">@if (isset($Item['DoorLeafFacing'])){{ $Item['DoorLeafFacing'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="doorLeafFacing-price1">£0.00</td>
                @endif
            </tr>

            <tr id="doorLeafFinish-section1" class="@if (isset($Item['DoorLeafFinish'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td id="doorLeafFinish-description1"></td>
                <td>Door Leaf Finish</td>
                <td id="doorLeafFinish-selected1">@if (isset($Item['DoorLeafFinish'])){{ $Item['DoorLeafFinish'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="doorLeafFinish-price1">£0.00</td>
                @endif
            </tr>

            <tr id="decorativeGroves-section1" class="@if (isset($Item['DecorativeGroves']) && $Item['DecorativeGroves'] == 'Yes'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td id="decorativeGroves-description1"></td>
                <td>Decorative Groves</td>
                <td id="decorativeGroves-selected1">@if (isset($Item['DecorativeGroves']) && $Item['DecorativeGroves'] == 'Yes'){{ $Item['DecorativeGroves'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="decorativeGroves-price1">£0.00</td>
                @endif
            </tr>

            <tr id="leaf1VisionPanel-section1" class="@if (isset($Item['Leaf1VisionPanel']) && $Item['Leaf1VisionPanel'] == 'Yes'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td id="leaf1VisionPanel-description1"></td>
                <td>Leaf 1 Vision Panel</td>
                <td id="leaf1VisionPanel-selected1">@if (isset($Item['Leaf1VisionPanel'])){{ $Item['Leaf1VisionPanel'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="leaf1VisionPanel-price1">£0.00</td>
                @endif
            </tr>

            <tr id="doorLeafFinish1-section1" class="@if (isset($Item['DoorLeafFinish'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td id="doorLeafFinish1-description1"></td>
                <td>Door Leaf Finish</td>
                <td id="doorLeafFinish1-selected1">@if (isset($Item['DoorLeafFinish'])){{ $Item['DoorLeafFinish'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="doorLeafFinish1-price1">£0.00</td>
                @endif
            </tr>

            <tr id="leaf1VisionPanel1-section1" class="@if (isset($Item['Leaf1VisionPanel']) && $Item['Leaf1VisionPanel'] == 'Yes'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td id="leaf1VisionPanel1-description1"></td>
                <td>Leaf 1 Vision Panel</td>
                <td id="leaf1VisionPanel1-selected1">@if (isset($Item['Leaf1VisionPanel'])){{ $Item['Leaf1VisionPanel'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="leaf1VisionPanel1-price1">£0.00</td>
                @endif
            </tr>

            <tr id="fireRating-section1" class="@if (isset($Item['Leaf1VisionPanel']) && $Item['Leaf1VisionPanel'] == 'Yes' && isset($Item['FireRating'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td id="fireRating-description1"></td>
                <td>VisionPanel & Fire Rating</td>
                <td id="fireRating-selected1">@if (isset($Item['Leaf1VisionPanel']) && isset($Item['FireRating'])){{ $Item['FireRating'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="fireRating-price1">£0.00</td>
                @endif
            </tr>

            <tr id="fireRating1-section1" class="@if (isset($Item['Leaf1VisionPanel']) && $Item['Leaf1VisionPanel'] == 'Yes' && isset($Item['FireRating'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td id="fireRating1-description1"></td>
                <td>VisionPanel & Fire Rating</td>
                <td id="fireRating1-selected1">@if (isset($Item['Leaf1VisionPanel']) && isset($Item['FireRating'])){{ $Item['FireRating'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="fireRating1-price1">£0.00</td>
                @endif
            </tr>

            <tr id="frameFinish-section1" class="@if (isset($Item['FrameFinish'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td id="frameFinish-description1"></td>
                <td>Frame Finish</td>
                <td id="frameFinish-selected1">@if (isset($Item['FrameFinish'])){{ $Item['FrameFinish'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="frameFinish-price1">£0.00</td>
                @endif
            </tr>

            <tr id="extLiner-section1" class="@if (isset($Item['ExtLiner']) && $Item['ExtLiner'] == 'Yes'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td id="extLiner-description1"></td>
                <td>Ex-Liner</td>
                <td id="extLiner-selected1">@if (isset($Item['ExtLiner']) && $Item['ExtLiner'] == 'Yes'){{ $Item['ExtLiner'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="extLiner-price1">£0.00</td>
                @endif
            </tr>

            <tr id="extLinerFramefinish-section1" class="@if (isset($Item['ExtLiner']) && $Item['ExtLiner'] == 'Yes' && isset($Item['FrameFinish'])){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td id="extLinerFramefinish-description1"></td>
                <td>ExLiner & FrameFinish</td>
                <td id="extLinerFramefinish-selected1">@if (isset($Item['FrameFinish']) && $Item['FrameFinish'] == 'Yes' && isset($Item['FrameFinish'])){{ $Item['FrameFinish'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="extLinerFramefinish-price1">£0.00</td>
                @endif
            </tr>

            <tr id="overpanel-section1" class="@if (isset($Item['Overpanel']) && $Item['Overpanel'] == 'Fan_Light'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td id="overpanel-description1"></td>
                <td>Over Panel</td>
                <td id="overpanel-selected1">@if (isset($Item['Overpanel']) && $Item['Overpanel'] == 'Fan_Light'){{ $Item['ExtLiner'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="overpanel-price1">£0.00</td>
                @endif
            </tr>

            <tr id="overpanel1-section1" class="@if (isset($Item['Overpanel']) && $Item['Overpanel'] == 'Fan_Light'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td id="overpanel1-description1"></td>
                <td>Over Panel</td>
                <td id="overpanel1-selected1">@if (isset($Item['Overpanel']) && $Item['Overpanel'] == 'Fan_Light'){{ $Item['ExtLiner'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="overpanel1-price1">£0.00</td>
                @endif
            </tr>

            <tr id="sideLight1-section1" class="@if (isset($Item['SideLight1']) && $Item['SideLight1'] == 'Yes'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td id="sideLight1-description1"></td>
                <td>Side Light1</td>
                <td id="sideLight1-selected1">@if (isset($Item['SideLight1']) && $Item['SideLight1'] == 'Yes'){{ $Item['ExtLiner'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="sideLight1-price1">£0.00</td>
                @endif
            </tr>

            <tr id="sideLight11-section1" class="@if (isset($Item['SideLight1']) && $Item['SideLight1'] == 'Yes'){{ 'table_row_show' }}@else{{ 'table_row_hide' }}@endif">
                <td id="sideLight11-description1"></td>
                <td>Side Light1</td>
                <td id="sideLight11-selected1">@if (isset($Item['SideLight1']) && $Item['SideLight1'] == 'Yes'){{ $Item['ExtLiner'] }}@endif</td>
                @if (price_view_vlidator() == 1)
                    <td id="sideLight11-price1">£0.00</td>
                @endif
            </tr>
        </table>
    </div>
</div>
