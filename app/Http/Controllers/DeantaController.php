<?php

namespace App\Http\Controllers;

// use App\IronmongeryInfoModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use App\Models\Item;
use App\Models\DoorSchedule;
use Session;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\ScheduleOrder;
use App\Exports\ScheduleOrderNew;
use App\Exports\ScheduleOrder2;
use App\Imports\DoorScheduleImport;
use App\Models\Quotation;
use App\Models\CompanyQuotationCounter;
use App\Models\CompanyOrderCounter;
use App\Models\Customer;
use App\Models\Option;
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
use Illuminate\Support\Facades\DB;
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
use App\Models\Floor;

use App\Models\LippingSpecies;
use App\Models\LippingSpeciesItems;
use App\Models\SelectedLippingSpeciesItems;

use App\Models\LeafType;
use App\Models\Color;
use App\Models\ConfigurableDoorFormula;
use App\Models\Items;
use App\Models\ConfigurableItems;
use App\Models\ProjectFiles;
use App\Models\ProjectFilesDS;
use App\Models\SettingCurrency;
use App\Models\SelectedDoordimension;
use App\Models\DoorDimension;
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
use Illuminate\Http\JsonResponse;
use App\Models\DoorFrameConstruction;


class DeantaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function adddeantaCadItem($id,$vid = null,$itemId = null){

        if(auth()->user()->UserType == 2){
            $userId = [1,auth()->user()->id];
            }elseif(auth()->user()->UserType == 3){
                $userId = [1,auth()->user()->CreatedBy];
            }else{
                $userId = [];
            }

        $item = [];
        $UserIds = CompanyUsers();
        $ConfigurableDoorFormulaData = ConfigurableDoorFormula::where('status',1)->get();
        $LippingSpeciesData = GetOptions(['lipping_species.Status'=> 1], "join", "lippingSpecies");
        $SelectedLippingSpeciesData = $LippingSpeciesData;
        $OptionsData = Option::where(['configurableitems'=> 6 ,'is_deleted'=>0])->wherein('editBy',$UserIds)->get();

        $intumescentSealArrangement = GetOptions(['setting_intumescentseals2.configurableitems'=> 6], "", "intumescentSealArrangement");

        $configurationDoor = configurationDoor(6);
        $UserId = Auth::user()->id;
        $UserType = Auth::user()->UserType;
        if(in_array($UserType,[1,4])){
            $SelectedOptionsData = $OptionsData;
            $intumescentSealColor = IntumescentSealColor::where([$configurationDoor => 6 ,'Status'=>1])->wherein('editBy',$UserIds)->get();
            $ArchitraveType = ArchitraveType::where([$configurationDoor => 6 ,'Status'=>1])->wherein('editBy',$UserIds)->get();
            $SelectedIntumescentSealArrangement = $intumescentSealArrangement;
            // $SelectedLippingSpeciesData = $LippingSpeciesData;

        }else{
            $UserId = CompanyUsers();
            $SelectedOptionsData = GetOptions(['options.configurableitems'=> 6 ,'options.is_deleted' => 0], "join");
            $intumescentSealColor = GetOptions(['intumescent_seal_color.'.$configurationDoor=> 6 ,'intumescent_seal_color.Status' => 1], "join","intumescent_seal_color");
            $ArchitraveType = GetOptions(['architrave_type.'.$configurationDoor=> 6 ,'architrave_type.Status' => 1], "join","architrave_type");

            $SelectedIntumescentSealArrangement = GetOptions(['selected_intumescentseals2.selected_configurableitems'=> 6], "join", "intumescentSealArrangement");

            // $SelectedLippingSpeciesData = GetOptions(['lipping_species.Status'=> 1, 'selected_lipping_species.SelectedStatus'=> 1, 'selected_lipping_species.LippingSpeciesUserId' => Auth::user()->id], "join", "lippingSpecies");

        }

        $ColorData = Color::where('Status',1)->wherein('editBy',$UserIds)->get();
        $company_data = Company::join('users','users.id','companies.UserId')->select('users.*')->get();
        $tooltip = Tooltip::first();
        $quotation = Quotation::where('id',$id)->first();


        $setIronmongery = AddIronmongery::wherein('UserId', $UserId)->orderBy('Setname','ASC')->get();
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
        $species = GetOptions(['leaf_type.Deanta'=> 6 ,'leaf_type.Status' => 1], "join","leaf_type");
        $BOMSetting = BOMSetting::where("id",1)->get()->first();

        if(Auth::user()->UserType == 3){
            $users = User::where('UserType',3)->where('id',Auth::user()->id)->first();
            $ids = $users->CreatedBy;
        }else{
            $ids = Auth::user()->id;
        }
        $defaultItems = Project::whereHas('defaultItems', function ($query) use ($quotation,$ids) {
            $query->where('default_type', 'standard')
                  ->where('UserId', $ids)
                  ->where('projectId', $quotation->ProjectId);
        })
        ->with(['defaultItems' => function ($query) use ($quotation,$ids) {
            $query->where('default_type', 'standard')
                  ->where('UserId', $ids)
                  ->where('projectId', $quotation->ProjectId);
        }])
        ->first();

        if ($defaultItems) {
            // Convert $defaultItems to array
            $defaultItems = $defaultItems->toArray();
            // Access the first default item if it exists
            $defaultItemsstandard = $defaultItems['default_items'][0] ?? [];
        } else {
            $defaultItemsstandard = [];
        }
        $hinge_location = DoorFrameConstruction::where('UserId',$ids)->where('DoorFrameConstruction', 'Hinge_Location')->first();
        return view('Items/Deanta/DeantaConfigurableItem',[
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
            'issingleconfiguration' => '6',
            'versionId' => $vid,
            'tooltip' => $tooltip,
            'setIronmongery' => $setIronmongery,
            'BOMSetting' => $BOMSetting,
            'quotation' => $quotation,
            'species' => $species,
            'default' => $defaultItemsstandard,
            'hinge_location' => $hinge_location,
        ]);
    }

    public function editdeantaCadItem($id,$vid = null,$itemId = null){
       if(auth()->user()->UserType == 2){
        $userId = [1,auth()->user()->id];
        }elseif(auth()->user()->UserType == 3){
            $userId = [1,auth()->user()->CreatedBy];
        }else{
            $userId = [];
        }
       $UserIds = CompanyUsers();
       $item = Item::where('itemId',$id)->first();
       if($item === null){
           return abort(404);
       }
       $item = $item->toArray();
        $UserIds = CompanyUsers();
        $ConfigurableDoorFormulaData = ConfigurableDoorFormula::where('status',1)->get();
        $LippingSpeciesData = GetOptions(['lipping_species.Status'=> 1], "join", "lippingSpecies");
        $SelectedLippingSpeciesData = $LippingSpeciesData;
        $OptionsData = Option::where(['configurableitems'=> 6 ,'is_deleted'=>0])->wherein('editBy',$UserIds)->get();

        $intumescentSealArrangement = GetOptions(['setting_intumescentseals2.configurableitems'=> 6], "", "intumescentSealArrangement");
       // dd($UserIds, $ConfigurableDoorFormulaData, $LippingSpeciesData, $SelectedLippingSpeciesData,$OptionsData, $intumescentSealArrangement);
        $configurationDoor = configurationDoor(6);
        $UserId = Auth::user()->id;
        $UserType = Auth::user()->UserType;
        if(in_array($UserType,[1,4])){
            $SelectedOptionsData = $OptionsData;
            $intumescentSealColor = IntumescentSealColor::where([$configurationDoor => 6 ,'Status'=>1])->wherein('editBy',$UserIds)->get();
            $ArchitraveType = ArchitraveType::where([$configurationDoor => 6 ,'Status'=>1])->wherein('editBy',$UserIds)->get();
            $SelectedIntumescentSealArrangement = $intumescentSealArrangement;
            // $SelectedLippingSpeciesData = $LippingSpeciesData;

        }else{
            $UserId = CompanyUsers();
            $SelectedOptionsData = GetOptions(['options.configurableitems'=> 6 ,'options.is_deleted' => 0], "join");
            $intumescentSealColor = GetOptions(['intumescent_seal_color.'.$configurationDoor=> 6 ,'intumescent_seal_color.Status' => 1], "join","intumescent_seal_color");
            $ArchitraveType = GetOptions(['architrave_type.'.$configurationDoor=> 6 ,'architrave_type.Status' => 1], "join","architrave_type");

            $SelectedIntumescentSealArrangement = GetOptions(['selected_intumescentseals2.selected_configurableitems'=> 6], "join", "intumescentSealArrangement");

            // $SelectedLippingSpeciesData = GetOptions(['lipping_species.Status'=> 1, 'selected_lipping_species.SelectedStatus'=> 1, 'selected_lipping_species.LippingSpeciesUserId' => Auth::user()->id], "join", "lippingSpecies");

        }

        $ColorData = Color::where('Status',1)->wherein('editBy',$UserIds)->get();
        $company_data = Company::join('users','users.id','companies.UserId')->select('users.*')->get();
        $tooltip = Tooltip::first();
        $quotation = Quotation::where('id',$item["QuotationId"])->first();

        $setIronmongery = AddIronmongery::wherein('UserId', $UserId)->orderBy('Setname','ASC')->get();
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
        $species = DB::table('leaf_type')->where('Deanta', 6)->where('Status',1)->whereIn('EditBy', $userId)->get();

        $BOMSetting = BOMSetting::where("id",1)->get()->first();
        return view('Items/Deanta/DeantaConfigurableItem',[
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
            'issingleconfiguration' => '6',
            'versionId' => $vid,
            'tooltip' => $tooltip,
            'setIronmongery' => $setIronmongery,
            'BOMSetting' => $BOMSetting,
            'quotation' => $quotation,
            'species' => $species,
        ]);
    }

    public function door_dimension_import(){
        $data = Excel::toArray(new DoorScheduleImport, request()->file('ExcelFile'));

        $i = 0;
        foreach ($data[0] as $row) {

            if ($i == 0) {
                $i++;
                continue;
            }

            $j = 0;
            $leaftype = trim($row[$j++]);
            $doorleaffacing = trim($row[$j++]);
            $width = trim($row[$j++]);
            $length = trim($row[$j++]);
            $depth = trim($row[$j++]);
            $firerating = trim($row[$j++]);
            $code = trim($row[$j++]);
            $cost = trim($row[$j++]);

            $leaftypedata = LeafType::where('LeafType',$leaftype)->count();
            if($leaftypedata == 0){
                $data = new LeafType();
                $key = str_replace(' ', '_', $leaftype);
                $data->Deanta = 6;
                $data->Key = $key;
                $data->UnderAttribute = $key;
                $data->LeafType = $leaftype;
                $data->EditBy = 1;
                $data->save();
            }

            $doorleaffacingdata = DoorLeafFacing::where('doorLeafFacingValue',$doorleaffacing)->count();
            if($doorleaffacingdata == 0){
                $data = new DoorLeafFacing();
                $key = str_replace(' ', '_', $doorleaffacing);
                $data->Deanta = 6;
                $data->Key = $key;
                $data->doorLeafFacing = $leaftype;
                $data->doorLeafFacingValue = $doorleaffacing;
                $data->editBy = 1;
                $data->save();
            }


            $dimensiondata = new DoorDimension();
            $dimensiondata->configurableitems = 6;
            $dimensiondata->code=$code;
            $dimensiondata->inch_height=$length / 25.4;
            $dimensiondata->inch_width=$width / 25.4;
            $dimensiondata->mm_height=$length;
            $dimensiondata->mm_width=$width;
            $dimensiondata->fire_rating=$firerating;
            $dimensiondata->door_leaf_facing=$doorleaffacing;
            $dimensiondata->cost_price=$cost;
            $dimensiondata->selling_price=$cost;
            $dimensiondata->leaf_type=$leaftype;
            $dimensiondata->UserId = 1;
            $dimensiondata->editBy = 1;
            $dimensiondata->save();

        }
    }

}
