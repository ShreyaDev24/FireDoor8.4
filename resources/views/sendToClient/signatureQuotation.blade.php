<!DOCTYPE html>
<html lang="en">
<head>
    <title>Quotation Signature</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.0.0/dist/signature_pad.umd.min.js"></script>

    <style>
        .signature-pad {
            border: 2px solid #000;
            border-radius: 5px;
            width: 100%;
            max-width: 500px;
            height: 200px;
            margin: 0 auto;
        }
        .btn-clear {
            margin-top: 10px;
        }
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
        html, body {
    height: 100%;
    margin: 0;
}

body {
    display: flex;
    flex-direction: column;
}

.container {
    flex: 1; /* take remaining height */
    display: flex;
    flex-direction: column;
    justify-content: center; /* vertical center */
    align-items: center; /* horizontal center */
}

.jumbotron {
    width: 100%;
    max-width: 700px;
    margin: auto;
    padding: 30px;
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
    </nav>

    <div class="container">
        @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            {{ session()->get('success') }}
        </div>
        @endif
        @if ($errors->any())
        <div class="alert alert-danger alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <ul>
                @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <div class="container-fluid bg-2 text-center">
            <h3>Quotation : - {{$quotationnumber}}</h3>
            <p>Please review the quotation and confirm your approval by signing below.</p>

            <p><a class="pdf_generate" href="{{url('/')}}/public/allpdfFile/{{$filename}}" target="_blanks">PDF File</a></p>
        </div>

        <div class="jumbotron text-center">
            <h2>Sign Quotation</h2>
            <form action="{{ route('signaturesubmit') }}" method="POST" id="signature-form">
                @csrf
                <input type="hidden" name="quotationId" value="{{ $qId }}">
                <input type="hidden" name="versionId" value="{{ $vId }}">
                <input type="hidden" name="userId" value="{{ $cId }}">
                <input type="hidden" name="signature" id="signature">

                <label>
                    <input type="checkbox" name="agree" value="1" required>
                    I agree to approve and sign this quotation.
                </label>
                <br><br>

                <!-- Signature Pad -->
                <canvas id="signature-pad" class="signature-pad"></canvas>
                <br>

                <label>
                    <input type="checkbox" name="submitforall" value="1" checked>
                    Signoff all
                </label>
                 <br>
                <button type="button" class="btn btn-warning btn-clear" id="clear-signature">Clear</button>
                <br><br>

                <button type="submit" class="btn btn-success">Sign & Generate PDF</button>
            </form>
        </div>
    </div>

    <script>
        const canvas = document.getElementById('signature-pad');
        const signaturePad = new SignaturePad(canvas);

        // Resize canvas to be responsive
        function resizeCanvas() {
            const ratio =  Math.max(window.devicePixelRatio || 1, 1);
            canvas.width = canvas.offsetWidth * ratio;
            canvas.height = canvas.offsetHeight * ratio;
            canvas.getContext("2d").scale(ratio, ratio);
            signaturePad.clear();
        }
        window.onresize = resizeCanvas;
        resizeCanvas();

        // Clear button
        document.getElementById('clear-signature').addEventListener('click', function () {
            signaturePad.clear();
        });

        // Before form submit, save signature as base64
        document.getElementById('signature-form').addEventListener('submit', function (e) {
            if (signaturePad.isEmpty()) {
                e.preventDefault();
                alert("Please provide a signature first.");
            } else {
                const dataUrl = signaturePad.toDataURL(); // Base64 string
                document.getElementById('signature').value = dataUrl;
            }
        });
    </script>
</body>
</html>
