@extends("layouts.Master")
@section('css')
@endsection
@section("main_section")
@if(session()->has('error'))
@endif

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
                                        <li onclick="applyFilters('set','desc')"><a href="javascript:void(0)">Descending</a></li>
                                        <li onclick="applyFilters('set','asc')"><a href="javascript:void(0)">Ascending</a></li>
                                    </ul>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="col-sm-7 search-box">
                    <div class="pull-right d-flex" style="">
                        <form action="#">
                            <div class="search_field">
                                <input type="text" id="QuotationSearch" name="search" placeholder="Search..." required="" onkeypress="return event.keyCode != 13;">
                                <i class="fa fa-search"></i>
                            </div>
                        </form>
                        @if($loginUser->UserType=='2' || $loginUser->UserType=='3')
                        {{-- <a href="{{url('quotation/generate/0/0')}}" class="new_quote">New Quote</a> --}}
                        @endif
                    </div>
                </div>
            </div>
            <div id="popover">
                <div class="filter_box">
                    <section>
                        <form>
                            <h4>Custom checkbox</h4>
                            <div class="custom-radio">
                                <div>
                                    <input autocomplete="off" checked class="radio-custom" id="radio-cust-1" type="radio" value="All" name="filterStatus">
                                    <label for="radio-cust-1" class="radio-custom-label">All</label>
                                </div>
                                <div>
                                    <input autocomplete="off" class="radio-custom" type="radio" id="radio-cust-01" value="Open" name="filterStatus">
                                    <label for="radio-cust-01" class="radio-custom-label">Open</label>
                                </div>
                                <div>
                                    <input autocomplete="off" class="radio-custom" id="radio-cust-2" type="radio" value="Ordered" name="filterStatus">

                                    <label for="radio-cust-2" class="radio-custom-label">Ordered</label>
                                </div>
                                <div>
                                    <input autocomplete="off" class="radio-custom" id="radio-cust-4" type="radio" value="Quote Returned" name="filterStatus">

                                    <label for="radio-cust-4" class="radio-custom-label">Quote Returned</label>
                                </div>
                                <div>
                                    <input autocomplete="off" class="radio-custom" id="radio-cust-5" type="radio" value="Order Value" name="filterStatus">

                                    <label for="radio-cust-5" class="radio-custom-label">Order Value</label>
                                </div>
                                <!-- <div>
                                    <input autocomplete="off" class="radio-custom" id="radio-cust-6" type="radio"
                                        value="Abandoned" name="filterStatus">

                                    <label for="radio-cust-6" class="radio-custom-label">Abandoned</label>
                                </div>
                                <div>
                                    <input autocomplete="off" class="radio-custom" id="radio-cust-7" type="radio"
                                        value="Expired" name="filterStatus">
                                    <label for="radio-cust-7" class="radio-custom-label">Expired</label>
                                </div> -->
                            </div>
                        </form>
                        <hr color="#e2e2e2">
                        <form>
                            <h4>Custom checkbox</h4>
                            <div class="custom-radio">
                                <div>
                                    <input autocomplete="off" checked class="radio-custom" id="radio-cust-8" type="radio" value="All" name="created_at">
                                    <label for="radio-cust-8" class="radio-custom-label">All</label>
                                </div>
                            </div>
                            <div class="custom-radio">
                                <div>
                                    <input autocomplete="off" type="radio" class="radio-custom" id="radio-cust-9" value="Today" name="created_at">
                                    <label for="radio-cust-9" class="radio-custom-label">Today</label>
                                </div>
                            </div>
                            <div class="custom-radio">
                                <div>
                                    <input autocomplete="off" type="radio" class="radio-custom" id="radio-cust-11" value="ThisWeek" name="created_at">
                                    <label for="radio-cust-11" class="radio-custom-label">This Week</label>
                                </div>
                            </div>
                            <div class="custom-radio">
                                <input autocomplete="off" type="radio" class="radio-custom" id="radio-cust-12" value="ThisMonth" name="created_at">
                                <label for="radio-cust-12" class="radio-custom-label">This Month</label>
                            </div>
                            <div class="custom-radio">
                                <input autocomplete="off" type="radio" class="radio-custom" id="radio-cust-13" value="ThisYear" name="created_at">
                                <label for="radio-cust-13" class="radio-custom-label">This Year</label>
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
    </div>
    <div class="app-main__inner">
        <div class="tab-content">
            <div class="row">
                <div class="col-sm-10 row_heading">
                    <h4>Overview</h4>
                </div>

                <div class="col-sm-1">
                    <div class="row bg-white listTypeBtn" style="border:2px solid">
                        <div class="col-sm-6 mouse-pointer btn-list listActive" onclick="listType('dataListType')">
                            <span style="display: none;" id="checkList"><svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M480 128c0 8.188-3.125 16.38-9.375 22.62l-256 256C208.4 412.9 200.2 416 192 416s-16.38-3.125-22.62-9.375l-128-128C35.13 272.4 32 264.2 32 256c0-18.28 14.95-32 32-32c8.188 0 16.38 3.125 22.62 9.375L192 338.8l233.4-233.4C431.6 99.13 439.8 96 448 96C465.1 96 480 109.7 480 128z" />
                                </svg></span>

                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5m0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5" />
                            </svg>
                        </div>


                        <div class="col-sm-6 mouse-pointer btn-list BoxActive" onclick="listType('dataBoxType')">
                            <span style="display: none;" id="checkBox"><svg viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M480 128c0 8.188-3.125 16.38-9.375 22.62l-256 256C208.4 412.9 200.2 416 192 416s-16.38-3.125-22.62-9.375l-128-128C35.13 272.4 32 264.2 32 256c0-18.28 14.95-32 32-32c8.188 0 16.38 3.125 22.62 9.375L192 338.8l233.4-233.4C431.6 99.13 439.8 96 448 96C465.1 96 480 109.7 480 128z" />
                                </svg></span>
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-ui-checks-grid" viewBox="0 0 16 16">
                                <path d="M2 10h3a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1m9-9h3a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-3a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1m0 9a1 1 0 0 0-1 1v3a1 1 0 0 0 1 1h3a1 1 0 0 0 1-1v-3a1 1 0 0 0-1-1zm0-10a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h3a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM2 9a2 2 0 0 0-2 2v3a2 2 0 0 0 2 2h3a2 2 0 0 0 2-2v-3a2 2 0 0 0-2-2zm7 2a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2h-3a2 2 0 0 1-2-2zM0 2a2 2 0 0 1 2-2h3a2 2 0 0 1 2 2v3a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2zm5.354.854a.5.5 0 1 0-.708-.708L3 3.793l-.646-.647a.5.5 0 1 0-.708.708l1 1a.5.5 0 0 0 .708 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="col-sm-1">
                    <a href="{{url('/quotation/exportAllQuotationsurl')}}" class="btn btn-primary float-right">Export</a>
                </div>
                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="overview_status">
                        <div class="row mb-3">
                            <div class="col-6 status_value">
                                <p>Open</p>
                                <h6>{!!$OpenCount!!}</h6>
                            </div>
                            <div class="col-6">
                                <div class="status_icon pull-right"><i class="fa fa-line-chart"></i></div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-6">
                                <p class="status_progress">+3.48%</p>
                            </div>
                            <div class="col-6">
                                <p class="status_statment">Since lst month</p>
                            </div>
                        </div> -->
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="overview_status">
                        <div class="row mb-3">
                            <div class="col-6 status_value">
                                <p>Ordered</p>
                                <h6>{!! $OrderedCount!!}</h6>
                            </div>
                            <div class="col-6">
                                <div class="status_icon pull-right status-danger"><i class="fa fa-level-down"></i></div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-6">
                                <p class="status_progress text-danger">+3.48%</p>
                            </div>
                            <div class="col-6">
                                <p class="status_statment">Since lst month</p>
                            </div>
                        </div> -->
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="overview_status">
                        <div class="row mb-3">
                            <div class="col-6 status_value">
                                <p style="width: 160px !important;">Quote Returned</p>
                                <h6>{!! $QuoteReturnedCount!!}</h6>
                            </div>
                            <div class="col-6">
                                <div class="status_icon pull-right status-info"><i class="fa fa-bar-chart"></i></div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-6">
                                <p class="status_progress">+3.48%</p>
                            </div>
                            <div class="col-6">
                                <p class="status_statment">Since lst month</p>
                            </div>
                        </div> -->
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6">
                    <div class="overview_status">
                        <div class="row mb-3">
                            <div class="col-6 status_value">
                                <p>Order Value</p>
                                <h6>{!! $OrderValueCount!!}</h6>
                            </div>
                            <div class="col-6">
                                <div class="status_icon pull-right status-purple"><i class="fa fa-gbp"></i></div>
                            </div>
                        </div>
                        <!-- <div class="row">
                            <div class="col-6">
                                <p class="status_progress">+3.48%</p>
                            </div>
                            <div class="col-6">
                                <p class="status_statment">Since lst month</p>
                            </div>
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="app-main__inner tableParentDiv">
        <div class="tab-content">
            @if(session()->has('success'))
            <div class="alert alert-success alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="icon fas fa-check"></i> Alert!</h5>
                {{ session()->get('success') }}
            </div>
            @endif

            <div class="row results"></div>
            <ul class="pagination pagination-sm table_page_ul"></ul>
        </div>
        <!-- Pop-up box -->
        <div class="popup-parent">
            <div class="filter_popup">
                <div class="row">
                    <div class="col-sm-12 mb-2">
                        <ul class="filter_items">
                            <h6>Quotation Status</h6>
                            <form>
                                <li>
                                    <label class="popup_container">All <input autocomplete="off" checked type="radio" value="All" name="filterStatus"><span class="checkmark"></span>
                                    </label>
                                </li>
                                <li>
                                    <label class="popup_container">Open <input autocomplete="off" type="radio" value="Open" name="filterStatus"><span class="checkmark"></span>
                                    </label>
                                </li>
                                <li>
                                    <label class="popup_container">Ordered <input autocomplete="off" type="radio" value="Ordered" name="filterStatus"><span class="checkmark"></span>
                                    </label>
                                </li>

                                <li>
                                    <label class="popup_container">Closed <input autocomplete="off" type="radio" value="Closed" name="filterStatus"><span class="checkmark"></span>
                                    </label>
                                </li>
                                <li>
                                    <label class="popup_container">Lost <input autocomplete="off" type="radio" value="Lost" name="filterStatus"><span class="checkmark"></span>
                                    </label>
                                </li>
                                <li>
                                    <label class="popup_container">Abandoned <input autocomplete="off" type="radio" value="Abandoned" name="filterStatus"><span class="checkmark"></span>
                                    </label>
                                </li>
                                <li>
                                    <label class="popup_container">Expired
                                        <input autocomplete="off" type="radio" value="Expired" name="filterStatus"><span class="checkmark"></span>
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
                                    <label class="popup_container">All <input autocomplete="off" checked type="radio" value="All" name="created_at"><span class="checkmark"></span>
                                    </label>
                                </li>
                                <li>
                                    <label class="popup_container">Today <input autocomplete="off" type="radio" value="Today" name="created_at"><span class="checkmark"></span>
                                    </label>
                                </li>
                                <li>
                                    <label class="popup_container">This Week <input autocomplete="off" type="radio" value="ThisWeek" name="created_at"><span class="checkmark"></span>
                                    </label>
                                </li>
                                <li>
                                    <label class="popup_container">This Month <input autocomplete="off" type="radio" value="ThisMonth" name="created_at"><span class="checkmark"></span>
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
    </div>

    <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
    <input type="hidden" name="directory_ajax" id="directory_ajax" value="{{route('quotation/records')}}" />
    <input type="hidden" name="excelexportNewUrl" id="excelexportNewUrl" value="{{url('/quotation/excelexportNew')}}" />
</div>
@endsection
@section('js')
<script>
    $(document).ready(function() {
        $('#checkBox').css("display", "");
        $('.BoxActive').addClass('btnActive');
    })

    function listType(type = 'box', from = 0, limitTo = 20, filters = [], order = [], doEmpty = false, setPage = false) {
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
        $.ajax({
            url: '{{ route("quotation/records") }}',
            method: "POST",
            dataType: 'json',
            data: {
                ajaxCall: 1,
                from: 0,
                limit: 12,
                orders: JSON.stringify([{
                    'column': 'quotation.created_at',
                    'dir': 'desc'
                }]),
                _token: "{{ csrf_token() }}",
                listType: type,
                isStatus: 11,
            },

            success: function(data) {
                if (data.st == "success") {
                    if (type == 'dataListType') {
                        $('.results').empty().append(data.html);

                        $('#dataListType').dataTable({

                        "lengthChange": true,
                        "responsive": true,
                        "pagingType": "simple_numbers"
                        });
                        $(".pagination.pagination-sm.table_page_ul").css("display", "none");
                    } else {
                        if (doEmpty) {
                            $('.results').empty().append(data.html);
                            $(".quotation_count").empty().append(data.total);
                        } else {
                            $('.results').empty().append(data.html);
                            $(".quotation_count").empty().append(data.total);
                        }
                        fromData = (parseInt(fromData) + parseInt(limit));
                        if (setPage) {
                            page = setPage;
                        }
                        pagination(data.total);
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

    function openQuotation(QuotationId, QVID) {
        $("ul.quoteList").css({
            "visibility": "hidden",
            'opacity': 0
        });
        const newurl = "{{url('quotation/request')}}/" + QuotationId + "/" + $QVID;
        window.open(newurl, '_blank');
    }
</script>
<script>
    $(document).ready(function() {
        column = "quotation.created_at";
        requester(0, limit, [], [{
            column: column,
            dir: dir
        }]);

    });

    function applyFilters(action = "", orderby = "desc") {

        filters = [];
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
            } else if (created_at == "ThisYear") {
                var today = new Date();
                var date = new Date(today.getTime() - 365 * 24 * 60 * 60 * 1000);
                var dd = String(date.getDate()).padStart(2, '0');
                var mm = String(date.getMonth() + 1).padStart(2, '0');
                var yyyy = date.getFullYear();
                var ThisYear = yyyy + '-' + mm + '-' + dd;
                filters.push(["quotation.created_at", ">", ThisYear]);
            }
        }

        column = "quotation.created_at";
        fromData = 0;

        if (action == "reset") {
            requester(fromData, limit, [], [{
                column: column,
                dir: dir
            }], true);
        } else {
            requester(fromData, limit, filters, [{
                column: column,
                dir: dir
            }], true);
        }

        //$(".popup-parent").modal("toggle");
        $(".close").trigger("click");
        $('#popover').removeClass('active')

    }

    $(document).on('keyup', '#QuotationSearch', function(e) {

        e.preventDefault();
        if (e.which == '13') {
            e.preventDefault();
        }
        const QuotationSearch = $(this).val();
        filters = [];
        column = "quotation.created_at";
        fromData = 0;
        dir = "desc";
        filters.push(["quotation.QuotationGenerationId", "LIKE", "%" + QuotationSearch + "%"]);
        let Query = {
            "AND": ["quotation.QuotationGenerationId", "LIKE", "%" + QuotationSearch + "%"],
            "OR": ["quotation.QuotationName", "LIKE", "%" + QuotationSearch + "%"]
        };

        // filters.push(Query);
        // filters.push(["quotation.QuotationGenerationId", "LIKE", "%" + QuotationSearch + "%"]);
        // filters.push(["quotation.ProjectName", "LIKE", "%" + QuotationSearch + "%"]);
        // filters.push(["quotation.PONumber", "LIKE", "%" + QuotationSearch + "%"]);

        requester(fromData, limit, filters, [{
            column: column,
            dir: dir
        }], true);
    })
</script>


<script src="https://code.jquery.com/jquery-git.js"></script>

<script>
    buttonfilter.onclick = function() {
        //     alert(555);
        $('#popover').toggleClass('active');
    }

    //    $(document).on('click', function () {
    //        $('#popover').removeClass('active')
    //      });

    $('.wrapper').on('click', function(x) {
        x.stopPropagation()
    })

    $("#exportAllQut").on('click', function(x) {
        var excelexportNewUrl = $("#exportAllQuotations").val();
            $.ajax({
                type: "GET",
                url: excelexportNewUrl,
                dataType: 'json',
                success: function(data) {
                    if (data.status == "success") {
                        swal("Copied!", data.message, "success");

                        column = "quotation.created_at";
                        requester(0, limit, [], [{
                            column: column,
                            dir: dir
                        }], true);
                    } else {
                        swal("Oops!", data.message, "error");
                    }
                },
                error: function(data) {
                    swal("Oops!", "Something went wrong. Please try again.", "error");
                }
            });
    })



    CopyQuotation = function(QuotationId, VersionId) {
        var r = confirm("Are you sure! you wan't to copy this quotation.");
        if (r == true) {
            $.ajax({
                type: "POST",
                url: "{{url('quotation/copy-existing-quotation')}}",
                data: {
                    _token: $("#_token").val(),
                    quotationId: QuotationId,
                    versionId: VersionId
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == "success") {
                        swal("Copied!", data.message, "success");

                        column = "quotation.created_at";
                        requester(0, limit, [], [{
                            column: column,
                            dir: dir
                        }], true);
                    } else {
                        swal("Oops!", data.message, "error");
                    }
                },
                error: function(data) {
                    swal("Oops!", "Something went wrong. Please try again.", "error");
                }
            });
        }
    };


    ExcelExportNew = function($QuotationId, $QVID) {
        var excelexportNewUrl = $("#excelexportNewUrl").val();
        var quotationId = $QuotationId;
        var currentVersion = $QVID;
        if (currentVersion != 0) {
            window.location.href = excelexportNewUrl + '/' + quotationId + '/' + currentVersion;
        } else {
            swal("Oops!", "You haven't selected any version yet.", "error");
        }
    };




    DeleteQuotation = (QuotationId, QVID) => {
        var r = confirm("Are you sure! you wan't to delete it.");
        if (r == true) {
            // if(QVID == 0){
            //     swal("Oops!", "Something went wrong. Please try again.", "error");
            //     return false;
            // }

            $.ajax({
                type: "POST",
                url: "{{url('quotation/deletequotation')}}",
                data: {
                    _token: $("#_token").val(),
                    quotationId: QuotationId,
                    versionId: QVID
                },
                dataType: 'Json',
                success: function(data) {
                    if (data.status == "success") {
                        window.location.href = "{{url('/')}}/" + data.url;
                    } else {
                        swal("Oops!", data.message, "error");
                    }
                },
                error: function(data) {
                    swal("Oops!", "Something went wrong. Please try again.", "error");
                }
            });
        }
    };

    PrintInvoice = function($QuotationId, $QVID, $bomTag) {
        $('.loader').css({
            'display': 'block'
        });
        var printInvoiceUrl = "{{url('/quotation/printinvoice')}}";
        var quotationId = $QuotationId;
        var currentVersion = $QVID;
        var bomTag = $bomTag;
        if (currentVersion != 0) {
            //if(bomTag > 0){
            let printurl = printInvoiceUrl + '/' + quotationId + '/' + currentVersion;
            window.open(printurl, '_blank');
            $('.loader').css({
                'display': 'none'
            });
            //} else {
            //    $('.loader').css({'display':'none'});
            //    swal("Oops!", "Please generate Bill Of Material.", "error");
            //}
        } else {
            $('.loader').css({
                'display': 'none'
            });
            swal("Oops!", "Please fill the Edit Header Form and create Revision.", "error");
        }
    };


    ExcelExport = function($QuotationId, $QVID) {
        $('.loader').css({
            'display': 'block'
        });
        var excelexportUrl = "{{url('/quotation/excelexport')}}";
        var quotationId = $QuotationId;
        var currentVersion = $QVID;
        if (currentVersion != 0) {
            let printurl = excelexportUrl + '/' + quotationId + '/' + currentVersion;
            window.open(printurl, '_blank');
            $('.loader').css({
                'display': 'none'
            });
        } else {
            $('.loader').css({
                'display': 'none'
            });
            swal("Oops!", "You haven't selected any version yet.", "error");
        }
    };
</script>

@endsection
