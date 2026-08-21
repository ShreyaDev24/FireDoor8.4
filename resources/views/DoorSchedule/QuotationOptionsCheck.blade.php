@extends("layouts.Master")

@section("main_section")
<div class="app-main__outer">
    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-attention icon-gradient bg-tempting-azure"></i>
                    </div>
                    <div>
                        Selected Options Check
                        <div class="page-title-subheading">{{ $folderName }}</div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <a href="{{ url('quotation/documents/' . $quotation->id . '/' . $versionId) }}"
                       class="btn-shadow btn btn-secondary">Back to Folder</a>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-4">
                <div class="card mb-3 widget-content bg-success">
                    <div class="widget-content-wrapper text-white">
                        <div class="widget-content-left">
                            <div class="widget-heading">Values still available</div>
                        </div>
                        <div class="widget-content-right">
                            <div class="widget-numbers text-white"><span>{{ $summary['ok'] }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-3 widget-content bg-warning">
                    <div class="widget-content-wrapper text-white">
                        <div class="widget-content-left">
                            <div class="widget-heading">Removed from Selected Options</div>
                        </div>
                        <div class="widget-content-right">
                            <div class="widget-numbers text-white"><span>{{ $summary['not_selected'] }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card mb-3 widget-content bg-danger">
                    <div class="widget-content-wrapper text-white">
                        <div class="widget-content-left">
                            <div class="widget-heading">No longer exists at all</div>
                        </div>
                        <div class="widget-content-right">
                            <div class="widget-numbers text-white"><span>{{ $summary['missing'] }}</span></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-header">Doors pointing at an option you can no longer pick</div>
            <div class="card-body">
                @if(count($rows) == 0)
                    <div class="text-center p-4">
                        <h5 class="text-success">Every door checks out</h5>
                        <p class="text-muted mb-0">
                            All option values on all doors in this version are still present in Selected Options.
                        </p>
                    </div>
                @else
                    <table style="width: 100%;" class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th>Door Type</th>
                                <th>Option</th>
                                <th>Value on the door</th>
                                <th style="width: 230px;">Status</th>
                                <th style="width: 100px;">Door</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($rows as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row['door_type'] }}</td>
                                <td>{{ $row['label'] }}</td>
                                <td><strong>{{ $row['value'] }}</strong></td>
                                <td>
                                    @if($row['status'] === 'not_selected')
                                        <span class="badge badge-warning">Removed from Selected Options</span>
                                    @else
                                        <span class="badge badge-danger">No longer exists</span>
                                    @endif
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-info"
                                       href="{{ url('quotation/documents/' . $quotation->id . '/' . $versionId . '/view') }}?itemId={{ $row['item_id'] }}">
                                        View
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <p class="text-muted mb-0">
                        <strong>Removed from Selected Options</strong> means the option still exists in the master
                        list but is no longer ticked, so it can be put back.
                        <strong>No longer exists</strong> means it is gone from the master list entirely.
                    </p>
                @endif

                @if(count($skipped) > 0)
                    <div class="alert alert-info mt-3 mb-0">
                        Not checked (table or column not found):
                        {{ implode(', ', array_keys($skipped)) }}
                    </div>
                @endif
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-header">Selected Options add / remove log</div>
            <div class="card-body">
                @if(count($recent) == 0)
                    <p class="text-muted mb-0">
                        Nothing recorded yet. The current Selected Options have been stored as the baseline —
                        anything added or removed from now on will be listed here.
                    </p>
                @else
                    <table style="width: 100%;" class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 150px;">Detected</th>
                                <th>Option Type</th>
                                <th>Value</th>
                                <th style="width: 120px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($recent as $log)
                            <tr>
                                <td>{{ $log->created_at }}</td>
                                <td>{{ $log->option_type }}</td>
                                <td>{{ $log->option_key }}</td>
                                <td>
                                    @if($log->action === 'removed')
                                        <span class="badge badge-danger">Removed</span>
                                    @else
                                        <span class="badge badge-success">Added</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                @endif

                <p class="text-muted mb-0 mt-2">
                    <strong>Detected</strong> is when this page noticed the change, not when someone made it.
                    Options are removed through several code paths and none of them record a timestamp.
                </p>
            </div>
        </div>

    </div>
</div>
@endsection
