@extends("layouts.Master")
@section("main_section")
    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="col-lg-12">
                <div class="card-body tab-card-body">
                    <div class="tab-content">
                        @if(session()->has('success'))
                        <div class="alert alert-success alert-dismissible">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <h5><i class="icon fas fa-check"></i> Alert!</h5>
                            {{ session()->get('success') }}
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
                        <div class="main-card mb-3 card">
                            <form class="" enctype="multipart/form-data" method="post" action="{{route('store-color')}}">
                                {{csrf_field()}}
                                @if(session()->has('upd'))
                                <input type="hidden" name="update" value="{{session()->get('upd')->id}}">
                                @endif
                                <div class="card-body">
                                    <div class="tab-content">
                                        <div class="card-header">
                                            @if(session()->has('upd'))
                                            <h5 class="card-title" style="margin-top: 10px">Update Color</h5>
                                            @else
                                            <h5 class="card-title" style="margin-top: 10px">Create Color</h5>
                                            @endif
                                        </div>
                                        <div class="card-body">
                                            <div class="form-row">
                                                <div class="col-md-3">
                                                    <div class="position-relative form-group">
                                                        <label for="DoorLeafFacing">Door Leaf Facing</label>
                                                        <select required name="DoorLeafFacing" id="DoorLeafFacing" class="form-control"
                                                            onchange="DoorLeafFacingChange(this.value);">
                                                            <option value="">Select any option</option>
                                                            @foreach($doorLeafFacing as $doorLeafFacingIndex => $doorLeafFacingVal)
                                                                @if($doorLeafFacingVal->OptionKey != "Veneer")
                                                                    <option value="{{$doorLeafFacingVal->OptionKey}}"
                                                                        @if(session()->has('upd'))
                                                                            @if(session()->get('upd')->DoorLeafFacing == $doorLeafFacingVal->OptionKey)
                                                                            {{ 'selected' }}
                                                                            @endif
                                                                        @endif
                                                                        @if(old('DoorLeafFacing') == $doorLeafFacingVal->OptionKey)
                                                                            {{ 'selected' }}
                                                                        @endif
                                                                        >{{$doorLeafFacingVal->OptionValue}}
                                                                    </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                                {{--  <div class="col-md-3">
                                                    <div class="position-relative form-group">
                                                        <label for="DoorLeafFacingValue">Door Leaf Facing Value</label>
                                                        <select required name="DoorLeafFacingValue" id="DoorLeafFacingValue"
                                                            class="form-control">
                                                            @if(session()->has('upd'))
                                                                <option value="{{session()->get('upd')->DoorLeafFacingValue}}">
                                                                    {{session()->get('upd')->DoorLeafFacingValue}}
                                                                </option>
                                                            @endif
                                                            @php

                                                            if(isset($option)){
                                                            echo $option;
                                                            }

                                                            @endphp

                                                        </select>
                                                    </div>
                                                </div>  --}}
                                                <div id="" class="col-md-3">
                                                    <div class="position-relative form-group"><label for="Name"
                                                            class="">Color Name</label>
                                                        <input name="ColorName"
                                                            value="@if(session()->has('upd')){{session()->get('upd')->ColorName}}@endif{{old('ColorName')}}"
                                                            required placeholder="Enter Color Name" type="text"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                {{--  <div id="" class="col-md-3">
                                                    <div class="position-relative form-group"><label for="Name"
                                                            class="">English Name</label>
                                                        <input name="EnglishName"
                                                            value="@if(session()->has('upd')){{session()->get('upd')->EnglishName}}@endif{{old('EnglishName')}}"
                                                            required placeholder="Enter English Name " type="text"
                                                            class="form-control">
                                                    </div>
                                                </div>  --}}
                                                <div id="" class="col-md-3">
                                                    <div class="position-relative form-group"><label for="Name"
                                                            class="">RGB</label>
                                                        <input name="RGB" onkeyup="colorChange(this)"
                                                            value="@if(session()->has('upd')){{session()->get('upd')->RGB}}@endif{{old('RGB')}}"
                                                            required placeholder="255,255,255" type="text"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                <div id="" class="col-md-3">
                                                    <div class="position-relative form-group"><label for="Name"
                                                            class="">Hex</label>
                                                        <input name="Hex" onkeyup="colorChange(this, true)"
                                                            value="@if(session()->has('upd')){{session()->get('upd')->Hex}}@endif{{old('Hex')}}"
                                                            required placeholder="#ffffff" type="text" class="form-control">
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="position-relative form-group"><label for="Name"
                                                            class="">Color Cost</label>
                                                        <input name="ColorCost"
                                                            value="@if(session()->has('upd')){{session()->get('upd')->ColorCost}}@endif{{old('ColorCost')}}"
                                                            required placeholder="Enter Color Cost" type="text"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                                @if (Auth::user()->UserType != 2)
                                                <div class="col-md-3">
                                                    <div class="position-relative form-group">
                                                        <label for="First Name" class="">Status</label>
                                                        <select required name="Status" id="Status" class="form-control">
                                                            <option value="1"
                                                                @if(session()->has('upd'))
                                                                    @if(session()->get('upd')->Status == 1)
                                                                    {{ 'selected' }}
                                                                    @endif
                                                                @endif

                                                                >
                                                                Active
                                                            </option>
                                                            <option value="0"
                                                                @if(session()->has('upd'))
                                                                    @if(session()->get('upd')->Status == 0)
                                                                    {{ 'selected' }}
                                                                    @endif
                                                                @endif

                                                                >
                                                                Inactive
                                                            </option>
                                                        </select>
                                                    </div>
                                                </div>
                                                @endif

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-block text-right card-footer">
                                    <button type="submit" class="btn-wide btn btn-success"
                                        >
                                        @if(session()->has('upd'))
                                        Update Now
                                        @else
                                        Create Now
                                        @endif
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
@endsection
@section('js')
    <script>
    $(document).ready(function() {
        //$(".CategoryChangeDynamicFields").hide();
    });

    $(document).on('submit', '.submit', function(e) {
        e.preventDefault();

        $(".page-loader-action").fadeIn();
        var form = $(this);
        var action = form.attr("action");
        var method = form.attr("method");


        var formData = new FormData(this);

        $.ajax({
            type: method,
            url: action,
            data: formData,
            cache: false,
            contentType: false,
            dataType: 'json',
            processData: false,
            success: function(data) {

                $(".page-loader-action").fadeOut();

                if (data.st == '1') {
                    //                    swal("Success!", data.txt, "success");
                    //swal("Success!", data.txt, "success").then(function(value){

                    //                    setTimeout(function(){
                    //
                    //                        if( !== ""){
                    //
                    window.location.assign(data.redirect);
                    //
                    //                        }else{
                    //
                    //                            //window.location.assign('dashboard');
                    //
                    //                        }
                    //
                    //                    },2000);

                    //});


                } else {

                    if (data.hasOwnProperty("field")) {

                        for (var key in data.field) {

                            if (data.field.hasOwnProperty(key)) {
                                //alert(key);
                                //alert(data.field[key]);
                                //                               $("#"+key).addClass("is-invalid");
                                $("input[name='" + key + "']").removeClass("is-valid");
                                $("input[name='" + key + "']").addClass("is-invalid");

                                $("select[name='" + key + "']").removeClass("is-valid");
                                $("select[name='" + key + "']").addClass("is-invalid");

                                //$("#"+key).after('<em id="firstname-error" class="error invalid-feedback">'+data.field[key]+'</em>');
                                //                                       $("#"+key).after('<span role="" class="text-danger">'+data.field[key]+'</span>');

                                $("input[name='" + key + "']").after(
                                    '<span role="" class="text-danger">' + data.field[key] +
                                    '</span>');
                                $("select[name='" + key + "']").after(
                                    '<span role="" class="text-danger">' + data.field[key] +
                                    '</span>');

                            }
                        }

                    } else {

                        swal("Oops!!", data.txt, "error");

                    }

                }
            },
            error: function(data) {
                $(".page-loader-action").fadeOut();

                swal("Oops!!", "Something went wrong. Please try again.", "error");

            }
        });
    });


    function colorChange(selector, hexType = false) {
        //    alert($(selector).val());
        var colorVal = $(selector).val();

        if (colorVal.trim() == "") {
            $(selector).css("background-color", "#fff");
            return false;
        }

        if (hexType) {
            if (!colorVal.includes("#")) {
                alert("Invalid value");
                $(selector).val("");
                return false;
            }

        } else {

            if (colorVal.includes("#")) {
                alert("Invalid value");
                $(selector).val("");
                return false;
            }
        }

        if (colorVal.includes("#")) {
            $(selector).css("background-color", colorVal);
        } else {
            $(selector).css("background-color", "rgb(" + colorVal + ")");

        }

    }
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    function DoorLeafFacingChange(value) {
        let UnderAttribute = value;
        $.ajax({
            type: 'post',
            url: "{{route('ColorDoorLeafFacing')}}",
            data: {'UnderAttribute':UnderAttribute},
            success: function(result) {
                $("#DoorLeafFacingValue").empty().append(result);
            },
            error: function(data) {
                $(".page-loader-action").fadeOut();
                swal("Oops!!", "Something went wrong. Please try again.", "error");
            }
        });

        // previous coder
            // //var where = ["UnderAttribute","Kraft_Paper"];
            // var where = ["UnderAttribute", value];

            // var action = "{{route('options/get')}}";
            // var method = "POST";
            // var formData = new FormData();
            // formData.append('where', where);
            // formData.append('_token', "{{csrf_token()}}");

            // $.ajax({
            //     type: method,
            //     url: action,
            //     data: formData,
            //     cache: false,
            //     contentType: false,
            //     dataType: 'json',
            //     processData: false,
            //     success: function(result) {
            //         //alert(result.data.[0].OptionName);
            //         if (result.st == "1") {
            //             var innerHtml = '';
            //             var innerHtml1 = '';

            //             var data = result.data;
            //             var length = result.data.length;

            //             innerHtml += '<option value="">select any option</option>';

            //             for (var index = 0; index < length; index++) {

            //                 innerHtml += '<option value="' + data[index].OptionKey + '">' + data[index]
            //                     .OptionValue + '</option>'


            //                 //
            //                 //                        if(data[index].OptionSlug == "door_leaf_facing_value"){
            //                 //                            if(data[index].UnderAttribute==doorLeafFacing){
            //                 //
            //                 //                            innerHtml+='<option value="'+data[index].OptionKey+'">'+data[index].OptionValue+'</option>'
            //                 //
            //                 //                            }
            //                 //
            //                 //                        }else{
            //                 //                            if(data[index].UnderAttribute==doorLeafFacing){
            //                 //                                innerHtml1+='<option value="'+data[index].OptionKey+'">'+data[index].OptionValue+'</option>'
            //                 //                            }
            //                 //
            //                 //                        }


            //             }


            //             $("#DoorLeafFacingValue").empty().append(innerHtml);

            //         } else {

            //             swal("Oops!!", data.txt, "error");

            //         }

            //     },
            //     error: function(data) {

            //         $(".page-loader-action").fadeOut();
            //         swal("Oops!!", "Something went wrong. Please try again.", "error");

            //     }
            // });

    }

    // $(document).on('change','#configurableitems',function(){
    //     let pageId = $(this).val();
    //     let DoorLeafFacing = $('#DoorLeafFacing').val();
    //     $('#configurableitems').removeAttr('style');
    //     if(DoorLeafFacing != ''){
    //         $.ajax({
    //             type: 'post',
    //             url: "{{route('ColorDoorLeafFacing')}}",
    //             data: {'pageId':pageId,'UnderAttribute':DoorLeafFacing},
    //             success: function(result) {
    //                 $("#DoorLeafFacingValue").empty().append(result);
    //             },
    //             error: function(data) {
    //                 $(".page-loader-action").fadeOut();
    //                 swal("Oops!!", "Something went wrong. Please try again.", "error");
    //             }
    //         });
    //     }
    // })


    </script>
@endsection
