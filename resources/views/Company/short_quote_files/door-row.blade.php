@if($doorType == 'vicaima')
<tr>
    <td>{{ $door['plot_ref_no'] }}</td>
    <td>{{ $door['certification_no'] }}</td>
    <td>{{ $door['floor'] }}</td>
    <td>{{ $door['configuration'] }}</td>
    <td>{{ $door['doorNumber'] }}</td>
    <td>{{ $door['DoorDescription'] }}</td>
    <td>{{ $door['SOHeight'] }}</td>
    <td>{{ $door['SOWidth'] }}</td>
    <td>{{ $door['SOWallThick'] }}</td>
    <td>{{ $door['DoorType'] }}</td>
    <td>{{ $door['LeafConstruction'] }}</td>
    <td>{{ $door['DoorLeafFacing'] }}</td>
    <td>{{ $door['DoorDimensionsCode'] }}</td>
    <td>{{ $door['Lipping'] }}</td>
    <td>{{ $door['LeafWidth1'] }}</td>
    <td>{{ $door['LeafWidth2'] }}</td>
    <td>{{ $door['LeafHeight'] }}</td>
    <td>{{ $door['LeafThickness'] }}</td>
    <td>{{ $door['Undercut'] }}</td>
    <td>{{ $door['Handing'] }}</td>
    <td>{{ $door['OpensInwards'] }}</td>
    <td>{{ $door['Leaf1VisionPanel'] }}</td>
    <td>{{ $door['Leaf2VisionPanel'] }}</td>
    <td>{{ $door['GlassType'] }}</td>
    <td>{{ $door['Overpanel'] }}</td>
    <td>{{ $door['OPGlassType'] }}</td>
    <td>{{ $door['SideScreen1'] }}</td>
    <td>{{ $door['SideScreen2'] }}</td>
    <td>{{ $door['FrameMaterial'] }}</td>
    <td>{{ $door['FrameType'] }}</td>
    <td>{{ $door['FrameSize'] }}</td>
    <td>{{ $door['FrameFinish'] }}</td>
    <td>{{ $door['ExtLiner'] }}</td>
    <td>{{ $door['ExtLinerSize'] }}</td>
    <td>{{ $door['intumescentSeal'] }}</td>
    <td>{{ $door['ArchitraveMaterial'] }}</td>
    <td>{{ $door['ArchitraveType'] }}</td>
    <td>{{ $door['ArchitraveSize'] }}</td>
    <td>{{ $door['ArchitraveFinish'] }}</td>
    <td>{{ $door['ArchitraveSetQty'] }}</td>
    <td>{{ $door['IronmongerySet'] }}</td>
    <td>{{ $door['rWdBRating'] }}</td>
    <td>{{ $door['fireRate'] }}</td>
    <td>{{ $door['SpecialFeatureRefs'] }}</td>
    @if($hideCosts == 0)
        <td class="tbl_last">{{ number_format($door['DoorsetPrice'], 2) }}</td>
        <td class="tbl_last">{{ number_format($door['IronmongaryPrice'], 2) }}</td>
    @endif
    <td class="tbl_last">{{ number_format($door['totalPrice'], 2) }}</td>
</tr>
@else
<tr>
    <td>{{ $door['plot_ref_no'] }}</td>
    <td>{{ $door['certification_no'] }}</td>
    <td>{{ $door['floor'] }}</td>
    <td>{{ $door['configuration'] }}</td>
    <td>{{ $door['doorNumber'] }}</td>
    <td>{{ $door['DoorDescription'] }}</td>
    <td>{{ $door['SOHeight'] }}</td>
    <td>{{ $door['SOWidth'] }}</td>
    <td>{{ $door['SOWallThick'] }}</td>
    <td>{{ $door['DoorType'] }}</td>
    <td>{{ $door['DoorLeafFinish'] }}</td>
    <td>{{ $door['DoorLeafFacing'] }}</td>
    <td>{{ $door['Lipping'] }}</td>
    <td>{{ $door['LeafWidth1'] }}</td>
    <td>{{ $door['LeafWidth2'] }}</td>
    <td>{{ $door['LeafHeight'] }}</td>
    <td>{{ $door['LeafThickness'] }}</td>
    <td>{{ $door['Undercut'] }}</td>
    <td>{{ $door['Handing'] }}</td>
    <td>{{ $door['OpensInwards'] }}</td>
    <td>{{ $door['Leaf1VisionPanel'] }}</td>
    <td>{{ $door['Leaf2VisionPanel'] }}</td>
    <td>{{ $door['GlassType'] }}</td>
    <td>{{ $door['Overpanel'] }}</td>
    <td>{{ $door['OPGlassType'] }}</td>
    <td>{{ $door['SideScreen1'] }}</td>
    <td>{{ $door['SideScreen2'] }}</td>
    <td>{{ $door['FrameMaterial'] }}</td>
    <td>{{ $door['FrameType'] }}</td>
    <td>{{ $door['FrameSize'] }}</td>
    <td>{{ $door['FrameFinish'] }}</td>
    <td>{{ $door['ExtLiner'] }}</td>
    <td>{{ $door['ExtLinerSize'] }}</td>
    <td>{{ $door['intumescentSeal'] }}</td>
    <td>{{ $door['ArchitraveMaterial'] }}</td>
    <td>{{ $door['ArchitraveType'] }}</td>
    <td>{{ $door['ArchitraveSize'] }}</td>
    <td>{{ $door['ArchitraveFinish'] }}</td>
    <td>{{ $door['ArchitraveSetQty'] }}</td>
    <td>{{ $door['IronmongerySet'] }}</td>
    <td>{{ $door['rWdBRating'] }}</td>
    <td>{{ $door['fireRate'] }}</td>
    <td>{{ $door['COC'] }}</td>
    <td>{{ $door['SpecialFeatureRefs'] }}</td>
    @if($hideCosts == 0)
        <td class="tbl_last">{{ number_format($door['DoorsetPrice'], 2) }}</td>
        <td class="tbl_last">{{ number_format($door['IronmongaryPrice'], 2) }}</td>
    @endif
    <td class="tbl_last">{{ number_format($door['totalPrice'], 2) }}</td>
</tr>
@endif
