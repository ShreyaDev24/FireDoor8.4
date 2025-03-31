<div class="main-card mb-3 custom_card">
    <div>
        <div class="tab-content">
            <div class="card-header">
                <h5 class="card-title" style="margin-top: 10px">Transom </h5>
            </div>
            <div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Transom Quantity">Transom Quantity</label>
                            @php
                                $options = [0,1,2,3];
                            @endphp
                            <select name="TransomQuantity" id="TransomQuantity" class="form-control change-event-calulation" >
                                <option value="">Select Transom Quantity</option>
                                @foreach ($options as $option)
                                    <option value="{{ $option }}">
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Transom Type">Transom Type</label>
                            @php
                                $options = ['Combined','Back to Back','NA'];
                            @endphp
                            <select name="TransomType" id="TransomType" class="form-control" >
                                <option value="">Select Transom Type</option>
                                @foreach ($options as $option)
                                    <option value="{{ $option }}" @if (isset($Item['TransomType']) && $Item['TransomType'] == $option) selected @endif>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="TransomDepth">Transom Depth mm</label>
                            <input type="number" name="TransomDepth" id="TransomDepth" class="form-control" placeholder="Transom Depth" value="@if(isset($Item['TransomDepth'])){{$Item['TransomDepth']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group input-icons">
                            <label for="TransomMaterial">Transom Material</label>
                            <i class="fa fa-info icon" id="TransomMaterialIcon"></i>
                            <input type="text"  id="TransomMaterial" class="form-control bg-white"
                                value="@if(isset($Item['TransomMaterial'])){{$Item['TransomMaterial']}}@endif">
                            <input type="hidden" id="TransomMaterialNew" name="TransomMaterial"
                                value="@if(isset($Item['TransomMaterial'])){{$Item['TransomMaterial']}}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="TransomWidth1">Transom Width 1</label>
                            <input type="number" name="TransomWidth1" id="TransomWidth1" class="form-control" placeholder="Transom Width 1"
                                value="@if(isset($Item['TransomWidth1'])){{$Item['TransomWidth1']}}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Transom1Thickness">Transom 1 Thickness</label>
                            <input type="number" name="Transom1Thickness" id="Transom1Thickness" class="form-control change-event-calulation T1" placeholder="Transom 1 Thickness" readonly
                                value="@if(isset($Item['Transom1Thickness'])){{$Item['Transom1Thickness']}}@else{{ 0 }}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="TransomHeight1">Transom Height Point 1</label>
                            <input type="number" name="TransomHeight1" id="TransomHeight1" class="form-control change-event-calulation T1" placeholder="Transom Height 1" readonly
                                value="@if(isset($Item['TransomHeight1'])){{$Item['TransomHeight1']}}@else{{ 0 }}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Transom2Thickness">Transom 2 Thickness</label>
                            <input type="number" name="Transom2Thickness" id="Transom2Thickness" class="form-control change-event-calulation T2" placeholder="Transom 2 Thickness" readonly
                                value="@if(isset($Item['Transom2Thickness'])){{$Item['Transom2Thickness']}}@else{{ 0 }}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="TransomHeightPoint2">Transom Height Point 2</label>
                            <input type="number" name="TransomHeightPoint2" id="TransomHeightPoint2" class="form-control change-event-calulation T2" placeholder="Transom Height Point 2" readonly
                                value="@if(isset($Item['TransomHeightPoint2'])){{$Item['TransomHeightPoint2']}}@else{{ 0 }}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Transom3Thickness">Transom 3 Thickness</label>
                            <input type="number" name="Transom3Thickness" id="Transom3Thickness" class="form-control change-event-calulation T3" placeholder="Transom 3 Thickness" readonly
                                value="@if(isset($Item['Transom3Thickness'])){{$Item['Transom3Thickness']}}@else{{ 0 }}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="TransomHeightPoint3">Transom Height Point 3</label>
                            <input type="number" name="TransomHeightPoint3" id="TransomHeightPoint3" class="form-control change-event-calulation T3" placeholder="Transom 3 Thickness" readonly
                                value="@if(isset($Item['TransomHeightPoint3'])){{$Item['TransomHeightPoint3']}}@else{{ 0 }}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="TransomHeightPoint4">Transom Height Point 4</label>
                            <input type="number" name="TransomHeightPoint4" id="TransomHeightPoint4" class="form-control" placeholder="Transom Height Point 4" readonly
                                value="@if(isset($Item['TransomHeightPoint4'])){{$Item['TransomHeightPoint4']}}@else{{ 0 }}@endif">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
