<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use App\Models\Quotation;
use App\Models\Item;
use Illuminate\Support\Facades\Auth;

class AllOrderExport implements FromCollection,WithHeadings,WithMapping
{
    private int $rowNumber = 1;
    
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $loginUserId = Auth::user()->id;
        $Quotations = Quotation::join("quotation_versions",function($join): void{
            $join->on("quotation_versions.id","=","quotation.VersionId")
                ->on("quotation.id","quotation_versions.quotation_id");
        })
        ->leftJoin("project","project.id","quotation.ProjectId")
        ->leftJoin('companies','companies.id','quotation.CompanyId')
        ->leftJoin('customers','customers.id','quotation.CustomerId')
        ->select('quotation.*', 'quotation.id as QuotationId','quotation_versions.version', 'companies.CompanyName', 'project.*','quotation_versions.id as QVID','customers.CstCompanyName')
        ->where('quotation.QuotationStatus','=','Ordered')
        ->where('quotation.UserId', "=", $loginUserId);
        $Quotations = $Quotations->orderBy("quotation.id", "DESC")->get();
        return $Quotations;
    }

    public function headings(): array
    {
        $listHeading = [
            'S.N',
            'Quotation Id',
            'Quotation Company Name',
            'Quotation Name',
            'Price',
            'Project',
            'Number of Door Sets',
            'P.O. Number',
            'Status',
        ];

        return $listHeading;
    }

    public function map($Quotations): array
    {
        $DoorsetPrice = 0;
            $Item = Item::join('item_master','item_master.itemID','=','items.itemId')->join('quotation','quotation.id','=','items.QuotationId')->
            join("quotation_version_items",function($join): void{
                $join->on("quotation_version_items.itemID","=","items.itemId")
                    ->on("quotation_version_items.itemmasterID","=","item_master.id");
            })->where('quotation.QuotationGenerationId',$Quotations->QuotationGenerationId)->where('quotation_version_items.version_id',$Quotations->QVID)->where('items.VersionId',$Quotations->QVID)->get();
            if(!empty($Item)){
                foreach($Item as $value){
                    $DoorsetPrice += (($value->AdjustPrice)?floatval($value->AdjustPrice) :floatval($value->DoorsetPrice)) + $value->IronmongaryPrice;
                }
            }
            
        $discountPrice = ($DoorsetPrice + nonConfigurableItem($Quotations->QuotationId,$Quotations->QVID,CompanyUsers(),'',true)) * $Quotations->QuoteSummaryDiscount/100;
        $DoorsetPrice = ($DoorsetPrice + nonConfigurableItem($Quotations->QuotationId,$Quotations->QVID,CompanyUsers(),'',true)) - $discountPrice;
            if(!empty($Quotations->projectCurrency)){
                if ($Quotations->projectCurrency == '£_GBP') {
                    $Currency = "£";
                } elseif ($Quotations->projectCurrency == '€_EURO') {
                    $Currency = "€";
                } elseif ($Quotations->projectCurrency == '$_US_DOLLAR') {
                    $Currency = "$";
                }
            }else{
                $Currency = "£";
            }

        return [
           $this->rowNumber++,
           $Quotations->OrderNumber,
           $Quotations->CstCompanyName,
           $Quotations->QuotationName,
           $Currency.floatval($DoorsetPrice),
           $Quotations->ProjectName,
           date2Formate($Quotations->ExpiryDate),
           NumberOfDoorSets($Quotations->QVID, $Quotations->QuotationId),
           $Quotations->PONumber,
           $Quotations->QuotationStatus,
        ];
    }

}
