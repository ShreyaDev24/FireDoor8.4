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


        <form action="{{ route('Door-Dimension.store') }}" method="POST"  enctype="multipart/form-data">
            @csrf

            @include('SelectedOptions.door_dimension._form')

            <button class="btn btn-success">Save</button>
            <a href="{{ route('Door-Dimension.index') }}" class="btn btn-secondary">
                Cancel
            </a>
        </form>

    </div>
</div>
@endsection
@section('js')
<script>
$(".configurableitemsdoordimension").on("click",function(){

    var data = $("#leaf_typedata").val();
    if(data!=''){
        data =  JSON.parse(data);
        config_type = $(this).val();
        var lenght = data.length;
        innerHtml = '';
        innerHtml += '<option value="">Select Leaf Type</option>';
        for(var index = 0; index<lenght;index++){
            if(config_type == data[index].NormaDoorCore || config_type == data[index].VicaimaDoorCore || config_type == data[index].Seadec || config_type == data[index].Deanta || config_type == data[index].MMM ){
                innerHtml +=  '<option value="'+ data[index].LeafType +'" class="">'+ data[index].LeafType +'</option>';
            }
        }
        $('#leaf_type').empty().html(innerHtml);
    }

});

$(document).on('change', '#leaf_type', function () {

    let leaftypeValue = $(this).val();

    // Reset dropdown properly
    $('#door_leaf_facing')
        .find('.leafFacingOptionBefore')
        .remove();

    if (!leaftypeValue) {
        return; // nothing selected
    }

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

            } else {
                console.error('Invalid response format', response);
            }
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.error("Ajax request failed:", textStatus, errorThrown);
        }
    });
});
</script>


@endsection
