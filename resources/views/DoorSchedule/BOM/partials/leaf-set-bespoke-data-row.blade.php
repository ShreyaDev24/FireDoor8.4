@php $rowTotals = $rowTotals ?? leafSetBespokeRowTotals($value); @endphp
<tr>
    <td>{{ $value->DoorType }}</td>
    <td> {{ isset($words[1]) ? $words[1] : '' }} </td>
    <td> {{ isset($words[2]) ? $words[2] : '' }} </td>
    <td colspan="3"> {{ isset($words[3]) ? $words[3] : '' }} </td>
    <td colspan="2"> {{ isset($words[4]) ? $words[4] : '' }} </td>
    <td colspan="2"> {{ isset($words[5]) ? $words[5] : '' }} </td>
    <td>{{ $value->QuantityOfDoorTypes }}</td>
    <td>{{ $value->Unit }}</td>
    <td>{{ $currency }}{{ $rowTotals['unitCost'] }}</td>
    <td>{{ $currency }}{{ $rowTotals['totalCost'] }}</td>
    <td>{{ $currency }}{{ $rowTotals['unitPriceSell'] }}</td>
    <td>{{ $currency }}{{ $rowTotals['gtSellPrice'] }}</td>
    <td>{{ $value->Margin }}%</td>
</tr>
