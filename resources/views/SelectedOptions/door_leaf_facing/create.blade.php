@extends("layouts.Master")

@section("main_section")
<div class="app-main__outer">

    <div class="app-main__inner">
        <h4 class="mb-3">Add Door Leaf Facing</h4>

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


        <form action="{{ route('door-leaf-facing.store') }}" method="POST">
            @csrf

            @include('SelectedOptions.door_leaf_facing._form')

            <button class="btn btn-success">Save</button>
            <a href="{{ route('door-leaf-facing.index') }}" class="btn btn-secondary">
                Cancel
            </a>
        </form>

    </div>
</div>
@endsection
@section('js')
<script>
function doorLeafFacingOption(selectedOption = '') {

    // Core selections
    let cores = {
        mmm: $('input[name="MMM"]').is(':checked'),
        vicaima: $('input[name="VicaimaDoorCore"]').is(':checked'),
        sedac: $('input[name="SeadecDoorCore"]').is(':checked')
    };

    // Hide everything first
    $('#DoorLeafOption option.leaf-option').prop('hidden', true);
    $('.leafFacingfirst3option').prop('hidden', true);

    let anyCoreSelected = cores.mmm || cores.vicaima || cores.sedac;

    // Show core-based options
    if (anyCoreSelected) {
        if (cores.mmm) {
            $('#DoorLeafOption option[data-core="mmm"]').prop('hidden', false);
        }
        if (cores.vicaima) {
            $('#DoorLeafOption option[data-core="vicaima"]').prop('hidden', false);
        }
        if (cores.sedac) {
            $('#DoorLeafOption option[data-core="sedac"]').prop('hidden', false);
        }
    }
    // No core selected → show default options
    else {
        $('.leafFacingfirst3option').prop('hidden', false);
    }

    // ✅ Restore selected value ONLY if option exists & visible
    if (
        selectedOption &&
        $('#DoorLeafOption option[value="' + selectedOption + '"]:not([hidden])').length
    ) {
        $('#DoorLeafOption').val(selectedOption);
    } else {
        $('#DoorLeafOption').val('');
    }
}

$(document).ready(function () {
    let selected = $('#DoorLeafOption').val();
    doorLeafFacingOption(selected);
});

$(document).on('change', '.checkboxDoorType', function () {
    doorLeafFacingOption($('#DoorLeafOption').val());
});
</script>


@endsection
