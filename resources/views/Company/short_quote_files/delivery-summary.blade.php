<p><span style="font-size:36px"><strong>{{ $project->ProjectName }}</strong></span></p>

<p><span style="font-size:36px"><strong>{{ $quotation->QuotationGenerationId }} -&nbsp;&nbsp; Delivery Summary </strong></span></p>

<p>&nbsp;</p>
<table class="table table-bordered">
    @foreach($siteDeliveries as $index => $delivery)
        <tr>
            <td colspan="4">
                <p><span style="font-size:15px"><strong>Site Delivery Address - {{ $loop->iteration }} </strong></span></p>
            </td>
        </tr>
        <tr>
            <td class="tbl_color"><span>Address 1</span></td>
            <td colspan="3">
                <span>{{ $delivery['Address1'] ?? '' }}</span>
            </td>
        </tr>
        <tr>
            <td class="tbl_color"><span>Address 2</span></td>
            <td colspan="3">
                <span>{{ $delivery['Address2'] ?? '' }}</span>
            </td>
        </tr>
        <tr>
            <td class="tbl_color"><span>Country</span></td>
            <td colspan="3"><span>{{ $delivery['Country'] ?? '' }}</span></td>
        </tr>
        <tr>
            <td class="tbl_color"><span>City</span></td>
            <td colspan="3"><span>{{ $delivery['City'] ?? '' }}</span></td>
        </tr>
        <tr>
            <td class="tbl_color"><span>Postal Code</span></td>
            <td colspan="3"><span>{{ $delivery['PostalCode'] ?? '' }}</span></td>
        </tr>
    @endforeach

    <tr>
        <td class="tbl_color"><span>Delivery Restrictions</span></td>
        <td colspan="3"><span>{{ $shipToInfo->DeliveryRestrictions ?? '' }}</span></td>
    </tr>
    <tr>
        <td class="tbl_color"><span>Wagon Preference</span></td>
        <td colspan="3">
            <span>{{ $wagonPreferenceLabel }}</span>
        </td>
    </tr>
    <tr>
        <td class="tbl_color"><span>Booking Notice & Contact</span></td>
        <td colspan="3"><span>{{ $shipToInfo->Booking ?? '' }}</span></td>
    </tr>
    <tr>
        <td class="tbl_color"><span>Delivery Policy</span></td>
        <td colspan="3"><span>{{ $shipToInfo->Deliverypolicy ?? '' }}</span></td>
    </tr>
    <tr>
        <td class="tbl_color"><span>FORS Requirement - Silver</span></td>
        <td><span>{{ $shipToInfo->silver ?? 'No' }}</span></td>
        <td class="tbl_color"><span>FORS Requirement - Gold</span></td>
        <td><span>{{ $shipToInfo->gold ?? 'No' }}</span></td>
    </tr>
    <tr>
        <td class="tbl_color"><span>Off-loading Requirements</span></td>
        <td colspan="3"><span>{{ $shipToInfo->Offloading ?? '' }}</span></td>
    </tr>
    <tr>
        <td class="tbl_color"><span>No. of Deliveries</span></td>
        <td><span>{{ $shipToInfo->NoOfDeliveries ?? '' }}</span></td>
        <td class="tbl_color"><span>Actual No. of Deliveries</span></td>
        <td><span>{{ $shipToInfo->ActualNoOfDeliveries ?? '' }}</span></td>
    </tr>
    @if($shipToInfo->CurrencyCostperdelivery == '£_GBP' || $shipToInfo->CurrencyCostperdelivery == '£')
        @php $CurrencyCostperdelivery = '£'; @endphp
    @elseif($shipToInfo->CurrencyCostperdelivery == '€_EURO' || $shipToInfo->CurrencyCostperdelivery == '€')
        @php $CurrencyCostperdelivery = '€'; @endphp
    @endif
    <tr>
        <td class="tbl_color"><span>Cost Per Delivery</span></td>
        <td><span>{{ $CurrencyCostperdelivery ?? '' }}{{ number_format((float)$shipToInfo->Costperdelivery, 2) ?? '0.00' }}</span></td>
        <td class="tbl_color"><span>Average No. Doorsets per Drop</span></td>
        <td><span>{{ $shipToInfo->AverageNoDoorsetsperdrop ?? '' }}</span></td>
    </tr>
</table>
