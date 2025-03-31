
<style>
    .company-title{
        width:35%;
    }
    .company-title li {    
        margin-left: -35px;
        list-style: none;
    }       
    #footer_fixed table, th, td {
        border: 1px solid black;
        border-collapse: collapse; 
        width: 100%;
        height: 10px;   
    }
    .footer p {
        margin-bottom:60px;
    }
    .footImg{
        width:90px;
        height:90px;
        margin-top:-155px;
        margin-left:748px;
    }
</style>
<div style="position:fixed; bottom:8%" id="footer_fixed">
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
if(!empty($comapnyDetail->CompanyAddressLine1)){
    $companyAddress = $comapnyDetail->CompanyAddressLine1;
} else {
    $companyAddress = '';
}
if(!empty($customer->CstCompanyAddressLine1)){
    $CustomerAddress = $customer->CstCompanyAddressLine1;
} else {
    $CustomerAddress = '';
}
@endphp
<p>&nbsp;</p>

            <p>&nbsp;</p>
            
            <table border="1" cellpadding="1" cellspacing="1" style="width:100%">
                <tbody>
                    <tr>
                        <td>
                        <p><span style="color:#8e44ad">{{ $CompanyName}}</span></p>
            
                        <p>{{ $companyAddress }}</p>
                        </td>
                        <td>&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;&nbsp;</td>
                    </tr>
                </tbody>
            </table>
            
            <p>Telephone: {{ $CompanyPhone }}&nbsp;<span style="color:#8e44ad">|</span>&nbsp;Email: {{ $CompanyEmail }}<span style="color:#8e44ad">&nbsp;| </span>Web: {{ $CompanyWebsite }}</p>
            {{--  <div>
                @if(!empty($comapnyDetail->ComplogoBase64))
                <img src="{{$comapnyDetail->ComplogoBase64}}" class="footImg" alt="Logo" />
                @else
                {!! Base64Image('defaultImg') !!}
                @endif
            </div>  --}}
 </div>
        