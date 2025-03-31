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
                <th colspan="9">Glass Order Sheet BOM</th>
            </tr>
            <tr>
                <th>Ref</th>
                <td colspan="2">{{ $quotation->QuotationGenerationId }}</td>
                <th>Project</th>
                <td colspan="2">{{ $quotation->projectname }}</td>
                <th colspan="1">Prepared By</th>
                <td colspan="2">{{ $userName }}</td>
            </tr>
            <tr>
                <th>Revision</th>
                <td>{{ $item[0]->VersionId }}</td>
                <th>Date</th>
                <td>{{ $today }}</td>
                <th>Main Contractor</th>
                <td>{{ $quotation->CstCompanyName }}</td>
                <th colspan="1">Sales Contact</th>
                <td colspan="2">{{ $quotation->SalesContact }}</td>
            </tr>
            <tr>
                <th colspan="9">Text</th>
            </tr>
            <tr>
                <th colspan="9">Items</th>
            </tr>
            @php
                $i = 0;
            @endphp

            @foreach ($item as $value)
                @if ($i++ == 0)
                    <tr>
                        <th>DOOR NUMBER</th>
                        <th>GLASS THICKNESS IN MM</th>
                        <th>GLASS TYPE</th>
                        <th>CUT Height BOTTOM PANEL</th>
                        <th>CUT WIDTH BOTTOM PANEL</th>
                        <th>QTY OF GLASS PANELS TO ORDER</th>
                        <th>CUT SIZES Height TOP PANEL</th>
                        <th>CUT SIZES WIDTH TOP PANEL</th>
                        <th>QTY OF GLASS PANELS TO ORDER</th>
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
                    </tr>
                @endif
                <tr>
                    <td>{{ $value->doorNumber }}</td>
                    <td>{{ $value->GlassThickness }}</td>
                    <td>{{ str_replace('_', ' ', $value->GlassType) }}</td>
                    <td>{{ ($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? $value->Leaf1VPHeight1 - 8 : $value->Leaf1VPHeight1 - 5 }}</td>
                    @php
                        if($value->FireRating == 'NFR' || $value->FireRating == 'FD30s' || $value->FireRating == 'FD30'){
                            $wdth = 5;
                        }elseif($value->FireRating == 'FD60s' || $value->FireRating == 'FD60'){
                            $wdth = 8;
                        }else{
                            $wdth = 0;
                        }
                    @endphp
                    <td>{{ $value->Leaf1VPWidth - $wdth }}</td>
                    <td>{{ $value->VisionPanelQuantity }}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
