@extends("layouts.CadMaster")
@section("main_section")

<style>
.input-icons i {
    position: absolute;
    right: 0;
    top: 27px;
}

.table_row_hide{
    display: none;
}




/* ul.nav.nav-tabs.border-0 {
    position: fixed;
    z-index: 1;
    background: #fff;
    padding: 10px;
    width: 47.5%;
} */

.main-carousel {
    position: fixed;
    z-index: 10000;
    width: 47.5%;
    background: #fff;
    padding: 10px;
}

.item-form {
    margin-top: 70px;
}

#BuildOfMaterial {
    margin-top: 71px;
}

.nav-pills,
.nav-tabs {
    margin-bottom: 0px;
}


@media only screen and (max-width: 600px) {
    .main-carousel {
        width: 92%;
    }

    ul.nav.nav-tabs.border-0 {
        top: 140px;
        width: 92%;
    }

    .item-form {
        margin-top: 200px;
    }

}
.td-align{
    width: 40%
}
.td-alignment{
    width: 45%
}
.td-align3{
    width: 15%
}
.modal-backdrop{
    display: none;
}
.modal{
background-color: #00000059;
margin-top: 40px;
}
.fixed-header .app-header{
    z-index: 9;
}
</style>

<div class="app-main__outer">
    <div class="app-main__inner pt-0">
        @if (\Session::has('msg'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('msg') !!}</li>
            </ul>
        </div>
        @endif

        <span class="error"></span>

        <div class="form-row">
            <div class="col-md-6">

                <div class="main-carousel">
                    <div class="main-container">
                        <div class="container-carousel pl-1 pr-4">
                            <button role="button" id="arrow-left"
                                class="arrow-left border-0 text-secondary p-1 rounded"><i
                                    class="fa fa-chevron-left"></i></button>
                            <div class="carousel">
                                <ul class="nav nav-tabs">
                                    <li class="optionItem">
                                        <a class="btn btn-primary active" data-toggle="tab"
                                            href="#main-options-section">Main Options</a>
                                        <input type="hidden" value="0px">
                                    </li>
                                    <li class="optionItem">
                                        <a class="btn btn-primary" data-toggle="tab"
                                            href="#door-dimensions-n-door-leaf-section">Screen Dimension & Glazing</a>
                                        <input type="hidden" value="432px">
                                    </li>
                                    <li class="optionItem framehideshow">
                                        <a class="btn btn-primary" data-toggle="tab" href="#frame-section">Frame & Sub Frame</a>
                                        <input type="hidden" value="2039px">
                                    </li>
                                    <li class="optionItem framehideshow">
                                        <a class="btn btn-primary" data-toggle="tab" href="#Transom-section">Transom</a>
                                        <input type="hidden" value="2656px">
                                    </li>
                                    <li class="optionItem framehideshow">
                                        <a class="btn btn-primary" data-toggle="tab" href="#Mullion-section">Mullion</a>
                                        <input type="hidden" value="2958px">
                                    </li>
                                    <li class="optionItem framehideshow">
                                        <a class="btn btn-primary" data-toggle="tab" href="#GlassPane-section">GlassPane</a>
                                        <input type="hidden" value="2958px">
                                    </li>
                                </ul>
                            </div>
                            <button role="button" id="arrow-right"
                                class="arrow-right border-0 text-secondary p-1 rounded"><i
                                    class="fa fa-chevron-right"></i></button>
                        </div>
                    </div>
                </div>

                <div class="item-form">
                    <form id="itemForm" enctype="multipart/form-data">
                        <input type="hidden" name="pageIdentity" id="pageIdentity" value="1">
                        <input type="hidden" name="VersionId" value="<?= (!is_null($versionId))?$versionId:0; ?>">
                        <input type="hidden" name="SvgImage" value="" />
                        <div class="tab-content">
                            <div id="main-options-section" class="tab-pane active">
                                @include("Items.SideScreen.SideScreenModules.MainOptions")
                            </div>

                            <div id="door-dimensions-n-door-leaf-section" class="tab-pane fade">
                                @include("Items.SideScreen.SideScreenModules.DoorDimensionsAndDoorLeaf")
                            </div>

                            <div id="frame-section" class="tab-pane fade framehideshow">
                                @include("Items.SideScreen.SideScreenModules.Frame")
                            </div>

                            <div id="Transom-section" class="tab-pane fade">
                                @include("Items.SideScreen.SideScreenModules.Transom")
                            </div>

                            <div id="Mullion-section" class="tab-pane fade framehideshow">
                                @include("Items.SideScreen.SideScreenModules.Mullion")
                            </div>

                            <div id="GlassPane-section" class="tab-pane fade framehideshow">
                                @include("Items.SideScreen.SideScreenModules.GlassPane")
                            </div>
                        </div>
                </div>
            </div>
            <div class="col-md-6">
                <div style="position: fixed;z-index: 1;background: #ffffff;padding: 10px;width: 47.5%;">
                    <ul class="nav nav-tabs border-0 float-left">
                        <li class="optionItem">
                            <a href="{{url('quotation/generate')}}/{{$QuotationId}}/{{ ($versionId !== null)?$versionId:0 }}"
                                class="door_submit">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </li>
                        <li class="optionItem">
                            <a class="btn btn-primary active" data-toggle="tab" href="#door">
                                <i class="fa fa-image" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li>
                            <a href="javascript:void(0);" class="btn-sm btn btn-primary active" onClick="render();" style="margin: 0px 10px 0px 5px;">Render Image</a>
                        </li>

                        <li class="optionItem d-flex align-items-center">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="change-dimension" checked>
                                <label class="form-check-label cursor-pointer" for="change-dimension">Dimensions On/Off</label>
                            </div>
                        </li>
                    </ul>
                    @if(Auth::user()->UserType=='1' ||Auth::user()->UserType=='2' || Auth::user()->UserType=='3' || Auth::user()->UserType=='4' || Auth::user()->UserType=='5')
                    <div class="float-right">
                        <button type="button" id="submit" class="btn btn-success active">
                            <i class="fas fa-paper-plane"></i> @if(!empty($Item["id"])){{ 'Update Now' }} @else
                            {{'Submit Now'}} @endif
                        </button>
                    </div>
                    @endif
                </div>
                </form>
                <div class="tab-content" id="opDiv">
                    <div id="door" class="tab-pane active">
                        <div id='container'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="glazingModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="glazingModalLabel">All Materials</h5>
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal"
                    aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="inputId">
                <div id="glazingModalBody">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<div hidden id="get-glass-options">{{route('items/get-glass-options')}}</div>
<div hidden id="screen-glass-glazing">{{route('items/screen-glass-glazing')}}</div>
<div hidden id="screen-glazing-thickness">{{route('items/screen-glazing-thickness')}}</div>
<div hidden id="glazingFilterScreen">{{route('items/glazingFilterScreen')}}</div>

<script src="{{asset('js/screen-rules.js')}}"></script>
<script src="{{asset('js/screen-cad-configurable.js')}}"></script>
<script src="{{asset('js/screen-build-of-material.js')}}"></script>

@if(!empty($Item))
@foreach($Item as $key => $val)
<div id="{{$key}}-value" data-value="{{$val}}" hidden=""></div>
@endforeach
@endif

@endsection


@section("js")
<script>
    var possibleSelectedOptionsJson = JSON.stringify(<?=json_encode(\Config::get('constants.PossibleSelectedOptions.SelectedOptionsWithDbSlugKey'))?>);

    $(document).ready(function() {

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        @if(isset($Item['FireRating']))
        FireRatingChange();
        @endif

        @if(isset($Item['GlazingType']))
        glazingType();
        @endif

        @if(isset($Item['SinglePane']))
        GlassGlazingFilter("{{$Item['SinglePane']}}",true);
        @endif

        @if(isset($Item['GlazingSystem']))
        GlazingThicknessFilter("{{$Item['GlazingSystem']}}",true);
        @endif

        @if(isset($Item['TransomQuantity']))
        $('select[name=TransomQuantity]').val({{$Item['TransomQuantity']}});
        updateFields('TransomQuantity',{{$Item['TransomQuantity']}},true);
        @endif

        @if(isset($Item['MullionQuantity']))
            $('select[name=MullionQuantity]').val({{$Item['MullionQuantity']}});
            updateFields('MullionQuantity',{{$Item['MullionQuantity']}},true);
        @endif

        glazingFilterScreen(true);

        const optionRow = document.querySelector('.container-carousel');
        const optionItems = document.querySelectorAll('.optionItem');

        const arrowLeft = document.getElementById('arrow-left');
        const arrowRight = document.getElementById('arrow-right');

        // ? ----- ----- Right arrow Event Listener ----- -----
        arrowRight.addEventListener('click', () => {
            optionRow.scrollLeft += optionRow.offsetWidth;

            const activeArrow = document.querySelector('.indicadores .activo');
            if(activeArrow){
                activeArrow.classList.add('activo');
                activeArrow.classList.remove('activo');
            }
        });

        // ? ----- ----- Left arrow Event Listener ----- -----
        arrowLeft.addEventListener('click', () => {
            optionRow.scrollLeft -= optionRow.offsetWidth;

            const activeArrow = document.querySelector('.indicadores .activo');
            if(activeArrow){
                activeArrow.classList.add('activo');
                activeArrow.classList.remove('activo');
            }
        });
    });

    $(document).on('change', '.change-event-calulation', function(e) {
        glazingFilterScreen();
    });

    function glazingFilterScreen(isstatus = false){
        var SOWidth = $('#SOWidth').val();
        var SOHeight = $('#SOHeight').val();
        var FrameThickness = $('#FrameThickness').val() ?? 0;
        var FrameDepth = $('#FrameDepth').val() ?? 0;
        var TransomHeight1 = $('#TransomHeight1').val() ?? 0;
        var TransomThickness = $('#TransomThickness').val() ?? 0;
        var Tolerance = $('#Tolerance').val() ?? 0;
        var SinglePane = $('#SinglePane').val() ?? 0;
        var SelectedValue = $('#SelectedValue').val() ?? 0;

        var TransomHeightPoint1 = $('#TransomHeight1').val() ?? 0;
        var TransomHeightPoint2 = $('#TransomHeightPoint2').val() ?? 0;
        var TransomHeightPoint3 = $('#TransomHeightPoint3').val() ?? 0;
        var Transom1Thickness = $('#Transom1Thickness').val() ?? 0;
        var Transom2Thickness = $('#Transom2Thickness').val() ?? 0;
        var Transom3Thickness = $('#Transom3Thickness').val() ?? 0;

        var MullionWidthPoint1 = $('#MullionWidthPoint1').val() ?? 0;
        var MullionWidthPoint2 = $('#MullionWidthPoint2').val() ?? 0;
        var MullionWidthPoint3 = $('#MullionWidthPoint3').val() ?? 0;
        var Mullion1Thickness = $('#Mullion1Thickness').val() ?? 0;
        var Mullion2Thickness = $('#Mullion2Thickness').val() ?? 0;
        var Mullion3Thickness = $('#Mullion3Thickness').val() ?? 0;

        var TransomQuantity = $('#TransomQuantity').val() ?? 0;
        var MullionQuantity = $('#MullionQuantity').val() ?? 0;

        var SinglePaneValue = document.getElementById('SinglePane-value');
        if(SinglePaneValue != null  && isstatus == true){
            SinglePaneValue = $("#SinglePane-value").data("value");
            if(SinglePaneValue != ""){
                SinglePane = SinglePaneValue;
            }
        }
        console.log(FrameThickness, FrameDepth, TransomHeight1, TransomThickness);

        var frameWidth = parseInt(SOWidth) - parseInt(Tolerance) - parseInt(Tolerance);
        var frameHeight = parseInt(SOHeight) - parseInt(Tolerance) - parseInt(Tolerance);

        var TransomHeightPoint4 = 0;
        var MullionWidthPoint4 = 0;

        if(TransomQuantity == 3){
            TransomHeightPoint4 = parseInt(frameHeight) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(TransomHeightPoint1) - parseInt(TransomHeightPoint2) - parseInt(TransomHeightPoint3) - parseInt(Transom1Thickness) - parseInt(Transom2Thickness) - parseInt(Transom3Thickness);
        }

        if(MullionQuantity == 3){
            MullionWidthPoint4 = parseInt(frameWidth) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(MullionWidthPoint1) - parseInt(MullionWidthPoint2) - parseInt(MullionWidthPoint3) - parseInt(Mullion1Thickness) - parseInt(Mullion2Thickness) - parseInt(Mullion3Thickness);
        }

        var GlassPane1Width = parseInt(frameWidth) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(MullionWidthPoint2) - parseInt(MullionWidthPoint3) - parseInt(MullionWidthPoint4) - parseInt(Mullion1Thickness) - parseInt(Mullion2Thickness) - parseInt(Mullion3Thickness);

        var GlassPane1Height = parseInt(frameHeight) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(TransomHeightPoint2) - parseInt(TransomHeightPoint3) - parseInt(TransomHeightPoint4) - parseInt(Transom1Thickness) - parseInt(Transom2Thickness) - parseInt(Transom3Thickness);

        var GlassPane2Width = parseInt(frameWidth) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(MullionWidthPoint1) - parseInt(MullionWidthPoint3) - parseInt(MullionWidthPoint4) - parseInt(Mullion1Thickness) - parseInt(Mullion2Thickness) - parseInt(Mullion3Thickness);

        var GlassPane2Height = parseInt(frameHeight) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(TransomHeightPoint2) - parseInt(TransomHeightPoint3) - parseInt(TransomHeightPoint4) - parseInt(Transom1Thickness) - parseInt(Transom2Thickness) - parseInt(Transom3Thickness);

        var GlassPane3Width = parseInt(frameWidth) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(MullionWidthPoint1) - parseInt(MullionWidthPoint2) - parseInt(MullionWidthPoint4) - parseInt(Mullion1Thickness) - parseInt(Mullion2Thickness) - parseInt(Mullion3Thickness);

        var GlassPane3Height = parseInt(frameHeight) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(TransomHeightPoint2) - parseInt(TransomHeightPoint3) - parseInt(TransomHeightPoint4) - parseInt(Transom1Thickness) - parseInt(Transom2Thickness) - parseInt(Transom3Thickness);

        var GlassPane4Width = parseInt(frameWidth) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(MullionWidthPoint1) - parseInt(MullionWidthPoint2) - parseInt(MullionWidthPoint4) - parseInt(Mullion1Thickness) - parseInt(Mullion2Thickness) - parseInt(Mullion3Thickness);

        var GlassPane4Height = parseInt(frameHeight) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(TransomHeightPoint2) - parseInt(TransomHeightPoint3) - parseInt(TransomHeightPoint4) - parseInt(Transom1Thickness) - parseInt(Transom2Thickness) - parseInt(Transom3Thickness);

        var GlassPane5Width = parseInt(frameWidth) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(MullionWidthPoint2) - parseInt(MullionWidthPoint3) - parseInt(MullionWidthPoint4) - parseInt(Mullion1Thickness) - parseInt(Mullion2Thickness) - parseInt(Mullion3Thickness);

        var GlassPane5Height = parseInt(frameHeight) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(TransomHeightPoint1) - parseInt(TransomHeightPoint3) - parseInt(TransomHeightPoint4) - parseInt(Transom1Thickness) - parseInt(Transom2Thickness) - parseInt(Transom3Thickness);

        var GlassPane6Width = parseInt(frameWidth) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(MullionWidthPoint1) - parseInt(MullionWidthPoint3) - parseInt(MullionWidthPoint4) - parseInt(Mullion1Thickness) - parseInt(Mullion2Thickness) - parseInt(Mullion3Thickness);

        var GlassPane6Height = parseInt(frameHeight) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(TransomHeightPoint1) - parseInt(TransomHeightPoint3) - parseInt(TransomHeightPoint4) - parseInt(Transom1Thickness) - parseInt(Transom2Thickness) - parseInt(Transom3Thickness);

        var GlassPane7Width = parseInt(frameWidth) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(MullionWidthPoint1) - parseInt(MullionWidthPoint2) - parseInt(MullionWidthPoint4) - parseInt(Mullion1Thickness) - parseInt(Mullion2Thickness) - parseInt(Mullion3Thickness);

        var GlassPane7Height = parseInt(frameHeight) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(TransomHeightPoint1) - parseInt(TransomHeightPoint3) - parseInt(TransomHeightPoint4) - parseInt(Transom1Thickness) - parseInt(Transom2Thickness) - parseInt(Transom3Thickness);

        var GlassPane8Width = parseInt(frameWidth) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(MullionWidthPoint1) - parseInt(MullionWidthPoint2) - parseInt(MullionWidthPoint3) - parseInt(Mullion1Thickness) - parseInt(Mullion2Thickness) - parseInt(Mullion3Thickness);

        var GlassPane8Height = parseInt(frameHeight) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(TransomHeightPoint1) - parseInt(TransomHeightPoint3) - parseInt(TransomHeightPoint4) - parseInt(Transom1Thickness) - parseInt(Transom2Thickness) - parseInt(Transom3Thickness);

        var GlassPane9Width = parseInt(frameWidth) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(MullionWidthPoint2) - parseInt(MullionWidthPoint3) - parseInt(MullionWidthPoint4) - parseInt(Mullion1Thickness) - parseInt(Mullion2Thickness) - parseInt(Mullion3Thickness);

        var GlassPane9Height = parseInt(frameHeight) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(TransomHeightPoint1) - parseInt(TransomHeightPoint2) - parseInt(TransomHeightPoint4) - parseInt(Transom1Thickness) - parseInt(Transom2Thickness) - parseInt(Transom3Thickness);

        var GlassPane10Width = parseInt(frameWidth) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(MullionWidthPoint1) - parseInt(MullionWidthPoint3) - parseInt(MullionWidthPoint4) - parseInt(Mullion1Thickness) - parseInt(Mullion2Thickness) - parseInt(Mullion3Thickness);

        var GlassPane10Height = parseInt(frameHeight) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(TransomHeightPoint1) - parseInt(TransomHeightPoint2) - parseInt(TransomHeightPoint4) - parseInt(Transom1Thickness) - parseInt(Transom2Thickness) - parseInt(Transom3Thickness);

        var GlassPane11Width = parseInt(frameWidth) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(MullionWidthPoint1) - parseInt(MullionWidthPoint2) - parseInt(MullionWidthPoint4) - parseInt(Mullion1Thickness) - parseInt(Mullion2Thickness) - parseInt(Mullion3Thickness);

        var GlassPane11Height = parseInt(frameHeight) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(TransomHeightPoint1) - parseInt(TransomHeightPoint2) - parseInt(TransomHeightPoint4) - parseInt(Transom1Thickness) - parseInt(Transom2Thickness) - parseInt(Transom3Thickness);

        var GlassPane12Width = parseInt(frameWidth) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(MullionWidthPoint1) - parseInt(MullionWidthPoint2) - parseInt(MullionWidthPoint3) - parseInt(Mullion1Thickness) - parseInt(Mullion2Thickness) - parseInt(Mullion3Thickness);

        var GlassPane12Height = parseInt(frameHeight) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(TransomHeightPoint1) - parseInt(TransomHeightPoint2) - parseInt(TransomHeightPoint4) - parseInt(Transom1Thickness) - parseInt(Transom2Thickness) - parseInt(Transom3Thickness);

        var GlassPane13Width = parseInt(frameWidth) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(MullionWidthPoint2) - parseInt(MullionWidthPoint3) - parseInt(MullionWidthPoint4) - parseInt(Mullion1Thickness) - parseInt(Mullion2Thickness) - parseInt(Mullion3Thickness);

        var GlassPane13Height = parseInt(frameHeight) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(TransomHeightPoint1) - parseInt(TransomHeightPoint2) - parseInt(TransomHeightPoint3) - parseInt(Transom1Thickness) - parseInt(Transom2Thickness) - parseInt(Transom3Thickness);

        var GlassPane14Width = parseInt(frameWidth) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(MullionWidthPoint1) - parseInt(MullionWidthPoint3) - parseInt(MullionWidthPoint4) - parseInt(Mullion1Thickness) - parseInt(Mullion2Thickness) - parseInt(Mullion3Thickness);

        var GlassPane14Height = parseInt(frameHeight) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(TransomHeightPoint1) - parseInt(TransomHeightPoint2) - parseInt(TransomHeightPoint3) - parseInt(Transom1Thickness) - parseInt(Transom2Thickness) - parseInt(Transom3Thickness);

        var GlassPane15Width = parseInt(frameWidth) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(MullionWidthPoint1) - parseInt(MullionWidthPoint2) - parseInt(MullionWidthPoint4) - parseInt(Mullion1Thickness) - parseInt(Mullion2Thickness) - parseInt(Mullion3Thickness);

        var GlassPane15Height = parseInt(frameHeight) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(TransomHeightPoint1) - parseInt(TransomHeightPoint2) - parseInt(TransomHeightPoint3) - parseInt(Transom1Thickness) - parseInt(Transom2Thickness) - parseInt(Transom3Thickness);

        var GlassPane16Width = parseInt(frameWidth) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(MullionWidthPoint1) - parseInt(MullionWidthPoint2) - parseInt(MullionWidthPoint3) - parseInt(Mullion1Thickness) - parseInt(Mullion2Thickness) - parseInt(Mullion3Thickness);

        var GlassPane16Height = parseInt(frameHeight) - parseInt(FrameThickness) - parseInt(FrameThickness) - parseInt(TransomHeightPoint1) - parseInt(TransomHeightPoint2) - parseInt(TransomHeightPoint3) - parseInt(Transom1Thickness) - parseInt(Transom2Thickness) - parseInt(Transom3Thickness);


        var TransomWidth1 = parseInt(frameWidth) - parseInt(FrameThickness) - parseInt(FrameThickness);
        var MullionHeight1 = parseInt(frameHeight) - parseInt(FrameThickness) - parseInt(FrameThickness);


        $('#FrameWidth').val(frameWidth);
        $('#FrameHeight').val(frameHeight);

        console.log(parseInt(frameWidth) , parseInt(FrameThickness),parseInt(FrameThickness),parseInt(MullionWidthPoint2),parseInt(MullionWidthPoint3),parseInt(MullionWidthPoint4),parseInt(Mullion1Thickness),parseInt(Mullion2Thickness),parseInt(Mullion3Thickness))

        $('#GlassPane1Width').val(GlassPane1Width);
        $('#GlassPane1Height').val(GlassPane1Height);
        $('#GlassPane2Width').val(GlassPane2Width);
        $('#GlassPane2Height').val(GlassPane2Height);
        $('#GlassPane3Width').val(GlassPane3Width);
        $('#GlassPane3Height').val(GlassPane3Height);
        $('#GlassPane4Width').val(GlassPane4Width);
        $('#GlassPane4Height').val(GlassPane4Height);
        $('#GlassPane5Width').val(GlassPane5Width);
        $('#GlassPane5Height').val(GlassPane5Height);
        $('#GlassPane6Width').val(GlassPane6Width);
        $('#GlassPane6Height').val(GlassPane6Height);
        $('#GlassPane7Width').val(GlassPane7Width);
        $('#GlassPane7Height').val(GlassPane7Height);
        $('#GlassPane8Width').val(GlassPane8Width);
        $('#GlassPane8Height').val(GlassPane8Height);
        $('#GlassPane9Width').val(GlassPane9Width);
        $('#GlassPane9Height').val(GlassPane9Height);
        $('#GlassPane10Width').val(GlassPane10Width);
        $('#GlassPane10Height').val(GlassPane10Height);
        $('#GlassPane11Width').val(GlassPane11Width);
        $('#GlassPane11Height').val(GlassPane11Height);
        $('#GlassPane12Width').val(GlassPane12Width);
        $('#GlassPane12Height').val(GlassPane12Height);
        $('#GlassPane13Width').val(GlassPane13Width);
        $('#GlassPane13Height').val(GlassPane13Height);
        $('#GlassPane14Width').val(GlassPane14Width);
        $('#GlassPane14Height').val(GlassPane14Height);
        $('#GlassPane15Width').val(GlassPane15Width);
        $('#GlassPane15Height').val(GlassPane15Height);
        $('#GlassPane16Width').val(GlassPane16Width);
        $('#GlassPane16Height').val(GlassPane16Height);


        $('#TransomWidth1').val(TransomWidth1);
        $('#MullionHeight1').val(MullionHeight1);
        $('#SubFrameBottomWidth').val(parseInt(frameWidth));

        $('#TransomHeightPoint4').val(parseInt(TransomHeightPoint4));
        $('#MullionWidthPoint4').val(parseInt(MullionWidthPoint4));

        const FireRating = $('#FireRating').val();     // FD30

        if(FireRating != '' && frameWidth != '' && frameHeight != '' && SOWidth != '' && SOHeight != ''){
            $.ajax({
                url: $("#glazingFilterScreen").text(),
                method:"POST",
                dataType:"Json",
                data:{frameWidth:frameWidth,frameHeight:frameHeight,FireRating:FireRating,SinglePane: SinglePane ,SelectedValue: SelectedValue , _token:$("#_token").val()},
                success: function(result){
                    if(result.status == 'ok'){
                        const hiddenvalue = $('#SelectedValuehidden').val();
                        $('#GlazingSystem').empty().append(result.data);
                        if (hiddenvalue && hiddenvalue.trim() !== "") {
                            $('#GlazingSystem').val(hiddenvalue).change();
                        }
                        $('#SOWidth').css({'border':'1px solid #ced4da'});
                        $('#SOHeight').css({'border':'1px solid #ced4da'});

                    } else if(result.status == 'error2'){
                        $('#GlazingSystem').empty('');
                        swal('Warning',result.msg);
                        $('#SOWidth').css({'border':'1px solid red'});
                        $('#SOHeight').css({'border':'1px solid red'});
                    }
                }
            });
        }
        let elements = $(this);
        render(elements);
    }

    $(document).on('click', '#submit', function(e) {
        let allAreFilled = true;
        let RequiredFields = "";
        let label = '';
        let parentid = '';

        document.getElementById("itemForm").querySelectorAll(".form-control").forEach(function(i) {
            $(".optionItem a").css({
                'background': '#3b86ff'
            });
            $('#' + i.id).css({
                'border': '1px solid #ced4da'
            });
        });

        document.getElementById("itemForm").querySelectorAll("[required]").forEach(function(i) {
            if (!i.value) {
                parentid = $('#' + i.id).parents().eq(6).parent('div').attr('id');
                $("a[href='#" + parentid + "']").css({
                    'background': 'red'
                });
                $('#' + i.id).css({
                    'border': '1px solid red'
                });
                label = $('#' + i.id).siblings('label').clone().children().remove().end().text();
                RequiredFields += '<li><i class="fas fa-exclamation-triangle"></i> ' + label +
                    ' field is required.</li>';
                allAreFilled = false;
            }
        })
        if (!allAreFilled) {
            $('.error').empty().html(
                `<div class="alert notify alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="fas fa-exclamation"></i> Alert!</h5>
                            <ul>
                                ` + RequiredFields + `
                            </ul>
                        </div>`
            );
            setTimeout(() => {
                $('.error').html('')
            }, 10000);
            return false;
        }

        var data = $('#itemForm').serialize();
        var data2 = JSON.stringify($('#itemForm').serializeArray());
        $('.loader').empty().css({
                'display': 'block'
        });
        $.ajax({
            url: "{{route('items/ScreenStore')}}",
            type: 'POST',
            data: data,
            datatype: "json",
            success: function(res) {
                if (res.status == 'error') {
                    $('.error').empty().html(
                        `<div class="alert notify alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="fas fa-exclamation"></i> Alert!</h5>
                            <ul>
                                ` + res.errors + `
                            </ul>
                        </div>`);
                    setTimeout(() => {
                        $('.error').html('')
                    }, 10000);
                    $('.loader').empty().css({
                        'display': 'none'
                    });
                } else if (res.status == 'success') {
                    $('.loader').empty().css({
                            'display': 'none'
                        });
                    window.location.href = "{{url('/')}}/" + res.url;
                } else if (res.status == 'success1') {
                    window.location.replace(document.referrer);
                }
            }
        });
    });
</script>


@endsection
