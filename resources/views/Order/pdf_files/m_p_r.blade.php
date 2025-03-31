<html>
    <head>
        <style>

            @page { margin: 30px; size:29.7cm 21cm;}
            body{
                font-family: 'Open Sans', sans-serif;
            }
           table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            height: 20px;
            font-size: 10px;
            }

            td{width: 34px;
            height: 34px;
            padding-left: 5px;
            }

            /*#header{
                height:4cm;
                border-bottom:1px solid black;
            }
            #header #left{
                width:23.6cm;
                float:left;
            }

            #header #left #first{
                width:3cm;
                border-right:1px solid black;
                height:3.2cm;
                position:absolute;
                top:31px;
            }
            #header #left #second{
                left:3cm;
                width:10.8cm;
                border-right:1px solid black;
                height:3.2cm;
                position:absolute;
                top:32px;
            }
            #header #left #third{
                left:13.8cm;
                width:5.3cm;
                border-right:1px solid black;
                height:3.2cm;
                position:absolute;
                top:32px;
            }
            #header #left #forth{
                left:19.2cm;
                width:4.3cm;
                height:3.2cm;
                position:absolute;
                top:32px;
            }
            #header #right{
                float:left;
                width:4.4cm;
                border-left:1px solid black;
                height:3.2cm;
            }
            h2 {
                color:#601055;
                font-weight:normal;
                margin-top:5px;
                border-bottom:1px solid black;
                height:0.7cm;
                font-size: 18px;

            }
            #job_title p{
                margin-top:2px;
                margin-left:2px;
                font-size:14px;
            }*/
            .imgClass{
                width:150px;
                float:right;
                margin-top:15px;
            }
        </style>
    </head>
    <body>

            <table>
    <tr>
        <th colspan="18" style="text-align :left; font-weight:500; font-size:20px; color:#5e1356;">MAINTENANCE PROGRAM RECORD</th>

        <td colspan="4" rowspan="4" >
            @if(!empty($comapnyDetail->ComplogoBase64))
                <img src="{{$comapnyDetail->ComplogoBase64}}" class="imgClass" alt="Logo" />
            @else
                {!! Base64Image('defaultImg') !!}
            @endif
        </td>
    </tr>
    <tr>
        <td style="font-size:16px;" >Job Title:</td>
        <td colspan="9">Preston Olive Primary School</td>
        <td colspan="5" rowspan="3"></td>
        <td colspan="3" rowspan="3" style="font-size:9px;">Datim Ltd<br>
        Foxwood Industrial Park Foxwood Road Chesterfield S41 9RN<br><br> Tel: 01246 572277 Fax: 01246 572270 Email: sales@datim.co.uk Web: www.datim.co.uk</td>
    </tr>

    <tr>
        <td>Contractor:</td>
        <td colspan="9">Spatial Initiative Ltd</td>
    </tr>
    <tr>
        <td>Date of Issue:</td>
        <td colspan="9">08/01/2021</td>
    </tr>
    <tr>
        <td rowspan="2">PRODUCT</td>
        <td rowspan="2">MAINTENANCE PERIOD</td>
        <td colspan="4">2020</td>
        <td colspan="4">2021</td>
        <td colspan="4">2022</td>
        <td colspan="4">2023</td>
        <td colspan="4">2024</td>
    </tr>
    <tr style="height:14px;" >
        <td >Q1</td>
        <td style="height:14px;">Q2</td>
        <td style="height:14px;">Q3</td>
        <td>Q4</td>
        <td>Q1</td>
        <td>Q2</td>
        <td>Q3</td>
        <td>Q4</td>
        <td>Q1</td>
        <td>Q2</td>
        <td>Q3</td>
        <td>Q4</td>
        <td>Q1</td>
        <td>Q2</td>
        <td>Q3</td>
        <td>Q4</td>
        <td>Q1</td>
        <td>Q2</td>
        <td>Q3</td>
        <td>Q4</td>
    </tr>
    <tr>
        <td>HINGES</td>
        <td>3 months</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>

    </tr>

    <tr>
        <td>DOOR CLOSERS</td>
        <td>3 months</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>

    <tr>
        <td>POWERED DOOR OPERATORS</td>
        <td>3 months</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>

    <tr>
        <td>LOCKCASES/ CYLINDERS</td>
        <td>24months</td>
        <td colspan="8"></td>
        <td colspan="8"></td>
        <td colspan="4"></td>




    </tr>

    <tr>
        <td>SUNDRY ITEMS:</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>

        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>

        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>

        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>

    <tr>
        <td>Panic Hardware</td>
        <td>3 months</td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>

        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>

        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>

        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
    </tr>

    <tr>
        <td>Mech. Digital Locks</td>
        <td>12 months</td>
        <td colspan="4"></td>

        <td colspan="4"></td>

        <td colspan="4"></td>

        <td colspan="4"></td>
        <td colspan="4"></td>
    </tr>

    <tr>
        <td>Sliding Door Gear</td>
        <td>6 months</td>
        <td colspan="2"></td>
        <td colspan="2"></td>
        <td colspan="2"></td>
        <td colspan="2"></td>

        <td colspan="2"></td>
        <td colspan="2"></td>
        <td colspan="2"></td>
        <td colspan="2"></td>

        <td colspan="2"></td>
        <td colspan="2"></td>

    </tr>

    <tr>
        <td>Security Products</td>
        <td>6 months</td>
        <td colspan="2"></td>
        <td colspan="2"></td>
        <td colspan="2"></td>
        <td colspan="2"></td>

        <td colspan="2"></td>
        <td colspan="2"></td>
        <td colspan="2"></td>
        <td colspan="2"></td>

        <td colspan="2"></td>
        <td colspan="2"></td>

    </tr>

    <tr>
        <td>Ancillary Fittings</td>
        <td>6 months</td>
        <td colspan="2"></td>
        <td colspan="2"></td>
        <td colspan="2"></td>
        <td colspan="2"></td>

        <td colspan="2"></td>
        <td colspan="2"></td>
        <td colspan="2"></td>
        <td colspan="2"></td>

        <td colspan="2"></td>
        <td colspan="2"></td>

    </tr>

    <tr>
        <td>Washroom & Disabled Fittings</td>
        <td>6 months</td>
        <td colspan="2"></td>
        <td colspan="2"></td>
        <td colspan="2"></td>
        <td colspan="2"></td>

        <td colspan="2"></td>
        <td colspan="2"></td>
        <td colspan="2"></td>
        <td colspan="2"></td>

        <td colspan="2"></td>
        <td colspan="2"></td>

    </tr>

    <tr>
        <td>Intumescent & Weather Seals</td>
        <td>6 months</td>
        <td colspan="2"></td>
        <td colspan="2"></td>
        <td colspan="2"></td>
        <td colspan="2"></td>

        <td colspan="2"></td>
        <td colspan="2"></td>
        <td colspan="2"></td>
        <td colspan="2"></td>

        <td colspan="2"></td>
        <td colspan="2"></td>
    </tr>

    <tr>
        <td colspan="22" style="text-align :left; font-weight:500; font-size:12px; height:18px; ">PLEASE CALL 01246 572277 to request further copies</td>
    </tr>
</table>


    </body>
</html>
