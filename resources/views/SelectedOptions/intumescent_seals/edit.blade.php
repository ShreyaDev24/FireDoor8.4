@extends("layouts.Master")

@section("main_section")
<div class="app-main__outer">

    <div class="app-main__inner">

        <h4 class="mb-3">Edit Intumescent Seal Arrangement</h4>

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


        <form action="{{ route('Intumescent-Seal-Arrangement.update',$item->id) }}" method="POST"  enctype="multipart/form-data">
            @csrf
            @method('PUT')

            @include('SelectedOptions.intumescent_seals._form', ['item' => $item])

            <button class="btn btn-success">Update</button>
            <a href="{{ route('Intumescent-Seal-Arrangement.index') }}" class="btn btn-secondary">
                Cancel
            </a>
        </form>

    </div>
</div>
@endsection
@section('js')
<script>
    $(document).on('change', 'input[name="configurableitems"]', function () {
        let configurableItems = $(this).val();
        handleDoorTypeChange(configurableItems, window.savedcustomeleafTypes);
    });

    // 🔥 Auto load on edit page (important fix)
    $(document).ready(function () {
        let configurableItems =
            $('input[name="configurableitems"]:checked').val() ||
            $('select[name="configurableitems"]').val();

        if (configurableItems) {
            handleDoorTypeChange(configurableItems, window.savedcustomeleafTypes);
        }
    });

    function handleDoorTypeChange(configurableItems, customLeafId) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url: "{{ url('options/filter-leaf-type') }}",
            method: "POST",
            dataType: "json",
            data: {
                configurableitems: configurableItems
            },
            success: function (result) {
                let container = $('#leafTypeContainer .leaf-type-options');
                container.empty();

                if (result && result.length > 0) {
                    result.forEach(function (type) {
                        container.append(`
                            <label class="d-block">
                                <input type="checkbox" name="customeleafTypes[]" value="${type.id}">
                                ${type.leaf_type_key}
                            </label>
                        `);
                    });

                    // ✅ Pre-check saved values
                    if (customLeafId) {
                        let selectedLeafTypes = customLeafId.split(',');

                        $('input[name="customeleafTypes[]"]').each(function () {
                            if (selectedLeafTypes.includes($(this).val())) {
                                $(this).prop('checked', true);
                            }
                        });
                    }

                    $('#leafTypeContainer').show();
                } else {
                    $('#leafTypeContainer').hide();
                }
            }
        });
    }
</script>


@endsection
