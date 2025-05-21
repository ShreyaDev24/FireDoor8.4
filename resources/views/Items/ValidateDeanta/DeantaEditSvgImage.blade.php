@extends("layouts.CadMaster")
@section("main_section")


<style>
    .input-icons i {
        position: absolute;
        right: 0;
        top: 27px;
    }
    .custom-checkbox {
        margin: 2px -4px 10px 12px;
        border: 1px solid #ced4da;
        height: 15px;
        width: 15px;
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
                                        <a class="btn btn-primary" data-toggle="tab" href="#over-panel-section">Overpanel/Fanlight</a>
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
                                        <a class="btn btn-primary" data-toggle="tab" href="#accoustics-section">Accoustics</a>
                                        <input type="hidden" value="3892px">
                                    </li>
                                    <li class="optionItem framehideshow">
                                        <a class="btn btn-primary" data-toggle="tab" href="#architrave-section">Architrave</a>
                                        <input type="hidden" value="3840px">
                                    </li>
                                    <li class="optionItem" id="transportSection">
                                        <a class="btn btn-primary" data-toggle="tab" href="#transport-section">Transport</a>
                                    </li>
                                </ul>
                            </div>
                            <button role="button" id="arrow-right" class="arrow-right border-0 text-secondary p-1 rounded"><i class="fa fa-chevron-right"></i></button>
                        </div>
                    </div>
                </div>


           <div class="item-form">
                <form id="itemForm" enctype="multipart/form-data" >
                    <input type="hidden" name="pageIdentity" id="pageIdentity" value="6">
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
                            @include("Items.ValidateDeanta.MainOptions")
                         </div>

                        <div id="door-dimensions-n-door-leaf-section" class="tab-pane fade">
                            @include("Items.ValidateDeanta.DoorDimensionsAndDoorLeaf")
                        </div>

                        <div id="vision-panel-section" class="tab-pane fade">
                            @include("Items.ValidateDeanta.VisionPanel")
                        </div>

                        <div id="frame-section" class="tab-pane fade framehideshow">
                            @include("Items.ValidateDeanta.Frame")
                        </div>

                        <div id="over-panel-section" class="tab-pane fade framehideshow">
                            @include("Items.ValidateDeanta.OverPanel")
                        </div>

                        <div id="side-light-section" class="tab-pane fade framehideshow">
                            @include("Items.ValidateDeanta.SideLight")
                        </div>

                        <div id="lipping-and-intumescent-section" class="tab-pane fade">
                            @include("Items.ValidateDeanta.LippingAndIntumescent")
                        </div>

                        <div id="accoustics-section" class="tab-pane fade">
                            @include("Items.ValidateDeanta.Accoustics")

                        </div>

                        <div id="architrave-section" class="tab-pane fade framehideshow">
                            @include("Items.ValidateDeanta.Architrave")
                        </div>

                        <div id="transport-section" class="tab-pane fade">
                            @include("Items.ValidateDeanta.Transport")
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
                <input type="hidden" id="itemID" value="{{ $Item["itemId"] }}">
                </form>
                <div class="tab-content item-form" id="opDiv">
                    {{-- <div id="door" class="tab-pane active">
                        <div id='container'></div>
                    </div>
                    <div id="BuildOfMaterial" class="tab-pane table-responsive">
                      @include("Items.Vicaima.VicaimaBuildOfMaterialForCadDoor")

                    </div>
                    <div id="doorPrice" class="tab-pane table-responsive" >
                        @include("Items.DoorPriceForCadDoor")
                    </div> --}}
                </div>
            </div>
        </div>
    </div>

</div>




<script src="{{url('/')}}/Deanta/Deanta-cad-door-configuration.js"></script>

@if(!empty($Item))
@foreach($Item as $key => $val)
    <div id="{{$key}}-value" data-value="{{$val}}" hidden=""></div>
@endforeach
@endif

@endsection


@section("js")



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
