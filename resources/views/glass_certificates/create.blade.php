@extends('layouts.Master')

@section('main_section')
<div class="container">
    <h4>Add Glass Certificate</h4>

    <form method="POST" action="{{ route('glass-certificates.store') }}">
        @csrf
        @include('glass_certificates.form')
        <button class="btn btn-success">Save</button>
    </form>
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
