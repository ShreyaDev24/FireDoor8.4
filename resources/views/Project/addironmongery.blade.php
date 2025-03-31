@extends("layouts.Master")

@section("main_section")
<style>
.input-icons i {
    position: absolute;
    left: 0;
}
.input-icons{
    display: flex;
}
.input-group-text{
    text-align: left;
    padding-left: 18px;
}
.qty{
    width: 80px;
}
.text_style{
    font-size: 12px;
    color: #f00;
    margin-top: -14px;
}
input[type=number]::-webkit-inner-spin-button,
input[type=number]::-webkit-outer-spin-button {
  -webkit-appearance: none;
  margin: 0;
}
.customcross{
    font-size: 15px;
    background: #ffffff;
    border: 1px solid #ececec;
    color: red;
    font-weight: bold;
}
.product_holder {
    position: relative;
    margin-bottom: 30px;
    border: 1px solid #f1f1f1;
    padding: 10px 10px;
    height: 330px;
}
.product_img {
    width: 100%;
    height: 75%;
    overflow: hidden;
}
.product_img img {
    width: 100%;
    display: block;
    height: auto;
}

.product_name {
    color: #454545;
    text-transform: uppercase;
    font-weight: 600;
    padding: 6px 0px 5px;
    display: block;
}

.product_name:hover{
    text-decoration: none;
    color: #000;
}

.product_face b {
    font-size: 12px;
    color: #505050;
    padding: 0px 5px;
    border-right: 1px solid #bfbfbf;
}

.product_face b:last-child{
    border-right: 0px !important;
}

.dimension {
    font-size: 15px;
    color: #8c8c8c;
}


.product_edit {
    background-image: linear-gradient(#ff5c50, #cc2f24);
    border: 0;
    color: #fff;
    font-size: 13px;
    padding: 4px 8px;
    border-radius: 5px;
    font-weight: 500;
    position: absolute;
    bottom: 8px;
    right: 10px;
    display: none;
}

.product_holder:hover .product_edit{
    display: block;
}
</style>
<script>
    function Tooltip(tooltipValue) {
        let TooltipCode2 =
            `<i class="fa fa-info-circle field_info tooltip" aria-hidden="true">
                <span class="tooltiptext info_tooltip">` + tooltipValue + `</span>
            </i>`;
        return TooltipCode2;
    }
</script>
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
        <form method="post" action="{{route('subaddironmongery')}}" enctype="multipart/form-data">
            {{csrf_field()}}
            <input type="hidden" name="addironmongeryID" value="@if(session()->has('item')){{session()->get('item')->id}}@endif">
            <input type="hidden" name="ProjectId" id="ProjectId" value="@if(!empty($pid)){{$pid}}@endif">
            <div class="tab-content">
                <!-- Fitting Hardware/Ironmongery -->
                <div class="main-card mb-3 custom_card">
                    <div class="">
                        <div class="tab-content">
                            <div class="card-header">
                                <h5 class="card-title" style="margin-top: 10px">Fitting Hardware/Ironmongery </h5>
                                <input type="hidden" id="ironIronmongerydata">
                                <input type="hidden" id="currency">
                            </div>
                            <div class="">
                                <div class="form-row">
                                    <div class="col-md-4">
                                        <label for="Setname">Set Name<span class="text-danger">*</span></label>
                                        <div class="input-icons">
                                            <input type="text" name="Setname" id="Setname" class="form-control" value="@if(session()->has('item')){{session()->get('item')->Setname}}@else{{old('Setname')}}@endif" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Total Price</label>
                                        <div class="input-icons">
                                            <input type="text" name="totalprice" id="totalprice" value="@if(session()->has('item')){{session()->get('item')->totalprice}}@else{{0}}@endif" class="form-control" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <label for="">Discounted Price <span class="text-danger">*</span></label>
                                        <div class="input-icons">
                                            <input type="text" min="0" name="discountprice" value="@if(session()->has('item')){{session()->get('item')->discountprice}}@endif" class="form-control" required oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1'); if(!/^\d*\.?\d{0,2}$/.test(this.value)) { this.value = this.value.slice(0, -1); }">

                                        </div>
                                    </div>
                                    <div class="col-md-12"></div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="HingesKey">Hinges
                                                @if(!empty($tooltip->HingesKey))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->HingesKey}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_Hinges" onClick="IronMongery('Hinges','Hinges')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="hingesValue" id="HingesValue" value="@if(session()->has('item')){{session()->get('item')->Hinges}}@else{{old('hingesValue')}}@endif">
                                                    <input type="hidden" name="hingesprice" class="price" value="@if(session()->has('hingePrice')){{session()->get('hingePrice')}}@else{{old('hingesprice')}}@endif">

                                                    <input type="text" name="hingesKey" id="HingesKey" class="form-control bg-white"  value="@if(session()->has('hinge')){{session()->get('hinge')}}@else{{old('hingesKey')}}@endif" readonly>

                                                </div>


                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" id="hingesQty" name="hingesQty" value="@if(session()->has('item')){{session()->get('item')->hingesQty}}@else{{old('hingesQty')}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->hingesQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>

                                            </div>
                                        </div>
                                        <div class="text_style" id="msg"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="FloorSpringKey">Floor Spring
                                                @if(!empty($tooltip->FloorSpringKey))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->FloorSpringKey}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_floorSpring" onClick="IronMongery('FloorSpring','Floor Spring')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="floorSpringValue" id="FloorSpringValue" value="@if(session()->has('item')){{session()->get('item')->FloorSpring}}@else{{old('floorSpringValue')}}@endif">
                                                    <input type="hidden" name="FloorSpringPrice" class="price" value="@if(session()->has('FloorSpringPrice')){{session()->get('FloorSpringPrice')}}@else{{old('FloorSpringPrice')}}@endif">

                                                    <input type="text" name="floorSpringKey" id="FloorSpringKey" class="form-control bg-white" value="@if(session()->has('FloorSpring')){{session()->get('FloorSpring')}}@else{{old('floorSpringKey')}}@endif" readonly>
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="floorSpringQty" id="floorSpringQty" value="@if(session()->has('item')){{session()->get('item')->floorSpringQty}}@else{{old('floorSpringQty')}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->floorSpringQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                            <div class="text_style" id="msg2"></div>
                                        </div>

                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="lockesAndLatchesKey">Locks And Latches
                                                @if(!empty($tooltip->lockesAndLatchesKey))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->lockesAndLatchesKey}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_lockesAndLatches" onClick="IronMongery('LocksandLatches','Locks And Latches')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="lockesAndLatchesValue" id="LocksandLatchesValue" value="@if(session()->has('item')){{session()->get('item')->LocksAndLatches}}@else{{old('lockesAndLatchesValue')}}@endif">
                                                    <input type="hidden" name="LocksandLatchesPrice" class="price" value="@if(session()->has('LocksandLatchesPrice')){{session()->get('LocksandLatchesPrice')}}@else{{old('LocksandLatchesPrice')}}@endif">

                                                    <input type="text" name="lockesAndLatchesKey" id="LocksandLatchesKey" class="form-control bg-white" value="@if(session()->has('LocksAndLatches')){{session()->get('LocksAndLatches')}}@else{{old('lockesAndLatchesKey')}}@endif" readonly>
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="lockesAndLatchesQty" id="lockesAndLatchesQty" value="@if(session()->has('item')){{session()->get('item')->lockesAndLatchesQty}}@else{{old('lockesAndLatchesQty')}}@endif" class="form-control qty" placeholder="QTY"  @if(session()->has('item') && session()->get('item')->lockesAndLatchesQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg3"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="flushBoltsKey" class="">Flush Bolts
                                                @if(!empty($tooltip->flushBoltsKey))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->flushBoltsKey}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_FlushBolts" onClick="IronMongery('FlushBolts','Flush Bolts')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="flushBoltsValue" id="FlushBoltsValue" value="@if(session()->has('item')){{session()->get('item')->FlushBolts}}@else{{old('flushBoltsValue')}}@endif">
                                                    <input type="hidden" name="FlushBoltsPrice" class="price" value="@if(session()->has('FlushBoltsPrice')){{session()->get('FlushBoltsPrice')}}@else{{old('FlushBoltsPrice')}}@endif">


                                                    <input type="text" name="flushBoltsKey" id="FlushBoltsKey" class="form-control bg-white" value="@if(session()->has('FlushBolts')){{session()->get('FlushBolts')}}@else{{old('flushBoltsKey')}}@endif" readonly>
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="flushBoltsQty" id="flushBoltsQty" value="@if(session()->has('item')){{session()->get('item')->flushBoltsQty}}@else{{old('flushBoltsQty')}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->flushBoltsQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg4"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="concealedOverheadCloserKey">Concealed Overhead
                                                Closer
                                                @if(!empty($tooltip->concealedOverheadCloserKey))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->concealedOverheadCloserKey}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_concealedOverheadCloser" onClick="IronMongery('ConcealedOverheadCloser','Concealed Overhead Closer')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="concealedOverheadCloserValue"
                                                    id="ConcealedOverheadCloserValue" value="@if(session()->has('item')){{session()->get('item')->ConcealedOverheadCloser}}@else{{old('concealedOverheadCloserValue')}}@endif">
                                                    <input type="hidden" name="ConcealedOverheadCloserPrice" class="price" value="@if(session()->has('ConcealedOverheadCloserPrice')){{session()->get('ConcealedOverheadCloserPrice')}}@else{{old('ConcealedOverheadCloserPrice')}}@endif">


                                                    <input type="text" name="concealedOverheadCloserKey" id="ConcealedOverheadCloserKey" class="form-control bg-white" value="@if(session()->has('ConcealedOverheadCloser')){{session()->get('ConcealedOverheadCloser')}}@else{{old('concealedOverheadCloserKey')}}@endif" readonly>
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="concealedOverheadCloserQty" id="concealedOverheadCloserQty" value="@if(session()->has('item')){{session()->get('item')->concealedOverheadCloserQty}}@else{{old('concealedOverheadCloserQty')}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->concealedOverheadCloserQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg5"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="pullHandlesKey" class="">Pull Handles
                                                @if(!empty($tooltip->pullHandlesKey))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->pullHandlesKey}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_PullHandles" onClick="IronMongery('PullHandles','Pull Handles')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="pullHandlesValue" id="PullHandlesValue" value="@if(session()->has('item')){{session()->get('item')->PullHandles}}@else{{old('pullHandlesValue')}}@endif">
                                                    <input type="hidden" name="PullHandlesPrice" class="price" value="@if(session()->has('PullHandlesPrice')){{session()->get('PullHandlesPrice')}}@else{{old('PullHandlesPrice')}}@endif">


                                                    <input type="text" name="pullHandlesKey" id="PullHandlesKey" class="form-control bg-white" value="@if(session()->has('PullHandles')){{session()->get('PullHandles')}}@else{{old('pullHandlesKey')}}@endif" readonly>
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="pullHandlesQty" id="pullHandlesQty" value="@if(session()->has('item')){{session()->get('item')->pullHandlesQty}}@else{{old('pullHandlesQty')}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->pullHandlesQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg6"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="pushHandlesValue" class="">Push Plates
                                                @if(!empty($tooltip->pushHandlesValue))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->pushHandlesValue}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_PushHandles" onClick="IronMongery('PushHandles','Push Handles')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="pushHandlesValue" id="PushHandlesValue" value="@if(session()->has('item')){{session()->get('item')->PushHandles}}@else{{old('pushHandlesValue')}}@endif">
                                                    <input type="hidden" name="PushHandlesPrice" class="price" value="@if(session()->has('PushHandlesPrice')){{session()->get('PushHandlesPrice')}}@else{{old('PushHandlesPrice')}}@endif">


                                                    <input type="text" readonly name="pushHandlesKey" id="PushHandlesKey"
                                                    class="form-control bg-white" value="@if(session()->has('PushHandles')){{session()->get('PushHandles')}}@else{{old('pushHandlesKey')}}@endif">
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="pushHandlesQty" id="pushHandlesQty" value="@if(session()->has('item')){{session()->get('item')->pushHandlesQty}}@else{{old('pushHandlesQty')}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->pushHandlesQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg7"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="kickPlates" class="">Kick Plates
                                                @if(!empty($tooltip->kickPlates))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->kickPlates}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_KickPlates"  onClick="IronMongery('KickPlates','Kick Plates')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="kickPlatesValue" id="KickPlatesValue" value="@if(session()->has('item')){{session()->get('item')->KickPlates}}@else{{old('kickPlatesValue')}}@endif">
                                                    <input type="hidden" name="KickPlatesPrice" class="price" value="@if(session()->has('KickPlatesPrice')){{session()->get('KickPlatesPrice')}}@else{{old('KickPlatesPrice')}}@endif">


                                                    <input type="text" readonly name="kickPlatesKey" id="KickPlatesKey"
                                                    class="form-control bg-white" value="@if(session()->has('KickPlates')){{session()->get('KickPlates')}}@else{{old('kickPlatesKey')}}@endif">
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="kickPlatesQty" id="kickPlatesQty" value="@if(session()->has('item')){{session()->get('item')->kickPlatesQty}}@else{{old('kickPlatesQty')}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->kickPlatesQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg8"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorSelectorsKey">Door Selectors
                                                @if(!empty($tooltip->doorSelectorsKey))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->doorSelectorsKey}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_doorSelectors" onClick="IronMongery('DoorSelectors','Door Selectors')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="doorSelectorsValue" id="DoorSelectorsValue" value="@if(session()->has('item')){{session()->get('item')->DoorSelectors}}@else{{old('doorSelectorsValue')}}@endif">
                                                    <input type="hidden" name="DoorSelectorsPrice" class="price" value="@if(session()->has('DoorSelectorsPrice')){{session()->get('DoorSelectorsPrice')}}@else{{old('DoorSelectorsPrice')}}@endif">

                                                    <input type="text" readonly name="doorSelectorsKey"
                                                    id="DoorSelectorsKey" class="form-control bg-white" value="@if(session()->has('DoorSelectors')){{session()->get('DoorSelectors')}}@else{{old('doorSelectorsKey')}}@endif">
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="doorSelectorsQty" id="doorSelectorsQty" value="@if(session()->has('item')){{session()->get('item')->doorSelectorsQty}}@else{{old('doorSelectorsQty')}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->doorSelectorsQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg9"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="panicHardwareKey">Panic Hardware
                                                @if(!empty($tooltip->panicHardwareKey))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->panicHardwareKey}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_panicHardware" onClick="IronMongery('PanicHardware','Panic Hardware')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="panicHardwareValue" id="PanicHardwareValue" value="@if(session()->has('item')){{session()->get('item')->PanicHardware}}@else{{old('panicHardwareValue')}}@endif">
                                                    <input type="hidden" name="PanicHardwarePrice" class="price" value="@if(session()->has('PanicHardwarePrice')){{session()->get('PanicHardwarePrice')}}@else{{old('PanicHardwarePrice')}}@endif">


                                                    <input type="text" readonly name="panicHardwareKey"
                                                    id="PanicHardwareKey" class="form-control bg-white" value="@if(session()->has('PanicHardware')){{session()->get('PanicHardware')}}@else{{old('panicHardwareKey')}}@endif">
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="panicHardwareQty" id="panicHardwareQty" value="@if(session()->has('item')){{session()->get('item')->panicHardwareQty}}@else{{old('panicHardwareQty')}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->panicHardwareQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg10"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorSecurityViewerKey">Door security viewer
                                                @if(!empty($tooltip->doorSecurityViewerKey))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->doorSecurityViewerKey}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_doorSecurityViewer" onClick="IronMongery('Doorsecurityviewer','Door security viewer')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="doorSecurityViewerValue"
                                                    id="DoorsecurityviewerValue" value="@if(session()->has('item')){{session()->get('item')->Doorsecurityviewer}}@else{{old('doorSecurityViewerValue')}}@endif">
                                                    <input type="hidden" name="DoorsecurityviewerPrice" class="price" value="@if(session()->has('DoorsecurityviewerPrice')){{session()->get('DoorsecurityviewerPrice')}}@else{{old('DoorsecurityviewerPrice')}}@endif">



                                                    <input type="text" readonly name="doorSecurityViewerKey"
                                                    id="DoorsecurityviewerKey" class="form-control bg-white" value="@if(session()->has('Doorsecurityviewer')){{session()->get('Doorsecurityviewer')}}@else{{old('doorSecurityViewerKey')}}@endif">
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="doorSecurityViewerQty" id="doorSecurityViewerQty" value="@if(session()->has('item')){{session()->get('item')->doorSecurityViewerQty}}@else{{old('doorSecurityViewerQty')}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->doorSecurityViewerQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg11"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="morticeddropdownsealsKey" class="">Morticed drop down seals
                                                @if(!empty($tooltip->morticeddropdownsealsKey))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->morticeddropdownsealsKey}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_morticeddropdownseals" onClick="IronMongery('Morticeddropdownseals','Morticed drop down seals')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="morticeddropdownsealsValue"
                                                    id="MorticeddropdownsealsValue" value="@if(session()->has('item')){{session()->get('item')->Morticeddropdownseals}}@else{{old('morticeddropdownsealsValue')}}@endif">
                                                    <input type="hidden" name="MorticeddropdownsealsPrice" class="price" value="@if(session()->has('MorticeddropdownsealsPrice')){{session()->get('MorticeddropdownsealsPrice')}}@else{{old('MorticeddropdownsealsPrice')}}@endif">


                                                    <input type="text" readonly name="morticeddropdownsealsKey"
                                                    id="MorticeddropdownsealsKey" class="form-control bg-white" value="@if(session()->has('Morticeddropdownseals')){{session()->get('Morticeddropdownseals')}}@else{{old('morticeddropdownsealsKey')}}@endif">
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="morticeddropdownsealsQty" id="morticeddropdownsealsQty" value="@if(session()->has('item')){{session()->get('item')->morticeddropdownsealsQty}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->morticeddropdownsealsQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg12"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="facefixeddropsealsKey" class="">Face fixed drop seals
                                                @if(!empty($tooltip->facefixeddropsealsKey))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->facefixeddropsealsKey}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_facefixeddropseals" onClick="IronMongery('Facefixeddropseals','Face fixed drop seals')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="facefixeddropsealsValue"
                                                    id="FacefixeddropsealsValue" value="@if(session()->has('item')){{session()->get('item')->Facefixeddropseals}}@endif">
                                                    <input type="hidden" class="price" value="@if(session()->has('FacefixeddropsealsPrice')){{session()->get('FacefixeddropsealsPrice')}}@endif">


                                                    <input type="text" readonly name="facefixeddropsealsKey"
                                                    id="FacefixeddropsealsKey" class="form-control bg-white" value="@if(session()->has('Facefixeddropseals')){{session()->get('Facefixeddropseals')}}@endif">
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="facefixeddropsealsQty" id="facefixeddropsealsQty" value="@if(session()->has('item')){{session()->get('item')->facefixeddropsealsQty}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->facefixeddropsealsQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg13"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="thresholdSealKey" class="">Threshold Seal
                                                @if(!empty($tooltip->thresholdSealKey))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->thresholdSealKey}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_thresholdSeal" onClick="IronMongery('ThresholdSeal','Threshold Seal')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="thresholdSealValue" id="ThresholdSealValue" value="@if(session()->has('item')){{session()->get('item')->ThresholdSeal}}@endif">
                                                    <input type="hidden" class="price" value="@if(session()->has('ThresholdSealPrice')){{session()->get('ThresholdSealPrice')}}@endif">


                                                    <input type="text" readonly name="thresholdSealKey"
                                                    id="ThresholdSealKey" class="form-control bg-white" value="@if(session()->has('ThresholdSeal')){{session()->get('ThresholdSeal')}}@endif">
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="thresholdSealQty" id="thresholdSealQty" value="@if(session()->has('item')){{session()->get('item')->thresholdSealQty}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->thresholdSealQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg14"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="airtransfergrillsKey" class="">Air Transfer Grill
                                                @if(!empty($tooltip->airtransfergrillsKey))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->airtransfergrillsKey}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_airtransfergrills" onClick="IronMongery('Airtransfergrills','Air Transfer Grill')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="airtransfergrillsValue"
                                                    id="AirtransfergrillsValue" value="@if(session()->has('item')){{session()->get('item')->AirTransferGrill}}@endif">
                                                    <input type="hidden" class="price" value="@if(session()->has('AirTransferGrillPrice')){{session()->get('AirTransferGrillPrice')}}@endif">

                                                    <input type="text" readonly name="airtransfergrillsKey"
                                                    id="AirtransfergrillsKey" class="form-control bg-white"  value="@if(session()->has('AirTransferGrill')){{session()->get('AirTransferGrill')}}@endif">
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="airtransfergrillsQty" id="airtransfergrillsQty" value="@if(session()->has('item')){{session()->get('item')->airtransfergrillsQty}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->airtransfergrillsQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg15"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="letterplatesKey" class="">Letterplates
                                                @if(!empty($tooltip->letterplatesKey))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->letterplatesKey}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_letterplates" onClick="IronMongery('Letterplates','Letter plates')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="letterplatesValue" id="LetterplatesValue" value="@if(session()->has('item')){{session()->get('item')->Letterplates}}@endif">
                                                    <input type="hidden" class="price" value="@if(session()->has('LetterplatesPrice')){{session()->get('LetterplatesPrice')}}@endif">


                                                    <input type="text" readonly name="letterplatesKey" id="LetterplatesKey"
                                                    class="form-control bg-white" value="@if(session()->has('Letterplates')){{session()->get('Letterplates')}}@endif">
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="letterplatesQty" id="letterplatesQty" value="@if(session()->has('item')){{session()->get('item')->letterplatesQty}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->letterplatesQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg16"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="cableWays" class="">Cable Ways
                                                @if(!empty($tooltip->cableWays))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->cableWays}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_CableWays" onClick="IronMongery('CableWays','Cable Ways')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="cableWaysValue" id="CableWaysValue" value="@if(session()->has('item')){{session()->get('item')->CableWays}}@endif">
                                                    <input type="hidden" class="price" value="@if(session()->has('CableWaysPrice')){{session()->get('CableWaysPrice')}}@endif">


                                                    <input type="text" readonly name="cableWaysKey" id="CableWaysKey"
                                                    class="form-control bg-white" value="@if(session()->has('CableWays')){{session()->get('CableWays')}}@endif">
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="cableWaysQty" id="cableWaysQty" value="@if(session()->has('item')){{session()->get('item')->cableWaysQty}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->cableWaysQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg17"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="safeHingeKey" class="">Safe Hinge
                                                @if(!empty($tooltip->safeHingeKey))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->safeHingeKey}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_SafeHinge" onClick="IronMongery('SafeHinge','Safe Hinge')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="safeHingeValue" id="SafeHingeValue" value="@if(session()->has('item')){{session()->get('item')->SafeHinge}}@endif">
                                                    <input type="hidden" class="price" value="@if(session()->has('SafeHingePrice')){{session()->get('SafeHingePrice')}}@endif">


                                                    <input type="text" readonly name="safeHingeKey" id="SafeHingeKey"
                                                    class="form-control bg-white" value="@if(session()->has('SafeHinge')){{session()->get('SafeHinge')}}@endif">
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="safeHingeQty" id="safeHingeQty" value="@if(session()->has('item')){{session()->get('item')->safeHingeQty}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->safeHingeQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg18"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="leverHandleKey" class="">Lever Handle
                                                @if(!empty($tooltip->leverHandleKey))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->leverHandleKey}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_LeverHandle" onClick="IronMongery('LeverHandle','Lever Handle')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="leverHandleValue" id="LeverHandleValue" value="@if(session()->has('item')){{session()->get('item')->LeverHandle}}@endif">
                                                    <input type="hidden" class="price" value="@if(session()->has('LeverHandlePrice')){{session()->get('LeverHandlePrice')}}@endif">


                                                    <input type="text" readonly name="leverHandleKey" id="LeverHandleKey"
                                                    class="form-control bg-white"  value="@if(session()->has('LeverHandle')){{session()->get('LeverHandle')}}@endif">
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="leverHandleQty" id="leverHandleQty" value="@if(session()->has('item')){{session()->get('item')->leverHandleQty}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->leverHandleQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg19"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="doorSignageKey" class="">Door Sinage
                                                @if(!empty($tooltip->doorSignageKey))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->doorSignageKey}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_DoorSignage" onClick="IronMongery('DoorSignage','Door Sinage')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="doorSignageValue" id="DoorSignageValue" value="@if(session()->has('item')){{session()->get('item')->DoorSinage}}@endif">
                                                    <input type="hidden" class="price" value="@if(session()->has('DoorSinagePrice')){{session()->get('DoorSinagePrice')}}@endif">


                                                    <input type="text" readonly name="doorSignageKey" id="DoorSignageKey"
                                                    class="form-control bg-white" value="@if(session()->has('DoorSinage')){{session()->get('DoorSinage')}}@endif">
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="doorSignageQty" id="doorSignageQty" value="@if(session()->has('item')){{session()->get('item')->doorSignageQty}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->doorSignageQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg20"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="faceFixedDoorClosersKey" class="">Face Fixed Door Closer
                                                @if(!empty($tooltip->faceFixedDoorClosersKey))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->faceFixedDoorClosersKey}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon"  id="data_fill_faceFixedDoorClosers" onClick="IronMongery('FaceFixedDoorClosers','Face Fixed Door Closer')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="faceFixedDoorClosersValue"
                                                    id="FaceFixedDoorClosersValue" value="@if(session()->has('item')){{session()->get('item')->FaceFixedDoorCloser}}@endif">
                                                    <input type="hidden" class="price" value="@if(session()->has('FaceFixedDoorCloserPrice')){{session()->get('FaceFixedDoorCloserPrice')}}@endif">


                                                    <input type="text" readonly name="faceFixedDoorClosersKey"
                                                    id="FaceFixedDoorClosersKey" class="form-control bg-white" value="@if(session()->has('FaceFixedDoorCloser')){{session()->get('FaceFixedDoorCloser')}}@endif">
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="faceFixedDoorClosersQty" id="faceFixedDoorClosersQty" value="@if(session()->has('item')){{session()->get('item')->faceFixedDoorClosersQty}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->faceFixedDoorClosersQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg21"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="thumbturnKey" class="">Thumbturn
                                                @if(!empty($tooltip->thumbturnKey))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->thumbturnKey}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_Thumbturn" onClick="IronMongery('Thumbturn','Thumbturn')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="thumbturnValue" id="ThumbturnValue" value="@if(session()->has('item')){{session()->get('item')->Thumbturn}}@endif">
                                                    <input type="hidden" class="price" value="@if(session()->has('ThumbturnPrice')){{session()->get('ThumbturnPrice')}}@endif">


                                                    <input type="text" readonly name="thumbturnKey" id="ThumbturnKey"
                                                    class="form-control bg-white" value="@if(session()->has('Thumbturn')){{session()->get('Thumbturn')}}@endif">
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="thumbturnQty" id="thumbturnQty" value="@if(session()->has('item')){{session()->get('item')->thumbturnQty}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->thumbturnQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg22"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="keyholeEscutcheonKey" class="">Keyhole Escutchen
                                                @if(!empty($tooltip->thumbturnKey))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->keyholeEscutcheonKey}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_keyholeEscutcheon" onClick="IronMongery('KeyholeEscutcheon','Keyhole Escutchen')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="keyholeEscutcheonValue"
                                                    id="KeyholeEscutcheonValue" value="@if(session()->has('item')){{session()->get('item')->KeyholeEscutchen}}@endif">
                                                    <input type="hidden" class="price" value="@if(session()->has('KeyholeEscutchenPrice')){{session()->get('KeyholeEscutchenPrice')}}@endif">

                                                    <input type="text" readonly name="keyholeEscutcheonKey"
                                                    id="KeyholeEscutcheonKey" class="form-control bg-white" value="@if(session()->has('KeyholeEscutchen')){{session()->get('KeyholeEscutchen')}}@endif">
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="keyholeEscutcheonQty" id="keyholeEscutcheonQty" value="@if(session()->has('item')){{session()->get('item')->keyholeEscutcheonQty}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->keyholeEscutcheonQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg23"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="DoorStopsKey">Door Stops
                                                @if(!empty($tooltip->DoorStopsKey))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->DoorStopsKey}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="data_fill_DoorStopsValue" onClick="IronMongery('DoorStops','Door Stops')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="DoorStopsValue"
                                                    id="DoorStopsValue" value="@if(session()->has('item')){{session()->get('item')->DoorStops}}@endif">
                                                    <input type="hidden" class="price" value="@if(session()->has('DoorStopsPrice')){{session()->get('DoorStopsPrice')}}@endif">

                                                    <input type="text" readonly name="DoorStopsKey"
                                                    id="DoorStopsKey" class="form-control bg-white" value="@if(session()->has('DoorStops')){{session()->get('DoorStops')}}@endif">
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="DoorStopsQty" id="DoorStopsQty" value="@if(session()->has('item')){{session()->get('item')->DoorStopsQty}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->DoorStopsQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg24"></div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label for="CylindersKey">Cylinders
                                                @if(!empty($tooltip->CylindersKey))
                                                <script type="text/javascript">
                                                document.write(Tooltip('{{$tooltip->CylindersKey}}'));
                                                </script>
                                                @endif
                                            </label>
                                            <div class="input-icons">
                                                <div class="input-group">
                                                    <div class="input-group-prepend">
                                                        <div class="input-group-text">
                                                            <i class="fa fa-info icon" id="door_fill_CylindersValue" onClick="IronMongery('Cylinders','Cylinders')"></i>
                                                        </div>
                                                    </div>
                                                    <input type="hidden" name="CylindersValue"
                                                    id="CylindersValue" value="@if(session()->has('item')){{session()->get('item')->Cylinders}}@endif">
                                                    <input type="hidden" class="price" value="@if(session()->has('CylindersPrice')){{session()->get('CylindersPrice')}}@endif">

                                                    <input type="text" readonly name="CylindersKey"
                                                    id="CylindersKey" class="form-control bg-white" value="@if(session()->has('Cylinders')){{session()->get('Cylinders')}}@endif">
                                                </div>
                                                <input type="number" min="1" onkeydown="if(event.key==='.'){event.preventDefault();}" name="CylindersQty" id="CylindersQty" value="@if(session()->has('item')){{session()->get('item')->CylindersQty}}@endif" class="form-control qty" placeholder="QTY" @if(session()->has('item') && session()->get('item')->CylindersQty){{ 'required' }}@endif>
                                                <button type="button" class="btn customcross Crossjs">X</button>
                                            </div>
                                        </div>
                                        <div class="text_style" id="msg25"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="main-card mb-3 custom_card">
                    <div class="d-block text-right">
                        <button type="submit" id="submit" class="btn-wide btn btn-success">
                            @if(session()->has('item')){{'Update Now'}} @else{{'Submit Now'}}@endif
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>
<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
@endsection
@section('js')
<script src="{{url('/')}}/js/AddIronmongeryjsfile.js"></script>
<script>
    $('.icon').addClass('cursor-pointer');
    $(document).ready(function(){
            $.ajax({
                url: "{{route('ironmongery-info/filter-iron-mongery-category')}}",
                method:"POST",
                data:{_token:$("#_token").val()},
                dataType:"Json",
                success: function(result){ console.log(result)
                    if(result.status=="ok"){
                        $("#ironIronmongerydata").val(JSON.stringify(result.data));
                        $("#currency").val(result.currency);
                        // alert(result.data)
                    }else{
                        $("#ironIronmongerydata").html('');
                    }
                }
            });



        // $(document).on('change', '#configurableitems', function() {
        //     let pageId = $(this).val();
        //     let pid = $('#ProjectId').val();
        //     let url = "{{url('project/add-ironmongery/')}}/"+pid+'/'+pageId;
        //     window.location.href =url;
        // })
    })

    function IronMongery(ironCategoryType,ironCategoryName){
        if(ironCategoryName == 'Push Handles'){
            ironCategoryName = 'Push Plates';
        }
        var data = $("#ironIronmongerydata").val();
        var currency = $("#currency").val();

        if(data!=''){
            data =  JSON.parse(data);
            var lenght = data.length;
            innerHtml = '';
            for(var index = 0; index<lenght;index++){
                if(data[index].Category==ironCategoryType){
                    if(data[index].Category == 'PushHandles'){
                        data[index].Category = 'PushPlates';
                    }
                    var image = "{{url('/')}}/uploads/IronmongeryInfo/"+data[index].Image;
                    innerHtml+=' <div class="col-md-4 col-sm-6 col-6">';
                    innerHtml+='<div class="product_holder">';
                    innerHtml+='<div class="product_img"><img src="'+image+'"></div>';
                    innerHtml+='<a class="product_name" href="#"><span>'+data[index].Code+'-</span> '+data[index].Name+'</a>';
                    innerHtml+='<div class="product_face">';
                    innerHtml+='<b>'+data[index].FireRating+'</b>';
                    innerHtml+='<b>'+currency+data[index].Price+'</b>';
                    innerHtml+='<b>'+data[index].Category+'</b>';
                    innerHtml+='</div>';
                    innerHtml+='<a href="javascript:void(0);" onClick="makeOption('+data[index].id+',\''+data[index].Name+'\',\''+data[index].Code+'\',\''+ironCategoryType+'\','+data[index].Price+')" class="product_edit" id="product_edit">Select</a>';
                    innerHtml+='</div></div>';
                }
            }
            if(innerHtml==''){
                innerHtml+='<div class=" col-md-12 alert alert-danger" role="alert"> No '+ ironCategoryName.toLowerCase() +' found </div>'

            }
        } else {
            innerHtml = '';
            innerHtml+='<div class=" col-md-12 alert alert-danger" role="alert"> No '+ ironCategoryName.toLowerCase() +' found </div>'
        }
        $("#content").empty().append(innerHtml);
        $("#modalTitle").empty().append('Select '+ironCategoryName);
        $("#iron").modal('show');
    }
    function makeOption(id,name,code,category,price){

        $("#"+category+'Value').val(id);
        $("#"+category+'Key').val(code+'-'+name);
        $("#"+category+'Key').siblings('.price').val(price);
        // $("#"+category+'Key').parent('div').siblings('.qty').val('');
        $("#"+category+'Key').parent('div').siblings('.qty').attr({'required':true});
        $("#iron").modal('hide');

        TotalPrice();


    }

    $(document).on('change','.qty',function(){
        TotalPrice();
    })


    function TotalPrice(){
        let totPrice = 0;
        $('.price').each(function(){
            var input = $(this); // This is the jquery object of the input, do what you will
            let price = parseFloat(input.val());
            let quantity = parseInt($(input).parent('.input-group').siblings('.qty').val());
            if(price != '' && quantity != ''){
                let calculatePrice = quantity*price;
                if(!isNaN(calculatePrice)){
                    totPrice += calculatePrice;
                }
            }
        });
        // $('#totalprice').empty().val(totPrice);
        // $('input[name=discountprice]').empty().val(totPrice);

        $('#totalprice').empty().val(parseFloat(totPrice).toFixed(2));
$('input[name=discountprice]').empty().val(parseFloat(totPrice).toFixed(2));

    }
    $(document).ready(function() {
        $("input[type=number]").on("focus", function() {
            $(this).on("keydown", function(event) {
                if (event.keyCode === 38 || event.keyCode === 40) {
                    event.preventDefault();
                }
            });
        });

    });
    $("#hingesQty").keyup(function(){
        if($(this).val() != '' || $('#HingesKey').val() != ''){
            $("#HingesKey").prop('required',true);
            if($("#HingesKey").val()=='' || $("#HingesKey").val()==undefined){
            $("#msg").text("Please select the Hinges then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#hingesQty").prop('required',false);
            $("#msg").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#data_fill_Hinges").click(function(){
        $("#msg").empty();
        $("#submit").prop('disabled',false);
    });


    $("#floorSpringQty").keyup(function(){
        if($(this).val() != '' || $('#FloorSpringKey').val() != ''){
            $("#FloorSpringKey").prop('required',true);
            if($("#FloorSpringKey").val()=='' || $("#FloorSpringKey").val()==undefined){
            $("#msg2").text("Please select the Floor Spring then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#floorSpringQty").prop('required',false);
            $("#msg2").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#data_fill_floorSpring").click(function(){
        $("#msg2").empty();
        $("#submit").prop('disabled',false);
    });

    $("#lockesAndLatchesQty").keyup(function(){
        if($(this).val() != '' || $('#LocksandLatchesKey').val() != ''){
            $("#LocksandLatchesKey").prop('required',true);
            if($("#LocksandLatchesKey").val()=='' || $("#LocksandLatchesKey").val()==undefined){
            $("#msg3").text("Please select the Lock And Latches then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#lockesAndLatchesQty").prop('required',false);
            $("#msg3").empty();
            $("#submit").prop('disabled',false);
        }

    });
    $("#data_fill_lockesAndLatches").click(function(){
        $("#msg3").empty();
        $("#submit").prop('disabled',false);
    });

    $("#flushBoltsQty").keyup(function(){
        if($(this).val() != '' || $('#FlushBoltsKey').val() != ''){
            $("#FlushBoltsKey").prop('required',true);
            if($("#FlushBoltsKey").val()=='' || $("#FlushBoltsKey").val()==undefined){
            $("#msg4").text("Please select the Flush Bolts then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#flushBoltsQty").prop('required',false);
            $("#msg4").empty();
            $("#submit").prop('disabled',false);
        }

    });
    $("#data_fill_FlushBolts").click(function(){
        $("#msg4").empty();
        $("#submit").prop('disabled',false);
    });

    $("#concealedOverheadCloserQty").keyup(function(){
        if($(this).val() != '' || $('#ConcealedOverheadCloserKey').val() != ''){
            $("#ConcealedOverheadCloserKey").prop('required',true);
            if($("#ConcealedOverheadCloserKey").val()=='' || $("#ConcealedOverheadCloserKey").val()==undefined){
            $("#msg5").text("Please select the Over head Closers then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#concealedOverheadCloserQty").prop('required',false);
            $("#msg5").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#data_fill_concealedOverheadCloser").click(function(){
        $("#msg5").empty();
        $("#submit").prop('disabled',false);
    });

    $("#pullHandlesQty").keyup(function(){
        if($(this).val() != '' || $('#PullHandlesKey').val() != ''){
            $("#PullHandlesKey").prop('required',true);
            if($("#PullHandlesKey").val()=='' || $("#PullHandlesKey").val()==undefined){
            $("#msg6").text("Please select the Pull Handles then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#pullHandlesQty").prop('required',false);
            $("#msg6").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#data_fill_PullHandles").click(function(){
        $("#msg6").empty();
        $("#submit").prop('disabled',false);
    });

    $("#pushHandlesQty").keyup(function(){
        if($(this).val() != '' || $('#PushHandlesKey').val() != ''){
            $("#PushHandlesKey").prop('required',true);
            if($("#PushHandlesKey").val()=='' || $("#PushHandlesKey").val()==undefined){
            $("#msg7").text("Please select the Flush Bolts then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#pushHandlesQty").prop('required',false);
            $("#msg7").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#data_fill_PushHandles").click(function(){
        $("#msg7").empty();
        $("#submit").prop('disabled',false);
    });

    $("#kickPlatesQty").keyup(function(){
        if($(this).val() != '' || $('#KickPlatesKey').val() != ''){
            $("#KickPlatesKey").prop('required',true);
            if($("#KickPlatesKey").val()=='' || $("#KickPlatesKey").val()==undefined){
            $("#msg8").text("Please select the Kick plates then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#kickPlatesQty").prop('required',false);
            $("#msg8").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#data_fill_KickPlates").click(function(){
        $("#msg8").empty();
        $("#submit").prop('disabled',false);
    });

    $("#doorSelectorsQty").keyup(function(){
        if($(this).val() != '' || $('#DoorSelectorsKey').val() != ''){
            $("#DoorSelectorsKey").prop('required',true);
            if($("#DoorSelectorsKey").val()=='' || $("#DoorSelectorsKey").val()==undefined){
            $("#msg9").text("Please select the Door Selectors then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#doorSelectorsQty").prop('required',false);
            $("#msg9").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#data_fill_doorSelectors").click(function(){
        $("#msg9").empty();
        $("#submit").prop('disabled',false);
    });

    $("#panicHardwareQty").keyup(function(){
        if($(this).val() != '' || $('#PanicHardwareKey').val() != ''){
            $("#PanicHardwareKey").prop('required',true);
            if($("#PanicHardwareKey").val()=='' || $("#PanicHardwareKey").val()==undefined){
            $("#msg10").text("Please select the Panic Hardware then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#panicHardwareQty").prop('required',false);
            $("#msg10").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#data_fill_panicHardware").click(function(){
        $("#msg10").empty();
        $("#submit").prop('disabled',false);
    });

    $("#doorSecurityViewerQty").keyup(function(){
        if($(this).val() != '' || $('#DoorsecurityviewerKey').val() != ''){
            $("#DoorsecurityviewerKey").prop('required',true);
            if($("#DoorsecurityviewerKey").val()=='' || $("#DoorsecurityviewerKey").val()==undefined){
            $("#msg11").text("Please select the Door security viewer then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#doorSecurityViewerQty").prop('required',false);
            $("#msg11").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#data_fill_doorSecurityViewer").click(function(){
        $("#msg11").empty();
        $("#submit").prop('disabled',false);
    });

    $("#morticeddropdownsealsQty").keyup(function(){
        if($(this).val() != '' || $('#MorticeddropdownsealsKey').val() != ''){
            $("#MorticeddropdownsealsKey").prop('required',true);
            if($("#MorticeddropdownsealsKey").val()=='' || $("#MorticeddropdownsealsKey").val()==undefined){
            $("#msg12").text("Please select the Morticed dropdown seals then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#morticeddropdownsealsQty").prop('required',false);
            $("#msg12").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#data_fill_morticeddropdownseals").click(function(){
        $("#msg12").empty();
        $("#submit").prop('disabled',false);
    });

    $("#facefixeddropsealsQty").keyup(function(){
        if($(this).val() != '' || $('#FacefixeddropsealsKey').val() != ''){
            $("#FacefixeddropsealsKey").prop('required',true);
            if($("#FacefixeddropsealsKey").val()=='' || $("#FacefixeddropsealsKey").val()==undefined){
            $("#msg13").text("Please select the Face fixed drop seals then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#facefixeddropsealsQty").prop('required',false);
            $("#msg13").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#data_fill_facefixeddropseals").click(function(){
        $("#msg13").empty();
        $("#submit").prop('disabled',false);
    });

    $("#thresholdSealQty").keyup(function(){
        if($(this).val() != '' || $('#ThresholdSealKey').val() != ''){
            $("#ThresholdSealKey").prop('required',true);
            if($("#ThresholdSealKey").val()=='' || $("#ThresholdSealKey").val()==undefined){
            $("#msg14").text("Please select the Threshold Seal then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#thresholdSealQty").prop('required',false);
            $("#msg14").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#data_fill_thresholdSeal").click(function(){
        $("#msg14").empty();
        $("#submit").prop('disabled',false);
    });

    $("#airtransfergrillsQty").keyup(function(){
        if($(this).val() != '' || $('#AirtransfergrillsKey').val() != ''){
            $("#AirtransfergrillsKey").prop('required',true);
            if($("#AirtransfergrillsKey").val()=='' || $("#AirtransfergrillsKey").val()==undefined){
            $("#msg15").text("Please select the Air transfer grill then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#airtransfergrillsQty").prop('required',false);
            $("#msg15").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#data_fill_airtransfergrills").click(function(){
        $("#msg15").empty();
        $("#submit").prop('disabled',false);
    });

    $("#letterplatesQty").keyup(function(){
        if($(this).val() != '' || $('#LetterplatesKey').val() != ''){
            $("#LetterplatesKey").prop('required',true);
            if($("#LetterplatesKey").val()=='' || $("#LetterplatesKey").val()==undefined){
            $("#msg16").text("Please select the Letter plates then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#letterplatesQty").prop('required',false);
            $("#msg16").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#data_fill_letterplates").click(function(){
        $("#msg16").empty();
        $("#submit").prop('disabled',false);
    });

    $("#cableWaysQty").keyup(function(){
        if($(this).val() != '' || $('#CableWaysKey').val() != ''){
            $("#CableWaysKey").prop('required',true);
            if($("#CableWaysKey").val()=='' || $("#CableWaysKey").val()==undefined){
            $("#msg17").text("Please select the Cable Ways then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#cableWaysQty").prop('required',false);
            $("#msg17").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#data_fill_CableWays").click(function(){
        $("#msg17").empty();
        $("#submit").prop('disabled',false);
    });

    $("#safeHingeQty").keyup(function(){
        if($(this).val() != '' || $('#SafeHingeKey').val() != ''){
            $("#SafeHingeKey").prop('required',true);
            if($("#SafeHingeKey").val()=='' || $("#SafeHingeKey").val()==undefined){
            $("#msg18").text("Please select the SafeHinge then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#safeHingeQty").prop('required',false);
            $("#msg18").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#data_fill_SafeHinge").click(function(){
        $("#msg18").empty();
        $("#submit").prop('disabled',false);
    });

    $("#leverHandleQty").keyup(function(){
        if($(this).val() != '' || $('#LeverHandleKey').val() != ''){
            $("#LeverHandleKey").prop('required',true);
            if($("#LeverHandleKey").val()=='' || $("#LeverHandleKey").val()==undefined){
            $("#msg19").text("Please select the Lever Handle then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#leverHandleQty").prop('required',false);
            $("#msg19").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#data_fill_LeverHandle").click(function(){
        $("#msg19").empty();
        $("#submit").prop('disabled',false);
    });

    $("#doorSignageQty").keyup(function(){
        if($(this).val() != '' || $('#DoorSignageKey').val() != ''){
            $("#DoorSignageKey").prop('required',true);
            if($("#DoorSignageKey").val()=='' || $("#DoorSignageKey").val()==undefined){
            $("#msg20").text("Please select the Door Signage then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#doorSignageQty").prop('required',false);
            $("#msg20").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#data_fill_DoorSignage").click(function(){
        $("#msg20").empty();
        $("#submit").prop('disabled',false);
    });

    $("#faceFixedDoorClosersQty").keyup(function(){
        if($(this).val() != '' || $('#FaceFixedDoorClosersKey').val() != ''){
            $("#FaceFixedDoorClosersKey").prop('required',true);
            if($("#FaceFixedDoorClosersKey").val()=='' || $("#FaceFixedDoorClosersKey").val()==undefined){
            $("#msg21").text("Please select the Face Fixed Door Closer then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#faceFixedDoorClosersQty").prop('required',false);
            $("#msg21").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#data_fill_faceFixedDoorClosers").click(function(){
        $("#msg21").empty();
        $("#submit").prop('disabled',false);
    });

    $("#thumbturnQty").keyup(function(){
        if($(this).val() != '' || $('#ThumbturnKey').val() != ''){
            $("#ThumbturnKey").prop('required',true);
            if($("#ThumbturnKey").val()=='' || $("#ThumbturnKey").val()==undefined){
            $("#msg22").text("Please Select the Thumbturn then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#thumbturnQty").prop('required',false);
            $("#msg22").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#data_fill_Thumbturn").click(function(){
        $("#msg22").empty();
        $("#submit").prop('disabled',false);
    });

    $("#keyholeEscutcheonQty").keyup(function(){
        if($(this).val() != '' || $('#KeyholeEscutcheonKey').val() != ''){
            $("#KeyholeEscutcheonKey").prop('required',true);
            if($("#KeyholeEscutcheonKey").val()=='' || $("#KeyholeEscutcheonKey").val()==undefined){
            $("#msg23").text("Please select the Keyhole Escutchen then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#keyholeEscutcheonQty").prop('required',false);
            $("#msg23").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#data_fill_keyholeEscutcheon").click(function(){
        $("#msg23").empty();
        $("#submit").prop('disabled',false);
    });

    $("#DoorStopsQty").keyup(function(){
        if($(this).val() != '' || $('#DoorStopsKey').val() != ''){
            $("#DoorStopsKey").prop('required',true);
            if($("#DoorStopsKey").val()=='' || $("#DoorStopsKey").val()==undefined){
            $("#msg24").text("Please select the Door Stops then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#DoorStopsQty").prop('required',false);
            $("#msg24").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#data_fill_DoorStopsValue").click(function(){
        $("#msg24").empty();
        $("#submit").prop('disabled',false);
    });

    $("#CylindersQty").keyup(function(){
        if($(this).val() != '' || $('#CylindersKey').val() != ''){
            $("#CylindersKey").prop('required',true);
            if($("#CylindersKey").val()=='' || $("#CylindersKey").val()==undefined){
            $("#msg25").text("Please select the Cylinders then add Qty...");
            $("#submit").prop('disabled',true);
            }
        }
        else{
            $("#CylindersQty").prop('required',false);
            $("#msg25").empty();
            $("#submit").prop('disabled',false);
        }
    });
    $("#door_fill_CylindersValue").keyup(function(){
        $("#msg25").empty();
        $("#submit").prop('disabled',false);
    });




</script>
@endsection

<!-- Modal -->
<div class="modal fade" id="iron" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header" style="display:block !important;">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title" id="modalTitle"></h5>
            </div>
            <div class="modal-body">
                <div class="row" id="content"></div>
            </div>
            <div class="modal-footer">
                <!-- <button type="button" class="btn btn-primary">Save changes</button> -->
            </div>
        </div>
    </div>
</div>
