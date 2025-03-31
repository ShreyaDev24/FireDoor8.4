@extends("layouts.Master")

@section("main_section")
<style type="text/css">
    .addfields,
    .removefields {
        margin-top: 30px !important;
    }
</style>
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
            <h5><i class="icon fas fa-check"></i> Alert!</h5>
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

        <form id='add_glasstype_form' method="post" action="{{ route('non-configural-items/store') }}" enctype="multipart/form-data">
            <div class="tab-content">
                <div class="main-card mb-3 card">
                <div class="card-header"><h4 class="card-title">Add Non Configurable Item</h4></div>
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <div class="form-row">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="name">Name<span class="text-danger">*</span></label>
                                    <input type="text" name="name" placeholder="Enter Your Name" class="form-control " required value="@if(!empty($editdata)){{$editdata->name}}@endif">
                                    <input type="hidden" name="id" value="@if(!empty($editdata) && !empty($editdata->id)){{$editdata->id}}@endif" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="desc">Description<span class="text-danger">*</span></label>
                                    <textarea name="description" id="non_config_description" rows="5" cols="30" class="form-control" placeholder="Enter Your Description" required>@if(!empty($editdata) && !empty($editdata->description)){{$editdata->description}}@endif</textarea>
                                </div>
                            </div>
                            <div class="col-md-10">
                                <div class="position-relative form-group">
                                    <label for="image">Image<span class="text-danger">*</span></label>
                                    <input type="file" name="image"  class="form-control" @if(empty($editdata->id)) {{"required"}} @endif>
                                </div>
                            </div>
                            <div class="col-md-2">
                                @if(isset($editdata->id))
                                <img src="{{url('/')}}/NonConfigImg/{{$editdata->image}}" class="" width="100px">
                                @endif
                            </div>

                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="product-code">Product Code<span class="text-danger">*</span></label>
                                    <input type="text" name="product_code" id="p_code" placeholder="Enter Product Code" class="form-control" value="@if(!empty($editdata) && !empty($editdata->product_code)){{$editdata->product_code}}@endif" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="unit">Unit<span class="text-danger">*</span></label>
                                    <input type="text" name="unit" id="unit" placeholder="Enter your unit" class="form-control" value="@if(!empty($editdata) && !empty($editdata->unit)){{$editdata->unit}}@endif" required>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="cost">Price<span class="text-danger">*</span></label>
                                    <input type="number" name="price" id="price" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0" placeholder="Enter your price" class="form-control" value="@if(!empty($editdata) && !empty($editdata->price)){{$editdata->price}}@endif" required>
                                </div>
                            </div>
                          
                        
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" id='task_submit'>Submit</button>
                        <a href="{{ url('non-configural-items/list') }}">
                        <button type="button" id="delete" class="btn btn-danger close_model ml-2 px-3 close_btn">Close</button>
</a>
                    </div>
                </div>
            </div>
        </form>

    </div>
</div>

@endsection

@section('js')
<script>
    $("#non_config_description").on('keydown', function (e) {
        if (this.value.length === 0 && e.which === 32) e.preventDefault();
    });
</script>

@endsection