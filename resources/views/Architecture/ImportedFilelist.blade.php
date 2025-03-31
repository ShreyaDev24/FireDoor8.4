@extends("layouts.Master")

@section("main_section")
     <div class="app-main__outer">

                <div class="app-main__inner">
                   
                    <div class="main-card mb-3 card">
                        <div class="card-body">
                            <div class="row">
                            <div class="col-sm-6">
                            <div>{{{ trans('main.Quatationfilelist') }}}
                                   
                                   </div></div>
                            <div class="col-sm-6 ">
                        
                                    </div>
                            </div>
                            @if ($message = Session::get('success'))
            <div class="alert alert-success">
                <strong>{{ $message }}</strong>
            </div>
          @endif
          @if ($message = Session::get('error'))
            <div class="alert alert-danger">
                <strong>{{ $message }}</strong>
            </div>
          @endif
                        <hr>
                        <table id="example" class="table table-hover table-striped table-bordered">
                                <thead class="text-uppercase table-header-bg">
                                <tr class="text-white">
                                    <th>File Title</th>
                                    <th>file path</th>
                                    <th>action</th>
                                   
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($files as $row)
                                <tr>
                                   
                                    <td>{{$row->filename}}</td>
                                    <td>{{$row->filepath}}</td>
                                    <td> <a href="javascript:void(0)" class="btn btn-info"><i class="fa fa-trash" onclick="deleteMe({{$row->id}})"></i></button></td>
                                   
                                </tr>
                                @endforeach
                                </tbody>
                               
                            </table>
                        </div>
                    </div>
                </div>
    <button style="display: none;" type="button" id="success-alert" data-type="success" class="btn btn-success btn-show-swal"></button>

@endsection

@section('js')
<script>
function deleteMe(id){

if(confirm('do you relly want to delete?')){

  window.location.href = "{{{url('/file/delete/')}}}"+'/'+id;
}

}
</script>
   @if(session()->has('updated'))
    <script type="text/javascript">       
            swal(
                'Success',
                'Company Updated the <b style="color:green;">Success</b> !',
                'success'
            )
            
    </script>
@endif

 @if(session()->has('added'))
    <script type="text/javascript">       
            swal(
                'Success',
                'Company Added the <b style="color:green;">Success</b> !',
                'success'
            )
    </script>
@endif
@endsection