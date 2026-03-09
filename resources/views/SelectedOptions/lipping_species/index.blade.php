@extends("layouts.Master")

@section("main_section")
<style>
    .card {
        border-radius: 8px;
    }

    .table-sm td,
    .table-sm th {
        padding: 7px 10px;
        font-size: 13px;
    }

    .priceInput {
        height: 30px;
    }

    .badge {
        font-size: 11px;
        padding: 4px 8px;
    }

    .card-header {
        background: #f8f9fa;
    }

    .card-header h6 {
        font-size: 14px;
    }

    .table tbody tr:hover {
        background: #f5f7fb;
    }

    .form-check-input{
        margin-top: 3px;
    }

    .form-check-label{
        font-weight: 500;
        cursor: pointer;
    }

    .gap-2{
        gap:10px;
    }
</style>
<div class="app-main__outer">

    <div class="app-main__inner">

        <div class="d-flex justify-content-between align-items-center mb-3">

            <h4 class="mb-0">Timber Species</h4>

            <div class="d-flex align-items-center gap-2">

                @if($auth->id != 1)
                <div class="form-check mr-3">
                    <input type="checkbox" class="form-check-input" id="checkAllSpecies">
                    <label class="form-check-label" for="checkAllSpecies">
                        Check All
                    </label>
                </div>

                <button id="updateSelected" class="btn btn-success  mr-2">
                    <i class="fa fa-save"></i> Update Selected
                </button>

                <button id="exportSelected" class="btn btn-info">
                    <i class="fa fa-download"></i> Export Selected
                </button>
                @endif

                <a href="{{ url('setting/lipping-species') }}" class="btn btn-primary">
                    + Add New
                </a>

            </div>

        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <input type="text" id="tableSearch" class="form-control form-control-sm"
                    placeholder="Search species, inch, mm...">
            </div>
        </div>

        @foreach($species as $row)

            @if($row->lipping_species_items->count())

            <div class="card mb-4 shadow-sm">

                <div class="card-header d-flex justify-content-between align-items-center py-2">

                    <h6 class="mb-0 font-weight-bold">
                        {{ $row->SpeciesName }}
                        <span class="text-muted">({{ $row->MinValue }} - {{ $row->MaxValues }})</span>
                    </h6>

                    @if($auth->id != 1)
                    <div>
                        <label class="mb-0 small text-muted mr-2">Select All</label>
                        <input type="checkbox" class="species-checkall" data-species="{{ $row->id }}">
                    </div>
                    @endif

                </div>


                <div class="card-body p-0">

                    <table class="table table-sm table-hover table-striped mb-0">

                        <thead class="bg-light">
                            <tr>
                                <th width="30%">Species</th>
                                <th class="text-center">Inch</th>
                                <th class="text-center">MM</th>
                                <th class="text-center">Status</th>
                                @if($auth->id != 1)
                                <th width="160" class="text-center">Price / M3</th>
                                <th width="90" class="text-center">Select</th>
                                @endif
                            </tr>
                        </thead>

                        <tbody>
{{--  @dd($row->lipping_species_items);  --}}
                            @foreach($row->lipping_species_items as $item)

                            @php
                            $selected = $item->selected_lipping_species_items->first();
                            @endphp

                            <tr>

                                <td>
                                    <span class="text-success mr-1">
                                        <i class="fa fa-check-circle"></i>
                                    </span>
                                    <b>{{ $row->SpeciesName }}</b>
                                </td>

                                <td class="text-center">{{ $item->thickness }}</td>

                                <td class="text-center">
                                    {{ number_format($item->thickness * 25.4,1) }}
                                </td>

                                <td class="text-center">

                                    @if($item->status)
                                    <span class="badge badge-success">Active</span>
                                    @else
                                    <span class="badge badge-danger">Inactive</span>
                                    @endif

                                </td>

                                @if($auth->id != 1 && $selected?->selected_price !== null)
                                <td>
                                    <input type="number"
                                        step="0.01"
                                        class="form-control form-control-sm text-center priceInput"
                                        value="{{ $selected->selected_price ?? 0 }}"
                                        data-option-id="{{ $row->id }}"
                                        data-selected-id="{{ $selected?->id }}"
                                        data-option-type="lippingSpecies"
                                        onkeyup="chooseOptionCost(this)">
                                </td>
                                @elseif($auth->id != 1)
                                <td></td>
                                @endif

                                @if($auth->id != 1)
                                <td class="text-center">

                                    <input type="checkbox"
                                    class="rowCheck species-{{ $row->id }}"
                                    name="items[{{ $item->id }}][checked]"
                                    value="{{ $item->id }}"
                                    @checked($selected)>

                                </td>
                                @endif

                            </tr>

                            @endforeach

                        </tbody>

                    </table>

                </div>

            </div>

            @endif

        @endforeach


    </div>

</div>

@endsection


@section('js')
<script>
$(document).on('change','.species-checkall',function(){

    let species = $(this).data('species');

    $('.species-'+species).prop('checked',this.checked);

});

$('#checkAllSpecies').on('change', function () {

    let checked = $(this).prop('checked');

    $('.rowCheck').prop('checked', checked);
    $('.species-checkall').prop('checked', checked);

});

$('#tableSearch').on('keyup', function () {

    let value = $(this).val().toLowerCase();

    $('.card').each(function () {

        let match = false;

        $(this).find('tbody tr').each(function () {

            if ($(this).text().toLowerCase().indexOf(value) > -1) {
                $(this).show();
                match = true;
            } else {
                $(this).hide();
            }

        });

        if(match){
            $(this).show();
        }else{
            $(this).hide();
        }

    });

});

function chooseOptionCost(input) {

    let price = parseFloat(input.value);
    if (isNaN(price) || price < 0) {
        alert('Invalid value entered!');
        return;
    }

    let formData = new FormData();
    formData.append('SelectedOptionCost', price.toFixed(2));
    formData.append('id', input.dataset.optionId);
    formData.append('SelectedOptionId', input.dataset.selectedId);
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

// Page load hone tak loader show
$(document).ready(function () {
    $('.loader').show();

    $('#exportSelected').on('click', function (e) {

        e.preventDefault();

        let ids = [];

        $('.rowCheck:checked').each(function () {
            ids.push($(this).val());
        });

        if (ids.length === 0) {
            alert('Please select at least one record to export.');
            return;
        }

        let form = document.createElement('form');
        form.method = 'POST';
        form.action = "{{ route('Lipping-Species.exportSelected') }}";

        let token = document.createElement('input');
        token.type = 'hidden';
        token.name = '_token';
        token.value = "{{ csrf_token() }}";
        form.appendChild(token);

        ids.forEach(function(id){
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

        $('.rowCheck:checked').each(function () {

            let row = $(this).closest('tr');

            data.push({
                id: $(this).val(),
                checked: true,
                price: row.find('.priceInput').val()
            });

        });

        if (data.length === 0) {
            alert('Please select at least one row.');
            return;
        }

        if (!confirm(`Update ${data.length} selected records?`)) {
            return;
        }

        fetch("{{ route('Lipping-Species.updateSelected') }}", {
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
});

// Sab kuch load hone ke baad loader hide
$(window).on('load', function () {
    $('.loader').fadeOut(200);
});

</script>

@endsection
