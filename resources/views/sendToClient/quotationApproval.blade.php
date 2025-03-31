<!DOCTYPE html>
<html lang="en">
<head>
    <title>Commerce Dashboard - This dashboard was created as an example of the flexibility that Architect offers.</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <style>
        .bg-1 {
            background-color: #1abc9c;
            color: #ffffff;
        }

        .bg-2 {
            background-color: #474e5d;
            color: #ffffff;
        }

        .bg-3 {
            background-color: #ffffff;
            color: #555555;
        }

        .container-fluid {
            padding-top: 70px;
            padding-bottom: 70px;
        }
        .navbar-default {
            background-color: #474e5d;
            border-color: #474e5d;
        }
        .box{
            border:1px solid black;
            text-align :left;
            margin:10px;
            padding:10px;
            border-radius:5px;
        }

        .pdf_generate{
            color: #fff;
            text-decoration: none;
        }

        .pdf_generate:hover{
        color: #fff;
        text-decoration: underline
        }

        .pdf_generate:focus{
            color: #fff;
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-default bg-dark">
        <div class="container">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">
                    <img src="{{url('/')}}/CompanyLogo/companylogo.png" style="width: 100px">
                </a>
            </div>
        </div>
        </div>
    </nav>
    <div class="container">
        @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Alert!</h5>
            {{ session()->get('success') }}
        </div>
        @endif
        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="fas fa-skull-crossbones"></i> Alert!</h5>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif
        <div class="container-fluid bg-2 text-center">
            <h3>Quotation : - {{$quotation->QuotationGenerationId}}</h3>
            <p>Please see the Quotation file.</p>

            <p><a class="pdf_generate" href="{{url('/')}}/public/allpdfFile/{{$filename}}" target="_blanks">PDF File</a></p>

            @if($quotation->linkStatus == 0)
                <a href="#" class="btn btn-default btn-lg acceptQuot">
                    <span class="glyphicon glyphicon-ok"></span> Accept Quotation
                </a>
                <a href="#" class="btn btn-danger btn-lg rejectQuot">
                    <span class="glyphicon glyphicon-remove"></span> Reject Quotation
                </a>
            @else
                @if($quotation->QuotationStatus == 'Reject')
                    <a href="#" class="btn btn-danger btn-lg">
                        <span class="glyphicon glyphicon-remove"></span> Rejected Quotation
                    </a>
                    <div class="box">
                    <p>Quotation Status : {{ $quotation->QuotationStatus }}</p>
                    <p>Reason : {{ $quotation->rejectreason }}</p>
                    </div>
                @endif
                @if($quotation->QuotationStatus == 'Accept')
                    <a href="#" class="btn btn-default btn-lg">
                        <span class="glyphicon glyphicon-ok"></span> Accepted Quotation
                    </a>
                    <div class="box">
                    <p>Quotation Status : {{ $quotation->QuotationStatus }}</p>
                    <p>PO Number : {{ $quotation->PONumber }}</p>
                    </div>
                @endif
            @endif
        </div>
        <div class="jumbotron">
            <div class="accept">
                <h2>Accept Quotation</h2>
                <form action="{{route('quotaionAccept')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" name="id" value="{{$quotation->id}}">
                    <input type="hidden" name="QuotationStatus" value="Accept">
                    <input type="hidden" name="versionId" value="{{$vId}}">
                    <div class="form-group">
                        <label for="ponumber">PO Number:</label>
                        <input type="text" name="PONumber" class="form-control" id="ponumber" required>
                    </div>
                    <div class="form-group">
                        <label for="file">Upload File:</label>
                        <input type="file" name="file" class="form-control" id="file" required>
                    </div>
                    <button type="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
            <div class="reject">
                <h2>Reject Quotation</h2>
                <form action="{{route('quotaionReject')}}" method="post">
                    {{csrf_field()}}
                    <input type="hidden" name="id" value="{{$quotation->id}}">
                    <input type="hidden" name="QuotationStatus" value="Reject">
                    <div class="form-group">
                        <label for="reason">Reason For Rejection:</label>
                        <textarea class="form-control" name="rejectreason" id="reason"></textarea>
                    </div>
                    <button type="submit" class="btn btn-danger">Submit</button>
                </form>
            </div>
        </div>

        <!-- <div class="container-fluid bg-3 text-center">
            <h3>Where To Find Me?</h3><br>
            <div class="row">
            </div>
        </div> -->
    </div>

    <script>
        $(function(){
            $('.accept').css({'display':'none'});
            $('.reject').css({'display':'none'});
            $('.jumbotron').css({'display':'none'});
            $(document).on('click','.acceptQuot',function(e){
                e.preventDefault();
                $('.jumbotron').css({'display':'block'});
                $('.accept').css({'display':'block'});
                $('.reject').css({'display':'none'});
            })
            $(document).on('click','.rejectQuot',function(e){
                e.preventDefault();
                $('.accept').css({'display':'none'});
                $('.reject').css({'display':'block'});
                $('.jumbotron').css({'display':'block'});
            })
        })
    </script>
</body>
</html>
