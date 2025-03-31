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
           
      <form id="signupForm" enctype="multipart/form-data" method="post" action="{{route('ChangePassword')}}" novalidate="novalidate">
        {{csrf_field()}}
      
    <div class="tab-content">
       <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="tab-content">
                      <div class="card-header">
                        <h5 class="card-title" style="margin-top: 10px">Reset Password</h5>
                        </div>
                        <div class="card-body">
                         <div class="form-row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                            <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="Pasword" class="">Password</label>

                                    {{--@if(isset()) @endif--}}

                                    <input name="id"  id="id" style="height: 45px !important" placeholder="id" type="hidden" value="{{Request::segment(2)}}" class="form-control">
                                    <input name="password"  id="password" style="height: 45px !important" required placeholder="New Password" type="password" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="ConfirmPassword" class="">Confirm Password</label>
                                    <input name="confirm_password" id="confirm_password" style="height: 45px !important" required placeholder="Confirm Password" type="password" class="form-control">
                                </div>
                            </div>
                            </div>
                             <button type="submit" id="submit" class="btn-wide btn btn-success" style="margin-right: 20px; height: 45px !important; width: 100%">
                                @if(isset($editdata->id))
                                Update Now
                                @else
                                Submit Now
                                @endif
                            </button>
                            </div>
                            <div class="col-sm-3"></div>
                        </div>
                </div>
            </div>
        </div>
                        <div class="d-block text-right card-footer">
                           
                        </div>
                </div>
            </div>
        </div>
      </form>
    </div>
</div>
@endsection