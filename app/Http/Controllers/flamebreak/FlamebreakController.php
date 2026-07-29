<?php

namespace App\Http\Controllers\flamebreak;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use DB;
use App\Models\{ConfigurableDoorFormula,Option,IntumescentSealColor,ArchitraveType,Color,Company,Tooltip,Quotation,AddIronmongery,BOMSetting,Item,LippingSpecies,SelectedLippingSpeciesItems,SelectedIronmongery,IronmongeryInfoModel,Project,DoorFrameConstruction,User};
use App\Http\Controllers\Concerns\BuildsIronmongeryAdditionalInfo;

class FlamebreakController extends Controller
{
    use BuildsIronmongeryAdditionalInfo;

    /**
     * Request-local lookup cache for repeated controller reads.
     *
     * @var array<string, mixed>
     */
    private array $requestLookupCache = [];

    private function rememberLookup(string $key, callable $resolver)
    {
        if (!array_key_exists($key, $this->requestLookupCache)) {
            $this->requestLookupCache[$key] = $resolver();
        }

        return $this->requestLookupCache[$key];
    }

    private function getFlamebreakConfigurationBaseLookups(array $userIds): array
    {
        return [
            'ConfigurableDoorFormulaData' => $this->rememberLookup('flamebreak_configurable_door_formula', static function () {
                return ConfigurableDoorFormula::where('status', 1)->get();
            }),
            'leafTypeIntumescentseal' => $this->rememberLookup('flamebreak_leaf_type_intumescentseal_7', static function () {
                return DB::table('intumescent_seal_leaf_type')->where('configurableitems', 7)->get();
            }),
            'LippingSpeciesData' => $this->rememberLookup('flamebreak_lipping_species_join', static function () {
                return GetOptions(['lipping_species.Status' => 1], "join", "lippingSpecies");
            }),
            'intumescentSealArrangement' => $this->rememberLookup('flamebreak_intumescent_arrangement_7', static function () {
                return GetOptions(['setting_intumescentseals2.configurableitems' => 7], "", "intumescentSealArrangement");
            }),
            'company_data' => $this->rememberLookup('flamebreak_company_data', static function () {
                return Company::join('users', 'users.id', 'companies.UserId')->select('users.*')->get();
            }),
            'tooltip' => $this->rememberLookup('flamebreak_tooltip', static function () {
                return Tooltip::first();
            }),
            'folders' => $this->rememberLookup('flamebreak_folders_' . Auth::id(), static function () {
                return DB::table('folders')
                    ->join('folder_ironmongery_sets', 'folders.id', '=', 'folder_ironmongery_sets.folder_id')
                    ->join('add_ironmongery', 'folder_ironmongery_sets.add_ironmongery_id', '=', 'add_ironmongery.id')
                    ->select(
                        'folders.id as folder_id',
                        'folders.name',
                        'add_ironmongery.id as ironmongery_id',
                        'add_ironmongery.Setname'
                    )
                    ->where('folders.user_id', Auth::user()->id)
                    ->get()
                    ->groupBy('folder_id');
            }),
            'setIronmongery' => $this->rememberLookup('flamebreak_set_ironmongery_' . md5(json_encode($userIds)), static function () use ($userIds) {
                return AddIronmongery::wherein('UserId', $userIds)->orderBy('Setname', 'ASC')->get();
            }),
            'BOMSetting' => $this->rememberLookup('flamebreak_bom_setting', static function () {
                return BOMSetting::where("id", 1)->get()->first();
            }),
        ];
    }

    public function addFlamebreakItem($id,$vid = null,$itemId = null){
        $item = [];
        $UserIds = CompanyUsers();
        [
            'ConfigurableDoorFormulaData' => $ConfigurableDoorFormulaData,
            'leafTypeIntumescentseal' => $leafTypeIntumescentseal,
            'LippingSpeciesData' => $LippingSpeciesData,
            'intumescentSealArrangement' => $intumescentSealArrangement,
            'company_data' => $company_data,
            'tooltip' => $tooltip,
            'folders' => $folders,
            'setIronmongery' => $setIronmongery,
            'BOMSetting' => $BOMSetting,
        ] = $this->getFlamebreakConfigurationBaseLookups($UserIds);
        $SelectedLippingSpeciesData = $LippingSpeciesData;
        $OptionsData = $this->rememberLookup('flamebreak_option_data_add_7_' . md5(json_encode($UserIds)), static function () use ($UserIds) {
            return Option::where(['configurableitems'=> 7 ,'is_deleted'=>0])->wherein('editBy',$UserIds)->get();
        });

        $configurationDoor = configurationDoor(7);
        $UserId = Auth::user()->id;
        $UserType = Auth::user()->UserType;
        if(in_array($UserType,[1,4])){
            $SelectedOptionsData = $OptionsData;
            $intumescentSealColor = IntumescentSealColor::where([$configurationDoor => 7 ,'Status'=>1])->wherein('editBy',$UserIds)->get();
            $ArchitraveType = ArchitraveType::where([$configurationDoor => 7 ,'Status'=>1])->wherein('editBy',$UserIds)->get();
            $SelectedIntumescentSealArrangement = $intumescentSealArrangement;

        }else{
            $UserId = CompanyUsers();
            $SelectedOptionsData = GetOptions(['options.configurableitems'=> 7 ,'options.is_deleted' => 0], "join");
            $intumescentSealColor = GetOptions(['intumescent_seal_color.'.$configurationDoor=> 7 ,'intumescent_seal_color.Status' => 1], "join","intumescent_seal_color");
            $ArchitraveType = GetOptions(['architrave_type.'.$configurationDoor=> 7 ,'architrave_type.Status' => 1], "join","architrave_type");

            $SelectedIntumescentSealArrangement = GetOptions(['selected_intumescentseals2.selected_configurableitems'=> 7], "join", "intumescentSealArrangement");
        }

        $quotation = Quotation::where('id',$id)->first();
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
        // foreach ($setIronmongery as $ironmongery) {
        //     $additionalInfo = []; // Temporary array to hold additional info

        //     foreach ($IronmongeryInfoSet as $valIronmongery) {
        //         // Check if the property exists and is not empty
        //         if (!empty($ironmongery->$valIronmongery)) {
        //             $SelectedIronmongery = SelectedIronmongery::where('id', $ironmongery->$valIronmongery)
        //                 ->where('UserId', Auth::user()->id)
        //                 ->first();

        //             if (!empty($SelectedIronmongery)) {
        //                 $IronmongeryInfoModel = IronmongeryInfoModel::where('IronmongeryId', $SelectedIronmongery->ironmongery_id)->where('UserId', Auth::user()->id)
        //                         ->first();
        //                 if(empty($IronmongeryInfoModel)){
        //                         $IronmongeryInfoModel = IronmongeryInfoModel::where('id', $SelectedIronmongery->ironmongery_id)->first();
        //                 }

        //                 if (!empty($IronmongeryInfoModel)) {
        //                     $additionalInfo[] = $IronmongeryInfoModel;
        //                 }
        //             }
        //         }
        //     }

        //     // Dynamically add the additional_info attribute
        //     $ironmongery->setAttribute('additional_info', $additionalInfo);
        // }
        // Bulk-load + slim ironmongery additional_info (BuildsIronmongeryAdditionalInfo trait).
        $this->attachIronmongeryAdditionalInfo($setIronmongery, $IronmongeryInfoSet);


        if(Auth::user()->UserType == 3){
            $users = User::where('UserType',3)->where('id',Auth::user()->id)->first();
            $ids = $users->CreatedBy;
        }else{
            $ids = Auth::user()->id;
        }

        $defaultItems = Project::whereHas('defaultItems', function ($query) use ($quotation, $ids): void {
            $query->where('default_type', 'custom')
                  ->where('UserId',  $ids)
                  ->where('projectId', $quotation->ProjectId);
        })
        ->with(['defaultItems' => function ($query) use ($quotation, $ids): void {
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
        return view('Items/Flamebreak/FlamebreakDoorConfiguration',[
            "QuotationId" => $id,
            'Item' => $item,
            'option_data' => $OptionsData,
            'selected_option_data' => $SelectedOptionsData,
            'intumescentSealColor' => $intumescentSealColor,
            'ArchitraveType' => $ArchitraveType,
            'intumescentSealArrangement' => $intumescentSealArrangement,
            'SelectedIntumescentSealArrangement' => $SelectedIntumescentSealArrangement,
            'lipping_species' => $LippingSpeciesData,
            'selected_lipping_species' => $SelectedLippingSpeciesData,
            'ConfigurableDoorFormula' => $ConfigurableDoorFormulaData,
            'company_list' => $company_data,
            'issingleconfiguration' => '7',
            'versionId' => $vid,
            'tooltip' => $tooltip,
            'setIronmongery' => $setIronmongery,
            'BOMSetting' => $BOMSetting,
            'quotation' => $quotation,
            'leafTypeIntumescentseal' => $leafTypeIntumescentseal,
            'default' => $defaultItemsCustom,
            'hinge_location' => $hinge_location,
            'folders' => $folders
        ]);
    }

    public function editFlamebreakConfigurationCadItem($id,$vid){

        $UserIds = CompanyUsers();
        $item = Item::where('itemId',$id)->first();
        // dd($item);
        if($item === null){
            return abort(404);
        }

        $item = $item->toArray();

        // below code to get lipping name and to show on edit page---
        $LippingName = LippingSpecies::where('id', $item['LippingSpecies'])->where('Status',1)->first();

        [
            'ConfigurableDoorFormulaData' => $ConfigurableDoorFormulaData,
            'leafTypeIntumescentseal' => $leafTypeIntumescentseal,
            'LippingSpeciesData' => $LippingSpeciesData,
            'intumescentSealArrangement' => $intumescentSealArrangement,
            'company_data' => $company_data,
            'tooltip' => $tooltip,
            'folders' => $folders,
            'setIronmongery' => $setIronmongery,
            'BOMSetting' => $BOMSetting,
        ] = $this->getFlamebreakConfigurationBaseLookups($UserIds);

        $OptionsData = $this->rememberLookup('flamebreak_option_data_edit_7', static function () {
            return Option::where(['configurableitems'=> 7 ,'is_deleted' => 0])->get();
        });

        $configurationDoor = configurationDoor(7);
        $UserType = Auth::user()->UserType;
        if(in_array($UserType,[1,4])){
            $UserId = $item['UserId'];
            $SelectedOptionsData = $OptionsData;
            $intumescentSealColor = IntumescentSealColor::where([$configurationDoor => 7 ,'Status'=>1])->wherein('editBy',$UserIds)->get();
            $ArchitraveType = ArchitraveType::where([$configurationDoor => 7 ,'Status'=>1])->wherein('editBy',$UserIds)->get();
        }else{
            if(Auth::user()->UserType == 3){
                $UserId = Auth::user()->CreatedBy;
            }else{
                $UserId = Auth::user()->id;
            }

            $SelectedOptionsData = GetOptions(['options.configurableitems'=> 7 ,'options.is_deleted' => 0, 'selected_option.SelectedUserId' => $UserId], "join");
            $intumescentSealColor = GetOptions(['intumescent_seal_color.'.$configurationDoor=> 7 ,'intumescent_seal_color.Status' => 1], "join","intumescent_seal_color");
            $ArchitraveType = GetOptions(['architrave_type.'.$configurationDoor=> 7 ,'architrave_type.Status' => 1], "join","architrave_type");
        }

        $SelectedIntumescentSealArrangement = GetOptions(['selected_intumescentseals2.selected_configurableitems'=> 7, 'selected_intumescentseals2.selected_intumescentseals2_user_id' => $UserId], "join", "intumescentSealArrangement");

        $SelectedLippingSpeciesQuery = SelectedLippingSpeciesItems::where([['selected_lipping_species_items.selected_user_id', '=', $UserId]]);
        $SelectedLippingSpeciesIds = array_column($SelectedLippingSpeciesQuery->groupBy("selected_lipping_species_id")->get()->toArray(), "id");

        $SelectedLippingSpeciesData = $this->rememberLookup('flamebreak_lipping_species_query', static function () {
            return GetOptions(['lipping_species.Status' => 1], "join", "lippingSpecies", "query");
        });
        $SelectedLippingSpeciesData = $SelectedLippingSpeciesData->whereIn("lipping_species.id",  $SelectedLippingSpeciesIds)->get();

        $quotation = Quotation::where(['id' => $item["QuotationId"] ])->first();
        $CompanyId = null;
        if($quotation != ''){
            $CompanyId = $quotation->CompanyId;
        }

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
        // foreach ($setIronmongery as $ironmongery) {
        //     $additionalInfo = []; // Temporary array to hold additional info

        //     foreach ($IronmongeryInfoSet as $valIronmongery) {
        //         // Check if the property exists and is not empty
        //         if (!empty($ironmongery->$valIronmongery)) {
        //             $SelectedIronmongery = SelectedIronmongery::where('id', $ironmongery->$valIronmongery)
        //                 ->where('UserId', Auth::user()->id)
        //                 ->first();

        //             if (!empty($SelectedIronmongery)) {
        //                 $IronmongeryInfoModel = IronmongeryInfoModel::where('IronmongeryId', $SelectedIronmongery->ironmongery_id)->where('UserId', Auth::user()->id)
        //                         ->first();
        //                 if(empty($IronmongeryInfoModel)){
        //                     $IronmongeryInfoModel = IronmongeryInfoModel::where('id', $SelectedIronmongery->ironmongery_id)->first();
        //                 }

        //                 if (!empty($IronmongeryInfoModel)) {
        //                     $additionalInfo[] = $IronmongeryInfoModel;
        //                 }
        //             }
        //         }
        //     }

        //     // Dynamically add the additional_info attribute
        //     $ironmongery->setAttribute('additional_info', $additionalInfo);
        // }

        // Bulk-load + slim ironmongery additional_info (BuildsIronmongeryAdditionalInfo trait).
        $this->attachIronmongeryAdditionalInfo($setIronmongery, $IronmongeryInfoSet);

        return view('Items/Flamebreak/FlamebreakDoorConfiguration',[
            "QuotationId" => $item["QuotationId"],
            'Item' => $item,
            'option_data' => $OptionsData,
            'selected_option_data' => $SelectedOptionsData,
            'intumescentSealColor' => $intumescentSealColor,
            'ArchitraveType' => $ArchitraveType,
            'intumescentSealArrangement' => $intumescentSealArrangement,
            'SelectedIntumescentSealArrangement' => $SelectedIntumescentSealArrangement,
            'lipping_species' => $LippingSpeciesData,
            'selected_lipping_species' => $SelectedLippingSpeciesData,
            'ConfigurableDoorFormula' => $ConfigurableDoorFormulaData,
            'company_list' => $company_data,
            'issingleconfiguration' => '7',
            'versionId' => $vid,
            'tooltip' => $tooltip,
            'setIronmongery' => $setIronmongery,
            'BOMSetting' => $BOMSetting,
            'quotation' => $quotation,
            'LippingName' => $LippingName,    // this line is for to send lipping name into edit form
            'leafTypeIntumescentseal' => $leafTypeIntumescentseal,    // this line is for to send lipping name into edit form
            'folders' => $folders
        ]);
    }
}
