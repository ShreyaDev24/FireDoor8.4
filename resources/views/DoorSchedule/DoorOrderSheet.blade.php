<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bill Of Material</title>
    <style>
        @page {
            size: 1260pt 660pt; /* Landscape large size */
            margin: 10pt;
        }

        body {
            font-size: 9px; /* Smaller font */
            font-family: Arial, sans-serif;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed; /* Ensures fixed layout for columns */
        }

        th, td {
            border: 1px solid black;
            padding: 2px 4px; /* Reduce padding */
            word-wrap: break-word;
            font-size: 8px; /* Further reduce font in cells */
        }

        th {
            {{--  background-color: #e0e0e0;  --}}
            text-align: center;
        }

        /* Optional: Fixed column widths (adjust values as needed) */
        td, th {
            width: 80px; /* or you can use percentages like: width: 3.5%; */
        }
    </style>

</head>

<body>
    <table style="max-width: 100vw; width: 100%; border-collapse: collapse;">
        <thead>
            <tr>
                <th colspan="25">Door Order Sheet BOM</th>
            </tr>
            <tr>
                <th>Ref</th>
                <td colspan="7">{{ $quotation->QuotationGenerationId }}</td>
                <th>Project</th>
                <td colspan="5">{{ $quotation->projectname }}</td>
                <th colspan="5">Prepared By</th>
                <td colspan="6">{{ $userName }}</td>
            </tr>
            <tr>
                <th colspan="5">Revision</th>
                <td>{{ $item[0]->VersionId }}</td>
                <th colspan="2">Date</th>
                <td colspan="3">{{ $today }}</td>
                <th colspan="3">Main Contractor</th>
                <td colspan="3">{{ $quotation->CstCompanyName }}</td>
                <th colspan="4">Sales Contact</th>
                <td colspan="4">{{ $quotation->SalesContact }}</td>
            </tr>
            <tr>
                <th colspan="25">Text</th>
            </tr>
            <tr>
                <th colspan="25">Items</th>
            </tr>
            <tr>
                <th>Total Doors</th>
                <th>Plot Number/Ref</th>
                <th>IFC/Certifire No/Q mark Plug</th>
                <th>Door Number</th>
                <th>Door Type</th>
                <th>FireRating</th>
                <th>Door Thickness</th>
                <th>Door Mat</th>
                <th>Door Leaf Facing</th>
                <th>Door Leaf Finish</th>
                <th>Product code leaf 1</th>
                <th>Product code leaf 2</th>
                <th>Ironmongery Ref</th>
                <th>Cut Size H</th>
                <th>Cut Size W</th>
                <th>Cut Size W2</th>
                <th>Lipping Thickness</th>
                <th>Lipping Finish W</th>
                <th>Lipping Finish H</th>
                <th>Lipping Mat</th>
                <th>Exposed or Concealed</th>
                <th>Intumescent Seal Type</th>
                <th>Notes</th>
                <th>Saddle Required</th>
                <th>Saddle Location</th>
            </tr>
            <tr style="background:#00B0F0">
                <td style="padding:5px 0px;"><b></b></td>
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
                <td></td>
                <td></td>
            </tr>
        </thead>

        <tbody>
            {!! implode('', $data) !!}
        </tbody>
    </table>
</body>

</html>
