@extends("layouts.CadMaster")
@section("main_section")
<style>
  .input-icons i {
    position: absolute;
    right: 0;
    top: 27px;
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

    <div class="form-row" style="margin-top:-30px">
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
                                    <li class="optionItem" id="vision_panel_li">
                                        <a class="btn btn-primary" data-toggle="tab" href="#vision-panel-section">Vision
                                            Panel</a>
                                        <input type="hidden" value="988px">
                                    </li>
                                    <li class="optionItem" id="frame_li">
                                        <a class="btn btn-primary" data-toggle="tab" href="#frame-section">Frame</a>
                                        <input type="hidden" value="2039px">
                                    </li>
                                    <li class="optionItem">
                                        <a class="btn btn-primary" data-toggle="tab" href="#over-panel-section">Over
                                            Panel Section</a>
                                        <input type="hidden" value="2656px">
                                    </li>
                                    {{-- <li class="optionItem">
                                        <a class="btn btn-primary" data-toggle="tab" href="#side-light-section">Side
                                            Light</a>
                                        <input type="hidden" value="2958px">
                                    </li> --}}
                                    <li class="optionItem" id="lipping_and_intument">
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
                        <input type="hidden" name="pageIdentity" id="pageIdentity" value="3">
                        <input type="hidden" name="version_id" value="<?= (!is_null($versionId))?$versionId:0; ?>">
                        <input type="hidden" name="SvgImage" value="" />
                        <div class="tab-content">
                            <div id="main-options-section" class="tab-pane active">
                            @include("Items.NormaDoorCore.NormaDoorCoreModules.MainOptions")
                            </div>

                            <div id="door-dimensions-n-door-leaf-section" class="tab-pane fade">
                            @include("Items.NormaDoorCore.NormaDoorCoreModules.DoorDimensionsAndDoorLeaf")
                            </div>

                            <div id="vision-panel-section" class="tab-pane fade">
                            @include("Items.NormaDoorCore.NormaDoorCoreModules.VisionPanel")
                            </div>

                            <div id="frame-section" class="tab-pane fade">
                            @include("Items.NormaDoorCore.NormaDoorCoreModules.Frame")
                            </div>

                            <div id="over-panel-section" class="tab-pane fade">
                                @include("Items.NormaDoorCore.NormaDoorCoreModules.OverPanel")
                            </div>

                            {{-- <div id="side-light-section" class="tab-pane fade">
                                @include("Items.CadDoorModules.SideLight")
                            </div> --}}

                            <div id="lipping-and-intumescent-section" class="tab-pane fade">
                            @include("Items.NormaDoorCore.NormaDoorCoreModules.LippingAndIntumescent")
                            </div>

                            <div id="acoustics-section" class="tab-pane fade">
                            @include("Items.NormaDoorCore.NormaDoorCoreModules.Accoustics")

                            </div>

                            <div id="architrave-section" class="tab-pane fade">
                            @include("Items.NormaDoorCore.NormaDoorCoreModules.Architrave")
                            </div>

                            <div id="transport-section" class="tab-pane fade">
                            @include("Items.NormaDoorCore.NormaDoorCoreModules.Transport")
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
                    <div hidden id="glass-type-nfr">{{route('items/glass-type-nfr')}}</div>
                    <div hidden id="door-thickness-filter">{{route('items/door-thickness-filter')}}</div>
                    <div hidden id="door-leaf-face-value-filter">{{route('items/door-leaf-face-value-filter')}}</div>
                    <div hidden id="ral-color-filter">{{route('items/ral-color-filter')}}</div>
                    <div hidden id="filter-iron-mongery-category">{{route('ironmongery-info/filter-iron-mongery-category')}}
                    </div>
                    <div hidden id="url">{{url('/')}}</div>
                    <div hidden id="get-handing-options">{{route('items/get-handing-options')}}</div>
                    <div hidden id="Filterintumescentseals">{{route('Filterintumescentseals')}}</div>
                    <div hidden id="opGlassTypeFilterUrl">{{route('opGlassTypeFilterUrl')}}</div>
                    <div hidden id="lipping-thickness">{{route('items/lipping-thickness')}}</div>

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

                    <div class="float-right">
                        <button type="button" id="submit" class="btn btn-success active" style="margin-right: 10px">
                            <i class="fas fa-paper-plane"></i> @if(!empty($Item["itemId"])){{ 'Update Now' }} @else
                            {{'Submit Now'}} @endif
                        </button>
                    </div>
                    <!-- @if(Auth::user()->UserType=='1' ||Auth::user()->UserType=='2' || Auth::user()->UserType=='3' || Auth::user()->UserType=='4' || Auth::user()->UserType=='5')
                    @if(isset($quotation->QuotationStatus))
                    @if($quotation->QuotationStatus != 'Ordered')
                    @endif
                    @endif
                    @endif -->
                </div>
                </form>
                <div class="tab-content" id="opDiv">
                    <div id="BuildOfMaterial" class="tab-pane active table-responsive">
                        {{--<table id="BuildOfMaterialDetails" class="table table-bordered table-striped"></table>--}}
                        @include("Items.NormaDoorCore.BuildOfMaterialForCadDoor")

                    </div>
                    <div id="door" class="tab-pane fade">
                        <div id='container'></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>



<script src="{{url('/')}}/js/doorcore/norma/cad/cad-door-configuration.js"></script>
<script src="{{url('/')}}/js/doorcore/norma/cad/build-of-material-for-cad-door.js"></script>
<script src="{{asset('js/doorcore/norma/custome-rules.js')}}"></script>
<script src="{{asset('js/doorcore/norma/change-event-calculation.js')}}"></script>

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

    $(document).ready(function(){

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        selectIronMongery();

        @if(isset($Item['FireRating']))
            render();
            FireRatingChange();
        @endif

        @if(isset($Item['DoorsetType']) && isset($Item['SwingType']))
            DoorSetTypeChange();
        @endif

        @if(isset($Item['DoorLeafFacing']))
            DoorLeafFacingChange();
        @endif

        @if(isset($Item['GlassIntegrity']))
            glassTypeFilter(true);
            doorThicknessFilter("{{$Item['FireRating']}}");
            glazingSystemFIlter("{{$Item['FireRating']}}");
        @endif

        @if(isset($Item["GlassType"]))
            GlassTypeChange();
        @endif

        @if(isset($Item["FrameFinish"]) && $Item["FrameFinish"] == "Painted_Finish")
            FrameFinishChange();
        @endif

        @if(isset($Item["DoorLeafFinishColor"]))
        console.log("test");
            doorLeafFinishChange();
        @endif


    });


    $(document).on('click','#submit',function(e){
        let allAreFilled = true;
        let RequiredFields = "";
        let label = '';
        let parentid = '';

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
            $('.NormaDoorCoreframe').hide();
        })
        $(document).on('change','#frameType',function(){
            const frametype_C = $(this).val();
            if(frametype_C == 'Standard'){
                $('.NormaDoorCoreframe').show();
            } else {
                $('.NormaDoorCoreframe').hide();
            }
        })
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
                <input type="hidden" id="inputId">
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

<input type="hidden" id="base_url" value="{{url('/')}}">
