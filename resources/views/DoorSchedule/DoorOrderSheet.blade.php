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
                <th colspan="15">Door Order Sheet BOM</th>
            </tr>
            <tr>
                <th>Ref</th>
                <td colspan="5">{{ $quotation->QuotationGenerationId }}</td>
                <th>Project</th>
                <td colspan="3">{{ $quotation->projectname }}</td>
                <th colspan="2">Prepared By</th>
                <td colspan="3">{{ $userName }}</td>
            </tr>
            <tr>
                <th>Revision</th>
                <td>{{ $item[0]->VersionId }}</td>
                <th>Date</th>
                <td>{{ $today }}</td>
                <th>Main Contractor</th>
                <td colspan="3">{{ $quotation->CstCompanyName }}</td>
                <th colspan="3">Sales Contact</th>
                <td colspan="4">{{ $quotation->SalesContact }}</td>
            </tr>
            <tr>
                <th colspan="15">Text</th>
            </tr>
            <tr>
                <th colspan="15">Items</th>
            </tr>
            @php
                $i = 0;
            @endphp

            @foreach ($item as $value)
                @if ($i++ == 0)
                    <tr>
                        <th>TOTAL DOORS</th>
                        <th>DOOR NUMBER</th>
                        <th>DOOR TYPE</th>
                        <th>DOOR THICKNESS</th>
                        <th>DOOR MAT</th>
                        <th>DOOR FINISH</th>
                        <th>CUT SIZE H</th>
                        <th>CUT SIZE W</th>
                        <th>CUT SIZE W2</th>
                        <th>LIPPING THICKNESS</th>
                        <th>Lipping Finish W</th>
                        <th>Lipping Finish H</th>
                        <th>LIPPING MAT</th>
                        <th>EXPOSED OR CONCEALED</th>
                        <th>NOTES</th>
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
                    </tr>
                @endif
                @php
                    // $cutSizeW = ($value->FrameWidth - $value->FrameThickness - $value->FrameThickness - 3 - 3) - 15;
                    if($quotation->configurableitems == '1' || $quotation->configurableitems == '2' || $quotation->configurableitems == '7' || $quotation->configurableitems == '8'){
                        $cutSizeH = ($value->LeafHeight  - $value->LippingThickness - $value->LippingThickness);
                        $cutSizeW = ($value->LeafWidth1 - $value->LippingThickness - $value->LippingThickness);
                        $cutSizeW2 = isset($value->LeafWidth2) && $value->LeafWidth2 != null && $value->LeafWidth2 != '' ? ($value->LeafWidth2 - $value->LippingThickness - $value->LippingThickness): '';

                        $LFH = ($value->LeafHeight  - $value->LippingThickness + $value->LippingThickness);
                        $LFW = ($value->LeafWidth1 - $value->LippingThickness + $value->LippingThickness);
                    }else{

                        $AdjustmentLeafWidth1 = $value->AdjustmentLeafWidth1 ?? 0;
                        $AdjustmentLeafWidth2 = $value->AdjustmentLeafWidth2 ?? 0;
                        $AdjustmentLeafHeightNoOP = $value->AdjustmentLeafHeightNoOP ?? 0;

                        if($AdjustmentLeafWidth1 == 0){
                            $cutSizeW = $value->LeafWidth1;
                            $LFW = $cutSizeW;
                        }else{
                            $cutSizeW = (($value->LeafWidth1 + $AdjustmentLeafWidth1) - $AdjustmentLeafWidth1 - $value->LippingThickness);
                            $LFW = $cutSizeW + $value->LippingThickness;
                        }

                        if($AdjustmentLeafWidth2 == 0){
                            $cutSizeW2 = isset($value->LeafWidth2) && $value->LeafWidth2 != null && $value->LeafWidth2 != '' ? ($value->LeafWidth2): '';
                        }else{
                            $cutSizeW2 = isset($value->LeafWidth2) && $value->LeafWidth2 != null && $value->LeafWidth2 != '' ? (($value->LeafWidth2 + $AdjustmentLeafWidth2) - $AdjustmentLeafWidth2 - $value->LippingThickness): '';
                        }

                        if($AdjustmentLeafHeightNoOP == 0){
                            $cutSizeH = $value->LeafHeight;
                            $LFH = $cutSizeH;
                        }else{
                            $cutSizeH = (($value->LeafHeight + $AdjustmentLeafHeightNoOP)  - $AdjustmentLeafHeightNoOP - $value->LippingThickness);
                            $LFH = $cutSizeH + $value->LippingThickness;
                        }
                    }
                    if($cutSizeW2 <= 0){
                        $cutSizeW2 = '';
                    }
                    if(isset($quotation->configurableitems) && $quotation->configurableitems == '1'){
                        $configurableitems = 'Streboard';
                    }elseif(isset($quotation->configurableitems) && $quotation->configurableitems == '2'){
                        $configurableitems = 'Halspan';
                    }elseif(isset($quotation->configurableitems) && $quotation->configurableitems == '3'){
                        $configurableitems = 'Norma';
                    }elseif(isset($quotation->configurableitems) && $quotation->configurableitems == '4'){
                        $configurableitems = 'Vicaima';
                    }elseif(isset($quotation->configurableitems) && $quotation->configurableitems == '5'){
                        $configurableitems = 'Seadec';
                    }elseif(isset($quotation->configurableitems) && $quotation->configurableitems == '6'){
                        $configurableitems = 'Deanta';
                    }
                    elseif(isset($quotation->configurableitems) && $quotation->configurableitems == '7'){
                        $configurableitems = 'Flamebreak';
                    }
                    elseif(isset($quotation->configurableitems) && $quotation->configurableitems == '8'){
                        $configurableitems = 'StreDoor';
                    }
                @endphp
                <tr>
                    <td>{{ ($value->DoorQuantity)?$value->DoorQuantity:1 }}</td>
                    <td>{{ $value->doorNumber }}</td>
                    <td>{{ $value->DoorType }}</td>
                    <td>{{ $value->LeafThickness }}</td>
                    <td>{{ $configurableitems }}</td>
                    <td>{{ str_replace('_', ' ', $value->DoorLeafFacing) }}</td>
                    {{-- <td>{{ (($value->FrameHeight) - 3 - 32 - 6) - 15 }}</td> --}}
                    <td>{{ $cutSizeH }}</td>
                    <td>{{ $cutSizeW }}</td>
                    <td>{{ $cutSizeW2 }}</td>
                    <td>{{ $value->LippingThickness }}</td>
                    <td>{{ $LFW }}</td>
                    <td>{{ $LFH }}</td>
                    <td>{{ $value->SpeciesName }}</td>
                    <td>{{ str_replace('_', ' ', $value->LippingType) }}</td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
