<div class="card mb-3">
    <div class="card-body">

        <div class="row">

            {{-- Colour Name --}}
            <div class="col-md-6 mb-3">
                <label>Colour Name <span class="text-danger">*</span></label>

                <input type="text" name="ColorName" class="form-control @error('ColorName') is-invalid @enderror"
                    value="{{ old('ColorName', $item->ColorName ?? '') }}" required>

                @error('ColorName')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Hex --}}
            <div class="col-md-6 mb-3">
                <label>Hex</label>

                <input type="text" name="Hex" onkeyup="colorChange(this, true)" class="form-control @error('Hex') is-invalid @enderror"
                    value="{{ old('Hex', $item->Hex ?? '') }}" id="colorfill" >

                <input type="hidden" name="RGB" value="{{ old('RGB', $item->RGB ?? '') }}" id="rgb_value">

                @error('Hex')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Door Leaf Facing --}}
            <div class="col-md-6 mb-3">
                <label>Door Leaf Facing <span class="text-danger">*</span></label>

                <select name="DoorLeafFacing"
                    class="form-control @error('DoorLeafFacing') is-invalid @enderror"
                    required
                    onchange="DoorLeafFacingChange(this.value)">

                    <option value="">Select Facing</option>

                    @foreach($facingTypes as $facing)

                    <option value="{{ $facing }}"
                        {{ old('DoorLeafFacing', $item->DoorLeafFacing ?? '') == $facing ? 'selected' : '' }}>

                        {{ $facing }}

                    </option>

                    @endforeach

                </select>

                @error('DoorLeafFacing')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>


            {{-- Door Leaf Facing Value --}}
            <div class="col-md-6 mb-3" id="doorLeafFacingValueDiv" style="display:none;">
                <label>Door Leaf Facing Value</label>

                <input type="hidden" value="{{ old('DoorLeafFacingValue', $item->DoorLeafFacingValue ?? '') }}" id="DoorLeafFacingValuestore">
                <select name="DoorLeafFacingValue"
                    id="DoorLeafFacingval"
                    class="form-control @error('DoorLeafFacingValue') is-invalid @enderror">

                    <option value="">Select Type</option>

                </select>

                @error('DoorLeafFacingValue')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>

        </div>


        <hr>


        {{-- Selected Price (Non Admin Only) --}}
        @if(auth()->id() != 1)

        <div class="row">

            <div class="col-md-4 mb-3">
                <label>Selected Price</label>

                <input type="number" step="0.01" name="price" class="form-control"
                    value="{{ old('price', $item->selectedPrice->SelectedPrice ?? '') }}">

            </div>

        </div>

        @endif


    </div>
</div>
