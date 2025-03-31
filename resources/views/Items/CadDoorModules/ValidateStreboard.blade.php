<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>

<body>
    <input type="hidden" name="issingleconfiguration" value="{{ $issingleconfiguration }}">
    <input type="hidden" name="QuotationId"
        value="@if (isset($QuotationId)) {{ $QuotationId }}@else{{ '' }} @endif">
    <input type="hidden" name="itemID"
        value="@if (isset($Item['itemId'])) {{ $Item['itemId'] }}@else{{ '' }} @endif">
    <input type="text" name="doorType" id="doorType" placeholder="Enter door type"
        value="@if (isset($Item['DoorType'])) {{ $Item['DoorType'] }}@else{{ '' }} @endif"
        class="form-control" required>
    <select name="fireRating" id="fireRating" class="form-control change-event-calulation" required>
        <option value="">Select fire rating</option>
        @foreach ($option_data as $row)
            @if ($row->OptionSlug == 'fire_rating')
                <option value="{{ $row->OptionKey }}"
                    @if (isset($Item['FireRating'])) @if ($Item['FireRating'] == $row->OptionKey){{ 'selected' }} @endif
                    @endif>{{ $row->OptionValue }}</option>
            @endif
        @endforeach
    </select>
    <select name="doorsetType" id="doorsetType"
        class="form-control combination_of change-event-calulation door-configuration" required>
        <option value="">Select door set type</option>
        @foreach ($option_data as $row)
            @if ($row->OptionSlug == 'door_configuration_doorset_type')
                <option value="{{ $row->OptionKey }}"
                    @if (isset($Item['DoorsetType'])) @if ($Item['DoorsetType'] == $row->OptionKey){{ 'selected' }} @endif
                    @endif
                    >{{ $row->OptionValue }}</option>
            @endif
        @endforeach
    </select>
    <select name="swingType" id="swingType" class="form-control combination_of" required>
        <option value="">Select swing type</option>
        @foreach ($option_data as $row)
            @if ($row->OptionSlug == 'door_configuration_swing_type')
                <option value="{{ $row->OptionKey }}"
                    @if (isset($Item['SwingType'])) @if ($Item['SwingType'] == $row->OptionKey) {{ 'selected' }} @endif
                    @endif
                    >{{ $row->OptionValue }}</option>
            @endif
        @endforeach
    </select>
    <select name="latchType" id="latchType" class="form-control combination_of">
        <option value="">Select latch type</option>
        @foreach ($option_data as $row)
            @if ($row->OptionSlug == 'door_configuration_latch_type')
                <option value="{{ $row->OptionKey }}"
                    @if (!empty($Item['LatchType'])) @if ($Item['LatchType'] == $row->OptionKey) {{ 'selected' }} @endif
                    @endif
                    >{{ $row->OptionValue }}</option>
            @endif
        @endforeach
    </select>
    <select required name="Handing" id="Handing" class="form-control">
        <option value="">Select Handing</option>
    </select>
    <input required type="number" min="0" max="20" step="any" id="tollerance"
        value="@if (isset($Item['Tollerance'])) {{ $Item['Tollerance'] }}@else{{ '' }} @endif"
        name="tollerance"
        class="form-control for_c_leaf_height  forleafHeightNoOP change-event-calulation door-configuration" required>
    <input type="number" id="undercut" readonly name="undercut"
        value="@if (isset($Item['Undercut'])) {{ $Item['Undercut'] }}@else{{ '' }} @endif"
        class="form-control change-event-calulation  undercut door-configuration" required>
    <input type="number" id="floorFinish" name="floorFinish"
        value="@if (isset($Item['FloorFinish'])) {{ $Item['FloorFinish'] }}@else{{ '' }} @endif"
        class="form-control change-event-calulation forundercut door-configuration" required>
    <input type="number" min="2" max="4" id="gap" name="gap"
        value="@if (isset($Item['GAP'])) {{ $Item['GAP'] }}@else{{ '' }} @endif"
        class="form-control for_c_leaf_height  forleafHeightNoOP change-event-calulation door-configuration" required>
    <input type="number" id="frameThickness" name="frameThickness"
        value="@if (isset($Item['FrameThickness'])) {{ $Item['FrameThickness'] }}@else{{ '' }} @endif"
        class="form-control change-event-calulation door-configuration" required>
    <select name="ironmongerySet" id="ironmongerySet" class="form-control">
        <option value="Yes"
            @if (isset($Item['IronmongerySet'])) @if ($Item['IronmongerySet'] == 'Yes') {{ 'selected' }} @endif
            @endif>Yes</option>
        <option value="No"
            @if (isset($Item['IronmongerySet'])) @if ($Item['IronmongerySet'] == 'No')
                                            {{ 'selected' }} @endif
        @else {{ 'selected' }} @endif>No</option>
    </select>
    <select name="IronmongeryID" id="IronmongeryID" class="form-control"
        @if (empty($Item['IronmongerySet']) || $Item['IronmongerySet'] == 'No') {{ 'disabled' }} @endif>
        <option value="">Select Ironmongery Set</option>
        @if (!empty($setIronmongery))
            @foreach ($setIronmongery as $setIronmongerys)
                <option value="{{ $setIronmongerys->id }}"
                    @if (isset($Item['IronmongeryID'])) @if ($Item['IronmongeryID'] == $setIronmongerys->id) {{ 'selected' }} @endif
                    @endif>{{ $setIronmongerys->Setname }}</option>
            @endforeach
        @endif
    </select>
</body>

</html>
