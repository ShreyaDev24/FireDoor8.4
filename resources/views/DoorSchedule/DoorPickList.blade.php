<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Of Material</title>
    <style>
        @page {
            size: 1260pt 660pt;
            margin: 20px;
        }

        table {
            border: 1px solid black;
            border-collapse: collapse;
            width: 100%;
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        th, td {
            border: 1px solid black;
            padding: 5px 8px;
            text-align: center;
            vertical-align: middle;
        }

        thead th {
            font-weight: bold;
            background: #f1f1f1;
        }

        thead {
            display: table-header-group;
        }

        .title-row th {
            background: #ddd;
            font-size: 14px;
        }

        .blue-row td {
            background: #00B0F0;
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr class="title-row">
                <th colspan="20">Door Pick List</th>
            </tr>
            <tr>
                <th>Ref</th>
                <td colspan="6">{{ $quotation->QuotationGenerationId }}</td>
                <th>Project</th>
                <td colspan="4">{{ $quotation->projectname }}</td>
                <th colspan="4">Prepared By</th>
                <td colspan="4">{{ $userName }}</td>
            </tr>
            <tr>
                <th colspan="3">Revision</th>
                <td>{{ $item[0]->VersionId }}</td>
                <th colspan="2">Date</th>
                <td colspan="2">{{ $today }}</td>
                <th colspan="2">Main Contractor</th>
                <td colspan="3">{{ $quotation->CstCompanyName }}</td>
                <th colspan="3">Sales Contact</th>
                <td colspan="4">{{ $quotation->SalesContact }}</td>
            </tr>
            <tr>
                <th colspan="20">Items</th>
            </tr>
            <tr>
                <th>Total Doors</th>
                <th>DoorSet Type</th>
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
            <tr class="blue-row">
                <td colspan="20"></td>
            </tr>
        </thead>
        <tbody>
            {!! implode('', $data) !!}
        </tbody>
    </table>
</body>
</html>
