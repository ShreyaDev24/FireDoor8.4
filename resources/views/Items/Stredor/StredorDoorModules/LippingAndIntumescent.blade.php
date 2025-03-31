<!-- LIPPING AND INTUMESCENT  -->
<div class="main-card mb-3 custom_card">
    <div>
        <div class="tab-content">
            <div class="card-header">
                <h5 class="card-title" style="margin-top: 10px">Lipping & Intumescent </h5>
            </div>
            <div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="lippingType">Lipping Type
                            @if(!empty($tooltip->lippingType))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->lippingType}}'));
                            </script>
                            @endif
                            </label>
                            <select name="lippingType" required  @if(isset($Item["FireRating"]) && $Item["FireRating"] != "NFR"){{'required'}} @endif id="lippingType" class="form-control">
                                <option value="">Select Lipping Types</option>
                                @foreach($option_data as $row)
                                @if($row->OptionSlug=='lipping_type')
                                <option value="{{$row->OptionKey}}" @if(isset($Item['LippingType'])) @if($Item['LippingType'] == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="lippingThickness">Lipping Thickness
                            @if(!empty($tooltip->lippingThickness))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->lippingThickness}}'));
                            </script>
                            @endif
                            </label>
                            <select name="lippingThickness" required @if(isset($Item["FireRating"]) && $Item["FireRating"] != "NFR"){{'required'}} @endif id="lippingThickness"
                                class="form-control forcoreWidth1 door-configuration" onchange="$('#lippingSpecies').val('')">
                                <option value="">Select leaping thickness</option>
                                @foreach($option_data as $row)
                                @if($row->OptionSlug=='lipping_thickness')
                                <option value="{{$row->OptionKey}}" @if(isset($Item['LippingThickness'])) @if($Item['LippingThickness'] == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group input-icons">
                            <label for="lippingSpecies">Lipping Species
                            @if(!empty($tooltip->lippingSpecies))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->lippingSpecies}}'));
                            </script>
                            @endif

                            </label>
                            <!-- <select name="lippingSpecies" id="lippingSpecies" class="form-control">
                                <option value="">Select Lipping Species</option>
                            </select> -->

                                <i class="fa fa-info icon" id="lippingSpeciesIcon"  onClick=""></i>
                                <input type="text" required  @if(isset($Item["FireRating"]) && $Item["FireRating"] != "NFR"){{'required'}} @endif readonly id="lippingSpecies"
                                    class="form-control bg-white door-configuration">
                                <input type="hidden" name="lippingSpecies" id="lippingSpeciesid" value="@if(isset($Item['LippingSpecies'])){{$Item['LippingSpecies']}}@endif">

                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="meetingStyle">Meeting Style
                            @if(!empty($tooltip->meetingStyle))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->meetingStyle}}'));
                            </script>
                            @endif
                            </label>
                            <select name="meetingStyle"
                                @if(empty($Item['MeetingStyle'])){{'disabled'}}@endif
                                id="meetingStyle" class="form-control">
                                <option value="">Select Meeting Style</option>
                                @foreach($option_data as $row)
                                @if($row->OptionSlug=='meeting_style')
                                <option value="{{$row->OptionKey}}"
                                    @if(isset($Item['MeetingStyle']))
                                        @if($Item['MeetingStyle'] == $row->OptionKey)
                                            {{'selected'}}
                                        @endif
                                    @endif
                                    >{{$row->OptionValue}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="scallopedLippingThickness">Scalloped Lipping
                                Thickness
                            @if(!empty($tooltip->scallopedLippingThickness))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->scallopedLippingThickness}}'));
                            </script>
                            @endif

                                </label>
                            <select name="scallopedLippingThickness"
                            @if(empty($Item['ScallopedLippingThickness'])){{'disabled'}}@endif
                                id="scallopedLippingThickness" class="form-control">
                                <option value="">Select Scalloped Lipping Thickness</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="flatLippingThickness">Flat Lipping Thickness
                            @if(!empty($tooltip->flatLippingThickness))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->flatLippingThickness}}'));
                            </script>
                            @endif
                            </label>
                            <select name="flatLippingThickness" @if(empty($Item['FlatLippingThickness'])){{'disabled'}}@endif id="flatLippingThickness"
                                class="form-control">
                                <option value="">Select Flat Lipping Thickness</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="rebatedLippingThickness">Rebated Lipping
                                Thickness
                            @if(!empty($tooltip->rebatedLippingThickness))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->rebatedLippingThickness}}'));
                            </script>
                            @endif
                                </label>
                            <select name="rebatedLippingThickness" @if(empty($Item['RebatedLippingThickness'])){{'disabled'}}@endif id="rebatedLippingThickness"
                                class="form-control">
                                <option value="">Select Rebated Lipping Thickness</option>

                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="coreWidth1">Core Width 1
                            @if(!empty($tooltip->coreWidth1))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->coreWidth1}}'));
                            </script>
                            @endif
                            </label>
                            <input type="number" min="1" readonly id="coreWidth1" name="coreWidth1"
                                class="form-control coreWidth1" value="@if(isset($Item['CoreWidth1'])){{$Item['CoreWidth1']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="coreWidth2">Core Width 2
                            @if(!empty($tooltip->coreWidth2))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->coreWidth2}}'));
                            </script>
                            @endif

                            </label>
                            <input type="number" min="1" readonly id="coreWidth2" name="coreWidth2"
                                class="form-control coreWidth1" value="@if(isset($Item['CoreWidth2'])){{$Item['CoreWidth2']}}@endif">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="coreHeight">Core Height
                            @if(!empty($tooltip->coreHeight))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->coreHeight}}'));
                            </script>
                            @endif
                            </label>
                            <input type="number" min="1" readonly id="coreHeight" name="coreHeight"
                                class="form-control coreWidth1" value="@if(isset($Item['CoreHeight'])){{$Item['CoreHeight']}}@endif">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="intumescentSealType">Intumescent Seal Type
                            @if(!empty($tooltip->intumescentSealType))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->intumescentSealType}}'));
                            </script>
                            @endif
                            </label>
                            <select name="intumescentSealType" id="intumescentSealType"
                                class="form-control">
                                <option value="">Select Intumescent seal type</option>
                                @foreach($option_data as $row)
                                @if($row->OptionSlug=='intumescent_seal_type')
                                <option value="{{$row->OptionKey}}" @if(isset($Item['IntumescentLeapingSealType'])) @if($Item['IntumescentLeapingSealType'] == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="intumescentSealLocation">Intumescent Seal
                                Location
                            @if(!empty($tooltip->intumescentSealLocation))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->intumescentSealLocation}}'));
                            </script>
                            @endif
                                </label>
                            <select name="intumescentSealLocation" id="intumescentSealLocation"
                                class="form-control">
                                <option value="">Select Intumescent seal Location</option>
                                @foreach($option_data as $row)
                                @if($row->OptionSlug=='IntumescentSeal_location')
                                <option value="{{$row->OptionKey}}" @if(isset($Item['IntumescentLeapingSealLocation'])) @if($Item['IntumescentLeapingSealLocation'] == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                                @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="intumescentSealColor">Intumescent Seal Color
                            @if(!empty($tooltip->intumescentSealColor))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->intumescentSealColor}}'));
                            </script>
                            @endif
                            </label>
                            <label for="intumescent_Seal_Color" style="display: none;">Intumescent Seal Color</label>
                            <select name="intumescentSealColor" id="intumescentSealColor"  option_slug = "Intumescent_Seal_Color"
                                class="form-control">
                                <option value="">Select Intumescent seal Color</option>
                                @foreach($intumescentSealColor as $row)
                                    <option value="{{$row->Key}}" @if(isset($Item['IntumescentLeapingSealColor'])) @if($Item['IntumescentLeapingSealColor'] == $row->Key) {{'selected'}} @endif @endif>{{$row->IntumescentSealColor}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="intumescentSealArrangement">Intumescent Seal
                                Arrangement
                            @if(!empty($tooltip->intumescentSealArrangement))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->intumescentSealArrangement}}'));
                            </script>
                            @endif
                                </label>
                            {{--<input type="text" name="intumescentSealArrangement"--}}
                                {{--id="intumescentSealArrangement" class="form-control">--}}

                            <select name="intumescentSealArrangement" required id="intumescentSealArrangement" class="form-control" option_slug = "intumescentSealArrangement"></select>
                        </div>
                    </div>

                    <div class="col-md-6 intumescentSealMeetingEdgesDiv" style="@if(!@$Item['intumescentSealMeetingEdges']) display:none @endif">
                        <div class="position-relative form-group">
                            <label for="intumescentSealMeetingEdges">Meeting Edges
                            @if(!empty($tooltip->intumescentSealMeetingEdges))
                            <script type="text/javascript">
                                document.write(Tooltip('{{$tooltip->intumescentSealMeetingEdges}}'));
                            </script>
                            @endif
                                </label>
                            <input type="text" readonly name="intumescentSealMeetingEdges"
                                id="intumescentSealMeetingEdges" value="@if(isset($Item['intumescentSealMeetingEdges'])){{$Item['intumescentSealMeetingEdges']}}@endif" class="form-control">

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
