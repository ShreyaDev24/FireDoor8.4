<!DOCTYPE html>
<html>
    <head>
        <title>Intorduction PDF</title>
        <style>
            body{
                font-size:18px;
                padding-left:15px;
            }
            .cusTable {
                width: 100%;
                border-collapse: collapse;
            }

            table th,
            table td {
                border: 0px solid #555;
                padding-left: 5px;
            }

            .imgClass {
                width: 180px;
                margin-left: 55%;
                /* border-bottom:1px solid black;
                border-right:1px solid black; */

            }



            .col1 {
                width: 50%;
                background: ;
                padding: 10px;
                padding-left: 0px;
                font-size: 20px;
                margin-top:-65px;

            }

            .col2 {
                width: 50%;
                margin-left: 65%;
                padding: 10px;
                width:55px;
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

            .footImg{
                width:90px;
                height:90px;
                margin-top:-155px;
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
    <body style="position: relative;">
        <!-- Page 1 Start -->
            <div class="col2">
                @if(!empty($comapnyDetail->ComplogoBase64))
                <img src="{{$comapnyDetail->ComplogoBase64}}" class="imgClass" alt="Logo" style="position:absolute; top:-20px; right: 10px;" />
                @else
                <!-- <img src="{{Base64Image('defaultImg')}}" class="imgClass" alt="Logo" /> -->
                {!! Base64Image('defaultImg') !!}
                @endif
            </div>
            <div class="col1">
                <p style="margin-top: -200px;">
                     @php


                        if(!empty($customer->CstCompanyAddressLine1)){
                            $CustomerAddress = $customer->CstCompanyAddressLine1;
                        } else {
                            $CustomerAddress = '';
                        }


                    @endphp
                </p>
            </div>

            @php
            $companyAddress = '';
            if(!empty( $pdf1->msg)) {
                $ExtractPdf1 = $pdf1->msg;
                if(!empty($contactfirstandlastname)){
                    $customerName = $contactfirstandlastname;
                } else if(!empty($customerContact->FirstName) || !empty($customerContact->LastName)) {
                    $customerName = $customerContact->FirstName.' '.$customerContact->LastName;
                }
                else{
                    $customerName = '';
                }
                if(!empty($comapnyDetail->CompanyAddressLine1)){
                    $companyAddress = $comapnyDetail->CompanyAddressLine1;
                } else {
                    $companyAddress = '';
                }
                if(!empty($project->ProjectName)){
                $ProjectName = $project->ProjectName;
                } else {
                $ProjectName = '';
                }

                if(!empty($quotaion->QuotationGenerationId)){
                $QuotationGenerationId = $quotaion->QuotationGenerationId;
                } else {
                $QuotationGenerationId = '';
                }

                if(!empty($user->FirstName) && !empty($user->LastName)){
                $userName = $user->FirstName.' '.$user->LastName;
                } else {
                $userName = '';
                }


                if(!empty($user->UserJobtitle)){
                $UserJobtitle = $user->UserJobtitle;
                } else {
                $UserJobtitle = '';
                }

                if(!empty($user->UserEmail)){
                $UserEmail = $user->UserEmail;
                } else {
                $UserEmail = '';
                }

                if(!empty($user->UserPhone)){
                $UserPhone = $user->UserPhone;
                } else {
                $UserPhone = '';
                }

                if(isset($contractorName)){
                    $contractorName = $contractorName;
                }else{
                    $contractorName = '';
                }

                $str2 = ['[Date]','[CustomerName]','[ProjectName]','[QuotationGenerationId]',
                '[UserName]','[Designation]','[UserEmail]','[UserMobile]','[CustomerAddress]', '[ContractorName]'];
                $rplc2 =[date('Y-m-d'),$customerName ,$ProjectName ,$QuotationGenerationId,
                $userName,$UserJobtitle,$UserEmail,$UserPhone,$CustomerAddress, $contractorName];

                echo  str_replace($str2,$rplc2,$ExtractPdf1);

            }
            @endphp
            @php
            $footer = '';
            @endphp
            @if(!empty( $pdf_footer->msg))
                @php
                    if(!empty($comapnyDetail->CompanyPhone)){
                        $CompanyPhone = $comapnyDetail->CompanyPhone;
                    } else {
                        $CompanyPhone = '';
                    }

                    if(!empty($comapnyDetail->CompanyEmail)){
                        $CompanyEmail = $comapnyDetail->CompanyEmail;
                    } else {
                        $CompanyEmail = '';
                    }

                    if(!empty($comapnyDetail->CompanyWebsite)){
                        $CompanyWebsite = $comapnyDetail->CompanyWebsite;
                    } else {
                        $CompanyWebsite = '';
                    }

                    if(!empty($comapnyDetail->CompanyName)){
                        $CompanyName = $comapnyDetail->CompanyName;
                    } else {
                        $CompanyName = '';
                    }
                    if(isset($contractorName)){
                        $contractorName = $contractorName;
                    }else{
                        $contractorName = '';
                    }


                    $ExtractPdf3 = $pdf_footer->msg;
                    $str3 = [
                        '[CompanyAddress]','[CustomerAddress]','[CompanyPhone]','[CompanyEmail]','[CompanyWebsite]','[CompanyName]', '[ContractorName]'
                    ];
                    $rplc3 = [$companyAddress,$CustomerAddress,$CompanyPhone,$CompanyEmail,$CompanyWebsite,$CompanyName, $contractorName];

                    $footer =  str_replace($str3,$rplc3,$ExtractPdf3);

                @endphp
            @endif

            <div class="col3">
                {!!$footer!!}
            </div>
            <div class="col4">
                @if(!empty($comapnyDetail->ComplogoBase64))
                <img src="{{$comapnyDetail->ComplogoBase64}}" style="width:150px; position:absolute; right: 10px;" class="footImg" alt="Logo" />
                @else
                <!-- <img src="{{Base64Image('defaultImg')}}" class="footImg" alt="Logo" /> -->
                {!! Base64Image('defaultImg') !!}
                @endif
            </div>
{{--  @php
    die;
@endphp  --}}
        <!-- Page 1 End -->
    </body>
</html>
