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
                <th colspan="22">Glazing Beads for Doors</th>
            </tr>
            <tr>
                <th colspan="4">Ref</th>
                <td colspan="2">{{ $quotation->QuotationGenerationId }}</td>
                <th colspan="5">Project</th>
                <td colspan="3">{{ $quotation->projectname }}</td>
                <th colspan="5">Prepared By</th>
                <td colspan="3">{{ $userName }}</td>
            </tr>
            <tr>
                <th>Revision</th>
                <td>{{ $item[0]->VersionId }}</td>
                <th colspan="3">Date</th>
                <td colspan="3">{{ $today }}</td>
                <th colspan="3">Main Contractor</th>
                <td colspan="3">{{ $quotation->CstCompanyName }}</td>
                <th colspan="4">Sales Contact</th>
                <td colspan="4">{{ $quotation->SalesContact }}</td>
            </tr>
            <tr>
                <th colspan="22">Text</th>
            </tr>
            <tr>
                <th colspan="22">Items</th>
            </tr>
            @php
                $i = 0;
            @endphp

            @foreach ($item as $value)
                @if ($i++ == 0)
                    <tr>
                        <th>Door Core</th>
                        <th>Door Ref</th>
                        <th>Door Type</th>
                        <th>Plot Number/Ref</th>
                        <th>IFC/Certifire No/Q mark Plug</th>
                        <th>Timber</th>
                        <th>Profile</th>
                        <th>Finish on Bead</th>
                        <th>Glazing Bead Height</th>
                        <th>Glazing Bead Depth</th>
                        <th>GB1 W</th>
                        <th>QTY</th>
                        <th>GB1 H</th>
                        <th>QTY</th>
                        <th>GB2 H</th>
                        <th>QTY</th>
                        <th>GB3 H</th>
                        <th>QTY</th>
                        <th>GB4 H</th>
                        <th>QTY</th>
                        <th>GB5 H</th>
                        <th>QTY</th>
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
                    </tr>
                @endif
                @endforeach
                {!! implode('', $data) !!}
        </tbody>
    </table>
</body>

</html>
