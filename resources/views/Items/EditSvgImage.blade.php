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
                '<i class="fa fa-info-circle field_info tooltip" aria-hidden="true"><span class="tooltiptext info_tooltip">' +
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
                                    <li class="optionItem">
                                        <a class="btn btn-primary" data-toggle="tab" href="#frame-section">Frame</a>
                                        <input type="hidden" value="2039px">
                                    </li>
                                    <li class="optionItem">
                                        <a class="btn btn-primary" data-toggle="tab" href="#over-panel-section">Over
                                            Panel Section</a>
                                        <input type="hidden" value="2656px">
                                    </li>
                                    <li class="optionItem">
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
                                    <li class="optionItem">
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
                        <input type="hidden" name="pageIdentity" id="pageIdentity" value="1">
                        <input type="hidden" name="version_id" value="<?= (!is_null($versionId))?$versionId:0; ?>">

                        <div class="tab-content">
                            <div id="main-options-section" class="tab-pane active">
                                @include("Items.EditSvg.MainOptions")
                            </div>

                            <div id="door-dimensions-n-door-leaf-section" class="tab-pane fade">
                                @include("Items.EditSvg.DoorDimensionsAndDoorLeaf")
                            </div>

                            <div id="vision-panel-section" class="tab-pane fade">
                                @include("Items.EditSvg.VisionPanel")
                            </div>

                            <div id="frame-section" class="tab-pane fade">
                                @include("Items.EditSvg.Frame")
                            </div>

                            <div id="over-panel-section" class="tab-pane fade">
                                @include("Items.EditSvg.OverPanel")
                            </div>

                            <div id="side-light-section" class="tab-pane fade">
                                @include("Items.EditSvg.SideLight")
                            </div>

                            <div id="lipping-and-intumescent-section" class="tab-pane fade">
                                @include("Items.EditSvg.LippingAndIntumescent")
                            </div>

                            <div id="acoustics-section" class="tab-pane fade">
                                @include("Items.EditSvg.Accoustics")

                            </div>

                            <div id="architrave-section" class="tab-pane fade">
                                @include("Items.EditSvg.Architrave")
                            </div>

                            <div id="transport-section" class="tab-pane fade">
                                <!-- @include("Items.EditSvg.Transport") -->
                            </div>
                        </div>




                        <div hidden id="glazing-system-filter">{{url('items/glazing-system-filter')}}</div>
                        <div hidden id="fire-rating-filter">{{url('items/fire-rating-filter')}}</div>
                        <div hidden id="glazing-beads-filter">{{url('items/glazing-beads-filter')}}</div>
                        <div hidden id="glass-type-filter">{{url('items/glass-type-filter')}}</div>
                        <div hidden id="glazing-thikness-filter">{{url('items/glazing-thikness-filter')}}</div>
                        <div hidden id="frame-material-filter">{{url('items/frame-material-filter')}}</div>
                        <div hidden id="scallopped-lipping-thickness">{{url('items/scallopped-lipping-thickness')}}
                        </div>
                        <div hidden id="flat-lipping-thickness">{{url('items/flat-lipping-thickness')}}</div>
                        <div hidden id="rebated-lipping-thickness">{{url('items/rebated-lipping-thickness')}}</div>
                        <div hidden id="door-thickness-filter">{{url('items/door-thickness-filter')}}</div>
                        <div hidden id="door-leaf-face-value-filter">{{url('items/door-leaf-face-value-filter')}}
                        </div>
                        <div hidden id="ral-color-filter">{{url('items/ral-color-filter')}}</div>
                        <div hidden id="filter-iron-mongery-category">
                            {{url('ironmongery-info/filter-iron-mongery-category')}}
                        </div>
                        <div hidden id="url">{{url('/')}}</div>
                        <div hidden id="get-handing-options">{{url('items/get-handing-options')}}</div>
                        <div hidden id="Filterintumescentseals">{{url('items/Filterintumescentseals')}}</div>
                        <div hidden id="opGlassTypeFilterUrl">{{url('opGlassTypeFilterUrl')}}</div>
                        <div hidden id="doorStandardPrice">{{url('doorStandardPrice')}}</div>
                        <div hidden id="IronmongeryIDPrice">{{url('IronmongeryIDPrice')}}</div>
                        <div hidden id="generalLabourCost">{{url('generalLabourCost')}}</div>
                        <div hidden id="FrameCost">{{url('FrameCost')}}</div>
                        <input type="hidden" id="fireRating" value="{{ $Item["FireRating"]}}">
                </div>
            </div>
            <div class="col-md-6">
                <div style="position: fixed;z-index: 1;background: #ffffff;padding: 10px;width: 47.5%;">
                    <ul class="nav nav-tabs border-0 float-left">
                        <li class="optionItem">
                            <a href="{{url('quotation/generate')}}/{{$QuotationId}}/{{ ($versionId !== null)?$versionId:0 }}"
                                class="door_submit" style="margin-left: 20px;margin-right: 20px">
                                <i class="fas fa-arrow-left"></i>
                            </a>
                        </li>
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
                        <li class="optionItem">
                            <a class="btn btn-primary" data-toggle="tab" href="#doorPrice" id="doorPriceCalculate">
                                <i class="fa fa-dollar" aria-hidden="true"></i>
                            </a>
                        </li>
                        <li class="optionItem">
                            <a href="javascript:void(0);" class="btn btn-primary active" onClick="render();"
                                style="margin-right: 10px">Render Image</a>
                        </li>

                        <li class="optionItem">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="change-dimension" checked>
                                <label class="form-check-label" for="change-dimension">Turn dimensions on/off</label>
                            </div>
                        </li>
                    </ul>
                    @if(Auth::user()->UserType=='1' ||Auth::user()->UserType=='2' || Auth::user()->UserType=='3' || Auth::user()->UserType=='4' || Auth::user()->UserType=='5')
                    @if(isset($quotation->QuotationStatus))
                    @if($quotation->QuotationStatus != 'Ordered')
                    <div class="float-right">
                        <button type="button" id="submit" class="btn btn-success active" style="margin-right: 10px">
                            <i class="fas fa-paper-plane"></i> @if(!empty($Item["itemId"])){{ 'Update Now' }} @else
                            {{'Submit Now'}} @endif
                        </button>
                    </div>
                    @endif
                    @endif
                    @endif
                </div>
                <input type="hidden" id="itemID" value="{{ $Item["itemId"] }}">
                </form>
                <div class="tab-content" id="opDiv">
                    {{--  <div id="BuildOfMaterial" class="tab-pane active table-responsive">  --}}
                        {{--<table id="BuildOfMaterialDetails" class="table table-bordered table-striped"></table>--}}
                        {{--  @include("Items.BuildOfMaterialForCadDoor")

                    </div>  --}}
                    <div id="door" class="tab-pane fade">
                        <div id='container'></div>
                    </div>
                    {{--  <div id="doorPrice" class="tab-pane table-responsive" >
                        @include("Items.DoorPriceForCadDoor")
                    </div>  --}}
                </div>
            </div>
        </div>
    </div>
</div>


<script src="{{url('/')}}/cad/cad-door-configuration.js"></script>
{{--  <script src="{{url('/')}}/cad/build-of-material-for-cad-door.js"></script>  --}}
{{--  <script src="{{asset('js/custome-rules.js')}}"></script>  --}}
{{--  <script src="{{asset('js/change-event-calculation.js')}}"></script>  --}}

@if(!empty($Item))
@foreach($Item as $key => $val)
<div id="{{$key}}-value" data-value="{{$val}}" hidden=""></div>
@endforeach
@endif

@endsection


@section("js")
<script>
    var fireRating = $('#fireRating').val();
    $('#fireRating').val('FD30s');
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
            url:"{{url('showAccoustic')}}",
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
