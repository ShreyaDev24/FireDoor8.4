<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Of Material</title>
    <style>
        @page {
            size: 1260pt 660pt;
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
                <th colspan="23">Frames & Transoms BOM</th>
            </tr>
            <tr>
                <th colspan="3">Ref</th>
                <td colspan="4">{{ $quotation->QuotationGenerationId }}</td>
                <th colspan="3">Project</th>
                <td colspan="4">{{ $quotation->projectname }}</td>
                <th colspan="5">Prepared By</th>
                <td colspan="4">{{ $userName }}</td>
            </tr>
            <tr>
                <th colspan="2">Revision</th>
                <td colspan="1">{{ $item[0]->VersionId }}</td>
                <th colspan="2">Date</th>
                <td colspan="2">{{ $today }}</td>
                <th colspan="3">Main Contractor</th>
                <td colspan="4">{{ $quotation->CstCompanyName }}</td>
                <th colspan="5">Sales Contact</th>
                <td colspan="4">{{ $quotation->SalesContact }}</td>
            </tr>
            <tr>
                <th colspan="23">Text</th>
            </tr>
            <tr>
                <th colspan="23">Items</th>
            </tr>
            @php
                $i = 0;
            @endphp

            @foreach ($item as $value)
                @if ($i++ == 0)
                    <tr>
                        <th>Door Number</th>
                        <th>Fire Rating</th>
                        <th>Door Thickness</th>
                        <th>Frame Material</th>
                        <th>O/A Frame H</th>
                        <th>O/A Frame W</th>
                        <th>Frame Thickness</th>
                        <th>Plant on Stop</th>
                        <th>Frame Depth</th>
                        <!-- <th>Frame & Ranson Trench</th> -->
                        <th>Thresh Thickness</th>
                        <th>Thresh Material</th>
                        <th>Leg</th>
                        <th>Head</th>
                        <th>Stop Leg x 2</th>
                        <th>Stop Head</th>
                        <th>Stop Bottom</th>
                        <th>Handing</th>
                        <th>Finish</th>
                        <th>Lock Type 1</th>
                        <th>Lock Type 2</th>
                        <th>Exitex Aliuminum Cills</th>
                        <th>Undercut</th>
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
                        {{-- <td></td> --}}
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
                    <td>{{ $value->FireRating }}</td>
                    <td>{{ $value->LeafThickness }}</td>
                    <td>{{ $value->SpeciesName }}</td>
                    <td>{{ $value->FrameHeight }}</td>
                    <td>{{ $value->FrameWidth }}</td>
                    <td>{{ $value->FrameThickness }}</td>
                    <td>{{ $FrameType }}</td>
                    <td>{{ $value->FrameDepth }}</td>
                    <!-- <td></td> -->
                    <td></td>
                    <td></td>
                    <td>{{ $leg }}</td>
                    <td>{{ $head }}</td>
                    <td>{{ $stopleg2 }}</td>
                    <td>{{ $stophead }}</td>
                    <td></td>
                    <td>{{ $value->Handing }}</td>
                    <td>{{ str_replace('_', ' ', $value->FrameFinish) }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{ $value->Undercut }}</td>
                    <td>{{ $value->SpecialFeatureRefs }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
