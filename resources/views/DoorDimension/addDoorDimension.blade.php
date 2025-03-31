@extends("layouts.Master")

@section("main_section")
@if(session()->has('error'))
<style type="text/css">
#useremail {
border-color: red;
}
.alert-dismissible {
word-wrap: break-word;
}




</style>
@endif
<style type="text/css">
.alert-dismissible {
word-wrap: break-word;
}
</style>
<div class="app-main__outer">
<div class="app-main__inner">
@if(session()->has('success'))
<div class="alert alert-success alert-dismissible">
<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
<h5><i class="icon fas fa-check"></i> Alert!</h5>
{!! session()->get('success') !!}
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
<form method="post" action="{{route('door-dimension-list-store')}}" enctype="multipart/form-data">
{{csrf_field()}}


  <input type="hidden" name="_token" value="{{ csrf_token() }}">

  <input type="hidden" name="update" value="@if(isset($editdata->id)){{$editdata->id}}@endif">

<div class="tab-content">
<div class="main-card mb-3 card">

    <div class="tab-content">


        <div class="row">
            <div class="col-md-12">
                <div class="main-card mb-3">
                    <div class="row">
                        <div class="col-sm-12 p-0">
                            <h5  style="margin-bottom:30px;"class="card-title">Door Dimension</h5>
                        </div>

                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="configurableitems">Configurable Item<span class="text-danger">*</span></label>
                                <select name="configurableitems" id="configurableitemsdoordimension" class="form-control" required="">
                                    <option value="">Configurable Item</option>
                                    <option value="1" selected>Streboard</option>
                                    <option value="2">Halspan</option>
                                    <option value="3">NormaDoorCore</option>
                                </select>
                            </div>
                        </div>

                                <div class="col-md-2 leaf_type_door" style="display:none;">
                                    <div class="form-group">
                                        <label for="Leaf Type">Leaf Type<span class="text-danger">*</span></label>
                                            <select name="leaf_type" id="leaf_type" class="form-control" required>
                                            <option value="">Select Leaf Type</option>
                                            <option value="Flush" >Flush</option>
                                            <option value="Shaker">Shaker</option>
                                            <option value="Moulded">Moulded</option>
                                            </select>
                                    </div>
                                </div>

                                <div class="col-sm-2 leaf_type_door" style="display:none;">
                                    <div class="form-group">
                                        <label for="Code">Code<span class="text-danger">*</span></label>
                                        <input name="code" id="code" type="text" class="form-control" placeholder="code"
                                            value="@if(isset($editdata->id)){{$editdata->code}}@else{{old('code')}}@endif" required>
                                    </div>
                                </div>
                                <div class="col-md-2 leaf_type_door" style="display:none;">
                                    <div class="form-group">
                                        <label for="inch_height">Inch Height<span class="text-danger">*</span></label>
                                        <input id = "inch_height" name="inch_height" type="number" class="form-control" placeholder="Inch Height" value="@if(isset($editdata->id)){{$editdata->inch_height}}@else{{old('inch_height')}}@endif" required>
                                    </div>
                                </div>
                                <div class="col-md-2 leaf_type_door" style="display:none;">
                                    <div class="form-group">
                                        <label for="inch_width">Inch Width<span class="text-danger">*</span></label>
                                        <input id = "inch_width" name="inch_width" type="number" class="form-control" placeholder="Inch Width" value="@if(isset($editdata->id)){{$editdata->inch_width}}@else{{old('inch_width')}}@endif" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="mm_height">MM Height<span class="text-danger">*</span></label>
                                        <input name="mm_height" type="number" class="form-control" placeholder="mm Height"
                                            value="@if(isset($editdata->id)){{$editdata->mm_height}}@else{{old('mm_height')}}@endif" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="mm_width">MM Width<span class="text-danger">*</span></label>
                                        <input name="mm_width" type="number" class="form-control" placeholder="MM Width"
                                            value="@if(isset($editdata->id)){{$editdata->mm_width}}@else{{old('mm_width')}}@endif" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="Fire Rating">Fire Rating<span class="text-danger">*</span></label>
                                            <select name="fire_rating" id="fire_rating" class="form-control" required>
                                            <option value="">Select Fire Rating</option>
                                            <option value="NFR" @if(isset($editdata->id)) @if($editdata->fire_rating=='NFR') selected @endif @endif>NFR</option>
                                            <option value="FD30" @if(isset($editdata->id)) @if($editdata->fire_rating=='FD30') selected @endif @endif>FD30</option>
                                            <option value="FD60" @if(isset($editdata->id)) @if($editdata->fire_rating=='FD60') selected @endif @endif>FD60</option>
                                            </select>
                                    </div>
                                </div>

                                <div class="col-md-2 leaf_type_door" style="display:none;">
                                    <div class="form-group">
                                        <label for="Door Leaf Facing">Door Leaf Facing<span class="text-danger">*</span></label>
                                            <select name="door_leaf_facing" id="door_leaf_facing" class="form-control" required>
                                            <option value="">Select Door leaf facing</option>
                                                @foreach($option_data as $row)
                                                @if($row->OptionSlug=='Door_Leaf_Facing')
                                                <option value="{{$row->OptionKey}}" @if(isset($Item["DoorLeafFacing"]))
                                                @if($Item["DoorLeafFacing"]==$row->OptionKey){{'selected'}} @endif
                                                @endif>{{$row->OptionValue}}</option>
                                                @endif
                                                @endforeach
                                            </select>
                                    </div>
                                </div>

                                <div class="col-md-2 leaf_type_door" style="display:none;">
                                    <div class="form-group">
                                        <label for="Door Leaf Finish">Door Leaf Finish<span class="text-danger">*</span></label>
                                            <select name="door_leaf_finish" id="door_leaf_finish" class="form-control" required>
                                            <option value="">Select Door leaf finish</option>
                                            </select>
                                    </div>
                                </div>

                                <div class="col-md-2 leaf_type_door" style="display:none;">
                                    <div class="form-group">
                                        <label for="cost_price">Cost Price<span class="text-danger">*</span></label>
                                        <input id="cost_price" name="cost_price" type="number" class="form-control" placeholder="cost price" value="@if(isset($editdata->id)){{$editdata->cost_price}}@else{{old('cost_price')}}@endif" required>
                                    </div>
                                </div>
                                <div class="col-md-2 leaf_type_door" style="display:none;" hidden>
                                    <div class="form-group">
                                        <label for="selling_price">Selling Price<span class="text-danger">*</span></label>
                                        <input name="selling_price" id="selling_price" type="number" class="form-control" placeholder="selling_price" value="0" required>
                                    </div>
                                </div>
                                <div class="col-md-2 leaf_type_door" style="display:none;">
                                    <div class="form-group">
                                        <label for="image">Image<span class="text-danger">*</span></label>
                                        <input name="image" id="image" type="file" class="form-control" placeholder="image" required>
                                         @if(isset($editdata->id))
                                        <img src="{{url('/')}}/DoorDimension/@if(isset($editdata->id)){{$editdata->image}}@else{{old('image')}}@endif" width="100px">
                                        @endif
                                    </div>
                                </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

</div>
<div class="main-card mb-3 custom_card">
<div class="d-block text-right">
<button type="submit" id="submit" class="btn-wide btn btn-success">
    @if(isset($editdata->id))
        Update Now
    @else
       submit
    @endif
</button>
</div>
</div>
</div>
</form>
<input type="hidden" id="door_leaf_fanish_filter" value="{{route('door-dimension/door-leaf-face-value-filter')}}">
<script type="text/javascript" src="{{url('/')}}/js/doorcore/norma/custome-rules.js"></script>
@endsection

