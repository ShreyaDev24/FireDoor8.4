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
    <td>ScreenSets</td>
    <td >{{ $totDoorsetType }}</td>
    </tr>
    </tbody>
</table>


<table style="width:30.2%; margin-top:-1px; border-top:none;">
    <tbody>
    <tr>
    <td>Total Cost </td>
    <td >{{ $currency }}{{ $TotalCostSum }}</td>
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
