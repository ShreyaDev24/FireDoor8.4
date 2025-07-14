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
                <th colspan="19">Door Order Sheet BOM</th>
            </tr>
            <tr>
                <th>Ref</th>
                <td colspan="5">{{ $quotation->QuotationGenerationId }}</td>
                <th>Project</th>
                <td colspan="4">{{ $quotation->projectname }}</td>
                <th colspan="4">Prepared By</th>
                <td colspan="4">{{ $userName }}</td>
            </tr>
            <tr>
                <th colspan="2">Revision</th>
                <td>{{ $item[0]->VersionId }}</td>
                <th colspan="2">Date</th>
                <td colspan="2">{{ $today }}</td>
                <th  colspan="2">Main Contractor</th>
                <td colspan="3">{{ $quotation->CstCompanyName }}</td>
                <th colspan="3">Sales Contact</th>
                <td colspan="4">{{ $quotation->SalesContact }}</td>
            </tr>
            <tr>
                <th colspan="19">Text</th>
            </tr>
            <tr>
                <th colspan="19">Items</th>
            </tr>
            @php
                $i = 0;
            @endphp

            @foreach ($item as $value)
                @if ($i++ == 0)
                    <tr>
                        <th>Total Doors</th>
                        <th>Plot Number/Ref</th>
                        <th>IFC/Certifire No/Q mark Plug</th>
                        <th>Door Number</th>
                        <th>Door Type</th>
                        <th>Door Thickness</th>
                        <th>Door Mat</th>
                        <th>Door Finish</th>
                        <th>PRODUCT CODE LEAF 1</th>
                        <th>PRODUCT CODE LEAF 2</th>
                        <th>Cut Size H</th>
                        <th>Cut Size W</th>
                        <th>Cut Size W2</th>
                        <th>Lipping Thickness</th>
                        <th>Lipping Finish W</th>
                        <th>Lipping Finish H</th>
                        <th>Lipping Mat</th>
                        <th>Exposed or Concealed</th>
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
                    </tr>
                @endif
                @endforeach
                {!! implode('', $data) !!}
        </tbody>
    </table>
</body>

</html>
