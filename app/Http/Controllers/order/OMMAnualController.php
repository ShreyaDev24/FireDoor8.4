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
use App\Models\SettingCurrency;
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
use App\Models\CoreCertificate;
use App\Models\OverpanelGlassGlazing;
use App\Models\IntumescentSealLeafType;
use App\Models\QuotationSiteDeliveryAddress;
use App\Models\FittingInstructions;
use App\Models\GlassCertificate;

class OMMAnualController extends Controller
{
    public function ommanual($quatationId, $versionID): void
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');
        $id = Auth::user()->id;
        $HideCosts = SettingCurrency::where('UserId', $id)->value('HideCosts');
        // $pdf1 = SettingOMmanualIntro::where('UserId',$id)->first();
        // $pdf2 = SettingOMmanualArchIron::where('UserId',$id)->first();
        // $pdf3 = SettingOMmanualDoorFurniture::where('UserId',$id)->first();
        $pdf1 = SettingOMmanualIntro::first();
        $pdf2 = SettingOMmanualArchIron::first();
        $pdf3 = SettingOMmanualDoorFurniture::first();

        $comapnyDetail = Company::where('UserId', $id)->first();
        $quotaion = Quotation::where('id', $quatationId)->first();
        $configurationItem = 1;
        if (!empty($quotaion->configurableitems)) {
            $configurationItem = $quotaion->configurableitems;
        }

        $configurationItemName = configurationDoor($configurationItem);

        $project = empty($quotaion->ProjectId) ? '' : Project::where('id', $quotaion->ProjectId)->first();

        if (!empty($quotaion->MainContractorId)) {
            $customerContact = CustomerContact::join('customers', 'customers.id', 'customer_contacts.MainContractorId')->where('customers.UserId', $quotaion->MainContractorId)->first();
        } else {
            $customerContact = '';
        }

        $customer = '';
        $CstCompanyAddressLine1 = '';
        if (!empty($customerContact)) {
            $customer = Customer::where(['id' => $customerContact->MainContractorId])->first();
            $CstCompanyAddressLine1 = $customer->CstCompanyAddressLine1;
        }

        $SalesContact = 'N/A';
        if (!empty($quotaion->SalesContact)) {
            $SalesContact = $quotaion->SalesContact;
        }

        $user = empty($quotaion->UserId) ? '' : User::where('id', $quotaion->CompanyUserId)->first();

        // First PDF
        // Introduction PDF
        // $pdfOne = PDF::loadView('Order.pdf_files.introduction', ['pdf1' => $pdf1, 'comapnyDetail' => $comapnyDetail, 'customer' => $customer]);
        // // return $pdfOne->download('file.pdf');
        // $path = public_path() . '/allpdfFile';
        // $fileName1 = $id . '1' . '.' . 'pdf';
        // $pdfOne->save($path . '/' . $fileName1);

        $ProjectsAddress = Project::join('quotation', 'quotation.ProjectId', 'project.id')->where(['quotation.CompanyId' => $quotaion->CompanyId, 'quotation.ProjectId' => $quotaion->ProjectId])->first();

        $QuotationSiteDeliveryAddress = QuotationSiteDeliveryAddress::where('QuotationId', $quatationId)->first();

        $customerDetails = CustomerContact::join('customers','customers.id','=','customer_contacts.MainContractorId')
                ->where('customers.UserId',$quotaion->MainContractorId)->first();

        $data = [
            'project' => $project,
            'companyLogo' => 'storage/company/logo.png',
            'preparedByName' => $quotaion->SalesContact,
            'preparedByCompany' => $comapnyDetail->CompanyName,
            'mainContractor' => $project->main_contractor,

            'project_name' => $project->ProjectName ?? '',
            'projectImageBase64' => $project->projectImageBase64 ?? '',
            'client_contractor' => $customerContact->CstCompanyName,
            'site_address' => (!empty($QuotationSiteDeliveryAddress->Address1) ? $QuotationSiteDeliveryAddress->Address1 : (!empty($ProjectsAddress->AddressLine1) ? $ProjectsAddress->AddressLine1 : '')),
            'fire_door_types' => 'Door cores used '. $configurationItemName .' FD30/FD60',
            'configurationItemName' => $configurationItemName,
            'date' => now()->format('d M Y'),
            'NumberOfDoorSets' => NumberOfDoorSets($versionID,$quatationId),
            'compiled_by' => ' ',
            'cover_image' => public_path('uploads/cover-image.jpg') // Uploaded image path
        ];


        $pdfOne = PDF::loadView('Order.pdf_files.cover', ['data' => $data, 'comapnyDetail' => $comapnyDetail]);
        // return $pdfOne->download('file.pdf');
        $path = public_path() . '/allpdfFile';
        $fileName1 = $id . '1' . '.' . 'pdf';
        $pdfOne->save($path . '/' . $fileName1);

        // Second PDF
        // Architectural Ironmongery
        $pdfTwo = PDF::loadView('Order.pdf_files.Fire-door-submittal', ['data' => $data, 'comapnyDetail' => $comapnyDetail, 'customer' => $customer]);
        // return $pdfTwo->download('file.pdf');
        $path2 = public_path() . '/allpdfFile';
        $fileName2 = $id . '2' . '.' . 'pdf';
        $pdfTwo->save($path2 . '/' . $fileName2);
        //  // Architectural Ironmongery
        // $pdfTwo = PDF::loadView('Order.pdf_files.architecturalIronmongery', ['pdf2' => $pdf2, 'comapnyDetail' => $comapnyDetail, 'customer' => $customer]);
        // // return $pdfTwo->download('file.pdf');
        // $path2 = public_path() . '/allpdfFile';
        // $fileName2 = $id . '2' . '.' . 'pdf';
        // $pdfTwo->save($path2 . '/' . $fileName2);

        // $pdf_mpr = PDF::loadView('Order.pdf_files.m_p_r');
        // $path_mpr = public_path() . '/allpdfFile';
        // $fileName_m_p_r = $id . 'm_p_r' . '.' . 'pdf';
        // $pdf_mpr->save($path_mpr . '/' . $fileName_m_p_r);

        // Third PDF
        // DOOR FURNITURE
        // $pdfThree = PDF::loadView('Order.pdf_files.doorfurniture', ['pdf3' => $pdf3, 'comapnyDetail' => $comapnyDetail, 'customer' => $customer]);
        // // return $pdfThree->download('file.pdf');
        // $path3 = public_path() . '/allpdfFile';
        // $fileName3 = $id . '3' . '.' . 'pdf';
        // $pdfThree->save($path3 . '/' . $fileName3);

        $qv = QuotationVersion::where('id', $versionID)->first();
        $version = $qv->version;

        $shows = Item::join('quotation_version_items', 'items.itemId', 'quotation_version_items.itemID')
            ->join('item_master', 'quotation_version_items.itemmasterID', 'item_master.id')
            ->where('quotation_version_items.version_id', $versionID)->get();
        // Sixth PDF
        // Ironmongery Set
        $itemIronmon = Item::where('QuotationId', $quatationId)->where('VersionId', $versionID)->get();
        $a23 = '';
        $fileName64 = '';
        foreach ($itemIronmon as $tt2) {
            $IronmongeryID = $tt2->IronmongeryID;
            if (!empty($IronmongeryID)) {
                // echo    $IronmongeryID.'<br>';die;
                $adIron = AddIronmongery::where('id', $IronmongeryID)->first();
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

                    $selectIron = SelectedIronmongery::where('id', $adIron->$key)->first();
                    if (!empty($selectIron)) {
                        $ironmongeryInfoId = $selectIron->ironmongery_id . '<br>';
                        $ironmongeryInfo = IronmongeryInfoModel::select('Name', 'Code')->where('id', $ironmongeryInfoId)->first();
                        $a23 .=
                            '<tr>
                                <td>' . $ironmongeryInfo->Code . '</td>
                                <td>' . $ironmongeryInfo->Name . '</td>
                                <td>' . $adIron->$value . '</td>
                            </tr>';
                    }
                }

                $Setname = $adIron->Setname;
                $pdf64 = PDF::loadView('Order.pdf_files.ironmongeryList', ['a23' => $a23, 'comapnyDetail' => $comapnyDetail, 'quotaion' => $quotaion, 'project' => $project, 'version' => $version, 'Setname' => $Setname]);
                // return $pdf3->download('file3.pdf');
                $path64 = public_path() . '/allpdfFile';
                $fileName64 = $id . '64' . '.' . 'pdf';
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
            if ($show->FireRating == 'FD30' || $show->FireRating == 'FD30s') {
                $show->FireRating = 'FD30';
            } elseif ($show->FireRating == 'FD60' || $show->FireRating == 'FD60s') {
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
            $DoorsetPrice = (($show->AdjustPrice) ? floatval($show->AdjustPrice) : floatval($show->DoorsetPrice));
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
                    $SelectedFrameMaterialForDoorDetailsTable = LippingSpecies::where("SpeciesName", $show->FrameMaterial)->where('Status', 1)->first();
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
                $SelectedLippingSpecies = LippingSpecies::where('id', $show->ArchitraveMaterial)->where('Status', 1)->get()->first();
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

            if($quotaion->configurableitems == 4 || $quotaion->configurableitems == 5 || $quotaion->configurableitems == 6 || $quotaion->configurableitems == 9){
                $a .= '<tr>
                            <td>' . $show->plot_ref_no . '</td>
                            <td>' . $show->certification_no . '</td>
                            <td>' . $show->floor . '</td>
                            <td>' . configurationDoor($quotaion->configurableitems) . '</td>
                            <td>' . $show->doorNumber . '</td>
                            <td>' . $DoorDescription . '</td>
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
                            </tr>
                            ';
            }else{

                $a .= '<tr>
                            <td>' . $show->plot_ref_no . '</td>
                            <td>' . $show->certification_no . '</td>
                            <td>' . $show->floor . '</td>
                            <td>' . configurationDoor($quotaion->configurableitems) . '</td>
                            <td>' . $show->doorNumber . '</td>
                            <td>' . $DoorDescription . '</td>
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
                            </tr>
                            ';
            }

            $i++;
            //     }
            // }
        }

        $a .= '
                    <tr>
                        <td class="tbl_bottom" colspan="4"></td>
                        <td class="tbl_bottom">' . $DoorQuantity . '</td>
                        <td class="tbl_bottom" colspan="39"></td>
                    </tr>
                ';


        if($quotaion->configurableitems == 4 || $quotaion->configurableitems == 5 || $quotaion->configurableitems == 6 || $quotaion->configurableitems == 9){
            $pdf5 = PDF::loadView('Order.pdf_files.vicaimapdf2', ['a' => $a, 'comapnyDetail' => $comapnyDetail, 'project' => $project, 'customerContact' => $customerContact, 'version' => $version, 'customer' => $customer, 'HideCosts' => $HideCosts]);
        }else{
            $pdf5 = PDF::loadView('Order.pdf_files.pdf2', ['a' => $a, 'comapnyDetail' => $comapnyDetail, 'project' => $project, 'customerContact' => $customerContact, 'version' => $version, 'customer' => $customer, 'HideCosts' => $HideCosts]);
        }

        // return $pdf4->download('file4.pdf');
        $path5 = public_path() . '/allpdfFile';
        $fileName5 = $id . '5' . '.' . 'pdf';
        $pdf5->save($path5 . '/' . $fileName5);


        // Eight PDF
        // Elevation Drawing
        // Elevation Drawing
        $elevTbl = '';
        // $ed = Item::where('QuotationId',$quatationId)->get();
        $ed = Item::join('item_master', 'item_master.itemID', '=', 'items.itemId')->join("quotation_version_items", function ($join): void {
            $join->on("quotation_version_items.itemID", "=", "items.itemId")
                ->on("quotation_version_items.itemmasterID", "=", "item_master.id");
        })
            ->join('quotation', 'quotation.id', '=', 'items.QuotationId')
            ->where('items.QuotationId', $quatationId)
            ->where('quotation_version_items.version_id', $versionID)->select('items.*', 'item_master.doorNumber', 'quotation.configurableitems')->groupBy('item_master.itemID')->get();

        $TotalItems = count($ed->toArray());

        $PageBreakCount = 1;
        $PageBreakCounts = 1;
        $GlassTypeIds = [];

        foreach ($ed as $tt) {
            $getLeaf = IntumescentSealLeafType::where('id', $tt->IntumescentLeafType)->select('id', 'leaf_type_key', 'door_thickness')->first();
            if ($getLeaf) {
                $fire = $tt->FireRating . ' - ' . $getLeaf->leaf_type_key . ' (' . $getLeaf->door_thickness . 'mm)';
            } else {
                $fire = $tt->FireRating;
            }

            if ($tt->FireRating == 'FD30' || $tt->FireRating == 'FD30s') {
                $FireRatingActualValue = 'FD30';
                $tt->FireRating = 'FD30';
            } elseif ($tt->FireRating == 'FD60' || $tt->FireRating == 'FD60s') {
                $FireRatingActualValue = 'FD60';
                $tt->FireRating = 'FD60';
            } else {
                $FireRatingActualValue  =  $tt->FireRating;
            }

            // sidelight
            if ($tt->FireRating == 'FD30s') {
                $tt->FireRating = 'FD30';
            } elseif ($tt->FireRating == 'FD60s') {
                $tt->FireRating = 'FD60';
            }

            $certMap = [
                4 => [
                    'FD30' => 'FEA/F99112 Revision L',
                    'FD60' => 'FEA/F96103  Revision Q',
                ],
                8 => [
                    'FD30' => 'BMT/CNA/F15159 Revision F',
                    'FD60' => 'WF377027 Revision A',
                ],
                1 => [
                    'FD30' => 'Chilt/A02066 Revision P',
                    'FD60' => 'Chilt/A02067 Revision M',
                ],
                2 => [
                    'FD30' => 'Chilt/A01204 Revision H',
                    'FD60' => 'Chilt/A01205 Part 1 Revision K',
                ],
                7 => [
                    'FD30' => 'FEA98164 Revision P',
                    'FD60' => 'FEA/F02141 Revision M',
                ],
                6 => [
                    'FD30' => 'WF399992 Revision E',
                ],
                5 => [
                    'FD30' => '10133/22-2.R1',
                    'FD60' => '10133/22-2.R1',
                ],
            ];

            $certNo = $certMap[$tt->configurableitems][$FireRatingActualValue] ?? '';

            $configurationDoor = configurationDoor($tt->configurableitems);
            $fireRatingDoor = fireRatingDoor($FireRatingActualValue);

            // dd($tt);
            // $tt->DoorsetType = 'SD';
            if ($tt->DoorsetType == 'leaf_and_a_half') {
                $tt->DoorsetType = 'DD';
            }

            $sidelight = '';
            $frameImageLeftMargin = 10;
            if ($tt->DoorsetType == "SD") {
                $frameImageLeftMargin = 0;
            }

            // $tt->SideLight1 = 'Yes';
            // $tt->SideLight2BeadingType = "Splayed_Flush";

            if (($tt->SideLight1 == 'Yes' || $tt->SideLight2 == 'Yes') && ($tt->SideLight2BeadingType != "Splayed_Bolection")) {
                $sidelight = 'sidelight';
                $frameImageLeftMargin = 192;
                if ($tt->DoorsetType == "SD") {
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

            $species = LippingSpecies::where('id', $tt->FrameMaterial)->first();
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
            $GlazingSystems = GlazingSystemsImage($GlazingSystems, $GlazingBeads, $tt->FireRating);
            $VisionPanelGlazingImageStructure = $GlazingSystems['VisionPanelGlazingImageStructure'];
            $VisionPanelGlazingImageRight = $GlazingSystems['VisionPanelGlazingImageRight'];
            $VisionPanelGlazingImageLeft = $GlazingSystems['VisionPanelGlazingImageLeft'];
            $VisionPanelGlazingImageStructure_Frame = $GlazingSystems['VisionPanelGlazingImageStructure_Frame'];

            if ($tt->Leaf1VisionPanel == "Yes" || $tt->Leaf2VisionPanel == "Yes") {

                $VisionPanelGlazingImage = '<div style=" width:300px;margin: 0 auto;position: relative;">
                        <img style="width: 300px;z-index: 1;" src="' . $VisionPanelGlazingImageStructure . '" alt="">
                        <img style="width: 220px; position: absolute;top: -160px;height: 320px;right:65px;z-index: -1;" src="' . $VisionPanelGlazingImageRight . '" alt="">
                    </div>';
            }


            // echo $VisionPanelGlazingImage;die;
            // dd(\Config::get('constants.base64Images.SplayedBolectionFD60Right'));
            // dd($VisionPanelGlazingImage);
            // $tt->FrameType = "Rebated_Frame";
            $VisionPanelGlazingImageStructure = $VisionPanelGlazingImageStructure_Frame;

            $FrameImageStructureLeft = \Config::get('constants.base64Images.FrameImageStructureLeft');
            $FrameImageStructureRight = \Config::get('constants.base64Images.FrameImageStructureRight');
            $FrameTypeLeft = "";
            $FrameTypeCommon = "";
            if (!empty($tt->FrameType) && $tt->FrameType == "Plant_on_Stop") {
                $FrameTypeLeft = \Config::get('constants.base64Images.FramePlantOnStopLeft');
                $FrameTypeRight = \Config::get('constants.base64Images.FramePlantOnStopRight');
                $FrameTypeCommon = \Config::get('constants.base64Images.FramePlantOnStopCommon');
            } elseif (!empty($tt->FrameType) && ($tt->FrameType == "Rebated_Frame")) {
                $FrameTypeLeft = \Config::get('constants.base64Images.FrameRebatedLeft');
                $FrameTypeRight = \Config::get('constants.base64Images.FrameRebatedRight');
                $FrameTypeCommon = \Config::get('constants.base64Images.FrameRebatedCommon');
            } elseif (!empty($tt->FrameType) && $tt->FrameType == "Scalloped") {

                if ($tt->DoorsetType == "SD") {
                    if (!empty($tt->Handing) && $tt->Handing == "Left") {
                        $FrameTypeLeft = \Config::get('constants.base64Images.ScallopedLeft');
                        $FrameTypeRight = \Config::get('constants.base64Images.ScallopedStraight');
                        $FrameTypeCommon = \Config::get('constants.base64Images.FrameRebatedCommon');
                    } else {
                        $FrameTypeLeft = \Config::get('constants.base64Images.ScallopedStraight');
                        $FrameTypeRight = \Config::get('constants.base64Images.ScallopedRight');
                        $FrameTypeCommon = \Config::get('constants.base64Images.FrameRebatedCommon');
                    }
                } else {
                    $FrameTypeLeft = \Config::get('constants.base64Images.ScallopedLeft');
                    $FrameTypeRight = \Config::get('constants.base64Images.ScallopedRight');
                    $FrameTypeCommon = \Config::get('constants.base64Images.FrameRebatedCommon');
                }
                // $FrameTypeLeft = \Config::get('constants.base64Images.ScallopedLeft');
                // $FrameTypeLeft = \Config::get('constants.base64Images.ScallopedStraight');
                // $FrameTypeRight = \Config::get('constants.base64Images.ScallopedRight');
                // $FrameTypeCommon = \Config::get('constants.base64Images.FrameRebatedCommon');

            }

            $FixedSpaceBlock = \Config::get('constants.base64Images.FixedSpaceBlock');
            $RemainingSpaceBlock = \Config::get('constants.base64Images.RemainingSpaceBlock');
            $FullBlock = \Config::get('constants.base64Images.FullBlock');
            $RemainingSpaceBlockScallopedLeft = \Config::get('constants.base64Images.RemainingSpaceBlockScallopedLeft');
            $RemainingSpaceBlockScallopedRight = \Config::get('constants.base64Images.RemainingSpaceBlockScallopedRight');
            $FixedSpaceBlockScallopedLeft = \Config::get('constants.base64Images.FixedSpaceBlockScallopedLeft');
            $FixedSpaceBlockScallopedRight = \Config::get('constants.base64Images.FixedSpaceBlockScallopedRight');


            $remainingWidth = $tt->LeafWidth1 - ($tt->Leaf1VPWidth + $tt->DistanceFromTheEdgeOfDoor);

            if (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') {
                if ($tt->DistanceFromTheEdgeOfDoor > $remainingWidth) {
                    $FrameImageStructureLeft = $FixedSpaceBlockScallopedRight;
                    $FrameImageStructureRight = $FixedSpaceBlockScallopedLeft;
                } elseif ($tt->DistanceFromTheEdgeOfDoor < $remainingWidth) {


                    if (($show->DoorsetType == "SD")) {
                        if (!empty($tt->Handing) && $tt->Handing == "Left") {
                            $FrameImageStructureLeft = $FixedSpaceBlockScallopedLeft;
                            $FrameImageStructureRight = $FixedSpaceBlock;
                        } else {
                            $FrameImageStructureLeft = $FixedSpaceBlock;
                            $FrameImageStructureRight = $FixedSpaceBlockScallopedRight;
                        }
                    } else {
                        $FrameImageStructureLeft = $FixedSpaceBlockScallopedLeft;
                        $FrameImageStructureRight = $FixedSpaceBlockScallopedRight;
                    }
                } else {
                    $FrameImageStructureLeft = $FixedSpaceBlockScallopedLeft;
                    $FrameImageStructureRight = $FixedSpaceBlockScallopedRight;
                }
            } elseif ($tt->DistanceFromTheEdgeOfDoor > $remainingWidth) {
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

            if (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') {
                if ($tt->DistanceFromTheEdgeOfDoor > $remainingWidth) {
                    $FrameImageStructureLeftLeaf1 = $FixedSpaceBlockScallopedRight;
                    $FrameImageStructureRightLeaf1 = $RemainingSpaceBlock;
                    $FullBlock = $FixedSpaceBlockScallopedLeft;
                } elseif ($tt->DistanceFromTheEdgeOfDoor < $remainingWidth) {
                    $FrameImageStructureLeftLeaf1 = $FixedSpaceBlockScallopedLeft;
                    $FrameImageStructureRightLeaf1 = $FixedSpaceBlock;

                    if (($show->DoorsetType == "SD")) {
                        if (!empty($tt->Handing) && $tt->Handing == "Left") {
                            $FullBlock = $FixedSpaceBlockScallopedLeft;
                        } else {
                            $FullBlock = $FixedSpaceBlockScallopedRight;
                        }
                    } else {
                        $FullBlock = $FixedSpaceBlockScallopedRight;
                    }
                } else {
                    $FrameImageStructureLeftLeaf1 = $FixedSpaceBlockScallopedLeft;
                    $FrameImageStructureRightLeaf1 = $RemainingSpaceBlock;
                    $FullBlock = $FixedSpaceBlockScallopedRight;
                }
            } elseif ($tt->DistanceFromTheEdgeOfDoor > $leaf1RemainingWidth) {
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

            if (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') {
                if ($tt->DistanceFromTheEdgeOfDoorforLeaf2 > $leaf2RemainingWidth) {
                    $FrameImageStructureLeftLeaf2 = $RemainingSpaceBlock;
                    $FrameImageStructureRightLeaf2 = $FixedSpaceBlock;
                } elseif ($tt->DistanceFromTheEdgeOfDoorforLeaf2 < $leaf2RemainingWidth) {
                    $FrameImageStructureLeftLeaf2 = $FixedSpaceBlock;
                    $FrameImageStructureRightLeaf2 = $RemainingSpaceBlockScallopedRight;
                } else {
                    $FrameImageStructureLeftLeaf2 = $RemainingSpaceBlock;
                    $FrameImageStructureRightLeaf2 = $RemainingSpaceBlock;
                }
            } elseif ($tt->DistanceFromTheEdgeOfDoorforLeaf2 > $leaf2RemainingWidth) {
                $FrameImageStructureLeftLeaf2 = $RemainingSpaceBlock;
                $FrameImageStructureRightLeaf2 = $FixedSpaceBlock;
            } elseif ($tt->DistanceFromTheEdgeOfDoorforLeaf2 < $leaf2RemainingWidth) {
                $FrameImageStructureLeftLeaf2 = $FixedSpaceBlock;
                $FrameImageStructureRightLeaf2 = $RemainingSpaceBlock;
            } else {
                $FrameImageStructureLeftLeaf2 = $RemainingSpaceBlock;
                $FrameImageStructureRightLeaf2 = $RemainingSpaceBlock;
            }

            // dd($tt->Leaf2VisionPanel != 'Yes');
            $redstripRightCommonClass = $tt->IntumescentLeapingSealLocation . '_right_strip_' . $tt->DoorsetType;
            $redstripLeftCommonClass = $tt->IntumescentLeapingSealLocation . '_left_strip_' . $tt->DoorsetType;
            switch ($tt->DoorsetType) {
                case "SD":
                    // $DoorFrameImage = Base64Image('FD30SingleDoorsetwithVP');

                    $DoorFrameImage = '<div style="padding:10px 30px;position: relative;margin-left: ' . $frameImageLeftMargin . 'px;height: 200px;">
                                <div style="position: relative;top: 12px;">';

                    if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {

                        if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                            // dd("972");class="'.$redstripLeftCommonClass.'"
                            $DoorFrameImage .= '<div  style="border: 0.5px solid black;
                                    background-color: red;
                                    z-index: 999;
                                    position: absolute;
                                    height: 16px;
                                    width: 6px;
                                    box-shadow: none;
                                    margin-left:' . (
                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ($tt->IntumescentLeapingSealLocation == 'Door' ? '26' : ((empty($tt->Handing) && $tt->Handing == "Left") ? '13' : '14')) : ($tt->IntumescentLeapingSealLocation == 'Door' ? '-3' : '-3')) . 'px;
                                    margin-top: 25px;"></div>';
                        } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                            // dd("985");
                            $DoorFrameImage .= '<div  style="border: 0.5px solid black;
                                    background-color: red;
                                    z-index: 999;
                                    position: absolute;
                                    height: 16px;
                                    width: 6px;
                                    box-shadow: none;
                                    margin-left: ' . (
                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ($tt->IntumescentLeapingSealLocation == 'Door' ? '26' : '13') : ($tt->IntumescentLeapingSealLocation == 'Door' ? '15' : '3')) . 'px;
                                    margin-top: 15px;"></div>

                                    <div style="border: 0.5px solid black;
                                    background-color: red;
                                    z-index: 999;
                                    position: absolute;
                                    height: 16px;
                                    width: 6px;
                                    box-shadow: none;
                                    margin-left:' . (
                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ($tt->IntumescentLeapingSealLocation == 'Door' ? '26' : '13') : ($tt->IntumescentLeapingSealLocation == 'Door' ? '15' : '3')) . 'px;
                                    margin-top: 42px;"></div>';
                        }
                    }


                    if ($sidelight !== "" && $tt->SideLight1 == 'Yes') {

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
                        width: 69px;
                        height: 71px;
                        margin-left: -26px;
                        ' .
                            (($tt->FireRating == 'FD30') ?
                                'top:' . (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '84' :  '133') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '86' : '137')) . 'px;'
                                :
                                'top:' . (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '84' : '135') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '86' : '137')) . 'px;')
                            . '
                        " alt="" src="' . $VisionPanelGlazingImageStructure . '"
                           >


                     </div>
                     <div style="position: absolute;top: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-47' : '5') . 'px;left: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-251' : '-265') . 'px;">
                        <img style="width:' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '53' : '76') . 'px;height: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '208' : ((!empty($tt->FrameType) && $tt->FrameType == 'Rebated_Frame') ? '131' : '196')) . 'px;" alt="" src="' . $FrameTypeCommon . '">
                     </div>
                     <div style="
                        width: 0px;
                        margin: 0 auto;
                        position: relative;
                        margin-left: -79px;
                        top: 2px;
                        left: -4px;
                        transform: rotate(180deg);
                        ">
                        <img style="
                           z-index: 999;
                           position: absolute;
                           margin-top: 86px;
                           width: 69px;
                           height: 71px;
                           margin-left: -8px;
                           ' .
                            (($tt->FireRating == 'FD30') ?
                                'top: ' . (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-108' : '-158') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-111' : '-163')) . 'px;'
                                :
                                'top: ' . (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-108' : '-158') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-111' : '-163')) . 'px;')
                            . '
                           " alt="" src="' . $VisionPanelGlazingImageStructure . '"
                           >

                     </div>
                     <div style="position: absolute;top:' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-46' : '7') . 'px;left:' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-83' : '-87') . 'px;">
                        <img style="width: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '53' : '76') . 'px; height: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '208' : ((!empty($tt->FrameType) && $tt->FrameType == 'Rebated_Frame') ? '131' : '196')) . 'px;" alt="" src="' . $FrameTypeCommon . '">
                     </div>';
                    }


                    $DoorFrameImage .= '<div  style="position: absolute; top: ' . (
                        (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-46' : '7') . 'px;left: -44px;">
                                        <img style="width:' . (
                        (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && !empty($tt->Handing) && $tt->Handing == "Left") ? '77' : '67') . 'px; height:' . (
                        (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '170px' : ($tt->FrameType == 'Rebated_Frame' ? '126px' : '196px')) . ';" src="' . $FrameTypeLeft . '" alt="">
                                    </div>
                                </div>';

                    if ($tt->Leaf1VisionPanel == 'Yes') {

                        $DoorFrameImage .= '<div style="position: relative;top: 12px;">
                                    <img style="
                                        width: ' . (
                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ?  '155' : '165') . 'px;
                                        position: relative;
                                        top: ' . (
                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (((!empty($tt->Handing) && $tt->Handing != "Left")) ? '-17' : '5') : '-17') . 'px;
                                        left: ' . (
                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '26' : '7') . 'px;'
                            .
                            ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (((!empty($tt->Handing) && $tt->Handing != "Left") && ($show->Leaf1VisionPanel == "Yes")) ? 'height: 116px;' : 'height: 65px;') : '') .
                            '" src="' . $FrameImageStructureLeft . '" alt="">
                                    </div>
                                    <div style="width:430px;">
                                        <div style="
                                            width:200px;
                                            margin: 0 auto;
                                            position: relative;
                                            margin-left: -66px;
                                            top: -168px;
                                            left: ' . (
                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '248' : '245') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '237' : '235')) . 'px;
                                            ">

                                            <img style="
                                            z-index: 999;
                                            ' . (($tt->FireRating == 'FD30') ?
                                'width: 125px;
                             margin-top: ' . (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (((!empty($tt->Handing) && $tt->Handing != "Left") && ($show->Leaf1VisionPanel == "Yes")) ? '71' : '118') : '74') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ((!empty($tt->Handing) && $tt->Handing != "Left") ? '62' : '111') : '68')) . 'px;
                                                height:' . (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? '70' : '82') . 'px;' :
                                'width: 108px; margin-left: ' . (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? '-6' : '4') . 'px;
                                                margin-top: ' . (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Handing != "Left") ? '66' : '118')  : '74') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '111' : '69')) . 'px;
                                                height:' . (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? '70' : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '82' : '80'))) . 'px;' . '" src="' . $VisionPanelGlazingImageStructure . '" alt="">



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

                                'width: ' . (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? '117' : '125') . 'px;
                                                 margin-top: ' . (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (((!empty($tt->Handing) && $tt->Handing != "Left") && ($show->Leaf1VisionPanel == "Yes")) ? '135' : '135') : '132') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '124' : '121')) . 'px;
                                                 margin-left: ' . (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '31' : '33') : '34') . 'px;
                                                height: ' . (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? '73' : '84') . 'px;'
                                :
                                'width: 124px; margin-top: ' . (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '135' : '132') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '124.5' : '122.5')) . 'px;
                                                 margin-left: ' . (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? '25.5' : '31.5') . 'px;
                                                height:' . (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? '72' : '82') . 'px;') . '
                                            transform: rotate(180deg);" src="' . $VisionPanelGlazingImageStructure . '" alt="">


                                        </div>
                                    </div>';




                        // if($tt->IntumescentLeapingSealLocation == 'Door'){

                        //     if(in_array($tt->FireRating, ["FD30", "FD30s"])){

                        //         $DoorFrameImage .= '<div  class="'.$redstripCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 16px;width: 6px;box-shadow: none;margin-left: 131px;margin-top: 40px;"></div>';


                        //     }else if(in_array($tt->FireRating, ["FD60", "FD60s"])){

                        //         $DoorFrameImage .= '<div  class="'.$redstripCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 16px;width: 6px;box-shadow: none;margin-left: 131px;margin-top: 30px;"></div>
                        //     <div style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 16px;width: 6px;box-shadow: none;margin-left: 131px;margin-top: 57px;"></div>';

                        //     }
                        // }
                        //    dd((in_array($tt->FireRating, ["FD60", "FD60s"])),'jhgjhg');
                        $DoorFrameImage .= '   <div style="position: relative;position: absolute;top: 7px;left:475px;"><img style="width: ' . (
                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '158' : '170') . 'px;
                                        position: relative;
                                        bottom: ' . (
                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Handing != "Left") ? (($show->Leaf1VisionPanel == "Yes") ? '-22' : '2') :  '1') : (($tt->Leaf1VisionPanel == "Yes") ? '2' : '-22'))  . 'px;
                                        left: ' . (
                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ((empty($tt->Handing) && $tt->Handing == "Left") ? '-32' : '-30') : '-31') . 'px;'
                            .
                            ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Handing == "Left") ? (($show->Leaf1VisionPanel == "Yes") ? ((in_array($tt->FireRating, ["FD60", "FD60s"])) ? 'height:112px;' : 'height:112px;') : 'height:109px;') : 'height:65px;')  : '') .
                            '" src="' . $FrameImageStructureRight . '" alt="">
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

                        $DoorFrameImage .= '<img style="width:' . (
                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '575' : '583') . 'px;
                        position: relative;
                        top: -17px;
                        left: ' . (
                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ((!empty($tt->Handing) && $tt->Handing != "Left") ? '27' : '25') : '3') . 'px;height: 108px;" src="' . $FullBlock . '" alt="">
                                </div>';
                    }

                    $DoorFrameImage .= '
                                <div style="position: relative;position: absolute;top: 7px;left:642px;">';

                    if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {


                        if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                            $DoorFrameImage .= '<div style="border: 0.5px solid black;
                                        background-color: red;
                                        z-index: 999;
                                        position: absolute;
                                        height: 16px;
                                        width: 6px;
                                        box-shadow: none;
                                        margin-left: ' . (
                                !empty($tt->FrameType) && $tt->FrameType == 'Scalloped' ? ($tt->IntumescentLeapingSealLocation == 'Door' ? '-50' : (!empty($tt->Handing) && $tt->Handing != "Left" ? (($show->Leaf1VisionPanel == "Yes") ? '-31.5' : '-5.5') : (($show->Leaf1VisionPanel == "Yes") ? '-34.5' : '-6.5'))) : ($tt->IntumescentLeapingSealLocation == 'Door' ? '-27.5' : '-27.5')) . 'px;
                                        margin-top:' . (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && (!empty($tt->Handing) && $tt->Handing != "Left") && ($show->Leaf1VisionPanel == "Yes") ? '50' : '41') . 'px;"></div>';
                        } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                            // class="'.$redstripRightCommonClass.'"
                            $DoorFrameImage .= '<div  style="border: 0.5px solid black;
                                        background-color: red;
                                        z-index: 999;
                                        position: absolute;
                                        height: 16px;
                                        width: 6px;
                                        box-shadow: none;
                                        margin-left: ' . (
                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ($tt->IntumescentLeapingSealLocation == 'Door' ? '-50' : ((!empty($tt->Handing) && $tt->Handing != "Left") ? (($tt->Handing != "Left") ? '-34.5' : '-5.5') : ((in_array($tt->FireRating, ["FD60", "FD60s"])) ? (($show->Leaf1VisionPanel == "Yes") ? '-35.5' : '-5.5') : '-5.5'))) : ($tt->IntumescentLeapingSealLocation == 'Door' ? '-35.5' : '-25.5')) . 'px;
                                        margin-top: 30px;"></div>

                                        <div style="border: 0.5px solid black;
                                        background-color: red;
                                        z-index: 999;
                                        position: absolute;
                                        height: 16px;
                                        width: 6px;
                                        box-shadow: none;
                                        margin-left: ' . (
                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ($tt->IntumescentLeapingSealLocation == 'Door' ? '-50' : ((!empty($tt->Handing) && $tt->Handing != "Left") ? (($tt->Handing != "Left") ? '-34.5' : '-5.5') : ((in_array($tt->FireRating, ["FD60", "FD60s"])) ? (($show->Leaf1VisionPanel == "Yes") ? '-35.5' : '-5.5') : '-5.5'))) : ($tt->IntumescentLeapingSealLocation == 'Door' ? '-35.5' : '-25.5')) . 'px;
                                        margin-top: 57px;"></div>';
                        }
                    }

                    if (empty($FrameTypeRight)) {
                        $FrameTypeRight = '';
                    }
                    // dd($tt->Handing);
                    if ($tt->FrameType !== null) {
                        $DoorFrameImage .= '<div style="position: absolute;top:  ' . (
                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && $tt->Handing != "Left" && ($show->Leaf1VisionPanel == "Yes")) ? '28' : '21') . 'px;right: ' . (
                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Handing == "Left") ? (($show->Leaf1VisionPanel == "Yes") ? '-40' : '-69') : (($show->Leaf1VisionPanel == "Yes") ? '-32' : '-60')) : '-27') . 'px;">
                                        <img style="width:' . (
                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && !empty($tt->Handing) && $tt->Handing != "Left") ? '77' : '77') . 'px;
                                        margin-top: ' . (
                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-53' : '-1') . 'px; height:' . (
                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '170px' : 'auto') . ';" src="' .  $FrameTypeRight . '" alt="">
                                    </div>
                                </div>
                            ';
                    }
                    if ($sidelight !== "" && $tt->SideLight2 == 'Yes') {

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
                              width: 69px;
                              height: 71px;
                              margin-left: -26px;
                              top: ' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '82' : '132') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '84' : '135')) . 'px;
                              ' .
                            (($tt->FireRating == 'FD30') ?
                                'top:' . (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '82' : '132') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '84' : '135')) . 'px;'
                                :
                                'top:' . (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '82' : '132') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '84' : '135')) . 'px;')
                            . '
                              " alt="" src="' . $VisionPanelGlazingImageStructure . '"
                              >


                        </div>
                        <div style="position: absolute;top:' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-49' : '4') . 'px;left:' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-252' : '-265') . 'px;">
                           <img style="width: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '55' : '77') . 'px;height: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '212' : '194') . 'px;" alt="" src="' . $FrameTypeCommon . '">
                        </div>
                        <div style="
                           width: 0px;
                           margin: 0 auto;
                           position: relative;
                           margin-left: -79px;
                           top: 2px;
                           left: -4px;
                           transform: rotate(180deg);
                           ">
                           <img style="
                              z-index: 999;
                              position: absolute;
                              margin-top: 86px;
                              width: 69px;
                              height: 71px;
                              margin-left: -8px;
                              top: ' . (
                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-106' : '-157') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-109' : '-162')) . 'px;
                              ' .
                            (($tt->FireRating == 'FD30') ?
                                'top: ' . (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-106' : '-157') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-109' : '-162')) . 'px;'
                                :
                                'top: ' . (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-106' : '-157') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-109' : '-162')) . 'px;')
                            . '" alt="" src="' . $VisionPanelGlazingImageStructure . '"
                              >


                        </div>
                        <div style="position: absolute;top: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-48' : '7') . 'px;left: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-84' : '-87') . 'px;">
                           <img style="width: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '55' : '76') . 'px;height: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '208' : ((!empty($tt->FrameType) && $tt->FrameType == 'Rebated_Frame') ? '131' : '196')) . 'px;" alt="" src="' . $FrameTypeCommon . '"
                              >
                        </div>
                       </div>

                      </div>';
                    }

                    break;


                case "DD":
                    // $DoorFrameImage = Base64Image('FD30DoubleDoorsetwithVP');

                    $DoorFrameImage = '<div style="padding: 10px 30px; position: relative;margin-left: ' . $frameImageLeftMargin . 'px;height: 200px;">
                            <div style="position: relative;  top: 12px;">';



                    if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {
                        // margin-left: 5px;
                        if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                            $DoorFrameImage .= '<div class="' . ($tt->FrameType !== null ? $redstripLeftCommonClass : 'DD_NoFrame_left_intubacent') . '"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-top: 18px;"></div>';
                        } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                            $DoorFrameImage .= '<div class="' . $redstripLeftCommonClass . '"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: -12px;margin-top: 10px;"></div>
                                    <div class="' . $redstripLeftCommonClass . '" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: -12px;margin-top: 25px;"></div>';
                        }
                    }



                    // ----------------Left-------------------

                    if ($sidelight !== "" && $tt->SideLight1 == 'Yes') {

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
                                ' .
                            (($tt->FireRating == 'FD30') ?
                                'width: 66px;height: 50px;margin-left: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-14' : '-4') . 'px;top:' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '111' : ($GlazingSystems['GlazingBeadsPadding'] == 0 ? '133' : '135')) . 'px;'
                                :
                                'width: 66px;height: 50px;margin-left: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-14' : '-4') . 'px;top:' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '111' : ($GlazingSystems['GlazingBeadsPadding'] == 0 ? '133' : '135')) . 'px;')
                            . '
                                " alt="" src="' . $VisionPanelGlazingImageStructure . '"
                                    />


                            </div>

                            <div style="position: absolute;top: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-19' : '4') . 'px;left:' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-233' : '-222') . 'px;">';

                        // if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {
                        //     if (in_array($tt->FireRating, ["FD30", "FD30s"])) {

                        //         $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 18px;"></div>';

                        //     } else if (in_array($tt->FireRating, ["FD60", "FD60s"])) {

                        //         $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 10px;"></div>
                        //                 <div class="'.$redstripLeftCommonClass.'_'.$sidelight.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 25px;"></div>';
                        //     }
                        // }


                        $DoorFrameImage .= '<img style="width: 46px;height: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '107' : '117') . 'px;" alt="" src="' . $FrameTypeCommon . '" />
                            </div>

                            <div style="
                            width: 0px;
                            margin: 0 auto;
                            position: relative;
                            margin-left: -79px;
                            top: 2px;
                            left: 13px;
                            transform: rotate(180deg);
                            ">

                                <img style="
                                    z-index: 999;
                                    position: absolute;
                                    margin-top: 86px;
                                    ' .
                            (($tt->FireRating == 'FD30') ?
                                'width: 66px;height: 55px;margin-left: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '2' : '-7') . 'px;top: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-116' : ($GlazingSystems['GlazingBeadsPadding'] == 0 ? '-141' : '-143.5')) . 'px;'
                                :
                                'width: 66px;height: 55px;margin-left: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '2' : '-7') . 'px;top: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-116' : ($GlazingSystems['GlazingBeadsPadding'] == 0 ? '-141' : '-143.5')) . 'px;')
                            . '
                                    " alt="" src="' . $VisionPanelGlazingImageStructure . '"
                                    >





                            </div>

                            <div style="position: absolute;top: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-22' : '4') . 'px;left:' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-74' : '-66') . 'px;">';


                        // if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {


                        //     if (in_array($tt->FireRating, ["FD30", "FD30s"])) {

                        //         $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 18px;"></div>';

                        //     } else if (in_array($tt->FireRating, ["FD60", "FD60s"])) {

                        //         $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 10px;"></div>
                        //                 <div class="'.$redstripRightCommonClass.'_'.$sidelight.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 25px;"></div>';
                        //     }
                        // }
                        $DoorFrameImage .= '<img style="width: 46px;height: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '107' : '117') . 'px;" alt="" src="' . $FrameTypeCommon . '">
                            </div>
                        ';
                    }


                    $DoorFrameImage .= '<div style="position: absolute; top:' . (
                        $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-22' : '4') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-22' : '4')) . 'px; left: -40px;">
                                    <img style="width: ' . (
                        ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '39' : '46')) . 'px; alt="" src="' . $FrameTypeLeft . '">
                                </div>
                            </div>';

                    if ($tt->Leaf1VisionPanel == 'Yes') {

                        $DoorFrameImage .= '
                                <div style="position: relative;  top: 12px;">
                                    <img style="width: 97px;
                                        position: relative;
                                        top: ' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '7' : '-10') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '7' : '-10')) . 'px;
                                        left:-5px " alt="" src="' . $FrameImageStructureLeftLeaf1 . '">
                                </div>
                                <div style="width:265px; position: relative;">
                                    <div style="width: 0px;
                                    margin: 0 auto;
                                    position: relative;
                                    margin-left: -66px;
                                    top: -185px;
                                    left: 258px;">
                                        <img style="z-index: 999;
                                        ' . (($tt->FireRating == 'FD30') ? 'width:' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '67' : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '70' : '70')) . 'px; margin-left:  ' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-100' : '-100') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-106' : '-106')) . 'px;
                                                 height:' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '32' : '42') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '36' : '49')) . 'px;
                                        margin-top: ' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '174' : '134') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '172' : '131')) . 'px;'
                            :
                            'width: ' . (
                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '67' : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '70' : '70')) . 'px;
                                        margin-left:' . (
                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-100.5' : '-103.5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-104.5' : '-103.5')) . 'px;
                                        height:' . (
                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '31' : '42') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '36' : '49')) . 'px;
                                        margin-top: ' . (
                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '174.5' : '134') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '171.5' : '131.5')) . 'px;') . '" alt="" src="' . $VisionPanelGlazingImageStructure . '">



                                        <img style="position: absolute;
                                        top: 120px;
                                        transform: rotate(90deg);
                                        z-index: -1;height: 40px;' . (($tt->FireRating == 'FD30') ? 'left: -99px;width: 17px;' : 'left: -97px;width: 14px;') . '" src="' . $VisionPanelGlazingImageLeft . '" alt="">
                                    </div>

                                    <div style="width: 200px; margin-top: 120px; position: relative; top:-354px; left:220px;">

                                        <img style="
                                        transform: rotate(180deg);
                                        z-index: 999;
                                        ' . (($tt->FireRating == 'FD30') ? 'width:' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '67' : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '70' : '70')) . 'px;
                                        margin-left:' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-64' : '-65') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-62' : '-62')) . 'px;
                                        margin-top:' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '16.5' : '8') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '12.5' : '1')) . 'px;
                                                 height:' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '32' : '41') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '36' : '48')) . 'px;'
                            :
                            'width: ' . (
                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '67' : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '70' : '70')) . 'px;
                                                margin-left:' . (
                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-65' : '-65') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-64' : '-64')) . 'px;
                                                 height:' . (
                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '31' : '42') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '36' : '48')) . 'px;
                                        margin-top: ' . (
                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '18' : '7') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '13' : '0')) . 'px;') . '" alt="" src="' . $VisionPanelGlazingImageStructure . '">



                                    </div>
                                </div>

                                <div style="position: absolute;
                                    top: 8px;
                                    left: 290px;">';

                        if ($tt->IntumescentLeapingSealLocation == 'Door' || $tt->IntumescentLeapingSealLocation == 'Frame') {

                            if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                                $DoorFrameImage .= '<div style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;
                                margin-left:' . (
                                    (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '32' : '53') . 'px;
                                                margin-top: ' . (
                                    (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '31' : '33') . 'px;"></div>';
                            } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                                $DoorFrameImage .= '<div class=""  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left:' . (
                                    (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->IntumescentLeapingSealLocation == 'Frame') ? (($tt->Leaf2VisionPanel == 'Yes') ? '31' : '140') : '132') : '53') . 'px;margin-top: 23px;"></div>
                                            <div style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left:' . (
                                    (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->IntumescentLeapingSealLocation == 'Frame') ? (($tt->Leaf2VisionPanel == 'Yes') ? '31' : '140') : '132') : '53') . 'px;margin-top: 40px;"></div>';
                            }
                        }

                        $DoorFrameImage .= '<img style="width: ' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '75' : '95') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '75' : '95')) . 'px;
                                        position: relative;
                                        top:' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '10' : '5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '10' : '5')) . 'px;
                                        right: 37.5px;" alt="" src="' . $FrameImageStructureRightLeaf1 . '">
                                </div>';
                    } else {
                        $DoorFrameImage .= '<div style="position: relative;top: 12px;">';

                        if ($tt->IntumescentLeapingSealLocation == 'Door' || $tt->IntumescentLeapingSealLocation == 'Frame') {

                            if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                                $DoorFrameImage .= '<div class="' . $redstripRightCommonClass . '"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 310px;margin-top: 18px;"></div>';
                            } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                                $DoorFrameImage .= '<div class="' . $redstripRightCommonClass . '"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 310px;margin-top: 13px;"></div>
                                            <div class="' . $redstripRightCommonClass . '" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 310px;margin-top: 24px;"></div>';
                            }
                        }

                        $DoorFrameImage .= '<img style="
                                    width: ' . (
                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '105' : '323')  . 'px;
                                    position: relative;
                                    top: ' . (
                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '6' : '-10')  . 'px;
                                    left: -5.5px;" src="' . $FullBlock . '" alt="">
                                </div>';
                    }

                    if ($tt->Leaf2VisionPanel == 'Yes') {

                        $DoorFrameImage .= '<div style="position: absolute;
                                        top: 8px;
                                        left: 386px;">
                                        <img style="width:' . (
                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '78' : '95')  . 'px;
                                            position: relative;
                                            top:' . (
                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '9' : '5')  . 'px;
                                            right:' . (
                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '56.5' : '37.5')  . 'px;" alt="" src="' . $FrameImageStructureLeftLeaf2 . '">
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
                                                ' . (($tt->FireRating == 'FD30') ? 'width: ' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '56' : '70') : '70') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '58' : '70') : '70')) . 'px;
                                                 margin-left:' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '-41' : '-5') : '-5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '-45' : '-5') : '-10')) . 'px;
                                                margin-top:' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '134' : '130') : '131') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '131' : '130') : '129')) . 'px;'
                            :
                            'width:' . (
                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '56' : '70') : '70') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '58' : '70') : '70')) . 'px;
                                                 margin-left:' . (
                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '-41' : '-5') : '-5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '-45' : '-5') : '-10')) . 'px;
                                                margin-top:' . (
                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '134' : '130') : '131') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '131' : '130') : '129')) . 'px;') . '" alt="" src="' . $VisionPanelGlazingImageStructure . '">


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
                                                ' . (($tt->FireRating == 'FD30') ? 'width:' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '56' : '67') : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '57' : '70') : '70')) . 'px;
                                                margin-left:' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '-13' : '29') : '32') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '-9' : '29') : '34')) . 'px;
                                                margin-top:' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '70' : '38') : '63.5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '65' : '38') : '57')) . 'px;'
                            :
                            'width:' . (
                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '56' : '67') : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '57' : '70') : '70')) . 'px;
                                                margin-left:' . (
                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '-13' : '29') : '32') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '-9' : '29') : '34')) . 'px;
                                                margin-top:' . (
                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '70' : '38') : '63.5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '65' : '38') : '57')) . 'px;') . '" alt="" src="' . $VisionPanelGlazingImageStructure . '">


                                            <img style="position: absolute;
                                                ' . (($tt->FireRating == 'FD30') ? 'top: 76px;width: 17px;right: 103px;' : 'top: 77px;right: 104px;width: 14px;') . 'z-index: -1;
                                                transform: rotate(270deg);height:40px;" src="' . $VisionPanelGlazingImageLeft . '" alt="">

                                        </div>
                                    </div>
                                    <div style="position: relative;  position: absolute; top: 8px; left: 630px;">
                                        <img style="width: ' . (
                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '136' : '95') . 'px;
                                            position: relative;
                                            top:' . (
                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '9' : '5') . 'px;
                                            right: ' . (
                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '111' : '55') . 'px; alt="" src="' . $FrameImageStructureRightLeaf2 . '">
                                    </div>';
                    } else {
                        $DoorFrameImage .= '<div style="position: absolute;
                                        top: 8px;
                                        left: 386px;">
                                        <img style="width:' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '99' : '319') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '99' : '319')) . 'px;
                                        position: relative;
                                        top:' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '21' : '4') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '21' : '4')) . 'px;;
                                        right:' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '56' : '35') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '56' : '35')) . 'px;" alt="" src="' . $FullBlock . '">
                                    </div>';

                        if (($tt->IntumescentLeapingSealLocation == 'Door' || $tt->IntumescentLeapingSealLocation == 'Frame') && in_array($tt->FireRating, ["FD30", "FD30s"])) {

                            $DoorFrameImage .= '<div class="' . $redstripRightCommonClass . '"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left:' . (($tt->FrameType !== null) ? '640' : '632') . 'px;margin-top:' . (($tt->FrameType !== null) ? '-35' : '-385') . 'px;"></div>';
                        }
                    }

                    $DoorFrameImage .= '
                                    <div style="position: relative;  position: absolute; top: 8px; left: 722px;">';





                    // if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                    //     $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: -52px;margin-top: 31px;"></div>';
                    // } else
                    if (($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') && in_array($tt->FireRating, ["FD60", "FD60s"])) {

                        // class="'.$redstripLeftCommonClass.'"
                        $DoorFrameImage .= '<div   style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left:' . (($tt->Leaf2VisionPanel == 'Yes') ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-64' : '-52') : '-60') . 'px;margin-top: 24px;"></div>
                                            <div  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: ' . (($tt->Leaf2VisionPanel == 'Yes') ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-64' : '-52') : '-60') . 'px;margin-top: 37px;"></div>';
                    }

                    if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {

                        if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                            if ($tt->Leaf2VisionPanel == 'Yes') {
                                $DoorFrameImage .= '<div   style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: ' . (
                                    (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->IntumescentLeapingSealLocation == 'Frame') ? '-64' : '-73') : '-50') . 'px;
                                                margin-top: ' . (
                                    (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '32' : '33') . 'px;"></div>';
                            }
                        } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                            // class="'.$redstripRightCommonClass.'"
                            if ($tt->Leaf2VisionPanel = ! 'Yes') {


                                $DoorFrameImage .= '<div   style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: ' . (
                                    (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->IntumescentLeapingSealLocation == 'Frame') ? '-63' : '-74') : '-50') . 'px;margin-top: 23px;"></div>
                                    <div  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: ' . (
                                    (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->IntumescentLeapingSealLocation == 'Frame') ? '-63' : '-74') : '-50') . 'px;margin-top: 40px;"></div>';
                            }
                        }
                    }

                    if (empty($FrameTypeRight)) {
                        $FrameTypeRight = '';
                    }
                    if ($tt->FrameType !== null) {
                        $DoorFrameImage .= '<div style="position: absolute; top:' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-6' : '18') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-6' : '18')) . 'px;
                                                right:' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '32' : '32') : '20') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes') ? '33' : '33') : '20')) . 'px;">
                                            <img style="width:' . (
                            $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '39' : '46') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '39' : '46')) . 'px;" alt="" src="' . $FrameTypeRight . '">
                                        </div>';
                    }
                    // ----------------Right-------------------

                    if ($sidelight !== "" && $tt->SideLight2 == 'Yes') {

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
                                ' .
                            (($tt->FireRating == 'FD30') ?
                                'width: 66px;height: 50px;margin-left: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-10' : '-5') . 'px;top: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '109' : ($GlazingSystems['GlazingBeadsPadding'] == 0 ? '133' : '135')) . 'px;'
                                :
                                'width: 66px;height: 50px;margin-left: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-10' : '-5') . 'px;top: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '109' : ($GlazingSystems['GlazingBeadsPadding'] == 0 ? '133' : '135')) . 'px;')
                            . '
                                " alt="" src="' . $VisionPanelGlazingImageStructure . '"
                                    />





                            </div>

                            <div style="position: absolute;top:' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-21' : '4') . 'px;left:' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel = ! 'Yes') ? '-456' : '-229') : '-222') . 'px;">';

                        // if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {
                        //     if (in_array($tt->FireRating, ["FD30", "FD30s"])) {

                        //         $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 18px;"></div>';

                        //     } else if (in_array($tt->FireRating, ["FD60", "FD60s"])) {

                        //         $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 10px;"></div>
                        //                 <div class="'.$redstripLeftCommonClass.'_'.$sidelight.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 25px;"></div>';
                        //     }
                        // }


                        $DoorFrameImage .= '<img style="width: 46px;height: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '107' : '117') . 'px;" alt="" src="' . $FrameTypeCommon . '" />
                            </div>

                            <div style="
                                width: 0px;
                                margin: 0 auto;
                                position: relative;
                                margin-left: -79px;
                                top: 6px;
                                left: 13px;
                                transform: rotate(180deg);
                                ">

                                <img style="
                                z-index: 999;
                                margin-top: 86px;
                                position: absolute;
                                ' .
                            (($tt->FireRating == 'FD30') ?
                                ' width: 66px;height: 52px;margin-left:' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '0' : '-7') . 'px;top: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-110' : ($GlazingSystems['GlazingBeadsPadding'] == 0 ? '-134' : '-136.5')) . 'px;'
                                :
                                ' width: 66px;height: 52px;margin-left:' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '0' : '-7') . 'px;top: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-110' : ($GlazingSystems['GlazingBeadsPadding'] == 0 ? '-134' : '-136.5')) . 'px;')
                            . '" alt="" src="' . $VisionPanelGlazingImageStructure . '"
                                    >




                            </div>

                            <div style="position: absolute;top: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-21' : '4') . 'px;left:  ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-73' : '-66') . 'px;">';


                        // if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {


                        //     if (in_array($tt->FireRating, ["FD30", "FD30s"])) {

                        //         $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 18px;"></div>';

                        //     } else if (in_array($tt->FireRating, ["FD60", "FD60s"])) {

                        //         $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 10px;"></div>
                        //                 <div class="'.$redstripRightCommonClass.'_'.$sidelight.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 25px;"></div>';
                        //     }
                        // }
                        $DoorFrameImage .= '<img style="width: 46px; height: ' . ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '107' : '117') . 'px;" alt="" src="' . $FrameTypeCommon . '">
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
                                                <td class="tbl_color" style="width:25px;padding-right:5px;"><span>Revision</span></td>
                                                <td style="width:20px;"><span>' . $version . '</span></td>
                                                <td class="tbl_color" style="width:20px;padding-right:5px;"><span>Date</span></td>
                                                <td><span >' . date('Y-m-d') . '</span></td>
                                                <td class="tbl_color" style="width:10px;padding-right:5px;"><span>Customer</span></td>
                                                <td><span>' . $customer->CstCompanyName . '</span></td>
                                                <td class="tbl_color" style="width:60px;padding-right:5px;"><span>Quote name</span></td>
                                                <td><span>' . $SalesContact . '</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="2">
                                    <table id="WithBorder" class="tbl1 dashedborder">
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
                                                <td class="tbl_color"><span>IO No</span></td>
                                                <td>' . $project->ioNumberOne . '</td>
                                                <td>' . $project->ioNumberTwo . '</td>
                                                <td>' . $project->ioNumberThree . '</td>
                                                <td class="tbl_color"><span>Frame Po</span></td>
                                                <td>' . $project->framePoOne . '</td>
                                                <td>' . $project->framePoTwo . '</td>
                                                <td>' . $project->framePoThree . '</td>
                                                <td class="tbl_color"><span>Ironmongery Po</span></td>
                                                <td>' . $project->ironmongeryPoOne . '</td>
                                                <td>' . $project->ironmongeryPoTwo . '</td>
                                                <td>' . $project->ironmongeryPoThree . '</td>
                                            </tr>
                                            <tr>
                                                <td class="tbl_color"><span>Door Po</span></td>
                                                <td>' . $project->doorPoOne . '</td>
                                                <td>' . $project->doorPoTwo . '</td>
                                                <td>' . $project->doorPoThree . '</td>
                                                <td class="tbl_color"><span>Glass Po</span></td>
                                                <td>' . $project->glassPoOne . '</td>
                                                <td>' . $project->glassPoTwo . '</td>
                                                <td>' . $project->glassPoThree . '</td>
                                                <td class="tbl_color"><span>Intumescent Po</span></td>
                                                <td>' . $project->intumescentPoOne . '</td>
                                                <td>' . $project->intumescentPoTwo . '</td>
                                                <td>' . $project->intumescentPoThree . '</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>';
            $elevTbl .= '<tr>';

            $ConfigurableItems = "Streboard";

            $SelectedConfigurableItems = ConfigurableItems::find($configurationItem);
            if ($SelectedConfigurableItems != null) {
                $ConfigurableItems = $SelectedConfigurableItems->name;
            }


            $GlassType = "";

            $SelectedGlassType =  GlassType::where($configurationDoor, $configurationItem)
                ->where($fireRatingDoor, $FireRatingActualValue)
                ->where("Key", $tt->GlassType)->first();

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
                if ($tt->DoorsetType == "SD" &&  $tt->FrameType == null) {
                    $elevTbl .= '<td style="width:20%;font-size: 7px !important;">
    <p class="visionpanel_t1_sd">' . $GlassType . '</p>
    <p class="visionpanel_t2_sd">' . $tt->glazingBeadsFixingDetail . '</p>
    <p class="visionpanel_t3_sd">' . $GlazingBeadSpecies . '<br>' . $tt->GlazingBeadsThickness . ' x ' . $tt->glazingBeadsHeight . 'mm</p>
    <p class="visionpanel_t4_sd">' . $ConfigurableItems . '<br>' . $tt->LeafThickness . 'mm</p>
    <p class="visionpanel_t5_sd">' . $tt->LeafThickness . '</p>
    <div class="doorImgBox">' . $VisionPanelGlazingImage . '</div>
</td>';
                } else {
                    $elevTbl .= '<td style="width:20%;font-size: 7px !important;">
    <p class="visionpanel_t1">' . $GlassType . '</p>
    <p class="visionpanel_t2">' . $tt->glazingBeadsFixingDetail . '</p>
    <p class="visionpanel_t3">' . $GlazingBeadSpecies . '<br>' . $tt->GlazingBeadsThickness . ' x ' . $tt->glazingBeadsHeight . 'mm</p>
    <p class="visionpanel_t4">' . $ConfigurableItems . '<br>' . $tt->LeafThickness . 'mm</p>
    <p class="visionpanel_t5">' . $tt->LeafThickness . '</p>
    <div class="doorImgBox">' . $VisionPanelGlazingImage . '</div>
</td>';
                }


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
                } elseif (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') {
                    $FrameTypeWidth = $tt->ScallopedWidth;
                    $FrameTypeHeight = $tt->ScallopedHeight;
                }

                switch ($tt->DoorsetType) {

                    case "SD":
                        if ($tt->FrameOnOff != 1) {
                            $elevTbl .= '<tr>
                                    <td class="mytabledata_sd"  colspan="2" style="">
                                    <p class="frame_dd_t1_sd_' . $tt->FrameType . ' frame_dd_t1_sd_' . $sidelight . '" style="margin-left:' . (
                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && (!empty($tt->Handing) && $tt->Handing != "Left")) ?  (($show->Leaf1VisionPanel == "Yes") ? '637' : '660') : '') . 'px;
                                            margin-top:' . (
                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && (!empty($tt->Handing) && $tt->Handing != "Left")) ?  (($show->Leaf1VisionPanel == "Yes") ? '22px !important' : '') : '') . '">' . $tt->FrameDepth . 'mm</p>
                                    <p class="frame_dd_t2_sd_' . $tt->FrameType . ' frame_dd_t2_sd_' . $sidelight . '" style="margin-left:' . (
                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && (!empty($tt->Handing) && $tt->Handing != "Left")) ? (($show->Leaf1VisionPanel == "Yes") ? '626' : '653') : '') . 'px">' . $tt->FrameThickness . 'mm</p>
                                    <p style="' . (($tt->FrameType == 'Rebated_Frame') ? 'display: none;' : '') . '" class="frame_dd_t3_sd_' . $tt->FrameType . ' frame_dd_t3_sd_' . $sidelight . '">' . $FrameTypeWidth . 'mm</p>
                                    <p class="frame_dd_t4_sd_' . $tt->FrameType . ' frame_dd_t4_sd_' . $sidelight . '" style="margin-left:' . (
                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && (!empty($tt->Handing) && $tt->Handing != "Left")) ?   (($show->Leaf1VisionPanel == "Yes") ? '587' : '613') : '') . 'px;
                                            margin-top:' . (
                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && (!empty($tt->Handing) && $tt->Handing != "Left")) ?  (($show->Leaf1VisionPanel == "Yes") ? '158px !important' : '') : '') . ' ">' . $FrameTypeHeight . 'mm</p>
                                    <p class="frame_dd_t5_sd_' . $tt->FrameType . ' frame_dd_t5_sd_' . $sidelight . '" style="margin-left:' . (
                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && (!empty($tt->Handing) && $tt->Handing != "Left")) ?  (($show->Leaf1VisionPanel == "Yes") ? '530' : '550') : '') . 'px">' . $tt->LeafThickness . '</p>';
                        }

                        if (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') {
                            $elevTbl .= '
                                <p class="scalloped-' . $tt->Handing . '-1' . (($show->Leaf1VisionPanel == "Yes" && $tt->Handing != "Left") ? '-VP' : '') . '"  ></p>
                                <p class="scalloped-' . $tt->Handing . '-2' . (($show->Leaf1VisionPanel == "Yes" && $tt->Handing != "Left") ? '-VP' : '') . '" >' . $tt->ScallopedWidth . '</p>
                                <p class="scalloped-' . $tt->Handing . '-3' . (($show->Leaf1VisionPanel == "Yes" && $tt->Handing != "Left") ? '-VP' : '') . '" ></p>';
                        }


                        $elevTbl .= '<!--  <div class="arrow-strat"></div>
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
                        if ($tt->FrameOnOff != 1) {
                            $elevTbl .= '<tr>
                                    <td class="mytabledata" colspan="2" style="">

                                    <p class="frame_dd_t1 frame_dd_t1_' . $tt->FrameType . ' ' . $sidelight . '">' . $tt->FrameDepth . 'mm</p>
                                    <p class="frame_dd_t2 frame_dd_t2_' . $tt->FrameType . ' ' . $sidelight . '">' . $tt->FrameThickness . 'mm</p>
                                    <p style="' . (($tt->FrameType == 'Rebated_Frame') ? 'display: none;' : '') . '" class="frame_dd_t3 frame_dd_t3_' . $tt->FrameType . ' ' . $sidelight . '">' . $FrameTypeWidth . 'mm</p>
                                    <p class="frame_dd_t4 frame_dd_t4_' . $tt->FrameType . ' ' . $sidelight . '">' . $FrameTypeHeight . 'mm</p>
                                    <p class="frame_dd_t5 frame_dd_t5_' . $tt->FrameType . ' ' . $sidelight . '">' . $tt->LeafThickness . '</p>';
                        }

                        $elevTbl .= '<!-- <div class="arrow-strat"></div>  <p class="frame_sd_t1">' . $tt->FrameDepth . '</p>
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

                $GlassTypeIds[] = GlassTypeThickness($configurationItem, $FireRatingActualValue, $tt->GlassType, $tt->GlassThickness,'OMMGlassID');
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
                if ($FireRatingActualValue == 'NFR') {
                    $gi = Option::where("configurableitems", $configurationItem)
                        ->where("OptionSlug", "Glass_Integrity")
                        ->where("OptionKey", $tt->GlassIntegrity)->first();
                } else {
                    $gi = Option::where("configurableitems", $configurationItem)
                        ->where("firerating", $FireRatingActualValue)
                        ->where("OptionSlug", "Glass_Integrity")
                        ->where("OptionKey", $tt->GlassIntegrity)->first();
                }

                $GlassIntegrity = $gi->OptionValue;
            }

            $glazing_beads_word = in_array($configurationItem, [1, 2, 7, 8]) ? 'side_light_glazing_beads' : 'leaf1_glazing_beads';

            $OPGlazingBeads = 'N/A';
            if (!empty($tt->OPGlazingBeads)) {
                $opgb = Option::where("configurableitems", $configurationItem)
                    ->where("firerating", $FireRatingActualValue)
                    ->where("OptionSlug", $glazing_beads_word)
                    ->where("OptionKey", $tt->OPGlazingBeads)
                    ->first();

                $OPGlazingBeads = $opgb ? $opgb->OptionValue : null;
            }

            $SLBeadingType = 'N/A';
            if (!empty($tt->BeadingType)) {
                $bt = Option::where("configurableitems", $configurationItem)
                    ->where("firerating", $FireRatingActualValue)
                    ->where("OptionSlug", $glazing_beads_word)
                    ->where("OptionKey", $tt->BeadingType)
                    ->first();

                $SLBeadingType = $bt ? $bt->OptionValue : null;
            }

            $glazingSystems = 'N/A';
            if (!empty($tt->GlazingSystems)) {
                // $gs = Option::where('configurableitems', $configurationItem)->where('UnderAttribute', $FireRatingActualValue)->where('OptionKey', $tt->GlazingSystems)
                //     ->where('OptionSlug', 'leaf1_glazing_systems')->first();
                $gs = GlazingSystem::join('selected_glazing_system', 'glazing_system.id', 'selected_glazing_system.glazingId')->where('selected_glazing_system.userId', Auth::user()->id)->where('glazing_system.' . $configurationDoor, $tt->configurableitems)->where('glazing_system.Key', $tt->GlazingSystems)->first();
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
            $IronmongerySet = empty($tt->IronmongeryID) ? 'N/A' : IronmongerySetName($tt->IronmongeryID);

            // if (!empty($tt->IronmongerySet)) {
            //     if ($tt->IronmongerySet == 'No') {
            //         $IronmongerySet = 'N/A';
            //         $IronmongeryData .= '';
            //     } else {
            //         if (!empty($tt->IronmongeryID)) {
            //             $IronmongerySet = IronmongerySetName($tt->IronmongeryID);
            //             $IronmongeryDataCountVal = ($IronmongeryDataCount == 1)?'':'';
            //             $IronmongeryData .= '<div>'.$IronmongeryDataCountVal.'
            //             <table id="WithBorder" class="tbl2">'.IronmongerySetData($tt->IronmongeryID).'</table></div>';
            //             $IronmongeryDataCount++;
            //             // $IronmongeryData .= '<div class="page-break"></div>';

            //         } else {
            //             $IronmongerySet = 'N/A';
            //             $IronmongeryData .= '';
            //         }
            //     }
            // } else {
            //     $IronmongerySet = 'N/A';
            //     $IronmongeryData .= '';
            // }
            $rWdBRating = 'N/A';
            if (!empty($tt->rWdBRating)) {
                $rWdBRating = $tt->rWdBRating;
            }



            $intumescentSealArrangement = 'N/A';
            if (!empty($tt->IntumescentLeapingSealArrangement)) {
                $Intumescentseals = SettingIntumescentSeals2::where('id', $tt->IntumescentLeapingSealArrangement)->first();
                if ($Intumescentseals) {
                    $intumescentSealArrangement =  $Intumescentseals->brand . ' - ' . $Intumescentseals->intumescentSeals;
                }
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
                // $op = GlassType::leftJoin('selected_glass_type', function ($join) use ($id) {
                //     $join->on('glass_type.id', '=', 'selected_glass_type.glass_id')
                //         ->where('selected_glass_type.editBy', '=', $id);
                // })->where('glass_type.'.$configurationDoor,$tt->configurableitems)->where('glass_type.Key',$tt->SideLight1GlassType)->first();
                if ($configurationDoor === 'VicaimaDoorCore' || $configurationDoor === 'MMM') {
                    $op = GlassType::leftJoin('selected_glass_type', function ($join) use ($id): void {
                        $join->on('glass_type.id', '=', 'selected_glass_type.glass_id')
                            ->where('selected_glass_type.editBy', '=', $id);
                    })->where('glass_type.' . $configurationDoor, $tt->configurableitems)->where('glass_type.Key', $tt->SideLight1GlassType)->first();
                    $sl1glasstype = $op->GlassType;
                } else {
                    $op = OverpanelGlassGlazing::leftJoin('selected_overpanel_glass_glazing', function ($join) use ($id): void {
                        $join->on('overpanel_glass_glazing.id', '=', 'selected_overpanel_glass_glazing.glass_glazing_id')
                            ->where('selected_overpanel_glass_glazing.editBy', '=', $id);
                    })->where('overpanel_glass_glazing.' . $configurationDoor, $tt->configurableitems)->where('overpanel_glass_glazing.Key', $tt->SideLight1GlassType)->first();
                    $sl1glasstype = $op->GlassType;
                }
            }

            $beadingtype = 'N/A';
            if (!empty($tt->SideLight2BeadingType)) {
                $op2 = Option::where(['configurableitems' => $configurationItem, 'UnderAttribute' => $FireRatingActualValue, 'OptionSlug' => $glazing_beads_word, 'OptionKey' => $tt->SideLight2BeadingType])->first();
                $beadingtype = ($op2->OptionValue) ?? "";
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

            $OPFLHeight = 'N/A';
            if ($tt->Overpanel == 'Overpanel' || $tt->Overpanel == 'Fan_Light') {
                $opHeight = is_numeric($tt->OPHeigth) ? $tt->OPHeigth : 0;
                $beadThickness = is_numeric($tt->OpBeadThickness) ? $tt->OpBeadThickness : 0;
                $gap = is_numeric($tt->GAP) ? $tt->GAP : 0;

                $OPFLHeight = $opHeight - $beadThickness - $beadThickness - $gap;
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
                                    <td class="dicription_grey">Door Core</td>
                                    <td class="dicription_blank">' . $configurationItemName . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Test Certificate Reference </td>
                                    <td class="dicription_blank">' . $certNo . '</td>
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
                                </tr>';
            if ($tt->FrameOnOff != 1) {
                $elevTbl .=  '<tr>
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
                                </tr>';
            }

            $elevTbl .= '<tr>
                                    <td class="dicription_grey">Ironmongery Set</td>
                                    <td class="dicription_blank">' . $IronmongerySet . '</td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Structural Opening & Door Leaf Dimensions</th>
                                </tr>';
            if ($tt->FrameOnOff != 1) {
                $elevTbl .=  '  <tr>
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
                                </tr>';
            }

            $elevTbl .=  '  <tr>
                                    <td class="dicription_grey">Door leaf Facing</td>
                                    <td class="dicription_blank">' . $DoorLeafFacing . '</td>
                                </tr>';
            if ($quotaion->configurableitems == 4) {
                $elevTbl .= '<tr>
                                    <td class="dicription_grey">Leaf Type</td>
                                    <td class="dicription_blank">' . $tt->LeafConstruction . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Product code</td>
                                    <td class="dicription_blank">' . $tt->DoorDimensionsCode . '</td>
                                </tr>';

                if ($tt->DoorsetType != 'SD') {
                    $elevTbl .= '<tr>
                                        <td class="dicription_grey">Product code2/Size </td>
                                        <td class="dicription_blank">' . $tt->DoorDimensionsCode2 . ' (' . $leafWidth2 . '<span style="font-weight: bold; font-size: 1.2em;">×</span>' . $LeafHeight  . ')</td>
                                    </tr>';
                }

                $elevTbl .= '<tr>
                                    <td class="dicription_grey">Decorative Groves</td>
                                    <td class="dicription_blank">' . $tt->groovesNumber . '</td>
                                </tr>';
            } else {
                $elevTbl .=         '<tr>
                                    <td class="dicription_grey">Door leaf Finish</td>
                                    <td class="dicription_blank">' . $DoorLeafFinish . $DoorLeafFinishColor . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Decorative Groves</td>
                                    <td class="dicription_blank">' . $DecorativeGroves . '</td>
                                </tr>';
            }

            $elevTbl .=         '
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
                        </table>';
            if ($tt->FrameOnOff != 1) {
                $elevTbl .=  '<table id="WithBorder">
                                <tbody>
                                    <tr>
                                        <th class="tblTitle">Frame</th>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Four Sided Frame</td>
                                        <td class="dicription_blank">' . (($tt->FourSidedFrame == 1) ? 'Yes' : 'No') . '</td>
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
                            </table>';
            }

            $elevTbl .=  '<table id="WithBorder">
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
                                    <td class="dicription_grey">Lipping Thickness</td>
                                    <td class="dicription_blank">' . $tt->LippingThickness . '</td>
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
                        </table>';
            if ($tt->FrameOnOff != 1) {
                $elevTbl .=  '<table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Overpanel/Fanlight Section</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">OP/FL Glass Type</td>
                                    <td class="dicription_blank">' . $OPGlassTypeForDoorDetailsTable . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">OP/FL Glazing Beads</td>
                                    <td class="dicription_blank">' . $OPGlazingBeads . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">OP/FL Glazing Bead Species</td>
                                    <td class="dicription_blank">' . $OPGlazingBeadSpecies . '</td>
                                </tr>';
                if ($tt->Overpanel == 'Overpanel') {
                    $elevTbl .= '
                                <tr>
                                    <td class="dicription_grey">OP Panel Width</td>
                                    <td class="dicription_blank">' . $tt->FrameWidth . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">OP Panel Height</td>
                                    <td class="dicription_blank">' . $OPFLHeight . '</td>
                                </tr>';
                } else if ($tt->Overpanel == 'Fan_Light') {
                    $elevTbl .= '
                                <tr>
                                    <td class="dicription_grey">FL Width</td>
                                    <td class="dicription_blank">' . $tt->FrameWidth . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">FL Height</td>
                                    <td class="dicription_blank">' . $OPFLHeight . '</td>
                                </tr>';
                }

                $elevTbl .= ' </tbody>
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
                                </tr>';
                if ($tt->SideLight1 == 'Yes') {
                    $elevTbl .= '
                                    <tr>
                                        <td class="dicription_grey">SL1 Width</td>
                                        <td class="dicription_blank">' . $tt->SL1Width . '</td>
                                    </tr>
                                     <tr>
                                        <td class="dicription_grey">SL1 Height</td>
                                        <td class="dicription_blank">' . $tt->SL1Height . '</td>
                                    </tr>
                                    ';
                }
                if ($tt->SideLight2 == 'Yes') {
                    $elevTbl .= '
                                     <tr>
                                        <td class="dicription_grey">SL2 Width</td>
                                        <td class="dicription_blank">' . $tt->SL2Width . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">SL2 Height</td>
                                        <td class="dicription_blank">' . $tt->SL2Height . '</td>
                                    </tr>
                                    ';
                }

                $elevTbl .= '</tbody>
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
                        </table>';
            }

            $elevTbl .=  '<table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Acoustics</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Rating</td>
                                    <td class="dicription_blank">' . $rWdBRating . '</td>
                                </tr>
                            </tbody>
                        </table>';
            if ($tt->FrameOnOff != 1) {
                $elevTbl .=  '<table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Special Feature</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Special Feature Refs</td>
                                    <td class="dicription_blank">' . $tt->SpecialFeatureRefs . '</td>
                                </tr>
                            </tbody>
                        </table>';
            }

            $elevTbl .=  '</div></div>
                    <div id="footer">
                        <h3><b>Total Doorsets: ' . $countDoorNumber . ',Door No-' . $doorNo . '</b></h3>

                    </div>
                ';

            if ($PageBreakCount < $TotalItems || $PageBreakCounts == $TotalItems) {
                $elevTbl .= '<div class="page-break"></div>';
            }

            $PageBreakCount++;
        }
        $Glassids = array_unique($GlassTypeIds);
        $Glassids = array_values($Glassids); // optional reindex

        // echo $elevTbl;die;
        $pdf6 = PDF::loadView('Company.pdf_files.elevationDrawing', ['elevTbl' => $elevTbl]);
        $path6 = public_path() . '/allpdfFile';
        $fileName6 = $id . '6' . '.' . 'pdf';
        // return $pdf6->download('elevation.pdf');
        $pdf6->save($path6 . '/' . $fileName6);

        $DoorsetPrice = Item::join('quotation_version_items', 'items.itemId', 'quotation_version_items.itemID')
            ->join('item_master', 'quotation_version_items.itemmasterID', 'item_master.id')
            ->where(['quotation_version_items.version_id' => $versionID, 'items.VersionId' => $versionID, 'items.QuotationId' => $quatationId]);

        $GetIronmongerySet = $DoorsetPrice->whereNotNull('items.IronmongeryID')->groupby('items.itemId')->get();
        $IronmongeryData = '';
        $PageBreakCount = 1;
        $pdfSpecification = [];
        // dd($GetIronmongerySet,count($GetIronmongerySet));
        if (!empty($GetIronmongerySet)) {
            foreach ($GetIronmongerySet as $ironData) {
                if (!empty($ironData->IronmongeryID)) {
                    $IronmongerySet = IronmongerySetName($ironData->IronmongeryID);
                    $IronmongeryData .= '<div id="headText"><b>Ironmongery Data</b></div>
                        <div><table id="WithBorder" class="tbl2">' . IronmongerySetData($ironData->IronmongeryID) . '</table></div>';

                    $doorNumbers = ItemMaster::where('itemID', $ironData->itemId)->pluck('doorNumber')->toArray();

                    if (!empty($doorNumbers)) {
                        $rows = '';
                        foreach ($doorNumbers as $door) {
                            $rows .= '<tr><td>&bull; ' . e($ironData->DoorType) . ' - ' . e($door) . '</td></tr>';
                        }

                        // Door list with repeating header
                        $IronmongeryData .= '
                            <table class="door-list">
                                <thead>
                                    <tr>
                                        <th>
                                            <div id="headText"><b>Ironmongery Data</b></div>
                                            <div><strong>Door list that this belongs to:</strong></div>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>' . $rows . '</tbody>
                            </table>';
                    }

                    if ($PageBreakCount < count($GetIronmongerySet)) {
                        $IronmongeryData .= '<div class="page-break"></div>';
                    }

                    $pdfSpecification[] = IronmongerySetData($ironData->IronmongeryID,true);

                    $PageBreakCount++;
                }
            }
        }
        $fileName7 = '';
        $pdf77 = '';

        if (!empty($IronmongeryData) && $IronmongeryData !== '0') {
            $pdf7 = PDF::loadView('Company.pdf_files.IronmongeryData', ['IronmongeryData' => $IronmongeryData]);
            $path7 = public_path() . '/allpdfFile';
            $fileName7 = $id . '7' . '.' . 'pdf';
            $pdf7->save($path7 . '/' . $fileName7);

            $pdf77 = public_path() . '/allpdfFile' . '/' . $fileName7;
        }

        // 🔹 certificates fetch
        $certificates = CoreCertificate::join('configurableitems', 'configurableitems.id', 'core_certificates.brand_of_core')
            ->where('core_certificates.user_id', Auth::id())
            ->where('core_certificates.brand_of_core', $quotaion->configurableitems)
            ->select('core_certificates.*', 'configurableitems.name')
            ->get();

        $GlassCertificate = GlassCertificate::join('configurableitems', 'configurableitems.id', 'glass_certificates.brand_of_core')
            ->where('glass_certificates.user_id', Auth::id())
            ->where('glass_certificates.brand_of_core', $quotaion->configurableitems)
            ->wherein('glass_certificates.glass_type_id', $Glassids)
            ->select('glass_certificates.*', 'configurableitems.name')
            ->get();

        // 🔹 final merged PDF name
        $PDFfilename = public_path() . '/allpdfFile' . '/' . $quotaion->QuotationGenerationId . '_' . $version . '.pdf';

        // 🔹 list of PDFs
        $pdf1 = public_path() . '/allpdfFile' . '/' . $fileName1;
        $pdf2 = public_path() . '/allpdfFile' . '/' . $fileName2;
        // $pdf3 = public_path() . '/allpdfFile' . '/' . $fileName3;
        // $pdf_m_p_r = public_path() . '/allpdfFile' . '/' . $fileName_m_p_r;
        $pdf64 = '';
        if ($fileName64 !== '' && $fileName64 !== '0') {
            $pdf64 = public_path() . '/allpdfFile' . '/' . $fileName64;
        }
        $pdf55 = public_path() . '/allpdfFile' . '/' . $fileName5;
        $pdf66 = public_path() . '/allpdfFile' . '/' . $fileName6;

        // 🔹 Merge process
        $pdfMerger = PDFMerger::init();
        $pdfMerger->addPDF($pdf1, 'all');
        $pdfMerger->addPDF($pdf2, 'all');
        // $pdfMerger->addPDF($pdf3, 'all');
        // $pdfMerger->addPDF($pdf_m_p_r, 'all');

        if ($pdf64 !== '' && $pdf64 !== '0' && file_exists($pdf64)) {
            $pdfMerger->addPDF($pdf64, 'all');
        }

        $pdfMerger->addPDF($pdf55, 'all');
        $pdfMerger->addPDF($pdf66, 'all');

        // 🔹 Add certificate PDFs
        if (!empty($certificates)) {
            foreach ($certificates as $cert) {
                $path = public_path($cert->document_path);
                if (file_exists($path)) {
                    $pdfMerger->addPDF($path, 'all');
                }
            }
        }

        if (!empty($GlassCertificate)) {
            foreach ($GlassCertificate as $cert) {
                $path = public_path($cert->document_path);
                if (file_exists($path)) {
                    $pdfMerger->addPDF($path, 'all');
                }
            }
        }

        // 🔹 Add IronmongeryData only if available
        if ($pdf77 !== '' && file_exists($pdf77)) {
            $pdfMerger->addPDF($pdf77, 'all');
        }

        if (!empty($pdfSpecification)) {
            foreach ($pdfSpecification as $pdfSpeci) {
                if (is_array($pdfSpeci)) {
                    foreach ($pdfSpeci as $pdfSpe) {
                        if (!empty($pdfSpe) && file_exists($pdfSpe)) {
                            $pdfMerger->addPDF($pdfSpe, 'all');
                        } else {
                            \Log::warning("PDF not found: " . $pdfSpe);
                        }
                    }
                }
            }
        }

        $fittingInstructions = FittingInstructions::where('user_id',Auth::id())->latest()->get();
        // 🔹 Add fittingInstructions PDFs
        if (!empty($fittingInstructions)) {
            foreach ($fittingInstructions as $cert) {
                $path = public_path($cert->document_path);
                if (file_exists($path)) {
                    $pdfMerger->addPDF($path, 'all');
                }
            }
        }

        $pdfMerger->merge();
        $pdfMerger->save($PDFfilename);
        $pdfMerger->save(sprintf("%s_%s.pdf", $quotaion->QuotationGenerationId, $version), 'download');

        // 🔹 Cleanup temporary files
        $unlinkpath1 = public_path() . '/allpdfFile' . '/' . $fileName1;
        $unlinkpath2 = public_path() . '/allpdfFile' . '/' . $fileName2;
        // $unlinkpath3 = public_path() . '/allpdfFile' . '/' . $fileName3;
        // $unlinkpath_m_p_r = public_path() . '/allpdfFile' . '/' . $fileName_m_p_r;
        // $unlinkpath4_2 = public_path() . '/allpdfFile' . '/' . $fileName4_2;
        // $unlinkpath4 = public_path() . '/allpdfFile' . '/' . $fileName4;
        $unlinkpath64 = '';
        if ($fileName64 !== '' && $fileName64 !== '0') {
            $unlinkpath64 = public_path() . '/allpdfFile' . '/' . $fileName64;
        }
        $unlinkpath5 = public_path() . '/allpdfFile' . '/' . $fileName5;
        $unlinkpath6 = public_path() . '/allpdfFile' . '/' . $fileName6;
        $unlinkpath7 = ($fileName7 !== '') ? public_path() . '/allpdfFile' . '/' . $fileName7 : '';

        // safely unlink files
        foreach ([$unlinkpath1, $unlinkpath2, $unlinkpath5, $unlinkpath6, $unlinkpath64, $unlinkpath7] as $f) {
            if ($f !== '' && file_exists($f)) {
                unlink($f);
            }
        }

    }
}
