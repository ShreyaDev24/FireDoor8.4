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
                    {{--  @php
                        $options = ['7.2mm PYROGUARD EW30', '7.2mm PYROGUARD FD60', '7.2mm PYROGUARD SATIN EW30', '11.4mm PYROGUARD EW60', '15mm PYROGUARD EI30 (INT)', '19mm PYROGUARD EI60 (EXT)', '23mm PYROGUARD EI60 (INT)', '27mm PYROGUARD EI60 (EXT)', 'PYROBELITE 7 - EW30', 'PYROBELITE 7 - EW60', 'PYROBELITE 9EG EW30', 'PYROBELITE 12 - EW60', 'PYROBEL 16 EI60/30', 'PYROBEL 16 (EXT) EI30/EW60', 'PYROBEL 25 - EI60', 'PYROBEL 25 (EXT) EI60', '7MM PYRODUR EW30', '10MM PYRODUR EW60', '11MM PYRODUR EW30 (2B2)', '13MM PYRODUR EW60 1(B)1', '14MM PYROSTOP EI30', '15MM PYROSTOP EI30 (INT)', '18MM PYROSTOP EI30 (EXT)', '23MM PYROSTOP EI60 (INT)', '27MM PYROSTOP EI60 (EXT)', '6MM PYROGUARD FIRESAFE T-E30', '8MM PYROGUARD FIRESAFE T-E30', '10MM PYROGUARD FIRESAFE T-E30', '12MM PYROGUARD FIRESAFE T-E30', '19MM PYROGUARD FIRESAFE T-E30', '6MM PYROGUARD FIRESAFE EW30', '6MM PYROGUARD FIRESAFE EW60', '6.4mm CLEAR LAMINATE', '8.8mm CLEAR LAMINATE', '10.8mm CLEAR LAMINATE', '11.5mm CLEAR LAMINATE', '6.8mm ACOUSTIC LAMINATE', '8.8mm ACOUSTIC LAMINATE', '10.8mm ACOUSTIC LAMINATE', '12.8mm ACOUSTIC LAMINATE', '16.8mm ACOUSTIC LAMINATE', '6.4mm WHITE LAMINATE', '6.8mm STIPPOLYTE LAMINATE', '6mm SILVERED MIRROR (SAFETY BACKED)', '4MM TOUGH CLEAR FLOAT', '6MM TOUGH CLEAR FLOAT', '8MM TOUGH CLEAR FLOAT', '10MM TOUGH CLEAR FLOAT', '12MM TOUGH CLEAR FLOAT', '15MM TOUGH CLEAR FLOAT', '19MM TOUGH CLEAR FLOAT'];
                    @endphp  --}}
                    <div class="col-md-6">
                        <div class="position-relative form-group">
                            <label for="SinglePane">Single Pane</label>
                            <select name="SinglePane" id="SinglePane" class="form-control " required>
                                <option value="">Select Single Pane</option>
                            </select>
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
