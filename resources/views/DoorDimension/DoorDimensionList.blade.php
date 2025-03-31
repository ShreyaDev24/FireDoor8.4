@extends("layouts.Master")

@section("main_section")
     <div class="app-main__outer">

                <div class="app-main__inner">

                    <div class="main-card mb-3 card">
                        <div class="card-body">
                          <div class="row">
                            <div class="col-sm-6">
                                <div class="card-header"><h5 class="card-title">Door Dimension List</h5></div>
                            </div>
                            <div class="col-sm-6 ">
                            @if(Auth::user()->UserType=='1')

                                    <a  href="{{route('door-dimension-add')}}" class="btn-shadow btn btn-info float-right">
                                        Add New
                                    </a>

                                @endif
                            </div>
                            </div>
                            <hr>
                            <div class="col-md-2">
                                <select name="configurableitems" id="configurable" class="form-control">
                                    <option value="1"
                                    @if($pageId == 1)
                                        {{ 'selected' }}
                                    @endif
                                    >Strebord</option>
                                    <option value="2"
                                    @if($pageId == 2)
                                        {{ 'selected' }}
                                    @endif
                                    >Halspan</option>
                                    <option value="3"
                                    @if($pageId == 3)
                                        {{ 'selected' }}
                                    @endif
                                    >Norma</option>
                                </select>
                                <br>
                            </div>
                            @if($pageId == 3)
                            <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                                <thead class="text-uppercase table-header-bg">
                                <tr class="text-white">

                                    <th>Code</th>
                                    <th>Inch Height</th>
                                    <th>Inch Width</th>
                                    <th>MM Height</th>
                                    <th>MM Width</th>
                                    <th>Fire Rating</th>
                                    <th>Door Leaf Finish</th>
                                    <th>Door Leaf Facing</th>
                                    <th>Cost Price</th>
                                    {{-- <th>Selling Price</th> --}}
                                    <th>Image</th>
                                    <th>Edit</th>

                                    <!-- @if(Auth::user()->UserType=='2')
                                    <th>Action</th>
                                    @endif -->
                                </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $i=1;
                                    @endphp
                                @foreach($doorDimension as $value)
                                <tr>

                                    <td>{{$value->code}}</td>
                                    <td>{{$value->inch_height}}</td>
                                    <td>{{$value->inch_width}}</td>
                                    <td>{{$value->mm_height}}</td>
                                    <td>{{$value->mm_width}}</td>
                                    <td>{{$value->fire_rating}}</td>
                                    <td>{{$value->door_leaf_finish}}</td>
                                    <td>{{$value->door_leaf_facing}}</td>
                                    <td>{{$value->cost_price}}</td>
                                    {{-- <td>{{$value->selling_price}}</td> --}}
                                    <td><img style="width: 50px" src="{{url('/')}}/DoorDimension/{{$value->image}}"></td>
                                    <td style="width: 100px">
                                    <a href="{{route('DoorDimension/edit',$value->id)}}" class="btn btn-success"><i class="fa fa-pencil"></i></a>
                                    <button onClick="dimension_delete({{$value->id}})" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                    </td>

                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                            @endif

                            @if($pageId == 1 || $pageId == 2)
                            <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                                <thead class="text-uppercase table-header-bg">
                                <tr class="text-white">

                                    {{-- <th>Code</th> --}}
                                    <th>Inch Height</th>
                                    <th>Inch Width</th>
                                    <th>Fire Rating</th>
                                    {{-- <th>Selling Price</th>
                                    <th>Image</th> --}}
                                    <th>Edit</th>

                                    <!-- @if(Auth::user()->UserType=='2')
                                    <th>Action</th>
                                    @endif -->
                                </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $i=1;
                                    @endphp
                                @foreach($doorDimension as $value)
                                <tr>

                                    {{-- <td>{{$value->code}}</td> --}}
                                    <td>{{$value->mm_height}}</td>
                                    <td>{{$value->mm_width}}</td>
                                    <td>{{$value->fire_rating}}</td>
                                    {{-- <td>{{$value->selling_price}}</td>
                                    <td><img style="width: 50px" src="{{url('/')}}/DoorDimension/{{$value->image}}"></td> --}}
                                    @if ($value->editBy != 1 || Auth::user()->UserType == 1)
                                    <td style="width: 100px">
                                    <a href="{{route('DoorDimension/edit',$value->id)}}" class="btn btn-success"><i class="fa fa-pencil"></i></a>
                                    <button onClick="dimension_delete({{$value->id}})" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                    </td>
                                    @else
                                    <td></td>
                                    @endif
                                </tr>
                                @endforeach
                                </tbody>
                            </table>
                            @endif
                        </div>
                    </div>
                </div>
                <form action="{{route('DoorDimension/delete')}}" method="post" id="dimension_delete">
        {{ csrf_field() }}
        <input type="hidden" name="id" id="id">
        <!-- <button type="submit" style="background:none" id=""></button> -->
    </form>

@endsection

@section('js')
@if(session()->has('success'))
    <script type="text/javascript">
            swal(
                'Success',
                // 'Customer Added the <b style="color:green;">Success</b> button!',
                'Main Contractor updated Successfully'
                'success'
            )
    </script>







@endif

<script>
            function dimension_delete(id){
              var r = confirm("Are you sure! you wan't to delete door dimension.");
                if (r == true) {
                    $('#id').val(id);
                    $('#dimension_delete').submit();
                }
            }
            $(document).on('change','#configurable',function(){
                let pageid = $(this).val();
                let url = "{{ url('door-dimension-list') }}/"+pageid;
                window.location.href=url;
            })
        </script>
@endsection
