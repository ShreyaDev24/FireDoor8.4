@extends("layouts.Master")
@section('css')

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

                                    <div class="custom-radio">
                                        <input autocomplete="off" type="radio" class="radio-custom" id="radio-cust-13"
                                            value="ThisThreeMonth" name="created_at">
                                        <label for="radio-cust-13" class="radio-custom-label">Last Three Month</label>
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
                                <input type="text" id="projectSearch" name="search" placeholder="Search..." required="" onkeypress="return event.keyCode != 13;">
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
                <div class="col-sm-10 row_heading">
                    <h4>Overview</h4>
                </div>
                <div class="col-sm-1">
                    <div class="row bg-white listTypeBtn" style="border:2px solid">
                        <div class="col-sm-6 mouse-pointer btn-list listActive" onclick="listType('dataListType')">
                        <span style="display: none;" id="checkList"><svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M480 128c0 8.188-3.125 16.38-9.375 22.62l-256 256C208.4 412.9 200.2 416 192 416s-16.38-3.125-22.62-9.375l-128-128C35.13 272.4 32 264.2 32 256c0-18.28 14.95-32 32-32c8.188 0 16.38 3.125 22.62 9.375L192 338.8l233.4-233.4C431.6 99.13 439.8 96 448 96C465.1 96 480 109.7 480 128z"/></svg></span>

                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                            </svg> </div>


                        <div class="col-sm-6 mouse-pointer btn-list BoxActive" onclick="listType('dataBoxType')">
                        <span style="display: none;" id="checkBox"><svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M480 128c0 8.188-3.125 16.38-9.375 22.62l-256 256C208.4 412.9 200.2 416 192 416s-16.38-3.125-22.62-9.375l-128-128C35.13 272.4 32 264.2 32 256c0-18.28 14.95-32 32-32c8.188 0 16.38 3.125 22.62 9.375L192 338.8l233.4-233.4C431.6 99.13 439.8 96 448 96C465.1 96 480 109.7 480 128z"/></svg></span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ui-checks-grid" viewBox="0 0 16 16">
                                <path d="M2 10h3a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1m9-9h3a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-3a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1m0 9a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1zm0-10a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h3a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM2 9a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h3a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2zm7 2a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-3a2 2 0 0 1-2-2zM0 2a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm5.354.854a.5.5 0 1 0-.708-.708L3 3.793l-.646-.647a.5.5 0 1 0-.708.708l1 1a.5.5 0 0 0 .708 0z" />
                            </svg> </div>
                    </div>
                </div>
                <div class="col-sm-1">
                    <a href="{{url('project/get-project-list-allexport')}}" class="btn btn-primary float-right">Export</a>
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
                        {{-- <div class="row">
                            <div class="col-6">
                                <p class="status_progress">+3.48%</p>
                            </div>
                            <div class="col-6">
                                <p class="status_statment">Since lst month</p>
                            </div>
                        </div> --}}
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
                        {{-- <div class="row">
                            <div class="col-6">
                                <p class="status_progress text-danger">+3.48%</p>
                            </div>
                            <div class="col-6">
                                <p class="status_statment">Since lst month</p>
                            </div>
                        </div> --}}
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
                        {{-- <div class="row">
                            <div class="col-6">
                                <p class="status_progress">+3.48%</p>
                            </div>
                            <div class="col-6">
                                <p class="status_statment">Since lst month</p>
                            </div>
                        </div> --}}
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
                        {{-- <div class="row">
                            <div class="col-6">
                                <p class="status_progress">+3.48%</p>
                            </div>
                            <div class="col-6">
                                <p class="status_statment">Since lst month</p>
                            </div>
                        </div> --}}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="app-main__inner tableParentDiv">

        <div class="tab-content">
            <div class="row projectList">

            </div>
            <ul class="pagination pagination-sm table_page_ul"></ul>

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

$(document).ready(function() {
        $('#checkBox').css("display", "");
        $('.BoxActive').addClass('btnActive');
    })

    function listType(type = 'box', from=0, limit=12,dir='desc',setPage = false) {
        if (type == '') {
            alert('please select list type')
            return false;
        }

        if (type == 'dataListType') {
            $('#checkList').css("display", "");
            $('#checkBox').css("display", "none");
            $('.BoxActive').removeClass('btnActive');
            $('.listActive').addClass('btnActive');
        } else {
            $('#checkList').css("display", "none");
            $('#checkBox').css("display", "");
            $('.listActive').removeClass('btnActive');
            $('.BoxActive').addClass('btnActive');
        }
        $('.loader').empty().css({
            'display': 'block'
        });
        let baseurl = "{{route('project/get-project-list')}}";
        $.ajax({
            url: baseurl,
            method: "POST",
            dataType: 'json',
            data: {
                url: baseurl,
                from: from,
                limit: limit,
                orders: null,
                _token: "{{ csrf_token() }}",
                listType: type,
                isStatus: 11,
               column: 'project.created_at',
               dir : dir
            },

            success: function(data) {
                if (data.st == "success") {
                    if (type == 'dataListType') {
                        $('.projectList').empty().append(data.html);

                        $('#dataListType').dataTable();
                        $(".pagination.pagination-sm.table_page_ul").css("display", "none");
                    } else {

                        $('.projectList').empty().html(data.html)
                if(setPage){
                    page2 = setPage;
                }
                pagination2(data.total);
                $(".close").trigger("click");

                        // if (doEmpty) {
                        //     $('.results').empty().append(data.html);
                        //     $(".quotation_count").empty().append(data.total);
                        // } else {
                        //     $('.results').empty().append(data.html);
                        //     $(".quotation_count").empty().append(data.total);
                        // }
                        // fromData = (parseInt(fromData) + parseInt(limit));
                        // if (setPage) {
                        //     page = setPage;
                        // }
                        // pagination(data.total);
                        $(".pagination.pagination-sm.table_page_ul").css("display", "");
                    }

                    $('.loader').empty().css({
                        'display': 'none'
                    });
                } else {
                    swal("Oops!", data.txt, "error");
                }
            },
            error: function(data) {
                swal("Oops!", "Something went wrong. Please try again.", "error");
            }

        });
    }

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
    $('.loader').empty().css({
        'display': 'block'
    });
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
                $('.loader').empty().css({'display': 'none'});
            }   else if(msg.st == "error"){
                if(msg.html == 'Data not found.'){
                    $('.projectList').html("<p class='text-center'>No projects found.</p>");
                    $(".close").trigger("click");
                    $('.loader').empty().css({'display': 'none'});
                    pagination2(msg.total);
                }
            }
            else{
                $('.loader').empty().css({'display': 'none'});
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
        } else if (created_at == "ThisThreeMonth") {
            var today = new Date();

            // Subtract 3 months from today
            var date = new Date();
            date.setMonth(today.getMonth() - 3);

            var dd = String(date.getDate()).padStart(2, '0');
            var mm = String(date.getMonth() + 1).padStart(2, '0');
            var yyyy = date.getFullYear();

            var ThisThreeMonth = yyyy + '-' + mm + '-' + dd;

            filters.push(["project.created_at", ">", ThisThreeMonth]);
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
<script src="https://code.jquery.com/jquery-git.js"></script>

@endsection
