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
.custom-checkbox {
        margin: 2px -4px 10px 12px;
        border: 1px solid #ced4da;
        height: 15px;
        width: 15px;
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
        <script>
        function Tooltip(tooltipValue) {
            let TooltipCode2 =
                '<i class="fa fa-info-circle field_info tooltip Species_icon" aria-hidden="true"><span class="tooltiptext info_tooltip">' +
                tooltipValue + '</span></i>';
            return TooltipCode2;
        }
        </script>

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
                                            href="#door-dimensions-n-door-leaf-section">Door Dimensions & Door Leaf</a>
                                        <input type="hidden" value="432px">
                                    </li>
                                    <li class="optionItem">
                                        <a class="btn btn-primary" data-toggle="tab" href="#vision-panel-section">Vision
                                            Panel</a>
                                        <input type="hidden" value="988px">
                                    </li>
                                    <li class="optionItem framehideshow">
                                        <a class="btn btn-primary" data-toggle="tab" href="#frame-section">Frame</a>
                                        <input type="hidden" value="2039px">
                                    </li>
                                    <li class="optionItem framehideshow">
                                        <a class="btn btn-primary" data-toggle="tab" href="#over-panel-section">Overpanel/Fanlight</a>
                                        <input type="hidden" value="2656px">
                                    </li>
                                    <li class="optionItem framehideshow">
                                        <a class="btn btn-primary" data-toggle="tab" href="#side-light-section">Side
                                            Light</a>
                                        <input type="hidden" value="2958px">
                                    </li>
                                    <li class="optionItem">
                                        <a class="btn btn-primary" data-toggle="tab"
                                            href="#lipping-and-intumescent-section">Lipping & Intumescent</a>
                                        <input type="hidden" value="3458px">
                                    </li>
                                    <li class="optionItem">
                                        <a class="btn btn-primary" data-toggle="tab"
                                            href="#acoustics-section">Acoustics</a>
                                        <input type="hidden" value="3892px">
                                    </li>
                                    <li class="optionItem framehideshow">
                                        <a class="btn btn-primary" data-toggle="tab"
                                            href="#architrave-section">Architrave</a>
                                        <input type="hidden" value="3840px">
                                    </li>
                                    <!-- <li class="optionItem">
                                        <a class="btn btn-primary" data-toggle="tab"
                                            href="#transport-section">Transport</a>
                                    </li> -->
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
                        <input type="hidden" name="pageIdentity" id="pageIdentity" value="2">
                        <input type="hidden" name="version_id" value="<?= (!is_null($versionId))?$versionId:0; ?>">
                        <input type="hidden" name="SvgImage" value="" />
                        @if(in_array(Auth::user()->UserType, ['1', '2', '3']) && isset($quotation->QuotationStatus) && $quotation->QuotationStatus != 'Ordered' && empty($Item["itemId"]))
                            <div class="float-right">
                                <button type="button" id="default" onclick="default()" class="btn btn-primary">
                                    <i class="fas fa-paper-plane"></i> Import Default
                                </button>
                            </div>
                        @endif
                        <div class="tab-content">
                            <div id="main-options-section" class="tab-pane active">
                                @include("Items.Halspan.HalspanDoorModules.MainOptions")
                            </div>

                            <div id="door-dimensions-n-door-leaf-section" class="tab-pane fade">
                                @include("Items.Halspan.HalspanDoorModules.DoorDimensionsAndDoorLeaf")
                            </div>

                            <div id="vision-panel-section" class="tab-pane fade">
                                @include("Items.Halspan.HalspanDoorModules.VisionPanel")
                            </div>

                            <div id="frame-section" class="tab-pane fade framehideshow">
                                @include("Items.Halspan.HalspanDoorModules.Frame")
                            </div>

                            <div id="over-panel-section" class="tab-pane fade framehideshow">
                                @include("Items.Halspan.HalspanDoorModules.OverPanel")
                            </div>

                            <div id="side-light-section" class="tab-pane fade framehideshow">
                                @include("Items.Halspan.HalspanDoorModules.SideLight")
                            </div>

                            <div id="lipping-and-intumescent-section" class="tab-pane fade">
                                @include("Items.Halspan.HalspanDoorModules.LippingAndIntumescent")
                            </div>

                            <div id="acoustics-section" class="tab-pane fade">
                                @include("Items.Halspan.HalspanDoorModules.Accoustics")

                            </div>

                            <div id="architrave-section" class="tab-pane fade framehideshow">
                                @include("Items.Halspan.HalspanDoorModules.Architrave")
                            </div>

                            <div id="transport-section" class="tab-pane fade">
                                <!-- @include("Items.Halspan.HalspanDoorModules.Transport") -->
                            </div>
                        </div>



                        <div hidden id="overpanel-glass-filter">{{route('items/overpanel-glass-filter')}}</div>
                        <div hidden id="overpanel-glass-type-filter">{{route('items/overpanel-glass-type-filter')}}</div>
                        <div hidden id="glazing-system-filter">{{route('items/glazing-system-filter')}}</div>
                        <div hidden id="architrave-system-filter">{{route('items/architrave-system-filter')}}</div>
                        <div hidden id="fire-rating-filter">{{route('items/fire-rating-filter')}}</div>
                        <div hidden id="glass-glazing-filter">{{route('items/glass-glazing-filter')}}</div>
                        <div hidden id="glazing-filter">{{route('items/glazing-filter')}}</div>
                        <div hidden id="glazing-beads-filter">{{route('items/glazing-beads-filter')}}</div>
                        <div hidden id="glass-type-filter">{{route('items/glass-type-filter')}}</div>
                        <div hidden id="glazing-thikness-filter">{{route('items/glazing-thikness-filter')}}</div>
                        <div hidden id="frame-material-filter">{{route('items/frame-material-filter')}}</div>
                        <div hidden id="scallopped-lipping-thickness">{{route('items/scallopped-lipping-thickness')}}
                        </div>
                        <div hidden id="flat-lipping-thickness">{{route('items/flat-lipping-thickness')}}</div>
                        <div hidden id="rebated-lipping-thickness">{{route('items/rebated-lipping-thickness')}}</div>
                        <div hidden id="door-thickness-filter">{{route('items/door-thickness-filter')}}</div>
                        <div hidden id="door-leaf-face-value-filter">{{route('items/door-leaf-face-value-filter')}}
                        </div>
                        <div hidden id="ral-color-filter">{{route('items/ral-color-filter')}}</div>
                        <div hidden id="filter-iron-mongery-category">
                            {{route('ironmongery-info/filter-iron-mongery-category')}}
                        </div>
                        <div hidden id="url">{{url('/')}}</div>
                        <div hidden id="get-handing-options">{{route('items/get-handing-options')}}</div>
                        <div hidden id="Filterintumescentseals">{{route('Filterintumescentseals')}}</div>
                        <div hidden id="opGlassTypeFilterUrl">{{route('opGlassTypeFilterUrl')}}</div>
                        <div hidden id="doorStandardPrice">{{route('doorStandardPrice')}}</div>
                        <div hidden id="IronmongeryIDPrice">{{route('IronmongeryIDPrice')}}</div>
                        <div hidden id="generalLabourCost">{{route('generalLabourCost')}}</div>
                        <div hidden id="FrameCost">{{route('FrameCost')}}</div>
                        <div hidden id="fanlightBeadsFilter">{{route('options/filterFanLightBeading')}}</div>
                        <div hidden id="sidelightBeadsFilter">{{route('options/filterSideLightBeading')}}</div>
                        <div hidden id="liping-glazing-system-filter">{{route('items/liping-glazing-system-filter')}}</div>
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
                        <li class="optionItem">
                            <a class="btn btn-primary" data-toggle="tab" href="#BuildOfMaterial">
                                <i class="fa fa-book" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="optionItem">
                            <a class="btn btn-primary" data-toggle="tab" href="#doorPrice" id="doorPriceCalculate">
                                <i class="fa fa-gbp" aria-hidden="true"></i>
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
                        <li class="optionItem d-flex align-items-center">
                            <div class="form-check" style="margin-left: 10px;">
                                <input type="checkbox" class="form-check-input" id="frameonoff" @if(isset($Item["FrameOnOff"]) && $Item["FrameOnOff"] == 1){{ 'checked' }}@else{{''}}@endif>
                                <label class="form-check-label cursor-pointer" for="frameonoff">Frame  On/Off</label>
                                <input type="hidden" name="FrameOnOff" id="withoutFrameId" value="@if(isset($Item["FrameOnOff"])){{$Item["FrameOnOff"]}}@else{{''}}@endif">
                            </div>
                        </li>
                    </ul>
                    @if(Auth::user()->UserType=='1' ||Auth::user()->UserType=='2' || Auth::user()->UserType=='3' || Auth::user()->UserType=='4' || Auth::user()->UserType=='5')
                    @if(isset($quotation->QuotationStatus))
                    @if($quotation->QuotationStatus != 'Ordered')
                    <div class="float-right">
                        <button type="button" id="submit" class="btn btn-success active">
                            <i class="fas fa-paper-plane"></i> @if(!empty($Item["itemId"])){{ 'Update Now' }} @else
                            {{'Submit Now'}} @endif
                        </button>
                    </div>
                    @endif
                    @endif
                    @endif
                </div>
                </form>
                <div class="tab-content" id="opDiv">
                    <div id="door" class="tab-pane active">
                        <div id='container'></div>
                    </div>
                    <div id="BuildOfMaterial" class="tab-pane table-responsive">
                        {{--<table id="BuildOfMaterialDetails" class="table table-bordered table-striped"></table>--}}
                        @include("Items.Halspan.HalspanBuildOfMaterialForCadDoor")

                    </div>
                    <div id="doorPrice" class="tab-pane table-responsive" >
                        @include("Items.Halspan.HalspanDoorPriceForCadDoor")

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="{{url('/')}}/Halspan/halspan-cad-door-configuration.js"></script>
<script src="{{url('/')}}/Halspan/halspan-build-of-material-for-cad-door.js"></script>
<script src="{{asset('Halspan/halspan-custome-rules.js')}}"></script>
<script src="{{asset('Halspan/halspan-change-event-calculation.js')}}"></script>
<script src="{{asset('js/common.js')}}"></script>

@if(!empty($Item))
@foreach($Item as $key => $val)
<div id="{{$key}}-value" data-value="{{$val}}" hidden=""></div>
@endforeach
@endif


@if(!empty($default))
    @foreach($default as $key => $item)
        <div id="{{ $key }}-import" data-value="{{ $item }}" hidden=""></div>
    @endforeach
    <div id="Handing-value" data-value="" hidden=""></div>
    <div id="DoorLeafFacingValue-value" data-value="" hidden=""></div>
    <div id="DoorLeafFinish-value" data-value="" hidden=""></div>
    <div id="LippingSpecies-value" data-value="" hidden=""></div>
    <div id="FrameMaterial-value" data-value="" hidden=""></div>
    {{--  <div id="FrameFinishColor-value" data-value="" hidden=""></div>  --}}
@endif


@endsection


@section("js")
<script>
    $(document).on('click', '#default', function(e) {
        defaultimport();
    });

    function defaultimport(){
        $("select[name=intumescentLeafType]").val(7).trigger("change");
        var handingImportValue = $("#Handing-import").data("value");
        var frameCostuction = $("#DoorFrameConstruction-import").data("value");
        $("#Handing-value").data("value", handingImportValue);
        $("#DoorLeafFacingValue-value").data("value",$("#DoorLeafFacingValue-import").data("value"));
        $("#DoorLeafFinish-value").data("value",$("#DoorLeafFinish-import").data("value"));
        $("#FrameFinishColor-value").val($("#FrameFinishColor-import").data("value"));
        $("select[name=fireRating]").val($("#FireRating-import").data("value")).trigger("change");
        FireRatingChange();
        $("select[name=doorsetType]").val($("#DoorsetType-import").data("value")).trigger("change");
        $("select[name=swingType]").val($("#SwingType-import").data("value")).trigger("change");
        $("select[name=latchType]").val($("#LatchType-import").data("value")).trigger("change");
        DoorSetTypeChange();
        filterHandling();
        //$("select[name=Handing]").val($("#Handing-import").data("value")).trigger("change");
        $("select[name=OpensInwards]").val($("#OpensInwards-import").data("value")).trigger("change");
        $("#tollerance").val($("#Tollerance-import").data("value")).trigger("change");
        $("#floorFinish").val($("#FloorFinish-import").data("value"));
        $("#gap").val($("#GAP-import").data("value"));
        $("#frameThickness").val($("#FrameThickness-import").data("value"));
        $("select[name=doorLeafFacing]").val($("#DoorLeafFacing-import").data("value"));
        const doorLeafFacingValue_C = $("#DoorLeafFacing-import").data("value");
        if(doorLeafFacingValue_C == 'CS_acrovyn'){
            IntumescentSeals();
        }else if($('#doorLeafFacing').val() == 'Laminate'){
            DoorLeafFacingChange(true,true);
        }else{
            DoorLeafFacingChange();
        }
        if($("#Leaf1VisionPanel-import").data("value") == 'Yes'){
            $("select[name=leaf1VisionPanel]").val($("#Leaf1VisionPanel-import").data("value")).trigger('change');
            $("select[name=leaf1VisionPanelShape]").val($("#Leaf1VisionPanelShape-import").data("value"));
            if($("#VisionPanelQuantity-import").data("value") == 1){
                $("select[name=visionPanelQuantity]").val($("#VisionPanelQuantity-import").data("value"));
                $("#distanceBetweenVPs").removeAttr('min', '80');
            }
            else{
                $("select[name=visionPanelQuantity]").val($("#VisionPanelQuantity-import").data("value")).trigger('change');
            }
            $("select[name=AreVPsEqualSizes]").val($("#AreVPsEqualSizesForLeaf1-import").data("value")).trigger('change');
            $("#distanceFromTopOfDoor").val($("#DistanceFromtopOfDoor-import").data("value"));
            $("#distanceFromTheEdgeOfDoor").val($("#DistanceFromTheEdgeOfDoor-import").data("value"));
            $("#distanceBetweenVPs").val($("#DistanceBetweenVPs-import").data("value"));
            $("#vP1Width").val($("#Leaf1VPWidth-import").data("value"));
            $("#vP1Height1").val($("#Leaf1VPHeight1-import").data("value"));
            $("#vP1Height2").val($("#Leaf1VPHeight2-import").data("value"));
            $("#vP1Height3").val($("#Leaf1VPHeight3-import").data("value"));
            $("#vP1Height4").val($("#Leaf1VPHeight4-import").data("value"));
            $("#vP1Height5").val($("#Leaf1VPHeight5-import").data("value"));
            $("#leaf1VpAreaSizeM2").val($("#Leaf1VPAreaSizem2-import").data("value"));
        }
        if($("#Leaf2VisionPanel-import").data("value") == "Yes"){
            $("select[name=leaf2VisionPanel]").val($("#Leaf2VisionPanel-import").data("value")).trigger('change');
            $("select[name=vpSameAsLeaf1]").val($("#sVPSameAsLeaf1-import").data("value")).trigger('change');
            $("select[name=visionPanelQuantityforLeaf2]").val($("#Leaf2VisionPanelQuantity-import").data("value")).trigger('change');
            $("select[name=AreVPsEqualSizesForLeaf2]").val($("#AreVPsEqualSizesForLeaf2-import").data("value")).trigger('change');
            $("#distanceFromTopOfDoorforLeaf2").val($("#DistanceFromTopOfDoorForLeaf2-import").data("value"));
            $("#distanceFromTheEdgeOfDoorforLeaf2").val($("#DistanceFromTheEdgeOfDoorforLeaf2-import").data("value"));
            $("#distanceBetweenVPsforLeaf2").val($("#DistanceBetweenVp-import").data("value"));
            $("#vP2Width").val($("#Leaf2VPWidth-import").data("value"));
            $("#vP2Height1").val($("#Leaf2VPHeight1-import").data("value"));
            $("#vP2Height2").val($("#Leaf2VPHeight2-import").data("value"));
            $("#vP2Height3").val($("#Leaf2VPHeight3-import").data("value"));
            $("#vP2Height4").val($("#Leaf2VPHeight4-import").data("value"));
            $("#vP2Height5").val($("#Leaf2VPHeight5-import").data("value"));
        }
        $("#FrameMaterial-value").data("value",$("#FrameMaterial-import").data("value"));
        $("#frameMaterialNew").val($("#FrameMaterial-import").data("value"));
        $("select[name=frameType]").val($("#FrameType-import").data("value"));
        $("#frameDepth").val($("#FrameDepth-import").data("value"));
        $("select[name=frameFinish]").val($("#FrameFinish-import").data("value"));
        if($("#FrameFinish-import").data("value") == 'Painted_Finish'){
            FrameFinishChange(false ,  'framefinish');
        }
        //$("select[name=framefinishColor]").val($("#FrameFinishColor-import").data("value"));
        $("input[name=frameCostuction]").val($("#DoorFrameConstruction-import").data("value"));
        DoorFrameConstruction('#frameCostuction',frameCostuction,frameCostuction.split('_').join(' '))
        $("select[name=lippingType]").val($("#LippingType-import").data("value"));
        $("select[name=lippingThickness]").val($("#LippingThickness-import").data("value"));
        $("#LippingSpecies-value").data("value",$("#LippingSpecies-import").data("value"));
        $("#lippingSpeciesid").val($("#LippingSpecies-import").data("value"));
        $("select[name=intumescentSealType]").val($("#IntumescentLeapingSealType-import").data("value"));
        $("select[name=intumescentSealLocation]").val($("#IntumescentLeapingSealLocation-import").data("value"));
        $("select[name=intumescentSealColor]").val($("#IntumescentLeapingSealColor-import").data("value"));
        $("select[name=Architrave]").val($("#Architrave-import").data("value")).trigger("change");
        $("#architraveMaterialNew").val($("#ArchitraveMaterial-import").data("value"));
        $("select[name=architraveType]").val($("#ArchitraveType-import").data("value"));
        $("#architraveWidth").val($("#ArchitraveWidth-import").data("value"));
        $("#architraveHeight").val($("#ArchitraveHeight-import").data("value"));
        $("select[name=architraveFinish]").val($("#ArchitraveFinish-import").data("value"));
        $("#architraveFinishcolor").val($("#ArchitraveFinishColor-import").data("value"));
        $("select[name=architraveSetQty]").val($("#ArchitraveSetQty-import").data("value"));
        architrave(1);

        let framTypeValue = $("#FrameType-import").data("value");
        let value = $("#FireRating-import").data("value");
        let newMin;
        if (framTypeValue == "Plant_on_Stop") {
            newMin = 14;
            $("#plantonStopWidth").attr('min', '14');
            $("#plantonStopHeight").attr('min', '12');
            $("#plantonStopWidthLabel").text(`Plant on Stop Width (min ${newMin})`);
            $("#plantonStopWidth").attr({ 'readonly': false, 'required': true });
            $("#plantonStopHeight").attr({ 'readonly': false, 'required': true });

            $("#rebatedHeight").removeAttr('min', '12');
            $("#rebatedHeight").removeAttr('min', '12');
            $("#rebatedWidth").removeAttr('min', '35');
            $("#rebatedWidth").removeAttr('min', '44');
            $("#rebatedWidth").removeAttr('min', '54');
            $("#rebatedWidth").attr({ 'readonly': true, 'required': false }).val(0);
            $("#rebatedHeight").attr({ 'readonly': true, 'required': false }).val(0);

            if(value == 'NFR' || value == 'FD30'){
                $("#ScallopedWidth").removeAttr('min', '22');
            }
            if(value == 'FD60'){
                $("#ScallopedWidth").removeAttr('min', '28');
            }
            $("#ScallopedHeight").removeAttr('max', '5');
            $("#ScallopedHeight").removeAttr('min', '12');
            $("#ScallopedWidth").removeAttr('min', '32');
            $("#ScallopedHeight").attr({ 'readonly': true, 'required': false }).val(0);
            $("#ScallopedWidth").attr({ 'readonly': true, 'required': false }).val(0);

            $("#frameTypeDimensions").val('').attr('readonly', false);
            $("#rebatedWidth-section,#rebatedHeight-section,#ScallopedWidth-section,#ScallopedHeight-section").removeClass("table_row_show");
            $("#rebatedWidth-section,#rebatedHeight-section,#ScallopedWidth-section,#ScallopedHeight-section").addClass("table_row_hide");
            FramePrice('Plant_on_Stop');
            // frameprice('Plant_on_Stop');
        } else if(framTypeValue == "Scalloped"){
            if(value == 'NFR' || value == 'FD30'){
                newMin = 22;
                $("#ScallopedWidth").attr('min', '22');
            }
            if(value == 'FD60'){
                newMin = 28;
                $("#ScallopedWidth").attr('min', '28');
            }
            // $("#ScallopedHeight").attr('max', '5');
            $("#ScallopedLabel").text(`Scalloped Width (min ${newMin})`);
            $("#ScallopedHeight").attr({ 'readonly': false, 'required': true });
            $("#ScallopedWidth").attr({ 'readonly': false, 'required': true });

            $("#plantonStopWidth").removeAttr('min', '14');
            $("#plantonStopHeight").removeAttr('min', '12');
            $("#plantonStopWidth").attr({ 'readonly': true, 'required': false }).val(0);
            $("#plantonStopHeight").attr({ 'readonly': true, 'required': false }).val(0);

            $("#rebatedHeight").removeAttr('min', '12');
            $("#rebatedHeight").removeAttr('min', '12');
            $("#rebatedWidth").removeAttr('min', '35');
            $("#rebatedWidth").removeAttr('min', '44');
            $("#rebatedWidth").removeAttr('min', '54');
            $("#rebatedWidth").attr({ 'readonly': true, 'required': false }).val(0);
            $("#rebatedHeight").attr({ 'readonly': true, 'required': false }).val(0);

            $("#frameTypeDimensions").val('').attr('readonly', false);
            $("#rebatedWidth-section,#rebatedHeight-section,#plantonStopWidth-section,#plantonStopHeight-section").removeClass("table_row_show");
            $("#rebatedWidth-section,#rebatedHeight-section,#plantonStopWidth-section,#plantonStopHeight-section").addClass("table_row_hide");
        } else if (framTypeValue == "Rebated_Frame") {
            $("#rebatedHeight").attr('min', '12');
                newMin = 32;
            if(value == 'NFR'){
                $("#rebatedWidth").attr('min', '35');
                newMin = 35;
            }
            if(value == 'FD30'){
                $("#rebatedWidth").attr('min', '44');
                newMin = 44;
            }
            if(value == 'FD60'){
                $("#rebatedWidth").attr('min', '54');
                newMin = 54;
            }
            $("#rebatedWidthLabel").text(`Rebated Width (min ${newMin})`);
            $("#rebatedWidth").attr({ 'readonly': false, 'required': true });
            $("#rebatedHeight").attr({ 'readonly': false, 'required': true });

            $("#plantonStopWidth").removeAttr('min', '14');
            $("#plantonStopHeight").removeAttr('min', '12');
            $("#plantonStopWidth").attr({ 'readonly': true, 'required': false }).val(0);
            $("#plantonStopHeight").attr({ 'readonly': true, 'required': false }).val(0);

            if(value  == 'NFR' || value  == 'FD30'){
                $("#ScallopedWidth").removeAttr('min', '22');
            }
            if(value  == 'FD60'){
                $("#ScallopedWidth").removeAttr('min', '28');
            }
            $("#ScallopedHeight").removeAttr('max', '5');
            $("#ScallopedHeight").removeAttr('min', '12');
            $("#ScallopedWidth").removeAttr('min', '32');
            $("#ScallopedHeight").attr({ 'readonly': true, 'required': false }).val(0);
            $("#ScallopedWidth").attr({ 'readonly': true, 'required': false }).val(0);

            $("#frameTypeDimensions").val('').attr('readonly', false);
            $("#plantonStopWidth-section,#plantonStopHeight-section,#ScallopedWidth-section,#ScallopedHeight-section").removeClass("table_row_show");
            $("#plantonStopWidth-section,#plantonStopHeight-section,#ScallopedWidth-section,#ScallopedHeight-section").addClass("table_row_hide");
            FramePrice('Rebated_Frame');
            // frameprice('Rebated_Frame');
        } else {
            $("#rebatedWidth").attr({ 'readonly': true, 'required': false }).val(0);
            $("#rebatedHeight").attr({ 'readonly': true, 'required': false }).val(0);
            $("#ScallopedHeight").attr({ 'readonly': true, 'required': false }).val(0);
            $("#ScallopedWidth").attr({ 'readonly': true, 'required': false }).val(0);
            $("#plantonStopWidth").val(0);
            $("#plantonStopHeight").val(0);
            $("#frameTypeDimensions").val(0).attr('readonly', true);
            $("#plantonStopWidth").attr({ 'readonly': true, 'required': false });
            $("#plantonStopHeight").attr({ 'readonly': true, 'required': false });
            $(".Plant_on_Stop_section").removeClass("table_row_show");
            $(".Plant_on_Stop_section").addClass("table_row_hide");
            $(".Rebated_Frame_section").removeClass("table_row_show");
            $(".Rebated_Frame_section").addClass("table_row_hide");
            $("#plantonStopWidth-section,#plantonStopHeight-section,#ScallopedWidth-section,#ScallopedHeight-section,#rebatedWidth-section,#rebatedHeight-section").removeClass("table_row_show");
            $("#plantonStopWidth-section,#plantonStopHeight-section,#ScallopedWidth-section,#ScallopedHeight-section,#rebatedWidth-section,#rebatedHeight-section").addClass("table_row_hide");
        }
        swingTypeFrameType();

        $(".form-control").each(function(index) {
            const element = $(this);
            setTimeout(function() {
                SetBuildOfMaterial(element);
            }, index * 100); // Delay increases by 100ms for each element
        });
    }




    $('#submit').attr({'disabled': true,"readonly":true });
var BomSettingsJson = JSON.stringify(<?= json_encode($BOMSetting); ?>);
var ColorsJson = JSON.stringify(<?= json_encode($color_data); ?>);
var OptionsJson = JSON.stringify(<?= json_encode($option_data); ?>);
var SelectedOptionsJson = JSON.stringify(<?= json_encode($selected_option_data); ?>);

var intumescentSealArrangementJson = JSON.stringify(<?=json_encode($intumescentSealArrangement); ?>);
var SelectedIntumescentSealArrangementJson = JSON.stringify(<?=json_encode($SelectedIntumescentSealArrangement); ?>);


var LippingSpeciesJson = JSON.stringify(<?= json_encode($lipping_species); ?>);
var SelectedLippingSpeciesJson = JSON.stringify(<?= json_encode($selected_lipping_species); ?>);

var ConfigurableDoorFormulaJson = JSON.stringify(<?= json_encode($ConfigurableDoorFormula); ?>);
var IronmongeryJson = JSON.stringify(<?= json_encode($setIronmongery); ?>);

var possibleSelectedOptionsJson = JSON.stringify(<?=json_encode(\Config::get('constants.PossibleSelectedOptions.SelectedOptionsWithDbSlugKey'))?>);

//var BomDoorCoresJson = JSON.stringify(<?//= json_encode($BOMDoorCores); ?>);

$(document).ready(function() {

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    selectIronMongery();
    labourPrice();
    @if(isset($Item['FireRating']))
    FireRatingChange();
    @endif

    @if(isset($Item['FireRating']) &&  $Item['FireRating'] == 'NFR')
    doorThicknessSelect("{{ $Item['LeafThickness'] }}");
    @endif

    @if(isset($Item['GlassIntegrity']) && !empty($Item['GlassIntegrity']))
    glassTypeFilter("{{$Item['FireRating']}}");
    doorThicknessFilter("{{$Item['FireRating']}}");
    glazingSystemFIlter("{{$Item['FireRating']}}");
    @endif

    @if(isset($Item['SL2GlassIntegrity']) && isset($Item['SL1GlassIntegrity']) && isset($Item['opGlassIntegrity']) && isset($Item['FireRating']))
    doorThicknessFilter("{{$Item['FireRating']}}",true,true,true);
    @elseif(isset($Item['SL1GlassIntegrity']) && isset($Item['opGlassIntegrity']) && isset($Item['FireRating']))
    doorThicknessFilter("{{$Item['FireRating']}}",true,true);
    @elseif(isset($Item['opGlassIntegrity']) && isset($Item['FireRating']))
    doorThicknessFilter("{{$Item['FireRating']}}",true);
    @elseif(isset($Item['FireRating']))
    doorThicknessFilter("{{$Item['FireRating']}}");
    @else
    doorThicknessFilter("FD30");
    @endif

    @if(isset($Item['DoorsetType']) && isset($Item['SwingType']))
    DoorSetTypeChange();
    swingTypeFrameType();
    @endif

    @if(isset($Item['DoorLeafFacing']))
    DoorLeafFacingChange(false,true);
    doorLeafFacingPrice('doorLeafFacing',"@if (isset($Item['DoorLeafFacing'])){{ $Item['DoorLeafFacing'] }}@endif");
    @endif

    @if(isset($Item['DoorLeafFinish']))
    doorLeafFacingPrice('doorLeafFinish',"{{$Item['DoorLeafFinish']}}");
    doorLeafFacingPrice('doorLeafFinish1',"{{$Item['DoorLeafFinish']}}");
    @endif



    @if(isset($Item["SideLight1GlassType"]))
    OverpanelGlassTypeChange(null,'sideLight1GlassType',true);
    @endif
    @if(isset($Item["SideLight2GlassType"]))
    OverpanelGlassTypeChange(null,'sideLight2GlassType',true);
    @endif

    @if(isset($Item["OPGlassType"]))
    OverpanelGlassTypeChange(null,'opGlassType',true);
    @endif

    @if(isset($Item["GlazingSystems"]))
    glazing_system("{{$Item['FireRating']}}",true)
    GlazingSystemsChange("{{$Item['GlazingSystems']}}");
    @endif
    @if(isset($Item["opGlazingSystems"]))
    GlazingSystemsChange("{{$Item['opGlazingSystems']}}",'opglazingSystems');
    @endif
    @if(isset($Item["SideLight1GlazingSystems"]))
    GlazingSystemsChange("{{$Item['SideLight1GlazingSystems']}}",'sideLight1GlazingSystems');
    @endif
    @if(isset($Item["SideLight2GlazingSystems"]))
    GlazingSystemsChange("{{$Item['SideLight2GlazingSystems']}}",'sideLight2GlazingSystems');
    @endif

    @if(isset($Item["FrameFinish"]) && $Item["FrameFinish"] == "Painted_Finish")
    FrameFinishChange(false ,  'framefinish');
    @endif

    @if(isset($Item['ArchitraveMaterial']) && $Item['Architrave'] == 'Yes')
    architrave(1);
    @endif

    @if(isset($Item["FrameFinish"]))
    doorLeafFacingPrice('frameFinish');
    @endif

    @if(isset($Item["DoorLeafFinishColor"]))
    doorLeafFinishChange();
    @endif

    @if(isset($Item['FireRating']))
    render($("#fireRating"));
    @endif

    @if(isset($Item['ExtLiner']) && $Item['ExtLiner'] == "Yes" && isset($Item["FrameFinish"]))
    doorLeafFacingPrice('extLinerFramefinish',"{{$Item['FrameFinish']}}")
    @endif

    @if(isset($Item["Leaf1VisionPanel"]) && $Item["Leaf1VisionPanel"] == 'Yes')
    doorLeafFacingPrice('leaf1VisionPanel',"{{$Item['Leaf1VisionPanel']}}");
    doorLeafFacingPrice('leaf1VisionPanel1',"{{$Item['Leaf1VisionPanel']}}");
    @endif

    @if(isset($Item['Leaf1VisionPanel']) && $Item['Leaf1VisionPanel'] == "Yes" && isset($Item["FireRating"]))
    doorLeafFacingPrice('fireRating',"{{$Item['FireRating']}}");
    doorLeafFacingPrice('fireRating1',"{{$Item['FireRating']}}");
    @endif

    @if(isset($Item['DecorativeGroves']) && $Item['DecorativeGroves'] == "Yes")
    doorLeafFacingPrice('decorativeGroves',"{{$Item['DecorativeGroves']}}");
    @endif

    @if(isset($Item['SideLight1']) && $Item['SideLight1'] == "Yes")
    sideLight1Change();
    sideLightGlassType(true);
    @endif
    @if(isset($Item['SideLight2']) && $Item['SideLight2'] == "Yes")
    sideLight2Change();
    sideLight2GlassType(true);
    @endif
    @if(isset($Item['DoYouWantToCopySameAsSL1']) && $Item['DoYouWantToCopySameAsSL1'] == "Yes")
    copyOfSideLite1Change();
    @endif

    @if(isset($Item['Overpanel']) && $Item['Overpanel'] == "Fan_Light")
    overpanelGlassType(true);
    @endif

    @if(isset($Item['SideLight2']) && $Item['SideLight2'] == "Yes")
    doorLeafFacingPrice('sideLight12',"{{$Item['SideLight2']}}");
    @endif

    @if(isset($Item['LeafWidth1']) && $Item['LeafHeight'])
    doorSize();
    @endif

    @if(isset($Item['DoorLeafFacing']) && isset($Item['DoorLeafFinish']) && isset($Item['LippingSpecies']))
    doorLeafFacingPrice('LeafSet',"{{$Item['DoorLeafFinish']}}");
    @endif

    @if(isset($Item['IntumescentLeapingSealType']) && isset($Item['IntumescentLeapingSealLocation']) && isset($Item['IntumescentLeapingSealColor']) && isset($Item['IntumescentLeapingSealArrangement']))
    doorLeafFacingPrice('intumescentSealArrangement',"{{$Item['IntumescentLeapingSealArrangement']}}");
    @endif

    @if(isset($Item['GlazingSystems']) && isset($Item['VisionPanelQuantity']))
    doorLeafFacingPrice('glazingSystems',"{{$Item['GlazingSystems']}}");
    @endif

    @if(isset($Item['GlassType']) && isset($Item['VisionPanelQuantity']))
    doorLeafFacingPrice('glassType',"{{$Item['GlassType']}}");
    @endif

    @if(isset($Item['GlazingBeads']) && isset($Item['VisionPanelQuantity']))
    doorLeafFacingPrice('glazingBead',"{{$Item['GlazingBeads']}}");
    @endif

    var identifier = $("#OpBeadThickness");
    if(identifier.length > 0){ // Check if element exists
        SetBuildOfMaterial(identifier);
    }

    identifier = $("#OpBeadHeight");
    if(identifier.length > 0){ // Check if element exists
        SetBuildOfMaterial(identifier);
    }
});

frameonoff();
$(document).on('click', '#frameonoff', function(e) {
    frameonoff();
});
$(document).on('focusout','#leafWidth1,#leafWidth2,#leafHeightNoOP',function(e){
    e.preventDefault();
    IntumescentSeals();
});
function frameonoff(){
    if ($("#frameonoff").prop('checked')){
        $(".framehideshow").hide();
        $("#withoutFrameId").val(1);
        $("#tollerance").val('').attr({'required':false});
        $("#undercut").val('').attr({'required':false});
        $("#floorFinish").val('').attr({'required':false});
        $("#gap").val('').attr({'required':false});
        $("#frameThickness").val('').attr({'required':false});
        $("#sOWidth").val('').attr({'required':false});
        $("#sOHeight").val('').attr({'required':false});
        $("#sODepth").val('');
        $("#frameMaterial").val('').attr({'required':false});
        $("#frameType").val('').attr({'required':false});
        $("#leafWidth1").attr({ 'readonly': false, "required": true });
        $("#leafHeightNoOP").attr({ 'readonly': false, "required": true });
        $("#hinge1Location").val('');$("#hinge2Location").val('');$("#hinge3Location").val('');$("#hinge4Location").val('');$("#hingeCenterCheck").val('');
        $("select[name=sideLight1]").val('No').trigger("change");
        $("select[name=sideLight2]").val('No').trigger("change");
        $("select[name=overpanel]").val('No').trigger("change");
        $("select[name=Architrave]").val('No').trigger("change");
        $("select[name=extLiner]").val('No').trigger("change");
        $("#SlBeadThickness").val('').attr('readonly',true);
        $("#SlBeadHeight").val('').attr('readonly',true);
        $("#SlBeadThickness").val('').attr('required',false);
        $("#SlBeadHeight").val('').attr('required',false);
        $("#copyOfSideLite1").attr({'disabled': true,"readonly":true }).val("No");
        $("#sideLight2GlassType").attr({ 'disabled': false, "required": true }).val('');
        $("#SideLight2BeadingType").attr({ 'disabled': false, "required": true }).val('');
        $("#SideLight2GlazingBeadSpecies").attr({ 'disabled': false, "required": true }).val('');
        $("#sideLight2GlassType").attr({ 'disabled': true, "required": false }).val('');
        $("#SideLight2BeadingType").attr({ 'disabled': true, "required": false }).val('');
        $("#SideLight2GlazingBeadSpecies").attr({ 'disabled': true, "required": false }).val('');
        $("#copyOfSideLite1").attr({ 'disabled': true, "required": false }).val('');
        $("#SL2Width").attr({ 'readonly': true, "required": false }).val('');
        $("#SL2Height").attr({ 'readonly': true, "required": false }).val("");
        $("#SL2Depth").attr({ 'readonly': true, "required": false }).val('');
        $("#SL2Transom").attr({ 'disabled': true, "required": false }).val('');
        $("#sideLight12-section1").removeClass("table_row_show");
        $("#sideLight12-section1").addClass("table_row_hide");
        $("#sideLight1GlassType").attr({ 'disabled': true, "required": false }).val('');
        $("#SideLight1BeadingType").attr({ 'disabled': true, "required": false }).val('');
        $("#SideLight1GlazingBeadSpecies").attr({ 'disabled': true, "required": false }).val('');
        $("#SL1Width").attr({ 'readonly': true, "required": false }).val('');
        $("#SL1Height").attr({ 'readonly': true, "required": false }).val("");
        $("#SL1Depth").attr({ 'readonly': true, "required": false }).val('');
        $("#SL1Transom").attr({ 'disabled': true, "required": false }).val('');
        $("#architraveMaterial").attr({'readonly':true,'required':false}).val('');
        $("#architraveMaterial").val('');
        $('#architraveMaterialIcon').attr('onclick','');
        $('input[name="architraveMaterial"]').val('');
        $("#architraveType").attr({'disabled':true,'required':false}).val('');
        $("#architraveWidth").attr({'readonly':true,'required':false}).val('');
        $("#architraveFinish").attr({'disabled':true,'required':false}).val('');
        $("#architraveSetQty").attr({'disabled':true,'required':false}).val('');
        $("#architraveHeight").attr({'readonly':true,'required':false}).val('');
        $("#architraveFinishcolor").attr('disabled',true).val('');
        $("#architraveFinishcolor").removeClass('bg-white');
        $('#architraveFinishcolorIcon').attr('onclick','');
        $('input[name="architraveFinishcolor"]').val('');
        $("#OpBeadThickness").val('').attr('readonly',true);
        $("#OpBeadHeight").val('').attr('readonly',true);
        $("#OpBeadThickness").val('').attr('required',false);
        $("#opGlazingBeads").attr('required',false);
        $("#OpBeadHeight").val('').attr('required',false);
        $("#intumescentSealLocation").children("option[value^=Frame]").hide();
        $("#oPWidth").val('').attr('readonly',true);
        $("#oPHeigth").attr({'readonly':true,'required':false}).val('');
        $("#OPLippingThickness").val('').attr('disabled','disabled');
        $("#transomThickness").val('').attr('disabled','disabled');
        $("#opTransom").val('').attr('disabled','disabled');
        $("#opGlassIntegrity").val('').attr('disabled','disabled');
        $("#OPGlassType").val('').attr('disabled','disabled');
        $("#opGlazingBeads").val('').attr('disabled','disabled');
        $("#opGlazingBeadSpecies").val('').attr('disabled','disabled');
        $("#frameMaterial").val('');
        $("#frameType").val('');
        $("#plantonStopWidth").attr({'readonly':true,'required':false}).val('');
        $("#plantonStopHeight").attr({'readonly':true,'required':false}).val('');
        $("#rebatedWidth").attr({'readonly':true,'required':false}).val('');
        $("#rebatedHeight").attr({'readonly':true,'required':false}).val('');
        $("#frameDepth").val('');
        $("#standardWidth").val('');
        $("#standardHeight").val('');
        $("#frameWidth").val('');
        $("#frameHeight").val('');
        $("#frameFinish").val('');
        $("#framefinishColor").val('');
        $("#frameCostuction").val('');
        $("#extLinerValue").val('');
        $("#extLinerSize").val('');
        $("#extLinerThickness").val('');
        $("#extLinerFinish").val('');
        $("#specialFeatureRefs").val('');
        $("#frameDepth").attr('required',false);
        $("#sODepth").attr({'required':false});
        if($("#intumescentSealLocation").val() != ''){
            $("#intumescentSealLocation").val('Door').trigger('change');
        }
    }else{
        $(".framehideshow").removeAttr("style");
        $("#withoutFrameId").val(0);
        $("#tollerance").attr({'required':true});
        $("#undercut").attr({'required':true});
        $("#floorFinish").attr({'required':true});
        $("#gap").attr({'required':true});
        $("#frameThickness").attr({'required':true});
        $("#sOWidth").attr({'required':true});
        $("#sOHeight").attr({'required':true});
        $("#frameMaterial").attr({'required':true});
        $("#frameType").attr({'required':true});
        $("#leafWidth1").attr({'readonly':true});
        $("#leafHeightNoOP").attr({'readonly':true});
        $("#frameDepth").attr('required',true);
        $("#sODepth").attr({'required':true});
        $("#intumescentSealLocation").children("option[value^=Frame]").show();
    }
    render($("#fireRating"));
}

$(document).on('click', '#submit', function(e) {
    let allAreFilled = true;
    let RequiredFields = "";
    let label = '';
    let parentid = '';
    let glassType = $("#glassType").val();
    if(glassType){
        let glazingSystems = $('#glazingSystems').val();
        if(glazingSystems === ""){
            $("#glazingSystems").attr({ 'disabled': false, "required": true });
        } else{
            $("#glazingSystems").attr({ 'disabled': false, "required": false });
        }
    } else {
        $("#glazingSystems").attr({ 'disabled': false, "required": false });
    }

        let Handing = $('#Handing').val();
        if(Handing === ""){
            $("#Handing").attr({ 'disabled': false, "required": true });
        } else {
            $("#Handing").attr({ 'disabled': false, "required": false });
        }
        let latchType = $('#latchType').val();
        if(latchType === ""){
            $("#latchType").attr({ 'disabled': false, "required": true });
        } else{
            $("#latchType").attr({ 'disabled': false, "required": false });
        }
        let withoutFrameId = $('#withoutFrameId').val();
        let frameCostuction = $('#mydoor').val();
        if(withoutFrameId == 0){
            if(frameCostuction === ""){
                $("#frameCostuction").attr({ 'disabled': false, "required": true });
            }
            else{
                $("#frameCostuction").attr({ 'disabled': false, "required": false });
            }
        } else {
                $("#frameCostuction").attr({ 'disabled': false, "required": false });
        }

    document.getElementById("itemForm").querySelectorAll(".form-control").forEach(function(i) {
        $(".optionItem a").css({
            'background': '#3b86ff'
        });
        $('#' + i.id).css({
            'border': '1px solid #ced4da'
        });
    });

    document.getElementById("itemForm").querySelectorAll("[required]").forEach(function(i) {
        // if (!allAreFilled) return;
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
        // if (i.type === "radio") {
        //     let radioValueCheck = false;
        //     document.getElementById("itemForm").querySelectorAll(`[name=${i.name}]`).forEach(function(r) {
        //         if (r.checked) radioValueCheck = true;
        //     })
        //     allAreFilled = radioValueCheck;
        // }
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
    // console.table(data2)
    $.ajax({
        url: "{{route('items/store1')}}",
        type: 'POST',
        data: data,
        datatype: "json",
        success: function(res) {
            console.log(res);
            // console.log(res.data)
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




$(document).on('click', '.optionItem', function() {
    let ScrolHeight = $(this).children('input').val();
    // alert($("#opDiv").scrollTop() + " px");
    $('#opDiv').animate({
        scrollTop: ScrolHeight
    });
});


    // Accoustic : add images to the options available in the Acoustics section.
    function openAccousticsModal(id,AccousticModalLabel,UnderAttribute){
        $('#AccousticModalLabel').html(AccousticModalLabel);
        let pageIdentity = $('#pageIdentity').val();
        $.ajax({
            url:"{{route('showAccoustic')}}",
            type:"POST",
            data:{'pageId':pageIdentity ,'id':id,'UnderAttribute':UnderAttribute ,'AccousticModalLabel':AccousticModalLabel},
            success:function(response){
                console.log(response);
                $(`#AccousticModalBody`).html(response)
                $('#AccousticModal').modal('show');
            }
        })
    }
    function selectAccoustic(id,key,value, cost = "0.00"){
        $(id).val(value);
        $(id).siblings('input[type="hidden"]').val(key);
        $('#AccousticModal').modal('hide');

        SetBuildOfMaterial($(id).siblings('input[type="hidden"]'), cost);

    }
    var count = 1;
    var counter = 1;
    $(document).ajaxComplete(function() {
        if(count == 1){
            $('#submit').attr({'disabled': true,"readonly":true });
            count = 0;
        }
    });

    $( document ).ajaxStop(function() {
        if(counter == 1){
            $('#submit').attr({'disabled': false,"readonly":false });
            counter = 0;
        }
    });
</script>


@endsection
<!-- Universal Modal -->
<div class="modal fade bd-example-modal-lg" id="UniversalModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="UniversalModalLabel"></h5>
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal"
                    aria-label="Close">X</button>
                    <input type="hidden" class="inputIdentity">
            </div>
            <div class="modal-body">
                <div id="UniversalModalBody">

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
<!-- Modal Acoustic-->
<div class="modal fade bd-example-modal-lg" id="AccousticModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="AccousticModalLabel"></h5>
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal"
                    aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <div id="AccousticModalBody">

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
<!-- Modal -->
<div class="modal fade" id="iron" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="display:block !important;">
                <button type="button" class="btn btn-default close" data-dismiss="modal">X</button>
                <h5 class="modal-title" id="modalTitle"></h5>
            </div>
            <div class="modal-body">
                <div class="row" id="content"></div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="OPglazingModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="OPglazingModalLabel">All OP Glazing</h5>
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal"
                    aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="inputId">
                <div id="OPglazingModalBody">

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

<div class="modal fade bd-example-modal-lg" id="SL1glazingModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="SL1glazingModalLabel">All SL1 Glazing</h5>
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal"
                    aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="inputId">
                <div id="SL1glazingModalBody">

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

<div class="modal fade bd-example-modal-lg" id="SL2glazingModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="SL2glazingModalLabel">All SL2 Glazing</h5>
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal"
                    aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="inputId">
                <div id="SL2glazingModalBody">

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

<div class="modal fade bd-example-modal-lg" id="frameMaterialModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="frameMaterialModalLabel">Frame Materials</h5>
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal"
                    aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <div id="frameMaterialModalBody">

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
<div class="modal fade bd-example-modal-lg" id="glazingModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="glazingModalLabel">All Glazing</h5>
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
<div class="modal fade bd-example-modal-lg" id="LipingModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="LipingModalLabel">All Glazing</h5>
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal"
                    aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="inputId">
                <div id="LipingModalBody">

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
<!-- Door Frame Construction  -->
<div class="modal fade bd-example-modal-lg" id="DoorFrameConstructionModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="DoorFrameConstructionModalLabel">Door Frame Construction</h5>
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal"
                    aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="inputId">
                <div id="DoorFrameConstructionModalBody">
                    <div class="container">
                        <div class="row">
                            @foreach($option_data as $row)
                            @if($row->OptionSlug=='Door_Frame_Construction')
                            <div class="col-md-2 col-sm-4 col-6 cursor-pointer"
                                onclick="DoorFrameConstruction('#frameCostuction','{{$row->OptionKey}}','{{$row->OptionValue}}')">
                                <div class="color_box">
                                    <div class="frameMaterialImage">
                                        <img width="100%" height="100"
                                            src="{{url('/')}}/uploads/Options/{{$row->file}}">
                                    </div>
                                    <h4>{{$row->OptionValue}}</h4>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>

                    <!-- @foreach($option_data as $row)
                        @if($row->OptionSlug=='Door_Frame_Construction')
                        <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                        @endif
                    @endforeach -->
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
