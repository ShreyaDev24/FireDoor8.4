@php $i = 0; $check = 0; $total=0; $GTSellPrice=0;@endphp
    @foreach($data as $value)
        @if($value->Category=='Glass')
        @php $check =  1; @endphp
        @if($i++==0)
        <tr class="bg-white">
            <td colspan="16"></td>
        </tr>
        <tr style="background:#00B0F0">
            <td colspan="16"><b>Glass</b></td>
        </tr>
        <tr>
            <th colspan="2">Door Type</th>
            <th colspan="3">Glass Type</th>
            <th colspan="3">Vision Panel Size</th>
            <th>M2 Per Door Type </th>
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
            <td colspan="2">{{ $value->DoorType }}</td>
            <td colspan="3">{{ isset($words[1]) ? str_replace('_', ' ',  $words[1]) : '' }}</td>
            <td colspan="3">{{ isset($words[2]) ? $words[2] : '' }}</td>
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
    @php
    global $Glassstotal;
    global $GlassstotalGTSell;
    $Glassstotal=round($total,2);
    $GlassstotalGTSell=round($GTSellPrice,2);
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
