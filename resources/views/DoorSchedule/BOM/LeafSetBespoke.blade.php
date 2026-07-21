@php
    $total = 0;
    $GTSellPrice = 0;
    $hasLeafSetRows = false;
    $doorDetailsTitleShown = false;
@endphp

{{-- Section 1: Vicaima / Seadec / Deanta / MMM (4, 5, 6, 9) — first Door Details layout --}}
@php $vicaimaHeaderShown = false; @endphp
@foreach ($data as $value)
    @if ($value->Category == 'LeafSetBesPoke' && in_array((int) $value->configurableitems, [4, 5, 6, 9], true))
        @if (!$vicaimaHeaderShown)
            @if (!$doorDetailsTitleShown)
                <tr class="bg-white">
                    <td colspan="17"></td>
                </tr>
                <tr style="background:#00B0F0">
                    <td colspan="17"><b>Door Details</b></td>
                </tr>
                @php $doorDetailsTitleShown = true; @endphp
            @endif
            <tr>
                <th>Door Type</th>
                <th>Door Core</th>
                <th>Lipping Type</th>
                <th colspan="3">Lipping Thickness/Lipping Species</th>
                <th colspan="2">Door Leaf Size</th>
                <th colspan="2">Door Dimensions Code</th>
                <th>Total Quantity </th>
                <th>Unit</th>
                <th>Unit Cost</th>
                <th>Total Cost</th>
                <th>Unit Price Sell</th>
                <th>GT Sell Price</th>
                <th>{{ $value->MarginMarkup }}</th>
            </tr>
            @php $vicaimaHeaderShown = true; @endphp
        @endif
        @php
            $hasLeafSetRows = true;
            $total = $total + $value->TotalCost;
            $GTSellPrice = $GTSellPrice + $value->GTSellPrice;
            $words = explode('|', $value->Description);
        @endphp
        @include('DoorSchedule.BOM.partials.leaf-set-bespoke-data-row', ['value' => $value, 'words' => $words, 'currency' => $currency])
    @endif
@endforeach

{{-- Section 2: Halspan family (1, 2, 7, 8) — second Door Details layout --}}
@php $halspanHeaderShown = false; @endphp
@foreach ($data as $value)
    @if ($value->Category == 'LeafSetBesPoke' && in_array((int) $value->configurableitems, [1, 2, 7, 8], true))
        @if (!$halspanHeaderShown)
            @if (!$doorDetailsTitleShown)
                <tr class="bg-white">
                    <td colspan="17"></td>
                </tr>
                <tr style="background:#00B0F0">
                    <td colspan="17"><b>Door Details</b></td>
                </tr>
                @php $doorDetailsTitleShown = true; @endphp
            @endif
            <tr>
                <th>Door Type</th>
                <th>Door Core</th>
                <th>Lipping Type</th>
                <th colspan="3">Lipping Thickness</th>
                <th colspan="2">Lipping Species</th>
                <th colspan="2">Door Leaf Size</th>
                <th>Total Quantity </th>
                <th>Unit</th>
                <th>Unit Cost</th>
                <th>Total Cost</th>
                <th>Unit Price Sell</th>
                <th>GT Sell Price</th>
                <th>{{ $value->MarginMarkup }}</th>
            </tr>
            @php $halspanHeaderShown = true; @endphp
        @endif
        @php
            $hasLeafSetRows = true;
            $total = $total + $value->TotalCost;
            $GTSellPrice = $GTSellPrice + $value->GTSellPrice;
            $words = explode('|', $value->Description);
        @endphp
        @include('DoorSchedule.BOM.partials.leaf-set-bespoke-data-row', ['value' => $value, 'words' => $words, 'currency' => $currency])
    @endif
@endforeach

@php
    global $LeafSetBesPokestotal;
    global $LeafSetBesPokestotalGTSell;
    $LeafSetBesPokestotal = round($total, 2);
    $LeafSetBesPokestotalGTSell = round($GTSellPrice, 2);
@endphp

@if ($hasLeafSetRows)
    <tr style="background:gray">
        <td colspan="10"><b>Total </b></td>
        <td></td>
        <td></td>
        <td></td>
        <td>{{ $currency }}{{ round($total, 2) }}</td>
        <td></td>
        <td>{{ $currency }}{{ $GTSellPrice }}</td>
        <td></td>
    </tr>
@endif
