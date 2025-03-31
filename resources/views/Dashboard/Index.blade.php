@extends("layouts.Master")

@section("main_section")
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="tabs-animation statistics">            
            <div class="row">
                @switch($authdata->UserType)
                {{--admin--}}
                @case(1)
                <div class="col-md-3">
                    <div class="box-dashboard">
                        <i class="fa fa-building fa-fw"></i>
                        <a href="{{route('company/list')}}" class="info">
                            <h3>{{$company_count}}</h3>
                            <p>Company</p>
                        </a>
                    
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box-dashboard">
                        <i class="fa fa-users fa-fw"></i>
                        <a href="{{route('user/list')}}" class="info">
                            <h3>{{$user_count}}</h3>
                            <p>User</p>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box-dashboard">
                        <i class="fa fa-building fa-fw"></i>
                        <a href="{{route('contractor/list')}}" class="info">
                            <h3>{{$customer_count}}</h3>
                            <p>Customers</p>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box-dashboard">
                        <i class="fa fa-tasks fa-fw"></i>
                        <a href="{{route('project/list')}}" class="info">
                            <h3>{{$project_count}}</h3>
                            <p>Projects</p>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box-dashboard">
                        <i class="fa fa-files-o fa-fw"></i>
                        <a href="{{route('quotation/list')}}" class="info">
                            <h3 class="quotation_count"></h3>
                            <p>Quotations</p>
                        </a>
                    </div>
                </div>
                <div class="col-md-3" style="display: none;">
                    <div class="box-dashboard">
                        <i class="fa fa-files-o  fa-fw"></i>
                        <a href="{{route('project/list')}}" class="info">
                            <h3>{{$order_count}}</h3>
                            <p>Orders</p>
                        </a>
                    </div>
                </div>
                @break
                {{--company--}}
                @case(2)
                <div class="col-md-3">
                    <div class="box-dashboard">
                        <i class="fa fa-users fa-fw"></i>
                        <a href="{{route('user/list')}}" class="info">
                            <h3>{{$user_count}}</h3>
                            <p>User</p>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box-dashboard">
                        <i class="fa fa-user fa-fw"></i>
                        <a href="{{route('contractor/list')}}" class="info">
                            <h3>{{$customer_count}}</h3>
                            <p>Customers</p>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box-dashboard">
                        <i class="fa fa-tasks fa-fw"></i>
                        <a href="{{route('project/list')}}" class="info">
                            <h3>{{$project_count}}</h3>
                            <p>Projects</p>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box-dashboard">
                        <i class="fa fa-files-o fa-fw"></i>
                        <a href="{{route('quotation/list')}}" class="info">
                            <h3 class="quotation_count"></h3>
                            <p>Quotations</p>
                        </a>
                    </div>
                </div>
                <div class="col-md-3" style="display: none;">
                    <div class="box-dashboard">
                        <i class="fa fa-files-o  fa-fw"></i>
                        <a href="{{route('project/list')}}" class="info">
                            <h3>{{$order_count}}</h3>
                            <p>Orders</p>
                        </a>
                    </div>
                </div>
                @break
                {{--user--}}
                @case(3)
                <div class="col-md-3">
                    <div class="box-dashboard">
                        <i class="fa fa-user fa-fw"></i>
                        <a href="{{route('contractor/list')}}" class="info">
                            <h3>{{$customer_count}}</h3>
                            <p>Customers</p>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box-dashboard">
                        <i class="fa fa-tasks fa-fw"></i>
                        <a href="{{route('project/list')}}" class="info">
                            <h3>{{$project_count}}</h3>
                            <p>Projects</p>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box-dashboard">
                        <i class="fa fa-files-o fa-fw"></i>
                        <a href="{{route('quotation/list')}}" class="info">
                            <h3 class="quotation_count"></h3>
                            <p>Quotations</p>
                        </a>
                    </div>
                </div>
                <div class="col-md-3" style="display: none;">
                    <div class="box-dashboard">
                        <i class="fa fa-files-o  fa-fw"></i>
                        <a href="{{route('project/list')}}" class="info">
                            <h3>{{$order_count}}</h3>
                            <p>Orders</p>
                        </a>
                    </div>
                </div>
                @break
                {{--architecture--}}
                @case(4)
                <div class="col-md-3">
                    <div class="box-dashboard">
                        <i class="fa fa-tasks fa-fw"></i>
                        <a href="{{route('project/list')}}" class="info">
                            <h3>{{$project_count}}</h3>
                            <p>Projects</p>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box-dashboard">
                        <i class="fa fa-tasks fa-fw"></i>
                        <a href="{{route('contractor/list')}}" class="info">
                            <h3>{{$customer_count}}</h3>
                            <p>Main Contractor</p>
                        </a>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="box-dashboard">
                        <i class="fa fa-files-o fa-fw"></i>
                        <a href="{{route('quotation/list')}}" class="info">
                            <h3 class="quotation_count"></h3>
                            <p>Quotations</p>
                        </a>
                    </div>
                </div>
                @break
                @case(5)
                <div class="col-md-3">
                    <div class="box-dashboard">
                        <i class="fa fa-tasks fa-fw"></i>
                        <a href="{{route('project/list')}}" class="info">
                            <h3>{{$project_count}}</h3>
                            <p>Projects</p>
                        </a>
                    </div>
                </div>
                
                <div class="col-md-3">
                    <div class="box-dashboard">
                        <i class="fa fa-files-o fa-fw"></i>
                        <a href="{{route('quotation/list')}}" class="info">
                            <h3 class="quotation_count"></h3>
                            <p>Quotations</p>
                        </a>
                    </div>
                </div>
                @break
                @default
                <span>No project available</span>
                @endswitch
           
        </div>



        <div class="tab-content">
            <div class="tab-pane tabs-animation fade active show" id="tab-content-0" role="tabpanel">
                <div class="row">
                    @if(in_array($authdata->UserType,array("1")))
                   
                    @endif

                  
                    @if(in_array($authdata->UserType,array("1", "2", "3")))
                    <div class="col-lg-8">
                        <div class="main-card mb-3 custom_card ">
                            <div class="card-header">Recent Registered Customers</div>
                            <div class="table-responsive overflow-auto height-400">
                                <table class="table table-striped text-left">
                                    <thead class="text-uppercase">
                                        <tr>
                                            <th scope="col">Name</th>
                                            <th scope="col">Email</th>
                                            <th scope="col">Mobile Number</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentCustomers as $value)
                                            <tr>
                                                <td>
                                                    {{$value['CstCompanyName']}}
                                                </td>
                                                <td>{{$value['CstCompanyEmail']}}</td>
                                                <td>{{$value['CstCompanyPhone']}}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    @endif
            
                @if(Auth::user()->UserType==3 || Auth::user()->UserType==2)
                {{--    <div class="col-lg-4">
                        <div class="custom_card">
                            <div class="card-header mb-2">Quotations Count By Status</div>
                            <div class="cripto-live overflow-auto height-400">
                                <ul>
                                    @foreach($quotation_count_status_wise as $val)                                   
                                        @if($val->QuotationStatus != '')                                  
                                            <li>
                                                {{$val->QuotationStatus}}
                                                <span><i class="fas fa-arrow-up"></i>{{$val->total}}</span>
                                            </li>
                                        @endif            
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div> --}}

                    <div class="col-lg-4">
                        <div class="custom_card">
                            <div class="card-header mb-2">Notification</div>
                            <div class="cripto-live overflow-auto height-400">
                                <div id="showNotification"></div>
                            </div>
                        </div>
                    </div>
                @endif

                    <div class="col-lg-12 mb-4">
                        <h3 class="qoute_heading">Projects</h3>
                        <div class="row projectList">
                          
                        </div>
                    </div>


                    <div class="col-lg-12 mb-4">
                        <h3 class="qoute_heading">Quotations</h3>
                        <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
                                        <input type="hidden" name="directory_ajax" id="directory_ajax" value="{{route('quotation/records')}}" /> 
                                        <div class="row results"></div>
                        {{-- <div class="row">
                            

                            @foreach($recentQuotations as $val)
                                @php
                                    if($val->QuotationStatus != ''){
                                        if($val->QuotationStatus == 'Open'){
                                            $quotation_status = '<strong class="QuotationStatus" style="background:#69e4a6;">'.$val->QuotationStatus.'</strong>';
                                        } 
                                        else if($val->QuotationStatus == 'Ordered'){
                                            $quotation_status = '<strong class="QuotationStatus" style="background: #47a91f;">'.$val->QuotationStatus.'</strong>';
                                        } else {
                                            $quotation_status = '<strong class="QuotationStatus">'.$val->QuotationStatus.'</strong>'; 
                                        }
                                    } else {
                                        $quotation_status = null;
                                    }
                                @endphp
                                <div class="col-sm-3 mb-3">
                                    <div class="QuotationBox">
                                        @if(Auth::user()->UserType=='2')
                                            <a href="{{url('quotation/generate/'.$val->QuotationId)}}" class="QuotationCode">{{$val->QuotationGenerationId}}</a>
                                        @else
                                            <a href="javascript:void(0);" class="QuotationCode">{{$val->QuotationGenerationId}}</a>
                                        @endif
                                        <div class="QuotationCompanyName">
                                            <b>{{$val->CompanyName!=''?$val->CompanyName:'-----------'}} {!! $quotation_status !!}</b>
                                        </div>
                                        <div class="QuotationStatusNumber">GBP 10,187.00</div>                                       
                                        <div class="QuotationListData">
                                            <b>Name</b>
                                            <span>{{$val->QuotationName!=''?$val->QuotationName:'-----------'}}</span>         
                                            <b>Project</b>
                                            <span>{{$val->ProjectName!=''?$val->ProjectName:'-----------'}}</span>
                                        </div>
                                        <div class="QuotationListNumber">
                                            <b>P.O. Number</b>
                                            <span>{{$val->PONumber!=''?$val->PONumber:'-----------'}}</span>
                                        </div>
                                        <div class="QuotationModifiedDate">
                                            <p>Last modified by Mark woods on 25/02/2021 11:07AM</p>   
                                        </div>  
                                        
                                        @if(Auth::user()->UserType=='2')
                                        <div class="filter_action">
                                            <label for="filter" class="quote_filter">
                                                <i class="fas fa-ellipsis-h"></i>
                                            </label>
                                            <ul class="QuotationMenu">
                                                <li>
                                                    <a href="{{url('quotation/request/'.$val->QuotationId)}}" target="_blank"><i class="fas fa-mouse-pointer"></i> Open</a>
                                                </li>
                                                <li><a href="#"><i class="far fa-copy"></i> Copy</a></li>
                                                <li><a href="#"><i class="fas fa-print"></i> Print</a></li>
                                                <li><a href="#"><i class="far fa-trash-alt"></i> Delete</a></li>
                                                <li><a href="#"><i class="fas fa-file-export"></i> Export</a></li>
                                            </ul>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            @endforeach
                        </div> --}}
                    </div>
                </div>
                
            </div>
        </div>

    </div>
</div>

<form action="{{route('deleteproject')}}" id="delSubmit" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="projectId" id="delId">
</form>
<form action="{{route('deactivateproject')}}" id="deactivateprojectSubmit" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="projectId" id="deactprojectId">
</form>
<form action="{{route('activateproject')}}" id="activateprojectSubmit" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="projectId" id="actprojectId">
</form>
@endsection
@section('js')
<script>
 $(document).on('click','.delproject',function(){
        var r = confirm("Are you sure! you wan't to delete it.");
        if (r == true) {
            let id = $(this).siblings('input').val();
            $('#delId').val(id);
            $('#delSubmit').submit();
        }
    })
    
    $(document).on('click','.deactivateproject',function(){
        var r = confirm("Are you sure! you wan't to Deactivate project.");
        if (r == true) {
            let id = $(this).siblings('input').val();
            $('#deactprojectId').val(id);
            $('#deactivateprojectSubmit').submit();
        }
    })
    $(document).on('click','.activateproject',function(){
        var r = confirm("Are you sure! you wan't to Activate project.");
        if (r == true) {
            let id = $(this).siblings('input').val();
            $('#actprojectId').val(id);
            $('#activateprojectSubmit').submit();
        }
    })



    let from = 0;
let page2 = 1;
let limit2 = 12;
function OnloadFilter(from,filters, dir,setPage = false) {  
    const url = "{{route('project/get-project-list')}}";
    const column = "project.created_at";
    const _token = "{{csrf_token()}}"; 
    const orders = null;
    $.ajax({
        'url': url,
        'type': 'post',
        data: {
            'url': url,
            'filters': filters,
            'column': column,
            'from': from,
            'limit': limit2,
            'dir': dir,
            'orders': orders,
            '_token': _token
        },
        success: function(msg) {
            console.log(msg)
            if(msg.st == "success"){
                $('.projectList').html(msg.html)
                if(setPage){
                    page2 = setPage;
                }
                pagination2(msg.total);
                $(".close").trigger("click");
            }else{
                // swal("Oops!", msg.txt, "error");
            }
        }
    });
}

$(document).ready(function() {
    var filters = [];
    var dir = "desc";
    OnloadFilter(from,filters, dir)
    var column = "quotation.created_at";
    requester(0, limit, [], [{
        column: column,
        dir: dir
    }]);
});
</script>
@endsection