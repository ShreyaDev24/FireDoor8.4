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

.check-btn{
    z-index: 1051;
    position: absolute;
    left: 9px;
    top: 63px;
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
                        <div class="col-sm-6">
                            <h5 class="card-title" style="margin-top: 10px">General Labour Cost Setting</h5>
                        </div>
                        <div class="col-sm-6">
                            <div class="float-right">
                                <label for="check">Check All</label>
                                <input type='checkbox' id='check_all'>
                            </div>

                        </div>
                    </div>
                    <form action="{{route('general_labour_cost_sub_setting')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="updval" value="@if(!empty($set)){{$set->id}}@endif">
                        <input type="hidden" name="user_id" value="@if(!empty($set)){{$set->user_id}}@endif">
                        <input type="hidden" name="selectedgeneralId" value="@if(!empty($set)){{$set->generalId}}@endif">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='DoorLeafFacingVaneer' name='DoorLeafFacingVaneer' @if(!empty($set->DoorLeafFacingVaneer) && $set->DoorLeafFacingVaneer== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Door Leaf Facing Vaneer</span></h5>
                                        </div>
                                        <input type="hidden" class="DoorLeafFacingVaneer" name="type[]" value="DoorLeafFacingVaneer">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="door_leaf_facing_vaneer_man_minute" class="form-control DoorLeafFacingVaneer"
                                                    value="@if(!empty($set)){{$set->DoorLeafFacingVaneerManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="door_leaf_facing_vaneer_machine_minute" class="form-control DoorLeafFacingVaneer"
                                                    value="@if(!empty($set)){{$set->DoorLeafFacingVaneerMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('DoorLeafFacingVaneer', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control DoorLeafFacingVaneer" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control DoorLeafFacingVaneer"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='DoorLeafFacingKraftPaper' name='DoorLeafFacingKraftPaper' @if(!empty($set->DoorLeafFacingKraftPaper) && $set->DoorLeafFacingKraftPaper== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Door Leaf Facing Kraft Paper</span></h5>
                                        </div>
                                        <input type="hidden" class="DoorLeafFacingKraftPaper" name="type[]" value="DoorLeafFacingKraftPaper">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="door_leaf_facing_kraft_paper_man_minute" class="form-control DoorLeafFacingKraftPaper"
                                                    value="@if(!empty($set)){{$set->DoorLeafFacingKraftPaperManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="door_leaf_facing_kraft_paper_machine_minute" class="form-control DoorLeafFacingKraftPaper"
                                                    value="@if(!empty($set)){{$set->DoorLeafFacingKraftPaperMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('DoorLeafFacingKraftPaper', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control DoorLeafFacingKraftPaper"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control DoorLeafFacingKraftPaper"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='DoorLeafFacingLaminate' name='DoorLeafFacingLaminate' @if(!empty($set->DoorLeafFacingLaminate) && $set->DoorLeafFacingLaminate== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Door Leaf Facing Laminate</span></h5>
                                        </div>
                                        <input type="hidden" class="DoorLeafFacingLaminate" name="type[]" value="DoorLeafFacingLaminate">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="door_leaf_facing_laminate_man_minute" class="form-control DoorLeafFacingLaminate"
                                                    value="@if(!empty($set)){{$set->DoorLeafFacingLaminateManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="door_leaf_facing_laminate_machine_minute" class="form-control DoorLeafFacingLaminate"
                                                    value="@if(!empty($set)){{$set->DoorLeafFacingLaminateMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('DoorLeafFacingLaminate', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control DoorLeafFacingLaminate"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" onkeydown="if(event.key==='.'){event.preventDefault();}" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control DoorLeafFacingLaminate"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='DoorLeafFacingPVC' name='DoorLeafFacingPVC' @if(!empty($set->DoorLeafFacingPVC) && $set->DoorLeafFacingPVC== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Door Leaf Facing PVC</span></h5>
                                        </div>
                                        <input type="hidden" class="DoorLeafFacingPVC" name="type[]" value="DoorLeafFacingPVC">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="door_leaf_facing_pvc_man_minute" class="form-control DoorLeafFacingPVC"
                                                    value="@if(!empty($set)){{$set->DoorLeafFacingPVCManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="door_leaf_facing_pvc_machine_minute" class="form-control DoorLeafFacingPVC"
                                                    value="@if(!empty($set)){{$set->DoorLeafFacingPVCMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('DoorLeafFacingPVC', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control DoorLeafFacingPVC"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control DoorLeafFacingPVC"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='DoorLeafFinishPrimed' name='DoorLeafFinishPrimed' @if(!empty($set->DoorLeafFinishPrimed) && $set->DoorLeafFinishPrimed== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Door Leaf Finish Primed(Prime of door slab)</span></h5>
                                        </div>
                                        <input type="hidden" class="DoorLeafFinishPrimed" name="type[]" value="DoorLeafFinishPrimed">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="door_leaf_finish_primed_man_minute" class="form-control DoorLeafFinishPrimed"
                                                    value="@if(!empty($set)){{$set->DoorLeafFinishPrimedManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="door_leaf_finish_primed_machine_minute" class="form-control DoorLeafFinishPrimed"
                                                    value="@if(!empty($set)){{$set->DoorLeafFinishPrimedMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('DoorLeafFinishPrimed', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control DoorLeafFinishPrimed"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control DoorLeafFinishPrimed"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='DoorLeafFinishPainted' name='DoorLeafFinishPainted' @if(!empty($set->DoorLeafFinishPainted) && $set->DoorLeafFinishPainted== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Door Leaf Finish Painted(Paint of door slab)</span></h5>
                                        </div>
                                        <input type="hidden" class="DoorLeafFinishPainted" name="type[]" value="DoorLeafFinishPainted">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="door_leaf_finish_painted_man_minute" class="form-control DoorLeafFinishPainted"
                                                    value="@if(!empty($set)){{$set->DoorLeafFinishPaintedManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="door_leaf_finish_painted_machine_minute" class="form-control DoorLeafFinishPainted"
                                                    value="@if(!empty($set)){{$set->DoorLeafFinishPaintedMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('DoorLeafFinishPainted', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control DoorLeafFinishPainted"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control DoorLeafFinishPainted"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='DoorLeafFinishLacquered' name='DoorLeafFinishLacquered' @if(!empty($set->DoorLeafFinishLacquered) && $set->DoorLeafFinishLacquered== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Door Leaf Finish Lacquered(Lacquer of door slab)</span></h5>
                                        </div>
                                        <input type="hidden" class="DoorLeafFinishLacquered" name="type[]" value="DoorLeafFinishLacquered">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="door_leaf_finish_lacquered_man_minute" class="form-control DoorLeafFinishLacquered"
                                                    value="@if(!empty($set)){{$set->DoorLeafFinishLacqueredManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="door_leaf_finish_lacquered_machine_minute" class="form-control DoorLeafFinishLacquered"
                                                    value="@if(!empty($set)){{$set->DoorLeafFinishLacqueredMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('DoorLeafFinishLacquered', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control DoorLeafFinishLacquered"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control DoorLeafFinishLacquered"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='FrameFinishPrimed' name='FrameFinishPrimed' @if(!empty($set->FrameFinishPrimed) && $set->FrameFinishPrimed== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Frame Finish Primed</span></h5>
                                        </div>
                                        <input type="hidden" class="FrameFinishPrimed" name="type[]" value="FrameFinishPrimed">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="frame_finish_primed_man_minute" class="form-control FrameFinishPrimed"
                                                    value="@if(!empty($set)){{$set->FrameFinishPrimedManMinutes	}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="frame_finish_primed_machine_minute" class="form-control FrameFinishPrimed"
                                                    value="@if(!empty($set)){{$set->FrameFinishPrimedMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('FrameFinishPrimed', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control FrameFinishPrimed"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control FrameFinishPrimed"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='FrameFinishPainted' name='FrameFinishPainted' @if(!empty($set->FrameFinishPainted) && $set->FrameFinishPainted== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Frame Finish Painted</span></h5>
                                        </div>
                                        <input type="hidden" class="FrameFinishPainted" name="type[]" value="FrameFinishPainted">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="frame_finish_painted_man_minute" class="form-control FrameFinishPainted"
                                                    value="@if(!empty($set)){{$set->FrameFinishPaintedManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="frame_finish_painted_machine_minute" class="form-control FrameFinishPainted"
                                                    value="@if(!empty($set)){{$set->FrameFinishPaintedMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('FrameFinishPainted', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control FrameFinishPainted"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control FrameFinishPainted"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='FrameFinishLacqured' name='FrameFinishLacqured' @if(!empty($set->FrameFinishLacqured) && $set->FrameFinishLacqured== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Frame Finish Lacqured</span></h5>
                                        </div>
                                        <input type="hidden" class="FrameFinishLacqured" name="type[]" value="FrameFinishLacqured">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="frame_finish_lacqured_man_minute" class="form-control FrameFinishLacqured"
                                                    value="@if(!empty($set)){{$set->FrameFinishLacquredManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="frame_finish_lacqured_machine_minute" class="form-control FrameFinishLacqured"
                                                    value="@if(!empty($set)){{$set->FrameFinishLacquredMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('FrameFinishLacqured', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control FrameFinishLacqured"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control FrameFinishLacqured"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='ExtLiner' name='ExtLiner' @if(!empty($set->ExtLiner) && $set->ExtLiner== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Ext-Liner</span></h5>
                                        </div>
                                        <input type="hidden" class="ExtLiner" name="type[]" value="ExtLiner">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="ext_liner_man_minute" class="form-control ExtLiner"
                                                    value="@if(!empty($set)){{$set->ExtLinerManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="ext_liner_machine_minute" class="form-control ExtLiner"
                                                    value="@if(!empty($set)){{$set->ExtLinerMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('ExtLiner', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control ExtLiner"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control ExtLiner"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='ExtLinerandFrameFinish' name='ExtLinerandFrameFinish' @if(!empty($set->ExtLinerandFrameFinish) && $set->ExtLinerandFrameFinish== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Ext-Liner and Frame Finish Primed</span></h5>
                                        </div>
                                        <input type="hidden" class="ExtLinerandFrameFinish" name="type[]" value="ExtLinerandFrameFinish">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="ext_liner_and_frame_finish_primed_man_minute" class="form-control ExtLinerandFrameFinish"
                                                    value="@if(!empty($set)){{$set->ExtLinerandFrameFinishPrimedManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="ext_liner_and_frame_finish_primed_machine_minute" class="form-control ExtLinerandFrameFinish"
                                                    value="@if(!empty($set)){{$set->ExtLinerandFrameFinishPrimedMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('ExtLinerandFrameFinish', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control ExtLinerandFrameFinish"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control ExtLinerandFrameFinish"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='ExtLinerandFrameFinishPainted' name='ExtLinerandFrameFinishPainted' @if(!empty($set->ExtLinerandFrameFinishPainted) && $set->ExtLinerandFrameFinishPainted== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Ext-Liner and Frame Finish Painted</span></h5>
                                        </div>
                                        <input type="hidden" class="ExtLinerandFrameFinishPainted" name="type[]" value="ExtLinerandFrameFinishPainted">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="ext_liner_and_frame_finish_painted_man_minute" class="form-control ExtLinerandFrameFinishPainted"
                                                    value="@if(!empty($set)){{$set->ExtLinerandFrameFinishPaintedManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="ext_liner_and_frame_finish_painted_machine_minute" class="form-control ExtLinerandFrameFinishPainted"
                                                    value="@if(!empty($set)){{$set->ExtLinerandFrameFinishPaintedMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('ExtLinerandFrameFinishPainted', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control ExtLinerandFrameFinishPainted"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control ExtLinerandFrameFinishPainted"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='ExtLinerandFrameFinishLacqured' name='ExtLinerandFrameFinishLacqured' @if(!empty($set->ExtLinerandFrameFinishLacqured) && $set->ExtLinerandFrameFinishLacqured== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Ext-Liner and Frame Finish Lacqured</span></h5>
                                        </div>
                                        <input type="hidden" class="ExtLinerandFrameFinishLacqured" name="type[]" value="ExtLinerandFrameFinishLacqured">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="ext_liner_and_frame_finish_lacqured_man_minute" class="form-control ExtLinerandFrameFinishLacqured"
                                                    value="@if(!empty($set)){{$set->ExtLinerandFrameFinishLacquredManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="ext_liner_and_frame_finish_lacqured_machine_minute" class="form-control ExtLinerandFrameFinishLacqured"
                                                    value="@if(!empty($set)){{$set->ExtLinerandFrameFinishLacquredMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('ExtLinerandFrameFinishLacqured', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control ExtLinerandFrameFinishLacqured"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control ExtLinerandFrameFinishLacqured"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='VisionPanel2' name='VisionPanel2' @if(!empty($set->VisionPanel2) && $set->VisionPanel2== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Vision Panel(Maching of Glazing Bead)</span></h5>
                                        </div>
                                        <input type="hidden" class="VisionPanel2" name="type[]" value="VisionPanel2">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="VisionPanel2ManMinutes" class="form-control VisionPanel2"
                                                    value="@if(!empty($set)){{$set->VisionPanel2ManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="VisionPanel2MachineMinutes" class="form-control VisionPanel2"
                                                    value="@if(!empty($set)){{$set->VisionPanel2MachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('VisionPanel2', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control VisionPanel2"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control VisionPanel2"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='DoorLeafFinishPrimed2' name='DoorLeafFinishPrimed2' @if(!empty($set->DoorLeafFinishPrimed2) && $set->DoorLeafFinishPrimed2== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Door Leaf Finish Primed(Priming of glazing bead)</span></h5>
                                        </div>
                                        <input type="hidden" class="DoorLeafFinishPrimed2" name="type[]" value="DoorLeafFinishPrimed2">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="DoorLeafFinishPrimed2ManMinutes" class="form-control DoorLeafFinishPrimed2"
                                                    value="@if(!empty($set)){{$set->DoorLeafFinishPrimed2ManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="DoorLeafFinishPrimed2MachineMinutes" class="form-control DoorLeafFinishPrimed2"
                                                    value="@if(!empty($set)){{$set->DoorLeafFinishPrimed2MachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('DoorLeafFinishPrimed2', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control DoorLeafFinishPrimed2"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control DoorLeafFinishPrimed2"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='DoorLeafFinishPainted2' name='DoorLeafFinishPainted2' @if(!empty($set->DoorLeafFinishPainted2) && $set->DoorLeafFinishPainted2== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Door Leaf Finish Painted(Painting of glazing bead)</span></h5>
                                        </div>
                                        <input type="hidden" class="DoorLeafFinishPainted2" name="type[]" value="DoorLeafFinishPainted2">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="DoorLeafFinishPainted2ManMinutes" class="form-control DoorLeafFinishPainted2"
                                                    value="@if(!empty($set)){{$set->DoorLeafFinishPainted2ManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="DoorLeafFinishPainted2MachineMinutes" class="form-control DoorLeafFinishPainted2"
                                                    value="@if(!empty($set)){{$set->DoorLeafFinishPainted2MachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('DoorLeafFinishPainted2', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control DoorLeafFinishPainted2"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control DoorLeafFinishPainted2"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='DoorLeafFinishLacquered2' name='DoorLeafFinishLacquered2' @if(!empty($set->DoorLeafFinishLacquered2) && $set->DoorLeafFinishLacquered2== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Door Leaf Finish Lacquered(Lacquering of glazing bead)</span></h5>
                                        </div>
                                        <input type="hidden" class="DoorLeafFinishLacquered2" name="type[]" value="DoorLeafFinishLacquered2">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="DoorLeafFinishLacquered2ManMinutes" class="form-control DoorLeafFinishLacquered2"
                                                    value="@if(!empty($set)){{$set->DoorLeafFinishLacquered2ManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="DoorLeafFinishLacquered2MachineMinutes" class="form-control DoorLeafFinishLacquered2"
                                                    value="@if(!empty($set)){{$set->DoorLeafFinishLacquered2MachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('DoorLeafFinishLacquered2', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control DoorLeafFinishLacquered2"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control DoorLeafFinishLacquered2"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='VisionPanel' name='VisionPanel' @if(!empty($set->VisionPanel) && $set->VisionPanel== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Vision Panel(Vision Panel Cut Outs)</span></h5>
                                        </div>
                                        <input type="hidden" class="VisionPanel" name="type[]" value="VisionPanel">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="vision_panel_man_minute" class="form-control VisionPanel"
                                                    value="@if(!empty($set)){{$set->VisionPanelManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="vision_panel_machine_minute" class="form-control VisionPanel"
                                                    value="@if(!empty($set)){{$set->VisionPanelMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('VisionPanel', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control VisionPanel"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control VisionPanel"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='VisionPanelandFireRatingFD30' name='VisionPanelandFireRatingFD30' @if(!empty($set->VisionPanelandFireRatingFD30) && $set->VisionPanelandFireRatingFD30== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Vision Panel and FireRating FD-30(VP (Hockey Stick) - FD30 Fit)</span></h5>
                                        </div>
                                        <input type="hidden" class="VisionPanelandFireRatingFD30" name="type[]" value="VisionPanelandFireRatingFD30">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="vision_panel_and_fireRating_fd30_man_minute" class="form-control VisionPanelandFireRatingFD30"
                                                    value="@if(!empty($set)){{$set->VisionPanelandFireRatingFD30ManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="vision_panel_and_fireRating_fd30_machine_minute" class="form-control VisionPanelandFireRatingFD30"
                                                    value="@if(!empty($set)){{$set->VisionPanelandFireRatingFD30MachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('VisionPanelandFireRatingFD30', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control VisionPanelandFireRatingFD30"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control VisionPanelandFireRatingFD30"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='VisionPanelandFireRatingFD60' name='VisionPanelandFireRatingFD60' @if(!empty($set->VisionPanelandFireRatingFD60) && $set->VisionPanelandFireRatingFD60== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Vision Panel and FireRating FD-60(VP (Hockey Stick) - FD60 Fit)</span></h5>
                                        </div>
                                        <input type="hidden" class="VisionPanelandFireRatingFD60" name="type[]" value="VisionPanelandFireRatingFD60">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="vision_panel_and_fireRating_fd60_man_minute" class="form-control VisionPanelandFireRatingFD60"
                                                    value="@if(!empty($set)){{$set->VisionPanelandFireRatingFD60ManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="vision_panel_and_fireRating_fd60_machine_minute" class="form-control VisionPanelandFireRatingFD60"
                                                    value="@if(!empty($set)){{$set->VisionPanelandFireRatingFD60MachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('VisionPanelandFireRatingFD60', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control VisionPanelandFireRatingFD60"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control VisionPanelandFireRatingFD60"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='VisionPanelandFireRating2FD30' name='VisionPanelandFireRating2FD30' @if(!empty($set->VisionPanelandFireRating2FD30) && $set->VisionPanelandFireRating2FD30== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Vision Panel and FireRating FD-30(VP (Flush) - FD30 Fit)</span></h5>
                                        </div>
                                        <input type="hidden" class="VisionPanelandFireRating2FD30" name="type[]" value="VisionPanelandFireRating2FD30">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="VisionPanelandFireRating2FD30ManMinutes" class="form-control VisionPanelandFireRating2FD30"
                                                    value="@if(!empty($set)){{$set->VisionPanelandFireRating2FD30ManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="VisionPanelandFireRating2FD30MachineMinutes" class="form-control VisionPanelandFireRating2FD30"
                                                    value="@if(!empty($set)){{$set->VisionPanelandFireRating2FD30MachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('VisionPanelandFireRating2FD30', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control VisionPanelandFireRating2FD30"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control VisionPanelandFireRating2FD30"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='VisionPanelandFireRating2FD60' name='VisionPanelandFireRating2FD60' @if(!empty($set->VisionPanelandFireRating2FD60) && $set->VisionPanelandFireRating2FD60== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Vision Panel and FireRating FD-60(VP (Flush) - FD60 Fit)</span></h5>
                                        </div>
                                        <input type="hidden" class="VisionPanelandFireRating2FD60" name="type[]" value="VisionPanelandFireRating2FD60">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="VisionPanelandFireRating2FD60ManMinutes" class="form-control VisionPanelandFireRating2FD60"
                                                    value="@if(!empty($set)){{$set->VisionPanelandFireRating2FD60ManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="VisionPanelandFireRating2FD60MachineMinutes" class="form-control VisionPanelandFireRating2FD60"
                                                    value="@if(!empty($set)){{$set->VisionPanelandFireRating2FD60MachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('VisionPanelandFireRating2FD60', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control VisionPanelandFireRating2FD60"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control VisionPanelandFireRating2FD60"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='DecorativeGroves' name='DecorativeGroves' @if(!empty($set->DecorativeGroves) && $set->DecorativeGroves== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Decorative Groves</span></h5>
                                        </div>
                                        <input type="hidden" class="DecorativeGroves" name="type[]" value="DecorativeGroves">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="decorative_groves_man_minute" class="form-control DecorativeGroves"
                                                    value="@if(!empty($set)){{$set->DecorativeGrovesManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="decorative_groves_machine_minute" class="form-control DecorativeGroves"
                                                    value="@if(!empty($set)){{$set->DecorativeGrovesMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('DecorativeGroves', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control DecorativeGroves"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control DecorativeGroves"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='DecorativeGrovesLeaf2' name='DecorativeGrovesLeaf2' @if(!empty($set->DecorativeGrovesLeaf2) && $set->DecorativeGrovesLeaf2== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Decorative Groves Leaf2</span></h5>
                                        </div>
                                        <input type="hidden" class="DecorativeGrovesLeaf2" name="type[]" value="DecorativeGrovesLeaf2">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="decorative_groves_Leaf2_man_minute" class="form-control DecorativeGrovesLeaf2"
                                                    value="@if(!empty($set)){{$set->DecorativeGrovesLeaf2ManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="decorative_groves_Leaf2_machine_minute" class="form-control DecorativeGrovesLeaf2"
                                                    value="@if(!empty($set)){{$set->DecorativeGrovesLeaf2MachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('DecorativeGrovesLeaf2', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control DecorativeGrovesLeaf2"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control DecorativeGrovesLeaf2"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='DoorsetTypeSD' name='DoorsetTypeSD' @if(!empty($set->DoorsetTypeSD) && $set->DoorsetTypeSD== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Doorset Type SD(Assemble Single Door Leaf Into Frame)</span></h5>
                                        </div>
                                        <input type="hidden" class="DoorsetTypeSD" name="type[]" value="DoorsetTypeSD">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="doorset_type_sd_man_minute" class="form-control DoorsetTypeSD"
                                                    value="@if(!empty($set)){{$set->DoorsetTypeSDManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="doorset_type_sd_machine_minute" class="form-control DoorsetTypeSD"
                                                    value="@if(!empty($set)){{$set->DoorsetTypeSDMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('DoorsetTypeSD', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control DoorsetTypeSD"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control DoorsetTypeSD"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='DoorsetTypeSD2' name='DoorsetTypeSD2' @if(!empty($set->DoorsetTypeSD2) && $set->DoorsetTypeSD2== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Doorset Type SD(Doorset Delivery Single)</span></h5>
                                        </div>
                                        <input type="hidden" class="DoorsetTypeSD2" name="type[]" value="DoorsetTypeSD2">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="DoorsetTypeSD2ManMinutes" class="form-control DoorsetTypeSD2"
                                                    value="@if(!empty($set)){{$set->DoorsetTypeSD2ManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="DoorsetTypeSD2MachineMinutes" class="form-control DoorsetTypeSD2"
                                                    value="@if(!empty($set)){{$set->DoorsetTypeSD2MachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('DoorsetTypeSD2', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control DoorsetTypeSD2"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control DoorsetTypeSD2"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='DoorsetTypeDD' name='DoorsetTypeDD' @if(!empty($set->DoorsetTypeDD) && $set->DoorsetTypeDD== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Doorset Type DD(Assemble Double Door Leaf Into Frame)</span></h5>
                                        </div>
                                        <input type="hidden" class="DoorsetTypeDD" name="type[]" value="DoorsetTypeDD">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="doorset_type_dd_man_minute" class="form-control DoorsetTypeDD"
                                                    value="@if(!empty($set)){{$set->DoorsetTypeDDManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="doorset_type_dd_machine_minute" class="form-control DoorsetTypeDD"
                                                    value="@if(!empty($set)){{$set->DoorsetTypeDDMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('DoorsetTypeDD', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control DoorsetTypeDD"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control DoorsetTypeDD"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='DoorsetTypeDD2' name='DoorsetTypeDD2' @if(!empty($set->DoorsetTypeDD2) && $set->DoorsetTypeDD2== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Doorset Type DD(Doorset Delivery Double)</span></h5>
                                        </div>
                                        <input type="hidden" class="DoorsetTypeDD2" name="type[]" value="DoorsetTypeDD2">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="DoorsetTypeDD2ManMinutes" class="form-control DoorsetTypeDD2"
                                                    value="@if(!empty($set)){{$set->DoorsetTypeDD2ManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="DoorsetTypeDD2MachineMinutes" class="form-control DoorsetTypeDD2"
                                                    value="@if(!empty($set)){{$set->DoorsetTypeDD2MachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('DoorsetTypeDD2', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control DoorsetTypeDD2"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control DoorsetTypeDD2"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='OverpanelFanlight' name='OverpanelFanlight' @if(!empty($set->OverpanelFanlight) && $set->OverpanelFanlight== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Overpanel Fanlight Assembley</span></h5>
                                        </div>
                                        <input type="hidden" class="OverpanelFanlight" name="type[]" value="OverpanelFanlight">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="overpanel_fanlight_man_minute" class="form-control OverpanelFanlight"
                                                    value="@if(!empty($set)){{$set->OverpanelFanlightManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="overpanel_fanlight_machine_minute" class="form-control OverpanelFanlight"
                                                    value="@if(!empty($set)){{$set->OverpanelFanlightMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('OverpanelFanlight', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control OverpanelFanlight"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control OverpanelFanlight"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='OverpanelFanlightGlazing' name='OverpanelFanlightGlazing' @if(!empty($set->OverpanelFanlightGlazing) && $set->OverpanelFanlightGlazing== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Overpanel Fanlight Glazing</span></h5>
                                        </div>
                                        <input type="hidden" class="OverpanelFanlightGlazing" name="type[]" value="OverpanelFanlightGlazing">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="OverpanelFanlightGlazingManMinutes" class="form-control OverpanelFanlightGlazing"
                                                    value="@if(!empty($set)){{$set->OverpanelFanlightGlazingManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="OverpanelFanlightGlazingMachineMinutes" class="form-control OverpanelFanlightGlazing"
                                                    value="@if(!empty($set)){{$set->OverpanelFanlightGlazingMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('OverpanelFanlightGlazing', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control OverpanelFanlightGlazing"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control OverpanelFanlightGlazing"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='SideLight' name='SideLight' @if(!empty($set->SideLight) && $set->SideLight== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Side Light Assembley</span></h5>
                                        </div>
                                        <input type="hidden" class="SideLight" name="type[]" value="SideLight">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="side_light_man_minute" class="form-control SideLight"
                                                    value="@if(!empty($set)){{$set->SideLightManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="side_light_machine_minute" class="form-control SideLight"
                                                    value="@if(!empty($set)){{$set->SideLightMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('SideLight', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control SideLight"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control SideLight"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='SideLight2' name='SideLight2' @if(!empty($set->SideLight2) && $set->SideLight2== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Side Light 2 Assembley</span></h5>
                                        </div>
                                        <input type="hidden" class="SideLight2" name="type[]" value="SideLight2">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="side_light2_man_minute" class="form-control SideLight2"
                                                    value="@if(!empty($set)){{$set->SideLight2ManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="side_light2_machine_minute" class="form-control SideLight2"
                                                    value="@if(!empty($set)){{$set->SideLight2MachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('SideLight2', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control SideLight2"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control SideLight2"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='SideLightGlazing' name='SideLightGlazing' @if(!empty($set->SideLightGlazing) && $set->SideLightGlazing== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Side Light Glazing</span></h5>
                                        </div>
                                        <input type="hidden" class="SideLightGlazing" name="type[]" value="SideLightGlazing">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="SideLightGlazingManMinutes" class="form-control SideLightGlazing"
                                                    value="@if(!empty($set)){{$set->SideLightGlazingManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute<span class="text-danger">*</span></label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="SideLightGlazingMachineMinutes" class="form-control SideLightGlazing"
                                                    value="@if(!empty($set)){{$set->SideLightGlazingMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('SideLightGlazing', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control SideLightGlazing"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control SideLightGlazing"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='SideLight2Glazing' name='SideLight2Glazing' @if(!empty($set->SideLight2Glazing) && $set->SideLight2Glazing== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Side Light 2 Glazing</span></h5>
                                        </div>
                                        <input type="hidden" class="SideLight2Glazing" name="type[]" value="SideLight2Glazing">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="SideLight2GlazingManMinutes" class="form-control SideLight2Glazing"
                                                    value="@if(!empty($set)){{$set->SideLight2GlazingManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute<span class="text-danger">*</span></label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="SideLight2GlazingMachineMinutes" class="form-control SideLight2Glazing"
                                                    value="@if(!empty($set)){{$set->SideLight2GlazingMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('SideLight2Glazing', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control SideLight2Glazing"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control SideLight2Glazing"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='MachiningOfDoorFrame' name='MachiningOfDoorFrame' @if(!empty($set->MachiningOfDoorFrame) && $set->MachiningOfDoorFrame== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Machining Of Door Frame</span></h5>
                                        </div>
                                        <input type="hidden" class="MachiningOfDoorFrame" name="type[]" value="MachiningOfDoorFrame">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="machining_of_door_frame_man_minute" class="form-control MachiningOfDoorFrame"
                                                    value="@if(!empty($set)){{$set->MachiningOfDoorFrameManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="machining_of_door_frame_machine_minute" class="form-control MachiningOfDoorFrame"
                                                    value="@if(!empty($set)){{$set->MachiningOfDoorFrameMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('MachiningOfDoorFrame', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control MachiningOfDoorFrame"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control MachiningOfDoorFrame"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='FittingOfIntumescentToFrame' name='FittingOfIntumescentToFrame' @if(!empty($set->FittingOfIntumescentToFrame) && $set->FittingOfIntumescentToFrame== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Fitting Of Intumescent To Frame FD30</span></h5>
                                        </div>
                                        <input type="hidden" class="FittingOfIntumescentToFrame" name="type[]" value="FittingOfIntumescentToFrame">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="Fitting_of_intumescent_to_frame_man_minute" class="form-control FittingOfIntumescentToFrame"
                                                    value="@if(!empty($set)){{$set->FittingOfIntumescentToFrameManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="Fitting_of_intumescent_to_frame_machine_minute" class="form-control FittingOfIntumescentToFrame"
                                                    value="@if(!empty($set)){{$set->FittingOfIntumescentToFrameMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('FittingOfIntumescentToFrame', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control FittingOfIntumescentToFrame"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control FittingOfIntumescentToFrame"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='FittingOfIntumescentToFrameFD60' name='FittingOfIntumescentToFrameFD60' @if(!empty($set->FittingOfIntumescentToFrameFD60) && $set->FittingOfIntumescentToFrameFD60== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Fitting of Intumescent to Frame FD60</span></h5>
                                        </div>
                                        <input type="hidden" class="FittingOfIntumescentToFrameFD60" name="type[]" value="FittingOfIntumescentToFrameFD60">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="Fitting_of_intumescent_to_frame_man_minuteFD60" class="form-control FittingOfIntumescentToFrameFD60"
                                                    value="@if(!empty($set)){{$set->FittingOfIntumescentToFrameManMinutesFD60}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="Fitting_of_intumescent_to_frame_machine_minuteFD60" class="form-control FittingOfIntumescentToFrameFD60"
                                                    value="@if(!empty($set)){{$set->FittingOfIntumescentToFrameMachineMinutesFD60}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('FittingOfIntumescentToFrameFD60', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control FittingOfIntumescentToFrameFD60"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control FittingOfIntumescentToFrameFD60"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='HingeAssembley' name='HingeAssembley' @if(!empty($set->HingeAssembley) && $set->HingeAssembley== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Hinge Assembly</span></h5>
                                        </div>
                                        <input type="hidden" class="HingeAssembley" name="type[]" value="HingeAssembley">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="hinge_assembley_man_minute" class="form-control HingeAssembley"
                                                    value="@if(!empty($set)){{$set->HingeAssembleyManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="hinge_assembley_machine_minute" class="form-control HingeAssembley"
                                                    value="@if(!empty($set)){{$set->HingeAssembleyMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('HingeAssembley', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control HingeAssembley"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control HingeAssembley"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='HingeAssembleyLeafandHalfDD' name='HingeAssembleyLeafandHalfDD' @if(!empty($set->HingeAssembleyLeafandHalfDD) && $set->HingeAssembleyLeafandHalfDD== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Hinge Assembly for Leaf and Half and DD</span></h5>
                                        </div>
                                        <input type="hidden" class="HingeAssembleyLeafandHalfDD" name="type[]" value="HingeAssembleyLeafandHalfDD">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="hinge_assembley_man_minuteLeafandHalfDD" class="form-control HingeAssembleyLeafandHalfDD"
                                                    value="@if(!empty($set)){{$set->HingeAssembleyLeafandHalfDDManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="hinge_assembley_machine_minuteLeafandHalfDD" class="form-control HingeAssembleyLeafandHalfDD"
                                                    value="@if(!empty($set)){{$set->HingeAssembleyLeafandHalfDDMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('HingeAssembleyLeafandHalfDD', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control HingeAssembleyLeafandHalfDD"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control HingeAssembleyLeafandHalfDD"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='LocksetAssembley' name='LocksetAssembley' @if(!empty($set->LocksetAssembley) && $set->LocksetAssembley== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Lockset (Standard) - Assembly</span></h5>
                                        </div>
                                        <input type="hidden" class="LocksetAssembley" name="type[]" value="LocksetAssembley">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="lockset_assembley_man_minute" class="form-control LocksetAssembley"
                                                    value="@if(!empty($set)){{$set->LocksetAssembleyManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="lockset_assembley_machine_minute" class="form-control LocksetAssembley"
                                                    value="@if(!empty($set)){{$set->LocksetAssembleyMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('LocksetAssembley', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control LocksetAssembley"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control LocksetAssembley"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='PlotLabelDoorsets' name='PlotLabelDoorsets' @if(!empty($set->PlotLabelDoorsets) && $set->PlotLabelDoorsets== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Plot & Label Door sets</span></h5>
                                        </div>
                                        <input type="hidden" class="PlotLabelDoorsets" name="type[]" value="PlotLabelDoorsets">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="PlotLabelDoorsetsyManMinutes" class="form-control PlotLabelDoorsets"
                                                    value="@if(!empty($set)){{$set->PlotLabelDoorsetsyManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="PlotLabelDoorsetsMachineMinutes" class="form-control PlotLabelDoorsets"
                                                    value="@if(!empty($set)){{$set->PlotLabelDoorsetsMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('PlotLabelDoorsets', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control PlotLabelDoorsets"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control PlotLabelDoorsets"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='FrameAssembley' name='FrameAssembley' @if(!empty($set->FrameAssembley) && $set->FrameAssembley== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Frame Assembley</span></h5>
                                        </div>
                                        <input type="hidden" class="FrameAssembley" name="type[]" value="FrameAssembley">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="FrameAssembleyManMinutes" class="form-control FrameAssembley"
                                                    value="@if(!empty($set)){{$set->FrameAssembleyManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="FrameAssembleyMachineMinutes" class="form-control FrameAssembley"
                                                    value="@if(!empty($set)){{$set->FrameAssembleyMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('FrameAssembley', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control FrameAssembley"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control FrameAssembley"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='PalletPackaging' name='PalletPackaging' @if(!empty($set->PalletPackaging) && $set->PalletPackaging== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Pallet & Packaging</span></h5>
                                        </div>
                                        <input type="hidden" class="PalletPackaging" name="type[]" value="PalletPackaging">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="PalletPackagingManMinutes" class="form-control PalletPackaging"
                                                    value="@if(!empty($set)){{$set->PalletPackagingManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="PalletPackagingMachineMinutes" class="form-control PalletPackaging"
                                                    value="@if(!empty($set)){{$set->PalletPackagingMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('PalletPackaging', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control PalletPackaging"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control PalletPackaging"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='DoorLeafProtectionPlasticSleeve' name='DoorLeafProtectionPlasticSleeve' @if(!empty($set->DoorLeafProtectionPlasticSleeve) && $set->DoorLeafProtectionPlasticSleeve== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Door Leaf Protection - Plastic Sleeve SD</span></h5>
                                        </div>
                                        <input type="hidden" class="DoorLeafProtectionPlasticSleeve" name="type[]" value="DoorLeafProtectionPlasticSleeve">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="DoorLeafProtectionPlasticSleeveManMinutes" class="form-control DoorLeafProtectionPlasticSleeve"
                                                    value="@if(!empty($set)){{$set->DoorLeafProtectionPlasticSleeveManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="DoorLeafProtectionPlasticSleeveMachineMinutes" class="form-control DoorLeafProtectionPlasticSleeve"
                                                    value="@if(!empty($set)){{$set->DoorLeafProtectionPlasticSleeveMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('DoorLeafProtectionPlasticSleeve', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control DoorLeafProtectionPlasticSleeve"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control DoorLeafProtectionPlasticSleeve"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='DoorLeafProtectionPlasticSleeveFD60' name='DoorLeafProtectionPlasticSleeveFD60' @if(!empty($set->DoorLeafProtectionPlasticSleeveFD60) && $set->DoorLeafProtectionPlasticSleeveFD60== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Door Leaf Protection - Plastic Sleeve DD</span></h5>
                                        </div>
                                        <input type="hidden" class="DoorLeafProtectionPlasticSleeveFD60" name="type[]" value="DoorLeafProtectionPlasticSleeveFD60">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="DoorLeafProtectionPlasticSleeveManMinutesFD60" class="form-control DoorLeafProtectionPlasticSleeveFD60"
                                                    value="@if(!empty($set)){{$set->DoorLeafProtectionPlasticSleeveManMinutesFD60}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="DoorLeafProtectionPlasticSleeveMachineMinutesFD60" class="form-control DoorLeafProtectionPlasticSleeveFD60"
                                                    value="@if(!empty($set)){{$set->DoorLeafProtectionPlasticSleeveMachineMinutesFD60}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('DoorLeafProtectionPlasticSleeveFD60', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_man[]" class="form-control DoorLeafProtectionPlasticSleeveFD60"
                                                    value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control DoorLeafProtectionPlasticSleeveFD60"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='LeafSizing' name='LeafSizing' @if(!empty($set->LeafSizing) && $set->LeafSizing== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Leaf Sizing</span></h5>
                                        </div>
                                        <input type="hidden" class="LeafSizing" name="type[]" value="LeafSizing">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="LeafSizingManMinutes" class="form-control LeafSizing"
                                                    value="@if(!empty($set)){{$set->LeafSizingManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="LeafSizingMachineMinutes" class="form-control LeafSizing"
                                                    value="@if(!empty($set)){{$set->LeafSizingMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('LeafSizing', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control LeafSizing" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control LeafSizing"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='LeafLiping' name='LeafLiping' @if(!empty($set->LeafLiping) && $set->LeafLiping== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Leaf Lipping</span></h5>
                                        </div>
                                        <input type="hidden" class="LeafLiping" name="type[]" value="LeafLiping">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="LeafLipingManMinutes" class="form-control LeafLiping"
                                                    value="@if(!empty($set)){{$set->LeafLipingManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="LeafLipingMachineMinutes" class="form-control LeafLiping"
                                                    value="@if(!empty($set)){{$set->LeafLipingMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('LeafLiping', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control LeafLiping" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control LeafLiping"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='LeafCalibration' name='LeafCalibration' @if(!empty($set->LeafCalibration) && $set->LeafCalibration== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Leaf Calibration (Sanding)</span></h5>
                                        </div>
                                        <input type="hidden" class="LeafCalibration" name="type[]" value="LeafCalibration">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="LeafCalibrationManMinutes" class="form-control LeafCalibration"
                                                    value="@if(!empty($set)){{$set->LeafCalibrationManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="LeafCalibrationMachineMinutes" class="form-control LeafCalibration"
                                                    value="@if(!empty($set)){{$set->LeafCalibrationMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('LeafCalibration', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control LeafCalibration" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control LeafCalibration"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='PaintPrep' name='PaintPrep' @if(!empty($set->PaintPrep) && $set->PaintPrep== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Paint prep (Trimming, Chamfer and Filling if required)</span></h5>
                                        </div>
                                        <input type="hidden" class="PaintPrep" name="type[]" value="PaintPrep">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="PaintPrepManMinutes" class="form-control PaintPrep"
                                                    value="@if(!empty($set)){{$set->PaintPrepManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="PaintPrepMachineMinutes" class="form-control PaintPrep"
                                                    value="@if(!empty($set)){{$set->PaintPrepMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('PaintPrep', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control PaintPrep" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control PaintPrep"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='LabourProcessForScallopedFrame' name='LabourProcessForScallopedFrame' @if(!empty($set->LabourProcessForScallopedFrame) && $set->LabourProcessForScallopedFrame== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Labour Process for Scalloped Frame</span></h5>
                                        </div>
                                        <input type="hidden" class="LabourProcessForScallopedFrame" name="type[]" value="LabourProcessForScallopedFrame">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="LabourProcessForScallopedFrameManMinutes" class="form-control LabourProcessForScallopedFrame"
                                                    value="@if(!empty($set)){{$set->LabourProcessForScallopedFrameManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="LabourProcessForScallopedFrameMachineMinutes" class="form-control LabourProcessForScallopedFrame"
                                                    value="@if(!empty($set)){{$set->LabourProcessForScallopedFrameMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('LabourProcessForScallopedFrame', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control LabourProcessForScallopedFrame" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control LabourProcessForScallopedFrame"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='LabourAssemblyFor4SidedFrame' name='LabourAssemblyFor4SidedFrame' @if(!empty($set->LabourAssemblyFor4SidedFrame) && $set->LabourAssemblyFor4SidedFrame== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Labour Assembly for 4-sided Frame</span></h5>
                                        </div>
                                        <input type="hidden" class="LabourAssemblyFor4SidedFrame" name="type[]" value="LabourAssemblyFor4SidedFrame">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="LabourAssemblyFor4SidedFrameManMinutes" class="form-control LabourAssemblyFor4SidedFrame"
                                                    value="@if(!empty($set)){{$set->LabourAssemblyFor4SidedFrameManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="LabourAssemblyFor4SidedFrameMachineMinutes" class="form-control LabourAssemblyFor4SidedFrame"
                                                    value="@if(!empty($set)){{$set->LabourAssemblyFor4SidedFrameMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('LabourAssemblyFor4SidedFrame', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control LabourAssemblyFor4SidedFrame" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control LabourAssemblyFor4SidedFrame"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='MachiningOfScreenframe' name='MachiningOfScreenframe' @if(!empty($set->MachiningOfScreenframe) && $set->MachiningOfScreenframe== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Machining of Screen frame</span></h5>
                                        </div>
                                        <input type="hidden" class="MachiningOfScreenframe" name="type[]" value="MachiningOfScreenframe">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="MachiningOfScreenframeManMinutes" class="form-control MachiningOfScreenframe"
                                                    value="@if(!empty($set)){{$set->MachiningOfScreenframeManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="MachiningOfScreenframeMachineMinutes" class="form-control MachiningOfScreenframe"
                                                    value="@if(!empty($set)){{$set->MachiningOfScreenframeMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('MachiningOfScreenframe', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control MachiningOfScreenframe" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control MachiningOfScreenframe"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='MachiningOfGlazingBead' name='MachiningOfGlazingBead' @if(!empty($set->MachiningOfGlazingBead) && $set->MachiningOfGlazingBead== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Machining of Glazing Bead</span></h5>
                                        </div>
                                        <input type="hidden" class="MachiningOfGlazingBead" name="type[]" value="MachiningOfGlazingBead">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="MachiningOfGlazingBeadManMinutes" class="form-control MachiningOfGlazingBead"
                                                    value="@if(!empty($set)){{$set->MachiningOfGlazingBeadManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="MachiningOfGlazingBeadMachineMinutes" class="form-control MachiningOfGlazingBead"
                                                    value="@if(!empty($set)){{$set->MachiningOfGlazingBeadMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('MachiningOfGlazingBead', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control MachiningOfGlazingBead" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control MachiningOfGlazingBead"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='MachiningOfTransom' name='MachiningOfTransom' @if(!empty($set->MachiningOfTransom) && $set->MachiningOfTransom== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Machining of Transom</span></h5>
                                        </div>
                                        <input type="hidden" class="MachiningOfTransom" name="type[]" value="MachiningOfTransom">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="MachiningOfTransomManMinutes" class="form-control MachiningOfTransom"
                                                    value="@if(!empty($set)){{$set->MachiningOfTransomManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="MachiningOfTransomMachineMinutes" class="form-control MachiningOfTransom"
                                                    value="@if(!empty($set)){{$set->MachiningOfTransomMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('MachiningOfTransom', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control MachiningOfTransom" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control MachiningOfTransom"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='MachiningOfSubFrame' name='MachiningOfSubFrame' @if(!empty($set->MachiningOfSubFrame) && $set->MachiningOfSubFrame== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Machining of Sub Frame</span></h5>
                                        </div>
                                        <input type="hidden" class="MachiningOfSubFrame" name="type[]" value="MachiningOfSubFrame">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="MachiningOfSubFrameManMinutes" class="form-control MachiningOfSubFrame"
                                                    value="@if(!empty($set)){{$set->MachiningOfSubFrameManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="MachiningOfSubFrameMachineMinutes" class="form-control MachiningOfSubFrame"
                                                    value="@if(!empty($set)){{$set->MachiningOfSubFrameMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('MachiningOfSubFrame', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control MachiningOfSubFrame" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control MachiningOfSubFrame"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='CuttingOfScreenframe' name='CuttingOfScreenframe' @if(!empty($set->CuttingOfScreenframe) && $set->CuttingOfScreenframe== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Cutting of Screen frame</span></h5>
                                        </div>
                                        <input type="hidden" class="CuttingOfScreenframe" name="type[]" value="CuttingOfScreenframe">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="CuttingOfScreenframeManMinutes" class="form-control CuttingOfScreenframe"
                                                    value="@if(!empty($set)){{$set->CuttingOfScreenframeManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="CuttingOfScreenframeMachineMinutes" class="form-control CuttingOfScreenframe"
                                                    value="@if(!empty($set)){{$set->CuttingOfScreenframeMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('CuttingOfScreenframe', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control CuttingOfScreenframe" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control CuttingOfScreenframe"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='CuttingOfGlazingBead' name='CuttingOfGlazingBead' @if(!empty($set->CuttingOfGlazingBead) && $set->CuttingOfGlazingBead== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Cutting of Glazing Bead</span></h5>
                                        </div>
                                        <input type="hidden" class="CuttingOfGlazingBead" name="type[]" value="CuttingOfGlazingBead">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="CuttingOfGlazingBeadManMinutes" class="form-control CuttingOfGlazingBead"
                                                    value="@if(!empty($set)){{$set->CuttingOfGlazingBeadManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="CuttingOfGlazingBeadMachineMinutes" class="form-control CuttingOfGlazingBead"
                                                    value="@if(!empty($set)){{$set->CuttingOfGlazingBeadMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('CuttingOfGlazingBead', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control CuttingOfGlazingBead" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control CuttingOfGlazingBead"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='CuttingOfTransom' name='CuttingOfTransom' @if(!empty($set->CuttingOfTransom) && $set->CuttingOfTransom== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Cutting of Transom</span></h5>
                                        </div>
                                        <input type="hidden" class="CuttingOfTransom" name="type[]" value="CuttingOfTransom">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="CuttingOfTransomManMinutes" class="form-control CuttingOfTransom"
                                                    value="@if(!empty($set)){{$set->CuttingOfTransomManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="CuttingOfTransomMachineMinutes" class="form-control CuttingOfTransom"
                                                    value="@if(!empty($set)){{$set->CuttingOfTransomMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('CuttingOfTransom', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control CuttingOfTransom" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control CuttingOfTransom"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='CuttingOfSubFrame' name='CuttingOfSubFrame' @if(!empty($set->CuttingOfSubFrame) && $set->CuttingOfSubFrame== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Cutting of Sub Frame</span></h5>
                                        </div>
                                        <input type="hidden" class="CuttingOfSubFrame" name="type[]" value="CuttingOfSubFrame">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="CuttingOfSubFrameManMinutes" class="form-control CuttingOfSubFrame"
                                                    value="@if(!empty($set)){{$set->CuttingOfSubFrameManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="CuttingOfSubFrameMachineMinutes" class="form-control CuttingOfSubFrame"
                                                    value="@if(!empty($set)){{$set->CuttingOfSubFrameMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('CuttingOfSubFrame', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control CuttingOfSubFrame" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control CuttingOfSubFrame"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='ScreenAssembley' name='ScreenAssembley' @if(!empty($set->ScreenAssembley) && $set->ScreenAssembley== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Screen Assembley</span></h5>
                                        </div>
                                        <input type="hidden" class="ScreenAssembley" name="type[]" value="ScreenAssembley">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="ScreenAssembleyManMinutes" class="form-control ScreenAssembley"
                                                    value="@if(!empty($set)){{$set->ScreenAssembleyManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="ScreenAssembleyMachineMinutes" class="form-control ScreenAssembley"
                                                    value="@if(!empty($set)){{$set->ScreenAssembleyMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('ScreenAssembley', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control ScreenAssembley" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control ScreenAssembley"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='TransomAssembley' name='TransomAssembley' @if(!empty($set->TransomAssembley) && $set->TransomAssembley== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Transom Assembley</span></h5>
                                        </div>
                                        <input type="hidden" class="TransomAssembley" name="type[]" value="TransomAssembley">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="TransomAssembleyManMinutes" class="form-control TransomAssembley"
                                                    value="@if(!empty($set)){{$set->TransomAssembleyManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="TransomAssembleyMachineMinutes" class="form-control TransomAssembley"
                                                    value="@if(!empty($set)){{$set->TransomAssembleyMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('TransomAssembley', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control TransomAssembley" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control TransomAssembley"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='SubFrameAssembley' name='SubFrameAssembley' @if(!empty($set->SubFrameAssembley) && $set->SubFrameAssembley== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Sub Frame Assembley</span></h5>
                                        </div>
                                        <input type="hidden" class="SubFrameAssembley" name="type[]" value="SubFrameAssembley">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="SubFrameAssembleyManMinutes" class="form-control SubFrameAssembley"
                                                    value="@if(!empty($set)){{$set->SubFrameAssembleyManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="SubFrameAssembleyMachineMinutes" class="form-control SubFrameAssembley"
                                                    value="@if(!empty($set)){{$set->SubFrameAssembleyMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('SubFrameAssembley', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control SubFrameAssembley" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control SubFrameAssembley"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='FittingOfGlass' name='FittingOfGlass' @if(!empty($set->FittingOfGlass) && $set->FittingOfGlass== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Fitting of Glass</span></h5>
                                        </div>
                                        <input type="hidden" class="FittingOfGlass" name="type[]" value="FittingOfGlass">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="FittingOfGlassManMinutes" class="form-control FittingOfGlass"
                                                    value="@if(!empty($set)){{$set->FittingOfGlassManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="FittingOfGlassMachineMinutes" class="form-control FittingOfGlass"
                                                    value="@if(!empty($set)){{$set->FittingOfGlassMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('FittingOfGlass', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control FittingOfGlass" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control FittingOfGlass"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='FittingOfGlazingSystem' name='FittingOfGlazingSystem' @if(!empty($set->FittingOfGlazingSystem) && $set->FittingOfGlazingSystem== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Fitting Of Glazing System</span></h5>
                                        </div>
                                        <input type="hidden" class="FittingOfGlazingSystem" name="type[]" value="FittingOfGlazingSystem">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="FittingOfGlazingSystemManMinutes" class="form-control FittingOfGlazingSystem"
                                                    value="@if(!empty($set)){{$set->FittingOfGlazingSystemManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="FittingOfGlazingSystemMachineMinutes" class="form-control FittingOfGlazingSystem"
                                                    value="@if(!empty($set)){{$set->FittingOfGlazingSystemMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('FittingOfGlazingSystem', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control FittingOfGlazingSystem" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control FittingOfGlazingSystem"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='FittingOfGlazingBead' name='FittingOfGlazingBead' @if(!empty($set->FittingOfGlazingBead) && $set->FittingOfGlazingBead== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Fitting of Glazing Bead</span></h5>
                                        </div>
                                        <input type="hidden" class="FittingOfGlazingBead" name="type[]" value="FittingOfGlazingBead">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="FittingOfGlazingBeadManMinutes" class="form-control FittingOfGlazingBead"
                                                    value="@if(!empty($set)){{$set->FittingOfGlazingBeadManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="FittingOfGlazingBeadMachineMinutes" class="form-control FittingOfGlazingBead"
                                                    value="@if(!empty($set)){{$set->FittingOfGlazingBeadMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('FittingOfGlazingBead', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control FittingOfGlazingBead" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control FittingOfGlazingBead"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='SprayFinishOf' name='SprayFinishOf' @if(!empty($set->SprayFinishOf) && $set->SprayFinishOf== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Spray Finish of</span></h5>
                                        </div>
                                        <input type="hidden" class="SprayFinishOf" name="type[]" value="SprayFinishOf">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="SprayFinishOfManMinutes" class="form-control SprayFinishOf"
                                                    value="@if(!empty($set)){{$set->SprayFinishOfManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="SprayFinishOfMachineMinutes" class="form-control SprayFinishOf"
                                                    value="@if(!empty($set)){{$set->SprayFinishOfMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('SprayFinishOf', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control SprayFinishOf" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control SprayFinishOf"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='SprayFinishOfScreenframe' name='SprayFinishOfScreenframe' @if(!empty($set->SprayFinishOfScreenframe) && $set->SprayFinishOfScreenframe== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Spray Finish of Screen frame</span></h5>
                                        </div>
                                        <input type="hidden" class="SprayFinishOfScreenframe" name="type[]" value="SprayFinishOfScreenframe">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="SprayFinishOfScreenframeManMinutes" class="form-control SprayFinishOfScreenframe"
                                                    value="@if(!empty($set)){{$set->SprayFinishOfScreenframeManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="SprayFinishOfScreenframeMachineMinutes" class="form-control SprayFinishOfScreenframe"
                                                    value="@if(!empty($set)){{$set->SprayFinishOfScreenframeMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('SprayFinishOfScreenframe', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control SprayFinishOfScreenframe" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control SprayFinishOfScreenframe"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='SprayFinishGlazingBead' name='SprayFinishGlazingBead' @if(!empty($set->SprayFinishGlazingBead) && $set->SprayFinishGlazingBead== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Spray Finish Glazing Bead</span></h5>
                                        </div>
                                        <input type="hidden" class="SprayFinishGlazingBead" name="type[]" value="SprayFinishGlazingBead">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="SprayFinishGlazingBeadManMinutes" class="form-control SprayFinishGlazingBead"
                                                    value="@if(!empty($set)){{$set->SprayFinishGlazingBeadManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="SprayFinishGlazingBeadMachineMinutes" class="form-control SprayFinishGlazingBead"
                                                    value="@if(!empty($set)){{$set->SprayFinishGlazingBeadMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('SprayFinishGlazingBead', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control SprayFinishGlazingBead" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control SprayFinishGlazingBead"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='SprayFinishOfTransom' name='SprayFinishOfTransom' @if(!empty($set->SprayFinishOfTransom) && $set->SprayFinishOfTransom== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Spray Finish of Transom</span></h5>
                                        </div>
                                        <input type="hidden" class="SprayFinishOfTransom" name="type[]" value="SprayFinishOfTransom">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="SprayFinishOfTransomManMinutes" class="form-control SprayFinishOfTransom"
                                                    value="@if(!empty($set)){{$set->SprayFinishOfTransomManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="SprayFinishOfTransomMachineMinutes" class="form-control SprayFinishOfTransom"
                                                    value="@if(!empty($set)){{$set->SprayFinishOfTransomMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('SprayFinishOfTransom', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control SprayFinishOfTransom" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control SprayFinishOfTransom"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='SprayFinishOfSubFrame' name='SprayFinishOfSubFrame' @if(!empty($set->SprayFinishOfSubFrame) && $set->SprayFinishOfSubFrame== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Spray Finish of Sub Frame</span></h5>
                                        </div>
                                        <input type="hidden" class="SprayFinishOfSubFrame" name="type[]" value="SprayFinishOfSubFrame">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="SprayFinishOfSubFrameManMinutes" class="form-control SprayFinishOfSubFrame"
                                                    value="@if(!empty($set)){{$set->SprayFinishOfSubFrameManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="SprayFinishOfSubFrameMachineMinutes" class="form-control SprayFinishOfSubFrame"
                                                    value="@if(!empty($set)){{$set->SprayFinishOfSubFrameMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('SprayFinishOfSubFrame', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control SprayFinishOfSubFrame" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control SprayFinishOfSubFrame"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='PallettingPackaging' name='PallettingPackaging' @if(!empty($set->PallettingPackaging) && $set->PallettingPackaging== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Palletting & Packaging</span></h5>
                                        </div>
                                        <input type="hidden" class="PallettingPackaging" name="type[]" value="PallettingPackaging">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="PallettingPackagingManMinutes" class="form-control PallettingPackaging"
                                                    value="@if(!empty($set)){{$set->PallettingPackagingManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="PallettingPackagingMachineMinutes" class="form-control PallettingPackaging"
                                                    value="@if(!empty($set)){{$set->PallettingPackagingMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('PallettingPackaging', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control PallettingPackaging" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control PallettingPackaging"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='LoadingOfLorry' name='LoadingOfLorry' @if(!empty($set->LoadingOfLorry) && $set->LoadingOfLorry== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>Loading of Lorry</span></h5>
                                        </div>
                                        <input type="hidden" class="LoadingOfLorry" name="type[]" value="LoadingOfLorry">
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="LoadingOfLorryManMinutes" class="form-control LoadingOfLorry"
                                                    value="@if(!empty($set)){{$set->LoadingOfLorryManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="LoadingOfLorryMachineMinutes" class="form-control LoadingOfLorry"
                                                    value="@if(!empty($set)){{$set->LoadingOfLorryMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                        @php
                                        $data = getMyLaborCost('LoadingOfLorry', $set->genLaborCost ?? '');
                                        @endphp
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                            <label>Per Man<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  onkeydown="if(event.key==='.'){event.preventDefault();}" name="labour_cost_per_man[]" class="form-control LoadingOfLorry" value="@if(isset($data->labour_cost_per_man)){{$data->labour_cost_per_man}}@else{{ $bomSetting->labour_cost_per_man }}@endif">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Per Machine<span class="text-danger">*</span></label>
                                                <input type="number" pattern="[0-9]+(\.[0-9][0-9]?)?" step="0.01" min="0"  name="labour_cost_per_machine[]" class="form-control LoadingOfLorry"
                                                    value="@if(isset($data->labour_cost_per_machine)){{$data->labour_cost_per_machine}}@else{{ $bomSetting->labour_cost_per_machine }}@endif">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{--  <div class="col-md-6">
                                <div class="field-name">
                                    <input type='checkbox' class='check-btn'  id='VGroves' name='VGroves' @if(!empty($set->VGroves) && $set->VGroves== 1){{ 'checked' }}@endif>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <h5 class="fieldset" style="margin-top: 10px"><span>V Groves</span></h5>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Man Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="VGrovesManMinutes" class="form-control VGroves"
                                                    value="@if(!empty($set)){{$set->VGrovesManMinutes}}@endif"
                                                    required>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="position-relative form-group">
                                                <label>Machine Minute</label>
                                                <input type="number" min="0" onkeydown="if(event.key==='.'){event.preventDefault();}" name="VGrovesMachineMinutes" class="form-control VGroves"
                                                    value="@if(!empty($set)){{$set->VGrovesMachineMinutes}}@endif" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>  --}}

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
@section("js")
<script>
    $(window).on('load',function(){
        checked();
    });

    $('#check_all').click(function() {
        var checkId = ['DoorLeafFacingVaneer', 'DoorLeafFacingKraftPaper', 'DoorLeafFacingLaminate', 'DoorLeafFacingPVC', 'DoorLeafFinishPrimed','DoorLeafFinishPainted','DoorLeafFinishLacquered','FrameFinishPrimed','FrameFinishPainted','FrameFinishLacqured','ExtLiner', 'ExtLinerandFrameFinish', 'ExtLinerandFrameFinishPainted', 'ExtLinerandFrameFinishLacqured', 'VisionPanel2','DoorLeafFinishPrimed2','DoorLeafFinishPainted2','DoorLeafFinishLacquered2','VisionPanel','VisionPanelandFireRatingFD30','VisionPanelandFireRatingFD60', 'VisionPanelandFireRating2FD30', 'VisionPanelandFireRating2FD60', 'DecorativeGroves','DecorativeGrovesLeaf2', 'DoorsetTypeSD','DoorsetTypeSD2','DoorsetTypeDD','DoorsetTypeDD2','OverpanelFanlight','OverpanelFanlightGlazing','SideLight','SideLight2', 'SideLightGlazing','SideLight2Glazing', 'MachiningOfDoorFrame', 'FittingOfIntumescentToFrame','FittingOfIntumescentToFrameFD60', 'HingeAssembley','HingeAssembley','LocksetAssembley','PlotLabelDoorsets','FrameAssembley','PalletPackaging','DoorLeafProtectionPlasticSleeve','DoorLeafProtectionPlasticSleeveFD60','LeafSizing','LeafLiping','LeafCalibration','PaintPrep', 'LabourProcessForScallopedFrame', 'LabourAssemblyFor4SidedFrame','MachiningOfScreenframe','MachiningOfGlazingBead','MachiningOfTransom','MachiningOfSubFrame','CuttingOfScreenframe','CuttingOfGlazingBead','CuttingOfTransom','CuttingOfSubFrame','ScreenAssembley','TransomAssembley','SubFrameAssembley','FittingOfGlass','FittingOfGlazingSystem','FittingOfGlazingBead', 'SprayFinishOf','SprayFinishOfScreenframe','SprayFinishGlazingBead','SprayFinishOfTransom','SprayFinishOfSubFrame','PallettingPackaging','LoadingOfLorry','labour_cost_per_man[]','labour_cost_per_machine[]','type[]'];
        for(i = 0; i < checkId.length; i++){
            if ($(this).is(':checked')) {
                $('#' + checkId[i]).prop('checked', true);
                $('#' + checkId[i]).val('1');
                $('.' + checkId[i]).attr('disabled',false);
                $('.' + checkId[i]).attr('required',true);
            } else {
                $('#' + checkId[i]).prop('checked', false);
                $('#' + checkId[i]).val('0');
                $('.' + checkId[i]).attr('disabled',true);
                $('.' + checkId[i]).attr('required',false);
                $('.' + checkId[i]).val('');
            }
        }
    });

    $('.check-btn').click(function(){
        checked();
    });

    function checked(){
        var checkId = ['DoorLeafFacingVaneer', 'DoorLeafFacingKraftPaper', 'DoorLeafFacingLaminate', 'DoorLeafFacingPVC', 'DoorLeafFinishPrimed','DoorLeafFinishPainted','DoorLeafFinishLacquered','FrameFinishPrimed','FrameFinishPainted','FrameFinishLacqured','ExtLiner', 'ExtLinerandFrameFinish', 'ExtLinerandFrameFinishPainted', 'ExtLinerandFrameFinishLacqured', 'VisionPanel2','DoorLeafFinishPrimed2','DoorLeafFinishPainted2','DoorLeafFinishLacquered2','VisionPanel','VisionPanelandFireRatingFD30','VisionPanelandFireRatingFD60', 'VisionPanelandFireRating2FD30', 'VisionPanelandFireRating2FD60', 'DecorativeGroves','DecorativeGrovesLeaf2', 'DoorsetTypeSD','DoorsetTypeSD2','DoorsetTypeDD','DoorsetTypeDD2','OverpanelFanlight','OverpanelFanlightGlazing','SideLight','SideLight2', 'SideLightGlazing','SideLight2Glazing', 'MachiningOfDoorFrame', 'FittingOfIntumescentToFrame','FittingOfIntumescentToFrameFD60', 'HingeAssembley','HingeAssembleyLeafandHalfDD','LocksetAssembley','PlotLabelDoorsets','FrameAssembley','PalletPackaging','DoorLeafProtectionPlasticSleeve','DoorLeafProtectionPlasticSleeveFD60','LeafSizing','LeafLiping','LeafCalibration','PaintPrep','LabourProcessForScallopedFrame', 'LabourAssemblyFor4SidedFrame','MachiningOfScreenframe','MachiningOfGlazingBead','MachiningOfTransom','MachiningOfSubFrame','CuttingOfScreenframe','CuttingOfGlazingBead','CuttingOfTransom','CuttingOfSubFrame','ScreenAssembley','TransomAssembley','SubFrameAssembley','FittingOfGlass','FittingOfGlazingSystem','FittingOfGlazingBead','SprayFinishOf','SprayFinishOfScreenframe','SprayFinishGlazingBead','SprayFinishOfTransom','SprayFinishOfSubFrame','PallettingPackaging','LoadingOfLorry','labour_cost_per_man[]','labour_cost_per_machine[]','type[]'];
        for(i = 0; i < checkId.length; i++){
            if($('#' + checkId[i]).is(":checked")){
                $('#' + checkId[i]).val('1');
                $('.' + checkId[i]).attr('disabled',false);
                $('.' + checkId[i]).attr('required',true);
            }else{
                $('#' + checkId[i]).val('0');
                $('.' + checkId[i]).attr('disabled',true);
                $('.' + checkId[i]).attr('required',false);
                if($('.' + checkId[i]).attr('name') != 'type[]'){
                    $('.' + checkId[i]).val('');
                }
            }
        }
    };
</script>
@endsection
