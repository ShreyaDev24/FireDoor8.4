<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PDF;
use Illuminate\Support\Facades\Auth;
use App\Models\{User,SettingPDF2,BOMSetting,SideScreenItem,QuotationShipToInformation,Item,BOMDetails,Company,Project,Quotation,SettingPDFfooter,Users,Customer};
use DB;

class pdf2 implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(public $quatationId, public $versionID)
    {
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

         // Quotation Sumary PDF
         $pdf2 = SettingPDF2::where('UserId', $id)->first();
         $totDoorsetType = NumberOfDoorSets($versionID,$quatationId);
         $quotaion = Quotation::where('id', $quatationId)->first();
         $pdf_footer = SettingPDFfooter::where('UserId', $id)->first();
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

         // for getting margin
         $userIds = CompanyUsers();
         $margin = BOMSetting::wherein('UserId',$userIds)->value('margin_for_material');

         $totgrand_total = BOMDetails::where(['quotationId' => $quatationId, 'version' => $versionID])->sum('grand_total');
         $totlabour_total = BOMDetails::where(['quotationId' => $quatationId, 'version' => $versionID])->sum('labour_total');

         $DoorsetPrice = Item::join('quotation_version_items','items.itemId','quotation_version_items.itemID')
                 ->join('item_master','quotation_version_items.itemmasterID','item_master.id')
                 ->where(['quotation_version_items.version_id'=>$versionID,'items.VersionId'=>$versionID,'items.QuotationId' => $quatationId]);

         $totDoorsetPrice = itemAdjustCount($quatationId,$versionID);
         $totIronmongaryPrice = $DoorsetPrice->sum('items.IronmongaryPrice');

         //end changes
         $nonConfigDataPrice = nonConfigurableItem($quatationId,$versionID,CompanyUsers(),'',true);
         $nonConfigDataCount = nonConfigurableItem($quatationId,$versionID,CompanyUsers(),'','','count');

         $totIronmongerySet = $DoorsetPrice->whereNotNull('items.IronmongeryID')->count();
         $GetIronmongerySet = $DoorsetPrice->whereNotNull('items.IronmongeryID')->groupby('items.itemId')->get();


         $SideScreenData = SideScreenItem::join('side_screen_item_master', 'side_screen_items.id', 'side_screen_item_master.ScreenId')->where(['side_screen_items.QuotationId' => $quatationId,'side_screen_items.VersionId' => $versionID])
                     ->select('side_screen_items.FireRating','side_screen_items.VersionId', 'side_screen_items.ScreenType' ,'side_screen_items.SOWidth', 'side_screen_items.SOHeight', 'side_screen_items.SODepth','side_screen_items.GlazingType', 'side_screen_items.ScreenPrice', 'side_screen_items.id', 'side_screen_item_master.screenNumber', 'side_screen_item_master.floor', 'side_screen_item_master.id as screenMasterid');

         $screenData = $SideScreenData->sum('side_screen_items.ScreenPrice');
         $ScreenSetQty = $SideScreenData->count();
         $screenDataprice = round(floatval($screenData),2);
         $nettot = itemAdjustCount($quatationId,$versionID) + $totIronmongaryPrice + $nonConfigDataPrice + $screenDataprice;
         $QSTI = QuotationShipToInformation::where('QuotationId', $quatationId)->first();
         $comapnyDetail = Company::where('UserId', $id)->first();
         $project = empty($quotaion->ProjectId) ? '' : Project::where('id', $quotaion->ProjectId)->first();

         $user = empty($quotaion->UserId) ? '' : User::where('id', $quotaion->CompanyUserId)->first();

        $contractorName = DB::table('users')->where(['id' => $quotaion->MainContractorId, 'UserType' => 5 ])->value('FirstName');
        $contractorName = $contractorName ?: '';


         $pdf2 = PDF::loadView('Company.pdf_files.quotationsummarypdf', ['comapnyDetail' => $comapnyDetail, 'project' => $project, 'quotaion' => $quotaion, 'pdf2' => $pdf2, 'pdf_footer' => $pdf_footer, 'totDoorsetType' => $totDoorsetType, 'totIronmongerySet' => $totIronmongerySet, 'totDoorsetPrice' => $totDoorsetPrice, 'totIronmongaryPrice' => $totIronmongaryPrice, 'nonConfigDataPrice' => $nonConfigDataPrice, 'nettot' => $nettot, 'QSTI' => $QSTI, 'customerContact' => $customerContact, 'customer' => $customer, 'user' => $user, 'nonConfigDataCount' => $nonConfigDataCount, 'contractorName' => $contractorName, 'ScreenSetQty' => $ScreenSetQty, 'screenDataprice' => $screenDataprice]);

         // return $pdf2->download('file2.pdf');
         $path2 = public_path() . '/allpdfFile';
         $fileName2 = $id . '2' . '.' . 'pdf';
         $pdf2->save($path2 . '/' . $fileName2);


    }
}
