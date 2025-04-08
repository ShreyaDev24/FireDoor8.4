<?php

namespace App\Http\Controllers\setting;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

use App\Models\BOMSetting;
use App\Models\SettingBOMCost;
use App\Models\BomGeneralLabourCost;
use App\Models\GeneralLabourData;
use App\Models\SelectedGeneralLabourCost;
use App\Models\GeneralLabourCostSetting;

class BuildOfMaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function settingbuildofmaterial()
    {
        if(Auth::user()->UserType== 2){
            $cid = [Auth::user()->id];
        }else{
            $cid = [Auth::user()->id];
        }
        
        $set = BOMSetting::wherein('UserId',$cid)->orderBy('id','desc')->first();
        return view('Setting.settingbuildofmaterial',['set' => $set]);
    }
    
    public function subsettingbuildofmaterial(Request $request)
    {
        if(Auth::user()->UserType== 2){
            $cid = [Auth::user()->id];
            BOMSetting::wherein('UserId', $cid)->delete();
        }else{
            $cid = [Auth::user()->id];
        }
        
        $update_val = $request->updval;
        foreach ($cid as $key => $usr) {
            if(!is_null($update_val) && Auth::user()->UserType != 2){
                $a = BOMSetting::find($update_val);
            } else {
                $a = new BOMSetting;
                $a->created_at = date('Y-m-d H:i:s');
            }
            
            // $cid = Auth::user()->id;

            $a->UserId = $usr;
            $a->MarginMarkup = $request->MarginMarkup;
            $a->labour_cost_per_man = $request->labour_cost_per_man;
            $a->labour_cost_per_machine = $request->labour_cost_per_machine;
            $a->margin_for_material = $request->margin_for_material;
            $a->margin_for_labour = $request->margin_for_labour;
            $a->markup_for_material = $request->markup_for_material;
            $a->markup_for_labour = $request->markup_for_labour;
            $a->cost_of_lipping_44mm = $request->cost_of_lipping_44mm;
            $a->cost_of_lipping_54mm = $request->cost_of_lipping_54mm;
            $a->lipping_time = $request->lipping_time;
            $a->cutting_door_core = $request->cutting_door_core;
            $a->pressing_door = $request->pressing_door;
            $a->machine_of_frame = $request->machine_of_frame;
            $a->machine_of_architrave = $request->machine_of_architrave;
            $a->updated_at = date('Y-m-d H:i:s');
            $a->save();
        }
        
        if(!is_null($update_val)){
            return redirect()->back()->with('success', 'The general setting update successfully!');
        }
        else
        {
            return redirect()->back()->withInput()->with('success', 'The general setting added successfully!');
        }
    }

    public function costsetting()
    {
        $cid = Auth::user()->id;
        $rr = SettingBOMCost::where('UserId',$cid)->orderBy('name')->get();
        $i = 1;
        $tbl = '';
        foreach($rr as $tt){
            $tbl .=
            '
            <tr>
                <td>'.$i.'</td>
                <td>'.$tt->name.'</td>
                <td>'.$tt->parent.'</td>
                <td>'.$tt->width.'</td>
                <td>'.$tt->height.'</td>
                <td>'.$tt->depth.'</td>
                <td>'.$tt->cost.'</td>
                <td>
                    <span style="display: flex;">
                        <form action="'.route('updcostsetting').'" method="post">
                            <input type="hidden" name="_token" value="'.csrf_token().'">
                            <button type="submit" name="upd" value="'.$tt->id.'" class="btn btn-success">
                                <i class="fa fa-edit"></i>
                            </button>
                        </form>
                        <form action="'.route('deletecostsetting').'" method="post">
                            <input type="hidden" name="_token" value="'.csrf_token().'">
                            <button type="submit" name="delete" value="'.$tt->id.'" onClick="return confirm(\'Are you sure, you want to delete?\')" class="btn btn-danger" style="margin-left: 5px;">
                                <i class="fa fa-trash"></i>
                            </button>
                        </form>
                    </span>
                </td>
            </tr>
            ';
            $i++;
        }
        
        return view('Setting.costsetting',['tbl' => $tbl]);
    }

    public function general_labour_cost_setting()
    {
        $cid = [Auth::user()->id];

        // $cid = Auth::user()->id;
        $set = BomGeneralLabourCost::with(['genLaborCost','genLaborCost.laborBomSettings'])->select('general_labour_cost.*','selected_generallabourcost.*','selected_generallabourcost.id as generalId','general_labour_cost.id')->join('selected_generallabourcost','selected_generallabourcost.generalLabourCostId','=','general_labour_cost.id')->wherein('general_labour_cost.UserId',$cid)->orderBy('general_labour_cost.id','desc')->first();

        $bomSetting = BOMSetting::wherein('UserId',$cid)->orderBy('id','desc')->first();

        return view('Setting.generalLabourcost',['set' => $set, 'bomSetting' => $bomSetting]);
    }
    
    public function general_labour_cost_sub_setting(Request $request)
    {
        if(Auth::user()->UserType== 2){
            $cid = [Auth::user()->id];
            BomGeneralLabourCost::wherein('UserId', $cid)->delete();
            SelectedGeneralLabourCost::wherein('user_Id', $cid)->delete();
            $bs = GeneralLabourCostSetting::wherein('UserId', $cid);
            $bsIds = $bs->pluck("id")->toArray();
            $bs->delete();
            GeneralLabourData::wherein('bom_setting_id', $bsIds)->delete();
        }else{
            $cid = [Auth::user()->id];
        }
        
        // dd($$request);
        $update_val = $request->updval;
        $selectedgeneralId = $request->selectedgeneralId;

        foreach ($cid as $usr) {
            if(!is_null($update_val) && !is_null($selectedgeneralId) && Auth::user()->UserType != 2){
                $a = BomGeneralLabourCost::find($update_val);
                $b = SelectedGeneralLabourCost::find($selectedgeneralId);
            } else {
                $a = new BomGeneralLabourCost();
                $b = new SelectedGeneralLabourCost();
                $a->created_at = date('Y-m-d H:i:s');
            }

            $checkId = ['DoorLeafFacingVaneer', 'DoorLeafFacingKraftPaper', 'DoorLeafFacingLaminate', 'DoorLeafFacingPVC', 'DoorLeafFinishPrimed','DoorLeafFinishPainted','DoorLeafFinishLacquered','FrameFinishPrimed','FrameFinishPainted','FrameFinishLacqured','ExtLiner', 'ExtLinerandFrameFinish', 'ExtLinerandFrameFinishPainted', 'ExtLinerandFrameFinishLacqured', 'VisionPanel2','DoorLeafFinishPrimed2','DoorLeafFinishPainted2','DoorLeafFinishLacquered2','VisionPanel','VisionPanelandFireRatingFD30','VisionPanelandFireRatingFD60', 'VisionPanelandFireRating2FD30', 'VisionPanelandFireRating2FD60', 'DecorativeGroves','DecorativeGrovesLeaf2', 'DoorsetTypeSD','DoorsetTypeSD2','DoorsetTypeDD','DoorsetTypeDD2','OverpanelFanlight','OverpanelFanlightGlazing','SideLight', 'SideLightGlazing','SideLight2', 'SideLight2Glazing', 'MachiningOfDoorFrame', 'FittingOfIntumescentToFrame', 'FittingOfIntumescentToFrameFD60', 'HingeAssembley','HingeAssembleyLeafandHalfDD','LocksetAssembley','PlotLabelDoorsets','FrameAssembley','PalletPackaging','DoorLeafProtectionPlasticSleeve','DoorLeafProtectionPlasticSleeveFD60','LeafSizing','LeafLiping','LeafCalibration','PaintPrep','VGroves','LabourProcessForScallopedFrame','LabourAssemblyFor4SidedFrame','MachiningOfScreenframe','MachiningOfGlazingBead','MachiningOfTransom','MachiningOfSubFrame','CuttingOfScreenframe','CuttingOfGlazingBead','CuttingOfTransom','CuttingOfSubFrame','ScreenAssembley','TransomAssembley','SubFrameAssembley','FittingOfGlass','FittingOfGlazingSystem','FittingOfGlazingBead','SprayFinishOf','SprayFinishOfScreenframe','SprayFinishGlazingBead','SprayFinishOfTransom','SprayFinishOfSubFrame','PallettingPackaging','LoadingOfLorry'];

            $data = '';
            $counter = count($checkId);

            for($i = 0; $i < $counter; $i++){
                $data = $checkId[$i];
                $b->$data = is_null($request->$data) ? 0 : $request->$data;

            }

            // $cid = Auth::user()->id;

            $a->UserId = $usr;
            $b->user_Id = $usr;

            $a->DoorLeafFacingVaneerManMinutes = $request->door_leaf_facing_vaneer_man_minute;
            $a->DoorLeafFacingVaneerMachineMinutes = $request->door_leaf_facing_vaneer_machine_minute;

            $a->DoorLeafFacingKraftPaperManMinutes = $request->door_leaf_facing_kraft_paper_man_minute;
            $a->DoorLeafFacingKraftPaperMachineMinutes = $request->door_leaf_facing_kraft_paper_machine_minute;

            $a->DoorLeafFacingLaminateManMinutes = $request->door_leaf_facing_laminate_man_minute;
            $a->DoorLeafFacingLaminateMachineMinutes = $request->door_leaf_facing_laminate_machine_minute;

            $a->DoorLeafFacingPVCManMinutes = $request->door_leaf_facing_pvc_man_minute;
            $a->DoorLeafFacingPVCMachineMinutes = $request->door_leaf_facing_pvc_machine_minute;

            $a->DoorLeafFinishPrimedManMinutes = $request->door_leaf_finish_primed_man_minute;
            $a->DoorLeafFinishPrimedMachineMinutes = $request->door_leaf_finish_primed_machine_minute;

            $a->DoorLeafFinishPaintedManMinutes = $request->door_leaf_finish_painted_man_minute;
            $a->DoorLeafFinishPaintedMachineMinutes = $request->door_leaf_finish_painted_machine_minute;

            $a->DoorLeafFinishLacqueredManMinutes = $request->door_leaf_finish_lacquered_man_minute;
            $a->DoorLeafFinishLacqueredMachineMinutes = $request->door_leaf_finish_lacquered_machine_minute;

            $a->FrameFinishPrimedManMinutes = $request->frame_finish_primed_man_minute;
            $a->FrameFinishPrimedMachineMinutes = $request->frame_finish_primed_machine_minute;

            $a->FrameFinishPaintedManMinutes = $request->frame_finish_painted_man_minute;
            $a->FrameFinishPaintedMachineMinutes = $request->frame_finish_painted_machine_minute;

            $a->FrameFinishLacquredManMinutes = $request->frame_finish_lacqured_man_minute;
            $a->FrameFinishLacquredMachineMinutes = $request->frame_finish_lacqured_machine_minute;

            $a->ExtLinerManMinutes = $request->ext_liner_man_minute;
            $a->ExtLinerMachineMinutes = $request->ext_liner_machine_minute;

            $a->ExtLinerandFrameFinishPrimedManMinutes = $request->ext_liner_and_frame_finish_primed_man_minute;
            $a->ExtLinerandFrameFinishPrimedMachineMinutes = $request->ext_liner_and_frame_finish_primed_machine_minute;

            $a->ExtLinerandFrameFinishPaintedManMinutes = $request->ext_liner_and_frame_finish_painted_man_minute;
            $a->ExtLinerandFrameFinishPaintedMachineMinutes = $request->ext_liner_and_frame_finish_painted_machine_minute;

            $a->ExtLinerandFrameFinishLacquredManMinutes = $request->ext_liner_and_frame_finish_lacqured_man_minute;
            $a->ExtLinerandFrameFinishLacquredMachineMinutes = $request->ext_liner_and_frame_finish_lacqured_machine_minute;

            $a->VisionPanelManMinutes = $request->vision_panel_man_minute;
            $a->VisionPanelMachineMinutes = $request->vision_panel_machine_minute;

            $a->VisionPanelandFireRatingFD30ManMinutes = $request->vision_panel_and_fireRating_fd30_man_minute;
            $a->VisionPanelandFireRatingFD30MachineMinutes = $request->vision_panel_and_fireRating_fd30_machine_minute;

            $a->VisionPanelandFireRatingFD60ManMinutes = $request->vision_panel_and_fireRating_fd60_man_minute;
            $a->VisionPanelandFireRatingFD60MachineMinutes = $request->vision_panel_and_fireRating_fd60_machine_minute;

            $a->DecorativeGrovesManMinutes = $request->decorative_groves_man_minute;
            $a->DecorativeGrovesMachineMinutes = $request->decorative_groves_machine_minute;

            $a->DecorativeGrovesLeaf2ManMinutes = $request->decorative_groves_Leaf2_man_minute;
            $a->DecorativeGrovesLeaf2MachineMinutes = $request->decorative_groves_Leaf2_machine_minute;


            $a->DoorsetTypeSDManMinutes = $request->doorset_type_sd_man_minute;
            $a->DoorsetTypeSDMachineMinutes = $request->doorset_type_sd_machine_minute;

            $a->DoorsetTypeDDManMinutes = $request->doorset_type_dd_man_minute;
            $a->DoorsetTypeDDMachineMinutes = $request->doorset_type_dd_machine_minute;


            $a->OverpanelFanlightManMinutes = $request->overpanel_fanlight_man_minute;
            $a->OverpanelFanlightMachineMinutes = $request->overpanel_fanlight_machine_minute;

            $a->SideLightManMinutes = $request->side_light_man_minute;
            $a->SideLightMachineMinutes = $request->side_light_machine_minute;

            $a->SideLight2ManMinutes = $request->side_light2_man_minute;
            $a->SideLight2MachineMinutes = $request->side_light2_machine_minute;

            $a->MachiningOfDoorFrameManMinutes = $request->machining_of_door_frame_man_minute;
            $a->MachiningOfDoorFrameMachineMinutes = $request->machining_of_door_frame_machine_minute;

            $a->FittingOfIntumescentToFrameManMinutes = $request->Fitting_of_intumescent_to_frame_man_minute;
            $a->FittingOfIntumescentToFrameMachineMinutes = $request->Fitting_of_intumescent_to_frame_machine_minute;

            $a->FittingOfIntumescentToFrameManMinutesFD60 = $request->Fitting_of_intumescent_to_frame_man_minuteFD60;
            $a->FittingOfIntumescentToFrameMachineMinutesFD60 = $request->Fitting_of_intumescent_to_frame_machine_minuteFD60;

            $a->HingeAssembleyManMinutes = $request->hinge_assembley_man_minute;
            $a->HingeAssembleyMachineMinutes = $request->hinge_assembley_machine_minute;

            $a->HingeAssembleyLeafandHalfDDManMinutes = $request->hinge_assembley_man_minuteLeafandHalfDD;
            $a->HingeAssembleyLeafandHalfDDMachineMinutes = $request->hinge_assembley_machine_minuteLeafandHalfDD;

            $a->LocksetAssembleyManMinutes = $request->lockset_assembley_man_minute;
            $a->LocksetAssembleyMachineMinutes = $request->lockset_assembley_machine_minute;

            $a->PlotLabelDoorsetsyManMinutes = $request->PlotLabelDoorsetsyManMinutes;
            $a->PlotLabelDoorsetsMachineMinutes = $request->PlotLabelDoorsetsMachineMinutes;

            $a->FrameAssembleyManMinutes = $request->FrameAssembleyManMinutes;
            $a->FrameAssembleyMachineMinutes = $request->FrameAssembleyMachineMinutes;

            $a->PalletPackagingManMinutes = $request->PalletPackagingManMinutes;
            $a->PalletPackagingMachineMinutes = $request->PalletPackagingMachineMinutes;

            $a->DoorLeafProtectionPlasticSleeveManMinutes = $request->DoorLeafProtectionPlasticSleeveManMinutes;
            $a->DoorLeafProtectionPlasticSleeveMachineMinutes = $request->DoorLeafProtectionPlasticSleeveMachineMinutes;

            $a->DoorLeafProtectionPlasticSleeveManMinutesFD60 = $request->DoorLeafProtectionPlasticSleeveManMinutesFD60;
            $a->DoorLeafProtectionPlasticSleeveMachineMinutesFD60 = $request->DoorLeafProtectionPlasticSleeveMachineMinutesFD60;

            $a->SideLightGlazingManMinutes = $request->SideLightGlazingManMinutes;
            $a->SideLightGlazingMachineMinutes = $request->SideLightGlazingMachineMinutes;

            $a->SideLight2GlazingManMinutes = $request->SideLight2GlazingManMinutes;
            $a->SideLight2GlazingMachineMinutes = $request->SideLight2GlazingMachineMinutes;

            $a->OverpanelFanlightGlazingManMinutes = $request->OverpanelFanlightGlazingManMinutes;
            $a->OverpanelFanlightGlazingMachineMinutes = $request->OverpanelFanlightGlazingMachineMinutes;

            $a->DoorsetTypeSD2ManMinutes = $request->DoorsetTypeSD2ManMinutes;
            $a->DoorsetTypeSD2MachineMinutes = $request->DoorsetTypeSD2MachineMinutes;

            $a->DoorsetTypeDD2ManMinutes = $request->DoorsetTypeDD2ManMinutes;
            $a->DoorsetTypeDD2MachineMinutes = $request->DoorsetTypeDD2MachineMinutes;

            $a->VisionPanelandFireRating2FD30ManMinutes = $request->VisionPanelandFireRating2FD30ManMinutes;
            $a->VisionPanelandFireRating2FD30MachineMinutes = $request->VisionPanelandFireRating2FD30MachineMinutes;

            $a->VisionPanelandFireRating2FD60ManMinutes = $request->VisionPanelandFireRating2FD60ManMinutes;
            $a->VisionPanelandFireRating2FD60MachineMinutes = $request->VisionPanelandFireRating2FD60MachineMinutes;

            $a->VisionPanel2ManMinutes = $request->VisionPanel2ManMinutes;
            $a->VisionPanel2MachineMinutes = $request->VisionPanel2MachineMinutes;

            $a->DoorLeafFinishPrimed2ManMinutes = $request->DoorLeafFinishPrimed2ManMinutes;
            $a->DoorLeafFinishPrimed2MachineMinutes = $request->DoorLeafFinishPrimed2MachineMinutes;

            $a->DoorLeafFinishPainted2ManMinutes = $request->DoorLeafFinishPainted2ManMinutes;
            $a->DoorLeafFinishPainted2MachineMinutes = $request->DoorLeafFinishPainted2MachineMinutes;

            $a->DoorLeafFinishLacquered2ManMinutes = $request->DoorLeafFinishLacquered2ManMinutes;
            $a->DoorLeafFinishLacquered2MachineMinutes = $request->DoorLeafFinishLacquered2MachineMinutes;

            $a->LeafSizingManMinutes = $request->LeafSizingManMinutes;
            $a->LeafSizingMachineMinutes = $request->LeafSizingMachineMinutes;

            $a->LeafLipingManMinutes = $request->LeafLipingManMinutes;
            $a->LeafLipingMachineMinutes = $request->LeafLipingMachineMinutes;

            $a->LeafCalibrationManMinutes = $request->LeafCalibrationManMinutes;
            $a->LeafCalibrationMachineMinutes = $request->LeafCalibrationMachineMinutes;

            $a->PaintPrepManMinutes = $request->PaintPrepManMinutes;
            $a->PaintPrepMachineMinutes = $request->PaintPrepMachineMinutes;

            $a->MachiningOfScreenframeManMinutes = $request->MachiningOfScreenframeManMinutes;
            $a->MachiningOfScreenframeMachineMinutes = $request->MachiningOfScreenframeMachineMinutes;

            $a->MachiningOfGlazingBeadManMinutes = $request->MachiningOfGlazingBeadManMinutes;
            $a->MachiningOfGlazingBeadMachineMinutes = $request->MachiningOfGlazingBeadMachineMinutes;

            $a->MachiningOfTransomManMinutes = $request->MachiningOfTransomManMinutes;
            $a->MachiningOfTransomMachineMinutes = $request->MachiningOfTransomMachineMinutes;

            $a->MachiningOfSubFrameManMinutes = $request->MachiningOfSubFrameManMinutes;
            $a->MachiningOfSubFrameMachineMinutes = $request->MachiningOfSubFrameMachineMinutes;

            $a->CuttingOfScreenframeManMinutes = $request->CuttingOfScreenframeManMinutes;
            $a->CuttingOfScreenframeMachineMinutes = $request->CuttingOfScreenframeMachineMinutes;

            $a->CuttingOfGlazingBeadManMinutes = $request->CuttingOfGlazingBeadManMinutes;
            $a->CuttingOfGlazingBeadMachineMinutes = $request->CuttingOfGlazingBeadMachineMinutes;

            $a->CuttingOfTransomManMinutes = $request->CuttingOfTransomManMinutes;
            $a->CuttingOfTransomMachineMinutes = $request->CuttingOfTransomMachineMinutes;

            $a->CuttingOfSubFrameManMinutes = $request->CuttingOfSubFrameManMinutes;
            $a->CuttingOfSubFrameMachineMinutes = $request->CuttingOfSubFrameMachineMinutes;

            $a->ScreenAssembleyManMinutes = $request->ScreenAssembleyManMinutes;
            $a->ScreenAssembleyMachineMinutes = $request->ScreenAssembleyMachineMinutes;

            $a->TransomAssembleyManMinutes = $request->TransomAssembleyManMinutes;
            $a->TransomAssembleyMachineMinutes = $request->TransomAssembleyMachineMinutes;

            $a->SubFrameAssembleyManMinutes = $request->SubFrameAssembleyManMinutes;
            $a->SubFrameAssembleyMachineMinutes = $request->SubFrameAssembleyMachineMinutes;

            $a->FittingOfGlassManMinutes = $request->FittingOfGlassManMinutes;
            $a->FittingOfGlassMachineMinutes = $request->FittingOfGlassMachineMinutes;

            $a->FittingOfGlazingSystemManMinutes = $request->FittingOfGlazingSystemManMinutes;
            $a->FittingOfGlazingSystemMachineMinutes = $request->FittingOfGlazingSystemMachineMinutes;

            $a->FittingOfGlazingBeadManMinutes = $request->FittingOfGlazingBeadManMinutes;
            $a->FittingOfGlazingBeadMachineMinutes = $request->FittingOfGlazingBeadMachineMinutes;

            $a->SprayFinishOfManMinutes = $request->SprayFinishOfManMinutes;
            $a->SprayFinishOfMachineMinutes = $request->SprayFinishOfMachineMinutes;

            $a->SprayFinishOfScreenframeManMinutes = $request->SprayFinishOfScreenframeManMinutes;
            $a->SprayFinishOfScreenframeMachineMinutes = $request->SprayFinishOfScreenframeMachineMinutes;

            $a->SprayFinishGlazingBeadManMinutes = $request->SprayFinishGlazingBeadManMinutes;
            $a->SprayFinishGlazingBeadMachineMinutes = $request->SprayFinishGlazingBeadMachineMinutes;

            $a->SprayFinishOfTransomManMinutes = $request->SprayFinishOfTransomManMinutes;
            $a->SprayFinishOfTransomMachineMinutes = $request->SprayFinishOfTransomMachineMinutes;

            $a->SprayFinishOfSubFrameManMinutes = $request->SprayFinishOfSubFrameManMinutes;
            $a->SprayFinishOfSubFrameMachineMinutes = $request->SprayFinishOfSubFrameMachineMinutes;

            $a->PallettingPackagingManMinutes = $request->PallettingPackagingManMinutes;
            $a->PallettingPackagingMachineMinutes = $request->PallettingPackagingMachineMinutes;

            $a->LoadingOfLorryManMinutes = $request->LoadingOfLorryManMinutes;
            $a->LoadingOfLorryMachineMinutes = $request->LoadingOfLorryMachineMinutes;

            $a->VGrovesManMinutes = $request->VGrovesManMinutes;
            $a->VGrovesMachineMinutes = $request->VGrovesMachineMinutes;

            $a->LabourProcessForScallopedFrameManMinutes = $request->LabourProcessForScallopedFrameManMinutes;
            $a->LabourProcessForScallopedFrameMachineMinutes = $request->LabourProcessForScallopedFrameMachineMinutes;

            $a->LabourAssemblyFor4SidedFrameManMinutes = $request->LabourAssemblyFor4SidedFrameManMinutes;
            $a->LabourAssemblyFor4SidedFrameMachineMinutes = $request->LabourAssemblyFor4SidedFrameMachineMinutes;

            $a->updated_at = date('Y-m-d H:i:s');
            $a->save();

            $b->generalLabourCostId = $a->id;
            $b->save();

            $types = $request->type;
            $labour_cost_per_man = $request->labour_cost_per_man;
            $labour_cost_per_machine = $request->labour_cost_per_machine;

            foreach ($types as $key => $type) {
                $c = new GeneralLabourCostSetting();
                $c->type = $type;
                $c->labour_cost_per_man = $labour_cost_per_man[$key];
                $c->labour_cost_per_machine = $labour_cost_per_machine[$key];
                $c->UserId = $usr;
                $c->save();

                $d = new GeneralLabourData();
                $d->type = $type;
                $d->bom_setting_id = $c->id;
                $d->general_labour_cost_id = $a->id;
                $d->save();
            }
        }


        if(!is_null($update_val)){
            return redirect()->back()->with('success', 'The general setting update successfully!');
        }
        else
        {
            return redirect()->back()->withInput()->with('success', 'The general setting added successfully!');
        }





    }

    public function subcostsetting(Request $request)
    {
        $valid = $request->validate([
            'name' => 'required'
        ]);
        $update_val = $request->updval;
        if(!is_null($update_val)){
            $a = SettingBOMCost::find($update_val);
        } else {
            $a = new SettingBOMCost;
            $a->UserId = Auth::user()->id;
            $a->created_at = date('Y-m-d H:i:s');
        }
        
        $a->name = $request->name;
        $a->parent = $request->parent;
        $a->width = $request->width;
        $a->height = $request->height;
        $a->depth = $request->depth;
        $a->cost = $request->cost;
        $a->updated_at = date('Y-m-d H:i:s');
        $a->save();
        if(!is_null($update_val)){
            return redirect()->back()->with('success', 'Cost setting of `'.$request->name.'` update successfully!');
        }
        else
        {
            return redirect()->back()->withInput()->with('success', 'Cost setting of `'.$request->name.'` added successfully!');
        }
    }
    
    public function deletecostsetting(Request $request)
    {
        $id = $request->delete;
        SettingBOMCost::where('id',$id)->delete();
        return redirect()->back()->with('success', 'Cost setting deleted successfully!');
    }
    
    public function updcostsetting(Request $request)
    {
        $id = $request->upd;
        $upd = SettingBOMCost::find($id);
         return redirect()->back()->with('upd',$upd);
        // return redirect()->back()->with('success', 'Cost setting updated successfully!');
    }
}
