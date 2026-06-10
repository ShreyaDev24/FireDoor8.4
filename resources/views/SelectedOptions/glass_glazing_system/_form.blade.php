<div class="card mb-3">
    <div class="card-body">

        {{-- GlazingSystem Name --}}
        <div class="row">
            {{-- Configuration --}}
            <div class="position-relative form-group options">
                <label class="d-block">
                    Configuration <span class="text-danger">*</span>
                </label>

                @php
                    $cores = [
                        ['name' => 'Streboard',  'value' => 1, 'label' => 'Streboard'],
                        ['name' => 'Halspan',    'value' => 2, 'label' => 'Halspan'],
                        ['name' => 'Flamebreak', 'value' => 7, 'label' => 'Flamebreak'],
                        ['name' => 'Stredor',    'value' => 8, 'label' => 'Stredor'],
                        ['name' => 'Vicaima',    'value' => 4, 'label' => 'Vicaima'],
                        ['name' => 'Seadec',    'value' => 5, 'label' => 'Seadec'],
                        ['name' => 'Deanta',    'value' => 6, 'label' => 'Deanta'],
                        ['name' => 'MMM',    'value' => 9, 'label' => 'MMM'],
                    ];

                    $selectedCore = old('core', $item->Configurableitems ?? null);
                @endphp

                @foreach ($cores as $core)
                    <div class="form-check form-check-inline ml-2">
                        <input type="radio"
                            name="core"
                            id="core_{{ $core['value'] }}"
                            value="{{ $core['value'] }}"
                            class="form-check-input glassconfigvalue"
                            {{ $selectedCore == $core['value'] ? 'checked' : '' }}>
                        <label class="form-check-label" for="core_{{ $core['value'] }}">
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

            <div class="col-md-12 mb-3">
                <label for="glasstype">Glass Type<span class="text-danger">*</span></label>
                <select name="GlassType" id="" required class="form-control ">
                    <option value="">Select Glass Type</option>
                </select>
            </div>

            <div class="col-md-12 mb-3">
                <label for="glasstype">Glazing System<span class="text-danger">*</span></label>
                <select name="glazingSystem" required class="form-control ">
                    <option value="">Select Glazing System</option>
                </select>
                <input type="hidden" name="id" class="form-control">
            </div>

            <div class="col-md-4 mb-3">
                <label for="vpareasize">Max VP Area Size (m²)<span class="text-danger">*</span></label>
                <input type="number" min="0" name="vpareasize" step="0.001" placeholder="e.g. 1.55"
                     class="form-control @error('VPAreaSize') is-invalid @enderror"
                    value="{{ old('VPAreaSize', $item->VPAreaSize ?? '') }}" required>
            </div>

            <div class="col-md-4 mb-3">
                <label for="vpwidth">Max VP Width (mm)<span class="text-danger">*</span></label>
                <input type="number" min="0" name="vpwidth" step="1" placeholder="e.g. 867"
                     class="form-control @error('VPWidth') is-invalid @enderror"
                    value="{{ old('VPWidth', $item->VPWidth ?? '') }}" required>
            </div>

            <div class="col-md-4 mb-3">
                <label for="vpheight">Max VP Height (mm)<span class="text-danger">*</span></label>
                <input type="number" min="0" name="vpheight" step="1" placeholder="e.g. 2275"
                     class="form-control @error('VPHeight') is-invalid @enderror"
                    value="{{ old('VPHeight', $item->VPHeight ?? '') }}" required>
            </div>
            @if(isset($item))
                <input type="hidden" id="savedGlassType" value="{{ $item->glass_id }}">
                <input type="hidden" id="savedGlazingSystem" value="{{ $item->glazing_system }}">
            @endif
        </div>
    </div>
</div>
