@forelse($projects as $project)
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
        @php $actionLink = '<a href="javascript:void(0);" class="deactivateproject"><i class="fa fa-lock"></i> Deactivate Project</a>'; @endphp
    @else
        @php $projectLink = '<a href="#" class="QuotationCode" style="color: black;">' . $project->ProjectName . '</a>'; @endphp
        @php $actionLink = '<a href="javascript:void(0);" class="activateproject"><i class="fa fa-unlock-alt"></i> Activate Project</a>'; @endphp
    @endif

    <div class="col-sm-3 mb-3">
        <div class="QuotationBox">
            {!! $projectLink !!}
            <div class="QuotationCompanyName">
                <b>{{ $companyName }}</b>
            </div>

            <div class="QuotationListData">
                <b>Building Type</b>
                <span>{{ ucwords($buildingType) }}</span>
                <b>Project Name</b>
                <span>{{ ucwords($project->ProjectName) }}</span>
                <b>Files</b>
                <span>{{ $projectFilesCount }}</span>
                <b>Quotes</b>
                <span>{{ $project->quotesCount ?? 0 }}</span>
                <b>Orders</b>
                <span>{{ $project->ordersCount ?? 0 }}</span>
                <b>Ironmongery Set</b>
                <span>{{ $countIronmongerySet }}</span>
                <b>Return Tender Date</b>
                <span>{{ date2Formate($returnTenderDate) }}</span>
            </div>
            <div class="QuotationListNumber"></div>
            <div class="QuotationModifiedDate">
                <p>Last modified by {{ $lastModifier }} on {{ dateFormate($project->Projectupdated_at) }}</p>
            </div>
            <div class="filter_action">
                <label for="filter" class="quote_filter">
                    <i class="fas fa-ellipsis-h"></i>
                </label>
                <ul class="QuotationMenu">
                    <li><a href="javascript:void(0);" class="delproject"><i class="fa fa-trash"></i> Delete Project</a>
                        <input type="hidden" value="{{ $project->ProjectId }}">
                    </li>
                    <li>
                        {!! $actionLink !!}
                        <input type="hidden" value="{{ $project->ProjectId }}">
                    </li>
                </ul>
            </div>
        </div>
    </div>
@empty
    <div class="col-12 text-center">
        <p>No projects found.</p>
    </div>
@endforelse
