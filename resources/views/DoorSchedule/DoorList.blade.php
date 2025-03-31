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



<div class="col-md-12 col-lg-6 col-xl-4">
                            <div class="card-shadow-primary card-border text-white mb-3 card bg-primary">
                                <div class="dropdown-menu-header">
                                    <div class="dropdown-menu-header-inner bg-primary">
                                        <div class="menu-header-content">
                                            <div class="avatar-icon-wrapper mb-3 avatar-icon-xl">
                                                <div class="avatar-icon"><img
                                                        src="http://firedoorsdev.intnook.com/storage/itemphoto/strebord.jpg"
                                                        alt="Avatar 5"></div>
                                            </div>
                                            <div><h5 class="menu-header-title">Strebord</h5><h6 class="menu-header-subtitle">on configuration</h6></div>
                                            <div class="menu-header-btn-pane pt-1">
                                                <a href="{{{route('items/add')}}}" class="btn-icon btn btn-dark btn-sm"><i class="pe-7s-config btn-icon-wrapper"> </i>Configuration</a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- <div class="text-center d-block card-footer">
                                    <button class="btn-shadow-dark btn-wider btn btn-dark">Send Message</button>
                                </div> -->
                            </div>
       </div>

    </div>
</div>


@endsection