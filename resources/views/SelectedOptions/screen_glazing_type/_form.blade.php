<div class="card mb-3">
    <div class="card-body">

        <div class="row">

            {{-- Fire Rating --}}
            <div class="col-md-6 mb-3">
                <label>Fire Rating <span class="text-danger">*</span></label>
                <select name="FireRating"
                    id="ScreenFireRating"
                    class="form-control @error('FireRating') is-invalid @enderror"
                    required>

                    <option value="">Select fire rating</option>

                    @foreach(['0-0','30-0','30-30','60-0','60-60','IGU 0-0','IGU 30-0','IGU 30-30'] as $rating)
                        <option value="{{ $rating }}"
                            {{ old('FireRating', $item->FireRating ?? '') == $rating ? 'selected' : '' }}>
                            {{ $rating }}
                        </option>
                    @endforeach

                </select>

                @error('FireRating')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            {{-- Glass Type --}}
            <div class="col-md-6 mb-3">
                <label>Glass Type <span class="text-danger">*</span></label>

                <select name="ScreenGlassId"
                    id="GlassTypeSideScreen"
                    class="form-control @error('ScreenGlassId') is-invalid @enderror"
                    required>

                    <option value="">Select Glass Type</option>

                    @if(isset($screenGlassType))
                        @foreach($screenGlassType as $val)
                            <option value="{{ $val->id }}" {{ old('ScreenGlassId', $item->ScreenGlassId ?? '') == $val->id ? 'selected' : '' }}>{{ $val->GlassType }}</option>
                        @endforeach
                    @endif

                </select>

                @error('ScreenGlassId')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            {{-- Glazing System --}}
            <div class="col-md-6 mb-3">
                <label>Glazing System <span class="text-danger">*</span></label>

                <input type="text"
                    name="GlazingSystem"
                    class="form-control @error('GlazingSystem') is-invalid @enderror"
                    value="{{ old('GlazingSystem', $item->GlazingSystem ?? '') }}"
                    placeholder="Enter Glazing System Name"
                    required>

                <input type="hidden"
                    name="id"
                    value="{{ old('id', $item->id ?? '') }}">

                <input type="hidden"
                    name="selectId"
                    value="{{ old('selectId', $item->selected->id ?? '') }}">

                <input type="hidden"
                    name="glass_ids"
                    id="glass_ids"
                    value="{{ old('glass_ids', $item->ScreenGlassId ?? '') }}">

                @error('GlazingSystem')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            {{-- Glazing Thickness --}}
            <div class="col-md-6 mb-3">
                <label>Glazing Thickness <span class="text-danger">*</span></label>

                <input type="number"
                    step="0.01"
                    min="0"
                    name="GlazingThickness"
                    class="form-control @error('GlazingThickness') is-invalid @enderror"
                    value="{{ old('GlazingThickness', $item->GlazingThickness ?? '') }}"
                    placeholder="Enter Glazing Thickness"
                    required>

                @error('GlazingThickness')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            {{-- Beading --}}
            <div class="col-md-4 mb-3">
                <label>Beading <span class="text-danger">*</span></label>

                <input type="number"
                    step="0.01"
                    min="0"
                    name="Beading"
                    class="form-control @error('Beading') is-invalid @enderror"
                    value="{{ old('Beading', $item->Beading ?? '') }}"
                    placeholder="Enter Beading"
                    required>

                @error('Beading')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            {{-- Beading Height --}}
            <div class="col-md-4 mb-3">
                <label>Beading Height <span class="text-danger">*</span></label>

                <input type="number"
                    step="0.01"
                    min="0"
                    name="BeadingHeight"
                    class="form-control @error('BeadingHeight') is-invalid @enderror"
                    value="{{ old('BeadingHeight', $item->BeadingHeight ?? '') }}"
                    placeholder="Enter Beading Height"
                    required>

                @error('BeadingHeight')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            {{-- Beading Width --}}
            <div class="col-md-4 mb-3">
                <label>Beading Width <span class="text-danger">*</span></label>

                <input type="number"
                    step="0.01"
                    min="0"
                    name="BeadingWidth"
                    class="form-control @error('BeadingWidth') is-invalid @enderror"
                    value="{{ old('BeadingWidth', $item->BeadingWidth ?? '') }}"
                    placeholder="Enter Beading Width"
                    required>

                @error('BeadingWidth')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            {{-- Fixing Details --}}
            <div class="col-md-12 mb-3">
                <label>Fixing Details <span class="text-danger">*</span></label>

                <input type="text"
                    name="FixingDetails"
                    class="form-control @error('FixingDetails') is-invalid @enderror"
                    value="{{ old('FixingDetails', $item->FixingDetails ?? '') }}"
                    placeholder="Enter Fixing Details"
                    required>

                @error('FixingDetails')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>


            {{-- Price (Non Admin Only) --}}
            @if(auth()->user()->UserType != 1)
            <div class="col-md-4 mb-3">

                <label>Glazing Price <span class="text-danger">*</span></label>

                <input type="number"
                    step="0.01"
                    min="0"
                    name="price"
                    class="form-control @error('price') is-invalid @enderror"
                    value="{{ old('price', $item->selectedPrice->glazingSelectedPrice ?? '') }}"
                    required>

                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>
            @endif


        </div>

    </div>
</div>
