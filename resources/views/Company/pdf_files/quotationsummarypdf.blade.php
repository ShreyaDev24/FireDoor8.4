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
                width:90px;
                height:90px;
                margin-top:-155px;
                margin-left:748px;
            }

            .bomlogo {
                min-width: 100px;
                max-width: 120px;
                min-height: 100px;
                max-height: 120px;
            }

        </style>
    </head>
    <body style="position: relative;">
        <!-- Page 2 Start -->
            <div class="col2 iconbig">
                @if(!empty($comapnyDetail->ComplogoBase64))
                <img src="{{$comapnyDetail->ComplogoBase64}}" class="imgClass" alt="logo"  style="position:absolute; top:10px; right: 10px;"/>
                @else
                <!-- <img src="{{Base64Image('defaultImg')}}" class="imgClass" alt="logo" /> -->
                {!! Base64Image('defaultImg') !!}
                @endif
            </div>
            <div id="main_body">
            <div class="col1">
                <h2>
                    @php
                        if(!empty($project->ProjectName)){
                            $projectName =  $project->ProjectName;
                        } else {
                            $projectName = '';
                        }

                        if(!empty(isset($project->project_ironmongery))){
                            $ProjectIronmongery =  $project->project_ironmongery;
                        } else {
                            $ProjectIronmongery = '';
                        }
                        if(!empty(isset($project->coc))){
                            $coc =  $project->coc;
                        } else {
                            $coc = '';
                        }

                    @endphp
                </h2>
                <h2 style="white-space: nowrap;">
                @php
                    if(!empty($quotaion->QuotationGenerationId)){
                        $QuotationGenerationId = $quotaion->QuotationGenerationId ;
                    } else {
                        $QuotationGenerationId = '';
                    }
                @endphp
                </h2>

            </div>

            @php
            // Format Transportation Cost outside the conditional block
            if (!empty($transportationCost) && is_numeric($transportationCost)) {
                $transportationCostFormatted = $currency . round((float) $transportationCost, 2);
            } else {
                $transportationCostFormatted = $currency . '0.00';
            }

            if(!empty( $pdf2->msg)){
                $ExtractPdf2 =   $pdf2->msg;
                if(!empty($totDoorsetType)){
                    $totDoorsetType = $totDoorsetType;
                } else {
                    $totDoorsetType = '';
                }

                if(!empty($totIronmongerySet)){
                    $totIronmongerySet = $totIronmongerySet;
                } else {
                    $totIronmongerySet = '';
                }

                if (!empty($screenDataprice) && is_numeric($screenDataprice)) {
                    $screenDataprice = $currency . round((float) $screenDataprice, 2);
                } else {
                    $screenDataprice = $currency . '0.00';
                }
                if (!empty($totDoorsetPrice) && is_numeric($totDoorsetPrice)) {
                    $totDoorsetPrice = $currency . round((float) $totDoorsetPrice, 2);
                } else {
                    $totDoorsetPrice = $currency . '0.00';
                }

                if (!empty($totIronmongaryPrice) && is_numeric($totIronmongaryPrice)) {
                    $totIronmongaryPrice = $currency . round((float) $totIronmongaryPrice, 2);
                } else {
                    $totIronmongaryPrice = $currency . '0.00';
                }

                if (!empty($nonConfigDataPrice) && is_numeric($nonConfigDataPrice)) {
                    $nonConfigDataPrice = $currency . round((float) $nonConfigDataPrice, 2);
                } else {
                    $nonConfigDataPrice = $currency . '0.00';
                }

                if(!empty($nonConfigDataCount)){
                    $nonConfigDataCount = round($nonConfigDataCount,2);
                } else {
                    $nonConfigDataCount = '';
                }

                if(!empty($nettot)){
                    $nettot = $currency . round((float) $nettot,2);
                } else {
                    $nettot = $currency . '0.00';
                }

                if(!empty($QSTI->PaymentTerms)){
                    $PaymentTerms = $QSTI->PaymentTerms;
                } else {
                    $PaymentTerms = '';
                }

                if(!empty($QSTI->NoOfDeliveries)){
                    $NoOfDeliveries = $QSTI->NoOfDeliveries;
                } else {
                    $NoOfDeliveries = '';
                }
                if(!empty($customerContact->FirstName) && !empty($customerContact->LastName)){
                    $customerName = $customerContact->FirstName.' '.$customerContact->LastName;
                } else {
                    $customerName = '';
                }
                if(!empty($user->FirstName) && !empty($user->LastName)){
                $userName = $user->FirstName.' '.$user->LastName;
                } else {
                $userName = '';
                }
                if(empty($totIronmongaryPrice)){
                    $totIronmongaryPrice = $currency . '0.00';
                }
                if(empty($totIronmongerySet)){
                    $totIronmongerySet = '0';
                }

                if(isset($contractorName)){
                    $contractorName = $contractorName;
                }else{
                    $contractorName = '';
                }

                $str = ['[UserName]','[ProjectName]','[QuotationGenerationId]','[TotalDoorSet]','[TotalIronmongery]','[TotalNonConfig]','[TotalScreenSet]','[TotalDoorValue]','[TotalIronmongeryValue]','[TotalNonConfigValue]','[TotalScreenValue]','[TransportationCost]','[NetSubTotal]','[NetTotal]','[PaymentTerms]','[NoOfDeliveries]','[customerName]','[coc]', '[ContractorName]','ProjectIronmongery'];
                $rplc =[$userName,$projectName,$QuotationGenerationId,$totDoorsetType,$totIronmongerySet,$nonConfigDataCount,$ScreenSetQty,$totDoorsetPrice,$totIronmongaryPrice,$nonConfigDataPrice,$screenDataprice,$transportationCostFormatted,$nettot,$nettot,$PaymentTerms,$NoOfDeliveries,$customerName, $coc,  $contractorName,$ProjectIronmongery];

                $output = str_replace($str,$rplc,$ExtractPdf2);

                // If Transportation Cost placeholder is not in the template, inject it after Total Screen Value
                if (strpos($output, 'Transportation Cost:') === false) {
                    // Create the Transportation Cost row HTML matching the existing table structure
                    $transportationRow = "\n                        <tr>\n"
                        . "                            <td><span style=\"font-size:18px\">Transportation Cost:</span></td>\n"
                        . "                            <td style=\"text-align:right\"><span style=\"font-size:18px\">" . $transportationCostFormatted . "</span></td>\n"
                        . "                        </tr>";

                    // Find and inject after Total Side Screen Value or Total Screen Value row
                    $patterns = [
                        '/(<tr>\s*<td>.*?Total Side Screen Value:.*?<\/td>\s*<td[^>]*>.*?' . preg_quote($screenDataprice, '/') . '.*?<\/td>\s*<\/tr>)/is',
                        '/(<tr>\s*<td>.*?Total Screen Value:.*?<\/td>\s*<td[^>]*>.*?' . preg_quote($screenDataprice, '/') . '.*?<\/td>\s*<\/tr>)/is',
                    ];

                    foreach ($patterns as $pattern) {
                        if (preg_match($pattern, $output)) {
                            $output = preg_replace($pattern, '$1' . $transportationRow, $output, 1);
                            break;
                        }
                    }
                }

                echo $output;
            }
            @endphp
            @if (strpos($ExtractPdf2, 'COC:') === false)
                <table class="table table-bordered">
                    <tr>
                        <td class="tbl_color"><span>COC:</span></td>
                        <td colspan="3">{{ !empty($project->coc) ? $project->coc : '' }}</td>
                    </tr>
                </table>
            @endif
            @if (strpos($ExtractPdf2, 'ProjectIronmongery:') === false)
                <table class="table table-bordered">
                    <tr>
                        <td class="tbl_color"><span>Ironmongery Status:</span></td>
                        <td colspan="3">{{ !empty($project->project_ironmongery) ? $project->project_ironmongery : '' }}</td>
                    </tr>
                </table>
            @endif
            </div>

        <!-- Page 2 End -->
    </body>
</html>
