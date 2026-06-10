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
    <table>
        <tbody>
            <tr>
                <th colspan="31">Frames & Transoms BOM</th>
            </tr>
            <tr>
                <th colspan="6">Ref</th>
                <td colspan="5">{{ $quotation->QuotationGenerationId }}</td>
                <th colspan="5">Project</th>
                <td colspan="5">{{ $quotation->projectname }}</td>
                <th colspan="5">Prepared By</th>
                <td colspan="5">{{ $userName }}</td>
            </tr>
            <tr>
                <th colspan="5">Revision</th>
                <td colspan="2">{{ $item[0]->VersionId }}</td>
                <th colspan="2">Date</th>
                <td colspan="3">{{ $today }}</td>
                <th colspan="4">Main Contractor</th>
                <td colspan="5">{{ $quotation->CstCompanyName }}</td>
                <th colspan="5">Sales Contact</th>
                <td colspan="5">{{ $quotation->SalesContact }}</td>
            </tr>
            <tr>
                <th colspan="31">Text</th>
            </tr>
            <tr>
                <th colspan="31">Items</th>
            </tr>
            @php
                $i = 0;
            @endphp
            @foreach ($item as $value)
                @if ($i++ == 0)
                    <tr>
                        <th>Door Number</th>
                        <th>Plot Number/Ref</th>
                        <th>IFC/Certifire No/Q mark Plug</th>
                        <th>Door Core</th>
                        <th>Door Type</th>
                        <th>Ironmongery Ref</th>
                        <th>Fire Rating</th>
                        <th>Door Thickness</th>
                        <th>Frame Material</th>
                        <th>O/A Frame H</th>
                        <th>O/A Frame W</th>
                        <th>Frame Thickness</th>
                        <th>Plant on stop thickness</th>
                        <th>Plant on stop Width</th>
                        <th>Rebate Width</th>
                        <th>Rebate Depth</th>
                        <th>Scalloped Width</th>
                        <th>Scalloped Depth</th>
                        <th>Frame Depth</th>
                        <th>Leg x2</th>
                        <th>Head</th>
                        <th>Stop Leg x 2</th>
                        <th>Stop Head</th>
                        <th>Stop Bottom</th>
                        <th>Bottom- 4 Sided Frame</th>
                        <th>Handing</th>
                        <th>Finish</th>
                        <th>Undercut</th>
                        <th>Transom</th>
                        <th>Mullion</th>
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
                        {{-- <td></td> --}}
                    </tr>
                @endif
                @endforeach
                {!! implode('', $data) !!}
        </tbody>
    </table>
</body>

</html>
