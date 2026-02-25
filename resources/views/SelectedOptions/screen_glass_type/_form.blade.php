<div class="card mb-3">
    <div class="card-body">

       <div class="row">

            {{-- Fire Rating --}}
            <div class="col-md-6 mb-3">
                <label>Fire Rating <span class="text-danger">*</span></label>
                <select name="FireRating"
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

            {{-- DF Rating --}}
            <div class="col-md-6 mb-3">
                <label>DF Rating <span class="text-danger">*</span></label>
                <input type="text"
                    name="DFRating"
                    class="form-control @error('DFRating') is-invalid @enderror"
                    value="{{ old('DFRating', $item->DFRating ?? '') }}"
                    required>
                @error('DFRating')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Glass Type --}}
            <div class="col-md-12 mb-3">
                <label>Glass Type <span class="text-danger">*</span></label>
                <input type="text"
                    name="GlassType"
                    class="form-control @error('GlassType') is-invalid @enderror"
                    value="{{ old('GlassType', $item->GlassType ?? '') }}"
                    required>

                <input type="hidden" name="id" value="{{ old('id', $item->id ?? '') }}">
                <input type="hidden" name="selectId" value="{{ old('selectId', $item->selectId ?? '') }}">

                @error('GlassType')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Width Point 1 --}}
            <div class="col-md-3 mb-3">
                <label>Width Point 1 (far right) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0"
                    name="WidthPoint1"
                    class="form-control @error('WidthPoint1') is-invalid @enderror"
                    value="{{ old('WidthPoint1', $item->WidthPoint1 ?? '') }}"
                    required>
                @error('WidthPoint1')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Height Point 1 --}}
            <div class="col-md-3 mb-3">
                <label>Height Point 1 (Lowest) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0"
                    name="HeightPoint1"
                    class="form-control @error('HeightPoint1') is-invalid @enderror"
                    value="{{ old('HeightPoint1', $item->HeightPoint1 ?? '') }}"
                    required>
                @error('HeightPoint1')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Width Point 2 --}}
            <div class="col-md-3 mb-3">
                <label>Width Point 2 (Closest) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0"
                    name="WidthPoint2"
                    class="form-control @error('WidthPoint2') is-invalid @enderror"
                    value="{{ old('WidthPoint2', $item->WidthPoint2 ?? '') }}"
                    required>
                @error('WidthPoint2')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Height Point 2 --}}
            <div class="col-md-3 mb-3">
                <label>Height Point 2 (Highest) <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0"
                    name="HeightPoint2"
                    class="form-control @error('HeightPoint2') is-invalid @enderror"
                    value="{{ old('HeightPoint2', $item->HeightPoint2 ?? '') }}"
                    required>
                @error('HeightPoint2')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Transom Thickness --}}
            <div class="col-md-6 mb-3">
                <label>MIN Transom Thickness <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0"
                    name="TransomThickness"
                    class="form-control @error('TransomThickness') is-invalid @enderror"
                    value="{{ old('TransomThickness', $item->TransomThickness ?? '') }}"
                    required>
                @error('TransomThickness')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Transom Depth --}}
            <div class="col-md-6 mb-3">
                <label>MIN Transom Depth <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0"
                    name="TransomDepth"
                    class="form-control @error('TransomDepth') is-invalid @enderror"
                    value="{{ old('TransomDepth', $item->TransomDepth ?? '') }}"
                    required>
                @error('TransomDepth')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Area Size --}}
            <div class="col-md-6 mb-3">
                <label>MAX Area m² <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0"
                    name="AreaSize"
                    class="form-control @error('AreaSize') is-invalid @enderror"
                    value="{{ old('AreaSize', $item->AreaSize ?? '') }}"
                    required>
                @error('AreaSize')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Frame Density --}}
            <div class="col-md-6 mb-3">
                <label>Frame Density <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0"
                    name="FrameDensity"
                    class="form-control @error('FrameDensity') is-invalid @enderror"
                    value="{{ old('FrameDensity', $item->FrameDensity ?? '') }}"
                    required>
                @error('FrameDensity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Glass Price (Only Non Admin) --}}
            @if(auth()->user()->UserType != 1)
            <div class="col-md-4 mb-3">
                <label>Glass Price <span class="text-danger">*</span></label>
                <input type="number" step="0.01" min="0"
                    name="price"
                    id="glassPrice"
                    class="form-control @error('price') is-invalid @enderror"
                    value="{{ old('price', $item->selectedPrice->glassSelectedPrice ?? '') }}"
                    required>
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

            </div>
            @endif

        </div>


    </div>
</div>
