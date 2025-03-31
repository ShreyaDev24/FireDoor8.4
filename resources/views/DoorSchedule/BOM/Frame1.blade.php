@php $i = 0; $check = 0; $total=0; $GTSellPrice=0;@endphp
    @foreach($data as $value)
        @if($value->Category=='Frame')
        @php $check =  1; @endphp
        @if($i++==0)
        <tr style="margin-top:10px">
            <th colspan="6">Description</th>
            <th>LM Per  Door Type </th>
            <th>Quantity of door types </th>
            <th>Unit</th>
            <th>Unit Cost</th>
            <th colspan="2">Total Cost</th>
            <th colspan="2">Unit Price Sell</th>
            <th>GT Sell Price</th>
            @if($quotation->configurableitems == 4)
            <th>Margin</th>
            @else
            <th>Markup</th>
            @endif
        </tr>
        <tr style="background:#00B0F0; margin-top:20px">
            <td colspan="3"><b>Frame - Total Cost: </b></td>
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
            if($quotation->configurableitems == 4){
                $total = $total + round(($value->LMPerDoorType * $value->UnitCost) , 2);
                $GTSellPrice = $GTSellPrice + $value->GTSellPrice;
            }else{
                $total = $total + $value->TotalCost;
                $GTSellPrice = $GTSellPrice + $value->GTSellPrice;
            }
        @endphp
        <tr>
            <td colspan="6">{{ $value->Description }}</td>
            <td>{{$value->LMPerDoorType}}</td>
            <td>{{$value->QuantityOfDoorTypes}}</td>
            <td>{{$value->Unit}}</td>
            <td>{{ $currency }}{{$value->UnitCost}}</td>
            @if($quotation->configurableitems == 4)
            <td colspan="2">{{ $currency }}{{ round(($value->LMPerDoorType * $value->UnitCost) , 2)}}</td>
            @else
            <td colspan="2">{{ $currency }}{{$value->TotalCost * $value->QuantityOfDoorTypes}}</td>
            @endif

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
            <td colspan="2">{{ $currency }}{{ $total }}</td>
            <td></td>
            <td></td>
            <td>{{ $currency }}{{ $GTSellPrice }}</td>
            <td></td>
        </tr>
        @endif
