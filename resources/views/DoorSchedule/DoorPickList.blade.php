<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Door Pick List</title>
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
                <th colspan="15">Door Pick List</th>
            </tr>
            <tr>
                <th>Ref</th>
                <td colspan="4">{{ $quotation->QuotationGenerationId }}</td>
                <th>Project</th>
                <td colspan="4">{{ $quotation->projectname }}</td>
                <th>Prepared By</th>
                <td colspan="4">{{ $userName }}</td>
            </tr>
            <tr>
                <th colspan="4">Revision</th>
                <td>{{ $QuotationVersion }}</td>
                <th colspan="2">Date</th>
                <td>{{ $today }}</td>
                <th colspan="2">Main Contractor</th>
                <td>{{ $quotation->CstCompanyName }}</td>
                <th colspan="2">Sales Contact</th>
                <td colspan="2">{{ $quotation->SalesContact }}</td>
            </tr>
            <tr>
                <th colspan="15">Items</th>
            </tr>
            <tr>
                <th>Door Quantity</th>
                <th>Door Thickness</th>
                <th>Door Mat</th>
                <th>Door Finish</th>
                <th>PRODUCT CODE LEAF</th>
                <th>mmm Width x Height </th>
                <th colspan="9">Notes</th>
            </tr>
            <tr class="blue-row">
                <td colspan="15"></td>
            </tr>
        </thead>
        <tbody>
            {!! implode('', $data) !!}
        </tbody>
    </table>
</body>
</html>
