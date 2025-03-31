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
                        <h5 class="card-title" style="margin-top: 10px">Mail Format</h5>
                    </div>
                    <div class="tab-card-body">
                        <ul class="nav nav-pills mb-3 tablist" id="pills-tab" role="tablist">
                            <li class="nav-item tab-item">
                                <a class="nav-link show active companytab" id="pills-company-tab" data-toggle="pill"
                                    href="#pdf_formate_one" role="tab" aria-controls="company" aria-selected="true">PDF
                                    Formate One</a>
                            </li>
                            <li class="nav-item tab-item">
                                <a class="nav-link show" id="pills-office-tab" data-toggle="pill"
                                    href="#pdf_formate_two" role="tab" aria-controls="pdf_formate_two"
                                    aria-selected="false">PDF Formate Two</a>
                            </li>
                            <li class="nav-item tab-item">
                                <a class="nav-link show" id="pills-admin-tab" data-toggle="pill" href="#footer_design"
                                    role="tab" aria-controls="admin" aria-selected="false">Footer Design</a>
                            </li>
                            <li class="nav-item tab-item">
                                <a class="nav-link show" id="pills-admin-tab" data-toggle="pill"
                                    href="#last_document_design" role="tab" aria-controls="admin"
                                    aria-selected="false">Last Document</a>
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
                                    <div class="col-md-2 col-sm-3">
                                        <br>
                                        <p><b>Sample </b></p>
                                        <p>[Date]</p>
                                        <p>[CustomerName]</p>
                                        <p>[ProjectName]</p>
                                        <p>[QuotationGenerationId]</p>
                                        <p>[ContractorName]</p>
                                    </div>
                                    <div class="col-md-10   col-sm-9">
                                        <div class="main-card mb-3 card">
                                            <form action="{{route('submitpdf1')}}" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="updval"
                                                    value="@if( !empty($pdf1->id) ) {{ $pdf1->id }} @endif">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label for="First Name" class="">PDF Format One<span
                                                                class="text-danger">*</span></label>
                                                        <textarea cols="80" style="height:500px !important" id="editor1" name="editor1" rows="10"
                                                            data-sample-short>@if( !empty($pdf1->msg) ) {{ $pdf1->msg }} @endif</textarea>

                                                    </div>
                                                </div>
                                                <div class="d-block text-right card-footer">
                                                    <button id="print1" class="btn-wide btn btn-info"
                                                        style="margin-right: 20px"> PRINT PREVIEW </button>
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
                                    <div class="col-md-2 col-sm-3">
                                        <br>
                                        <p><b>Sample </b></p>
                                        <p>[ProjectName]</p>
                                        <p>[QuotationGenerationId]</p>
                                        <p>[TotalDoorSet]</p>
                                        <p>[TotalIronmongery]</p>
                                        <p>[TotalNonConfig]</p>
                                        <p>[TotalScreenSet]</p>
                                        <p>[TotalDoorValue]</p>
                                        <p>[TotalIronmongeryValue]</p>
                                        <p>[TotalNonConfigValue]</p>
                                        <p>[TotalScreenValue]</p>
                                        <p>[NetSubTotal]</p>
                                        <p>[NetTotal]</p>
                                        <p>[NoOfDeliveries]</p>
                                        <p>[PaymentTerms]</p>
                                        <p>[UserName]</p>
                                        <p>[ContractorName]</p>
                                    </div>
                                    <div class="col-md-10 col-sm-9">
                                        <div class="main-card mb-3 card">
                                            <form action="{{route('submitpdf2')}}" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="updval2"
                                                    value="@if( !empty($pdf2->id) ) {{ $pdf2->id }} @endif">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label for="First Name" class="">PDF Format Two<span
                                                                class="text-danger">*</span></label>
                                                        <textarea cols="80" id="editor2" name="editor2" rows="10" style="height:29.7cm"
                                                            data-sample-short>@if( !empty($pdf2->msg) ) {{ $pdf2->msg }} @endif</textarea>

                                                    </div>
                                                </div>
                                                <div class="d-block text-right card-footer">
                                                    <button id="print2" class="btn-wide btn btn-info"
                                                        style="margin-right: 20px"> PRINT PREVIEW </button>
                                                    <button type="submit" id="submit" class="btn-wide btn btn-success"
                                                        style="margin-right: 20px"> SET FORMAT </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="footer_design" role="tabpanel" aria-labelledby="company">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2">
                                        <br>
                                        <p><b>Sample </b></p>
                                        <p>[CompanyName]</p>
                                        <p>[CompanyAddress]</p>
                                    </div>
                                    <div class="col-md-10 col-sm-10">
                                        <div class="main-card mb-3 card">
                                            <form action="{{route('submitpdf3')}}" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="updval3"
                                                    value="@if( !empty($pdf_footer->id) ) {{ $pdf_footer->id }} @endif">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label for="First Name" class="">Footer Design<span
                                                                class="text-danger">*</span></label>
                                                        <textarea cols="80" id="editor3" name="editor3" rows="10"
                                                            data-sample-short>@if( !empty($pdf_footer->msg) ) {{ $pdf_footer->msg }} @endif</textarea>

                                                    </div>
                                                </div>
                                                <div class="d-block text-right card-footer">
                                                    <button id="print3" class="btn-wide btn btn-info"
                                                        style="margin-right: 20px"> PRINT PREVIEW </button>
                                                    <button type="submit" id="submit" class="btn-wide btn btn-success"
                                                        style="margin-right: 20px"> SET FORMAT </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="tab-pane fade" id="last_document_design" role="tabpanel"
                                aria-labelledby="company">
                                <div class="row">
                                    <div class="col-md-2 col-sm-2" >
                                        <br>
                                        <p><b>Sample </b></p>
                                    </div>
                                    <div class="col-md-10 col-sm-10">
                                        <div class="main-card mb-3 card">
                                            <form action="{{route('submitpdf4')}}" method="post">
                                                {{ csrf_field() }}
                                                <input type="hidden" name="updval4"
                                                    value="@if( !empty($pdf_document->id) ) {{ $pdf_document->id }} @endif">
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <label for="First Name" class="">Last Document Design<span
                                                                class="text-danger">*</span></label>
                                                        <textarea cols="80" id="editor4" name="editor4" rows="10"
                                                            data-sample-short>@if( !empty($pdf_document->msg) ) {{ $pdf_document->msg }} @endif</textarea>
                                                    </div>
                                                </div>
                                                <div class="d-block text-right card-footer">
                                                    <button id="print4" class="btn-wide btn btn-info"
                                                        style="margin-right: 20px"> PRINT PREVIEW </button>
                                                    <button type="submit" id="submit" class="btn-wide btn btn-success"
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

    // CKEDITOR.replace( 'editor'+i, {
    //     height: 300,
    //     filebrowserUploadUrl: "upload.php"
    // });
    // CKEDITOR.replace('editor'+i, {
    //     extraPlugins: 'easyimage',
    //     removePlugins: 'image',
    //     removeDialogTabs: 'link:advanced',
    //     cloudServices_uploadUrl: 'https://33333.cke-cs.com/easyimage/upload/',
    //     // Note: this is a token endpoint to be used for CKEditor 4 samples only. Images uploaded using this token may be deleted automatically at any moment.
    //     // To create your own token URL please visit https://ckeditor.com/ckeditor-cloud-services/.
    //     cloudServices_tokenUrl: 'https://33333.cke-cs.com/token/dev/ijrDsqFix838Gh3wGO3F77FSW94BwcLXprJ4APSp3XQ26xsUHTi0jcb1hoBt',
    //     easyimage_styles: {
    //         gradient1: {
    //         group: 'easyimage-gradients',
    //         attributes: {
    //             'class': 'easyimage-gradient-1'
    //         },
    //         label: 'Blue Gradient',
    //         icon: 'https://ckeditor.com/docs/ckeditor4/4.15.1/examples/assets/easyimage/icons/gradient1.png',
    //         iconHiDpi: 'https://ckeditor.com/docs/ckeditor4/4.15.1/examples/assets/easyimage/icons/hidpi/gradient1.png'
    //         },
    //         gradient2: {
    //         group: 'easyimage-gradients',
    //         attributes: {
    //             'class': 'easyimage-gradient-2'
    //         },
    //         label: 'Pink Gradient',
    //         icon: 'https://ckeditor.com/docs/ckeditor4/4.15.1/examples/assets/easyimage/icons/gradient2.png',
    //         iconHiDpi: 'https://ckeditor.com/docs/ckeditor4/4.15.1/examples/assets/easyimage/icons/hidpi/gradient2.png'
    //         },
    //         noGradient: {
    //         group: 'easyimage-gradients',
    //         attributes: {
    //             'class': 'easyimage-no-gradient'
    //         },
    //         label: 'No Gradient',
    //         icon: 'https://ckeditor.com/docs/ckeditor4/4.15.1/examples/assets/easyimage/icons/nogradient.png',
    //         iconHiDpi: 'https://ckeditor.com/docs/ckeditor4/4.15.1/examples/assets/easyimage/icons/hidpi/nogradient.png'
    //         }
    //     },
    //     easyimage_toolbar: [
    //         'EasyImageFull',
    //         'EasyImageSide',
    //         'EasyImageGradient1',
    //         'EasyImageGradient2',
    //         'EasyImageNoGradient',
    //         'EasyImageAlt'
    //     ]
    // });

    i++;
}

$('#print1').on('click',function(){
    var editor1 = $('#editor1').val();
    newwin=window.open();
    newwin.document.write(editor1)
    newwin.document.close();
})

$('#print2').on('click',function(){
    var editor2 = $('#editor2').val();
    newwin=window.open();
    newwin.document.write(editor2)
    newwin.document.close();
})

$('#print3').on('click',function(){
    var editor3 = $('#editor3').val();
    newwin=window.open();
    newwin.document.write(editor3)
    newwin.document.close();
})

$('#print4').on('click',function(){
    var editor4 = $('#editor4').val();
    newwin=window.open();
    newwin.document.write(editor4)
    newwin.document.close();
})
</script>

@endsection
