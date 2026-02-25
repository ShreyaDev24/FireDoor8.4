@extends("layouts.Master")

@section("main_section")
<div class="app-main__outer">

    <div class="app-main__inner">

        <h4 class="mb-3">Edit Glass Glazing System</h4>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif


        <form action="{{ route('Glass-Glazing-System.update',$item->id) }}" method="POST"  enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('SelectedOptions.glass_glazing_system._form', ['item' => $item])

            <button class="btn btn-success">Update</button>
            <a href="{{ route('Glass-Glazing-System.index') }}" class="btn btn-secondary">
                Cancel
            </a>
        </form>

    </div>
</div>
@endsection
@section('js')
<script>
    function loadGlassOptions() {

        let confi = $('input[name="core"]:checked').val();

        let firerating = $('input[name="firerating[]"]:checked')
            .map(function () {
                return $(this).val();
            }).get();

        if (!confi || firerating.length === 0) {
            return;
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url: "{{ url('options/glassconfigvalue') }}",
            method: "POST",
            dataType: "json",
            data: {
                confi: confi,
                firerating: firerating
            },
            success: function (result) {

                let glassTypeSelect = $('select[name="GlassType"]');
                glassTypeSelect.empty().append('<option value="">Select Glass Type</option>');

                if (result.GlassType?.length) {
                    result.GlassType.forEach(item => {
                        glassTypeSelect.append(
                            `<option value="${item.id}">${item.GlassType}</option>`
                        );
                    });
                }

                let glazingSystemSelect = $('select[name="glazingSystem"]');
                glazingSystemSelect.empty().append('<option value="">Select Glazing System</option>');

                if (result.GlazingSystem?.length) {
                    result.GlazingSystem.forEach(item => {
                        glazingSystemSelect.append(
                            `<option value="${item.id}">${item.GlazingSystem}</option>`
                        );
                    });
                }

                // ✅ Restore selected values on Edit
                let savedGlassType = $('#savedGlassType').val();
                let savedGlazingSystem = $('#savedGlazingSystem').val();

                if (savedGlassType) {
                    glassTypeSelect.val(savedGlassType);
                }

                if (savedGlazingSystem) {
                    glazingSystemSelect.val(savedGlazingSystem);
                }
            },
            error: function (xhr) {
                console.error('AJAX error:', xhr.responseText);
            }
        });
    }

    // Trigger on change
    $(document).on('change', 'input[name="core"], input[name="firerating[]"]', function () {
        loadGlassOptions();
    });

    // Trigger on page load (Edit form)
    $(document).ready(function () {
        loadGlassOptions();
    });
</script>
@endsection
