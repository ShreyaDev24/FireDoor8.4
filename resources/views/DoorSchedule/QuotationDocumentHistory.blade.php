@extends("layouts.Master")

@section("main_section")
<div class="app-main__outer">
    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-clock icon-gradient bg-tempting-azure"></i>
                    </div>
                    <div>
                        Change History
                        <div class="page-title-subheading">
                            {{ $folderName }}
                            @if($doorType !== '')
                                &nbsp;/&nbsp; {{ $doorType }}
                            @else
                                &nbsp;/&nbsp; all door types
                            @endif
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <a href="{{ url('quotation/documents/' . $quotation->id . '/' . $versionId) }}"
                       class="btn-shadow btn btn-secondary">Back to Folder</a>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card">
            <div class="card-body">
                <form method="GET" class="form-inline mb-3"
                      action="{{ url('quotation/documents/' . $quotation->id . '/' . $versionId . '/history') }}">
                    <label class="mr-2">Door type</label>
                    <select name="itemId" class="form-control mr-2" onchange="this.form.submit()">
                        <option value="">All door types</option>
                        @foreach($doorTypes as $dt)
                            <option value="{{ $dt['itemId'] }}" {{ (string) $itemId === (string) $dt['itemId'] ? 'selected' : '' }}>
                                {{ $dt['name'] }}
                            </option>
                        @endforeach
                    </select>
                    <noscript><button type="submit" class="btn btn-primary">Filter</button></noscript>
                </form>

                @if(count($logs) == 0)
                    <div class="text-center p-4">
                        <h5>No changes recorded yet</h5>
                        <p class="text-muted mb-0">
                            Entries appear here the next time a door in this quotation is edited.
                            Changes made before the history feature was switched on are not shown.
                        </p>
                    </div>
                @else
                    <table style="width: 100%;" class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 150px;">When</th>
                                <th style="width: 140px;">Who</th>
                                <th style="width: 160px;">Door Type (at the time)</th>
                                <th>Field</th>
                                <th>Before</th>
                                <th>After</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($logs as $log)
                            <tr>
                                <td>{{ $log->created_at }}</td>
                                <td>{{ $userNames[$log->changed_by] ?? '—' }}</td>
                                <td>{{ $log->door_type }}</td>
                                <td>{{ $log->label ?: $log->field }}</td>
                                <td><span class="text-danger">{{ $log->old_value === null || $log->old_value === '' ? '(empty)' : $log->old_value }}</span></td>
                                <td><span class="text-success">{{ $log->new_value === null || $log->new_value === '' ? '(empty)' : $log->new_value }}</span></td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <p class="text-muted mb-0">Showing the most recent {{ count($logs) }} change(s).</p>
                @endif
            </div>
        </div>

    </div>
</div>
@endsection
