@extends("layouts.Master")

@section("main_section")
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
            <h4>Door Leaf Facing</h4>
            <a href="{{ route('door-leaf-facing.create') }}" class="btn btn-primary">
                + Add New
            </a>
        </div>
        @if($auth->id != 1)
        <div class="mb-3">
            <button id="updateSelected" class="btn btn-success">
                <i class="fa fa-save"></i> Update Selected
            </button>
        </div>
        @endif


        <table class="table table-bordered table-hover"  id="doorLeafFacingTable">
            <thead>
                <tr>
                    @if($auth->id != 1)
                    <th>
                        <input type="checkbox" id="checkAll">
                    </th>
                    @endif
                    <th>Streboard</th>
                    <th>Halspan</th>
                    <th>Flamebreak</th>
                    <th>Stredor</th>
                    <th>Vicaima</th>
                    <th>Seadec</th>
                    <th>Deanta</th>
                    <th>MMM</th>
                    <th>Option</th>
                    <th>Name</th>
                    <th>Action</th>
                    @if($auth->id != 1)
                    <th>Price / m²</th>
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

                    <td class="text-center">{!! yesNoIcon($item->Streboard) !!}</td>
                    <td class="text-center">{!! yesNoIcon($item->Halspan) !!}</td>
                    <td class="text-center">{!! yesNoIcon($item->Flamebreak) !!}</td>
                    <td class="text-center">{!! yesNoIcon($item->Stredor) !!}</td>
                    <td class="text-center">{!! yesNoIcon($item->VicaimaDoorCore) !!}</td>
                    <td class="text-center">{!! yesNoIcon($item->Seadec) !!}</td>
                    <td class="text-center">{!! yesNoIcon($item->Deanta) !!}</td>
                    <td class="text-center">{!! yesNoIcon($item->MMM) !!}</td>

                    <td><b>{{ $item->doorLeafFacing }}</b></td>
                    <td>{{ $item->doorLeafFacingValue }}</td>

                    @if($item->editBy != 1 || Auth::user()->UserType == 1)

                    <td>
                        <a href="{{ route('door-leaf-facing.edit',$item->id) }}" class="btn btn-sm btn-success">
                            <i class="fa fa-edit"></i>
                        </a>

                        <form action="{{ route('door-leaf-facing.destroy',$item->id) }}" method="POST" class="d-inline">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </td>
                    @else
                    <td></td>
                    @endif

                    @if($auth->id != 1 && $item->selectedPrice !== null)
                    <td>
                        <input type="number"
                            step="0.01"
                            class="form-control priceInput"
                            value="{{ number_format($item->selectedPrice, 2, '.', '') }}"
                            data-option-id="{{ $item->id }}"
                            data-selected-id="{{ $item->selectedId }}"
                            data-option-type="door_leaf_facing_value"
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

    let table = $('#doorLeafFacingTable').DataTable({
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

        fetch("{{ route('door-leaf-facing.updateSelected') }}", {
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
</script>


@endsection


