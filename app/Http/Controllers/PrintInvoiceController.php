<?php

namespace App\Http\Controllers;

use Session;
use Illuminate\Support\Facades\Auth;
use PDF;
use PdfMerger;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoiceInExcel;
use App\Exports\InvoiceInExcelVicaima;
use Illuminate\Http\Request;
use DB;
use App\Models\User;
use App\Models\Users;
use App\Models\Items;
use App\Models\Option;
use App\Models\Company;
use App\Models\ConfigurableItems;
use App\Models\Item;
use App\Models\ItemMaster;
use App\Models\LippingSpecies;
use App\Models\SettingIntumescentSeals2;
use App\Models\Project;
use App\Models\Quotation;
use App\Models\QuotationShipToInformation;
use App\Models\NonConfigurableItems;
use App\Models\QuotationVersion;
use App\Models\SettingPDF1;
use App\Models\SettingPDF2;
use App\Models\SettingPDFfooter;
use App\Models\SettingPDFDocument;
use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\BOMSetting;
use App\Models\AddIronmongery;
use App\Models\BOMDetails;
use App\Models\SideScreenItem;
use App\Models\SideScreenItemMaster;
use App\Models\Intumescentseals;
use App\Models\SelectedLippingSpecies;
use App\Models\GlassType;
use App\Models\OverpanelGlassGlazing;
use App\Models\IntumescentSealLeafType;
use App\Models\QuotationContactInformation;
use App\Models\GlazingSystem;
use App\Models\SettingCurrency;
use App\Models\QuotationSiteDeliveryAddress;
use App\Models\ClientSignature;
use setasign\Fpdi\Tcpdf\Fpdi;
use App\Jobs\{GenerateQuotationPDF,pdf1,pdf2,pdf3andpdf4_2,pdf4,pdf6,pdf8};

class PrintInvoiceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['printinvoiceclient','signaturesubmit']);
    }

    public function printinvoice($quatationId, $versionID, $isActive = null , $userid = null,$clientclick=null): mixed
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

        $contractorName = DB::table('users')
        ->where('id', $quotaion->MainContractorId)
        ->where('UserType', 5)
        ->value('FirstName') ?? '';

        $settings = SettingCurrency::where('UserId', $id)
            ->select('HideCosts', 'companyCode')
            ->first();

        $HideCosts = $settings->HideCosts ?? null;
        $companyCode = $settings->companyCode ?? null;

        $currency = QuotationCurrency($quotaion->Currency);

        // $configurationItem = 1;
        $configurationItem = $quotaion->configurableitems;
        if (!empty($quotaion->configurableitems)) {
            $configurationItem = $quotaion->configurableitems;
        }

        $configurationItemName = configurationDoor($configurationItem);
        if($configurationItemName === 'Halspan'){
            $configurationItemName = 'Halspan Optima';
        }
        $project = empty($quotaion->ProjectId) ? '' : Project::where('id', $quotaion->ProjectId)->first();

        $pdf_footer = SettingPDFfooter::where('UserId', $id)->first();

        // Sales Contact
        $SalesContact = $quotaion->QuotationName ?? 'N/A';

        // PDF 1 (Introduction PDF)
        $customerContact = !empty($quotaion->MainContractorId)
            ? Users::find($quotaion->MainContractorId)
            : null;

        // Customer & Address
        $customer = null;
        $CstCompanyAddressLine1 = '';

        if ($customerContact) {
            $customer = Customer::where('UserId', $quotaion->MainContractorId)->first();
            $CstCompanyAddressLine1 = $customer->CstCompanyAddressLine1 ?? '';
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

        $pdf2 = PDF::loadView('Company.pdf_files.quotationsummarypdf', ['comapnyDetail' => $comapnyDetail, 'project' => $project, 'quotaion' => $quotaion, 'pdf2' => $pdf2, 'pdf_footer' => $pdf_footer, 'totDoorsetType' => $totDoorsetType, 'totIronmongerySet' => $totIronmongerySet, 'totDoorsetPrice' => $totDoorsetPrice, 'totIronmongaryPrice' => $totIronmongaryPrice, 'nonConfigDataPrice' => $nonConfigDataPrice, 'nettot' => $nettot, 'QSTI' => $QSTI, 'customerContact' => $customerContact, 'customer' => $customer, 'user' => $user, 'nonConfigDataCount' => $nonConfigDataCount, 'contractorName' => $contractorName, 'ScreenSetQty' => $ScreenSetQty, 'screenDataprice' => $screenDataprice, 'currency' => $currency, 'transportationCost' => $transportationCost]);

        // return $pdf2->download('file2.pdf');
        $path2 = public_path() . '/allpdfFile';
        $fileName2 = $id . '2' . '.' . 'pdf';
        $pdf2->save($path2 . '/' . $fileName2);

        $QuotationShipToInformation = QuotationShipToInformation::where('QuotationId', $quatationId)->first();
        $QuotationSiteDelivery = QuotationSiteDeliveryAddress::where('QuotationId', $quatationId)->get();
        $ProjectsAddress = Project::join('quotation', 'quotation.ProjectId', 'project.id')->where(['quotation.CompanyId' => $quotaion->CompanyId, 'quotation.ProjectId' => $quotaion->ProjectId])->first();

        $htmlPreview = '<p><span style="font-size:36px"><strong>' .$project->ProjectName.'</strong></span></p>

            <p><span style="font-size:36px"><strong>' .$quotaion->QuotationGenerationId.' -&nbsp;&nbsp; Delivery Summary </strong></span></p>

            <p>&nbsp;</p>
            <table class="table table-bordered">';

            $i = 1;
            foreach($QuotationSiteDelivery as $QuotationSiteDeliveryAddress){

                $htmlPreview .= '<tr>
                    <td colspan="4"><p><span style="font-size:15px"><strong>Site Delivery Address - '.$i++.' </strong></span></p></td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>Address 1</span></td>
                    <td colspan="3">
                        <span>' . (!empty($QuotationSiteDeliveryAddress->Address1) ? $QuotationSiteDeliveryAddress->Address1 : (!empty($ProjectsAddress->AddressLine1) ? $ProjectsAddress->AddressLine1 : '')) . '</span>
                    </td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>Address 2</span></td>
                    <td colspan="3">
                        <span>' . (!empty($QuotationSiteDeliveryAddress->Address2) ? $QuotationSiteDeliveryAddress->Address2 : (!empty($ProjectsAddress->AddressLine2) ? $ProjectsAddress->AddressLine2 : '')) . '</span>
                    </td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>Country</span></td>
                    <td colspan="3"><span>' . (!empty($QuotationSiteDeliveryAddress->Country) ? $QuotationSiteDeliveryAddress->Country : (!empty($ProjectsAddress->Country) ? $ProjectsAddress->Country : '')) . '</span></td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>City</span></td>
                    <td colspan="3"><span>' . (!empty($QuotationSiteDeliveryAddress->City) ? $QuotationSiteDeliveryAddress->City : (!empty($ProjectsAddress->City) ? $ProjectsAddress->City : '')) . '</span></td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>Postal Code</span></td>
                    <td colspan="3"><span>' . (!empty($QuotationSiteDeliveryAddress->PostalCode) ? $QuotationSiteDeliveryAddress->PostalCode : (!empty($ProjectsAddress->PostalCode) ? $ProjectsAddress->PostalCode : '')) . '</span></td>
                </tr>';
            }

            $htmlPreview .= '<tr>
                    <td class="tbl_color"><span>Delivery Restrictions</span></td>
                    <td colspan="3"><span>' . ($QuotationShipToInformation->DeliveryRestrictions ?? '') . '</span></td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>Wagon Preference</span></td>
                    <td colspan="3">
                        <span>';
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
                            $wagon = $QuotationShipToInformation->WagonPreference ?? '';
                            $htmlPreview .= $wagonOptions[$wagon] ?? '';
            $htmlPreview .= '</span>
                    </td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>Booking Notice & Contact</span></td>
                    <td colspan="3"><span>' . ($QuotationShipToInformation->Booking ?? '') . '</span></td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>Delivery Policy</span></td>
                    <td colspan="3"><span>' . ($QuotationShipToInformation->Deliverypolicy ?? '') . '</span></td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>FORS Requirement - Silver</span></td>
                    <td><span>' . ($QuotationShipToInformation->silver ?? 'No') . '</span></td>
                    <td class="tbl_color"><span>FORS Requirement - Gold</span></td>
                    <td><span>' . ($QuotationShipToInformation->gold ?? 'No') . '</span></td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>Off-loading Requirements</span></td>
                    <td colspan="3"><span>' . ($QuotationShipToInformation->Offloading ?? '') . '</span></td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>No. of Deliveries</span></td>
                    <td><span>' . ($QuotationShipToInformation->NoOfDeliveries ?? '') . '</span></td>
                    <td class="tbl_color"><span>Actual No. of Deliveries</span></td>
                    <td><span>' . ($QuotationShipToInformation->ActualNoOfDeliveries ?? '') . '</span></td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>Cost Per Delivery</span></td>
                    <td><span>' . ($QuotationShipToInformation->CurrencyCostperdelivery  ?? '').(number_format((float)$QuotationShipToInformation->Costperdelivery, 2) ?? '0.00') . '</span></td>
                    <td class="tbl_color"><span>Average No. Doorsets per Drop</span></td>
                    <td><span>' . ($QuotationShipToInformation->AverageNoDoorsetsperdrop ?? '') . '</span></td>
                </tr>
            </table>';

        $pdf2_1 = PDF::loadView('Company.pdf_files.quotationDeliverypdf', ['comapnyDetail' => $comapnyDetail,'htmlPreview' => $htmlPreview]);

        // return $pdf2->download('file2.pdf');
        $path2_1 = public_path() . '/allpdfFile';
        $fileName2_1 = $id . '2_1' . '.' . 'pdf';
        $pdf2_1->save($path2_1 . '/' . $fileName2_1);


        $margin = BOMSetting::wherein('UserId',$userIds)->value('margin_for_material');


        // Details Door List PDF
        $qv = QuotationVersion::where('id', $versionID)->first();
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


        // SUMMARY (if you still need it)
        $showDatas = $shows->groupBy('itemId'); // no ->get()

        $i = 1;
        $DoorDescription = '';
        foreach ($showDatas as $itemId => $rows) {

            $qty = $rows->count();           // qty based on grouped rows
            $show = $rows->first();          // ✔ pick first row for properties
            if (!empty($show->DoorsetType)) {
                $DoorDescription = DoorDescription($show->DoorsetType);
            }


            $doorPrice = $show->AdjustPrice
                            ? floatval($show->AdjustPrice)
                            : floatval($show->DoorsetPrice);

            $ironPrice = floatval($show->IronmongaryPrice);

            $doorPriceTotal = round($doorPrice, 2);
            $ironPriceTotal = round($ironPrice, 2);
            $total = round(($doorPrice + $ironPrice), 2) * $qty;

            $a2 .= '<tr>
                        <td>' . $show->doorNumber . '</td>
                        <td>' . $DoorDescription . '</td>
                        <td>' . $show->DoorType . '</td>
                        <td>' . $qty . '</td>';

            if ($HideCosts == 0) {
                $a2 .= '<td>' . $doorPriceTotal . '</td>
                        <td>' . $ironPriceTotal . '</td>';
            }

            $a2 .= '<td class="price">' . number_format(
                $total,
                2,
                '.',
                ''
            ) . '</td></tr>';
            $i++;
        }


        $pdf3 = PDF::loadView('Company.pdf_files.detaildoorlist', ['a2' => $a2, 'comapnyDetail' => $comapnyDetail, 'quotaion' => $quotaion, 'project' => $project, 'version' => $version, 'HideCosts' => $HideCosts]);
        // return $pdf3->download('file3.pdf');
        $path3 = public_path() . '/allpdfFile';
        $fileName3 = $id . '3' . '.' . 'pdf';
        $pdf3->save($path3 . '/' . $fileName3);


        //Non Configurable Item
        $nonConfigData = nonConfigurableItem($quatationId,$versionID,$userIds);

        $pdf4_2 = PDF::loadView('Company.pdf_files.nonconfigdoor', ['nonConfigData' => $nonConfigData, 'comapnyDetail' => $comapnyDetail, 'quotaion' => $quotaion, 'project' => $project, 'customerContact' => $customerContact, 'version' => $version, 'customer' => $customer]);
        // return $pdf4->download('file4.pdf');
        $path4_2 = public_path() . '/allpdfFile';
        $fileName4_2 = $id . '4_2' . '.' . 'pdf';
        $pdf4_2->save($path4_2 . '/' . $fileName4_2);



        //PDF 2
        $CompanyId = get_company_id($id)->id;
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
            $DoorQuantity++;
            // $DoorsetPrice += $show->DoorsetPrice;
            // $IronmongaryPrice += $show->IronmongaryPrice;


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
                            <td>' . $SpecialFeatureRefs . '</td>';
                            if($HideCosts == 0){
                                $a .= '<td class="tbl_last">' . round($DoorsetPrice, 2) . '</td>
                                <td class="tbl_last">' . round($IronmongaryPrice, 2) . '</td>';
                            }

                            $a .= '<td class="tbl_last">' . round($totalpriceperdoorset, 2) . '</td>
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
                            <td>' . $SpecialFeatureRefs . '</td>';
                            if($HideCosts == 0){
                                $a .= '<td class="tbl_last">' . round($DoorsetPrice, 2) . '</td>
                                <td class="tbl_last">' . round($IronmongaryPrice, 2) . '</td>';
                            }

                            $a .= '<td class="tbl_last">' . round($totalpriceperdoorset, 2) . '</td>
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
                        <td class="tbl_bottom" colspan="39"></td>';
                        if($HideCosts == 0){
                            $a .= '<td class="tbl_bottom">' .$currency. round($SumDoorsetPrice, 2) . '</td>
                            <td class="tbl_bottom">' . $currency.round($SumIronmongaryPrice, 2) . '</td>';
                        }

                        $a .= '<td class="tbl_bottom">' .$currency. round($Alltotalpriceperdoorset, 2) . '</td>
                    </tr>
                ';


        if($quotaion->configurableitems == 4 || $quotaion->configurableitems == 5 || $quotaion->configurableitems == 6 || $quotaion->configurableitems == 9){
            $pdf4 = PDF::loadView('Company.pdf_files.vicaima.pdf2', ['a' => $a, 'comapnyDetail' => $comapnyDetail, 'project' => $project, 'customerContact' => $customerContact, 'version' => $version, 'customer' => $customer, 'HideCosts' => $HideCosts]);
        }else{
            $pdf4 = PDF::loadView('Company.pdf_files.pdf2', ['a' => $a, 'comapnyDetail' => $comapnyDetail, 'project' => $project, 'customerContact' => $customerContact, 'version' => $version, 'customer' => $customer, 'HideCosts' => $HideCosts]);
        }

        // return $pdf4->download('file4.pdf');
            $path4 = public_path() . '/allpdfFile';
            $fileName4 = $id . '4' . '.' . 'pdf';
            $pdf4->save($path4 . '/' . $fileName4);
        // side screen door list

        $s2 = '';
        $shocw = SideScreenItem::join('side_screen_item_master', 'side_screen_items.id', 'side_screen_item_master.ScreenId')->where(['side_screen_items.QuotationId' => $quatationId])
        ->where(['side_screen_items.VersionId' => $versionID])
        ->select('side_screen_items.*','side_screen_item_master.screenNumber')
        ->get();
        $i = 1;
        foreach ($shocw as $show) {
            $s2 .=
            '<tr>
            <td>' . $show->ScreenType . '</td>
            <td>' . $show->screenNumber . '</td>
            <td>' . $show->GlazingType . '</td>
            <td>' . ($show->ScreenAdjustPrice > 0 ? $show->ScreenAdjustPrice : $show->ScreenPrice) . '</td>
            </tr>';
            $i++;
        }

        $pdf9 = PDF::loadView('Company.pdf_files.sidescreendoorlist', ['a2' => $s2, 'comapnyDetail' => $comapnyDetail, 'quotaion' => $quotaion, 'project' => $project, 'version' => $version]);
        $path9 = public_path() . '/allpdfFile';
        $fileName9 = $id . '9' . '.' . 'pdf';
        $pdf9->save($path9 . '/' . $fileName9);
        // end

        // Elevation Drawing
        $elevTbl = '';
        // $ed = Item::where('QuotationId',$quatationId)->get();
        $ed = Item::join('item_master','item_master.itemID','=','items.itemId')->join("quotation_version_items",function($join): void{
            $join->on("quotation_version_items.itemID","=","items.itemId")
                ->on("quotation_version_items.itemmasterID","=","item_master.id");
        })
        ->join('quotation','quotation.id','=','items.QuotationId')
        ->where('items.QuotationId', $quatationId)
        // ->where('items.itemId',2342) to see particular quote
        ->where('quotation_version_items.version_id', $versionID)->select('items.*','item_master.doorNumber','quotation.configurableitems')->groupBy('item_master.itemID')->get();

        $TotalItems = count($ed->toArray());

        $PageBreakCount = 1;
        $PageBreakCounts = 1;

        foreach ($ed as $tt) {
            $getLeaf = IntumescentSealLeafType::where('id',$tt->IntumescentLeafType)->select('id','leaf_type_key','door_thickness')->first();
            if($getLeaf){
                $fire = $tt->FireRating . ' - ' . $getLeaf->leaf_type_key . ' (' . $getLeaf->door_thickness . 'mm)';
            }
            else{
                $fire = $tt->FireRating;
            }

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

           $certMap = [
                4 => [
                    'NFR' => 'FEA/F99112 Revision L',
                    'FD30' => 'FEA/F99112 Revision L',
                    'FD60' => 'FEA/F96103  Revision Q',
                ],
                8 => [
                    'NFR' => 'BMT/CNA/F15159 Revision F',
                    'FD30' => 'BMT/CNA/F15159 Revision F',
                    'FD60' => 'WF377027 Revision A',
                ],
                1 => [
                    'NFR' => 'Chilt/A02066 Revision P',
                    'FD30' => 'Chilt/A02066 Revision P',
                    'FD60' => 'Chilt/A02067 Revision M',
                ],
                2 => [
                    'NFR' => 'Chilt/A01205 Part 1 Revision K',
                    'FD30' => 'Chilt/A01205 Part 1 Revision K',
                    'FD60' => 'Chilt/A01205 Part 1 Revision K',
                ],
                7 => [
                    'NFR' => 'FEA98164 Revision P',
                    'FD30' => 'FEA98164 Revision P',
                    'FD60' => 'FEA/F02141 Revision M',
                ],
                6 => [
                    'NFR' => 'WF399992 Revision E',
                    'FD30' => 'WF399992 Revision E',
                    'FD60' => 'WF399992 Revision E',
                ],
                5 => [
                    'NFR' => '10133/22-2.R1',
                    'FD30' => '10133/22-2.R1',
                    'FD60' => '10133/22-2.R1',
                ],
            ];

            $certNo = $certMap[$tt->configurableitems][$FireRatingActualValue] ?? '';

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
            $GlazingSystems = GlazingSystemsImage($GlazingSystems,$GlazingBeads,$tt->FireRating);
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

                if($tt->DoorsetType == "SD"){
                if(!empty($tt->Handing) && $tt->Handing == "Left"){
                  $FrameTypeLeft = \Config::get('constants.base64Images.ScallopedLeft');
                  $FrameTypeRight = \Config::get('constants.base64Images.ScallopedStraight');
                  $FrameTypeCommon = \Config::get('constants.base64Images.FrameRebatedCommon');
                } else {
                  $FrameTypeLeft = \Config::get('constants.base64Images.ScallopedStraight');
                  $FrameTypeRight = \Config::get('constants.base64Images.ScallopedRight');
                  $FrameTypeCommon = \Config::get('constants.base64Images.FrameRebatedCommon');
                }}
                else{
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
            $RemainingSpaceBlockScallopedLeft=\Config::get('constants.base64Images.RemainingSpaceBlockScallopedLeft');
            $RemainingSpaceBlockScallopedRight=\Config::get('constants.base64Images.RemainingSpaceBlockScallopedRight');
            $FixedSpaceBlockScallopedLeft=\Config::get('constants.base64Images.FixedSpaceBlockScallopedLeft');
            $FixedSpaceBlockScallopedRight=\Config::get('constants.base64Images.FixedSpaceBlockScallopedRight');


            $remainingWidth = $tt->LeafWidth1 - ($tt->Leaf1VPWidth + $tt->DistanceFromTheEdgeOfDoor);

            if (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') {
                if ($tt->DistanceFromTheEdgeOfDoor > $remainingWidth) {
                    $FrameImageStructureLeft = $FixedSpaceBlockScallopedRight;
                    $FrameImageStructureRight = $FixedSpaceBlockScallopedLeft;

                } elseif ($tt->DistanceFromTheEdgeOfDoor < $remainingWidth) {


                     if(($show->DoorsetType == "SD")){
                       if(!empty($tt->Handing) && $tt->Handing == "Left"){
                          $FrameImageStructureLeft = $FixedSpaceBlockScallopedLeft;
                          $FrameImageStructureRight = $FixedSpaceBlock;
                       }else{
                           $FrameImageStructureLeft = $FixedSpaceBlock;
                           $FrameImageStructureRight = $FixedSpaceBlockScallopedRight;
                       }
                    }else{
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

                   if(($tt->DoorsetType == "SD")){
                       if(!empty($tt->Handing) && $tt->Handing == "Left"){
                           $FullBlock =$FixedSpaceBlockScallopedLeft;
                       }else{
                          $FullBlock =$FixedSpaceBlockScallopedRight;
                       }
                    }else{
                          $FullBlock =$FixedSpaceBlockScallopedRight;
                    }

                } else {
                    $FrameImageStructureLeftLeaf1 = $FixedSpaceBlockScallopedLeft;
                    $FrameImageStructureRightLeaf1 = $RemainingSpaceBlock;
                    $FullBlock =$FixedSpaceBlockScallopedRight;

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

            $fetchSignature = ClientSignature::where('quotation_id',$quatationId)->where('version_id',$versionID)->first();
// dd($tt->Leaf2VisionPanel != 'Yes');
            $redstripRightCommonClass = $tt->IntumescentLeapingSealLocation.'_right_strip_'.$tt->DoorsetType;
            $redstripLeftCommonClass = $tt->IntumescentLeapingSealLocation.'_left_strip_'.$tt->DoorsetType;
            switch ($tt->DoorsetType) {
                case "SD":
                    // $DoorFrameImage = Base64Image('FD30SingleDoorsetwithVP');

                    $DoorFrameImage = '<div style="padding:10px 30px;position: relative;margin-left: '.$frameImageLeftMargin.'px;height: 100px;">
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
                                    margin-left:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ($tt->IntumescentLeapingSealLocation == 'Door'? '26' : ((empty($tt->Handing) && $tt->Handing == "Left")?'13':'14')) : ($tt->IntumescentLeapingSealLocation == 'Door'? '-3' : '-3')) .'px;
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
                                    margin-left: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ($tt->IntumescentLeapingSealLocation == 'Door'? '26' : '13') : ($tt->IntumescentLeapingSealLocation == 'Door'? '15' : '3')) .'px;
                                    margin-top: 15px;"></div>

                                    <div style="border: 0.5px solid black;
                                    background-color: red;
                                    z-index: 999;
                                    position: absolute;
                                    height: 16px;
                                    width: 6px;
                                    box-shadow: none;
                                    margin-left:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ($tt->IntumescentLeapingSealLocation == 'Door'? '26' : '13') : ($tt->IntumescentLeapingSealLocation == 'Door'? '15' : '3')) .'px;
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
                        width: 69px;
                        height: 71px;
                        margin-left: -26px;
                        '.
                            (($tt->FireRating == 'FD30') ?
                                'top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '84' :  '133') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '86' : '137')) .'px;'
                                :
                                'top:'. (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '84' : '135') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '86' : '137')) .'px;')
                        .'
                        " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                           >


                     </div>
                     <div style="position: absolute;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-47' : '5').'px;left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-251' : '-265').'px;">
                        <img style="width:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '53' : '76').'px;height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '208' : ((!empty($tt->FrameType) && $tt->FrameType == 'Rebated_Frame') ? '131' : '196')).'px;" alt="" src="'.$FrameTypeCommon.'">
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
                           '.
                            (($tt->FireRating == 'FD30') ?
                                'top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-108' : '-158') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-111' : '-163')) .'px;'
                                :
                                'top: '. (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-108' : '-158') :((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-111' : '-163')) .'px;')
                            .'
                           " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                           >

                     </div>
                     <div style="position: absolute;top:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-46' : '7').'px;left:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-83' : '-87').'px;">
                        <img style="width: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '53' : '76').'px; height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '208' : ((!empty($tt->FrameType) && $tt->FrameType == 'Rebated_Frame') ? '131' : '196')).'px;" alt="" src="'.$FrameTypeCommon.'">
                     </div>';

                    }


                    $DoorFrameImage .= '<div  style="position: absolute; top: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-46' : '7') .'px;left: -44px;">
                                        <img style="width:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && !empty($tt->Handing) && $tt->Handing == "Left") ? '77' : '67') .'px; height:'. (
    (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '170px' : ($tt->FrameType == 'Rebated_Frame'? '126px': '196px' ) ) .';" src="' . $FrameTypeLeft . '" alt="">
                                    </div>
                                </div>';

                    if ($tt->Leaf1VisionPanel == 'Yes') {

                        $DoorFrameImage .= '<div style="position: relative;top: 12px;">
                                    <img style="
                                        width: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ?  '155' : '165') .'px;
                                        position: relative;
                                        top: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (((!empty($tt->Handing) && $tt->Handing != "Left"))?'-17': '5') : '-17') .'px;
                                        left: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '26' : '7') .'px;'
                                           .
        ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (((!empty($tt->Handing) && $tt->Handing != "Left") && ($show->Leaf1VisionPanel == "Yes"))? 'height: 116px;' :'height: 65px;') : '') .
        '" src="' . $FrameImageStructureLeft . '" alt="">
                                    </div>
                                    <div style="width:430px;">
                                        <div style="
                                            width:200px;
                                            margin: 0 auto;
                                            position: relative;
                                            margin-left: -66px;
                                            top: -168px;
                                            left: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '248' : '245') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '237' : '235')) .'px;
                                            ">

                                            <img style="
                                            z-index: 999;
                                            ' .(($tt->FireRating == 'FD30') ?
                            'width: 125px;
                             margin-top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (((!empty($tt->Handing) && $tt->Handing != "Left")&& ($show->Leaf1VisionPanel == "Yes"))?'71': '118') : '74') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ((!empty($tt->Handing) && $tt->Handing != "Left")?'62':'111') : '68')) .'px;
                                                height:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? '70' : '82') .'px;' :
                            'width: 108px; margin-left: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? '-6' : '4') .'px;
                                                margin-top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ?( ($tt->Handing != "Left")?'66':'118')  : '74') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '111' : '69')) .'px;
                                                height:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? '70' : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '82' : '80'))) .'px;' . '" src="' . $VisionPanelGlazingImageStructure . '" alt="">



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

                                             'width: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? '117' : '125') .'px;
                                                 margin-top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (((!empty($tt->Handing) && $tt->Handing != "Left")&& ($show->Leaf1VisionPanel == "Yes"))? '135':'135') : '132') :  ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '124' : '121')) .'px;
                                                 margin-left: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '31' : '33') : '34') .'px;
                                                height: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? '73' : '84') .'px;'
                            :
                            'width: 124px; margin-top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '135' : '132') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '124.5' : '122.5')) .'px;
                                                 margin-left: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? '25.5' : '31.5') .'px;
                                                height:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? '72' : '82') .'px;') . '
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
                        $DoorFrameImage .= '   <div style="position: relative;position: absolute;top: 7px;left:475px;"><img style="width: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '158' : '170') .'px;
                                        position: relative;
                                        bottom: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (( $tt->Handing != "Left")? (($show->Leaf1VisionPanel == "Yes")? '-22':'2'):  '1') : (($tt->Leaf1VisionPanel == "Yes")?'2':'-22')  )  .'px;
                                        left: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ((empty($tt->Handing) && $tt->Handing == "Left")?'-32': '-30') : '-31') .'px;'
                                           .
        ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Handing == "Left")? (($show->Leaf1VisionPanel == "Yes")? ((in_array($tt->FireRating, ["FD60", "FD60s"]))?'height:112px;': 'height:112px;'):'height:109px;') : 'height:65px;' )  : '') .
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

                        $DoorFrameImage .= '<img style="width:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' ) ? '575':'583') .'px;
                        position: relative;
                        top: -17px;
                        left: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' ) ? (( !empty($tt->Handing) && $tt->Handing != "Left")? '27' : '25'):'3') .'px;height: 108px;" src="' . $FullBlock . '" alt="">
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
                                        margin-left: '. (
                                            !empty($tt->FrameType) && $tt->FrameType == 'Scalloped' ? ($tt->IntumescentLeapingSealLocation == 'Door'? '-50' : (!empty($tt->Handing) && $tt->Handing != "Left"? (($show->Leaf1VisionPanel == "Yes")?'-31.5':'-5.5') :(($show->Leaf1VisionPanel == "Yes")?'-34.5':'-6.5')) ) : ($tt->IntumescentLeapingSealLocation == 'Door'? '-27.5' : '-27.5')) .'px;
                                        margin-top:'.(!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && (!empty($tt->Handing) && $tt->Handing != "Left") &&($show->Leaf1VisionPanel == "Yes")?'50':'41'  ).'px;"></div>';
                        } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                            // class="'.$redstripRightCommonClass.'"
                            $DoorFrameImage .= '<div  style="border: 0.5px solid black;
                                        background-color: red;
                                        z-index: 999;
                                        position: absolute;
                                        height: 16px;
                                        width: 6px;
                                        box-shadow: none;
                                        margin-left: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ($tt->IntumescentLeapingSealLocation == 'Door'? '-50' : ((!empty($tt->Handing) && $tt->Handing != "Left" )?( ($tt->Handing != "Left")? '-34.5':'-5.5' ): ((in_array($tt->FireRating, ["FD60", "FD60s"]))? (($show->Leaf1VisionPanel == "Yes")?'-35.5':'-5.5' ): '-5.5'))) : ($tt->IntumescentLeapingSealLocation == 'Door'? '-35.5' : '-25.5')) .'px;
                                        margin-top: 30px;"></div>

                                        <div style="border: 0.5px solid black;
                                        background-color: red;
                                        z-index: 999;
                                        position: absolute;
                                        height: 16px;
                                        width: 6px;
                                        box-shadow: none;
                                        margin-left: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ($tt->IntumescentLeapingSealLocation == 'Door'? '-50' : ((!empty($tt->Handing) && $tt->Handing != "Left" )?( ($tt->Handing != "Left")? '-34.5':'-5.5' ):((in_array($tt->FireRating, ["FD60", "FD60s"]))?(($show->Leaf1VisionPanel == "Yes")?'-35.5':'-5.5' ): '-5.5'))) : ($tt->IntumescentLeapingSealLocation == 'Door'? '-35.5' : '-25.5')) .'px;
                                        margin-top: 57px;"></div>';
                        }
                    }

                    if(empty($FrameTypeRight)){
                        $FrameTypeRight = '';
                    }
                    // dd($tt->Handing);
                    if($tt->FrameType !== null){
                    $DoorFrameImage .= '<div style="position: absolute;top:  '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && $tt->Handing != "Left" && ($show->Leaf1VisionPanel == "Yes") ) ? '28' :'21' ).'px;right: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (( $tt->Handing == "Left")? (($show->Leaf1VisionPanel == "Yes")?'-40':'-69') :(($show->Leaf1VisionPanel == "Yes")?'-32':'-60')) : '-27') .'px;">
                                        <img style="width:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && !empty($tt->Handing) && $tt->Handing != "Left") ? '77' : '77') .'px;
                                        margin-top: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-53' : '-1') .'px; height:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '170px' : 'auto') .';" src="' .  $FrameTypeRight . '" alt="">
                                    </div>
                                </div>
                            ';
                                        }
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
                              width: 69px;
                              height: 71px;
                              margin-left: -26px;
                              top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '82' : '132') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '84' : '135')) .'px;
                              '.
                            (($tt->FireRating == 'FD30') ?
                                'top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '82' : '132') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '84' : '135')) .'px;'
                                :
                                'top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '82' : '132') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '84' : '135')) .'px;')
                            .'
                              " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                              >


                        </div>
                        <div style="position: absolute;top:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-49' : '4').'px;left:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-252' : '-265').'px;">
                           <img style="width: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '55' : '77').'px;height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '212' : '194').'px;" alt="" src="'.$FrameTypeCommon.'">
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
                              top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-106' : '-157') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-109' : '-162')) .'px;
                              '.
                            (($tt->FireRating == 'FD30') ?
                                'top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-106' : '-157') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-109' : '-162')) .'px;'
                                :
                                'top: '. (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-106' : '-157') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-109' : '-162')) .'px;')
                            .'" alt="" src="'.$VisionPanelGlazingImageStructure.'"
                              >


                        </div>
                        <div style="position: absolute;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-48' : '7').'px;left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-84' : '-87').'px;">
                           <img style="width: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '55' : '76').'px;height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '208' : ((!empty($tt->FrameType) && $tt->FrameType == 'Rebated_Frame') ? '131' : '196')).'px;" alt="" src="'.$FrameTypeCommon.'"
                              >
                        </div>
                       </div>

                      </div>';

                    }

                    break;


                case "DD":
                    // $DoorFrameImage = Base64Image('FD30DoubleDoorsetwithVP');

                    $DoorFrameImage = '<div style="padding: 10px 30px; position: relative;margin-left: '.$frameImageLeftMargin.'px;height: 200px;">
                            <div style="position: relative;  top: 12px;">';



                    if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {
                        // margin-left: 5px;
                        if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                            $DoorFrameImage .= '<div class="'. ($tt->FrameType !== null ? $redstripLeftCommonClass : 'DD_NoFrame_left_intubacent') .'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-top: 18px;"></div>';
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
                                'width: 66px;height: 50px;margin-left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-14' : '-4').'px;top:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '111' : ($GlazingSystems['GlazingBeadsPadding'] == 0 ?'133': '135')).'px;'
                                :
                                'width: 66px;height: 50px;margin-left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-14' : '-4').'px;top:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '111' : ($GlazingSystems['GlazingBeadsPadding'] == 0 ?'133': '135')).'px;')
                                 .'
                                " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                                    />


                            </div>

                            <div style="position: absolute;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-19' : '4').'px;left:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-233' : '-222').'px;">';

                            // if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {
                            //     if (in_array($tt->FireRating, ["FD30", "FD30s"])) {

                            //         $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 18px;"></div>';

                            //     } else if (in_array($tt->FireRating, ["FD60", "FD60s"])) {

                            //         $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 10px;"></div>
                            //                 <div class="'.$redstripLeftCommonClass.'_'.$sidelight.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 25px;"></div>';
                            //     }
                            // }


                            $DoorFrameImage .= '<img style="width: 46px;height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '107' : '117').'px;" alt="" src="'.$FrameTypeCommon.'" />
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
                                    '.
                                    (($tt->FireRating == 'FD30') ?
                                    'width: 66px;height: 55px;margin-left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '2' : '-7').'px;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-116' :  ($GlazingSystems['GlazingBeadsPadding'] == 0 ?'-141':'-143.5')).'px;'
                                    :
                                    'width: 66px;height: 55px;margin-left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '2' : '-7').'px;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-116' :  ($GlazingSystems['GlazingBeadsPadding'] == 0 ?'-141':'-143.5')).'px;')
                                    .'
                                    " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                                    >





                            </div>

                            <div style="position: absolute;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-22' : '4').'px;left:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-74' : '-66').'px;">';


                            // if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {


                            //     if (in_array($tt->FireRating, ["FD30", "FD30s"])) {

                            //         $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 18px;"></div>';

                            //     } else if (in_array($tt->FireRating, ["FD60", "FD60s"])) {

                            //         $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 10px;"></div>
                            //                 <div class="'.$redstripRightCommonClass.'_'.$sidelight.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 25px;"></div>';
                            //     }
                            // }
                            $DoorFrameImage .= '<img style="width: 46px;height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '107' : '117').'px;" alt="" src="'.$FrameTypeCommon.'">
                            </div>
                        ';

                    }


                    $DoorFrameImage .= '<div style="position: absolute; top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-22' : '4') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-22' : '4')) .'px; left: -40px;">
                                    <img style="width: '. (
                                                ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '39' : '46')) .'px; alt="" src="' . $FrameTypeLeft . '">
                                </div>
                            </div>';

                    if ($tt->Leaf1VisionPanel == 'Yes') {

                        $DoorFrameImage .= '
                                <div style="position: relative;  top: 12px;">
                                    <img style="width: 97px;
                                        position: relative;
                                        top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '7' : '-10') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '7' : '-10')) .'px;
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
                                        ' . (($tt->FireRating == 'FD30') ? 'width:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '67' : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '70' : '70')) .'px; margin-left:  '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-100' : '-100') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-106' : '-106')) .'px;
                                                 height:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '32' : '42') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '36' : '49')) .'px;
                                        margin-top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '174' : '134') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '172' : '131')) .'px;'
                                                 :
                                                 'width: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '67' : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '70' : '70')) .'px;
                                        margin-left:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-100.5' : '-103.5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-104.5' : '-103.5')) .'px;
                                        height:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '31' : '42') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '36' : '49')) .'px;
                                        margin-top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '174.5' : '134') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '171.5' : '131.5')) .'px;') . '" alt="" src="' . $VisionPanelGlazingImageStructure . '">



                                        <img style="position: absolute;
                                        top: 120px;
                                        transform: rotate(90deg);
                                        z-index: -1;height: 40px;' . (($tt->FireRating == 'FD30') ? 'left: -99px;width: 17px;' : 'left: -97px;width: 14px;') . '" src="' . $VisionPanelGlazingImageLeft . '" alt="">
                                    </div>

                                    <div style="width: 200px; margin-top: 120px; position: relative; top:-354px; left:220px;">

                                        <img style="
                                        transform: rotate(180deg);
                                        z-index: 999;
                                        ' . (($tt->FireRating == 'FD30') ? 'width:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '67' : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '70' : '70')) .'px;
                                        margin-left:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-64' : '-65') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-62' : '-62')) .'px;
                                        margin-top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '16.5' : '8') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '12.5' : '1')) .'px;
                                                 height:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '32' : '41') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '36' : '48')) .'px;'
                                                :
                                                'width: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '67' : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '70' : '70')) .'px;
                                                margin-left:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-65' : '-65') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-64' : '-64')) .'px;
                                                 height:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '31' : '42') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '36' : '48')) .'px;
                                        margin-top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '18' : '7') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '13' : '0')) .'px;') . '" alt="" src="' . $VisionPanelGlazingImageStructure . '">



                                    </div>
                                </div>

                                <div style="position: absolute;
                                    top: 8px;
                                    left: 290px;">';

                        if ($tt->IntumescentLeapingSealLocation == 'Door' || $tt->IntumescentLeapingSealLocation == 'Frame') {

                            if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                                $DoorFrameImage .= '<div style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;
                                margin-left:'. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '32' : '53') .'px;
                                                margin-top: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '31' : '33') .'px;"></div>';
                            } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                                $DoorFrameImage .= '<div class=""  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left:'. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->IntumescentLeapingSealLocation == 'Frame')? (($tt->Leaf2VisionPanel == 'Yes')? '31':'140'):'132') : '53') .'px;margin-top: 23px;"></div>
                                            <div style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left:'. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->IntumescentLeapingSealLocation == 'Frame')? (($tt->Leaf2VisionPanel == 'Yes')? '31':'140'):'132') : '53') .'px;margin-top: 40px;"></div>';
                            }
                        }

                        $DoorFrameImage .= '<img style="width: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '75' : '95') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '75' : '95')) .'px;
                                        position: relative;
                                        top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '10' : '5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '10' : '5')) .'px;
                                        right: 37.5px;" alt="" src="' . $FrameImageStructureRightLeaf1 . '">
                                </div>';
                    } else {
                        $DoorFrameImage .= '<div style="position: relative;top: 12px;">';

                        if ($tt->IntumescentLeapingSealLocation == 'Door' || $tt->IntumescentLeapingSealLocation == 'Frame') {

                            if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                                $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left:'. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '93' : '310')  .'px;margin-top: 18px;"></div>';
                            } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                                $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '93' : '310')  .'px;margin-top: 13px;"></div>
                                            <div class="'.$redstripRightCommonClass.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '93' : '310')  .'px;margin-top: 24px;"></div>';
                            }
                        }

                        $DoorFrameImage .= '<img style="
                                    width: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '105' : '323')  .'px;
                                    position: relative;
                                    top: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '6' : '-10')  .'px;
                                    left: -5.5px;" src="' . $FullBlock . '" alt="">
                                </div>';
                    }

                    if ($tt->Leaf2VisionPanel == 'Yes') {

                        $DoorFrameImage .= '<div style="position: absolute;
                                        top: 8px;
                                        left: 386px;">
                                        <img style="width:'. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '78' : '95')  .'px;
                                            position: relative;
                                            top:'. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '9' : '5')  .'px;
                                            right:'. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '56.5' : '37.5')  .'px;" alt="" src="' . $FrameImageStructureLeftLeaf2 . '">
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
                                                ' . (($tt->FireRating == 'FD30') ? 'width: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '56':'70') : '70') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '58':'70') : '70')) .'px;
                                                 margin-left:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-41':'-5') : '-5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-45':'-5') : '-10')) .'px;
                                                margin-top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '134':'130') : '131') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '131':'130') : '129')) .'px;'
                                                 :
                                                'width:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '56':'70') : '70') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '58':'70') : '70')) .'px;
                                                 margin-left:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-41':'-5') : '-5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-45':'-5') : '-10')) .'px;
                                                margin-top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '134':'130') : '131') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '131':'130') : '129')) .'px;') . '" alt="" src="' . $VisionPanelGlazingImageStructure . '">


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
                                                ' . (($tt->FireRating == 'FD30') ? 'width:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '56':'67') : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '57':'70') : '70')) .'px;
                                                margin-left:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-13':'29') : '32') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-9':'29') : '34')) .'px;
                                                margin-top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '70':'38') : '63.5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '65':'38') : '57')) .'px;'
                                                :
                                                'width:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '56':'67') : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '57':'70') : '70')) .'px;
                                                margin-left:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-13':'29') : '32') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-9':'29') : '34')) .'px;
                                                margin-top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '70':'38') : '63.5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '65':'38') : '57')) .'px;') . '" alt="" src="' . $VisionPanelGlazingImageStructure . '">


                                            <img style="position: absolute;
                                                ' . (($tt->FireRating == 'FD30') ? 'top: 76px;width: 17px;right: 103px;' : 'top: 77px;right: 104px;width: 14px;') . 'z-index: -1;
                                                transform: rotate(270deg);height:40px;" src="' . $VisionPanelGlazingImageLeft . '" alt="">

                                        </div>
                                    </div>
                                    <div style="position: relative;  position: absolute; top: 8px; left: 630px;">
                                        <img style="width: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '136' : '95') .'px;
                                            position: relative;
                                            top:'. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '9' : '5') .'px;
                                            right: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '111' : '55') .'px; alt="" src="' . $FrameImageStructureRightLeaf2 . '">
                                    </div>';
                    } else {
                        $DoorFrameImage .= '<div style="position: absolute;
                                        top: 8px;
                                        left: 386px;">
                                        <img style="width:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '99' : '319') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '99' : '319')) .'px;
                                        position: relative;
                                        top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '21' : '4') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '21' : '4')) .'px;;
                                        right:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '256' : '35') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '256' : '35')) .'px;" alt="" src="' . $FullBlock . '">
                                    </div>';

                                    if (($tt->IntumescentLeapingSealLocation == 'Door' || $tt->IntumescentLeapingSealLocation == 'Frame') && in_array($tt->FireRating, ["FD30", "FD30s"])) {

                                        $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left:' .(($tt->FrameType !== null)? (($tt->FrameType == 'Scalloped')?'194':'640'):'634').'px;margin-top:' .(($tt->FrameType !== null)? (($tt->FrameType == 'Scalloped')?'-5':'-37'):'-37').'px;"></div>'; //intubacent fixes -385
                                    }
                    }

                    $DoorFrameImage .= '
                                    <div style="position: relative;  position: absolute; top: 8px; left: 722px;">';





                            // if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                            //     $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: -52px;margin-top: 31px;"></div>';
                            // } else
                            if (($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') && in_array($tt->FireRating, ["FD60", "FD60s"])) {

                                // class="'.$redstripLeftCommonClass.'"
                                $DoorFrameImage .= '<div   style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left:'.(($tt->Leaf2VisionPanel == 'Yes')? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-64' : '-52'):((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-489' : '-60')).'px;margin-top: 24px;"></div>
                                            <div  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: '.(($tt->Leaf2VisionPanel == 'Yes')? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-64' : '-52'):((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-489' : '-60')).'px;margin-top: 37px;"></div>';
                            }

                    if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {

                        if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                            if($tt->Leaf2VisionPanel == 'Yes'){
                            $DoorFrameImage .= '<div   style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->IntumescentLeapingSealLocation == 'Frame')? '-64':'-73') : '-50') .'px;
                                                margin-top: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '32' : '33') .'px;"></div>';
                            }
                        } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                            // class="'.$redstripRightCommonClass.'"
                            if($tt->Leaf2VisionPanel =! 'Yes'){


                                                        $DoorFrameImage .= '<div   style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: '. (
                                                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->IntumescentLeapingSealLocation == 'Frame')? '-63':'-74') : '-50') .'px;margin-top: 23px;"></div>
                                    <div  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: '. (
                                                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->IntumescentLeapingSealLocation == 'Frame')? '-63':'-74') : '-50') .'px;margin-top: 40px;"></div>';
                                                                }
                        }
                    }

                    if(empty($FrameTypeRight)){
                        $FrameTypeRight = '';
                    }
                    if($tt->FrameType !== null){
                    $DoorFrameImage .= '<div style="position: absolute; top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-6' : '18') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-6' : '18')) .'px;
                                                right:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '32':'32') : '20') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '33':'458') : '20')) .'px;">
                                            <img style="width:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '39' : '46') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '39' : '46')) .'px;" alt="" src="' . $FrameTypeRight . '">
                                        </div>';
                                            }
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
                                    'width: 66px;height: 50px;margin-left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-10' : '-5').'px;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '109' : ($GlazingSystems['GlazingBeadsPadding'] == 0 ?'133': '135')).'px;'
                                    :
                                   'width: 66px;height: 50px;margin-left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-10' : '-5').'px;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '109' : ($GlazingSystems['GlazingBeadsPadding'] == 0 ?'133': '135')).'px;')
                                    .'
                                " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                                    />





                            </div>

                            <div style="position: absolute;top:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-21' : '4').'px;left:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel =! 'Yes')? '-456' : '-229') : '-222').'px;">';

                            // if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {
                            //     if (in_array($tt->FireRating, ["FD30", "FD30s"])) {

                            //         $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 18px;"></div>';

                            //     } else if (in_array($tt->FireRating, ["FD60", "FD60s"])) {

                            //         $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 10px;"></div>
                            //                 <div class="'.$redstripLeftCommonClass.'_'.$sidelight.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 25px;"></div>';
                            //     }
                            // }


                            $DoorFrameImage .= '<img style="width: 46px;height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '107' : '117').'px;" alt="" src="'.$FrameTypeCommon.'" />
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
                                '.
                                    (($tt->FireRating == 'FD30') ?
                                    ' width: 66px;height: 52px;margin-left:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '0' : '-7').'px;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-110' :  ($GlazingSystems['GlazingBeadsPadding'] == 0 ? '-134':'-136.5')).'px;'
                                    :
                                    ' width: 66px;height: 52px;margin-left:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '0' : '-7').'px;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-110' :  ($GlazingSystems['GlazingBeadsPadding'] == 0 ? '-134':'-136.5')).'px;')
                                    .'" alt="" src="'.$VisionPanelGlazingImageStructure.'"
                                    >




                            </div>

                            <div style="position: absolute;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-21' : '4').'px;left:  '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-73' : '-66').'px;">';


                            // if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {


                            //     if (in_array($tt->FireRating, ["FD30", "FD30s"])) {

                            //         $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 18px;"></div>';

                            //     } else if (in_array($tt->FireRating, ["FD60", "FD60s"])) {

                            //         $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 10px;"></div>
                            //                 <div class="'.$redstripRightCommonClass.'_'.$sidelight.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 25px;"></div>';
                            //     }
                            // }
                            $DoorFrameImage .= '<img style="width: 46px; height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '107' : '117').'px;" alt="" src="'.$FrameTypeCommon.'">
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
        <!-- HEADER TABLE -->
        <table id="WithBorderNew" class="tbl1">
            <tbody>
                <tr>
                    <td class="marImg" rowspan="2">
                        <span>';
            if (!empty($comapnyDetail->ComplogoBase64)) {
                $elevTbl .= '<img src="' . $comapnyDetail->ComplogoBase64 . '" class="imgClass" alt="Logo"/>';
            } else {
                $elevTbl .= Base64Image('defaultImg');
            }
            $elevTbl .= '</span>
                    </td>
                    <td class="tbl_color"><span>Ref</span></td>
                    <td colspan="3"><span>' . $QuotationGenerationId . '</span></td>
                    <td class="tbl_color"><span>Project</span></td>
                    <td><span>' . $ProjectName . '</span></td>
                    <td class="tbl_color"><span>Prepared By</span></td>
                    <td><span>' . $Username . '</span></td>
                </tr>
                <tr>
                    <td class="tbl_color" style="width:25px;"><span>Revision</span></td>
                    <td style="width:20px;"><span>' . $version . '</span></td>
                    <td class="tbl_color" style="width:20px;"><span>Date</span></td>
                    <td><span>' . date('Y-m-d') . '</span></td>
                    <td class="tbl_color" style="width:10px;"><span>Customer</span></td>
                    <td><span>' . $customer->CstCompanyName . '</span></td>
                    <td class="tbl_color" style="width:60px;"><span>Quote name</span></td>
                    <td><span>' . $SalesContact . '</span></td>
                </tr>
            </tbody>
        </table>
    </td>
</tr>

<tr>
    <td colspan="2">
        <!-- PO TABLE -->
        <table id="WithBorderNew" class="tbl1 dashedborder">
            <tbody>
                <tr>
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

                <!-- SIGNATURE + DATE ROW -->
                <tr>
                    <td colspan="12" style="padding: 12px;">
                        <table style="width:100%; border-collapse: collapse;">
                            <tr>
                                <td style="text-align:left; font-size:16px; vertical-align:middle;">
                                    <b>Signature:</b>
                                    ';
                                if(!empty($fetchSignature->signature_path) && $clientclick == 'yes'){
                                    $elevTbl .= '<img style="width: 160px; margin-left:10px;" src="' . public_path($fetchSignature->signature_path) . '"/>';
                                }
                                $elevTbl .= '
                                                                </td>
                                                                <td style="text-align:right; font-size:16px; vertical-align:middle;">
                                                                    <b>Date:</b> ';
                                if($clientclick == 'yes'){
                                    $elevTbl .= \Carbon\Carbon::parse($fetchSignature->signed_at)->format('d-m-Y');
                                }
                                $elevTbl .= '
                                </td>
                            </tr>
                        </table>
                    </td>
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
if($tt->DoorsetType == "SD" &&  $tt->FrameType==null ){
    $elevTbl .= '<td style="width:20%;font-size: 7px !important;">
    <p class="visionpanel_t1_sd">' . $GlassType . '</p>
    <p class="visionpanel_t2_sd">' . $tt->glazingBeadsFixingDetail . '</p>
    <p class="visionpanel_t3_sd">' . $GlazingBeadSpecies . '<br>' . $tt->GlazingBeadsThickness . ' x ' . $tt->glazingBeadsHeight . 'mm</p>
    <p class="visionpanel_t4_sd">' . $ConfigurableItems . '<br>' . $tt->LeafThickness . 'mm</p>
    <p class="visionpanel_t5_sd">' . $tt->LeafThickness . '</p>
    <div class="doorImgBox">' . $VisionPanelGlazingImage . '</div>
</td>';
}else{
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
                        if($tt->FrameOnOff != 1){
                        $elevTbl .= '<tr>
                                    <td class="mytabledata_sd"  colspan="2" style="">
                                    <p class="frame_dd_t1_sd_' . $tt->FrameType . ' frame_dd_t1_sd_'.$sidelight.'" style="margin-left:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && (!empty($tt->Handing) && $tt->Handing != "Left")) ?  ( ($show->Leaf1VisionPanel == "Yes")?'637':'660' ) : '') .'px;
                                            margin-top:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && (!empty($tt->Handing) && $tt->Handing != "Left")) ?  ( ($show->Leaf1VisionPanel == "Yes")?'22px !important':'' ) : '') .'">' . $tt->FrameDepth . 'mm</p>
                                    <p class="frame_dd_t2_sd_' . $tt->FrameType . ' frame_dd_t2_sd_'.$sidelight.'" style="margin-left:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && (!empty($tt->Handing) && $tt->Handing != "Left")) ? ( ($show->Leaf1VisionPanel == "Yes")?'626':'653' ) : '') .'px">' . $tt->FrameThickness . 'mm</p>
                                    <p style="'.(($tt->FrameType == 'Rebated_Frame')?'display: none;':'').'" class="frame_dd_t3_sd_' . $tt->FrameType . ' frame_dd_t3_sd_'.$sidelight.'">' . $FrameTypeWidth . 'mm</p>
                                    <p class="frame_dd_t4_sd_' . $tt->FrameType . ' frame_dd_t4_sd_'.$sidelight.'" style="margin-left:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && (!empty($tt->Handing) && $tt->Handing != "Left")) ?   ( ($show->Leaf1VisionPanel == "Yes")?'587':'613' ) : '') .'px;
                                            margin-top:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && (!empty($tt->Handing) && $tt->Handing != "Left")) ?  ( ($show->Leaf1VisionPanel == "Yes")?'158px !important':'' ) : '') .' ">' . $FrameTypeHeight . 'mm</p>
                                    <p class="frame_dd_t5_sd_' . $tt->FrameType . ' frame_dd_t5_sd_'.$sidelight.'" style="margin-left:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && (!empty($tt->Handing) && $tt->Handing != "Left")) ?  ( ($show->Leaf1VisionPanel == "Yes")?'530':'550' ) : '') .'px">' . $tt->LeafThickness . '</p>';
                        }

                      if (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') {
                           $elevTbl .= '
                                <p class="scalloped-'.$tt->Handing.'-1'.(($show->Leaf1VisionPanel == "Yes" && $tt->Handing != "Left")?'-VP':'').'"  ></p>
                                <p class="scalloped-'.$tt->Handing.'-2'.(($show->Leaf1VisionPanel == "Yes" && $tt->Handing != "Left")?'-VP':'').'" >'.$tt->ScallopedWidth.'</p>
                                <p class="scalloped-'.$tt->Handing.'-3'.(($show->Leaf1VisionPanel == "Yes" && $tt->Handing != "Left")?'-VP':'').'" ></p>';
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
                        if($tt->FrameOnOff != 1){
                        $elevTbl .= '<tr>
                                    <td class="mytabledata" colspan="2" style="">

                                    <p class="frame_dd_t1 frame_dd_t1_' . $tt->FrameType.' '.$sidelight.'">' . $tt->FrameDepth . 'mm</p>
                                    <p class="frame_dd_t2 frame_dd_t2_' . $tt->FrameType . ' '.$sidelight.'">' . $tt->FrameThickness . 'mm</p>
                                    <p style="'.(($tt->FrameType == 'Rebated_Frame')?'display: none;':'').'" class="frame_dd_t3 frame_dd_t3_' . $tt->FrameType . ' '.$sidelight.'">' . $FrameTypeWidth . 'mm</p>
                                    <p class="frame_dd_t4 frame_dd_t4_' . $tt->FrameType . ' '.$sidelight.'">' . $FrameTypeHeight . 'mm</p>
                                    <p class="frame_dd_t5 frame_dd_t5_' . $tt->FrameType . ' '.$sidelight.'">' . $tt->LeafThickness . '</p>';
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
                if($FireRatingActualValue == 'NFR'){
                    $gi = Option::where("configurableitems", $configurationItem)
                    ->where("OptionSlug", "Glass_Integrity")
                    ->where("OptionKey", $tt->GlassIntegrity)->first();
                }
                else{
                    $gi = Option::where("configurableitems", $configurationItem)
                    ->where("firerating", $FireRatingActualValue)
                    ->where("OptionSlug", "Glass_Integrity")
                    ->where("OptionKey", $tt->GlassIntegrity)->first();
                }

                $GlassIntegrity = $gi->OptionValue;
            }

            $glazing_beads_word = in_array($configurationItem, [1,2,7,8]) ? 'side_light_glazing_beads' : 'leaf1_glazing_beads';

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
                if($Intumescentseals){
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
                if($configurationDoor === 'VicaimaDoorCore' || $configurationDoor === 'MMM'){
                    $op = GlassType::leftJoin('selected_glass_type', function ($join) use ($id): void {
                        $join->on('glass_type.id', '=', 'selected_glass_type.glass_id')
                            ->where('selected_glass_type.editBy', '=', $id);
                    })->where('glass_type.'.$configurationDoor,$tt->configurableitems)->where('glass_type.Key',$tt->SideLight1GlassType)->first();
                    $sl1glasstype = $op->GlassType ?? '';
                }
                else{
                    $op = OverpanelGlassGlazing::leftJoin('selected_overpanel_glass_glazing', function ($join) use ($id): void {
                        $join->on('overpanel_glass_glazing.id', '=', 'selected_overpanel_glass_glazing.glass_glazing_id')
                            ->where('selected_overpanel_glass_glazing.editBy', '=', $id);
                    })->where('overpanel_glass_glazing.'.$configurationDoor,$tt->configurableitems)->where('overpanel_glass_glazing.Key',$tt->SideLight1GlassType)->first();
                    $sl1glasstype = $op->GlassType ?? '';
        }

            }

            $beadingtype = 'N/A';
            if (!empty($tt->SideLight2BeadingType)) {
                $op2 = Option::where(['configurableitems' => $configurationItem, 'UnderAttribute' => $FireRatingActualValue, 'OptionSlug' => $glazing_beads_word, 'OptionKey' => $tt->SideLight2BeadingType])->first();
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

            $OPFLHeight = 'N/A';
            if ($tt->Overpanel == 'Overpanel' || $tt->Overpanel == 'Fan_Light') {
                $opHeight = is_numeric($tt->OPHeigth) ? $tt->OPHeigth : 0;
                $beadThickness = is_numeric($tt->OpBeadThickness) ? $tt->OpBeadThickness : 0;
                $gap = is_numeric($tt->GAP) ? $tt->GAP : 0;

                $OPFLHeight = $opHeight - $beadThickness - $beadThickness;
            }

            $OPFLWeidth = 'N/A';
            if ($tt->Overpanel == 'Overpanel' || $tt->Overpanel == 'Fan_Light') {
                $frameWidth = is_numeric($tt->FrameWidth) ? $tt->FrameWidth : 0;
                $beadThickness = is_numeric($tt->OpBeadThickness) ? $tt->OpBeadThickness : 0;
                // old formula before 15-09-2025
                // $OPFLWeidth = $frameWidth - $beadThickness - $beadThickness;
                //new formula after 15-09-2025
                //$OPFLWeidth = $frameWidth + $SideLight1Width + $SideLight2Width - $beadThickness - $beadThickness;
                // NEW DEVELOPMENT JFDS 1059 25-09-2025
                $OPFLWeidth = $frameWidth - $beadThickness - $beadThickness;
            }

            $ElevSL1Width = 'N/A';
            if ($tt->SideLight1 == 'Yes') {
                //NEW DEVELOPMENT JFDS 1059 25-09-2025
                $SLight1Width= is_numeric($tt->SL1Width) ? $tt->SL1Width : 0;
                $SideLight1FrameThickness = is_numeric($tt->SideLight1FrameThickness) ? $tt->SideLight1FrameThickness : 0;
                $ElevSL1Width = $SLight1Width - $SideLight1FrameThickness - $SideLight1FrameThickness;
            }

            $ElevSL1Height = 'N/A';
            if ($tt->SideLight1 == 'Yes') {
                //NEW DEVELOPMENT JFDS 1059 25-09-2025
                $SLight1Height = is_numeric($tt->SL1Height) ? $tt->SL1Height : 0;
                $SideLight1FrameThickness = is_numeric($tt->SideLight1FrameThickness) ? $tt->SideLight1FrameThickness : 0;
                $ElevSL1Height = $SLight1Height - $SideLight1FrameThickness - $SideLight1FrameThickness;
            }

            $ElevSL2Width = 'N/A';
            if ($tt->SideLight1 == 'Yes') {
                //NEW DEVELOPMENT JFDS 1059 25-09-2025
                $SLight2Width= is_numeric($tt->SL2Width) ? $tt->SL2Width : 0;
                $SideLight1FrameThickness = is_numeric($tt->SideLight1FrameThickness) ? $tt->SideLight1FrameThickness : 0;
                $ElevSL2Width = $SLight2Width - $SideLight1FrameThickness - $SideLight1FrameThickness;
            }

            $ElevSL2Height = 'N/A';
            if ($tt->SideLight1 == 'Yes') {
                //NEW DEVELOPMENT JFDS 1059 25-09-2025
                $SLight2Height = is_numeric($tt->SL2Height) ? $tt->SL2Height : 0;
                $SideLight2FrameThickness = is_numeric($tt->SideLight2FrameThickness) ? $tt->SideLight2FrameThickness : 0;
                $ElevSL2Height = $SLight2Height - $SideLight2FrameThickness - $SideLight2FrameThickness;
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
                if($tt->FrameOnOff != 1){
                    $Handing = '';
                    if($tt->Handing == 'Left_Hand_Master_Right_Hand_Slave'){
                        $Handing = 'Primary Leaf Left';
                    }else if($tt->Handing == 'Right_Hand_Master_Left_Hand_Slave'){
                        $Handing = 'Primary Leaf Right';
                    }else{
                        $Handing = $tt->Handing;
                    }
                    $elevTbl .=  '<tr>
                                    <td class="dicription_grey">Handing</td>
                                    <td class="dicription_blank">' . $Handing . '</td>
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
            if($tt->FrameOnOff != 1){
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
            if($quotaion->configurableitems == 4){
                    $elevTbl .= '
                                <tr>
                                    <td class="dicription_grey">Door leaf Finish</td>
                                    <td class="dicription_blank">' . $DoorLeafFinish . $DoorLeafFinishColor . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Leaf Type</td>
                                    <td class="dicription_blank">' . $tt->LeafConstruction . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Product code</td>
                                    <td class="dicription_blank">' . $tt->DoorDimensionsCode .'</td>
                                </tr>';

                    if ($tt->DoorsetType != 'SD') {
                        $elevTbl .= '<tr>
                                        <td class="dicription_grey">Product code2/Size </td>
                                        <td class="dicription_blank">' . $tt->DoorDimensionsCode2 .' ('. $leafWidth2 .'<span style="font-weight: bold; font-size: 1.2em;">×</span>'. $LeafHeight  .')</td>
                                    </tr>';
                    }

                    $elevTbl .= '<tr>
                                    <td class="dicription_grey">Decorative Groves</td>
                                    <td class="dicription_blank">' . $tt->groovesNumber . '</td>
                                </tr>';
            }else{
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
            if($tt->FrameOnOff != 1){
                $elevTbl .=  '<table id="WithBorder">
                                <tbody>
                                    <tr>
                                        <th class="tblTitle">Frame</th>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Four Sided Frame</td>
                                        <td class="dicription_blank">' . (($tt->FourSidedFrame == 1)?'Yes':'No') . '</td>
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
                if($tt->FrameOnOff != 1){
                            $elevTbl .=  '<table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Overpanel/Fanlight Section</th>
                                </tr>';
                                if($tt->Overpanel == 'Overpanel'){
                                $elevTbl .= '
                                <tr>
                                    <td class="dicription_grey">OP Panel Width</td>
                                    <td class="dicription_blank">' . $OPFLWeidth . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">OP Panel Height</td>
                                    <td class="dicription_blank">' . $OPFLHeight . '</td>
                                </tr>';
                                } else if($tt->Overpanel == 'Fan_Light'){
                                $elevTbl .= '
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
                                </tr>
                                <tr>
                                    <td class="dicription_grey">FL Width</td>
                                    <td class="dicription_blank">' . $OPFLWeidth . '</td>
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
                                if($tt->SideLight1 == 'Yes'){
                                    $elevTbl .= '
                                    <tr>
                                        <td class="dicription_grey">SL1 Width</td>
                                        <td class="dicription_blank">' . $ElevSL1Width . '</td>
                                    </tr>
                                     <tr>
                                        <td class="dicription_grey">SL1 Height</td>
                                        <td class="dicription_blank">' . $ElevSL1Height . '</td>
                                    </tr>
                                    ';
                                    }
                                     if($tt->SideLight2 == 'Yes'){
                                    $elevTbl .= '
                                     <tr>
                                        <td class="dicription_grey">SL2 Width</td>
                                        <td class="dicription_blank">' . $ElevSL2Width . '</td>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">SL2 Height</td>
                                        <td class="dicription_blank">' . $ElevSL2Height . '</td>
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
                        if($tt->FrameOnOff != 1){
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


            if($isActive == true){

            $rating = 60;     // example
            $status = 'green';  // example

            // Outer color (fire rating)
            switch ($rating) {
                case 30: $outerColor = '#f4d23c'; break; // Yellow
                case 60: $outerColor = '#1f3a93'; break; // Blue
                case 90: $outerColor = '#6b3e26'; break; // Brown
                case 120: $outerColor = '#000000'; break; // Black
                default: $outerColor = '#cccccc';
            }

            // Inner color (status)
            switch ($status) {
                case 'red': $innerColor = '#ef2b2d'; break;
                case 'green': $innerColor = '#2ecc71'; break;
                case 'orange': $innerColor = '#f39c12'; break;
                case 'silver': $innerColor = '#bdc3c7'; break;
                case 'gold': $innerColor = '#f1c40f'; break;
                default: $innerColor = '#999999';
            }

            $outerColor = '';
            $innerColor = '';

            $outerColor2 = '';
            $innerColor2 = '';

            if(($tt->FireRating == 'FD30' || $tt->FireRating == 'FD30s') && $tt->IronmongerySet == 'No' && $tt->Leaf1VisionPanel == 'No'){
                $outerColor = '#f4d23c'; //yellow
                $innerColor = '#2ecc71'; //green

            }

            if(($tt->FireRating == 'FD30' || $tt->FireRating == 'FD30s') && $tt->IronmongerySet == 'No' && $tt->Leaf1VisionPanel == 'Yes'){
                $outerColor = '#f4d23c'; //yellow
                $innerColor = '#2ecc71'; //green

                $outerColor2 = '#f4d23c'; //yellow
                $innerColor2 = '#f39c12'; //orange
            }

            if(($tt->FireRating == 'FD30' || $tt->FireRating == 'FD30s') && $tt->IronmongerySet == 'Yes' && $tt->Leaf1VisionPanel == 'Yes'){
                $outerColor = '#f4d23c'; //yellow
                $innerColor = '#bdc3c7'; //silver

                $outerColor2 = '#f4d23c'; //yellow
                $innerColor2 = '#f39c12'; //orange
            }

            if(($tt->FireRating == 'FD60' || $tt->FireRating == 'FD60s') && $tt->IronmongerySet == 'No' && $tt->Leaf1VisionPanel == 'No'){
                $outerColor = '#1f3a93'; //Blue
                $innerColor = '#2ecc71'; //green
            }

            if(($tt->FireRating == 'FD60' || $tt->FireRating == 'FD60s') && $tt->IronmongerySet == 'No' && $tt->Leaf1VisionPanel == 'Yes'){
                $outerColor = '#1f3a93'; //Blue
                $innerColor = '#2ecc71'; //green

                $outerColor2 = '#1f3a93'; //Blue
                $innerColor2 = '#f39c12'; //orange
            }

            if(($tt->FireRating == 'FD60' || $tt->FireRating == 'FD60s') && $tt->IronmongerySet == 'Yes' && $tt->Leaf1VisionPanel == 'Yes'){
                $outerColor = '#1f3a93'; //Blue
                $innerColor = '#bdc3c7'; //silver

                $outerColor2 = '#1f3a93'; //Blue
                $innerColor2 = '#f39c12'; //orange
            }

                $elevTbl .= ' <div class="tbl_prn">
                <div style="margin:0 auto;"><h3 style="text-align:center;">Quality Control </h3></div>
                <div class="fr_d_tbl doorImgBox" style="display:flex; justify-content:center;">

                    <table id="NoBorder" style="margin-top:1rem;margin-bottom: 15px;" class="mt-4">
                        <tr>
                            <td colspan="2">
                                <table id="WithBorder" class="tbl1">
                                    <tbody>
                                        <tr>
                                            <td class="marImg" rowspan="2">
                                                <span>';
                    if (!empty($comapnyDetail->ComplogoBase64)) {
                        $elevTbl .= '<img src="' . htmlspecialchars((string) $comapnyDetail->ComplogoBase64) . '" class="imgClass" alt="Logo"/>';
                    } else {
                        $elevTbl .= Base64Image('defaultImg');
                    }

                    $elevTbl .= '</span>
                                            </td>
                                            <td class="tbl_color"><span>Ref</span></td>
                                            <td colspan="3"><span>' . htmlspecialchars((string) $QuotationGenerationId) . '</span></td>
                                            <td class="tbl_color"><span>Project</span></td>
                                            <td><span>' . htmlspecialchars((string) $ProjectName) . '</span></td>
                                            <td class="tbl_color"><span>Prepared By</span></td>
                                            <td><span>' . htmlspecialchars((string) $Username) . '</span></td>
                                        </tr>
                                        <tr>
                                            <td class="tbl_color" style="width:25px;padding-right:5px;"><span>Revision</span></td>
                                            <td style="width:20px;"><span>' . htmlspecialchars((string) $version) . '</span></td>
                                            <td class="tbl_color" style="width:20px;padding-right:5px;"><span>Date</span></td>
                                            <td><span>' . date('Y-m-d') . '</span></td>
                                            <td class="tbl_color" style="width:10px;padding-right:5px;"><span>Customer</span></td>
                                            <td><span>' . htmlspecialchars((string) $customer->CstCompanyName) . '</span></td>
                                            <td class="tbl_color" style="width:60px;padding-right:5px;"><span>Quote name</span></td>
                                            <td><span>' . htmlspecialchars((string) $SalesContact) . '</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="fr_d_tbl" style=" margin: 0 auto;">

                    <div style="text-align:center; margin-bottom: 20px;">

                        <table style="width: 800px; margin: 0 auto; border: 1px solid #000; border-collapse: collapse;">
                            <tr>';

                           if (!empty($outerColor) && !empty($innerColor) && !empty($companyCode) && $tt->FireRating !== 'NFR') {
                                $elevTbl .= '<td style="border:1px solid #000; padding:10px; text-align:center; width:120px;">

                                            <div style="width:90px; height:90px; margin:auto; position:relative;">

                                                <!-- Outer Circle -->
                                                <div style="
                                                  width: 100px;
                                                  height: 100px;
                                                  background:' . $outerColor . ';
                                                  border-radius: 50%;
                                                  position: absolute;
                                                  top: -6px;
                                                "></div>

                                                <!-- Triangle -->
                                                <div style="
                                                  width: 0;
                                                  height: 0;
                                                  border-left: 40px solid transparent;
                                                  border-right: 40px solid transparent;
                                                  border-bottom: 54px solid ' . $innerColor . ';
                                                  position: absolute;
                                                  top: 4px;
                                                  left: 9px;
                                                "></div>

                                                <!-- Trunk -->
                                                <div style="
                                                    width:20px;
                                                    height:26px;
                                                    background:' . $innerColor . ';
                                                    position:absolute;
                                                    top:52px;
                                                    left:39px;
                                                "></div>

                                                <!-- Number -->
                                                <div style="
                                                position: absolute;
                                                left: 4px;
                                                top: 38px;
                                                width: 100%;
                                                text-align: center;
                                                color: #050505;
                                                font-size: 13px;
                                                font-weight: bold;
                                                ">
                                                    ' . htmlspecialchars($companyCode) . '
                                                </div>

                                            </div>

                                        </td>';
                            }

                           if (!empty($outerColor2) && !empty($innerColor2) && !empty($companyCode) && $tt->FireRating !== 'NFR') {
                                $elevTbl .= '<td style="border:1px solid #000; padding:10px; text-align:center; width:120px;">

                                    <div style="width:90px; height:90px; margin:auto; position:relative;">

                                        <!-- Outer Circle -->
                                        <div style="
                                            width: 100px;
                                            height: 100px;
                                            background:' . $outerColor2 . ';
                                            border-radius: 50%;
                                            position: absolute;
                                            top: -6px;
                                        "></div>

                                        <!-- Triangle -->
                                        <div style="
                                            width: 0;
                                            height: 0;
                                            border-left: 40px solid transparent;
                                            border-right: 40px solid transparent;
                                            border-bottom: 54px solid ' . $innerColor2 . ';
                                            position: absolute;
                                            top: 4px;
                                            left: 9px;
                                        "></div>

                                        <!-- Trunk -->
                                        <div style="
                                            width:20px;
                                            height:26px;
                                            background:' . $innerColor2 . ';
                                            position:absolute;
                                            top:52px;
                                            left:39px;
                                        "></div>

                                        <!-- Number -->
                                        <div style="
                                        position: absolute;
                                        left: 4px;
                                        top: 38px;
                                        width: 100%;
                                        text-align: center;
                                        color: #050505;
                                        font-size: 13px;
                                        font-weight: bold;
                                        ">
                                            ' . htmlspecialchars($companyCode) . '
                                        </div>

                                    </div>

                                </td>';
                            }

                            $elevTbl .= '<td style="background:#f2f2f2; padding:8px;">SELECT<br>Door Type</td>
                                <td style="padding:8px;"><b>Type ' . htmlspecialchars($tt->DoorType) . '</b></td>
                            </tr>
                        </table>
                    </div>

                    <div style="margin: 0 auto; width: 95%;">
                        <table style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
                            <thead style="background: #f9f9f9;">
                                <tr>
                                    <th style="border: 1px solid #000; padding: 6px;">Door / Screen No</th>
                                    <th style="border: 1px solid #000; padding: 6px;">Assign Plot Ref</th>
                                    <th style="border: 1px solid #000; padding: 6px;">Certification No</th>
                                    <th style="border: 1px solid #000; padding: 6px;">Quality Check- CNC</th>
                                    <th style="border: 1px solid #000; padding: 6px;">Quality Check- VP</th>
                                    <th style="border: 1px solid #000; padding: 6px;">Quality Check- Assembly</th>
                                    <th style="border: 1px solid #000; padding: 6px;">Door Plug</th>
                                    <th style="border: 1px solid #000; padding: 6px;">Frame Plug</th>
                                    <th style="border: 1px solid #000; padding: 6px;">Glass Stamp Visible</th>
                                </tr>
                            </thead>
                            <tbody>';
                foreach ($DoorNumber as $bb) {
                    $elevTbl .= '<tr>
                        <td style="border: 1px solid #000; padding: 5px;">' . $bb->doorNumber . '</td>
                        <td style="border: 1px solid #000; padding: 5px;">' . $bb->plot_ref_no . '</td>
                        <td style="border: 1px solid #000; padding: 5px;">' . $bb->certification_no . '</td>
                        <td style="border: 1px solid #000; padding: 5px;"></td>
                        <td style="border: 1px solid #000; padding: 5px;"></td>
                        <td style="border: 1px solid #000; padding: 5px;"></td>
                        <td style="border: 1px solid #000; padding: 5px;"></td>
                        <td style="border: 1px solid #000; padding: 5px;"></td>
                        <td style="border: 1px solid #000; padding: 5px;"></td>
                    </tr>';
                }
                $elevTbl .= '
                            </tbody>
                        </table>
                    </div>
                </div>';
                if ($PageBreakCounts < $TotalItems) {
                    $elevTbl .= '<div class="page-break"></div>';
                }


                $PageBreakCounts++;
            }

        }

        // return $elevTbl;
        // return view('Company.pdf_files.elevationDrawing', compact('elevTbl'));
        $pdf6 = PDF::loadView('Company.pdf_files.elevationDrawing', ['elevTbl' => $elevTbl]);
        $path6 = public_path() . '/allpdfFile';
        $fileName6 = $id . '6' . '.' . 'pdf';
        $pdf6->save($path6 . '/' . $fileName6);
        // back page design



        // end

        // elevation drawing for side screen
        $elevSideScreenTbl = '';
        $eds = SideScreenItem::join('side_screen_item_master', 'side_screen_items.id', 'side_screen_item_master.ScreenId')->where(['side_screen_items.QuotationId' => $quatationId])
        ->where(['side_screen_items.VersionId' => $versionID])
        ->select('side_screen_items.*')
        ->groupBy('side_screen_item_master.ScreenID')
        ->get();
        $TotalItems = count($eds->toArray());


        $PageBreakCount = 1;

        foreach ($eds as $tt) {
            // dd($tt);
            $countDoorNumberSide = SideScreenItemMaster::where('ScreenId', $tt->id)->count();
            $DoorNumberS = SideScreenItemMaster::where('ScreenId', $tt->id)->get();
            $doorNoS = '';
            foreach ($DoorNumberS as $bb) {
                $doorNoS .= '<span style="padding-left:5px;">' . $bb->screenNumber . '</span>';
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
                $svgFileS = str_contains((string) $tt->SvgImage, '.png') ? URL('/') . '/uploads/files/' . $tt->SvgImage : $tt->SvgImage;
            } else {
                $svgFileS = URL('/') . '/uploads/files/no_image_prod.jpg';
            }

            $IsLeafEnabled = 'colspan="2"';
            $elevSideScreenTbl .=
                '
                <div id="headText">
                    <b>Elevation Drawing Side Screen</b>
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
                $elevSideScreenTbl .=
                    '<img src="' . $comapnyDetail->ComplogoBase64 . '" class="imgClass" alt="Logo"/>';
            } else {
                $elevSideScreenTbl .= Base64Image('defaultImg');
            }

            $elevSideScreenTbl .=
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
                                                <td class="tbl_color"><span>Revision</span></td>
                                                <td colspan="3"><span>' . $version . '</span></td>
                                                <td class="tbl_color"><span>Customer</span></td>
                                                <td><span>' . $customer->CstCompanyName . '</span></td>
                                                <td class="tbl_color"><span>Quote name</span></td>
                                                <td><span>' . $SalesContact . '</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>';
            $elevSideScreenTbl .= '<tr>';


                $elevSideScreenTbl .= '<td ' . $IsLeafEnabled . '>
                <div class="doorImgBox">

                    <img src="' . $svgFileS . '" class="doorImg" style="">
                </div>
            </td>
            </tr>';

            $SideScreenChamfered = \Config::get('constants.base64Images.SideScreenChamfered');
            $SideScreenSquare = \Config::get('constants.base64Images.SideScreenSquare');
            $SelectedFrameMaterial = LippingSpecies::where("id", $tt->FrameMaterial)->first();
            // dd($tt->FrameMaterial);
            // dd($SelectedFrameMaterial);
            $FrameMaterials = $SelectedFrameMaterial != null ? $SelectedFrameMaterial->SpeciesName : 'N/A';

            if($tt->GlazingBeadShape == 'Square'){
                $elevSideScreenTbl .= '<td style=" width:300px;margin: 0 auto;position: relative;">
                <div class="">
                    <img style="width: 220px; position: absolute;top: -160px;height: 320px;right:65px;z-index: -1;" src="' . $SideScreenSquare . '" class="" >
                </div>

            </td>

            </tr>';
            $elevSideScreenTbl .= '<td style="width:20%;font-size: 7px !important;">
            <p class="visionpanel_t1">' . $tt->SinglePane . '</p>
            <p class="visionpanel_t2">' . $tt->GlazingSystemFixingDetail . '</p>
            <p class="visionpanel_t3">' . $tt->GlazingBeadShape . '<br>' . $tt->GlazingBeadWidth . ' x ' . $tt->GlazingBeadHeight . 'mm</p>
            <p class="visionpanel_t4">' . $FrameMaterials . '</p>
        </td>';
            }

            if($tt->GlazingBeadShape == 'Chamfer'){
                $elevSideScreenTbl .= '<td style=" width:300px;margin: 0 auto;position: relative;">
                <div class="">
                    <img style="width: 220px; position: absolute;top: -160px;height: 320px;right:65px;z-index: -1;" src="' . $SideScreenChamfered . '" class="" >
                </div>

            </td>
            </tr>';
            $elevSideScreenTbl .= '<td style="width:20%;font-size: 7px !important;">
            <p class="visionpanel_t1">' . $tt->SinglePane . '</p>
            <p class="visionpanel_t2">' . $tt->GlazingSystemFixingDetail . '</p>
            <p class="visionpanel_t3">' . $tt->GlazingBeadShape . '<br>' . $tt->GlazingBeadWidth . ' x ' . $tt->GlazingBeadHeight . 'mm</p>
            <p class="visionpanel_t4">' . $FrameMaterials . '</p>
        </td>';
            }


            $elevSideScreenTbl .= '</table>
                    </div>
                    <div id="section-right">
                        <table id="WithBorder" class="tbl3">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">General</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Screen Type</td>
                                    <td class="dicription_blank">' . $tt->ScreenType . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Description</td>
                                    <td class="dicription_blank">Single Screen</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Fire Rating</td>
                                    <td class="dicription_blank">' . $tt->FireRating . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">SO WIdth</td>
                                    <td class="dicription_blank">' . $tt->SOWidth . '</td>
                                </tr>';

                    $elevSideScreenTbl .=  '<tr>
                                    <td class="dicription_grey">SO Height</td>
                                    <td class="dicription_blank">' . $tt->SOHeight . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">SO Depth</td>
                                    <td class="dicription_blank">' . $tt->SODepth . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Tolerance</td>
                                    <td class="dicription_blank">' . $tt->Tolerance . '</td>
                                </tr>';

                    $elevSideScreenTbl .= '<tr>
                                    <td class="dicription_grey">Frame Thickness</td>
                                    <td class="dicription_blank">' . $tt->FrameThickness . '</td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Structural Opening & Door Leaf Dimensions</th>
                                </tr>';
                $elevSideScreenTbl .=  '  <tr>
                                    <td class="dicription_grey">Frame Material</td>
                                    <td class="dicription_blank">' . lippingName($tt->FrameMaterial) . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Frame Finish</td>
                                    <td class="dicription_blank">' . $tt->Finish . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Frame WIdth</td>
                                    <td class="dicription_blank">' . $tt->FrameWidth . '</td>
                                </tr>';

            $elevSideScreenTbl .=  '  <tr>
                                    <td class="dicription_grey">Frame Height</td>
                                    <td class="dicription_blank">' . $tt->FrameHeight . '</td>
                                </tr>';

            $elevSideScreenTbl .=         '<tr>
                                    <td class="dicription_grey">Frame Depth</td>
                                    <td class="dicription_blank">' . $tt->FrameDepth . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glazing Bead Shape</td>
                                    <td class="dicription_blank">' . $tt->GlazingBeadShape . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glazing Bead Height</td>
                                    <td class="dicription_blank">' . $tt->GlazingBeadHeight . '</td>
                                </tr>';

            $elevSideScreenTbl .=         '<tr>
                                    <td class="dicription_grey">Glazing Bead Width</td>
                                    <td class="dicription_blank">' . $tt->GlazingBeadWidth . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glazing Bead Species</td>
                                    <td class="dicription_blank">' .  lippingName($tt->GlazingBeadMaterial). '</td>
                                </tr>';

            $elevSideScreenTbl .=         '
                                <tr>
                                    <td class="dicription_grey">Transom Width</td>
                                    <td class="dicription_blank">' . $tt->TransomWidth1 . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Transom 1 Thickness</td>
                                    <td class="dicription_blank">' . $tt->Transom1Thickness . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Transom 2 Thickness</td>
                                    <td class="dicription_blank">' . $tt->Transom2Thickness . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Transom 3 Thickness</td>
                                    <td class="dicription_blank">' . $tt->Transom3Thickness . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Transom Material</td>
                                    <td class="dicription_blank">' .lippingName($tt->TransomMaterial) . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Mullion 1 Thickness</td>
                                    <td class="dicription_blank">' .$tt->Mullion1Thickness . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Mullion 2 Thickness</td>
                                    <td class="dicription_blank">' .$tt->Mullion2Thickness . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Mullion 3 Thickness</td>
                                    <td class="dicription_blank">' .$tt->Mullion3Thickness . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Transom Finish </td>
                                    <td class="dicription_blank">' . $tt->Finish . '</td>
                                </tr>
                            </tbody>
                        </table>';
            $elevSideScreenTbl .=  '<table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Lipping And Intumescent</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glass Type</td>
                                    <td class="dicription_blank">' . $tt->SinglePane  . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glazing System</td>
                                    <td class="dicription_blank">' . $tt->GlazingSystem . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glass Liner</td>
                                    <td class="dicription_blank">' . $tt->GlassLiner . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Sub Frame Bottom Thickness</td>
                                    <td class="dicription_blank">' . $tt->SubFrameBottomThickness . '</td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Vision Panel</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Sub Frame Species </td>
                                    <td class="dicription_blank">' . lippingName($tt->SubFrameMaterial) . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Acoustics </td>
                                    <td class="dicription_blank">' . $tt->Acoustic . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Special Feature </td>
                                    <td class="dicription_blank">' .$tt->SpecialFeatuers . '</td>
                                </tr>
                            </tbody>
                        </table>';

                        $elevSideScreenTbl .=  '</div></div>
                    <div id="footer">
                        <h3><b>Total Side Screens: ' . $countDoorNumberSide . ',Screen No-' . $doorNoS . '</b></h3>

                    </div>
                ';

            if ($PageBreakCount < $TotalItems) {
                $elevSideScreenTbl .= '<div class="page-break"></div>';
            }

            $PageBreakCount++;
        }

        //  return $elevSideScreenTbl;
        // return view('Company.pdf_files.elevationDrawingSideScreen', compact('elevSideScreenTbl'));
        $pdf8 = PDF::loadView('Company.pdf_files.elevationDrawingSideScreen',['elevSideScreenTbl' => $elevSideScreenTbl]);
        $path8 = public_path() . '/allpdfFile';
        $fileName8 = $id . '8' . '.' . 'pdf';
        $pdf8->save($path8 . '/' . $fileName8);
        // end

        $fileName7 = '';
        if($IronmongeryData !== '' && $IronmongeryData !== '0'){
            $pdf7 = PDF::loadView('Company.pdf_files.IronmongeryData', ['IronmongeryData' => $IronmongeryData]);
            $path7 = public_path() . '/allpdfFile';
            $fileName7 = $id . '7' . '.' . 'pdf';
            // return $pdf7->download('IronmongeryData.pdf');
            $pdf7->save($path7 . '/' . $fileName7);
        }

        // Document PDF
        $pdf_document = SettingPDFDocument::where('UserId', $id)->first();
        $pdf5 = PDF::loadView('Company.pdf_files.documentpdf', ['pdf_document' => $pdf_document]);
        $path5 = public_path() . '/allpdfFile';
        $fileName5 = $id . '5' . '.' . 'pdf';
        $pdf5->save($path5 . '/' . $fileName5);



        $PDFfilename = public_path() . '/allpdfFile' . '/' . $quotaion->QuotationGenerationId . '_' . $version . '.pdf';

        if($IronmongeryData !== '' && $IronmongeryData !== '0'){
                 $pdfFiles = [
                    public_path() . '/allpdfFile' . '/' . $fileName1,
                    public_path() . '/allpdfFile' . '/' . $fileName2,
                    public_path() . '/allpdfFile' . '/' . $fileName2_1,
                    public_path() . '/allpdfFile' . '/' . $fileName3,
                    public_path() . '/allpdfFile' . '/' . $fileName4_2,
                    public_path() . '/allpdfFile' . '/' . $fileName4,
                    public_path() . '/allpdfFile' . '/' . $fileName9,
                    public_path() . '/allpdfFile' . '/' . $fileName6,
                    public_path() . '/allpdfFile' . '/' . $fileName8,
                    public_path() . '/allpdfFile' . '/' . $fileName7,
                    public_path() . '/allpdfFile' . '/' . $fileName5,
                ];
            }
        else{
            $pdfFiles = [
                    public_path() . '/allpdfFile' . '/' . $fileName1,
                    public_path() . '/allpdfFile' . '/' . $fileName2,
                    public_path() . '/allpdfFile' . '/' . $fileName2_1,
                    public_path() . '/allpdfFile' . '/' . $fileName3,
                    public_path() . '/allpdfFile' . '/' . $fileName4_2,
                    public_path() . '/allpdfFile' . '/' . $fileName4,
                    public_path() . '/allpdfFile' . '/' . $fileName9,
                    public_path() . '/allpdfFile' . '/' . $fileName6,
                    public_path() . '/allpdfFile' . '/' . $fileName8,
                    public_path() . '/allpdfFile' . '/' . $fileName5,
                ];
            }

        if(count($ed) == 0){
            $pdfFiles = [
                public_path() . '/allpdfFile' . '/' . $fileName1,
                public_path() . '/allpdfFile' . '/' . $fileName2,
                public_path() . '/allpdfFile' . '/' . $fileName2_1,
                public_path() . '/allpdfFile' . '/' . $fileName9,
                public_path() . '/allpdfFile' . '/' . $fileName8,
                public_path() . '/allpdfFile' . '/' . $fileName5,
            ];
        }

        $isActive = filter_var($isActive, FILTER_VALIDATE_BOOLEAN);

        if ($isActive) {
            if(count($eds) == 0){
                $pdfFiles = [
                    public_path() . '/allpdfFile' . '/' . $fileName6
                ];
            } else if(count($ed) == 0) {
                $pdfFiles = [
                    public_path() . '/allpdfFile' . '/' . $fileName8
                ];
            } else {
                $pdfFiles = [
                    public_path() . '/allpdfFile' . '/' . $fileName6,
                    public_path() . '/allpdfFile' . '/' . $fileName8
                ];
            }
        }

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

            // unlink($mergedFilePath); (27-11-2024 comment these code bcs it deleted the file to the system and getting 404 not found when send to client the quotations.)

            foreach ($pdfFiles as $unlinkPath) {
                unlink($unlinkPath);
            }

    }

    public function printinvoiceclient($quatationId, $versionID, $isActive = null , $userid = null,$clientclick=null): mixed
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');

        $quatationId = $quatationId;
        if($userid == 3){
            $users = User::where('UserType',3)->where('id',$userid)->first();
            $id = $users->CreatedBy;
        }else{
            $id = $userid;
        }

        $comapnyDetail = Company::where('UserId', $id)->first();
        $quotaion = Quotation::where('id', $quatationId)->first();
        $contractorName = DB::table('users')->where(['id' => $quotaion->MainContractorId, 'UserType' => 5 ])->value('FirstName');
        $contractorName = $contractorName ?: '';

        $HideCosts = SettingCurrency::where('UserId', $id)->value('HideCosts');
        $currency = QuotationCurrency($quotaion->Currency);

        // $configurationItem = 1;
        $configurationItem = $quotaion->configurableitems;
        if (!empty($quotaion->configurableitems)) {
            $configurationItem = $quotaion->configurableitems;
        }

        $configurationItemName = configurationDoor($configurationItem);
        if($configurationItemName === 'Halspan'){
            $configurationItemName = 'Halspan Optima';
        }
        $project = empty($quotaion->ProjectId) ? '' : Project::where('id', $quotaion->ProjectId)->first();

        $pdf_footer = SettingPDFfooter::where('UserId', $id)->first();

        $SalesContact = 'N/A';
        if (!empty($quotaion->QuotationName)) {
            $SalesContact = $quotaion->QuotationName;
        }

        // PDF 1 ( Introduction PDF )
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

        $quotaion_contact_info = QuotationContactInformation::where('QuotationId',$quatationId)->first();
        if($quotaion_contact_info->Contact){
            $contactid = explode(',',(string) $quotaion_contact_info->Contact);
            $contact_persion = CustomerContact::where('id',$contactid[0])->first();
            $contactfirstandlastname = $contact_persion->FirstName . ' ' . $contact_persion->LastName;
        }
        else{
            $contactfirstandlastname = '';
        }


        $user = empty($quotaion->UserId) ? '' : User::where('id', $quotaion->CompanyUserId)->first();

        $pdf1 = SettingPDF1::where('UserId', $id)->first();
        $pdf = PDF::loadView('Company.pdf_files.introductionpdf', ['pdf1' => $pdf1, 'pdf_footer' => $pdf_footer, 'comapnyDetail' => $comapnyDetail, 'quotaion' => $quotaion, 'customerContact' => $customerContact, 'project' => $project, 'user' => $user, 'customer' => $customer, 'contactfirstandlastname' => $contactfirstandlastname, 'contractorName' => $contractorName]);
        // return $pdf->download('file.pdf');
        $path1 = public_path() . '/allpdfFile';
        $fileName1 = $id . '1' . '.' . 'pdf';
        $pdf->save($path1 . '/' . $fileName1);




        // Quotation Sumary PDF
        $pdf2 = SettingPDF2::where('UserId', $id)->first();
        // $totDoorsetType = Item::where(['QuotationId' => $quatationId ])->groupBy('DoorsetType')->count();
        $totDoorsetType = NumberOfDoorSets($versionID,$quatationId);

        // for getting margin
        $userIds = CompanyUsersClient(false, $userid);
        $margin = BOMSetting::wherein('UserId',$userIds)->value('margin_for_material');

// dd(\Config::get('constants.base64Images.FrameRebatedLeft'),"139");
// dd(\Config::get('constants.base64Images.ScallopedLeft'),"139");
        // $totDoorsetPrice = Item::where(['QuotationId' => $quatationId ])->sum('DoorsetPrice');

        $totgrand_total = BOMDetails::where(['quotationId' => $quatationId, 'version' => $versionID])->sum('grand_total');
        $totlabour_total = BOMDetails::where(['quotationId' => $quatationId, 'version' => $versionID])->sum('labour_total');

        // $totDoorsetPrice = 0;
        // $DoorsetPrice = Item::select('DoorsetPrice', 'itemID')->where(['QuotationId' => $quatationId])->whereNotNull('DoorsetPrice')->get();
        // foreach ($DoorsetPrice as $price) {
        //     if (!empty($price->DoorsetPrice)) {
        //         $itemMaster = ItemMaster::where(['itemID' => $price->itemID])->count();
        //         $totDoorsetPrice = $totDoorsetPrice + ($price->DoorsetPrice * $itemMaster);
        //     }
        // }

        $DoorsetPrice = Item::join('quotation_version_items','items.itemId','quotation_version_items.itemID')
                ->join('item_master','quotation_version_items.itemmasterID','item_master.id')
                ->where(['quotation_version_items.version_id'=>$versionID,'items.VersionId'=>$versionID,'items.QuotationId' => $quatationId]);

        $totDoorsetPrice = itemAdjustCount($quatationId,$versionID);
        $totIronmongaryPrice = $DoorsetPrice->sum('items.IronmongaryPrice');

        //end changes
        $nonConfigDataPrice = nonConfigurableItem($quatationId,$versionID,CompanyUsersClient(false, $userid),'',true);
        $nonConfigDataCount = nonConfigurableItem($quatationId,$versionID,CompanyUsersClient(false, $userid),'','','count');

        $totIronmongerySet = $DoorsetPrice->whereNotNull('items.IronmongeryID')->count();

        // $GetIronmongerySetCount = $DoorsetPrice->whereNotNull('items.IronmongeryID')->groupby('items.itemId')->count();
        $GetIronmongerySet = $DoorsetPrice->whereNotNull('items.IronmongeryID')->groupby('items.itemId')->get();
        $IronmongeryData = '';
        $PageBreakCount = 1;
        // dd($GetIronmongerySet,count($GetIronmongerySet));
        if(!empty($GetIronmongerySet)){
            foreach($GetIronmongerySet as $ironData){
                if (!empty($ironData->IronmongeryID)) {
                    $IronmongerySet = IronmongerySetName($ironData->IronmongeryID);
                    $IronmongeryData .= '<div id="headText"><b>Ironmongery Data</b></div>
                    <div><table id="WithBorder" class="tbl2">'. IronmongerySetDataClient($ironData->IronmongeryID,$userid) .'</table></div>';

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

        $screenData = $SideScreenData->sum('side_screen_items.ScreenPrice');
        $ScreenSetQty = $SideScreenData->count();
        // $totIronmongerySet = Item::where(['QuotationId' => $quatationId,'items.VersionId'=>$versionID])->whereNotNull('IronmongeryID')->count();
        // $jj = Item::select('IronmongeryID', 'itemID', 'IronmongaryPrice')->where(['QuotationId' => $quatationId])->whereNotNull('IronmongeryID')->get();
        // $totIronmongaryPrice = 0;
        // foreach ($jj as $price) {
        //     if (!empty($price->IronmongeryID)) {
        //         $itemMaster = ItemMaster::where(['itemID' => $price->itemID])->count();
        //         $totIronmongaryPrice = $totIronmongaryPrice + ($price->IronmongaryPrice * $itemMaster);
        //     }
        // }

        // $totIronmongaryPrice = Item::where(['QuotationId' => $quatationId ])->sum('IronmongaryPrice');
        $screenDataprice = round(floatval($screenData),2);
        $nettot = itemAdjustCount($quatationId, $versionID) + (float) $totIronmongaryPrice + (float) $nonConfigDataPrice + (float) $screenDataprice;

        $QSTI = QuotationShipToInformation::where('QuotationId', $quatationId)->first();


        $pdf2 = PDF::loadView('Company.pdf_files.quotationsummarypdf', ['comapnyDetail' => $comapnyDetail, 'project' => $project, 'quotaion' => $quotaion, 'pdf2' => $pdf2, 'pdf_footer' => $pdf_footer, 'totDoorsetType' => $totDoorsetType, 'totIronmongerySet' => $totIronmongerySet, 'totDoorsetPrice' => $totDoorsetPrice, 'totIronmongaryPrice' => $totIronmongaryPrice, 'nonConfigDataPrice' => $nonConfigDataPrice, 'nettot' => $nettot, 'QSTI' => $QSTI, 'customerContact' => $customerContact, 'customer' => $customer, 'user' => $user, 'nonConfigDataCount' => $nonConfigDataCount, 'contractorName' => $contractorName, 'ScreenSetQty' => $ScreenSetQty, 'screenDataprice' => $screenDataprice, 'currency' => $currency]);

        // return $pdf2->download('file2.pdf');
        $path2 = public_path() . '/allpdfFile';
        $fileName2 = $id . '2' . '.' . 'pdf';
        $pdf2->save($path2 . '/' . $fileName2);

        $QuotationShipToInformation = QuotationShipToInformation::where('QuotationId', $quatationId)->first();
        $QuotationSiteDelivery = QuotationSiteDeliveryAddress::where('QuotationId', $quatationId)->get();
        $ProjectsAddress = Project::join('quotation', 'quotation.ProjectId', 'project.id')->where(['quotation.CompanyId' => $quotaion->CompanyId, 'quotation.ProjectId' => $quotaion->ProjectId])->first();

        $htmlPreview = '<p><span style="font-size:36px"><strong>' .$project->ProjectName.'</strong></span></p>

            <p><span style="font-size:36px"><strong>' .$quotaion->QuotationGenerationId.' -&nbsp;&nbsp; Delivery Summary </strong></span></p>

            <p>&nbsp;</p>
            <table class="table table-bordered">';

            $i = 1;
            foreach($QuotationSiteDelivery as $QuotationSiteDeliveryAddress){

                $htmlPreview .= '<tr>
                    <td colspan="4"><p><span style="font-size:15px"><strong>Site Delivery Address - '.$i++.' </strong></span></p></td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>Address 1</span></td>
                    <td colspan="3">
                        <span>' . (!empty($QuotationSiteDeliveryAddress->Address1) ? $QuotationSiteDeliveryAddress->Address1 : (!empty($ProjectsAddress->AddressLine1) ? $ProjectsAddress->AddressLine1 : '')) . '</span>
                    </td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>Address 2</span></td>
                    <td colspan="3">
                        <span>' . (!empty($QuotationSiteDeliveryAddress->Address2) ? $QuotationSiteDeliveryAddress->Address2 : (!empty($ProjectsAddress->AddressLine2) ? $ProjectsAddress->AddressLine2 : '')) . '</span>
                    </td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>Country</span></td>
                    <td colspan="3"><span>' . (!empty($QuotationSiteDeliveryAddress->Country) ? $QuotationSiteDeliveryAddress->Country : (!empty($ProjectsAddress->Country) ? $ProjectsAddress->Country : '')) . '</span></td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>City</span></td>
                    <td colspan="3"><span>' . (!empty($QuotationSiteDeliveryAddress->City) ? $QuotationSiteDeliveryAddress->City : (!empty($ProjectsAddress->City) ? $ProjectsAddress->City : '')) . '</span></td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>Postal Code</span></td>
                    <td colspan="3"><span>' . (!empty($QuotationSiteDeliveryAddress->PostalCode) ? $QuotationSiteDeliveryAddress->PostalCode : (!empty($ProjectsAddress->PostalCode) ? $ProjectsAddress->PostalCode : '')) . '</span></td>
                </tr>';
            }

            $htmlPreview .= '<tr>
                    <td class="tbl_color"><span>Delivery Restrictions</span></td>
                    <td colspan="3"><span>' . ($QuotationShipToInformation->DeliveryRestrictions ?? '') . '</span></td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>Wagon Preference</span></td>
                    <td colspan="3">
                        <span>';
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
                            $wagon = $QuotationShipToInformation->WagonPreference ?? '';
                            $htmlPreview .= $wagonOptions[$wagon] ?? '';
            $htmlPreview .= '</span>
                    </td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>Booking Notice & Contact</span></td>
                    <td colspan="3"><span>' . ($QuotationShipToInformation->Booking ?? '') . '</span></td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>Delivery Policy</span></td>
                    <td colspan="3"><span>' . ($QuotationShipToInformation->Deliverypolicy ?? '') . '</span></td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>FORS Requirement - Silver</span></td>
                    <td><span>' . ($QuotationShipToInformation->silver ?? 'No') . '</span></td>
                    <td class="tbl_color"><span>FORS Requirement - Gold</span></td>
                    <td><span>' . ($QuotationShipToInformation->gold ?? 'No') . '</span></td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>Off-loading Requirements</span></td>
                    <td colspan="3"><span>' . ($QuotationShipToInformation->Offloading ?? '') . '</span></td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>No. of Deliveries</span></td>
                    <td><span>' . ($QuotationShipToInformation->NoOfDeliveries ?? '') . '</span></td>
                    <td class="tbl_color"><span>Actual No. of Deliveries</span></td>
                    <td><span>' . ($QuotationShipToInformation->ActualNoOfDeliveries ?? '') . '</span></td>
                </tr>
                <tr>
                    <td class="tbl_color"><span>Cost Per Delivery</span></td>
                    <td><span>' . ($QuotationShipToInformation->Costperdelivery ?? '') . '</span></td>
                    <td class="tbl_color"><span>Average No. Doorsets per Drop</span></td>
                    <td><span>' . ($QuotationShipToInformation->AverageNoDoorsetsperdrop ?? '') . '</span></td>
                </tr>
            </table>';

        $pdf2_1 = PDF::loadView('Company.pdf_files.quotationDeliverypdf', ['comapnyDetail' => $comapnyDetail,'htmlPreview' => $htmlPreview]);

        // return $pdf2->download('file2.pdf');
        $path2_1 = public_path() . '/allpdfFile';
        $fileName2_1 = $id . '2_1' . '.' . 'pdf';
        $pdf2_1->save($path2_1 . '/' . $fileName2_1);




        // for getting margin
        $userIds = CompanyUsersClient(false, $userid);
        $margin = BOMSetting::wherein('UserId',$userIds)->value('margin_for_material');


        // Details Door List PDF
        $qv = QuotationVersion::where('id', $versionID)->first();
        $version = $qv->version;
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
            <td>' . $show->DoorType . '</td>';

            if($HideCosts == 0){
               $a2 .= '<td>' . round((($show->AdjustPrice)?floatval($show->AdjustPrice) :floatval($show->DoorsetPrice)), 2) . '</td><td>' . round($show->IronmongaryPrice, 2) . '</td>';
            }

            $a2 .= '<td>' . round((($show->AdjustPrice)?floatval($show->AdjustPrice) + floatval($show->IronmongaryPrice):floatval($show->DoorsetPrice) + floatval($show->IronmongaryPrice)), 2) . '</td>
            </tr>';
            $i++;
        }

        $pdf3 = PDF::loadView('Company.pdf_files.detaildoorlist', ['a2' => $a2, 'comapnyDetail' => $comapnyDetail, 'quotaion' => $quotaion, 'project' => $project, 'version' => $version, 'HideCosts' => $HideCosts]);
        // return $pdf3->download('file3.pdf');
        $path3 = public_path() . '/allpdfFile';
        $fileName3 = $id . '3' . '.' . 'pdf';
        $pdf3->save($path3 . '/' . $fileName3);


        //Non Configurable Item
        $nonConfigData = nonConfigurableItem($quatationId,$versionID,CompanyUsersClient(false, $userid));

        $pdf4_2 = PDF::loadView('Company.pdf_files.nonconfigdoor', ['nonConfigData' => $nonConfigData, 'comapnyDetail' => $comapnyDetail, 'quotaion' => $quotaion, 'project' => $project, 'customerContact' => $customerContact, 'version' => $version, 'customer' => $customer]);
        // return $pdf4->download('file4.pdf');
        $path4_2 = public_path() . '/allpdfFile';
        $fileName4_2 = $id . '4_2' . '.' . 'pdf';
        $pdf4_2->save($path4_2 . '/' . $fileName4_2);



        //PDF 2
        $CompanyId = get_company_id($id)->id;
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
                            <td>' . $SpecialFeatureRefs . '</td>';
                            if($HideCosts == 0){
                                $a .= '<td class="tbl_last">' . round($DoorsetPrice, 2) . '</td>
                                <td class="tbl_last">' . round($IronmongaryPrice, 2) . '</td>';
                            }

                            $a .= '<td class="tbl_last">' . round($totalpriceperdoorset, 2) . '</td>
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
                            <td>' . $SpecialFeatureRefs . '</td>';
                            if($HideCosts == 0){
                                $a .= '<td class="tbl_last">' . round($DoorsetPrice, 2) . '</td>
                                <td class="tbl_last">' . round($IronmongaryPrice, 2) . '</td>';
                            }

                            $a .= '<td class="tbl_last">' . round($totalpriceperdoorset, 2) . '</td>
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
                        <td class="tbl_bottom" colspan="39"></td>';
                        if($HideCosts == 0){
                            $a .= '<td class="tbl_bottom">' .$currency. round($SumDoorsetPrice, 2) . '</td>
                            <td class="tbl_bottom">' . $currency.round($SumIronmongaryPrice, 2) . '</td>';
                        }

                        $a .= '<td class="tbl_bottom">' .$currency. round($Alltotalpriceperdoorset, 2) . '</td>
                    </tr>
                ';


        if($quotaion->configurableitems == 4 || $quotaion->configurableitems == 9){
            $pdf4 = PDF::loadView('Company.pdf_files.vicaima.pdf2', ['a' => $a, 'comapnyDetail' => $comapnyDetail, 'project' => $project, 'customerContact' => $customerContact, 'version' => $version, 'customer' => $customer, 'HideCosts' => $HideCosts]);
        }else{
            $pdf4 = PDF::loadView('Company.pdf_files.pdf2', ['a' => $a, 'comapnyDetail' => $comapnyDetail, 'project' => $project, 'customerContact' => $customerContact, 'version' => $version, 'customer' => $customer, 'HideCosts' => $HideCosts]);
        }

        // return $pdf4->download('file4.pdf');
        $path4 = public_path() . '/allpdfFile';
        $fileName4 = $id . '4' . '.' . 'pdf';
        $pdf4->save($path4 . '/' . $fileName4);

        // side screen door list

        $s2 = '';
        $shocw = SideScreenItem::join('side_screen_item_master', 'side_screen_items.id', 'side_screen_item_master.ScreenId')->where(['side_screen_items.QuotationId' => $quatationId])
        ->where(['side_screen_items.VersionId' => $versionID])
        ->select('side_screen_items.*','side_screen_item_master.screenNumber')
        ->get();
        $i = 1;
        foreach ($shocw as $show) {
            $s2 .=
            '<tr>
            <td>' . $show->ScreenType . '</td>
            <td>' . $show->screenNumber . '</td>
            <td>' . $show->GlazingType . '</td>
            <td>' . ($show->ScreenAdjustPrice > 0 ? $show->ScreenAdjustPrice : $show->ScreenPrice) . '</td>
            </tr>';
            $i++;
        }

        $pdf9 = PDF::loadView('Company.pdf_files.sidescreendoorlist', ['a2' => $s2, 'comapnyDetail' => $comapnyDetail, 'quotaion' => $quotaion, 'project' => $project, 'version' => $version]);
        $path9 = public_path() . '/allpdfFile';
        $fileName9 = $id . '9' . '.' . 'pdf';
        $pdf9->save($path9 . '/' . $fileName9);
        // end

        // Elevation Drawing
        $elevTbl = '';
        // $ed = Item::where('QuotationId',$quatationId)->get();
        $ed = Item::join('item_master','item_master.itemID','=','items.itemId')->join("quotation_version_items",function($join): void{
            $join->on("quotation_version_items.itemID","=","items.itemId")
                ->on("quotation_version_items.itemmasterID","=","item_master.id");
        })
        ->join('quotation','quotation.id','=','items.QuotationId')
        ->where('items.QuotationId', $quatationId)
        ->where('quotation_version_items.version_id', $versionID)->select('items.*','item_master.doorNumber','quotation.configurableitems')->groupBy('item_master.itemID')->get();

        $TotalItems = count($ed->toArray());

        $PageBreakCount = 1;
        $PageBreakCounts = 1;

        foreach ($ed as $tt) {
            $getLeaf = IntumescentSealLeafType::where('id',$tt->IntumescentLeafType)->select('id','leaf_type_key','door_thickness')->first();
            if($getLeaf){
                $fire = $tt->FireRating . ' - ' . $getLeaf->leaf_type_key . ' (' . $getLeaf->door_thickness . 'mm)';
            }
            else{
                $fire = $tt->FireRating;
            }

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
                    'FD30' => 'Chilt/A01205 Part 1 Revision K',
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
            $GlazingSystems = GlazingSystemsImage($GlazingSystems,$GlazingBeads,$tt->FireRating);
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

                if($tt->DoorsetType == "SD"){
                if(!empty($tt->Handing) && $tt->Handing == "Left"){
                  $FrameTypeLeft = \Config::get('constants.base64Images.ScallopedLeft');
                  $FrameTypeRight = \Config::get('constants.base64Images.ScallopedStraight');
                  $FrameTypeCommon = \Config::get('constants.base64Images.FrameRebatedCommon');
                } else {
                  $FrameTypeLeft = \Config::get('constants.base64Images.ScallopedStraight');
                  $FrameTypeRight = \Config::get('constants.base64Images.ScallopedRight');
                  $FrameTypeCommon = \Config::get('constants.base64Images.FrameRebatedCommon');
                }}
                else{
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
            $RemainingSpaceBlockScallopedLeft=\Config::get('constants.base64Images.RemainingSpaceBlockScallopedLeft');
            $RemainingSpaceBlockScallopedRight=\Config::get('constants.base64Images.RemainingSpaceBlockScallopedRight');
            $FixedSpaceBlockScallopedLeft=\Config::get('constants.base64Images.FixedSpaceBlockScallopedLeft');
            $FixedSpaceBlockScallopedRight=\Config::get('constants.base64Images.FixedSpaceBlockScallopedRight');


            $remainingWidth = $tt->LeafWidth1 - ($tt->Leaf1VPWidth + $tt->DistanceFromTheEdgeOfDoor);

            if (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') {
                if ($tt->DistanceFromTheEdgeOfDoor > $remainingWidth) {
                    $FrameImageStructureLeft = $FixedSpaceBlockScallopedRight;
                    $FrameImageStructureRight = $FixedSpaceBlockScallopedLeft;

                } elseif ($tt->DistanceFromTheEdgeOfDoor < $remainingWidth) {


                     if(($show->DoorsetType == "SD")){
                       if(!empty($tt->Handing) && $tt->Handing == "Left"){
                          $FrameImageStructureLeft = $FixedSpaceBlockScallopedLeft;
                          $FrameImageStructureRight = $FixedSpaceBlock;
                       }else{
                           $FrameImageStructureLeft = $FixedSpaceBlock;
                           $FrameImageStructureRight = $FixedSpaceBlockScallopedRight;
                       }
                    }else{
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

                   if(($show->DoorsetType == "SD")){
                       if(!empty($tt->Handing) && $tt->Handing == "Left"){
                           $FullBlock =$FixedSpaceBlockScallopedLeft;
                       }else{
                          $FullBlock =$FixedSpaceBlockScallopedRight;
                       }
                    }else{
                          $FullBlock =$FixedSpaceBlockScallopedRight;
                    }

                } else {
                    $FrameImageStructureLeftLeaf1 = $FixedSpaceBlockScallopedLeft;
                    $FrameImageStructureRightLeaf1 = $RemainingSpaceBlock;
                    $FullBlock =$FixedSpaceBlockScallopedRight;

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

            $fetchSignature = ClientSignature::where('quotation_id',$quatationId)->where('version_id',$versionID)->first();
// dd($tt->Leaf2VisionPanel != 'Yes');
            $redstripRightCommonClass = $tt->IntumescentLeapingSealLocation.'_right_strip_'.$tt->DoorsetType;
            $redstripLeftCommonClass = $tt->IntumescentLeapingSealLocation.'_left_strip_'.$tt->DoorsetType;
            switch ($tt->DoorsetType) {
                case "SD":
                    // $DoorFrameImage = Base64Image('FD30SingleDoorsetwithVP');

                    $DoorFrameImage = '<div style="padding:10px 30px;position: relative;margin-left: '.$frameImageLeftMargin.'px;height: 100px;">
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
                                    margin-left:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ($tt->IntumescentLeapingSealLocation == 'Door'? '26' : ((empty($tt->Handing) && $tt->Handing == "Left")?'13':'14')) : ($tt->IntumescentLeapingSealLocation == 'Door'? '-3' : '-3')) .'px;
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
                                    margin-left: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ($tt->IntumescentLeapingSealLocation == 'Door'? '26' : '13') : ($tt->IntumescentLeapingSealLocation == 'Door'? '15' : '3')) .'px;
                                    margin-top: 15px;"></div>

                                    <div style="border: 0.5px solid black;
                                    background-color: red;
                                    z-index: 999;
                                    position: absolute;
                                    height: 16px;
                                    width: 6px;
                                    box-shadow: none;
                                    margin-left:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ($tt->IntumescentLeapingSealLocation == 'Door'? '26' : '13') : ($tt->IntumescentLeapingSealLocation == 'Door'? '15' : '3')) .'px;
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
                        width: 69px;
                        height: 71px;
                        margin-left: -26px;
                        '.
                            (($tt->FireRating == 'FD30') ?
                                'top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '84' :  '133') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '86' : '137')) .'px;'
                                :
                                'top:'. (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '84' : '135') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '86' : '137')) .'px;')
                        .'
                        " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                           >


                     </div>
                     <div style="position: absolute;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-47' : '5').'px;left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-251' : '-265').'px;">
                        <img style="width:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '53' : '76').'px;height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '208' : ((!empty($tt->FrameType) && $tt->FrameType == 'Rebated_Frame') ? '131' : '196')).'px;" alt="" src="'.$FrameTypeCommon.'">
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
                           '.
                            (($tt->FireRating == 'FD30') ?
                                'top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-108' : '-158') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-111' : '-163')) .'px;'
                                :
                                'top: '. (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-108' : '-158') :((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-111' : '-163')) .'px;')
                            .'
                           " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                           >

                     </div>
                     <div style="position: absolute;top:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-46' : '7').'px;left:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-83' : '-87').'px;">
                        <img style="width: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '53' : '76').'px; height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '208' : ((!empty($tt->FrameType) && $tt->FrameType == 'Rebated_Frame') ? '131' : '196')).'px;" alt="" src="'.$FrameTypeCommon.'">
                     </div>';

                    }


                    $DoorFrameImage .= '<div  style="position: absolute; top: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-46' : '7') .'px;left: -44px;">
                                        <img style="width:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && !empty($tt->Handing) && $tt->Handing == "Left") ? '77' : '67') .'px; height:'. (
    (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '170px' : ($tt->FrameType == 'Rebated_Frame'? '126px': '196px' ) ) .';" src="' . $FrameTypeLeft . '" alt="">
                                    </div>
                                </div>';

                    if ($tt->Leaf1VisionPanel == 'Yes') {

                        $DoorFrameImage .= '<div style="position: relative;top: 12px;">
                                    <img style="
                                        width: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ?  '155' : '165') .'px;
                                        position: relative;
                                        top: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (((!empty($tt->Handing) && $tt->Handing != "Left"))?'-17': '5') : '-17') .'px;
                                        left: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '26' : '7') .'px;'
                                           .
        ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (((!empty($tt->Handing) && $tt->Handing != "Left") && ($show->Leaf1VisionPanel == "Yes"))? 'height: 116px;' :'height: 65px;') : '') .
        '" src="' . $FrameImageStructureLeft . '" alt="">
                                    </div>
                                    <div style="width:430px;">
                                        <div style="
                                            width:200px;
                                            margin: 0 auto;
                                            position: relative;
                                            margin-left: -66px;
                                            top: -168px;
                                            left: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '248' : '245') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '237' : '235')) .'px;
                                            ">

                                            <img style="
                                            z-index: 999;
                                            ' .(($tt->FireRating == 'FD30') ?
                            'width: 125px;
                             margin-top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (((!empty($tt->Handing) && $tt->Handing != "Left")&& ($show->Leaf1VisionPanel == "Yes"))?'71': '118') : '74') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ((!empty($tt->Handing) && $tt->Handing != "Left")?'62':'111') : '68')) .'px;
                                                height:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? '70' : '82') .'px;' :
                            'width: 108px; margin-left: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? '-6' : '4') .'px;
                                                margin-top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ?( ($tt->Handing != "Left")?'66':'118')  : '74') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '111' : '69')) .'px;
                                                height:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? '70' : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '82' : '80'))) .'px;' . '" src="' . $VisionPanelGlazingImageStructure . '" alt="">



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

                                             'width: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? '117' : '125') .'px;
                                                 margin-top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (((!empty($tt->Handing) && $tt->Handing != "Left")&& ($show->Leaf1VisionPanel == "Yes"))? '135':'135') : '132') :  ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '124' : '121')) .'px;
                                                 margin-left: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '31' : '33') : '34') .'px;
                                                height: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? '73' : '84') .'px;'
                            :
                            'width: 124px; margin-top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '135' : '132') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '124.5' : '122.5')) .'px;
                                                 margin-left: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? '25.5' : '31.5') .'px;
                                                height:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? '72' : '82') .'px;') . '
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
                        $DoorFrameImage .= '   <div style="position: relative;position: absolute;top: 7px;left:475px;"><img style="width: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '158' : '170') .'px;
                                        position: relative;
                                        bottom: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (( $tt->Handing != "Left")? (($show->Leaf1VisionPanel == "Yes")? '-22':'2'):  '1') : (($tt->Leaf1VisionPanel == "Yes")?'2':'-22')  )  .'px;
                                        left: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ((empty($tt->Handing) && $tt->Handing == "Left")?'-32': '-30') : '-31') .'px;'
                                           .
        ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Handing == "Left")? (($show->Leaf1VisionPanel == "Yes")? ((in_array($tt->FireRating, ["FD60", "FD60s"]))?'height:112px;': 'height:112px;'):'height:109px;') : 'height:65px;' )  : '') .
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

                        $DoorFrameImage .= '<img style="width:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' ) ? '575':'583') .'px;
                        position: relative;
                        top: -17px;
                        left: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' ) ? (( !empty($tt->Handing) && $tt->Handing != "Left")? '27' : '25'):'3') .'px;height: 108px;" src="' . $FullBlock . '" alt="">
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
                                        margin-left: '. (
                                            !empty($tt->FrameType) && $tt->FrameType == 'Scalloped' ? ($tt->IntumescentLeapingSealLocation == 'Door'? '-50' : (!empty($tt->Handing) && $tt->Handing != "Left"? (($show->Leaf1VisionPanel == "Yes")?'-31.5':'-5.5') :(($show->Leaf1VisionPanel == "Yes")?'-34.5':'-6.5')) ) : ($tt->IntumescentLeapingSealLocation == 'Door'? '-27.5' : '-27.5')) .'px;
                                        margin-top:'.(!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && (!empty($tt->Handing) && $tt->Handing != "Left") &&($show->Leaf1VisionPanel == "Yes")?'50':'41'  ).'px;"></div>';
                        } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                            // class="'.$redstripRightCommonClass.'"
                            $DoorFrameImage .= '<div  style="border: 0.5px solid black;
                                        background-color: red;
                                        z-index: 999;
                                        position: absolute;
                                        height: 16px;
                                        width: 6px;
                                        box-shadow: none;
                                        margin-left: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ($tt->IntumescentLeapingSealLocation == 'Door'? '-50' : ((!empty($tt->Handing) && $tt->Handing != "Left" )?( ($tt->Handing != "Left")? '-34.5':'-5.5' ): ((in_array($tt->FireRating, ["FD60", "FD60s"]))? (($show->Leaf1VisionPanel == "Yes")?'-35.5':'-5.5' ): '-5.5'))) : ($tt->IntumescentLeapingSealLocation == 'Door'? '-35.5' : '-25.5')) .'px;
                                        margin-top: 30px;"></div>

                                        <div style="border: 0.5px solid black;
                                        background-color: red;
                                        z-index: 999;
                                        position: absolute;
                                        height: 16px;
                                        width: 6px;
                                        box-shadow: none;
                                        margin-left: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? ($tt->IntumescentLeapingSealLocation == 'Door'? '-50' : ((!empty($tt->Handing) && $tt->Handing != "Left" )?( ($tt->Handing != "Left")? '-34.5':'-5.5' ):((in_array($tt->FireRating, ["FD60", "FD60s"]))?(($show->Leaf1VisionPanel == "Yes")?'-35.5':'-5.5' ): '-5.5'))) : ($tt->IntumescentLeapingSealLocation == 'Door'? '-35.5' : '-25.5')) .'px;
                                        margin-top: 57px;"></div>';
                        }
                    }

                    if(empty($FrameTypeRight)){
                        $FrameTypeRight = '';
                    }
                    // dd($tt->Handing);
                    if($tt->FrameType !== null){
                    $DoorFrameImage .= '<div style="position: absolute;top:  '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && $tt->Handing != "Left" && ($show->Leaf1VisionPanel == "Yes") ) ? '28' :'21' ).'px;right: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (( $tt->Handing == "Left")? (($show->Leaf1VisionPanel == "Yes")?'-40':'-69') :(($show->Leaf1VisionPanel == "Yes")?'-32':'-60')) : '-27') .'px;">
                                        <img style="width:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && !empty($tt->Handing) && $tt->Handing != "Left") ? '77' : '77') .'px;
                                        margin-top: '. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-53' : '-1') .'px; height:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '170px' : 'auto') .';" src="' .  $FrameTypeRight . '" alt="">
                                    </div>
                                </div>
                            ';
                                        }
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
                              width: 69px;
                              height: 71px;
                              margin-left: -26px;
                              top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '82' : '132') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '84' : '135')) .'px;
                              '.
                            (($tt->FireRating == 'FD30') ?
                                'top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '82' : '132') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '84' : '135')) .'px;'
                                :
                                'top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '82' : '132') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '84' : '135')) .'px;')
                            .'
                              " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                              >


                        </div>
                        <div style="position: absolute;top:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-49' : '4').'px;left:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-252' : '-265').'px;">
                           <img style="width: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '55' : '77').'px;height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '212' : '194').'px;" alt="" src="'.$FrameTypeCommon.'">
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
                              top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-106' : '-157') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-109' : '-162')) .'px;
                              '.
                            (($tt->FireRating == 'FD30') ?
                                'top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-106' : '-157') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-109' : '-162')) .'px;'
                                :
                                'top: '. (
                                    $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-106' : '-157') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-109' : '-162')) .'px;')
                            .'" alt="" src="'.$VisionPanelGlazingImageStructure.'"
                              >


                        </div>
                        <div style="position: absolute;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-48' : '7').'px;left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-84' : '-87').'px;">
                           <img style="width: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '55' : '76').'px;height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '208' : ((!empty($tt->FrameType) && $tt->FrameType == 'Rebated_Frame') ? '131' : '196')).'px;" alt="" src="'.$FrameTypeCommon.'"
                              >
                        </div>
                       </div>

                      </div>';

                    }

                    break;


                case "DD":
                    // $DoorFrameImage = Base64Image('FD30DoubleDoorsetwithVP');

                    $DoorFrameImage = '<div style="padding: 10px 30px; position: relative;margin-left: '.$frameImageLeftMargin.'px;height: 200px;">
                            <div style="position: relative;  top: 12px;">';



                    if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {
                        // margin-left: 5px;
                        if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                            $DoorFrameImage .= '<div class="'. ($tt->FrameType !== null ? $redstripLeftCommonClass : 'DD_NoFrame_left_intubacent') .'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-top: 18px;"></div>';
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
                                'width: 66px;height: 50px;margin-left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-14' : '-4').'px;top:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '111' : ($GlazingSystems['GlazingBeadsPadding'] == 0 ?'133': '135')).'px;'
                                :
                                'width: 66px;height: 50px;margin-left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-14' : '-4').'px;top:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '111' : ($GlazingSystems['GlazingBeadsPadding'] == 0 ?'133': '135')).'px;')
                                 .'
                                " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                                    />


                            </div>

                            <div style="position: absolute;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-19' : '4').'px;left:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-233' : '-222').'px;">';

                            // if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {
                            //     if (in_array($tt->FireRating, ["FD30", "FD30s"])) {

                            //         $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 18px;"></div>';

                            //     } else if (in_array($tt->FireRating, ["FD60", "FD60s"])) {

                            //         $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 10px;"></div>
                            //                 <div class="'.$redstripLeftCommonClass.'_'.$sidelight.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 25px;"></div>';
                            //     }
                            // }


                            $DoorFrameImage .= '<img style="width: 46px;height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '107' : '117').'px;" alt="" src="'.$FrameTypeCommon.'" />
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
                                    '.
                                    (($tt->FireRating == 'FD30') ?
                                    'width: 66px;height: 55px;margin-left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '2' : '-7').'px;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-116' :  ($GlazingSystems['GlazingBeadsPadding'] == 0 ?'-141':'-143.5')).'px;'
                                    :
                                    'width: 66px;height: 55px;margin-left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '2' : '-7').'px;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-116' :  ($GlazingSystems['GlazingBeadsPadding'] == 0 ?'-141':'-143.5')).'px;')
                                    .'
                                    " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                                    >





                            </div>

                            <div style="position: absolute;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-22' : '4').'px;left:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-74' : '-66').'px;">';


                            // if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {


                            //     if (in_array($tt->FireRating, ["FD30", "FD30s"])) {

                            //         $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 18px;"></div>';

                            //     } else if (in_array($tt->FireRating, ["FD60", "FD60s"])) {

                            //         $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 10px;"></div>
                            //                 <div class="'.$redstripRightCommonClass.'_'.$sidelight.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 25px;"></div>';
                            //     }
                            // }
                            $DoorFrameImage .= '<img style="width: 46px;height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '107' : '117').'px;" alt="" src="'.$FrameTypeCommon.'">
                            </div>
                        ';

                    }


                    $DoorFrameImage .= '<div style="position: absolute; top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-22' : '4') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-22' : '4')) .'px; left: -40px;">
                                    <img style="width: '. (
                                                ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '39' : '46')) .'px; alt="" src="' . $FrameTypeLeft . '">
                                </div>
                            </div>';

                    if ($tt->Leaf1VisionPanel == 'Yes') {

                        $DoorFrameImage .= '
                                <div style="position: relative;  top: 12px;">
                                    <img style="width: 97px;
                                        position: relative;
                                        top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '7' : '-10') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '7' : '-10')) .'px;
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
                                        ' . (($tt->FireRating == 'FD30') ? 'width:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '67' : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '70' : '70')) .'px; margin-left:  '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-100' : '-100') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-106' : '-106')) .'px;
                                                 height:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '32' : '42') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '36' : '49')) .'px;
                                        margin-top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '174' : '134') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '172' : '131')) .'px;'
                                                 :
                                                 'width: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '67' : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '70' : '70')) .'px;
                                        margin-left:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-100.5' : '-103.5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-104.5' : '-103.5')) .'px;
                                        height:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '31' : '42') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '36' : '49')) .'px;
                                        margin-top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '174.5' : '134') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '171.5' : '131.5')) .'px;') . '" alt="" src="' . $VisionPanelGlazingImageStructure . '">



                                        <img style="position: absolute;
                                        top: 120px;
                                        transform: rotate(90deg);
                                        z-index: -1;height: 40px;' . (($tt->FireRating == 'FD30') ? 'left: -99px;width: 17px;' : 'left: -97px;width: 14px;') . '" src="' . $VisionPanelGlazingImageLeft . '" alt="">
                                    </div>

                                    <div style="width: 200px; margin-top: 120px; position: relative; top:-354px; left:220px;">

                                        <img style="
                                        transform: rotate(180deg);
                                        z-index: 999;
                                        ' . (($tt->FireRating == 'FD30') ? 'width:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '67' : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '70' : '70')) .'px;
                                        margin-left:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-64' : '-65') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-62' : '-62')) .'px;
                                        margin-top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '16.5' : '8') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '12.5' : '1')) .'px;
                                                 height:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '32' : '41') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '36' : '48')) .'px;'
                                                :
                                                'width: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '67' : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '70' : '70')) .'px;
                                                margin-left:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-65' : '-65') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-64' : '-64')) .'px;
                                                 height:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '31' : '42') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '36' : '48')) .'px;
                                        margin-top: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '18' : '7') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '13' : '0')) .'px;') . '" alt="" src="' . $VisionPanelGlazingImageStructure . '">



                                    </div>
                                </div>

                                <div style="position: absolute;
                                    top: 8px;
                                    left: 290px;">';

                        if ($tt->IntumescentLeapingSealLocation == 'Door' || $tt->IntumescentLeapingSealLocation == 'Frame') {

                            if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                                $DoorFrameImage .= '<div style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;
                                margin-left:'. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '32' : '53') .'px;
                                                margin-top: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '31' : '33') .'px;"></div>';
                            } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                                $DoorFrameImage .= '<div class=""  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left:'. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->IntumescentLeapingSealLocation == 'Frame')? (($tt->Leaf2VisionPanel == 'Yes')? '31':'140'):'132') : '53') .'px;margin-top: 23px;"></div>
                                            <div style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left:'. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->IntumescentLeapingSealLocation == 'Frame')? (($tt->Leaf2VisionPanel == 'Yes')? '31':'140'):'132') : '53') .'px;margin-top: 40px;"></div>';
                            }
                        }

                        $DoorFrameImage .= '<img style="width: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '75' : '95') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '75' : '95')) .'px;
                                        position: relative;
                                        top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '10' : '5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '10' : '5')) .'px;
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
                                    width: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '105' : '323')  .'px;
                                    position: relative;
                                    top: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '6' : '-10')  .'px;
                                    left: -5.5px;" src="' . $FullBlock . '" alt="">
                                </div>';
                    }

                    if ($tt->Leaf2VisionPanel == 'Yes') {

                        $DoorFrameImage .= '<div style="position: absolute;
                                        top: 8px;
                                        left: 386px;">
                                        <img style="width:'. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '78' : '95')  .'px;
                                            position: relative;
                                            top:'. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '9' : '5')  .'px;
                                            right:'. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '56.5' : '37.5')  .'px;" alt="" src="' . $FrameImageStructureLeftLeaf2 . '">
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
                                                ' . (($tt->FireRating == 'FD30') ? 'width: '. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '56':'70') : '70') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '58':'70') : '70')) .'px;
                                                 margin-left:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-41':'-5') : '-5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-45':'-5') : '-10')) .'px;
                                                margin-top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '134':'130') : '131') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '131':'130') : '129')) .'px;'
                                                 :
                                                'width:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '56':'70') : '70') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '58':'70') : '70')) .'px;
                                                 margin-left:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-41':'-5') : '-5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-45':'-5') : '-10')) .'px;
                                                margin-top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '134':'130') : '131') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '131':'130') : '129')) .'px;') . '" alt="" src="' . $VisionPanelGlazingImageStructure . '">


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
                                                ' . (($tt->FireRating == 'FD30') ? 'width:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '56':'67') : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '57':'70') : '70')) .'px;
                                                margin-left:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-13':'29') : '32') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-9':'29') : '34')) .'px;
                                                margin-top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '70':'38') : '63.5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '65':'38') : '57')) .'px;'
                                                :
                                                'width:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '56':'67') : '67') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '57':'70') : '70')) .'px;
                                                margin-left:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-13':'29') : '32') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '-9':'29') : '34')) .'px;
                                                margin-top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '70':'38') : '63.5') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '65':'38') : '57')) .'px;') . '" alt="" src="' . $VisionPanelGlazingImageStructure . '">


                                            <img style="position: absolute;
                                                ' . (($tt->FireRating == 'FD30') ? 'top: 76px;width: 17px;right: 103px;' : 'top: 77px;right: 104px;width: 14px;') . 'z-index: -1;
                                                transform: rotate(270deg);height:40px;" src="' . $VisionPanelGlazingImageLeft . '" alt="">

                                        </div>
                                    </div>
                                    <div style="position: relative;  position: absolute; top: 8px; left: 630px;">
                                        <img style="width: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '136' : '95') .'px;
                                            position: relative;
                                            top:'. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '9' : '5') .'px;
                                            right: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '111' : '55') .'px; alt="" src="' . $FrameImageStructureRightLeaf2 . '">
                                    </div>';
                    } else {
                        $DoorFrameImage .= '<div style="position: absolute;
                                        top: 8px;
                                        left: 386px;">
                                        <img style="width:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '99' : '319') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '99' : '319')) .'px;
                                        position: relative;
                                        top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '21' : '4') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '21' : '4')) .'px;;
                                        right:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '56' : '35') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '56' : '35')) .'px;" alt="" src="' . $FullBlock . '">
                                    </div>';

                                    if (($tt->IntumescentLeapingSealLocation == 'Door' || $tt->IntumescentLeapingSealLocation == 'Frame') && in_array($tt->FireRating, ["FD30", "FD30s"])) {

                                        $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left:' .(($tt->FrameType !== null)? '640':'634').'px;margin-top:' .(($tt->FrameType !== null)? '-385':'-385').'px;"></div>';
                                    }
                    }

                    $DoorFrameImage .= '
                                    <div style="position: relative;  position: absolute; top: 8px; left: 722px;">';





                            // if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                            //     $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: -52px;margin-top: 31px;"></div>';
                            // } else
                            if (($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') && in_array($tt->FireRating, ["FD60", "FD60s"])) {

                                // class="'.$redstripLeftCommonClass.'"
                                $DoorFrameImage .= '<div   style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left:'.(($tt->Leaf2VisionPanel == 'Yes')? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-64' : '-52'):'-60').'px;margin-top: 24px;"></div>
                                            <div  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: '.(($tt->Leaf2VisionPanel == 'Yes')? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-64' : '-52'):'-60').'px;margin-top: 37px;"></div>';
                            }

                    if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {

                        if (in_array($tt->FireRating, ["FD30", "FD30s"])) {
                            if($tt->Leaf2VisionPanel == 'Yes'){
                            $DoorFrameImage .= '<div   style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->IntumescentLeapingSealLocation == 'Frame')? '-64':'-73') : '-50') .'px;
                                                margin-top: '. (
                                                (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '32' : '33') .'px;"></div>';
                            }
                        } elseif (in_array($tt->FireRating, ["FD60", "FD60s"])) {
                            // class="'.$redstripRightCommonClass.'"
                            if($tt->Leaf2VisionPanel =! 'Yes'){


                                                        $DoorFrameImage .= '<div   style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: '. (
                                                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->IntumescentLeapingSealLocation == 'Frame')? '-63':'-74') : '-50') .'px;margin-top: 23px;"></div>
                                    <div  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: '. (
                                                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->IntumescentLeapingSealLocation == 'Frame')? '-63':'-74') : '-50') .'px;margin-top: 40px;"></div>';
                                                                }
                        }
                    }

                    if(empty($FrameTypeRight)){
                        $FrameTypeRight = '';
                    }
                    if($tt->FrameType !== null){
                    $DoorFrameImage .= '<div style="position: absolute; top:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-6' : '18') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-6' : '18')) .'px;
                                                right:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '32':'32') : '20') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel == 'Yes')? '33':'33') : '20')) .'px;">
                                            <img style="width:'. (
                                                $GlazingSystems['GlazingBeadsPadding'] == 0 ? ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '39' : '46') : ((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '39' : '46')) .'px;" alt="" src="' . $FrameTypeRight . '">
                                        </div>';
                                            }
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
                                    'width: 66px;height: 50px;margin-left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-10' : '-5').'px;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '109' : ($GlazingSystems['GlazingBeadsPadding'] == 0 ?'133': '135')).'px;'
                                    :
                                   'width: 66px;height: 50px;margin-left: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-10' : '-5').'px;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '109' : ($GlazingSystems['GlazingBeadsPadding'] == 0 ?'133': '135')).'px;')
                                    .'
                                " alt="" src="'.$VisionPanelGlazingImageStructure.'"
                                    />





                            </div>

                            <div style="position: absolute;top:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-21' : '4').'px;left:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? (($tt->Leaf2VisionPanel =! 'Yes')? '-456' : '-229') : '-222').'px;">';

                            // if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {
                            //     if (in_array($tt->FireRating, ["FD30", "FD30s"])) {

                            //         $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 18px;"></div>';

                            //     } else if (in_array($tt->FireRating, ["FD60", "FD60s"])) {

                            //         $DoorFrameImage .= '<div class="'.$redstripLeftCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 10px;"></div>
                            //                 <div class="'.$redstripLeftCommonClass.'_'.$sidelight.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 28px;margin-top: 25px;"></div>';
                            //     }
                            // }


                            $DoorFrameImage .= '<img style="width: 46px;height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '107' : '117').'px;" alt="" src="'.$FrameTypeCommon.'" />
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
                                '.
                                    (($tt->FireRating == 'FD30') ?
                                    ' width: 66px;height: 52px;margin-left:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '0' : '-7').'px;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-110' :  ($GlazingSystems['GlazingBeadsPadding'] == 0 ? '-134':'-136.5')).'px;'
                                    :
                                    ' width: 66px;height: 52px;margin-left:'.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '0' : '-7').'px;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-110' :  ($GlazingSystems['GlazingBeadsPadding'] == 0 ? '-134':'-136.5')).'px;')
                                    .'" alt="" src="'.$VisionPanelGlazingImageStructure.'"
                                    >




                            </div>

                            <div style="position: absolute;top: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-21' : '4').'px;left:  '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '-73' : '-66').'px;">';


                            // if ($tt->IntumescentLeapingSealLocation == 'Frame' || $tt->IntumescentLeapingSealLocation == 'Door') {


                            //     if (in_array($tt->FireRating, ["FD30", "FD30s"])) {

                            //         $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 18px;"></div>';

                            //     } else if (in_array($tt->FireRating, ["FD60", "FD60s"])) {

                            //         $DoorFrameImage .= '<div class="'.$redstripRightCommonClass.'_'.$sidelight.'"  style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 10px;"></div>
                            //                 <div class="'.$redstripRightCommonClass.'_'.$sidelight.'" style="border: 0.5px solid black;background-color: red;z-index: 999;position: absolute;height: 8px;width: 3px;box-shadow: none;margin-left: 7px;margin-top: 25px;"></div>';
                            //     }
                            // }
                            $DoorFrameImage .= '<img style="width: 46px; height: '.((!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') ? '107' : '117').'px;" alt="" src="'.$FrameTypeCommon.'">
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
                                    <div style="margin-top: 10px;overflow: auto; padding: 0px 20px;align-items: center height: 150px ;display: flex;">
                                    <div style="float: left;border: aliceblue;padding: 5px 10px;display: flex;width: 50%;">
                                        <div style="display: inline-flex; align-items: center;">
                                            <p style="font-size: 16px; line-height: 23px; padding: 0; margin: 0;">Signature</p>';
                                            if(!empty($fetchSignature->signature_path) && $clientclick == 'yes'){
                                               $elevTbl .= ' <img style="width: 160px;" src="' . public_path($fetchSignature->signature_path) . '"/>';
                                            } else {
                                                $elevTbl .= ' ';
                                            }
                                      $elevTbl .= '  </div>
                                    </div>';

                                    if($clientclick == 'yes'){
                                     $elevTbl .= '<div style="float: right;font-size: 16px;width: 46%;text-align: right;padding: 8px 0;">
                                        Date - '. \Carbon\Carbon::parse($fetchSignature->signed_at)->format('d-m-Y').'
                                    </div>';
                                    } else{
                                        $elevTbl .= '<div style="float: right;font-size: 16px;width: 46%;text-align: right;padding: 8px 0;">
                                        Date -
                                    </div>';
                                    }
                                    $elevTbl .= '
                                </div>
                                </td>
                            </tr>';
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
if($tt->DoorsetType == "SD" &&  $tt->FrameType==null ){
    $elevTbl .= '<td style="width:20%;font-size: 7px !important;">
    <p class="visionpanel_t1_sd">' . $GlassType . '</p>
    <p class="visionpanel_t2_sd">' . $tt->glazingBeadsFixingDetail . '</p>
    <p class="visionpanel_t3_sd">' . $GlazingBeadSpecies . '<br>' . $tt->GlazingBeadsThickness . ' x ' . $tt->glazingBeadsHeight . 'mm</p>
    <p class="visionpanel_t4_sd">' . $ConfigurableItems . '<br>' . $tt->LeafThickness . 'mm</p>
    <p class="visionpanel_t5_sd">' . $tt->LeafThickness . '</p>
    <div class="doorImgBox">' . $VisionPanelGlazingImage . '</div>
</td>';
}else{
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
                        if($tt->FrameOnOff != 1){
                        $elevTbl .= '<tr>
                                    <td class="mytabledata_sd"  colspan="2" style="">
                                    <p class="frame_dd_t1_sd_' . $tt->FrameType . ' frame_dd_t1_sd_'.$sidelight.'" style="margin-left:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && (!empty($tt->Handing) && $tt->Handing != "Left")) ?  ( ($show->Leaf1VisionPanel == "Yes")?'637':'660' ) : '') .'px;
                                            margin-top:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && (!empty($tt->Handing) && $tt->Handing != "Left")) ?  ( ($show->Leaf1VisionPanel == "Yes")?'22px !important':'' ) : '') .'">' . $tt->FrameDepth . 'mm</p>
                                    <p class="frame_dd_t2_sd_' . $tt->FrameType . ' frame_dd_t2_sd_'.$sidelight.'" style="margin-left:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && (!empty($tt->Handing) && $tt->Handing != "Left")) ? ( ($show->Leaf1VisionPanel == "Yes")?'626':'653' ) : '') .'px">' . $tt->FrameThickness . 'mm</p>
                                    <p style="'.(($tt->FrameType == 'Rebated_Frame')?'display: none;':'').'" class="frame_dd_t3_sd_' . $tt->FrameType . ' frame_dd_t3_sd_'.$sidelight.'">' . $FrameTypeWidth . 'mm</p>
                                    <p class="frame_dd_t4_sd_' . $tt->FrameType . ' frame_dd_t4_sd_'.$sidelight.'" style="margin-left:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && (!empty($tt->Handing) && $tt->Handing != "Left")) ?   ( ($show->Leaf1VisionPanel == "Yes")?'587':'613' ) : '') .'px;
                                            margin-top:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && (!empty($tt->Handing) && $tt->Handing != "Left")) ?  ( ($show->Leaf1VisionPanel == "Yes")?'158px !important':'' ) : '') .' ">' . $FrameTypeHeight . 'mm</p>
                                    <p class="frame_dd_t5_sd_' . $tt->FrameType . ' frame_dd_t5_sd_'.$sidelight.'" style="margin-left:'. (
                                            (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped' && (!empty($tt->Handing) && $tt->Handing != "Left")) ?  ( ($show->Leaf1VisionPanel == "Yes")?'530':'550' ) : '') .'px">' . $tt->LeafThickness . '</p>';
                        }

                      if (!empty($tt->FrameType) && $tt->FrameType == 'Scalloped') {
                           $elevTbl .= '
                                <p class="scalloped-'.$tt->Handing.'-1'.(($show->Leaf1VisionPanel == "Yes" && $tt->Handing != "Left")?'-VP':'').'"  ></p>
                                <p class="scalloped-'.$tt->Handing.'-2'.(($show->Leaf1VisionPanel == "Yes" && $tt->Handing != "Left")?'-VP':'').'" >'.$tt->ScallopedWidth.'</p>
                                <p class="scalloped-'.$tt->Handing.'-3'.(($show->Leaf1VisionPanel == "Yes" && $tt->Handing != "Left")?'-VP':'').'" ></p>';
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
                        if($tt->FrameOnOff != 1){
                        $elevTbl .= '<tr>
                                    <td class="mytabledata" colspan="2" style="">

                                    <p class="frame_dd_t1 frame_dd_t1_' . $tt->FrameType.' '.$sidelight.'">' . $tt->FrameDepth . 'mm</p>
                                    <p class="frame_dd_t2 frame_dd_t2_' . $tt->FrameType . ' '.$sidelight.'">' . $tt->FrameThickness . 'mm</p>
                                    <p style="'.(($tt->FrameType == 'Rebated_Frame')?'display: none;':'').'" class="frame_dd_t3 frame_dd_t3_' . $tt->FrameType . ' '.$sidelight.'">' . $FrameTypeWidth . 'mm</p>
                                    <p class="frame_dd_t4 frame_dd_t4_' . $tt->FrameType . ' '.$sidelight.'">' . $FrameTypeHeight . 'mm</p>
                                    <p class="frame_dd_t5 frame_dd_t5_' . $tt->FrameType . ' '.$sidelight.'">' . $tt->LeafThickness . '</p>';
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
                if($FireRatingActualValue == 'NFR'){
                    $gi = Option::where("configurableitems", $configurationItem)
                    ->where("OptionSlug", "Glass_Integrity")
                    ->where("OptionKey", $tt->GlassIntegrity)->first();
                }
                else{
                    $gi = Option::where("configurableitems", $configurationItem)
                    ->where("firerating", $FireRatingActualValue)
                    ->where("OptionSlug", "Glass_Integrity")
                    ->where("OptionKey", $tt->GlassIntegrity)->first();
                }

                $GlassIntegrity = $gi->OptionValue;
            }

            $glazing_beads_word = in_array($configurationItem, [1,2,7,8]) ? 'side_light_glazing_beads' : 'leaf1_glazing_beads';

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
                 $gs = GlazingSystem::join('selected_glazing_system','glazing_system.id','selected_glazing_system.glazingId')->where('selected_glazing_system.userId', $userid)->where('glazing_system.'.$configurationDoor,$tt->configurableitems)->where('glazing_system.Key',$tt->GlazingSystems)->first();
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
                if($Intumescentseals){
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
                if($configurationDoor === 'VicaimaDoorCore' || $configurationDoor === 'MMM'){
                    $op = GlassType::leftJoin('selected_glass_type', function ($join) use ($id): void {
                        $join->on('glass_type.id', '=', 'selected_glass_type.glass_id')
                            ->where('selected_glass_type.editBy', '=', $id);
                    })->where('glass_type.'.$configurationDoor,$tt->configurableitems)->where('glass_type.Key',$tt->SideLight1GlassType)->first();
                    $sl1glasstype = $op->GlassType ?? '';
                }
                else{
                    $op = OverpanelGlassGlazing::leftJoin('selected_overpanel_glass_glazing', function ($join) use ($id): void {
                        $join->on('overpanel_glass_glazing.id', '=', 'selected_overpanel_glass_glazing.glass_glazing_id')
                            ->where('selected_overpanel_glass_glazing.editBy', '=', $id);
                    })->where('overpanel_glass_glazing.'.$configurationDoor,$tt->configurableitems)->where('overpanel_glass_glazing.Key',$tt->SideLight1GlassType)->first();
                    $sl1glasstype = $op->GlassType ?? '';
        }

            }

            $beadingtype = 'N/A';
            if (!empty($tt->SideLight2BeadingType)) {
                $op2 = Option::where(['configurableitems' => $configurationItem, 'UnderAttribute' => $FireRatingActualValue, 'OptionSlug' => $glazing_beads_word, 'OptionKey' => $tt->SideLight2BeadingType])->first();
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

            $OPFLHeight = 'N/A';
            if ($tt->Overpanel == 'Overpanel' || $tt->Overpanel == 'Fan_Light') {
                $opHeight = is_numeric($tt->OPHeigth) ? $tt->OPHeigth : 0;
                $beadThickness = is_numeric($tt->OpBeadThickness) ? $tt->OpBeadThickness : 0;
                $gap = is_numeric($tt->GAP) ? $tt->GAP : 0;

                $OPFLHeight = $opHeight - $beadThickness - $beadThickness;
            }

            $OPFLWeidth = 'N/A';
            if ($tt->Overpanel == 'Overpanel' || $tt->Overpanel == 'Fan_Light') {
                $frameWidth = is_numeric($tt->FrameWidth) ? $tt->FrameWidth : 0;
                $beadThickness = is_numeric($tt->OpBeadThickness) ? $tt->OpBeadThickness : 0;
                $OPFLWeidth = $frameWidth - $beadThickness - $beadThickness;
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
                if($tt->FrameOnOff != 1){
                    $Handing = '';
                    if($tt->Handing == 'Left_Hand_Master_Right_Hand_Slave'){
                        $Handing = 'Primary Leaf Right';
                    }else if($tt->Handing == 'Right_Hand_Master_Left_Hand_Slave'){
                        $Handing = 'Primary Leaf Left';
                    }else{
                        $Handing = $tt->Handing;
                    }
                    $elevTbl .=  '<tr>
                                    <td class="dicription_grey">Handing</td>
                                    <td class="dicription_blank">' . $Handing . '</td>
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
            if($tt->FrameOnOff != 1){
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
            if($quotaion->configurableitems == 4){
                    $elevTbl .= '<tr>
                                    <td class="dicription_grey">Leaf Type</td>
                                    <td class="dicription_blank">' . $tt->LeafConstruction . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Product code</td>
                                    <td class="dicription_blank">' . $tt->DoorDimensionsCode .'</td>
                                </tr>';

                    if ($tt->DoorsetType != 'SD' || $tt->DoorsetType != 'leaf_and_a_half') {
                        $elevTbl .= '<tr>
                                        <td class="dicription_grey">Product code2 </td>
                                        <td class="dicription_blank">' . $tt->DoorDimensionsCode2 .'</td>
                                    </tr>';
                    }

                    $elevTbl .= '<tr>
                                    <td class="dicription_grey">Decorative Groves</td>
                                    <td class="dicription_blank">' . $tt->groovesNumber . '</td>
                                </tr>';
            }else{
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
            if($tt->FrameOnOff != 1){
                $elevTbl .=  '<table id="WithBorder">
                                <tbody>
                                    <tr>
                                        <th class="tblTitle">Frame</th>
                                    </tr>
                                    <tr>
                                        <td class="dicription_grey">Four Sided Frame</td>
                                        <td class="dicription_blank">' . (($tt->FourSidedFrame == 1)?'Yes':'No') . '</td>
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
                if($tt->FrameOnOff != 1){
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
                                if($tt->Overpanel == 'Overpanel'){
                                $elevTbl .= '
                                <tr>
                                    <td class="dicription_grey">OP Panel Width</td>
                                    <td class="dicription_blank">' . $OPFLWeidth . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">OP Panel Height</td>
                                    <td class="dicription_blank">' . $OPFLHeight . '</td>
                                </tr>';
                                } else if($tt->Overpanel == 'Fan_Light'){
                                $elevTbl .= '
                                <tr>
                                    <td class="dicription_grey">FL Width</td>
                                    <td class="dicription_blank">' . $OPFLWeidth . '</td>
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
                                if($tt->SideLight1 == 'Yes'){
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
                                     if($tt->SideLight2 == 'Yes'){
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
                        if($tt->FrameOnOff != 1){
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


            if($isActive == true){
                $elevTbl .= ' <div class="tbl_prn">
                <div style="margin:0 auto;"><h3 style="text-align:center;">Quality Control </h3></div>
                <div class="fr_d_tbl doorImgBox" style="display:flex; justify-content:center;">
                    <table id="NoBorder" style="margin-top:1rem;margin-bottom: 15px;" class="mt-4">
                        <tr>
                            <td colspan="2">
                                <table id="WithBorder" class="tbl1">
                                    <tbody>
                                        <tr>
                                            <td class="marImg" rowspan="2">
                                                <span>';
                    if (!empty($comapnyDetail->ComplogoBase64)) {
                        $elevTbl .= '<img src="' . htmlspecialchars((string) $comapnyDetail->ComplogoBase64) . '" class="imgClass" alt="Logo"/>';
                    } else {
                        $elevTbl .= Base64Image('defaultImg');
                    }

                    $elevTbl .= '</span>
                                            </td>
                                            <td class="tbl_color"><span>Ref</span></td>
                                            <td colspan="3"><span>' . htmlspecialchars((string) $QuotationGenerationId) . '</span></td>
                                            <td class="tbl_color"><span>Project</span></td>
                                            <td><span>' . htmlspecialchars((string) $ProjectName) . '</span></td>
                                            <td class="tbl_color"><span>Prepared By</span></td>
                                            <td><span>' . htmlspecialchars((string) $Username) . '</span></td>
                                        </tr>
                                        <tr>
                                            <td class="tbl_color" style="width:25px;padding-right:5px;"><span>Revision</span></td>
                                            <td style="width:20px;"><span>' . htmlspecialchars((string) $version) . '</span></td>
                                            <td class="tbl_color" style="width:20px;padding-right:5px;"><span>Date</span></td>
                                            <td><span>' . date('Y-m-d') . '</span></td>
                                            <td class="tbl_color" style="width:10px;padding-right:5px;"><span>Customer</span></td>
                                            <td><span>' . htmlspecialchars((string) $customer->CstCompanyName) . '</span></td>
                                            <td class="tbl_color" style="width:60px;padding-right:5px;"><span>Quote name</span></td>
                                            <td><span>' . htmlspecialchars((string) $SalesContact) . '</span></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </td>
                        </tr>
                    </table>
                </div>
                <div class="fr_d_tbl" style=" margin: 0 auto;">

                    <div style="text-align:center; margin-bottom: 20px;">
                        <table style="width: 300px; margin: 0 auto; border: 1px solid #000; border-collapse: collapse;">
                            <tr>
                                <td style="background: #f2f2f2; padding: 8px;">SELECT<br>Door Type</td>
                                <td style="padding: 8px;"><b>Type ' . htmlspecialchars($tt->DoorType) . '</b></td>
                            </tr>
                        </table>
                    </div>

                    <div style="margin: 0 auto; width: 95%;">
                        <table style="width: 100%; border-collapse: collapse; border: 1px solid #000;">
                            <thead style="background: #f9f9f9;">
                                <tr>
                                    <th style="border: 1px solid #000; padding: 6px;">Door / Screen No</th>
                                    <th style="border: 1px solid #000; padding: 6px;">Assign Plot Ref</th>
                                    <th style="border: 1px solid #000; padding: 6px;">Certification No</th>
                                    <th style="border: 1px solid #000; padding: 6px;">Quality Check- CNC</th>
                                    <th style="border: 1px solid #000; padding: 6px;">Quality Check- VP</th>
                                    <th style="border: 1px solid #000; padding: 6px;">Quality Check- Assembly</th>
                                    <th style="border: 1px solid #000; padding: 6px;">Door Plug</th>
                                    <th style="border: 1px solid #000; padding: 6px;">Frame Plug</th>
                                    <th style="border: 1px solid #000; padding: 6px;">Glass Stamp Visible</th>
                                </tr>
                            </thead>
                            <tbody>';
                foreach ($DoorNumber as $bb) {
                    $elevTbl .= '<tr>
                        <td style="border: 1px solid #000; padding: 5px;">' . $bb->doorNumber . '</td>
                        <td style="border: 1px solid #000; padding: 5px;">' . $bb->plot_ref_no . '</td>
                        <td style="border: 1px solid #000; padding: 5px;">' . $bb->certification_no . '</td>
                        <td style="border: 1px solid #000; padding: 5px;"></td>
                        <td style="border: 1px solid #000; padding: 5px;"></td>
                        <td style="border: 1px solid #000; padding: 5px;"></td>
                        <td style="border: 1px solid #000; padding: 5px;"></td>
                        <td style="border: 1px solid #000; padding: 5px;"></td>
                        <td style="border: 1px solid #000; padding: 5px;"></td>
                    </tr>';
                }
                $elevTbl .= '
                            </tbody>
                        </table>
                    </div>
                </div>';
                if ($PageBreakCounts < $TotalItems) {
                    $elevTbl .= '<div class="page-break"></div>';
                }

                $PageBreakCounts++;
            }
        }

        // return $elevTbl;
        // return view('Company.pdf_files.elevationDrawing', compact('elevTbl'));
        $pdf6 = PDF::loadView('Company.pdf_files.elevationDrawing', ['elevTbl' => $elevTbl]);

        $isActive = filter_var($isActive, FILTER_VALIDATE_BOOLEAN);

        if ($isActive) {
            return $pdf6->download('ElevationDrawing.pdf');  // ✅ Forces download with correct name
        } else {
            $path6 = public_path('allpdfFile');
            if (!file_exists($path6)) {
                mkdir($path6, 0777, true);
            }

            $fileName6 = $id . '6' . '.pdf';
            $pdf6->save($path6 . '/' . $fileName6);
        }


        // back page design



        // end

        // elevation drawing for side screen
        $elevSideScreenTbl = '';
        $eds = SideScreenItem::join('side_screen_item_master', 'side_screen_items.id', 'side_screen_item_master.ScreenId')->where(['side_screen_items.QuotationId' => $quatationId])
        ->where(['side_screen_items.VersionId' => $versionID])
        ->select('side_screen_items.*')
        ->groupBy('side_screen_item_master.ScreenID')
        ->get();
        $TotalItems = count($eds->toArray());


        $PageBreakCount = 1;

        foreach ($eds as $tt) {
            // dd($tt);
            $countDoorNumberSide = SideScreenItemMaster::where('ScreenId', $tt->id)->count();
            $DoorNumberS = SideScreenItemMaster::where('ScreenId', $tt->id)->get();
            $doorNoS = '';
            foreach ($DoorNumberS as $bb) {
                $doorNoS .= '<span style="padding-left:5px;">' . $bb->screenNumber . '</span>';
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
                $svgFileS = str_contains((string) $tt->SvgImage, '.png') ? URL('/') . '/uploads/files/' . $tt->SvgImage : $tt->SvgImage;
            } else {
                $svgFileS = URL('/') . '/uploads/files/no_image_prod.jpg';
            }

            $IsLeafEnabled = 'colspan="2"';
            $elevSideScreenTbl .=
                '
                <div id="headText">
                    <b>Elevation Drawing Side Screen</b>
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
                $elevSideScreenTbl .=
                    '<img src="' . $comapnyDetail->ComplogoBase64 . '" class="imgClass" alt="Logo"/>';
            } else {
                $elevSideScreenTbl .= Base64Image('defaultImg');
            }

            $elevSideScreenTbl .=
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
                                                <td class="tbl_color"><span>Revision</span></td>
                                                <td colspan="3"><span>' . $version . '</span></td>
                                                <td class="tbl_color"><span>Customer</span></td>
                                                <td><span>' . $customer->CstCompanyName . '</span></td>
                                                <td class="tbl_color"><span>Quote name</span></td>
                                                <td><span>' . $SalesContact . '</span></td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>';
            $elevSideScreenTbl .= '<tr>';


                $elevSideScreenTbl .= '<td ' . $IsLeafEnabled . '>
                <div class="doorImgBox">

                    <img src="' . $svgFileS . '" class="doorImg" style="">
                </div>
            </td>
            </tr>';

            $SideScreenChamfered = \Config::get('constants.base64Images.SideScreenChamfered');
            $SideScreenSquare = \Config::get('constants.base64Images.SideScreenSquare');
            $SelectedFrameMaterial = LippingSpecies::where("id", $tt->FrameMaterial)->first();
            // dd($tt->FrameMaterial);
            // dd($SelectedFrameMaterial);
            $FrameMaterials = $SelectedFrameMaterial != null ? $SelectedFrameMaterial->SpeciesName : 'N/A';

            if($tt->GlazingBeadShape == 'Square'){
                $elevSideScreenTbl .= '<td style=" width:300px;margin: 0 auto;position: relative;">
                <div class="">
                    <img style="width: 220px; position: absolute;top: -160px;height: 320px;right:65px;z-index: -1;" src="' . $SideScreenSquare . '" class="" >
                </div>

            </td>

            </tr>';
            $elevSideScreenTbl .= '<td style="width:20%;font-size: 7px !important;">
            <p class="visionpanel_t1">' . $tt->SinglePane . '</p>
            <p class="visionpanel_t2">' . $tt->GlazingSystemFixingDetail . '</p>
            <p class="visionpanel_t3">' . $tt->GlazingBeadShape . '<br>' . $tt->GlazingBeadWidth . ' x ' . $tt->GlazingBeadHeight . 'mm</p>
            <p class="visionpanel_t4">' . $FrameMaterials . '</p>
        </td>';
            }

            if($tt->GlazingBeadShape == 'Chamfer'){
                $elevSideScreenTbl .= '<td style=" width:300px;margin: 0 auto;position: relative;">
                <div class="">
                    <img style="width: 220px; position: absolute;top: -160px;height: 320px;right:65px;z-index: -1;" src="' . $SideScreenChamfered . '" class="" >
                </div>

            </td>
            </tr>';
            $elevSideScreenTbl .= '<td style="width:20%;font-size: 7px !important;">
            <p class="visionpanel_t1">' . $tt->SinglePane . '</p>
            <p class="visionpanel_t2">' . $tt->GlazingSystemFixingDetail . '</p>
            <p class="visionpanel_t3">' . $tt->GlazingBeadShape . '<br>' . $tt->GlazingBeadWidth . ' x ' . $tt->GlazingBeadHeight . 'mm</p>
            <p class="visionpanel_t4">' . $FrameMaterials . '</p>
        </td>';
            }


            $elevSideScreenTbl .= '</table>
                    </div>
                    <div id="section-right">
                        <table id="WithBorder" class="tbl3">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">General</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Screen Type</td>
                                    <td class="dicription_blank">' . $tt->ScreenType . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Description</td>
                                    <td class="dicription_blank">Single Screen</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Fire Rating</td>
                                    <td class="dicription_blank">' . $tt->FireRating . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">SO WIdth</td>
                                    <td class="dicription_blank">' . $tt->SOWidth . '</td>
                                </tr>';

                    $elevSideScreenTbl .=  '<tr>
                                    <td class="dicription_grey">SO Height</td>
                                    <td class="dicription_blank">' . $tt->SOHeight . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">SO Depth</td>
                                    <td class="dicription_blank">' . $tt->SODepth . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Tolerance</td>
                                    <td class="dicription_blank">' . $tt->Tolerance . '</td>
                                </tr>';

                    $elevSideScreenTbl .= '<tr>
                                    <td class="dicription_grey">Frame Thickness</td>
                                    <td class="dicription_blank">' . $tt->FrameThickness . '</td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Structural Opening & Door Leaf Dimensions</th>
                                </tr>';
                $elevSideScreenTbl .=  '  <tr>
                                    <td class="dicription_grey">Frame Material</td>
                                    <td class="dicription_blank">' . lippingName($tt->FrameMaterial) . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Frame Finish</td>
                                    <td class="dicription_blank">' . $tt->Finish . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Frame WIdth</td>
                                    <td class="dicription_blank">' . $tt->FrameWidth . '</td>
                                </tr>';

            $elevSideScreenTbl .=  '  <tr>
                                    <td class="dicription_grey">Frame Height</td>
                                    <td class="dicription_blank">' . $tt->FrameHeight . '</td>
                                </tr>';

            $elevSideScreenTbl .=         '<tr>
                                    <td class="dicription_grey">Frame Depth</td>
                                    <td class="dicription_blank">' . $tt->FrameDepth . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glazing Bead Shape</td>
                                    <td class="dicription_blank">' . $tt->GlazingBeadShape . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glazing Bead Height</td>
                                    <td class="dicription_blank">' . $tt->GlazingBeadHeight . '</td>
                                </tr>';

            $elevSideScreenTbl .=         '<tr>
                                    <td class="dicription_grey">Glazing Bead Width</td>
                                    <td class="dicription_blank">' . $tt->GlazingBeadWidth . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glazing Bead Species</td>
                                    <td class="dicription_blank">' .  lippingName($tt->GlazingBeadMaterial). '</td>
                                </tr>';

            $elevSideScreenTbl .=         '
                                <tr>
                                    <td class="dicription_grey">Transom Width</td>
                                    <td class="dicription_blank">' . $tt->TransomWidth1 . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Transom 1 Thickness</td>
                                    <td class="dicription_blank">' . $tt->Transom1Thickness . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Transom 2 Thickness</td>
                                    <td class="dicription_blank">' . $tt->Transom2Thickness . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Transom 3 Thickness</td>
                                    <td class="dicription_blank">' . $tt->Transom3Thickness . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Transom Material</td>
                                    <td class="dicription_blank">' .lippingName($tt->TransomMaterial) . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Mullion 1 Thickness</td>
                                    <td class="dicription_blank">' .$tt->Mullion1Thickness . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Mullion 2 Thickness</td>
                                    <td class="dicription_blank">' .$tt->Mullion2Thickness . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Mullion 3 Thickness</td>
                                    <td class="dicription_blank">' .$tt->Mullion3Thickness . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Transom Finish </td>
                                    <td class="dicription_blank">' . $tt->Finish . '</td>
                                </tr>
                            </tbody>
                        </table>';
            $elevSideScreenTbl .=  '<table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Lipping And Intumescent</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glass Type</td>
                                    <td class="dicription_blank">' . $tt->SinglePane  . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glazing System</td>
                                    <td class="dicription_blank">' . $tt->GlazingSystem . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Glass Liner</td>
                                    <td class="dicription_blank">' . $tt->GlassLiner . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Sub Frame Bottom Thickness</td>
                                    <td class="dicription_blank">' . $tt->SubFrameBottomThickness . '</td>
                                </tr>
                            </tbody>
                        </table>
                        <table id="WithBorder">
                            <tbody>
                                <tr>
                                    <th class="tblTitle">Vision Panel</th>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Sub Frame Species </td>
                                    <td class="dicription_blank">' . lippingName($tt->SubFrameMaterial) . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Acoustics </td>
                                    <td class="dicription_blank">' . $tt->Acoustic . '</td>
                                </tr>
                                <tr>
                                    <td class="dicription_grey">Special Feature </td>
                                    <td class="dicription_blank">' .$tt->SpecialFeatuers . '</td>
                                </tr>
                            </tbody>
                        </table>';

                        $elevSideScreenTbl .=  '</div></div>
                    <div id="footer">
                        <h3><b>Total Side Screens: ' . $countDoorNumberSide . ',Screen No-' . $doorNoS . '</b></h3>

                    </div>
                ';

            if ($PageBreakCount < $TotalItems) {
                $elevSideScreenTbl .= '<div class="page-break"></div>';
            }

            $PageBreakCount++;
        }

        //  return $elevSideScreenTbl;
        // return view('Company.pdf_files.elevationDrawingSideScreen', compact('elevSideScreenTbl'));
        $pdf8 = PDF::loadView('Company.pdf_files.elevationDrawingSideScreen',['elevSideScreenTbl' => $elevSideScreenTbl]);
        $path8 = public_path() . '/allpdfFile';
        $fileName8 = $id . '8' . '.' . 'pdf';
        $pdf8->save($path8 . '/' . $fileName8);
        // end

        $fileName7 = '';
        if($IronmongeryData !== '' && $IronmongeryData !== '0'){
            $pdf7 = PDF::loadView('Company.pdf_files.IronmongeryData', ['IronmongeryData' => $IronmongeryData]);
            $path7 = public_path() . '/allpdfFile';
            $fileName7 = $id . '7' . '.' . 'pdf';
            // return $pdf7->download('IronmongeryData.pdf');
            $pdf7->save($path7 . '/' . $fileName7);
        }

        // Document PDF
        $pdf_document = SettingPDFDocument::where('UserId', $id)->first();
        $pdf5 = PDF::loadView('Company.pdf_files.documentpdf', ['pdf_document' => $pdf_document]);
        $path5 = public_path() . '/allpdfFile';
        $fileName5 = $id . '5' . '.' . 'pdf';
        $pdf5->save($path5 . '/' . $fileName5);



        $PDFfilename = public_path() . '/allpdfFile' . '/' . $quotaion->QuotationGenerationId . '_' . $version . '.pdf';

        $pdfFiles = [];

        // Common files always included
        $pdfFiles[] = public_path('allpdfFile/' . $fileName1);
        $pdfFiles[] = public_path('allpdfFile/' . $fileName2);
        $pdfFiles[] = public_path('allpdfFile/' . $fileName2_1);
        $pdfFiles[] = public_path('allpdfFile/' . $fileName3);

        // Add fileName2_2 only if it's set and not empty
        if (!empty($fileName2_2)) {
            $pdfFiles[] = public_path('allpdfFile/' . $fileName2_2);
        }

        // Conditional blocks
        if ($IronmongeryData !== '' && $IronmongeryData !== '0') {
            $pdfFiles[] = public_path('allpdfFile/' . $fileName4_2);
            $pdfFiles[] = public_path('allpdfFile/' . $fileName4);
            $pdfFiles[] = public_path('allpdfFile/' . $fileName9);
            $pdfFiles[] = public_path('allpdfFile/' . $fileName6);
            $pdfFiles[] = public_path('allpdfFile/' . $fileName8);
            $pdfFiles[] = public_path('allpdfFile/' . $fileName7);
            $pdfFiles[] = public_path('allpdfFile/' . $fileName5);
        } else {
            $pdfFiles[] = public_path('allpdfFile/' . $fileName4_2);
            $pdfFiles[] = public_path('allpdfFile/' . $fileName4);
            $pdfFiles[] = public_path('allpdfFile/' . $fileName9);
            $pdfFiles[] = public_path('allpdfFile/' . $fileName6);
            $pdfFiles[] = public_path('allpdfFile/' . $fileName8);
            $pdfFiles[] = public_path('allpdfFile/' . $fileName5);
        }

        // If $ed count is 0, override with a smaller set
        if (count($ed) == 0) {
            $pdfFiles = [];
            $pdfFiles[] = public_path('allpdfFile/' . $fileName1);
            $pdfFiles[] = public_path('allpdfFile/' . $fileName2);
            $pdfFiles[] = public_path('allpdfFile/' . $fileName2_1);

            if (!empty($fileName2_2)) {
                $pdfFiles[] = public_path('allpdfFile/' . $fileName2_2);
            }

            $pdfFiles[] = public_path('allpdfFile/' . $fileName9);
            $pdfFiles[] = public_path('allpdfFile/' . $fileName8);
            $pdfFiles[] = public_path('allpdfFile/' . $fileName5);
        }

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

            // unlink($mergedFilePath); (27-11-2024 comment these code bcs it deleted the file to the system and getting 404 not found when send to client the quotations.)

            foreach ($pdfFiles as $unlinkPath) {
                unlink($unlinkPath);
            }

    }

    public function submitSignature(Request $request)
    {
        $request->validate([
            'quotationId' => 'required',
            'versionId'   => 'required',
            'userId'      => 'required',
            'signature'   => 'required', // signature image (base64 from canvas)
            'agree'       => 'accepted', // checkbox must be ticked
        ]);

        $quotationId = $request->quotationId;
        $versionId   = $request->versionId;
        $userId      = $request->userId;

        // 🔹 Decode and save the signature image
        $signatureData = $request->input('signature');
        $signaturePath = null;

        if ($signatureData) {
            // remove the base64 prefix if present
            $signature = str_replace('data:image/png;base64,', '', $signatureData);
            $signature = str_replace(' ', '+', $signature);
            $signatureImage = base64_decode($signature);

            // choose your folder inside public/
            $signaturePath = 'signatures/' . uniqid() . '.png';

            // save directly into public folder
            file_put_contents(public_path($signaturePath), $signatureImage);
        }


        // 🔹 Save record in DB
        ClientSignature::create([
            'quotation_id' => $quotationId,
            'version_id'   => $versionId,
            'user_id'      => $userId,
            'signature_path' => $signaturePath,
            'signed_at'    => now(),
        ]);
        $clientclick = 'yes';

        // 🔹 Call your existing invoice generator
        return $this->printinvoiceclient($quotationId, $versionId, null, $userId,$clientclick);
    }

    public function testprintinvoice(Request $request)
    {

        return 'ff';
        $file = public_path() . '/quotationFiles/#DEF8100016_1.pdf';
        return response()->download($file);
    }


    public function printinvoiceinexcel($quatationId, $versionID)
    {
        $quotaion = Quotation::where('id', $quatationId)->first();
        $QuotationGenerationId = $quotaion->QuotationGenerationId;
        if($quotaion->configurableitems == 4){
            return Excel::download(new InvoiceInExcelVicaima($quatationId, $versionID), $QuotationGenerationId . '.xlsx', \Maatwebsite\Excel\Excel::XLSX,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        }else{
            return Excel::download(new InvoiceInExcel($quatationId, $versionID), $QuotationGenerationId . '.xlsx', \Maatwebsite\Excel\Excel::XLSX,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
        }
    }
}
