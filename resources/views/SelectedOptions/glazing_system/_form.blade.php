<div class="card mb-3">
    <div class="card-body">

        {{-- GlazingSystem Name --}}
        <div class="row">
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

            <div class="col-md-6 mb-3">
                <label>Glazing Thickness <span class="text-danger">*</span></label>
                <input type="number" min="0"
                    name="GlazingThickness"
                    class="form-control @error('GlazingThickness') is-invalid @enderror"
                    value="{{ old('GlazingThickness', $item->GlazingThickness ?? '') }}"
                    required>

                @error('GlazingThickness')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="col-md-6 mb-3">
                <label>Glazing Bead Fixing Detail <span class="text-danger">*</span></label>
                <input type="text"
                    name="GlazingBeadFixingDetail"
                    class="form-control @error('GlazingBeadFixingDetail') is-invalid @enderror"
                    value="{{ old('GlazingBeadFixingDetail', $item->GlazingBeadFixingDetail ?? '') }}"
                    required>

                @error('GlazingBeadFixingDetail')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
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

        {{-- Selected Price (Only Non Admin) + Test Ref --}}
        <div class="row">
            @if(auth()->id() != 1)
            <div class="col-md-4 mb-3">
                <label>Selected Price</label>
                <input type="number"
                    step="0.01"
                    name="price"
                    class="form-control"
                    value="{{ old('price', $item->selectedPrice->selectedPrice ?? '') }}">
            </div>
            @endif

            <div class="col-md-6 mb-3">
                <label>Test Ref</label>
                <input type="text"
                    name="test_ref"
                    class="form-control"
                    value="{{ old('test_ref', $item->test_ref ?? '') }}">
            </div>
        </div>

    </div>
</div>
