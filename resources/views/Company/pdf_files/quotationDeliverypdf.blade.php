<!DOCTYPE html>
<html>
    <head>
        <title>quotaion Summary PDF</title>
        <style>

            .cusTable {
                width: 100%;
                border-collapse: collapse;
            }

            table {
                width: 100%;
                border-collapse: collapse;
                font-family: Arial, sans-serif;
                font-size: 14px;
            }
            .table-bordered td, .table-bordered th {
                border: 1px solid #ddd;
                padding: 8px;
                vertical-align: top;
            }
            .tbl_color {
                background-color: #f2f2f2;
                font-weight: bold;
                width: 25%;
            }
            td span {
                display: block;
                word-wrap: break-word;
            }
            h1, h2 {
                margin: 0;
                padding: 0;
            }
            p {
                margin: 10px 0;
            }

            .imgClass {
                width: 180px;
                margin-left: 55%;
                /* border-bottom:1px solid black;
                border-right:1px solid black; */

            }

            .iconbig {
                border: 0px solid;
                background: ;
            }

            .col1 {
                width: 50%;
                background: ;
                padding: 10px;
                padding-left: 0px;
                font-size: 20px;
            }

            .col2 {
                width: 50%;
                background: ;
                margin-left: 45%;
                padding: 10px;
            }

            .roright {
                margin-left: 78%;
                padding-bottom: 55px;
                margin-top: -90px;
            }

            .table1 {
                border-collapse: collapse;
                border: 0px solid #ddd;

            }

            .cusTable>th,
            .cusTable>td {
                text-align: left !important;
                padding: 8px;
            }

            .into1 {
                margin-left: 5px;
            }



            .page1_foot {
                margin-top: 55px;
                background: ;
                margin-bottom: 55px;
            }

            .page1_msg {
                font-size: 20px;
            }

            .page2_table {
                width: 60%;
            }

            .page2_foot {
                margin-left: 150px;
            }

            .page2_table2 {
                width: 70%;
            }

            .page3_tr {
                border-bottom: 5px solid black;
                background: ;
                text-align: left !important;
            }

            .footer2 {
                bottom: 0;
                /* padding-top: 100px; */
            }

            .rightInfo {
                width: 150px !important;
                border: 1px solid;
                padding: 10px;
            }

            .rightTbl>tr>td {
                border-spacing: 1px;
                border: 1px solid !important;
                padding: 0px;
            }
            #main_body{
                padding-left:45px;
            }
            @page {
                size: 710pt 925pt;
            }
            .footImg{
                width:80px;
                height:80px;
                margin-top:-140px;
                margin-left:748px;
            }
            .bomlogo{
            min-width: 100px;
            max-width: 120px;
            min-height: 100px;
            max-height: 120px;
            }
        </style>
    </head>
    <body>
        <div class="col2 iconbig">
            @if(!empty($comapnyDetail->ComplogoBase64))
            <img src="{{$comapnyDetail->ComplogoBase64}}" class="imgClass" alt="logo"  style="position:absolute; top:10px; right: 10px;"/>
            @else
            <!-- <img src="{{Base64Image('defaultImg')}}" class="imgClass" alt="logo" /> -->
            {!! Base64Image('defaultImg') !!}
            @endif
        </div>
        {!! htmlspecialchars_decode($htmlPreview) !!}
    </body>
</html>
