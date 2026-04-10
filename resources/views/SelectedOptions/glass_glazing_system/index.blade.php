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

        @if(Auth::user()->UserType == 1)
        <div class="card-body">
            <div class="tab-content">
                <form method="post" action="{{ route('option/import-glassglazing') }}" enctype="multipart/form-data">
                {{--  <form method="post" action="{{ route('option/import-glazing') }}" enctype="multipart/form-data">  --}}
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

        <ul class="nav nav-tabs mb-3" id="glazingSystemTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#StreBoard">StreBoard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#Halspan">Halspan</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#FlameBreak">FlameBreak</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#Stredoor">Stredoor</a>
            </li>
        </ul>

        <div class="tab-content">

            <div class="tab-pane fade show active" id="StreBoard">
                <table class="table table-bordered table-hover StreBoardTable">
                    <thead>
                        <tr>
                            <th>StreBoard</th>
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
                        @if($item->Streboard == 1)
                            @include('SelectedOptions.glass_glazing_system.row', ['item' => $item])
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="Halspan">
                <table class="table table-bordered table-hover HalspanTable">
                    <thead>
                        <tr>
                            <th>Halspan</th>
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
                        @if($item->Halspan == 2)
                            @include('SelectedOptions.glass_glazing_system.row', ['item' => $item])
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="FlameBreak">
                <table class="table table-bordered table-hover flameBreakTable">
                    <thead>
                        <tr>
                            <th>FlameBreak</th>
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
                        @if($item->Flamebreak == 7)
                            @include('SelectedOptions.glass_glazing_system.row', ['item' => $item])
                        @endif
                    @endforeach
                    </tbody>
                </table>
            </div>

            <div class="tab-pane fade" id="Stredoor">
                <table class="table table-bordered table-hover stredoorTable">
                    <thead>
                        <tr>
                            <th>Stredoor</th>
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
                        @if($item->Stredor == 8)
                            @include('SelectedOptions.glass_glazing_system.row', ['item' => $item])
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

    let streBoardTable = $('.StreBoardTable').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        ordering: true,
        searching: true,
        responsive: true,
        columnDefs: [{ orderable: false, targets: [0, -1] }]
    });

    let halspanTable = $('.HalspanTable').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        ordering: true,
        searching: true,
        responsive: true,
        columnDefs: [{ orderable: false, targets: [0, -1] }]
    });

    let flameBreakTable = $('.flameBreakTable').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        ordering: true,
        searching: true,
        responsive: true,
        columnDefs: [{ orderable: false, targets: [0, -1] }]
    });

    let stredoorTable = $('.stredoorTable').DataTable({
        pageLength: 10,
        lengthMenu: [10, 25, 50, 100],
        ordering: true,
        searching: true,
        responsive: true,
        columnDefs: [{ orderable: false, targets: [0, -1] }]
    });

    // 🔥 Fix DataTables when switching tabs
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        $.fn.dataTable.tables({ visible: true, api: true }).columns.adjust().responsive.recalc();
    });

});
</script>
@endsection

