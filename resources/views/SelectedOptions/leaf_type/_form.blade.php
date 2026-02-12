<div class="card mb-3">
    <div class="card-body">
        <div class="row">

            {{-- Leaf Type --}}
            <div class="col-md-12 mb-3">
                <label>Leaf Type</label>
                <input type="text"
                    name="LeafType"
                    class="form-control @error('LeafType') is-invalid @enderror"
                    value="{{ old('LeafType', $item->LeafType ?? '') }}"
                    required>
                @error('LeafType')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

        </div>

        <hr>

        <div class="position-relative form-group options">
            <label class="d-block">
                Configuration <span class="text-danger">*</span>
            </label>

            @php
                $cores = [
                    ['name' => 'VicaimaDoorCore',  'value' => 4, 'label' => 'Vicaima Door Core'],
                    ['name' => 'Seadec',   'value' => 5, 'label' => 'Seadec'],
                    ['name' => 'Deanta',   'value' => 6, 'label' => 'Deanta'],
                    ['name' => 'MMM',      'value' => 9, 'label' => 'MMM'],
                ];
            @endphp

            @foreach ($cores as $core)
                <input
                    type="checkbox"
                    name="{{ $core['name'] }}"
                    value="{{ $core['value'] }}"
                    class="form-group ml-3 option-style checkboxDoorType"
                    {{ !empty($item->{$core['name']}) ? 'checked' : '' }}
                >
                {{ $core['label'] }}
            @endforeach
        </div>
        <hr>

        {{-- Price (Only for Non Admin) --}}
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
