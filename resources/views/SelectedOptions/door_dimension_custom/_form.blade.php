<div class="card mb-3">
    <div class="card-body">

        <div class="row">

            {{-- Configuration --}}
            <div class="col-md-12 mb-3">
                <label class="d-block">Configuration <span class="text-danger">*</span></label>

                @php
                    $cores = [
                        ['name' => 'Streboard', 'value' => 1],
                        ['name' => 'Halspan', 'value' => 2],
                        ['name' => 'Flamebreak', 'value' => 7],
                        ['name' => 'Stredoor', 'value' => 8],
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

            @if(Auth::user()->UserType != 1)

           <div class="col-md-12 leaf-price-section mb-3" style="display:none;">
                <div class="position-relative form-group">
                    <label class="d-block">
                        Leaf Type Price <span class="text-danger">*</span>
                    </label>

                    <div class="leaf-price-wrapper">

                        @php
                            $prices = $item->selectedPrice->custome_door_selected_cost ?? [];
                        @endphp
                        @foreach($intumenseLeafType as $type)

                            @php
                                $configMap = [
                                    1 => 'sterboard-fields',
                                    2 => 'halspan-fields',
                                    7 => 'flamebreak-fields',
                                    8 => 'stredor-fields',
                                ];
                            @endphp

                            <div class="leaf-price-item {{ $configMap[$type->configurableitems] ?? '' }}"
                                style="display:none;">

                                <div class="form-group">
                                    <label>{{ $type->leaf_type_key }}:</label>

                                    <input type="number"
                                        name="prices[{{ $type->id }}]"
                                        class="form-control"
                                        step="0.01"
                                        placeholder="Enter price for {{ $type->leaf_type_key }}"
                                        value="{{ old('prices.'.$type->id, $prices[$type->id] ?? '') }}">
                                </div>

                            </div>

                        @endforeach

                    </div>
                </div>
            </div>

            @endif

        </div>
    </div>
</div>
