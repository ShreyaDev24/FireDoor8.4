@extends("layouts.Master")
@section('css')

@endsection
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
<div class="tab-content">

@if($errors->any())
<div class="alert alert-danger"><strong>Sorry!</strong>{{$errors->first()}}</div>
@endif
@if(!empty(@formlist) && count($formlist)>0)
<div class="row">

@foreach($formlist as $form)
@if(file_exists('../resources/views/Forms/'.$form->FileName))

@if($form->user_id=='0')
<div class="col-md-4 ">
<div class="card-shadow-primary card-border text-white mb-3 card">
<div class="dropdown-menu-header">

<div class="dropdown-menu-header-inner ">
    <div class="menu-header-content">
        <div>
              <button type="button" style="float: right; position: absolute; right: 1%" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true" class="btn-shadow dropdown-toggle btn btn-success">
    </button>
<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu dropdown-menu-right" x-placement="bottom-end" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(-129px, 33px, 0px);">
    <ul class="nav flex-column" style="padding:10px">
       <form action="{{route('assign-form-user/store')}}" method="post">
        {{csrf_field()}}
        <label><h5><b>Select User</b></h5></label>
        <input type="hidden" name="id" value="{{$form->id}}">
           <select class="form-control" name="user_id" style="height: 35px !important">
          
            @foreach($user as $row)
               <option value="{{$row->id}}">{{$row->FirstName.' '.$row->LastName}}</option>
            @endforeach
           </select>
           <br>
           <button type="submit" class="btn btn-success" style="width: 100%">Submit Now</button>
           <br><br>
       </form>
    </ul>
</div>
        </div>
        <div class="avatar-icon-wrapper mb-3 avatar-icon-xl">
            <div class="avatar-icon">
            
            @if(!empty($form->UserImage))
                <img src="{{url('/CompanyLogo/'.$form->UserImage)}}"
                    alt="Avatar 5">
            @else
            <img src="http://firedoorsdev.intnook.com/storage/itemphoto/strebord.jpg"
                    alt="Avatar 5">
            @endif
            </div>
        </div>
        <div><h5 class="menu-header-title">{{$form->FormName}}</h5><h6 class="menu-header-subtitle">on configuration</h6></div>
        <div class="menu-header-btn-pane pt-1">
            <a href="{{url('/form/view/'.$form->id)}}" class="btn-icon btn btn-dark btn-sm"><i class="pe-7s-config btn-icon-wrapper"> </i>Configuration</a>
        </div>
    </div>
</div>
</div>
<!-- <div class="text-center d-block card-footer">
<button class="btn-shadow-dark btn-wider btn btn-dark">Send Message</button>
</div> -->
</div>

</div>
@endif
@endif
@endforeach
</div>
@else
<div class="alert alert-danger" role="alert">
No Configuration form found.
</div>
@endif


@endsection

@section('js')
@if(session()->has('success'))
<script type="text/javascript">       
    swal(
        'Success',
        'User Assign <b style="color:green;">Success</b>!',
        'success'
    )
</script>
@endif
@endsection