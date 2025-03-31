@php
    $intumscenttotal = 0;
    $intumscentGTSell = 0;
    $ironmongerytotal = 0;

    $GlazingBeadstotal = 0;
    $Framestotal = 0;
    $Accousticstotal = 0;
    $Architravetotal = 0;
    $Glassstotal = 0;
    $GlazingSystemstotal = 0;
    $LeafSetBesPokestotal = 0;
    $MachiningCostsstotal = 0;
    $GeneralLabourCostsstotal = 0;
    $GlazingBeadstotalGTSell = 0;
    $FramestotalGTSell = 0;
    $AccousticstotalGTSell = 0;
    $ArchitravetotalGTSell = 0;
    $GlassstotalGTSell = 0;
    $GlazingSystemstotalGTSell = 0;
    $LeafSetBesPokestotalGTSell = 0;
    $MachiningCostsstotalGTSell = 0;
    $GeneralLabourCostsstotalGTSell = 0;
@endphp

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
        td {
            text-align: center;
            vertical-align: middle;
          }

          thead { display: table-header-group }
          tfoot { display: table-row-group }
          tr { page-break-inside: avoid }
    </style>
</head>
<body>
    <table>
    <tbody>
        <tr>
            <th>Ref</th>
            <td colspan="3">{{ $quotation->QuotationGenerationId }}</td>
            <th>Project</th>
            <td colspan="4">{{ $quotation->projectname }}</td>
            <th colspan="3">Prepared By</th>
            <td colspan="4">{{ $userName }}</td>
        </tr>
        <tr>
            <th>Revision</th>
            <td>{{ $data[0]->VersionId }}</td>
            <th>Date</th>
            <td>{{ $today }}</td>
            <th>Main Contractor</th>
            <td colspan="4">{{ $quotation->CstCompanyName }}</td>
            <th colspan="3">Sales Contact</th>
            <td colspan="4">{{ $quotation->SalesContact }}</td>
        </tr>
        <!-- <tr><th colspan="16">Text</th></tr>
        <tr><th colspan="16">Items</th></tr> -->

        @include('DoorSchedule.BOM.GlazingBeads')
        @include('DoorSchedule.BOM.Frame')
        @include('DoorSchedule.BOM.Accoustic')
        @include('DoorSchedule.BOM.Architrave')
        @include('DoorSchedule.BOM.Glass')
        @include('DoorSchedule.BOM.GlazingSystem')
        @include('DoorSchedule.BOM.IntumescentSeal')
        @include('DoorSchedule.BOM.LeafSetBespoke')
        @include('DoorSchedule.BOM.IronmomngeryMaterial')
        {{-- @include('DoorSchedule.BOM.Ironmongery&MachiningCosts') --}}
        @include('DoorSchedule.BOM.MachiningCosts')
        @include('DoorSchedule.BOM.GeneralLabourCosts')

    </table>

    <br>
    @include('DoorSchedule.BOM.Footer')
</body>
</html>
