@extends("layouts.Master")

@section("css")
<link rel="stylesheet" href="//unpkg.com/leaflet@1.8.0/dist/leaflet.css" integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ==" crossorigin="" />
{{-- <link href="{{asset('css/leaflet.css')}}" rel="stylesheet"> --}}
@endsection

@section("main_section")
<style>
    .input-icons i {
    position: absolute;
    right: 0;
    top: 27px;
}
</style>
<!-- Pop-up box -->
    <div class="popup-parent" style="z-index: 555;background:">
        <div class="filter_popup">
            <div class="row">
                <div class="col-sm-12 mb-2">
                    <ul class="filter_items">
                        <h6>Quotation Status</h6>
                        <form>
                            <li>
                                <label class="popup_container">All <input autocomplete="off" checked type="radio"
                                        value="All" name="filterStatus"><span class="checkmark"></span>
                                </label>
                            </li>

                            <li>
                                <label class="popup_container">Open <input autocomplete="off" type="radio" value="Open"
                                        name="filterStatus"><span class="checkmark"></span>
                                </label>
                            </li>
                            <li>
                                <label class="popup_container">Ordered <input autocomplete="off" type="radio"
                                        value="Ordered" name="filterStatus"><span class="checkmark"></span>
                                </label>
                            </li>

                            <li>
                                <label class="popup_container">Closed <input autocomplete="off" type="radio" value="Closed"
                                        name="filterStatus"><span class="checkmark"></span>
                                </label>
                            </li>
                            <li>
                                <label class="popup_container">Lost <input autocomplete="off" type="radio" value="Lost"
                                        name="filterStatus"><span class="checkmark"></span>
                                </label>
                            </li>
                            <li>
                                <label class="popup_container">Abandoned <input autocomplete="off" type="radio"
                                        value="Abandoned" name="filterStatus"><span class="checkmark"></span>
                                </label>
                            </li>
                            <li>
                                <label class="popup_container">Expired <input autocomplete="off" type="radio"
                                        value="Expired" name="filterStatus"><span class="checkmark"></span>
                                </label>
                            </li>
                        </form>
                    </ul>
                </div>

                <div class="col-sm-12 mb-2">
                    <ul class="filter_items">
                        <h6>Date Created</h6>
                        <form>
                            <li>
                                <label class="popup_container">All <input autocomplete="off" checked type="radio"
                                        value="All" name="created_at"><span class="checkmark"></span>
                                </label>
                            </li>

                            <li>
                                <label class="popup_container">Today <input autocomplete="off" type="radio" value="Today"
                                        name="created_at"><span class="checkmark"></span>
                                </label>
                            </li>
                            <li>
                                <label class="popup_container">This Week <input autocomplete="off" type="radio"
                                        value="ThisWeek" name="created_at"><span class="checkmark"></span>
                                </label>
                            </li>
                            <li>
                                <label class="popup_container">This Month <input autocomplete="off" type="radio"
                                        value="ThisMonth" name="created_at"><span class="checkmark"></span>
                                </label>
                            </li>
                        </form>
                    </ul>
                </div>
            </div>
            <button class="close" draggable="true">&times;</button>
            <div class="mt-3">
                <button class="btn btn-warning" onclick="applyFilters('reset')">Reset</button>
                <button class="btn btn-success" onclick="applyFilters()">Apply</button>
            </div>
        </div>
    </div>
<div class="app-main__outer">
    <div class="app-main__inner">
        <div class="mb-3">
            <div class="card-body">
                @if(session()->has('status'))
                    <input type="hidden" id="tab_redirect" value="{{ session()->get('message') }}">
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
                <section>
                    <div class="project_info">
                        <div class="row">
                            <div class="col-lg-2 col-md-4 col-sm-6">
                                <div class="company_logo">
                                    @if($data[0]->ProjectImage != '')
                                    <img src="{{url('uploads/Project')}}/{{$data[0]->ProjectImage}}">
                                    @else
                                    <img src="{{url('CompanyLogo/default-image.jpg')}}">
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-10 col-md-8 col-sm-6">
                                <div class="project_details">
                                    <h4>{{$data[0]->ProjectName}}</h4>
                                    <p><i class="fa fa-map-marker location" aria-hidden="true"></i>
                                        @if($data[0]->customerId != '')
                                            {{ CustomerCompanyName($data[0]->customerId) }}
                                        @else
                                            {{'-----------' }}
                                        @endif
                                    </p>
                                    <div class="row">
                                        <div class="col-sm-6">
                                        <p>{{$data[0]->AddressLine1}}</p>
                                        </div>
                                        <div class="col-sm-6">
                                            <div class="row">
                                                <div class="col-sm-8">
                                                   <p style="font-size:14px"> Architect - {{$architect_name}}</p>
                                                </div>
                                                <div class="col-sm-4">
                                                <a  class="btn btn-primary text-white" data-toggle="modal" data-target="#architectSubmit">
                                                    Select
                                                </a>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p style="font-size:14px">MainContractor - {{$main_contractor_name}}</p>
                                                </div>
                                                <div class="col-sm-4">
                                                <a  class="btn btn-primary text-white" data-toggle="modal" data-target="#maincontractorSubmit">
                                                    Select
                                                </a>
                                                </div>
                                                <div class="col-sm-8">
                                                    <p style="font-size:14px">Company - {{$company_name}}</p>
                                                </div>
                                                <div class="col-sm-4">
                                                <a class="btn btn-primary text-white" data-toggle="modal" data-target="#companySubmit">
                                                    Select
                                                </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @if(Auth::user()->UserType==1)
                                    <a hidden href="{{route('addironmongery', [ $projectId ] )}}" class="project_edit " style="right: 429px;">
                                        <i class="fa fa-edit" aria-hidden="true"></i> Add Ironmongery Set
                                    </a>

                                    <a href="{{url('project/quotation/'.Request::segment(3))}}" class="project_edit project_edit1">
                                        <i class="fa fa-edit" aria-hidden="true"></i> Add Quotation
                                    </a>
                                    <a href="{{route('projectNewQuotation', [ $projectId ] )}}/{{$data[0]->customerId}}" class="project_edit " style="right: 264px;">
                                        <i class="fa fa-edit" aria-hidden="true"></i> New Quotation
                                    </a>
                                    <a href="{{url('project/update/'.$data[0]->GeneratedKey)}}" class="project_edit">
                                        <i class="fa fa-edit" aria-hidden="true"></i> Edit
                                    </a>
                                    @endif



                                    <!-- <a href="{{url('quotation/generate/0/0/')}}" class="project_edit project_edit1"> -->

                                    @if(Auth::user()->UserType==2)

                                    <a href="{{route('addironmongery', [ $projectId ] )}}" hidden class="project_edit " style="right: 429px;">
                                        <i class="fa fa-edit" aria-hidden="true"></i> Add Ironmongery Set
                                    </a>

                                    <a href="{{url('project/quotation/'.Request::segment(3))}}" class="project_edit project_edit1">
                                        <i class="fa fa-edit" aria-hidden="true"></i> Add Quotation
                                    </a>
                                    <a href="{{route('projectNewQuotation', [ $projectId ] )}}/{{$data[0]->customerId}}" class="project_edit " style="right: 264px;">
                                        <i class="fa fa-edit" aria-hidden="true"></i> New Quotation
                                    </a>
                                    <a href="{{url('project/update/'.$data[0]->GeneratedKey)}}" class="project_edit">
                                        <i class="fa fa-edit" aria-hidden="true"></i> Edit
                                    </a>

                                    @endif




                                    @if(Auth::user()->UserType==3)
                                 <a hidden href="{{route('addironmongery', [ $projectId ] )}}" class="project_edit " style="right: 429px;">
                                        <i class="fa fa-edit" aria-hidden="true"></i> Add Ironmongery Set
                                    </a>

                                    <a href="{{url('project/quotation/'.Request::segment(3))}}" class="project_edit project_edit1">
                                        <i class="fa fa-edit" aria-hidden="true"></i> Add Quotation
                                    </a>
                                    <a href="{{route('projectNewQuotation', [ $projectId ] )}}/{{$data[0]->customerId}}" class="project_edit " style="right: 264px;">
                                        <i class="fa fa-edit" aria-hidden="true"></i> New Quotation
                                    </a>
                                    <a href="{{url('project/update/'.$data[0]->GeneratedKey)}}" class="project_edit">
                                        <i class="fa fa-edit" aria-hidden="true"></i> Edit
                                    </a>
                                    @endif









                                    @if(Auth::user()->UserType==4)
                                    <a hidden href="{{route('addironmongery', [ $projectId ] )}}" class="project_edit " style="right: 429px;">
                                        <i class="fa fa-edit" aria-hidden="true"></i> Add Ironmongery Set
                                    </a>
                                    @if($quotation_limit_for_arch==0)
                                    <a href="{{route('projectNewQuotation', [ $projectId ] )}}/{{$data[0]->customerId}}" class="project_edit " style="right: 264px;">
                                        <i class="fa fa-edit" aria-hidden="true"></i> Door Schedule
                                    </a>
                                    @endif

                                    <a href="{{url('project/update/'.$data[0]->GeneratedKey)}}" class="project_edit">
                                        <i class="fa fa-edit" aria-hidden="true"></i> Edit
                                    </a>
                                    @endif






                                    @if(Auth::user()->UserType==5)
                                    <a href="{{url('project/update/'.$data[0]->GeneratedKey)}}" class="project_edit">
                                        <i class="fa fa-edit" aria-hidden="true"></i> Edit
                                    </a>
                                    @endif







                                </div>
                            </div>


                        </div>
                    </div>
                </section>
                <div class="container project_div"></div>
                <div class="projectNew"></div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="projectId" value="{{$projectId}}">

@endsection


<!--select company list-->
    <form action="{{route('change-assign-project')}}" method="post">
            {{ csrf_field() }}
            <input type="hidden" name="CompanyId" id="CompanyId">
            <input type="hidden" name="ProjectId" value="{{$projectId}}">
            <div class="modal fade" tabindex="-1" role="dialog" id="companySubmit" data-backdrop="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Assign project to Company</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="msform_assign_body" >
                    @foreach($company_list as $value)
                        <div class="row">
                            <div class="col-sm-10">
                                <p>{{$value->CompanyName}} - {{$value->UserEmail}}</p>
                            </div>
                            <div class="col-sm-2 text-right">
                            <button onclick="set_company_id('{{$value->id}}')" class="btn btn-success">Assign</button>
                            </div>
                        </div>
                    @endforeach
                    </div>
                    </div>
                </div>
            </div>
        </form>

<!--select architect list-->
<form action="{{route('change-assign-project')}}" method="post" >
            {{ csrf_field() }}
            <input type="hidden" name="ArchitectId" id="ArchitectId">
            <input type="hidden" name="ProjectId" value="{{$projectId}}">
            <div class="modal fade" tabindex="-1" role="dialog" id="architectSubmit" data-backdrop="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Assign project to Architect</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="msform_assign_body" >
                    @foreach($architect_list as $value)
                        <div class="row">
                            <div class="col-sm-10">
                                <p>{{$value->ArcCompanyName}} - {{$value->UserEmail}}</p>
                            </div>
                            <div class="col-sm-2 text-right">
                            <button onclick="set_architect_id('{{$value->id}}')" class="btn btn-success">Assign</button>
                            </div>
                        </div>
                    @endforeach
                    </div>
                    </div>
                </div>
            </div>
        </form>


<!--select main contractor list-->
<form action="{{route('change-assign-project')}}" method="post" >
            {{ csrf_field() }}
            <input type="hidden" name="MainContractorId" id="MainContractorId">
            <input type="hidden" name="ProjectId" value="{{$projectId}}">
            <div class="modal fade" tabindex="-1" role="dialog" id="maincontractorSubmit" data-backdrop="false">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Assign project to MainContractor</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body" id="msform_assign_body" >
                    @foreach($main_contractor_list as $value)

                        <div class="row">
                            <div class="col-sm-10">
                                <p style>{{$value->CstCompanyName}} - {{$value->UserEmail}}</p>
                            </div>
                            <div class="col-sm-2 text-right">
                            <button onclick="set_maincontractor_id('{{$value->id}}');" class="btn btn-success">Assign</button>
                            </div>
                        </div>
                    @endforeach
                    </div>
                    </div>
                </div>
            </div>
        </form>
        <input type="hidden" id="qid" value="{{ $qid }}">
        <input type="hidden" id="vid" value="{{ $vid }}">
@section("script_section")
<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click','.DeleteProjectFile',function(e){
        e.preventDefault();
        var r = confirm("Are you sure! you wan't to delete it.");
        if (r == true) {
            const projectFileID = $(this).siblings('.projectFileID').val();
            const filename = $(this).siblings('.filename').val();
            const self = $(this);
            $.ajax({
                type: "POST",
                url: "{{route('deleteProjectFile')}}",
                    data: { projectFileID: projectFileID, filename: filename },
                    dataType: "json",
                    success: function(data) { console.log(data);
                        $(self).parent('li').parent('ul').parent('div').parent('div').remove();
                    }
            });
        }
    });
    var markAsRead = 0;
function OnloadFilter(filters, dir) {
    $('.loader').empty().css({
        'display': 'block'
    });
    const url = "{{route('project/quotation-list-ajax')}}";
    const column = "quotation.created_at";
    const _token = "{{csrf_token()}}";
    const from = 0;
    const limit = 0;
    const projectIdValue = $('#projectId').val();
    $.ajax({
        'url': url,
        'type': 'post',
        data: {
            'url': url,
            'filters': filters,
            'column': column,
            'from': from,
            'limit': limit,
            'dir': dir,
            'projectId': projectIdValue,
            '_token': _token
        },
        success: function(msg) {
            $('.projectNew').html(msg)
            // $(".close").trigger("click");
            $('.loader').empty().css({'display': 'none'});
            let url = new URL(window.location.href);

            let checkNotification = url.searchParams.get('from');
            if(checkNotification == 'notification'){
                setTimeout(() => {
                    $('#team_board_tab').click();
                }, 1000);
                url.searchParams.delete('from');
                history.replaceState({}, '', url.href);
            }

            if(document.getElementById('team_board_tab')){
                document.getElementById('team_board_tab').addEventListener('click', ()=>{
                    if(markAsRead==1){
                        return false;
                    }
                    // markAsRead = 1;
                    $.ajax({
                        'url': "{{route('notification/markRead')}}",
                        'type': 'post',
                        data: {
                            'p_id': $('#projectId').val(),
                        },
                        success: function(msg) {

                        }
                    });
                })
            }

        }
    });
}
$(document).ready(function() {
    var filters = [];
    filters.push(["project.GeneratedKey", "=", $('input[name="url_segment3"]').val()]);
    var dir = "desc";
    OnloadFilter(filters, dir)
});


function set_company_id(id){
    $("#CompanyId").val(id)
}
function set_architect_id(id){
    $("#ArchitectId").val(id)
}
function set_maincontractor_id(id){
    $("#MainContractorId").val(id)
}






$(".form-control").attr("disabled", true);
const tabs = document.querySelectorAll('[data-tab-target]');
const tabContents = document.querySelectorAll('[data-tab-content]');


tabs.forEach(tab => {
    tab.addEventListener('click', () => {
        const target = document.querySelector(tab.dataset.tabTarget);
        tabContents.forEach(tabContent => {
            tabContent.style.display = "none";
            tabContent.style.transform = "translateY(30px)";
            tabContent.style.opacity = "0";
        })
        target.style.display = "block";
        setTimeout(() => {
            target.style.opacity = "1";
            target.style.transform = "translateY(20px)";
        }, 100)
    })
})
</script>




@endsection



@section('js')

<script>
$(".quote_filter").click(function() {

    if ($(this).next("ul").css("visibility") == "hidden") {

        $(this).next("ul").css("visibility", "visible");
        $(this).next("ul").css("opacity", 1);
        $(this).next("ul").css("transform", "translateX(-50%) scaleY(1)");


    } else {
        $(this).next("ul").css("visibility", "hidden");
        $(this).next("ul").css("opacity", 0);
        $(this).next("ul").css("transform", "translateX(-50%) scaleY(1)");
    }
    //        alert($(".quote_filter").next("ul").css("visibility"));

});
</script>

<script>
let popupParent = document.querySelector(".popup-parent");
let btn = document.getElementById("btn");
let btnClose = document.querySelector(".close");
let mainSection = document.querySelector(".mainSection");


btn.addEventListener("click", showPopup);

function showPopup() {
    popupParent.style.display = "block";
}

btnClose.addEventListener("click", closePopup);

function closePopup() {
    popupParent.style.display = "none";
}
popupParent.addEventListener("click", closeOutPopup);

function closeOutPopup(o) {
    if (o.target.className == "popup-parent") {
        popupParent.style.display = "none";
    }
}
</script>


<script>
function applyFilters(action = "", orderby = "desc") {

    filters = [];
    filters.push(["project.GeneratedKey", "=", $('input[name="url_segment3"]').val()]);
    dir = orderby;

    var filterStatus = $('input[name="filterStatus"]:checked').val();
    var created_at = $('input[name="created_at"]:checked').val();

    if (filterStatus != "" && filterStatus != "All") {

        filters.push(["quotation.QuotationStatus", "=", filterStatus]);
    }

    if (created_at != "" && created_at != "All") {

        if (created_at == "Today") {

            var date = new Date();

            var dd = String(date.getDate()).padStart(2, '0');
            var mm = String(date.getMonth() + 1).padStart(2, '0');
            var yyyy = date.getFullYear();

            var today = yyyy + '-' + mm + '-' + dd;

            filters.push(["quotation.created_at", ">", today]);


        } else if (created_at == "ThisWeek") {


            var today = new Date();

            var date = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
            var dd = String(date.getDate()).padStart(2, '0');
            var mm = String(date.getMonth() + 1).padStart(2, '0');
            var yyyy = date.getFullYear();

            var ThisWeek = yyyy + '-' + mm + '-' + dd;

            filters.push(["quotation.created_at", ">", ThisWeek]);

        } else if (created_at == "ThisMonth") {


            var today = new Date();

            var date = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);
            var dd = String(date.getDate()).padStart(2, '0');
            var mm = String(date.getMonth() + 1).padStart(2, '0');
            var yyyy = date.getFullYear();

            var ThisMonth = yyyy + '-' + mm + '-' + dd;

            filters.push(["quotation.created_at", ">", ThisMonth]);
        }
    }

    // if (action == "reset") {
    //     OnloadFilter(filters, dir)
    //     // requester(".project_div", url, "{{csrf_token()}}", 0, 0,[],[{ column : column, dir : dir }], true);

    // } else {
    //     OnloadFilter(filters, dir)
    //     // requester(".project_div", url, "{{csrf_token()}}", 0, 0, filters,[{ column : column, dir : dir }], true);
    // }

    //$(".popup-parent").modal("toggle");
    // $(".close").trigger("click");

}



</script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" crossorigin="anonymous"></script>
<script src="//unpkg.com/leaflet@1.8.0/dist/leaflet.js" integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ==" crossorigin="" ></script>

{{-- <script src="{{asset('js/leaflet.js')}}"></script> --}}

@endsection


