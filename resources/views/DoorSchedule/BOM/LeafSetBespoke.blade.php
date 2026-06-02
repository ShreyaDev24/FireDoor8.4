@php
    $i = 0;
    $j = 0;
    $check = 0;
    $total = 0;
    $GTSellPrice = 0;
@endphp
@foreach ($data as $value)
    @if ($value->Category == 'LeafSetBesPoke')
        @if (
            $value->configurableitems == 4 ||
                $value->configurableitems == 5 ||
                $value->configurableitems == 6 ||
                $value->configurableitems == 9)
            @php $check++; @endphp
            @if ($i++ == 0)
                @if($check == 1)
                <tr class="bg-white">
                    <td colspan="17"></td>
                </tr>
                <tr style="background:#00B0F0">
                    <td colspan="17"><b>Door Details</b></td>
                </tr>
                @endif
                <tr>
                    <th>Door Type</th>
                    <th>Door Core</th>
                    <th>Lipping Type</th>
                    <th colspan="3">Lipping Thickness/Lipping Species</th>
                    <th colspan="2">Door Leaf Size</th>
                    <th colspan="2">Door Dimensions Code</th>
                    <th>Total Quantity </th>
                    <th>Unit</th>
                    <th>Unit Cost</th>
                    <th>Total Cost</th>
                    <th>Unit Price Sell</th>
                    <th>GT Sell Price</th>
                    <th>{{ $value->MarginMarkup }}</th>
                </tr>
            @endif
        @endif
        @if (
            $value->configurableitems == 1 ||
                $value->configurableitems == 2 ||
                $value->configurableitems == 7 ||
                $value->configurableitems == 8)
            @php $check++; @endphp

            @if ($j++ == 0)
                @if($check == 1)
                <tr class="bg-white">
                    <td colspan="17"></td>
                </tr>
                <tr style="background:#00B0F0">
                    <td colspan="17"><b>Door Details</b></td>
                </tr>
                @endif
                <tr>
                    <th>Door Type</th>
                    <th>Door Core</th>
                    <th>Lipping Type</th>
                    <th colspan="3">Lipping Thickness</th>
                    <th colspan="2">Lipping Species</th>
                    <th colspan="2">Door Leaf Size</th>
                    <th>Total Quantity </th>
                    <th>Unit</th>
                    <th>Unit Cost</th>
                    <th>Total Cost</th>
                    <th>Unit Price Sell</th>
                    <th>GT Sell Price</th>
                    <th>{{ $value->MarginMarkup }}</th>
                </tr>
            @endif
        @endif
        @php
            $total = $total + $value->TotalCost;
            $GTSellPrice = $GTSellPrice + $value->GTSellPrice;
            $words = explode('|', $value->Description);
        @endphp
        <tr>
            <td>{{ $value->DoorType }}</td>
            <td> {{ isset($words[1]) ? $words[1] : '' }} </td>
            <td> {{ isset($words[2]) ? $words[2] : '' }} </td>
            <td colspan="3"> {{ isset($words[3]) ? $words[3] : '' }} </td>
            <td colspan="2"> {{ isset($words[4]) ? $words[4] : '' }} </td>
            <td colspan="2"> {{ isset($words[5]) ? $words[5] : '' }} </td>
            <td>{{ $value->QuantityOfDoorTypes }}</td>
            <td>{{ $value->Unit }}</td>
            <td>{{ $currency }}{{ $value->UnitCost }}</td>
            <td>{{ $currency }}{{ round($value->UnitCost * $value->QuantityOfDoorTypes, 2) }}</td>
            <td>{{ $currency }}{{ $value->UnitPriceSell }}</td>
            <td>{{ $currency }}{{ $value->GTSellPrice }}</td>
            <td>{{ $value->Margin }}%</td>
        </tr>
    @endif
@endforeach
@php
    global $LeafSetBesPokestotal;
    global $LeafSetBesPokestotalGTSell;
    $LeafSetBesPokestotal = round($total, 2);
    $LeafSetBesPokestotalGTSell = round($GTSellPrice, 2);
@endphp

@if ($check != 0)
    <tr style="background:gray">
        <td colspan="10"><b>Total </b></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{ $currency }}{{ round($total, 2) }}</td>
        <td></td>
        <td>{{ $currency }}{{ $GTSellPrice }}</td>
        <td></td>
    </tr>
@endif
