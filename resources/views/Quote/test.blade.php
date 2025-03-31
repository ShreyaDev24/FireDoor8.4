<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elevation Diagram</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <style>
         @page {
          
          size: 1260pt 860pt;
           
        }
        body{
           
            border:1px solid;
            box-sizing: border-box;
        }

        table, td, th {
            border: 1px solid black;
        }
       .doorImg{
                   width:100% !important;
                   height:100%;
               }

        table.table-bordered{
    border:1px solid black;
    margin-top:10px;
  }
table.table-bordered > thead > tr > th{
    border:1px solid black;
}
table.table-bordered > tbody > tr > td{
    border:1px solid black;
}
table.table-bordered > tbody > tr > th{
    border:1px solid black;
}
.pdf-fd td{padding:6px !important;}

body{
            margin:-45px -25px -25px;
        }
        #NoBorder{
            border-collapse: collapse;
            width:90% !important;
        }
        #WithBorder{
            width: 100%;
            border-collapse: collapse;
        }
        #WithBorder td{
            border: 1px solid #000;
        }
        .imgClass{
            width:50px;
            text-align:center;
            padding:5px;
        }
        .marImg{
            width:60px;
        }
        #main{
            font-size:10px;
            display: flex;
        }
        .tbl1{
            padding-right:8px;

        }
        .tbl1 td{
            padding-left:5px !important;
        }
        .doorImg{
            width:70% !important;
        }
        .doorImgBox{
            text-align:center;
            margin-top:10px;
        }

        #section-right{
            float:right;
            margin-left:1230px;
            width:25%;
        }

        #section-left{
            float:left;
            /* margin-right:300px; */
            width:82%;
        }
        .tbl2{
            font-size:15px;

        }
        th{
            text-align: left;
        }
        .dicription_grey {
            background: #ececec;
            font-size: 10px;
            padding-left: 6px;
            width: 50%;
        }
        .tbl_color {
            background: #ececec;
            font-weight: bold;
        }
        #footer{
            position: relative;
            clear: both;
            /* padding-bottom:125px; */
            width:100%;
        }
        .tbl4{
            width:100%;
        }
        @page {
            /* size: 710pt 600pt; */
            /* size: 875pt 1140pt; */
            size: 1260pt 860pt;
        }

        .page-break {
            page-break-after: always;
        }
        .tblTitle{
            width:130px;
        }
        .dicription_blank{
            padding-left:5px;
        }



        /* Fanlinght CSS */
            .fanlightframe_t1{
                margin-left:75px;
                position:absolute;
                width:85px;
                font-size:5px;
                margin-top:35px;
            }
            .fanlightframe_t2{
                position:absolute;
                width:85px;
                font-size:5px;
                margin-left:75px;
                margin-top:60px;
            }
            .fanlightframe_t3{
                position:absolute;
                width:85px;
                font-size:5px;
                margin-left:75px;
                margin-top:80px;
            }
            .fanlightframe_t4{
                position:absolute;
                width:85px;
                font-size:5px;
                margin-left:75px;
                margin-top:100px;
            }
            .fanlightframe_t5{
                position:absolute;
                width:57px;
                font-size:5px;
                margin-left:65px;
                margin-top:125px;
            }
            .fanlightframe_t6{
                position:absolute;
                width:57px;
                font-size:5px;
                margin-left:65px;
                margin-top:153px;
            }
            .fanlightframe_t7{
                position:absolute;
                width:57px;
                font-size:5px;
                margin-left:65px;
                margin-top:190px;
            }
            .fanlightframe_t8{
                position:absolute;
                width:57px;
                font-size:5px;
                margin-left:65px;
                margin-top:235px;
            }
            .fanlightframe_t9{
                position:absolute;
                width:57px;
                font-size:5px;
                margin-left:295px;
                margin-top:90px;
            }

        /* vision panel */
            .visionpanel_t1{
                position:absolute;
                width:80px;
                font-size:8px;
                margin-left:105px;
                margin-top:183px;
            }
            .visionpanel_t2{
                position:absolute;
                width:90px;
                font-size:8px;
                margin-left:50px;
                margin-top:220px;
            }
            .visionpanel_t3{
                position:absolute;
                width:80px;
                font-size:8px;
                margin-left:50px;
                margin-top:283px;
            }
            .visionpanel_t4{
                position:absolute;
                width:80px;
                font-size:8px;
                margin-left:75px;
                margin-top:353px;
            }

            .visionpanel_t5{
                position:absolute;
                color: red;
                /*width:57px;*/
                font-size:13px;
                margin-left:217px;
                margin-top:432px;
            }

            .visionpanel_t6{
                position:absolute;
                width:57px;
                font-size:5px;
                margin-left:98px;
                margin-top:220px; 
            }

        /* Frame */
        .frame_sd_t1{
            position:absolute;
            width:100px;
            font-size:8px;
            margin-left:5px;
            margin-top:93px;
        }
        .frame_sd_t2{
            position:absolute;
            width:100px;
            font-size:8px;
            margin-left:140px;
            margin-top:10px;
        }
        .frame_sd_t3{
            position:absolute;
            width:100px;
            font-size:8px;
            margin-left:395px;
            margin-top:10px;
        }
        .frame_sd_t4{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:615px;
            margin-top:10px;
        }

        .frame_dd_t1{
            position:absolute;
            width:100px;
            font-size:8px;
            margin-left:0px;
            margin-top:90px;
        }

        .frame_dd_t2{
            position:absolute;
            width:100px;
            font-size:8px;
            margin-left:112px;
            margin-top:50px;
        }

        .frame_dd_t3{
ouioiuo            width:40px;
            font-size:8px;
            margin-left:250px;
            margin-top:50px;
        }

        .frame_dd_t4{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:365px;
            margin-top:50px;
        }

        .frame_dd_t5{
            position:absolute;
            width:57px;
            font-size:8px;
            margin-left:675px;
            margin-top:10px;
        }

        .frame_sd_z1{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:665px;
            margin-top:55px;
        }
        .frame_sd_z2{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:665px;
            margin-top:255px;
        }
        .frame_sd_z3{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:665px;
            margin-top:150px;
        }
        .frame_sd_z4{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:345px;
            margin-top:75px;
        }
        .frame_sd_z5{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:945px;
            margin-top:75px;
        }

        .frame_sd_DD1{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:635px;
            margin-top:260px;
        }
        .frame_sd_DD2{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:665px;
            margin-top:145px;
        }
        .frame_sd_DD3{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:555px;
            margin-top:145px;
        }
        .frame_sd_DD4{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:845px;
            margin-top:145px;
        }
        .frame_sd_DD5{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:885px;
            margin-top:50px;
        }
        .frame_sd_DD6{
            position:absolute;
            width:50px;
            font-size:8px;
            margin-left:565px;
            margin-top:70px;
        }
    </style>
</head>
<body>

@if(count($FinalElavationDetails) > 0)

    @foreach($FinalElavationDetails as $EDKey => $EDVal)

<div style="width:30%; heigh:30%; margin:5px;" >
      <table style="padding:5px 10px; font-size:8px;"  class="table table-bordered">
        <thead class="pdf-fd">
          <tr>
            <th  width="5%" > </th>
            @foreach($EDVal['DoorName'] as $DoorNameVal)
              <th >{{$DoorNameVal}}</th>
            @endforeach
          </tr>
        </thead>
        <tbody class="pdf-fd" style="text-align:center;">
        
          <tr>
            <td  width="5%" >Elevation</td>
            @foreach($EDVal['Elevation'] as $ElevationVal)
              <td><img src="{{$ElevationVal}}" width="90px"></td>
            @endforeach
          </tr>
          <tr>
            <td  width="5%" >Plan</td>
            @foreach($EDVal['Plan'] as $PlanVal)
              <td>
                <img src="{{$PlanVal}}" width="90px">
            </td>
            @endforeach
          </tr>
          <tr>
            <td  width="5%">Type</td>
            @foreach($EDVal['Type'] as $TypeVal)
              <td >{{$TypeVal}}</td>
            @endforeach
          </tr>
          <tr>
          <td  width="5px">Structural Opening</td>
            @foreach($EDVal['StructuralOpening'] as $StructuralOpeningVal)
              <td>{{$StructuralOpeningVal}}</td>
            @endforeach
          </tr>
          <tr>
          <td   width="5%">Door Size</td>
            @foreach($EDVal['DoorSize'] as $DoorSizeVal)
              <td>{{$DoorSizeVal}}</td>
            @endforeach
          </tr>
          <tr>
          <td width="5%" >Fire Rating</td>
            @foreach($EDVal['FireRating'] as $FireRatingVal)
              <td>{{$FireRatingVal}}</td>
            @endforeach
          </tr>
          <tr>
          <td  width="5%" >Frame</td>
            @foreach($EDVal['Frame'] as $FrameVal)
              <td>{{$FrameVal}}</td>
            @endforeach
          </tr>
          <tr>
          <td  width="5%" >Vis Panel</td>
            @foreach($EDVal['VisionPanel'] as $VisionPanelVal)
              <td>{{$VisionPanelVal}}</td>
            @endforeach
          </tr>
          <tr>
          <td  width="5%">Finish Colour</td>
            @foreach($EDVal['FinishColour'] as $FinishColourVal)
              <td>{{$FinishColourVal}}</td>
            @endforeach
          </tr>
          <tr>
          <td  width="5%" >Quantity</td>
            @foreach($EDVal['Quantity'] as $QuantityVal)
              <td>{{$QuantityVal}}</td>
            @endforeach
          </tr>
          <tr>
          <td   width="5%" >Notes</td>
            @foreach($EDVal['Notes'] as $NotesVal)
              <td>{{$NotesVal}}</td>
            @endforeach
          </tr>
          <tr>
          <td  width="5%" >NBS ref</td>
            @foreach($EDVal['NBSRef'] as $NBSRefVal)
              <td>{{$NBSRefVal}}</td>
            @endforeach
          </tr>
    
        </tbody>
      </table>
</div>

    @endforeach
@endif
<body>
    @foreach($ed as $tt)
<div id="section-right">
                        <table id="WithBorder" class="tbl2">
                            <tbody>
                                <tr>
                                    <td class="tbl_color tblTitle" style="font-weight: normal;">SELECT <br>Door Type</td>
                                    <td class="dicription_blank"><b>Type {{$tt->DoorType}}</b></td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="WithBorder" class="tbl3">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">General</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Door Type</td>
                                    <td class="dicription_blank">{{$tt->DoorType}}</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Fire Rating</td>
                                    <td class="dicription_blank">{{$tt->FireRating}}</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Mark</td>
                                    <td class="dicription_blank">D-001</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Type Mark </td>
                                    <td class="dicription_blank">IND-1A</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Level </td>
                                    <td class="dicription_blank">Working Level - Ground Floor</td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Structural Opening & Door Leaf Dimensions</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">S.O. Width</td>
                                    <td class="dicription_blank">{{$tt->SOWidth}}</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">S.O. Height</td>
                                    <td class="dicription_blank">{{$tt->SOHeight}}</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Door leaf Finish</td>
                                    <td class="dicription_blank">{{$tt->DoorLeafFinish}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Vision Panel</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Vision panel</td>
                                    <td class="dicription_blank">{{$tt->Leaf1VisionPanel}}</td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">NBS</th>
                                </tr>
                            </tbody>
                        </table>
                      
                    </div>
                    </div>
@endforeach
</body>
</div>
<script>
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
    </script>


</body>
</html>