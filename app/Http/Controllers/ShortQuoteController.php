<?php

namespace App\Http\Controllers;
use setasign\Fpdi\Tcpdf\Fpdi;
use Illuminate\Support\Facades\Auth;
use PDF;
use PdfMerger;
use DB;
use App\Models\{User,Users,Items,Option,Company,Item,ItemMaster,LippingSpecies,SettingIntumescentSeals2,Project,Quotation,QuotationShipToInformation,QuotationVersion,SettingPDF1,SettingPDF2,SettingPDFfooter,SettingPDFDocument,Customer,CustomerContact,BOMSetting,BOMDetails,SideScreenItem,QuotationContactInformation,SettingCurrency,QuotationSiteDeliveryAddress};


class ShortQuoteController extends Controller
{
    public function shortquote($quatationId, $versionID): mixed
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '4096M');

        $quatationId = $quatationId;

        if(Auth::user()->UserType == 3){
            $users = User::where('UserType',3)->where('id',Auth::user()->id)->first();
            $id = $users->CreatedBy;
        }else{
            $id = Auth::user()->id;
        }

        $comapnyDetail = Company::where('UserId', $id)->first();
        $quotaion = Quotation::where('id', $quatationId)->first();
        $qv = QuotationVersion::where('id', $versionID)->first();

        $contractorName = DB::table('users')
        ->where('id', $quotaion->MainContractorId)
        ->where('UserType', 5)
        ->value('FirstName') ?? '';

        $settings = SettingCurrency::where('UserId', $id)
            ->select('HideCosts', 'companyCode')
            ->first();

        $HideCosts = $settings->HideCosts ?? null;

        $currency = QuotationCurrency($quotaion->Currency);

        // $configurationItem = 1;
        // configurationItem is resolved per door from items.configurableitems below

        $project = empty($quotaion->ProjectId) ? '' : Project::where('id', $quotaion->ProjectId)->first();

        $pdf_footer = SettingPDFfooter::where('UserId', $id)->first();

        // PDF 1 (Introduction PDF)
        $customerContact = !empty($quotaion->MainContractorId)
            ? Users::find($quotaion->MainContractorId)
            : null;

        // Customer & Address
        $customer = null;

        if ($customerContact) {
            $customer = Customer::where('UserId', $quotaion->MainContractorId)->first();
        }

        // Quotation Contact Info
        $contactfirstandlastname = '';

        $quotaion_contact_info = QuotationContactInformation::where('QuotationId', $quatationId)->first();

        if (!empty($quotaion_contact_info?->Contact)) {
            $contactId = explode(',', (string) $quotaion_contact_info->Contact)[0];

            $contactPerson = CustomerContact::find($contactId);

            $contactfirstandlastname = trim(
                ($contactPerson->FirstName ?? '') . ' ' . ($contactPerson->LastName ?? '')
            );
        }

        $user = empty($quotaion->UserId) ? '' : User::where('id', $quotaion->CompanyUserId)->first();

        $pdf1 = SettingPDF1::where('UserId', $id)->first();
        $pdf = PDF::loadView('Company.pdf_files.introductionpdf', ['pdf1' => $pdf1, 'pdf_footer' => $pdf_footer, 'comapnyDetail' => $comapnyDetail, 'quotaion' => $quotaion, 'customerContact' => $customerContact, 'project' => $project, 'user' => $user, 'customer' => $customer, 'contactfirstandlastname' => $contactfirstandlastname, 'contractorName' => $contractorName]);
        $path1 = public_path() . '/allpdfFile';
        $fileName1 = $id . '1' . '.' . 'pdf';
        $pdf->save($path1 . '/' . $fileName1);

        // Quotation Sumary PDF
        $pdf2 = SettingPDF2::where('UserId', $id)->first();

        $totDoorsetType = NumberOfDoorSets($versionID,$quatationId);

        // for getting margin
        $userIds = CompanyUsers();
        $margin = BOMSetting::whereIn('UserId', $userIds)
            ->value('margin_for_material') ?? 0;

        $DoorsetPrice = Items::join('item_master', 'item_master.itemID', '=', 'items.itemId')
            ->where('QuotationId', $quatationId)
            ->where('VersionId', $versionID);

        $totDoorsetPrice = itemAdjustCount($quatationId,$versionID);
        $totIronmongaryPrice = $DoorsetPrice->sum('items.IronmongaryPrice');

        //end changes
        $nonConfigDataPrice = nonConfigurableItem($quatationId,$versionID,$userIds,'',true);
        $nonConfigDataCount = nonConfigurableItem($quatationId,$versionID,$userIds,'','','count');

        $totIronmongerySet = $DoorsetPrice->whereNotNull('items.IronmongeryID')->count();

        $GetIronmongerySet = $DoorsetPrice->whereNotNull('items.IronmongeryID')->groupby('items.itemId')->get();
        $IronmongeryData = '';
        $PageBreakCount = 1;

        if(!empty($GetIronmongerySet)){
            foreach($GetIronmongerySet as $ironData){
                if (!empty($ironData->IronmongeryID)) {
                    $IronmongerySet = IronmongerySetName($ironData->IronmongeryID);
                    $IronmongeryData .= '<div id="headText"><b>Ironmongery Data</b></div>
                    <div><table id="WithBorder" class="tbl2">'. IronmongerySetData($ironData->IronmongeryID) .'</table></div>';

                    $doorNumbers = ItemMaster::where('itemID', $ironData->itemId)->pluck('doorNumber')->toArray();

                    if (!empty($doorNumbers)) {
                        $rows = '';
                        foreach ($doorNumbers as $door) {
                            $rows .= '<tr><td>&bull; '. e($ironData->DoorType) .' - '. e($door) .'</td></tr>';
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
                            <tbody>'. $rows .'</tbody>
                        </table>';
                    }

                    if ($PageBreakCount < count($GetIronmongerySet)) {
                        $IronmongeryData .= '<div class="page-break"></div>';
                    }


                    $PageBreakCount++;
                }
            }
        }

        $SideScreenData = SideScreenItem::join('side_screen_item_master', 'side_screen_items.id', 'side_screen_item_master.ScreenId')->where(['side_screen_items.QuotationId' => $quatationId,'side_screen_items.VersionId' => $versionID])
                    ->select('side_screen_items.FireRating','side_screen_items.VersionId', 'side_screen_items.ScreenType' ,'side_screen_items.SOWidth', 'side_screen_items.SOHeight', 'side_screen_items.SODepth','side_screen_items.GlazingType', 'side_screen_items.ScreenPrice', 'side_screen_items.id', 'side_screen_item_master.screenNumber', 'side_screen_item_master.floor', 'side_screen_item_master.id as screenMasterid');

        $screenData = $SideScreenData->sum(\DB::raw('IF(side_screen_items.ScreenAdjustPrice IS NOT NULL AND side_screen_items.ScreenAdjustPrice != 0, side_screen_items.ScreenAdjustPrice, side_screen_items.ScreenPrice)'));
        $ScreenSetQty = $SideScreenData->count();

        $screenDataprice = round(floatval($screenData),2);

        $QSTI = QuotationShipToInformation::where('QuotationId', $quatationId)->first();

        // Calculate Transportation Cost
        $transportationCost = 0;
        if (!empty($QSTI->ActualNoOfDeliveries) && !empty($QSTI->Costperdelivery)) {
            $transportationCost = floatval($QSTI->ActualNoOfDeliveries) * floatval($QSTI->Costperdelivery);
        }
        $nettot = itemAdjustCount($quatationId, $versionID) + (float) $totIronmongaryPrice + (float) $nonConfigDataPrice + (float) $screenDataprice + (float) $transportationCost;

        $pdf2 = PDF::loadView('Company.pdf_files.quotationsummarypdf', ['comapnyDetail' => $comapnyDetail, 'project' => $project, 'quotaion' => $quotaion, 'pdf2' => $pdf2, 'pdf_footer' => $pdf_footer, 'totDoorsetType' => $totDoorsetType, 'totIronmongerySet' => $totIronmongerySet, 'totDoorsetPrice' => $totDoorsetPrice, 'totIronmongaryPrice' => $totIronmongaryPrice, 'nonConfigDataPrice' => $nonConfigDataPrice, 'nettot' => $nettot, 'QSTI' => $QSTI, 'customerContact' => $customerContact, 'customer' => $customer, 'user' => $user, 'nonConfigDataCount' => $nonConfigDataCount, 'contractorName' => $contractorName, 'ScreenSetQty' => $ScreenSetQty, 'screenDataprice' => $screenDataprice, 'currency' => $currency, 'transportationCost' => $transportationCost, 'qv' => $qv]);

        // return $pdf2->download('file2.pdf');
        $path2 = public_path() . '/allpdfFile';
        $fileName2 = $id . '2' . '.' . 'pdf';
        $pdf2->save($path2 . '/' . $fileName2);

        $QuotationShipToInformation = QuotationShipToInformation::where('QuotationId', $quatationId)->first();
        $QuotationSiteDelivery = QuotationSiteDeliveryAddress::where('QuotationId', $quatationId)->get();
        $ProjectsAddress = Project::join('quotation', 'quotation.ProjectId', 'project.id')->where(['quotation.CompanyId' => $quotaion->CompanyId, 'quotation.ProjectId' => $quotaion->ProjectId])->first();

        // Prepare site delivery data for template
        $siteDeliveries = [];
        foreach($QuotationSiteDelivery as $delivery) {
            $siteDeliveries[] = [
                'Address1' => $delivery->Address1 ?: ($ProjectsAddress->AddressLine1 ?? ''),
                'Address2' => $delivery->Address2 ?: ($ProjectsAddress->AddressLine2 ?? ''),
                'Country' => $delivery->Country ?: ($ProjectsAddress->Country ?? ''),
                'City' => $delivery->City ?: ($ProjectsAddress->City ?? ''),
                'PostalCode' => $delivery->PostalCode ?: ($ProjectsAddress->PostalCode ?? ''),
            ];
        }

        // Map wagon preference to label
        $wagonOptions = [
            '40ft_Artic_Curtain_Side' => '40ft Artic Curtain Side',
            '26t_Rigid_Curtain_Side' => '26t Rigid Curtain Side',
            '18t_Rigid_Curtain_Side' => '18t Rigid Curtain Side',
            '7.5t_Curtain_Side' => '7.5t Curtain Side',
            '1t_Box_Van_Curtain_Side' => '1t Box Van Curtain Side',
            'Pallet' => 'Pallet',
            'Moffit_Off_Load' => 'Moffit Off Load',
            'Tail_Lift_Offload' => 'Tail Lift Offload',
            'Retractable_Roof' => 'Retractable Roof (Crane Off Load)'
        ];
        $wagonPreferenceLabel = $wagonOptions[$QuotationShipToInformation->WagonPreference ?? ''] ?? '';

        $pdf2_1 = PDF::loadView('Company.short_quote_files.quotationDeliverypdf', [
            'comapnyDetail' => $comapnyDetail,
            'project' => $project,
            'quotation' => $quotaion,
            'siteDeliveries' => $siteDeliveries,
            'shipToInfo' => $QuotationShipToInformation,
            'wagonPreferenceLabel' => $wagonPreferenceLabel
        ]);

        // return $pdf2->download('file2.pdf');
        $path2_1 = public_path() . '/allpdfFile';
        $fileName2_1 = $id . '2_1' . '.' . 'pdf';
        $pdf2_1->save($path2_1 . '/' . $fileName2_1);


        $margin = BOMSetting::wherein('UserId',$userIds)->value('margin_for_material');


        // Details Door List PDF

        $version = $qv->version;

        if($project->QualificationsStatus == 1){
            $MoreInformation = $project->MoreInformation;
            $pdf2_2 = PDF::loadView('Company.pdf_files.MoreInformation', ['comapnyDetail' => $comapnyDetail,'version' => $version,'project' => $project,'MoreInformation' => $MoreInformation,'quotaion' => $quotaion]);

            // return $pdf2->download('file2.pdf');
            $path2_2 = public_path() . '/allpdfFile';
            $fileName2_2 = $id . '2_2' . '.' . 'pdf';
            $pdf2_2->save($path2_2 . '/' . $fileName2_2);
        }

        $a2 = '';
       $shows = Item::join('quotation_version_items', 'items.itemId', '=', 'quotation_version_items.itemID')
            ->join('item_master', 'quotation_version_items.itemmasterID', '=', 'item_master.id')
            ->where('quotation_version_items.version_id', $versionID)
            ->select(
                'items.*',
                'item_master.doorNumber'
            )
            ->get();

        // SUMMARY - Prepare clean data for detail door list
        $showDatas = $shows->groupBy('itemId');
        $doorSummaryRows = [];

        foreach ($showDatas as $itemId => $rows) {
            $qty = $rows->count();
            $show = $rows->first();
            $DoorDescription = '';
            if (!empty($show->DoorsetType)) {
                $DoorDescription = DoorDescription($show->DoorsetType);
            }

            $basePrice = floatval($show->DoorsetPrice ?? 0);
            $leafDelta = floatval($show->leaf_price_delta ?? 0);

            $doorPrice = $leafDelta
                        ? $leafDelta
                        : (
                            $show->AdjustPrice
                                ? floatval($show->AdjustPrice)
                                : $basePrice
                        );

            $ironPrice = floatval($show->IronmongaryPrice);
            $doorPriceTotal = round($doorPrice, 2);
            $ironPriceTotal = round($ironPrice, 2);
            $total = round(($doorPrice + $ironPrice), 2) * $qty;

            $doorSummaryRows[] = [
                'doorNumber' => $show->doorNumber,
                'DoorDescription' => $DoorDescription,
                'DoorType' => $show->DoorType,
                'qty' => $qty,
                'doorPrice' => $doorPriceTotal,
                'ironPrice' => $ironPriceTotal,
                'total' => $total,
            ];
        }

        $pdf3 = PDF::loadView('Company.short_quote_files.detaildoorlist', [
            'doorSummaryRows' => $doorSummaryRows,
            'comapnyDetail' => $comapnyDetail,
            'quotaion' => $quotaion,
            'project' => $project,
            'version' => $version,
            'HideCosts' => $HideCosts,
            'currency' => $currency
        ]);
        $path3 = public_path() . '/allpdfFile';
        $fileName3 = $id . '3' . '.' . 'pdf';
        $pdf3->save($path3 . '/' . $fileName3);

        //PDF 2 - Prepare clean door data structure
        $CompanyId = get_company_id($id)->id;
        $doorRows = [];
        $doorRowsCustom = [];
        $SumDoorsetPrice = 0;
        $SumDoorsetPriceCustom = 0;
        $SumIronmongaryPrice = 0;
        $SumIronmongaryPriceCustom = 0;

        foreach ($shows as $show) {
            $configurationItem = $show->configurableitems ?: 1;
            $fireRate = $show->FireRating;
            if($show->FireRating == 'FD30' || $show->FireRating == 'FD30s'){
                $show->FireRating = 'FD30';
            }elseif($show->FireRating == 'FD60' || $show->FireRating == 'FD60s'){
                $show->FireRating = 'FD60';
            }

            $basePrice = floatval($show->DoorsetPrice);
            $leafDelta = floatval($show->leaf_price_delta ?? 0);

            if($show->configurableitems == 4 || $show->configurableitems == 5 || $show->configurableitems == 6 || $show->configurableitems == 9){
                $DoorsetPrice = $leafDelta
                            ? $leafDelta
                            : (
                                $show->AdjustPrice
                                    ? floatval($show->AdjustPrice)
                                    : $basePrice
                            );
                $IronmongaryPrice = $show->IronmongaryPrice;
                $totalpriceperdoorset = $DoorsetPrice + $IronmongaryPrice;
                $SumDoorsetPrice += $DoorsetPrice;
                $SumIronmongaryPrice += $IronmongaryPrice;
            }else{
                $DoorsetPriceCustom = $leafDelta
                            ? $leafDelta
                            : (
                                $show->AdjustPrice
                                    ? floatval($show->AdjustPrice)  
                                    : $basePrice
                            );
                $IronmongaryPriceCustom = $show->IronmongaryPrice;
                $totalpriceperdoorsetCustom = $DoorsetPriceCustom + $IronmongaryPriceCustom;
                $SumDoorsetPriceCustom += $DoorsetPriceCustom;
                $SumIronmongaryPriceCustom += $IronmongaryPriceCustom;
            }

            // Process Door Leaf Finish
            $DoorLeafFinish = "N/A";
            if (!empty($show->DoorLeafFinish)) {
                $dlf = DoorLeafFinish($configurationItem, $show->DoorLeafFinish);
                $DoorLeafFinish = empty($show->SheenLevel) ? $dlf : $dlf . ' - ' . $show->SheenLevel . ' Sheen';
            }
            $DoorLeafFinishColor = '';
            if (!empty($show->DoorLeafFinishColor)) {
                $DoorLeafFinishColor = ' + ' . $show->DoorLeafFinishColor;
            }

            // Process Door Leaf Facing
            $DoorLeafFacing = "N/A";
            if (!empty($show->DoorLeafFacing)) {
                $DoorLeafFacing = DoorLeafFacing($configurationItem, $show->DoorLeafFacing, $show->DoorLeafFacingValue);
            }

            // Process Lipping
            $LippingType = $LippingSpecies = $LippingThickness = '';
            if (!empty($show->LippingType)) {
                $SelectedLippingType = Option::where("configurableitems", $configurationItem)
                    ->where("OptionSlug", "lipping_type")
                    ->where("OptionKey", $show->LippingType)->first();
                if ($SelectedLippingType != null) {
                    $LippingType = $SelectedLippingType->OptionValue;
                }
            }
            if (!empty($show->LippingSpecies)) {
                $SelectedLippingSpecies = LippingSpecies::find($show->LippingSpecies);
                if ($SelectedLippingSpecies != null) {
                    $LippingSpecies = $SelectedLippingSpecies->SpeciesName;
                }
            }
            if (!empty($show->LippingThickness)) {
                $LippingThickness = $show->LippingThickness;
            }

            $Lipping = $this->formatLipping($LippingType, $LippingSpecies, $LippingThickness);

            // Process Vision Panels
            $Leaf1VisionPanel = $this->formatVisionPanel($show->Leaf1VisionPanel, $show->Leaf1VisionPanelShape, $show->VisionPanelQuantity, 'Leaf1', $show);
            $Leaf2VisionPanel = $this->formatVisionPanel($show->Leaf2VisionPanel, $show->Leaf1VisionPanelShape, $show->Leaf2VisionPanelQuantity, 'Leaf2', $show);

            // Process Glass Type
            $GlassTypeForDoorDetailsTable = "N/A";
            if (!empty($show->GlassType)) {
                $GlassTypeForDoorDetailsTable = GlassTypeThickness($configurationItem, $show->FireRating, $show->GlassType, $show->GlassThickness);
            }

            // Process Overpanel
            $OverpanelForDoorDetailsTable = "N/A";
            if ($show->Overpanel == "Fan_Light" || $show->Overpanel == "Overpanel") {
                $OverpanelForDoorDetailsTable = $show->OPHeigth . "x" . $show->OPWidth;
            }

            // Process OP Glass Type
            $OPGlassTypeForDoorDetailsTable = "N/A";
            if (!empty($show->OPGlassType)) {
                $OPGlassTypeForDoorDetailsTable = OPGlassType($configurationItem, $show->FireRating, $show->OPGlassType);
            }

            // Process Frame Material
            $FrameMaterialForDoorDetailsTable = $this->getFrameMaterial($show->FrameMaterial);

            // Process Frame Type
            $FrameTypeForDoorDetailsTable = 'N/A';
            if (!empty($show->FrameType)) {
                $FrameTypeForDoorDetailsTable = FrameType($configurationItem, $show->FrameType);
            }

            // Process Frame Size
            $FrameSizeForDoorDetailsTable = $this->getFrameSize($show);

            // Process Ironmongery Set
            $IronmongerySet = 'N/A';
            if (!empty($show->IronmongerySet)) {
                if ($show->IronmongerySet == 'No') {
                    $IronmongerySet = 'N/A';
                } elseif (!empty($show->IronmongeryID)) {
                    $IronmongerySet = IronmongerySetName($show->IronmongeryID);
                }
            }

            // Process Frame Finish
            $FrameFinishForDoorDetailsTable = 'N/A';
            if (!empty($show->FrameFinish)) {
                $FrameFinishForDoorDetailsTable = FrameFinish($configurationItem, $show->FrameFinish, $show->FrameFinishColor);
            }

            // Process External Liner
            $ExtLiner = $show->ExtLiner ?? 'N/A';
            $ExtLinerSizeForDoorDetailsTable = $this->getExtLinerSize($show);

            // Process Intumescent Seal
            $intumescentSeal = 'N/A';
            if (!empty($show->IntumescentLeapingSealArrangement)) {
                $intum = SettingIntumescentSeals2::select('brand', 'intumescentSeals')->where('id', $show->IntumescentLeapingSealArrangement)->first();
                if($intum){
                    $intumescentSeal = $intum->brand . ' - ' . $intum->intumescentSeals;
                }
            }

            // Process Door Description
            $DoorDescription = 'N/A';
            if (!empty($show->DoorsetType)) {
                $DoorDescription = DoorDescription($show->DoorsetType);
            }

            // Process Architrave
            $ArchitraveMaterialForDoorDetailsTable = $ArchitraveTypeForDoorDetailsTable = $ArchitraveSizeForDoorDetailsTable = $ArchitraveFinishForDoorDetailsTable = "N/A";
            $ArchitraveSetQty = 'N/A';
            if ($show->Architrave == "Yes") {
                $SelectedLippingSpecies = LippingSpecies::where('id', $show->ArchitraveMaterial)->get()->first();
                $ArchitraveMaterialForDoorDetailsTable = $SelectedLippingSpecies->SpeciesName;
                $ArchitraveTypeForDoorDetailsTable = $show->ArchitraveType;
                $ArchitraveSizeForDoorDetailsTable = $show->ArchitraveWidth . "x" . $show->ArchitraveHeight . "mm";
                if (!empty($show->ArchitraveFinish)) {
                    $ArchitraveFinishForDoorDetailsTable = ArchitraveFinish($configurationItem, $show->ArchitraveFinish, $show->FrameFinishColor);
                }
                if (!empty($show->ArchitraveSetQty)) {
                    $ArchitraveSetQty = $show->ArchitraveSetQty;
                }
            }

            // Process Other Fields
            $rWdBRating = $show->rWdBRating ?? 'N/A';
            $COC = $show->COC ?? 'None';
            $SpecialFeatureRefs = $show->SpecialFeatureRefs ?? 'None';

            // Process Side Screens
            $SideScreen1 = $this->formatSideScreen($show->SL1Width ?? '', $show->SL1Height ?? '');
            $SideScreen2 = $this->formatSideScreen($show->SL2Width ?? '', $show->SL2Height ?? '');

            // Add door row data
            if($show->configurableitems == 4 || $show->configurableitems == 5 || $show->configurableitems == 6 || $show->configurableitems == 9){
                $doorRows[] = [
                    'plot_ref_no' => $show->plot_ref_no,
                    'certification_no' => $show->certification_no,
                    'floor' => $show->floor,
                    'configuration' => configurationDoor($show->configurableitems),
                    'doorNumber' => $show->doorNumber,
                    'DoorDescription' => $DoorDescription,
                    'SOHeight' => $show->SOHeight,
                    'SOWidth' => $show->SOWidth,
                    'SOWallThick' => $show->SOWallThick,
                    'DoorType' => $show->DoorType,
                    'LeafConstruction' => $show->LeafConstruction ?? '',
                    'DoorLeafFinish' => $DoorLeafFinish . $DoorLeafFinishColor,
                    'DoorLeafFacing' => $DoorLeafFacing,
                    'DoorDimensionsCode' => $show->DoorDimensionsCode ?? '',
                    'Lipping' => $Lipping,
                    'LeafWidth1' => $show->LeafWidth1,
                    'LeafWidth2' => $show->LeafWidth2,
                    'LeafHeight' => $show->LeafHeight,
                    'LeafThickness' => $show->LeafThickness,
                    'Undercut' => $show->Undercut,
                    'Handing' => $show->Handing,
                    'OpensInwards' => $show->OpensInwards,
                    'Leaf1VisionPanel' => $Leaf1VisionPanel,
                    'Leaf2VisionPanel' => $Leaf2VisionPanel,
                    'GlassType' => $GlassTypeForDoorDetailsTable,
                    'Overpanel' => $OverpanelForDoorDetailsTable,
                    'OPGlassType' => $OPGlassTypeForDoorDetailsTable,
                    'SideScreen1' => $SideScreen1,
                    'SideScreen2' => $SideScreen2,
                    'FrameMaterial' => $FrameMaterialForDoorDetailsTable,
                    'FrameType' => $FrameTypeForDoorDetailsTable,
                    'FrameSize' => $FrameSizeForDoorDetailsTable,
                    'FrameFinish' => $FrameFinishForDoorDetailsTable,
                    'ExtLiner' => $ExtLiner,
                    'ExtLinerSize' => $ExtLinerSizeForDoorDetailsTable,
                    'intumescentSeal' => $intumescentSeal,
                    'ArchitraveMaterial' => $ArchitraveMaterialForDoorDetailsTable,
                    'ArchitraveType' => $ArchitraveTypeForDoorDetailsTable,
                    'ArchitraveSize' => $ArchitraveSizeForDoorDetailsTable,
                    'ArchitraveFinish' => $ArchitraveFinishForDoorDetailsTable,
                    'ArchitraveSetQty' => $ArchitraveSetQty,
                    'IronmongerySet' => $IronmongerySet,
                    'rWdBRating' => $rWdBRating,
                    'fireRate' => $fireRate,
                    'COC' => $COC,
                    'SpecialFeatureRefs' => $SpecialFeatureRefs,
                    'DoorsetPrice' => $DoorsetPrice,
                    'IronmongaryPrice' => $IronmongaryPrice,
                    'totalPrice' => $totalpriceperdoorset,
                ];
            }else{
                $doorRowsCustom[] = [
                    'plot_ref_no' => $show->plot_ref_no,
                    'certification_no' => $show->certification_no,
                    'floor' => $show->floor,
                    'configuration' => configurationDoor($show->configurableitems),
                    'doorNumber' => $show->doorNumber,
                    'DoorDescription' => $DoorDescription,
                    'SOHeight' => $show->SOHeight,
                    'SOWidth' => $show->SOWidth,
                    'SOWallThick' => $show->SOWallThick,
                    'DoorType' => $show->DoorType,
                    'LeafConstruction' => $show->LeafConstruction ?? '',
                    'DoorLeafFinish' => $DoorLeafFinish . $DoorLeafFinishColor,
                    'DoorLeafFacing' => $DoorLeafFacing,
                    'DoorDimensionsCode' => $show->DoorDimensionsCode ?? '',
                    'Lipping' => $Lipping,
                    'LeafWidth1' => $show->LeafWidth1,
                    'LeafWidth2' => $show->LeafWidth2,
                    'LeafHeight' => $show->LeafHeight,
                    'LeafThickness' => $show->LeafThickness,
                    'Undercut' => $show->Undercut,
                    'Handing' => $show->Handing,
                    'OpensInwards' => $show->OpensInwards,
                    'Leaf1VisionPanel' => $Leaf1VisionPanel,
                    'Leaf2VisionPanel' => $Leaf2VisionPanel,
                    'GlassType' => $GlassTypeForDoorDetailsTable,
                    'Overpanel' => $OverpanelForDoorDetailsTable,
                    'OPGlassType' => $OPGlassTypeForDoorDetailsTable,
                    'SideScreen1' => $SideScreen1,
                    'SideScreen2' => $SideScreen2,
                    'FrameMaterial' => $FrameMaterialForDoorDetailsTable,
                    'FrameType' => $FrameTypeForDoorDetailsTable,
                    'FrameSize' => $FrameSizeForDoorDetailsTable,
                    'FrameFinish' => $FrameFinishForDoorDetailsTable,
                    'ExtLiner' => $ExtLiner,
                    'ExtLinerSize' => $ExtLinerSizeForDoorDetailsTable,
                    'intumescentSeal' => $intumescentSeal,
                    'ArchitraveMaterial' => $ArchitraveMaterialForDoorDetailsTable,
                    'ArchitraveType' => $ArchitraveTypeForDoorDetailsTable,
                    'ArchitraveSize' => $ArchitraveSizeForDoorDetailsTable,
                    'ArchitraveFinish' => $ArchitraveFinishForDoorDetailsTable,
                    'ArchitraveSetQty' => $ArchitraveSetQty,
                    'IronmongerySet' => $IronmongerySet,
                    'rWdBRating' => $rWdBRating,
                    'fireRate' => $fireRate,
                    'COC' => $COC,
                    'SpecialFeatureRefs' => $SpecialFeatureRefs,
                    'DoorsetPrice' => $DoorsetPriceCustom,
                    'IronmongaryPrice' => $IronmongaryPriceCustom,
                    'totalPrice' => $totalpriceperdoorsetCustom,
                ];
            }


        }

        $Alltotalpriceperdoorset = $SumDoorsetPrice + $SumIronmongaryPrice;
        $AlltotalpriceperdoorsetCustom = $SumDoorsetPriceCustom + $SumIronmongaryPriceCustom;
        $doorQuantity = count($doorRows);
        $doorQuantityCustom = count($doorRowsCustom);

        if(isset($doorRows)){
            $pdf4 = PDF::loadView('Company.short_quote_files.vicimapdf2', [
                'doorRows' => $doorRows,
                'comapnyDetail' => $comapnyDetail,
                'project' => $project,
                'customerContact' => $customerContact,
                'version' => $version,
                'customer' => $customer,
                'HideCosts' => $HideCosts,
                'doorQuantity' => $doorQuantity,
                'SumDoorsetPrice' => $SumDoorsetPrice,
                'SumIronmongaryPrice' => $SumIronmongaryPrice,
                'Alltotalpriceperdoorset' => $Alltotalpriceperdoorset,
                'currency' => $currency,
                'doorType' => 'vicaima'
            ]);
        }

        $path4 = public_path() . '/allpdfFile';
        $fileName4 = $id . '4' . '.' . 'pdf';
        $pdf4->save($path4 . '/' . $fileName4);

        if(isset($doorRowsCustom)){
            $pdf4_custom = PDF::loadView('Company.short_quote_files.pdf2', [
                'doorRows' => $doorRowsCustom,
                'comapnyDetail' => $comapnyDetail,
                'project' => $project,
                'customerContact' => $customerContact,
                'version' => $version,
                'customer' => $customer,
                'HideCosts' => $HideCosts,
                'doorQuantity' => $doorQuantityCustom,
                'SumDoorsetPrice' => $SumDoorsetPriceCustom,
                'SumIronmongaryPrice' => $SumIronmongaryPriceCustom,
                'Alltotalpriceperdoorset' => $AlltotalpriceperdoorsetCustom,
                'currency' => $currency,
                'doorType' => 'default'
            ]);
        }

        $path4_custom = public_path() . '/allpdfFile';
        $fileName4_custom = $id . '4_custom' . '.' . 'pdf';
        $pdf4_custom->save($path4_custom . '/' . $fileName4_custom);

        // Document PDF
        $pdf_document = SettingPDFDocument::where('UserId', $id)->first();
        $pdf5 = PDF::loadView('Company.pdf_files.documentpdf', ['pdf_document' => $pdf_document]);
        $path5 = public_path() . '/allpdfFile';
        $fileName5 = $id . '5' . '.' . 'pdf';
        $pdf5->save($path5 . '/' . $fileName5);



        $PDFfilename = public_path() . '/allpdfFile' . '/' . $quotaion->QuotationGenerationId . '_' . $version . '.pdf';


                 $pdfFiles = [
                    public_path() . '/allpdfFile' . '/' . $fileName1,
                    public_path() . '/allpdfFile' . '/' . $fileName2,
                    public_path() . '/allpdfFile' . '/' . $fileName2_1,
                    public_path() . '/allpdfFile' . '/' . $fileName3,
                    public_path() . '/allpdfFile' . '/' . $fileName4,
                    public_path() . '/allpdfFile' . '/' . $fileName4_custom,
                ];




            // Merge the PDF files using PDFMerger
            $pdfMerger = PDFMerger::init();
            foreach ($pdfFiles as $pdfFile) {
                $pdfMerger->addPDF($pdfFile, 'all');
            }

            $mergedFilePath = public_path() . '/allpdfFile/' . $quotaion->QuotationGenerationId . '_' . $version . '.pdf';
            $pdfMerger->merge();
            $pdfMerger->save($mergedFilePath);
            $pdfMerger->save(public_path().'/quotationFiles'.'/'.$quotaion->QuotationGenerationId.'_'.$version.'.pdf');

            $quo = Quotation::find($quatationId);
            $quo->quotTag = 1;
            $quo->save();

            // new code
            $pdf = new Fpdi();
            // Source file path
            $pageCount = $pdf->setSourceFile($mergedFilePath);
            // Disable auto page break to avoid pushing content down
            $pdf->SetAutoPageBreak(false);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);
            $pdf->SetMargins(0, 0, 0); // Full-page use

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $tplId = $pdf->importPage($pageNo);
                $pdf->AddPage();
                $pdf->useTemplate($tplId, ['adjustPageSize' => true]);
                // Add page number without adding new page or overlapping content
                $pdf->SetFont('Helvetica', '', 9);
                $pdf->SetTextColor(0, 0, 0);
                // ✅ Position footer safely above bottom margin
                $pdf->SetXY(0, -12); // or -15 if needed
                $pdf->Cell(0, 10, 'Page ' . $pageNo . ' / ' . $pageCount, 0, 0, 'C');
            }
            // Save the final PDF
            $pdf->Output($PDFfilename, 'F');
            $pdf->Output($quotaion->QuotationGenerationId . '_' . $version . '.pdf', 'D');
            // Source file path
            $pageCount = $pdf->setSourceFile($mergedFilePath);

            // Disable auto page break to avoid pushing content down
            $pdf->SetAutoPageBreak(false);
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(false);

            $pdf->SetMargins(0, 0, 0); // Full-page use

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {
                $tplId = $pdf->importPage($pageNo);
                $pdf->AddPage();
                $pdf->useTemplate($tplId, ['adjustPageSize' => true]);

                // Add page number without adding new page or overlapping content
                $pdf->SetFont('Helvetica', '', 9);
                $pdf->SetTextColor(0, 0, 0);

                // ✅ Position footer safely above bottom margin
                $pdf->SetXY(0, -12); // or -15 if needed
                $pdf->Cell(0, 10, 'Page ' . $pageNo . ' / ' . $pageCount, 0, 0, 'C');
            }

            // Save the final PDF
            $pdf->Output($PDFfilename, 'F');
            $pdf->Output($quotaion->QuotationGenerationId . '_' . $version . '.pdf', 'D');
            // end code

            $quo = Quotation::find($quatationId);
            $quo->quotTag = 1;
            $quo->save();

            foreach ($pdfFiles as $unlinkPath) {
                unlink($unlinkPath);
            }
    }

    /**
     * Helper method to format lipping information
     */
    private function formatLipping($lippingType, $lippingSpecies, $lippingThickness)
    {
        if (!empty($lippingType) && !empty($lippingSpecies) && !empty($lippingThickness)) {
            return $lippingType . ' - ' . $lippingSpecies . ' - ' . $lippingThickness . 'mm';
        } elseif (empty($lippingType) && !empty($lippingSpecies) && !empty($lippingThickness)) {
            return 'N/A - ' . $lippingSpecies . ' - ' . $lippingThickness . 'mm';
        } elseif (!empty($lippingType) && empty($lippingSpecies) && !empty($lippingThickness)) {
            return $lippingType . ' - N/A - ' . $lippingThickness . 'mm';
        } elseif (empty($lippingType) && empty($lippingSpecies) && !empty($lippingThickness)) {
            return 'N/A - N/A - ' . $lippingThickness . 'mm';
        } elseif (!empty($lippingType) && !empty($lippingSpecies) && empty($lippingThickness)) {
            return $lippingType . ' - ' . $lippingSpecies . ' - N/A';
        } elseif (!empty($lippingType) && empty($lippingSpecies) && empty($lippingThickness)) {
            return $lippingType . ' - N/A - N/A';
        } elseif (empty($lippingType) && !empty($lippingSpecies) && empty($lippingThickness)) {
            return 'N/A - ' . $lippingSpecies . ' - N/A';
        }
        return 'N/A';
    }

    /**
     * Helper method to format vision panel information
     */
    private function formatVisionPanel($isYes, $shape, $quantity, $leafType, $show)
    {
        if ($isYes != "Yes") {
            return "N/A";
        }

        $width = ($leafType === 'Leaf1') ? ($show->Leaf1VPWidth ?? '') : ($show->Leaf2VPWidth ?? '');
        $heights = [];

        for ($i = 1; $i <= intval($quantity); $i++) {
            $heightField = ($leafType === 'Leaf1') ? "Leaf1VPHeight{$i}" : "Leaf2VPHeight{$i}";
            if (!empty($show->$heightField)) {
                $heights[] = $width . "x" . $show->$heightField . " ({$i})";
            }
        }

        if (!empty($heights)) {
            return $shape . " (" . $quantity . ") " . implode(" </br> ", $heights);
        }

        return "N/A";
    }

    /**
     * Helper method to format side screen dimensions
     */
    private function formatSideScreen($width, $height)
    {
        if (!empty($width) && !empty($height)) {
            return $width . ' x ' . $height;
        } elseif (!empty($width) && empty($height)) {
            return $width . ' x N/A';
        } elseif (empty($width) && !empty($height)) {
            return 'N/A x ' . $height;
        }
        return 'N/A';
    }

    /**
     * Helper method to get frame material
     */
    private function getFrameMaterial($frameMaterial)
    {
        if (empty($frameMaterial) || in_array($frameMaterial, ["MDF", "Softwood", "Hardwood"])) {
            return "N/A";
        }

        $selected = LippingSpecies::find($frameMaterial);
        if ($selected) {
            return $selected->SpeciesName;
        }

        $selected = LippingSpecies::where("SpeciesName", $frameMaterial)->first();
        if ($selected) {
            return $selected->SpeciesName;
        }

        return "N/A";
    }

    /**
     * Helper method to get frame size
     */
    private function getFrameSize($show)
    {
        $size = '';

        if (!empty($show->FrameType)) {
            if ($show->FrameType == 'Rebated_Frame') {
                $size = ($show->RebatedWidth ?? '') . "x" . ($show->RebatedHeight ?? '') . "mm";
            } elseif ($show->FrameType == 'Plant_on_Stop') {
                $size = ($show->PlantonStopWidth ?? '') . "x" . ($show->PlantonStopHeight ?? '') . "mm";
            } elseif ($show->FrameType == 'Scalloped') {
                $size = ($show->ScallopedWidth ?? '') . "x" . ($show->ScallopedHeight ?? '') . "mm";
            }
        }

        return $size ?: '';
    }

    /**
     * Helper method to get external liner size
     */
    private function getExtLinerSize($show)
    {
        $ExtLinerValue = $show->ExtLinerValue ?? '';
        $ExtLinerThickness = '';
        if (!empty($show->ExtLinerThickness)) {
            $ExtLinerThickness = $show->ExtLinerThickness . 'mm';
        }

        if (empty($ExtLinerValue) && ($ExtLinerThickness === '' || $ExtLinerThickness === '0')) {
            return "N/A";
        } elseif (empty($ExtLinerValue) && ($ExtLinerThickness !== '' && $ExtLinerThickness !== '0')) {
            return 'N/A x ' . $ExtLinerThickness;
        } elseif (!empty($ExtLinerValue) && ($ExtLinerThickness === '' || $ExtLinerThickness === '0')) {
            return $ExtLinerValue . ' x N/A';
        } elseif (!empty($ExtLinerValue) && ($ExtLinerThickness !== '' && $ExtLinerThickness !== '0')) {
            return $ExtLinerValue . ' x ' . $ExtLinerThickness;
        }

        return '';
    }
}
