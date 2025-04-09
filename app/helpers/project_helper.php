<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Redirect;

use App\Models\BOMDetails;
use App\Models\ConfigurableDoorFormula;
use App\Models\Color;
use App\Models\SelectedColor;
use App\Models\Item;
use App\Models\ItemMaster;
use App\Models\ScreenGlassType;
use App\Models\Customer;
use App\Models\Option;
use App\Models\LippingSpecies;
use App\Models\SelectedLippingSpecies;
use App\Models\SelectedIntumescentSeals2;
use App\Models\SettingIntumescentSeals2;
use App\Models\CompanyQuotationCounter;
use App\Models\Quotation;
use App\Models\User;
use App\Models\Company;
use App\Models\Architect;
use App\Models\AddIronmongery;
use App\Models\Project;
use App\Models\BOMSetting;
use App\Models\QuotationVersion;
use App\Models\BOMCalculation;
use App\Models\ScreenBOMCalculation;
use App\Models\IronmongeryInfoModel;
use App\Models\SelectedLippingSpeciesItems;
use App\Models\LippingSpeciesItems;
use App\Models\SelectedOption;
use App\Models\SettingCurrency;
use App\Models\DoorDimension;
use App\Models\SelectedIronmongery;
use App\Models\GeneralLabourCost;
use App\Models\SelectedDoordimension;
use App\Models\DoorLeafFacing;
use App\Models\GlazingSystem;
use App\Models\IntumescentSealColor;
use App\Models\Accoustics;
use App\Models\GlassType;
use App\Models\SelectedGlassType;
use App\Models\SelectedGlazingSystem;
use App\Models\SelectedIntumescentSealColor;
use App\Models\SelectedAccoustics;
use App\Models\SelectedDoorLeafFacing;
use App\Models\SelectedArchitraveType;
use App\Models\ArchitraveType;
use App\Models\SideScreenItem;
use App\Models\SideScreenItemMaster;
use App\Models\BomGeneralLabourCost;
use App\Models\ScreenGlazingType;
use Carbon\Carbon;
use App\Models\{NonConfigurableItems,NonConfigurableItemStore};

function nonConfigurableItem($Id,$vId,$userId,$select='',$sum=false,$query='get'){
    $NonConfigurableItems = NonConfigurableItemStore::join('non_configurable_items','non_configurable_item_store.nonConfigurableId','non_configurable_items.id')->join('quotation','non_configurable_item_store.quotationId','quotation.id')->leftJoin("quotation_versions",function($join) use ($vId): void{
        $join->on("quotation.id","quotation_versions.quotation_id")
            ->On("quotation_versions.id","=","quotation.VersionId")
            ->where("quotation_versions.id","=",$vId);
    });
    $NonConfigurableItems = $NonConfigurableItems->where(['non_configurable_item_store.quotationId'=>$Id, 'quotation.id'=>$Id,'non_configurable_item_store.versionId'=>$vId])->wherein('non_configurable_item_store.userId',$userId);

    if(!empty($select)){
        $NonConfigurableItems = $NonConfigurableItems->select('non_configurable_item_store.*');
    }else{
        $NonConfigurableItems = $NonConfigurableItems->select('non_configurable_items.*','non_configurable_item_store.id as NonConfigId','non_configurable_item_store.quantity','non_configurable_item_store.total_price','non_configurable_item_store.price as storePrice');
    }

    if($sum == true){
        $NonConfigurableItems = $NonConfigurableItems->orderBy('non_configurable_item_store.id','desc')->sum('non_configurable_item_store.total_price');
    }else{
        $NonConfigurableItems = $NonConfigurableItems->orderBy('non_configurable_item_store.id','desc')->$query();
    }


    return $NonConfigurableItems;
}

function itemAdjustCount($Id,$vId): float|int{
    if($vId > 0){
        $Schedule = Item::join('quotation_version_items','items.itemId','quotation_version_items.itemID')
        ->join('item_master','quotation_version_items.itemmasterID','item_master.id')
        ->where(['quotation_version_items.version_id'=>$vId,'items.VersionId'=>$vId,'items.QuotationId' => $Id])->get();
    } else {
        $Schedule = Item::join('item_master','items.itemId','item_master.itemID')
        ->where(['items.QuotationId' => $Id ])->get();
    }

    $TotalDoorSetPrice = 0;
    if(!empty($Schedule)){
        foreach($Schedule as $row){
            $TotalDoorSetPrice += (($row->AdjustPrice)?floatval($row->AdjustPrice):floatval($row->DoorsetPrice));
        }
    }

    return $TotalDoorSetPrice;
}

function countTotalPrice($Id,$vId,$itemId=0, $type=null): array{
    if($vId > 0){
        $QV = QuotationVersion::where('id',$vId)->first();
        $vId = $QV->version;
    }

    $bomVersion = BOMCalculation::where('QuotationId',$Id)->get()->first();
    if($vId == 0 || $bomVersion->VersionId == 0 || $bomVersion->VersionId == NULL){
        $data = BOMCalculation::join('items','items.itemId','bom_calculations.itemId')->join('item_master','item_master.itemID','items.itemId')->where('bom_calculations.QuotationId',$Id)->whereNotNull('bom_calculations.itemId')->select('bom_calculations.*')->get();

        $laborCost = BOMCalculation::join('items','items.itemId','bom_calculations.itemId')->join('item_master','item_master.itemID','items.itemId')->select('bom_calculations.*',DB::raw('count(*) as count'),DB::raw('sum(QuantityOfDoorTypes) as sum'),DB::raw('sum(UnitCost) as sum_UnitCost'),DB::raw('sum(TotalCost) as sum_TotalCost'),DB::raw('sum(UnitPriceSell) as sum_UnitPriceSell'),DB::raw('sum(GTSellPrice) as sum_GTSellPrice'))->where('bom_calculations.QuotationId',$Id)->where('bom_calculations.Category','GeneralLabourCosts')->whereNotNull('bom_calculations.itemId')->groupBy('bom_calculations.Description')->get();
    }elseif(!$itemId){
        $data = BOMCalculation::join('items','items.itemId','bom_calculations.itemId')->join('item_master','item_master.itemID','items.itemId')->where('bom_calculations.QuotationId',$Id)->where('bom_calculations.VersionId',$vId)->whereNotNull('bom_calculations.itemId')->select('bom_calculations.*')->get();

        $laborCost = BOMCalculation::join('items','items.itemId','bom_calculations.itemId')->join('item_master','item_master.itemID','items.itemId')->select('bom_calculations.*',DB::raw('count(*) as count'),DB::raw('sum(QuantityOfDoorTypes) as sum'),DB::raw('sum(UnitCost) as sum_UnitCost'),DB::raw('sum(TotalCost) as sum_TotalCost'),DB::raw('sum(UnitPriceSell) as sum_UnitPriceSell'),DB::raw('sum(GTSellPrice) as sum_GTSellPrice'))->where('bom_calculations.QuotationId',$Id)->where('bom_calculations.VersionId',$vId)->where('bom_calculations.Category','GeneralLabourCosts')->whereNotNull('bom_calculations.itemId')->groupBy('bom_calculations.Description')->get();
    }elseif(!is_int($itemId)){
        $doorType = $itemId;
        $data = BOMCalculation::join('items','items.itemId','bom_calculations.itemId')->leftJoin('item_master','item_master.itemID','items.itemId')->where('bom_calculations.QuotationId',$Id)->where('bom_calculations.DoorType', $doorType)->where('bom_calculations.VersionId',$vId)->whereNotNull('bom_calculations.itemId')->select('bom_calculations.*')->get();

        $laborCost = BOMCalculation::join('items','items.itemId','bom_calculations.itemId')->leftJoin('item_master','item_master.itemID','items.itemId')->select('bom_calculations.*',DB::raw('count(*) as count'),DB::raw('sum(QuantityOfDoorTypes) as sum'),DB::raw('sum(UnitCost) as sum_UnitCost'),DB::raw('sum(TotalCost) as sum_TotalCost'),DB::raw('sum(UnitPriceSell) as sum_UnitPriceSell'),DB::raw('sum(GTSellPrice) as sum_GTSellPrice'))->where('bom_calculations.QuotationId',$Id)->where('bom_calculations.VersionId',$vId)->where('bom_calculations.DoorType', $doorType)->where('bom_calculations.Category','GeneralLabourCosts')->whereNotNull('bom_calculations.itemId')->groupBy('bom_calculations.Description')->get();

    }else{
        $data = BOMCalculation::join('items','items.itemId','bom_calculations.itemId')->join('item_master','item_master.itemID','items.itemId')->where('bom_calculations.QuotationId',$Id)->where('bom_calculations.itemId', $itemId)->where('bom_calculations.VersionId',$vId)->whereNotNull('bom_calculations.itemId')->select('bom_calculations.*')->get();

        $laborCost = BOMCalculation::join('items','items.itemId','bom_calculations.itemId')->join('item_master','item_master.itemID','items.itemId')->select('bom_calculations.*',DB::raw('count(*) as count'),DB::raw('sum(QuantityOfDoorTypes) as sum'),DB::raw('sum(UnitCost) as sum_UnitCost'),DB::raw('sum(TotalCost) as sum_TotalCost'),DB::raw('sum(UnitPriceSell) as sum_UnitPriceSell'),DB::raw('sum(GTSellPrice) as sum_GTSellPrice'))->where('bom_calculations.QuotationId',$Id)->where('bom_calculations.VersionId',$vId)->where('bom_calculations.itemId', $itemId)->where('bom_calculations.Category','GeneralLabourCosts')->whereNotNull('bom_calculations.itemId')->groupBy('bom_calculations.Description')->get();
    }

    $IntumescentSealData = collect($data)->where("Category", "IntumescentSeal")->groupBy('Description');

    $total=0; $GTSellPrice=0; $Margin = 0;

    foreach($data as $value){
        $arr = ['GlazingBeads', 'Frame', 'Accoustics', 'Architrave', 'Glass', 'GlazingSystem', 'LeafSetBesPoke', 'MachiningCosts'];
        if(in_array($value->Category, $arr)){
            $total += $value->TotalCost;
            $GTSellPrice += $value->GTSellPrice;
            $Margin += $value->Margin;
        }
    }

    foreach($laborCost as $value){
        if($value->Category == 'GeneralLabourCosts'){
            $total += $value->sum_TotalCost;
            $GTSellPrice += $value->sum_GTSellPrice;
            $Margin += $value->Margin;
        }
    }


    $intumescentTotal = 0;
    $intumescentGTSell = 0;
    $intumescentMargin = 0;

    foreach($IntumescentSealData as $IntumescentSealDataGroup){
        foreach($IntumescentSealDataGroup as $IntumescentSealDataGroupRow){
            $intumescentTotal +=  $IntumescentSealDataGroupRow->TotalCost;
            $intumescentGTSell +=  $IntumescentSealDataGroupRow->GTSellPrice;
            $intumescentMargin +=  $IntumescentSealDataGroupRow->Margin;
        }
    }


    $total += $intumescentTotal;
    $GTSellPrice += $intumescentGTSell;
    $Margin += $intumescentMargin;

    $res = [];
    $res['totalCost'] = $total;
    $res['GTSellPrice'] = $GTSellPrice;
    $res['margin'] = $Margin;
    return $res;
}


function filterTimberSpecies($type,$configurationDoor="",$fireRating="",$foursided=""){
    $UserId = CompanyUsers();
    $authdata = Auth::user();
    $lippingSpecies=[];

    $SelectedLippingSpecies = SelectedLippingSpeciesItems::wherein('selected_lipping_species_items.selected_user_id', $UserId)->groupBy("selected_lipping_species_id")->get();
    $SelectedLippingSpeciesIds = array_column($SelectedLippingSpecies->toArray(), "selected_lipping_species_id");

    if($type == "Frame"){
        if($configurationDoor == 2 || $configurationDoor == 3 || $configurationDoor == 4 || $configurationDoor == 5 || $configurationDoor == 6){
            if ($fireRating=="FD30" || $fireRating=="FD30s") {
                $lippingSpecies = GetOptions(['lipping_species.Status' => 1, ["lipping_species.MinValue", ">=", "510"]], "join", "lippingSpecies", "query",[],[["lipping_species.MaxValues", ">=", "510"]]);
            } elseif ($fireRating=="FD60" || $fireRating=="FD60s") {
                $lippingSpecies = GetOptions(['lipping_species.Status' => 1, ["lipping_species.MinValue", ">=", "640"]], "join", "lippingSpecies", "query",[],[["lipping_species.MaxValues", ">=", "640"]]);
            }
        }elseif($configurationDoor == 7){
            if ($foursided == 1) {
                if ($fireRating=="FD30" || $fireRating=="FD30s") {
                    // $lippingSpecies = GetOptions(['lipping_species.Status' => 1, ["lipping_species.MinValue", ">=", "510"]], "join", "lippingSpecies", "query",[],[["lipping_species.MinValue", "<=", "510"], ["lipping_species.MaxValues", ">=", "510"]]);
                    $lippingSpecies = GetOptions(['lipping_species.Status' => 1, ["lipping_species.MinValue", "640"]], "join", "lippingSpecies", "query",[],[["lipping_species.MaxValues", ">=", "640"]]);
                } elseif ($fireRating=="FD60" || $fireRating=="FD60s") {
                    // $lippingSpecies = GetOptions(['lipping_species.Status' => 1, ["lipping_species.MinValue", ">=", "640"]], "join", "lippingSpecies", "query",[],[["lipping_species.MinValue", "<=", "640"], ["lipping_species.MaxValues", ">=", "640"]]);
                    $lippingSpecies = GetOptions(['lipping_species.Status' => 1, ["lipping_species.MinValue",  "640"]], "join", "lippingSpecies", "query",[],[["lipping_species.MaxValues", ">=", "640"]]);
                }
            } elseif ($fireRating=="FD30" || $fireRating=="FD30s") {
                // $lippingSpecies = GetOptions(['lipping_species.Status' => 1, ["lipping_species.MinValue", ">=", "510"]], "join", "lippingSpecies", "query",[],[["lipping_species.MinValue", "<=", "510"], ["lipping_species.MaxValues", ">=", "510"]]);
                $lippingSpecies = GetOptions(['lipping_species.Status' => 1, ["lipping_species.MinValue", ">=", "510"]], "join", "lippingSpecies", "query",[],[["lipping_species.MaxValues", ">=", "510"]]);
            } elseif ($fireRating=="FD60" || $fireRating=="FD60s") {
                // $lippingSpecies = GetOptions(['lipping_species.Status' => 1, ["lipping_species.MinValue", ">=", "640"]], "join", "lippingSpecies", "query",[],[["lipping_species.MinValue", "<=", "640"], ["lipping_species.MaxValues", ">=", "640"]]);
                $lippingSpecies = GetOptions(['lipping_species.Status' => 1, ["lipping_species.MinValue", ">=", "640"]], "join", "lippingSpecies", "query",[],[["lipping_species.MaxValues", ">=", "640"]]);
            }

        }
        elseif($configurationDoor == 1 || $configurationDoor == 8){
            if ($fireRating=="FD30" || $fireRating=="FD30s") {
                $lippingSpecies = GetOptions(['lipping_species.Status' => 1, ["lipping_species.MinValue", ">=", "450"]], "join", "lippingSpecies", "query",[],[["lipping_species.MaxValues", ">=", "450"]]);
            } elseif ($fireRating=="FD60" || $fireRating=="FD60s") {
                $lippingSpecies = GetOptions(['lipping_species.Status' => 1, ["lipping_species.MinValue", ">=", "640"]], "join", "lippingSpecies", "query",[],[["lipping_species.MaxValues", ">=", "640"]]);
            }
        }
    }

    if($type == "Other" && ($fireRating == "FD30" || $fireRating == "FD30s" || $fireRating == "FD60" || $fireRating == "FD60s")){
        $lippingSpecies = GetOptions(['lipping_species.Status' => 1, ["lipping_species.MinValue", ">=", "640"]], "join", "lippingSpecies", "query",[],[["lipping_species.MinValue", "<=", "640"], ["lipping_species.MaxValues", ">=", "640"]]);
    }

    if($fireRating=="NFR" || $type == "Architrave"){
        $lippingSpecies = GetOptions(['lipping_species.Status' => 1], "join", "lippingSpecies", "query");
    }

    if (!($lippingSpecies instanceof \Illuminate\Database\Eloquent\Builder)) {
        $lippingSpecies = collect($lippingSpecies);
    }

    if(in_array($authdata->UserType, [1,4])){
        $lippingSpecies = $lippingSpecies->values(); // Return as collection
    } else {
        $lippingSpecies = $lippingSpecies->whereIn("id",  $SelectedLippingSpeciesIds)->values(); // Filtered
    }


    return $lippingSpecies;
}

function filterOnlyLippingSpecies($type,$configurationDoor="",$fireRating="",$foursided=""){
    $UserId = CompanyUsers();
    $authdata = Auth::user();
    $OnlylippingSpecies=[];

    $SelectedLippingSpecies = SelectedLippingSpeciesItems::wherein('selected_lipping_species_items.selected_user_id', $UserId)->groupBy("selected_lipping_species_id")->get();
    $SelectedLippingSpeciesIds = array_column($SelectedLippingSpecies->toArray(), "selected_lipping_species_id");

    if($type == "Lipping" && ($fireRating == "FD30" || $fireRating == "FD30s" || $fireRating == "FD60" || $fireRating == "FD60s" || $fireRating == "NFR")){
        // $OnlylippingSpecies = GetOptions(['lipping_species.Status' => 1, ["lipping_species.MinValue", ">=", "640"]], "join", "Lipping", "query",[],[]);
        $OnlylippingSpecies = GetOptions(['lipping_species.Status' => 1, ["lipping_species.MinValue", ">=", "640"]], "join", "lippingSpecies", "query",[],[["lipping_species.MaxValues", ">=", "640"]]);
    }

    if(in_array($authdata->UserType, [1,4])){
        $OnlylippingSpecies = $OnlylippingSpecies->get();

    }else{
        $OnlylippingSpecies = $OnlylippingSpecies->whereIn("lipping_species.id",  $SelectedLippingSpeciesIds)->get();
    }

    return $OnlylippingSpecies;
}

function GetlippingSpeciesName($LippingSpeciesNames){
    if (!empty($LippingSpeciesNames)) {
        $LippingSpeciesName = LippingSpecies::where('SpeciesName','LIKE',$LippingSpeciesNames)->first();
        if ($LippingSpeciesName != null) {
            return $LippingSpeciesName->id ?? '';
        }
    }

    return null;
}

///halspan bom calculation
function HalspanBomCalculation($request): void{
    $userIds = CompanyUsers();
    if ($request->fireRating == 'FD30' || $request->fireRating == 'FD30s') {
        $fireRatingVal = 'FD30';
    } elseif ($request->fireRating == 'FD60' || $request->fireRating == 'FD60s') {
        $fireRatingVal = 'FD60';
    } else{
        $fireRatingVal = 'NFR';
    }

    $configurationDoor = configurationDoor($request->issingleconfiguration);
    $fireRatingDoor = fireRatingDoor($fireRatingVal);

    $version_id = QuotationVersion::where('quotation_id', $request->QuotationId)->where('id', $request->version_id)->value('version');
    //glazing beads
    $BOMCalculation = '';
    if(!empty($request->itemID)){
        $BOMCalculation = BOMCalculation::where('QuotationId',$request->QuotationId)->where('itemId',$request->itemID)->where('VersionId',$version_id)->first();
    }

    $SelectedOption = SelectedOption::wherein('SelectedUserId', $userIds)->where('configurableitems',$request->issingleconfiguration)->get();

    if(!empty($BOMCalculation->itemId) && !empty($BOMCalculation->QuotationId)){
        BOMCalculation::where('QuotationId',$request->QuotationId)->where('VersionId',$version_id)->where('itemId',$request->itemID)->delete();
    }

    $BOMCalculation = '';
    if(!empty($request->itemID)){
        $BOMCalculation = BOMCalculation::where('QuotationId',$request->QuotationId)->where('itemId',$request->itemID)->first();
    }

    if(!empty($BOMCalculation->itemId) && !empty($BOMCalculation->QuotationId) && $BOMCalculation->VersionId == 0){
        BOMCalculation::where('QuotationId',$request->QuotationId)->where('itemId',$request->itemID)->delete();
    }

    if(!empty($request->glazingBeadSpecies) && !empty($request->glazingBeads) && !empty($request->glazingBeadsThickness) &&  !empty($request->glazingBeadsHeight) &&  !empty($request->vP1Width) && !empty($request->vP1Height1) && !empty($request->visionPanelQuantity)){
        $selected_lipping_species = LippingSpecies::where('id', $request->glazingBeadSpecies)->get();

        // CHANGED BY @UT 22-02-2024
        if(!empty($request->glazingBeadsThickness)){
            // $glazingBeadsThickness = round($request->glazingBeadsThickness/25.4,1);
            $glazingBeadsThickness = getLippingSpeciesNearTheeknessValue($request->glazingBeadsThickness);

            // CHANGED BY @UT 22-02-2024
            $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->glazingBeadSpecies)->where('selected_thickness','>=',$glazingBeadsThickness)->get()->first();

            if($unitcost){
                $pricePerLM = ($request->glazingBeadsThickness*$request->glazingBeadsHeight*$unitcost->selected_price)/1000000;
                if($request->visionPanelQuantity == '1'){
                    $LMOfGlazing = ($request->vP1Width*2)+($request->vP1Height1*2);
                }elseif($request->visionPanelQuantity == '2'){
                    $LMOfGlazing = ($request->vP1Width*2)+($request->vP1Height1*2)+($request->vP1Width*2)+($request->vP1Height2*2);
                }elseif($request->visionPanelQuantity == '3'){
                    $LMOfGlazing = ($request->vP1Width*2)+($request->vP1Height1*2)+($request->vP1Width*2)+($request->vP1Height2*2)+($request->vP1Width*2)+($request->vP1Height3*2);
                }elseif($request->visionPanelQuantity == '4'){

                    $LMOfGlazing = ($request->vP1Width*2)+($request->vP1Height1*2)+($request->vP1Width*2)+($request->vP1Height2*2)+($request->vP1Width*2)+($request->vP1Height3*2)+($request->vP1Width*2)+($request->vP1Height4*2);
                }elseif($request->visionPanelQuantity == '5'){
                    $LMOfGlazing = ($request->vP1Width*2)+($request->vP1Height1*2)+($request->vP1Width*2)+($request->vP1Height2*2)+($request->vP1Width*2)+($request->vP1Height3*2)+($request->vP1Width*2)+($request->vP1Height4*2)+($request->vP1Width*2)+($request->vP1Height5*2);
                }

                $LMOfGlazingSystem = $LMOfGlazing/1000;
                $unit_cost = $pricePerLM*$LMOfGlazingSystem;
            }else{
                $unit_cost = 0;
            }
        }else{
            $unit_cost = 0;
        }

        $margin = BOMSetting::wherein('UserId',$userIds)->value('margin_for_material');
        $word = str_replace('_', ' ',  $request->glazingBeads);
        $words = explode(" ", $word);
        $acronym = "";

        foreach ($words as $w) {
        $acronym .= $w[0];
        }


        if($request->visionPanelQuantity == '1'){
            $description = $word.'|'.$selected_lipping_species[0]['SpeciesName'].'|Lacquer|'.$acronym.'_'.$request->glazingBeadsThickness.'mm x '.$request->glazingBeadsHeight.'mm|'.$request->vP1Width.'mm x '.$request->vP1Height1.'mm';
        }elseif($request->visionPanelQuantity == '2'){
            $description = $word.'|'.$selected_lipping_species[0]['SpeciesName'].'|Lacquer|'.$acronym.'_'.$request->glazingBeadsThickness.'mm x '.$request->glazingBeadsHeight.'mm|'.$request->vP1Width.'mm x '.$request->vP1Height1.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height2.'mm';
        }elseif($request->visionPanelQuantity == '3'){
            $description = $word.'|'.$selected_lipping_species[0]['SpeciesName'].'|Lacquer|'.$acronym.'_'.$request->glazingBeadsThickness.'mm x '.$request->glazingBeadsHeight.'mm|'.$request->vP1Width.'mm x '.$request->vP1Height1.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height2.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height3.'mm';
           // dd($description);
        }elseif($request->visionPanelQuantity == '4'){
            $description = $word.'|'.$selected_lipping_species[0]['SpeciesName'].'|Lacquer|'.$acronym.'_'.$request->glazingBeadsThickness.'mm x '.$request->glazingBeadsHeight.'mm|'.$request->vP1Width.'mm x '.$request->vP1Height1.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height2.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height3.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height4.'mm';
        }elseif($request->visionPanelQuantity == '5'){
            $description = $word.'|'.$selected_lipping_species[0]['SpeciesName'].'|Lacquer|'.$acronym.'_'.$request->glazingBeadsThickness.'mm x '.$request->glazingBeadsHeight.'mm|'.$request->vP1Width.'mm x '.$request->vP1Height1.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height2.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height3.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height4.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height5.'mm';
        }




        $category = 'GlazingBeads';
        $frame_unit = 'Each';
        $QtyPerDoorType = $request->visionPanelQuantity * 2;
        $total_cost = $unit_cost*$QtyPerDoorType;
        SaveBOMCalculation($request, $category, $frame_unit, $description, $unit_cost,$QtyPerDoorType,$total_cost);
    }

        if ($request->overpanel == 'Fan_Light' && (!empty($request->lippingSpecies) && !empty($request->OpBeadThickness) && !empty($request->opGlazingBeadSpecies))) {
            $selected_lipping_species = LippingSpecies::where('id', $request->opGlazingBeadSpecies)->get()->first();
            // $selected_lipping_species = SelectedLippingSpecies::where('LippingSpeciesId', $request->lippingSpecies)->get()->first();
            $description = '[Fanlight Bead] '.str_replace('_', ' ',  $request->opGlazingBeads).'|'.$selected_lipping_species->SpeciesName.'|Primer|'.$request->OpBeadThickness.' x '.$request->OpBeadHeight.'|'.$request->oPWidth.'mm x '.$request->oPHeigth.'mm';
            $category = 'GlazingBeads';
            $frame_unit = 'Each';
            $OpBeadThickness = getLippingSpeciesNearTheeknessValue($request->OpBeadThickness);
            if(in_array(Auth::user()->UserType, [1,4])){

                $unitcost = LippingSpeciesItems::where('lipping_species_id',$request->lippingSpecies)->where('thickness','>=',$OpBeadThickness)->get()->first();
            }else{
                $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->lippingSpecies)->where('selected_thickness','>=',$OpBeadThickness)->get()->first();

            }

            if(isset($unitcost->id)){

                $unitcost_selected_price = $unitcost->selected_price ?: $unitcost->price;
                $pricePerLM = ($request->OpBeadThickness * $request->OpBeadHeight * $unitcost_selected_price)/1000000;
                $LMOfGlazing = $request->oPWidth + $request->oPWidth + $request->oPHeigth + $request->oPHeigth;
                $LMOfGlazingSystem = $LMOfGlazing/1000;

                $unit_cost = $pricePerLM*$LMOfGlazingSystem;

                if($request->doorsetType == 'DD'){
                    $quantity_of_door_type = 2;
                }elseif($request->doorsetType == 'SD'){
                    $quantity_of_door_type = 1;
                }else{
                    $quantity_of_door_type = 1;
                }

                $total_cost = $unit_cost*$quantity_of_door_type;

                SaveBOMCalculation($request, $category, $frame_unit, $description, $unit_cost,$quantity_of_door_type,$total_cost);

            }
        }

        if ($request->sideLight1 == 'Yes' && (!empty($request->lippingSpecies) && !empty($request->SlBeadThickness) && !empty($request->SideLight1GlazingBeadSpecies))) {
            $selected_lipping_species = LippingSpecies::where('id', $request->SideLight1GlazingBeadSpecies)->get()->first();
            $description = '[Side Screen Bead] '.str_replace('_', ' ',  $request->SideLight1BeadingType).'|'.$selected_lipping_species->SpeciesName.'|Primer|'.$request->SlBeadThickness.' x '.$request->SlBeadHeight.'|'.$request->SL1Width.'mm x '.$request->SL1Height.'mm';
            $category = 'GlazingBeads';
            $frame_unit = 'Each';
            $SlBeadThickness = getLippingSpeciesNearTheeknessValue($request->SlBeadThickness);
            $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->SideLight1GlazingBeadSpecies)->where('selected_thickness','>=',$SlBeadThickness)->first();
            if(!empty($unitcost)){
                $unitcost_selected_price = $unitcost->selected_price ?: $unitcost->price;
            }else{
                $unitcost_selected_price = 0;
            }

            $pricePerLM = ($request->SlBeadThickness * $request->SlBeadHeight * $unitcost_selected_price)/1000000;
            $LMOfGlazing = $request->SL1Width + $request->SL1Width + $request->SL1Height + $request->SL1Height;
            $LMOfGlazingSystem = $LMOfGlazing/1000;
            $unit_cost = $pricePerLM*$LMOfGlazingSystem;
            if($request->sideLight2=='Yes'){
                $unit_cost *= 2;
                $quantity_of_door_type = 2;
            }else{
                $quantity_of_door_type = 1;
            }

            $total_cost = $unit_cost*$quantity_of_door_type;
            SaveBOMCalculation($request, $category, $frame_unit, $description, $unit_cost,$quantity_of_door_type,$total_cost);
        }

        if ($request->sideLight2 == 'Yes' && (!empty($request->lippingSpecies) && !empty($request->SlBeadThickness) && !empty($request->SideLight2GlazingBeadSpecies))) {
            $selected_lipping_species = LippingSpecies::where('id', $request->SideLight2GlazingBeadSpecies)->get()->first();
            $SideLight2GlazingBeadSpecies = ($request->copyOfSideLite1 == "Yes")?$request->SideLight1BeadingType:$request->SideLight2BeadingType;
            $description = '[Side Screen Bead2] '.str_replace('_', ' ',  $SideLight2GlazingBeadSpecies).'|'.$selected_lipping_species->SpeciesName.'|Primer|'.$request->SlBeadThickness.' x '.$request->SlBeadHeight.'|'.$request->SL2Width.'mm x '.$request->SL2Height.'mm';
            $category = 'GlazingBeads';
            $frame_unit = 'Each';
            $SlBeadThickness = getLippingSpeciesNearTheeknessValue($request->SlBeadThickness);
            $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->SideLight2GlazingBeadSpecies)->where('selected_thickness','>=',$SlBeadThickness)->get()->first();
            $unitcost_selected_price = $unitcost->selected_price ?: $unitcost->price;
            $pricePerLM = ($request->SlBeadThickness * $request->SlBeadHeight * $unitcost_selected_price)/1000000;
            $LMOfGlazing = $request->SL2Width + $request->SL2Width + $request->SL2Height + $request->SL2Height;
            $LMOfGlazingSystem = $LMOfGlazing/1000;
            $unit_cost = $pricePerLM*$LMOfGlazingSystem;
            if($request->sideLight1=='Yes'){
                $unit_cost *= 2;
                $quantity_of_door_type = 2;
            }else{
                $quantity_of_door_type = 1;
            }

            $total_cost = $unit_cost*$quantity_of_door_type;
            SaveBOMCalculation($request, $category, $frame_unit, $description, $unit_cost,$quantity_of_door_type,$total_cost);
        }



    //frame
    frameExport($request,$userIds);

    //glass
    GlassExport($request,$userIds,$configurationDoor);

    //glazing system
    glazingExport($request,$userIds,$configurationDoor);

    //Intumescent Seal
    IntumescentExport($request);

    //Ironmongery Material Costs
    IronmongeryCostExport($request,$version_id);

    //Ironmongery & Machining Costs
    MachiningCostExport($request);

    if(!empty($request->issingleconfiguration) && !empty($request->doorLeafFacing) && !empty($request->doorLeafFinish) && !empty($request->lippingSpecies)){
        if($request->issingleconfiguration == 2){
            $doorConfiguration = "Halspan";
        }

        $doorLeafFacing = @collect($SelectedOption)->where("SelectedOptionKey", $request->doorLeafFacing)->first()->SelectedOptionValue;
        $doorLeafFinish = @collect($SelectedOption)->where("SelectedOptionKey", $request->doorLeafFinish)->first()->SelectedOptionValue;
        $doorLeafFinishColor = $request->doorLeafFinishColor;

        $word = str_replace('_', ' ',  $request->doorLeafFacing);

        $lipping_type = str_replace('_', ' ',  $request->lippingType);

        $selected_lipping_species = LippingSpecies::where('id', $request->lippingSpecies)->get()->first();
        if($selected_lipping_species->SpeciesName == ''){
            $selected_lipping_species->SpeciesName = 0;
        }

        if($request->decorativeGroves == 'Yes'){
            $groves = ', V Grooves, '.str_replace('_', ' ',  $request->grooveLocation).', '.$request->numberOfGroove.' No, '.$request->grooveWidth.'mm wide, '.$request->grooveDepth.'mm deep.';
        }else{
            $groves = '.';
        }

        // if($request->doorLeafFacing == 'Kraft_Paper'){
        //     if($request->doorLeafFinish == 'Painted'){
        //         $description = $doorConfiguration.'| '.$word.','.$doorLeafFinish.', '.$doorLeafFinishColor.'|'.$lipping_type.'|'.$request->lippingThickness.'mm | '.$selected_lipping_species->SpeciesName.'|'.$request->leafWidth1.' x '.$request->leafHeightNoOP.' x '.$request->doorThickness.$groves;
        //     }else{
        //         $description = $doorConfiguration.'| '.$word.','.$doorLeafFinish.'|'.$lipping_type.','.$selected_lipping_species->SpeciesName.'|'.$request->lippingThickness.'mm|'.$request->leafWidth1.' x '.$request->leafHeightNoOP.' x '.$request->doorThickness.$groves;
        //     }
        //     //dd($description);

        // }elseif($request->doorLeafFacing == 'Veneer'){
        //     $description = $doorConfiguration.', '.$word.'|'.$doorLeafFinish.'|'.$lipping_type.','.$selected_lipping_species->SpeciesName.'|'.str_replace('_', ' ',  $request->doorLeafFacingValue).', Sheen Level '.$request->SheenLevel.', '.$request->lippingThickness.'mm, '.$request->leafWidth1.' x '.$request->leafHeightNoOP.' x '.$request->doorThickness.$groves;
        // }elseif($request->doorLeafFacing == 'Laminate'){
        //     $description = $doorConfiguration.', '.$word.'|'.$request->doorLeafFinish.'|'.$lipping_type.$selected_lipping_species->SpeciesName. '|'.str_replace('_', ' ',  $request->doorLeafFacingValue).', '.$request->lippingThickness.'mm '.$request->leafWidth1.' x '.$request->leafHeightNoOP.' x '.$request->doorThickness.$groves;
        // }elseif($request->doorLeafFacing == 'PVC'){
        //     $description = $doorConfiguration.', '.$word.'|'.$request->doorLeafFinish.'|'.$lipping_type.', '.$selected_lipping_species->SpeciesName. '|'.str_replace('_', ' ',  $request->doorLeafFacingValue).', '.$request->lippingThickness.'mm '.$request->leafWidth1.' x '.$request->leafHeightNoOP.' x '.$request->doorThickness.$groves;
        // }

        $description = $doorConfiguration.'|'.$lipping_type.'|'.$request->lippingThickness.'mm | '.$selected_lipping_species->SpeciesName.'|'.$request->leafWidth1.' x '.$request->leafHeightNoOP.' x '.$request->doorThickness;

        if($request->doorsetType == 'DD' || $request->doorsetType == 'leaf_and_a_half'){
            $description .= ' and '.$request->leafWidth2.' x '.$request->leafHeightNoOP.' x '.$request->doorThickness;
        }

        $lippingSpecies = getLippingSpeciesNearTheeknessValue($request->lippingThickness);

        $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->lippingSpecies)->where('selected_thickness','>=',$lippingSpecies)->get()->first();

        $door_core_size = getDoorDimensionData($userIds,$request->issingleconfiguration,$fireRatingVal);

        $minCost = null;
        $minCostLeafAndAHalf = null;

        // Initialize variables to track minimum width and height
        $minWidth1 = PHP_INT_MAX;
        $minHeight1 = PHP_INT_MAX;
        $minWidth2 = PHP_INT_MAX;
        $minHeight2 = PHP_INT_MAX;

        if (!empty($door_core_size)) {
            foreach ($door_core_size as $door_core) {
                // For leafWidth1 and leafHeightNoOP
                if ($door_core->selected_mm_width >= $request->leafWidth1 && $door_core->selected_mm_height >= $request->leafHeightNoOP && ($door_core->selected_mm_width <= $minWidth1 && $door_core->selected_mm_height <= $minHeight1)) {
                    $minWidth1 = $door_core->selected_mm_width;
                    $minHeight1 = $door_core->selected_mm_height;
                    // $minCost = $door_core->selected_cost; // Track the cost with the minimum dimensions
                    $pricesArray = json_decode((string) $door_core->custome_door_selected_cost, true);
                    $intumescentLeafType = $request->intumescentLeafType;
                    if (isset($pricesArray[$intumescentLeafType])) {
                        $minCost = $pricesArray[$intumescentLeafType];
                        // $minCost = $door_core->selected_cost;
                    }
                }

                // For leafWidth2 and leafHeightNoOP (Leaf and a half)
                if ($door_core->selected_mm_width >= $request->leafWidth2 && $door_core->selected_mm_height >= $request->leafHeightNoOP && ($door_core->selected_mm_width <= $minWidth2 && $door_core->selected_mm_height <= $minHeight2)) {
                    $minWidth2 = $door_core->selected_mm_width;
                    $minHeight2 = $door_core->selected_mm_height;
                    $pricesArray2 = json_decode((string) $door_core->custome_door_selected_cost, true);
                    $intumescentLeafType2 = $request->intumescentLeafType;
                    if (isset($pricesArray2[$intumescentLeafType2])) {
                        $minCostLeafAndAHalf = $pricesArray2[$intumescentLeafType2];
                        // $minCost = $door_core->selected_cost;
                    }
                }
            }
        }

        $door_core1 = $minCost;
        $door_core2 = $minCostLeafAndAHalf;
        $unitcost1 = (empty($unitcost))?0:$unitcost->selected_price;

        $lm = ($request->leafWidth1 + $request->leafWidth1 + $request->leafHeightNoOP + $request->leafHeightNoOP)/1000;
        $thickness_cost = ($request->lippingThickness * $request->doorThickness * $unitcost1)/1000000;
        $painted_cost = (($request->leafHeightNoOP * 2) + 50)/1000;

        if($request->doorLeafFacing == 'Kraft_Paper'){

            $doorLeafFacing = @collect($SelectedOption)->where("SelectedOptionKey", $request->doorLeafFacing)->first()->SelectedOptionCost;

            $doorLeafFacingCost = $painted_cost * $doorLeafFacing;

            $doorLeafFinish = @collect($SelectedOption)->where("SelectedOptionKey", $request->doorLeafFinish)->where("SelectedOptionSlug", "door_leaf_finish")->first()->SelectedOptionCost;
            $door_cost = ((($request->leafWidth1 / 1000) * ($request->leafHeightNoOP / 1000)) * 2) * $doorLeafFinish;
        }elseif($request->doorLeafFacing == 'Veneer'){

            // $doorLeafFacing = @collect($SelectedOption)->where("SelectedOptionKey", $request->doorLeafFacingValue)->first()->SelectedOptionCost;
            $doorLeafFacing = DoorLeafFacing::join('selected_door_leaf_facing','door_leaf_facing.id','selected_door_leaf_facing.doorLeafFacingId')->wherein('selected_door_leaf_facing.userId', $userIds)->where('door_leaf_facing.'.$configurationDoor,$request->issingleconfiguration)->where('door_leaf_facing.Key',$request->doorLeafFacingValue)->first();
            $selectedPrice = 0;
            if(!empty($doorLeafFacing)){
                $selectedPrice = $doorLeafFacing->selectedPrice;
            }

            $doorLeafFacingCost = $painted_cost * $selectedPrice;

            $doorLeafFinish = @collect($SelectedOption)->where("SelectedOptionKey", $request->doorLeafFinish)->where("SelectedOptionSlug", "door_leaf_finish")->first()->SelectedOptionCost;
            $door_cost = ((($request->leafWidth1 / 1000) * ($request->leafHeightNoOP / 1000)) * 2) * $doorLeafFinish;
        }elseif($request->doorLeafFacing == 'Laminate'){

            // $doorLeafFacing = @collect($SelectedOption)->where("SelectedOptionKey", $request->doorLeafFacingValue)->first()->SelectedOptionCost;
            $doorLeafFacing = DoorLeafFacing::join('selected_door_leaf_facing','door_leaf_facing.id','selected_door_leaf_facing.doorLeafFacingId')->wherein('selected_door_leaf_facing.userId', $userIds)->where('door_leaf_facing.'.$configurationDoor,$request->issingleconfiguration)->where('door_leaf_facing.Key',$request->doorLeafFacingValue)->first();
            $selectedPrice = 0;
            if(!empty($doorLeafFacing)){
                $selectedPrice = $doorLeafFacing->selectedPrice;
            }

            $doorLeafFacingCost = $painted_cost * $selectedPrice;

            $doorLeafFinish = Color::join('selected_color','selected_color.SelectedColorId','color.id')
            ->where('color.DoorLeafFacing',$request->doorLeafFacing)->where('color.ColorName',$request->doorLeafFinish)
            ->where('selected_color.SelectedUserId',Auth::user()->id)
            ->get()->first();
            $door_cost = ((($request->leafWidth1 / 1000) * ($request->leafHeightNoOP / 1000)) * 2) * ($doorLeafFinish->SelectedPrice??0);

        }elseif($request->doorLeafFacing == 'PVC'){

            // $doorLeafFacing = @collect($SelectedOption)->where("SelectedOptionKey", $request->doorLeafFacingValue)->first()->SelectedOptionCost;
            $doorLeafFacing = DoorLeafFacing::join('selected_door_leaf_facing','door_leaf_facing.id','selected_door_leaf_facing.doorLeafFacingId')->wherein('selected_door_leaf_facing.userId', $userIds)->where('door_leaf_facing.'.$configurationDoor,$request->issingleconfiguration)->where('door_leaf_facing.Key',$request->doorLeafFacingValue)->first();
            if(empty($doorLeafFacing->selectedPrice)){
                $doorLeafFacing->selectedPrice = 0;
            }

            $doorLeafFacingCost = $painted_cost * $doorLeafFacing->selectedPrice;

            $doorLeafFinish = Color::join('selected_color','selected_color.SelectedColorId','color.id')
            ->where('color.DoorLeafFacing',$request->doorLeafFacing)->where('color.ColorName',$request->doorLeafFinish)
            ->where('selected_color.SelectedUserId',Auth::user()->id)
            ->get()->first();
            $door_cost = ((($request->leafWidth1 / 1000) * ($request->leafHeightNoOP / 1000)) * 2) * ($doorLeafFinish->SelectedPrice??0);
        }


        $category = 'LeafSetBesPoke';
        $frame_unit = 'Each';
        if($door_core1 == 'NaN'){
            $door_core1 = 0;
        }

        $unit_cost = ($door_core1) + ($lm * $thickness_cost) + ($doorLeafFacingCost + $door_cost);
        if($request->doorsetType == 'leaf_and_a_half'){
            $unit_cost += ($door_core2) + ($lm * $thickness_cost) + ($doorLeafFacingCost + $door_cost);
        }

        // dd($door_core_size,$minWidth1,$minHeight1,$door_core1);
        SaveBOMCalculation($request, $category, $frame_unit, $description, $unit_cost);
    }

    //Accoustics
    AccousticsExport($request);

    // General Labour Costs
    commonGeneralLabourCost($request,$userIds);
}

///flamebreak bom calculation
function FlamebreakBomCalculation($request): void{
    $userIds = CompanyUsers();
    if ($request->fireRating == 'FD30' || $request->fireRating == 'FD30s') {
        $fireRatingVal = 'FD30';
    } elseif ($request->fireRating == 'FD60' || $request->fireRating == 'FD60s') {
        $fireRatingVal = 'FD60';
    } else{
        $fireRatingVal = 'NFR';
    }

    $configurationDoor = configurationDoor($request->issingleconfiguration);
    $fireRatingDoor = fireRatingDoor($fireRatingVal);

    $version_id = QuotationVersion::where('quotation_id', $request->QuotationId)->where('id', $request->version_id)->value('version');
    //glazing beads
    $BOMCalculation = '';
    if(!empty($request->itemID)){
        $BOMCalculation = BOMCalculation::where('QuotationId',$request->QuotationId)->where('itemId',$request->itemID)->where('VersionId',$version_id)->first();
    }

    $SelectedOption = SelectedOption::wherein('SelectedUserId', $userIds)->where('configurableitems',$request->issingleconfiguration)->get();

    if(!empty($BOMCalculation->itemId) && !empty($BOMCalculation->QuotationId)){
        BOMCalculation::where('QuotationId',$request->QuotationId)->where('VersionId',$version_id)->where('itemId',$request->itemID)->delete();
    }

    $BOMCalculation = '';
    if(!empty($request->itemID)){
        $BOMCalculation = BOMCalculation::where('QuotationId',$request->QuotationId)->where('itemId',$request->itemID)->first();
    }

    if(!empty($BOMCalculation->itemId) && !empty($BOMCalculation->QuotationId) && $BOMCalculation->VersionId == 0){
        BOMCalculation::where('QuotationId',$request->QuotationId)->where('itemId',$request->itemID)->delete();
    }

    if(!empty($request->glazingBeadSpecies) && !empty($request->glazingBeads) && !empty($request->glazingBeadsThickness) &&  !empty($request->glazingBeadsHeight) &&  !empty($request->vP1Width) && !empty($request->vP1Height1) && !empty($request->visionPanelQuantity)){
        $selected_lipping_species = LippingSpecies::where('id', $request->glazingBeadSpecies)->get();

        // CHANGED BY @UT 22-02-2024
        if(!empty($request->glazingBeadsThickness)){
            // $glazingBeadsThickness = round($request->glazingBeadsThickness/25.4,1);
            $glazingBeadsThickness = getLippingSpeciesNearTheeknessValue($request->glazingBeadsThickness);

            // CHANGED BY @UT 22-02-2024
            $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->glazingBeadSpecies)->where('selected_thickness','>=',$glazingBeadsThickness)->get()->first();

            if($unitcost){
                $pricePerLM = ($request->glazingBeadsThickness*$request->glazingBeadsHeight*$unitcost->selected_price)/1000000;
                if($request->visionPanelQuantity == '1'){
                    $LMOfGlazing = ($request->vP1Width*2)+($request->vP1Height1*2);
                }elseif($request->visionPanelQuantity == '2'){
                    $LMOfGlazing = ($request->vP1Width*2)+($request->vP1Height1*2)+($request->vP1Width*2)+($request->vP1Height2*2);
                }elseif($request->visionPanelQuantity == '3'){
                    $LMOfGlazing = ($request->vP1Width*2)+($request->vP1Height1*2)+($request->vP1Width*2)+($request->vP1Height2*2)+($request->vP1Width*2)+($request->vP1Height3*2);
                }elseif($request->visionPanelQuantity == '4'){

                    $LMOfGlazing = ($request->vP1Width*2)+($request->vP1Height1*2)+($request->vP1Width*2)+($request->vP1Height2*2)+($request->vP1Width*2)+($request->vP1Height3*2)+($request->vP1Width*2)+($request->vP1Height4*2);
                }elseif($request->visionPanelQuantity == '5'){
                    $LMOfGlazing = ($request->vP1Width*2)+($request->vP1Height1*2)+($request->vP1Width*2)+($request->vP1Height2*2)+($request->vP1Width*2)+($request->vP1Height3*2)+($request->vP1Width*2)+($request->vP1Height4*2)+($request->vP1Width*2)+($request->vP1Height5*2);
                }

                $LMOfGlazingSystem = $LMOfGlazing/1000;
                $unit_cost = $pricePerLM*$LMOfGlazingSystem;
            }else{
                $unit_cost = 0;
            }
        }else{
            $unit_cost = 0;
        }

        $margin = BOMSetting::wherein('UserId',$userIds)->value('margin_for_material');
        $word = str_replace('_', ' ',  $request->glazingBeads);
        $words = explode(" ", $word);
        $acronym = "";

        foreach ($words as $w) {
        $acronym .= $w[0];
        }


        if($request->visionPanelQuantity == '1'){
            $description = $word.'|'.$selected_lipping_species[0]['SpeciesName'].'|Lacquer|'.$acronym.'_'.$request->glazingBeadsThickness.'mm x '.$request->glazingBeadsHeight.'mm|'.$request->vP1Width.'mm x '.$request->vP1Height1.'mm';
        }elseif($request->visionPanelQuantity == '2'){
            $description = $word.'|'.$selected_lipping_species[0]['SpeciesName'].'|Lacquer|'.$acronym.'_'.$request->glazingBeadsThickness.'mm x '.$request->glazingBeadsHeight.'mm|'.$request->vP1Width.'mm x '.$request->vP1Height1.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height2.'mm';
        }elseif($request->visionPanelQuantity == '3'){
            $description = $word.'|'.$selected_lipping_species[0]['SpeciesName'].'|Lacquer|'.$acronym.'_'.$request->glazingBeadsThickness.'mm x '.$request->glazingBeadsHeight.'mm|'.$request->vP1Width.'mm x '.$request->vP1Height1.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height2.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height3.'mm';
           // dd($description);
        }elseif($request->visionPanelQuantity == '4'){
            $description = $word.'|'.$selected_lipping_species[0]['SpeciesName'].'|Lacquer|'.$acronym.'_'.$request->glazingBeadsThickness.'mm x '.$request->glazingBeadsHeight.'mm|'.$request->vP1Width.'mm x '.$request->vP1Height1.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height2.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height3.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height4.'mm';
        }elseif($request->visionPanelQuantity == '5'){
            $description = $word.'|'.$selected_lipping_species[0]['SpeciesName'].'|Lacquer|'.$acronym.'_'.$request->glazingBeadsThickness.'mm x '.$request->glazingBeadsHeight.'mm|'.$request->vP1Width.'mm x '.$request->vP1Height1.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height2.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height3.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height4.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height5.'mm';
        }




        $category = 'GlazingBeads';
        $frame_unit = 'Each';
        $QtyPerDoorType = $request->visionPanelQuantity * 2;
        $total_cost = $unit_cost*$QtyPerDoorType;
        SaveBOMCalculation($request, $category, $frame_unit, $description, $unit_cost,$QtyPerDoorType,$total_cost);
    }

        if ($request->overpanel == 'Fan_Light' && (!empty($request->lippingSpecies) && !empty($request->OpBeadThickness) && !empty($request->opGlazingBeadSpecies))) {
            $selected_lipping_species = LippingSpecies::where('id', $request->opGlazingBeadSpecies)->get()->first();
            // $selected_lipping_species = SelectedLippingSpecies::where('LippingSpeciesId', $request->lippingSpecies)->get()->first();
            $description = '[Fanlight Bead] '.str_replace('_', ' ',  $request->opGlazingBeads).'|'.$selected_lipping_species->SpeciesName.'|Primer|'.$request->OpBeadThickness.' x '.$request->OpBeadHeight.'|'.$request->oPWidth.'mm x '.$request->oPHeigth.'mm';
            $category = 'GlazingBeads';
            $frame_unit = 'Each';
            $OpBeadThickness = getLippingSpeciesNearTheeknessValue($request->OpBeadThickness);
            if(in_array(Auth::user()->UserType, [1,4])){

                $unitcost = LippingSpeciesItems::where('lipping_species_id',$request->lippingSpecies)->where('thickness','>=',$OpBeadThickness)->get()->first();
            }else{
                $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->lippingSpecies)->where('selected_thickness','>=',$OpBeadThickness)->get()->first();

            }

            if(isset($unitcost->id)){

                $unitcost_selected_price = $unitcost->selected_price ?: $unitcost->price;
                $pricePerLM = ($request->OpBeadThickness * $request->OpBeadHeight * $unitcost_selected_price)/1000000;
                $LMOfGlazing = $request->oPWidth + $request->oPWidth + $request->oPHeigth + $request->oPHeigth;
                $LMOfGlazingSystem = $LMOfGlazing/1000;

                $unit_cost = $pricePerLM*$LMOfGlazingSystem;

                if($request->doorsetType == 'DD'){
                    $quantity_of_door_type = 2;
                }elseif($request->doorsetType == 'SD'){
                    $quantity_of_door_type = 1;
                }else{
                    $quantity_of_door_type = 1;
                }

                $total_cost = $unit_cost*$quantity_of_door_type;

                SaveBOMCalculation($request, $category, $frame_unit, $description, $unit_cost,$quantity_of_door_type,$total_cost);

            }
        }

        if ($request->sideLight1 == 'Yes' && (!empty($request->lippingSpecies) && !empty($request->SlBeadThickness) && !empty($request->SideLight1GlazingBeadSpecies))) {
            $selected_lipping_species = LippingSpecies::where('id', $request->SideLight1GlazingBeadSpecies)->get()->first();
            $description = '[Side Screen Bead] '.str_replace('_', ' ',  $request->SideLight1BeadingType).'|'.$selected_lipping_species->SpeciesName.'|Primer|'.$request->SlBeadThickness.' x '.$request->SlBeadHeight.'|'.$request->SL1Width.'mm x '.$request->SL1Height.'mm';
            $category = 'GlazingBeads';
            $frame_unit = 'Each';
            $SlBeadThickness = getLippingSpeciesNearTheeknessValue($request->SlBeadThickness);
            $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->SideLight1GlazingBeadSpecies)->where('selected_thickness','>=',$SlBeadThickness)->first();
            if(!empty($unitcost)){
                $unitcost_selected_price = $unitcost->selected_price ?: $unitcost->price;
            }else{
                $unitcost_selected_price = 0;
            }

            $pricePerLM = ($request->SlBeadThickness * $request->SlBeadHeight * $unitcost_selected_price)/1000000;
            $LMOfGlazing = $request->SL1Width + $request->SL1Width + $request->SL1Height + $request->SL1Height;
            $LMOfGlazingSystem = $LMOfGlazing/1000;
            $unit_cost = $pricePerLM*$LMOfGlazingSystem;
            if($request->sideLight2=='Yes'){
                $unit_cost *= 2;
                $quantity_of_door_type = 2;
            }else{
                $quantity_of_door_type = 1;
            }

            $total_cost = $unit_cost*$quantity_of_door_type;
            SaveBOMCalculation($request, $category, $frame_unit, $description, $unit_cost,$quantity_of_door_type,$total_cost);
        }

        if ($request->sideLight2 == 'Yes' && (!empty($request->lippingSpecies) && !empty($request->SlBeadThickness) && !empty($request->SideLight2GlazingBeadSpecies))) {
            $selected_lipping_species = LippingSpecies::where('id', $request->SideLight2GlazingBeadSpecies)->get()->first();
            $SideLight2GlazingBeadSpecies = ($request->copyOfSideLite1 == "Yes")?$request->SideLight1BeadingType:$request->SideLight2BeadingType;
            $description = '[Side Screen Bead2] '.str_replace('_', ' ',  $SideLight2GlazingBeadSpecies).'|'.$selected_lipping_species->SpeciesName.'|Primer|'.$request->SlBeadThickness.' x '.$request->SlBeadHeight.'|'.$request->SL2Width.'mm x '.$request->SL2Height.'mm';
            $category = 'GlazingBeads';
            $frame_unit = 'Each';
            $SlBeadThickness = getLippingSpeciesNearTheeknessValue($request->SlBeadThickness);
            $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->SideLight2GlazingBeadSpecies)->where('selected_thickness','>=',$SlBeadThickness)->get()->first();
            $unitcost_selected_price = $unitcost->selected_price ?: $unitcost->price;
            $pricePerLM = ($request->SlBeadThickness * $request->SlBeadHeight * $unitcost_selected_price)/1000000;
            $LMOfGlazing = $request->SL2Width + $request->SL2Width + $request->SL2Height + $request->SL2Height;
            $LMOfGlazingSystem = $LMOfGlazing/1000;
            $unit_cost = $pricePerLM*$LMOfGlazingSystem;
            if($request->sideLight1=='Yes'){
                $unit_cost *= 2;
                $quantity_of_door_type = 2;
            }else{
                $quantity_of_door_type = 1;
            }

            $total_cost = $unit_cost*$quantity_of_door_type;
            SaveBOMCalculation($request, $category, $frame_unit, $description, $unit_cost,$quantity_of_door_type,$total_cost);
        }



    //frame
    frameExport($request,$userIds);

    //glass
    GlassExport($request,$userIds,$configurationDoor);

    //glazing system
    glazingExport($request,$userIds,$configurationDoor);

    //Intumescent Seal
    IntumescentExport($request);

    //Ironmongery Material Costs
    IronmongeryCostExport($request,$version_id);

    //Ironmongery & Machining Costs
    MachiningCostExport($request);

    if(!empty($request->issingleconfiguration) && !empty($request->doorLeafFacing) && !empty($request->doorLeafFinish) && !empty($request->lippingSpecies)){
        if($request->issingleconfiguration == 7){
            $doorConfiguration = "Flamebreak";
        }

        $doorLeafFacing = @collect($SelectedOption)->where("SelectedOptionKey", $request->doorLeafFacing)->first()->SelectedOptionValue;
        $doorLeafFinish = @collect($SelectedOption)->where("SelectedOptionKey", $request->doorLeafFinish)->first()->SelectedOptionValue;
        $doorLeafFinishColor = $request->doorLeafFinishColor;

        $word = str_replace('_', ' ',  $request->doorLeafFacing);

        $lipping_type = str_replace('_', ' ',  $request->lippingType);

        $selected_lipping_species = LippingSpecies::where('id', $request->lippingSpecies)->get()->first();
        if($selected_lipping_species->SpeciesName == ''){
            $selected_lipping_species->SpeciesName = 0;
        }

        if($request->decorativeGroves == 'Yes'){
            $groves = ', V Grooves, '.str_replace('_', ' ',  $request->grooveLocation).', '.$request->numberOfGroove.' No, '.$request->grooveWidth.'mm wide, '.$request->grooveDepth.'mm deep.';
        }else{
            $groves = '.';
        }

        // if($request->doorLeafFacing == 'Kraft_Paper'){
        //     if($request->doorLeafFinish == 'Painted'){
        //         $description = $doorConfiguration.'| '.$word.','.$doorLeafFinish.', '.$doorLeafFinishColor.'|'.$lipping_type.'|'.$request->lippingThickness.'mm | '.$selected_lipping_species->SpeciesName.'|'.$request->leafWidth1.' x '.$request->leafHeightNoOP.' x '.$request->doorThickness.$groves;
        //     }else{
        //         $description = $doorConfiguration.'| '.$word.','.$doorLeafFinish.'|'.$lipping_type.','.$selected_lipping_species->SpeciesName.'|'.$request->lippingThickness.'mm|'.$request->leafWidth1.' x '.$request->leafHeightNoOP.' x '.$request->doorThickness.$groves;
        //     }
        //     //dd($description);

        // }elseif($request->doorLeafFacing == 'Veneer'){
        //     $description = $doorConfiguration.', '.$word.'|'.$doorLeafFinish.'|'.$lipping_type.','.$selected_lipping_species->SpeciesName.'|'.str_replace('_', ' ',  $request->doorLeafFacingValue).', Sheen Level '.$request->SheenLevel.', '.$request->lippingThickness.'mm, '.$request->leafWidth1.' x '.$request->leafHeightNoOP.' x '.$request->doorThickness.$groves;
        // }elseif($request->doorLeafFacing == 'Laminate'){
        //     $description = $doorConfiguration.', '.$word.'|'.$request->doorLeafFinish.'|'.$lipping_type.$selected_lipping_species->SpeciesName. '|'.str_replace('_', ' ',  $request->doorLeafFacingValue).', '.$request->lippingThickness.'mm '.$request->leafWidth1.' x '.$request->leafHeightNoOP.' x '.$request->doorThickness.$groves;
        // }elseif($request->doorLeafFacing == 'PVC'){
        //     $description = $doorConfiguration.', '.$word.'|'.$request->doorLeafFinish.'|'.$lipping_type.', '.$selected_lipping_species->SpeciesName. '|'.str_replace('_', ' ',  $request->doorLeafFacingValue).', '.$request->lippingThickness.'mm '.$request->leafWidth1.' x '.$request->leafHeightNoOP.' x '.$request->doorThickness.$groves;
        // }

        $description = $doorConfiguration.'|'.$lipping_type.'|'.$request->lippingThickness.'mm | '.$selected_lipping_species->SpeciesName.'|'.$request->leafWidth1.' x '.$request->leafHeightNoOP.' x '.$request->doorThickness;

        if($request->doorsetType == 'DD' || $request->doorsetType == 'leaf_and_a_half'){
            $description .= ' and '.$request->leafWidth2.' x '.$request->leafHeightNoOP.' x '.$request->doorThickness;
        }

        $lippingSpecies = getLippingSpeciesNearTheeknessValue($request->lippingThickness);

        $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->lippingSpecies)->where('selected_thickness','>=',$lippingSpecies)->get()->first();

        $door_core_size = getDoorDimensionData($userIds,$request->issingleconfiguration,$fireRatingVal);

        $minCost = null;
        $minCostLeafAndAHalf = null;

        // Initialize variables to track minimum width and height
        $minWidth1 = PHP_INT_MAX;
        $minHeight1 = PHP_INT_MAX;
        $minWidth2 = PHP_INT_MAX;
        $minHeight2 = PHP_INT_MAX;

        if (!empty($door_core_size)) {
            foreach ($door_core_size as $door_core) {
                // For leafWidth1 and leafHeightNoOP
                if ($door_core->selected_mm_width >= $request->leafWidth1 && $door_core->selected_mm_height >= $request->leafHeightNoOP && ($door_core->selected_mm_width <= $minWidth1 && $door_core->selected_mm_height <= $minHeight1)) {
                    $minWidth1 = $door_core->selected_mm_width;
                    $minHeight1 = $door_core->selected_mm_height;
                    $pricesArray = json_decode((string) $door_core->custome_door_selected_cost, true);
                    $intumescentLeafType = $request->intumescentLeafType;
                    if (isset($pricesArray[$intumescentLeafType])) {
                        $minCost = $pricesArray[$intumescentLeafType];
                        // $minCost = $door_core->selected_cost;
                    }
                }

                // For leafWidth2 and leafHeightNoOP (Leaf and a half)
                if ($door_core->selected_mm_width >= $request->leafWidth2 && $door_core->selected_mm_height >= $request->leafHeightNoOP && ($door_core->selected_mm_width <= $minWidth2 && $door_core->selected_mm_height <= $minHeight2)) {
                    $minWidth2 = $door_core->selected_mm_width;
                    $minHeight2 = $door_core->selected_mm_height;
                    $pricesArray2 = json_decode((string) $door_core->custome_door_selected_cost, true);
                    $intumescentLeafType2 = $request->intumescentLeafType;
                    if (isset($pricesArray2[$intumescentLeafType2])) {
                        $minCostLeafAndAHalf = $pricesArray2[$intumescentLeafType2];
                        // $minCost = $door_core->selected_cost;
                    }
                }
            }
        }

        $door_core1 = $minCost;
        $door_core2 = $minCostLeafAndAHalf;
        $unitcost1 = (empty($unitcost))?0:$unitcost->selected_price;

        $lm = ($request->leafWidth1 + $request->leafWidth1 + $request->leafHeightNoOP + $request->leafHeightNoOP)/1000;
        $thickness_cost = ($request->lippingThickness * $request->doorThickness * $unitcost1)/1000000;
        $painted_cost = (($request->leafHeightNoOP * 2) + 50)/1000;

        if($request->doorLeafFacing == 'Kraft_Paper'){

            $doorLeafFacing = @collect($SelectedOption)->where("SelectedOptionKey", $request->doorLeafFacing)->first()->SelectedOptionCost;

            $doorLeafFacingCost = $painted_cost * $doorLeafFacing;

            $doorLeafFinish = @collect($SelectedOption)->where("SelectedOptionKey", $request->doorLeafFinish)->where("SelectedOptionSlug", "door_leaf_finish")->first()->SelectedOptionCost;
            $door_cost = ((($request->leafWidth1 / 1000) * ($request->leafHeightNoOP / 1000)) * 2) * $doorLeafFinish;
        }elseif($request->doorLeafFacing == 'Veneer'){

            // $doorLeafFacing = @collect($SelectedOption)->where("SelectedOptionKey", $request->doorLeafFacingValue)->first()->SelectedOptionCost;
            $doorLeafFacing = DoorLeafFacing::join('selected_door_leaf_facing','door_leaf_facing.id','selected_door_leaf_facing.doorLeafFacingId')->wherein('selected_door_leaf_facing.userId', $userIds)->where('door_leaf_facing.'.$configurationDoor,$request->issingleconfiguration)->where('door_leaf_facing.Key',$request->doorLeafFacingValue)->first();
            $selectedPrice = 0;
            if(!empty($doorLeafFacing)){
                $selectedPrice = $doorLeafFacing->selectedPrice;
            }

            $doorLeafFacingCost = $painted_cost * $selectedPrice;

            $doorLeafFinish = @collect($SelectedOption)->where("SelectedOptionKey", $request->doorLeafFinish)->where("SelectedOptionSlug", "door_leaf_finish")->first()->SelectedOptionCost;
            $door_cost = ((($request->leafWidth1 / 1000) * ($request->leafHeightNoOP / 1000)) * 2) * $doorLeafFinish;
        }elseif($request->doorLeafFacing == 'Laminate'){

            // $doorLeafFacing = @collect($SelectedOption)->where("SelectedOptionKey", $request->doorLeafFacingValue)->first()->SelectedOptionCost;
            $doorLeafFacing = DoorLeafFacing::join('selected_door_leaf_facing','door_leaf_facing.id','selected_door_leaf_facing.doorLeafFacingId')->wherein('selected_door_leaf_facing.userId', $userIds)->where('door_leaf_facing.'.$configurationDoor,$request->issingleconfiguration)->where('door_leaf_facing.Key',$request->doorLeafFacingValue)->first();
            $selectedPrice = 0;
            if(!empty($doorLeafFacing)){
                $selectedPrice = $doorLeafFacing->selectedPrice;
            }

            $doorLeafFacingCost = $painted_cost * $selectedPrice;

            $doorLeafFinish = Color::join('selected_color','selected_color.SelectedColorId','color.id')
            ->where('color.DoorLeafFacing',$request->doorLeafFacing)->where('color.ColorName',$request->doorLeafFinish)
            ->where('selected_color.SelectedUserId',Auth::user()->id)
            ->get()->first();
            $door_cost = ((($request->leafWidth1 / 1000) * ($request->leafHeightNoOP / 1000)) * 2) * ($doorLeafFinish->SelectedPrice??0);

        }elseif($request->doorLeafFacing == 'PVC'){

            // $doorLeafFacing = @collect($SelectedOption)->where("SelectedOptionKey", $request->doorLeafFacingValue)->first()->SelectedOptionCost;
            $doorLeafFacing = DoorLeafFacing::join('selected_door_leaf_facing','door_leaf_facing.id','selected_door_leaf_facing.doorLeafFacingId')->wherein('selected_door_leaf_facing.userId', $userIds)->where('door_leaf_facing.'.$configurationDoor,$request->issingleconfiguration)->where('door_leaf_facing.Key',$request->doorLeafFacingValue)->first();
            if(empty($doorLeafFacing->selectedPrice)){
                $doorLeafFacing->selectedPrice = 0;
            }

            $doorLeafFacingCost = $painted_cost * $doorLeafFacing->selectedPrice;

            $doorLeafFinish = Color::join('selected_color','selected_color.SelectedColorId','color.id')
            ->where('color.DoorLeafFacing',$request->doorLeafFacing)->where('color.ColorName',$request->doorLeafFinish)
            ->where('selected_color.SelectedUserId',Auth::user()->id)
            ->get()->first();
            $door_cost = ((($request->leafWidth1 / 1000) * ($request->leafHeightNoOP / 1000)) * 2) * ($doorLeafFinish->SelectedPrice??0);
        }


        $category = 'LeafSetBesPoke';
        $frame_unit = 'Each';
        $unit_cost = ($door_core1) + ($lm * $thickness_cost) + ($doorLeafFacingCost + $door_cost);
        if($request->doorsetType == 'leaf_and_a_half'){
            $unit_cost += ($door_core2) + ($lm * $thickness_cost) + ($doorLeafFacingCost + $door_cost);
        }

        // dd($door_core_size,$minWidth1,$minHeight1,$door_core1);
        SaveBOMCalculation($request, $category, $frame_unit, $description, $unit_cost);
    }

    //Accoustics
    AccousticsExport($request);

    // General Labour Costs
    commonGeneralLabourCost($request,$userIds);
}

function BOMCAlculationExport($id,$version): array{
    $vid = ['selectVersionID'=>0,'selectVersion'=>0];
    if($vid > 0){
        $QV = QuotationVersion::where('id',$version)->first();
        $vid = $QV->version;
    }

    BOMCalculation::where('QuotationId',$id)->whereNull('bom_calculations.itemId')->delete();

    $quotation = Quotation::select('project.*','quotation.*','customers.CstCompanyName','project.ProjectName as projectname')->leftjoin('project','quotation.ProjectId','=','project.id')->leftjoin('customers','customers.UserId','quotation.MainContractorId')->where('quotation.id',$id)->first();
    $bomVersion = BOMCalculation::where('QuotationId',$id)->get()->first();
    if($vid == 0 || $bomVersion->VersionId == 0 || $bomVersion->VersionId == NULL){
        $data = BOMCalculation::join('item_master','item_master.itemID','bom_calculations.itemId')->where('bom_calculations.QuotationId',$id)->whereNotNull('bom_calculations.itemId')->select('bom_calculations.*')->distinct('item_master.itemID')->get();

        $laborCost = BOMCalculation::join('item_master','item_master.itemID','bom_calculations.itemId')->select('bom_calculations.*',DB::raw('count(*) as count'),DB::raw('sum(QuantityOfDoorTypes) as sum'),DB::raw('sum(UnitCost) as sum_UnitCost'),DB::raw('sum(TotalCost) as sum_TotalCost'),DB::raw('sum(UnitPriceSell) as sum_UnitPriceSell'),DB::raw('sum(GTSellPrice) as sum_GTSellPrice'))->where('bom_calculations.QuotationId',$id)->where('bom_calculations.Category','GeneralLabourCosts')->whereNotNull('bom_calculations.itemId')->groupBy('bom_calculations.Description')->distinct('item_master.itemID')->get();
    }else{
        $data = BOMCalculation::join('item_master','item_master.itemID','bom_calculations.itemId')->where('bom_calculations.QuotationId',$id)->where('bom_calculations.VersionId',$vid)->whereNotNull('bom_calculations.itemId')->select('bom_calculations.*')->distinct('item_master.itemID')->get();

        $laborCost = BOMCalculation::join('item_master','item_master.itemID','bom_calculations.itemId')->select('bom_calculations.*',DB::raw('count(*) as count'),DB::raw('sum(QuantityOfDoorTypes) as sum'),DB::raw('sum(UnitCost) as sum_UnitCost'),DB::raw('sum(TotalCost) as sum_TotalCost'),DB::raw('sum(UnitPriceSell) as sum_UnitPriceSell'),DB::raw('sum(GTSellPrice) as sum_GTSellPrice'))->where('bom_calculations.QuotationId',$id)->where('bom_calculations.VersionId',$vid)->where('bom_calculations.Category','GeneralLabourCosts')->whereNotNull('bom_calculations.itemId')->groupBy('bom_calculations.Description')->distinct('item_master.itemID')->get();
    }

    $currency = QuotationCurrency($quotation->Currency);
    $today = Carbon::now()->format('d-m-Y');
    $userName = Auth::user()->FirstName ." ".Auth::user()->LastName;
    $totDoorsetType = NumberOfDoorSets($version,$id);
    $totIronmongerySet = Item::join('item_master','item_master.itemID','items.itemId')->where(['items.QuotationId' => $id,'items.VersionId'=>$version])->whereNotNull('items.IronmongeryID')->count();
    $version = $bomVersion->VersionId;
    $item_details = Item::where(['QuotationId'=>$id])->get();

    $result = ['data'=>$data,'quotation'=>$quotation,'currency'=>$currency,'laborCost'=>$laborCost,'today'=>$today,'userName'=>$userName,'version'=>$version,'totDoorsetType'=>$totDoorsetType,'totIronmongerySet'=>$totIronmongerySet,'item_details'=>$item_details];
    return $result;
}

/**
 * @return \list<\non-empty-list>
 */
function getBomDoorTypeDetails($id, $version, $doorType, $category): array {
    $vid = QuotationVersion::where('id', $version)->value('version') ?? 0;

    BOMCalculation::where('QuotationId', $id)->whereNull('itemId')->delete();

    $data = BOMCalculation::join('item_master', 'item_master.itemID', 'bom_calculations.itemId')
        ->where([
            ['bom_calculations.QuotationId', $id],
            ['bom_calculations.VersionId', $vid],
            ['bom_calculations.DoorType', $doorType],
            ['bom_calculations.Category', $category]
        ])
        ->whereNotNull('bom_calculations.itemId')
        ->select('bom_calculations.*')
        ->distinct('item_master.itemID')
        ->get();

    $result = [];

    $mapping = [
        'Ironmongery&MachiningCosts' => [1, 2, 3, 4, 5, 'LMPerDoorType', 6],
        'GeneralLabourCosts' => [0, 1, 2, 3, 4, 5, 'LMPerDoorType', 'Unit', 'UnitCost', 'TotalCost', 'UnitPriceSell', 'GTSellPrice', 'Margin'],
        'MachiningCosts' => [0, 1, 2, 3, 4, 5, 'LMPerDoorType', 'Unit', 'UnitCost', 'TotalCost', 'UnitPriceSell', 'GTSellPrice', 'Margin'],
        'default' => [0, 1, 2, 3, 4, 5, 'LMPerDoorType', 'QuantityOfDoorTypes', 'Unit', 'UnitCost', 'TotalCost', 'UnitPriceSell', 'GTSellPrice', 'Margin']
    ];

    $fields = $mapping[$category] ?? $mapping['default'];

    foreach ($data as $index => $value) {
        $words = explode("|", (string) $value->Description);
        $row = [];

        foreach ($fields as $field) {
            if (is_int($field)) {
                // Cache $words[$field] to avoid repetitive access
                $wordValue = $words[$field] ?? '';
                if (in_array($category, ['MachiningCosts', 'GeneralLabourCosts'])) {
                    $row[] = in_array($field, [2, 3, 4, 5]) ? round(floatval($wordValue), 2) : $wordValue;
                } else {
                    $row[] = $wordValue;
                }

                // $row[] = $words[$field] ?? '';
            } elseif ($field === 'TotalCost') {
                $row[] = round($value->$field, 2);
            } elseif ($field === 'Margin') {
                $row[] = number_format($value->$field) . '%';
            } else {
                $row[] = $value->$field ?? '';
            }
        }

        // dd($row);

        // Prepend the row index if not 'Ironmongery&MachiningCosts'
        if ($category !== 'Ironmongery&MachiningCosts') {
            array_unshift($row, $index + 1);
        }

        $result[] = $row;
    }

    return $result;
}

function ExportSideScreen($id,$version){
    $vid = ['selectVersionID'=>0,'selectVersion'=>0];
    if($vid > 0){
        $QV = QuotationVersion::where('id',$version)->first();
        $vid = $QV->version;
    }

    $result = SideScreenItem::join('side_screen_item_master', 'side_screen_items.id', 'side_screen_item_master.ScreenId')->where(['side_screen_items.QuotationId' => $id, 'side_screen_items.VersionId' => $version])->select('side_screen_items.*','side_screen_item_master.screenNumber')->get();

    return $result;
}

function lippingName($id){
    $lipping_species = LippingSpecies::where('id', $id)->first();
    return $lipping_species->SpeciesName ?? '';
}

function ironmongeryGetCodeName($id): array{
    $data = explode(',',(string) $id);
    $name = [];
    $price = [];
    foreach($data as $val){
        $a = SelectedIronmongery::select('selected_ironmongery.id','ironmongery_info.Name','ironmongery_info.Code','ironmongery_info.Price')
            ->leftJoin('ironmongery_info','ironmongery_info.id','selected_ironmongery.ironmongery_id')
            ->where([ 'selected_ironmongery.UserId' => Auth::user()->id ,'selected_ironmongery.id'=>$val])->first();
        // $a = IronmongeryInfoModel::where(['UserId'=>Auth::user()->id,'id'=>$val])->orderBy('id','desc')->first();
        if(!empty($a)){
            $name[] = $a->Code.'-'.$a->Name;
            $price[] = $a->Price;
        }
    }

    $result = ['name'=>$name,'price'=>$price];
    return $result;
}

//Sreadec bom calculation
function BomCalculationSeadec($request): void{
    $userIds = CompanyUsers();
    if ($request->fireRating == 'FD30' || $request->fireRating == 'FD30s') {
        $fireRatingVal = 'FD30';
    } elseif ($request->fireRating == 'FD60' || $request->fireRating == 'FD60s') {
        $fireRatingVal = 'FD60';
    } else{
        $fireRatingVal = 'NFR';
    }

    $configurationDoor = configurationDoor($request->issingleconfiguration);
    $fireRatingDoor = fireRatingDoor($fireRatingVal);

    $version_id = QuotationVersion::where('quotation_id', $request->QuotationId)->where('id', $request->version_id)->value('version');
    //glazing beads
    $BOMCalculation = '';
    if(!empty($request->itemID)){
        $BOMCalculation = BOMCalculation::where('QuotationId',$request->QuotationId)->where('itemId',$request->itemID)->where('VersionId',$version_id)->first();
    }

    $SelectedOption = SelectedOption::wherein('SelectedUserId', $userIds)->where('configurableitems',$request->issingleconfiguration)->get();

    if(!empty($BOMCalculation->itemId) && !empty($BOMCalculation->QuotationId)){
        BOMCalculation::where('QuotationId',$request->QuotationId)->where('VersionId',$version_id)->where('itemId',$request->itemID)->delete();
    }

    $BOMCalculation = '';
    if(!empty($request->itemID)){
        $BOMCalculation = BOMCalculation::where('QuotationId',$request->QuotationId)->where('itemId',$request->itemID)->first();
    }

    if(!empty($BOMCalculation->itemId) && !empty($BOMCalculation->QuotationId) && $BOMCalculation->VersionId == 0){
        BOMCalculation::where('QuotationId',$request->QuotationId)->where('itemId',$request->itemID)->delete();
    }

    if(!empty($request->glazingBeadSpecies) && !empty($request->glazingBeads) && !empty($request->glazingBeadsThickness) &&  !empty($request->glazingBeadsHeight) &&  !empty($request->vP1Width) && !empty($request->vP1Height1) && !empty($request->visionPanelQuantity)){
        $selected_lipping_species = LippingSpecies::where('id', $request->glazingBeadSpecies)->get();

        // CHANGED BY @UT 22-02-2024
        if(!empty($request->glazingBeadsThickness)){
            // $glazingBeadsThickness = round($request->glazingBeadsThickness/25.4,1);
            $glazingBeadsThickness = getLippingSpeciesNearTheeknessValue($request->glazingBeadsThickness);

            // CHANGED BY @UT 22-02-2024
            $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->glazingBeadSpecies)->where('selected_thickness','>=',$glazingBeadsThickness)->get()->first();

            if($unitcost){
                $pricePerLM = ($request->glazingBeadsThickness*$request->glazingBeadsHeight*$unitcost->selected_price)/1000000;
                if($request->visionPanelQuantity == '1'){
                    $LMOfGlazing = ($request->vP1Width*2)+($request->vP1Height1*2);
                }elseif($request->visionPanelQuantity == '2'){
                    $LMOfGlazing = ($request->vP1Width*2)+($request->vP1Height1*2)+($request->vP1Width*2)+($request->vP1Height2*2);
                }elseif($request->visionPanelQuantity == '3'){
                    $LMOfGlazing = ($request->vP1Width*2)+($request->vP1Height1*2)+($request->vP1Width*2)+($request->vP1Height2*2)+($request->vP1Width*2)+($request->vP1Height3*2);
                }elseif($request->visionPanelQuantity == '4'){
                    $LMOfGlazing = ($request->vP1Width*2)+($request->vP1Height1*2)+($request->vP1Width*2)+($request->vP1Height2*2)+($request->vP1Width*2)+($request->vP1Height3*2)+($request->vP1Width*2)+($request->vP1Height4*2);
                }elseif($request->visionPanelQuantity == '5'){
                    $LMOfGlazing = ($request->vP1Width*2)+($request->vP1Height1*2)+($request->vP1Width*2)+($request->vP1Height2*2)+($request->vP1Width*2)+($request->vP1Height3*2)+($request->vP1Width*2)+($request->vP1Height4*2)+($request->vP1Width*2)+($request->vP1Height5*2);
                }

                $LMOfGlazingSystem = $LMOfGlazing/1000;
                $unit_cost = $pricePerLM*$LMOfGlazingSystem;
            }else{
                $unit_cost = 0;
            }
        }else{
            $unit_cost = 0;
        }

        $margin = BOMSetting::wherein('UserId',$userIds)->value('margin_for_material');
        $word = str_replace('_', ' ',  $request->glazingBeads);
        $words = explode(" ", $word);
        $acronym = "";

        foreach ($words as $w) {
        $acronym .= $w[0];
        }

        if($request->visionPanelQuantity == '1'){
            $description = $word.'|'.$selected_lipping_species[0]['SpeciesName'].'|Lacquer|'.$acronym.'_'.$request->glazingBeadsThickness.'mm x '.$request->glazingBeadsHeight.'mm|'.$request->vP1Width.'mm x '.$request->vP1Height1.'mm';
        }elseif($request->visionPanelQuantity == '2'){
            $description = $word.'|'.$selected_lipping_species[0]['SpeciesName'].'|Lacquer|'.$acronym.'_'.$request->glazingBeadsThickness.'mm x '.$request->glazingBeadsHeight.'mm|'.$request->vP1Width.'mm x '.$request->vP1Height1.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height2.'mm';
        }elseif($request->visionPanelQuantity == '3'){
            $description = $word.'|'.$selected_lipping_species[0]['SpeciesName'].'|Lacquer|'.$acronym.'_'.$request->glazingBeadsThickness.'mm x '.$request->glazingBeadsHeight.'mm|'.$request->vP1Width.'mm x '.$request->vP1Height1.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height2.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height3.'mm';
        }elseif($request->visionPanelQuantity == '4'){
            $description = $word.'|'.$selected_lipping_species[0]['SpeciesName'].'|Lacquer|'.$acronym.'_'.$request->glazingBeadsThickness.'mm x '.$request->glazingBeadsHeight.'mm|'.$request->vP1Width.'mm x '.$request->vP1Height1.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height2.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height3.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height4.'mm';
        }elseif($request->visionPanelQuantity == '5'){
            $description = $word.'|'.$selected_lipping_species[0]['SpeciesName'].'|Lacquer|'.$acronym.'_'.$request->glazingBeadsThickness.'mm x '.$request->glazingBeadsHeight.'mm|'.$request->vP1Width.'mm x '.$request->vP1Height1.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height2.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height3.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height4.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height5.'mm';
        }

        $category = 'GlazingBeads';
        $frame_unit = 'Each';
        $QtyPerDoorType = $request->visionPanelQuantity * 2;
        $total_cost = $unit_cost*$QtyPerDoorType;
        SaveBOMCalculation($request, $category, $frame_unit, $description, $unit_cost,$QtyPerDoorType,$total_cost);
    }

    if ($request->overpanel == 'Fan_Light' && (!empty($request->lippingSpecies) && !empty($request->OpBeadThickness) && !empty($request->opGlazingBeadSpecies))) {
        $selected_lipping_species = LippingSpecies::where('id', $request->opGlazingBeadSpecies)->get()->first();
        // $selected_lipping_species = SelectedLippingSpecies::where('LippingSpeciesId', $request->lippingSpecies)->get()->first();
        $description = '[Fanlight Bead] '.str_replace('_', ' ',  $request->opGlazingBeads).'|'.$selected_lipping_species->SpeciesName.'|Primer|'.$request->OpBeadThickness.' x '.$request->OpBeadHeight.'|'.$request->oPWidth.'mm x '.$request->oPHeigth.'mm';
        $category = 'GlazingBeads';
        $frame_unit = 'Each';
        $OpBeadThickness = getLippingSpeciesNearTheeknessValue($request->OpBeadThickness);
        if(in_array(Auth::user()->UserType, [1,4])){

            $unitcost = LippingSpeciesItems::where('lipping_species_id',$request->lippingSpecies)->where('thickness','>=',$OpBeadThickness)->get()->first();
        }else{
            $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->lippingSpecies)->where('selected_thickness','>=',$OpBeadThickness)->get()->first();

        }

        if(isset($unitcost->id)){

            $unitcost_selected_price = $unitcost->selected_price ?: $unitcost->price;
            $pricePerLM = ($request->OpBeadThickness * $request->OpBeadHeight * $unitcost_selected_price)/1000000;
            $LMOfGlazing = $request->oPWidth + $request->oPWidth + $request->oPHeigth + $request->oPHeigth;
            $LMOfGlazingSystem = $LMOfGlazing/1000;

            $unit_cost = $pricePerLM*$LMOfGlazingSystem;

            if($request->doorsetType == 'DD'){
                $quantity_of_door_type = 2;
            }elseif($request->doorsetType == 'SD'){
                $quantity_of_door_type = 1;
            }else{
                $quantity_of_door_type = 1;
            }

            $total_cost = $unit_cost*$quantity_of_door_type;

            SaveBOMCalculation($request, $category, $frame_unit, $description, $unit_cost,$quantity_of_door_type,$total_cost);

        }
    }

    if ($request->sideLight1 == 'Yes' && (!empty($request->lippingSpecies) && !empty($request->SlBeadThickness) && !empty($request->SideLight1GlazingBeadSpecies))) {
        $selected_lipping_species = LippingSpecies::where('id', $request->SideLight1GlazingBeadSpecies)->get()->first();
        $description = '[Side Screen Bead] '.str_replace('_', ' ',  $request->SideLight1BeadingType).'|'.$selected_lipping_species->SpeciesName.'|Primer|'.$request->SlBeadThickness.' x '.$request->SlBeadHeight.'|'.$request->SL1Width.'mm x '.$request->SL1Height.'mm';
        $category = 'GlazingBeads';
        $frame_unit = 'Each';
        $SlBeadThickness = getLippingSpeciesNearTheeknessValue($request->SlBeadThickness);
        $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->SideLight1GlazingBeadSpecies)->where('selected_thickness','>=',$SlBeadThickness)->first();
        if(!empty($unitcost)){
            $unitcost_selected_price = $unitcost->selected_price ?: $unitcost->price;
        }else{
            $unitcost_selected_price = 0;
        }

        $pricePerLM = ($request->SlBeadThickness * $request->SlBeadHeight * $unitcost_selected_price)/1000000;
        $LMOfGlazing = $request->SL1Width + $request->SL1Width + $request->SL1Height + $request->SL1Height;
        $LMOfGlazingSystem = $LMOfGlazing/1000;
        $unit_cost = $pricePerLM*$LMOfGlazingSystem;
        if($request->sideLight2=='Yes'){
            $unit_cost *= 2;
            $quantity_of_door_type = 2;
        }else{
            $quantity_of_door_type = 1;
        }

        $total_cost = $unit_cost*$quantity_of_door_type;
        SaveBOMCalculation($request, $category, $frame_unit, $description, $unit_cost,$quantity_of_door_type,$total_cost);
    }

    if ($request->sideLight2 == 'Yes' && (!empty($request->lippingSpecies) && !empty($request->SlBeadThickness) && !empty($request->SideLight2GlazingBeadSpecies))) {
        $selected_lipping_species = LippingSpecies::where('id', $request->SideLight2GlazingBeadSpecies)->get()->first();
        $SideLight2GlazingBeadSpecies = ($request->copyOfSideLite1 == "Yes")?$request->SideLight1BeadingType:$request->SideLight2BeadingType;
        $description = '[Side Screen Bead2] '.str_replace('_', ' ',  $SideLight2GlazingBeadSpecies).'|'.$selected_lipping_species->SpeciesName.'|Primer|'.$request->SlBeadThickness.' x '.$request->SlBeadHeight.'|'.$request->SL2Width.'mm x '.$request->SL2Height.'mm';
        $category = 'GlazingBeads';
        $frame_unit = 'Each';
        $SlBeadThickness = getLippingSpeciesNearTheeknessValue($request->SlBeadThickness);
        $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->SideLight2GlazingBeadSpecies)->where('selected_thickness','>=',$SlBeadThickness)->get()->first();
        $unitcost_selected_price = $unitcost->selected_price ?: $unitcost->price;
        $pricePerLM = ($request->SlBeadThickness * $request->SlBeadHeight * $unitcost_selected_price)/1000000;
        $LMOfGlazing = $request->SL2Width + $request->SL2Width + $request->SL2Height + $request->SL2Height;
        $LMOfGlazingSystem = $LMOfGlazing/1000;
        $unit_cost = $pricePerLM*$LMOfGlazingSystem;
        if($request->sideLight1=='Yes'){
            $unit_cost *= 2;
            $quantity_of_door_type = 2;
        }else{
            $quantity_of_door_type = 1;
        }

        $total_cost = $unit_cost*$quantity_of_door_type;
        SaveBOMCalculation($request, $category, $frame_unit, $description, $unit_cost,$quantity_of_door_type,$total_cost);
    }

    //frame
    frameExport($request,$userIds);

    //glass
    GlassExport($request,$userIds,$configurationDoor);

    //glazing system
    glazingExport($request,$userIds,$configurationDoor);

    //Intumescent Seal
    IntumescentExport($request);

    //Ironmongery Material Costs
    IronmongeryCostExport($request,$version_id);

    //Ironmongery & Machining Costs
    MachiningCostExport($request);

    //Leaf Set BesPoke
    if (!empty($request->issingleconfiguration) && !empty($request->lippingSpecies)) {
        if($request->issingleconfiguration == 5){
            $doorConfiguration = "Seadec";
        }

        $lipping_type = str_replace('_', ' ',  $request->lippingType);
        $selected_lipping_species = LippingSpecies::where('id', $request->lippingSpecies)->get()->first();
        if($selected_lipping_species->SpeciesName == ''){
            $selected_lipping_species->SpeciesName = 0;
        }

        if($request->decorativeGroves == 'Yes'){
            $groves = ', V Grooves, '.str_replace('_', ' ',  $request->grooveLocation).', '.$request->numberOfGroove.' No, '.$request->grooveWidth.'mm wide, '.$request->grooveDepth.'mm deep.';
        }else{
            $groves = '.';
        }

        $description = $doorConfiguration.'| '.$lipping_type.'| '.$request->lippingThickness.'mm /'.$selected_lipping_species->SpeciesName. '| '.$request->leafWidth1.' x '.$request->leafHeightNoOP.' x '.$request->doorThickness;
        $lippingSpecies = getLippingSpeciesNearTheeknessValue($request->lippingThickness);
        $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->lippingSpecies)->where('selected_thickness','>=',$lippingSpecies)->get()->first();
        $door_core_size = getDoorDimensionFirstVicaimaData($userIds,$request->issingleconfiguration,$fireRatingVal,$request->DoorDimensions);
        $door_core1 =  $door_core_size->selected_cost ?? 0;
        $unitcost1 = empty($unitcost) ? 0 : $unitcost->selected_price;
        $lm = 0;
        if ($request->doorsetType == 'DD') {
            if(!empty($request->adjustmentLeafWidth1) && !empty($request->adjustmentLeafHeightNoOP)){
                $lm = (($request->leafWidth1 / 1000)*  2) + (($request->leafHeightNoOP / 1000) * 2);
            }elseif(!empty($request->adjustmentLeafWidth1)){
                $lm = (($request->leafHeightNoOP / 1000) * 2);
            }elseif(!empty($request->adjustmentLeafHeightNoOP)){
                $lm = (($request->leafWidth1 / 1000) * 2);
            }
        } elseif (!empty($request->adjustmentLeafWidth1) && !empty($request->adjustmentLeafHeightNoOP)) {
            $lm = ($request->leafWidth1 / 1000) + ($request->leafHeightNoOP / 1000);
        } elseif(!empty($request->adjustmentLeafWidth1)){
            $lm = ($request->leafHeightNoOP / 1000);
        } elseif(!empty($request->adjustmentLeafHeightNoOP)){
            $lm = ($request->leafWidth1 / 1000);
        }

        $thickness_cost = ($request->lippingThickness *  $request->doorThickness * $unitcost1)/1000000;
        $door_core2 = 0;
        $lm2 = 0;
        $leafandhalfunitcost = 0;
        if($request->doorsetType == 'leaf_and_a_half'){
            $door_core_size2 = getDoorDimensionFirstVicaimaData($userIds,$request->issingleconfiguration,$fireRatingVal,$request->DoorDimensions2);

            $door_core2 =  $door_core_size2->selected_cost ?? 0;

            if(!empty($request->adjustmentLeafWidth2) && !empty($request->adjustmentLeafHeightNoOP)){
                $lm2 = ($request->leafWidth2 / 1000) + ($request->leafHeightNoOP / 1000);
            }elseif(!empty($request->adjustmentLeafWidth2)){
                $lm2 = ($request->leafHeightNoOP / 1000);
            }elseif(!empty($request->adjustmentLeafHeightNoOP)){
                $lm2 = ($request->leafWidth2 / 1000);
            }

            $leafandhalfunitcost = ($door_core2) + ($lm2 * $thickness_cost);

            $description .= ' and '.$request->leafWidth2.' x '.$request->leafHeightNoOP.' x '.$request->doorThickness.'| ' .$request->DoorDimensionsCode.', ' .$request->DoorDimensionsCode2;
        }else{
            $description .= '| ' .$request->DoorDimensionsCode;
        }

        $category = 'LeafSetBesPoke';
        $frame_unit = 'Each';
        $unit_cost = (($door_core1) + ($lm * $thickness_cost)) + $leafandhalfunitcost;
        SaveBOMCalculation($request, $category, $frame_unit, $description, $unit_cost);
    } elseif ($request->issingleconfiguration == 5) {
        $doorConfiguration = "Seadec";
        $lipping_type = str_replace('_', ' ',  $request->lippingType);
        if($request->decorativeGroves == 'Yes'){
            $groves = ', V Grooves, '.str_replace('_', ' ',  $request->grooveLocation).', '.$request->numberOfGroove.' No, '.$request->grooveWidth.'mm wide, '.$request->grooveDepth.'mm deep.';
        }else{
            $groves = '.';
        }

        $description = $doorConfiguration. '| - | - |'.$request->leafWidth1.' x '.$request->leafHeightNoOP.' x '.$request->doorThickness;
        $door_core_size = getDoorDimensionFirstVicaimaData($userIds,$request->issingleconfiguration,$fireRatingVal,$request->DoorDimensions);
        $door_core1 =  $door_core_size->selected_cost ?? 0;
        $door_core2 = 0;
        if($request->doorsetType == 'leaf_and_a_half'){
            $door_core_size2 = getDoorDimensionFirstVicaimaData($userIds,$request->issingleconfiguration,$fireRatingVal,$request->DoorDimensions2);

            $door_core2 =  $door_core_size2->selected_cost ?? 0;

            $description .= ' and '.$request->leafWidth2.' x '.$request->leafHeightNoOP.' x '.$request->doorThickness.'| ' .$request->DoorDimensionsCode.', ' .$request->DoorDimensionsCode2;
        }else{
            $description .= '| ' .$request->DoorDimensionsCode;
        }

        $category = 'LeafSetBesPoke';
        $frame_unit = 'Each';
        $unit_cost = $door_core1 + $door_core2;
        SaveBOMCalculation($request, $category, $frame_unit, $description, $unit_cost);
    }


    //Accoustics
    AccousticsExport($request);

    // General Labour Costs
    commonGeneralLabourCost($request,$userIds);
}

function BOMUpdate($data, $configurableitems): void{
    $item = new Item();
    $item->itemID = $data->itemId;
    $item->QuotationId = $data->QuotationId;
    $item->version_id = $data->VersionId;
    $item->UserId = Auth::user()->id;
    $item->DoorQuantity = $data->DoorQuantity;
    $item->intumescentLeafType = $data->IntumescentLeafType;
    $item->ScallopedWidth = $data->ScallopedWidth;
    $item->ScallopedHeight = $data->ScallopedHeight;
    $item->DoorDimensionsCode2 = $data->DoorDimensionsCode2;
    //Main Options
    $item->doorType = $data->DoorType;
    $item->fireRating = $data->FireRating;
    $item->doorsetType = $data->DoorsetType;
    $item->swingType = $data->SwingType;
    $item->latchType = $data->LatchType;
    $item->Handing = $data->Handing;
    $item->OpensInwards = $data->OpensInwards;
    $item->COC = $data->COC;
    $item->tollerance = $data->Tollerance;
    $item->undercut = $data->Undercut;
    $item->floorFinish = $data->FloorFinish;
    $item->gap = $data->GAP;
    $item->frameThickness = $data->FrameThickness;
    $item->ironmongerySet = $data->IronmongerySet;
    $item->IronmongeryID = $data->IronmongeryID;
    //Door Dimensions & Door Leaf
    $item->LeafConstruction = $data->LeafConstruction;
    $item->DoorDimensions = $data->DoorDimensions;
    $item->DoorDimensions2 = $data->DoorDimensions2;
    $item->DoorDimensionsCode = $data->DoorDimensionsCode;
    $item->AdjustmentLeafWidth1 = $data->AdjustmentLeafWidth1;
    $item->AdjustmentLeafWidth2 = $data->AdjustmentLeafWidth2;
    $item->AdjustmentLeafHeightNoOP = $data->AdjustmentLeafHeightNoOP;
    $item->hinge1Location = $data->hinge1Location;
    $item->hinge2Location = $data->hinge2Location;
    $item->hinge3Location = $data->hinge3Location;
    $item->hinge4Location = $data->hinge4Location;
    $item->hingeCenterCheck = $data->hingeCenterCheck;
    $item->groovesNumber = $data->groovesNumber;
    $item->GroovesNumberLeaf2 = $data->GroovesNumberLeaf2;
    $item->DoorsetPrice = $data->DoorsetPrice;
    $item->sOWidth = $data->SOWidth;
    $item->sOHeight = $data->SOHeight;
    $item->sODepth = $data->SOWallThick;
    $item->leafWidth1 = $data->LeafWidth1;
    $item->leafWidth2 = $data->LeafWidth2;
    $item->leafHeightNoOP = $data->LeafHeight;
    $item->doorThickness = $data->LeafThickness;
    $item->doorLeafFacing = $data->DoorLeafFacing;
    $item->doorLeafFacingValue = $data->DoorLeafFacingValue;
    $item->doorLeafFinish = $data->DoorLeafFinish;
    $item->doorLeafFinishColor = $data->DoorLeafFinishColor;
    $item->SheenLevel = $data->SheenLevel;
    $item->decorativeGroves = $data->DecorativeGroves;
    $item->grooveLocation = $data->GrooveLocation;
    $item->grooveWidth = $data->GrooveWidth;
    $item->grooveDepth = $data->GrooveDepth;
    $item->maxNumberOfGroove = $data->MaxNumberOfGroove;
    $item->numberOfGroove = $data->NumberOfGroove;
    $item->numberOfVerticalGroove = $data->NumberOfVerticalGroove;
    $item->numberOfHorizontalGroove = $data->NumberOfHorizontalGroove;
    $item->DecorativeGrovesLeaf2 = $data->DecorativeGrovesLeaf2;
    $item->GrooveLocationLeaf2 = $data->GrooveLocationLeaf2;
    $item->IsSameAsDecorativeGroves1 = $data->IsSameAsDecorativeGroves1;
    $item->GrooveWidthLeaf2 = $data->GrooveWidthLeaf2;
    $item->GrooveDepthLeaf2 = $data->GrooveDepthLeaf2;
    $item->MaxNumberOfGrooveLeaf2 = $data->MaxNumberOfGrooveLeaf2;
    $item->NumberOfGrooveLeaf2 = $data->NumberOfGrooveLeaf2;
    $item->NumberOfVerticalGrooveLeaf2 = $data->NumberOfVerticalGrooveLeaf2;
    $item->NumberOfHorizontalGrooveLeaf2 = $data->NumberOfHorizontalGrooveLeaf2;

    //Vision Panel
    $item->leaf1VisionPanel = $data->Leaf1VisionPanel;
    $item->leaf1VisionPanelShape = $data->Leaf1VisionPanelShape;
    $item->visionPanelQuantity = $data->VisionPanelQuantity;
    $item->AreVPsEqualSizes = $data->AreVPsEqualSizesForLeaf1;
    $item->distanceFromTopOfDoor = $data->DistanceFromtopOfDoor;
    $item->distanceFromTheEdgeOfDoor = $data->DistanceFromTheEdgeOfDoor;
    $item->distanceBetweenVPs = $data->DistanceBetweenVPs;
    $item->vP1Width = $data->Leaf1VPWidth;
    $item->vP1Height1 = $data->Leaf1VPHeight1;
    $item->vP1Height2 = $data->Leaf1VPHeight2;
    $item->vP1Height3 = $data->Leaf1VPHeight3;
    $item->vP1Height4 = $data->Leaf1VPHeight4;
    $item->vP1Height5 = $data->Leaf1VPHeight5;
    $item->leaf1VpAreaSizeM2 = $data->Leaf1VPAreaSizem2;
    $item->leaf2VisionPanel = $data->Leaf2VisionPanel;
    $item->vpSameAsLeaf1 = $data->sVPSameAsLeaf1;
    $item->Leaf2VisionPanelQuantity = $data->Leaf2VisionPanelQuantity;
    $item->AreVPsEqualSizesForLeaf2 =  $data->AreVPsEqualSizesForLeaf2;
    $item->distanceFromTopOfDoorforLeaf2 = $data->DistanceFromTopOfDoorForLeaf2;
    $item->distanceFromTheEdgeOfDoorforLeaf2 = $data->DistanceFromTheEdgeOfDoorforLeaf2;
    $item->distanceBetweenVPsforLeaf2 = $data->DistanceBetweenVp;
    $item->vP2Width = $data->Leaf2VPWidth;
    $item->vP2Height1 = $data->Leaf2VPHeight1;
    $item->vP2Height2 = $data->Leaf2VPHeight2;
    $item->vP2Height3 = $data->Leaf2VPHeight3;
    $item->vP2Height4 = $data->Leaf2VPHeight4;
    $item->vP2Height5 = $data->Leaf2VPHeight5;
    $item->lazingIntegrityOrInsulationIntegrity = $data->GlassIntegrity;
    $item->glassType = $data->GlassType;
    $item->glassThickness = $data->GlassThickness;
    $item->glazingSystems = $data->GlazingSystems;
    $item->glazingSystemsThickness = $data->GlazingSystemThickness;
    $item->glazingBeads = $data->GlazingBeads;
    $item->glazingBeadsThickness = $data->GlazingBeadsThickness;
    $item->glazingBeadsWidth = $data->glazingBeadsWidth;
    $item->glazingBeadsHeight = $data->glazingBeadsHeight;
    $item->glazingBeadsFixingDetail = $data->glazingBeadsFixingDetail;
    $item->glazingBeadSpecies = $data->GlazingBeadSpecies;

    //Frame
    $item->frameMaterial = $data->FrameMaterial;
    $item->frameType = $data->FrameType;
    // streboard
    $item->plantonStopWidth = $data->PlantonStopWidth;
    $item->plantonStopHeight = $data->PlantonStopHeight;
    // streboard
    $item->rebatedWidth = $data->RebatedWidth;
    $item->rebatedHeight = $data->RebatedHeight;
    //halspan
    // $item->standardWidth = $data->QuotationId;
    // $item->standardHeight = $data->QuotationId;
    $item->frameWidth = $data->FrameWidth;
    $item->frameHeight = $data->FrameHeight;
    $item->frameDepth = $data->FrameDepth;
    $item->frameFinish = $data->FrameFinish;
    $item->framefinishColor = $data->FrameFinishColor;
    $item->extLiner = $data->ExtLiner;
    $item->frameCostuction = $data->DoorFrameConstruction;
    $item->extLinerValue = $data->ExtLinerValue;
    $item->extLinerSize = $data->extLinerSize;
    $item->extLinerThickness = $data->ExtLinerThickness;
    $item->ironmongerySet = $data->IronmongerySet;
    $item->IronmongeryID = $data->IronmongeryID;
    $item->specialFeatureRefs = $data->SpecialFeatureRefs;

    //Over Panel Section
    $item->overpanel = $data->Overpanel;
    $item->oPWidth = $data->OPWidth;
    $item->oPHeigth = $data->OPHeigth;
    $item->opTransom = $data->OPTransom;
    $item->transomThickness = $data->TransomThickness;
    $item->opGlassIntegrity = $data->opGlassIntegrity;
    $item->opGlassType = $data->OPGlassType;
    //
    $item->opglassThickness = $data->OPGlassThickness;
    $item->opglazingSystems = $data->OPGlazingSystems;
    $item->opglazingSystemsThickness =$data->OPGlazingSystemsThickness;
    //
    $item->opGlazingBeads = $data->OPGlazingBeads;
    //
    $item->opglazingBeadsThickness = $data->OPGlazingBeadsThickness;
    $item->opglazingBeadsHeight = $data->OPGlazingBeadsHeight;     // confusion
    $item->opglazingBeadsFixingDetail = $data->OPGlazingBeadsFixingDetail;
    //
    $item->opGlazingBeadSpecies = $data->OPGlazingBeadSpecies;
    $item->OpBeadThickness = $data->OpBeadThickness;
    $item->OpBeadHeight = $data->OpBeadHeight;

    //Side Light
    $item->sideLight1 = $data->SideLight1;
    $item->sideLight1GlassType = $data->SideLight1GlassType;
    //
    $item->sideLight1GlassThickness = $data->SideLight1GlassThickness;
    $item->sideLight1GlazingSystems = $data->SideLight1GlazingSystems;
    $item->sideLight1GlazingSystemsThickness = $data->SideLight1GlazingSystemsThickness;
    //
    $item->SideLight1BeadingType = $data->BeadingType;
    //
    $item->sideLight1GlazingBeadsThickness = $data->SideLight1GlazingBeadsThickness;
    $item->sideLight1GlazingBeadsWidth = $data->SideLight1GlazingBeadsWidth;
    $item->sideLight1GlazingBeadsFixingDetail = $data->SideLight1GlazingBeadsFixingDetail;
    //
    $item->SideLight1GlazingBeadSpecies = $data->SL1GlazingBeadSpecies;
    $item->SL1Width = $data->SL1Width;
    $item->SL1Height = $data->SL1Height;
    $item->SL1Depth = $data->SL1Depth;
    $item->SL1Transom = $data->SL1Transom;
    $item->sideLight2 = $data->SideLight2;
    $item->copyOfSideLite1 = $data->DoYouWantToCopySameAsSL1;
    $item->SideLight2GlassType = $data->SideLight2GlassType;
    //
    $item->sideLight2GlassThickness = $data->SideLight2GlassThickness;
    $item->sideLight2GlazingSystems = $data->SideLight2GlazingSystems;
    $item->sideLight2GlazingSystemsThickness = $data->SideLight2GlazingSystemsThickness;
    //
    $item->SideLight2BeadingType = $data->SideLight2BeadingType;
    //
    $item->sideLight2GlazingBeadsThickness = $data->SideLight2GlazingBeadsThickness;
    $item->sideLight2GlazingBeadsWidth = $data->SideLight2GlazingBeadsWidth;
    $item->sideLight2GlazingBeadsFixingDetail = $data->SideLight2GlazingBeadsFixingDetail;
    //
    $item->SideLight2GlazingBeadSpecies = $data->SideLight2GlazingBeadSpecies;
    $item->SL2Width = $data->SL2Width;
    $item->SL2Height = $data->SL2Height;
    $item->SL2Depth = $data->SL2Depth;
    $item->SL2Transom = $data->SL2Transom;
    $item->SLtransomHeightFromTop = $data->SLtransomHeightFromTop;
    $item->SLtransomThickness = $data->SLtransomThickness;
    $item->SlBeadThickness = $data->SlBeadThickness;
    $item->SlBeadHeight = $data->SlBeadHeight;

    //Lipping And Intumescent
    $item->lippingType = $data->LippingType;
    $item->lippingThickness = $data->LippingThickness;
    $item->lippingSpecies = $data->LippingSpecies;
    $item->meetingStyle = $data->MeetingStyle;
    $item->scallopedLippingThickness = $data->ScallopedLippingThickness;
    $item->flatLippingThickness = $data->FlatLippingThickness;
    $item->rebatedLippingThickness = $data->RebatedLippingThickness;
    $item->coreWidth1 = $data->CoreWidth1;
    $item->coreWidth2 = $data->CoreWidth2;
    $item->coreHeight = $data->CoreHeight;
    $item->intumescentSealType = $data->IntumescentLeapingSealType;
    $item->intumescentSealLocation = $data->IntumescentLeapingSealLocation;
    $item->intumescentSealColor = $data->IntumescentLeapingSealColor;
    $item->intumescentSealArrangement = $data->IntumescentLeapingSealArrangement;
    $item->intumescentSealMeetingEdges = $data->intumescentSealMeetingEdges;

    //Accoustics
    $item->accoustics = $data->Accoustics;
    $item->rWdBRating = $data->rWdBRating;
    $item->perimeterSeal1 = $data->perimeterSeal1;
    $item->perimeterSeal2 = $data->perimeterSeal2;
    // $item->thresholdSeal1 = $data->thresholdSeal1;
    // $item->thresholdSeal2 = $data->thresholdSeal2;
    $item->accousticsmeetingStiles = $data->AccousticsMeetingStiles;

    //Architrave
    $item->Architrave = $data->Architrave;
    $item->architraveMaterial = $data->ArchitraveMaterial;
    $item->architraveType = $data->ArchitraveType;
    $item->architraveWidth = $data->ArchitraveWidth;
    $item->architraveHeight = $data->ArchitraveHeight;
    $item->architraveFinish = $data->ArchitraveFinish;
    $item->architraveFinishcolor = $data->ArchitraveFinishColor;
    $item->architraveSetQty = $data->ArchitraveSetQty;
    $item->FrameOnOff = $data->FrameOnOff;
    $item->issingleconfiguration = $configurableitems;

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
        '7' => FlamebreakBomCalculation($item),
        // Stredor DOOR
        '8' => StredorBomCalculation($item),
        // STAREBOARD AND ALL
        default => BomCalculation($item),
    };

}

function BomCalculationDeanta($request): void{
    $userIds = CompanyUsers();
    if ($request->fireRating == 'FD30' || $request->fireRating == 'FD30s') {
        $fireRatingVal = 'FD30';
    } elseif ($request->fireRating == 'FD60' || $request->fireRating == 'FD60s') {
        $fireRatingVal = 'FD60';
    } else{
        $fireRatingVal = 'NFR';
    }

    $configurationDoor = configurationDoor($request->issingleconfiguration);
    $fireRatingDoor = fireRatingDoor($fireRatingVal);

    $version_id = QuotationVersion::where('quotation_id', $request->QuotationId)->where('id', $request->version_id)->value('version');
    //glazing beads
    $BOMCalculation = '';
    if(!empty($request->itemID)){
        $BOMCalculation = BOMCalculation::where('QuotationId',$request->QuotationId)->where('itemId',$request->itemID)->where('VersionId',$version_id)->first();
    }

    $SelectedOption = SelectedOption::wherein('SelectedUserId', $userIds)->where('configurableitems',$request->issingleconfiguration)->get();

    if(!empty($BOMCalculation->itemId) && !empty($BOMCalculation->QuotationId)){
        BOMCalculation::where('QuotationId',$request->QuotationId)->where('VersionId',$version_id)->where('itemId',$request->itemID)->delete();
    }

    $BOMCalculation = '';
    if(!empty($request->itemID)){
        $BOMCalculation = BOMCalculation::where('QuotationId',$request->QuotationId)->where('itemId',$request->itemID)->first();
    }

    if(!empty($BOMCalculation->itemId) && !empty($BOMCalculation->QuotationId) && $BOMCalculation->VersionId == 0){
        BOMCalculation::where('QuotationId',$request->QuotationId)->where('itemId',$request->itemID)->delete();
    }

    if(!empty($request->glazingBeadSpecies) && !empty($request->glazingBeads) && !empty($request->glazingBeadsThickness) &&  !empty($request->glazingBeadsHeight) &&  !empty($request->vP1Width) && !empty($request->vP1Height1) && !empty($request->visionPanelQuantity)){
        $selected_lipping_species = LippingSpecies::where('id', $request->glazingBeadSpecies)->get();

        // CHANGED BY @UT 22-02-2024
        if(!empty($request->glazingBeadsThickness)){
            // $glazingBeadsThickness = round($request->glazingBeadsThickness/25.4,1);
            $glazingBeadsThickness = getLippingSpeciesNearTheeknessValue($request->glazingBeadsThickness);

            // CHANGED BY @UT 22-02-2024
            $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->glazingBeadSpecies)->where('selected_thickness','>=',$glazingBeadsThickness)->get()->first();

            if($unitcost){
                $pricePerLM = ($request->glazingBeadsThickness*$request->glazingBeadsHeight*$unitcost->selected_price)/1000000;
                if($request->visionPanelQuantity == '1'){
                    $LMOfGlazing = ($request->vP1Width*2)+($request->vP1Height1*2);
                }elseif($request->visionPanelQuantity == '2'){
                    $LMOfGlazing = ($request->vP1Width*2)+($request->vP1Height1*2)+($request->vP1Width*2)+($request->vP1Height2*2);
                }elseif($request->visionPanelQuantity == '3'){
                    $LMOfGlazing = ($request->vP1Width*2)+($request->vP1Height1*2)+($request->vP1Width*2)+($request->vP1Height2*2)+($request->vP1Width*2)+($request->vP1Height3*2);
                }elseif($request->visionPanelQuantity == '4'){
                    $LMOfGlazing = ($request->vP1Width*2)+($request->vP1Height1*2)+($request->vP1Width*2)+($request->vP1Height2*2)+($request->vP1Width*2)+($request->vP1Height3*2)+($request->vP1Width*2)+($request->vP1Height4*2);
                }elseif($request->visionPanelQuantity == '5'){
                    $LMOfGlazing = ($request->vP1Width*2)+($request->vP1Height1*2)+($request->vP1Width*2)+($request->vP1Height2*2)+($request->vP1Width*2)+($request->vP1Height3*2)+($request->vP1Width*2)+($request->vP1Height4*2)+($request->vP1Width*2)+($request->vP1Height5*2);
                }

                $LMOfGlazingSystem = $LMOfGlazing/1000;
                $unit_cost = $pricePerLM*$LMOfGlazingSystem;
            }else{
                $unit_cost = 0;
            }
        }else{
            $unit_cost = 0;
        }

        $margin = BOMSetting::wherein('UserId',$userIds)->value('margin_for_material');
        $word = str_replace('_', ' ',  $request->glazingBeads);
        $words = explode(" ", $word);
        $acronym = "";

        foreach ($words as $w) {
        $acronym .= $w[0];
        }

        if($request->visionPanelQuantity == '1'){
            $description = $word.'|'.$selected_lipping_species[0]['SpeciesName'].'|Lacquer|'.$acronym.'_'.$request->glazingBeadsThickness.'mm x '.$request->glazingBeadsHeight.'mm|'.$request->vP1Width.'mm x '.$request->vP1Height1.'mm';
        }elseif($request->visionPanelQuantity == '2'){
            $description = $word.'|'.$selected_lipping_species[0]['SpeciesName'].'|Lacquer|'.$acronym.'_'.$request->glazingBeadsThickness.'mm x '.$request->glazingBeadsHeight.'mm|'.$request->vP1Width.'mm x '.$request->vP1Height1.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height2.'mm';
        }elseif($request->visionPanelQuantity == '3'){
            $description = $word.'|'.$selected_lipping_species[0]['SpeciesName'].'|Lacquer|'.$acronym.'_'.$request->glazingBeadsThickness.'mm x '.$request->glazingBeadsHeight.'mm|'.$request->vP1Width.'mm x '.$request->vP1Height1.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height2.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height3.'mm';
        }elseif($request->visionPanelQuantity == '4'){
            $description = $word.'|'.$selected_lipping_species[0]['SpeciesName'].'|Lacquer|'.$acronym.'_'.$request->glazingBeadsThickness.'mm x '.$request->glazingBeadsHeight.'mm|'.$request->vP1Width.'mm x '.$request->vP1Height1.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height2.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height3.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height4.'mm';
        }elseif($request->visionPanelQuantity == '5'){
            $description = $word.'|'.$selected_lipping_species[0]['SpeciesName'].'|Lacquer|'.$acronym.'_'.$request->glazingBeadsThickness.'mm x '.$request->glazingBeadsHeight.'mm|'.$request->vP1Width.'mm x '.$request->vP1Height1.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height2.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height3.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height4.'mm, & '.$request->vP1Width.'mm x '.$request->vP1Height5.'mm';
        }

        $category = 'GlazingBeads';
        $frame_unit = 'Each';
        $QtyPerDoorType = $request->visionPanelQuantity * 2;
        $total_cost = $unit_cost*$QtyPerDoorType;
        SaveBOMCalculation($request, $category, $frame_unit, $description, $unit_cost,$QtyPerDoorType,$total_cost);
    }

    if ($request->overpanel == 'Fan_Light' && (!empty($request->lippingSpecies) && !empty($request->OpBeadThickness) && !empty($request->opGlazingBeadSpecies))) {
        $selected_lipping_species = LippingSpecies::where('id', $request->opGlazingBeadSpecies)->get()->first();
        // $selected_lipping_species = SelectedLippingSpecies::where('LippingSpeciesId', $request->lippingSpecies)->get()->first();
        $description = '[Fanlight Bead] '.str_replace('_', ' ',  $request->opGlazingBeads).'|'.$selected_lipping_species->SpeciesName.'|Primer|'.$request->OpBeadThickness.' x '.$request->OpBeadHeight.'|'.$request->oPWidth.'mm x '.$request->oPHeigth.'mm';
        $category = 'GlazingBeads';
        $frame_unit = 'Each';
        $OpBeadThickness = getLippingSpeciesNearTheeknessValue($request->OpBeadThickness);
        if(in_array(Auth::user()->UserType, [1,4])){

            $unitcost = LippingSpeciesItems::where('lipping_species_id',$request->lippingSpecies)->where('thickness','>=',$OpBeadThickness)->get()->first();
        }else{
            $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->lippingSpecies)->where('selected_thickness','>=',$OpBeadThickness)->get()->first();

        }

        if(isset($unitcost->id)){

            $unitcost_selected_price = $unitcost->selected_price ?: $unitcost->price;
            $pricePerLM = ($request->OpBeadThickness * $request->OpBeadHeight * $unitcost_selected_price)/1000000;
            $LMOfGlazing = $request->oPWidth + $request->oPWidth + $request->oPHeigth + $request->oPHeigth;
            $LMOfGlazingSystem = $LMOfGlazing/1000;

            $unit_cost = $pricePerLM*$LMOfGlazingSystem;

            if($request->doorsetType == 'DD'){
                $quantity_of_door_type = 2;
            }elseif($request->doorsetType == 'SD'){
                $quantity_of_door_type = 1;
            }else{
                $quantity_of_door_type = 1;
            }

            $total_cost = $unit_cost*$quantity_of_door_type;

            SaveBOMCalculation($request, $category, $frame_unit, $description, $unit_cost,$quantity_of_door_type,$total_cost);

        }
    }

    if ($request->sideLight1 == 'Yes' && (!empty($request->lippingSpecies) && !empty($request->SlBeadThickness) && !empty($request->SideLight1GlazingBeadSpecies))) {
        $selected_lipping_species = LippingSpecies::where('id', $request->SideLight1GlazingBeadSpecies)->get()->first();
        $description = '[Side Screen Bead] '.str_replace('_', ' ',  $request->SideLight1BeadingType).'|'.$selected_lipping_species->SpeciesName.'|Primer|'.$request->SlBeadThickness.' x '.$request->SlBeadHeight.'|'.$request->SL1Width.'mm x '.$request->SL1Height.'mm';
        $category = 'GlazingBeads';
        $frame_unit = 'Each';
        $SlBeadThickness = getLippingSpeciesNearTheeknessValue($request->SlBeadThickness);
        $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->SideLight1GlazingBeadSpecies)->where('selected_thickness','>=',$SlBeadThickness)->first();
        if(!empty($unitcost)){
            $unitcost_selected_price = $unitcost->selected_price ?: $unitcost->price;
        }else{
            $unitcost_selected_price = 0;
        }

        $pricePerLM = ($request->SlBeadThickness * $request->SlBeadHeight * $unitcost_selected_price)/1000000;
        $LMOfGlazing = $request->SL1Width + $request->SL1Width + $request->SL1Height + $request->SL1Height;
        $LMOfGlazingSystem = $LMOfGlazing/1000;
        $unit_cost = $pricePerLM*$LMOfGlazingSystem;
        if($request->sideLight2=='Yes'){
            $unit_cost *= 2;
            $quantity_of_door_type = 2;
        }else{
            $quantity_of_door_type = 1;
        }

        $total_cost = $unit_cost*$quantity_of_door_type;
        SaveBOMCalculation($request, $category, $frame_unit, $description, $unit_cost,$quantity_of_door_type,$total_cost);
    }

    if ($request->sideLight2 == 'Yes' && (!empty($request->lippingSpecies) && !empty($request->SlBeadThickness) && !empty($request->SideLight2GlazingBeadSpecies))) {
        $selected_lipping_species = LippingSpecies::where('id', $request->SideLight2GlazingBeadSpecies)->get()->first();
        $SideLight2GlazingBeadSpecies = ($request->copyOfSideLite1 == "Yes")?$request->SideLight1BeadingType:$request->SideLight2BeadingType;
        $description = '[Side Screen Bead2] '.str_replace('_', ' ',  $SideLight2GlazingBeadSpecies).'|'.$selected_lipping_species->SpeciesName.'|Primer|'.$request->SlBeadThickness.' x '.$request->SlBeadHeight.'|'.$request->SL2Width.'mm x '.$request->SL2Height.'mm';
        $category = 'GlazingBeads';
        $frame_unit = 'Each';
        $SlBeadThickness = getLippingSpeciesNearTheeknessValue($request->SlBeadThickness);
        $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->SideLight2GlazingBeadSpecies)->where('selected_thickness','>=',$SlBeadThickness)->get()->first();
        $unitcost_selected_price = $unitcost->selected_price ?: $unitcost->price;
        $pricePerLM = ($request->SlBeadThickness * $request->SlBeadHeight * $unitcost_selected_price)/1000000;
        $LMOfGlazing = $request->SL2Width + $request->SL2Width + $request->SL2Height + $request->SL2Height;
        $LMOfGlazingSystem = $LMOfGlazing/1000;
        $unit_cost = $pricePerLM*$LMOfGlazingSystem;
        if($request->sideLight1=='Yes'){
            $unit_cost *= 2;
            $quantity_of_door_type = 2;
        }else{
            $quantity_of_door_type = 1;
        }

        $total_cost = $unit_cost*$quantity_of_door_type;
        SaveBOMCalculation($request, $category, $frame_unit, $description, $unit_cost,$quantity_of_door_type,$total_cost);
    }

    //frame
    frameExport($request,$userIds);

    //glass
    GlassExport($request,$userIds,$configurationDoor);

    //glazing system
    glazingExport($request,$userIds,$configurationDoor);

    //Intumescent Seal
    IntumescentExport($request);

    //Ironmongery Material Costs
    IronmongeryCostExport($request,$version_id);

    //Ironmongery & Machining Costs
    MachiningCostExport($request);

    //Leaf Set BesPoke
    if (!empty($request->issingleconfiguration) && !empty($request->lippingSpecies)) {
        if($request->issingleconfiguration == 6){
            $doorConfiguration = "Deanta";
        }

        $lipping_type = str_replace('_', ' ',  $request->lippingType);
        $selected_lipping_species = LippingSpecies::where('id', $request->lippingSpecies)->get()->first();
        if($selected_lipping_species->SpeciesName == ''){
            $selected_lipping_species->SpeciesName = 0;
        }

        if($request->decorativeGroves == 'Yes'){
            $groves = ', V Grooves, '.str_replace('_', ' ',  $request->grooveLocation).', '.$request->numberOfGroove.' No, '.$request->grooveWidth.'mm wide, '.$request->grooveDepth.'mm deep.';
        }else{
            $groves = '.';
        }

        $description = $doorConfiguration.'| '.$lipping_type.'| '.$request->lippingThickness.'mm /'.$selected_lipping_species->SpeciesName. '| '.$request->leafWidth1.' x '.$request->leafHeightNoOP.' x '.$request->doorThickness;
        $lippingSpecies = getLippingSpeciesNearTheeknessValue($request->lippingThickness);
        $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->lippingSpecies)->where('selected_thickness','>=',$lippingSpecies)->get()->first();
        $door_core_size = getDoorDimensionFirstVicaimaData($userIds,$request->issingleconfiguration,$fireRatingVal,$request->DoorDimensions);
        $door_core1 =  $door_core_size->selected_cost ?? 0;
        $unitcost1 = empty($unitcost) ? 0 : $unitcost->selected_price;
        $lm = 0;
        if ($request->doorsetType == 'DD') {
            if(!empty($request->adjustmentLeafWidth1) && !empty($request->adjustmentLeafHeightNoOP)){
                $lm = (($request->leafWidth1 / 1000)*  2) + (($request->leafHeightNoOP / 1000) * 2);
            }elseif(!empty($request->adjustmentLeafWidth1)){
                $lm = (($request->leafHeightNoOP / 1000) * 2);
            }elseif(!empty($request->adjustmentLeafHeightNoOP)){
                $lm = (($request->leafWidth1 / 1000) * 2);
            }
        } elseif (!empty($request->adjustmentLeafWidth1) && !empty($request->adjustmentLeafHeightNoOP)) {
            $lm = ($request->leafWidth1 / 1000) + ($request->leafHeightNoOP / 1000);
        } elseif(!empty($request->adjustmentLeafWidth1)){
            $lm = ($request->leafHeightNoOP / 1000);
        } elseif(!empty($request->adjustmentLeafHeightNoOP)){
            $lm = ($request->leafWidth1 / 1000);
        }

        $thickness_cost = ($request->lippingThickness *  $request->doorThickness * $unitcost1)/1000000;
        $door_core2 = 0;
        $lm2 = 0;
        $leafandhalfunitcost = 0;
        if($request->doorsetType == 'leaf_and_a_half'){
            $door_core_size2 = getDoorDimensionFirstVicaimaData($userIds,$request->issingleconfiguration,$fireRatingVal,$request->DoorDimensions2);

            $door_core2 =  $door_core_size2->selected_cost ?? 0;

            if(!empty($request->adjustmentLeafWidth2) && !empty($request->adjustmentLeafHeightNoOP)){
                $lm2 = ($request->leafWidth2 / 1000) + ($request->leafHeightNoOP / 1000);
            }elseif(!empty($request->adjustmentLeafWidth2)){
                $lm2 = ($request->leafHeightNoOP / 1000);
            }elseif(!empty($request->adjustmentLeafHeightNoOP)){
                $lm2 = ($request->leafWidth2 / 1000);
            }

            $leafandhalfunitcost = ($door_core2) + ($lm2 * $thickness_cost);

            $description .= ' and '.$request->leafWidth2.' x '.$request->leafHeightNoOP.' x '.$request->doorThickness.'| ' .$request->DoorDimensionsCode.', ' .$request->DoorDimensionsCode2;
        }else{
            $description .= '| ' .$request->DoorDimensionsCode;
        }

        $category = 'LeafSetBesPoke';
        $frame_unit = 'Each';
        $unit_cost = (floatval($door_core1) + (floatval($lm) * floatval($thickness_cost))) + floatval($leafandhalfunitcost);
        SaveBOMCalculation($request, $category, $frame_unit, $description, $unit_cost);
    } elseif ($request->issingleconfiguration == 6) {
        $doorConfiguration = "Deanta";
        $lipping_type = str_replace('_', ' ',  $request->lippingType);
        if($request->decorativeGroves == 'Yes'){
            $groves = ', V Grooves, '.str_replace('_', ' ',  $request->grooveLocation).', '.$request->numberOfGroove.' No, '.$request->grooveWidth.'mm wide, '.$request->grooveDepth.'mm deep.';
        }else{
            $groves = '.';
        }

        $description = $doorConfiguration. '| - | - |'.$request->leafWidth1.' x '.$request->leafHeightNoOP.' x '.$request->doorThickness;
        $door_core_size = getDoorDimensionFirstVicaimaData($userIds,$request->issingleconfiguration,$fireRatingVal,$request->DoorDimensions);
        $door_core1 = (float) ($door_core_size->selected_cost ?? 0);
        $door_core2 = 0;
        if($request->doorsetType == 'leaf_and_a_half'){
            $door_core_size2 = getDoorDimensionFirstVicaimaData($userIds,$request->issingleconfiguration,$fireRatingVal,$request->DoorDimensions2);

            $door_core2 = (float) ($door_core_size2->selected_cost ?? 0);

            $description .= ' and '.$request->leafWidth2.' x '.$request->leafHeightNoOP.' x '.$request->doorThickness.'| ' .$request->DoorDimensionsCode.', ' .$request->DoorDimensionsCode2;
        }else{
            $description .= '| ' .$request->DoorDimensionsCode;
        }

        $category = 'LeafSetBesPoke';
        $frame_unit = 'Each';
        $unit_cost = $door_core1 + $door_core2;
        SaveBOMCalculation($request, $category, $frame_unit, $description, $unit_cost);
    }


    //Accoustics
    AccousticsExport($request);

    // General Labour Costs
    commonGeneralLabourCost($request,$userIds);
}

function saveScreenBOMCalculation($request,$description,$Qty,$QTYOfScreenType,$unit,$unit_cost,$total_cost,$category): void{
    $userIds = CompanyUsers();

    $version_id = QuotationVersion::where('quotation_id', $request->QuotationId)->where('id', $request->VersionId)->value('version');
    if(empty($version_id)){
        $version_id = 0;
    }

    $currencyPrice = getCurrencyRate($request->QuotationId);

    $items = SideScreenItem::where('QuotationId',$request->QuotationId)->get();

    $marginData = BOMSetting::whereIn('UserId', $userIds)->first();
    $margin = 0;
    if ($marginData) {
        $isMargin = ($marginData->MarginMarkup == 'Margin');
        if ($category == 'GeneralLabourCosts') {
            $margin = $isMargin ? $marginData->margin_for_labour : $marginData->markup_for_labour;
        } else {
            $margin = $isMargin ? $marginData->margin_for_material : $marginData->markup_for_material;
        }
    }else{
        $marginData->MarginMarkup = 'Margin';
    }

    $marginDiscount = discountQuotationValue($request->QuotationId,$request->VersionId);
    if($marginDiscount != 0){
        $margin += $marginDiscount;
    }

    $bom_calculation = new ScreenBOMCalculation();
    $bom_calculation->Category = $category;
    $bom_calculation->Description = $description;
    $bom_calculation->Unit = $unit;
    $bom_calculation->QuotationId = $request->QuotationId;
    $bom_calculation->ScreenId = $request->id;
    $bom_calculation->VersionId = $version_id;
    $bom_calculation->ScreenType =$request->ScreenType;
    $bom_calculation->LMPerDoorType = round($Qty,2);
    $bom_calculation->QuantityOfDoorTypes = $QTYOfScreenType;
    $bom_calculation->UnitCost = round(($unit_cost * $currencyPrice),2);
    $bom_calculation->TotalCost = round(($total_cost * $currencyPrice),2);
    $bom_calculation->UnitPriceSell = round((($unit_cost * $currencyPrice) / (1- ($margin/100))),2);
    $bom_calculation->GTSellPrice = round((($total_cost * $currencyPrice) /(1 - ($margin/100))),2);
    $bom_calculation->Margin = round($margin,2);
    $bom_calculation->MarginMarkup = $marginData->MarginMarkup;
    $bom_calculation->save();
}

function sideScreenBOM($request): void{
    $userIds = CompanyUsers(true);
    $FireRating = $request->FireRating;
    if($FireRating == 'IGU 0-0'){
        $FireRating = '0-0';
    }elseif($FireRating == 'IGU 30-0'){
        $FireRating = '30-0';
    }elseif($FireRating == 'IGU 30-30'){
        $FireRating = '30-30';
    }

    $version_id = QuotationVersion::where('quotation_id', $request->QuotationId)->where('id', $request->VersionId)->value('version');
    //glazing beads
    $ScreenBOMCalculation = '';
    if(!empty($request->id)){
        $ScreenBOMCalculation = ScreenBOMCalculation::where('QuotationId',$request->QuotationId)->where('ScreenId',$request->id)->where('VersionId',$version_id)->first();
    }

    if(!empty($ScreenBOMCalculation->ScreenId) && !empty($ScreenBOMCalculation->QuotationId)){
        ScreenBOMCalculation::where('QuotationId',$request->QuotationId)->where('VersionId',$version_id)->where('ScreenId',$request->id)->delete();
    }

    $ScreenBOMCalculation = '';
    if(!empty($request->id)){
        $ScreenBOMCalculation = ScreenBOMCalculation::where('QuotationId',$request->QuotationId)->where('ScreenId',$request->id)->first();
    }

    if(!empty($ScreenBOMCalculation->ScreenId) && !empty($ScreenBOMCalculation->QuotationId) && $ScreenBOMCalculation->VersionId == 0){
        ScreenBOMCalculation::where('QuotationId',$request->QuotationId)->where('ScreenId',$request->id)->delete();
    }

    $QTYOfScreenType = SideScreenItemMaster::where('ScreenId',$request->id)->get()->count();
    if(empty($QTYOfScreenType)){
        $QTYOfScreenType = 1;
    }

    if(empty($request->TransomQuantity)){
        $request->TransomQuantity = 0;
    }

    if(empty($request->MullionQuantity)){
        $request->MullionQuantity = 0;
    }

    //screen glazing beads
    if(!empty($request->GlazingBeadShape) && !empty($request->GlazingBeadMaterial) && !empty($request->Finish) &&  !empty($request->GlazingBeadWidth) &&  !empty($request->GlazingBeadHeight)){
        $TransomQuantity = $request->TransomQuantity + 1;
        $MullionQuantity = $request->MullionQuantity + 1;
        $alphabet = range('A', 'D'); // For row labels (A, B, C)
        for ($i = 0; $i < $TransomQuantity; $i++) {
            for ($j = 1; $j <= $MullionQuantity; $j++) {

                $ScreenType = $request->ScreenType;
                $glasspane = $alphabet[$i] . $j;
                $GlazingBeadShape = $request->GlazingBeadShape;
                $GlazingBeadMaterial = lippingName($request->GlazingBeadMaterial);
                $Finish = $request->Finish;
                $GlazingBeadWidth = $request->GlazingBeadWidth;
                $GlazingBeadHeight = $request->GlazingBeadHeight;

                // Map glass pane identifiers to their respective width and height properties
                $glassPaneMap = [
                    'A1' => ['width' => 'GlassPane1Width', 'height' => 'GlassPane1Height'],
                    'A2' => ['width' => 'GlassPane2Width', 'height' => 'GlassPane2Height'],
                    'A3' => ['width' => 'GlassPane3Width', 'height' => 'GlassPane3Height'],
                    'A4' => ['width' => 'GlassPane4Width', 'height' => 'GlassPane4Height'],
                    'B1' => ['width' => 'GlassPane5Width', 'height' => 'GlassPane5Height'],
                    'B2' => ['width' => 'GlassPane6Width', 'height' => 'GlassPane6Height'],
                    'B3' => ['width' => 'GlassPane7Width', 'height' => 'GlassPane7Height'],
                    'B4' => ['width' => 'GlassPane8Width', 'height' => 'GlassPane8Height'],
                    'C1' => ['width' => 'GlassPane9Width', 'height' => 'GlassPane9Height'],
                    'C2' => ['width' => 'GlassPane10Width', 'height' => 'GlassPane10Height'],
                    'C3' => ['width' => 'GlassPane11Width', 'height' => 'GlassPane11Height'],
                    'C4' => ['width' => 'GlassPane12Width', 'height' => 'GlassPane12Height'],
                    'D1' => ['width' => 'GlassPane13Width', 'height' => 'GlassPane13Height'],
                    'D2' => ['width' => 'GlassPane14Width', 'height' => 'GlassPane14Height'],
                    'D3' => ['width' => 'GlassPane15Width', 'height' => 'GlassPane15Height'],
                    'D4' => ['width' => 'GlassPane16Width', 'height' => 'GlassPane16Height'],
                    // Add more mappings as needed
                ];

                // Check if the glass pane exists in the map
                if (isset($glassPaneMap[$glasspane])) {
                    $GlassPaneWidth = $request->{$glassPaneMap[$glasspane]['width']};
                    $GlassPaneHeight = $request->{$glassPaneMap[$glasspane]['height']};
                }else {
                    $GlassPaneWidth = 0;
                    $GlassPaneHeight = 0;
                }

                $description = sprintf('%s|%s| %s| %s| %s| %s| %s| %s| %s', $ScreenType, $glasspane, $GlazingBeadShape, $GlazingBeadMaterial, $Finish, $GlazingBeadWidth, $GlazingBeadHeight, $GlassPaneWidth, $GlassPaneHeight);

                $screenQty = 1;
                $Qty = 1;

                $glazingBeadsThickness = getLippingSpeciesNearTheeknessValue($request->GlazingBeadHeight);

                $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->GlazingBeadMaterial)->where('selected_thickness','>=',$glazingBeadsThickness)->get()->first();

                $PricePerLM = ($GlazingBeadWidth * $GlazingBeadHeight * $unitcost->selected_price) / 1000000;

                $LM = (($GlassPaneWidth + $GlassPaneWidth + $GlassPaneHeight + $GlassPaneHeight) * 2)/1000;

                $unit_cost = $PricePerLM * $LM;
                $total_cost = $unit_cost * $QTYOfScreenType;

                saveScreenBOMCalculation($request,$description,$Qty,$QTYOfScreenType,'Each',$unit_cost,$total_cost,'GlazingBeads');
            }
        }
    }

    //frame
    if(!empty($request->ScreenType) && !empty($request->FrameMaterial) && !empty($request->Finish) &&  !empty($request->FrameDepth) &&  !empty($request->FrameWidth) &&  !empty($request->FrameThickness)){
        $screenNumber = $request->screenNumber;
        $ScreenType = $request->ScreenType;
        $FrameMF = lippingName($request->FrameMaterial);
        $Finish = $request->Finish;
        $FrameDimensions = [
            'Head' => $request->FrameWidth . ' x ' . $request->FrameDepth . ' x ' . $request->FrameThickness,
            'Bottom' => $request->FrameWidth . ' x ' . $request->FrameDepth . ' x ' . $request->FrameThickness,
            'Sides' => $request->FrameHeight . ' x ' . $request->FrameDepth . ' x ' . $request->FrameThickness,
        ];
        $Quantities = [
            'Head' => 1,
            'Bottom' => 1,
            'Sides' => 2,
        ];
        $LM = [
            'Head' => $request->FrameWidth/1000,
            'Bottom' => $request->FrameWidth/1000,
            'Sides' => ($request->FrameHeight * 2)/1000,
        ];

        foreach (['Head', 'Bottom', 'Sides'] as $FrameLocation) {
            $Qty = $Quantities[$FrameLocation];
            $screenDim = $FrameDimensions[$FrameLocation];
            $LMData = $LM[$FrameLocation];

            $description = sprintf('%s | %s | %s | %s | %s | %s', $ScreenType, $FrameLocation, $FrameMF, $Finish, $screenDim, $Qty);

            $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->FrameMaterial)->get()->first();

            $unit_cost = ($request->FrameThickness * $request->FrameDepth * $unitcost->selected_price) / 1000000;
            $total_cost = $unit_cost * $LMData * $QTYOfScreenType;

            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Metre',$unit_cost,$total_cost,'Frame');

        }

        if(!empty($request->TransomQuantity) && ($request->TransomQuantity != 0)){
            $TransomQuantity = $request->TransomQuantity;
            for ($i = 1; $i <= $TransomQuantity; $i++) {
                $FrameLocation = 'Transom'.$i;
                $Qty = 1;
                $TransomThickness ='Transom'.$i.'Thickness';
                $screenDim = $request->TransomWidth1.' x '.$request->TransomDepth.' x '.$request->$TransomThickness;
                $FrameMF = lippingName($request->TransomMaterial);

                $LMData = $request->TransomWidth1 / 1000;

                $description = sprintf('%s | %s | %s | %s | %s | %d', $ScreenType, $FrameLocation, $FrameMF, $Finish, $screenDim, $Qty);

                $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->TransomMaterial)->get()->first();

                $unit_cost = ($request->$TransomThickness * $request->TransomDepth * $unitcost->selected_price) / 1000000;
                $total_cost = $unit_cost * $LMData * $QTYOfScreenType;

                saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Metre',$unit_cost,$total_cost,'Frame');
            }
        }

        if(!empty($request->MullionQuantity) && ($request->MullionQuantity != 0)){
            $MullionQuantity = $request->MullionQuantity;
            for ($i = 1; $i <= $MullionQuantity; $i++) {
                $FrameLocation = 'Mullion'.$i;
                $Qty = 1;
                $MullionThickness ='Mullion'.$i.'Thickness';
                $screenDim = $request->MullionHeight1.' x '.$request->FrameDepth.' x '.$request->$MullionThickness;
                $FrameMF = lippingName($request->MullionMaterial);
                $LMData = $request->MullionHeight1 / 1000;

                $description = sprintf('%s | %s | %s | %s | %s | %d', $ScreenType, $FrameLocation, $FrameMF, $Finish, $screenDim, $Qty);

                $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->MullionMaterial)->get()->first();

                $unit_cost = ($request->$MullionThickness * $request->FrameDepth * $unitcost->selected_price) / 1000000;
                $total_cost = $unit_cost * $LMData * $QTYOfScreenType;

                saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Metre',$unit_cost,$total_cost,'Frame');
            }
        }

        if(!empty($request->SubFrameMaterial) && !empty($request->SubFrameBottomThickness)){
            $FrameLocation = 'SubFrame Bottom';
            $FrameMF = lippingName($request->SubFrameMaterial);
            $screenDim = $request->FrameWidth.' x '.$request->FrameDepth.' x '.$request->SubFrameBottomThickness;
            $Qty = 1;

            $LMData = $request->SubFrameBottomWidth / 1000;

            $description = sprintf('%s | %s | %s | %s | %s | %d', $ScreenType, $FrameLocation, $FrameMF, $Finish, $screenDim, $Qty);

            $unitcost = SelectedLippingSpeciesItems::wherein('selected_user_id',$userIds)->where('selected_lipping_species_id',$request->SubFrameMaterial)->get()->first();

            $unit_cost = ($request->FrameThickness * $request->FrameDepth * $unitcost->selected_price) / 1000000;
            $total_cost = $unit_cost * $LMData * $QTYOfScreenType;

            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Metre',$unit_cost,$total_cost,'Frame');
        }
    }

    //side screen Glass
    $TransomQuantity = $request->TransomQuantity + 1;
    $MullionQuantity = $request->MullionQuantity + 1;
    $alphabet = range('A', 'D'); // For row labels (A, B, C)
    for ($i = 0; $i < $TransomQuantity; $i++) {
        for ($j = 1; $j <= $MullionQuantity; $j++) {

            $ScreenType = $request->ScreenType;
            $glasspane = $alphabet[$i] . $j;
            $glassType = $request->SinglePane;
            // Map glass pane identifiers to their respective width and height properties
            $glassPaneMap = [
                'A1' => ['width' => 'GlassPane1Width', 'height' => 'GlassPane1Height'],
                'A2' => ['width' => 'GlassPane2Width', 'height' => 'GlassPane2Height'],
                'A3' => ['width' => 'GlassPane3Width', 'height' => 'GlassPane3Height'],
                'A4' => ['width' => 'GlassPane4Width', 'height' => 'GlassPane4Height'],
                'B1' => ['width' => 'GlassPane5Width', 'height' => 'GlassPane5Height'],
                'B2' => ['width' => 'GlassPane6Width', 'height' => 'GlassPane6Height'],
                'B3' => ['width' => 'GlassPane7Width', 'height' => 'GlassPane7Height'],
                'B4' => ['width' => 'GlassPane8Width', 'height' => 'GlassPane8Height'],
                'C1' => ['width' => 'GlassPane9Width', 'height' => 'GlassPane9Height'],
                'C2' => ['width' => 'GlassPane10Width', 'height' => 'GlassPane10Height'],
                'C3' => ['width' => 'GlassPane11Width', 'height' => 'GlassPane11Height'],
                'C4' => ['width' => 'GlassPane12Width', 'height' => 'GlassPane12Height'],
                'D1' => ['width' => 'GlassPane13Width', 'height' => 'GlassPane13Height'],
                'D2' => ['width' => 'GlassPane14Width', 'height' => 'GlassPane14Height'],
                'D3' => ['width' => 'GlassPane15Width', 'height' => 'GlassPane15Height'],
                'D4' => ['width' => 'GlassPane16Width', 'height' => 'GlassPane16Height'],
                // Add more mappings as needed
            ];

            // Check if the glass pane exists in the map
            if (isset($glassPaneMap[$glasspane])) {
                $GlassPaneWidth = $request->{$glassPaneMap[$glasspane]['width']};
                $GlassPaneHeight = $request->{$glassPaneMap[$glasspane]['height']};
            }else {
                $GlassPaneWidth = 0;
                $GlassPaneHeight = 0;
            }

            $LMData = ($GlassPaneWidth * $GlassPaneHeight) / 1000000;

            $description = sprintf('%s | %s | %s | %s | %s', $ScreenType, $glasspane, $glassType, $GlassPaneWidth, $GlassPaneHeight);

            $unitcost = ScreenGlassType::join('selected_screen_glass','screen_glass_type.id','selected_screen_glass.glass_id')
            ->where(['screen_glass_type.FireRating' => $FireRating,'screen_glass_type.GlassType' => $glassType,'screen_glass_type.status'=>1])
            ->wherein('selected_screen_glass.editBy', $userIds)
            ->select('selected_screen_glass.*')
            ->first();

            $unit_cost = (empty($unitcost))?0:$unitcost->glassSelectedPrice;

            $total_cost = $unit_cost * $LMData * $QTYOfScreenType;

            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Area M2',$unit_cost,$total_cost,'Glass');
        }
    }

    //side screen Glazing System
    if(!empty($request->GlazingSystem)){
        $TransomQuantity = $request->TransomQuantity + 1;
        $MullionQuantity = $request->MullionQuantity + 1;
        $GlazingSystem = $request->GlazingSystem;
        $ScreenType = $request->ScreenType;
        $alphabet = range('A', 'D'); // For row labels (A, B, C)
        $LMData = 0;
        for ($i = 0; $i < $TransomQuantity; $i++) {
            for ($j = 1; $j <= $MullionQuantity; $j++) {
                $glasspane = $alphabet[$i] . $j;
               // Map glass pane identifiers to their respective width and height properties
                $glassPaneMap = [
                    'A1' => ['width' => 'GlassPane1Width', 'height' => 'GlassPane1Height'],
                    'A2' => ['width' => 'GlassPane2Width', 'height' => 'GlassPane2Height'],
                    'A3' => ['width' => 'GlassPane3Width', 'height' => 'GlassPane3Height'],
                    'A4' => ['width' => 'GlassPane4Width', 'height' => 'GlassPane4Height'],
                    'B1' => ['width' => 'GlassPane5Width', 'height' => 'GlassPane5Height'],
                    'B2' => ['width' => 'GlassPane6Width', 'height' => 'GlassPane6Height'],
                    'B3' => ['width' => 'GlassPane7Width', 'height' => 'GlassPane7Height'],
                    'B4' => ['width' => 'GlassPane8Width', 'height' => 'GlassPane8Height'],
                    'C1' => ['width' => 'GlassPane9Width', 'height' => 'GlassPane9Height'],
                    'C2' => ['width' => 'GlassPane10Width', 'height' => 'GlassPane10Height'],
                    'C3' => ['width' => 'GlassPane11Width', 'height' => 'GlassPane11Height'],
                    'C4' => ['width' => 'GlassPane12Width', 'height' => 'GlassPane12Height'],
                    'D1' => ['width' => 'GlassPane13Width', 'height' => 'GlassPane13Height'],
                    'D2' => ['width' => 'GlassPane14Width', 'height' => 'GlassPane14Height'],
                    'D3' => ['width' => 'GlassPane15Width', 'height' => 'GlassPane15Height'],
                    'D4' => ['width' => 'GlassPane16Width', 'height' => 'GlassPane16Height'],
                    // Add more mappings as needed
                ];

                // Check if the glass pane exists in the map
                if (isset($glassPaneMap[$glasspane])) {
                    $GlassPaneWidth = $request->{$glassPaneMap[$glasspane]['width']};
                    $GlassPaneHeight = $request->{$glassPaneMap[$glasspane]['height']};
                }else {
                    $GlassPaneWidth = 0;
                    $GlassPaneHeight = 0;
                }

                $LMData += ($GlassPaneWidth * 4) + ($GlassPaneHeight * 4);
            }
        }

        $LMData /= 1000;

        $GlazingBead = $request->GlazingBeadHeight .' x '. $request->GlazingBeadWidth;

        $description = sprintf('%s | %s | %s', $ScreenType, $GlazingSystem, $GlazingBead);

        $unitcost = ScreenGlazingType::join('selected_screen_glazing','screen_glazing_type.id','selected_screen_glazing.glazing_id')
        ->where(['screen_glazing_type.FireRating' => $FireRating,'screen_glazing_type.GlazingSystem' => $GlazingSystem,'screen_glazing_type.status'=>1])
        ->wherein('selected_screen_glazing.editBy', $userIds)
        ->select('selected_screen_glazing.*','screen_glazing_type.*')
        ->first();

        $unit_cost = (empty($unitcost))?0:$unitcost->glazingSelectedPrice;

        $total_cost = $unit_cost * $LMData * $QTYOfScreenType;

        saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Metre',$unit_cost,$total_cost,'GlazingSystem');
    }


    //GeneralLabourCost
    $BOMSetting = BOMSetting::select('*')->wherein('UserId', $userIds)->first();

    $GeneralLabourCost = BomGeneralLabourCost::with(['genLaborCost','genLaborCost.laborBomSettings'])->select('general_labour_cost.*','selected_generallabourcost.*','selected_generallabourcost.id as generalId','general_labour_cost.id')->join('selected_generallabourcost','selected_generallabourcost.generalLabourCostId','=','general_labour_cost.id')->wherein('general_labour_cost.UserId',$userIds)->orderBy('general_labour_cost.id','desc')->first();
    $LMData = 1;

    if(!empty($GeneralLabourCost)){
        if($GeneralLabourCost->MachiningOfScreenframe == 1){
            $data = getMyLaborCost('MachiningOfScreenframe', $GeneralLabourCost->genLaborCost);
            $description = "SS - Machining of Screen frame |".($GeneralLabourCost->MachiningOfScreenframeManMinutes/ 60)."|".$data->labour_cost_per_man."|".($GeneralLabourCost->MachiningOfScreenframeMachineMinutes / 60) ."|".$data->labour_cost_per_machine;
            $unit_cost = ($GeneralLabourCost->MachiningOfScreenframeManMinutes * ($data->labour_cost_per_man/ 60)) + ($GeneralLabourCost->MachiningOfScreenframeMachineMinutes * ($data->labour_cost_per_machine/ 60));
            $total_cost = $unit_cost * $QTYOfScreenType;
            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Each',$unit_cost,$total_cost,'GeneralLabourCosts');
        }

        if($GeneralLabourCost->MachiningOfGlazingBead == 1){
            $data = getMyLaborCost('MachiningOfGlazingBead', $GeneralLabourCost->genLaborCost);
            $description = "SS - Machining of Glazing Bead |".($GeneralLabourCost->MachiningOfGlazingBeadManMinutes/ 60)."|".$data->labour_cost_per_man."|".($GeneralLabourCost->MachiningOfGlazingBeadMachineMinutes / 60) ."|".$data->labour_cost_per_machine;
            $unit_cost = ($GeneralLabourCost->MachiningOfGlazingBeadManMinutes * ($data->labour_cost_per_man/ 60)) + ($GeneralLabourCost->MachiningOfGlazingBeadMachineMinutes * ($data->labour_cost_per_machine/ 60));
            $total_cost = $unit_cost * $QTYOfScreenType;
            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Each',$unit_cost,$total_cost,'GeneralLabourCosts');
        }

        if (!empty($request->TransomQuantity) && $request->TransomQuantity != 0 && $GeneralLabourCost->MachiningOfTransom == 1) {
            $data = getMyLaborCost('MachiningOfTransom', $GeneralLabourCost->genLaborCost);
            $description = "SS - Machining of Transom |".($GeneralLabourCost->MachiningOfTransomManMinutes/ 60)."|".$data->labour_cost_per_man."|".($GeneralLabourCost->MachiningOfTransomMachineMinutes / 60) ."|".$data->labour_cost_per_machine;
            $unit_cost = ($GeneralLabourCost->MachiningOfTransomManMinutes * ($data->labour_cost_per_man/ 60)) + ($GeneralLabourCost->MachiningOfTransomMachineMinutes * ($data->labour_cost_per_machine/ 60));
            $total_cost = $unit_cost * $QTYOfScreenType;
            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Each',$unit_cost,$total_cost,'GeneralLabourCosts');
        }

        if (!empty($request->SubFrameMaterial) && !empty($request->SubFrameBottomThickness) && $GeneralLabourCost->MachiningOfSubFrame == 1) {
            $data = getMyLaborCost('MachiningOfSubFrame', $GeneralLabourCost->genLaborCost);
            $description = "SS - Machining of Sub Frame |".($GeneralLabourCost->MachiningOfSubFrameManMinutes/ 60)."|".$data->labour_cost_per_man."|".($GeneralLabourCost->MachiningOfSubFrameMachineMinutes / 60) ."|".$data->labour_cost_per_machine;
            $unit_cost = ($GeneralLabourCost->MachiningOfSubFrameManMinutes * ($data->labour_cost_per_man/ 60)) + ($GeneralLabourCost->MachiningOfSubFrameMachineMinutes * ($data->labour_cost_per_machine/ 60));
            $total_cost = $unit_cost * $QTYOfScreenType;
            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Each',$unit_cost,$total_cost,'GeneralLabourCosts');
        }

        if($GeneralLabourCost->CuttingOfScreenframe == 1){
            $data = getMyLaborCost('CuttingOfScreenframe', $GeneralLabourCost->genLaborCost);
            $description = "SS - Cutting Of Screen frame |".($GeneralLabourCost->CuttingOfScreenframeManMinutes/ 60)."|".$data->labour_cost_per_man."|".($GeneralLabourCost->CuttingOfScreenframeMachineMinutes / 60) ."|".$data->labour_cost_per_machine;
            $unit_cost = ($GeneralLabourCost->CuttingOfScreenframeManMinutes * ($data->labour_cost_per_man/ 60)) + ($GeneralLabourCost->CuttingOfScreenframeMachineMinutes * ($data->labour_cost_per_machine/ 60));
            $total_cost = $unit_cost * $QTYOfScreenType;
            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Each',$unit_cost,$total_cost,'GeneralLabourCosts');
        }

        if($GeneralLabourCost->CuttingOfGlazingBead == 1){
            $data = getMyLaborCost('CuttingOfGlazingBead', $GeneralLabourCost->genLaborCost);
            $description = "SS - Cutting Of Glazing Bead |".($GeneralLabourCost->CuttingOfGlazingBeadManMinutes/ 60)."|".$data->labour_cost_per_man."|".($GeneralLabourCost->CuttingOfGlazingBeadMachineMinutes / 60) ."|".$data->labour_cost_per_machine;
            $unit_cost = ($GeneralLabourCost->CuttingOfGlazingBeadManMinutes * ($data->labour_cost_per_man/ 60)) + ($GeneralLabourCost->CuttingOfGlazingBeadMachineMinutes * ($data->labour_cost_per_machine/ 60));
            $total_cost = $unit_cost * $QTYOfScreenType;
            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Each',$unit_cost,$total_cost,'GeneralLabourCosts');
        }

        if (!empty($request->TransomQuantity) && $request->TransomQuantity != 0 && $GeneralLabourCost->CuttingOfTransom == 1) {
            $data = getMyLaborCost('CuttingOfTransom', $GeneralLabourCost->genLaborCost);
            $description = "SS - Cutting Of Transom |".($GeneralLabourCost->CuttingOfTransomManMinutes/ 60)."|".$data->labour_cost_per_man."|".($GeneralLabourCost->CuttingOfTransomMachineMinutes / 60) ."|".$data->labour_cost_per_machine;
            $unit_cost = ($GeneralLabourCost->CuttingOfTransomManMinutes * ($data->labour_cost_per_man/ 60)) + ($GeneralLabourCost->CuttingOfTransomMachineMinutes * ($data->labour_cost_per_machine/ 60));
            $total_cost = $unit_cost * $QTYOfScreenType;
            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Each',$unit_cost,$total_cost,'GeneralLabourCosts');
        }

        if (!empty($request->SubFrameMaterial) && !empty($request->SubFrameBottomThickness) && $GeneralLabourCost->CuttingOfSubFrame == 1) {
            $data = getMyLaborCost('CuttingOfSubFrame', $GeneralLabourCost->genLaborCost);
            $description = "SS - Cutting Of Sub Frame |".($GeneralLabourCost->CuttingOfSubFrameManMinutes/ 60)."|".$data->labour_cost_per_man."|".($GeneralLabourCost->CuttingOfSubFrameMachineMinutes / 60) ."|".$data->labour_cost_per_machine;
            $unit_cost = ($GeneralLabourCost->CuttingOfSubFrameManMinutes * ($data->labour_cost_per_man/ 60)) + ($GeneralLabourCost->CuttingOfSubFrameMachineMinutes * ($data->labour_cost_per_machine/ 60));
            $total_cost = $unit_cost * $QTYOfScreenType;
            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Each',$unit_cost,$total_cost,'GeneralLabourCosts');
        }

        if($GeneralLabourCost->ScreenAssembley == 1){
            $data = getMyLaborCost('ScreenAssembley', $GeneralLabourCost->genLaborCost);
            $description = "SS - Screen Assembley |".($GeneralLabourCost->ScreenAssembleyManMinutes/ 60)."|".$data->labour_cost_per_man."|".($GeneralLabourCost->ScreenAssembleyMachineMinutes / 60) ."|".$data->labour_cost_per_machine;
            $unit_cost = ($GeneralLabourCost->ScreenAssembleyManMinutes * ($data->labour_cost_per_man/ 60)) + ($GeneralLabourCost->ScreenAssembleyMachineMinutes * ($data->labour_cost_per_machine/ 60));
            $total_cost = $unit_cost * $QTYOfScreenType;
            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Each',$unit_cost,$total_cost,'GeneralLabourCosts');
        }

        if (!empty($request->TransomQuantity) && $request->TransomQuantity != 0 && $GeneralLabourCost->TransomAssembley == 1) {
            $data = getMyLaborCost('TransomAssembley', $GeneralLabourCost->genLaborCost);
            $description = "SS - Transom Assembley |".($GeneralLabourCost->TransomAssembleyManMinutes/ 60)."|".$data->labour_cost_per_man."|".($GeneralLabourCost->TransomAssembleyMachineMinutes / 60) ."|".$data->labour_cost_per_machine;
            $unit_cost = ($GeneralLabourCost->TransomAssembleyManMinutes * ($data->labour_cost_per_man/ 60)) + ($GeneralLabourCost->TransomAssembleyMachineMinutes * ($data->labour_cost_per_machine/ 60));
            $total_cost = $unit_cost * $QTYOfScreenType;
            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Each',$unit_cost,$total_cost,'GeneralLabourCosts');
        }

        if (!empty($request->SubFrameMaterial) && !empty($request->SubFrameBottomThickness) && $GeneralLabourCost->SubFrameAssembley == 1) {
            $data = getMyLaborCost('SubFrameAssembley', $GeneralLabourCost->genLaborCost);
            $description = "SS - Sub Frame Assembley |".($GeneralLabourCost->SubFrameAssembleyManMinutes/ 60)."|".$data->labour_cost_per_man."|".($GeneralLabourCost->SubFrameAssembleyMachineMinutes / 60) ."|".$data->labour_cost_per_machine;
            $unit_cost = ($GeneralLabourCost->SubFrameAssembleyManMinutes * ($data->labour_cost_per_man/ 60)) + ($GeneralLabourCost->SubFrameAssembleyMachineMinutes * ($data->labour_cost_per_machine/ 60));
            $total_cost = $unit_cost * $QTYOfScreenType;
            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Each',$unit_cost,$total_cost,'GeneralLabourCosts');
        }

        if($GeneralLabourCost->FittingOfGlass == 1){
            $data = getMyLaborCost('FittingOfGlass', $GeneralLabourCost->genLaborCost);
            $description = "SS -Fitting Of Glass |".($GeneralLabourCost->FittingOfGlassManMinutes/ 60)."|".$data->labour_cost_per_man."|".($GeneralLabourCost->FittingOfGlassMachineMinutes / 60) ."|".$data->labour_cost_per_machine;
            $unit_cost = ($GeneralLabourCost->FittingOfGlassManMinutes * ($data->labour_cost_per_man/ 60)) + ($GeneralLabourCost->FittingOfGlassMachineMinutes * ($data->labour_cost_per_machine/ 60));
            $total_cost = $unit_cost * $QTYOfScreenType;
            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Each',$unit_cost,$total_cost,'GeneralLabourCosts');
        }

        if($GeneralLabourCost->FittingOfGlazingSystem == 1){
            $data = getMyLaborCost('FittingOfGlazingSystem', $GeneralLabourCost->genLaborCost);
            $description = "SS - Fitting Of Glazing System |".($GeneralLabourCost->FittingOfGlazingSystemManMinutes/ 60)."|".$data->labour_cost_per_man."|".($GeneralLabourCost->FittingOfGlazingSystemMachineMinutes / 60) ."|".$data->labour_cost_per_machine;
            $unit_cost = ($GeneralLabourCost->FittingOfGlazingSystemManMinutes * ($data->labour_cost_per_man/ 60)) + ($GeneralLabourCost->FittingOfGlazingSystemMachineMinutes * ($data->labour_cost_per_machine/ 60));
            $total_cost = $unit_cost * $QTYOfScreenType;
            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Each',$unit_cost,$total_cost,'GeneralLabourCosts');
        }

        if($GeneralLabourCost->FittingOfGlazingBead == 1){
            $data = getMyLaborCost('FittingOfGlazingBead', $GeneralLabourCost->genLaborCost);
            $description = "SS - Fitting Of Glazing Bead |".($GeneralLabourCost->FittingOfGlazingBeadManMinutes/ 60)."|".$data->labour_cost_per_man."|".($GeneralLabourCost->FittingOfGlazingBeadMachineMinutes / 60) ."|".$data->labour_cost_per_machine;
            $unit_cost = ($GeneralLabourCost->FittingOfGlazingBeadManMinutes * ($data->labour_cost_per_man/ 60)) + ($GeneralLabourCost->FittingOfGlazingBeadMachineMinutes * ($data->labour_cost_per_machine/ 60));
            $total_cost = $unit_cost * $QTYOfScreenType;
            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Each',$unit_cost,$total_cost,'GeneralLabourCosts');
        }

        if($GeneralLabourCost->SprayFinishOf == 1){
            $data = getMyLaborCost('SprayFinishOf', $GeneralLabourCost->genLaborCost);
            $description = "SS - Spray Finish Of |".($GeneralLabourCost->SprayFinishOfManMinutes/ 60)."|".$data->labour_cost_per_man."|".($GeneralLabourCost->SprayFinishOfMachineMinutes / 60) ."|".$data->labour_cost_per_machine;
            $unit_cost = ($GeneralLabourCost->SprayFinishOfManMinutes * ($data->labour_cost_per_man/ 60)) + ($GeneralLabourCost->SprayFinishOfMachineMinutes * ($data->labour_cost_per_machine/ 60));
            $total_cost = $unit_cost * $QTYOfScreenType;
            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Each',$unit_cost,$total_cost,'GeneralLabourCosts');
        }

        if($GeneralLabourCost->SprayFinishOfScreenframe == 1){
            $data = getMyLaborCost('SprayFinishOfScreenframe', $GeneralLabourCost->genLaborCost);
            $description = "SS - Spray Finish Of Screen frame |".($GeneralLabourCost->SprayFinishOfScreenframeManMinutes/ 60)."|".$data->labour_cost_per_man."|".($GeneralLabourCost->SprayFinishOfScreenframeMachineMinutes / 60) ."|".$data->labour_cost_per_machine;
            $unit_cost = ($GeneralLabourCost->SprayFinishOfScreenframeManMinutes * ($data->labour_cost_per_man/ 60)) + ($GeneralLabourCost->SprayFinishOfScreenframeMachineMinutes * ($data->labour_cost_per_machine/ 60));
            $total_cost = $unit_cost * $QTYOfScreenType;
            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Each',$unit_cost,$total_cost,'GeneralLabourCosts');
        }

        if($GeneralLabourCost->SprayFinishGlazingBead == 1){
            $data = getMyLaborCost('SprayFinishGlazingBead', $GeneralLabourCost->genLaborCost);
            $description = "SS - Spray Finish Glazing Bead |".($GeneralLabourCost->SprayFinishGlazingBeadManMinutes/ 60)."|".$data->labour_cost_per_man."|".($GeneralLabourCost->SprayFinishGlazingBeadMachineMinutes / 60) ."|".$data->labour_cost_per_machine;
            $unit_cost = ($GeneralLabourCost->SprayFinishGlazingBeadManMinutes * ($data->labour_cost_per_man/ 60)) + ($GeneralLabourCost->SprayFinishGlazingBeadMachineMinutes * ($data->labour_cost_per_machine/ 60));
            $total_cost = $unit_cost * $QTYOfScreenType;
            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Each',$unit_cost,$total_cost,'GeneralLabourCosts');
        }

        if (!empty($request->TransomQuantity) && $request->TransomQuantity != 0 && $GeneralLabourCost->SprayFinishOfTransom == 1) {
            $data = getMyLaborCost('SprayFinishOfTransom', $GeneralLabourCost->genLaborCost);
            $description = "SS - Spray Finish Of Transom |".($GeneralLabourCost->SprayFinishOfTransomManMinutes/ 60)."|".$data->labour_cost_per_man."|".($GeneralLabourCost->SprayFinishOfTransomMachineMinutes / 60) ."|".$data->labour_cost_per_machine;
            $unit_cost = ($GeneralLabourCost->SprayFinishOfTransomManMinutes * ($data->labour_cost_per_man/ 60)) + ($GeneralLabourCost->SprayFinishOfTransomMachineMinutes * ($data->labour_cost_per_machine/ 60));
            $total_cost = $unit_cost * $QTYOfScreenType;
            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Each',$unit_cost,$total_cost,'GeneralLabourCosts');
        }

        if (!empty($request->SubFrameMaterial) && !empty($request->SubFrameBottomThickness) && $GeneralLabourCost->SprayFinishOfSubFrame == 1) {
            $data = getMyLaborCost('SprayFinishOfSubFrame', $GeneralLabourCost->genLaborCost);
            $description = "SS - Spray Finish Of Sub Frame |".($GeneralLabourCost->SprayFinishOfSubFrameManMinutes/ 60)."|".$data->labour_cost_per_man."|".($GeneralLabourCost->SprayFinishOfSubFrameMachineMinutes / 60) ."|".$data->labour_cost_per_machine;
            $unit_cost = ($GeneralLabourCost->SprayFinishOfSubFrameManMinutes * ($data->labour_cost_per_man/ 60)) + ($GeneralLabourCost->SprayFinishOfSubFrameMachineMinutes * ($data->labour_cost_per_machine/ 60));
            $total_cost = $unit_cost * $QTYOfScreenType;
            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Each',$unit_cost,$total_cost,'GeneralLabourCosts');
        }

        if($GeneralLabourCost->PallettingPackaging == 1){
            $data = getMyLaborCost('PallettingPackaging', $GeneralLabourCost->genLaborCost);
            $description = "SS - Palletting Packaging |".($GeneralLabourCost->PallettingPackagingManMinutes/ 60)."|".$data->labour_cost_per_man."|".($GeneralLabourCost->PallettingPackagingMachineMinutes / 60) ."|".$data->labour_cost_per_machine;
            $unit_cost = ($GeneralLabourCost->PallettingPackagingManMinutes * ($data->labour_cost_per_man/ 60)) + ($GeneralLabourCost->PallettingPackagingMachineMinutes * ($data->labour_cost_per_machine/ 60));
            $total_cost = $unit_cost * $QTYOfScreenType;
            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Each',$unit_cost,$total_cost,'GeneralLabourCosts');
        }

        if($GeneralLabourCost->LoadingOfLorry == 1){
            $data = getMyLaborCost('LoadingOfLorry', $GeneralLabourCost->genLaborCost);
            $description = "SS - Loading Of Lorry |".($GeneralLabourCost->LoadingOfLorryManMinutes/ 60)."|".$data->labour_cost_per_man."|".($GeneralLabourCost->LoadingOfLorryMachineMinutes / 60) ."|".$data->labour_cost_per_machine;
            $unit_cost = ($GeneralLabourCost->LoadingOfLorryManMinutes * ($data->labour_cost_per_man/ 60)) + ($GeneralLabourCost->LoadingOfLorryMachineMinutes * ($data->labour_cost_per_machine/ 60));
            $total_cost = $unit_cost * $QTYOfScreenType;
            saveScreenBOMCalculation($request,$description,$LMData,$QTYOfScreenType,'Each',$unit_cost,$total_cost,'GeneralLabourCosts');
        }

    }
}

function discountQuotationValue($quotationId,$versionId){
    $QuotationVersion = QuotationVersion::where(['quotation_id'=> $quotationId,'id'=> $versionId])->value('discountQuotation');
    return $QuotationVersion;
}

function discountQuote($quotationId,$versionId): bool{
    $Items = Item::where(['items.QuotationId' => $quotationId, 'items.VersionId' => $versionId])->get();

    $quotation = Quotation::where('id',$quotationId)->first();

    if(!empty($Items)){
        foreach($Items as $data){
            $itemid = $data->itemId;

            $IronmongaryPrice = 0;
            if(!empty($data->IronmongeryID)){
                $AI = AddIronmongery::select('discountprice')->where('id',$data->IronmongeryID)->first();
                $userIds = CompanyUsers();
                $margin = BOMSetting::wherein('UserId',$userIds)->value('margin_for_material');
                $marginDiscount = discountQuotationValue($data->QuotationId,$data->VersionId);
                if($marginDiscount != 0){
                    $margin += $marginDiscount;
                }

                $marginwithcal = 100 - $margin;
                $testvar = $marginwithcal/100;
                $totalcost = $AI->discountprice / $testvar;
                $IronmongaryPrice = round(($totalcost),2);
            }

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
                'IronmongaryPrice' => $IronmongaryPrice,
            ]);
        }
    }

    $NonConfigurableItemStore = NonConfigurableItemStore::where(['non_configurable_item_store.quotationId' => $quotationId, 'non_configurable_item_store.versionId' => $versionId])->get();
    $margin = discountQuotationValue($quotationId,$versionId);
    $currencyPrice = getCurrencyRate($quotationId);
    if(!empty($NonConfigurableItemStore)){
        foreach($NonConfigurableItemStore as $val){
            $NonConfigurableItems = NonConfigurableItems::where('id',$val->nonConfigurableId)->first();
            $QuoteSummaryDiscountValue = ($NonConfigurableItems->price * $margin) / 100;
            $price = ($margin > 0)? ($NonConfigurableItems->price + $QuoteSummaryDiscountValue): ($NonConfigurableItems->price - $QuoteSummaryDiscountValue);
            NonConfigurableItemStore::where('id', $val->id)->update([
                // 'price' => $price,
                // 'total_price' => $price,
                'price' => $price * $currencyPrice,
                'total_price' => $price * $val->quantity * $currencyPrice,
            ]);
        }
    }

    $SideScreenItems = SideScreenItem::where(['side_screen_items.QuotationId' => $quotationId, 'side_screen_items.VersionId' => $versionId])->get();
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
                $GTSellPriceTotal = ($ItemMaster > 0) ? round(($GTSellPrice/$ItemMaster),2) : $GTSellPrice;
            }

            SideScreenItem::where('id', $id)->update([
                'ScreenPrice' => $GTSellPriceTotal
            ]);
        }
    }

    return true;
}
