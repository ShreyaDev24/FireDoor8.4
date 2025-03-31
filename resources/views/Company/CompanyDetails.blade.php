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
        <div class="tabs-animation statistics">
        <div class="row">
            @if(Auth::user()->UserType==1)
                <div class="col-md-3">
                    <div class="box-dashboard">
                        <i class="fa fa-users fa-fw"></i>
                        <a href="{{route('user/list')}}" class="info">
                            <h3>{{ $user_count }}</h3>
                            <p>User</p>
                        </a>
                    </div>
                </div>
                </div>
            @endif
        </div>
            <div class="app-inner-layout chat-layout">
                <div class="custom_card mb-5 pb-5">
                    <div class="container company-background pt-5">
                        <div class="row">
                            <div class="col-sm-4">
                                @if(!empty($data->CompanyPhoto))
                                <img class="company-logo" src="{{url('/')}}/CompanyLogo/{{$data->CompanyPhoto}}"alt="Company logo" />
                                @else
                                <img class="company-logo" src="{{url('/')}}/CompanyLogo/defoult-logo.png" alt="Company logo"/>
                                @endif
                                <!-- <img class="company-logo" src="{{url('/')}}/images/companylogo.png" alt="millenium logo" /> -->
                            </div>
                            <div class="col-sm-8 company-details">
                                <a href="{{url('company/edit-profile/'.$data->userId)}}" class="btn-shadow btn btn-info float-right">
                                    <i class="fa fa-pencil"></i> Edit Profile
                                </a>
                                <div class="company-name "><h4>{{@$data->CompanyName}}</h4></div>
                                <div class="company-number"><img src="{{url('/')}}/images/phone.png" alt="number">{{$data->CompanyPhone}}</div>
                                <div class="company-number"><img src="{{url('/')}}/images/mail.png" alt="email">{{$data->CompanyEmail}}</div>
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
                                                        <td>{{$data->CompanyName}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Mail</b></td>
                                                        <td><a href="#">{{$data->CompanyEmail}} </a></td>
                                                    </tr>

                                                    <tr>
                                                        <td><b>Website:</b></td>
                                                        <td><a href="#">{{$data->CompanyWebsite}}</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>VAT</b></td>
                                                        <td>{{$data->CompanyVatNumber}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>More info</b></td>
                                                        <td>{{$data->CompanyMoreInfo}} </td>
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
                                                        <td>{{$data->CompanyAddressLine1}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>City</b></td>
                                                        <td><a href="#">{{$data->CompanyCity}} </a></td>
                                                    </tr>

                                                    <tr>
                                                        <td><b>State</b></td>
                                                        <td><a href="#">{{$data->CompanyState}}</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Country</b></td>
                                                        <td>{{$data->CompanyCountry}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Postal Code</b></td>
                                                        <td>{{$data->CompanyPostalCode}} </td>
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
                                        <div class="company-details-title">Company Admin Details</div>
                                        <table class="table profile_info_table">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 160px;"><b>Name:</b></td>
                                                        <td>{{$data->FirstName}} {{$data->LastName}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>User name</b></td>
                                                        <td>{{$data->UserEmail}}</td>
                                                    </tr>

                                                    <tr>
                                                        <td><b>Job title</b></td>
                                                        <td>{{$data->UserJobtitle}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="profile_map mt-4">
                                    <iframe
                                        frameborder="0"
                                        width="100%"
                                        height="250px;"
                                        scrolling="no"
                                        marginheight="0"
                                        marginwidth="0" src="https://maps.google.it/maps?q={{$data->CompanyAddressLine1}}&output=embed"
                                        title="{{$data->CompanyAddressLine1}}"
                                        aria-label="{{$data->CompanyAddressLine1}}"></iframe>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



<input type="hidden" id="CstLat" value="{{$data->Lat}}">
<input type="hidden" id="CstLong" value="{{$data->Long}}">
@endsection

@section('js')
<script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
@if(session()->has('updated'))
<script type="text/javascript">
swal(
    'Success',
    'Company updated Succesfully!',
    'success'
)


</script>
@endif
<script
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB0IWtnotDvo-ciYGFLFU8RXWkC496NFcU&callback=initMap&libraries=&v=weekly"
    defer></script>

<script>
function initMap() {

    const CstLat = parseFloat(document.getElementById("Lat").value);
    const CstLong = parseFloat(document.getElementById("Long").value);
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
