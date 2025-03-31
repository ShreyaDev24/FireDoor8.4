@extends("...layouts.Master")

@section("main_section")
<div class="app-main__outer">
    <div class="app-main__inner">
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
        @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Alert!</h5>
            {{ session()->get('success') }}
        </div>
        @endif
        <div class="main-card mb-3 custom_card">
            <div class="card-header">
                <h5 class="card-title" style="margin-top: 10px">Add Non Configurable Item</h5>
            </div>
            <form action="{{route('items/save-non-configurable-items')}}" method="post" enctype="multipart/form-data">
                {{ csrf_field() }}
                <div class="card-body">
                    <div class="container-fluid">
                        <div class="alert response-section" role="alert" style="display:none;"></div>                        
                        <div class="form-row">
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="item_name" class="">Name</label>
                                    <input name="item_name" id="item_name" required placeholder="Enter name" type="text"
                                        class="form-control" value="@if(session()->has('data')){{session()->get('data')->name}}@endif">
                                    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                                    <input type="hidden" name="updid" value="@if(session()->has('data')){{session()->get('data')->id}}@endif">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="price" class="">Price</label>
                                    <input name="price" id="price" required placeholder="Enter price" type="text"
                                        class="form-control" value="@if(session()->has('data')){{session()->get('data')->price}}@endif">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <div class="position-relative form-group">
                                    <label for="description" class="">Description</label>
                                    <textarea name="description" id="description" required
                                        placeholder="Enter description" class="form-control">@if(session()->has('data')){{session()->get('data')->description}}@endif</textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="image" class="">Image</label>
                                    <input name="image" id="image" type="file" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                @if(session()->has('data'))
                                    <img src="{{url('/')}}/uploads/non-configurable-items/{{session()->get('data')->image}}" alt="Image" style="width:50px">                                    
                                @endif
                            </div>
                        </div>                        
                    </div>
                </div>
                <div class="d-block text-right card-footer">
                    <button type="submit" id="submit" class="btn-wide btn btn-success" style="margin-right: 20px">
                    @if(session()->has('data')){{ 'Update Now' }}@else {{ 'Submit Now' }}@endif 
                    </button>
                </div>
            </form>
        </div>           
        <div class="main-card mb-3 custom_card">
            <div class="card-header">
                <h5 class="card-title" style="margin-top: 10px">Manage Non Configurable Item</h5>
            </div>
            <form action="{{route('updNonconfigurable')}}" method="post">
                {{ csrf_field() }}
                <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                    <thead class="text-uppercase table-header-bg">
                        <tr class="text-white">
                            <th>S.No.</th>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Description</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        {!! $tbl !!}
                    </tbody>
                </table>
            </form>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
// $(document).on("submit", "#itemForm", function(e) {
//     e.preventDefault();

//     var formdata = new FormData(this);
//     $.ajax({
//         type: "POST",
//         url: "{{url('items/save-non-configurable-items')}}",
//         data: formdata,
//         dataType: "json",
//         processData: false,
//         contentType: false,
//         cache: false,
//         success: function(data) {
//             console.log(data);
//             if (data.status == "success") {
//                 $(".response-section").removeClass('alert-danger');
//                 $(".response-section").addClass('alert-success');
//                 $(".response-section").empty().append(data.message).show();
//                 setTimeout(function() {
//                     location.reload();
//                 }, 2000);
//             } else {
//                 $(".response-section").removeClass('alert-success');
//                 $(".response-section").addClass('alert-danger');
//                 $(".response-section").empty().append(data.message).show();
//             }

//             setTimeout(function() {
//                 $(".response-section").fadeOut();
//             }, 3000);

//         },
//         error: function(data) {
//             swal("Oops!", "Something went wrong. Please try again.", "error");
//         }
//     });
// });
</script>

@endsection