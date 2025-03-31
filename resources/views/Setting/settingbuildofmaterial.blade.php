@extends("layouts.Master")
@section("main_section")

<style>
.field-name {
    border: 1px solid #d8d8d8;
    width: 100%;
    border-radius: 5px;
    padding: 10px;
    margin-top: 15px;
}


.field-name span {
    background: #ffffff;
    padding: 5px;
}

.fieldset {
    color: #333;
    font-weight: bold;
    font-size: 13px;
    padding: 0;
    position: absolute;
    top: -29px;
}
</style>


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
                        <h5 class="card-title" style="margin-top: 10px">General Setting</h5>
                    </div>
                    <form action="{{route('subsettingbuildofmaterial')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="updval" value="@if(!empty($set)){{$set->id}}@endif">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="field-name">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Labour Cost</span></h5>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man" class="form-control"
                                                    value="@if(!empty($set)){{$set->labour_cost_per_man}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine" class="form-control"
                                                    value="@if(!empty($set)){{$set->labour_cost_per_machine}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-name">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Margin OR Markup</span></h5>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label for="glasstype" class="d-block">Margin OR Markup<span class="text-danger">*</span></label>
                                                <input type="radio" name="MarginMarkup" class="form-group  option-style"
                                                    value="Margin" required @if(!empty($set) && ($set->MarginMarkup == 'Margin')){{ 'checked' }}@endif>Margin
                                                <input type="radio" name="MarginMarkup" class="form-group  option-style"
                                                    value="Markup" required @if(!empty($set) && ($set->MarginMarkup == 'Markup')){{ 'checked' }}@endif>Markup
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-name">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Margin For</span></h5>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Material<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="margin_for_material" class="form-control"
                                                    value="@if(!empty($set)){{$set->margin_for_material}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Labour<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0" name="margin_for_labour" class="form-control"
                                                    value="@if(!empty($set)){{$set->margin_for_labour}}@endif" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-name">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Markup For</span></h5>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Material<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="markup_for_material" class="form-control"
                                                    value="@if(!empty($set)){{$set->markup_for_material}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Labour<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0" name="markup_for_labour" class="form-control"
                                                    value="@if(!empty($set)){{$set->markup_for_labour}}@endif" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="field-name">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Cost Of Lipping</span>
                                            </h5>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>44mm</label>
                                                <input type="text" name="cost_of_lipping_44mm" class="form-control"
                                                    value="@if(!empty($set)){{$set->cost_of_lipping_44mm}}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>54mm</label>
                                                <input type="text" name="cost_of_lipping_54mm" class="form-control"
                                                    value="@if(!empty($set)){{$set->cost_of_lipping_54mm}}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-name">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Machine Of</span></h5>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Frame</label>
                                                <input type="text" name="machine_of_frame" class="form-control"
                                                    value="@if(!empty($set)){{$set->machine_of_frame}}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Architrave</label>
                                                <input type="text" name="machine_of_architrave" class="form-control"
                                                    value="@if(!empty($set)){{$set->machine_of_architrave}}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <br>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label>Lipping Time</label>
                                            <input type="text" name="lipping_time" class="form-control"
                                                value="@if(!empty($set)){{$set->lipping_time}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label>Cutting Door Core</label>
                                            <input type="text" name="cutting_door_core" class="form-control"
                                                value="@if(!empty($set)){{$set->cutting_door_core}}@endif">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="position-relative form-group">
                                            <label>Pressing Door</label>
                                            <input type="text" name="pressing_door" class="form-control"
                                                value="@if(!empty($set)){{$set->pressing_door}}@endif">
                                        </div>
                                    </div>

                                </div>
                            </div> --}}
                            <div class="col-md-12">
                                <div class="row">
                                    <div class="col-md-12">
                                        <hr>
                                    </div>
                                    <div class="col-md-12">
                                        <button type="submit" id="submit" class="btn-wide btn btn-success"
                                            style="float: right;">
                                            Submit
                                        </button>
                                    </div>
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
