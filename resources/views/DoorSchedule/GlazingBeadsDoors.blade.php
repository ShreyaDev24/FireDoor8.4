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
                <th colspan="12">Glazing Beads for Doors BOM</th>
            </tr>
            <tr>
                <th>Ref</th>
                <td colspan="3">{{ $quotation->QuotationGenerationId }}</td>
                <th>Project</th>
                <td colspan="3">{{ $quotation->projectname }}</td>
                <th colspan="2">Prepared By</th>
                <td colspan="2">{{ $userName }}</td>
            </tr>
            <tr>
                <th>Revision</th>
                <td>{{ $item[0]->VersionId }}</td>
                <th>Date</th>
                <td>{{ $today }}</td>
                <th>Main Contractor</th>
                <td colspan="3">{{ $quotation->CstCompanyName }}</td>
                <th colspan="2">Sales Contact</th>
                <td colspan="2">{{ $quotation->SalesContact }}</td>
            </tr>
            <tr>
                <th colspan="12">Text</th>
            </tr>
            <tr>
                <th colspan="12">Items</th>
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
                        <th>SAW CUT L</th>
                        <th>QUANT</th>
                        <th>SAW CUT W</th>
                        <th>QUANT</th>
                        <th>SAW CUT L</th>
                        <th>QUANT</th>
                        <th>SAW CUT W</th>
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
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                @endif
                <tr>
                    <td>{{ $value->doorNumber }}</td>
                    <td>{{ $value->SpeciesName }}</td>
                    <td>{{ str_replace('_', ' ', $value->GlazingBeads) }}</td>
                    <td>{{ str_replace('_', ' ', $value->DoorLeafFinish) }}</td>
                    <td>{{ $value->FrameHeight }}</td>
                    <td>{{ $value->FrameWidth }}</td>
                    <td>{{ $value->Leaf1VPHeight1 - 1 }}</td>
                    <td>{{ $value->VisionPanelQuantity * 4 }}</td>
                    <td>{{ $value->Leaf1VPWidth - 1}}</td>
                    <td>{{ $value->VisionPanelQuantity * 4 }}</td>
                    <td></td>
                    <td></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>
