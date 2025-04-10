<?php

namespace App\Http\Controllers\halspan;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use Illuminate\Support\Facades\Crypt;
use App\Models\Item;
use App\Models\DoorSchedule;
use App\Models\DoorDimension;
use Session;
use App\Models\Quotation;
use App\Models\Customer;
use App\Models\Option;
use App\Models\Company;
use Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Project;
use App\Models\QuotationVersion;
use App\Models\QuotationVersionItems;
use App\Models\User;
use App\Models\BOMCalculation;
use DB;
use View;
use Illuminate\Support\Facades\Storage;
use App\Models\Tooltip;
use App\Models\AddIronmongery;
use App\Models\BOMSetting;
use App\Models\ItemMaster;
use App\Models\Floor;
use App\Models\LippingSpecies;
use App\Models\LippingSpeciesItems;
use App\Models\SelectedLippingSpeciesItems;
use App\Models\Color;
use App\Models\ConfigurableDoorFormula;
use App\Models\Items;
use App\Models\ConfigurableItems;
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
use App\Models\IntumescentSealLeafType;
use App\Models\DoorFrameConstruction;

class HalspanController extends Controller
{
    public function addHalspanItem($id,$vid = null,$itemId = null)
    {
        $item = [];
        $UserIds = CompanyUsers();
        $ConfigurableDoorFormulaData = ConfigurableDoorFormula::where('status',1)->get();
        $LippingSpeciesData = GetOptions(['lipping_species.Status'=> 1], "join", "lippingSpecies");
        $SelectedLippingSpeciesData = $LippingSpeciesData;
        $OptionsData = Option::where(['configurableitems'=> 2 ,'is_deleted'=>0])->wherein('editBy',$UserIds)->get();

        $intumescentSealArrangement = GetOptions(['setting_intumescentseals2.configurableitems'=> 2], "", "intumescentSealArrangement");

        $configurationDoor = configurationDoor(2);
        $UserId = Auth::user()->id;
        $UserType = Auth::user()->UserType;
        if(in_array($UserType,[1,4])){
            $SelectedOptionsData = $OptionsData;
            $intumescentSealColor = IntumescentSealColor::where([$configurationDoor => 2 ,'Status'=>1])->wherein('editBy',$UserIds)->get();
            $ArchitraveType = ArchitraveType::where([$configurationDoor => 2 ,'Status'=>1])->wherein('editBy',$UserIds)->get();
            $SelectedIntumescentSealArrangement = $intumescentSealArrangement;

        }else{
            $UserId = CompanyUsers();
            $SelectedOptionsData = GetOptions(['options.configurableitems'=> 2 ,'options.is_deleted' => 0], "join");
            $intumescentSealColor = GetOptions(['intumescent_seal_color.'.$configurationDoor=> 2 ,'intumescent_seal_color.Status' => 1], "join","intumescent_seal_color");
            $ArchitraveType = GetOptions(['architrave_type.'.$configurationDoor=> 2 ,'architrave_type.Status' => 1], "join","architrave_type");

            $SelectedIntumescentSealArrangement = GetOptions(['selected_intumescentseals2.selected_configurableitems'=> 2], "join", "intumescentSealArrangement");
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

        $leafTypeIntumescentseal = IntumescentSealLeafType::where('configurableitems',2)->where('status',1)->get();

        $BOMSetting = BOMSetting::where("id",1)->get()->first();


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

        return view('Items/Halspan/HalspanDoorConfiguration',[
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
            'issingleconfiguration' => '2',
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

    public function editHalspanConfigurationCadItem($id,$vid){

        $UserIds = CompanyUsers();
        $item = Item::where('itemId',$id)->first();
        // dd($item);
        if($item === null){
            return abort(404);
        }
        
        $item = $item->toArray();

        // below code to get lipping name and to show on edit page---
        $LippingName = LippingSpecies::where('id', $item['LippingSpecies'])->where('Status',1)->first();


        $ConfigurableDoorFormulaData = ConfigurableDoorFormula::where('status',1)->get();
        $OptionsData = Option::where(['configurableitems'=> 2 ,'is_deleted' => 0])->get();
        $intumescentSealArrangement = GetOptions(['setting_intumescentseals2.configurableitems'=> 2], "", "intumescentSealArrangement");

        $LippingSpeciesData = GetOptions(['lipping_species.Status'=> 1], "join", "lippingSpecies");

        $configurationDoor = configurationDoor(2);
        $UserType = Auth::user()->UserType;
        if(in_array($UserType,[1,4])){
            $UserId = $item['UserId'];
            $SelectedOptionsData = $OptionsData;
            $intumescentSealColor = IntumescentSealColor::where([$configurationDoor => 2 ,'Status'=>1])->wherein('editBy',$UserIds)->get();
            $ArchitraveType = ArchitraveType::where([$configurationDoor => 2 ,'Status'=>1])->wherein('editBy',$UserIds)->get();
        }else{
            if(Auth::user()->UserType == 3){
                $UserId = Auth::user()->CreatedBy;
            }else{
                $UserId = Auth::user()->id;
            }

            $SelectedOptionsData = GetOptions(['options.configurableitems'=> 2 ,'options.is_deleted' => 0, 'selected_option.SelectedUserId' => $UserId], "join");
            $intumescentSealColor = GetOptions(['intumescent_seal_color.'.$configurationDoor=> 2 ,'intumescent_seal_color.Status' => 1], "join","intumescent_seal_color");
            $ArchitraveType = GetOptions(['architrave_type.'.$configurationDoor=> 2 ,'architrave_type.Status' => 1], "join","architrave_type");
        }

        $SelectedIntumescentSealArrangement = GetOptions(['selected_intumescentseals2.selected_configurableitems'=> 2, 'selected_intumescentseals2.selected_intumescentseals2_user_id' => $UserId], "join", "intumescentSealArrangement");

        $SelectedLippingSpeciesQuery = SelectedLippingSpeciesItems::where([['selected_lipping_species_items.selected_user_id', '=', $UserId]]);
        $SelectedLippingSpeciesIds = array_column($SelectedLippingSpeciesQuery->groupBy("selected_lipping_species_id")->get()->toArray(), "id");

        $SelectedLippingSpeciesData = GetOptions(['lipping_species.Status' => 1], "join", "lippingSpecies", "query");
        $SelectedLippingSpeciesData = $SelectedLippingSpeciesData->whereIn("lipping_species.id",  $SelectedLippingSpeciesIds)->get();

        $ColorData = Color::where([ 'Status' => 1])->get();
        $company_data = Company::join('users','users.id','companies.UserId')->select('users.*')->get();
        $tooltip = Tooltip::first();
        $quotation = Quotation::where(['id' => $item["QuotationId"] ])->first();
        $CompanyId = null;
        if($quotation != ''){
            $CompanyId = $quotation->CompanyId;
        }

        $setIronmongery = AddIronmongery::wherein('UserId', $UserIds)->orderBy('Setname','ASC')->get();
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


        $BOMSetting = BOMSetting::where("id",1)->get()->first();
        $leafTypeIntumescentseal = IntumescentSealLeafType::where('configurableitems',2)->where('status',1)->get();

        return view('Items/Halspan/HalspanDoorConfiguration',[
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
            'issingleconfiguration' => '2',
            'versionId' => $vid,
            'tooltip' => $tooltip,
            'setIronmongery' => $setIronmongery,
            'BOMSetting' => $BOMSetting,
            'quotation' => $quotation,
            'LippingName' => $LippingName,
            'leafTypeIntumescentseal' => $leafTypeIntumescentseal,  // this line is for to send lipping name into edit form
        ]);
    }
}
