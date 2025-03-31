<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Door Furniture</title>
    <style>
        .imgClass{
            width:150px;
            float:right;
            margin-top:15px;
        }
        /* .title{
            font-size:40px;
            margin-top:120px;
            width:280px;
            margin-left:45px;
        }
        .headTitle{
            font-weight:bold;
        }
        .bookName{
            font-size:25px;
            margin-top:80px;
            margin-left:45px;
            font-weight:bold;
        } */
        @page {
            size: 610pt 660pt;
        }
    </style>
</head>
<body>
    <div class="container" style="padding-bottom:100px">
        @if(!empty($comapnyDetail->ComplogoBase64))
            <img src="{{$comapnyDetail->ComplogoBase64}}" class="imgClass" alt="Logo" />
        @else
            {!! Base64Image('defaultImg') !!}
        @endif

        @if(!empty($pdf3->content ))
            {!! $pdf3->content !!}
        @endif

        {{--  <div style="margin-top:260px;">
            <span ><u><strong>PRODUCTS FITTED INTERNALLY</u></strong></span><br>
            <span>
                Regular cleaning with warm soapy water should be sufficient to keep any finish in its original condition for a long period of time. The occasional use of good quality wax polish is also recommended. 
            </span>
        </div>  --}}

    </div>
</body>

</html>
