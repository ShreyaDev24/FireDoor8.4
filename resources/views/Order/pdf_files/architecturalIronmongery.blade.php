<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Architectural Ironmongery</title>
    <style>
        .imgClass{
            width:150px;
            float:right;
            margin-top:15px;
        }
        .title{
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
        }
        @page {
            size: 710pt 950pt;
        }
    </style>
</head>
<body>
    <div class="container">
    <div style="padding-bottom:100px">
        @if(!empty($comapnyDetail->ComplogoBase64))
            <img src="{{$comapnyDetail->ComplogoBase64}}" class="imgClass" alt="Logo" />
        @else                
            {!! Base64Image('defaultImg') !!}
        @endif

        {!! $pdf2->content !!}
    
        <!-- <div class="title">
            <span class="headTitle">Architectural</span><br>
            <span>Ironmongery</span>
        </div>
        <div class="bookName">OPERATION & MAINTENANCE MANUAL</div> 
    -->
        
    </div>
</body>
</html>