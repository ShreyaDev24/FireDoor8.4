<div class="card-header">
    <h3 class="w-100">
        Edit Headers Details
        <a href="javascript:void(0);" onclick="$('#edit-header-section').hide();$('#quotation-item-list').show();$('#nonConfig').addClass('activeNC');"
            class="add_button"><i class="fa fa-times"></i></a>
    </h3>
</div>
<div class="card-body">
    <form id="signupForm" enctype="multipart/form-data" method="post" action="{{route('quotation/store')}}">
        {{csrf_field()}}
        <input type="hidden" name="quotationId" value="{{$quotationId}}">
        <input type="hidden" name="selectVersionID" value="{{ ($selectQV['selectVersionID'] > 0)?$selectQV['selectVersionID']:0 }}">
        <input type="hidden" name="ShippingAddressId" id="ShippingAddressId">
        <input type="hidden" name="ProjectId" id="ProjectId" value="@if(isset($projectId)){{$projectId}}@else{{'0'}}@endif">
        <div class="row" data-select2-id="5">
            <div class="col-sm-6">
                <div class="card-header">
                    <h5 class="card-title" style="margin-top: 10px">Quote Information</h5>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="ProductName">Due Date<span
                                    class="text-danger">*</span></label>
                            <input type="text" readonly class="form-control datepicker"
                                name="ExpiryDate"
                                value="@if(!empty($quotation->ExpiryDate)){{ date('d-m-Y',strtotime($quotation->ExpiryDate)) }}@endif"
                                required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="ProductName">Follow-up Date<span
                                    class="text-danger">*</span></label>
                            <input type="text" readonly class="form-control datepicker"
                                name="FollowUpDate"
                                value="@if(!empty($quotation->FollowUpDate)){{ date('d-m-Y',strtotime($quotation->FollowUpDate)) }}@endif"
                                required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="ProductName">Quote Name<span
                                    class="text-danger">*</span></label>
                            <input type="text" readonly class="form-control" name="QuotationName"
                                value="@if(!empty($quotation->QuotationName)){{$quotation->QuotationName}}@endif"
                                required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="ProductName">Quote Status</label>
                            <input type="text" readonly readonly class="form-control"
                                placeholder="Ex:. Open" value="@if(!empty($quotation->QuotationStatus)){{$quotation->QuotationStatus}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6" data-select2-id="4">
                        <div class="position-relative form-group">
                            <label for="Customer">Change Status</label>
                            <select readonly onchange="get_user_commission(this.value);"
                                name="QuotationStatus" class="form-control">
                                @php
                                    $rr = ['Open','Ordered','Quote Returned','Order Value'];
                                    $i = 0;
                                    $count = count($rr);
                                    $quotStatus = '<option value="">Select Status</option>';
                                    while($count > $i){
                                        $selected = '';
                                        if(!empty($quotation)){
                                            if($quotation->QuotationStatus == $rr[$i]){
                                                $selected = "selected";
                                            }
                                        }
                                        $quotStatus .= '<option value="'.$rr[$i].'" '.$selected.'>'.$rr[$i].'</option>';
                                        $i++;
                                    }
                                    echo  $quotStatus;
                                @endphp
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6" data-select2-id="4">
                        <div class="position-relative form-group">
                            <label for="Customer">Select Project</label>
                            <select readonly name="projectId" class="form-control selectprojectId" >
                                <option value="">Select Project</option>
                                @foreach($ProjectTable as $Project)
                                <option value="{{$Project['id']}}"
                                    @if($quotation->ProjectId == $Project['id'])
                                        {{'selected'}}
                                    @endif
                                    >{{$Project['ProjectName']}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="ProductName">Currency <span class="text-danger">*</span></label>
                            <select readonly name="Currency" id="Currency" class="form-control">
                                <option value="">Select Currency</option>
                                {{--  <option value="$_US_DOLLAR"
                                    @php
                                        if(!empty($quotation->Currency)){
                                            if($quotation->Currency == '$_US_DOLLAR'){
                                                echo 'selected';
                                            }
                                        } else {
                                            if(!empty($currency->currency)){
                                                if($currency->currency == '$_US_DOLLAR'){
                                                    echo 'selected';
                                                }
                                            }
                                        }
                                    @endphp
                                >$ US DOLLAR</option>  --}}
                                <option value="£_GBP"
                                    @php
                                        if(!empty($quotation->Currency)){
                                            if($quotation->Currency == '£_GBP'){
                                                echo 'selected';
                                            }
                                        } else {
                                            if(!empty($currency->currency)){
                                                if($currency->currency == '£_GBP'){
                                                    echo 'selected';
                                                }
                                            }
                                        }
                                    @endphp
                                >£ GBP</option>
                                <option value="€_EURO"
                                    @php
                                        if(!empty($quotation->Currency)){
                                            if($quotation->Currency == '€_EURO'){
                                                echo 'selected';
                                            }
                                        } else {
                                            if(!empty($currency->currency)){
                                                if($currency->currency == '€_EURO'){
                                                    echo 'selected';
                                                }
                                            }
                                        }
                                    @endphp
                                >€ EURO</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="ProductName">Sales Contact<span
                                    class="text-danger">*</span></label>
                            <input type="text" readonly class="form-control" name="SalesContact"
                            value="@if(!empty($quotation->SalesContact)){{$quotation->SalesContact}}@endif"
                                required>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-6">
                <div class="card-header">
                    <h5 class="card-title" style="margin-top: 10px">Site Contact Information</h5>
                </div>
                <div class="col-md-12 p-0">
                    <div class="position-relative form-group">
                        <label for="ProductName">Site Contact </label>
                        <select readonly name="Contact" class="form-control CustomerId" disabled>
                            <option value="">Select Contact List</option>
                            @if(!empty($customerMultiContact))
                            @foreach($customerMultiContact as $row)
                            <option @if($quotation_data->QSCustomerContactId==$row->id) selected @endif value="{{$row->id}},{{$row->MainContractorId}}"
                                @if(!empty($QuotationContactInformation->Contact))
                                @php $array_data = explode(",",$QuotationContactInformation->Contact); @endphp
                                    @if($array_data[0] == $row->id && $array_data[1] == $row->MainContractorId)
                                    {{'selected'}}
                                    @endif
                                @endif
                            >{{$row->FirstName.' '.$row->LastName}}</option>
                            @endforeach
                            @endif
                        </select>
                        <!-- <input type="text" readonly class="form-control" name="Contact"
                            value="@if(!empty($QuotationContactInformation->Contact)){{$QuotationContactInformation->Contact}}@endif"> -->
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Email">Email<span
                                    class="text-danger">*</span></label>
                            <input type="email" readonly class="form-control" name="Email"
                                value="@if(!empty($QuotationContactInformation->Email)){{$QuotationContactInformation->Email}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="ProductName">Phone</label>
                            <input type="text" readonly pattern="\d" class="form-control"
                                name="Phone"
                                value="@if(!empty($QuotationContactInformation->Phone)){{$QuotationContactInformation->Phone}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Mobile">Mobile</label>
                            <input type="text" readonly pattern="\d" class="form-control"
                                name="Mobile"
                                value="@if(!empty($QuotationContactInformation->Mobile)){{$QuotationContactInformation->Mobile}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="ProductName">Fax</label>
                            <input type="text" readonly class="form-control" name="Fax"
                                value="@if(!empty($QuotationContactInformation->Fax)){{$QuotationContactInformation->Fax}}@endif">
                        </div>
                    </div>
                </div>
            </div>
            @if($countDeliveryAddressInEditHeader == 0)
            <input type="hidden" name="quotation_sitedeliveryaddressID[]">
            <div class="col-sm-12">
                <div class="card-header">
                    <h5 class="card-title" style="margin-top: 10px">Site Delivery Address</h5>
                </div>
                <div>
                    <a style="float: right; margin-right: 10px; margin-top: -45px" href="javascript:void(0);" id="add-customer-detail" class="btn-shadow btn btn-success">
                        <i class="fa fa-plus"></i>
                    </a>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Address1">Address 1<span
                                    class="text-danger">*</span></label>
                            <input type="text" readonly class="form-control" name="Address1[]"
                                value="@if(!empty($QuotationShipToInformation->Address1)){{$QuotationShipToInformation->Address1}}@endif" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Address2">Address 2</label>
                            <input type="text" readonly class="form-control" name="Address2[]"
                                value="@if(!empty($QuotationShipToInformation->Address2)){{$QuotationShipToInformation->Address2}}@endif">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label for="Country">Country</label>
                            <input type="text" readonly class="form-control" name="Country[]"
                                value="@if(!empty($QuotationShipToInformation->Country)){{$QuotationShipToInformation->Country}}@endif">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label for="City">City</label>
                            <input type="text" readonly class="form-control" name="City[]"
                                value="@if(!empty($QuotationShipToInformation->City)){{$QuotationShipToInformation->City}}@endif">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="position-relative form-group">
                            <label for="PostalCode">Postal Code/Eircode</label>
                            <input type="text" readonly class="form-control" name="PostalCode[]"
                                value="@if(!empty($QuotationShipToInformation->PostalCode)){{$QuotationShipToInformation->PostalCode}}@endif">
                        </div>
                    </div>
                </div>
            </div>
            @else
                {!! $DA !!}
            @endif
            <span id="customer-details-section"></span>
            <div class="col-sm-8">
                <div class="card-header">
                    <h5 class="card-title" style="margin-top: 10px">Shipping Information
                    </h5>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="ProductName">Delivery Restrictions (if any)</label>
                            <input type="text" readonly name="DeliveryRestrictions" class="form-control"
                                value="@if(!empty($QuotationShipToInformation->DeliveryRestrictions)){{$QuotationShipToInformation->DeliveryRestrictions}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Email">Wagon Preference</label>
                            <select readonly name="WagonPreference" class="form-control">
                                @php
                                    $dd = ['40ft_Artic_Curtain_Side' => '40ft Artic Curtain Side' , '26t_Rigid_Curtain_Side' => '26t Rigid Curtain Side' , '18t_Rigid_Curtain_Side' => '18t Rigid Curtain Side' , '7.5t_Curtain_Side' => '7.5t Curtain Side' , '1t_Box_Van_Curtain_Side' => '1t Box Van Curtain Side' , 'Pallet' => 'Pallet' , 'Moffit_Off_Load' => 'Moffit Off Load' , 'Tail_Lift_Offload' => 'Tail Lift Offload' , 'Retractable_Roof' => 'Retractable Roof (Crane Off Load)'];
                                    $count = count($dd);
                                    $i = 0;
                                    $wagon = '<option value="">Select Wagon Preference</option>';
                                    while($count > $i){
                                        $selec = '';
                                        if(!empty($QuotationShipToInformation->WagonPreference)){
                                            if($QuotationShipToInformation->WagonPreference == array_keys($dd)[$i]){
                                                $selec =  'selected';
                                            }
                                        }
                                        $wagon .= '<option value="'.array_keys($dd)[$i].'" '.$selec.'>'.$dd[array_keys($dd)[$i]].'</option>';
                                        $i++;
                                    }
                                    echo $wagon;
                                @endphp
                            </select>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="Booking">Booking in process and notice period required with contact details</label>
                            <textarea name="Booking" readonly id="Booking" cols="30" rows="10" class="form-control">@if(!empty($QuotationShipToInformation->Booking)){{$QuotationShipToInformation->Booking}}@endif</textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="Deliverypolicy">Delivery policy including route into site etc</label>
                            <textarea name="Deliverypolicy" readonly id="Deliverypolicy" cols="30" rows="10" class="form-control">@if(!empty($QuotationShipToInformation->Deliverypolicy)){{$QuotationShipToInformation->Deliverypolicy}}@endif</textarea>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <label>FORS Requirements (radio buttons) </label>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label>Silver</label><br>
                            <input type="radio" id="silver1" readonly name="silver" value="Yes"
                            @if(!empty($QuotationShipToInformation->silver))
                                @if($QuotationShipToInformation->silver == 'Yes')
                                    {{'checked'}}
                                @endif
                            @endif
                            >
                            <label for="silver1">Yes</label><br>
                            <input type="radio" id="silver2" readonly name="silver" value="No"
                            @if(!empty($QuotationShipToInformation->silver))
                                @if($QuotationShipToInformation->silver == 'No')
                                    {{'checked'}}
                                @endif
                            @endif
                            >
                            <label for="silver2">No</label><br>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label>Gold</label><br>
                            <input type="radio" id="gold1" readonly name="gold" value="Yes"
                            @if(!empty($QuotationShipToInformation->gold))
                                @if($QuotationShipToInformation->gold == 'Yes')
                                    {{'checked'}}
                                @endif
                            @endif>
                            <label for="gold1">Yes</label><br>
                            <input type="radio" id="gold2" readonly name="gold" value="No"
                            @if(!empty($QuotationShipToInformation->gold))
                                @if($QuotationShipToInformation->gold == 'No')
                                    {{'checked'}}
                                @endif
                            @endif>
                            <label for="gold2">No</label><br>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Offloading">Off-loading requirements</label>
                            <input type="text"  readonly readonly name="Offloading" class="form-control"
                                value="@if(!empty($QuotationShipToInformation->Offloading)){{$QuotationShipToInformation->Offloading}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="NoOfDeliveries">No. of deliveries/drops allowed for</label>
                            <input type="number" readonly name="NoOfDeliveries" class="form-control"
                                value="@if(!empty($QuotationShipToInformation->NoOfDeliveries)){{$QuotationShipToInformation->NoOfDeliveries}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="ActualNoOfDeliveries">Actual No. of deliveries</label>
                            <input type="number" readonly name="ActualNoOfDeliveries" class="form-control"
                                value="@if(!empty($QuotationShipToInformation->ActualNoOfDeliveries)){{$QuotationShipToInformation->ActualNoOfDeliveries}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Costperdelivery">Cost per delivery</label>
                            <input type="text" readonly readonly name="Costperdelivery" class="form-control"
                                value="@if(!empty($QuotationShipToInformation->Costperdelivery)){{$QuotationShipToInformation->Costperdelivery}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="AverageNoDoorsetsperdrop">Average no of doorsets per drop</label>
                            <input type="number" readonly name="AverageNoDoorsetsperdrop" class="form-control"
                                value="@if(!empty($QuotationShipToInformation->AverageNoDoorsetsperdrop)){{$QuotationShipToInformation->AverageNoDoorsetsperdrop}}@endif">
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-4">
                <div class="card-header">
                    <h5 class="card-title" style="margin-top: 10px">Payment Information
                    </h5>
                </div>
                <div class="col-md-12 p-0">
                    <div class="position-relative form-group">
                        <label for="Payment Method">Payment Method</label>
                        <input type="text" readonly class="form-control" name="PaymentMethod"
                            value="@if(!empty($QuotationShipToInformation->PaymentMethod)){{$QuotationShipToInformation->PaymentMethod}}@endif">
                    </div>
                </div>

                <div class="col-md-12 p-0">
                    <div class="position-relative form-group">
                        <label for="PaymentTerms">Payment Terms</label>
                        <input type="text" readonly class="form-control" name="PaymentTerms"
                            value="@if(!empty($QuotationShipToInformation->PaymentTerms)){{$QuotationShipToInformation->PaymentTerms}}@endif">
                    </div>
                </div>

            </div>
        </div>
        @if($quotation->QuotationStatus != 'Ordered')
            <hr>
            <button type="submit" class="btn btn-success">Submit Details</button>
        @endif
    </form>
</div>
