<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Introduction</title>
    <style>
        body{
            margin-bottom:-50px;
        }
        .container{
            background-image: url("{!! $pdf1->backgroundImagebase64 !!}");
            background-repeat: no-repeat;
            width:100%;
            height:100%;
            margin: auto;

            margin-top:-25px;
            margin-left:-25px;
            margin-right:-25px;
        }
        .imgClass{
            width:150px;
            float:right;
            margin-top:75px;
        }
        
        @page {
            size: 710pt 950pt;
        }
    </style>
</head>
<body>
    <div class="container">
        @if(!empty($comapnyDetail->ComplogoBase64))
            <img src="{{$comapnyDetail->ComplogoBase64}}" class="imgClass" alt="Logo" />
        @else                
            {!! Base64Image('defaultImg') !!}
        @endif

        {!! $pdf1->content !!}

        <!-- <div class="title">Operation & Maintenance Manual</div>
        <div class="preparedby">
            <p>Prepared For:</p>
            <p>Preston Olive Primary School</p>
        </div> -->
    </div>
</body>
</html>