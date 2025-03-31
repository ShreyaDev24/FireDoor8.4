<style type="text/css">
    .nav-tabs {
        border: 1px solid #dee2e6;
    }

    .tab-content {
        border: 1px solid #dee2e6;
    }

    #list {
        padding: 30px 60px;
    }

    #email {
        padding: 30px 60px;
    }

    .fade input {
        width: 50%;
        border: 1px solid #eaeaea;
        background: #fff;
        color: #616161;
        padding: 5px 10px;
        border-radius: 4px;
    }

    .send-btn {
        background: #0f96f9;

        color: #fff;
        box-shadow: 0 0 5px rgb(0 0 0 / 20%);
        border: none;
        border-radius: 50px;
        padding: 6px 40px;
    }

    .tab-space {
        margin: 20px;
    }

    .alert-dismissible {
        word-wrap: break-word;
    }

    .filter_action {
        top: 8px;
        right: 11px;
    }

    .alert-primary {
        color: #213770;
        background-color: #fafbff;
        border-color: #c9d5f4;
    }

    .alert {
        padding: 10px 10px;
    }

    .filename {
        font-size: 10px;
        font-weight: 600;
    }

    .fileBox {
        padding-left: 4px;
        padding-right: 4px;
    }

    .fa-ellipsis-v {
        font-size: 10px;
    }

    .btn-secondary {
        background: #0052cc !important;
        border-color: #0052cc !important;
    }

    .btn-secondary.focus,
    .btn-secondary:focus {
        box-shadow: none !important;
    }

    .chatpage-table-box {
        display: flex;
        padding: 20px;
        position: relative;
    }

    .chatpage-table-configure h6 {
        position: absolute;
        top: 20px;
        right: 20px;
    }

    .chatpage-table-configure i {
        position: absolute;
        top: 20px;
        right: 90px;
    }

    .chatpage-table-configure h6 {}

    .chatpage-table {
        width: 100%;
    }

    .chatpage-table td {
        padding: 20px;

    }

    .accordion {
        width: 100%;
        margin: 20px auto 0px;
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
        border: 1px solid #ccc;
        /*  box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.16), 0 1px 5px 0 rgba(0, 0, 0, 0.12);*/
        display: -webkit-box;
        display: -webkit-flex;
        display: -moz-flex;
        display: -ms-flexbox;
        display: flex;
        -webkit-flex-wrap: wrap;
        -moz-flex-wrap: wrap;
        -ms-flex-wrap: wrap;
        flex-wrap: wrap;
    }

    .accordion__list {
        background: #FFF;
        width: 100%;
    }

    .accordion__list .link {
        font-size: 0.85em;
        font-weight: 700;
        color: #37474F;
        cursor: pointer;
        border-bottom: 1px solid #ccc;
        display: block;
        position: relative;
        -webkit-transition: all 0.4s ease;
        -moz-transition: all 0.4s ease;
        -moz-transition: all 0.4s ease;
        -o-transition: all 0.4s ease;
        transition: all 0.4s ease;
    }

    .accordion__list .link__title {

        padding: 20px;
        position: relative;
        box-sizing: border-box;
        width: 100%;
        display: block;
        background-color: transparent;
        color: #37474F;
        border-bottom: 1px solid #e7e8ec;
    }

    .accordion li:last-child .link {
        border-bottom: 0;
    }

    .showText {
        height: 40px;
        overflow: hidden;
        margin-bottom: 0px;

    }

    .lessText {
        height: inherit !important;
        overflow: inherit !important;
        margin-bottom: 0px;

    }

    .more_less {
        border: 0;
        background: 0;
        color: #4d90fe;
        font-size: 13px;
        text-decoration: underline;
        margin: 0;
        padding: 0;
        cursor: pointer;
    }

    .more_less:hover {
        text-decoration: none;
    }

    /** ========================
* Dise単o iconos font-awesome
============================= **/
    .accordion li i {
        position: absolute;
        top: 1.5em;
        left: 1rem;
        font-size: 1em;
        color: #595959;
        -webkit-transition: all 0.4s ease;
        -moz-transition: all 0.4s ease;
        -o-transition: all 0.4s ease;
        transition: all 0.4s ease;
    }

    .accordion li i.fa-chevron-down {
        top: 20px;
        right: 20px;
        left: auto;
        font-size: 10px;
    }

    /**===================================
* Conf. clase link al estar activo submenu (con js)
====================================**/
    .accordion li.open .link {
        color: #ff5252;
    }

    .accordion li.open i {
        color: #ff5252;
    }

    .profile-photo-input span {
        background: #dfe1e6;
        color: #42526e;
        padding: 4px;
        font-weight: 600;
        font-size: 12px;
        border-radius: 4px;
    }

    .accordion li.open i.fa-chevron-down {
        -webkit-transform: rotate(180deg);
        -moz-transform: rotate(180deg);
        -ms-transform: rotate(180deg);
        -o-transform: rotate(180deg);
        transform: rotate(180deg);
    }

    /**=======================
* Submenu
==========================**/
    .submenu {
        display: none;
        background: #444359;
        font-size: 0.95em;
    }

    .chatpage-space {
        padding: 20px;
    }

    .chatpage-comment-section input {
        width: 100%;
        padding: 10px;
        border: 1px solid #dfe1e6;
        margin-bottom: 10px;
        border-radius: 4px;
    }

    .profile-photo {
        display: flex;
    }

    .profile-photo img {
        border-radius: 50%;
        margin-right: 20px;
    }

    .profile-photo-input {
        width: 100%;
    }

    .chatpage-comment {
        margin: 30px 0px 0px;
    }

    .profile-person img {
        border-radius: 50%;
        margin-right: 20px;
    }

    .profile-person {
        display: flex;
    }

    .profile-person p {
        margin-top: 20px;
    }

    .profile-person b {
        font-weight: 700;
    }

    .sub-comment {
        list-style-type: disc;
        margin: 20px 100px;
    }

    .sub-comment li {
        margin-top: 30px;
    }

    @media only screen and (max-width: 600px) {

        .dropdown,
        .dropup {
            margin: 20px 0px;
        }

        .chatpage-table-configure i {
            top: 50px;
        }

        .chatpage-table-configure h6 {
            top: 50px;
        }
    }

    .surveyTab {
        display: block !important;
    }

    .table-overflow {
        max-height: 250px;
        overflow: auto;
    }
</style>
<style>
    .modal-backdrop.show,
    .show.blockOverlay {
        opacity: 0;
    }

    .show {
        top: 20%;
    }

    .modal-content {
    max-height: 80vh; /* Adjust the height as needed */
    overflow-y: auto;
}


    .CustomTabs {
        list-style-type: none;

        padding: 10px 30px;
        cursor: pointer;
        border-right: 1px solid #ececec;
        text-transform: uppercase;
        border-bottom: 1px solid #fff;
        font-size: 16px;
        font-weight: bold;
        color: #303641;
    }

    .CustomTabs li {
        list-style-type: none;
        float: left;
        padding: 10px 0px !important;
        cursor: pointer;
        border-right: 1px solid #ececec;
        text-transform: uppercase;
        border-bottom: 1px solid #fff;
        font-size: 16px;
        font-weight: bold;
        color: #303641;
    }

    .CustomTabs a {

        width: 0px;
        height: 3px;
        border-top-left-radius: 3px;
        border-top-right-radius: 3px;
        color: #444242;
        font-weight: bold;
        padding: 10px 30px;
    }

    .tab-content {
        border: none
    }

    .tab_heading {
        font-size: 18px;
        font-weight: 600;
        color: #303641;
    }

    .tab-content {
        height: 0px;
    }

    .checkupdate-btn {
        display: block;
        float: right;
        margin-right: 30px;
        margin-top: 30px;
    }

    .bg-red {
        /* color: #fb0000;
        font-weight: 700;
        list-style: none; */
        min-height: 23px;
        min-width: 10px;
        background-color: #fb0000;
        float: left !important;
        margin-right: 10px !important;
        border: radiu;
        border-radius: 20px;
    }

    .bg-orange {
        /* color: #df9102;
        font-weight: 700;
        list-style: none; */
        min-height: 23px;
        min-width: 10px;
        background-color: #df9102;
        float: left !important;
        margin-right: 10px !important;
        border: radiu;
        border-radius: 20px;
    }

    .bg-green {
        /* color: #008000;
        font-weight: 700;
        list-style: none;
        margin-bottom: 21px; */
        min-height: 23px;
        min-width: 10px;
        background-color: 008000;
        float: left !important;
        margin-right: 10px !important;
        border: radiu;
        border-radius: 20px;
    }

    .survey_count {
        border: 1px solid #e1dcdc;
        0px 1px 1px 2px #dfdada;
        width: 23rem;
    }

    .survey_count li span {
        float: right;
        margin-right: 40px;
    }

    .from_date {
        border: 1px solid #e7e7e7;
        padding: 8px;
        border-radius: 6px;
        box-shadow: 0px 5px 11px 1px rgb(0 0 0 / 8%);
    }

    .from_date:focus-visible {
        outline: none;
        box-shadow: 0px 0px 4px 1px #e1e1e1;
        border: 1px solid #7fd0ff;
    }

    .to_date {
        border: 1px solid #e7e7e7;
        padding: 8px;
        border-radius: 6px;
        box-shadow: 0px 5px 11px 1px rgb(0 0 0 / 8%);
    }

    .to_date:focus-visible {
        outline: none;
        box-shadow: 0px 0px 4px 1px #e1e1e1;
        border: 1px solid #7fd0ff;

    }

    #task_input {
        background: #ffffff;
        height: 60px;
        border: 1px solid #e1e1e1;
        border-radius: 4px;
        outline: none;
    }

    .building-label {
        margin-top: 13px;
    }

    .building-button {
        padding: 5px 47px;
        border-radius: 4px;
        margin-top: 26px;
    }

    .building-input {
        width: 100%;
        border: 1px solid gray;
        border-radius: 3px;
        height: 35px;
    }

    .card-container {
        width: 100%;
        background-color: #fff;
        padding: 20px;
        border-radius: 4px;
        border: 1px solid #e1e1e1;
        box-shadow: 0 2px 15px rgb(0 0 0 / 8%);
        height: 700px;
        overflow: auto;
    }

    .doorcard {
        background-color: #f8f8f8;
        margin-bottom: 10px;
        border-radius: 4px;
        box-shadow: 0 1px 2px rgb(0 0 0 / 10%);
        border: 1px solid #e1e1e1;
        cursor: pointer;
        transition: background-color 0.1s;
    }

    .doorcard-header-name {
        flex: 1 1;
        font-weight: 700;
        color: #353535;
    }

    .body-info-title {
        position: absolute;
        left: 10px;
        top: -6px;
        background: #fff;
        padding: 0 5px;
        font-weight: 700;
        color: #a1a1a1;
        border-radius: 4px;
        font-size: 14px;
    }

    .body-info {
        position: relative;
        margin-bottom: 20px;
    }

    .body-info-data {
        border: 1px solid #f1f1f1;
        padding: 17px 10px 6px;
        border-radius: 4px;
        background: #fff;
        font-size: 14px;
        color: #353535;
    }

        {
            {
            -- #floorPlans {
                background: #fff;
                padding: 20px;
            }

            --
        }
    }

    .imagePlan {
        margin-top: 45px;
        border-radius: 5px;
    }

    .msg {
        position: absolute;
        top: 15%;
        left: 20%;
        background: #f3f8fb;
        max-width: 500px;
        min-height: 40px;
        width: 100%;
        padding: 40px 10px;
        text-align: center;
        border-radius: 5px;
    }
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">

<style>
    /* #map { height: 600px; width: 600px; } */
    #map {
        height: 600px;
        margin-top: 60px;
        /* width: 800px; */
    }

    /* .leaflet-image-layer{
        margin-left: 399px;
        margin-top: 138px;
    } */


    /* .leaflet-image-layer{
        left: 400px !important;
        top: 200px !important;
    } */

    /* .leaflet-pane, .leaflet-tile, .leaflet-marker-icon, .leaflet-marker-shadow, .leaflet-tile-container, .leaflet-pane > svg, .leaflet-pane > canvas, .leaflet-zoom-box, .leaflet-image-layer, .leaflet-layer {
    position: absolute;
    left: 50% !important;
    top: 50% !important;

} */

    .leaflet-popup {
        background-color: #ffffff;
        margin-bottom: 10px;
        border-radius: 12px;
        cursor: pointer;
        width: 200px;
    }

    .floormap-title {
        margin-bottom: 14px;
        font-size: 13px;
        font-weight: 600;
    }

    .leaflet-popup .popup-info-title {
        background: #fff;
        padding: 0 5px;
        font-weight: 600;
        color: #a1a1a1;
        border-radius: 4px;
        font-size: 11px;
        margin-bottom: -7px;
    }

    .unlinkDoorBtn {
        font-size: 14px !important;
        border: 0px;
        border-radius: 3px;
        width: 100%;
        padding: 5px 0;
    }

    .popup-info-data {
        border: 1px solid #f1f1f1;
        padding: 8px 10px 6px;
        border-radius: 4px;
        background: #fff;
        font-size: 11px;
        color: #353535;
        margin-bottom: 8px;
    }

    .leaflet-fade-anim .leaflet-map-pane .leaflet-popup {
        left: -100px !important;
    }

    .popup_close {
        position: absolute;
        right: -9px;
        top: -7px;
        background: #505050;
        color: #fff;
        font-size: 17px;
        line-height: 8px;
        font-weight: bold;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        border: 4px solid #fff;
        box-shadow: 0px 0px 6px 1px rgb(0 0 0 / 25%);
        cursor: pointer
    }

    .popup_close:hover {
        background: #fb0000
    }

    .imagePreview {
        width: 90px;
        height: 90px;
        background-size: cover;
        background-position: center;
        background-repeat: no-repeat;
        margin-right: 0;
        position: absolute;
        background-color: #fff;
        top: 0;
        left: 0;
    }

    .file-upload {
        display: inline-block;
    }

    .file-select {
        position: relative;
        overflow: hidden;
        display: flex;
        align-items: center;
    }

    .file-select.file-select-box {
        display: inline-block;
        border-radius: 14px;
    }

    .file-upload-custom-btn {
        border: none;
        background-color: #d4eaff;
        color: #3b4c5c;
        z-index: 1;
        position: relative;
        font-size: 14px;
        font-weight: 5;
        padding: 5px 12px;
    }

    .file-select-name {
        margin-left: 15px;
    }

    .file-select input[type=file] {
        position: absolute;
        left: 0;
        top: 0;
        opacity: 0;
        width: 100%;
        height: 100%;
    }

    .file-select.file-select-box input[type=file] {
        z-index: 2;
    }

    .file-upload+.file-upload {
        margin-left: 10px;
    }

    .floorPlanImg {
        width: 30px;
        height: 25px;
        border-radius: 4px;
        margin-top: -23px;
    }

    .floorPlanBtn {
        background: #16aaff;
        color: #fff;
        font-size: 13px;
        font-weight: 500;
        border: 0;
        padding: 5px 25px;
        border-radius: 60px;
        cursor: pointer;
    }

    .floorplan_category {
        position: absolute;
        right: 65px;
        top: 20px;
        z-index: 1012;
    }

    .floorplan_category select {
        width: 200px;
        border: 1px solid #cbcbcb;
        padding: 7px 10px;
        border-radius: 4px;
        font-weight: 600;
    }

    .floorplan_category select:focus {
        outline: 2px solid #1498e4;
    }

    .doortypename {
        position: absolute;
        top: -20px;
        left: 23px;
        z-index: 1111;
        font-size: 12px;
        font-weight: bold;
        padding: 3px 12px;
        border-radius: 3px;
    }

    .mapdoorlist {
        border: 1px solid #d6d6d6;
        padding: 22px 10px 0px !important;
        background: #eee;
        margin-top: 3px;
        border-radius: 5px;
    }
    .modal-backdrop.show, .show.blockOverlay{
        z-index: 6;
    }
    .show {
    top: 10%;
}
</style>



<div class="project_tab">
    <ul id="project_tab">
        @if (Auth::user()->UserType == 2 || Auth::user()->UserType == 3)
        <li class="tab common active_black" data-tab-target="#floorPlans" id="floorplanid">Floor Plan<span></span></li>
        @endif

        @if (Auth::user()->UserType == 2 || Auth::user()->UserType == 3)
        <li class="tab common " data-tab-target="#Overview">Overview<span></span></li>
        @else
        <li class="tab common active_black" data-tab-target="#Overview">Overview<span></span></li>
        @endif

        <li class="tab common" data-tab-target="#files">Files<span></span></li>
        <li class="tab common" data-tab-target="#Quotes">Quotes<span></span></li>

        <li class="tab common" data-tab-target="#Orders">Orders<span></span></li>
        <li class="tab common" hidden data-tab-target="#ironmongery">Ironmongery Set<span></span></li>
        <li class="tab common" data-tab-target="#contact">Contact<span></span></li>
        @if (Auth::user()->UserType == 4)
        <li class="tab common" data-tab-target="#tender">Tender<span></span></li>
        @endif

        <li id="team_board_tab" class="tab common" data-tab-target="#team_board">Team Board<span></span></li>
        @if (Auth::user()->UserType == 2 || Auth::user()->UserType == 3)
        <li class="tab" data-tab-target="#survey" id="survey_tab">Survey<span></span></li>
        @endif
        <li class="tab common" data-tab-target="#defaults">Defaults<span></span></li>
    </ul>
</div>

<div class="tab-content mb-5" id="defaults" data-tab-content style="display: none; transform: translateY(20px); opacity: 1;">
    <ul class="CustomTabs tabs d-flex" id="again_black_Defaults">
        <li class="brdrBlackDefaults active_black_again_Defaults" id="customtab"><a href="#Custom">Custom</a></li>
        <li class="brdrBlackDefaults" id="standardtab"><a href="#Standard">Standard</a></li>
    </ul>

    <div class="CustomTabContent tab-content">
        <div id="Custom" class="tab-pane active">
            <div class="row">
                <div class="col-sm-12">
                    <div class="custom_card">
                        <div class="row mt-3">
                            <div class="col-sm-6">
                                <h3 class="tab_heading">Custom</h3>
                            </div>

                        </div>
                        <form action="{{route('project/defaultsStore')}}" method="post">
                            @csrf
                            <input type="hidden" name="projectId" value="{{$projectId}}">
                            <input type="hidden" name="default_type" value="custom">
                            <input type="hidden" name="updateVal" value="@if(isset($defaultItemsCustom->id)){{$defaultItemsCustom->id}}@else{{''}}@endif">
                            @include('Project.Ajax.DefaultSetting')
                        </form>
                    </div>
                </div>
            </div>
        </div>
         <div id="Standard" class="tab-pane">
            <div class="row">
                <div class="col-sm-12">
                    <div class="custom_card">
                        <div class="row mt-3">
                            <div class="col-sm-6">
                                <h3 class="tab_heading">Standard</h3>
                            </div>

                        </div>
                        <form action="{{route('project/defaultsStore')}}" method="post">
                            @csrf
                            <input type="hidden" name="projectId" value="{{$projectId}}">
                            <input type="hidden" name="default_type" value="standard">
                            <input type="hidden" name="updateVal" value="@if(isset($defaultItemsStandard->id)){{$defaultItemsStandard->id}}@else{{''}}@endif">
                            @include('Project.Ajax.DefaultSettingStandard')
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--  //survey tab  --}}
<div class="tab-content mb-5" id="survey" data-tab-content style="display: none; transform: translateY(20px); opacity: 1;">
    <ul class="CustomTabs tabs d-flex" id="again_black">
        <li class="brdrBlack active_black_again"><a href="#Dashboard">Dashboard</a></li>
        <li class="brdrBlack"><a href="#Change_request">Change Requests</a></li>
        <li class="brdrBlack"><a href="#Users">Users</a></li>
        <li class="brdrBlack"><a href="#Schedule">Schedule</a></li>
        <li class="brdrBlack"><a href="#Tasks">Tasks</a></li>
        <li class="brdrBlack"><a href="#Attachments">Attachments</a></li>
        <li class="brdrBlack"><a href="#Floor">Add Floor Plan</a></li>
    </ul>

    <div class="CustomTabContent tab-content">
        {{--  //dashboard Tab  --}}
        <div id="Dashboard" class="tab-pane active">
            <div class="row">
                <div class="col-sm-12">
                    <div class="custom_card">
                        <div class="row mt-3">
                            <div class="col-sm-6">
                                <h3 class="tab_heading">Dashboard</h3>
                            </div>
                            <div class="col-sm-6">
                                <div class="float-right">
                                    <a href="{{ url('project/surveyReport') }}/{{ $projectId }}"
                                        class="btn btn-success">Survey Report</a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4">
                                <ul class="survey_count">
                                    <li class="fw-bolder mt-5" style="list-style: none;font-size: 1.1rem;"><span
                                            class="bg-red"></span> Pending <span>{{ $pending }} /
                                            {{ $totalcount }}</span> </li>
                                    <li class=" mt-5" style="list-style: none;font-size: 1.1rem;"> <span
                                            class="bg-orange"></span>In Progress <span>{{ $inProgress }}</span>
                                    </li>
                                    <li class="my-5" style="list-style: none;font-size: 1.1rem;"> <span
                                            class="bg-green"></span> Completed <span>{{ $completed }}</span>
                                    </li>
                                </ul>
                            </div>
                            @php
                                if(empty($data[0]->instruction)){
                                    $instruction = '';
                                }else{
                                    $instruction = $data[0]->instruction;
                                }
                            @endphp
                            <div class="col-sm-8">
                                <form id='add_dashboard_form'>
                                    <div class="task-input d-flex flex-column">
                                        <label for="task"> <span class="font-weight-bold" style="font-size: 13px;color: #303641;font-weight: 600;">Instruction</span></label>
                                        <textarea id="instruction" name="instruction" rows="10"
                                            cols="50">{{ $instruction; }}</textarea>
                                        <input type="hidden" id="projectId" name="projectId"
                                            value="{{ $projectId }}">
                                        <input type="hidden" id="instruction_url"
                                            value="{{ url('project/instructionSave') }}">
                                    </div>
                                    <div class="mt-2">
                                        <button type="submit"
                                            class="btn btn-primary float-right">Submit</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--  //change request tab  --}}
        <div id="Change_request" class="tab-pane">
            <div class="row">
                <div class="col-sm-12">
                    <div class="custom_card">
                        <h3>Change Requests</h3>
                        <div class="row">
                            <div class="col-sm-12">
                                {!! $survey_change_request !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--  //User Tab  --}}
        <div id="Users" class="tab-pane">
            <div class="row">
                <div class="col-sm-12">
                    <div class="custom_card">
                        <div class="row mt-3">
                            <div class="col-sm-6">
                                <h3 class="tab_heading">Select Survey Users</h3>
                            </div>
                            <div class="col-sm-6">
                                @if (isset($survey_user) && !empty($survey_user) && $survey_user_count > 0)
                                <div class="float-right">
                                    <label for="">Check All</label>
                                    <input type="checkbox" class="checkall" value="survey_users" />
                                </div>
                                @endif

                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                {!! $survey_user_table !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--  //schedule tab  --}}
        <div id="Schedule" class="tab-pane">
            <div class="row">
                <div class="col-sm-12">
                    <div class="custom_card">
                        <div class="row mt-3">
                            <div class="col-sm-8">
                                <h3 class="tab_heading">Schedule</h3>
                                <label style="color: red">Note :- In case of Mozilla Firefox browser you have to
                                    fill time manually (HH:MM AM/PM) </label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                {!! $survey_info_table !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--  //task tab  --}}
        <div id="Tasks" class="tab-pane">
            <div class="row">
                <div class="col-sm-12">
                    <div class="custom_card">
                        <div class="row mt-3">
                            <div class="col-sm-6">
                                <h3 class="tab_heading">Survey User Tasks</h3>
                            </div>
                            <div class="col-sm-6">
                                <div class="float-right">
                                    <button id='add_task' class="btn btn-primary">Add Task</button>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                {!! $survey_tasks !!}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        {{--  //Attachment  --}}
        <div id="Attachments" class="tab-pane">
            <div class="row">
                <div class="col-sm-12">
                    <div class="custom_card">
                        <div class="row mt-3">
                            <div class="col-sm-6">
                                <h3 class="tab_heading">Survey User Attachments</h3>
                            </div>
                            <div class="col-sm-6">
                                <div class="float-right">
                                    <button id='add_attachments' class="btn btn-primary">Add
                                        Attachments</button>

                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-sm-12">
                                {!! $survey_attachments_table !!}
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        {{--  //floor  --}}
        <div id="Floor" class="tab-pane">
            <div class="row">
                <div class="col-sm-12">
                    <div class="custom_card">
                        <div class="card-header">
                            <h3><span>{{ $buildingDetails[0]->buildingType }}</span></h3>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <th>S.No.</th>
                                            <th>Building Type</th>
                                            <th>House Type</th>
                                            <th>No of Floors</th>
                                            <th>Floor Plan</th>
                                            <th>Action</th>
                                        </thead>
                                        <tbody>
                                            @if ($buildingDetails)
                                            @php
                                            $i = 1;
                                            @endphp
                                            @foreach ($buildingDetails as $val)
                                            <tr>
                                                <td>{{ $i++ }}</td>
                                                <td>{{ $val->buildingType }}</td>
                                                <td>{{ $val->houseType }}</td>
                                                <td>{{ $val->floorCount }}</td>
                                                <td>
                                                    <div class="file-upload">
                                                        <div class="file-select file-select-box">
                                                            <div class="imagePreview"></div>
                                                            <button class="file-upload-custom-btn"><i
                                                                    class="fa fa-camera"></i> Add floor plan
                                                            </button>
                                                            <input type="file" id="floorPlan{{ $val->id }}"
                                                                name="floorPlan[]" class="form-control">
                                                        </div>
                                                    </div>
                                                    @if (!empty($val->floorPlan))
                                                    <img src="{{ url('floorPlan/' . $val->floorPlan) }}"
                                                        alt="Floor Plan" class="floorPlanImg">
                                                    @endif
                                                </td>
                                                <td><button type="button" class="floorPlanBtn"
                                                        onclick="floorSubmit({{ $val->id }})"
                                                        id="floorSubmit{{ $val->id }}">Save <i
                                                            class="fa fa-arrow-up"></i></button></td>
                                            </tr>
                                            @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--  //Floor Plans  --}}
@if (Auth::user()->UserType == 2 || Auth::user()->UserType == 3)
<div class="tab-content mb-5" id="floorPlans" data-tab-content style="display: block; transform: translateY(20px); opacity: 1;">
@else
<div class="tab-content mb-5" id="floorPlans" data-tab-content style="display: none; transform: translateY(20px); opacity: 1;">
@endif

    <div class="custom_card ">
        <div class="floorplan_category">
            @if ($buildingDetails[0]->buildingType == 'House')
                <select name="Category" id="floorplan_category">
                    <option value="">Select Floor plan</option>
                    @if (!empty($buildingDetails))
                    @php
                    $select = 'selected';
                    @endphp
                    @foreach ($buildingDetails as $info)
                    <option value="@if ($info->floorPlan){{ url('floorPlan') }}/{{ $info->floorPlan }}@endif" @if ($buildingDetails[0]->
                        houseType == $info->houseType) {{ $select }} @endif>{{ $info->houseType }}
                    </option>
                    @endforeach
                    @endif
                </select>
            @elseif ($buildingDetails[0]->buildingType == 'Commercial')
                <select name="Category" id="floorplan_category">
                    <option value="">Select Floor plan</option>
                    @if (!empty($buildingDetails))
                    @php
                    $select = 'selected';
                    @endphp
                    @foreach ($buildingDetails as $info)
                    <option value="@if ($info->floorPlan){{ url('floorPlan') }}/{{ $info->floorPlan }}@endif" @if ($buildingDetails[0]->
                        floorCount == $info->floorCount) {{ $select }} @endif>{{ $info->floorCount }}
                    </option>
                    @endforeach
                    @endif
                </select>
            @endif
        </div>
        <div class="row">
            <div class="col-sm-4">
                <div class="doorcard-header-name mb-4 ml-3">Floor Plan</div>
                <div class="card-container">
                    <div class="doorcard-header-name mb-4">Door list</div>
                    <div class="row">
                        <div class="col-sm-6"></div>
                        <div class="col-sm-6">
                            <div class="float-right"></div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-12">
                            <div id="listDoor"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-sm-8" style="padding-left: 10px;">
                <input type="hidden" id="floorplan_category_name">
                @if (!empty($buildingDetails[0]->buildingType) && ($buildingDetails[0]->buildingType == 'House' || $buildingDetails[0]->buildingType == 'Commercial'))
                    <div id="floorPlanAdd"></div>
                @endif
                @if (!empty($buildingDetails[0]->buildingType) && $buildingDetails[0]->buildingType == 'Apartment')
                    <div id="locationIcon" hidden>{{ asset('images/location-icon-png-4225.png') }}</div>
                    <div id="FloorStructureSource" hidden>
                        @if ($buildingDetails[0]->floorPlan)
                        {{ url('floorPlan/' . $buildingDetails[0]->floorPlan) }}
                        @endif

                    </div>
                    <div id="get-floor-plan-doors" hidden>{{ url('project/get-floor-plan-doors') }}</div>
                    <div id="add-floor-plan-door" hidden>{{ url('project/add-floor-plan-door') }}</div>
                    <div id="remove-floor-plan-door" hidden>{{ url('project/remove-floor-plan-door') }}</div>
                    <div id="map"></div>
                @endif
            </div>
        </div>
    </div>
</div>

{{--  Overview  --}}
@if (Auth::user()->UserType == 2 || Auth::user()->UserType == 3)
<div class="tab-content mb-5" id="Overview" data-tab-content style="display: none; transform: translateY(20px); opacity: 1;">
@else
<div class="tab-content mb-5" id="Overview" data-tab-content style="display: block; transform: translateY(20px); opacity: 1;">
@endif

    <div class="row">
        <div class="col-sm-12">
            <div class="custom_card">
                <h3>Project <span>Overview</span></h3>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table profile_info_table">
                            <tbody>
                                <tr>
                                    <td style="width: 160px;"><b>Company Name</b></td>
                                    <td>
                                        @if ($projectinfo->customerId != '' && !empty($projectinfo->customerId))
                                        {{ CustomerCompanyName($projectinfo->customerId) }}
                                        @else
                                        {{ '-----------' }}
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td style="width: 160px;"><b>Address</b></td>
                                    <td>{{ $projectinfo->AddressLine1 }}</td>
                                </tr>
                                <tr>
                                    <td><b>City</b></td>
                                    <td>{{ $projectinfo->City }}</td>
                                </tr>

                                <tr>
                                    <td><b>State/Province</b></td>
                                    <td>{{ $projectinfo->Province }}</td>
                                </tr>
                                <tr>
                                    <td><b>Country</b></td>
                                    <td>{{ $projectinfo->Country }}</td>
                                </tr>
                                <tr>
                                    <td><b>Postal Code</b></td>
                                    <td>{{ $projectinfo->PostalCode }}</td>
                                </tr>
                                <tr>
                                    <td><b>Return Tender Date</b></td>
                                    <td>{{ date2Formate($projectinfo->returnTenderDate) }}</td>
                                </tr>
                                <tr>
                                    <td><b>More Information</b></td>
                                    <td>{{ $projectinfo->MoreInformation }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--  Files tab  --}}
<div class="tab-content mb-5" id="files" data-tab-content style="display: none; transform: translateY(20px); opacity: 1;">
    <div class="row">
        <div class="col-sm-12">
            <div class="custom_card">
                <h3>Project <span>Files</span></h3>
                <hr>
                <div class="row">
                    @php
                        $projectFilesArray = [
                        'Door Schedule',
                        'Door Elevations',
                        'Floor Plan',
                        'NBS',
                        "BOQ (Bill of
                        Quantities)",
                        'Other Files',
                        'Ironmongery Schedule',
                        ];
                    @endphp
                    @foreach ($projectFilesArray as $projectFileIndex => $projectFileVal)
                        @php
                            $projectFileValKey = preg_replace('/\s+/', '', $projectFileVal);
                            $filename = preg_replace('/\s+/', '', $projectFileVal);
                            if ($filename == 'DoorSchedule') {
                                $filetype = '';
                                $altername = $filename;
                                $matchname = $filename;
                            } elseif ($filename == 'BOQ(BillofQuantities)') {
                                $filetype = 'multiple';
                                $altername = 'BOQ[]';
                                $matchname = 'BOQ';
                            } else {
                                $filetype = 'multiple';
                                $altername = $filename . '[]';
                                $matchname = $filename;
                            }
                        @endphp
                        <div class="col-md-3 fileBox">
                            <div class="alert alert-primary" role="alert">
                                <strong>{{ $projectFileVal }}</strong>
                                <hr>
                                @if (!empty($ProjectFiles))
                                    @foreach ($ProjectFiles as $newFile)
                                        @if ($newFile->tag == $matchname)
                                            <div class="alert alert-primary alert-dismissible fade show" role="alert">
                                                <strong class="filename">{{ $newFile->file }}</strong>
                                                <div class="filter_action">
                                                    <label for="filter" class="quote_filter">
                                                        <i class="fas fa-ellipsis-v"></i>
                                                    </label>
                                                    <ul class="QuotationMenu"
                                                        style="visibility: hidden; opacity: 0; transform: translateX(-50%) scaleY(1);">
                                                        <li>
                                                            <input type="hidden" class="projectFileID" value="{{ $newFile->id }}">
                                                            <input type="hidden" class="filename" value="{{ $newFile->file }}">
                                                            <a href="{{ url('uploads/Project') }}/{{ $newFile->file }}">
                                                                <i class="fas fa-eye"></i> View</a>
                                                        </li>
                                                        <li>
                                                            <input type="hidden" class="projectFileID" value="{{ $newFile->id }}">
                                                            <input type="hidden" class="filename" value="{{ $newFile->file }}">
                                                            <a href="javascript:void(0);" class="DeleteProjectFile"><i
                                                                    class="far fa-trash-alt"></i> Delete</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        @endif
                                    @endforeach
                                @endif

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

{{--  Quotes Tab  --}}
<div class="tab-content mb-5" id="Quotes" data-tab-content style="display: none; transform: translateY(20px); opacity: 1;">
    <div class="row">
        @foreach ($data as $val)
        {!! QuotationList($val) !!}
        @endforeach
        <input type="hidden" id='projectId' value="{{ $projectId }}">
    </div>
</div>

{{--  Orders Tab  --}}
<div class="tab-content mb-5" id="Orders" data-tab-content style="display: none; transform: translateY(20px); opacity: 1;">
    <div class="row">

        @foreach ($data as $val)
        @if ($val['QuotationStatus'] == 'Ordered')
        @php
        if ($val['QuotationStatus'] != '') {
        if ($val['QuotationStatus'] == 'Ordered' || $val['QuotationStatus'] == 'Accept') {
        $quotation_status = $val['QuotationStatus'];
        }
        } else {
        $quotation_status = null;
        }

        $version = $val['version'] != '' ? $val['version'] : 1;
        $QVID = $val['QVID'] != '' ? $val['QVID'] : 0;
        $bomTag = $val['bomTag'] != '' ? $val['bomTag'] : 0;

        if ($val['CstCompanyName'] != '') {
        $CstCompanyName = $val['CstCompanyName'];
        } else {
        $CstCompanyName = '-----------';
        }

        if ($val['QuotationName'] != '') {
        $QuotationName = $val['QuotationName'];
        } else {
        $QuotationName = '-----------';
        }

        if ($val['ProjectName'] != '') {
        $ProjectName = $val['ProjectName'];
        } else {
        $ProjectName = '-----------';
        }

        if ($val['ExpiryDate'] != '') {
        $ExpiryDate = $val['ExpiryDate'];
        } else {
        $ExpiryDate = '-----------';
        }

        if ($version > 0) {
        $NumberOfDoorSets = NumberOfDoorSets($QVID,$val['QuotationId']);
        }

        if ($val['PONumber'] != '') {
        $PONumber = $val['PONumber'];
        } else {
        $PONumber = '-----------';
        }

        if ($val['ProjectId'] != '') {
        $ProjectId = $val['ProjectId'];
        } else {
        $ProjectId = 0;
        }

        if ($val['QuotEditBy'] != '') {
        if ($us['FirstName'] != '') {
        $lastModifyName = $us['FirstName'] . ' ' . $us['LastName'];
        } else {
        $lastModifyName = '-----------';
        }
        } else {
        $lastModifyName = '-----------';
        }
        if (!empty($val->projectCurrency)) {
        if ($val->projectCurrency == '£_GBP') {
        $Currency = '£';
        } elseif ($val->projectCurrency == '€_EURO') {
        $Currency = '€';
        } elseif ($val->projectCurrency == '$_US_DOLLAR') {
        $Currency = "$";
        }
        } else {
        $Currency = '£';
        }
        $doorSetPrice = orderQuotationList($val);

        @endphp

        <div class="col-sm-3 mb-3">
            <div class="QuotationBox">
                <a href="{{ url('order/generate') }}/{{ $val['QuotationId'] }}" class="QuotationCode">{{
                    $val['OrderNumber'] }}</a>
                <div class="QuotationCompanyName">
                    <b>{{ $CstCompanyName }}<strong class="QuotationStatus" style="background: #47a91f;">
                            {{ $quotation_status }}</strong></b>
                </div>
                <div class="QuotationStatusNumber">{{ $Currency . $doorSetPrice }}</div>
                <div class="QuotationListData">
                    <b>Quotation Name</b>
                    <span>{{ $QuotationName }}</span>
                    <b>Project</b>
                    <span>{{ $ProjectName }}</span>
                    <b>Due Date</b>
                    <span>{{ date2Formate($ExpiryDate) }}</span>
                    <b>Number of Door Sets</b>
                    <span>{{ $NumberOfDoorSets }}</span>
                </div>
                <div class="QuotationListNumber">
                    <b>P.O. Number</b>
                    <span>{{ $PONumber }}</span>
                </div>
                <div class="QuotationModifiedDate">
                    <p>Last modified by {{ $lastModifyName }} on {{ dateFormate($val['QuotUpdatedAt']) }}</p>
                </div>
                <div class="filter_action">
                    <label for="filter" class="quote_filter">
                        <i class="fas fa-ellipsis-h"></i>
                    </label>
                    <ul class="QuotationMenu">
                        <li>
                            <a href="{{ url('quotation/generate') }}/{{ $val['QuotationId'] }}/{{ $QVID }}"
                                target="_blank"><i class="fas fa-mouse-pointer"></i> Open</a>
                        </li>

                        <li><a href="javascript:void(0);"
                                onClick="PrintInvoice({{ $val['QuotationId'] }},{{ $QVID }},{{ $bomTag }});"><i
                                    class="fas fa-print"></i> Generate PDF</a></li>

                        <li><a href="javascript:void(0);"
                                onClick="ExcelExport({{ $val['QuotationId'] }},{{ $QVID }});">
                                <i class="fas fa-file-export"></i> Export</a>
                        </li>

                        <li>
                            <a href="javascript:void(0);"
                                onClick="OMmanualQuotation({{ $val['QuotationId'] }},{{ $QVID }},{{ $ProjectId }});"><i
                                    class="fa fa-book"></i> O&M Manual</a>
                        </li>

                    </ul>
                </div>

            </div>
        </div>
        @endif
        @endforeach
    </div>
</div>

{{--  Ironmongery  --}}
<div class="tab-content mb-5" id="ironmongery" data-tab-content style="display: none; transform: translateY(20px); opacity: 1;">
    <div class="row">
        <div class="col-sm-12">
            <div class="custom_card">
                <h3>Ironmongery <span>Set</span></h3>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <th>Set Name</th>
                                    <th>Manage</th>
                                </tr>
                                {!! $tbl !!}
                                <!-- <p>  No Ironmongery Set available </p> -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--  contact  --}}
<div class="tab-content mb-5" id="contact" data-tab-content style="display: none; transform: translateY(20px); opacity: 1;">
    <div class="row">
        <div class="col-sm-12">
            <div class="custom_card">
                <h3>Contact</h3>
                <div class="row">
                    <div class="col-sm-12">
                        <table class="table">
                            <tbody>
                                <tr>
                                    <!-- <th>Name</th>
                                    <th>ContactEmail</th>
                                    <th>ContactPhone</th>
                                    <th>ContactJobtitle</th> -->
                                </tr>
                                @if (!empty($tbl2))
                                {!! $tbl2 !!}
                                @else
                                <p> No contact available </p>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{--  Tender  --}}
@if (Auth::user()->UserType == 4)
<div class="tab-content mb-5" id="tender" data-tab-content style="display: none; transform: translateY(20px); opacity: 1;">
    <div class="row">
        <div class="col-sm-12">
            <div class="custom_card">
                <h3>Tender</h3>
                <div class="row">
                    <div class="col-sm-12">
                        <div class="tab-space">

                            <ul class="nav nav-tabs" role="tablist">
                                <li class="nav-item">
                                    <a class="nav-link active" data-toggle="tab" href="#list">Invite by
                                        List</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#email">Invite by
                                        Email</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-toggle="tab" href="#invited">Invited</a>
                                </li>

                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div id="list" class=" tab-pane active"><br>
                                    <form method="post" action="{{ route('project/invite') }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="type" value="list">
                                        <input type="hidden" name="projectId" value="{{ $projectId }}">
                                        @php
                                        $ii = 1;
                                        @endphp
                                        @foreach ($main_contractors_full as $main_contractor)
                                        <input type="checkbox" id="mail{{ $ii }}" name="email[]"
                                            value="{{ $main_contractor['UserId'] }}">
                                        <label for="mail{{ $ii }}">{{ $main_contractor['email'] }}</label><br>
                                        @php
                                        $ii++;
                                        @endphp
                                        @endforeach
                                        <button class="send-btn" type="submit" value="Send">Send</button>
                                    </form>
                                </div>
                                <div id="email" class=" tab-pane fade"><br>
                                    <form method="post" action="{{ route('project/invite') }}">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="type" value="email">
                                        <input type="hidden" name="projectId" value="{{ $projectId }}">
                                        <label for="fname">write email to invite: </label><br>
                                        <input type="text" id="mail" class="autocomplete" name="email"
                                            placeholder="Email"><br>
                                        <br>
                                        <button class="send-btn" type="submit" value="Send">Send</button>
                                    </form>
                                </div>
                                <div id="invited" class=" tab-pane fade"><br>

                                    <form action="{{ route('project/assign') }}" id="assignSubmit" method="post"
                                        enctype="multipart/form-data">
                                        {{ csrf_field() }}
                                        <input type="hidden" name="maincontratorId" id="choiceMaincontratorId">
                                        <input type="hidden" name="projectId" value="{{ $projectId }}">
                                        <input type="hidden" class="project_invitation_id"
                                            name="project_invitation_id">
                                        <div class="modal fade" tabindex="-1" role="dialog" id="form_assign_modal"
                                            data-backdrop="false">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Assign project</h5>
                                                        <button type="button" class="close" data-dismiss="modal"
                                                            aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <div class="modal-body" id="msform_assign_body">

                                                    </div>
                                                    <div class="modal-footer">
                                                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                                            <button type="button" data-dismiss="modal"
                                                                class="btn btn-info"> No </button>
                                                            <button type="submit" class="btn btn-success">
                                                                Yes
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>



                                    <table style="width: 100%;" id="example"
                                        class="table table-hover table-striped table-bordered">
                                        <thead class="text-uppercase table-header-bg">
                                            <tr class="text-white">
                                                <th>Company Name</th>
                                                <th>Contact Name</th>
                                                <th>E-Mail</th>
                                                <th>Phone</th>
                                                <th>Address</th>
                                                <th>Invited At</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            {!! $tbl3 !!}
                                        </tbody>
                                    </table>

                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endif

{{--  team board  --}}
<div class="tab-content mb-5" id="team_board" data-tab-content style="display: none; transform: translateY(20px); opacity: 1;">
    <div class="row">
        <div class="col-sm-12">
            <div class="custom_card">
                <h3>Team Board</h3>
                <div class="row">
                    <div class="col-sm-10">
                        <div class="chatpage-comment-section" id="chatpage_section">
                            <form id="commentForm">
                                {{ csrf_field() }}
                                <div class="profile-photo">
                                    <div class="profile-photo-image">
                                        @if ($user->UserImage)
                                        @if (Auth::user()->UserType == 3)
                                        <img src="{{ url('/') }}/UserImage/{{ $user->UserImage }}" alt="photo"
                                        width="40" height="40">
                                        @else
                                        <img src="{{ url('/') }}/CompanyLogo/{{ $user->UserImage }}" alt="photo"
                                        width="40" height="40">
                                        @endif
                                        @endif
                                    </div>
                                    <div class="profile-photo-input">
                                        <input type="text" id="message" placeholder="Add a comment..."
                                            name="message">
                                        <input type="hidden" id="projectId" name="projectId"
                                            value="{{ $projectId }}">
                                        <input type="hidden" id="addcomment" value="{{ route('addcomment') }}" />
                                        <h6><strong>Pro tip:</strong> press <span>Enter</span> to comment</h6>
                                    </div>

                                </div>
                            </form>

                            @foreach ($teamboards as $teamboard)
                            <div class="chatpage-comment">
                                <div class="profile-person">
                                    @if ($teamboard->user)
                                        @if ($teamboard->UserType == 3)
                                            <img src="{{ url('/') }}/UserImage/{{ $teamboard->user->UserImage }}"
                                        alt="photo" width="40" height="40" />
                                        @else
                                            <img src="{{ url('/') }}/CompanyLogo/{{ $teamboard->user->UserImage }}"
                                        alt="photo" width="40" height="40" />
                                        @endif

                                    @else
                                    <img src="https://www.kindpng.com/picc/m/24-248253_user-profile-default-image-png-clipart-png-download.png"
                                        alt="photo" width="40" height="40" />
                                    @endif
                                    <h6>
                                        @if ($teamboard->user)
                                        <b>{{ $teamboard->user->FirstName }}
                                        </b>{{ $teamboard->time }}<br>
                                        <p>{{ $teamboard->Message }}</p>
                                        @endif
                                    </h6>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    <div class="col-sm-2">
                        <div class="dropdown show">
                            <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                <a class="dropdown-item" href="#">Action</a>
                                <a class="dropdown-item" href="#">Another action</a>
                                <a class="dropdown-item" href="#">Something else here</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<form action="{{ route('updAddIronmongery') }}" id="updSubmit" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="updAddIronmongery" id="updId">
</form>
<form action="{{ route('delAddIronmongery') }}" id="delSubmit" method="post">
    {{ csrf_field() }}
    <input type="hidden" name="delId" id="delId">
</form>

<!-- Modal -->
<div class="modal fade" id="myModal" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close close_model" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Task</h5>
            </div>
            <div class="modal-body">
                <form id='add_task_form'>
                    <div class="add-task">
                        <div class="">

                        </div>
                        <div class="task-input d-flex flex-column">
                            <label for="task"> <span class="font-weight-bold" style="
                                font-size: 13px;
                                color: #303641;
                                font-weight: 600;">Task</span></label>
                            <input type='hidden' id='user_id'>
                            {{-- <input type="text" id="task_input" placeholder="Add a Task..." name="task"
                                style="height: 130px; width: 450px;"> --}}
                            <textarea id="task_input" name="task" rows="6" cols="50"></textarea>
                            <input type='hidden' id='task_value'>
                            <input type="hidden" id="projectId" name="projectId" value="{{ $projectId }}">
                        </div>
                        <div class="mt-2">
                            <button type="button" class="btn btn-primary" id='task_submit'
                                onclick="addTask({{ $projectId }})">Submit</button>
                            <button type="button" class="btn btn-danger close_model ml-2 px-3 ">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Modal for survey attachments-->
<div class="modal fade" id="myModalAttachment" role="dialog" tabindex="-1" data-backdrop="false">
    <div class="modal-dialog">
        <!-- Modal content for survey attachments-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h5 class="modal-title">Add Attachment</h5>
            </div>
            <div class="modal-body">
                <form id='add_attachment_form' method="post" action="{{ route('survey/update-attachment') }}">
                    <div class="add-task">
                        <div class="">

                        </div>
                        <div class="task-input d-flex flex-column">
                            <label for="task"> <span class="font-weight-bold" style="
                        font-size: 13px;
                        color: #303641;
                        font-weight: 600;">Select
                                    Attachment File</span></label>

                            <input class="mt-2" type="file" id="attachment_input" name="attachment_input">
                            {{-- <input name="UserImage" required type="file" class="form-control"> --}}
                            <input type='hidden' id='attachment_input'>

                        </div>
                        <div class="mt-3">
                            <button type="button" class="btn btn-primary" id='task_submit'
                                onclick="addAttachment({{ $projectId }})">Submit</button>
                            <button type="button" class="btn btn-danger close_model ml-2 px-3 ">Close</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<!-- Modal for survey attachments-->
<div class="modal fade" id="myModalFloorPlan" role="dialog" tabindex="-1" data-backdrop="false"
    style="top: 10%;left: 20%;">
    <div class="modal-dialog">
        <!-- Modal content for survey attachments-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="submit" class="btn btn-primary linkDoorBtn" data-dismiss="modal" style="float: right">
                    Link
                </button>

                <button class="popup_close" data-dismiss="modal">x</button>

                <input type="hidden" value="" name="latPosition">
                <input type="hidden" value="" name="lngPosition">
                <h5 class="modal-title">Link Doors</h5>
            </div>
            <div class="modal-body">
                <ul class="accordian_list optionHtml">
                    <li>
                        <div class="row">
                            <div class="col-md-10 col-sm-10"> <label> <b><i class="fa fa-check-circle"
                                            aria-hidden="true"></i>Type 1</b> </label> </div>
                            <div class="col-md-2 col-sm-2">
                                <div class="control-group"> <label class="control control-checkbox"> <input type="radio"
                                            name="doorId" class="leaf1_glass_type" value="67">
                                        <div class="control_indicator"></div>
                                    </label> </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="row">
                            <div class="col-md-10 col-sm-10"> <label> <b><i class="fa fa-check-circle"
                                            aria-hidden="true"></i>Type 1 Copy</b> </label> </div>
                            <div class="col-md-2 col-sm-2">
                                <div class="control-group"> <label class="control control-checkbox"> <input type="radio"
                                            name="doorId" class="leaf1_glass_type" value="68">
                                        <div class="control_indicator"></div>
                                    </label> </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="row">
                            <div class="col-md-10 col-sm-10"> <label> <b><i class="fa fa-check-circle"
                                            aria-hidden="true"></i>DD FD30</b> </label> </div>
                            <div class="col-md-2 col-sm-2">
                                <div class="control-group"> <label class="control control-checkbox"> <input type="radio"
                                            name="doorId" class="leaf1_glass_type" value="69">
                                        <div class="control_indicator"></div>
                                    </label> </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="row">
                            <div class="col-md-10 col-sm-10"> <label> <b><i class="fa fa-check-circle"
                                            aria-hidden="true"></i>DD FD30 Copy</b> </label> </div>
                            <div class="col-md-2 col-sm-2">
                                <div class="control-group"> <label class="control control-checkbox"> <input type="radio"
                                            name="doorId" class="leaf1_glass_type" value="70">
                                        <div class="control_indicator"></div>
                                    </label> </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>
<div id="floor-door-list" hidden>{{ url('project/floor-door-list') }}</div>
<input type="hidden" id="ommanual" value="{{url('order/ommanual')}}" />
<div hidden id="frame-material-filter">{{route('items/frame-material-filter')}}</div>

<!-- Door Frame Construction  -->
<div class="modal fade bd-example-modal-lg" id="DoorFrameConstructionModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="DoorFrameConstructionModalLabel">Door Frame Construction</h5>
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal"
                    aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="inputId">
                <div id="DoorFrameConstructionModalBody">
                    <div class="container">
                        <div class="row">
                            @foreach($option_data as $row)
                            @if($row->OptionSlug=='Door_Frame_Construction')
                            <div class="col-md-2 col-sm-4 col-6 cursor-pointer"
                                onclick="DoorFrameConstruction('#frameCostuction','{{$row->OptionKey}}','{{$row->OptionValue}}')">
                                <div class="color_box">
                                    <div class="frameMaterialImage">
                                        <img width="100%" height="100"
                                            src="{{url('/')}}/uploads/Options/{{$row->file}}">
                                    </div>
                                    <h4>{{$row->OptionValue}}</h4>
                                </div>
                            </div>
                            @endif
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="ralColor" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ralColorModalLabel">Frame Finish Color</h5>
                    <button type="button" class="btn btn-default btn-close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <div id="printedColor"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>
<div class="modal fade bd-example-modal-lg" id="standardralColor" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="standardralColorModalLabel">Frame Finish Color</h5>
                    <button type="button" class="btn btn-default btn-close" data-dismiss="modal" aria-label="Close">X</button>
                </div>
                <div class="modal-body">
                    <div id="standardprintedColor"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        Close
                    </button>
                </div>
            </div>
        </div>
    </div>


<div class="modal fade bd-example-modal-lg" id="frameMaterialModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="frameMaterialModalLabel">Frame Materials</h5>
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal"
                    aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <div id="frameMaterialModalBody">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="standardframeMaterialModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="standardframeMaterialModalLabel">Frame Materials</h5>
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal"
                    aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <div id="standardframeMaterialModalBody">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="glazingModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="glazingModalLabel">All Glazing</h5>
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal"
                    aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="inputId">
                <div id="glazingModalBody">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade bd-example-modal-lg" id="standardglazingModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="StandardLipingModalLabel">All Glazing</h5>
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal"
                    aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="standardinputId">
                <div id="standardglazingModalBody">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="LipingModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="LipingModalLabel">All Glazing</h5>
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal"
                    aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="inputId">
                <div id="LipingModalBody">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="StandardLipingModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="StandardLipingModalLabel">Lipping Species</h5>
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal"
                    aria-label="Close">X</button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="inputId">
                <div id="StandardLipingModalBody">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Universal Modal -->
<div class="modal fade bd-example-modal-lg" id="UniversalModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="UniversalModalLabel"></h5>
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal"
                    aria-label="Close">X</button>
                    <input type="hidden" class="inputIdentity">
            </div>
            <div class="modal-body">
                <div id="UniversalModalBody">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<div class="modal fade bd-example-modal-lg" id="standardUniversalModal" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="standardUniversalModalLabel"></h5>
                <button type="button" class="btn btn-default btn-close" data-dismiss="modal"
                    aria-label="Close">X</button>
                    <input type="hidden" class="standardinputIdentity">
            </div>
            <div class="modal-body">
                <div id="standardUniversalModalBody">

                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal" aria-label="Close">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>
<script src="{{asset('js/custome-rules.js')}}"></script>
<script src="{{asset('js/default.js')}}"></script>
<script>
    var ConfigurableDoorFormulaJson = JSON.stringify(<?= json_encode($ConfigurableDoorFormula); ?>);
    var possibleSelectedOptionsJson = JSON.stringify(<?=json_encode(\Config::get('constants.PossibleSelectedOptions.SelectedOptionsWithDbSlugKey'))?>);
    var ColorsJson = JSON.stringify(<?= json_encode($color_data); ?>);
    var LippingSpeciesJson = JSON.stringify(<?= json_encode($lipping_species); ?>);
    var SelectedLippingSpeciesJson = JSON.stringify(<?= json_encode($selected_lipping_species); ?>);

    function floor_finish_change(){

        if($("#fireRating").val()=='FD30' || $("#fireRating").val()=='FD60'){
            $("#undercut").attr("readonly","readonly")
            $("#floor_finish").show();
            $("#undercut").val(parseInt($("#floorFinish").val())+8);
        }else if($("#fireRating").val()=='FD30s' || $("#fireRating").val()=='FD60s'){
            $("#undercut").attr("readonly","readonly")
            $("#floor_finish").show();
            $("#undercut").val(parseInt($("#floorFinish").val())+3);
        }else{
            $("#undercut").attr('readonly',false)
            $("#floor_finish").show();
        }
    }

    $(document).ready(function() {
        frameMaterialFilter('FD30');
        StandardframeMaterialFilter('FD30');
        FireRatingChange();
        $("#doorsetType").change(function(){
            DoorSetTypeChange();
        });

        @if(isset($defaultItemsCustom->FrameFinish) && $defaultItemsCustom->FrameFinish == "Painted_Finish")
            FrameFinishChange(false , 'framefinish');
        @endif

        @if(isset($defaultItemsCustom->DoorsetType) && isset($defaultItemsCustom->SwingType))
        DoorSetTypeChange();
        @endif

        @if(isset($defaultItemsCustom->DoorLeafFacing))
        DoorLeafFacingChange(false,true);
        @endif

        @if(isset($defaultItemsCustom->ArchitraveMaterial) && $defaultItemsCustom->Architrave == 'Yes')
        architrave(1);
        @endif
        @if(isset($defaultItemsStandard->ArchitraveMaterial) && $defaultItemsStandard->Architrave == 'Yes')
        standardarchitrave(1);
        @endif

        @if(isset($defaultItemsCustom->DoorLeafFinishColor))
        doorLeafFinishChange();
        @endif

        @if(isset($defaultItemsStandard->FrameFinish) && $defaultItemsStandard->FrameFinish == "Painted_Finish")
        StandardFrameFinishChange(false ,  'StandardframeFinish');
        @endif

        var tabRedirect = $('#tab_redirect').val();
        $('#floorplanid').on('click',function(){
            if(tabRedirect){
                window.location.reload();
            }
        })
        if(tabRedirect){
            $('#survey_tab').removeClass('common');
            $('#survey_tab').addClass('active_black');
            $('#floorplanid').removeClass('active_black');
            $('#floorplanid').addClass('common');
            $('#floorPlans').css({'display': 'none'});
            $('#survey').css({'display': 'block'});
            $('.brdrBlack a[href="#Dashboard"]').parent().removeClass('active_black_again');
            $('.brdrBlack a[href="#'+tabRedirect+'"]').tab('show');
            $('.brdrBlack a[href="#'+tabRedirect+'"]').parent().addClass('active_black_again');
        }

        var floorplan_category = $('#floorplan_category').val();
        var floorplan_category_text = $.trim($("#floorplan_category  option:selected").text());
        $('#floorplan_category_name').val(floorplan_category_text);
        var html = '';
        html += '<div id="locationIcon" hidden>{{ asset('images/location-icon-png-4225.png') }}</div>';
        html += '<div id="FloorStructureSource" hidden>' + floorplan_category + '</div>';
        html += '<div id="get-floor-plan-doors" hidden>{{ url('project/get-floor-plan-doors') }}</div>';
        html += '<div id="add-floor-plan-door" hidden>{{ url('project/add-floor-plan-door') }}</div>';
        html += '<div id="remove-floor-plan-door" hidden>{{ url('project/remove-floor-plan-door') }}</div>';
        html += '<div id="map"></div>';
        html += '<div id="msg" class="msg">Please Add Floor Plan!</div>';
        $('#floorPlanAdd').empty().append(html);
        getFloorPlanDoors();
        var projectId = $('#projectId').val();
        $.ajax({
            type: "POST",
            url: $('#floor-door-list').text(),
            data: {
                _token: "{{ csrf_token() }}",
                floorplan_category_text: floorplan_category_text,
                projectId: projectId
            },
            dataType: 'Json',
            success: function(data) {
                if (data.status == "success") {
                    if(data.floorPlans){
                        $('#listDoor').empty().append(data.floorPlans)
                    }else{
                        $('#listDoor').empty().html('<div>Please select quotation for survey</div>')
                    }

                } else {
                    swal("Oops!", data.message, "error");
                }
            },
            error: function(data) {
                swal("Oops!", "Something went wrong. Please try again.", "error");
            }
        });

        var map = L.map("map", {
            // center: [-89.233742, 277.988262],
            center: [51.505, -0.09],
            // center: [17.342761, 78.552432],
            zoom: 13,
        });

        if($('#FloorStructureSource').text()){
            $('#map').show();
            $('#msg').hide();
            var imageUrl = $('#FloorStructureSource').text(),
            imageBounds = [
                [-683, -600],
                [150, -205],
            ];
        }else{
            $('#map').hide();
            $('#msg').show();
            var imageUrl = '',
            imageBounds = [
                [0, 0],
                [0, 0],
            ];
        }


        // imageBounds = [
        //    [-39.90973623453719, -462.65625000000006],[83.82994542398042, -181.40625]
        // ];


        //  alert(map.getBounds());
        //  console.log(map.getBounds());

        var imageOverlay = L.imageOverlay(imageUrl, imageBounds).addTo(map);
        map.invalidateSize(true);
        map.fitBounds(imageBounds);
        // map.fitBounds(imageBounds, { padding: [10, 10] });
        map.setZoom(1);

        // map.on('moveend', function() {
        //   alert(map.getBounds());
        //   // alert(map.getBounds());
        //   console.log(map.getBounds());
        // });


        map.on("click", function(ev) {
            // alert(ev.latlng); // ev is an event object (MouseEvent in this case)

            $("#myModalFloorPlan").modal();

            var lat = ev.latlng.lat;
            var lng = ev.latlng.lng;

            $('input[name="latPosition"]').val(lat);
            $('input[name="lngPosition"]').val(lng);

        });

        function removeFloorPlanDoors(id, marker) {

            $.ajax({
                type: "POST",
                url: $('#remove-floor-plan-door').text(),
                data: {
                    _token: $("#_token").val(),
                    id: id
                },
                dataType: 'Json',
                success: function(data) {
                    if (data.status == "success") {
                        // alert(data.status);
                        marker.remove();
                        getFloorPlanDoors("option");

                    } else {
                        swal("Oops!", data.message, "error");
                    }
                },
                error: function(data) {
                    swal("Oops!", "Something went wrong. Please try again.", "error");
                }
            });

        }

        function addFloorPlanDoors() {
            let lat = $('input[name="latPosition"]').val();
            let lng = $('input[name="lngPosition"]').val();
            let doorId = $('input[name="doorId"]:checked').val();

            // alert(doorId);

            if (doorId == undefined) {
                alert("Please select any door.");
                return 0;
            }

            $.ajax({
                type: "POST",
                url: $('#add-floor-plan-door').text(),
                data: {
                    _token: $("#_token").val(),
                    lat: lat,
                    lng: lng,
                    doorId: doorId,
                },
                dataType: 'Json',
                success: function(data) {
                    if (data.status == "success") {
                        // alert(data.status);

                        var htmlPopup = '<div class="floormap-title">' + data.data
                            .DoorType +
                            '</div><div class="floormap-popup"><div class="popup-info"><div class="popup-info-title">Door Ident No.</div><div class="popup-info-data">' +
                            data.data.doorNumber +
                            '</div></div><div class="popup-info"><div class="popup-info-title">Fire Rating</div><div class="popup-info-data">' +
                            data.data.FireRating +
                            '</div></div></div><div class=" flex j-center" style="padding: 0px; margin: 10px 0px 0px;"><div class="" style="padding: 0px; margin: 0px;"><button class="button default unlinkDoorBtn" data-planId="' +
                            data.data.itemMasterId +
                            '" style="background: rgb(255, 67, 67); color: white; margin-right: 10px; font-size: 14px;">Unlink Door</button></div></div>';

                        let Lmarker = L.marker([lat, lng])
                            .addTo(map)
                            .bindPopup(htmlPopup)
                            .on("click", function(ev) {
                                //this.openPopup();
                                let marker = this;
                                $(".unlinkDoorBtn").on("click", function(ev) {
                                    marker = marker;
                                    removeFloorPlanDoors($(this).attr(
                                        "data-planId"), marker);
                                });
                            });

                        getFloorPlanDoors("option");

                    } else {
                        swal("Oops!", data.message, "error");
                    }
                },
                error: function(data) {
                    swal("Oops!", "Something went wrong. Please try again.", "error");
                }
            });

        }

        function updateDoorOptions(data) {

            var optionHtml = '';
            data.forEach(element => {
                console.log(element);

                if (!element.id) {
                    optionHtml +=
                        '<li class="mapdoorlist"> <div class="row"> <div class="col-md-10 col-sm-10"> <label> <span><i class="fa fa-check-circle" aria-hidden="true"></i><b class="doortypename">' +
                        element.DoorType + '</b>' + element.doorNumber +
                        '</span> </label> </div><div class="col-md-2 col-sm-2"> <div class="control-group"> <label class="control control-checkbox"> <input type="radio" name="doorId" class="leaf1_glass_type" value="' +
                        element.itemMasterId +
                        '"> <div class="control_indicator"></div></label> </div></div></div></li>';
                }
            });
            console.log(optionHtml);
            $('.optionHtml').empty().append(optionHtml);

        }

        function updateDoorLocations(data) {

            data.forEach(element => {

                if (element.itemMasterId && element.latPosition != null) {

                    var htmlPopup = '<div class="floormap-title">' + element.DoorType +
                        '</div><div class="floormap-popup"><div class="popup-info"><div class="popup-info-title">Door Ident No.</div><div class="popup-info-data">' +
                        element.doorNumber +
                        '</div></div><div class="popup-info"><div class="popup-info-title">Fire Rating</div><div class="popup-info-data">' +
                        element.FireRating +
                        '</div></div></div><div class=" flex j-center" style="padding: 0px; margin: 10px 0px 0px;"><div class="" style="padding: 0px; margin: 0px;"><button class="button default unlinkDoorBtn"  data-planId="' +
                        element.itemMasterId +
                        '" style="background: rgb(255, 67, 67); color: white; margin-right: 10px; font-size: 14px;">Unlink Door</button></div></div>';

                    let Lmarker = L.marker([element.latPosition, element.lngPosition])
                        .addTo(map)
                        .bindPopup(htmlPopup)
                        .on("click", function(ev) {
                            // //this.openPopup();
                            let marker = this;
                            $(".unlinkDoorBtn").on("click", function(ev) {
                                // marker.remove();
                                marker = marker;
                                removeFloorPlanDoors($(this).attr(
                                    "data-planId"), marker);
                            });
                        });
                    console.log(Lmarker);


                }

            });

        }

        function getFloorPlanDoors(updateType = "") {
            var projectId = $('#projectId').val();
            var floorplan_category_text = $('#floorplan_category_name').val();
            $.ajax({
                type: "GET",
                url: $('#get-floor-plan-doors').text(),
                data: {
                    _token: $("#_token").val(),
                    projectId: projectId,
                    floorplan_category_text: floorplan_category_text
                },
                dataType: 'Json',
                success: function(data) {
                    if (data.status == "success") {

                        if (updateType == "") {
                            updateDoorOptions(data.data);
                            updateDoorLocations(data.data);

                        } else if (updateType == "option") {
                            updateDoorOptions(data.data);

                        } else if (updateType == "map") {
                            updateDoorLocations(data.data);

                        }

                    } else {
                        swal("Oops!", data.message, "error");
                    }
                },
                //   error: function(data) {
                //       swal("Oops!", "Something went wrong. Please try again.", "error");
                //   }
            });

        }

        $(".linkDoorBtn").on("click", function(ev) {
            addFloorPlanDoors();
        });



        $('#floorplan_category').on('change', function() {
            var floorplan_category = $('#floorplan_category').val();
            var floorplan_category_text = $.trim($("#floorplan_category  option:selected").text());
            $('#floorplan_category_name').val(floorplan_category_text);
            var html = '';
            html +=
                '<div id="locationIcon" hidden>{{ asset('images/location-icon-png-4225.png') }}</div>';
            html += '<div id="FloorStructureSource" hidden>' + floorplan_category + '</div>';
            html +=
                '<div id="get-floor-plan-doors" hidden>{{ url('project/get-floor-plan-doors') }}</div>';
            html +=
                '<div id="add-floor-plan-door" hidden>{{ url('project/add-floor-plan-door') }}</div>';
            html +=
                '<div id="remove-floor-plan-door" hidden>{{ url('project/remove-floor-plan-door') }}</div>';
            html += '<div id="map"></div>';
            html += '<div id="msg" class="msg">Please Add Floor Plan!</div>';

            $('#floorPlanAdd').empty().append(html);
            getFloorPlanDoors();
            var projectId = $('#projectId').val();
            $.ajax({
                type: "POST",
                url: $('#floor-door-list').text(),
                data: {
                    _token: "{{ csrf_token() }}",
                    floorplan_category_text: floorplan_category_text,
                    projectId: projectId
                },
                dataType: 'Json',
                success: function(data) {
                    if (data.status == "success") {
                        if(data.floorPlans){
                            $('#listDoor').empty().append(data.floorPlans)
                        }else{
                            $('#listDoor').empty().html('<div>Please select quotation for survey</div>')
                        }
                    } else {
                        swal("Oops!", data.message, "error");
                    }
                },
                error: function(data) {
                    swal("Oops!", "Something went wrong. Please try again.", "error");
                }
            });

            map = L.map("map", {
                // center: [-89.233742, 277.988262],
                center: [51.505, -0.09],
                // center: [17.342761, 78.552432],
                zoom: 13,
            });

            if($('#FloorStructureSource').text()){
                $('#map').show();
                $('#msg').hide();
                var imageUrl = $('#FloorStructureSource').text(),
                imageBounds = [
                    [-683, -600],
                    [150, -205],
                ];
            }else{
                $('#map').hide();
                $('#msg').show();
                var imageUrl = '',
                imageBounds = [
                    [0, 0],
                    [0, 0],
                ];
            }

            // imageBounds = [
            //    [-39.90973623453719, -462.65625000000006],[83.82994542398042, -181.40625]
            // ];


            //  alert(map.getBounds());
            //  console.log(map.getBounds());

            imageOverlay = L.imageOverlay(imageUrl, imageBounds).addTo(map);
            map.invalidateSize(true);
            map.fitBounds(imageBounds);
            // map.fitBounds(imageBounds, { padding: [10, 10] });
            map.setZoom(1);

            // map.on('moveend', function() {
            //   alert(map.getBounds());
            //   // alert(map.getBounds());
            //   console.log(map.getBounds());
            // });


            map.on("click", function(ev) {
                // alert(ev.latlng); // ev is an event object (MouseEvent in this case)

                $("#myModalFloorPlan").modal();

                var lat = ev.latlng.lat;
                var lng = ev.latlng.lng;

                $('input[name="latPosition"]').val(lat);
                $('input[name="lngPosition"]').val(lng);

            });
        });


    });





    $(function() {
        //ADD comment

        $("#add_task").click(function(e) {
            e.preventDefault();
            $("#myModal").modal('show');

        });

        $('.close_model').on('click', function() {
            $('#myModal').modal('hide');
            $('#add_task_form')[0].reset();
        });

        $("#add_attachments").click(function(e) {
            e.preventDefault();
            $("#myModalAttachment").modal('show');
        });

        $('.close_model').on('click', function() {
            $('#myModalAttachment').modal('hide');
        });



        $('#commentForm').keypress(function(e) {
            if (e.which === 13) {
                e.preventDefault();
                let data = $('#commentForm').serialize();
                let url = $('#addcomment').val();
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: data,
                    datatype: "json",
                    success: function(res) {

                        if (res.status == 'success') {
                            $("#message").val("");
                            var comments = '';
                            var teamboards = res.teamboards.reverse();
                            for (var key in teamboards) {
                                if (teamboards[key].user.UserImage) {
                                    var image =
                                        `<img src="{{ url('/') }}/CompanyLogo/${teamboards[key].user.UserImage}" alt="photo" width="40" height="40"/>`;
                                } else {
                                    var image = `<img src="https://www.kindpng.com/picc/m/24-248253_user-profile-default-image-png-clipart-png-download.png" alt="photo" width="40"
                                                 height="40"/>`;
                                }
                                if (teamboards.hasOwnProperty(key)) {

                                    comments += `

                        <div class="chatpage-comment">
                         <div class="profile-person">

\t${image}


    <h6>
        <b>${teamboards[key].user.FirstName} </b>${teamboards[key].time}<br>
\t<p>${teamboards[key].Message}</p>

    </h6>

</div>


</div>`
                                }

                            }

                            $('.chatpage-comment').remove();
                            $('#commentForm').after(comments);

                        }
                    }
                })


            }
        });


        // Edit Ironmongery
        $(document).on('click', '.updAddIronmongery', function() {
            let id = $(this).val();
            $('#updId').val(id);
            $('#updSubmit').submit();
        })
        // Delete Ironmongery
        $(document).on('click', '.delAddIronmongery', function() {
            let id = $(this).val();
            $('#delId').val(id);
            $('#delSubmit').submit();
        })
    })
    $(".form-control").attr("disabled", false);
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

<script>
    // Add by caca
    // For autocompleting
    $(function() {
        var autocompletData = @json($main_contractors, JSON_PRETTY_PRINT);
        // console.log(autocompletData);
        $(".autocomplete").autocomplete({
            source: function(request, response) {
                var matcher = new RegExp("^" + $.ui.autocomplete.escapeRegex(request.term), "i");
                response($.grep(autocompletData, function(item) {
                    return matcher.test(item);
                }));
            },
            autoFocus: true
        });
    });

    $(document).on('click', '.assignproject', function() {
        let id = $(this).siblings('input').val();
        $('#choiceMaincontratorId').val(id);
        $(".project_invitation_id").val($(this).siblings(".invitationId").val())
        // Build form here
        var content = "";
        element = document.querySelector('#msform_assign_body');
        container = $("#msform_assign_body");
        container.empty();
        div = document.createElement('div');
        content += `Do you really want to assign this project to this main contrator ?`;
        div.innerHTML = content;
        element.appendChild(div);
        // show now the modal
        $('#form_assign_modal').modal('show');
    })
</script>


<script>
    $("#files").attr("style", "display: none; transform: translateY(30px); opacity: 0;")
    $("#ironmongery").attr("style", "display: none; transform: translateY(30px); opacity: 0;")
    $("#Orders").attr("style", "display: none; transform: translateY(30px); opacity: 0;")
    $("#Quotes").attr("style", "display: none; transform: translateY(30px); opacity: 0;")
    $("#contact").attr("style", "display: none; transform: translateY(30px); opacity: 0;")
    $("#tender").attr("style", "display: none; transform: translateY(30px); opacity: 0;")
    $("#team_board").attr("style", "display: none; transform: translateY(30px); opacity: 0;")
</script>



<script>
    DeleteQuotation = (QuotationId, QVID) => {
        var r = confirm("Are you sure! you wan't to delete it.");
        if (r == true) {
            // if(QVID == 0){
            //     swal("Oops!", "Something went wrong. Please try again.", "error");
            //     return false;
            // }

            $.ajax({
                type: "POST",
                url: "{{ url('quotation/deletequotation') }}",
                data: {
                    _token: $("#_token").val(),
                    quotationId: QuotationId,
                    versionId: QVID
                },
                dataType: 'Json',
                success: function(data) {
                    if (data.status == "success") {
                        swal({
                            title: "Deleted!",
                            text: "Quotation Deleted Successfully!",
                            type: "success"
                        }).
                        then(function() {
                            history.go(0);
                        });
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


    CopyQuotation = function(QuotationId, VersionId) {
        var r = confirm("Are you sure! you wan't to copy this quotation.");
        if (r == true) {
            $.ajax({
                type: "POST",
                url: "{{ url('quotation/copy-existing-quotation') }}",
                data: {
                    _token: $("#_token").val(),
                    quotationId: QuotationId,
                    versionId: VersionId
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == "success") {
                        swal({
                            title: "Copied!",
                            text: data.message,
                            type: "success"
                        }).
                        then(function() {
                            history.go(0);
                        });
                        //column = "quotation.created_at";
                        //requester(0, limit, [], [{
                        //    column: column,
                        //    dir: dir
                        //}], true);
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
        var printInvoiceUrl = "{{ url('/quotation/printinvoice') }}";
        var quotationId = $QuotationId;
        var currentVersion = $QVID;
        var bomTag = $bomTag;
        if (currentVersion != 0) {
            //if (bomTag > 0) {
            let printurl = printInvoiceUrl + '/' + quotationId + '/' + currentVersion;
            window.open(printurl, '_blank');
            $('.loader').css({
                'display': 'none'
            });
            //} else {
            //$('.loader').css({
            //  'display': 'none'
            //});
            //swal("Oops!", "Please generate Bill Of Material.", "error");
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
        var excelexportUrl = "{{ url('/quotation/excelexport') }}";
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

    $('#survey_tab').on('click', function() {
        $('#survey').addClass('surveyTab');
    });
    $('.common').on('click', function() {
        $('#survey').removeClass('surveyTab');
    });

    $('.CustomTabs>li.active>a').css({
        'color': 'black',
        'font-weight': 'bold'
    })
    $(document).on('click', '.CustomTabs>li>a', function(e) {
        e.preventDefault();
        $('.CustomTabs>li>a').removeAttr('style')
        $(this).css({
            'color': 'black',
            'font-weight': 'bold'
        })
        let href = $(this).attr('href');
        $('.CustomTabContent > div').removeAttr('class')
        $('.CustomTabContent > div').addClass('tab-pane fade')
        $(href).removeAttr('class')
        $(href).addClass('tab-pane active')
    })

    $('.checkall').click(function() {
        const tabname = $(this).val();
        if ($(this).is(':checked')) {
            $('.' + tabname).prop('checked', true);
        } else {
            $('.' + tabname).prop('checked', false);
        }
    });

    function updateMe() {
        var projectId = $('#projectId').val();
        var selectedValue = [];
        $('.survey_users:checked').map(function(_, el) {
            selectedValue.push($(el).val());
        }).get();

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url: "{{ url('survey/update-check-option') }}",
            method: "POST",
            dataType: "Json",
            data: {
                selectedValue: selectedValue,
                projectId: projectId,
                _token: "{{ csrf_token() }}"
            },
            success: function(result) {
                if (result.status = "ok") {
                    alert('Survey user Selected successfully.')
                    location.reload();
                } else {
                    alert(result.msg);
                }
            }
        });




    }


    function updateFromToDate(user_id, projectId) {

        var fromTime = $('#from_date' + user_id).val();
        var toTime = $('#to_date' + user_id).val();


        if (fromTime > toTime) {
            alert("End Date should be greater than Start Date!!");
            return false;
        }

        if (fromTime == '') {
            alert("Please select the Start Date Time!");
            return false;
        } else if (toTime == '') {
            alert("Please select the End Date Time!");
            return false;
        }

        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url: "{{ url('survey/update-from-to-date') }}",
            method: "POST",
            dataType: "Json",
            data: {
                fromTime: fromTime,
                toTime: toTime,
                user_id: user_id,
                projectId: projectId,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                if (data.status == "success") {
                    swal({
                        title: "success!",
                        text: data.msg,
                        type: "success"
                    }).
                    then(function() {
                        history.go(0);
                    });
                } else {
                    swal("Oops!", data.msg, "error");
                }
            }
        });
    }

    SurveyQuotation = function(QuotationId, vid) {
        var qid = $('#qid').val();
        var verid = $('#vid').val();
        if (QuotationId == qid && vid == verid) {
            alert('Quotation already selected!');
            return false;
        }
        var r = confirm("Are you sure? you want to survey this quotation.");
        if (r == true) {

            if (vid == "" || vid == 0) {
                alert('Please Select the Quotation Revision!');
                return false;
            }
            var projectId = $('#projectId').val();

            $.ajax({
                type: "POST",
                url: "{{ url('survey/project-quotation-survey') }}",
                data: {
                    _token: $("#_token").val(),
                    quotationId: QuotationId,
                    versionId: vid,
                    projectId: projectId
                },
                dataType: 'json',
                success: function(data) {
                    if (data.status == "success") {
                        swal({
                            title: "success!",
                            text: data.msg,
                            type: "success"
                        }).
                        then(function() {
                            history.go(0);
                        });
                    } else {
                        swal("Oops!", data.msg, "error");
                    }
                },
                error: function(data) {
                    swal("Oops!", "Something went wrong. Please try again.", "error");
                }
            });
        }
    };

    function addTask(projectId, id = '') {
        var id = $('#user_id').val();
        var task_input = $('#task_input').val();
        if (task_input == '') {
            alert("Please Enter the Task!");
            return false;
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url: "{{ url('survey/update-tasks') }}",
            method: "POST",
            dataType: "Json",
            data: {
                id: id,
                task_input: task_input,
                projectId: projectId,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                if (data.status == "success") {
                    swal({
                        title: "success!",
                        text: data.msg,
                        type: "success"
                    }).
                    then(function() {
                        history.go(0);
                    });
                } else {
                    swal("Oops!", data.msg, "error");
                }
            }
        });
    }

    function taskUpdate(tasks, id) {
        var id = $('#user_id').val(id);
        var task_value = $('#task_input').val(tasks);
        $("#myModal").modal('show');
    }

    function ChangeRequest(QuotationId, VersionId, SOWidth, SOHeight, itemId, id, oldSOWidth, oldSOHeight, oldSODepth,
        SOWallThick, requestId) {
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url: "{{ url('survey/update-change-request') }}",
            method: "POST",
            dataType: "Json",
            data: {
                quotationId: QuotationId,
                versionId: VersionId,
                id: id,
                itemId: itemId,
                oldSOWidth: oldSOWidth,
                oldSOHeight: oldSOHeight,
                oldSODepth: oldSODepth,
                SOWidth: SOWidth,
                SOHeight: SOHeight,
                SOWallThick: SOWallThick,
                requestId: requestId,
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                if (data.status == "success") {
                    swal({
                        title: "success!",
                        text: data.msg,
                        type: "success"
                    }).
                    then(function() {
                        history.go(0);
                    });
                } else {
                    swal("Oops!", data.msg, "error");
                }
            }
        });
    }

    $('#add_dashboard_form').on('submit',function(e){
        e.preventDefault();
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url: $('#instruction_url').val(),
            method: "POST",
            dataType: "Json",
            data: {
                projectId: $('#projectId').val(),
                instruction: $('#instruction').val(),
                _token: "{{ csrf_token() }}"
            },
            success: function(data) {
                if (data.status == "success") {
                    swal({
                        title: "success!",
                        text: data.msg,
                        type: "success"
                    }).
                    then(function() {
                        history.go(0);
                    });
                } else {
                    swal("Oops!", data.msg, "error");
                }
            }
        });
    });


    function addAttachment(projectId, id = '') {

        var id = $('#user_id').val();
        var file_data = $('#attachment_input').prop('files')[0];

        console.log(file_data);
        var form_data = new FormData();
        form_data.append('file', file_data);
        form_data.append('projectId', projectId);


        if (attachment_input == '') {
            alert("Please Choose the Attachment file!");
            return false;
        }
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url: "{{ url('survey/update-attachment') }}",
            method: "POST",
            dataType: "Json",
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function(data) {
                if (data.status == "success") {
                    swal({
                        title: "success!",
                        text: data.msg,
                        type: "success"
                    }).
                    then(function() {
                        history.go(0);
                    });
                } else {
                    swal("Oops!", data.msg, "error");
                }
            }
        });
    }

    $(".more_less").on("click", function() {
        if ($(this).text() == 'show more..') {
            $(this).siblings().addClass('lessText');
            $(this).text('less more..');
        } else {
            $(this).siblings().removeClass('lessText');
            $(this).text('show more..');
        }
    });

    function floorSubmit(id) {
        var file = $('#floorPlan' + id).prop('files')[0];
        var projectId = $('#projectId').val();
        var floor_plan = $('#floor_plan').val();
        var form_data = new FormData();
        form_data.append('file', file);
        form_data.append('projectId', projectId);
        form_data.append('floor_plan', floor_plan);
        form_data.append('id', id);
        $.ajax({
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url: "{{ url('project/floorStore') }}",
            method: "POST",
            dataType: "Json",
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,
            success: function(data) {
                if (data.status == "success") {
                    swal({
                        title: "success!",
                        text: data.msg,
                        type: "success"
                    }).
                    then(function() {
                        history.go(0);
                    });
                } else {
                    swal("Oops!", data.msg, "error");
                }
            }
        });
    }

    OMmanualQuotation = function($QuotationId , $QVID , $projectId=null){
        $('.loader').css({'display':'block'});
        let ommanual = $('#ommanual').val();
        let url = ommanual + '/' + $QuotationId + '/' + $QVID;
        let filename = "OMmanual.pdf";
        // if($projectId > 0){
            convertToAudio(url , filename);
        // } else {
        //     $('.loader').css({'display':'none'});
        //     alert('These quotation is not have project.');
        // }

        // $('.loader').css({'display':'none'});
    };

    function convertToAudio(url,filename) {
        var request = new XMLHttpRequest();
        request.onreadystatechange = function() {
            if(request.readyState == 4) {
                if(request.status == 200) {
                    console.log(typeof request.response); // should be a blob
                    var blob = request.response;
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;
                    link.click();
                    $('.loader').css({'display':'none'});
                } else if(request.responseText != "") {
                    console.log(request.responseText);
                }
            } else if(request.readyState == 2) {
                if(request.status == 200) {
                    request.responseType = "blob";
                    var blob = request.response;
                    var link = document.createElement('a');
                    link.href = window.URL.createObjectURL(blob);
                    link.download = filename;
                    link.click();
                    $('.loader').css({'display':'none'});
                } else {
                    request.responseType = "text";
                }
            }
        };
        request.open("GET", url, true);
        request.send();
    }

    // Add active class to the current button (highlight it)
    var header = document.getElementById("project_tab");
    var btns = header.getElementsByClassName("tab");
    for (var i = 0; i < btns.length; i++) {
        btns[i].addEventListener("click", function() {
            var current = document.getElementsByClassName("active_black");
            current[0].className = current[0].className.replace(" active_black", "");
            this.className += " active_black";
        });
    }

    var header = document.getElementById("again_black");
    var btns = header.getElementsByClassName("brdrBlack");
    for (var i = 0; i < btns.length; i++) {
        btns[i].addEventListener("click", function() {
            var current = document.getElementsByClassName("active_black_again");
            current[0].className = current[0].className.replace(" active_black_again", "");
            this.className += " active_black_again";
        });
    }

    var header = document.getElementById("again_black_Defaults");
    var btns = header.getElementsByClassName("brdrBlackDefaults");
    for (var i = 0; i < btns.length; i++) {
        btns[i].addEventListener("click", function() {
            var current = document.getElementsByClassName("active_black_again_Defaults");
            current[0].className = current[0].className.replace(" active_black_again_Defaults", "");
            this.className += " active_black_again_Defaults";
        });
    }
</script>

{{--  @section('js')  --}}
{{--  <script src="{{ asset('js/FloorPlanDoorAdd.js') }}"></script>  --}}

{{--  @endsection  --}}
