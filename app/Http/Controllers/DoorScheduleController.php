<?php

namespace App\Http\Controllers;

// use App\IronmongeryInfoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Item;
use App\Models\DoorSchedule;
use App\Models\DoorDimension;
use App\Models\IntumescentSealLeafType;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ScheduleOrder;
use App\Exports\BomCalculationExport;
use App\Exports\SideScreenExport;
use App\Exports\IronmongeryExport;
use App\Exports\ScheduleOrderNew;
use App\Exports\ScheduleOrderVicaima;
use App\Exports\ScheduleOrder2;
use App\Exports\BomDoorTypeExport;
use App\Exports\AllQuotationExport;
use App\Imports\DoorScheduleImport;
use App\Models\Quotation;
use App\Models\CompanyQuotationCounter;
use App\Models\CompanyOrderCounter;
use App\Models\Customer;
use App\Models\Option;
use App\Models\ScreenBOMCalculation;
use App\Models\Company;
use Response;
use App\Models\CustomerContact;
use Illuminate\Support\Facades\Auth;
use App\Models\ShippingAddress;
use App\Models\Project;
use App\Models\QuotationVersion;
use App\Models\QuotationVersionItems;
use App\Models\User;
use App\Models\BOMCalculation;
use DB;
use View;
use Illuminate\Support\Facades\Storage;
use PDF;
use PdfMerger;
use App\Models\SettingPDF1;
use App\Models\SettingPDF2;
use App\Models\SettingPDFfooter;
use App\Models\SettingPDFDocument;
use App\Models\QuotationContactInformation;
use App\Models\QuotationShipToInformation;
use App\Models\QuotationSiteDeliveryAddress;
use App\Models\Tooltip;
use App\Models\AddIronmongery;
use App\Models\NonConfigurableItems;
use App\Models\NonConfigurableItemStore;

use App\Models\BOMSetting;
use App\Models\BOMDetails;
use App\Models\SettingBOMCost;
use App\Models\ItemMaster;
use App\Models\SideScreenItemMaster;
use App\Models\Floor;

use App\Models\LippingSpecies;
use App\Models\LippingSpeciesItems;
use App\Models\SelectedLippingSpeciesItems;

use App\Models\Color;
use App\Models\ConfigurableDoorFormula;
use App\Models\Items;
use App\Models\SideScreenItem;
use App\Models\ConfigurableItems;
use App\Models\ProjectFiles;
use App\Models\ProjectFilesDS;
use App\Models\SettingCurrency;
use App\Models\SelectedDoordimension;
use App\Models\SelectedIronmongery;
use App\Models\IronmongeryInfoModel;
use App\Models\GeneralLabourCost;
use App\Models\SettingIntumescentSeals2;
use App\Models\SelectedOption;
use App\Models\FavoriteItem;
use App\Models\IntumescentSealColor;
use App\Models\DoorLeafFacing;
use App\Models\GlassType;
use App\Models\GlazingSystem;
use App\Models\SelectedArchitraveType;
use App\Models\ArchitraveType;
use App\Models\DoorFrameConstruction;

class DoorScheduleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add(request $request)
    {
        // $customers = CustomerContact::join('customers','customers.ID','customer_contacts.MainContractorId')->where('customers.UserId',Auth::user()->id)->orderBy('customers.id','desc')->get();
        // echo"<pre>";
        // print_r($customers);
        // die();
        if (Auth::user()->UserType == "2") {
            $customers = CustomerContact::join('customers', 'customers.id', 'customer_contacts.MainContractorId')->select('customers.id', 'customer_contacts.FirstName', 'customer_contacts.LastName')->where('customers.UserId', Auth::user()->id)->orderBy('customers.id', 'desc')->get();
        } else {
            $customers = CustomerContact::join('customers', 'customers.id', 'customer_contacts.MainContractorId')->select('customers.id', 'customer_contacts.FirstName', 'customer_contacts.LastName')->where('customers.UserId', Auth::user()->id)->orderBy('customers.id', 'desc')->get();
        }

        return view('DoorSchedule.AddQuotation', ['customers' => $customers]);
    }


    public function store(request $request)
    {
        // $projects = Project::where('GeneratedKey',$request->ProjectId)->first();
        // if(!empty($projects) && count((array)$projects)>0){
        //     $projectId= $projects->id;
        // } else {
        //     $projectId= 0;
        // }


        $array_data = explode(",", $request->Contact);
        $quotationId = $request->quotationId;
        $selectVersionID = $request->selectVersionID;


        // Quote Information fields
        if (!empty($quotationId)) {
            $array_data[1] = 0;
            $quotation = Quotation::find($quotationId);
        } else {
            $quotation = new Quotation();
            $quotation->MainContractorId = $request->MainContractorId;
            $quotation->created_at = date('Y-m-d H:i:s');
        }

        if ($request->ExpiryDate != '' && $request->QuotationName != '' && $request->Currency != '' && $request->Email != '') {
            $quotation->flag = 1;
        } else {
            $quotation->flag = 0;
        }

        $quotation->ProjectId = $request->projectId;
        $quotation->UserId = Auth::user()->id;
        $quotation->QuotationName = $request->QuotationName;
        // $quotation->PONumber = $request->ponumber;
        $quotation->Currency = $request->Currency;
        $quotation->SalesContact = $request->SalesContact;
        $quotation->ExchangeRate = $request->ExchangeRate;
        $quotation->ExpiryDate = date('Y-m-d', strtotime($request->ExpiryDate));
        $quotation->FollowUpDate = date('Y-m-d', strtotime($request->FollowUpDate));
        $quotation->QuotationStatus = $request->QuotationStatus;
        $quotation->editBy = Auth::user()->id;
        $quotation->QSCustomerContactId = $array_data[1];
        $quotation->updated_at = date('Y-m-d H:i:s');
        $quotation->save();

        // Site Contact Information
        $quoteContactInfoCount = QuotationContactInformation::where('QuotationId', $request->quotationId)->count();
        if ($quoteContactInfoCount > 0) {
            $a = QuotationContactInformation::where('QuotationId', $request->quotationId)->first();
            $quoteContactInfo = QuotationContactInformation::find($a->id);
        } else {
            $quoteContactInfo = new QuotationContactInformation();
            $quoteContactInfo->QuotationId = $request->quotationId;
            $quoteContactInfo->created_at = date('Y-m-d H:i:s');
        }

        $quoteContactInfo->Contact = $request->Contact;
        $quoteContactInfo->Email = $request->Email;
        $quoteContactInfo->Phone = $request->Phone;
        $quoteContactInfo->Mobile = $request->Mobile;
        $quoteContactInfo->Fax = $request->Fax;
        $quoteContactInfo->updated_at = date('Y-m-d H:i:s');
        $quoteContactInfo->save();

        // Site Delivery Address
        $count = count($request->Address1);
        $i = 0;
        while ($count > $i) {
            $id = $request->quotation_sitedeliveryaddressID[$i];
            if ($id != '') {
                $DeliveryAddress = QuotationSiteDeliveryAddress::find($request->quotation_sitedeliveryaddressID[$i]);
            } else {
                $DeliveryAddress = new QuotationSiteDeliveryAddress();
                $DeliveryAddress->QuotationId = $request->quotationId;
                $DeliveryAddress->created_at = date('Y-m-d H:i:s');
            }

            $DeliveryAddress->Address1 = $request->Address1[$i];
            $DeliveryAddress->Address2 = $request->Address2[$i];
            $DeliveryAddress->Country = $request->Country[$i];
            $DeliveryAddress->City = $request->City[$i];
            $DeliveryAddress->PostalCode = $request->PostalCode[$i];
            $DeliveryAddress->updated_at = date('Y-m-d H:i:s');
            $DeliveryAddress->save();

            $i++;
        }



        // Shipping Information + Payment Information
        $quoteShipInfoCount = QuotationShipToInformation::where('QuotationId', $request->quotationId)->count();
        if ($quoteShipInfoCount > 0) {
            $b = QuotationShipToInformation::where('QuotationId', $request->quotationId)->first();
            $quoteShipInfo = QuotationShipToInformation::find($b->id);
        } else {
            $quoteShipInfo = new QuotationShipToInformation();
            $quoteShipInfo->QuotationId = $request->quotationId;
            $quoteShipInfo->created_at = date('Y-m-d H:i:s');
        }

        $quoteShipInfo->DeliveryRestrictions = $request->DeliveryRestrictions;
        $quoteShipInfo->WagonPreference = $request->WagonPreference;
        $quoteShipInfo->Booking = $request->Booking;
        $quoteShipInfo->Deliverypolicy = $request->Deliverypolicy;
        $quoteShipInfo->silver = $request->silver;
        $quoteShipInfo->gold = $request->gold;
        $quoteShipInfo->Offloading = $request->Offloading;
        $quoteShipInfo->NoOfDeliveries = $request->NoOfDeliveries;
        $quoteShipInfo->ActualNoOfDeliveries = $request->ActualNoOfDeliveries;
        $quoteShipInfo->Costperdelivery = $request->Costperdelivery;
        $quoteShipInfo->AverageNoDoorsetsperdrop = $request->AverageNoDoorsetsperdrop;
        $quoteShipInfo->PaymentMethod = $request->PaymentMethod;
        $quoteShipInfo->PaymentTerms = $request->PaymentTerms;
        $quoteShipInfo->updated_at = date('Y-m-d H:i:s');
        $quoteShipInfo->save();

        $Items = Item::where(['items.QuotationId' => $quotationId, 'items.VersionId' => $selectVersionID])->get();

        $quotation = Quotation::where('id',$quotationId)->first();

        if(!empty($Items)){
            foreach($Items as $data){
                $itemid = $data->itemId;

                BOMUpdate($data, $quotation->configurableitems);

                $BOMCalculation = BOMCalculation::select('*')->where('QuotationId',$quotationId)->where('DoorType',$data->DoorType)->where('itemId',$itemid)->get();
                $GTSellPrice = 0;
                $GTSellPriceTotal = 0;
                if(!empty($BOMCalculation)){
                    foreach($BOMCalculation as $value){
                        if($value->Category != 'Ironmongery&MachiningCosts'){
                            $GTSellPrice += $value->GTSellPrice;
                        }
                    }

                    $ItemMaster = ItemMaster::where('itemID',$itemid)->get()->count();
                    $GTSellPriceTotal = ($ItemMaster > 0) ? round(($GTSellPrice/$ItemMaster),2) : $GTSellPrice;
                }

                Item::where('itemId', $itemid)->update([
                    'DoorsetPrice' => $GTSellPriceTotal,
                ]);
            }
        }

        $NonConfigurableItemStore = NonConfigurableItemStore::where(['non_configurable_item_store.quotationId' => $quotationId, 'non_configurable_item_store.versionId' => $selectVersionID])->get();
        $currencyPrice = getCurrencyRate($quotationId);
        $margin = QuotationVersion::where(['quotation_id'=> $quotationId,'id'=> $selectVersionID])->value('discountQuotation');
        if(!empty($NonConfigurableItemStore)){
            foreach($NonConfigurableItemStore as $val){
                $NonConfigurableItems = NonConfigurableItems::where('id',$val->nonConfigurableId)->first();
                $QuoteSummaryDiscountValue = 0 ;
                if($margin != 0){
                    $QuoteSummaryDiscountValue = ($NonConfigurableItems->price * $margin) / 100;
                }

                $price = ($margin > 0)? ($NonConfigurableItems->price + $QuoteSummaryDiscountValue):
                ($NonConfigurableItems->price - $QuoteSummaryDiscountValue);
                NonConfigurableItemStore::where('id', $val->id)->update([
                    'price' => $price * $currencyPrice,
                    'total_price' => $price * $val->quantity * $currencyPrice,
                ]);
            }
        }

        $SideScreenItems = SideScreenItem::where(['side_screen_items.QuotationId' => $quotationId, 'side_screen_items.VersionId' => $selectVersionID])->get();
        if(!empty($SideScreenItems)){
            foreach($SideScreenItems as $data){
                $id = $data->id;
                sideScreenBOM($data);

                $ScreenBOMCalculation = ScreenBOMCalculation::select('*')->where('QuotationId',$quotationId)->where('ScreenType',$data->ScreenType)->where('ScreenId',$id)->get();
                $GTSellPrice = 0;
                $GTSellPriceTotal = 0;
                if(!empty($ScreenBOMCalculation)){
                    foreach($ScreenBOMCalculation as $value){
                        $GTSellPrice += $value->GTSellPrice;
                    }

                    $ItemMaster = SideScreenItemMaster::where('ScreenId',$id)->get()->count();
                    $GTSellPriceTotal = round(($GTSellPrice/$ItemMaster),2);
                }

                SideScreenItem::where('id', $id)->update([
                    'ScreenPrice' => $GTSellPriceTotal
                ]);
            }
        }

        \Session::flash('status', 'success');
        \Session::flash('message', 'Headers added successfully!!!');

        return redirect('quotation/generate/' . $quotationId . '/' . $selectVersionID);
    }

    public function delquotationDeliveryAddress(Request $request): int
    {
        $id = $request->quotationDeliveryAddressID;
        QuotationSiteDeliveryAddress::where('id', $id)->delete();
        return 1; //  1 = success
    }


    public function quotation_details()
    {
        return view('DoorSchedule.QuotationListDetails');
    }

    public function quotationList(Request $Request){
        ini_set('memory_limit','-1');
        $LoginUserId = Auth::user()->id;
        $assigned_project = '';
        $OpenCount = '';
        $OrderedCount = '';
        $QuoteReturnedCount = '';
        $OrderValueCount = '';
        $UserType = Auth::user()->UserType;
        switch ($UserType) {
            case 1:
                $OpenCount = Quotation::where(['QuotationStatus' => 'Open'])->count();
                $OrderedCount = Quotation::where(['QuotationStatus' => 'Ordered'])->count();
                $QuoteReturnedCount = Quotation::where(['QuotationStatus' => 'Quote Returned'])->count();
                $OrderValueCount = Quotation::where(['QuotationStatus' => 'Order Value'])->count();
                $assigned_project = '';
                if (empty($Request->id)) {
                    $data = Quotation::leftjoin("project", "project.id", "quotation.ProjectId")
                        ->leftJoin('companies', 'companies.id', 'quotation.CompanyId')
                        ->select('quotation.*', 'quotation.id as QuotationId', 'companies.CompanyName', 'project.*')
                        ->where('QuotationGenerationId', '!=', null)
                        ->orderBy('quotation.id', 'desc')
                        ->get();
                } else {
                    $data = Quotation::leftjoin("project", "project.id", "quotation.ProjectId")
                        ->leftJoin('companies', 'companies.id', 'quotation.CompanyId')
                        ->select('quotation.*', 'quotation.id as QuotationId', 'companies.CompanyName', 'project.*')
                        ->where([['quotation.UserId', '=', $Request->id]])
                        ->where('QuotationGenerationId', '!=', null)
                        ->orderBy('id', 'desc')
                        ->get();
                }

                break;

            case 2:

                $login_company_id = get_company_id(Auth::user()->id)->id ?? Auth::user()->id;
                $OpenCount = Quotation::where(['QuotationStatus' => 'Open', 'CompanyId' => $login_company_id])->count();
                $OrderedCount = Quotation::where(['QuotationStatus' => 'Ordered', 'CompanyId' => $login_company_id])->count();
                $QuoteReturnedCount = Quotation::where(['QuotationStatus' => 'Quote Returned', 'CompanyId' => $login_company_id])->count();
                $OrderValueCount = Quotation::where(['QuotationStatus' => 'Order Value', 'CompanyId' => $login_company_id])->count();
                $assigned_project = '';
                if (empty($Request->id)) {
                    $data = Quotation::leftjoin("project", "project.id", "quotation.ProjectId")
                        ->leftJoin('companies', 'companies.id', 'quotation.CompanyId')
                        ->select('quotation.*', 'quotation.id as QuotationId', 'companies.CompanyName', 'project.*')
                        ->where([['quotation.CompanyId', '=', $login_company_id]])
                        ->where('QuotationGenerationId', '!=', null)
                        ->orderBy('quotation.id', 'desc')
                        ->get();
                } else {
                    $data = Quotation::leftjoin("project", "project.id", "quotation.ProjectId")
                        ->leftJoin('companies', 'companies.id', 'quotation.CompanyId')
                        ->select('quotation.*', 'quotation.id as QuotationId', 'companies.CompanyName', 'project.*')
                        ->where('QuotationGenerationId', '!=', null)
                        ->where([
                            ['quotation.UserId', '=', $Request->id],
                            ['quotation.CompanyId', '=', $login_company_id]
                        ])
                        ->orderBy('quotation.id', 'desc')
                        ->get();
                }

                break;

            case 3;
                $users = User::where('UserType', 3)->where('id', Auth::user()->id)->first();
                $UserId = [Auth::user()->id, intval($users->CreatedBy)];
                // $data = Quotation::leftjoin("project","project.id","quotation.ProjectId")
                // ->leftJoin('companies','companies.id','quotation.CompanyId')
                // ->select('quotation.*', 'quotation.id as QuotationId', 'companies.CompanyName', 'project.*')
                // ->wherein('quotation.UserId', $UserId)
                // ->orderBy('quotation.id','desc')
                // ->get();
                $login_company_id = get_company_id($users->CreatedBy)->id;
                $data = Quotation::leftjoin("project", "project.id", "quotation.ProjectId")
                    ->leftJoin('companies', 'companies.id', 'quotation.CompanyId')
                    ->select('quotation.*', 'quotation.id as QuotationId', 'companies.CompanyName', 'project.*')
                    ->where('QuotationGenerationId', '!=', null)
                    ->where([
                        // ['quotation.UserId', '=', $Request->id],
                        ['quotation.CompanyId', '=', $login_company_id]
                    ])
                    ->orderBy('quotation.id', 'desc')
                    ->get();
                break;

            case 4:
                $login_architect_id = get_architect_id(Auth::user()->id)->id;
                $OpenCount = Quotation::where(['QuotationStatus' => 'Open', 'CompanyId' => $login_architect_id])->count();
                $OrderedCount = Quotation::where(['QuotationStatus' => 'Ordered', 'CompanyId' => $login_architect_id])->count();
                $QuoteReturnedCount = Quotation::where(['QuotationStatus' => 'Quote Returned', 'CompanyId' => $login_architect_id])->count();
                $OrderValueCount = Quotation::where(['QuotationStatus' => 'Order Value', 'CompanyId' => $login_architect_id])->count();
                $assigned_project = '';
                break;

            case 5:
                if (Auth::user()->UserType == 5) {
                    $login_customer_id = get_customer_id(Auth::user()->id)->id;
                    $OpenCount = Quotation::where(['QuotationStatus' => 'Open', 'CompanyId' => $login_customer_id])->count();
                    $OrderedCount = Quotation::where(['QuotationStatus' => 'Ordered', 'CompanyId' => $login_customer_id])->count();
                    $QuoteReturnedCount = Quotation::where(['QuotationStatus' => 'Quote Returned', 'CompanyId' => $login_customer_id])->count();
                    $OrderValueCount = Quotation::where(['QuotationStatus' => 'Order Value', 'CompanyId' => $login_customer_id])->count();

                    $assigned_project = Project::join('quotation', 'quotation.ProjectId', 'project.id')->select('QuotationGenerationId')
                        ->where('project.MainContractorId', $login_customer_id)
                        ->where('quotation.QuotationGenerationId', null)
                        ->first();
                }

                break;

            default:

                $data = Quotation::leftjoin("project", "project.id", "quotation.ProjectId")
                    ->leftJoin('companies', 'companies.id', 'quotation.CompanyId')
                    ->select('quotation.*', 'quotation.id as QuotationId', 'companies.CompanyName', 'project.*')
                    ->where([['quotation.UserId', '=', $LoginUserId]])
                    ->orderBy('quotation.id', 'desc')
                    ->get();

                break;
        }






        return view('DoorSchedule.QuotationListDetails', ['data' => $data, 'assigned_project' => $assigned_project, 'OpenCount' => $OpenCount, 'OrderedCount' => $OrderedCount, 'QuoteReturnedCount' => $QuoteReturnedCount, 'OrderValueCount' => $OrderValueCount]);
    }

    public function quotation_request($id, $vid)
    {
        if (isset($id)) {
            if ($vid > 0) {
                $aa = Item::join('quotation_version_items', 'items.itemId', 'quotation_version_items.itemID')
                    ->join('item_master', 'quotation_version_items.itemmasterID', 'item_master.id')
                    ->where('quotation_version_items.version_id', $vid)->get();
            } else {
                $aa = Item::join('item_master', 'items.itemId', 'item_master.itemID')
                    ->where(['items.QuotationId' => $id])->orderBy('id', 'desc')->get();
            }

            $q = Quotation::select('configurableitems')->where('id', $id)->first();
            $i = 1;
            $tbl = '';
            foreach ($aa as $row) {
                $tbl .=
                    '
                <tr>
                    <td>' . $i . '</td>
                    <td><a href="' . ConfigurationURL($q->configurableitems, $row->itemId, $vid) . '">' . $row->doorNumber . '</a></td>
                    <td>' . $row->DoorType . '</td>
                    <td>' . $row->floor . '</td>
                    <td>' . $row->FireRating . '</td>
                    <td>' . $row->Leaf1VisionPanel . '</td>
                    <td></td>
                    <td>' . $row->SOWidth . '</td>
                    <td>' . $row->SOHeight . '</td>
                    <td>' . $row->DoorLeafFacing . '</td>
                    <td></td>
                </tr>';
                $i++;
            }

            return view('DoorSchedule.QuotationRequest', ['tbl' => $tbl, 'vid' => $vid]);
        } else {
            return redirect()->route('quotation/add');
        }
    }


    public function doors($id)
    {

        $schedule = DoorSchedule::where('id', $id)->first();
        $item = Item::where('DoorScheduleId', $id)->first();

        // print_r($schedule);
        // die();

        $options_data = Option::where('is_deleted', 0)->get();
        $company_data = Company::join('users', 'users.id', 'companies.UserId')->select('users.*')->get();
        return view('Items/Door', ['itemlist' => $item, 'option_data' => $options_data, 'company_list' => $company_data, 'schedule' => $schedule, 'issingleconfiguration' => '0', 'versionId' => ""]);
        // return view('DoorSchedule.QuotationRequest',compact('data'));

        // return view('DoorSchedule.DoorList');
    }


    public function addcustomer(request $request): string
    {
        $check_email = CustomerContact::where('ContactEmail', $request->ContactEmail)->count();
        if ($check_email == '0') {
            $customer = new Customer();
            $customer->CstSiteAddressLine1 = $request->CstSiteAddressLine1;
            $customer->CstSiteCountry = $request->CstSiteCountry;
            $customer->CstSiteCity = $request->CstSiteCity;
            $customer->CstSitePostalCode = $request->CstSitePostalCode;
            $customer->CstSiteState = $request->CstSiteState;
            $customer->UserId = Auth::user()->id;
            $customer->save();

            $customer_contact = new CustomerContact();
            $customer_contact->FirstName = $request->FirstName;
            $customer_contact->LastName = $request->LastName;
            $customer_contact->ContactEmail = $request->ContactEmail;
            $customer_contact->ContactPhone = $request->ContactPhone;
            $customer_contact->ContactJobTitle = $request->ContactJobTitle;
            $customer_contact->MainContractorId = $customer->id;
            $customer_contact->save();
            Session::put('quotation_customer_id', $customer->id);
            return "success";
        } else {
            return "exists";
        }
    }

    public function getcustomer(request $request)
    {
        if (Auth::user()->UserType == "2") {
            $customers = CustomerContact::join('customers', 'customers.id', 'customer_contacts.MainContractorId')->select('customers.id', 'customer_contacts.FirstName', 'customer_contacts.LastName')->where('customers.UserId', Auth::user()->id)->orderBy('customers.id', 'desc')->get();
        } else {
            $customers = CustomerContact::join('customers', 'customers.id', 'customer_contacts.MainContractorId')->select('customers.id', 'customer_contacts.FirstName', 'customer_contacts.LastName')->where('customers.UserId', Auth::user()->id)->orderBy('customers.id', 'desc')->get();
        }

        return $customers;
    }

    public function add_shipping_address(request $request): string
    {
        $data = new ShippingAddress();
        $data->Country = $request->Country;
        $data->Address = $request->ShippingAddress;
        $data->PIN = $request->PinCode;
        $data->save();
        Session::put('shipping_address_id', $data->id);
        return "success";
    }

    public function getupdateddoors($id, $vid)
    {
        $quotationID = $id;
        $addDoorType = '';
        if ($vid > 0) {
            $zz = Item::select('items.itemId', 'items.DoorType')->where('QuotationId', $quotationID)->where('VersionId', $vid)->get();
        } else {
            $zz = Item::select('items.itemId', 'items.DoorType')->where('QuotationId', $quotationID)->get();
        }

        // $zz = Item::select('items.itemId','items.DoorType')->where('QuotationId',$quotationID)->get();
        $doortype = '<option value="">Select Door Type</option>';
        foreach ($zz as $rr) {
            $select = '';
            if (old('doortypeId') == $rr->itemId) {
                $select = 'selected';
            }

            $doortype .= '<option value="' . $rr->itemId . '" ' . $select . '>' . $rr->DoorType . '</option>';
        }

        $floor = Quotation::join('project_building_details', 'quotation.ProjectId', 'project_building_details.projectId')->where('quotation.id', $quotationID)->select('project_building_details.*')->get();

        // $doortype .= $addDoorType;
        return view('DoorSchedule.AddNewDoors', ['doortype' => $doortype, 'quotationID' => $quotationID, 'vid' => $vid, 'floor' => $floor]);
    }

    public function getupdatedScreens($id, $vid)
    {
        $quotationID = $id;
        $addDoorType = '';
        if ($vid > 0) {
            $zz = SideScreenItem::select('side_screen_items.id', 'side_screen_items.ScreenType')->where('QuotationId', $quotationID)->where('VersionId', $vid)->get();
        } else {
            $zz = SideScreenItem::select('side_screen_items.id', 'side_screen_items.ScreenType')->where('QuotationId', $quotationID)->get();
        }

        $doortype = '<option value="">Select Screen Type</option>';
        foreach ($zz as $rr) {
            $doortype .= '<option value="' . $rr->id . '">' . $rr->ScreenType . '</option>';
        }

        $floor = Quotation::join('project_building_details', 'quotation.ProjectId', 'project_building_details.projectId')->where('quotation.id', $quotationID)->select('project_building_details.*')->get();

        // $doortype .= $addDoorType;
        return view('DoorSchedule.AddNewScreen', ['doortype' => $doortype, 'quotationID' => $quotationID, 'vid' => $vid, 'floor' => $floor]);
    }

    public function newdoorsstore(request $request)
    {
        $valid = $request->validate(
            [
                'doortypeId' => 'required',
                'doornumber' => 'required'
            ],
            [
                'doortypeId.required' => 'Door type field is required.',
                'doornumber.required' => 'Door number field is required.'
            ]
        );
        $QuotationId = $request->quotationID;
        $itemid = $request->doortypeId;
        $floor = $request->floor;
        $location = $request->location;
        $DoorNo = $request->doornumber;
        $versionID = $request->versionID;

        $qq = Quotation::find($QuotationId);
        $qq->editBy = Auth::user()->id;
        $qq->updated_at = date('Y-m-d H:i:s');
        $qq->save();
        // $aa = ItemMaster::where('id',$itemmasterId)->first();
        // $doorType = $aa->doorType;
        // $itemID = $aa->itemID;
        // check these `Door Type` with `Door Number` with `Quotation Id` is not duplicate entry
        // it show message if it is more than 0 (zero) - Door Type with Door Number is already exist.

        $mm = Item::join('item_master', 'items.itemId', 'item_master.itemID')->where(['items.QuotationId' => $QuotationId, 'item_master.doorNumber' => $DoorNo, 'items.VersionId' => $versionID])->count();

        // $mm = Item::join('item_master','items.itemId','item_master.itemID')->join('quotation_version_items','quotation_version_items.itemmasterID','item_master.id')->where(['items.QuotationId' => $QuotationId, 'item_master.doorNumber' => $DoorNo, 'quotation_version_items.version_id'=>$versionID])->count();
        if ($mm > 0) {
            return redirect()->back()->withInput()->with('error', 'Door Number ' . $DoorNo . ' is already exist for these quotation.');
        } else {
            $itemmaster = new ItemMaster();
            $itemmaster->itemID = $itemid;
            $itemmaster->doorNumber = $DoorNo;
            $itemmaster->floor = $floor;
            $itemmaster->location = $location;
            $itemmaster->save();

            $kl = ItemMaster::orderBy('id', 'desc')->limit(1)->first();
            $itemmasterID = $kl->id;
            if ($versionID > 0) {
                $qv = new QuotationVersionItems();
                $qv->version_id = $versionID;
                $qv->itemID = $itemid;
                $qv->itemmasterID = $itemmasterID;
                $qv->Status = 1;
                $qv->created_at = date('Y-m-d H:i:s');
                $qv->updated_at = date('Y-m-d H:i:s');
                $qv->save();
            }

            $data = Item::where(['items.QuotationId' => $QuotationId, 'items.itemId' => $itemid])->first();

            $quotation = Quotation::where('id',$QuotationId)->first();

            BOMUpdate($data, $quotation->configurableitems);

            $BOMCalculation = BOMCalculation::select('*')->where('QuotationId',$QuotationId)->where('DoorType',$data->DoorType)->where('itemId',$itemid)->get();
            $GTSellPrice = 0;
            $GTSellPriceTotal = 0;
            if(!empty($BOMCalculation)){
                foreach($BOMCalculation as $value){
                    if($value->Category != 'Ironmongery&MachiningCosts'){
                        $GTSellPrice += $value->GTSellPrice;
                    }
                }

                $ItemMaster = ItemMaster::where('itemID',$itemid)->get()->count();
                $GTSellPriceTotal = round(($GTSellPrice/$ItemMaster),2);
            }

            $Item = Item::where('itemId', $itemid)->update([
                'DoorsetPrice' => $GTSellPriceTotal
             ]);
            // BOMQuatityOfDoorUpdate($itemid, $QuotationId);
        }

        return redirect()->back()->with('success', 'Door Created Successfully!');
    }

    public function newScreenStore(request $request)
    {
        $valid = $request->validate(
            [
                'screentypeId' => 'required',
                'screennumber' => 'required'
            ],
            [
                'screentypeId.required' => 'Screen type field is required.',
                'screennumber.required' => 'Screen number field is required.'
            ]
        );
        $QuotationId = $request->quotationID;
        $ScreenId = $request->screentypeId;
        $floor = $request->floor;
        $location = $request->location;
        $screenNo = $request->screennumber;
        $versionID = $request->versionID;

        $mm = SideScreenItem::join('side_screen_item_master', 'side_screen_items.id', 'side_screen_item_master.ScreenId')->where(['side_screen_items.QuotationId' => $QuotationId, 'side_screen_item_master.screenNumber' => $screenNo, 'side_screen_items.VersionId' => $versionID])->count();

        if ($mm > 0) {
            return redirect()->back()->withInput()->with('error', 'Screen Number ' . $screenNo . ' is already exist for these quotation.');
        } else {
            $itemmaster = new SideScreenItemMaster();
            $itemmaster->ScreenId = $ScreenId;
            $itemmaster->screenNumber = $screenNo;
            $itemmaster->floor = $floor;
            $itemmaster->location = $location;
            $itemmaster->save();

            $itemList = SideScreenItem::where(['side_screen_items.QuotationId' => $QuotationId, 'side_screen_items.id' => $ScreenId, 'side_screen_items.VersionId' => $versionID])->first();
            sideScreenBOM($itemList);
        }

        return redirect()->back()->with('success', 'Screen Created Successfully!');
    }

    public function adddoor($id)
    {
        if (!empty($id)) {
            $quotation = Quotation::where('id', $id)->first();
            if (!empty($quotation)) {
                $options_data = Option::where('is_deleted', 0)->where('OptionSlug', 'fire_rating')->orwhere('OptionSlug', 'Door_Leaf_Facing')->get();
                return view('DoorSchedule/AddDoor', ['option_data' => $options_data, 'quotationId' => $id]);
            } else {
                return redirect()->route('quotation/request/' . $id);
            }
        } else {
            return redirect()->route('quotation/request/' . $id);
        }
    }


    public function excelupload($id, $vid)
    {

        if (!empty($id)) {
            $quotation = Quotation::where('id', $id)->first();
            if (!empty($quotation)) {
                $quotationId = $id;
                $vid = $vid;
                $ProjectFiles = ProjectFiles::where(['projectId' => $quotation->ProjectId, 'tag' => 'DoorSchedule'])->count();
                return view('DoorSchedule/ExcelUpload', ['quotationId' => $quotationId, 'vid' => $vid, 'ProjectFiles' => $ProjectFiles]);
            } else {
                return redirect()->route('quotation/request/' . $id);
            }
        } else {
            return redirect()->route('quotation/request/' . $id);
        }
    }

    public function adjustPriceDiscount(Request $request)
    {
        $quotationId = $request->quotationId;
        $versionId = $request->versionId;
        $QuoteSummaryDiscount = $request->QuoteSummaryDiscount;
        $UserId = Auth::user()->id;

        $discountQuotationValue = discountQuotationValue($quotationId,$versionId);
        if($QuoteSummaryDiscount == 0){
            $discountQuotationValue = 0;
        }

        $QuotationVersion = QuotationVersion::where('quotation_id', $quotationId)->where('id', $versionId)->update([
            'discountQuotation' => $QuoteSummaryDiscount + $discountQuotationValue
        ]);

        $discountQuote = discountQuote($quotationId,$versionId);

        if($discountQuote){
            $response = [
                'status'=>true,
                'msg'=> 'Price Adjusted Successfully!'
            ];
        }else{
            $response = [
                'status'=>false,
                'msg'=> 'something went wrong!'
            ];
        }

        return response()->json($response, 200,['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'], JSON_UNESCAPED_UNICODE);
    }

    public function ImportfileUpload(Request $request)
    {
        $quotationId = $request->quotationId;
        $versionId = $request->versionId;
        $UserId = Auth::user()->id;
        $q = Quotation::select('ProjectId')->where('id', $quotationId)->first();
        $pf = ProjectFiles::where(['projectId' => $q->ProjectId, 'tag' => 'DoorSchedule'])->first();
        $pfDS = ProjectFilesDS::where('projectfileId', $pf->id)->get();
        $success = null;
        foreach ($pfDS as $tt) {
            $doorNumber = $tt->doorNumber;              // Mark
            $DoorType = $tt->DoorType;                  // Type
            $FireRating = $tt->FireRating;              // FireRating
            $SOWidth = $tt->SOWidth;                    // StructuralWidth
            $SOHeight = $tt->SOHeight;                  // StructuralHeight
            $DoorLeafFacing = $tt->DoorLeafFacing;      // DoorFinish
            $Leaf1VisionPanel = $tt->Leaf1VisionPanel;  // VisionPanel
            $floor = $tt->floor;                        // MarkLevel

            $itemCount = Item::where(['QuotationId' => $quotationId, 'DoorType' => $DoorType])->count();
            if ($itemCount > 0) {
                $itemLastId = Item::where(['QuotationId' => $quotationId, 'DoorType' => $DoorType])->first();
                $itemId = $itemLastId->itemId;
                $itemMasterCount = Item::join('item_master', 'items.itemId', 'item_master.itemID')
                    ->where(['items.QuotationId' => $quotationId, 'item_master.doorNumber' => $doorNumber])->count();
                if ($itemMasterCount == 0) {
                    $dd = new ItemMaster();
                    $dd->itemID = $itemId;
                    $dd->doorNumber = $doorNumber;
                    if (isset($floor)) {
                        $dd->floor = $floor;
                    }

                    $dd->save();

                    if ($versionId > 0) {
                        $itemMasterID = ItemMaster::orderBy('id', 'DESC')->limit(1)->first();
                        $itemMasterTblID = $itemMasterID->id;

                        $QVI = new QuotationVersionItems();
                        $QVI->version_id = $versionId;
                        $QVI->itemID = $itemId;
                        $QVI->itemmasterID = $itemMasterTblID;
                        $QVI->save();
                    }
                }

                $success = 0;
            } else {
                $aa = new Item();
                $aa->QuotationId = $quotationId;
                $aa->VersionId = $quotationId;
                $aa->UserId = $UserId;
                $aa->DoorType = $DoorType;
                $aa->FireRating = $FireRating;
                $aa->SOWidth = $SOWidth;
                $aa->SOHeight = $SOHeight;
                $aa->DoorLeafFacing = $DoorLeafFacing;
                $aa->Leaf1VisionPanel = $Leaf1VisionPanel;
                $aa->save();

                $itemLastId = Item::orderBy('itemId', 'DESC')->limit(1)->first();
                $itemId = $itemLastId->itemId;
                // $itemMasterCount2 = ItemMaster::where(['itemID' => $itemId, 'doorNumber' => $doorNumber])->count();
                $itemMasterCount2 = Item::join('item_master', 'items.itemId', 'item_master.itemID')
                    ->where(['items.QuotationId' => $quotationId, 'item_master.doorNumber' => $doorNumber])->count();
                if ($itemMasterCount2 == 0) {
                    $dd = new ItemMaster();
                    $dd->itemID = $itemId;
                    $dd->doorNumber = $doorNumber;
                    if (isset($floor)) {
                        $dd->floor = $floor;
                    }

                    $dd->save();

                    if ($versionId > 0) {
                        $itemMasterID = ItemMaster::orderBy('id', 'DESC')->limit(1)->first();
                        $itemMasterTblID = $itemMasterID->id;

                        $QVI = new QuotationVersionItems();
                        $QVI->version_id = $versionId;
                        $QVI->itemID = $itemId;
                        $QVI->itemmasterID = $itemMasterTblID;
                        $QVI->save();
                    }
                }

                $success = 0;
            }
        }


        $error2 = '<p>Import data already exist!</p>';
        $error3 = '<p>Excel file is imported successfully.</p>';
        if ($success === 0) {
            return redirect()->back()->with('success', $error3);
        } else {
            return redirect()->back()->with('error', $error2);
        }
    }


    public function parseImport(Request $request)
    { // dd($request->all());
        $quotationId = $request->quotationId;
        $vid = $request->versionId;
        $csv_data = Excel::toArray(new DoorScheduleImport, request()->file('csv_file'));
        $ConfigurationType = $request->ConfigurationType;

        if ($request->ConfigurationType == 4 || $request->ConfigurationType == 5 || $request->ConfigurationType == 6) {
            return view('DoorSchedule/vicaima_import_fields', ['csv_data' => $csv_data, 'quotationId' => $quotationId, 'vid' => $vid, 'ConfigurationType' => $ConfigurationType]);
        } else {
            return view('DoorSchedule/import_fields', ['csv_data' => $csv_data, 'quotationId' => $quotationId, 'vid' => $vid, 'ConfigurationType' => $ConfigurationType]);
        }

        // $path = $request->file('csv_file')->getRealPath();
        // $data = array_map('str_getcsv', $path);
        // $csv_data = array_slice($data, 0, 2);
        // return view('DoorSchedule/import_fields', compact('csv_data','quotationId','vid'));

        // return redirect()->back()->with('csv_data',$csv_data);
        // dd(    $csv_data);
        // To be continued...
    }

    public function import_process(Request $request)
    {
        ini_set('max_input_vars', 100000);
        $quotationId = $request->quotationId;
        $versionId = $request->versionId;
        if (!$request->DoorType || !$request->FireRating || !$request->DoorNumber) {
            $error = '<p style="color:red">Selection of doornumber, doortype and firerating is must.</p>';
            return redirect()->route('quotation/excel-upload', [$quotationId, $versionId])->with('success2', $error);
        }

        $UserId = Auth::user()->id;
        $countFR = null;
        $success = null;
        $countDFR = null;

        $DoorNumber = $request->DoorNumber;
        $count = count($request->DoorNumber);

        if($request->pageType == 4 || $request->pageType == 5 || $request->pageType == 6){

            $i = 0;
            $quotation = Quotation::where('id', $quotationId)->first();

            if (!empty($quotation)) {
                $quotation->configurableitems = $request->pageType;
                $quotation->save();
                while ($count > $i) {
                    $doorNumber       = $DoorNumber[$i];    // Mark
                    if ($request->DoorType) {
                        $DoorType         = $request->DoorType[$i];      // Type
                    }

                    if ($request->FireRating) {
                        if ($request->FireRating[$i] == 'FD30s' || $request->FireRating[$i] == 'FD30S') {
                            $FireRating = "FD30s";
                        } elseif ($request->FireRating[$i] == 'FD60s' || $request->FireRating[$i] == 'FD60S') {
                            $FireRating = "FD60s";
                        } else {
                            $FireRating = $request->FireRating[$i];          // FireRating
                        }
                    }

                    if ($request->SOWidth) {
                        $SOWidth          = $request->SOWidth[$i];             // StructuralWidth
                    }

                    if ($request->SOHeight) {
                        $SOHeight         = $request->SOHeight[$i];            // StructuralHeight
                    }

                    if ($request->DoorLeafFacing) {
                        $DoorLeafFacing   = $request->DoorLeafFacing[$i];      // DoorFinish
                    }

                    if ($request->Leaf1VisionPanel) {
                        $Leaf1VisionPanel = $request->Leaf1VisionPanel[$i];    // VisionPanel
                    }

                    if ($request->Floor) {
                        $floor            = $request->Floor[$i];              // MarkLevel
                    }

                    if ($request->doorsetType) {
                        $doorsetType = $request->doorsetType[$i];              // MarkLevel
                    }

                    if ($request->sODepth) {
                        $sODepth = $request->sODepth[$i];              // MarkLevel
                    }

                    if ($request->vP1Width) {
                        $vP1Width = $request->vP1Width[$i];              // MarkLevel
                    }

                    if ($request->vP1Height1) {
                        $vP1Height1 = $request->vP1Height1[$i];              // MarkLevel
                    }

                    if ($request->Architrave) {
                        $Architrave = $request->Architrave[$i];              // MarkLevel
                    }

                    if ($request->architraveWidth) {
                        $architraveWidth = $request->architraveWidth[$i];              // MarkLevel
                    }

                    if ($request->architraveThickness) {
                        $architraveThickness = $request->architraveThickness[$i];              // MarkLevel
                    }

                    if ($request->Location) {
                        $Location = $request->Location[$i];              // MarkLevel
                    }

                    if ($request->DoorDescription) {
                        $DoorDescription = $request->DoorDescription[$i];              // MarkLevel
                    }

                    if ($request->LeafWidth1) {
                        $LeafWidth1 = $request->LeafWidth1[$i];              // MarkLevel
                    }

                    if ($request->LeafWidth2) {
                        $LeafWidth2 = $request->LeafWidth2[$i];              // MarkLevel
                    }

                    if ($request->LeafHeight) {
                        $LeafHeight = $request->LeafHeight[$i];              // MarkLevel
                    }

                    if ($request->sODepth) {
                        $sODepth = $request->sODepth[$i];              // MarkLevel
                    }

                    if ($request->LeafType) {
                        $LeafType = $request->LeafType[$i];              // MarkLevel
                    }

                    if ($request->Handing) {
                        $Handing = $request->Handing[$i];              // MarkLevel
                    }

                    if ($request->OpensInwards) {
                        $OpensInwards = $request->OpensInwards[$i];              // MarkLevel
                    }

                    if ($request->VpSize1) {
                        $VpSize1 = $request->VpSize1[$i];              // MarkLevel
                    }

                    if ($request->VpSize2) {
                        $VpSize2 = $request->VpSize2[$i];              // MarkLevel
                    }

                    if ($request->GlassType) {
                        $GlassType = $request->GlassType[$i];              // MarkLevel
                    }

                    if ($request->FrameMaterials) {
                        $FrameMaterials = $request->FrameMaterials[$i];              // MarkLevel
                    }

                    if ($request->FrameFinish) {
                        $FrameFinish = $request->FrameFinish[$i];              // MarkLevel
                    }

                    if ($request->ArchitraveMaterial) {
                        $ArchitraveMaterial = $request->ArchitraveMaterial[$i];              // MarkLevel
                    }

                    if ($request->ArchitraveSetQty) {
                        $ArchitraveSetQty = $request->ArchitraveSetQty[$i];              // MarkLevel
                    }

                    if ($request->IronSet) {
                        $IronSet = $request->IronSet[$i];              // MarkLevel
                    }

                    if ($request->rWDBRating) {
                        $rWDBRating = $request->rWDBRating[$i];              // MarkLevel
                    }

                    // if($request->FireRating){
                    // $Checkingfirerating = Option::where(['OptionSlug' => 'fire_rating' , 'OptionKey' => $FireRating ])->count();
                    // if($Checkingfirerating == 0){
                    //     $countFR = 0;
                    // }
                    // }
                    // else{
                    //     $Checkingfirerating = 0;
                    //     $countFR = 0;
                    // }


                    // if($request->Leaf1VisionPanel){
                    // $CheckingDLF = Option::where(['OptionSlug' => 'leaf1_vision_panel' , 'OptionKey' => $DoorLeafFacing ])->count();
                    // if($CheckingDLF == 0){
                    //     $countDFR = 0;
                    // }
                    // }
                    // else{
                    //     $countDFR = 0;
                    //     $CheckingDLF = 0;
                    // }

                    $Checkingfirerating = Option::where(['OptionSlug' => 'fire_rating', 'OptionKey' => $FireRating])->count();
                    if ($Checkingfirerating == 0) {
                        $countFR = 0;
                    }


                    // $CheckingDLF = Option::where(['OptionSlug' => 'Door_Leaf_Facing' , 'OptionKey' => $DoorLeafFacing ])->count();
                    // if($CheckingDLF == 0){
                    //     $countDFR = 0;
                    // }



                    // if($Checkingfirerating > 0 && $CheckingDLF > 0){

                    if ($Checkingfirerating > 0) {
                        $itemCount = Item::where(['QuotationId' => $quotationId, 'DoorType' => $DoorType])->count();

                        if ($itemCount > 0) {

                            $itemLastId = Item::where(['QuotationId' => $quotationId, 'DoorType' => $DoorType])->first();
                            $itemId = $itemLastId->itemId;
                            $itemMasterCount = Item::join('item_master', 'items.itemId', 'item_master.itemID')
                                ->where(['items.QuotationId' => $quotationId, 'items.DoorType' => $DoorType, 'item_master.doorNumber' => $doorNumber])->count();

                            if ($itemMasterCount == 0) {
                                $dd = new ItemMaster();
                                $dd->itemID = $itemId;
                                $dd->doorNumber = $doorNumber;
                                if (isset($floor)) {

                                    $dd->floor = $floor;
                                }

                                $dd->save();

                                if ($versionId > 0) {
                                    $itemMasterID = ItemMaster::orderBy('id', 'DESC')->limit(1)->first();
                                    $itemMasterTblID = $itemMasterID->id;

                                    $QVI = new QuotationVersionItems();
                                    $QVI->version_id = $versionId;
                                    $QVI->itemID = $itemId;
                                    $QVI->itemmasterID = $itemMasterTblID;
                                    $QVI->save();
                                }
                            }

                            $success = 0;
                        } else {


                            $aa = new Item();
                            $aa->QuotationId = $quotationId;
                            $aa->VersionId = $versionId;
                            $aa->UserId = $UserId;
                            $aa->DoorType = $DoorType;
                            $aa->FireRating = $FireRating;
                            if ($request->SOWidth) {
                                $aa->SOWidth = $SOWidth;
                            }

                            if ($request->SOHeight) {
                                $aa->SOHeight = $SOHeight;
                            }

                            // $aa->DoorLeafFacing = $DoorLeafFacing;
                            if ($request->Leaf1VisionPanel) {
                                $aa->Leaf1VisionPanel = $Leaf1VisionPanel;
                            }

                            if ($request->doorsetType) {
                                $aa->DoorsetType = $doorsetType;              // MarkLevel
                            }

                            if ($request->sODepth) {
                                $aa->SOWallThick = $sODepth;              // MarkLevel
                            }

                            if ($request->vP1Width) {
                                $aa->Leaf1VPWidth = $vP1Width;              // MarkLevel
                            }

                            if ($request->vP1Height1) {
                                $aa->Leaf1VPHeight1 = $vP1Height1;              // MarkLevel
                            }

                            if ($request->Architrave) {
                                $aa->Architrave = $Architrave;              // MarkLevel
                            }

                            if ($request->architraveWidth) {
                                $aa->ArchitraveWidth = $architraveWidth;              // MarkLevel
                            }

                            if ($request->architraveThickness) {
                                $aa->ArchitraveHeight = $architraveThickness;              // MarkLevel
                            }

                            if ($request->Location) {
                                // $aa->Location = $Location;
                            }

                            if ($request->DoorDescription) {
                                // $aa->DoorDescription = $DoorDescription;               // MarkLevel
                            }

                            if ($request->LeafWidth1) {

                                $aa->LeafWidth1 = $LeafWidth1;              // MarkLevel
                            }

                            if ($request->LeafWidth2) {

                                $aa->LeafWidth2 = $LeafWidth2;              // MarkLevel
                            }

                            $aa->SOWidth = $request->LeafWidth2 && $request->LeafWidth1 ? $LeafWidth2 + $LeafWidth1 : $LeafWidth1 ?? "";
                            if (isset($LeafWidth1) && isset($LeafWidth2)) {
                                $aa->DoorsetType = $LeafWidth1 != $LeafWidth2 ? 'leaf_and_a_half' : 'DD';
                            } else {
                                $aa->DoorsetType = 'SD';
                            }

                            if ($request->LeafHeight) {

                                $aa->LeafHeight = $LeafHeight;               // MarkLevel
                                $aa->SOHeight = $LeafHeight;               // MarkLevel
                            }

                            // if($request->sODepth){

                            //     $aa->sODepth = $sODepth;             // MarkLevel
                            // }

                            if ($request->LeafType) {
                                $aa->LeafConstruction = $LeafType;               // MarkLevel
                            }

                            if ($request->Handing) {

                                $aa->Handing = $Handing;              // MarkLevel
                            }

                            if ($request->OpensInwards) {

                                $aa->OpensInwards = $OpensInwards;               // MarkLevel
                            }

                            if ($request->VpSize1) {

                                $aa->Leaf1VisionPanel = $VpSize1;             // MarkLevel
                            }

                            if ($request->VpSize2) {

                                $aa->Leaf2VisionPanel = $VpSize2;             // MarkLevel
                            }

                            if ($request->GlassType) {

                                $aa->GlassType = $GlassType;             // MarkLevel
                            }

                            if ($request->FrameMaterials) {

                                $aa->FrameMaterial = lippingSpeciesId($FrameMaterials);             // MarkLevel
                            }

                            if ($request->FrameFinish) {

                                $aa->FrameFinish = $FrameFinish;              // MarkLevel
                            }

                            if ($request->ArchitraveMaterial) {

                                $ArchitraveMaterials = DB::table('lipping_species')->where('SpeciesName', $ArchitraveMaterial)->first();
                                if ($ArchitraveMaterials) {

                                    $aa->ArchitraveMaterial = $ArchitraveMaterials->id;             // MarkLevel
                                }
                            }

                            if ($request->ArchitraveSetQty) {

                                $aa->ArchitraveSetQty = $ArchitraveSetQty;               // MarkLevel
                            }

                            if ($request->IronSet) {

                                $aa->IronmongerySet = $IronSet;              // MarkLevel
                            }

                            if ($request->rWDBRating) {

                                $aa->rWDBRating = $rWDBRating;             // MarkLevel
                            }

                            if ($request->DoorLeafFacing) {

                                $aa->DoorLeafFacing = $DoorLeafFacing;     // DoorFinish
                                }

                            if ($request->FrameOnOff) {
                                $FrameOnOff = ($request->FrameOnOff[$i] == 'Yes') ? 1 : 0;
                            }

                            $aa->FrameOnOff = $FrameOnOff ?? 0;

                            if ($request->LeafHeight && $request->FireRating && $request->LeafWidth1 && $request->pageType &&  $request->DoorLeafFacing  && $request->LeafType) {

                                if ($request->LeafWidth1 && $request->LeafWidth2 && ($LeafWidth1 != $LeafWidth2)) {
                                    // leaf and half
                                    if ($FireRating == 'FD30' || $FireRating == 'FD60') {

                                        $doordimensionCount =   DoorDimension::where([
                                            'configurableitems' => $request->pageType,
                                            'fire_rating' => $FireRating,
                                            'mm_width' => $LeafWidth1,
                                            'mm_height' => $LeafHeight,
                                            'door_leaf_facing' => $DoorLeafFacing,
                                            'leaf_type' => $LeafType
                                        ])->get();

                                        $doordimensionCount2 =   DoorDimension::where([
                                            'configurableitems' => $request->pageType,
                                            'fire_rating' => $FireRating,
                                            'mm_width' => $LeafWidth2,
                                            'mm_height' => $LeafHeight,
                                            'door_leaf_facing' => $DoorLeafFacing,
                                            'leaf_type' => $LeafType
                                        ])->get();
                                    } else {

                                        $doordimensionCount =   DoorDimension::where([
                                            'mm_width' => $LeafWidth1,
                                            'mm_height' => $LeafHeight,
                                            'door_leaf_facing' => $DoorLeafFacing,
                                            'leaf_type' => $LeafType
                                        ])->get();

                                        $doordimensionCount2 =   DoorDimension::where([
                                            'mm_width' => $LeafWidth2,
                                            'mm_height' => $LeafHeight,
                                            'door_leaf_facing' => $DoorLeafFacing,
                                            'leaf_type' => $LeafType
                                        ])->get();

                                        // if NFR case, 1 more than data so FD30 data allow
                                        if (count($doordimensionCount) > 1) {

                                            $doordimensionCount =   DoorDimension::where([
                                                'configurableitems' => $request->pageType,
                                                'fire_rating' => 'FD30',
                                                'mm_width' => $LeafWidth1,
                                                'mm_height' => $LeafHeight,
                                                'door_leaf_facing' => $DoorLeafFacing,
                                                'leaf_type' => $LeafType
                                            ])->get();
                                        }

                                        // if NFR case 1 more than data so FD30 data allow
                                        if (count($doordimensionCount2) > 1) {

                                            $doordimensionCount2 =   DoorDimension::where([
                                                'configurableitems' => $request->pageType,
                                                'fire_rating' => 'FD30',
                                                'mm_width' => $LeafWidth2,
                                                'mm_height' => $LeafHeight,
                                                'door_leaf_facing' => $DoorLeafFacing,
                                                'leaf_type' => $LeafType
                                            ])->get();
                                        }
                                    }
                                } elseif ($FireRating == 'FD30' || $FireRating == 'FD60') {
                                    // single door and double door
                                    $doordimensionCount =   DoorDimension::where([
                                        'configurableitems' => $request->pageType,
                                        'fire_rating' => $FireRating,
                                        'mm_width' => $LeafWidth1,
                                        'mm_height' => $LeafHeight,
                                        'door_leaf_facing' => $DoorLeafFacing,
                                        'leaf_type' => $LeafType
                                    ])->get();
                                } else {


                                    $doordimensionCount =   DoorDimension::where([
                                        'mm_width' => $LeafWidth1,
                                        'mm_height' => $LeafHeight,
                                        'door_leaf_facing' => $DoorLeafFacing,
                                        'leaf_type' => $LeafType
                                    ])->get();


                                    // if NFR case 1 more than data so FD30 data allow
                                    if (count($doordimensionCount) > 1) {

                                        $doordimensionCount =   DoorDimension::where([
                                            'configurableitems' => $request->pageType,
                                            'fire_rating' => 'FD30',
                                            'mm_width' => $LeafWidth1,
                                            'mm_height' => $LeafHeight,
                                            'door_leaf_facing' => $DoorLeafFacing,
                                            'leaf_type' => $LeafType
                                        ])->get();
                                    }
                                }




                                if (count($doordimensionCount) == 1) {

                                    $aa->DoorDimensionsCode = $doordimensionCount[0]->code ?? "";
                                    $aa->DoorDimensions = $doordimensionCount[0]->id ?? "";
                                }

                                if (isset($doordimensionCount2) && count($doordimensionCount2) == 1) {
                                    $aa->DoorDimensionsCode2 = $doordimensionCount2[0]->code ?? "";
                                    $aa->DoorDimensions2 = $doordimensionCount2[0]->id ?? "";
                                }
                            }

                            // dd($aa);

                            $aa->save();

                            $itemLastId = Item::orderBy('itemId', 'DESC')->limit(1)->first();
                            $itemId = $itemLastId->itemId;
                            // $itemMasterCount2 = ItemMaster::where(['itemID' => $itemId, 'doorNumber' => $doorNumber])->count();
                            $itemMasterCount2 = Item::join('item_master', 'items.itemId', 'item_master.itemID')
                                ->where(['items.QuotationId' => $quotationId, 'items.DoorType' => $DoorType, 'item_master.doorNumber' => $doorNumber])->count();

                            if ($itemMasterCount2 == 0) {
                                $dd = new ItemMaster();
                                $dd->itemID = $itemId;
                                $dd->doorNumber = $doorNumber;
                                if (isset($floor)) {

                                    $dd->floor = $floor;
                                }

                                $dd->save();

                                if ($versionId > 0) {
                                    $itemMasterID = ItemMaster::orderBy('id', 'DESC')->limit(1)->first();
                                    $itemMasterTblID = $itemMasterID->id;

                                    $QVI = new QuotationVersionItems();
                                    $QVI->version_id = $versionId;
                                    $QVI->itemID = $itemId;
                                    $QVI->itemmasterID = $itemMasterTblID;
                                    $QVI->save();
                                }
                            }

                            $success = 0;
                        }
                    }


                    $i++;
                }
            }

            $error = null;
            $error2 = null;
            $error3 = null;
            if ($request->FireRating && $countFR === 0) {
                $error = '<p style="color:red">Some Fire Rating is not in correct format.</p>';
            }

            if ($request->Leaf1VisionPanel && $countDFR === 0) {
                $error2 = '<p style="color:red">Some VisionPanel is not in correct formate.</p>';
            }

            if ($success === 0) {
                $error3 = '<p>Excel file is imported successfully.</p>';
            }

            return redirect()->route('quotation/excel-upload', [$quotationId, $versionId])->with('success2', $error3 . $error . $error2);
        } else {
            $i = 0;
            $quotation = Quotation::where('id', $quotationId)->first();
            if (!empty($quotation)) {
                $quotation->configurableitems = $request->pageType;
                $quotation->save();

                while ($count > $i) {
                    $doorNumber       = $DoorNumber[$i];    // Mark
                    if ($request->DoorType) {
                        $DoorType         = $request->DoorType[$i];      // Type
                    }

                    if ($request->FireRating) {
                        if ($request->FireRating[$i] == 'FD30s' || $request->FireRating[$i] == 'FD30S') {
                            $FireRating = "FD30s";
                        } elseif ($request->FireRating[$i] == 'FD60s' || $request->FireRating[$i] == 'FD60S') {
                            $FireRating = "FD60s";
                        } else {
                            $FireRating = $request->FireRating[$i];          // FireRating
                        }
                    }

                    if ($request->SOWidth) {
                        $SOWidth          = $request->SOWidth[$i];             // StructuralWidth
                    }

                    if ($request->SOHeight) {
                        $SOHeight         = $request->SOHeight[$i];            // StructuralHeight
                    }

                    if ($request->DoorLeafFacing) {
                        $DoorLeafFacing   = $request->DoorLeafFacing[$i];      // DoorFinish
                    }

                    if ($request->Leaf1VisionPanel) {
                        $Leaf1VisionPanel = $request->Leaf1VisionPanel[$i];    // VisionPanel
                    }

                    if ($request->Floor) {
                        $floor            = $request->Floor[$i];              // MarkLevel
                    }

                    if ($request->doorsetType) {
                        $doorsetType = $request->doorsetType[$i];              // MarkLevel
                    }

                    if ($request->sODepth) {
                        $sODepth = $request->sODepth[$i];              // MarkLevel
                    }

                    if ($request->vP1Width) {
                        $vP1Width = $request->vP1Width[$i];              // MarkLevel
                    }

                    if ($request->vP1Height1) {
                        $vP1Height1 = $request->vP1Height1[$i];              // MarkLevel
                    }

                    if ($request->Architrave) {
                        $Architrave = $request->Architrave[$i];              // MarkLevel
                    }

                    if ($request->architraveWidth) {
                        $architraveWidth = $request->architraveWidth[$i];              // MarkLevel
                    }

                    if ($request->architraveThickness) {
                        $architraveThickness = $request->architraveThickness[$i];              // MarkLevel
                    }

                    $Checkingfirerating = Option::where(['OptionSlug' => 'fire_rating', 'OptionKey' => $FireRating])->count();
                    if ($Checkingfirerating == 0) {
                        $countFR = 0;
                    }

                    if ($Checkingfirerating > 0) {
                        $itemCount = Item::where(['QuotationId' => $quotationId, 'DoorType' => $DoorType])->count();

                        if ($itemCount > 0) {

                            $itemLastId = Item::where(['QuotationId' => $quotationId, 'DoorType' => $DoorType])->first();
                            $itemId = $itemLastId->itemId;
                            $itemMasterCount = Item::join('item_master', 'items.itemId', 'item_master.itemID')
                                ->where(['items.QuotationId' => $quotationId, 'items.DoorType' => $DoorType, 'item_master.doorNumber' => $doorNumber])->count();

                            if ($itemMasterCount == 0) {
                                $dd = new ItemMaster();
                                $dd->itemID = $itemId;
                                $dd->doorNumber = $doorNumber;
                                if (isset($floor)) {
                                    $dd->floor = $floor;
                                }

                                $dd->save();

                                if ($versionId > 0) {
                                    $itemMasterID = ItemMaster::orderBy('id', 'DESC')->limit(1)->first();
                                    $itemMasterTblID = $itemMasterID->id;

                                    $QVI = new QuotationVersionItems();
                                    $QVI->version_id = $versionId;
                                    $QVI->itemID = $itemId;
                                    $QVI->itemmasterID = $itemMasterTblID;
                                    $QVI->save();
                                }
                            }

                            $success = 0;
                        } else {


                            $aa = new Item();
                            $aa->QuotationId = $quotationId;
                            $aa->VersionId = $versionId;
                            $aa->UserId = $UserId;
                            $aa->DoorType = $DoorType;
                            $aa->FireRating = $FireRating;

                            if ($request->LeafWidth1) {
                                $aa->LeafWidth1 = $request->LeafWidth1[$i];              // MarkLevel
                            }

                            if ($request->LeafWidth2) {
                                $aa->LeafWidth2 = $request->LeafWidth2[$i];              // MarkLevel
                            }

                            if ($request->LeafHeight) {
                                $aa->LeafHeight = $request->LeafHeight[$i];              // MarkLevel
                            }

                            // $aa->DoorLeafFacing = $DoorLeafFacing;
                            if ($request->Leaf1VisionPanel) {
                                $aa->Leaf1VisionPanel = $Leaf1VisionPanel;
                            }

                            if ($request->doorsetType) {
                                $aa->DoorsetType = $doorsetType;              // MarkLevel
                            }

                            if ($request->sODepth) {
                                $aa->SOWallThick = $sODepth;              // MarkLevel
                            }

                            if ($request->vP1Width) {
                                $aa->Leaf1VPWidth = $vP1Width;              // MarkLevel
                            }

                            if ($request->vP1Height1) {
                                $aa->Leaf1VPHeight1 = $vP1Height1;              // MarkLevel
                            }

                            if ($request->Architrave) {
                                $aa->Architrave = $Architrave;              // MarkLevel
                            }

                            if ($request->architraveWidth) {
                                $aa->ArchitraveWidth = $architraveWidth;              // MarkLevel
                            }

                            if ($request->architraveThickness) {
                                $aa->ArchitraveHeight = $architraveThickness;              // MarkLevel
                            }

                            if ($request->SwingType) {
                                $aa->SwingType = $request->SwingType[$i];              // SwingType
                            }

                            if ($request->Handing) {
                                $aa->Handing = $request->Handing[$i];              // Handing
                            }

                            if ($request->LatchType) {
                                $aa->LatchType = $request->LatchType[$i];              // LatchType
                            }

                            if($request->Tollerance){
                                $aa->Tollerance = $request->Tollerance[$i];              // Tollerance
                            }

                            if($request->GAP){
                                $aa->GAP = $request->GAP[$i];              // GAP
                            }

                            if($request->FrameMaterial){
                                $aa->FrameMaterial = GetlippingSpeciesName($request->FrameMaterial[$i]);              // FrameMaterial
                            }

                            if($request->FrameType){
                                $aa->FrameType = $request->FrameType[$i];              // FrameType
                            }

                            if($request->PlantonStopWidth){
                                $aa->PlantonStopWidth = $request->PlantonStopWidth[$i];              // PlantonStopWidth
                            }

                            if($request->PlantonStopHeight){
                                $aa->PlantonStopHeight = $request->PlantonStopHeight[$i];              // PlantonStopHeight
                            }

                            if($request->RebatedWidth){
                                $aa->RebatedWidth = $request->RebatedWidth[$i];              // RebatedWidth
                            }

                            if($request->RebatedHeight){
                                $aa->RebatedHeight = $request->RebatedHeight[$i];              // RebatedHeight
                            }

                            if($request->FrameDepth){
                                $aa->FrameDepth = $request->FrameDepth[$i];              // FrameDepth
                            }

                            if($request->LippingType){
                                $aa->LippingType = $request->LippingType[$i];              // LippingType
                            }

                            if ($request->LippingThickness) {
                                $aa->LippingThickness = $request->LippingThickness[$i];              // LippingThickness
                            }

                            if ($request->LippingSpecies) {
                                $aa->LippingSpecies = $request->LippingSpecies[$i];              // LippingSpecies
                            }

                            if ($request->Accoustics) {
                                $aa->Accoustics = $request->Accoustics[$i];              // rWdBRating
                            }

                            if ($request->rWdBRating) {
                                $aa->rWdBRating = $request->rWdBRating[$i];              // rWdBRating
                            }

                            if ($request->SOWidth) {
                                $aa->SOWidth = $SOWidth;
                            }

                            if ($request->SOHeight) {
                                $aa->SOHeight = $SOHeight;
                            }

                            if ($request->Tollerance) {
                                $aa->Tollerance = $request->Tollerance[$i];              // Tollerance
                            }

                            if ($request->GAP) {
                                $aa->GAP = $request->GAP[$i];              // GAP
                            }

                            if ($request->FrameMaterial) {
                                $aa->FrameMaterial = GetlippingSpeciesName($request->FrameMaterial[$i]);              // FrameMaterial
                            }

                            if ($request->FrameType) {
                                $aa->FrameType = $request->FrameType[$i];              // FrameType
                            }

                            if ($request->PlantonStopWidth) {
                                $aa->PlantonStopWidth = $request->PlantonStopWidth[$i];              // PlantonStopWidth
                            }

                            if ($request->PlantonStopHeight) {
                                $aa->PlantonStopHeight = $request->PlantonStopHeight[$i];              // PlantonStopHeight
                            }

                            if ($request->RebatedWidth) {
                                $aa->RebatedWidth = $request->RebatedWidth[$i];              // RebatedWidth
                            }

                            if ($request->RebatedHeight) {
                                $aa->RebatedHeight = $request->RebatedHeight[$i];              // RebatedHeight
                            }

                            if ($request->FrameDepth) {
                                $aa->FrameDepth = $request->FrameDepth[$i];              // FrameDepth
                            }

                            if ($request->ArchitraveMaterial) {
                                $aa->ArchitraveMaterial = GetlippingSpeciesName($request->ArchitraveMaterial[$i]);              // ArchitraveMaterial
                            }

                            if ($request->ArchitraveType) {
                                $aa->ArchitraveType = $request->ArchitraveType[$i];              // ArchitraveType
                            }

                            if ($request->ArchitraveFinish) {
                                $aa->ArchitraveFinish = $request->ArchitraveFinish[$i];              // ArchitraveFinish
                            }

                            if ($request->ArchitraveFinishColor) {
                                $aa->ArchitraveFinishColor = $request->ArchitraveFinishColor[$i];              // ArchitraveFinishColor
                            }

                            if ($request->ArchitraveSetQty) {
                                $aa->ArchitraveSetQty = $request->ArchitraveSetQty[$i];              // ArchitraveSetQty
                            }

                            if ($request->FrameThickness) {
                                $aa->FrameThickness = $request->FrameThickness[$i];              // FrameThickness
                            }

                            $aa->FrameOnOff = $FrameOnOff ?? 0;
                            $aa->save();

                            $itemLastId = Item::orderBy('itemId', 'DESC')->limit(1)->first();
                            $itemId = $itemLastId->itemId;
                            // $itemMasterCount2 = ItemMaster::where(['itemID' => $itemId, 'doorNumber' => $doorNumber])->count();
                            $itemMasterCount2 = Item::join('item_master', 'items.itemId', 'item_master.itemID')
                                ->where(['items.QuotationId' => $quotationId, 'items.DoorType' => $DoorType, 'item_master.doorNumber' => $doorNumber])->count();

                            if ($itemMasterCount2 == 0) {
                                $dd = new ItemMaster();
                                $dd->itemID = $itemId;
                                $dd->doorNumber = $doorNumber;
                                if (isset($floor)) {
                                    $dd->floor = $floor;
                                }

                                $dd->save();

                                if ($versionId > 0) {
                                    $itemMasterID = ItemMaster::orderBy('id', 'DESC')->limit(1)->first();
                                    $itemMasterTblID = $itemMasterID->id;

                                    $QVI = new QuotationVersionItems();
                                    $QVI->version_id = $versionId;
                                    $QVI->itemID = $itemId;
                                    $QVI->itemmasterID = $itemMasterTblID;
                                    $QVI->save();
                                }
                            }

                            $success = 0;
                        }
                    }


                    $i++;
                }
            }

            $error = null;
            $error2 = null;
            $error3 = null;
            if ($request->FireRating && $countFR === 0) {
                $error = '<p style="color:red">Some Fire Rating is not in correct format</p>';
            }

            if ($request->Leaf1VisionPanel && $countDFR === 0) {
                $error2 = '<p style="color:red">Some VisionPanel is not in correct formate.</p>';
            }

            if ($success === 0) {
                $error3 = '<p>Excel file is imported successfully.</p>';
            }

            return redirect()->route('quotation/excel-upload', [$quotationId, $versionId])->with('success2', $error3 . $error . $error2);
        }
    }



    public function storedoor(request $request)
    {

        $quotation = Quotation::where('id', $request->quotationId)->first();
        if (!empty($quotation)) {

            $data = new DoorSchedule();
            $data->Mark = $request->doorNumber;
            $data->Type = $request->doorType;
            $data->MarkLevel = $request->floor;
            $data->FireRating = $request->fireRating;
            $data->VisionPanel = $request->visionPanel;
            $data->InternalorExternal = $request->internalOrExternal;
            $data->StructuralWidth = $request->soWidth;
            $data->StructuralHeight = $request->soHeight;
            $data->DoorFinish = $request->doorFacing;
            $data->NBS = $request->nsb;
            $data->Status = '0';
            $data->QuotationId = $request->quotationId;
            $data->save();
            return redirect()->back()->with('msg', 'Door Created Succesfully!');
        } else {
            //        return redirect()->route('quotation/request/'.$id);
        }

        return null;
    }



    public function storexcel(request $request)
    {
        $quotationId = $request->quotationId;
        $versionId = $request->versionId;
        $UserId = Auth::user()->id;
        $countFR = null;
        $success = null;
        $countDFR = null;

        $quotation = Quotation::where('id', $quotationId)->first();
        if (!empty($quotation)) {
            $data = Excel::toArray(new DoorScheduleImport, request()->file('ExcelFile'));

            $configurableitems = '';
            if ($data[0][1][1] == 'Streboard') {
                $configurableitems = 1;
            } elseif ($data[0][1][1] == 'Halspan') {
                $configurableitems = 2;
            } elseif ($data[0][1][1] == 'Norma') {
                $configurableitems = 3;
            } elseif ($data[0][1][1] == 'Vicaima') {
                $configurableitems = 4;
            }elseif($data[0][1][1] == 'Seadec'){
                $configurableitems = 5;
            }elseif($data[0][1][1] == 'Deanta'){
                $configurableitems = 6;
            }elseif($data[0][1][1] == 'Flamebreak'){
                $configurableitems = 7;
            }elseif($data[0][1][1] == 'Stredor'){
                $configurableitems = 8;
            }

            if($quotation->configurableitems != $configurableitems && $quotation->configurableitems != null){
                return redirect()->back()->with('error', 'Quotation is not linked with '.$data[0][1][1].' door!');
            }

            if ($configurableitems == 1 || $configurableitems == 2 || $configurableitems == 3 || $configurableitems == 7 || $configurableitems == 8) {
                $quotation->configurableitems = $configurableitems;
                $quotation->save();
                $i = 0;
                foreach ($data[0] as $row) {

                    if (isset($row[6]) && ($i == 0 || trim($row[6]) === '')) {
                        $i++;
                        continue;
                    }

                    // dd($row);
                    $j = 2;
                    $IntumescentLeafType = trim((string) $row[$j++]);
                    $FrameOnOff = trim((string) $row[$j++]);
                    $floor = trim((string) $row[$j++]);
                    $doorNumber = trim((string) $row[$j++]);
                    $location = trim((string) $row[$j++]);
                    $DoorQuantity = trim((string) $row[$j++]);
                    $FourSidedFrame = trim((string) $row[$j++]);
                    $DoorType = trim((string) $row[$j++]);
                    $FireRating = trim((string) $row[$j++]);
                    $DoorsetType = trim((string) $row[$j++]);
                    $SwingType = trim((string) $row[$j++]);
                    $LatchType = trim((string) $row[$j++]);
                    $Handing = trim((string) $row[$j++]);
                    $OpensInwards = trim((string) $row[$j++]);
                    $COC = trim((string) $row[$j++]);
                    $Tollerance = trim((string) $row[$j++]);
                    $Dropseal = trim((string) $row[$j++]);
                    $Undercut = trim((string) $row[$j++]);
                    $FloorFinish = trim((string) $row[$j++]);
                    $GAP = trim((string) $row[$j++]);
                    $FrameThickness = trim((string) $row[$j++]);
                    $IronmongerySet = trim((string) $row[$j++]);
                    $IronmongeryID = trim((string) $row[$j++]);
                    $SOHeight = trim((string) $row[$j++]);
                    $SOWidth = trim((string) $row[$j++]);
                    $SOWallThick = trim((string) $row[$j++]);
                    $LeafWidth1 = trim((string) $row[$j++]);
                    $LeafWidth2 = trim((string) $row[$j++]);
                    $LeafHeight = trim((string) $row[$j++]);
                    $LeafThickness = trim((string) $row[$j++]);
                    $DoorLeafFacing = trim((string) $row[$j++]);
                    $DoorLeafFacingValue = trim((string) $row[$j++]);
                    $DoorLeafFinish = trim((string) $row[$j++]);
                    $DoorLeafFinishColor = trim((string) $row[$j++]);
                    $SheenLevel = trim((string) $row[$j++]);
                    $DecorativeGroves = trim((string) $row[$j++]);
                    $GrooveLocation = trim((string) $row[$j++]);
                    $GrooveWidth = trim((string) $row[$j++]);
                    $GrooveDepth = trim((string) $row[$j++]);
                    $MaxNumberOfGroove = trim((string) $row[$j++]);
                    $NumberOfGroove = trim((string) $row[$j++]);
                    $NumberOfVerticalGroove = trim((string) $row[$j++]);
                    $NumberOfHorizontalGroove = trim((string) $row[$j++]);
                    $DecorativeGrovesLeaf2 = trim((string) $row[$j++]);
                    $GrooveLocationLeaf2 = trim((string) $row[$j++]);
                    $IsSameAsDecorativeGroves1 = trim((string) $row[$j++]);
                    $GrooveWidthLeaf2 = trim((string) $row[$j++]);
                    $GrooveDepthLeaf2 = trim((string) $row[$j++]);
                    $MaxNumberOfGrooveLeaf2 = trim((string) $row[$j++]);
                    $NumberOfGrooveLeaf2 = trim((string) $row[$j++]);
                    $NumberOfVerticalGrooveLeaf2 = trim((string) $row[$j++]);
                    $NumberOfHorizontalGrooveLeaf2 = trim((string) $row[$j++]);
                    $Leaf1VisionPanel = trim((string) $row[$j++]);
                    $Leaf1VisionPanelShape = trim((string) $row[$j++]);
                    $VisionPanelQuantity = trim((string) $row[$j++]);
                    $AreVPsEqualSizesForLeaf1 = trim((string) $row[$j++]);
                    $DistanceFromtopOfDoor = trim((string) $row[$j++]);
                    $DistanceFromTheEdgeOfDoor = trim((string) $row[$j++]);
                    $DistanceBetweenVPs = trim((string) $row[$j++]);
                    $Leaf1VPWidth = trim((string) $row[$j++]);
                    $Leaf1VPHeight1 = trim((string) $row[$j++]);
                    $Leaf1VPHeight2 = trim((string) $row[$j++]);
                    $Leaf1VPHeight3 = trim((string) $row[$j++]);
                    $Leaf1VPHeight4 = trim((string) $row[$j++]);
                    $Leaf1VPHeight5 = trim((string) $row[$j++]);
                    $Leaf1VPAreaSizem2 = trim((string) $row[$j++]);
                    $Leaf2VisionPanel = trim((string) $row[$j++]);
                    $sVPSameAsLeaf1 = trim((string) $row[$j++]);
                    $Leaf2VisionPanelQuantity = trim((string) $row[$j++]);
                    $AreVPsEqualSizesForLeaf2 = trim((string) $row[$j++]);
                    $DistanceFromTopOfDoorForLeaf2 = trim((string) $row[$j++]);
                    $DistanceFromTheEdgeOfDoorforLeaf2 = trim((string) $row[$j++]);
                    $DistanceBetweenVp = trim((string) $row[$j++]);
                    $Leaf2VPWidth = trim((string) $row[$j++]);
                    $Leaf2VPHeight1 = trim((string) $row[$j++]);
                    $Leaf2VPHeight2 = trim((string) $row[$j++]);
                    $Leaf2VPHeight3 = trim((string) $row[$j++]);
                    $Leaf2VPHeight4 = trim((string) $row[$j++]);
                    $Leaf2VPHeight5 = trim((string) $row[$j++]);
                    $GlassIntegrity = trim((string) $row[$j++]);
                    $GlassType = trim((string) $row[$j++]);
                    $GlassThickness = trim((string) $row[$j++]);
                    $GlazingSystems = trim((string) $row[$j++]);
                    $GlazingSystemThickness = trim((string) $row[$j++]);
                    $GlazingBeads = trim((string) $row[$j++]);
                    $GlazingBeadsThickness = trim((string) $row[$j++]);
                    $glazingBeadsWidth = trim((string) $row[$j++]);
                    $glazingBeadsHeight = trim((string) $row[$j++]);
                    $glazingBeadsFixingDetail = trim((string) $row[$j++]);
                    $GlazingBeadSpecies = trim((string) $row[$j++]);
                    $FrameMaterial = trim((string) $row[$j++]);
                    $FrameType = trim((string) $row[$j++]);
                    $PlantonStopWidth = trim((string) $row[$j++]);
                    $PlantonStopHeight = trim((string) $row[$j++]);
                    $ScallopedWidth = trim((string) $row[$j++]);
                    $ScallopedHeight = trim((string) $row[$j++]);
                    $RebatedWidth = trim((string) $row[$j++]);
                    $RebatedHeight = trim((string) $row[$j++]);
                    $FrameWidth = trim((string) $row[$j++]);
                    $FrameHeight = trim((string) $row[$j++]);
                    $FrameDepth = trim((string) $row[$j++]);
                    $FrameFinish = trim((string) $row[$j++]);
                    $FrameFinishColor = trim((string) $row[$j++]);
                    $ExtLiner = trim((string) $row[$j++]);
                    $DoorFrameConstruction = trim((string) $row[$j++]);
                    $ExtLinerValue = trim((string) $row[$j++]);
                    $extLinerSize = trim((string) $row[$j++]);
                    $ExtLinerThickness = trim((string) $row[$j++]);
                    $SpecialFeatureRefs = trim((string) $row[$j++]);
                    $Overpanel = trim((string) $row[$j++]);
                    $OPWidth = trim((string) $row[$j++]);
                    $OPHeigth = trim((string) $row[$j++]);
                    $OpBeadThickness = trim((string) $row[$j++]);
                    $OpBeadHeight = trim((string) $row[$j++]);
                    $OPTransom = trim((string) $row[$j++]);
                    $TransomThickness = trim((string) $row[$j++]);
                    $opGlassIntegrity = trim((string) $row[$j++]);
                    $OPGlassType = trim((string) $row[$j++]);
                    //
                    $OPGlassThickness = trim((string) $row[$j++]);
                    $opglazingSystemsvalue = trim((string) $row[$j++]);
                    $OPGlazingSystemsThickness = trim((string) $row[$j++]);
                    //
                    $OPGlazingBeads = trim((string) $row[$j++]);
                    //
                    $OPGlazingBeadsThickness = trim((string) $row[$j++]);
                    $OPGlazingBeadsHeight = trim((string) $row[$j++]);     // confusion
                    $OPGlazingBeadsFixingDetail = trim((string) $row[$j++]);
                    //
                    $OPGlazingBeadSpecies = trim((string) $row[$j++]);
                    $SideLight1 = trim((string) $row[$j++]);
                    $SideLight1GlassType = trim((string) $row[$j++]);
                    //
                    $SL1GlassThickness = trim((string) $row[$j++]);
                    $SL1GlazingSystems = trim((string) $row[$j++]);
                    $SL1GlazingSystemsThickness = trim((string) $row[$j++]);
                    //
                    $BeadingType = trim((string) $row[$j++]);
                    //
                    $SL1GlazingBeadsThickness = trim((string) $row[$j++]);
                    $SL1GlazingBeadsWidth = trim((string) $row[$j++]);
                    $SL1GlazingBeadsFixingDetail = trim((string) $row[$j++]);
                    //
                    $SL1GlazingBeadSpecies = trim((string) $row[$j++]);
                    $SL1Width = trim((string) $row[$j++]);
                    $SL1Height = trim((string) $row[$j++]);
                    $SlBeadThickness = trim((string) $row[$j++]);
                    $SlBeadHeight = trim((string) $row[$j++]);
                    $SL1Depth = trim((string) $row[$j++]);
                    $SL1Transom = trim((string) $row[$j++]);
                    $SideLight2 = trim((string) $row[$j++]);
                    $DoYouWantToCopySameAsSL1 = trim((string) $row[$j++]);
                    $SideLight2GlassType = trim((string) $row[$j++]);
                    //
                    $SL2GlassThickness = trim((string) $row[$j++]);
                    $SL2GlazingSystems = trim((string) $row[$j++]);
                    $SL2GlazingSystemsThickness = trim((string) $row[$j++]);
                    //
                    $SideLight2BeadingType = trim((string) $row[$j++]);
                    //
                    $SL2GlazingBeadsThickness = trim((string) $row[$j++]);
                    $SL2GlazingBeadsWidth = trim((string) $row[$j++]);
                    $SL2GlazingBeadsFixingDetail = trim((string) $row[$j++]);
                    //
                    $SideLight2GlazingBeadSpecies = trim((string) $row[$j++]);
                    $SL2Width = trim((string) $row[$j++]);
                    $SL2Height = trim((string) $row[$j++]);
                    $SL2Depth = trim((string) $row[$j++]);
                    $SL2Transom = trim((string) $row[$j++]);
                    $SLtransomHeightFromTop = trim((string) $row[$j++]);
                    $SLtransomThickness = trim((string) $row[$j++]);
                    $LippingType = trim((string) $row[$j++]);
                    $LippingThickness = trim((string) $row[$j++]);
                    $LippingSpecies = trim((string) $row[$j++]);
                    $MeetingStyle = trim((string) $row[$j++]);
                    $ScallopedLippingThickness = trim((string) $row[$j++]);
                    $FlatLippingThickness = trim((string) $row[$j++]);
                    $RebatedLippingThickness = trim((string) $row[$j++]);
                    $CoreWidth1 = trim((string) $row[$j++]);
                    $CoreWidth2 = trim((string) $row[$j++]);
                    $CoreHeight = trim((string) $row[$j++]);
                    $IntumescentLeapingSealType = trim((string) $row[$j++]);
                    $IntumescentLeapingSealLocation = trim((string) $row[$j++]);
                    $IntumescentLeapingSealColor = trim((string) $row[$j++]);
                    $IntumescentLeapingSealArrangement = trim((string) $row[$j++]);
                    $intumescentSealMeetingEdges = trim((string) $row[$j++]);
                    $Accoustics = trim((string) $row[$j++]);
                    $rWdBRating = trim((string) $row[$j++]);
                    $perimeterSeal1 = trim((string) $row[$j++]);
                    $perimeterSeal2 = trim((string) $row[$j++]);
                    // $thresholdSeal1 = trim($row[$j++]);
                    // $thresholdSeal2 = trim($row[$j++]);
                    $AccousticsMeetingStiles = trim((string) $row[$j++]);
                    $Architrave = trim((string) $row[$j++]);
                    $ArchitraveMaterial = trim((string) $row[$j++]);
                    $ArchitraveType = trim((string) $row[$j++]);
                    $ArchitraveWidth = trim((string) $row[$j++]);
                    $ArchitraveThickness = trim((string) $row[$j++]);
                    $ArchitraveFinish = trim((string) $row[$j++]);
                    $ArchitraveFinishColor = trim((string) $row[$j++]);
                    $ArchitraveSetQty = trim((string) $row[$j++]);
                    $DoorsetPrice = trim((string) $row[$j++]);
                    $IronmongaryPrice = trim((string) $row[$j++]);

                    $Checkingfirerating = Option::where(['OptionSlug' => 'fire_rating', 'OptionKey' => $FireRating])->count();
                    if ($Checkingfirerating == 0) {
                        $countFR = 0;
                    }

                    if ($DoorLeafFacing !== '' && $DoorLeafFacing !== '0') {
                        $CheckingDLF = Option::where(['OptionSlug' => 'Door_Leaf_Facing', 'OptionKey' => $DoorLeafFacing])->count();
                        if ($CheckingDLF == 0) {
                            $countDFR = 0;
                        }
                    } else {
                        $CheckingDLF = 1;
                    }


                    if ($Checkingfirerating > 0 && $CheckingDLF > 0) {
                        $itemCount = Item::where(['QuotationId' => $quotationId, 'DoorType' => $DoorType])->count();
                        if ($itemCount > 0) {
                            $itemLastId = Item::where(['QuotationId' => $quotationId, 'DoorType' => $DoorType])->first();
                            $itemId = $itemLastId->itemId;
                            $itemMasterCount = Item::join('item_master', 'items.itemId', 'item_master.itemID')
                                ->where(['items.QuotationId' => $quotationId, 'item_master.doorNumber' => $doorNumber])->count();
                            if ($itemMasterCount == 0) {
                                $dd = new ItemMaster();
                                $dd->itemID = $itemId;
                                $dd->doorNumber = $doorNumber;
                                if (isset($floor)) {
                                    $dd->floor = $floor;
                                }

                                $dd->save();

                                if ($versionId > 0) {
                                    $itemMasterID = ItemMaster::orderBy('id', 'DESC')->limit(1)->first();
                                    $itemMasterTblID = $itemMasterID->id;

                                    $QVI = new QuotationVersionItems();
                                    $QVI->version_id = $versionId;
                                    $QVI->itemID = $itemId;
                                    $QVI->itemmasterID = $itemMasterTblID;
                                    $QVI->save();
                                }
                            }

                            $success = 0;
                        } else {
                            $IronmongaryPrice = 0;
                            if (!empty(floatval($IronmongeryID)) || floatval($IronmongeryID) != 0) {
                                $AI = AddIronmongery::select('discountprice')->where('id', floatval($IronmongeryID))->where('UserId', user_id())->first();
                                $IronmongaryPrice = empty($AI) ? 0 : $AI->discountprice;
                            }

                            $FrameOnOff = ($FrameOnOff == 1) ? 1 : 0;
                            $aa = new Item();
                            $aa->QuotationId = $quotationId;
                            $aa->IntumescentLeafType = $IntumescentLeafType;
                            $aa->VersionId = $versionId;
                            $aa->UserId = $UserId;
                            $aa->DoorQuantity = floatval($DoorQuantity);
                            $aa->FourSidedFrame = ($FourSidedFrame == 1) ? 1 : 0;
                            $aa->DoorType = $DoorType;
                            $aa->FireRating = $FireRating;
                            $aa->DoorsetType = $DoorsetType;
                            $aa->SwingType = $SwingType;
                            $aa->LatchType = $LatchType;
                            $aa->Handing = $Handing;
                            $aa->Dropseal = ($Dropseal == 1) ? 1 : 0;
                            $aa->OpensInwards = $OpensInwards;
                            $aa->IronmongerySet = $IronmongerySet;
                            $aa->IronmongeryID = floatval($IronmongeryID);
                            $aa->LeafWidth1 = floatval($LeafWidth1);
                            $aa->LeafWidth2 = floatval($LeafWidth2);
                            $aa->LeafHeight = floatval($LeafHeight);
                            $aa->LeafThickness = floatval($LeafThickness);
                            $aa->DoorLeafFacing = $DoorLeafFacing;
                            $aa->DoorLeafFacingValue = $DoorLeafFacingValue;
                            $aa->DoorLeafFinish = $DoorLeafFinish;
                            $aa->DoorLeafFinishColor = $DoorLeafFinishColor;
                            $aa->SheenLevel = $SheenLevel;
                            $aa->DecorativeGroves = $DecorativeGroves;
                            $aa->GrooveLocation = $GrooveLocation;
                            $aa->GrooveWidth = floatval($GrooveWidth);
                            $aa->GrooveDepth = floatval($GrooveDepth);
                            $aa->MaxNumberOfGroove = floatval($MaxNumberOfGroove);
                            $aa->NumberOfGroove = floatval($NumberOfGroove);
                            $aa->NumberOfVerticalGroove = floatval($NumberOfVerticalGroove);
                            $aa->NumberOfHorizontalGroove = floatval($NumberOfHorizontalGroove);
                            $aa->DecorativeGrovesLeaf2 = $DecorativeGrovesLeaf2;
                            $aa->GrooveLocationLeaf2 = $GrooveLocationLeaf2;
                            $aa->IsSameAsDecorativeGroves1 = $IsSameAsDecorativeGroves1;
                            $aa->GrooveWidthLeaf2 = floatval($GrooveWidthLeaf2);
                            $aa->GrooveDepthLeaf2 = floatval($GrooveDepthLeaf2);
                            $aa->MaxNumberOfGrooveLeaf2 = floatval($MaxNumberOfGrooveLeaf2);
                            $aa->NumberOfGrooveLeaf2 = floatval($NumberOfGrooveLeaf2);
                            $aa->NumberOfVerticalGrooveLeaf2 = floatval($NumberOfVerticalGrooveLeaf2);
                            $aa->NumberOfHorizontalGrooveLeaf2 = floatval($NumberOfHorizontalGrooveLeaf2);
                            $aa->Leaf1VisionPanel = $Leaf1VisionPanel;
                            $aa->Leaf1VisionPanelShape = $Leaf1VisionPanelShape;
                            $aa->VisionPanelQuantity = floatval($VisionPanelQuantity);
                            $aa->AreVPsEqualSizesForLeaf1 = $AreVPsEqualSizesForLeaf1;
                            $aa->DistanceFromtopOfDoor = floatval($DistanceFromtopOfDoor);
                            $aa->DistanceFromTheEdgeOfDoor = floatval($DistanceFromTheEdgeOfDoor);
                            $aa->DistanceBetweenVPs = floatval($DistanceBetweenVPs);
                            $aa->Leaf1VPWidth = floatval($Leaf1VPWidth);
                            $aa->Leaf1VPHeight1 = floatval($Leaf1VPHeight1);
                            $aa->Leaf1VPHeight2 = floatval($Leaf1VPHeight2);
                            $aa->Leaf1VPHeight3 = floatval($Leaf1VPHeight3);
                            $aa->Leaf1VPHeight4 = floatval($Leaf1VPHeight4);
                            $aa->Leaf1VPHeight5 = floatval($Leaf1VPHeight5);
                            $aa->Leaf1VPAreaSizem2 = floatval($Leaf1VPAreaSizem2);
                            $aa->Leaf2VisionPanel = $Leaf2VisionPanel;
                            $aa->sVPSameAsLeaf1 = $sVPSameAsLeaf1;
                            $aa->Leaf2VisionPanelQuantity = floatval($Leaf2VisionPanelQuantity);
                            $aa->AreVPsEqualSizesForLeaf2 = $AreVPsEqualSizesForLeaf2;
                            $aa->DistanceFromTopOfDoorForLeaf2 = floatval($DistanceFromTopOfDoorForLeaf2);
                            $aa->DistanceFromTheEdgeOfDoorforLeaf2 = floatval($DistanceFromTheEdgeOfDoorforLeaf2);
                            $aa->DistanceBetweenVp = floatval($DistanceBetweenVp);
                            $aa->Leaf2VPWidth = floatval($Leaf2VPWidth);
                            $aa->Leaf2VPHeight1 = floatval($Leaf2VPHeight1);
                            $aa->Leaf2VPHeight2 = floatval($Leaf2VPHeight2);
                            $aa->Leaf2VPHeight3 = floatval($Leaf2VPHeight3);
                            $aa->Leaf2VPHeight4 = floatval($Leaf2VPHeight4);
                            $aa->Leaf2VPHeight5 = floatval($Leaf2VPHeight5);
                            $aa->GlassIntegrity = $GlassIntegrity;
                            $aa->GlassType = $GlassType;
                            $aa->GlassThickness = floatval($GlassThickness);
                            $aa->GlazingSystems = $GlazingSystems;
                            $aa->GlazingSystemThickness = floatval($GlazingSystemThickness);
                            $aa->GlazingBeads = $GlazingBeads;
                            $aa->GlazingBeadsThickness = floatval($GlazingBeadsThickness);
                            $aa->glazingBeadsWidth = floatval($glazingBeadsWidth);
                            $aa->glazingBeadsHeight = floatval($glazingBeadsHeight);
                            $aa->glazingBeadsFixingDetail = $glazingBeadsFixingDetail;
                            $aa->GlazingBeadSpecies = lippingSpeciesId($GlazingBeadSpecies);
                            if ($FrameOnOff == 0) {
                                $aa->COC = $COC;
                                $aa->Tollerance = floatval($Tollerance);
                                $aa->Undercut = floatval($Undercut);
                                $aa->FloorFinish = floatval($FloorFinish);
                                $aa->GAP = floatval($GAP);
                                $aa->FrameThickness = floatval($FrameThickness);
                                $aa->SOHeight = floatval($SOHeight);
                                $aa->SOWidth = floatval($SOWidth);
                                $aa->SOWallThick = floatval($SOWallThick);
                                $aa->FrameMaterial = lippingSpeciesId($FrameMaterial);
                                $aa->FrameType = $FrameType;
                                $aa->PlantonStopWidth = floatval($PlantonStopWidth);
                                $aa->PlantonStopHeight = floatval($PlantonStopHeight);
                                $aa->RebatedWidth = floatval($RebatedWidth);
                                $aa->RebatedHeight = floatval($RebatedHeight);
                                $aa->ScallopedWidth = floatval($ScallopedWidth);
                                $aa->ScallopedHeight = floatval($ScallopedHeight);
                                $aa->FrameWidth = floatval($FrameWidth);
                                $aa->FrameHeight = floatval($FrameHeight);
                                $aa->FrameDepth = floatval($FrameDepth);
                                $aa->FrameFinish = $FrameFinish;
                                $aa->FrameFinishColor = $FrameFinishColor;
                                $aa->ExtLiner = $ExtLiner;
                                $aa->DoorFrameConstruction = $DoorFrameConstruction;
                                $aa->ExtLinerValue = $ExtLinerValue;
                                $aa->extLinerSize = floatval($extLinerSize);
                                $aa->ExtLinerThickness = floatval($ExtLinerThickness);
                                $aa->SpecialFeatureRefs = $SpecialFeatureRefs;
                                $aa->Overpanel = $Overpanel;
                                $aa->OPWidth = floatval($OPWidth);
                                $aa->OPHeigth = floatval($OPHeigth);
                                $aa->OpBeadThickness = floatval($OpBeadThickness);
                                $aa->OpBeadHeight = floatval($OpBeadHeight);
                                $aa->OPTransom = floatval($OPTransom);
                                $aa->TransomThickness = $TransomThickness;
                                $aa->opGlassIntegrity = $opGlassIntegrity;
                                $aa->OPGlassType = $OPGlassType;
                                //
                                $aa->OPGlassThickness = floatval($OPGlassThickness);
                                $aa->OPGlazingSystems = $opglazingSystemsvalue;
                                $aa->OPGlazingSystemsThickness = floatval($OPGlazingSystemsThickness);
                                //
                                $aa->OPGlazingBeads = $OPGlazingBeads;
                                //
                                $aa->OPGlazingBeadsThickness = floatval($OPGlazingBeadsThickness);
                                $aa->OPGlazingBeadsHeight = floatval($OPGlazingBeadsHeight);     // confusion
                                $aa->OPGlazingBeadsFixingDetail = $OPGlazingBeadsFixingDetail;
                                //
                                $aa->OPGlazingBeadSpecies = lippingSpeciesId($OPGlazingBeadSpecies);
                                $aa->SideLight1 = $SideLight1;
                                $aa->SideLight1GlassType = $SideLight1GlassType;
                                //
                                $aa->SideLight1GlassThickness = floatval($SL1GlassThickness);
                                $aa->SideLight1GlazingSystems = $SL1GlazingSystems;
                                $aa->SideLight1GlazingSystemsThickness = floatval($SL1GlazingSystemsThickness);
                                //
                                $aa->BeadingType = $BeadingType;
                                //
                                $aa->SideLight1GlazingBeadsThickness = floatval($SL1GlazingBeadsThickness);
                                $aa->SideLight1GlazingBeadsWidth = floatval($SL1GlazingBeadsWidth);
                                $aa->SideLight1GlazingBeadsFixingDetail = $SL1GlazingBeadsFixingDetail;
                                //
                                $aa->SL1GlazingBeadSpecies = lippingSpeciesId($SL1GlazingBeadSpecies);
                                $aa->SL1Width = floatval($SL1Width);
                                $aa->SL1Height = floatval($SL1Height);
                                $aa->SlBeadThickness = floatval($SlBeadThickness);
                                $aa->SlBeadHeight = floatval($SlBeadHeight);
                                $aa->SL1Depth = floatval($SL1Depth);
                                $aa->SL1Transom = floatval($SL1Transom);
                                $aa->SideLight2 = $SideLight2;
                                $aa->DoYouWantToCopySameAsSL1 = $DoYouWantToCopySameAsSL1;
                                $aa->SideLight2GlassType = $SideLight2GlassType;
                                //
                                $aa->SideLight2GlassThickness = floatval($SL2GlassThickness);
                                $aa->SideLight2GlazingSystems = $SL2GlazingSystems;
                                $aa->SideLight2GlazingSystemsThickness = floatval($SL2GlazingSystemsThickness);
                                //
                                $aa->SideLight2BeadingType = $SideLight2BeadingType;
                                //
                                $aa->SideLight2GlazingBeadsThickness = floatval($SL2GlazingBeadsThickness);
                                $aa->SideLight2GlazingBeadsWidth = floatval($SL2GlazingBeadsWidth);
                                $aa->SideLight2GlazingBeadsFixingDetail = $SL2GlazingBeadsFixingDetail;
                                //
                                $aa->SideLight2GlazingBeadSpecies = lippingSpeciesId($SideLight2GlazingBeadSpecies);
                                $aa->SL2Width = floatval($SL2Width);
                                $aa->SL2Height = floatval($SL2Height);
                                $aa->SL2Depth = floatval($SL2Depth);
                                $aa->SL2Transom = floatval($SL2Transom);
                                $aa->SLtransomHeightFromTop = floatval($SLtransomHeightFromTop);
                                $aa->SLtransomThickness = floatval($SLtransomThickness);
                                $aa->Architrave = $Architrave;
                                $aa->ArchitraveMaterial = lippingSpeciesId($ArchitraveMaterial);
                                $aa->ArchitraveType = $ArchitraveType;
                                $aa->ArchitraveWidth = floatval($ArchitraveWidth);
                                $aa->ArchitraveHeight = floatval($ArchitraveThickness);
                                $aa->ArchitraveFinish = $ArchitraveFinish;
                                $aa->ArchitraveFinishColor = $ArchitraveFinishColor;
                                $aa->ArchitraveSetQty = floatval($ArchitraveSetQty);
                            }

                            $aa->LippingType = $LippingType;
                            $aa->LippingThickness = floatval($LippingThickness);
                            $aa->LippingSpecies = lippingSpeciesId($LippingSpecies);
                            $aa->MeetingStyle = $MeetingStyle;
                            $aa->ScallopedLippingThickness = floatval($ScallopedLippingThickness);
                            $aa->FlatLippingThickness = floatval($FlatLippingThickness);
                            $aa->RebatedLippingThickness = floatval($RebatedLippingThickness);
                            $aa->CoreWidth1 = floatval($CoreWidth1);
                            $aa->CoreWidth2 = floatval($CoreWidth2);
                            $aa->CoreHeight = floatval($CoreHeight);
                            $aa->IntumescentLeapingSealType = $IntumescentLeapingSealType;
                            $aa->IntumescentLeapingSealLocation = $IntumescentLeapingSealLocation;
                            $aa->IntumescentLeapingSealColor = $IntumescentLeapingSealColor;
                            $aa->IntumescentLeapingSealArrangement = $IntumescentLeapingSealArrangement;
                            $aa->intumescentSealMeetingEdges = $intumescentSealMeetingEdges;
                            $aa->Accoustics = $Accoustics;
                            $aa->rWdBRating = $rWdBRating;
                            $aa->perimeterSeal1 = $perimeterSeal1;
                            $aa->perimeterSeal2 = $perimeterSeal2;
                            // $aa->thresholdSeal1 = $thresholdSeal1;
                            // $aa->thresholdSeal2 = $thresholdSeal2;
                            $aa->AccousticsMeetingStiles = $AccousticsMeetingStiles;

                            // $aa->DoorsetPrice = floatval($DoorsetPrice);
                            $aa->IronmongaryPrice = $IronmongaryPrice;
                            $aa->FrameOnOff = $FrameOnOff;

                            $aa->save();

                            $item = new Item();
                            $item->itemID = $aa->id;
                            $item->QuotationId = $aa->QuotationId;
                            $item->version_id = $aa->VersionId;
                            $item->IntumescentLeafType = $aa->IntumescentLeafType;
                            $item->UserId = Auth::user()->id;
                            $item->DoorQuantity = $aa->DoorQuantity;
                            //Main Options
                            $item->FourSidedFrame = $aa->FourSidedFrame;
                            $item->Dropseal = $aa->Dropseal;
                            $item->ScallopedWidth = $aa->ScallopedWidth;
                            $item->ScallopedHeight = $aa->ScallopedHeight;
                            $item->doorType = $aa->DoorType;
                            $item->fireRating = $aa->FireRating;
                            $item->doorsetType = $aa->DoorsetType;
                            $item->swingType = $aa->SwingType;
                            $item->latchType = $aa->LatchType;
                            $item->Handing = $aa->Handing;
                            $item->OpensInwards = $aa->OpensInwards;
                            $item->COC = $aa->COC;
                            $item->tollerance = $aa->Tollerance;
                            $item->undercut = $aa->Undercut;
                            $item->floorFinish = $aa->FloorFinish;
                            $item->gap = $aa->GAP;
                            $item->frameThickness = $aa->FrameThickness;
                            $item->ironmongerySet = $aa->IronmongerySet;
                            $item->IronmongeryID = $aa->IronmongeryID;
                            //Door Dimensions & Door Leaf
                            $item->sOWidth = $aa->SOWidth;
                            $item->sOHeight = $aa->SOHeight;
                            $item->sODepth = $aa->SOWallThick;
                            $item->leafWidth1 = $aa->LeafWidth1;
                            $item->leafWidth2 = $aa->LeafWidth2;
                            $item->leafHeightNoOP = $aa->LeafHeight;
                            $item->doorThickness = $aa->LeafThickness;
                            $item->doorLeafFacing = $aa->DoorLeafFacing;
                            $item->doorLeafFacingValue = $aa->DoorLeafFacingValue;
                            $item->doorLeafFinish = $aa->DoorLeafFinish;
                            $item->doorLeafFinishColor = $aa->DoorLeafFinishColor;
                            $item->SheenLevel = $aa->SheenLevel;
                            $item->decorativeGroves = $aa->DecorativeGroves;
                            $item->grooveLocation = $aa->GrooveLocation;
                            $item->grooveWidth = $aa->GrooveWidth;
                            $item->grooveDepth = $aa->GrooveDepth;
                            $item->maxNumberOfGroove = $aa->MaxNumberOfGroove;
                            $item->numberOfGroove = $aa->NumberOfGroove;
                            $item->numberOfVerticalGroove = $aa->NumberOfVerticalGroove;
                            $item->numberOfHorizontalGroove = $aa->NumberOfHorizontalGroove;
                            $item->DecorativeGrovesLeaf2 = $aa->DecorativeGrovesLeaf2;
                            $item->GrooveLocationLeaf2 = $aa->GrooveLocationLeaf2;
                            $item->IsSameAsDecorativeGroves1 = $aa->IsSameAsDecorativeGroves1;
                            $item->GrooveWidthLeaf2 = $aa->GrooveWidthLeaf2;
                            $item->GrooveDepthLeaf2 = $aa->GrooveDepthLeaf2;
                            $item->MaxNumberOfGrooveLeaf2 = $aa->MaxNumberOfGrooveLeaf2;
                            $item->NumberOfGrooveLeaf2 = $aa->NumberOfGrooveLeaf2;
                            $item->NumberOfVerticalGrooveLeaf2 = $aa->NumberOfVerticalGrooveLeaf2;
                            $item->NumberOfHorizontalGrooveLeaf2 = $aa->NumberOfHorizontalGrooveLeaf2;

                            //Vision Panel
                            $item->leaf1VisionPanel = $aa->Leaf1VisionPanel;
                            $item->leaf1VisionPanelShape = $aa->Leaf1VisionPanelShape;
                            $item->visionPanelQuantity = $aa->VisionPanelQuantity;
                            $item->AreVPsEqualSizes = $aa->AreVPsEqualSizesForLeaf1;
                            $item->distanceFromTopOfDoor = $aa->DistanceFromtopOfDoor;
                            $item->distanceFromTheEdgeOfDoor = $aa->DistanceFromTheEdgeOfDoor;
                            $item->distanceBetweenVPs = $aa->DistanceBetweenVPs;
                            $item->vP1Width = $aa->Leaf1VPWidth;
                            $item->vP1Height1 = $aa->Leaf1VPHeight1;
                            $item->vP1Height2 = $aa->Leaf1VPHeight2;
                            $item->vP1Height3 = $aa->Leaf1VPHeight3;
                            $item->vP1Height4 = $aa->Leaf1VPHeight4;
                            $item->vP1Height5 = $aa->Leaf1VPHeight5;
                            $item->leaf1VpAreaSizeM2 = $aa->Leaf1VPAreaSizem2;
                            $item->leaf2VisionPanel = $aa->Leaf2VisionPanel;
                            $item->vpSameAsLeaf1 = $aa->sVPSameAsLeaf1;
                            $item->Leaf2VisionPanelQuantity = $aa->Leaf2VisionPanelQuantity;
                            $item->AreVPsEqualSizesForLeaf2 =  $aa->AreVPsEqualSizesForLeaf2;
                            $item->distanceFromTopOfDoorforLeaf2 = $aa->DistanceFromTopOfDoorForLeaf2;
                            $item->distanceFromTheEdgeOfDoorforLeaf2 = $aa->DistanceFromTheEdgeOfDoorforLeaf2;
                            $item->distanceBetweenVPsforLeaf2 = $aa->DistanceBetweenVp;
                            $item->vP2Width = $aa->Leaf2VPWidth;
                            $item->vP2Height1 = $aa->Leaf2VPHeight1;
                            $item->vP2Height2 = $aa->Leaf2VPHeight2;
                            $item->vP2Height3 = $aa->Leaf2VPHeight3;
                            $item->vP2Height4 = $aa->Leaf2VPHeight4;
                            $item->vP2Height5 = $aa->Leaf2VPHeight5;
                            $item->lazingIntegrityOrInsulationIntegrity = $aa->GlassIntegrity;
                            $item->glassType = $aa->GlassType;
                            $item->glassThickness = $aa->GlassThickness;
                            $item->glazingSystems = $aa->GlazingSystems;
                            $item->glazingSystemsThickness = $aa->GlazingSystemThickness;
                            $item->glazingBeads = $aa->GlazingBeads;
                            $item->glazingBeadsThickness = $aa->GlazingBeadsThickness;
                            $item->glazingBeadsWidth = $aa->glazingBeadsWidth;
                            $item->glazingBeadsHeight = $aa->glazingBeadsHeight;
                            $item->glazingBeadsFixingDetail = $aa->glazingBeadsFixingDetail;
                            $item->glazingBeadSpecies = $aa->GlazingBeadSpecies;

                            //Frame
                            $item->frameMaterial = $aa->FrameMaterial;
                            $item->frameType = $aa->FrameType;
                            // streboard
                            $item->plantonStopWidth = $aa->PlantonStopWidth;
                            $item->plantonStopHeight = $aa->PlantonStopHeight;
                            // streboard
                            $item->rebatedWidth = $aa->RebatedWidth;
                            $item->rebatedHeight = $aa->RebatedHeight;
                            //halspan
                            // $item->standardWidth = $aa->QuotationId;
                            // $item->standardHeight = $aa->QuotationId;
                            $item->frameWidth = $aa->FrameWidth;
                            $item->frameHeight = $aa->FrameHeight;
                            $item->frameDepth = $aa->FrameDepth;
                            $item->frameFinish = $aa->FrameFinish;
                            $item->framefinishColor = $aa->FrameFinishColor;
                            $item->extLiner = $aa->ExtLiner;
                            $item->frameCostuction = $aa->DoorFrameConstruction;
                            $item->extLinerValue = $aa->ExtLinerValue;
                            $item->extLinerSize = $aa->extLinerSize;
                            $item->extLinerThickness = $aa->ExtLinerThickness;
                            $item->ironmongerySet = $aa->IronmongerySet;
                            $item->IronmongeryID = $aa->IronmongeryID;
                            $item->specialFeatureRefs = $aa->SpecialFeatureRefs;

                            //Over Panel Section
                            $item->overpanel = $aa->Overpanel;
                            $item->oPWidth = $aa->OPWidth;
                            $item->oPHeigth = $aa->OPHeigth;
                            $item->opTransom = $aa->OPTransom;
                            $item->transomThickness = $aa->TransomThickness;
                            $item->opGlassIntegrity = $aa->opGlassIntegrity;
                            $item->opGlassType = $aa->OPGlassType;
                            //
                            $item->opglassThickness = $aa->OPGlassThickness;
                            $item->opglazingSystems = $aa->OPGlazingSystems;
                            $item->opglazingSystemsThickness =$aa->OPGlazingSystemsThickness;
                            //
                            $item->opGlazingBeads = $aa->OPGlazingBeads;
                            //
                            $item->opglazingBeadsThickness = $aa->OPGlazingBeadsThickness;
                            $item->opglazingBeadsHeight = $aa->OPGlazingBeadsHeight;     // confusion
                            $item->opglazingBeadsFixingDetail = $aa->OPGlazingBeadsFixingDetail;
                            //
                            $item->opGlazingBeadSpecies = $aa->OPGlazingBeadSpecies;
                            $item->OpBeadThickness = $aa->OpBeadThickness;
                            $item->OpBeadHeight = $aa->OpBeadHeight;

                            //Side Light
                            $item->sideLight1 = $aa->SideLight1;
                            $item->sideLight1GlassType = $aa->SideLight1GlassType;
                            //
                            $item->sideLight1GlassThickness = $aa->SideLight1GlassThickness;
                            $item->sideLight1GlazingSystems = $aa->SideLight1GlazingSystems;
                            $item->sideLight1GlazingSystemsThickness = $aa->SideLight1GlazingSystemsThickness;
                            //
                            $item->SideLight1BeadingType = $aa->BeadingType;
                            //
                            $item->sideLight1GlazingBeadsThickness = $aa->SideLight1GlazingBeadsThickness;
                            $item->sideLight1GlazingBeadsWidth = $aa->SideLight1GlazingBeadsWidth;
                            $item->sideLight1GlazingBeadsFixingDetail = $aa->SideLight1GlazingBeadsFixingDetail;
                            //
                            $item->SideLight1GlazingBeadSpecies = $aa->SL1GlazingBeadSpecies;
                            $item->SL1Width = $aa->SL1Width;
                            $item->SL1Height = $aa->SL1Height;
                            $item->SL1Depth = $aa->SL1Depth;
                            $item->SL1Transom = $aa->SL1Transom;
                            $item->sideLight2 = $aa->SideLight2;
                            $item->copyOfSideLite1 = $aa->DoYouWantToCopySameAsSL1;
                            $item->SideLight2GlassType = $aa->SideLight2GlassType;
                            //
                            $item->sideLight2GlassThickness = $aa->SideLight2GlassThickness;
                            $item->sideLight2GlazingSystems = $aa->SideLight2GlazingSystems;
                            $item->sideLight2GlazingSystemsThickness = $aa->SideLight2GlazingSystemsThickness;
                            //
                            $item->SideLight2BeadingType = $aa->SideLight2BeadingType;
                            //
                            $item->sideLight2GlazingBeadsThickness = $aa->SideLight2GlazingBeadsThickness;
                            $item->sideLight2GlazingBeadsWidth = $aa->SideLight2GlazingBeadsWidth;
                            $item->sideLight2GlazingBeadsFixingDetail = $aa->SideLight2GlazingBeadsFixingDetail;
                            //
                            $item->SideLight2GlazingBeadSpecies = $aa->SideLight2GlazingBeadSpecies;
                            $item->SL2Width = $aa->SL2Width;
                            $item->SL2Height = $aa->SL2Height;
                            $item->SL2Depth = $aa->SL2Depth;
                            $item->SL2Transom = $aa->SL2Transom;
                            $item->SLtransomHeightFromTop = $aa->SLtransomHeightFromTop;
                            $item->SLtransomThickness = $aa->SLtransomThickness;
                            $item->SlBeadThickness = $aa->SlBeadThickness;
                            $item->SlBeadHeight = $aa->SlBeadHeight;

                            //Lipping And Intumescent
                            $item->lippingType = $aa->LippingType;
                            $item->lippingThickness = $aa->LippingThickness;
                            $item->lippingSpecies = $aa->LippingSpecies;
                            $item->meetingStyle = $aa->MeetingStyle;
                            $item->scallopedLippingThickness = $aa->ScallopedLippingThickness;
                            $item->flatLippingThickness = $aa->FlatLippingThickness;
                            $item->rebatedLippingThickness = $aa->RebatedLippingThickness;
                            $item->coreWidth1 = $aa->CoreWidth1;
                            $item->coreWidth2 = $aa->CoreWidth2;
                            $item->coreHeight = $aa->CoreHeight;
                            $item->intumescentSealType = $aa->IntumescentLeapingSealType;
                            $item->intumescentSealLocation = $aa->IntumescentLeapingSealLocation;
                            $item->intumescentSealColor = $aa->IntumescentLeapingSealColor;
                            $item->intumescentSealArrangement = $aa->IntumescentLeapingSealArrangement;
                            $item->intumescentSealMeetingEdges = $aa->intumescentSealMeetingEdges;

                            //Accoustics
                            $item->accoustics = $aa->Accoustics;
                            $item->rWdBRating = $aa->rWdBRating;
                            $item->perimeterSeal1 = $aa->perimeterSeal1;
                            $item->perimeterSeal2 = $aa->perimeterSeal2;
                            // $item->thresholdSeal1 = $aa->thresholdSeal1;
                            // $item->thresholdSeal2 = $aa->thresholdSeal2;
                            $item->accousticsmeetingStiles = $aa->AccousticsMeetingStiles;

                            //Architrave
                            $item->Architrave = $aa->Architrave;
                            $item->architraveMaterial = $aa->ArchitraveMaterial;
                            $item->architraveType = $aa->ArchitraveType;
                            $item->architraveWidth = $aa->ArchitraveWidth;
                            $item->architraveHeight = $aa->ArchitraveHeight;
                            $item->architraveFinish = $aa->ArchitraveFinish;
                            $item->architraveFinishcolor = $aa->ArchitraveFinishColor;
                            $item->architraveSetQty = $aa->ArchitraveSetQty;
                            $item->issingleconfiguration = $configurableitems;

                            $item->IronmongaryPrice = $IronmongaryPrice;

                            $itemLastId = Item::orderBy('itemId', 'DESC')->limit(1)->first();
                            $itemId = $itemLastId->itemId;
                            // $itemMasterCount2 = ItemMaster::where(['itemID' => $itemId, 'doorNumber' => $doorNumber])->count();
                            $itemMasterCount2 = Item::join('item_master', 'items.itemId', 'item_master.itemID')
                                ->where(['items.QuotationId' => $quotationId, 'item_master.doorNumber' => $doorNumber, 'item_master.itemID' => $itemId])->count();
                            if ($itemMasterCount2 == 0) {
                                $dd = new ItemMaster();
                                $dd->itemID = $itemId;
                                $dd->doorNumber = $doorNumber;
                                if (isset($floor)) {
                                    $dd->floor = $floor;
                                }

                                $dd->save();

                                if ($versionId > 0) {
                                    $itemMasterID = ItemMaster::orderBy('id', 'DESC')->limit(1)->first();
                                    $itemMasterTblID = $itemMasterID->id;

                                    $QVI = new QuotationVersionItems();
                                    $QVI->version_id = $versionId;
                                    $QVI->itemID = $itemId;
                                    $QVI->itemmasterID = $itemMasterTblID;
                                    $QVI->save();
                                }
                            }

                            match ($configurableitems) {
                                // VICAIMA DOOR
                                '4' => BomCalculationVicaima($item),
                                // Seadec DOOR
                                '5' => BomCalculationSeadec($item),
                                // Deanta DOOR
                                '6' => BomCalculationDeanta($item),
                                // Halspan DOOR
                                '2' => HalspanBomCalculation($item),
                                // Flamebreak DOOR
                                '7' => FlamebreakBomCalculation($request),
                                // Stredor DOOR
                                '8' => StredorBomCalculation($request),
                                // STAREBOARD AND ALL
                                default => BomCalculation($item),
                            };

                            $BOMCalculation = BOMCalculation::select('*')->where('QuotationId', $aa->QuotationId)->where('DoorType', $DoorType)->get();
                            $Item = Item::where(['QuotationId' => $aa->QuotationId, 'DoorType' => $DoorType])->get()->first();

                            $Itemcount = Item::where(['QuotationId' => $aa->QuotationId])->get()->count();

                            if (!empty($BOMCalculation)) {
                                foreach ($BOMCalculation as $value) {
                                    $BOM = BOMCalculation::find($value->id);
                                    if (!empty($BOM)) {
                                        $BOM->itemId = $Item->itemId;
                                        $BOM->save();
                                    }
                                }
                            }

                            $BOMCalculation = BOMCalculation::select('*')->where('QuotationId', $aa->QuotationId)->where('DoorType', $DoorType)->where('itemId', $Item->itemId)->get();
                            $GTSellPrice = 0;
                            $GTSellPriceTotal = 0;
                            if (!empty($BOMCalculation)) {
                                foreach ($BOMCalculation as $value1) {
                                    if($value1->Category != 'Ironmongery&MachiningCosts'){
                                        $GTSellPrice += $value1->GTSellPrice;
                                    }
                                }

                                $ItemMaster = ItemMaster::where('itemID', $Item->itemId)->get()->count();
                                $GTSellPriceTotal = round(($GTSellPrice / $ItemMaster), 2);
                            }

                            $Item = Item::where('itemId', $Item->itemId)->update([
                                'DoorsetPrice' => $GTSellPriceTotal
                            ]);

                            $success = 0;
                        }

                        // BOMQuatityOfDoorUpdate($itemId, $quotationId);
                    }
                }

                $error = null;
                $error2 = null;
                $error3 = null;

                if ($countFR === 0) {
                    $error = '<p style="color:red">Some Fire Rating is not in correct format</p>';
                }

                if ($countDFR === 0) {
                    $error2 = '<p style="color:red">Some VisionPanel is not in correct formate.</p>';
                }

                if ($success === 0) {
                    $error3 = '<p>Excel file is imported successfully.</p>';
                }

                // if(!empty($countFR) && !empty($countDFR) && !empty($success)){
                //     return redirect()->back()->with('success',$error3.$error.$error2);
                // } else if(empty($success)){
                //     return redirect()->back()->with('error',$error.$error2);
                // } else {
                //     return redirect()->back()->with('success',$error3.$error.$error2);
                // }
                return redirect()->back()->with('success', $error3 . $error . $error2);
                // return redirect('quotation/request/'.$quotationId.'/'.$versionId)->with('success','Excel file is imported successfully.'.$error);

            } elseif ($configurableitems == 4 || $configurableitems == 5 || $configurableitems == 6) {

                $quotation->configurableitems = $configurableitems;
                $quotation->save();
                $i = 0;
                //   dd($data[0]);
                foreach ($data[0] as $row) {

                    if (isset($row[6]) && ($i == 0 || trim($row[6]) === '')) {
                        $i++;
                        continue;
                    }

                    $j = 2;
                    $FrameOnOff = trim((string) $row[$j++]);
                    $floor = trim((string) $row[$j++]);
                    $doorNumber = trim((string) $row[$j++]);
                    $location = trim((string) $row[$j++]);
                    $DoorQuantity = trim((string) $row[$j++]);
                    $FourSidedFrame = trim((string) $row[$j++]);
                    $LeafType = trim((string) $row[$j++]);
                    $DoorType = trim((string) $row[$j++]);
                    $FireRating = trim((string) $row[$j++]);
                    $DoorsetType = trim((string) $row[$j++]);
                    $SwingType = trim((string) $row[$j++]);
                    $LatchType = trim((string) $row[$j++]);
                    $Handing = trim((string) $row[$j++]);
                    $OpensInwards = trim((string) $row[$j++]);
                    $Tollerance = trim((string) $row[$j++]);
                    $Dropseal = trim((string) $row[$j++]);
                    $Undercut = trim((string) $row[$j++]);
                    $FloorFinish = trim((string) $row[$j++]);
                    $GAP = trim((string) $row[$j++]);
                    $FrameThickness = trim((string) $row[$j++]);
                    $IronmongerySet = trim((string) $row[$j++]);
                    $IronmongeryID = trim((string) $row[$j++]);
                    $DoorLeafFacing = trim((string) $row[$j++]);
                    $DoorLeafFinish = trim((string) $row[$j++]);
                    $DoorDimensionId = trim((string) $row[$j++]);
                    $DoorDimensionId2 = trim((string) $row[$j++]);
                    $DoorDimension = trim((string) $row[$j++]);
                    $SOHeight = trim((string) $row[$j++]);
                    $SOWidth = trim((string) $row[$j++]);
                    $SOWallThick = trim((string) $row[$j++]);
                    $LeafWidth1 = trim((string) $row[$j++]);
                    $LeafWidth1Adjustment = trim((string) $row[$j++]);
                    $LeafWidth2 = trim((string) $row[$j++]);
                    $LeafWidth2Adjustment = trim((string) $row[$j++]);
                    $LeafHeight = trim((string) $row[$j++]);
                    $LeafHeightAdjustment = trim((string) $row[$j++]);
                    $LeafThickness = trim((string) $row[$j++]);
                    $Hinge1Location = trim((string) $row[$j++]);
                    $Hinge2Location = trim((string) $row[$j++]);
                    $Hinge3Location = trim((string) $row[$j++]);
                    $Hinge4Location = trim((string) $row[$j++]);
                    $HingeCenterCheck = trim((string) $row[$j++]);
                    // $DoorLeafFacing = trim($row[$j++]);
                    // $DoorLeafFacingValue = trim($row[$j++]);
                    // $DoorLeafFinish = trim($row[$j++]);
                    // $DoorLeafFinishColor = trim($row[$j++]);
                    // $SheenLevel = trim($row[$j++]);
                    $DecorativeGroves = trim((string) $row[$j++]);
                    $DecorativeGrovesIcon = trim((string) $row[$j++]);
                    // $GrooveLocation = trim($row[$j++]);
                    $GrooveWidth = trim((string) $row[$j++]);
                    $GrooveDepth = trim((string) $row[$j++]);
                    $MaxNumberOfGroove = trim((string) $row[$j++]);
                    $NumberOfGroove = trim((string) $row[$j++]);
                    $DecorativeGrovesLeaf2  = trim((string) $row[$j++]);
                    $IsSameAsDecorativeGroves1  = trim((string) $row[$j++]);
                    $GroovesNumberLeaf2  = trim((string) $row[$j++]);
                    $GrooveWidthLeaf2 = trim((string) $row[$j++]);
                    $GrooveDepthLeaf2 = trim((string) $row[$j++]);
                    $MaxNumberOfGrooveLeaf2 = trim((string) $row[$j++]);
                    $NumberOfGrooveLeaf2 = trim((string) $row[$j++]);
                    // $NumberOfVerticalGroove = trim($row[$j++]);
                    // $NumberOfHorizontalGroove = trim($row[$j++]);
                    $Leaf1VisionPanel = trim((string) $row[$j++]);
                    $Leaf1VisionPanelShape = trim((string) $row[$j++]);
                    $VisionPanelQuantity = trim((string) $row[$j++]);
                    $AreVPsEqualSizesForLeaf1 = trim((string) $row[$j++]);
                    $DistanceFromtopOfDoor = trim((string) $row[$j++]);
                    $DistanceFromTheEdgeOfDoor = trim((string) $row[$j++]);
                    $DistanceBetweenVPs = trim((string) $row[$j++]);

                    $Leaf1VPWidth = trim((string) $row[$j++]);
                    $Leaf1VPHeight1 = trim((string) $row[$j++]);
                    $Leaf1VPHeight2 = trim((string) $row[$j++]);
                    $Leaf1VPHeight3 = trim((string) $row[$j++]);
                    $Leaf1VPHeight4 = trim((string) $row[$j++]);
                    $Leaf1VPHeight5 = trim((string) $row[$j++]);
                    $Leaf1VPAreaSizem2 = trim((string) $row[$j++]);
                    $Leaf2VisionPanel = trim((string) $row[$j++]);
                    $sVPSameAsLeaf1 = trim((string) $row[$j++]);
                    $Leaf2VisionPanelQuantity = trim((string) $row[$j++]);
                    $AreVPsEqualSizesForLeaf2 = trim((string) $row[$j++]);
                    $DistanceFromTopOfDoorForLeaf2 = trim((string) $row[$j++]);
                    $DistanceFromTheEdgeOfDoorforLeaf2 = trim((string) $row[$j++]);
                    $DistanceBetweenVp = trim((string) $row[$j++]);
                    $Leaf2VPWidth = trim((string) $row[$j++]);
                    $Leaf2VPHeight1 = trim((string) $row[$j++]);
                    $Leaf2VPHeight2 = trim((string) $row[$j++]);
                    $Leaf2VPHeight3 = trim((string) $row[$j++]);
                    $Leaf2VPHeight4 = trim((string) $row[$j++]);
                    $Leaf2VPHeight5 = trim((string) $row[$j++]);
                    $GlassIntegrity = trim((string) $row[$j++]);
                    $GlassType = trim((string) $row[$j++]);
                    $GlassThickness = trim((string) $row[$j++]);
                    $GlazingSystems = trim((string) $row[$j++]);
                    $GlazingSystemThickness = trim((string) $row[$j++]);
                    $GlazingBeads = trim((string) $row[$j++]);
                    $GlazingBeadsThickness = trim((string) $row[$j++]);
                    $glazingBeadsWidth = trim((string) $row[$j++]);
                    $glazingBeadsHeight = trim((string) $row[$j++]);
                    $glazingBeadsFixingDetail = trim((string) $row[$j++]);
                    $GlazingBeadSpecies = trim((string) $row[$j++]);
                    $FrameMaterial = trim((string) $row[$j++]);
                    $FrameType = trim((string) $row[$j++]);
                    $PlantonStopWidth = trim((string) $row[$j++]);
                    $PlantonStopHeight = trim((string) $row[$j++]);
                    $RebatedWidth = trim((string) $row[$j++]);
                    $RebatedHeight = trim((string) $row[$j++]);
                    $ScallopedWidth = trim((string) $row[$j++]);
                    $ScallopedHeight = trim((string) $row[$j++]);
                    $FrameWidth = trim((string) $row[$j++]);
                    $FrameHeight = trim((string) $row[$j++]);
                    $FrameDepth = trim((string) $row[$j++]);
                    $FrameFinish = trim((string) $row[$j++]);
                    $FrameFinishColor = trim((string) $row[$j++]);
                    $ExtLiner = trim((string) $row[$j++]);
                    $DoorFrameConstruction = trim((string) $row[$j++]);
                    $ExtLinerValue = trim((string) $row[$j++]);
                    $extLinerSize = trim((string) $row[$j++]);
                    $ExtLinerThickness = trim((string) $row[$j++]);
                    $SpecialFeatureRefs = trim((string) $row[$j++]);
                    $Overpanel = trim((string) $row[$j++]);
                    $OPWidth = trim((string) $row[$j++]);
                    $OPHeigth = trim((string) $row[$j++]);
                    $OpBeadThickness = trim((string) $row[$j++]);
                    $OpBeadHeight = trim((string) $row[$j++]);
                    $OPTransom = trim((string) $row[$j++]);
                    $TransomThickness = trim((string) $row[$j++]);
                    $opGlassIntegrity = trim((string) $row[$j++]);
                    $OPGlassType = trim((string) $row[$j++]);
                    //
                    $OPGlassThickness = trim((string) $row[$j++]);
                    $opglazingSystemsvalue = trim((string) $row[$j++]);
                    $OPGlazingSystemsThickness = trim((string) $row[$j++]);
                    //
                    $OPGlazingBeads = trim((string) $row[$j++]);
                    //
                    $OPGlazingBeadsThickness = trim((string) $row[$j++]);
                    $OPGlazingBeadsHeight = trim((string) $row[$j++]);     // confusion
                    $OPGlazingBeadsFixingDetail = trim((string) $row[$j++]);
                    //
                    $OPGlazingBeadSpecies = trim((string) $row[$j++]);
                    $SideLight1 = trim((string) $row[$j++]);
                    $SideLight1GlassType = trim((string) $row[$j++]);
                    //
                    $SL1GlassThickness = trim((string) $row[$j++]);
                    $SL1GlazingSystems = trim((string) $row[$j++]);
                    $SL1GlazingSystemsThickness = trim((string) $row[$j++]);
                    //
                    $BeadingType = trim((string) $row[$j++]);
                    //
                    $SL1GlazingBeadsThickness = trim((string) $row[$j++]);
                    $SL1GlazingBeadsWidth = trim((string) $row[$j++]);
                    $SL1GlazingBeadsFixingDetail = trim((string) $row[$j++]);
                    //
                    $SL1GlazingBeadSpecies = trim((string) $row[$j++]);
                    $SL1Width = trim((string) $row[$j++]);
                    $SL1Height = trim((string) $row[$j++]);
                    $SlBeadThickness = trim((string) $row[$j++]);
                    $SlBeadHeight = trim((string) $row[$j++]);
                    $SL1Depth = trim((string) $row[$j++]);
                    $SL1Transom = trim((string) $row[$j++]);
                    $SideLight2 = trim((string) $row[$j++]);
                    $DoYouWantToCopySameAsSL1 = trim((string) $row[$j++]);
                    $SideLight2GlassType = trim((string) $row[$j++]);
                    //
                    $SL2GlassThickness = trim((string) $row[$j++]);
                    $SL2GlazingSystems = trim((string) $row[$j++]);
                    $SL2GlazingSystemsThickness = trim((string) $row[$j++]);
                    //
                    $SideLight2BeadingType = trim((string) $row[$j++]);
                    //
                    $SL2GlazingBeadsThickness = trim((string) $row[$j++]);
                    $SL2GlazingBeadsWidth = trim((string) $row[$j++]);
                    $SL2GlazingBeadsFixingDetail = trim((string) $row[$j++]);
                    //
                    $SideLight2GlazingBeadSpecies = trim((string) $row[$j++]);
                    $SL2Width = trim((string) $row[$j++]);
                    $SL2Height = trim((string) $row[$j++]);
                    $SL2Depth = trim((string) $row[$j++]);
                    $SL2Transom = trim((string) $row[$j++]);
                    $SLtransomHeightFromTop = trim((string) $row[$j++]);
                    $SLtransomThickness = trim((string) $row[$j++]);
                    $LippingType = trim((string) $row[$j++]);
                    $LippingThickness = trim((string) $row[$j++]);
                    $LippingSpecies = trim((string) $row[$j++]);
                    $MeetingStyle = trim((string) $row[$j++]);
                    $ScallopedLippingThickness = trim((string) $row[$j++]);
                    $FlatLippingThickness = trim((string) $row[$j++]);
                    $RebatedLippingThickness = trim((string) $row[$j++]);
                    $CoreWidth1 = trim((string) $row[$j++]);
                    $CoreWidth2 = trim((string) $row[$j++]);
                    $CoreHeight = trim((string) $row[$j++]);
                    $IntumescentLeapingSealType = trim((string) $row[$j++]);
                    $IntumescentLeapingSealLocation = trim((string) $row[$j++]);
                    $IntumescentLeapingSealColor = trim((string) $row[$j++]);
                    $IntumescentLeapingSealArrangement = trim((string) $row[$j++]);
                    $intumescentSealMeetingEdges = trim((string) $row[$j++]);
                    $Accoustics = trim((string) $row[$j++]);
                    $rWdBRating = trim((string) $row[$j++]);
                    $perimeterSeal1 = trim((string) $row[$j++]);
                    $perimeterSeal2 = trim((string) $row[$j++]);
                    $thresholdSeal1 = trim((string) $row[$j++]);
                    $thresholdSeal2 = trim((string) $row[$j++]);
                    $AccousticsMeetingStiles = trim((string) $row[$j++]);
                    $Architrave = trim((string) $row[$j++]);
                    $ArchitraveMaterial = trim((string) $row[$j++]);
                    $ArchitraveType = trim((string) $row[$j++]);
                    $ArchitraveWidth = trim((string) $row[$j++]);
                    $ArchitraveThickness = trim((string) $row[$j++]);
                    $ArchitraveFinish = trim((string) $row[$j++]);
                    $ArchitraveFinishColor = trim((string) $row[$j++]);
                    $ArchitraveSetQty = trim((string) $row[$j++]);
                    $DoorsetPrice = trim((string) $row[$j++]);
                    $IronmongaryPrice = trim((string) $row[$j++]);
                    $totalPricePerDoorSet = trim((string) $row[$j++]);

                    $Checkingfirerating = Option::where(['OptionSlug' => 'fire_rating', 'OptionKey' => $FireRating])->count();

                    if ($Checkingfirerating == 0) {
                        $countFR = 0;
                    }

                $configurationDoor = configurationDoor($configurableitems);
                    if ($DoorLeafFacing !== '' && $DoorLeafFacing !== '0') {
                        // $CheckingDLF = Option::where(['OptionSlug' => 'Door_Leaf_Facing', 'OptionValue' => $DoorLeafFacing])->count();

                    $CheckingDLF = GetOptions(['door_leaf_facing.'.$configurationDoor=> $configurableitems ,'door_leaf_facing.Status' => 1,'door_leaf_facing.doorLeafFacingValue' => $DoorLeafFacing], "join","door_leaf_facing");
                    $CheckingDLF = $CheckingDLF->count();
                        // $CheckingDLF = Option::where(['OptionSlug' => 'Door_Leaf_Facing' , 'OptionValue' => $DoorLeafFacing ])->count();
                        if ($CheckingDLF == 0) {
                            $countDFR = 0;
                        }
                    } else {
                        $CheckingDLF = 1;
                    }


                    if ($Checkingfirerating > 0 && $CheckingDLF > 0) {

                        $itemCount = Item::where(['QuotationId' => $quotationId, 'DoorType' => $DoorType])->count();

                        if ($itemCount > 0) {
                            $itemLastId = Item::where(['QuotationId' => $quotationId, 'DoorType' => $DoorType])->first();
                            $itemId = $itemLastId->itemId;
                            $itemMasterCount = Item::join('item_master', 'items.itemId', 'item_master.itemID')
                                ->where(['items.QuotationId' => $quotationId, 'item_master.doorNumber' => $doorNumber])->count();
                            if ($itemMasterCount == 0) {
                                $dd = new ItemMaster();
                                $dd->itemID = $itemId;
                                $dd->doorNumber = $doorNumber;
                                if (isset($floor)) {
                                    $dd->floor = $floor;
                                }

                                $dd->save();

                                if ($versionId > 0) {
                                    $itemMasterID = ItemMaster::orderBy('id', 'DESC')->limit(1)->first();
                                    $itemMasterTblID = $itemMasterID->id;

                                    $QVI = new QuotationVersionItems();
                                    $QVI->version_id = $versionId;
                                    $QVI->itemID = $itemId;
                                    $QVI->itemmasterID = $itemMasterTblID;
                                    $QVI->save();
                                }
                            }

                            $success = 0;
                        } else {
                            $IronmongaryPrice = 0;
                            if (!empty(floatval($IronmongeryID)) || floatval($IronmongeryID) != 0) {
                                $AI = AddIronmongery::select('discountprice')->where('id', floatval($IronmongeryID))->where('UserId', user_id())->first();
                                $IronmongaryPrice = empty($AI) ? 0 : $AI->discountprice;
                            }

                            if($DoorDimensionId2 === '' || $DoorDimensionId2 === '0'){
                                $DoorDimensionId2 = 0;
                            }

                            if($HingeCenterCheck === '' || $HingeCenterCheck === '0'){
                                $HingeCenterCheck = 0;
                            }

                            $FrameOnOff = ($FrameOnOff == 1) ? 1 : 0;
                            $aa = new Item();
                            $aa->QuotationId = $quotationId;
                            $aa->VersionId = $versionId;
                            $aa->UserId = $UserId;
                            $aa->DoorQuantity = floatval($DoorQuantity);
                            $aa->DoorType = $DoorType;
                            $aa->FourSidedFrame = ($FourSidedFrame == 1) ? 1 : 0;
                            $aa->Dropseal = ($Dropseal == 1) ? 1 : 0;
                            $aa->FireRating = $FireRating;
                            $aa->LeafConstruction = $LeafType;
                            $aa->DoorDimensions = $DoorDimensionId;
                            $aa->DoorDimensions2 = $DoorDimensionId2 ?? 0;
                            $aa->DoorDimensionsCode = $DoorDimension;
                            $aa->AdjustmentLeafWidth1 = $LeafWidth1Adjustment;
                            $aa->AdjustmentLeafWidth2 = $LeafWidth2Adjustment;
                            $aa->AdjustmentLeafHeightNoOP = $LeafHeightAdjustment;
                            $aa->hinge1Location = $Hinge1Location;
                            $aa->hinge2Location = $Hinge2Location;
                            $aa->hinge3Location = $Hinge3Location;
                            $aa->hinge4Location = $Hinge4Location;
                            $aa->hingeCenterCheck = $HingeCenterCheck;
                            $aa->groovesNumber = $DecorativeGrovesIcon;
                            $aa->DoorsetPrice = $DoorsetPrice;
                            $aa->DoorsetPrice = $totalPricePerDoorSet;
                            $aa->DoorsetType = $DoorsetType;
                            $aa->SwingType = $SwingType;
                            $aa->LatchType = $LatchType;
                            $aa->Handing = $Handing;
                            $aa->OpensInwards = $OpensInwards;

                            $aa->IronmongerySet = $IronmongerySet;
                            $aa->IronmongeryID = floatval($IronmongeryID);

                            $aa->LeafWidth1 = floatval($LeafWidth1);
                            $aa->LeafWidth2 = floatval($LeafWidth2);
                            $aa->LeafHeight = floatval($LeafHeight);
                            $aa->LeafThickness = floatval($LeafThickness);
                            $aa->DoorLeafFacing = $DoorLeafFacing;
                            // $aa->DoorLeafFacingValue = $DoorLeafFacingValue;
                            $aa->DoorLeafFinish = $DoorLeafFinish;
                            // $aa->DoorLeafFinishColor = $DoorLeafFinishColor;
                            // $aa->SheenLevel = $SheenLevel;
                            $aa->DecorativeGroves = $DecorativeGroves;
                            // $aa->GrooveLocation = $GrooveLocation;
                            $aa->GrooveWidth = floatval($GrooveWidth);
                            $aa->GrooveDepth = floatval($GrooveDepth);
                            $aa->MaxNumberOfGroove = floatval($MaxNumberOfGroove);
                            $aa->NumberOfGroove = floatval($NumberOfGroove);
                            $aa->DecorativeGrovesLeaf2  = $DecorativeGrovesLeaf2;
                            $aa->IsSameAsDecorativeGroves1  = $IsSameAsDecorativeGroves1;
                            $aa->GroovesNumberLeaf2  = $GroovesNumberLeaf2;
                            $aa->GrooveWidthLeaf2 = floatval($GrooveWidthLeaf2);
                            $aa->GrooveDepthLeaf2 = floatval($GrooveDepthLeaf2);
                            $aa->MaxNumberOfGrooveLeaf2 = floatval($MaxNumberOfGrooveLeaf2);
                            $aa->NumberOfGrooveLeaf2 = floatval($NumberOfGrooveLeaf2);
                            // $aa->NumberOfVerticalGroove = floatval($NumberOfVerticalGroove);
                            // $aa->NumberOfHorizontalGroove = floatval($NumberOfHorizontalGroove);
                            $aa->Leaf1VisionPanel = $Leaf1VisionPanel;
                            $aa->Leaf1VisionPanelShape = $Leaf1VisionPanelShape;
                            $aa->VisionPanelQuantity = floatval($VisionPanelQuantity);
                            $aa->AreVPsEqualSizesForLeaf1 = $AreVPsEqualSizesForLeaf1;
                            $aa->DistanceFromtopOfDoor = floatval($DistanceFromtopOfDoor);
                            $aa->DistanceFromTheEdgeOfDoor = floatval($DistanceFromTheEdgeOfDoor);
                            $aa->DistanceBetweenVPs = floatval($DistanceBetweenVPs);
                            $aa->Leaf1VPWidth = floatval($Leaf1VPWidth);
                            $aa->Leaf1VPHeight1 = floatval($Leaf1VPHeight1);
                            $aa->Leaf1VPHeight2 = floatval($Leaf1VPHeight2);
                            $aa->Leaf1VPHeight3 = floatval($Leaf1VPHeight3);
                            $aa->Leaf1VPHeight4 = floatval($Leaf1VPHeight4);
                            $aa->Leaf1VPHeight5 = floatval($Leaf1VPHeight5);
                            $aa->Leaf1VPAreaSizem2 = floatval($Leaf1VPAreaSizem2);
                            $aa->Leaf2VisionPanel = $Leaf2VisionPanel;
                            $aa->sVPSameAsLeaf1 = $sVPSameAsLeaf1;
                            $aa->Leaf2VisionPanelQuantity = floatval($Leaf2VisionPanelQuantity);
                            $aa->AreVPsEqualSizesForLeaf2 = $AreVPsEqualSizesForLeaf2;
                            $aa->DistanceFromTopOfDoorForLeaf2 = floatval($DistanceFromTopOfDoorForLeaf2);
                            $aa->DistanceFromTheEdgeOfDoorforLeaf2 = floatval($DistanceFromTheEdgeOfDoorforLeaf2);
                            $aa->DistanceBetweenVp = floatval($DistanceBetweenVp);
                            $aa->Leaf2VPWidth = floatval($Leaf2VPWidth);
                            $aa->Leaf2VPHeight1 = floatval($Leaf2VPHeight1);
                            $aa->Leaf2VPHeight2 = floatval($Leaf2VPHeight2);
                            $aa->Leaf2VPHeight3 = floatval($Leaf2VPHeight3);
                            $aa->Leaf2VPHeight4 = floatval($Leaf2VPHeight4);
                            $aa->Leaf2VPHeight5 = floatval($Leaf2VPHeight5);
                            $aa->GlassIntegrity = $GlassIntegrity;
                            $aa->GlassType = $GlassType;
                            $aa->GlassThickness = floatval($GlassThickness);
                            $aa->GlazingSystems = $GlazingSystems;
                            $aa->GlazingSystemThickness = floatval($GlazingSystemThickness);
                            $aa->GlazingBeads = $GlazingBeads;
                            $aa->GlazingBeadsThickness = floatval($GlazingBeadsThickness);
                            $aa->glazingBeadsWidth = floatval($glazingBeadsWidth);
                            $aa->glazingBeadsHeight = floatval($glazingBeadsHeight);
                            $aa->glazingBeadsFixingDetail = $glazingBeadsFixingDetail;
                            $aa->GlazingBeadSpecies = lippingSpeciesId($GlazingBeadSpecies);
                            $aa->SOHeight = floatval($SOHeight);
                            $aa->SOWidth = floatval($SOWidth);
                            $aa->SOWallThick = floatval($SOWallThick);
                            if ($FrameOnOff == 0) {
                                $aa->Tollerance = floatval($Tollerance);
                                $aa->Undercut = floatval($Undercut);
                                $aa->FloorFinish = floatval($FloorFinish);
                                $aa->GAP = floatval($GAP);
                                $aa->FrameThickness = floatval($FrameThickness);

                                $aa->FrameMaterial = lippingSpeciesId($FrameMaterial);
                                $aa->FrameType = $FrameType;
                                $aa->PlantonStopWidth = floatval($PlantonStopWidth);
                                $aa->PlantonStopHeight = floatval($PlantonStopHeight);
                                $aa->RebatedWidth = floatval($RebatedWidth);
                                $aa->RebatedHeight = floatval($RebatedHeight);
                                $aa->ScallopedWidth = floatval($ScallopedWidth);
                                $aa->ScallopedHeight = floatval($ScallopedHeight);
                                $aa->FrameWidth = floatval($FrameWidth);
                                $aa->FrameHeight = floatval($FrameHeight);
                                $aa->FrameDepth = floatval($FrameDepth);
                                $aa->FrameFinish = $FrameFinish;
                                $aa->FrameFinishColor = $FrameFinishColor;
                                $aa->ExtLiner = $ExtLiner;
                                $aa->DoorFrameConstruction = $DoorFrameConstruction;
                                $aa->ExtLinerValue = $ExtLinerValue;
                                $aa->extLinerSize = floatval($extLinerSize);
                                $aa->ExtLinerThickness = floatval($ExtLinerThickness);
                                $aa->SpecialFeatureRefs = $SpecialFeatureRefs;
                                $aa->Overpanel = $Overpanel;
                                $aa->OPWidth = floatval($OPWidth);
                                $aa->OPHeigth = floatval($OPHeigth);
                                $aa->OpBeadThickness = floatval($OpBeadThickness);
                                $aa->OpBeadHeight = floatval($OpBeadHeight);
                                $aa->OPTransom = floatval($OPTransom);
                                $aa->TransomThickness = $TransomThickness;
                                $aa->opGlassIntegrity = $opGlassIntegrity;
                                $aa->OPGlassType = $OPGlassType;
                                //
                                $aa->OPGlassThickness = floatval($OPGlassThickness);
                                $aa->OPGlazingSystems = $opglazingSystemsvalue;
                                $aa->OPGlazingSystemsThickness = floatval($OPGlazingSystemsThickness);
                                //
                                $aa->OPGlazingBeads = $OPGlazingBeads;
                                //
                                $aa->OPGlazingBeadsThickness = floatval($OPGlazingBeadsThickness);
                                $aa->OPGlazingBeadsHeight = floatval($OPGlazingBeadsHeight);     // confusion
                                $aa->OPGlazingBeadsFixingDetail = $OPGlazingBeadsFixingDetail;
                                //
                                $aa->OPGlazingBeadSpecies = lippingSpeciesId($OPGlazingBeadSpecies);
                                $aa->SideLight1 = $SideLight1;
                                $aa->SideLight1GlassType = $SideLight1GlassType;
                                //
                                $aa->SideLight1GlassThickness = floatval($SL1GlassThickness);
                                $aa->SideLight1GlazingSystems = $SL1GlazingSystems;
                                $aa->SideLight1GlazingSystemsThickness = floatval($SL1GlazingSystemsThickness);
                                //
                                $aa->BeadingType = $BeadingType;
                                //
                                $aa->SideLight1GlazingBeadsThickness = floatval($SL1GlazingBeadsThickness);
                                $aa->SideLight1GlazingBeadsWidth = floatval($SL1GlazingBeadsWidth);
                                $aa->SideLight1GlazingBeadsFixingDetail = $SL1GlazingBeadsFixingDetail;
                                //
                                $aa->SL1GlazingBeadSpecies = lippingSpeciesId($SL1GlazingBeadSpecies);
                                $aa->SL1Width = floatval($SL1Width);
                                $aa->SL1Height = floatval($SL1Height);
                                $aa->SlBeadThickness = floatval($SlBeadThickness);
                                $aa->SlBeadHeight = floatval($SlBeadHeight);
                                $aa->SL1Depth = floatval($SL1Depth);
                                $aa->SL1Transom = floatval($SL1Transom);
                                $aa->SideLight2 = $SideLight2;
                                $aa->DoYouWantToCopySameAsSL1 = $DoYouWantToCopySameAsSL1;
                                $aa->SideLight2GlassType = $SideLight2GlassType;
                                 //
                                 $aa->SideLight2GlassThickness = floatval($SL2GlassThickness);
                                 $aa->SideLight2GlazingSystems = $SL2GlazingSystems;
                                 $aa->SideLight2GlazingSystemsThickness = floatval($SL2GlazingSystemsThickness);
                                 //
                                $aa->SideLight2BeadingType = $SideLight2BeadingType;
                                //
                                $aa->SideLight2GlazingBeadsThickness = floatval($SL2GlazingBeadsThickness);
                                $aa->SideLight2GlazingBeadsWidth = floatval($SL2GlazingBeadsWidth);
                                $aa->SideLight2GlazingBeadsFixingDetail = $SL2GlazingBeadsFixingDetail;
                                //
                                $aa->SideLight2GlazingBeadSpecies = lippingSpeciesId($SideLight2GlazingBeadSpecies);
                                $aa->SL2Width = floatval($SL2Width);
                                $aa->SL2Height = floatval($SL2Height);
                                $aa->SL2Depth = floatval($SL2Depth);
                                $aa->SL2Transom = floatval($SL2Transom);
                                $aa->SLtransomHeightFromTop = floatval($SLtransomHeightFromTop);
                                $aa->SLtransomThickness = floatval($SLtransomThickness);
                                $aa->Architrave = $Architrave;
                                $aa->ArchitraveMaterial = lippingSpeciesId($ArchitraveMaterial);
                                $aa->ArchitraveType = $ArchitraveType;
                                $aa->ArchitraveWidth = floatval($ArchitraveWidth);
                                $aa->ArchitraveHeight = floatval($ArchitraveThickness);
                                $aa->ArchitraveFinish = $ArchitraveFinish;
                                $aa->ArchitraveFinishColor = $ArchitraveFinishColor;
                                $aa->ArchitraveSetQty = floatval($ArchitraveSetQty);
                            }

                            $aa->LippingType = $LippingType;
                            $aa->LippingThickness = floatval($LippingThickness);
                            $aa->LippingSpecies = lippingSpeciesId($LippingSpecies);
                            $aa->MeetingStyle = $MeetingStyle;
                            $aa->ScallopedLippingThickness = floatval($ScallopedLippingThickness);
                            $aa->FlatLippingThickness = floatval($FlatLippingThickness);
                            $aa->RebatedLippingThickness = floatval($RebatedLippingThickness);
                            $aa->CoreWidth1 = floatval($CoreWidth1);
                            $aa->CoreWidth2 = floatval($CoreWidth2);
                            $aa->CoreHeight = floatval($CoreHeight);
                            $aa->IntumescentLeapingSealType = $IntumescentLeapingSealType;
                            $aa->IntumescentLeapingSealLocation = $IntumescentLeapingSealLocation;
                            $aa->IntumescentLeapingSealColor = $IntumescentLeapingSealColor;
                            $aa->IntumescentLeapingSealArrangement = $IntumescentLeapingSealArrangement;
                            $aa->intumescentSealMeetingEdges = $intumescentSealMeetingEdges;
                            $aa->Accoustics = $Accoustics;
                            $aa->rWdBRating = $rWdBRating;
                            $aa->perimeterSeal1 = $perimeterSeal1;
                            $aa->perimeterSeal2 = $perimeterSeal2;
                            // $aa->thresholdSeal1 = $thresholdSeal1;
                            // $aa->thresholdSeal2 = $thresholdSeal2;
                            $aa->AccousticsMeetingStiles = $AccousticsMeetingStiles;

                            $aa->IronmongaryPrice = $IronmongaryPrice;
                            $aa->FrameOnOff = $FrameOnOff;
                            $aa->save();

                            $item = new Item();
                            $item->itemID = $aa->id;
                            $item->QuotationId = $aa->QuotationId;
                            $item->version_id = $aa->VersionId;
                            $item->UserId = Auth::user()->id;
                            $item->DoorQuantity = $aa->DoorQuantity;
                            //Main Options
                            $item->doorType = $aa->DoorType;
                            $item->Dropseal = $aa->Dropseal;
                            $item->FourSidedFrame = $aa->FourSidedFrame;
                            $item->fireRating = $aa->FireRating;
                            $item->doorsetType = $aa->DoorsetType;
                            $item->swingType = $aa->SwingType;
                            $item->latchType = $aa->LatchType;
                            $item->Handing = $aa->Handing;
                            $item->OpensInwards = $aa->OpensInwards;
                            $item->COC = $aa->COC;
                            $item->tollerance = $aa->Tollerance;
                            $item->undercut = $aa->Undercut;
                            $item->floorFinish = $aa->FloorFinish;
                            $item->gap = $aa->GAP;
                            $item->frameThickness = $aa->FrameThickness;
                            $item->ironmongerySet = $aa->IronmongerySet;
                            $item->IronmongeryID = $aa->IronmongeryID;
                            $item->LeafConstruction = $aa->LeafConstruction;
                            $item->DoorDimensions = $aa->DoorDimensions;
                            $item->DoorDimensions2 = $aa->DoorDimensions2;
                            $item->DoorDimensionsCode = $aa->DoorDimensionsCode;
                            $item->AdjustmentLeafWidth1 = $aa->AdjustmentLeafWidth1;
                            $item->AdjustmentLeafWidth2 = $aa->AdjustmentLeafWidth2;
                            $item->AdjustmentLeafHeightNoOP = $aa->AdjustmentLeafHeightNoOP;
                            $item->hinge1Location = $aa->hinge1Location;
                            $item->hinge2Location = $aa->hinge2Location;
                            $item->hinge3Location = $aa->hinge3Location;
                            $item->hinge4Location = $aa->hinge4Location;
                            $item->hingeCenterCheck = $aa->hingeCenterCheck;
                            $item->groovesNumber = $aa->groovesNumber;
                            $item->DoorsetPrice = $aa->DoorsetPrice;

                            $item->sOWidth = $aa->SOWidth;
                            $item->sOHeight = $aa->SOHeight;
                            $item->sODepth = $aa->SOWallThick;
                            $item->leafWidth1 = $aa->LeafWidth1;
                            $item->leafWidth2 = $aa->LeafWidth2;
                            $item->leafHeightNoOP = $aa->LeafHeight;
                            $item->doorThickness = $aa->LeafThickness;
                            $item->doorLeafFacing = $aa->DoorLeafFacing;
                            $item->doorLeafFacingValue = $aa->DoorLeafFacingValue;
                            $item->doorLeafFinish = $aa->DoorLeafFinish;
                            $item->doorLeafFinishColor = $aa->DoorLeafFinishColor;
                            $item->SheenLevel = $aa->SheenLevel;
                            $item->decorativeGroves = $aa->DecorativeGroves;
                            $item->grooveLocation = $aa->GrooveLocation;
                            $item->grooveWidth = $aa->GrooveWidth;
                            $item->grooveDepth = $aa->GrooveDepth;
                            $item->maxNumberOfGroove = $aa->MaxNumberOfGroove;
                            $item->numberOfGroove = $aa->NumberOfGroove;
                            $item->numberOfVerticalGroove = $aa->NumberOfVerticalGroove;
                            $item->numberOfHorizontalGroove = $aa->NumberOfHorizontalGroove;
                            $item->DecorativeGrovesLeaf2  = $aa->DecorativeGrovesLeaf2;
                            $item->IsSameAsDecorativeGroves1  = $aa->IsSameAsDecorativeGroves1;
                            $item->GroovesNumberLeaf2  = $aa->GroovesNumberLeaf2;
                            $item->GrooveWidthLeaf2 = $aa->GrooveWidthLeaf2;
                            $item->GrooveDepthLeaf2 = $aa->GrooveDepthLeaf2;
                            $item->MaxNumberOfGrooveLeaf2 = $aa->MaxNumberOfGrooveLeaf2;
                            $item->NumberOfGrooveLeaf2 = $aa->NumberOfGrooveLeaf2;

                            //Vision Panel
                            $item->leaf1VisionPanel = $aa->Leaf1VisionPanel;
                            $item->leaf1VisionPanelShape = $aa->Leaf1VisionPanelShape;
                            $item->visionPanelQuantity = $aa->VisionPanelQuantity;
                            $item->AreVPsEqualSizes = $aa->AreVPsEqualSizesForLeaf1;
                            $item->distanceFromTopOfDoor = $aa->DistanceFromtopOfDoor;
                            $item->distanceFromTheEdgeOfDoor = $aa->DistanceFromTheEdgeOfDoor;
                            $item->distanceBetweenVPs = $aa->DistanceBetweenVPs;
                            $item->vP1Width = $aa->Leaf1VPWidth;
                            $item->vP1Height1 = $aa->Leaf1VPHeight1;
                            $item->vP1Height2 = $aa->Leaf1VPHeight2;
                            $item->vP1Height3 = $aa->Leaf1VPHeight3;
                            $item->vP1Height4 = $aa->Leaf1VPHeight4;
                            $item->vP1Height5 = $aa->Leaf1VPHeight5;
                            $item->leaf1VpAreaSizeM2 = $aa->Leaf1VPAreaSizem2;
                            $item->leaf2VisionPanel = $aa->Leaf2VisionPanel;
                            $item->vpSameAsLeaf1 = $aa->sVPSameAsLeaf1;
                            $item->Leaf2VisionPanelQuantity = $aa->Leaf2VisionPanelQuantity;
                            $item->AreVPsEqualSizesForLeaf2 =  $aa->AreVPsEqualSizesForLeaf2;
                            $item->distanceFromTopOfDoorforLeaf2 = $aa->DistanceFromTopOfDoorForLeaf2;
                            $item->distanceFromTheEdgeOfDoorforLeaf2 = $aa->DistanceFromTheEdgeOfDoorforLeaf2;
                            $item->distanceBetweenVPsforLeaf2 = $aa->DistanceBetweenVp;
                            $item->vP2Width = $aa->Leaf2VPWidth;
                            $item->vP2Height1 = $aa->Leaf2VPHeight1;
                            $item->vP2Height2 = $aa->Leaf2VPHeight2;
                            $item->vP2Height3 = $aa->Leaf2VPHeight3;
                            $item->vP2Height4 = $aa->Leaf2VPHeight4;
                            $item->vP2Height5 = $aa->Leaf2VPHeight5;
                            $item->lazingIntegrityOrInsulationIntegrity = $aa->GlassIntegrity;
                            $item->glassType = $aa->GlassType;
                            $item->glassThickness = $aa->GlassThickness;
                            $item->glazingSystems = $aa->GlazingSystems;
                            $item->glazingSystemsThickness = $aa->GlazingSystemThickness;
                            $item->glazingBeads = $aa->GlazingBeads;
                            $item->glazingBeadsThickness = $aa->GlazingBeadsThickness;
                            $item->glazingBeadsWidth = $aa->glazingBeadsWidth;
                            $item->glazingBeadsHeight = $aa->glazingBeadsHeight;
                            $item->glazingBeadsFixingDetail = $aa->glazingBeadsFixingDetail;
                            $item->glazingBeadSpecies = $aa->GlazingBeadSpecies;

                            //Frame
                            $item->frameMaterial = $aa->FrameMaterial;
                            $item->frameType = $aa->FrameType;
                            // streboard
                            $item->plantonStopWidth = $aa->PlantonStopWidth;
                            $item->plantonStopHeight = $aa->PlantonStopHeight;
                            // streboard
                            $item->rebatedWidth = $aa->RebatedWidth;
                            $item->rebatedHeight = $aa->RebatedHeight;
                            $item->ScallopedWidth = $aa->ScallopedWidth;
                            $item->ScallopedHeight = $aa->ScallopedHeight;
                            //halspan
                            // $item->standardWidth = $aa->QuotationId;
                            // $item->standardHeight = $aa->QuotationId;
                            $item->frameWidth = $aa->FrameWidth;
                            $item->frameHeight = $aa->FrameHeight;
                            $item->frameDepth = $aa->FrameDepth;
                            $item->frameFinish = $aa->FrameFinish;
                            $item->framefinishColor = $aa->FrameFinishColor;
                            $item->extLiner = $aa->ExtLiner;
                            $item->frameCostuction = $aa->DoorFrameConstruction;
                            $item->extLinerValue = $aa->ExtLinerValue;
                            $item->extLinerSize = $aa->extLinerSize;
                            $item->extLinerThickness = $aa->ExtLinerThickness;
                            $item->ironmongerySet = $aa->IronmongerySet;
                            $item->IronmongeryID = $aa->IronmongeryID;
                            $item->specialFeatureRefs = $aa->SpecialFeatureRefs;

                            //Over Panel Section
                            $item->overpanel = $aa->Overpanel;
                            $item->oPWidth = $aa->OPWidth;
                            $item->oPHeigth = $aa->OPHeigth;
                            $item->opTransom = $aa->OPTransom;
                            $item->transomThickness = $aa->TransomThickness;
                            $item->opGlassIntegrity = $aa->opGlassIntegrity;
                            $item->opGlassType = $aa->OPGlassType;
                            //
                            $item->opglassThickness = $aa->OPGlassThickness;
                            $item->opglazingSystems = $aa->OPGlazingSystems;
                            $item->opglazingSystemsThickness =$aa->OPGlazingSystemsThickness;
                            //
                            $item->opGlazingBeads = $aa->OPGlazingBeads;
                            //
                            $item->opglazingBeadsThickness = $aa->OPGlazingBeadsThickness;
                            $item->opglazingBeadsHeight = $aa->OPGlazingBeadsHeight;     // confusion
                            $item->opglazingBeadsFixingDetail = $aa->OPGlazingBeadsFixingDetail;
                            //
                            $item->opGlazingBeadSpecies = $aa->OPGlazingBeadSpecies;
                            $item->OpBeadThickness = $aa->OpBeadThickness;
                            $item->OpBeadHeight = $aa->OpBeadHeight;

                            //Side Light
                            $item->sideLight1 = $aa->SideLight1;
                            $item->sideLight1GlassType = $aa->SideLight1GlassType;
                            //
                            $item->sideLight1GlassThickness = $aa->SideLight1GlassThickness;
                            $item->sideLight1GlazingSystems = $aa->SideLight1GlazingSystems;
                            $item->sideLight1GlazingSystemsThickness = $aa->SideLight1GlazingSystemsThickness;
                            //
                            $item->SideLight1BeadingType = $aa->BeadingType;
                            //
                            $item->sideLight1GlazingBeadsThickness = $aa->SideLight1GlazingBeadsThickness;
                            $item->sideLight1GlazingBeadsWidth = $aa->SideLight1GlazingBeadsWidth;
                            $item->sideLight1GlazingBeadsFixingDetail = $aa->SideLight1GlazingBeadsFixingDetail;
                            //
                            $item->SideLight1GlazingBeadSpecies = $aa->SL1GlazingBeadSpecies;
                            $item->SL1Width = $aa->SL1Width;
                            $item->SL1Height = $aa->SL1Height;
                            $item->SL1Depth = $aa->SL1Depth;
                            $item->SL1Transom = $aa->SL1Transom;
                            $item->sideLight2 = $aa->SideLight2;
                            $item->copyOfSideLite1 = $aa->DoYouWantToCopySameAsSL1;
                            $item->SideLight2GlassType = $aa->SideLight2GlassType;
                            //
                            $item->sideLight2GlassThickness = $aa->SideLight2GlassThickness;
                            $item->sideLight2GlazingSystems = $aa->SideLight2GlazingSystems;
                            $item->sideLight2GlazingSystemsThickness = $aa->SideLight2GlazingSystemsThickness;
                            //
                            $item->SideLight2BeadingType = $aa->SideLight2BeadingType;
                            //
                            $item->sideLight2GlazingBeadsThickness = $aa->SideLight2GlazingBeadsThickness;
                            $item->sideLight2GlazingBeadsWidth = $aa->SideLight2GlazingBeadsWidth;
                            $item->sideLight2GlazingBeadsFixingDetail = $aa->SideLight2GlazingBeadsFixingDetail;
                            //
                            $item->SideLight2GlazingBeadSpecies = $aa->SideLight2GlazingBeadSpecies;
                            $item->SL2Width = $aa->SL2Width;
                            $item->SL2Height = $aa->SL2Height;
                            $item->SL2Depth = $aa->SL2Depth;
                            $item->SL2Transom = $aa->SL2Transom;
                            $item->SLtransomHeightFromTop = $aa->SLtransomHeightFromTop;
                            $item->SLtransomThickness = $aa->SLtransomThickness;
                            $item->SlBeadThickness = $aa->SlBeadThickness;
                            $item->SlBeadHeight = $aa->SlBeadHeight;

                            //Lipping And Intumescent
                            $item->lippingType = $aa->LippingType;
                            $item->lippingThickness = $aa->LippingThickness;
                            $item->lippingSpecies = $aa->LippingSpecies;
                            $item->meetingStyle = $aa->MeetingStyle;
                            $item->scallopedLippingThickness = $aa->ScallopedLippingThickness;
                            $item->flatLippingThickness = $aa->FlatLippingThickness;
                            $item->rebatedLippingThickness = $aa->RebatedLippingThickness;
                            $item->coreWidth1 = $aa->CoreWidth1;
                            $item->coreWidth2 = $aa->CoreWidth2;
                            $item->coreHeight = $aa->CoreHeight;
                            $item->intumescentSealType = $aa->IntumescentLeapingSealType;
                            $item->intumescentSealLocation = $aa->IntumescentLeapingSealLocation;
                            $item->intumescentSealColor = $aa->IntumescentLeapingSealColor;
                            $item->intumescentSealArrangement = $aa->IntumescentLeapingSealArrangement;
                            $item->intumescentSealMeetingEdges = $aa->intumescentSealMeetingEdges;

                            //Accoustics
                            $item->accoustics = $aa->Accoustics;
                            $item->rWdBRating = $aa->rWdBRating;
                            $item->perimeterSeal1 = $aa->perimeterSeal1;
                            $item->perimeterSeal2 = $aa->perimeterSeal2;
                            // $item->thresholdSeal1 = $aa->thresholdSeal1;
                            // $item->thresholdSeal2 = $aa->thresholdSeal2;
                            $item->accousticsmeetingStiles = $aa->AccousticsMeetingStiles;

                            //Architrave
                            $item->Architrave = $aa->Architrave;
                            $item->architraveMaterial = $aa->ArchitraveMaterial;
                            $item->architraveType = $aa->ArchitraveType;
                            $item->architraveWidth = $aa->ArchitraveWidth;
                            $item->architraveHeight = $aa->ArchitraveHeight;
                            $item->architraveFinish = $aa->ArchitraveFinish;
                            $item->architraveFinishcolor = $aa->ArchitraveFinishColor;
                            $item->architraveSetQty = $aa->ArchitraveSetQty;
                            $item->issingleconfiguration = $configurableitems;

                            $item->IronmongaryPrice = $IronmongaryPrice;

                            $itemLastId = Item::orderBy('itemId', 'DESC')->limit(1)->first();
                            $itemId = $itemLastId->itemId;
                            // $itemMasterCount2 = ItemMaster::where(['itemID' => $itemId, 'doorNumber' => $doorNumber])->count();
                            $itemMasterCount2 = Item::join('item_master', 'items.itemId', 'item_master.itemID')
                                ->where(['items.QuotationId' => $quotationId, 'item_master.doorNumber' => $doorNumber, 'item_master.itemID' => $itemId])->count();
                            if ($itemMasterCount2 == 0) {
                                $dd = new ItemMaster();
                                $dd->itemID = $itemId;
                                $dd->doorNumber = $doorNumber;
                                if (isset($floor)) {
                                    $dd->floor = $floor;
                                }

                                $dd->save();

                                if ($versionId > 0) {
                                    $itemMasterID = ItemMaster::orderBy('id', 'DESC')->limit(1)->first();
                                    $itemMasterTblID = $itemMasterID->id;

                                    $QVI = new QuotationVersionItems();
                                    $QVI->version_id = $versionId;
                                    $QVI->itemID = $itemId;
                                    $QVI->itemmasterID = $itemMasterTblID;
                                    $QVI->save();
                                }
                            }

                            match ($configurableitems) {
                                // VICAIMA DOOR
                                '4' => BomCalculationVicaima($item),
                                // Seadec DOOR
                                '5' => BomCalculationSeadec($item),
                                // Deanta DOOR
                                '6' => BomCalculationDeanta($item),
                                // Halspan DOOR
                                '2' => HalspanBomCalculation($item),
                                // Flamebreak DOOR
                                '7' => FlamebreakBomCalculation($request),
                                // Stredor DOOR
                                '8' => StredorBomCalculation($request),
                                // STAREBOARD AND ALL
                                default => BomCalculation($item),
                            };

                            $BOMCalculation = BOMCalculation::select('*')->where('QuotationId', $aa->QuotationId)->where('DoorType', $DoorType)->get();
                            $Item = Item::where(['QuotationId' => $aa->QuotationId, 'DoorType' => $DoorType])->get()->first();

                            $Itemcount = Item::where(['QuotationId' => $aa->QuotationId])->get()->count();

                            if (!empty($BOMCalculation)) {
                                foreach ($BOMCalculation as $value) {
                                    $BOM = BOMCalculation::find($value->id);
                                    if (!empty($BOM)) {
                                        $BOM->itemId = $Item->itemId;
                                        $BOM->save();
                                    }
                                }
                            }

                            $BOMCalculation = BOMCalculation::select('*')->where('QuotationId', $aa->QuotationId)->where('DoorType', $DoorType)->where('itemId', $Item->itemId)->get();
                            $GTSellPrice = 0;
                            $GTSellPriceTotal = 0;
                            if (!empty($BOMCalculation)) {
                                foreach ($BOMCalculation as $value1) {
                                    if($value1->Category != 'Ironmongery&MachiningCosts'){
                                        $GTSellPrice += $value1->GTSellPrice;
                                    }
                                }

                                $ItemMaster = ItemMaster::where('itemID', $Item->itemId)->get()->count();
                                $GTSellPriceTotal = round(($GTSellPrice / $ItemMaster), 2);
                            }

                            $Item = Item::where('itemId', $Item->itemId)->update([
                                'DoorsetPrice' => $GTSellPriceTotal
                            ]);

                            $success = 0;
                        }

                        // BOMQuatityOfDoorUpdate($itemId, $quotationId);
                    }
                }

                $error = null;
                $error2 = null;
                $error3 = null;
                if ($countFR === 0) {

                    $error = '<p style="color:red">Some Fire Rating is not in correct format</p>';
                }

                if ($countDFR === 0) {
                    $error2 = '<p style="color:red">Some VisionPanel is not in correct formate.</p>';
                }

                if ($success === 0) {
                    $error3 = '<p>Excel file is imported successfully.</p>';
                }

                // if(!empty($countFR) && !empty($countDFR) && !empty($success)){
                //     return redirect()->back()->with('success',$error3.$error.$error2);
                // } else if(empty($success)){
                //     return redirect()->back()->with('error',$error.$error2);
                // } else {
                //     return redirect()->back()->with('success',$error3.$error.$error2);
                // }
                return redirect()->back()->with('success', $error3 . $error . $error2);
            }
        } else {
            return redirect()->back()->with('error', 'Quotation not found!');
        }

        return null;
    }

    public function createNewVersion(Request $request): void
    {
        if (empty($request->quotationId)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.',
                'error' => 'Quotation ID is required.'
            ]);
            exit;
        }

        if (empty($request->version)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.',
                "error" => "Version is required."
            ]);
            exit;
        }

        $quotationId = $request->quotationId;
        $versionID = $request->version;
        $MaxVersion = QuotationVersion::where('quotation_id', $quotationId)->max('version');
        $QuotationVersionItems = QuotationVersionItems::where('version_id', $versionID)->get();
        if ($QuotationVersionItems !== null) {
            $QuotationVersion = new QuotationVersion();
            $QuotationVersion->quotation_id = $quotationId;
            $QuotationVersion->version = ($MaxVersion + 1);
            if ($QuotationVersion->save()) {

                $QuotationItemsversion = QuotationVersionItems::select('quotation_version_items.*')->join('quotation_versions', 'quotation_versions.id', '=', 'quotation_version_items.version_id')->where('version_id', $versionID)->where('quotation_id', $quotationId)->get();
                $data = [];
                if ($QuotationItemsversion != null) {
                    foreach ($QuotationItemsversion as $val) {
                        $data[] = $val->itemID;
                    }
                }

                $OldItemsInformation = Item::select('items.*')->where('items.QuotationId', $quotationId)->whereIn('itemId', $data)->get();

                if ($OldItemsInformation != null) {
                    foreach ($OldItemsInformation as $items) {
                        if ($QuotationVersionItems[0]->version_id == $versionID) {
                            //replicate item data on creating version
                            $qvLastID = QuotationVersion::orderBy('id', 'desc')->limit(1)->first();
                            $NewItemInformation = $items->replicate();
                            $NewItemInformation->itemId = Items::max('itemId') + 1;
                            $NewItemInformation->VersionId = $qvLastID->id;
                            $NewItemInformation->save();

                            // inser into `BOMCalculation`
                            $version_id = QuotationVersion::where('quotation_id', $quotationId)->where('id', $versionID)->value('version');
                            $BOMCalculation = BOMCalculation::where('QuotationId', $quotationId)->where('VersionId', $version_id)->where('itemId', $items->itemId)->get();

                            if (!empty($BOMCalculation)) {
                                foreach ($BOMCalculation as $value) {
                                    $BOM = $value->replicate();
                                    $BOM->id = BOMCalculation::max('id') + 1;
                                    $BOM->VersionId = $QuotationVersion->version;
                                    $BOM->itemId = $NewItemInformation->itemId;
                                    $BOM->save();
                                }
                            }

                            $OldItemMasterInformation = ItemMaster::select('item_master.*')->join('items', 'item_master.itemID', '=', 'items.itemId')->join('quotation_version_items', 'quotation_version_items.itemmasterID', 'item_master.id')->where('item_master.itemID', $items->itemId)->where('quotation_version_items.version_id', $versionID)->get();
                            if ($OldItemMasterInformation != null) {
                                foreach ($OldItemMasterInformation as $items) {
                                    //replicate item master data on creating version
                                    $NewItemMasterInformation = $items->replicate();
                                    $NewItemMasterInformation->id = ItemMaster::max('id') + 1;
                                    $NewItemMasterInformation->itemID = $NewItemInformation->itemId;
                                    $NewItemMasterInformation->save();

                                    $qvLastID = QuotationVersion::orderBy('id', 'desc')->limit(1)->first();

                                    // inser into `quotation_version_items`
                                    $QV = new QuotationVersionItems();
                                    $QV->version_id = $qvLastID->id;
                                    $QV->itemID = $NewItemInformation->itemId;
                                    $QV->itemmasterID = $NewItemMasterInformation->id;
                                    $QV->save();
                                }
                            }
                        }
                    }

                    $nonConfigData = nonConfigurableItem($quotationId, $versionID, CompanyUsers(), true);
                    if (!empty($nonConfigData)) {
                        foreach ($nonConfigData as $value) {
                            $nonConfig = $value->replicate();
                            $nonConfig->id = NonConfigurableItemStore::max('id') + 1;
                            $nonConfig->versionId = $qvLastID->id;
                            $nonConfig->userId = user_id();
                            $nonConfig->save();
                        }
                    }

                    $oldScreenItem = SideScreenItem::where(['QuotationId' => $quotationId,'VersionId' => $versionID])->get();
                    if ($oldScreenItem != null) {
                        $qvLastID = QuotationVersion::orderBy('id', 'desc')->limit(1)->first();
                        foreach ($oldScreenItem as $screen) {
                            $NewScreenInformation = $screen->replicate();
                            $NewScreenInformation->id = SideScreenItem::max('id') + 1;
                            $NewScreenInformation->VersionId = $qvLastID->id;
                            $NewScreenInformation->save();

                            // inser into `BOMCalculation`
                            $version_id = QuotationVersion::where('quotation_id', $quotationId)->where('id', $versionID)->value('version');
                            $ScreenBOMCalculation = ScreenBOMCalculation::where('QuotationId', $quotationId)->where('VersionId', $version_id)->where('ScreenId', $screen->id)->get();

                            if (!empty($ScreenBOMCalculation)) {
                                foreach ($ScreenBOMCalculation as $value) {
                                    $ScreenBOM = $value->replicate();
                                    $ScreenBOM->id = ScreenBOMCalculation::max('id') + 1;
                                    $ScreenBOM->VersionId = $QuotationVersion->version;
                                    $ScreenBOM->ScreenId = $NewScreenInformation->id;
                                    $ScreenBOM->save();
                                }
                            }

                            $OldItemMasterScreen = SideScreenItemMaster::select('side_screen_item_master.*')->join('side_screen_items', 'side_screen_item_master.ScreenID', '=', 'side_screen_items.id')->where('side_screen_item_master.ScreenID', $screen->id)->get();
                            if ($OldItemMasterScreen != null) {
                                foreach ($OldItemMasterScreen as $items) {
                                    //replicate item master data on creating version
                                    $NewItemMasterScreen = $items->replicate();
                                    $NewItemMasterScreen->id = SideScreenItemMaster::max('id') + 1;
                                    $NewItemMasterScreen->ScreenId = $NewScreenInformation->id;
                                    $NewItemMasterScreen->save();
                                }
                            }
                        }
                    }

                }

                $url = url('quotation/generate') . '/' . $quotationId . '/' . $QuotationVersion->id;

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Quotation generated successfully',
                    'newVersion' => ($MaxVersion + 1),
                    'url' => $url
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Something went wrong. Please try again later.',
                    "error" => "Quotation version not created."
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.',
                "error" => "Quotation items not found."
            ]);
        }
    }

    public function versionStore(Request $request): void
    {
        $quotationId = $request->quotationId;
        $doors = $request->doors;
        // $items = array_unique($request->items);
        $items = $request->items;

        $MaxVersion = QuotationVersion::where('quotation_id', $quotationId)->max('version');
        $versionId = $MaxVersion != null ? $MaxVersion + 1 : 1;

        if (!empty($quotationId) && !empty($versionId)) {
            $QuotationVersion = new QuotationVersion();
            $QuotationVersion->quotation_id = $quotationId;
            $QuotationVersion->version = $versionId;

            if ($QuotationVersion->save()) {
                // if($versionId == 1){
                //     $lastQV = QuotationVersion::orderBy('id','desc')->limit(1)->first();
                //     $QV_ID = $lastQV->id;
                //     ItemMaster::where(['quotationID' => $quotationId])->update(['versionId' => $QV_ID]);
                // }
                $doorid = 0;
                if (!empty($items) && count($items) > 0) {
                    foreach ($items as $id) {

                        $QV = new QuotationVersionItems();
                        $QV->version_id = $QuotationVersion->id;
                        $QV->itemID = $id;
                        $QV->itemmasterID = $doors[$doorid];
                        $QV->save();

                        $doorid++;

                        Item::where('QuotationId', $quotationId)->update(['VersionId' => $QuotationVersion->id]);

                    }


                    $nonConfig = NonConfigurableItems::join('non_configurable_item_store', 'non_configurable_item_store.nonConfigurableId', 'non_configurable_items.id')->where(['non_configurable_item_store.userId' => Auth::user()->id, 'non_configurable_item_store.quotationId' => $quotationId])->first();
                    if (!empty($nonConfig)) {
                        NonConfigurableItemStore::where('quotationId', $quotationId)->update(['versionId' => $QuotationVersion->id]);
                    }

                    Item::where('QuotationId', $quotationId)->update(['VersionId' => $QuotationVersion->id]);
                    $qvdata = QuotationVersion::orderBy('id', 'desc')->limit(1)->first();
                    $tblQVID = $qvdata->id;
                    $BOMCalculation = BOMCalculation::select('*')->where('QuotationId', $quotationId)->where('VersionId', '0')->get();
                    if (!empty($BOMCalculation)) {
                        foreach ($BOMCalculation as $value) {
                            $BOM = BOMCalculation::find($value->id);
                            if (!empty($BOM)) {
                                $BOM->VersionId = 1;
                                $BOM->save();
                            }
                        }
                    }
                }

                $SideScreen = SideScreenItem::where('QuotationId', $quotationId)->get();
                if (!empty($SideScreen) && count($SideScreen) > 0) {
                    SideScreenItem::where('QuotationId', $quotationId)->update(['VersionId' => $QuotationVersion->id]);
                    $ScreenBOMCalculation = ScreenBOMCalculation::select('*')->where('QuotationId', $quotationId)->where('VersionId', '0')->get();
                    if (!empty($ScreenBOMCalculation)) {
                        foreach ($ScreenBOMCalculation as $value1) {
                            $ScreenBOM = ScreenBOMCalculation::find($value1->id);
                            if (!empty($ScreenBOM)) {
                                $ScreenBOM->VersionId = 1;
                                $ScreenBOM->save();
                            }
                        }
                    }
                }

                $url = url('quotation/generate') . '/' . $quotationId . '/' . $QuotationVersion->id;

                echo json_encode([
                    'status' => 'success',
                    'message' => 'Quotation generated successfully',
                    'newVersion' => $versionId,
                    'url' => $url
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Something went wrong. Please try again later.',
                    'error' => 'Quotation version not created.'
                ]);
            }
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.',
                'error' => "You haven't selected any item."
            ]);
        }
    }


    public function generateQuotation(string $Id, string $vId, $pId = null, $cId = null)
    {
        markAsRead($Id, 'quote');

        if ($Id == 0 && $vId == 0) {
            $qidFromhelper = GenerateQuotationFirstTime($pId, $cId);
            return redirect()->route('quotation/generate/', [$qidFromhelper, 0]);
        } else {
            $Quotation = Quotation::where('id', $Id)->first();
            $QuotationContactInformation = QuotationContactInformation::where('QuotationId', $Id)->first();
            $QuotationShipToInformation = QuotationShipToInformation::where('QuotationId', $Id)->first();
        }



        if ($Quotation === null) {
            return abort(404);
        }

        // NEED TO SHOW ALL PROJECT ON "EDIT HEADER DETAILS => PROJECT SELECT BOX" (09-12-2023)
        $projectUserId = [];
        switch (auth()->user()->UserType) {
            case 2:
                $projectUserId = User::where('CreatedBy', auth()->user()->id)->pluck('id')->toArray();
                $projectUserId[] = $Quotation->UserId;
                $projectUserId[] = auth()->user()->id;
                break;
            case 3:
                $projectUserId = User::where('CreatedBy', auth()->user()->CreatedBy)->pluck('id')->toArray();
                $projectUserId[] = $Quotation->UserId;
                $projectUserId[] = intval(auth()->user()->CreatedBy);
                break;
            default:
                $projectUserId = [$Quotation->UserId, auth()->user()->CreatedBy];
                break;
        }

        // $ProjectTable = '<option value="">Select Project</option>';

        $ProjectsAddress = '';
        if ($Quotation->MainContractorId != '') {
            $dd = Project::where(['UserId' => $Quotation->UserId, 'MainContractorId' => $Quotation->MainContractorId])->count();
            if ($dd > 0) {
                if ($Quotation->ProjectId != '') {
                    // it show single project it come from project list when create New Quotation press button `New Quotation`
                    $Projects = Project::where(['UserId' => $Quotation->UserId, 'id' => $Quotation->ProjectId, 'Status' => 1])->get();
                } else {
                    // when you directly create quotation it show multiple project
                    $Projects = Project::where(['UserId' => $Quotation->UserId, 'MainContractorId' => $Quotation->MainContractorId, 'Status' => 1])->get();
                }
            } else {
                // $Projects = Project::where(['UserId' => $Quotation->UserId , 'Status' => 1])->get();
                $Projects = Project::where(['Status' => 1])->whereIn('UserId', $projectUserId)->get();
            }

            $ProjectsAddress = Project::join('quotation', 'quotation.ProjectId', 'project.id')->where(['quotation.CompanyId' => $Quotation->CompanyId, 'quotation.ProjectId' => $Quotation->ProjectId])->first();
        } else {
            // $Projects = Project::where(['UserId' => $Quotation->UserId , 'Status' => 1])->get();
            $Projects = Project::where(['Status' => 1])->whereIn('UserId', $projectUserId)->get();
        }

        // if($Projects !== null){
        //     $Projects = $Projects->toArray();
        //     foreach($Projects as $Project){
        //         $ProjectTable .= '<option value="'.$Project['id'].'">'.$Project['ProjectName'].'</option>';
        //     }
        // }

        if (!empty($Quotation)) {

            if ($vId > 0) {
                $userIds = CompanyUsers();
                $margin = BOMSetting::wherein('UserId',$userIds)->value('margin_for_material');
                $Schedule = Item::join('quotation_version_items', 'items.itemId', 'quotation_version_items.itemID')
                    ->join('item_master', 'quotation_version_items.itemmasterID', 'item_master.id')
                    ->where('quotation_version_items.version_id', $vId)
                    ->select('items.FireRating', 'items.SvgImage', 'items.DoorType', 'items.DoorQuantity', 'items.DoorsetType', 'items.SOWidth', 'items.SOHeight', 'items.SOWallThick', 'items.AdjustPrice', 'items.DoorsetPrice', 'items.IronmongaryPrice', 'items.itemId', 'item_master.id', 'item_master.doorNumber', 'item_master.floor', 'item_master.id', 'item_master.id', 'quotation_version_items.version_id')
                    ->get();

                // Total Door Price
                $TotalDoorPrice = Item::join('quotation_version_items', 'items.itemId', 'quotation_version_items.itemID')
                    ->join('item_master', 'quotation_version_items.itemmasterID', 'item_master.id')
                    ->where(['quotation_version_items.version_id' => $vId, 'items.VersionId' => $vId, 'items.QuotationId' => $Id]);

                $TotalExactDoorPrice = $TotalDoorPrice->sum('items.DoorsetPrice');
                $TotalIronmongeryPrice = $TotalDoorPrice->sum('items.IronmongaryPrice');

                $SideScreenData = SideScreenItem::join('side_screen_item_master', 'side_screen_items.id', 'side_screen_item_master.ScreenId')->where(['side_screen_items.QuotationId' => $Id,'side_screen_items.VersionId' => $vId])
                    ->select('side_screen_items.FireRating','side_screen_items.VersionId', 'side_screen_items.ScreenType' ,'side_screen_items.SOWidth', 'side_screen_items.SOHeight', 'side_screen_items.SODepth','side_screen_items.GlazingType', 'side_screen_items.ScreenPrice', 'side_screen_items.id', 'side_screen_item_master.screenNumber', 'side_screen_item_master.floor', 'side_screen_item_master.id as screenMasterid');
            } else {
                $Schedule = Item::join('item_master', 'items.itemId', 'item_master.itemID')
                    ->select('items.FireRating', 'items.SvgImage', 'items.DoorType', 'items.DoorQuantity', 'items.DoorsetType', 'items.SOWidth', 'items.SOHeight', 'items.SOWallThick', 'items.AdjustPrice', 'items.DoorsetPrice', 'items.IronmongaryPrice', 'items.itemId', 'item_master.id', 'item_master.doorNumber', 'item_master.floor', 'item_master.id', 'item_master.id')
                    ->where(['items.QuotationId' => $Id])->get();

                // Total Door Price
                $TotalDoorPrice = Item::join('item_master', 'items.itemId', 'item_master.itemID')
                    ->where(['items.QuotationId' => $Id]);
                    // dd( $TotalDoorPrice->get());
                $TotalExactDoorPrice = $TotalDoorPrice->sum('items.DoorsetPrice');
                // $TotalDoorSetPrice = $TotalDoorPrice->sum('items.DoorsetPrice');
                $TotalIronmongeryPrice = $TotalDoorPrice->sum('items.IronmongaryPrice');

                $SideScreenData = SideScreenItem::join('side_screen_item_master', 'side_screen_items.id', 'side_screen_item_master.ScreenId')->where(['side_screen_items.QuotationId' => $Id])
                ->select('side_screen_items.FireRating','side_screen_items.VersionId', 'side_screen_items.ScreenType' ,'side_screen_items.SOWidth', 'side_screen_items.SOHeight', 'side_screen_items.SODepth','side_screen_items.GlazingType', 'side_screen_items.ScreenPrice', 'side_screen_items.id', 'side_screen_item_master.screenNumber', 'side_screen_item_master.floor', 'side_screen_item_master.id as screenMasterid');
            }

            $TotalDoorSetPrice = itemAdjustCount($Id, $vId);
            $nonConfigData = nonConfigurableItem($Id, $vId, CompanyUsers());
            $nonConfigDataPrice = nonConfigurableItem($Id, $vId, CompanyUsers(), '', true);
            $screenDataprice = $SideScreenData->sum('side_screen_items.ScreenPrice');
            $total_price = $TotalDoorSetPrice +  $TotalIronmongeryPrice + $nonConfigDataPrice + $screenDataprice;
            $Version = QuotationVersion::where('quotation_id', $Id)->get()->toArray();
            $MaxVersion = QuotationVersion::where('quotation_id', $Id)->max('version');
            $VersionId = QuotationVersion::where('quotation_id', $Id)->where('id', $vId)->value('version');
            $SideScreenData = $SideScreenData->get();
            $companykacustomer = "";
            $customerMultiContact = "";
            if (Auth::user()->UserType == "2" || Auth::user()->UserType == "3") {
                $Quotation;
                $UserId = Auth::user()->id;
                $companykacustomer = Customer::join('users', 'customers.UserId', '=', 'users.id')->where(['users.CreatedBy' => $UserId])->select('customers.*', 'users.*', 'customers.id as CId')->orderBy('customers.id', 'desc')->get();

                $customerMultiContact = CustomerContact::join('customers', 'customers.id', '=', 'customer_contacts.MainContractorId')->select('customer_contacts.*')->where(['customers.UserId' => $Quotation->MainContractorId])->get();
            } else {
                $companykacustomer = Customer::where(['UserId' => Auth::user()->id])->orderBy('customers.id', 'desc')->get();
                $customerMultiContact = CustomerContact::where(['MainContractorId' => $Quotation->MainContractorId])->get();
            }

            $CustomerDetails = CustomerContact::join('customers', 'customers.id', '=', 'customer_contacts.MainContractorId')
                ->where('customers.UserId', $Quotation->MainContractorId)->first();


            $nonconfigdata = NonConfigurableItems::wherein('userId', CompanyUsers())->orderBy('id', 'desc')->get();
            $NonConfig = '<div class="col-sm-12 p-0">
            <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead class="table-header-bg">
                        <tr class="text-white">
                            <th>Line</th>
                            <th>Name</th>
                            <th>Image</th>
                            <th>Product Code</th>
                            <th>Description</th>
                            <th>Unit</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody id="versionData">';
            $SI = 1;
            foreach ($nonconfigdata as $value) {
                // $NonConfig .=
                // '
                // <div class="col-sm-6 p-0 pr-1">
                //     <div class="Quote_tems">
                //         <img src="'.$nonconfigdatas->NonconfiBase64.'">
                //         <a href="javascript:void(0);">'.$nonconfigdatas->name.'</a>
                //         <p class="description_msg">
                //             '.$nonconfigdatas->description.'
                //         </p>
                //         <p class="nonConfigData">Product Code : <span>'.$nonconfigdatas->product_code.'</span></p>
                //         <p class="nonConfigData">Unit : <span>'.$nonconfigdatas->unit.'</span></p>
                //         <p class="nonConfigData">Price : <span>$'.$nonconfigdatas->price.'</span></p>
                //         <input type="number" class="form-control " placeholder="Quantity" style="display:inline-block !important; max-width: 120px;font-size: 14px !important;" id="nonconfigQuantity-'.$nonconfigdatas->id.'" value="">
                //         <a href="javascript:void(0);" data-type="strebord" onclick="nonConfigStore('.$Id.','.$vId.','.$nonconfigdatas->id.','.$nonconfigdatas->price.');" class="configure_btn">Non Configure</a>
                //     </div>
                // </div>
                // ';
                $NonConfig .= '<tr>
                <td>' . $SI++ . '</td>
                <td>' . $value->name . '</td>
                <td><img src="' . $value->NonconfiBase64 . '" alt="Non-ConfigImage" style="width: 100px;"></td>
                <td>' . $value->product_code . '</td>
                <td><p style="max-width: 200px;"><script type="text/javascript">
                         document.write(ReadMore(5,"' . $value->description . '"))
                     </script></p></td>
                <td>' . $value->unit . '</td>
                <td>' . floatval($value->price) . '</td>
                <td><input type="number" class="form-control nonconfigQut" placeholder="Quantity" style="margin: 0 auto; max-width: 50px;font-size: 14px !important;" id="nonconfigQuantity-' . $value->id . '" value=""></td>
                <td><a href="javascript:void(0);" data-type="strebord" onclick="nonConfigStore(' . $Id . ',' . $vId . ',' . $value->id . ',' . $value->price . ');" class="configure_btn">Add</a></td>
            </tr>';
            }

            $NonConfig .= '</tbody></table></div></div></div>';
            //     <script type="text/javascript">
            //          document.write(ReadMore(5,"'.$nonconfigdatas->description.'"))
            //     </script>
            // hide or disabled 'Add Item' button from GenerateQuotation.blade page
            // Button only appear when version is selected.
            $selectQV = ['selectVersionID' => 0, 'selectVersion' => 0, 'discountQuotation' => 0];
            $additem = 0;
            if ($vId > 0) {
                $QV = QuotationVersion::where('id', $vId)->first();
                $selectQV = ['selectVersionID' => $QV->id, 'selectVersion' => $QV->version, 'discountQuotation' => $QV->discountQuotation];
            }

            // Configurable Items
            $configurableItem = ConfigurableItems::orderBy('orderBy','ASC')->get();
            $configItem = '';
            foreach ($configurableItem as $ci) {
                if (!empty($Quotation->configurableitems)) {
                    if ($Quotation->configurableitems == $ci->id) {
                        $btnLink = '<a href="javascript:void(0);" data-type="' . $ci->id . '"
                            class="configure_btn">Create <br>Door Set</a>
                            <a href="javascript:void(0);"
                            data-type="' . $ci->id . '" class="configure_btn configure_door_btn">Add
                            Additional <br> Door Set</a>';
                    } else {
                        $btnLink = '<p class="configure_btn"> Another Door is selected for these quotation</p>';
                    }
                } else {
                    $btnLink =
                        '
                        <a href="javascript:void(0);" data-type="' . $ci->id . '"
                        class="configure_btn">Create <br>Door Set</a>
                        <a href="javascript:void(0);"
                        data-type="' . $ci->id . '" class="configure_btn configure_door_btn">Add
                        Additional <br> Door Set</a>
                    ';
                }

                $configItem .=
                    '
                <div class="col-sm-6 p-0 pr-1">
                    <div class="Quote_tems">
                        <img src="' . url('/') . '/images/' . $ci->img . '" style="height: 52;">
                        <a href="#">' . $ci->name . '</a>
                        <input type="hidden" value="' . $ci->id . '" class="configItemId">
                        <p>Configurable On Configuration</p>
                        ' . $btnLink . '
                    </div>
                </div>
                ';
            }

            $countDeliveryAddressInEditHeader = QuotationSiteDeliveryAddress::where('QuotationId', $Id)->count();
            $xx = QuotationSiteDeliveryAddress::where('QuotationId', $Id)->get();
            $DA = '';
            $loop = 0;
            foreach ($xx as $xxs) {
                $plus = '';
                if ($loop == 0) {
                    $plus = '
                    <div>
                        <a style="float: right; margin-right: 10px; margin-top: -45px" href="javascript:void(0);" id="add-customer-detail" class="btn-shadow btn btn-success">
                            <i class="fa fa-plus"></i>
                        </a>
                    </div>';
                } else {
                    $plus = '
                    <div>
                        <input type="hidden" class="QuotDeliverAddrID" value="' . $xxs->id . '">
                        <a style="float: right; margin-right: 10px; margin-top: -45px" href="javascript:void(0);" class="btn-shadow btn btn-danger deleteQuotDeliverAddr">
                            <i class="fa fa-remove"></i>
                        </a>
                    </div>';
                }

                $DA .= '
                <input type="hidden" name="quotation_sitedeliveryaddressID[]" value="' . $xxs->id . '">
                <div class="col-sm-12">
                    <div class="card-header">
                        <h5 class="card-title" style="margin-top: 10px">Site Delivery Address</h5>
                    </div>
                    ' . $plus . '
                    <div class="row">
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Address1">Address 1<span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="Address1[]"
                                    value="' . $xxs->Address1 . '" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="position-relative form-group">
                                <label for="Address2">Address 2</label>
                                <input type="text" class="form-control" name="Address2[]"
                                    value="' . $xxs->Address2 . '">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="Country">Country</label>
                                <input type="text" class="form-control" name="Country[]"
                                    value="' . $xxs->Country . '">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="City">City</label>
                                <input type="text" class="form-control" name="City[]"
                                    value="' . $xxs->City . '">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="position-relative form-group">
                                <label for="PostalCode">Postal Code/Eircode</label>
                                <input type="text" class="form-control" name="PostalCode[]"
                                    value="' . $xxs->PostalCode . '">
                            </div>
                        </div>
                    </div>
                </div>

                ';
                $loop++;
            }

            $currency = SettingCurrency::where('UserId', Auth::user()->id)->first();
            $quotation_data = Quotation::where('id', $Id)->first();
            if (Auth::user()->UserType == 1) {
                $Favorite = FavoriteItem::join('quotation', 'quotation.id', 'favorite_item.quotationId')->select('favorite_item.*', 'quotation.configurableitems')->get();
            } else {
                $UserIds = CompanyMultiUsers();
                $Favorite = FavoriteItem::join('quotation', 'quotation.id', 'favorite_item.quotationId')->select('favorite_item.*', 'quotation.configurableitems')->wherein('favorite_item.userId', $UserIds)->get();
            }

            return view('DoorSchedule.GenerateQuotation', [
                'data' => $Schedule,
                'SideScreenData' => $SideScreenData,
                'quotation_data' => $quotation_data,
                'quotationId' => $Id,
                'version' => $Version,
                'maxVersion' => $MaxVersion,
                'VersionId' => $VersionId,
                'generatedId' => $Quotation->QuotationGenerationId,
                'ArcgeneratedId' => $Quotation->ArchitectGenerationId,
                'companykacustomer' => $companykacustomer,
                'customerMultiContact' => $customerMultiContact,
                'quotation' => $Quotation,
                'ProjectTable' => $Projects,
                'customerDetails' => $CustomerDetails,
                'QuotationContactInformation' => $QuotationContactInformation,
                'QuotationShipToInformation' => $QuotationShipToInformation,
                'additem' => $additem,
                'NonConfig' => $NonConfig,
                'TotalDoorPrice' => $TotalDoorSetPrice,
                'TotalExactDoorPrice' => $TotalExactDoorPrice,
                'TotalIronmongeryPrice' => $TotalIronmongeryPrice,
                'total_price' => $total_price,
                'nonConfigDataPrice' => $nonConfigDataPrice,
                'screenDataprice' => $screenDataprice,
                'selectQV' => $selectQV,
                'configItem' => $configItem,
                'countDeliveryAddressInEditHeader' => $countDeliveryAddressInEditHeader,
                'QuotationSiteDeliveryAddress' => $xx,
                'DA' => $DA,
                'currency' => $currency,
                'Favorite' => $Favorite,
                'ProjectsAddress' => $ProjectsAddress,
                'nonConfigData' => $nonConfigData,
                'ProjectId' => $Quotation->ProjectId
            ]);
        } else {
            return redirect()->route('quotation/list');
        }
    }

    public function getVersionQuotation(Request $request)
    {
        if (!empty($request->version)) {
            $versionId = $request->version;
            $quotationId = $request->quotationId;
            $qv = QuotationVersion::where('id', $versionId)->first();
            $version = $qv->version;
            if ($request->version == 0) {
                $dtd = Item::Join('quotation_version_items', 'items.QuotationId', 'quotation_version_items.QuotationId')->get();
                return $dtd;
            } else {
                $quotation = Quotation::where('id', $quotationId)->first();
                if (!empty($quotation)) {

                    $schedule = Item::join('quotation_version_items', 'items.itemId', 'quotation_version_items.itemID')
                        ->join('item_master', 'quotation_version_items.itemmasterID', 'item_master.id')
                        ->where('quotation_version_items.version_id', $versionId)->get();

                    echo json_encode([
                        "status" => "success",
                        "quotationId" => $request->quatationId,
                        "version" => $version,
                        "versionId" => $versionId,
                        "door" => $schedule
                    ]);
                    // view('DoorSchedule.GenerateQuotation',['data'=>$schedule,'quotationId'=>$request->quatationId,'version'=>$request->version]);
                } else {
                    echo json_encode(["status" => "error"]);
                    // return redirect()->route('quotation/list');
                }
            }
        } else {
            echo json_encode(["status" => "error"]);
            // return redirect()->route('quotation/quotation/list');
        }

        return null;
    }

    public function records(request $request): void
    {
        //dd($request->all());
        ini_set('memory_limit', '-1');

        if ($request->input('isStatus') == 11) {
            $orderData = json_decode((string) $request->input('orders'), true);
            $request->merge(['orders' => $orderData]);
        }

        $AndWhereCondition = [];
        $OrWhereCondition = [];
        if ($request->ajaxCall == 1) {

            $from = $request->from;
            $limit = $request->limit;

            if ($limit == "0" || $limit == "") {
                $limit = PHP_INT_MAX;
            }


            $filters = $request->filters;
            if ($request->filters == "") {
                $filters = [];
            }

            for ($i = 0; $i <= count($filters) - 1; $i++) {
                // if(array_key_exists("AND",$filters[$i]) || array_key_exists("OR",$filters[$i])){
                //     if(array_key_exists("AND",$filters[$i])){
                //         array_push($AndWhereCondition,[$filters[$i]['AND'][0],$filters[$i]['AND'][1],$filters[$i]['AND'][2]]);
                //     }
                //     if(array_key_exists("OR",$filters[$i])){
                //         array_push($OrWhereCondition,[$filters[$i]['OR'][0],$filters[$i]['OR'][1],$filters[$i]['OR'][2]]);

                //         // print_r($OrWhereCondition);die;
                //     }
                // }else{
                //     $filters[$i] = [$filters[$i][0],$filters[$i][1],$filters[$i][2]];
                // }

                $filters[$i] = [$filters[$i][0], $filters[$i][1], $filters[$i][2]];
            }

            $orders = $request->orders;
            $column = $orders[0]["column"];
            $dir = $orders[0]["dir"];
        }




        $loginUserId = Auth::user()->id;

        $UserType = Auth::user()->UserType;


        switch ($UserType) {

            case 1:

                if (!empty($request->id)) {
                    $filters[] = ['quotation.UserId', "=", $request->id];
                }

                $filters[] = ['quotation.UserId', "!=", null];
                break;

            case 2:
                $login_company_id = get_company_id(Auth::user()->id)->id ?? Auth::user()->id;
                if (empty($request->id)) {

                    // $filters[] = ['quotation.CompanyId', "=", $login_company_id];
                } else {

                    $filters[] = ['quotation.UserId', "=", $request->id];
                    $filters[] = ['quotation.CompanyId', "=", $login_company_id];
                }

                // $UserId = User::where('CreatedBy', Auth::user()->id)->where('UserType',3)->pluck('id')->toArray();
                // array_push($UserId, Auth::user()->id);
                $UserId =  myCreatedUser();

                break;

            case 3:
                // $UserId = User::where('CreatedBy', Auth::user()->CreatedBy)->where('UserType',3)->pluck('id')->toArray();
                // array_push($UserId, intval(Auth::user()->CreatedBy));
                $UserId = myCreatedUser();
                $users = User::where('UserType',3)->where('id',Auth::user()->id)->first();
                // $UserId = [Auth::user()->id, intval($users->CreatedBy)];
                // $filters[] = ['quotation.UserId', "=", $loginUserId];
                $login_company_id = get_company_id($users->CreatedBy)->id ?? null;
                // $filters[] = ['quotation.CompanyId', "=", $login_company_id];
                break;

            case 4:
                $login_architect_id = get_architect_id(Auth::user()->id)->id;
                if (empty($request->id)) {

                    $filters[] = ['quotation.ArchitectId', "=", $login_architect_id];
                } else {

                    $filters[] = ['quotation.UserId', "=", $request->id];
                    $filters[] = ['quotation.ArchitectId', "=", $login_architect_id];
                }

                break;


            case 5:
                $login_customer_id = get_customer_id(Auth::user()->id)->id;
                if (empty($request->id)) {

                    $filters[] = ['quotation.MainContractorId', "=", $login_customer_id];
                } else {

                    $filters[] = ['quotation.UserId', "=", $request->id];
                    $filters[] = ['quotation.MainContractorId', "=", $login_customer_id];
                }

                break;

            default:
                $filters[] = ['quotation.UserId', "=", $loginUserId];
        }

        // dd($filters);
        $Quotations = Quotation::leftJoin("quotation_versions", function ($join): void {
            $join->on("quotation.id", "quotation_versions.quotation_id")
                ->orOn("quotation_versions.id", "=", "quotation.VersionId");
            })
            ->leftJoin("project", "project.id", "quotation.ProjectId")
            ->leftJoin('companies', 'companies.id', 'quotation.CompanyId')
            ->leftJoin('customers', 'customers.id', 'quotation.MainContractorId')
            ->leftJoin('users', 'users.id', 'quotation.MainContractorId')
            ->select('quotation.*', 'quotation.editBy as QuotEditBy', 'quotation.updated_at as QuotUpdatedAt', 'quotation.id as QuotationId', 'quotation_versions.version', 'companies.CompanyName', 'project.*', 'quotation_versions.id as QVID', 'customers.CstCompanyName', 'quotation.MainContractorId as MainId', 'users.FirstName', 'quotation.VersionId as verId')
            // ->where('quotation.QuotationStatus','!=','Ordered')
            // ->where($filters)
            ->where('quotation.QuotationGenerationId', '!=', null);

        if ($UserType == '3') {
            $users = User::where('UserType',3)->where('id',Auth::user()->id)->first();
            $Quotations = $Quotations->whereIn('quotation.UserId', [$users->CreatedBy,$users->id]);
            // $Quotations = $Quotations->where('quotation.CompanyId', $login_company_id);
            // if(!empty($filters)){
            //     // echo $filters[0][2];die;
            //     $Quotations = $Quotations->orWhere('quotation.QuotationName','LIKE',$filters[0][2])
            //     ->orWhere('quotation.FollowUpDate','LIKE',$filters[0][2])
            //     ->orWhere('quotation.PONumber','LIKE',$filters[0][2])
            //     ->orWhere('project.ProjectName','LIKE',$filters[0][2])
            //     ->orWhere('customers.CstCompanyName','LIKE',$filters[0][2]);
            // }

            //new code for list-------------------------------
            // $Quotations = $Quotations->where($filters);
            // $QuotationsCount = $Quotations->count();
            // if($request->listType=='dataListType'){
            //     $Quotations = $Quotations->get();
            // }else{

            //     $Quotations = $Quotations->skip($from)->take($limit)->orderBy("$column", "$dir")->get();
            // }

        } elseif ($UserType == '2') {
            $Quotations = $Quotations->wherein('quotation.UserId', $UserId);
            // if($filters[0][0] != "quotation.QuotationGenerationId"){
            //     $Quotations = $Quotations->orWhere('quotation.QuotationName','LIKE',$filters[0][2])
            //         ->orWhere('quotation.FollowUpDate','LIKE',$filters[0][2])
            //         ->orWhere('quotation.PONumber','LIKE',$filters[0][2])
            //         ->orWhere('project.ProjectName','LIKE',$filters[0][2])
            //         ->orWhere('customers.CstCompanyName','LIKE',$filters[0][2]);
            // }else{
            // $Quotations = $Quotations->where($filters);
            // }

            //new code for list-----------------------------------
            // $Quotations = $Quotations->where($filters);
            // $QuotationsCount = $Quotations->count();
            // if($request->listType=='dataListType'){
            //     $Quotations = $Quotations->get();
            // }else{

            //     $Quotations = $Quotations->skip($from)->take($limit)->orderBy("$column", "$dir")->get();
            // }

        }

        // else{
        //     $Quotations = $Quotations->where($filters);
        // ->orWhere('quotation.QuotationName','LIKE',$filters[0][2])
        // ->orWhere('quotation.FollowUpDate','LIKE',$filters[0][2])
        // ->orWhere('quotation.PONumber','LIKE',$filters[0][2])
        // ->orWhere('project.ProjectName','LIKE',$filters[0][2])
        // ->orWhere('customers.CstCompanyName','LIKE',$filters[0][2]);
        // }

        // ----------CODE TO SHOW DATA IN LIST AND IN GRID-------------------------------
        // dd($filters,$from,$limit,$column,$dir,$request->listType);
        $Quotations = $Quotations->where($filters);
        $QuotationsCount = $Quotations->count();
        if($request->listType=='dataListType'){
            $Quotations = $Quotations->orderBy($column, $dir)->get();
        }else{

            $Quotations = $Quotations->skip($from)->take($limit)->orderBy($column, $dir)->get();
        }

        // ->where($AndWhereCondition)
        // ->orWhere($OrWhereCondition)


        // $QuotationsCount = Quotation::leftJoin("quotation_versions",function($join){
        //         $join->on("quotation.id","quotation_versions.quotation_id")
        //         ->orOn("quotation_versions.id","=","quotation.VersionId");
        //     })
        //     ->leftJoin("project","project.id","quotation.ProjectId")
        //     ->leftJoin('companies','companies.id','quotation.CompanyId')
        //     ->leftJoin('customers','customers.id','quotation.MainContractorId')
        //     ->leftJoin('users','users.id','quotation.MainContractorId')
        //     ->select('quotation.*', 'quotation.id as QuotationId','quotation_versions.version', 'companies.CompanyName', 'project.*','customers.CstCompanyName')
        //     ->where('quotation.QuotationGenerationId','!=',null);
        //     if($UserType == '3'|| $UserType == '2'){
        //         $QuotationsCount = $QuotationsCount->wherein('quotation.UserId', $UserId);
        //     }else{
        //         $QuotationsCount = $QuotationsCount->where($filters);
        //     }
        //     $QuotationsCount = $QuotationsCount->count();

        // $QuotationsCount = 10;

        if ($Quotations !== null) {
            $htmlData = '';
            if ($request->input('listType') == 'dataListType') {
                 // $Quotations = $Quotations->toArray();
                 $htmlData .= '<table id="dataListType" class="table table-hover table-striped table-bordered dataTable no-footer dtr-inline">
                <thead  class="text-uppercase table-header-bg text-white">
                    <tr>
                        <th>S.N</th>
                        <th>Quotation Id</th>
                        <th>Quotation Company Name</th>
                        <th>Quotation Name</th>
                        <th>Project</th>
                        <th>Due Date</th>
                        <th>Follow-up Date</th>
                        <th>Number of Door Sets</th>
                        <th>P.O. Number</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>';
                 $sn = 1;
            foreach($Quotations as $val){
                // print_r($val);die;
                $QVID = $val['QVID'] != ""?$val['QVID']:0;
                if($val['QuotationStatus'] != ''){
                    if ($val['QuotationStatus'] == 'Open') {
                        $quotation_status = '<strong class="QuotationStatus" style="background: #69e4a6;">'.$val['QuotationStatus'].'</strong>';
                    } elseif ($val['QuotationStatus'] == 'Ordered' || $val['QuotationStatus'] == 'Accept') {
                        if($val['verId'] == $QVID){
                            $quotation_status = '<strong class="QuotationStatus" style="background: #47a91f;">'.$val['QuotationStatus'].'</strong>';
                        }else{
                            $quotation_status = '<strong class="QuotationStatus" style="background: #69e4a6;">Open</strong>';
                        }
                    } elseif ($val['QuotationStatus'] == 'All') {
                        $quotation_status = '<strong class="QuotationStatus" style="background:#808080;">'.$val['QuotationStatus'].'</strong>';
                    } else {
                        $quotation_status = '<strong class="QuotationStatus" style="background:red;">'.$val['QuotationStatus'].'</strong>';
                    }
                } else {
                    $quotation_status = null;
                }

                $version = $val['version'] != ""?$val['version']:1;

                $bomTag = $val['bomTag'] != ""?$val['bomTag']:0;

                $CstCompanyName = $val['FirstName'] != '' ? $val['FirstName'] : '-----------';

                $QuotationName = $val['QuotationName'] != '' ? $val['QuotationName'] : '-----------';

                $ProjectName = $val['ProjectName'] != '' ? $val['ProjectName'] : '-----------';

                $ExpiryDate = $val['ExpiryDate'] != '' ? $val['ExpiryDate'] : '-----------';

                $FollowUpDate = $val['FollowUpDate'] != '' ? $val['FollowUpDate'] : '-----------';

                // if($version > 0){
                    $NumberOfDoorSets = NumberOfDoorSets($QVID, $val['QuotationId']);
                // }

                $PONumber = $val['PONumber'] != '' ? $val['PONumber'] : '-----------';

                if($val['QuotEditBy'] != ''){
                    $us = User::where('id',$val['QuotEditBy'])->first();
                    $lastModifyName = $us && $us['FirstName'] != '' ? $us['FirstName'].' '.$us['LastName'] : '-----------';
                } else {
                    $lastModifyName = '-----------';
                }

                //currency showing formate is changed accordingly(dynamically)
                $Currency = '';
                if(!empty($val->Currency)){
                    if ($val->Currency == '£_GBP') {
                        $Currency="£";
                    } elseif ($val->Currency == '€_EURO') {
                        $Currency= "€";
                    } elseif ($val->Currency == '$_US_DOLLAR') {
                        $Currency= "$";
                    }
                }else{
                    $Currency="£";
                }

                $htmlData .= '<tr>
                <td>'.$sn.'</td>
                <td><a href="' . url('quotation/generate/' . $val['QuotationId']) . '/' . $QVID . '" class="QuotationCode">' . $val['QuotationGenerationId'] . '-' . $version . '</a></td>
                <td>'.$CstCompanyName . ' ' . $quotation_status.'</td>
                <td>'.$QuotationName.'</td>
                <td>'.$ProjectName.'</td>
                <td>'.date2Formate($ExpiryDate).'</td>
                <td>'.date2Formate($FollowUpDate).'</td>
                <td>'.$NumberOfDoorSets.'</td>
                <td>'.$PONumber.'</td>
                <td><div class="dropdown">
                <button class="btn  dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  ....
                </button>
                <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                  <a class="dropdown-item" href="' . url('quotation/generate/' . $val['QuotationId']) . '/' . $QVID . '" target="_blank">Open</a>
                  <a class="dropdown-item" href="javascript:void(0);" onClick="CopyQuotation(' . $val['QuotationId'] . ',' . $QVID . ');">Copy</a>
                  <a class="dropdown-item" href="javascript:void(0);" onClick="PrintInvoice(' . $val['QuotationId'] . ',' . $QVID . ',' . $bomTag . ');">Generate Quote</a>
                  <a class="dropdown-item" href="javascript:void(0);" onClick="ExcelExportNew(' . $val['QuotationId'] . ',' . $QVID . ');">Export</a>
                  <a class="dropdown-item" href="javascript:void(0);" onClick="DeleteQuotation(' . $val['QuotationId'] . ',' . $QVID . ');">Delete</a>
                </div>
              </div>
                </td>
            </tr>';
            $sn++;


                // <div class="QuotationStatusNumber">'.$Currency .''. $totalCost .'</div>
            }

            $htmlData .= '</tbody>
            </table>';
            } else {
                // $Quotations = $Quotations->toArray();
                foreach ($Quotations as $val) {
                    $QVID = $val['QVID'] != "" ? $val['QVID'] : 0;
                    if ($val['QuotationStatus'] != '') {
                        if ($val['QuotationStatus'] == 'Open') {
                            $quotation_status = '<strong class="QuotationStatus" style="background: #69e4a6;">' . $val['QuotationStatus'] . '</strong>';
                        } elseif ($val['QuotationStatus'] == 'Ordered' || $val['QuotationStatus'] == 'Accept') {
                            if ($val['verId'] == $QVID) {
                                $quotation_status = '<strong class="QuotationStatus" style="background: #47a91f;">' . $val['QuotationStatus'] . '</strong>';
                            } else {
                                $quotation_status = '<strong class="QuotationStatus" style="background: #69e4a6;">Open</strong>';
                            }
                        } elseif ($val['QuotationStatus'] == 'All') {
                            $quotation_status = '<strong class="QuotationStatus" style="background:#808080;">' . $val['QuotationStatus'] . '</strong>';
                        } else {
                            $quotation_status = '<strong class="QuotationStatus" style="background:red;">' . $val['QuotationStatus'] . '</strong>';
                        }
                    } else {
                        $quotation_status = null;
                    }

                    $version = $val['version'] != "" ? $val['version'] : 1;

                    $bomTag = $val['bomTag'] != "" ? $val['bomTag'] : 0;

                    $CstCompanyName = $val['FirstName'] != '' ? $val['FirstName'] : '-----------';

                    $QuotationName = $val['QuotationName'] != '' ? $val['QuotationName'] : '-----------';

                    $ProjectName = $val['ProjectName'] != '' ? $val['ProjectName'] : '-----------';

                    $ExpiryDate = $val['ExpiryDate'] != '' ? $val['ExpiryDate'] : '-----------';

                    $FollowUpDate = $val['FollowUpDate'] != '' ? $val['FollowUpDate'] : '-----------';

                    // if($version > 0){
                    $NumberOfDoorSets = NumberOfDoorSets($QVID, $val['QuotationId']);
                    // }

                    $PONumber = $val['PONumber'] != '' ? $val['PONumber'] : '-----------';

                    if ($val['QuotEditBy'] != '') {
                        $us = User::where('id', $val['QuotEditBy'])->first();
                        $lastModifyName = $us && $us['FirstName'] != '' ? $us['FirstName'] . ' ' . $us['LastName'] : '-----------';
                    } else {
                        $lastModifyName = '-----------';
                    }

                    //currency showing formate is changed accordingly(dynamically)
                    $Currency = '';
                    if (!empty($val->Currency)) {
                        if ($val->Currency == '£_GBP') {
                            $Currency = "£";
                        } elseif ($val->Currency == '€_EURO') {
                            $Currency = "€";
                        } elseif ($val->Currency == '$_US_DOLLAR') {
                            $Currency = "$";
                        }
                    } else {
                        $Currency = "£";
                    }

                    // $DoorsetPrice = 0;
                    // $IronmongaryPrice = 0;

                    // $Item = Item::Join('quotation','quotation.id','=','items.QuotationId')->
                    //     // Join('item_master','item_master.itemID','=','items.itemId')->
                    //     leftJoin("quotation_version_items",function($join) use ($QVID){
                    //         $join->on("quotation_version_items.itemID","=","items.itemId")
                    //             // ->on("quotation_version_items.itemmasterID","=","item_master.id")
                    //             ->where("quotation_version_items.version_id","=",$QVID);
                    //     })->where('items.QuotationId',$val->QuotationId)->where('items.VersionId',$QVID)->get();

                    // if(!empty($Item)){
                    //     foreach($Item as $value){
                    //         $DoorsetPrice = floatval($DoorsetPrice) + floatval((($value->AdjustPrice)?floatval($value->AdjustPrice) :floatval($value->DoorsetPrice)));
                    //         $IronmongaryPrice = floatval($IronmongaryPrice) + floatval($value->IronmongaryPrice);
                    //     }
                    // }
                    // $nonConfigDataPrice = nonConfigurableItem($val->QuotationId,$QVID,Auth::user()->id,'',true);
                    // $discountPrice = (floatval($DoorsetPrice) + floatval($IronmongaryPrice) + floatval($nonConfigDataPrice)) * $val->QuoteSummaryDiscount/100;
                    // $totalCost = (floatval($DoorsetPrice) + floatval($IronmongaryPrice) + floatval($nonConfigDataPrice)) - $discountPrice;
                    $htmlData .=
                        '
                <div class="col-sm-3 mb-3">
                    <div class="QuotationBox">
                        <a href="' . url('quotation/generate/' . $val['QuotationId']) . '/' . $QVID . '" class="QuotationCode">' . $val['QuotationGenerationId'] . '-' . $version . '</a>
                        <div class="QuotationCompanyName">
                            <b>' . $CstCompanyName .  $quotation_status . '</b>
                        </div>
                        <div class="QuotationListData">
                            <b>Quotation Name</b>
                            <span>' . $QuotationName . '</span>
                            <b>Project</b>
                            <span>' . $ProjectName . '</span>
                            <b>Due Date</b>
                            <span>' . date2Formate($ExpiryDate) . '</span>
                            <b>Follow-up Date</b>
                            <span>' . date2Formate($FollowUpDate) . '</span>
                            <b>Number of Door Sets</b>
                            <span>' . $NumberOfDoorSets . '</span>
                        </div>
                        <div class="QuotationListNumber">
                            <b>P.O. Number</b>
                            <span>' . $PONumber . '</span>
                        </div>
                        <div class="QuotationModifiedDate">
                            <p>Last modified by ' . $lastModifyName . ' on ' . dateFormate($val['QuotUpdatedAt']) . '</p>
                        </div>
                        <div class="filter_action">
                            <label for="filter" class="quote_filter">
                                <i class="fas fa-ellipsis-h"></i>
                            </label>
                            <ul class="QuotationMenu">
                                <li>
                                    <a href="' . url('quotation/generate/' . $val['QuotationId']) . '/' . $QVID . '" target="_blank"><i class="fas fa-mouse-pointer"></i> Open</a>
                                </li>
                                <li><a href="javascript:void(0);" onClick="CopyQuotation(' . $val['QuotationId'] . ',' . $QVID . ');"><i class="far fa-copy"></i> Copy</a></li>
                                <li><a href="javascript:void(0);" onClick="PrintInvoice(' . $val['QuotationId'] . ',' . $QVID . ',' . $bomTag . ');"><i class="fas fa-print"></i> Generate Quote</a></li>

                                <li><a href="javascript:void(0);" onClick="ExcelExportNew(' . $val['QuotationId'] . ',' . $QVID . ');">
                                    <i class="fas fa-file-export"></i> Export</a>
                                </li>
                                <li>
                                <a href="javascript:void(0);" onClick="DeleteQuotation(' . $val['QuotationId'] . ',' . $QVID . ');"><i class="far fa-trash-alt"></i> Delete</a></li>
                            </ul>
                        </div>

                    </div>
                </div>';

                    // <div class="QuotationStatusNumber">'.$Currency .''. $totalCost .'</div>
                }
            }




  //dd($htmlData);
            if (!empty($Quotations)) {

                // $htmlData = View::make('DoorSchedule.Ajax.AjaxQuotationList',compact('Quotations'))->render();

                ms([
                    'st' => "success",
                    'txt' => 'Data found.',
                    'total' => $QuotationsCount,
                    'html' => $htmlData,
                ]);
            } else {
                ms([
                    'st' => "error",
                    'txt' => 'Data not found.',
                    'total' => 0,
                    'html' => "",
                ]);
            }
        } else {
            ms([
                'st' => "error",
                'txt' => 'Data not found.',
                'total' => 0,
                'html' => "",
            ]);
        }
    }




    public function selectcustomer(Request $request): void
    {
        $users = User::where('UserType', 3)->where('id', Auth::user()->id)->first();
        if (Auth::user()->UserType == 3) {
            $UserId = $users->CreatedBy;
        } else {
            $UserId = Auth::user()->id;
        }

        $company_id = get_company_id($UserId)->id;
        $quotationId = $request->quotationId;
        $customerId = $request->customerId;
        $quotaionUpdate = Quotation::find($quotationId);
        $quotaionUpdate->MainContractorId = $customerId;
        $quotaionUpdate->editBy = Auth::user()->id;
        $quotaionUpdate->updated_at = date('Y-m-d H:i:s');
        $quotaionUpdate->save();

        $aa = Quotation::where('id', $quotationId)->first();
        $tt = CustomerContact::where('MainContractorId', $aa->MainContractorId)->get();
        $selectCC = '<option value="">Select Contact List</option>';
        foreach ($tt as $cc) {
            $selectCC .= '<option value="' . $cc->id . '" selected>' . $cc->FirstName . ' ' . $cc->LastName . '</option>';
        }

        $selproj = '<option value="">Select Contact List</option>';
        $kk = Project::where(['CompanyId' => $company_id, 'MainContractorId' => $customerId, 'Status' => 1])->get();
        foreach ($kk as $kks) {
            $selproj .= '<option value="' . $kks->id . '" selected>' . $kks->ProjectName . '</option>';
        }

        $proCur = Project::where(['CompanyId' => $company_id, 'MainContractorId' => $customerId, 'Status' => 1])->first();
        $projectCurrency = '';
        if (!empty($proCur->projectCurrency)) {
            $projectCurrency = '<option value="' . $proCur->projectCurrency . '" selected>' . $proCur->projectCurrency . '</option>';
        } else {
            $currency = SettingCurrency::where('UserId', Auth::user()->id)->first();
            $rr = ['$_US_DOLLAR', '£_GBP', '€_EURO'];
            $i = 0;
            $count = count($rr);
            while ($count > $i) {
                $selected = '';
                if (!empty($currency) && $currency->currency == $rr[$i]) {
                    $selected = "selected";
                }

                $projectCurrency .= '<option value="' . $rr[$i] . '" ' . $selected . '>' . $rr[$i] . '</option>';

                $i++;
            }
        }

        echo json_encode(['status' => 'success', 'message' => "Quotation assigned successfully.", 'MainContractorId' => $quotaionUpdate->MainContractorId, 'flag' => $quotaionUpdate->flag, 'selectCC' => $selectCC, 'selproj' => $selproj, 'projectCurrency' => $projectCurrency]);

        // if($quotaionUpdate->save()){
        //     echo json_encode(['status'=>'success','message'=> "Quotation assigned successfully.",'MainContractorId'=>$quotaionUpdate->MainContractorId,'flag'=>$quotaionUpdate->flag,'selectCC'=>$selectCC]);
        // }else{
        //     echo json_encode(['status'=>'error','message'=> "Something went wrong. Please try again..."]);
        // }
        die();
    }

    public function singleconfigurationitem($id, $vid = null)
    {

        // $schedule = DoorSchedule::where('id',$id)->first();
        $item = Item::all();
        $schedule = (object)['QuotationId' => $id, 'id' => '', 'Mark' => '', 'Type' => '', 'MarkLevel' => '', 'FireRating' => '', 'VisionPanel' => '', 'InternalorExternal' => '', 'StructuralWidth' => '', 'StructuralHeight' => '', 'DoorFinish' => '', 'NBS' => '', 'Status' => '', 'IsSentForInvoice' => ''];

        $options_data = Option::where('is_deleted', 0)->get();
        $company_data = Company::join('users', 'users.id', 'companies.UserId')->select('users.*')->get();
        $tooltip = Tooltip::first();
        $quotation = Quotation::where('id', $id)->first();
        // if(!empty($quotation->ProjectId)){
        //     $setIronmongery = AddIronmongery::where('ProjectId',$quotation->ProjectId)->get();
        // } else {
        //     $setIronmongery = null;
        // }
        $setIronmongery = AddIronmongery::where(['UserId' => Auth::user()->id])->orderBy('Setname', 'ASC')->get();


        return view('Items/Door', ['itemlist' => $item, 'option_data' => $options_data, 'company_list' => $company_data, 'schedule' => $schedule, 'issingleconfiguration' => '1', 'versionId' => $vid, 'tooltip' => $tooltip, 'setIronmongery' => $setIronmongery]);
        // return view('Items/Door',['itemlist'=>$item,'option_data'=>$options_data,'company_list'=>$company_data,'schedule'=>$schedule]);

    }

    public function removeItemFromVersion(Request $request): void
    {

        if (empty($request->quotationId)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.',
                'error' => 'Quotation ID is required.'
            ]);
            exit;
        }

        if (empty($request->version)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.',
                "error" => "Version is required."
            ]);
            exit;
        }

        if (empty($request->id)) {
            echo json_encode([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.',
                "error" => "Id is required."
            ]);
            exit;
        }

        $quotationId = $request->quotationId;
        $version = $request->version;
        $id = $request->id;

        $DeleteVersionItem = QuotationVersionItems::where('id', $id)
            ->where('QuotationId', $quotationId)
            ->where('Version', $version)
            ->delete();
        if ($DeleteVersionItem) {
            echo json_encode([
                'status' => 'success',
                'message' => 'Item removed successfully.',
            ]);
            exit;
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.',
                "error" => "Error in removing item."
            ]);
            exit;
        }
    }

    // Export in excel
    public function export($quotationId, $versionID)
    {
        // return $quotationId;
        // $item = Item::limit(5)->get();
        // $fp = fopen('file.csv', 'w');
        return Excel::download(new ScheduleOrder($quotationId, $versionID), 'ScheduleOrder.xlsx', \Maatwebsite\Excel\Excel::XLSX,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
    }

    // Export in new excel
    public function exportNew($quotationId, $versionID)
    {
        // return $quotationId;
        // $item = Item::limit(5)->get();
        // $fp = fopen('file.csv', 'w');
        return Excel::download(
            new ScheduleOrderNew($quotationId, $versionID),
            'ScheduleOrder.xlsx',
            \Maatwebsite\Excel\Excel::XLSX,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]
        );

    }

    public function ExportBomCalculation($quotationId,$versionID)
    {
        $quotation = Quotation::where('quotation.id',$quotationId)->first();
        $vid = ['selectVersionID'=>0,'selectVersion'=>0];
        if($vid > 0){
            $QV = QuotationVersion::where('id',$versionID)->first();
            $vid = $QV->version;
        }

        return Excel::download(new BomCalculationExport($quotationId,$versionID), "BOM ".trim((string) $quotation->QuotationGenerationId, "#")."-".$vid.'.xlsx', \Maatwebsite\Excel\Excel::XLSX,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
    }

    public function ExportSideScreen($quotationId,$versionID)
    {
        $quotation = Quotation::where('quotation.id',$quotationId)->first();
        $vid = ['selectVersionID'=>0,'selectVersion'=>0];
        if($vid > 0){
            $QV = QuotationVersion::where('id',$versionID)->first();
            $vid = $QV->version;
        }

        return Excel::download(new SideScreenExport($quotationId,$versionID), "SCREEN ".trim((string) $quotation->QuotationGenerationId, "#")."-".$vid.'.xlsx', \Maatwebsite\Excel\Excel::XLSX,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
    }

    public function ExportIronmongery($quotationId,$versionID)
    {
        $quotation = Quotation::where('quotation.id',$quotationId)->first();
        $vid = ['selectVersionID'=>0,'selectVersion'=>0];
        if($vid > 0){
            $QV = QuotationVersion::where('id',$versionID)->first();
            $vid = $QV->version;
        }

        return Excel::download(new IronmongeryExport($quotationId,$versionID), "Ironmongery ".trim((string) $quotation->QuotationGenerationId, "#")."-".$vid.'.xlsx', \Maatwebsite\Excel\Excel::XLSX,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
    }

    // Export in Vicama excel
    public function excelexportVicaima($quotationId, $versionID)
    {
        return Excel::download(new ScheduleOrderVicaima($quotationId, $versionID), 'ScheduleOrder.xlsx', \Maatwebsite\Excel\Excel::XLSX,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
    }

    // Export in excel
    public function export2()
    {
        // $item = Item::limit(5)->get();
        // $fp = fopen('file.csv', 'w');
        return Excel::download(new ScheduleOrder2(), 'TwoScheduleOrder.xlsx', \Maatwebsite\Excel\Excel::XLSX,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
    }

    // Search customer
    public function searchCustomer(Request $request): string
    {
        $name = $request->customerName;
        if (!empty($name)) {
            $Customers = CustomerContact::join('customers', 'customer_contacts.MainContractorId', 'customers.id')
                ->join('users', 'users.id', 'customers.UserId')
                ->select('customers.*', 'customer_contacts.FirstName', 'customer_contacts.LastName')
                ->where('users.CreatedBy', Auth::user()->id)
                ->where(DB::raw("CONCAT(customer_contacts.FirstName,customer_contacts.LastName)"), 'LIKE', '%' . $name . '%')
                ->get();
        } else {
            $Customers = CustomerContact::join('customers', 'customers.id', 'customer_contacts.MainContractorId')
                ->select('customers.*', 'customer_contacts.FirstName', 'customer_contacts.LastName')
                ->where('customers.UserId', Auth::user()->id)
                ->orderBy('customers.id', 'desc')
                ->get();
        }

        $Quotation = Quotation::where('id', $request->quotationIdValue)->first();
        $cusTbl = '';
        $cusTbl .=
            '
        <table style="width: 100%;margin-top: 10px;" id="example" class="table table-hover table-striped">
            <tbody>';
        if ($Customers != '[]') {
            $i = 1;
            foreach ($Customers as $row) {
                $cusTbl .=
                    '<tr>
                            <td>' . $i . '</td>
                            <td><a href="' . url('customer/details/' . $row->id) . '">' . $row->CstCompanyName . '</a></td>
                            <td>' . $row->CstCompanyPhone . '</td>
                            <td>' . $row->CstCompanyAddressLine1 . '</td>
                            <td>
                                <a class="btn btn-dark" onclick="selectCustomer(\'' . $row->id . "','" . $Quotation->QuotationGenerationId . "','" . $row->CstCompanyName . "','" . $row->CstSiteAddressLine1 . '\')" href="javascript:void(0);">Select</a>
                            </td>
                        </tr>';
                $i++;
            }
        } else {
            $cusTbl .= '<tr><td>No data found.<td></tr>';
        }

        $cusTbl .=
            '</tbody>
        </table>';

        return $cusTbl;
    }





    public function QuoteSummary(Request $request)
    {

        $array_data = explode(",", $request->QSCustomerContactId);

        $quotationId = $request->quotationId;
        $QSCustomerContactId = $array_data[0];
        $QSQuotationSiteDeliveryAddressId = $request->QSQuotationSiteDeliveryAddressId;
        $PONumber = $request->PONumber;

        $CompanyOrderCounter = CompanyOrderCounter::where('UserId', Auth::user()->id)->first();
        $QuotationCounter = CompanyQuotationCounter::where('UserId', Auth::user()->id)->first();
        $qq = Quotation::find($quotationId);
        $qq->QSCustomerContactId = $QSCustomerContactId;
        $qq->QSQuotationSiteDeliveryAddressId = $QSQuotationSiteDeliveryAddressId;
        $qq->PONumber = $PONumber;
        if (!empty($CompanyOrderCounter) && !empty($QuotationCounter)) {
            $qq->OrderNumber = str_replace($QuotationCounter->quotation_prefix, $CompanyOrderCounter->order_prefix, $qq->QuotationGenerationId);
        } elseif (!empty($CompanyOrderCounter) && empty($QuotationCounter)) {
            $qq->OrderNumber = str_replace("QTR", $CompanyOrderCounter->order_prefix, $qq->QuotationGenerationId);
        } elseif (empty($CompanyOrderCounter) && !empty($QuotationCounter)) {
            $qq->OrderNumber = str_replace($QuotationCounter->quotation_prefix, "ORD", $qq->QuotationGenerationId);
        } else {
            $qq->OrderNumber = str_replace("QTR", "ORD", $qq->QuotationGenerationId);
        }

        if ($qq->OrderNumber == $qq->QuotationGenerationId) {
            $qq->OrderNumber = str_replace("#", "#ORD", $qq->QuotationGenerationId);
        }

        if (!empty($request->QuoteSummaryDiscount)) {
            $qq->QuoteSummaryDiscount = $request->QuoteSummaryDiscount;
        }

        $qq->QuotationStatus = 'Ordered';
        $qq->editBy = Auth::user()->id;
        $qq->updated_at = date('Y-m-d H:i:s');
        $qq->save();

        return redirect()->route('orderlist');
    }


    public function QuoteSummaryOnChangeCustomer(Request $request)
    {
        $array_data = explode(",", $request->QSCustomerContactId);
        $quotationId = $request->quotationId;
        $QSCustomerContactId = $array_data[0];
        $QSCustomerContactId_Name = $request->QSCustomerContactId_Name;
        $QSQuotationSiteDeliveryAddressId = $request->QSQuotationSiteDeliveryAddressId;

        $qq = Quotation::find($quotationId);

        if ($QSCustomerContactId !== '' && $QSCustomerContactId !== '0') {
            $qq->QSCustomerContactId = $QSCustomerContactId[0];
        }

        if (!empty($QSQuotationSiteDeliveryAddressId)) {
            $qq->QSQuotationSiteDeliveryAddressId = $QSQuotationSiteDeliveryAddressId;
        }

        $qq->editBy = Auth::user()->id;
        $qq->updated_at = date('Y-m-d H:i:s');
        $qq->save();

        return $QSQuotationSiteDeliveryAddressId;
    }


    public function addConfigurationCadItem($id, $vid = null, $itemId = null)
    {
        $item = [];
        $UserIds = CompanyUsers();
        $ConfigurableDoorFormulaData = ConfigurableDoorFormula::where('status', 1)->get();
        $leafTypeIntumescentseal = IntumescentSealLeafType::where('configurableitems',1)->where('status',1)->get();
        $LippingSpeciesData = GetOptions(['lipping_species.Status' => 1], "join", "lippingSpecies");
        $SelectedLippingSpeciesData = $LippingSpeciesData;
        $OptionsData = Option::where(['configurableitems' => 1, 'is_deleted' => 0])->wherein('editBy', $UserIds)->get();

        $intumescentSealArrangement = GetOptions(['setting_intumescentseals2.configurableitems' => 1], "", "intumescentSealArrangement");

        $configurationDoor = configurationDoor(1);
        $UserId = Auth::user()->id;
        $UserType = Auth::user()->UserType;
        if (in_array($UserType, [1, 4])) {
            $SelectedOptionsData = $OptionsData;
            $intumescentSealColor = IntumescentSealColor::where([$configurationDoor => 1, 'Status' => 1])->wherein('editBy', $UserIds)->get();
            $ArchitraveType = ArchitraveType::where([$configurationDoor => 1, 'Status' => 1])->wherein('editBy', $UserIds)->get();
            $SelectedIntumescentSealArrangement = $intumescentSealArrangement;
            // $SelectedLippingSpeciesData = $LippingSpeciesData;

        } else {
            $UserId = CompanyUsers();
            $SelectedOptionsData = GetOptions(['options.configurableitems' => 1, 'options.is_deleted' => 0], "join");
            $intumescentSealColor = GetOptions(['intumescent_seal_color.' . $configurationDoor => 1, 'intumescent_seal_color.Status' => 1], "join", "intumescent_seal_color");
            $ArchitraveType = GetOptions(['architrave_type.' . $configurationDoor => 1, 'architrave_type.Status' => 1], "join", "architrave_type");

            $SelectedIntumescentSealArrangement = GetOptions(['selected_intumescentseals2.selected_configurableitems' => 1], "join", "intumescentSealArrangement");

            // $SelectedLippingSpeciesData = GetOptions(['lipping_species.Status'=> 1, 'selected_lipping_species.SelectedStatus'=> 1, 'selected_lipping_species.LippingSpeciesUserId' => Auth::user()->id], "join", "lippingSpecies");

        }

        $ColorData = Color::where('Status', 1)->wherein('editBy', $UserIds)->get();
        $company_data = Company::join('users', 'users.id', 'companies.UserId')->select('users.*')->get();
        $tooltip = Tooltip::first();
        $quotation = Quotation::where('id', $id)->first();

        // if(!empty($quotation->ProjectId)){
        //     $setIronmongery = AddIronmongery::where('ProjectId',$quotation->ProjectId)->get();
        // } else {
        //     $setIronmongery = null;
        // }

        //old
        $setIronmongery = AddIronmongery::wherein('UserId', $UserId)
        ->orderBy('Setname', 'ASC')
        ->get();


        $IronmongeryInfoSet = [
            'Hinges',
            'FloorSpring',
            'LocksAndLatches',
            'FlushBolts',
            'ConcealedOverheadCloser',
            'PullHandles',
            'PushHandles',
            'KickPlates',
            'DoorSelectors',
            'PanicHardware',
            'Doorsecurityviewer',
            'Morticeddropdownseals',
            'Facefixeddropseals',
            'ThresholdSeal',
            'AirTransferGrill',
            'Letterplates',
            'CableWays',
            'SafeHinge',
            'LeverHandle',
            'DoorSinage',
            'FaceFixedDoorCloser',
            'Thumbturn',
            'KeyholeEscutchen',
            'DoorStops',
            'Cylinders'
        ];

        // Process the data and merge
        foreach ($setIronmongery as $ironmongery) {
            $additionalInfo = []; // Temporary array to hold additional info

            foreach ($IronmongeryInfoSet as $valIronmongery) {
                // Check if the property exists and is not empty
                if (!empty($ironmongery->$valIronmongery)) {
                    $SelectedIronmongery = SelectedIronmongery::where('id', $ironmongery->$valIronmongery)
                        ->where('UserId', Auth::user()->id)
                        ->first();

                    if (!empty($SelectedIronmongery)) {
                            $IronmongeryInfoModel = IronmongeryInfoModel::where('IronmongeryId', $SelectedIronmongery->ironmongery_id)->where('UserId', Auth::user()->id)
                                ->first();
                            if(empty($IronmongeryInfoModel)){
                                $IronmongeryInfoModel = IronmongeryInfoModel::where('id', $SelectedIronmongery->ironmongery_id)->first();
                            }

                            if (!empty($IronmongeryInfoModel)) {
                                $additionalInfo[] = $IronmongeryInfoModel;
                            }
                    }
                }
            }

            // Dynamically add the additional_info attribute
            $ironmongery->setAttribute('additional_info', $additionalInfo);
        }


    // Simplified null check with optional chaining
    if(Auth::user()->UserType == 3){
        $users = User::where('UserType',3)->where('id',Auth::user()->id)->first();
        $ids = $users->CreatedBy;
    }else{
        $ids = Auth::user()->id;
    }

    $defaultItems = Project::whereHas('defaultItems', function ($query) use ($quotation,$ids): void {
        $query->where('default_type', 'custom')
              ->where('UserId', $ids)
              ->where('projectId', $quotation->ProjectId);
    })
    ->with(['defaultItems' => function ($query) use ($quotation,$ids): void {
        $query->where('default_type', 'custom')
              ->where('UserId', $ids)
              ->where('projectId', $quotation->ProjectId);
    }])
    ->first();

    if ($defaultItems) {
        // Convert $defaultItems to array
        $defaultItems = $defaultItems->toArray();
        // Access the first default item if it exists
        $defaultItemsCustom = $defaultItems['default_items'][0] ?? [];
    } else {
        $defaultItemsCustom = [];
    }

    $hinge_location = DoorFrameConstruction::where('UserId',$ids)->where('DoorFrameConstruction', 'Hinge_Location')->first();

// dd($defaultItemsCustom,$quotation->ProjectId);
        $BOMSetting = BOMSetting::where("id", 1)->get()->first();
        return view('Items/CadConfigurableItem', [
            "QuotationId" => $id,
            'Item' => $item,
            'option_data' => $OptionsData,
            'selected_option_data' => $SelectedOptionsData,
            'intumescentSealColor' => $intumescentSealColor,
            'ArchitraveType' => $ArchitraveType,
            'intumescentSealArrangement' => $intumescentSealArrangement,
            'SelectedIntumescentSealArrangement' => $SelectedIntumescentSealArrangement,
            'color_data' => $ColorData,
            'lipping_species' => $LippingSpeciesData,
            'selected_lipping_species' => $SelectedLippingSpeciesData,
            'ConfigurableDoorFormula' => $ConfigurableDoorFormulaData,
            'company_list' => $company_data,
            'issingleconfiguration' => '1',
            'versionId' => $vid,
            'tooltip' => $tooltip,
            'setIronmongery' => $setIronmongery,
            'BOMSetting' => $BOMSetting,
            'quotation' => $quotation,
            'leafTypeIntumescentseal' => $leafTypeIntumescentseal,
            'default' => $defaultItemsCustom,
            'hinge_location' => $hinge_location,
        ]);
    }

    public function addSideScreenItem($id, $vid = null, $itemId = null)
    {
        $item = [];
        $UserId = Auth::user()->id;
        $UserType = Auth::user()->UserType;

        $company_data = Company::join('users', 'users.id', 'companies.UserId')->select('users.*')->get();
        $quotation = Quotation::where('id', $id)->first();

        $BOMSetting = BOMSetting::where("id", 1)->get()->first();
        return view('Items/SideScreen/SideScreenItem', [
            "QuotationId" => $id,
            'Item' => $item,
            'company_list' => $company_data,
            'versionId' => $vid,
            'quotation' => $quotation,
        ]);
    }



    public function editSideScreenItem($id, $vid)
    {
        $item = SideScreenItem::where('id', $id)->first();
        if ($item === null) {
            return abort(404);
        }

        $item = $item->toArray();
        $UserId = Auth::user()->id;
        $UserType = Auth::user()->UserType;

        $company_data = Company::join('users', 'users.id', 'companies.UserId')->select('users.*')->get();
        $quotation = Quotation::where(['id' => $item["QuotationId"]])->first();

        $BOMSetting = BOMSetting::where("id", 1)->get()->first();
        return view('Items/SideScreen/SideScreenItem', [
            "QuotationId" => $quotation->id,
            'Item' => $item,
            'company_list' => $company_data,
            'versionId' => $vid,
            'quotation' => $quotation,
        ]);
    }

    public function editConfigurationCadItem($id, $vid)
    {

        $UserIds = CompanyUsers();
        $item = Item::where('itemId', $id)->first();
        if ($item === null) {
            return abort(404);
        }

        $item = $item->toArray();
        // $LippingSpeciesData = LippingSpecies::where(['Status' => 1])->get();

        $ConfigurableDoorFormulaData = ConfigurableDoorFormula::where('status', 1)->get();
        $OptionsData = Option::where(['configurableitems' => 1, 'is_deleted' => 0])->get();
        $intumescentSealArrangement = GetOptions(['setting_intumescentseals2.configurableitems' => 1], "", "intumescentSealArrangement");

        $LippingSpeciesData = GetOptions(['lipping_species.Status' => 1], "join", "lippingSpecies");
        // $SelectedLippingSpeciesData = $LippingSpeciesData;

        $configurationDoor = configurationDoor(1);
        $UserType = Auth::user()->UserType;
        if (in_array($UserType, [1, 4])) {
            $UserId = $item['UserId'];
            $SelectedOptionsData = $OptionsData;
            $intumescentSealColor = IntumescentSealColor::where([$configurationDoor => 1, 'Status' => 1])->wherein('editBy', $UserIds)->get();
            $ArchitraveType = ArchitraveType::where([$configurationDoor => 1, 'Status' => 1])->wherein('editBy', $UserIds)->get();
        } else {
            if(Auth::user()->UserType == 3){
                $UserId = Auth::user()->CreatedBy;
            }else{
                $UserId = Auth::user()->id;
            }

            $SelectedOptionsData = GetOptions(['options.configurableitems' => 1, 'options.is_deleted' => 0, 'selected_option.SelectedUserId' => $UserId], "join");
            $intumescentSealColor = GetOptions(['intumescent_seal_color.' . $configurationDoor => 1, 'intumescent_seal_color.Status' => 1], "join", "intumescent_seal_color");
            $ArchitraveType = GetOptions(['architrave_type.' . $configurationDoor => 1, 'architrave_type.Status' => 1], "join", "architrave_type");
        }



        // dd($OptionsData->toArray());

        $SelectedIntumescentSealArrangement = GetOptions(['selected_intumescentseals2.selected_configurableitems' => 1, 'selected_intumescentseals2.selected_intumescentseals2_user_id' => $UserId], "join", "intumescentSealArrangement");


        $SelectedLippingSpeciesQuery = SelectedLippingSpeciesItems::where([['selected_lipping_species_items.selected_user_id', '=', $UserId]]);
        $SelectedLippingSpeciesIds = array_column($SelectedLippingSpeciesQuery->groupBy("selected_lipping_species_id")->get()->toArray(), "id");

        $SelectedLippingSpeciesData = GetOptions(['lipping_species.Status' => 1], "join", "lippingSpecies", "query");
        $SelectedLippingSpeciesData = $SelectedLippingSpeciesData->whereIn("lipping_species.id",  $SelectedLippingSpeciesIds)->get();



        // dd($SelectedOptionsData);

        $ColorData = Color::where(['Status' => 1])->get();
        $company_data = Company::join('users', 'users.id', 'companies.UserId')->select('users.*')->get();
        $tooltip = Tooltip::first();
        $quotation = Quotation::where(['id' => $item["QuotationId"]])->first();
        $CompanyId = null;
        if ($quotation != '') {
            $CompanyId = $quotation->CompanyId;
        }

        // if(!empty($quotation->ProjectId)){
        //     $setIronmongery = AddIronmongery::where('ProjectId',$quotation->ProjectId)->get();
        // } else {
        //     $setIronmongery = null;
        // }
        // old code
        // $setIronmongery = AddIronmongery::wherein('UserId', $UserIds)->orderBy('Setname', 'ASC')->get();

        // new code
        $setIronmongery =  AddIronmongery::wherein('UserId', $UserIds)->orderBy('Setname', 'ASC')->get();
        $IronmongeryInfoSet = [
            'Hinges',
            'FloorSpring',
            'LocksAndLatches',
            'FlushBolts',
            'ConcealedOverheadCloser',
            'PullHandles',
            'PushHandles',
            'KickPlates',
            'DoorSelectors',
            'PanicHardware',
            'Doorsecurityviewer',
            'Morticeddropdownseals',
            'Facefixeddropseals',
            'ThresholdSeal',
            'AirTransferGrill',
            'Letterplates',
            'CableWays',
            'SafeHinge',
            'LeverHandle',
            'DoorSinage',
            'FaceFixedDoorCloser',
            'Thumbturn',
            'KeyholeEscutchen',
            'DoorStops',
            'Cylinders'
        ];

        // Process the data and merge
        foreach ($setIronmongery as $ironmongery) {
            $additionalInfo = []; // Temporary array to hold additional info

            foreach ($IronmongeryInfoSet as $valIronmongery) {
                // Check if the property exists and is not empty
                if (!empty($ironmongery->$valIronmongery)) {
                    $SelectedIronmongery = SelectedIronmongery::where('id', $ironmongery->$valIronmongery)
                        ->where('UserId', Auth::user()->id)
                        ->first();

                    if (!empty($SelectedIronmongery)) {
                            $IronmongeryInfoModel = IronmongeryInfoModel::where('IronmongeryId', $SelectedIronmongery->ironmongery_id)->where('UserId', Auth::user()->id)
                                ->first();
                            if(empty($IronmongeryInfoModel)){
                                $IronmongeryInfoModel = IronmongeryInfoModel::where('id', $SelectedIronmongery->ironmongery_id)->first();
                            }

                            if (!empty($IronmongeryInfoModel)) {
                                $additionalInfo[] = $IronmongeryInfoModel;
                            }
                    }
                }
            }

            // Dynamically add the additional_info attribute
            $ironmongery->setAttribute('additional_info', $additionalInfo);
        }

        $BOMSetting = BOMSetting::where("id", 1)->get()->first();
        $leafTypeIntumescentseal = IntumescentSealLeafType::where('configurableitems',1)->where('status',1)->get();

        // dd(\Config::get('constants.PossibleSelectedOptions'));

        return view('Items/CadConfigurableItem', [
            "QuotationId" => $item["QuotationId"],
            'Item' => $item,
            'option_data' => $OptionsData,
            'selected_option_data' => $SelectedOptionsData,
            'intumescentSealColor' => $intumescentSealColor,
            'ArchitraveType' => $ArchitraveType,
            'intumescentSealArrangement' => $intumescentSealArrangement,
            'SelectedIntumescentSealArrangement' => $SelectedIntumescentSealArrangement,
            'color_data' => $ColorData,
            'lipping_species' => $LippingSpeciesData,
            'selected_lipping_species' => $SelectedLippingSpeciesData,
            'ConfigurableDoorFormula' => $ConfigurableDoorFormulaData,
            'company_list' => $company_data,
            'issingleconfiguration' => '1',
            'versionId' => $vid,
            'tooltip' => $tooltip,
            'setIronmongery' => $setIronmongery,
            'BOMSetting' => $BOMSetting,
            'quotation' => $quotation,
            'leafTypeIntumescentseal' => $leafTypeIntumescentseal,
        ]);
    }

    public function copyExistingQuotation(Request $request)
    {

        if (empty($request->quotationId)) {
            $response = [
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.',
                'error' => "Quotation ID is required."
            ];
            return response()->json(
                $response,
                200,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }

        if ($request->versionId == "") {
            $response = [
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.',
                'error' => "Version ID is required."
            ];
            return response()->json(
                $response,
                200,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }

        $id = $request->quotationId;
        $VersionId = $request->versionId;

        $Quotation = Quotation::find($id);
        if ($Quotation != null) {

            $QuotationCounter = CompanyQuotationCounter::where('UserId', Auth::user()->id)->first();

            $IsCounterExist = false;

            if ($QuotationCounter !== null) {
                $QuotationCounter = $QuotationCounter->toArray();
                if (!empty($QuotationCounter) && !empty($QuotationCounter["quotation_counter"])) {
                    $IsCounterExist = true;
                }
            }

            if (!$IsCounterExist) {
                $quotation_prefix = empty($QuotationCounter->quotation_prefix) ? "QTR" : $QuotationCounter->quotation_prefix;
                $CompanyQuotationCounter = new CompanyQuotationCounter();
                $CompanyQuotationCounter->UserId = Auth::user()->id;
                $CompanyQuotationCounter->quotation_prefix = $quotation_prefix;
                $CompanyQuotationCounter->quotation_counter = 100001;
                $CompanyQuotationCounter->save();
                $NewQuotationCounter = 100001;
                $QuotationGenerationId = '#' . $quotation_prefix . Auth::user()->id . $NewQuotationCounter;
                $QuotationCounterId = $QuotationCounter->id;
            } else {
                $NewQuotationCounter = $QuotationCounter["quotation_counter"] + 1;
                $QuotationGenerationId = '#' . $QuotationCounter["quotation_prefix"] . Auth::user()->id . $NewQuotationCounter;
                $QuotationCounterId = $QuotationCounter["id"];
            }

            $NewQuotation = $Quotation->replicate();
            $NewQuotation->QuotationGenerationId = $QuotationGenerationId;
            if ($NewQuotation->save()) {
                if ($IsCounterExist) {
                    $UpdateQuotationCounter = CompanyQuotationCounter::where('id', $QuotationCounterId)
                        ->update(['quotation_counter' => $NewQuotationCounter]);
                }

                $NewCreatedQuotationId = $NewQuotation->id;

                $OldQuotationContactInformation = QuotationContactInformation::where('QuotationId', $id)->first();
                if ($OldQuotationContactInformation != null) {
                    $NewQuotationContactInformation = $OldQuotationContactInformation->replicate();
                    $NewQuotationContactInformation->QuotationId = $NewCreatedQuotationId;
                    $NewQuotationContactInformation->save();
                }

                $OldQuotationShipToInformation = QuotationShipToInformation::where('QuotationId', $id)->first();
                if ($OldQuotationShipToInformation != null) {
                    $NewQuotationShipToInformation = $OldQuotationShipToInformation->replicate();
                    $NewQuotationShipToInformation->QuotationId = $NewCreatedQuotationId;
                    $NewQuotationShipToInformation->save();
                }

                $NewQuotationVersion = new QuotationVersion();
                $NewQuotationVersion->quotation_id = $NewCreatedQuotationId;
                $NewQuotationVersion->version = 1;
                $NewQuotationVersion->save();

                $NewQuotationVersionId = $NewQuotationVersion->id;

                if ($VersionId == 0) {
                    $OldQuotationItems = Items::where('QuotationId', $id)->get();
                } else {
                    $QuotationVersionItemsForWhereIn = [];
                    $QuotationVersion = QuotationVersion::where('quotation_id', $id)->where('id', $VersionId)->first();


                    if ($QuotationVersion != null) {
                        $QuotationVersionIdForFilter = $QuotationVersion->id;

                        $QuotationVersionItemsForFilter = QuotationVersionItems::where('version_id', $QuotationVersionIdForFilter)->distinct()->get(['itemID']);



                        if ($QuotationVersionItemsForFilter != null) {
                            $QuotationVersionItemsForFilter = $QuotationVersionItemsForFilter->toArray();
                            foreach ($QuotationVersionItemsForFilter as $FilterKey => $FilterVal) {
                                $QuotationVersionItemsForWhereIn[] = $FilterVal['itemID'];
                            }
                        }
                    }

                    if ($QuotationVersionItemsForWhereIn !== []) {
                        $OldQuotationItems = Items::whereIn('itemId', $QuotationVersionItemsForWhereIn)->get();
                    } else {
                        $OldQuotationItems = null;
                    }
                }

                if ($OldQuotationItems != null) {
                    foreach ($OldQuotationItems as $IKey => $IVal) {
                        $NewQuotationItems = $IVal->replicate();
                        $NewQuotationItems->itemId = Items::max('itemId') + 1;
                        $NewQuotationItems->QuotationId = $NewCreatedQuotationId;
                        $NewQuotationItems->VersionId = $NewQuotationVersionId;
                        $NewQuotationItems->save();

                        $NewQuotationItemId = $NewQuotationItems->itemId;

                        $OldQuotationItemMaster = ItemMaster::where('itemID', $IVal->itemId)->get();
                        if ($OldQuotationItemMaster != null) {
                            foreach ($OldQuotationItemMaster as $IMKey => $IMVal) {
                                $NewQuotationItemMaster = $IMVal->replicate();
                                $NewQuotationItemMaster->itemID = $NewQuotationItemId;
                                $NewQuotationItemMaster->save();

                                $NewQuotationItemMasterId = $NewQuotationItemMaster->id;

                                if ($VersionId > 0) {
                                    $OldQuotationItemVersions = QuotationVersionItems::where('itemID', $IVal->itemId)
                                        ->where('itemmasterID', $IMVal->id)
                                        ->where('version_id', $VersionId)
                                        ->get();

                                    if ($OldQuotationItemVersions != null) {
                                        foreach ($OldQuotationItemVersions as $IVKey => $IVVal) {
                                            $NewQuotationItemVersions = $IVVal->replicate();
                                            $NewQuotationItemVersions->version_id = $NewQuotationVersionId;
                                            $NewQuotationItemVersions->itemID = $NewQuotationItemId;
                                            $NewQuotationItemVersions->itemmasterID = $NewQuotationItemMasterId;
                                            $NewQuotationItemVersions->save();
                                        }
                                    }
                                } else {
                                    $NewQuotationItemVersions = new QuotationVersionItems();
                                    $NewQuotationItemVersions->version_id = $NewQuotationVersionId;
                                    $NewQuotationItemVersions->itemID = $NewQuotationItemId;
                                    $NewQuotationItemVersions->itemmasterID = $NewQuotationItemMasterId;
                                    $NewQuotationItemVersions->save();
                                }
                            }
                        }

                        // inser into `BOMCalculation`
                        $version_id = QuotationVersion::where('quotation_id', $id)->where('id', $VersionId)->value('version');
                        $BOMCalculation = BOMCalculation::where('QuotationId', $id)->where('VersionId', $version_id)->where('itemId', $IVal->itemId)->get();

                        if (!empty($BOMCalculation)) {
                            foreach ($BOMCalculation as $value) {
                                $BOM = $value->replicate();
                                $BOM->id = BOMCalculation::max('id') + 1;
                                $BOM->QuotationId = $NewCreatedQuotationId;
                                $BOM->VersionId = 1;
                                $BOM->itemId = $NewQuotationItemId;
                                $BOM->save();
                            }
                        }
                    }

                    $nonConfigData = nonConfigurableItem($id, $VersionId, CompanyUsers(), true);
                    if (!empty($nonConfigData)) {
                        foreach ($nonConfigData as $value) {
                            $nonConfig = $value->replicate();
                            $nonConfig->id = NonConfigurableItemStore::max('id') + 1;
                            $nonConfig->quotationId = $NewCreatedQuotationId;
                            $nonConfig->versionId = $NewQuotationVersionId;
                            $nonConfig->userId = user_id();
                            $nonConfig->save();
                        }
                    }

                    $oldScreenItem = SideScreenItem::where(['QuotationId' => $id,'VersionId' => $VersionId])->get();
                    if ($oldScreenItem != null) {
                        foreach ($oldScreenItem as $screen) {
                            $NewScreenInformation = $screen->replicate();
                            $NewScreenInformation->id = SideScreenItem::max('id') + 1;
                            $NewScreenInformation->QuotationId = $NewCreatedQuotationId;
                            $NewScreenInformation->VersionId = $NewQuotationVersionId;
                            $NewScreenInformation->save();

                            // inser into `BOMCalculation`
                            $version_id = QuotationVersion::where('quotation_id', $id)->where('id', $VersionId)->value('version');
                            $ScreenBOMCalculation = ScreenBOMCalculation::where('QuotationId', $id)->where('VersionId', $version_id)->where('ScreenId', $screen->id)->get();

                            if (!empty($ScreenBOMCalculation)) {
                                foreach ($ScreenBOMCalculation as $value) {
                                    $ScreenBOM = $value->replicate();
                                    $ScreenBOM->id = ScreenBOMCalculation::max('id') + 1;
                                    $ScreenBOM->QuotationId = $NewCreatedQuotationId;
                                    $ScreenBOM->VersionId = 1;
                                    $ScreenBOM->ScreenId = $NewScreenInformation->id;
                                    $ScreenBOM->save();
                                }
                            }

                            $OldItemMasterScreen = SideScreenItemMaster::select('side_screen_item_master.*')->join('side_screen_items', 'side_screen_item_master.ScreenID', '=', 'side_screen_items.id')->where('side_screen_item_master.ScreenID', $screen->id)->get();
                            if ($OldItemMasterScreen != null) {
                                foreach ($OldItemMasterScreen as $items) {
                                    //replicate item master data on creating version
                                    $NewItemMasterScreen = $items->replicate();
                                    $NewItemMasterScreen->id = SideScreenItemMaster::max('id') + 1;
                                    $NewItemMasterScreen->ScreenId = $NewScreenInformation->id;
                                    $NewItemMasterScreen->save();
                                }
                            }
                        }
                    }
                }

                $response = [
                    'url' => 'quotation/generate/' . $NewCreatedQuotationId . '/' . $NewQuotationVersionId,
                    'status' => 'success',
                    'message' => 'Quotation copied successfully.',
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Something went wrong. Please try again later.',
                    'error' => "Error in saving new quotation."
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.',
                'error' => "Quotation not found."
            ];
        }

        return response()->json(
            $response,
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function deletequotation(Request $request): void
    {
        $quotationId = $request->quotationId;
        $versionId = $request->versionId;
        $quot = Quotation::select('QuotationGenerationId')->where('id', $quotationId)->first();
        $QuotationGenerationId = $quot->QuotationGenerationId;
        if ($versionId == 0) {
            Quotation::where('id', $quotationId)->delete();
            QuotationShipToInformation::where('QuotationId', $quotationId)->delete();
            QuotationContactInformation::where('QuotationId', $quotationId)->delete();
            $item = Item::where('QuotationId', $quotationId)->get();
            foreach ($item as $rr) {
                $itemId = $rr->itemId;
                ItemMaster::where('itemID', $itemId)->delete();
            }

            Item::where('QuotationId', $quotationId)->delete();
            BOMCalculation::where('QuotationId', $quotationId)->delete();
            $successmsg = 'Quotation ' . $QuotationGenerationId . ' is deleted successfully!';
        } else {
            $QV = QuotationVersion::where('id', $versionId)->first();
            $version = $QV->version;
            BOMCalculation::where('QuotationId', $quotationId)->where('VersionId', $version)->delete();
            QuotationVersion::where('id', $versionId)->delete();
            QuotationVersionItems::where('version_id', $versionId)->delete();

            $QVCount = QuotationVersion::where('quotation_id', $quotationId)->count();
            if ($QVCount == 0) {
                Quotation::where('id', $quotationId)->delete();
                QuotationShipToInformation::where('QuotationId', $quotationId)->delete();
                QuotationContactInformation::where('QuotationId', $quotationId)->delete();
                $item = Item::where('QuotationId', $quotationId)->get();
                foreach ($item as $rr) {
                    $itemId = $rr->itemId;
                    ItemMaster::where('itemID', $itemId)->delete();
                }

                Item::where('QuotationId', $quotationId)->delete();
            }

            $successmsg = 'Quotation ' . $QuotationGenerationId . ' Version-' . $version . ' is deleted successfully!';
        }

        \Session::flash('success', __($successmsg));
        echo json_encode(["status" => "success", 'url' => 'quotation/list']);
    }

    // Accoustic : add images to the options available in the Acoustics section.
    public function showAccoustic(Request $request): string
    {
        $id = $request->id;
        $pageId = $request->pageId;
        $UnderAttribute = $request->UnderAttribute;
        $configurationDoor = configurationDoor($pageId);

        $userType = Auth::user()->UserType;
        if ($userType == "1" || $userType == "4") {

            $OptionsData = GetOptions(['accoustics.' . $configurationDoor => $pageId, 'accoustics.Status' => 1, 'accoustics.UnderAttribute' => $UnderAttribute], "", "Accoustic");
        } else {
            $OptionsData = GetOptions(['accoustics.' . $configurationDoor => $pageId, 'accoustics.Status' => 1, 'accoustics.UnderAttribute' => $UnderAttribute], "join", "Accoustic");
        }

        // dd($OptionsData);

        $data = '
        <div class="container">
        <div class="row">
        ';
        foreach ($OptionsData as $tt) {
            $SelectedOptionCost = $tt->selectedPrice ?? 0;
            $data .= '
            <div class="col-md-2 col-sm-4 col-6 cursor-pointer" onclick="selectAccoustic(\'#' . $id . "' , '" . $tt->Key . "' , '" . $tt->Accoustics . "', '" . $SelectedOptionCost . '\')">
                <div class="color_box">
                    <div class="frameMaterialImage">
                        <img width="100%" height="100" src="' . url('/') . '/uploads/Options/' . $tt->file . '">
                    </div>
                    <h4>' . $tt->Accoustics . '</h4>
                </div>
            </div>
            ';
        }

        $data .= '</div> </div>';

        return $data;
    }


    public function projectfetchCurrency(Request $request): string
    {
        $id = $request->id;
        $pp = Project::select('id', 'projectCurrency')->where('id', $id)->first();
        $projectCurrency = $pp->projectCurrency;
        $cur = '';
        if (!empty($projectCurrency)) {
            $currency = CurrencyBeautify($projectCurrency);

            $cur = '<option value="' . $projectCurrency . '">' . $currency . '</option>';
        } else {
            $currency = SettingCurrency::where('UserId', Auth::user()->id)->first();

            $selcur = '';
            if (!empty($currency->currency)) {
                $selcur = $currency->currency;
            }

            $rr = ['$_US_DOLLAR', '£_GBP', '€_EURO'];
            $i = 0;
            $count = count($rr);
            while ($count > $i) {
                $selected = '';
                if (!empty($currency) && !empty($selcur) && $selcur == $rr[$i]) {
                    $selected = "selected";
                }

                $currency = CurrencyBeautify($rr[$i]);
                $cur .= '<option value="' . $rr[$i] . '" ' . $selected . '>' . $currency . '</option>';

                $i++;
            }
        }

        return $cur;
    }





    public function convertToQuotation($QuotationId = null, $ArchitectGenerationId = null, $ProjectId = null, $CustomerId = null)
    {

        //if($QuotationId == 0){
        $qidFromhelper = GenerateQuotationFirstTime($ProjectId, $CustomerId, $ArchitectGenerationId);
        return redirect()->route('quotation/generate/', [$qidFromhelper, 0]);
        // }
        //  else {
        //     $Quotation = Quotation::where('id',$QuotationId)->first();
        //     $QuotationContactInformation = QuotationContactInformation::where('QuotationId',$QuotationId)->first();
        //     $QuotationShipToInformation = QuotationShipToInformation::where('QuotationId',$QuotationId)->first();
        // }


        if ($Quotation === null) {
            return abort(404);
        }

        // $ProjectTable = '<option value="">Select Project</option>';

        if ($Quotation->MainContractorId != '') {
            $dd = Project::where(['CompanyId' => $Quotation->CompanyId, 'MainContractorId' => $Quotation->MainContractorId])->count();
            if ($dd > 0) {
                if ($Quotation->ProjectId != '') {
                    // it show single project it come from project list when create New Quotation press button `New Quotation`
                    $Projects = Project::where(['CompanyId' => $Quotation->CompanyId, 'id' => $Quotation->ProjectId, 'Status' => 1])->get();
                } else {
                    // when you directly create quotation it show multiple project
                    $Projects = Project::where(['CompanyId' => $Quotation->CompanyId, 'MainContractorId' => $Quotation->MainContractorId, 'Status' => 1])->get();
                }
            } else {
                $Projects = Project::where(['CompanyId' => $Quotation->CompanyId, 'Status' => 1])->get();
            }
        } else {
            $Projects = Project::where(['CompanyId' => $Quotation->CompanyId, 'Status' => 1])->get();
        }
    }


    public function get_contact_details(request $request)
    {
        $array_data = explode(",", $request->id);

        $data = CustomerContact::where('MainContractorId', $array_data[1])->get();
        $selected_data = CustomerContact::where('id', $array_data[0])->first();
        return Response::json(['data' => $data, 'selected_data' => $selected_data]);
    }

    public function copyExistingDoorSet(Request $request)
    {
        //copying existing door type with copy keyword
        if (empty($request->quotationId)) {
            $response = [
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.',
                'error' => "Quotation ID is required."
            ];
            return response()->json(
                $response,
                200,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }

        if ($request->versionId == "") {
            $response = [
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.',
                'error' => "Version ID is required."
            ];
            return response()->json(
                $response,
                200,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }



        $id = $request->quotationId;
        $VersionId = $request->versionId;
        $itemID = $request->id;
        $Quotation = Quotation::find($id);
        if ($Quotation != null) {

            if (!empty($itemID)) {

                // if(!empty($VersionId)){
                $OldQuotationItems = Item::select('items.*')->join('item_master', 'item_master.itemID', 'items.itemId')->where('items.QuotationId', $id)->where('item_master.id', $itemID)->get()->first();

                $door = $OldQuotationItems->DoorType . ' Copy';
                $mm =  Item::where(['QuotationId' => $id, 'DoorType' => $door])->get()->first();
                if (!empty($mm) && $OldQuotationItems->DoorType . ' Copy' == $mm->DoorType) {
                    $errorlist = 'Door Type ' . $mm->DoorType . ' is already exist for these quotation.';
                    $response = [
                        'status' => 'error',
                        'message' => $errorlist,
                        'error' => "Error"
                    ];
                    return response()->json(
                        $response,
                        200,
                        ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                        JSON_UNESCAPED_UNICODE
                    );
                }

                $Items = $OldQuotationItems->replicate();
                $Items->DoorType = $OldQuotationItems->DoorType . ' Copy';
                $Items->itemId = Items::max('itemId') + 1;
                $Items->save();

                $NewQuotationItemId = $Items->itemId;
                $NewDoorType = $Items->DoorType;

                $version_id = QuotationVersion::where('quotation_id', $OldQuotationItems->QuotationId)->where('id', $VersionId)->value('version');

                if ($VersionId == 0) {
                    $BOMCalculation = BOMCalculation::where('QuotationId', $OldQuotationItems->QuotationId)->where('itemId', $OldQuotationItems->itemId)->get();
                } else {
                    $BOMCalculation = BOMCalculation::where('QuotationId', $OldQuotationItems->QuotationId)->where('itemId', $OldQuotationItems->itemId)->where('VersionId', $version_id)->get();
                }

                if ($BOMCalculation != null) {
                    foreach ($BOMCalculation as $IKey => $IVal) {
                        $BOMCalculationItems = $IVal->replicate();
                        $BOMCalculationItems->id = BOMCalculation::max('id') + 1;
                        $BOMCalculationItems->itemId = $NewQuotationItemId;
                        $BOMCalculationItems->DoorType = $NewDoorType;
                        $BOMCalculationItems->save();
                    }
                }

                // }

                $url = 'quotation/add-new-doors/' . $id . '/' . $VersionId;
                $response = [
                    'status' => 'success',
                    'message' => 'Quotation copied successfully.',
                    'url' => $url
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Something went wrong. Please try again later.',
                    'error' => "Error in saving new quotation."
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.',
                'error' => "Quotation not found."
            ];
        }

        return response()->json(
            $response,
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function copyExistingSideScreen(Request $request)
    {
        //copying existing door type with copy keyword
        if (empty($request->quotationId)) {
            $response = [
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.',
                'error' => "Quotation ID is required."
            ];
            return response()->json(
                $response,
                200,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }

        if ($request->versionId == "") {
            $response = [
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.',
                'error' => "Version ID is required."
            ];
            return response()->json(
                $response,
                200,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }

        $id = $request->quotationId;
        $VersionId = $request->versionId;
        $screen_id = $request->id;
        $Quotation = Quotation::find($id);
        if ($Quotation != null) {

            if (!empty($screen_id)) {

                // if(!empty($VersionId)){
                $OldQuotationItems = SideScreenItem::select('side_screen_items.*')->join('side_screen_item_master', 'side_screen_item_master.ScreenId', 'side_screen_items.id')->where('side_screen_items.QuotationId', $id)->where('side_screen_item_master.id', $screen_id)->get()->first();

                $ScreenType = $OldQuotationItems->ScreenType . ' Copy';
                $mm =  SideScreenItem::where(['QuotationId' => $id, 'ScreenType' => $ScreenType])->get()->first();
                if (!empty($mm) && $OldQuotationItems->ScreenType . ' Copy' == $mm->ScreenType) {
                    $errorlist = 'Screen Type ' . $mm->ScreenType . ' is already exist for these quotation.';
                    $response = [
                        'status' => 'error',
                        'message' => $errorlist,
                        'error' => "Error"
                    ];
                    return response()->json(
                        $response,
                        200,
                        ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                        JSON_UNESCAPED_UNICODE
                    );
                }

                $Items = $OldQuotationItems->replicate();
                $Items->ScreenType = $OldQuotationItems->ScreenType . ' Copy';
                $Items->id = SideScreenItem::max('id') + 1;
                $Items->save();

                $NewQuotationScreenId = $Items->id;
                $NewScreenType = $Items->ScreenType;

                $version_id = QuotationVersion::where('quotation_id', $OldQuotationItems->QuotationId)->where('id', $VersionId)->value('version');

                if ($VersionId == 0) {
                    $BOMCalculation = ScreenBOMCalculation::where('QuotationId', $OldQuotationItems->QuotationId)->where('ScreenId', $OldQuotationItems->id)->get();
                } else {
                    $BOMCalculation = ScreenBOMCalculation::where('QuotationId', $OldQuotationItems->QuotationId)->where('ScreenId', $OldQuotationItems->id)->where('VersionId', $version_id)->get();
                }

                if ($BOMCalculation != null) {
                    foreach ($BOMCalculation as $IKey => $IVal) {
                        $BOMCalculationItems = $IVal->replicate();
                        $BOMCalculationItems->id = ScreenBOMCalculation::max('id') + 1;
                        $BOMCalculationItems->ScreenId = $NewQuotationScreenId;
                        $BOMCalculationItems->ScreenType = $NewScreenType;
                        $BOMCalculationItems->save();
                    }
                }


                $url = 'quotation/add-new-screens/' . $id . '/' . $VersionId;
                $response = [
                    'status' => 'success',
                    'message' => 'Screen copied successfully.',
                    'url' => $url
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Something went wrong. Please try again later.',
                    'error' => "Error in saving new quotation."
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Something went wrong. Please try again later.',
                'error' => "Quotation not found."
            ];
        }

        return response()->json(
            $response,
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function doorStandardPrice(Request $request)
    {
        if ($request->fireRating == 'FD30' || $request->fireRating == 'FD30s') {
            $request->fireRating = 'FD30';
        } elseif ($request->fireRating == 'FD60' || $request->fireRating == 'FD60s') {
            $request->fireRating = 'FD60';
        }

        $door_core_size = DB::table('selected_doordimension')->select('selected_doordimension.*')->where('doordimension_user_id', '=', Auth::user()->id)->where('selected_configurableitems', '=', $request->issingleconfiguration)->where('selected_firerating', '=', $request->fireRating)->get();

        $arr = [];
        if (!empty($door_core_size)) {
            foreach ($door_core_size as $door_core) {
                if ($door_core->selected_mm_width >= $request->LeafWidth1 && $door_core->selected_mm_height >= $request->LeafHeightNoOP) {
                    $arr[] = $door_core->selected_cost;
                }
            }
        }

        $door_core = $arr === [] ? 0 : min($arr);

        $doorcoresize = DB::table('selected_doordimension')->select('selected_doordimension.*')->where('doordimension_user_id', '=', Auth::user()->id)->where('selected_configurableitems', '=', $request->issingleconfiguration)->where('selected_firerating', '=', $request->fireRating)->where('selected_cost', $door_core)->first();

        $response = [
            'status' => 'ok',
            'message' => 'Standard Door Core size price.',
            'door_core' => $door_core,
            'selected_mm_width' => $doorcoresize->selected_mm_width,
            'selected_mm_height' => $doorcoresize->selected_mm_height
        ];
        return response()->json(
            $response,
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function IronmongeryIDPrice(Request $request)
    {
        $optionType = $request->optionType;
        switch ($optionType) {
            case 'IronmongeryIDItems':
                $IronmongeryInfo = AddIronmongery::select('*')->where('id', $request->IronmongeryIDValue)->where('UserId', Auth::user()->id)->get()->first();

                $IronmongeryInfoSet = [
                    'Hinges',
                    'FloorSpring',
                    'LocksAndLatches',
                    'FlushBolts',
                    'ConcealedOverheadCloser',
                    'PullHandles',
                    'PushHandles',
                    'KickPlates',
                    'DoorSelectors',
                    'PanicHardware',
                    'Doorsecurityviewer',
                    'Morticeddropdownseals',
                    'Facefixeddropseals',
                    'ThresholdSeal',
                    'AirTransferGrill',
                    'Letterplates',
                    'CableWays',
                    'SafeHinge',
                    'LeverHandle',
                    'DoorSinage',
                    'FaceFixedDoorCloser',
                    'Thumbturn',
                    'KeyholeEscutchen',
                    'DoorStops',
                    'Cylinders'
                ];

                $IronmongeryInfoSetQut = [
                    'hingesQty',
                    'floorSpringQty',
                    'lockesAndLatchesQty',
                    'flushBoltsQty',
                    'concealedOverheadCloserQty',
                    'pullHandlesQty',
                    'pushHandlesQty',
                    'kickPlatesQty',
                    'doorSelectorsQty',
                    'panicHardwareQty',
                    'doorSecurityViewerQty',
                    'morticeddropdownsealsQty',
                    'facefixeddropsealsQty',
                    'thresholdSealQty',
                    'airtransfergrillsQty',
                    'letterplatesQty',
                    'cableWaysQty',
                    'safeHingeQty',
                    'leverHandleQty',
                    'doorSignageQty',
                    'faceFixedDoorClosersQty',
                    'thumbturnQty',
                    'keyholeEscutcheonQty',
                    'DoorStopsQty',
                    'CylindersQty',

                ];

                $unit_cost = 0;
                for ($i = 0; $i <= 24; $i++) {
                    $valIronmongey = $IronmongeryInfoSet[$i];
                    $valIronmongeyQty = $IronmongeryInfoSetQut[$i];
                    if (!empty($IronmongeryInfo->$valIronmongey)) {
                        $SelectedIronmongery = SelectedIronmongery::select('*')->where('id', $IronmongeryInfo->$valIronmongey)->where('user_id', Auth::user()->id)->first();
                        if (!empty($SelectedIronmongery)) {
                            $IronmongeryInfoModel = IronmongeryInfoModel::select('*')->where('id', $SelectedIronmongery->ironmongery_id)->first();
                            $unit_cost += $IronmongeryInfoModel->Price;
                        }
                    }
                }

                $response = [
                    'status' => 'ok',
                    'message' => 'Ironmongery Info price.',
                    'IronmongeryInfo' => round($unit_cost, 2)
                ];
                break;

            case 'IronmongeryIDPrice':
                $IronmongeryInfo = AddIronmongery::select('*')->where('id', $request->IronmongeryIDValue)->where('UserId', Auth::user()->id)->get()->first();

                $IronmongeryInfoSet = [
                    'Hinges',
                    'FloorSpring',
                    'LocksAndLatches',
                    'FlushBolts',
                    'ConcealedOverheadCloser',
                    'PullHandles',
                    'PushHandles',
                    'KickPlates',
                    'DoorSelectors',
                    'PanicHardware',
                    'Doorsecurityviewer',
                    'Morticeddropdownseals',
                    'Facefixeddropseals',
                    'ThresholdSeal',
                    'AirTransferGrill',
                    'Letterplates',
                    'CableWays',
                    'SafeHinge',
                    'LeverHandle',
                    'DoorSinage',
                    'FaceFixedDoorCloser',
                    'Thumbturn',
                    'KeyholeEscutchen',
                    'DoorStops',
                    'Cylinders'
                ];

                $IronmongeryInfoSetQut = [
                    'hingesQty',
                    'floorSpringQty',
                    'lockesAndLatchesQty',
                    'flushBoltsQty',
                    'concealedOverheadCloserQty',
                    'pullHandlesQty',
                    'pushHandlesQty',
                    'kickPlatesQty',
                    'doorSelectorsQty',
                    'panicHardwareQty',
                    'doorSecurityViewerQty',
                    'morticeddropdownsealsQty',
                    'facefixeddropsealsQty',
                    'thresholdSealQty',
                    'airtransfergrillsQty',
                    'letterplatesQty',
                    'cableWaysQty',
                    'safeHingeQty',
                    'leverHandleQty',
                    'doorSignageQty',
                    'faceFixedDoorClosersQty',
                    'thumbturnQty',
                    'keyholeEscutcheonQty',
                    'DoorStopsQty',
                    'CylindersQty',

                ];

                $price = 0;
                for ($i = 0; $i <= 24; $i++) {

                    $valIronmongey = $IronmongeryInfoSet[$i];
                    $valIronmongeyQty = $IronmongeryInfoSetQut[$i];

                    if (!empty($IronmongeryInfo->$valIronmongey)) {

                        $SelectedIronmongery = SelectedIronmongery::select('*')->where('id', $IronmongeryInfo->$valIronmongey)->where('user_id', Auth::user()->id)->first();

                        $array = [];
                        if (!empty($SelectedIronmongery)) {
                            $IronmongeryInfoModel = IronmongeryInfoModel::select('*')->where('IronmongeryId', $SelectedIronmongery->ironmongery_id)->where('UserId', Auth::user()->id)->first();
                            $BOMSetting = BOMSetting::select('*')->where('UserId', Auth::user()->id)->first();

                            $unit_cost = ($IronmongeryInfoModel->ManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($IronmongeryInfoModel->MachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));

                            $price += $unit_cost;
                        }
                    }
                }

                $response = [
                    'status' => 'ok',
                    'message' => 'Ironmongery Info price.',
                    'IronmongeryInfo' => round($price, 2)
                ];
                break;
        }

        return response()->json(
            $response,
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function IronmongeryIDValue(Request $request)
    {
        $optionType = $request->optionType;
        if ($optionType === 'IronmongeryValue') {
            $IronmongeryInfo = AddIronmongery::select('*')->where('id', $request->IronmongeryIDValue)->where('UserId', Auth::user()->id)->get()->first();
            // dd($IronmongeryInfo);
            $IronmongeryInfoSet = [
                'Hinges',
                'FloorSpring',
                'LocksAndLatches',
                'FlushBolts',
                'ConcealedOverheadCloser',
                'PullHandles',
                'PushHandles',
                'KickPlates',
                'DoorSelectors',
                'PanicHardware',
                'Doorsecurityviewer',
                'Morticeddropdownseals',
                'Facefixeddropseals',
                'ThresholdSeal',
                'AirTransferGrill',
                'Letterplates',
                'CableWays',
                'SafeHinge',
                'LeverHandle',
                'DoorSinage',
                'FaceFixedDoorCloser',
                'Thumbturn',
                'KeyholeEscutchen',
                'DoorStops',
                'Cylinders'
            ];
            $IronmongeryInfoModel = [];
            for ($i = 0; $i <= 24; $i++) {
                $valIronmongey = $IronmongeryInfoSet[$i];
                if (!empty($IronmongeryInfo->$valIronmongey)) {
                    $SelectedIronmongery = SelectedIronmongery::select('*')->where('id', $IronmongeryInfo->$valIronmongey)->where('user_id', Auth::user()->id)->first();
                    if (!empty($SelectedIronmongery)) {
                        $IronmongeryInfoModel = IronmongeryInfoModel::select('*')->where('id', $SelectedIronmongery->ironmongery_id)->first();
                    }
                }
            }

            // dd($IronmongeryInfoModel);
            $response = [
                'status' => 'ok',
                'message' => 'Ironmongery Info price.',
                'IronmongeryInfo' => $IronmongeryInfoModel
            ];
        }

        return response()->json(
            $response,
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function generalLabourCost(Request $request)
    {
        $option = $request->option;
        $BOMSetting = BOMSetting::select('*')->where('UserId', Auth::user()->id)->first();
        $GeneralLabourCost = GeneralLabourCost::select('general_labour_cost.*', 'selected_generallabourcost.*', 'selected_generallabourcost.id as generalId', 'general_labour_cost.id')->join('selected_generallabourcost', 'selected_generallabourcost.generalLabourCostId', '=', 'general_labour_cost.id')->where('general_labour_cost.UserId', Auth::user()->id)->first();
        $margin = BOMSetting::where('UserId', Auth::user()->id)->value('margin_for_material');
        if (!empty($GeneralLabourCost) && !empty($BOMSetting->labour_cost_per_man) && !empty($BOMSetting->labour_cost_per_machine)) {
            if (!empty($request->doorsetType)) {
                $doorsetType = $request->doorsetType == 'DD' ? 2 : 1;
            }

            if ($request->fireRating == 'FD30' || $request->fireRating == 'FD30s') {
                $request->fireRating = 'FD30';
            } elseif ($request->fireRating == 'FD60' || $request->fireRating == 'FD60s') {
                $request->fireRating = 'FD60';
            }

            $configurationDoor = configurationDoor($request->issingleconfiguration);
            $fireRatingDoor = fireRatingDoor($request->fireRating);
            switch ($option) {
                case 'doorsetType':
                    $description = [];
                    $price = [];
                    if ($request->doorsetType == 'DD' && $GeneralLabourCost->DoorsetTypeDD == 1) {
                        $description[] = 'Assemble Double Door Leaf Into Frame';
                        $unit_cost = ($GeneralLabourCost->DoorsetTypeDDManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->DoorsetTypeDDMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        $price[] = round($unit_cost, 2);
                    }

                    if ($request->doorsetType == 'SD' && $GeneralLabourCost->DoorsetTypeSD == 1) {
                        $description[] = 'Assemble Single Door Leaf Into Frame';
                        $unit_cost = ($GeneralLabourCost->DoorsetTypeSDManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->DoorsetTypeSDMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        $price[] = round($unit_cost, 2);
                    }

                    if ($request->doorsetType == 'SD' && $GeneralLabourCost->DoorsetTypeSD2 == 1) {
                        $description[] = 'Doorset Delivery Single';
                        $unit_cost = ($GeneralLabourCost->DoorsetTypeSD2ManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->DoorsetTypeSD2MachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        $price[] = round($unit_cost, 2);
                    }

                    if ($request->doorsetType == 'DD' && $GeneralLabourCost->DoorsetTypeDD2 == 1) {
                        $description[] = 'Doorset Delivery Double';
                        $unit_cost = ($GeneralLabourCost->DoorsetTypeDD2ManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->DoorsetTypeDD2MachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        $price[] = round($unit_cost, 2);
                    }

                    break;

                case 'common':
                    if ($GeneralLabourCost->MachiningOfDoorFrame == 1) {
                        $description = [];
                        $price = [];
                        $description[] = 'Machining of door frame';
                        $unit_cost = ($GeneralLabourCost->MachiningOfDoorFrameManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->MachiningOfDoorFrameMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        $price[] = round($unit_cost, 2);
                    }

                    if ($GeneralLabourCost->FittingOfIntumescentToFrame == 1) {
                        $description[] = 'Fitting of intumescent to frame';
                        $unit_cost = ($GeneralLabourCost->FittingOfIntumescentToFrameManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->FittingOfIntumescentToFrameMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        $price[] = round($unit_cost, 2);
                    }

                    if ($GeneralLabourCost->HingeAssembley == 1) {
                        $description[] = 'Hinge Assembley';
                        $unit_cost = ($GeneralLabourCost->HingeAssembleyManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->HingeAssembleyMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        $price[] = round($unit_cost, 2);
                    }

                    if ($GeneralLabourCost->LocksetAssembley == 1) {
                        $description[] = 'Lockset (Standard) - Assembly';
                        $unit_cost = ($GeneralLabourCost->LocksetAssembleyManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->LocksetAssembleyMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        $price[] = round($unit_cost, 2);
                    }

                    if ($GeneralLabourCost->PlotLabelDoorsetsy == 1) {
                        $description[] = 'Plot & Label Door sets';
                        $unit_cost = ($GeneralLabourCost->PlotLabelDoorsetsyManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->PlotLabelDoorsetsMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        $price[] = round($unit_cost, 2);
                    }

                    if ($GeneralLabourCost->FrameAssembley == 1) {
                        $description[] = 'Frame Assembley';
                        $unit_cost = ($GeneralLabourCost->FrameAssembleyManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->FrameAssembleyMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        $price[] = round($unit_cost, 2);
                    }

                    if ($GeneralLabourCost->PalletPackaging == 1) {
                        $description[] = 'Pallet & Packaging';
                        $unit_cost = ($GeneralLabourCost->PalletPackagingManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->PalletPackagingMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        $price[] = round($unit_cost, 2);
                    }

                    if ($GeneralLabourCost->DoorLeafProtectionPlasticSleeve == 1) {
                        $description[] = 'Door Leaf Protection - Plastic Sleeve ';
                        $unit_cost = ($GeneralLabourCost->DoorLeafProtectionPlasticSleeveManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->DoorLeafProtectionPlasticSleeveMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        $price[] = round($unit_cost, 2);
                    }

                    break;

                case 'doorLeafFacing':
                    if (!empty($request->optionName)) {
                        if ($request->optionName == 'Veneer' && $GeneralLabourCost->DoorLeafFacingVaneer == 1) {
                            $description = 'Make Door Slab Veneer';
                            $unit_cost = ($GeneralLabourCost->DoorLeafFacingVaneerManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->DoorLeafFacingVaneerMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                            $price = round($unit_cost, 2);
                        }

                        if ($request->optionName == 'Kraft_Paper' && $GeneralLabourCost->DoorLeafFacingKraftPaper == 1) {
                            $description = 'Make door slab Kraft Paper';
                            $unit_cost = ($GeneralLabourCost->DoorLeafFacingKraftPaperManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->DoorLeafFacingKraftPaperMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                            $price = round($unit_cost, 2);
                        }

                        if ($request->optionName == 'Laminate' && $GeneralLabourCost->DoorLeafFacingLaminate == 1) {
                            $description = 'Make door slab Laminate';
                            $unit_cost = ($GeneralLabourCost->DoorLeafFacingLaminateManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->DoorLeafFacingLaminateMachineMinutes
                                * ($BOMSetting->labour_cost_per_machine / 60));
                            $price = round($unit_cost, 2);
                        }

                        if ($request->optionName == 'PVC' && $GeneralLabourCost->DoorLeafFacingPVC == 1) {
                            $description = 'Make door slab PVC';
                            $unit_cost = ($GeneralLabourCost->DoorLeafFacingPVCManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->DoorLeafFacingPVCMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                            $price = round($unit_cost, 2);
                        }
                    }

                    break;

                case 'doorLeafFinish':
                    if (!empty($request->optionName)) {
                        if ($request->optionName == 'Primed' && $GeneralLabourCost->DoorLeafFinishPrimed == 1) {
                            $description = 'Prime of door slab';
                            $unit_cost = ($GeneralLabourCost->DoorLeafFinishPrimedManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->DoorLeafFinishPrimedMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                            $price = round($unit_cost, 2);
                        }

                        if (($request->optionName == 'Paint_Finish' || $request->optionName == 'Painted') && $GeneralLabourCost->DoorLeafFinishPainted == 1) {
                            $description = 'Paint of door slab';
                            $unit_cost = ($GeneralLabourCost->DoorLeafFinishPaintedManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->DoorLeafFinishPaintedMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                            $price = round($unit_cost, 2);
                        }

                        if ($request->optionName == 'Laqure_Finish' && $GeneralLabourCost->DoorLeafFinishLacquered == 1) {
                            $description = 'Lacquer of door slab';
                            $unit_cost = ($GeneralLabourCost->DoorLeafFinishLacqueredManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->DoorLeafFinishLacqueredMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                            $price = round($unit_cost, 2);
                        }
                    }

                    break;

                case 'frameFinish':
                    if (!empty($request->optionName)) {
                        if ($request->optionName == 'Primed_Only' && $GeneralLabourCost->FrameFinishPrimed == 1) {
                            $description = 'Priming of door frame';
                            $unit_cost = ($GeneralLabourCost->FrameFinishPrimedManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->FrameFinishPrimedMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        }

                        if ($request->optionName == 'Painted_Finish' && $GeneralLabourCost->FrameFinishPainted == 1) {
                            $description = 'Painting of door frame';
                            $unit_cost = ($GeneralLabourCost->FrameFinishPaintedManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->FrameFinishPaintedMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        }

                        if ($request->optionName == 'Clear_Lacquer' && $GeneralLabourCost->FrameFinishLacqured == 1) {
                            $description = 'Lacquering of Door Frame';
                            $unit_cost = ($GeneralLabourCost->FrameFinishLacquredManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->FrameFinishLacquredMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        }

                        $price = round($unit_cost, 2);
                    }

                    break;

                case 'extLiner':
                    if (!empty($request->optionName) && $request->optionName == 'Yes' && $GeneralLabourCost->ExtLiner == 1) {
                        $description = 'Machining of Liner';
                        $unit_cost = ($GeneralLabourCost->ExtLinerManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->ExtLinerMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        $price = round($unit_cost, 2);
                    }

                    break;

                case 'extLinerFramefinish':
                    if (!empty($request->optionName)) {
                        if ($request->optionName == 'Primed_Only' && $GeneralLabourCost->ExtLinerandFrameFinishPrimed == 1) {
                            $description = 'Priming of liner ';
                            $unit_cost = ($GeneralLabourCost->ExtLinerandFrameFinishPrimedManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->ExtLinerandFrameFinishPrimedMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        }

                        if ($request->optionName == 'Clear_Lacquer' && $GeneralLabourCost->ExtLinerandFrameFinishLacqured == 1) {
                            $description = 'Lacquering liner';
                            $unit_cost = ($GeneralLabourCost->ExtLinerandFrameFinishLacquredManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->ExtLinerandFrameFinishLacquredMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        }

                        if ($request->optionName == 'Painted_Finish' && $GeneralLabourCost->ExtLinerandFrameFinishPainted == 1) {
                            $description = 'Painting of liner';
                            $unit_cost = ($GeneralLabourCost->ExtLinerandFrameFinishPaintedManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->ExtLinerandFrameFinishPaintedMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        }
                    }

                    $price = round($unit_cost, 2);
                    break;

                case 'leaf1VisionPanel':
                    if (!empty($request->optionName) && $request->optionName == 'Yes' && $GeneralLabourCost->VisionPanel2 == 1) {
                        $description = 'Maching of Glazing Bead';
                        $unit_cost = ($GeneralLabourCost->VisionPanel2ManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->VisionPanel2MachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        $price = round($unit_cost, 2);
                    }

                    break;

                case 'doorLeafFinish1':
                    if (!empty($request->optionName)) {
                        if ($request->optionName == 'Primed' && $GeneralLabourCost->DoorLeafFinishPrimed2 == 1) {
                            $description = 'Priming of glazing bead';
                            $unit_cost = ($GeneralLabourCost->DoorLeafFinishPrimed2ManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->DoorLeafFinishPrimed2MachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        }

                        if (($request->optionName == 'Paint_Finish' || $request->optionName == 'Painted') && $GeneralLabourCost->DoorLeafFinishPainted2 == 1) {
                            $description = 'Painting of glazing bead';
                            $unit_cost = ($GeneralLabourCost->DoorLeafFinishPainted2ManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->DoorLeafFinishPainted2MachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        }

                        if ($request->optionName == 'Laqure_Finish' && $GeneralLabourCost->DoorLeafFinishLacquered2 == 1) {
                            $description = 'Lacquering of glazing bead';
                            $unit_cost = ($GeneralLabourCost->DoorLeafFinishLacquered2ManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->DoorLeafFinishLacquered2MachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        }
                    }

                    $price = round($unit_cost, 2);
                    break;

                case 'leaf1VisionPanel1':
                    if (!empty($request->optionName) && $request->optionName == 'Yes' && $GeneralLabourCost->VisionPanel == 1) {
                        $description = 'Vision Panel Cut Outs';
                        $unit_cost = ($GeneralLabourCost->VisionPanelManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->VisionPanelMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        $price = round($unit_cost, 2);
                    }

                    break;

                case 'fireRating':
                    if (!empty($request->optionName)) {
                        if (($request->optionName == 'FD30' || $request->optionName == 'FD30s') && $GeneralLabourCost->VisionPanelandFireRatingFD30 == 1) {
                            $description = 'VP (Hockey Stick) - FD30 Fit';
                            $unit_cost = ($GeneralLabourCost->VisionPanelandFireRatingFD30ManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->VisionPanelandFireRatingFD30MachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        }

                        if (($request->optionName == 'FD60' || $request->optionName == 'FD60s') && $GeneralLabourCost->VisionPanelandFireRatingFD60 == 1) {
                            $description = 'VP (Hockey Stick) - FD60 Fit';
                            $unit_cost = ($GeneralLabourCost->VisionPanelandFireRatingFD60ManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->VisionPanelandFireRatingFD60MachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        }
                    }

                    $price = round($unit_cost, 2);
                    break;

                case 'fireRating1':
                    if (!empty($request->optionName)) {
                        if (($request->optionName == 'FD30' || $request->optionName == 'FD30s') && $GeneralLabourCost->VisionPanelandFireRating2FD30 == 1) {
                            $description = 'VP (Flush) - FD30 Fit';
                            $unit_cost = ($GeneralLabourCost->VisionPanelandFireRating2FD30ManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->VisionPanelandFireRating2FD30MachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        }

                        if (($request->optionName == 'FD60' || $request->optionName == 'FD60s') && $GeneralLabourCost->VisionPanelandFireRating2FD60 == 1) {
                            $description = 'VP (Flush) - FD60 Fit';
                            $unit_cost = ($GeneralLabourCost->VisionPanelandFireRating2FD60ManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->VisionPanelandFireRating2FD60MachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                        }
                    }

                    $price = round($unit_cost, 2);
                    break;

                case 'decorativeGroves':
                    if (!empty($request->optionName) && $request->optionName == 'Yes' && $GeneralLabourCost->DecorativeGroves == 1) {
                        $description = 'V Grooves';
                        $unit_cost = ($GeneralLabourCost->DecorativeGrovesManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->DecorativeGrovesMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                    }

                    $price = round($unit_cost, 2);
                    break;

                case 'overpanel':
                    if (!empty($request->optionName) && $request->optionName == 'Fan_Light' && $GeneralLabourCost->OverpanelFanlight == 1) {
                        $description = 'Fanlight Assembley';
                        $unit_cost = ($GeneralLabourCost->OverpanelFanlightManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->OverpanelFanlightMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                    }

                    $price = round($unit_cost, 2);
                    break;

                case 'overpanel1':
                    if (!empty($request->optionName) && $request->optionName == 'Fan_Light' && $GeneralLabourCost->OverpanelFanlightGlazing == 1) {
                        $description = 'Fanlight Glazing';
                        $unit_cost = ($GeneralLabourCost->OverpanelFanlightGlazingManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->OverpanelFanlightGlazingMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                    }

                    $price = round($unit_cost, 2);
                    break;

                case 'sideLight1':
                    if (!empty($request->optionName) && $request->optionName == 'Yes' && $GeneralLabourCost->SideLight == 1) {
                        $description = 'Side light Assembley';
                        $unit_cost = ($GeneralLabourCost->SideLightManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->SideLightMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                    }

                    $price = round($unit_cost, 2);
                    break;

                case 'sideLight11':
                    if (!empty($request->optionName) && $request->optionName == 'Yes' && $GeneralLabourCost->SideLightGlazing == 1) {
                        $description = 'Side Light Glazing';
                        $unit_cost = ($GeneralLabourCost->SideLightGlazingManMinutes * ($BOMSetting->labour_cost_per_man / 60)) + ($GeneralLabourCost->SideLightGlazingMachineMinutes * ($BOMSetting->labour_cost_per_machine / 60));
                    }

                    $price = round($unit_cost, 2);
                    break;
            }
        }

        switch ($option) {
            case 'LeafSet':
                if (!empty($request->issingleconfiguration) && !empty($request->doorLeafFacing) && !empty($request->doorLeafFinish) && !empty($request->lippingSpecies)) {

                    $lippingSpecies = getLippingSpeciesNearTheeknessValue($request->lippingThickness);

                    $unitcost = SelectedLippingSpeciesItems::where('selected_user_id', Auth::user()->id)->where('selected_lipping_species_id', $request->lippingSpecies)->where('selected_thickness', '>=', $lippingSpecies)->get()->first();

                    $door_core_size = DB::table('selected_doordimension')->select('selected_doordimension.*')->where('doordimension_user_id', '=', Auth::user()->id)->where('selected_configurableitems', '=', $request->issingleconfiguration)->where('selected_firerating', '=', $request->fireRating)->get();

                    $arr = [];
                    if (!empty($door_core_size)) {
                        foreach ($door_core_size as $door_core) {
                            if ($door_core->selected_mm_width >= $request->leafWidth1 && $door_core->selected_mm_height >= $request->leafHeightNoOP) {
                                $arr[] = $door_core->selected_cost;
                            }
                        }
                    }

                    $door_core->selected_cost = $arr === [] ? 0 : min($arr);


                    $lm = ($request->leafWidth1 + $request->leafWidth1 + $request->leafHeightNoOP + $request->leafHeightNoOP) / 1000;
                    $thickness_cost = ($request->lippingThickness * $request->doorThickness * $unitcost->selected_price) / 1000000;
                    $painted_cost = (($request->leafHeightNoOP * 2) + 50) / 1000;

                    if ($request->doorLeafFacing == 'Kraft_Paper') {

                        $doorLeafFacing = @collect($SelectedOption)->where("SelectedOptionKey", $request->doorLeafFacing)->first()->SelectedOptionCost;

                        $doorLeafFacingCost = $painted_cost * $doorLeafFacing;

                        $doorLeafFinish = @collect($SelectedOption)->where("SelectedOptionKey", $request->doorLeafFinish)->where("SelectedOptionSlug", "door_leaf_finish")->first()->SelectedOptionCost;
                        $door_cost = ((($request->leafWidth1 / 1000) * ($request->leafHeightNoOP / 1000)) * 2) * $doorLeafFinish;
                    } elseif ($request->doorLeafFacing == 'Veneer') {

                        $doorLeafFacing = DoorLeafFacing::join('selected_door_leaf_facing', 'door_leaf_facing.id', 'selected_door_leaf_facing.doorLeafFacingId')->where('selected_door_leaf_facing.userId', Auth::user()->id)->where('door_leaf_facing.' . $configurationDoor, $request->issingleconfiguration)->where('door_leaf_facing.Key', $request->doorLeafFacingValue)->first();

                        $doorLeafFacingCost = $painted_cost * $doorLeafFacing->selectedPrice;

                        $doorLeafFinish = @collect($SelectedOption)->where("SelectedOptionKey", $request->doorLeafFinish)->where("SelectedOptionSlug", "door_leaf_finish")->first()->SelectedOptionCost;
                        $door_cost = ((($request->leafWidth1 / 1000) * ($request->leafHeightNoOP / 1000)) * 2) * $doorLeafFinish;
                    } elseif ($request->doorLeafFacing == 'Laminate') {

                        $doorLeafFacing = DoorLeafFacing::join('selected_door_leaf_facing', 'door_leaf_facing.id', 'selected_door_leaf_facing.doorLeafFacingId')->where('selected_door_leaf_facing.userId', Auth::user()->id)->where('door_leaf_facing.' . $configurationDoor, $request->issingleconfiguration)->where('door_leaf_facing.Key', $request->doorLeafFacingValue)->first();

                        $doorLeafFacingCost = $painted_cost * $doorLeafFacing->selectedPrice;

                        $doorLeafFinish = Color::where('DoorLeafFacing', $request->doorLeafFacing)->where('ColorName', $request->doorLeafFinish)->get()->first();
                        $door_cost = ((($request->leafWidth1 / 1000) * ($request->leafHeightNoOP / 1000)) * 2) * $doorLeafFinish->ColorCost;
                    } elseif ($request->doorLeafFacing == 'PVC') {

                        $doorLeafFacing = DoorLeafFacing::join('selected_door_leaf_facing', 'door_leaf_facing.id', 'selected_door_leaf_facing.doorLeafFacingId')->where('selected_door_leaf_facing.userId', Auth::user()->id)->where('door_leaf_facing.' . $configurationDoor, $request->issingleconfiguration)->where('door_leaf_facing.Key', $request->doorLeafFacingValue)->first();

                        $doorLeafFacingCost = $painted_cost * $doorLeafFacing->selectedPrice;

                        $doorLeafFinish = Color::where('DoorLeafFacing', $request->doorLeafFacing)->where('ColorName', $request->doorLeafFinish)->get()->first();
                        $door_cost = ((($request->leafWidth1 / 1000) * ($request->leafHeightNoOP / 1000)) * 2) * $doorLeafFinish->ColorCost;
                    }

                    $unit_cost = ($door_core->selected_cost) + ($lm * $thickness_cost) + ($doorLeafFacingCost + $door_cost);
                }

                $price = round($unit_cost, 2);
                break;

            case 'intumescentSealArrangement';
                if (!empty($request->intumescentSealArrangement) && !empty($request->intumescentSealType) && !empty($request->intumescentSealLocation) && !empty($request->intumescentSealColor)) {
                    $IntumescentSealsDetails = SettingIntumescentSeals2::select('id', 'intumescentSeals', 'brand')
                        ->where("id", $request->intumescentSealArrangement)->first();

                    // dd($IntumescentSealsDetails);
                    if (isset($IntumescentSealsDetails->id)) {
                        if ($request->doorsetType == "DD") {
                            $intumescentSealArrangement = $IntumescentSealsDetails->brand . "-" . $request->intumescentSealMeetingEdges;
                        } else {
                            $intumescentSealArrangement = $IntumescentSealsDetails->brand . "-" . $IntumescentSealsDetails->intumescentSeals;
                        }

                        $intumescentSealTypeDetails = GetOptions(["setting_intumescentseals2.id" => $request->intumescentSealArrangement, "selected_intumescentseals2.selected_intumescentseals2_user_id" => Auth::user()->id], "join", "intumescentSealArrangement", "first", ["selected_intumescentseals2.selected_cost", "selected_intumescentseals2.id"]);

                        $unit_cost = $intumescentSealTypeDetails->selected_cost ?: 0;
                    }
                }

                $price = round($unit_cost, 2);
                break;

            case 'glazingSystems';
                if (!empty($request->issingleconfiguration) && !empty($request->glazingSystems) && !empty($request->visionPanelQuantity)) {
                    // $glazing_unit_cost = SelectedOption::where('SelectedUserId', Auth::user()->id)->where('configurableitems',$request->issingleconfiguration)->where('SelectedOptionKey',$request->glazingSystems)->first();

                    $glazing_unit_cost = GlazingSystem::join('selected_glazing_system', 'glazing_system.id', 'selected_glazing_system.glazingId')->where('selected_glazing_system.userId', Auth::user()->id)->where('glazing_system.' . $configurationDoor, $request->issingleconfiguration)->where('glazing_system.Key', $request->glazingSystems)->first();

                    if ($request->visionPanelQuantity == '1') {
                        $QtyPerDoorType = (($request->vP1Width * 4) + ($request->vP1Height1 * 4)) / 1000;
                    } elseif ($request->visionPanelQuantity == '2') {
                        $QtyPerDoorType = (($request->vP1Width * 4) + ($request->vP1Height1 * 4) + ($request->vP1Width * 4) + ($request->vP1Height2 * 4)) / 1000;
                    } elseif ($request->visionPanelQuantity == '3') {
                        $QtyPerDoorType = (($request->vP1Width * 4) + ($request->vP1Height1 * 4) + ($request->vP1Width * 4) + ($request->vP1Height2 * 4) + ($request->vP1Width * 4) + ($request->vP1Height3 * 4)) / 1000;
                    } elseif ($request->visionPanelQuantity == '4') {
                        $QtyPerDoorType = (($request->vP1Width * 4) + ($request->vP1Height1 * 4) + ($request->vP1Width * 4) + ($request->vP1Height2 * 4) + ($request->vP1Width * 4) + ($request->vP1Height3 * 4) + ($request->vP1Width * 4) + ($request->vP1Height4 * 4)) / 1000;
                    } elseif ($request->visionPanelQuantity == '5') {
                        $QtyPerDoorType = (($request->vP1Width * 4) + ($request->vP1Height1 * 4) + ($request->vP1Width * 4) + ($request->vP1Height2 * 4) + ($request->vP1Width * 4) + ($request->vP1Height3 * 4) + ($request->vP1Width * 4) + ($request->vP1Height4 * 4) + ($request->vP1Width * 4) + ($request->vP1Height5 * 4)) / 1000;
                    }

                    $unit_cost = empty($glazing_unit_cost) ? 0 : $glazing_unit_cost->selectedPrice;
                }

                $price = round($unit_cost, 2);
                break;

            case 'glassType':
                if (!empty($request->visionPanelQuantity) && !empty($request->issingleconfiguration) && !empty($request->glassType)) {
                    // $frame_unit_cost = SelectedOption::where('SelectedUserId', Auth::user()->id)->where('configurableitems',$request->issingleconfiguration)->where('SelectedOptionKey',$request->glassType)->first();

                    $frame_unit_cost = GlassType::join('selected_glass_type', 'glass_type.id', 'selected_glass_type.glass_id')->where('selected_glass_type.editBy', Auth::user()->id)->where('glass_type.' . $configurationDoor, $request->issingleconfiguration)->where('glass_type.Key', $request->glassType)->first();
                    $unit_cost = empty($frame_unit_cost) ? 0 : $frame_unit_cost->selectedPrice;
                }

                $price = round($unit_cost, 2);
                break;

            case 'glazingBead':
                if (!empty($request->glazingBeadSpecies) && !empty($request->glazingBeadsThickness)) {
                    $selected_lipping_species = LippingSpecies::where('id', $request->glazingBeadSpecies)->where('Status',1)->get();

                    if (!empty($request->lippingSpecies) && !empty($request->glazingBeadsThickness)) {
                        $glazingBeadsThickness = getLippingSpeciesNearTheeknessValue($request->glazingBeadsThickness);

                        $unitcost = SelectedLippingSpeciesItems::where('selected_user_id', Auth::user()->id)->where('selected_lipping_species_id', $request->lippingSpecies)->where('selected_thickness', '=', $glazingBeadsThickness)->get()->first();
                        if ($unitcost) {
                            $pricePerLM = ($request->glazingBeadsThickness * $request->glazingBeadsHeight * $unitcost->selected_price) / 1000000;
                            if ($request->visionPanelQuantity == '1') {
                                $LMOfGlazing = ($request->vP1Width * 2) + ($request->vP1Height1 * 2);
                            } elseif ($request->visionPanelQuantity == '2') {
                                $LMOfGlazing = ($request->vP1Width * 2) + ($request->vP1Height1 * 2) + ($request->vP1Width * 2) + ($request->vP1Height2 * 2);
                            } elseif ($request->visionPanelQuantity == '3') {
                                $LMOfGlazing = ($request->vP1Width * 2) + ($request->vP1Height1 * 2) + ($request->vP1Width * 2) + ($request->vP1Height2 * 2) + ($request->vP1Width * 2) + ($request->vP1Height3 * 2);
                            } elseif ($request->visionPanelQuantity == '4') {
                                $LMOfGlazing = ($request->vP1Width * 2) + ($request->vP1Height1 * 2) + ($request->vP1Width * 2) + ($request->vP1Height2 * 2) + ($request->vP1Width * 2) + ($request->vP1Height3 * 2) + ($request->vP1Width * 2) + ($request->vP1Height4 * 2);
                            } elseif ($request->visionPanelQuantity == '5') {
                                $LMOfGlazing = ($request->vP1Width * 2) + ($request->vP1Height1 * 2) + ($request->vP1Width * 2) + ($request->vP1Height2 * 2) + ($request->vP1Width * 2) + ($request->vP1Height3 * 2) + ($request->vP1Width * 2) + ($request->vP1Height4 * 2) + ($request->vP1Width * 2) + ($request->vP1Height5 * 2);
                            }

                            $LMOfGlazingSystem = $LMOfGlazing / 1000;
                            $unit_cost = $pricePerLM * $LMOfGlazingSystem;
                        } else {
                            $unit_cost = 0;
                        }
                    } else {
                        $unit_cost = 0;
                    }
                }

                $price = round($unit_cost, 2);
                break;

            case 'overpanel2':
                if ($request->overpanel == 'Fan_Light' && (!empty($request->lippingSpecies) && !empty($request->OpBeadThickness) && !empty($request->opGlazingBeadSpecies))) {
                    $selected_lipping_species = LippingSpecies::where('id', $request->opGlazingBeadSpecies)->where('Status',1)->get()->first();
                    $OpBeadThickness = getLippingSpeciesNearTheeknessValue($request->OpBeadThickness);
                    if (in_array(Auth::user()->UserType, [1, 4])) {
                        $unitcost = LippingSpeciesItems::where('lipping_species_id', $request->lippingSpecies)->where('thickness', '=', $OpBeadThickness)->get()->first();
                    } else {
                        $unitcost = SelectedLippingSpeciesItems::where('selected_user_id', Auth::user()->id)->where('selected_lipping_species_id', $request->lippingSpecies)->where('selected_thickness', '=', $OpBeadThickness)->get()->first();
                    }

                    if (isset($unitcost->id)) {
                        $unitcost_selected_price = $unitcost->selected_price ?: $unitcost->price;
                        $pricePerLM = ($request->OpBeadThickness * $request->OpBeadHeight * $unitcost_selected_price) / 1000000;
                        $LMOfGlazing = $request->oPWidth + $request->oPWidth + $request->oPHeigth + $request->oPHeigth;
                        $LMOfGlazingSystem = $LMOfGlazing / 1000;
                        $unit_cost = $pricePerLM * $LMOfGlazingSystem;
                    }
                }

                $price = round($unit_cost, 2);
                break;

            case 'sideLight2':
                if ($request->sideLight1 == 'Yes' && (!empty($request->SideLight1GlazingBeadSpeciesid) && !empty($request->SlBeadThickness))) {
                    $SlBeadThickness = getLippingSpeciesNearTheeknessValue($request->SlBeadThickness);
                    $unitcost = SelectedLippingSpeciesItems::where('selected_user_id', Auth::user()->id)->where('selected_lipping_species_id', $request->SideLight1GlazingBeadSpeciesid)->where('selected_thickness', '=', $SlBeadThickness)->get()->first();
                    $unitcost_selected_price = $unitcost->selected_price ?: $unitcost->price;
                    $pricePerLM = ($request->SlBeadThickness * $request->SlBeadHeight * $unitcost_selected_price) / 1000000;
                    $LMOfGlazing = $request->SL1Width + $request->SL1Width + $request->SL1Height + $request->SL1Height;
                    $LMOfGlazingSystem = $LMOfGlazing / 1000;
                    $unit_cost = $pricePerLM * $LMOfGlazingSystem;
                    if ($request->sideLight2 == 'Yes') {
                        $unit_cost *= 2;
                    }
                }

                $price = round($unit_cost, 2);
                break;
            case 'sideLight12':
                if ($request->sideLight2 == 'Yes' && (!empty($request->SideLight2GlazingBeadSpeciesid) && !empty($request->SlBeadThickness))) {
                    $SlBeadThickness = getLippingSpeciesNearTheeknessValue($request->SlBeadThickness);
                    $unitcost = SelectedLippingSpeciesItems::where('selected_user_id', Auth::user()->id)->where('selected_lipping_species_id', $request->SideLight2GlazingBeadSpeciesid)->where('selected_thickness', '=', $SlBeadThickness)->get()->first();
                    $unitcost_selected_price = $unitcost->selected_price ?: $unitcost->price;
                    $pricePerLM = ($request->SlBeadThickness * $request->SlBeadHeight * $unitcost_selected_price) / 1000000;
                    $LMOfGlazing = $request->SL2Width + $request->SL2Width + $request->SL2Height + $request->SL2Height;
                    $LMOfGlazingSystem = $LMOfGlazing / 1000;
                    $unit_cost = $pricePerLM * $LMOfGlazingSystem;
                    if ($request->sideLight2 == 'Yes') {
                        $unit_cost *= 2;
                    }
                }

                $price = round($unit_cost, 2);
                break;
        }

        // print_r($description);die;
        if (isset($description) && ($description !== [] && $description !== [] && $description !== [] && ($description !== '' && $description !== '0') && ($description !== '' && $description !== '0') && ($description !== '' && $description !== '0'))) {
            $response = [
                'status' => 'ok',
                'message' => 'General Labour Info price.',
                'description' => $description,
                'price' => $price,
                'margin' => $margin
            ];
        } else {
            $response = [
                'status' => 'ok',
                'message' => 'General Labour Info price.',
                'price' => $price,
                'margin' => $margin
            ];
        }


        return response()->json(
            $response,
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function FrameCost(Request $request)
    {
        $option = $request->option;
        if (!empty($request->frameMaterial) && !empty($request->frameType)) {
            $frameMaterial = LippingSpecies::where('id', $request->frameMaterial)->where('Status',1)->get();
            switch ($option) {
                case 'Rebated_Frame':
                    if ($request->frameType == 'Rebated_Frame') {
                        // $description1 = '[Rebated Frame Head], '.$frameMaterial[0]['SpeciesName'].', '.$request->frameFinish.', '.$request->frameDepth.'mm x '.$request->frameThickness.'mm x '.$request->frameWidth."mm ".$request->frameType." ".$request->rebatedWidth."mm x ".$request->rebatedHeight."mm";

                        $frameThickness = getLippingSpeciesNearTheeknessValue($request->frameThickness);

                        if (in_array(Auth::user()->UserType, [1, 4])) {
                            $unitcost = LippingSpeciesItems::where('lipping_species_id', $request->frameMaterial)->where('thickness', '=', $frameThickness)->get()->first();
                        } else {
                            $unitcost = SelectedLippingSpeciesItems::where('selected_user_id', Auth::user()->id)->where('selected_lipping_species_id', $request->frameMaterial)->where('selected_thickness', '=', $frameThickness)->get()->first();
                        }

                        if (isset($unitcost->id)) {

                            $unitcost_selected_price = $unitcost->selected_price ?: $unitcost->price;

                            $unit_cost = ($request->frameThickness * $request->frameDepth * $unitcost_selected_price) / 1000000;

                            // $description2 = '[Rebated Frame Sides], '.$frameMaterial[0]['SpeciesName'].', '.$request->frameFinish.', '.$request->frameDepth.'mm x '.$request->frameThickness.'mm x '.$request->frameHeight."mm ".$request->frameType." ".$request->rebatedWidth."mm x ".$request->rebatedHeight."mm";
                        }
                    }

                    break;

                case 'Plant_on_Stop':
                    if ($request->frameType == 'Plant_on_Stop') {
                        $frameMaterial = LippingSpecies::where('id', $request->frameMaterial)->where('Status',1)->get();

                        $frameThickness = getLippingSpeciesNearTheeknessValue($request->frameThickness);

                        $plantonStopHeight = getLippingSpeciesNearTheeknessValue($request->plantonStopHeight);


                        if (in_array(Auth::user()->UserType, [1, 4])) {

                            $unitcost = LippingSpeciesItems::where('lipping_species_id', $request->frameMaterial)->where('thickness', '=', $frameThickness)->get()->first();

                            $unitcostPlantonStopHeight = LippingSpeciesItems::where('lipping_species_id', $request->frameMaterial)->where('thickness', '=', $plantonStopHeight)->get()->first();
                        } else {
                            $unitcost = SelectedLippingSpeciesItems::where('selected_user_id', Auth::user()->id)->where('selected_lipping_species_id', $request->frameMaterial)->where('selected_thickness', '=', $frameThickness)->get()->first();

                            $unitcostPlantonStopHeight = SelectedLippingSpeciesItems::where('selected_user_id', Auth::user()->id)->where('selected_lipping_species_id', $request->frameMaterial)->where('selected_thickness', '>=', $plantonStopHeight)->get()->first();
                        }

                        if (isset($unitcost->id) && isset($unitcostPlantonStopHeight->id)) {

                            $unitcost_selected_price = $unitcost->selected_price ?: $unitcost->price;

                            $unitcostPlantonStopHeight_selected_price = $unitcostPlantonStopHeight->selected_price ?: $unitcostPlantonStopHeight->price;

                            $unit_cost1 = ($request->frameThickness * $request->frameDepth * $unitcost_selected_price) / 1000000;

                            // $description1 = '[Head], '.$frameMaterial[0]['SpeciesName'].', '.$request->frameFinish.', '.$request->frameDepth.'mm x '.$request->frameThickness.'mm x '.$request->frameWidth."mm ";


                            // $description2 = '[Plant On Stop Head], '.$frameMaterial[0]['SpeciesName'].', '.$request->frameFinish.', '.$request->plantonStopWidth.'mm x '.$request->plantonStopHeight.'mm x '. $request->frameWidth.'mm '.str_replace('_', ' ',  $request->frameType);

                            $unit_cost2 = ($request->plantonStopWidth * $request->plantonStopHeight * $unitcostPlantonStopHeight_selected_price) / 1000000;


                            // $description3 = '[Sides], '.$frameMaterial[0]['SpeciesName'].', '.$request->frameFinish.', '.$request->frameDepth.'mm x '.$request->frameThickness.'mm x '. $request->frameHeight.'mm';
                            $QtyPerDoorType = ($request->frameHeight / 1000) * 2;
                            $unit_cost3 = ($request->frameThickness * $request->frameDepth * $unitcost_selected_price) / 1000000;


                            // $description4 = '[Plant On Stop Sides], '.$frameMaterial[0]['SpeciesName'].', '.$request->frameFinish.', '.$request->plantonStopWidth.'mm x '.$request->plantonStopHeight.'mm x '. $request->frameHeight.'mm '.str_replace('_', ' ',  $request->frameType);
                            $QtyPerDoorType = ($request->frameHeight / 1000) * 2;
                            $unit_cost4 = ($request->plantonStopWidth * $request->plantonStopHeight * $unitcostPlantonStopHeight_selected_price) / 1000000;
                        }
                    }

                    $price1 = round($unit_cost1, 2);
                    $price2 = round($unit_cost2, 2);
                    $price3 = round($unit_cost3, 2);
                    $price4 = round($unit_cost4, 2);

                    $response = [
                        'status' => 'ok',
                        'message' => 'Plant on Stop Info price.',
                        'price1' => $price1, 'price2' => $price2, 'price3' => $price3, 'price4' => $price4
                    ];

                    // 'description1' => $description1,'description2' => $description2,'description3' => $description3,'description4' => $description4,
                    return response()->json(
                        $response,
                        200,
                        ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                        JSON_UNESCAPED_UNICODE
                    );
                    break;

                case 'extLiner':
                    if ($request->extLiner == 'Yes') {
                        $extLinerThickness = getLippingSpeciesNearTheeknessValue($request->extLinerThickness);

                        if (in_array(Auth::user()->UserType, [1, 4])) {

                            $unitcost = LippingSpeciesItems::where('lipping_species_id', $request->frameMaterial)->where('thickness', '=', $extLinerThickness)->get()->first();
                        } else {
                            $unitcost = SelectedLippingSpeciesItems::where('selected_user_id', Auth::user()->id)->where('selected_lipping_species_id', $request->frameMaterial)->where('selected_thickness', '=', $extLinerThickness)->get()->first();
                        }

                        if (isset($unitcost->id)) {

                            $unitcost_selected_price = $unitcost->selected_price ?: $unitcost->price;

                            // $description = '[Fanlight Top/Bottom], '.$frameMaterial[0]['SpeciesName'].', '.$request->frameFinish.', '.$request->extLinerValue.'mm x '.$request->extLinerThickness."mm x ".$request->frameWidth."mm";
                            $unit_cost = ($request->extLinerThickness * $request->frameDepth * $unitcost_selected_price) / 1000000;
                            // $QtyPerDoorType = $request->frameWidth/1000;

                            // $description = '[Sidelights sides], '.$frameMaterial[0]['SpeciesName'].', '.$request->frameFinish.', '.$request->extLinerValue.'mm x '.$request->extLinerThickness."mm x ".$request->frameHeight."mm";
                            // $QtyPerDoorType = ($request->frameHeight/1000)*2;
                        }
                    }

                    break;

                case 'sideLight3':
                    if ($request->sideLight1 == 'Yes') {

                        $frameThickness = getLippingSpeciesNearTheeknessValue($request->frameThickness);

                        if (in_array(Auth::user()->UserType, [1, 4])) {

                            $unitcost = LippingSpeciesItems::where('lipping_species_id', $request->frameMaterial)->where('thickness', '=', $frameThickness)->get()->first();
                        } else {
                            $unitcost = SelectedLippingSpeciesItems::where('selected_user_id', Auth::user()->id)->where('selected_lipping_species_id', $request->frameMaterial)->where('selected_thickness', '=', $frameThickness)->get()->first();
                        }

                        if (isset($unitcost->id)) {

                            $unitcost_selected_price = $unitcost->selected_price ?: $unitcost->price;

                            $unit_cost = ($request->frameThickness * $request->frameDepth * $unitcost_selected_price) / 1000000;

                            // $description = '[Sidelight Top/Bottom], '.$frameMaterial[0]['SpeciesName'].', '.$request->frameFinish.', '.$request->frameDepth.'mm x '.$request->frameThickness.'mm x '.$request->SL1Width."mm";

                            // $description = '[Sidelight Sides], '.$frameMaterial[0]['SpeciesName'].', '.$request->frameFinish.', '.$request->frameDepth.'mm x '.$request->frameThickness.'mm x '.$request->SL1Height."mm";

                            if (empty($request->SL2Height)) {
                                $request->SL2Height = 0;
                            }

                            $QtyPerDoorType = (($request->SL1Height * 2) + ($request->SL2Height * 2)) / 1000;
                        }
                    }

                    break;

                case 'overpanel3':
                    if ($request->overpanel == 'Fan_Light') {
                        $OPLippingThickness = getLippingSpeciesNearTheeknessValue($request->OPLippingThickness);


                        if (in_array(Auth::user()->UserType, [1, 4])) {

                            $unitcost = LippingSpeciesItems::where('lipping_species_id', $request->frameMaterial)->where('thickness', '=', $OPLippingThickness)->get()->first();
                        } else {
                            $unitcost = SelectedLippingSpeciesItems::where('selected_user_id', Auth::user()->id)->where('selected_lipping_species_id', $request->frameMaterial)->where('selected_thickness', '>=', $OPLippingThickness)->get()->first();
                        }

                        if (isset($unitcost->id)) {

                            $unitcost_selected_price = $unitcost->selected_price ?: $unitcost->price;

                            // $description = '[Fanlight side1], '.$frameMaterial[0]['SpeciesName'].', '.$request->frameFinish.', '.$request->frameDepth.'mm x '.$request->frameThickness.'mm x '.$request->oPHeigth."mm";
                            $unit_cost = ($request->OPLippingThickness * $request->frameDepth * $unitcost_selected_price) / 1000000;
                        }
                    }

                    break;
            }
        }

        $price = round($unit_cost, 2);

        $response = [
            'status' => 'ok',
            'message' => 'Frame Info price.',
            'price' => $price
        ];

        return response()->json(
            $response,
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function editImage(Request $request)
    {
        if ($request->versionId == 0) {
            $data =  Item::select('itemId')->where(['items.QuotationId' => $request->quotationId])->get();
        } else {
            $data =  Item::select('items.itemId')->join('quotation_version_items', 'quotation_version_items.itemID', 'items.itemId', 'left')->where(['items.QuotationId' => $request->quotationId, 'quotation_version_items.version_id' => $request->versionId])->get();
        }

        $data1 = [];
        foreach ($data as $value) {
            $data1[] = $value->itemId;
        }

        $response = [
            'status' => 'ok',
            'message' => 'Data found successfully',
            'data' => $data1
        ];

        return response()->json(
            $response,
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function editImage1(Request $request)
    {
        $item =  Item::where(['items.QuotationId' => $request->quotationId, 'items.itemId' => $request->id])->first();
        $UserIds = CompanyUsers();
        if ($item === null) {
            return abort(404);
        }

        $item = $item->toArray();
        // $LippingSpeciesData = LippingSpecies::where(['Status' => 1])->get();

        $ConfigurableDoorFormulaData = ConfigurableDoorFormula::where('status', 1)->get();
        $OptionsData = Option::where(['configurableitems' => 1, 'is_deleted' => 0])->get();
        $intumescentSealArrangement = GetOptions(['setting_intumescentseals2.configurableitems' => 1], "", "intumescentSealArrangement");

        $LippingSpeciesData = GetOptions(['lipping_species.Status' => 1], "join", "lippingSpecies");
        // $SelectedLippingSpeciesData = $LippingSpeciesData;

        $configurationDoor = configurationDoor(1);
        $UserType = Auth::user()->UserType;
        if (in_array($UserType, [1, 4])) {
            $UserId = $item['UserId'];
            $SelectedOptionsData = $OptionsData;
            $intumescentSealColor = IntumescentSealColor::where([$configurationDoor => 1, 'Status' => 1])->wherein('editBy', $UserIds)->get();
            $ArchitraveType = ArchitraveType::where([$configurationDoor => 1, 'Status' => 1])->wherein('editBy', $UserIds)->get();
        } else {
            $UserId = Auth::user()->id;

            $SelectedOptionsData = GetOptions(['options.configurableitems' => 1, 'options.is_deleted' => 0, 'selected_option.SelectedUserId' => $UserId], "join");

            $intumescentSealColor = GetOptions(['intumescent_seal_color.' . $configurationDoor => 1, 'intumescent_seal_color.Status' => 1], "join", "intumescent_seal_color");

            $ArchitraveType = GetOptions(['architrave_type.' . $configurationDoor => 1, 'architrave_type.Status' => 1], "join", "architrave_type");
        }



        // dd($OptionsData->toArray());

        $SelectedIntumescentSealArrangement = GetOptions(['selected_intumescentseals2.selected_configurableitems' => 1, 'selected_intumescentseals2.selected_intumescentseals2_user_id' => $UserId], "join", "intumescentSealArrangement");


        $SelectedLippingSpeciesQuery = SelectedLippingSpeciesItems::where([['selected_lipping_species_items.selected_user_id', '=', $UserId]]);
        $SelectedLippingSpeciesIds = array_column($SelectedLippingSpeciesQuery->groupBy("selected_lipping_species_id")->get()->toArray(), "id");

        $SelectedLippingSpeciesData = GetOptions(['lipping_species.Status' => 1], "join", "lippingSpecies", "query");
        $SelectedLippingSpeciesData = $SelectedLippingSpeciesData->whereIn("lipping_species.id",  $SelectedLippingSpeciesIds)->get();



        // dd($SelectedOptionsData);

        $ColorData = Color::where(['Status' => 1])->get();
        $company_data = Company::join('users', 'users.id', 'companies.UserId')->select('users.*')->get();
        $tooltip = Tooltip::first();
        $quotation = Quotation::where(['id' => $item["QuotationId"]])->first();
        $CompanyId = null;
        if ($quotation != '') {
            $CompanyId = $quotation->CompanyId;
        }

        // if(!empty($quotation->ProjectId)){
        //     $setIronmongery = AddIronmongery::where('ProjectId',$quotation->ProjectId)->get();
        // } else {
        //     $setIronmongery = null;
        // }
        $setIronmongery = AddIronmongery::where(['UserId' => Auth::user()->id])->orderBy('Setname', 'ASC')->get();

        $BOMSetting = BOMSetting::where("id", 1)->get()->first();

        // dd(\Config::get('constants.PossibleSelectedOptions'));

        $returnHTML = view('Items/EditSvgImage', [
            "QuotationId" => $item["QuotationId"],
            'Item' => $item,
            'option_data' => $OptionsData,
            'selected_option_data' => $SelectedOptionsData,
            'intumescentSealColor' => $intumescentSealColor,
            'ArchitraveType' => $ArchitraveType,
            'intumescentSealArrangement' => $intumescentSealArrangement,
            'SelectedIntumescentSealArrangement' => $SelectedIntumescentSealArrangement,
            'color_data' => $ColorData,
            'lipping_species' => $LippingSpeciesData,
            'selected_lipping_species' => $SelectedLippingSpeciesData,
            'ConfigurableDoorFormula' => $ConfigurableDoorFormulaData,
            'company_list' => $company_data,
            'issingleconfiguration' => '1',
            'versionId' => $request->versionId,
            'tooltip' => $tooltip,
            'setIronmongery' => $setIronmongery,
            'BOMSetting' => $BOMSetting,
            'quotation' => $quotation,
        ])->render();
        $response = [
            'status' => true,
            'html' => $returnHTML
        ];

        return response()->json(
            $response,
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function favoriteItem(Request $request)
    {
        if (empty($request->versionId)) {
            $request->versionId = 0;
        }

        // echo $request->itemId;die;
        if (!empty($request->itemId) && !empty($request->itemMasterId) && !empty($request->quotationId)) {
            $Favorite = FavoriteItem::where('itemId', $request->itemId)->where('itemMasterId', $request->itemMasterId)->where('quotationId', $request->quotationId)->where('versionId', $request->versionId)->where('userId', Auth::user()->id)->get()->first();
            if (empty($Favorite)) {
                $FavoriteItem = new FavoriteItem();
                $FavoriteItem->itemId = $request->itemId;
                $FavoriteItem->itemMasterId = $request->itemMasterId;
                $FavoriteItem->quotationId = $request->quotationId;
                $FavoriteItem->versionId = $request->versionId;
                $FavoriteItem->DoorType = $request->doorTypeName;
                $FavoriteItem->userId = Auth::user()->id;
                $FavoriteItem->save();

                if (!empty($FavoriteItem->id)) {
                    $response = [
                        'status' => true,
                        'msg' => 'Door added successfully!'
                    ];
                } else {
                    $response = [
                        'status' => false,
                        'msg' => 'something went wrong!'
                    ];
                }
            } else {
                $response = [
                    'status' => false,
                    'msg' => 'Door already selected for this quotation!'
                ];
            }

            return response()->json(
                $response,
                200,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        } else {
            $response = [
                'status' => false,
                'msg' => 'something went wrong!'
            ];

            return response()->json(
                $response,
                200,
                ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                JSON_UNESCAPED_UNICODE
            );
        }
    }

    public function favoriteDeleteItem(Request $request)
    {
        if (!empty($request->id)) {
            $data = FavoriteItem::find($request->id)->delete();
            if ($data) {
                $response = [
                    'status' => true,
                    'QuotationId' => $request->qId,
                    'VersionId' => $request->vId,
                    'msg' => 'Door added successfully!'
                ];
            } else {
                $response = [
                    'status' => false,
                    'msg' => 'something went wrong!'
                ];
            }
        } else {
            $response = [
                'status' => false,
                'msg' => 'something went wrong!'
            ];
        }

        return response()->json(
            $response,
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function favoriteItemAdd(Request $request)
    {

        if (!empty($request->itemId) && !empty($request->itemMasterId) && !empty($request->quotationId)) {

            if ($request->quotationId != $request->qId && ($request->versionId != $request->vId || ($request->versionId == 0 && $request->vId == 0))) {

                $version_id = QuotationVersion::where('quotation_id', $request->qId)->where('id', $request->vId)->value('version');
                $old_version_id = QuotationVersion::where('quotation_id', $request->quotationId)->where('id', $request->versionId)->value('version');
                if (empty($version_id)) {
                    $version_id = 1;
                }

                if (empty($request->versionId)) {
                    $request->versionId = 0;
                }

                $userId = CompanyMultiUsers();
                $Favorite = FavoriteItem::where('itemId', $request->itemId)->where('itemMasterId', $request->itemMasterId)->where('quotationId', $request->quotationId)->where('versionId', $request->versionId)->wherein('userId', $userId)->get()->first();
                if (!empty($Favorite)) {
                    $item = Item::where('itemId', $Favorite->itemId)->get()->first();
                    $itemMaster = ItemMaster::where('id', $Favorite->itemMasterId)->get()->first();
                    $version_ids = QuotationVersion::where('quotation_id', $request->qId)->where('id', $request->vId)->value('version');
                    $itemListValidate = Item::where(['items.QuotationId' => $request->qId, 'items.DoorType' => $item->DoorType, 'items.VersionId' => $request->vId])->count();
                    // if(empty($version_ids)){
                    //     $itemListValidate = Item::join('item_master','item_master.itemID','items.itemId')->where(['items.QuotationId' => $request->qId, 'items.DoorType' => $item->DoorType,'item_master.doorNumber' => $itemMaster->doorNumber])->count();
                    // }else{
                    //     $itemListValidate = Item::join('quotation_version_items','quotation_version_items.itemID','items.itemId')->join('item_master','item_master.itemID','items.itemId')->where(['items.QuotationId' => $request->qId, 'items.DoorType' => $item->DoorType,'item_master.doorNumber' => $itemMaster->doorNumber, 'quotation_version_items.version_id'=>$request->vId])->count();
                    // }
                    if ($itemListValidate > 0) {
                        $response = [
                            'status' => false,
                            'msg' => 'Door Type ' . $item->DoorType . ' is already exist for these quotation.'
                            // 'msg'=> 'Door Type '.$item->DoorType.' and Door number '.$itemMaster->doorNumber.' is already exist for these quotation.
                        ];
                        return response()->json(
                            $response,
                            200,
                            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
                            JSON_UNESCAPED_UNICODE
                        );
                    } else {
                        $item = Item::where('itemId', $Favorite->itemId)->get()->first();
                        if (!empty($item)) {
                            $NewItemInformation = $item->replicate();
                            $NewItemInformation->itemId = Item::max('itemId') + 1;
                            $NewItemInformation->QuotationId = $request->qId;
                            $NewItemInformation->VersionId = $request->vId;
                            $NewItemInformation->save();

                            // $OldItemMasterInformation = ItemMaster::find($Favorite->itemMasterId);
                            // if($OldItemMasterInformation != null){
                            //     $NewItemMasterInformation = $OldItemMasterInformation->replicate();
                            //     $NewItemMasterInformation->id = ItemMaster::max('id') + 1;
                            //     $NewItemMasterInformation->itemID = $NewItemInformation->itemId;
                            //     $NewItemMasterInformation->save();


                            //     if($request->vId == 0){
                            //         $request->vId = 1;
                            //     }else{
                            //         $QV = new QuotationVersionItems();
                            //         $QV->version_id = $request->vId;
                            //         $QV->itemID = $NewItemInformation->itemId;
                            //         $QV->itemmasterID = $NewItemMasterInformation->id;
                            //         $QV->save();
                            //     }

                            //$qId and $vId is current quotation Ids
                            // $BOMCalculation = BOMCalculation::where('QuotationId', $request->quotationId)->where('VersionId', $old_version_id)->where('itemId', $request->itemId)->get();
                            // if (!empty($BOMCalculation)) {
                            //     foreach ($BOMCalculation as $value) {
                            //         $BOM = $value->replicate();
                            //         $BOM->id = BOMCalculation::max('id') + 1;
                            //         $BOM->QuotationId = $request->qId;
                            //         $BOM->VersionId = $version_id;
                            //         $BOM->itemId = $NewItemInformation->itemId;
                            //         $BOM->save();
                            //     }
                            //     // BOMQuatityOfDoorUpdate($NewItemInformation->itemId, $request->qId);
                            //     $data = Item::where(['items.QuotationId' => $request->quotationId, 'items.itemId' => $request->itemId])->first();

                            //     $quotation = Quotation::where('id',$request->quotationId)->first();

                            //     BOMUpdate($data, $quotation->configurableitems);
                            // }

                            //adding configurableitems like streboard etc to quotation table
                            $quotationConfigurableitems = Quotation::where('id', $request->quotationId)->first();
                            $quotation = Quotation::where('id', $request->qId)->first();
                            if (!empty($quotation)) {
                                $quotation->configurableitems = $quotationConfigurableitems->configurableitems;
                                $quotation->save();
                            }

                            // }
                            $response = [
                                'status' => true,
                                'QuotationId' => $request->qId,
                                'VersionId' => $request->vId,
                                'msg' => 'Door added successfully!'
                            ];
                        } else {
                            $response = [
                                'status' => false,
                                'msg' => 'something went wrong!'
                            ];
                        }
                    }
                } else {
                    $response = [
                        'status' => false,
                        'msg' => 'Data not found!'
                    ];
                }
            } else {
                $response = [
                    'status' => false,
                    'msg' => 'Door already added!'
                ];
            }
        } else {
            $response = [
                'status' => false,
                'msg' => 'something went wrong!'
            ];
        }

        return response()->json(
            $response,
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function doorListShow($id, string $vid)
    {
        //door list this will show all records with that door type is not created in add door type form
        $cc = '';
        if (isset($id)) {
            if ($vid > 0) {
                $aa = Item::join('item_master', 'items.itemId', 'item_master.itemID')->where('QuotationId', $id)->where('VersionId', $vid)->select('item_master.*', 'items.*')->orderBy('items.itemId', 'ASC')->get();
            } else {
                $aa = Item::join('item_master', 'items.itemId', 'item_master.itemID')->where(['items.QuotationId' => $id])->orderBy('id', 'desc')->get();
            }

            $q = Quotation::select('configurableitems')->where('id', $id)->first();
            $i = 1;
            $tbl = '';

            foreach ($aa as $row) {
                $doorNumber = '';
                if (!empty($row->doorNumber)) {
                    $doorNumber = '<td><a href="' . ConfigurationURL($q->configurableitems, $row->itemId, $vid) . '">' . $row->doorNumber . '</a></td>';
                    $delete = '<td><a onclick="doorListAjax(' . $row->itemId . ',' . $row->id . ',' . $vid . ')" href="javascript:void(0);" class="btn btn-danger">Delete <i class="fa fa-trash"></i></a></td>';
                } else {
                    $doorNumber = '<td></td>';
                    $delete = '<td><a onclick="doorListAjax(' . $row->itemId . ',0,' . $vid . ')" href="javascript:void(0);" class="btn btn-danger">Delete <i class="fa fa-trash"></i></a></td>';
                }

                $tbl .=
                    '
                <tr>
                    <td>' . $i . '</td>
                    ' . $doorNumber . '
                    <td>' . $row->DoorType . '</td>
                    <td>' . $row->floor . '</td>
                    <td>' . $row->FireRating . '</td>
                    <td>' . $row->Leaf1VisionPanel . '</td>
                    <td>' . $row->SOWidth . '</td>
                    <td>' . $row->SOHeight . '</td>
                    <td>' . $row->DoorLeafFacing . '</td>
                    ' . $delete . '
                </tr>';
                $i++;
            }

            return view('DoorSchedule.DoorListView', ['tbl' => $tbl, 'vid' => $vid]);
        } else {
            return redirect()->route('quotation/add');
        }
    }

    public function doorListDelete(Request $request)
    {
        //delete records according to item id or item master id
        if ($request->itemmasterId == 0) {
            Item::where('itemId', $request->id)->where('VersionId', $request->vid)->delete();
        } else {
            Item::where('itemId', $request->id)->where('VersionId', $request->vid)->delete();
            ItemMaster::where('id', $request->itemmasterId)->delete();
        }

        BOMCalculation::where('QuotationId', $request->qId)->where('itemId', $request->id)->delete();

        $response = [
            'status' => 'success',
            'message' => 'Door Type Deleted Successfully!'
        ];

        return response()->json(
            $response,
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function surveyReport(array $id)
    {
        // $quotation = Quotation::select('project.*','quotation.*','customers.CstCompanyName')->leftjoin('project','quotation.ProjectId','=','project.id')->leftjoin('customers','customers.UserId','quotation.MainContractorId')->where('quotation.id',$id)->first();
        // $data = BOMCalculation::where('QuotationId',$id)->get();
        // $pdf = PDF::loadView('DoorSchedule.BOM.BOM_pdf',compact('data','quotation'));

        $survey = Project::join("quotation_versions", function ($join): void {
            $join->on("quotation_versions.id", "=", "project.versionId")
                ->on("quotation_versions.quotation_id", "=", "project.quotationId");
        })
            ->join('quotation_version_items', 'quotation_version_items.version_id', 'quotation_versions.id')
            ->join('survey_status', 'quotation_version_items.itemmasterID', 'survey_status.itemMasterId')
            ->join('survey_info', 'survey_info.projectId', 'project.id')
            ->select('survey_status.*', 'project.*', 'survey_info.*', 'survey_info.created_at as surveyDate')->where('project.id', $id)->groupBy('survey_status.itemMasterId')->first();

        $surveyUser = User::join('survey_info', 'survey_info.userId', 'users.id')->select('users.*')->where('survey_info.projectId', $id)->first();

        $company = User::join('companies', 'companies.UserId', 'users.id')->select('users.*', 'companies.*')->wherein('users.id', CompanyUsers())->first();

        $survey_changerequest = Project::join('survey_changerequest', 'survey_changerequest.projectId', 'project.id')->join('items', 'survey_changerequest.itemId', 'items.itemId')->join('item_master', 'survey_changerequest.itemMasterId', 'item_master.id')->select('survey_changerequest.*', 'items.DoorType', 'items.SOWidth', 'items.DoorType', 'items.DoorType', 'item_master.doorNumber')->where('survey_changerequest.projectId', $id)->get();

        $survey_changerequest_count = Project::join('survey_changerequest', 'survey_changerequest.projectId', 'project.id')->join('items', 'survey_changerequest.itemId', 'items.itemId')->join('item_master', 'survey_changerequest.itemMasterId', 'item_master.id')->select('survey_changerequest.*', 'items.DoorType', 'items.SOWidth', 'items.DoorType', 'items.DoorType', 'item_master.doorNumber')->where('survey_changerequest.projectId', $id)->count();

        $door_details = Project::join("quotation_versions", function ($join): void {
            $join->on("quotation_versions.id", "=", "project.versionId")
                ->on("quotation_versions.quotation_id", "=", "project.quotationId");
        })
            ->join('quotation_version_items', 'quotation_version_items.version_id', 'quotation_versions.id')
            ->join('items', 'quotation_version_items.itemID', 'items.itemId')->join('item_master', 'quotation_version_items.itemmasterID', 'item_master.id')
            ->join('survey_info', 'survey_info.projectId', 'project.id')
            ->select('items.*', 'item_master.doorNumber', 'item_master.location')->where('project.id', $id)->get();

        $project_building_details = Project::join('project_building_details', 'project_building_details.projectId', 'project.id')->where('project_building_details.projectId', $id)->count();

        $door_set = Project::join("quotation_versions", function ($join): void {
            $join->on("quotation_versions.id", "=", "project.versionId")
                ->on("quotation_versions.quotation_id", "=", "project.quotationId");
        })
            ->join('quotation_version_items', 'quotation_version_items.version_id', 'quotation_versions.id')
            ->join('items', 'quotation_version_items.itemID', 'items.itemId')->join('item_master', 'quotation_version_items.itemmasterID', 'item_master.id')
            ->join('survey_info', 'survey_info.projectId', 'project.id')
            ->select('items.*', 'item_master.doorNumber', 'item_master.location')->where('project.id', $id)->count();

        $contact = Project::join('quotation_contact_information', 'quotation_contact_information.QuotationId', 'project.quotationId')->where('project.id', $id)->first();

        $Project = Project::where(['id' => $id, 'Status' => 1])->first();

        $id = '';
        $site_contact = '';
        if (!empty($contact)) {
            $id = explode(',', (string) $contact->Contact);
            $site_contact = CustomerContact::where('id', $id[0])->first();
        }


        $pdf = PDF::loadView('Project.surveyReport', ['survey' => $survey, 'company' => $company, 'survey_changerequest' => $survey_changerequest, 'door_details' => $door_details, 'surveyUser' => $surveyUser, 'survey_changerequest_count' => $survey_changerequest_count, 'project_building_details' => $project_building_details, 'door_set' => $door_set, 'site_contact' => $site_contact, 'contact' => $contact, 'Project' => $Project])->setOptions(['defaultFont' => 'sans-serif']);
        return $pdf->download('surveyReport.pdf');
    }

    public function adjustPriceUrl(Request $request)
    {
        if (!empty($request->itemId) && !empty($request->quotationId)) {
            $item = Item::where(['itemId' => $request->itemId, 'QuotationId' => $request->quotationId])->first();
            if (!empty($item)) {
                $updateDetails['AdjustPrice'] = $request->AdjustPrice;
                Item::where('itemId', $request->itemId)->update($updateDetails);
                $response = [
                    'status' => true,
                    'msg' => 'Price updated successfully!'
                ];
            } else {
                $response = [
                    'status' => false,
                    'msg' => 'something went wrong!'
                ];
            }
        } else {
            $response = [
                'status' => false,
                'msg' => 'something went wrong!'
            ];
        }

        return response()->json(
            $response,
            200,
            ['Content-Type' => 'application/json;charset=UTF-8', 'Charset' => 'utf-8'],
            JSON_UNESCAPED_UNICODE
        );
    }

    public function userparent(Request $request): void{
        $user = User::where('UserType',2)->get();

        foreach($user as $val){
            $save = User::find($val->id);
            $save->parent_id = $val->id;
            $save->save();
        }

        $quote = Quotation::get();

        foreach($quote as $val){
            $save = Quotation::find($val->id);
            $save->CompanyUserId = $val->UserId;
            $save->save();
        }
    }

    public function exportAllQuotations(){
        return Excel::download(new AllQuotationExport(), 'quotation.xlsx', \Maatwebsite\Excel\Excel::XLSX,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
    }

    public function getFireRatingOptions(Request $request)
    {
        $leafTypeId = $request->input('leaf_type_id');
        // Fetch the leaf type with the fire rating IDs (assuming fire_ratings is the column with comma-separated IDs)
        $leafType = IntumescentSealLeafType::where('id', $leafTypeId)->first();
        if ($leafType && $leafType->fireratingid) {
            // Convert the comma-separated string of fire rating IDs to an array
            $fireRatingIds = explode(',', (string) $leafType->fireratingid);

            // Fetch the corresponding fire ratings from the options table
            $fireRatings = Option::whereIn('id', $fireRatingIds)
                            ->get(['id', 'OptionKey']);  // Adjust 'name' to the correct column for fire rating name

            return response()->json(['fire_ratings' => $fireRatings,'leafType' => $leafType]);
        }

        // If no fire ratings found, return an empty array
        return response()->json(['fire_ratings' => []]);
    }


    public function ExportDoorTypeBom($quotationId,$versionID){
        $quotation = Quotation::where('quotation.id',$quotationId)->first();
        $vid = ['selectVersionID'=>0,'selectVersion'=>0];
        if($vid > 0){
            $QV = QuotationVersion::where('id',$versionID)->first();
            $vid = $QV->version;
        }

        return Excel::download(new BomDoorTypeExport($quotationId,$versionID), "DoorTypeBOM ".trim((string) $quotation->QuotationGenerationId, "#")."-".$vid.'.xlsx', \Maatwebsite\Excel\Excel::XLSX,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            ]);
    }
}
