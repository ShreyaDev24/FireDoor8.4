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
                <th colspan="8">Glazing Beads for Doors BOM</th>
            </tr>
            <tr>
                <th>Ref</th>
                <td colspan="2">{{ $quotation->QuotationGenerationId }}</td>
                <td colspan="2">{{ $quotation->QuotationGenerationId }}</td>
                <th>Project</th>
                <td colspan="2">{{ $quotation->projectname }}</td>
                <th>Prepared By</th>
                <td>{{ $userName }}</td>
            </tr>
            <tr>
                <th>Revision</th>
                <td>{{ $item[0]->VersionId }}</td>
                <th>Date</th>
                <td>{{ $today }}</td>
                <th>Main Contractor</th>
                <td>{{ $quotation->CstCompanyName }}</td>
                <th>Sales Contact</th>
                <td>{{ $quotation->SalesContact }}</td>
            </tr>
            <tr>
                <th colspan="8">Text</th>
            </tr>
            <tr>
                <th colspan="8">Items</th>
            </tr>
            @php
                $i = 0;
            @endphp

            @foreach ($item as $value)
                @if ($i++ == 0)
                    <tr>
                        <th>DOOR REF</th>
                        <th>TIMBER</th>
                        <th>SECTION</th>
                        <th>FINISH ON BEAD</th>
                        <th>SAW CUT W</th>
                        <th>QUANT</th>
                        <th>SAW CUT L</th>
                        <th>QUANT</th>
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
                    </tr>
                @endif
                @php
                    $VisionPanelWidthNFR = 0;
                    $VisionPanelHeightNFR = 0;
                    $VisionPanelWidthFD60 = 0;
                    $VisionPanelHeightFD60 = 0;
                    if(!empty($allSettings['VPBead.NRF'])){
                        $VisionPanelWidthNFR = $allSettings['VPBead.NRF']->Width;
                        $VisionPanelHeightNFR = $allSettings['VPBead.NRF']->Height;
                    }
                    if(!empty($allSettings['VPBead.FD60'])){
                        $VisionPanelWidthFD60 = $allSettings['VPBead.FD60']->Width;
                        $VisionPanelHeightFD60 = $allSettings['VPBead.FD60']->Height;
                    }
                @endphp
                @if ($value->GlazingBeads != '' && $value->Leaf1VPHeight1 != '' && $value->Leaf1VPHeight1 != 0  && $value->Leaf1VPWidth != '' && $value->Leaf1VPWidth != 0 )
                <tr>
                    <td>{{ $value->doorNumber }}</td>
                    <td>{{ $value->SpeciesName }}</td>
                    <td>{{ str_replace('_', ' ', $value->GlazingBeads) }}</td>
                    <td>{{ str_replace('_', ' ', $value->DoorLeafFinish) }}</td>
                    <td>{{ ($value->FireRating == 'NFR' || $value->FireRating == 'FD30s' || $value->FireRating == 'FD30') ? ($value->Leaf1VPWidth + $VisionPanelWidthNFR) : ($value->Leaf1VPWidth + $VisionPanelWidthFD60) }}</td>
                    <td>{{ $value->VisionPanelQuantity * 4 }}</td>
                    <td>{{ ($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? $value->Leaf1VPHeight1 + $VisionPanelHeightFD60 : $value->Leaf1VPHeight1 + $VisionPanelHeightNFR}}</td>
                    <td>{{ ($value->VisionPanelQuantity * 2)  + ($value->Leaf2VisionPanelQuantity * 2)}}</td>
                </tr>
                @endif
            @endforeach
        </tbody>
    </table>
</body>

</html>
