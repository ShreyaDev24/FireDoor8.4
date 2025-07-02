<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Of Material</title>
    <style>
    @page {
        size: A2 landscape;
        margin: 20pt;
    }

   table {
    border: 1px solid black;
    border-collapse: collapse;
    width: 100%;
    table-layout: fixed;
    font-size: 9pt;
}

    thead {
        display: table-header-group; /* 🔑 This ensures repeating headers in PDF */
    }

    tbody {
        display: table-row-group;
    }

    th, td {
        padding: 5px 10px;
        border: 1px solid black;
        border-collapse: collapse;
    }
</style>

</head>

<body>
    <table>
    <thead>
        <tr>
            <th colspan="30">Frames & Transoms BOM</th>
        </tr>
        <tr>
            <th colspan="6">Ref</th>
            <td colspan="5">{{ $quotation->QuotationGenerationId }}</td>
            <th colspan="6">Project</th>
            <td colspan="5">{{ $quotation->projectname }}</td>
            <th colspan="4">Prepared By</th>
            <td colspan="4">{{ $userName }}</td>
        </tr>
        <tr>
            <th colspan="4">Revision</th>
            <td colspan="4">{{ $item[0]->VersionId }}</td>
            <th colspan="4">Date</th>
            <td colspan="3">{{ $today }}</td>
            <th colspan="4">Main Contractor</th>
            <td colspan="3">{{ $quotation->CstCompanyName }}</td>
            <th colspan="4">Sales Contact</th>
            <td colspan="4">{{ $quotation->SalesContact }}</td>
        </tr>
        <tr>
            <th colspan="30">Text</th>
        </tr>
        <tr>
            <th colspan="30">Items</th>
        </tr>
        <tr>
            <th>Door Number</th>
            <th>Plot Number/Ref</th>
            <th>IFC/Certifire No/Q mark Plug</th>
            <th>Door Type</th>
            <th>Fire Rating</th>
            <th>Door Thickness</th>
            <th>Door Size</th>
            <th>Frame Material</th>
            <th>O/A Frame H</th>
            <th>O/A Frame W</th>
            <th>Frame Thickness</th>
            <th>Plant on stop thickness</th>
            <th>Plant on stop Width</th>
            <th>Rebate Width</th>
            <th>Rebate Depth</th>
            <th>Scalloped Width</th>
            <th>Scalloped Depth</th>
            <th>Frame Depth</th>
            <th>Leg x 2</th>
            <th>Head</th>
            <th>Stop Leg x 2</th>
            <th>Stop Head</th>
            <th>Stop Bottom</th>
            <th>Bottom- 4 Sided Frame</th>
            <th>Handing</th>
            <th>Finish</th>
            <th>Undercut</th>
            <th>Transom</th>
            <th>Mullion</th>
            <th>Notes</th>
        </tr>
        <tr style="background:#00B0F0">
            <td colspan="30"></td>
        </tr>
    </thead>
    <tbody>
        @php $i = 0; @endphp
        @foreach ($item as $value)
            @php
                $leg = $value->FrameHeight + $value->Height;
                $head = $value->FrameWidth + $value->Width;
                $stophead = $value->FrameWidth - $value->FrameThickness - $value->FrameThickness;
                $FrameType = '';
                if($value->FrameType == 'Plant_on_Stop'){
                    $FrameType = $value->PlantonStopHeight;
                }elseif($value->FrameType == 'Rebated_Frame'){
                    $FrameType = $value->RebatedHeight;
                }
                $stopleg2 = $leg - floatval($FrameType);
                if($value->DoorFrameConstruction == 'Half_Lapped_Joint'){
                    if ($halflapedjoint->Height > 0) {
                        $leg = $value->FrameHeight - $value->FrameThickness + $halflapedjoint->Height;
                    } else {
                        $leg = $value->FrameHeight - $value->FrameThickness + $halflapedjoint->Height;
                    }
                    $head = $value->FrameWidth - $halflapedjoint->Width;
                    $stopleg2 = $value->FrameHeight - $value->FrameThickness;
                    $stophead = $value->FrameWidth - $value->FrameThickness - $value->FrameThickness;
                }
            @endphp
            <tr>
                <td>{{ $value->doorNumber }}</td>
                <td>{{ $value->plot_ref_no }}</td>
                <td>{{ $value->certification_no }}</td>
                <td>{{ $value->DoorType }}</td>
                <td>{{ $value->FireRating }}</td>
                <td>{{ $value->LeafThickness }}</td>
                <td><p style="width: 100px;">{{ $value->LeafWidth1 }} × {{ $value->LeafHeight }}</p></td>
                <td>{{ $value->SpeciesName }}</td>
                <td>{{ $value->FrameHeight }}</td>
                <td>{{ $value->FrameWidth }}</td>
                <td>{{ $value->FrameThickness }}</td>
                <td>{{ $value->PlantonStopHeight }}</td>
                <td>{{ $value->PlantonStopWidth }}</td>
                <td>{{ $value->RebatedWidth }}</td>
                <td>{{ $value->RebatedHeight }}</td>
                <td>{{ $value->ScallopedWidth }}</td>
                <td>{{ $value->ScallopedHeight }}</td>
                <td>{{ $value->FrameDepth }}</td>
                <td>{{ $leg }}</td>
                <td>{{ $head }}</td>
                <td>{{ $stopleg2 }}</td>
                <td>{{ $stophead }}</td>
                <td></td>
                <td></td>
                @if($value->Handing == 'Left_Hand_Master_Right_Hand_Slave')
                    <td><p style="width: 120px;">Left Hand Master Right Hand Slave</p></td>
                @elseif($value->Handing == 'Right_Hand_Master_Left_Hand_Slave')
                    <td><p style="width: 120px;">Right Hand Master Left Hand Slave</p></td>
                @else
                    <td>{{ $value->Handing }}</td>
                @endif
                <td>{{ str_replace('_', ' ', $value->FrameFinish) }}</td>
                <td>{{ $value->Undercut }}</td>
                <td></td>
                <td></td>
                <td>{{ $value->SpecialFeatureRefs }}</td>
            </tr>
        @endforeach
    </tbody>
</table>

</body>

</html>
