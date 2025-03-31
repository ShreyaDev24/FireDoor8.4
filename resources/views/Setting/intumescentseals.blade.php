@extends("layouts.Master")
@section("main_section")


<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="tab-content">
            @if(session()->has('error'))
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="fas fa-skull-crossbones"></i> Alert!</h5>
                {{ session()->get('error') }}
            </div>
            @endif
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
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title" style="margin-top: 10px">Intumescent Seals</h5>
                    </div>
                    <form action="{{route('submitintumescentseals')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="updval"
                            value="@if(session()->has('upd')){{ session()->get('upd')->id }}@endif">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="position-relative form-group">
                                    <label for="configurableitems">Configurable Item</label>
                                    <select name="configurableitems" id="configurableitems" class="form-control" required>
                                        <option value="">Configurable Item</option>
                                        @foreach($ConfigurableItems as $tt)
                                            <option value="{{$tt->id}}"
                                                @if(session()->has('upd'))
                                                    @if(session()->get('upd')->configurableitems == $tt->id)
                                                    {{ 'selected' }}
                                                    @endif
                                                @endif
                                                @if(old('configurableitems') == $tt->id)
                                                    {{ 'selected' }}
                                                @endif
                                            >{{$tt->name}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="position-relative form-group">
                                    <label for="firerating">Select Fire Rating</label>
                                    <select name="firerating" id="firerating" class="form-control" required>
                                        @if(session()->has('upd'))
                                            <option value="{{session()->get('upd')->firerating}}">{{session()->get('upd')->firerating}}</option>
                                        @endif
                                        <!-- <option value="">Select Fire Rating</option>
                                        @foreach($firerating as $rr)
                                            <option value="{{$rr->OptionKey}}"
                                                @if(session()->has('upd'))
                                                    @if(session()->get('upd')->firerating == $rr->OptionKey)
                                                    {{ 'selected' }}
                                                    @endif
                                                @endif
                                                @if(old('firerating') == $rr->OptionKey)
                                                    {{ 'selected' }}
                                                @endif
                                            >{{$rr->OptionKey}}</option>
                                        @endforeach -->
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="position-relative form-group">
                                    <label>Tag</label>
                                    <input type="text" name="tag" class="form-control"
                                        value="@if(session()->has('upd')){{session()->get('upd')->tag}}@endif{{old('tag')}}" required
                                        @if(session()->has('upd')) {{'readonly'}} @endif >
                                </div>
                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-3">
                                <div class="position-relative form-group">
                                    <label>Configuration</label>
                                    <input type="text" name="configuration" class="form-control"
                                        value="@if(session()->has('upd')){{session()->get('upd')->configuration}}@endif" required
                                        @if(session()->has('upd')) {{'readonly'}} @endif >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="position-relative form-group">
                                    <label>Intumescent Seals</label>
                                    <input type="text" name="intumescentSeals" class="form-control"
                                        value="@if(session()->has('upd')){{session()->get('upd')->intumescentSeals}}@endif{{old('intumescentSeals')}}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="position-relative form-group">
                                    <label>Brand</label>
                                    <input type="text" name="brand" class="form-control"
                                        value="@if(session()->has('upd')){{session()->get('upd')->brand}}@endif{{old('brand')}}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="position-relative form-group">
                                    <label>Fire Tested</label>
                                    <input type="text" name="firetested" class="form-control"
                                        value="@if(session()->has('upd')){{session()->get('upd')->firetested}}@endif{{old('firetested')}}" required>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="position-relative form-group">
                                    <label>Height Point1</label>
                                    <input type="text" name="Point1height" class="form-control"
                                        value="@if(session()->has('upd')){{session()->get('upd')->Point1height}}@endif">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="position-relative form-group">
                                    <label>Height Point2</label>
                                    <input type="text" name="Point2height" class="form-control"
                                        value="@if(session()->has('upd')){{session()->get('upd')->Point2height}}@endif">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="position-relative form-group">
                                    <label>Width Point1</label>
                                    <input type="text" name="Point1width" class="form-control"
                                        value="@if(session()->has('upd')){{session()->get('upd')->Point1width}}@endif">
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="position-relative form-group">
                                    <label>Width Point2</label>
                                    <input type="text" name="Point2width" class="form-control"
                                        value="@if(session()->has('upd')){{session()->get('upd')->Point2width}}@endif">
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12"><hr></div>
                            <div class="col-md-12">
                                <button type="submit" id="submit" class="btn-wide btn btn-success" style="float: right;">
                                    @if(session()->has('upd')) {{'Update'}} @else {{'Submit'}} @endif
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="col-md-2">
                        <div class="position-relative form-group">
                            <label for="configurableitems">Configurable Item</label>
                            <select name="" id="configurableitems2" class="form-control" required>
                                <option value="">Configurable Item</option>
                                @foreach($ConfigurableItems as $tt)
                                    <option value="{{$tt->id}}"
                                    @if($pageId == $tt->id)
                                        {{ 'selected' }}
                                    @endif
                                    >{{$tt->name}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                                <thead class="text-uppercase table-header-bg">
                                    <tr class="text-white">
                                        <th>S.No.</th>
                                        <th>Fire Rating</th>
                                        {{--  <th>Configuration</th>  --}}
                                        <th>Intumescent Seals</th>
                                        <th>Brand</th>
                                        <th>Fire Tested</th>
                                        <th style="width: 12%;">Height <br>(Point1 - Point2)</th>
                                        <th style="width: 12%;">Width <br>(Point1 - Point2)</th>
                                        <th>Manage</th>
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
@section("script_section")
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
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
        $(document).on('change', '#configurableitems2', function() {
            let pageId = $(this).val();
            let url = "{{url('setting/intumescentseals')}}"+'/'+pageId;
            window.location.href =url;
        })
    </script>
@endsection
