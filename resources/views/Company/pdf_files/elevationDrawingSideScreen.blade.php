<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elevation Diagram</title>
    <style>
        body {
            margin: -45px -25px -25px;
        }

        #NoBorder {
            border-collapse: collapse;
            width: 90% !important;
        }

        #WithBorder {
            width: 100%;
            border-collapse: collapse;
        }

        #WithBorder td {
            border: 1px solid #000;
        }

        .imgClass {
            width: 50px;
            text-align: center;
            padding: 5px;
        }

        .marImg {
            width: 60px;
        }

        /* #main {
            font-size: 10px;
            padding: 20px 40px 40px 40px;
            width: 100%;
        } */

        #main {
            font-size: 10px;
            padding: 20px 40px 40px 40px;
            width: 100%;
            position: absolute;
            top: 0;
        }

        .tbl1 {
            padding-right: 8px;

        }

        .tbl1 td {
            padding-left: 5px !important;
        }

        .doorImg {
            width: 70% !important;

            max-width: 600px;
        }

        .doorImgBox {
            text-align: center;
            margin-top: 10px;
        }

        #section-right {
            float: right;
            /* margin-left:1230px; */
            width: 25%;

        }

        #section-left {
            float: left;
            /* margin-right:300px; */
            width: 75%;
        }

        .tbl2 {
            font-size: 15px;

        }

        th {
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

        #footer {
            position: absolute;
            bottom: -45px;
            font-size: 9px;
            width: 100%
        }

        .tbl4 {
            width: 100%;
        }

        @page {
            /* size: 710pt 600pt; */
            /* size: 875pt 1140pt; */
            size: 1260pt 860pt;
        }

        .page-break {
            page-break-after: always;
        }

        .tblTitle {
            width: 130px;
        }

        .dicription_blank {
            padding-left: 5px;
        }



        /* Fanlinght CSS */
        .fanlightframe_t1 {
            margin-left: 75px;
            position: absolute;
            width: 85px;
            font-size: 5px;
            margin-top: 35px;
        }

        .fanlightframe_t2 {
            position: absolute;
            width: 85px;
            font-size: 5px;
            margin-left: 75px;
            margin-top: 60px;
        }

        .fanlightframe_t3 {
            position: absolute;
            width: 85px;
            font-size: 5px;
            margin-left: 75px;
            margin-top: 80px;
        }

        .fanlightframe_t4 {
            position: absolute;
            width: 85px;
            font-size: 5px;
            margin-left: 75px;
            margin-top: 100px;
        }

        .fanlightframe_t5 {
            position: absolute;
            width: 57px;
            font-size: 5px;
            margin-left: 65px;
            margin-top: 125px;
        }

        .fanlightframe_t6 {
            position: absolute;
            width: 57px;
            font-size: 5px;
            margin-left: 65px;
            margin-top: 153px;
        }

        .fanlightframe_t7 {
            position: absolute;
            width: 57px;
            font-size: 5px;
            margin-left: 65px;
            margin-top: 190px;
        }

        .fanlightframe_t8 {
            position: absolute;
            width: 57px;
            font-size: 5px;
            margin-left: 65px;
            margin-top: 235px;
        }

        .fanlightframe_t9 {
            position: absolute;
            width: 57px;
            font-size: 5px;
            margin-left: 295px;
            margin-top: 90px;
        }

        /* vision panel */
        .visionpanel_t1 {
            position: absolute;
            width: 80px;
            font-size: 8px;
            margin-left: -250px;
            margin-top: -130px;
        }

        .visionpanel_t2 {
            position: absolute;
            width: 90px;
            font-size: 15px;
            margin-left:-290px;
            margin-top: -85px;
        }

        .visionpanel_t3 {
            position: absolute;
            width: 80px;
            font-size: 8px;
            margin-left: -270px;
            margin-top: -22px;
        }

        .visionpanel_t4 {
            position: absolute;
            width: 80px;
            font-size: 8px;
            margin-left: -255px;
            margin-top: 40px;
        }

        .visionpanel_t5 {
            position: absolute;
            color: red;
            /*width:57px;*/
            font-size: 13px;
            margin-left: 165px !important;
            margin-top: 480px;
        }

        .visionpanel_t6 {
            position: absolute;
            width: 57px;
            font-size: 5px;
            /* margin-left: 98px; */
            margin-top: 220px;
        }

        /* Frame */
        /* .frame_sd_t1 {
            position: absolute;
            width: 100px;
            font-size: 8px;
            margin-left: 0px;
            margin-top: 0px;
        } */

        .frame_sd_t2 {
            position: absolute;
            width: 100px;
            font-size: 8px;
            margin-left: 100px;
            margin-top: -15px;
        }

        .frame_sd_t3 {
            position: absolute;
            width: 100px;
            font-size: 8px;
            margin-left: 500px;
            margin-top: 0px;
        }

        .frame_sd_t4 {
            position: absolute;
            width: 50px;
            font-size: 8px;
            margin-left: 615px;
            margin-top: 0px;
        }

        .frame_dd_t1 {
            position: absolute;
            width: 100px;
            font-size: 8px;
            margin-left: 0px;
            margin-top: 0px;
        }

        .frame_dd_t2 {
            position: absolute;
            width: 100px;
            font-size: 8px;
            margin-left: 112px;
            margin-top: 0px;
        }

        .frame_dd_t3 {
            position: absolute;
            width: 80px;
            font-size: 8px;
            margin-left: 250px;
            margin-top: 0px;
        }

        .frame_dd_t4 {
            position: absolute;
            width: 50px;
            font-size: 8px;
            margin-left: 365px;
            margin-top: 0px;
        }

        .frame_dd_t5 {
            position: absolute;
            width: 57px;
            font-size: 8px;
            margin-left: 675px;
            margin-top: 0px;
        }

        .frame_sd_z1 {
            position: absolute;
            width: 50px;
            font-size: 8px;
            margin-left: 325px;
            margin-top: 200px;
        }

        .frame_sd_z2 {
            position: absolute;
            width: 50px;
            font-size: 8px;
            margin-left: 325px;
            margin-top: 220px;
        }

        .frame_sd_z3 {
            position: absolute;
            width: 50px;
            font-size: 8px;
            margin-left: 325px;
            margin-top: 180px;
        }

        .frame_sd_z4 {
            position: absolute;
            width: 50px;
            font-size: 8px;
            margin-left: 120px;
            margin-top: 160px;
        }

        .frame_sd_z5 {
            position: absolute;
            width: 50px;
            font-size: 8px;
            margin-left: 520px;
            margin-top: 160px;
        }

        .frame_sd_DD1 {
            position: absolute;
            width: 50px;
            font-size: 8px;
            margin-left: 350px;
            margin-top: 160px;
        }

        .frame_sd_DD2 {
            position: absolute;
            width: 50px;
            font-size: 8px;
            margin-left: 300px;
            margin-top: 120px;
        }

        .frame_sd_DD3 {
            position: absolute;
            width: 50px;
            font-size: 8px;
            margin-left: 400px;
            margin-top: 120px;
        }

        .frame_sd_DD4 {
            position: absolute;
            width: 50px;
            font-size: 8px;
            margin-left: 510px;
            margin-top: 120px;
        }

        .frame_sd_DD5 {
            position: absolute;
            width: 50px;
            font-size: 8px;
            margin-left: 185px;
            margin-top: 140px;
        }

        .frame_sd_DD6 {
            position: absolute;
            width: 50px;
            font-size: 8px;
            margin-left: 356px;
            margin-top: 100px;
        }


        /*
        td.mytabledata > p:nth-of-type(4)::after {
            content: "";
            position: absolute;
            width: 60px;
            height: 1px;
            background: #e71111;
            top: 0px;
            right: -115px;
            transform: rotate(60deg);
        }

        td.mytabledata > p:nth-of-type(4)::before {
            content: "";
            position: absolute;
            width: 40px;
            height: 1px;
            background: #e71111;
            top: -26px;
            right: -70px;
        }

        td.mytabledata > p:nth-of-type(3)::after {
            content: "";
            position: absolute;
            width: 60px;
            height: 1px;
            background: #e71111;
            top: 0px;
            right: -115px;
            transform: rotate(60deg);
        }

        td.mytabledata > p:nth-of-type(3)::before {
            content: "";
            position: absolute;
            width: 40px;
            height: 1px;
            background: #e71111;
            top: -26px;
            right: -70px;
        }

        td.mytabledata > p:nth-of-type(2)::after {
            content: "";
            position: absolute;
            width: 60px;
            height: 1px;
            background: #e71111;
            top: 0px;
            right: -115px;
            transform: rotate(60deg);
        }

        td.mytabledata > p:nth-of-type(2)::before {
            content: "";
            position: absolute;
            width: 40px;
            height: 1px;
            background: #e71111;
            top: -26px;
            right: -70px;
        }
        td.mytabledata > p:nth-of-type(1)::after {
            content: "";
            position: absolute;
            width: 60px;
            height: 1px;
            background: #e71111;
            top: 0px;
            right: -115px;
            transform: rotate(60deg);
        }

        td.mytabledata > p:nth-of-type(1)::before {
            content: "";
            position: absolute;
            width: 40px;
            height: 1px;
            background: #e71111;
            top: -26px;
            right: -70px;
        } */


        .frame_dd_t1 {
            position: absolute;
            width: 100px;
            font-size: 8px;
            margin-left: -75px;
            margin-top: 30px;
            transform: rotate(270deg);
        }

        td.mytabledata > p:nth-of-type(1)::before {
            content: "";
            position: absolute;
            width: 110px;
            height: 1px;
            background: #e71111;
            top: 15px;
            right: 42px;
        }
        td.mytabledata > .frame_dd_t1_Rebated_Frame::before {
            width: 64px !important;
        }
        .frame_dd_t2 {
            position: absolute;
            width: 94px;
            font-size: 8px;
            margin-left: 9px;
            margin-top: 12px;
        }
        .frame_dd_t2_Rebated_Frame {
            margin-top: 12px !important;
        }

        td.mytabledata > p:nth-of-type(2)::after {
            content: "";
            position: absolute;
            width: 26px;
            height: 1px;
            background: #e71111;
            top: 11px;
            right: 70px;
        }


        .frame_dd_t3 {
            position: absolute;
            width: 100px;
            font-size: 8px;
            margin-left: -57px;
            margin-top: 1px;
            transform: rotate(270deg);
        }


        td.mytabledata > p:nth-of-type(3)::after{
            content: "";
            position: absolute;
            width: 39px;
            height: 1px;
            background: #e71111;
            top: 14px;
            right: 71px;
        }
        .frame_dd_t3_Rebated_Frame {
            margin-top: 31px !important;
        }
        .frame_dd_t3_Rebated_Frame::after {
            content: "";
            position: absolute;
            width: 23px;
            height: 1px;
            background: #e71111;
            top: -2px;
            right: 81px;
        }


        .frame_dd_t4 {
            position: absolute;
            width: 94px;
            font-size: 8px;
            margin-left: 26px;
            margin-top: 141px;
        }

        .frame_dd_t4_Rebated_Frame {
            margin-top: 95px !important;
        }

        td.mytabledata > p:nth-of-type(4)::after {
            content: "";
            position: absolute;
            width: 11px;
            height: 1px;
            background: #e71111;
            top: -1px;
            right: 77px;
        }

        td.mytabledata_sd > p:nth-of-type(1) {
            position: absolute;
            width: 100px;
            font-size: 8px;
            margin-left: -82px;
            margin-top: 67px;
            transform: rotate(270deg);
        }

        td.mytabledata_sd > p:nth-of-type(1)::before {
            content: "";
            position: absolute;
            width: 183px;
            height: 1px;
            background: #e71111;
            top: 15px;
            right: 7px;
        }

        td.mytabledata_sd > .frame_dd_t1_sd_Rebated_Frame::before {
            width: 106px !important;
        }

        td.mytabledata_sd > p:nth-of-type(2) {
            position: absolute;
            width: 94px;
            font-size: 8px;
            margin-left: 14px;
            margin-top: 12px;
        }

        td.mytabledata_sd > .frame_dd_t2_sd_Rebated_Frame {
            margin-top: 12px !important;
        }


        td.mytabledata_sd > p:nth-of-type(2)::after {
            content: "";
            position: absolute;
            width: 44px;
            height: 1px;
            background: #e71111;
            top: 13px;
            right: 67px;
        }



        td.mytabledata_sd > p:nth-of-type(3) {
            position: absolute;
            width: 100px;
            font-size: 8px;
            margin-left: -65px;
            margin-top: 14px;
            transform: rotate(270deg);
        }

        td.mytabledata_sd > p:nth-of-type(3)::after {
            content: "";
            position: absolute;
            width: 64px;
            height: 1px;
            background: #e71111;
            top: 12px;
            right: 60px;
        }

        td.mytabledata_sd > p:nth-of-type(4) {
            position: absolute;
            width: 94px;
            font-size: 8px;
            margin-left: 40px;
            margin-top: 218px;
        }

        td.mytabledata_sd > .frame_dd_t4_sd_Rebated_Frame {
            margin-top: 142px !important;
        }

        td.mytabledata_sd > p:nth-of-type(4)::after {
            content: "";
            position: absolute;
            width: 18px;
            height: 1px;
            background: #e71111;
            top: -1px;
            right: 76px;
        }

        .frame_dd_t5 {
            position: absolute;
            width: 100px;
            font-size: 13px;
            margin-left: 9px;
            margin-top: -3px;
            color: red;
            transform: rotate(270deg);
        }

        td.mytabledata > p:nth-of-type(5)::after {
            content: "";
            position: absolute;
            width: 37px;
            height: 1px;
            background: #e71111;
            top: 14px;
            right: 71px;
        }

        td.mytabledata_sd > p:nth-of-type(5) {
            position: absolute;
            width: 100px;
            font-size: 13px;
            margin-left: 40px;
            margin-top: 11px;
            transform: rotate(270deg);
            color: red;
        }

        td.mytabledata_sd > p:nth-of-type(5)::after {
            content: "";
            position: absolute;
            width: 63px;
            height: 1px;
            background: #e71111;
            top: 14px;
            right: 60px;
        }

        p.frame_dd_t3.frame_dd_t3_Plant_on_Stop{
            position: absolute !important;
            width: 113px !important;
            font-size: 8px !important;
            margin-left: 0px !important;
            margin-top: 35px !important;
            transform: rotate(270deg);
        }

        p.frame_dd_t3.frame_dd_t3_Plant_on_Stop::after{
            content: "";
            position: absolute !important;
            width: 40px !important;
            height: 1px !important;
            background: #e71111;
            top: -5px !important;
            right: 83px !important;
        }

        p.frame_dd_t3_sd_Plant_on_Stop {
            position: absolute !important;
            width: 113px !important;
            font-size: 8px !important;
            margin-left: 15px !important;
            margin-top: 73px !important;
            transform: rotate(270deg);
        }

        p.frame_dd_t3_sd_Plant_on_Stop::after {
            content: "";
            position: absolute !important;
            width: 69px !important;
            height: 1px !important;
            background: #e71111;
            top: -5px !important;
            right: 72px !important;
        }

        .Door_right_strip_SD {
            margin-left: -35.5px !important;
        }

        .Door_left_strip_SD {
            margin-left: 15px !important;
        }
        .Door_right_strip_DD {
            margin-left: -57px !important;
        }
        .Door_left_strip_DD {
            margin-left: -4px !important;
        }

        /* .Frame_right_strip_DD {
            margin-left: -52px !important;
        } */

        p.frame_dd_t2.sidelight {
            margin-left: 191px !important;
        }

        p.frame_dd_t3.sidelight {
            margin-left: 180px !important;
        }

        p.frame_dd_t4.sidelight {
            margin-left: 209px !important;
        }

        p.frame_dd_t5.sidelight {
            margin-left: 189px !important;
        }



        /* .Door_right_strip_SD.sidelight {
            margin-left: -35.5px !important;
        }

        .Door_left_strip_SD.sidelight {
            margin-left: 15px !important;
        }
         */
        .Door_right_strip_DD_sidelight {
            margin-left: 3px !important;
        }
        .Door_left_strip_DD_sidelight {
            margin-left: 33px !important;
        }


        p.frame_dd_t2_sd_sidelight {
            margin-left: 236px !important;
        }
        p.frame_dd_t3_sd_sidelight {
            margin-left: 238px !important;
        }
        p.frame_dd_t4_sd_sidelight {
            margin-left: 262px !important;
        }
        p.frame_dd_t5_sd_sidelight {
            margin-left: 262px !important;
        }




    </style>
</head>

<body>

    {!! htmlspecialchars_decode($elevSideScreenTbl) !!}
</body>

</html>
