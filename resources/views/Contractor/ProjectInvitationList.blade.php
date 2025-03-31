@extends("layouts.Master")
@section('css')
<style>
.modal-backdrop.show, .show.blockOverlay {
    opacity: 0;
}
.show {
    top: 10%;
}
</style>
@endsection
@section("main_section")
@if(session()->has('error'))
<style type="text/css">
    #useremail { border-color: red; }

</style>
@endif

<style>
    .filter_action>ul{
        min-width: 169px;top: 30px;left: 55px;
    }
    .filter_main>ul{
        min-width: 169px;top: 30px;left: 108px;
    }
</style>
<?php
  $loginUser = Auth::user();
  ?>
<div class="app-main__outer">
    <div class="box_holder">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-5">
                    <div class="filter_row">
                        <ul class="filter_list">
                            <li id="buttonfilter">
                                <label for="filter" class="quote_filter" style="width: 120px;margin-top: 4px;">
                                    <img src="{{url('/')}}/images/outline_filter.png" alt="filter" style="width: 20px;color: #a8abad;"> Filter By
                                </label>
                            </li>
                            <li>
                                <div class="filter_action filter_main" style="margin-top: -8px;">
                                    <label for="filter" class="quote_filter" style="width: 120px;">
                                        <i class="fas fa-sort-amount-down-alt"></i> Sort By
                                    </label>
                                    <ul class="QuotationMenu" style="visibility: hidden; opacity: 0; transform: translateX(-50%) scaleY(1);">
                                        <li onclick="applyFilters('set','desc')"><a
                                                href="javascript:void(0)">Descending</a></li>
                                        <li onclick="applyFilters('set','asc')"><a
                                                href="javascript:void(0)">Ascending</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div id="popover">
                        <div class="filter_box">
                            <section>
                                <form>
                                    <h4>Custom checkbox</h4>
                                    <div class="custom-radio">
                                        <div>
                                            <input autocomplete="off" checked class="radio-custom" id="radio-cust-8"
                                                type="radio" value="All" name="created_at">
                                            <label for="radio-cust-8" class="radio-custom-label">All</label>
                                        </div>
                                    </div>
                                    <div class="custom-radio">
                                        <div>
                                            <input autocomplete="off" type="radio" class="radio-custom"
                                                id="radio-cust-9" value="Today" name="created_at">
                                            <label for="radio-cust-9" class="radio-custom-label">Today</label>
                                        </div>
                                    </div>
                                    <div class="custom-radio">
                                        <div>
                                            <input autocomplete="off" type="radio" class="radio-custom"
                                                id="radio-cust-11" value="ThisWeek" name="created_at">
                                            <label for="radio-cust-11" class="radio-custom-label">This Week</label>
                                        </div>
                                    </div>

                                    <div class="custom-radio">
                                        <input autocomplete="off" type="radio" class="radio-custom" id="radio-cust-12"
                                            value="ThisMonth" name="created_at">
                                        <label for="radio-cust-12" class="radio-custom-label">This Month</label>
                                    </div>
                                </form>
                                <div class="filter_footer">
                                    <button class="cursor-pointer" onclick="applyFilters('reset')">Reset</button>
                                    <button class="cursor-pointer" onclick="applyFilters()">Apply</button>
                                </div>
                            </section>
                        </div>
                    </div>
                </div>
                <div class="col-sm-7 search-box">
                    <div class="pull-right d-flex" style="">
                        <form action="#">
                            <div class="search_field">
                                <input type="text" id="projectSearch" name="search" placeholder="Search..." required="">
                                <i class="fa fa-search"></i>
                            </div>
                        </form>
                        @if($loginUser->UserType=='2' || $loginUser->UserType=='3')
                        <a href="{{route('project/create')}}" class="new_quote">Add Project</a>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>
    @if(session()->has('success'))
    <div class="alert alert-success alert-dismissible">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('success') }}
    </div>
    @endif
    <div class="app-main__inner">
        <div class="tab-content">
            <div class="row">
                <div class="col-sm-12 row_heading">
                    <h4>Overview</h4>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="overview_status">
                        <div class="row mb-3">
                            <div class="col-6 status_value">
                                <p>On Hold</p>
                                <h6>12</h6>
                            </div>
                            <div class="col-6">
                                <div class="status_icon pull-right"><i class="fa fa-line-chart"></i></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="status_progress">+3.48%</p>
                            </div>
                            <div class="col-6">
                                <p class="status_statment">Since lst month</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="overview_status">
                        <div class="row mb-3">
                            <div class="col-6 status_value">
                                <p>Processing</p>
                                <h6>7</h6>
                            </div>
                            <div class="col-6">
                                <div class="status_icon pull-right status-danger"><i class="fa fa-level-down"></i></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="status_progress text-danger">+3.48%</p>
                            </div>
                            <div class="col-6">
                                <p class="status_statment">Since lst month</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="overview_status">
                        <div class="row mb-3">
                            <div class="col-6 status_value">
                                <p>On Hold</p>
                                <h6>12</h6>
                            </div>
                            <div class="col-6">
                                <div class="status_icon pull-right status-info"><i class="fa fa-bar-chart"></i></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="status_progress">+3.48%</p>
                            </div>
                            <div class="col-6">
                                <p class="status_statment">Since lst month</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="overview_status">
                        <div class="row mb-3">
                            <div class="col-6 status_value">
                                <p>Revenue</p>
                                <h6>12025</h6>
                            </div>
                            <div class="col-6">
                                <div class="status_icon pull-right status-purple"><i class="fa fa-gbp"></i></div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                <p class="status_progress">+3.48%</p>
                            </div>
                            <div class="col-6">
                                <p class="status_statment">Since lst month</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="app-main__inner">
        <div class="tab-content">
            <div class="row projectList">

            </div>
            <ul class="pagination pagination-sm table_page_ul"></ul>

        </div>
    </div>

</div>

<form action="{{route('project/invitation/status')}}" id="acceptSubmit" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="hidden" name="choice" value="accepted">
    <input type="hidden" name="projectId" id="projectId1">
    <div class="modal fade" tabindex="-1" role="dialog" id="form_accept_modal" data-backdrop="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Accept project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="msform_accept_body" >

            </div>
            <div class="modal-footer">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" data-dismiss="modal" class="btn btn-info">Cancel</button>
                    <button type="submit" class="btn btn-success"> Confirm </button>
                </div>
            </div>
            </div>
        </div>
    </div>

</form>
<form action="{{route('project/invitation/status')}}" id="rejectSubmit" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input type="hidden" name="choice" value="rejected">
    <input type="hidden" name="projectId" id="projectId2">
    <div class="modal fade" tabindex="-1" role="dialog" id="form_reject_modal" data-backdrop="false">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject project</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body" id="msform_reject_body" >

            </div>
            <div class="modal-footer">
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="button" data-dismiss="modal" class="btn btn-info">Cancel</button>
                    <button type="submit" class="btn btn-success"> Confirm </button>
                </div>
            </div>
            </div>
        </div>
    </div>
</form>
<!-- <form action="{{route('activateproject')}}" id="activateprojectSubmit" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="projectId" id="actprojectId">
</form> -->
@endsection


@section('js')

<script>
    $(document).on('click','.acceptproject',function(){
        let id = $(this).siblings('input').val();
        $('#projectId1').val(id);
        // $('#delSubmit').submit();

        // Build form here
        var content = "";
        element = document.querySelector('#msform_accept_body');
        container = $("#msform_accept_body");
        container.empty();
        div = document.createElement('div');
        content += `<div class="form-group">
                        <label for="message">Message</label>
                        <input name="message" type="text" class="form-control" placeholder="Enter a comment">
                    </div><br>
                    <div class="projectFiles">
                        <label for="uploadfile"> Upload Document </label>
                        <div class="input-group mb-2">
                            <input type="file" name="document" class="form-control">
                        </div>
                    </div>`;
        div.innerHTML = content;
        element.appendChild(div);
        // show now the modal
        $('#form_accept_modal').modal('show');
    })

    $(document).on('click','.rejectproject',function(){
        let id = $(this).siblings('input').val();
        $('#projectId2').val(id);

        // Build form here
        var content = "";
        element = document.querySelector('#msform_reject_body');
        container = $("#msform_reject_body");
        container.empty();
        div = document.createElement('div');
        content += `<div class="form-group">
                        <label for="message">Message<span class="text-danger">*</span></label>
                        <input name="message" type="text" class="form-control" placeholder="Enter a comment">
                    </div><br>
                    <div class="projectFiles">
                        <label for="uploadfile"> Upload Document </label>
                        <div class="input-group mb-2">
                            <input type="file" name="document" class="form-control">
                        </div>
                    </div>`;
        div.innerHTML = content;
        element.appendChild(div);
        // show now the modal
        $('#form_reject_modal').modal('show');
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
    const url = "{{route('project/invitation/records')}}";
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
});



</script>




<script>
function applyFilters(action = "", orderby = "desc") {
    filters = [];
    dir = orderby;

    //            var filterStatus = $('input[name="filterStatus"]:checked').val();
    var created_at = $('input[name="created_at"]:checked').val();

    //            if(filterStatus !="" && filterStatus != "All"){
    //
    //                filters.push(["quotation.QuotationStatus", "=",  filterStatus]);
    //            }


    if (created_at != "" && created_at != "All") {

        if (created_at == "Today") {

            var date = new Date();

            var dd = String(date.getDate()).padStart(2, '0');
            var mm = String(date.getMonth() + 1).padStart(2, '0');
            var yyyy = date.getFullYear();

            var today = yyyy + '-' + mm + '-' + dd;

            filters.push(["project.created_at", ">", today]);



        } else if (created_at == "ThisWeek") {


            var today = new Date();

            var date = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000);
            var dd = String(date.getDate()).padStart(2, '0');
            var mm = String(date.getMonth() + 1).padStart(2, '0');
            var yyyy = date.getFullYear();

            var ThisWeek = yyyy + '-' + mm + '-' + dd;

            filters.push(["project.created_at", ">", ThisWeek]);

        } else if (created_at == "ThisMonth") {


            var today = new Date();

            var date = new Date(today.getTime() - 30 * 24 * 60 * 60 * 1000);
            var dd = String(date.getDate()).padStart(2, '0');
            var mm = String(date.getMonth() + 1).padStart(2, '0');
            var yyyy = date.getFullYear();

            var ThisMonth = yyyy + '-' + mm + '-' + dd;

            filters.push(["project.created_at", ">", ThisMonth]);
        }
    }


    if (action == "reset") {
        OnloadFilter(from,filters, dir)
        // requester(".projectList", url, "{{csrf_token()}}", 0, 0,[],[{ column : column, dir : dir }], true);

    } else {
        OnloadFilter(from,filters, dir)
        // requester(".projectList", url, "{{csrf_token()}}", 0, 0, filters,[{ column : column, dir : dir }], true);
    }

    //$(".popup-parent").modal("toggle");

    $(".close").trigger("click");
    $('#popover').removeClass('active')
}
$(document).on('keyup','#projectSearch',function(e){
    e.preventDefault();
    const projectSearch = $(this).val();
    filters = [];
    dir = "desc";
    filters.push(["project.ProjectName","LIKE", "%"+projectSearch+"%"]);
    OnloadFilter(from,filters, dir)
})


</script>
<!-- <script src="https://code.jquery.com/jquery-git.js"></script> -->

@endsection
