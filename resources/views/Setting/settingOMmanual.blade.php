@extends("layouts.Master")
@section("main_section")
<!-- <script src="https://cdn.ckeditor.com/4.15.1/standard/ckeditor.js"></script> -->
<!-- <script src="https://cdn.ckeditor.com/4.15.1/standard-all/ckeditor.js"></script> -->
<!-- <script src="https://cdn.ckeditor.com/4.15.1/full-all/ckeditor.js"></script> -->
<!-- <script src="http://cdn.ckeditor.com/4.6.2/standard-all/ckeditor.js"></script> -->
<script src="https://cdn.ckeditor.com/4.12.1/full/ckeditor.js"></script>
<style>
    .cke_contents{
        height: 29.7cm !important;
    }
</style>
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="tab-content">
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title" style="margin-top: 10px">O&M Manual</h5>
                    </div>
                    <div class="tab-card-body">
                        <ul class="nav nav-pills mb-3 tablist" id="pills-tab" role="tablist">
                            <li class="nav-item tab-item">
                                <a class="nav-link show active companytab" id="pills-company-tab" data-toggle="pill"
                                    href="#pdf_formate_one" role="tab" aria-controls="company" aria-selected="true">
                                    Introduction</a>
                            </li>
                            <li class="nav-item tab-item">
                                <a class="nav-link show companytab" id="pills-company-tab" data-toggle="pill"
                                    href="#pdf_formate_two" role="tab" aria-controls="company" aria-selected="true">
                                    Architectural Ironmongery</a>
                            </li>
                            <li class="nav-item tab-item">
                                <a class="nav-link show companytab" id="pills-company-tab" data-toggle="pill"
                                    href="#pdf_formate_three" role="tab" aria-controls="company" aria-selected="true">
                                    Door Furniture</a>
                            </li>
                        </ul>
                        <div class="tab-content" id="pills-tabContent">
                            @if(session()->has('success'))
                            <div class="alert alert-success alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>
                                <h5><i class="icon fas fa-check"></i> Alert!</h5>
                                {{ session()->get('success') }}
                            </div>
                            @endif
                            @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert"
                                    aria-hidden="true">&times;</button>
                                <h5><i class="fas fa-skull-crossbones"></i> Alert!</h5>
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                            <div class="tab-pane fade active show" id="pdf_formate_one" role="tabpanel"
                                aria-labelledby="company">
                                <div class="row">
                                    <div class="col-md-3 col-sm-3">
                                        <br>
                                        <p><b>Sample </b></p>
                                    </div>
                                    <div class="col-md-9 col-sm-9">
                                        <div class="main-card mb-3 card">
                                            <form action="{{route('submitIntroductionPDF')}}" method="post" enctype="multipart/form-data">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="updval" value="@if(!empty($pdf1->id)){{ $pdf1->id }}@endif">
                                                <div class="row"> 
                                                    <div class="col-md-6" style="padding-left: 0;">
                                                        <div class="form-group">
                                                            <div class="position-relative form-group">
                                                                <label for="backgroundImage">Background Image</label>
                                                                <input type="file" name="backgroundImage" id="backgroundImage" class="form-control">
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6" style="padding-left: 0;">
                                                        @if(!empty($pdf1->backgroundImage))
                                                            <img src="{{url('/')}}/ommanual/{{ $pdf1->backgroundImage }}" alt="image" style="width:100px;height:100px;">
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label for="">Content<span class="text-danger">*</span></label>
                                                        <textarea cols="80" id="editor1" name="editor1" rows="10"
                                                            data-sample-short>@if(!empty($pdf1->content)){{ $pdf1->content }}@endif</textarea>
                                                    </div>
                                                </div>
                                                <div class="d-block text-right card-footer">
                                                    <button type="submit" id="submit" class="btn-wide btn btn-success"
                                                        style="margin-right: 20px"> SET FORMAT </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pdf_formate_two" role="tabpanel" aria-labelledby="company">
                                <div class="row">
                                    <div class="col-md-3 col-sm-3">
                                        <br>
                                        <p><b>Sample </b></p>
                                    </div>
                                    <div class="col-md-9 col-sm-9">
                                        <div class="main-card mb-3 card">
                                            <form action="{{route('submitArchitecIronmon')}}" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="updval2"
                                                    value="@if( !empty($pdf2->id) ) {{ $pdf2->id }} @endif">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label for="First Name" class="">Architectural Ironmongery<span
                                                                class="text-danger">*</span></label>
                                                        <textarea cols="80" id="editor2" name="editor2" rows="10"
                                                            data-sample-short>@if( !empty($pdf2->content) ) {{ $pdf2->content }} @endif</textarea>

                                                    </div>
                                                </div>
                                                <div class="d-block text-right card-footer">
                                                    <button type="submit" id="submit" class="btn-wide btn btn-success"
                                                        style="margin-right: 20px"> SET FORMAT </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="pdf_formate_three" role="tabpanel" aria-labelledby="company">
                                <div class="row">
                                    <div class="col-md-3 col-sm-3">
                                        <br>
                                        <p><b>Sample </b></p>
                                    </div>
                                    <div class="col-md-9 col-sm-9">
                                        <div class="main-card mb-3 card">
                                            <form action="{{route('submitDoorFurniture')}}" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="updval3"
                                                    value="@if( !empty($pdf3->id) ) {{ $pdf3->id }} @endif">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label for="">Door Furniture<span class="text-danger">*</span></label>
                                                        <textarea cols="80" id="editor3" name="editor3" rows="10"
                                                            data-sample-short>@if( !empty($pdf3->content) ) {{ $pdf3->content }} @endif</textarea>

                                                    </div>
                                                </div>
                                                <div class="d-block text-right card-footer">
                                                    <button type="submit" class="btn-wide btn btn-success"
                                                        style="margin-right: 20px"> SET FORMAT </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
@section("script_section")
<script>
var i = 1;
var max = 5;
while (max > i) {
    CKEDITOR.replace('editor' + i, {
        filebrowserUploadUrl: "{{route('ckeditor.upload', ['_token' => csrf_token() ])}}",
        filebrowserUploadMethod: 'form'
    });
    i++;
}
</script>

@endsection