@extends('layouts.FloorMaster')
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

    {{-- #floorPlans {
        background: #fff;
        padding: 20px;
    } --}} .imagePlan {
        margin-top: 45px;
        border-radius: 5px;
    }
</style>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
    integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
    crossorigin="" />
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
        z-index: 1012;
        margin: 0 auto;
        display: table;
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

    #map {
        height: 600px;
        margin: 20px auto 0;
        background: #fff;
        box-shadow: 0px 0px 21px 1px rgb(0 0 0 / 11%);
        border-radius: 9px;
        margin-bottom: 50px;
    }

    .fixed-header .app-main {
        padding-top: 0px !important;
    }
</style>
@section('main_section')
    @if (empty($buildingDetails[0]->buildingType) || empty($floorPlans))
        echo 'something went wrong!';die;
    @endif
    <div>
        <div class="app-main__inner">
            <div class="floorplan_category">
                @if ($buildingDetails[0]->buildingType == 'House')
                    <select name="Category" id="floorplan_category">
                        <option value="">Select Floor plan</option>
                        @if (!empty($buildingDetails))
                            @php
                                $select = 'selected';
                            @endphp
                            @foreach ($buildingDetails as $info)
                                <option value=" {{ url('floorPlan') }}/{{ $info->floorPlan }}"
                                    @if ($buildingDetails[0]->houseType == $info->houseType) {{ $select }} @endif>
                                    {{ $info->houseType }}</option>
                            @endforeach
                        @endif
                    </select>
                @elseif ($buildingDetails[0]->buildingType == 'Commercial')
                    <select name="Category" id="floorplan_category">
                        <option value="">Select Floor plan</option>
                        @if (!empty($buildingDetails))
                            @foreach ($buildingDetails as $info)
                                <option value="{{ url('floorPlan') }}/{{ $info->floorPlan }}">
                                    {{ $info->floorCount }}</option>
                            @endforeach
                        @endif
                    </select>
                @endif

            </div>

            <div class="row">
                <div class="col-sm-12" style="padding-left: 10px;">
                    @if (!empty($buildingDetails[0]->buildingType) &&
                        ($buildingDetails[0]->buildingType == 'House' || $buildingDetails[0]->buildingType == 'Commercial'))
                        <div id="floorPlanAdd"></div>
                    @endif
                    @if (!empty($buildingDetails[0]->buildingType) && $buildingDetails[0]->buildingType == 'Apartment')
                        <div id="locationIcon" hidden>
                            {{ asset('images/location-icon-png-4225.png') }}</div>
                        <div id="FloorStructureSource" hidden>
                            {{ url('floorPlan/' . $buildingDetails[0]->floorPlan) }}</div>
                        <div id="get-floor-plan-doors" hidden>
                            {{ url('project/get-floor-plan-doors') }}</div>
                        <div id="add-floor-plan-door" hidden>
                            {{ url('project/add-floor-plan-door') }}</div>
                        <div id="remove-floor-plan-door" hidden>
                            {{ url('project/remove-floor-plan-door') }}</div>

                        <div id="map"></div>
                    @endif

                    <input type="hidden" id="projectId" value="{{ $buildingDetails[0]->projectId }}">
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="myModalFloorPlan" role="dialog" tabindex="-1" data-backdrop="false" style="top: 10%;">
        <div class="modal-dialog">
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
                                    <div class="control-group"> <label class="control control-checkbox">
                                        <input type="radio" name="doorId" class="leaf1_glass_type" value="67">
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
                                    <div class="control-group"> <label class="control control-checkbox"> <input
                                                type="radio" name="doorId" class="leaf1_glass_type" value="68">
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
                                    <div class="control-group"> <label class="control control-checkbox"> <input
                                                type="radio" name="doorId" class="leaf1_glass_type" value="69">
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
                                    <div class="control-group"> <label class="control control-checkbox"> <input
                                                type="radio" name="doorId" class="leaf1_glass_type" value="70">
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
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            var floorplan_category = $('#floorplan_category').val();
            var floorplan_category_text = $.trim($("#floorplan_category  option:selected").text());
            var html = '';
            html += '<div id="locationIcon" hidden>{{ asset('images/location-icon-png-4225.png') }}</div>';
            html += '<div id="FloorStructureSource" hidden>' + floorplan_category + '</div>';
            html += '<div id="get-floor-plan-doors" hidden>{{ url('project/get-floor-plan-doors') }}</div>';
            html += '<div id="add-floor-plan-door" hidden>{{ url('project/add-floor-plan-door') }}</div>';
            html += '<div id="remove-floor-plan-door" hidden>{{ url('project/remove-floor-plan-door') }}</div>';
            html += '<div id="map"></div>';
            $('#floorPlanAdd').empty().append(html);
            getFloorPlanDoors();

            $.ajax({
                type: "POST",
                url: $('#floor-door-list').text(),
                data: {
                    _token: "{{ csrf_token() }}",
                    floorplan_category_text: floorplan_category_text
                },
                dataType: 'Json',
                success: function(data) {
                    if (data.status == "success") {


                    } else {
                        swal("Oops!", data.message, "error");
                    }
                },
                error: function(data) {
                    swal("Oops!", "Something went wrong. Please try again.", "error");
                }
            });

            const map = L.map("map", {
                // center: [-89.233742, 277.988262],
                center: [51.505, -0.09],
                // center: [17.342761, 78.552432],
                zoom: 13,
            });

            var imageUrl = $('#FloorStructureSource').text(),
                imageBounds = [
                    [-683, -600],
                    [150, -205],
                ];

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

                //$("#myModalFloorPlan").modal();

                var lat = ev.latlng.lat;
                var lng = ev.latlng.lng;

                $('input[name="latPosition"]').val(lat);
                $('input[name="lngPosition"]').val(lng);

            });

            $(".linkDoorBtn").on("click", function(ev) {

                //addFloorPlanDoors();

                // $(".leaflet-image-layer").css({"margin-left": "399px","margin-top": "138px"});



            });


            function updateDoorLocations(data) {

                data.forEach(element => {

                    if (element.itemMasterId) {

                        let Lmarker = L.marker([element.latPosition, element.lngPosition])
                            .addTo(map)
                            .on("click", function(ev) {

                                console.log(element.itemMasterId);
                                window.ReactNativeWebView && window.ReactNativeWebView.postMessage(JSON.stringify({
                                    doorId: element.itemMasterId,
                                    doorname: element.DoorType
                                }))

                            });
                        console.log(Lmarker);

                    }


                });

            }

            function getFloorPlanDoors(updateType = "") {
                let projectId = $('#projectId').val();
                $.ajax({
                    type: "GET",
                    url: $('#get-floor-plan-doors').text(),
                    data: {
                        _token: "{{ csrf_token() }}",
                        projectId: projectId,
                        floorplan_category_text: floorplan_category_text
                    },
                    dataType: 'Json',
                    success: function(data) {
                        if (data.status == "success") {

                            if (updateType == "") {
                                //(data.data);
                                updateDoorLocations(data.data);

                            } else if (updateType == "option") {
                                // updateDoorOptions(data.data);

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
            $('#floorplan_category').on('change', function() {
                var floorplan_category = $('#floorplan_category').val();
                var floorplan_category_text = $.trim($("#floorplan_category  option:selected").text());
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
                $('#floorPlanAdd').empty().append(html);
                getFloorPlanDoors();

                $.ajax({
                    type: "POST",
                    url: $('#floor-door-list').text(),
                    data: {
                        _token: "{{ csrf_token() }}",
                        floorplan_category_text: floorplan_category_text
                    },
                    dataType: 'Json',
                    success: function(data) {
                        if (data.status == "success") {


                        } else {
                            swal("Oops!", data.message, "error");
                        }
                    },
                    error: function(data) {
                        swal("Oops!", "Something went wrong. Please try again.", "error");
                    }
                });

                const map = L.map("map", {
                    // center: [-89.233742, 277.988262],
                    center: [51.505, -0.09],
                    // center: [17.342761, 78.552432],
                    zoom: 13,
                });

                var imageUrl = $('#FloorStructureSource').text(),
                    imageBounds = [
                        [-683, -600],
                        [150, -205],
                    ];

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

                    //$("#myModalFloorPlan").modal();

                    var lat = ev.latlng.lat;
                    var lng = ev.latlng.lng;

                    $('input[name="latPosition"]').val(lat);
                    $('input[name="lngPosition"]').val(lng);

                });

                console.log(map);

                $(".linkDoorBtn").on("click", function(ev) {

                    //addFloorPlanDoors();

                    // $(".leaflet-image-layer").css({"margin-left": "399px","margin-top": "138px"});



                });


                function updateDoorLocations(data) {

                    data.forEach(element => {

                        if (element.itemMasterId) {

                            let Lmarker = L.marker([element.latPosition, element.lngPosition])
                                .addTo(map)
                                //.bindPopup(htmlPopup)
                                .on("click", function(ev) {
                                    // //this.openPopup();
                                    console.log(element);
                                    window.ReactNativeWebView && window.ReactNativeWebView.postMessage(JSON.stringify({
                                        doorId: element.itemMasterId,
                                        doorname: element.DoorType
                                    }))
                                });
                            //console.log(Lmarker);

                        }


                    });

                }

                function getFloorPlanDoors(updateType = "") {
                    let projectId = $('#projectId').val();
                    $.ajax({
                        type: "GET",
                        url: $('#get-floor-plan-doors').text(),
                        data: {
                            _token: "{{ csrf_token() }}",
                            projectId: projectId,
                            floorplan_category_text: floorplan_category_text
                        },
                        dataType: 'Json',
                        success: function(data) {
                            if (data.status == "success") {

                                if (updateType == "") {
                                    //updateDoorOptions(data.data);
                                    updateDoorLocations(data.data);

                                } else if (updateType == "option") {
                                    //updateDoorOptions(data.data);

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
            });
        });
    </script>
@endsection
<script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
    integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
    crossorigin=""></script>
