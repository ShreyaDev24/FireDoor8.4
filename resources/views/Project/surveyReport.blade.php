
<html lang="en">

<head>
    <title>Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.1/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600&display=swap" rel="stylesheet">

    <link href="{{url('/')}}/css/main.css" rel="stylesheet">

    <style>
        body {
            padding: 0;
            margin: 0;
            font-family: 'Poppins', sans-serif !important;
        }

        .page_holder {
    max-width: 900px;
    margin: 0 auto;
}


        .main_heading {
    font-size: 45px;
    color: #3c3c3c;
    padding-top: 0px;
    font-family: 'Poppins', sans-serif !important;
}

.logo img {
    width: 150px;
}

.survey-title {
    font-size: 20px;
    color: #3c3c3c;
    text-transform: uppercase;
    margin-bottom: 25px;
    padding: 40px 0px 15px;
    font-family: 'Poppins', sans-serif !important;
}

.sub_title {
    font-size: 13px;
    font-family: 'Poppins', sans-serif !important;
}

.survey_main .form-group {
    width: 100%;
    clear: both;
}


.survey_label {
    font-size: 15px;
    float: left;
    margin-right: 30px;
    width: 220px;
    font-family: 'Poppins', sans-serif !important;
}

.surveyRP_value {
    float: left;
    font-size: 14px;
    color: #000;
    text-transform: capitalize;
    width: 400px;
    margin-top: -15px;
    padding-top: 15px;
    vertical-align: text-top;
}

.survey_fitureimg_col{
    width: 300px;
    height: 150px;
    float: left;
    margin-right: 10px;
}

.survey_fitureimg{
    width: 300px;
    height: 150px;
}

.survey_pdf_table thead th {
    font-size: 13px;
}

.sign_img {
    width: 135px;
}
.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
}

.table-bordered {
    border: 1px solid #e9ecef;
}

.table-bordered th{
    font-size:13px;
    margin: 0px;
}


.table-bordered th, .table-bordered td {
    border: 1px solid #e9ecef;
    padding: 0.55rem;
    font-size:13px;
    text-align:center;
    margin: 0px;
}
    </style>
</head>

<body>
    <main class="page_holder survey_main">
        <div class="survey_tobbar">
            <div class="container">
                    <div style="float: left;">
                        <h1 class="main_heading">SURVEY REPORT</h1>
                    </div>
                    <div style="float: right;">
                        <div class="logo"><img src="{{ $company->ComplogoBase64 }}" /></div>
                    </div>
            </div>
        </div>

        <div style="clear: both;">
            <form id="signupForm" enctype="multipart/form-data" method="post" action="{{route('user/store')}}" novalidate="novalidate">
                <div class="main-card mb-3 p-0">
                    <div class="card-body">
                        <div class="tab-content mt-3">
                            <h5 class="survey-title">PROPERTY INFORMATION</h5>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">Project Name:</label>
                                            <p class="surveyRP_value">{{ $Project->ProjectName??'' }}</p>

                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">Address:</label>
                                            <p class="surveyRP_value">{{ $Project->AddressLine1??''}}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">City:</label>
                                            <p class="surveyRP_value">{{ $Project->City??''}}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">Country:</label>
                                            <p class="surveyRP_value">{{ $Project->Country??''}}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">Post Code:</label>
                                            <p class="surveyRP_value">{{ $Project->PostalCode??''}}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-content mt-5">
                            <h5 class="survey-title">SURVEY INFORMATION</h5>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">Survey Date:</label>
                                            <p class="surveyRP_value">{{ date('d M Y',strtotime($survey->surveyDate??'')) }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">Surveyor Name:</label>
                                            <p class="surveyRP_value">{{ ($surveyUser->FirstName??'').' '.($surveyUser->LastName??'')}}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">Email:</label>
                                            <p class="surveyRP_value">{{ $surveyUser->UserEmail??''}}</p>
                                        </div>
                                    </div>

                                @php

                                @endphp
                                <div style="clear: both;">
                                    <div class="survey_fitureimg_col">
                                        <img class="survey_fitureimg" src="{{ $Project->projectImageBase64??'' }}" />
                                        {{--  <img class="survey_fitureimg" src="{{ url('/') }}//uploads/Project/{{ $survey->ProjectImage }}" />  --}}
                                    </div>
                                </div>

                                </div>
                            </div>
                        </div>

                        <div class="tab-content mt-3" style="clear: both;">
                            <h5 class="survey-title">CHANGE REQUEST</h5>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">Number of Changes:</label>
                                            <p class="surveyRP_value">{{ $survey_changerequest_count }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-content mt-5">
                            <h5 class="survey-title">INTRODUCTION</h5>
                            <p class="sub_title">This report provides a record of the Fire Door Survey Report at: {{ $Project->AddressLine1??'' }} for {{ $company->CompanyName }}. It was inspected by {{ ($surveyUser->FirstName??'').' '.($surveyUser->LastName??'') }} on the {{ date('d M Y',strtotime($survey->surveyDate??'')) }}</p>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">Company Name:</label>
                                            <p class="surveyRP_value">{{ $company->CompanyName }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">Instructions:</label>
                                            <p class="surveyRP_value">{{ $Project->instruction??'' }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">The Property:</label>
                                            <p class="surveyRP_value">{{ $Project->AddressLine1??'' }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">Surveyour:</label>
                                            <p class="surveyRP_value">{{ ($surveyUser->FirstName??'').' '.($surveyUser->LastName??'') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">Survey Date:</label>
                                            <p class="surveyRP_value">{{ date('d M Y',strtotime($survey->surveyDate??'')) }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            {{--  <label class="survey_label">Scope and Purpose of the Survey Report:</label>  --}}
                                            <label class="survey_label">Notes:</label>
                                            <p class="surveyRP_value">{{ $survey->notes??'' }}</p>
                                        </div>
                                    </div>

                                    {{--  <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">Limitations of the Survey</label>
                                            <p class="surveyRP_value"></p>
                                        </div>
                                    </div>  --}}
                                </div>
                            </div>
                        </div>

                        <div class="tab-content mt-5">
                            <h5 class="survey-title">SURVEY OVERVIEW</h5>
                            <div class="card-body">
                                <div class="form-row">
                                    {{--  <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">Survey Description:</label>
                                            <p class="surveyRP_value"></p>
                                        </div>
                                    </div>  --}}

                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">Building Type:</label>
                                            <p class="surveyRP_value">{{ $Project->BuildingType??'' }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">Floors:</label>
                                            <p class="surveyRP_value">{{ $project_building_details }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">Number of Doorsets:</label>
                                            <p class="surveyRP_value">{{ $door_set }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">Site Contact:</label>
                                            <p class="surveyRP_value">{{ ($site_contact->FirstName??'').' '.($site_contact->LastName??'') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">Site Contact Number:</label>
                                            <p class="surveyRP_value">{{ $contact->Phone??'' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="tab-content mt-5">
                            <h5 class="survey-title">FULL SURVEY</h5>
                            <p class="sub_title">The following is a summary of the Structural Openings surveyed within the scope of the survey. This shows the list of doors after being surveyed.</p>
                            <div class="card-body">
                                <div class="form-row">
                                    <table class="table table-bordered survey_pdf_table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Door Type</th>
                                                <th scope="col">Location</th>
                                                <th scope="col">Door Number</th>
                                                <th scope="col">Fire Rating</th>
                                                <th scope="col">S.O. Height</th>
                                                <th scope="col">S.O. Width</th>
                                                <th scope="col">S.O. Depth</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($door_details))
                                                @foreach ($door_details as $val)
                                                <tr>
                                                    <td>{{ $val->DoorType }}</td>
                                                    <td>{{ $val->location }}</td>
                                                    <td>{{ $val->doorNumber }}</td>
                                                    <td>{{ $val->FireRating }}</td>
                                                    <td>{{ $val->SOHeight }}</td>
                                                    <td>{{ $val->SOWidth }}</td>
                                                    <td>{{ $val->SOWallThick }}</td>
                                                </tr>
                                                @endforeach
                                            @endif

                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>

                        <div class="tab-content mt-5">
                            <h5 class="survey-title">CHANGE REQUEST</h5>
                            <p class="sub_title">The following is a list of all the doors with changes requested after the survey has been completed.</p>
                            <div class="card-body">
                                <div class="form-row">
                                    <table class="table table-bordered survey_pdf_table">
                                        <thead>
                                            <tr>
                                                <th scope="col">Door Type</th>
                                                <th scope="col">Door Number</th>
                                                <th scope="col">S.O. Height old</th>
                                                <th scope="col">S.O. Width old</th>
                                                <th scope="col">S.O. Depth old</th>
                                                <th scope="col">S.O. Height</th>
                                                <th scope="col">S.O. Width</th>
                                                <th scope="col">S.O. Depth</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @if(!empty($survey_changerequest))
                                                @foreach ($survey_changerequest as $val)
                                                    <tr>
                                                        <td>{{ $val->DoorType }}</td>
                                                        <td>{{ $val->doorNumber }}</td>
                                                        <td>{{ $val->oldSOHeight }}</td>
                                                        <td>{{ $val->oldSOWidth }}</td>
                                                        <td>{{ $val->oldSODepth }}</td>
                                                        <td>{{ $val->SOHeight }}</td>
                                                        <td>{{ $val->SOWidth }}</td>
                                                        <td>{{ $val->SODepth }}</td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>

                                </div>
                            </div>
                        </div>


                        <div class="tab-content mt-5">
                            <h5 class="survey-title">DECLARATION</h5>
                            <p class="sub_title">This report is for the use of the party to whom it is addressed and should be used within the context of instruction under which it has been prepared.</p>
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">Surveyed By:</label>
                                            <p class="surveyRP_value">{{ ($surveyUser->FirstName??'').' '.($surveyUser->LastName??'') }}</p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">Signature:</label>
                                            <p class="surveyRP_value"><img class="sign_img" src="{{ $survey->signatureimage??'' }}"></p>
                                        </div>
                                    </div>

                                    <div class="col-md-12">
                                        <div class="position-relative form-group">
                                            <label class="survey_label">Date:</label>
                                            <!-- <p class="surveyRP_value">{{ date('d M Y',strtotime($survey->updated_at??'')) }}</p> -->
                                            <p class="surveyRP_value">@if(isset($survey->updated_at)) {{ date('d M Y',strtotime($survey->updated_at)) }} @endif</p>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>


                    </div>
                </div>
            </form>
        </div>
    </main>


   </body>

</html>
