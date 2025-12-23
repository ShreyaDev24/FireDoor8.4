<div class="mb-3">
    <label>Glass Type</label>
    <select name="glass_type_id" class="form-control" required>
        <option value="">Select</option>
        @foreach($glassTypes as $type)
            <option value="{{ $type->id }}"
                @selected(old('glass_type_id', $glassCertificate->glass_type_id ?? '') == $type->id)>
                {{ $type->GlassType }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Certificate Reference</label>
    <input type="text" name="certificate_reference"
           class="form-control"
           value="{{ old('certificate_reference', $glassCertificate->certificate_reference ?? '') }}">
</div>

<div class="mb-3">
    <label>Fire Rating</label>
    <input type="text" name="fire_rating"
           class="form-control"
           value="{{ old('fire_rating', $glassCertificate->fire_rating ?? '') }}">
</div>

<div class="mb-3">
    <label>Expiry Date</label>
    <input type="date" name="expiry_date"
           class="form-control"
           value="{{ old('expiry_date', $glassCertificate->expiry_date ?? '') }}">
</div>
