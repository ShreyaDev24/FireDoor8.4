@extends("layouts.Master")

@section("main_section")
@if(session()->has('error'))
<style type="text/css">
#useremail {
    border-color: red
}
</style>
@endif
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="card">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card-header"><h5 class="card-title">Ironmongery</h5></div>
                </div>                
            </div>
            <div id="accordion">                
                <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
                {!! $list !!}
            </div>
        </div>

    </div>
</div>

@endsection


@section('js')

<script>
// JQUERY
$(document).ready(function() {    
    $('#accordion header').click(function() {
        $(this).next()
            .slideToggle(200)
            .closest('.question')
            .toggleClass('active')
            .siblings()
            .removeClass('active')
            .find('main')
            .slideUp(200);
    })
});

function selectMe(iron_id) {
    $.ajax({
        url: "{{url('ironmongery-info/select')}}",
        method: "POST",
        dataType: "Json",
        data: {
            iron_id: iron_id,
            _token: $("#_token").val()
        },
        success: function(result) {
            if (result.status == "ok") {

                $('.select_class_' + iron_id).addClass('border-success');

            } else if (result.status == "deleted") {
                $('.select_class_' + iron_id).removeClass('border-success');
            }

        }
    });
}
</script>
@endsection