@extends("layouts.Master")
@section("main_section")


<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="tab-content">
            @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Alert!</h5>
                {{ session()->get('success') }}
            </div>
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
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="card-header">
                        <h5 class="card-title" style="margin-top: 10px">Cost Setting</h5>
                    </div>
                    <form action="{{route('subcostsetting')}}" method="post">
                        {{ csrf_field() }}
                        <input type="hidden" name="updval" value="@if(session()->has('upd')){{session()->get('upd')->id}}@endif">
                        <div class="row">
                            <div class="col-md-2">
                                <div class="position-relative form-group">
                                    <label>Name<span class="text-danger">*</span></label>
                                    <select name="name" id="selectname" class="form-control" required>
                                        <option value="">Select Fields</option>
                                        @php
                                        $select = '';
                                        if(session()->has('upd')){
                                            $select = session()->get('upd')->name;
                                        }
                                        @endphp
                                        <option value="DoorCore" @if($select == 'DoorCore'){{ 'selected' }}@endif>DoorCore</option>
                                        <option value="Finishes" @if($select == 'Finishes'){{ 'selected' }}@endif>Finishes</option>
                                        <option value="FrameSizes" @if($select == 'FrameSizes'){{ 'selected' }}@endif>FrameSizes</option>
                                        <option value="Architrave" @if($select == 'Architrave'){{ 'selected' }}@endif>Architrave</option>
                                    </select>
                                </div>
                            </div>
                            
                            <div class="col-md-2 parent" 
                                style="@if(session()->has('upd')) 
                                    @if(session()->get('upd')->parent != '') 
                                    
                                    @endif 
                                    @else
                                    {{'display:none;'}}
                                @endif">
                                <div class="position-relative form-group">
                                    <label>Parent</label>
                                    <select name="parent" class="form-control">
                                    @php
                                        $selectParent = '';
                                        if(session()->has('upd')){
                                            $selectParent = session()->get('upd')->parent;
                                        }
                                        @endphp
                                        <option value="">Select Field</option>
                                        <option value="Kraft_Paper" @if($selectParent == 'Kraft_Paper'){{ 'selected' }}@endif>Kraft Paper</option>
                                        <option value="Laminate" @if($selectParent == 'Laminate'){{ 'selected' }}@endif>Laminate</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="position-relative form-group">
                                    <label>Width<span class="text-danger">*</span></label>
                                    <input type="number" min="1" step="0.01" name="width" class="form-control"
                                        value="@if(session()->has('upd')){{session()->get('upd')->width}}@endif" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="position-relative form-group">
                                    <label>Height OR L/M<span class="text-danger">*</span></label>
                                    <input type="number" min="1" step="0.01" name="height" class="form-control"
                                        value="@if(session()->has('upd')){{session()->get('upd')->height}}@endif" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="position-relative form-group">
                                    <label>Depth<span class="text-danger">*</span></label>
                                    <input type="number" min="1" step="0.01" name="depth" class="form-control"
                                        value="@if(session()->has('upd')){{session()->get('upd')->depth}}@endif" required>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="position-relative form-group">
                                    <label>Cost<span class="text-danger">*</span></label>
                                    <input type="number" min="1" step="0.01" name="cost" class="form-control"
                                        value="@if(session()->has('upd')){{session()->get('upd')->cost}}@endif" required>
                                </div>
                            </div>
                            <div class="col-md-12"><hr></div>
                            <div class="col-md-12">
                                <button type="submit" id="submit" class="btn-wide btn btn-success"
                                    style="float: right;">
                                    @if(session()->has('upd')) {{'Update'}} @else {{'Submit'}} @endif
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="main-card mb-3 card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                                <thead class="text-uppercase table-header-bg">
                                    <tr class="text-white">
                                        <th>S.No.</th>
                                        <th>Name</th>
                                        <th>Parent</th>
                                        <th>Width</th>
                                        <th>Height OR L/M</th>
                                        <th>Depth</th>
                                        <th>Cost</th>                        
                                        <th>Manage</th>                                 
                                    </tr>
                                </thead>
                                <tbody>
                                    {!! $tbl !!}
                                </tbody>                               
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
@section("script_section")
    <script>
        $(function(){
            const name = $('#selectname').val();
            if(name == 'Finishes'){
                $('.parent').css({'display':'block'});
            } else {
                $('.parent').css({'display':'none'});
            }

            $(document).on('change','#selectname',function(e){
                e.preventDefault();
                const name = $(this).val();
                if(name == 'Finishes'){
                    $('.parent').css({'display':'block'});
                } else {
                    $('.parent').css({'display':'none'});
                }
            })
        })
    </script>
@endsection