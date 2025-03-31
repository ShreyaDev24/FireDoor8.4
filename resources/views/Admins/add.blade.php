@extends('layouts.Master')

@section('main_section')
    @if (session()->has('error'))
        <style type="text/css">
            #useremail {
                border-color: red
            }

        </style>
    @endif
    <style>
        .error
        {
        color: red;
        size: 80%
        }
        .hidden
        {
        display:none;
        }
        .show{
            display:block;
        }
        .phone-boarder{
            border-color: #ff0000 !important;
            background-image: url("data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' fill='%23dc3545' viewBox='-2 -2 7 7'><path stroke='%23d9534f' d='M0 0l3 3m0-3L0 3'/><circle r='.5'/><circle cx='3' r='.5'/><circle cy='3' r='.5'/><circle cx='3' cy='3' r='.5'/></svg>") !important;
            background-repeat: no-repeat !important;
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

            <form id="signupForm" enctype="multipart/form-data" method="post" action="{{ route('admins/store') }}"
                novalidate="novalidate">
                {{ csrf_field() }}
                @if (isset($editdata->id))
                    <input type="hidden" name="update" value="{{ $editdata->id }}">
                @endif
                <div class="tab-content">
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="tab-content">
                                <div class="card-header">
                                    <h5 class="card-title" style="margin-top: 10px">Add Admin</h5>
                                </div>
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group"><label for="First Name"
                                                    class="">First Name <span class="text-danger">*</span></label>
                                                <input name="FirstName"
                                                    value="@if (isset($editdata->id)){{$editdata->FirstName}}@else{{old('FirstName')}}@endif"
                                                    required placeholder="Enter First Name" type="text"
                                                    class="form-control" id="FirstName">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="Last Name" class="">Last Name <span class="text-danger">*</span></label>
                                                <input name="LastName"
                                                    value="@if (isset($editdata->id)){{$editdata->LastName}}@else{{old('LastName')}}@endif"
                                                    required placeholder="Enter Last Name" type="text"
                                                    class="form-control" id="LastName">
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="Email" class="">Email <span class="text-danger">*</span></label>
                                                <input name="UserEmail"
                                                    value="@if (isset($editdata->id)){{$editdata->UserEmail}}@else{{old('UserEmail')}}@endif"
                                                    required placeholder="Enter Email" id="useremail" type="email"
                                                    class="form-control">
                                                @if ($errors->has('UserEmail'))
                                                    <p style="color:red; margin-left: 1px; font-weight: bold;">
                                                        {{ $errors->first('UserEmail') }}</p>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="UserPhone" class="">Phone <span class="text-danger">*</span></label>
                                                <input name="UserPhone"
                                                    value="@if (isset($editdata->id)){{$editdata->UserPhone}}@else{{old('UserPhone')}}@endif"
                                                    required placeholder="Enter User Phone" type="number"
                                                    class="form-control" id="userphone" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" onkeydown="if(event.key==='.'){event.preventDefault();}">
                                                    <div id="phone_error" class="error hidden">Please enter a valid phone number</div>

                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="UserJobTitle" class="">JobTitle <span class="text-danger">*</span></label>
                                                <input name="UserJobtitle"
                                                    value="@if (isset($editdata->id)){{$editdata->UserJobtitle}}@else{{old('UserJobtitle')}}@endif"
                                                    required placeholder="Enter User UserJobTitle" type="text"
                                                    class="form-control" id="UserJobtitle">
                                            </div>
                                        </div>

                                        <div class="col-lg-4 col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="User Image" class="">Image</label>
                                                <input name="UserImage"  type="file" value="@if (isset($editdata->id)){{ $editdata->UserImage }}@endif"
                                                {{--  @if (!isset($editdata->id)){{ "required" }}@endif  --}}
                                                 placeholder="Enter User UserImage" class="form-control">
                                                @if (isset($editdata->id))
                                                <input type="hidden" name="oldImage" value="{{ $editdata->UserImage }}">
                                                @endif
                                            </div>
                                        </div>

                                        @if (isset($editdata->id) && isset($editdata->UserImage))
                                            <div class="col-lg-2 col-md-12 d-flex justify-content-center">
                                                <img src="{{ url('/') }}/CompanyLogo/{{ $editdata->UserImage }}" alt="No Image" width="100px" height="100px" style=" margin: 28px 0px 10px 0px;border-radius: 10px;">

                                            </div>
                                        @endif

                                        <input name="password"  id="password" type="hidden" class="form-control" value="$2y$10$bT6mqnjYECn30kExDUwE5O87jumg2uvGHcw1IwDKk4MEGa26MItQC">

                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="d-block text-right card-footer">
                            <button type="submit" id="submit" class="btn-wide btn btn-success" style="margin-right: 20px" onclick="run()">
                                @if (isset($editdata->id))
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
    <script>

// $("#userphone").change(function() {
//         var a = $("#userphone").val();
//         var filter = /^[0-9][0-9]{9}$/;

//         if (filter.test(a)) {
//             var element = document.getElementById('phone_error');
//             element.classList.remove("show");
//             var ele = document.getElementById('userphone');
//             ele.classList.remove("phone-boarder");
//         }
//         else {
//             alert("Please enter 10 digit mobile number!");
//             var element = document.getElementById('phone_error');
//             var ele = document.getElementById('userphone');
//             // element.classList.remove("is-valid");
//             element.classList.add("show");
//             ele.classList.add("phone-boarder");

//         }
//     });

    $("input").on('keydown', function (e) {
        if (this.value.length === 0 && e.which === 32) e.preventDefault();
    });

    function run() {
        let firstName = document.getElementById('FirstName').value;
        let lastName = document.getElementById('LastName').value;
        let userJobtitle = document.getElementById('UserJobtitle').value;
        if (firstName == null) {
            alert(`First Name can't be null`);
        }
        if (lastName == " ") {
            alert(`You can't fill null`);
        }
        if (userJobtitle == " ") {
            alert(`You can't fill null`);
        }
    }
    </script>
@endsection
