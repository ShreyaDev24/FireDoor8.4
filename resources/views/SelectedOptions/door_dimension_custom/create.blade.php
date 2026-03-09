@extends("layouts.Master")

@section("main_section")
<div class="app-main__outer">

    <div class="app-main__inner">
        <h4 class="mb-3">Add Door Dimension</h4>

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


        <form action="{{ route('Door-Dimension-Custom.store') }}" method="POST"  enctype="multipart/form-data">
            @csrf

            @include('SelectedOptions.door_dimension_custom._form')

            <button class="btn btn-success">Save</button>
            <a href="{{ route('Door-Dimension-Custom.index') }}" class="btn btn-secondary">
                Cancel
            </a>
        </form>

    </div>
</div>
@endsection
@section('js')
<script>
function toggleConfigurationFields(configurableitems) {

    // Parent section show karo
    $('.leaf-price-section').show();

    // Sab hide + disable
    $('.leaf-price-item')
        .hide()
        .find('input').prop('disabled', true);

    // Selected wala show + enable
    if (configurableitems == '1') {
        $('.sterboard-fields')
            .show()
            .find('input').prop('disabled', false);
    }
    else if (configurableitems == '2') {
        $('.halspan-fields')
            .show()
            .find('input').prop('disabled', false);
    }
    else if (configurableitems == '7') {
        $('.flamebreak-fields')
            .show()
            .find('input').prop('disabled', false);
    }
    else if (configurableitems == '8') {
        $('.stredor-fields')
            .show()
            .find('input').prop('disabled', false);
    }
}


// 🔹 Change Event (For Add Page)
$(document).on('change', 'input[name="configurableitems"]', function () {
    toggleConfigurationFields($(this).val());
});


// 🔹 Auto Trigger For Edit Page
$(document).ready(function () {

    let selected = $('input[name="configurableitems"]:checked').val();

    if (selected) {
        toggleConfigurationFields(selected);
    } else {
        $('.leaf-price-section').hide();
    }

});
</script>


@endsection
