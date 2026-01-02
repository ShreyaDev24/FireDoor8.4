@extends('layouts.Master')

@section('main_section')
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h4>Add Glass Certificate</h4>

                <form action="{{ route('glass-certificates.store') }}"
                    method="POST"
                    enctype="multipart/form-data">

                    @csrf
                    @include('glass_certificates.form')
                    <button class="btn btn-success">Save</button>
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

        if (
            brandId &&
            fireRating &&
            certMap[brandId] &&
            certMap[brandId][fireRating]
        ) {
            document.getElementById('certificate_reference').value =
                certMap[brandId][fireRating];
        } else {
            document.getElementById('certificate_reference').value = '';
        }
    }


    $(document).ready(function () {

        function loadGlassTypes() {
            let brandId    = $('#brand_of_core').val();
            let fireRating = $('#fire_rating').val();

            if (brandId && fireRating) {
                $.ajax({
                    url: "{{ route('glass-certificates.get-glass-types') }}",
                    type: "GET",
                    data: {
                        brand_id: brandId,
                        fire_rating: fireRating
                    },
                    success: function (data) {
                        let options = '<option value="">Select</option>';

                        $.each(data, function (key, value) {
                            options += `<option value="${value.id}">
                                ${value.GlassType} (${value.GlassThickness}mm)
                            </option>`;
                        });

                        $('#glass_type').html(options);
                    }
                });
            } else {
                $('#glass_type').html('<option value="">Select</option>');
            }
        }

        $('#brand_of_core, #fire_rating').on('change', loadGlassTypes);

    });
</script>

@endsection
