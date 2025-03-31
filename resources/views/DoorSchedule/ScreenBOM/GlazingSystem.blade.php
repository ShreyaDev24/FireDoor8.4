@php $i = 0; $check = 0; $total=0; $GTSellPrice=0;@endphp
    @foreach($data as $value)
        @if($value->Category=='GlazingSystem')
        @php $check =  1; @endphp
        @if($i++==0)
        <tr class="bg-white">
            <td colspan="16"></td>
        </tr>
        <tr style="background:#00B0F0">
            <td colspan="16"><b>Screen Glazing System</b></td>
        </tr>
        <tr>
            <th colspan="2">Screen Type</th>
            <th  colspan="2">Glazing System</th>
            <th colspan="4">Glazing System Size</th>
            <th>LM Per Screen Type </th>
            <th>Quantity of Screen Types </th>
            <th>Unit</th>
            <th>Unit Cost PLM</th>
            <th>Total Cost</th>
            <th>Unit Price Sell</th>
            <th>GT Sell Price</th>
            <th>{{ $value->MarginMarkup }}</th>
        </tr>
        @endif
        @php
                $total = $total + $value->TotalCost;
                $GTSellPrice = $GTSellPrice + $value->GTSellPrice;
                $words = explode("|", $value->Description);
        @endphp
        <tr>
            <td colspan="2">{{ isset($words[0]) ? $words[0] : '' }}</td>
            <td colspan="2">{{ isset($words[1]) ? $words[1] : ''  }}</td>
            <td colspan="4">{{ isset($words[2]) ? $words[2] : '' }}</td>
            <td>{{$value->LMPerDoorType}}</td>
            <td>{{$value->QuantityOfDoorTypes}}</td>
            <td>{{$value->Unit}}</td>
            <td>{{ $currency }}{{$value->UnitCost}}</td>
            <td>{{ $currency }}{{ round($value->TotalCost, 2) }}</td>
            <td>{{ $currency }}{{$value->UnitPriceSell}}</td>
            <td>{{ $currency }}{{$value->GTSellPrice}}</td>
            <td>{{$value->Margin}}%</td>
        </tr>

        @endif
@endforeach

@if($check==1)
<tr style="background:gray">
    <td colspan="8"><b>Total </b></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>{{ $currency }}{{ round($total,2) }}</td>
    <td></td>
    <td>{{ $currency }}{{ $GTSellPrice }}</td>
    <td></td>
</tr>
@endif
