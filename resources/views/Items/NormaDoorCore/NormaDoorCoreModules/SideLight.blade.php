<!-- SIDE LIGHT -->
                        <div class="main-card mb-3 custom_card">
                            <div>
                                <div class="tab-content">
                                    <div class="card-header">
                                        <h5 class="card-title" style="margin-top: 10px">Side Light</h5>
                                    </div>
                                    <div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="sideLight1">Side Light 1 (SL1)
                                                    @if(!empty($tooltip->sideLight1))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->sideLight1}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <select name="sideLight1" id="sideLight1" class="form-control door-configuration" required>
                                                        <option value=""> Is side light 1 is active?</option>
                                                        @foreach($option_data as $row)
                                                        @if($row->OptionSlug=='SideLight1')
                                                        <option value="{{$row->OptionKey}}" 
                                                            @if(isset($Item['SideLight1'])) 
                                                                @if($Item['SideLight1'] == $row->OptionKey) 
                                                                    {{'selected'}}
                                                                @endif
                                                            @elseif($row->OptionKey == "No")
                                                                {{'selected'}}
                                                            @endif>{{$row->OptionValue}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="sideLight1GlassType">Side Light 1 Glass Type
                                                    @if(!empty($tooltip->sideLight1GlassType))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->sideLight1GlassType}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <select name="sideLight1GlassType" @if(empty($Item['SideLight1GlassType'])){{'disabled'}}@endif id="sideLight1GlassType"
                                                        class="form-control">
                                                        <option value="">Select Glass Type</option>
                                                        <!-- @foreach($option_data as $row)
                                                        @if($row->OptionSlug=='leaf1_glass_type')
                                                        <option value="{{$row->OptionKey}}" @if(isset($Item['SideLight1GlassType'])) @if($Item['SideLight1GlassType'] == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                                                        @endif
                                                        @endforeach -->
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="SideLight1BeadingType">Beading Type
                                                    @if(!empty($tooltip->SideLight1BeadingType))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->SideLight1BeadingType}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <select name="SideLight1BeadingType" id="SideLight1BeadingType" @if(empty($Item['BeadingType'])){{'disabled'}}@endif
                                                        class="form-control">
                                                        <option value="">Select Beading Type</option>
                                                        <!-- @foreach($option_data as $row)
                                                        @if($row->OptionSlug=='leaf1_glass_type')
                                                        <option value="{{$row->OptionKey}}" @if(isset($Item['BeadingType'])) @if($Item['BeadingType'] == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                                                        @endif
                                                        @endforeach -->
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group input-icons">
                                                    <label for="SideLight1GlazingBeadSpecies">Glazing Bead
                                                        Species
                                                    @if(!empty($tooltip->SideLight1GlazingBeadSpecies))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->SideLight1GlazingBeadSpecies}}'));
                                                    </script>
                                                    @endif

                                                        </label>                                                    
                                                        <i class="fa fa-info icon" id="SideLight1GlazingBeadSpeciesIcon"
                                                            onClick=""></i>
                                                        <input type="text" id="SideLight1GlazingBeadSpecies"
                                                            class="form-control" @if(empty($Item['SL1GlazingBeadSpecies'])){{'disabled'}}@endif  value="">
                                                        <input type="hidden" name="SideLight1GlazingBeadSpecies" value="@if(isset($Item['SL1GlazingBeadSpecies'])){{$Item['SL1GlazingBeadSpecies']}}@endif">
                                                    
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="SL1Width">SL1 Width  (Max-value:600)
                                                    @if(!empty($tooltip->SL1Width))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->SL1Width}}'));
                                                    </script>
                                                    @endif

                                                    </label>
                                                    <input name="SL1Width" max="600" @if(empty($Item['SL1Width'])){{'readonly'}}@endif id="SL1Width" class="form-control door-configuration"
                                                        type="text" value="@if(isset($Item['SL1Width'])){{$Item['SL1Width']}}@endif">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="SL1Height" readonly>SL1 Height
                                                    @if(!empty($tooltip->SL1Height))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->SL1Height}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <input name="SL1Height" @if(empty($Item['SL1Height'])){{'readonly'}}@endif id="SL1Height" class="form-control door-configuration"
                                                        type="text" value="@if(isset($Item['SL1Height'])){{$Item['SL1Height']}}@endif">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="SL1Depth">SL1 Depth
                                                    @if(!empty($tooltip->SL1Depth))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->SL1Depth}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <input name="SL1Depth" @if(empty($Item['SL1Depth'])){{'readonly'}}@endif id="SL1Depth" class="form-control"
                                                        type="text" value="@if(isset($Item['SL1Depth'])){{$Item['SL1Depth']}}@endif">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="SL1Transom">SL1 Transom
                                                    @if(!empty($tooltip->SL1Transom))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->SL1Transom}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <select name="SL1Transom" @if(empty($Item['SL1Transom'])){{'disabled'}}@endif id="SL1Transom" class="form-control">
                                                        <option value="">Select side light 1 transom</option>
                                                        <option value="No">No</option>
                                                        @foreach($option_data as $row)
                                                        @if($row->OptionSlug=='SideLight1_transom')
                                                        <option value="{{$row->OptionKey}}" @if(isset($Item['SL1Transom'])) @if($Item['SL1Transom'] == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>

                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="sideLight2">Side Light 2 (SL2)
                                                    @if(!empty($tooltip->sideLight2))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->sideLight2}}'));
                                                    </script>
                                                    @endif

                                                    </label>
                                                    <select name="sideLight2" id="sideLight2" class="form-control door-configuration" required>
                                                        <option value=""> Is side light 2 is active?</option>
                                                        @foreach($option_data as $row)
                                                        @if($row->OptionSlug=='SideLight2')
                                                        <option value="{{$row->OptionKey}}" 
                                                            @if(isset($Item['SideLight2'])) 
                                                                @if($Item['SideLight2'] == $row->OptionKey) 
                                                                    {{'selected'}} 
                                                                @endif 
                                                            @elseif($row->OptionKey == "No")
                                                                {{'selected'}}
                                                            @endif>{{$row->OptionValue}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="copyOfSideLite1">Do you want to copy Same as
                                                        SL1?
                                                    @if(!empty($tooltip->copyOfSideLite1))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->copyOfSideLite1}}'));
                                                    </script>
                                                    @endif
                                                        </label>
                                                    <select name="copyOfSideLite1" id="copyOfSideLite1" class="form-control door-configuration"
                                                    @if(empty($Item['DoYouWantToCopySameAsSL1'])){{'disabled'}}@endif>
                                                        <option value=""> Do you want to copy Same as SL1?</option>
                                                        @foreach($option_data as $row)
                                                        @if($row->OptionSlug=='copy_Same_as_SL1')
                                                        <option value="{{$row->OptionKey}}" @if(isset($Item['DoYouWantToCopySameAsSL1'])) @if($Item['DoYouWantToCopySameAsSL1'] == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                                                        @endif
                                                        @endforeach

                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="sideLight2GlassType">Side Light 2 Glass Type
                                                    @if(!empty($tooltip->sideLight2GlassType))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->sideLight2GlassType}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <select name="sideLight2GlassType" 
                                                        @if(isset($Item['SideLight2']) && $Item['SideLight2'] == "Yes")
                                                            @if(isset($Item['DoYouWantToCopySameAsSL1']) && $Item['DoYouWantToCopySameAsSL1'] == "Yes")
                                                                {{'disabled'}}
                                                            @endif
                                                        @else
                                                            {{'disabled'}}
                                                        @endif
                                                        id="sideLight2GlassType"
                                                        class="form-control">
                                                        <option value="">Select Glass Type</option>
                                                        <!-- @foreach($option_data as $row)
                                        @if($row->OptionSlug=='leaf1_glass_type')
                                          <option value="{{$row->OptionKey}}"  @if(isset($Item['SideLight2GlassType'])) @if($Item['SideLight2GlassType'] == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                                        @endif
                                        @endforeach -->
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="SideLight2BeadingType">Side Light 2 Beading
                                                        Type
                                                    @if(!empty($tooltip->SideLight2BeadingType))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->SideLight2BeadingType}}'));
                                                    </script>
                                                    @endif
                                                        </label>
                                                    <select name="SideLight2BeadingType" id="SideLight2BeadingType" 
                                                        @if(isset($Item['SideLight2']) && $Item['SideLight2'] == "Yes")
                                                            @if(isset($Item['DoYouWantToCopySameAsSL1']) && $Item['DoYouWantToCopySameAsSL1'] == "Yes")
                                                                {{'disabled'}}
                                                            @endif
                                                        @else
                                                            {{'disabled'}}
                                                        @endif 
                                                        class="form-control">
                                                        <option value="">Select Beading Type</option>
                                                        <!-- @foreach($option_data as $row)
                                        @if($row->OptionSlug=='leaf1_glass_type')
                                          <option value="{{$row->OptionKey}}" @if(isset($Item['SideLight2BeadingType'])) @if($Item['SideLight2BeadingType'] == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                                        @endif
                                        @endforeach -->
                                                    </select>


                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group input-icons">
                                                    <label for="SideLight2GlazingBeadSpecies">Side Light 2 Glazing Bead
                                                        Species
                                                    @if(!empty($tooltip->SideLight2GlazingBeadSpecies))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->SideLight2GlazingBeadSpecies}}'));
                                                    </script>
                                                    @endif
                                                        </label>    
                                                    <i class="fa fa-info icon" id="SideLight2GlazingBeadSpeciesIcon"
                                                        onClick=""></i>
                                                    <input type="text" id="SideLight2GlazingBeadSpecies"
                                                        class="form-control" 
                                                        @if(isset($Item['SideLight2']) && $Item['SideLight2'] == "Yes")
                                                            @if(isset($Item['DoYouWantToCopySameAsSL1']) && $Item['DoYouWantToCopySameAsSL1'] == "Yes")
                                                                {{'disabled'}}
                                                            @endif
                                                        @else
                                                            {{'disabled'}}
                                                        @endif 
                                                        value="">
                                                    <input type="hidden" name="SideLight2GlazingBeadSpecies" value="@if(isset($Item['SideLight2GlazingBeadSpecies'])){{$Item['SideLight2GlazingBeadSpecies']}}@endif">
                                                
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="SL2Width">SL2 Width (Max-value:600)
                                                    @if(!empty($tooltip->SL2Width))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->SL2Width}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <input name="SL2Width" max="600" 
                                                        @if(isset($Item['SideLight2']) && $Item['SideLight2'] == "Yes")
                                                            @if(isset($Item['DoYouWantToCopySameAsSL1']) && $Item['DoYouWantToCopySameAsSL1'] == "Yes")
                                                                {{'readonly'}}
                                                            @endif
                                                        @else
                                                            {{'readonly'}}
                                                        @endif 
                                                    id="SL2Width" class="form-control door-configuration"
                                                        type="text" value="@if(isset($Item['SL2Width'])){{$Item['SL2Width']}}@endif">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="SL2Height">SL2 Height
                                                    @if(!empty($tooltip->SL2Height))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->SL2Height}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <input name="SL2Height" 
                                                        @if(isset($Item['SideLight2']) && $Item['SideLight2'] == "Yes")
                                                            @if(isset($Item['DoYouWantToCopySameAsSL1']) && $Item['DoYouWantToCopySameAsSL1'] == "Yes")
                                                                {{'readonly'}}
                                                            @endif
                                                        @else
                                                            {{'readonly'}}
                                                        @endif 
                                                     id="SL2Height" class="form-control door-configuration"
                                                        type="text" value="@if(isset($Item['SL2Height'])){{$Item['SL2Height']}}@endif">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="SL2Depth">SL2 Depth
                                                    @if(!empty($tooltip->SL2Depth))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->SL2Depth}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <input name="SL2Depth"
                                                        @if(isset($Item['SideLight2']) && $Item['SideLight2'] == "Yes")
                                                            @if(isset($Item['DoYouWantToCopySameAsSL1']) && $Item['DoYouWantToCopySameAsSL1'] == "Yes")
                                                                {{'readonly'}}
                                                            @endif
                                                        @else
                                                            {{'readonly'}}
                                                        @endif 
                                                    
                                                     id="SL2Depth" class="form-control"
                                                        type="text" value="@if(isset($Item['SL2Depth'])){{$Item['SL2Depth']}}@endif">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="SL2Transom">SL2 Transom
                                                    @if(!empty($tooltip->SL2Transom))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->SL2Transom}}'));
                                                    </script>
                                                    @endif

                                                    </label>
                                                    <select name="SL2Transom" 
                                                        @if(isset($Item['SideLight2']) && $Item['SideLight2'] == "Yes")
                                                            @if(isset($Item['DoYouWantToCopySameAsSL1']) && $Item['DoYouWantToCopySameAsSL1'] == "Yes")
                                                                {{'disabled'}}
                                                            @endif
                                                        @else
                                                            {{'disabled'}}
                                                        @endif 
                                                    id="SL2Transom" class="form-control">
                                                        <option value="">Select side light 2 transom</option>
                                                        <option value="No">No</option>
                                                        @foreach($option_data as $row)
                                                        @if($row->OptionSlug=='SideLight2_transom')
                                                        <option value="{{$row->OptionKey}}" @if(isset($Item['SL2Transom'])) @if($Item['SL2Transom'] == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group input-icons">
                                                    <label for="SLtransomHeightFromTop">Transom height from top
                                                        @if(!empty($tooltip->SLtransomHeightFromTop))
                                                            <script type="text/javascript">
                                                                document.write(Tooltip('{{$tooltip->SLtransomHeightFromTop}}'));
                                                            </script>
                                                        @endif
                                                    </label> 
                                                    <input type="text" id="SLtransomHeightFromTop" name="SLtransomHeightFromTop"
                                                            class="form-control" value="@if(isset($Item['SLtransomHeightFromTop'])){{$Item['SLtransomHeightFromTop']}}@endif">
                                                  
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group input-icons">
                                                    <label for="SLtransomThickness">Transom Thickness (Min 32mm)
                                                        @if(!empty($tooltip->SLtransomThickness))
                                                            <script type="text/javascript">
                                                                document.write(Tooltip('{{$tooltip->SLtransomThickness}}'));
                                                            </script>
                                                        @endif
                                                    </label>                                                    
                                                    <input type="number" min="32" id="SLtransomThickness" name="SLtransomThickness"
                                                            class="form-control" value="@if(isset($Item['SLtransomThickness'])){{$Item['SLtransomThickness']}}@endif">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
