<!-- Over Panel Section -->
                        <div class="main-card mb-3 custom_card">
                            <div>
                                <div class="tab-content">
                                    <div class="card-header">
                                        <h5 class="card-title" style="margin-top: 10px">Over Panel Section</h5>
                                    </div>
                                    <div>
                                        <div class="form-row">
                                        <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="overpanel">Overpanel
                                                    @if(!empty($tooltip->overpanel))
                                                    <script type="text/javascript">
                                                    //(Tooltip('{{$tooltip->overpanel}}'));
                                                    </script>
                                                    @endif

                                                    </label>
                                                    <select required name="overpanel" id="overpanel"
                                                        class="form-control change-event-calulation door-configuration">
                                                        <option value="">Is Over Panel Active?</option>
                                                        @foreach($option_data as $row)
                                                        @if($row->OptionSlug=='door_configuration_overpanel')
                                                        <option value="{{$row->OptionKey}}"
                                                        @if(isset($Item['Overpanel']))
                                                            @if($Item['Overpanel'] == $row->OptionKey)
                                                                {{'selected'}}
                                                            @endif
                                                        @else
                                                            @if($row->OptionKey == "No")
                                                                {{'selected'}}
                                                            @endif
                                                        @endif

                                                        >{{$row->OptionValue}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            {{-- <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="OPLippingThickness">OP Lipping Thickness
                                                    @if(!empty($tooltip->OPLippingThickness))
                                                    <script type="text/javascript">
                                                    //(Tooltip('{{$tooltip->OPLippingThickness}}'));
                                                    </script>
                                                    @endif
                                                    </label> --}}
                                                    <select name="OPLippingThickness"
                                                         hidden>
                                                        <option value="">Select Op Thickness</option>
                                                        <option value="0" selected>0</option>

                                                        {{-- @foreach($option_data as $row)
                                                        @if($row->OptionSlug=='op_lipping_thickness')
                                                        <option value="{{$row->OptionKey}}" @if(isset($Item['OPLippingThickness'])) @if($Item['OPLippingThickness'] == $row->OptionKey){{'selected'}}@endif @endif>{{$row->OptionValue}}</option>
                                                        @endif
                                                        @endforeach --}}
                                                    </select>
                                                {{-- </div>
                                            </div> --}}
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="oPWidth">OP Width
                                                    @if(!empty($tooltip->oPWidth))
                                                    <script type="text/javascript">
                                                    //(Tooltip('{{$tooltip->oPWidth}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <input name="oPWidth" id="oPWidth" readonly class="form-control oPWidth door-configuration"
                                                        type="text" value="@if(isset($Item['OPWidth'])){{$Item['OPWidth']}}@endif">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="oPHeigth">OP Height (Max-value:600)
                                                    @if(!empty($tooltip->oPHeigth))
                                                    <script type="text/javascript">
                                                    //(Tooltip('{{$tooltip->oPHeigth}}'));
                                                    </script>
                                                    @endif

                                                    </label>
                                                    <input required  name="oPHeigth" id="oPHeigth" max="600" class="form-control door-configuration"
                                                        type="number" value="@if(isset($Item['OPHeigth'])){{$Item['OPHeigth']}}@endif">
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="OpBeadThickness">Fan Light/ Over Panel Frame Thickness
                                                    @if(!empty($tooltip->OpBeadThickness))
                                                    <script type="text/javascript">
                                                    //(Tooltip('{{$tooltip->OpBeadThickness}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <input name="OpBeadThickness" id="OpBeadThickness" @if(empty(@$Item['OpBeadThickness'])) readonly @else required @endif class="form-control OpBeadThickness door-configuration"
                                                        type="text" value="@if(isset($Item['OpBeadThickness'])){{$Item['OpBeadThickness']}}@endif">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="OpBeadHeight">Fan Light/ Over Panel Frame Depth
                                                    @if(!empty($tooltip->OpBeadHeight))
                                                    <script type="text/javascript">
                                                    //(Tooltip('{{$tooltip->OpBeadHeight}}'));
                                                    </script>
                                                    @endif

                                                    </label>
                                                    <input @if(empty(@$Item['OpBeadHeight'])) readonly @else required @endif  name="OpBeadHeight" id="OpBeadHeight" max="600" class="form-control door-configuration"
                                                        type="number" value="@if(isset($Item['OpBeadHeight'])){{$Item['OpBeadHeight']}}@endif">
                                                </div>
                                            </div>


                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="opTransom">OP Transom
                                                    @if(!empty($tooltip->opTransom))
                                                    <script type="text/javascript">
                                                    //(Tooltip('{{$tooltip->opTransom}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <select id="opTransom" name="opTransom" class="form-control">
                                                    <option value="No" @if(isset($Item['OPTransom'])) @if($Item['OPTransom'] == 'No') {{'selected'}} @endif @endif>No</option>
                                                        <option value="1" @if(isset($Item['OPTransom'])) @if($Item['OPTransom'] == 1) {{'selected'}} @endif @endif>1</option>
                                                        <option value="2" @if(isset($Item['OPTransom'])) @if($Item['OPTransom'] == 2) {{'selected'}} @endif @endif>2</option>
                                                        <option value="3" @if(isset($Item['OPTransom'])) @if($Item['OPTransom'] == 3) {{'selected'}} @endif @endif>3</option>
                                                        <option value="4" @if(isset($Item['OPTransom'])) @if($Item['OPTransom'] == 4) {{'selected'}} @endif @endif>4</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="transomThickness">Transom Thickness
                                                    @if(!empty($tooltip->transomThickness))
                                                    <script type="text/javascript">
                                                    //(Tooltip('{{$tooltip->transomThickness}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <select name="transomThickness" id="transomThickness" class="form-control">
                                                        <option value="">Select transform thickness</option>
                                                        @foreach($option_data as $row)
                                                        @if($row->OptionSlug=='transom_thickness')
                                                        <option value="{{$row->OptionKey}}" @if(isset($Item['TransomThickness'])) @if($Item['TransomThickness'] == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="opGlassInopGlassIntegritytegrity">Fan Light Glass
                                                        Integrity
                                                    @if(!empty($tooltip->opGlassIntegrity))
                                                    <script type="text/javascript">
                                                    //(Tooltip('{{$tooltip->opGlassIntegrity}}'));
                                                    </script>
                                                    @endif
                                                        </label>

                                                    <select required name="opGlassIntegrity"
                                                        id="opGlassIntegrity" class="form-control">
                                                        <option value=''> Select OP Glass Integrity</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="opGlassType">Fan Light Glass Type
                                                    @if(!empty($tooltip->opGlassType))
                                                    <script type="text/javascript">
                                                    //(Tooltip('{{$tooltip->opGlassType}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <select required name="opGlassType" id="opGlassType" class="form-control">
                                                        <option value="">Select OP Glass Type</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="opGlazingBeads">Fan Light Glazing Beads
                                                    @if(!empty($tooltip->opGlazingBeads))
                                                    <script type="text/javascript">
                                                    //(Tooltip('{{$tooltip->opGlazingBeads}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <select required name="opGlazingBeads" id="opGlazingBeads" class="form-control">
                                                        <option value="">Select Glazing Beads</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group input-icons">
                                                    <label for="opGlazingBeadSpecies">Fan Light Glazing Bead Species
                                                    @if(!empty($tooltip->opGlazingBeadSpecies))
                                                    <script type="text/javascript">
                                                    //(Tooltip('{{$tooltip->opGlazingBeadSpecies}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <!-- <select name="opGlazingBeadSpecies" id="opGlazingBeadSpecies"
                                                        class="form-control">
                                                        <option value="">Select Species</option>
                                                    </select> -->
                                                        <i class="fa fa-info icon" id="opGlazingBeadSpeciesIcon" onClick=""></i>
                                                        <input type="text" disabled id="opGlazingBeadSpecies"
                                                            class="form-control">
                                                        <input type="hidden" name="opGlazingBeadSpecies" value="@if(isset($Item['OPGlazingBeadSpecies'])){{$Item['OPGlazingBeadSpecies']}}@endif" id="opGlazingBeadSpeciesïd">

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
