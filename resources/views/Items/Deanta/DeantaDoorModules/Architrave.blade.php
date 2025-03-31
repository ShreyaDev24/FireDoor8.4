
                        <!-- ARCHITRAVE -->
                        <div class="main-card mb-3 custom_card">
                            <div>
                                <div class="tab-content">
                                    <div class="card-header">
                                        <h5 class="card-title" style="margin-top: 10px">Architrave </h5>
                                    </div>
                                    <div>
                                        <div class="form-row">
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="Architrave">Architrave
                                                    @if(!empty($tooltip->Architrave))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->Architrave}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <select name="Architrave" id="Architrave" class="form-control">
                                                        <option value="">Select Architrave</option>
                                                        @foreach($option_data as $row)
                                                        @if($row->OptionSlug=='Architrave')
                                                        <option value="{{$row->OptionKey}}"
                                                            @if(isset($Item['Architrave']))
                                                                @if($Item['Architrave'] == $row->OptionKey)
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
                                                <div class="position-relative form-group input-icons">
                                                    <label for="architraveMaterial">Architrave Material
                                                    @if(!empty($tooltip->architraveMaterial))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->architraveMaterial}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <i class="fa fa-info icon cursor-pointer" id="architraveMaterialIcon"></i>
                                                    <input  type="text" id="architraveMaterial" class="form-control" readonly  value="@if(isset($Item['ArchitraveMaterial'])){{$Item['ArchitraveMaterial']}}@endif">
                                                    <input type="hidden" name="architraveMaterial" id="architraveMaterialNew"
                                                        value="@if(isset($Item['ArchitraveMaterial'])){{$Item['ArchitraveMaterial']}}@endif">
                                                    <!-- <select name="" id=""
                                                        @if(isset($Item['Architrave']))
                                                            @if($Item['Architrave'] != "Yes")
                                                                {{'disabled'}}
                                                            @endif
                                                        @else
                                                            {{'disabled'}}
                                                        @endif

                                                        class="form-control">
                                                        <option value="">Select Architrave Material</option>
                                                        @foreach($option_data as $row)
                                                        @if($row->OptionSlug=='Architrave_Material')
                                                        <option value="{{$row->OptionKey}}" @if(isset($Item['ArchitraveMaterial'])) @if($Item['ArchitraveMaterial'] == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select> -->
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="architraveType">Architrave Type
                                                    @if(!empty($tooltip->architraveType))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->architraveType}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <select  name="architraveType" id="architraveType" option_slug = "Architrave_Type"
                                                        @if(isset($Item['Architrave']))
                                                            @if($Item['Architrave'] != "Yes")
                                                                {{'disabled'}}
                                                            @endif
                                                        @else
                                                            {{'disabled'}}
                                                        @endif
                                                         class="form-control">
                                                        <option value="">Select Architrave Type</option>
                                                        @foreach($ArchitraveType as $row)
                                                            <option value="{{$row->Key}}" @if(isset($Item['ArchitraveType'])) @if($Item['ArchitraveType'] == $row->Key) {{'selected'}} @endif @endif>{{$row->ArchitraveType}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="architraveWidth">Architrave Width
                                                    @if(!empty($tooltip->architraveWidth))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->architraveWidth}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <input name="architraveWidth" id="architraveWidth"
                                                        @if(isset($Item['Architrave']))
                                                            @if($Item['Architrave'] != "Yes")
                                                                {{'readonly'}}
                                                            @endif
                                                        @else
                                                            {{'readonly'}}
                                                        @endif
                                                        placeholder="Architrave Width" class="form-control" type="text"
                                                        value="@if(isset($Item['ArchitraveWidth'])){{$Item['ArchitraveWidth']}}@endif">
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="architraveHeight">Architrave Thickness
                                                    @if(!empty($tooltip->architraveHeight))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->architraveHeight}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <label for="architrave_Height" style="display: none;">Architrave Thickness</label>
                                                    <input name="architraveHeight" id="architraveHeight"
                                                        @if(isset($Item['Architrave']))
                                                            @if($Item['Architrave'] != "Yes")
                                                                {{'readonly'}}
                                                            @endif
                                                        @else
                                                            {{'readonly'}}
                                                        @endif
                                                        placeholder="Architrave Thickness" class="form-control" type="text"
                                                        value="@if(isset($Item['ArchitraveHeight'])){{$Item['ArchitraveHeight']}}@endif">
                                                </div>
                                            </div>

                                            {{-- <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="architraveDepth">Architrave Depth
                                                    @if(!empty($tooltip->architraveDepth))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->architraveDepth}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <input name="architraveDepth" id="architraveDepth"
                                                        @if(isset($Item['Architrave']))
                                                            @if($Item['Architrave'] != "Yes")
                                                                {{'readonly'}}
                                                            @endif
                                                        @else
                                                            {{'readonly'}}
                                                        @endif
                                                        placeholder="Architrave Depth" class="form-control" type="text"
                                                        value="@if(isset($Item['ArchitraveDepth'])){{$Item['ArchitraveDepth']}}@endif">
                                                </div>
                                            </div> --}}

                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="architraveFinish">Architrave Finish
                                                    @if(!empty($tooltip->architraveFinish))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->architraveFinish}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <select name="architraveFinish" id="architraveFinish"
                                                        @if(isset($Item['Architrave']))
                                                            @if($Item['Architrave'] != "Yes")
                                                                {{'disabled'}}
                                                            @endif
                                                        @else
                                                            {{'disabled'}}
                                                        @endif
                                                        class="form-control">
                                                        <option value="">Select Architrave Finish</option>
                                                        @foreach($option_data as $row)
                                                        @if($row->OptionSlug=='Architrave_Finish')
                                                        <option value="{{$row->OptionKey}}" @if(isset($Item['ArchitraveFinish'])) @if($Item['ArchitraveFinish'] == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group input-icons">
                                                    <label for="architravefinishColor">Architrave Finish color
                                                    @if(!empty($tooltip->architravefinishColor))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->architravefinishColor}}'));
                                                    </script>
                                                    @endif
                                                 </label>
                                                    <i class="fa fa-info icon cursor-pointer" id="architraveFinishcolorIcon"></i>

                                                    <input type="text" id="architraveFinishcolor" name="architraveFinishcolor" class="form-control"  @if(empty($Item['ArchitraveFinishColor'])){{'readonly'}}@endif value="@if(isset($Item['ArchitraveFinishColor'])){{$Item['ArchitraveFinishColor']}}@endif">


                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="architraveSetQty">Architrave Set Qty
                                                    @if(!empty($tooltip->architraveSetQty))
                                                    <script type="text/javascript">
                                                    document.write(Tooltip('{{$tooltip->architraveSetQty}}'));
                                                    </script>
                                                    @endif
                                                    </label>
                                                    <select name="architraveSetQty" id="architraveSetQty"
                                                        @if(isset($Item['Architrave']))
                                                            @if($Item['Architrave'] != "Yes")
                                                                {{'disabled'}}
                                                            @endif
                                                        @else
                                                            {{'disabled'}}
                                                        @endif
                                                        class="form-control">
                                                        <option value="">Select Architrave Finish</option>
                                                        @foreach($option_data as $row)
                                                        @if($row->OptionSlug=='Architrave_Set_Qty')
                                                        <option value="{{$row->OptionKey}}" @if(isset($Item['ArchitraveSetQty'])) @if($Item['ArchitraveSetQty'] == $row->OptionKey) {{'selected'}} @endif @endif>{{$row->OptionValue}}</option>
                                                        @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <!-- <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                <label for="ironmongeryFinish">Ironmongery Finish</label>
                                            <input type="text" name="ironmongeryFinish" id="ironmongeryFinish" placeholder="Ironmongery Finish" class="form-control">
                                                </div>
                                            </div> -->




                                            <!-- <div class="col-md-6">
                                                <div class="position-relative form-group">
                                                    <label for="lockPositionHeight">Lock Position Height</label>
                                                    <input type="text" name="lockPositionHeight" placeholder="Lock Position Height" id="lockPositionHeight" class="form-control">
                                                </div>
                                            </div> -->

                                        </div>


                                    </div>
                                </div>
                            </div>

                        </div>
