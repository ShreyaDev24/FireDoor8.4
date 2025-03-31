@extends("layouts.Master")

@section("main_section")
@if(session()->has('error'))
<style type="text/css">
    #useremail{
        border-color: red
    }
</style>
@endif
<div class="app-main__outer">
    <div class="app-main__inner">
    <div class="card">
    <div id="accordion">
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
  <div class="question">
    <header>
      <h3>Painted Color</h3>
      <i class="fa fa-chevron-down"></i>
    </header>
    <main>
      <ul class="accordian_list">
      <?php $index = 0;?>
        @if(!empty($electedColorOption) && count((array)$electedColorOption)>0)
            @foreach($electedColorOption as $row)
                @if($row->DoorLeafFacing=='Painted')

        <li>
          <div class="row">
          <div class="col-md-5 col-sm-12">
          <label><b><i class="fa fa-check-circle" aria-hidden="true"></i> {{$row->ColorName}}</b></label>
        </div>

        <div class="col-md-1 col-sm-6 text-center mb-2"><span class="color_box" style="background:{{$row->Hex}}"></span></div>
        <div class="col-md-5 col-sm-6 text-center ">{{$row->Hex}}</div>

          <div class="col-md-1 col-sm-12">
          <div class="control-group">
        <label class="control control-checkbox">
        <input type="checkbox" class="painted" value="{{$row->id}}" {{$row->selected=="1"?'checked':''}} />
        <div class="control_indicator"></div>
    </label>
    </div>
    </div>
  </div>
 </li>

                @endif
                <?php $index++ ;?>
            @endforeach
        @endif





      </ul>
      <button class="accordian_update_button" onclick="updateMe('painted')" >Update</button>
    </main>
  </div>


  <div class="question">
    <header>
      <h3>Laminate Color</h3>
      <i class="fa fa-chevron-down"></i>
    </header>
    <main>
      <ul class="accordian_list">
      <?php $index = 0;?>
        @if(!empty($electedColorOption) && count((array)$electedColorOption)>0)
            @foreach($electedColorOption as $row)
                @if($row->DoorLeafFacing=='Laminate')
     <li>
          <div class="row">
          <div class="col-md-5 col-sm-12">
          <label ><b><i class="fa fa-check-circle" aria-hidden="true"></i> {{$row->ColorName}}</b></label>
        </div>

        <div class="col-md-1 col-sm-6 text-center mb-2"><span class="color_box" style="background:{{$row->Hex}}"></span></div>
        <div class="col-md-5 col-sm-6 text-center">{{$row->Hex}}</div>

          <div class="col-md-1 col-sm-12">
          <div class="control-group">
        <label class="control control-checkbox">
        <input type="checkbox" class="Laminate" value="{{$row->id}}" {{$row->selected=="1"?'checked':''}} />
        <div class="control_indicator"></div>
    </label>
    </div>
    </div>
  </div>
 </li>

                @endif
                <?php $index++ ;?>
            @endforeach
        @endif





      </ul>
      <button class="accordian_update_button" onclick="updateMe('Laminate')" >Update</button>
    </main>
  </div>

  <div class="question">
    <header>
      <h3>PVC Color</h3>
      <i class="fa fa-chevron-down"></i>
    </header>
    <main>
      <ul class="accordian_list">

        @if(!empty($electedColorOption) && count((array)$electedColorOption)>0)
            @foreach($electedColorOption as $row)
                @if($row->DoorLeafFacing=='PVC')

        <li>
          <div class="row">
          <div class="col-md-5 col-sm-12">
          <label ><b><i class="fa fa-check-circle" aria-hidden="true"></i> {{$row->ColorName}}</b></label>
        </div>

        <div class="col-md-1 col-sm-6 text-center mb-2"><span class="color_box" style="background:{{$row->Hex}}"></span></div>
        <div class="col-md-5 col-sm-6 text-center" >{{$row->Hex}}</div>

          <div class="col-md-1 col-sm-12">
          <div class="control-group">
        <label class="control control-checkbox">
        <input type="checkbox" class="pvc" value="{{$row->id}}" {{$row->selected=="1"?'checked':''}} />
        <div class="control_indicator"></div>
    </label>
    </div>
    </div>
  </div>
 </li>

                @endif
                <?php $index++ ;?>
            @endforeach
        @endif





      </ul>
      <button class="accordian_update_button" onclick="updateMe('pvc')" >Update</button>
    </main>
  </div>





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


function updateMe(className){
    var selectedId = [];
    $('.'+className+':checked').each(function() {
        selectedId.push($(this).val());
     });

    // var key = $(".keys_"+id).val();

            $.ajax({
                url:  "{{url('options/update-color')}}",
                method:"POST",
                dataType:"Json",
                data:{selectedId:selectedId,className:className,_token:$("#_token").val()},
                success: function(result){
                    if(result.status="ok"){
                        location.reload();
                    }else{
                        alert(result.msg);
                    }
                }


                });

}
</script>
@endsection
