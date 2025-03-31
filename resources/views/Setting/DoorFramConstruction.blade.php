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
                        <h5 class="card-title" style="margin-top: 10px">Door Frame Construction Setting</h5>
                    </div>
                    <form action="{{route('storeDoorFrameConstruction')}}" method="post">
                        {{ csrf_field() }}
                            <input type="hidden" name="currencyUpdate" value="{{$users}}">

                            <div class="row">
                                <div class="h6 col-12">
                                    Half Lapped Joint
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="width_half_lap">Frame Head Width <span class="text-danger">*</span></label>
                                        <input type="number" name="width_half_lap" id="width_half_lap" class="form-control" placeholder="Enter Width" value="@if(!empty($half_lap_joint)){{$half_lap_joint->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="height_half_lap">Frame Height<span class="text-danger">*</span></label>
                                        <input name="height_half_lap" id="height_half_lap" placeholder="Enter Height" type="number" class="form-control" value="@if(!empty($half_lap_joint)){{$half_lap_joint->Height}}@endif">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="h6 col-12">
                                    Mitre Joint
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="width_mitre">Frame Head Width <span class="text-danger">*</span></label>
                                        <input type="number" name="width_mitre" id="width_mitre" class="form-control" placeholder="Enter Width" value="@if(!empty($mitre_joint)){{$mitre_joint->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="height_mitre">Frame Height<span class="text-danger">*</span></label>
                                        <input name="height_mitre" id="height_mitre" placeholder="Enter Height" type="number" class="form-control" value="@if(!empty($mitre_joint)){{$mitre_joint->Height}}@endif">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="h6 col-12">
                                    Mortice & Tenon Joint
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="width_mortice">Frame Head Width <span class="text-danger">*</span></label>
                                        <input type="number" name="width_mortice" id="width_mortice" class="form-control" placeholder="Enter Width" value="@if(!empty($mortice_tenon_joint)){{$mortice_tenon_joint->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="height_mortice">Frame Height<span class="text-danger">*</span></label>
                                        <input name="height_mortice" id="height_mortice" placeholder="Enter Height" type="number" class="form-control" value="@if(!empty($mortice_tenon_joint)){{$mortice_tenon_joint->Height}}@endif">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="h6 col-12">
                                    Butt Joint
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="width_butt">Frame Head Width <span class="text-danger">*</span></label>
                                        <input type="number" name="width_butt" id="width_butt" class="form-control" placeholder="Enter Width" value="@if(!empty($butt_joint)){{$butt_joint->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="height_butt">Frame Height<span class="text-danger">*</span></label>
                                        <input name="height_butt" id="height_butt" placeholder="Enter Height" type="number" class="form-control" value="@if(!empty($butt_joint)){{$butt_joint->Height}}@endif">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="h6 col-12">
                                    Hinge Location
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="hinge1Location">Hinge 1 Location (Min 100 mm, Max 180 mm) <span class="text-danger">*</span></label>
                                        <input type="number" name="hinge1Location" id="hinge1Location" class="form-control" placeholder="Enter Width" value="@if(!empty($hinge_location)){{$hinge_location->hinge1Location}}@endif" min="100" max="180">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="hinge2Location">Hinge 2 Location (Min 200 mm) <span class="text-danger">*</span></label>
                                        <input type="number" name="hinge2Location" id="hinge2Location" class="form-control" placeholder="Enter Width" value="@if(!empty($hinge_location)){{$hinge_location->hinge2Location}}@endif" min="200">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="hinge3Location">Hinge 3 Location (Min 150 mm, Max 250 mm) <span class="text-danger">*</span></label>
                                        <input type="number" name="hinge3Location" id="hinge3Location" class="form-control" placeholder="Enter Width" value="@if(!empty($hinge_location)){{$hinge_location->hinge3Location}}@endif" min="150" max="250">
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="position-relative form-group d-flex">
                                        <label for="hingeCenterCheck">Hinge Center</label>
                                        <input type="checkbox" name="hingeCenterCheck" id="hingeCenterCheck" class="change-event-calulation form-control" style="margin: 2px -4px 10px 12px;border: 1px solid rgb(206, 212, 218);display: inline-block;height: 15px;width: 15px;" value="1" @if(!empty($hinge_location->hingeCenterCheck) && $hinge_location->hingeCenterCheck == 1){{'checked'}}@endif>
                                    </div>
                                </div>
                            </div>

                            <div class="form-btn">
                                <button type="submit" class="position-relative form-group btn-wide btn btn-success" style="margin-left: auto; float: right;">Update</button>
                            </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
