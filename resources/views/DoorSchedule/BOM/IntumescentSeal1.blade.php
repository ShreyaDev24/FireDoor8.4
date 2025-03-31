@php $i = 0; $check = 0;$total=0; $GTSellPrice=0;

$IntumescentSealData = collect($data)->where("Category", "IntumescentSeal")->groupBy('Description');
$IronmomngeryMaterialData = collect($data)->where("Category", "IronmomngeryMaterial")->groupBy('Description');

@endphp

@php $check =  1; @endphp
@if(count($IntumescentSealData) > 0 || count($IronmomngeryMaterialData) > 0)
<tr>
    <th colspan="6">Description</th>

    <th colspan="2">Total Quantity </th>
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
<tr style="background:#00B0F0">
    <td colspan="3"><b>Intumescent Strip / Seals - Total Cost:</b></td>
    <td>#######</td>
    <td ></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td>Text</td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
    <td></td>
</tr>
@endif


    @foreach($IntumescentSealData as $IntumescentSealDataGroup)
        @php
                $value = new stdClass();
                // $value->Category = 'IntumescentSeal';
                $value->Description =  "";
                $value->QuantityOfDoorTypes =  0;
                $value->Unit =  "";
                $value->UnitCost =  0;
                $value->TotalCost =  0;
                $value->UnitPriceSell =  0;
                $value->GTSellPrice =  0;
                $value->Margin =  0;

                foreach($IntumescentSealDataGroup as $IntumescentSealDataGroupRow){
                    $value->Description =  $IntumescentSealDataGroupRow->Description;
                    $value->QuantityOfDoorTypes +=  $IntumescentSealDataGroupRow->QuantityOfDoorTypes;
                    $value->Unit =  $IntumescentSealDataGroupRow->Unit;
                    $value->UnitCost +=  $IntumescentSealDataGroupRow->UnitCost;
                    $value->TotalCost +=  $IntumescentSealDataGroupRow->TotalCost;
                    $value->UnitPriceSell +=  $IntumescentSealDataGroupRow->UnitPriceSell;
                    $value->GTSellPrice +=  $IntumescentSealDataGroupRow->GTSellPrice;
                    $value->Margin +=  $IntumescentSealDataGroupRow->Margin;
                }
        @endphp

        {{-- @if($value->Category == 'IntumescentSeal') --}}

        <tr>
            <td colspan="6">{{ $value->Description }}</td>
            {{-- <td>{{$value->LMPerDoorType}}</td> --}}
            <td colspan="2">{{$value->QuantityOfDoorTypes}}</td>
            <td>{{$value->Unit}}</td>
            <td>{{$value->UnitCost}}</td>
            <td colspan="2">{{$value->TotalCost}}</td>
            <td colspan="2">{{$value->UnitPriceSell}}</td>
            <td>{{$value->GTSellPrice}}</td>
            <td>{{$value->Margin}}%</td>
        </tr>
        @php
        $total = $total + $value->TotalCost;
        $GTSellPrice = $GTSellPrice + $value->GTSellPrice;
    @endphp
        {{-- @endif --}}
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
