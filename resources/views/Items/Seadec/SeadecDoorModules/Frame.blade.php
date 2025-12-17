<!-- Frame -->
                        <div class="main-card mb-3 custom_card">
                            <div>
                                <div class="tab-content">
                                    <div class="card-header">
                                        <h5 class="card-title" style="margin-top: 10px">Frame </h5>
                                    </div>
                                    <div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="position-relative form-group input-icons">
                                                    <label for="frameMaterial">Frame Material
                                                    @if(!empty($tooltip->frameMaterial))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->frameMaterial}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <i class="fa fa-info icon" id="frameMaterialIcon"></i>
                                                    <input type="text" readonly id="frameMaterial"
                                                        class="form-control bg-white"
                                                        value="@if(isset($Item['FrameMaterial'])){{$Item['FrameMaterial']}}@endif">
                                                    <input type="hidden" id="frameMaterialNew"
                                                        name="frameMaterial"
                                                        value="@if(isset($Item['FrameMaterial'])){{$Item['FrameMaterial']}}@endif">
                                                </div>

                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="frameType">Frame Type
                                                    @if(!empty($tooltip->frameType))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->frameType}}'));
                                                    </script>
                                                    @endif

                                                    </label>
                                                    <select name="frameType" id="frameType" class="form-control">
                                                        <option value="">Select Frame Type</option>
                                                        @foreach($option_data as $row)
                                                        @if($row->OptionSlug=='Frame_Type')
                                                        <option value="{{$row->OptionKey}}" @if(isset($Item['FrameType'])) @if($Item['FrameType'] == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <input type="hidden" id="frametypevalue" name="frametypevalue" value="@if(isset($Item['FrameType'])){{$Item['FrameType']}}@endif" >
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="plantonStopWidth">Plant on Stop Width <span id="plan-on-stop-min-width"></span>

                                                    @if(!empty($tooltip->plantonStopWidth))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->plantonStopWidth}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <input type="number" name="plantonStopWidth" value="@if(isset($Item['PlantonStopWidth'])){{$Item['PlantonStopWidth']}}@else{{'0'}}@endif" id="plantonStopWidth" class="form-control" @if(empty($Item['PlantonStopWidth'])){{'readonly'}}@endif min="32">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="plantonStopHeight">Plant on Stop Height <span id="plan-on-stop-min-height"></span>
                                                    @if(!empty($tooltip->plantonStopHeight))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->plantonStopHeight}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <input type="number" @if(empty($Item['PlantonStopHeight'])){{'readonly'}}@endif  name="plantonStopHeight"
                                                        id="plantonStopHeight" class="form-control"
                                                        value="@if(isset($Item['PlantonStopHeight'])){{$Item['PlantonStopHeight']}}@else{{'0'}}@endif" min="12">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="rebatedWidth">Scalloped Width (min32)

                                                    @if(!empty($tooltip->ScallopedWidth))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->ScallopedWidth}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <label for="Rebated_Width" style="display: none;">Rebated Width</label>
                                                    <input type="number" name="ScallopedWidth" value="@if(isset($Item['ScallopedWidth'])){{$Item['ScallopedWidth']}}@else{{'0'}}@endif" id="ScallopedWidth" class="form-control" @if(empty($Item['ScallopedWidth'])){{'readonly'}}@endif min="32">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="rebatedHeight">Scalloped Depth (min 12)
                                                    @if(!empty($tooltip->ScallopedHeight))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->ScallopedHeight}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    {{--  <label for="Rebated_Height" style="display: none;">Rebated Height</label>  --}}
                                                    <input type="number" @if(empty($Item['ScallopedHeight'])){{'readonly'}}@endif min="12" name="ScallopedHeight" id="ScallopedHeight" class="form-control"
                                                    value="@if(isset($Item['ScallopedHeight'])){{$Item['ScallopedHeight']}}@else{{'0'}}@endif">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="rebatedWidth">Rebated Width (min32)

                                                    @if(!empty($tooltip->rebatedWidth))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->rebatedWidth}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <label for="Rebated_Width" style="display: none;">Rebated Width</label>
                                                    <input type="number" name="rebatedWidth" value="@if(isset($Item['RebatedWidth'])){{$Item['RebatedWidth']}}@else{{'0'}}@endif" id="rebatedWidth" class="form-control" @if(empty($Item['RebatedWidth'])){{'readonly'}}@endif min="32">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="rebatedHeight">Rebated Depth  (min 12)
                                                    @if(!empty($tooltip->rebatedHeight))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->rebatedHeight}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <label for="Rebated_Height" style="display: none;">Rebated Depth </label>
                                                    <input type="number" @if(empty($Item['RebatedHeight'])){{'readonly'}}@endif min="12" name="rebatedHeight"
                                                        id="rebatedHeight" class="form-control change-event-calulation"
                                                        value="@if(isset($Item['RebatedHeight'])){{$Item['RebatedHeight']}}@else{{'0'}}@endif">
                                                </div>
                                            </div>

                                             <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="rebatedWidth" id="rebatedWidthLabel">Rebated Head Depth(min 12)<span id="rebatedWidthText"></span>

                                                    @if(!empty($tooltip->rebatedWidth))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->rebatedWidth}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <label for="Rebated_Width" style="display: none;">Rebated Width</label>
                                                    <input type="number" name="RebatedHeadDepth" min="12" value="@if(isset($Item['RebatedHeadDepth'])){{$Item['RebatedHeadDepth']}}@else{{'0'}}@endif" id="RebatedHeadDepth" class="form-control" >
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="rebatedHeight">Rebated Bottom Depth (min 12)
                                                    @if(!empty($tooltip->rebatedHeight))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->rebatedHeight}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <label for="Rebated_Height" style="display: none;">Rebated Depth</label>
                                                    <input type="number" min="12" name="RebatedBottomDepth" id="RebatedBottomDepth" class="form-control change-event-calulation" value="@if(isset($Item['RebatedBottomDepth'])){{$Item['RebatedBottomDepth']}}@else{{'0'}}@endif">
                                                </div>
                                            </div>

                                            <div class="col-md-6" hidden>
                                                <div class="position-relative form-group">
                                                    <label for="frameTypeDimensions">Dimensions
                                                    @if(!empty($tooltip->frameTypeDimensions))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->frameTypeDimensions}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <input type="number" name="frameTypeDimensions" min="1"  @if(empty($Item['FrameTypeDimensions'])){{'readonly'}}@endif
                                                        id="frameTypeDimensions" class="form-control"
                                                        value="@if(isset($Item['FrameTypeDimensions'])){{$Item['FrameTypeDimensions']}}@else{{'0'}}@endif">

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="frameWidth">Frame Width
                                                    @if(!empty($tooltip->frameWidth))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->frameWidth}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <input type="text" name="frameWidth" id="frameWidth"
                                                        placeholder="Frame Width" class="form-control change-event-calulation"
                                                        value="@if(isset($Item['FrameWidth'])){{$Item['FrameWidth']}}@endif" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="frameHeight">Frame Height
                                                    @if(!empty($tooltip->frameHeight))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->frameHeight}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <input type="text" name="frameHeight" placeholder="Frame Height"
                                                        id="frameHeight" class="form-control"
                                                        value="@if(isset($Item['FrameHeight'])){{$Item['FrameHeight']}}@endif" readonly>
                                                </div>
                                            </div>
                                            <div class="col-md-6 framehideshow">
                                                <div class="position-relative form-group">
                                                    <label for="headframeThickness">Head Frame Thickness
                                                        @if(!empty($tooltip->headframeThickness))
                                                        <script type="text/javascript">
                                                        document.write(Tooltip('{{$tooltip->headframeThickness}}'));
                                                        </script>
                                                        @endif
                                                    </label>
                                                    <input type="number" id="headframeThickness" name="headframeThickness" value="{{ isset($Item['HeadFrameThickness']) ? $Item['HeadFrameThickness'] : (isset($Item['FrameThickness']) ? $Item['FrameThickness'] : '') }}"                                                        class="form-control change-event-calulation door-configuration" required pattern="\d*" maxlength="5" oninput="if(this.value.length > 5) this.value = this.value.slice(0, 5);">
                                                </div>
                                            </div>
                                            <div class="col-md-6 framehideshow">
                                                <div class="position-relative form-group">
                                                    <label for="bottomframeThickness">bottom Frame Thickness
                                                        @if(!empty($tooltip->bottomframeThickness))
                                                        <script type="text/javascript">
                                                        document.write(Tooltip('{{$tooltip->bottomframeThickness}}'));
                                                        </script>
                                                        @endif
                                                    </label>
                                                    <input type="number" id="bottomframeThickness" name="bottomframeThickness" value="{{ isset($Item['BottomFrameThickness']) ? $Item['BottomFrameThickness'] : (isset($Item['FrameThickness']) ? $Item['FrameThickness'] : '') }}"
                                                        class="form-control change-event-calulation door-configuration" required pattern="\d*" maxlength="5" oninput="if(this.value.length > 5) this.value = this.value.slice(0, 5);">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="frameDepth">Frame Depth <span id="frame-depth-min"></span>
                                                    @if(!empty($tooltip->frameDepth))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->frameDepth}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <input type="text" name="frameDepth" id="frameDepth" class="form-control"
                                                        value="@if(isset($Item['FrameDepth'])){{$Item['FrameDepth']}}@endif" >
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-6">
                                <div class="position-relative form-group">
                                    <label for="frameThickness1">Frame Thickness</label>
                                     <input name="frameThickness1" required placeholder="Frame Thickness"
                                            id="frameThickness1" min="0" type="text" class="form-control"
                                            value="@if(isset($Item['FrameThickness1'])){{$Item['FrameThickness1']}}@endif">

                                </div>
                            </div> -->
                                            <input name="frameThickness1" id="frameThickness1" min="0" type="hidden"
                                                class="form-control">

                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="frameFinish">Frame Finish

                                                    @if(!empty($tooltip->frameFinish))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->frameFinish}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <select name="frameFinish" id="frameFinish"
                                                        class="form-control change-event-calulation" required>
                                                        <option value="">Select Frame finish</option>
                                                        @foreach($option_data as $row)
                                                        @if($row->OptionSlug=='Frame_Finish')
                                                        <option value="{{$row->OptionKey}}" @if(isset($Item['FrameFinish'])) @if($Item['FrameFinish'] == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="framefinishColor">Frame Finish Color
                                                    @if(!empty($tooltip->framefinishColor))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->framefinishColor}}'));
                                                    </script>
                                                    @endif

                                                    </label>
                                                    <select name="framefinishColor" id="framefinishColor" class="form-control">
                                                        <option value="">Frame Finish</option>
                                                        <!-- @foreach($option_data as $row)
                                                            @if($row->OptionSlug=='door_leaf_finish')
                                                            <option value="{{$row->OptionKey}}">{{$row->OptionValue}}</option>
                                                            @endif
                                                        @endforeach -->
                                                    </select>
                                                    <input type="hidden" id="FrameFinishColor-value" value="@if(isset($Item['FrameFinishColor'])){{$Item['FrameFinishColor']}}@endif">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="extLiner">Ext-Liner
                                                    @if(!empty($tooltip->extLiner))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->extLiner}}'));
                                                    </script>
                                                    @endif

                                                    </label>
                                                    <select name="extLiner" id="extLiner" class="form-control change-event-calulation">
                                                        <option value="">Select Ex-liner</option>
                                                        @foreach($option_data as $row)
                                                        @if($row->OptionSlug=='Ext_Liner')
                                                        <option value="{{$row->OptionKey}}"
                                                            @if(isset($Item['ExtLiner']))
                                                                @if($Item['ExtLiner'] == $row->OptionKey)
                                                                    {{'selected'}}
                                                                @endif
                                                            @elseif($row->OptionKey == 'No')
                                                                {{'selected'}}
                                                            @endif>{{$row->OptionValue}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group input-icons">
                                                    <label for="frameCostuction">Door Frame Construction
                                                    @if(!empty($tooltip->frameCostuction))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->frameCostuction}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <!-- <select name="frameCostuction" id="frameCostuction"
                                                        class="form-control change-event-calulation">
                                                        <option value="">Select Door Frame Construction</option>
                                                        @foreach($option_data as $row)
                                                        @if($row->OptionSlug=='Door_Frame_Construction')
                                                        <option value="{{$row->OptionKey}}" @if(isset($Item['DoorFrameConstruction'])) @if($Item['DoorFrameConstruction'] == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select> -->
                                                        @if(isset($Item['DoorFrameConstruction']) && $Item['DoorFrameConstruction'] != "")
                                                            @foreach($option_data as $row)
                                                                @if($row->OptionSlug=='Door_Frame_Construction')
                                                                    @if($row->OptionKey == $Item['DoorFrameConstruction'])
                                                                        <?php $DoorFrameConstruction = $row->OptionValue; ?>
                                                                    @endif
                                                                @endif
                                                            @endforeach
                                                        @endif
                                                        <i class="fa fa-info icon cursor-pointer" id="frameCostuction" onClick="$('#DoorFrameConstructionModal').modal('show')"></i>
                                                        <input type="text" readonly id="frameCostuction" value="@if(isset($DoorFrameConstruction)){{$DoorFrameConstruction}}@endif" class="form-control bg-white">
                                                        <input type="hidden" name="frameCostuction" id="mydoor" value="@if(isset($Item['DoorFrameConstruction'])){{$Item['DoorFrameConstruction']}}@endif">

                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="extLinerValue">Ext-Liner Size
                                                    @if(!empty($tooltip->extLinerValue))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->extLinerValue}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <input type="text" @if(empty($Item['ExtLinerValue'])){{'readonly'}}@endif name="extLinerValue"
                                                        id="extLinerValue" class="form-control"
                                                        value="@if(isset($Item['ExtLinerValue'])){{$Item['ExtLinerValue']}}@else{{'0'}}@endif">


                                                </div>
                                            </div>
                                            <!-- <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="extLinerSize">Ext-Liner Size
                                                    @if(!empty($tooltip->extLinerSize))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->extLinerSize}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <input name="extLinerSize" @if(empty($Item['extLinerSize'])){{'readonly'}}@endif id="extLinerSize"
                                                        placeholder="Ext-Liner Size" class="form-control" type="text"
                                                        value="@if(isset($Item['extLinerSize'])){{$Item['extLinerSize']}}@endif">
                                                </div>
                                            </div> -->
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="extLinerThickness">Ext-Liner Thickness
                                                    @if(!empty($tooltip->extLinerThickness))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->extLinerThickness}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <input name="extLinerThickness" @if(empty($Item['ExtLinerThickness'])){{'readonly'}}@endif  id="extLinerThickness"
                                                        placeholder="Ext-Liner Thickness"
                                                        class="form-control change-event-calulation" type="text"
                                                        value="@if(isset($Item['ExtLinerThickness'])){{$Item['ExtLinerThickness']}}@endif">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="extLinerFinish">Ext-Liner FInish
                                                    @if(!empty($tooltip->extLinerFinish))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->extLinerFinish}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <input name="extLinerFinish" id="extLinerFinish" readonly
                                                        placeholder="Ext-Liner Finish" class="form-control" type="text"
                                                        value="@if(isset($Item['ExtLinerFInish'])){{$Item['ExtLinerFInish']}}@endif">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="accoustics">Saddle Required</label>
                                                    <select name="Saddle" id="Saddle" class="form-control">
                                                        <option value="">Select Saddle</option>
                                                        <option value="Yes" @if (isset($Item['Saddle']))
                                                            @if ($Item['Saddle'] == 'Yes')
                                                                {{ 'selected' }}
                                                            @endif
                                                            @endif>Yes
                                                        </option>
                                                        <option value="No" @if (isset($Item['Saddle']))
                                                            @if ($Item['Saddle'] == 'No')
                                                                {{ 'selected' }}
                                                            @endif
                                                        @else
                                                            {{ 'selected' }}
                                                            @endif>No
                                                        </option>

                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="saddleLocation">Saddle Location</label>
                                                    <select id="saddleLocation" name="saddleLocation" class="form-control" @if(isset($Item['Saddle']) && $Item['Saddle'] == "No") {{'disabled'}} @endif>
                                                        <option value="">select Saddle Location</option>
                                                        <option value="Between_Styles" @if(isset($Item['saddleLocation'])) @if($Item['saddleLocation'] == 'Between_Styles') {{'selected'}} @endif @endif>Between Styles</option>
                                                        <option value="Under_Frame" @if(isset($Item['saddleLocation'])) @if($Item['saddleLocation'] == 'Under_Frame') {{'selected'}} @endif @endif>Under Frame</option>
                                                    </select>
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
