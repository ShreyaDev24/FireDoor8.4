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

    .price-column {
        padding: 8px !important;
        vertical-align: top !important;
        min-width: 320px;
    }

    .price-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 8px 16px;
    }

    .price-item {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }

    .price-item span {
        font-size: 12px;
        color: #555;
        white-space: nowrap;
    }

    .price-input {
        width: 70px;
        height: 26px;
        padding: 2px 6px;
        font-size: 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
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

        <div class="d-flex justify-content-between mb-3">
            <h4>Door Dimension</h4>
            <a href="{{ route('Door-Dimension-Custom.create') }}" class="btn btn-primary">
                + Add New
            </a>
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

        <ul class="nav nav-tabs mb-3" id="glazingSystemTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#Streboard">Streboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#Halspan">Halspan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#Flamebreak">Flamebreak</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#Stredoor">Stredoor</a>
            </li>
        </ul>

        <div class="tab-content">

            <div class="tab-pane fade show active" id="Streboard">
                <table class="table table-bordered table-hover StreboardTable">
                    <thead>
                        <tr>
                            @if($auth->id != 1)
                            <th>
                                <input type="checkbox" id="StreboardcheckAll">
                            </th>
                            @endif
                            <th>Strebord</th>
                            <th>FireRating</th>
                            <th>MMWidth x MMHeight</th>
                            <th>Action</th>
                            @if($auth->id != 1)
                            <th style="min-width: 80px;">Price Per Unit</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        @if($item->configurableitems == 1)
                            @include('SelectedOptions.door_dimension_custom.row', ['item' => $item])
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="Halspan">
                <table class="table table-bordered table-hover HalspanTable">
                    <thead>
                        <tr>
                            @if($auth->id != 1)
                            <th>
                                <input type="checkbox" id="HalspancheckAll">
                            </th>
                            @endif
                            <th>Halspan</th>
                            <th>FireRating</th>
                            <th>MMWidth x MMHeight</th>
                            <th>Action</th>
                            @if($auth->id != 1)
                            <th style="min-width: 80px;">Price Per Unit</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        @if($item->configurableitems == 2)
                            @include('SelectedOptions.door_dimension_custom.row', ['item' => $item])
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="Flamebreak">
                <table class="table table-bordered table-hover FlamebreakTable">
                    <thead>
                        <tr>
                            @if($auth->id != 1)
                            <th>
                                <input type="checkbox" id="FlamebreakcheckAll">
                            </th>
                            @endif
                            <th>Flamebreak</th>
                            <th>FireRating</th>
                            <th>MMWidth x MMHeight</th>
                            <th>Action</th>
                            @if($auth->id != 1)
                            <th style="min-width: 80px;">Price Per Unit</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        @if($item->configurableitems == 7)
                            @include('SelectedOptions.door_dimension_custom.row', ['item' => $item])
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="Stredoor">
                <table class="table table-bordered table-hover StredoorTable">
                    <thead>
                        <tr>
                            @if($auth->id != 1)
                            <th>
                                <input type="checkbox" id="StredoorcheckAll">
                            </th>
                            @endif
                            <th>Stredoor</th>
                            <th>FireRating</th>
                            <th>Width x Height(MM)</th>
                            <th>Action</th>
                            @if($auth->id != 1)
                            <th style="min-width: 80px;">Price Per Unit</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($items as $item)
                        @if($item->configurableitems == 8)
                            @include('SelectedOptions.door_dimension_custom.row', ['item' => $item])
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

    let StreboardTable = $('.StreboardTable').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        ordering: true,
        searching: true,
        responsive: true,
        columnDefs: [{ orderable: false, targets: [0, -1] }]
    });

    let HalspanTable = $('.HalspanTable').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        ordering: true,
        searching: true,
        responsive: true,
        columnDefs: [{ orderable: false, targets: [0, -1] }]
    });

    let FlamebreakTable = $('.FlamebreakTable').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        ordering: true,
        searching: true,
        responsive: true,
        columnDefs: [{ orderable: false, targets: [0, -1] }]
    });

    let StredoorTable = $('.StredoorTable').DataTable({
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
    $('#FlamebreakcheckAll').on('change', function () {
        let checked = this.checked;

        FlamebreakTable.rows().every(function () {
            $(this.node()).find('.rowCheck').prop('checked', checked);
        });
    });

    $('#StreboardcheckAll').on('change', function () {
        let checked = this.checked;

        StreboardTable.rows().every(function () {
            $(this.node()).find('.rowCheck').prop('checked', checked);
        });
    });

    $('#StredoorcheckAll').on('change', function () {
        let checked = this.checked;

        StredoorTable.rows().every(function () {
            $(this.node()).find('.rowCheck').prop('checked', checked);
        });
    });

    $('#HalspancheckAll').on('change', function () {
        let checked = this.checked;

        HalspanTable.rows().every(function () {
            $(this.node()).find('.rowCheck').prop('checked', checked);
        });
    });

    /* ============================
       UPDATE SELECTED BUTTON
    ============================ */
    $('#updateSelected').on('click', function () {

        let data = [];

        [StreboardTable, HalspanTable, FlamebreakTable, StredoorTable].forEach(function (table) {
            table.rows().every(function () {
                let row = $(this.node());
                let checkbox = row.find('.rowCheck');

                if (checkbox.length && checkbox.prop('checked')) {
                    data.push({
                        id: checkbox.val(),
                        checked: true,
                        price: row.find('.priceInput').val()
                    });
                }
            });
        });

        if (data.length === 0) {
            alert('Please select at least one row.');
            return;
        }

        if (!confirm(`Update ${data.length} selected records?`)) {
            return;
        }

        fetch("{{ route('Door-Dimension-Custom.updateSelected') }}", {
            method: "POST",
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ rows: data })
        })
        .then(res => res.json())
        .then(resp => {
            if (resp.status === 'ok') {
                location.reload();
            } else {
                alert(resp.msg || 'Update failed');
            }
        })
        .catch(() => alert('Server error'));
    });

    $('#exportSelected').on('click', function (e) {
        e.preventDefault();

        let ids = [];

        [StreboardTable, HalspanTable, FlamebreakTable, StredoorTable].forEach(function (table) {
            table.rows().every(function () {
                let checkbox = $('input.rowCheck', this.node());
                if (checkbox.length && checkbox.prop('checked')) {
                    ids.push(checkbox.val());
                }
            });
        });

        if (!ids.length) {
            alert('Please select at least one record to export.');
            return;
        }

        let form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('Door-Dimension-Custom.exportSelected') }}";

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
});

$(document).on('input', '.cost-input', function () {

    let price = $(this).val();
    let id = $(this).data('optionid');
    let selectedId = $(this).data('selectedid');
    let leafTypeId = $(this).data('leaftypeid');
    let OptionType = 'door_dimension_custome'; // change if dynamic

    chooseoptioncostcustome(price, id, selectedId, OptionType, leafTypeId);
});

function chooseoptioncostcustome(price, id, selectedId, OptionType, leafTypeId) {

    console.log("Price:", price);
    console.log("Option ID:", id);
    console.log("Selected ID:", selectedId);
    console.log("Leaf ID:", leafTypeId);

    if (price === '' || price < 0) {
        alert("Invalid Value Entered!");
        return false;
    }

    let formData = new FormData();

    formData.append('price', parseFloat(price).toFixed(2));
    formData.append('id', id);
    formData.append('selectedId', selectedId ?? '');
    formData.append('OptionType', OptionType);
    formData.append('leafid', leafTypeId);
    formData.append('_token', '{{ csrf_token() }}');

    $.ajax({
        url: "{{ route('Door-Dimension-Custom.updateSelectedCost') }}",
        type: "POST",
        data: formData,
        dataType: "json",
        contentType: false,
        processData: false,

        success: function (result) {

            if (result.status === "ok") {

                console.log("Price updated successfully");

                // 🔥 IMPORTANT:
                // If new record created, update selectedId in input
                if (result.selectedId) {
                    $('.cost-input[data-optionid="'+id+'"][data-leaftypeid="'+leafTypeId+'"]')
                        .attr('data-selectedid', result.selectedId);
                }

            } else {
                alert(result.msg);
            }
        },

        error: function (xhr) {
            console.error("Update failed:", xhr.responseText);
        }
    });
}


</script>


@endsection


