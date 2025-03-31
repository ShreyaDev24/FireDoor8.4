@php $i = 0; $check = 0; $total=0;$GTSellPrice=0;@endphp
    @foreach($data as $value)
        @if($value->Category=='GlazingBeads')
        @php $check =  1; @endphp
        @if($i++==0)
        <tr class="bg-white">
            <td colspan="16"></td>
        </tr>
        <tr style="background:#00B0F0">
            <td colspan="16"><b>Glazing Beads</b></td>
        </tr>
        <tr>
            <th>Door Type</th>
            <th>Glazing Beads</th>
            <th>Glazing Bead Species</th>
            <th>Finish</th>
            <th  colspan="2">Glazing Bead Dimensions</th>
            <th  colspan="2">Vision Panel Dimensions</th>
            <th>Qty Per  Door Type </th>
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
        <td> {{ isset($words[0]) ? $words[0] : '' }} </td>
        <td> {{ isset($words[1]) ? $words[1] : '' }} </td>
        <td> {{ isset($words[2]) ? $words[2] : '' }} </td>
        <td> {{ isset($words[3]) ? $words[3] : '' }} </td>
        <td  colspan="2"> {{ isset($words[4]) ? $words[4] : '' }} </td>
        <td  colspan="2"> {{ isset($words[5]) ? $words[5] : '' }} </td>
            <td>{{$value->LMPerDoorType}}</td>
            <td>{{$value->QuantityOfDoorTypes}}</td>
            <td>{{$value->Unit}}</td>
            <td>{{ $currency }}{{$value->UnitCost}}</td>
            <td >{{ $currency }}{{ round($value->TotalCost, 2) }}</td>
            <td >{{ $currency }}{{$value->UnitPriceSell}}</td>
            <td>{{ $currency }}{{$value->GTSellPrice}}</td>
            <td>{{$value->Margin}}%</td>
        </tr>

        @endif
    @endforeach
    @php
    global $GlazingBeadstotal;
    global $GlazingBeadstotalGTSell;
    $GlazingBeadstotal=round($total,2);
    $GlazingBeadstotalGTSell=round($GTSellPrice,2);
    @endphp

    @if($check==1)
       <tr style="background:gray">
            <td colspan="8"><b>Total </b></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td >{{ $currency }}{{ round($total,2) }}</td>
            <td ></td>
            <!-- <td colspan=""></td> -->
            <td>{{ $currency }}{{ $GTSellPrice }}</td>
            <td></td>
        </tr>
        @endif
