   <!-- Main Options -->
   <div class="main-card mb-3 custom_card">
       <input type="hidden" name="issingleconfiguration" value="{{$issingleconfiguration}}">
       <input type="hidden" name="QuotationId" value="@if(isset($QuotationId)){{$QuotationId}}@else{{''}}@endif">
       <input type="hidden" name="itemID" value="@if(isset($Item["itemId"])){{$Item["itemId"]}}@else{{''}}@endif">
       <div>
           <div>
               <div class="card-header">
                   <h5 class="card-title" style="margin-top: 10px">Main Options</h5>
               </div>
               <div>
                   <div class="form-row">
                       {{--  <div class="col-md-6">
                           <div class="position-relative form-group">
                               <label for="leafConstruction">Leaf Construction
                                   @if(!empty($tooltip->leafConstruction))
                                   <script type="text/javascript">
                                   document.write(Tooltip('{{$tooltip->leafConstruction}}'));
                                   </script>
                                   @endif
                               </label>
                               <select name="leafConstruction" id="leafConstruction" class="form-control" required>
                                   <option value="">Select any Leaf Construction</option>
                                   @foreach($option_data as $row)
                                   @if($row->OptionSlug=='leaf_construction')
                                   <option value="{{$row->OptionKey}}" @if(isset($Item['LeafConstruction']))
                                       @if($Item["LeafConstruction"]==$row->OptionKey){{'selected'}} @endif
                                       @endif>{{$row->OptionValue}}</option>
                                   @endif
                                   @endforeach
                               </select>
                           </div>
                       </div>  --}}
                       <div class="col-md-12">
                           <div class="position-relative form-group d-flex">
                               <label for="Dropseal">Four Sided Frame</label>
                               <input type="checkbox" name="FourSidedFrame" id="foursidedframe" class="change-event-calulation form-control custom-checkbox" style="margin: 2px -4px 10px 12px;border: 1px solid rgb(206, 212, 218);display: inline-block;height: 15px;width: 15px;" value="1" @if(!empty($Item['FourSidedFrame']) && $Item['FourSidedFrame'] == 1){{'checked'}}@endif>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="position-relative form-group">
                               <label for="doorType">Door Type
                                   @if(!empty($tooltip->doorType))
                                   <script type="text/javascript">
                                   document.write(Tooltip('{{$tooltip->doorType}}'));
                                   </script>
                                   @endif
                               </label>
                               <input type="text" name="doorType" id="doorType" placeholder="Enter door type"
                                   value="@if(isset($Item['DoorType'])){{$Item['DoorType']}}@else{{''}}@endif"
                                   class="form-control" required>
                           </div>
                       </div>
                       <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="intumescentLeafType">Leaf Type
                                @if(!empty($tooltip->intumescentLeafType))
                                <script type="text/javascript">
                                document.write(Tooltip('{{$tooltip->intumescentLeafType}}'));
                                </script>
                                @endif
                            </label>
                            <select name="intumescentLeafType" id="intumescentLeafType" class="form-control intumescentLeafchange" required>
                                <option value="">Select Leaf Type</option>
                                @foreach($leafTypeIntumescentseal as $row)
                                    <option value="{{$row->id}}" @if(isset($Item["IntumescentLeafType"]))
                                        @if($Item["IntumescentLeafType"]==$row->id){{'selected'}} @endif
                                        @endif>{{$row->leaf_type_key}} ({{$row->leaf_type_value}})</option>
                                    @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                           <div class="position-relative form-group">
                               <label for="fireRating">Fire Rating
                                   @if(!empty($tooltip->fireRating))
                                   <script type="text/javascript">
                                   document.write(Tooltip('{{$tooltip->fireRating}}'));
                                   </script>
                                   @endif
                               </label>
                               <select name="fireRating" id="fireRating" class="form-control change-event-calulation" required>
                                   <option value="">Select fire rating</option>
                                  {{-- @foreach($option_data as $row)
                                   @if($row->OptionSlug=='fire_rating')
                                   <option value="{{$row->OptionKey}}" @if(isset($Item["FireRating"]))
                                       @if($Item["FireRating"]==$row->OptionKey){{'selected'}} @endif
                                       @endif>{{$row->OptionValue}}</option>
                                   @endif
                                   @endforeach --}}
                               </select>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="position-relative form-group">
                               <label for="doorsetType">Doorset Type
                                   @if(!empty($tooltip->doorsetType))
                                   <script type="text/javascript">
                                   document.write(Tooltip('{{$tooltip->doorsetType}}'));
                                   </script>
                                   @endif
                                   <span class="dsl"></span>

                               </label>
                               <!-- combination_of -->
                               <select name="doorsetType" id="doorsetType"
                                   class="form-control combination_of change-event-calulation door-configuration" required>
                                   <option value="">Select door set type</option>
                                   @foreach($option_data as $row)
                                   @if($row->OptionSlug=='door_configuration_doorset_type')
                                   <option value="{{$row->OptionKey}}" @if(isset($Item['DoorsetType']))
                                       @if($Item["DoorsetType"]==$row->OptionKey){{'selected'}} @endif @endif
                                       >{{$row->OptionValue}}</option>
                                   @endif
                                   @endforeach
                               </select>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="position-relative form-group">
                               <label for="swingType">Swing Type
                                   @if(!empty($tooltip->swingType))
                                   <script type="text/javascript">
                                   document.write(Tooltip('{{$tooltip->swingType}}'));
                                   </script>
                                   @endif
                                   <span class="dsl"></span>
                               </label>
                               <select name="swingType" id="swingType" class="form-control combination_of" required>
                                   <option value="">Select swing type</option>
                                   @foreach($option_data as $row)
                                   @if($row->OptionSlug=='door_configuration_swing_type')
                                   <option value="{{$row->OptionKey}}" @if(isset($Item["SwingType"]))
                                       @if($Item["SwingType"]==$row->OptionKey) {{'selected'}} @endif @endif
                                       >{{$row->OptionValue}}</option>
                                   @endif
                                   @endforeach
                               </select>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="position-relative form-group">
                               <label for="latchType">Latch Type
                                   @if(!empty($tooltip->latchType))
                                   <script type="text/javascript">
                                   document.write(Tooltip('{{$tooltip->latchType}}'));
                                   </script>
                                   @endif
                                   <span class="dsl"></span>
                               </label>
                               <select name="latchType" id="latchType" class="form-control combination_of">
                                   <option value="">Select latch type</option>
                                   @foreach($option_data as $row)
                                   @if($row->OptionSlug=='door_configuration_latch_type')
                                   <option value="{{$row->OptionKey}}" @if(!empty($Item['LatchType']))
                                       @if($Item['LatchType']==$row->OptionKey) {{'selected'}} @endif @endif
                                       >{{$row->OptionValue}}</option>
                                   @endif
                                   @endforeach
                               </select>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="position-relative form-group">
                               <label for="Handing">Handing
                                   @if(!empty($tooltip->Handing))
                                   <script type="text/javascript">
                                   document.write(Tooltip('{{$tooltip->Handing}}'));
                                   </script>
                                   @endif
                               </label>
                               <select required name="Handing" id="Handing" class="form-control">
                                   <option value="">Select Handing</option>
                               </select>
                           </div>
                       </div>
                       <div class="col-md-6">
                           <div class="position-relative form-group">
                               <label for="OpensInwards">Pull Towards
                                   @if(!empty($tooltip->OpensInwards))
                                   <script type="text/javascript">
                                   document.write(Tooltip('{{$tooltip->OpensInwards}}'));
                                   </script>
                                   @endif
                               </label>
                               <select required name="OpensInwards" id="OpensInwards" class="form-control">
                                   <option value="">Select Pull Towards</option>
                                   @foreach($option_data as $row)
                                   @if($row->OptionSlug=='Opens_Inwards')
                                   <option value="{{$row->OptionKey}}" @if(!empty($Item['OpensInwards']))
                                       @if($Item['OpensInwards']==$row->OptionKey) {{'selected'}} @endif
                                       @endif>{{$row->OptionValue}}</option>
                                   @endif
                                   @endforeach
                               </select>
                           </div>
                       </div>
                       {{-- NO NEED TO ADD COC --}}
                       {{-- COMMENT DATE 29-11-2023  --}}
                       {{-- <div class="col-md-6">
                           <div class="position-relative form-group">
                               <label for="COC">COC

                                   @if(!empty($tooltip->COC))
                                   <script type="text/javascript">
                                   document.write(Tooltip('{{$tooltip->COC}}'));
                                   </script>
                                   @endif
                               </label>
                               <select name="COC" id="COC" class="form-control" required>
                                   <option value="">Select COC</option>
                                   @foreach($option_data as $row)
                                   @if($row->OptionSlug=='COC')
                                   <option value="{{$row->OptionKey}}" @if(!empty($Item['COC']))
                                       @if($Item['COC']==$row->OptionKey) {{'selected'}} @endif
                                       @endif>{{$row->OptionValue}}</option>
                                   @endif
                                   @endforeach
                               </select>
                           </div>
                       </div> --}}
                       <div class="col-md-6 framehideshow">
                           <div class="position-relative form-group">
                               <label for="tollerance">Tolerance
                                   @if(!empty($tooltip->tollerance))
                                   <script type="text/javascript">
                                   document.write(Tooltip('{{$tooltip->tollerance}}'));
                                   </script>
                                   @endif
                               </label>
                               <input required type="number" min="0" max="20" step="any" id="tollerance" value="@if(isset($Item["Tollerance"])){{$Item["Tollerance"]}}@else{{''}}@endif" name="tollerance"
                                   class="form-control for_c_leaf_height  forleafHeightNoOP change-event-calulation door-configuration" required>
                           </div>
                       </div>
                       <div class="col-md-12">
                            <div class="position-relative form-group d-flex">
                                <label for="Dropseal">Dropseal</label>
                                <input type="checkbox" name="Dropseal" id="Dropseal" class="change-event-calulation form-control" style="margin: 2px -4px 10px 12px;border: 1px solid rgb(206, 212, 218);display: inline-block;height: 15px;width: 15px;" value="1" @if(!empty($Item['Dropseal']) && $Item['Dropseal'] == 1){{'checked'}}@endif>
                            </div>
                        </div>
                       <div class="col-md-6 framehideshow">
                           <div class="position-relative form-group">
                               <label for="undercut">Undercut
                                   @if(!empty($tooltip->undercut))
                                   <script type="text/javascript">
                                   document.write(Tooltip('{{$tooltip->undercut}}'));
                                   </script>
                                   @endif
                               </label>
                               <input type="number" id="undercut" readonly name="undercut" value="@if(isset($Item["Undercut"])){{$Item["Undercut"]}}@else{{''}}@endif"
                                   class="form-control change-event-calulation  undercut door-configuration" required>
                           </div>
                       </div>
                       <div class="col-md-6 framehideshow" id="floor_finish">
                           <div class="position-relative form-group">
                               <label for="floorFinish">Floor Finish
                                   @if(!empty($tooltip->floorFinish))
                                   <script type="text/javascript">
                                   document.write(Tooltip('{{$tooltip->floorFinish}}'));
                                   </script>
                                   @endif
                                </label>
                               <input type="number" id="floorFinish" name="floorFinish" value="@if(isset($Item["FloorFinish"])){{$Item["FloorFinish"]}}@else{{''}}@endif"
                                   class="form-control change-event-calulation forundercut door-configuration" required>
                           </div>
                       </div>
                       <div class="col-md-6 framehideshow">
                           <div class="position-relative form-group">
                               <label for="gap">GAP (Min:2 Max:4)
                                   @if(!empty($tooltip->gap))
                                   <script type="text/javascript">
                                   document.write(Tooltip('{{$tooltip->gap}}'));
                                   </script>
                                   @endif
                               </label>
                               <label for="gap_NFR" style="display: none;">GAP</label>
                               <input type="number" min="2" max="4" id="gap" name="gap" value="@if(isset($Item["GAP"])){{$Item["GAP"]}}@else{{''}}@endif"
                                   class="form-control for_c_leaf_height  forleafHeightNoOP change-event-calulation door-configuration" required>
                           </div>
                       </div>
                       <div class="col-md-6 framehideshow">
                           <div class="position-relative form-group">
                               <label for="frameThickness">Frame Thickness
                                   @if(!empty($tooltip->frameThickness))
                                   <script type="text/javascript">
                                   document.write(Tooltip('{{$tooltip->frameThickness}}'));
                                   </script>
                                   @endif
                               </label>
                               {{-- <select name="frameThickness"
                                   class="form-control change-event-calulation door-configuration" id="frameThickness" required>
                                   <option value="" @if(isset($Item["FrameThickness"])) @if($Item["FrameThickness"]==""
                                       ) {{'selected'}} @endif @endif>Select Frame thickness</option>
                                   <option value="30" @if(isset($Item["FrameThickness"]))
                                       @if($Item["FrameThickness"]=="30" ) {{'selected'}} @endif @endif>30</option>
                                   <option value="32" @if(isset($Item["FrameThickness"]))
                                       @if($Item["FrameThickness"]=="32" ) {{'selected'}} @endif @endif>32</option>
                               </select> --}}
                               <input type="number" id="frameThickness" name="frameThickness" value="@if(isset($Item["FrameThickness"])){{$Item["FrameThickness"]}}@else{{''}}@endif"
                                   class="form-control change-event-calulation door-configuration" required pattern="\d*" maxlength="5" oninput="if(this.value.length > 5) this.value = this.value.slice(0, 5);">
                           </div>
                       </div>
                       <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="ironmongerySet">Ironmongery Set
                            @if(!empty($tooltip->ironmongerySet))
                            <script type="text/javascript">
                            document.write(Tooltip('{{$tooltip->ironmongerySet}}'));
                            </script>
                            @endif
                            </label>
                            <label for="ironmogery_set" style="display: none;">Ironmongery Set</label>
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
                            <label for="select_ironmogery_set" style="display: none;">Select Ironmongery Set</label>
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

                   </div>
               </div>
           </div>
       </div>
   </div>

   <input type="hidden" id="currentLeafType" value="{{ isset($Item['IntumescentLeafType']) ? $Item['IntumescentLeafType'] : '' }}">
   <input type="hidden" id="currentFireRating" value="{{ isset($Item['FireRating']) ? $Item['FireRating'] : '' }}">

