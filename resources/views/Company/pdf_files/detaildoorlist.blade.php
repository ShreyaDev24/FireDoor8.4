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
            <div class="col2" style="margin-top:70px;">
                @if(!empty($comapnyDetail->ComplogoBase64))
                <img src="{{$comapnyDetail->ComplogoBase64}}" class="imgClass" alt="Logo" style="position:absolute; top:10px; right: 10px;"/>
                @else
                <!-- <img src="{{Base64Image('defaultImg')}}" class="imgClass" alt="Logo"/> -->
                {!! Base64Image('defaultImg') !!}
                @endif
            </div>
            <div class="col1">
                <h3>Detailed Door Listing</h3>
                @if(!empty($project->ProjectName))<h3>Project :  {{ $project->ProjectName }}</h3> @endif
            </div>
            <div class="roright">
                <table class="rightTbl">
                    <tr>
                        <td><b>Ref</b></td>
                        <td>:</td>
                        <td>@if(!empty($quotaion->QuotationGenerationId)) {{ $quotaion->QuotationGenerationId }} @endif</td>
                    </tr>
                    <tr>
                        <td><b>Date</b></td>
                        <td>:</td>
                        <td>{{ date('Y-m-d') }}</td>
                    </tr>
                    <tr>
                        <td><b>Revision</b></td>
                        <td>:</td>
                        <td>@if(!empty($version)) {{ $version }} @endif</td>
                    </tr>
                </table>
            </div>
            <table class="table table-bordered cusTable" style="margin-top: 70px;">
                <thead>
                    <tr class="page3_tr">
                        <th scope="col">Door</th>
                        <th scope="col">Description</th>
                        <th scope="col">D/Set</th>
                        @if($HideCosts == 0)
                        <th scope="col">Doorset Price</th>
                        <th scope="col">Ironm'y Price</th>
                        @endif
                        <th scope="col">Total Price</th>
                    </tr>
                </thead>
                <tbody>
                    {!! $a2 !!}
                </tbody>
            </table>
        <!-- Page 3 End -->

    </body>
</html>
