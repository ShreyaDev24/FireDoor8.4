<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PDF;
use PdfMerger;
use Illuminate\Support\Facades\Auth;
use App\Models\{User,QuotationVersion,Item,Company,Quotation,Project,Users,Customer};
use DB;

class pdf3andpdf4_2 implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;
    public  $quatationId;

    public  $versionID;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($quatationId, $versionID)
    {
        $this->quatationId = $quatationId;
        $this->versionID = $versionID;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(): void
    {
        ini_set('memory_limit', '2048M');
        ini_set('max_execution_time', '0');
        $quatationId = $this->quatationId;
        $versionID = $this->versionID;
        if(Auth::user()->UserType == 3){
            $users = User::where('UserType',3)->where('id',Auth::user()->id)->first();
            $id = $users->CreatedBy;
        }else{
            $id = Auth::user()->id;
        }

        // Details Door List PDF
        $qv = QuotationVersion::where('id', $versionID)->first();
        $version = $qv->version;
        $comapnyDetail = Company::where('UserId', $id)->first();
        $quotaion = Quotation::where('id', $quatationId)->first();
        $project = empty($quotaion->ProjectId) ? '' : Project::where('id', $quotaion->ProjectId)->first();
        
        if (!empty($quotaion->MainContractorId)) {
            $customerContact = Users::where('id', $quotaion->MainContractorId)->first();
        } else {
            $customerContact = '';
        }
        
        $customer = '';
        $CstCompanyAddressLine1 = '';
        if (!empty($customerContact)) {
            $customer = Customer::where(['UserId' => $quotaion->MainContractorId])->first();
            $CstCompanyAddressLine1 = $customer->CstCompanyAddressLine1;
        }
        
        $a2 = '';
        $shows = Item::join('quotation_version_items', 'items.itemId', 'quotation_version_items.itemID')
            ->join('item_master', 'quotation_version_items.itemmasterID', 'item_master.id')
            ->where('quotation_version_items.version_id', $versionID)->get();
        $i = 1;

        $DoorDescription = '';
        foreach ($shows as $show) {
            if (!empty($show->DoorsetType)) {
                $DoorDescription = DoorDescription($show->DoorsetType);
            }
            
            $a2 .=
                '<tr>
            <td>' . $show->doorNumber . '</td>
            <td>' . $DoorDescription . '</td>
            <td>' . $show->DoorType . '</td>
            <td>' . round((($show->AdjustPrice)?floatval($show->AdjustPrice) :floatval($show->DoorsetPrice)), 2) . '</td>
             <td>' . round($show->IronmongaryPrice, 2) . '</td>
            <td>' . round((($show->AdjustPrice)?floatval($show->AdjustPrice) + floatval($show->IronmongaryPrice):floatval($show->DoorsetPrice) + floatval($show->IronmongaryPrice)), 2) . '</td>
            </tr>';
            $i++;
        }
        
        $pdf3 = PDF::loadView('Company.pdf_files.detaildoorlist', ['a2' => $a2, 'comapnyDetail' => $comapnyDetail, 'quotaion' => $quotaion, 'project' => $project, 'version' => $version]);
        // return $pdf3->download('file3.pdf');
        $path3 = public_path() . '/allpdfFile';
        $fileName3 = $id . '3' . '.' . 'pdf';
        $pdf3->save($path3 . '/' . $fileName3);


        //Non Configurable Item
        $nonConfigData = nonConfigurableItem($quatationId,$versionID,CompanyUsers());

        $pdf4_2 = PDF::loadView('Company.pdf_files.nonconfigdoor', ['nonConfigData' => $nonConfigData, 'comapnyDetail' => $comapnyDetail, 'quotaion' => $quotaion, 'project' => $project, 'customerContact' => $customerContact, 'version' => $version, 'customer' => $customer]);
        // return $pdf4->download('file4.pdf');
        $path4_2 = public_path() . '/allpdfFile';
        $fileName4_2 = $id . '4_2' . '.' . 'pdf';
        $pdf4_2->save($path4_2 . '/' . $fileName4_2);
    }
}
