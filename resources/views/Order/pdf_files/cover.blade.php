<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Cover Page</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            margin: 0;
            padding: 0;
            text-align: center;
            background: #f9f9f9;
        }
        .cover-container {
            padding: 50px;
            text-align: center;
            position: relative;
        }
        .cover-image {
            width: 220px;
            position: absolute;
            top: 20px;
            right: 40px;
        }
        .info-table {
            margin: 100px auto 0 auto;
            border-collapse: separate;
            border-spacing: 0;
            width: 70%;
            font-size: 16px;
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .info-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }
        .info-table tr:last-child td {
            border-bottom: none;
        }
        .heading {
            font-weight: bold;
            width: 200px;
            background: #f0f0f0;
        }
        .info-table tr:hover td {
            background: #f9f9f9;
            transition: background 0.3s ease;
        }
        @page {
            size: 710pt 950pt;
            margin: 40pt;
        }
    </style>

</head>
<body>
    <div class="cover-container">
        @if(!empty($comapnyDetail->ComplogoBase64))
        <img src="{{$comapnyDetail->ComplogoBase64}}" class="imgClass" alt="Logo" style="position:absolute; top:-20px; right: 10px;" />
        @else
        {!! Base64Image('defaultImg') !!}
        @endif

        <table class="info-table">
            <tr><td class="heading">Project Name:</td><td>{{ $data['project_name'] }}</td></tr>
            <tr><td class="heading">Client/Contractor:</td><td>{{ $data['client_contractor'] }}</td></tr>
            <tr><td class="heading">Site Address:</td><td>{{ $data['site_address'] }}</td></tr>
            <tr><td class="heading">Fire Door Types:</td><td>{{ $data['fire_door_types'] }}</td></tr>
            <tr><td class="heading">Date:</td><td>{{ $data['date'] }}</td></tr>
            <tr><td class="heading">Compiled By:</td><td>{{ $data['compiled_by'] }}</td></tr>
        </table>
    </div>
</body>
</html>
