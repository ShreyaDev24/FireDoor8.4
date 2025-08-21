@extends('layouts.Master')

@section('main_section')
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h2>Edit Certificate</h2>
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <form action="{{ route('core_certificates.update', $coreCertificate) }}" method="POST"
                    enctype="multipart/form-data">
                    @csrf @method('PUT')
                    <div class="mb-2">
                        <label>Brand of Core</label>
                        <select name="brand_of_core" id="brand_of_core" class="form-control" required>
                            <option value="">-- Select Brand --</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ $coreCertificate->brand_of_core == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Fire Rating</label>
                        <select name="fire_rating" id="fire_rating" class="form-control" required>
                            <option value="">-- Select Fire Rating --</option>
                            <option value="FD30" {{ $coreCertificate->fire_rating == 'FD30' ? 'selected' : '' }}>
                                NFR / FD30 / FD30s
                            </option>
                            <option value="FD60" {{ $coreCertificate->fire_rating == 'FD60' ? 'selected' : '' }}>
                                FD60 / FD60s
                            </option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label>Test Certificate Reference</label>
                        <input type="text" name="test_certificate_reference" id="test_certificate_reference"
                            value="{{ $coreCertificate->test_certificate_reference }}" class="form-control" required>
                    </div>
                    <div class="mb-2">
                        <label>Date of Expiry</label>
                        <input type="date" name="expiry_date" value="{{ $coreCertificate->expiry_date }}"
                            class="form-control">
                    </div>
                    <div class="mb-2">
                        <label>Upload Document</label>
                        <input type="file" name="document" class="form-control">
                        @if($coreCertificate->document_path)
                        <p>Current: <a href="{{ asset('/' . $coreCertificate->document_path) }}"
                                target="_blank">View</a></p>
                        @endif
                    </div>
                    <button type="submit" class="btn btn-success">Update</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    // JS object from PHP
    let certMap = @json($certMap);

    document.getElementById('brand_of_core').addEventListener('change', fillCertRef);
    document.getElementById('fire_rating').addEventListener('change', fillCertRef);

    function fillCertRef() {
        let brandId = document.getElementById('brand_of_core').value;
        let fireRating = document.getElementById('fire_rating').value;

        if (brandId && fireRating && certMap[brandId] && certMap[brandId][fireRating]) {
            document.getElementById('test_certificate_reference').value = certMap[brandId][fireRating];
        } else {
            document.getElementById('test_certificate_reference').value = '';
        }
    }
</script>
@endsection
