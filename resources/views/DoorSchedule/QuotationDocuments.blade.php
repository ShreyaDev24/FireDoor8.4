@extends("layouts.Master")

@section("main_section")
<div class="app-main__outer">
    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-folder icon-gradient bg-tempting-azure"></i>
                    </div>
                    <div>
                        {{ $folderName }}
                        <div class="page-title-subheading">
                            @if(!empty($project))
                                {{ $project->ProjectName }} &nbsp;/&nbsp;
                            @endif
                            {{ $quotation->QuotationName }}
                            @if(!empty($quotation->QuotationGenerationId))
                                ({{ $quotation->QuotationGenerationId }})
                            @endif
                            &nbsp;/&nbsp; {{ $versionName }}
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <a href="{{ url('quotation/generate/' . $quotation->id . '/' . $versionId) }}"
                       class="btn-shadow btn btn-secondary mr-2">Back to Quotation</a>
                    <a href="{{ url('quotation/documents/' . $quotation->id . '/' . $versionId . '/options-check') }}"
                       class="btn-shadow btn btn-warning mr-2">Options Check</a>
                    <a href="{{ url('quotation/documents/' . $quotation->id . '/' . $versionId . '/history') }}"
                       class="btn-shadow btn btn-info mr-2">Change History</a>
                    @if(count($doorTypes) > 0)
                        <a href="{{ url('quotation/documents/' . $quotation->id . '/' . $versionId . '/download-all') }}"
                           class="btn-shadow btn btn-primary">Download All (ZIP)</a>
                    @endif
                </div>
            </div>
        </div>

        @if(session()->has('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="main-card mb-3 card">
            <div class="card-header">
                Door Files
                <div class="btn-actions-pane-right">
                    <span class="badge badge-pill badge-info">
                        {{ count($doorTypes) }} door type{{ count($doorTypes) == 1 ? '' : 's' }}
                    </span>
                </div>
            </div>
            <div class="card-body">

                @if(count($doorTypes) == 0)
                    <div class="text-center p-4">
                        <h5>This folder is empty</h5>
                        <p class="text-muted mb-3">
                            No doors have been added to this quotation version yet.
                            Each door type you add will appear here as its own Excel file.
                        </p>
                        <a href="{{ url('quotation/generate/' . $quotation->id . '/' . $versionId) }}"
                           class="btn btn-primary">Add a Door</a>
                    </div>
                @else
                    <table style="width: 100%;" class="table table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 60px;">#</th>
                                <th>Door Type</th>
                                <th style="width: 110px;">Doors</th>
                                <th>File</th>
                                <th style="width: 280px;">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($doorTypes as $index => $doorType)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $doorType['name'] }}</td>
                                <td>{{ $doorType['qty'] }}</td>
                                <td>
                                    <i class="fa fa-file-excel-o text-success"></i>
                                    {{ $doorType['file'] }}
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-info"
                                       href="{{ url('quotation/documents/' . $quotation->id . '/' . $versionId . '/view') }}?itemId={{ $doorType['itemId'] }}">
                                        View
                                    </a>
                                    <a class="btn btn-sm btn-success"
                                       href="{{ url('quotation/documents/' . $quotation->id . '/' . $versionId . '/download') }}?itemId={{ $doorType['itemId'] }}">
                                        Download
                                    </a>
                                    <a class="btn btn-sm btn-outline-info"
                                       href="{{ url('quotation/documents/' . $quotation->id . '/' . $versionId . '/history') }}?itemId={{ $doorType['itemId'] }}">
                                        History
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>

                    <p class="text-muted mb-0">
                        Files are built from the current quotation data every time you open or
                        download them, so they always match what is saved against the door.
                    </p>
                @endif

            </div>
        </div>

    </div>
</div>
@endsection
