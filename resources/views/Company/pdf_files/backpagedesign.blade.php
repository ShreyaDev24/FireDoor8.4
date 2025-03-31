<!DOCTYPE html>
<html>
    <head>
        <title>Intorduction PDF</title>
        <style>
            @page {
                size: 710pt 950pt;
            }
            table {
                border-collapse: collapse;
            }
            table, td, th {
            border: 1px solid #000 !important;
            text-align: left;
            background-color: #ecedef;
            }
            th, td {
                height: 45px;
                padding: 6px;
            }
            table.fir-dr-tbl {
                margin: 0 auto;
            }
            .mytableclass td{
                background: #fff;
            }
        </style>
    </head>
    <body style="position: relative;">
        {!! htmlspecialchars_decode($backpage) !!}
    </body>
</html>
