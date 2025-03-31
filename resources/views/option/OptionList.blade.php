@extends("layouts.Master")
@section("main_section")
<style type="text/css">
.swal2-icon.swal2-warning {
    font-size: 18px;
}


#example3_paginate a {
    background: #fff;
    padding: 6px 14px;
    border: 1px solid #dee2e6;
    cursor: pointer;
}

#example3_paginate a:hover {
    text-decoration: none;
    background: #e9ecef;
}

a.paginate_button.current {
    background: #0056b3 !important;
    color: #fff;
}

#example3_paginate .ellipsis {
    padding: 0px 6px;
    font-size: 14px;
}

#example3_wrapper {
    width: 100%;
    overflow-y: hidden;
    padding-bottom: 25px;
}

.table th, .table td {
    vertical-align: middle;
    box-sizing: border-box;
    font-size: 11px;
}

input {
    overflow: visible;
    width: 117px;
    border: 1px solid #8c8a8a;
    border-radius: 3px;
}

    /* placing the footer on top */
    tfoot {
        display: table-header-group;
    }
</style>

<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <div class="row">
                    <div class="col-sm-6">
                        <div class="card-header">
                            <h5 class="card-title">Option List</h5>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        @if(Auth::user()->UserType == 1)
                        <a href="{{route('options/add',0)}}" class="btn-shadow btn btn-info float-right">
                            Add New
                        </a>
                        @else
                        {{--  <a href="{{route('options/add1',0)}}" class="btn-shadow btn btn-info float-right">
                            Add New
                        </a>  --}}
                        @endif

                    </div>
                </div>
                <hr>
                <div class="row">
                <div class="col-md-2">
                    <select name="configurable" id="configurable" class="form-control">
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
                    </select>
                    <br>
                </div>
                <div class="col-md-12">
                    <table id="example3" class="table table-striped table-bordered">
                        <thead class="text-uppercase table-header-bg">
                            <tr class="text-white">
                                <th>S.No.</th>
                                <th>Fire Rating</th>
                                <th>OptionName</th>
                                <th>Image</th>
                                <th>OptionKey</th>
                                <th>OptionValue</th>
                                <th>Cost</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tfoot>
                            <tr>
                                <th>S.No.</th>
                                <th>Fire Rating</th>
                                <th>OptionName</th>
                                <th>Image</th>
                                <th>OptionKey</th>
                                <th>OptionValue</th>
                                <th>Cost</th>
                                <th>Action</th>
                            </tr>
                        </tfoot>

                        <tbody>
                            @php
                            $i = 1;
                            @endphp
                            @foreach($data as $row)
                                @php
                                if($row->file != ''){
                                    $file = $row->file;
                                } else {
                                    $file = 'team-author5.jpg';
                                }
                            @endphp
                            <tr>
                                <td>{{$i}}</td>
                                <td>{{$row->firerating}}</td>
                                <td>{{$row->OptionName}}</td>
                                <td><img src="{{url('/')}}/uploads/Options/{{$file}}" style="width:50px;height:50px;"></td>
                                <td style="width: 20% !important;word-break: break-all;">
                                    <script type="text/javascript">
                                        document.write(ReadMore(30,"{!! $row->OptionKey !!}"))
                                    </script>
                                </td>
                                <td style="width: 20% !important;word-break: break-all;">{{$row->OptionValue}}</td>
                                <td style="width: 10% !important;word-break: break-all;">{{$row->OptionCost}}</td>
                                @if (Auth::user()->UserType == 1)
                                <td style="width: 100px">
                                    <div style="float: left; margin-right: 5px">
                                        <!--  <form action="{{route('options/edit')}}" method="post">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="edit" value="{{$row->id}}">
                                            <button type="submit" class="btn btn-success"><i class="fa fa-pencil"></i></button>
                                            </form> -->
                                    </div>
                                    <span style="display: flex;">
                                        <a href="{{route('options/add',$row->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>
                                        <form method="post" style="padding-left: 5px;">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                            <input type="hidden" name="delete" value="{{$row->id}}">
                                            <!-- <button type="submit" id="delete" style="display: none"></button>                                            -->

                                            <button type="button" class="btn btn-danger" onclick="deletefunction({{$row->id}})">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </span>
                                </td>
                                @elseif(Auth::user()->UserType == 2)
                                    @if ($row->editBy != 1 || Auth::user()->UserType == 1)
                                    <td style="width: 100px">
                                        <div style="float: left; margin-right: 5px">
                                            <!--  <form action="{{route('options/edit')}}" method="post">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="edit" value="{{$row->id}}">
                                                <button type="submit" class="btn btn-success"><i class="fa fa-pencil"></i></button>
                                                </form> -->
                                        </div>
                                        <span style="display: flex;">
                                            {{--  <a href="{{route('options/add1',$row->id)}}" class="btn btn-success"><i class="fa fa-edit"></i></a>  --}}
                                            <form method="post" style="padding-left: 5px;">
                                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                                <input type="hidden" name="delete" value="{{$row->id}}">
                                                <!-- <button type="submit" id="delete" style="display: none"></button>                                            -->

                                                <button type="button" class="btn btn-danger" onclick="deletefunction({{$row->id}})">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>
                                        </span>
                                    </td>
                                    @else
                                    <td></td>
                                    @endif
                                @else
                                <td></td>
                                @endif
                            </tr>
                            @php
                            $i++;
                            @endphp
                            @endforeach
                        </tbody>

                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>
    <button style="display: none;" type="button" id="success-alert" data-type="success"
        class="btn btn-success btn-show-swal"></button>
    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}">
    @endsection

    @section('js')
    @if(session()->has('updated'))
    <script type="text/javascript">
    swal(
        'Success',
        'Option Updated the <b style="color:green;">Success</b>!',
        'success'
    )
    </script>
    @endif

    @if(session()->has('added'))
    <script type="text/javascript">
    swal(
        'Success',
        'Option Added the <b style="color:green;">Success</b>!',
        'success'
    )
    </script>
    @endif



<script type="text/javascript">
    function deletefunction(id) {
        swal({
                title: "Are you sure?",
                text: "if you delete this. parent and child will be delete. not get back anyone",
                type: "warning",
                confirmButtonText: "Yes, visit link!",
                showCancelButton: true
            })
            .then((result) => {
                if (result.value) {
                    // $("#delete").click();
                    $.ajax({
                        url: "{{route('options/delete')}}",
                        method: "POST",
                        data: {
                            'id': id,
                            _token: $("#_token").val()
                        },
                        success: function(result) {
                            swal(
                                'Success',
                                'Option Deleted the <b style="color:green;">Success</b>!',
                                'success'
                            ).then((result) => {
                                window.location.reload();
                            })
                        }
                    });


                } else if (result.dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        'Your stay here :)',
                        'error'
                    )
                }
            })
    }

    $(document).ready(function() {
        let i = 1;
        let label = '';
        $('#example3 tfoot th').each( function () {
            var title = $(this).text();
            if(i == 1){
                label = '<input type="text"  placeholder="Search '+title+'" />';
            } else if(i == 2){
                label = '<input type="text"  placeholder="Search '+title+'" />';
            } else if(i == 3){
                label = '<input type="text"  placeholder="Search '+title+'" />';
            } else if(i == 4){
                label = '<input type="text"  placeholder="Search '+title+'" />';
            } else if(i == 5){
                label = '<input type="text"  placeholder="Search '+title+'" />';
            } else if(i == 6){
                label = '<input type="text"  placeholder="Search '+title+'" />';
            } else if(i == 7){
                label = '';
            } else if(i == 8){
                label = '';
            }
            $(this).html(label);
            i++;
        });

        // DataTable
        var table = $('#example3').DataTable({
            initComplete: function () {
                // Apply the search
                this.api().columns().every( function () {
                    var that = this;

                    $( 'input', this.footer() ).on( 'keyup change clear', function () {
                        if ( that.search() !== this.value ) {
                            that
                                .search( this.value )
                                .draw();
                        }
                    } );
                } );
            }
        });
    } );

    $(document).on('change','#configurable',function(){
        let pageid = $(this).val();
        let url = "{{ url('/') }}/options/list/"+pageid;
        window.location.href=url;
    })
</script>
@endsection
