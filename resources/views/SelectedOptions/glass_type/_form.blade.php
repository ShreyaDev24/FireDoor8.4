<div class="card mb-3">
    <div class="card-body">

        {{-- GlassType Name --}}
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="d-block">
                    Glass Integrity <span class="text-danger">*</span>
                </label>

                <div class="form-check form-check-inline">
                    <input
                        type="radio"
                        name="GlassIntegrity"
                        id="Integrity_And_Insulation"
                        value="Integrity_And_Insulation"
                        class="form-check-input option-style"
                        required
                        {{ old('GlassIntegrity', $item->GlassIntegrity ?? '') == 'Integrity_And_Insulation' ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="Integrity_And_Insulation">
                        Integrity And Insulation
                    </label>
                </div>

                <div class="form-check form-check-inline">
                    <input
                        type="radio"
                        name="GlassIntegrity"
                        id="Integrity_only"
                        value="Integrity_only"
                        class="form-check-input option-style"
                        required
                        {{ old('GlassIntegrity', $item->GlassIntegrity ?? '') == 'Integrity_only' ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="Integrity_only">
                        Integrity Only
                    </label>
                </div>

                <div class="form-check form-check-inline">
                    <input
                        type="radio"
                        name="GlassIntegrity"
                        id="All_Fire_Rated_Glass"
                        value="All_Fire_Rated_Glass"
                        class="form-check-input option-style"
                        required
                        {{ old('GlassIntegrity', $item->GlassIntegrity ?? '') == 'All_Fire_Rated_Glass' ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="Integrity_only">
                        Non Fire rated Glass
                    </label>
                </div>

            </div>


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

            <div class="col-md-6 mb-3">
                <label>Glass Thickness <span class="text-danger">*</span></label>
                <input type="text"
                    name="GlassThickness"
                    class="form-control @error('GlassThickness') is-invalid @enderror"
                    value="{{ old('GlassThickness', $item->GlassThickness ?? '') }}"
                    required>

                @error('GlassThickness')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label>Glazing Beads <span class="text-danger">*</span></label>
                <div id="glazingBeadsContainer" class="mt-2"></div>

                @php
                    $savedGlazingBeads = [];

                    if (isset($item) && !empty($item->GlazingBeads)) {
                        $savedGlazingBeads = json_decode($item->GlazingBeads, true) ?? [];
                        $savedGlazingBeads = array_map('trim', $savedGlazingBeads);
                        $savedGlazingBeads = array_map('strtolower', $savedGlazingBeads);
                    }
                @endphp


                <script>
                    window.savedGlazingBeads = @json($savedGlazingBeads);
                </script>
            </div>
        </div>



        <hr>

        {{-- Configuration --}}
        <div class="position-relative form-group options">
            <label class="d-block">
                Configuration <span class="text-danger">*</span>
            </label>

            @php
                $cores = [
                    ['name' => 'Streboard',       'value' => 1, 'label' => 'Streboard'],
                    ['name' => 'Halspan',         'value' => 2, 'label' => 'Halspan'],
                    ['name' => 'Flamebreak',      'value' => 7, 'label' => 'Flamebreak'],
                    ['name' => 'Stredor',         'value' => 8, 'label' => 'Stredor'],
                ];
            @endphp

            @foreach ($cores as $core)
                <div class="form-check form-check-inline ml-2">
                    <input type="checkbox"
                    name="core[]"
                    value="{{ $core['value'] }}"
                    data-core="{{ $core['name'] }}"
                    class="form-check-input core-checkbox"
                    {{ old($core['name'], $item->{$core['name']} ?? false) ? 'checked' : '' }}>

                    <label class="form-check-label">
                        {{ $core['label'] }}
                    </label>
                </div>
            @endforeach
        </div>

        <hr>

        <div class="col-md-12">
            <div class="position-relative form-group firerating-options">

                <label class="d-block">
                    Fire Rating <span class="text-danger">*</span>
                </label>

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



                <div class="form-check form-check-inline">
                    <input
                        type="checkbox"
                        name="firerating[]"
                        id="firerating_nfr"
                        value="NFR"
                        class="form-check-input option-style"
                        {{ in_array('NFR', $selectedFireRatings) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="firerating_nfr">
                        NFR
                    </label>
                </div>

                <div class="form-check form-check-inline">
                    <input
                        type="checkbox"
                        name="firerating[]"
                        id="firerating_fd30"
                        value="FD30"
                        class="form-check-input option-style"
                        {{ in_array('FD30', $selectedFireRatings) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="firerating_fd30">
                        FD30
                    </label>
                </div>

                <div class="form-check form-check-inline">
                    <input
                        type="checkbox"
                        name="firerating[]"
                        id="firerating_fd60"
                        value="FD60"
                        class="form-check-input option-style"
                        {{ in_array('FD60', $selectedFireRatings) ? 'checked' : '' }}
                    >
                    <label class="form-check-label" for="firerating_fd60">
                        FD60
                    </label>
                </div>

            </div>
        </div>

        <hr>

        {{-- Selected Price (Only Non Admin) --}}
        @if(auth()->id() != 1)
        <div class="row">
            <div class="col-md-4 mb-3">
                <label>Selected Price</label>
                <input type="number"
                    step="0.01"
                    name="price"
                    class="form-control"
                    value="{{ old('price', $item->selectedPrice->selectedPrice ?? '') }}">
            </div>
        </div>
        @endif

    </div>
</div>
