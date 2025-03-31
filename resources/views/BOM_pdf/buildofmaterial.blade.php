<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Build Of Material</title>
    <style>
        body{
            font-size:15px;
        }
        #WithBorder {
            width: 100%;
            border-collapse: collapse;
        }

        #WithBorder th,td {
            border: 1px solid #000;
        }

        @page {
            /* size: 590pt 600pt; */
            size: 710pt 925pt;
        }

        .tbl1 td,th {
            padding-right: 8px;
            padding:5px;
        }
        .title{
            margin: 25px 0 5px 0;
        }
        .tbl_color {
            font-family: sans-serif;
            font-weight: bold;

        }

        td>span {
            padding-left: 5px;
        }
        .doortype{
            text-align:left;
            padding:5px;
            padding-bottom:15px;
        }
        </style>
</head>

<body>
    <div id="main">
        <table id="WithBorder" class="tbl1">
            <tbody>
                <tr>
                    <td class="tbl_color"><span>Ref</span></td>
                    <td>
                        <span>
                            @if(!empty($quotaion->QuotationGenerationId))
                            {{ $quotaion->QuotationGenerationId }}
                            @endif
                        </span>
                    </td>
                    <td class="tbl_color"><span>Project</span></td>
                    <td><span>@if(!empty($project->ProjectName)){{ $project->ProjectName }}@endif</span></td>
                    <td class="tbl_color"><span>Prepared By</span></td>
                    <td><span>Aden McNally</span></td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>Revision/Date</span></td>
                    <td><span>@if(!empty($version)) {{ $version }} @endif | {{ date('Y-m-d') }}</td>
                    <td class="tbl_color"><span>Customer</span></td>
                    <td>
                        <span>
                            @if(!empty($comapnyDetail->CompanyAddressLine1))
                            {{ $comapnyDetail->CompanyAddressLine1 }}
                            @endif
                        </span>
                    </td>
                    <td class="tbl_color"><span>Sales Contact</span></td>
                    <td>
                        <span>
                            @if(!empty($user->FirstName) && !empty($user->LastName))
                            {{$user->FirstName.' '.$user->LastName}}
                            @endif
                        </span>
                    </td>
                </tr>
                <tr>
                    <td colspan="6"><center>Items</center></td>
                </tr>
            </tbody>
        </table>


         {!! htmlspecialchars_decode($tbl) !!}


    </div>
</body>

</html>
