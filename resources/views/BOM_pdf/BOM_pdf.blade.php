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
        table{
            border:1px solid black;
            border-collapse: collapse;
            width:100%
        }
        table tbody tr td{
            padding:5px 10px;
            border:1px solid black;
            border-collapse: collapse;
        }

        table tbody tr th{
            border:1px solid black;
            border-collapse: collapse;
        }
    </style>
</head>
<body>
    <table>
    <tbody>
        <tr>
            <th>Ref</th>
            <td colspan="3">{{ $quotation->QuotationGenerationId }}</td>
            <th>Project</th>
            <td colspan="5">{{ $quotation->ProjectName }}</td>
            <th colspan="2">Prepared By</th>
            <td colspan="4">{{ $quotation->SalesContact }}</td>
        </tr>
        <tr>
            <th>Revision</th>
            <td>Test</td>
            <th>Date</th>
            <td>10/23/2020</td>
            <th>Customer</th>
            <td colspan="5">{{ $quotation->CstCompanyName }}</td>
            <th colspan="2">Sales Contact</th>
            <td colspan="4">{{ $quotation->SalesContact }}</td>
        </tr>
        <tr><th colspan="16">Text</th></tr>
        <tr><th colspan="16">Items</th></tr>

        @include('DoorSchedule.BOM.GlazingBeads')
        @include('DoorSchedule.BOM.Frame')
        @include('DoorSchedule.BOM.Glass')
        @include('DoorSchedule.BOM.GlazingSystem')
        @include('DoorSchedule.BOM.IntumescentSeal')
        @include('DoorSchedule.BOM.LeafSetBespoke')
        @include('DoorSchedule.BOM.IronmomngeryMaterial')
        @include('DoorSchedule.BOM.Ironmongery&MachiningCosts')
        @include('DoorSchedule.BOM.GeneralLabourCosts')

    </table>

    <br>
    @include('DoorSchedule.BOM.Footer')
</body>
</html>
