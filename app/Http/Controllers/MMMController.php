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


class MMMController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function add_MMM_door_core($id, $vid = null, $itemId = null)
    {
        $__totalStart = microtime(true);
        // Log::info('add_MMM_door_core START', ['QuotationId' => $id]);

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
        $start = microtime(true);
        $UserIds = CompanyUsers();
        // Log::info('CompanyUsers time', ['ms' => (microtime(true) - $start) * 1000]);

        /* ================= CONFIG FORMULA ================= */
        $start = microtime(true);
        $ConfigurableDoorFormulaData = ConfigurableDoorFormula::where('status', 1)->get();
        // Log::info('ConfigurableDoorFormula time', ['ms' => (microtime(true) - $start) * 1000]);

        /* ================= LIPPING SPECIES ================= */
        $start = microtime(true);
        $LippingSpeciesData = GetOptions(['lipping_species.Status' => 1], "join", "lippingSpecies");
        $SelectedLippingSpeciesData = $LippingSpeciesData;
        // Log::info('LippingSpecies time', [
        //     'count' => is_countable($LippingSpeciesData) ? count($LippingSpeciesData) : 0,
        //     'ms' => (microtime(true) - $start) * 1000
        // ]);

        /* ================= OPTIONS ================= */
        $start = microtime(true);
        $OptionsData = Option::where([
                'configurableitems' => 9,
                'is_deleted' => 0
            ])
            ->whereIn('editBy', $UserIds)
            ->get();

        // Log::info('OptionsData time', [
        //     'count' => is_countable($OptionsData) ? $OptionsData->count() : 0,
        //     'ms' => (microtime(true) - $start) * 1000
        // ]);

        /* ================= INTUMESCENT ARRANGEMENT ================= */
        $start = microtime(true);
        $intumescentSealArrangement = GetOptions(
            ['setting_intumescentseals2.configurableitems' => 9],
            "",
            "intumescentSealArrangement"
        );
        // Log::info('Intumescent arrangement time', ['ms' => (microtime(true) - $start) * 1000]);

        /* ================= USER TYPE CONDITIONAL ================= */
        $start = microtime(true);
        $configurationDoor = configurationDoor(9);
        $UserType = Auth::user()->UserType;

        if (in_array($UserType, [1, 4])) {
            $SelectedOptionsData = $OptionsData;
            $intumescentSealColor = IntumescentSealColor::where([
                    $configurationDoor => 9,
                    'Status' => 1
                ])
                ->whereIn('editBy', $UserIds)
                ->get();

            $ArchitraveType = ArchitraveType::where([
                    $configurationDoor => 9,
                    'Status' => 1
                ])
                ->whereIn('editBy', $UserIds)
                ->get();

            $SelectedIntumescentSealArrangement = $intumescentSealArrangement;
        } else {
            $SelectedOptionsData = GetOptions(
                ['options.configurableitems' => 9, 'options.is_deleted' => 0],
                "join"
            );

            $intumescentSealColor = GetOptions(
                ['intumescent_seal_color.' . $configurationDoor => 9, 'intumescent_seal_color.Status' => 1],
                "join",
                "intumescent_seal_color"
            );

            $ArchitraveType = GetOptions(
                ['architrave_type.' . $configurationDoor => 9, 'architrave_type.Status' => 1],
                "join",
                "architrave_type"
            );

            $SelectedIntumescentSealArrangement = GetOptions(['selected_intumescentseals2.selected_configurableitems'=> 9], "join", "intumescentSealArrangement");

            // $SelectedLippingSpeciesData = GetOptions(['lipping_species.Status'=> 1, 'selected_lipping_species.SelectedStatus'=> 1, 'selected_lipping_species.LippingSpeciesUserId' => Auth::user()->id], "join", "lippingSpecies");

        }

        $company_data = Company::join('users','users.id','companies.UserId')->select('users.*')->get();
        $tooltip = Tooltip::first();
        // Log::info('Tooltip query time', ['ms' => (microtime(true) - $start) * 1000]);

        /* ================= QUOTATION ================= */
        $start = microtime(true);
        $quotation = Quotation::where('id', $id)->first();
        // Log::info('Quotation query time', ['ms' => (microtime(true) - $start) * 1000]);

        /* ================= FOLDERS ================= */
        $start = microtime(true);
        $folders = DB::table('folders')
            ->join('folder_ironmongery_sets', 'folders.id', '=', 'folder_ironmongery_sets.folder_id')
            ->join('add_ironmongery', 'folder_ironmongery_sets.add_ironmongery_id', '=', 'add_ironmongery.id')
            ->where('folders.user_id', Auth::user()->id)
            ->get()
            ->groupBy('folder_id');

        // Log::info('Folders join time', ['ms' => (microtime(true) - $start) * 1000]);

        /* ================= IRONMONGERY ================= */
        $start = microtime(true);
        $setIronmongery = AddIronmongery::whereIn('UserId', $userId)
            ->orderBy('Setname', 'ASC')
            ->get();

        // Log::info('SetIronmongery fetch time', [
        //     'count' => is_countable($setIronmongery) ? $setIronmongery->count() : 0,
        //     'ms' => (microtime(true) - $start) * 1000
        // ]);

        /* ================= SPECIES ================= */
        $start = microtime(true);
        $species = GetOptions(
            ['leaf_type.MMM' => 9, 'leaf_type.Status' => 1],
            "join",
            "leaf_type"
        );
        // Log::info('Species time', ['ms' => (microtime(true) - $start) * 1000]);

        /* ================= BOM SETTING ================= */
        $start = microtime(true);
        $BOMSetting = BOMSetting::where('id', 1)->first();
        // Log::info('BOMSetting time', ['ms' => (microtime(true) - $start) * 1000]);

        // Log::info('add_MMM_door_core END', [
        //     'total_ms' => (microtime(true) - $__totalStart) * 1000
        // ]);

        return view('Items/MMM/MMMConfigurableItem', [
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
            'issingleconfiguration' => '9',
            'versionId' => $vid,
            'tooltip' => $tooltip,
            'setIronmongery' => $setIronmongery,
            'BOMSetting' => $BOMSetting,
            'quotation' => $quotation,
            'species' => $species,
            'folders' => $folders
        ]);
    }


    public function edit_MMM_door_core($id, $vid = null, $itemId = null)
    {
        $__totalStart = microtime(true);
       // Log::info('edit_MMM_door_core START', ['ItemId' => $id]);

        /* ================= USER IDS ================= */
        $start = microtime(true);
        if (auth()->user()->UserType == 2) {
            $userId = [1, auth()->user()->id];
        } elseif (auth()->user()->UserType == 3) {
            $userId = [1, auth()->user()->CreatedBy];
        } else {
            $userId = [];
        }
        Log::info('UserId resolve time', ['ms' => (microtime(true) - $start) * 1000]);

        /* ================= ITEM ================= */
        $start = microtime(true);
        $item = Item::where('itemId', $id)->first();
        if (!$item) {
            return abort(404);
        }
        $item = $item->toArray();
        Log::info('Item fetch time', ['ms' => (microtime(true) - $start) * 1000]);

        $UserIds = CompanyUsers();

        /* ================= CONFIG FORMULA ================= */
        $start = microtime(true);
        $ConfigurableDoorFormulaData = ConfigurableDoorFormula::where('status', 1)->get();
        Log::info('ConfigurableDoorFormula time', ['ms' => (microtime(true) - $start) * 1000]);

        /* ================= LIPPING SPECIES (CACHED) ================= */
        $start = microtime(true);
        $LippingSpeciesData = Cache::remember(
            'mmm_lipping_species_v1',
            now()->addHours(6),
            function () {
                return GetOptions(['lipping_species.Status' => 1], "join", "lippingSpecies");
            }
        );
        $SelectedLippingSpeciesData = $LippingSpeciesData;
        Log::info('LippingSpecies time (cached)', [
            'count' => is_countable($LippingSpeciesData) ? count($LippingSpeciesData) : 0,
            'ms' => (microtime(true) - $start) * 1000
        ]);

        /* ================= OPTIONS ================= */
        $start = microtime(true);
        $OptionsData = Option::where(['configurableitems' => 9, 'is_deleted' => 0])
            ->whereIn('editBy', $UserIds)
            ->get();
        Log::info('OptionsData time', [
            'count' => is_countable($OptionsData) ? count($OptionsData) : 0,
            'ms' => (microtime(true) - $start) * 1000
        ]);

        /* ================= INTUMESCENT ARRANGEMENT ================= */
        $start = microtime(true);
        $intumescentSealArrangement = GetOptions(
            ['setting_intumescentseals2.configurableitems' => 9],
            "",
            "intumescentSealArrangement"
        );
        Log::info('Intumescent arrangement time', ['ms' => (microtime(true) - $start) * 1000]);

        /* ================= USER TYPE BLOCK (CACHED) ================= */
        $start = microtime(true);
        $configurationDoor = configurationDoor(9);
        $UserType = Auth::user()->UserType;
        $cacheKey = 'mmm_userblock_' . $UserType . '_door9';

        $userBlock = Cache::remember(
            $cacheKey,
            now()->addHours(4),
            function () use ($UserType, $configurationDoor, $UserIds, $OptionsData, $intumescentSealArrangement) {
                if (in_array($UserType, [1, 4])) {
                    return [
                        'SelectedOptionsData' => $OptionsData,
                        'intumescentSealColor' => IntumescentSealColor::where([$configurationDoor => 9, 'Status' => 1])
                            ->whereIn('editBy', $UserIds)->get(),
                        'ArchitraveType' => ArchitraveType::where([$configurationDoor => 9, 'Status' => 1])
                            ->whereIn('editBy', $UserIds)->get(),
                        'SelectedIntumescentSealArrangement' => $intumescentSealArrangement
                    ];
                }

                return [
                    'SelectedOptionsData' => GetOptions(['options.configurableitems' => 9, 'options.is_deleted' => 0], "join"),
                    'intumescentSealColor' => GetOptions(
                        ['intumescent_seal_color.' . $configurationDoor => 9, 'intumescent_seal_color.Status' => 1],
                        "join",
                        "intumescent_seal_color"
                    ),
                    'ArchitraveType' => GetOptions(
                        ['architrave_type.' . $configurationDoor => 9, 'architrave_type.Status' => 1],
                        "join",
                        "architrave_type"
                    ),
                    'SelectedIntumescentSealArrangement' => GetOptions(
                        ['selected_intumescentseals2.selected_configurableitems' => 9],
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

        Log::info('UserType conditional block time (cached)', [
            'ms' => (microtime(true) - $start) * 1000,
            'cached' => Cache::has($cacheKey)
        ]);

        /* ================= COLORS ================= */
        $start = microtime(true);
        Log::info('Color query time', ['ms' => (microtime(true) - $start) * 1000]);

        /* ================= COMPANY ================= */
        $start = microtime(true);
        $company_data = Company::join('users', 'users.id', 'companies.UserId')
            ->select('users.*')
            ->get();
        Log::info('Company join time', ['ms' => (microtime(true) - $start) * 1000]);

        /* ================= TOOLTIP ================= */
        $tooltip = Tooltip::first();

        /* ================= QUOTATION ================= */
        $quotation = Quotation::where('id', $item['QuotationId'])->first();

        /* ================= FOLDERS ================= */
        $start = microtime(true);
        $folders = DB::table('folders')
            ->join('folder_ironmongery_sets', 'folders.id', '=', 'folder_ironmongery_sets.folder_id')
            ->join('add_ironmongery', 'folder_ironmongery_sets.add_ironmongery_id', '=', 'add_ironmongery.id')
            ->where('folders.user_id', Auth::user()->id)
            ->get()
            ->groupBy('folder_id');
        Log::info('Folders join time', ['ms' => (microtime(true) - $start) * 1000]);

        /* ================= IRONMONGERY ================= */
        $start = microtime(true);
        $setIronmongery = AddIronmongery::whereIn('UserId', [Auth::user()->id])
            ->orderBy('Setname', 'ASC')
            ->get();
        Log::info('SetIronmongery fetch time', [
            'count' => is_countable($setIronmongery) ? count($setIronmongery) : 0,
            'ms' => (microtime(true) - $start) * 1000
        ]);

        /* ================= SPECIES ================= */
        $start = microtime(true);
        $species = DB::table('leaf_type')
            ->where('MMM', 9)
            ->where('Status', 1)
            ->whereIn('EditBy', $userId)
            ->get();
        Log::info('Species time', ['ms' => (microtime(true) - $start) * 1000]);

        /* ================= BOM ================= */
        $BOMSetting = BOMSetting::where('id', 1)->first();

        Log::info('edit_MMM_door_core END', [
            'total_ms' => (microtime(true) - $__totalStart) * 1000
        ]);

        return view('Items/MMM/MMMConfigurableItem', [
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
            'issingleconfiguration' => '9',
            'versionId' => $vid,
            'tooltip' => $tooltip,
            'setIronmongery' => $setIronmongery,
            'BOMSetting' => $BOMSetting,
            'quotation' => $quotation,
            'species' => $species,
            'folders' => $folders
        ]);
    }


}
