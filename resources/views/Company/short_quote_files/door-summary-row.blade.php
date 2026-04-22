<tr>
    <td>{{ $doorSummary['doorNumber'] }}</td>
    <td>{{ $doorSummary['DoorDescription'] }}</td>
    <td>{{ $doorSummary['DoorType'] }}</td>
    <td>{{ $doorSummary['qty'] }}</td>
    @if($hideCosts == 0)
        <td>{{ number_format($doorSummary['doorPrice'], 2) }}</td>
        <td>{{ number_format($doorSummary['ironPrice'], 2) }}</td>
    @endif
    <td class="price">{{ number_format($doorSummary['total'], 2, '.', '') }}</td>
</tr>
