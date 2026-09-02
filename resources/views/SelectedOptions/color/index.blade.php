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

        <div class="d-flex justify-content-between mb-3">
            <h4>Colour List</h4>
            <a href="{{ route('Colour-List.create') }}" class="btn btn-primary">
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

        <ul class="nav nav-tabs mb-3" role="tablist">
            @foreach($items as $facing => $colors)
            <li class="nav-item">
                <a class="nav-link {{ $loop->first ? 'active' : '' }}" data-toggle="tab"
                    href="#{{ Str::slug($facing) }}">
                    {{ $facing }}
                </a>
            </li>
            @endforeach
        </ul>

        <div class="tab-content">

            @foreach($items as $facing => $colors)

            <div class="tab-pane fade {{ $loop->first ? 'show active' : '' }}" id="{{ Str::slug($facing) }}">

                <table class="table table-bordered table-hover colorTable">
                    <thead>
                        <tr>
                            @if($auth->id != 1)
                            <th>
                                <input type="checkbox" class="checkAll">
                            </th>
                            @endif
                            <th>Colour Name</th>
                            <th>Hex</th>
                            <th>Door Leaf Facing</th>
                            <th>Door Leaf Facing Value</th>
                            <th>Action</th>
                            @if($auth->id != 1)
                            <th style="min-width:80px;">{{ $facing == 'Laminate' ? 'Price per Sheet' : 'Price Per Unit' }}</th>
                            @endif
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($colors as $item)
                        @include('SelectedOptions.color.row', ['item' => $item])
                        @endforeach
                    </tbody>

                </table>

            </div>

            @endforeach

        </div>
    </div>
</div>

@endsection


@section('js')
<script>
$(document).ready(function () {

    let tables = [];

    $('.colorTable').each(function () {

        let table = $(this).DataTable({
            pageLength: 10,
            lengthMenu: [10, 25, 50, 100],
            ordering: true,
            searching: true,
            responsive: true,
            columnDefs: [{ orderable: false, targets: [0, -1] }]
        });

        tables.push(table);
    });


    /* ============================
       CHECK ALL (ALL PAGES)
    ============================ */
    $(document).on('change', '.checkAll', function () {

        let table = $(this).closest('table').DataTable();
        let checked = this.checked;

        table.rows().every(function () {
            $(this.node()).find('.rowCheck').prop('checked', checked);
        });

    });

    /* ============================
       UPDATE SELECTED BUTTON
    ============================ */
    $('#updateSelected').on('click', function () {

        let data = [];

        tables.forEach(function (table) {

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

        fetch("{{ route('Colour-List.updateSelected') }}", {
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


        tables.forEach(function (table) {

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
        form.action = "{{ route('Colour-List.exportSelected') }}";

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
