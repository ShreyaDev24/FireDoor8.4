<div class="main-card mb-3 custom_card">
    <div>
        <div class="tab-content">
            <div class="card-header">
                <h5 class="card-title" style="margin-top: 10px">Screen Dimensions</h5><span></span>
            </div>
            <div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="SO Width mm">SO Width mm</label>
                            <input type="number" name="SOWidth" id="SOWidth" placeholder="Enter SO Width mm"
                                value="@if (isset($Item['SOWidth'])){{$Item['SOWidth']}}@else{{ '' }} @endif"
                                class="form-control change-event-calulation" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="SO Height mm">SO Height mm</label>
                            <input type="number" name="SOHeight" id="SOHeight" placeholder="Enter SO Height mm"
                                value="@if (isset($Item['SOHeight'])){{$Item['SOHeight']}}@else{{ '' }} @endif"
                                class="form-control change-event-calulation" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="SO Depth mm">SO Depth mm</label>
                            <input type="number" name="SODepth" id="SODepth" placeholder="Enter SO Depth mm"
                                value="@if (isset($Item['SODepth'])){{$Item['SODepth']}}@else{{ '' }} @endif"
                                class="form-control" >
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="GlazingBeadShape ">Glazing Bead Shape </label>
                            <select name="GlazingBeadShape" id="GlazingBeadShape" class="form-control">
                                <option value="">Select Glazing Bead Shape </option>
                                <option value="Square" @if(isset($Item['GlazingBeadShape']) && $Item['GlazingBeadShape'] == 'Square') selected @endif>Square</option>
                                <option value="Chamfer" @if(isset($Item['GlazingBeadShape']) && $Item['GlazingBeadShape'] == 'Chamfer') selected @endif>Chamfer</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="GlazingBeadHeight">Glazing Bead Height</label>
                            <input type="number" name="GlazingBeadHeight" placeholder="Glazing Bead Height" id="GlazingBeadHeight"
                                class="form-control" value="@if(isset($Item['GlazingBeadHeight'])){{$Item['GlazingBeadHeight']}}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="GlazingBeadWidth">Glazing Bead Width</label>
                            <input type="number" name="GlazingBeadWidth" placeholder="Glazing Bead Width" id="GlazingBeadWidth"
                                class="form-control" value="@if(isset($Item['GlazingBeadWidth'])){{$Item['GlazingBeadWidth']}}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group input-icons">
                            <label for="Glazing Bead Species">Glazing Bead Species</label>
                            <i class="fa fa-info icon" id="GlazingBeadMaterialIcon"></i>
                            <input type="text"  id="GlazingBeadMaterial" class="form-control bg-white"
                                value="@if(isset($Item['GlazingBeadMaterial'])){{$Item['GlazingBeadMaterial']}}@endif">
                            <input type="hidden" id="GlazingBeadMaterialNew" name="GlazingBeadMaterial"
                                value="@if(isset($Item['GlazingBeadMaterial'])){{$Item['GlazingBeadMaterial']}}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="GlazingSystem">Glazing System</label>
                            <select name="GlazingSystem" id="GlazingSystem" class="form-control " required>
                                <option value="">Select Single Pane</option>
                            </select>

                            <input type="hidden" placeholder="Glazing System" id="SelectedValue"
                                class="form-control" value="@if(isset($Item['GlazingSystem'])){{$Item['GlazingSystem']}}@endif">
                            <input type="hidden" id="SelectedValuehidden" value="@if(isset($Item['GlazingSystem'])){{$Item['GlazingSystem']}}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="GlazingSystemThickness">Glazing System Thickness</label>
                            <input type="number" name="GlazingSystemThickness" readonly placeholder="Glazing System Thickness" id="GlazingSystemThickness"
                                class="form-control" value="@if(isset($Item['GlazingSystemThickness'])){{$Item['GlazingSystemThickness']}}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="GlazingSystemFixingDetail">Glazing System Fixing Detail</label>
                            <input type="text" name="GlazingSystemFixingDetail" readonly placeholder="Glazing System Fixing Detail" id="GlazingSystemFixingDetail"
                                class="form-control" value="@if(isset($Item['GlazingSystemFixingDetail'])){{$Item['GlazingSystemFixingDetail']}}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="GlassLiner">Glass Liner</label>
                            <input type="text" name="GlassLiner" placeholder="Glass Liner" id="GlassLiner"
                                class="form-control" value="@if(isset($Item['GlassLiner'])){{$Item['GlassLiner']}}@endif">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
