@extends("layouts.Master")

@section("main_section")
<script>
    function Tooltip(tooltipValue) {
        let TooltipCode2 =
            `<i class="fa fa-info-circle field_info tooltip" aria-hidden="true">
                <span class="tooltiptext info_tooltip">` + tooltipValue + `</span>
            </i>`;
        return TooltipCode2;
    }
</script>
<div class="app-main__outer">
    <div class="app-main__inner">

        @if(session()->has('success'))
        <div class="alert alert-success alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
            <h5><i class="icon fas fa-check"></i> Alert!</h5>
            {{ session()->get('success') }}
        </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible">
                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                <h5><i class="fas fa-skull-crossbones"></i> Alert!</h5>
                <ul>
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="tab-content mb-5" id="ironmongery" data-tab-content>
            <div class="row">
                <div class="col-sm-12">
                    <div class="custom_card">
                        <div class="row">
                            <div class="col-sm-6">
                                <div class="card-header"><h5 class="card-title">Ironmongery <span>Set</span></h5></div>
                            </div>
                            <div class="col-sm-6 ">
                                <a href="{{route('ironmongeryadd')}}" class="btn-shadow btn btn-info float-right">
                                    <i class="fa fa-edit" aria-hidden="true"></i> Add Ironmongery Set
                                </a>
                            </div>
                            <div class="col-sm-6">
                                <a href="{{ route('folders.create') }}" class="btn btn-info mb-2">
                                    <i class="fas fa-folder-plus"></i> Add Folder
                                </a>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table style="width: 100%;" id="example" class="table table-striped table-bordered">
                                    <thead class="text-uppercase table-header-bg">
                                        <tr class="text-white">
                                            <th>Folder Name</th>
                                            <th>Ironmongery Set Count</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($folders as $folder)
                                        <tr>
                                            <td>{{ $folder->name }}</td>
                                            <td>{{ $folder->ironmongery_sets_count }}</td>
                                            <td>
                                                <a href="{{ route('folders.show', $folder->id) }}" class="btn btn-info">View</a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

