<div class="card mb-3">
    <div class="card-body">

        {{-- GlassType Name --}}
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

            <div class="col-md-6 mb-3">
                <label for="glasstype">VP Area Size<span class="text-danger">*</span></label>
                <input type="number" min="0" name="VPAreaSize" placeholder="Enter VPAreaSize"
                    class="form-control" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" value="{{ old('VPAreaSize', $item->VPAreaSize ?? '') }}" required >
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
                    ['name' => 'VicaimaDoorCore', 'value' => 4, 'label' => 'VicaimaDoorCore'],
                    ['name' => 'Seadec',          'value' => 5, 'label' => 'Seadec'],
                    ['name' => 'Deanta',          'value' => 6, 'label' => 'Deanta'],
                    ['name' => 'MMM',             'value' => 9, 'label' => 'MMM'],
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
