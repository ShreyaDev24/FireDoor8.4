<?php

namespace App\Http\Controllers\order;
use Session;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use PDF;
use PdfMerger;

use App\Models\SettingOMmanualIntro;
use App\Models\SettingOMmanualArchIron;
use App\Models\SettingOMmanualDoorFurniture;
use App\Models\Company;
use App\Models\Quotation;
use App\Models\QuotationVersion;
use App\Models\Item;
use App\Models\BOMDetails;
use App\Models\Option;
use App\Models\LippingSpecies;
use App\Models\SettingIntumescentSeals2;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\ItemMaster;
use App\Models\ConfigurableItems;
use App\Models\Project;
use App\Models\AddIronmongery;
use App\Models\SelectedIronmongery;
use App\Models\IronmongeryInfoModel;
use App\Models\IronmongeryID;
use App\Models\GlassType;
use App\Models\GlazingSystem;
use App\Models\User;

class OMMAnualController extends Controller
{
    public function ommanual($quatationId , $versionID ): void
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');
        $id = Auth::user()->id;
        // $pdf1 = SettingOMmanualIntro::where('UserId',$id)->first();
        // $pdf2 = SettingOMmanualArchIron::where('UserId',$id)->first();
        // $pdf3 = SettingOMmanualDoorFurniture::where('UserId',$id)->first();
        $pdf1 = SettingOMmanualIntro::first();
        $pdf2 = SettingOMmanualArchIron::first();
        $pdf3 = SettingOMmanualDoorFurniture::first();

        $comapnyDetail = Company::where('UserId', $id)->first();
        $quotaion = Quotation::where('id',$quatationId)->first();
        $configurationItem = 1;
        if (!empty($quotaion->configurableitems)) {
            $configurationItem = $quotaion->configurableitems;
        }

        $project = empty($quotaion->ProjectId) ? '' : Project::where('id',$quotaion->ProjectId)->first();
        
        if(!empty($quotaion->MainContractorId)){
            $customerContact = CustomerContact::join('customers','customers.id','customer_contacts.MainContractorId')->where('customers.UserId',$quotaion->MainContractorId)->first();
        } else {
            $customerContact = '';
        }
        
        $customer = '';
        $CstCompanyAddressLine1 = '';
        if(!empty($customerContact)){
            $customer = Customer::where(['id' => $customerContact->MainContractorId])->first();
            $CstCompanyAddressLine1 = $customer->CstCompanyAddressLine1;
        }
        
        $SalesContact = 'N/A';
        if(!empty($quotaion->SalesContact)){
            $SalesContact = $quotaion->SalesContact;
        }

        $user = empty($quotaion->UserId) ? '' : User::where('id', $quotaion->CompanyUserId)->first();
        
        // First PDF
        // Introduction PDF
            $pdfOne = PDF::loadView('Order.pdf_files.introduction',['pdf1' => $pdf1, 'comapnyDetail' => $comapnyDetail, 'customer' => $customer]);
            // return $pdfOne->download('file.pdf');
            $path = public_path().'/allpdfFile';
            $fileName1 = $id.'1' . '.' . 'pdf' ;
            $pdfOne->save($path . '/' . $fileName1);

        // Second PDF
        // Architectural Ironmongery
            $pdfTwo = PDF::loadView('Order.pdf_files.architecturalIronmongery',['pdf2' => $pdf2, 'comapnyDetail' => $comapnyDetail, 'customer' => $customer]);
            // return $pdfTwo->download('file.pdf');
            $path2 = public_path().'/allpdfFile';
            $fileName2 = $id.'2' . '.' . 'pdf' ;
            $pdfTwo->save($path2 . '/' . $fileName2);

            $pdf_mpr = PDF::loadView('Order.pdf_files.m_p_r');
            $path_mpr = public_path().'/allpdfFile';
            $fileName_m_p_r = $id.'m_p_r' . '.' . 'pdf' ;
            $pdf_mpr->save($path_mpr . '/' . $fileName_m_p_r);

        // Third PDF
        // DOOR FURNITURE
            $pdfThree = PDF::loadView('Order.pdf_files.doorfurniture',['pdf3' => $pdf3, 'comapnyDetail' => $comapnyDetail, 'customer' => $customer]);
            // return $pdfThree->download('file.pdf');
            $path3 = public_path().'/allpdfFile';
            $fileName3 = $id.'3' . '.' . 'pdf' ;
            $pdfThree->save($path3 . '/' . $fileName3);

            $qv = QuotationVersion::where('id',$versionID)->first();
            $version = $qv->version;

        //Non Configurable Item
            $nonConfigData = nonConfigurableItem($quatationId,$versionID,CompanyUsers());

            $pdf4_2 = PDF::loadView('Company.pdf_files.nonconfigdoor', ['nonConfigData' => $nonConfigData, 'comapnyDetail' => $comapnyDetail, 'quotaion' => $quotaion, 'project' => $project, 'customerContact' => $customerContact, 'version' => $version, 'customer' => $customer]);
            // return $pdf4->download('file4.pdf');
            $path4_2 = public_path() . '/allpdfFile';
            $fileName4_2 = $id . '4_2' . '.' . 'pdf';
            $pdf4_2->save($path4_2 . '/' . $fileName4_2);

        // Fourth PDF
        // Details Door List PDF

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
            
            $pdf4 = PDF::loadView('Order.pdf_files.detaildoorlist',['a2' => $a2, 'comapnyDetail' => $comapnyDetail, 'quotaion' => $quotaion, 'project' => $project, 'version' => $version, 'customer' => $customer]);
            // return $pdf3->download('file3.pdf');
            $path4 = public_path().'/allpdfFile';
            $fileName4 = $id.'4' . '.' . 'pdf' ;
            $pdf4->save($path4 . '/' . $fileName4);

        // Sixth PDF
        // Ironmongery Set
            $itemIronmon = Item::where('QuotationId',$quatationId)->where('VersionId',$versionID)->get();
            $a23 = '';
            $fileName64 = '';
            foreach($itemIronmon as $tt2){
                $IronmongeryID = $tt2->IronmongeryID;
                if(!empty($IronmongeryID)){
                    // echo    $IronmongeryID.'<br>';die;
                    $adIron = AddIronmongery::where('id',$IronmongeryID)->first();
                    $columnName = [
                        'Hinges' => 'hingesQty',
                        'FloorSpring' => 'floorSpringQty',
                        'LocksAndLatches' => 'lockesAndLatchesQty',
                        'FlushBolts' => 'flushBoltsQty',
                        'ConcealedOverheadCloser' => 'concealedOverheadCloserQty',
                        'PullHandles' => 'pullHandlesQty',
                        'PushHandles' => 'pushHandlesQty',
                        'KickPlates' => 'kickPlatesQty',
                        'DoorSelectors' => 'doorSelectorsQty',
                        'PanicHardware' => 'panicHardwareQty',
                        'Doorsecurityviewer' => 'doorSecurityViewerQty',
                        'Morticeddropdownseals' => 'morticeddropdownsealsQty',
                        'Facefixeddropseals' => 'facefixeddropsealsQty',
                        'ThresholdSeal' => 'thresholdSealQty',
                        'AirTransferGrill' => 'airtransfergrillsQty',
                        'Letterplates' => 'letterplatesQty',
                        'CableWays' => 'cableWaysQty',
                        'SafeHinge' => 'safeHingeQty',
                        'LeverHandle' => 'leverHandleQty',
                        'DoorSinage' => 'doorSignageQty',
                        'FaceFixedDoorCloser' => 'faceFixedDoorClosersQty',
                        'Thumbturn' => 'thumbturnQty',
                        'KeyholeEscutchen' => 'keyholeEscutcheonQty',
                        'DoorStops' => 'DoorStopsQty',
                        'Cylinders' => 'CylindersQty'
                    ];
                    //,'FloorSpring','LocksAndLatches','FlushBolts','ConcealedOverheadCloser','PullHandles','PushHandles','KickPlates','DoorSelectors','PanicHardware','Doorsecurityviewer','Morticeddropdownseals','Facefixeddropseals','ThresholdSeal','AirTransferGrill','Letterplates','CableWays','SafeHinge','LeverHandle','DoorSinage','FaceFixedDoorCloser','Thumbturn','KeyholeEscutchen','DoorStops','Cylinders'
                    $count = count($columnName);
                    $i = 0;
                    foreach ($columnName as $key => $value) {

                        $selectIron = SelectedIronmongery::where('id',$adIron->$key)->first();
                        if(!empty($selectIron)){
                            $ironmongeryInfoId = $selectIron->ironmongery_id.'<br>';
                            $ironmongeryInfo = IronmongeryInfoModel::select('Name','Code')->where('id',$ironmongeryInfoId)->first();
                            $a23 .=
                            '<tr>
                                <td>'.$ironmongeryInfo->Code.'</td>
                                <td>'.$ironmongeryInfo->Name.'</td>
                                <td>'.$adIron->$value.'</td>
                            </tr>';
                        }
                    }
                    
                    $Setname = $adIron->Setname;
                    $pdf64 = PDF::loadView('Order.pdf_files.ironmongeryList',['a23' => $a23, 'comapnyDetail' => $comapnyDetail, 'quotaion' => $quotaion, 'project' => $project, 'version' => $version, 'Setname' => $Setname]);
                    // return $pdf3->download('file3.pdf');
                    $path64 = public_path().'/allpdfFile';
                    $fileName64 = $id.'64' . '.' . 'pdf' ;
                    $pdf64->save($path64 . '/' . $fileName64);

                }
            }


        // Seven PDF
        $CompanyId = get_company_id(Auth::user()->id)->id;
        $a = '';
        $i = 1;
        $DoorQuantity = 0;
        $DoorsetPrice = 0;

        $SumDoorsetPrice = 0;
        $SumIronmongaryPrice = 0;
        // $quotation_version = QuotationVersionItems::where(['QuotationId' => $quatationId , 'Version' => $version ])->count();dd($quotation_version);

        foreach ($shows as $show) {

            $fireRate = $show->FireRating;
            if($show->FireRating == 'FD30' || $show->FireRating == 'FD30s'){
                $show->FireRating = 'FD30';
            }elseif($show->FireRating == 'FD60' || $show->FireRating == 'FD60s'){
                $show->FireRating = 'FD60';
            }

            // $quotation_version = QuotationVersionItems::where(['QuotationId' => $quatationId , 'Version' => $version ])->limit(5)->get();
            // foreach($quotation_version as $dt){
            //     $item = Item::where(['itemId' => $dt->itemID , 'CompanyID' => $CompanyId , 'QuotationId' => $quatationId ])->get();
            //     foreach($item as $show){
            // $totalpriceperdoorset = $show->DoorsetPrice + $show->IronmongaryPrice;
            $DoorQuantity += $show->DoorQuantity;
            // $DoorsetPrice += $show->DoorsetPrice;
            // $IronmongaryPrice += $show->IronmongaryPrice;


            $grand_total = BOMDetails::where('itemId', $show->itemId)->sum('grand_total');
            $labour_total = BOMDetails::where('itemId', $show->itemId)->sum('labour_total');
            $DoorsetPrice = (($show->AdjustPrice)?floatval($show->AdjustPrice) :floatval($show->DoorsetPrice));
            $IronmongaryPrice = 0;
            if (!empty($show->IronmongeryID)) {
                $AI = AddIronmongery::select('discountprice')->where('id', $show->IronmongeryID)->first();
                $IronmongaryPrice = $AI->discountprice;
            }
            
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
                    $SelectedFrameMaterialForDoorDetailsTable = LippingSpecies::where("SpeciesName", $show->FrameMaterial)->where('Status',1)->first();
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
                $SelectedLippingSpecies = LippingSpecies::where('id', $show->ArchitraveMaterial)->where('Status',1)->get()->first();
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
                $intumescentSeal = $intum->brand . ' - ' . $intum->intumescentSeals;
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

            $pdf5 = PDF::loadView('Company.pdf_files.pdf2',['a' => $a, 'comapnyDetail' => $comapnyDetail, 'project' => $project, 'customerContact' => $customerContact, 'version' => $version, 'customer' => $customer]);
            // return $pdf4->download('file4.pdf');
            $path5 = public_path().'/allpdfFile';
            $fileName5 = $id.'5' . '.' . 'pdf' ;
            $pdf5->save($path5 . '/' . $fileName5);


        // Eight PDF
        // Elevation Drawing
            $elevTbl = '';
            // $ed = Item::where('QuotationId',$quatationId)->get();
            // $elevTbl = ElevationDrawing($quatationId,$versionID,$CstCompanyAddressLine1,$SalesContact,$configurationItem);
            $ed = Item::join('item_master','item_master.itemID','=','items.itemId')->join("quotation_version_items",function($join): void{
                $join->on("quotation_version_items.itemID","=","items.itemId")
                    ->on("quotation_version_items.itemmasterID","=","item_master.id");
            })
            ->join('quotation','quotation.id','=','items.QuotationId')
            ->where('items.QuotationId', $quatationId)
            ->where('quotation_version_items.version_id', $versionID)->select('items.*','item_master.doorNumber','quotation.configurableitems')->groupBy('item_master.itemID')->get();

            $TotalItems = count($ed->toArray());

            $PageBreakCount = 1;

            foreach ($ed as $tt) {

                $fire = $tt->FireRating;
                if($tt->FireRating == 'FD30' || $tt->FireRating == 'FD30s'){
                    $FireRatingActualValue = 'FD30';
                    $tt->FireRating = 'FD30';
                }elseif($tt->FireRating == 'FD60' || $tt->FireRating == 'FD60s'){
                    $FireRatingActualValue = 'FD60';
                    $tt->FireRating = 'FD60';
                }else{
                    $FireRatingActualValue  =  $tt->FireRating;
                }
                
               // sidelight
                if($tt->FireRating == 'FD30s'){
                    $tt->FireRating = 'FD30';

                }elseif($tt->FireRating == 'FD60s'){
                    $tt->FireRating = 'FD60';

                }

                $configurationDoor = configurationDoor($tt->configurableitems);
                $fireRatingDoor = fireRatingDoor($FireRatingActualValue);

                // dd($tt);
                // $tt->DoorsetType = 'SD';
                if($tt->DoorsetType == 'leaf_and_a_half'){
                    $tt->DoorsetType = 'DD';
                }

                $sidelight = '';
                $frameImageLeftMargin = 10;
                if($tt->DoorsetType == "SD") {
                    $frameImageLeftMargin = 0;
                }

                // $tt->SideLight1 = 'Yes';
                // $tt->SideLight2BeadingType = "Splayed_Flush";

                if(($tt->SideLight1 == 'Yes' || $tt->SideLight2 == 'Yes') && ($tt->SideLight2BeadingType != "Splayed_Bolection")){
                    $sidelight = 'sidelight';
                    $frameImageLeftMargin = 192;
                    if($tt->DoorsetType == "SD") {
                        $frameImageLeftMargin = 222;
                    }

                    // $VisionPanelGlazingImageRight = \Config::get('constants.base64Images.SplayedFlushFD30Right');
                    // $VisionPanelGlazingImageLeft = \Config::get('constants.base64Images.SplayedFlushFD30Left');

                    $SideLightGlazingImageRight = \Config::get('constants.base64Images.SplayedFlushFD30Right');
                    $SideLightGlazingImageLeft = \Config::get('constants.base64Images.SplayedFlushFD30Left');
                }

                // dd($frameImageLeftMargin);



                $countDoorNumber = ItemMaster::where('itemID', $tt->itemId)->count();
                $DoorNumber = ItemMaster::where('itemID', $tt->itemId)->get();

                $doorNo = '';
                foreach ($DoorNumber as $bb) {
                    $doorNo .= '<span style="padding-left:5px;">' . $bb->doorNumber . '</span>';
                }
                
                $species = LippingSpecies::where('id', $tt->FrameMaterial)->where('Status',1)->first();
                if ($species != '') {
                    $frameMaterial = $species->SpeciesName;
                    $GlazingBeadSpecies = $species->SpeciesName;
                } else {
                    $frameMaterial = empty($tt->FrameMaterial) ? 'N/A' : $tt->FrameMaterial;

                    $GlazingBeadSpecies = 'N/A';
                }

                // Overpanel/Fanlight Section :- OP Glazing Bead Species
                $OPspecies = LippingSpecies::where('id', $tt->OPGlazingBeadSpecies)->first();
                $OPGlazingBeadSpecies = $OPspecies != '' ? $OPspecies->SpeciesName : 'N/A';

                $DoorFrameImage = "";
                $VisionPanelGlazingImage = "";



                // dd($tt->toArray());

                $GlazingSystems = $tt->GlazingSystems;
                // $GlazingSystems = "Norsound_Vision_60";
                $GlazingBeads = $tt->GlazingBeads;
                // $GlazingBeads = "Splayed_Bolection";

                // $tt->FireRating = "FD60";
                $GlazingSystems = GlazingSystemsImage($GlazingSystems,$GlazingBeads,$tt->FireRating);
                $VisionPanelGlazingImageStructure = $GlazingSystems['VisionPanelGlazingImageStructure'];
                $VisionPanelGlazingImageRight = $GlazingSystems['VisionPanelGlazingImageRight'];
                $VisionPanelGlazingImageLeft = $GlazingSystems['VisionPanelGlazingImageLeft'];
                $VisionPanelGlazingImageStructure_Frame = $GlazingSystems['VisionPanelGlazingImageStructure_Frame'];

                if ($tt->Leaf1VisionPanel == "Yes" || $tt->Leaf2VisionPanel == "Yes") {

                    $VisionPanelGlazingImage = '<div style=" width:300px;margin: 0 auto;position: relative;">
                            <img style="width: 300px;z-index: 1;" src="' . $VisionPanelGlazingImageStructure . '" alt="">
                            <img style="width: 48px; position: absolute;top: 103px;height: 105px;right: 29.5px;z-index: -1;" src="' . $VisionPanelGlazingImageRight . '" alt="">
                            <img style="width: 48px;position: absolute;top: 103px;height: 105px;left: 142px;z-index: -1;" src="' . $VisionPanelGlazingImageLeft . '" alt="">
                        </div>';
                }


                // echo $VisionPanelGlazingImage;die;
                // dd(\Config::get('constants.base64Images.SplayedBolectionFD60Right'));
                // dd($VisionPanelGlazingImage);
                // $tt->FrameType = "Rebated_Frame";
                $VisionPanelGlazingImageStructure = $VisionPanelGlazingImageStructure_Frame;

                $FrameImageStructureLeft = \Config::get('constants.base64Images.FrameImageStructureLeft');
                $FrameImageStructureRight = \Config::get('constants.base64Images.FrameImageStructureRight');

                if (!empty($tt->FrameType) && $tt->FrameType == "Plant_on_Stop") {
                    $FrameTypeLeft = \Config::get('constants.base64Images.FramePlantOnStopLeft');
                    $FrameTypeRight = \Config::get('constants.base64Images.FramePlantOnStopRight');
                    $FrameTypeCommon = \Config::get('constants.base64Images.FramePlantOnStopCommon');
                } elseif (!empty($tt->FrameType) && $tt->FrameType == "Rebated_Frame") {
                    $FrameTypeLeft = \Config::get('constants.base64Images.FrameRebatedLeft');
                    $FrameTypeRight = \Config::get('constants.base64Images.FrameRebatedRight');
                    $FrameTypeCommon = \Config::get('constants.base64Images.FrameRebatedCommon');
                } else {
                    $FrameTypeLeft = \Config::get('constants.base64Images.FrameRebatedLeft');
                    $FrameTypeCommon = \Config::get('constants.base64Images.FrameRebatedCommon');
                }

                $FixedSpaceBlock = \Config::get('constants.base64Images.FixedSpaceBlock');
                $RemainingSpaceBlock = \Config::get('constants.base64Images.RemainingSpaceBlock');
                $FullBlock = \Config::get('constants.base64Images.FullBlock');

                $remainingWidth = $tt->LeafWidth1 - ($tt->Leaf1VPWidth + $tt->DistanceFromTheEdgeOfDoor);

                if ($tt->DistanceFromTheEdgeOfDoor > $remainingWidth) {
                    $FrameImageStructureLeft = $FixedSpaceBlock;
                    $FrameImageStructureRight = $RemainingSpaceBlock;
                } elseif ($tt->DistanceFromTheEdgeOfDoor < $remainingWidth) {
                    $FrameImageStructureLeft = $RemainingSpaceBlock;
                    $FrameImageStructureRight = $FixedSpaceBlock;
                } else {
                    $FrameImageStructureLeft = $RemainingSpaceBlock;
                    $FrameImageStructureRight = $RemainingSpaceBlock;
                }

                $leaf1RemainingWidth = $tt->LeafWidth1 - ($tt->Leaf1VPWidth + $tt->DistanceFromTheEdgeOfDoor);

                if ($tt->DistanceFromTheEdgeOfDoor > $leaf1RemainingWidth) {
                    $FrameImageStructureLeftLeaf1 = $FixedSpaceBlock;
                    $FrameImageStructureRightLeaf1 = $RemainingSpaceBlock;
                } elseif ($tt->DistanceFromTheEdgeOfDoor < $leaf1RemainingWidth) {
                    $FrameImageStructureLeftLeaf1 = $RemainingSpaceBlock;
                    $FrameImageStructureRightLeaf1 = $FixedSpaceBlock;
                } else {
                    $FrameImageStructureLeftLeaf1 = $RemainingSpaceBlock;
                    $FrameImageStructureRightLeaf1 = $RemainingSpaceBlock;
                }

                $leaf2RemainingWidth = $tt->LeafWidth2 - ($tt->Leaf2VPWidth + $tt->DistanceFromTheEdgeOfDoorforLeaf2);

                if ($tt->DistanceFromTheEdgeOfDoorforLeaf2 > $leaf2RemainingWidth) {
                    $FrameImageStructureLeftLeaf2 = $RemainingSpaceBlock;
                    $FrameImageStructureRightLeaf2 = $FixedSpaceBlock;
                } elseif ($tt->DistanceFromTheEdgeOfDoorforLeaf2 < $leaf2RemainingWidth) {
                    $FrameImageStructureLeftLeaf2 = $FixedSpaceBlock;
                    $FrameImageStructureRightLeaf2 = $RemainingSpaceBlock;
                } else {
                    $FrameImageStructureLeftLeaf2 = $RemainingSpaceBlock;
                    $FrameImageStructureRightLeaf2 = $RemainingSpaceBlock;
                }
                
                $redstripRightCommonClass = $tt->IntumescentLeapingSealLocation.'_right_strip_'.$tt->DoorsetType;
                $redstripLeftCommonClass = $tt->IntumescentLeapingSealLocation.'_left_strip_'.$tt->DoorsetType;

                switch ($tt->DoorsetType) {
                    case "SD":
                        // $DoorFrameImage = Base64Image('FD30SingleDoorsetwithVP');

                        $DoorFrameImage = '<div style="padding:10px 30px;position: relative;margin-left: '.$frameImageLeftMargin.'px;">
                                    <div style="position: relative;top: 12px;">';

                        if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {

                            if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                                $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'" style="border: 0.5px solid black;
                                        background-color: red;
                                        z-index: 999;
                                        position: absolute;
                                        height: 16px;
                                        width: 6px;
                                        box-shadow: none;
                                        margin-left: 3px;
                                        margin-top: 25px;"></div>';
                            } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                                $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'"  style="border: 0.5px solid black;
                                        background-color: red;
                                        z-index: 999;
                                        position: absolute;
                                        height: 16px;
                                        width: 6px;
                                        box-shadow: none;
                                        margin-left: 3px;
                                        margin-top: 15px;"></div>

                                        <div class="'.$redstripLeftCommonClass.'"  style="border: 0.5px solid black;
                                        background-color: red;
                                        z-index: 999;
                                        position: absolute;
                                        height: 16px;
                                        width: 6px;
                                        box-shadow: none;
                                        margin-left: 3px;
                                        margin-top: 42px;"></div>';
                            }
                        }


                        if($sidelight !== "" && $tt->SideLight1 == 'Yes'){

                            $DoorFrameImage .= '<div style="
                            width: 0px;
                            margin: 0 auto;
                            position: relative;
                            margin-left: -66px;
                            top: -131px;
                            left: -119px;
                            ">
                            <img style="
                            z-index: 999;
                            position: absolute;
                            width: 78px;
                            height: 71px;
                            margin-left: -26px;
                            '.
                                (($tt->FireRating == 'FD30') ?
                                    'top: 133px;'
                                    :
                                    'top: 128px;')
                            .'
                            " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                               >
                            <img style="

                               position: absolute;
                               z-index: -1;
                               transform: rotate(90deg);
                               '.
                                (($tt->FireRating == 'FD30') ?
                                    'width: 30px;height: 70px;top: 159px;right: -5px;'
                                    :
                                    'width: 25px;height: 67px;top: 158px;right: -2px;')
                                .'
                               " src="'.$SideLightGlazingImageRight.'"
                               >
                            <img style="
                               position: absolute;
                               z-index: -1;
                               transform: rotate(90deg);
                               '.
                                (($tt->FireRating == 'FD30') ?
                                    'width: 30px;height: 70px;top: 108px;left: -25px;'
                                    :
                                    'width: 25px;height: 67px;top: 108px;left: -23px;')
                                .'
                               " src="'.$SideLightGlazingImageLeft.'"
                               >
                         </div>
                         <div style="position: absolute;top: 5px;left: -265px;">
                            <img style="width: 76px;" alt="" src="'.$FrameTypeCommon.'">
                         </div>
                         <div style="
                            width: 0px;
                            margin: 0 auto;
                            position: relative;
                            margin-left: -79px;
                            top: 2px;
                            left: -4px;
                            transform: rotate(181deg);
                            ">
                            <img style="
                               z-index: 999;
                               position: absolute;
                               margin-top: 86px;
                               width: 78px;
                               height: 71px;
                               margin-left: -8px;
                               '.
                                (($tt->FireRating == 'FD30') ?
                                    'top: -158px;'
                                    :
                                    'top: -157px;')
                                .'
                               " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                               >
                            <img style="
                               position: absolute;
                               z-index: -1;
                               transform: rotate(90deg);
                               '.
                                (($tt->FireRating == 'FD30') ?
                                    'width: 30px;height: 70px;top: -46px;right: -23px;'
                                    :
                                    'width: 25px;height: 67px;top: -43px;right: -21px;')
                                .'
                               " src="'.$SideLightGlazingImageRight.'">
                            <img style="
                               position: absolute;
                               z-index: -1;
                               transform: rotate(90deg);
                               '.
                                (($tt->FireRating == 'FD30') ?
                                    'width: 30px;height: 70px;top: -97px;right: -22px;'
                                    :
                                    'width: 25px;height: 67px;top: -93px;right: -20px;')
                                .'
                               " src="'.$SideLightGlazingImageLeft.'">
                         </div>
                         <div style="position: absolute;top: 7px;left: -87px;">
                            <img style="width: 76px;" alt="" src="'.$FrameTypeCommon.'">
                         </div>';

                        }


                        $DoorFrameImage .= '<div  style="position: absolute; top: 7px;left: -44px;">
                                            <img style="width:76px;" src="' . $FrameTypeLeft . '" alt="">
                                        </div>
                                    </div>';

                        if ($tt->Leaf1VisionPanel == 'Yes') {

                            $DoorFrameImage .= '<div style="position: relative;top: 12px;">
                                        <img style="
                                            width: 165px;
                                            position: relative;
                                            top: -17px;
                                            left: 14px;" src="' . $FrameImageStructureLeft . '" alt="">
                                        </div>
                                        <div style="width:430px;">
                                            <div style="
                                                width:200px;
                                                margin: 0 auto;
                                                position: relative;
                                                margin-left: -66px;
                                                top: -205px;
                                                left: 244px;
                                                ">

                                                <img style="
                                                z-index: 999;
                                                ' .(($tt->FireRating == 'FD30') ?
                                'width: 125px; margin-top: 68px;' :
                                'width: 135px; margin-left: -8px; margin-top: 65px;') . '" src="' . $VisionPanelGlazingImageStructure . '" alt="">

                                                <img style="position: absolute;
                                                top: 137.2px;
                                                z-index: -1;
                                                transform: rotate(90deg);' . (($tt->FireRating == 'FD30') ? 'width: 30px;left: 4px;height: 70px;' : 'width: 25px;left: 5px;height: 67px;') . '" src="' . $VisionPanelGlazingImageRight . '" alt="">

                                                <img style="position: absolute;
                                                z-index: -1;
                                                transform: rotate(90deg);' . (($tt->FireRating == 'FD30') ? 'width: 30px;left: 4px;top: 86.2px;height: 70px;' : 'width: 25px;top: 84.8px;left:5px;height: 67px;') . '" src="' . $VisionPanelGlazingImageLeft . '" alt="">

                                            </div>
                                            <div style="
                                                width:200px;
                                                margin-top:120px;
                                                position: relative;
                                                top: -493px;
                                                left: 265px;
                                                ">

                                                <img style="
                                                z-index: 999;
                                                ' . (($tt->FireRating == 'FD30') ?
                                'width: 125px; margin-top: 80px; margin-left: 25px;' :
                                'width: 135px; margin-top: 78.5px; margin-left: 23.5px;') . '
                                                transform: rotate(180deg);" src="' . $VisionPanelGlazingImageStructure . '" alt="">

                                                <img style="
                                                position: absolute;
                                                ' . (($tt->FireRating == 'FD30') ? 'top: 106.2px;width: 30px; left: 116px;height: 70px;' : 'top: 103.2px;width: 25px; left: 120px;height: 67px;') . '
                                                z-index: -1;
                                                transform: rotate(270deg);" src="' . $VisionPanelGlazingImageRight . '" alt="">

                                                <img style="
                                                position: absolute;
                                                ' . (($tt->FireRating == 'FD30') ? 'top: 158.2px;width: 30px; left: 116px;height: 70px;' : 'top: 156px;width: 25px; left: 120px;height: 67px;') . '

                                                z-index: -1;
                                                transform: rotate(270deg);" src="' . $VisionPanelGlazingImageLeft . '" alt="">
                                            </div>
                                        </div>
                                        <div style="position: relative;position: absolute;top: 7px;left:475px;">';

                            // if($tt->IntumescentLeapingSealLocation == 'Door'){

                            //     if(in_array($tt->FireRating, ["FD30", "FD30s"])){

                            //         $DoorFrameImage .= '<div  class="'.$redstripCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 16px;width: 6px;box-shadow: none;margin-left: 131px;margin-top: 40px;"></div>';


                            //     }else if(in_array($tt->FireRating, ["FD60", "FD60s"])){

                            //         $DoorFrameImage .= '<div  class="'.$redstripCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 16px;width: 6px;box-shadow: none;margin-left: 131px;margin-top: 30px;"></div>
                            //     <div style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 16px;width: 6px;box-shadow: none;margin-left: 131px;margin-top: 57px;"></div>';

                            //     }
                            // }

                            $DoorFrameImage .= '<img style="width: 170px;
                                            position: relative;
                                            bottom: 5px;
                                            left: -31px;" src="' . $FrameImageStructureRight . '" alt="">
                                        </div>';
                        } else {

                            // dd($FullBlock);
                            $DoorFrameImage .= '<div style="position: relative;top: 12px;">';

                            // if($tt->IntumescentLeapingSealLocation == 'Door'){


                            //     if(in_array($tt->FireRating, ["FD30", "FD30s"])){

                            //         $DoorFrameImage .= '<div  class="'.$redstripCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 16px;width: 6px;box-shadow: none;margin-left: 131px;margin-top: 40px;"></div>';


                            //     }else if(in_array($tt->FireRating, ["FD60", "FD60s"])){

                            //         $DoorFrameImage .= '<div class="'.$redstripCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 16px;width: 6px;box-shadow: none;margin-left: 131px;margin-top: 30px;"></div>
                            //         <div style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 16px;width: 6px;box-shadow: none;margin-left: 131px;margin-top: 57px;"></div>';
                            //     }
                            // }

                            $DoorFrameImage .= '<img style="width: 575px;
                            position: relative;
                            top: -17px;
                            left: 11px;height: 108px;" src="' . $FullBlock . '" alt="">
                                    </div>';
                        }

                        $DoorFrameImage .= '
                                    <div style="position: relative;position: absolute;top: 7px;left:642px;">';

                        if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {


                            if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                                $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'"  style="border: 0.5px solid black;
                                            background-color: red;
                                            z-index: 999;
                                            position: absolute;
                                            height: 16px;
                                            width: 6px;
                                            box-shadow: none;
                                            margin-left: -25.5px;
                                            margin-top: 40px;"></div>';
                            } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                                $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'" style="border: 0.5px solid black;
                                            background-color: red;
                                            z-index: 999;
                                            position: absolute;
                                            height: 16px;
                                            width: 6px;
                                            box-shadow: none;
                                            margin-left: -25.5px;
                                            margin-top: 30px;"></div>

                                            <div  class="'.$redstripRightCommonClass.'" style="border: 0.5px solid black;
                                            background-color: red;
                                            z-index: 999;
                                            position: absolute;
                                            height: 16px;
                                            width: 6px;
                                            box-shadow: none;
                                            margin-left: -25.5px;
                                            margin-top: 57px;"></div>';
                            }
                        }



                        $DoorFrameImage .= '<div style="position: absolute;top: 21px;right: -27px;">
                                            <img style="width: 77px;
                                            margin-top: -1px;" src="' . $FrameTypeRight . '" alt="">
                                        </div>
                                    </div>
                                ';

                        if($sidelight !== "" && $tt->SideLight2 == 'Yes'){

                            $DoorFrameImage .= '<div style="position: absolute;top: 23px;left: 912px;">
                            <div style="
                               width: 0px;
                               margin: 0 auto;
                               position: relative;
                               margin-left: -66px;
                               top: -131px;
                               left: -119px;
                               ">
                               <img style="
                                  z-index: 999;
                                  position: absolute;
                                  width: 78px;
                                  height: 71px;
                                  margin-left: -26px;
                                  top: 132px;
                                  '.
                                (($tt->FireRating == 'FD30') ?
                                    'top: 132px;'
                                    :
                                    'top: 127px;')
                                .'
                                  " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                                  >
                               <img style="
                                  position: absolute;
                                  z-index: -1;
                                  transform: rotate(90deg);
                                  '.
                                    (($tt->FireRating == 'FD30') ?
                                        'width: 30px;height: 70px;top: 158px;right: -5px;'
                                        :
                                        'width: 25px;height: 67px;top: 156px;right: -5px;')
                                    .'
                                  " src="'.$SideLightGlazingImageRight.'"
                                  >
                               <img style="
                                  position: absolute;
                                  z-index: -1;
                                  transform: rotate(90deg);
                                  '.
                                    (($tt->FireRating == 'FD30') ?
                                        'width: 30px;height: 70px;top: 107px;left: -25px;'
                                        :
                                        'width: 25px;height: 67px;top: 107px;left: -21px;')
                                    .'
                                  " src="'.$SideLightGlazingImageLeft.'">
                            </div>
                            <div style="position: absolute;top: 4px;left: -265px;">
                               <img style="width: 77px;" alt="" src="'.$FrameTypeCommon.'">
                            </div>
                            <div style="
                               width: 0px;
                               margin: 0 auto;
                               position: relative;
                               margin-left: -79px;
                               top: 2px;
                               left: -4px;
                               transform: rotate(181deg);
                               ">
                               <img style="
                                  z-index: 999;
                                  position: absolute;
                                  margin-top: 86px;
                                  width: 78px;
                                  height: 71px;
                                  margin-left: -8px;
                                  top: -157px;
                                  '.
                                (($tt->FireRating == 'FD30') ?
                                    'top: -157px;'
                                    :
                                    'top: -155px;')
                                .'" alt="" src="'.$VisionPanelGlazingImageStructure.'"
                                  >
                               <img style="
                                  position: absolute;
                                  z-index: -1;
                                  transform: rotate(90deg);
                                  '.
                                    (($tt->FireRating == 'FD30') ?
                                        'width: 30px;height: 70px;top: -45px !important;right: -23px;'
                                        :
                                        'width: 25px;height: 67px;top: -42px !important;right: -21px;')
                                    .'
                                  " src="'.$SideLightGlazingImageRight.'">
                               <img style="
                                  position: absolute;
                                  z-index: -1;
                                  transform: rotate(90deg);
                                  '.
                                    (($tt->FireRating == 'FD30') ?
                                        'width: 30px;height: 70px;top: -96px;right: -22px;'
                                        :
                                        'width: 25px;height: 67px;top: -91px;right: -20px;')
                                    .'
                                  " src="'.$SideLightGlazingImageLeft.'">
                            </div>
                            <div style="position: absolute;top: 7px;left: -87px;">
                               <img style="width: 76px;" alt="" src="'.$FrameTypeCommon.'"
                                  >
                            </div>
                           </div>

                          </div>';

                        }

                        break;


                    case "DD":

                        // $DoorFrameImage = Base64Image('FD30DoubleDoorsetwithVP');

                        $DoorFrameImage = '<div style="padding: 10px 30px; position: relative;margin-left: '.$frameImageLeftMargin.'px;">
                                <div style="position: relative;  top: 12px;">';



                        if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {

                            if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                                $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: -12px;margin-top: 18px;"></div>';
                            } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                                $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: -12px;margin-top: 10px;"></div>
                                        <div class="'.$redstripLeftCommonClass.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: -12px;margin-top: 25px;"></div>';
                            }
                        }

                        // ----------------Left-------------------

                        if($sidelight !== "" && $tt->SideLight1 == 'Yes'){

                            $DoorFrameImage .= '<div style="
                                    width: 0px;
                                    margin: 0 auto;
                                    position: relative;
                                    margin-left: -66px;
                                    top: -131px;
                                    left: -119px;
                                    ">

                                    <img style="
                                    z-index: 999;
                                    position: absolute;
                                    '.
                                    (($tt->FireRating == 'FD30') ?
                                    'width: 66px;height: 50px;margin-left: -4px;top: 127px;'
                                    :
                                    'width: 76px;margin-left: -7px;top: 127px;')
                                     .'
                                    " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                                        />

                                    <img style="
                                    width: 14px;
                                    height: 40px;
                                    position: absolute;
                                    top: 147px;
                                    right: -12px;
                                    z-index: -1;
                                    transform: rotate(90deg);
                                    " src="'.$SideLightGlazingImageRight.'"
                                    />

                                    <img style="
                                    width: 14px;
                                    height: 40px;
                                    position: absolute;
                                    top: 118px;
                                    left: -2px;
                                    z-index: -1;
                                    transform: rotate(90deg);
                                    " src="'.$SideLightGlazingImageLeft.'"
                                        />

                                </div>

                                <div style="position: absolute;top: 4px;left: -222px;">';

                                // if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {
                                //     if (in_array($tt->FireRating, ["FD30", "FD30s"])) {

                                //         $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 18px;"></div>';

                                //     } else if (in_array($tt->FireRating, ["FD60", "FD60s"])) {

                                //         $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 10px;"></div>
                                //                 <div class="'.$redstripLeftCommonClass.'_'.$sidelight.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 25px;"></div>';
                                //     }
                                // }


                                $DoorFrameImage .= '<img style="width: 46px;" alt="" src="'.$FrameTypeCommon.'" />
                                </div>

                                <div style="
                                width: 0px;
                                margin: 0 auto;
                                position: relative;
                                margin-left: -79px;
                                top: 2px;
                                left: 13px;
                                transform: rotate(181deg);
                                ">

                                    <img style="
                                        z-index: 999;
                                        position: absolute;
                                        margin-top: 86px;
                                        '.
                                        (($tt->FireRating == 'FD30') ?
                                        'width: 66px;height: 55px;margin-left: -8px;top: -133px;'
                                        :
                                        ' width: 76px;margin-left: -10px;top: -131px;')
                                        .'
                                        " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                                        >

                                    <img style="
                                            width: 14px;
                                            height: 40px;
                                            position: absolute;
                                            top: -24px !important;
                                            right: -9px;
                                            z-index: -1;
                                            transform: rotate(90deg);
                                        " src="'.$SideLightGlazingImageRight.'"
                                        >

                                    <img style="
                                            width: 14px;
                                            height: 40px;
                                            position: absolute;
                                            top: -55px;
                                            right: -9px;
                                            z-index: -1;
                                            transform: rotate(90deg);
                                        " src="'.$SideLightGlazingImageLeft.'"
                                        >

                                </div>

                                <div style="position: absolute;top: 4px;left: -66px;">';


                                // if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {


                                //     if (in_array($tt->FireRating, ["FD30", "FD30s"])) {

                                //         $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 18px;"></div>';

                                //     } else if (in_array($tt->FireRating, ["FD60", "FD60s"])) {

                                //         $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 10px;"></div>
                                //                 <div class="'.$redstripRightCommonClass.'_'.$sidelight.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 25px;"></div>';
                                //     }
                                // }
                                $DoorFrameImage .= '<img style="width: 46px;" alt="" src="'.$FrameTypeCommon.'">
                                </div>
                            ';

                        }


                        $DoorFrameImage .= '<div style="position: absolute; top: 4px; left: -40px;">
                                        <img style="width: 46px;" alt="" src="' . $FrameTypeLeft . '">
                                    </div>
                                </div>';

                        if ($tt->Leaf1VisionPanel == 'Yes') {

                            $DoorFrameImage .= '
                                    <div style="position: relative;  top: 12px;">
                                        <img style="width: 97px;
                                            position: relative;
                                            top: -10px;
                                            left: -5px;" alt="" src="' . $FrameImageStructureLeftLeaf1 . '">
                                    </div>
                                    <div style="width:265px; position: relative;">
                                        <div style="width: 0px;
                                        margin: 0 auto;
                                        position: relative;
                                        margin-left: -66px;
                                        top: -185px;
                                        left: 258px;">
                                            <img style="z-index: 999;
                                            ' . (($tt->FireRating == 'FD30') ? 'width: 70px; margin-left: -101px;
                                            margin-top: 79px;' : 'width: 75px;
                                            margin-left: -103.5px;
                                            margin-top: 77.5px;') . '" alt="" src="' . $VisionPanelGlazingImageStructure . '">

                                            <img style="position: absolute;
                                            top: 150.5px;
                                            transform: rotate(90deg);
                                            z-index: -1;height: 40px;' . (($tt->FireRating == 'FD30') ? 'width: 17px; right: 82px;' : 'width: 14px;left: -97px;') . '" src="' . $VisionPanelGlazingImageRight . '" alt="">

                                            <img style="position: absolute;
                                            top: 120px;
                                            transform: rotate(90deg);
                                            z-index: -1;height: 40px;' . (($tt->FireRating == 'FD30') ? 'left: -99px;width: 17px;' : 'left: -97px;width: 14px;') . '" src="' . $VisionPanelGlazingImageLeft . '" alt="">
                                        </div>

                                        <div style="width: 200px; margin-top: 120px; position: relative; top:-354px; left:220px;">

                                            <img style="
                                            transform: rotate(180deg);
                                            z-index: 999;
                                            ' . (($tt->FireRating == 'FD30') ? 'width: 70px;
                                            margin-left: -67px;
                                            margin-top: 38.2px;' : 'width: 75px; margin-left: -69px;
                                            margin-top: 37.5px;') . '" alt="" src="' . $VisionPanelGlazingImageStructure . '">

                                            <img style="position: absolute;
                                            ' . (($tt->FireRating == 'FD30') ? 'top: 47px;width: 17px; left: -16px;
                                            ' : 'top: 44px;width: 14px; left: -14px;
                                            ') . '
                                            z-index: -1;
                                            transform: rotate(270deg);
                                            height: 40px;" src="' . $VisionPanelGlazingImageRight . '" alt="">

                                            <img style="width: 17px;
                                            position: absolute;
                                            ' . (($tt->FireRating == 'FD30') ? 'top: 76px;width: 17px; left: -16px;
                                            ' : 'top: 75px;width: 14px; left: -14px;
                                            ') . '
                                            z-index: -1;
                                            transform: rotate(270deg);height: 40px;" src="' . $VisionPanelGlazingImageLeft . '" alt="">
                                        </div>
                                    </div>

                                    <div style="position: absolute;
                                        top: 8px;
                                        left: 290px;">';

                            if ($tt->IntumescentLeapingSealLocation == 'Door' || $tt->IntumescentLeapingSealLocation == 'Frame') {

                                if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                                    $DoorFrameImage .= '<div style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 53px;margin-top: 33px;"></div>';
                                } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                                    $DoorFrameImage .= '<div class=""  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 53px;margin-top: 23px;"></div>
                                                <div style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 53px;margin-top: 40px;"></div>';
                                }
                            }

                            $DoorFrameImage .= '<img style="width: 95px;
                                            position: relative;
                                            top: 5px;
                                            right: 37.5px;" alt="" src="' . $FrameImageStructureRightLeaf1 . '">
                                    </div>';
                        } else {
                            $DoorFrameImage .= '<div style="position: relative;top: 12px;">';

                            if ($tt->IntumescentLeapingSealLocation == 'Door' || $tt->IntumescentLeapingSealLocation == 'Frame') {

                                if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                                    $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 310px;margin-top: 18px;"></div>';
                                } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                                    $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 310px;margin-top: 13px;"></div>
                                                <div class="'.$redstripRightCommonClass.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 310px;margin-top: 24px;"></div>';
                                }
                            }

                            $DoorFrameImage .= '<img style="
                                        width: 323px;
                                        position: relative;
                                        top: -10px;
                                        left: -5.5px;" src="' . $FullBlock . '" alt="">
                                    </div>';
                        }

                        if ($tt->Leaf2VisionPanel == 'Yes') {

                            $DoorFrameImage .= '<div style="position: absolute;
                                            top: 8px;
                                            left: 386px;">
                                            <img style="width: 95px;
                                                position: relative;
                                                top: 5px;
                                                right: 37.5px;" alt="" src="' . $FrameImageStructureLeftLeaf2 . '">
                                        </div>

                                        <div style="position: absolute;top: 78px;left: 286px;">
                                            <div style="width: 0px;
                                                margin: 0 auto;
                                                position: relative;
                                                margin-left: -66px;
                                                top: -185px;
                                                left: 228px;">

                                                <img style="
                                                    z-index: 999;
                                                    ' . (($tt->FireRating == 'FD30') ? 'width: 70px; margin-left: -5px;
                                                    margin-top: 130px;' : 'width: 75px; margin-left: -8px;
                                                    margin-top: 128px;') . '" alt="" src="' . $VisionPanelGlazingImageStructure . '">

                                                <img style="position: absolute;z-index: -1;
                                                    transform: rotate(90deg);height:40px;' . (($tt->FireRating == 'FD30') ? 'width: 17px; right: -14px;top: 147px;' : 'width: 14px; right: -12.9px;top: 146.7px;') . '" src="' . $VisionPanelGlazingImageRight . '" alt="">

                                                <img style="position: absolute;
                                                    z-index: -1;
                                                    transform: rotate(90deg);height:40px;' . (($tt->FireRating == 'FD30') ? 'width: 17px; right: -14px;top: 117px;' : 'width: 14px;right: -12.9px;top: 117.3px;') . '" src="' . $VisionPanelGlazingImageLeft . '" alt="">

                                            </div>
                                            <div style="width: 200px;
                                                margin-top: 120px;
                                                position: relative;
                                                top: -408px;
                                                left: 190px;">

                                                <img style="
                                                    z-index: 999;
                                                    transform: rotate(180deg);
                                                    ' . (($tt->FireRating == 'FD30') ? 'width: 70px;
                                                    margin-left: 29px;
                                                    margin-top: 38px;' : 'width: 75px; margin-left: 27px;
                                                    margin-top: 38px;') . '" alt="" src="' . $VisionPanelGlazingImageStructure . '">

                                                <img style="position: absolute;
                                                    ' . (($tt->FireRating == 'FD30') ? 'top: 47px;width: 17px;right: 103px;' : 'top: 46px;width: 14px;right: 104px;') . 'z-index: -1;
                                                    transform: rotate(270deg);height:40px;" src="' . $VisionPanelGlazingImageRight . '" alt="">

                                                <img style="position: absolute;
                                                    ' . (($tt->FireRating == 'FD30') ? 'top: 76px;width: 17px;right: 103px;' : 'top: 77px;right: 104px;width: 14px;') . 'z-index: -1;
                                                    transform: rotate(270deg);height:40px;" src="' . $VisionPanelGlazingImageLeft . '" alt="">

                                            </div>
                                        </div>
                                        <div style="position: relative;  position: absolute; top: 8px; left: 630px;">
                                            <img style="width: 95px;
                                                position: relative;
                                                top: 5px;
                                                right: 55px;" alt="" src="' . $FrameImageStructureRightLeaf2 . '">
                                        </div>';
                        } else {
                            $DoorFrameImage .= '<div style="position: absolute;
                                            top: 8px;
                                            left: 386px;">
                                            <img style="width: 319px;
                                            position: relative;
                                            top: 4px;
                                            right: 35px;" alt="" src="' . $FullBlock . '">
                                        </div>';
                        }

                        $DoorFrameImage .= '
                                        <div style="position: relative;  position: absolute; top: 8px; left: 722px;">';

                        if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {

                            if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                                $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: -50px;margin-top: 33px;"></div>';
                            } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                                $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: -50px;margin-top: 23px;"></div>
                                        <div class="'.$redstripRightCommonClass.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: -50px;margin-top: 40px;"></div>';
                            }
                        }

                        $DoorFrameImage .= '<div style="position: absolute; top: 18px; right:20px;">
                                                <img style="width:46px;" alt="" src="' . $FrameTypeRight . '">
                                            </div>';

                        // ----------------Right-------------------

                        if($sidelight !== "" && $tt->SideLight2 == 'Yes'){

                            $DoorFrameImage .= '<div style="position:relative; right:-191px; bottom:-14px;"><div style="
                                    width: 0px;
                                    margin: 0 auto;
                                    position: relative;
                                    margin-left: -66px;
                                    top: -131px;
                                    left: -119px;
                                    ">

                                    <img style="
                                    z-index: 999;
                                    position: absolute;
                                    '.
                                        (($tt->FireRating == 'FD30') ?
                                        'width: 66px;height: 50px;margin-left: -5px;top: 127px;'
                                        :
                                        'width: 76px;margin-left: -7px;top: 127px;')
                                        .'
                                    " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                                        />

                                    <img style="
                                    width: 14px;
                                    height: 40px;
                                    position: absolute;
                                    top: 147px;
                                    right: -11px;
                                    z-index: -1;
                                    transform: rotate(90deg);
                                    " src="'.$SideLightGlazingImageRight.'"
                                    />

                                    <img style="
                                    width: 14px;
                                    height: 40px;
                                    position: absolute;
                                    top: 118px;
                                    left: -3px;
                                    z-index: -1;
                                    transform: rotate(90deg);
                                " src="'.$SideLightGlazingImageLeft.'"
                                        />

                                </div>

                                <div style="position: absolute;top: 4px;left: -222px;">';

                                // if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {
                                //     if (in_array($tt->FireRating, ["FD30", "FD30s"])) {

                                //         $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 18px;"></div>';

                                //     } else if (in_array($tt->FireRating, ["FD60", "FD60s"])) {

                                //         $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 10px;"></div>
                                //                 <div class="'.$redstripLeftCommonClass.'_'.$sidelight.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 25px;"></div>';
                                //     }
                                // }


                                $DoorFrameImage .= '<img style="width: 46px;" alt="" src="'.$FrameTypeCommon.'" />
                                </div>

                                <div style="
                                    width: 0px;
                                    margin: 0 auto;
                                    position: relative;
                                    margin-left: -79px;
                                    top: 6px;
                                    left: 13px;
                                    transform: rotate(181deg);
                                    ">

                                    <img style="
                                    z-index: 999;
                                    margin-top: 86px;
                                    position: absolute;
                                    '.
                                        (($tt->FireRating == 'FD30') ?
                                        ' width: 66px;height: 52px;margin-left: -7px;top: -127px;'
                                        :
                                        'width: 76px;margin-left: -10px;top: -126px;')
                                        .'" alt="" src="'.$VisionPanelGlazingImageStructure.'"
                                        >
                                    <img style="
                                        width: 14px;
                                        height: 40px;
                                        position: absolute;
                                        right: -9px;
                                        z-index: -1;
                                        transform: rotate(90deg);
                                        '.
                                        (($tt->FireRating == 'FD30') ?
                                        'top: -20px !important;'
                                        :
                                        'top: -19px !important;')
                                        .'" src="'.$SideLightGlazingImageRight.'"
                                        >

                                    <img style="
                                    width: 14px;
                                    height: 40px;
                                    position: absolute;
                                    top: -50px;
                                    right: -9px;
                                    z-index: -1;
                                    transform: rotate(90deg);
                                    " src="'.$SideLightGlazingImageLeft.'"
                                        >

                                </div>

                                <div style="position: absolute;top: 4px;left: -66px;">';


                                // if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {


                                //     if (in_array($tt->FireRating, ["FD30", "FD30s"])) {

                                //         $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 18px;"></div>';

                                //     } else if (in_array($tt->FireRating, ["FD60", "FD60s"])) {

                                //         $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 10px;"></div>
                                //                 <div class="'.$redstripRightCommonClass.'_'.$sidelight.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 25px;"></div>';
                                //     }
                                // }
                                $DoorFrameImage .= '<img style="width: 46px;" alt="" src="'.$FrameTypeCommon.'">
                                </div>
                                </div>
                            ';

                        }

                    break;

                }


                $QuotationGenerationId = null;
                if (!empty($quotaion->QuotationGenerationId)) {
                    $QuotationGenerationId = $quotaion->QuotationGenerationId;
                }
                
                $ProjectName = null;
                if (!empty($project->ProjectName)) {
                    $ProjectName = $project->ProjectName;
                }
                
                if (!empty($version)) {
                    $version = $version;
                }
                
                $CompanyAddressLine1 = null;
                if (!empty($comapnyDetail->CompanyAddressLine1)) {
                    $CompanyAddressLine1 = $comapnyDetail->CompanyAddressLine1;
                }
                
                $Username = null;
                if (!empty($user->FirstName) && !empty($user->LastName)) {
                    $Username = $user->FirstName . ' ' . $user->LastName;
                }

                if (!empty($tt->SvgImage)) {
                    $svgFile = str_contains((string) $tt->SvgImage, '.png') ? URL('/') . '/uploads/files/' . $tt->SvgImage : $tt->SvgImage;
                } else {
                    // $svgFile = URL('/') . '/uploads/files/door.jpg';
                    $svgFile = URL('/') . '/uploads/files/no_image_prod.jpg';
                }
                
                $elevTbl .=
                    '
                    <div id="headText">
                        <b>Elevation Drawing</b>
                    </div>
                    <div id="main">
                        <div id="section-left">
                            <table id="NoBorder">
                                <tr>
                                    <td colspan="2">
                                        <table id="WithBorder" class="tbl1">
                                            <tbody>
                                                <tr>
                                                    <td class="marImg" rowspan="2">
                                                        <span>';
                if (!empty($comapnyDetail->ComplogoBase64)) {
                    $elevTbl .=
                        '<img src="' . $comapnyDetail->ComplogoBase64 . '" class="imgClass" alt="Logo"/>';
                } else {
                    $elevTbl .= Base64Image('defaultImg');
                }
                
                $elevTbl .=
                    '</span>
                                                    </td>
                                                    <td class="tbl_color"><span>Ref</span></td>
                                                    <td colspan="3"><span>' . $QuotationGenerationId . '</span></td>
                                                    <td class="tbl_color"><span>Project</span></td>
                                                    <td><span>' . $ProjectName . '</span></td>
                                                    <td class="tbl_color"><span>Prepared By</span></td>
                                                    <td><span>' . $Username . '</span></td>
                                                </tr>
                                                <tr>
                                                    <td class="tbl_color" style="width:25px;padding-right:5px;"><span>Version</span></td>
                                                    <td style="width:20px;"><span>' . $version . '</span></td>
                                                    <td class="tbl_color" style="width:20px;padding-right:5px;"><span>Date</span></td>
                                                    <td><span >' . date('Y-m-d') . '</span></td>
                                                    <td class="tbl_color" style="width:10px;padding-right:5px;"><span>Customer</span></td>
                                                    <td><span>' . $CstCompanyAddressLine1 . '</span></td>
                                                    <td class="tbl_color" style="width:60px;padding-right:5px;"><span>Sales Contact</span></td>
                                                    <td><span>' . $SalesContact . '</span></td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>';
                // $elevTbl .= '<tr>
                //                     <td colspan="2">
                //                         <div class="doorImgBox">
                //                             <img src="'.URL('/').'/uploads/files/'.$svgFile.'" class="doorImg">
                //                         </div>
                //                     </td>
                //                 </tr>';
                $elevTbl .= '<tr>';

                $ConfigurableItems = "Streboard";

                $SelectedConfigurableItems = ConfigurableItems::find($configurationItem);
                if ($SelectedConfigurableItems != null) {
                    $ConfigurableItems = $SelectedConfigurableItems->name;
                }


                $GlassType = "";

                $SelectedGlassType =  GlassType::where($configurationDoor,$configurationItem)
                ->where($fireRatingDoor,$FireRatingActualValue)
                ->where("Key",$tt->GlassType)->first();

                if ($SelectedGlassType != null) {
                    $GlassType = $SelectedGlassType->GlassType;
                }

                //                $SelectedFixingDetails = Option::where("configurableitems",$configurationItem)
                //                    ->where("firerating",$tt->FireRating)
                //                    ->where("UnderAttribute",$tt->GlazingSystems)
                //                    ->where("OptionSlug","Fixing_Detail")->first();
                //                if($SelectedFixingDetails != null){
                //                    $FixingDetails = $SelectedFixingDetails->OptionValue;
                //                }

                $GlazingBeadSpecies = "";

                $SelectedGlazingBeadSpecies = LippingSpecies::find($tt->GlazingBeadSpecies);
                if ($SelectedGlazingBeadSpecies != null) {
                    $GlazingBeadSpecies = $SelectedGlazingBeadSpecies->SpeciesName;
                }

                if ($tt->Leaf1VisionPanel == "Yes" || $tt->Leaf2VisionPanel == "Yes") {
                    //                        $elevTbl .= '<td style="width:50%;">
                    //                                    <p class="fanlightframe_t1">23mm Pilkington Pyrostop -60-101</p>
                    //                                    <p class="fanlightframe_t2">-Norseal Vision 60 -25 x 3mm</p>
                    //                                    <p class="fanlightframe_t3">50mm Lg. Steel Pin @ 150mm c/c</p>
                    //                                    <p class="fanlightframe_t4">-Sapele -27 x 24.5mm</p>
                    //                                    <p class="fanlightframe_t5">- Norseal 60B -2mm x 42mm</p>
                    //                                    <p class="fanlightframe_t6">-Rebate:3 x 3mm -Both Frames</p>
                    //                                    <p class="fanlightframe_t7">-Pyroplex Rigid Box -15mm x 4mm -Twin Flipper x 1 -Flat x 1 -CF822</p>
                    //                                    <p class="fanlightframe_t8">-Strebord -FD60 -54mm -Chilt A032067 -Rev.M</p>
                    //                                    <p class="fanlightframe_t9">-Sapele</p>
                    //                                    <div class="doorImgBox">
                    //                                        <img src="'.URL('/').'/uploads/files/fanlightframe.jpg" class="doorImg" style="position:relative;">
                    //                                    </div></td>'


                    $elevTbl .= '<td style="width:20%;font-size: 7px !important;">
                                        <p class="visionpanel_t1">' . $GlassType . '</p>
                                        <p class="visionpanel_t2">' . $tt->glazingBeadsFixingDetail . '</p>
                                        <p class="visionpanel_t3">' . $GlazingBeadSpecies . '<br>' . $tt->GlazingBeadsThickness . ' x ' . $tt->glazingBeadsHeight . 'mm</p>
                                        <p class="visionpanel_t4">' . $ConfigurableItems . '<br>' . $tt->LeafThickness . 'mm</p>
                                        <p class="visionpanel_t5">' . $tt->LeafThickness . '</p>
                                        <div class="doorImgBox">' . $VisionPanelGlazingImage . '</div>
                                    </td>';
                    // $elevTbl .= '<td style="width:50%;"></td>';
                }

                $IsLeafEnabled = $tt->Leaf1VisionPanel == "Yes" ? 'style="width:80%;"' : 'colspan="2"';

                $elevTbl .= '<td ' . $IsLeafEnabled . '>
                    <div class="doorImgBox">
                        <!--<img src="' . URL('/') . '/uploads/files/' . $svgFile . '" class="doorImg">-->
                        <img src="' . $svgFile . '" class="doorImg" style="">
                    </div>
                </td>
                </tr>';

                // if($tt->Leaf1VisionPanel == "Yes" || $tt->Leaf2VisionPanel == "Yes"){
                if (isset($DoorFrameImage)) :
                    // dd(55);

                    $FrameMaterial = 'N/A';

                    if (!empty($tt->FrameMaterial) && !in_array($tt->FrameMaterial, ["MDF", "Softwood", "Hardwood"])) {
                        $SelectedFrameMaterial = LippingSpecies::find($tt->FrameMaterial);
                        if ($SelectedFrameMaterial != null) {
                            $FrameMaterial = $SelectedFrameMaterial->SpeciesName;
                            $FrameMaterial .= "<br>-" . (($SelectedFrameMaterial->MinValue > 0) ? $SelectedFrameMaterial->MinValue . "x" : "") . $SelectedFrameMaterial->MaxValues . " Kg/m3";
                        } else {
                            $SelectedFrameMaterial = LippingSpecies::where("SpeciesName", $tt->FrameMaterial)->first();
                            if ($SelectedFrameMaterial != null) {
                                $FrameMaterial = $SelectedFrameMaterial->SpeciesName;
                                $FrameMaterial .= "<br>-" . (($SelectedFrameMaterial->MinValue > 0) ? $SelectedFrameMaterial->MinValue . "x" : "") . $SelectedFrameMaterial->MaxValues . " Kg/m3";
                            }
                        }
                    }

                    $maxIN = $tt->LeafWidth1 - ($tt->Leaf1VPWidth + $tt->DistanceFromTheEdgeOfDoor);
                    $FrameTypeWidth = $tt->FrameWidth;
                    $FrameTypeHeight = $tt->FrameHeight;
                    if (!empty($tt->FrameType) && $tt->FrameType == 'Rebated_Frame') {
                        $FrameTypeWidth = $tt->RebatedWidth;
                        $FrameTypeHeight = $tt->RebatedHeight;
                    } elseif (!empty($tt->FrameType) && $tt->FrameType == 'Plant_on_Stop') {
                        $FrameTypeWidth = $tt->PlantonStopWidth;
                        $FrameTypeHeight = $tt->PlantonStopHeight;
                    }

                    switch ($tt->DoorsetType) {

                        case "SD":

                            $elevTbl .= '<tr>
                                        <td class="mytabledata_sd" colspan="2" style="">
                                        <p class="frame_dd_t1_sd_' . $tt->FrameType . ' frame_dd_t1_sd_'.$sidelight.'">' . $tt->FrameDepth . 'mm</p>
                                        <p class="frame_dd_t2_sd_' . $tt->FrameType . ' frame_dd_t2_sd_'.$sidelight.'">' . $tt->FrameThickness . 'mm</p>
                                        <p style="'.(($tt->FrameType == 'Rebated_Frame')?'display: none;':'').'" class="frame_dd_t3_sd_' . $tt->FrameType . ' frame_dd_t3_sd_'.$sidelight.'">' . $FrameTypeWidth . 'mm</p>
                                        <p class="frame_dd_t4_sd_' . $tt->FrameType . ' frame_dd_t4_sd_'.$sidelight.'">' . $FrameTypeHeight . 'mm</p>
                                        <p class="frame_dd_t5_sd_' . $tt->FrameType . ' frame_dd_t5_sd_'.$sidelight.'">' . $tt->LeafThickness . '</p>

                                        <!--  <div class="arrow-strat"></div>
                                            <p class="frame_sd_t1">-' . $FrameMaterial . '</p>
                                            <div class="arrow-strat"></div>
                                            <p class="frame_sd_t2">-' . $ConfigurableItems . '<br>-' . $tt->LeafThickness . 'mm</p>
                                            <div class="arrow-strat"></div>
                                            <p class="frame_sd_t3">-' . $GlazingBeadSpecies . '<br>-' . $tt->glazingBeadsWidth . ' x ' . $tt->glazingBeadsHeight . 'mm</p>
                                            <div class="arrow-strat"></div>
                                            <p class="frame_sd_t4">-' . $tt->GlassThickness . 'mm ' . $GlassType . '</p>
                                            <p class="frame_sd_z1">' . $tt->LeafWidth1 . '</p>
                                            <p class="frame_sd_z2">' . $tt->FrameWidth . '</p>
                                            <p class="frame_sd_z3">' . $tt->Leaf1VPWidth . '</p>
                                            <p class="frame_sd_z4">' . $maxIN . '</p>
                                            <p class="frame_sd_z5">' . $tt->DistanceFromTheEdgeOfDoor . '</p> -->

                                            ' . $DoorFrameImage . '</td></tr>';
                            break;

                        case "DD":

                            // dd($tt);
                            $elevTbl .= '<tr>
                                        <td class="mytabledata" colspan="2" style="">

                                        <p class="frame_dd_t1 frame_dd_t1_' . $tt->FrameType.' '.$sidelight.'">' . $tt->FrameDepth . 'mm</p>
                                        <p class="frame_dd_t2 frame_dd_t2_' . $tt->FrameType . ' '.$sidelight.'">' . $tt->FrameThickness . 'mm</p>
                                        <p style="'.(($tt->FrameType == 'Rebated_Frame')?'display: none;':'').'" class="frame_dd_t3 frame_dd_t3_' . $tt->FrameType . ' '.$sidelight.'">' . $FrameTypeWidth . 'mm</p>
                                        <p class="frame_dd_t4 frame_dd_t4_' . $tt->FrameType . ' '.$sidelight.'">' . $FrameTypeHeight . 'mm</p>
                                        <p class="frame_dd_t5 frame_dd_t5_' . $tt->FrameType . ' '.$sidelight.'">' . $tt->LeafThickness . '</p>

                                            <!-- <div class="arrow-strat"></div>  <p class="frame_sd_t1">' . $tt->FrameDepth . '</p>
                                            <p class="frame_sd_t2">' . $tt->FrameThickness . '</p>
                                            <p class="frame_dd_t1">-' . $FrameMaterial . '</p>
                                            <div class="arrow-strat"></div>
                                            <p class="frame_dd_t2 ">-' . $ConfigurableItems . '<br>-' . $tt->LeafThickness . 'mm</p>
                                            <div class="arrow-strat"></div>
                                            <p class="frame_dd_t3 arrow">-' . $GlazingBeadSpecies . '<br>-' . $tt->glazingBeadsWidth . ' x ' . $tt->glazingBeadsHeight . 'mm</p>
                                            <div class="arrow-strat"></div>
                                            <p class="frame_dd_t4">-' . $tt->GlassThickness . 'mm ' . $GlassType . '</p>
                                            <div class="arrow-strat"></div>
                                            <p class="frame_dd_t5">-' . $tt->GAP . 'mm GAP</p>
                                            <p class="frame_sd_DD1">' . $tt->FrameWidth . '</p>
                                            <p class="frame_sd_DD2">' . $tt->DistanceFromTheEdgeOfDoor . '</p>
                                            <p class="frame_sd_DD3">' . $tt->DistanceFromTheEdgeOfDoorforLeaf2 . '</p>
                                            <p class="frame_sd_DD4">' . $tt->Leaf1VPWidth . '</p>
                                            <p class="frame_sd_DD5">' . $tt->LeafWidth1 . '</p>
                                            <p class="frame_sd_DD6">' . $tt->GAP . '</p>  -->

                                            ' . $DoorFrameImage . '</td>
                                            </tr>';
                            break;
                    }
                endif;
                
                // }




                $ExtLinerValue = '';
                if (!empty($tt->ExtLinerValue)) {
                    $ExtLinerValue = $tt->ExtLinerValue;
                }
                
                $ExtLinerThickness = '';
                if (!empty($tt->ExtLinerThickness)) {
                    $ExtLinerThickness = $tt->ExtLinerThickness . "mm";
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

                $SpeciesName = 'N/A';
                if (!empty($tt->LippingSpecies)) {
                    $ls = LippingSpecies::where('id', $tt->LippingSpecies)->first();
                    $SpeciesName = $ls->SpeciesName;
                }

                // $intumescentSealType = 'N/A';
                // $IntumescentLeapingSealArrangement = $tt->IntumescentLeapingSealArrangement;
                // if(!empty($IntumescentLeapingSealArrangement)){
                //     $intum = SettingIntumescentSeals2::select('intumescentSeals')->where('id',$IntumescentLeapingSealArrangement)->first();
                //     $intumescentSealType = $intum->intumescentSeals;
                // }

                $intumescentSealType = 'N/A';
                if (!empty($tt->IntumescentLeapingSealType)) {
                    $intumescentSealType = IntumescentSealType($configurationItem, $tt->IntumescentLeapingSealType);
                }

                $DoorLeafFinish = "N/A";
                if (!empty($tt->DoorLeafFinish)) {
                    $dlf = DoorLeafFinish($configurationItem, $tt->DoorLeafFinish);
                    $DoorLeafFinish = empty($tt->SheenLevel) ? $dlf : $dlf . ' - ' . $tt->SheenLevel . ' Sheen';
                }
                
                $DoorLeafFinishColor = '';
                if (!empty($tt->DoorLeafFinishColor)) {
                    $DoorLeafFinishColor = ' + ' . $tt->DoorLeafFinishColor;
                }
                
                $DoorLeafFacing = "N/A";
                if (!empty($tt->DoorLeafFacing)) {
                    $DoorLeafFacing = DoorLeafFacing($configurationItem, $tt->DoorLeafFacing, $tt->DoorLeafFacingValue);
                }

                $FrameTypeForDoorDetailsTable = 'N/A';
                if (!empty($tt->FrameType)) {
                    $FrameTypeForDoorDetailsTable = FrameType($configurationItem, $tt->FrameType);
                }

                $FrameFinishForDoorDetailsTable = 'N/A';
                if (!empty($tt->FrameFinish)) {
                    $FrameFinishForDoorDetailsTable = FrameFinish($configurationItem, $tt->FrameFinish, $tt->FrameFinishColor);
                }

                $LippingType = "N/A";
                if (!empty($tt->LippingType)) {
                    $SelectedLippingType = Option::where("configurableitems", $configurationItem)
                        ->where("OptionSlug", "lipping_type")
                        ->where("OptionKey", $tt->LippingType)->first();
                    if ($SelectedLippingType != null) {
                        $LippingType = $SelectedLippingType->OptionValue;
                    }
                }


                $GlassTypeForDoorDetailsTable = "N/A";
                if (!empty($tt->GlassType)) {
                    $GlassTypeForDoorDetailsTable = GlassTypeThickness($configurationItem, $FireRatingActualValue, $tt->GlassType, $tt->GlassThickness);
                }
                
                $OPGlassTypeForDoorDetailsTable = "N/A";
                if (!empty($tt->OPGlassType)) {
                    $OPGlassTypeForDoorDetailsTable = OPGlassType($configurationItem, $FireRatingActualValue, $tt->OPGlassType);
                }
                
                $ArchitraveFinishForDoorDetailsTable = "N/A";
                if (!empty($tt->ArchitraveFinish)) {
                    $ArchitraveFinishForDoorDetailsTable = ArchitraveFinish($configurationItem, $tt->ArchitraveFinish, $tt->FrameFinishColor);
                }

                $GlassIntegrity = 'N/A';
                if (!empty($tt->GlassIntegrity)) {
                    $gi = Option::where("configurableitems", $configurationItem)
                        ->where("firerating", $FireRatingActualValue)
                        ->where("OptionSlug", "Glass_Integrity")
                        ->where("OptionKey", $tt->GlassIntegrity)->first();
                    $GlassIntegrity = $gi->OptionValue;
                }
                
                $OPGlazingBeads = 'N/A';
                if (!empty($tt->OPGlazingBeads)) {
                    if($FireRatingActualValue == 'NFR'){
                        $opgb = Option::where("configurableitems", $configurationItem)
                        // ->where("firerating", $FireRatingActualValue)
                        ->where("OptionSlug", "leaf1_glazing_beads")
                        ->where("OptionKey", $tt->OPGlazingBeads)->first();
                    }else{
                        $opgb = Option::where("configurableitems", $configurationItem)
                        ->where("firerating", $FireRatingActualValue)
                        ->where("OptionSlug", "leaf1_glazing_beads")
                        ->where("OptionKey", $tt->OPGlazingBeads)->first();
                    }
                    
                    $OPGlazingBeads = $opgb->OptionValue;
                }
                
                $SLBeadingType = 'N/A';
                if (!empty($tt->BeadingType)) {
                    if($FireRatingActualValue == 'NFR'){
                        $bt = Option::where("configurableitems", $configurationItem)
                        // ->where("firerating", $FireRatingActualValue)
                        ->where("OptionSlug", "leaf1_glazing_beads")
                        ->where("OptionKey", $tt->BeadingType)->first();
                    }else{
                        $bt = Option::where("configurableitems", $configurationItem)
                        ->where("firerating", $FireRatingActualValue)
                        ->where("OptionSlug", "leaf1_glazing_beads")
                        ->where("OptionKey", $tt->BeadingType)->first();
                    }
                    
                    $SLBeadingType = $bt->OptionValue;
                }

                $glazingSystems = 'N/A';
                if (!empty($tt->GlazingSystems)) {
                    // $gs = Option::where('configurableitems', $configurationItem)->where('UnderAttribute', $FireRatingActualValue)->where('OptionKey', $tt->GlazingSystems)
                    //     ->where('OptionSlug', 'leaf1_glazing_systems')->first();
                     $gs = GlazingSystem::join('selected_glazing_system','glazing_system.id','selected_glazing_system.glazingId')->where('selected_glazing_system.userId', Auth::user()->id)->where('glazing_system.'.$configurationDoor,$tt->configurableitems)->where('glazing_system.Key',$tt->GlazingSystems)->first();
                    $glazingSystems = @$gs->GlazingSystem;
                }

                if ($tt->SwingType == 'SA') {
                    $SwingType = 'Single Acting';
                } elseif ($tt->SwingType == 'DA') {
                    $SwingType = 'Double Acting';
                } else {
                    $SwingType = '';
                }

                // Under the row ‘Decorative Groves’ this should show the width x depth. Example 5mm wide x 2mm deep
                if (!empty($tt->DecorativeGroves)) {
                    $GrooveWidth = empty($tt->GrooveWidth) ? 'N/A' : $tt->GrooveWidth . 'mm wide';

                    $GrooveDepth = empty($tt->GrooveDepth) ? 'N/A' : $tt->GrooveDepth . 'mm deep';

                    $DecorativeGroves = empty($tt->GrooveWidth) && empty($tt->GrooveDepth) ? 'N/A' : $GrooveWidth . ' x ' . $GrooveDepth;
                } else {
                    $DecorativeGroves = 'N/A';
                }

                $leafWidth1 = empty($tt->LeafWidth1) ? 'N/A' : $tt->LeafWidth1;

                $leafWidth2 = empty($tt->LeafWidth2) ? 'N/A' : $tt->LeafWidth2;

                $LeafHeight = empty($tt->LeafHeight) ? 'N/A' : $tt->LeafHeight;

                $LeafThickness = empty($tt->LeafThickness) ? 'N/A' : $tt->LeafThickness;

                $FrameDepth = empty($tt->FrameDepth) ? 'N/A' : $tt->FrameDepth;

                if (!empty($tt->IronmongerySet)) {
                    if ($tt->IronmongerySet == 'No') {
                        $IronmongerySet = 'N/A';
                        $IronmongeryData = '';
                    } elseif (!empty($tt->IronmongeryID)) {
                        $IronmongerySet = IronmongerySetName($tt->IronmongeryID);
                        $IronmongeryData = '<tr><th class="tblTitle" >Ironmongery Set</th></tr>'.IronmongerySetData($tt->IronmongeryID);
                    } else {
                        $IronmongerySet = 'N/A';
                        $IronmongeryData = '';
                    }
                } else {
                    $IronmongerySet = 'N/A';
                    $IronmongeryData = '';
                }
                
                $rWdBRating = 'N/A';
                if (!empty($tt->rWdBRating)) {
                    $rWdBRating = $tt->rWdBRating;
                }



                $intumescentSealArrangement = 'N/A';
                if (!empty($tt->IntumescentLeapingSealArrangement)) {
                    $Intumescentseals = SettingIntumescentSeals2::where('id', $tt->IntumescentLeapingSealArrangement)->first();
                    $intumescentSealArrangement =  $Intumescentseals->brand . ' - ' . $Intumescentseals->intumescentSeals;
                }

                $IntumescentLeapingSealColor = 'N/A';
                if (!empty($tt->IntumescentLeapingSealColor)) {
                    $IntumescentLeapingSealColor = $tt->IntumescentLeapingSealColor;
                }

                $ArchitraveMaterial = 'N/A';
                if (!empty($tt->ArchitraveMaterial)) {
                    if ($tt->ArchitraveMaterial == 'Softwood' || $tt->ArchitraveMaterial == 'MDF' || $tt->ArchitraveMaterial == 'Hardwood') {
                        $ArchitraveMaterial = 'N/A';
                    } else {
                        $ls = LippingSpecies::where('id', $tt->ArchitraveMaterial)->first();
                        $ArchitraveMaterial = $ls->SpeciesName;
                    }
                }
                
                $ArchitraveSetQty = 'N/A';
                if (!empty($tt->ArchitraveSetQty)) {
                    $ArchitraveSetQty = $tt->ArchitraveSetQty;
                }
                
                $ArchitraveWidth = 'N/A';
                if (!empty($tt->ArchitraveWidth)) {
                    $ArchitraveWidth = $tt->ArchitraveWidth;
                }
                
                $ArchitraveDepth = 'N/A';
                if (!empty($tt->ArchitraveDepth)) {
                    $ArchitraveDepth = $tt->ArchitraveDepth;
                }
                
                $ArchitraveHeight = 'N/A';
                if (!empty($tt->ArchitraveHeight)) {
                    $ArchitraveHeight = $tt->ArchitraveHeight;
                }



                // Add a new section called 'Side Screen Section' SL1 Glass Type , Beading Type and Glazing Bead Species.
                $sl1glasstype = 'N/A';
                if (!empty($tt->SideLight1GlassType)) {
                    // $op = Option::where(['configurableitems' => $configurationItem, 'UnderAttribute' => $FireRatingActualValue, 'OptionSlug' => 'leaf1_glass_type', 'OptionKey' => $tt->SideLight1GlassType])->first();
                    $op = GlassType::leftJoin('selected_glass_type', function ($join) use ($id): void {
                        $join->on('glass_type.id', '=', 'selected_glass_type.glass_id')
                            ->where('selected_glass_type.editBy', '=', $id);
                    })->where('glass_type.'.$configurationDoor,$tt->configurableitems)->where('glass_type.Key',$tt->SideLight1GlassType)->first();
                    $sl1glasstype = $op->GlassType;
                }

                $beadingtype = 'N/A';
                if (!empty($tt->SideLight2BeadingType)) {
                    $op2 = Option::where(['configurableitems' => $configurationItem, 'UnderAttribute' => $FireRatingActualValue, 'OptionSlug' => 'leaf1_glazing_beads', 'OptionKey' => $tt->SideLight2BeadingType])->first();
                    $beadingtype = ($op2->OptionValue)??"";
                }

                $glazingbeadspecies = 'N/A';
                if (!empty($tt->SL1GlazingBeadSpecies)) {
                    $ls = LippingSpecies::where('id', $tt->SL1GlazingBeadSpecies)->first();
                    if (!empty($ls->SpeciesName)) {
                        $glazingbeadspecies = $ls->SpeciesName;
                    }
                }

                $VPBeadingType = 'N/A';
                if (!empty($tt->GlazingBeads)) {
                    $VPBeadingType = VPBeadingType($configurationItem, 'leaf1_glazing_beads', $tt->GlazingBeads);
                }

                $elevTbl .= '</table>
                        </div>
                        <div id="section-right">
                            <table id="WithBorder" class="tbl2">
                                <tbody>
                                    <tr>
                                        <td class="tbl_color tblTitle" style="font-weight: normal;">SELECT <br>Door Type</td>
                                        <td class="dicription_blank"><b>Type ' . $tt->DoorType . '</b></td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="WithBorder" class="tbl3">
                                <tbody>
                                    <tr>
                                        <th class="tblTitle">General</th>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Door Type</td>
                                        <td class="dicription_blank">' . $tt->DoorType . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Fire Rating</td>
                                        <td class="dicription_blank">' . $fire . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Swing Type</td>
                                        <td class="dicription_blank">' . $SwingType . ' ' . $tt->SwingType . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Open Inwards</td>
                                        <td class="dicription_blank">' . $tt->OpensInwards . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Handing</td>
                                        <td class="dicription_blank">' . $tt->Handing . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Undercut</td>
                                        <td class="dicription_blank">' . $tt->Undercut . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Frame Thickness</td>
                                        <td class="dicription_blank">' . $tt->FrameThickness . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Ironmongery Set</td>
                                        <td class="dicription_blank">' . $IronmongerySet . '</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="WithBorder">
                                <tbody>
                                    <tr>
                                        <th class="tblTitle">Structural Opening & Door Leaf Dimensions</th>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">S.O. Width</td>
                                        <td class="dicription_blank">' . $tt->SOWidth . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">S.O. Height</td>
                                        <td class="dicription_blank">' . $tt->SOHeight . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">S.O. Depth</td>
                                        <td class="dicription_blank">' . $tt->SOWallThick . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Door leaf Facing</td>
                                        <td class="dicription_blank">' . $DoorLeafFacing . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Door leaf Finish</td>
                                        <td class="dicription_blank">' . $DoorLeafFinish . $DoorLeafFinishColor . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Decorative Groves</td>
                                        <td class="dicription_blank">' . $DecorativeGroves . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Door Leaf Width 1</td>
                                        <td class="dicription_blank">' . $leafWidth1 . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Door Leaf Width 2</td>
                                        <td class="dicription_blank">' . $leafWidth2 . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Door Leaf Height</td>
                                        <td class="dicription_blank">' . $LeafHeight . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Door Leaf Thickness</td>
                                        <td class="dicription_blank">' . $LeafThickness . '</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="WithBorder">
                                <tbody>
                                    <tr>
                                        <th class="tblTitle">Frame</th>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Frame Material</td>
                                        <td class="dicription_blank">' . $frameMaterial . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Type</td>
                                        <td class="dicription_blank">' . $FrameTypeForDoorDetailsTable . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Width</td>
                                        <td class="dicription_blank">' . $tt->FrameWidth . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Height</td>
                                        <td class="dicription_blank">' . $tt->FrameHeight . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Depth</td>
                                        <td class="dicription_blank">' . $FrameDepth . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Finish</td>
                                        <td class="dicription_blank">' . $FrameFinishForDoorDetailsTable . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Ext-Liner</td>
                                        <td class="dicription_blank">' . $tt->ExtLiner . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Ext-Liner Size</td>
                                        <td class="dicription_blank">' . $ExtLinerSizeForDoorDetailsTable . '</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="WithBorder">
                                <tbody>
                                    <tr>
                                        <th class="tblTitle">Lipping And Intumescent</th>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Type</td>
                                        <td class="dicription_blank">' . $intumescentSealType . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Lipping Species</td>
                                        <td class="dicription_blank">' . $SpeciesName . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Intumescent Seal Brand</td>
                                        <td class="dicription_blank">' . $intumescentSealArrangement . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Intumescent Seal Color</td>
                                        <td class="dicription_blank">' . $IntumescentLeapingSealColor . '</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="WithBorder">
                                <tbody>
                                    <tr>
                                        <th class="tblTitle">Vision Panel</th>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Glass Integrity</td>
                                        <td class="dicription_blank">' . $GlassIntegrity . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Glass Type + Thickness</td>
                                        <td class="dicription_blank">' . $GlassTypeForDoorDetailsTable . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Glazing Bead Species</td>
                                        <td class="dicription_blank">' . $GlazingBeadSpecies . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Beading Type</td>
                                        <td class="dicription_blank">' . $VPBeadingType . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Glazing System</td>
                                        <td class="dicription_blank">' . $glazingSystems . '</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="WithBorder">
                                <tbody>
                                    <tr>
                                        <th class="tblTitle">Overpanel/Fanlight Section</th>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">OP Glass Type</td>
                                        <td class="dicription_blank">' . $OPGlassTypeForDoorDetailsTable . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">OP Glazing Beads</td>
                                        <td class="dicription_blank">' . $OPGlazingBeads . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">OP Glazing Bead Species</td>
                                        <td class="dicription_blank">' . $OPGlazingBeadSpecies . '</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="WithBorder">
                                <tbody>
                                    <tr>
                                        <th class="tblTitle">Side Screen Section</th>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">SL1 Glass Type</td>
                                        <td class="dicription_blank">' . $sl1glasstype . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Beading Type</td>
                                        <td class="dicription_blank">' . $SLBeadingType . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Glazing Bead Species</td>
                                        <td class="dicription_blank">' . $glazingbeadspecies . '</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="WithBorder">
                                <tbody>
                                    <tr>
                                        <th class="tblTitle">Architrave</th>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Material</td>
                                        <td class="dicription_blank">' . $ArchitraveMaterial . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Finish</td>
                                        <td class="dicription_blank">' . $ArchitraveFinishForDoorDetailsTable . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Set Qty</td>
                                        <td class="dicription_blank">' . $ArchitraveSetQty . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Width</td>
                                        <td class="dicription_blank">' . $ArchitraveWidth . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Depth</td>
                                        <td class="dicription_blank">' . $ArchitraveDepth . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Height</td>
                                        <td class="dicription_blank">' . $ArchitraveHeight . '</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="WithBorder">
                                <tbody>
                                    <tr>
                                        <th class="tblTitle">Accoustics</th>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Rating</td>
                                        <td class="dicription_blank">' . $rWdBRating . '</td>
                                    </tr>
                                </tbody>
                            </table>
                            <table id="WithBorder">
                                <tbody>


                                    '.$IronmongeryData.'
                                </tbody>
                            </table>
                        </div>
                        </div>
                        <div id="footer">
                            <h3><b>Total Doorsets: ' . $countDoorNumber . ',Door No-' . $doorNo . '</b></h3>

                        </div>
                    ';

                if ($PageBreakCount < $TotalItems) {
                    $elevTbl .= '<div class="page-break"></div>';
                }

                $PageBreakCount++;
            }
            
            // echo $elevTbl;die;
            $pdf6 = PDF::loadView('Company.pdf_files.elevationDrawing',['elevTbl' => $elevTbl]);
            $path6 = public_path().'/allpdfFile';
            $fileName6 = $id.'6' . '.' . 'pdf' ;
            // return $pdf6->download('elevation.pdf');
            $pdf6->save($path6 . '/' . $fileName6);

        // End

        $PDFfilename = public_path() . '/allpdfFile' . '/' . $quotaion->QuotationGenerationId . '_' . $version . '.pdf';
        // Merge the PDF File
        $pdf1 = public_path() . '/allpdfFile' . '/' . $fileName1;
        $pdf2 = public_path() . '/allpdfFile' . '/' . $fileName2;
        $pdf3 = public_path() . '/allpdfFile' . '/' . $fileName3;
        $pdf_m_p_r = public_path() . '/allpdfFile' . '/' . $fileName_m_p_r;
        $pdf4_2 = public_path() . '/allpdfFile' . '/' . $fileName4_2;
        $pdf4 = public_path() . '/allpdfFile' . '/' . $fileName4;
        $pdf64 = '';
        if($fileName64 !== '' && $fileName64 !== '0'){
            $pdf64 = public_path().'/allpdfFile'.'/'.$fileName64;
        }
        
        $pdf55 = public_path().'/allpdfFile'.'/'.$fileName5;
        $pdf66 = public_path().'/allpdfFile'.'/'.$fileName6;

        $pdfMerger = PDFMerger::init();
        $pdfMerger->addPDF($pdf1, 'all');
        $pdfMerger->addPDF($pdf2, 'all');
        $pdfMerger->addPDF($pdf3, 'all');
        $pdfMerger->addPDF($pdf_m_p_r, 'all');
        $pdfMerger->addPDF($pdf4_2, 'all');
        $pdfMerger->addPDF($pdf4, 'all');
        if($pdf64 !== '' && $pdf64 !== '0'){
            $pdfMerger->addPDF($pdf64, 'all');
        }
        
        $pdfMerger->addPDF($pdf55, 'all');
        $pdfMerger->addPDF($pdf66, 'all');

        $pdfMerger->merge();
        $pdfMerger->save($PDFfilename);
        $pdfMerger->save(sprintf("%s+'_'+%s.pdf", $quotaion->QuotationGenerationId, $version), 'download');


        $unlinkpath1 = public_path() . '/allpdfFile' . '/' . $fileName1;
        $unlinkpath2 = public_path() . '/allpdfFile' . '/' . $fileName2;
        $unlinkpath3 = public_path() . '/allpdfFile' . '/' . $fileName3;
        $unlinkpath_m_p_r = public_path() . '/allpdfFile' . '/' . $fileName_m_p_r;
        $unlinkpath4_2 = public_path() . '/allpdfFile' . '/' . $fileName4_2;
        $unlinkpath4 = public_path() . '/allpdfFile' . '/' . $fileName4;
        $unlinkpath64 = '';
        if($fileName64 !== '' && $fileName64 !== '0'){
            $unlinkpath64 = public_path().'/allpdfFile'.'/'.$fileName64;
        }
        
        $unlinkpath5 = public_path().'/allpdfFile'.'/'.$fileName5;
        $unlinkpath6 = public_path().'/allpdfFile'.'/'.$fileName6;
        unlink($unlinkpath1);
        unlink($unlinkpath2);
        unlink($unlinkpath3);
        unlink($unlinkpath4_2);
        unlink($unlinkpath4);
        unlink($unlinkpath6);
        unlink($unlinkpath5);
        unlink($unlinkpath_m_p_r);
        unlink($unlinkpath64);
    }
}
