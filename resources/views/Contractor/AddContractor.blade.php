@extends("layouts.Master")

@section("main_section")
<style type="text/css">
#calender li {
    float: left;
    list-style-type: none;
    width: 12%;
}

#calender {
    width: 100%
}
</style>
@if(session()->has('error'))
<style type="text/css">
.useremail {
    border-color: red
}
</style>
@endif

@if(session()->has('error'))
<style type="text/css">
    #useremail {
        border-color: red
    }
</style>
@endif

@section('css')
<style type="text/css">
        .exist_email {
            background-image: url('../../assets/images/danger_cross.png') !important;
            background-repeat: no-repeat;
            background-size: 12px !important;
            background-position: center right calc(2rem / 4) !important;
            border-color: #ff4141 !important;'
            box-shadow: 0 0 0 0.2rem rgb(217 37 80 / 25%) !important;
        }

        .disable_tab {
            pointer-events: none;
            filter: grayscale(100%);
            background: #e7e7e7 !important;
        }
</style>
@endsection

<?php
if(isset($editdata->id)){
$week = explode(",", $editdata->CstDeliveryDay);
$cert = explode(",", $editdata->CstCertification);
$s_a = explode(",", $editdata->CstSiteAvailability);
}

?>
<div class="app-main__outer">

    <div class="app-main__inner">

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="fas fa-skull-crossbones"></i> Alert!</h5>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
    @if(session()->has('contacterror'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="fas fa-skull-crossbones"></i> Alert!</h5>
            {{ session()->get('contacterror') }}
        </div>
        @endif

        <div class="main-card mb-3 card">
            <div class="card-body">
                <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                    <li class="nav-item tab-item">
                        <a class="nav-link show active" id="link-tab1" data-toggle="pill" href="#tab1" role="tab"
                            aria-controls="pills-home" aria-selected="true"> Company Details</a>
                    </li>

                    <li class="nav-item tab-item">
                        <a class="nav-link show disable_tab" id="link-tab2" data-toggle="pill" href="#tab2" role="tab"
                            aria-controls="pills-profile" aria-selected="false">Head Office Address</a>
                    </li>

                    <li class="nav-item tab-item">
                        <a class="nav-link show disable_tab" id="link-tab3" data-toggle="pill" href="#tab3" role="tab"
                            aria-controls="pills-contact" aria-selected="false">Add Additional Information</a>
                    </li>

                    <!-- <li class="nav-item tab-item">
                        <a class="nav-link show" id="link-tab4" data-toggle="pill" href="#tab4" role="tab"
                            aria-controls="pills-contact" aria-selected="false">Site Details</a>
                    </li> -->

                    <li class="nav-item tab-item">
                        <a class="nav-link show disable_tab" id="link-tab5" data-toggle="pill" href="#tab5" role="tab"
                            aria-controls="pills-contact" aria-selected="false">Contact</a>
                    </li>

                </ul>

                <form id="" enctype="multipart/form-data" method="post" action="{{route('contractor/store')}}">
                    {{--  signupForm  --}}

                    <div class="tab-content" id="pills-tabContent">

                        <div class="tab-pane fade active show" id="tab1" role="tabpanel" aria-labelledby="tab1">
                            <div class="main-card mb-3 card">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                @if(isset($editdata->id))
                                <input type="hidden" name="update" id="update" value="{{ $editdata->id }}">
                                <input type="hidden" name="usersId" id="UserId" value="{{ $editdata->UserId }}">
                                @endif
                                <div class="card-header">
                                    <h5 class="card-title" style="margin-top: 10px">@if(isset($editdata->id)) Update
                                        @else Add @endif Company Details</h5>
                                </div>
                                <div>
                                    <a style="float: right; margin-right: 10px; margin-top: -45px"
                                        href="{{route('contractor/list')}}" class="btn-shadow  btn btn-info">
                                        Contractor List
                                    </a>
                                </div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="Company Name"
                                                    class="">Company Name<span class="text-danger">*</span></label>
                                                <input name="CstCompanyName" required placeholder="Enter Company Name" value="@if(isset($editdata->id)){{$editdata->CstCompanyName}}@else{{(old('CstCompanyName'))}}@endif"
                                                    type="text" class="form-control" onkeypress="keyprs(this.value)">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="Website url" class="">Website</label>
                                                <input name="CstCompanyWebsite" value="@if(isset($editdata->id)){{$editdata->CstCompanyWebsite}}@else{{(old('CstCompanyWebsite'))}}@endif"
                                                    placeholder="Enter Company Website" type="text"
                                                    class="form-control" onkeypress="keyprs(this.value)">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="Company Email" class="">Company Email<span class="text-danger">*</span></label>
                                                <input name="CstCompanyEmail"
                                                    value="@if(isset($editdata->id)){{$editdata->CstCompanyEmail}}@else{{(old('CstCompanyEmail'))}}@endif"
                                                    required placeholder="Enter Company Email" type="email"
                                                    class="form-control" id="useremail">
                                                    @if ($errors->has('CstCompanyEmail'))
                                                    <p style="color:red">{{ $errors->first('CstCompanyEmail') }}</p>
                                                    @endif

                                                    <div hidden id="useremail_check">{{route('company/useremail_check')}}</div>
                                                    @if($errors->has('UserEmail'))
                                                    <p style="color:red; margin-left: 1px; font-weight: bold;">

                                                        {{$errors->first('UserEmail')}}</p>
                                                    @endif
                                                    <p id="message" style="width: inherit;font-size: 80%;color: #d92550;position: absolute;right: 30px;top: 38px;"></p>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="Company Phone"
                                                    class="">Phone<span class="text-danger">*</span></label>
                                                <input name="CstCompanyPhone"
                                                    value="@if(isset($editdata->id)){{$editdata->CstCompanyPhone}}@else{{(old('CstCompanyPhone'))}}@endif"
                                                    required placeholder="Enter Company Phone Number" type="number"
                                                    class="form-control" onkeydown="if(event.key==='.'){event.preventDefault();}">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="Company VAT" class="">VAT Number</label>
                                                <input name="CstCompanyVatNumber"
                                                    value="@if(isset($editdata->id)){{$editdata->CstCompanyVatNumber}}@else{{(old('CstCompanyVatNumber'))}}@endif"
                                                    placeholder="Enter Company Company VAT" type="text"
                                                    class="form-control" onkeypress="keyprs(this.value)">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="Cst Company Photo" class="">Logo</label>
                                                <input name="CstCompanyPhoto" type="file" class="form-control">
                                            </div>
                                        </div>

                                        @if(Auth::user()->UserType!='5')
                                        <div class="col-md-6">
                                            {{-- <div class="position-relative form-group">
                                                <label for="Password" class="">Password</label>
                                                <input name="password"
                                                    required placeholder="Enter Contact Password" type="password"
                                                    class="form-control">
                                            </div> --}}
                                        </div>
                                        @endif

                                        <div class="col-md-6">
                                            @if (!empty($editdata->CstCompanyPhoto))
                                            <img src="{{url('/')}}/CompanyLogo/@if(isset($editdata->CstCompanyPhoto)){{$editdata->CstCompanyPhoto}}@else{{old('CstCompanyPhoto')}}@endif" class="" width="100px">
                                            @endif
                                        </div>
                                        <div class="col-md-12">
                                            <div class="position-relative form-group">
                                                <label for="Company Moreinfo" class="">More Information</label>
                                                <textarea name="CstMoreInfo" id="CstMoreInfo"
                                                    placeholder="Enter More Information"
                                                    class="form-control" rows="4">@if(isset($editdata->id)){{$editdata->CstMoreInfo}}@else{{(old('CstMoreInfo'))}}@endif</textarea>
                                            </div>
                                        </div>
                                        @if (Auth::user()->UserType!='5')
                                        <div class="col-md-12">
                                            <div class="position-relative form-group">
                                                <input type="checkbox" id="sendMail" name="sendMail" value="1">
                                                <label for="Company Moreinfo" class="form-check-label">Send login details to contractor</label>
                                            </div>
                                        </div>
                                        @endif

                                    </div>
                                    <button name="company" type="button" class="btn btn-primary pull-right"
                                        id="companyDetails">Save & Next </button>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab2" role="tabpanel" aria-labelledby="tab2">
                            <div class="main-card mb-3 card">
                                <div class="card-header">
                                    <h5 class="card-title" style="margin-top: 10px">@if(isset($editdata->id)) Update
                                        @else Add @endif Company Head Office</h5>
                                </div>
                                <div class="card-body">

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="position-relative form-group">
                                                <label for="Address Line 1" class="">Address Line 1<span class="text-danger">*</span></label>
                                                <input name="CstCompanyAddressLine1"
                                                    value="@if(isset($editdata->id)){{$editdata->CstCompanyAddressLine1}}@else{{(old('CstCompanyAddressLine1'))}}@endif"
                                                    id="searchTextField" required
                                                    placeholder="Cst Company Address Line1" type="text"
                                                    class="form-control " onkeypress="keyprs(this.value)">
                                            </div>
                                        </div>
                                        <input type="hidden" id="city2" name="city2" />
                                        <input type="hidden" name="CstLat" id="CstLat">
                                        <input type="hidden" name="CstLong" id="CstLong">
                                        <!-- <div class="col-md-4">
                                            <div class="position-relative form-group">
                                                <label for="Address Line 2" class="">Address Line 2</label>
                                                <input name="CstCompanyAddressLine2" value="@if(isset($editdata->id)){{$editdata->CstCompanyAddressLine2}}@else{{(old('CstCompanyAddressLine2'))}}@endif" required placeholder="Enter Cst Company Address Line2" type="text" class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="position-relative form-group">
                                                <label for="Addres Line 3" class="">Address Line 3</label>
                                                <input name="CstCompanyAddressLine3" value="@if(isset($editdata->id)) {{$editdata->CstCompanyAddressLine3}}@else{{(old('CstCompanyAddressLine3'))}}@endif" required placeholder="Cst Company Address Line3" type="text" class="form-control">
                                            </div>
                                        </div> -->

                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="CstCountry" class="">Country<span class="text-danger">*</span></label>
                                                <input name="CstCompanyCountry" id="Country"
                                                    value="@if(isset($editdata->id)){{$editdata->CstCompanyCountry}}@else{{(old('CstCompanyCountry'))}}@endif"
                                                    required placeholder="Enter Country" type="text"
                                                    class="form-control" onkeypress="keyprs(this.value)">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="State" class="">State<span class="text-danger">*</span></label>
                                                <input name="CstCompanyState"
                                                    value="@if(isset($editdata->id)){{$editdata->CstCompanyState}}@else{{(old('CstCompanyState'))}}@endif"
                                                     placeholder="Enter State" id="State" type="text" required
                                                    class="form-control" onkeypress="keyprs(this.value)">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="CstCity" class="">City<span class="text-danger">*</span></label>
                                                <input name="CstCompanyCity" id="City"
                                                    value="@if(isset($editdata->id)){{$editdata->CstCompanyCity}}@else{{(old('CstCompanyCity'))}}@endif"
                                                    required placeholder="Enter City"  type="text" class="form-control" onkeypress="keyprs(this.value)">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="PostalCode" class="">Postal Code/Eircode<span class="text-danger">*</span></label>
                                                <input name="CstCompanyPostalCode" id="PinCode"
                                                    value="@if(isset($editdata->id)){{$editdata->CstCompanyPostalCode}}@else{{(old('CstCompanyPostalCode'))}}@endif"
                                                    placeholder="Enter Postal Code/Eircode" type="text" required class="form-control" onkeypress="keyprs(this.value)">
                                            </div>
                                        </div>
                                    </div>
                                    <button name="company" type="button" class="btn btn-primary pull-right"
                                        id="OfficeAddressSubmit">Save & Next </button>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade" id="tab3" role="tabpanel" aria-labelledby="tab3">
                            <div class="main-card mb-3 card">
                                <div class="card-header">
                                    <h5 class="card-title" style="margin-top: 10px">Default Payment Terms</h5>
                                </div>
                                <div class="row">
                                    <div class="col-sm-2">
                                        <div class="position-relative form-check"><label
                                                class="form-check-label"><input
                                                    name="CstDeliveryPaymentType"
                                                    id="CstDeliveryPaymentType" value="Pro Forma"
                                                    type="radio" @if(isset($editdata->id))
                                                @if($editdata->CstDeliveryPaymentType=='Pro Forma') checked
                                                @endif @endif class="form-check-input"> Pro Forma</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="position-relative form-check"><label
                                                class="form-check-label"><input
                                                    name="CstDeliveryPaymentType"
                                                    id="CstDeliveryPaymentType" value="30 Days" type="radio"
                                                    @if(isset($editdata->id))
                                                @if($editdata->CstDeliveryPaymentType=='30 Days') checked
                                                @endif @endif class="form-check-input"> 30 Days</label>
                                        </div>
                                    </div>
                                    <div class="col-sm-2">
                                        <div class="position-relative form-check"><label
                                                class="form-check-label"><input
                                                    name="CstDeliveryPaymentType"
                                                    id="CstDeliveryPaymentType" value="Retention"
                                                    @if(isset($editdata->id))
                                                @if($editdata->CstDeliveryPaymentType=='Retention') checked
                                                @endif @endif type="radio" class="form-check-input">
                                                Retention</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="card-header">
                                    <h5 class="card-title" style="margin-top: 10px">Delivery Times</h5>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <ul id="calender">
                                            <li>
                                                <div class="position-relative form-group">
                                                    <div class="position-relative form-check">
                                                        <input type="checkbox" id="select-all-days" class="form-check-input">
                                                        <label for="select-all-days" class="form-check-label">Select All</label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="position-relative form-group">
                                                    <div class="position-relative form-check"><input
                                                            name="CstDeliveryDay[]" id="exampleCheck1"
                                                            type="checkbox" class="form-check-input select-days"
                                                            @if(isset($editdata->id))
                                                        <?php  if(json_encode(in_array("MON",$week))=='true') {  ?>
                                                        checked <?php }  ?>
                                                        @endif
                                                        value="MON">
                                                        <label for="exampleCheck1"
                                                            class="form-check-label">MON</label>
                                                    </div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="position-relative form-group">
                                                    <div class="position-relative form-check"><input
                                                            name="CstDeliveryDay[]" @if(isset($editdata->id))
                                                        <?php  if(json_encode(in_array("TUE",$week))=='true') {  ?>
                                                        checked <?php }  ?>
                                                        @endif
                                                        id="exampleCheck2" type="checkbox"
                                                        class="form-check-input select-days"

                                                        value="TUE"><label for="exampleCheck2"
                                                            class="form-check-label">TUE</label></div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="position-relative form-group">
                                                    <div class="position-relative form-check"><input
                                                            name="CstDeliveryDay[]" @if(isset($editdata->id))
                                                        <?php  if(json_encode(in_array("WED",$week))=='true') {  ?>
                                                        checked <?php }  ?>
                                                        @endif
                                                        id="exampleCheck3" type="checkbox"
                                                        class="form-check-input select-days" value="WED"><label
                                                            for="exampleCheck3"
                                                            class="form-check-label ">WED</label></div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="position-relative form-group">
                                                    <div class="position-relative form-check"><input
                                                            name="CstDeliveryDay[]" @if(isset($editdata->id))
                                                        <?php  if(json_encode(in_array("THU",$week))=='true') {  ?>
                                                        checked <?php }  ?>
                                                        @endif
                                                        id="exampleCheck4" type="checkbox"
                                                        class="form-check-input select-days" value="THU"><label
                                                            for="exampleCheck4"
                                                            class="form-check-label ">THU</label></div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="position-relative form-group">
                                                    <div class="position-relative form-check"><input
                                                            name="CstDeliveryDay[]" @if(isset($editdata->id))
                                                        <?php  if(json_encode(in_array("FRI",$week))=='true') {  ?>
                                                        checked <?php }  ?>
                                                        @endif
                                                        id="exampleCheck5" type="checkbox"
                                                        class="form-check-input select-days" value="FRI"><label
                                                            for="exampleCheck5"
                                                            class="form-check-label ">FRI</label></div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="position-relative form-group">
                                                    <div class="position-relative form-check"><input
                                                            name="CstDeliveryDay[]" @if(isset($editdata->id))
                                                        <?php  if(json_encode(in_array("SAT",$week))=='true') {  ?>
                                                        checked <?php }  ?>
                                                        @endif
                                                        id="exampleCheck6" type="checkbox"
                                                        class="form-check-input select-days" value="SAT"><label
                                                            for="exampleCheck6"
                                                            class="form-check-label">SAT</label></div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="position-relative form-group">
                                                    <div class="position-relative form-check">
                                                    <input name="CstDeliveryDay[]"
                                                        @if(isset($editdata->id))
                                                            <?php  if(json_encode(in_array("SUN",$week))=='true') {  ?>
                                                                checked
                                                            <?php }  ?>
                                                        @endif
                                                        id="exampleCheck7" type="checkbox"
                                                        class="form-check-input select-days" value="SUN"><label
                                                            for="exampleCheck7"
                                                            class="form-check-label ">SUN</label></div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                    <div class="col-sm-12" style="margin: -11px 0px 10px -22px;">
                                    <div class="row">
                                        <div class="col-sm-3">
                                            <div class="position-relative form-check">
                                                <label class="form-check-label" style="text-align: left;"> From
                                                </label>
                                                <input name="CstDeliveryFromTime"
                                                    value="@if(isset($editdata->id)){{$editdata->CstDeliveryFromTime}}@else{{(old('CstDeliveryFromTime'))}}@endif"
                                                     type="time" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-sm-3">
                                            <div class="position-relative form-check">
                                                <label class="form-check-label"> To</label>
                                                <input name="CstDeliveryToTime"
                                                    value="@if(isset($editdata->id)){{$editdata->CstDeliveryToTime}}@else{{(old('CstDeliveryToTime'))}}@endif"
                                                    type="time" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    </div>
                                </div>

                                <div class="card-header">
                                    <h5 class="card-title" style="margin-top: 10px">Delivery Information</h5>
                                </div>


                                <div class="row">
                                            <div class="col-sm-2">
                                                <div class="position-relative form-check"><label
                                                        class="form-check-label"><input
                                                            name="CstDeliveryDeliveryType" @if(isset($editdata->id))
                                                        @if($editdata->CstDeliveryDeliveryType=='Delivery') checked
                                                        @endif
                                                        @endif
                                                        value="Delivery" type="radio" class="form-check-input">
                                                        Delivery</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="position-relative form-check"><label
                                                        class="form-check-label"><input
                                                            name="CstDeliveryDeliveryType" @if(isset($editdata->id))
                                                        @if($editdata->CstDeliveryDeliveryType=='Collection')
                                                        checked @endif @endif value="Collection" type="radio"
                                                        class="form-check-input">Collection</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="position-relative form-check"><label
                                                        class="form-check-label"><input name="CstDeliverySupplyType"
                                                            type="radio" @if(isset($editdata->id))
                                                        @if($editdata->CstDeliverySupplyType=='SupplyOnly') checked
                                                        @endif @endif class="form-check-input" value="SupplyOnly">
                                                        Supply Only</label>
                                                </div>
                                            </div>
                                            <div class="col-sm-2">
                                                <div class="position-relative form-check"><label
                                                        class="form-check-label"><input name="CstDeliverySupplyType"
                                                            @if(isset($editdata->id))
                                                        @if($editdata->CstDeliverySupplyType=='SupplyandFit')
                                                        checked @endif @endif class="form-check-input" type="radio"
                                                        value="SupplyandFit" class="form-check-input"> Supply And
                                                        Fit</label>
                                                </div>
                                            </div>
                                        </div>

                                <div class="card-header">
                                    <h5 class="card-title" style="margin-top: 10px">Certification</h5>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <ul id="calender">
                                            <li>
                                                <div class="position-relative form-group">
                                                    <div class="position-relative form-check"><input
                                                            name="CstCertification[]" id="exampleCheck10"
                                                            type="checkbox" class="form-check-input"
                                                            @if(isset($editdata->id))
                                                        <?php  if(json_encode(in_array("FSC",$cert))=='true') {  ?>
                                                        checked <?php }  ?>
                                                        @else

                                                        @endif
                                                        value="FSC"><label for="exampleCheck10"
                                                            class="form-check-label">FSC</label></div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="position-relative form-group">
                                                    <div class="position-relative form-check"><input
                                                            name="CstCertification[]" id="exampleCheck11"
                                                            type="checkbox" class="form-check-input"
                                                            @if(isset($editdata->id))
                                                        <?php  if(json_encode(in_array("PEFC",$cert))=='true') {  ?>
                                                        checked <?php }  ?>
                                                        @endif value="PEFC"><label for="exampleCheck11"
                                                            class="form-check-label">PEFC</label></div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="position-relative form-group">
                                                    <div class="position-relative form-check"><input
                                                            name="CstCertification[]" id="exampleCheck12"
                                                            type="checkbox" class="form-check-input"
                                                            @if(isset($editdata->id))
                                                        <?php  if(json_encode(in_array("LEED",$cert))=='true') {  ?>
                                                        checked <?php }  ?>
                                                        @endif value="LEED"><label for="exampleCheck12"
                                                            class="form-check-label">LEED</label></div>
                                                </div>
                                            </li>
                                            <li>
                                                <div class="position-relative form-group">
                                                    <div class="position-relative form-check"><input
                                                            name="CstCertification[]" id="exampleCheck13"
                                                            type="checkbox" class="form-check-input"
                                                            @if(isset($editdata->id))
                                                        <?php  if(json_encode(in_array("BIM360",$cert))=='true') {  ?>
                                                        checked <?php }  ?>

                                                        @endif value="BIM360"><label for="exampleCheck13"
                                                            class="form-check-label">BIM360</label>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <button name="company" type="button" class="btn btn-primary pull-right"
                                        id="AdditionalInformationSubmit">Save & Next </button>

                            </div>
                        </div>
                            <!--
                        <div class="tab-pane fade" id="tab4" role="tabpanel" aria-labelledby="tab4">
                            <div class="main-card mb-3 card">
                                <div class="card-header">
                                    <h5 class="card-title" style="margin-top: 10px"> @if(isset($editdata->id)) Update
                                        @else Add @endif Site Details</h5>
                                </div>
                                <div class="card-body">

                                    <div class="form-row">
                                        <div class="col-md-12">
                                            <div class="position-relative form-group">
                                                <label for="CST Address Line 1" class="">Site Address Line 1</label>
                                                <input name="CstSiteAddressLine1"
                                                    value="@if(isset($editdata->id)){{$editdata->CstSiteAddressLine1}}@else{{(old('CstSiteWebsite'))}}@endif"
                                                    required placeholder="Site Address Line 1" type="text"
                                                    class="form-control">
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="Country" class="">Country</label>
                                                <input name="CstSiteCountry" id="Country"
                                                    value="@if(isset($editdata->id)){{$editdata->CstSiteCountry}} @else{{(old('CstSiteCountry'))}}@endif"
                                                    required placeholder="Enter Country" type="text"
                                                    class="form-control">
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="State" class="">State/Province</label>
                                                <input name="CstSiteState" id="State"
                                                    value="@if(isset($editdata->id)){{$editdata->CstSiteState}} @else{{(old('CstSiteState'))}}@endif"
                                                    placeholder="Enter State/Province" type="text"
                                                    class="form-control">
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="City" class="">City</label>
                                                <input name="CstSiteCity" id="City"
                                                    value="@if(isset($editdata->id)){{$editdata->CstSiteCity}} @else{{(old('CstSiteCity'))}}@endif"
                                                    placeholder="Enter City" type="text" class="form-control">
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="PostalCode" class="">Postal Code</label>
                                                <input name="CstSitePostalCode" id="PinCode"
                                                    value="@if(isset($editdata->id)){{$editdata->CstSitePostalCode}}@else{{(old('CstSitePostalCode'))}}@endif"
                                                    placeholder="Enter Postal Code" type="text"
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-12" style="margin-top: 20px">
                                            <div class="row">
                                                <div class="col-sm-3">
                                                    <div class="position-relative form-group">
                                                        <div class="position-relative form-check"><input
                                                                name="CstSiteAvailability[]" id="exampleCheck20"
                                                                type="checkbox" class="form-check-input"
                                                                @if(isset($editdata->id))
                                                            <?php  if(json_encode(in_array("Forklift",$s_a))=='true') {  ?>
                                                            checked <?php }  ?>

                                                            @endif
                                                            value="Forklift"><label for="exampleCheck20"
                                                                class="form-check-label">Forklift</label></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="position-relative form-group">
                                                        <div class="position-relative form-check"><input
                                                                name="CstSiteAvailability[]" id="exampleCheck21"
                                                                type="checkbox" class="form-check-input"
                                                                @if(isset($editdata->id))
                                                            <?php  if(json_encode(in_array("TelePorter On Site",$s_a))=='true') {  ?>
                                                            checked <?php }  ?>

                                                            @endif
                                                            value="TelePorter On Site"><label for="exampleCheck21"
                                                                class="form-check-label">TelePorter On Site</label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="position-relative form-group">
                                                        <div class="position-relative form-check"><input
                                                                name="CstSiteAvailability[]" id="exampleCheck23"
                                                                type="checkbox" class="form-check-input"
                                                                @if(isset($editdata->id))
                                                            <?php  if(json_encode(in_array("40 Foot Access",$s_a))=='true') {  ?>
                                                            checked <?php }  ?>
                                                            @endif
                                                            value="40 Foot Access"><label for="exampleCheck23"
                                                                class="form-check-label">40 Foot Access</label></div>
                                                    </div>
                                                </div>
                                                <div class="col-sm-3">
                                                    <div class="position-relative form-group">
                                                        <div class="position-relative form-check"><input
                                                                name="CstSiteAvailability[]" id="exampleCheck24"
                                                                type="checkbox" class="form-check-input"
                                                                @if(isset($editdata->id))
                                                            <?php  if(json_encode(in_array("Crane",$s_a))=='true') {  ?>
                                                            checked <?php }  ?>
                                                            @endif
                                                            value="Crane"><label for="exampleCheck24"
                                                                class="form-check-label">Crane</label></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                    <button name="company" type="button" class="btn btn-primary pull-right"
                                        id="SiteAddressSubmit">Save & Next </button>
                                </div>
                            </div>
                        </div> -->

                        <div class="tab-pane fade" id="tab5" role="tabpanel" aria-labelledby="tab5">
                            <div class="main-card mb-3 card">
                                <div class="card-header">
                                    <h5 class="card-title" style="margin-top: 10px">Company's Main Contact</h5>
                                </div>
                                    <div>
                                        <a style="float: right; margin-right: 10px; margin-top: -45px" href="javascript:void(0);" id="add-customer-detail" class="btn-shadow btn btn-success">
                                            <i class="fa fa-plus"></i>
                                        </a>
                                    </div>

                                <div class="card-body" id="customer-details-section">
                                @if(isset($ContractorContactDetails) && (!empty($ContractorContactDetails)))
                                    @php
                                        $Count = 1;
                                    @endphp
                                    @foreach($ContractorContactDetails as $ContactKey => $ContactVal)
                                    <div class="form-row" id="customer-details-{{$Count}}">
                                        @if($Count > 1)
                                            <div class="col-md-12">
                                                <div class="card-header">
                                                    <h5 class="card-title" style="margin-top: 10px">Company's Other Contact</h5>
                                                </div>
                                                <div>
                                                    <a style="float: right; margin-right: 10px; margin-top: -45px" href="javascript:void(0);" onclick="RemoveCustomerSection({{$Count}});" class="btn-shadow btn btn-danger">
                                                        <i class="fa fa-remove"></i>
                                                    </a>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="col-md-6">
                                            <input type="hidden" name="count[]" value="{{$Count}}">
                                            <div class="position-relative form-group"><label for="First Name"
                                                    class="">First Name<span class="text-danger">*</span></label>
                                                <input name="Id[]" value="{{$ContactVal->id}}"
                                                    type="hidden"
                                                    class="form-control">
                                                <input name="FirstName[]"
                                                    value="{{$ContactVal->FirstName}}"
                                                    required placeholder="Enter First Name" type="text"
                                                    class="form-control" onkeypress="keyprs(this.value)">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="Last Name" class="">Last Name<span class="text-danger">*</span></label>
                                                <input name="LastName[]"
                                                    value="{{$ContactVal->LastName}}"
                                                    required placeholder="Enter Last Name" type="text"
                                                    class="form-control" onkeypress="keyprs(this.value)">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="ContactJobTitle" class="">Job Title<span class="text-danger">*</span></label>
                                                <input type="text" required name="ContactJobTitle[]" id="ContactJobTitle-1"
                                                class="form-control" placeholder="Enter Job Title" value="{{$ContactVal->ContactJobtitle}}" onkeypress="keyprs(this.value)">
                                                {{-- <select required name="ContactJobTitle[]" id="ContactJobTitle-{{$Count}}"
                                                    class="form-control">
                                                    <option value="">Select JobTitle</option>
                                                    <option value="Payment Contact"
                                                        @if($ContactVal->ContactJobtitle == 'Payment Contact')
                                                        {{'selected'}}
                                                        @endif >Payment Contact</option>
                                                    <option value="Foreman Contact"
                                                        @if($ContactVal->ContactJobtitle == 'Foreman Contact')
                                                        {{'selected'}}
                                                        @endif >Foreman Contact</option>
                                                    <option value="Quantity Surveyor"
                                                        @if($ContactVal->ContactJobTitle == 'Quantity Surveyor')
                                                        {{'selected'}}
                                                        @endif >Quantity Surveyor</option>
                                                    <option value="Project Manager"
                                                        @if($ContactVal->ContactJobTitle == 'Project Manager')
                                                        {{'selected'}}
                                                        @endif >Project Manager</option>
                                                </select> --}}
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="ContactEmail" class="">E-Mail<span class="text-danger">*</span></label>
                                                <input name="ContactEmail[]"
                                                    value="{{$ContactVal->ContactEmail}}"
                                                    required placeholder="Enter E-Mail" id="useremail-{{$Count}}"
                                                    type="email" class="form-control useremail">
                                                @if(session()->has('error'))
                                                <p style="color:red; margin-left: 1px; font-weight: bold;">
                                                    {{session()->get('error')}}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="ContactPhone" class="">Contractor Phone<span class="text-danger">*</span></label>
                                                <input name="ContactPhone[]"
                                                    value="{{$ContactVal->ContactPhone}}"
                                                    required placeholder="Enter Contact Phone" type="number"
                                                    class="form-control" onkeydown="if(event.key==='.'){event.preventDefault();}">
                                            </div>
                                        </div>

                                    </div>
                                    @php
                                        $Count++;
                                    @endphp
                                    @endforeach
                                @else
                                    <div class="form-row" id="customer-details-1">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="First Name"
                                                    class="">First Name<span class="text-danger">*</span></label>
                                                <input name="Id[]" value="0"
                                                    type="hidden"
                                                    class="form-control">
                                                <input name="FirstName[]"
                                                    required placeholder="Enter First Name" type="text"
                                                    class="form-control">
                                            </div>
                                            <input type="hidden" name="count[]" value="1">
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="Last Name" class="">Last Name<span class="text-danger">*</span></label>
                                                <input name="LastName[]"
                                                    required placeholder="Enter Last Name" type="text"
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="ContactJobTitle" class="">Job Title<span class="text-danger">*</span></label>
                                                <input type="text" required name="ContactJobTitle[]" id="ContactJobTitle-1"
                                                class="form-control" placeholder="Enter Job Title">

                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="ContactEmail" class="">E-Mail<span class="text-danger">*</span></label>
                                                <input name="ContactEmail[]"
                                                    required placeholder="Enter E-Mail" id="useremail"
                                                    type="email" class="form-control useremail">
                                                <!-- @if(session()->has('error'))
                                                <p style="color:red; margin-left: 1px; font-weight: bold;">
                                                    {{session()->get('error')}}</p>
                                                @endif -->
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="ContactPhone" class="">Contractor Phone<span class="text-danger">*</span></label>
                                                <input name="ContactPhone[]"
                                                    required placeholder="Enter Contact Phone" type="number"
                                                    class="form-control" onkeydown="if(event.key==='.'){event.preventDefault();}">
                                            </div>
                                        </div>



                                    </div>
                                @endif

                                </div>
                                <div class="d-block text-right card-footer">
                                    <button type="submit" id="click_btn" class="btn-wide btn btn-success"
                                        style="margin-right: 20px" onclick="submit_form()">
                                        @if(isset($editdata->id))
                                        Update Now
                                        @else
                                        Submit Now
                                        @endif
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>

                </form>

            </div>
        </div>

    </div>

</div>
@endsection

@section('js')


<script type="text/javascript">
$("#click_btn").click(function() {
    localStorage.clear();

})
</script>



@if(!isset($editdata->id))
<script type="text/javascript">
$(document).ready(function() {
    if (localStorage.getItem('CstCompanyName')) {
        $("input[name=CstCompanyName]").val(localStorage.getItem('CstCompanyName'));
    }
    if (localStorage.getItem('CstCompanyWebsite')) {
        $("input[name=CstCompanyWebsite]").val(localStorage.getItem('CstCompanyWebsite'));
    }
    if (localStorage.getItem('CstCompanyPhone')) {
        $("input[name=CstCompanyPhone]").val(localStorage.getItem('CstCompanyPhone'));
    }
    if (localStorage.getItem('CstCompanyEmail')) {
        $("input[name=CstCompanyEmail]").val(localStorage.getItem('CstCompanyEmail'));
    }
    if (localStorage.getItem('CstCompanyVatNumber')) {
        $("input[name=CstCompanyVatNumber]").val(localStorage.getItem('CstCompanyVatNumber'));
    }
    if (localStorage.getItem('CstCompanyMoreInfoo')) {
        $("#CstMoreInfo").append(localStorage.getItem('CstCompanyMoreInfoo'));
    }
    if (localStorage.getItem('CstCompanyPhoto')) {
        $("input[name=CstCompanyPhoto]").val(localStorage.getItem('CstCompanyPhoto'));
    }
    if (localStorage.getItem('CstCompanyAddressLine1')) {
        $("input[name=CstCompanyAddressLine1]").val(localStorage.getItem('CstCompanyAddressLine1'));
    }
    if (localStorage.getItem('CstCompanyPostalCode')) {
        $("input[name=CstCompanyPostalCode]").val(localStorage.getItem('CstCompanyPostalCode'));
    }
    if (localStorage.getItem('CstCompanyCountry')) {
        $("input[name=CstCompanyCountry]").val(localStorage.getItem('CstCompanyCountry'));
    }
    if (localStorage.getItem('CstCompanyState')) {
        $("input[name=CstCompanyState]").val(localStorage.getItem('CstCompanyState'));
    }
    if (localStorage.getItem('CstCompanyCity')) {
        $("input[name=CstCompanyCity]").val(localStorage.getItem('CstCompanyCity'));
    }

    if (localStorage.getItem('CstDeliveryPaymentType')) {
        $('input[value="' + localStorage.getItem('CstDeliveryPaymentType') + '"]').attr('checked', true)
    }
    if (localStorage.getItem('CstDeliveryDeliveryType')) {
        $('input[value="' + localStorage.getItem('CstDeliveryDeliveryType') + '"]').attr('checked', true)
    }
    if (localStorage.getItem('CstDeliverySupplyType')) {
        $('input[value="' + localStorage.getItem('CstDeliverySupplyType') + '"]').attr('checked', true)
    }
    if (localStorage.getItem('CstDeliveryFromTime')) {
        $('input[name="CstDeliveryFromTime"]').val(localStorage.getItem('CstDeliveryFromTime'))
    }
    if (localStorage.getItem('CstDeliveryToTime')) {
        $('input[name="CstDeliveryToTime"]').val(localStorage.getItem('CstDeliveryToTime'))
    }

    var cst_certficate = JSON.parse(localStorage.getItem('CstCertification'));
    $('input[name="CstCertification[]"]').each(function(i, item) {

        if ($.inArray(item.value, cst_certficate) != -1) {
            $('input[value="' + item.value + '"]').attr('checked', true)
        }
    })

    var cst_delivery = JSON.parse(localStorage.getItem('CstDeliveryDay'));
    $('input[name="CstDeliveryDay[]"]').each(function(i, item) {
        if ($.inArray(item.value, cst_delivery) != -1) {
            $('input[value="' + item.value + '"]').attr('checked', true)
        }
    })


    var cst_site_availability = JSON.parse(localStorage.getItem('CstSiteAvailability'))



    $('input[name="CstSiteAvailability[]"]').each(function(i, item) {
        if ($.inArray(item.value, cst_site_availability) != -1) {
            $('input[value="' + item.value + '"]').attr('checked', true);
        }
    })

    if (localStorage.getItem('CstSiteAddressLine1')) {
        $('input[name="CstSiteAddressLine1"]').val(localStorage.getItem('CstSiteAddressLine1'))
    }
    if (localStorage.getItem('CstSiteCountry')) {
        $('input[name="CstSiteCountry"]').val(localStorage.getItem('CstSiteCountry'))
    }
    if (localStorage.getItem('CstSiteCity')) {
        $('input[name="CstSiteCity"]').val(localStorage.getItem('CstSiteCity'))
    }
    if (localStorage.getItem('CstSiteState')) {
        $('input[name="CstSiteState"]').val(localStorage.getItem('CstSiteState'))
    }
    if (localStorage.getItem('CstSitePostalCode')) {
        $('input[name="CstSitePostalCode"]').val(localStorage.getItem('CstSitePostalCode'))
    }

})
</script>

@endif

<script type="text/javascript">
    if($('#UserId').val()){
        $('#link-tab2').removeClass('disable_tab');
        $('#link-tab3').removeClass('disable_tab');
        $('#link-tab5').removeClass('disable_tab');
    }
    $("input").on('keydown', function (e) {
        if (this.value.length === 0 && e.which === 32) e.preventDefault();
    });
    $("#companyDetails").click(function() {
        var CompanyName = $("input[name=CstCompanyName]").val();
        var CompanyWebsite = $("input[name=CstCompanyWebsite]").val();
        var CompanyPhone = $("input[name=CstCompanyPhone]").val();
        var CompanyEmail = $("input[name=CstCompanyEmail]").val();
        var CompanyVatNumber = $("input[name=CstCompanyVatNumber]").val();
        var CompanyMoreInfo = $("#CstMoreInfo").val();
        var CompanyLogo = $("input[name=CstCompanyPhoto]").val();
        if (CompanyName == '' || CompanyPhone == '' || CompanyEmail == '') {
            $("#tab1").addClass('active show');
            $("#link-tab1").addClass('active show');
            $('#link-tab2').addClass('disable_tab');
            $('#link-tab3').addClass('disable_tab');
            $('#link-tab5').addClass('disable_tab');
            $("#click_btn").click();
        } else {
            localStorage.setItem('CstCompanyName', CompanyName);
            localStorage.setItem('CstCompanyWebsite', CompanyWebsite);
            localStorage.setItem('CstCompanyPhone', CompanyPhone);
            localStorage.setItem('CstCompanyEmail', CompanyEmail);
            localStorage.setItem('CstCompanyVatNumber', CompanyVatNumber);
            localStorage.setItem('CstCompanyMoreInfoo', CompanyMoreInfo);
            localStorage.setItem('CstCompanyLogo', CompanyLogo);

            $("#tab1").removeClass('active show');
            $("#tab2").addClass('active show');
            $("#link-tab1").removeClass('active show');
            $("#link-tab2").addClass('active show');
            // $("#link-tab3").addClass('active show');
            $("#link-tab2").removeClass('disable_tab');
        }
    })



    $("#OfficeAddressSubmit").click(function() {
        var CstCompanyAddressLine1 = $("input[name=CstCompanyAddressLine1]").val();
        var CstCompanyCountry = $("input[name=CstCompanyCountry]").val();
        var CstCompanyState = $("input[name=CstCompanyState]").val();
        var CstCompanyCity = $("input[name=CstCompanyCity]").val();
        var CstCompanyPostalCode = $("input[name=CstCompanyPostalCode]").val();

        if (CstCompanyAddressLine1 == '' || CstCompanyCountry == '' || CstCompanyCity ==
            '' || CstCompanyPostalCode == '' || CstCompanyState == '') {
                // $("#tab3").addClass('disable_tab');
                $("#tab2").addClass('active show');
                $("#link-tab2").addClass('active show');
                $('#link-tab3').addClass('disable_tab');
                $('#link-tab5').addClass('disable_tab');
                $("#click_btn").click();
        } else {
            localStorage.setItem('CstCompanyAddressLine1', CstCompanyAddressLine1);
            localStorage.setItem('CstCompanyCountry', CstCompanyCountry);
            localStorage.setItem('CstCompanyState', CstCompanyState);
            localStorage.setItem('CstCompanyCity', CstCompanyCity);
            localStorage.setItem('CstCompanyPostalCode', CstCompanyPostalCode);

            $("#tab2").removeClass('active show');
            $("#tab3").addClass('active show');
            $("#link-tab3").addClass('active show');
            $("#link-tab2").removeClass('active show');
            $("#link-tab3").removeClass('disable_tab');
            $("#link-tab5").removeClass('disable_tab');
        }
    })



    $("#AdditionalInformationSubmit").click(function() {
        // $('input[name="name_of_your_radiobutton"]:checked').val();
        var CstDeliveryPaymentType = $("input[name=CstDeliveryPaymentType]:checked").val();
        var CstDeliveryDay = $('input[name*="CstDeliveryDay"]').val();
        var CstDeliveryFromTime = $("input[name=CstDeliveryFromTime]").val();
        var CstDeliveryToTime = $("input[name=CstDeliveryToTime]").val();
        var CstDeliveryDeliveryType = $("input[name=CstDeliveryDeliveryType]:checked").val();
        var CstDeliverySupplyType = $("input[name=CstDeliverySupplyType]:checked").val();
        var CstCertification = $('input[name*="CstCertification"]').val();

        if (CstDeliveryFromTime == '' || CstDeliveryToTime == '') {
            $('#link-tab5').click();
        } else {
            var delivery_day_values = [];
            $('input[name="CstDeliveryDay[]"]').each(function(i, item) {
                if (item.checked == true) {
                    delivery_day_values.push(item.value);
                }
            });

            localStorage.setItem('CstDeliveryDay', JSON.stringify(delivery_day_values))

            var cst_certification_value = [];
            $('input[name="CstCertification[]"]').each(function(i, item) {
                if (item.checked == true) {
                    cst_certification_value.push(item.value);
                }
            })

            localStorage.setItem('CstCertification', JSON.stringify(cst_certification_value));


            localStorage.setItem('CstDeliveryPaymentType', CstDeliveryPaymentType);
            localStorage.setItem('CstDeliveryFromTime', CstDeliveryFromTime);
            localStorage.setItem('CstDeliveryToTime', CstDeliveryToTime);
            localStorage.setItem('CstDeliveryDeliveryType', CstDeliveryDeliveryType);
            localStorage.setItem('CstDeliverySupplyType', CstDeliverySupplyType);


            $("#tab5").addClass('active show');
            $("#tab3").removeClass('active show');
            $("#link-tab5").addClass('active show');
            $("#link-tab3").removeClass('active show');
        }
    })

    //
        // $("#SiteAddressSubmit").click(function() {
        //     var CstSiteAddressLine1 = $("input[name=CstSiteAddressLine1]").val();
        //     var CstSiteCountry = $("input[name=CstSiteCountry]").val();
        //     var CstSiteCity = $("input[name=CstSiteCity]").val();
        //     var CstSiteState = $("input[name=CstSiteState]").val();
        //     var CstSitePostalCode = $("input[name=CstSitePostalCode]").val();
        //     if (CstSiteAddressLine1 == '' || CstSiteCity == '' || CstSiteCountry == '' || CstSiteState == '' ||
        //         CstSitePostalCode == '') {
        //         // $("#submit").click();
        //     } else {
        //         localStorage.setItem('CstSiteAddressLine1', CstSiteAddressLine1);
        //         localStorage.setItem('CstSiteCountry', CstSiteCountry);
        //         localStorage.setItem('CstSiteCity', CstSiteCity);
        //         localStorage.setItem('CstSiteState', CstSiteState);
        //         localStorage.setItem('CstSitePostalCode', CstSitePostalCode);

        //         var cst_site_availability_values = [];
        //         $('input[name="CstSiteAvailability[]"]').each(function(i, item) {
        //             if (item.checked == true) {
        //                 cst_site_availability_values.push(item.value);
        //             }
        //         })

        //         localStorage.setItem('CstSiteAvailability', JSON.stringify(cst_site_availability_values));

        //         $("#tab5").addClass('active show');
        //         $("#tab4").removeClass('active show');
        //         $("#link-tab5").addClass('active show');
        //         $("#link-tab4").removeClass('active show');
        //     }
        // })





    function submit_form() {
        var CompanyName = $("input[name=CstCompanyName]").val();
        var CompanyWebsite = $("input[name=CstCompanyWebsite]").val();
        var CompanyPhone = $("input[name=CstCompanyPhone]").val();
        var CompanyEmail = $("input[name=CstCompanyEmail]").val();
        var CompanyVatNumber = $("input[name=CstCompanyVatNumber]").val();
        var CompanyMoreInfo = $("input[name=CstMoreInfo]").val();
        var CompanyLogo = $("input[name=CstCompanyPhoto]").val();

        var CstCompanyAddressLine1 = $("input[name=CstCompanyAddressLine1]").val();
        var CstCompanyCountry = $("input[name=CstCompanyCountry]").val();
        var CstCompanyState = $("input[name=CstCompanyState]").val();
        var CstCompanyCity = $("input[name=CstCompanyCity]").val();
        var CstCompanyPostalCode = $("input[name=CstCompanyPostalCode]").val();

        var CstDeliveryFromTime = $("input[name=CstDeliveryFromTime]").val();
        var CstDeliveryToTime = $("input[name=CstDeliveryToTime]").val();

        var CstSiteAddressLine1 = $("input[name=CstSiteAddressLine1]").val();
        var CstSiteCountry = $("input[name=CstSiteCountry]").val();
        var CstSiteState = $("input[name=CstCompanyState]").val();
        var CstSiteCity = $("input[name=CstSiteCity]").val();
        var CstSitePostalCode = $("input[name=CstSitePostalCode]").val();

        if (CompanyName == '' || CompanyPhone == '' || CompanyEmail == '') {
            $("#tab1").addClass('active show');
            $("#tab5").removeClass('active show');
            $("#tab2").removeClass('active show');
            $("#tab3").removeClass('active show');
            $("#link-tab1").addClass('active show');
            $("#link-tab5").removeClass('active show');
            $("#link-tab2").removeClass('active show');
            $("#link-tab3").removeClass('active show');
            setTimeout(function() {
                $("#submit").click();
            }, 10)
        } else if (CstCompanyAddressLine1 == '' || CstCompanyCountry == '' || CstCompanyState == '' || CstCompanyCity ==
            '' || CstCompanyPostalCode == '') {
            $("#tab2").addClass('active show');
            $("#tab5").removeClass('active show');
            $("#tab1").removeClass('active show');
            $("#tab3").removeClass('active show');
            $("#link-tab2").addClass('active show');
            $("#link-tab5").removeClass('active show');
            $("#link-tab1").removeClass('active show');
            $("#link-tab3").removeClass('active show');
            setTimeout(function() {
                $("#submit").click();
            }, 10)
        }
        //else if (CstDeliveryFromTime == '' || CstDeliveryToTime == '') {
          //  $("#tab3").addClass('active show');
           // $("#tab5").removeClass('active show');
           // $("#link-tab3").addClass('active show');
           // $("#link-tab5").removeClass('active show');
            //setTimeout(function() {
              //  $("#submit").click();
            //}, 10)
        //}
        // else if (CstSiteAddressLine1 == '' || CstSiteCity == '' || CstSiteCountry == '' || CstSiteState == '' ||
        //     CstSitePostalCode == '') {
        //     $("#tab4").addClass('active show');
        //     $("#tab5").removeClass('active show');
        //     $("#link-tab4").addClass('active show');
        //     $("#link-tab5").removeClass('active show');
        //     setTimeout(function() {
        //         // $("#submit").click();
        //     }, 10)
        // }
        else {
            $("#submit").click();
            localStorage.clear();
        }
    }

    $("#select-all-days").click(function () {
        $('.select-days:checkbox').not(this).prop('checked', this.checked);
    });


    @if(isset($Count))
        let CustomerCounter = {{$Count}};
    @else
        let CustomerCounter = 1;
    @endif

    function RemoveCustomerSection(id){
        $("#customer-details-"+ id).remove();
    }

    $(document).on("click","#add-customer-detail",function(){

        CustomerCounter = CustomerCounter + 1;

        var CustomerDetails = `<div class="form-row" id="customer-details-`+ CustomerCounter +`">
                                    <input type="hidden" name="count[]" value="`+ CustomerCounter +`">
                                    <div class="col-md-12">
                                        <div class="card-header">
                                            <h5 class="card-title" style="margin-top: 10px">Company's Other Contact</h5>
                                        </div>
                                        <div>
                                            <a style="float: right; margin-right: 10px; margin-top: -45px" href="javascript:void(0);" onclick="RemoveCustomerSection(`+ CustomerCounter +`);" class="btn-shadow btn btn-danger">
                                                <i class="fa fa-remove"></i>
                                            </a>
                                        </div>
                                    </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="First Name"
                                                    class="">First Name<span class="text-danger">*</span></label>
                                                <input name="Id[]" value="0"
                                                    type="hidden"
                                                    class="form-control">
                                                <input name="FirstName[]" id="fn"
                                                    required placeholder="Enter First Name" type="text"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="Last Name" class="">Last Name<span class="text-danger">*</span></label>
                                                <input name="LastName[]"
                                                    required placeholder="Enter Last Name" type="text"
                                                    class="form-control">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="ContactJobTitle" class="">Job Title<span class="text-danger">*</span></label>
                                                <input type="text" required name="ContactJobTitle[]" id="ContactJobTitle-1"
                                                class="form-control" placeholder="Enter Job Title">

                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="ContactEmail" class="">E-Mail<span class="text-danger">*</span></label>
                                                <input name="ContactEmail[]"
                                                    required placeholder="Enter E-Mail" id="useremail-` + CustomerCounter + `"
                                                    type="email" class="form-control useremail">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="ContactPhone" class="">Customer Phone<span class="text-danger">*</span></label>
                                                <input name="ContactPhone[]"
                                                    required placeholder="Enter Contact Phone" type="number"
                                                    class="form-control">
                                            </div>
                                        </div>

                                    </div>`;
        $("#customer-details-section").append(CustomerDetails);
    });



    $(document).ready(function(){
        $("input[name=CstCompanyEmail]").on("keyup",function(e){
            var value = $("#useremail").val();
            var UserId = $("#usersId").val();
            $.ajax({
                url:  $("#useremail_check").html(),
                method:"POST",
                data:{"_token": "{{ csrf_token() }}", value:value},
                dataType:"Json",
                success: function(result){
                    if(result.status == "ok"){
                        $("#message").empty();
                        $('#companyDetails').prop('disabled', false);
                        $('#click_btn').prop('disabled', false);
                        $("#useremail").removeClass("exist_email");
                    }else{
                        $("#message").empty().text(result.message);
                        $('#companyDetails').attr("disabled", true);
                        $('#click_btn').attr("disabled", true);
                        $("#useremail").addClass("exist_email");
                        if(UserId == result.UserId){
                            if(result.UserEmail == value){
                                $("#message").empty();
                                $('#companyDetails').prop('disabled', false);
                                $('#click_btn').prop('disabled', false);
                                $("#useremail").removeClass("exist_email");
                            }
                        }
                    }
                }
            });
        });
        localStorage.clear();
        $(window).keydown(function(event){
            if(event.keyCode == 13) {
              event.preventDefault();
              return false;
            }
        });
    });


    function keyprs(value){
        console.log(value)
        if(value.indexOf('  ') >= 0){
            alert("You can't add wide space");
        }
    }
</script>
@endsection
