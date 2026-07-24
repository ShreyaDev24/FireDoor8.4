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
            <h4>Screen Glass Type</h4>
            <a href="{{ route('Screen-Glass-Type.create') }}" class="btn btn-primary">
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

        <table class="table table-bordered table-hover" style="table-layout:auto;" id="screen_glass_typeTable">
            <thead>
                <tr>
                    @if($auth->id != 1)
                    <th>
                        <input type="checkbox" id="checkAll">
                    </th>
                    @endif
                    <th>Fire Rating</th>
                    <th>dB rating</th>
                    <th>Glass Name</th>
                    <th>Width Point 1 (far right)</th>
                    <th>Height Point 1 (Lowest)</th>
                    <th>Width Point 2 (Cloest)</th>
                    <th>Height Point 2 (Higest)</th>
                    <th>MIN Transom Thickness</th>
                    <th>MIN Transom Depth</th>
                    <th>MAX Area m2</th>
                    <th>Frame Density</th>
                    <th>Action</th>
                    @if($auth->id != 1)
                    <th style="min-width: 80px;">Price Per L/M</th>
                    @endif
                </tr>
            </thead>

            <tbody>
                @foreach($items as $item)
                <tr>
                    @if($auth->id != 1)
                    <td>
                        <input type="checkbox" class="rowCheck" value="{{ $item->id }}" {{ $item->selectedId ? 'checked'
                        :
                        '' }}>
                    </td>
                    @endif

                    <td class="text-center">{{ $item->FireRating }}</td>
                    <td class="text-center">{{ $item->DFRating }}</td>
                    <td>{{ $item->GlassType }}</td>
                    <td>{{ $item->WidthPoint1 }}</td>
                    <td>{{ $item->HeightPoint1 }}</td>
                    <td>{{ $item->WidthPoint2 }}</td>
                    <td>{{ $item->HeightPoint2 }}</td>
                    <td>{{ $item->TransomThickness }}</td>
                    <td>{{ $item->TransomDepth }}</td>
                    <td>{{ $item->AreaSize }}</td>
                    <td>{{ $item->FrameDensity }}</td>

                    @if($item->EditBy != 1 || Auth::user()->UserType == 1)

                    <td class="text-center">
                        <div class="d-flex justify-content-center align-items-center gap-2">

                            {{-- Edit --}}
                            <a href="{{ route('Screen-Glass-Type.edit', $item->id) }}"
                            class="action-icon text-success"
                            title="Edit">
                                <i class="fa fa-edit"></i>
                            </a>

                            {{-- Delete --}}
                            <form action="{{ route('Screen-Glass-Type.destroy', $item->id) }}"
                                method="POST"
                                onsubmit="return confirm('Are you sure?')"
                                class="m-0 p-0">
                                @csrf
                                @method('DELETE')

                                <button type="submit"
                                        class="action-icon text-danger border-0 bg-transparent"
                                        title="Delete">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </form>

                        </div>
                    </td>

                    @else
                    <td></td>
                    @endif

                    @if($auth->id != 1 && $item->glassSelectedPrice !== null)
                    <td style="min-width: 80px;">
                        <input type="number"
                            step="0.01"
                            class="form-control priceInput"
                            value="{{ number_format($item->glassSelectedPrice, 2, '.', '') }}"
                            data-option-id="{{ $item->id }}"
                            data-selected-id="{{ $item->selectedId }}"
                            data-option-type="SideScreen_Glass_Type"
                            onkeyup="chooseOptionCost(this)">
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

@endsection


@section('js')
<script>
$(document).ready(function () {

    let table = $('#screen_glass_typeTable').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        ordering: true,
        searching: true,
        responsive: true,
        columnDefs: [
            { orderable: false, targets: [0, -1] }
        ]
    });

    /* ============================
       CHECK ALL (ALL PAGES)
    ============================ */
    $('#checkAll').on('change', function () {
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

        if (!confirm(`Update ${data.length} selected records?`)) {
            return;
        }

        fetch("{{ route('Screen-Glass-Type.updateSelected') }}", {
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
            }
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

$('#exportSelected').on('click', function () {

    let ids = [];
    let table = $('#screen_glass_typeTable').DataTable();

    table.rows().every(function () {
        let row = $(this.node());
        let checkbox = row.find('.rowCheck');

        if (checkbox.length && checkbox.prop('checked')) {
            ids.push(checkbox.val());
        }
    });

    if (!ids.length) {
        alert('Please select at least one record to export.');
        return;
    }

    // Create a hidden form and submit (browser will download CSV)
    let form = document.createElement('form');
    form.method = 'POST';
    form.action = "{{ route('Screen-Glass-Type.exportSelected') }}";

    let token = document.createElement('input');
    token.type = 'hidden';
    token.name = '_token';
    token.value = "{{ csrf_token() }}";
    form.appendChild(token);

    let idsInput = document.createElement('input');
    idsInput.type = 'hidden';
    idsInput.name = 'ids';
    idsInput.value = JSON.stringify(ids);
    form.appendChild(idsInput);

    document.body.appendChild(form);
    form.submit();
    form.remove();
});
</script>


@endsection


