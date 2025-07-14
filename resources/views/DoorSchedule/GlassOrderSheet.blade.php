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
                <th colspan="17">Glass Order Sheet BOM</th>
            </tr>
            <tr>
                <th colspan="1">Ref</th>
                <td colspan="4">{{ $quotation->QuotationGenerationId }}</td>
                <th colspan="2">Project</th>
                <td colspan="4">{{ $quotation->projectname }}</td>
                <th colspan="1">Prepared By</th>
                <td colspan="5">{{ $userName }}</td>
            </tr>
            <tr>
                <th colspan="2">Revision</th>
                <td colspan="3">{{ $item[0]->VersionId }}</td>
                <th colspan="1">Date</th>
                <td colspan="3">{{ $today }}</td>
                <th colspan="2">Main Contractor</th>
                <td colspan="3">{{ $quotation->CstCompanyName }}</td>
                <th colspan="1">Sales Contact</th>
                <td colspan="2">{{ $quotation->SalesContact }}</td>
            </tr>
            <tr>
                <th colspan="17">Text</th>
            </tr>
            <tr>
                <th colspan="17">Items</th>
            </tr>
            @php
                $i = 0;
            @endphp

           @foreach ($item as $value)
            @if ($i++ == 0)
                <tr>
                    <th>Door Type</th>
                    <th>DOOR NUMBER</th>
                    <th>Plot Number/Ref</th>
                    <th>IFC/Certifire No/Q mark Plug</th>
                    <th>GLASS THICKNESS IN MM</th>
                    <th>GLASS TYPE</th>
                    <th>VP1 H</th>
                    <th>VP1 W</th>
                    <th>QTY</th>
                    <th>VP2 H</th>
                    <th>QTY</th>
                    <th>VP3 H</th>
                    <th>QTY</th>
                    <th>VP4 H</th>
                    <th>QTY</th>
                    <th>VP5 H</th>
                    <th>QTY</th>
                </tr>
                <tr style="background:#00B0F0">
                    <td><b></b></td>
                    <td></td><td></td><td></td><td></td><td></td>
                    <td></td><td></td><td></td><td></td><td></td>
                    <td></td><td></td><td></td><td></td><td></td>
                    <td></td>
                </tr>
            @endif

            {!! implode('', $data) !!}
        @endforeach

        </tbody>
    </table>
</body>

</html>
