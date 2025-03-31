@php
//    dd(session()->get('upd')->toArray());
@endphp
@extends("layouts.Master")
@section('main_section')


    <div class="app-main__outer">
        <div class="app-main__inner">
            <div class="tab-content">
                @if (session()->has('success'))
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
                    <div class="card-body">
                        <div class="card-header">
                            <h5 class="card-title" style="margin-top: 10px">Timber Species</h5>
                        </div>
                        <form enctype="multipart/form-data" action="{{ route('sublippingSpecies') }}" method="post">
                            {{ csrf_field() }}
                            <input type="hidden" name="updval" value="@if (session()->has('upd')){{ session()->get('upd')->id }}@endif">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label>Species Name<span class="text-danger">*</span></label>
                                        <input type="text" name="SpeciesName" class="form-control"
                                            value="@if (session()->has('upd')){{ session()->get('upd')->SpeciesName }}@endif{{ old('SpeciesName') }}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="position-relative form-group">
                                        <label>Minimum Value<span class="text-danger">*</span></label>
                                        <input type="number" min="0" name="MinValue" class="form-control"
                                            value="@if (session()->has('upd')){{ session()->get('upd')->MinValue }}@endif{{ old('MinValue') }}" onkeydown="if(event.key==='.'){event.preventDefault();}" required>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="position-relative form-group">
                                        <label>Maximum Value<span class="text-danger">*</span></label>
                                        <input type="number" min="0" name="MaxValues" class="form-control"
                                            value="@if (session()->has('upd')){{ session()->get('upd')->MaxValues }}@endif{{ old('MaxValues') }}" onkeydown="if(event.key==='.'){event.preventDefault();}" required>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="position-relative form-group">
                                        <label>File</label>
                                        <input type="file" name="file" class="form-control">
                                    </div>
                                </div>

                                <div class="col-md-2">
                                    <div class="position-relative form-group">
                                        @if (session()->has('upd'))
                                            <img src="{{ url('/') }}/uploads/Options/{{ session()->get('upd')->file }}"
                                                style="width:50px;height:50px;">
                                        @endif
                                    </div>
                                </div>

                            </div>

                            @if (@session()->get('upd')->lipping_species_items[0]->price)
                                @foreach (session()->get('upd')->lipping_species_items as $key => $item)

                                <div class="row">

                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label>Thickness</label>
                                            <input readonly step="0.5" type="text" name="Thickness[]" class="form-control"
                                                value="{{$item->thickness}}">
                                        </div>
                                    </div>


                                    <div class="col-md-6">
                                        <div class="position-relative form-group">
                                            <label>Price</label>
                                            <input min="0" type="number" name="Price[]" class="form-control"
                                                value="{{$item->price}}">
                                        </div>
                                    </div>

                                    {{-- <div class="col-md-6">
                                        <div class="position-relative form-group mt-4">
                                            @if ($key == 0)
                                                <button type="button" class="btn btn-success addfields  mt-2">
                                                    <i class="fa fa-plus"></i>
                                                </button>
                                            @else
                                                <button type="button" class="btn btn-danger removefields  mt-2">
                                                    <i class="fa fa-minus"></i>
                                                </button>
                                            @endif

                                        </div>
                                    </div> --}}


                                </div>

                                @endforeach

                            @else


                                @for($i = 1; $i <= 4; $i = $i + 0.5)

                                    <div class="row">

                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Thickness</label>
                                                <input readonly type="text" name="Thickness[]" class="form-control"
                                                    value="{{$i}}">
                                            </div>
                                        </div>


                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Price</label>
                                                <input min="0" type="number" name="Price[]" class="form-control"
                                                    value="0">
                                            </div>
                                        </div>


                                    </div>

                                @endfor




                                {{-- <div class="col-md-2">
                                    <div class="position-relative form-group">
                                        <label>Price</label>
                                        <input min="0" type="number" name="Price[]" class="form-control"
                                            value="@if (session()->has('upd')){{ session()->get('upd')->Price }}@endif{{ old('Price') }}" required>
                                    </div>
                                </div>

                                <div class="col-md-1">
                                    <div class="position-relative form-group mt-4">
                                        <button type="button" class="btn btn-success addfields  mt-2">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                </div> --}}
                            @endif

                            <span class="row appField">
                            </span>

                            <div class="row">
                                <div class="col-md-12">
                                    <hr>
                                </div>
                                <div class="col-md-12">
                                    <button type="submit" id="submit" class="btn-wide btn btn-success"
                                        style="float: right;">
                                        @if (session()->has('upd')) {{ 'Update' }} @else {{ 'Submit' }} @endif
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="main-card mb-3 card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <table style="width: 100%;" id="example"
                                    class="table table-hover table-striped table-bordered">
                                    <thead class="text-uppercase table-header-bg">
                                        <tr class="text-white">
                                            <th>S.No.</th>
                                            <th>Species Name</th>
                                            <th>Min Value</th>
                                            <th>Max Value</th>
                                            {{-- <th>Thickness</th>
                                            <th>Prices</th> --}}
                                            <th>File</th>
                                            <th>Manage
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        {!! $tbl !!}
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
            var fieldHTML = `<div class="row">
                                <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label>Price</label>
                                    <input min="0" type="number" name="Price[]" class="form-control"
                                        value="" required>
                                </div>
                            </div>
                                <div class="col-md-6">
                                <div class="position-relative form-group mt-4">
                                    <button type="button" class="btn btn-danger removefields  mt-2">
                                        <i class="fa fa-minus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>`;
            $('.appField').append(fieldHTML);
        })


        $(document).on('click', '.removefields', function(e) {
            $(this).parent('div').parent('div').parent('div').remove();
        })
        $(document).ready(function() {
            $(".hideShow").hide();
            $("#ParentAttr").change(function() {
                if ($(this).val() == 'no') {
                    $('#OptionName').attr('required', true);
                    $(".hideShow").show();
                } else {
                    $('#OptionName').attr('required', false);
                    $(".hideShow").hide();
                }
            })
        })
        $(document).ready(function() {
            $("#OptionName").change(function(e) {
                e.preventDefault();
                var id = $(this).val();
                let pageId = $('#configurableitems').val();
                $.ajax({
                    url: "{{ route('get-option-value') }}",
                    type: "POST",
                    data: {
                        id: id,
                        pageId: pageId
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
            $(document).on('change', '#configurableitems', function() {
                let pageId = $(this).val();
                $.ajax({
                    url: "{{ route('filterConfiguretype') }}",
                    type: "POST",
                    data: {
                        pageId: pageId
                    },
                    success: function(data) {
                        $("#firerating").empty().append(data);
                    }
                })
            })
        })
    </script>
@endsection
