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
           
      <form id="signupForm" enctype="multipart/form-data" method="post" action="{{route('file/store-filename')}}" novalidate="novalidate">
        {{csrf_field()}}
        @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <strong>{{ $message }}</strong>
            </div>
          @endif
          @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                <strong>{{ $message }}</strong>
            </div>
          @endif
    <div class="tab-content">
       <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="tab-content">
                      <div class="card-header">
                        <h5 class="card-title" style="margin-top: 10px">{{{ trans('main.createQuationFile') }}}</h5>
                        </div>
                        <div class="card-body">
                         <div class="form-row">
                            <div class="col-sm-3"></div>
                            <div class="col-sm-6">
                            <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="filename" class="">{{{ trans('main.fileName') }}}</label>
                                    <input name="id"  id="id" style="height: 45px !important" type="hidden" value=@if(!empty($editdata)) {{{$editdata->id }}}@else {{{0}}} @endif class="form-control"> 
                                    <input name="filename"  id="filename" type="text" style="height: 45px !important" required placeholder="Enter file name"  class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="filename" class="">{{{ trans('main.SelectFile') }}}</label>
                                  
                                    <input name="file" require id="file" style="height: 45px !important" required type="file" class="form-control">
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