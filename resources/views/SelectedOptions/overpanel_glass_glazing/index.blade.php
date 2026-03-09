@extends('layouts.Master')

@section('main_section')
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
    @if (session()->has('success'))
    <div class="alert alert-success alert-dismissible float-right">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="icon fas fa-check"></i> Alert!</h5>
        {{ session()->get('success') }}
    </div>
    @endif
    @if (session()->has('error'))
    <div class="alert alert-danger alert-dismissible float-right">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <h5><i class="fas fa-skull-crossbones"></i> Alert!</h5>
        {{ session()->get('error') }}
    </div>
    @endif

    <div class="app-main__inner">

        <div class="d-flex justify-content-between mb-3">
            <h4>Overpanel Glass Type</h4>
            <a href="{{ route('Overpanel-Glass-Type.create') }}" class="btn btn-primary">
                + Add New
            </a>
        </div>
        @if ($auth->id != 1)
        <div class="mb-3 d-flex gap-2">
            <button id="updateSelected" class="btn btn-success  mr-2">
                <i class="fa fa-save"></i> Update Selected
            </button>

            <button id="exportSelected" class="btn btn-info">
                <i class="fa fa-download"></i> Export Selected
            </button>
        </div>
        @endif

        <ul class="nav nav-tabs mb-3" id="glassTypeTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" id="Glass-tab" data-toggle="tab" href="#Glass" role="tab">
                    Glass Type
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" id="Glazing-tab" data-toggle="tab" href="#Glazing" role="tab">
                    Glazing System
                </a>
            </li>
        </ul>

        <div class="tab-content">

            {{-- Glass TAB --}}
            <div class="tab-pane fade show active" id="Glass" role="tabpanel">

                <table class="table table-bordered table-hover overpanel_glass_Table" style="table-layout:auto;"
                    id="overpanel_glass_Table">
                    <thead>
                        <tr>
                            @if ($auth->id != 1)
                            <th>
                                <input type="checkbox" id="checkGlassAll">
                            </th>
                            @endif
                            <th>Streboard</th>
                            <th>Halspan</th>
                            <th>Flamebreak</th>
                            <th>Stredor</th>
                            <th>NFR</th>
                            <th>FD30</th>
                            <th>FD60</th>
                            <th>Integrity</th>
                            <th>Glass Name</th>
                            <th>Glass Thickness</th>
                            <th>Max Width(FL)</th>
                            <th>Max Height(Fl)</th>
                            <th>Side Sceen Width</th>
                            <th>Side Sceen Height</th>
                            <th>Action</th>
                            @if ($auth->id != 1)
                            <th style="min-width: 80px;">Price Per M2</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($items as $item)
                        <tr>
                            @if ($auth->id != 1)
                            <td>
                                <input type="checkbox" class="rowGlassCheck" value="{{ $item->id }}" {{ $item->selectedId ?
                                'checked' : '' }}>
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
                            <td>{{ $item->FanLightWidth }}</td>
                            <td>{{ $item->FanLightHeight }}</td>
                            <td>{{ $item->SideScreenWidth }}</td>
                            <td>{{ $item->SideScreenHeight }}</td>

                            @if ($item->EditBy != 1 || Auth::user()->UserType == 1)
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2">

                                    {{-- Edit --}}
                                    <a href="{{ route('Overpanel-Glass-Type.edit', $item->id) }}"
                                        class="action-icon text-success" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('Overpanel-Glass-Type.destroy', $item->id) }}" method="POST"
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

                            @if ($auth->id != 1 && $item->glassSelectedPrice !== null)
                            <td style="min-width: 80px;">
                                <input type="number" step="0.01" class="form-control GlasspriceInput"
                                    value="{{ number_format($item->glassSelectedPrice, 2, '.', '') }}"
                                    data-option-id="{{ $item->id }}" data-selected-id="{{ $item->selectedId }}"
                                    data-option-type="Overpanel_Glass_Type" onkeyup="chooseOptionCost(this)">
                            </td>
                            @elseif($auth->id != 1)
                            <td></td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>


            {{-- Glazing TAB --}}
            <div class="tab-pane fade" id="Glazing" role="tabpanel">
                <table class="table table-bordered table-hover overpanel_glazing_Table" style="table-layout:auto;"
                    id="overpanel_glazing_Table">
                    <thead>
                        <tr>
                            {{--  @if ($auth->id != 1)
                            <th>
                                <input type="checkbox" id="checkGlazingAll">
                            </th>
                            @endif  --}}
                            <th>Streboard</th>
                            <th>Halspan</th>
                            <th>Flamebreak</th>
                            <th>Stredor</th>
                            <th>NFR</th>
                            <th>FD30</th>
                            <th>FD60</th>
                            <th>Glass Name</th>
                            <th>Glazing System</th>
                            <th>Glazing Thickness</th>
                            <th>Beading</th>
                            <th>Beading Height</th>
                            <th>Beading Width</th>
                            <th>Glazing Bead Fixing Detail</th>
                            <th>Action</th>
                            @if ($auth->id != 1)
                            <th style="min-width: 80px;">Price Per M2</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($items as $item)
                        <tr>
                            {{--  @if ($auth->id != 1)
                            <td>
                                <input type="checkbox" class="rowGlazingCheck" value="{{ $item->id }}" {{ $item->selectedId ?
                                'checked' : '' }}>
                            </td>
                            @endif  --}}

                            <td class="text-center">{!! yesNoIcon($item->Streboard) !!}</td>
                            <td class="text-center">{!! yesNoIcon($item->Halspan) !!}</td>
                            <td class="text-center">{!! yesNoIcon($item->Flamebreak) !!}</td>
                            <td class="text-center">{!! yesNoIcon($item->Stredor) !!}</td>
                            <td class="text-center">{!! yesNoIcon($item->NFR) !!}</td>
                            <td class="text-center">{!! yesNoIcon($item->FD30) !!}</td>
                            <td class="text-center">{!! yesNoIcon($item->FD60) !!}</td>
                            <td><b>{{ $item->GlassType }}</b></td>
                            <td>{{ $item->GlazingSystem }}</td>
                            <td>{{ $item->GlazingThickness }}</td>
                            <td>{{ $item->Beading }}</td>
                            <td>{{ $item->BeadingHeight }}</td>
                            <td>{{ $item->BeadingWidth }}</td>
                            <td>{{ $item->FixingDetails }}</td>

                            @if ($item->EditBy != 1 || Auth::user()->UserType == 1)
                            <td class="text-center">
                                <div class="d-flex justify-content-center align-items-center gap-2">

                                    {{-- Edit --}}
                                    <a href="{{ route('Overpanel-Glass-Type.edit', $item->id) }}"
                                        class="action-icon text-success" title="Edit">
                                        <i class="fa fa-edit"></i>
                                    </a>

                                    {{-- Delete --}}
                                    <form action="{{ route('Overpanel-Glass-Type.destroy', $item->id) }}" method="POST"
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

                            @if ($auth->id != 1 && $item->glazingSelectedPrice !== null)
                            <td style="min-width: 80px;">
                                <input type="number" step="0.01" class="form-control GlazingpriceInput"
                                    value="{{ number_format($item->glazingSelectedPrice, 2, '.', '') }}"
                                    data-option-id="{{ $item->id }}" data-selected-id="{{ $item->selectedId }}"
                                    data-option-type="Overpanel_Glazing_System" onkeyup="chooseOptionCost(this)">
                            </td>
                            @elseif($auth->id != 1)
                            <td></td>
                            @endif
                        </tr>
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
    $(document).ready(function() {

       let GlassTable = $('.overpanel_glass_Table').DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            ordering: true,
            searching: true,
            responsive: true,
            columnDefs: [{ orderable: false, targets: [0, -1] }]
        });

        let GlazingTable = $('.overpanel_glazing_Table').DataTable({
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
        $('#checkGlassAll').on('change', function () {
            let checked = this.checked;

            GlassTable.rows().every(function () {
                $(this.node()).find('.rowGlassCheck').prop('checked', checked);
            });
        });

        $('#checkGlazingAll').on('change', function () {
            let checked = this.checked;

            GlazingTable.rows().every(function () {
                $(this.node()).find('.rowGlazingCheck').prop('checked', checked);
            });
        });


        $('#exportSelected').on('click', function (e) {
            e.preventDefault();

            let ids = [];

            GlassTable.rows().every(function () {
                let checkbox = $('input.rowGlassCheck', this.node());
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
            form.action = "{{ route('Overpanel-Glass-Type.exportSelected') }}";

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

            GlassTable.rows().every(function () {
                let row = $(this.node());
                let checkbox = row.find('.rowGlassCheck');

                if (checkbox.length && checkbox.prop('checked')) {
                    let priceInput = row.find('.GlasspriceInput');

                    data.push({
                        id: checkbox.val(),
                        type: 'Glass',
                        checked: true,
                        price: priceInput.length ? priceInput.val() : null
                    });
                }
            });

            GlazingTable.rows().every(function () {
                let row = $(this.node());
                let checkbox = row.find('.rowGlazingCheck');

                if (checkbox.length && checkbox.prop('checked')) {
                    let priceInput = row.find('.GlazingpriceInput');

                    data.push({
                        id: checkbox.val(),
                        type: 'Glazing',
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

            fetch("{{ route('Overpanel-Glass-Type.updateSelected') }}", {
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
            url: "{{ url('options/update-selected-option-cost') }}"
            , type: "POST"
            , dataType: "json"
            , data: formData
            , contentType: false
            , processData: false
            , success: function(result) {
                if (result.status === 'ok') {
                    console.log('Price updated');
                } else {
                    alert(result.msg || 'Something went wrong');
                }
            }
            , error: function() {
                alert('Server error');
            }
        });
    }

</script>
@endsection
