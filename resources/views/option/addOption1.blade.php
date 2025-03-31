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
        <form method="post" action="{{route('options/store')}}" enctype="multipart/form-data">
            <input type="hidden" id="optionType" value="{{ $optionType }}">
            <div class="tab-content">
                <div class="main-card mb-3 card">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    <input type="hidden" name="update" value="@if(!empty($upd->id)){{$upd->id}}@endif">
                    <div class="card-header">
                        <h5 class="card-title" style="margin-top: 10px">
                            Daynamic Fields Option
                        </h5>
                    </div>

                    <div class="card-body">
                        <div class="tab-content">
                            @if($id == 0)
                                <div class="card-body">
                                    <div class="form-row">
                                        <div class="row">
                                            <div class="col-md-2">
                                                <div class="position-relative form-group">
                                                    <label for="configurableitems">Configurable Item</label>
                                                    <select name="configurableitems" id="configurableitems" class="form-control" required>
                                                        <option value="">Configurable Item</option>
                                                        @foreach($ConfigurableItems as $tt)
                                                            <option value="{{$tt->id}}">{{$tt->name}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="position-relative form-group">
                                                    <label for="firerating">Select Fire Rating</label>
                                                    <select name="firerating" id="firerating" class="form-control">
                                                        <!-- <option value="">Select Fire Rating</option>
                                                        @foreach($firerating as $rr)
                                                            <option value="{{$rr->OptionKey}}"
                                                                @if(session()->has('success'))
                                                                    @if(session()->get('firerating') == $rr->OptionKey)
                                                                    {{ 'selected' }}
                                                                    @endif
                                                                @endif
                                                            >{{$rr->OptionKey}}</option>
                                                        @endforeach -->
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-2" id="attribute">
                                                <div class="position-relative form-group">
                                                    <label for="ParentAttr">
                                                        is attribute parent
                                                    </label>
                                                    <select name="ParentAttr" class="form-control" id="ParentAttr" required>
                                                        <option value="">Select</option>
                                                        <option value="yes" @if(session()->has('success'))
                                                            @if(session()->get('ParentAttr') == 'yes')
                                                            {{ 'selected' }}
                                                            @endif
                                                            @endif
                                                            >Yes</option>
                                                        <option value="no" @if(session()->has('success'))
                                                            @if(session()->get('ParentAttr') == 'no')
                                                            {{ 'selected' }}
                                                            @endif
                                                            @endif
                                                            >No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                @if(session()->has('success'))
                                                    @if(session()->get('ParentAttr') == 'no')
                                                        @php $classhide = ''; @endphp
                                                    @elseif(session()->get('ParentAttr') == 'yes')
                                                        @php $classhide = 'hideShow'; @endphp
                                                    @endif
                                                @else
                                                    @php $classhide = 'hideShow'; @endphp
                                                @endif
                                                <div class="{{ $classhide }}">
                                                    <div class="position-relative form-group">
                                                        <label for="optionName">
                                                            Option Name
                                                        </label>
                                                        <select name="selectOptionName" class="form-control" id="OptionName">
                                                            <option value="">Select Option Name</option>
                                                            @foreach($option as $row)
                                                            <option value="{{$row->OptionSlug}}" @if(session()->
                                                                has('success'))
                                                                @if(session()->get('selectOptionName') == $row->OptionSlug)
                                                                {{ 'selected' }}
                                                                @endif
                                                                @endif
                                                                >{{$row->OptionName}}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="{{ $classhide }}">
                                                    <div class="position-relative form-group">
                                                        <label for="NewOptionValue">Option Value</label>
                                                        <select name="optionID" id="NewOptionValue" class="form-control">
                                                            @if(session()->has('success'))
                                                                @if(session()->get('ParentAttr') == 'no')
                                                                    {!! session()->get('optionID') !!}
                                                                @endif
                                                            @endif
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="">Option Name</label>
                                                    <div style="display: flex;">
                                                        <select name="OptionName2" class="form-control" id="OptionName2"
                                                            required>
                                                            <option value="">Select Option Name</option>
                                                            <option value="addOption" @if(session()->has('success'))
                                                                @if(session()->get('OptionName2') == 'addOption')
                                                                {{ 'selected' }}
                                                                @endif
                                                                @endif
                                                                >Add New Option Name</option>
                                                            @foreach($option as $row)
                                                                @php
                                                                $nameslug = $row->OptionName.','.$row->OptionSlug;
                                                                @endphp
                                                                <option value="{{$nameslug}}" @if(session()->has('success'))
                                                                    @if(session()->get('OptionName2') == $nameslug)
                                                                    {{ 'selected' }}
                                                                    @endif
                                                                    @endif
                                                                    >{{$row->OptionName}}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        <input type="text" name="InputOptName"
                                                            value="@if(session()->has('success')){{ session()->get('InputOptName') }}@endif"
                                                            placeholder="Enter Option Name" class="form-control optName"
                                                            required readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="optionSlug">Option slug</label>
                                                    <input readonly name="optionSlug" id="optionSlug"
                                                        value="@if(isset($editdata->id)){{$editdata->OptionSlug}}@else{{old('optionSlug')}}@endif @if(session()->has('success')){{session()->get('optionSlug')}}@endif"
                                                        required type="text" class="form-control optSlug">
                                                </div>
                                            </div>
                                            <div class="col-md-3 abc1">
                                                <div class="position-relative form-group">
                                                    <label for="OptionKey">Option key</label>
                                                    <input name="OptionKey[]" id="OptionKey"
                                                        value="@if(isset($editdata->id)) {{$editdata->OptionKey}} @else{{old('OptionKey')}}@endif"
                                                        required placeholder="Enter option key " type="text"
                                                        class="form-control OptionKey">
                                                    <span class="field-instruction">(Option key should not content any
                                                        space )</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="position-relative form-group">
                                                    <label>Option Value</label>
                                                    <input name="OptionValue[]"
                                                        value="@if(isset($editdata->id)){{$editdata->OptionValue}}@else{{old('OptionValue')}}@endif"
                                                        required placeholder="Enter option value " type="text"
                                                        class="form-control">
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="position-relative form-group">
                                                    <label for="" class="">Select Image</label>
                                                    <input type="file" name="image[]" class="form-control"
                                                        placeholder="Enter option value">
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <div class="position-relative form-group">
                                                    <label for="">Cost</label>
                                                    <input type="text" name="OptionCost[]" class="form-control"
                                                        placeholder="Enter Cost">
                                                </div>
                                            </div>
                                            <div class="col-md-1">
                                                <button type="button" class="btn btn-success addfields">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="appField"></span>
                                    <div class="row">
                                        <div class="col-md-12"><hr></div>
                                        <div class="col-md-12">
                                            <button type="submit" id="submit" class="btn-wide btn btn-success"
                                                style="float: right;">
                                                @if(isset($editdata->id))
                                                Update
                                                @else
                                                Submit
                                                @endif
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label>Configurable Items</label>
                                            <input type="text" class="form-control"
                                                value="@if(!empty($ci->name)){{$ci->name}}@endif"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label>Fire Rating</label>
                                            <input type="text" class="form-control"
                                                value="@if(!empty($upd->firerating)){{$upd->firerating}}@endif"
                                                readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label>Option Name</label>
                                            <input type="text" class="form-control"
                                                value="@if(!empty($upd->OptionName)){{$upd->OptionName}}@endif" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label>Attribute</label>
                                            <input type="text" class="form-control"
                                                value="@if(!empty($upd->UnderAttribute)){{$upd->UnderAttribute}}@endif" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label>Option Value</label>
                                            <input type="text" name="OptionValue" class="form-control"
                                                value="@if(!empty($upd->OptionValue)){{$upd->OptionValue}}@endif"
                                                required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label>Select Image</label>
                                            <input type="file" name="image" class="form-control"
                                                placeholder="Enter option value">
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="position-relative form-group">
                                            @if(!empty($upd->file))
                                            <img src="{{url('/')}}/uploads/Options/{{$upd->file}}" alt="image" style="width:50px;height:50px">
                                            @else
                                            {{'No Image'}}
                                            @endif
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="position-relative form-group">
                                            <label for="">Cost</label>
                                            <input type="text" name="OptionCost" class="form-control"
                                                placeholder="Enter Cost" value="@if(!empty($upd->OptionCost)){{$upd->OptionCost}}@endif">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12"><hr></div>
                                    <div class="col-md-12">
                                        <button type="submit" class="btn-wide btn btn-success"
                                            style="float: right;">Update</button>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('js')
<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });
    $(document).on('click', '.addfields', function(e) {
        e.preventDefault()
        var fieldHTML = `
            <div class="form-row">
                <div class="col-md-3">
                    <div class="position-relative form-group">
                        <label>Option key</label>
                        <input name="OptionKey[]"
                            value="@if(isset($editdata->id)) {{$editdata->OptionKey}} @else{{old('OptionKey')}}@endif"
                            placeholder="Enter option key " type="text"
                            class="form-control OptionKey">
                        <span class="field-instruction">(Option key should not content any
                            space )</span>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="position-relative form-group">
                        <label>Option Value</label>
                        <input name="OptionValue[]"
                            value="@if(isset($editdata->id)){{$editdata->OptionValue}}@else{{old('OptionValue')}}@endif"
                            placeholder="Enter option value " type="text"
                            class="form-control">
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="position-relative form-group">
                        <label for="" class="">Select Image</label>
                        <input type="file" name="image[]" class="form-control" placeholder="Enter option value">
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="position-relative form-group">
                        <label for="">Cost</label>
                        <input type="text" name="OptionCost[]" class="form-control"
                            placeholder="Enter Cost">
                    </div>
                </div>
                <div class="col-md-1" style="padding-left: 30px;">
                    <button type="button" class="btn btn-danger removefields">
                        <i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
        `;
        $('.appField').append(fieldHTML);
    })


    $(document).on('click', '.removefields', function(e) {
        $(this).parent('div').parent('div').remove();
    })
    $(document).ready(function() {
        var optionType = $('#optionType').val();
        if(optionType == 'Accoustics' || optionType == 'door_leaf_facing_value'){
            $('#OptionName').attr('required',true);
            $('#NewOptionValue').attr('required',true);
            $('#ParentAttr option[value="no"]').attr("selected",true);
            $("#attribute").hide();
        }else{
            $(".hideShow").hide();
            $("#ParentAttr").change(function() {
                if ($(this).val() == 'no') {
                    $('#OptionName').attr('required',true);
                    $(".hideShow").show();
                } else {
                    $('#OptionName').attr('required',false);
                    $(".hideShow").hide();
                }
            })
        }
    })
    $(document).ready(function() {
        $("#OptionName").change(function(e) {
            e.preventDefault();
            var id = $(this).val();
            let pageId = $('#configurableitems').val();
            $.ajax({
                url: "{{route('get-option-value')}}",
                type: "POST",
                data: {
                    id: id,pageId:pageId
                },
                success: function(data) {
                    $("#NewOptionValue").empty().append(data);
                }
            })
        })
        $("#OptionName2").change(function(e) {
            e.preventDefault();
            let str = $(this).val();
            let arr = str.split(',');
            if (arr[0] == 'addOption') {
                $('.optName').val('');
                $('#optionSlug').val('');
                $('.optName').removeAttr('readonly');
            } else {
                $('.optName').attr('readonly', true);
                $('.optName').val(arr[0]);
                $("#optionSlug").val(arr[1]);
            }
        })
        $(document).on('change','#configurableitems',function(){
            let pageId = $(this).val();
            $.ajax({
                url: "{{route('filterConfiguretype')}}",
                type: "POST",
                data: {pageId: pageId},
                success: function(data) {
                    $("#firerating").empty().append(data);
                }
            })
        })

        $("#OptionName2 option[value='Glass Integrity,Glass_Integrity']").remove();
        $("#OptionName2 option[value='addOption']").remove();
        $("#OptionName option[value='Intumescent_Seal_Color']").remove();
        $("#OptionName option[value='leaf1_glazing_systems']").remove();
        $("#OptionName option[value='leaf1_glass_type']").remove();
        $('#OptionName2').change(function(){
            var OptionName2 = $('#OptionName2').val();
            if(OptionName2 == 'Intumescent Seal Color,Intumescent_Seal_Color'){
                $('#OptionName').attr('disabled',true);
                $('#NewOptionValue').attr('disabled',true);
                $('#OptionName').val('');
                $('#NewOptionValue').val('');
            }else if(OptionName2 == 'Accoustics,Accoustics'){
                $('#OptionName').attr('disabled',false);
                $('#NewOptionValue').attr('disabled',false);
            }else if(OptionName2 == 'Door Leaf Facing Value,door_leaf_facing_value'){
                $('#OptionName').attr('disabled',false);
                $('#NewOptionValue').attr('disabled',false);
            }else if(OptionName2 == 'Leaf 1 Glass Type,leaf1_glass_type'){
                $('#OptionName').attr('disabled',false);
                $('#NewOptionValue').attr('disabled',false);
            }else if(OptionName2 == 'Leaf 1 Glazing System,leaf1_glazing_systems'){
                $('#OptionName').attr('disabled',true);
                $('#NewOptionValue').attr('disabled',true);
                $('#OptionName').val('');
                $('#NewOptionValue').val('');
            }
        })

    })
</script>
@endsection






















