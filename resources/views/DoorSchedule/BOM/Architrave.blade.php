@php $i = 0; $check = 0; $total=0; $GTSellPrice=0;@endphp

    @foreach($data as $value)
        @if($value->Category=='Architrave')
        @php $check =  1; @endphp
        @if($i++==0)
        <tr class="bg-white">
            <td colspan="16"></td>
        </tr>
        <tr style="background:#00B0F0">
            <td colspan="16"><b>Architrave - Total Cost:</b></td>
        </tr>
        <tr style="margin-top:10px">
            <th colspan="">Door Type</th>
            <th>Architrave Size</th>
            <th>Architrave Type </th>
            <th  colspan="2">Architrave Material</th>
            <th  colspan="2">Architrave Finsih</th>
            <th >Set Qty</th>
            <th>LM Per  Door Type </th>
            <th>Quantity of door types </th>
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
            <td colspan="">{{$value->DoorType}}</td>
            <td> {{ isset($words[1]) ? $words[1] : '' }} </td>
            <td> {{ isset($words[2]) ? $words[2] : '' }} </td>
            <td  colspan="2"> {{ isset($words[3]) ? $words[3] : '' }} </td>
            <td  colspan="2"> {{ isset($words[4]) ? $words[4] : '' }} </td>
            <td> {{ isset($words[5]) ? $words[5] : '' }} </td>
            <td>{{$value->LMPerDoorType}}</td>
            <td>{{$value->QuantityOfDoorTypes}}</td>
            <td>{{$value->Unit}}</td>
            <td>{{ $currency }}{{$value->UnitCost}}</td>
            {{-- @if($quotation->configurableitems == 4)
            <td>{{ $currency }}{{ round(($value->LMPerDoorType * $value->UnitCost) , 2)}}</td>
            @else
            <td>{{ $currency }}{{$value->TotalCost * $value->QuantityOfDoorTypes}}</td>
            @endif --}}
            <td>{{ $currency }}{{$value->TotalCost}}</td>
            <td>{{ $currency }}{{$value->UnitPriceSell}}</td>

            {{-- <td>{{ $currency }}{{$value->UnitPriceSell}}</td> --}}
            <td>{{ $currency }}{{$value->GTSellPrice}}</td>
            <td>{{$value->Margin}}%</td>
        </tr>

        @endif
    @endforeach
    @php
    global $Architravetotal;
    global $ArchitravetotalGTSell;
    $Architravetotal=round($total,2);
    $ArchitravetotalGTSell=round($GTSellPrice,2);
    @endphp

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
