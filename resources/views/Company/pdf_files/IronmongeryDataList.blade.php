<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Ironmongery Data</title>

    <style>
        body {
            font-family: DejaVu Sans, Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
        }

        #headText {
            font-size: 14px;
            margin-bottom: 6px;
            margin-top: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        .tbl2,
        .door-list {
            margin-bottom: 15px;
        }

        .tbl2 td,
        .tbl2 th,
        .door-list td,
        .door-list th {
            border: 1px solid #000;
            padding: 5px;
            vertical-align: top;
        }

        .door-list th {
            text-align: left;
            background-color: #f2f2f2;
        }

        .page-break {
            page-break-after: always;
        }

        .iron-door-list {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 15px;
            border: 1px solid #000;
        }

        .iron-door-header {
            text-align: left;
            padding: 6px;
            background-color: #f2f2f2;
            border-bottom: 1px solid #000;
        }

        .iron-door-title {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 3px;
        }

        .iron-door-subtitle {
            font-size: 12px;
        }

        .iron-door-count {
            font-weight: normal;
        }

        .iron-door-cell {
            width: 33.33%;
            padding: 5px 8px;
            vertical-align: top;
            border: none;
        }

        /* PDF safety */
        .iron-door-list tr {
            page-break-inside: avoid;
        }

    </style>
</head>

<body>

    <!-- ================= START IRONMONGERY BLOCK ================= -->

    @foreach($ironmongerySets as $index => $set)

        <div id="headText">
            <b>Ironmongery Data</b>
        </div>

        <div>
            <table id="WithBorder" class="tbl2">
                {!! $set['ironmongery_table'] !!}
            </table>
        </div>

        @if(!empty($set['door_numbers']))
        <table class="iron-door-list">
            <thead>
                <tr>
                    <th colspan="3" class="iron-door-header">
                        <div class="iron-door-title">Ironmongery Data</div>
                        <div class="iron-door-subtitle">
                            Door list that this belongs to:
                            <span class="iron-door-count">
                                (Total doors: {{ count($set['door_numbers']) }})
                            </span>
                        </div>
                    </th>
                </tr>
            </thead>

            <tbody>
                @foreach(collect($set['door_numbers'])->chunk(3) as $doors)
                    <tr>
                        @foreach($doors as $door)
                            <td class="iron-door-cell">
                                &bull; {{ $set['door_type'] }} - {{ $door }}
                            </td>
                        @endforeach

                        {{-- Fill empty cells --}}
                        @for($i = $doors->count(); $i < 3; $i++)
                            <td class="iron-door-cell"></td>
                        @endfor
                    </tr>
                @endforeach
            </tbody>
        </table>

        @endif

        @if($index + 1 < count($ironmongerySets))
            <div class="page-break"></div>
        @endif

    @endforeach
    <!-- ================= END IRONMONGERY BLOCK ================= -->

    <!-- ================= COPY BLOCK ABOVE FOR NEXT SET ================= -->

</body>

</html>
