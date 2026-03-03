<div class="card mb-3">
    <div class="card-body">
        {{-- Configuration --}}
        <label class="d-block">Configuration <span class="text-danger">*</span></label>

        @php
            $cores = [
                ['name' => 'Streboard',  'value' => 1, 'label' => 'Streboard'],
                ['name' => 'Halspan',    'value' => 2, 'label' => 'Halspan'],
                ['name' => 'Flamebreak', 'value' => 7, 'label' => 'Flamebreak'],
                ['name' => 'Stredor',    'value' => 8, 'label' => 'Stredor'],
            ];
        @endphp

        @foreach ($cores as $core)
            <div class="form-check form-check-inline ml-2">
                <input type="checkbox"
                    name="core[]"
                    value="{{ $core['value'] }}"
                    data-core="{{ $core['name'] }}"
                    class="form-check-input"
                    {{ in_array($core['value'], old('core', collect([
                        $item->Streboard,
                        $item->Halspan,
                        $item->Flamebreak,
                        $item->Stredor
                    ])->filter()->toArray())) ? 'checked' : '' }}>
                <label class="form-check-label">{{ $core['label'] }}</label>
            </div>
        @endforeach

        <hr>

        {{-- Fire Rating --}}
        <label class="d-block">Fire Rating <span class="text-danger">*</span></label>

        @php
            $selectedFireRatings = old('firerating', []);

            if(isset($item)){
                $selectedFireRatings = old('firerating', collect([
                    $item->NFR,
                    $item->FD30,
                    $item->FD60
                ])->filter()->toArray());
            }
        @endphp

        @foreach (['NFR','FD30','FD60'] as $rating)
            <div class="form-check form-check-inline">
                <input type="checkbox"
                    name="firerating[]"
                    value="{{ $rating }}"
                    class="form-check-input"
                    {{ in_array($rating, $selectedFireRatings) ? 'checked' : '' }}>
                <label class="form-check-label">{{ $rating }}</label>
            </div>
        @endforeach

        <hr>

        <div class="row">
            {{-- Glass Integrity --}}
            <div class="col-md-12 mb-3">
                <label>Glass Integrity <span class="text-danger">*</span></label>

                @php
                    $selectedIntegrity = old('GlassIntegrity', $item->GlassIntegrity ?? '');
                @endphp

                <div class="form-check">
                    <input type="radio" name="GlassIntegrity" value="Integrity_And_Insulation"
                        class="form-check-input"
                        {{ $selectedIntegrity == 'Integrity_And_Insulation' ? 'checked' : '' }}>
                    <label class="form-check-label">Integrity And Insulation</label>
                </div>

                <div class="form-check">
                    <input type="radio" name="GlassIntegrity" value="Integrity_only"
                        class="form-check-input"
                        {{ $selectedIntegrity == 'Integrity_only' ? 'checked' : '' }}>
                    <label class="form-check-label">Integrity Only</label>
                </div>
            </div>

            {{-- Glass Type --}}
            <div class="col-md-6 mb-3">
                <label>Glass Type <span class="text-danger">*</span></label>
                <input type="text"
                    name="GlassType"
                    class="form-control @error('GlassType') is-invalid @enderror"
                    value="{{ old('GlassType', $item->GlassType ?? '') }}"
                    required>

                @error('GlassType')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>



            {{-- Glass Thickness --}}
            <div class="col-md-6 mb-3">
                <label>Glass Thickness <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0"
                    name="GlassThickness"
                    class="form-control @error('GlassThickness') is-invalid @enderror"
                    value="{{ old('GlassThickness', $item->GlassThickness ?? '') }}"
                    required>
            </div>

            {{-- Fanlight Width --}}
            <div class="col-md-6 mb-3">
                <label>Max Width of Fanlight <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0"
                    name="FanLightWidth"
                    class="form-control"
                    value="{{ old('FanLightWidth', $item->FanLightWidth ?? '') }}"
                    required>
            </div>

            {{-- Fanlight Height --}}
            <div class="col-md-6 mb-3">
                <label>Max Height of Fanlight <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0"
                    name="FanLightHeight"
                    class="form-control"
                    value="{{ old('FanLightHeight', $item->FanLightHeight ?? '') }}"
                    required>
            </div>

            {{-- Side Screen Width --}}
            <div class="col-md-6 mb-3">
                <label>Side Screen Width <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0"
                    name="SideScreenWidth"
                    class="form-control"
                    value="{{ old('SideScreenWidth', $item->SideScreenWidth ?? '') }}"
                    required>
            </div>

            {{-- Side Screen Height --}}
            <div class="col-md-6 mb-3">
                <label>Side Screen Height <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0"
                    name="SideScreenHeight"
                    class="form-control"
                    value="{{ old('SideScreenHeight', $item->SideScreenHeight ?? '') }}"
                    required>
            </div>

            {{-- Transom Thickness --}}
            <div class="col-md-6 mb-3">
                <label>MIN Frame Thickness <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0"
                    name="TransomThickness"
                    class="form-control"
                    value="{{ old('TransomThickness', $item->TransomThickness ?? '') }}"
                    required>
            </div>

            {{-- Transom Depth --}}
            <div class="col-md-6 mb-3">
                <label>MIN Frame Depth <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0"
                    name="TransomDepth"
                    class="form-control"
                    value="{{ old('TransomDepth', $item->TransomDepth ?? '') }}"
                    required>
            </div>

        </div>

        <hr>

        <div class="row">

            {{-- Glazing System --}}
            <div class="col-md-6 mb-3">
                <label>Glazing System <span class="text-danger">*</span></label>
                <input type="text"
                    name="GlazingSystem"
                    class="form-control @error('GlazingSystem') is-invalid @enderror"
                    value="{{ old('GlazingSystem', $item->GlazingSystem ?? '') }}"
                    required>

                @error('GlazingSystem')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Glazing Thickness --}}
            <div class="col-md-6 mb-3">
                <label>Glazing Thickness <span class="text-danger">*</span></label>
                <input type="number" min="0" step="0.01"
                    name="GlazingThickness"
                    class="form-control @error('GlazingThickness') is-invalid @enderror"
                    value="{{ old('GlazingThickness', $item->GlazingThickness ?? '') }}"
                    required>

                @error('GlazingThickness')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Min Beading --}}
            <div class="col-md-4 mb-3">
                <label>Min Beading <span class="text-danger">*</span></label>
                <input type="number" min="0" step="0.01"
                    name="Beading"
                    class="form-control @error('Beading') is-invalid @enderror"
                    value="{{ old('Beading', $item->Beading ?? '') }}"
                    required>

                @error('Beading')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Min Beading Height --}}
            <div class="col-md-4 mb-3">
                <label>Min Beading Height <span class="text-danger">*</span></label>
                <input type="number" min="0" step="0.01"
                    name="BeadingHeight"
                    class="form-control @error('BeadingHeight') is-invalid @enderror"
                    value="{{ old('BeadingHeight', $item->BeadingHeight ?? '') }}"
                    required>

                @error('BeadingHeight')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Min Beading Width --}}
            <div class="col-md-4 mb-3">
                <label>Min Beading Width <span class="text-danger">*</span></label>
                <input type="number" min="0" step="0.01"
                    name="BeadingWidth"
                    class="form-control @error('BeadingWidth') is-invalid @enderror"
                    value="{{ old('BeadingWidth', $item->BeadingWidth ?? '') }}"
                    required>

                @error('BeadingWidth')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Glazing Bead Fixing Detail --}}
            <div class="col-md-12 mb-3">
                <label>Glazing Bead Fixing Detail <span class="text-danger">*</span></label>
                <input type="text"
                    name="FixingDetails"
                    class="form-control @error('FixingDetails') is-invalid @enderror"
                    value="{{ old('FixingDetails', $item->FixingDetails ?? '') }}"
                    required>

                @error('FixingDetails')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>

        <hr>

        {{-- Selected Price --}}
        @if(auth()->id() != 1)
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Glass Price</label>
                <input type="number" step="0.01" min="0"
                    name="glass_price"
                    class="form-control"
                    value="{{ old('glass_price', $item->selectedPrice->glassSelectedPrice ?? '') }}">
            </div>

            <div class="col-md-4 mb-3">
                <label>Glazing Price</label>
                <input type="number" step="0.01" min="0"
                    name="glazing_price"
                    class="form-control"
                    value="{{ old('glazing_price', $item->selectedPrice->glazingSelectedPrice ?? '') }}">
            </div>
        </div>
        @endif

    </div>
</div>
