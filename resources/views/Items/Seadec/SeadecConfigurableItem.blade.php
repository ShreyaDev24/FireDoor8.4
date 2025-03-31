@extends("layouts.CadMaster")
@section("main_section")


<style>
    .input-icons i {
        position: absolute;
        right: 0;
        top: 27px;
    }
</style>

<div class="app-main__outer">
    <div class="app-main__inner">
        @if (\Session::has('msg'))
        <div class="alert alert-success">
            <ul>
                <li>{!! \Session::get('msg') !!}</li>
            </ul>
        </div>
        @endif

        <span class="error"></span>
        <script>
            function Tooltip(tooltipValue){
                let TooltipCode2 = '<i class="fa fa-info-circle field_info tooltip" aria-hidden="true"><span class="tooltiptext info_tooltip">'+ tooltipValue +'</span></i>';
                return TooltipCode2;
            }
        </script>

        <div class="form-row">
            <div class="col-md-6">

                <div class="main-carousel">
                    <div class="main-container">
                        <div class="container-carousel pl-1 pr-4">
                            <button role="button" id="arrow-left" class="arrow-left border-0 text-secondary p-1 rounded"><i class="fa fa-chevron-left"></i></button>
                            <div class="carousel">
                                <ul class="nav nav-tabs">
                                    <li class="optionItem">
                                        <a class="btn btn-primary active" data-toggle="tab" href="#main-options-section">Main Options</a>
                                        <input type="hidden" value="0px">
                                    </li>
                                    <li class="optionItem">
                                        <a class="btn btn-primary" data-toggle="tab" href="#door-dimensions-n-door-leaf-section">Door Dimensions & Door Leaf</a>
                                        <input type="hidden" value="432px">
                                    </li>
                                    <li class="optionItem">
                                        <a class="btn btn-primary" data-toggle="tab" href="#vision-panel-section">Vision Panel</a>
                                        <input type="hidden" value="988px">
                                    </li>
                                    <li class="optionItem framehideshow">
                                        <a class="btn btn-primary" data-toggle="tab" href="#frame-section">Frame</a>
                                        <input type="hidden" value="2039px">
                                    </li>
                                    <li class="optionItem framehideshow">
                                        <a class="btn btn-primary" data-toggle="tab" href="#over-panel-section">Overpanel/Fanlight Section</a>
                                        <input type="hidden" value="2656px">
                                    </li>
                                    <li class="optionItem framehideshow">
                                        <a class="btn btn-primary" data-toggle="tab" href="#side-light-section">Side Light</a>
                                        <input type="hidden" value="2958px">
                                    </li>
                                    <li class="optionItem">
                                        <a class="btn btn-primary" data-toggle="tab" href="#lipping-and-intumescent-section">Lipping & Intumescent</a>
                                        <input type="hidden" value="3458px">
                                    </li>
                                    <li class="optionItem">
                                        <a class="btn btn-primary" data-toggle="tab" href="#accoustics-section">Acoustics</a>
                                        <input type="hidden" value="3892px">
                                    </li>
                                    <li class="optionItem framehideshow">
                                        <a class="btn btn-primary" data-toggle="tab" href="#architrave-section">Architrave</a>
                                        <input type="hidden" value="3840px">
                                    </li>
                                    {{--  <li class="optionItem" id="transportSection">
                                        <a class="btn btn-primary" data-toggle="tab" href="#transport-section">Transport</a>
                                    </li>  --}}
                                </ul>
                            </div>
                            <button role="button" id="arrow-right" class="arrow-right border-0 text-secondary p-1 rounded"><i class="fa fa-chevron-right"></i></button>
                        </div>
                    </div>
                </div>


           <div class="item-form">
                <form id="itemForm" enctype="multipart/form-data" >
                    <input type="hidden" name="pageIdentity" id="pageIdentity" value="5">
                    <input type="hidden" name="version_id" value="<?= (!is_null($versionId))?$versionId:0; ?>">
                    <input type="hidden" name="SvgImage" value=""/>
                    @if(in_array(Auth::user()->UserType, ['1', '2', '3']) && isset($quotation->QuotationStatus) && $quotation->QuotationStatus != 'Ordered' && empty($Item["itemId"]))
                        <div class="float-right">
                            <button type="button" id="default" onclick="default()" class="btn btn-primary">
                                <i class="fas fa-paper-plane"></i> Import Default
                            </button>
                        </div>
                    @endif
                    <div class="tab-content">
                        <div id="main-options-section" class="tab-pane active">
                            @include("Items.Seadec.SeadecDoorModules.MainOptions")
                         </div>

                        <div id="door-dimensions-n-door-leaf-section" class="tab-pane fade">
                            @include("Items.Seadec.SeadecDoorModules.DoorDimensionsAndDoorLeaf")
                        </div>

                        <div id="vision-panel-section" class="tab-pane fade">
                            @include("Items.Seadec.SeadecDoorModules.VisionPanel")
                        </div>

                        <div id="frame-section" class="tab-pane fade framehideshow">
                            @include("Items.Seadec.SeadecDoorModules.Frame")
                        </div>

                        <div id="over-panel-section" class="tab-pane fade framehideshow">
                            @include("Items.Seadec.SeadecDoorModules.OverPanel")
                        </div>

                        <div id="side-light-section" class="tab-pane fade framehideshow">
                            @include("Items.Seadec.SeadecDoorModules.SideLight")
                        </div>

                        <div id="lipping-and-intumescent-section" class="tab-pane fade">
                            @include("Items.Seadec.SeadecDoorModules.LippingAndIntumescent")
                        </div>

                        <div id="accoustics-section" class="tab-pane fade">
                            @include("Items.Seadec.SeadecDoorModules.Accoustics")

                        </div>

                        <div id="architrave-section" class="tab-pane fade framehideshow">
                            @include("Items.Seadec.SeadecDoorModules.Architrave")
                        </div>

                        <div id="transport-section" class="tab-pane fade">
                            @include("Items.Seadec.SeadecDoorModules.Transport")
                        </div>
                    </div>




                    <div hidden id="glazing-system-filter">{{route('items/glazing-system-filter')}}</div>
                    <div hidden id="architrave-system-filter">{{route('items/architrave-system-filter')}}</div>
                    <div hidden id="fire-rating-filter">{{route('items/fire-rating-filter')}}</div>
                    <div hidden id="glazing-beads-filter">{{route('items/glazing-beads-filter')}}</div>
                    <div hidden id="glass-type-filter">{{route('items/glass-type-filter')}}</div>
                    <div hidden id="glazing-thikness-filter">{{route('items/glazing-thikness-filter')}}</div>
                    <div hidden id="frame-material-filter">{{route('items/frame-material-filter')}}</div>
                    <div hidden id="scallopped-lipping-thickness">{{route('items/scallopped-lipping-thickness')}}</div>
                    <div hidden id="flat-lipping-thickness">{{route('items/flat-lipping-thickness')}}</div>
                    <div hidden id="rebated-lipping-thickness">{{route('items/rebated-lipping-thickness')}}</div>
                    <div hidden id="door-thickness-filter">{{route('items/door-thickness-filter')}}</div>
                    <div hidden id="door-leaf-face-value-filter">{{route('items/door-leaf-face-value-filter')}}</div>
                    <div hidden id="ral-color-filter">{{route('items/ral-color-filter')}}</div>
                    <div hidden id="face-groove-image">{{route('items/face-groove-image')}}</div>
                    <div hidden id="filter-iron-mongery-category">{{route('ironmongery-info/filter-iron-mongery-category')}}
                    </div>
                    <div hidden id="url">{{url('/')}}</div>
                    <div hidden id="get-handing-options">{{route('items/get-handing-options')}}</div>
                    <div hidden id="Filterintumescentseals">{{route('Filterintumescentseals')}}</div>
                    <div hidden id="opGlassTypeFilterUrl">{{route('opGlassTypeFilterUrl')}}</div>
                    <div hidden id="liping-glazing-system-filter">{{route('items/liping-glazing-system-filter')}}</div>

                </div>
            </div>
            <div class="col-md-6">
                {{-- <ul class="nav nav-tabs border-0">
                    <li class="optionItem">
                        <a class="btn btn-primary" data-toggle="tab" href="#BuildOfMaterial">
                            <i class="fa fa-book" aria-hidden="true"></i>
                        </a>
                    </li>
                    <li class="optionItem">
                        <a class="btn btn-primary" data-toggle="tab" href="#door">
                            <i class="fa fa-image" aria-hidden="true"></i>
                        </a>
                    </li>
                    @if(Auth::user()->UserType=='1' ||Auth::user()->UserType=='2' || Auth::user()->UserType=='3' || Auth::user()->UserType=='4')
                        <li class="optionItem">
                            <button type="button" id="submit" class="door_submit" style="margin-right: 20px">
                            <i class="fas fa-paper-plane"></i> @if(!empty($Item["itemId"])){{ 'Update Now' }} @else {{'Submit Now'}}  @endif
                            </button>
                        </li>
                    @endif
                </ul> --}}
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
                <div class="tab-content item-form" id="opDiv">
                    <div id="door" class="tab-pane active">
                        <div id='container'></div>
                    </div>
                    <div id="BuildOfMaterial" class="tab-pane table-responsive">
                        {{--<table id="BuildOfMaterialDetails" class="table table-bordered table-striped"></table>--}}
                      @include("Items.Seadec.SeadecBuildOfMaterialForCadDoor")

                    </div>
                    <div id="doorPrice" class="tab-pane table-responsive" >
                        @include("Items.Seadec.DoorPriceForCadDoor")
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>




<script src="{{url('/')}}/Seadec/Seadec-cad-door-configuration.js"></script>
<script src="{{url('/')}}/Seadec/Seadec-build-of-material-for-cad-door.js"></script>
<script src="{{asset('Seadec/Seadec-custome-rules.js')}}"></script>
<script src="{{asset('Seadec/Seadec-change-event-calculation.js')}}"></script>
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
@endif
@endsection


@section("js")

<script>
    $(document).on('click', '#default', function(e) {
        defaultimport();
    });

    function defaultimport(){
        $("#FrameFinishColor-value").val($("#FrameFinishColor-import").data("value"));
        var handingImportValue = $("#Handing-import").data("value");
        var frameCostuction = $("#DoorFrameConstruction-import").data("value");
        $("#Handing-value").data("value", handingImportValue);
        $("#DoorLeafFacingValue-value").data("value",$("#DoorLeafFacingValue-import").data("value"));
        $("#DoorLeafFinish-value").data("value",$("#DoorLeafFinish-import").data("value"));
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
        DoorLeafFacingChange();
        if($("#Leaf1VisionPanel-import").data("value") == 'Yes'){
            $("#leaf1VisionPanelShape").attr('readonly', false);
            $("#visionPanelQuantity").attr('disabled', false);
            $("#visionPanelQuantity").attr('required', true);
            $("#leaf1VisionPanelShape").attr('readonly', false);
            $("#leaf1VisionPanelShape").attr('required', true);
                //$("#AreVPsEqualSizes").attr('required',true);
            $("#vP1Width").attr('readonly', false);
            $("#vP1Height1").attr('readonly', true);
            $("#vP1Width").attr('required', true);
            $("#vP1Height1").attr('required', true);
            $("#Leaf1VPWidth").attr('required', true);
            $("#Leaf1VPWidth").attr('required', true);
            $("#distanceFromTopOfDoor").attr('readonly', false);
            $("#distanceFromTheEdgeOfDoor").attr({ 'required': true, 'readonly': false });
            $("select[name=leaf1VisionPanel]").val($("#Leaf1VisionPanel-import").data("value")).trigger('change');
            $("select[name=leaf1VisionPanelShape]").val($("#Leaf1VisionPanelShape-import").data("value"));
            $("select[name=visionPanelQuantity]").val($("#VisionPanelQuantity-import").data("value")).trigger('change');
            $("select[name=AreVPsEqualSizes]").val($("#AreVPsEqualSizesForLeaf1-import").data("value")).trigger('change');
            $("#distanceFromTopOfDoor").val($("#DistanceFromtopOfDoor-import").data("value"));
            $("#distanceFromTheEdgeOfDoor").val($("#DistanceFromTheEdgeOfDoor-import").data("value"));
            // $("#distanceBetweenVPs").val($("#DistanceBetweenVPs-import").data("value"));
            if($("#VisionPanelQuantity-import").data("value") == 1){
                $("select[name=visionPanelQuantity]").val($("#VisionPanelQuantity-import").data("value"));
                $("#distanceBetweenVPs").removeAttr('min', '80');
            }
            else{
                $("select[name=visionPanelQuantity]").val($("#VisionPanelQuantity-import").data("value")).trigger('change');
            }
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
        $("select[name=frameType]").val($("#FrameType-import").data("value")).trigger('change');98
        $("#frameDepth").val($("#FrameDepth-import").data("value"));
        $("select[name=frameFinish]").val($("#FrameFinish-import").data("value"));
        if($("#FrameFinish-import").data("value") == 'Painted_Finish'){
            FrameFinishChange(false ,  'framefinish');
        }
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
        if($("#Architrave-import").data("value") == 'Yes'){
            $("#architraveMaterial").attr({ 'readonly': true, 'required': true });
            $("#architraveMaterial").addClass('bg-white');
            $('#architraveMaterialIcon').attr('onclick', "return ArchitraveMaterial()");
            $("#architraveType").attr({ 'disabled': false, 'required': true });
            $("#architraveWidth").attr({ 'readonly': false, 'required': true });
            // $("#architraveDepth").attr({'readonly':false,'required':true}).val('');
            $("#architraveFinish").attr({ 'disabled': false, 'required': true });
            $("#architraveSetQty").attr({ 'disabled': false, 'required': true });
            $("#architraveHeight").attr({ 'readonly': false, 'required': true });
            $("#architraveFinishcolor").attr('readonly', true);
            if (ArcFin == 'Painted_Finish') {
                $("#architraveFinishcolor").addClass('bg-white');
                $("#architraveFinishcolorIcon").attr('onclick', "return ArchitraveFinishColor()");
            }
        }
        architrave(1);
        floor_finish_change();
        let framTypeValue = $("#FrameType-import").data("value");
        if (framTypeValue == "Plant_on_Stop") {
            $("#plantonStopWidth").attr('min', '20');
            $("#plantonStopHeight").attr('min', '12');
            $("#rebatedWidth").removeAttr('min', '32');
            $("#rebatedHeight").removeAttr('min', '12');
            $("#ScallopedHeight").removeAttr('min', '12');
            $("#ScallopedHeight").removeAttr('max', '5');
            $("#ScallopedWidth").removeAttr('min', '32');
            $("#plantonStopWidth").attr({ 'readonly': false, 'required': true });
            $("#plantonStopHeight").attr({ 'readonly': false, 'required': true });
            $("#frameTypeDimensions").val('').attr('readonly', false);
            $("#rebatedWidth").attr({ 'readonly': true, 'required': false }).val(0);
            $("#rebatedHeight").attr({ 'readonly': true, 'required': false }).val(0);
            $("#ScallopedHeight").attr({ 'readonly': true, 'required': false }).val(0);
            $("#ScallopedWidth").attr({ 'readonly': true, 'required': false }).val(0);
            $("#rebatedWidth-section,#rebatedHeight-section,#ScallopedWidth-section,#ScallopedHeight-section").removeClass("table_row_show");
            $("#rebatedWidth-section,#rebatedHeight-section,#ScallopedWidth-section,#ScallopedHeight-section").addClass("table_row_hide");
            // frameprice('Plant_on_Stop');
        } else if(framTypeValue == "Scalloped"){
            //$("#ScallopedWidth").attr('min', '32');
            // $("#ScallopedHeight").attr('max', '5');
            $("#ScallopedHeight").attr({ 'readonly': false, 'required': true });
            $("#ScallopedWidth").attr({ 'readonly': false, 'required': true });
            $("#plantonStopWidth").attr({ 'readonly': true, 'required': false }).val(0);
            $("#plantonStopHeight").attr({ 'readonly': true, 'required': false }).val(0);
            $("#rebatedWidth").attr({ 'readonly': true, 'required': false }).val(0);
            $("#rebatedHeight").attr({ 'readonly': true, 'required': false }).val(0);
            $("#rebatedWidth-section,#rebatedHeight-section,#plantonStopWidth-section,#plantonStopHeight-section").removeClass("table_row_show");
            $("#rebatedWidth-section,#rebatedHeight-section,#plantonStopWidth-section,#plantonStopHeight-section").addClass("table_row_hide");
        } else if (framTypeValue == "Rebated_Frame") {
            $("#plantonStopWidth").attr({ 'readonly': true, 'required': false }).val(0);
            $("#plantonStopHeight").attr({ 'readonly': true, 'required': false }).val(0);
            $("#rebatedWidth").attr('min', '32');
            $("#rebatedHeight").attr('min', '12');
            $("#plantonStopWidth").removeAttr('min', '20');
            $("#ScallopedHeight").removeAttr('max', '5');
            $("#plantonStopHeight").removeAttr('min', '12');
            $("#ScallopedHeight").removeAttr('min', '12');
            $("#ScallopedWidth").removeAttr('min', '32');
            $("#rebatedWidth").attr({ 'readonly': false, 'required': true });
            $("#rebatedHeight").attr({ 'readonly': false, 'required': true });
            $("#ScallopedHeight").attr({ 'readonly': true, 'required': false }).val(0);
            $("#ScallopedWidth").attr({ 'readonly': true, 'required': false }).val(0);
            $("#plantonStopWidth-section,#plantonStopHeight-section,#ScallopedWidth-section,#ScallopedHeight-section").removeClass("table_row_show");
            $("#plantonStopWidth-section,#plantonStopHeight-section,#ScallopedWidth-section,#ScallopedHeight-section").addClass("table_row_hide");
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
        $(".form-control").each(function(index) {
            const element = $(this);
            setTimeout(function() {
                SetBuildOfMaterial(element);
            }, index * 100); // Delay increases by 100ms for each element
        });
    }
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
            $("#sOWidth").attr({'required':false});
            $("#sOHeight").attr({'required':false});
            $("#sODepth").val('').attr({'required':false});
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
            $("#frameDepth").val('').attr('required',false);
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

$('#adjustmentLeafWidth1,#adjustmentLeafWidth2, #adjustmentLeafHeightNoOP').keyup(function(){
    AdjustmentLipping();
});

function AdjustmentLipping() {
    let adjustmentLeafWidth1 = $('#adjustmentLeafWidth1').val();
    let adjustmentLeafHeightNoOP = $('#adjustmentLeafHeightNoOP').val();

    if (adjustmentLeafWidth1.trim() !== '' || adjustmentLeafHeightNoOP.trim() !== '') {

        $('#lippingType').prop('disabled', false);
        $('#lippingType').prop('required', true);
        $('#lippingThickness').prop('disabled', false);
        $('#lippingThickness').prop('required', true);
        $('#lippingSpecies').prop('disabled', false).addClass("bg-white");
        $('#lippingSpecies').prop('required', true);
        $('#coreWidth1').prop('disabled', false);
        $('#coreWidth2').prop('disabled', false);
        $('#coreHeight').prop('disabled', false);
    } else {
        $('#lippingType').prop('disabled', true).val('');
        $('#lippingThickness').prop('disabled', true).val('');
        $('#lippingSpecies').prop('disabled', true).removeClass('bg-white').val('');
        $('#coreWidth1').prop('disabled', true).val('');
        $('#coreWidth2').prop('disabled', true).val('');
        $('#coreHeight').prop('disabled', true).val('');
        $('#LippingThicknessValue').val('');
        $('#lippingSpeciesid').val('');
        $('#lippingType').prop('required', false);
        $('#lippingThickness').prop('required', false);
        $('#lippingSpecies').prop('required', false);
    }
}

function groovesNumbershow(){
    let decorativeGroves = $('#decorativeGroves').val();
    if(decorativeGroves == 'Yes'){
        $('#doorDimensionGroove').attr({'disable':false, 'readonly':false }).addClass('bg-white');
    }else{
        $('#doorDimensionGroove').attr({ 'readonly':true }).removeClass('bg-white');
    }
}

   $('#frameDepth').keyup(function(){
  let framDepthValue = $(this).val();
  $('#SL1Depth').val(framDepthValue);
  $('#SL1Depth').val(framDepthValue);
  $('#SL2Depth').val(framDepthValue);

   });

    $(document).on('change','#fireRating', function(){
        fireRatingChange();
    });

    function fireRatingChange(){
        let fireRating = $('#fireRating').val();

     // Intumescent Seal Arrangement input field
    //  IntumescentSealArrangementValue2();

     //lipping type
     lippingTypeValue();
     changeLippingThickness();
     IntumescentSealArrangementValue();
     $('#lippingThickness').html('<option value="">Select Leaping Thickness</option>');

     // fire rating change to remove minimum width height and depth for frame section
     $('#plan-on-stop-min-width').html('');
     $('#plan-on-stop-min-height').html('');
     $('#frame-depth-min').html('');
     $('#plantonStopWidth').removeAttr('min');
     $('#plantonStopHeight').removeAttr('min');
     $('#frameDepth').removeAttr('min');
     $('#frameDepth').val('');


     //Overpanel/Fanlight
     $('.OverPanelTitle').html('Overpanel/Fanlight Section');
     $('.overPanelLabel').html('Overpanel/Fanlight');
     $('.OpWidthCase').html('OP/FL Width');
     $('.OpHeightMax').html('OP/FL Height (Max-value:600)');
     $('#oPHeigth').attr('max',600);

     //side light
     $('.slBeadThickness').html('SL Bead Thickness');
     $('#SlBeadThickness').removeAttr('min');
     $('.slBeadHeightMin').html('SL Bead Height');
     $('#SlBeadHeight').removeAttr('min');
     $('#SlBeadThickness').val('');
     $('#SlBeadHeight').val('');

     //frame
     $('#plantonStopWidth').val('');
     $('#plantonStopHeight').val('');

     // Lipping & Intumescent
     $('#intumescentSealType').attr({ 'required':false})
           $('#intumescentSealLocation').attr({ 'required':false})
           $('#intumescentSealColor').attr({ 'required':false})
           $('#intumescentSealArrangement').attr({ 'required':false})
           $('#frameDepth').attr({ 'required':false})


       if(fireRating == 'FD30'){

         // Lipping & Intumescent
           $('#intumescentSealType').attr({ 'required':true})
           $('#intumescentSealLocation').attr({ 'required':true})
           $('#intumescentSealColor').attr({ 'required':true})
           $('#intumescentSealArrangement').attr({ 'required':true})
           $('#frameDepth').attr({ 'required':true})

          $('#plan-on-stop-min-width').html('(min 32 MM)');
          $('#plantonStopWidth').attr('min',32);
           $('#plan-on-stop-min-height').html('(min 12 MM)');
          $('#plantonStopHeight').attr('min',12);
          $('#frame-depth-min').html('(min 70 MM)');
          $('#frameDepth').attr('min',70);
          $('.OverPanelTitle').html('Overpanel/Fanlight');
          $('.OpWidthCase').html('OP/FL width');
          $('.OpHeightMax').html('OP/FL Height (Max-value:600)');
          $('.slBeadThickness').html('SL Bead Thickness (min 20mm)');
          $('#SlBeadThickness').attr('min',20);
         $('.slBeadHeightMin').html('SL Bead Height (min 20mm)');
          $('#SlBeadHeight').attr('min',20);

       }

       if(fireRating == 'FD60'){
        $('#intumescentSealType').attr({ 'required':true})
           $('#intumescentSealLocation').attr({ 'required':true})
           $('#intumescentSealColor').attr({ 'required':true})
           $('#intumescentSealArrangement').attr({ 'required':true})
           $('#frameDepth').attr({ 'required':true})

        $('#plan-on-stop-min-width').html('(min 32 MM)');
          $('#plantonStopWidth').attr('min',32);
           $('#plan-on-stop-min-height').html('(min 12 MM)');
          $('#plantonStopHeight').attr('min',12);
          $('#frame-depth-min').html('(min 70 MM)');
          $('#frameDepth').attr('min',70);
          $('.slBeadThickness').html('SL Bead Thickness (min 20mm)');
          $('#SlBeadThickness').attr('min',20);
          $('.slBeadHeightMin').html('SL Bead Height (min 20mm)');
          $('#SlBeadHeight').attr('min',20);
       }

       let doorSetType = $('#doorsetType').val();
        if(doorSetType == 'SD' && (fireRating == 'FD30' || fireRating == 'FD60')){
          $('.OpHeightMax').html('OP/FL Heigth (Max-value:2000)');
          $('#oPHeigth').attr('max',2000);
        }

        if(doorSetType == 'DD' && (fireRating == 'FD30' || fireRating == 'FD60')){

            $('.OpHeightMax').html('OP/FL Heigth (Max-value:1500)');
            $('#oPHeigth').attr('max',1500);
        }
        let fanLightOverpanel = $('#overpanel').val();
        if((doorSetType == 'DD' || doorSetType == 'SD') && fanLightOverpanel == 'Fan_Light'  && (fireRating == 'FD30' || fireRating == 'FD60')){

            $('.OpHeightMax').html('OP/FL Height (Max-value:600)');
            $('#oPHeigth').attr('max',600);
        }
        frameonoff();
    }

    function editFirerating(){
        let fireRating = $('#fireRating').val();

     // Intumescent Seal Arrangement input field
    //  IntumescentSealArrangementValue2();

     //lipping type
     setTimeout(function(){
         lippingTypeValue();
        changeLippingThickness();
     }, 200)

     setTimeout(function(){

        changeLippingThickness();
     }, 500)
     IntumescentSealArrangementValue();
     $('#lippingThickness').html('<option value="">Select Leaping Thickness</option>');

     // fire rating change to remove minimum width height and depth for frame section
     $('#plan-on-stop-min-width').html('');
     $('#plan-on-stop-min-height').html('');
     $('#frame-depth-min').html('');
     $('#plantonStopWidth').removeAttr('min');
     $('#plantonStopHeight').removeAttr('min');
     $('#frameDepth').removeAttr('min');
    //  $('#frameDepth').val('');


     //Overpanel/Fanlight
     $('.OverPanelTitle').html('Overpanel/Fanlight Section');
     $('.overPanelLabel').html('Overpanel/Fanlight');
     $('.OpWidthCase').html('OP/FL Width');
     $('.OpHeightMax').html('OP/FL Height (Max-value:600)');
     $('#oPHeigth').attr('max',600);

     //side light
     $('.slBeadThickness').html('SL Bead Thickness');
     $('#SlBeadThickness').removeAttr('min');
     $('.slBeadHeightMin').html('SL Bead Height');
     $('#SlBeadHeight').removeAttr('min');
    //  $('#SlBeadThickness').val('');
    //  $('#SlBeadHeight').val('');

     //frame
    //  $('#plantonStopWidth').val('');
    //  $('#plantonStopHeight').val('');

      // Lipping & Intumescent
      $('#intumescentSealType').attr({ 'required':false})
           $('#intumescentSealLocation').attr({ 'required':false})
           $('#intumescentSealColor').attr({ 'required':false})
           $('#intumescentSealArrangement').attr({ 'required':false})
           $('#frameDepth').attr({ 'required':false})



       if(fireRating == 'FD30'){


           // Lipping & Intumescent
           $('#intumescentSealType').attr({ 'required':true})
           $('#intumescentSealLocation').attr({ 'required':true})
           $('#intumescentSealColor').attr({ 'required':true})
           $('#intumescentSealArrangement').attr({ 'required':true})
           $('#frameDepth').attr({ 'required':true})

          $('#plan-on-stop-min-width').html('(min 32 MM)');
          $('#plantonStopWidth').attr('min',32);
           $('#plan-on-stop-min-height').html('(min 12 MM)');
          $('#plantonStopHeight').attr('min',12);
          $('#frame-depth-min').html('(min 70 MM)');
          $('#frameDepth').attr('min',70);
          $('.OverPanelTitle').html('Overpanel/Fanlight');
          $('.OpWidthCase').html('OP/FL width');
          $('.OpHeightMax').html('OP/FL Height (Max-value:600)');
          $('.slBeadThickness').html('SL Bead Thickness (min 20mm)');
          $('#SlBeadThickness').attr('min',20);
         $('.slBeadHeightMin').html('SL Bead Height (min 20mm)');
          $('#SlBeadHeight').attr('min',20);

       }

       if(fireRating == 'FD60'){

           // Lipping & Intumescent
           $('#intumescentSealType').attr({ 'required':true})
           $('#intumescentSealLocation').attr({ 'required':true})
           $('#intumescentSealColor').attr({ 'required':true})
           $('#intumescentSealArrangement').attr({ 'required':true})
           $('#frameDepth').attr({ 'required':true})

        $('#plan-on-stop-min-width').html('(min 32 MM)');
          $('#plantonStopWidth').attr('min',32);
           $('#plan-on-stop-min-height').html('(min 12 MM)');
          $('#plantonStopHeight').attr('min',12);
          $('#frame-depth-min').html('(min 70 MM)');
          $('#frameDepth').attr('min',70);
          $('.slBeadThickness').html('SL Bead Thickness (min 20mm)');
          $('#SlBeadThickness').attr('min',20);
          $('.slBeadHeightMin').html('SL Bead Height (min 20mm)');
          $('#SlBeadHeight').attr('min',20);
       }

       if(fireRating == 'FD30s'){
        $('#intumescentSealArrangement').attr({ 'required':true})
       }

       if(fireRating == 'FD60s'){
        $('#intumescentSealArrangement').attr({ 'required':true})
       }

       let doorSetType = $('#doorsetType').val();
        if(doorSetType == 'SD' && (fireRating == 'FD30' || fireRating == 'FD60')){
          $('.OpHeightMax').html('OP/FL Heigth (Max-value:2000)');
          $('#oPHeigth').attr('max',2000);
        }

        if(doorSetType == 'DD' && (fireRating == 'FD30' || fireRating == 'FD60')){

            $('.OpHeightMax').html('OP/FL Heigth (Max-value:1500)');
            $('#oPHeigth').attr('max',1500);
        }
        let fanLightOverpanel = $('#overpanel').val();
        if((doorSetType == 'DD' || doorSetType == 'SD') && fanLightOverpanel == 'Fan_Light'  && (fireRating == 'FD30' || fireRating == 'FD60')){

            $('.OpHeightMax').html('OP/FL Height (Max-value:600)');
            $('#oPHeigth').attr('max',600);
        }
        frameonoff();
    }


    $(document).on('change','#doorsetType', function(){
      let doorSetType = $(this).val();
      let fireRating = $('#fireRating').val();

      $('.OpHeightMax').html('OP/FL Height (Max-value:600)');
     $('#oPHeigth').attr('max',600);

      if(doorSetType == 'SD' && (fireRating == 'FD30' || fireRating == 'FD60')){

          $('.OpHeightMax').html('OP/FL Heigth (Max-value:2000)');
          $('#oPHeigth').attr('max',2000);
        }

        if(doorSetType == 'DD' && (fireRating == 'FD30' || fireRating == 'FD60')){

            $('.OpHeightMax').html('OP/FL Heigth (Max-value:1500)');
            $('#oPHeigth').attr('max',1500);
        }

        let fanLightOverpanel = $('#overpanel').val();
        if((doorSetType == 'DD' || doorSetType == 'SD') && fanLightOverpanel == 'Fan_Light'  && (fireRating == 'FD30' || fireRating == 'FD60')){

            $('.OpHeightMax').html('OP/FL Height (Max-value:600)');
            $('#oPHeigth').attr('max',600);
        }

    });

    $(document).on('change','#overpanel', function(){

        let fanLightOverpanel = $(this).val();
        let doorSetType = $('#doorsetType').val();
        let fireRating = $('#fireRating').val();

        $('.OpHeightMax').html('OP/FL Height (Max-value:600)');
        $('#oPHeigth').attr('max',600);

      if(doorSetType == 'SD' && (fireRating == 'FD30' || fireRating == 'FD60')){
          $('.OpHeightMax').html('OP/FL Heigth (Max-value:2000)');
          $('#oPHeigth').attr('max',2000);
        }

        if(doorSetType == 'DD' && (fireRating == 'FD30' || fireRating == 'FD60')){
            $('.OpHeightMax').html('OP/FL Heigth (Max-value:1500)');
            $('#oPHeigth').attr('max',1500);
        }

        if((doorSetType == 'DD' || doorSetType == 'SD') && fanLightOverpanel == 'Fan_Light'  && (fireRating == 'FD30' || fireRating == 'FD60')){
            $('.OpHeightMax').html('OP/FL Height (Max-value:600)');
            $('#oPHeigth').attr('max',600);
        }
    });
</script>

<script>
    var BomSettingsJson = JSON.stringify(<?= json_encode($BOMSetting); ?>);
    var ColorsJson = JSON.stringify(<?= json_encode($color_data); ?>);
    var OptionsJson = JSON.stringify(<?= json_encode($option_data); ?>);
    var LippingSpeciesJson = JSON.stringify(<?= json_encode($lipping_species); ?>);
    var ConfigurableDoorFormulaJson = JSON.stringify(<?= json_encode($ConfigurableDoorFormula); ?>);
    var IronmongeryJson = JSON.stringify(<?= json_encode($setIronmongery); ?>);
    //var BomDoorCoresJson = JSON.stringify(<?//= json_encode($BOMDoorCores); ?>);
var SelectedOptionsJson = JSON.stringify(<?= json_encode($selected_option_data); ?>);
    var possibleSelectedOptionsJson = JSON.stringify(<?=json_encode(\Config::get('constants.PossibleSelectedOptions.SelectedOptionsWithDbSlugKey'))?>);

    $(document).ready(function(){

        // lipping section for Seadec
        setTimeout(function(){
            AdjustmentLipping();
        }, 3000)

        setTimeout(function(){
            editFirerating();
            groovesNumbershow();
        }, 200)
        // IntumescentSealArrangementValue2();
        IntumescentSealArrangementValue();
        $('#lippingType').prop('disabled', true);
        $('#lippingThickness').prop('disabled', true);
        $('#lippingSpecies').prop('disabled', true).removeClass("bg-white");
        $('#coreWidth1').prop('disabled', true);
        $('#coreWidth2').prop('disabled', true);
        $('#coreHeight').prop('disabled', true);



        let pageIdentity = $('#pageIdentity').val();
        if(pageIdentity == 5){
        $('#transportSection').css('display', 'none');
        }else{
            $('#transportSection').css('display', 'block');
        }
        commanLeafType();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        // let editDoorleafFacingValue = JSON.stringify(<?= json_encode(isset($Item['DoorLeafFacing'])); ?>);

        selectIronMongery();
    labourPrice();
    @if(isset($Item['FireRating']))
    FireRatingChange();
    @endif

    @if(isset($Item['FireRating']) &&  $Item['FireRating'] == 'NFR')
    doorThicknessSelect("{{ $Item['LeafThickness'] }}");
    @endif

    @if(isset($Item['GlassIntegrity']))
    glassTypeFilter(true);
    doorThicknessFilter("{{$Item['FireRating']}}");
    glazingSystemFIlter("{{$Item['FireRating']}}");
    @endif

    @if(isset($Item['opGlassIntegrity']))
    doorThicknessFilter("{{$Item['FireRating']}}",true);
    @endif

    @if(isset($Item['DoorsetType']) && isset($Item['SwingType']))
    DoorSetTypeChange();
    @endif

    @if(isset($Item['DoorLeafFacing']))
    DoorLeafFacingChange();
    doorLeafFacingPrice('doorLeafFacing',"@if (isset($Item['DoorLeafFacing'])){{ $Item['DoorLeafFacing'] }}@endif");
    @endif

    @if(isset($Item['DoorLeafFinish']))
    doorLeafFacingPrice('doorLeafFinish',"{{$Item['DoorLeafFinish']}}");
    doorLeafFacingPrice('doorLeafFinish1',"{{$Item['DoorLeafFinish']}}");
    @endif

    @if(isset($Item["GlassType"]))
    GlassTypeChange("{{$Item['GlassType']}}");
    @endif

    @if(isset($Item["opGlassType"]))
    GlassTypeChange("{{$Item['opGlassType']}}","opGlassType");
    @endif
    @if(isset($Item["SideLight1GlassType"]))
    GlassTypeChange("{{$Item['SideLight1GlassType']}}","sideLight1GlassType");
    @endif
    @if(isset($Item["SideLight2GlassType"]))
    GlassTypeChange("{{$Item['SideLight2GlassType']}}","sideLight2GlassType");
    @endif

    @if(isset($Item["opGlassIntegrity"]))
    opGlassTypeFilter("{{$Item['opGlassIntegrity']}}","{{$Item['OPGlassType']}}");
    @endif

    @if(isset($Item["GlazingSystems"]))
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

    @if(isset($Item["FrameType"]) && $Item["FrameType"] == "Plant_on_Stop")
    FramePrice('Plant_on_Stop');
    @endif

    @if(isset($Item["FrameType"]) && $Item["FrameType"] == "Rebated_Frame")
    FramePrice('Rebated_Frame');
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

    @if(isset($Item['IronmongeryID']) && $Item['IronmongerySet'] == "Yes")
    IronmongeryIDItemsPrice();
    IronmongeryIDPrice();
    @endif

    @if(isset($Item['ExtLiner']) && $Item['ExtLiner'] == "Yes")
    doorLeafFacingPrice('extLiner');
    FramePrice('extLiner');
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

    @if(isset($Item['Overpanel']) && $Item['Overpanel'] == "Fan_Light")
    doorLeafFacingPrice('overpanel',"{{$Item['Overpanel']}}");
    doorLeafFacingPrice('overpanel1',"{{$Item['Overpanel']}}");
    doorLeafFacingPrice('overpanel2',"{{$Item['Overpanel']}}");
    FramePrice('overpanel3');
    @endif

    @if(isset($Item['SideLight1']) && $Item['SideLight1'] == "Yes")
    sideLight1Change();
    doorLeafFacingPrice('sideLight1',"{{$Item['SideLight1']}}");
    doorLeafFacingPrice('sideLight11',"{{$Item['SideLight1']}}");
    doorLeafFacingPrice('sideLight2',"{{$Item['SideLight1']}}");
    FramePrice('sideLight3');
    @endif

    @if(isset($Item['SideLight2']) && $Item['SideLight2'] == "Yes")
    sideLight2Change();
    doorLeafFacingPrice('sideLight12',"{{$Item['SideLight2']}}");
    @endif

    @if(isset($Item['DoYouWantToCopySameAsSL1']) && $Item['DoYouWantToCopySameAsSL1'] == "Yes")
    copyOfSideLite1Change();
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

    });
    commanLeafType();
    function commanLeafType() {
    let doorLeafType = $('#leafConstruction').val();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "{{ route('quotation/doorLeafFacingValue') }}",
        data: {
            doorLeafType: doorLeafType,
            pageId:5,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.status === 'ok' && Array.isArray(response.doorleafFacingOption)) {

                let editDoorLeafFacingValue = <?= isset($Item['DoorLeafFacing']) ? json_encode($Item['DoorLeafFacing']) : 'null'; ?>;
                let editdoorLeafType = <?= isset($Item['LeafConstruction']) ? json_encode($Item['LeafConstruction']) : 'null'; ?>;

                let html = '<option value="">Select Leaf Facing</option>';

                response.doorleafFacingOption.forEach(function(item) {
                    html += `<option value="${item.doorLeafFacingValue}">${item.doorLeafFacingValue}</option>`;
                });

                $('#doorLeafFacing').html(html);
                // $('#doorLeafFacing option[value=' + editDoorLeafFacingValue + ']').attr('selected', 'selected');
                if(doorLeafType === editdoorLeafType){
                    setTimeout(() => {
                        $('#doorLeafFacing').val(editDoorLeafFacingValue);
                    }, 100);
                }else{
                    $("#DoorDimensions,#DoorDimensionId,#doorDimensionHeightWidth,#sOWidth,#sOHeight").val('');
                }
            } else {
                console.error('Invalid response format');
            }

            if (response.status === 'ok' && Array.isArray(response.doorLeafFinish)) {

                let editDoorLeafFinishValue = <?= isset($Item['DoorLeafFinish']) ? json_encode($Item['DoorLeafFinish']) : 'null'; ?>;

                let html1 = '<option value="">Select Door Leaf Finish</option>';

                response.doorLeafFinish.forEach(function(val) {

                    //let isSelectedfinish = val.ColorName === editDoorLeafFinishValue;

                   html1 += `<option value="${val.ColorName}">${val.ColorName}</option>`;
                });
                $('#doorLeafFinish').html(html1 );
                if(editDoorLeafFinishValue){
                    $('#doorLeafFinish option[value=' + editDoorLeafFinishValue + ']').attr('selected', 'selected');
                }
            } else {
                console.error('Invalid response format');
            }

        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Ajax request failed: " + textStatus, errorThrown);
        }
    });
}

function lippingTypeValue() {
    let fireRating = $('#fireRating').val();
    if(!fireRating){
        return false;
    }
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "{{ route('quotation/lippingtype') }}",
        data: {
            fireRating: fireRating,
            pageId:5,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.status === 'ok' && Array.isArray(response.lipping_type_value)) {

                 let editDoorLeafFacingValue = $('#LippingTypeEditValue').val();

                let html = '<option value="">Select Lipping Type</option>';

                response.lipping_type_value.forEach(function(item) {
                    isSelected = item.OptionKey.trim() === editDoorLeafFacingValue.trim();
                    html += `<option value="${item.OptionKey}" ${isSelected ? 'selected' : ''}>${item.OptionValue}</option>`;
                });

                $('#lippingType').html(html);
            } else {
                console.error('Invalid response format');
            }

        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Ajax request failed: " + textStatus, errorThrown);
        }
    });
}

$(document).on('change', '#intumescentSealType', function(e) {
    IntumescentSealArrangementValue();
});

function IntumescentSealArrangementValue2() {
    let fireRating = $('#fireRating').val();
    $('#intumescentSealArrangement').html('<option value="">Select Intumescent Seal Arrangement</option>');
    if(!fireRating){
        return false;
    }
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "{{ route('quotation/IntumescentSealArrangementOption') }}",
        data: {
            fireRating: fireRating,
            pageId:5,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.status === 'ok' && Array.isArray(response.intumescentSealArrangementOption)) {

                let IntumescentLeapingSealArrangement = <?= isset($Item['IntumescentLeapingSealArrangement']) ? json_encode($Item['IntumescentLeapingSealArrangement']) : 'null'; ?>;
                let html = '<option value="">Select Intumescent Seal Arrangement</option>';

                response.intumescentSealArrangementOption.forEach(function(item) {
                    let isSelected = item.id == IntumescentLeapingSealArrangement;
                     //alert(isSelected);
                    html += `<option value="${item.id}" ${isSelected ? 'selected' : ''}>${item.intumescentSeals}</option>`;
                });

                $('#intumescentSealArrangement').html(html);
            } else {
                console.error('Invalid response format');
            }

        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Ajax request failed: " + textStatus, errorThrown);
        }
    });
}

function IntumescentSealArrangementValue() {
    let fireRating = $('#fireRating').val();
    let intumescentSealType = $('#intumescentSealType').val();
    $('#intumescentSealArrangement').html('<option value="">Select Intumescent Seal Arrangement</option>');
    if(!fireRating || !intumescentSealType){
        return false;
    }
    $.ajax({
        type: "POST",
        dataType: "json",
        url: "{{ route('quotation/IntumescentSealArrangementValue') }}",
        data: {
            fireRating: fireRating,
            intumescentSealType:intumescentSealType,
            pageId:5,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {
            if (response.status === 'ok' && Array.isArray(response.intumescentSealArrangementValue)) {

                let IntumescentLeapingSealArrangement = <?= isset($Item['IntumescentLeapingSealArrangement']) ? json_encode($Item['IntumescentLeapingSealArrangement']) : 'null'; ?>;

                let html = '<option value="">Select Intumescent Seal Arrangement</option>';

                response.intumescentSealArrangementValue.forEach(function(item) {

                    let isSelected = item.intumescentseals2_id == IntumescentLeapingSealArrangement;

                    html += `<option value="${item.intumescentseals2_id}" ${isSelected ? 'selected' : ''}>${item.intumescentSeals}</option>`;
                });

                $('#intumescentSealArrangement').html(html);
            } else {
                console.error('Invalid response format');
            }

        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Ajax request failed: " + textStatus, errorThrown);
        }
    });
}

$(document).on('change', '#lippingType', function(e) {
    $('#lippingThickness').val('');
    $('#LippingThicknessValue').val('')
    changeLippingThickness();
});

function changeLippingThickness(){

    let lippingType = $('#lippingType').val();
    let fireRating = $('#fireRating').val();
    let LippingThicknessValue = $('#LippingThicknessValue').val();

    $('#lippingThickness').html('<option value="">Select Leaping Thickness</option>');

    let html = '<option value="">Select Leaping Thickness</option>';

     if(lippingType == 'Exposed' && (fireRating == 'FD30' || fireRating == 'FD30s')){

    for(let i = 5; i <= 10; i++ ){
        isSelected = i == LippingThicknessValue.trim();
        html += `<option value="${i}" ${isSelected ? 'selected' : ''}>${i}</option>`;
    }

     }

     if(lippingType == 'Scalloped' && (fireRating == 'FD30' || fireRating == 'FD30s')){
        for(let i = 7; i <= 12; i++ ){
            isSelected = i == LippingThicknessValue.trim();
        html += `<option value="${i}" ${isSelected ? 'selected' : ''}>${i}</option>`;
    }
     }

     if(lippingType == 'Exposed' && (fireRating == 'NFR')){

        for(let i = 5; i <= 10; i++ ){
            isSelected = i == LippingThicknessValue.trim();
            html += `<option value="${i}" ${isSelected ? 'selected' : ''}>${i}</option>`;
        }

         }

         if(lippingType == 'Scalloped' && (fireRating == 'NFR')){
            for(let i = 7; i <= 12; i++ ){
                isSelected = i == LippingThicknessValue.trim();
            html += `<option value="${i}" ${isSelected ? 'selected' : ''}>${i}</option>`;
        }
         }

     if (lippingType == 'Flat_Exposed' && (fireRating == 'FD60' || fireRating == 'FD60s')) {
    for (let i = 6; i <= 18; i++) {
        isSelected = i == LippingThicknessValue.trim();
        html += `<option value="${i}" ${isSelected ? 'selected' : ''}>${i}</option>`;
    }
   }
   if(lippingType == 'Exposed' && ( fireRating == 'NFR')){

    for(let i = 1; i <= 18; i++ ){
        isSelected = i == LippingThicknessValue.trim();
        html += `<option value="${i}" ${isSelected ? 'selected' : ''}>${i}</option>`;
    }

     }

     if(lippingType == 'Scalloped' && (fireRating == 'NFR')){
        for(let i = 1; i <= 18; i++ ){
            isSelected = i == LippingThicknessValue.trim();
        html += `<option value="${i}" ${isSelected ? 'selected' : ''}>${i}</option>`;
    }
     }

   if (lippingType == 'Scalloped' && (fireRating == 'FD60' || fireRating == 'FD60s')) {
    for (let i = 8; i <= 18; i++) {
        isSelected = i == LippingThicknessValue.trim();
        html += `<option value="${i}" ${isSelected ? 'selected' : ''}>${i}</option>`;
    }
   }

     $('#lippingThickness').html(html);
}


    $(document).on('change', '.changeLeaftype', function(e) {

        commanLeafType();
});


        $(document).on('click','#submit',function(e){
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
            $(".optionItem a").css({'background':'#3b86ff'});
            $('#'+i.id).css({'border':'1px solid #ced4da'});
        });

        document.getElementById("itemForm").querySelectorAll("[required]").forEach(function(i) {
                // if (!allAreFilled) return;
            if (!i.value){
                parentid = $('#'+i.id).parents().eq(6).parent('div').attr('id');
                $( "a[href='#"+parentid+"']").css({'background':'red'});
                $('#'+i.id).css({'border':'1px solid red'});
                label = $('#'+i.id).siblings('label').clone().children().remove().end().text();
                RequiredFields += '<li><i class="fas fa-exclamation-triangle"></i> '+ label +' field is required.</li>';
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
                            `+RequiredFields+`
                        </ul>
                    </div>`
            );
            setTimeout(() => {
                $('.error').html('')
            }, 10000);
            return false;
        }

        var data = $('#itemForm').serialize();
        var data2 = JSON.stringify( $('#itemForm').serializeArray() );
        $('.loader').empty().css({
            'display': 'block'
        });
        // console.table(data2) alert('rrrr'); return false;
        $.ajax({
            url: "{{route('items/store1')}}",
            type: 'POST',
            data: data,
            datatype: "json",
            success: function(res){
                console.log(res);
                // console.log(res.data)
                if(res.status == 'error'){
                    $('.error').empty().html(
                    `<div class="alert notify alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <h5><i class="fas fa-exclamation"></i> Alert!</h5>
                        <ul>
                            `+res.errors+`
                        </ul>
                    </div>`);
                    setTimeout(() => {
                        $('.error').html('')
                    }, 10000);
                    $('.loader').empty().css({
                        'display': 'none'
                    });
                } else if(res.status == 'success'){
                    $('.loader').empty().css({
                        'display': 'none'
                    });
                    window.location.href = "{{url('/')}}/"+res.url;
                }  else if (res.status == 'success1') {
                    window.location.replace(document.referrer);
                }
            }
        });
    });

        $(document).on('click','.optionItem',function(){
            let ScrolHeight = $(this).children('input').val();
            // alert($("#opDiv").scrollTop() + " px");
            $('#opDiv').animate({scrollTop: ScrolHeight});
        });

        $(function(){
            $('.Seadecframe').hide();
        })
        $(document).on('change','#frameType',function(){
            const frametype_C = $(this).val();
            if(frametype_C == 'Standard'){
                $('.halspanframe').show();
            } else {
                $('.halspanframe').hide();
            }
        })

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
        $('#submit').attr({'disabled': true,"readonly":true });
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
<!-- Modal -->
<div class="modal fade" id="iron" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="display:block !important;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
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
<div class="modal fade bd-example-modal-lg" id="frameMaterialModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="frameMaterialModalLabel">Frame Materials</h5>
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="frameMaterialModalBody">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
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
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="inputId">
                <div id="glazingModalBody">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
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
                <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="inputId">
                <div id="DoorFrameConstructionModalBody">
                    <div class="container">
                        <div class="row">
                            @foreach($option_data as $row)
                                @if($row->OptionSlug=='Door_Frame_Construction')
                                    <div class="col-md-2 col-sm-4 col-6 cursor-pointer" onclick="DoorFrameConstruction('#frameCostuction','{{$row->OptionKey}}','{{$row->OptionValue}}')">
                                        <div class="color_box">
                                            <div class="frameMaterialImage">
                                                <img width="100%" height="100" src="{{url('/')}}/uploads/Options/{{$row->file}}">
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
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!--door dimensions modal popup-->
<div class="modal fade bd-example-modal-lg" id="DoorDimension" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="DoorDimensionLabel">All DoorDimension</h5>
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal"
                    aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <div class="row" id="DoorDimensionBody">

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
<!--door dimensions2 modal popup-->
<div class="modal fade bd-example-modal-lg" id="DoorDimension2" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="DoorDimensionLabel">All DoorDimension</h5>
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal"
                    aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <div class="row" id="DoorDimensionBody2">

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

<!--door dimensions2 modal popup-->

<div class="modal fade bd-example-modal-lg" id="DoorDimension2" tabindex="-1" role="dialog"

    aria-labelledby="myLargeModalLabel" aria-hidden="true">

    <div class="modal-dialog modal-lg">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="DoorDimensionLabel">All DoorDimension</h5>

                <button type="button" class="btn btn-default btn-close" data-dismiss="modal"

                    aria-label="Close">X</button>

            </div>

            <div class="modal-body">

                <div class="row" id="DoorDimensionBody2">



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

<!--door groove modal popup-->
<div class="modal fade bd-example-modal-lg" id="DoorDimensionGrooves" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="DoorDimensionLabel">All Grooves</h5>
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal"
                    aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="inputId">
                <div class="row" id="DoorDimensionBodyGroove">

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
