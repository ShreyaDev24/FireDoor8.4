@extends("layouts.Master")
@section("main_section")
@if(session()->has('error'))
<style type="text/css">
#useremail {
    border-color: red;
}
</style>
@endif

@if ($errors->any())
<div class="alert alert-danger alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <h5><i class="fas fa-skull-crossbones"></i> Alert!</h5>
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>
@endif
<div class="app-main__outer">
  <div class="app-main__inner">
    <div class="card">
            <div class="row">
                <div class="col-sm-6">
                    <div class="card-header"><h5 class="card-title">Color</h5></div>
                </div>                
            </div>             
      <div id="accordion">
        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
        
        <div class="question">
          <header>
            <h3>Kraft Paper Color</h3><i class="fa fa-chevron-down"></i>
          </header>
          <main>
            <ul class="accordian_list">
              {!! $Kraft_Paper !!}              
            </ul>
            @if(Auth::user()->UserType != 1)
            <button class="accordian_update_button" onclick="updateMe('Kraft_Paper')">Update</button>
            @endif
          </main>
        </div>
        <div class="question">
          <header>
            <h3>Laminate Color</h3><i class="fa fa-chevron-down"></i>
          </header>
          <main>
            <ul class="accordian_list">
              {!! $Laminate !!}
            </ul>
            @if(Auth::user()->UserType != 1)
            <button class="accordian_update_button" onclick="updateMe('Laminate')">Update</button>
            @endif
          </main>
        </div>
        <div class="question">
          <header>
            <h3>PVC Color</h3><i class="fa fa-chevron-down"></i>
          </header>
          <main>
            <ul class="accordian_list">
              {!! $PVC !!}
            </ul>
            @if(Auth::user()->UserType != 1)
            <button class="accordian_update_button" onclick="updateMe('PVC')">Update</button>
            @endif
          </main>
        </div>
        <!-- <div class="question">
          <header>
            <h3>Veneer Color</h3><i class="fa fa-chevron-down"></i>
          </header>
          <main>
            <ul class="accordian_list">
              {!! $Veneer !!}
            </ul>
            @if(Auth::user()->UserType != 1)
            <button class="accordian_update_button" onclick="updateMe('Veneer')">Update</button>
            @endif
          </main>
        </div> -->
        
        <!-- <div class="question">
          <header>
            <h3>Others</h3><i class="fa fa-chevron-down"></i>
          </header>
          <main>
            <ul class="accordian_list">
                {!! $other !!}
            </ul>
            @if(Auth::user()->UserType != 1)
            <button class="accordian_update_button" onclick="updateMe('Painted')">Update</button>
            @endif
          </main>
        </div> -->
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


function updateMe(className) {
    var selectedId = [];
    $('.' + className + ':checked').each(function() {
        selectedId.push($(this).val());
    });
    $.ajax({
        method: "POST",
        url: "{{route('update-color')}}",
        dataType: "Json",
        data: {
            selectedId: selectedId,
            className: className,
            _token: $("#_token").val()
        },
        success: function(result) {
            if (result.status = "ok") {
              alert('Data updated successfully.')
                location.reload();
            } else {
                alert(result.msg);
            }
        }


    });

}

    $('.checkall').click(function() {
        const tabname = $(this).val();
        // console.log(tabname)
        if ($(this).is(':checked')) {
            $('input[class='+tabname+']').prop('checked', true);
        } else {
            $('input[class='+tabname+']').prop('checked', false);
        }
    });
</script>
@endsection