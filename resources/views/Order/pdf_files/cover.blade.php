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
            color: #333;
        }

        .page {
            width: 100%;
            height: 100%;
            position: relative;
        }

        /* Header */
        .header {
            padding: 30px 50px;
            border-bottom: 2px solid #e5e5e5;
        }

        .logo {
            height: 60px;
        }

        /* Main Content */
        .content {
            padding: 60px 50px;
            text-align: center;
        }

        .project-title {
            font-size: 32px;
            font-weight: bold;
            margin-bottom: 30px;
        }

        .project-image {
            margin: 30px 0;
        }

        .project-image img {
            max-width: 100%;
            max-height: 300px;
            border-radius: 6px;
        }

        /* Footer Section */
        .footer {
            position: absolute;
            bottom: 50px;
            left: 50px;
            right: 50px;
            display: flex;
            justify-content: space-between;
            font-size: 14px;
        }

        .footer-box {
            width: 45%;
        }

        .label {
            font-weight: bold;
            margin-bottom: 5px;
            color: #666;
        }

        .value {
            font-size: 15px;
        }
    </style>
</head>
<body>

<div class="page">

    <!-- Header -->
    <div class="header">
        @if(!empty($comapnyDetail->ComplogoBase64))
            <img src="{{$comapnyDetail->ComplogoBase64}}" class="logo" alt="Company Logo" />
        @else
            {!! Base64Image('defaultImg') !!}
        @endif
    </div>

    <!-- Content -->
    <div class="content">
        <div class="project-title">
            {{ $data['project_name'] ?? 'Project Name' }}
        </div>

        @if(!empty($project->cover_image))
            <div class="project-image">
                <img src="{{ $data['projectImageBase64'] }}" alt="Project Image">
            </div>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        <div class="footer-box">
            <div class="label">Prepared By</div>
            <div class="value">
                {{ $data['preparedByName'] ?? '' }}<br>
                {{ $data['preparedByCompany'] ?? '' }}
            </div>
        </div>

        <div class="footer-box">
            <div class="label">Prepared For</div>
            <div class="value">
                {{ $data['client_contractor'] ?? '' }}
            </div>
        </div>
    </div>

</div>

</body>
</html>

