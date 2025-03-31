@extends("layouts.Master")

@section("main_section")
     <div class="app-main__outer">
                <div class="app-main__inner">
                    <div class="app-page-title">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-medal icon-gradient bg-tempting-azure">
                                    </i>
                                </div>
                                <div>Configuration
                                   
                                </div>
                            </div>
                            <div class="page-title-actions">
                                <div class="d-inline-block dropdown">
                                    <a  href="{{route('user/add')}}" class="btn-shadow btn btn-info">
                                        Add New
                                    </a>
                                </div>
                            </div>    
                        </div>
                    </div>            
                    	<div class="main-card mb-3 card">
                       <div class="card-body">
                            <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered">
                                <thead>
                                <tr>
                                    <th>QuotationName</th>
                                    <th>ProductName</th>
                                    <th>CreatedAt</th>
                                    <th>UpdatedAt</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($data as $row)
                                <tr>
                                    <td>{{$row->QuotationName}}</td>
                                    <td>{{$row->ProductName}}</td>
                                    <td>{{$row->created_at}}</td>
                                    <td>{{$row->updated_at}}</td>
                                    <td style="width: 100px">
                                        <div style="float: left; margin-right: 5px">
                                        <form action="{{route('quotation/request')}}" method="post">
                                        <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                        <input type="hidden" name="quotation_id" value="{{$row->id}}">
                                        <button type="submit" class="btn btn-success"><i class="fa fa-info"></i></button>
                                        </form>
                                        </div>
                                    </td>
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
   @if(session()->has('updated'))
    <script type="text/javascript">       
            swal(
                'Success',
                'User Updated the <b style="color:green;">Success</b>!',
                'success'
            )
    </script>
@endif

 @if(session()->has('added'))
    <script type="text/javascript">       
            swal(
                'Success',
                'Quotation Added <b style="color:green;">Success</b>!',
                'success'
            )
    </script>
@endif
@endsection