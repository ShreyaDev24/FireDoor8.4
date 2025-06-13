
                                            <!-- Transport -->
                                            <div class="main-card mb-3 custom_card">
                                                <div>
                                                    <div class="tab-content">
                                                        <div class="card-header">
                                                            <h5 class="card-title" style="margin-top: 10px">Transport </h5>
                                                        </div>
                                                        <div>
                                                            <div class="form-row">
                                                                <div class="col-md-6">
                                                                    <div class="position-relative form-group">
                                                                        <label for="Vehicle Type">Vehicle Type
                                                                        @if(!empty($tooltip->VehicleType))
                                                                            <script type="text/javascript">
                                                                            document.write(Tooltip('{{$tooltip->VehicleType}}'));
                                                                            </script>
                                                                            @endif
                                                                        </label>
                                                                        <select name="vehicleType" id="vehicleType" class="form-control">
                                                                            <option value="Curtainsider" @if(isset($Item['VehicleType'])) @if($Item['VehicleType'] == "Curtainsider") {{'selected'}} @endif @endif>Curtainsider</option>
                                                                            <option value="Opentop" @if(isset($Item['VehicleType'])) @if($Item['VehicleType'] == "Opentop") {{'selected'}} @endif @endif>Opentop</option>
                                                                            <option value="Tail Lift" @if(isset($Item['VehicleType'])) @if($Item['VehicleType'] == "Tail Lift") {{'selected'}} @endif @endif>Tail Lift</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="position-relative form-group">
                                                                        <label for="deliveryTime">Delivery Time
                                                                        @if(!empty($tooltip->deliveryTime))
                                                                            <script type="text/javascript">
                                                                            document.write(Tooltip('{{$tooltip->deliveryTime}}'));
                                                                            </script>
                                                                            @endif
                                                                        </label>
                                                                        <select name="deliveryTime" id="deliveryTime" class="form-control">
                                                                            <option value="Yes" @if(isset($Item['DeliveryTime'])) @if($Item['DeliveryTime'] == "Yes") {{'selected'}} @endif @endif>Yes</option>
                                                                            <option value="No" @if(isset($Item['DeliveryTime'])) @if($Item['DeliveryTime'] == "No") {{'selected'}} @endif @endif>No</option>
                                                                        </select>
                                                                    </div>
                                                                </div>

                                                                <div class="col-md-6">
                                                                    <div class="position-relative form-group">
                                                                        <label for="packaging">Packaging
                                                                        @if(!empty($tooltip->packaging))
                                                                            <script type="text/javascript">
                                                                            document.write(Tooltip('{{$tooltip->packaging}}'));
                                                                            </script>
                                                                            @endif
                                                                        </label>
                                                                        <select name="packaging" id="packaging" class="form-control">
                                                                            <option value="cardbord" @if(isset($Item['Packaging'])) @if($Item['Packaging'] == "cardbord") {{'selected'}} @endif @endif>Cardbord</option>
                                                                            <option value="corryboard"  @if(isset($Item['Packaging'])) @if($Item['Packaging'] == "corryboard") {{'selected'}} @endif @endif>Corryboard</option>
                                                                        </select>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                               

                                            </div>
