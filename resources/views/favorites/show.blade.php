@extends('layouts.Master')

@section('main_section')
    <div class="container">
        <h2>Favorite Details</h2>
        <p><strong>Name:</strong> {{ $favorite->name }}</p>
        <p><strong>User:</strong> {{ $favorite->user->UserEmail ?? 'N/A' }}</p>
        <p><strong>Status:</strong> {{ $favorite->status }}</p>
        <br>
        <div
            style="background: #fff3cd; color: #856404; padding: 10px 15px; border-radius: 5px; margin-bottom: 20px; border: 1px solid #ffeeba;">
            <strong>Note:</strong> If you edit a Door or Screen here, it will reflect in its quotation
            where you select the door or screen for favorite. Please make changes accordingly if you want them to apply in
            the quotation.
        </div>
        <hr>
        <div class="table-responsive">
            {!! $html !!}
        </div>
        <br>
        <hr>
        <div class="table-responsive">
            {!! $htmlScreen !!}
        </div>

    </div>
    <input type="hidden" id="favoriteDeleteItem" value="{{ url('/quotation/favoriteDeleteItem') }}" />
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
@endsection
@section('js')
    <script>
        function favoriteDeleteItem(id) {
            $('.loader').empty().css({
                'display': 'block'
            });
            var r = confirm("Are you sure! you wan't to delete it.");
            if (r == true) {
                $.ajax({
                    url: $("#favoriteDeleteItem").val(),
                    method: "POST",
                    data: {
                        _token: $("#_token").val(),
                        id: id
                    },
                    dataType: "Json",
                    success: function(data) {
                        if (data.status == true) {
                            location.reload();
                        } else {
                            swal('error', data.msg, 'error').then(function() {
                                location.reload();
                            });
                        }
                    }
                });
            }
        }
    </script>
@endsection
