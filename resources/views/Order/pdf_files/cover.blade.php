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

        .page {
            width: 100%;
            height: 100%;
            background: #ffffff;
            position: relative;
        }

        /* ================= HEADER ================= */
        .header {
            padding: 25px 50px;
            border-bottom: 1px solid #ddd;
            text-align: right;
        }

        .logo {
            height: 55px;
        }

        /* ================= MAIN CONTENT ================= */
        .content {
            padding: 80px 70px 40px;
            text-align: center;
        }

        .project-title {
            font-size: 36px;
            font-weight: bold;
            margin-bottom: 12px;
        }

        .project-subtitle {
            font-size: 16px;
            color: #777;
            margin-bottom: 40px;
        }

        .project-image-box {
            margin: 0 auto;
            width: 70%;
            background: #f8f9fb;
            padding: 25px;
            border-radius: 10px;
            border: 1px solid #e2e2e2;
        }

        .project-image-box img {
            max-width: 100%;
            max-height: 320px;
        }

        /* ================= FOOTER ================= */
        .footer {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            background: #f1f3f5;
            padding: 30px 60px;
            border-top: 1px solid #ddd;
            font-size: 14px;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
        }

        .footer-table td {
            width: 50%;
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            font-size: 13px;
            color: #555;
            margin-bottom: 5px;
        }

        .value {
            font-size: 15px;
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
