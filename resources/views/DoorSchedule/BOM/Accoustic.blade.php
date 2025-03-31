@php $i = 0; $check = 0; $total=0; $GTSellPrice=0;@endphp

    @foreach($data as $value)
        @if($value->Category=='Accoustics')
        @php $check =  1; @endphp
        @if($i++==0)
        <tr class="bg-white">
            <td colspan="16"></td>
        </tr>
        <tr style="background:#00B0F0">
            <td colspan="16"><b>Acoustics</b></td>
        </tr>
        <tr style="margin-top:10px">
            <th>Door Type</th>
            <th>rW dB Rating</th>
            <th  colspan="2">Perimeter Seal 1 </th>
            <th colspan="2">Perimeter Seal 2</th>
            {{-- <th  colspan="2">Threshold Seal 1 </th>
            <th>Threshold Seal 2 </th> --}}
            <th colspan="2">LM Per  Door Type </th>
            <th>Quantity of door types </th>
            <th>Unit</th>
            <th>Unit Cost</th>
            <th>Total Cost</th>
            <th colspan="2">Unit Price Sell</th>
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
            <td  colspan="2"> {{ isset($words[2]) ? $words[2] : '' }} </td>
            <td colspan="2"> {{ isset($words[3]) ? $words[3] : '' }} </td>
            {{-- <td  colspan="2"> {{ isset($words[4]) ? $words[4] : '' }} </td>
            <td> {{ isset($words[5]) ? $words[5] : '' }} </td> --}}
            <td colspan="2">{{$value->LMPerDoorType}}</td>
            <td>{{$value->QuantityOfDoorTypes}}</td>
            <td>{{$value->Unit}}</td>
            <td>{{ $currency }}{{$value->UnitCost}}</td>
            {{-- @if($quotation->configurableitems == 4)
            <td>{{ $currency }}{{ round(($value->LMPerDoorType * $value->UnitCost) , 2)}}</td>
            @else
            <td>{{ $currency }}{{$value->TotalCost * $value->QuantityOfDoorTypes}}</td>
            @endif --}}
            <td>{{ $currency }}{{$value->TotalCost}}</td>
            <td colspan="2">{{ $currency }}{{$value->UnitPriceSell}}</td>

            {{-- <td>{{ $currency }}{{$value->UnitPriceSell}}</td> --}}
            <td>{{ $currency }}{{$value->GTSellPrice}}</td>
            <td>{{$value->Margin}}%</td>
        </tr>

        @endif
    @endforeach
    @php
    global $Accousticstotal;
    global $AccousticstotalGTSell;
    $Accousticstotal=round($total,2);
    $AccousticstotalGTSell=round($GTSellPrice,2);
    @endphp

    @if($check==1)
       <tr style="background:gray">
            <td colspan="6"><b>Total </b></td>
            <td colspan="2"></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{ $currency }}{{ round($total,2) }}</td>
            <td colspan="2"></td>
            <td>{{ $currency }}{{ $GTSellPrice }}</td>
            <td></td>
        </tr>
        @endif
