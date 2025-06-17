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
                        <h6 class="card-title font-weight-bold" style="margin-top: 10px">Manufacturing Setting</h6>
                    </div>
                    <form action="{{route('storeDoorFrameConstruction')}}" method="post">
                        {{ csrf_field() }}
                            <input type="hidden" name="currencyUpdate" value="{{$users}}">

                            <div class="row">
                                <div class="h6 col-12 font-weight-bold">
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
                                <div class="h6 col-12 font-weight-bold">
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
                                <div class="h6 col-12 font-weight-bold">
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
                                <div class="h6 col-12 font-weight-bold">
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
                                <div class="h6 col-12 font-weight-bold">
                                    Hinge Location
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="hinge1Location">Hinge 1 Location (Min 120 mm, Max 200 mm) <span class="text-danger">*</span></label>
                                        <input type="number" name="hinge1Location" id="hinge1Location" class="form-control" placeholder="Enter Width" value="@if(!empty($hinge_location)){{$hinge_location->hinge1Location}}@endif" min="120" max="200">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="hinge2Location">Hinge 2 Location (Min 220 mm) <span class="text-danger">*</span></label>
                                        <input type="number" name="hinge2Location" id="hinge2Location" class="form-control" placeholder="Enter Width" value="@if(!empty($hinge_location)){{$hinge_location->hinge2Location}}@endif" min="220">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="hinge3Location">Hinge 3 Location (Min 150 mm, Max 300 mm) <span class="text-danger">*</span></label>
                                        <input type="number" name="hinge3Location" id="hinge3Location" class="form-control" placeholder="Enter Width" value="@if(!empty($hinge_location)){{$hinge_location->hinge3Location}}@endif" min="150" max="300">
                                    </div>
                                </div>
                                <div class="col-md-6 ">
                                    <div class="position-relative form-group d-flex">
                                        <label for="hingeCenterCheck">Hinge Center</label>
                                        <input type="checkbox" name="hingeCenterCheck" id="hingeCenterCheck" class="change-event-calulation form-control" style="margin: 2px -4px 10px 12px;border: 1px solid rgb(206, 212, 218);display: inline-block;height: 15px;width: 15px;" value="1" @if(!empty($hinge_location->hingeCenterCheck) && $hinge_location->hingeCenterCheck == 1){{'checked'}}@endif>
                                    </div>
                                </div>
                            </div>

                           <div class="row">
                                <div class="h6 col-12 font-weight-bold">DOOR FRAME CONSTRUCTION SETTINGS</div>

                                <!-- HALF LIPPED JOINT -->
                                <div class="col-12 font-weight-bold">HALF LIPPED JOINT</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="doorFrame_halfLippedWidth">FRAME HEAD WIDTH</label>
                                        <input type="number" name="doorFrame_halfLippedWidth" id="doorFrame_halfLippedWidth" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['DoorFrame.HalfLipped'])){{$allSettings['DoorFrame.HalfLipped']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="doorFrame_halfLippedHeight">FRAME HEAD HEIGHT</label>
                                        <input type="number" name="doorFrame_halfLippedHeight" id="doorFrame_halfLippedHeight" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['DoorFrame.HalfLipped'])){{$allSettings['DoorFrame.HalfLipped']->Height}}@endif">
                                    </div>
                                </div>

                                <!-- MITRE JOINT -->
                                <div class="col-12 font-weight-bold">MITRE JOINT</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="doorFrame_mitreWidth">FRAME HEAD WIDTH</label>
                                        <input type="number" name="doorFrame_mitreWidth" id="doorFrame_mitreWidth" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['DoorFrame.Mitre'])){{$allSettings['DoorFrame.Mitre']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="doorFrame_mitreHeight">FRAME HEAD HEIGHT</label>
                                        <input type="number" name="doorFrame_mitreHeight" id="doorFrame_mitreHeight" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['DoorFrame.Mitre'])){{$allSettings['DoorFrame.Mitre']->Height}}@endif">
                                    </div>
                                </div>

                                <!-- MORTICE AND TENON JOINT 1 -->
                                <div class="col-12 font-weight-bold">MORTICE AND TENON JOINT</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="doorFrame_mortice1Width">FRAME HEAD WIDTH</label>
                                        <input type="number" name="doorFrame_mortice1Width" id="doorFrame_mortice1Width" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['DoorFrame.Mortice1'])){{$allSettings['DoorFrame.Mortice1']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="doorFrame_mortice1Height">FRAME HEAD HEIGHT</label>
                                        <input type="number" name="doorFrame_mortice1Height" id="doorFrame_mortice1Height" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['DoorFrame.Mortice1'])){{$allSettings['DoorFrame.Mortice1']->Height}}@endif">
                                    </div>
                                </div>

                                <!-- MORTICE AND TENON JOINT 2 -->
                                <div class="col-12 font-weight-bold">Butt Joint</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="doorFrame_buttWidth">FRAME HEAD WIDTH</label>
                                        <input type="number" name="doorFrame_buttWidth" id="doorFrame_buttWidth" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['DoorFrame.Butt'])){{$allSettings['DoorFrame.Butt']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="doorFrame_buttHeight">FRAME HEAD HEIGHT</label>
                                        <input type="number" name="doorFrame_buttHeight" id="doorFrame_buttHeight" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['DoorFrame.Butt'])){{$allSettings['DoorFrame.Butt']->Height}}@endif">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="h6 col-12 font-weight-bold">DOOR FRAME PLANT ON STOP CONSTRUCTION SETTINGS</div>

                                <!-- HALF LIPPED JOINT -->
                                <div class="col-12 font-weight-bold">HALF LIPPED JOINT</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="plantOn_halfLippedWidth">PLANT ON STOP HEAD/BOTTOM WIDTH</label>
                                        <input type="number" name="plantOn_halfLippedWidth" id="plantOn_halfLippedWidth" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['PlantOn.HalfLipped'])){{$allSettings['PlantOn.HalfLipped']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="plantOn_halfLippedHeight">PLANT ON STOP HEAD/BOTTOM HEIGHT</label>
                                        <input type="number" name="plantOn_halfLippedHeight" id="plantOn_halfLippedHeight" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['PlantOn.HalfLipped'])){{$allSettings['PlantOn.HalfLipped']->Height}}@endif">
                                    </div>
                                </div>

                                <!-- MITRE JOINT -->
                                <div class="col-12 font-weight-bold">MITRE JOINT</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="plantOn_mitreWidth">PLANT ON STOP HEAD/BOTTOM WIDTH</label>
                                        <input type="number" name="plantOn_mitreWidth" id="plantOn_mitreWidth" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['PlantOn.Mitre'])){{$allSettings['PlantOn.Mitre']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="plantOn_mitreHeight">PLANT ON STOP HEAD/BOTTOM HEIGHT</label>
                                        <input type="number" name="plantOn_mitreHeight" id="plantOn_mitreHeight" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['PlantOn.Mitre'])){{$allSettings['PlantOn.Mitre']->Height}}@endif">
                                    </div>
                                </div>

                                <!-- MORTICE AND TENON JOINT 1 -->
                                <div class="col-12 font-weight-bold">MORTICE AND TENON JOINT</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="plantOn_mortice1Width">PLANT ON STOP HEAD/BOTTOM WIDTH</label>
                                        <input type="number" name="plantOn_mortice1Width" id="plantOn_mortice1Width" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['PlantOn.Mortice1'])){{$allSettings['PlantOn.Mortice1']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="plantOn_mortice1Height">PLANT ON STOP HEAD/BOTTOM HEIGHT</label>
                                        <input type="number" name="plantOn_mortice1Height" id="plantOn_mortice1Height" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['PlantOn.Mortice1'])){{$allSettings['PlantOn.Mortice1']->Height}}@endif">
                                    </div>
                                </div>

                                <!-- Butt Joint -->
                                <div class="col-12 font-weight-bold">Butt Joint</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="plantOn__buttWidth">PLANT ON STOP HEAD/BOTTOM WIDTH</label>
                                        <input type="number" name="plantOn__buttWidth" id="plantOn__buttWidth" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['PlantOn.Butt'])){{$allSettings['PlantOn.Butt']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="plantOn__buttHeight">PLANT ON STOP HEAD/BOTTOM HEIGHT</label>
                                        <input type="number" name="plantOn__buttHeight" id="plantOn__buttHeight" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['PlantOn.Butt'])){{$allSettings['PlantOn.Butt']->Height}}@endif">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="h6 col-12 font-weight-bold">SIDE LIGHT FRAME CONSTRUCTION SETTINGS</div>

                                <!-- HALF LIPPED JOINT -->
                                <div class="col-12 font-weight-bold">HALF LIPPED JOINT</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="sideLight_halfLippedWidth">FRAME HEAD WIDTH</label>
                                        <input type="number" name="sideLight_halfLippedWidth" id="sideLight_halfLippedWidth" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['SideLight.HalfLipped'])){{$allSettings['SideLight.HalfLipped']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="sideLight_halfLippedHeight">FRAME HEAD HEIGHT</label>
                                        <input type="number" name="sideLight_halfLippedHeight" id="sideLight_halfLippedHeight" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['SideLight.HalfLipped'])){{$allSettings['SideLight.HalfLipped']->Height}}@endif">
                                    </div>
                                </div>

                                <!-- MITRE JOINT -->
                                <div class="col-12 font-weight-bold">MITRE JOINT</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="sideLight_mitreWidth">FRAME HEAD WIDTH</label>
                                        <input type="number" name="sideLight_mitreWidth" id="sideLight_mitreWidth" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['SideLight.Mitre'])){{$allSettings['SideLight.Mitre']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="sideLight_mitreHeight">FRAME HEAD HEIGHT</label>
                                        <input type="number" name="sideLight_mitreHeight" id="sideLight_mitreHeight" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['SideLight.Mitre'])){{$allSettings['SideLight.Mitre']->Height}}@endif">
                                    </div>
                                </div>

                                <!-- MORTICE AND TENON JOINT 1 -->
                                <div class="col-12 font-weight-bold">MORTICE AND TENON JOINT</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="sideLight_mortice1Width">FRAME HEAD WIDTH</label>
                                        <input type="number" name="sideLight_mortice1Width" id="sideLight_mortice1Width" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['SideLight.Mortice1'])){{$allSettings['SideLight.Mortice1']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="sideLight_mortice1Height">FRAME HEAD HEIGHT</label>
                                        <input type="number" name="sideLight_mortice1Height" id="sideLight_mortice1Height" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['SideLight.Mortice1'])){{$allSettings['SideLight.Mortice1']->Height}}@endif">
                                    </div>
                                </div>

                                <!-- Butt Joint -->
                                <div class="col-12 font-weight-bold">Butt Joint</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="sideLight_buttWidth">FRAME HEAD WIDTH</label>
                                        <input type="number" name="sideLight_buttWidth" id="sideLight_buttWidth" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['SideLight.Butt'])){{$allSettings['SideLight.Butt']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="sideLight_buttHeight">FRAME HEAD HEIGHT</label>
                                        <input type="number" name="sideLight_buttHeight" id="sideLight_buttHeight" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['SideLight.Butt'])){{$allSettings['SideLight.Butt']->Height}}@endif">
                                    </div>
                                </div>
                            </div>


                            <div class="row">
                                <div class="h6 col-12 font-weight-bold">
                                    FANLIGHT / OVER PANEL FRAME CONSTRUCTION
                                </div>

                                <!-- HALF LIPPED JOINT -->
                                <div class="col-12 font-weight-bold">HALF LIPPED JOINT</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="fanlightHalfLippedWidth">FRAME HEAD WIDTH</label>
                                        <input type="number" name="fanlightHalfLippedWidth" id="fanlightHalfLippedWidth" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['Fanlight.HalfLipped'])){{$allSettings['Fanlight.HalfLipped']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="fanlightHalfLippedHeight">FRAME HEAD HEIGHT</label>
                                        <input type="number" name="fanlightHalfLippedHeight" id="fanlightHalfLippedHeight" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['Fanlight.HalfLipped'])){{$allSettings['Fanlight.HalfLipped']->Height}}@endif">
                                    </div>
                                </div>

                                <!-- MITRE JOINT -->
                                <div class="col-12 font-weight-bold">MITRE JOINT</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="fanlightMitreWidth">FRAME HEAD WIDTH</label>
                                        <input type="number" name="fanlightMitreWidth" id="fanlightMitreWidth" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['Fanlight.Mitre'])){{$allSettings['Fanlight.Mitre']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="fanlightMitreHeight">FRAME HEAD HEIGHT</label>
                                        <input type="number" name="fanlightMitreHeight" id="fanlightMitreHeight" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['Fanlight.Mitre'])){{$allSettings['Fanlight.Mitre']->Height}}@endif">
                                    </div>
                                </div>

                                <!-- MORTICE AND TENON JOINT 1 -->
                                <div class="col-12 font-weight-bold">MORTICE AND TENON JOINT</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="fanlightMortice1Width">FRAME HEAD WIDTH</label>
                                        <input type="number" name="fanlightMortice1Width" id="fanlightMortice1Width" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['Fanlight.Mortice1'])){{$allSettings['Fanlight.Mortice1']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="fanlightMortice1Height">FRAME HEAD HEIGHT</label>
                                        <input type="number" name="fanlightMortice1Height" id="fanlightMortice1Height" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['Fanlight.Mortice1'])){{$allSettings['Fanlight.Mortice1']->Height}}@endif">
                                    </div>
                                </div>

                                <!-- Butt Joint -->
                                <div class="col-12 font-weight-bold">Butt Joint</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="fanlight_buttWidth">FRAME HEAD WIDTH</label>
                                        <input type="number" name="fanlight_buttWidth" id="fanlight_buttWidth" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['Fanlight.Butt'])){{$allSettings['Fanlight.Butt']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="fanlight_buttHeight">FRAME HEAD HEIGHT</label>
                                        <input type="number" name="fanlight_buttHeight" id="fanlight_buttHeight" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['Fanlight.Butt'])){{$allSettings['Fanlight.Butt']->Height}}@endif">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                             <!-- VP GLASS SIZE ADJUSTMENT -->
                                <div class="h6 col-12 mt-4  font-weight-bold">
                                    VP GLASS SIZE ADJUSTMENT
                                </div>

                                <!-- NRF or FD30 -->
                                <div class="col-12 font-weight-bold">VP GLASS SIZE (NRF or FD30)<span class="text-muted">CAN BE - ONLY</span></div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="vpWidthNRF">VP GLASS WIDTH NRF OR FD30 <small class="text-muted">CAN BE - ONLY</small></label>
                                        <input type="number" name="vpWidthNRF" id="vpWidthNRF" class="form-control" placeholder="Enter Width" min="-5" max="0" value="@if(!empty($allSettings['VisionPanel.NRF'])){{$allSettings['VisionPanel.NRF']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="vpHeightNRF">VP GLASS HEIGHT NRF OR FD30 <small class="text-muted">CAN BE - ONLY</small></label>
                                        <input type="number" name="vpHeightNRF" id="vpHeightNRF" class="form-control" placeholder="Enter Height" min="-5" max="0" value="@if(!empty($allSettings['VisionPanel.NRF'])){{$allSettings['VisionPanel.NRF']->Height}}@endif">
                                    </div>
                                </div>

                                <!-- FD60 -->
                                <div class="col-12 font-weight-bold">VP GLASS SIZE (FD60)<span class="text-muted">CAN BE - ONLY</span></div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="vpWidthFD60">VP GLASS WIDTH FD60 <small class="text-muted">CAN BE - ONLY</small></label>
                                        <input type="number" name="vpWidthFD60" id="vpWidthFD60" class="form-control" placeholder="Enter Width" min="-10" max="0" value="@if(!empty($allSettings['VisionPanel.FD60'])){{$allSettings['VisionPanel.FD60']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="vpHeightFD60">VP GLASS HEIGHT FD60 <small class="text-muted">CAN BE - ONLY</small></label>
                                        <input type="number" name="vpHeightFD60" id="vpHeightFD60" class="form-control" placeholder="Enter Height" min="-10" max="0" value="@if(!empty($allSettings['VisionPanel.FD60'])){{$allSettings['VisionPanel.FD60']->Height}}@endif">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="h6 col-12  font-weight-bold">
                                    SIDE LIGHT 1 & 2 GLASS SIZE ADJUSTMENT
                                </div>

                                <!-- SIDE LIGHT GLASS WIDTH - NRF or FD30 -->
                                <div class="col-12 font-weight-bold">SIDE LIGHT GLASS WIDTH NRF OR FD30 - CAN BE ONLY</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="sideLightWidthFD30">SIDE LIGHT GLASS WIDTH</label>
                                        <input type="number" max="0" name="sideLightWidthFD30" id="sideLightWidthFD30" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['SideLightFD.FD30'])){{$allSettings['SideLightFD.FD30']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="sideLightHeightFD30">SIDE LIGHT GLASS HEIGHT</label>
                                        <input type="number" max="0" name="sideLightHeightFD30" id="sideLightHeightFD30" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['SideLightFD.FD30'])){{$allSettings['SideLightFD.FD30']->Height}}@endif">
                                    </div>
                                </div>

                                <!-- SIDE LIGHT GLASS WIDTH - FD60 -->
                                <div class="col-12 font-weight-bold">SIDE LIGHT GLASS WIDTH FD60 - CAN BE ONLY</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="sideLightWidthFD60">SIDE LIGHT GLASS WIDTH</label>
                                        <input type="number" max="0" name="sideLightWidthFD60" id="sideLightWidthFD60" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['SideLightFD.FD60'])){{$allSettings['SideLightFD.FD60']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="sideLightHeightFD60">SIDE LIGHT GLASS HEIGHT</label>
                                        <input type="number" max="0" name="sideLightHeightFD60" id="sideLightHeightFD60" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['SideLightFD.FD60'])){{$allSettings['SideLightFD.FD60']->Height}}@endif">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="h6 col-12 font-weight-bold">
                                    FANLIGHT GLASS / OP LEAF ADJUSTMENT
                                </div>

                                <!-- NRF or FD30 -->
                                <div class="col-12 font-weight-bold">FANLIGHT GLASS OR OP LEAF SIZE (NRF OR FD30)</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="fanlightNrfWidth">FANLIGHT GLASS OR OP LEAF SIZE WIDTH NRF OR FD30 <span class="text-muted">CAN BE - ONLY</span></label>
                                        <input type="number" max="0" name="fanlightNrfWidth" id="fanlightNrfWidth" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['FanlightSize.NRF'])){{$allSettings['FanlightSize.NRF']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="fanlightNrfHeight">FANLIGHT GLASS OR OP LEAF SIZE HEIGHT NRF OR FD30 <span class="text-muted">CAN BE - ONLY</span></label>
                                        <input type="number" max="0" name="fanlightNrfHeight" id="fanlightNrfHeight" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['FanlightSize.NRF'])){{$allSettings['FanlightSize.NRF']->Height}}@endif">
                                    </div>
                                </div>

                                <!-- FD60 -->
                                <div class="col-12 font-weight-bold">FANLIGHT GLASS OR OP LEAF SIZE (FD60)</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="fanlightFd60Width">FANLIGHT GLASS OR OP LEAF SIZE WIDTH FD60 <span class="text-muted">CAN BE - ONLY</span></label>
                                        <input type="number" max="0" name="fanlightFd60Width" id="fanlightFd60Width" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['FanlightSize.FD60'])){{$allSettings['FanlightSize.FD60']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="fanlightFd60Height">FANLIGHT GLASS OR OP LEAF SIZE HEIGHT FD60 <span class="text-muted">CAN BE - ONLY</span></label>
                                        <input type="number" max="0" name="fanlightFd60Height" id="fanlightFd60Height" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['FanlightSize.FD60'])){{$allSettings['FanlightSize.FD60']->Height}}@endif">
                                    </div>
                                </div>

                                <!-- VP GLASSING BEAD SIZE ADJUSTMENT -->
                                <div class="h6 col-12 mt-4 font-weight-bold">
                                    VP GLASSING BEAD SIZE ADJUSTMENT
                                </div>

                                <!-- NRF or FD30 -->
                                <div class="col-12 font-weight-bold">VP GLASSING BEAD SIZE (NRF OR FD30)</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="vpBeadNrfWidth">VP GLASSING BEAD WIDTH NRF OR FD30 <span class="text-muted">CAN BE - ONLY</span></label>
                                        <input type="number" max="0" name="vpBeadNrfWidth" id="vpBeadNrfWidth" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['VPBead.NRF'])){{$allSettings['VPBead.NRF']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="vpBeadNrfHeight">VP GLASSING BEAD HEIGHT NRF OR FD30 <span class="text-muted">CAN BE - ONLY</span></label>
                                        <input type="number" max="0" name="vpBeadNrfHeight" id="vpBeadNrfHeight" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['VPBead.NRF'])){{$allSettings['VPBead.NRF']->Height}}@endif">
                                    </div>
                                </div>

                                <!-- FD60 -->
                                <div class="col-12 font-weight-bold">VP GLASSING BEAD SIZE (FD60)</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="vpBeadFd60Width">VP GLASSING BEAD WIDTH FD60 <span class="text-muted">CAN BE - ONLY</span></label>
                                        <input type="number" max="0" name="vpBeadFd60Width" id="vpBeadFd60Width" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['VPBead.FD60'])){{$allSettings['VPBead.FD60']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="vpBeadFd60Height">VP GLASSING BEAD HEIGHT FD60 <span class="text-muted">CAN BE - ONLY</span></label>
                                        <input type="number" max="0" name="vpBeadFd60Height" id="vpBeadFd60Height" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['VPBead.FD60'])){{$allSettings['VPBead.FD60']->Height}}@endif">
                                    </div>
                                </div>

                                <!-- SIDE LIGHT 1 & 2 GLASSING BEAD SIZE ADJUSTMENT -->
                                <div class="h6 col-12 mt-4 font-weight-bold">
                                    SIDE LIGHT 1 & 2 GLASSING BEAD SIZE ADJUSTMENT
                                </div>

                                <!-- NRF or FD30 -->
                                <div class="col-12 font-weight-bold">SIDE LIGHT 1 & 2 GLASSING BEAD SIZE (NRF OR FD30)</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="sideBeadNrfWidth">SIDE LIGHT GLASSING BEAD WIDTH NRF OR FD30 <span class="text-muted">CAN BE - ONLY</span></label>
                                        <input type="number" max="0" name="sideBeadNrfWidth" id="sideBeadNrfWidth" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['SideBead.NRF'])){{$allSettings['SideBead.NRF']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="sideBeadNrfHeight">SIDE LIGHT GLASSING BEAD HEIGHT NRF OR FD30 <span class="text-muted">CAN BE - ONLY</span></label>
                                        <input type="number" max="0" name="sideBeadNrfHeight" id="sideBeadNrfHeight" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['SideBead.NRF'])){{$allSettings['SideBead.NRF']->Height}}@endif">
                                    </div>
                                </div>

                                <!-- FD60 -->
                                <div class="col-12 font-weight-bold">SIDE LIGHT 1 & 2 GLASSING BEAD SIZE (FD60)</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="sideBeadFd60Width">SIDE LIGHT GLASSING BEAD WIDTH FD60 <span class="text-muted">CAN BE - ONLY</span></label>
                                        <input type="number" max="0" name="sideBeadFd60Width" id="sideBeadFd60Width" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['SideBead.FD60'])){{$allSettings['SideBead.FD60']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="sideBeadFd60Height">SIDE LIGHT GLASSING BEAD HEIGHT FD60 <span class="text-muted">CAN BE - ONLY</span></label>
                                        <input type="number" max="0" name="sideBeadFd60Height" id="sideBeadFd60Height" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['SideBead.FD60'])){{$allSettings['SideBead.FD60']->Height}}@endif">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="h6 col-12 font-weight-bold">
                                    FANLIGHT / OP GLAZING BEAD ADJUSTMENT
                                </div>

                                <!-- NRF OR FD30 -->
                                <div class="col-12 font-weight-bold">FANLIGHT / OP GLASSING BEAD SIZE - NRF OR FD30</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="fanlightNRFWidth">FANLIGHT / OP GLASSING BEAD SIZE WIDTH NRF OR FD30 <br> <small class="text-muted">CAN BE - ONLY</small></label>
                                        <input type="number" max="0" name="fanlightNRFWidth" id="fanlightNRFWidth" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['FanlightBead.NRF'])){{$allSettings['FanlightBead.NRF']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="fanlightNRFHeight">FANLIGHT / OP GLASSING BEAD SIZE HEIGHT NRF OR FD30 <br> <small class="text-muted">CAN BE - ONLY</small></label>
                                        <input type="number" max="0" name="fanlightNRFHeight" id="fanlightNRFHeight" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['FanlightBead.NRF'])){{$allSettings['FanlightBead.NRF']->Height}}@endif">
                                    </div>
                                </div>

                                <!-- FD60 -->
                                <div class="col-12 font-weight-bold">FANLIGHT / OP GLASSING BEAD SIZE - FD60</div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="fanlightFD60Width">FANLIGHT / OP GLASSING BEAD SIZE WIDTH FD60 <br> <small class="text-muted">CAN BE - ONLY</small></label>
                                        <input type="number" max="0" name="fanlightFD60Width" id="fanlightFD60Width" class="form-control" placeholder="Enter Width" value="@if(!empty($allSettings['FanlightBead.FD60'])){{$allSettings['FanlightBead.FD60']->Width}}@endif">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="position-relative form-group">
                                        <label for="fanlightFD60Height">FANLIGHT / OP GLASSING BEAD SIZE HEIGHT FD60 <br> <small class="text-muted">CAN BE - ONLY</small></label>
                                        <input type="number" max="0" name="fanlightFD60Height" id="fanlightFD60Height" class="form-control" placeholder="Enter Height" value="@if(!empty($allSettings['FanlightBead.FD60'])){{$allSettings['FanlightBead.FD60']->Height}}@endif">
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
@section("script_section")
    <script>
        document.querySelectorAll('input[type="number"][max="0"]').forEach(function(input) {
            input.addEventListener('input', function () {
                if (this.value > 0) this.value = 0;
            });
        });
    </script>

@endsection
