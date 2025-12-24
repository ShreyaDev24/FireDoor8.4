@extends('layouts.Master')

@section('main_section')
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <form method="POST" action="{{ route('glass-certificates.update', $glassCertificate) }}">
                    @csrf @method('PUT')
                    @include('glass_certificates.form')
                    <button class="btn btn-success">Update</button>
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
