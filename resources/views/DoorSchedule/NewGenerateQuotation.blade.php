
@extends("layouts.Master")
@section("main_section")
<div class="app-main__outer">
<div class="app-main__inner">
   <div class="main-card mb-3 card">
      <div class="card-body">
         <!-- table-striped -->
         <div class="alert alert-success message_success" role="alert">
         </div>
         <div class="alert alert-danger message_error" role="alert">
         </div>
         <div >
            <div class="action_options">
            @if(!empty($generatedId))
                        <h2 class="origin_heading">Quote Search / {{$generatedId}}</h2>
                        @else
                        <h2 class="origin_heading">Door Schedule / {{$ArcgeneratedId}}</h2>
                        @endif
               <ul class="float-right">
                  <li>
                     <div class="dropdown">
                        <a class="btn btn-light dropdown-toggle" data-toggle="dropdown">PRINT</a>
                        <ul class="dropdown-menu drop_style">
                           <li><a href="#">Generate Document</a></li>
                           <li><a href="#">EMail Documnet</a></li>
                           <li><a href="#">More</a></li>
                        </ul>
                     </div>
                  </li>
                  @if(Auth::user()->UserType=='2')
                  <li>
                     <div class="dropdown">
                        <a class="btn btn-light dropdown-toggle"  data-toggle="dropdown">Revision</a>
                        <ul class="dropdown-menu drop_style">
                           @if(is_array($version))
                           @foreach($version as $row)
                           <li><a href="javascript:void(0);" onclick="getVersion('{{$row['Version']}}',{{$quotationId}})">Revision-{{$row['Version']}}</a></li>
                           @endforeach
                           @endif
                        </ul>
                     </div>
                  </li>
                  @endif
                  <li>
                     <div class="dropdown">
                        <a class="btn btn-light dropdown-toggle"  data-toggle="dropdown">MORE</a>
                        <ul class="dropdown-menu drop_style">
                           <!-- <li><a href="#">Create Version</a></li> -->
                           <li><a href="#">Rapid Change</a></li>
                           <li><a href="#">Attachment</a></li>
                           <li><a href="{{url('quotation/excel-upload/'.$quotationId)}}">Import</a></li>
                           <li><a href="#">Export</a></li>
                           <li><a href="#">Dlelete</a></li>
                        </ul>
                     </div>
                  </li>
                  <li>
                     <button class="btn btn-primary float-right" id="generateInvoice">Generate Invoice</button>
                  </li>
               </ul>
            </div>
            <div class="table_list_row">
               <h5 class="float-left">
                  <!-- <span>Checkmate Fire Solutions LTD</span>
                     <span>Cambridge House</span>
                     <span>Cambridge Road, Harlow CM20 2EQ</span> -->
               </h5>
               <!-- <h3 class="float-right">Change Customer</h3> -->
            </div>
            <table class="table table-bordered table-striped">
               <thead>
                  <tr>
                     <!-- <th><input type="checkbox" class="check" id="selectAll" > Select All</th> -->
                     <th>Line</th>
                     <th>Label</th>
                     <th>Item</th>
                     <th>Qty</th>
                     <th>Price</th>
                     <th>Total</th>
                     <th></th>
                  </tr>
               </thead>
               <tbody id="versionData">
                  @if(!empty($data) && count($data)>0)
                  <?php  $index =0; $SI=1;?>
                  @foreach($data as $row)
                  <tr>
                     <!-- <td><input type="checkbox" class="check" value="{{$row->itemId}}"></td> -->
                     <td>{{$SI}} <input type="hidden" class="check" value="{{$row->itemId}}"><input type="hidden" class="doors_{{$index}}" value="{{$row->id}}"></td>
                     <td>{{$row->DoorNumber}}</td>
                     <td>@if($row->DoorsetType=='DD'){{'Double Door'}}@elseif($row->DoorsetType=='SD'){{'Single Door'}} @else {{"Leaf and a half"}} @endif</td>
                     <td><input type="number"  style="width: 100%;" readonly id="quantity" value="1" name="quantity" min="1" max="100" class="quantity_{{$index}}"></td>
                     <td>{{$row->DoorsetPrice}}</td>
                     <td>{{$row->DoorsetPrice}}</td>
                     <td class="text-center">
                        <div class="dropdown">
                           <a class="dropdown-toggle btn btn-light" type="button" data-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></a>
                           <ul class="dropdown-menu">
                              <li><a href="#">Change Option</a></li>
                              <li><a href="#">Name</a></li>
                              <li><a href="#">Configuration</a></li>
                              <li><a href="#">Adjust Price</a></li>
                              <li><a href="#">Comment</a></li>
                              <li><a href="#">Copy</a></li>
                              <li><a href="#">Export</a></li>
                              <li><a href="#">Remove</a></li>
                           </ul>
                        </div>
                     </td>
                  </tr>
                  <?php $index++; $SI++; ?>
                  @endforeach
                  @endif
                  <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
                  <input type="hidden" name="quotationId" id="quotationId" value="{{$quotationId}}" />
                  <input type="hidden" name="version" id="version" value="0" />
               </tbody>
            </table>
         </div>
      </div>
   </div>
</div>
<button style="display: none;" type="button" id="success-alert" data-type="success" class="btn btn-success btn-show-swal"></button>
@endsection
@section('js')
<script>
   $(document).ready(function(){
    $(".message_success").hide();
    $(".message_error").hide();
   })
   // alert(123);
   $('#selectAll').click(function (ele) {
     // alert(112);
     var checkboxes = document.getElementsByClassName('check');
         if($(this).prop("checked") == true)
         {
             $(this).prop('checked',false);
             for(var i = 0; i < checkboxes.length; i++)
             {
                 $(checkboxes[i]).prop('checked',true);
             }

         }else{
             $(this).prop('checked',true);

             for(var i = 0; i < checkboxes.length; i++)
                 {
                     $(checkboxes[i]).prop('checked',false);


                 }
     }



   });

   $("#generateInvoice").click(function(){
     var checkboxes = document.getElementsByClassName('check');
     var isChecked =false;
     var doors =[];
     var items =[];
     var quantity =[];
     for(var i = 0; i < checkboxes.length; i++)
         {

                 isChecked=true;

                 items.push(checkboxes[i].value);
             // var quant = document.getElementsByClassName('quantity_'+i);
             // items.push(quant[0].value);
             quantity.push($('.quantity_'+i).val());

             // var door = document.getElementsByClassName('doors_'+i)[0].value;
             // doors.push(door[0].value);
             doors.push($('.doors_'+i).val());

         }

   if(isChecked){

   $.ajax({
     type: "POST",
     url: "{{url('quotation/versionstore')}}",
     data: {_token : $("#_token").val(),quotationId:$("#quotationId").val(),itemId:$("#version").val(),doors:doors,quantity:quantity,items:items},
     success: function (data) {

         if(data.status == "ok"){
             $(".message_success").empty().append(data.msg).show();
             setInterval(function(){
                 location.reload();
                  }, 3000);
         }else{

             $(".message_error").empty().append(data.msg).show();
         }


     },
     error: function (data) {
             $(".page-loader-action").fadeOut();
             swal("Oops!!", "Something went wrong. Please try again.", "error");
     }
   });


         }else{
             alert('select atleast one door');
         }
   });


   function getVersion(version,quatationId){
   $.ajax({
     type: "POST",
     url: "{{url('quotation/get-version')}}",
     data: {_token : $("#_token").val(),version:version,quatationId:quatationId},
     dataType:'Json',
     success: function (data) {
         innerhtml='';
         if(data.status=="ok"){
             // alert(data.status);
             var door = data.door;
             var length = door.length;
             // alert(length);
             for(var i=0; i<length;i++){
                 // alert(door[i].DoorsetType);
                 if(door[i].DoorsetType=="DD"){
                    var doorType='Double Door';
                 }else if(door[i].DoorsetType=="SD"){
                     var doorType='Single Door';
                 }else{
                     var doorType='Leaf and a half';
                 }

             innerhtml+='<tr>';
             innerhtml+='<td>'+(i+1)+'<input type="hidden" class="check" value="'+door[i].itemId+'"><input type="hidden" class="doors_'+i+'" value="'+door[i].id+'"></td>';
             innerhtml+='<td>'+door[i].DoorNumber+'</td>';
             innerhtml+='<td>'+doorType+'</td>';
             innerhtml+='<td><input type="number"  style="width: 100%;" readonly id="quantity" value="1" name="quantity" min="1" max="100" class="quantity_'+i+'"></td>';
             innerhtml+='<td>'+door[i].DoorsetPrice+'</td>';
             innerhtml+='<td>'+door[i].DoorsetPrice+'</td>';
             innerhtml+='<td class="text-center">';
             innerhtml+='<div class="dropdown">';
             innerhtml+='<a class="dropdown-toggle btn btn-light" type="button" data-toggle="dropdown"><i class="fa fa-ellipsis-h"></i></a>';
             innerhtml+='<ul class="dropdown-menu">';
             innerhtml+='<li><a href="#">Change Option</a></li>';
             innerhtml+='<li><a href="#">Name</a></li>';
             innerhtml+='<li><a href="#">Configuration</a></li>';
             innerhtml+='<li><a href="#">Adjust Price</a></li>';
             innerhtml+='<li><a href="#">Comment</a></li>';
             innerhtml+='<li><a href="#">Copy</a></li>';
             innerhtml+='<li><a href="#">Export</a></li>';
             innerhtml+='<li><a href="#">Remove</a></li>';
             innerhtml+='</ul>';
             innerhtml+='</div>';
             innerhtml+='</td>';
             innerhtml+='</tr>';



         }
         innerhtml+='<input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />';
         innerhtml+='<input type="hidden" name="quotationId" id="quotationId" value="'+data.quotationId+'" />';
         innerhtml+='<input type="hidden" name="version" id="version" value="'+data.version+' />';

         }else{
             innerhtml+='';
         }

       $("#versionData").empty().append(innerhtml);

     },
     error: function (data) {
             $(".page-loader-action").fadeOut();
             swal("Oops!!", "Something went wrong. Please try again.", "error");
     }
   });
   }

</script>
@endsection

