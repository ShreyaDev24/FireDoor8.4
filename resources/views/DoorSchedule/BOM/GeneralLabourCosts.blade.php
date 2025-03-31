@php $i = 0; $check = 0; $total=0; $GTSellPrice=0;@endphp

    @foreach($data as $value)
        @if($value->Category=='GeneralLabourCosts')
        @php $check =  1; @endphp
        @if($i++==0)
        <tr class="bg-white">
            <td colspan="16"></td>
        </tr>
        <tr style="background:#00B0F0">
            <td colspan="16"><b>General Labour Costs - Total Cost: </b></td>
        </tr>
        <tr style="margin-top:30px">
            <th>Door Type</th>
            <th colspan="4">Labour Element</th>
            <th>MAN HOURS</th>
            <th>MAN Hour Rate</th>
            <th>MACHINE HOURS</th>
            <th>MACHINE Hour Rate</th>
            {{-- <th>Qty Per  Door Type </th> --}}
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
            {{--  <td>{{$value->DoorType}}</td>
            <td  colspan="4"> {{ isset($words[1]) ? $words[1] : '' }} </td>
            <td> {{ isset($words[2]) ? round($words[2], 2) : '' }} </td>
            <td> {{ isset($words[3]) ? round($words[3], 2) : '' }} </td>
            <td> {{ isset($words[4]) ? round($words[4], 2) : '' }} </td>
            <td> {{ isset($words[5]) ? round($words[5], 2) : '' }} </td>
            <td>{{$value->sum}}</td>
            <td>{{$value->Unit}}</td>
            <td>{{ $currency }}{{$value->sum_UnitCost}}</td>
            <td>{{ $currency }}{{ round($value->sum_TotalCost,2)}}</td>
            <td>{{ $currency }}{{$value->sum_UnitPriceSell}}</td>
            <td>{{ $currency }}{{$value->sum_GTSellPrice}}</td>
            <td>{{$value->Margin}}%</td>  --}}

            <td>{{$value->DoorType}}</td>
            <td  colspan="4"> {{ isset($words[1]) ? $words[1] : '' }} </td>
            <td> {{ isset($words[2]) ? round($words[2], 2) : '' }} </td>
            <td> {{ isset($words[3]) ? round($words[3], 2) : '' }} </td>
            <td> {{ isset($words[4]) ? round($words[4], 2) : '' }} </td>
            <td> {{ isset($words[5]) ? round($words[5], 2) : '' }} </td>
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
    global $GeneralLabourCostsstotal;
    global $GeneralLabourCostsstotalGTSell;
    $GeneralLabourCostsstotal=round($total,2);
    $GeneralLabourCostsstotalGTSell=round($GTSellPrice,2);
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
