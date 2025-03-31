<div class="main-card mb-3 custom_card">
    <div>
        <div class="tab-content">
            <div class="card-header">
                <h5 class="card-title" style="margin-top: 10px">Mullion </h5>
            </div>
            <div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Mullion Quantity">Mullion Quantity</label>
                            @php
                                $options = [0,1,2,3];
                            @endphp
                            <select name="MullionQuantity" id="MullionQuantity" class="form-control change-event-calulation" >
                                <option value="">Select Mullion Quantity</option>
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
                            <label for="Mullion Type">Mullion Type</label>
                            @php
                                $options = ['Combined','Back to Back','NA'];
                            @endphp
                            <select name="MullionType" id="MullionType" class="form-control" >
                                <option value="">Select Mullion Type</option>
                                @foreach ($options as $option)
                                    <option value="{{ $option }}" @if (isset($Item['MullionType']) && $Item['MullionType'] == $option) selected @endif>
                                        {{ $option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group input-icons">
                            <label for="MullionMaterial">Mullion Material</label>
                            <i class="fa fa-info icon" id="MullionMaterialIcon"></i>
                            <input type="text"  id="MullionMaterial" class="form-control bg-white"
                                value="@if(isset($Item['MullionMaterial'])){{$Item['MullionMaterial']}}@endif">
                            <input type="hidden" id="MullionMaterialNew" name="MullionMaterial"
                                value="@if(isset($Item['MullionMaterial'])){{$Item['MullionMaterial']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="MullionHeight1">Mullion Height Point 1</label>
                            <input type="number" name="MullionHeight1" id="MullionHeight1" class="form-control change-event-calulation" placeholder="Mullion Height 1"
                                value="@if(isset($Item['MullionHeight1'])){{$Item['MullionHeight1']}}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Mullion1Thickness">Mullion 1 Thickness</label>
                            <input type="number" name="Mullion1Thickness" id="Mullion1Thickness" class="form-control change-event-calulation M1" placeholder="Mullion 1 Thickness" readonly
                                value="@if(isset($Item['Mullion1Thickness'])){{$Item['Mullion1Thickness']}}@else{{ 0 }}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="MullionWidthPoint1">Mullion Width Point 1</label>
                            <input type="number" name="MullionWidthPoint1" id="MullionWidthPoint1" class="form-control change-event-calulation M1" placeholder="Mullion Width Point 1" readonly
                                value="@if(isset($Item['MullionWidthPoint1'])){{$Item['MullionWidthPoint1']}}@else{{ 0 }}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Mullion2Thickness">Mullion 2 Thickness</label>
                            <input type="number" name="Mullion2Thickness" id="Mullion2Thickness" class="form-control change-event-calulation M2" placeholder="Mullion 2 Thickness" readonly
                                value="@if(isset($Item['Mullion2Thickness'])){{$Item['Mullion2Thickness']}}@else{{ 0 }}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="MullionWidthPoint2">Mullion Width Point 2</label>
                            <input type="number" name="MullionWidthPoint2" id="MullionWidthPoint2" class="form-control change-event-calulation M2" placeholder="Mullion Width Point 2" readonly
                                value="@if(isset($Item['MullionWidthPoint2'])){{$Item['MullionWidthPoint2']}}@else{{ 0 }}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Mullion3Thickness">Mullion 3 Thickness</label>
                            <input type="number" name="Mullion3Thickness" id="Mullion3Thickness" class="form-control change-event-calulation M3" placeholder="Mullion 3 Thickness" readonly
                                value="@if(isset($Item['Mullion3Thickness'])){{$Item['Mullion3Thickness']}}@else{{ 0 }}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="MullionWidthPoint3">Mullion Width Point 3</label>
                            <input type="number" name="MullionWidthPoint3" id="MullionWidthPoint3" class="form-control change-event-calulation M3" placeholder="Mullion Width Point 3" readonly
                                value="@if(isset($Item['MullionWidthPoint3'])){{$Item['MullionWidthPoint3']}}@else{{ 0 }}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="MullionWidthPoint4">Mullion Width Point 4</label>
                            <input type="number" name="MullionWidthPoint4" id="MullionWidthPoint4" class="form-control" placeholder="Mullion Width Point 4" readonly
                                value="@if(isset($Item['MullionWidthPoint4'])){{$Item['MullionWidthPoint4']}}@else{{ 0 }}@endif">
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
