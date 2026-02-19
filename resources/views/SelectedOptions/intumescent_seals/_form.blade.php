<div class="card mb-3">
    <div class="card-body">

        <div class="row">

            {{-- Configuration --}}
            <div class="col-md-12 mb-3">
                <label class="d-block">Configurable Item <span class="text-danger">*</span></label>

                @php
                    $cores = [
                        ['name' => 'Streboard', 'value' => 1],
                        ['name' => 'Halspan', 'value' => 2],
                        ['name' => 'Flamebreak', 'value' => 7],
                        ['name' => 'Stredor', 'value' => 8],
                    ];
                @endphp

                @foreach ($cores as $core)
                    <div class="form-check form-check-inline ml-2">
                        <input type="radio"
                            name="configurableitems"
                            value="{{ $core['value'] }}"
                            class="form-check-input"
                            {{ old('configurableitems', $item->configurableitems ?? '') == $core['value'] ? 'checked' : '' }}
                            required>
                        <label class="form-check-label">{{ $core['name'] }}</label>
                    </div>
                @endforeach
            </div>


            <hr>

            {{-- Fire Rating --}}
            <div class="col-md-12 mb-3">
                <label class="d-block">Fire Rating <span class="text-danger">*</span></label>

                @php
                    $selectedFireRating = old('firerating', $item->firerating ?? '');
                @endphp

                @foreach (['NFR', 'FD30', 'FD60'] as $rate)
                    <div class="form-check form-check-inline ml-2">
                        <input type="radio"
                            name="firerating"
                            value="{{ $rate }}"
                            class="form-check-input"
                            {{ $selectedFireRating == $rate ? 'checked' : '' }}
                            required>
                        <label class="form-check-label">{{ $rate }}</label>
                    </div>
                @endforeach
            </div>


            <hr>

            {{-- Configuration Dropdown --}}
            <div class="col-md-6 mb-3">
                <label>Configuration <span class="text-danger">*</span></label>
                <select name="configuration" class="form-control" required>
                    <option value="">Select Configuration</option>
                    @foreach ($IntumescentSealsConfiguration as $Configuration)
                        <option value="{{ $Configuration->configuration }}"
                            {{ old('configuration', $item->configuration ?? '') == $Configuration->configuration ? 'selected' : '' }}>
                            {{ $Configuration->configuration }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Fire Tested --}}
            <div class="col-md-6 mb-3">
                <label>Fire Tested <span class="text-danger">*</span></label>
                <input type="text" name="firetested" class="form-control"
                    value="{{ old('firetested', $item->firetested ?? '') }}" required>
            </div>

            {{-- Intumescent Seals --}}
            <div class="col-md-6 mb-3">
                <label>Intumescent Seals <span class="text-danger">*</span></label>
                <input type="text" name="intumescentSeals" class="form-control"
                    value="{{ old('intumescentSeals', $item->intumescentSeals ?? '') }}" required>
            </div>

            {{-- Brand --}}
            <div class="col-md-6 mb-3">
                <label>Brand <span class="text-danger">*</span></label>
                <input type="text" name="brand" class="form-control"
                    value="{{ old('brand', $item->brand ?? '') }}" required>
            </div>

            {{-- Height / Width --}}
            <div class="col-md-3 mb-3">
                <label>Height Point1</label>
                <input type="number" min="0" name="Point1height" class="form-control"
                    value="{{ old('Point1height', $item->Point1height ?? '') }}">
            </div>

            <div class="col-md-3 mb-3">
                <label>Height Point2</label>
                <input type="number" min="0" name="Point2height" class="form-control"
                    value="{{ old('Point2height', $item->Point2height ?? '') }}">
            </div>

            <div class="col-md-3 mb-3">
                <label>Width Point1</label>
                <input type="number" min="0" name="Point1width" class="form-control"
                    value="{{ old('Point1width', $item->Point1width ?? '') }}">
            </div>

            <div class="col-md-3 mb-3">
                <label>Width Point2</label>
                <input type="number" min="0" name="Point2width" class="form-control"
                    value="{{ old('Point2width', $item->Point2width ?? '') }}">
            </div>

            {{-- Fire Only Type --}}
            @if (isset($item->FireOnly))
            <div class="col-md-6 mb-3">
                <label>Fire Only Type</label>
                <input type="FireOnly" name="FireOnly" class="form-control"
                    value="{{ old('FireOnly', $item->FireOnly ?? '') }}" readonly>
            </div>
            @else
            <div class="col-md-6 mb-3">
                <label>Fire Only Type</label>
                <select name="FireOnly[]" class="form-control" multiple>

                    <option value="Fire_only" >Fire only</option>
                    <option value="Fire_and_Smoke">Fire and Smoke</option>
                    <option value="Fire_Smoke_and_Acoustic">Fire Smoke and Acoustic</option>
                </select>
            </div>
            @endif

            <div class="col-md-6 mb-3">
                <label>Meeting Edges</label>
                <input type="MeetingEdges" name="MeetingEdges" class="form-control"
                    value="{{ old('MeetingEdges', $item->MeetingEdges ?? '') }}">
            </div>

            <hr>

            <div class="col-md-12 mb-3" id="leafTypeContainer">
                <label>Leaf Type</label>
                <div class="leaf-type-options"></div>

                <script>
                    window.savedcustomeleafTypes = "{{ $item->customeleafTypes ?? '' }}";
                </script>

            </div>



            {{-- Price --}}
            @if(Auth::user()->UserType != 1)
            <div class="col-md-4 mb-3">
                <label>Intumescent Seal Price</label>
                <input type="number" step="0.01" name="IntumescentSealPrice" class="form-control"
                    value="{{ old('selected_cost', $item->selected_cost ?? '') }}">
            </div>
            @endif

        </div>

    </div>
</div>
