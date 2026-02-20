@extends("layouts.Master")

@section("main_section")
<div class="app-main__outer">

    <div class="app-main__inner">
        <h4 class="mb-3">Add Screen Glass Type</h4>

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


        <form action="{{ route('Screen-Glazing-Type.store') }}" method="POST"  enctype="multipart/form-data">
            @csrf

            @include('SelectedOptions.screen_glazing_type._form')

            <button class="btn btn-success">Save</button>
            <a href="{{ route('Screen-Glazing-Type.index') }}" class="btn btn-secondary">
                Cancel
            </a>
        </form>

    </div>
</div>
@endsection
@section('js')
<script>

    function SinglePane(){
        var FireRating = $("#ScreenFireRating").val();
        if(FireRating == ''){
            swal('Warning','Somethings went wrong!');
            return false;
        }
        $.ajax({
            url: "{{ route('items/get-glass-options') }}",
            method: "POST",
            dataType: "json",
            data: { FireRating: FireRating, _token: '{{ csrf_token() }}' },
            success: function (result) {
                function buildOptions(data, valueElementId, defaultOptionText) {
                    let optionsHtml = `<option value="">${defaultOptionText}</option>`;
                    let selectedValue = $(valueElementId).val() || null;
                    data.forEach(item => {
                        let selected = selectedValue == item.id ? "selected" : "";
                        optionsHtml += `<option value="${item.id}" ${selected}>${item.GlassType}</option>`;
                    });

                    return optionsHtml;
                }

                if (result.status === "ok") {
                    let data = result.dataSelected;
                    $("#GlassTypeSideScreen").empty().append(buildOptions(data, "#glass_ids", "Select Glass Type"));

                } else {
                    $("#GlassTypeSideScreen").empty().append('<option value="">No Glass Type Found</option>');
                }

                // let elements = $(this);
                // render(elements);
            },
            error: function (err) {
                console.error("AJAX Error:", err);
            }
        });
    }

    $(document).on('change','#ScreenFireRating', function () {
        SinglePane();
    });

</script>


@endsection
