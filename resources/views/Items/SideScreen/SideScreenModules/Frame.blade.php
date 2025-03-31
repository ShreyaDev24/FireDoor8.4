<!-- Frame -->
<div class="main-card mb-3 custom_card">
    <div>
        <div class="tab-content">
            <div class="card-header">
                <h5 class="card-title" style="margin-top: 10px">Frame & Sub Frame</h5>
            </div>
            <div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="FrameThickness">Frame Thickness</label>
                            <input name="FrameThickness"  placeholder="Frame Thickness" id="FrameThickness"
                                min="0" type="number" class="form-control change-event-calulation"
                                value="@if(isset($Item['FrameThickness'])){{$Item['FrameThickness']}}@else{{ 0 }}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="FrameDepth">Frame Depth</label>
                            <input type="number" name="FrameDepth" id="FrameDepth" class="form-control change-event-calulation" placeholder="Frame Depth"
                                value="@if(isset($Item['FrameDepth'])){{$Item['FrameDepth']}}@else{{ 0 }}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="frameWidth">Frame Width</label>
                            <input type="number" name="FrameWidth" id="FrameWidth" placeholder="Frame Width"
                                class="form-control change-event-calulation" readonly required
                                value="@if(isset($Item['FrameWidth'])){{$Item['FrameWidth']}}@else{{ 0 }}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="frameHeight">Frame Height</label>
                            <input type="number" name="FrameHeight" placeholder="Frame Height" id="FrameHeight"
                                class="form-control change-event-calulation" readonly required
                                value="@if(isset($Item['FrameHeight'])){{$Item['FrameHeight']}}@else{{ 0 }}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group input-icons">
                            <label for="frameMaterial">Frame Material</label>
                            <i class="fa fa-info icon" id="frameMaterialIcon"></i>
                            <input type="text"  id="FrameMaterial" class="form-control bg-white"
                                value="@if(isset($Item['FrameMaterial'])){{$Item['FrameMaterial']}}@endif">
                            <input type="hidden" id="FrameMaterialNew" name="FrameMaterial"
                                value="@if(isset($Item['FrameMaterial'])){{$Item['FrameMaterial']}}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="SubFrameBottom">Sub Frame Bottom</label>
                            <select name="SubFrameBottom" id="SubFrameBottom" class="form-control">
                                <option value="">Select Sub Frame Bottom</option>
                                <option value="Yes" @if(isset($Item['SubFrameBottom']) && $Item['SubFrameBottom'] == 'Yes') selected @endif>Yes</option>
                                <option value="No" @if(isset($Item['SubFrameBottom']) && $Item['SubFrameBottom'] == 'No') selected @endif>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="SubFrameTop">Sub Frame Top</label>
                            <select name="SubFrameTop" id="SubFrameTop" class="form-control">
                                <option value="">Select Sub Frame Top</option>
                                <option value="Yes" @if(isset($Item['SubFrameTop']) && $Item['SubFrameTop'] == 'Yes') selected @endif>Yes</option>
                                <option value="No" @if(isset($Item['SubFrameTop']) && $Item['SubFrameTop'] == 'No') selected @endif>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="SubFrameLeft">Sub Frame Left</label>
                            <select name="SubFrameLeft" id="SubFrameLeft" class="form-control">
                                <option value="">Select Sub Frame Left</option>
                                <option value="Yes" @if(isset($Item['SubFrameLeft']) && $Item['SubFrameLeft'] == 'Yes') selected @endif>Yes</option>
                                <option value="No" @if(isset($Item['SubFrameLeft']) && $Item['SubFrameLeft'] == 'No') selected @endif>No</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="SubFrameRight">Sub Frame Right</label>
                            <select name="SubFrameRight" id="SubFrameRight" class="form-control">
                                <option value="">Select Sub Frame Right</option>
                                <option value="Yes" @if(isset($Item['SubFrameRight']) && $Item['SubFrameRight'] == 'Yes') selected @endif>Yes</option>
                                <option value="No" @if(isset($Item['SubFrameRight']) && $Item['SubFrameRight'] == 'No') selected @endif>No</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="SubFrameBottomThickness">Sub Frame Bottom Thickness</label>
                            <input type="number" name="SubFrameBottomThickness" placeholder="Sub Frame Bottom Thickness" id="SubFrameBottomThickness"
                                class="form-control" value="@if(isset($Item['SubFrameBottomThickness'])){{$Item['SubFrameBottomThickness']}}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="SubFrameBottomWidth">Sub Frame Bottom Width</label>
                            <input type="number" name="SubFrameBottomWidth" placeholder="Sub Frame Bottom Width" id="SubFrameBottomWidth"
                                class="form-control" value="@if(isset($Item['SubFrameBottomWidth'])){{$Item['SubFrameBottomWidth']}}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="SubFrameTopThickness">Sub Frame Top Thickness</label>
                            <input type="number" name="SubFrameTopThickness" placeholder="Sub Frame Top Thickness" id="SubFrameTopThickness"
                                class="form-control" value="@if(isset($Item['SubFrameTopThickness'])){{$Item['SubFrameTopThickness']}}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="SubFrameLeftThickness">Sub Frame Left Thickness</label>
                            <input type="number" name="SubFrameLeftThickness" placeholder="Sub Frame Left Thickness" id="SubFrameLeftThickness"
                                class="form-control" value="@if(isset($Item['SubFrameLeftThickness'])){{$Item['SubFrameLeftThickness']}}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="SubFrameRightThickness">Sub Frame Right Thickness</label>
                            <input type="number" name="SubFrameRightThickness" placeholder="Sub Frame Right Thickness" id="SubFrameRightThickness"
                                class="form-control" value="@if(isset($Item['SubFrameRightThickness'])){{$Item['SubFrameRightThickness']}}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group input-icons">
                            <label for="Sub FrameMaterial">Sub Frame Material</label>
                            <i class="fa fa-info icon" id="SubFrameMaterialIcon"></i>
                            <input type="text"  id="SubFrameMaterial" class="form-control bg-white"
                                value="@if(isset($Item['SubFrameMaterial'])){{$Item['SubFrameMaterial']}}@endif">
                            <input type="hidden" id="SubFrameMaterialNew" name="SubFrameMaterial"
                                value="@if(isset($Item['SubFrameMaterial'])){{$Item['SubFrameMaterial']}}@endif">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
