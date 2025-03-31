<div class="main-card mb-3 custom_card">
    <div>
        <div class="tab-content">
            <div class="card-header">
                <h5 class="card-title" style="margin-top: 10px">Glass Pane</h5><span></span>
            </div>
            <div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Glass Pane 1 Width">A1 Glass Pane Width</label>
                            <input type="number" name="GlassPane1Width" readonly id="GlassPane1Width"
                                placeholder="Enter Glass Pane 1 Width"
                                value="@if(isset($Item['GlassPane1Width'])){{$Item['GlassPane1Width']}}@else{{ '' }}@endif"
                                class="form-control change-event-calulation">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Glass Pane 1 Height">A1 Glass Pane Height</label>
                            <input type="number" name="GlassPane1Height" readonly id="GlassPane1Height"
                                placeholder="Enter Glass Pane 1 Height"
                                value="@if(isset($Item['GlassPane1Height'])){{$Item['GlassPane1Height']}}@else{{ '' }} @endif"
                                class="form-control change-event-calulation">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Glass Pane 2 Width">A2 Glass Pane Width</label>
                            <input type="number" name="GlassPane2Width" readonly id="GlassPane2Width"
                                placeholder="Enter Glass Pane 2 Width"
                                value="@if (isset($Item['GlassPane2Width'])){{$Item['GlassPane2Width']}}@else{{ '' }} @endif"
                                class="form-control change-event-calulation">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Glass Pane 2 Height">A2 Glass Pane Height</label>
                            <input type="number" name="GlassPane2Height" readonly id="GlassPane2Height"
                                placeholder="Enter Glass Pane 2 Height"
                                value="@if (isset($Item['GlassPane2Height'])){{$Item['GlassPane2Height']}}@else{{ '' }} @endif"
                                class="form-control change-event-calulation">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Glass Pane 3 Width">A3 Glass Pane Width</label>
                            <input type="number" name="GlassPane3Width" readonly id="GlassPane3Width"
                                placeholder="Enter Glass Pane 3 Width"
                                value="@if (isset($Item['GlassPane3Width'])){{$Item['GlassPane3Width']}}@else{{ '' }} @endif"
                                class="form-control change-event-calulation">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Glass Pane 3 Height">A3 Glass Pane Height</label>
                            <input type="number" name="GlassPane3Height" readonly id="GlassPane3Height"
                                placeholder="Enter Glass Pane 3 Height"
                                value="@if (isset($Item['GlassPane3Height'])){{$Item['GlassPane3Height']}}@else{{ '' }} @endif"
                                class="form-control change-event-calulation">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Glass Pane 4 Width">A4 Glass Pane Width</label>
                            <input type="number" name="GlassPane4Width" readonly id="GlassPane4Width"
                                placeholder="Enter Glass Pane 4 Width"
                                value="@if (isset($Item['GlassPane4Width'])){{$Item['GlassPane4Width']}}@else{{ '' }} @endif"
                                class="form-control change-event-calulation">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Glass Pane 4 Height">A4 Glass Pane Height</label>
                            <input type="number" name="GlassPane4Height" readonly id="GlassPane4Height"
                                placeholder="Enter Glass Pane 4 Height"
                                value="@if (isset($Item['GlassPane4Height'])){{$Item['GlassPane4Height']}}@else{{ '' }} @endif"
                                class="form-control change-event-calulation">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 1 Width">B1 Glass Pane Width</label>
                                <input type="number" name="GlassPane5Width" readonly id="GlassPane5Width"
                                    placeholder="Enter Glass Pane 5 Width"
                                    value="@if(isset($Item['GlassPane5Width'])){{$Item['GlassPane5Width']}}@else{{ '' }}@endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 1 Height">B1 Glass Pane Height</label>
                                <input type="number" name="GlassPane5Height" readonly id="GlassPane5Height"
                                    placeholder="Enter Glass Pane 5 Height"
                                    value="@if(isset($Item['GlassPane5Height'])){{$Item['GlassPane5Height']}}@else{{ '' }} @endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 2 Width">B2 Glass Pane Width</label>
                                <input type="number" name="GlassPane6Width" readonly id="GlassPane6Width"
                                    placeholder="Enter Glass Pane 6 Width"
                                    value="@if (isset($Item['GlassPane6Width'])){{$Item['GlassPane6Width']}}@else{{ '' }} @endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 2 Height">B2 Glass Pane Height</label>
                                <input type="number" name="GlassPane6Height" readonly id="GlassPane6Height"
                                    placeholder="Enter Glass Pane 6 Height"
                                    value="@if (isset($Item['GlassPane6Height'])){{$Item['GlassPane6Height']}}@else{{ '' }} @endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 3 Width">B3 Glass Pane Width</label>
                                <input type="number" name="GlassPane7Width" readonly id="GlassPane7Width"
                                    placeholder="Enter Glass Pane 7 Width"
                                    value="@if (isset($Item['GlassPane7Width'])){{$Item['GlassPane7Width']}}@else{{ '' }} @endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 3 Height">B3 Glass Pane Height</label>
                                <input type="number" name="GlassPane7Height" readonly id="GlassPane7Height"
                                    placeholder="Enter Glass Pane 7 Height"
                                    value="@if (isset($Item['GlassPane7Height'])){{$Item['GlassPane7Height']}}@else{{ '' }} @endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 4 Width">B4 Glass Pane Width</label>
                                <input type="number" name="GlassPane8Width" readonly id="GlassPane8Width"
                                    placeholder="Enter Glass Pane 8 Width"
                                    value="@if (isset($Item['GlassPane8Width'])){{$Item['GlassPane8Width']}}@else{{ '' }} @endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 4 Height">B4 Glass Pane Height</label>
                                <input type="number" name="GlassPane8Height" readonly id="GlassPane8Height"
                                    placeholder="Enter Glass Pane 8 Height"
                                    value="@if (isset($Item['GlassPane8Height'])){{$Item['GlassPane8Height']}}@else{{ '' }} @endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 1 Width">C1 Glass Pane Width</label>
                                <input type="number" name="GlassPane9Width" readonly id="GlassPane9Width"
                                    placeholder="Enter Glass Pane 9 Width"
                                    value="@if(isset($Item['GlassPane9Width'])){{$Item['GlassPane9Width']}}@else{{ '' }}@endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 1 Height">C1 Glass Pane Height</label>
                                <input type="number" name="GlassPane9Height" readonly id="GlassPane9Height"
                                    placeholder="Enter Glass Pane 9 Height"
                                    value="@if(isset($Item['GlassPane9Height'])){{$Item['GlassPane9Height']}}@else{{ '' }} @endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 2 Width">C2 Glass Pane Width</label>
                                <input type="number" name="GlassPane10Width" readonly id="GlassPane10Width"
                                    placeholder="Enter Glass Pane 10 Width"
                                    value="@if (isset($Item['GlassPane10Width'])){{$Item['GlassPane10Width']}}@else{{ '' }} @endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 2 Height">C2 Glass Pane Height</label>
                                <input type="number" name="GlassPane10Height" readonly id="GlassPane10Height"
                                    placeholder="Enter Glass Pane 10 Height"
                                    value="@if (isset($Item['GlassPane10Height'])){{$Item['GlassPane10Height']}}@else{{ '' }} @endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 3 Width">C3 Glass Pane Width</label>
                                <input type="number" name="GlassPane11Width" readonly id="GlassPane11Width"
                                    placeholder="Enter Glass Pane 11 Width"
                                    value="@if (isset($Item['GlassPane11Width'])){{$Item['GlassPane11Width']}}@else{{ '' }} @endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 3 Height">C3 Glass Pane Height</label>
                                <input type="number" name="GlassPane11Height" readonly id="GlassPane11Height"
                                    placeholder="Enter Glass Pane 11 Height"
                                    value="@if (isset($Item['GlassPane11Height'])){{$Item['GlassPane11Height']}}@else{{ '' }} @endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 4 Width">C4 Glass Pane Width</label>
                                <input type="number" name="GlassPane12Width" readonly id="GlassPane12Width"
                                    placeholder="Enter Glass Pane 12 Width"
                                    value="@if (isset($Item['GlassPane12Width'])){{$Item['GlassPane12Width']}}@else{{ '' }} @endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 4 Height">C4 Glass Pane Height</label>
                                <input type="number" name="GlassPane12Height" readonly id="GlassPane12Height"
                                    placeholder="Enter Glass Pane 12 Height"
                                    value="@if (isset($Item['GlassPane12Height'])){{$Item['GlassPane12Height']}}@else{{ '' }} @endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>

                    </div>
                    <div class="form-row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 1 Width">D1 Glass Pane Width</label>
                                <input type="number" name="GlassPane13Width" readonly id="GlassPane13Width"
                                    placeholder="Enter Glass Pane 13 Width"
                                    value="@if(isset($Item['GlassPane13Width'])){{$Item['GlassPane13Width']}}@else{{ '' }}@endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 1 Height">D1 Glass Pane Height</label>
                                <input type="number" name="GlassPane13Height" readonly id="GlassPane13Height"
                                    placeholder="Enter Glass Pane 13 Height"
                                    value="@if(isset($Item['GlassPane13Height'])){{$Item['GlassPane13Height']}}@else{{ '' }}@endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 2 Width">D2 Glass Pane Width</label>
                                <input type="number" name="GlassPane14Width" readonly id="GlassPane14Width"
                                    placeholder="Enter Glass Pane 14 Width"
                                    value="@if(isset($Item['GlassPane14Width'])){{$Item['GlassPane14Width']}}@else{{ '' }}@endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 2 Height">D2 Glass Pane Height</label>
                                <input type="number" name="GlassPane14Height" readonly id="GlassPane14Height"
                                    placeholder="Enter Glass Pane 14 Height"
                                    value="@if(isset($Item['GlassPane14Height'])){{$Item['GlassPane14Height']}}@else{{ '' }}@endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 3 Width">D3 Glass Pane Width</label>
                                <input type="number" name="GlassPane15Width" readonly id="GlassPane15Width"
                                    placeholder="Enter Glass Pane 15 Width"
                                    value="@if(isset($Item['GlassPane15Width'])){{$Item['GlassPane15Width']}}@else{{ '' }}@endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 3 Height">D3 Glass Pane Height</label>
                                <input type="number" name="GlassPane15Height" readonly id="GlassPane15Height"
                                    placeholder="Enter Glass Pane 15 Height"
                                    value="@if(isset($Item['GlassPane15Height'])){{$Item['GlassPane15Height']}}@else{{ '' }}@endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 4 Width">D4 Glass Pane Width</label>
                                <input type="number" name="GlassPane16Width" readonly id="GlassPane16Width"
                                    placeholder="Enter Glass Pane 16 Width"
                                    value="@if(isset($Item['GlassPane16Width'])){{$Item['GlassPane16Width']}}@else{{ '' }}@endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Glass Pane 4 Height">D4 Glass Pane Height</label>
                                <input type="number" name="GlassPane16Height" readonly id="GlassPane16Height"
                                    placeholder="Enter Glass Pane 16 Height"
                                    value="@if(isset($Item['GlassPane16Height'])){{$Item['GlassPane16Height']}}@else{{ '' }}@endif"
                                    class="form-control change-event-calulation">
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
