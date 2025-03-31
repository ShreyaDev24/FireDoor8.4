@extends("layouts.Master")

@section("main_section")
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
<style>
    body{
        font-family: 'Poppins', sans-serif;
    }
    .company-details-title {
        color: #000;
        font-size: 18px;
        font-weight: 600;
        padding-bottom: 30px;
    }
    .company-name h4 {
        font-weight: 900;
        font-size: 25px;
        color: #000;
        margin-bottom: 30px;
    }
    .company-number {
        font-size: 20px;
        font-weight: 400;
        color: #000;
    }
    .company-logo {
        width: 100%;
    }
    .company-info-title ul li {
        list-style: none;
        margin-bottom: 16px;
        color: #a5a5a5;
        font-size: 13px;
    }
    .company-info-details ul {
        padding: 0;
        margin: 0;
    }
    .company-info-details ul li {
        list-style: none;
        margin-bottom: 16px;
        font-size: 13px;

        color: #000;
    }
    .company-info-title ul {
        padding: 0;
    }
    .profile_map {
        border: 1px solid #ececec;
        padding: 6px;
    }
    @media only screen and (max-width: 768px) {
        .company-number {
            font-size: 16px;
        }
    }
    @media only screen and (max-width: 582px) {
        .company-name h4 {
            margin-top: 40px;
        }
    }
</style>

<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="app-inner-layout chat-layout">
            <div class="custom_card mb-5 pb-5">
                <div class="container company-background pt-5">
                    <div class="row">
                        <div class="col-sm-4">
                            @if(!empty($data->CstCompanyPhoto))
                                @php
                                  $img = url('/').'/CompanyLogo/'.$data->CstCompanyPhoto;
                                @endphp
                                    <img class="company-logo" src="{{url('/')}}/CompanyLogo/{{$data->CstCompanyPhoto}}"
                                    />

                            @else
                            <img class="company-logo" src="{{url('CompanyLogo/default-image.jpg')}}">
                            @endif
                        </div>
                        <div class="col-sm-8 company-details">
                            <a href="{{url('contractor/edit/'.$data->id)}}" class="btn-shadow btn btn-info float-right">
                                <i class="fa fa-pencil"></i> Edit Profile
                            </a>
                            <div class="company-name">
                                <h4>{{$data->CstCompanyName}} {{$data->LastName}}</h4>
                            </div>
                            <div class="company-number"><img src="{{url('/')}}/images/phone.png"
                                    alt="number">{{$data->CstCompanyPhone}}</div>
                            <div class="company-number"><img src="{{url('/')}}/images/mail.png"
                                    alt="email">{{$data->CstCompanyEmail}}</div>
                            <hr />
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="company-details-title">Company Details</div>
                                            <table class="table profile_info_table">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 160px;"><b>Company name:</b></td>
                                                        <td>{{$data->CstCompanyName}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Mail</b></td>
                                                        <td><a href="#">{{$data->CstCompanyEmail}} </a></td>
                                                    </tr>

                                                    <tr>
                                                        <td><b>Website:</b></td>
                                                        <td><a href="#">{{$data->CstCompanyWebsite}}</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>VAT</b></td>
                                                        <td>{{$data->CstCompanyVatNumber}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>More info</b></td>
                                                        <td>{{$data->CstMoreInfo}} </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <div class="row">
                                <div class="col-sm-12">
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="company-details-title">Company Head Office</div>
                                            <table class="table profile_info_table">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 160px;"><b>Address:</b></td>
                                                        <td>{{$data->CstMoreInfo}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>City</b></td>
                                                        <td><a href="#">{{$data->CstCompanyCity}} </a></td>
                                                    </tr>

                                                    <tr>
                                                        <td><b>State</b></td>
                                                        <td><a href="#">{{$data->CstCompanyState}}</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Country</b></td>
                                                        <td>{{$data->CstCompanyCountry}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Postal Code</b></td>
                                                        <td>{{$data->CstCompanyPostalCode}} </td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <hr>
                                            <div class="company-details-title">Additional Information</div>
                                            <table class="table profile_info_table">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 200px;"><b>Cst Site DeliveryType</b></td>
                                                        <td>{{$data->CstDeliveryPaymentType}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Delivery Day</b></td>
                                                        <td>{{$data->CstDeliveryDay}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Delivery Time From - To</b></td>
                                                        <td>{{$data->CstDeliveryFromTime}} -
                                                            {{$data->CstDeliveryToTime}}</td>
                                                    </tr>

                                                    <tr>
                                                        <td><b>Cst DeliveryType</b></td>
                                                        <td>{{$data->CstDeliveryDeliveryType}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Certification</b></td>
                                                        <td>{{$data->CstCertification}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>


                        </div>
                    </div>
                </div>

            <div class="row">
            <div class="col-sm-4"></div>
                <div class="col-sm-8">
                                    <hr>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="company-details-title">Contact</div>
                                            <table class="table profile_info_table">
                                            <tbody>
                                                @foreach($customer_contact as $value)
                                                    <tr>
                                                        <td style="width: 160px;"><b>First Name:</b></td>
                                                        <td>{{$data->FirstName}}</td>
                                                        <td>{{$value->FirstName}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Last Name</b></td>
                                                        <td><a href="#">{{$data->LastName}} </a></td>
                                                        <td><a href="#">{{$value->LastName}} </a></td>
                                                    </tr>

                                                    <tr>
                                                        <td><b>Contact Email</b></td>
                                                        <td><a href="#">{{$data->ContactEmail}}</a></td>
                                                        <td><a href="#">{{$value->ContactEmail}}</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Contact Phone</b></td>
                                                        <td>{{$data->ContactPhone}}</td>
                                                        <td>{{$value->ContactPhone}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Contact Job title</b></td>
                                                        <td>{{$data->ContactJobtitle}} </td>
                                                        <td>{{$value->ContactJobtitle}} </td>
                                                    </tr>

                                                    <tr>
                                                        <td><b></b></td>
                                                        <td></td>
                                                    </tr>

                                                @endforeach
                                                </tbody>
                                            </table>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                <section>
                    <div class="profile_details_holder">
                        <div class="row">

                        </div>
                    </div>
                </section>

            </div>
        </div>
    </div>
    <input type="hidden" id="CstLat" value="{{$data->CstLat}}">
    <input type="hidden" id="CstLong" value="{{$data->CstLong}}">
    @endsection
    @section('js')
    @if(session()->has('success'))
        <script type="text/javascript">
                swal(
                    'Success',
                    'Main Contractor updated Successfully!',
                    'success'
                )
        </script>
    @endif
    @endsection
    @section('js')
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB0IWtnotDvo-ciYGFLFU8RXWkC496NFcU&callback=initMap&libraries=&v=weekly"
        defer></script>

    <script>
    function initMap() {

        const CstLat = parseFloat(document.getElementById("CstLat").value);
        const CstLong = parseFloat(document.getElementById("CstLong").value);
        const myLatLng = {
            lat: CstLat,
            lng: CstLong
        };
        const map = new google.maps.Map(document.getElementById("map"), {
            zoom: 15,
            center: myLatLng,
        });
        new google.maps.Marker({
            position: myLatLng,
            map,
            title: "Hello World!",
        });
    }
    </script>

    <script type="text/javascript">
    function editcustomer() {
        document.getElementById("editsubmit").click()
    }
    </script>
    @endsection

