@extends("layouts.Master")
@section("main_section")


<div class="app-main__outer">
    <div class="app-main__inner">
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
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title" style="margin-top: 10px">Undercut</h5>
                    </div>
                    <form action="{{route('save-configurable-door-formula')}}" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="position-relative form-group">
                                    <label>Undercut</label>

                                    <input type="hidden" name="slug" class="form-control"
                                        value="undercut" required >
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="position-relative form-group">
                                    <?php $Undercut = 0; ?>
                                    @foreach($ConfigurableDoorFormula as $row)
                                        @if($row->slug == 'undercut')
                                            <?php
                                                $UndercutDecoded = json_decode($row->value,true);
                                                $Undercut = $UndercutDecoded["undercut"];
                                            ?>
                                        @endif
                                    @endforeach

                                    <div class="d-flex">
                                        <b class="pt-2">Floor Finish + </b>
                                        <input type="number" min="0" name="undercut" class="form-control ml-3" value="{{$Undercut}}" style="width: 75px !important;">
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12"><hr></div>
                            <div class="col-md-12">
                                <button type="submit" id="submit" class="btn-wide btn btn-success" style="float: right;">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title" style="margin-top: 10px">Leaf Width 1 For Single Door Set</h5>
                    </div>
                    <form action="{{route('save-configurable-door-formula')}}" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="position-relative form-group">
                                    <label>Leaf Width 1 For Single Door Set</label>

                                    <input type="hidden" name="slug" class="form-control"
                                        value="leaf_width_1_for_single_door_set" required >
                                </div>
                            </div>

                                    <?php
                                        $ToleranceLeafWidth1ForSingleDoorSet = 1;
                                        $FrameThicknessLeafWidth1ForSingleDoorSet = 1;
                                        $GapLeafWidth1ForSingleDoorSet = 1;
                                    ?>
                                    @foreach($ConfigurableDoorFormula as $row)
                                        @if($row->slug == 'leaf_width_1_for_single_door_set')
                                            <?php
                                                $LeafWidth1ForSingleDoorSetDecoded = json_decode($row->value,true);
                                                $ToleranceLeafWidth1ForSingleDoorSet = $LeafWidth1ForSingleDoorSetDecoded["tolerance"];
                                                $FrameThicknessLeafWidth1ForSingleDoorSet = $LeafWidth1ForSingleDoorSetDecoded["frame_thickness"];
                                                $GapLeafWidth1ForSingleDoorSet = $LeafWidth1ForSingleDoorSetDecoded["gap"];
                                            ?>
                                        @endif
                                    @endforeach

                            <div class="col-md-9">
                                <div class="position-relative form-group">

                                    <div class="d-flex">
                                        <b class="pt-2">SOWidth - ( Tolerance * </b>

                                        <input type="number" min="1" name="tolerance" class="form-control ml-3 mr-3"
                                            value="{{$ToleranceLeafWidth1ForSingleDoorSet}}" style="width: 75px !important;" required >

                                        <b class="pt-2"> ) - ( FrameThickness * </b>

                                        <input type="number" min="1" name="frame_thickness" class="form-control ml-3 mr-3"  style="width: 75px !important;"
                                            value="{{$FrameThicknessLeafWidth1ForSingleDoorSet}}" required >

                                        <b class="pt-2"> ) - ( Gap *</b>

                                        <input type="number" min="1" name="gap" class="form-control ml-3 mr-3" style="width: 75px !important;"
                                            value="{{$GapLeafWidth1ForSingleDoorSet}}" required >

                                        <b class="pt-2"> )</b>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12"><hr></div>
                            <div class="col-md-12">
                                <button type="submit" id="submit" class="btn-wide btn btn-success" style="float: right;">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title" style="margin-top: 10px">Leaf Width 1 For Double Door Set</h5>
                    </div>
                    <form action="{{route('save-configurable-door-formula')}}" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="position-relative form-group">
                                    <label>Leaf Width 1 For Double Door Set</label>

                                    <input type="hidden" name="slug" class="form-control"
                                        value="leaf_width_1_for_double_door_set" required >
                                </div>
                            </div>

                                    <?php
                                        $ToleranceLeafWidth1ForDoubleDoorSet = 1;
                                        $FrameThicknessLeafWidth1ForDoubleDoorSet = 1;
                                        $GapLeafWidth1ForDoubleDoorSet = 1;
                                    ?>
                                    @foreach($ConfigurableDoorFormula as $row)
                                        @if($row->slug == 'leaf_width_1_for_double_door_set')
                                            <?php
                                                $LeafWidth1ForDoubleDoorSetDecoded = json_decode($row->value,true);
                                                $ToleranceLeafWidth1ForDoubleDoorSet = $LeafWidth1ForDoubleDoorSetDecoded["tolerance"];
                                                $FrameThicknessLeafWidth1ForDoubleDoorSet = $LeafWidth1ForDoubleDoorSetDecoded["frame_thickness"];
                                                $GapLeafWidth1ForDoubleDoorSet = $LeafWidth1ForDoubleDoorSetDecoded["gap"];
                                            ?>
                                        @endif
                                    @endforeach

                            <div class="col-md-9">
                                <div class="position-relative form-group">

                                    <div class="d-flex">
                                        <b class="pt-2">( SOWidth - ( Tolerance * </b>

                                        <input type="number" min="1" name="tolerance" class="form-control ml-3 mr-3"
                                            value="{{$ToleranceLeafWidth1ForDoubleDoorSet}}" style="width: 75px !important;" required >

                                        <b class="pt-2"> ) - ( FrameThickness * </b>

                                        <input type="number" min="1" name="frame_thickness" class="form-control ml-3 mr-3"  style="width: 75px !important;"
                                            value="{{$FrameThicknessLeafWidth1ForDoubleDoorSet}}" required >

                                        <b class="pt-2"> ) - ( Gap *</b>

                                        <input type="number" min="1" name="gap" class="form-control ml-3 mr-3" style="width: 75px !important;"
                                            value="{{$GapLeafWidth1ForDoubleDoorSet}}" required >

                                        <b class="pt-2"> ) ) / 2</b>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12"><hr></div>
                            <div class="col-md-12">
                                <button type="submit" id="submit" class="btn-wide btn btn-success" style="float: right;">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title" style="margin-top: 10px">Leaf Width 2 For Double Door Set</h5>
                    </div>
                    <form action="{{route('save-configurable-door-formula')}}" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="position-relative form-group">
                                    <label>Leaf Width 2 For Double Door Set</label>

                                    <input type="hidden" name="slug" class="form-control"
                                        value="leaf_width_2_for_double_door_set" required >
                                </div>
                            </div>

                                    <?php
                                        $ToleranceLeafWidth2ForDoubleDoorSet = 1;
                                        $FrameThicknessLeafWidth2ForDoubleDoorSet = 1;
                                        $GapLeafWidth2ForDoubleDoorSet = 1;
                                    ?>
                                    @foreach($ConfigurableDoorFormula as $row)
                                        @if($row->slug == 'leaf_width_2_for_double_door_set')
                                            <?php
                                                $LeafWidth2ForDoubleDoorSetDecoded = json_decode($row->value,true);
                                                $ToleranceLeafWidth2ForDoubleDoorSet = $LeafWidth2ForDoubleDoorSetDecoded["tolerance"];
                                                $FrameThicknessLeafWidth2ForDoubleDoorSet = $LeafWidth2ForDoubleDoorSetDecoded["frame_thickness"];
                                                $GapLeafWidth2ForDoubleDoorSet = $LeafWidth2ForDoubleDoorSetDecoded["gap"];
                                            ?>
                                        @endif
                                    @endforeach

                            <div class="col-md-9">
                                <div class="position-relative form-group">
                                    <div class="d-flex">
                                        <b class="pt-2">( SOWidth - ( Tolerance * </b>

                                        <input type="number" min="1" name="tolerance" class="form-control ml-3 mr-3"
                                            value="{{$ToleranceLeafWidth2ForDoubleDoorSet}}" style="width: 75px !important;" required >

                                        <b class="pt-2"> ) - ( FrameThickness * </b>

                                        <input type="number" min="1" name="frame_thickness" class="form-control ml-3 mr-3"  style="width: 75px !important;"
                                            value="{{$FrameThicknessLeafWidth2ForDoubleDoorSet}}" required >

                                        <b class="pt-2"> ) - ( Gap *</b>

                                        <input type="number" min="1" name="gap" class="form-control ml-3 mr-3" style="width: 75px !important;"
                                            value="{{$GapLeafWidth2ForDoubleDoorSet}}" required >

                                        <b class="pt-2"> ) ) / 2</b>
                                    </div>

                                </div>
                            </div>



                        </div>

                        <div class="row">
                            <div class="col-md-12"><hr></div>
                            <div class="col-md-12">
                                <button type="submit" id="submit" class="btn-wide btn btn-success" style="float: right;">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title" style="margin-top: 10px">Leaf Width 2 For Leaf and a half</h5>
                    </div>
                    <form action="{{route('save-configurable-door-formula')}}" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="position-relative form-group">
                                    <label>Tolerance Additional Value</label>

                                    <input type="hidden" name="slug" class="form-control"
                                        value="leaf_width_2_for_leaf_and_a_half" required >
                                </div>
                            </div>

                                    <?php
                                        $ToleranceLeafWidth2ForLeafAndAHalf = 1;
                                        $FrameThicknessLeafWidth2ForLeafAndAHalf = 1;
                                        $GapLeafWidth2ForLeafAndAHalf = 1;
                                    ?>
                                    @foreach($ConfigurableDoorFormula as $row)
                                        @if($row->slug == 'leaf_width_2_for_leaf_and_a_half')
                                            <?php
                                                $LeafWidth2ForLeafAndAHalfDecoded = json_decode($row->value,true);
                                                $ToleranceLeafWidth2ForLeafAndAHalf = $LeafWidth2ForLeafAndAHalfDecoded["tolerance"];
                                                $FrameThicknessLeafWidth2ForLeafAndAHalf = $LeafWidth2ForLeafAndAHalfDecoded["frame_thickness"];
                                                $GapLeafWidth2ForLeafAndAHalf = $LeafWidth2ForLeafAndAHalfDecoded["gap"];
                                            ?>
                                        @endif
                                    @endforeach

                            <div class="col-md-9">
                                <div class="position-relative form-group">

                                    <div class="d-flex">
                                        <b class="pt-2">SOWidth - ( Tolerance * </b>

                                        <input type="number" min="1" name="tolerance" class="form-control ml-3 mr-3"
                                            value="{{$ToleranceLeafWidth2ForLeafAndAHalf}}" style="width: 75px !important;" required >

                                        <b class="pt-2"> ) - ( FrameThickness * </b>

                                        <input type="number" min="1" name="frame_thickness" class="form-control ml-3 mr-3"  style="width: 75px !important;"
                                            value="{{$FrameThicknessLeafWidth2ForLeafAndAHalf}}" required >

                                        <b class="pt-2"> ) - ( Gap *</b>

                                        <input type="number" min="1" name="gap" class="form-control ml-3 mr-3" style="width: 75px !important;"
                                            value="{{$GapLeafWidth2ForLeafAndAHalf}}" required >

                                        <b class="pt-2"> ) - LeafWidth1</b>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12"><hr></div>
                            <div class="col-md-12">
                                <button type="submit" id="submit" class="btn-wide btn btn-success" style="float: right;">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title" style="margin-top: 10px">Frame Width</h5>
                    </div>
                    <form action="{{route('save-configurable-door-formula')}}" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="position-relative form-group">
                                    <label>Frame Width</label>

                                    <input type="hidden" name="slug" class="form-control"
                                        value="frame_width" required >
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="position-relative form-group">
                                    <?php $FrameWidthTolerance = 1; ?>
                                    @foreach($ConfigurableDoorFormula as $row)
                                        @if($row->slug == 'frame_width')
                                            <?php
                                                $FrameWidthDecoded = json_decode($row->value,true);
                                                $FrameWidthTolerance = $FrameWidthDecoded["tolerance"];
                                            ?>
                                        @endif
                                    @endforeach

                                    <div class="d-flex">
                                        <b class="pt-2">SOWidth - ( Tolerance * </b>

                                        <input type="number" min="1" name="tolerance" class="form-control ml-3 mr-3"
                                            value="{{$FrameWidthTolerance}}" style="width: 75px !important;" required >

                                        <b class="pt-2"> )</b>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12"><hr></div>
                            <div class="col-md-12">
                                <button type="submit" id="submit" class="btn-wide btn btn-success" style="float: right;">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title" style="margin-top: 10px">OP Width</h5>
                    </div>
                    <form action="{{route('save-configurable-door-formula')}}" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="position-relative form-group">
                                    <label>OP Width</label>

                                    <input type="hidden" name="slug" class="form-control"
                                        value="op_width" required >
                                </div>
                            </div>

                                    <?php
                                        $ToleranceOPWidth = 1;
                                        $FrameThicknessOPWidth = 1;
                                        $GapOPWidth = 1;
                                    ?>
                                    @foreach($ConfigurableDoorFormula as $row)
                                        @if($row->slug == 'op_width')
                                            <?php
                                                $OPWidthDecoded = json_decode($row->value,true);
                                                $ToleranceOPWidth = $OPWidthDecoded["tolerance"];
                                                $FrameThicknessOPWidth = $OPWidthDecoded["frame_thickness"];
                                                $GapOPWidth = $OPWidthDecoded["gap"];
                                            ?>
                                        @endif
                                    @endforeach

                            <div class="col-md-9">
                                <div class="position-relative form-group">

                                    <div class="d-flex">
                                        <b class="pt-2">SOWidth - ( Tolerance * </b>

                                        <input type="number" min="1" name="tolerance" class="form-control ml-3 mr-3"
                                            value="{{$ToleranceOPWidth}}" style="width: 75px !important;" required >

                                        <b class="pt-2"> ) - ( FrameThickness * </b>

                                        <input type="number" min="1" name="frame_thickness" class="form-control ml-3 mr-3"  style="width: 75px !important;"
                                            value="{{$FrameThicknessOPWidth}}" required >

                                        <b class="pt-2"> ) - ( Gap *</b>

                                        <input type="number" min="1" name="gap" class="form-control ml-3 mr-3" style="width: 75px !important;"
                                            value="{{$GapOPWidth}}" required >

                                        <b class="pt-2"> )</b>
                                    </div>

                                </div>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col-md-12"><hr></div>
                            <div class="col-md-12">
                                <button type="submit" id="submit" class="btn-wide btn btn-success" style="float: right;">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title" style="margin-top: 10px">Core Width 1</h5>
                    </div>
                    <form action="{{route('save-configurable-door-formula')}}" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="position-relative form-group">
                                    <label>Core Width 1</label>

                                    <input type="hidden" name="slug" class="form-control"
                                        value="core_width_1" required >
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="position-relative form-group">
                                    <?php $CoreWidth1LippingThickness = 1; ?>
                                    @foreach($ConfigurableDoorFormula as $row)
                                        @if($row->slug == 'core_width_1')
                                            <?php
                                                $CoreWidth1Decoded = json_decode($row->value,true);
                                                $CoreWidth1LippingThickness = $CoreWidth1Decoded["lipping_thickness"];
                                            ?>
                                        @endif
                                    @endforeach

                                    <div class="d-flex">
                                        <b class="pt-2">LeafWidth1 - ( LippingThickness * </b>

                                        <input type="number" min="1" name="lipping_thickness" class="form-control ml-3 mr-3" style="width: 75px !important;"
                                            value="{{$CoreWidth1LippingThickness}}" required >

                                        <b class="pt-2"> )</b>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12"><hr></div>
                            <div class="col-md-12">
                                <button type="submit" id="submit" class="btn-wide btn btn-success" style="float: right;">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title" style="margin-top: 10px">Core Width 2</h5>
                    </div>
                    <form action="{{route('save-configurable-door-formula')}}" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="position-relative form-group">
                                    <label>Core Width 2</label>

                                    <input type="hidden" name="slug" class="form-control"
                                        value="core_width_2" required >
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="position-relative form-group">
                                    <?php $CoreWidth2LippingThickness = 1; ?>
                                    @foreach($ConfigurableDoorFormula as $row)
                                        @if($row->slug == 'core_width_2')
                                            <?php
                                                $CoreWidth2Decoded = json_decode($row->value,true);
                                                $CoreWidth2LippingThickness = $CoreWidth2Decoded["lipping_thickness"];
                                            ?>
                                        @endif
                                    @endforeach

                                    <div class="d-flex">
                                        <b class="pt-2">LeafWidth2 - ( LippingThickness * </b>

                                        <input type="number" min="1" name="lipping_thickness" class="form-control ml-3 mr-3" style="width: 75px !important;"
                                            value="{{$CoreWidth2LippingThickness}}" required >

                                        <b class="pt-2"> )</b>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12"><hr></div>
                            <div class="col-md-12">
                                <button type="submit" id="submit" class="btn-wide btn btn-success" style="float: right;">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title" style="margin-top: 10px">Core Height</h5>
                    </div>
                    <form action="{{route('save-configurable-door-formula')}}" method="post">
                        {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-3">
                                <div class="position-relative form-group">
                                    <label>Core Height</label>

                                    <input type="hidden" name="slug" class="form-control"
                                        value="core_height" required >
                                </div>
                            </div>

                            <div class="col-md-9">
                                <div class="position-relative form-group">
                                    <?php $CoreHeightLippingThickness = 1; ?>
                                    @foreach($ConfigurableDoorFormula as $row)
                                        @if($row->slug == 'core_height')
                                            <?php
                                                $CoreHeightDecoded = json_decode($row->value,true);
                                                $CoreHeightLippingThickness = $CoreHeightDecoded["lipping_thickness"];
                                            ?>
                                        @endif
                                    @endforeach

                                    <div class="d-flex">
                                        <b class="pt-2">LeafHeight - ( LippingThickness * </b>

                                        <input type="number" min="1" name="lipping_thickness" class="form-control ml-3 mr-3" style="width: 75px !important;"
                                            value="{{$CoreHeightLippingThickness}}" required >

                                        <b class="pt-2"> )</b>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="row">
                            <div class="col-md-12"><hr></div>
                            <div class="col-md-12">
                                <button type="submit" id="submit" class="btn-wide btn btn-success" style="float: right;">
                                    Submit
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
@section("script_section")

@endsection