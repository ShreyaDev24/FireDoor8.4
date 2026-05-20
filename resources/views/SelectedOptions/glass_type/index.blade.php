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
            <h4 class="mb-0">Glass Type</h4>

            <div>
                <a href="{{ route('Glass-type.create') }}" class="btn btn-primary me-2">
                    + Add Custom New
                </a>

                <a href="{{ route('Glass-type.createStandard') }}" class="btn btn-primary">
                    + Add Standard New
                </a>
            </div>
        </div>

        @if($auth->id != 1)
        <div class="mb-3 d-flex gap-2">
            <button id="updateSelected" class="btn btn-success  mr-2">
                <i class="fa fa-save"></i> Update Selected
            </button>

            <button id="exportSelected" class="btn btn-info">
                <i class="fa fa-download"></i> Export Selected
            </button>
        </div>
        @endif
        @if(Auth::user()->UserType == 1)
        <div class="card-body">
            <div class="tab-content">
                <form method="post" action="{{ route('option/import-glasstype') }}" enctype="multipart/form-data">
                    {{csrf_field()}}
                    <div class="card-body">
                        <div class="form-row">
                        <div class="col-md-3">
                            <div class="position-relative form-group">
                                <label for="file">Excel File</label>
                                <input name="ExcelFile" id="ExcelFile" type="file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" required class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <input type="hidden" name="_token" id="_token" value="{{ csrf_token() }}" />
                            <input type="hidden" id="base_url" value="{{url('/')}}">
                            <div class="position-relative form-group">
                                <label for="file" class=""></label>
                                <input type="submit" value="Submit" class="btn btn-success" style="margin-top: 25px;">
                            </div>
                        </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        @endif

        <ul class="nav nav-tabs mb-3" id="glassTypeTabs" role="tablist">
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

                <table class="table table-bordered table-hover glass_typeTable customTable">
                    <thead>
                        <tr>
                            @if($auth->id != 1)
                            <th><input type="checkbox" id="checkCustomAll"></th>
                            @endif

                            <th>Streboard</th>
                            <th>Halspan</th>
                            <th>Flamebreak</th>
                            <th>Stredor</th>
                            <th>NFR</th>
                            <th>FD30</th>
                            <th>FD60</th>

                            <th>Integrity</th>
                            <th>Glass Type</th>
                            <th>Glass Thickness</th>
                            <th>Action</th>

                            @if($auth->id != 1)
                            <th>Price Per M2</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($items as $item)
                            @if($item->Streboard || $item->Halspan || $item->Flamebreak || $item->Stredor)
                                <tr>
                                    @if($auth->id != 1)
                                    <td>
                                        <input type="checkbox" class="rowCustomCheck" value="{{ $item->id }}" {{ $item->selectedId ? 'checked'
                                        :
                                        '' }}>
                                    </td>
                                    @endif

                                    <td class="text-center">{!! yesNoIcon($item->Streboard) !!}</td>
                                    <td class="text-center">{!! yesNoIcon($item->Halspan) !!}</td>
                                    <td class="text-center">{!! yesNoIcon($item->Flamebreak) !!}</td>
                                    <td class="text-center">{!! yesNoIcon($item->Stredor) !!}</td>
                                    <td class="text-center">{!! yesNoIcon($item->NFR) !!}</td>
                                    <td class="text-center">{!! yesNoIcon($item->FD30) !!}</td>
                                    <td class="text-center">{!! yesNoIcon($item->FD60) !!}</td>
                                    <td><b>{{ str_replace('_', ' ', $item->GlassIntegrity) }}</b></td>
                                    <td><b>{{ $item->GlassType }}</b></td>
                                    <td><b>{{ $item->GlassThickness }}</b></td>

                                    @if($item->EditBy != 1 || Auth::user()->UserType == 1)

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">

                                            {{-- Edit --}}
                                            <a href="{{ route('Glass-type.edit', $item->id) }}" class="action-icon text-success"
                                                title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            {{-- Delete --}}
                                            <form action="{{ route('Glass-type.destroy', $item->id) }}" method="POST"
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

                                    @if($auth->id != 1 && $item->selectedPrice !== null)
                                    <td style="min-width: 80px;">
                                        <input type="number" step="0.01" class="form-control priceInput"
                                            value="{{ number_format($item->selectedPrice, 2, '.', '') }}"
                                            data-option-id="{{ $item->id }}" data-selected-id="{{ $item->selectedId }}"
                                            data-option-type="leaf1_glass_type" onkeyup="chooseOptionCost(this)">
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

                <table class="table table-bordered table-hover glass_typeTable standardTable">
                    <thead>
                        <tr>
                            @if($auth->id != 1)
                            <th><input type="checkbox" id="checkStandardAll"></th>
                            @endif

                            <th>Vicaima</th>
                            <th>Seadec</th>
                            <th>Deanta</th>
                            <th>MMM</th>
                            <th>NFR</th>
                            <th>FD30</th>
                            <th>FD60</th>
                            <th>Integrity</th>
                            <th>Glass Type</th>
                            <th>Glass Thickness</th>
                            <th>Vp Area Size</th>
                            <th>Action</th>

                            @if($auth->id != 1)
                            <th>Price Per M2</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($items as $item)
                            @if($item->VicaimaDoorCore || $item->Seadec || $item->Deanta || $item->MMM)
                                <tr>
                                    @if($auth->id != 1)
                                    <td>
                                        <input type="checkbox" class="rowStandardCheck" value="{{ $item->id }}" {{ $item->selectedId ? 'checked'
                                        :
                                        '' }}>
                                    </td>
                                    @endif
                                    <td class="text-center">{!! yesNoIcon($item->VicaimaDoorCore) !!}</td>
                                    <td class="text-center">{!! yesNoIcon($item->Seadec) !!}</td>
                                    <td class="text-center">{!! yesNoIcon($item->Deanta) !!}</td>
                                    <td class="text-center">{!! yesNoIcon($item->MMM) !!}</td>
                                    <td class="text-center">{!! yesNoIcon($item->NFR) !!}</td>
                                    <td class="text-center">{!! yesNoIcon($item->FD30) !!}</td>
                                    <td class="text-center">{!! yesNoIcon($item->FD60) !!}</td>
                                    <td><b>{{ str_replace('_', ' ', $item->GlassIntegrity) }}</b></td>
                                    <td><b>{{ $item->GlassType }}</b></td>
                                    <td><b>{{ $item->GlassThickness }}</b></td>
                                    <td><b>{{ $item->VpAreaSize }}</b></td>

                                    @if($item->EditBy != 1 || Auth::user()->UserType == 1)

                                    <td class="text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">

                                            {{-- Edit --}}
                                            <a href="{{ route('Glass-type.editStandard', $item->id) }}" class="action-icon text-success"
                                                title="Edit">
                                                <i class="fa fa-edit"></i>
                                            </a>

                                            {{-- Delete --}}
                                            <form action="{{ route('Glass-type.destroy', $item->id) }}" method="POST"
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

                                    @if($auth->id != 1 && $item->selectedPrice !== null)
                                    <td style="min-width: 80px;">
                                        <input type="number" step="0.01" class="form-control priceInput"
                                            value="{{ number_format($item->selectedPrice, 2, '.', '') }}"
                                            data-option-id="{{ $item->id }}" data-selected-id="{{ $item->selectedId }}"
                                            data-option-type="leaf1_glass_type" onkeyup="chooseOptionCost(this)">
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


    $('#exportSelected').on('click', function (e) {
        e.preventDefault();

        let ids = [];

        standardTable.rows().every(function () {
            let checkbox = $('input.rowStandardCheck', this.node());
            if (checkbox.length && checkbox.prop('checked')) {
                ids.push(checkbox.val());
            }
        });
        customTable.rows().every(function () {
            let checkbox = $('input.rowCustomCheck', this.node());
            if (checkbox.length && checkbox.prop('checked')) {
                ids.push(checkbox.val());
            }
        });

        if (!ids.length) {
            alert('Please select at least one record to export.');
            return;
        }

        let form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('Glass-type.exportSelected') }}";

        let token = document.createElement('input');
        token.type = 'hidden';
        token.name = '_token';
        token.value = "{{ csrf_token() }}";
        form.appendChild(token);

        ids.forEach(id => {
            let input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
        });

        document.body.appendChild(form);
        form.submit();
        form.remove();
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

        fetch("{{ route('Glass-type.updateSelected') }}", {
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


