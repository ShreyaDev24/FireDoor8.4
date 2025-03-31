@extends("layouts.Master")

@section("main_section")
<style type="text/css">
    .addfields,
    .removefields {
        margin-top: 30px !important;
    }
</style>
<div class="app-main__outer">
    <div class="app-main__inner">
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


        <div class="tab-content">
            <a href="{{ url('non-configural-items/create') }}">
                <button id="nonconfigurableitem" class="btn btn-info float-right mt-3 mr-4">Create Non Configurable Item</button>
            </a>
            <div class="main-card mb-3 card">
                <table style="width:100%" id="example" class="table table-hover table-striped table-bordered" >
                    <thead class="text-uppercase table-header-bg">
                        <tr class="text-white">
                            <th>#</th>
                            <th>Name</th>
                            <th>Description</th>
                            <th>Image</th>
                            <th>Product Code</th>
                            <th>Unit</th>
                            <th>Cost</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(!empty($item))
                        @php
                        $i = 1;
                        @endphp
                        @foreach($item as $value)

                        <tr>
                            <td class="text-center">{{ $i++ }}</td>
                            <td class="text-center">{{ $value->name }}</td>
                            <td class="text-center"><script type="text/javascript">
                                document.write(ReadMore(5,"{{ $value->description }}"))
                            </script></td>
                            <td class="text-center"><img src="{{$value->NonconfiBase64}}" alt="" style="max-width:100px; max-height:50px;"></td>
                            <td class="text-center">{{ $value->product_code }}</td>
                            <td class="text-center">{{ $value->unit }}</td>
                            <td class="text-center">{{ $value->price }}</td>
                            {{--  @if($value->userId != 1 || Auth::user()->UserType == 1)  --}}
                            <td>
                                <a href="{{ url('non-configural-items/edit') }}/{{ $value->id }}">
                                <button type="button" class="btn btn-success" style="color: #fff; font-size:15px">
                                    <i class="fa fa-edit text-white text-center"></i>
                                </button>
                                </a>
                                <button type="button" onclick ="deletenonconfi('{{ $value->id }}')" class="btn btn-danger" style="color: #fff; font-size:15px">
                                    <i class="fa fa-trash text-white text-center"></i>
                                </button>
                            </td>
                            {{--  @else
                            <td></td>
                            @endif  --}}

                        </tr>

                        @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>

@endsection

@section('js')
<script>

function deletenonconfi(id) {
        swal({
                title: "Are you sure?",
                text: "if you delete this parent and child will be delete. not get back anyone",
                type: "error",
                confirmButtonText: "Delete",
                showCancelButton: true
            })
            .then((result) => {
                if (result.value) {
                    // $("#delete").click();
                    $.ajax({
                        url: "{{url('non-configural-items/delete')}}/"+id,
                        method: "GET",
                        data: {
                            _token: $("#_token").val()
                        },
                        success: function(result) {
                            swal(
                                'Success',
                                'Non Configurable Item Deleted <b style="color:green;">Success</b>!',
                                'success'
                            ).then((result) => {
                                window.location.reload();
                            })
                        }
                    });


                } else if (result.dismiss === 'cancel') {
                    swal(
                        'Cancelled',
                        'Non Configurable Item not deleted!',
                        'error'
                    )
                }
            })
    }
  </script>

@endsection
