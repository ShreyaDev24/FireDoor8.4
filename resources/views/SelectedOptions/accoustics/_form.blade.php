<div class="card mb-3">
    <div class="card-body">

        {{-- Accoustics Name --}}
        <div class="row">
            <div class="col-md-12 mb-3">
                <label>Accoustics <span class="text-danger">*</span></label>
                <input type="text"
                    name="Accoustics"
                    class="form-control @error('Accoustics') is-invalid @enderror"
                    value="{{ old('Accoustics', $item->Accoustics ?? '') }}"
                    required>

                @error('Accoustics')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
        <hr>

        {{-- Acoustics Option --}}
        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="d-block">
                    Acoustics Option <span class="text-danger">*</span>
                </label>

                <div class="form-check form-check-inline">
                    <input class="form-check-input"
                        type="radio"
                        name="AccousticsOption"
                        value="Perimeter_Seal_1"
                        {{ old('AccousticsOption', $item->UnderAttribute ?? '') == 'Perimeter_Seal_1' ? 'checked' : '' }}
                        required>
                    <label class="form-check-label">Perimeter Seal 1</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input"
                        type="radio"
                        name="AccousticsOption"
                        value="Perimeter_Seal_2"
                        {{ old('AccousticsOption', $item->UnderAttribute ?? '') == 'Perimeter_Seal_2' ? 'checked' : '' }}>
                    <label class="form-check-label">Perimeter Seal 2</label>
                </div>

                <div class="form-check form-check-inline">
                    <input class="form-check-input"
                        type="radio"
                        name="AccousticsOption"
                        value="Meeting_Stiles"
                        {{ old('AccousticsOption', $item->UnderAttribute ?? '') == 'Meeting_Stiles' ? 'checked' : '' }}>
                    <label class="form-check-label">Meeting Stiles</label>
                </div>

                @error('AccousticsOption')
                    <div class="text-danger mt-1">{{ $message }}</div>
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
                    ['name' => 'VicaimaDoorCore', 'value' => 4, 'label' => 'VicaimaDoorCore'],
                    ['name' => 'Seadec',          'value' => 5, 'label' => 'Seadec'],
                    ['name' => 'Deanta',          'value' => 6, 'label' => 'Deanta'],
                    ['name' => 'MMM',             'value' => 9, 'label' => 'MMM'],
                ];
            @endphp

            @foreach ($cores as $core)
                <div class="form-check form-check-inline ml-2">
                    <input type="checkbox"
                        name="{{ $core['name'] }}"
                        value="{{ $core['value'] }}"
                        class="form-check-input"
                        {{ old($core['name'], $item->{$core['name']} ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label">
                        {{ $core['label'] }}
                    </label>
                </div>
            @endforeach
        </div>

        <hr>

        {{-- Accoustics Image --}}
        <div class="row">
            <div class="col-md-6 mb-3">
                <label>Accoustics Image</label>
                <input type="file"
                    name="file"
                    class="form-control @error('file') is-invalid @enderror">

                @error('file')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                {{-- Show Old Image In Edit --}}
                @if(!empty($item->file))
                    <div class="mt-2">
                        <img src="{{ asset('uploads/Options/' . $item->file) }}"
                             style="height:60px; object-fit:contain;">
                    </div>
                @endif
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
