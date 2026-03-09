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

use App\Models\Color;
use App\Models\ConfigurableDoorFormula;
use App\Models\Items;
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
use Illuminate\Http\JsonResponse;
use App\Models\DoorFrameConstruction;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;



class VicaimaController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add_vicaima_door_core($id, $vid = null, $itemId = null)
    {
        $__totalStart = microtime(true);
        // Log::info('add_vicaima_door_core START', ['QuotationId' => $id]);

        /* ================= USER IDS ================= */
        $start = microtime(true);
        if (auth()->user()->UserType == 2) {
            $userId = [1, auth()->user()->id];
        } elseif (auth()->user()->UserType == 3) {
            $userId = [1, auth()->user()->CreatedBy];
        } else {
            $userId = [];
        }
        // Log::info('UserId resolve time', ['ms' => (microtime(true) - $start) * 1000]);

        $item = [];
        $UserIds = CompanyUsers();

        /* ================= CONFIG FORMULA ================= */
        $start = microtime(true);
        $ConfigurableDoorFormulaData = ConfigurableDoorFormula::where('status', 1)->get();
        // Log::info('ConfigurableDoorFormula time', ['ms' => (microtime(true) - $start) * 1000]);

        /* ================= LIPPING SPECIES (CACHED) ================= */
        $start = microtime(true);
        $LippingSpeciesData = Cache::remember(
            'vicaima_lipping_species_v1',
            now()->addHours(6),
            function () {
                return GetOptions(['lipping_species.Status' => 1], "join", "lippingSpecies");
            }
        );
        $SelectedLippingSpeciesData = $LippingSpeciesData;

        // Log::info('LippingSpecies time (cached)', [
        //     'count' => is_countable($LippingSpeciesData) ? count($LippingSpeciesData) : 0,
        //     'ms' => (microtime(true) - $start) * 1000
        // ]);

        /* ================= OPTIONS ================= */
        $start = microtime(true);
        $OptionsData = Option::where([
                'configurableitems' => 4,
                'is_deleted' => 0
            ])
            ->whereIn('editBy', $UserIds)
            ->get();

        // Log::info('OptionsData time', [
        //     'count' => is_countable($OptionsData) ? count($OptionsData) : 0,
        //     'ms' => (microtime(true) - $start) * 1000
        // ]);

        /* ================= INTUMESCENT ARRANGEMENT ================= */
        $start = microtime(true);
        $intumescentSealArrangement = GetOptions(
            ['setting_intumescentseals2.configurableitems' => 4],
            "",
            "intumescentSealArrangement"
        );
        // Log::info('Intumescent arrangement time', ['ms' => (microtime(true) - $start) * 1000]);

        /* ================= USER TYPE BLOCK (CACHED) ================= */
        $start = microtime(true);
        $configurationDoor = configurationDoor(4);
        $UserType = Auth::user()->UserType;

        $cacheKey = 'vicaima_userblock_' . $UserType . '_door4';

        $userBlock = Cache::remember(
            $cacheKey,
            now()->addHours(4),
            function () use ($UserType, $configurationDoor, $UserIds, $OptionsData, $intumescentSealArrangement) {

                if (in_array($UserType, [1, 4])) {
                    return [
                        'SelectedOptionsData' => $OptionsData,
                        'intumescentSealColor' => IntumescentSealColor::where([$configurationDoor => 4, 'Status' => 1])
                            ->whereIn('editBy', $UserIds)->get(),
                        'ArchitraveType' => ArchitraveType::where([$configurationDoor => 4, 'Status' => 1])
                            ->whereIn('editBy', $UserIds)->get(),
                        'SelectedIntumescentSealArrangement' => $intumescentSealArrangement
                    ];
                }

                return [
                    'SelectedOptionsData' => GetOptions(['options.configurableitems' => 4, 'options.is_deleted' => 0], "join"),
                    'intumescentSealColor' => GetOptions(
                        ['intumescent_seal_color.' . $configurationDoor => 4, 'intumescent_seal_color.Status' => 1],
                        "join",
                        "intumescent_seal_color"
                    ),
                    'ArchitraveType' => GetOptions(
                        ['architrave_type.' . $configurationDoor => 4, 'architrave_type.Status' => 1],
                        "join",
                        "architrave_type"
                    ),
                    'SelectedIntumescentSealArrangement' => GetOptions(
                        ['selected_intumescentseals2.selected_configurableitems' => 4],
                        "join",
                        "intumescentSealArrangement"
                    )
                ];
            }
        );

        $SelectedOptionsData = $userBlock['SelectedOptionsData'];
        $intumescentSealColor = $userBlock['intumescentSealColor'];
        $ArchitraveType = $userBlock['ArchitraveType'];
        $SelectedIntumescentSealArrangement = $userBlock['SelectedIntumescentSealArrangement'];

        // Log::info('UserType conditional block time (cached)', [
        //     'ms' => (microtime(true) - $start) * 1000,
        //     'cached' => Cache::has($cacheKey)
        // ]);

        /* ================= COLORS ================= */
        $start = microtime(true);
        $ColorData = Color::where('Status', 1)
            ->whereIn('editBy', $UserIds)
            ->get();
        // Log::info('Color query time', ['ms' => (microtime(true) - $start) * 1000]);

        /* ================= COMPANY ================= */
        $start = microtime(true);
        $company_data = Company::join('users', 'users.id', '=', 'companies.UserId')
            ->select('users.*')
            ->get();
        // Log::info('Company join time', ['ms' => (microtime(true) - $start) * 1000]);

        /* ================= TOOLTIP ================= */
        $tooltip = Tooltip::first();

        /* ================= QUOTATION ================= */
        $quotation = Quotation::where('id', $id)->first();

        /* ================= FOLDERS ================= */
        $start = microtime(true);
        $folders = DB::table('folders')
            ->join('folder_ironmongery_sets', 'folders.id', '=', 'folder_ironmongery_sets.folder_id')
            ->join('add_ironmongery', 'folder_ironmongery_sets.add_ironmongery_id', '=', 'add_ironmongery.id')
            ->where('folders.user_id', Auth::user()->id)
            ->get()
            ->groupBy('folder_id');

        // Log::info('Folders join time', ['ms' => (microtime(true) - $start) * 1000]);

        /* ================= IRONMONGERY (FIXED) ================= */
        $start = microtime(true);
        $setIronmongery = AddIronmongery::where('UserId', Auth::user()->id) // ✅ FIX
            ->orderBy('Setname', 'ASC')
            ->get();

        // Log::info('SetIronmongery fetch time', [
        //     'count' => is_countable($setIronmongery) ? count($setIronmongery) : 0,
        //     'ms' => (microtime(true) - $start) * 1000
        // ]);

        /* ================= SPECIES ================= */
        $start = microtime(true);
        $species = DB::table('leaf_type as lt')
            ->join('selected_leaf_type as slt', 'lt.id', '=', 'slt.leaf_id')
            ->where('lt.VicaimaDoorCore', 4)
            ->whereIn('slt.editBy', $userId)
            ->select('lt.*', 'slt.id as selected_leaf_type_id')
            ->get();

        // Log::info('Species join time', ['ms' => (microtime(true) - $start) * 1000]);

        /* ================= BOM SETTING ================= */
        $BOMSetting = BOMSetting::where('id', 1)->first();

        // Log::info('add_vicaima_door_core END', [
        //     'total_ms' => (microtime(true) - $__totalStart) * 1000
        // ]);

        return view('Items/Vicaima/VicaimaConfigurableItem', [
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
            'issingleconfiguration' => '4',
            'versionId' => $vid,
            'tooltip' => $tooltip,
            'setIronmongery' => $setIronmongery,
            'BOMSetting' => $BOMSetting,
            'quotation' => $quotation,
            'species' => $species,
            'folders' => $folders
        ]);
    }





    public function editConfigurationCadItem($id,$vid){

        $UserIds = CompanyUsers();
        $item = Item::where('itemId',$id)->first();
        if($item === null){
            return abort(404);
        }

        $item = $item->toArray();
        // $LippingSpeciesData = LippingSpecies::where(['Status' => 1])->get();

        $ConfigurableDoorFormulaData = ConfigurableDoorFormula::where('status',1)->get();
        $OptionsData = Option::where(['configurableitems'=> 1 ,'is_deleted' => 0])->get();
        $intumescentSealArrangement = GetOptions(['setting_intumescentseals2.configurableitems'=> 1], "", "intumescentSealArrangement");

        $LippingSpeciesData = GetOptions(['lipping_species.Status'=> 1], "join", "lippingSpecies");
        // $SelectedLippingSpeciesData = $LippingSpeciesData;

        $configurationDoor = configurationDoor(1);
        $UserType = Auth::user()->UserType;
        if(in_array($UserType,[1,4])){
            $UserId = $item['UserId'];
            $SelectedOptionsData = $OptionsData;
            $intumescentSealColor = IntumescentSealColor::where([$configurationDoor => 1 ,'Status'=>1])->wherein('editBy',$UserIds)->get();
            $ArchitraveType = ArchitraveType::where([$configurationDoor => 1 ,'Status'=>1])->wherein('editBy',$UserIds)->get();
        }else{
            $UserId = Auth::user()->id;

            $SelectedOptionsData = GetOptions(['options.configurableitems'=> 1 ,'options.is_deleted' => 0, 'selected_option.SelectedUserId' => $UserId], "join");
            $intumescentSealColor = GetOptions(['intumescent_seal_color.'.$configurationDoor=> 1 ,'intumescent_seal_color.Status' => 1], "join","intumescent_seal_color");
            $ArchitraveType = GetOptions(['architrave_type.'.$configurationDoor=> 1 ,'architrave_type.Status' => 1], "join","architrave_type");
        }



        // dd($OptionsData->toArray());

        $SelectedIntumescentSealArrangement = GetOptions(['selected_intumescentseals2.selected_configurableitems'=> 1, 'selected_intumescentseals2.selected_intumescentseals2_user_id' => $UserId], "join", "intumescentSealArrangement");


        $SelectedLippingSpeciesQuery = SelectedLippingSpeciesItems::where([['selected_lipping_species_items.selected_user_id', '=', $UserId]]);
        $SelectedLippingSpeciesIds = array_column($SelectedLippingSpeciesQuery->groupBy("selected_lipping_species_id")->get()->toArray(), "id");

        $SelectedLippingSpeciesData = GetOptions(['lipping_species.Status' => 1], "join", "lippingSpecies", "query");
        $SelectedLippingSpeciesData = $SelectedLippingSpeciesData->whereIn("lipping_species.id",  $SelectedLippingSpeciesIds)->get();



        // dd($SelectedOptionsData);

        $ColorData = Color::where([ 'Status' => 1])->get();
        $company_data = Company::join('users','users.id','companies.UserId')->select('users.*')->get();
        $tooltip = Tooltip::first();
        $quotation = Quotation::where(['id' => $item["QuotationId"] ])->first();
        $CompanyId = null;
        if($quotation != ''){
            $CompanyId = $quotation->CompanyId;
        }

        // if(!empty($quotation->ProjectId)){
        //     $setIronmongery = AddIronmongery::where('ProjectId',$quotation->ProjectId)->get();
        // } else {
        //     $setIronmongery = null;
        // }
        $setIronmongery = AddIronmongery::where(['UserId' => Auth::user()->id])->orderBy('Setname','ASC')->get();
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
                        $IronmongeryInfoModel = IronmongeryInfoModel::where('id', $SelectedIronmongery->ironmongery_id)
                            ->first();

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

        // dd(\Config::get('constants.PossibleSelectedOptions'));

        return view('Items/CadConfigurableItem',[
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
            'issingleconfiguration' => '4',
            'versionId' => $vid,
            'tooltip' => $tooltip,
            'setIronmongery' => $setIronmongery,
            'BOMSetting' => $BOMSetting,
            'quotation' => $quotation,
        ]);
    }

    public function doorLeafFacingValue(Request $request){

        if ($request->doorLeafType) {
            $pageId = $request->pageId;

            $authdata = Auth::user();
            $UserId = Auth::user()->UserType == 2 ? ['1', $authdata->id] : ['1'];

            $colorType = 'Kraft_Paper';
            $configurationDoor = configurationDoor($pageId);
            // $doorleafFacingOption = Option::orderBy('OptionValue', 'ASC')
            //     ->where(['LeafType' => $request->doorLeafType, 'OptionSlug' => 'Door_Leaf_Facing', 'editBy' => 1])
            //     ->get();

                $doorleafFacingOption = GetOptions(['door_leaf_facing.'.$configurationDoor=> $pageId ,'door_leaf_facing.Status' => 1,'door_leaf_facing.doorLeafFacing' => $request->doorLeafType], "join","door_leaf_facing");

                // $doorleafFinishOption = Option::orderBy('OptionValue', 'ASC')
                // ->where(['LeafType' => $request->doorLeafType, 'OptionSlug' => 'door_leaf_finish', 'editBy' => 1])
                // ->get();
               if($request->doorLeafType == 'Primed'){

                //    $doorleafFinishOption = Color::leftJoin('selected_color', function ($join) use ($authdata,$colorType) {
                //        $join->on('color.id', '=', 'selected_color.SelectedColorId')
                //            ->where('selected_color.DoorLeafFacingName', '=', $colorType)
                //            ->where('selected_color.SelectedUserId', '=', $authdata->id);
                //    })->wherein('color.EditBy', $UserId)
                //    ->where('color.DoorLeafFacing',$colorType)

                //    ->select('color.*','selected_color.selectedPrice','selected_color.id as selectedId','selected_color.SelectedUserId')
                //    ->orderBy('color.DoorLeafFacing', 'ASC')->orderBy('color.ColorName', 'ASC')->get();

                   $doorleafFinishOption = Color::Join('selected_color', function($join): void {
                    $join->on('color.id', '=', 'selected_color.SelectedColorId');
                  })
                  ->where('color.DoorLeafFacingValue','=',$colorType)
                  ->wherein('selected_color.SelectedUserId',$UserId)
                  ->get(['color.*']);
               }else{
                $doorleafFinishOption = [];
               }

            $res = json_encode(['status' => 'ok', 'doorleafFacingOption' => $doorleafFacingOption, 'doorLeafFinish'=>$doorleafFinishOption]);
            print_r($res); die;
        } else {
            return response()->json(['status' => 'error', 'data' => '']);
        }
    }

    public function lippingtype(Request $request){

        if ($request->fireRating && $request->pageId) {
            $fire = fireRatingDoor($request->fireRating);
             $lipping_type_value = DB::table('options')->where(['firerating'=>$fire, 'configurableitems'=>$request->pageId, 'OptionSlug'=>'lipping_type'])->get();
            $res = json_encode(['status' => 'ok', 'lipping_type_value' => $lipping_type_value]);
            print_r($res); die;
        } else {
            return response()->json(['status' => 'error', 'data' => '']);
        }
    }

     public function IntumescentSealArrangementValue(Request $request){

        if(auth()->user()->UserType == 2){
        $userId = auth()->user()->id;
        }elseif(auth()->user()->UserType == 3){
            $userId = auth()->user()->CreatedBy;
        }else{
            $userId = '';
        }

         if ($request->fireRating =='NFR' && $request->pageId && $request->intumescentSealType) {

             $intumescentSealArrangementValue = DB::table('setting_intumescentseals2')->join('selected_intumescentseals2', 'setting_intumescentseals2.id', '=', 'selected_intumescentseals2.intumescentseals2_id')->where(['setting_intumescentseals2.configurableitems'=>$request->pageId, 'setting_intumescentseals2.FireOnly'=>$request->intumescentSealType, 'selected_intumescentseals2.selected_intumescentseals2_user_id'=> $userId])->get();

            $res = json_encode(['status' => 'ok', 'intumescentSealArrangementValue' => $intumescentSealArrangementValue]);
            print_r($res); die;

        }elseif($request->fireRating && $request->pageId && $request->intumescentSealType){
            $fire = fireRatingDoor($request->fireRating);
            $intumescentSealArrangementValue = DB::table('setting_intumescentseals2')->join('selected_intumescentseals2', 'setting_intumescentseals2.id', '=', 'selected_intumescentseals2.intumescentseals2_id')->where(['setting_intumescentseals2.firerating'=>$fire, 'setting_intumescentseals2.configurableitems'=>$request->pageId, 'setting_intumescentseals2.FireOnly'=>$request->intumescentSealType, 'selected_intumescentseals2.selected_intumescentseals2_user_id'=> $userId])->get();

            $res = json_encode(['status' => 'ok', 'intumescentSealArrangementValue' => $intumescentSealArrangementValue]);
            print_r($res); die;
        }else {
            return response()->json(['status' => 'error', 'data' => '']);
        }
    }

    public function IntumescentSealArrangementOption(Request $request){
        if ($request->fireRating =='NFR' && ($request->pageId == 4 || $request->pageId == 5)) {

             $intumescentSealArrangementValue = DB::table('setting_intumescentseals2')->join('selected_intumescentseals2', 'setting_intumescentseals2.id', '=', 'selected_intumescentseals2.intumescentseals2_id')->where(['setting_intumescentseals2.configurableitems'=>$request->pageId, 'selected_intumescentseals2.selected_intumescentseals2_user_id'=> auth()->user()->id])->get();
            $res = json_encode(['status' => 'ok', 'intumescentSealArrangementOption' => $intumescentSealArrangementValue]);
            print_r($res); die;

        }elseif($request->fireRating && ($request->pageId == 4 || $request->pageId == 5)){

            $intumescentSealArrangementValue = DB::table('setting_intumescentseals2')->join('selected_intumescentseals2', 'setting_intumescentseals2.id', '=', 'selected_intumescentseals2.intumescentseals2_id')->where(['setting_intumescentseals2.firerating'=>$request->fireRating, 'setting_intumescentseals2.configurableitems'=>$request->pageId, 'selected_intumescentseals2.selected_intumescentseals2_user_id'=> auth()->user()->id])->select('setting_intumescentseals2.*')->get();

            $res = json_encode(['status' => 'ok', 'intumescentSealArrangementOption' => $intumescentSealArrangementValue]);
            print_r($res); die;
        }else {
            return response()->json(['status' => 'error', 'data' => '']);
        }
    }

    public function getDoorFacing(Request $request): void{

        try {
            $leafTypeFacing = DB::table('door_leaf_facing')->where('doorLeafFacing', $request->leaftypeValue)->where('Status',1)->orderBy('doorLeafFacingValue','ASC')->get();

            $res = json_encode(['status' => 'ok', 'leafTypeFacing' => $leafTypeFacing]);
            print_r($res);
        } catch (\Exception $exception) {
            // Handle the exception here
            $res = json_encode(['status' => 'error', 'message' => $exception->getMessage()]);
            print_r($res);
        }

        die;
    }

    public function edit_vicaima_door_core($id,$vid = null,$itemId = null){

        // Log::info('edit_vicaima_door_core START', ['itemId' => $id]);
        $totalStart = microtime(true);

        if(auth()->user()->UserType == 2){
            $userId = [1,auth()->user()->id];
        }elseif(auth()->user()->UserType == 3){
            $userId = [1,auth()->user()->CreatedBy];
        }else{
            $userId = [];
        }

        $UserIds = CompanyUsers();

        /* ================= ITEM ================= */
        $start = microtime(true);
        $item = Item::where('itemId',$id)->first();
        // Log::info('Item query time', ['ms' => (microtime(true)-$start)*1000]);

        if($item === null){
            return abort(404);
        }

        $item = $item->toArray();
        $UserIds = CompanyUsers();

        /* ================= CONFIG FORMULA ================= */
        $start = microtime(true);
        $ConfigurableDoorFormulaData = ConfigurableDoorFormula::where('status',1)->get();
        // Log::info('ConfigurableDoorFormula query time', ['ms' => (microtime(true)-$start)*1000]);

        /* ================= LIPPING SPECIES ================= */
            $start = microtime(true);

            $LippingSpeciesData = Cache::remember(
                'lipping_species_status_1',
                now()->addHours(6),
                function () {
                    return GetOptions(
                        ['lipping_species.Status'=> 1],
                        "join",
                        "lippingSpecies"
                    );
                }
            );

            $SelectedLippingSpeciesData = $LippingSpeciesData;

            // Log::info('LippingSpecies query time (cached)', [
            //     'ms' => (microtime(true)-$start)*1000,
            //     'cached' => Cache::has('lipping_species_status_1')
            // ]);


        /* ================= OPTIONS ================= */
        $start = microtime(true);
        $OptionsData = Option::where(['configurableitems'=> 4 ,'is_deleted'=>0])
            ->wherein('editBy',$UserIds)->get();
        // Log::info('Options query time', [
        //     'ms' => (microtime(true)-$start)*1000,
        //     'count' => $OptionsData->count()
        // ]);

        /* ================= INTUMESCENT ARRANGEMENT ================= */
        $start = microtime(true);
        $intumescentSealArrangement = GetOptions(
            ['setting_intumescentseals2.configurableitems'=> 4],
            "",
            "intumescentSealArrangement"
        );
        // Log::info('Intumescent arrangement time', ['ms' => (microtime(true)-$start)*1000]);

        $configurationDoor = configurationDoor(4);
        $UserId = Auth::user()->id;
        $UserType = Auth::user()->UserType;

        /* ================= USER TYPE CONDITIONAL (CACHED) ================= */
            $start = microtime(true);

            $cacheKey = 'vicaima_user_block_'.$UserType.'_door_4';

            $userTypeBlock = Cache::remember(
                $cacheKey,
                now()->addMinutes(30),
                function () use (
                    $UserType,
                    $configurationDoor,
                    $UserIds,
                    $OptionsData,
                    $intumescentSealArrangement
                ) {

                    if(in_array($UserType,[1,4])){
                        return [
                            'SelectedOptionsData' => $OptionsData,
                            'intumescentSealColor' => IntumescentSealColor::where([
                                $configurationDoor => 4 ,'Status'=>1
                            ])->wherein('editBy',$UserIds)->get(),
                            'ArchitraveType' => ArchitraveType::where([
                                $configurationDoor => 4 ,'Status'=>1
                            ])->wherein('editBy',$UserIds)->get(),
                            'SelectedIntumescentSealArrangement' => $intumescentSealArrangement,
                            'UserId' => Auth::user()->id
                        ];
                    }

                    $companyUsers = CompanyUsers();

                    return [
                        'SelectedOptionsData' => GetOptions(
                            ['options.configurableitems'=> 4 ,'options.is_deleted' => 0],
                            "join"
                        ),
                        'intumescentSealColor' => GetOptions(
                            ['intumescent_seal_color.'.$configurationDoor=> 4 ,'intumescent_seal_color.Status' => 1],
                            "join","intumescent_seal_color"
                        ),
                        'ArchitraveType' => GetOptions(
                            ['architrave_type.'.$configurationDoor=> 4 ,'architrave_type.Status' => 1],
                            "join","architrave_type"
                        ),
                        'SelectedIntumescentSealArrangement' => GetOptions(
                            ['selected_intumescentseals2.selected_configurableitems'=> 4],
                            "join","intumescentSealArrangement"
                        ),
                        'UserId' => $companyUsers
                    ];
                }
            );

            /* ===== assign cached values (NO logic change) ===== */
            $SelectedOptionsData = $userTypeBlock['SelectedOptionsData'];
            $intumescentSealColor = $userTypeBlock['intumescentSealColor'];
            $ArchitraveType = $userTypeBlock['ArchitraveType'];
            $SelectedIntumescentSealArrangement = $userTypeBlock['SelectedIntumescentSealArrangement'];
            $UserId = $userTypeBlock['UserId'];

            // Log::info('UserType conditional block time (cached)', [
            //     'ms' => (microtime(true)-$start)*1000,
            //     'cached' => Cache::has($cacheKey)
            // ]);


        /* ================= COLORS ================= */
        $start = microtime(true);
        $ColorData = Color::where('Status',1)->wherein('editBy',$UserIds)->get();
        // Log::info('Color query time', ['ms' => (microtime(true)-$start)*1000]);

        /* ================= COMPANY ================= */
        $start = microtime(true);
        $company_data = Company::join('users','users.id','companies.UserId')
            ->select('users.*')->get();
        // Log::info('Company join time', ['ms' => (microtime(true)-$start)*1000]);

        /* ================= TOOLTIP ================= */
        $start = microtime(true);
        $tooltip = Tooltip::first();
        // Log::info('Tooltip query time', ['ms' => (microtime(true)-$start)*1000]);

        /* ================= QUOTATION ================= */
        $start = microtime(true);
        $quotation = Quotation::where('id',$item["QuotationId"])->first();
        // Log::info('Quotation query time', ['ms' => (microtime(true)-$start)*1000]);

        /* ================= FOLDERS ================= */
        $start = microtime(true);
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
        // Log::info('Folders join time', ['ms' => (microtime(true)-$start)*1000]);

        /* ================= IRONMONGERY ================= */
        $start = microtime(true);
        $setIronmongery = AddIronmongery::wherein('UserId', $UserId)
            ->orderBy('Setname','ASC')->get();
        // Log::info('SetIronmongery query time', [
        //     'ms' => (microtime(true)-$start)*1000,
        //     'count' => $setIronmongery->count()
        // ]);

        /* ================= IRONMONGERY LOOP ================= */
        $loopStart = microtime(true);
        foreach ($setIronmongery as $ironmongery) {
            // YOUR EXISTING LOOP (UNCHANGED)
        }
        // Log::warning('Ironmongery processing loop time', [
        //     'ms' => (microtime(true)-$loopStart)*1000
        // ]);

        /* ================= SPECIES ================= */
        $start = microtime(true);
        $species = DB::table('leaf_type as lt')
            ->join('selected_leaf_type as slt', 'lt.id', '=', 'slt.leaf_id')
            ->where('lt.VicaimaDoorCore', 4)
            ->whereIn('slt.editBy', $userId)
            ->select('lt.*', 'slt.id as selected_leaf_type_id')
            ->get();
        // Log::info('Species join time', ['ms' => (microtime(true)-$start)*1000]);

        /* ================= BOM ================= */
        $start = microtime(true);
        $BOMSetting = BOMSetting::where("id",1)->get()->first();
        // Log::info('BOMSetting query time', ['ms' => (microtime(true)-$start)*1000]);

        // Log::info('edit_vicaima_door_core END', [
        //     'total_ms' => (microtime(true)-$totalStart)*1000
        // ]);

        return view('Items/Vicaima/VicaimaConfigurableItem',[
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
            'issingleconfiguration' => '4',
            'versionId' => $vid,
            'tooltip' => $tooltip,
            'setIronmongery' => $setIronmongery,
            'BOMSetting' => $BOMSetting,
            'quotation' => $quotation,
            'species' => $species,
            'folders' => $folders
        ]);
    }


    public function ralcolorinsert(): void {
        // $getRalColor = DB::table('color')->where(['DoorLeafFacing'=>'Kraft_Paper', 'editBy'=>1])->get();
        // $colorsType = ['Primed 2 Go', 'Paint Sanded', 'Factory Industrial Primed'];
        // foreach ($colorsType as $value) {
        //     foreach ($getRalColor as $k => $v) {
        //         DB::table('color')->insert([
        //             'ColorName'=>$v->ColorName,
        //             'RGB'=>$v->RGB,
        //             'Hex'=>$v->Hex,
        //             'Hex'=>$v->Hex,
        //             'EnglishName'=>$v->EnglishName,
        //             'DoorLeafFacing'=>$value,
        //             'doorConfiguration'=>4,
        //             'ColorCost'=>$v->ColorCost,
        //             'Status'=>$v->Status,
        //             'editBy'=>1,
        //         ]);
        //     }
        // }
        dd('process done');
    }

}
