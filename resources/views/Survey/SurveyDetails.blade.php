@extends('layouts.Master')

@section('main_section')
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins&display=swap" rel="stylesheet">

    <style>
        body {
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
                                @if (!empty($data->UserImage))
                                    <img class="company-logo" src="{{ $data->UserImage }}"
                                        alt="Survey User Image" />
                                @else
                                    <img class="company-logo" src="{{ url('/') }}/CompanyLogo/defoult-logo.png"
                                        alt="Survey User Image" />
                                @endif
                            </div>
                            <div class="col-sm-8 company-details">
                                <div class="company-name ">
                                    <h4>{{ $data->FirstName }} {{ $data->LastName }}</h4>
                                </div>
                                <div class="company-number"><img src="{{ url('/') }}/images/phone.png"
                                        alt="number">{{ $data->UserPhone }}</div>
                                <div class="company-number"><img src="{{ url('/') }}/images/mail.png"
                                        alt="email">{{ $data->UserEmail }}</div>
                                <hr />
                                <div class="row">
                                    <div class="col-sm-12">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div class="company-details-title">Survey User Details</div>
                                                <table class="table profile_info_table">
                                                    <tbody>
                                                        <tr>
                                                            <td style="width: 160px;"><b>Survey User name</b></td>
                                                            <td>{{ $data->FirstName }} {{ $data->LastName }}</td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Mail</b></td>
                                                            <td><a href="#">{{ $data->UserEmail }} </a></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Phone</b></td>
                                                            <td><a href="#">{{ $data->UserPhone }}</a></td>
                                                        </tr>
                                                        <tr>
                                                            <td><b>Job Title</b></td>
                                                            <td>{{ $data->UserJobtitle }}</td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <br />
                                {{-- <div class="profile_map mt-4">
                                    <iframe frameborder="0" width="100%" height="250px;" scrolling="no" marginheight="0"
                                        marginwidth="0" src="https://maps.google.it/maps?q=&output=embed" title=""
                                        aria-label=""></iframe>
                                </div> --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="CstLat" value="{{ $data->CstLat }}">
        <input type="hidden" id="CstLong" value="{{ $data->CstLong }}">
    @endsection

    @section('js')
        <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
        <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB0IWtnotDvo-ciYGFLFU8RXWkC496NFcU&callback=initMap&libraries=&v=weekly"
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
