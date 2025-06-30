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
            width: 100%
        }

        table tbody tr td {
            padding: 5px 10px;
            border: 1px solid black;
            border-collapse: collapse;
        }

        table tbody tr th {
            border: 1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>

<body>
    <table>
        <tbody>
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
            @php
                $i = 0;
            @endphp

            @foreach ($item as $value)
                @if ($i++ == 0)
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
                        <td><b></b></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endif
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
                    $stopleg2 = $leg - floatval($FrameType) - 0;

                    $Height = 0;
                    $Width = 0;
                    if($value->DoorFrameConstruction == 'Half_Lapped_Joint'){
                        $Height = $halflapedjoint->Height ?? 0;
                        $Width = $halflapedjoint->Width ?? 0;
                    }else if($value->DoorFrameConstruction == 'Mitre_Joint'){
                        $Height = $mitre_joint->Height ?? 0;
                        $Width = $mitre_joint->Width ?? 0;
                    }else if($value->DoorFrameConstruction == 'Mortice_&_Tenon_Joint'){
                        $Height = $mortice_tenon_joint->Height ?? 0;
                        $Width = $mortice_tenon_joint->Width ?? 0;
                    }else if($value->DoorFrameConstruction == 'Butt_Joint'){
                        $Height = $butt_joint->Height ?? 0;
                        $Width = $butt_joint->Width ?? 0;
                    }

                    $leg = $value->FrameHeight - $value->FrameThickness + $Height;
                    $head = $value->FrameWidth - $Width;
                    $stopleg2 = $value->FrameHeight - $value->FrameThickness;
                    $stophead = $value->FrameWidth - $value->FrameThickness - $value->FrameThickness;
                    if($value->FrameType == 'Plant_on_Stop'){
                        if($value->DoorFrameConstruction == 'Half_Lapped_Joint'){
                            if(!empty($allSettings['PlantOn.HalfLipped'])){
                                $stophead += $allSettings['PlantOn.HalfLipped']->Width;
                                $stopleg2 += $allSettings['PlantOn.HalfLipped']->Height;
                            }
                        }else if($value->DoorFrameConstruction == 'Mitre_Joint'){
                            if(!empty($allSettings['PlantOn.Mitre'])){
                                $stophead += $allSettings['PlantOn.Mitre']->Width;
                                $stopleg2 += $allSettings['PlantOn.Mitre']->Height;
                            }
                        }else if($value->DoorFrameConstruction == 'Mortice_&_Tenon_Joint'){
                            if(!empty($allSettings['PlantOn.Mortice1'])){
                                $stophead += $allSettings['PlantOn.Mortice1']->Width;
                                $stopleg2 += $allSettings['PlantOn.Mortice1']->Height;
                            }
                        }else if($value->DoorFrameConstruction == 'Butt_Joint'){
                            if(!empty($allSettings['PlantOn.Butt'])){
                                $stophead += $allSettings['PlantOn.Butt']->Width;
                                $stopleg2 += $allSettings['PlantOn.Butt']->Height;
                            }
                        }
                    }
                @endphp
                <tr>
                    <td>{{ $value->doorNumber }}</td>
                    <td>{{ $value->plot_ref_no }}</td>
                    <td>{{ $value->certification_no }}</td>
                    <td>{{ $value->DoorType }}</td>
                    <td>{{ $value->FireRating }}</td>
                    <td>{{ $value->LeafThickness }}</td>
                    <td> <p style="width: 100px;">{{ $value->LeafWidth1 }} × {{ $value->LeafHeight }}</p></td>
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
