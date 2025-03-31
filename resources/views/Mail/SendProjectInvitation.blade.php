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

        <div class="container-fluid bg-2 text-center">
            <h3>Project : - </h3>
            <p>Please see the Quotation file.</p>
            <p><a href="{{url('/')}}/quotationFiles" target="_blanks">PDF File</a></p>
            <p><a href="{{url('/')}}/quotationFiles" target="_blanks">Excel File</a></p>

                <a href="#" class="btn btn-default btn-lg acceptQuot">
                    <span class="glyphicon glyphicon-ok"></span> Accept project
                </a>
                <a href="#" class="btn btn-danger btn-lg rejectQuot">
                    <span class="glyphicon glyphicon-remove"></span> Reject project
                </a>
        </div>
        <div class="jumbotron">
            <div class="accept">
                <h2>Accept Project</h2>
                <form action="{{route('project/invitation/status')}}" method="post" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <input type="hidden" name="projectId" id="projectId1">
                    <input type="hidden" name="choice" value="accepted">
                    <div class="form-group">
                        <label for="message">Message<span class="text-danger">*</span></label>
                        <input name="message" type="text" class="form-control" placeholder="Enter a comment">
                    </div><br>
                    <div class="projectFiles">
                        <label for="uploadfile"> Upload Document </label>
                        <div class="input-group mb-2">
                            <input type="file" name="document" class="form-control">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success">Submit</button>
                </form>
            </div>
            <div class="reject">
                <h2>Reject Project</h2>
                <form action="{{route('project/invitation/status')}}" method="post">
                    {{csrf_field()}}
                    <input type="hidden" name="choice" value="rejected">
                     <input type="hidden" name="projectId" id="projectId2">
                    <div class="form-group">
                        <label for="reason">Rejection:</label>
                       The project is rejected.
                    </div>
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
