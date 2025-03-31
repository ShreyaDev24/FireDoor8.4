@php
global $intumscenttotal, $intumscentGTSell, $GlazingBeadstotal , $Framestotal ,$Accousticstotal , $Architravetotal ,$Glassstotal , $GlazingSystemstotal ,$LeafSetBesPokestotal , $MachiningCostsstotal , $GeneralLabourCostsstotal;
global $intumscenttotalGTSell, $intumscentGTSellGTSell, $GlazingBeadstotalGTSell , $FramestotalGTSell ,$AccousticstotalGTSell , $ArchitravetotalGTSell ,$GlassstotalGTSell , $GlazingSystemstotalGTSell ,$LeafSetBesPokestotalGTSell , $MachiningCostsstotalGTSell , $GeneralLabourCostsstotalGTSell;
$total=0; $GTSellPrice=0; $Margin = 0;$passArr = array();
$failed = array();


@endphp

{{-- @foreach($data as $value)
    @php

    $arr = ['GlazingBeads', 'Frame', 'Accoustics', 'Architrave', 'Glass', 'GlazingSystem', 'LeafSetBesPoke', 'MachiningCosts'];
    if(in_array($value->Category,$arr)){
        if(!in_array($value->Category, $passArr)){
            array_push($passArr, $value->Category);
        }
        $total = $total + $value->TotalCost;
        $GTSellPrice = $GTSellPrice + $value->GTSellPrice;
        $Margin = $Margin + $value->Margin;
    }else{
        if(!in_array($value->Category, $failed)){
            array_push($failed, $value->Category);
        }
    }
    if($value->Category == "GeneralLabourCosts"){
        $total = $total + $value->sum_TotalCost;
        $GTSellPrice = $GTSellPrice + $value->sum_GTSellPrice;
    }
    @endphp
@endforeach --}}
@php
    $total= $GlazingBeadstotal + $Framestotal +$Accousticstotal + $Architravetotal +$Glassstotal + $GlazingSystemstotal + $intumscenttotal +$LeafSetBesPokestotal + $MachiningCostsstotal + $GeneralLabourCostsstotal;
    $GTSellPrice= $GlazingBeadstotalGTSell + $FramestotalGTSell +$AccousticstotalGTSell + $ArchitravetotalGTSell +$GlassstotalGTSell + $GlazingSystemstotalGTSell +$intumscentGTSell +$LeafSetBesPokestotalGTSell + $MachiningCostsstotalGTSell + $GeneralLabourCostsstotalGTSell;
@endphp
{{-- @dd($GlazingBeadstotalGTSell , $FramestotalGTSell ,$AccousticstotalGTSell , $ArchitravetotalGTSell ,$GlassstotalGTSell , $GlazingSystemstotalGTSell , $intumscenttotal ,$LeafSetBesPokestotalGTSell , $MachiningCostsstotalGTSell , $GeneralLabourCostsstotalGTSell, $GTSellPrice) --}}

@php
    // if(isset($ironmongerytotal)){
    //     $total = $total+ $ironmongerytotal;
    // }

@endphp


<table>
    <tbody>
    <tr>
    <td>Ref</td>
    <td colspan="3">{{ $quotation->QuotationGenerationId }}</td>
    <td>Project</td>
    <td>{{ $quotation->projectname }}</td>
    <td>Prepared By</td>
    <td>{{ $userName }}</td>
    </tr>

    <tr style="border-bottom:1px solid #000;">
    <td>Revision</td>
    <td colspan="3">{{ $data[0]->VersionId }}</td>
    <td>Date</td>
    <td>{{ $today }}</td>
    <td >Sales Contact</td>
    <td>{{ $quotation->SalesContact }}</td>
    </tr>
    </tbody>
</table>

<table style="width:30.2%; margin-top:-1px; border-top:none;">
    <tbody>
    <tr>
    <td>Doorsets</td>
    <td >{{ $totDoorsetType }}</td>
    </tr>

    <tr style="border-bottom:1px solid #000;">
    <td>Ironmongery Sets </td>
    <td>{{ $totIronmongerySet }}</td>
    </tr>
    </tbody>
</table>


<table style="width:30.2%; margin-top:-1px; border-top:none;">
    <tbody>
    <tr>
    <td>Total Cost </td>
    <td >{{ $currency }}{{ $total }}</td>
    </tr>

    <tr style="border-bottom:1px solid #000;">
    <td>Calculated Sale Price </td>
    <td>{{ $currency }}{{ $GTSellPriceSum }}</td>
    </tr>
    </tbody>
</table>

<table style="width:30.2%; margin-top:-1px; border-top:none;">
    <tbody>

    <tr style="border-bottom:1px solid #000;">
    <td>Any Prices OverRidden</td>
    <td>0</td>
    </tr>
    </tbody>
</table>
