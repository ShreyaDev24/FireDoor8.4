<table id="dataListType" class="table table-hover table-striped table-bordered dataTable no-footer dtr-inline">
    <thead class="text-uppercase table-header-bg text-white">
        <tr>
            <th>S.N</th>
            <th>Project Name</th>
            <th>Quotation Company Name</th>
            <th>Building Type</th>
            <th>Files</th>
            <th>Quotes</th>
            <th>Orders</th>
            <th>Ironmongery Set</th>
            <th>Return Tender Date</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        @forelse($projects as $sn => $project)
            @php
                $companyName = $relatedData['customers'][$project->MainContractorId]->CstCompanyName ?? '-----------';
                $buildingType = $project->BuildingType ?: '-----------';
                $editedByUser = $relatedData['users'][$project->editBy] ?? null;
                $lastModifier = $editedByUser ? $editedByUser->FirstName . ' ' . $editedByUser->LastName : '';
                $returnTenderDate = $project->returnTenderDate ?: '-----------';
                $projectFilesCount = $relatedData['filesCount'][$project->ProjectId] ?? 0;
                $countIronmongerySet = $relatedData['ironmongeryCount'][$project->ProjectId] ?? 0;
            @endphp

            @if($project->Status == 1)
                @php $projectLink = '<a href="' . url('project/quotation-list/' . $project->GeneratedKey) . '" class="QuotationCode">' . $project->ProjectName . '</a>'; @endphp
                @php $actionLink = '<a href="javascript:void(0);" class="dropdown-item deactivateproject"><i class="fa fa-lock" style="margin-right: 8px;"></i> Deactivate Project</a>'; @endphp
            @else
                @php $projectLink = '<a href="#" class="QuotationCode" style="color: black;">' . $project->ProjectName . '</a>'; @endphp
                @php $actionLink = '<a href="javascript:void(0);" class="dropdown-item activateproject"><i class="fa fa-unlock-alt" style="margin-right: 8px;"></i> Activate Project</a>'; @endphp
            @endif

            <tr>
                <td>{{ $sn + 1 }}</td>
                <td>{!! $projectLink !!}</td>
                <td>{{ $companyName }}</td>
                <td>{{ ucwords($buildingType) }}</td>
                <td>{{ $projectFilesCount }}</td>
                <td>{{ $project->quotesCount ?? 0 }}</td>
                <td>{{ $project->ordersCount ?? 0 }}</td>
                <td>{{ $countIronmongerySet }}</td>
                <td>{{ date2Formate($returnTenderDate) }}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            ....
                        </button>
                        <div class="dropdown-menu dropdown-list" aria-labelledby="dropdownMenuButton">
                            <a class="dropdown-item delproject" href="javascript:void(0);"><i class="fa fa-trash" style="margin-right: 8px;"></i> Delete Project</a>
                            <input type="hidden" value="{{ $project->ProjectId }}">
                            {!! $actionLink !!}
                            <input type="hidden" value="{{ $project->ProjectId }}">
                        </div>
                    </div>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="10" class="text-center">No projects found</td>
            </tr>
        @endforelse
    </tbody>
</table>
