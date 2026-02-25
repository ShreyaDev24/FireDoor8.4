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

            <div class="col-md-6 mb-3">
                <label for="glasstype">VP Area Size<span class="text-danger">*</span></label>
                <input type="number" min="0" name="vpareasize" step="0.1" placeholder="Enter VPAreaSize"
                     class="form-control @error('VPAreaSize') is-invalid @enderror"
                    value="{{ old('VPAreaSize', $item->VPAreaSize ?? '') }}" required>
            </div>
            @if(isset($item))
                <input type="hidden" id="savedGlassType" value="{{ $item->glass_id }}">
                <input type="hidden" id="savedGlazingSystem" value="{{ $item->glazing_system }}">
            @endif
        </div>
    </div>
</div>
