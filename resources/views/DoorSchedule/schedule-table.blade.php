<table class="table table-bordered table-striped itemTable" >
    <thead class="table-header-bg">
            <tr class="text-white">
                <th>Line</th>
                <th>Door Core</th>
                <th>Fire Rating</th>
                <th>Door Type</th>
                <th>Door No.</th>
                <th>Floor</th>
                <th>Item</th>
                <th>Handing</th>
                <th>S.O. Width</th>
                <th>S.O. Height</th>
                <th>S.O. Depth</th>
                <th>Doorset Price</th>
                <th>Ironmongery Price</th>
                <th>Total</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody id="versionData">
            @if (!empty($rows) && count($rows) > 0)

                @php
                    $index = 0;
                    $SI = 1;
                @endphp

                @foreach ($rows as $row)

                    @php
                        if($row['DoorsetType'] == 'leaf_and_a_half'){
                            $row['DoorsetType'] = 'LAH';
                            if($row['Handing'] == 'Left_Hand_Master_Right_Hand_Slave'){
                                $row['Handing'] = 'Left';
                            }else{
                                $row['Handing'] = 'Right';
                            }
                        }
                        $version_id = $row['version_id'] ?? 0;
                        $SvgImage = empty($row['SvgImage']) ? 'color_red' : '';
                    @endphp

                    {{-- 🔥 LOOP DOORS --}}

                    <tr id="validate{{ $row['itemId'] }}" class="{{ $SvgImage }}">
                        <td>
                            {{ $SI }}
                            <input type="hidden" class="check" value="{{ $row['itemId'] }}">
                            <input type="hidden" class="doors_{{ $index }}" value="{{ $row['id'] }}">
                        </td>

                        <td>{{ doorcorename($row['configurableitems']) }}</td>
                        <td>{{ $row['FireRating'] }}</td>
                        <td>{{ $row['DoorType'] }}</td>
                        <td>{{ $row['doorNumber'] }}</td>
                        <td>{{ $row['floor'] }}</td>
                        <td>{{ $row['DoorsetType'] }}</td>
                        <td>{{ $row['Handing'] }}</td>
                        <td>{{ $row['SOWidth'] }}</td>
                        <td>{{ $row['SOHeight'] }}</td>
                        <td>{{ $row['SOWallThick'] }}</td>
                        <td>
                            {{ number_format(
                                $row['AdjustPrice']
                                    ? floatval($row['AdjustPrice'])
                                    : (
                                        !empty($row['leafpricedelta'])
                                            ? floatval($row['leafpricedelta'])
                                            : floatval($row['DoorsetPrice'])
                                    ),
                                2
                            ) }}
                        </td>

                        <td>{{ number_format($row['IronmongaryPrice'], 2) }}</td>

                        <td>
                                {{ number_format(
                                    (
                                        $row['AdjustPrice']
                                            ? floatval($row['AdjustPrice']) + floatval($row['IronmongaryPrice'] ?? 0)
                                            : (
                                                !empty($row['leafpricedelta'])
                                                    ? floatval($row['leafpricedelta']) + floatval($row['IronmongaryPrice'] ?? 0)
                                                    : floatval($row['DoorsetPrice']) + floatval($row['IronmongaryPrice'] ?? 0)
                                            )
                                    ),
                                    2
                            ) }}
                        </td>

                        <td class="text-center">
                            <div class="dropdown">
                                <a class="dropdown-toggle btn btn-light" data-toggle="dropdown">
                                    <i class="fa fa-ellipsis-h"></i>
                                </a>

                                <ul class="dropdown-menu drop_style">

                                    <li>
                                        <a href="{{ ConfigurationURL($row['configurableitems'], $row['itemId'], $version_id) }}">
                                            Edit
                                        </a>
                                    </li>

                                    <li>
                                        <a onclick="favoriteItem('{{ $row['itemId'] }}','{{ $row['id'] }}','Door','Configurable Favorite Item','Configurable Type Name')"
                                            href="javascript:void(0);">
                                                Name Configuration
                                        </a>
                                    </li>

                                    <li>
                                        <a onclick="FloorNoChange(
                                            '{{ $row['id'] }}',
                                            '{{ $row['DoorType'] }}',
                                            '{{ $row['doorNumber'] }}'
                                        )" href="javascript:void(0);">
                                            Edit Floor No.
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ url('quotation/add-new-doors') }}/{{ $quotationId }}/{{ $version_id }}/{{ $row['itemId'] }}">
                                            Add New
                                        </a>
                                    </li>

                                    <li>
                                        <a onclick="DoorNoChange(
                                            '{{ $row['id'] }}',
                                            '{{ $row['DoorType'] }}',
                                            '{{ $row['doorNumber'] }}'
                                        )" href="javascript:void(0);">
                                            Edit Door No.
                                        </a>
                                    </li>

                                    <li>
                                        <a onclick="adjustPrice(
                                            {{ $row['itemId'] }},
                                            {{ $row['id'] }},
                                            {{ floatval($row['DoorsetPrice']) + floatval($row['IronmongaryPrice']) }}
                                        )" href="javascript:void(0);">
                                            Adjust Price
                                        </a>
                                    </li>

                                    <li>
                                        <a onclick="adjustLeafType(
                                            {{ $row['itemId'] }},
                                            {{ $row['id'] }},
                                            '{{ $row['DoorDimensions'] }}'
                                        )" href="javascript:void(0);">
                                            Door Leaf Adjust Price
                                        </a>
                                    </li>

                                    <li>
                                        <a onclick="CopyDoorSet({{ $quotationId }}, {{ $row['id'] }})"
                                        href="javascript:void(0);">
                                            Copy
                                        </a>
                                    </li>

                                    <li>
                                        <a onclick="remove_item({{ $row['id'] }})" href="javascript:void(0);">
                                            Remove
                                        </a>
                                    </li>

                                    <li>
                                        <a onclick="edit_image1({{ $row['itemId'] }})"
                                        href="javascript:void(0);">
                                            Validate
                                        </a>
                                    </li>

                                </ul>
                            </div>
                        </td>
                    </tr>

                    @php
                        $index++;
                        $SI++;
                    @endphp


                @endforeach
            @endif

        </tbody>
</table>
