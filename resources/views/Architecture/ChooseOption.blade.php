@extends("layouts.Master")

@section("main_section")
<div class="app-main__outer">
      <div class="app-main__inner">
                    <div class="tabs-animation">
                      
                        <div class="container">
                           <div class="row">
                           @if(!empty($id))
                               
                                <div class="col-md-4">
                                <a href="{{ url('file/file-import/'.$id) }}" style="text-decoration: none;">
                                    <div class="card mb-3 bg-primary widget-chart text-white card-border">
                                        <div class="icon-wrapper rounded-circle">
                                            <div class="icon-wrapper-bg bg-white opacity-1"></div>
                                            <i class="lnr-cog text-white"></i></div>
                                        <div class="widget-numbers">{{{ trans('main.Import') }}} </div>
                                        <div class="widget-subheading">{{{ trans('main.ImportFile') }}} </div>
                                        <div class="widget-description text-success">
                                    </div>
                                    </div>
                                </a>
                                </div>
                             
                                <div class="col-md-4">
                                <a href="{{ url('file/add-quation-details/'.$id) }}" style="text-decoration: none;">
                                    <div class="card mb-3 bg-primary widget-chart text-white card-border">
                                        <div class="icon-wrapper rounded-circle">
                                            <div class="icon-wrapper-bg bg-white opacity-1"></div>
                                            <i class="lnr-cog text-white"></i></div>
                                        <div class="widget-numbers">{{{ trans('main.Mannual') }}} </div>
                                        <div class="widget-subheading">{{{ trans('main.AddQUationDetail') }}} </div>
                                        <div class="widget-description text-success">
                                    </div>
                                    </div>
                                </a>
                                </div>
                              
                            </div>
                            @else
                            redirect('file/quation-file-list/');
                            @endif
                               
                               
                        </div>
                       
                    </div>
                </div>
            </div>
@endsection