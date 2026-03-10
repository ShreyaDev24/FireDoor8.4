<div class="card mb-3">
    <div class="card-body">

        <div class="row">

            {{-- Configuration --}}
            <div class="col-md-12 mb-3">
                <label class="d-block">Configuration <span class="text-danger">*</span></label>

                @php
                    $cores = [
                        ['name' => 'VicaimaDoorCore', 'value' => 4],
                        ['name' => 'Seadec', 'value' => 5],
                        ['name' => 'Deanta', 'value' => 6],
                        ['name' => 'MMM', 'value' => 9],
                    ];
                @endphp

                @foreach ($cores as $core)
                    <div class="form-check form-check-inline ml-2">
                        <input type="radio"
                            name="configurableitems"
                            value="{{ $core['value'] }}"
                            class="form-check-input configurableitemsdoordimension"
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

                @foreach (['NFR', 'FD30', 'FD60'] as $rate)
                    <div class="form-check form-check-inline ml-2">
                        <input type="radio"
                            name="fire_rating"
                            value="{{ $rate }}"
                            class="form-check-input"
                            {{ old('fire_rating', $item->fire_rating ?? '') == $rate ? 'checked' : '' }}
                            required>
                        <label class="form-check-label">{{ $rate }}</label>
                    </div>
                @endforeach
            </div>

            <hr>

            {{-- Leaf Type --}}
            <div class="col-md-6 mb-3">
                <label>Leaf Type <span class="text-danger">*</span></label>
                <input type="hidden" id="leaf_typedata" value="{{ isset($leaftype)?$leaftype:'' }}">
                <select name="leaf_type" id="leaf_type" class="form-control" data-selected="{{ old('leaf_type', $item->leaf_type ?? '') }}" required>
                    <option value="">Select Leaf Type</option>
                </select>
            </div>

            {{-- Code --}}
            <div class="col-md-6 mb-3">
                <label>Code <span class="text-danger">*</span></label>
                <input name="code" type="text" class="form-control"
                    value="{{ old('code', $item->code ?? '') }}" required>
            </div>

            {{-- Inch Height --}}
            <div class="col-md-3 mb-3">
                <label>Inch Height</label>
                <input name="inch_height" type="number" min="1" class="form-control"
                    value="{{ old('inch_height', $item->inch_height ?? '') }}">
            </div>

            {{-- Inch Width --}}
            <div class="col-md-3 mb-3">
                <label>Inch Width</label>
                <input name="inch_width" type="number" min="1" class="form-control"
                    value="{{ old('inch_width', $item->inch_width ?? '') }}">
            </div>

            {{-- MM Height --}}
            <div class="col-md-3 mb-3">
                <label>MM Height <span class="text-danger">*</span></label>
                <input name="mm_height" type="number" min="1" class="form-control"
                    value="{{ old('mm_height', $item->mm_height ?? '') }}" required>
            </div>

            {{-- MM Width --}}
            <div class="col-md-3 mb-3">
                <label>MM Width <span class="text-danger">*</span></label>
                <input name="mm_width" type="number" min="1" class="form-control"
                    value="{{ old('mm_width', $item->mm_width ?? '') }}" required>
            </div>

            {{-- Door Leaf Facing --}}
            <div class="col-md-8 mb-3">
                <label>Door Leaf Facing</label>
                <select name="door_leaf_facing" id="door_leaf_facing" data-selected="{{ old('door_leaf_facing', $item->door_leaf_facing ?? '') }}" class="form-control">
                </select>
            </div>

            {{-- Cost Price --}}
            <div class="col-md-4 mb-3">
                <label>Cost Price</label>
                <input type="number" step="0.01" name="cost_price" class="form-control"
                    value="{{ old('cost_price', $item->cost_price ?? '') }}">
            </div>

            {{-- Image --}}
            {{--  <div class="col-md-8 mb-3">
                <label>Image</label>
                <input name="image" type="file" class="form-control">
            </div>  --}}

            {{-- User Price --}}
            @if(Auth::user()->UserType != 1)
                <div class="col-md-8 mb-3">
                    <label>Door Dimension Price</label>
                    <input type="number" step="0.01" name="DoorDimensionPrice" class="form-control"
                        value="{{ old('DoorDimensionPrice', $item->selectedPrice->selected_cost ?? '') }}">
                </div>
            @endif

        </div>
    </div>
</div>
