@extends("layouts.Master")

@section("main_section")
@if(session()->has('error'))
<style type="text/css">
    #useremail{
        border-color: red;
    }
</style>
@endif
<div class="app-main__outer">
<div class="app-main__inner">
           

    <div class="tab-content">
       <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="tab-content">
                                  <div class="card-header">
                            <h5 class="card-title" style="margin-top: 10px">Add New Doors</h5>
                            @if (!empty($msg))
                            <h1>{{$msg}}</h1>
                            
                            @endif
                        </div>
                 <form method="post" action="{{route('quotation/store-door')}}" >
                         {{csrf_field()}}
       
            <div class="card-body">
                <div class="form-row">
                         <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="doorNumber" class="">Door Number</label>
                                    <input name="doorNumber" id="doorNumber"  required placeholder="D-001" type="text" class="form-control">
                                </div>
                            </div>
                            <input type="hidden" name="quotationId" value="{{$quotationId}}">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="doorType" class="">Door Type</label>
                                    <input name="doorType" id="doorType"  required placeholder="IND-001" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group"><label for="fireRating" class="">Fire Rating</label>
                                <select name="fireRating" id="fireRating" class="form-control">
                                <option value="">Select Fire Rating</option>
                                @if(!empty($option_data) && count((array)$option_data))
                                    @foreach($option_data as $row)
                                        @if($row->OptionSlug=='fire_rating')
                                        <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                        @endif
                                    @endforeach
                                @endif
                                </select>
                            </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="visionPanel" class="">Vision Panel</label>
                                     <select class="form-control"  required name="visionPanel" id="visionPanel">
                                     <option value="">Is Over Panel?</option>
                                     <option value="Yes">Yes</option>
                                     <option value="No">No</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="floor" class="">Floor</label>
                                    <input name="floor" id="floor"  required placeholder="Enter floor" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="internalOrExternal" class="">Internal Or External</label>
                                    <input name="internalOrExternal" id="internalOrExternal"  required placeholder="Enter floor" type="text" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="soWidth" class="">SO Width</label>
                                    <input name="soWidth" id="soWidth"  required placeholder="200" type="number" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="soHeight" class="">SO Height</label>
                                    <input name="soHeight" id="soHeight"  required placeholder="200" type="number" class="form-control">
                                </div>
                            </div>

                            <div class="col-md-6">
                            <div class="position-relative form-group"><label for="doorFacing" class="">Door Facing</label>
                                <select name="doorFacing" id="doorFacing" class="form-control">
                                <option value="">Select Facing</option>
                                @if(!empty($option_data) && count((array)$option_data))
                                    @foreach($option_data as $row)
                                        @if($row->OptionSlug=='Door_Leaf_Facing')
                                        <option value="{{$row->OptionKey}}"  >{{$row->OptionValue}}</option>
                                        @endif
                                    @endforeach
                                @endif
                                </select>
                            </div>
                    </div>
                    
                        <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="nsb" class="">NSB</label>
                                        <input name="nsb" id="nsb"  required placeholder="" type="text" class="form-control">
                                    </div>
                        </div>
                </div>
            </div>
                               
                        
                        <div class="d-block text-right card-footer">
                            <button type="submit" id="submit" class="btn-wide btn btn-success" style="margin-right: 20px">
                              Add
                            </button>
                        </div>
             </form>
            </div>
        </div>

    </div>
    </div>


    </div>

</div>
@endsection
@section("script_section")

<script>
$("#newDoorID").change(function(){

    // $(this).value()
    var selectedDoor = $(this).val();
    var name = $("#newDoorID option[value='"+selectedDoor+"']").text();
    var doors = name.split('|');
    $("#doorNumber").val(doors[0]);
    $("#doorType").val(doors[1]);
   
});
</script>
@endsection