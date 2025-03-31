@php $i = 0; $check = 0; $total=0; $GTSellPrice=0;@endphp
@foreach($data as $value)
@if($value->Category=='LeafSetBesPoke')
@php $check = 1; @endphp
@if($i++==0)
<tr class="bg-white">
            <td colspan="16"></td>
        </tr>
<tr style="background:#00B0F0">
    <td colspan="16"><b>Door Details</b></td>
</tr>
<tr>
    <th>Door Type</th>
    <th>Door Core</th>
    <th>Lipping Type</th>
    @if($quotation->configurableitems == 4 || $quotation->configurableitems == 5)
    <th colspan="2">Lipping Thickness/Lipping Species</th>
    <th colspan="2">Door Leaf Size</th>
    <th colspan="2">Door Dimensions Code</th>
    @else
    <th colspan="2">Lipping Thickness</th>
    <th colspan="2" >Lipping Species</th>
    <th colspan="2">Door Leaf Size</th>
    @endif

    <th>Total Quantity </th>
    <th>Unit</th>
    <th>Unit Cost</th>
    <th>Total Cost</th>
    <th>Unit Price Sell</th>
    <th>GT Sell Price</th>
    {{--  @if($quotation->configurableitems == 4 || $quotation->configurableitems == 5 || $quotation->configurableitems == 6)
    <th>Margin</th>
    @else
    <th>Markup</th>
    @endif  --}}
    <th>{{ $value->MarginMarkup }}</th>
</tr>
@endif
@php
$total = $total + $value->TotalCost;
$GTSellPrice = $GTSellPrice + $value->GTSellPrice;
$words = explode("|", $value->Description);
@endphp
<tr>
    <td>{{$value->DoorType}}</td>
    <td> {{ isset($words[1]) ? $words[1] : '' }} </td>
        <td> {{ isset($words[2]) ? $words[2] : '' }} </td>
        <td colspan="2"> {{ isset($words[3]) ? $words[3] : '' }} </td>
        <td colspan="2"> {{ isset($words[4]) ? $words[4] : '' }} </td>
        <td colspan="2"> {{ isset($words[5]) ? $words[5] : '' }} </td>
    <td>{{ $value->QuantityOfDoorTypes }}</td>
    <td>{{$value->Unit}}</td>
    <td>{{ $currency }}{{$value->UnitCost}}</td>
    <td>{{ $currency }}{{ round($value->UnitCost * $value->QuantityOfDoorTypes,2) }}</td>
    <td>{{ $currency }}{{$value->UnitPriceSell}}</td>
    <td>{{ $currency }}{{$value->GTSellPrice}}</td>
    <td>{{$value->Margin}}%</td>
</tr>

@endif
@endforeach
@php
global $LeafSetBesPokestotal;
global $LeafSetBesPokestotalGTSell;
    $LeafSetBesPokestotal=round($total,2);
    $LeafSetBesPokestotalGTSell=round($GTSellPrice,2);
    @endphp

@if($check==1)
<tr style="background:gray">
    <td colspan="9"><b>Total </b></td>
    <td></td>
    <td></td>
    <td></td>
    <td>{{ $currency }}{{ round($total,2) }}</td>
    <td></td>
    <td>{{ $currency }}{{ $GTSellPrice }}</td>
    <td></td>
</tr>
@endif
