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
                <h3>Non Configurable Item</h3>
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
                        <th>Line</th>
                        <th>Name</th>
                        <th>Product Code</th>
                        <th>Description</th>
                        <th>Unit</th>
                        <th>Qty</th>
                        <th>Price</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @if (!empty($nonConfigData) && count($nonConfigData) > 0)
                        <?php
                        $SI = 1; ?>
                        @foreach ($nonConfigData as $value)
                            <tr>
                                <td>{{ $SI++; }}</td>
                                <td>{{ $value->name }}</td>
                                <td>{{ $value->product_code }}</td>
                                <td>{{ $value->description }}</td>
                                <td>{{ $value->unit }}</td>
                                <td>{{ $value->quantity }}</td>
                                <td>{{ floatval($value->price) }}</td>
                                <td>{{ floatval($value->quantity) * floatval($value->price) }}</td>
                            </tr>
                        @endforeach
                    @endif

                </tbody>
            </table>
        <!-- Page 3 End -->

    </body>
</html>
