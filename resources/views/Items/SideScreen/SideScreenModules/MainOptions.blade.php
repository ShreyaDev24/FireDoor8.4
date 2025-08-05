<!-- Main Options -->
<div class="main-card mb-3 custom_card">
    <input type="hidden" name="QuotationId"  value="@if (isset($QuotationId)){{$QuotationId }}@else{{ '' }} @endif">
    <input type="hidden" name="id" value="@if (isset($Item['id'])){{$Item['id'] }}@else{{ '' }} @endif">
    <div>
        <div>
            <div class="card-header">
                <h5 class="card-title" style="margin-top: 10px">Main Options</h5>
            </div>
            <div>
                <div class="form-row">
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="ScreenType">Screen Type</label>
                            <input type="text" name="ScreenType" id="ScreenType" placeholder="Enter Screen type"
                                value="@if (isset($Item['ScreenType'])){{$Item['ScreenType']}}@else{{ '' }} @endif"
                                class="form-control" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Tolerance">Tolerance</label>
                            <input type="number" name="Tolerance" id="Tolerance" placeholder="Enter Tolerance"
                                value="@if(isset($Item['Tolerance'])){{$Item['Tolerance']}}@else{{ '' }}@endif"
                                class="form-control change-event-calulation" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="FireRating">Fire Rating</label>
                            <select name="FireRating" id="FireRating" class="form-control " required>
                                <option value="">Select fire rating</option>
                                <option value="0-0" @if (isset($Item['FireRating'])) @if ($Item['FireRating'] == '0-0'){{ 'selected' }} @endif @endif>0-0</option>
                                <option value="30-0" @if (isset($Item['FireRating'])) @if ($Item['FireRating'] == '30-0'){{ 'selected' }} @endif @endif>30-0</option>
                                <option value="30-30" @if (isset($Item['FireRating'])) @if ($Item['FireRating'] == '30-30'){{ 'selected' }} @endif @endif>30-30</option>
                                <option value="60-0" @if (isset($Item['FireRating'])) @if ($Item['FireRating'] == '60-0'){{ 'selected' }} @endif @endif>60-0</option>
                                <option value="60-60" @if (isset($Item['FireRating'])) @if ($Item['FireRating'] == '60-60'){{ 'selected' }} @endif @endif>60-60</option>
                                <option value="IGU 0-0" @if (isset($Item['FireRating'])) @if ($Item['FireRating'] == 'IGU 0-0'){{ 'selected' }} @endif @endif>IGU 0-0</option>
                                <option value="IGU 30-0" @if (isset($Item['FireRating'])) @if ($Item['FireRating'] == 'IGU 30-0'){{ 'selected' }} @endif @endif>IGU 30-0</option>
                                <option value="IGU 30-30" @if (isset($Item['FireRating'])) @if ($Item['FireRating'] == 'IGU 30-30'){{ 'selected' }} @endif @endif>IGU 30-30</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="GlazingType">Glazing Type</label>
                            <select name="GlazingType" id="GlazingType" class="form-control " required>
                                <option value="">Select Glazing Type</option>
                                <option value="Single Pane" @if (isset($Item['GlazingType'])) @if ($Item['GlazingType'] == 'Single Pane'){{ 'selected' }} @endif @endif>Single Pane</option>
                                <option value="IGU" @if (isset($Item['GlazingType'])) @if ($Item['GlazingType'] == 'IGU'){{ 'selected' }} @endif @endif>IGU</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="AreSinglePaneEqualSizes">Are Single Pane equal sizes?</label>
                            <select name="AreSinglePaneEqualSizes" id="AreSinglePaneEqualSizes" class="form-control" required>
                                <option value="Yes"
                                    @if(isset($Item['AreSinglePaneEqualSizes']) && $Item['AreSinglePaneEqualSizes'] == 'Yes')
                                        selected
                                    @endif>
                                    Yes
                                </option>
                                <option value="No"
                                    @if(!isset($Item['AreSinglePaneEqualSizes']) || $Item['AreSinglePaneEqualSizes'] == 'No')
                                        selected
                                    @endif>
                                    No
                                </option>
                            </select>

                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="SinglePane">Single Pane A</label>
                            <select name="SinglePane" id="SinglePane" class="form-control " required>
                                <option value="">Select Single Pane A</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label for="SinglePaneB">Single Pane B</label>
                        <select name="SinglePaneB_disabled" id="SinglePaneB" class="form-control">
                            <option value="">Select Single Pane B</option>
                        </select>
                        <input type="hidden" name="SinglePaneB" id="SinglePaneB-hidden">
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label for="SinglePaneC">Single Pane C</label>
                        <select name="SinglePaneC_disabled" id="SinglePaneC" class="form-control">
                            <option value="">Select Single Pane C</option>
                        </select>
                        <input type="hidden" name="SinglePaneC" id="SinglePaneC-hidden">
                    </div>
                    </div>
                    <div class="col-md-6">
                    <div class="position-relative form-group">
                        <label for="SinglePaneD">Single Pane D</label>
                        <select name="SinglePaneD_disabled" id="SinglePaneD" class="form-control">
                            <option value="">Select Single Pane D</option>
                        </select>
                        <input type="hidden" name="SinglePaneD" id="SinglePaneD-hidden">
                    </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="IGUInnerPane">IGU Inner Pane NFR</label>
                            <select name="IGUInnerPane" id="IGUInnerPane" class="form-control " >
                                <option value="">Select IGU Inner Pane NFR</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="IGUOuterPane">IGU Outer Pane Fire</label>
                            <input type="text" name="IGUOuterPane" id="IGUOuterPane" readony placeholder="Enter IGU Outer Pane"
                                value="@if(isset($Item['IGUOuterPane'])){{$Item['IGUOuterPane']}}@else{{ '' }}@endif"
                                class="form-control" readonly>
                            {{--  <select name="IGUOuterPane" id="IGUOuterPane" class="form-control " >
                                <option value="">Select IGU Outer Pane Fire</option>
                            </select>  --}}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="CAVITY">Cavity</label>
                            <input type="number" name="CAVITY" id="CAVITY" placeholder="Enter CAVITY"
                                value="@if(isset($Item['CAVITY'])){{$Item['CAVITY']}}@else{{ '' }}@endif"
                                class="form-control"  max="36" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Acoustic Value">Acoustic Value</label>
                            <input type="text" name="Acoustic" id="Acoustic" readonly placeholder="Enter Acoustic Value"
                            value="@if (isset($Item['Acoustic'])){{$Item['Acoustic']}}@else{{ '' }} @endif" class="form-control" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Special Featuers">Special Feature</label>
                            <input type="text" name="SpecialFeatuers" id="SpecialFeatuers" placeholder="Enter Special Featuers" value="@if (isset($Item['SpecialFeatuers'])){{$Item['SpecialFeatuers']}}@else{{ '' }} @endif" class="form-control" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="Finish">Finish</label>
                            @php
                                $options = ['Prime', 'Paint RAL 7016', 'Lacquer', 'Stain & Lacquer'];
                            @endphp
                            <select name="Finish" id="Finish" class="form-control " >
                                <option value="">Select Finish</option>
                                @foreach ($options as $option)
                                    <option value="{{ $option }}" @if (isset($Item['Finish']) && $Item['Finish'] == $option) selected @endif>
                                       {{$option }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
