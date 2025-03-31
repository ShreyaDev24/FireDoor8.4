@extends("layouts.Master")

@section("main_section")
<style>
    .option-style {
        margin-right: 5px;
    }

    .update-bottom {
        margin: 3px 10px 0;
        position: absolute;
        right: 225px;
        bottom: 15px;
    }

    #example th, #example td{
        display: table-cell !important;
    }
</style>

{{-- @php
dd(1);
@endphp --}}


<div class="app-main__outer">
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible float-right">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="fas fa-skull-crossbones"></i> Alert!</h5>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if(session()->has('success'))
    <div class="alert alert-success alert-dismissible float-right">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('success') }}
    </div>
    @endif
    @if(session()->has('error'))
    <div class="alert alert-danger alert-dismissible float-right">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="fas fa-skull-crossbones"></i> Alert!</h5>
        {{ session()->get('error') }}
    </div>
    @endif

    <div class="app-main__inner">
        @if ($optionType == 'leaf1_glass_type')
            <button id="add_glassType" class="btn btn-info float-right mt-1">Add Glass Type</button>
        @elseif ($optionType == 'leaf1_glass_type_custome')
            <button id="add_glassTypeCustome" class="btn btn-info float-right mt-1">Add Glass Type</button>
        @elseif ($optionType == 'leaf_type')
            <button id="AddLeafType" class="btn btn-info float-right mt-1">Add Leaf Type</button>
        @elseif ($optionType == 'door_leaf_facing_value')
            <button id="AddDoorLeafFacing" class="btn btn-info float-right mt-1">Add Door Leaf Facing</button>
        @elseif ($optionType == 'leaf1_glazing_systems' && empty($configurableItem))
            <button id="add_glazingType" class="btn btn-info float-right mt-1">Add Glazing Systems</button>
        @elseif ($optionType == 'leaf1_glazing_systems_custome' && empty($configurableItem))
            <button id="add_glazingTypeCustome" class="btn btn-info float-right mt-1">Add Glazing Systems</button>
        @elseif ($optionType == 'Accoustics')
            <button id="AddAccoustics" class="btn btn-info float-right mt-1">Add Acoustics</button>
        @elseif ($optionType == 'Architrave_Type')
            <button id="AddArchitrave_Type" class="btn btn-info float-right mt-1">Add Architrave Type</button>
        @elseif ($optionType == 'Intumescent_Seal_Color')
            <button id="Add_IntumescentSealColor" class="btn btn-info float-right mt-1">Add Intumescent Seal Color</button>
        @elseif ($optionType == 'intumescentSealArrangement' || $optionType == 'intumescentSealArrangementCustome')
           <button id="Add_IntumescentSealArrangement" class="btn btn-info float-right mt-1">Add Intumescent Seal Arrangement</button>
            <!-- <a href="{{ url('setting/intumescentseals/1') }}" class="btn btn-info float-right mt-3">Intumescentseal
            Arrangement</a> -->
        @elseif ($optionType == 'lippingSpecies')
            <a href="{{ url('setting/lipping-species') }}" class="btn btn-info float-right mt-1">Add Timber Species</a>
        @elseif ($optionType == 'door_dimension')
            <button id="add_door_dimension" class="btn btn-info float-right mt-1">Add Door Dimension</button>
            @elseif ($optionType == 'door_dimension_custome')
            <button id="add_door_dimension_custome" class="btn btn-info float-right mt-1">Add Door Dimension</button>
            {{--  <input type="hidden" name="doorcoreValue" id="doorcoreValue" value="{{ $doorCore }}">  --}}
        @elseif ($optionType == 'color_list')
            <button id="add_color_modal" class="btn btn-info float-right mt-1">Add Colour</button>
        @elseif ($optionType == 'Overpanel_Glass_Type')
            <button id="add_Overpanel_Glass_Type" class="btn btn-info float-right mt-1">Add Glass Type</button>
        @elseif ($optionType == 'Overpanel_Glazing_System')
            <button id="Overpanel_Glazing_System" class="btn btn-info float-right mt-1">Add Glazing Systems</button>
        @elseif ($optionType == 'SideScreen_Glass_Type')
            <button id="add_SideScreen_Glass_Type" class="btn btn-info float-right mt-1">Add Glass Type</button>
        @elseif ($optionType == 'SideScreen_Glazing_System')
            <button id="add_SideScreen_Glazing_System" class="btn btn-info float-right mt-1">Add Glass Type</button>
            @endif

        @if($optionType == 'leaf1_glazing_systems' && !empty($configurableItem))
        <button id="add_GlassGlazingType" class="btn btn-info float-right mt-1">Add Glass Glazing Systems</button>

        <div class="">
            <button class="btn btn-success accordian_update_button " style="margin: 3px 10px 0;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Door Type
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{url('options/filter/leaf1_glazing_systems/Streboard')}}">Streboard</a>
                <a class="dropdown-item" href="{{url('options/filter/leaf1_glazing_systems/Halspan')}}">Halspan</a>
                <a class="dropdown-item" href="{{url('options/filter/leaf1_glazing_systems/Flamebreak')}}">Flamebreak</a>
                <a class="dropdown-item" href="{{url('options/filter/leaf1_glazing_systems/Stredor')}}">Stredor</a>
            </div>
        </div>
        @endif
        {{--  @if(Auth::user()->UserType == 1 && $optionType == 'door_dimension')
        <div class="card">
            <div class="card-body">
                <div class="tab-content">
                   <form method="post" action="{{ route('quotation/import-denta') }}" enctype="multipart/form-data">
                      {{csrf_field()}}
                      <div class="card-body">
                         <div class="form-row">
                            <div class="col-md-3">
                               <div class="position-relative form-group">
                                  <label for="file">Excel File</label>
                                  <input name="ExcelFile" id="ExcelFile" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required class="form-control">
                               </div>
                            </div>
                            <div class="col-md-6">
                               <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
                               <input type="hidden" id="base_url" value="{{url('/')}}">
                               <div class="position-relative form-group">
                                  <label for="file" class=""></label>
                                  <input type="submit" value="Submit" class="btn btn-success" style="margin-top: 25px;">
                               </div>
                            </div>
                         </div>
                      </div>
                   </form>
                </div>
            </div>
            @endif  --}}
            @if(Auth::user()->UserType == 1 && $optionType == 'leaf1_glass_type_custome')
            <div class="card-body">
                <div class="tab-content">
                   <form method="post" action="{{ route('option/import-glasstype') }}" enctype="multipart/form-data">
                      {{csrf_field()}}
                      <div class="card-body">
                         <div class="form-row">
                            <div class="col-md-3">
                               <div class="position-relative form-group">
                                  <label for="file">Excel File</label>
                                  <input name="ExcelFile" id="ExcelFile" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required class="form-control">
                               </div>
                            </div>
                            <div class="col-md-6">
                               <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
                               <input type="hidden" id="base_url" value="{{url('/')}}">
                               <div class="position-relative form-group">
                                  <label for="file" class=""></label>
                                  <input type="submit" value="Submit" class="btn btn-success" style="margin-top: 25px;">
                               </div>
                            </div>
                         </div>
                      </div>
                   </form>
                </div>
            </div>
            @endif
            @if(Auth::user()->UserType == 1 && $optionType == 'leaf1_glazing_systems')
            <div class="card-body">
                <div class="tab-content">
                   <form method="post" action="{{ route('option/import-glassglazing') }}" enctype="multipart/form-data">
                    {{--  <form method="post" action="{{ route('option/import-glazing') }}" enctype="multipart/form-data">  --}}
                      {{csrf_field()}}
                      <div class="card-body">
                         <div class="form-row">
                            <div class="col-md-3">
                               <div class="position-relative form-group">
                                  <label for="file">Excel File</label>
                                  <input name="ExcelFile" id="ExcelFile" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required class="form-control">
                               </div>
                            </div>
                            <div class="col-md-6">
                               <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
                               <input type="hidden" id="base_url" value="{{url('/')}}">
                               <div class="position-relative form-group">
                                  <label for="file" class=""></label>
                                  <input type="submit" value="Submit" class="btn btn-success" style="margin-top: 25px;">
                               </div>
                            </div>
                         </div>
                      </div>
                   </form>
                </div>
            </div>
            @endif
            @if(Auth::user()->UserType == 1 && $optionType == 'intumescentSealArrangement')
            <div class="card-body">
                <div class="tab-content">
                   <form method="post" action="{{ route('option/import-intumescentSealArrangement') }}" enctype="multipart/form-data">
                      {{csrf_field()}}
                      <div class="card-body">
                         <div class="form-row">
                            <div class="col-md-3">
                               <div class="position-relative form-group">
                                  <label for="file">Excel File</label>
                                  <input name="ExcelFile" id="ExcelFile" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required class="form-control">
                               </div>
                            </div>
                            <div class="col-md-6">
                               <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
                               <input type="hidden" id="base_url" value="{{url('/')}}">
                               <div class="position-relative form-group">
                                  <label for="file" class=""></label>
                                  <input type="submit" value="Submit" class="btn btn-success" style="margin-top: 25px;">
                               </div>
                            </div>
                         </div>
                      </div>
                   </form>
                </div>
            </div>
            @endif
            @if(Auth::user()->UserType == 1 && $optionType == 'Overpanel_Glass_Type')
            <div class="card-body">
                <div class="tab-content">
                   <form method="post" action="{{ route('option/import-OverpanelGlassGlazing') }}" enctype="multipart/form-data">
                      {{csrf_field()}}
                      <div class="card-body">
                         <div class="form-row">
                            <div class="col-md-3">
                               <div class="position-relative form-group">
                                  <label for="file">Excel File</label>
                                  <input name="ExcelFile" id="ExcelFile" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required class="form-control">
                               </div>
                            </div>
                            <div class="col-md-6">
                               <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
                               <input type="hidden" id="base_url" value="{{url('/')}}">
                               <div class="position-relative form-group">
                                  <label for="file" class=""></label>
                                  <input type="submit" value="Submit" class="btn btn-success" style="margin-top: 25px;">
                               </div>
                            </div>
                         </div>
                      </div>
                   </form>
                </div>
            </div>
            @endif
            @if($optionType == 'color_list')
        <div class="">
            <button class="btn btn-success accordian_update_button " style="margin: 3px 10px 0;" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Colour Type
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <a class="dropdown-item" href="{{route('options/selected1',['color_list','Kraft_Paper'])}}">Kraft Paper</a>
                <a class="dropdown-item" href="{{route('options/selected1',['color_list','FactoryIndustrialPrimed'])}}">Factory Industrial Primed</a>
                <a class="dropdown-item" href="{{route('options/selected1',['color_list','PaintSanded'])}}">Paint Sanded</a>
                <a class="dropdown-item" href="{{route('options/selected1',['color_list','Primed2Go'])}}">Primed 2 Go</a>
            </div>
        </div>
        @endif
        {{--  @if($optionType == 'door_dimension')
        <div class="">
            <button class="btn btn-success accordian_update_button " style="margin: 3px 10px 0;" type="button" id="dropdownDoorCore" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Door Core
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownDoorCore">
                <a class="dropdown-item" href="{{route('options/filter',['door_dimension','streboard'])}}">Streboard</a>
                <a class="dropdown-item" href="{{route('options/filter',['door_dimension','halspan'])}}">Halspan</a>
                <a class="dropdown-item" href="{{route('options/filter',['door_dimension','norma'])}}">Norma</a>
                <a class="dropdown-item" href="{{route('options/filter',['door_dimension','vicaima'])}}">Vicaima DoorCore</a>
                <a class="dropdown-item" href="{{route('options/filter',['door_dimension','seadec'])}}">Seadec</a>
                <a class="dropdown-item" href="{{route('options/filter',['door_dimension','Deanta'])}}">Deanta</a>
            </div>
        </div>
        @endif  --}}

        {{--  @if($optionType == 'leaf1_glazing_systems' && !empty($configurableItem))
        <div class="">
            <button class="btn btn-success accordian_update_button " style="margin: 3px 10px 0;" type="button" id="dropdownDoorCore" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Door Core
            </button>
            <div class="dropdown-menu" aria-labelledby="dropdownDoorCore">
                <a class="dropdown-item" href="{{route('options/filter',['leaf1_glazing_systems','Streboard'])}}">Streboard</a>
                <a class="dropdown-item" href="{{route('options/filter',['leaf1_glazing_systems','Halspan'])}}">Halspan</a>
                <a class="dropdown-item" href="{{route('options/filter',['leaf1_glazing_systems','Flamebreak'])}}">Flamebreak</a>
                <a class="dropdown-item" href="{{route('options/filter',['leaf1_glazing_systems','NormaDoorCore'])}}">Norma</a>
                <a class="dropdown-item" href="{{route('options/filter',['leaf1_glazing_systems','VicaimaDoorCore'])}}">Vicaima DoorCore</a>
                <a class="dropdown-item" href="{{route('options/filter',['leaf1_glazing_systems','Seadec'])}}">Seadec</a>
                <a class="dropdown-item" href="{{route('options/filter',['leaf1_glazing_systems','Deanta'])}}">Deanta</a>
            </div>
        </div>
        @endif  --}}

            <div id="">
                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

                {!! $tbl1 !!}

                <form action="{{route('DoorDimension/delete')}}" method="post" id="dimension_delete">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id">
                    <input type="hidden" name="doorCore" id="doorCore">
                    <!-- <button type="submit" style="background:none" id=""></button> -->
                </form>

            </div>

        </div>

    </div>

</div>

<!-- Modal for Standard Glass Type-->
<div class="modal fade" id="glassTypeAddForm" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog " style="margin-top: 70px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Glass Type</h5>
            </div>
            <div class="modal-body" style="overflow-y: inherit">
                <form id='add_glasstype_form' method="post" action="{{ route('option/update-glassType') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="Glass">
                    <div class="form-row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group options">
                                    <label for="glasstype " class="d-block">Configuration<span class="text-danger">*</span></label>
                                        <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="4">Vicaima Door Core
                                        <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="5">Seadec
                                        <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="6">Deanta
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group firerating-options">
                                    <label for="glasstype" class="d-block">Fire Rating<span class="text-danger">*</span></label>
                                    <input type="checkbox" name="firerating[]" class="form-group  ml-3 option-style"
                                        value="NFR">NFR
                                    <input type="checkbox" name="firerating[]" class="form-group  ml-3 option-style"
                                        value="FD30">FD30
                                    <input type="checkbox" name="firerating[]" class="form-group  ml-3 option-style"
                                        value="FD60">FD60
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype" class="d-block">Glass Integrity<span class="text-danger">*</span></label>
                                    <input type="radio" name="integrity" class="form-group  option-style"
                                        value="Integrity_And_Insulation" required>Integrity And Insulation
                                    <input type="radio" name="integrity" class="form-group  option-style"
                                        value="Integrity_only" required>Integrity only
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glass Type<span class="text-danger">*</span></label>
                                    <input type="text" name="glassType" placeholder="Enter Glass Type Name"
                                        class="form-control " required>
                                    <input type="hidden" name="id" class="form-control">
                                    <input type="hidden" name="selectId" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glass Thickness  <span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="glassThickness" placeholder="Enter Glass Thickness"
                                        class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">VP Area Size<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="vpareasize" placeholder="Enter VPAreaSize"
                                        class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required >
                                </div>
                            </div>

                            @if(Auth::user()->UserType != 1)
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glass Price<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="glassPrice" id="glassPrice" pattern="[0-9]+([\.,][0-9]+)?" placeholder="Enter Glass Price" class="form-control " required step="0.01">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 close_btn">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Custome Glass Type-->
<div class="modal fade" id="glassTypeCustomeAddForm" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog " style="margin-top: 70px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Glass Type</h5>
            </div>
            <div class="modal-body" style="overflow-y: inherit">
                <form id='add_glasstype_formcustome' method="post" action="{{ route('option/update-glassType') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="Glass">
                    <div class="form-row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group options">
                                    <label for="glasstype " class="d-block">Configuration<span class="text-danger">*</span></label>
                                    <input type="radio" name="config[]" class="form-group  ml-3 option-style"
                                        value="1">Streboard
                                    <input type="radio" name="config[]" class="form-group  ml-3 option-style"
                                        value="2">Halspan
                                    <input type="radio" name="config[]" class="form-group  ml-3 option-style"
                                        value="7">Flamebreak
                                    <input type="radio" name="config[]" class="form-group  ml-3 option-style"
                                        value="8">Stredor
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group firerating-options">
                                    <label for="glasstype" class="d-block">Fire Rating<span class="text-danger">*</span></label>
                                    <input type="checkbox" name="firerating[]" class="form-group  ml-3 option-style"
                                        value="NFR">NFR
                                    <input type="checkbox" name="firerating[]" class="form-group  ml-3 option-style"
                                        value="FD30">FD30
                                    <input type="checkbox" name="firerating[]" class="form-group  ml-3 option-style"
                                        value="FD60">FD60
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype" class="d-block">Glass Integrity<span class="text-danger">*</span></label>
                                    <input type="radio" name="integrity" class="form-group  option-style"
                                        value="Integrity_And_Insulation" required>Integrity And Insulation
                                    <input type="radio" name="integrity" class="form-group  option-style"
                                        value="Integrity_only" required>Integrity only
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glass Type<span class="text-danger">*</span></label>
                                    <input type="text" name="glassType" placeholder="Enter Glass Type Name"
                                        class="form-control " required>
                                    <input type="hidden" name="id" class="form-control">
                                    <input type="hidden" name="selectId" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glass Thickness  <span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="glassThickness" placeholder="Enter Glass Thickness"
                                        class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glassbeads">Glazing Beads  <span class="text-danger">*</span></label>
                                    <div id="glazingBeadsContainer"></div>
                                </div>

                            </div>

                            @if(Auth::user()->UserType != 1)
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glass Price<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="glassPrice" id="glassPrice" pattern="[0-9]+([\.,][0-9]+)?" placeholder="Enter Glass Price" class="form-control " required step="0.01">
                                </div>
                            </div>
                            @endif
                            <input type="hidden" name="glassTypeurl" value="leaf1_glass_type_custome">
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 close_btn">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Leaf Type-->
<div class="modal fade" id="leafTypeAddForm" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog " style="margin-top: 70px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Leaf Type</h5>
            </div>
            <div class="modal-body" style="overflow-y: inherit">
                <form id='AddLeafType_form' method="post" action="{{ route('option/update-glassType') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="LeafType">
                    <div class="form-row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group options">
                                    <label for="glasstype " class="d-block">Configuration<span class="text-danger">*</span></label>
                                        <input type="checkbox" name="vicaimaDoorCore" class="form-group  ml-3 option-style"
                                        value="4">Vicaima Door Core
                                        <input type="checkbox" name="seadecDoorCore" class="form-group  ml-3 option-style"
                                        value="5">Seadec
                                        <input type="checkbox" name="deantaDoorCore" class="form-group  ml-3 option-style"
                                        value="6">Deanta
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Leaf Type<span class="text-danger">*</span></label>
                                    <input type="text" name="leafType" placeholder="Enter Leaf Type Name"
                                        class="form-control " required>
                                    <input type="hidden" name="id" class="form-control">
                                    <input type="hidden" name="selectId" class="form-control">
                                </div>
                            </div>

                            @if(Auth::user()->UserType != 1)
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Leaf Price<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="leafPrice" id="leafPrice" pattern="[0-9]+([\.,][0-9]+)?" placeholder="Enter Leaf Price" class="form-control " required step="0.01">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 close_btn">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Intumescent Seal Arrangement Type-->
<div class="modal fade" id="intumescentSealArrangementAddForm" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog " style="margin-top: 70px">
        <div class="modal-content">
        <div class="modal-header">
                <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Intumescent Seals</h5>
            </div>
            <div class="modal-body" style="overflow-y: inherit">
                <form id='add_intumescent_seals_arrangement_form' method="post" action="{{route('submitintumescentseals')}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="intumescentSealArrangement">
                    <div class="form-row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group options">
                                    <label for="configurableitems" class="d-block">Configurable Item<span class="text-danger">*</span></label>
                                    <input type="radio" name="configurableitems" class="form-group  ml-3 option-style "
                                        value="1" required>Streboard
                                    <input type="radio" name="configurableitems" class="form-group  ml-3 option-style"
                                        value="2" required>Halspan
                                    <input type="radio" name="configurableitems" class="form-group  ml-3 option-style"
                                        value="7" required>Flambreak
                                    <input type="radio" name="configurableitems" class="form-group  ml-3 option-style"
                                        value="8" required>Stredor
                                    <input type="radio" name="configurableitems" class="form-group  ml-3 option-style"
                                        value="3" required>NormaDoorCore
                                        <input type="radio" name="configurableitems" class="form-group  ml-3 option-style"
                                        value="4" required>VicaimaDoorCore
                                        <input type="radio" name="configurableitems" class="form-group  ml-3 option-style"
                                        value="5" required>Seadec
                                        <input type="radio" name="configurableitems" class="form-group  ml-3 option-style"
                                        value="6" required>Deanta
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group firerating-options">
                                    <label for="firerating" class="d-block">Select Fire Rating<span class="text-danger">*</span></label>
                                    <input type="radio" name="firerating" class="form-group  ml-3 option-style"
                                        value="NFR" required>NFR
                                    <input type="radio" name="firerating" class="form-group  ml-3 option-style"
                                        value="FD30" required>FD30
                                    <input type="radio" name="firerating" class="form-group  ml-3 option-style"
                                        value="FD60" required>FD60
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                <label for="configuration" class="d-block">Configuration<span class="text-danger">*</span></label>
                                    <select name="configuration" id="" class="form-control" required>
                                        <option value="">Select Configuration</option>
                                        @foreach ($IntumescentSealsConfiguration as $Configuration)
                                        <option value="{{ $Configuration->configuration }}">{{ $Configuration->configuration }}</option>
                                        @endforeach
                                    </select>
                                    <input type="hidden" name="id" class="form-control">
                                    <input type="hidden" name="selectId" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="intumescent-seals">Intumescent Seals<span class="text-danger">*</span></label>
                                    <input type="text" name="intumescentSeals" class="form-control"
                                       placeholder="Enter Intumescent Seals Name" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="brand">Brand<span class="text-danger">*</span></label>
                                    <input type="text" name="brand" class="form-control"
                                       placeholder="Enter Brand Name" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                <label for="fire-tested">Fire Tested<span class="text-danger">*</span></label>
                                    <input type="text" name="firetested" class="form-control"
                                        placeholder="Enter Fire Tested" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="h-point1">Height Point1<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="Point1height" class="form-control"
                                       placeholder="Enter Height Point 1" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="h-point2">Height Point2<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="Point2height" class="form-control"
                                        value="@if(session()->has('upd')){{session()->get('upd')->Point2height}}@endif" placeholder="Enter Height Point 2" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="w-point1">Width Point1<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="Point1width" class="form-control"
                                        placeholder="Enter Width Point 1" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="w-point2">Width Point2<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="Point2width" class="form-control"
                                      placeholder="Enter Width Point 2" required>
                                </div>
                            </div>
                            @if(Auth::user()->UserType != 1)
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">IntumescentSeal Arrangement Price<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="IntumescentSealPrice" id="IntumescentSealPrice" placeholder="Enter IntumescentSeal Price" pattern="[0-9]+([\.,][0-9]+)?" step="0.01"
                                        class="form-control " required>
                                </div>
                            </div>
                            @endif

                             <div class="col-md-12 customedoor" style="display: none">
                                <div class="position-relative form-group options">
                                    <label for="leaf_type" class="d-block">Leaf Type<span class="text-danger">*</span></label>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 close_btn">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Accoustics Type-->
<div class="modal fade" id="add_Accoustics" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog " style="margin-top: 70px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Acoustics</h5>
            </div>
            <div class="modal-body" style="overflow-y: inherit">
                <form id='add_Accoustics_form' method="post" action="{{ route('option/update-glassType') }}"
                    enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="Accoustics">
                    <div class="form-row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group options">
                                    <label for="glasstype " class="d-block">Configuration<span class="text-danger">*</span></label>
                                    <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="1">Streboard
                                    <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="2">Halspan
                                    <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="7">Flamebreak
                                    <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="8">Stredor
                                        <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="4">VicaimaDoorCore
                                        <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="5">Seadec
                                        <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="6">Deanta
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype" class="d-block">Acoustics Option<span class="text-danger">*</span></label>
                                    <input type="radio" name="AccousticsOption" class="form-group  option-style"
                                        value="Perimeter_Seal_1" required>Perimeter Seal 1
                                    <input type="radio" name="AccousticsOption" class="form-group  option-style"
                                        value="Perimeter_Seal_2" required>Perimeter Seal 2
                                    <input type="radio" name="AccousticsOption" class="form-group  option-style"
                                        value="Meeting_Stiles" required>Meeting Stiles
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Acoustics<span class="text-danger">*</span></label>
                                    <input type="text" name="AccousticsName" placeholder="Enter Acoustics Name"
                                        class="form-control " required>
                                    <input type="hidden" name="id" class="form-control">
                                    <input type="hidden" name="selectId" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Acoustics Image<span class="text-danger">*</span></label>
                                    <input type="file" name="image" id="Accoustics_image" class="form-control"
                                        placeholder="Upload Accoustics Image" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="position-relative form-group">
                                    <div id="accousticsImage"></div>
                                    <input type="hidden" id="url" value="{{ url('/') }}">
                                </div>
                            </div>
                            @if(Auth::user()->UserType != 1)
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Acoustics Price<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="AccousticsPrice" id="accousticsPrice"
                                        placeholder="Enter Acoustics Price" class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 close_btn">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal for Architrave Type-->
<div class="modal fade" id="add_Architrave_Type" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog " style="margin-top: 70px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Architrave Type</h5>
            </div>
            <div class="modal-body" style="overflow-y: inherit">
                <form id='add_Architrave_Type_form' method="post" action="{{ route('option/update-glassType') }}"
                    enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="Architrave_Type">
                    <div class="form-row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group options">
                                    <label for="glasstype " class="d-block">Configuration<span class="text-danger">*</span></label>
                                    <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="1">Streboard
                                    <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="2">Halspan
                                    <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="7">Flamebreak
                                    <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="8">Stredor
                                        <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="4">VicaimaDoorCore
                                        <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="5">Seadec
                                        <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="6">Deanta
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Architrave Type<span class="text-danger">*</span></label>
                                    <input type="text" name="ArchitraveTypeName"
                                        placeholder="Enter Architrave Type Name" class="form-control " required>
                                    <input type="hidden" name="id" class="form-control">
                                    <input type="hidden" name="selectId" class="form-control">
                                </div>
                            </div>
                            @if(Auth::user()->UserType != 1)
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Architrave Type Price<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="ArchitraveTypePrice" id="ArchitraveTypePrice"
                                        placeholder="Enter Architrave Type Price" class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 close_btn">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for add_GlassGlazingType System-->
<div class="modal fade" id="addGlassGlazingType" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog " style="margin-top: 70px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Glass Glazing System</h5>
            </div>
            <div class="modal-body" style="overflow-y: inherit">
                <form id='add_GlassGlazingType_form' method="post" action="{{ route('option/update-glassType') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="GlassGlazing">
                    <div class="form-row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group options">
                                    <label for="glasstype " class="d-block">Configuration<span class="text-danger">*</span></label>
                                    <input type="radio" name="config" id="glassconfigvalue" class="form-group  ml-3 option-style" value="1" required>Streboard
                                    <input type="radio" name="config" id="glassconfigvalue"  class="form-group  ml-3 option-style" value="2" required>Halspan
                                    <input type="radio" name="config" id="glassconfigvalue"  class="form-group  ml-3 option-style" value="7" required>Flamebreak
                                    <input type="radio" name="config" id="glassconfigvalue"  class="form-group  ml-3 option-style" value="8" required>Stredor
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glass Type<span class="text-danger">*</span></label>
                                    <select name="GlassType" id="" required class="form-control ">
                                        <option value="">Select Glass Type</option>
                                       {{-- @if(isset($GlassType))
                                        @foreach($GlassType as $val)
                                            <option value="{{ $val->GlassType }}">{{ $val->GlassType }}</option>
                                        @endforeach
                                        @endif --}}
                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glazing System<span class="text-danger">*</span></label>
                                    <select name="glazingSystem" required class="form-control ">
                                        <option value="">Select Glazing System</option>
                                       {{-- @if(isset($GlazingSystem))
                                        @foreach($GlazingSystem as $val)
                                            <option value="{{ $val->GlazingSystem }}">{{ $val->GlazingSystem }}</option>
                                        @endforeach
                                        @endif --}}
                                    </select>
                                    <input type="hidden" name="id" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">VP Area Size<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="vpareasize" placeholder="Enter VPAreaSize"
                                        class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>

                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id=''>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 close_btn">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for add_glazingType For Standard System-->
<div class="modal fade" id="glazingTypeAddForm" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog " style="margin-top: 70px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Glazing System</h5>
            </div>
            <div class="modal-body" style="overflow-y: inherit">
                <form id='add_glazingtype_form' method="post" action="{{ route('option/update-glassType') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="Glazing">
                    <div class="form-row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group options">
                                    <label for="glasstype " class="d-block">Configuration<span class="text-danger">*</span></label>
                                        <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="4">Vicaima Door Core
                                        <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="5">Seadec
                                        <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="6">Deanta
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group firerating-options">
                                    <label for="glasstype" class="d-block">Fire Rating<span class="text-danger">*</span></label>
                                    <input type="checkbox" name="firerating[]" class="form-group  ml-3 option-style"
                                        value="NFR">NFR
                                    <input type="checkbox" name="firerating[]" class="form-group  ml-3 option-style"
                                        value="FD30">FD30
                                    <input type="checkbox" name="firerating[]" class="form-group  ml-3 option-style"
                                        value="FD60">FD60
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glazing System<span class="text-danger">*</span></label>
                                    <input type="text" name="glazingSystem" placeholder="Enter Glazing System Name"
                                        class="form-control " required>
                                    <input type="hidden" name="id" class="form-control">
                                    <input type="hidden" name="selectId" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glazing Thickness<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="GlazingThickness" placeholder="Enter Glazing Thickness"
                                        class="form-control " pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glazing Bead Fixing Detail<span class="text-danger">*</span></label>
                                    <input type="text" name="GlazingBeadFixingDetail"
                                        placeholder="Enter Glazing Bead Fixing Detail" class="form-control " required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">VP Area Size<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="vpareasize" placeholder="Enter VPAreaSize"
                                        class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            @if(Auth::user()->UserType != 1)
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glass Price<span class="text-danger">*</span></label>
                                    <input type="number" name="glazingPrice" id="glazingPrice" min="0"
                                        placeholder="Enter Glazing System Price" class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 close_btn">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for add_glazingType For Custome System-->
<div class="modal fade" id="glazingTypeAddFormcustome" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog " style="margin-top: 70px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Glazing System</h5>
            </div>
            <div class="modal-body" style="overflow-y: inherit">
                <form id='glazingTypeAddFormcustome_form' method="post" action="{{ route('option/update-glassType') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="Glazing">
                    <div class="form-row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group options">
                                    <label for="glasstype " class="d-block">Configuration<span class="text-danger">*</span></label>
                                    <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="1">Streboard
                                    <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="2">Halspan
                                    <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="7">Flamebreak
                                    <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="8">Stredor

                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group firerating-options">
                                    <label for="glasstype" class="d-block">Fire Rating<span class="text-danger">*</span></label>
                                    <input type="checkbox" name="firerating[]" class="form-group  ml-3 option-style"
                                        value="NFR">NFR
                                    <input type="checkbox" name="firerating[]" class="form-group  ml-3 option-style"
                                        value="FD30">FD30
                                    <input type="checkbox" name="firerating[]" class="form-group  ml-3 option-style"
                                        value="FD60">FD60
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glazing System<span class="text-danger">*</span></label>
                                    <input type="text" name="glazingSystem" placeholder="Enter Glazing System Name"
                                        class="form-control " required>
                                    <input type="hidden" name="id" class="form-control">
                                    <input type="hidden" name="selectId" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glazing Thickness<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="GlazingThickness" placeholder="Enter Glazing Thickness"
                                        class="form-control " pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glazing Bead Fixing Detail<span class="text-danger">*</span></label>
                                    <input type="text" name="GlazingBeadFixingDetail"
                                        placeholder="Enter Glazing Bead Fixing Detail" class="form-control " required>
                                </div>
                            </div>

                            @if(Auth::user()->UserType != 1)
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glass Price<span class="text-danger">*</span></label>
                                    <input type="number" name="glazingPrice" id="glazingPrice" min="0"
                                        placeholder="Enter Glazing System Price" class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 close_btn">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal for Intumescent_Seal_Color Type-->
<div class="modal fade" id="Add_Intumescent_Seal_Color" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog " style="margin-top: 70px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Intumescent Seal Color</h5>
            </div>
            <div class="modal-body" style="overflow-y: inherit">
                <form id='add_Intumescent_form' method="post" action="{{ route('option/update-glassType') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="IntumescentSealColor">
                    <div class="form-row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group options">
                                    <label for="glasstype " class="d-block">Configuration<span class="text-danger">*</span></label>
                                    <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="1">Streboard sdf
                                    <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="2">Halspan
                                    <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="7">Flamebreak
                                    <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="8">Stredor
                                         <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="4">VicaimaDoorCore
                                         <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="5">Seadec
                                         <input type="checkbox" name="config[]" class="form-group  ml-3 option-style"
                                        value="6">Deanta
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Intumescent Seal Color<span class="text-danger">*</span></label>
                                    <input type="text" name="IntumescentSealColorName"
                                        placeholder="Enter Intumescent Seal Color" class="form-control " required>
                                    <input type="hidden" name="id" class="form-control">
                                    <input type="hidden" name="selectId" class="form-control">
                                </div>
                            </div>
                            @if(Auth::user()->UserType != 1)
                            <!-- <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Intumescent Seal Color Price<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="IntumescentSealColorPrice" id="IntumescentSealColorPrice"
                                        placeholder="Enter Intumescent Seal Color Price" class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div> -->
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 close_btn">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for Door Leaf facing Type-->
<div class="modal fade" id="add_Door_Leaf" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog " style="margin-top: 70px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Door Leaf Facing</h5>
            </div>
            <div class="modal-body" style="overflow-y: inherit">
                <form id='add_doorleaf_form' method="post" action="{{ route('option/update-glassType') }}"
                    enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="DoorLeafFacing">
                    <div class="form-row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group options">
                                    <label for="glasstype " class="d-block">Configuration<span class="text-danger">*</span></label>
                                    <input type="checkbox" name="Streboard" class="form-group  ml-3 option-style checkboxDoorType"
                                        value="1">Streboard
                                    <input type="checkbox" name="Halspan" class="form-group  ml-3 option-style checkboxDoorType"
                                        value="2">Halspan
                                    <input type="checkbox" name="Flamebreak" class="form-group  ml-3 option-style checkboxDoorType"
                                        value="7">Flamebreak
                                    <input type="checkbox" name="Stredor" class="form-group  ml-3 option-style checkboxDoorType"
                                        value="8">Stredor
                                        <input type="checkbox" name="VicaimaDoorCore" class="form-group  ml-3 option-style checkboxDoorType"
                                        value="4">Vicaima Door Core
                                        <input type="checkbox" name="SeadecDoorCore" class="form-group  ml-3 option-style checkboxDoorType"
                                        value="5">Seadec
                                        <input type="checkbox" name="deantaDoorCore" class="form-group  ml-3 option-style checkboxDoorType"
                                        value="6">Deanta
                                </div>
                            </div>

                                        <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype" class="d-block">Door Leaf Facing Option<span class="text-danger">*</span></label>
                                    <select name="DoorLeafOption" required id="DoorLeafOption" class="form-control">
                                        <option class="" value="">Select Door Leaf Option</option>
                                        <option class="leafFacingfirst3option" value="Veneer">Veneer</option>
                                        <option class="leafFacingfirst3option" value="Laminate">Laminate</option>
                                        <option class="leafFacingfirst3option" value="PVC">PVC</option>
                                        @if(isset($leaftype))
                                            @foreach($leaftype as $val)
                                                <option value="{{ $val->LeafType }}" style="display:none" class="doorLeafType1">{{ $val->LeafType }}</option>
                                            @endforeach
                                            @endif
                                            @if(isset($leaftype2))
                                            @foreach($leaftype2 as $val)
                                                <option value="{{ $val->LeafType }}" style="display:none" class="doorLeafType2">{{ $val->LeafType }}</option>
                                            @endforeach
                                            @endif

                                    </select>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Door Leaf Facing<span class="text-danger">*</span></label>
                                    <input type="text" name="DoorLeafFacingName"
                                        placeholder="Enter Door Leaf Facing Name" class="form-control " required>
                                    <input type="hidden" name="id" class="form-control">
                                    <input type="hidden" name="selectId" class="form-control">
                                </div>
                            </div>
                            @if(Auth::user()->UserType != 1)
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Door Leaf Facing Price<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="DoorLeafFacingPrice" id="DoorLeafFacingPrice"
                                placeholder="Enter Door Leaf Facing Price" class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 close_btn">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal for adding color Type-->
<div class="modal fade" id="add_color" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog " style="margin-top: 70px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Colour</h5>
            </div>
            <div class="modal-body" style="overflow-y: inherit">
                <form id='add_color_form' method="post" action="{{ route('option/update-glassType') }}"
                    enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="color">
                    <div class="form-row">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype" class="d-block">Door Leaf Facing Option</label>
                                    <select required="" name="DoorLeafFacing" id="DoorLeafFacing" class="form-control"
                                    onchange="DoorLeafFacingChange(this.value);">
                                        <option value="">Select any option</option>
                                        @if ($optionType == 'color_list' && $colorType == 'Kraft_Paper')
                                            <option value="Kraft_Paper" selected>Kraft Paper</option>
                                        @elseif ($optionType == 'color_list' && $colorType == 'Laminate')
                                            <option value="Laminate" selected>Laminate</option>
                                        @elseif ($optionType == 'color_list' && $colorType == 'PVC')
                                            <option value="PVC" selected>PVC</option>
                                        @elseif ($optionType == 'color_list' && $colorType == 'Veneer')
                                            <option value="Veneer" selected>Veneer</option>
                                        @elseif ($optionType == 'color_list' && $colorType == 'Painted')
                                            <option value="Painted" selected>Painted</option>
                                        @endif
                                    </select>
                                </div>
                            </div>
                            @if ($optionType == 'color_list' && $colorType == 'Laminate')
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Door Leaf Facing value<span class="text-danger">*</span></label>
                                    <select name="DoorLeafFacingval" id="DoorLeafFacingval" class="form-control"></select>
                                </div>
                            </div>
                            @endif

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Colour Name<span class="text-danger">*</span></label>
                                    <input type="text" name="ColorName" id="ColorName" required
                                        placeholder="Enter Colour Name" class="form-control ">
                                    <input type="hidden" name="id" class="form-control">
                                    <input type="hidden" name="selectId" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Hex<span class="text-danger">*</span></label>
                                    <input name="Hex" onkeyup="colorChange(this, true)" value="" required="" placeholder="#ffffff" type="text" class="form-control" id="colorfill">
                                </div>
                            </div>
                            @if(Auth::user()->UserType != 1)
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Colour Price<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="colorPrice" id="colorPrice" placeholder="Enter Colour Price" class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 close_btn">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for door dimension Type-->
<div class="modal fade" id="door_dimensionAddForm" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog " style="margin-top: 70px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Door Dimension</h5>
            </div>
            <div class="modal-body" style="overflow-y: inherit">
                <form id='add_door_dimension_form' method="post" action="{{ route('door-dimension-list-store') }}"
                    enctype="multipart/form-data">
                    <input type="hidden" id="door_leaf_fanish_filter" value="{{route('door-dimension/door-leaf-face-value-filter')}}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="door_dimension">
                    <div class="form-row">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="position-relative form-group options">
                                    <label for="glasstype " class="d-block">Configuration<span class="text-danger">*</span></label>
                                        <input type="radio" name="configurableitems" id="configurableitems1" class="form-group  ml-3 option-style configurableitemsdoordimension"
                                        value="4" required>VicaimaDoorCore
                                        <input type="radio" name="configurableitems" id="configurableitems1" class="form-group  ml-3 option-style configurableitemsdoordimension"
                                        value="5" required>Seadec
                                        <input type="radio" name="configurableitems" id="configurableitems1" class="form-group  ml-3 option-style configurableitemsdoordimension"
                                        value="6" required>Deanta
                                    <input type="hidden" name="id" class="form-control">
                                    <input type="hidden" name="selectId" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group firerating-options">
                                    <label for="glasstype" class="d-block">Fire Rating<span class="text-danger">*</span></label>
                                    <input type="radio" name="fire_rating" class="form-group  ml-3 option-style"
                                        value="NFR" required>NFR
                                    <input type="radio" name="fire_rating" class="form-group  ml-3 option-style"
                                        value="FD30" required>FD30
                                    <input type="radio" name="fire_rating" class="form-group  ml-3 option-style"
                                        value="FD60" required>FD60
                                </div>
                            </div>

                            <div class="col-md-12 leaf_type_door" style="display:none;">
                                <div class="form-group">
                                    <label for="Leaf Type">Leaf Type<span class="text-danger">*</span></label>
                                    <input type="hidden" id="leaf_typedata" value="{{ isset($leaftype)?$leaftype:'' }}">
                                    <select name="leaf_type" id="leaf_type" class="form-control" required>

                                    </select>
                                </div>
                            </div>

                            <div class="col-sm-12 leaf_type_door" style="display:none;">
                                <div class="form-group">
                                    <label for="Code">Code safasf<span class="text-danger">*</span></label>
                                    <input name="code" id="code" type="text" class="form-control" placeholder="code"
                                        >
                                </div>
                            </div>
                            <div class="col-md-12 leaf_type_door" style="display:none;">
                                <div class="form-group">
                                    <label for="inch_height">Inch Height<span class="text-danger">*</span></label>
                                    <input id = "inch_height" name="inch_height" type="number" min="1" class="form-control" placeholder="Inch Height" required>
                                </div>
                            </div>
                            <div class="col-md-12 leaf_type_door" style="display:none;">
                                <div class="form-group">
                                    <label for="inch_width">Inch Width<span class="text-danger">*</span></label>
                                    <input id = "inch_width" name="inch_width" type="number" min="1" class="form-control" placeholder="Inch Width" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="mm_height">MM Height<span class="text-danger">*</span></label>
                                    <input name="mm_height" type="number" min="1" class="form-control" placeholder="mm Height"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="mm_width">MM Width<span class="text-danger">*</span></label>
                                    <input name="mm_width" type="number" min="1" class="form-control" placeholder="MM Width"
                                         required>
                                </div>
                            </div>

                            <div class="col-md-12 leaf_type_door" style="display:none;">
                                <div class="form-group">
                                    <label for="Door Leaf Facing">Door Leaf Facing<span class="text-danger">*</span></label>
                                        <select name="door_leaf_facing" id="door_leaf_facing" class="form-control " onchange="doorleafFacing();" required>
                                        <option value="">Select Door leaf facing</option>
                                            @foreach($option_data as $row)
                                            @if($row->OptionSlug=='Door_Leaf_Facing')
                                            <option class="doorLeafFacingExistingOption" value="{{$row->OptionKey}}" @if(isset($Item["DoorLeafFacing"]))
                                            @if($Item["DoorLeafFacing"]==$row->OptionKey){{'selected'}} @endif
                                            @endif>{{$row->OptionValue}}</option>
                                            @endif
                                            @endforeach
                                        </select>
                                </div>
                            </div>

                            <div class="col-md-12 leaf_type_door"  style="display:none;">
                                <div class="form-group" id="doorLeafFinish">
                                    <label for="Door Leaf Finish">Door Leaf Finish<span class="text-danger">*</span></label>
                                        <select name="door_leaf_finish" id="door_leaf_finish" class="form-control">
                                        <option value="">Select Door leaf finish</option>
                                        </select>
                                </div>
                            </div>

                            <div class="col-md-12 leaf_type_door" style="display:none;">
                                <div class="form-group">
                                    <label for="cost_price">Cost Price<span class="text-danger">*</span></label>
                                    <input type="number" id="cost_price" name="cost_price" min="0" class="form-control" placeholder="cost price" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" >
                                </div>
                            </div>
                            {{--  <div class="col-md-12 leaf_type_door" style="display:none;" hidden>
                                <div class="form-group">
                                    <label for="selling_price">Selling Price<span class="text-danger">*</span></label>
                                    <input name="selling_price" id="selling_price" type="number" min="0" class="form-control"  pattern="[0-9]+([\.,][0-9]+)?" step="0.01" placeholder="selling_price" required>
                                </div>
                            </div>  --}}
                            <div class="col-md-6 leaf_type_door" style="display:none;">
                                <div class="form-group">
                                    <label for="image">Image</label>
                                    <input name="image" id="image" type="file" class="form-control" placeholder="image">
                                </div>
                            </div>
                            <div class="col-md-4 leaf_type_door" style="display:none;">
                                <div class="position-relative form-group">
                                    <div id="doorDimensionImage"></div>
                                    <input type="hidden" id="url" value="{{ url('/') }}">
                                </div>
                            </div>
                            @if(Auth::user()->UserType != 1)
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Door Leaf Facing Price<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="DoorDimensionPrice" id="DoorDimensionPrice"
                                    placeholder="Enter Door Dimension Price" class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 close_btn">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="door_dimension_customeAddForm" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog " style="margin-top: 70px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Door Dimension</h5>
            </div>
            <div class="modal-body" style="overflow-y: inherit">
                <form id='add_door_dimension_form' method="post" action="{{ route('door-dimension-list-store-custome') }}"
                    enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="door_dimension_custome">
                    <div class="form-row">
                        <div class="row">

                            <div class="col-md-12">
                                <div class="position-relative form-group options">
                                    <label for="glasstype " class="d-block">Configuration<span class="text-danger">*</span></label>
                                    <input type="radio" name="configurableitems" id="configurableitems1" class="form-group  ml-3 option-style configurableitemsdoordimension"
                                        value="1" required>Streboard
                                    <input type="radio" name="configurableitems" id="configurableitems1"  class="form-group  ml-3 option-style configurableitemsdoordimension"
                                        value="2" required>Halspan
                                    <input type="radio" name="configurableitems" id="configurableitems1"  class="form-group  ml-3 option-style configurableitemsdoordimension"
                                        value="7" required>Flamebreak
                                    <input type="radio" name="configurableitems" id="configurableitems1"  class="form-group  ml-3 option-style configurableitemsdoordimension"
                                        value="8" required>Stredor
                                    <input type="hidden" name="id" class="form-control">
                                    <input type="hidden" name="selectId" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group firerating-options">
                                    <label for="glasstype" class="d-block">Fire Rating<span class="text-danger">*</span></label>
                                    <input type="radio" name="fire_rating" class="form-group  ml-3 option-style"
                                        value="NFR" required>NFR
                                    <input type="radio" name="fire_rating" class="form-group  ml-3 option-style"
                                        value="FD30" required>FD30
                                    <input type="radio" name="fire_rating" class="form-group  ml-3 option-style"
                                        value="FD60" required>FD60
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="mm_height">MM Height<span class="text-danger">*</span></label>
                                    <input name="mm_height" type="number" min="1" class="form-control" placeholder="mm Height"
                                        required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="mm_width">MM Width<span class="text-danger">*</span></label>
                                    <input name="mm_width" type="number" min="1" class="form-control" placeholder="MM Width"
                                         required>
                                </div>
                            </div>

                            @if(Auth::user()->UserType != 1)
                            <div class="col-md-12 sterboard-fields" style="display: none">
                                <div class="position-relative form-group options">
                                    <label for="leaf_type" class="d-block">Door Leaf Facing Price<span class="text-danger">*</span></label>
                                    @foreach($intumenseLeafType as $type)
                                        @if($type->configurableitems == 1)
                                            <div class="form-group">
                                                <label>{{ $type->leaf_type_key }}:</label> <!-- Display Leaf Type Name -->
                                                <input type="number" name="prices[{{ $type->id }}]" class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" placeholder="Enter price for {{ $type->leaf_type_key }}" value="">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-md-12 halspan-fields" style="display: none">
                                <div class="position-relative form-group options">
                                    <label for="leaf_type" class="d-block">Door Leaf Facing Price<span class="text-danger">*</span></label>
                                    @foreach($intumenseLeafType as $type)
                                        @if($type->configurableitems == 2)
                                            <div class="form-group">
                                                <label>{{ $type->leaf_type_key }}:</label> <!-- Display Leaf Type Name -->
                                                <input type="number" name="prices[{{ $type->id }}]" class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" placeholder="Enter price for {{ $type->leaf_type_key }}" value="">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>

                            <div class="col-md-12 flamebreak-fields" style="display: none">
                                <div class="position-relative form-group options">
                                    <label for="leaf_type" class="d-block">Door Leaf Facing Price<span class="text-danger">*</span></label>
                                    @foreach($intumenseLeafType as $type)
                                        @if($type->configurableitems == 7)
                                            <div class="form-group">
                                                <label>{{ $type->leaf_type_key }}:</label> <!-- Display Leaf Type Name -->
                                                <input type="number" name="prices[{{ $type->id }}]" class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" placeholder="Enter price for {{ $type->leaf_type_key }}" value="">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            <div class="col-md-12 stredor-fields" style="display: none">
                                <div class="position-relative form-group options">
                                    <label for="leaf_type" class="d-block">Door Leaf Facing Price<span class="text-danger">*</span></label>
                                    @foreach($intumenseLeafType as $type)
                                        @if($type->configurableitems == 8)
                                            <div class="form-group">
                                                <label>{{ $type->leaf_type_key }}:</label> <!-- Display Leaf Type Name -->
                                                <input type="number" name="prices[{{ $type->id }}]" class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" placeholder="Enter price for {{ $type->leaf_type_key }}" value="">
                                            </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 close_btn">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<!-- Modal for glass type for sidelight and overpanel-->
<div class="modal fade" id="slAndFlGlassTypeAddForm" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog " style="margin-top: 70px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Glass Type</h5>
            </div>
            <div class="modal-body" style="overflow-y: inherit">
                <form id='add_sl_and_fl_glass_type_form' method="post" action="{{ route('option/update-glassType') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="Overpanel_Glass_Type">
                    <div class="form-row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group options">
                                    <label for="glasstype " class="d-block">Configuration<span class="text-danger">*</span></label>
                                    <input type="radio" name="config[]" class="form-group  ml-3 option-style"
                                        value="1">Streboard
                                    <input type="radio" name="config[]" class="form-group  ml-3 option-style"
                                        value="2">Halspan
                                    <input type="radio" name="config[]" class="form-group  ml-3 option-style"
                                        value="7">Flamebreak
                                    <input type="radio" name="config[]" class="form-group  ml-3 option-style"
                                        value="8">Stredor
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group firerating-options">
                                    <label for="glasstype" class="d-block">Fire Rating<span class="text-danger">*</span></label>
                                    <input type="radio" name="firerating[]" class="form-group  ml-3 option-style"
                                        value="NFR">NFR
                                    <input type="radio" name="firerating[]" class="form-group  ml-3 option-style"
                                        value="FD30">FD30
                                    <input type="radio" name="firerating[]" class="form-group  ml-3 option-style"
                                        value="FD60">FD60
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype" class="d-block">Glass Integrity<span class="text-danger">*</span></label>
                                    <input type="radio" name="GlassIntegrity" class="form-group  option-style"
                                        value="Integrity_And_Insulation" required>Integrity And Insulation
                                    <input type="radio" name="GlassIntegrity" class="form-group  option-style"
                                        value="Integrity_only" required>Integrity only
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glass Type<span class="text-danger">*</span></label>
                                    <input type="text" name="GlassType" placeholder="Enter Glass Type Name"
                                        class="form-control " required>
                                    <input type="hidden" name="id" class="form-control">
                                    <input type="hidden" name="selectId" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glass Thickness  <span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="GlassThickness" placeholder="Enter Glass Thickness"
                                        class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Max Width Of Fanlight <span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="FanLightWidth" placeholder="Enter Max Width Of Fanlight"
                                        class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Max Height of Fanlight <span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="FanLightHeight" placeholder="Enter Max Height of Fanlight"
                                        class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Side Sceen Width <span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="SideScreenWidth" placeholder="Enter Side Sceen Width"
                                        class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Side Screen Height <span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="SideScreenHeight" placeholder="Enter Side Screen Height"
                                        class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">MIN Fan Light/ Over Panel Frame Thickness <span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="TransomThickness" placeholder="Enter Fan Light/ Over Panel Frame Thickness"
                                        class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">MIN Fan Light/ Over Panel Frame Depth <span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="TransomDepth" placeholder="Enter Fan Light/ Over Panel Frame Depth"
                                        class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            @if(Auth::user()->UserType != 1)
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glass Price<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="glassPrice" id="glassPrice" pattern="[0-9]+([\.,][0-9]+)?" placeholder="Enter Glass Price" class="form-control " required step="0.01">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 close_btn">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for glazing System for sidelight and overpanel-->
<div class="modal fade" id="SlandFlglazingTypeAddForm" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog " style="margin-top: 70px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Glazing System</h5>
            </div>
            <div class="modal-body" style="overflow-y: inherit">
                <form id='add_glazingSlAndFl_form' method="post" action="{{ route('option/update-glassType') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="Overpanel_Glazing_System">
                    <div class="form-row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group options">
                                    <label for="glasstype " class="d-block">Configuration<span class="text-danger">*</span></label>
                                    <input type="radio" name="config[]" class="form-group  ml-3 option-style"
                                        value="1">Streboard
                                    <input type="radio" name="config[]" class="form-group  ml-3 option-style"
                                        value="2">Halspan
                                    <input type="radio" name="config[]" class="form-group  ml-3 option-style"
                                        value="7">Flamebreak
                                    <input type="radio" name="config[]" class="form-group  ml-3 option-style"
                                        value="8">Stredor
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group firerating-options">
                                    <label for="glasstype" class="d-block">Fire Rating<span class="text-danger">*</span></label>
                                    <input type="radio"  name="firerating[]" class="form-group  ml-3 option-style"
                                        value="NFR">NFR
                                    <input type="radio" name="firerating[]" class="form-group  ml-3 option-style"
                                        value="FD30">FD30
                                    <input type="radio"  name="firerating[]" class="form-group  ml-3 option-style"
                                        value="FD60">FD60
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glass Type<span class="text-danger">*</span></label>
                                    <select name="GlassTypeOverpanel" id="" required class="form-control ">
                                        <option value="">Select Glass Type</option>
                                       {{-- @if(isset($GlassType))
                                        @foreach($GlassType as $val)
                                            <option value="{{ $val->GlassType }}">{{ $val->GlassType }}</option>
                                        @endforeach
                                        @endif --}}
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glazing System<span class="text-danger">*</span></label>
                                    <input type="text" name="GlazingSystem" placeholder="Enter Glazing System Name"
                                        class="form-control " required>
                                    <input type="hidden" name="id" class="form-control">
                                    <input type="hidden" name="selectId" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glazing System Thickness<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="GlazingThickness" placeholder="Enter Glazing Thickness"
                                        class="form-control " pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Min Beading<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="Beading" placeholder="Enter Min Beading"
                                        class="form-control " pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Min Beading Height<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="BeadingHeight" placeholder="Enter Min Beading Height"
                                        class="form-control " pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Min Beading Width<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="BeadingWidth" placeholder="Enter Min Beading Width"
                                        class="form-control " pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glazing Bead Fixing Detail<span class="text-danger">*</span></label>
                                    <input type="text" name="FixingDetails"
                                        placeholder="Enter Glazing Bead Fixing Detail" class="form-control " required>
                                </div>
                            </div>
                            @if(Auth::user()->UserType != 1)
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glazing System Price<span class="text-danger">*</span></label>
                                    <input type="number" name="glazingPrice" id="glazingPrice" min="0"
                                        placeholder="Enter Glazing System Price" class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 close_btn">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal for side screen glass type -->
<div class="modal fade" id="SideScreenGlassTypeAddForm" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog " style="margin-top: 70px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Glass Type</h5>
            </div>
            <div class="modal-body" style="overflow-y: inherit">
                <form id='add_sl_and_fl_glass_type_form' method="post" action="{{ route('option/update-glassType') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="SideScreen_Glass_Type">
                    <div class="form-row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="FireRating">Fire Rating</label>
                                    <select name="FireRating" id="" class="form-control " required>
                                        <option value="">Select fire rating</option>
                                        <option value="0-0">0-0</option>
                                        <option value="30-0">30-0</option>
                                        <option value="30-30">30-30</option>
                                        <option value="60-0">60-0</option>
                                        <option value="60-60">60-60</option>
                                        <option value="IGU 0-0">IGU 0-0</option>
                                        <option value="IGU 30-0">IGU 30-0</option>
                                        <option value="IGU 30-30">IGU 30-30</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">DFRating<span class="text-danger">*</span></label>
                                    <input type="text" name="DFRating" placeholder="Enter DFRating"
                                        class="form-control " required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glass Type<span class="text-danger">*</span></label>
                                    <input type="text" name="GlassType" placeholder="Enter Glass Type Name"
                                        class="form-control " required>
                                    <input type="hidden" name="id" class="form-control">
                                    <input type="hidden" name="selectId" class="form-control">
                                </div>
                            </div>
                            {{-- <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glass Thickness  <span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="GlassThickness" placeholder="Enter Glass Thickness"
                                        class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div> --}}
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Width Point 1 (far right) <span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="WidthPoint1" placeholder="Enter Width Point 1 (far right)"
                                        class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Height Point 1 (Lowest) <span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="HeightPoint1" placeholder="Enter Height Point 1 (Lowest)"
                                        class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Width Point 2 (Cloest) <span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="WidthPoint2" placeholder="Enter Width Point 2 (Cloest)"
                                        class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Height Point 2 (Higest) <span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="HeightPoint2" placeholder="Enter Height Point 2 (Higest)"
                                        class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">MIN Transom Thickness <span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="TransomThickness" placeholder="Enter MIN Transom Thickness"
                                        class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">MIN Transom Depth <span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="TransomDepth" placeholder="Enter MIN Transom Depth"
                                        class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">MAX Area m2 <span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="AreaSize" placeholder="Enter MAX Area m2"
                                        class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Frame Density <span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="FrameDensity" placeholder="Enter Frame Density"
                                        class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            @if(Auth::user()->UserType != 1)
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glass Price<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="glassPrice" id="glassPrice" pattern="[0-9]+([\.,][0-9]+)?" placeholder="Enter Glass Price" class="form-control " required step="0.01">
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 close_btn">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for side screen glazing system -->
<div class="modal fade" id="SideScreenGlazingSystemAddForm" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog " style="margin-top: 70px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_btn" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Glazing System</h5>
            </div>
            <div class="modal-body" style="overflow-y: inherit">
                <form id='add_glazingSlAndFl_form' method="post" action="{{ route('option/update-glassType') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="SideScreen_Glazing_System">
                    <div class="form-row">
                        <div class="row">
                            {{-- <div class="col-md-12">
                                <div class="position-relative form-group options">
                                    <label for="glasstype " class="d-block">Configuration<span class="text-danger">*</span></label>
                                    <input type="radio" name="config[]" class="form-group  ml-3 option-style"
                                        value="1">Streboard
                                    <input type="radio" name="config[]" class="form-group  ml-3 option-style"
                                        value="2">Halspan
                                    <input type="radio" name="config[]" class="form-group  ml-3 option-style"
                                        value="7">Flamebreak
                                    <input type="radio" name="config[]" class="form-group  ml-3 option-style"
                                        value="8">Stredor
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group firerating-options">
                                    <label for="glasstype" class="d-block">Fire Rating<span class="text-danger">*</span></label>
                                    <input type="radio"  name="firerating[]" class="form-group  ml-3 option-style"
                                        value="NFR">NFR
                                    <input type="radio" name="firerating[]" class="form-group  ml-3 option-style"
                                        value="FD30">FD30
                                    <input type="radio"  name="firerating[]" class="form-group  ml-3 option-style"
                                        value="FD60">FD60
                                </div>
                            </div> --}}
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Fire Rating<span class="text-danger">*</span></label>
                                    <select name="FireRating" id="ScreenFireRating" class="form-control " required>
                                        <option value="">Select fire rating</option>
                                        <option value="0-0">0-0</option>
                                        <option value="30-0">30-0</option>
                                        <option value="30-30">30-30</option>
                                        <option value="60-0">60-0</option>
                                        <option value="60-60">60-60</option>
                                        <option value="IGU 0-0">IGU 0-0</option>
                                        <option value="IGU 30-0">IGU 30-0</option>
                                        <option value="IGU 30-30">IGU 30-30</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glass Type<span class="text-danger">*</span></label>
                                    <select name="GlassTypeSideScreen" id="GlassTypeSideScreen" required class="form-control ">
                                        <option value="">Select Glass Type</option>
                                       @if(isset($screenGlassType))
                                        @foreach($screenGlassType as $val)
                                            <option value="{{ $val->id }}">{{ $val->GlassType }}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glazing System<span class="text-danger">*</span></label>
                                    <input type="text" name="GlazingSystem" placeholder="Enter Glazing System Name"
                                        class="form-control " required>
                                    <input type="hidden" name="id" class="form-control">
                                    <input type="hidden" name="selectId" class="form-control">
                                    <input type="hidden" name="glass_ids" id ="glass_ids" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glazing Thickness<span class="text-danger">*</span></label>
                                    <input type="text" min="0" name="GlazingThickness" placeholder="Enter Glazing Thickness"
                                        class="form-control " required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Beading<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="Beading" placeholder="Enter Beading"
                                        class="form-control " pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Beading Height<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="BeadingHeight" placeholder="Enter Min Beading Height"
                                        class="form-control " pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Beading Width<span class="text-danger">*</span></label>
                                    <input type="number" min="0" name="BeadingWidth" placeholder="Enter Min Beading Width"
                                        class="form-control " pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="FixingDetails">Fixing Details<span class="text-danger">*</span></label>
                                    <input type="text" min="0" name="FixingDetails" placeholder="Enter Fixing Details"
                                        class="form-control " required>
                                </div>
                            </div>

                            @if(Auth::user()->UserType != 1)
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glass Price<span class="text-danger">*</span></label>
                                    <input type="number" name="glazingPrice" id="glazingPrice" min="0"
                                        placeholder="Enter Glazing System Price" class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" required>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 close_btn">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@section('js')

    <script>

    $(document).on('click','#glazingHalspan', function () {
        if ($('#glazingHalspan').is(':checked')) {

        } else {

        }
    });

    function SinglePane(){
        var FireRating = $("#ScreenFireRating").val();
        if(FireRating == ''){
            swal('Warning','Somethings went wrong!');
            return false;
        }
        $.ajax({
            url: "{{ route('items/get-glass-options') }}",
            method: "POST",
            dataType: "json",
            data: { FireRating: FireRating, _token: '{{ csrf_token() }}' },
            success: function (result) {
                function buildOptions(data, valueElementId, defaultOptionText) {
                    let optionsHtml = `<option value="">${defaultOptionText}</option>`;
                    let selectedValue = $(valueElementId).val() || null;
                    data.forEach(item => {
                        let selected = selectedValue == item.id ? "selected" : "";
                        optionsHtml += `<option value="${item.id}" ${selected}>${item.GlassType}</option>`;
                    });

                    return optionsHtml;
                }

                if (result.status === "ok") {
                    let data = result.dataSelected;
                    $("#GlassTypeSideScreen").empty().append(buildOptions(data, "#glass_ids", "Select Glass Type"));

                } else {
                    $("#GlassTypeSideScreen").empty().append('<option value="">No Glass Type Found</option>');
                }

                // let elements = $(this);
                // render(elements);
            },
            error: function (err) {
                console.error("AJAX Error:", err);
            }
        });
    }

    $(document).on('change','#ScreenFireRating', function () {
        SinglePane();
    });

    $(document).on('change','.checkboxDoorType', function () {
        doorLeafFacingOption();
    });


    function doorLeafFacingOption(option = '') {
    let strebord = $('input[name="Streboard"]').is(':checked');
    let halspan = $('input[name="Halspan"]').is(':checked');
    let norma = $('input[name="NormaDoorCore"]').is(':checked');
    let vicaima = $('input[name="VicaimaDoorCore"]').is(':checked');
    let sedac = $('input[name="SeadecDoorCore"]').is(':checked');

    if (vicaima && sedac && (strebord || halspan || norma)) {
        $('.doorLeafType1').css('display', 'block');
        $('.doorLeafType2').css('display', 'block');
        $('.leafFacingfirst3option').css('display', 'block');
        $('#DoorLeafOption').val(option);
    } else if (vicaima && (strebord || halspan || norma)) {
        $('.doorLeafType1').css('display', 'block');
        $('.doorLeafType2').css('display', 'none');
        $('.leafFacingfirst3option').css('display', 'block');
        $('#DoorLeafOption').val(option);
    } else if (sedac && (strebord || halspan || norma)) {
        $('.doorLeafType1').css('display', 'none');
        $('.doorLeafType2').css('display', 'block');
        $('.leafFacingfirst3option').css('display', 'block');
        $('#DoorLeafOption').val(option);
    }else if (vicaima && sedac) {
        $('.doorLeafType1').css('display', 'block');
        $('.doorLeafType2').css('display', 'block');
        $('.leafFacingfirst3option').css('display', 'none');
        $('#DoorLeafOption').val(option);
    }
    else if (sedac) {
        $('.doorLeafType1').css('display', 'none');
        $('.doorLeafType2').css('display', 'block');
        $('.leafFacingfirst3option').css('display', 'none');
        $('#DoorLeafOption').val(option);
    }
    else if (vicaima) {
        $('.doorLeafType1').css('display', 'block');
        $('.doorLeafType2').css('display', 'none');
        $('.leafFacingfirst3option').css('display', 'none');
        $('#DoorLeafOption').val(option);
    }
    else {
        $('#DoorLeafOption').val(option);
            $('.doorLeafType1').css('display', 'none');
            $('.doorLeafType2').css('display', 'none');
            $('.leafFacingfirst3option').css('display', 'block');
    }
}


    $(document).on('change', '#configurableitems1', function () {
        let confi = $(this).val();
        if (confi == '4') {
            $('#leaf_type').val('');
            $('.doorLeafTypeValue').css('display', 'block');
            $('.first3optionLeafType').css('display', 'none');

        } else {
            $('#leaf_type').val('');
            $('.doorLeafTypeValue').css('display', 'none');
            $('.first3optionLeafType').css('display', 'block');
        }
    });

    // leaf type only for custome door
    $('input[name="configurableitems"]').change(function() {
        let confi = $(this).val();
        if (confi == '3' || confi == '4' || confi == '5' || confi == '6') {
            $('.customedoor').css('display', 'none');
        }
    });

    $(document).ready(function() {
    // Your code here
    let confi = $('input[name="configurableitems"]:checked').val();
    console.log(confi,'test3')
    if (confi == '3' || confi == '4' || confi == '5' || confi == '6') {
            $('.customedoor').css('display', 'none');
        }
    });

    // take value of glass type and glazing system according door

    $(document).on('change', '#glassconfigvalue', function() {
        let confi = $(this).val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url: "{{url('options/glassconfigvalue')}}",
            method: "POST",
            dataType: "Json",
            data: {
                confi: confi,
                _token: "{{ csrf_token() }}"
            },
            success: function(result) {
                console.log(result);

                // Update Glass Type select field
                let glassTypeSelect = $('select[name="GlassType"]');
                glassTypeSelect.empty(); // Clear existing options
                glassTypeSelect.append('<option value="">Select Glass Type</option>');
                if (result.GlassType && result.GlassType.length) {
                    result.GlassType.forEach(function(item) {
                        glassTypeSelect.append(`<option value="${item.GlassType}">${item.GlassType}</option>`);
                    });
                }

                // Update Glazing System select field
                let glazingSystemSelect = $('select[name="glazingSystem"]');
                glazingSystemSelect.empty(); // Clear existing options
                glazingSystemSelect.append('<option value="">Select Glazing System</option>');
                if (result.GlazingSystem && result.GlazingSystem.length) {
                    result.GlazingSystem.forEach(function(item) {
                        glazingSystemSelect.append(`<option value="${item.GlazingSystem}">${item.GlazingSystem}</option>`);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
    });

    function editGlassGlazing(configurable,GlassType,GlazingSystem){
        let confi = configurable;
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url: "{{url('options/glassconfigvalue')}}",
            method: "POST",
            dataType: "Json",
            data: {
                confi: confi,
                _token: "{{ csrf_token() }}"
            },
            success: function(result) {
                console.log(result);

                // Update Glass Type select field
                let glassTypeSelect = $('select[name="GlassType"]');
                glassTypeSelect.empty(); // Clear existing options
                glassTypeSelect.append('<option value="">Select Glass Type</option>');
                if (result.GlassType && result.GlassType.length) {
                    result.GlassType.forEach(function(item) {
                        glassTypeSelect.append(`<option value="${item.GlassType}" >${item.GlassType}</option>`);
                    });
                }
                if (GlassType) {
                    glassTypeSelect.val(GlassType); // Set the selected value
                }


                // Update Glazing System select field
                let glazingSystemSelect = $('select[name="glazingSystem"]');
                glazingSystemSelect.empty(); // Clear existing options
                glazingSystemSelect.append('<option value="">Select Glazing System</option>');
                if (result.GlazingSystem && result.GlazingSystem.length) {
                    result.GlazingSystem.forEach(function(item) {
                        glazingSystemSelect.append(`<option value="${item.GlazingSystem}">${item.GlazingSystem}</option>`);
                    });
                }
                if (GlazingSystem) {
                    glazingSystemSelect.val(GlazingSystem); // Set the selected value
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
    }



    $(document).on('change', '#configurableitems1', function () {

        let confi = $(this).val();
        if (confi == '4') {

    $(document).on('change', '#leaf_type', function(){

        let leaftypeValue = $(this).val();

        $('.leafFacingOptionBefore').remove();

        $.ajax({
        type: "Post",
        dataType: "json",
        url: "{{ route('quotation/getDoorFacing') }}",
        data: {
            leaftypeValue:leaftypeValue,
            _token: '{{ csrf_token() }}'
        },
        success: function(response) {

            if (response.status === 'ok' && Array.isArray(response.leafTypeFacing)) {
                let html = '';
                response.leafTypeFacing.forEach(function(item) {
                html += `<option value="${item.doorLeafFacingValue}" class="leafFacingOptionBefore">${item.doorLeafFacingValue}</option>`
                });


                $('#door_leaf_facing').append(html);

            } else {
                console.error('Invalid response format');
            }

        },
        error: function(jqXHR, textStatus, errorThrown) {
            console.error("Ajax request failed: " + textStatus, errorThrown);
        }
        });
    })

      $('.doorLeafType').css('display', 'block');
        } else {

            $('.doorLeafType').css('display', 'none');

        }

        if (confi == '4') {
            $('#door_leaf_facing').val('');
            $('.doorLeafFacing123').css('display', 'block');
            $('.doorLeafFacingExistingOption').css('display', 'none');
        }else{
            $('#door_leaf_facing').val('');
            $('.leafFacingOptionBefore').remove();
            $('.doorLeafFacing123').css('display', 'none');
            $('.doorLeafFacingExistingOption').css('display', 'block');
        }

    });


</script>

<script>
    // JQUERY

    // var table = $('#example').DataTable({
    //     searching : false,
    //     info : false,
    //     "lengthChange": true,
    //     "bPaginate": false,
    //     "responsive": true
    // });
    // var rows = table.rows({ 'search': 'applied' }).nodes();
    // $('.checkall').click(function() {
    //     var rows = table.rows({ 'search': 'applied' }).nodes();
    //     // Check/uncheck checkboxes for all rows in the table
    //     $('input[type="checkbox"]', rows).prop('checked', this.checked);

    // }); old code

    var table;
    $(document).ready(function() {
            table = $('#example').DataTable({
            searching: false,
            info: false,
            "lengthChange": true,
            "bPaginate": false,
            "responsive": true,
            "order": [],  // No default sorting
            "columnDefs": [{
                "targets": 0,  // Checkbox column index
                "orderDataType": "dom-checkbox",
                "orderable": true  // Enable sorting for checkboxes
            }]
        });

        // Custom sorting for checkboxes (checked = 1, unchecked = 0)
        $.fn.dataTable.ext.order['dom-checkbox'] = function(settings, col) {
            return this.api().column(col, { order: 'index' }).nodes().map(function(td) {
                return $(td).find('input[type="checkbox"]').prop('checked') ? 1 : 0;
            });
        };

        // Ensure sorting is correct only when clicking the checkbox column
        $('#example thead').on('click', 'th', function() {
            var columnIndex = $(this).index(); // Get clicked column index
            if (columnIndex === 0) { // If checkbox column is clicked
                setTimeout(function() {
                    table.order([0, 'desc']).draw(false); // Ensure checked first only when clicked
                }, 50);
            }
        });

        // "Check All" functionality
        $('.checkall').click(function() {
            var rows = table.rows({ 'search': 'applied' }).nodes();
            $('input[type="checkbox"]', rows).prop('checked', this.checked);
            table.draw(); // Refresh table to reflect changes
        });
    });

    function updateMeOption(className,colorType=null) {

        //console.log(table.find('input[type=checkbox]:checked'));

        //return false;
        var selectedValue = [];
       //$('.'+className+':checked').map(function(_, el) {
        //$('input[type="checkbox"]', rows).push($(el).val());
        //}).get();
            let checkedvalues = table.$('input:checked').each(function () {
        selectedValue.push($(this).val());
        });
        if(className == 'color_list' || className == 'door_dimension'){
            commaSeparatedValues = selectedValue.join(',');
        }else{
            commaSeparatedValues = selectedValue;
        }

        var doorcore = $('#doorcoreValue').val();

        $('.loader').css({'display':'block'});

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url: "{{url('options/update-option')}}",
            method: "POST",
            dataType: "Json",
            data: {
                selectedValue: commaSeparatedValues,
                className: className,
                colorType:colorType,
                doorcore:doorcore??0,
                _token: "{{ csrf_token() }}"
            },
            success: function(result) {
                if (result.status = "ok") {
                    $('.loader').css({'display':'none'});
                    alert('Data updated successfully.')
                    location.reload();
                } else {
                    $('.loader').css({'display':'none'});
                    alert(result.msg);
                }
            }


        });

    }

    function updateMeOptionCustome(className,colorType=null) {
        var selectedValue = [];
            let checkedvalues = table.$('input:checked').each(function () {
        selectedValue.push($(this).val());
        });
        if(className == 'color_list' || className == 'door_dimension' || className == 'door_dimension_custome'){
            commaSeparatedValues = selectedValue.join(',');
        }else{
            commaSeparatedValues = selectedValue;
        }

        var doorcore = $('#doorcoreValue').val();

        $('.loader').css({'display':'block'});
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url: "{{url('options/update-option-custome')}}",
            method: "POST",
            dataType: "Json",
            data: {
                selectedValue: commaSeparatedValues,
                className: className,
                colorType:colorType,
                doorcore:doorcore??0,
                _token: "{{ csrf_token() }}"
            },
            success: function(result) {
                if (result.status = "ok") {
                    $('.loader').css({'display':'none'});
                    alert('Data updated successfully.')
                    location.reload();
                } else {
                    $('.loader').css({'display':'none'});
                    alert(result.msg);
                }
            }
    });

}


    function updateLeafTypeCost(value, doorDimensionId, leafTypeKey) {
        $('.loader').css({'display':'block'});
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url: '/update-leaf-type-cost',
            method: 'POST',
            data: {
                cost: value,
                door_dimension_id: doorDimensionId,
                leaf_type_key: leafTypeKey,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                $('.loader').css({'display':'none'});
                    alert('Data updated successfully.')
                    location.reload();
            },
            error: function(xhr, status, error) {
                $('.loader').css({'display':'none'});
                alert(result.msg);
            }
        });
}



    $(document).ready(function(){
        $("#DoorLeafFacingPriceDiv").hide();
        $("#coloroptionHide").hide();
        $("#add_glasstype_form,#add_glazingtype_form,#glazingTypeAddFormcustome_form,#add_GlassGlazingType,#AddLeafType"). on('submit',function(){

            if ($('input:checkbox[name="config[]"]:checked').length < 1){
                alert("Please select at least one configuration");
                return false;
            }
            if ($('input:checkbox[name="firerating[]"]:checked').length < 1){
                alert("Please select at least one firerating");
                return false;
            }
        });
        $("#add_glasstype_formcustome"). on('submit',function(){
            if ($('input:radio[name="config[]"]:checked').length < 1){
                alert("Please select at least one configuration");
                return false;
            }
            if ($('input:checkbox[name="firerating[]"]:checked').length < 1){
                alert("Please select at least one firerating");
                return false;
            }
        });

        $("#add_Intumescent_form,#add_Accoustics_form,#add_Architrave_Type_form"). on('submit',function(){
            if ($('input:checkbox[name="config[]"]:checked').length < 1){
                alert("Please select at least one configuration");
                return false;
            }
        });

        $("#add_doorleaf_form"). on('submit',function(){
            if ($('.checkboxDoorType:checked').length < 1) {
                alert("Please select at least one configuration");
                return false;
            }
        });
        //$(".DoorLeafOption").on('click',function(){
          //  var DoorLeafOption = $(this).val();
            //if(DoorLeafOption == 'Veneer'){
              //  $("#DoorLeafFacingPrice").attr('required',true);
                //$("#DoorLeafFacingPriceDiv").show();
           // }else{
             //   $("#DoorLeafFacingPrice").val(0);
               // $("#DoorLeafFacingPrice").attr('required',false);
               // $("#DoorLeafFacingPriceDiv").hide();
            //}
        //});
        if($('#DoorLeafFacing').val() == 'Laminate'){
            DoorLeafFacingChange($('#DoorLeafFacing').val());
        }
    });

    $('.close_btn').click(function(){
        $('input[name=id]').val('');
        $('input[name=selectId]').val('');
        $('#add_glasstype_form')[0].reset();
        $('#add_glasstype_formcustome')[0].reset();
        $('#AddLeafType_form')[0].reset();
        $('#add_intumescent_seals_arrangement_form')[0].reset();
        $('#add_glazingtype_form')[0].reset();
        $('#glazingTypeAddFormcustome_form')[0].reset();
        $('#add_GlassGlazingType')[0].reset();
        $('#add_Intumescent_form')[0].reset();
        $("#accousticsImage").empty();
        $("#doorDimensionImage").empty();
        $("#Accoustics_image").attr('required',true);
        $('input[name="configurableitems"]').attr('checked', false);
        $(".leaf_type_door").css("display", "none");
        $('#add_Accoustics_form')[0].reset();
        $('#add_doorleaf_form')[0].reset();
        $('#add_Architrave_Type_form')[0].reset();
        $('#add_color_form')[0].reset();
        $('#add_door_dimension_form')[0].reset();
    });


    $("#add_glassType").click(function(e) {
        e.preventDefault();
        $("#glassTypeAddForm").modal('show');
    });
    $("#add_glassTypeCustome").click(function(e) {
        e.preventDefault();
        $("#glassTypeCustomeAddForm").modal('show');
    });
    $("#AddLeafType").click(function(e) {
        e.preventDefault();
        $("#leafTypeAddForm").modal('show');
    });

    $('.close_model').on('click', function() {
        $('#glassTypeAddForm').modal('hide');
    });
    $('.close_model').on('click', function() {
        $('#glassTypeCustomeAddForm').modal('hide');
    });

    $('.close_model').on('click', function() {
        $('#leafTypeAddForm').modal('hide');
    });

    $("#Add_IntumescentSealArrangement").click(function(e) {
        e.preventDefault();
        $("#intumescentSealArrangementAddForm").modal('show');
    });

    $('.close_model').on('click', function() {
        $('#intumescentSealArrangementAddForm').modal('hide');
    });

    $("#add_door_dimension").click(function(e) {
        e.preventDefault();
        $("#door_dimensionAddForm").modal('show');
    });
    $("#add_door_dimension_custome").click(function(e) {
        e.preventDefault();
        $("#door_dimension_customeAddForm").modal('show');
    });

    $('.close_model').on('click', function() {
        $('#door_dimensionAddForm').modal('hide');
    });
    $('.close_model').on('click', function() {
        $('#door_dimension_customeAddForm').modal('hide');
    });

    $("#add_color_modal").click(function(e) {
        e.preventDefault();
        $('#DoorLeafFacingval').val("");
        $('#coloroptionHide').hide();
        $("#add_color").modal('show');
    });

    $('.close_model').on('click', function() {
        $('#add_color').modal('hide');
    });
    $("#AddArchitrave_Type").click(function(e) {
        e.preventDefault();
        $("#add_Architrave_Type").modal('show');
    });

    $('.close_model').on('click', function() {

        $('#add_Architrave_Type').modal('hide');
    });

    $("#add_glazingType").click(function(e) {
        e.preventDefault();
        $("#glazingTypeAddForm").modal('show');
    });

    $('.close_model').on('click', function() {
        $('#glazingTypeAddForm').modal('hide');
    });
    $("#add_glazingTypeCustome").click(function(e) {
        e.preventDefault();
        $("#glazingTypeAddFormcustome").modal('show');
    });

    $('.close_model').on('click', function() {
        $('#glazingTypeAddFormcustome').modal('hide');
    });
    $("#add_GlassGlazingType").click(function(e) {
        e.preventDefault();
        $("#addGlassGlazingType").modal('show');
    });

    $('.close_model').on('click', function() {
        $('#addGlassGlazingType').modal('hide');
    });

    $("#Add_IntumescentSealColor").click(function(e) {
        e.preventDefault();
        $("#Add_Intumescent_Seal_Color").modal('show');
    });

    $('.close_model').on('click', function() {
        $('#Add_Intumescent_Seal_Color').modal('hide');
    });

    $("#AddAccoustics").click(function(e) {
        e.preventDefault();
        $("#add_Accoustics").modal('show');
    });

    $('.close_model').on('click', function() {
        $('#add_Accoustics').modal('hide');
    });

    $("#AddDoorLeafFacing").click(function(e) {
        e.preventDefault();
        $("#add_Door_Leaf").modal('show');
    });

    $('.close_model').on('click', function() {
        $('#add_Door_Leaf').modal('hide');
    });

    $("#add_Overpanel_Glass_Type").click(function(e) {
        e.preventDefault();
        $("#slAndFlGlassTypeAddForm").modal('show');
    });

    $('.close_model').on('click', function() {
        $('#slAndFlGlassTypeAddForm').modal('hide');
    });

    $("#Overpanel_Glazing_System").click(function(e) {
        e.preventDefault();
        $("#SlandFlglazingTypeAddForm").modal('show');
    });
    $('.close_model').on('click', function() {
        $('#SlandFlglazingTypeAddForm').modal('hide');
    });


    function colorListAdd(DoorLeafFacing){
        $("#colorListAdd").modal('show');
    }

    $("#add_SideScreen_Glass_Type").click(function(e) {
        e.preventDefault();
        $("#SideScreenGlassTypeAddForm").modal('show');
    });

    $('.close_model').on('click', function() {
        $('#SideScreenGlassTypeAddForm').modal('hide');
    });

    $("#add_SideScreen_Glazing_System").click(function(e) {
        e.preventDefault();
        $("#SideScreenGlazingSystemAddForm").modal('show');
    });

    $('.close_model').on('click', function() {
        $('#SideScreenGlazingSystemAddForm').modal('hide');
    });

    function editLeafType(id,NormaDoorCore,VicaimaDoorCore,Seadec,Deanta,LeafType,selectedPrice,selectedId){
        $("#leafTypeAddForm").modal('show');
        $("input[name=id]").val(id);
        $("input[name=selectId]").val(selectedId);
        $("input[name=leafType]").val(LeafType);
        $("input[name=leafPrice]").val(selectedPrice);
        $('input:checkbox[name="normaDoorCore"][value="'+NormaDoorCore+'"]').prop('checked',true);
        $('input:checkbox[name="vicaimaDoorCore"][value="'+VicaimaDoorCore+'"]').prop('checked',true);
        $('input:checkbox[name="seadecDoorCore"][value="'+Seadec+'"]').prop('checked',true);
        $('input:checkbox[name="deantaDoorCore"][value="'+Deanta+'"]').prop('checked',true);
    }

    function editGlassType(id,Streboard,Halspan,NormaDoorCore,VicaimaDoorCore,Seadec,Deanta,NFR,FD30,FD60,GlassIntegrity,GlassType,GlassThickness,VpAreaSize,selectedPrice,selectedId){
        $("#glassTypeAddForm").modal('show');
        $("input[name=id]").val(id);
        $("input[name=selectId]").val(selectedId);
        $("input[name=glassType]").val(GlassType);
        $("input[name=glassThickness]").val(GlassThickness);
        $("input[name=vpareasize]").val(VpAreaSize);
        $("input[name=glassPrice]").val(selectedPrice);
        $('input[name=integrity][value='+GlassIntegrity+']').attr('checked', 'checked');
        $('input:checkbox[name="config[]"][value="'+Streboard+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Halspan+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+NormaDoorCore+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+VicaimaDoorCore+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Seadec+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Deanta+'"]').prop('checked',true);
        $('input:checkbox[name="firerating[]"][value="'+NFR+'"]').prop('checked',true);
        $('input:checkbox[name="firerating[]"][value="'+FD30+'"]').prop('checked',true);
        $('input:checkbox[name="firerating[]"][value="'+FD60+'"]').prop('checked',true);
    }
    function editGlassTypeCustome(id,Streboard,Halspan,Flamebreak,Stredor,NFR,FD30,FD60,GlassIntegrity,GlassType,GlassThickness,selectedPrice,selectedId, glazingBeads){
        $("#glassTypeCustomeAddForm").modal('show');
        $("input[name=id]").val(id);
        $("input[name=selectId]").val(selectedId);
        $("input[name=glassType]").val(GlassType);
        $("input[name=glassThickness]").val(GlassThickness);
        $("input[name=glassPrice]").val(selectedPrice);
        $('input[name=integrity][value='+GlassIntegrity+']').attr('checked', 'checked');
        $('input:radio[name="config[]"][value="'+Streboard+'"]').prop('checked',true);
        $('input:radio[name="config[]"][value="'+Halspan+'"]').prop('checked',true);
        $('input:radio[name="config[]"][value="'+Flamebreak+'"]').prop('checked',true);
        $('input:radio[name="config[]"][value="'+Stredor+'"]').prop('checked',true);
        $('input:checkbox[name="firerating[]"][value="'+NFR+'"]').prop('checked',true);
        $('input:checkbox[name="firerating[]"][value="'+FD30+'"]').prop('checked',true);
        $('input:checkbox[name="firerating[]"][value="'+FD60+'"]').prop('checked',true);

        const selectedConfigurations = $("input[name='config[]']:checked").map(function() {
            return $(this).val(); // Get the value of the checked checkbox (e.g., '2' for Halspan)
        }).get();

        const optionsslug = 'leaf1_glazing_beads';

        // Load and check glazing beads dynamically
        $.ajax({
            url: '/options/get-glazing-beads', // Adjust the endpoint to fetch glazing bead options
            method: 'GET',
            data: {
                    configurations: selectedConfigurations,
                    optionsslug: optionsslug
                },
            // success: function(response) {
            //     const glazingBeadsContainer = $("#glazingBeadsContainer");
            //     glazingBeadsContainer.empty(); // Clear previous checkboxes
            //     console.log(response)
            //     // Add each glazing bead as a checkbox and check if it's in the saved values
            //     response.forEach(bead => {
            //         const isChecked = glazingBeads.includes(bead.OptionKey) ? 'checked' : '';
            //         console.log(isChecked,bead.OptionKey,glazingBeads)
            //         glazingBeadsContainer.append(`
            //             <input type="checkbox" name="glazingBeads[]" value="${bead.OptionKey}" class="ml-3 option-style" ${isChecked}>
            //             ${bead.OptionValue}<br>
            //         `);
            //     });
            // },
            success: function(response) {
                const $glazingBeadsContainer = $("#glazingBeadsContainer");
                $glazingBeadsContainer.empty(); // Clear previous checkboxes
                console.log('Response:', response);
                console.log('Original Glazing Beads:', glazingBeads);

                // Parse glazingBeads if it's a serialized JSON string
                let selectedBeads = [];
                if (typeof glazingBeads === 'string') {
                    try {
                        selectedBeads = JSON.parse(glazingBeads); // Parse the JSON string
                    } catch (error) {
                        console.error('Error parsing glazingBeads:', error);
                    }
                } else if (Array.isArray(glazingBeads)) {
                    selectedBeads = glazingBeads; // Use directly if already an array
                }

                console.log('Processed Glazing Beads as Array:', selectedBeads);

                response.forEach(bead => {
                    // Trim spaces and ensure consistent comparison
                    const beadKey = bead.OptionKey.trim();
                    const isChecked = selectedBeads.some(selected => selected.trim() === beadKey) ? 'checked' : '';

                    console.log(`Bead: ${beadKey}, Checked: ${isChecked}, Glazing Beads: ${selectedBeads}`);

                    $glazingBeadsContainer.append(`
                        <input type="checkbox" name="glazingBeads[]" value="${beadKey}" class="ml-3 option-style" ${isChecked}>
                        ${bead.OptionValue}<br>
                    `);
                });
            },
            error: function() {
                console.error("Could not fetch glazing bead options.");
            }
        });
    }

    function editColor(id,DoorLeafFacing,DoorLeafFacingValue,ColorName,Hex,selectedPrice,selectedId){
        $("#add_color").modal('show');
        $("input[name=id]").val(id);
        $("input[name=selectId]").val(selectedId);
        // $("select[name=DoorLeafFacing]").val(DoorLeafFacing);
        $("select[name=DoorLeafFacingval]").val(DoorLeafFacingValue);
        $("input[name=ColorName]").val(ColorName);
        $("input[name=Hex]").val(Hex);
        $("input[name=colorPrice]").val(selectedPrice);
        if(DoorLeafFacing == 'Laminate'){
            DoorLeafFacingChange(DoorLeafFacing,DoorLeafFacingValue);
        }

    }

    function editAccoustics(id,Streboard,Halspan,NormaDoorCore,VicaimaDoorCore,Seadec,Deanta,Flamebreak,Stredor,underattribute,accoustics,file,selectedPrice,selectedId){
        let url = $('#url').val();
        $("#add_Accoustics").modal('show');
        $("input[name=id]").val(id);
        $("input[name=selectId]").val(selectedId);
        $("#accousticsImage").empty().html('<img src = "'+ url +'/uploads/Options/'+file+'" style="width:100%">');
        $("input[name=image]").attr('required',false);
        $("input[name=AccousticsName]").val(accoustics);
        $("input[name=AccousticsPrice]").val(selectedPrice);
        $('input[name=AccousticsOption][value='+underattribute+']').attr('checked', 'checked');
        $('input:checkbox[name="config[]"][value="'+Streboard+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Halspan+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+NormaDoorCore+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+VicaimaDoorCore+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Seadec+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Deanta+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Flamebreak+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Stredor+'"]').prop('checked',true);
    }

    function editDoorLeafFacing(id,Streboard,Halspan,NormaDoorCore,VicaimaDoorCore,Seadec,Deanta,Flamebreak,Stredor,option,doorleaffacing,selectedPrice,selectedId){
        let url = $('#url').val();
        $("#add_Door_Leaf").modal('show');
        //if(option == 'Veneer'){
            //$("#DoorLeafFacingPrice").attr('required',true);
            //$("#DoorLeafFacingPriceDiv").show();
            $("input[name=DoorLeafFacingPrice]").val(selectedPrice);
        //}
        $("input[name=id]").val(id);
        $("input[name=selectId]").val(selectedId);
        $("input[name=DoorLeafFacingName]").val(doorleaffacing);
        $('input[name=DoorLeafOption][value='+option+']').attr('checked', 'checked');
        $('input:checkbox[name="Streboard"][value="'+Streboard+'"]').prop('checked',true);
        $('input:checkbox[name="Halspan"][value="'+Halspan+'"]').prop('checked',true);
        $('input:checkbox[name="NormaDoorCore"][value="'+NormaDoorCore+'"]').prop('checked',true);
        $('input:checkbox[name="VicaimaDoorCore"][value="'+VicaimaDoorCore+'"]').prop('checked',true);
        $('input:checkbox[name="SeadecDoorCore"][value="'+Seadec+'"]').prop('checked',true);
        $('input:checkbox[name="deantaDoorCore"][value="'+Deanta+'"]').prop('checked',true);
        $('input:checkbox[name="Flamebreak"][value="'+Flamebreak+'"]').prop('checked',true);
        $('input:checkbox[name="Stredor"][value="'+Stredor+'"]').prop('checked',true);
        doorLeafFacingOption(option);
    }

    function editGlazingSystem(id,Streboard,Halspan,NormaDoorCore,VicaimaDoorCore,Seadec,Deanta,NFR,FD30,FD60,GlazingSystem,GlazingBeadFixingDetail,VpAreaSize,selectedPrice,selectedId,GlazingThickness){
        $("#glazingTypeAddForm").modal('show');
        $("input[name=id]").val(id);
        $("input[name=selectId]").val(selectedId);
        $("input[name=glazingSystem]").val(GlazingSystem);
        $("input[name=GlazingBeadFixingDetail]").val(GlazingBeadFixingDetail);
        $("input[name=vpareasize]").val(VpAreaSize);
        $("input[name=glazingPrice]").val(selectedPrice);
        $("input[name=GlazingThickness]").val(GlazingThickness);
        $('input:checkbox[name="config[]"][value="'+Streboard+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Halspan+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+NormaDoorCore+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+VicaimaDoorCore+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Seadec+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Deanta+'"]').prop('checked',true);
        $('input:checkbox[name="firerating[]"][value="'+NFR+'"]').prop('checked',true);
        $('input:checkbox[name="firerating[]"][value="'+FD30+'"]').prop('checked',true);
        $('input:checkbox[name="firerating[]"][value="'+FD60+'"]').prop('checked',true);
    }
    function editGlassGlazingSystem(id,Configurableitems,GlassType,GlazingSystem,VpAreaSize){
        $("#addGlassGlazingType").modal('show');
        $("input[name=id]").val(id);
        $("select[name=glazingSystem]").val(GlazingSystem).trigger('change');
        $("select[name=GlassType]").val(GlassType).trigger('change');
        $("input[name=vpareasize]").val(VpAreaSize);
        $('input[name=config][value='+Configurableitems+']').attr('checked', 'checked');
        editGlassGlazing(Configurableitems,GlassType,GlazingSystem);
    }

    function editGlazingSystemCustome(id,Streboard,Halspan,Flamebreak,Stredor,NFR,FD30,FD60,GlazingSystem,GlazingBeadFixingDetail,selectedPrice,selectedId,GlazingThickness){
        $("#glazingTypeAddFormcustome").modal('show');
        $("input[name=id]").val(id);
        $("input[name=selectId]").val(selectedId);
        $("input[name=glazingSystem]").val(GlazingSystem);
        $("input[name=GlazingBeadFixingDetail]").val(GlazingBeadFixingDetail);
        $("input[name=glazingPrice]").val(selectedPrice);
        $("input[name=GlazingThickness]").val(GlazingThickness);
        $('input:checkbox[name="config[]"][value="'+Streboard+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Halspan+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Flamebreak+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Stredor+'"]').prop('checked',true);
        $('input:checkbox[name="firerating[]"][value="'+NFR+'"]').prop('checked',true);
        $('input:checkbox[name="firerating[]"][value="'+FD30+'"]').prop('checked',true);
        $('input:checkbox[name="firerating[]"][value="'+FD60+'"]').prop('checked',true);
    }

    function editIntumescentSealColor(id,Streboard,Halspan,NormaDoorCore,VicaimaDoorCore,Seadec,Deanta,Flamebreak,Stredor,IntumescentSealColorName,selectedId){

        $("#Add_Intumescent_Seal_Color").modal('show');
        $("input[name=id]").val(id);
        $("input[name=selectId]").val(selectedId);
        $("input[name=IntumescentSealColorName]").val(IntumescentSealColorName);
        $('input:checkbox[name="config[]"][value="'+Streboard+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Halspan+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+NormaDoorCore+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+VicaimaDoorCore+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Seadec+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Deanta+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Flamebreak+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Stredor+'"]').prop('checked',true);
    }

    function editArchitraveType(id,Streboard,Halspan,NormaDoorCore,VicaimaDoorCore,Seadec,Deanta,Flamebreak,Stredor,ArchitraveType,selectedPrice,selectedId){
        $("#add_Architrave_Type").modal('show');
        $("input[name=id]").val(id);
        $("input[name=selectId]").val(selectedId);
        $("input[name=ArchitraveTypeName]").val(ArchitraveType);
        $("input[name=ArchitraveTypePrice]").val(selectedPrice);
        $('input:checkbox[name="config[]"][value="'+Streboard+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Halspan+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+NormaDoorCore+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+VicaimaDoorCore+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Seadec+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Deanta+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Flamebreak+'"]').prop('checked',true);
        $('input:checkbox[name="config[]"][value="'+Stredor+'"]').prop('checked',true);
    }

    function editintumescentSealArrangement(id,configurableitems,firerating,configuration,Point1height ,Point2height,Point1width,Point2width,intumescentSeals,brand,firetested,selected_cost,selectedId,customeLeafId){
        $("#intumescentSealArrangementAddForm").modal('show');
        $("input[name=id]").val(id);
        $("input[name=selectId]").val(selectedId);
        $("select[name=configuration]").val(configuration);
        $("input[name=intumescentSeals]").val(intumescentSeals);
        $("input[name=Point1height]").val(Point1height);
        $("input[name=Point2height]").val(Point2height);
        $("input[name=Point1width]").val(Point1width);
        $("input[name=Point2width]").val(Point2width);
        $("input[name=brand]").val(brand);
        $("input[name=firetested]").val(firetested);
        $("input[name=IntumescentSealPrice]").val(selected_cost);
        $('input[name=configurableitems][value='+configurableitems+']').attr('checked', 'checked');
        $('input[name=firerating][value='+firerating+']').attr('checked', 'checked');

        handleDoorTypeChange(configurableitems,customeLeafId)
    }

    function editSLAndFLGlassType(id,Streboard,Halspan,Flamebreak,Stredor,NFR,FD30,FD60,GlassIntegrity,GlassType,WidthFanlight,HeightFanlight,SideScreenWidth,SideScreenHeight,TransomThickness,TransomDepth,GlassThickness,selectedPrice,selectedId){
        $("#slAndFlGlassTypeAddForm").modal('show');
        $("input[name=id]").val(id);
        $("input[name=selectId]").val(selectedId);
        $("input[name=GlassType]").val(GlassType);
        $("input[name=FanLightWidth]").val(WidthFanlight);
        $("input[name=FanLightHeight]").val(HeightFanlight);
        $("input[name=SideScreenWidth]").val(SideScreenWidth);
        $("input[name=SideScreenHeight]").val(SideScreenHeight);
        $("input[name=TransomThickness]").val(TransomThickness);
        $("input[name=TransomDepth]").val(TransomDepth);
        $("input[name=glassPrice]").val(selectedPrice);
        $("input[name=GlassThickness]").val(GlassThickness);
        $('input[name=GlassIntegrity][value='+GlassIntegrity+']').attr('checked', 'checked');
        $('input:radio[name="config[]"][value="'+Streboard+'"]').prop('checked',true);
        $('input:radio[name="config[]"][value="'+Halspan+'"]').prop('checked',true);
        $('input:radio[name="config[]"][value="'+Flamebreak+'"]').prop('checked',true);
        $('input:radio[name="config[]"][value="'+Stredor+'"]').prop('checked',true);
        $('input:radio[name="firerating[]"][value="'+NFR+'"]').prop('checked',true);
        $('input:radio[name="firerating[]"][value="'+FD30+'"]').prop('checked',true);
        $('input:radio[name="firerating[]"][value="'+FD60+'"]').prop('checked',true);
    }

    function editSLAndFLGlazingSystem(id,Streboard,Halspan,Flamebreak,Stredor,NFR,FD30,FD60,GlazingSystem,GlazingBeadFixingDetail,MinBeading,MinBeadingHeight,MinBeadingWidth,selectedPrice,selectedId,GlassThickness,GlassId){
        $("#SlandFlglazingTypeAddForm").modal('show');
        $("input[name=id]").val(id);
        $("input[name=selectId]").val(selectedId);
        $("input[name=GlazingSystem]").val(GlazingSystem);
        $("input[name=FixingDetails]").val(GlazingBeadFixingDetail);
        $("input[name=Beading]").val(MinBeading);
        $("input[name=BeadingHeight]").val(MinBeadingHeight);
        $("input[name=BeadingWidth]").val(MinBeadingWidth);
        $("input[name=glazingPrice]").val(selectedPrice);
        $('input:radio[name="config[]"][value="'+Streboard+'"]').prop('checked',true);
        $('input:radio[name="config[]"][value="'+Halspan+'"]').prop('checked',true);
        $('input:radio[name="config[]"][value="'+Flamebreak+'"]').prop('checked',true);
        $('input:radio[name="config[]"][value="'+Stredor+'"]').prop('checked',true);
        $('input:radio[name="firerating[]"][value="'+NFR+'"]').prop('checked',true);
        $('input:radio[name="firerating[]"][value="'+FD30+'"]').prop('checked',true);
        $('input:radio[name="firerating[]"][value="'+FD60+'"]').prop('checked',true);
        $('select[name="GlassTypeOverpanel"]').val(GlassId);
        editGlassGlazingOverPanel(GlassId);
    }

    function deleteGlassType(optionType,id) {
        swal({
                title: "Are you sure?",
                text: "if you delete this parent and child will be delete. not get back anyone",
                type: "error",
                confirmButtonText: "Delete",
                showCancelButton: true
            })
            .then((result) => {
                if (result.value) {
                    // $("#delete").click();
                    $.ajax({
                        url: "{{route('options/deleteGlassType')}}",
                        method: "POST",
                        data: {
                            'id': id,
                            'optionType': optionType,
                            _token: $("#_token").val()
                        },
                        success: function(result) {
                            swal(
                                'Success',
                                'Option Deleted the <b style="color:green;">Success</b>!',
                                'success'
                            ).then((result) => {
                                window.location.reload();
                            })
                        }
                    });


                } else if (result.dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        'Option not deleted!',
                        'error'
                    )
                }
            })
    }
    function deletecolor(id) {
        swal({
                title: "Are you sure?",
                text: "if you delete this parent and child will be delete. not get back anyone",
                type: "error",
                confirmButtonText: "Delete",
                showCancelButton: true
            })
            .then((result) => {
                if (result.value) {
                    // $("#delete").click();
                    $.ajax({
                        url: "{{ url('colors/deletecolor') }}",
                        method: "POST",
                        data: {
                            'id': id,
                            _token: $("#_token").val()
                        },
                        success: function(result) {
                            swal(
                                'Success',
                                'Option Deleted the <b style="color:green;">Success</b>!',
                                'success'
                            ).then((result) => {
                                window.location.reload();
                            })
                        }
                    });


                } else if (result.dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        'Option not deleted!',
                        'error'
                    )
                }
            })
    }

    function editDoorDimensional(id,configurableitems,fire_rating,code,inch_height,inch_width,mm_height,mm_width,door_leaf_finish,door_leaf_facing,cost_price,image,selectedPrice,selectedId,leaf_type){

        let url = $('#url').val();
        $("#door_dimensionAddForm").modal('show');
        $("input[name=id]").val(id);
        $("input[name=selectId]").val(selectedId);
        $("input[name=DoorDimensionPrice]").val(selectedPrice);
        $('input[name=configurableitems][value='+configurableitems+']').attr('checked', 'checked');
        $('input[name=fire_rating][value='+fire_rating+']').attr('checked', 'checked');
        $("input[name=mm_height]").val(mm_height);
        $("input[name=mm_width]").val(mm_width);
        $("input[name=mm_height]").attr('required',true);
        $("input[name=mm_width]").attr('required',true);
        if(configurableitems == 3 || configurableitems == 4 || configurableitems == 5 || configurableitems == 6){
            $(".configurableitemsdoordimension").trigger("click");
            $(".leaf_type_door").css("display", "block");
            $("select[name=leaf_type]").val(leaf_type);
            $("select[name=door_leaf_facing]").val(door_leaf_facing).trigger("change");
            $("input[name=code]").val(code);
            $("input[name=cost_price]").val(cost_price);
            $("input[name=inch_height]").val(inch_height);
            $("input[name=inch_width]").val(inch_width);
            $("#leaf_type").attr('required',true);
            $("#inch_height").attr('required',true);
            $("#inch_width").attr('required',true);
            if(configurableitems == 4 || configurableitems == 5 || configurableitems == 6){

                $("#door_leaf_finish").attr('required',false);
                $("#doorLeafFinish").css("display", "none");
            }else{
                $("#door_leaf_finish").attr('required',true);
                $("#doorLeafFinish").css("display", "block");
            }


            $("#door_leaf_facing").attr('required',true);

            // $("#cost_price").attr('required',true);
            $("#selling_price").attr('required',true);
            // $("#code").attr('required',true);
            $("#image").attr({"required":false});
            if(image != ''){
                $("#doorDimensionImage").empty().html('<img src = "'+ url +'/DoorDimension/'+image+'" style="width:100%">');
            }
            doorleafFacing(door_leaf_finish);

        }else{
            $("#leaf_type").attr({"required":false}).val('');
            $("#inch_height").attr({"required":false}).val('');
            $("#inch_width").attr({"required":false}).val('');
            $("#door_leaf_facing").attr({"required":false}).val('');
            $("#door_leaf_finish").attr({"required":false}).val('');
            $("#cost_price").attr({"required":false}).val('');
            $("#selling_price").attr({"required":false}).val('');
            $("#code").attr({"required":false}).val('');
            $("#image").attr({"required":false});
            $("#doorDimensionImage").empty();
            $(".leaf_type_door").css("display", "none");
        }
        doorDimensionValue(leaf_type, configurableitems, door_leaf_facing);
    }

    function handleEditButton(button) {
    var id = button.getAttribute('data-id');
    var configurableitems = button.getAttribute('data-configurableitems');
    var fire_rating = button.getAttribute('data-fire_rating');
    var code = button.getAttribute('data-code');
    var inch_height = button.getAttribute('data-inch_height');
    var inch_width = button.getAttribute('data-inch_width');
    var mm_height = button.getAttribute('data-mm_height');
    var mm_width = button.getAttribute('data-mm_width');
    var door_leaf_finish = button.getAttribute('data-door_leaf_finish');
    var door_leaf_facing = button.getAttribute('data-door_leaf_facing');
    var cost_price = button.getAttribute('data-cost_price');
    var image = button.getAttribute('data-image');
    var selected_cost = button.getAttribute('data-selected_cost');
    var selected_id = button.getAttribute('data-selected_id');
    var leaf_type = button.getAttribute('data-leaf_type');

    // Parse the JSON stored in the data-custome_door_selected_cost attribute
    var custome_door_selected_cost = JSON.parse(button.getAttribute('data-custome_door_selected_cost'));

    // Populate the modal with these values
    editDoorDimensionalCustome(id, configurableitems, fire_rating, code, inch_height, inch_width, mm_height, mm_width, door_leaf_finish, door_leaf_facing, cost_price, image, selected_cost, selected_id, leaf_type, custome_door_selected_cost);
    }


    function editDoorDimensionalCustome(id, configurableitems, fire_rating, code, inch_height, inch_width, mm_height, mm_width, door_leaf_finish, door_leaf_facing, cost_price, image, selectedPrice, selectedId, leaf_type, custome_door_selected_cost) {
    let url = $('#url').val();
    let custome_door_selected_costs = JSON.parse(custome_door_selected_cost);
    $("#door_dimension_customeAddForm").modal('show');
    $("input[name=id]").val(id);
    $("input[name=selectId]").val(selectedId);
    // $("input[name=DoorDimensionPrice]").val(selectedPrice);
    // $('input[name=configurableitems][value=' + configurableitems + ']').attr('checked', 'checked');
    $('input[name=configurableitems]').prop('checked', false); // Clear previous selection
    $('input[name=configurableitems][value=' + configurableitems + ']').prop('checked', true); // Set the current record's value

    $('input[name=fire_rating][value=' + fire_rating + ']').attr('checked', 'checked');
    $("input[name=mm_height]").val(mm_height);
    $("input[name=mm_width]").val(mm_width);
    $("input[name^='prices']").val(''); // Clear previous values
    $("input[name=mm_height]").attr('required', true);
    $("input[name=mm_width]").attr('required', true);



        // var startKey = configurableitems === '7' ? 10 : 1;
        // var maxKeys = configurableitems === '1' ? 6 : (configurableitems === '7' ? 17 : 3);


        // for (var key = startKey; key <= maxKeys; key++) {
        //     var stringKey = key.toString(); // Convert to string

        //     var priceInput = $("input[name='prices[" + stringKey + "]']"); // Get input field
        //     console.log(priceInput)
        //     // Check if the key exists in the object
        //     if (custome_door_selected_costs.hasOwnProperty(stringKey)) {
        //         var value = custome_door_selected_costs[stringKey];

        //         if (value !== null && value !== '') {
        //             // Format the value to two decimal places and set
        //             priceInput.val(parseFloat(value).toFixed(2));
        //             console.log("Setting price for Leaf Type " + stringKey + ": " + priceInput.val());
        //         } else {
        //             priceInput.val(''); // Clear the field if the value is null
        //             console.log("Clearing price for Leaf Type " + stringKey + " (value was null or empty)");
        //         }
        //     } else {
        //         console.warn("No input found for prices[" + stringKey + "]"); // Debugging
        //     }
        // }
        var startKey = configurableitems === '7' ? 10 :
               configurableitems === '8' ? 18 :
               1;

var maxKeys = configurableitems === '1' ? 6 :
              configurableitems === '7' ? 17 :
              configurableitems === '8' ? 25 :
              3;

for (var key = startKey; key <= maxKeys; key++) {
    var stringKey = key.toString(); // Convert to string

    var priceInput = $("input[name='prices[" + stringKey + "]']"); // Get input field
    console.log(priceInput);

    // Check if the key exists in the object
    if (custome_door_selected_costs.hasOwnProperty(stringKey)) {
        var value = custome_door_selected_costs[stringKey];

        if (value !== null && value !== '') {
            // Format the value to two decimal places and set
            priceInput.val(parseFloat(value).toFixed(2));
            console.log("Setting price for Leaf Type " + stringKey + ": " + priceInput.val());
        } else {
            priceInput.val(''); // Clear the field if the value is null
            console.log("Clearing price for Leaf Type " + stringKey + " (value was null or empty)");
        }
    } else {
        console.warn("No input found for prices[" + stringKey + "]"); // Debugging
    }
}

    // Show or hide fields based on configurable items
    if (configurableitems == '1') {
        $('.sterboard-fields').show();
        $('.halspan-fields').hide();
        $('.flamebreak-fields').hide();
        $('.stredor-fields').hide();
        // Enable sterboard inputs and disable halspan inputs
        $('.sterboard-fields input').prop('disabled', false);
        $('.halspan-fields input').prop('disabled', true);
        $('.flamebreak-fields input').prop('disabled', true);
        $('.stredor-fields input').prop('disabled', true);
    } else if (configurableitems == '2') {
        $('.sterboard-fields').hide();
        $('.halspan-fields').show();
        $('.flamebreak-fields').hide();
        $('.stredor-fields').hide();
        // Enable halspan inputs and disable sterboard inputs
        $('.halspan-fields input').prop('disabled', false);
        $('.sterboard-fields input').prop('disabled', true);
        $('.flamebreak-fields input').prop('disabled', true);
        $('.stredor-fields input').prop('disabled', true);
    }
    else if (configurableitems == '7') {
        $('.sterboard-fields').hide();
        $('.halspan-fields').hide();
        $('.flamebreak-fields').show();
        $('.stredor-fields').hide();
        // Enable halspan inputs and disable sterboard inputs
        $('.halspan-fields input').prop('disabled', true);
        $('.sterboard-fields input').prop('disabled', true);
        $('.flamebreak-fields input').prop('disabled', false);
        $('.stredor-fields input').prop('disabled', true);
    }
    else if (configurableitems == '8') {
        $('.sterboard-fields').hide();
        $('.halspan-fields').hide();
        $('.flamebreak-fields').hide();
        $('.stredor-fields').show();
        // Enable halspan inputs and disable sterboard inputs
        $('.halspan-fields input').prop('disabled', true);
        $('.sterboard-fields input').prop('disabled', true);
        $('.flamebreak-fields input').prop('disabled', true);
        $('.stredor-fields input').prop('disabled', false);
    }

    // Call other functions as needed
    doorDimensionValue(leaf_type, configurableitems, door_leaf_facing);
}

// Submit event handler for the form
$(document).ready(function () {
    $('#door_dimension_customeAddForm').on('submit', function (event) {
        var maxKeys = 6; // Maximum number of price fields

        for (var key = 1; key <= maxKeys; key++) {
            var priceInput = $("input[name='prices[" + key + "]']");
            if (priceInput.length) {
                var value = priceInput.val().trim(); // Get the input value

                // Format to two decimal places if the value is not empty
                if (value !== '') {
                    priceInput.val(parseFloat(value).toFixed(2));
                } else {
                    priceInput.val(''); // Handle empty values
                }
            }
        }
    });
});


    function editSideScreenGlazingSystem(id,glassType,FireRating,GlazingSystem,FixingDetails,Beading,BeadingHeight,BeadingWidth,glazingSelectedPrice,selectedId,GlazingThickness,GlassType)
    {
        $("input[name=glass_ids]").val(glassType);
        $("#SideScreenGlazingSystemAddForm").modal('show');
        $("input[name=id]").val(id);
        $("input[name=selectId]").val(selectedId);
        $("select[name=FireRating]").val(FireRating);
        $("input[name=GlazingSystem]").val(GlazingSystem);
        $("input[name=Beading]").val(Beading);
        $("input[name=GlazingThickness]").val(GlazingThickness);
        $("input[name=FixingDetails]").val(FixingDetails);
        $("input[name=BeadingWidth]").val(BeadingWidth);
        $("input[name=BeadingHeight]").val(BeadingHeight);
        $("input[name=glazingPrice]").val(glazingSelectedPrice);
        SinglePane();
    }

    function editSideScreenGlassType(id,DFRating,FireRating,GlassType,WidthPoint1,HeightPoint1,WidthPoint2,HeightPoint2,TransomThickness,TransomDepth,AreaSize,FrameDensity,glassSelectedPrice,selectedId)
    {
        $("#SideScreenGlassTypeAddForm").modal('show');
        $("input[name=id]").val(id);
        $("input[name=selectId]").val(selectedId);
        $("select[name=FireRating]").val(FireRating);
        $("input[name=GlassType]").val(GlassType);
        $("input[name=WidthPoint1]").val(WidthPoint1);
        $("input[name=HeightPoint1]").val(HeightPoint1);
        $("input[name=WidthPoint2]").val(WidthPoint2);
        $("input[name=HeightPoint2]").val(HeightPoint2);
        $("input[name=TransomThickness]").val(TransomThickness);
        $('input[name="TransomDepth"]').val(TransomDepth);
        $('input[name="AreaSize"]').val(AreaSize);
        $('input[name="glassPrice"]').val(glassSelectedPrice);
        $('input[name="selectedId"]').val(TransomDepth);
        $('input[name="DFRating"]').val(DFRating);
        $('input[name="FrameDensity"]').val(FrameDensity);
    }

    function doorDimensionValue(leaf_type, configurableitems, door_leaf_facing){

        $("input[name=mm_height]").attr('required',true);
        $("input[name=mm_width]").attr('required',true);
    //    var configurableitemsdoordimension = $('.configurableitemsdoordimension').val();


        if(configurableitems == 1 || configurableitems == 2){
            $("#leaf_type").attr({"required":false}).val('');
            $("#inch_height").attr({"required":false}).val('');
            $("#inch_width").attr({"required":false}).val('');
            $("#door_leaf_facing").attr({"required":false}).val('');
            $("#door_leaf_finish").attr({"required":false}).val('');
            $("#cost_price").attr({"required":false}).val('');

            $("#selling_price").attr({"required":false}).val('');
            $("#code").attr({"required":false}).val('');
            $(".leaf_type_door").css("display", "none");
            $("#image").attr({"required":false});
        }else{
            if(configurableitems == 4 || configurableitems == 5 || configurableitems == 6){
                $("#doorLeafFinish").css("display", "none");
                $("#door_leaf_finish").attr('required',false);
                $('#leaf_type').val('');
            $('.doorLeafTypeValue').css('display', 'block');
            $('.first3optionLeafType').css('display', 'none');
            $('#leaf_type').val(leaf_type);

            setTimeout(() => {
                ajaxDoorLeafFacing(door_leaf_facing, configurableitems);
            }, 100);
            }
            // else{
            //     $("#door_leaf_finish").attr('required',true);
            //     $("#doorLeafFinish").css("display", "block");
            //     $('#leaf_type').val('');
            // $('.doorLeafTypeValue').css('display', 'none');
            // $('.first3optionLeafType').css('display', 'block');
            // }

            $("#leaf_type").attr('required',true);
            $("#inch_height").attr('required',true);
            $("#inch_width").attr('required',true);
            $("#door_leaf_facing").attr('required',true);
            // $("#cost_price").attr('required',true);
            $("#selling_price").attr('required',true);
            // $("#code").attr('required',true);
            $("#image").attr({"required":false});
            $(".leaf_type_door").css("display", "block");

        }
    }

    function ajaxDoorLeafFacing(doorLeafFacingValue='',configurableitems = ''){
           let leaftypeValue = $('#leaf_type').val();
            $('.leafFacingOptionBefore').remove();

            $.ajax({
            type: "Post",
            dataType: "json",
            url: "{{ route('quotation/getDoorFacing') }}",
            data: {
                leaftypeValue:leaftypeValue,
                _token: '{{ csrf_token() }}'
            },
            success: function(

            ) {

                if (response.status === 'ok' && Array.isArray(response.leafTypeFacing)) {

                    let html = '';
                    response.leafTypeFacing.forEach(function(item) {
                        isSelected = item.doorLeafFacingValue.trim() == doorLeafFacingValue.trim();

                    html += `<option value="${item.doorLeafFacingValue}" ${isSelected ? 'selected' : ''} class="leafFacingOptionBefore">${item.doorLeafFacingValue}</option>`
                    });


                    $('#door_leaf_facing').append(html);



                    if (configurableitems == 4 || configurableitems == 5 || configurableitems == 6) {

                        $('.doorLeafType').css('display', 'block');
            $('.doorLeafFacingExistingOption').css('display', 'none');

        }else{

            $('.leafFacingOptionBefore').remove();
            $('.doorLeafFacingExistingOption').css('display', 'block');
            $('.doorLeafType').css('display', 'none');
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

function dimension_delete(id,doorCore){
    var r = confirm("Are you sure! you wan't to delete door dimension. not get back anyone");
      if (r == true) {
          $('#id').val(id);
          $('#doorCore').val(doorCore);
          $('#dimension_delete').submit();
      }
  }

$(document).ready(function() {
    $(".configurableitemsdoordimension").on("click",function(){

        $("input[name=mm_height]").attr('required',true);
        $("input[name=mm_width]").attr('required',true);
        if($(this).val() == 1 || $(this).val() == 2){
            $("#leaf_type").attr({"required":false}).val('');
            $("#inch_height").attr({"required":false}).val('');
            $("#inch_width").attr({"required":false}).val('');
            $("#door_leaf_facing").attr({"required":false}).val('');
            $("#door_leaf_finish").attr({"required":false}).val('');
            $("#cost_price").attr({"required":false}).val('');

            $("#selling_price").attr({"required":false}).val('');
            $("#code").attr({"required":false}).val('');
            $(".leaf_type_door").css("display", "none");
            $("#image").attr({"required":false});
        }else{
            if($(this).val() == 4 || $(this).val() == 5 || $(this).val() == 6){
                $("#doorLeafFinish").css("display", "none");
                $("#door_leaf_finish").attr('required',false);
            }else{
                $("#door_leaf_finish").attr('required',true);
                $("#doorLeafFinish").css("display", "block");
            }
            $("#leaf_type").attr('required',true);
            $("#inch_height").attr('required',true);
            $("#inch_width").attr('required',true);
            $("#door_leaf_facing").attr('required',true);
            // $("#cost_price").attr('required',true);
            $("#selling_price").attr('required',true);
            // $("#code").attr('required',true);
            $("#image").attr({"required":false});
            $(".leaf_type_door").css("display", "block");

            var data = $("#leaf_typedata").val();
            console.log(data);
            if(data!=''){
                data =  JSON.parse(data);
                config_type = $(this).val();
                var lenght = data.length;
                innerHtml = '';
                innerHtml += '<option value="">Select Leaf Type</option>';
                for(var index = 0; index<lenght;index++){
                    if(config_type == data[index].NormaDoorCore || config_type == data[index].VicaimaDoorCore || config_type == data[index].Seadec || config_type == data[index].Deanta ){
                        innerHtml +=  '<option value="'+ data[index].LeafType +'" class="">'+ data[index].LeafType +'</option>';
                    }
                }
                $('#leaf_type').empty().html(innerHtml);
            }
        }

    });

    $("#leaf_type").on("change",function(){
        commanLeafType();
    });

    function commanLeafType() {
        let doorLeafType = $('#leaf_type').val();
        let pageId = 0;
        $('input[name="configurableitems"]:checked').each(function() {
            pageId = this.value;
         });
        $.ajax({
            type: "POST",
            dataType: "json",
            url: "{{ route('quotation/doorLeafFacingValue') }}",
            data: {
                doorLeafType: doorLeafType,
                pageId:pageId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.status === 'ok' && Array.isArray(response.doorleafFacingOption)) {

                    let html = '<option value="">Select Leaf Facing</option>';

                    response.doorleafFacingOption.forEach(function(item) {
                        html += `<option value="${item.doorLeafFacingValue}">${item.doorLeafFacingValue}</option>`;
                    });

                    $('#door_leaf_facing').empty().html(html);

                } else {
                    console.error('Invalid response format');
                }

                if (response.status === 'ok' && Array.isArray(response.doorLeafFinish)) {


                    let html1 = '<option value="">Select Door Leaf Finish</option>';

                    response.doorLeafFinish.forEach(function(val) {

                       html1 += `<option value="${val.ColorName}">${val.ColorName}</option>`;
                    });
                    $('#door_leaf_finish').empty().html(html1 );
                } else {
                    console.error('Invalid response format');
                }

            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error("Ajax request failed: " + textStatus, errorThrown);
            }
        });
    }


    //$(document).on('change', '#configurableitems', function() {
      //  let pageId = $(this).val();
        //let url = "{{url('options/select')}}"+'/'+pageId+'/{{Request::segment(4)}}';
        //window.location.href =url;
    //})

    $('#accordion header').click(function() {
        $(this).next()
            .slideToggle(200)
            .closest('.question')
            .toggleClass('active')
            .siblings()
            .removeClass('active')
            .find('main')
            .slideUp(200);
    })
});

//door leaf finish filter
function doorleafFacing(facingValue = ""){
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    var url = $("#door_leaf_fanish_filter").val();

    $.ajax({
        url:url,
        type:"POST",
        data:{doorLeafFacing:$('#door_leaf_facing').val(),facingValue:facingValue, pageId:3},
        success:function(data){
            var result = "<option value=''>Select Door leaf finish</option>";
            for(var i = 0; i < data.length; i++){
                var select = '';
                if(facingValue){
                    if(facingValue == data[i]['OptionKey']){
                        select = 'selected';
                    }
                }
                result += '<option value="'+data[i]['OptionKey']+'" '+ select +'>'+data[i]['OptionValue']+'</option>';
            }
            $("#door_leaf_finish").empty().append(result)
        }
    })
}

$('input[type="checkbox"]').click(function(){
    const val = $(this).val()
    $.ajax({
        url: "{{url('options/update-check-option')}}",
        method: "POST",
        dataType: "Json",
        data: {
            optionId: val,
            _token: "{{ csrf_token() }}",
            optionname:"option"
        },
        success: function(result) {
            if(result.optionType == 'option'){
                if (result.status = "ok") {
                    if($("input[value='" + result.id + "']").prop('checked') == true){
                        $("input[value='" + result.id + "']").prop('checked', false);
                    }else{
                        $("input[value='" + result.id + "']").prop('checked', true);
                    }
                } else {
                    alert(result.status);
                }
            }
        }
    });
});

function updateMe(className, pageId) {

    var selectedValue = [];
   $('.'+className+':checked').map(function(_, el) {
        selectedValue.push($(el).val());
    }).get();

    $.ajax({
        headers: {
    'X-CSRF-TOKEN': "{{ csrf_token() }}"
    },
        url: "{{url('options/update-option')}}",
        method: "POST",
        dataType: "Json",
        data: {
            selectedValue: selectedValue,
            className: className,
            pageId:pageId,
            _token: "{{ csrf_token() }}"
        },
        success: function(result) {
            if (result.status = "ok") {
                alert('Data updated successfully.')
                location.reload();
            } else {
                alert(result.msg);
            }
        }


    });

}

function updateSelectedOptionCost(OptionType, SelectedOptionId, SelectedOptionCost,brand,intumescentSeals) {

    // alert(SelectedOptionId);
    // alert(SelectedOptionCost);

    // return 0;

    let formData = new FormData();
    formData.append('SelectedOptionId', SelectedOptionId);
    formData.append('SelectedOptionCost', SelectedOptionCost);
    formData.append('OptionType', OptionType);
    formData.append('brand', brand);
    formData.append('intumescentSeals', intumescentSeals);
    formData.append('_token', $("#_token").val());

    $.ajax({
        url: "{{url('options/update-selected-option-cost')}}",
        method: "POST",
        dataType: "Json",
        data: formData,
        contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
        processData: false, // NEEDED, DON'T OMIT THIS
        success: function(result) { console.log(result)
            if (result.status = "ok") {
                // alert('Data updated successfully.')
                // location.reload();
            } else {
                alert(result.msg);
            }
        }

    });

}
    $('.updateSelectedOptionCost').click(function() {
        const SelectedOptionId = $(this).attr('SelectedOptionId');
        const SelectedOptionCost = $(this).attr('SelectedOptionCost');
        const OptionType = $(this).attr('OptionType');
        const brand = $(this).attr('brand');
        const intumescentSeals = $(this).attr('intumescentSeals');

        updateSelectedOptionCost(OptionType, SelectedOptionId, SelectedOptionCost,brand,intumescentSeals);

    });

    $(".price_update").keyup(function(event) {
        //$(this).next().find(".prices").click();
        var value = $(this).val();

        var val = $(this).data('optionid');
        var optionname = $(this).data('optionname');
        $.ajax({
            url: "{{url('options/update-check-option')}}",
            method: "POST",
            dataType: "Json",
            data: {
                optionId: val,
                _token: "{{ csrf_token() }}",
                optionname:optionname
            },
            success: function(result) {
                if(result.optionType == 'option'){
                    if(result.msg = "ok") {
                        $('#'+result.SelectedOptionId).val(value);
                        const OptionType = $('#'+result.SelectedOptionId).attr('OptionType');
                        const brand = $('#'+result.SelectedOptionId).attr('brand');
                        const intumescentSeals = $('#'+result.SelectedOptionId).attr('intumescentSeals');
                        updateSelectedOptionCost(OptionType, result.SelectedOptionId, value,brand,intumescentSeals);
                    } else {
                        alert(result.msg);
                    }
                }else if(result.optionType == 'intumescentSealArrangement'){
                    if(result.msg = "ok") {
                        $('#'+result.SelectedOptionId).val(value);
                        const OptionType = 'intumescentSealArrangement';
                        const brand = result.brand;
                        const intumescentSeals = result.intumescentSeals;
                        updateSelectedOptionCost(OptionType, result.SelectedOptionId, value,brand,intumescentSeals);
                    } else {
                        alert(result.msg);
                    }
                }

            },
        });
        $(this).next().find(".prices").click();
    });

    $('.intumescentSealArrangement').click(function(){
    const val = $(this).val();
    $.ajax({
        url: "{{url('options/update-check-option')}}",
        method: "POST",
        dataType: "Json",
        data: {
            optionId: val,
            _token: "{{ csrf_token() }}",
            optionname:"intumescentSealArrangement"
        },
        success: function(result) {
            if(result.optionType == 'intumescentSealArrangement'){
                if (result.status = "ok") {

                    if($("input[value='" + result.id + "']").prop('checked') == true){
                        $("input[value='" + result.id + "']").prop('checked', false);
                    }else{
                        $("input[value='" + result.id + "']").prop('checked', true);
                    }
                } else {
                    alert(result.status);
                }
            }
        }


    });


});

function chooseoptioncost(price,id,selectedId,OptionType){
    if(price >= 0){
        let formData = new FormData();
        formData.append('price', parseFloat(price).toFixed(2));
        formData.append('id', id);
        formData.append('selectedId', selectedId);
        formData.append('OptionType', OptionType);
        formData.append('_token', $("#_token").val());
        $.ajax({
            url: "{{url('options/update-selected-option-cost')}}",
            method: "POST",
            dataType: "Json",
            data: formData,
            contentType: false, // NEEDED, DON'T OMIT THIS (requires jQuery 1.6+)
            processData: false, // NEEDED, DON'T OMIT THIS
            success: function(result) { console.log(result)
                if (result.status = "ok") {
                } else {
                    alert(result.msg);
                }
            }

        });
    }else{
        alert("Invalid Value Entered!");
        return false;
    }

}

function chooseoptioncostcustome(price, id, selectedId, OptionType, leafTypeId) {
    console.log(price, id, selectedId, OptionType, leafTypeId);

    if(price >= 0){
        let formData = new FormData();

        // Format the key as 'price[leafid]' and set the price as the value
        formData.append(`price[${leafTypeId}]`, parseFloat(price).toFixed(2));

        // Add other fields
        formData.append('id', id);
        formData.append('selectedId', selectedId);
        formData.append('OptionType', OptionType);
        formData.append('leafid', leafTypeId);
        formData.append('_token', $("#_token").val());

        $.ajax({
            url: "{{url('options/update-selected-option-cost-custome')}}",
            method: "POST",
            dataType: "json", // JSON should be lowercase here
            data: formData,
            contentType: false, // Required for FormData to work properly
            processData: false,  // Required for FormData to work properly
            success: function(result) {
                console.log(result);
                if (result.status === "ok") {
                    // Handle successful response
                    console.log("Price updated successfully");
                } else {
                    alert(result.msg);
                }
            },
            error: function(err) {
                console.error("Update failed", err);
            }
        });
    } else {
        alert("Invalid Value Entered!");
        return false;
    }
}


function DoorLeafFacingChange(value,DoorLeafFacingValue = '') {
    let UnderAttribute = value;
    if(UnderAttribute == 'Laminate'){
        $('#coloroptionHide').show();
        $.ajax({
            type: 'post',
            url: "{{route('ColorDoorLeafFacing')}}",
            data: {'UnderAttribute':UnderAttribute,'_token': $("#_token").val(),'DoorLeafFacingValue':DoorLeafFacingValue},
            success: function(result) {
                $("#DoorLeafFacingval").attr({"required":true});
                $("#DoorLeafFacingval").empty().append(result);
            },
            error: function(data) {
                $(".page-loader-action").fadeOut();
                swal("Oops!!", "Something went wrong. Please try again.", "error");
            }
        });
    }else{
        $("#DoorLeafFacingval").attr({"required":false});
        $('#coloroptionHide').hide();
    }

}

// fetching glass beads from options table
$(document).ready(function() {
    function toggleGlazingBeads() {
        // Get selected configurations and fire ratings dynamically
        const selectedConfigurations = $("input[name='config[]']:checked").map(function() {
            return $(this).val(); // Get the value of the checked checkbox (e.g., '2' for Halspan)
        }).get();

        const optionsslug = 'leaf1_glazing_beads';


        // Only send the request if configurations and fire ratings are selected
        if (selectedConfigurations.length > 0) {
            // Send AJAX request with selected configurations and fire ratings
            $.ajax({
                url: '/options/get-glazing-beads',
                method: 'GET',
                data: {
                    configurations: selectedConfigurations,
                    optionsslug: optionsslug
                },
                success: function(response) {
                    const $glazingBeadsContainer = $("#glazingBeadsContainer");
                    $glazingBeadsContainer.empty(); // Clear previous options

                    // Remove duplicates based on OptionKey or OptionValue
                    // Assuming OptionKey is the identifier of uniqueness
                    const uniqueBeads = response.filter((value, index, self) => {
                        return index === self.findIndex((t) => (
                            t.OptionKey === value.OptionKey // Make sure OptionKey is unique
                        ));
                    });

                    // Add each unique glazing bead as a checkbox
                    uniqueBeads.forEach(bead => {
                        $glazingBeadsContainer.append(`
                            <input type="checkbox" name="glazingBeads[]" value="${bead.OptionKey}" class="ml-3 option-style">
                            ${bead.OptionValue}<br>
                        `);
                    });

                    // Ensure the modal stays open
                    // $('#glassTypeCustomeAddForm').modal('show');
                },
                error: function() {
                    console.error("Could not fetch glazing bead options.");
                }
            });
        } else {
            // Optionally, hide or show the glazing beads field depending on selection
            // $('#glassTypeCustomeAddForm').modal('hide');
        }
    }

    // Attach event listeners to the checkboxes for config and fire ratings
    $("input[name='config[]'], input[name='firerating[]']").change(function() {
        // Call the toggle function whenever a checkbox is selected or unselected
        toggleGlazingBeads();
    });

    // Optionally handle closing the modal by clicking on the close button
    $(".close_model").click(function() {
        $('#glassTypeCustomeAddForm').modal('hide');
    });
});


// $(document).on('change','')

function colorChange(selector, hexType = false) {
    //    alert($(selector).val());
    var colorVal = $(selector).val();

    if (colorVal.trim() == "") {
        $('#colorfill').css("background-color", "#fff");
        return false;
    }

    if (hexType) {
        if (!colorVal.includes("#")) {
            alert("Invalid value");
            $('#colorfill').val("");
            return false;
        }

    } else {

        if (colorVal.includes("#")) {
            alert("Invalid value");
            $('#colorfill').val("");
            return false;
        }
    }

    if (colorVal.includes("#")) {
        $('#colorfill').css("background-color", colorVal);
    } else {
        $('#colorfill').css("background-color", "rgb(" + colorVal + ")");

    }

}

// sidelight glass type
$(document).on('change', 'input[name="firerating[]"]', function() {
        let firerating = $(this).val();
        let confi = $('input[name="config[]"]:checked').val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url: "{{url('options/filter-glass-type-overpanel')}}",
            method: "POST",
            dataType: "Json",
            data: {
                confi: confi,
                firerating:firerating,
                _token: "{{ csrf_token() }}"
            },
            success: function(result) {
                // Update Glass Type select field
                let glassTypeSelect = $('select[name="GlassTypeOverpanel"]');
                glassTypeSelect.empty(); // Clear existing options
                glassTypeSelect.append('<option value="">Select Glass Type</option>');
                if (result.GlassType && result.GlassType.length) {
                    result.GlassType.forEach(function(item) {
                        glassTypeSelect.append(`<option value="${item.GlassType}">${item.GlassType}</option>`);
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
});

function editGlassGlazingOverPanel(GlassType){
    console.log('hi')
        let firerating = $('input[name="firerating[]"]:checked').val();
        let confi = $('input[name="config[]"]:checked').val();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url: "{{url('options/filter-glass-type-overpanel')}}",
            method: "POST",
            dataType: "Json",
            data: {
                confi: confi,
                firerating:firerating,
                _token: "{{ csrf_token() }}"
            },
            success: function(result) {

                // Update Glass Type select field
                let glassTypeSelect = $('select[name="GlassTypeOverpanel"]');
                glassTypeSelect.empty(); // Clear existing options
                glassTypeSelect.append('<option value="">Select Glass Type</option>');
                if (result.GlassType && result.GlassType.length) {
                    result.GlassType.forEach(function(item) {
                        glassTypeSelect.append(`<option value="${item.GlassType}" >${item.GlassType}</option>`);
                    });
                }
                if (GlassType) {
                    console.log(glassTypeSelect)
                    glassTypeSelect.val(GlassType); // Set the selected value
                }

            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
}

$(document).on('change', 'input[name="configurableitems"]', function () {
    let configurableitems = $(this).val();
    handleDoorTypeChange(configurableitems,'');
});

function handleDoorTypeChange(configurableitems,customeLeafId){
    $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url: "{{url('options/filter-leaf-type')}}",
            method: "POST",
            dataType: "Json",
            data: {
                configurableitems: configurableitems,
                _token: "{{ csrf_token() }}"
            },
            success: function(result) {
                let container = $('.customedoor .options');
                let label = container.find('label').first().detach();
                container.empty();
                container.append(label);
                if (result && result.length) {
                    result.forEach(function (type) {
                        let checkbox = `
                            <input type="checkbox" name="leaf_type[]"
                                class="form-group ml-3 option-style"
                                value="${type.id}"> ${type.leaf_type_key}
                        `;
                        container.append(checkbox);
                    });
                    if(customeLeafId){
                        let selectedLeafTypes = customeLeafId.split(',');
                            $('input[name="leaf_type[]"]').prop('checked', false);
                            $('input[name="leaf_type[]"]').each(function() {
                            if (selectedLeafTypes.includes($(this).val())) {
                                $(this).prop('checked', true);
                            }
                    });
                    }
                    $('.customedoor').show();
                } else {
                    $('.customedoor').hide();
                }
            },
            error: function(xhr, status, error) {
                console.error("Error:", error);
            }
        });
}

$(document).on('change', 'input[name="configurableitems"]', function () {
    let configurableitems = $(this).val();
    if (configurableitems == '1') {
        $('.sterboard-fields').show();
        $('.halspan-fields').hide();
        $('.flamebreak-fields').hide();
        $('.stredor-fields').hide();
        // Enable sterboard inputs and disable halspan inputs
        $('.sterboard-fields input').prop('disabled', false);
        $('.halspan-fields input').prop('disabled', true);
        $('.flamebreak-fields input').prop('disabled', true);
        $('.stredor-fields input').prop('disabled', true);
    } else if (configurableitems == '2') {
        $('.sterboard-fields').hide();
        $('.halspan-fields').show();
        $('.flamebreak-fields').hide();
        $('.stredor-fields').hide();
        // Enable halspan inputs and disable sterboard inputs
        $('.halspan-fields input').prop('disabled', false);
        $('.sterboard-fields input').prop('disabled', true);
        $('.flamebreak-fields input').prop('disabled', true);
        $('.stredor-fields input').prop('disabled', true);
    }
    else if (configurableitems == '7') {
        $('.sterboard-fields').hide();
        $('.halspan-fields').hide();
        $('.flamebreak-fields').show();
        $('.stredor-fields').hide();
        // Enable halspan inputs and disable sterboard inputs
        $('.halspan-fields input').prop('disabled', true);
        $('.sterboard-fields input').prop('disabled', true);
        $('.flamebreak-fields input').prop('disabled', false);
        $('.stredor-fields input').prop('disabled', true);
    }
    else if (configurableitems == '8') {
        $('.sterboard-fields').hide();
        $('.halspan-fields').hide();
        $('.flamebreak-fields').hide();
        $('.stredor-fields').show();
        // Enable halspan inputs and disable sterboard inputs
        $('.halspan-fields input').prop('disabled', true);
        $('.sterboard-fields input').prop('disabled', true);
        $('.flamebreak-fields input').prop('disabled', true);
        $('.stredor-fields input').prop('disabled', false);
    }
});




// side screen
$(document).on('change', 'input[name="firerating[]"]', function() {
    let firerating = $(this).val();
    let confi = $('input[name="config[]"]:checked').val();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': "{{ csrf_token() }}"
        },
        url: "{{url('options/filter-glass-type-sideScreen')}}",
        method: "POST",
        dataType: "Json",
        data: {
            confi: confi,
            firerating:firerating,
            _token: "{{ csrf_token() }}"
        },
        success: function(result) {
            // Update Glass Type select field
            let glassTypeSelect = $('select[name="GlassTypeSideScreen"]');
            glassTypeSelect.empty(); // Clear existing options
            glassTypeSelect.append('<option value="">Select Glass Type</option>');

            if (result.GlassType && result.GlassType.length) {
                result.GlassType.forEach(function(item) {
                    glassTypeSelect.append(`<option value="${item.id}">${item.GlassType}</option>`);
                });
            }
        },
        error: function(xhr, status, error) {
            console.error("Error:", error);
        }
    });
});

{{--  $('.selectOption').DataTable({
    responsive: true
});  --}}

</script>
@endsection
