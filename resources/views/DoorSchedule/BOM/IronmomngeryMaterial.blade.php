@php  $i = 0; $check = 0; $total=0; $GTSellPrice=0; @endphp

@php $i = 0; $check = 0;

$IronmomngeryMaterialData = collect($data)->where("Category", "IronmomngeryMaterial")->groupBy('Description');

// dd($IronmomngeryMaterialData);

@endphp
    @foreach($data as $value)
        @if($value->Category=='IronmomngeryMaterial')
        @php $check =  1; @endphp
        {{-- @if($i++==0)
        <tr>
            <th colspan="6">Description</th>
            <th>Qty Per  Door Type </th>
            <th>Quantity of door types </th>
            <th>Unit</th>
            <th>Unit Cost</th>
            <th colspan="2">Total Cost</th>
            <th colspan="2">Unit Price Sell</th>
            <th>GT Sell Price</th>
            <th>Margin</th>
        </tr>
        <tr style="background:#00B0F0">
            <td colspan="3"><b>Ironmongery Material</b></td>
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
        @endif --}}
        <tr>
            <td colspan="6">{{ $value->Description }}</td>
            {{-- <td>{{$value->LMPerDoorType}}</td> --}}
            <td colspan="2">{{$value->QuantityOfDoorTypes}}</td>
            <td>{{$value->Unit}}</td>
            <td>{{$value->UnitCost}}</td>
            <td colspan="2">{{ round($value->TotalCost,2) }}</td>
            <td colspan="2">{{$value->UnitPriceSell}}</td>
            <td>{{$value->GTSellPrice}}</td>
            <td>{{$value->Margin}}%</td>
        </tr>

        @endif

        @php
           $total = $total + $value->TotalCost;
        //    $ironmongerytotal = $total;
           $GTSellPrice = $GTSellPrice + $value->GTSellPrice;
           $words = explode("|", $value->Description);
        @endphp

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
    <td></td>
    <td></td>
    <td>Text</td>
    <td></td>
    <td></td>
    <td></td>
</tr>
@endif
