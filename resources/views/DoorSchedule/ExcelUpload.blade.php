
@extends("layouts.Master")
@section("main_section")
<div class="app-main__outer">
<div class="app-main__inner">
   <div class="tab-content">
      <div class="main-card mb-3 card">
         <div class="card-body">
            <div class="tab-content">
               <div class="card-header">
                  <h5 class="card-title" style="margin-top: 10px">Add New Doors</h5>
                  @if (!empty($msg))
                  <h1>{{$msg}}</h1>
                  @endif


               </div>
               <a href="{{url('quotation/generate')}}/{{Request::segment(3)}}/{{$vid}}" class="btn-shadow btn btn-info float-right" style="margin-right:5px; margin-top:-50px">
                                <i class="fa fa-arrow-left"></i> Back
                            </a>
               @if(session()->has('success'))
               <div class="alert alert-success alert-dismissible">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <strong>Success!</strong> {!! session()->get('success') !!}
               </div>
               @endif
               @if(session()->has('error'))
               <div class="alert alert-danger alert-dismissible">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                  <strong>Error!</strong> {!! session()->get('error') !!}
               </div>
               @endif
               <span class="error"></span>
               <span class="success"></span>
               @if($ProjectFiles > 0)
                  <h3 style="display: flex;">
                     File already exist please click to upload file!
                     <form method="post" action="{{route('ImportfileUpload')}}" enctype="multipart/form-data" >
                        {{csrf_field()}}
                        <input type="hidden" id="quotationId" name="quotationId" value="{{$quotationId}}">
                        <input type="hidden" id="versionId" name="versionId" value="{{$vid}}">
                        <input type="submit" value="Click Here" class="btn btn-success" >
                     </form>
                  </h3>
               @else
                  <form method="post" action="{{route('quotation/store-excel')}}" enctype="multipart/form-data" >
                     {{csrf_field()}}
                     <div class="card-body">
                        <div class="form-row">
                           <div class="col-md-3">
                              <div class="position-relative form-group">
                                 <label for="file">Excel File</label>
                                 <input name="ExcelFile" id="ExcelFile" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel"    required class="form-control">
                              </div>
                           </div>
                           <input type="hidden" id="quotationId" name="quotationId" value="{{$quotationId}}">
                           <input type="hidden" id="versionId" name="versionId" value="{{$vid}}">
                           <div class="col-md-6">
                            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
                            <input type="hidden" id="edit_image" value="{{url('/quotation/edit-image')}}" />
                            <input type="hidden" id="edit_image1" value="{{url('/quotation/edit-image1')}}" />
                            <input type="hidden" id="base_url" value="{{url('/')}}">
                              <div class="position-relative form-group">
                                 <label for="file" class=""></label>
                                 <input type="submit" value="Submit" class="btn btn-success" style="margin-top: 25px;">


                              </div>

                           </div>
                        </div>
                     </div>
                  </form>
                  <div id="validate"></div>
               @endif
            </div>
         </div>
      </div>
   </div>
   <div class="tab-content">
      <div class="main-card mb-3 card">
         <div class="card-body">
            <div class="card-header">
               <h5 class="card-title" style="margin-top: 10px">Add New Doors [With Match Column]</h5>
            </div>
            @if(session()->has('success2'))
            <div class="alert alert-success alert-dismissible">
               <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
               <strong>Success!</strong> {!! session()->get('success2') !!}
            </div>
            @endif
            <form class="form-horizontal" method="POST" action="{{ route('parseImport') }}" enctype="multipart/form-data">
               {{ csrf_field() }}
               <input type="hidden" name="quotationId" value="{{$quotationId}}">
               <input type="hidden" name="versionId" value="{{$vid}}">
               <div class="form-row mb-2">
                  <div class="col-md-3">
                  <label for="ConfigurationType">Configuration Type</label>
                  <select required name="ConfigurationType" id="ConfigurationType" class="form-control">
                     <option value="">Select Configuration Type</option>
                     <option value="1">Streboard</option>
                     <option value="2">Halspan</option>
                     <option value="7">Flamebreak</option>
                     <option value="8">Stredoor</option>
                     <option value="3">Norma</option>
                     <option value="4">Vicaima</option>
                     <option value="5">Seadec</option>
                     <option value="6">Deanta</option>
                 </select>
                  </div>
               </div>

               <div class="form-row">
                  <div class="col-md-3">
                     <div class="position-relative form-group">
                        <label for="csv_file">File to import</label>
                        <input id="csv_file" type="file" class="form-control" name="csv_file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required>
                        <p style="color:red; font-size:12px; margin-top:5px">File must contain firerating, doornumber and doortype. Project floor name should match document floor name.</p>
                     </div>
                  </div>
                  <div class="col-md-6 mt-4">
                     <button type="submit" class="btn btn-primary mt-1">Match Column</button>
                  </div>
               </div>
            </form>
         </div>
      </div>
   </div>
   <div id="user_jobs" hidden></div>
   <input type="hidden" id="SvgImage" name="SvgImage" value="" />
   <input type="hidden" id='store2' value="{{url('items/store2')}}">
</div>
</div>

@endsection
@section("js")
<script>

//$('#validate').on('click',function(){
    // $(document).ready(function(){
    //     var versionId = $('#versionId').val();
    //     var quotationId = $('#quotationId').val();
    //     var base_url = $('#base_url').val();
    //      $.ajax({
    //         url:  $("#edit_image").val(),
    //         method:"POST",
    //         data:{_token:$("#_token").val(), quotationId:quotationId, versionId:versionId},
    //         dataType:"Json",
    //         success: function(result){
    //             if(result.status == "ok"){
    //                 var html = '';
    //                 //$('.loader').empty().css({'display':'block'});
    //                 for(var i = 0; i < result.data.length; i++){
    //                     //(function(i) {
    //                         html += '<button onclick = "edit_image1(' + result.data[i] + ')"  class="btn btn-success Validate' + result.data[i] + '" style="margin-right: 10px; margin-top: 10px;">Validate ' + result.data[i] + '</button>';

    //                         //var timeToStartNote = 8000 * i;
    //                         //setTimeout(function() {
    //                             //edit_image1(result.data[i])
    //                         //}, timeToStartNote);
    //                     //})(i);
    //                 }
    //                 $('#validate').empty().append(html);
    //                 if(result.data == ''){
    //                     $('#validate').empty();
    //                     //alert('Doorsets Not Exist!')
    //                     //$('.loader').empty().css({'display':'none'});
    //                 }
    //             }
    //         },
    //     });
    // });

    // function edit_image1(element){
    //     $('.loader').empty().css({'display':'block'});
    //     var versionId = $('#versionId').val();
    //     var quotationId = $('#quotationId').val();
    //     $.ajax({
    //         // async: false,
    //         url:  $("#edit_image1").val(),
    //         method:"POST",
    //         data:{_token:$("#_token").val(), quotationId:quotationId, versionId:versionId, id:element},
    //         dataType:"Json",
    //         success: function(data){
    //             if(data.status == true) {
    //                 //user_jobs div defined on page
    //                 $('.loader').empty().css({'display':'block'});
    //                 $('#user_jobs').empty().html(data.html);
    //                 setTimeout(function() {
    //                     editSvg()
    //                 }, 4000);
    //             }
    //         }
    //     });
    // }

    // function editSvg(){
    //     // var retval = true;
    //     var itemID = $('#itemID').val();
    //     var SvgImage = $('#SvgImage').val();
    //     if(SvgImage == ''){
    //         $('.error').empty().html(
    //             `<div class="alert notify alert-dismissible">
    //                 <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    //                 <h5><i class="fas fa-exclamation"></i> Alert!</h5>
    //                 <ul>wrong credential is inserted for
    //                     ` + itemID + `
    //                 </ul>
    //             </div>`);
    //             $('.loader').empty().css({'display':'none'});
    //     }
    //     var versionId = $('#versionId').val();
    //     var quotationId = $('#quotationId').val();

    //     $.ajax({
    //         url: $('#store2').val(),
    //         type: 'POST',
    //         data: {SvgImage:SvgImage,_token:$("#_token").val(),QuotationId:quotationId,versionId:versionId,itemID:itemID},
    //         datatype: "json",
    //         success: function(res) {
    //             // console.log(res);
    //             // console.log(res.data)
    //             if (res.status == 'error') {
    //                 $('.error').empty().html(
    //                     `<div class="alert notify alert-dismissible">
    //                         <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    //                         <h5><i class="fas fa-exclamation"></i> Alert!</h5>
    //                         <ul>
    //                             ` + res.errors + `
    //                         </ul>
    //                     </div>`);
    //                     $('.loader').empty().css({'display':'none'});
    //                 setTimeout(() => {
    //                     $('.error').html('')
    //                 }, 10000);
    //             } else if (res.status == 'success') {
    //                 $('.success').empty().html(
    //                     `<div class="alert alert-success alert-dismissible">
    //                         <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    //                         <strong>Success!</strong> ` + res.data + `
    //                     </div>`);
    //                     $('.loader').empty().css({'display':'none'});
    //                     $('.Validate'+itemID).attr({'disabled':true});
    //                 setTimeout(() => {
    //                     $('.success').html('')
    //                 }, 10000);

    //             }
    //         }
    //     });
    // }


</script>
@endsection

