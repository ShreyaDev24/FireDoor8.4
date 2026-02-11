<div class="card mb-3">
    <div class="card-body">

        <div class="row">

            <div class="col-md-4 mb-3">
                <label>Door Leaf Facing</label>

                <select name="DoorLeafOption" required id="DoorLeafOption" class="form-control">
                    <option value="">Select Door Leaf Option</option>

                    {{-- First 3 static options --}}
                    @php
                        $selectedDoorLeaf = old('DoorLeafOption', $item->doorLeafFacing ?? '');
                    @endphp

                    <option value="Veneer" {{ $selectedDoorLeaf == 'Veneer' ? 'selected' : '' }}>
                        Veneer
                    </option>
                    <option value="Laminate" {{ $selectedDoorLeaf == 'Laminate' ? 'selected' : '' }}>
                        Laminate
                    </option>
                    <option value="PVC" {{ $selectedDoorLeaf == 'PVC' ? 'selected' : '' }}>
                        PVC
                    </option>

                    {{-- Vicaima --}}
                    @if(!empty($leaftypevicima))
                        @foreach($leaftypevicima as $val)
                            <option
                                value="{{ $val->LeafType }}"
                                data-core="vicaima"
                                class="leaf-option"
                                {{ $selectedDoorLeaf == $val->LeafType ? 'selected' : '' }}
                            >
                                {{ $val->LeafType }}
                            </option>
                        @endforeach
                    @endif

                    {{-- Sedac --}}
                    @if(!empty($leaftype2))
                        @foreach($leaftype2 as $val)
                            <option
                                value="{{ $val->LeafType }}"
                                data-core="sedac"
                                class="leaf-option"
                                {{ $selectedDoorLeaf == $val->LeafType ? 'selected' : '' }}
                            >
                                {{ $val->LeafType }}
                            </option>
                        @endforeach
                    @endif

                    {{-- MMM --}}
                    @if(!empty($leaftypemmm))
                        @foreach($leaftypemmm as $val)
                            <option
                                value="{{ $val->LeafType }}"
                                data-core="mmm"
                                class="leaf-option"
                                {{ $selectedDoorLeaf == $val->LeafType ? 'selected' : '' }}
                            >
                                {{ $val->LeafType }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>


            <div class="col-md-4 mb-3">
                <label>Facing Value</label>
                <input type="text" name="doorLeafFacingValue" class="form-control"
                    value="{{ old('doorLeafFacingValue', $item->doorLeafFacingValue ?? '') }}" required>
            </div>

            @if(auth()->id() != 1)
            <div class="col-md-4 mb-3">
                <label>Price</label>
                <input type="number" step="0.01" name="price" class="form-control" value="{{ old('price', $item->selectedPrice->selectedPrice ?? '') }}">
            </div>
            @endif

        </div>

        <hr>

        <h6>Applicable Door Cores</h6>

        <div class="position-relative form-group options">
            <label class="d-block">
                Configuration <span class="text-danger">*</span>
            </label>

            @php
                $cores = [
                    ['name' => 'Streboard',        'value' => 1, 'label' => 'Streboard'],
                    ['name' => 'Halspan',          'value' => 2, 'label' => 'Halspan'],
                    ['name' => 'Flamebreak',       'value' => 7, 'label' => 'Flamebreak'],
                    ['name' => 'Stredor',          'value' => 8, 'label' => 'Stredor'],
                    ['name' => 'VicaimaDoorCore',  'value' => 4, 'label' => 'Vicaima Door Core'],
                    ['name' => 'SeadecDoorCore',   'value' => 5, 'label' => 'Seadec'],
                    ['name' => 'deantaDoorCore',   'value' => 6, 'label' => 'Deanta'],
                    ['name' => 'MMM',              'value' => 9, 'label' => 'MMM'],
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


    </div>
</div>
