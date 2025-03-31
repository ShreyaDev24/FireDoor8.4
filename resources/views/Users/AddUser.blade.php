@extends("layouts.Master")

@section("main_section")
@if(session()->has('error'))
<style type="text/css">
    #useremail{
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

      <form id="signupForm" enctype="multipart/form-data" method="post" action="{{route('user/store')}}" novalidate="novalidate">
        {{csrf_field()}}
        @if(isset($editdata->id))
        <input type="hidden" name="update" value="{{$editdata->id}}">
        @endif
    <div class="tab-content">
       <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="tab-content">
                                  <div class="card-header">
                            <h5 class="card-title" style="margin-top: 10px">Add Employee Details</h5>
                        </div>
                        <div class="card-body">
                         <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group"><label for="FirstName" class="">First Name<span class="text-danger">*</span></label>
                                <input name="FirstName" value="@if(isset($editdata->id)){{$editdata->FirstName}}@else{{old('FirstName')}}@endif" required placeholder="Enter First Name" type="text" class="form-control" id="FirstName" pattern="[A-Za-z]{10}" onkeypress="keyprs(this.value)"></div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="Last Name" class="">Last Name<span class="text-danger">*</span></label>
                                    <input name="LastName" value="@if(isset($editdata->id)){{$editdata->LastName}}@else{{old('LastName')}}@endif" required placeholder="Enter Last Name" type="text" class="form-control" id="LastName" onkeypress="keyprs(this.value)">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="Email" class="">Email<span class="text-danger">*</span></label>
                                    <input name="UserEmail" value="@if(isset($editdata->id)){{$editdata->UserEmail}}@else{{old('UserEmail')}}@endif" required placeholder="Enter Email" id="useremail" type="email" class="form-control">
                                    @if($errors->has('UserEmail'))
                                    <p style="color:red; margin-left: 1px; font-weight: bold;">{{$errors->first('UserEmail')}}</p>
                                    @endif
                                </div>
                            </div>
                                <div class="col-md-6">
                                <div class="position-relative form-group">
                                <label for="UserPhone" class="">User Phone<span class="text-danger">*</span></label>
                                <input name="UserPhone" value="@if(isset($editdata->id)){{$editdata->UserPhone}}@endif" required placeholder="Enter User Phone" type="number" class="form-control" pattern="[0-9]{3}-[0-9]{2}-[0-9]{3}" id="userphone" onkeydown="if(event.key==='.'){event.preventDefault();}"></div>
                                <div id="phone_error" class="error hidden">Please enter a valid phone number</div>
                            </div>

                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                <label for="UserJobTitle" class="">User JobTitle<span class="text-danger">*</span></label>
                                <input name="UserJobtitle" value="@if(isset($editdata->id)){{$editdata->UserJobtitle}}@else{{old('UserJobtitle')}}@endif" required placeholder="Enter User UserJobTitle" type="text" class="form-control" id="UserJobtitle"></div>
                            </div>

                             <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <label for="User Image" class="">User Image<span class="text-danger">*</span></label>
                                    <input name="UserImage" @if (!isset($editdata->id)){{ "required" }}@endif type="file" class="form-control" >
                                    @if (isset($editdata->id))
                                        <input type="hidden" name="oldImage" value="{{$editdata->UserImage}}">
                                    @endif
                                </div>
                            </div>
                            @if(isset($editdata->id))
                            <div class="col-md-2 text-center">
                                <img src="/UserImage/{{$editdata->UserImage}}" alt="Image" height="150px" width="150px" style=" margin: 10px 0px 10px 0px;border-radius: 10px;">
                            </div>
                            @endif
                        @if(!isset($editdata->id))
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="Pasword" class="">Password<span class="text-danger">*</span></label>
                                    <input name="password"  id="password" required placeholder="endsectionr Password" type="password" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="ConfirmPassword" class="">Confirm Password<span class="text-danger">*</span></label>
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
//             // alert("Please enter 10 digit mobile number!");
//             // var element = document.getElementById('phone_error');
//             // var ele = document.getElementById('userphone');
//             // // element.classList.remove("is-valid");
//             // element.classList.add("show");
//             // ele.classList.add("phone-boarder");

//         }
//     });

    $("input").on('keydown', function (e) {
        if (this.value.length === 0 && e.which === 32) e.preventDefault();
    });

    function run() {
        let firstName = document.getElementById('FirstName').value;
        let lastName = document.getElementById('LastName').value;
        let userJobtitle = document.getElementById('UserJobtitle').value;
        let userPhone = document.getElementById('userphone').value;
        if (firstName.indexOf(' ') >= 0) {
            alert(`First Name can't be null`);
        }
        if (lastName.indexOf(' ') >= 0) {
            alert(`You can't fill null`);
        }
        if (userJobtitle.indexOf(' ') >= 0) {
            alert(`You can't fill null`);
        }
        // if(userPhone.length > 10 || userPhone.length <10){
        //     alert("Enter velid phone no. ")
        // }
    }

    // function keyprs(value){
    //     if(value.indexOf(' ') >= 0){
    //         alert("You can't add wide space");
    //     }
    // }

</script>

@endsection
