@extends("layouts.Master")

@section("main_section")
<div class="app-main__outer">

    <div class="app-main__inner">
        <h4 class="mb-3">Add Colour List</h4>

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


        <form action="{{ route('Colour-List.store') }}" method="POST"  enctype="multipart/form-data">
            @csrf

            @include('SelectedOptions.color._form')

            <button class="btn btn-success">Save</button>
            <a href="{{ route('Colour-List.index') }}" class="btn btn-secondary">
                Cancel
            </a>
        </form>

    </div>
</div>
@endsection
@section('js')
<script>
    $(document).ready(function(){

        let selectedFacing = $('select[name="DoorLeafFacing"]').val();

        DoorLeafFacingChange(selectedFacing);

    });

    function DoorLeafFacingChange(value,DoorLeafFacingValue = '') {
        let UnderAttribute = value;

        if(UnderAttribute == 'Laminate'){
            $('#doorLeafFacingValueDiv').show();
            $('#DoorLeafFacingval').attr('required', true);

            $.ajax({
                type: 'post',
                url: "{{route('ColorDoorLeafFacing')}}",
                data: {'UnderAttribute':UnderAttribute,'_token': '{{ csrf_token() }}','DoorLeafFacingValue':DoorLeafFacingValue},
                success: function(result) {
                    $("#DoorLeafFacingval").attr({"required":true});
                    $("#DoorLeafFacingval").empty().append(result);
                },
                error: function(data) {
                    $(".page-loader-action").fadeOut();
                    swal("Oops!!", "Something went wrong. Please try again.", "error");
                }
            });
        } else {
            $('#doorLeafFacingValueDiv').hide();
            $('#DoorLeafFacingval').removeAttr('required');
            $('#DoorLeafFacingval').val('');
        }
    }

    function hexToRGB(hex) {

        hex = hex.replace('#', '');

        if (hex.length === 3) {
            hex = hex.split('').map(function (h) {
                return h + h;
            }).join('');
        }

        let bigint = parseInt(hex, 16);

        let r = (bigint >> 16) & 255;
        let g = (bigint >> 8) & 255;
        let b = bigint & 255;

        return r + "," + g + "," + b;
    }

    function colorChange(selector, hexType = false) {

        var colorVal = $(selector).val();

        if (!colorVal) {
            $('#colorfill').css("background-color", "#fff");
            return;
        }

        if (hexType && !/^#([0-9A-F]{3}){1,2}$/i.test(colorVal)) {
            return;
        }

        var rgb = hexToRGB(colorVal);

        $('#rgb_value').val(rgb);

        $(selector).css("background-color", colorVal); // better than fixed id
    }
</script>
@endsection
