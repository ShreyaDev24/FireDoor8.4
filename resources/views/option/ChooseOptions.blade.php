@extends("layouts.Master")

@section("main_section")

<style>
    .option-style{
        margin-right: 5px;
    }
    .option-style:focus{
        box-shadow: 0px 1px 5px 0px rgba(8,12,252,1);
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
        <div class="card">
            <div class="row">

                <div class="col-md-2" style="padding-left: 0px;">
                    <div class="position-relative form-group ml-5 mt-3">
                        <label for="configurableitems">Configurable Item</label>
                        <select name="configurableitems" id="configurableitems" class="form-control" required>
                            <option value="" disabled>Configurable Item</option>
                            @foreach($ConfigurableItems as $tt)
                            <option value="{{$tt->id}}" @if($pageId==$tt->id)
                                {{'selected'}}
                                @endif
                                >{{$tt->name}}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-md-10">
                    @if ($optionType == 'leaf1_glass_type')
                        <button id="add_glassType" class="btn btn-info float-right mt-3">Add Glass Type</button>
                        {{--  <a href="{{ url('door-dimension-add') }}" class="btn btn-info float-right mt-3">Add Door
                        Dimension</a>  --}}
                    @elseif ($optionType == 'door_leaf_facing_value')
                        {{-- <a href="{{route('options/add1', [0, $optionType])}}" class="btn btn-info float-right mt-3">Add Door Leaf Facing</a> --}}
                        <button id="AddDoorLeafFacing" class="btn btn-info float-right mt-3">Add Door Leaf Facing</button>
                    @elseif ($optionType == 'leaf1_glazing_systems')
                        <button id="add_glazingType" class="btn btn-info float-right mt-3">Add Glazing Systems</button>
                        {{--  <a href="{{route('options/add1', [0, $optionType])}}" class="btn btn-info float-right mt-3">Add Glazing System</a>  --}}
                    @elseif ($optionType == 'Accoustics')
                        <button id="AddAccoustics" class="btn btn-info float-right mt-3">Add Accoustics</button>
                        {{--  <a href="{{route('options/add1', [0, $optionType])}}" class="btn btn-info float-right mt-3">Add Accoustics</a>  --}}
                    @elseif ($optionType == 'Intumescent_Seal_Color')
                        <button id="Add_IntumescentSealColor" class="btn btn-info float-right mt-3">Add Intumescent Seal Color</button>
                        {{--  <a href="{{route('options/add1', [0, $optionType])}}" class="btn btn-info float-right mt-3">Add Intumescent Seal Color</a>  --}}
                    @elseif ($optionType == 'intumescentSealArrangement')
                        <a href="{{ url('setting/intumescentseals/1') }}" class="btn btn-info float-right mt-3">Intumescentseal Arrangement</a>
                    @elseif ($optionType == 'lippingSpecies')
                        <a href="{{ url('setting/lipping-species') }}" class="btn btn-info float-right mt-3">Add Timber Species</a>
                    @elseif ($optionType == 'door_dimension')
                        <a href="{{ url('door-dimension-add') }}" class="btn btn-info float-right mt-3">Add Door
                        Dimension</a>
                    @elseif ($optionType == 'color_list')
                        <a href="{{ url('colors/create-color') }}" class="btn btn-info float-right mt-3">Add Color</a>
                    @endif

                </div>
            </div>

            <div id="accordion">
                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">

                {!! $tbl1 !!}

                <form action="{{route('DoorDimension/delete')}}" method="post" id="dimension_delete">
                    {{ csrf_field() }}
                    <input type="hidden" name="id" id="id">
                    <!-- <button type="submit" style="background:none" id=""></button> -->
                </form>

            </div>

        </div>

    </div>

</div>

<!-- Modal for Glass Type-->
<div class="modal fade" id="glassTypeAddForm" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog " style="margin-top: 70px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Glass Type</h5>
            </div>
            <div class="modal-body">
                <form id='add_glasstype_form' method="post" action="{{ route('option/update-glassType') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="Glass">
                    <div class="form-row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group options">
                                    <label for="glasstype " class="d-block">Configuration</label>
                                    <input type="checkbox" name="config[]"  class="form-group  ml-3 option-style" value="1" required>Streboard
                                    <input type="checkbox" name="config[]"  class="form-group  ml-3 option-style" value="2" required>Halspan
                                    <input type="checkbox" name="config[]"  class="form-group  ml-3 option-style" value="3" required>NormaDoorCore
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group firerating-options">
                                    <label for="glasstype" class="d-block">Fire Rating</label>
                                    <input type="checkbox" name="firerating[]"  class="form-group  ml-3 option-style" value="NFR" required>NFR
                                    <input type="checkbox" name="firerating[]"  class="form-group  ml-3 option-style" value="FD30" required>FD30
                                    <input type="checkbox" name="firerating[]"  class="form-group  ml-3 option-style" value="FD60" required>FD60
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype" class="d-block">Glass Integrity</label>
                                    <input type="radio" name="integrity"  class="form-group  option-style" value="Integrity_And_Insulation" required>Integrity And Insulation
                                    <input type="radio" name="integrity"  class="form-group  option-style" value="Integrity_only" required>Integrity only
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glass Type</label>
                                    <input type="text" name="glassType" placeholder="Enter Glass Type Name" class="form-control " required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glass Thickness</label>
                                    <input type="text" name="glassThickness" placeholder="Enter Glass Thickness Name" class="form-control " required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glass Price</label>
                                    <input type="number" name="glassPrice" placeholder="Enter Glass Price" class="form-control " required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 ">Close</button>
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
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Accoustics</h5>
            </div>
            <div class="modal-body">
                <form id='add_glasstype_form' method="post" action="{{ route('option/update-glassType') }}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="Accoustics">
                    <div class="form-row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group options">
                                    <label for="glasstype " class="d-block">Configuration</label>
                                    <input type="checkbox" name="config[]"  class="form-group  ml-3 option-style" value="1" required>Streboard
                                    <input type="checkbox" name="config[]"  class="form-group  ml-3 option-style" value="2" required>Halspan
                                    <input type="checkbox" name="config[]"  class="form-group  ml-3 option-style" value="3" required>NormaDoorCore
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype" class="d-block">Accoustics Option</label>
                                    <input type="radio" name="AccousticsOption"  class="form-group  option-style" value="Perimeter_Seal_1" required>Perimeter Seal 1
                                    <input type="radio" name="AccousticsOption"  class="form-group  option-style" value="Perimeter_Seal_2" required>Perimeter Seal 2
                                 {{--   <input type="radio" name="AccousticsOption"  class="form-group  option-style" value="Threshold_Seal_1" required>Threshold Seal 1
                                    <input type="radio" name="AccousticsOption"  class="form-group  option-style" value="Threshold_Seal_2" required>Threshold Seal 2 --}}
                                    <input type="radio" name="AccousticsOption"  class="form-group  option-style" value="Meeting_Stiles" required>Meeting Stiles
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Accoustics</label>
                                    <input type="text" name="AccousticsName" placeholder="Enter Accoustics Name" class="form-control " required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Accoustics Image</label>
                                    <input type="file" name="image" class="form-control" placeholder="Upload Accoustics Image">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Accoustics Price</label>
                                    <input type="number" name="AccousticsPrice" placeholder="Enter Accoustics Price" class="form-control " required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 ">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal for add_glazingType Type-->
<div class="modal fade" id="glazingTypeAddForm" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog " style="margin-top: 70px">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Glazing System</h5>
            </div>
            <div class="modal-body">
                <form id='add_glasstype_form' method="post" action="{{ route('option/update-glassType') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="Glazing">
                    <div class="form-row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group options">
                                    <label for="glasstype " class="d-block">Configuration</label>
                                    <input type="checkbox" name="config[]"  class="form-group  ml-3 option-style" value="1" required>Streboard
                                    <input type="checkbox" name="config[]"  class="form-group  ml-3 option-style" value="2" required>Halspan
                                    <input type="checkbox" name="config[]"  class="form-group  ml-3 option-style" value="3" required>NormaDoorCore
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group firerating-options">
                                    <label for="glasstype" class="d-block">Fire Rating</label>
                                    <input type="checkbox" name="firerating[]"  class="form-group  ml-3 option-style" value="NFR" required>NFR
                                    <input type="checkbox" name="firerating[]"  class="form-group  ml-3 option-style" value="FD30" required>FD30
                                    <input type="checkbox" name="firerating[]"  class="form-group  ml-3 option-style" value="FD60" required>FD60
                                </div>
                            </div>


                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glazing System</label>
                                    <input type="text" name="glazingSystem" placeholder="Enter Glazing System Name" class="form-control " required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Glass Price</label>
                                    <input type="number" name="glazingPrice" placeholder="Enter Glazing System Price" class="form-control " required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 ">Close</button>
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
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Intumescent Seal Color</h5>
            </div>
            <div class="modal-body">
                <form id='add_glasstype_form' method="post" action="{{ route('option/update-glassType') }}">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="IntumescentSealColor">
                    <div class="form-row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group options">
                                    <label for="glasstype " class="d-block">Configuration</label>
                                    <input type="checkbox" name="config[]"  class="form-group  ml-3 option-style" value="1" required>Streboard
                                    <input type="checkbox" name="config[]"  class="form-group  ml-3 option-style" value="2" required>Halspan
                                    <input type="checkbox" name="config[]"  class="form-group  ml-3 option-style" value="3" required>NormaDoorCore
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Intumescent Seal Color</label>
                                    <input type="text" name="IntumescentSealColorName" placeholder="Enter Intumescent Seal Color" class="form-control " required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Intumescent Seal Color Price</label>
                                    <input type="number" name="IntumescentSealColorPrice" placeholder="Enter Intumescent Seal Color Price" class="form-control " required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 ">Close</button>
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
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Door Leaf Facing</h5>
            </div>
            <div class="modal-body">
                <form id='add_glasstype_form' method="post" action="{{ route('option/update-glassType') }}" enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="optionName" value="DoorLeafFacing">
                    <div class="form-row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group options">
                                    <label for="glasstype " class="d-block">Configuration</label>
                                    <input type="checkbox" name="config[]"  class="form-group  ml-3 option-style" value="1" required>Streboard
                                    <input type="checkbox" name="config[]"  class="form-group  ml-3 option-style" value="2" required>Halspan
                                    <input type="checkbox" name="config[]"  class="form-group  ml-3 option-style" value="3" required>NormaDoorCore
                                </div>
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype" class="d-block">Door Laef Facing Option</label>
                                    <input type="radio" name="DoorLeafOption"  class="form-group  option-style" value="Veneer" required>Veneer
                                    <input type="radio" name="DoorLeafOption"  class="form-group  option-style" value="Laminate" required>Laminate
                                    <input type="radio" name="DoorLeafOption"  class="form-group  option-style" value="PVC" required>PVC
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Door Laef Facing</label>
                                    <input type="text" name="DoorLeafFacingName" placeholder="Enter Accoustics Name" class="form-control " required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="glasstype">Door Laef Facing Price</label>
                                    <input type="number" name="DoorLeafFacingPrice" placeholder="Enter Accoustics Price" class="form-control " required>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <button type="button" class="btn btn-danger close_model ml-2 px-3 ">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection


@section('js')

<script>
    // JQUERY

    $(function(){
        var requiredCheckboxes = $('.options :checkbox[required]');
        requiredCheckboxes.change(function(){
            if(requiredCheckboxes.is(':checked')) {
                requiredCheckboxes.removeAttr('required');
            } else {
                requiredCheckboxes.attr('required', 'required');
            }
        });
    });
    $(function(){
         var requiredCheckboxes = $('.firerating-options :checkbox[required]');
        requiredCheckboxes.change(function(){
            if(requiredCheckboxes.is(':checked')) {
                requiredCheckboxes.removeAttr('required');
            } else {
                requiredCheckboxes.attr('required', 'required');
            }
        });
    });

    $("#add_glassType").click(function(e) {
        e.preventDefault();
        $("#glassTypeAddForm").modal('show');
    });

    $('.close_model').on('click', function() {
        $('#glassTypeAddForm').modal('hide');
    });

    $("#add_glazingType").click(function(e) {
        e.preventDefault();
        $("#glazingTypeAddForm").modal('show');
    });

    $('.close_model').on('click', function() {
        $('#glazingTypeAddForm').modal('hide');
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

    function deletefunction(id) {
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
                        url: "{{route('options/delete')}}",
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

function dimension_delete(id){
    var r = confirm("Are you sure! you wan't to delete door dimension. not get back anyone");
      if (r == true) {
          $('#id').val(id);
          $('#dimension_delete').submit();
      }
  }

$(document).ready(function() {
    $(document).on('change', '#configurableitems', function() {
        let pageId = $(this).val();
        let url = "{{url('options/select')}}"+'/'+pageId+'/{{Request::segment(4)}}';
        window.location.href =url;
    })

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


    $('.checkall').click(function() {
        const tabname = $(this).val();
        console.log(tabname)
        // if ($(this).is(':checked')) {
        //     $('input[class='+tabname+']').prop('checked', true);
        // } else {
        //     $('input[class='+tabname+']').prop('checked', false);
        // }

        if ($(this).is(':checked')) {
            $('.'+tabname).prop('checked', true);
        } else {
            $('.'+tabname).prop('checked', false);
        }

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

</script>
@endsection
