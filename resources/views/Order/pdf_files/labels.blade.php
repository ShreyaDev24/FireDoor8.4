<!DOCTYPE html>
<html>
<head>
    <title>Labels</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
        }

        .label {
            display: inline-block;
            vertical-align: top;
            border: 1px solid #000;
            padding: 4mm;
            margin: 2mm;
            width: 60mm;              /* Fixed width */
            height: 70mm;             /* Fixed height to ensure uniformity */
            box-sizing: border-box;   /* Includes padding in width/height */
            overflow: hidden;         /* Prevent content from expanding box */
            page-break-inside: avoid;
        }

        @page {
            size: A4 portrait;
            margin: 10mm;
        }
    </style>
</head>
<body>
    {!! $html !!}
</body>
</html>
