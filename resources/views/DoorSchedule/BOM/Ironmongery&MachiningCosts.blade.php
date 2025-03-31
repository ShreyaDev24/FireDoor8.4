@php $i = 0; $check = 0; $total=0; $GTSellPrice=0;@endphp
    @foreach($data as $value)
        @if($value->Category=='Ironmongery&MachiningCosts')
        @php $check =  1; @endphp
        @if($i++==0)
        <tr>
            <th colspan="6">Description</th>
            <th colspan="2">Total Quantity </th>
            <th>Unit</th>
            <th>Unit Cost</th>
            <th colspan="2">Total Cost</th>
            <th colspan="2">Unit Price Sell</th>
            <th>GT Sell Price</th>
            {{--  @if($quotation->configurableitems == 4 || $quotation->configurableitems == 5 || $quotation->configurableitems == 6)
            <th>Margin</th>
            @else
            <th>Markup</th>
            @endif  --}}
            <th>{{ $value->MarginMarkup }}</th>
        </tr>
        <tr style="background:#00B0F0">
            <td colspan="3"><b>Ironmongery Items </b></td>
            <td>#######</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>Text</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @endif
        @php
                $total = $total + $value->TotalCost;
                $GTSellPrice = $GTSellPrice + $value->GTSellPrice;
        @endphp
        <tr>
            <td colspan="6">{{ $value->Description }}</td>
            <td>{{$value->LMPerDoorType}}</td>
            <td>{{$value->QuantityOfDoorTypes}}</td>
            <td>{{$value->Unit}}</td>
            <td>{{ $currency }}{{$value->UnitCost}}</td>
            <td colspan="2">{{ $currency }}{{ round($value->TotalCost,2) }}</td>
            <td colspan="2">{{ $currency }}{{$value->UnitPriceSell}}</td>
            <td>{{ $currency }}{{$value->GTSellPrice}}</td>
            <td>{{$value->Margin}}%</td>
        </tr>


        @endif


@endforeach

@if($check==1)
<tr style="background:gray">
    <td colspan="3"><b>Total </b></td>
    <td>#######</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td colspan="2">{{ $currency }}{{ round($total,2) }}</td>
    <td></td>
    <td></td>
    <td>{{ $currency }}{{ $GTSellPrice }}</td>
    <td></td>
</tr>
@endif
