<div class="mb-2">
    <label>Brand of Core</label>
    <select id="brand_of_core" name="brand_of_core" class="form-control" required>
        <option value="">-- Select Brand --</option>
        @foreach($brands as $brand)
            <option value="{{ $brand->id }}">{{ $brand->name }}</option>
        @endforeach
    </select>
</div>
<div class="mb-2">
    <label>Fire Rating</label>
    <select id="fire_rating" name="fire_rating" class="form-control" required>
        <option value="">-- Select Fire Rating --</option>
        <option value="NFR">NFR</option>
        <option value="FD30">FD30 / FD30s</option>
        <option value="FD60">FD60 / FD60s</option>
    </select>
</div>
<div class="mb-3">
    <label>Glass Type</label>
    <select name="glass_type_id" id="glass_type" class="form-control" required>
        <option value="">Select</option>
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
