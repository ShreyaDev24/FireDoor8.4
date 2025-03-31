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


<div class="col-md-4 ">
<div class="card-shadow-primary card-border text-white mb-3 card ">
<div class="dropdown-menu-header">

<div class="dropdown-menu-header-inner ">
    <div class="menu-header-content">
        
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