<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>{{ $project->name ?? 'Project Cover' }}</title>

     <style>
        @page {
            margin: 0;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            background: #f4f6f8;
            color: #2c2c2c;
        }

        /* ================= PAGE ================= */
        .page {
            width: 100%;
            height: 100vh;                 /* CRITICAL for PDF */
            background: #ffffff;
        }

        /* ================= HEADER ================= */
        .header {
            padding: 30px 60px;
            border-bottom: 1px solid #e2e2e2;
            text-align: right;
        }

        .logo {
            height: 55px;
        }

        /* ================= MAIN CONTENT ================= */
        .content {
            padding: 90px 80px 0;
            text-align: center;
        }

        .project-title {
            font-size: 38px;
            font-weight: 700;
            margin-bottom: 40px;
            color: #222;
        }

        .project-image-box {
            margin: 0 auto;
            width: 70%;
            background: #f8f9fb;
            padding: 30px;
            border-radius: 10px;
            border: 1px solid #e2e2e2;
        }

        .project-image-box img {
            max-width: 100%;
            max-height: 340px;
        }

        /* ================= FOOTER ================= */
        .footer {
            margin-top: 120px;          /* controls vertical position */
            padding: 35px 80px;
            background: #f6f7f9;
            border-top: 1px solid #e1e1e1;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table td {
            width: 50%;
            vertical-align: top;
        }

        .footer-table td:first-child {
            text-align: left;
        }

        .footer-table td:last-child {
            text-align: right;
        }

        .label {
            font-size: 14px;
            font-weight: 600;
            color: #666;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
        }

        .value {
            font-size: 18px;
            font-weight: 500;
            color: #222;
            line-height: 1.5;
        }
    </style>
</head>
<body>

<div class="page">

    <!-- HEADER -->
    <div class="header">
        @if(!empty($comapnyDetail->ComplogoBase64))
            <img src="{{ $comapnyDetail->ComplogoBase64 }}" class="logo">
        @else
            {!! Base64Image('defaultImg') !!}
        @endif
    </div>

    <!-- CONTENT -->
    <div class="content">

        <div class="project-title">
            {{ $data['project_name'] ?? 'Project Name' }}
        </div>

        {{--  <div class="project-subtitle">
            Fire Door Specification & Technical Documentation
        </div>  --}}

        @if(!empty($data['projectImageBase64']))
            <div class="project-image-box">
                <img src="{{ $data['projectImageBase64'] }}">
            </div>
        @endif

    </div>

    <!-- FOOTER -->
    <div class="footer">
        <table class="footer-table">
            <tr>
                <td>
                    <div class="label">Prepared By</div>
                    <div class="value">
                        {{ $data['preparedByName'] ?? '' }}<br>
                        {{ $data['preparedByCompany'] ?? '' }}
                    </div>
                </td>
                <td>
                    <div class="label">Prepared For</div>
                    <div class="value">
                        {{ $data['client_contractor'] ?? '' }}
                    </div>
                </td>
            </tr>
        </table>
    </div>

</div>

</body>
</html>
