<?php

namespace App\Exports;
use App\Models\Quotation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class AllQuotationExport implements FromCollection,WithHeadings,WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */

    private $rowNumber = 1;

    public function collection()
    {
        $UserId =  myCreatedUser();
        $Quotations = Quotation::leftJoin("quotation_versions", function ($join) {
            $join->on("quotation.id", "quotation_versions.quotation_id")
                ->orOn("quotation_versions.id", "=", "quotation.VersionId");
            })
            ->leftJoin("project", "project.id", "quotation.ProjectId")
            ->leftJoin('companies', 'companies.id', 'quotation.CompanyId')
            ->leftJoin('customers', 'customers.id', 'quotation.MainContractorId')
            ->leftJoin('users', 'users.id', 'quotation.MainContractorId')
            ->select('quotation.*', 'quotation.editBy as QuotEditBy', 'quotation.updated_at as QuotUpdatedAt', 'quotation.id as QuotationId', 'quotation_versions.version', 'companies.CompanyName', 'project.*', 'quotation_versions.id as QVID', 'customers.CstCompanyName', 'quotation.MainContractorId as MainId', 'users.FirstName', 'quotation.VersionId as verId')
            ->where('quotation.QuotationGenerationId', '!=', null);
        $Quotations = $Quotations->wherein('quotation.UserId', $UserId);
        $Quotations = $Quotations->orderBy("quotation.id", "DESC")->get();
        return $Quotations;
    }

    public function headings(): array
    {
        $listHeading = [
            'S N',
            'Quotation Id',
            'Quotation Company Name',
            'Quotation Name',
            'Project',
            'Due Date',
            'Follow-up Date',
            'Number of Door Sets',
            'P.O. Number',
            'Quotation Status',
        ];

        return $listHeading;
    }

    public function map($Quotations): array
    {

        return [
           $this->rowNumber++,
           $Quotations->QuotationGenerationId,
           $Quotations->FirstName,
           $Quotations->QuotationName,
           $Quotations->ProjectName,
           date2Formate($Quotations->ExpiryDate),
           date2Formate($Quotations->FollowUpDate),
           NumberOfDoorSets($Quotations->QVID, $Quotations->QuotationId),
           $Quotations->PONumber,
           $Quotations->QuotationStatus,
        ];
    }

}
