<!DOCTYPE html>
<html>
    <head>
        <title>Intorduction PDF</title>
        <style>
            .cusTable {
                width: 100%;
                border-collapse: collapse;
            }
            table thead tr th {
                border-bottom: 1px solid #000000;
                text-align: left !important;
            }

            table th,
            table td {
                border: 0px solid #555;
                padding-left: 5px;
            }
            table tbody tr td {
                font-size:13px;
                padding:5px;
            }

            .imgClass {
                width: 150px;
                margin-left: 55%;

            }

            .col1 {
                width: 50%;
                background: ;
                padding: 10px;
                padding-left: 0px;
                font-size: 20px;
                margin-top: -80px;
            }

            .col2 {
                width: 50%;
                background: ;
                margin-left: 45%;
                padding: 10px;
            }

            .roright {
                margin-left: 50%;
                padding-bottom: 55px;
                margin-top: -110px;
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



            .footer2 {
                bottom: 0;
                padding-top: 130px;
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
            @page {
                size: 710pt 950pt;
            }
            .bomlogo{
            min-width: 100px;
            max-width: 120px;
            min-height: 100px;
            max-height: 120px;
            }
        </style>
    </head>
    <body style="position: relative;">
        <!-- Page 3 Start -->
           <h2>More Information</h2>
            <p>{{ $MoreInformation }}</p>

    </body>
</html>
