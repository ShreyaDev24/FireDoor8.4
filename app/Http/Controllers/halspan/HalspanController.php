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
use App\Http\Controllers\Concerns\BuildsIronmongeryAdditionalInfo;
use Illuminate\Support\Facades\Log;

class HalspanController extends Controller
{
    use BuildsIronmongeryAdditionalInfo;

    public function addHalspanItem($id,$vid = null,$itemId = null)
    {
        $startTime = microtime(true);
        $item = [];
        $UserIds = CompanyUsers();
        $ConfigurableDoorFormulaData = ConfigurableDoorFormula::where('status',1)->get();
        $LippingSpeciesData = GetOptions(['lipping_species.Status'=> 1], "join", "lippingSpecies");
        $SelectedLippingSpeciesData = $LippingSpeciesData;
        $OptionsData = Option::where(['configurableitems'=> 2 ,'is_deleted'=>0])->wherein('editBy',$UserIds)->get();
        // Group options by slug once so each Blade dropdown iterates only its own
        // options instead of re-scanning the full collection on every dropdown.
        $OptionsDataGrouped = $OptionsData->groupBy('OptionSlug');

        $intumescentSealArrangement = GetOptions(['setting_intumescentseals2.configurableitems'=> 2], "", "intumescentSealArrangement");

        $configurationDoor = configurationDoor(2);
        $UserId = Auth::user()->id;
        $UserType = Auth::user()->UserType;
        $configurationDoor = configurationDoor(2);

        $checkpoints = [];
        $checkpoints['start'] = ['time' => microtime(true), 'label' => 'Function Start'];

        // Load data in parallel
        $time1 = microtime(true);
        $ConfigurableDoorFormulaData = ConfigurableDoorFormula::where('status', 1)->get();
        $checkpoints['ConfigurableDoorFormula'] = ['time' => microtime(true), 'duration' => microtime(true) - $time1, 'label' => 'ConfigurableDoorFormula Query'];

        $time2 = microtime(true);
        $LippingSpeciesData = GetOptions(['lipping_species.Status' => 1], "join", "lippingSpecies");
        $checkpoints['LippingSpecies'] = ['time' => microtime(true), 'duration' => microtime(true) - $time2, 'label' => 'LippingSpecies GetOptions'];

        $time3 = microtime(true);
        $OptionsData = Option::where(['configurableitems' => 2, 'is_deleted' => 0])
            ->wherein('editBy', $UserIds)
            ->get();
        $checkpoints['OptionsData'] = ['time' => microtime(true), 'duration' => microtime(true) - $time3, 'label' => 'OptionsData Query'];

        $time4 = microtime(true);
        $intumescentSealArrangement = GetOptions(['setting_intumescentseals2.configurableitems' => 2], "", "intumescentSealArrangement");
        $checkpoints['IntumescentArrangement'] = ['time' => microtime(true), 'duration' => microtime(true) - $time4, 'label' => 'IntumescentSealArrangement GetOptions'];

        // Determine user type and load relevant data
        $time5 = microtime(true);
        if (in_array($UserType, [1, 4])) {
            $SelectedOptionsData = $OptionsData;
            $intumescentSealColor = IntumescentSealColor::where([$configurationDoor => 2, 'Status' => 1])
                ->wherein('editBy', $UserIds)
                ->get();
            $ArchitraveType = ArchitraveType::where([$configurationDoor => 2, 'Status' => 1])
                ->wherein('editBy', $UserIds)
                ->get();
            $SelectedIntumescentSealArrangement = $intumescentSealArrangement;
        } else {
            $UserId = CompanyUsers();
            $SelectedOptionsData = GetOptions(['options.configurableitems'=> 2 ,'options.is_deleted' => 0, 'options.OptionSlug' => 'Door_Leaf_Facing'], "join");
            $intumescentSealColor = GetOptions(['intumescent_seal_color.'.$configurationDoor=> 2 ,'intumescent_seal_color.Status' => 1], "join","intumescent_seal_color");
            $ArchitraveType = GetOptions(['architrave_type.'.$configurationDoor=> 2 ,'architrave_type.Status' => 1], "join","architrave_type");

            $SelectedIntumescentSealArrangement = GetOptions(['selected_intumescentseals2.selected_configurableitems'=> 2], "join", "intumescentSealArrangement");
        }
        $checkpoints['UserTypeProcessing'] = ['time' => microtime(true), 'duration' => microtime(true) - $time5, 'label' => 'User Type & Related Data Processing'];

        // $ColorData = Color::where('Status',1)->wherein('editBy',$UserIds)->get();
        $company_data = Company::join('users','users.id','companies.UserId')->select('users.*')->get();
        $time8 = microtime(true);
        $tooltip = Tooltip::first();
        $checkpoints['Tooltip'] = ['time' => microtime(true), 'duration' => microtime(true) - $time8, 'label' => 'Tooltip Query'];

        $time9 = microtime(true);
        $quotation = Quotation::where('id', $id)->first();
        $checkpoints['Quotation'] = ['time' => microtime(true), 'duration' => microtime(true) - $time9, 'label' => 'Quotation Query'];

        $time10 = microtime(true);
        $BOMSetting = BOMSetting::where("id", 1)->first();
        $checkpoints['BOMSetting'] = ['time' => microtime(true), 'duration' => microtime(true) - $time10, 'label' => 'BOMSetting Query'];

        $time11 = microtime(true);
        $leafTypeIntumescentseal = IntumescentSealLeafType::where('configurableitems', 2)
            ->where('status', 1)
            ->get();
        $checkpoints['LeafTypeIntumescent'] = ['time' => microtime(true), 'duration' => microtime(true) - $time11, 'label' => 'LeafTypeIntumescentseal Query'];

        // Process ironmongery data
        $time12 = microtime(true);
        if (Auth::user()->UserType == 1) {
            $setIronmongery = AddIronmongery::orderBy('Setname','ASC')->get();
        }else{
            $setIronmongery = AddIronmongery::wherein('UserId', $UserId)->orderBy('Setname','ASC')->get();
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
        //                     $IronmongeryInfoModel = IronmongeryInfoModel::where('IronmongeryId', $SelectedIronmongery->ironmongery_id)->where('UserId', Auth::user()->id)
        //                         ->first();
        //                     if(empty($IronmongeryInfoModel)){
        //                         $IronmongeryInfoModel = IronmongeryInfoModel::where('id', $SelectedIronmongery->ironmongery_id)->first();
        //                     }

        //                     if (!empty($IronmongeryInfoModel)) {
        //                         $additionalInfo[] = $IronmongeryInfoModel;
        //                     }
        //             }
        //         }
        //     }

        //     // Dynamically add the additional_info attribute
        //     $ironmongery->setAttribute('additional_info', $additionalInfo);
        // }
        // Bulk-load SelectedIronmongery + IronmongeryInfoModel and attach additional_info
        // in memory (no per-row DB queries). Output is identical to the previous
        // nested-loop logic, including quantity duplication and ordering.
        // NOTE: dev branch also added a processIronmongeryData() call here during the
        // merge; it has been removed because the trait call above already builds
        // additional_info in bulk AND slims it. Running both did the work twice and the
        // (un-slimmed) dev version overwrote the slim output.
        $this->attachIronmongeryAdditionalInfo($setIronmongery, $IronmongeryInfoSet);

        // Get user ID for defaults
        $userId = Auth::user()->UserType == 3
            ? Auth::user()->CreatedBy
            : Auth::user()->id;

        // Get default items
        $time14 = microtime(true);
        $defaultItemsCustom = $this->getDefaultItems($quotation, $userId);
        $checkpoints['DefaultItems'] = ['time' => microtime(true), 'duration' => microtime(true) - $time14, 'label' => 'Get Default Items'];

        // Get folder data
        $time15 = microtime(true);
        $hinge_location = DoorFrameConstruction::where('UserId', $userId)
            ->where('DoorFrameConstruction', 'Hinge_Location')
            ->first();
        $checkpoints['HingeLocation'] = ['time' => microtime(true), 'duration' => microtime(true) - $time15, 'label' => 'Hinge Location Query'];

        $time16 = microtime(true);
        $folders = DB::table('folders')
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
        $checkpoints['Folders'] = ['time' => microtime(true), 'duration' => microtime(true) - $time16, 'label' => 'Folders Join Query'];

        // Log all checkpoints
        Log::info('=== HALSPAN ADD ITEM PERFORMANCE LOG ===');
        usort($checkpoints, function($a, $b) {
            return ($b['duration'] ?? 0) <=> ($a['duration'] ?? 0);
        });

        foreach ($checkpoints as $key => $checkpoint) {
            if (isset($checkpoint['duration'])) {
                Log::info($checkpoint['label'] . ': ' . round($checkpoint['duration'] * 1000, 2) . 'ms');
            }
        }
        if(Auth::user()->UserType == 3){
            $users = User::where('UserType',3)->where('id',Auth::user()->id)->first();
            $ids = $users->CreatedBy;
        } else {
            $ids = Auth::user()->id;
        }

        $hinge_location = DoorFrameConstruction::where('UserId',$ids)->where('DoorFrameConstruction', 'Hinge_Location')->first();
        $totalTime = microtime(true) - $startTime;
        Log::info('TOTAL TIME: ' . round($totalTime * 1000, 2) . 'ms');
        Log::info('=== END PERFORMANCE LOG ===');

        return view('Items/Halspan/HalspanDoorConfiguration', [
            "QuotationId" => $id,
            'Item' => $item,
            'option_data' => $OptionsData,
            'option_data_grouped' => $OptionsDataGrouped,
            'selected_option_data' => $SelectedOptionsData,
            'intumescentSealColor' => $intumescentSealColor,
            'ArchitraveType' => $ArchitraveType,
            'intumescentSealArrangement' => $intumescentSealArrangement,
            'SelectedIntumescentSealArrangement' => $SelectedIntumescentSealArrangement,
            'lipping_species' => $LippingSpeciesData,
            'selected_lipping_species' => $LippingSpeciesData,
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
            'folders' => $folders
        ]);
    }

    /**
     * Process ironmongery additional info data
     * Optimized to avoid N+1 queries by batching SelectedIronmongery and IronmongeryInfoModel fetches.
     */
    private function processIronmongeryData($setIronmongery)
    {
        $totalStartTime = microtime(true);

        $IronmongeryInfoSet = [
            'Hinges', 'FloorSpring', 'LocksAndLatches', 'FlushBolts', 'ConcealedOverheadCloser',
            'PullHandles', 'PushHandles', 'KickPlates', 'DoorSelectors', 'PanicHardware',
            'Doorsecurityviewer', 'Morticeddropdownseals', 'Facefixeddropseals', 'ThresholdSeal',
            'AirTransferGrill', 'Letterplates', 'CableWays', 'SafeHinge', 'LeverHandle',
            'DoorSinage', 'FaceFixedDoorCloser', 'Thumbturn', 'KeyholeEscutchen', 'DoorStops', 'Cylinders'
        ];

        $qtyFieldOverrides = [
            'DoorSinage' => 'doorSignageQty',
            'FaceFixedDoorCloser' => 'faceFixedDoorClosersQty',
            'DoorStops' => 'DoorStopsQty',
            'AirTransferGrill' => 'airtransfergrillsQty',
        ];

        // Collect all SelectedIronmongery IDs to fetch in a single query
        $collectStart = microtime(true);
        $allSelectedIds = [];
        foreach ($setIronmongery as $ironmongery) {
            foreach ($IronmongeryInfoSet as $valIronmongery) {
                if (empty($ironmongery->$valIronmongery)) {
                    continue;
                }
                $ids = array_map('trim', explode(',', $ironmongery->$valIronmongery));
                foreach ($ids as $id) {
                    if ($id !== '') {
                        $allSelectedIds[] = $id;
                    }
                }
            }
        }
        $allSelectedIds = array_values(array_unique($allSelectedIds));
        Log::debug('processIronmongeryData - Collected SelectedIronmongery IDs: ' . count($allSelectedIds) . ', collect time: ' . round((microtime(true) - $collectStart) * 1000, 2) . 'ms');

        $selectedMap = collect();
        $infoByIronId = collect();
        $infoById = collect();

        if (!empty($allSelectedIds)) {
            $t1 = microtime(true);
            $selectedMap = SelectedIronmongery::whereIn('id', $allSelectedIds)
                ->where('UserId', Auth::user()->id)
                ->get()
                ->keyBy('id');
            Log::debug('processIronmongeryData - Fetched SelectedIronmongery: ' . count($selectedMap) . ', time: ' . round((microtime(true) - $t1) * 1000, 2) . 'ms');

            // Fetch IronmongeryInfoModel by IronmongeryId for the selected ironmongery
            $ironmongeryIds = $selectedMap->pluck('ironmongery_id')->filter()->unique()->values()->all();

            if (!empty($ironmongeryIds)) {
                $t2 = microtime(true);
                $infoByIronId = IronmongeryInfoModel::whereIn('IronmongeryId', $ironmongeryIds)
                    ->where('UserId', Auth::user()->id)
                    ->get()
                    ->keyBy('IronmongeryId');
                Log::debug('processIronmongeryData - Fetched IronmongeryInfoModel by IronmongeryId: ' . count($infoByIronId) . ', time: ' . round((microtime(true) - $t2) * 1000, 2) . 'ms');

                // For missing entries, fetch by id as fallback
                $missing = array_diff($ironmongeryIds, array_keys($infoByIronId->toArray()));
                if (!empty($missing)) {
                    $t3 = microtime(true);
                    $infoById = IronmongeryInfoModel::whereIn('id', $missing)->get()->keyBy('id');
                    Log::debug('processIronmongeryData - Fetched IronmongeryInfoModel by id (fallback): ' . count($infoById) . ', time: ' . round((microtime(true) - $t3) * 1000, 2) . 'ms');
                }
            }
        }

        // Build additional_info using cached maps
        $buildStart = microtime(true);
        foreach ($setIronmongery as $ironmongery) {
            $additionalInfo = [];

            foreach ($IronmongeryInfoSet as $valIronmongery) {
                if (empty($ironmongery->$valIronmongery)) {
                    continue;
                }

                $qtyField = $qtyFieldOverrides[$valIronmongery] ?? lcfirst($valIronmongery) . 'Qty';
                $ids = array_map('trim', explode(',', $ironmongery->$valIronmongery));
                $qtys = !empty($ironmongery->$qtyField)
                    ? array_map('trim', explode(',', $ironmongery->$qtyField))
                    : [];

                foreach ($ids as $index => $itemId) {
                    $qty = isset($qtys[$index]) ? (int) $qtys[$index] : 1;

                    $SelectedIronmongery = $selectedMap->get($itemId);
                    if (!$SelectedIronmongery) {
                        continue;
                    }

                    $ironId = $SelectedIronmongery->ironmongery_id;
                    $IronmongeryInfoModel = $infoByIronId->get($ironId) ?: $infoById->get($ironId);

                    if ($IronmongeryInfoModel) {
                        for ($i = 0; $i < $qty; $i++) {
                            $additionalInfo[] = $IronmongeryInfoModel;
                        }
                    }
                }
            }

            $ironmongery->setAttribute('additional_info', $additionalInfo);
        }
        Log::debug('processIronmongeryData - Build additional_info time: ' . round((microtime(true) - $buildStart) * 1000, 2) . 'ms');

        $totalTime = microtime(true) - $totalStartTime;
        Log::info('processIronmongeryData - Total Time: ' . round($totalTime * 1000, 2) . 'ms');
    }

    /**
     * Get default items for the quotation
     */
    private function getDefaultItems($quotation, $userId)
    {
        $startTime = microtime(true);

        if (!$quotation) {
            return [];
        }

        $time1 = microtime(true);
        $defaultItems = Project::whereHas('defaultItems', function ($query) use ($quotation, $userId) {
            $query->where('default_type', 'custom')
                ->where('UserId', $userId)
                ->where('projectId', $quotation->ProjectId);
        })
        ->with(['defaultItems' => function ($query) use ($quotation, $userId) {
            $query->where('default_type', 'custom')
                ->where('UserId', $userId)
                ->where('projectId', $quotation->ProjectId);
        }])
        ->first();

        $queryTime = microtime(true) - $time1;
        if ($queryTime > 0.5) {
            Log::warning('SLOW QUERY - getDefaultItems whereHas query: ' . round($queryTime * 1000, 2) . 'ms');
        }

        if (!$defaultItems) {
            Log::debug('getDefaultItems - No default items found');
            return [];
        }

        $defaultItems = $defaultItems->toArray();
        $result = $defaultItems['default_items'][0] ?? [];

        $totalTime = microtime(true) - $startTime;
        Log::info('getDefaultItems - Total Time: ' . round($totalTime * 1000, 2) . 'ms');

        return $result;
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
        // Group options by slug once so each Blade dropdown iterates only its own
        // options instead of re-scanning the full collection on every dropdown.
        $OptionsDataGrouped = $OptionsData->groupBy('OptionSlug');
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
        $folders = DB::table('folders')
                ->join('folder_ironmongery_sets', 'folders.id', '=', 'folder_ironmongery_sets.folder_id')
                ->join('add_ironmongery', 'folder_ironmongery_sets.add_ironmongery_id', '=', 'add_ironmongery.id')
                ->select(
                    'folders.id as folder_id',
                    'folders.name',
                    'add_ironmongery.id as ironmongery_id',
                    'add_ironmongery.Setname'
                )
                ->where('folders.user_id',Auth::user()->id)
                ->get()
                ->groupBy('folder_id');
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
        // foreach ($setIronmongery as $ironmongery) {
        //     $additionalInfo = []; // Temporary array to hold additional info

        //     foreach ($IronmongeryInfoSet as $valIronmongery) {
        //         // Check if the property exists and is not empty
        //         if (!empty($ironmongery->$valIronmongery)) {
        //             $SelectedIronmongery = SelectedIronmongery::where('id', $ironmongery->$valIronmongery)
        //                 ->where('UserId', Auth::user()->id)
        //                 ->first();

        //             if (!empty($SelectedIronmongery)) {
        //                     $IronmongeryInfoModel = IronmongeryInfoModel::where('IronmongeryId', $SelectedIronmongery->ironmongery_id)->where('UserId', Auth::user()->id)
        //                         ->first();
        //                     if(empty($IronmongeryInfoModel)){
        //                         $IronmongeryInfoModel = IronmongeryInfoModel::where('id', $SelectedIronmongery->ironmongery_id)->first();
        //                     }

        //                     if (!empty($IronmongeryInfoModel)) {
        //                         $additionalInfo[] = $IronmongeryInfoModel;
        //                     }
        //             }
        //         }
        //     }

        //     // Dynamically add the additional_info attribute
        //     $ironmongery->setAttribute('additional_info', $additionalInfo);
        // }
        // Bulk-load SelectedIronmongery + IronmongeryInfoModel and attach additional_info
        // in memory (no per-row DB queries). Output is identical to the previous
        // nested-loop logic, including quantity duplication and ordering.
        $this->attachIronmongeryAdditionalInfo($setIronmongery, $IronmongeryInfoSet);


        $BOMSetting = BOMSetting::where("id",1)->get()->first();
        $leafTypeIntumescentseal = IntumescentSealLeafType::where('configurableitems',2)->where('status',1)->get();

        return view('Items/Halspan/HalspanDoorConfiguration',[
            "QuotationId" => $item["QuotationId"],
            'Item' => $item,
            'option_data' => $OptionsData,
            'option_data_grouped' => $OptionsDataGrouped,
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
            'folders' => $folders
        ]);
    }

    public function getColors(Request $request)
    {
        $UserIds = CompanyUsers();

        $colors = Color::where('Status', 1)
            ->whereIn('editBy', $UserIds)
            ->select(
                'id',
                'ColorName',
                'Hex',
                'ColorCost'
            )
            ->orderBy('ColorName')
            ->get();

        return response()->json([
            'status' => 'ok',
            'data' => $colors
        ]);
    }
}
