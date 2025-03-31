@extends("layouts.Master")
@section("main_section")

<div class="app-main__outer">
    <div class="app-main__inner">
        @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Alert!</h5>
            {{ session()->get('success') }}
        </div>
        @endif
        @if(session()->has('error'))
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="fas fa-skull-crossbones"></i> Alert!</h5>
            {{ session()->get('error') }}
        </div>
        @endif
        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="fas fa-skull-crossbones"></i> Alert!</h5>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <form method="post" action="{{route('quotation/store-new-screen')}}" novalidate="novalidate">
            {{csrf_field()}}
            <input type="hidden" name="quotationID" value="{{$quotationID}}">
            <input type="hidden" name="versionID" value="{{$vid}}">
            <div class="tab-content">
                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="card-header">
                                    <h5 class="card-title" style="margin-top: 10px">Add New Screens</h5>
                                </div>
                                <a href="{{url('quotation/request')}}/{{Request::segment(3)}}/{{$vid}}"
                                    class="btn-shadow btn btn-info float-right" style="margin-left: 5px;top: -45px;">
                                    <i class="fa fa-arrow-left"></i> Back
                                </a>
                            </div>
                        </div>
                        <div class="tab-content">
                            <div class="card-body">
                                <div class="form-row">
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="existingDoorId">Select screen Type</label>
                                            <select required="" name="screentypeId" id="existingDoorId" class="form-control">


                                                {!! $doortype !!}
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="location">Location</label>
                                            <input name="location" value="{{old('location')}}" id="location" required placeholder="Enter location"
                                                type="text" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="floor">Floor</label>
                                            @if(!empty($floor) || floor[0]->buildingType != 'Apartment')
                                            <select required="" name="floor" id="floor" class="form-control">
                                               <option value="" disabled selected>Select Floor</option>
                                                @foreach ($floor as $val)
                                                @if($val->buildingType == 'House')
                                                <option value="{{ $val->houseType }}">{{ $val->houseType }}</option>
                                                @elseif ($val->buildingType == 'Commercial')
                                                <option value="{{ $val->floorCount }}">{{ $val->floorCount }}</option>
                                                @endif

                                                @endforeach
                                            </select>
                                            @else
                                            <input name="floor" value="{{old('floor')}}" id="floor" required placeholder="Enter floor"
                                            type="text" class="form-control">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label for="doornumber">screen Number</label>
                                            <input name="screennumber" value="{{old('doornumber')}}" id="doornumber" class="form-control" placeholder="screen Number">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-block text-right card-footer">
                            <button type="submit" id="submit" class="btn-wide btn btn-success"
                                style="margin-right: 20px">
                                Add
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
@section("script_section")

<script>
$("#newDoorID").change(function() {

    // $(this).value()
    var selectedDoor = $(this).val();
    var name = $("#newDoorID option[value='" + selectedDoor + "']").text();
    var doors = name.split('|');
    $("#doorNumber").val(doors[0]);
    $("#doorType").val(doors[1]);

});


$("#existingDoorId").change(function() {

    var typefilterValue = $('#existingDoorId :selected').attr("typefilter");

    $('#newDoorID option').each(function() {

        //alert($(this).attr('typefilter'));

        if (typefilterValue != $(this).attr('typefilter')) {
            $(this).hide();
        }


    });

});
</script>
@endsection
