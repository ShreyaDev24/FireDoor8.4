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
use App\Models\{User,Quotation,Item,BOMDetails,AddIronmongery,LippingSpecies,Option,SettingIntumescentSeals2,BOMSetting,Company,Project,Users,QuotationVersion,Customer};
use DB;


class pdf4 implements ShouldQueue
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
        
        $quotaion = Quotation::where('id', $quatationId)->first();
        $comapnyDetail = Company::where('UserId', $id)->first();
        $contractorName = DB::table('users')->where(['id' => $quotaion->MainContractorId, 'UserType' => 5 ])->value('FirstName');
        $contractorName = $contractorName ?: '';

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
        
        $qv = QuotationVersion::where('id', $versionID)->first();
        $version = $qv->version;
         // for getting margin
         $userIds = CompanyUsers();
         $margin = BOMSetting::wherein('UserId',$userIds)->value('margin_for_material');
         //PDF 2
         $CompanyId = get_company_id($id)->id;
         $a = '';
         $i = 1;
         $DoorQuantity = 0;
         $DoorsetPrice = 0;

         $SumDoorsetPrice = 0;
         $SumIronmongaryPrice = 0;
         $configurationItem = $quotaion->configurableitems;
         if (!empty($quotaion->configurableitems)) {
             $configurationItem = $quotaion->configurableitems;
         }
         
         $shows = Item::join('quotation_version_items', 'items.itemId', 'quotation_version_items.itemID')
            ->join('item_master', 'quotation_version_items.itemmasterID', 'item_master.id')
            ->where('quotation_version_items.version_id', $versionID)->get();

         foreach ($shows as $show) {

             $fireRate = $show->FireRating;
             if($show->FireRating == 'FD30' || $show->FireRating == 'FD30s'){
                 $show->FireRating = 'FD30';
             }elseif($show->FireRating == 'FD60' || $show->FireRating == 'FD60s'){
                 $show->FireRating = 'FD60';
             }
             
             $grand_total = BOMDetails::where('itemId', $show->itemId)->sum('grand_total');
             $labour_total = BOMDetails::where('itemId', $show->itemId)->sum('labour_total');
             $DoorsetPrice = (($show->AdjustPrice)?floatval($show->AdjustPrice) :floatval($show->DoorsetPrice));
             $IronmongaryPrice = 0;
             if (!empty($show->IronmongeryID)) {
                 $AI = AddIronmongery::select('discountprice')->where('id', $show->IronmongeryID)->first();
                 if(!empty($AI->discountprice)){
                     $marginwithcal = 100 - $margin;
                     $testvar = $marginwithcal/100;
                     $totalcost = $AI->discountprice / $testvar;
                     $IronmongaryPrice = $totalcost;
                 }
             }
             
             // dd( $IronmongaryPrice);
             $totalpriceperdoorset = $DoorsetPrice + $IronmongaryPrice;


             $SumDoorsetPrice += $DoorsetPrice;
             $SumIronmongaryPrice += $IronmongaryPrice;

             $DoorLeafFinish = "N/A";
             if (!empty($show->DoorLeafFinish)) {
                 $dlf = DoorLeafFinish($configurationItem, $show->DoorLeafFinish);
                 $DoorLeafFinish = empty($show->SheenLevel) ? $dlf : $dlf . ' - ' . $show->SheenLevel . ' Sheen';
             }
             
             $DoorLeafFinishColor = '';
             if (!empty($show->DoorLeafFinishColor)) {
                 $DoorLeafFinishColor = ' + ' . $show->DoorLeafFinishColor;
             }

             $DoorLeafFacing = "N/A";

             if (!empty($show->DoorLeafFacing)) {
                 $DoorLeafFacing = DoorLeafFacing($configurationItem, $show->DoorLeafFacing, $show->DoorLeafFacingValue);
             }


             $LippingType = '';
             if (!empty($show->LippingType)) {
                 $SelectedLippingType = Option::where("configurableitems", $configurationItem)
                     ->where("OptionSlug", "lipping_type")
                     ->where("OptionKey", $show->LippingType)->first();
                 if ($SelectedLippingType != null) {
                     $LippingType = $SelectedLippingType->OptionValue;
                 }
             }
             
             $LippingSpecies = '';
             if (!empty($show->LippingSpecies)) {
                 $SelectedLippingSpecies = LippingSpecies::find($show->LippingSpecies);
                 if ($SelectedLippingSpecies != null) {
                     $LippingSpecies = $SelectedLippingSpecies->SpeciesName;
                 }
             }
             
             $LippingThickness = '';
             if (!empty($show->LippingThickness)) {
                 $LippingThickness = $show->LippingThickness;
             }
             
             if (!empty($LippingType) && !empty($LippingSpecies) && !empty($LippingThickness)) {
                 $Lipping = $LippingType . ' - ' . $LippingSpecies . ' - ' . $LippingThickness . 'mm';
                 // LY-LS-LT = 1-1-1 //
             } elseif (empty($LippingType) && !empty($LippingSpecies) && !empty($LippingThickness)) {
                 $Lipping = 'N/A - ' . $LippingSpecies . ' - ' . $LippingThickness . 'mm';
                 // N/A-LS-LT = 0-1-1 //
             } elseif (!empty($LippingType) && empty($LippingSpecies) && !empty($LippingThickness)) {
                 $Lipping = $LippingType . ' - N/A - ' . $LippingThickness . 'mm';
                 // LY-N/A-LT = 1-0-1 //
             } elseif (empty($LippingType) && empty($LippingSpecies) && !empty($LippingThickness)) {
                 $Lipping = 'N/A - N/A - ' . $LippingThickness . 'mm';
                 // N/A-N/A-LT = 0-0-1 //
             } elseif (!empty($LippingType) && !empty($LippingSpecies) && empty($LippingThickness)) {
                 $Lipping = $LippingType . ' - ' . $LippingSpecies . ' - N/A';
                 // LY-LS-LT = 1-1-0 //
             } elseif (!empty($LippingType) && empty($LippingSpecies) && empty($LippingThickness)) {
                 $Lipping = $LippingType . ' - N/A - N/A';
                 // LY-N/A-N/A = 1-0-0 //
             } elseif (empty($LippingType) && !empty($LippingSpecies) && empty($LippingThickness)) {
                 $Lipping = 'N/A - ' . $LippingSpecies . ' - N/A';
                 // N/A-LS-N/A = 0-1-0 //
             } elseif (empty($LippingType) && empty($LippingSpecies) && empty($LippingThickness)) {
                 $Lipping = 'N/A';
                 // N/A = 0-0-0 //
             }

             $Leaf1VisionPanel = "N/A";
             $Leaf2VisionPanel = "N/A";
             if ($show->Leaf1VisionPanel == "Yes") {
                 if ($show->VisionPanelQuantity == '1') {
                     $Leaf1VisionPanel = $show->Leaf1VisionPanelShape . " (" . $show->VisionPanelQuantity . ") </br> " . $show->Leaf1VPWidth . "x" . $show->Leaf1VPHeight1 . " (1) ";
                 } elseif ($show->VisionPanelQuantity == '2') {
                     $Leaf1VisionPanel = $show->Leaf1VisionPanelShape . " (" . $show->VisionPanelQuantity . ") " . $show->Leaf1VPWidth . "x" . $show->Leaf1VPHeight1 . " (1) </br> " . $show->Leaf1VPWidth . "x" . $show->Leaf1VPHeight2 . " (2) ";
                 } elseif ($show->VisionPanelQuantity == '3') {
                     $Leaf1VisionPanel = $show->Leaf1VisionPanelShape . " (" . $show->VisionPanelQuantity . ") " . $show->Leaf1VPWidth . "x" . $show->Leaf1VPHeight1 . " (1) </br> " . $show->Leaf1VPWidth . "x" . $show->Leaf1VPHeight2 . " (2) " . $show->Leaf1VPWidth . "x" . $show->Leaf1VPHeight3 . " (3) ";
                 } elseif ($show->VisionPanelQuantity == '4') {
                     $Leaf1VisionPanel = $show->Leaf1VisionPanelShape . " (" . $show->VisionPanelQuantity . ") " . $show->Leaf1VPWidth . "x" . $show->Leaf1VPHeight1 . " (1) </br> " . $show->Leaf1VPWidth . "x" . $show->Leaf1VPHeight2 . " (2) " . $show->Leaf1VPWidth . "x" . $show->Leaf1VPHeight3 . " (3) " . $show->Leaf1VPWidth . "x" . $show->Leaf1VPHeight4 . " (4) ";
                 } elseif ($show->VisionPanelQuantity == '5') {
                     $Leaf1VisionPanel = $show->Leaf1VisionPanelShape . " (" . $show->VisionPanelQuantity . ") " . $show->Leaf1VPWidth . "x" . $show->Leaf1VPHeight1 . " (1) </br> " . $show->Leaf1VPWidth . "x" . $show->Leaf1VPHeight2 . " (2) " . $show->Leaf1VPWidth . "x" . $show->Leaf1VPHeight3 . " (3) " . $show->Leaf1VPWidth . "x" . $show->Leaf1VPHeight4 . " (4) " . $show->Leaf1VPWidth . "x" . $show->Leaf1VPHeight5 . " (5) ";
                 }
             }

             if ($show->Leaf2VisionPanel == "Yes") {
                 if ($show->Leaf2VisionPanelQuantity == '1') {
                     $Leaf2VisionPanel = $show->Leaf1VisionPanelShape . " (" . $show->Leaf2VisionPanelQuantity . ") " . $show->Leaf2VPWidth . "x" . $show->Leaf2VPHeight1 . " (1) ";
                 } elseif ($show->Leaf2VisionPanelQuantity == '2') {
                     $Leaf2VisionPanel = $show->Leaf1VisionPanelShape . " (" . $show->Leaf2VisionPanelQuantity . ") " . $show->Leaf2VPWidth . "x" . $show->Leaf2VPHeight1 . " (1) " . $show->Leaf2VPWidth . "x" . $show->Leaf2VPHeight2 . " (2) ";
                 } elseif ($show->Leaf2VisionPanelQuantity == '3') {
                     $Leaf2VisionPanel = $show->Leaf1VisionPanelShape . " (" . $show->Leaf2VisionPanelQuantity . ") " . $show->Leaf2VPWidth . "x" . $show->Leaf2VPHeight1 . " (1) " . $show->Leaf2VPWidth . "x" . $show->Leaf2VPHeight2 . " (2) " . $show->Leaf2VPWidth . "x" . $show->Leaf2VPHeight3 . " (3) ";
                 } elseif ($show->Leaf2VisionPanelQuantity == '4') {
                     $Leaf2VisionPanel = $show->Leaf1VisionPanelShape . " (" . $show->Leaf2VisionPanelQuantity . ") " . $show->Leaf2VPWidth . "x" . $show->Leaf2VPHeight1 . " (1) " . $show->Leaf2VPWidth . "x" . $show->Leaf2VPHeight2 . " (2) " . $show->Leaf2VPWidth . "x" . $show->Leaf2VPHeight3 . " (3) " . $show->Leaf2VPWidth . "x" . $show->Leaf2VPHeight4 . " (4) ";
                 } elseif ($show->Leaf2VisionPanelQuantity == '5') {
                     $Leaf2VisionPanel = $show->Leaf1VisionPanelShape . " (" . $show->Leaf2VisionPanelQuantity . ") " . $show->Leaf2VPWidth . "x" . $show->Leaf2VPHeight1 . " (1) " . $show->Leaf2VPWidth . "x" . $show->Leaf2VPHeight2 . " (2) " . $show->Leaf2VPWidth . "x" . $show->Leaf2VPHeight3 . " (3) " . $show->Leaf2VPWidth . "x" . $show->Leaf2VPHeight4 . " (4) " . $show->Leaf2VPWidth . "x" . $show->Leaf2VPHeight5 . " (5) ";
                 }
             }

             $GlassTypeForDoorDetailsTable = "N/A";

             if (!empty($show->GlassType)) {
                 $GlassTypeForDoorDetailsTable = GlassTypeThickness($configurationItem, $show->FireRating, $show->GlassType, $show->GlassThickness);
             }

             $OverpanelForDoorDetailsTable = "N/A";

             if ($show->Overpanel == "Fan_Light" || $show->Overpanel == "Overpanel") {
                 $OverpanelForDoorDetailsTable = $show->OPHeigth . "x" . $show->OPWidth;
             }

             $OPGlassTypeForDoorDetailsTable = "N/A";

             if (!empty($show->OPGlassType)) {
                 $OPGlassTypeForDoorDetailsTable = OPGlassType($configurationItem, $show->FireRating, $show->OPGlassType);
             }

             $FrameMaterialForDoorDetailsTable = "N/A";

             if (!empty($show->FrameMaterial) && !in_array($show->FrameMaterial, ["MDF", "Softwood", "Hardwood"])) {
                 $SelectedFrameMaterialForDoorDetailsTable = LippingSpecies::find($show->FrameMaterial);
                 if ($SelectedFrameMaterialForDoorDetailsTable != null) {
                     $FrameMaterialForDoorDetailsTable = $SelectedFrameMaterialForDoorDetailsTable->SpeciesName;
                 } else {
                     $SelectedFrameMaterialForDoorDetailsTable = LippingSpecies::where("SpeciesName", $show->FrameMaterial)->first();
                     if ($SelectedFrameMaterialForDoorDetailsTable != null) {
                         $FrameMaterialForDoorDetailsTable = $SelectedFrameMaterialForDoorDetailsTable->SpeciesName;
                     }
                 }
             }

             $FrameTypeForDoorDetailsTable = 'N/A';

             if (!empty($show->FrameType)) {
                 $FrameTypeForDoorDetailsTable = FrameType($configurationItem, $show->FrameType);
             }

             $FrameSizeForDoorDetailsTable = "";
             if (!empty($show->FrameType) && $show->FrameType == 'Rebated_Frame') {
                 $FrameSizeForDoorDetailsTable .= $show->RebatedWidth . "x" . $show->RebatedHeight . "mm";
             } elseif (!empty($show->FrameType) && $show->FrameType == 'Plant_on_Stop') {
                 $FrameSizeForDoorDetailsTable .= $show->PlantonStopWidth . "x" . $show->PlantonStopHeight . "mm";
             } elseif (!empty($show->FrameType) && $show->FrameType == 'Scalloped') {
                 $FrameSizeForDoorDetailsTable .= $show->ScallopedWidth . "x" . $show->ScallopedHeight . "mm";
             }
             
             // $FrameSizeForDoorDetailsTable .= $show->FrameThickness."mm";

             if (!empty($show->IronmongerySet)) {
                 if ($show->IronmongerySet == 'No') {
                     $IronmongerySet = 'N/A';
                 } elseif (!empty($show->IronmongeryID)) {
                     $IronmongerySet = IronmongerySetName($show->IronmongeryID);
                 } else {
                     $IronmongerySet = 'N/A';
                 }
             } else {
                 $IronmongerySet = 'N/A';
             }
             
             $FrameFinishForDoorDetailsTable = 'N/A';
             if (!empty($show->FrameFinish)) {

                 $FrameFinishForDoorDetailsTable = FrameFinish($configurationItem, $show->FrameFinish, $show->FrameFinishColor);
             }

             $ExtLiner = 'N/A';
             if (!empty($show->ExtLiner)) {
                 $ExtLiner = $show->ExtLiner;
             }


             $ExtLinerValue = '';
             if (!empty($show->ExtLinerValue)) {
                 $ExtLinerValue = $show->ExtLinerValue;
             }
             
             $ExtLinerThickness = '';
             if (!empty($show->ExtLinerThickness)) {
                 $ExtLinerThickness = $show->ExtLinerThickness . 'mm';
             }
             
             if (empty($ExtLinerValue) && ($ExtLinerThickness === '' || $ExtLinerThickness === '0')) {
                 $ExtLinerSizeForDoorDetailsTable = "N/A";
             } elseif (empty($ExtLinerValue) && ($ExtLinerThickness !== '' && $ExtLinerThickness !== '0')) {
                 $ExtLinerSizeForDoorDetailsTable = 'N/A x ' . $ExtLinerThickness;
             } elseif (!empty($ExtLinerValue) && ($ExtLinerThickness === '' || $ExtLinerThickness === '0')) {
                 $ExtLinerSizeForDoorDetailsTable = $ExtLinerValue . ' x N/A';
             } elseif (!empty($ExtLinerValue) && ($ExtLinerThickness !== '' && $ExtLinerThickness !== '0')) {
                 $ExtLinerSizeForDoorDetailsTable = $ExtLinerValue . ' x ' . $ExtLinerThickness;
             }

             $ArchitraveMaterialForDoorDetailsTable = "N/A";
             $ArchitraveTypeForDoorDetailsTable = "N/A";
             $ArchitraveSizeForDoorDetailsTable = "N/A";
             $ArchitraveFinishForDoorDetailsTable = "N/A";

             if ($show->Architrave == "Yes") {
                 $SelectedLippingSpecies = LippingSpecies::where('id', $show->ArchitraveMaterial)->get()->first();
                 $ArchitraveMaterialForDoorDetailsTable = $SelectedLippingSpecies->SpeciesName;
                 $ArchitraveTypeForDoorDetailsTable = $show->ArchitraveType;
                 $ArchitraveSizeForDoorDetailsTable = $show->ArchitraveWidth . "x" . $show->ArchitraveHeight . "mm";

                 if (!empty($show->ArchitraveFinish)) {
                     $ArchitraveFinishForDoorDetailsTable = ArchitraveFinish($configurationItem, $show->ArchitraveFinish, $show->FrameFinishColor);
                 }
             }


             if ($show->DoorsetType == "DD") {
                 $DoorsetType = "Double Doorset";
             } elseif ($show->DoorsetType == "SD") {
                 $DoorsetType = "Single Doorset";
             } else {
                 $DoorsetType = "Leaf and a Half";
             }

             $rWdBRating = 'N/A';
             if (!empty($show->rWdBRating)) {
                 $rWdBRating = $show->rWdBRating;
             }

             $COC = 'None';
             if (!empty($show->COC)) {
                 $COC = $show->COC;
             }

             $SpecialFeatureRefs = 'None';
             if (!empty($show->SpecialFeatureRefs)) {
                 $SpecialFeatureRefs = $show->SpecialFeatureRefs;
             }

             $intumescentSeal = 'N/A';
             $IntumescentLeapingSealArrangement = $show->IntumescentLeapingSealArrangement;
             if (!empty($IntumescentLeapingSealArrangement)) {
                 $intum = SettingIntumescentSeals2::select('brand', 'intumescentSeals')->where('id', $IntumescentLeapingSealArrangement)->first();
                 if($intum){
                     $intumescentSeal = $intum->brand . ' - ' . $intum->intumescentSeals;
                 }
             }

             $DoorDescription = 'N/A';
             if (!empty($show->DoorsetType)) {
                 $DoorDescription = DoorDescription($show->DoorsetType);
             }
             
             $ArchitraveSetQty = 'N/A';
             if (!empty($show->ArchitraveSetQty)) {
                 $ArchitraveSetQty = $show->ArchitraveSetQty;
             }

             // Side Screen 1
             $SL1Width = '';
             if (!empty($show->SL1Width)) {
                 $SL1Width = $show->SL1Width;
             }
             
             $SL1Height = '';
             if (!empty($show->SL1Height)) {
                 $SL1Height = $show->SL1Height;
             }
             
             $SideScreen1 = 'N/A';
             if (!empty($SL1Width) && !empty($SL1Height)) {
                 $SideScreen1 = $SL1Width . ' x ' . $SL1Height;
             } elseif (!empty($SL1Width) && empty($SL1Height)) {
                 $SideScreen1 = $SL1Width . ' x N/A';
             } elseif (empty($SL1Width) && !empty($SL1Height)) {
                 $SideScreen1 = 'N/A x ' . $SL1Height;
             } elseif (empty($SL1Width) && empty($SL1Height)) {
                 $SideScreen1 = 'N/A';
             }

             // Side Screen 2
             $SL2Width = '';
             if (!empty($show->SL2Width)) {
                 $SL2Width = $show->SL2Width;
             }
             
             $SL2Height = '';
             if (!empty($show->SL2Height)) {
                 $SL2Height = $show->SL2Height;
             }
             
             $SideScreen2 = 'N/A';
             if (!empty($SL2Width) && !empty($SL2Height)) {
                 $SideScreen2 = $SL2Width . ' x ' . $SL2Height;
             } elseif (!empty($SL2Width) && empty($SL2Height)) {
                 $SideScreen2 = $SL2Width . ' x N/A';
             } elseif (empty($SL2Width) && !empty($SL2Height)) {
                 $SideScreen2 = 'N/A x ' . $SL2Height;
             } elseif (empty($SL2Width) && empty($SL2Height)) {
                 $SideScreen2 = 'N/A';
             }
             
             if($quotaion->configurableitems == 4){
                 $a .= '<tr>
                             <td>' . $i . '</td>
                             <td>' . $show->floor . '</td>
                             <td>' . $show->doorNumber . '</td>
                             <td>' . $DoorDescription . '</td>
                             <td>' . $show->DoorQuantity . '</td>
                             <td>' . $show->SOHeight . '</td>
                             <td>' . $show->SOWidth . '</td>
                             <td>' . $show->SOWallThick . '</td>
                             <td>' . $show->DoorType . '</td>
                             <td>' . $show->LeafConstruction . '</td>
                             <td>' . $DoorLeafFacing . '</td>
                             <td>' . $show->DoorDimensionsCode . '</td>
                             <td>' . $Lipping . '</td>
                             <td>' . $show->LeafWidth1 . '</td>
                             <td>' . $show->LeafWidth2 . '</td>
                             <td>' . $show->LeafHeight . '</td>
                             <td>' . $show->LeafThickness . '</td>
                             <td>' . $show->Undercut . '</td>
                             <td>' . $show->Handing . '</td>
                             <td>' . $show->OpensInwards . '</td>


                             <td>' . $Leaf1VisionPanel . '</td>
                             <td>' . $Leaf2VisionPanel . '</td>
                             <td>' . $GlassTypeForDoorDetailsTable . '</td>

                             <td>' . $OverpanelForDoorDetailsTable . '</td>
                             <td>' . $OPGlassTypeForDoorDetailsTable . '</td>
                             <td>' . $SideScreen1 . '</td>
                             <td>' . $SideScreen2 . '</td>


                             <td>' . $FrameMaterialForDoorDetailsTable . '</td>
                             <td>' . $FrameTypeForDoorDetailsTable . '</td>
                             <td>' . $FrameSizeForDoorDetailsTable . '</td>
                             <td>' . $FrameFinishForDoorDetailsTable . '</td>
                             <td>' . $ExtLiner . '</td>
                             <td>' . $ExtLinerSizeForDoorDetailsTable . '</td>

                             <td>' . $intumescentSeal . '</td>

                             <td>' . $ArchitraveMaterialForDoorDetailsTable . '</td>
                             <td>' . $ArchitraveTypeForDoorDetailsTable . '</td>
                             <td>' . $ArchitraveSizeForDoorDetailsTable . '</td>
                             <td>' . $ArchitraveFinishForDoorDetailsTable . '</td>
                             <td>' . $ArchitraveSetQty . '</td>

                             <td>' . $IronmongerySet . '</td>
                             <td>' . $rWdBRating . '</td>
                             <td>' . $fireRate . '</td>
                             <td>' . $SpecialFeatureRefs . '</td>
                             <td class="tbl_last">' . round($DoorsetPrice, 2) . '</td>
                             <td class="tbl_last">' . round($IronmongaryPrice, 2) . '</td>
                             <td class="tbl_last">' . round($totalpriceperdoorset, 2) . '</td>
                             </tr>
                             ';
             }else{

                 $a .= '<tr>
                             <td>' . $i . '</td>
                             <td>' . $show->floor . '</td>
                             <td>' . $show->doorNumber . '</td>
                             <td>' . $DoorDescription . '</td>
                             <td>' . $show->DoorQuantity . '</td>
                             <td>' . $show->SOHeight . '</td>
                             <td>' . $show->SOWidth . '</td>
                             <td>' . $show->SOWallThick . '</td>
                             <td>' . $show->DoorType . '</td>
                             <td>' . $DoorLeafFinish . $DoorLeafFinishColor . '</td>
                             <td>' . $DoorLeafFacing . '</td>
                             <td>' . $Lipping . '</td>
                             <td>' . $show->LeafWidth1 . '</td>
                             <td>' . $show->LeafWidth2 . '</td>
                             <td>' . $show->LeafHeight . '</td>
                             <td>' . $show->LeafThickness . '</td>
                             <td>' . $show->Undercut . '</td>
                             <td>' . $show->Handing . '</td>
                             <td>' . $show->OpensInwards . '</td>


                             <td>' . $Leaf1VisionPanel . '</td>
                             <td>' . $Leaf2VisionPanel . '</td>
                             <td>' . $GlassTypeForDoorDetailsTable . '</td>

                             <td>' . $OverpanelForDoorDetailsTable . '</td>
                             <td>' . $OPGlassTypeForDoorDetailsTable . '</td>
                             <td>' . $SideScreen1 . '</td>
                             <td>' . $SideScreen2 . '</td>


                             <td>' . $FrameMaterialForDoorDetailsTable . '</td>
                             <td>' . $FrameTypeForDoorDetailsTable . '</td>
                             <td>' . $FrameSizeForDoorDetailsTable . '</td>
                             <td>' . $FrameFinishForDoorDetailsTable . '</td>
                             <td>' . $ExtLiner . '</td>
                             <td>' . $ExtLinerSizeForDoorDetailsTable . '</td>

                             <td>' . $intumescentSeal . '</td>

                             <td>' . $ArchitraveMaterialForDoorDetailsTable . '</td>
                             <td>' . $ArchitraveTypeForDoorDetailsTable . '</td>
                             <td>' . $ArchitraveSizeForDoorDetailsTable . '</td>
                             <td>' . $ArchitraveFinishForDoorDetailsTable . '</td>
                             <td>' . $ArchitraveSetQty . '</td>

                             <td>' . $IronmongerySet . '</td>
                             <td>' . $rWdBRating . '</td>
                             <td>' . $fireRate . '</td>
                             <td>' . $COC . '</td>
                             <td>' . $SpecialFeatureRefs . '</td>
                             <td class="tbl_last">' . round($DoorsetPrice, 2) . '</td>
                             <td class="tbl_last">' . round($IronmongaryPrice, 2) . '</td>
                             <td class="tbl_last">' . round($totalpriceperdoorset, 2) . '</td>
                             </tr>
                             ';
             }
             
             $i++;
             //     }
             // }
         }

         $Alltotalpriceperdoorset = $SumDoorsetPrice + $SumIronmongaryPrice;

         $a .= '
                     <tr>
                         <td class="tbl_bottom" colspan="4"></td>
                         <td class="tbl_bottom">' . $DoorQuantity . '</td>
                         <td class="tbl_bottom" colspan="38"></td>
                         <td class="tbl_bottom">£' . round($SumDoorsetPrice, 2) . '</td>
                         <td class="tbl_bottom">£' . round($SumIronmongaryPrice, 2) . '</td>
                         <td class="tbl_bottom">£' . round($Alltotalpriceperdoorset, 2) . '</td>
                     </tr>
                 ';


         if($quotaion->configurableitems == 4){
             $pdf4 = PDF::loadView('Company.pdf_files.vicaima.pdf2', ['a' => $a, 'comapnyDetail' => $comapnyDetail, 'project' => $project, 'customerContact' => $customerContact, 'version' => $version, 'customer' => $customer]);
         }else{
             $pdf4 = PDF::loadView('Company.pdf_files.pdf2', ['a' => $a, 'comapnyDetail' => $comapnyDetail, 'project' => $project, 'customerContact' => $customerContact, 'version' => $version, 'customer' => $customer]);
         }

         // return $pdf4->download('file4.pdf');
         $path4 = public_path() . '/allpdfFile';
         $fileName4 = $id . '4' . '.' . 'pdf';
         $pdf4->save($path4 . '/' . $fileName4);
    }
}
