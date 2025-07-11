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
                    <th>DOOR NUMBER</th>
                    <th>Plot Number/Ref</th>
                    <th>Door Type</th>
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

            @if ($value->GlassType != '' && $value->GlassThickness != '' || $value->Leaf1VPHeight1 != '' || $value->Leaf1VPHeight1 != 0  && $value->Leaf1VPWidth != '' && $value->Leaf1VPWidth != 0 )
                <tr>
                    <td>{{ $value->doorNumber }}</td>
                    <td>{{ $value->plot_ref_no }}</td>
                    <td>{{ $value->DoorType }}</td>
                    <td>{{ $value->certification_no }}</td>
                    <td>{{ $value->GlassThickness }}</td>
                    <td>{{ str_replace('_', ' ', $value->GlassType) }}</td>
                    @if($value->Leaf1VPWidth && $value->Leaf1VPHeight1)
                        @php
                            if($value->FireRating == 'NFR' || $value->FireRating == 'FD30s' || $value->FireRating == 'FD30'){
                                $wdth = 5;
                            }elseif($value->FireRating == 'FD60s' || $value->FireRating == 'FD60'){
                                $wdth = 10;
                            }else{
                                $wdth = 0;
                            }

                            $vpQty = (int) $value->VisionPanelQuantity ?? 0;
                            $doorType = $value->DoorsetType;
                            $bothLeafsGlazed = $value->bothLeafsGlazed ?? false;

                            switch ($doorType) {
                                case 'SD':
                                    $totalQty = $vpQty;
                                    break;
                                case 'DD':
                                    $totalQty = $vpQty * 2;
                                    break;
                                case 'LH':
                                    $totalQty = $bothLeafsGlazed ? $vpQty * 2 : $vpQty;
                                    break;
                                default:
                                    $totalQty = $vpQty;
                            }

                            $qtyPerVP = ($vpQty > 0) ? $totalQty / $vpQty : 0;
                        @endphp
                        <td>{{ ($value->FireRating == 'FD60s' || $value->FireRating == 'FD60') ? $value->Leaf1VPHeight1 - 10 : $value->Leaf1VPHeight1 - 5 }}</td>
                        <td>{{ $value->Leaf1VPWidth - $wdth }}</td>
                        <td>
                            {{ $value->Leaf1VPHeight1 ? (
                                $value->AreVPsEqualSizesForLeaf1 == 'Yes'
                                    ? rtrim(rtrim(number_format($qtyPerVP, 2), '0'), '.')
                                    : ($value->Leaf1VPQty1 ?? 1)
                            ) : '' }}
                        </td>
                    @else
                        <td></td><td></td><td></td>
                    @endif

                    <td>{{ $value->Leaf1VPHeight2 }}</td>
                    <td>
                        {{ $value->Leaf1VPHeight2 ? (
                            $value->AreVPsEqualSizesForLeaf1 == 'Yes'
                                ? rtrim(rtrim(number_format($qtyPerVP, 2), '0'), '.')
                                : ($value->Leaf1VPQty2 ?? 1)
                        ) : '' }}
                    </td>

                    <td>{{ $value->Leaf1VPHeight3 }}</td>
                    <td>
                        {{ $value->Leaf1VPHeight3 ? (
                            $value->AreVPsEqualSizesForLeaf1 == 'Yes'
                                ? rtrim(rtrim(number_format($qtyPerVP, 2), '0'), '.')
                                : ($value->Leaf1VPQty3 ?? 1)
                        ) : '' }}
                    </td>

                    <td>{{ $value->Leaf1VPHeight4 }}</td>
                    <td>
                        {{ $value->Leaf1VPHeight4 ? (
                            $value->AreVPsEqualSizesForLeaf1 == 'Yes'
                                ? rtrim(rtrim(number_format($qtyPerVP, 2), '0'), '.')
                                : ($value->Leaf1VPQty4 ?? 1)
                        ) : '' }}
                    </td>

                    <td>{{ $value->Leaf1VPHeight5 }}</td>
                    <td>
                        {{ $value->Leaf1VPHeight5 ? (
                            $value->AreVPsEqualSizesForLeaf1 == 'Yes'
                                ? rtrim(rtrim(number_format($qtyPerVP, 2), '0'), '.')
                                : ($value->Leaf1VPQty5 ?? 1)
                        ) : '' }}
                    </td>
                </tr>
            @endif
        @endforeach

        </tbody>
    </table>
</body>

</html>
