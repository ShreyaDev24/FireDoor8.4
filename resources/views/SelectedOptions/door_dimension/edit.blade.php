@extends("layouts.Master")

@section("main_section")
<div class="app-main__outer">

    <div class="app-main__inner">

        <h4 class="mb-3">Edit Door Dimension</h4>

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


        <form action="{{ route('Door-Dimension.update',$item->id) }}" method="POST"  enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('SelectedOptions.door_dimension._form', ['item' => $item])

            <button class="btn btn-success">Update</button>
            <a href="{{ route('Door-Dimension.index') }}" class="btn btn-secondary">
                Cancel
            </a>
        </form>

    </div>
</div>
@endsection
@section('js')
<script>
$(document).ready(function () {

    function loadLeafTypes(config_type, selectedLeafType = null) {
        var data = $("#leaf_typedata").val();

        if (data !== '') {
            data = JSON.parse(data);

            let innerHtml = '<option value="">Select Leaf Type</option>';

            for (let index = 0; index < data.length; index++) {
                if (
                    config_type == data[index].NormaDoorCore ||
                    config_type == data[index].VicaimaDoorCore ||
                    config_type == data[index].Seadec ||
                    config_type == data[index].Deanta ||
                    config_type == data[index].MMM
                ) {
                    let selected = (selectedLeafType && selectedLeafType == data[index].LeafType) ? 'selected' : '';
                    innerHtml += `<option value="${data[index].LeafType}" ${selected}>${data[index].LeafType}</option>`;
                }
            }

            $('#leaf_type').html(innerHtml);
            $('.leaf_type_door').show();   // show fields
        }
    }

    // On click (create + edit)
    $(".configurableitemsdoordimension").on("click", function () {
        let config_type = $(this).val();
        loadLeafTypes(config_type);
    });

    // On page load (EDIT mode auto fill)
    let checkedConfig = $(".configurableitemsdoordimension:checked").val();
    let savedLeafType = $("#leaf_type").data('selected'); // we'll set this in blade

    if (checkedConfig) {
        loadLeafTypes(checkedConfig, savedLeafType);
    }
});

function loadDoorLeafFacing(leaftypeValue, selectedFacing = null) {

    // Clear old options
    $('#door_leaf_facing')
        .find('.leafFacingOptionBefore')
        .remove();

    if (!leaftypeValue) return;

    $.ajax({
        type: "POST",
        dataType: "json",
        url: "{{ route('quotation/getDoorFacing') }}",
        data: {
            leaftypeValue: leaftypeValue,
            _token: '{{ csrf_token() }}'
        },
        success: function (response) {

            if (response.status === 'ok' && Array.isArray(response.leafTypeFacing)) {

                let html = '<option value="" class="leafFacingOptionBefore">Select Door leaf facing</option>';

                response.leafTypeFacing.forEach(function (item) {
                    html += `<option value="${item.doorLeafFacingValue}" class="leafFacingOptionBefore">
                                ${item.doorLeafFacingValue}
                             </option>`;
                });

                $('#door_leaf_facing').append(html);

                // Auto-select in edit mode
                if (selectedFacing) {
                    $('#door_leaf_facing').val(selectedFacing);
                }

            } else {
                console.error('Invalid response format', response);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("Ajax request failed:", textStatus, errorThrown);
        }
    });
}


// When user changes leaf type (create + edit)
$(document).on('change', '#leaf_type', function () {
    let leaftypeValue = $(this).val();
    let selectedFacing = $('#door_leaf_facing').data('selected') || null;

    loadDoorLeafFacing(leaftypeValue, selectedFacing);
});


// Auto-load for edit form
$(document).ready(function () {

    let editLeafType = $('#leaf_type').data('selected');
    let editFacing = $('#door_leaf_facing').data('selected');

    if (editLeafType) {
        $('#leaf_type').val(editLeafType);
        loadDoorLeafFacing(editLeafType, editFacing);
    }
});
</script>
@endsection
