@extends("layouts.Master")

@section("main_section")
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">
<!--
<style>
body {
    font-family: 'Poppins', sans-serif;
}

.profile_container,
.info,
.back {
    margin: 60px 100px 0px;
    max-width: 900px;
    display: flex;
    overflow-x: hidden;
}



.profile_img-LG {
    height: 250px;
    width: 250px;
    border-radius: 50%;
    object-fit: cover;
    object-position: 50% 50%;
    background-position: 40% 50%;
}



.description {
    margin-bottom: 30px;
    margin-top: 0px;
}

.profile_img_section {
    margin-right: 50px;
}

.profile_desc_section {
    display: flex;
    flex-direction: column;

    margin-left: 50px;
}

.interests_item {
    display: inline-block;
    padding: 5px 15px;
    margin-right: 7.5px;
    margin-bottom: 10px;
    line-height: 35px;
    border-radius: 50px;
    border: 1px solid #e2e2e2;
    color: #000;
}

.info {
    margin-top: -20px;
    margin-left: 100px;
}

.link_img_wrapper {
    width: 40px;
    height: 40px;
    background-color: #f2f2f2;
    border-radius: 10px;
    position: relative;
}

.link_img {
    height: 20px;
    width: 20px;
    position: absolute;
    right: 0;
    left: 0;
    top: 0;
    bottom: 0;
    margin: auto auto;
}

.edit_button {
    text-decoration: none;
    color: #fff;
    background: #F44336;
    display: inline-block;
    text-align: center;
    font-size: 18px;
    max-width: 170px;
    width: 100%;
    padding: 10px 0px;
    border-radius: 6px;
    margin-top: 12px;
}


.edit_button:hover {
    color: #fff;
    text-decoration: none;
}

.profile_name {
    font-size: 30px;
    color: #000;
}

.interests {
    padding: 20px 0px;
}

.interests_item b i {
    font-size: 25px;
    vertical-align: middle;
    margin-right: 10px;
}


.profile_details_holder {
    max-width: 1100px;
    width: 100%;
    margin: 0 auto;
}

.details_box {
    background: #fff;
    border-radius: 16px;
    padding: 20px 30px !important;
    border: 1px solid #ececec;
}




@media screen and (max-width: 1000px) {

    .profile_container,
    .info,
    .back {
        margin: 60px 33px 0px;
    }

    .profile_container {
        flex-direction: column;
    }

    .profile_img_section {
        margin: 0 auto;
    }

    .profile_img-LG {
        width: 300px;
        height: 300px;
        border-radius: 100%;
    }



    .profile_desc_section {
        margin-left: 0px;
        margin-bottom: 10px;
        margin-top: -40px;
    }

    .info {
        margin-top: 10px;
        margin-left: 33px;
    }
}
</style> -->


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
                            @if(!empty($data->UserImage))
                            <img class="company-logo" src="{{url('/')}}/UserImage/{{$data->UserImage}}"alt="Company logo" />
                            @else
                            <img class="company-logo" src="{{url('/')}}/CompanyLogo/defoult-logo.png" alt="Company logo"/>
                            @endif
                        </div>
                        <div class="col-sm-8 company-details">
                            @if(Auth::user()->UserType == '3')
                            <a href="{{url('user/edit/'.$data->id)}}" class="btn-shadow btn btn-info float-right">
                                <i class="fa fa-pencil"></i> Edit Profile
                            </a>
                            @endif
                            <div class="company-name "><h4>{{$data->FirstName}} {{$data->LastName}}</h4></div>
                            <div class="company-number"><img src="{{url('/')}}/images/phone.png" alt="number">{{$data->UserPhone}}</div>
                            <div class="company-number"><img src="{{url('/')}}/images/mail.png" alt="email">{{$data->UserEmail}}</div>
                            <hr />
                            <div class="row">
                                <div class="col-sm-12">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="company-details-title">Company Details</div>
                                            <table class="table profile_info_table">
                                                <tbody>
                                                    <tr>
                                                        <td style="width: 160px;"><b>Company name</b></td>
                                                        <td>{{$data->FirstName}} {{$data->LastName}}</td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Mail</b></td>
                                                        <td><a href="#">{{$data->UserEmail}} </a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Phone</b></td>
                                                        <td><a href="#">{{$data->UserPhone}}</a></td>
                                                    </tr>
                                                    <tr>
                                                        <td><b>Job Title</b></td>
                                                        <td>{{$data->UserJobtitle}}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <br />
                            <div class="profile_map mt-4">
                                <iframe
                                    frameborder="0"
                                    width="100%"
                                    height="250px;"
                                    scrolling="no"
                                    marginheight="0"
                                    marginwidth="0" src="https://maps.google.it/maps?q=&output=embed"
                                    title=""
                                    aria-label=""></iframe>
                            </div>
                        </div>
                    </div>
                </div>







                <!-- <section class="profile_container">
                    <div class="profile_img_section">
                        @if(!empty($data->CstCompanyPhoto))
                        <img class="profile_img-LG" src="{{url('/')}}/CompanyLogo/{{$data->CstCompanyPhoto}}" />
                        @else
                        <img class="profile_img-LG" src="{{url('/')}}/CompanyLogo/defoult-logo.png" />
                        @endif
                    </div>

                    <div class="profile_desc_section mt-4">
                        <h2 class="profile_name mb-2"><b>{{$data->FirstName}} {{$data->LastName}}</b></h2>
                        @if(Auth::user()->UserType == '2')
                        <a href="{{url("ChargePassword/".$data->id)}}" class="edit_button btn-xs">Reset Password</a>

                        @endif


                        {{--@if(Auth::user()->UserType == '2')--}}
                        {{--<a href="{{url('user/edit/'.$data->id)}}" class="edit_button" >Edit Customer</a>--}}
                        {{--@endif--}}




                        <div class="interests">
                            <span class="interests_item"><b><i style="color: #4CAF50;" class="fa fa-phone"
                                        aria-hidden="true"></i> {{$data->UserPhone}}</b></span>
                            <span class="interests_item"><b><i style="color: #F44336;" class="fa fa-envelope"
                                        aria-hidden="true"></i> {{$data->UserEmail}}</b></span>
                        </div>
                    </div>

                </section> -->
            </div>

            <!-- <section>
                <div class="profile_details_holder">
                    <div class="row">
                        <div class="col-md-12 my-4">
                            <a class=" text-dark">
                                <div class="px-3 py-3 details_box">
                                    <h5 class="text-capitalize my-2 mb-4" style="font-weight: 600">Company Details</h5>
                                    <div class="row">
                                        <div class="col-lg-4 col-md-6 col-sm-6">
                                            <div class="profile">
                                                <p><i class="fa fa-building   icon_style"
                                                        aria-hidden="true"></i><b>Company Name</b>
                                                    <strong>{{$data->FirstName}} {{$data->LastName}} </strong>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="profile">
                                                <p><i class="fa fa-envelope   icon_style"
                                                        aria-hidden="true"></i><b>Company Email</b>
                                                    <strong>{{$data->UserEmail}} </strong>
                                                </p>
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="profile">
                                                <p><i class="fa fa-phone-square icon_style"
                                                        aria-hidden="true"></i><b>Phone</b>
                                                    <strong>{{$data->UserPhone}} </strong>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="col-lg-4 col-md-6 col-sm-12">
                                            <div class="profile">
                                                <p><i class="fa fa-id-card icon_style" aria-hidden="true"></i><b>Job Title</b>
                                                    <strong>{{$data->UserJobTitle}}</strong>
                                                </p>
                                            </div>
                                        </div>


                                    </div>
                                </div>
                            </a>
                        </div>


                        <div class="col-md-12 my-4">
                            <a class=" text-dark">
                                <div class="px-3 py-3 details_box">
                                    <iframe frameborder="0" width="100%" height="299px;" scrolling="no" marginheight="0"
                                        marginwidth="0"
                                        src="https://maps.google.com/maps?q=chouksey%20engineering%20collage%20&amp;t=m&amp;z=11&amp;output=embed&amp;iwloc=near"
                                        title="chouksey engineering collage "
                                        aria-label="chouksey engineering collage "></iframe>
                                </div>
                            </a>
                        </div>


                    </div>
                </div>
            </section> -->

        </div>
    </div>
</div>
<input type="hidden" id="CstLat" value="{{$data->CstLat}}">
<input type="hidden" id="CstLong" value="{{$data->CstLong}}">
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
