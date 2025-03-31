@extends("layouts.Master")
@section("main_section")
@if(session()->has('error'))
<style type="text/css">
    #useremail {
        border-color: red
    }
</style>
@endif
<style>
    .disable_tab {
        pointer-events: none;
        filter: grayscale(100%);
        background: #e7e7e7 !important;
    }

    .exist_email {
        background-image: url('../../assets/images/danger_cross.png') !important;
        background-repeat: no-repeat;
        background-size: 12px !important;
        background-position: center right calc(2rem / 4) !important;
        border-color: #ff4141 !important;
        '
box-shadow: 0 0 0 0.2rem rgb(217 37 80 / 25%) !important;
    }
</style>
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
        @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="fas fa-skull-crossbones"></i> Alert!</h5>
            {{ session()->get('error') }}
        </div>
        @endif
        <div class="custom_card">
            <div class="tab-card-body">
                <ul class="nav nav-pills mb-3 tablist" id="pills-tab" role="tablist">
                    <li class="nav-item tab-item">
                        <a class="nav-link show active companytab" id="pills-company-tab" data-toggle="pill" href="#company" role="tab" aria-controls="company" aria-selected="true">ARCHITECT DETAILS</a>
                    </li>
                    <li class="nav-item tab-item">
                        <a class="nav-link show disable_tab" id="pills-office-tab" data-toggle="pill" href="#office" role="tab" aria-controls="office" aria-selected="false">Address Details</a>
                    </li>
                    <li class="nav-item tab-item">
                        <a class="nav-link show disable_tab" id="pills-admin-tab" data-toggle="pill" href="#admin" role="tab" aria-controls="admin" aria-selected="false">ADMIN</a>
                    </li>
                </ul>
                <form id="signupForm" enctype="multipart/form-data" method="post" action="{{route('Architect/store')}}" autocomplete="off">
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade active show" id="company" role="tabpanel" aria-labelledby="company">
                            <div class="main-card mb-3">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                @if(isset($editdata->id))
                                <input type="hidden" name="update" id="UserId" value="{{ $editdata->UserId }}">
                                @endif
                                <div hidden id="useremail_check">{{route('company/useremail_check')}}</div>
                                <div class="pt-0">
                                    <div class="tab-content">
                                        <div class="">
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group"><label for="Company Name" class="">Architect Company Name<span class="text-danger">*</span></label>
                                                        <input name="CompanyName" required placeholder="Enter Company Name" value="@if(isset($editdata->id)){{$editdata->ArcCompanyName}}@else{{old('ArcCompanyName')}}@endif" type="text" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="Website url" class="">Website</label>
                                                        <input name="CompanyWebsite" value="@if(isset($editdata->id)){{$editdata->ArcCompanyWebsite}}@else{{old('ArcCompanyWebsite')}}@endif" placeholder="Enter Company Website" type="text" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="Company Email" class="">Company Email<span class="text-danger">*</span></label>
                                                        <input name="CompanyEmail" value="@if(isset($editdata->id)){{$editdata->ArcCompanyEmail}}@else{{old('ArcCompanyEmail')}}@endif" required placeholder="Enter Company Email" type="email" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group"><label for="Company Phone" class="">Company Phone<span class="text-danger">*</span></label>
                                                        <input name="CompanyPhone" value="@if(isset($editdata->id)){{$editdata->ArcCompanyPhone}}@else{{old('ArcCompanyPhone')}}@endif" required placeholder="Enter Company Phone Number" type="number" class="form-control" onkeydown="if(event.key==='.'){event.preventDefault();}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="Company Logo" class="">Company Logo<span class="text-danger">*</span></label>
                                                        <input name="CompanyLogo" @if(!isset($editdata->id)) required
                                                        @endif type="file" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        @if(isset($editdata->ArcCompanylogo))
                                                        <img src="{{url('/')}}/CompanyLogo/@if(isset($editdata->id)){{$editdata->ArcCompanylogo}}@endif" width="100px">
                                                        <input type="hidden" id="imageurl" value="{{$editdata->ArcCompanylogo}}">
                                                        @endif
                                                    </div>
                                                </div>

                                                <div class="col-md-12">
                                                    <div class="position-relative form-group">
                                                        <label for="Company Moreinfo" class="">More Information</label>
                                                        <textarea name="CompanyMoreInfo" id="CompanyMoreInfo" placeholder="Enter More Information" class="form-control" rows="4">@if(isset($editdata->id)){{$editdata->ArcMoreInfo}}@else{{old('ArcMoreInfo')}}@endif</textarea>
                                                    </div>
                                                </div>
                                                <button style="display: none;" id="submitcompany"></button>
                                                <div class="col-sm-12">
                                                    <button type="button" name="company" id="companyDetails" class="btn btn-primary pull-right">Save & Next </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="tab-pane fade  " id="office" role="tabpanel" aria-labelledby="office">
                            <div class="main-card mb-3">
                                <!-- <div class="card-header">
                                    <h5 class="card-title" style="margin-top: 10px">Add Company Head Office</h5>
                                </div> -->
                                <div class="pt-0">
                                    <div class="tab-content">
                                        <div class="">
                                            <div class="form-row">
                                                <div class="col-md-12">
                                                    <div class="position-relative form-group">
                                                        <label for="Address Line 1" class="">Address Line 1<span class="text-danger">*</span></label>
                                                        <input name="CompanyAddressLine1" id="searchTextField" value="@if(isset($editdata->id)){{$editdata->ArcCompanyAddressLine1}}@else{{old('ArcCompanyAddressLine1')}}@endif" required placeholder="Address Line 1" type="text" autocomplete="off" class="form-control">
                                                    </div>
                                                    <input type="hidden" id="city2" name="city2" />
                                                    <input type="hidden" name="Lat" id="CstLat">
                                                    <input type="hidden" name="Long" id="CstLong">
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="Country" class="">Country<span class="text-danger">*</span></label>
                                                        <input name="CompanyCountry" id="Country" value="@if(isset($editdata->id)){{$editdata->ArcCompanyCountry}}@else{{old('ArcCompanyCountry')}}@endif" required placeholder="Enter Country" type="text" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="State" class="">State<span class="text-danger">*</span></label>
                                                        <input name="CompanyState" id="State" value="@if(isset($editdata->id)){{$editdata->ArcCompanyState}}@else{{old('CompanyState')}}@endif" placeholder="Enter State" type="text" required class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="City" class="">City<span class="text-danger">*</span></label>
                                                        <input name="CompanyCity" id="City" value="@if(isset($editdata->id)){{$editdata->ArcCompanyCity}}@else{{old('ArcCompanyCity')}}@endif" required placeholder="Enter City" type="text" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="PostalCode" class="">Postal Code/Eircode<span class="text-danger">*</span></label>
                                                        <input name="CompanyPostalCode" id="PinCode" value="@if(isset($editdata->id)){{$editdata->ArcCompanyPostalCode}}@else{{old('ArcCompanyPostalCode')}}@endif" required placeholder="Enter Postal Code/Eircode" type="text" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-sm-12">
                                                    <button type="button" class="btn btn-primary pull-right" id="officeDetails">Save & Next </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="admin" role="tabpanel" aria-labelledby="admin">
                            <div class="main-card mb-3">
                                <div class="">
                                    <div class="tab-content">
                                        <!-- <div class="card-header">
                                    <h5 class="card-title" style="margin-top: 10px">Add Admin Details</h5>
                                </div> -->
                                        <div class="pt-0">
                                            <div class="form-row">
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group"><label for="First Name" class="">First Name<span class="text-danger">*</span></label>
                                                        <input name="FirstName" value="@if(isset($editdata->id)){{$editdata->FirstName}}@else{{old('FirstName')}}@endif" required placeholder="Enter First Name" type="text" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="Last Name" class="">Last Name<span class="text-danger">*</span></label>
                                                        <input name="LastName" value="@if(isset($editdata->id)){{$editdata->LastName}}@else{{old('LastName')}}@endif" required placeholder="Enter Last Name" type="text" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="Email" class="">Email<span class="text-danger">*</span></label>
                                                        <input name="UserEmail" value="@if(isset($editdata->id)){{$editdata->UserEmail}}@else{{old('UserEmail')}}@endif" required placeholder="Enter Email" id="useremail" type="email" class="form-control">
                                                        @if($errors->has('UserEmail'))
                                                        <p style="color:red; margin-left: 1px; font-weight: bold;">

                                                            {{$errors->first('UserEmail')}}
                                                        </p>
                                                        @endif
                                                        <p id="message" style="width: inherit;font-size: 80%;color: #d92550;position: absolute;right: 30px;top: 38px;"></p>
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="UserPhone" class="">Phone<span class="text-danger">*</span></label>
                                                        <input name="UserPhone" value="@if(isset($editdata->id)){{$editdata->UserPhone}}@else{{old('UserPhone')}}@endif" required placeholder="Enter Phone" type="number" class="form-control" onkeydown="if(event.key==='.'){event.preventDefault();}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="UserPhone" class="">Job Title<span class="text-danger">*</span></label>
                                                        <input name="UserJobtitle" value="@if(isset($editdata->id)){{$editdata->UserJobtitle}}@else{{old('UserJobtitle')}}@endif" required placeholder="Enter Job Title" type="text" class="form-control">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="position-relative form-group">
                                                        <label for="UserPhone" class="">Password</label>
                                                        <input name="UserPassword"  placeholder="Enter password" type="password" class="form-control">
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-block text-right card-footer">
                                    <button style="display: none;" id="submit"></button>
                                    <button type="button" onclick="submit_form()" class="btn-wide btn btn-success" style="margin-right: 20px">
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
    </div>

</div>

@endsection

@section('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/pnp-common/1.3.11/common.js" integrity="sha512-kLuD69UJ+Ninvrdjx8Ww4aIOlDnAZ86hJzkqhs09rReQc2UkVQVXCn0Y8ynhwdfgLE05WsCMVzhuV7pUwXC6gA==" crossorigin="anonymous"></script>
@if(!isset($editdata->id))
<script type="text/javascript">
    $(document).ready(function() {
        if (localStorage.getItem('CompanyName')) {
            $("input[name=CompanyName]").val(localStorage.getItem('CompanyName'));
        }
        if (localStorage.getItem('CompanyWebsite')) {
            $("input[name=CompanyWebsite]").val(localStorage.getItem('CompanyWebsite'));
        }
        if (localStorage.getItem('CompanyPhone')) {
            $("input[name=CompanyPhone]").val(localStorage.getItem('CompanyPhone'));
        }
        if (localStorage.getItem('CompanyEmail')) {
            $("input[name=CompanyEmail]").val(localStorage.getItem('CompanyEmail'));
        }
        if (localStorage.getItem('CompanyMoreInfo')) {
            $("#CompanyMoreInfo").append(localStorage.getItem('CompanyMoreInfo'));
        }
        if (localStorage.getItem('CompanyAddressLine1')) {
            $("input[name=CompanyAddressLine1]").val(localStorage.getItem('CompanyAddressLine1'));
        }
        if (localStorage.getItem('CompanyPostalCode')) {
            $("input[name=CompanyPostalCode]").val(localStorage.getItem('CompanyPostalCode'));
        }
        if (localStorage.getItem('CompanyCountry')) {
            $("input[name=CompanyCountry]").val(localStorage.getItem('CompanyCountry'));
        }
        if (localStorage.getItem('CompanyState')) {
            $("input[name=CompanyState]").val(localStorage.getItem('CompanyState'));
        }
        if (localStorage.getItem('CompanyCity')) {
            $("input[name=CompanyCity]").val(localStorage.getItem('CompanyCity'));
        }


    })
</script>
@endif
<script type="text/javascript">
    if ($('#UserId').val()) {
        $('#pills-office-tab').removeClass('disable_tab');
        $('#pills-admin-tab').removeClass('disable_tab');
    }
    $("input").on('keydown', function (e) {
        if (this.value.length === 0 && e.which === 32) e.preventDefault();
    });
    $("#companyDetails").click(function() {
        var CompanyName = $("input[name=CompanyName]").val();
        var CompanyWebsite = $("input[name=CompanyWebsite]").val();
        var CompanyPhone = $("input[name=CompanyPhone]").val();
        var CompanyEmail = $("input[name=CompanyEmail]").val();
        var CompanyLogo = $("input[name=CompanyLogo]").val();
        var CompanyMoreInfo = $("#CompanyMoreInfo   ").val();
        if ($('#UserId').val()) {
            CompanyLogo = $('#imageurl').val();
        }

        localStorage.setItem('CompanyName', CompanyName);
        localStorage.setItem('CompanyWebsite', CompanyWebsite);
        localStorage.setItem('CompanyPhone', CompanyPhone);
        localStorage.setItem('CompanyEmail', CompanyEmail);
        localStorage.setItem('CompanyLogo', CompanyLogo);
        localStorage.setItem('CompanyMoreInfo', CompanyMoreInfo);

        if (CompanyName == '' || CompanyPhone == '' || CompanyEmail == '' || CompanyLogo == '') {
            $("#company").addClass('active show');
            $("#pills-company-tab").addClass('active show');
            $("#pills-office-tab").addClass('disable_tab');
            $("#pills-admin-tab").addClass('disable_tab');
            $('#submit').click();
        } else {

            $("#company").removeClass('active show');
            $("#office").addClass('active show');
            $("#pills-office-tab").removeClass('disable_tab');
            $("#pills-company-tab").removeClass('active show');
            $("#pills-office-tab").addClass('active show');
            if ($('#UserId').val()) {
                $("#pills-admin-tab").removeClass('disable_tab');
            }
        }
    })
    $("#officeDetails").click(function() {
        var CompanyAddressLine1 = $("input[name=CompanyAddressLine1]").val();
        var CompanyCountry = $("input[name=CompanyCountry]").val();
        var CompanyState = $("input[name=CompanyState]").val();
        var CompanyCity = $("input[name=CompanyCity]").val();
        var CompanyPostalCode = $("input[name=CompanyPostalCode]").val();

        localStorage.setItem('CompanyAddressLine1', CompanyAddressLine1);
        localStorage.setItem('CompanyCountry', CompanyCountry);
        localStorage.setItem('CompanyState', CompanyState);
        localStorage.setItem('CompanyCity', CompanyCity);
        localStorage.setItem('CompanyPostalCode', CompanyPostalCode);

        if (CompanyAddressLine1 == '' || CompanyCountry == '' || CompanyState == '' || CompanyCity == '' ||
            CompanyPostalCode == '') {
            $("#office").addClass('active show');
            $("#pills-office-tab").addClass('active show');
            $("#pills-admin-tab").addClass('disable_tab');
            $("#submit").click();
        } else {

            $("#admin").addClass('active show');
            $("#pills-admin-tab").removeClass('disable_tab');
            $("#office").removeClass('active show');
            $("#pills-admin-tab").addClass('active show');
            $("#pills-office-tab").removeClass('active show');
        }

    })

    function submit_form() {
        var CompanyName = $("input[name=CompanyName]").val();
        var CompanyWebsite = $("input[name=CompanyWebsite]").val();
        var CompanyPhone = $("input[name=CompanyPhone]").val();
        var CompanyEmail = $("input[name=CompanyEmail]").val();
        var CompanyMoreInfo = $("input[name=CompanyMoreInfo]").val();
        var CompanyLogo = $("input[name=CompanyLogo]").val();

        var CompanyAddressLine1 = $("input[name=CompanyAddressLine1]").val();
        var CompanyCountry = $("input[name=CompanyCountry]").val();
        var CompanyState = $("input[name=CompanyState]").val();
        var CompanyCity = $("input[name=CompanyCity]").val();
        var CompanyPostalCode = $("input[name=CompanyPostalCode]").val();
        if ($('#UserId').val()) {
            CompanyLogo = $('#imageurl').val();
        }
        if (CompanyName == '' || CompanyPhone == '' || CompanyEmail == '' || CompanyLogo == '') {
            $("#company").addClass('active show');
            $("#admin").removeClass('active show');
            $("#pills-company-tab").addClass('active show');
            $("#pills-admin-tab").removeClass('active show');
            setTimeout(function() {
                localStorage.clear();
                $("#submitcompany").click();
            }, 1)
        } else if (CompanyAddressLine1 == '' || CompanyCountry == '' || CompanyState == '' || CompanyCity == '' ||
            CompanyPostalCode == '') {
            $("#office").addClass('active show');
            $("#admin").removeClass('active show');
            $("#pills-office-tab").addClass('active show');
            $("#pills-admin-tab").removeClass('active show');
            setTimeout(function() {
                localStorage.clear();
                $("#submitcompany").click();
            }, 10)
        } else {
            $("#submit").click();
            localStorage.clear();
        }
    }

    $(document).ready(function() {
        $("#useremail").on("keyup", function(e) {
            var value = $("#useremail").val();
            var UserId = $("#UserId").val();
            $.ajax({
                url: $("#useremail_check").html(),
                method: "POST",
                data: {
                    "_token": "{{ csrf_token() }}",
                    value: value
                },
                dataType: "Json",
                success: function(result) {
                    if (result.status == "ok") {
                        $("#message").empty();
                        $('#submit_btn').prop('disabled', false);
                        $('#submitcompany').prop('disabled', false);
                        $('#submit').prop('disabled', false);
                        $("#useremail").removeClass("exist_email");
                    } else {
                        $("#message").empty().text(result.message);
                        $('#submit_btn').attr("disabled", true);
                        $('#submitcompany').attr("disabled", true);
                        $('#submit').attr("disabled", true);
                        $("#useremail").addClass("exist_email");
                        if (UserId == result.UserId) {
                            if (result.UserEmail == value) {
                                $("#message").empty();
                                $('#submit_btn').prop('disabled', false);
                                $('#submitcompany').prop('disabled', false);
                                $('#submit').prop('disabled', false);
                                $("#useremail").removeClass("exist_email");
                            }
                        }

                    }
                }
            });
        });
    });
</script>
@if($errors->has('UserEmail'))
<script type="text/javascript">
    $("#admin").addClass('active show');
    $("#company").removeClass('active show');
    $("#pills-admin-tab").addClass('active show');
    $("#pills-company-tab").removeClass('active show');
</script>
@endif

@endsection
