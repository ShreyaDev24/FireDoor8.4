<!DOCTYPE html>
<html>
<head>
    <title>Labels</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 12mm;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 11pt;
            margin: 0;
            padding: 0;
        }

        .label {
            border: 1px solid black;
            padding: 6px 10px;
            margin-bottom: 12px;
            page-break-inside: avoid;
            box-sizing: border-box;
        }

        .row {
            margin-bottom: 6px;
        }

        .row p {
            display: inline-block;     /* allow side by side */
            vertical-align: top;
            width: 48%;                /* 2 per row */
            box-sizing: border-box;    /* include padding in width */
            margin: 0;
            padding: 1px 0;
        }

        p {
            margin: 0;
            padding: 1px 0;
        }

        strong {
            font-weight: bold;
        }
    </style>
</head>
<body>
    @foreach ($labels as $label)
        <div class="label">
            <div class="row">
                <p><strong>Order No:</strong> {{ $label['OrderNo'] }}</p>
                <p><strong>Handing:</strong> {{ $label['Handing'] }}</p>
            </div>
            <div class="row">
                <p><strong>Fire Rating:</strong> {{ $label['FireRating'] }}</p>
                <p><strong>Door No:</strong> {{ $label['DoorNo'] }}</p>
            </div>
            <div class="row">
                <p><strong>Door Leaf Size:</strong> {{ $label['LeafWidth'] }} x {{ $label['LeafHeight'] }}</p>
                <p><strong>Door Type:</strong> {{ $label['DoorType'] }}</p>
            </div>
            <p><strong>Ironmongery Pk Ref:</strong> {{ $label['Ironmongery'] }}</p>
        </div>
    @endforeach
</body>
</html>
