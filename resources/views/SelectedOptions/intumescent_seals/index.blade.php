@extends("layouts.Master")

@section("main_section")
<style>
    .action-icon {
        font-size: 14px;
        cursor: pointer;
        transition: 0.2s ease;
    }

    .action-icon:hover {
        transform: scale(1.15);
        opacity: 0.8;
    }

</style>
<div class="app-main__outer">
    @if ($errors->any())
    <div class="alert alert-danger alert-dismissible float-right">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="fas fa-skull-crossbones"></i> Alert!</h5>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if(session()->has('success'))
    <div class="alert alert-success alert-dismissible float-right">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('success') }}
    </div>
    @endif
    @if(session()->has('error'))
    <div class="alert alert-danger alert-dismissible float-right">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="fas fa-skull-crossbones"></i> Alert!</h5>
        {{ session()->get('error') }}
    </div>
    @endif

    <div class="app-main__inner">

       <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="mb-0">Intumescent Seal Arrangement</h4>

            <div>
                <a href="{{ route('Intumescent-Seal-Arrangement.create') }}" class="btn btn-primary me-2">
                    + Add Custom New
                </a>

                <a href="{{ route('Intumescent-Seal-Arrangement.createStandard') }}" class="btn btn-primary">
                    + Add Standard New
                </a>
            </div>
        </div>

        @if($auth->id != 1)
        <div class="mb-3">
            <button id="updateSelected" class="btn btn-success">
                <i class="fa fa-save"></i> Update Selected
            </button>
        </div>
        @endif

        <ul class="nav nav-tabs mb-3" id="glazingSystemTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="custom-tab" data-toggle="tab" href="#custom" role="tab">
                    Custom Door
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="standard-tab" data-toggle="tab" href="#standard" role="tab">
                    Standard Door
                </a>
            </li>
        </ul>

        <div class="tab-content">

            {{-- CUSTOM TAB --}}
            <div class="tab-pane fade show active" id="custom" role="tabpanel">

                <table class="table table-bordered table-hover  customTable">
                    <thead>
                        <tr>
                            @if($auth->id != 1)
                            <th><input type="checkbox" id="checkCustomAll"></th>
                            @endif

                            <th>Streboard</th>
                            <th>Halspan</th>
                            <th>Flamebreak</th>
                            <th>Stredor</th>
                            <th>FireDoor</th>

                            <th>Configuration</th>
                            <th>Height</th>
                            <th>Width</th>
                            <th>intumescent Seal</th>
                            <th>BRAND</th>
                            <th>FireOnly Type</th>
                            <th>Leaf Type</th>
                            <th>Action</th>

                            @if($auth->id != 1)
                            <th style="min-width: 80px;">Price Per M2</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($items as $item)
                            @if(in_array($item->configurableitems, [1, 2, 7, 8]))
                                @php
                                    $streboard  = yesNoIcon($item->configurableitems == 1);
                                    $Halspan    = yesNoIcon($item->configurableitems == 2);
                                    $flamebreak = yesNoIcon($item->configurableitems == 7);
                                    $stredor    = yesNoIcon($item->configurableitems == 8);
                                @endphp
                                <tr>
                                    @if($auth->id != 1)
                                    <td>
                                        <input type="checkbox" class="rowCustomCheck" value="{{ $item->id }}" {{ $item->selectedId ? 'checked'
                                        :
                                        '' }}>
                                    </td>
                                    @endif

                                    <td class="text-center"><?= $streboard ?></td>
                                    <td class="text-center"><?= $Halspan ?></td>
                                    <td class="text-center"><?= $flamebreak ?></td>
                                    <td class="text-center"><?= $stredor ?></td>
                                    <td class="text-center">{{ $item->firerating }}</td>
                                    <td class="text-center">{{ $item->configuration }}</td>

                                    <td>
                                        {{ $item->Point1height }} - {{ $item->Point2height }}
                                    </td>

                                    <td>
                                        {{ $item->Point1width }} - {{ $item->Point2width }}
                                    </td>

                                    <td class="text-center">
                                        {{ $item->intumescentSeals }}
                                    </td>

                                    <td>
                                        {{ $item->brand }}
                                    </td>

                                    <td>
                                        {{ $item->FireOnly }}
                                    </td>

                                    <td>
                                        {{ $item->leaf_type_keys }}
                                    </td>


                                    @if($item->editBy != 1 || Auth::user()->UserType == 1)

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">

                                            {{-- Edit --}}
                                            <a href="{{ route('Intumescent-Seal-Arrangement.edit', $item->id) }}" class="action-icon text-success"
                                                title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            {{-- Delete --}}
                                            <form action="{{ route('Intumescent-Seal-Arrangement.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure?')" class="m-0 p-0">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="action-icon text-danger border-0 bg-transparent"
                                                    title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                    @else
                                    <td></td>
                                    @endif

                                    @if($auth->id != 1 && $item->selected_cost !== null)
                                    <td style="min-width: 80px;">
                                        <input type="number" step="0.01" class="form-control priceInput"
                                            value="{{ number_format($item->selected_cost, 2, '.', '') }}"
                                            data-option-id="{{ $item->id }}" data-selected-id="{{ $item->selectedId }}"
                                            data-option-type="intumescentSealArrangement" onkeyup="chooseOptionCost(this)">
                                    </td>
                                    @elseif($auth->id != 1)
                                    <td></td>
                                    @endif
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

            </div>


            {{-- STANDARD TAB --}}
            <div class="tab-pane fade" id="standard" role="tabpanel">

                <table class="table table-bordered table-hover  standardTable">
                    <thead>
                        <tr>
                            @if($auth->id != 1)
                            <th><input type="checkbox" id="checkStandardAll"></th>
                            @endif

                            <th>Vicaima</th>
                            <th>Seadec</th>
                            <th>Deanta</th>
                            <th>MMM</th>
                            <th>FireDoor</th>

                            <th>Configuration</th>
                            <th>Height</th>
                            <th>Width</th>
                            <th>intumescent Seal</th>
                            <th>BRAND</th>
                            <th>FireOnly Type</th>
                            <th>Action</th>

                            @if($auth->id != 1)
                            <th style="min-width: 80px;">Price Per M2</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($items as $item)
                            @if(in_array($item->configurableitems, [4, 5, 6, 9]))
                                @php
                                    $vicaima = yesNoIcon($item->configurableitems == 4);
                                    $seadec = yesNoIcon($item->configurableitems == 5);
                                    $deanta = yesNoIcon($item->configurableitems == 6);
                                    $MMM    = yesNoIcon($item->configurableitems == 9);
                                @endphp
                                <tr>
                                    @if($auth->id != 1)
                                    <td>
                                        <input type="checkbox" class="rowStandardCheck" value="{{ $item->id }}" {{ $item->selectedId ? 'checked'
                                        :
                                        '' }}>
                                    </td>
                                    @endif
                                    <td class="text-center"><?= $vicaima ?></td>
                                    <td class="text-center"><?= $seadec ?></td>
                                    <td class="text-center"><?= $deanta ?></td>
                                    <td class="text-center"><?= $MMM ?></td>
                                    <td class="text-center">{{ $item->firerating }}</td>
                                    <td class="text-center">{{ $item->configuration }}</td>

                                    <td>
                                        {{ $item->Point1height }} - {{ $item->Point2height }}
                                    </td>

                                    <td>
                                        {{ $item->Point1width }} - {{ $item->Point2width }}
                                    </td>

                                    <td class="text-center">
                                        {{ $item->intumescentSeals }}
                                    </td>

                                    <td>
                                        {{ $item->brand }}
                                    </td>

                                    <td>
                                        {{ $item->FireOnly }}
                                    </td>

                                    @if($item->editBy != 1 || Auth::user()->UserType == 1)

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">

                                            {{-- Edit --}}
                                            <a href="{{ route('Intumescent-Seal-Arrangement.editStandard', $item->id) }}" class="action-icon text-success"
                                                title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            {{-- Delete --}}
                                            <form action="{{ route('Intumescent-Seal-Arrangement.destroy', $item->id) }}" method="POST"
                                                onsubmit="return confirm('Are you sure?')" class="m-0 p-0">
                                                @csrf
                                                @method('DELETE')

                                                <button type="submit" class="action-icon text-danger border-0 bg-transparent"
                                                    title="Delete">
                                                    <i class="fa fa-trash"></i>
                                                </button>
                                            </form>

                                        </div>
                                    </td>

                                    @else
                                    <td></td>
                                    @endif

                                    @if($auth->id != 1 && $item->selected_cost !== null)
                                    <td style="min-width: 80px;">
                                        <input type="number" step="0.01" class="form-control priceInput"
                                            value="{{ number_format($item->selected_cost, 2, '.', '') }}"
                                            data-option-id="{{ $item->id }}" data-selected-id="{{ $item->selectedId }}"
                                            data-option-type="intumescentSealArrangement" onkeyup="chooseOptionCost(this)">
                                    </td>
                                    @elseif($auth->id != 1)
                                    <td></td>
                                    @endif
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>

            </div>

        </div>
    </div>
</div>

@endsection


@section('js')
<script>
$(document).ready(function () {

    let customTable = $('.customTable').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        ordering: true,
        searching: true,
        responsive: true,
        columnDefs: [{ orderable: false, targets: [0, -1] }]
    });

    let standardTable = $('.standardTable').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        ordering: true,
        searching: true,
        responsive: true,
        columnDefs: [{ orderable: false, targets: [0, -1] }]
    });

    /* ============================
       CHECK ALL (ALL PAGES)
    ============================ */
    $('#checkCustomAll').on('change', function () {
        let checked = this.checked;

        customTable.rows().every(function () {
            $(this.node()).find('.rowCustomCheck').prop('checked', checked);
        });
    });

    $('#checkStandardAll').on('change', function () {
        let checked = this.checked;

        standardTable.rows().every(function () {
            $(this.node()).find('.rowStandardCheck').prop('checked', checked);
        });
    });


    /* ============================
       UPDATE SELECTED BUTTON
    ============================ */
    $('#updateSelected').on('click', function () {

        let data = [];

        customTable.rows().every(function () {
            let row = $(this.node());
            let checkbox = row.find('.rowCustomCheck');

            if (checkbox.length && checkbox.prop('checked')) {
                let priceInput = row.find('.priceInput');

                data.push({
                    id: checkbox.val(),
                    checked: true,
                    price: priceInput.length ? priceInput.val() : null
                });
            }
        });

        standardTable.rows().every(function () {
            let row = $(this.node());
            let checkbox = row.find('.rowStandardCheck');

            if (checkbox.length && checkbox.prop('checked')) {
                let priceInput = row.find('.priceInput');

                data.push({
                    id: checkbox.val(),
                    checked: true,
                    price: priceInput.length ? priceInput.val() : null
                });
            }
        });

        if (!data.length) {
            alert('Please select at least one row.');
            return;
        }

        if (!confirm(`Update ${data.length} selected records?`)) return;

        fetch("{{ route('Intumescent-Seal-Arrangement.updateSelected') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ rows: data })
        })
        .then(res => res.json())
        .then(resp => {
            console.log(resp);
            if (resp.status === 'ok') location.reload();
        });
    });


});

function chooseOptionCost(input) {

    let price = parseFloat(input.value);
    if (isNaN(price) || price < 0) {
        alert('Invalid value entered!');
        return;
    }

    let formData = new FormData();
    formData.append('price', price.toFixed(2));
    formData.append('id', input.dataset.optionId);
    formData.append('selectedId', input.dataset.selectedId);
    formData.append('OptionType', input.dataset.optionType);
    formData.append('_token', '{{ csrf_token() }}');

    $.ajax({
        url: "{{ url('options/update-selected-option-cost') }}",
        type: "POST",
        dataType: "json",
        data: formData,
        contentType: false,
        processData: false,
        success: function (result) {
            if (result.status === 'ok') {
                console.log('Price updated');
            } else {
                alert(result.msg || 'Something went wrong');
            }
        },
        error: function () {
            alert('Server error');
        }
    });
}
</script>


@endsection


