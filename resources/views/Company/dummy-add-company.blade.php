@extends("layouts.Master")

@section("main_section")
@if(session()->has('error'))
<style type="text/css">
    #useremail{
        border-color: red
    }
</style>
@endif
<div class="app-main__outer">
<div class="app-main__inner">
           
      <form id="signupForm" enctype="multipart/form-data" method="post" action="{{route('company/store')}}" novalidate="novalidate">
    <div class="tab-content">
      <div class="main-card mb-3 card">
           <input type="hidden" name="_token" value="{{ csrf_token() }}">
           @if(isset($editdata->id))
           <input type="hidden" name="update" value="{{ $editdata->id }}">
           @endif
                        <div class="card-header">
                            <h5 class="card-title" style="margin-top: 10px">Add Company Details</h5>
                        </div>
                        <div>
                            <a style="float: right; margin-right: 10px; margin-top: -45px" href="{{route('company/list')}}" class="btn-shadow  btn btn-info">
                                Company List
                            </a>
                        </div>
                    <div class="card-body">
                        <div class="tab-content">
                            <div class="card-body">
                                <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group"><label for="Company Name" class="">Company Name</label>
                            <input name="CompanyName" required placeholder="Enter Company Name" value="@if(isset($editdata->id)) {{$editdata->CompanyName}}@else{{old('CompanyName')}}@endif" type="text" class="form-control"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="Website url" class="">Website</label>
                                    <input name="CompanyWebsite" value="@if(isset($editdata->id)) {{$editdata->CompanyWebsite}}@else{{old('CompanyWebsite')}}@endif" required placeholder="Enter Company Website" type="url" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="Company Email" class="">Company Email</label>
                                    <input name="CompanyEmail" value="@if(isset($editdata->id)) {{$editdata->CompanyEmail}} @else{{old('CompanyEmail')}}@endif" required placeholder="Enter Company Email" type="email" class="form-control">
                                </div>
                            </div>

                                <div class="col-md-6">
                                <div class="position-relative form-group"><label for="Company Phone" class="">Company Phone</label>
                                <input name="CompanyPhone" value="@if(isset($editdata->id)){{$editdata->CompanyPhone}}@else{{old('CompanyPhone')}}@endif" required placeholder="Enter Company Phone Number" type="number" class="form-control"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="Company VAT" class="">Company VAT</label>
                                    <input name="CompanyVatNumber" value="@if(isset($editdata->id)) {{$editdata->CompanyVatNumber}} @else{{old('CompanyVatNumber')}}@endif" required placeholder="Enter Company Company VAT" type="text" class="form-control">
                                </div>
                            </div>

                              <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="Company Logo" class="">Company Logo</label>
                                    <input name="CompanyLogo" required type="file" class="form-control">
                                </div>
                            </div>

                     <div class="col-md-12">
                        <div class="position-relative form-group">
                            <label for="Company Moreinfo"  class="">Company Moreinfo</label>
                        <textarea name="CompanyMoreInfo" required placeholder="Enter Company Moreinfo" class="form-control">@if(isset($editdata->id)){{$editdata->CompanyMoreInfo}}@else{{old('CompanyMoreInfo')}}@endif</textarea>
                        </div>
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



      <div class="main-card mb-3 card">
                        <div class="card-header">
                            <h5 class="card-title" style="margin-top: 10px">Add Company Head Office</h5>
                        </div>
                        <div class="card-body">
                            <div class="tab-content">
                        <div class="card-body">
                           <div class="form-row">
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="Address Line 1" class="">Address Line 1</label>
                                <input name="CompanyAddressLine1" id="searchTextField" value="@if(isset($editdata->id)){{$editdata->CompanyAddressLine1}}@else{{old('CompanyAddressLine1')}}@endif" required placeholder="Address Line 1" type="text" class="form-control"></div>
                                <input type="hidden" id="city2" name="city2" />
                            <input type="hidden" name="Lat" id="CstLat">
                            <input type="hidden" name="Long" id="CstLong">
                            </div>
                         <!--    <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="Address Line 2" class="">Address Line 2</label>
                                    <input name="CompanyAddressLine2" value="@if(isset($editdata->id)){{$editdata->CompanyAddressLine2}}@else{{old('CompanyAddressLine2')}}@endif" required placeholder="Address Line 2" type="text" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="Addres Line 3" class="">Address Line 3</label>
                                    <input name="CompanyAddressLine3" value="@if(isset($editdata->id)){{$editdata->CompanyAddressLine3}}@else{{old('CompanyAddressLine3')}}@endif" required placeholder="Address Line 3" type="text" class="form-control">
                                </div>
                            </div> -->

                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="Country" class="">Country</label>
                                <input name="CompanyCountry" value="@if(isset($editdata->id)){{$editdata->CompanyCountry}}@else{{old('CompanyCountry')}}@endif" required placeholder="Enter Country" type="text" class="form-control"></div>
                            </div>


                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                <label for="State" class="">State/Province</label>
                                <input name="CompanyState" value="@if(isset($editdata->id)){{$editdata->CompanyState}} @else{{old('CompanyState')}}@endif" required placeholder="Enter State/Province" type="text" class="form-control">
                                </div>
                            </div>


                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="City" class="">City</label>
                                <input name="CompanyCity" value="@if(isset($editdata->id)){{$editdata->CompanyCity}}@else{{old('CompanyCity')}}@endif" required placeholder="Enter City" type="text" class="form-control"></div>
                            </div>


                            <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="PostalCode" class="">Postal Code</label>
                                <input name="CompanyPostalCode" value="@if(isset($editdata->id)){{$editdata->CompanyPostalCode}}@else{{old('CompanyPostalCode')}}@endif" required placeholder="Enter Postal Code" type="number" class="form-control">
                            </div>
                            </div>
                            
                        </div>
                </div>
            </div>
                               
                        </div>
                      
        </div>

                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="tab-content">
                                  <div class="card-header">
                            <h5 class="card-title" style="margin-top: 10px">Add Admin Details</h5>
                        </div>
                        <div class="card-body">
                         <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group"><label for="First Name" class="">First Name</label>
                                <input name="FirstName" value="@if(isset($editdata->id)) {{$editdata->FirstName}}@else{{old('FirstName')}}@endif" required placeholder="Enter First Name" type="text" class="form-control"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="Last Name" class="">Last Name</label>
                                    <input name="LastName" value="@if(isset($editdata->id)){{$editdata->LastName}}@else{{old('LastName')}}@endif" required placeholder="Enter Last Name" type="text" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="Email" class="">Email/Username</label>
                                    <input name="UserEmail" value="@if(isset($editdata->id)){{$editdata->UserEmail}}@else{{old('UserEmail')}}@endif" required placeholder="Enter Email/Username" id="useremail" type="email" class="form-control">
                                    @if($errors->has('UserEmail'))
                                    <p style="color:red; margin-left: 1px; font-weight: bold;">{{$errors->first('UserEmail')}}</p>
                                    @endif
                                </div>
                            </div>

                                <div class="col-md-6">
                                <div class="position-relative form-group">
                                <label for="UserPhone" class="">User Phone</label>
                                <input name="UserPhone" value="@if(isset($editdata->id)){{$editdata->UserPhone}}@else{{old('UserPhone')}}@endif" required placeholder="Enter User Phone" type="text" class="form-control"></div>
                            </div>
                        @if(!isset($editdata->id))
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="Pasword" class="">Password</label>
                                    <input name="password"  id="password" required placeholder="endsectionr Password" type="password" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="ConfirmPassword" class="">Confirm Password</label>
                                    <input name="confirm_password" id="confirm_password" required placeholder="Confirm Password" type="password" class="form-control">
                                </div>
                            </div>
                        @endif
                        </div>
                </div>
            </div>
                               
                        </div>
                        <div class="d-block text-right card-footer">
                            <button type="submit" id="submit" class="btn-wide btn btn-success" style="margin-right: 20px">
                                @if(isset($editdata->id))
                                Update Now
                                @else
                                Submit Now
                                @endif
                            </button>
                        </div>
                      
            </div>
        </div>
      </form>


    </div>

</div>

@endsection

@section('js')
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB0IWtnotDvo-ciYGFLFU8RXWkC496NFcU&libraries=places"></script>
<script>
        function initialize() {
          var input = document.getElementById('searchTextField');
          var autocomplete = new google.maps.places.Autocomplete(input);
            google.maps.event.addListener(autocomplete, 'place_changed', function () {
                var place = autocomplete.getPlace();
                document.getElementById('city2').value = place.name;
                document.getElementById('CstLat').value = place.geometry.location.lat();
                document.getElementById('CstLong').value = place.geometry.location.lng();
            });
        }
        google.maps.event.addDomListener(window, 'load', initialize);
    </script>
@endsection