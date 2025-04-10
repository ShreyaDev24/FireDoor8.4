<?php

namespace App\Http\Controllers;

use App\Models\Quotation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Option;
use App\Models\Company;
use App\Models\Color;
use App\Models\SideScreenItem;
use App\Models\Doors;
use App\Models\LippingSpecies;
use App\Models\SelectedLippingSpeciesItems;
use App\Models\DoorSchedule;
use App\Models\Items;
use Illuminate\Support\Facades\DB;
use App\Models\QuotationVersion;
use App\Models\QuotationVersionItems;
use App\Models\ItemMaster;
use App\Models\SettingIntumescentSeals2;
use App\Models\BOMCalculation;
use App\Models\AddIronmongery;
use App\Models\GlassGlazingSystem;
use App\Models\GlazingSystem;
use App\Models\screenBOMCalculation;
use App\Models\GlassType;
use App\Models\OverpanelGlassGlazing;
use App\Models\DoorLeafFacing;
use App\Models\BOMSetting;
use App\Models\ScreenGlassType;
use App\Models\SideScreenItemMaster;

class ItemListController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $item=Item::all();
        $options_data = Option::where('is_deleted',0)->get();
        $company_data = Company::join('users','users.id','companies.UserId')->select('users.*')->get();
        return view('Items/AddItem',['itemlist'=>$item,'option_data'=>$options_data,'company_list'=>$company_data]);
        // return view('Forms.itemform123214893',['itemlist'=>$item,'option_data'=>$options_data,'company_list'=>$company_data]);
        // return view('Items/mbk/AddItem',['itemlist'=>$item,'option_data'=>$options_data,'company_list'=>$company_data]);
    }

    public function itmeList()
    {
        $item=Item::all();
        return view('itemlist',['itemlist'=>$item]);
    }

    public function changeStatus(Request $request)
    {
        if($request->id){
            $Item               = Item::find(crypt::decrypt($request->id));
            if($Item){
                $Item->ItemStatus   = ($Item->ItemStatus==1) ? 2 : 1;
                $result=$Item->save();
                $statuscode=http_response_code();
            }else{
                $statuscode='fail';
            }
        }

        return response()->json(['success'=>$statuscode,'item'=>$Item]);
    }


    public function tab(){


        $item=Item::all();
        return view('items.tab',['itemlist'=>$item]);
    }


    public function filterFireRating(Request $request): void{

        $pageId = $request->pageId;
        $fireRating = $request->fireRating;

        $integrityValue = $request->integrity;
        $integrityValue2 = $request->integrity2;
        $userIds = CompanyUsers();
        $integrity = "";

        if($fireRating == 'FD30' || $fireRating == 'FD30s'){
            $fireRating = 'FD30';
        }elseif($fireRating == 'FD60' || $fireRating == 'FD60s'){
            $fireRating = 'FD60';
        }

        $configurationDoor = configurationDoor($pageId);
        $fireRatingDoor = fireRatingDoor($fireRating);
        if (!empty($integrityValue)) {
            $integrity = $integrityValue;
        } elseif (!empty($integrityValue2)) {
            $integrity = $integrityValue2;
        }

        $leaf1VpAreaSizeM2Value = $request->leaf1VpAreaSizeM2Value;
        $userType = Auth::user()->UserType;
        if(empty($request->isIntegrity)) {
            echo json_encode(['status' => 'error','message' => 'Something went wrong.', 'data' => '']);
            die();
        }

        if($request->isIntegrity === true && $request->integrity == ""){
            echo json_encode(['status' => 'error','message' => "Integrity is required.", 'data' => '']);
            die();
        }

        if($userType=="1" || $userType=="4"){
            if ($fireRating=="NFR") {
                $glassType = GlassType::where('glass_type.'.$configurationDoor, $pageId)
                // ->where('glass_type.'.$fireRatingDoor, $fireRating)
                ->wherein('EditBy',$userIds)
                ->get();
            } elseif ($request->isIntegrity === true) {
                if($leaf1VpAreaSizeM2Value != ''){
                    $glassType = GlassType::where('glass_type.'.$configurationDoor, $pageId)
                    ->where('glass_type.'.$fireRatingDoor, $fireRating)
                    ->where('GlassIntegrity',$integrity)
                    ->where('VpAreaSize','>=',$leaf1VpAreaSizeM2Value)
                    ->wherein('EditBy',$userIds)
                    ->distinct('id')
                    ->get();
                } else {
                    $glassType = GlassType::where('glass_type.'.$configurationDoor, $pageId)
                        ->where('glass_type.'.$fireRatingDoor, $fireRating)
                        ->where('GlassIntegrity',$integrity)
                        ->wherein('EditBy',$userIds)
                        ->distinct('id')
                        ->get();
                }
            } elseif ($leaf1VpAreaSizeM2Value != '') {
                if($integrity != ""){
                    $glassType = GlassType::where('glass_type.'.$configurationDoor, $pageId)
                    ->where('glass_type.'.$fireRatingDoor, $fireRating)
                    ->where('GlassIntegrity',$integrity)
                    ->where('VpAreaSize','>=',$leaf1VpAreaSizeM2Value)
                    ->wherein('EditBy',$userIds)
                    ->distinct('id')
                    ->get();
                }else{
                    $glassType = GlassType::where('glass_type.'.$configurationDoor, $pageId)
                    ->where('glass_type.'.$fireRatingDoor, $fireRating)
                    ->where('VpAreaSize','>=',$leaf1VpAreaSizeM2Value)
                    ->wherein('EditBy',$userIds)
                    ->distinct('id')
                    ->get();
                }
            } else {
                $glassType = GlassType::where('glass_type.'.$configurationDoor, $pageId)
                    ->where('glass_type.'.$fireRatingDoor, $fireRating)
                    ->wherein('EditBy',$userIds)
                    ->distinct('id')
                    ->get();
            }

        } else {
            $UserId = CompanyUsers();
            if ($fireRating=="NFR") {
                if((string)$request->isIntegrity === "true"){
                    $glassType = GlassType::Join('selected_glass_type', function($join): void {
                        $join->on('glass_type.id', '=', 'selected_glass_type.glass_id');
                    })
                        ->where('glass_type.'.$configurationDoor, $pageId)
                        // ->where('glass_type.'.$fireRatingDoor, $fireRating)
                        ->where('glass_type.GlassIntegrity',$integrity)
                        ->wherein('selected_glass_type.editBy',$UserId)
                        ->wherein('glass_type.EditBy',$userIds)
                        ->distinct('glass_type.id')
                        ->groupBy('glass_type.Key')
                        ->get(['glass_type.*']);

                }else{
                    $glassType = GlassType::Join('selected_glass_type', function($join): void {
                        $join->on('glass_type.id', '=', 'selected_glass_type.glass_id');
                    })
                        ->where('glass_type.'.$configurationDoor, $pageId)
                        // ->where('glass_type.'.$fireRatingDoor, $fireRating)
                        ->wherein('selected_glass_type.editBy',$UserId)
                        ->wherein('glass_type.EditBy',$userIds)
                        ->distinct('glass_type.id')
                        ->groupBy('glass_type.Key')
                        ->get(['glass_type.*']);
                }
            } elseif ((string)$request->isIntegrity === "true") {
                if($leaf1VpAreaSizeM2Value != ''){
                    $glassType = GlassType::Join('selected_glass_type', function($join): void {
                        $join->on('glass_type.id', '=', 'selected_glass_type.glass_id');
                    })
                        ->where('glass_type.'.$configurationDoor, $pageId)
                        ->where('glass_type.'.$fireRatingDoor, $fireRating)
                        ->where('glass_type.GlassIntegrity',$integrity)
                        ->where('glass_type.VpAreaSize','>=',$leaf1VpAreaSizeM2Value)
                        ->wherein('selected_glass_type.editBy',$UserId)
                        ->wherein('glass_type.EditBy',$userIds)
                        ->distinct('glass_type.id')
                        ->groupBy('glass_type.Key')
                        ->get(['glass_type.*']);
                } else {
                    $glassType = GlassType::Join('selected_glass_type', function($join): void {
                        $join->on('glass_type.id', '=', 'selected_glass_type.glass_id');
                    })
                        ->where('glass_type.'.$configurationDoor, $pageId)
                        ->where('glass_type.'.$fireRatingDoor, $fireRating)
                        ->where('glass_type.GlassIntegrity',$integrity)
                        ->wherein('selected_glass_type.editBy',$UserId)
                        ->wherein('glass_type.EditBy',$userIds)
                        ->distinct('glass_type.id')
                        ->groupBy('glass_type.Key')
                        ->get(['glass_type.*']);
                }
            } elseif ($leaf1VpAreaSizeM2Value != '') {
                if($integrity != ""){
                    $glassType = GlassType::Join('selected_glass_type', function($join): void {
                        $join->on('glass_type.id', '=', 'selected_glass_type.glass_id');
                    })
                        ->where('glass_type.'.$configurationDoor, $pageId)
                        ->where('glass_type.'.$fireRatingDoor, $fireRating)
                        ->where('glass_type.GlassIntegrity',$integrity)
                        ->where('glass_type.VpAreaSize','>=',$leaf1VpAreaSizeM2Value)
                        ->wherein('selected_glass_type.editBy',$UserId)
                        ->wherein('glass_type.EditBy',$userIds)
                        ->distinct('glass_type.id')
                        ->groupBy('glass_type.Key')
                        ->get(['glass_type.*']);
                }else{
                    $glassType = GlassType::Join('selected_glass_type', function($join): void {
                        $join->on('glass_type.id', '=', 'selected_glass_type.glass_id');
                    })
                        ->where('glass_type.'.$configurationDoor, $pageId)
                        ->where('glass_type.'.$fireRatingDoor, $fireRating)
                        ->where('glass_type.VpAreaSize','>=',$leaf1VpAreaSizeM2Value)
                        ->wherein('selected_glass_type.editBy',$UserId)
                        ->distinct('glass_type.id')
                        ->groupBy('glass_type.Key')
                        ->wherein('glass_type.EditBy',$userIds)
                        ->get(['glass_type.*']);
                }
            } else {
                $glassType = GlassType::Join('selected_glass_type', function($join): void {
                    $join->on('glass_type.id', '=', 'selected_glass_type.glass_id');
                })
                    ->where('glass_type.'.$configurationDoor, $pageId)
                    ->where('glass_type.'.$fireRatingDoor, $fireRating)
                    ->wherein('selected_glass_type.editBy',$UserId)
                    ->wherein('glass_type.EditBy',$userIds)
                    ->distinct('glass_type.id')
                    ->groupBy('glass_type.Key')
                    ->get(['glass_type.*']);
            }
        }

        if(!empty($glassType) && count( $glassType)){
            echo json_encode(['status'=>'ok','data'=> $glassType]);
        }else{
            echo json_encode(['status'=>'error','data'=> '']);
        }
    }



    public function glassTypeFilter(Request $request): void{
        $userIds = CompanyUsers();
        $pageId = $request->pageId;
        $glassType = $request->glassType;
        $userType = Auth::user()->UserType;
        $fireRating = $request->fireRating;
        if($request->fireRating == 'FD30' || $request->fireRating == 'FD30s'){
            $request->fireRating = 'FD30';
        }elseif($request->fireRating == 'FD60' || $request->fireRating == 'FD60s'){
            $request->fireRating = 'FD60';
        }

        $configurationDoor = configurationDoor($pageId);
        $fireRatingDoor = fireRatingDoor($request->fireRating);
        if ($userType=="1" ||$userType=="4") {
            $glassThikness = GlassType::where($configurationDoor,$pageId)->where('Key',$glassType)->where($fireRatingDoor,$request->fireRating)->where('glass_type.EditBy',1)->get();
        } elseif ($request->fireRating == 'NFR') {
            $glassThikness = GlassType::where('glass_type.'.$configurationDoor,$pageId)
            ->where('glass_type.Key',$glassType)
            ->wherein('glass_type.EditBy',$userIds)
            // ->where('glass_type.'.$fireRatingDoor,$request->fireRating)
            ->groupBy('glass_type.Key')
            ->get(['glass_type.*']);
        } else{


            $glassThikness = GlassType::where('glass_type.'.$configurationDoor,$pageId)
            ->where('glass_type.Key',$glassType)
            ->wherein('glass_type.EditBy',$userIds)
            ->where('glass_type.'.$fireRatingDoor,$request->fireRating)
            ->get(['glass_type.*']);
        }

        if(!empty($glassThikness) && count( $glassThikness)){
            echo json_encode(['status'=>'ok','data'=> $glassThikness]);
        } else {
            echo json_encode(['status'=>'error','data'=> '']);
        }
    }



    // public function fileterGlazingSystem(Request $request){
    //     $pageId = $request->pageId;
    //     $fireRating = $request->fireRating;
    //     if($fireRating == 'FD30' || $fireRating == 'FD30s'){
    //         $fireRating = 'FD30';
    //     }elseif($fireRating == 'FD60' || $fireRating == 'FD60s'){
    //         $fireRating = 'FD60';
    //     }

    //     $configurationDoor = configurationDoor($pageId);
    //     $fireRatingDoor = fireRatingDoor($fireRating);
    //     $leaf1VpAreaSizeM2Value = $request->leaf1VpAreaSizeM2Value;
    //     $lippingSpecies=array();

    //     $authdata = Auth::user();

    //     if($authdata->UserType=="1" ||$authdata->UserType=="4"){

    //         if(empty($leaf1VpAreaSizeM2Value)){
    //             if($fireRating == 'NFR'){
    //                 $glaszingSystem = GetOptions(['glazing_system.'.$configurationDoor => $pageId ,'glazing_system.Status' => 1], "","glazing_systems");
    //             } else {
    //                 $glaszingSystem = GetOptions(['glazing_system.'.$configurationDoor => $pageId ,'glazing_system.Status' => 1,'glazing_system.'.$fireRatingDoor => $fireRating], "","glazing_systems");
    //             }
    //         }else{
    //             if($fireRating == 'NFR'){
    //                 $glaszingSystem = GetOptions(['glazing_system.'.$configurationDoor => $pageId ,'glazing_system.Status' => 1, ["VPAreaSize", ">=", $leaf1VpAreaSizeM2Value]], "","glazing_systems");
    //             } else {
    //                 $glaszingSystem = GetOptions(['glazing_system.'.$configurationDoor => $pageId ,'glazing_system.Status' => 1,'glazing_system.'.$fireRatingDoor => $fireRating, ["VPAreaSize", ">=", $leaf1VpAreaSizeM2Value]], "","glazing_systems");
    //             }
    //         }

    //         if($fireRating=="FD30"){
    //             $lippingSpecies = GetOptions(['lipping_species.Status' => 1, ["lipping_species.MinValue", "<=", "530"], ["lipping_species.MaxValues", ">", "530"]], "join", "lippingSpecies", "query");
    //         }else if($fireRating=="FD60"){
    //             $lippingSpecies = GetOptions(['lipping_species.Status' => 1, ["lipping_species.MinValue", "<=", "640"], ["lipping_species.MaxValues", ">", "640"]], "join", "lippingSpecies", "query");
    //         }else{
    //             $lippingSpecies = GetOptions(['lipping_species.Status' => 1], "join", "lippingSpecies", "query");
    //         }
    //         $lippingSpecies = $lippingSpecies->get();
    //     }else{
    //         $UserId = CompanyUsers();
    //         if(empty($leaf1VpAreaSizeM2Value)){
    //             if($fireRating == 'NFR'){
    //                 $glaszingSystem = GetOptions(['glazing_system.'.$configurationDoor => $pageId ,'glazing_system.Status' => 1], "join","glazing_systems");
    //             } else {
    //                 $glaszingSystem = GetOptions(['glazing_system.'.$configurationDoor => $pageId ,'glazing_system.Status' => 1,'glazing_system.'.$fireRatingDoor => $fireRating], "join","glazing_systems");
    //             }
    //         }else{
    //             if($fireRating == 'NFR'){
    //                 $glaszingSystem = GetOptions(['glazing_system.'.$configurationDoor => $pageId ,'glazing_system.Status' => 1], "join","glazing_systems");
    //             } else {
    //                 $glaszingSystem = GetOptions(['glazing_system.'.$configurationDoor => $pageId ,'glazing_system.Status' => 1,'glazing_system.'.$fireRatingDoor => $fireRating, ["glazing_system.VPAreaSize", ">=", $leaf1VpAreaSizeM2Value]], "join","glazing_systems");
    //             }
    //         }

    //         $SelectedLippingSpecies = SelectedLippingSpeciesItems::wherein('selected_lipping_species_items.selected_user_id', $UserId)->groupBy("selected_lipping_species_id")->get();
    //         $SelectedLippingSpeciesIds = array_column($SelectedLippingSpecies->toArray(), "selected_lipping_species_id");

    //         if($fireRating=="FD30" || $fireRating=="FD30s"){
    //             $lippingSpecies = GetOptions(['lipping_species.Status' => 1, ["lipping_species.MinValue", "<=", "530"], ["lipping_species.MaxValues", ">", "530"]], "join", "lippingSpecies", "query");
    //         }else if($fireRating=="FD60" || $fireRating=="FD60s"){
    //             $lippingSpecies = GetOptions(['lipping_species.Status' => 1, ["lipping_species.MinValue", "<=", "640"], ["lipping_species.MaxValues", ">", "640"]], "join", "lippingSpecies", "query");

    //         }else{
    //             $lippingSpecies = GetOptions(['lipping_species.Status' => 1], "join", "lippingSpecies", "query");
    //         }
    //         $lippingSpecies = $lippingSpecies->whereIn("lipping_species.id",  $SelectedLippingSpeciesIds)->get();
    //     }

    //     if(!empty($glaszingSystem) && count( $glaszingSystem) >0){
    //         echo json_encode(array('status'=>'ok','data'=> $glaszingSystem,'lippingSpecies'=>$lippingSpecies));
    //     }else{
    //         echo json_encode(array('status'=>'error','data'=> '','lippingSpecies'=>$lippingSpecies));
    //     }
    // }


    public function fileterGlazingSystem(Request $request): void{
        $pageId = $request->pageId;
        $fireRating = $request->fireRating;
        if($fireRating == 'FD30' || $fireRating == 'FD30s'){
            $fireRating = 'FD30';
        }elseif($fireRating == 'FD60' || $fireRating == 'FD60s'){
            $fireRating = 'FD60';
        }

        $configurationDoor = configurationDoor($pageId);
        $fireRatingDoor = fireRatingDoor($fireRating);
        $leaf1VpAreaSizeM2Value = $request->leaf1VpAreaSizeM2Value;
        $authdata = Auth::user();

        if($authdata->UserType=="1" ||$authdata->UserType=="4"){

            if (empty($leaf1VpAreaSizeM2Value)) {
                if($fireRating == 'NFR'){
                    $glaszingSystem = GetOptions(['glazing_system.'.$configurationDoor => $pageId ,'glazing_system.Status' => 1], "","glazing_systems");
                } else {
                    $glaszingSystem = GetOptions(['glazing_system.'.$configurationDoor => $pageId ,'glazing_system.Status' => 1,'glazing_system.'.$fireRatingDoor => $fireRating], "","glazing_systems");
                }
            } elseif ($fireRating == 'NFR') {
                $glaszingSystem = GetOptions(['glazing_system.'.$configurationDoor => $pageId ,'glazing_system.Status' => 1, ["VPAreaSize", ">=", $leaf1VpAreaSizeM2Value]], "","glazing_systems");
            } else {
                $glaszingSystem = GetOptions(['glazing_system.'.$configurationDoor => $pageId ,'glazing_system.Status' => 1,'glazing_system.'.$fireRatingDoor => $fireRating, ["VPAreaSize", ">=", $leaf1VpAreaSizeM2Value]], "","glazing_systems");
            }

        }else{
            $UserId = CompanyUsers();
            if (empty($leaf1VpAreaSizeM2Value)) {
                if($fireRating == 'NFR'){
                    $glaszingSystem = GetOptions(['glazing_system.'.$configurationDoor => $pageId ,'glazing_system.Status' => 1], "join","glazing_systems");
                } else {
                    $glaszingSystem = GetOptions(['glazing_system.'.$configurationDoor => $pageId ,'glazing_system.Status' => 1,'glazing_system.'.$fireRatingDoor => $fireRating], "join","glazing_systems");
                }
            } elseif ($fireRating == 'NFR') {
                $glaszingSystem = GetOptions(['glazing_system.'.$configurationDoor => $pageId ,'glazing_system.Status' => 1], "join","glazing_systems");
            } else {
                $glaszingSystem = GetOptions(['glazing_system.'.$configurationDoor => $pageId ,'glazing_system.Status' => 1,'glazing_system.'.$fireRatingDoor => $fireRating, ["glazing_system.VPAreaSize", ">=", $leaf1VpAreaSizeM2Value]], "join","glazing_systems");
            }
        }

        //getting Timber Species
        $lippingSpecies = filterTimberSpecies("Other",$pageId,$fireRating);

        if(!empty($lippingSpecies) && count( $lippingSpecies) >0){
            echo json_encode(['status'=>'ok','data'=> $glaszingSystem,'lippingSpecies'=>$lippingSpecies]);
        }else{
            echo json_encode(['status'=>'error','data'=> '','lippingSpecies'=>$lippingSpecies]);
        }
    }

    public function LipingGlazingSystem(Request $request): void{
        $pageId = $request->pageId;
        $fireRating = $request->fireRating;
        if($fireRating == 'FD30' || $fireRating == 'FD30s'){
            $fireRating = 'FD30';
        }elseif($fireRating == 'FD60' || $fireRating == 'FD60s'){
            $fireRating = 'FD60';
        }

        $configurationDoor = configurationDoor($pageId);
        $OnlylippingSpecies = filterOnlyLippingSpecies("Lipping",$pageId,$fireRating);

        if(!empty($OnlylippingSpecies) && count( $OnlylippingSpecies)){
            echo json_encode(['status'=>'ok','OnlylippingSpecies'=>$OnlylippingSpecies]);
        }else{
            echo json_encode(['status'=>'error','data'=> '','OnlylippingSpecies'=>$OnlylippingSpecies]);
        }
    }


    public function fileterArchitraveSystem(Request $request): void{

        $lippingSpecies = filterTimberSpecies("Architrave");

        if(!empty($lippingSpecies) && count( $lippingSpecies) >0){
            echo json_encode(['status'=>'ok','lippingSpecies'=>$lippingSpecies]);
        }else{
            echo json_encode(['status'=>'error','data'=> '','lippingSpecies'=>$lippingSpecies]);
        }
    }


    public function fileterGlazingThikness(Request $request): void{
        $pageId = $request->pageId;
        $userIds = CompanyUsers();
        $glazingSystem = $request->glazingSystems;
        $configurationDoor = configurationDoor($pageId);
        $glaszingSystemThickness = GlazingSystem::where($configurationDoor,$pageId)->where('Key',$glazingSystem)->wherein('editBy',$userIds)->where('Status',1)->first();
        // $GlazingBeadFixingDetail  = Option::where('configurableitems',$pageId)->where(['GlazingSystem' => $glazingSystem , 'OptionSlug' => 'Fixing_Detail' ])->first();
        if(!empty($glaszingSystemThickness)){
            echo json_encode(['status'=>'ok','data'=> $glaszingSystemThickness]);
        } else {
            echo json_encode(['status'=>'error','data'=> '','data2'=>'']);
        }
    }



    public function fileterGlazingBeads(Request $request): void{
        $pageId = $request->pageId;
        $fireRating = $request->fireRating;
        if($request->fireRating=='NFR'){
            $fireRating = 'FD30';
        }

        if($fireRating == 'FD30' || $fireRating == 'FD30s'){
            $fireRating = 'FD30';
        }elseif($fireRating == 'FD60' || $fireRating == 'FD60s'){
            $fireRating = 'FD60';
        }

        $glaszingBeads = Option::where('configurableitems',$pageId)->where('UnderAttribute',$fireRating)->where('OptionSlug','leaf1_glazing_beads')->get();
        if(!empty($glaszingBeads) && count( $glaszingBeads)){
            echo json_encode(['status'=>'ok','data'=> $glaszingBeads]);
        }else{
            echo json_encode(['status'=>'error','data'=> '']);
        }
    }


    public function filterFrameMaterial(Request $request): void{

        $pageId = $request->pageId;
        $foursided = $request->framesided;
        $fireRating = fireRatingDoor($request->fireRating);

        $lippingSpecies = filterTimberSpecies("Frame",$pageId,$fireRating,$foursided);

        echo json_encode(['status'=>'ok','data'=> '','leepingSpecies'=>$lippingSpecies]);
    }

    public function scalloppedLippingThickness(Request $request): void{
        $pageId = $request->pageId;
        $fireRating = $request->fireRating;
        if($fireRating == 'FD30' || $fireRating == 'FD30s'){
            $fireRating = 'FD30';
        }elseif($fireRating == 'FD60' || $fireRating == 'FD60s'){
            $fireRating = 'FD60';
        }

        $scalloppedLippingThickness = Option::where('configurableitems',$pageId)->where('UnderAttribute',$fireRating)->where('OptionSlug','scalloped_lipping_thickness')->get();
        if(!empty($scalloppedLippingThickness) && count( $scalloppedLippingThickness)){
            echo json_encode(['status'=>'ok','data'=> $scalloppedLippingThickness]);
        } else {
            echo json_encode(['status'=>'error','data'=> '']);
        }
    }

    public function flatLippingThickness(Request $request): void{
        $pageId = $request->pageId;
        $fireRating = $request->fireRating;
        if($fireRating == 'FD30' || $fireRating == 'FD30s'){
            $fireRating = 'FD30';
        }elseif($fireRating == 'FD60' || $fireRating == 'FD60s'){
            $fireRating = 'FD60';
        }

        $flatLippingThickness = Option::where('configurableitems',$pageId)->where('UnderAttribute',$fireRating)->where('OptionSlug','flat_lipping_thickness')->get();
        if(!empty($flatLippingThickness) && count( $flatLippingThickness)){
            echo json_encode(['status'=>'ok','data'=> $flatLippingThickness]);
        }else{
            echo json_encode(['status'=>'error','data'=> '']);
        }
    }

    public function rebatedLippingThickness(Request $request): void{
        $pageId = $request->pageId;
        $fireRating = $request->fireRating;
        if($fireRating == 'FD30' || $fireRating == 'FD30s'){
            $fireRating = 'FD30';
        }elseif($fireRating == 'FD60' || $fireRating == 'FD60s'){
            $fireRating = 'FD60';
        }

        $rebatedLippingThickness = Option::where('configurableitems',$pageId)->where('UnderAttribute',$fireRating)->where('OptionSlug','rebeated_lipping_thickness')->get();
        if(!empty($rebatedLippingThickness) && count( $rebatedLippingThickness)){
            echo json_encode(['status'=>'ok','data'=> $rebatedLippingThickness]);
        }else{
            echo json_encode(['status'=>'error','data'=> '']);
        }
    }

    public function glassTypeNFR(Request $request): void{
        $pageId = $request->pageId;
        $configurationDoor = configurationDoor($pageId);
        $glassTypeNFR = GlassType::where($configurationDoor,$pageId)->groupBy('Key')->get();
        if(!empty($glassTypeNFR) && count( $glassTypeNFR)){
            echo json_encode(['status'=>'ok','data'=> $glassTypeNFR]);
        }else{
            echo json_encode(['status'=>'error','data'=> '']);
        }
    }

    public function filterDoorThickness(Request $request): void{
        $pageId = $request->pageId;
        $fireRating = $request->fireRating;
        if($fireRating == 'FD30' || $fireRating == 'FD30s'){
            $fireRating = 'FD30';
        }elseif($fireRating == 'FD60' || $fireRating == 'FD60s'){
            $fireRating = 'FD60';
        }

        $doorThickness = Option::where(['configurableitems' => $pageId , 'OptionSlug'=>'Glass_Integrity'])
                                ->orWhere(['configurableitems' => $pageId ,'OptionSlug'=>'Door_Thickness'])->get();
        if(!empty($doorThickness) && count( $doorThickness)){
            echo json_encode(['status'=>'ok','data'=> $doorThickness]);
        }else{
            echo json_encode(['status'=>'error','data'=> '']);
        }

    }

    public function filterDoorleafFacingValue(Request $request): void{
        $userIds = CompanyUsers();
        $pageId = $request->pageId;
        $doorLeafFacing = $request->doorLeafFacing;
        $configurationDoor = configurationDoor($pageId);
        $userType = Auth::user()->UserType;
        if($userType=="1" || $userType=="4"){

            $doorfacingValue = DoorLeafFacing::where([$configurationDoor => $pageId,'door_leaf_facing.Status' => 1])
                                ->wherein('editBy',$userIds)->get();

        }else{

            $doorfacingValue = GetOptions(['door_leaf_facing.'.$configurationDoor => $pageId ,'door_leaf_facing.Status' => 1], "join","door_leaf_facing");
            // $doorfacingValue = GetOptions(['options.configurableitems'=> $pageId ,'options.is_deleted' => 0,'options.OptionSlug' => 'door_leaf_facing_value'], "join");

        }

        if(!empty($doorfacingValue) && count( $doorfacingValue)){
            if($doorLeafFacing=="Laminate"){
                // $color = Color::where('DoorLeafFacing',$doorLeafFacing)->get();
                $color = Color::Join('selected_color', function($join): void {
                    $join->on('color.id', '=', 'selected_color.SelectedColorId');
                    })
                    ->where('color.DoorLeafFacing',$doorLeafFacing)
                    ->where('color.DoorLeafFacingValue',$request->doorLeafFacingValue)
                    ->wherein('selected_color.SelectedUserId',$userIds)
                    ->get(['color.*']);
                echo json_encode(['status'=>'ok','data'=> $doorfacingValue,'color'=>$color]);
            }elseif($doorLeafFacing=="PVC"){
                $color = Color::Join('selected_color', function($join): void {
                    $join->on('color.id', '=', 'selected_color.SelectedColorId');
                    })
                    ->where('color.DoorLeafFacing',$doorLeafFacing)
                    ->wherein('selected_color.SelectedUserId',$userIds)
                    ->get(['color.*']);
                echo json_encode(['status'=>'ok','data'=> $doorfacingValue,'color'=>$color]);
            }else{
                $color = GetOptions(['options.configurableitems'=> $pageId ,'options.is_deleted' => 0,'options.OptionSlug' => 'door_leaf_finish','options.UnderAttribute' => $doorLeafFacing], "join");
                echo json_encode(['status'=>'ok','data'=> $doorfacingValue,'color'=>$color]);
            }

        }else{
            $color = GetOptions(['options.configurableitems'=> $pageId ,'options.is_deleted' => 0,'options.OptionSlug' => 'door_leaf_finish','options.UnderAttribute' => $doorLeafFacing], "join");
            echo json_encode(['status'=>'ok','data'=> $doorfacingValue,'color'=>$color]);

        }

        // dd(44);
    }



    public function filterRalColor(Request $request): void{
        $doorLeafFinish = $request->doorLeafFinish;
        $userType = Auth::user()->UserType;
        if($userType=="1" ||$userType=="4"){
            $colors = Color::where(['DoorLeafFacing'=>$doorLeafFinish])->get();
        }else{
            $UserId = CompanyUsers();
            if($doorLeafFinish == 'Painted' || $doorLeafFinish == 'Paint_Finish'){
                $colors = Color::Join('selected_color', function($join): void {
                    $join->on('color.id', '=', 'selected_color.SelectedColorId');
                  })
                  ->where('color.DoorLeafFacingValue','Painted')
                  ->wherein('selected_color.SelectedUserId',$UserId)
                  ->get(['color.*']);
            }else {
                $colors = Color::Join('selected_color', function($join): void {
                    $join->on('color.id', '=', 'selected_color.SelectedColorId');
                  })
                  ->where('color.DoorLeafFacing',$doorLeafFinish)
                  ->wherein('selected_color.SelectedUserId',$UserId)
                  ->get(['color.*']);
            }

            if($request->pageType == 4 && $request->leafConstruction == 'Primed' && $userType == 2 && ($request->doorLeafFacing == 'Factory Industrial Primed' || $request->doorLeafFacing == 'Paint Sanded' || $request->doorLeafFacing == 'Primed 2 Go')){

                $UserId = CompanyUsers();
                $colors = Color::Join('selected_color', function($join): void {
                    $join->on('color.id', '=', 'selected_color.SelectedColorId');
                  })
                  ->where('selected_color.DoorLeafFacingName', $request->doorLeafFacing)
                  ->wherein('selected_color.SelectedUserId',$UserId)
                  ->get(['color.*']);
            }
        }

        if(!empty($colors) && count( $colors)){
            echo json_encode(['status'=>'ok','data'=> $colors]);
        } else {
            echo json_encode(['status'=>'error','data'=> '']);
        }
    }

    public function faceGrooveImage(Request $request): void{
        if($request->decorativeGroves == 'Yes' && ($request->pageId == 4 || $request->pageId == 5 || $request->pageId == 6)){
            $face_grooves = DB::table('face_grooves')->get();
           // dd($face_grooves);
            if(!empty($face_grooves) && count( $face_grooves)){
                echo json_encode(['status'=>'ok','face_grooves'=> $face_grooves]);
            } else {
                echo json_encode(['status'=>'error','data'=> '']);
            }
        }else {
            echo json_encode(['status'=>'error','data'=> 'Something Went Wrong...']);
        }
    }



    public function itemStore(Request $request){

        $door = new Doors();
        // $ds = new DoorSchedule();
        $door->QuotationId =0;
        $door->ComanyId =  0;
        $door->Mark ='';
        $door->Type = '';
        $door->FieldsValue =json_encode($request->all());
        $door->Status = 1;
        $door->save();
        // DB::table('door_schedule')->where('id',$request->itemID )->where('QuotationId',$request->QuotationId )->update(array('Status' => 1));
        // return redirect('quotation/request/'.$request->QuotationId);
    //    return redirect('quotation/request/'.$quotation->id);
    return redirect()->back()->with('msg', 'item added succesfully');

    }

    public function itemStore2(Request $request){
        // if(Auth::user()->UserType != 4){
        //     BomCalculation($request);
        // }
        $id = $request->itemID;
        if(!empty($id)){
            $doorset =  Item::where(['itemId'=>$id])->get()->first();
            if (empty($doorset)) {
                $errorlist = 'Door Not Found';
                \Session::flash('errors', __('validate'.$id));
                return response()->json(['status'=>'error','errors'=>$errorlist]);
            } elseif (!empty($request->SvgImage)) {
                $updateDetails['SvgImage'] = $request->SvgImage;
                $updateDetails['LeafWidth1'] = $request->leafWidth1;
                $updateDetails['LeafHeight'] = $request->calculationOfLeafHeight;
                $item = Item::where('itemId',$id)->update($updateDetails);
                $successmsg = 'Updated configure door '.$id.' successfully.';
                $url = 'quotation/excel-upload/'.$request->QuotationId.'/'.$request->versionId;
                \Session::flash('successes', __('validate'.$id));
                return response()->json(['status'=>'success','data'=>$successmsg,'url'=>$url]);
            } else{
                $successmsg = 'Something went wrong.';
                $url = 'quotation/excel-upload/'.$request->QuotationId.'/'.$request->versionId;
                \Session::flash('errors', __('validate'.$id));
                return response()->json(['status'=>'success','data'=>$successmsg,'url'=>$url]);
            }
        }else{
            $successmsg = 'Something went wrong.';
            $url = 'quotation/excel-upload/'.$request->QuotationId.'/'.$request->versionId;
            \Session::flash('errors', __('validate'.$id));
            return response()->json(['status'=>'success','data'=>$successmsg,'url'=>$url]);
        }


    }

    public function ScreenStore(Request $request){

        if(Auth::user()->UserType != 4){
            sideScreenBOM($request);
        }

        $QuotationId = $request->QuotationId;
        $doorType = $request->doorType;
        $DoorNo = $request->DoorNo;
        $VersionId = $request->VersionId;

        $SvgImage = "";

        $id = $request->id;

        $validated = $request->validate([
            'QuotationId' => 'required|numeric',
            'VersionId' => 'required|numeric',
            'ScreenType' => 'nullable|string',
            'Tolerance' => 'nullable|numeric',
            'FireRating' => 'nullable|string',
            'GlazingType' => 'nullable|string',
            'SinglePane' => 'nullable|string',
            'IGUInnerPane' => 'nullable|string',
            'IGUOuterPane' => 'nullable|string',
            'CAVITY' => 'nullable|string',
            'GlassPane1Width' => 'nullable|numeric',
            'GlassPane1Height' => 'nullable|numeric',
            'GlassPane2Width' => 'nullable|numeric',
            'GlassPane2Height' => 'nullable|numeric',
            'GlassPane3Width' => 'nullable|numeric',
            'GlassPane3Height' => 'nullable|numeric',
            'GlassPane4Width' => 'nullable|numeric',
            'GlassPane4Height' => 'nullable|numeric',
            'GlassPane5Width' => 'nullable|numeric',
            'GlassPane5Height' => 'nullable|numeric',
            'GlassPane6Width' => 'nullable|numeric',
            'GlassPane6Height' => 'nullable|numeric',
            'GlassPane7Width' => 'nullable|numeric',
            'GlassPane7Height' => 'nullable|numeric',
            'GlassPane8Width' => 'nullable|numeric',
            'GlassPane8Height' => 'nullable|numeric',
            'GlassPane9Width' => 'nullable|numeric',
            'GlassPane9Height' => 'nullable|numeric',
            'GlassPane10Width' => 'nullable|numeric',
            'GlassPane10Height' => 'nullable|numeric',
            'GlassPane11Width' => 'nullable|numeric',
            'GlassPane11Height' => 'nullable|numeric',
            'GlassPane12Width' => 'nullable|numeric',
            'GlassPane12Height' => 'nullable|numeric',
            'GlassPane13Width' => 'nullable|numeric',
            'GlassPane13Height' => 'nullable|numeric',
            'GlassPane14Width' => 'nullable|numeric',
            'GlassPane14Height' => 'nullable|numeric',
            'GlassPane15Width' => 'nullable|numeric',
            'GlassPane15Height' => 'nullable|numeric',
            'GlassPane16Width' => 'nullable|numeric',
            'GlassPane16Height' => 'nullable|numeric',
            'Acoustic' => 'nullable|string',
            'SpecialFeatuers' => 'nullable|string',
            'Finish' => 'nullable|string',
            'SOWidth' => 'nullable|numeric',
            'SOHeight' => 'nullable|numeric',
            'SODepth' => 'nullable|numeric',
            'GlazingBeadShape' => 'nullable|string',
            'GlazingBeadHeight' => 'nullable|numeric',
            'GlazingBeadWidth' => 'nullable|numeric',
            'GlazingBeadMaterial' => 'nullable|string',
            'GlazingSystem' => 'nullable|string',
            'GlazingSystemThickness' => 'nullable|numeric',
            'GlazingSystemFixingDetail' => 'nullable|string',
            'GlassLiner' => 'nullable|string',
            'FrameThickness' => 'nullable|numeric',
            'FrameDepth' => 'nullable|numeric',
            'FrameWidth' => 'nullable|numeric',
            'FrameHeight' => 'nullable|numeric',
            'FrameMaterial' => 'nullable|string',
            'SubFrameBottom' => 'nullable|string',
            'SubFrameTop' => 'nullable|string',
            'SubFrameLeft' => 'nullable|string',
            'SubFrameRight' => 'nullable|string',
            'SubFrameBottomThickness' => 'nullable|numeric',
            'SubFrameBottomWidth' => 'nullable|numeric',
            'SubFrameTopThickness' => 'nullable|numeric',
            'SubFrameLeftThickness' => 'nullable|numeric',
            'SubFrameRightThickness' => 'nullable|numeric',
            'SubFrameMaterial' => 'nullable|string',
            'TransomQuantity' => 'nullable|integer',
            'TransomType' => 'nullable|string',
            'TransomThickness' => 'nullable|numeric',
            'TransomMaterial' => 'nullable|string',
            'TransomDepth' => 'nullable|string',
            'TransomHeight1' => 'nullable|numeric',
            'TransomWidth1' => 'nullable|numeric',
            'MullionQuantity' => 'nullable|integer',
            'MullionType' => 'nullable|string',
            'MullionThickness' => 'nullable|numeric',
            'MullionMaterial' => 'nullable|string',
            'MullionHeight1' => 'nullable|numeric',
            'Transom1Thickness' => 'nullable|numeric',
            'TransomHeightPoint1' => 'nullable|numeric',
            'Transom2Thickness' => 'nullable|numeric',
            'TransomHeightPoint2' => 'nullable|numeric',
            'Transom3Thickness' => 'nullable|numeric',
            'TransomHeightPoint3' => 'nullable|numeric',
            'TransomHeightPoint4' => 'nullable|numeric',
            'Mullion1Thickness' => 'nullable|numeric',
            'MullionWidthPoint1' => 'nullable|numeric',
            'Mullion2Thickness' => 'nullable|numeric',
            'MullionWidthPoint2' => 'nullable|numeric',
            'Mullion3Thickness' => 'nullable|numeric',
            'MullionWidthPoint3' => 'nullable|numeric',
            'MullionWidthPoint4' => 'nullable|numeric',
        ]);

        if(!empty($id)){
             // update the SideScreenItem entry
            $sideScreenItem = SideScreenItem::findOrFail($id);
        }else{
            // Create the SideScreenItem entry
            $sideScreenItem = new SideScreenItem();
        }

        $sideScreenItem->fill($validated);
        $sideScreenItem->UserId = Auth::user()->id;
        $sideScreenItem->SvgImage = $request->SvgImage;
        $sideScreenItem->save();

        if(!empty($id)){

            $version_id = QuotationVersion::where('quotation_id', $QuotationId)->where('id', $VersionId)->value('version');
            if(!empty($version_id)){
                ScreenBOMCalculation::where('QuotationId',$QuotationId)->where('VersionId','0')->where('ScreenId',$id)->delete();
            }

            $ScreenBOMCalculation = ScreenBOMCalculation::select('*')->where('QuotationId',$request->QuotationId)->where('ScreenType',$request->ScreenType)->where('ScreenId',$id)->get();
            $GTSellPrice = 0;
            $GTSellPriceTotal = 0;
            if(!empty($ScreenBOMCalculation)){
                foreach($ScreenBOMCalculation as $value){
                    $GTSellPrice += $value->GTSellPrice;

                }

                $ItemMaster = SideScreenItemMaster::where('ScreenId',$id)->get()->count();
                $GTSellPriceTotal = round(($GTSellPrice/$ItemMaster),2);
            }

            $Item = SideScreenItem::where('id', $id)->update([
                'ScreenPrice' => $GTSellPriceTotal
            ]);

            $successmsg = 'Side Screen Updated Successfully.';
            $url = 'quotation/generate/'.$request->QuotationId.'/'.$VersionId;
            \Session::flash('success', __($successmsg));
            return response()->json(['status'=>'success1','data'=>$successmsg,'url'=>$url]);
        }else{
            $screenBOMCalculation = ScreenBOMCalculation::select('*')->where('QuotationId',$request->QuotationId)->where('ScreenType',$request->ScreenType)->get();
            $Item = SideScreenItem::where(['QuotationId' => $QuotationId , 'ScreenType' => $request->ScreenType])->get()->first();

            if(!empty($screenBOMCalculation)){
                foreach($screenBOMCalculation as $value){
                    $BOM = ScreenBOMCalculation::find($value->id);
                    if(!empty($BOM)){
                        $BOM->ScreenId = $Item->id;
                        $BOM->save();
                    }
                }
            }

            $ScreenBOMCalculation = ScreenBOMCalculation::select('*')->where('QuotationId',$request->QuotationId)->where('ScreenType',$request->ScreenType)->where('ScreenId',$Item->id)->get();
            $GTSellPrice = 0;
            if(!empty($ScreenBOMCalculation)){
                foreach($ScreenBOMCalculation as $value1){
                    $GTSellPrice += $value1->GTSellPrice;
                }
            }

            $Item = SideScreenItem::where('id', $Item->id)->update([
                'ScreenPrice' => $GTSellPrice
            ]);

            $successmsg = "Side Screen Created Successfully, now please add Screen's.";
            $url = 'quotation/add-new-screens/'.$request->QuotationId.'/'.$VersionId;
            \Session::flash('success', __($successmsg));
            return response()->json(['status'=>'success','data'=>$successmsg,'url'=>$url]);
        }
    }

    public function itemStore1(Request $request){

        if(Auth::user()->UserType != 4){
            $pageType = $request->pageIdentity;

            match ($pageType) {
                // VICAIMA DOOR
                '4' => BomCalculationVicaima($request),
                // Seadec DOOR
                '5' => BomCalculationSeadec($request),
                // Deanta DOOR
                '6' => BomCalculationDeanta($request),
                // Halspan DOOR
                '2' => HalspanBomCalculation($request),
                // Flamebreak DOOR
                '7' => FlamebreakBomCalculation($request),
                // Stredor DOOR
                '8' => StredorBomCalculation($request),
                // STAREBOARD AND ALL
                default => BomCalculation($request),
            };
        }


        $pageIdentity = $request->pageIdentity;
        $QuotationId = $request->QuotationId;
        $doorType = $request->doorType;
        $DoorNo = $request->DoorNo;
        $versionId = $request->version_id;

        $SvgImage = "";

        $id = $request->itemID;

        $qq = Quotation::find($QuotationId);
        $qq->editBy = Auth::user()->id;
        $qq->updated_at = date('Y-m-d H:i:s');
        $qq->save();

        $Quotation = Quotation::where("id",$QuotationId)->first();

        $IronmongaryPrice = 0;
        if(!empty($request->IronmongeryID)){
            $AI = AddIronmongery::select('discountprice')->where('id',$request->IronmongeryID)->first();
            // old code
            // $IronmongaryPrice = $AI->discountprice;
            //end old code

            //new code for add margin in ironmongary prices
            $userIds = CompanyUsers();
            $margin = BOMSetting::wherein('UserId',$userIds)->value('margin_for_material');
            $marginDiscount = discountQuotationValue($request->QuotationId,$request->version_id);
            if($marginDiscount != 0){
                $margin += $marginDiscount;
            }

            $marginwithcal = 100 - $margin;
            $testvar = $marginwithcal/100;
            $totalcost = $AI->discountprice / $testvar;
            $IronmongaryPrice = round(($totalcost),2);
            //end
        }

        if(!empty($id)){

            $mm =  Item::where(['QuotationId' => $QuotationId , 'DoorType' => $doorType, 'VersionId' => $versionId])->count();
            if($mm > 0){
                $doorset =  Item::where(['itemId'=>$id])->get()->first();
                if($doorset->DoorType != $doorType){
                    $errorlist = 'Door Type '.$doorType.' is already exist for these quotation.';
                    return response()->json(['status'=>'error','errors'=>$errorlist]);
                }
            }

            $version_id = QuotationVersion::where('quotation_id', $QuotationId)->where('id', $versionId)->value('version');
            if(!empty($version_id)){
                BOMCalculation::where('QuotationId',$QuotationId)->where('VersionId','0')->where('itemId',$id)->delete();
            }

            $BOMCalculation = BOMCalculation::select('*')->where('QuotationId',$request->QuotationId)->where('DoorType',$request->doorType)->where('itemId',$id)->get();
            $GTSellPrice = 0;
            $GTSellPriceTotal = 0;
            if(!empty($BOMCalculation)){
                foreach($BOMCalculation as $value){
                    if($value->Category != 'Ironmongery&MachiningCosts'){
                        $GTSellPrice += $value->GTSellPrice;
                    }
                }

                $ItemMaster = ItemMaster::where('itemID',$request->itemID)->get()->count();
                $GTSellPriceTotal = round(($GTSellPrice/$ItemMaster),2);
            }

            $Item = Item::where('itemId', $id)->update([
                'DoorsetPrice' => $GTSellPriceTotal
             ]);


            $updateDetails = [
                //item (update)
                    //Main Options
                        'IntumescentLeafType'                   => $request->intumescentLeafType,
                        'LeafConstruction'                      => $request->leafConstruction,
                        'DoorType'                              => $doorType,
                        'FireRating'                            => $request->fireRating,
                        'DoorsetType'                           => $request->doorsetType,
                        'SwingType'                             => $request->swingType,
                        'LatchType'                             => $request->latchType,
                        'Handing'                               => $request->Handing,
                        'OpensInwards'                          => $request->OpensInwards,
                        'COC'                                   => $request->COC,
                        'Tollerance'                            => $request->tollerance,
                        'Dropseal'                              => $request->Dropseal,
                        'Undercut'                              => $request->undercut,
                        'FloorFinish'                           => $request->floorFinish,
                        'GAP'                                   => $request->gap,
                        'FrameThickness'                        => $request->frameThickness,

                    //Door Dimensions & Door Leaf
                        'SOWidth'                               => $request->sOWidth,
                        'SOHeight'                              => $request->sOHeight,
                        'SOWallThick'                           => $request->sODepth,
                        'DoorDimensions'                         => $request->DoorDimensions,
                        'DoorDimensions2'                         => $request->DoorDimensions2,
                        'LeafWidth1'                            => $request->leafWidth1,
                        'AdjustmentLeafWidth1'                  => $request->adjustmentLeafWidth1,
                        'LeafWidth2'                            => $request->leafWidth2,
                        'AdjustmentLeafWidth2'                  => $request->adjustmentLeafWidth2,
                        'LeafHeight'                            => $request->leafHeightNoOP,
                        'AdjustmentLeafHeightNoOP'              => $request->adjustmentLeafHeightNoOP,
                        'LeafThickness'                         => $request->doorThickness,
                        'DoorLeafFacing'                        => $request->doorLeafFacing,
                        'DoorLeafFacingValue'                   => $request->doorLeafFacingValue,
                        'DoorLeafFinish'                        => $request->doorLeafFinish,
                        'DoorLeafFinishColor'                   => $request->doorLeafFinishColor,
                        'SheenLevel'                            => $request->SheenLevel,
                        'DecorativeGroves'                      => $request->decorativeGroves,
                        'GrooveLocation'                        => $request->grooveLocation,
                        'GrooveWidth'                           => $request->grooveWidth,
                        'GrooveDepth'                           => $request->grooveDepth,
                        'MaxNumberOfGroove'                     => $request->maxNumberOfGroove,
                        'NumberOfGroove'                        => $request->numberOfGroove,
                        'NumberOfVerticalGroove'                => $request->numberOfVerticalGroove,
                        'NumberOfHorizontalGroove'              => $request->numberOfHorizontalGroove,
                        'DecorativeGrovesLeaf2' => $request->DecorativeGrovesLeaf2,
                        'GrooveLocationLeaf2' => $request->GrooveLocationLeaf2,
                        'IsSameAsDecorativeGroves1' => $request->IsSameAsDecorativeGroves1,
                        'GrooveWidthLeaf2' => $request->GrooveWidthLeaf2,
                        'GrooveDepthLeaf2' => $request->GrooveDepthLeaf2,
                        'MaxNumberOfGrooveLeaf2' => $request->MaxNumberOfGrooveLeaf2,
                        'NumberOfGrooveLeaf2' => $request->NumberOfGrooveLeaf2,
                        'NumberOfVerticalGrooveLeaf2' => $request->NumberOfVerticalGrooveLeaf2,
                        'NumberOfHorizontalGrooveLeaf2' => $request->NumberOfHorizontalGrooveLeaf2,
                        'DoorDimensionsCode'              => $request->DoorDimensionsCode,
                        'DoorDimensionsCode2'              => $request->DoorDimensionsCode2,
                        'groovesNumber'              => $request->doorDimensionGroove,
                        'GroovesNumberLeaf2'              => $request->DoorDimensionGrooveLeaf2,

                    // HINGE LOCATION (25-12-2023)
                        'hinge1Location'                        => $request->hinge1Location,
                        'hinge2Location'                        => $request->hinge2Location,
                        'hinge3Location'                        => $request->hinge3Location,
                        'hinge4Location'                        => $request->hinge4Location,
                        'hingeCenterCheck'                      => $request->hingeCenterCheck ?? 0,
                        'fourthHinges'                          => $request->fourthHinges ?? 0,

                    //Vision Panel
                        'Leaf1VisionPanel'                      => $request->leaf1VisionPanel,
                        'Leaf1VisionPanelShape'                 => $request->leaf1VisionPanelShape,
                        'VisionPanelQuantity'                   => $request->visionPanelQuantity,
                        'AreVPsEqualSizesForLeaf1'              => $request->AreVPsEqualSizes,
                        'DistanceFromtopOfDoor'                 => $request->distanceFromTopOfDoor,
                        'DistanceFromTheEdgeOfDoor'             => $request->distanceFromTheEdgeOfDoor,
                        'DistanceBetweenVPs'                    => $request->distanceBetweenVPs,
                        'Leaf1VPWidth'                          => $request->vP1Width,
                        'Leaf1VPHeight1'                        => $request->vP1Height1,
                        'Leaf1VPHeight2'                        => $request->vP1Height2,
                        'Leaf1VPHeight3'                        => $request->vP1Height3,
                        'Leaf1VPHeight4'                        => $request->vP1Height4,
                        'Leaf1VPHeight5'                        => $request->vP1Height5,
                        'Leaf1VPAreaSizem2'                     => $request->leaf1VpAreaSizeM2,
                        'Leaf2VisionPanel'                      => $request->leaf2VisionPanel,
                        'sVPSameAsLeaf1'                        => $request->vpSameAsLeaf1,
                        'Leaf2VisionPanelQuantity'              => ($request->vpSameAsLeaf1 == "Yes")?$request->visionPanelQuantity:$request->visionPanelQuantityforLeaf2,
                        'AreVPsEqualSizesForLeaf2'              => ($request->vpSameAsLeaf1 == "Yes")?$request->AreVPsEqualSizes:$request->AreVPsEqualSizesForLeaf2,
                        'DistanceFromTopOfDoorForLeaf2'         => $request->distanceFromTopOfDoorforLeaf2,
                        'DistanceFromTheEdgeOfDoorforLeaf2'     => $request->distanceFromTheEdgeOfDoorforLeaf2,
                        'DistanceBetweenVp'                     => $request->distanceBetweenVPsforLeaf2,
                        'Leaf2VPWidth'                          => $request->vP2Width,
                        'Leaf2VPHeight1'                        => $request->vP2Height1,
                        'Leaf2VPHeight2'                        => $request->vP2Height2,
                        'Leaf2VPHeight3'                        => $request->vP2Height3,
                        'Leaf2VPHeight4'                        => $request->vP2Height4,
                        'Leaf2VPHeight5'                        => $request->vP2Height5,
                        'GlassIntegrity'                        => $request->lazingIntegrityOrInsulationIntegrity,
                        'GlassType'                             => $request->glassType,
                        'GlassThickness'                        => $request->glassThickness,
                        'GlazingSystems'                        => $request->glazingSystems,
                        'GlazingSystemThickness'                => $request->glazingSystemsThickness,
                        'GlazingBeads'                          => $request->glazingBeads,
                        'GlazingBeadsThickness'                 => $request->glazingBeadsThickness,
                        'glazingBeadsWidth'                     => $request->glazingBeadsWidth,
                        'glazingBeadsHeight'                    => $request->glazingBeadsHeight,
                        'glazingBeadsFixingDetail'              => $request->glazingBeadsFixingDetail,
                        'GlazingBeadSpecies'                    => $request->glazingBeadSpecies,

                    //Frame
                        'FrameMaterial'                         => $request->frameMaterial,
                        'FrameType'                             => $request->frameType,
                        'FourSidedFrame'                             => $request->FourSidedFrame,
                            // streboard
                            'PlantonStopWidth'                  => $request->plantonStopWidth,
                            'PlantonStopHeight'                 => $request->plantonStopHeight,
                            // streboard
                            'RebatedWidth'                  => $request->rebatedWidth,
                            'RebatedHeight'                 => $request->rebatedHeight,
                            //halspan
                            'standardWidth'                     => $request->standardWidth,
                            'standardHeight'                    => $request->standardHeight,
                        //vicaima
                        'ScallopedWidth'                  => $request->ScallopedWidth,
                        'ScallopedHeight'                 => $request->ScallopedHeight,
                        'FrameWidth'                            => $request->frameWidth,
                        'FrameHeight'                           => $request->frameHeight,
                        'FrameDepth'                            => $request->frameDepth,
                        'FrameFinish'                           => $request->frameFinish,
                        'FrameFinishColor'                      => $request->framefinishColor,
                        'ExtLiner'                              => $request->extLiner,
                        'DoorFrameConstruction'                 => $request->frameCostuction,
                        'ExtLinerValue'                         => $request->extLinerValue,
                        'ExtLinerThickness'                     => $request->extLinerThickness,
                        'ExtLinerFInish'                        => $request->extLinerFinish,
                        'IntumescentSeal'                       => $request->intumescentSeal,
                        'IntumescentSealColor'                  => $request->intumescentSealColor,
                        'IntumescentSealSize'                   => $request->intumescentSealSize,
                        'IronmongerySet'                        => $request->ironmongerySet,
                        'IronmongeryID'                         => $request->IronmongeryID,
                        'SpecialFeatureRefs'                    => $request->specialFeatureRefs,

                    //Over Panel Section
                        'Overpanel'                             => $request->overpanel,
                        'OPLippingThickness'                    => $request->OPLippingThickness,
                        'OPWidth'                               => $request->oPWidth,
                        'OPHeigth'                              => $request->oPHeigth,
                        'OPTransom'                             => $request->opTransom,
                        'TransomThickness'                      => $request->transomThickness,
                        'TransomDepth'                      => $request->opTransomDepth,
                        'opGlassIntegrity'                      => $request->opGlassIntegrity,
                        'OPGlassType'                           => $request->opGlassType,
                        'OPGlassThickness'                      => $request->opglassThickness,
                        'OPGlazingSystems'                      => $request->opglazingSystems,
                        'OPGlazingSystemsThickness'             => $request->opglazingSystemsThickness,
                        'OPGlazingBeads'                        => $request->opGlazingBeads,
                        'OPGlazingBeadsThickness'               => $request->opglazingBeadsThickness,
                        'OPGlazingBeadsHeight'                  => $request->opglazingBeadsHeight,
                        'OPGlazingBeadsFixingDetail'            => $request->opglazingBeadsFixingDetail,
                        'OPGlazingBeadSpecies'                  => $request->opGlazingBeadSpecies,
                        'OpBeadThickness'                       => $request->OpBeadThickness,
                        'OpBeadHeight'                          => $request->OpBeadHeight,

                    //Side Light
                        'SideLight1'                            => $request->sideLight1,
                        'SL1GlassIntegrity'                   => $request->SL1GlassIntegrity,
                        'SideLight1GlassType'                   => $request->sideLight1GlassType,
                        'SideLight1GlassThickness'              => $request->sideLight1GlassThickness,
                        'SideLight1GlazingSystems'              => $request->sideLight1GlazingSystems,
                        'SideLight1GlazingSystemsThickness'     => $request->sideLight1GlazingSystemsThickness,
                        'BeadingType'                           => $request->SideLight1BeadingType,
                        'SideLight1GlazingBeadsThickness'       => $request->sideLight1GlazingBeadsThickness,
                        'SideLight1GlazingBeadsWidth'           => $request->sideLight1GlazingBeadsWidth,
                        'SideLight1GlazingBeadsFixingDetail'    => $request->sideLight1GlazingBeadsFixingDetail,
                        'SL1GlazingBeadSpecies'                 => $request->SideLight1GlazingBeadSpecies,
                        'SL1Width'                              => $request->SL1Width,
                        'SL1Height'                             => $request->SL1Height,
                        'SL1Depth'                              => $request->SL1Depth,
                        'SL1Transom'                            => $request->SL1Transom,
                        'SL1transomThickness'                   => $request->SL1transomThickness,
                        'SL1TransomDepth'                       => $request->SL1TransomDepth,
                        'SideLight2'                            => $request->sideLight2,
                        'DoYouWantToCopySameAsSL1'              => $request->copyOfSideLite1,
                        'SL2GlassIntegrity'                   => ($request->copyOfSideLite1 == "Yes")?$request->SL1GlassIntegrity:$request->SL2GlassIntegrity,
                        'SideLight2GlassType'                   => ($request->copyOfSideLite1 == "Yes")?$request->sideLight1GlassType:$request->sideLight2GlassType,
                        'SideLight2GlassThickness'              => ($request->copyOfSideLite1 == "Yes")?$request->sideLight1GlassThickness:$request->sideLight2GlassThickness,
                        'SideLight2GlazingSystems'              => ($request->copyOfSideLite1 == "Yes")?$request->sideLight1GlazingSystems:$request->sideLight2GlazingSystems,
                        'SideLight2GlazingSystemsThickness'     => ($request->copyOfSideLite1 == "Yes")?$request->sideLight1GlazingSystemsThickness:$request->sideLight2GlazingSystemsThickness,
                        'SideLight2BeadingType'                 => ($request->copyOfSideLite1 == "Yes")?$request->SideLight1BeadingType:$request->SideLight2BeadingType,
                        'SideLight2GlazingBeadsThickness'       => ($request->copyOfSideLite1 == "Yes")?$request->sideLight1GlazingBeadsThickness:$request->sideLight2GlazingBeadsThickness,
                        'SideLight2GlazingBeadsWidth'           => ($request->copyOfSideLite1 == "Yes")?$request->sideLight1GlazingBeadsWidth:$request->sideLight2GlazingBeadsWidth,
                        'SideLight2GlazingBeadsFixingDetail'    => ($request->copyOfSideLite1 == "Yes")?$request->sideLight1GlazingBeadsFixingDetail:$request->sideLight2GlazingBeadsFixingDetail,
                        'SideLight2GlazingBeadSpecies'          => ($request->copyOfSideLite1 == "Yes")?$request->SideLight1GlazingBeadSpecies:$request->SideLight2GlazingBeadSpecies,
                        'SL2Width'                              => $request->SL2Width,
                        'SL2Height'                             => $request->SL2Height,
                        'SL2Depth'                              => $request->SL2Depth,
                        'SL2Transom'                            => ($request->copyOfSideLite1 == "Yes")?$request->SL1Transom:$request->SL2Transom,
                        'SL2transomThickness'                   => $request->SL2transomThickness,
                        'SL2TransomDepth'                       => $request->SL2TransomDepth,
                        'SLtransomHeightFromTop'                => $request->SLtransomHeightFromTop,
                        'SLtransomThickness'                    => $request->SLtransomThickness,
                        'SlBeadThickness'                  => $request->SlBeadThickness,
                        'SlBeadHeight'                  => $request->SlBeadHeight,

                    //Lipping And Intumescent
                        'LippingType'                           => $request->lippingType,
                        'LippingThickness'                      => $request->lippingThickness,
                        'LippingSpecies'                        => $request->lippingSpecies,
                        'MeetingStyle'                          => $request->meetingStyle,
                        'ScallopedLippingThickness'             => $request->scallopedLippingThickness,
                        'FlatLippingThickness'                  => $request->flatLippingThickness,
                        'RebatedLippingThickness'               => $request->rebatedLippingThickness,
                        'CoreWidth1'                            => $request->coreWidth1,
                        'CoreWidth2'                            => $request->coreWidth2,
                        'CoreHeight'                            => $request->coreHeight,
                        'IntumescentLeapingSealType'            => $request->intumescentSealType,
                        'IntumescentLeapingSealLocation'        => $request->intumescentSealLocation,
                        'IntumescentLeapingSealColor'           => $request->intumescentSealColor,
                        'IntumescentLeapingSealArrangement'     => $request->intumescentSealArrangement,
                        'intumescentSealMeetingEdges'     => $request->intumescentSealMeetingEdges,

                    //Accoustics
                        'Accoustics'                            => $request->accoustics,
                        'rWdBRating'                            => $request->rWdBRating,
                        'perimeterSeal1'                        => $request->perimeterSeal1,
                        'perimeterSeal2'                        => $request->perimeterSeal2,
                        // 'thresholdSeal1'                        => $request->thresholdSeal1,
                        // 'thresholdSeal2'                        => $request->thresholdSeal2,
                        'AccousticsMeetingStiles'               => $request->accousticsmeetingStiles,

                    //Architrave
                        'Architrave'                            => $request->Architrave,
                        'ArchitraveMaterial'                    => $request->architraveMaterial,
                        'ArchitraveType'                        => $request->architraveType,
                        'ArchitraveWidth'                       => $request->architraveWidth,
                        'ArchitraveHeight'                      => $request->architraveHeight,
                        'ArchitraveDepth'                       => $request->architraveDepth,
                        'ArchitraveFinish'                      => $request->architraveFinish,
                        'ArchitraveFinishColor'                 => $request->architraveFinishcolor,
                        'ArchitraveSetQty'                      => $request->architraveSetQty,


                    //Transport
                        // 'VehicleType'                           => $request->vehicleType,
                        // 'DeliveryTime'                          => $request->deliveryTime,
                        // 'Packaging'                             => $request->packaging

                        'IronmongaryPrice'                      => $IronmongaryPrice,
                        'FrameOnOff'                      => $request->FrameOnOff,
            ];

            // if(!empty($SvgImage)){
                // $updateDetails['SvgImage'] = $SvgImage;
                $updateDetails['SvgImage'] = $request->SvgImage;
                $updateDetails['VersionId'] = $versionId;
            // }
            $item = Item::where('itemId',$id)->update($updateDetails);
            // return redirect('quotation/generate/'.$request->QuotationId.'/'.$versionId)->with('success','Updated configure door successfully.');

            $successmsg = 'Updated configure door successfully.';
            $url = 'history.back()';
            // $url = 'quotation/generate/'.$request->QuotationId.'/'.$versionId;
            \Session::flash('success', __($successmsg));
            return response()->json(['status'=>'success1','data'=>$successmsg,'url'=>$url]);

        } else {
            // checking configurableitems is empty or not
            // if empty then only these code work
            // it update `quotation` table. These tells about Strebord or Halspan door for perticular quotation
            if(empty($Quotation->configurableitems)){
                Quotation::where('id',$QuotationId)->update(["configurableitems" => $pageIdentity]);
            }

            // insert
            $item = new Item();

            // check these `Quotation ID` with `Door Number` is not duplicate entry
            // it show message if it is more than 0 (zero) - Quotation ID with Door Number is already exist.
            $mm =  Item::where(['items.QuotationId' => $QuotationId, 'items.DoorType' => $doorType,'items.VersionId' => $versionId])->count();
            if($mm > 0){
                // return redirect()->back()->withInput()->with('error', 'Door Type '.$doorType.' is already exist for these quotation.');
                $errorlist = 'Door Type '.$doorType.' is already exist for these quotation.';
                return response()->json(['status'=>'error','errors'=>$errorlist]);
            } else {
                // item (insert)
                    $item->QuotationId = $request->QuotationId;
                    $item->VersionId = $versionId;
                    $item->UserId = Auth::user()->id;
                    // $item->DoorQuantity = 1;
                    //Main Options
                        $item->LeafConstruction = $request->leafConstruction;
                        $item->IntumescentLeafType = $request->intumescentLeafType;
                        $item->DoorType = $request->doorType;
                        $item->FireRating = $request->fireRating;
                        $item->DoorsetType = $request->doorsetType;
                        $item->SwingType = $request->swingType;
                        $item->LatchType = $request->latchType;
                        $item->Handing = $request->Handing;
                        $item->OpensInwards = $request->OpensInwards;
                        $item->COC = $request->COC;
                        $item->Tollerance = $request->tollerance;
                        $item->Dropseal = $request->Dropseal;
                        $item->Undercut = $request->undercut;
                        $item->FloorFinish = $request->floorFinish;
                        $item->GAP = $request->gap;
                        $item->FrameThickness = $request->frameThickness;

                    //Door Dimensions & Door Leaf
                        $item->SOWidth = $request->sOWidth;
                        $item->SOHeight = $request->sOHeight;
                        $item->SOWallThick = $request->sODepth;
                        $item->LeafWidth1 = $request->leafWidth1;
                        $item->AdjustmentLeafWidth1 = $request->adjustmentLeafWidth1;
                        $item->LeafWidth2 = $request->leafWidth2;
                        $item->AdjustmentLeafWidth2 = $request->adjustmentLeafWidth2;
                        $item->LeafHeight = $request->leafHeightNoOP;
                        $item->AdjustmentLeafHeightNoOP = $request->adjustmentLeafHeightNoOP;
                        $item->DoorDimensions = $request->DoorDimensions;
                        $item->DoorDimensions2 = $request->DoorDimensions2;
                        $item->LeafThickness = $request->doorThickness;
                        $item->DoorLeafFacing = $request->doorLeafFacing;
                        $item->DoorLeafFacingValue = $request->doorLeafFacingValue;
                        $item->DoorLeafFinish = $request->doorLeafFinish;
                        $item->DoorLeafFinishColor = $request->doorLeafFinishColor;
                        $item->SheenLevel = $request->SheenLevel;
                        $item->DecorativeGroves = $request->decorativeGroves;
                        $item->GrooveLocation = $request->grooveLocation;
                        $item->GrooveWidth = $request->grooveWidth;
                        $item->GrooveDepth = $request->grooveDepth;
                        $item->MaxNumberOfGroove = $request->maxNumberOfGroove;
                        $item->NumberOfGroove = $request->numberOfGroove;
                        $item->NumberOfVerticalGroove = $request->numberOfVerticalGroove;
                        $item->NumberOfHorizontalGroove = $request->numberOfHorizontalGroove;
                        $item->DecorativeGrovesLeaf2 = $request->DecorativeGrovesLeaf2;
                        $item->GrooveLocationLeaf2 = $request->GrooveLocationLeaf2;
                        $item->IsSameAsDecorativeGroves1 = $request->IsSameAsDecorativeGroves1;
                        $item->GrooveWidthLeaf2 = $request->GrooveWidthLeaf2;
                        $item->GrooveDepthLeaf2 = $request->GrooveDepthLeaf2;
                        $item->MaxNumberOfGrooveLeaf2 = $request->MaxNumberOfGrooveLeaf2;
                        $item->NumberOfGrooveLeaf2 = $request->NumberOfGrooveLeaf2;
                        $item->NumberOfVerticalGrooveLeaf2 = $request->NumberOfVerticalGrooveLeaf2;
                        $item->NumberOfHorizontalGrooveLeaf2 = $request->NumberOfHorizontalGrooveLeaf2;
                        $item->DoorDimensionsCode = $request->DoorDimensionsCode;
                        $item->DoorDimensionsCode2 = $request->DoorDimensionsCode2;
                        $item->groovesNumber = $request->doorDimensionGroove;
                        $item->GroovesNumberLeaf2 = $request->DoorDimensionGrooveLeaf2;

                    // HINGE LOCATION (25-12-2023)
                        $item->hinge1Location = $request->hinge1Location;
                        $item->hinge2Location = $request->hinge2Location;
                        $item->hinge3Location = $request->hinge3Location;
                        $item->hinge4Location = $request->hinge4Location;
                        $item->hingeCenterCheck = $request->hingeCenterCheck??0;
                        $item->fourthHinges = $request->fourthHinges??0;

                    //Vision Panel
                        $item->Leaf1VisionPanel = $request->leaf1VisionPanel;
                        $item->Leaf1VisionPanelShape = $request->leaf1VisionPanelShape;
                        $item->VisionPanelQuantity = $request->visionPanelQuantity;
                        $item->AreVPsEqualSizesForLeaf1 = $request->AreVPsEqualSizes;
                        $item->DistanceFromtopOfDoor = $request->distanceFromTopOfDoor;
                        $item->DistanceFromTheEdgeOfDoor = $request->distanceFromTheEdgeOfDoor;
                        $item->DistanceBetweenVPs = $request->distanceBetweenVPs;
                        $item->Leaf1VPWidth = $request->vP1Width;
                        $item->Leaf1VPHeight1 = $request->vP1Height1;
                        $item->Leaf1VPHeight2 = $request->vP1Height2;
                        $item->Leaf1VPHeight3 = $request->vP1Height3;
                        $item->Leaf1VPHeight4 = $request->vP1Height4;
                        $item->Leaf1VPHeight5 = $request->vP1Height5;
                        $item->Leaf1VPAreaSizem2 = $request->leaf1VpAreaSizeM2;
                        $item->Leaf2VisionPanel = $request->leaf2VisionPanel;
                        $item->sVPSameAsLeaf1 = $request->vpSameAsLeaf1;
                        $item->Leaf2VisionPanelQuantity = ($request->vpSameAsLeaf1 == "Yes")?$request->visionPanelQuantity:$request->visionPanelQuantityforLeaf2;
                        $item->AreVPsEqualSizesForLeaf2 = ($request->vpSameAsLeaf1 == "Yes")?$request->AreVPsEqualSizes:$request->AreVPsEqualSizesForLeaf2;
                        $item->DistanceFromTopOfDoorForLeaf2 = $request->distanceFromTopOfDoorforLeaf2;
                        $item->DistanceFromTheEdgeOfDoorforLeaf2 = $request->distanceFromTheEdgeOfDoorforLeaf2;
                        $item->DistanceBetweenVp = $request->distanceBetweenVPsforLeaf2;
                        $item->Leaf2VPWidth = $request->vP2Width;
                        $item->Leaf2VPHeight1 = $request->vP2Height1;
                        $item->Leaf2VPHeight2 = $request->vP2Height2;
                        $item->Leaf2VPHeight3 = $request->vP2Height3;
                        $item->Leaf2VPHeight4 = $request->vP2Height4;
                        $item->Leaf2VPHeight5 = $request->vP2Height5;
                        $item->GlassIntegrity = $request->lazingIntegrityOrInsulationIntegrity;
                        $item->GlassType = $request->glassType;
                        $item->GlassThickness = $request->glassThickness;
                        $item->GlazingSystems = $request->glazingSystems;
                        $item->GlazingSystemThickness = $request->glazingSystemsThickness;
                        $item->GlazingBeads = $request->glazingBeads;
                        $item->GlazingBeadsThickness = $request->glazingBeadsThickness;
                        $item->glazingBeadsWidth = $request->glazingBeadsWidth;
                        $item->glazingBeadsHeight = $request->glazingBeadsHeight;
                        $item->glazingBeadsFixingDetail = $request->glazingBeadsFixingDetail;
                        $item->GlazingBeadSpecies = $request->glazingBeadSpecies;

                    //Frame
                        $item->FrameMaterial = $request->frameMaterial;
                        $item->FrameType = $request->frameType;
                        $item->FourSidedFrame = $request->FourSidedFrame;
                        // streboard
                        $item->PlantonStopWidth = $request->plantonStopWidth;
                        $item->PlantonStopHeight = $request->plantonStopHeight;
                        // streboard
                        $item->RebatedWidth = $request->rebatedWidth;
                        $item->RebatedHeight = $request->rebatedHeight;
                        //halspan
                        $item->standardWidth = $request->standardWidth;
                        $item->standardHeight = $request->standardHeight;
                        $item->ScallopedWidth = $request->ScallopedWidth;
                        $item->ScallopedHeight = $request->ScallopedHeight;
                        $item->FrameWidth = $request->frameWidth;
                        $item->FrameHeight = $request->frameHeight;
                        $item->FrameDepth = $request->frameDepth;
                        $item->FrameFinish = $request->frameFinish;
                        $item->FrameFinishColor = $request->framefinishColor;
                        $item->ExtLiner = $request->extLiner;
                        $item->DoorFrameConstruction = $request->frameCostuction;
                        $item->ExtLinerValue = $request->extLinerValue;
                        $item->extLinerSize = $request->extLinerSize;
                        $item->ExtLinerThickness = $request->extLinerThickness;
                        $item->ExtLinerFInish = $request->extLinerFinish;
                        $item->IntumescentSeal = $request->intumescentSeal;
                        $item->IntumescentSealColor = $request->intumescentSealColor;
                        $item->IntumescentSealSize = $request->intumescentSealSize;
                        $item->IronmongerySet = $request->ironmongerySet;
                        $item->IronmongeryID = $request->IronmongeryID;
                        $item->SpecialFeatureRefs = $request->specialFeatureRefs;

                    //Over Panel Section
                        $item->Overpanel = $request->overpanel;
                        $item->OPLippingThickness = $request->OPLippingThickness;
                        $item->OPWidth = $request->oPWidth;
                        $item->OPHeigth = $request->oPHeigth;
                        $item->OPTransom = $request->opTransom;
                        $item->TransomThickness = $request->transomThickness;
                        $item->TransomDepth = $request->opTransomDepth;
                        $item->opGlassIntegrity = $request->opGlassIntegrity;
                        $item->OPGlassType = $request->opGlassType;
                        $item->OPGlassThickness = $request->opglassThickness;
                        $item->OPGlazingSystems = $request->opglazingSystems;
                        $item->OPGlazingSystemsThickness = $request->opglazingSystemsThickness;
                        $item->OPGlazingBeads = $request->opGlazingBeads;
                        $item->OPGlazingBeadsThickness = $request->opglazingBeadsThickness;
                        $item->OPGlazingBeadsHeight = $request->opglazingBeadsHeight;
                        $item->OPGlazingBeadsFixingDetail = $request->opglazingBeadsFixingDetail;
                        $item->OPGlazingBeadSpecies = $request->opGlazingBeadSpecies;
                        $item->OpBeadThickness = $request->OpBeadThickness;
                        $item->OpBeadHeight = $request->OpBeadHeight;

                    //Side Light
                        $item->SideLight1 = $request->sideLight1;
                        $item->SL1GlassIntegrity = $request->SL1GlassIntegrity;
                        $item->SideLight1GlassType = $request->sideLight1GlassType;
                        $item->SideLight1GlassThickness = $request->sideLight1GlassThickness;
                        $item->SideLight1GlazingSystems = $request->sideLight1GlazingSystems;
                        $item->SideLight1GlazingSystemsThickness = $request->sideLight1GlazingSystemsThickness;
                        $item->BeadingType = $request->SideLight1BeadingType;
                        $item->SideLight1GlazingBeadsThickness = $request->sideLight1GlazingBeadsThickness;
                        $item->SideLight1GlazingBeadsWidth = $request->sideLight1GlazingBeadsWidth;
                        $item->SideLight1GlazingBeadsFixingDetail = $request->sideLight1GlazingBeadsFixingDetail;
                        $item->SL1GlazingBeadSpecies = $request->SideLight1GlazingBeadSpecies;
                        $item->SL1Width = $request->SL1Width;
                        $item->SL1Height = $request->SL1Height;
                        $item->SL1Depth = $request->SL1Depth;
                        $item->SL1Transom = $request->SL1Transom;
                        $item->SL1TransomDepth = $request->SL1TransomDepth;
                        $item->SL1transomThickness = $request->SL1transomThickness;
                        $item->SideLight2 = $request->sideLight2;
                        $item->DoYouWantToCopySameAsSL1 = $request->copyOfSideLite1;
                        $item->SL2GlassIntegrity = ($request->copyOfSideLite1 == "Yes")?$request->SL1GlassIntegrity:$request->SL2GlassIntegrity;
                        $item->SideLight2GlassType = ($request->copyOfSideLite1 == "Yes")?$request->sideLight1GlassType:$request->sideLight2GlassType;
                        $item->SideLight2GlassThickness = ($request->copyOfSideLite1 == "Yes")?$request->sideLight1GlassThickness:$request->sideLight2GlassThickness;
                        $item->SideLight2GlazingSystems = ($request->copyOfSideLite1 == "Yes")?$request->sideLight1GlazingSystems:$request->sideLight2GlazingSystems;
                        $item->SideLight2GlazingSystemsThickness = ($request->copyOfSideLite1 == "Yes")?$request->sideLight1GlazingSystemsThickness:$request->sideLight2GlazingSystemsThickness;
                        $item->SideLight2BeadingType = ($request->copyOfSideLite1 == "Yes")?$request->SideLight1BeadingType:$request->SideLight2BeadingType;
                        $item->SideLight2GlazingBeadsThickness = ($request->copyOfSideLite1 == "Yes")?$request->sideLight1GlazingBeadsThickness:$request->sideLight2GlazingBeadsThickness;
                        $item->SideLight2GlazingBeadsWidth = ($request->copyOfSideLite1 == "Yes")?$request->sideLight1GlazingBeadsWidth:$request->sideLight2GlazingBeadsWidth;
                        $item->SideLight2GlazingBeadsFixingDetail = ($request->copyOfSideLite1 == "Yes")?$request->sideLight1GlazingBeadsFixingDetail:$request->sideLight2GlazingBeadsFixingDetail;
                        $item->SideLight2GlazingBeadSpecies = ($request->copyOfSideLite1 == "Yes")?$request->SideLight1GlazingBeadSpecies:$request->SideLight2GlazingBeadSpecies;
                        $item->SL2transomThickness = ($request->copyOfSideLite1 == "Yes")?$request->SL1transomThickness:$request->SL2transomThickness;
                        $item->SL2TransomDepth = ($request->copyOfSideLite1 == "Yes")?$request->SL1TransomDepth:$request->SL2TransomDepth;
                        $item->SL2Width = $request->SL2Width;
                        $item->SL2Height = $request->SL2Height;
                        $item->SL2Depth = $request->SL2Depth;
                        $item->SL2Transom = ($request->copyOfSideLite1 == "Yes")?$request->SL1Transom:$request->SL2Transom;
                        $item->SLtransomHeightFromTop = $request->SLtransomHeightFromTop;
                        $item->SLtransomThickness = $request->SLtransomThickness;
                        $item->SlBeadThickness = $request->SlBeadThickness;
                        $item->SlBeadHeight = $request->SlBeadHeight;


                    //Lipping And Intumescent
                        $item->LippingType = $request->lippingType;
                        $item->LippingThickness = $request->lippingThickness;
                        $item->LippingSpecies = $request->lippingSpecies;
                        $item->MeetingStyle = $request->meetingStyle;
                        $item->ScallopedLippingThickness = $request->scallopedLippingThickness;
                        $item->FlatLippingThickness = $request->flatLippingThickness;
                        $item->RebatedLippingThickness = $request->rebatedLippingThickness;
                        $item->CoreWidth1 = $request->coreWidth1;
                        $item->CoreWidth2 = $request->coreWidth2;
                        $item->CoreHeight = $request->coreHeight;
                        $item->IntumescentLeapingSealType = $request->intumescentSealType;
                        $item->IntumescentLeapingSealLocation = $request->intumescentSealLocation;
                        $item->IntumescentLeapingSealColor = $request->intumescentSealColor;
                        $item->IntumescentLeapingSealArrangement = $request->intumescentSealArrangement;
                        $item->intumescentSealMeetingEdges = $request->intumescentSealMeetingEdges;

                    //Accoustics
                        $item->Accoustics = $request->accoustics;
                        $item->rWdBRating = $request->rWdBRating;
                        $item->perimeterSeal1 = $request->perimeterSeal1;
                        $item->perimeterSeal2 = $request->perimeterSeal2;
                        // $item->thresholdSeal1 = $request->thresholdSeal1;
                        // $item->thresholdSeal2 = $request->thresholdSeal2;
                        $item->AccousticsMeetingStiles = $request->accousticsmeetingStiles;

                    //Architrave
                        $item->Architrave = $request->Architrave;
                        $item->ArchitraveMaterial = $request->architraveMaterial;
                        $item->ArchitraveType = $request->architraveType;
                        $item->ArchitraveWidth = $request->architraveWidth;
                        $item->ArchitraveHeight = $request->architraveHeight;
                        $item->ArchitraveDepth = $request->architraveDepth;
                        $item->ArchitraveFinish = $request->architraveFinish;
                        $item->ArchitraveFinishColor = $request->architraveFinishcolor;
                        $item->ArchitraveSetQty = $request->architraveSetQty;

                        $item->SvgImage = $request->SvgImage;

                    //Transport
                        // $item->VehicleType = $request->vehicleType;
                        // $item->DeliveryTime = $request->deliveryTime;
                        // $item->Packaging = $request->packaging;
                        $item->IronmongaryPrice =  $IronmongaryPrice;
                        $item->FrameOnOff =  $request->FrameOnOff;

                    $item->save();

                    $BOMCalculation = BOMCalculation::select('*')->where('QuotationId',$request->QuotationId)->where('DoorType',$request->doorType)->get();
                    $Item = Item::where(['QuotationId' => $QuotationId , 'DoorType' => $doorType])->get()->first();

                    $Itemcount = Item::where(['QuotationId' => $QuotationId])->get()->count();

                    if(!empty($BOMCalculation)){
                        foreach($BOMCalculation as $value){
                            $BOM = BOMCalculation::find($value->id);
                            if(!empty($BOM)){
                                $BOM->itemId = $Item->itemId;
                                $BOM->save();
                            }
                        }
                    }

                    $BOMCalculation = BOMCalculation::select('*')->where('QuotationId',$request->QuotationId)->where('DoorType',$request->doorType)->where('itemId',$Item->itemId)->get();
                    $GTSellPrice = 0;
                    if(!empty($BOMCalculation)){
                        foreach($BOMCalculation as $value1){
                            if($value1->Category != 'Ironmongery&MachiningCosts'){
                                $GTSellPrice += $value1->GTSellPrice;
                            }
                        }
                    }

                    // $itemGTSellPrice = countTotalPrice($request->QuotationId, $versionId, $Item->itemId);

                    $Item = Item::where('itemId', $Item->itemId)->update([
                        'DoorsetPrice' => $GTSellPrice
                        // 'DoorsetPrice' => $itemGTSellPrice['GTSellPrice']
                    ]);

                // if(!empty($request->SvgImage)){
                //     $Quotation = Quotation::where("id",$QuotationId)->first();
                //     $Base64SvgImage = str_replace("data:image/png;base64,","",$request->SvgImage);
                //     $SvgImage = str_replace("#","",$Quotation->QuotationGenerationId)."-".$item->id."-".(($request->version_id != 0)?$request->version_id:"1").".png";
                //     $fileData = base64_decode($Base64SvgImage);
                //     $path = public_path('uploads/files/' . $SvgImage);
                //     $fp = fopen($path, "wb");
                //     fwrite($fp, $fileData);
                //     fclose($fp);
                //     Item::where('itemId',$item->id)->update(["SvgImage" => $SvgImage]);
                // }
                $successmsg = "Configure door successfully, now please add door's.";
                $url = 'quotation/add-new-doors/'.$request->QuotationId.'/'.$versionId;
                \Session::flash('success', __($successmsg));
                return response()->json(['status'=>'success','data'=>$successmsg,'url'=>$url]);
            }
        }
    }


    public function getHandingOptions(request $request): void{
        if(empty($request->doorsetType)){
            ms([
                'st' => "0",
                'txt' => '',
                'html' => "",
            ]);
        }

        if(empty($request->doorsetType)){
            ms([
                'st' => "0",
                'txt' => '',
                'html' => "",
            ]);
        }

        $pageId = $request->pageId;
        $doorsetType = $request->doorsetType;
        $swingType = $request->swingType;
        $optionResponse = Option::where('configurableitems', $pageId)->where('OptionSlug','Handing')->Where('UnderAttribute',$swingType)->where('UnderParent2',$doorsetType)->get()->toArray();
        if($optionResponse!='' && count($optionResponse)>0){
            echo json_encode(['status'=>'ok', 'data'=>$optionResponse]);
        }else{
            echo json_encode(['status'=>'error', 'data'=>'']);
        }
    }


    public function Filterintumescentseals(Request $request): void
    {

        $pageId = $request->pageId;
        $fireRatingValue = $request->fireRatingValue;
        if($fireRatingValue == 'FD30' || $fireRatingValue == 'FD30s'){
            $fireRatingValue = 'FD30';
        }elseif($fireRatingValue == 'FD60' || $fireRatingValue == 'FD60s'){
            $fireRatingValue = 'FD60';
        }

        $intumescentseals = $request->intumescentseals;
        $leafWidth1Value = (float)$request->leafWidth1Value;
        $leafHeightNoOPValue = (float)$request->leafHeightNoOPValue;
        $doorLeafFacingValueNew = $request->doorLeafFacingValueNew; // CS_acrovyn
        $frameMaterialNew = $request->frameMaterialNew; // MDF
        $intumescentsealsleaftype = $request->intumescentsealsleaftype; // MDF

        if(!empty($leafWidth1Value) && !empty($leafHeightNoOPValue)){


            $data = '';
            $configuration = '';
            $width = 0;
            $height = 0;

            $width = (int)$request->leafWidth1Value;
            $height = (int)$request->leafHeightNoOPValue;

            $configuration = $request->intumescentseals;

            $allValue =  $frameMaterialNew.'===|'.$doorLeafFacingValueNew.'===|'.$intumescentseals . '==' . $fireRatingValue .'=='.$leafHeightNoOPValue .'=='. $leafWidth1Value;



            $UserType = Auth::user()->UserType;
            if(in_array($UserType,[1,4])){

                $getConditions = [
                    ['intumescentseals2.configurableitems', $pageId],
                    ['intumescentseals2.configuration', $intumescentseals]
                ];
                $sqlGetConditions = [
                    ['intumescentseals2.configurableitems', $pageId],
                    ['intumescentseals2.configuration', $intumescentseals]
                ];

                if ($fireRatingValue == 'NFR') {
                    // $IntumescentSeals_A = SettingIntumescentSeals2::where(['configurableitems' => $pageId , 'configuration' => $intumescentseals])->get();
                    // $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId && `configuration` = '$intumescentseals'";
                } elseif ($doorLeafFacingValueNew == 'CS_acrovyn' && ($fireRatingValue == 'FD30' || $fireRatingValue == 'FD30s')) {
                    // $IntumescentSeals_A = SettingIntumescentSeals2::where(['configurableitems' => $pageId , 'configuration' => $intumescentseals,'firerating' => $fireRatingValue,'tag' => 'FD60AcrovynFaced'])->get();
                    // $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId  && `configuration` = '$intumescentseals' && `firerating` = '$fireRatingValue' && `tag` = 'FD30AcrovynFaced'  && $leafHeightNoOPValue <= `height_max` && $leafWidth1Value<= `width_max`";
                    $getConditions = array_merge($getConditions, [['intumescentseals2.firerating', $fireRatingValue],['intumescentseals2.tag', 'FD60AcrovynFaced']]);
                    $sqlGetConditions = array_merge($sqlGetConditions, [['intumescentseals2.firerating', $fireRatingValue],['intumescentseals2.tag', 'FD60AcrovynFaced'], ['intumescentseals2.height_max', '>=', $leafHeightNoOPValue], [ 'intumescentseals2.width_max', '>=', $leafWidth1Value]]);
                } elseif ($doorLeafFacingValueNew == 'CS_acrovyn' && ($fireRatingValue == 'FD60' || $fireRatingValue == 'FD60s')) {
                    // $IntumescentSeals_A = SettingIntumescentSeals2::where(['configurableitems' => $pageId , 'configuration' => $intumescentseals,'firerating' => $fireRatingValue,'tag' => 'FD60AcrovynFaced'])->get();
                    // $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId && `firerating` = '$fireRatingValue' && `tag` = 'FD60AcrovynFaced' && `configuration` = '$intumescentseals' && $leafHeightNoOPValue <= `height_max` && $leafWidth1Value<= `width_max`";
                    $getConditions = array_merge($getConditions, [['intumescentseals2.firerating', $fireRatingValue],['intumescentseals2.tag', 'FD60AcrovynFaced']]);
                    $sqlGetConditions = array_merge($sqlGetConditions, [['intumescentseals2.firerating', $fireRatingValue],['intumescentseals2.tag', 'FD60AcrovynFaced'], [ 'intumescentseals2.height_max', '>=', $leafHeightNoOPValue], [ 'intumescentseals2.width_max', '>=', $leafWidth1Value]]);
                } elseif ($frameMaterialNew == 'MDF') {
                    // $IntumescentSeals_A = SettingIntumescentSeals2::where(['configurableitems' => $pageId , 'configuration' => $intumescentseals,'firerating' => $fireRatingValue,'tag' => 'FD60MDFFrames'])->get();
                    // $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId && `firerating` = '$fireRatingValue' && `tag` = 'FD60MDFFrames' && `configuration` = '$intumescentseals' && $leafHeightNoOPValue <= `height_max` && $leafWidth1Value<= `width_max`";
                    $getConditions = array_merge($getConditions, [['intumescentseals2.firerating', $fireRatingValue],['intumescentseals2.tag', 'FD60MDFFrames']]);
                    $sqlGetConditions = array_merge($sqlGetConditions, [['intumescentseals2.firerating', $fireRatingValue],['intumescentseals2.tag', 'FD60MDFFrames'], [ 'intumescentseals2.height_max', '>=', $leafHeightNoOPValue], [ 'intumescentseals2.width_max', '>=', $leafWidth1Value]]);
                } else {
                    // $IntumescentSeals_A = SettingIntumescentSeals2::where(['configurableitems' => $pageId , 'configuration' => $intumescentseals,'firerating' => $fireRatingValue,'tag' => $fireRatingValue])->get();
                    // $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId && `firerating` = '$fireRatingValue' && `tag` = '$fireRatingValue' && `configuration` = '$intumescentseals' && $leafHeightNoOPValue <= `height_max` && $leafWidth1Value<= `width_max`";

                    $getConditions = array_merge($getConditions, [['intumescentseals2.firerating', $fireRatingValue],['intumescentseals2.tag', $fireRatingValue]]);
                    $sqlGetConditions = array_merge($sqlGetConditions, [['intumescentseals2.firerating', $fireRatingValue],['intumescentseals2.tag', $fireRatingValue], [ 'intumescentseals2.height_max', '>=', $leafHeightNoOPValue], [ 'intumescentseals2.width_max', '>=', $leafWidth1Value]]);

                }

                $IntumescentSeals_A = SettingIntumescentSeals2::select('setting_intumescentseals2.*','intumescentseals2.id as intumescentseals2_id','intumescentseals2.*')->Join('intumescentseals2', function($join): void {
                    $join->on('setting_intumescentseals2.id', '=', 'intumescentseals2.id');
                })
                ->where($getConditions)
                ->whereRaw("FIND_IN_SET(?, REPLACE(customeleafTypes, ' ', '')) > 0", [$intumescentsealsleaftype])
                ->get();

                $sql = SettingIntumescentSeals2::select('setting_intumescentseals2.*', 'intumescentseals2.id as intumescentseals2_id','intumescentseals2.*')->Join('intumescentseals2', function($join): void {
                    $join->on('setting_intumescentseals2.id', '=', 'intumescentseals2.intumescentseals2_id');
                })
                ->whereRaw("FIND_IN_SET(?, REPLACE(customeleafTypes, ' ', '')) > 0", [$intumescentsealsleaftype])
                ->where($sqlGetConditions)->toSQL();

                foreach($IntumescentSeals_A as $content){
                    $selected = "";
                    if ($fireRatingValue == 'NFR') {
                        if($request->SelectedValue == $content["intumescentseals2_id"]){
                            $selected = "selected";
                        }

                        $data .= '<option value="'.$content["intumescentseals2_id"].'" '.$selected.'>'.$content["brand"].' - '.$content["intumescentSeals"].'</option>';
                    } elseif (checkValid($height, $width, $content->toArray())) {
                        if($request->SelectedValue == $content["intumescentseals2_id"]){
                            $selected = "selected";
                        }

                        // echo "id: " . $content["id"] . "&nbsp;&nbsp;&nbsp;" . "\tintumescentSeals: &nbsp;" . $content["intumescentSeals"] . "<br>";
                        // $data .=  "id: " . $content["id"] . "\t" . "configuration: " . $content["configuration"]. "\t" . "intumescentSeals: " . $content["intumescentSeals"] . "\t". $content["widthPoint1"] . "\t" . $content["widthPoint2"] . "\t" . $content["heightPoint1"] . "\t" . $content["heightPoint2"] . "<br>";
                        $data .= '<option value="'.$content["intumescentseals2_id"].'" '.$selected.'>'.$content["brand"].' - '.$content["intumescentSeals"].'</option>';
                    }
                }

                if($data !== ''){
                    $IS = '<option value="">Select Intumescent Seal Arrangement</option>'.$data;
                    echo json_encode(['status'=>'ok','data'=> $IS , 'c'=>$IntumescentSeals_A,'allValue'=>$allValue ,'sql'=> $sql,'msg'=>'null']);
                } else {
                    // $msg = "In $fireRatingValue, Leaf Width 1 = $leafWidth1Value and Leaf Height = $leafHeightNoOPValue for `$intumescentseals` is not possible.";
                    $msg = sprintf('It’s not possible to make this door with these configurations. Fire Rating = %s | Leaf Width 1 = %s | Leaf Height = %s | Configuration = %s ', $fireRatingValue, $leafWidth1Value, $leafHeightNoOPValue, $intumescentseals);
                    echo json_encode(['status'=>'error2','data'=> $data, 'c'=>$IntumescentSeals_A,'allValue'=>$allValue,'sql'=> $sql,'msg'=>$msg]);
                }



            }else{
                $UserId = CompanyUsers(true);
                $getConditions = [
                    ['selected_intumescentseals2.selected_configurableitems', $pageId],
                    ['selected_intumescentseals2.selected_configuration', $intumescentseals],
                    // ['selected_intumescentseals2.selected_intumescentseals2_user_id',Auth::user()->id]
                ];
                $sqlGetConditions = [
                    ['selected_intumescentseals2.selected_configurableitems', $pageId],
                    ['selected_intumescentseals2.selected_configuration', $intumescentseals],
                    // ['selected_intumescentseals2.selected_intumescentseals2_user_id',Auth::user()->id]
                ];

                if ($fireRatingValue == 'NFR') {
                    // $IntumescentSeals_A = SettingIntumescentSeals2::where(['configurableitems' => $pageId , 'configuration' => $intumescentseals])->get();
                    // $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId && `configuration` = '$intumescentseals'";
                } elseif ($doorLeafFacingValueNew == 'CS_acrovyn' && ($fireRatingValue == 'FD30' || $fireRatingValue == 'FD30s')) {
                    // $IntumescentSeals_A = SettingIntumescentSeals2::where(['configurableitems' => $pageId , 'configuration' => $intumescentseals,'firerating' => $fireRatingValue,'tag' => 'FD60AcrovynFaced'])->get();
                    // $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId  && `configuration` = '$intumescentseals' && `firerating` = '$fireRatingValue' && `tag` = 'FD30AcrovynFaced'  && $leafHeightNoOPValue <= `height_max` && $leafWidth1Value<= `width_max`";
                    $getConditions = array_merge($getConditions, [['selected_intumescentseals2.selected_firerating', $fireRatingValue],['selected_intumescentseals2.selected_tag', 'FD60AcrovynFaced']]);
                    $sqlGetConditions = array_merge($sqlGetConditions, [['selected_intumescentseals2.selected_firerating', $fireRatingValue],['selected_intumescentseals2.selected_tag', 'FD60AcrovynFaced'], ['selected_intumescentseals2.selected_Point2height', '>=', $leafHeightNoOPValue], [ 'selected_intumescentseals2.selected_Point2width', '>=', $leafWidth1Value]]);
                } elseif ($doorLeafFacingValueNew == 'CS_acrovyn' && ($fireRatingValue == 'FD60' || $fireRatingValue == 'FD60s')) {
                    // $IntumescentSeals_A = SettingIntumescentSeals2::where(['configurableitems' => $pageId , 'configuration' => $intumescentseals,'firerating' => $fireRatingValue,'tag' => 'FD60AcrovynFaced'])->get();
                    // $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId && `firerating` = '$fireRatingValue' && `tag` = 'FD60AcrovynFaced' && `configuration` = '$intumescentseals' && $leafHeightNoOPValue <= `height_max` && $leafWidth1Value<= `width_max`";
                    $getConditions = array_merge($getConditions, [['selected_intumescentseals2.selected_firerating', $fireRatingValue],['selected_intumescentseals2.selected_tag', 'FD60AcrovynFaced']]);
                    $sqlGetConditions = array_merge($sqlGetConditions, [['selected_intumescentseals2.selected_firerating', $fireRatingValue],['selected_intumescentseals2.selected_tag', 'FD60AcrovynFaced'], [ 'selected_intumescentseals2.selected_Point2height', '>=', $leafHeightNoOPValue], [ 'selected_intumescentseals2.selected_Point2width', '>=', $leafWidth1Value]]);
                } elseif ($frameMaterialNew == 'MDF') {
                    // $IntumescentSeals_A = SettingIntumescentSeals2::where(['configurableitems' => $pageId , 'configuration' => $intumescentseals,'firerating' => $fireRatingValue,'tag' => 'FD60MDFFrames'])->get();
                    // $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId && `firerating` = '$fireRatingValue' && `tag` = 'FD60MDFFrames' && `configuration` = '$intumescentseals' && $leafHeightNoOPValue <= `height_max` && $leafWidth1Value<= `width_max`";
                    $getConditions = array_merge($getConditions, [['selected_intumescentseals2.selected_firerating', $fireRatingValue],['selected_intumescentseals2.selected_tag', 'FD60MDFFrames']]);
                    $sqlGetConditions = array_merge($sqlGetConditions, [['selected_intumescentseals2.selected_firerating', $fireRatingValue],['selected_intumescentseals2.selected_tag', 'FD60MDFFrames'], [ 'selected_intumescentseals2.selected_Point2height', '>=', $leafHeightNoOPValue], [ 'selected_intumescentseals2.selected_Point2width', '>=', $leafWidth1Value]]);
                } else {
                    // $IntumescentSeals_A = SettingIntumescentSeals2::where(['configurableitems' => $pageId , 'configuration' => $intumescentseals,'firerating' => $fireRatingValue,'tag' => $fireRatingValue])->get();
                    // $sql = "SELECT * FROM `setting_intumescentseals` WHERE `configurableitems` = $pageId && `firerating` = '$fireRatingValue' && `tag` = '$fireRatingValue' && `configuration` = '$intumescentseals' && $leafHeightNoOPValue <= `height_max` && $leafWidth1Value<= `width_max`";

                    $getConditions = array_merge($getConditions, [['selected_intumescentseals2.selected_firerating', $fireRatingValue],['selected_intumescentseals2.selected_tag', $fireRatingValue]]);
                    $sqlGetConditions = array_merge($sqlGetConditions, [['selected_intumescentseals2.selected_firerating', $fireRatingValue],['selected_intumescentseals2.selected_tag', $fireRatingValue], [ 'selected_intumescentseals2.selected_Point2height', '>=', $leafHeightNoOPValue], [ 'selected_intumescentseals2.selected_Point2width', '>=', $leafWidth1Value]]);

                }

                $IntumescentSeals_A = SettingIntumescentSeals2::select('setting_intumescentseals2.*','selected_intumescentseals2.id as selected_intumescentseals2_id','selected_intumescentseals2.*')->Join('selected_intumescentseals2', function($join): void {
                    $join->on('setting_intumescentseals2.id', '=', 'selected_intumescentseals2.intumescentseals2_id');
                })
                ->wherein('selected_intumescentseals2.selected_intumescentseals2_user_id',$UserId)->where($getConditions)
                ->whereRaw("FIND_IN_SET(?, REPLACE(customeleafTypes, ' ', '')) > 0", [$intumescentsealsleaftype])
                ->orderBy('setting_intumescentseals2.brand','ASC')->get();
                // ->where($getConditions)->orderBy('setting_intumescentseals2.brand','ASC')->get();

                $sql = SettingIntumescentSeals2::select('setting_intumescentseals2.*', 'selected_intumescentseals2.id as selected_intumescentseals2_id','selected_intumescentseals2.*')->Join('selected_intumescentseals2', function($join): void {
                    $join->on('setting_intumescentseals2.id', '=', 'selected_intumescentseals2.intumescentseals2_id');
                })
                ->wherein('selected_intumescentseals2.selected_intumescentseals2_user_id',$UserId)
                ->whereRaw("FIND_IN_SET(?, REPLACE(customeleafTypes, ' ', '')) > 0", [$intumescentsealsleaftype])
                ->where($sqlGetConditions)->toSQL();
                // ->where($sqlGetConditions)->toSQL();


                foreach($IntumescentSeals_A as $content){

                    $selected = "";
                    if ($fireRatingValue == 'NFR') {
                        if($request->SelectedValue == $content["intumescentseals2_id"]){
                            $selected = "selected";
                        }

                        $data .= '<option value="'.$content["intumescentseals2_id"].'" '.$selected.'>'.$content["brand"].' - '.$content["intumescentSeals"].'</option>';
                    } elseif (checkValid($height, $width, $content->toArray())) {
                        if($request->SelectedValue == $content["intumescentseals2_id"]){
                            $selected = "selected";
                        }

                        // echo "id: " . $content["id"] . "&nbsp;&nbsp;&nbsp;" . "\tintumescentSeals: &nbsp;" . $content["intumescentSeals"] . "<br>";
                        // $data .=  "id: " . $content["id"] . "\t" . "configuration: " . $content["configuration"]. "\t" . "intumescentSeals: " . $content["intumescentSeals"] . "\t". $content["widthPoint1"] . "\t" . $content["widthPoint2"] . "\t" . $content["heightPoint1"] . "\t" . $content["heightPoint2"] . "<br>";
                        $data .= '<option value="'.$content["intumescentseals2_id"].'" '.$selected.'>'.$content["brand"].' - '.$content["intumescentSeals"].'</option>';
                    }
                }

                // echo "<pre>";
                // print_r($data);die;

                if($data !== ''){
                    $IS = '<option value="">Select Intumescent Seal Arrangement</option>'.$data;
                    echo json_encode(['status'=>'ok','data'=> $IS , 'c'=>$IntumescentSeals_A,'allValue'=>$allValue ,'sql'=> $sql,'msg'=>'null']);
                } else {
                    // $msg = "In $fireRatingValue, Leaf Width 1 = $leafWidth1Value and Leaf Height = $leafHeightNoOPValue for `$intumescentseals` is not possible.";
                    $msg = sprintf('It’s not possible to make this door with these configurations. Fire Rating = %s | Leaf Width 1 = %s | Leaf Height = %s | Configuration = %s ', $request->fireRatingValue, $leafWidth1Value, $leafHeightNoOPValue, $intumescentseals);
                    echo json_encode(['status'=>'error2','data'=> $data, 'c'=>$IntumescentSeals_A,'allValue'=>$allValue,'sql'=> $sql,'msg'=>$msg]);
                }



            }












        } else {
            $msg = "Leaf Width 1 and Leaf Height is never be null.";
            echo json_encode(['status'=>'error2','data'=> 'null', 'c'=>'null','allValue'=>'null','sql'=> 'null','msg'=>$msg]);
        }
    }


    public function opGlassTypeFilterUrl(Request $request): void
    {
        $pageId = $request->pageId;
        $fireRating = $request->fireRating;
        if($fireRating == 'FD30' || $fireRating == 'FD30s'){
            $fireRating = 'FD30';
        }elseif($fireRating == 'FD60' || $fireRating == 'FD60s'){
            $fireRating = 'FD60';
        }

        $configurationDoor = configurationDoor($pageId);
        $fireRatingDoor = fireRatingDoor($fireRating);
        $opGlassIntegrity = $request->opGlassIntegrity;
        $userType = Auth::user()->UserType;
        if($userType=="1" || $userType=="4"){
            if($fireRating=="NFR"){
                $opglassType = GlassType::where($configurationDoor,$pageId)->get();
            } else {
                $opglassType = GlassType::where($configurationDoor,$pageId)
                    ->where($fireRatingDoor,$fireRating)
                    ->where('GlassIntegrity',$opGlassIntegrity)
                    ->distinct('id')
                    ->get();
            }
        } else {
            $userIds = CompanyUsers();
            if($fireRating=="NFR"){
                $opglassType = GlassType::Join('selected_glass_type', function($join): void {
                    $join->on('glass_type.id', '=', 'selected_glass_type.glass_id');
                })
                    ->where('glass_type.'.$configurationDoor,$pageId)
                    // ->where('glass_type.'.$fireRatingDoor,$fireRating)
                    // ->where('options.UnderParent2',$opGlassIntegrity)
                    ->wherein('selected_glass_type.editBy',$userIds)
                    ->distinct('glass_type.id')
                    ->get(['glass_type.*']);
            } else {

                $opglassType = GlassType::Join('selected_glass_type', function($join): void {
                    $join->on('glass_type.id', '=', 'selected_glass_type.glass_id');
                })
                    ->where('glass_type.'.$configurationDoor,$pageId)
                    ->where('glass_type.'.$fireRatingDoor,$fireRating)
                    ->where('glass_type.GlassIntegrity',$opGlassIntegrity)
                    ->wherein('selected_glass_type.editBy',$userIds)
                    ->distinct('glass_type.id')
                    ->get(['glass_type.*']);
            }
        }

        if(!empty($opglassType) && count( $opglassType)){
            echo json_encode(['status'=>'ok','data'=> $opglassType]);
        }else{
            echo json_encode(['status'=>'error','data'=> '']);
        }
    }

    public function filterOverpanelGlass(Request $request): void{
        $pageId = $request->pageId;
        $fireRating = $request->fireRating;
        $type = $request->type;

        $data = OverpanelGlassGlazing::select('overpanel_glass_glazing.*');

        if($type == "overpanel"){
            $oPWidth = $request->oPWidth;
            $data = $data->where('overpanel_glass_glazing.FanLightWidth', '>=', $oPWidth);
        }elseif($type == "SL1"){
            $SL1Height = $request->SL1Height;
            $data = $data->where('overpanel_glass_glazing.SideScreenHeight', '>=', $SL1Height);
        }elseif($type == "SL2"){
            $SL2Height = $request->SL2Height;
            $data = $data->where('overpanel_glass_glazing.SideScreenHeight', '>=', $SL2Height);
        }

        $integrity = $request->integrity;
        $userIds = CompanyUsers();

        if($fireRating == 'FD30' || $fireRating == 'FD30s'){
            $fireRating = 'FD30';
        }elseif($fireRating == 'FD60' || $fireRating == 'FD60s'){
            $fireRating = 'FD60';
        }

        $configurationDoor = configurationDoor($pageId);
        $fireRatingDoor = fireRatingDoor($fireRating);
        $userType = Auth::user()->UserType;

        if($fireRating != 'NFR'){
            $data = $data->where('overpanel_glass_glazing.GlassIntegrity', $integrity)
            ->where('overpanel_glass_glazing.'.$fireRatingDoor, $fireRating);
        }

        // ->join('glass_type','glass_type.id','glass_glazing_system.glass_id')
        $data = $data->join('selected_overpanel_glass_glazing','selected_overpanel_glass_glazing.glass_glazing_id','overpanel_glass_glazing.id')
        ->where('overpanel_glass_glazing.'.$configurationDoor, $pageId)
        ->where('selected_overpanel_glass_glazing.editBy', Auth::user()->id)
        ->groupBy('overpanel_glass_glazing.GlassType')
        ->orderBy('overpanel_glass_glazing.Key','ASC')
        ->get();

        if(!empty($data) && count( $data)){
            echo json_encode(['status'=>'ok','data'=> $data]);
        }else{
            echo json_encode(['status'=>'error','data'=> '']);
        }

    }

    public function overpanelglassTypeFilter(Request $request): void{
        $userIds = CompanyUsers();
        $pageId = $request->pageId;
        $glassType = $request->glassType;
        $userType = Auth::user()->UserType;
        $fireRating = $request->fireRating;
        if($request->fireRating == 'FD30' || $request->fireRating == 'FD30s'){
            $request->fireRating = 'FD30';
        }elseif($request->fireRating == 'FD60' || $request->fireRating == 'FD60s'){
            $request->fireRating = 'FD60';
        }

        $configurationDoor = configurationDoor($pageId);
        $fireRatingDoor = fireRatingDoor($request->fireRating);
        if ($userType=="1" ||$userType=="4") {
            $OverpanelGlassGlazing = OverpanelGlassGlazing::where($configurationDoor,$pageId)->where('Key',$glassType)->where($fireRatingDoor,$request->fireRating)->where('overpanel_glass_glazing.EditBy',1)->first();
        } elseif ($request->fireRating == 'NFR') {
            $OverpanelGlassGlazing = OverpanelGlassGlazing::where('overpanel_glass_glazing.'.$configurationDoor,$pageId)
            ->where('overpanel_glass_glazing.Key',$glassType)
            ->wherein('overpanel_glass_glazing.EditBy',$userIds)
            ->groupBy('overpanel_glass_glazing.Key')
            ->first(['overpanel_glass_glazing.*']);
        } else{
            $OverpanelGlassGlazing = OverpanelGlassGlazing::where('overpanel_glass_glazing.'.$configurationDoor,$pageId)
            ->where('overpanel_glass_glazing.Key',$glassType)
            ->wherein('overpanel_glass_glazing.EditBy',$userIds)
            ->where('overpanel_glass_glazing.'.$fireRatingDoor,$request->fireRating)
            ->first(['overpanel_glass_glazing.*']);
        }

        $authdata = Auth::user();
        $lippingSpecies=[];

        $SelectedLippingSpecies = SelectedLippingSpeciesItems::wherein('selected_lipping_species_items.selected_user_id', $userIds)->groupBy("selected_lipping_species_id")->get();
        $SelectedLippingSpeciesIds = array_column($SelectedLippingSpecies->toArray(), "selected_lipping_species_id");

        if(!empty($OverpanelGlassGlazing) && ($fireRating == "FD30" || $fireRating == "FD30s" || $fireRating == "FD60" || $fireRating == "FD60s")){
            $lippingSpecies = GetOptions([["lipping_species.Status", "=", 1], ["lipping_species.MinValue", ">=", $OverpanelGlassGlazing->Beading ]], "join", "lippingSpecies", "query",[],[["lipping_species.MinValue", "<=", $OverpanelGlassGlazing->Beading], ["lipping_species.MaxValues", ">=", $OverpanelGlassGlazing->Beading]]);
        }

        if($fireRating=="NFR"){
            $lippingSpecies = GetOptions(["lipping_species.Status", "=", 1], "join", "lippingSpecies", "query");
        }

        if(in_array($authdata->UserType, [1,4])){
            $lippingSpecies = $lippingSpecies->get();

        }else{
            $lippingSpecies = $lippingSpecies->whereIn("lipping_species.id",  $SelectedLippingSpeciesIds)->get();
        }

        if(!empty($OverpanelGlassGlazing)){
            echo json_encode(['status'=>'ok','data'=> $OverpanelGlassGlazing,'lippingSpecies'=>$lippingSpecies]);
        } else {
            echo json_encode(['status'=>'error','data'=> '']);
        }
    }

    public function lippingThickness(Request $request): void{
        $pageId = $request->pageId;
        $fireRating = $request->fireRating;
        if($fireRating == 'FD30' || $fireRating == 'FD30s'){
            $fireRating = 'FD30';
        }elseif($fireRating == 'FD60' || $fireRating == 'FD60s'){
            $fireRating = 'FD60';
        }

        $lippingThickness = Option::where('configurableitems',$pageId)->where('UnderAttribute',$fireRating)->where('OptionSlug','lipping_thickness')->get();
        if(!empty($lippingThickness) && count( $lippingThickness)){
            echo json_encode(['status'=>'ok','data'=> $lippingThickness]);
        }else{
            echo json_encode(['status'=>'error','data'=> '']);
        }
    }

    public function getGlassOptions(Request $request): void{
        $FireRating = $request->FireRating;
        if($FireRating == 'IGU 0-0'){
            $FireRating = '0-0';
        }elseif($FireRating == 'IGU 30-0'){
            $FireRating = '30-0';
        }elseif($FireRating == 'IGU 30-30'){
            $FireRating = '30-30';
        }

        $UserId = CompanyUsers(true);

        if(!empty($FireRating)){
            $glassData = ScreenGlassType::join('selected_screen_glass','screen_glass_type.id','selected_screen_glass.glass_id')
            ->where(['screen_glass_type.FireRating' => $FireRating,'screen_glass_type.status'=>1])
            ->wherein('selected_screen_glass.editBy', $UserId)
            ->select('screen_glass_type.*')
            ->orderBy('screen_glass_type.GlassType','ASC')
            ->get();

            $glassDataSelectedOption = ScreenGlassType::where(['screen_glass_type.FireRating' => $FireRating,'screen_glass_type.status'=>1])
            ->select('screen_glass_type.*')
            ->orderBy('screen_glass_type.GlassType','ASC')
            ->get();

            $NFRGlassData = ScreenGlassType::join('selected_screen_glass','screen_glass_type.id','selected_screen_glass.glass_id')
            ->where(['screen_glass_type.status'=>1])
            ->wherein('selected_screen_glass.editBy', $UserId)
            ->select('screen_glass_type.*')
            ->orderBy('screen_glass_type.GlassType','ASC')
            ->get();
        }

        if(!empty($glassData) && count( $glassData)){
            echo json_encode(['status'=>'ok','data'=> $glassData,'dataNFR' => $NFRGlassData,'dataSelected' => $glassDataSelectedOption]);
        }else{
            echo json_encode(['status'=>'error','data'=> '']);
        }
    }

    public function glazingFilterScreen(Request $request): void{
        $FireRating = $request->FireRating;
        $SinglePane = $request->SinglePane;
        $frameWidth = $request->frameWidth;
        $frameHeight = $request->frameHeight;
        if($FireRating == 'IGU 0-0'){
            $FireRating = '0-0';
        }elseif($FireRating == 'IGU 30-0'){
            $FireRating = '30-0';
        }elseif($FireRating == 'IGU 30-30'){
            $FireRating = '30-30';
        }

        if(!empty($frameWidth) && !empty($frameHeight)){


            $data = '';
            $configuration = '';
            $width = 0;
            $height = 0;

            $width = (int)$request->frameWidth;
            $height = (int)$request->frameHeight;

            $allValue =  $SinglePane . '==' . $FireRating .'=='.$frameHeight .'=='. $frameWidth;

            $UserType = Auth::user()->UserType;
            if(in_array($UserType,[1,4])){

                // $getConditions = [
                //     ['intumescentseals2.configurableitems', $pageId],
                //     ['intumescentseals2.configuration', $intumescentseals]
                // ];


                // if($fireRatingValue == 'NFR'){

                // } else {

                //     $getConditions = array_merge($getConditions, [['intumescentseals2.firerating', $fireRatingValue],['intumescentseals2.tag', $fireRatingValue]]);
                // }

                // $IntumescentSeals_A = SettingIntumescentSeals2::select('setting_intumescentseals2.*','intumescentseals2.id as intumescentseals2_id','intumescentseals2.*')->Join('intumescentseals2', function($join) {
                //     $join->on('setting_intumescentseals2.id', '=', 'intumescentseals2.id');
                // })
                // ->where($getConditions)->get();

                // foreach($IntumescentSeals_A as $content){
                //     $selected = "";
                //     if($fireRatingValue == 'NFR'){
                //         if($request->SelectedValue == $content["intumescentseals2_id"]){
                //             $selected = "selected";
                //         }
                //         $data .= '<option value="'.$content["intumescentseals2_id"].'" '.$selected.'>'.$content["brand"].' - '.$content["intumescentSeals"].'</option>';
                //     } else {
                //         if( checkValid($height, $width, $content) ){
                //             if($request->SelectedValue == $content["intumescentseals2_id"]){
                //                 $selected = "selected";
                //             }

                //             $data .= '<option value="'.$content["intumescentseals2_id"].'" '.$selected.'>'.$content["brand"].' - '.$content["intumescentSeals"].'</option>';
                //         }
                //     }
                // }

                // if($data != ''){
                //     $IS = '<option value="">Select Intumescent Seal Arrangement</option>'.$data;
                //     echo json_encode(array('status'=>'ok','data'=> $IS , 'c'=>$IntumescentSeals_A,'allValue'=>$allValue ,'msg'=>'null'));
                // } else {
                //     $msg = "It’s not possible to make this door with these configurations. Fire Rating = $fireRatingValue | Leaf Width 1 = $leafWidth1Value | Leaf Height = $leafHeightNoOPValue | Configuration = $intumescentseals ";
                //     echo json_encode(array('status'=>'error2','data'=> $data, 'c'=>$IntumescentSeals_A,'allValue'=>$allValue,'msg'=>$msg));
                // }
            }else{
                $UserId = CompanyUsers(true);
                $getConditions = [
                    ['screen_glass_type.GlassType', $SinglePane],
                    ['screen_glass_type.status', 1],
                ];

                if($FireRating == '0-0'){
                } else {
                    $getConditions = array_merge($getConditions, [['screen_glass_type.FireRating', $FireRating]]);
                }

                $ScreenGlassType = ScreenGlassType::select('screen_glass_type.*','screen_glazing_type.*')
                ->join('screen_glazing_type','screen_glass_type.id','screen_glazing_type.ScreenGlassId')
                ->join('selected_screen_glazing','selected_screen_glazing.glazing_id','screen_glazing_type.id')
                ->wherein('selected_screen_glazing.editBy',$UserId)
                ->where($getConditions)->orderBy('screen_glass_type.GlassType','ASC')->get();

                foreach($ScreenGlassType as $content){

                    $selected = "";
                    if ($FireRating == '0-0') {
                        if($request->SelectedValue == $content["GlazingSystem"]){
                            $selected = "selected";
                        }

                        $data .= '<option value="'.$content["GlazingSystem"].'" '.$selected.'>'.$content["GlazingSystem"].'</option>';
                    } elseif (checkValidScreen($height, $width, $content->toArray())) {
                        if($request->SelectedValue == $content["GlazingSystem"]){
                            $selected = "selected";
                        }

                        $data .= '<option value="'.$content["GlazingSystem"].'" '.$selected.'>'.$content["GlazingSystem"].'</option>';
                    }
                }

                // echo "<pre>";
                // print_r($data);die;

                if($data !== ''){
                    $IS = '<option value="">Select Glazing System</option>'.$data;
                    echo json_encode(['status'=>'ok','data'=> $IS , 'c'=>$ScreenGlassType,'allValue'=>$allValue ,'msg'=>'null']);
                } else {
                    $msg = "It’s not possible to make this screen with these configurations.";
                    echo json_encode(['status'=>'error2','data'=> $data, 'c'=>$ScreenGlassType,'allValue'=>$allValue,'msg'=>$msg]);
                }
            }
        } else {
            $msg = "Frame Width and Frame Height is never be null.";
            echo json_encode(['status'=>'error2','data'=> 'null', 'c'=>'null','allValue'=>'null','msg'=>$msg]);
        }
    }

    public function screenGlassGlazing(Request $request): void{
        $FireRating = $request->FireRating;
        $glassType = $request->glassType;
        if($FireRating == 'IGU 0-0'){
            $FireRating = '0-0';
        }elseif($FireRating == 'IGU 30-0'){
            $FireRating = '30-0';
        }elseif($FireRating == 'IGU 30-30'){
            $FireRating = '30-30';
        }

        if(!empty($FireRating)){
            $glassData = ScreenGlassType::join('screen_glazing_type','screen_glass_type.id','screen_glazing_type.ScreenGlassId')
            ->where(['screen_glass_type.FireRating' => $FireRating,'screen_glass_type.GlassType' => $glassType,'screen_glass_type.status'=>1])
            ->select('screen_glazing_type.*','screen_glass_type.*')
            ->first();

            $authdata = Auth::user();
            $lippingSpecies=[];

            $SelectedLippingSpecies = SelectedLippingSpeciesItems::wherein('selected_lipping_species_items.selected_user_id', CompanyUsers())->groupBy("selected_lipping_species_id")->get();
            $SelectedLippingSpeciesIds = array_column($SelectedLippingSpecies->toArray(), "selected_lipping_species_id");

            if(!empty($glassData)){
                $lippingSpecies = GetOptions([["lipping_species.Status", "=", 1], ["lipping_species.MinValue", ">=", $glassData->Beading ]], "join", "lippingSpecies", "query",[],[["lipping_species.MinValue", "<=", $glassData->Beading], ["lipping_species.MaxValues", ">=", $glassData->Beading]]);

            }

            if($FireRating=="0-0"){
                $lippingSpecies = GetOptions([["lipping_species.Status", "=", 1]], "join", "lippingSpecies", "query");
            }

            if(in_array($authdata->UserType, [1,4])){
                $lippingSpecies = $lippingSpecies->get();

            }else{
                $lippingSpecies = $lippingSpecies->whereIn("lipping_species.id",  $SelectedLippingSpeciesIds)->get();
            }
        }

        if(!empty($glassData)){
            echo json_encode(['status'=>'ok','data'=> $glassData,'lippingSpecies'=>$lippingSpecies]);
        }else{
            echo json_encode(['status'=>'error','data'=> '']);
        }
    }

    public function screenGlazingThickness(Request $request): void{
        $FireRating = $request->FireRating;
        $GlazingSystem = $request->GlazingSystem;
        if($FireRating == 'IGU 0-0'){
            $FireRating = '0-0';
        }elseif($FireRating == 'IGU 30-0'){
            $FireRating = '30-0';
        }elseif($FireRating == 'IGU 30-30'){
            $FireRating = '30-30';
        }

        if(!empty($FireRating)){
            $GlazingSystem = ScreenGlassType::join('screen_glazing_type','screen_glass_type.id','screen_glazing_type.ScreenGlassId')
            ->where(['screen_glass_type.FireRating' => $FireRating,'screen_glazing_type.GlazingSystem' => $GlazingSystem,'screen_glass_type.status'=>1])
            // ->wherein('screen_glass_type.EditBy',CompanyUsers())
            ->select('screen_glazing_type.*','screen_glass_type.*')
            ->first();
        }

        if(!empty($GlazingSystem)){
            echo json_encode(['status'=>'ok','data'=> $GlazingSystem]);
        }else{
            echo json_encode(['status'=>'error','data'=> '']);
        }
    }

    public function glassGlazingFilter(Request $request): void{
        $pageId = $request->pageId;
        $fireRating = $request->fireRating;
        $integrity = $request->integrity;
        $userIds = CompanyUsers();

        if($fireRating == 'FD30' || $fireRating == 'FD30s'){
            $fireRating = 'FD30';
        }elseif($fireRating == 'FD60' || $fireRating == 'FD60s'){
            $fireRating = 'FD60';
        }

        $configurationDoor = configurationDoor($pageId);
        $fireRatingDoor = fireRatingDoor($fireRating);

        $leaf1VpAreaSizeM2Value = $request->leaf1VpAreaSizeM2Value;
        $userType = Auth::user()->UserType;

        if($fireRating == 'NFR'){
            $data = GlassGlazingSystem::select('glass_glazing_system.VPAreaSize as leaf1VpAreaSizeM2Value','glass_type.*')
                    ->join('glass_type','glass_type.id','glass_glazing_system.glass_id')
                    ->leftJoin('selected_glass_type','selected_glass_type.glass_id','glass_glazing_system.glass_id')
                    ->where('glass_type.GlassIntegrity', $integrity)
                    ->where('glass_type.'.$configurationDoor, $pageId)
                    ->wherein('glass_type.EditBy', $userIds)
                    ->where('glass_glazing_system.VPAreaSize', '>=', $leaf1VpAreaSizeM2Value)
                    ->groupBy('glass_glazing_system.glass_id')
                    ->orderBy('glass_type.Key','ASC')
                    ->where('selected_glass_type.editBy',Auth::user()->id)
                    ->get();
        }
        else{
            $data = GlassGlazingSystem::select('glass_glazing_system.VPAreaSize as leaf1VpAreaSizeM2Value','glass_type.*')
                    ->join('glass_type','glass_type.id','glass_glazing_system.glass_id')
                    ->leftJoin('selected_glass_type','selected_glass_type.glass_id','glass_glazing_system.glass_id')
                    ->where('glass_type.GlassIntegrity', $integrity)
                    ->where('glass_type.'.$fireRatingDoor, $fireRating)
                    ->where('glass_type.'.$configurationDoor, $pageId)
                    ->wherein('glass_type.EditBy', $userIds)
                    ->where('glass_glazing_system.VPAreaSize', '>=', $leaf1VpAreaSizeM2Value)
                    ->groupBy('glass_glazing_system.glass_id')
                    ->orderBy('glass_type.Key','ASC')
                    ->where('selected_glass_type.editBy',Auth::user()->id)
                    ->get();
        }


        if(!empty($data) && count( $data)){
            echo json_encode(['status'=>'ok','data'=> $data]);
        }else{
            echo json_encode(['status'=>'error','data'=> '']);
        }
    }

    public function GlazingFilter(Request $request): void{
        $pageId = $request->pageId;
        $fireRating = $request->fireRating;
        $integrity = $request->integrity;
        $glassType = $request->glassType;

        $userIds = CompanyUsers();

        if($fireRating == 'FD30' || $fireRating == 'FD30s'){
            $fireRating = 'FD30';
        }elseif($fireRating == 'FD60' || $fireRating == 'FD60s'){
            $fireRating = 'FD60';
        }

        $configurationDoor = configurationDoor($pageId);
        $fireRatingDoor = fireRatingDoor($fireRating);

        $leaf1VpAreaSizeM2Value = $request->leaf1VpAreaSizeM2Value;
        $userType = Auth::user()->UserType;

        if (!empty($glassType)) {
            if($fireRating == 'NFR'){
                $GlazingData = GlassGlazingSystem::select('glass_glazing_system.VPAreaSize as leaf1VpAreaSizeM2Value','glass_glazing_system.GlassType','glazing_system.*')
                ->join('glazing_system','glazing_system.id','glass_glazing_system.glazing_system')
                ->leftJoin('selected_glazing_system','selected_glazing_system.glazingId','glass_glazing_system.glazing_system')
                ->where('glass_glazing_system.GlassType', str_replace('_', ' ', $glassType))
                ->where('glazing_system.'.$configurationDoor, $pageId)
                ->wherein('glazing_system.editBy', $userIds)
                ->where('glass_glazing_system.VPAreaSize', '>=', $leaf1VpAreaSizeM2Value)
                ->groupBy('glass_glazing_system.GlazingSystem')
                ->orderBy('glazing_system.Key','ASC')
                ->wherein('selected_glazing_system.userId',$userIds)
                ->get();
            }else{
                $GlazingData = GlassGlazingSystem::select('glass_glazing_system.VPAreaSize as leaf1VpAreaSizeM2Value','glass_glazing_system.GlassType','glazing_system.*')
                ->join('glazing_system','glazing_system.id','glass_glazing_system.glazing_system')
                ->leftJoin('selected_glazing_system','selected_glazing_system.glazingId','glass_glazing_system.glazing_system')
                ->where('glass_glazing_system.GlassType', str_replace('_', ' ', $glassType))
                ->where('glazing_system.'.$fireRatingDoor, $fireRating)
                ->where('glazing_system.'.$configurationDoor, $pageId)
                ->wherein('glazing_system.editBy', $userIds)
                ->where('glass_glazing_system.VPAreaSize', '>=', $leaf1VpAreaSizeM2Value)
                ->groupBy('glass_glazing_system.GlazingSystem')
                ->orderBy('glazing_system.Key','ASC')
                ->wherein('selected_glazing_system.userId',$userIds)
                ->get();
            }

            $glazingBeads = DB::table('options')->where('configurableitems', $pageId)->where('OptionSlug', 'leaf1_glazing_beads')->get(['id', 'OptionKey', 'OptionValue']);
            $beads = [];
            foreach($glazingBeads as $val){
                $beads[] = $val->OptionKey;
            }

            $glazing = json_encode($beads);
        } elseif (!empty($glassType)) {
            if($fireRating == 'NFR'){
                $GlazingData = GlassGlazingSystem::select('glass_glazing_system.VPAreaSize as leaf1VpAreaSizeM2Value','glass_glazing_system.GlassType','glazing_system.*')
                ->join('glazing_system','glazing_system.id','glass_glazing_system.glazing_system')
                ->join('selected_glazing_system','selected_glazing_system.glazingId','glass_glazing_system.glazing_system')
                ->where('glass_glazing_system.GlassType', str_replace('_', ' ', $glassType))
                ->where('glass_glazing_system.VPAreaSize', '>=', $leaf1VpAreaSizeM2Value)
                ->where('glazing_system.'.$configurationDoor, $pageId)
                ->groupBy('glass_glazing_system.glazing_system')
                ->orderBy('glazing_system.Key','ASC')
                ->where('selected_glazing_system.userId',Auth::user()->id)
                ->get();

            }
            else{
                $GlazingData = GlassGlazingSystem::select('glass_glazing_system.VPAreaSize as leaf1VpAreaSizeM2Value','glass_glazing_system.GlassType','glazing_system.*')
                ->join('glazing_system','glazing_system.id','glass_glazing_system.glazing_system')
                ->join('selected_glazing_system','selected_glazing_system.glazingId','glass_glazing_system.glazing_system')
                ->where('glazing_system.'.$fireRatingDoor, $fireRating)
                ->where('glass_glazing_system.GlassType', str_replace('_', ' ', $glassType))
                ->where('glass_glazing_system.VPAreaSize', '>=', $leaf1VpAreaSizeM2Value)
                ->where('glazing_system.'.$configurationDoor, $pageId)
                ->groupBy('glass_glazing_system.glazing_system')
                ->orderBy('glazing_system.Key','ASC')
                ->where('selected_glazing_system.userId',Auth::user()->id)
                ->get();

            }

            $GlassType = GlassType::where('Key',$glassType)->first();
            $glazing = $GlassType->GlazingBeads;
        }

        if(!empty($GlazingData) && count( $GlazingData)){
            echo json_encode(['status'=>'ok','data'=> $GlazingData,'GlazingBeads' => $glazing]);
        }else{
            echo json_encode(['status'=>'error','data'=> '','GlazingBeads' => $glazing]);
        }
    }


}
