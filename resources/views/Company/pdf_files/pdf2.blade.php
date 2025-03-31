<!DOCTYPE html>
<html>
<head>
<style>
   body
    {
        margin:0;
        padding:0;
        font-size:5px;
    }
    table th,span{
        height:80px;
    }
    table.page2_2  {
        padding-left:5px;
    }
    .cusTable{
        width:100%;
        border-collapse:collapse;
    }
    .cusTable th,td{
        border:1px solid black;
    }
    .leftTbl{
        border-collapse:collapse;
        font-size:12px;
        padding:8px;
        width:320px;
        margin-top:30px;
    }
    .page2_2 > p {
        white-space: nowrap;
        transform: rotate(-90deg);
    }

    .tbl_extra{
        text-align:center;
    }
    .tbl_leaft{
        border:0px solid;
    }

    .row{
        width:100%;
        display: flex;
        flex-direction:row;
    }
    .col_6{
        width:100%;
        border:0px solid;
    }
    .col2{
        display: flex;
    }
    .imgClass{
        width:100px;
        margin-left:75%;
    }
    .col_6>p{
        font-size:12px;
    }

    .right_txt {
        font-size: 12px;
        margin-top: 25px !important;
        margin-right: 50%;
        position: absolute;
        right: -40%;
    }
    .tbl_bottom{
        background:#ccc;
        color:black;
        font-weight:bold;
    }
    .tbl_last{
        background:#f2d1a7;
    }
    @page {
        size: 1260pt 660pt;
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
    <div class="row">
        <div class="col_6">
            <table class="leftTbl">
                <tr>
                    <td class="tbl_leaft" style="width:100px"><b>Project</b></td>
                    <td class="tbl_leaft">@if(!empty($project->ProjectName)) {{ $project->ProjectName }} @endif</td>
                </tr>
                <tr>
                    <td class="tbl_leaft"><b>Customer</b></td>
                    <td class="tbl_leaft">@if(!empty($customerContact->FirstName)) {{ $customerContact->FirstName }} @endif @if(!empty($customerContact->LastName)) {{ $customerContact->LastName }} @endif</td>
                </tr>
                <tr>
                    <td class="tbl_leaft"><b>Schedule</b></td>
                    <td class="tbl_leaft">Sales Doorset Schedule</td>
                </tr>
                <tr>
                    <td class="tbl_leaft"><b>Date</b></td>
                    <td class="tbl_leaft">{{ date('Y-m-d') }}</td>
                </tr>
                <tr>
                    <td class="tbl_leaft"><b>Revision</b></td>
                    <td class="tbl_leaft">@if(!empty($version)) {{ $version }} @endif</td>
                </tr>
            </table>
        </div>
        <div class="col_6 col2">
            <div>
                @if(!empty($comapnyDetail->ComplogoBase64))
                <img src="{{$comapnyDetail->ComplogoBase64}}" style="position:absolute; top:10px; right: 10px;" class="imgClass" />
                @else
                <!-- <img src="{{Base64Image('defaultImg')}}" class="imgClass" /> -->
                {!! Base64Image('defaultImg') !!}
                @endif
            </div>
            <div class="right_txt">
                <h4 style="width: 250px;word-wrap: break-word;">
                    @if(!empty($comapnyDetail->CompanyName)) {{ $comapnyDetail->CompanyName }} @endif
                </h4>
                <table class="leftTbl" style="margin-right:85px;padding-top:0px !important;padding-left:0px;">
                    <tr>
                        <td class="tbl_leaft">
                        @if(!empty($customer->CstCompanyAddressLine1)) {{ $customer->CstCompanyAddressLine1 }} @endif
                        </td>
                    </tr>
                    <tr>
                        <td class="tbl_leaft">Telephone: @if(!empty($customer->CstCompanyPhone)) {{ $customer->CstCompanyPhone }} @endif</td>
                        </tr>
                    <tr>
                        <td class="tbl_leaft">Email: @if(!empty($customer->CstCompanyEmail)) {{ $customer->CstCompanyEmail }} @endif</td>
                    </tr>
                    <tr>
                        <td class="tbl_leaft">Web: @if(!empty($customer->CstCompanyWebsite)) {{ $customer->CstCompanyWebsite }} @endif</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
    <table class="table table-bordered cusTable" style="margin-top: 100px;">
        <tbody>
            <tr>
                <td class="tbl_leaft" colspan="19"></td>
                <td class="tbl_extra" colspan="3">Vision Panel</td>
                <td class="tbl_leaft" colspan="4"></td>
                <td class="tbl_extra" colspan="6">Frame</td>
                <td class="tbl_leaft" colspan="1"></td>
                <td class="tbl_extra" colspan="5">Architrave</td>
                @if($HideCosts == 0)
                <td class="tbl_leaft" colspan="8"></td>
                @else
                <td class="tbl_leaft" colspan="6"></td>
                @endif

            </tr>
            <tr>
                <th class="page2_2"><p> Line No. </p></th>
                <th class="page2_2"><p> Floor </p></th>
                <th class="page2_2"><p> Door No. </p></th>
                <th class="page2_2"><p> Door Description </p></th>
                <th class="page2_2"><p> Door Qty. </p></th>
                <th class="page2_2"><p> S.O Height </p></th>
                <th class="page2_2"><p> S.O Width </p></th>
                <th class="page2_2"><p> S.O Wall Thick </p></th>
                <th class="page2_2"><p> Door Type </p></th>
                <th class="page2_2"><p> Door Leaf Finish </p></th>
                <th class="page2_2"><p> Door leaf facing + Brand </p></th>
                <th class="page2_2"><p> LippingType - LippingSpecies</p></th>
                <th class="page2_2"><p> Leaf Width 1 </p></th>
                <th class="page2_2"><p> Leaf Width 2 </p></th>
                <th class="page2_2"><p> Leaf Height </p></th>
                <th class="page2_2"><p> Leaf Thick </p></th>
                <th class="page2_2"><p> Undercut </p></th>
                <th class="page2_2"><p> Handing </p></th>
                <th class="page2_2"><p> Pull Towards </p></th>


                <th class="page2_2"><p> Leaf 1 Size </p></th>
                <th class="page2_2"><p> Leaf 2 Size </p></th>
                <th class="page2_2"><p> Glass Type </p></th>

                <th class="page2_2"><p> Fanlight/Overpanel </p></th>
                <th class="page2_2"><p> Screen Glass </p></th>
                <th class="page2_2"><p> Side Screen 1 </p></th>
                <th class="page2_2"><p> Side Screen 2 </p></th>

                <th class="page2_2"><p> Material </p></th>
                <th class="page2_2"><p> Type </p></th>
                <th class="page2_2"><p> Size </p></th>
                <th class="page2_2"><p> Finish </p></th>
                <th class="page2_2"><p> Ext-Liner </p></th>
                <th class="page2_2"><p> Ext-Liner Size </p></th>

                <th class="page2_2"><p> Intumescent Seal </p></th>

                <th class="page2_2"><p> Material </p></th>
                <th class="page2_2"><p> Type </p></th>
                <th class="page2_2"><p> Size </p></th>
                <th class="page2_2"><p> Finish </p></th>
                <th class="page2_2"><p> Set Qty </p></th>

                <th class="page2_2"><p> Iron. Set </p></th>
                <th class="page2_2"><p> rW Db Rating </p></th>
                <th class="page2_2"><p> Fire Rating </p></th>
                <th class="page2_2"><p> COC Type </p></th>
                <th class="page2_2"><p> Special Feature Refs </p></th>
                @if($HideCosts == 0)
                <th class="page2_2 tbl_last"><p> Doorset Price </p></th>
                <th class="page2_2 tbl_last"><p> Ironmongery Price </p></th>
                @endif
                <th class="page2_2 tbl_last"><p> Total Price Per Doorset </p></th>
            </tr>

            {!! $a !!}

        </tbody>
    </table>


    {{--  @php
    die;
@endphp  --}}
</body>
</html>
