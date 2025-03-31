@php $i = 0; $check = 0;$total=0; $GTSellPrice=0;

$IntumescentSealData = collect($data)->where("Category", "IntumescentSeal")->groupBy('Description');
$IronmomngeryMaterialData = collect($data)->where("Category", "IronmomngeryMaterial")->groupBy('Description');

@endphp

@foreach($data as $val)
    @if($val->Category == 'IntumescentSeal')
        @php
            $check = 1;
            $MarginMarkup = $val->MarginMarkup;
        @endphp
    @endif
@endforeach

@if(count($IntumescentSealData) > 0 || count($IronmomngeryMaterialData) > 0)
<tr class="bg-white">
            <td colspan="16"></td>
        </tr>
<tr style="background:#00B0F0">
    <td colspan="16"><b>Intumescent Strip / Seals - Total Cost:</b></td>
</tr>
<tr>
    <th>Door Type</th>
    <th>Intumescent Seal Type</th>
    <th>Intumescent Seal Location </th>
    <th>Intumescent Seal Colour</th>
    <th>Brand</th>
    <th colspan="3">Intumescent Seal</th>
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
    <th>{{ $MarginMarkup }}</th>
</tr>

@endif


    @foreach($IntumescentSealData as $IntumescentSealDataGroup)
        @php
                $value = new stdClass();
                // $value->Category = 'IntumescentSeal';
                $value->Description =  "";
                $value->QuantityOfDoorTypes =  0;
                $value->LMPerDoorType =  0;
                $value->Unit =  "";
                $value->UnitCost =  0;
                $value->TotalCost =  0;
                $value->UnitPriceSell =  0;
                $value->GTSellPrice =  0;
                $value->Margin =  0;

                foreach($IntumescentSealDataGroup as $IntumescentSealDataGroupRow){
                    $value->Description =  $IntumescentSealDataGroupRow->Description;
                    $value->LMPerDoorType +=  $IntumescentSealDataGroupRow->LMPerDoorType;
                    $value->QuantityOfDoorTypes +=  $IntumescentSealDataGroupRow->QuantityOfDoorTypes;
                    $value->Unit =  $IntumescentSealDataGroupRow->Unit;
                    $value->UnitCost +=  $IntumescentSealDataGroupRow->UnitCost;
                    $value->TotalCost +=  $IntumescentSealDataGroupRow->TotalCost;
                    $value->UnitPriceSell +=  $IntumescentSealDataGroupRow->UnitPriceSell;
                    $value->GTSellPrice +=  $IntumescentSealDataGroupRow->GTSellPrice;
                    $value->Margin +=  $IntumescentSealDataGroupRow->Margin;
                    $words = explode("|", $value->Description);
                }
        @endphp

        {{-- @if($value->Category == 'IntumescentSeal') --}}

        <tr>
        <td>{{ isset($words[0]) ? $words[0] : '' }}</td>
        <td>{{ isset($words[1]) ? $words[1] : '' }}</td>
        <td>{{ isset($words[2]) ? $words[2] : '' }}</td>
        <td>{{ isset($words[3]) ? $words[3] : '' }}</td>
        <td>{{ isset($words[4]) ? $words[4] : '' }}</td>
        <td colspan="3">{{ isset($words[5]) ? $words[5] : '' }}</td>
            <td>{{$value->LMPerDoorType}}</td>
            <td>{{$value->QuantityOfDoorTypes}}</td>
            <td>{{$value->Unit}}</td>
            <td>{{$value->UnitCost}}</td>
            <td>{{ round($value->TotalCost,2) }}</td>
            <td>{{$value->UnitPriceSell}}</td>
            <td>{{$value->GTSellPrice}}</td>
            <td>{{$value->Margin}}%</td>
        </tr>
        @php
        $total = $total + $value->TotalCost;
        $GTSellPrice = $GTSellPrice + $value->GTSellPrice;

    @endphp
        {{-- @endif --}}
@endforeach

@php
global $intumscenttotal, $intumscentGTSell;
$intumscenttotal = $total;
$intumscentGTSell = $GTSellPrice;
@endphp

@if($check==1)
<tr style="background:gray">
    <td colspan="8"><b>Total</b></td>
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
