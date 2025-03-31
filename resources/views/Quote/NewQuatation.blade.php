<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elevation Diagram</title>
    <style>
        body{
            margin:-45px -25px -25px;
        }
        #NoBorder{
            border-collapse: collapse;
            width:90% !important;
        }
        #WithBorder{
            width: 100%;
            border-collapse: collapse;
        }
        #WithBorder td{
            border: 1px solid #000;
        }
        .imgClass{
            width:50px;
            text-align:center;
            padding:5px;
        }
        .marImg{
            width:60px;
        }
        #main{
            font-size:10px;
            display: flex;
        }
        .tbl1{
            padding-right:8px;

        }
        .tbl1 td{
            padding-left:5px !important;
        }
        .doorImg{
            width:70% !important;
        }
        .doorImgBox{
            text-align:center;
            margin-top:10px;
        }

        #section-right{
            margin-top:5px;

            float:right;
            margin-left:1230px;
            width:25%;
        }

        #section-left{
            float:left;
            /* margin-right:300px; */
            width:82%;
        }
        .tbl2{
            font-size:15px;

        }
        th{
            text-align: left;
        }
        .dicription_grey {
            background: #ececec;
            font-size: 10px;
            padding-left: 6px;
            width: 50%;
        }
        .tbl_color {
            background: #ececec;
            font-weight: bold;
        }
        #footer{
            position: relative;
            clear: both;
            /* padding-bottom:125px; */
            width:100%;
        }
        .tbl4{
            width:100%;
        }
        @page {
            /* size: 710pt 600pt; */
            /* size: 875pt 1140pt; */
            size: 1260pt 860pt;
        }

        .page-break {
            page-break-after: always;
        }
        .tblTitle{
            width:130px;
        }
        .dicription_blank{
            padding-left:5px;
        }



        /* Fanlinght CSS */
            .fanlightframe_t1{
                margin-left:75px;
                position:absolute;
                width:85px;
                font-size:5px;
                margin-top:35px;
            }
            .fanlightframe_t2{
                position:absolute;
                width:85px;
                font-size:5px;
                margin-left:75px;
                margin-top:60px;
            }
            .fanlightframe_t3{
                position:absolute;
                width:85px;
                font-size:5px;
                margin-left:75px;
                margin-top:80px;
            }
            .fanlightframe_t4{
                position:absolute;
                width:85px;
                font-size:5px;
                margin-left:75px;
                margin-top:100px;
            }
            .fanlightframe_t5{
                position:absolute;
                width:57px;
                font-size:5px;
                margin-left:65px;
                margin-top:125px;
            }
            .fanlightframe_t6{
                position:absolute;
                width:57px;
                font-size:5px;
                margin-left:65px;
                margin-top:153px;
            }
            .fanlightframe_t7{
                position:absolute;
                width:57px;
                font-size:5px;
                margin-left:65px;
                margin-top:190px;
            }
            .fanlightframe_t8{
                position:absolute;
                width:57px;
                font-size:5px;
                margin-left:65px;
                margin-top:235px;
            }
            .fanlightframe_t9{
                position:absolute;
                width:57px;
                font-size:5px;
                margin-left:295px;
                margin-top:90px;
            }

        /* vision panel */
            .visionpanel_t1{
                position:absolute;
                width:80px;
                font-size:8px;
                margin-left:105px;
                margin-top:183px;
            }
            .visionpanel_t2{
                position:absolute;
                width:90px;
                font-size:8px;
                margin-left:50px;
                margin-top:220px;
            }
            .visionpanel_t3{
                position:absolute;
                width:80px;
                font-size:8px;
                margin-left:50px;
                margin-top:283px;
            }
            .visionpanel_t4{
                position:absolute;
                width:80px;
                font-size:8px;
                margin-left:75px;
                margin-top:353px;
            }

            .visionpanel_t5{
                position:absolute;
                color: red;
                /*width:57px;*/
                font-size:13px;
                margin-left:217px;
                margin-top:432px;
            }

            .visionpanel_t6{
                position:absolute;
                width:57px;
                font-size:5px;
                margin-left:98px;
                margin-top:220px; 
            }

        /* Frame */
        .frame_sd_t1{
            position:absolute;
            width:100px;
            font-size:8px;
            margin-left:5px;
            margin-top:93px;
        }
        .frame_sd_t2{
            position:absolute;
            width:100px;
            font-size:8px;
            margin-left:140px;
            margin-top:10px;
        }
        .frame_sd_t3{
            position:absolute;
            width:100px;
            font-size:8px;
            margin-left:395px;
            margin-top:10px;
        }
        .frame_sd_t4{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:615px;
            margin-top:10px;
        }

        .frame_dd_t1{
            position:absolute;
            width:100px;
            font-size:8px;
            margin-left:0px;
            margin-top:90px;
        }

        .frame_dd_t2{
            position:absolute;
            width:100px;
            font-size:8px;
            margin-left:112px;
            margin-top:50px;
        }

        .frame_dd_t3{
ouioiuo            width:40px;
            font-size:8px;
            margin-left:250px;
            margin-top:50px;
        }

        .frame_dd_t4{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:365px;
            margin-top:50px;
        }

        .frame_dd_t5{
            position:absolute;
            width:57px;
            font-size:8px;
            margin-left:675px;
            margin-top:10px;
        }

        .frame_sd_z1{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:665px;
            margin-top:55px;
        }
        .frame_sd_z2{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:665px;
            margin-top:255px;
        }
        .frame_sd_z3{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:665px;
            margin-top:150px;
        }
        .frame_sd_z4{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:345px;
            margin-top:75px;
        }
        .frame_sd_z5{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:945px;
            margin-top:75px;
        }

        .frame_sd_DD1{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:635px;
            margin-top:260px;
        }
        .frame_sd_DD2{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:665px;
            margin-top:145px;
        }
        .frame_sd_DD3{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:555px;
            margin-top:145px;
        }
        .frame_sd_DD4{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:845px;
            margin-top:145px;
        }
        .frame_sd_DD5{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:885px;
            margin-top:50px;
        }
        .frame_sd_DD6{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:565px;
            margin-top:70px;
        }
    </style>
</head>
<body>
    @foreach($ed as $tt)
<div id="section-right">
                        <table id="WithBorder" class="tbl2">
                            <tbody>
                                <tr>
                                    <td class="tbl_color tblTitle" style="font-weight: normal;">SELECT <br>Door Type</td>
                                    <td class="dicription_blank"><b>Type {{$tt->DoorType}}</b></td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="WithBorder" class="tbl3">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">General</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Door Type</td>
                                    <td class="dicription_blank">{{$tt->DoorType}}</td>
                                  
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Fire Rating</td>
                                    <td class="dicription_blank">{{$tt->FireRating}}</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Mark</td>
                                    <td class="dicription_blank">D-001</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Type Mark </td>
                                    <td class="dicription_blank">IND-1A</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Level </td>
                                    <td class="dicription_blank">Working Level - Ground Floor</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">S.O. Width</td>
                                    <td class="dicription_blank">{{$tt->SOWidth}}</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">S.O. Height</td>
                                    <td class="dicription_blank">{{$tt->SOHeight}}</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Door leaf Finish</td>
                                    <td class="dicription_blank">{{$tt->DoorLeafFinish}}</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Vision panel</td>
                                    <td class="dicription_blank">{{$tt->Leaf1VisionPanel}}</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Vision panel</td>
                                    <td class="dicription_blank">{{$tt->Leaf1VisionPanel}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    </div>
@endforeach
</body>
</html>