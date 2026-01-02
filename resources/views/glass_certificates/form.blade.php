<div class="mb-2">
    <label>Brand of Core</label>
    <select id="brand_of_core" name="brand_of_core" class="form-control" required>
        <option value="">-- Select Brand --</option>
        @foreach($brands as $brand)
            <option value="{{ $brand->id }}"
                {{ old('brand_of_core', $glassCertificate->brand_of_core ?? '') == $brand->id ? 'selected' : '' }}>
                {{ $brand->name }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-2">
    <label>Fire Rating</label>
    <select id="fire_rating" name="fire_rating" class="form-control" required>
        <option value="">-- Select Fire Rating --</option>
        <option value="NFR"
            {{ old('fire_rating', $glassCertificate->fire_rating ?? '') == 'NFR' ? 'selected' : '' }}>
            NFR
        </option>
        <option value="FD30"
            {{ old('fire_rating', $glassCertificate->fire_rating ?? '') == 'FD30' ? 'selected' : '' }}>
            FD30 / FD30s
        </option>
        <option value="FD60"
            {{ old('fire_rating', $glassCertificate->fire_rating ?? '') == 'FD60' ? 'selected' : '' }}>
            FD60 / FD60s
        </option>
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
    <input type="text" name="certificate_reference" id="certificate_reference"
           class="form-control"
           value="{{ old('certificate_reference', $glassCertificate->certificate_reference ?? '') }}">
</div>

<div class="mb-3">
    <label>Expiry Date</label>
    <input type="date" name="expiry_date"
           class="form-control"
           value="{{ old('expiry_date', $glassCertificate->expiry_date ?? '') }}">
</div>

<div class="mb-3">
    <label class="form-label">Document</label>

    {{-- Existing document --}}
    @if(!empty($glassCertificate->document_path))
        <div class="d-flex align-items-center mb-2 p-2 border rounded bg-light">
            <i class="fa fa-file-pdf-o text-danger fs-4 me-2"></i>

            <div class="flex-grow-1">
                <strong>Current PDF uploaded</strong>
            </div>

            <a href="{{ asset($glassCertificate->document_path) }}"
               target="_blank"
               class="btn btn-sm btn-outline-primary">
                View
            </a>
        </div>
    @endif

    {{-- Upload new --}}
    <input type="file"
           name="document"
           class="form-control"
           accept="application/pdf">

    <small class="text-muted">
        Upload a new PDF to replace the existing document
    </small>
</div>


