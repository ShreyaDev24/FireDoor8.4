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
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="plantonStopWidth">Plant on Stop Width(min 32)

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
                                                    <label for="plantonStopHeight">Plant on Stop Height(min 12)
                                                    @if(!empty($tooltip->plantonStopHeight))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->plantonStopHeight}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <input type="number" @if(empty($Item['PlantonStopHeight'])){{'readonly'}}@endif min="12" name="plantonStopHeight"
                                                        id="plantonStopHeight" class="form-control"
                                                        value="@if(isset($Item['PlantonStopHeight'])){{$Item['PlantonStopHeight']}}@else{{'0'}}@endif">
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
                                                    <input type="number" name="frameWidth" id="frameWidth"
                                                        placeholder="Frame Width" class="form-control"
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
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="frameDepth">Frame Depth (min 80)
                                                    @if(!empty($tooltip->frameDepth))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->frameDepth}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <input type="text" name="frameDepth" id="frameDepth" class="form-control"
                                                        value="@if(isset($Item['FrameDepth'])){{$Item['FrameDepth']}}@endif" min="80">
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
                                                        class="form-control change-event-calulation">
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
                                                        <input type="hidden" name="frameCostuction" value="@if(isset($Item['DoorFrameConstruction'])){{$Item['DoorFrameConstruction']}}@endif">

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


                                            <!-- <div class="col-md-6">
                              <div class="position-relative form-group">
                                <label for="intumescentSealType">Intumescent Seal Type</label>
                               <select name="intumescentSealType" id="intumescentSealType" class="form-control">
                                          <option value="Fire only" @if(isset($Item['IntumescentLeapingSealType'])) @if($Item['IntumescentLeapingSealType'] == "Fire only") {{'selected'}} @endif @endif>Fire only</option>
                                          <option value="Fire and Smoke" @if(isset($Item['IntumescentLeapingSealType'])) @if($Item['IntumescentLeapingSealType'] == "Fire and Smoke") {{'selected'}} @endif @endif>Fire and Smoke</option>
                                </select>
                              </div>
                           </div> -->
                                            <!-- <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="intumescentSeal">Intumescent Seal
                                                    @if(!empty($tooltip->intumescentSeal))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->intumescentSeal}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <select name="intumescentSeal" id="intumescentSeal" class="form-control">
                                                        <option value="Frame" @if(isset($Item['IntumescentSeal'])) @if($Item['IntumescentSeal'] == "Frame") {{'selected'}} @endif @endif>Frame</option>
                                                        <option value="Door Leaf 1" @if(isset($Item['IntumescentSeal'])) @if($Item['IntumescentSeal'] == "Door Leaf 1") {{'selected'}} @endif @endif>Door Leaf 1</option>
                                                        <option value="Door Leaf 2" @if(isset($Item['IntumescentSeal'])) @if($Item['IntumescentSeal'] == "Door Leaf 2") {{'selected'}} @endif @endif>Door Leaf 2</option>
                                                    </select>
                                                </div>
                                            </div> -->

                                            <!-- <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="intumescentSealColor">Intumescent Seal Color
                                                    @if(!empty($tooltip->intumescentSealColor))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->intumescentSealColor}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <select name="intumescentSealColor" id="intumescentSealColor"
                                                        class="form-control">
                                                        <option value="White" @if(isset($Item['IntumescentSealColor'])) @if($Item['IntumescentSealColor'] == "White") {{'selected'}} @endif @endif>White</option>
                                                        <option value="brown" @if(isset($Item['IntumescentSealColor'])) @if($Item['IntumescentSealColor'] == "brown") {{'selected'}} @endif @endif>brown</option>
                                                        <option value="black" @if(isset($Item['IntumescentSealColor'])) @if($Item['IntumescentSealColor'] == "black") {{'selected'}} @endif @endif>black</option>
                                                    </select>
                                                </div>
                                            </div> -->
                                            <!-- <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="intumescentSealSize">Intumescent Seal Size
                                                    @if(!empty($tooltip->intumescentSealSize))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->intumescentSealSize}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <select name="intumescentSealSize" id="intumescentSealSize"
                                                        class="form-control">
                                                        <option value="10x4mm" @if(isset($Item['IntumescentSealSize'])) @if($Item['IntumescentSealSize'] == "10x4mm") {{'selected'}} @endif @endif>10x4mm</option>
                                                    </select>
                                                </div>
                                            </div> -->


                                            {{-- <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="ironmongerySet">Ironmongery Set
                                                    @if(!empty($tooltip->ironmongerySet))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->ironmongerySet}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <select name="ironmongerySet" id="ironmongerySet" class="form-control">
                                                        <option value="Yes" @if(isset($Item['IronmongerySet'])) @if($Item['IronmongerySet'] == "Yes") {{'selected'}} @endif @endif>Yes</option>
                                                        <option value="No" @if(isset($Item['IronmongerySet']))
                                                            @if($Item['IronmongerySet'] == "No")
                                                                {{'selected'}}
                                                            @endif
                                                            @else {{'selected'}} @endif>No</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="IronmongeryID">Select Ironmongery Set
                                                    @if(!empty($tooltip->selectironmongerySet))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->selectironmongerySet}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <select name="IronmongeryID" id="IronmongeryID" class="form-control" @if(empty($Item['IronmongerySet']) || $Item['IronmongerySet'] == "No") {{'disabled'}} @endif>
                                                        <option value="">Select Ironmongery Set</option>
                                                        @if(!empty($setIronmongery))
                                                        @foreach($setIronmongery as $setIronmongerys)
                                                            <option value="{{$setIronmongerys->id}}"
                                                            @if(isset($Item['IronmongeryID'])) @if($Item['IronmongeryID'] == $setIronmongerys->id) {{'selected'}} @endif @endif>{{$setIronmongerys->Setname}}</option>
                                                        @endforeach
                                                        @endif
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="specialFeatureRefs">Special Feature Refs
                                                    @if(!empty($tooltip->specialFeatureRefs))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->specialFeatureRefs}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <input name="specialFeatureRefs" id="specialFeatureRefs"
                                                        placeholder="Special Feature Refs" class="form-control" type="text"
                                                        value="@if(isset($Item['SpecialFeatureRefs'])){{$Item['SpecialFeatureRefs']}}@endif">
                                                </div>
                                            </div> --}}

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
