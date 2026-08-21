@extends("layouts.Master")

@section("main_section")
<div class="app-main__outer">
    <div class="app-main__inner">

        <div class="app-page-title">
            <div class="page-title-wrapper">
                <div class="page-title-heading">
                    <div class="page-title-icon">
                        <i class="pe-7s-file icon-gradient bg-tempting-azure"></i>
                    </div>
                    <div>
                        {{ $doorType }}
                        <div class="page-title-subheading">
                            {{ $folderName }} &nbsp;/&nbsp; {{ $fileName }}
                            &nbsp;&mdash;&nbsp; {{ $qty }} door{{ $qty == 1 ? '' : 's' }}
                        </div>
                    </div>
                </div>
                <div class="page-title-actions">
                    <a href="{{ url('quotation/documents/' . $quotation->id . '/' . $versionId) }}"
                       class="btn-shadow btn btn-secondary mr-2">Back to Folder</a>
                    <a href="{{ url('quotation/documents/' . $quotation->id . '/' . $versionId . '/download') }}?itemId={{ $itemId }}"
                       class="btn-shadow btn btn-success">Download Excel</a>
                </div>
            </div>
        </div>

        @foreach($sections as $section)
            @continue(empty($section['data']) && $loop->index > 0)
            <div class="main-card mb-3 card">
                <div class="card-header">{{ $section['title'] }}</div>
                <div class="card-body table-responsive">
                    <table style="width: 100%;" class="table table-sm table-hover table-striped table-bordered">
                        <thead>
                            <tr>
                                @foreach($section['headings'] as $heading)
                                    <th>{{ $heading }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($section['data'] as $row)
                                <tr>
                                    @foreach($row as $cell)
                                        <td>{{ $cell }}</td>
                                    @endforeach
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ max(count($section['headings']), 1) }}" class="text-muted">
                                        Nothing recorded for this section.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        @endforeach

    </div>
</div>
@endsection
