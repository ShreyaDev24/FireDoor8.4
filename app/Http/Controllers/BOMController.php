<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Session;
use PDF;
use DB;

use App\Models\DoorFrameConstruction;
use App\Models\Quotation;
use App\Models\QuotationVersion;
use App\Models\Project;
use App\Models\User;
use App\Models\Company;
use App\Models\BOMSetting;
use App\Models\BOMDetails;
use App\Models\SettingBOMCost;
use App\Models\Item;
use App\Models\ItemMaster;
use App\Models\ConfigurableDoorFormula;
use App\Models\Option;
use App\Models\LippingSpecies;
use App\Models\AddIronmongery;
use App\Models\BOMCalculation;
use App\Models\ScreenBOMCalculation;
use App\Models\SideScreenItem;
use App\Models\SettingPDFfooter;
use App\Models\CustomerContact;
use App\Models\QuotationContactInformation;
use App\Models\Customer;
use App\Models\IntumescentSealLeafType;
use App\Models\Users;
use App\Models\IronmongeryInfoModel;
use Carbon\Carbon;
use PdfMerger;
use setasign\Fpdi\Tcpdf\Fpdi;

class BOMController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }



    public function generateBOM(Request $request): void
    {

        $quotationId = $request->quatationId;
        $versionID = $request->versionID;
        $UserId = Auth::user()->id;

        $quotaion = Quotation::where('id',$quotationId)->first();
        $project = empty($quotaion->ProjectId) ? '' : Project::where('id',$quotaion->ProjectId)->first();

        $user = empty($quotaion->UserId) ? '' : User::where('id',$quotaion->UserId)->first();
        
        $comapnyDetail = Company::where('UserId',$UserId)->first();
        $bom_setting = BOMSetting::where('UserId',$UserId)->first();
        $bom_doorcore = SettingBOMCost::first();
        $qv = QuotationVersion::where('id',$versionID)->first();
        $version = $qv->version;

        if(empty($bom_setting)){
            echo json_encode([
                'status'=>'error',
                'msg'=>'Please set Build Of Material from setting tabs.'
            ]);
            exit;
        }
        
        if(empty($quotationId)){
            echo json_encode([
                'status'=>'error',
                'msg'=>"Sorry we don't get Quotation number."
            ]);
            exit;
        }
        
        if(empty($versionID)){
            echo json_encode([
                'status'=>'error',
                'msg'=>'Please select version first.'
            ]);
            exit;
        }


        $margin = $bom_setting->margin_for_material;                                    // SAME
        $labourCostPH = $bom_setting->labour_cost_per_machine;                          // SAME
        $labourMargin =  $bom_setting->margin_for_labour;                               // SAME
        $minutes = $bom_setting->lipping_time;
        $pressing_door = $bom_setting->pressing_door;
        $machine_of_frame = $bom_setting->machine_of_frame;
        $machine_of_architrave = $bom_setting->machine_of_architrave;
        $machine_of_plant = $bom_setting->machine_of_plant;
        $CuttingOfVisionPanel = $bom_setting->cutting_of_vision_panel;
        $LabourCostPerMan = $bom_setting->labour_cost_per_man;
        $GlazingSystemTime = $bom_setting->glazing_system_time;
        $MakingGlazingBead = $bom_setting->making_glazing_bead;
        $AccousticSealApplication = $bom_setting->accoustic_seal_application;
        $FittingThresholdSeal = $bom_setting->fitting_threshold_seal;
        $HingesCNC = $bom_setting->hinges_CNC;
        $LocksAndLatchesCNC = $bom_setting->locks_and_latches_CNC;

        $tag = random_int(1000 , 9999999);
        $test = '';
        $totDoorNo = 0;
        $item = Item::select('items.*')
            ->join('quotation_version_items','items.itemId','quotation_version_items.itemID')
            ->where('quotation_version_items.version_id',$versionID)
            //            ->where('items.QuotationId',$quotationId)
            ->groupBy('items.itemId')->get();
        foreach($item as $tt){


            $totDoorNo = ItemMaster::where('itemID',$tt->itemId)->count();
            $doortype = $tt->DoorType;
            $lipping_thickness = $tt->LippingThickness;
            $lipping_width1 = $tt->LeafWidth1;
            $lipping_width2 = $tt->LeafWidth2;
            $lipping_height = $tt->LeafHeight;
            $lipping_depth = $tt->LeafThickness;
            $given_qty = $totDoorNo;
            $FireRating = $tt->FireRating;

            $cost_of_lipping = 1;

            if ($FireRating == 'FD30') {
                $lipping_depth = 44;
                $cost_of_lipping = $bom_setting->cost_of_lipping_44mm;
            } elseif ($FireRating == 'FD60') {
                $lipping_depth = 54;
                $cost_of_lipping = $bom_setting->cost_of_lipping_54mm;
            }

            // Door Core
            if (!empty($lipping_thickness)) {
                $LippingThicknessAdditionalForWidth1 = 2;
                $LippingThicknessAdditionalForWidth2 = 2;
                $LippingThicknessAdditionalForHeight = 2;
                $core_width1 = '';
                if(!empty($lipping_width1)){

                    $ConfigurableDoorFormula = ConfigurableDoorFormula::where('slug',"core_width_1")->first();
                    if($ConfigurableDoorFormula !== null){
                        $ConfigurableDoorFormulaDecoded = json_decode($ConfigurableDoorFormula->value,true);
                        $LippingThicknessAdditionalForWidth1 = $ConfigurableDoorFormulaDecoded["lipping_thickness"];
                    }

                    $lipwidth1 = $lipping_width1 - $LippingThicknessAdditionalForWidth1 * $lipping_thickness;
                    $core_width1 = $lipwidth1;
                }

                $core_width2 = '';
                if(!empty($lipping_width2)){

                    $ConfigurableDoorFormula = ConfigurableDoorFormula::where('slug',"core_width_2")->first();
                    if($ConfigurableDoorFormula !== null){
                        $ConfigurableDoorFormulaDecoded = json_decode($ConfigurableDoorFormula->value,true);
                        $LippingThicknessAdditionalForWidth2 = $ConfigurableDoorFormulaDecoded["lipping_thickness"];
                    }

                    $lipwidth2 = $lipping_width2 - $LippingThicknessAdditionalForWidth2 * $lipping_thickness;
                    $core_width2 = $lipwidth2;
                }

                $core_height = '';
                if(!empty($lipping_height)){

                    $ConfigurableDoorFormula = ConfigurableDoorFormula::where('slug',"core_height")->first();
                    if($ConfigurableDoorFormula !== null){
                        $ConfigurableDoorFormulaDecoded = json_decode($ConfigurableDoorFormula->value,true);
                        $LippingThicknessAdditionalForHeight = $ConfigurableDoorFormulaDecoded["lipping_thickness"];
                    }

                    $lipheight = $lipping_height - $LippingThicknessAdditionalForHeight * $lipping_thickness;
                    $core_height = $lipheight;
                }

                $core_depth = $lipping_depth;
                $door = $tt->DoorsetType;
                $cutting_door_core = $bom_setting->cutting_door_core;
                // SELECT *,min(`width`),min(`height`) FROM `bom_doorcore` WHERE `width` >= 999 AND `height` >= 2300 AND`depth` = 54
                // return $tt->itemId.'='.$core_width1.'-'.$core_height.'-'.$core_depth;
                if($core_width1 != '' && $core_height != '' && $core_depth != ''){
                    $bom_doorcoreCount = SettingBOMCost::
                    where(['UserId'=>$UserId,'name'=>'DoorCore'])
                        ->where('width','>=', $core_width1)->where('height','>=', $core_height)->where('depth','=', $core_depth)
                        ->MIN('width')->MIN('height')
                        ->count();
                    if($bom_doorcoreCount > 0){
                        $bom_doorcore = SettingBOMCost::
                        where(['UserId'=>$UserId,'name'=>'DoorCore'])
                            ->where('width','>=', $core_width1)->where('height','>=', $core_height)->where('depth','=', $core_depth)
                            ->MIN('width')->MIN('height')
                            ->first();
                        $unitCost = $bom_doorcore->cost;

                        $SelectedDoorDimension = $bom_doorcore->width." x ".$bom_doorcore->height." x ".$bom_doorcore->depth."mm";

                        if(!empty($door)){
                            DoorCore($quotationId,$versionID,$doortype,$SelectedDoorDimension,$margin,$labourCostPH,$labourMargin,$given_qty,$door,$unitCost,$cutting_door_core,$tag);
                        }
                    }
                }
            }

            // Lipping
            if($lipping_thickness != '' || $lipping_thickness > 0){
                if ($lipping_width1 != '' && $lipping_width2 != '' && $lipping_height != '') {
                    $i = 3;
                    $j = 0;
                    while($i > $j){
                        if ($j == 0) {
                            // echo $lipping_width1.'-'.$lipping_width2.'-'.$lipping_height."<br>";
                            Lipping('Width1',$quotationId,$versionID,$doortype,$margin,$labourCostPH,$labourMargin,$minutes,$given_qty,$lipping_width1,'','',$cost_of_lipping,$lipping_thickness,$tag);
                        } elseif ($j == 1) {
                            Lipping('Width2',$quotationId,$versionID,$doortype,$margin,$labourCostPH,$labourMargin,$minutes,$given_qty,'',$lipping_width2,'',$cost_of_lipping,$lipping_thickness,$tag);
                        } elseif ($j === 2) {
                            Lipping('Height',$quotationId,$versionID,$doortype,$margin,$labourCostPH,$labourMargin,$minutes,$given_qty,'','',$lipping_height,$cost_of_lipping,$lipping_thickness,$tag);
                        }
                        
                        $j++;
                    }
                } elseif ($lipping_width1 != '' && $lipping_width2 == '' && $lipping_height == '') {
                    $i = 1;
                    Lipping('Width1',$quotationId,$versionID,$doortype,$margin,$labourCostPH,$labourMargin,$minutes,$given_qty,$lipping_width1,'','',$cost_of_lipping,$lipping_thickness,$tag);
                } elseif ($lipping_width1 != '' && $lipping_width2 != '' && $lipping_height == '') {
                    $i = 2;
                    $j = 0;
                    while($i > $j){
                        if ($j == 0) {
                            Lipping('Width1',$quotationId,$versionID,$doortype,$margin,$labourCostPH,$labourMargin,$minutes,$given_qty,$lipping_width1,'','',$cost_of_lipping,$lipping_thickness,$tag);
                        } elseif ($j === 1) {
                            Lipping('Width2',$quotationId,$versionID,$doortype,$margin,$labourCostPH,$labourMargin,$minutes,$given_qty,'',$lipping_width2,'',$cost_of_lipping,$lipping_thickness,$tag);
                        }
                        
                        $j++;
                    }
                } elseif ($lipping_width1 != '' && $lipping_width2 == '' && $lipping_height != '') {
                    $i = 2;
                    $j = 0;
                    while($i > $j){
                        if ($j == 0) {
                            Lipping('Width1',$quotationId,$versionID,$doortype,$margin,$labourCostPH,$labourMargin,$minutes,$given_qty,$lipping_width1,'','',$cost_of_lipping,$lipping_thickness,$tag);
                        } elseif ($j === 1) {
                            Lipping('Height',$quotationId,$versionID,$doortype,$margin,$labourCostPH,$labourMargin,$minutes,$given_qty,'','',$lipping_height,$cost_of_lipping,$lipping_thickness,$tag);
                        }
                        
                        $j++;
                    }
                } elseif ($lipping_width1 == '' && $lipping_width2 != '' && $lipping_height == '') {
                    $i = 1;
                    Lipping('Width2',$quotationId,$versionID,$doortype,$margin,$labourCostPH,$labourMargin,$minutes,$given_qty,'',$lipping_width2,'',$cost_of_lipping,$lipping_thickness,$tag);
                } elseif ($lipping_width1 == '' && $lipping_width2 != '' && $lipping_height != '') {
                    $i = 2;
                    $j = 0;
                    while($i > $j){
                        if ($j == 0) {
                            Lipping('Width2',$quotationId,$versionID,$doortype,$margin,$labourCostPH,$labourMargin,$minutes,$given_qty,'',$lipping_width2,'',$cost_of_lipping,$lipping_thickness,$tag);
                        } elseif ($j === 1) {
                            Lipping('Height',$quotationId,$versionID,$doortype,$margin,$labourCostPH,$labourMargin,$minutes,$given_qty,'','',$lipping_height,$cost_of_lipping,$lipping_thickness,$tag);
                        }
                        
                        $j++;
                    }
                } elseif ($lipping_width1 == '' && $lipping_width2 == '' && $lipping_height != '') {
                    $i = 1;
                    Lipping('Height',$quotationId,$versionID,$doortype,$margin,$labourCostPH,$labourMargin,$minutes,$given_qty,'','',$lipping_height,$cost_of_lipping,$lipping_thickness,$tag);
                }
            }

            // Door Finish
            if(isset($core_width1) && isset($core_width2) && isset($core_height)){

                $core_width1 = $core_width1;
                $core_width2 = $core_width2;
                $core_height = $core_height;
                $door_leaf_facing = $tt->DoorLeafFacing;

                $finishesCount = SettingBOMCost::
                where(['UserId'=>$UserId,'name'=>'Finishes'])
                    ->where('width','>=', $core_width1)->where('height','>=', $core_height)
                    ->MIN('width')->MIN('height')
                    ->count();
                $unitCost = null;
                if($finishesCount > 0){
                    $finishes = SettingBOMCost::
                    where(['UserId'=>$UserId,'name'=>'Finishes'])
                        ->where('width','>=', $core_width1)->where('height','>=', $core_height)
                        ->MIN('width')->MIN('height')
                        ->first();
                    $unitCost = $finishes->cost;
                }

                if(!empty($core_width1) && !empty($core_width2) && !empty($core_height) && !empty($door_leaf_facing)){
                    $aa = BOMDetails::where(['tag'=>$tag,'name' => 'DoorCore' , 'quotationId' => $quotationId , 'version' => $versionID , 'doortype' => $doortype])->first();
                    if($aa != null){
                        $totalSlabsPerType = $aa->total_slabs_per_type;
                        DoorFinish($quotationId,$versionID,$doortype,$margin,$labourCostPH,$labourMargin,$totalSlabsPerType,$unitCost,$door_leaf_facing,$core_width1,$core_width2,$core_height,$pressing_door,$tag);
                    }
                }
            }



            // Frame
            $FrameWidth = $tt->FrameWidth;
            $FrameHeight = $tt->FrameHeight;
            $FrameType = $tt->FrameType;
            $FrameDepth = $tt->FrameDepth;
            $FrameSpecies = $tt->FrameMaterial;
            $FrameFinish = $tt->FrameFinish;
            $ExtLiner = $tt->ExtLiner;

            // Frame
            // Architrave
            if(!empty($FrameWidth) && !empty($FrameHeight) && !empty($FrameDepth)){
            $BOMFrame = SettingBOMCost::where(['UserId'=>$UserId,'name'=>'FrameSizes'])
                    ->whereNull('parent')
                    ->where('depth','=', $FrameDepth)
                    ->first();
                if($BOMFrame != null) {
                    $unitCost = $BOMFrame->cost;

                    if ($FrameFinish == "Painted_Finish" || $FrameFinish == "Primed_Only") {
                        $BOMFrameFinish = SettingBOMCost::where(['UserId' => $UserId, 'name' => 'FrameSizes', "parent" => $FrameFinish])
                            ->where('depth', '=', $FrameDepth)
                            ->first();
                        if ($BOMFrameFinish != null) {
                            $unitCost += $BOMFrameFinish->cost;
                        }
                    }

                    $insertedIn = $FrameSpecies;

                    $OptionForFrameFinish = Option::where(['OptionSlug' => 'Frame_Finish', 'OptionKey' => $FrameFinish])->first();
                    if ($OptionForFrameFinish != null) {
                        $OptionForFrameFinish = $OptionForFrameFinish->toArray();
                        $OptionValueForFrameFinish = $OptionForFrameFinish['OptionValue'];
                        $insertedIn = $FrameSpecies . " ( " . $OptionValueForFrameFinish . " )";
                    }

                    Frame($quotationId, $versionID, $doortype, $insertedIn, $margin, $labourCostPH, $labourMargin, $given_qty, $FrameWidth, $FrameHeight, $unitCost, $machine_of_frame, '', '', $tag);

                    if ($FrameType == "Plant_on_Stop") {
                        $BOMFrameType = SettingBOMCost::where(['UserId' => $UserId, 'name' => 'FrameSizes', "parent" => $FrameType])
                            ->first();
                        if ($BOMFrameType != null) {
                            $BOMFrameTypeUnitCost = $BOMFrameType->cost;
                            $insertedIn = "Plant On Stop";
                            Frame($quotationId, $versionID, $doortype, $insertedIn, $margin, $labourCostPH, $labourMargin, $given_qty, $FrameWidth, $FrameHeight, $BOMFrameTypeUnitCost, $machine_of_plant, '', '', $tag);
                        }
                    }

                    if($ExtLiner == "Yes"){
                        $BOMExtLiner = SettingBOMCost::where(['UserId' => $UserId, 'name' => 'FrameLinear'])
                            ->whereNull('parent')
                            ->first();
                        if ($BOMExtLiner != null) {
                            $BOMExtLinerUnitCost = $BOMExtLiner->cost;
                            $insertedIn = "Linear - ".$FrameSpecies;

                            if ($FrameFinish == "Painted_Finish" || $FrameFinish == "Primed_Only") {
                                $BOMExtLinerFrameFinish = SettingBOMCost::where(['UserId' => $UserId, 'name' => 'FrameLinear', "parent" => $FrameFinish])
                                    ->first();
                                if ($BOMExtLinerFrameFinish != null) {
                                    $BOMExtLinerUnitCost += $BOMExtLinerFrameFinish->cost;
                                }

                                if(isset($OptionValueForFrameFinish)){
                                    $insertedIn = "Linear - ".$FrameSpecies . " ( " . $OptionValueForFrameFinish . " )";
                                }
                            }

                            Frame($quotationId, $versionID, $doortype, $insertedIn, $margin, $labourCostPH, $labourMargin, $given_qty, $FrameWidth, $FrameHeight, $BOMExtLinerUnitCost, $machine_of_frame, '', '', $tag);
                        }
                    }
                }

                if($tt->Architrave == "Yes" && !empty($tt->ArchitraveWidth)){

                    $BOMArchitrave = SettingBOMCost::where(['UserId' => $UserId, 'name' => 'Architrave'])
                        ->where('width',">=",$tt->ArchitraveWidth)
                        ->whereNull('parent')
                        ->first();
                    if ($BOMArchitrave != null) {
                        $BOMArchitraveUnitCost = $BOMArchitrave->cost;

                        $totalLMofJob = (($FrameWidth/1000) + (($FrameHeight/1000)*2)) * $given_qty;

                        $insertedIn = null;

                        if ($tt->ArchitraveFinish == "Painted_Finish" || $tt->ArchitraveFinish == "Primed_Only") {
                            $BOMArchitraveFinish = SettingBOMCost::where(['UserId' => $UserId, 'name' => 'Architrave', "parent" => $tt->ArchitraveFinish])
                                ->first();
                            if ($BOMArchitraveFinish != null) {
                                $BOMArchitraveUnitCost += $BOMArchitraveFinish->cost;
                            }

                            $OptionForArchitraveFinish = Option::where(['OptionSlug' => 'Architrave_Finish', 'OptionKey' => $tt->ArchitraveFinish])->first();
                            if ($OptionForArchitraveFinish != null) {
                                $OptionForArchitraveFinish = $OptionForArchitraveFinish->toArray();
                                $OptionValueForArchitraveFinish = $OptionForArchitraveFinish['OptionValue'];
                                $insertedIn = $OptionValueForArchitraveFinish;
                            }
                        }

                        $ArchitraveSetQty = (empty($tt->ArchitraveSetQty))?1:$tt->ArchitraveSetQty;

                        Architrave($quotationId,$versionID,$doortype,$insertedIn,$margin,$labourCostPH,$labourMargin,$given_qty,$totalLMofJob,$BOMArchitraveUnitCost,$machine_of_architrave,$ArchitraveSetQty,$tag);

                    }

                }
            }


            if($tt->Leaf1VisionPanel == "Yes" &&
                !empty($tt->Leaf1VPWidth) &&
                !empty($tt->Leaf1VPHeight1) &&
                !empty($tt->VisionPanelQuantity) &&
                !empty($tt->GlassType) &&
                !empty($tt->GlassThickness)){

                $Quantity = $tt->VisionPanelQuantity;
                $Leaf1VPWidth = $tt->Leaf1VPWidth / 1000;
                $Leaf1VPHeight1 = $tt->Leaf1VPHeight1 / 1000;
                $UnitLMPerM2 = $Leaf1VPWidth * $Leaf1VPHeight1;

                if(!empty($tt->Leaf1VPHeight2) && is_float($tt->Leaf1VPHeight2)){
                    $UnitLMPerM2 += $Leaf1VPWidth * ($tt->Leaf1VPHeight2/1000);
                }

                if(!empty($tt->Leaf1VPHeight3) && is_float($tt->Leaf1VPHeight3)){
                    $UnitLMPerM2 += $Leaf1VPWidth * ($tt->Leaf1VPHeight3/1000);
                }

                if(!empty($tt->Leaf1VPHeight4) && is_float($tt->Leaf1VPHeight4)){
                    $UnitLMPerM2 += $Leaf1VPWidth * ($tt->Leaf1VPHeight4/1000);
                }

                if(!empty($tt->Leaf1VPHeight5) && is_float($tt->Leaf1VPHeight5)){
                    $UnitLMPerM2 += $Leaf1VPWidth * ($tt->Leaf1VPHeight5/1000);
                }


                $OptionForGlassThickness = Option::where(['OptionSlug' => 'leaf1_glass_thickness', 'UnderAttribute' => $tt->GlassType, 'OptionKey' => $tt->GlassThickness])->first();
                if ($OptionForGlassThickness != null) {
                    $OptionForGlassThickness = $OptionForGlassThickness->toArray();
                    $OptionCostForGlassThickness = $OptionForGlassThickness['OptionCost'];
                    $UnitLMPerM2Cost = $OptionCostForGlassThickness;
                    $insertedIn = "";
                    $OptionForGlassType = Option::where(['OptionSlug' => 'leaf1_glass_type','OptionKey' => $tt->GlassType])->first();
                    if ($OptionForGlassType != null) {
                        $OptionForGlassType = $OptionForGlassType->toArray();
                        $OptionValueForGlassType = $OptionForGlassType["OptionValue"];
                        $insertedIn = $OptionValueForGlassType." ".$tt->GlassThickness;
                    }

                    $Minutes = $CuttingOfVisionPanel * $Quantity;

                    Glass($quotationId,$versionID,$doortype,$insertedIn,$Quantity,$UnitLMPerM2,$UnitLMPerM2Cost,$margin,$labourCostPH,$Minutes,$labourMargin,$tag);

                    if(!empty($tt->GlazingSystems)){

                        $QuantityForGlazingSystems = $Quantity * 2;
                        $UnitLMPerM2ForGlazingSystems = ((2 * $Leaf1VPWidth) + (2 * $Leaf1VPHeight1)) * $QuantityForGlazingSystems;

                        if(!empty($tt->Leaf1VPHeight2) && is_float($tt->Leaf1VPHeight2)){
                            $UnitLMPerM2ForGlazingSystems += ((2 * $Leaf1VPWidth) + (2 * ($tt->Leaf1VPHeight2/1000))) * $QuantityForGlazingSystems;
                        }

                        if(!empty($tt->Leaf1VPHeight3) && is_float($tt->Leaf1VPHeight3)){
                            $UnitLMPerM2ForGlazingSystems += ((2 * $Leaf1VPWidth) + (2 * ($tt->Leaf1VPHeight3/1000))) * $QuantityForGlazingSystems;
                        }

                        if(!empty($tt->Leaf1VPHeight4) && is_float($tt->Leaf1VPHeight4)){
                            $UnitLMPerM2ForGlazingSystems += ((2 * $Leaf1VPWidth) + (2 * ($tt->Leaf1VPHeight4/1000))) * $QuantityForGlazingSystems;
                        }

                        if(!empty($tt->Leaf1VPHeight5) && is_float($tt->Leaf1VPHeight5)){
                            $UnitLMPerM2ForGlazingSystems += ((2 * $Leaf1VPWidth) + (2 * ($tt->Leaf1VPHeight5/1000))) * $QuantityForGlazingSystems;
                        }

                        $OptionForGlassThickness = Option::where(['OptionSlug' => 'leaf1_glazing_systems', 'OptionKey' => $tt->GlazingSystems])->first();
                        if ($OptionForGlassThickness != null) {
                            $OptionForGlassThickness = $OptionForGlassThickness->toArray();
                            $OptionCostForGlassThickness = $OptionForGlassThickness['OptionCost'];
                            $UnitLMPerM2CostForGlazingSystems = $OptionCostForGlassThickness;
                            $insertedInForGlazingSystems = $OptionForGlassThickness['OptionValue'];

                            $Minutes = $GlazingSystemTime * $Quantity;

                            Glass($quotationId,$versionID,$doortype,$insertedInForGlazingSystems,$QuantityForGlazingSystems,$UnitLMPerM2ForGlazingSystems,$UnitLMPerM2CostForGlazingSystems,$margin,$LabourCostPerMan,$Minutes,$labourMargin,$tag);

                            if(!empty($tt->GlazingBeads) && !empty($tt->GlazingBeadSpecies)){

                                $OptionForGlazingBeads = Option::where(['OptionSlug' => 'leaf1_glazing_beads', 'OptionKey' => $tt->GlazingBeads])->first();
                                if ($OptionForGlazingBeads != null) {
                                    $OptionForGlazingBeads = $OptionForGlazingBeads->toArray();
                                    $OptionCostForGlazingBeads = $OptionForGlazingBeads['OptionCost'];
                                    $UnitLMPerM2CostForGlazingBeads = $OptionCostForGlazingBeads;
                                    $insertedInForGlazingBeads = $OptionForGlazingBeads['OptionValue'];

                                    $LippingSpeciesForGlazingBeads = LippingSpecies::where('id',$tt->GlazingBeadSpecies)->first();
                                    if($LippingSpeciesForGlazingBeads != null){
                                        $LippingSpeciesForGlazingBeads = $LippingSpeciesForGlazingBeads->toArray();
                                        $UnitLMPerM2CostForGlazingBeads += $LippingSpeciesForGlazingBeads['LippingSpeciesCost'];
                                        $insertedInForGlazingBeads .= " ". $LippingSpeciesForGlazingBeads['SpeciesName'];

                                        $Minutes = $MakingGlazingBead * $QuantityForGlazingSystems;
                                        Glass($quotationId,$versionID,$doortype,$insertedInForGlazingBeads,$QuantityForGlazingSystems,$UnitLMPerM2ForGlazingSystems,$UnitLMPerM2CostForGlazingBeads,$margin,$LabourCostPerMan,$Minutes,$labourMargin,$tag);
                                    }

                                }

                            }

                        }

                    }
                }

            }

            if(!empty($FrameWidth) && !empty($FrameHeight)){
                $AccousticQuantity = 1;
                $AccousticFrameWidth = $FrameWidth / 1000;
                $AccousticFrameHeight = $FrameHeight / 1000;

                $AccousticUnitLMPerM2 = ( $AccousticFrameHeight * 2 ) + $AccousticFrameWidth;

                $AccousticInsertedIn = "Accoustic";
                $AccousticUnitLMPerM2Cost = 1.5;

                $AccousticMinutes = $AccousticSealApplication * $given_qty;
                $AccousticTotalSlabPerType = null;

                $GrandTotalCost = round($AccousticUnitLMPerM2*$AccousticUnitLMPerM2Cost,2);

                Accoustic($quotationId,$versionID,$doortype,$AccousticInsertedIn,$GrandTotalCost,$AccousticQuantity,$AccousticTotalSlabPerType,$AccousticUnitLMPerM2,$AccousticUnitLMPerM2Cost,$margin,$LabourCostPerMan,$AccousticMinutes,$labourMargin,$tag);

                // if(!empty($tt->AccousticsThresholdSeal)){
                //     $AccousticInsertedIn = "Threshold Seal";
                //     $AccousticUnitLMPerM2Cost = 10;

                //     $AccousticMinutes = $FittingThresholdSeal * $given_qty;
                //     $AccousticTotalSlabPerType = $given_qty * 1;

                //     $GrandTotalCost = round($AccousticTotalSlabPerType*$AccousticUnitLMPerM2Cost,2);

                //     Accoustic($quotationId,$version,$doortype,$AccousticInsertedIn,$GrandTotalCost,$AccousticQuantity,$AccousticTotalSlabPerType,$AccousticUnitLMPerM2,$AccousticUnitLMPerM2Cost,$margin,$LabourCostPerMan,$AccousticMinutes,$labourMargin,$tag);
                // }

            }

            if($tt->IronmongerySet == "Yes" && !empty($tt->IronmongeryID)){

                $IronmongeryDetails = AddIronmongery::where("id",$tt->IronmongeryID)->first();
                if($IronmongeryDetails !== null){
                    if(!empty($IronmongeryDetails->Hinges) && !empty($IronmongeryDetails->hingesQty)){

                        $Quantity = $IronmongeryDetails->hingesQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->Hinges)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Hinges";
                            $Minutes = $HingesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$margin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->FloorSpring) && !empty($IronmongeryDetails->floorSpringQty)){

                        $Quantity = $IronmongeryDetails->floorSpringQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->FloorSpring)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Floor Spring";
                            $Minutes = $HingesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$margin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->LocksAndLatches) && !empty($IronmongeryDetails->lockesAndLatchesQty)){

                        $Quantity = $IronmongeryDetails->lockesAndLatchesQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->LocksAndLatches)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Locks And Latches";
                            $Minutes = $LocksAndLatchesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$labourMargin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->FlushBolts) && !empty($IronmongeryDetails->flushBoltsQty)){

                        $Quantity = $IronmongeryDetails->flushBoltsQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->FlushBolts)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Flush Bolts";
                            $Minutes = $LocksAndLatchesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$labourMargin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->ConcealedOverheadCloser) && !empty($IronmongeryDetails->concealedOverheadCloserQty)){

                        $Quantity = $IronmongeryDetails->concealedOverheadCloserQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->ConcealedOverheadCloser)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Concealed Overhead Closer";
                            $Minutes = $LocksAndLatchesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$labourMargin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->PullHandles) && !empty($IronmongeryDetails->pullHandlesQty)){

                        $Quantity = $IronmongeryDetails->pullHandlesQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->PullHandles)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Pull Handles";
                            $Minutes = $LocksAndLatchesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$labourMargin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->PushHandles) && !empty($IronmongeryDetails->pushHandlesQty)){

                        $Quantity = $IronmongeryDetails->pushHandlesQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->PushHandles)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Push Handles";
                            $Minutes = $LocksAndLatchesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$labourMargin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->KickPlates) && !empty($IronmongeryDetails->kickPlatesQty)){

                        $Quantity = $IronmongeryDetails->kickPlatesQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->KickPlates)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Kick Plates";
                            $Minutes = $LocksAndLatchesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$labourMargin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->DoorSelectors) && !empty($IronmongeryDetails->doorSelectorsQty)){

                        $Quantity = $IronmongeryDetails->doorSelectorsQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->DoorSelectors)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Door Selectors";
                            $Minutes = $LocksAndLatchesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$labourMargin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->PanicHardware) && !empty($IronmongeryDetails->panicHardwareQty)){

                        $Quantity = $IronmongeryDetails->panicHardwareQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->PanicHardware)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Panic Hardware";
                            $Minutes = $LocksAndLatchesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$labourMargin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->PanicHardware) && !empty($IronmongeryDetails->panicHardwareQty)){

                        $Quantity = $IronmongeryDetails->panicHardwareQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->PanicHardware)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Panic Hardware";
                            $Minutes = $LocksAndLatchesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$labourMargin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->Doorsecurityviewer) && !empty($IronmongeryDetails->doorSecurityViewerQty)){

                        $Quantity = $IronmongeryDetails->doorSecurityViewerQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->Doorsecurityviewer)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Door Security Viewer";
                            $Minutes = $LocksAndLatchesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$labourMargin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->Morticeddropdownseals) && !empty($IronmongeryDetails->morticeddropdownsealsQty)){

                        $Quantity = $IronmongeryDetails->morticeddropdownsealsQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->Morticeddropdownseals)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Morticed Dropdown Seals";
                            $Minutes = $LocksAndLatchesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$labourMargin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->Facefixeddropseals) && !empty($IronmongeryDetails->facefixeddropsealsQty)){

                        $Quantity = $IronmongeryDetails->facefixeddropsealsQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->Facefixeddropseals)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Face fixed dropseals";
                            $Minutes = $LocksAndLatchesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$labourMargin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->ThresholdSeal) && !empty($IronmongeryDetails->thresholdSealQty)){

                        $Quantity = $IronmongeryDetails->thresholdSealQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->ThresholdSeal)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Threshold Seal";
                            $Minutes = $LocksAndLatchesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$labourMargin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->AirTransferGrill) && !empty($IronmongeryDetails->airtransfergrillsQty)){

                        $Quantity = $IronmongeryDetails->airtransfergrillsQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->AirTransferGrill)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Air Transfer Grill";
                            $Minutes = $LocksAndLatchesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$labourMargin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->Letterplates) && !empty($IronmongeryDetails->letterplatesQty)){

                        $Quantity = $IronmongeryDetails->letterplatesQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->Letterplates)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Letter Plates";
                            $Minutes = $LocksAndLatchesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$labourMargin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->CableWays) && !empty($IronmongeryDetails->cableWaysQty)){

                        $Quantity = $IronmongeryDetails->cableWaysQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->CableWays)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Cable Ways";
                            $Minutes = $LocksAndLatchesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$labourMargin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->SafeHinge) && !empty($IronmongeryDetails->safeHingeQty)){

                        $Quantity = $IronmongeryDetails->safeHingeQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->SafeHinge)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Safe Hinge";
                            $Minutes = $LocksAndLatchesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$labourMargin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->LeverHandle) && !empty($IronmongeryDetails->leverHandleQty)){

                        $Quantity = $IronmongeryDetails->leverHandleQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->LeverHandle)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Lever Handle";
                            $Minutes = $LocksAndLatchesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$labourMargin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->DoorSinage) && !empty($IronmongeryDetails->doorSignageQty)){

                        $Quantity = $IronmongeryDetails->doorSignageQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->DoorSinage)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Door Sinage";
                            $Minutes = $LocksAndLatchesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$labourMargin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->FaceFixedDoorCloser) && !empty($IronmongeryDetails->faceFixedDoorClosersQty)){

                        $Quantity = $IronmongeryDetails->faceFixedDoorClosersQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->FaceFixedDoorCloser)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Face Fixed Door Closer";
                            $Minutes = $LocksAndLatchesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$labourMargin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->Thumbturn) && !empty($IronmongeryDetails->thumbturnQty)){

                        $Quantity = $IronmongeryDetails->thumbturnQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->Thumbturn)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Thumbturn";
                            $Minutes = $LocksAndLatchesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$labourMargin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }

                    if(!empty($IronmongeryDetails->KeyholeEscutchen) && !empty($IronmongeryDetails->keyholeEscutcheonQty)){

                        $Quantity = $IronmongeryDetails->keyholeEscutcheonQty;
                        $IronmongeryInfo = IronmongeryInfoModel::where("id",$IronmongeryDetails->KeyholeEscutchen)->first();
                        if($IronmongeryInfo !== null){
                            $UnitLMPerM2Cost = $IronmongeryInfo->Price;
                            $InsertedIn = $IronmongeryInfo->Name." Keyhole Escutchen";
                            $Minutes = $LocksAndLatchesCNC * $Quantity;
                            Ironmongery($quotationId,$versionID,$doortype,$InsertedIn,$Quantity,$UnitLMPerM2Cost,$labourMargin,$labourCostPH,$Minutes,$labourMargin,$tag);
                        }

                    }
                }

            }



            BOMDetails::where([ 'quotationId' => $quotationId , 'version' => $versionID , 'doortype' => $tt->DoorType , 'tag' => $tag ])->update([ 'itemId' => $tt->itemId ]);



        }


        $quo = Quotation::find($quotationId);
        $quo->bomTag = $tag;
        $quo->save();

        echo json_encode([
            'status'=>'success',
            'tag'=>$tag,
        ]);


    }

    public function generateBOM2($quotationId,$versionID,$tag)
    {

        $quotationId = $quotationId;
        $versionID = $versionID;
        $UserId = Auth::user()->id;

        $comapnyDetail = Company::where('UserId',$UserId)->first();
        $quotaion = Quotation::where('id',$quotationId)->first();
        $project = empty($quotaion->ProjectId) ? '' : Project::where('id',$quotaion->ProjectId)->first();
        
        $qv = QuotationVersion::where('id',$versionID)->first();
        $version = $qv->version;
        $user = empty($quotaion->UserId) ? '' : User::where('id',$quotaion->UserId)->first();


        $item = Item::select('items.*')
        ->join('quotation_version_items','items.itemId','quotation_version_items.itemID')
        ->where('quotation_version_items.version_id',$versionID)
        ->groupBy('items.itemId')->get();

        $tbl = '';
        $TitleArray = ["Door Leaf Items","Door Frame Items","Door Architrave Items","Door Glass Items","Door Accoustic Items","Door Ironmongery Items"];

        if($TitleArray !== []){
            foreach($TitleArray as $TitleKey => $TitleVal){
                $bom = BOMDetails::where(['tag' => $tag, "type" => $TitleVal])->orderBy('id', 'ASC')->groupBy('doortype')->get();
                if($bom != null){
                    $BOMArray = $bom->toArray();
                    if(empty($BOMArray)){
                        continue;
                    }
                    
                    $tbl .= '<h3 class="title">'.$TitleVal.'</h3>';
                    $tbl .= '
                    <table id="WithBorder" class="tbl1">
                        <tbody>
                            <tr>
                                <th>Description</th>
                                <th>Qty</th>
                                <th>Unit</th>
                                <th>Unit Cost</th>
                                <th>Cost</th>
                                <th>Margin</th>
                                <th>Markup</th>
                                <th>Material Total</th>
                                <th>Labour Cost</th>
                                <th>Labour Margin</th>
                                <th>Labour Markup</th>
                                <th>Labour Total</th>
                                <th>Grand Total</th>
                            </tr>';
                        foreach ($bom as $gg) {
                            $tbl .= '
                            <tr>
                                <th colspan="13" class="doortype">DOOR TYPE ' . $gg->doortype . '</th>
                            </tr>';
                            $bom2 = BOMDetails::where(['tag' => $tag, "type" => $TitleVal, 'doortype' => $gg->doortype])->orderBy('id', 'ASC')->groupBy('name')->get();
                            if($bom2 != null){
                                foreach ($bom2 as $b2key => $gg2) {
                                    $bom3 = BOMDetails::where(['tag' => $tag, 'doortype' => $gg2->doortype, "name" => $gg2->name ])->orderBy('id', 'ASC')->get();
                                    if($bom3 != null){
                                        foreach ($bom3 as $b3key => $gg3){
                                            $Description = "";
                                            $TopBottomBorder = 'style="border-top:none;border-bottom:none;"';
                                            $TopBorder = 'style="border-top:none;"';
                                            $BottomBorder = 'style="border-bottom:none;"';
                                            foreach ($item as $tt){
                                                if($version = $gg3->version && $tt->DoorType == $gg3->doortype){
                                                    if ($gg3->name == "DoorCore"){
                                                        $Description .= 'Strebord Core<br>';
                                                        $Description .= $tt->CoreWidth1 . "x" . $tt->CoreHeight . "x" . $tt->LeafThickness . "mm";
                                                        if ($tt->DoorsetType != "SD") {
                                                            $Description .= "<br>" . $tt->CoreWidth2 . "x" . $tt->CoreHeight . "x" . $tt->LeafThickness . "mm";
                                                        }
                                                        
                                                        $gg3->unitLM = $gg3->insertedIn;
                                                    } elseif ($gg3->name == "Lipping"){
                                                        if($b3key == 0){
                                                            $tbl .= '<tr>
                                                                <td '.$BottomBorder.'><span>Lipping</span></td>
                                                                <td '.$BottomBorder.'><span></span></td>
                                                                <td '.$BottomBorder.'><span>LM</span></td>
                                                                <td '.$BottomBorder.'><span></span></td>
                                                                <td '.$BottomBorder.'><span></span></td>
                                                                <td '.$BottomBorder.'><span></span></td>
                                                                <td '.$BottomBorder.'><span></span></td>
                                                                <td '.$BottomBorder.'><span></span></td>
                                                                <td '.$BottomBorder.'><span></span></td>
                                                                <td '.$BottomBorder.'><span></span></td>
                                                                <td '.$BottomBorder.'><span></span></td>
                                                                <td '.$BottomBorder.'><span></span></td>
                                                                <td '.$BottomBorder.'><span></span></td>
                                                            </tr>';
                                                        }

                                                        if($gg3->insertedIn == "Width1"){
                                                            $Description .= $tt->LeafWidth1 . "x" . $tt->LippingThickness . "x" . $tt->LeafThickness . "mm";
                                                        }

                                                        if($gg3->insertedIn == "Width2"){
                                                            $Description .= $tt->LeafWidth2 . "x" . $tt->LippingThickness . "x" . $tt->LeafThickness . "mm";
                                                        }

                                                        if($gg3->insertedIn == "Height"){
                                                            $Description .= $tt->LeafHeight . "x" . $tt->LippingThickness . "x" . $tt->LeafThickness . "mm";
                                                        }
                                                    } elseif ($gg3->name == "DoorFinish") {
                                                        $DoorLeafFacing = "";
                                                        $DoorLeafFinish = "";
                                                        $OptionForDoorLeafFacing = Option::where(['OptionKey' => $tt->DoorLeafFacing])->first();
                                                        if ($OptionForDoorLeafFacing != null) {
                                                            $OptionForDoorLeafFacing = $OptionForDoorLeafFacing->toArray();
                                                            $DoorLeafFacing = $OptionForDoorLeafFacing['OptionValue'];
                                                        }
                                                        
                                                        $OptionForDoorLeafFinish = Option::where(['OptionKey' => $tt->DoorLeafFinish])->first();
                                                        if ($OptionForDoorLeafFinish != null) {
                                                            $OptionForDoorLeafFinish = $OptionForDoorLeafFinish->toArray();
                                                            $DoorLeafFinish = $OptionForDoorLeafFinish['OptionValue'];
                                                        }
                                                        
                                                        $tbl .= '<tr>
                                                            <td '.$BottomBorder.'><span>'.$DoorLeafFacing . ' ( ' . $DoorLeafFinish . ' )</span></td>
                                                            <td '.$BottomBorder.'><span></span></td>
                                                            <td '.$BottomBorder.'><span>m2</span></td>
                                                            <td '.$BottomBorder.'><span></span></td>
                                                            <td '.$BottomBorder.'><span></span></td>
                                                            <td '.$BottomBorder.'><span></span></td>
                                                            <td '.$BottomBorder.'><span></span></td>
                                                            <td '.$BottomBorder.'><span></span></td>
                                                            <td '.$BottomBorder.'><span></span></td>
                                                            <td '.$BottomBorder.'><span></span></td>
                                                            <td '.$BottomBorder.'><span></span></td>
                                                            <td '.$BottomBorder.'><span></span></td>
                                                            <td '.$BottomBorder.'><span></span></td>
                                                        </tr>';
                                                        $Description .= $tt->LeafWidth1 . "x" . $tt->LeafHeight;
                                                        if ($tt->DoorsetType != "SD") {
                                                            $Description .= "<br>" . $tt->LeafWidth2 . "x" . $tt->LeafHeight;
                                                        }
                                                    } elseif ($gg3->name == "Frame") {
                                                        $Description = $gg3->insertedIn;
                                                    } elseif ($gg3->name == "Architrave") {
                                                        $Description = (empty($gg3->insertedIn))?$gg3->name:$gg3->insertedIn;
                                                    } elseif ($gg3->name == "Glass" || $gg3->name == "Accoustic" || $gg3->name == "Ironmongery") {
                                                        $Description = (empty($gg3->insertedIn))?$gg3->name:$gg3->insertedIn;
                                                    }
                                                    
                                                    break;
                                                }
                                            }
                                            
                                            $Border = "";
                                            if($gg3->name == "Lipping"){
                                                if($gg3->insertedIn == "Width1"){
                                                    $Border = $TopBottomBorder;
                                                }

                                                if($gg3->insertedIn == "Width2"){
                                                    $Border = $TopBottomBorder;
                                                }

                                                if($gg3->insertedIn == "Height"){
                                                    $Border = $TopBorder;
                                                }
                                            } elseif($gg3->name == "DoorFinish"){
                                                $Border = $TopBorder;
                                            }
                                            
                                            $tbl .= '<tr>
                                                <!--<td><span>' . $gg3->name . ' ' . $gg3->insertedIn . '</span></td>-->
                                                <td '.$Border.'><span>' . $Description . '</span></td>
                                                <td '.$Border.'><span>' . $gg3->qty . '</span></td>
                                                <td '.$Border.'><span>' . round($gg3->unitLM,2) . '</span></td>
                                                <td '.$Border.'><span>£' . $gg3->unitLMCost . '</span></td>
                                                <td '.$Border.'><span>£' . $gg3->grandTotalCost . '</span></td>
                                                <td '.$Border.'><span>' . $gg3->margin . '%</span></td>
                                                <td '.$Border.'><span>£' . $gg3->markup . '</span></td>
                                                <td '.$Border.'><span>£' . $gg3->total_price . '</span></td>
                                                <td '.$Border.'><span>£' . $gg3->labour_cost_ph . '</span></td>
                                                <td '.$Border.'><span>' . $gg3->labour_margin . '%</span></td>
                                                <td '.$Border.'><span>£' . $gg3->labour_markup . '</span></td>
                                                <td '.$Border.'><span>£' . round($gg3->labour_total,2) . '</span></td>
                                                <td '.$Border.'><span>£' . $gg3->grand_total . '</span></td>
                                            </tr>';
                                        }
                                    }
                                }
                            }
                        }
                }

                $tbl .='</tbody>
                </table>';
            }
        }

        $pdf = PDF::loadView('BOM_pdf.buildofmaterial',['comapnyDetail' => $comapnyDetail, 'quotaion' => $quotaion, 'project' => $project, 'version' => $version, 'user' => $user, 'tbl' => $tbl]);
        return $pdf->download($quotaion->QuotationGenerationId."-".$version.'-BillOfMaterial.pdf');
    }


    public function BomCalculationPrint(request $request): void{
        $quotationId = $request->quatationId;
        $versionID = $request->versionID;
        if(empty($quotationId)){
            echo json_encode([
                'status'=>'error',
                'msg'=>"Sorry we don't get Quotation number."
            ]);
            exit;
        }
        
        if(empty($versionID)){
            echo json_encode([
                'status'=>'error',
                'msg'=>'Please select version first.'
            ]);
            exit;
        }
        
        $quotation = Quotation::select('project.*','quotation.*','customers.CstCompanyName')->leftjoin('project','quotation.ProjectId','=','project.id')->leftjoin('customers','customers.UserId','quotation.MainContractorId')->where('quotation.id',$request->quatationId)->first();
        $data = BOMCalculation::where('QuotationId',$request->quatationId)->get();
        $item_details = Item::where(['QuotationId'=>$quotationId, 'VersionId'=>$versionID])->get();
        //  dd($item_details, $quotationId, $versionID);
        echo json_encode([
            'status'=>'success',
            'data'=>$data,
            'quotation'=>$quotation,
            'item_details'=>$item_details
        ]);
    }

    public function ScreenBomCalculationPrint(Request $request): void
    {
        $quotationId = $request->quatationId;
        $versionID = $request->versionID;
        if(empty($quotationId)){
            echo json_encode([
                'status'=>'error',
                'msg'=>"Sorry we don't get Quotation number."
            ]);
            exit;
        }
        
        if(empty($versionID)){
            echo json_encode([
                'status'=>'error',
                'msg'=>'Please select version first.'
            ]);
            exit;
        }
        
        $quotation = Quotation::select('project.*','quotation.*','customers.CstCompanyName')->leftjoin('project','quotation.ProjectId','=','project.id')->leftjoin('customers','customers.UserId','quotation.MainContractorId')->where('quotation.id',$request->quatationId)->first();
        $data = ScreenBOMCalculation::where('QuotationId',$request->quatationId)->get();
        $item_details = SideScreenItem::where(['QuotationId'=>$quotationId, 'VersionId'=>$versionID])->get();
        //  dd($item_details, $quotationId, $versionID);
        echo json_encode([
            'status'=>'success',
            'data'=>$data,
            'quotation'=>$quotation,
            'item_details'=>$item_details
        ]);
    }

    public function DoorOrderSheetUrl(request $request): void{
        $quotationId = $request->quatationId;
        $versionID = $request->versionID;
        if(empty($quotationId)){
            echo json_encode([
                'status'=>'error',
                'msg'=>"Sorry we don't get Quotation number."
            ]);
            exit;
        }
        
        if(empty($versionID)){
            echo json_encode([
                'status'=>'error',
                'msg'=>'Please select version first.'
            ]);
            exit;
        }
        
        $quotation = Quotation::select('project.*','quotation.*','customers.CstCompanyName')->leftjoin('project','quotation.ProjectId','=','project.id')->leftjoin('customers','customers.UserId','quotation.MainContractorId')->where('quotation.id',$request->quatationId)->first();
        $data = Item::where('QuotationId',$request->quatationId)->get();
        echo json_encode([
            'status'=>'success',
            'data'=>$data,
            'quotation'=>$quotation
        ]);
    }

    public function FrameTransomsUrl(request $request): void{
        $quotationId = $request->quatationId;
        $versionID = $request->versionID;
        if(empty($quotationId)){
            echo json_encode([
                'status'=>'error',
                'msg'=>"Sorry we don't get Quotation number."
            ]);
            exit;
        }
        
        if(empty($versionID)){
            echo json_encode([
                'status'=>'error',
                'msg'=>'Please select version first.'
            ]);
            exit;
        }
        
        $DoorFrameConstruction = DoorFrameConstruction::where('UserId',Auth::user()->id)->get();
        if ($DoorFrameConstruction->isEmpty()) {
            echo json_encode([
                'status'=>'error',
                'msg'=>'Please fill the Door Frame Construction form in Setting → Door Frame Construction.'
            ]);
            exit;
        }
        
        $quotation = Quotation::select('project.*','quotation.*','customers.CstCompanyName')->leftjoin('project','quotation.ProjectId','=','project.id')->leftjoin('customers','customers.UserId','quotation.MainContractorId')->where('quotation.id',$request->quatationId)->first();
        $data = Item::where('QuotationId',$request->quatationId)->get();
        echo json_encode([
            'status'=>'success',
            'data'=>$data,
            'quotation'=>$quotation
        ]);
    }

    public function BomCalculation($id,string $vid,$version){
        ini_set('memory_limit', '2048M');
        // $vid means version number(1,2,3,4 etc) and $version means version id or number
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
        $totIronmongerySet = $totIronmongerySet;
        
        $version = $bomVersion->VersionId;
        $item_details = Item::join('item_master','items.itemId','item_master.itemID')->select('items.*','item_master.*')->where(['QuotationId'=>$id])->get();

        $GTSellPriceSum = 0;
        foreach($data as $value){
            if($value->Category != 'Ironmongery&MachiningCosts'){
                $GTSellPriceSum += $value->GTSellPrice;
            }
        }

        $pdf = PDF::loadView('DoorSchedule.BOM.BOM_pdf',['data' => $data, 'quotation' => $quotation, 'currency' => $currency, 'laborCost' => $laborCost, 'today' => $today, 'userName' => $userName, 'version' => $version, 'totDoorsetType' => $totDoorsetType, 'totIronmongerySet' => $totIronmongerySet, 'item_details' => $item_details, 'GTSellPriceSum' => $GTSellPriceSum]);


        return $pdf->download("BOM ".trim($quotation->QuotationGenerationId, "#")."-".$vid.".pdf");
    }
    
    public function ScreenBomCalculation($id,string $vid,$version){
        ini_set('memory_limit', '500M');
        // $vid means version number(1,2,3,4 etc) and $version means version id or number
        $quotation = Quotation::select('project.*','quotation.*','customers.CstCompanyName','project.ProjectName as projectname')->leftjoin('project','quotation.ProjectId','=','project.id')->leftjoin('customers','customers.UserId','quotation.MainContractorId')->where('quotation.id',$id)->first();
        $bomVersion = ScreenBOMCalculation::where('QuotationId',$id)->get()->first();
        if($vid == 0 || $bomVersion->VersionId == 0 || $bomVersion->VersionId == NULL){
            $data = ScreenBOMCalculation::join('side_screen_item_master','side_screen_item_master.ScreenID','screen_bom_calculations.ScreenID')->where('screen_bom_calculations.QuotationId',$id)->whereNotNull('screen_bom_calculations.ScreenID')->select('screen_bom_calculations.*')->distinct('side_screenitem_master.ScreenID')->get();
        }else{
            $data = ScreenBOMCalculation::join('side_screen_item_master','side_screen_item_master.ScreenID','screen_bom_calculations.ScreenID')->where('screen_bom_calculations.QuotationId',$id)->where('screen_bom_calculations.VersionId',$vid)->whereNotNull('screen_bom_calculations.ScreenID')->select('screen_bom_calculations.*')->distinct('side_screen_item_master.ScreenID')->get();
        }

        $currency = QuotationCurrency($quotation->Currency);
        $today = Carbon::now()->format('d-m-Y');
        $userName = Auth::user()->FirstName ." ".Auth::user()->LastName;
        $totDoorsetType = NumberOfScreenSets($version,$id);
        $version = $bomVersion->VersionId;
        $item_details = SideScreenItem::join('side_screen_item_master','side_screen_items.id','side_screen_item_master.ScreenID')->select('side_screen_items.*','side_screen_item_master.*')->where(['QuotationId'=>$id])->get();

        $GTSellPriceSum = 0;
        $TotalCostSum = 0;
        foreach($data as $value){
            $GTSellPriceSum += $value->GTSellPrice;
            $TotalCostSum += $value->TotalCost;
        }


        $pdf = PDF::loadView('DoorSchedule.ScreenBOM.Screen_BOM_pdf',['data' => $data, 'quotation' => $quotation, 'currency' => $currency, 'today' => $today, 'userName' => $userName, 'version' => $version, 'totDoorsetType' => $totDoorsetType, 'item_details' => $item_details, 'GTSellPriceSum' => $GTSellPriceSum, 'TotalCostSum' => $TotalCostSum]);


        return $pdf->download("BOM ".trim($quotation->QuotationGenerationId, "#")."-".$vid.".pdf");
    }

    public function DoorOrderSheet($id,string $vid,$version){
        ini_set('memory_limit', '500M');

        $quotation = Quotation::select('project.*','quotation.*','customers.CstCompanyName','project.ProjectName as projectname')->leftjoin('project','quotation.ProjectId','=','project.id')->leftjoin('customers','customers.UserId','quotation.MainContractorId')->where('quotation.id',$id)->first();

        $item = Item::Join('item_master','items.itemId','item_master.itemID')->leftjoin('lipping_species','lipping_species.id','items.LippingSpecies')->where('QuotationId',$id)->where('VersionId',$version)->select('item_master.*','items.*','lipping_species.SpeciesName')->orderBy('items.itemId','ASC')->get();

        $currency = QuotationCurrency($quotation->Currency);
        $today = Carbon::now()->format('d-m-Y');
        $userName = Auth::user()->FirstName ." ".Auth::user()->LastName;
        $totDoorsetType = NumberOfDoorSets($version,$id);
        $totIronmongerySet = Item::where(['QuotationId' => $id,'VersionId'=>$version])->whereNotNull('IronmongeryID')->count();

        $pdf = PDF::loadView('DoorSchedule.DoorOrderSheet',['item' => $item, 'quotation' => $quotation, 'currency' => $currency, 'today' => $today, 'userName' => $userName, 'version' => $version, 'totDoorsetType' => $totDoorsetType, 'totIronmongerySet' => $totIronmongerySet]);
        return $pdf->download("BOM ".trim($quotation->QuotationGenerationId, "#")."-".$vid.".pdf");
    }

    public function FrameTransoms($id,string $vid,$version){
        ini_set('memory_limit', '500M');

        $quotation = Quotation::select('project.*','quotation.*','customers.CstCompanyName','project.ProjectName as projectname')->leftjoin('project','quotation.ProjectId','=','project.id')->leftjoin('customers','customers.UserId','quotation.MainContractorId')->where('quotation.id',$id)->first();

        $item = Item::Join('item_master','items.itemId','item_master.itemID')->leftjoin('door_frame_construction','items.DoorFrameConstruction','door_frame_construction.DoorFrameConstruction')->leftjoin('lipping_species','lipping_species.id','items.FrameMaterial')->where('QuotationId',$id)->where('VersionId',$version)->where('door_frame_construction.UserId',Auth::user()->id)->select('item_master.*','items.*','lipping_species.SpeciesName','door_frame_construction.Width','door_frame_construction.Height')->orderBy('items.itemId','ASC')->get();

        $currency = QuotationCurrency($quotation->Currency);
        $today = Carbon::now()->format('d-m-Y');
        $userName = Auth::user()->FirstName ." ".Auth::user()->LastName;
        $totDoorsetType = NumberOfDoorSets($version,$id);
        $totIronmongerySet = Item::where(['QuotationId' => $id,'VersionId'=>$version])->whereNotNull('IronmongeryID')->count();
        if(Auth::user()->UserType == 3){
            $users = User::where('UserType',3)->where('id',Auth::user()->id)->first();
            $ids = $users->CreatedBy;
        }else{
            $ids = Auth::user()->id;
        }
        
        $halflapedjoint = DoorFrameConstruction::where('UserId',$ids)->where('DoorFrameConstruction', 'Half_Lapped_Joint')->first();

        $pdf = PDF::loadView('DoorSchedule.FrameTransoms',['item' => $item, 'quotation' => $quotation, 'currency' => $currency, 'today' => $today, 'userName' => $userName, 'version' => $version, 'totDoorsetType' => $totDoorsetType, 'totIronmongerySet' => $totIronmongerySet, 'halflapedjoint' => $halflapedjoint]);
        return $pdf->download("BOM ".trim($quotation->QuotationGenerationId, "#")."-".$vid.".pdf");
    }

    public function GlassOrderSheet($id,string $vid,$version){
        ini_set('memory_limit', '500M');

        $quotation = Quotation::select('project.*','quotation.*','customers.CstCompanyName','project.ProjectName as projectname')->leftjoin('project','quotation.ProjectId','=','project.id')->leftjoin('customers','customers.UserId','quotation.MainContractorId')->where('quotation.id',$id)->first();

        $item = Item::Join('item_master','items.itemId','item_master.itemID')->leftjoin('lipping_species','lipping_species.id','items.FrameMaterial')->where('QuotationId',$id)->where('VersionId',$version)->select('item_master.*','items.*','lipping_species.SpeciesName')->orderBy('items.itemId','ASC')->get();

        $currency = QuotationCurrency($quotation->Currency);
        $today = Carbon::now()->format('d-m-Y');
        $userName = Auth::user()->FirstName ." ".Auth::user()->LastName;
        $totDoorsetType = NumberOfDoorSets($version,$id);
        $totIronmongerySet = Item::where(['QuotationId' => $id,'VersionId'=>$version])->whereNotNull('IronmongeryID')->count();

        $pdf = PDF::loadView('DoorSchedule.GlassOrderSheet',['item' => $item, 'quotation' => $quotation, 'currency' => $currency, 'today' => $today, 'userName' => $userName, 'version' => $version, 'totDoorsetType' => $totDoorsetType, 'totIronmongerySet' => $totIronmongerySet]);
        return $pdf->download("BOM ".trim($quotation->QuotationGenerationId, "#")."-".$vid.".pdf");
    }

    public function GlazingBeadsDoors($id,string $vid,$version){
        ini_set('memory_limit', '500M');

        $quotation = Quotation::select('project.*','quotation.*','customers.CstCompanyName','project.ProjectName as projectname')->leftjoin('project','quotation.ProjectId','=','project.id')->leftjoin('customers','customers.UserId','quotation.MainContractorId')->where('quotation.id',$id)->first();

        $item = Item::Join('item_master','items.itemId','item_master.itemID')->leftjoin('lipping_species','lipping_species.id','items.GlazingBeadSpecies')->where('QuotationId',$id)->where('VersionId',$version)->select('item_master.*','items.*','lipping_species.SpeciesName')->orderBy('items.itemId','ASC')->get();

        $currency = QuotationCurrency($quotation->Currency);
        $today = Carbon::now()->format('d-m-Y');
        $userName = Auth::user()->FirstName ." ".Auth::user()->LastName;
        $totDoorsetType = NumberOfDoorSets($version,$id);
        $totIronmongerySet = Item::where(['QuotationId' => $id,'VersionId'=>$version])->whereNotNull('IronmongeryID')->count();

        $pdf = PDF::loadView('DoorSchedule.GlazingBeadsDoors',['item' => $item, 'quotation' => $quotation, 'currency' => $currency, 'today' => $today, 'userName' => $userName, 'version' => $version, 'totDoorsetType' => $totDoorsetType, 'totIronmongerySet' => $totIronmongerySet]);
        return $pdf->download("BOM ".trim($quotation->QuotationGenerationId, "#")."-".$vid.".pdf");
    }

    public function QualityControlPrint($quatationId, string $versionID): void{
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '2048M');
        $result = BOMCAlculationExport($quatationId,$versionID);

        $quatationId = $quatationId;

        if(Auth::user()->UserType == 3){
            $users = User::where('UserType',3)->where('id',Auth::user()->id)->first();
            $id = $users->CreatedBy;
        }else{
            $id = Auth::user()->id;
        }
        
        $comapnyDetail = Company::where('UserId', $id)->first();
        $quotaion = Quotation::where('id', $quatationId)->first();
        $contractorName = DB::table('users')->where(['id' => $quotaion->MainContractorId, 'UserType' => 5 ])->value('FirstName');
        $contractorName = $contractorName ?: '';

        // $configurationItem = 1;
        $configurationItem = $quotaion->configurableitems;
        if (!empty($quotaion->configurableitems)) {
            $configurationItem = $quotaion->configurableitems;
        }

        $project = empty($quotaion->ProjectId) ? '' : Project::where('id', $quotaion->ProjectId)->first();
        
        $pdf_footer = SettingPDFfooter::where('UserId', $id)->first();

        $SalesContact = 'N/A';
        if (!empty($quotaion->SalesContact)) {
            $SalesContact = $quotaion->SalesContact;
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
            $contactid = explode(',',$quotaion_contact_info->Contact);
            $contact_persion = CustomerContact::where('id',$contactid[0])->first();
            $contactfirstandlastname = $contact_persion->FirstName . ' ' . $contact_persion->LastName;
        }
        else{
            $contactfirstandlastname = '';
        }
        
        $QuotationGenerationId = null;
            if (!empty($quotaion->QuotationGenerationId)) {
                $QuotationGenerationId = $quotaion->QuotationGenerationId;
            }

            $user = empty($quotaion->UserId) ? '' : User::where('id', $quotaion->CompanyUserId)->first();
            
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


            $data = BOMCalculation::join('items','items.itemId','bom_calculations.itemId')->select('bom_calculations.*','items.FireRating','items.Leaf1VPHeight1','items.Leaf1VPWidth','items.VisionPanelQuantity','items.GlassType','items.DoorLeafFacing','items.LeafConstruction','items.IntumescentLeafType')->where('bom_calculations.QuotationId',$quatationId)->get();
            $elevTbl = '';
            $elevTbl  = '
        <div id="headText" style="font-size:20px; text-align: center; font-weight: bold; margin-top:20px">
            <b>FRAMES</b>
        </div>
        <div id="main">
            <div id="section-left">
                <table style="width: 100%; border-collapse: collapse; font-size: 10px;">
                    <tr>
                        <td colspan="2">
                            <table style="width: 1400px; border: 1px solid black;border-collapse: collapse;">
                                <tbody>
                                    <tr>
                                        <td rowspan="2" style="border: 1px solid black; padding: 5px;">
                                            <span>';
    if (!empty($comapnyDetail->ComplogoBase64)) {
        $elevTbl .= '<img src="' . $comapnyDetail->ComplogoBase64 . '" width="50px" height="50px" alt="Logo"/>';
    } else {
        $elevTbl .= Base64Image('defaultImg');
    }
    
    $elevTbl .= '</span>
                                        </td>
                                        <td style="border: 1px solid black; padding: 5px;"><b>Ref</b></td>
                                        <td colspan="3" style="border: 1px solid black; padding: 5px;">' . $QuotationGenerationId . '</td>
                                        <td style="border: 1px solid black; padding: 5px;"><b>Project</b></td>
                                        <td style="border: 1px solid black; padding: 5px;">' . $ProjectName . '</td>
                                        <td style="border: 1px solid black; padding: 5px;"><b>Prepared By</b></td>
                                        <td style="border: 1px solid black; padding: 5px;">' . $Username . '</td>
                                    </tr>
                                    <tr>
                                        <td style="border: 1px solid black; padding: 5px;"><b>Revision</b></td>
                                        <td style="border: 1px solid black; padding: 5px;">' . $versionID . '</td>
                                        <td style="border: 1px solid black; padding: 5px;"><b>Date</b></td>
                                        <td style="border: 1px solid black; padding: 5px;">' . date('Y-m-d') . '</td>
                                        <td style="border: 1px solid black; padding: 5px;"><b>Customer</b></td>
                                        <td style="border: 1px solid black; padding: 5px;">' . $CstCompanyAddressLine1 . '</td>
                                        <td style="border: 1px solid black; padding: 5px;"><b>Sales Contact</b></td>
                                        <td style="border: 1px solid black; padding: 5px;">' . $SalesContact . '</td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </table>';

    $elevTbl .= '
        <table style="width: 1500px; border-collapse: collapse; font-size: 10px; margin-top: 10px; border: 1px solid black;">
            <thead>
                <tr style="background: #ddd; border: 1px solid black;">
                    <th style="border: 1px solid black; padding: 5px;">S.No</th>
                    <th style="border: 1px solid black; padding: 5px;">Door Type</th>
                    <th style="border: 1px solid black; padding: 5px;">Fire Rating</th>
                    <th style="border: 1px solid black; padding: 5px;">Frame Location</th>
                    <th style="border: 1px solid black; padding: 5px;">Frame Material/Finish</th>
                    <th style="border: 1px solid black; padding: 5px;">Frame Size</th>
                    <th style="border: 1px solid black; padding: 5px;">Frame Type</th>
                    <th style="border: 1px solid black; padding: 5px;">[Frame Type] Size</th>
                    <th style="border: 1px solid black; padding: 5px;">Qty Per Door Type</th>
                    <th style="border: 1px solid black; padding: 5px;">Quantity of door types</th>
                    <th style="border: 1px solid black; padding: 5px;">Unit</th>
                    <th style="border: 1px solid black; padding: 5px;">Good Inwards Check by;</th>
                    <th style="border: 1px solid black; padding: 5px;">Quality Check(Please Tick if Correct)</th>
                    <th style="border: 1px solid black; padding: 5px;">Please Insert Moisture Content And Report if Not Between 10% to 12%</th>
                    <th style="border: 1px solid black; padding: 5px;">Density Check (Please Tick 510kg/m3 FD30 & 640kg/m3 FD60)</th>
                    <th style="border: 1px solid black; padding: 5px;">Notes,Please any non conformainace of quantity issues</th>
                </tr>
            </thead>
            <tbody>';

    $i = 1;
    $PageBreakCount = 0;
    $TotalItems = count($data);
    $ItemsPerPage = 20;

    foreach ($data as $value) {
        if ($value->Category == 'Frame') {
            $words = explode("|", $value->Description);
            $PageBreakCount++;
            $elevTbl .= '<tr>
                <td style="border: 1px solid black; padding: 5px;">' . $i++ . '</td>
                <td style="border: 1px solid black; padding: 5px;">' . $value->DoorType . '</td>
                <td style="border: 1px solid black; padding: 5px;"> ' . $value->FireRating . '</td>
                <td style="border: 1px solid black; padding: 5px;">' . ($words[1] ?? '') . '</td>
                <td style="border: 1px solid black; padding: 5px;">' . ($words[2] ?? '') . '</td>
                <td style="border: 1px solid black; padding: 5px;">' . ($words[3] ?? '') . '</td>
                <td style="border: 1px solid black; padding: 5px;">' . ($words[4] ?? '') . '</td>
                <td style="border: 1px solid black; padding: 5px;">' . ($words[5] ?? '') . '</td>
                <td style="border: 1px solid black; padding: 5px;">' . $value->LMPerDoorType . '</td>
                <td style="border: 1px solid black; padding: 5px;">' . $value->QuantityOfDoorTypes . '</td>
                <td style="border: 1px solid black; padding: 5px;">' . $value->Unit . '</td>
                <td style="border: 1px solid black; padding: 5px;"></td>
                <td style="border: 1px solid black; padding: 5px;"></td>
                <td style="border: 1px solid black; padding: 5px;"></td>
                <td style="border: 1px solid black; padding: 5px;"></td>
                <td style="border: 1px solid black; padding: 5px;"></td>
            </tr>';
        }
    }

    $elevTbl .= '</tbody></table></div></div>';


                            // return view('Company.quality_control_pdf.FramesQualityControl', compact('elevTbl'));
        $pdf1 = PDF::loadView('Company.quality_control_pdf.FramesQualityControl', ['elevTbl' => $elevTbl]);
        $path1 = public_path() . '/qualitycontrolallpdf';
        $fileName1 = $id . '1' . '.' . 'pdf';
        $pdf1->save($path1 . '/' . $fileName1);

        // glazing beads
        $glazingTbl = '
                <div id="headText" style="font-size:20px; text-align: center; font-weight: bold; margin-top:20px">
                                        <b>GLAZING BEADS</b>
                                    </div>
                                    <div id="main">
                                        <div id="section-left">
                                            <table style="width: 100%; border-collapse: collapse; font-size: 10px;">
                                                <tr>
                                                    <td colspan="2">
                                                        <table style="width: 1400px; border: 1px solid black;border-collapse: collapse;">
                                                            <tbody>
                                                <tr>
                                                    <td rowspan="2" style="border: 1px solid black; padding: 5px;">
                                                        <span>';
                                                            if (!empty($comapnyDetail->ComplogoBase64)) {
                                                                $glazingTbl .= '<img src="' . $comapnyDetail->ComplogoBase64 . '" width="50px" height="50px" alt="Logo"/>';
                                                            } else {
                                                                $glazingTbl .= Base64Image('defaultImg');
                                                            }
                                                            
                                                            $glazingTbl .= '</span>



                                                    </td>
                                                    <td style="border: 1px solid black; padding: 5px;"><b>Ref</b></td>
                                                    <td colspan="3" style="border: 1px solid black; padding: 5px;">' . $QuotationGenerationId . '</td>
                                                    <td style="border: 1px solid black; padding: 5px;"><b>Project</b></td>
                                                    <td style="border: 1px solid black; padding: 5px;">' . $ProjectName . '</td>
                                                    <td style="border: 1px solid black; padding: 5px;"><b>Prepared By</b></td>
                                                    <td style="border: 1px solid black; padding: 5px;">' . $Username . '</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid black; padding: 5px;"><b>Revision</b></td>
                                                    <td style="border: 1px solid black; padding: 5px;">' . $versionID . '</td>
                                                    <td style="border: 1px solid black; padding: 5px;"><b>Date</b></td>
                                                    <td style="border: 1px solid black; padding: 5px;">' . date('Y-m-d') . '</td>
                                                    <td style="border: 1px solid black; padding: 5px;"><b>Customer</b></td>
                                                    <td style="border: 1px solid black; padding: 5px;">' . $CstCompanyAddressLine1 . '</td>
                                                    <td style="border: 1px solid black; padding: 5px;"><b>Sales Contact</b></td>
                                                    <td style="border: 1px solid black; padding: 5px;">' . $SalesContact . '</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>';

                $glazingTbl .= '
                    <table style="width: 1500px; border-collapse: collapse; font-size: 10px; margin-top: 10px; border: 1px solid black;">
                        <thead>
                            <tr style="background: #ddd; border: 1px solid black;">
                                <th style="border: 1px solid black; padding: 5px;">S.No</th>
                                <th style="border: 1px solid black; padding: 5px;">Door Type</th>
                                <th style="border: 1px solid black; padding: 5px;">Fire Rating</th>
                                <th style="border: 1px solid black; padding: 5px;">Glazing Beads</th>
                                <th style="border: 1px solid black; padding: 5px;">Glazing Bead Species</th>
                                <th style="border: 1px solid black; padding: 5px;">Finish</th>
                                <th style="border: 1px solid black; padding: 5px;">Glazing Bead Dimensions</th>
                                <th style="border: 1px solid black; padding: 5px;">Vision Panel Dimensions</th>
                                <th style="border: 1px solid black; padding: 5px;">Qty Per Door Type</th>
                                <th style="border: 1px solid black; padding: 5px;">Quantity of door types</th>
                                <th style="border: 1px solid black; padding: 5px;">Unit</th>
                                <th style="border: 1px solid black; padding: 5px;">Good Inwards Check by;</th>
                                <th style="border: 1px solid black; padding: 5px;">Quality Check(Please Tick if Correct)</th>
                                <th style="border: 1px solid black; padding: 5px;">Please Insert Moisture Content And Report if Not Between 10% to 12%</th>
                                <th style="border: 1px solid black; padding: 5px;">Density Check (Please Tick 510kg/m3 FD30 & 640kg/m3 FD60)</th>
                                <th style="border: 1px solid black; padding: 5px;">Notes,Please any non conformainace of quantity issues</th>
                            </tr>
                        </thead>
                        <tbody>';

                $i = 1;
                $PageBreakCount = 0;
                $TotalItems = count($data);
                $ItemsPerPage = 20;

                foreach ($data as $value) {
                    if ($value->Category == 'GlazingBeads') {
                        $words = explode("|", $value->Description);
                        $PageBreakCount++;
                        $glazingTbl .= '<tr>
                            <td style="border: 1px solid black; padding: 5px;">' . $i++ . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . $value->DoorType . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . $value->FireRating . ' </td>
                            <td style="border: 1px solid black; padding: 5px;">' . ($words[1] ?? '') . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . ($words[2] ?? '') . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . ($words[3] ?? '') . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . ($words[4] ?? '') . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . ($words[5] ?? '') . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . $value->LMPerDoorType . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . $value->QuantityOfDoorTypes . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . $value->Unit . '</td>
                            <td style="border: 1px solid black; padding: 5px;"></td>
                            <td style="border: 1px solid black; padding: 5px;"></td>
                            <td style="border: 1px solid black; padding: 5px;"></td>
                            <td style="border: 1px solid black; padding: 5px;"></td>
                            <td style="border: 1px solid black; padding: 5px;"></td>
                        </tr>';
                    }
                }

            $glazingTbl .= '</tbody></table></div></div>';



                            // return view('Company.quality_control_pdf.GlazingBeadsQualityControl', compact('glazingTbl'));
        $pdf2 = PDF::loadView('Company.quality_control_pdf.GlazingBeadsQualityControl', ['glazingTbl' => $glazingTbl]);
        $path2 = public_path() . '/qualitycontrolallpdf';
        $fileName2 = $id . '2' . '.' . 'pdf';
        $pdf2->save($path2 . '/' . $fileName2);
        // end

         // Liping Species
         $lipingTbl = '';
         $lipingTbl = '
                <div id="headText" style="font-size:20px; text-align: center; font-weight: bold; margin-top:20px">
                                        <b>DOOR DETAILS</b>
                                    </div>
                                    <div id="main">
                                        <div id="section-left">
                                            <table style="width: 100%; border-collapse: collapse; font-size: 10px;">
                                                <tr>
                                                    <td colspan="2">
                                                        <table style="width: 1400px; border: 1px solid black;border-collapse: collapse;">
                                                            <tbody>
                                                <tr>
                                                    <td rowspan="2" style="border: 1px solid black; padding: 5px;">
                                                        <span>';
                                                            if (!empty($comapnyDetail->ComplogoBase64)) {
                                                                $lipingTbl .= '<img src="' . $comapnyDetail->ComplogoBase64 . '" width="50px" height="50px" alt="Logo"/>';
                                                            } else {
                                                                $lipingTbl .= Base64Image('defaultImg');
                                                            }
                                                            
                                                            $lipingTbl .= '</span>



                                                    </td>
                                                    <td style="border: 1px solid black; padding: 5px;"><b>Ref</b></td>
                                                    <td colspan="3" style="border: 1px solid black; padding: 5px;">' . $QuotationGenerationId . '</td>
                                                    <td style="border: 1px solid black; padding: 5px;"><b>Project</b></td>
                                                    <td style="border: 1px solid black; padding: 5px;">' . $ProjectName . '</td>
                                                    <td style="border: 1px solid black; padding: 5px;"><b>Prepared By</b></td>
                                                    <td style="border: 1px solid black; padding: 5px;">' . $Username . '</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid black; padding: 5px;"><b>Revision</b></td>
                                                    <td style="border: 1px solid black; padding: 5px;">' . $versionID . '</td>
                                                    <td style="border: 1px solid black; padding: 5px;"><b>Date</b></td>
                                                    <td style="border: 1px solid black; padding: 5px;">' . date('Y-m-d') . '</td>
                                                    <td style="border: 1px solid black; padding: 5px;"><b>Customer</b></td>
                                                    <td style="border: 1px solid black; padding: 5px;">' . $CstCompanyAddressLine1 . '</td>
                                                    <td style="border: 1px solid black; padding: 5px;"><b>Sales Contact</b></td>
                                                    <td style="border: 1px solid black; padding: 5px;">' . $SalesContact . '</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>';
                if($quotaion->configurableitems == 4 || $quotaion->configurableitems == 5){
                    $lipingTbl .= '
                    <table style="width: 1500px; border-collapse: collapse; font-size: 10px; margin-top: 10px; border: 1px solid black;">
                        <thead>
                            <tr style="background: #ddd; border: 1px solid black;">
                                <th style="border: 1px solid black; padding: 5px;">S.No</th>
                                <th style="border: 1px solid black; padding: 5px;">Door Type</th>
                                <th style="border: 1px solid black; padding: 5px;">Fire Rating</th>
                                <th style="border: 1px solid black; padding: 5px;">Door Core</th>
                                <th style="border: 1px solid black; padding: 5px;">Liping Type</th>
                                <th style="border: 1px solid black; padding: 5px;">Liping Thickness/Liping Species</th>
                                <th style="border: 1px solid black; padding: 5px;">Leaf Type</th>
                                <th style="border: 1px solid black; padding: 5px;">Door Leaf Facing</th>
                                <th style="border: 1px solid black; padding: 5px;">Door Leaf Size</th>
                                <th style="border: 1px solid black; padding: 5px;">Total Quantity</th>
                                <th style="border: 1px solid black; padding: 5px;">Unit</th>
                                <th style="border: 1px solid black; padding: 5px;">Good Inwards Check by;</th>
                                <th style="border: 1px solid black; padding: 5px;">Quality Check(Please Tick if Correct)</th>
                                <th style="border: 1px solid black; padding: 5px;">Door Thickness (mm)</th>
                                <th style="border: 1px solid black; padding: 5px;">Please Insert Moisture Content And Report if Not Between 10% to 12%</th>
                                <th style="border: 1px solid black; padding: 5px;">Density Check (Please Tick 510kg/m3 FD30 & 640kg/m3 FD60)</th>
                                <th style="border: 1px solid black; padding: 5px;">Notes,Please any non conformainace of quantity issues</th>
                            </tr>
                        </thead>
                        <tbody>';
                }
                else{
                    $lipingTbl .= '
                    <table style="width: 1500px; border-collapse: collapse; font-size: 10px; margin-top: 10px; border: 1px solid black;">
                        <thead>
                            <tr style="background: #ddd; border: 1px solid black;">
                                <th style="border: 1px solid black; padding: 5px;">S.No</th>
                                <th style="border: 1px solid black; padding: 5px;">Door Type</th>
                                <th style="border: 1px solid black; padding: 5px;">Fire Rating</th>
                                <th style="border: 1px solid black; padding: 5px;">Door Core</th>
                                <th style="border: 1px solid black; padding: 5px;">Liping Type</th>
                                <th style="border: 1px solid black; padding: 5px;">Liping Thickness</th>
                                <th style="border: 1px solid black; padding: 5px;">Liping Species</th>
                                <th style="border: 1px solid black; padding: 5px;">Leaf Type</th>
                                <th style="border: 1px solid black; padding: 5px;">Door Leaf Facing</th>
                                <th style="border: 1px solid black; padding: 5px;">Door Leaf Size</th>
                                <th style="border: 1px solid black; padding: 5px;">Total Quantity</th>
                                <th style="border: 1px solid black; padding: 5px;">Unit</th>
                                <th style="border: 1px solid black; padding: 5px;">Good Inwards Check by;</th>
                                <th style="border: 1px solid black; padding: 5px;">Quality Check(Please Tick if Correct)</th>
                                <th style="border: 1px solid black; padding: 5px;">Door Thickness (mm)</th>
                                <th style="border: 1px solid black; padding: 5px;">Please Insert Moisture Content And Report if Not Between 10% to 12%</th>
                                <th style="border: 1px solid black; padding: 5px;">Density Check (Please Tick 510kg/m3 FD30 & 640kg/m3 FD60)</th>
                                <th style="border: 1px solid black; padding: 5px;">Notes,Please any non conformainace of quantity issues</th>
                            </tr>
                        </thead>
                        <tbody>';
                }


                $i = 1;
                $PageBreakCount = 0;
                $TotalItems = count($data);
                $ItemsPerPage = 20;

                foreach ($data as $value) {
                    if ($value->Category == 'LeafSetBesPoke') {

                        $words = explode("|", $value->Description);
                        // dd($words);
                        $PageBreakCount++;
                        if($quotaion->configurableitems == 4 || $quotaion->configurableitems == 5){
                            $lipingTbl .= '<tr>
                            <td style="border: 1px solid black; padding: 5px;">' . $i++ . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . $value->DoorType . '</td>
                            <td style="border: 1px solid black; padding: 5px;"> ' . $value->FireRating . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . ($words[1] ?? '') . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . ($words[2] ?? '') . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . ($words[3] ?? '') . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . $value->LeafConstruction . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . $value->DoorLeafFacing . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . ($words[4] ?? '') . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . $value->QuantityOfDoorTypes . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . $value->Unit . '</td>
                            <td style="border: 1px solid black; padding: 5px;"></td>
                            <td style="border: 1px solid black; padding: 5px;"></td>
                            <td style="border: 1px solid black; padding: 5px;"></td>
                            <td style="border: 1px solid black; padding: 5px;"></td>
                            <td style="border: 1px solid black; padding: 5px;"></td>
                            <td style="border: 1px solid black; padding: 5px;"></td>

                        </tr>';
                        }
                        else{
                            $getLeaf = IntumescentSealLeafType::where('id',$value->IntumescentLeafType)->select('leaf_type_key')->first();
                            $lipingTbl .= '<tr>
                            <td style="border: 1px solid black; padding: 5px;">' . $i++ . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . $value->DoorType . '</td>
                            <td style="border: 1px solid black; padding: 5px;"> ' . $value->FireRating . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . ($words[1] ?? '') . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . ($words[2] ?? '') . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . ($words[3] ?? '') . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . ($words[4] ?? '') . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . $getLeaf->leaf_type_key . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . $value->DoorLeafFacing . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . ($words[5] ?? '') . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . $value->QuantityOfDoorTypes . '</td>
                            <td style="border: 1px solid black; padding: 5px;">' . $value->Unit . '</td>
                            <td style="border: 1px solid black; padding: 5px;"></td>
                            <td style="border: 1px solid black; padding: 5px;"></td>
                            <td style="border: 1px solid black; padding: 5px;"></td>
                            <td style="border: 1px solid black; padding: 5px;"></td>
                            <td style="border: 1px solid black; padding: 5px;"></td>
                            <td style="border: 1px solid black; padding: 5px;"></td>

                        </tr>';
                        }

                    }
                }

            $lipingTbl .= '</tbody></table></div></div>';


                            //  return view('Company.quality_control_pdf.FramesQualityControl', compact('elevTbl'));
         $pdf3 = PDF::loadView('Company.quality_control_pdf.LippingQualityControl', ['lipingTbl' => $lipingTbl]);
         $path3 = public_path() . '/qualitycontrolallpdf';
         $fileName3 = $id . '3' . '.' . 'pdf';
         $pdf3->save($path3 . '/' . $fileName3);
        // end

        // Glass

            $glassTbl = '';
            $glassTbl = '
                <div id="headText" style="font-size:20px; text-align: center; font-weight: bold; margin-top:20px">
                                        <b>GLASS</b>
                                    </div>
                                    <div id="main">
                                        <div id="section-left">
                                            <table style="width: 100%; border-collapse: collapse; font-size: 10px;">
                                                <tr>
                                                    <td colspan="2">
                                                        <table style="width: 1400px; border: 1px solid black;border-collapse: collapse;">
                                                            <tbody>
                                                <tr>
                                                    <td rowspan="2" style="border: 1px solid black; padding: 5px;">
                                                        <span>';
                                                            if (!empty($comapnyDetail->ComplogoBase64)) {
                                                                $glassTbl .= '<img src="' . $comapnyDetail->ComplogoBase64 . '" width="50px" height="50px" alt="Logo"/>';
                                                            } else {
                                                                $glassTbl .= Base64Image('defaultImg');
                                                            }
                                                            
                                                            $glassTbl .= '</span>



                                                    </td>
                                                    <td style="border: 1px solid black; padding: 5px;"><b>Ref</b></td>
                                                    <td colspan="3" style="border: 1px solid black; padding: 5px;">' . $QuotationGenerationId . '</td>
                                                    <td style="border: 1px solid black; padding: 5px;"><b>Project</b></td>
                                                    <td style="border: 1px solid black; padding: 5px;">' . $ProjectName . '</td>
                                                    <td style="border: 1px solid black; padding: 5px;"><b>Prepared By</b></td>
                                                    <td style="border: 1px solid black; padding: 5px;">' . $Username . '</td>
                                                </tr>
                                                <tr>
                                                    <td style="border: 1px solid black; padding: 5px;"><b>Revision</b></td>
                                                    <td style="border: 1px solid black; padding: 5px;">' . $versionID . '</td>
                                                    <td style="border: 1px solid black; padding: 5px;"><b>Date</b></td>
                                                    <td style="border: 1px solid black; padding: 5px;">' . date('Y-m-d') . '</td>
                                                    <td style="border: 1px solid black; padding: 5px;"><b>Customer</b></td>
                                                    <td style="border: 1px solid black; padding: 5px;">' . $CstCompanyAddressLine1 . '</td>
                                                    <td style="border: 1px solid black; padding: 5px;"><b>Sales Contact</b></td>
                                                    <td style="border: 1px solid black; padding: 5px;">' . $SalesContact . '</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                            </table>';

                $glassTbl .= '
                    <table style="width: 1500px; border-collapse: collapse; font-size: 10px; margin-top: 10px; border: 1px solid black;">
                        <thead>
                            <tr style="background: #ddd; border: 1px solid black;">
                                <th style="border: 1px solid black; padding: 5px;">S.No</th>
                                <th style="border: 1px solid black; padding: 5px;">Door Type</th>
                                <th style="border: 1px solid black; padding: 5px;">Fire Rating</th>
                                <th style="border: 1px solid black; padding: 5px;">Glass Type</th>
                                <th style="border: 1px solid black; padding: 5px;">Vision Panel Size</th>
                                <th style="border: 1px solid black; padding: 5px;">M2 Per Door Type</th>
                                <th style="border: 1px solid black; padding: 5px;">Quantity of door types</th>
                                <th style="border: 1px solid black; padding: 5px;">Good Inwards Check by;</th>
                                <th style="border: 1px solid black; padding: 5px;">Glass Width Check</th>
                                <th style="border: 1px solid black; padding: 5px;">Glass Height Check</th>
                                <th style="border: 1px solid black; padding: 5px;">Glass Thickness Check</th>
                                <th style="border: 1px solid black; padding: 5px;">Glass Stamp Check</th>
                                <th style="border: 1px solid black; padding: 5px;">Notes,Please note any non conformiance or quantity issues.</th>
                            </tr>
                        </thead>
                        <tbody>';

                $i = 1;
                $PageBreakCount = 0;
                $TotalItems = count($data);
                $ItemsPerPage = 20;

                foreach ($data as $value) {
                    if ($value->Category == 'Glass') {
                        $words = explode("|", $value->Description);
                        $PageBreakCount++;
                        $glassTbl .= '<tr>
                            <td style="border: 1px solid black; padding: 5px;">' .$i++. '</td>
                            <td style="border: 1px solid black; padding: 5px;">' .$value->DoorType. '</td>
                            <td style="border: 1px solid black; padding: 5px;"> ' . $value->FireRating . '</td>
                            <td style="border: 1px solid black; padding: 5px;">'. (isset($words[1]) ? str_replace('_', ' ',  $words[1]) : '').'</td>
                            <td style="border: 1px solid black; padding: 5px;">'. ($words[2] ?? '').'</td>
                            <td style="border: 1px solid black; padding: 5px;">'. $value->LMPerDoorType .'</td>
                            <td style="border: 1px solid black; padding: 5px;">'. $value->QuantityOfDoorTypes.'</td>
                            <td style="border: 1px solid black; padding: 5px;"></td>
                            <td style="border: 1px solid black; padding: 5px;"></td>
                            <td style="border: 1px solid black; padding: 5px;"></td>
                            <td style="border: 1px solid black; padding: 5px;"></td>
                            <td style="border: 1px solid black; padding: 5px;"></td>
                            <td style="border: 1px solid black; padding: 5px;"></td>
                        </tr>';
                    }
                }

            $glassTbl .= '</tbody></table></div></div>';


                           //  return view('Company.quality_control_pdf.FramesQualityControl', compact('elevTbl'));
            $pdf4 = PDF::loadView('Company.quality_control_pdf.GlassQualityControl', ['glassTbl' => $glassTbl]);
            $path4 = public_path() . '/qualitycontrolallpdf';
            $fileName4 = $id . '4' . '.' . 'pdf';
            $pdf4->save($path4 . '/' . $fileName4);
        // end

        // Summary
        $summaryTbl = '';
        $summaryTbl = '
                    <div id="headText" style="font-size:20px; text-align: center; font-weight: bold; margin-top:20px">
                        <b>Quality Control Summary</b>
                    </div>
                    <div id="main">
                        <div id="section-left">';
        $summaryTbl .= '    <table style="width: 1500px; border-collapse: collapse; font-size: 10px; margin-top: 10px; border: 1px solid black;">
                                <thead>
                                    <tr style="background-color: #00b0f0;">
                                     <th style="text-align: center;padding: 5px;width: 100%;font-size: 14px;font-weight: bold;" colspan="7">Frame</th>
                                    </tr>

                                    <tr style="font-size: 12px; border: 1px solid black;">
                                        <th style="border: 1px solid black; padding: 5px;">Qty</th>
                                        <th style="border: 1px solid black; padding: 5px;">Frame Location</th>
                                        <th style="border: 1px solid black; padding: 5px;">Frame Material Finish</th>
                                        <th style="border: 1px solid black; padding: 5px;">Frame Size</th>
                                        <th style="border: 1px solid black; padding: 5px;">Frame Type</th>
                                        <th style="border: 1px solid black; padding: 5px;">Frame Type Size</th>
                                        <th style="border: 1px solid black; padding: 5px;">Qty Meters</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                    foreach ($data as $value) {
                                        if ($value->Category == 'Frame') {
                                            $parts = explode("|", $value->Description);
                                            $frameLocation = trim($parts[1]);
        $summaryTbl .=                      '<tr>
                                                <td style="border: 1px solid black; padding: 5px;">' . $value->QuantityOfDoorTypes . '</td>
                                                <td style="border: 1px solid black; padding: 5px;">' .  $frameLocation . '</td>
                                                <td style="border: 1px solid black; padding: 5px;"> ' . trim($parts[2]) . '</td>
                                                <td style="border: 1px solid black; padding: 5px;">' .  trim($parts[3]) . '</td>
                                                <td style="border: 1px solid black; padding: 5px;">' . (isset($parts[4]) ? trim($parts[4]) : '-') . '</td>
                                                <td style="border: 1px solid black; padding: 5px;">' . (isset($parts[5]) ? trim($parts[5]) : '-') . '</td>
                                                <td style="border: 1px solid black; padding: 5px;">' . $value->LMPerDoorType . '</td>
                                            </tr>';
                                        }
                                    }
                                    
        $summaryTbl .= '        </tbody>
                            </table>';
        $summaryTbl .= '    <table style="width: 1500px; border-collapse: collapse; font-size: 10px; margin-top: 20px; border: 1px solid black;">
                                <thead>
                                    <tr style="background-color: #00b0f0;">
                                        <th style="text-align: center;padding: 5px;width: 100%;font-size: 14px;font-weight: bold;" colspan="5">Door Details</th>
                                    </tr>
                                    <tr style="font: size 12px; border: 1px solid black;">
                                        <th style="border: 1px solid black; padding: 5px;">Qty</th>
                                        <th style="border: 1px solid black; padding: 5px;">Door Core</th>
                                        <th style="border: 1px solid black; padding: 5px;">Leaf Type</th>
                                        <th style="border: 1px solid black; padding: 5px;">Door Leaf Facing</th>
                                        <th style="border: 1px solid black; padding: 5px;">Door Leaf Size</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                    foreach ($data as $value) {
                                        if ($value->Category == 'LeafSetBesPoke') {
                                            $parts = explode("|", $value->Description);
                                            $doorCore = trim($parts[1]); // Door Core (e.g., Halspan)
                                            if($quotaion->configurableitems == 4 || $quotaion->configurableitems == 5){
                                                $leafType =  $value->LeafConstruction;;
                                            } else {
                                                $getLeaf = IntumescentSealLeafType::where('id',$value->IntumescentLeafType)->select('leaf_type_key')->first();
                                                $leafType = $getLeaf->leaf_type_key;
                                            }
                                            
                                            $doorLeafFacing = $value->DoorLeafFacing;
                                            $doorLeafSize = trim($parts[5]);
        $summaryTbl .=                      '<tr>
                                                <td style="border: 1px solid black; padding: 5px;">' . $value->QuantityOfDoorTypes . '</td>
                                                <td style="border: 1px solid black; padding: 5px;">' . $doorCore . '</td>
                                                <td style="border: 1px solid black; padding: 5px;">' . $leafType . '</td>
                                                <td style="border: 1px solid black; padding: 5px;">' . $doorLeafFacing . '</td>
                                                <td style="border: 1px solid black; padding: 5px;">' . $doorLeafSize . '</td>
                                            </tr>';
                                        }
                                    }
                                    
        $summaryTbl .=          '</tbody>
                            </table>';
        $summaryTbl .= '    <table style="width: 1500px; border-collapse: collapse; font-size: 10px; margin-top: 20px; border: 1px solid black;">
                                <thead>
                                    <tr style="background-color: #00b0f0;">
                                        <th style="text-align: center;padding: 5px;width: 100%;font-size: 14px;font-weight: bold;" colspan="5">Glass</th>
                                    </tr>
                                    <tr style="font: size 12px; border: 1px solid black;">
                                        <th style="border: 1px solid black; padding: 5px;">Qty</th>
                                        <th style="border: 1px solid black; padding: 5px;">Glass Type</th>
                                        <th style="border: 1px solid black; padding: 5px;">Vp Size</th>
                                        <th style="border: 1px solid black; padding: 5px;">CUT Height BOTTOM PANEL</th>
                                        <th style="border: 1px solid black; padding: 5px;">CUT WIDTH BOTTOM PANEL</th>
                                    </tr>
                                </thead>
                                <tbody>';
                                    foreach ($data as $value) {
                                        if ($value->Category == 'Glass') {
                                            $parts = explode('|', $value->Description);
                                            if($value->FireRating == 'NFR' || $value->FireRating == 'FD30s' || $value->FireRating == 'FD30'){
                                                $wdth = 5;
                                            }elseif($value->FireRating == 'FD60s' || $value->FireRating == 'FD60'){
                                                $wdth = 8;
                                            }else{
                                                $wdth = 0;
                                            }
                                            
                                            $glassType = trim($parts[1]); // Extract Glass Type
                                            $cutheght = $value->FireRating == 'FD60s' || $value->FireRating == 'FD60' ? $value->Leaf1VPHeight1 - 8 : $value->Leaf1VPHeight1 - 5;
                                            $cutwidth = $value->Leaf1VPWidth - $wdth;
                                            $vpSize = trim($parts[2]);

        $summaryTbl .=                  '<tr>
                                            <td style="border: 1px solid black; padding: 5px;">' . $value->QuantityOfDoorTypes . '</td>
                                            <td style="border: 1px solid black; padding: 5px;">' . (str_replace('_', ' ', $value->GlassType)) . '</td>
                                            <td style="border: 1px solid black; padding: 5px;">' . $vpSize . '</td>
                                            <td style="border: 1px solid black; padding: 5px;">' . $cutheght . '</td>
                                            <td style="border: 1px solid black; padding: 5px;">' . $cutwidth . '</td>
                                        </tr>';

                                            // Group by Glass Type and VP Size
                                            $key = $glassType;
                                            if (!isset($data3[$key])) {
                                                $data3[$key] = [
                                                    'Qty' => $value->QuantityOfDoorTypes,
                                                    'Glass_Type' => str_replace('_', ' ', $value->GlassType),
                                                    'cutheght' => $cutheght,
                                                    'cutwidth' => $cutwidth,
                                                    'VP_Size' => $vpSize
                                                ];
                                            }
                                        }
                                    }
                                    
        $summaryTbl .=          '</tbody>
                            </table>';
                        '</div>
                    </div>';

        // return view('Company.quality_control_pdf.SummaryQualityControl', compact('summaryTbl'));
        $pdf5 = PDF::loadView('Company.quality_control_pdf.SummaryQualityControl', ['summaryTbl' => $summaryTbl]);
        $path5 = public_path() . '/qualitycontrolallpdf';
        $fileName5 = $id . '5' . '.' . 'pdf';
        $pdf5->save($path5 . '/' . $fileName5);
        // end

        $PDFfilename = public_path() . '/qualitycontrolallpdf' . '/' . $quotaion->QuotationGenerationId . '_' . $versionID . '.pdf';
        $pdfFiles = [
                public_path() . '/qualitycontrolallpdf' . '/' . $fileName1,
                public_path() . '/qualitycontrolallpdf' . '/' . $fileName2,
                public_path() . '/qualitycontrolallpdf' . '/' . $fileName3,
                public_path() . '/qualitycontrolallpdf' . '/' . $fileName4,
                public_path() . '/qualitycontrolallpdf' . '/' . $fileName5
            ];

            // Merge the PDF files using PDFMerger
            $pdfMerger = PDFMerger::init();
            foreach ($pdfFiles as $pdfFile) {
                $pdfMerger->addPDF($pdfFile, 'all');
            }
            
            $mergedFilePath = public_path() . '/qualitycontrolallpdf/' . $quotaion->QuotationGenerationId . '_' . $versionID . '.pdf';
            $pdfMerger->merge();
            $pdfMerger->save($mergedFilePath);
            $pdfMerger->save(public_path().'/quotationFiles'.'/'.$quotaion->QuotationGenerationId.'_'.$versionID.'.pdf');

            $pdf = new Fpdi();
            $pageCount = $pdf->setSourceFile($mergedFilePath);

            // Set margins
            $pdf->SetMargins(10, 10, 10);
            $pdf->SetAutoPageBreak(true, 10);

            // Disable header and footer completely
            $pdf->setPrintHeader(false);
            $pdf->setPrintFooter(true);

            for ($pageNo = 1; $pageNo <= $pageCount; $pageNo++) {

                $tplId = $pdf->importPage($pageNo);
                $pdf->AddPage();
                $pdf->useTemplate($tplId, ['adjustPageSize' => true]);

                // Add page number at bottom center
                $pdf->SetY(-20);
                $pdf->Cell(0, 10, 'Page ' . $pageNo . '/' . $pageCount, 0, 0, 'C');
            }

            // Save the final PDF with page numbers
            $outputPath = $PDFfilename;
            $pdf->Output($outputPath, 'F');

            $pdf->Output($quotaion->QuotationGenerationId . '.pdf', 'D');

            $quo = Quotation::find($quatationId);
            $quo->quotTag = 1;
            $quo->save();

            // unlink($mergedFilePath); (27-11-2024 comment these code bcs it deleted the file to the system and getting 404 not found when send to client the quotations.)

            foreach ($pdfFiles as $unlinkPath) {
                unlink($unlinkPath);
            }
    }
}
