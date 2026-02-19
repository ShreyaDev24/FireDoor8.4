<div class="card mb-3">
    <div class="card-body">

        {{-- ArchitraveType Name --}}
        <div class="row">
            <div class="col-md-12 mb-3">
                <label>ArchitraveType <span class="text-danger">*</span></label>
                <input type="text"
                    name="ArchitraveType"
                    class="form-control @error('ArchitraveType') is-invalid @enderror"
                    value="{{ old('ArchitraveType', $item->ArchitraveType ?? '') }}"
                    required>

                @error('ArchitraveType')
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
