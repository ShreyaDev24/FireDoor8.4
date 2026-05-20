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
            <h4 class="mb-0">Glass Glazing System</h4>

            <div>
                <a href="{{ route('Glass-Glazing-System.create') }}" class="btn btn-primary me-2">
                    + Add New
                </a>
            </div>
        </div>

        @php
        $tabs = [
            ['id' => 'StreBoard', 'field' => 'Streboard', 'value' => 1, 'class' => 'streBoardTable', 'title' => 'StreBoard'],
            ['id' => 'Halspan', 'field' => 'Halspan', 'value' => 2, 'class' => 'halspanTable', 'title' => 'Halspan'],
            ['id' => 'Flamebreak', 'field' => 'Flamebreak', 'value' => 7, 'class' => 'flameBreakTable', 'title' => 'FlameBreak'],
            ['id' => 'Stredor', 'field' => 'Stredor', 'value' => 8, 'class' => 'stredoorTable', 'title' => 'Stredoor'],
            ['id' => 'VicaimaDoorCore', 'field' => 'VicaimaDoorCore', 'value' => 4, 'class' => 'VicaimaTable', 'title' => 'VicaimaDoorCore'],
            ['id' => 'Seadec', 'field' => 'Seadec', 'value' => 5, 'class' => 'SeadecTable', 'title' => 'Seadec'],
            ['id' => 'Deanta', 'field' => 'Deanta', 'value' => 6, 'class' => 'DeantaTable', 'title' => 'Deanta'],
            ['id' => 'MMM', 'field' => 'MMM', 'value' => 9, 'class' => 'MMMTable', 'title' => 'MMM'],
        ];
        @endphp

        <ul class="nav nav-tabs mb-3" role="tablist">
            @foreach($tabs as $key => $tab)
                <li class="nav-item">
                    <a class="nav-link {{ $key == 0 ? 'active' : '' }}" data-toggle="tab" href="#{{ $tab['id'] }}">
                        {{ $tab['title'] }}
                    </a>
                </li>
            @endforeach
        </ul>

        <div class="tab-content">
            @foreach($tabs as $key => $tab)
                <div class="tab-pane fade {{ $key == 0 ? 'show active' : '' }}" id="{{ $tab['id'] }}">

                    <table class="table table-bordered table-hover dataTable {{ $tab['class'] }}">
                        <thead>
                            <tr>
                                <th>{{ $tab['title'] }}</th>
                                <th>NFR</th>
                                <th>FD30</th>
                                <th>FD60</th>
                                <th>Glass Type</th>
                                <th>Glazing System</th>
                                <th>VP Area Size</th>
                                <th>Action</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach($items as $item)
                                @if($item->{$tab['field']} == $tab['value'])
                                    @include('SelectedOptions.glass_glazing_system.row', ['item' => $item])
                                @endif
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

    $('.dataTable').each(function () {
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

    // Fix on tab switch
    $('a[data-toggle="tab"]').on('shown.bs.tab', function () {
        tables.forEach(function (table) {
            table.columns.adjust().responsive.recalc();
        });
    });

});
</script>
@endsection

