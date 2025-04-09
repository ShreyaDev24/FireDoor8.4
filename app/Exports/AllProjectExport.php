<?php

namespace App\Exports;
use App\Models\Customer;
use App\Models\Project;
use App\Models\ProjectFiles;
use App\Models\AddIronmongery;
use DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AllProjectExport implements FromCollection,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    private int $rowNumber = 1;

    public function collection()
    {
            $created_by_me_users = myCreatedUser();
            $projectList = Project::leftJoin('companies','companies.id','project.CompanyId')
            ->select('project.*', 'project.updated_at as Projectupdated_at','project.id as ProjectId', 'companies.*', DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId) quotesCount"), DB::raw("(SELECT count(*) from quotation  WHERE project.id = quotation.ProjectId AND quotation.IsOrdered = 1) ordersCount"))
            ->whereIn('project.UserId', $created_by_me_users);
            $projectList = $projectList->orderBy("project.id", "DESC")->get();
            return $projectList;
    }

    public function headings(): array
    {
        $listHeading = [
            'S.N',
            'Project Name',
            'Quotation Company Name',
            'Building Type',
            'Files',
            'Quotes',
            'Orders',
            'Ironmongery Set',
            'Return Tender Date',
            'Project Status'
        ];

        return $listHeading;
    }

    public function map($projectList): array
    {
        $custCompanyName = Customer::where('id',$projectList->MainContractorId)->first();
        $projectFilesCount = ProjectFiles::where('projectId',$projectList->ProjectId)->count();
        $countIronmongerySet = AddIronmongery::where(['ProjectId' => $projectList->ProjectId])->count();
        $status = $projectList->Status == 1 ? "Activate Project" : "Deactivate";
        
        return [
           $this->rowNumber++,
           $projectList->ProjectName,
           $custCompanyName->CstCompanyName ?? '',
           ucwords((string) $projectList->BuildingType),
           $projectFilesCount,
           $projectList->quotesCount,
           $projectList->ordersCount,
           $countIronmongerySet,
           date2Formate($projectList->returnTenderDate),
           $status
        ];
    }
}
